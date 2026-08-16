<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'employee_number',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
        'branch_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Employee Branch
    |--------------------------------------------------------------------------
    */

    public function employeeBranches()
    {
        return $this->hasMany(EmployeeBranch::class);
    }

    /*
    |--------------------------------------------------------------------------
    | PIC
    |--------------------------------------------------------------------------
    */

    public function picAssignments()
    {
        return $this->hasMany(BranchPicAssignment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Schedule
    |--------------------------------------------------------------------------
    */

    public function createdWeeklySchedules()
    {
        return $this->hasMany(
            WeeklySchedule::class,
            'created_by'
        );
    }

    public function scheduleAssignments()
    {
        return $this->hasMany(ScheduleAssignment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Leave
    |--------------------------------------------------------------------------
    */

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Correction
    |--------------------------------------------------------------------------
    */

    public function attendanceCorrections()
    {
        return $this->hasMany(AttendanceCorrection::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Approval
    |--------------------------------------------------------------------------
    */

    public function approvedLeaveRequests()
    {
        return $this->hasMany(
            LeaveRequest::class,
            'approved_by'
        );
    }

    public function approvedAttendanceCorrections()
    {
        return $this->hasMany(
            AttendanceCorrection::class,
            'approved_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Audit
    |--------------------------------------------------------------------------
    */

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
