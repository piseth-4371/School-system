<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    // Accessor for profile photo URL
   public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            // Check if file exists in storage
            if (Storage::exists('public/profile-photos/' . $this->profile_photo)) {
                return asset('storage/profile-photos/' . $this->profile_photo);
            }
        }
        
        // Return default avatar if no profile photo
        return asset('images/default-avatar.png');
}

    // Check if user has profile photo
    public function getHasProfilePhotoAttribute()
    {
        return !empty($this->profile_photo);
    }
}