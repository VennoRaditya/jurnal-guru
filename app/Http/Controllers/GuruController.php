<?php

namespace App\Http\Controllers;

use App\Models\Materi; // Model yang menyimpan jurnal (KD, Kegiatan, Evaluasi)
use App\Models\Absensi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    // ... Method index & store yang sudah kamu punya ...

    /**
     * Fitur Rekap Mingguan ke PDF
     */
    public function downloadRekap(Request $request)
    {
        // 1. Tentukan rentang waktu (Minggu ini: Senin sampai Minggu)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // 2. Ambil data Jurnal/Materi milik guru yang sedang login di minggu ini
        $jurnals = Materi::where('guru_id', Auth::guard('guru')->id())
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->orderBy('tanggal', 'asc')
            ->get();

        // 3. Siapkan data untuk dikirim ke View PDF
        $data = [
            'title'     => 'LAPORAN REKAP PEMBELAJARAN MINGGUAN',
            'periode'   => $startOfWeek->translatedFormat('d F') . ' - ' . $endOfWeek->translatedFormat('d F Y'),
            'nama_guru' => Auth::guard('guru')->user()->nama,
            'nip'       => Auth::guard('guru')->user()->nip,
            'jurnals'   => $jurnals
        ];

        // 4. Generate PDF dengan layout Landscape agar tabel muat
        $pdf = Pdf::loadView('guru.absensi.rekap_pdf', $data)
                  ->setPaper('a4', 'landscape');

        // 5. Download file
        return $pdf->download('Rekap_Jurnal_' . $startOfWeek->format('Y-m-d') . '.pdf');
    }
}