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
     * PENTING: Relasi ke model Absensi
     * Tanpa ini, fitur hitung rekap S/I/A di Controller/PDF akan error.
     */
    public function absensis()
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