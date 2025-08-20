<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'credit_hours',
        'is_active'
    ];

    protected $casts = [
        'credit_hours' => 'integer',
        'is_active' => 'boolean'
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_subject')
                    ->withPivot('year_id', 'semester')
                    ->withTimestamps();
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}