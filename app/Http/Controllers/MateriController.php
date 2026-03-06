<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class MateriController extends Controller 
{
    public function index(Request $request)
    {
        $guru_id = Auth::guard('guru')->id();
        $periode = $request->query('bulan_tahun', now()->format('Y-m'));
        
        try {
            $date = Carbon::parse($periode . "-01");
        } catch (\Exception $e) {
            $date = Carbon::today();
            $periode = $date->format('Y-m');
        }

        $riwayatMateri = Materi::with(['absensi', 'kelas']) 
            ->where('guru_id', $guru_id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month)
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString(); 

        return view('guru.materi.index', compact('riwayatMateri', 'periode'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'materi_kd' => 'required|string|max:255',
            'kegiatan_pembelajaran' => 'required|string',
            'evaluasi' => 'required|string',
            'mata_pelajaran' => 'sometimes|required|string' // Jika ingin mapel bisa diubah
        ]);

        $materi = Materi::where('guru_id', Auth::guard('guru')->id())->findOrFail($id);

        $materi->update($request->only([
            'materi_kd', 
            'kegiatan_pembelajaran', 
            'evaluasi', 
            'mata_pelajaran'
        ]));

        return redirect()->route('guru.materi.index')->with('success', 'Jurnal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $materi = Materi::where('guru_id', Auth::guard('guru')->id())->findOrFail($id);
                
                // Menghapus absensi terlebih dahulu
                $materi->absensi()->delete(); 
                $materi->delete();
            });

            return redirect()->back()->with('success', 'Riwayat jurnal dan absensi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    // Rekap mingguan tetap sama, sudah bagus
    public function downloadRekap()
    {
        Carbon::setLocale('id');
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $jurnals = Materi::with(['absensi', 'kelas'])
            ->where('guru_id', Auth::guard('guru')->id())
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->orderBy('tanggal', 'asc')
            ->get();

        $data = [
            'title'     => 'REKAP JURNAL PEMBELAJARAN MINGGUAN',
            'periode'   => $startOfWeek->translatedFormat('d F') . ' - ' . $endOfWeek->translatedFormat('d F Y'),
            'nama_guru' => Auth::guard('guru')->user()->nama,
            'nip'       => Auth::guard('guru')->user()->nip ?? '-',
            'jurnals'   => $jurnals
        ];

        return Pdf::loadView('guru.absensi.rekap_pdf', $data)
                  ->setPaper('a4', 'landscape')
                  ->download('Rekap_Mingguan_' . now()->format('Ymd') . '.pdf');
    }
}   