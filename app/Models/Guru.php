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
        'kelas', 
        'email', 
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', 
        // TAMBAHKAN BARIS INI:
        // Ini otomatis mengubah array dari form menjadi JSON saat disimpan,
        // dan mengubah JSON menjadi array kembali saat dipanggil di Blade.
        'kelas' => 'array', 
    ];
}