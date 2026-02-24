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
        'nama', 
        'username', 
        'password', 
        'mapel',
        'kelas', // Menambahkan kolom kelas yang baru dibuat di migration
        'email', // Tetap ada jika Anda masih ingin menggunakan email
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