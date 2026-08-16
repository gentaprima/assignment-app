<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'requires_attachment',
        'is_paid',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_attachment' => 'boolean',
            'is_paid' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function leaveRequests()
    {
        return $this->hasMany(
            LeaveRequest::class
        );
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}