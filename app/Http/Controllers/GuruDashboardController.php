<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Materi; // Pastikan model Materi sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller 
{
    public function index() 
    {
        // 1. Mengambil total siswa (bisa difilter berdasarkan kelas guru jika ada relasinya)
        $totalSiswa = Siswa::count();

        // 2. Mengambil total materi yang sudah diinput oleh guru yang sedang login
        // Diasumsikan tabel materi memiliki kolom 'guru_id'
        $totalMateri = Materi::where('guru_id', Auth::guard('guru')->id())->count();

        // 3. Dummy data untuk presensi (nanti bisa dihubungkan ke model Absensi)
        $rataPresensi = '95%'; 

        // 4. Mengambil materi terbaru untuk riwayat di dashboard
        $materiTerbaru = Materi::where('guru_id', Auth::guard('guru')->id())
                                ->latest()
                                ->take(1)
                                ->get();

        return view('guru.dashboard', compact(
            'totalSiswa', 
            'totalMateri', 
            'rataPresensi', 
            'materiTerbaru'
        ));
    }
}