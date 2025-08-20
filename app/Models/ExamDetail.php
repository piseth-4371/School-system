<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamDetail extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'score',
        'grade',
        'remarks'
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getPercentageAttribute()
    {
        if ($this->exam->total_marks > 0) {
            return ($this->score / $this->exam->total_marks) * 100;
        }
        return 0;
    }

    public function getIsPassedAttribute()
    {
        return $this->score >= $this->exam->passing_marks;
    }

    public function calculateGrade()
    {
        $percentage = $this->percentage;

        if ($percentage >= 90) return 'A+';
        if ($percentage >= 85) return 'A';
        if ($percentage >= 80) return 'A-';
        if ($percentage >= 75) return 'B+';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 65) return 'B-';
        if ($percentage >= 60) return 'C+';
        if ($percentage >= 55) return 'C';
        if ($percentage >= 50) return 'C-';
        if ($percentage >= 45) return 'D+';
        if ($percentage >= 40) return 'D';
        return 'F';
    }
}