<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\Log;

class SiswaImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    protected $kelas_id;

    public function __construct($kelas_id)
    {
        $this->kelas_id = $kelas_id;
    }

    public function model(array $row)
    {
        // Debugging: Mengambil semua value dalam satu baris untuk jaga-jaga jika header gagal dibaca
        $values = array_values($row);

        /**
         * LOGIKA FAIL-SAFE:
         * 1. Coba ambil pakai index header (misal: 'nis', 'nama_siswa')
         * 2. Kalau null, ambil pakai index urutan (0, 1, 2)
         */
        
        $nisRaw  = $row['nis'] ?? $values[0] ?? null;
        $namaRaw = $row['nama_siswa'] ?? $row['nama'] ?? $values[1] ?? null;
        $jkRaw   = $row['jenis_kelamin'] ?? $row['jk'] ?? $values[2] ?? 'L';

        // Validasi minimal: NIS dan Nama harus ada
        if (empty($nisRaw) || empty($namaRaw)) {
            Log::warning("Import dilewati: Baris kekurangan data.", ['data' => $row]);
            return null;
        }

        // Bersihkan NIS (Hanya angka)
        $nis = preg_replace('/[^0-9]/', '', (string)$nisRaw);

        // Simpan ke database
        return new Siswa([
            'nis'      => $nis,
            'nama'     => strtoupper(trim((string)$namaRaw)),
            'jk'       => $this->formatJk($jkRaw),
            'kelas_id' => $this->kelas_id,
        ]);
    }

    private function formatJk($value)
    {
        if (empty($value)) return 'L';
        
        $val = strtoupper(trim((string)$value));
        // Cek apakah mengandung huruf P atau kata PEREMPUAN
        if ($val === 'P' || str_contains($val, 'PEREMPUAN') || str_contains($val, 'WANITA')) {
            return 'P';
        }
        return 'L';
    }
}