<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'name',
        'class_year_id',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'classroom_id',
        'type',
        'total_marks',
        'passing_marks',
        'description',
        'teacher_id'
    ];

    protected $casts = [
        'exam_date' => 'date',
        'total_marks' => 'integer',
        'passing_marks' => 'integer',
    ];

    public function classYear()
    {
        return $this->belongsTo(ClassYear::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function examDetails()
    {
        return $this->hasMany(ExamDetail::class);
    }

    public function getDurationAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        return $end->diffInHours($start);
    }

    public function getIsUpcomingAttribute()
    {
        return $this->exam_date > now();
    }

    public function getIsCompletedAttribute()
    {
        return $this->exam_date < now();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('exam_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('exam_date', '<', now());
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}