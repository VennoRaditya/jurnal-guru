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
     */
    public function selectClass()
    {
        return view('guru.absensi.select');
    }

    /**
     * TAMPILAN: Form Absensi Siswa
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
        DB::beginTransaction();

        // 1. Simpan Jurnal
        $materi = Materi::create([
            'guru_id'        => auth()->guard('guru')->id(), // Pastikan guard sudah benar
            'judul_materi'   => $request->judul_materi,
            'pembahasan'     => $request->pembahasan,
            'mata_pelajaran' => $request->mata_pelajaran,
            'kelas'          => $request->kelas,
            'tanggal'        => now()->format('Y-m-d'),
        ]);

        // 2. Simpan Detail Absensi
        foreach ($request->absen as $siswa_id => $status) {
            Absensi::create([
                'materi_id' => $materi->id,
                'siswa_id'  => $siswa_id,
                'status'    => $status,
                'tanggal'   => now()->format('Y-m-d'),
            ]);
        }

        DB::commit();

        return redirect()->route('guru.materi.index')
                         ->with('success', 'Jurnal & Presensi kelas ' . $request->kelas . ' berhasil disimpan!');

    } catch (\Exception $e) {
        DB::rollBack();
        // Log error untuk mempermudah debug jika dibutuhkan
        \Log::error("Gagal simpan jurnal: " . $e->getMessage());

        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Gagal menyimpan: ' . $e->getMessage());
    }
}

    /**
     * TAMPILAN: Riwayat Materi (Arsip)
     */
    public function index() 
    {
        $riwayatMateri = Materi::with(['presensis'])
            ->where('guru_id', Auth::guard('guru')->id())
            ->latest()
            ->paginate(10);

        return view('guru.materi.index', [
            'riwayatMateri' => $riwayatMateri
        ]);
    }
}