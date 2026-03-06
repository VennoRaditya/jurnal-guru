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
        'jk',
        'kelas_id',
    ];

    /**
     * Relasi ke model Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke model Absensi
     * Nama fungsi diubah menjadi 'absensi' (tanpa s) agar sinkron dengan:
     * $siswa->absensi->where(...) di Controller dan Export.
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    /**
     * Mutator: Memastikan nama yang masuk ke database selalu Huruf Kapital
     */
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = strtoupper($value);
    }
}