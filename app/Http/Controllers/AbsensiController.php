<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Materi;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * Menampilkan daftar siswa berdasarkan kelas (Halaman Input Satu Pintu).
     */
    public function showByKelas($kelas)
    {
        // Mengambil data siswa berdasarkan kelas yang dipilih
        $siswas = Siswa::where('kelas', $kelas)
                    ->orderBy('nama', 'asc')
                    ->get();

        // Jika kelas tidak ditemukan/kosong, tetap kirim array kosong agar count() tidak error
        return view('guru.presensi.index', [
            'siswas' => $siswas,
            'kelas_nama' => $kelas
        ]);
    }

    /**
     * Menyimpan data Jurnal Materi dan Presensi Siswa secara atomik.
     */
    public function storeJurnal(Request $request)
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'pembahasan'   => 'required',
            'kelas'        => 'required',
            'absen'        => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Simpan ke tabel Materi
                $materi = Materi::create([
                    'guru_id'        => Auth::guard('guru')->id(),
                    'judul_materi'   => $request->judul_materi,
                    'pembahasan'     => $request->pembahasan,
                    'mata_pelajaran' => Auth::guard('guru')->user()->mata_pelajaran,
                    'kelas'          => $request->kelas,
                    'tanggal'        => now()->format('Y-m-d'),
                ]);

                // 2. Simpan ke tabel Absensi
                foreach ($request->absen as $siswa_id => $status) {
                    Absensi::create([
                        'materi_id' => $materi->id,
                        'siswa_id'  => $siswa_id,
                        'status'    => $status, 
                        'tanggal'   => now()->format('Y-m-d'),
                    ]);
                }
            });

            return redirect()->route('guru.dashboard')->with('success', 'Presensi berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Riwayat Absensi (Arsip).
     */
    public function index(Request $request) 
    {
        $riwayat = Absensi::with(['siswa', 'materi'])
            ->whereHas('materi', function($query) {
                $query->where('guru_id', Auth::guard('guru')->id());
            })
            ->when($request->search, function($query, $search) {
                $query->whereHas('siswa', function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        // PERBAIKAN FATAL: Mengirim array kosong untuk $siswas agar count() di Blade tidak error
        // Karena view riwayat biasanya memakai layout yang sama atau baris kode yang mirip
        return view('guru.absensi.index', [
            'riwayat'    => $riwayat,
            'siswas'     => collect([]), // Kirim collection kosong agar count($siswas) jadi 0, bukan error
            'kelas_nama' => 'Arsip'
        ]);
    }
}