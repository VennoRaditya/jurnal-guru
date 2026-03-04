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
        'kelas_id', // WAJIB ADA: Field ini yang dipakai untuk relasi belongsTo
        'materi_kd', 
        'kegiatan_pembelajaran', 
        'evaluasi', 
        'tanggal'
    ];

    /**
     * RELASI KE KELAS
     * Menghubungkan kolom 'kelas_id' di tabel materis ke tabel kelas.
     */
    public function kelas()
    {
        // Menggunakan 'kelas_id' sebagai foreign key
        return $this->belongsTo(Kelas::class, 'kelas_id')->withDefault([
            'nama_kelas' => $this->kelas ?? 'Kelas Terhapus' 
            // Tip: Jika relasi ID gagal, ia akan menampilkan string dari kolom 'kelas'
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