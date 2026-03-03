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
        'kelas', // Tipe array
        'email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'kelas' => 'array',
    ];

    /**
     * --- TAMBAHKAN RELASI INI ---
     * Relasi: Satu guru memiliki banyak kelas yang diampu.
     * Digunakan oleh Controller untuk menghitung $total_kelas.
     */
    public function daftarKelas()
    {
        // Asumsi: Tabel 'kelas' memiliki kolom 'guru_id'
        return $this->hasMany(Kelas::class, 'guru_id');
    }
}