<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'date', 
        'status', 
        'teacher_id'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function details()
    {
        return $this->hasMany(AttendanceDetail::class);
    }

    public function getTotalPresentAttribute()
    {
        return $this->details->where('status', 'present')->count();
    }

    public function getTotalAbsentAttribute()
    {
        return $this->details->where('status', 'absent')->count();
    }
}