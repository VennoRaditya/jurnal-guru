<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Materi; // Asumsi nama model jurnal/materi Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guru_id = Auth::guard('guru')->user()->id;

        // Ambil data asli dari database
        $total_siswa = Siswa::count();
        $total_materi = Materi::where('guru_id', $guru_id)->count();
        
        // Ambil 3 jurnal terakhir milik guru ini
        $recent_materi = Materi::where('guru_id', $guru_id)
                                ->latest()
                                ->take(3)
                                ->get();

        return view('guru.dashboard', compact(
            'total_siswa', 
            'total_materi', 
            'recent_materi'
        ));
    }
}   