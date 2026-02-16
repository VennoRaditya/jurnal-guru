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
     * TAMPILAN: Pilih Kelas
     * File: resources/views/guru/absensi/select.blade.php
     */
    public function selectClass()
    {
        return view('guru.absensi.select');
    }

    /**
     * TAMPILAN: Form Absensi Siswa
     * File: resources/views/guru/absensi/index.blade.php
     */
    public function create(Request $request)
    {
        $request->validate([
            'tingkat' => 'required',
            'jurusan' => 'required'
        ]);

        $kelas_nama = $request->tingkat . ' ' . $request->jurusan;
        
        $siswas = Siswa::where('kelas', $kelas_nama)
                    ->orderBy('nama', 'asc')
                    ->get();

        // Mengarah ke folder absensi, bukan materi
        return view('guru.absensi.index', [
            'siswas' => $siswas,
            'kelas_nama' => $kelas_nama
        ]);
    }

    /**
     * PROSES: Simpan Jurnal & Absensi
     */
    public function storeJurnal(Request $request)
    {
        $request->validate([
            'judul_materi'   => 'required|string|max:255',
            'pembahasan'     => 'required',
            'mata_pelajaran' => 'required|string|max:255',
            'kelas'          => 'required',
            'absen'          => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $materi = Materi::create([
                    'guru_id'        => Auth::guard('guru')->id(),
                    'judul_materi'   => $request->judul_materi,
                    'pembahasan'     => $request->pembahasan,
                    'mata_pelajaran' => $request->mata_pelajaran,
                    'kelas'          => $request->kelas,
                    'tanggal'        => now()->format('Y-m-d'),
                ]);

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
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * TAMPILAN: Riwayat Materi (Arsip)
     * File: resources/views/guru/materi/index.blade.php
     */
    public function index() 
    {
        $riwayatMateri = Materi::with(['presensis'])
            ->where('guru_id', Auth::guard('guru')->id())
            ->latest()
            ->paginate(10);

        // Mengarah ke folder materi
        return view('guru.materi.index', [
            'riwayatMateri' => $riwayatMateri
        ]);
    }
}