<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Guru extends Authenticatable 
{
    use HasFactory, Notifiable;

    // Pastikan nama tabel sesuai dengan migration kamu
    protected $table = 'gurus'; 

    protected $fillable = [
        'nama', 
        'nip', 
        'email', 
        'password', 
        'mata_pelajaran'
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