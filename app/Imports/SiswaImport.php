<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows; // Penting untuk skip baris kosong

class SiswaImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    protected $kelas_id;

    public function __construct($kelas_id)
    {
        $this->kelas_id = $kelas_id;
    }

    public function model(array $row)
    {
        // Ambil data dari berbagai kemungkinan header dan bersihkan spasi
        $nisRaw  = $row['nis'] ?? $row['nisn'] ?? null;
        $namaRaw = $row['nama_siswa'] ?? $row['nama'] ?? null;
        $jkRaw   = $row['jenis_kelamin'] ?? $row['jk'] ?? null;

        // 1. Bersihkan NIS dari karakter aneh (seperti 'L =')
        // Kita hanya ambil angka saja (numeric)
        $nis = preg_replace('/[^0-9]/', '', (string)$nisRaw);
        
        // 2. Jika NIS kosong atau bukan angka setelah dibersihkan, skip baris ini
        if (empty($nis)) {
            return null; 
        }

        // 3. Mapping & Normalisasi Nama
        $nama = strtoupper(trim($namaRaw ?? ''));

        // 4. Normalisasi Jenis Kelamin agar SQL Constraint tidak marah
        $jk = $this->formatJk($jkRaw);

        return new Siswa([
            'kelas_id' => $this->kelas_id,
            'nis'      => $nis,
            'nama'     => $nama,
            'jk'       => $jk,
        ]);
    }

    /**
     * Helper untuk memastikan input tetap masuk ke database sebagai 'L' atau 'P'
     * SQLite Check Constraint sangat ketat soal ini.
     */
    private function formatJk($value)
    {
        if (!$value) return 'L'; // Default jika kosong

        $value = strtoupper(trim((string)$value));
        
        if (str_starts_with($value, 'L')) return 'L';
        if (str_starts_with($value, 'P')) return 'P';
        
        // Jika data aneh, default ke L agar tidak error SQL
        return 'L'; 
    }
}