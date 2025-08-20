<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassYear extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'class_id',
        'year_id', // Changed from academic_year_id to match database
        'semester',
        'department_id',
        'shift_id',
        'classroom_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'year_id'); // Fixed foreign key
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function getDisplayNameAttribute()
    {
        $className = $this->class ? $this->class->name : 'N/A';
        $academicYearName = $this->academicYear ? $this->academicYear->name : 'N/A';
        
        return "{$className} - {$academicYearName} ({$this->semester})";
    }
}