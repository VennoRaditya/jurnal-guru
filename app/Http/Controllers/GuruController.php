<?php

namespace App\Http\Controllers;

use App\Models\Materi; 
use App\Models\Absensi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class AbsensiController extends Controller
{
    // ... Method index, store, dan downloadRekap yang sudah ada ...

    /**
     * Update status absensi siswa dari halaman Rekap
     */
    public function updateAbsensi(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'status' => 'required|in:Sakit,Izin,Alfa,Hadir'
        ], [
            'status.in' => 'Status harus salah satu dari: Sakit, Izin, Alfa, atau Hadir.'
        ]);

        try {
            DB::beginTransaction();

            // 2. Cari data absensi berdasarkan ID
            $absensi = Absensi::findOrFail($id);

            // 3. Logika Perubahan Status
            // Jika status diubah menjadi 'Hadir', maka data ketidakhadiran dihapus 
            // karena tabel absensi biasanya hanya menyimpan siswa yang TIDAK hadir.
            if ($request->status === 'Hadir') {
                $absensi->delete();
                $pesan = "Siswa berhasil ditandai sebagai Hadir dan dihapus dari daftar rekap.";
            } else {
                $absensi->update([
                    'status' => $request->status
                ]);
                $pesan = "Status absensi siswa berhasil diubah menjadi " . $request->status;
            }

            DB::commit();

            return back()->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Method Rekap (untuk menampilkan halaman Blade yang tadi kita buat)
     */
    public function rekap(Request $request)
    {
        // Ambil periode dari input bulan_tahun, default ke bulan sekarang
        $periodeInput = $request->get('bulan_tahun', date('Y-m'));
        $year = Carbon::parse($periodeInput)->year;
        $month = Carbon::parse($periodeInput)->month;

        // Ambil riwayat jurnal beserta relasi absensi dan siswanya
        $riwayatJurnal = Materi::with(['absensi.siswa'])
            ->where('guru_id', Auth::guard('guru')->id())
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.absensi.rekap', compact('riwayatJurnal', 'periodeInput'));
    }
}