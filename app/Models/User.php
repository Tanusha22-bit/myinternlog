<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    public function student()
    {
        return $this->hasOne(\App\Models\Student::class, 'user_id');
    }
    // Remove or comment out this incorrect relationship:
    // public function internship()
    // {
    //     return $this->hasOne(\App\Models\Internship::class, 'student_id', 'id');
    // }   
    public function universitySupervisor() { return $this->hasOne(UniversitySupervisor::class); }
    public function industrySupervisor()
    {
        return $this->hasOne(\App\Models\IndustrySupervisor::class, 'user_id');
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}