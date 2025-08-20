<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
{
    protected $fillable = [
        'attendance_id', 
        'student_id', 
        'session1', 
        'session2', 
        'session3', 
        'session4', 
        'remarks'
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getTotalPresentSessionsAttribute()
    {
        $count = 0;
        if ($this->session1 === 'present') $count++;
        if ($this->session2 === 'present') $count++;
        if ($this->session3 === 'present') $count++;
        if ($this->session4 === 'present') $count++;
        return $count;
    }
}