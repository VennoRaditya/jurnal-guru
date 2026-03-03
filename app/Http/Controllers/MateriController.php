<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Absensi; // 1. IMPORT MODEL ABSENSI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // 2. IMPORT DB FACADE
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class MateriController extends Controller 
{
    /**
     * Menampilkan Arsip Materi Pembelajaran (Riwayat dengan Filter Bulanan)
     */
    public function index(Request $request)
    {
        $guru_id = Auth::guard('guru')->id();

        // 1. Ambil input bulan_tahun, jika tidak ada default ke bulan saat ini
        $periode = $request->query('bulan_tahun', now()->format('Y-m'));
        
        // 2. Parse menggunakan Carbon untuk mendapatkan tahun dan bulan
        $date = Carbon::parse($periode);

        // 3. Query dengan filter Tahun dan Bulan yang spesifik
        $riwayatMateri = Materi::with('absensi') 
            ->where('guru_id', $guru_id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month)
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString(); // Menjaga parameter URL saat pindah halaman pagination

        return view('guru.materi.index', compact('riwayatMateri', 'periode'));
    }

    /**
     * TAMPILAN: Form Input Materi Baru
     */
    public function create()
    {
        // Pastikan view 'guru.materi.create' ada
        return view('guru.materi.create');
    }

    /**
     * Fitur Rekap Mingguan ke PDF
     */
    public function downloadRekap()
    {
        Carbon::setLocale('id');
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $jurnals = Materi::where('guru_id', Auth::guard('guru')->id())
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->orderBy('tanggal', 'asc')
            ->get();

        $data = [
            'title'     => 'REKAP JURNAL PEMBELAJARAN MINGGUAN',
            'periode'   => $startOfWeek->translatedFormat('d F') . ' - ' . $endOfWeek->translatedFormat('d F Y'),
            'nama_guru' => Auth::guard('guru')->user()->nama,
            'nip'       => Auth::guard('guru')->user()->nip,
            'jurnals'   => $jurnals
        ];

        $pdf = Pdf::loadView('guru.absensi.rekap_pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('Rekap_Mingguan_' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Memperbarui Data Materi
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'materi_kd' => 'required|string|max:255',
            'kegiatan_pembelajaran' => 'required',
            'evaluasi' => 'required'
        ]);

        $materi = Materi::where('guru_id', Auth::guard('guru')->id())->findOrFail($id);

        $materi->update([
            'materi_kd' => $request->materi_kd,
            'kegiatan_pembelajaran' => $request->kegiatan_pembelajaran,
            'evaluasi' => $request->evaluasi,
        ]);

        return redirect()->route('guru.materi.index')->with('success', 'Jurnal berhasil diperbarui!');
    }

    /**
     * Menghapus Materi dari Arsip
     */
    public function destroy($id)
    {
        // Gunakan Transaksi agar jika gagal menghapus absensi, materi tidak jadi dihapus
        try {
            DB::transaction(function () use ($id) {
                $materi = Materi::where('guru_id', Auth::guard('guru')->id())->findOrFail($id);
                
                // 3. Hapus absensi yang terkait terlebih dahulu
                Absensi::where('materi_id', $materi->id)->delete();
                
                // 4. Hapus materinya
                $materi->delete();
            });

            return redirect()->back()->with('success', 'Jurnal dan data absensi terkait berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jurnal: ' . $e->getMessage());
        }
    }
}