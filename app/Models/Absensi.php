<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional tapi disarankan)
    protected $table = 'absensis';

    protected $fillable = [
        'materi_id', 
        'siswa_id', 
        'status', 
        'tanggal'
    ];

    /**
     * Casting variabel tanggal menjadi objek Carbon otomatis.
     * Ini memudahkan Anda melakukan format tanggal di Blade tanpa parse manual.
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke Materi (Arsip Jurnal)
     * withDefault() mencegah error "Attempt to read property on null" jika materi terhapus.
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
     * Scope untuk memudahkan filter berdasarkan status tertentu (Hadir, Sakit, dll)
     * Contoh penggunaan: Absensi::status('hadir')->get();
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}