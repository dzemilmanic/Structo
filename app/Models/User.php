<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_PROFI = 'profi';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'specialization',
        'bio',
        'phone',
        'location',
        'photo',
        'years_of_experience',
        'certification',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    public function isProfi()
    {
        return $this->role === self::ROLE_PROFI;
    }

    // Regular user relationships
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    // Professional relationships
    public function services()
    {
        return $this->hasMany(Service::class, 'professional_id');
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class, 'professional_id');
    }

    public function assignedJobs()
    {
        return $this->hasMany(Job::class, 'assigned_professional_id');
    }
}