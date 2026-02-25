<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'password' => 'hashed', // Otomatis meng-hash password saat disimpan
        'kelas' => 'array',    // Otomatis convert Array <=> JSON
    ];
}