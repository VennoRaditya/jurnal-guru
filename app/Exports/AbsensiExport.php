<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $rowNumber = 0;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Kelas',
            'Hadir',
            'Sakit',
            'Izin',
            'Alfa',
            'Terlambat',
            'Total Akumulasi (S+I+A+T)',
        ];
    }

    public function map($siswa): array
    {
        $this->rowNumber++;

        // 1. Ambil data absensi dan pastikan status diubah ke huruf kecil untuk pengecekan
        $absensi = collect($siswa->absensi)->map(function($item) {
            return [
                'status' => strtolower($item->status)
            ];
        });

        // 2. Hitung jumlah berdasarkan status (semua sudah lowercase)
        $hadir     = $absensi->where('status', 'hadir')->count();
        $sakit     = $absensi->where('status', 'sakit')->count();
        $izin      = $absensi->where('status', 'izin')->count();
        $alfa      = $absensi->where('status', 'alfa')->count();
        $terlambat = $absensi->where('status', 'terlambat')->count();

        // 3. Hitung total akumulasi ketidakhadiran
        $total = $sakit + $izin + $alfa + $terlambat;

        return [
            $this->rowNumber,
            $siswa->nama,
            $siswa->kelas->nama_kelas ?? '-',
            $hadir,
            $sakit,
            $izin,
            $alfa,
            $terlambat,
            $total,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Memberikan border dan styling pada header
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}