<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceCorrection extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'user_id',

        'requested_check_in_at',
        'requested_check_out_at',

        'reason',
        'attachment',

        'status',

        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'requested_check_in_at' => 'datetime',
            'requested_check_out_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function attendance()
    {
        return $this->belongsTo(
            Attendance::class
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}