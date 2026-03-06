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
     * PENTING: Mutator & Accessor
     * Agar sistem export dan pencarian aman, kita pastikan data di DB 
     * disimpan dalam huruf kecil (lowercase), namun ditampilkan Kapital jika dipanggil di View.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            // Simpan ke DB sebagai 'hadir' (lowercase) agar sinkron dengan sistem export
            set: fn (string $value) => strtolower($value),
            // Tampilkan di view sebagai 'Hadir' (ucfirst) agar rapi di interface
            get: fn (string $value) => ucfirst($value),
        );
    }

    /**
     * Relasi ke Materi
     */
    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id')->withDefault([
            'materi_kd' => 'Materi Terhapus',
            'kelas' => '-'
        ]);
    }

    /**
     * Relasi ke Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id')->withDefault([
            'nama' => 'Siswa Terhapus',
            'nis' => '-'
        ]);
    }

    /**
     * Scope Filter Status
     */
    public function scopeStatus($query, $status)
    {
        // Mencari dalam format lowercase sesuai penyimpanan di DB
        return $query->where('status', strtolower($status));
    }
}