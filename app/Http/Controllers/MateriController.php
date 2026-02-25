<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF
use Carbon\Carbon;

class MateriController extends Controller 
{
    /**
     * Menampilkan Arsip Materi Pembelajaran (Riwayat)
     */
    public function index()
    {
        // Gunakan with('presensis') agar tidak lambat (Eager Loading)
        $riwayatMateri = Materi::with('presensis')
            ->where('guru_id', Auth::guard('guru')->id())
            ->latest('tanggal')
            ->paginate(10);

        return view('guru.materi.index', compact('riwayatMateri'));
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
     * Memperbarui Data Materi (Update sesuai kolom baru)
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
        $materi = Materi::where('guru_id', Auth::guard('guru')->id())->findOrFail($id);
        $materi->delete();

        return redirect()->back()->with('success', 'Jurnal berhasil dihapus!');
    }

    /**
     * Simpan Materi Baru
     */
    public function store(Request $request) 
    {
        $request->validate([
            'materi_kd' => 'required|string|max:255',
            'kegiatan_pembelajaran' => 'required',
            'evaluasi' => 'required',
            'kelas' => 'required',
            'tanggal' => 'required|date'
        ]);

        Materi::create([
            'guru_id'               => Auth::guard('guru')->id(),
            'mata_pelajaran'        => Auth::guard('guru')->user()->mapel, // Sesuaikan kolom di tabel guru
            'materi_kd'             => $request->materi_kd,
            'kegiatan_pembelajaran' => $request->kegiatan_pembelajaran,
            'evaluasi'              => $request->evaluasi,
            'kelas'                 => $request->kelas,
            'tanggal'               => $request->tanggal
        ]);

        return redirect()->back()->with('success', 'Jurnal berhasil ditambahkan!');
    }
}