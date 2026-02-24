<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Imports\SiswaImport; 
use Maatwebsite\Excel\Facades\Excel; 
use Illuminate\Support\Facades\Response; 
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa
     */
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        $siswas = $query->latest()->paginate(10);
        $kelases = Kelas::all();

        return view('guru.siswa.index', compact('siswas', 'kelases'));
    }

    /**
     * Simpan Siswa Manual
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis'      => 'required|unique:siswas,nis',
            'nama'     => 'required|string|max:255',
            'jk'       => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id', 
        ], [
            'nis.unique'        => 'NIS/NISN ini sudah terdaftar di kelas lain.',
            'kelas_id.required' => 'Terjadi kesalahan: ID Kelas tidak ditemukan.',
        ]);

        Siswa::create([
            'nis'      => $request->nis,
            'nama'     => strtoupper($request->nama),
            'jk'       => $request->jk,
            'kelas_id' => $request->kelas_id,
        ]);

        return back()->with('success', 'Siswa ' . strtoupper($request->nama) . ' berhasil ditambahkan!');
    }

    /**
     * Fitur Import Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file'     => 'required|mimes:xlsx,xls,csv|max:2048',
            'kelas_id' => 'required|exists:kelas,id'
        ], [
            'file.required' => 'File Excel wajib diunggah.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv',
            'file.max'      => 'Ukuran file maksimal 2MB'
        ]);

        try {
            // Kita bungkus proses import
            Excel::import(new SiswaImport($request->kelas_id), $request->file('file'));
            
            return back()->with('success', 'Data berhasil di-import ke kelas yang dipilih.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Menangkap error validasi data (misal NIS duplikat di tengah baris)
            $failures = $e->failures();
            return back()->with('error', 'Validasi gagal di baris ' . $failures[0]->row() . ': ' . $failures[0]->errors()[0]);

        } catch (\ErrorException $e) {
            // Menangkap error jika kolom di Excel tidak ada (Undefined index)
            return back()->with('error', 'Kolom Excel tidak dikenali. Pastikan Header sesuai template (nis, nama, jk).');

        } catch (\Exception $e) {
            // Menangkap error sistem lainnya
            Log::error($e->getMessage());
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    /**
     * Download Template CSV
     */
    public function downloadTemplate()
    {
        // Gunakan nama kolom huruf kecil agar sinkron dengan $row['nis'], dll di Import
        $columns = ['nis', 'nama', 'jk'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan BOM agar Excel tidak berantakan saat baca CSV
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);
            
            // Baris Contoh (Dummy)
            fputcsv($file, ['1234567890', 'AHMAD JUNAEDI', 'L']);
            fputcsv($file, ['1234567891', 'SITI AISYAH', 'P']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_siswa_import.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }

    /**
     * Hapus Siswa
     */
    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $nama = $siswa->nama;
            $siswa->delete();

            return back()->with('success', "Siswa $nama telah berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal menghapus data.");
        }
    }
}