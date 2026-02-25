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
        'kelas', 
        'materi_kd', 
        'kegiatan_pembelajaran', 
        'evaluasi', 
        'tanggal'
    ];

    /**
     * Relasi ke Absensi (Nama asli)
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'materi_id');
    }

    /**
     * Relasi ke Presensis (Alias agar tidak error)
     * Ini akan memperbaiki error "RelationNotFoundException"
     */
    public function presensis()
    {
        return $this->hasMany(Absensi::class, 'materi_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}