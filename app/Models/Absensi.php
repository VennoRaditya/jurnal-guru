<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'materi_id', 
        'siswa_id', 
        'status', 
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Mutator & Accessor (Terbaru di Laravel)
     * Memastikan status yang masuk ke DB selalu rapi (contoh: 'hadir' jadi 'Hadir')
     * Ini kunci agar hitungan statistik di riwayat tidak error.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucfirst(strtolower($value)),
        );
    }

    /**
     * Relasi ke Materi
     */
    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id')->withDefault([
            'judul_materi' => 'Materi Terhapus',
            'kelas' => '-'
        ]);
    }

    /**
     * Relasi ke Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id')->withDefault([
            'nama' => 'Siswa Tidak Ditemukan'
        ]);
    }

    /**
     * Scope Filter Status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', ucfirst(strtolower($status)));
    }
}