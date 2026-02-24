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
        'kelas_id', // Ini yang paling penting untuk relasi
    ];

    /**
     * Relasi ke model Kelas
     * Pastikan di tabel 'siswas' ada kolom 'kelas_id'
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Optional: Accessor agar nama selalu rapi (Caps Lock)
     */
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = strtoupper($value);
    }
}