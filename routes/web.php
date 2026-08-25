<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return match (auth()->user()->role) {

        'admin' => redirect()->route('admin.dashboard'),

        'manager' => redirect()->route('manager.dashboard'),

        'pic' => redirect()->route('pic.dashboard'),

        'employee' => redirect()->route('employee.dashboard'),

        default => abort(403, 'Role pengguna tidak valid.'),

    };
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    Route::get('/attendance', [
        AttendanceController::class,
        'index'
    ])->name('attendance');

    Route::post('/attendance/check-in', [
        AttendanceController::class,
        'checkIn'
    ])->name('attendance.check-in');

    Route::post('/attendance/check-out', [
        AttendanceController::class,
        'checkOut'
    ])->name('attendance.check-out');

    Route::get('/my-schedule', [
        ScheduleController::class,
        'mySchedule'
    ])->name('my.schedule');

    Route::get('/reports', [
        ReportController::class,
        'index'
    ])->name('reports.index');

    Route::get('/reports/export', [
        ReportController::class,
        'export'
    ])->name('reports.export');

    // Employee & PIC
    Route::middleware('role:employee,pic')->group(function () {

        Route::get('/izin', [LeaveRequestController::class, 'index'])
            ->name('leave-requests.index');

        Route::post('/izin', [LeaveRequestController::class, 'store'])
            ->name('leave-requests.store');

        Route::delete('/izin/{permission}', [LeaveRequestController::class, 'destroy'])
            ->name('leave-requests.destroy');
    });

    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/permissions', [LeaveRequestController::class, 'adminIndex'])
            ->name('leave-requests.index');

        Route::post('/permissions/{permission}/approve', [LeaveRequestController::class, 'approve'])
            ->name('leave-requests.approve');

        Route::post('/permissions/{permission}/reject', [LeaveRequestController::class, 'reject'])
            ->name('leave-requests.reject');
    });


    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [
        DashboardController::class,
        'admin'
    ])->middleware('role:admin')
        ->name('admin.dashboard');


    Route::middleware(['auth'])->group(function () {

        Route::get('/admin/employees', [EmployeeController::class, 'index'])
            ->name('admin.employees');

        Route::post('/admin/employees', [EmployeeController::class, 'store'])
            ->name('admin.employees.store');

        Route::put('/admin/employees/{employee}', [EmployeeController::class, 'update'])
            ->name('admin.employees.update');

        Route::delete('/admin/employees/{employee}', [EmployeeController::class, 'destroy'])
            ->name('admin.employees.destroy');

    });

    Route::middleware(['auth'])->group(function () {

        Route::get('/admin/branches', [BranchController::class, 'index'])
            ->name('admin.branches');

        Route::post('/admin/branches', [BranchController::class, 'store'])
            ->name('admin.branches.store');

        Route::put('/admin/branches/{branch}', [BranchController::class, 'update'])
            ->name('admin.branches.update');

        Route::delete('/admin/branches/{branch}', [BranchController::class, 'destroy'])
            ->name('admin.branches.destroy');

    });

    Route::middleware(['auth'])->group(function () {

        Route::get('/admin/schedules', [ScheduleController::class, 'index'])
            ->name('admin.schedules');

        Route::post('/admin/schedules', [ScheduleController::class, 'store'])
            ->name('admin.schedules.store');

        Route::delete('/admin/schedules/{assignment}', [ScheduleController::class, 'destroy'])
            ->name('admin.schedules.destroy');

        Route::post(
            '/admin/schedules/{weeklySchedule}/publish',
            [ScheduleController::class, 'publish']
        )->name('admin.schedules.publish');

    });

    /*
    |--------------------------------------------------------------------------
    | Manager
    |--------------------------------------------------------------------------
    */

    Route::get('/manager/dashboard', [
        DashboardController::class,
        'manager'
    ])->middleware('role:manager')
        ->name('manager.dashboard');


    /*
    |--------------------------------------------------------------------------
    | PIC
    |--------------------------------------------------------------------------
    */

    Route::get('/pic/dashboard', [
        DashboardController::class,
        'pic'
    ])->middleware('role:pic')
        ->name('pic.dashboard');


    // =========================
    // JADWAL PIC
    // =========================

    Route::middleware('role:pic')->prefix('pic')->name('pic.')->group(function () {

        Route::get('/schedules', [
            ScheduleController::class,
            'index'
        ])->name('schedules');


        Route::post('/schedules', [
            ScheduleController::class,
            'store'
        ])->name('schedules.store');


        Route::delete('/schedules/{assignment}', [
            ScheduleController::class,
            'destroy'
        ])->name('schedules.destroy');


        Route::post('/schedules/{weeklySchedule}/publish', [
            ScheduleController::class,
            'publish'
        ])->name('schedules.publish');

    });

    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    Route::get('/employee/dashboard', [
        DashboardController::class,
        'employee'
    ])->middleware('role:employee')
        ->name('employee.dashboard');
    Route::middleware(['auth', 'role:employee'])->group(function () {

        Route::get('/employee/schedules', [
            ScheduleController::class,
            'mySchedule'
        ])->name('employee.schedules');

    });
});

require __DIR__ . '/auth.php';
