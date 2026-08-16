<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduleAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'weekly_schedule_id',
        'user_id',
        'shift_id',
        'work_date',
        'status',
        'notes',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    public function weeklySchedule()
    {
        return $this->belongsTo(
            WeeklySchedule::class
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }
}