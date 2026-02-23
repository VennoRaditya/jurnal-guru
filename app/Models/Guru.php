<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Authenticatable 
{
    use HasFactory, Notifiable;

    protected $table = 'gurus'; 

    protected $fillable = [
        'nip',
        'username', // Penting agar tidak kena error Integrity Constraint lagi
        'nama', 
        'email', 
        'password', 
        'mapel' // Diseragamkan dengan Controller dan Migration
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