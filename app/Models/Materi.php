<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materis';

    protected $fillable = [
        'guru_id', 'mata_pelajaran', 'kelas', 'materi_kd', 
        'kegiatan_pembelajaran', 'evaluasi', 'tanggal'
    ];

    // Relasi Utama (Singular) - Sesuai saran sebelumnya
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'materi_id');
    }

    // Relasi Alias (Plural) - Untuk memperbaiki error "presensis" Anda
    public function presensis()
    {
        return $this->hasMany(Absensi::class, 'materi_id');
    }
}       