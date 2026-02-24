<?php

namespace App\Http\Controllers\Admin; // Sesuaikan dengan namespace lo

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas; // Pastikan Model Kelas di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        // 1. Ambil data guru dengan pagination
        $gurus = Guru::latest()->paginate(10);

        // 2. AMBIL SEMUA DATA KELAS (Ini yang bikin form lo nggak kosong lagi)
        $kelases = Kelas::all(); 

        // 3. Kirim ke view index.blade.php
        return view('admin.guru.index', compact('gurus', 'kelases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:gurus,nip',
            'nama' => 'required',
            'mapel' => 'required',
            'username' => 'required|unique:gurus,username',
            'password' => 'required|min:6',
            'kelas' => 'nullable|array', // Menghandle checkbox kelas[]
        ]);

        Guru::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'mapel' => $request->mapel,
            'username' => $request->username,
            'password' => Hash::make($request->password), // Password harus di-hash
            'kelas' => $request->kelas, // Laravel otomatis cast array ke JSON jika di model sudah di-cast
        ]);

        return redirect()->back()->with('success', 'Data Guru berhasil didaftarkan.');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return redirect()->back()->with('success', 'Data Guru berhasil dihapus.');
    }
}