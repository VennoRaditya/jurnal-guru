<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        // 1. Logika Filter Berdasarkan Dropdown (Tingkat & Jurusan)
        if ($request->filled('tingkat') && $request->filled('jurusan')) {
            $namaKelas = $request->tingkat . ' ' . $request->jurusan;
            $query->where('kelas', $namaKelas);
        }

        // 2. Logika Search (Nama atau NIS)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        // Ambil data terbaru dengan pagination
        $siswas = $query->latest()->paginate(10);

        return view('guru.siswa.index', compact('siswas'));
    }

    public function store(Request $request)
    {
        // 1. PROSES PENGGABUNGAN KELAS (Anti-Error)
        // Kita ambil data langsung dari dropdown temp agar tidak bergantung pada JavaScript
        $tingkat = $request->input('tingkat_temp');
        $jurusan = $request->input('jurusan_temp');
        
        // Gabungkan secara manual di server side
        $gabungKelas = ($tingkat && $jurusan) ? $tingkat . ' ' . $jurusan : null;

        // Masukkan kembali ke request agar bisa divalidasi oleh 'kelas' => 'required'
        $request->merge(['kelas' => $gabungKelas]);

        // 2. VALIDASI
        $request->validate([
            'nis'   => 'required|unique:siswas,nis',
            'nama'  => 'required|string|max:255',
            'kelas' => 'required|string', 
        ], [
            'nis.unique'     => 'NIS ini sudah terdaftar.',
            'kelas.required' => 'Mohon pilih Tingkat dan Jurusan terlebih dahulu!',
        ]);

        // 3. EKSEKUSI SIMPAN
        Siswa::create([
            'nis'   => $request->nis,
            'nama'  => $request->nama,
            'kelas' => $request->kelas, // Sekarang variabel ini DIJAMIN ada isinya
        ]);

        return back()->with('success', 'Murid ' . $request->nama . ' berhasil ditambahkan ke kelas ' . $request->kelas);
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $nama = $siswa->nama;
        $siswa->delete();

        return redirect()->back()->with('success', "Data murid $nama berhasil dihapus!");
    }
}