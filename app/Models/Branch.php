<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
        'latitude',
        'longitude',
        'radius',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'radius' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Employees
    |--------------------------------------------------------------------------
    */

    public function employeeBranches()
    {
        return $this->hasMany(EmployeeBranch::class);
    }

    public function employees()
    {
        return $this->belongsToMany(
            User::class,
            'employee_branches'
        )->withPivot([
            'start_date',
            'end_date',
            'is_primary',
        ])->withTimestamps();
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

    public function pics()
    {
        return $this->belongsToMany(
            User::class,
            'branch_pic_assignments'
        )->withPivot([
            'start_date',
            'end_date',
        ])->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Weekly Schedule
    |--------------------------------------------------------------------------
    */

    public function weeklySchedules()
    {
        return $this->hasMany(WeeklySchedule::class);
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

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'branch_id');
    }
}