<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeeklySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'week_start_date',
        'week_end_date',
        'created_by',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'week_start_date' => 'date',
            'week_end_date' => 'date',
            'published_at' => 'datetime',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function assignments()
    {
        return $this->hasMany(
            ScheduleAssignment::class
        );
    }
}