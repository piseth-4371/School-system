<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'code',
        'department_id',
        'description',
        'is_active',
        'teacher_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classYears()
    {
        return $this->hasMany(ClassYear::class, 'class_id');
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, ClassYear::class, 'class_id', 'class_year_id');
    }
}