<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 
        'code', 
        'start_date', 
        'end_date', 
        'is_current', 
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function classYears()
    {
        return $this->hasMany(ClassYear::class, 'year_id'); // Specify the foreign key
    }

    public function departmentSubjects()
    {
        return $this->hasMany(DepartmentSubject::class, 'year_id'); // Specify the foreign key
    }
}