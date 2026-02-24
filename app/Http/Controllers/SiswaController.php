<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Imports\SiswaImport; 
use Maatwebsite\Excel\Facades\Excel; 
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    // Mengambil data siswa via AJAX (untuk Alpine.js)
    public function getSiswaByKelas($id)
    {
        $siswas = Siswa::where('kelas_id', $id)->orderBy('nama', 'asc')->get();
        return response()->json($siswas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'      => 'required|unique:siswas,nis',
            'nama'     => 'required|string|max:255',
            'jk'       => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::create([
            'nis'      => $request->nis,
            'nama'     => strtoupper($request->nama),
            'jk'       => $request->jk,
            'kelas_id' => $request->kelas_id,
        ]);

        // Kirim flash message dan instruksi ke Alpine untuk buka kembali kelas ini
        return back()->with([
            'success' => 'Siswa berhasil ditambahkan.',
            'last_kelas_id' => $request->kelas_id
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file'     => 'required|mimes:xlsx,xls,csv|max:2048',
            'kelas_id' => 'required|exists:kelas,id'
        ]);

        try {
            Excel::import(new SiswaImport($request->kelas_id), $request->file('file'));
            
            return back()->with([
                'success' => 'Data berhasil di-import!',
                'last_kelas_id' => $request->kelas_id // Simpan ID agar otomatis terbuka
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas_id = $siswa->kelas_id;
        $siswa->delete();

        return back()->with([
            'success' => "Siswa berhasil dihapus.",
            'last_kelas_id' => $kelas_id
        ]);
    }
}