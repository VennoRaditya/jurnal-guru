<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa; // Pastikan model Siswa sudah dibuat
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kartu kelas.
     */
    public function index() 
    {
        // Mengambil semua kelas dan menghitung jumlah siswa di tiap kelas secara otomatis
        $kelases = Kelas::withCount('siswas')->get(); 
        
        return view('admin.kelas.index', compact('kelases'));
    }

    /**
     * Fungsi khusus untuk mengambil data siswa via AJAX (Fetch).
     * Digunakan oleh Alpine.js di bagian detail.
     */
    public function getSiswa($id)
    {
        // Cari kelas berdasarkan ID
        $kelas = Kelas::findOrFail($id);
        
        // Ambil daftar siswa yang memiliki kelas_id sesuai
        $siswas = Siswa::where('kelas_id', $id)
                        ->orderBy('nama', 'asc')
                        ->get();

        return response()->json($siswas);
    }

    /**
     * Menyimpan kelas baru (Jika Anda ingin menambah fitur tambah kelas).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas',
        ]);

        Kelas::create($request->all());

        return redirect()->back()->with('success', 'Kelas berhasil dibuat.');
    }

    /**
     * Menghapus kelas.
     */
    public function destroy(Kelas $kelas)
    {
        // Opsional: Cek apakah kelas masih ada siswanya sebelum dihapus
        if($kelas->siswas()->count() > 0) {
            return redirect()->back()->with('error', 'Kelas tidak bisa dihapus karena masih memiliki siswa.');
        }

        $kelas->delete();
        return redirect()->back()->with('success', 'Kelas berhasil dihapus.');
    }

    // Fungsi show, edit, update bisa disesuaikan jika Anda butuh halaman terpisah, 
    // namun dengan konsep satu halaman (AJAX), fungsi di atas sudah cukup.
}