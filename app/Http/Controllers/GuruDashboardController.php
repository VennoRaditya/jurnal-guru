<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuruDashboardController extends Controller
{
    public function index()
    {
        // 1. Identitas Guru & Waktu
        $guru = Auth::guard('guru')->user();
        $guru_id = $guru->id;
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 2. DATA UNTUK STAT CARDS
        
        // Jurnal bulan ini
        $jurnal_bulan_ini = Materi::where('guru_id', $guru_id)
                                  ->whereBetween('tanggal', [$startOfMonth, Carbon::now()])
                                  ->count();

        // Jurnal/Presensi hari ini
        $presensi_hari_ini = Materi::where('guru_id', $guru_id)
                                   ->whereDate('tanggal', $today)
                                   ->count();
        
        // Total kelas yang diampu
        $total_kelas = Kelas::where('guru_id', $guru_id)->count();

        // Total seluruh jurnal
        $total_materi = Materi::where('guru_id', $guru_id)->count();
        
        // 3. STATUS KEAKTIFAN
        $sudah_isi_hari_ini = $presensi_hari_ini > 0;

        // 4. JURNAL TERBARU (5 Terakhir)
        $recent_materi = Materi::where('guru_id', $guru_id)
                                ->with('kelas') 
                                ->orderBy('tanggal', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        // 5. KIRIM DATA KE VIEW (Hapus total_siswa)
        return view('guru.dashboard', compact(
            'guru',
            'jurnal_bulan_ini',
            'presensi_hari_ini',
            'total_kelas',
            'total_materi', 
            'recent_materi',
            'sudah_isi_hari_ini'
        ));
    }
}