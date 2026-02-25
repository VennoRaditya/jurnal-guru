<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id', 
        'mata_pelajaran', 
        'kelas', 
        'materi_kd',             // Update: sebelumnya judul_materi
        'kegiatan_pembelajaran', // Update: sebelumnya pembahasan
        'evaluasi',              // Tambahan baru
        'tanggal'
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'materi_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}