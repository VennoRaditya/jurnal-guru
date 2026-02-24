<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nama',
        'kelas_id', // Menggunakan ID untuk relasi ke tabel kelas
        'jk',       // Gender (L/P)
    ];

    /**
     * Relasi Balik: Siswa dimiliki oleh satu Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}