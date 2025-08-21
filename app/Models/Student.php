<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'student_code',
        'gender',
        'dob',
        'address',
        'phone',
        'parent_name',
        'parent_phone',
        'enrolled_date',
        'department_id',
        'class_year_id' 
    ];

    protected $casts = [
        'dob' => 'date',
        'enrolled_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function attendances()
    {
        return $this->hasMany(AttendanceDetail::class);
    }

    public function examDetails()
    {
        return $this->hasMany(ExamDetail::class);
    }

    public function getAgeAttribute()
    {
        return $this->dob ? $this->dob->age : null;
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('paid_amount');
    }
    public function classYear()
    {
        return $this->belongsTo(ClassYear::class);
    }
}