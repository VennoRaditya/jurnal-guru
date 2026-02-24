<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Materi;
use App\Models\Absensi;
use App\Models\Kelas; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    /**
     * TAMPILAN: Pilih Kelas
     */
    public function selectClass()
    {
        $guru = Auth::guard('guru')->user();
        
        // Memastikan data kelas ada. Jika disimpan sebagai string koma di DB, gunakan explode
        $kelasDiampu = is_array($guru->kelas) ? $guru->kelas : explode(',', $guru->kelas);

        return view('guru.absensi.select', compact('kelasDiampu'));
    }

    /**
     * TAMPILAN: Form Absensi Siswa
     */
    public function create(Request $request)
    {
        $request->validate([
            'kelas' => 'required|string',
        ]);

        $kelas_nama = trim($request->kelas);
        
        // 1. Cari data kelas di tabel 'kelas' berdasarkan nama
        $kelas = Kelas::where('nama_kelas', $kelas_nama)->first();

        if (!$kelas) {
            return redirect()->back()->with('error', "Data struktur kelas $kelas_nama tidak ditemukan di database.");
        }

        // 2. Ambil siswa berdasarkan kelas_id (ini yang bikin data muncul!)
        $siswas = Siswa::where('kelas_id', $kelas->id)
                    ->orderBy('nama', 'asc')
                    ->get();

        // 3. Fallback: Jika kelas_id kosong, coba cari manual via string 'kelas' 
        // (Hanya untuk jaga-jaga jika ada data lama yang belum ter-update kelas_id-nya)
        if ($siswas->isEmpty()) {
            $siswas = Siswa::where('kelas', 'LIKE', '%' . $kelas_nama . '%')
                        ->orderBy('nama', 'asc')
                        ->get();
        }

        return view('guru.absensi.index', [
            'siswas' => $siswas,
            'kelas_nama' => $kelas_nama,
            'kelas_id' => $kelas->id // Kirim ID kelas juga ke view
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

            // Simpan Jurnal Materi
            $materi = Materi::create([
                'guru_id'        => Auth::guard('guru')->id(),
                'judul_materi'   => $request->judul_materi,
                'pembahasan'     => $request->pembahasan,
                'mata_pelajaran' => $request->mata_pelajaran,
                'kelas'          => $request->kelas, // Simpan nama kelas
                'tanggal'        => now()->toDateString(),
            ]);

            $dataAbsensi = [];
            foreach ($request->absen as $siswa_id => $status) {
                $dataAbsensi[] = [
                    'materi_id'  => $materi->id,
                    'siswa_id'   => $siswa_id,
                    'status'     => $status,
                    'tanggal'    => now()->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Insert massal untuk performa lebih cepat
            Absensi::insert($dataAbsensi);

            DB::commit();

            return redirect()->route('guru.materi.index')
                             ->with('success', "Jurnal & Presensi kelas $request->kelas berhasil disimpan!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal simpan jurnal: " . $e->getMessage());

            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function index() 
    {
        $riwayatMateri = Materi::where('guru_id', Auth::guard('guru')->id())
            ->latest()
            ->paginate(10);

        return view('guru.materi.index', compact('riwayatMateri'));
    }
}