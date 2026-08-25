<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceReportExport;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\ScheduleAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | TANGGAL
        |--------------------------------------------------------------------------
        */

        $startDate = $request->input(
            'start_date',
            now()->toDateString()
        );

        $endDate = $request->input(
            'end_date',
            now()->toDateString()
        );


        /*
        |--------------------------------------------------------------------------
        | CABANG
        |--------------------------------------------------------------------------
        */

        $branches = collect();

        if ($user->role === 'admin') {

            $branches = Branch::where('is_active', true)
                ->orderBy('name')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | QUERY JADWAL
        |--------------------------------------------------------------------------
        |
        | Jadwal menjadi sumber utama laporan.
        | Attendance hanya sebagai data tambahan.
        |
        */

        $query = ScheduleAssignment::with([
            'user',
            'shift',
            'attendance',
            'weeklySchedule.branch',
        ])
            ->whereBetween('work_date', [
                $startDate,
                $endDate,
            ]);


        /*
        |--------------------------------------------------------------------------
        | PIC HANYA CABANG SENDIRI
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'pic') {

            $query->whereHas('weeklySchedule', function ($q) use ($user) {

                $q->where('branch_id', $user->branch_id);

            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER CABANG ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'admin' &&
            $request->filled('branch_id')
        ) {

            $query->whereHas('weeklySchedule', function ($q) use ($request) {

                $q->where('branch_id', $request->branch_id);

            });
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $assignments = $query
            ->orderBy('work_date')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TENTUKAN STATUS LAPORAN
        |--------------------------------------------------------------------------
        |
        | leave     = izin
        | late      = terlambat
        | present   = hadir
        | absent    = tidak hadir
        | off       = tidak dihitung sebagai absensi
        |
        */

        $reports = $assignments->map(function ($assignment) {

            $attendance = $assignment->attendance;

            /*
            |--------------------------------------------------------------------------
            | OFF
            |--------------------------------------------------------------------------
            */

            if ($assignment->status === 'off') {

                $reportStatus = 'off';

            }

            /*
            |--------------------------------------------------------------------------
            | IZIN
            |--------------------------------------------------------------------------
            */ elseif ($assignment->status === 'leave') {

                $reportStatus = 'leave';

            }

            /*
            |--------------------------------------------------------------------------
            | BELUM ABSEN
            |--------------------------------------------------------------------------
            |
            | Kalau tidak ada attendance ATAU check_in_at kosong,
            | maka TIDAK HADIR.
            |
            */ elseif (
                !$attendance ||
                !$attendance->check_in_at
            ) {

                $reportStatus = 'absent';

            }

            /*
            |--------------------------------------------------------------------------
            | SUDAH ABSEN
            |--------------------------------------------------------------------------
            */ elseif ($attendance->check_in_at) {

                if ($attendance->status === 'late') {

                    $reportStatus = 'late';

                } else {

                    $reportStatus = 'present';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | FALLBACK
            |--------------------------------------------------------------------------
            */ else {

                $reportStatus = 'absent';
            }


            $assignment->report_status = $reportStatus;

            return $assignment;
        });



        /*
        |--------------------------------------------------------------------------
        | BUANG HARI OFF
        |--------------------------------------------------------------------------
        |
        | Jadwal OFF tidak dianggap sebagai absensi.
        |
        */

        $reports = $reports
            ->where('report_status', '!=', 'off')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $reports = $reports
                ->where(
                    'report_status',
                    $request->status
                )
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalAttendance = $reports->count();

        $totalPresent = $reports
            ->where('report_status', 'present')
            ->count();

        $totalLate = $reports
            ->where('report_status', 'late')
            ->count();

        $totalAbsent = $reports
            ->where('report_status', 'absent')
            ->count();

        $totalLeave = $reports
            ->where('report_status', 'leave')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('reports.index', compact(
            'reports',
            'branches',
            'startDate',
            'endDate',
            'totalAttendance',
            'totalPresent',
            'totalLate',
            'totalAbsent',
            'totalLeave'
        ));
    }

    public function export(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'branch_id' => [
                'nullable',
                'exists:branches,id',
            ],

            'status' => [
                'nullable',
                'in:present,late,absent,leave',
            ],

        ]);


        $user = $request->user();


        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        */

        $startDate =
            $request->input(
                'start_date',
                now()
                    ->startOfMonth()
                    ->toDateString()
            );

        $endDate =
            $request->input(
                'end_date',
                now()
                    ->endOfMonth()
                    ->toDateString()
            );


        /*
        |--------------------------------------------------------------------------
        | FILTER CABANG
        |--------------------------------------------------------------------------
        */

        $branchId = null;

        if ($user->role === 'admin') {

            if ($request->filled('branch_id')) {

                $branchId =
                    (int) $request->branch_id;
            }

        } else {

            $branchId =
                $user->branch_id;
        }


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $filename =
            'laporan-absensi_'
            . $startDate
            . '_sampai_'
            . $endDate
            . '.xlsx';


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        return Excel::download(

            new AttendanceReportExport(

                userId:
                $user->id,

                userRole:
                $user->role,

                userBranchId:
                $user->branch_id,

                startDate:
                $startDate,

                endDate:
                $endDate,

                branchId:
                $branchId,

                status:
                $request->input('status'),

            ),

            $filename

        );
    }

}