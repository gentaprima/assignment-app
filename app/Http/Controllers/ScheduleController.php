<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Shift;
use App\Models\User;
use App\Models\WeeklySchedule;
use App\Models\ScheduleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Halaman jadwal
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Minggu yang dipilih
        $weekStart = $request->filled('week')
            ? Carbon::parse($request->week)->startOfWeek(Carbon::MONDAY)
            : now()->startOfWeek(Carbon::MONDAY);

        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        /*
        |--------------------------------------------------------------------------
        | CABANG
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'pic') {

            $branches = Branch::where('id', $user->branch_id)
                ->where('is_active', true)
                ->get();

        } else {

            $branches = Branch::where('is_active', true)
                ->orderBy('name')
                ->get();

        }


        /*
        |--------------------------------------------------------------------------
        | CABANG YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        $branchId = $request->branch_id;

        if ($user->role === 'pic') {

            $branchId = $user->branch_id;

        } elseif (!$branchId && $branches->count()) {

            $branchId = $branches->first()->id;

        }


        /*
        |--------------------------------------------------------------------------
        | WEEKLY SCHEDULE
        |--------------------------------------------------------------------------
        */

        $weeklySchedule = null;

        if ($branchId) {

            $weeklySchedule = WeeklySchedule::with([
                'assignments.user',
                'assignments.shift',
            ])
                ->where('branch_id', $branchId)
                ->whereDate('week_start_date', $weekStart->toDateString())
                ->first();

        }


        /*
        |--------------------------------------------------------------------------
        | KARYAWAN
        |--------------------------------------------------------------------------
        */

        $employees = collect();

        if ($user->role === 'admin') {

            // ADMIN:
            // Bisa memilih employee dari semua cabang
            $employees = User::whereIn('role', ['employee', 'pic'])
                ->orderBy('name')
                ->get();

        } elseif ($user->role === 'pic') {

            // PIC:
            // Hanya bisa melihat employee/PIC di cabangnya sendiri
            $employees = User::whereIn('role', ['employee', 'pic'])
                ->where('branch_id', $user->branch_id)
                ->orderBy('name')
                ->get();

        }


        /*
        |--------------------------------------------------------------------------
        | SHIFT
        |--------------------------------------------------------------------------
        */

        $shifts = Shift::where('is_active', true)
            ->orderBy('start_time')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DATA PER HARI
        |--------------------------------------------------------------------------
        */

        $days = collect();

        for ($i = 0; $i < 7; $i++) {

            $date = $weekStart->copy()->addDays($i);

            $assignments = collect();

            if ($weeklySchedule) {

                $assignments = $weeklySchedule->assignments
                    ->filter(function ($assignment) use ($date) {

                        return $assignment->work_date->isSameDay($date);

                    })
                    ->values();

            }

            $days->push([
                'date' => $date,
                'assignments' => $assignments,
            ]);
        }



        return view('admin.schedules.index', compact(
            'weekStart',
            'weekEnd',
            'branches',
            'branchId',
            'weeklySchedule',
            'employees',
            'shifts',
            'days'
        ));
    }


    /**
     * Simpan jadwal
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'branch_id' => [
                'required',
                'exists:branches,id',
            ],

            'user_id' => [
                'required',
                'exists:users,id',
            ],

            'work_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'nullable',
                'date_format:H:i',
                'required_if:status,scheduled',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
                'required_if:status,scheduled',
            ],

            'status' => [
                'required',
                'in:scheduled,off,leave',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | PIC hanya boleh mengelola cabangnya
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'pic' &&
            (int) $validated['branch_id'] !== (int) $user->branch_id
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan karyawan valid
        |--------------------------------------------------------------------------
        |
        | PENTING:
        | Untuk kebutuhan kamu sekarang, employee boleh punya
        | cabang utama berbeda dengan cabang tempat dia dijadwalkan.
        |
        */

        $employee = User::findOrFail(
            $validated['user_id']
        );


        /*
        |--------------------------------------------------------------------------
        | PIC tidak boleh membuat jadwal employee di cabang lain
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'pic') {

            // PIC hanya boleh membuat jadwal
            // untuk cabangnya sendiri.

            if (
                (int) $validated['branch_id']
                !==
                (int) $user->branch_id
            ) {
                abort(403);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Tanggal kerja
        |--------------------------------------------------------------------------
        */

        $workDate = Carbon::parse(
            $validated['work_date']
        );


        /*
        |--------------------------------------------------------------------------
        | Tentukan minggu
        |--------------------------------------------------------------------------
        */

        $weekStart = $workDate
            ->copy()
            ->startOfWeek(Carbon::MONDAY);

        $weekEnd = $workDate
            ->copy()
            ->endOfWeek(Carbon::SUNDAY);


        /*
        |--------------------------------------------------------------------------
        | Simpan jadwal
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated, $weekStart, $weekEnd, $user) {

            /*
            |--------------------------------------------------------------------------
            | Buat weekly schedule jika belum ada
            |--------------------------------------------------------------------------
            */

            $weeklySchedule = WeeklySchedule::firstOrCreate(
                [
                    'branch_id' => $validated['branch_id'],
                    'week_start_date' => $weekStart->toDateString(),
                ],
                [
                    'week_end_date' => $weekEnd->toDateString(),
                    'created_by' => $user->id,
                    'status' => 'draft',
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | Simpan assignment
            |--------------------------------------------------------------------------
            */

            ScheduleAssignment::updateOrCreate(
                [
                    'weekly_schedule_id' => $weeklySchedule->id,
                    'user_id' => $validated['user_id'],
                    'work_date' => $validated['work_date'],
                ],
                [
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],

                    // Karena sudah tidak menggunakan shift
                    'shift_id' => null,

                    'status' => $validated['status'],

                    'notes' => $validated['notes'] ?? null,
                ]
            );
        });


        return back()->with(
            'success',
            'Jadwal berhasil disimpan.'
        );
    }


    /**
     * Hapus jadwal
     */
    public function destroy(ScheduleAssignment $assignment)
    {
        $user = Auth::user();

        $assignment->load('weeklySchedule');

        if (
            $user->role === 'pic' &&
            $assignment->weeklySchedule->branch_id != $user->branch_id
        ) {

            abort(403);

        }


        $assignment->delete();

        return back()->with(
            'success',
            'Jadwal berhasil dihapus.'
        );
    }

    public function publish(WeeklySchedule $weeklySchedule)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | PIC HANYA BOLEH PUBLIKASI CABANG SENDIRI
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'pic' &&
            (int) $weeklySchedule->branch_id !== (int) $user->branch_id
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN ADA JADWAL
        |--------------------------------------------------------------------------
        */

        if ($weeklySchedule->assignments()->count() === 0) {

            return back()->with(
                'error',
                'Jadwal belum memiliki assignment.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PUBLISH
        |--------------------------------------------------------------------------
        */

        $weeklySchedule->update([
            'status' => 'published',
            'published_at' => now(),
        ]);


        return back()->with(
            'success',
            'Jadwal berhasil dipublikasikan.'
        );
    }

    public function mySchedule(Request $request)
    {
        $user = auth()->user();

        $weekStart = $request->input('week_start');

        if ($weekStart) {
            $startDate = \Carbon\Carbon::parse($weekStart)->startOfWeek();
        } else {
            $startDate = now()->startOfWeek();
        }

        $endDate = $startDate->copy()->endOfWeek();

        $schedules = \App\Models\ScheduleAssignment::with([
            'shift',
            'weeklySchedule.branch',
        ])
            ->where('user_id', $user->id)
            ->whereBetween('work_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->orderBy('work_date')
            ->get();

        return view('employees.schedules', compact(
            'schedules',
            'startDate',
            'endDate'
        ));
    }
}