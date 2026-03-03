<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kartu kelas.
     */
    public function index()
    {
        $kelases = Kelas::withCount('siswas')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        return view('admin.kelas.index', compact('kelases'));
    }

    /**
     * Fungsi khusus untuk mengambil data siswa via AJAX (Fetch).
     * Digunakan oleh Alpine.js di bagian detail.
     */
    public function getSiswa($id)
    {
        $siswas = Siswa::where('kelas_id', $id)
            ->orderBy('nama', 'asc')
            ->get();

        return response()->json($siswas);
    }

    /**
     * Menyimpan kelas baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
        ]);

        Kelas::create($request->all());

        return redirect()->back()->with('success', 'Kelas berhasil dibuat.');
    }

    /**
     * Menghapus kelas beserta siswanya (cascade manually).
     * Menggunakan respons JSON untuk Alpine.js agar update instan.
     */
    public function destroy(Kelas $kelas)
    {
        try {
            DB::transaction(function () use ($kelas) {
                // 1. HAPUS SEMUA SISWA TERLEBIH DAHULU
                // Menggunakan delete() langsung pada relationship akan memicu query delete
                $kelas->siswas()->delete();

                // 2. HAPUS KELASNYA
                $kelas->delete();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Kelas dan seluruh siswa di dalamnya berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal hapus kelas: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus kelas. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * FUNGSI: Kosongkan siswa dalam kelas.
     */
    public function clearSiswa($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);

            // Gunakan transaksi untuk memastikan konsistensi data
            DB::transaction(function () use ($kelas) {
                // Hapus semua siswa di kelas tersebut
                $kelas->siswas()->delete();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Semua siswa berhasil dihapus dari kelas'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Gagal hapus siswa: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengosongkan kelas.'
            ], 500);
        }
    }

    /**
     * Hapus siswa spesifik dari kelas.
     */
    public function destroySiswa($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasId = $siswa->kelas_id;

        $siswa->delete();

        return redirect()->back()
            ->with('success', 'Siswa berhasil dihapus dari kelas.')
            ->with('last_kelas_id', $kelasId);
    }

    /**
     * Tambah siswa baru secara manual ke kelas.
     */
    public function storeSiswa(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nis'      => 'required|string|unique:siswas,nis',
            'nama'     => 'required|string|max:255',
            'jk'       => 'required|in:L,P',
        ]);

        Siswa::create($request->all());

        return redirect()->back()
            ->with('success', "Siswa {$request->nama} berhasil ditambahkan.")
            ->with('last_kelas_id', $request->kelas_id);
    }

    /**
     * Import siswa via Excel.
     */
    public function importSiswa(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'file'     => 'required|mimes:xlsx,csv,xls',
        ]);

        try {
            Excel::import(new SiswaImport($request->kelas_id), $request->file('file'));
            
            return redirect()->back()
                ->with('success', 'Data siswa berhasil diimport.')
                ->with('last_kelas_id', $request->kelas_id);
                
        } catch (\Exception $e) {
            Log::error('Import gagal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengimport data siswa. Periksa format file.');
        }
    }

    /**
     * Download template excel untuk import.
     */
    public function downloadTemplate()
    {
        $path = storage_path('app/templates/template_siswa.xlsx');

        if (file_exists($path)) {
            return response()->download($path);
        }

        return redirect()->back()->with('error', 'Template file tidak ditemukan.');
    }
}