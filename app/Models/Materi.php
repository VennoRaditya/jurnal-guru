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
        'kelas',    // Field ini menyimpan string nama kelas (backup)
        'kelas_id', // Field ini yang dipakai untuk relasi belongsTo
        'materi_kd', 
        'kegiatan_pembelajaran', 
        'evaluasi', 
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * RELASI KE GURU
     * Diperlukan untuk query filter berdasarkan guru_id di Controller.
     */
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id'); // Sesuaikan dengan model Guru/User Anda
    }

    /**
     * RELASI KE KELAS
     * Menghubungkan kolom 'kelas_id' di tabel materis ke tabel kelas.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id')->withDefault([
            'nama_kelas' => $this->kelas ?? 'Kelas Terhapus' 
        ]);
    }

    /**
     * RELASI KE ABSENSI
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'materi_id');
    }
}