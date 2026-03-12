<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Incident; 

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'occupant_status',    
        'is_verified',       
        'apartment_number',
        'is_admin', 
        'role',
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
            'is_admin' => 'boolean', 
        ];
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || (bool) $this->is_admin;
    }
}
