<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    // Pastikan kolom ini sama persis dengan migration database kamu
    protected $fillable = [
        'guru_id', 
        'judul_materi', 
        'pembahasan', 
        'mata_pelajaran', 
        'kelas', 
        'tanggal'
    ];

    /**
     * Relasi ke Absensi
     * Gunakan SATU nama saja agar tidak bingung. 
     * Jika tabelnya 'absensis', gunakan nama ini.
     */
    public function absensis()
    {
        // Pastikan foreign key di tabel absensis adalah 'materi_id'
        return $this->hasMany(Absensi::class, 'materi_id');
    }

    /**
     * Alias untuk presensi (opsional)
     * Jika di view kamu memanggil $materi->presensis, maka gunakan ini.
     */
    public function presensis()
    {
        return $this->hasMany(Absensi::class, 'materi_id');
    }

    /**
     * Relasi ke Guru
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}