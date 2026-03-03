<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Menegaskan nama tabel jika tidak mengikuti konvensi jamak standar Laravel
    protected $table = 'kelas';

    protected $fillable = ['nama_kelas', 'guru_id'];

    /**
     * Relasi: Satu kelas memiliki banyak siswa.
     */
    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    /**
     * Relasi: Kelas dimiliki oleh satu guru.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * --- TAMBAHKAN RELASI INI ---
     * Relasi: Satu kelas memiliki banyak materi/jurnal.
     * Digunakan agar: $materi->kelas->nama_kelas berfungsi di view.
     */
    public function materis()
    {
        return $this->hasMany(Materi::class, 'kelas_id');
    }
}