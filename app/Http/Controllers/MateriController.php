<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller 
{
    /**
     * Menampilkan Arsip Materi Pembelajaran
     */
    public function index()
{
    // Mengambil data materi yang diinput oleh guru yang sedang login
    $riwayatMateri = Materi::where('guru_id', Auth::guard('guru')->id())
        ->latest('tanggal')
        ->paginate(10);

    // Kirim dengan nama variabel riwayatMateri
    return view('guru.materi.index', compact('riwayatMateri'));
}

    /**
     * Menampilkan Form Edit Materi
     */
    public function edit($id)
    {
        // Pastikan guru hanya bisa mengedit materi miliknya sendiri
        $materi = Materi::where('guru_id', Auth::guard('guru')->id())
                        ->findOrFail($id);

        return view('guru.materi.edit', compact('materi'));
    }

    /**
     * Memperbarui Data Materi di Database
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'pembahasan'   => 'required'
        ]);

        $materi = Materi::where('guru_id', Auth::guard('guru')->id())
                        ->findOrFail($id);

        $materi->update([
            'judul_materi' => $request->judul_materi,
            'pembahasan'   => $request->pembahasan,
        ]);

        return redirect()->route('guru.materi.index')
                         ->with('success', 'Arsip materi berhasil diperbarui!');
    }

    /**
     * Menghapus Materi dari Arsip
     */
    public function destroy($id)
    {
        $materi = Materi::where('guru_id', Auth::guard('guru')->id())
                        ->findOrFail($id);
        
        $materi->delete();

        return redirect()->back()
                         ->with('success', 'Arsip materi berhasil dihapus!');
    }

    /**
     * Simpan Materi Baru (Jika dibutuhkan secara mandiri)
     */
    public function store(Request $request) 
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'pembahasan'   => 'required',
            'kelas'        => 'required'
        ]);

        Materi::create([
            'guru_id'        => Auth::guard('guru')->id(),
            'mata_pelajaran' => Auth::guard('guru')->user()->mata_pelajaran,
            'judul_materi'   => $request->judul_materi,
            'pembahasan'     => $request->pembahasan,
            'kelas'          => $request->kelas,
            'tanggal'        => now()->format('Y-m-d')
        ]);

        return redirect()->back()->with('success', 'Materi berhasil ditambahkan ke arsip!');
    }
}