<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = ['guru_id', 'judul_materi', 'pembahasan', 'mata_pelajaran', 'kelas', 'tanggal'];

    // Relasi ke banyak Absensi (Satu materi punya banyak data absen siswa)
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function presensis()
{
    return $this->hasMany(Presensi::class, 'materi_id');
}
}