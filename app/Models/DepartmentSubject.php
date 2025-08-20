<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentSubject extends Model
{
    protected $table = 'department_subject';

    protected $fillable = [
        'department_id',
        'subject_id',
        'year_id',
        'semester'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'year_id');
    }
}