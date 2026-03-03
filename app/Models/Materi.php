<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materis';

    protected $fillable = [
        'guru_id', 
        'mata_pelajaran', 
        'kelas', // Pastikan di DB tipe data ini integer/foreign key
        'materi_kd', 
        'kegiatan_pembelajaran', 
        'evaluasi', 
        'tanggal'
    ];

    // --- RELASI KE KELAS ---
    // Dipakai di view: $materi->kelas->nama_kelas
    public function kelas()
    {
        // 1. Peningkatan: Tambahkan handling jika relasi kelas tidak ditemukan
        return $this->belongsTo(Kelas::class, 'kelas_id')->withDefault([
            'nama_kelas' => 'Kelas Terhapus'
        ]);
    }

    // --- RELASI KE ABSENSI/PRESENSI ---
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'materi_id');
    }

    // 2. Peningkatan: Gunakan nama relasi yang konsisten
    public function presensis()
    {
        return $this->hasMany(Absensi::class, 'materi_id');
    }

    // --- FUNGSI materis() DIHAPUS KARENA SALAH LOGIKA ---
}