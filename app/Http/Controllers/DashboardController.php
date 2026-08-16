<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\Branch;
use App\Models\ScheduleAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function admin()
    {
        $today = now()->toDateString();

        // =========================
        // TOTAL KARYAWAN
        // =========================

        $totalEmployees = User::whereIn('role', ['employee','pic'])->count();


        // =========================
        // TOTAL CABANG
        // =========================

        $totalBranches = Branch::count();

        $activeBranches = Branch::where('is_active', true)->count();


        // =========================
        // ABSENSI HARI INI
        // =========================

        $todayAttendances = Attendance::whereDate(
            'check_in_at',
            $today
        );


        // Karyawan yang sudah absen masuk
        $totalPresent = (clone $todayAttendances)
            ->whereNotNull('check_in_at')
            ->count();


        // Karyawan terlambat
        $totalLate = (clone $todayAttendances)
            ->where('status', 'late')
            ->count();


        // Belum melakukan absensi
        $totalAbsent = max(
            $totalEmployees - $totalPresent,
            0
        );


        // =========================
        // PERBAIKAN ABSENSI
        // =========================

        $pendingCorrections = AttendanceCorrection::where(
            'status',
            'pending'
        )->count();


        // =========================
        // STATUS CABANG
        // =========================

        $branches = Branch::withCount([
            'users as employee_count' => function ($query) {
                $query->where('role', 'employee');
            }
        ])
            ->latest()
            ->take(5)
            ->get();


        return view('dashboard.admin', compact(
            'totalEmployees',
            'totalBranches',
            'activeBranches',
            'totalPresent',
            'totalLate',
            'totalAbsent',
            'pendingCorrections',
            'branches'
        ));
    }

    public function manager()
    {
        return view('dashboard.manager');
    }

    public function pic()
    {
        $user = auth()->user();

        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | CABANG PIC
        |--------------------------------------------------------------------------
        */

        $branch = $user->branch;


        /*
        |--------------------------------------------------------------------------
        | KARYAWAN CABANG
        |--------------------------------------------------------------------------
        */

        $employees = User::query()
            ->where('branch_id', $user->branch_id)
            ->whereIn('role', ['employee', 'pic'])
            ->orderBy('name')
            ->get();


        $totalEmployees = $employees->count();


        /*
        |--------------------------------------------------------------------------
        | JADWAL HARI INI
        |--------------------------------------------------------------------------
        */

        $todayAssignments = ScheduleAssignment::query()
            ->with([
                'user',
                'shift',
                'attendance',
            ])
            ->where('work_date', $today)
            ->whereHas('user', function ($query) use ($user) {
                $query->where('branch_id', $user->branch_id)
                      ->whereIn('role', ['employee', 'pic']);
            })
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ABSENSI HARI INI
        |--------------------------------------------------------------------------
        */

        $todayAttendances = Attendance::query()
            ->with('user')
            ->where('branch_id', $user->branch_id)
            ->whereDate('check_in_at', $today)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $hadir = $todayAssignments
            ->filter(function ($assignment) {
                return $assignment->status === 'scheduled'
                    && $assignment->attendance
                    && $assignment->attendance->check_in_at;
            })
            ->count();


        $izin = $todayAssignments
            ->where('status', 'leave')
            ->count();


        $belumAbsen = $todayAssignments
            ->filter(function ($assignment) {
                return $assignment->status === 'scheduled'
                    && ! $assignment->attendance;
            })
            ->count();


        $off = $todayAssignments
            ->where('status', 'off')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TERLAMBAT
        |--------------------------------------------------------------------------
        */

        $terlambat = $todayAssignments
            ->filter(function ($assignment) {

                if (
                    $assignment->status !== 'scheduled' ||
                    ! $assignment->attendance ||
                    ! $assignment->attendance->check_in_at ||
                    ! $assignment->shift
                ) {
                    return false;
                }

                $checkIn = Carbon::parse(
                    $assignment->attendance->check_in_at
                );

                $shiftStart = Carbon::parse(
                    $assignment->shift->start_time
                );

                return $checkIn->gt($shiftStart);
            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | JADWAL MINGGU INI
        |--------------------------------------------------------------------------
        */

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyAssignments = ScheduleAssignment::query()
            ->with([
                'user',
                'shift',
                'attendance',
            ])
            ->whereBetween('work_date', [
                $startOfWeek,
                $endOfWeek
            ])
            ->whereHas('user', function ($query) use ($user) {
                $query->where('branch_id', $user->branch_id)
                      ->whereIn('role', ['employee', 'pic']);
            })
            ->orderBy('work_date')
            ->get();


        $totalWeeklySchedules = $weeklyAssignments->count();

        $scheduledWeekly = $weeklyAssignments
            ->where('status', 'scheduled')
            ->count();

        $offWeekly = $weeklyAssignments
            ->where('status', 'off')
            ->count();

        $leaveWeekly = $weeklyAssignments
            ->where('status', 'leave')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK VIEW
        |--------------------------------------------------------------------------
        */

        return view('dashboard.pic', compact(
            'user',
            'branch',
            'employees',

            'totalEmployees',

            'todayAssignments',
            'todayAttendances',

            'hadir',
            'izin',
            'belumAbsen',
            'off',
            'terlambat',

            'weeklyAssignments',
            'totalWeeklySchedules',
            'scheduledWeekly',
            'offWeekly',
            'leaveWeekly',

            'startOfWeek',
            'endOfWeek'
        ));
    }

    public function employee()
    {
        $user = auth()->user();

        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | JADWAL HARI INI
        |--------------------------------------------------------------------------
        */

        $todaySchedule = ScheduleAssignment::with([
            'shift',
            'weeklySchedule.branch',
            'attendance',
        ])
        ->where('user_id', $user->id)
        ->whereDate('work_date', $today)
        ->first();


        /*
        |--------------------------------------------------------------------------
        | ABSENSI HARI INI
        |--------------------------------------------------------------------------
        */

        $todayAttendance = Attendance::with('branch')
            ->where('user_id', $user->id)
            ->whereDate('check_in_at', $today)
            ->first();


        /*
        |--------------------------------------------------------------------------
        | JADWAL MINGGU INI
        |--------------------------------------------------------------------------
        */

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklySchedules = ScheduleAssignment::with([
            'shift',
            'weeklySchedule.branch',
            'attendance',
        ])
        ->where('user_id', $user->id)
        ->whereBetween('work_date', [
            $startOfWeek->toDateString(),
            $endOfWeek->toDateString(),
        ])
        ->orderBy('work_date')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK MINGGU INI
        |--------------------------------------------------------------------------
        */

        $totalSchedule = $weeklySchedules->count();

        $totalPresent = $weeklySchedules
            ->filter(function ($schedule) {
                return $schedule->attendance?->check_in_at !== null;
            })
            ->count();

        $totalOff = $weeklySchedules
            ->where('status', 'off')
            ->count();

        $totalLeave = $weeklySchedules
            ->where('status', 'leave')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | AKTIVITAS ABSENSI TERBARU
        |--------------------------------------------------------------------------
        */

        $recentAttendances = Attendance::with('branch')
            ->where('user_id', $user->id)
            ->latest('check_in_at')
            ->take(5)
            ->get();


        return view('dashboard.employee', compact(
            'todaySchedule',
            'todayAttendance',
            'weeklySchedules',
            'totalSchedule',
            'totalPresent',
            'totalOff',
            'totalLeave',
            'recentAttendances'
        ));
    }
    

    public function addEmployee()
    {
        return view('dashboard.add-employee');
    }
}