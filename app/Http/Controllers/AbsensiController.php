<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Materi;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * TAMPILAN: Riwayat Jurnal (DENGAN FILTER TANGGAL/BULAN)
     */
    public function index(Request $request) 
    {
        $guru_id = Auth::guard('guru')->id();
        $periodeInput = $request->query('bulan_tahun');
        
        $date = $periodeInput ? Carbon::parse($periodeInput) : Carbon::today();

        $riwayatMateri = Materi::where('guru_id', $guru_id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month)
            ->with(['absensi' => function($query) {
                $query->whereIn('status', ['sakit', 'izin', 'alfa']);
            }])
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString(); 

        return view('guru.materi.index', compact('riwayatMateri'));
    }

    /**
     * TAMPILAN: Rekap Absensi (HANYA Sakit, Izin, Alfa)
     * URL: /guru/siswa/absensi
     */
    public function rekapHarian(Request $request)
    {
        $guru_id = Auth::guard('guru')->id();
        $tanggal = $request->query('tanggal', Carbon::today()->toDateString());

        $riwayatJurnal = Materi::where('guru_id', $guru_id)
            ->whereDate('tanggal', $tanggal)
            ->with(['absensi' => function ($query) {
                $query->whereIn('status', ['sakit', 'izin', 'alfa'])
                      ->with('siswa');
            }])
            ->latest()
            ->get();

        return view('guru.siswa.absensi', [
            'riwayatJurnal' => $riwayatJurnal,
            'tanggal'       => $tanggal
        ]);
    }

    /**
     * PROSES: Cetak PDF KHUSUS Harian Ketidakhadiran
     * MENGGUNAKAN VIEW: pdf.blade.php
     */
    public function cetakHarian(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        Carbon::setLocale('id');
        
        $tanggal = $request->query('tanggal', Carbon::today()->toDateString());

        $riwayatJurnal = Materi::where('guru_id', $guru->id)
            ->whereDate('tanggal', $tanggal)
            ->with(['absensi' => function ($query) {
                $query->whereIn('status', ['sakit', 'izin', 'alfa'])
                      ->with('siswa');
            }])
            ->latest()
            ->get();

        if ($riwayatJurnal->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data untuk dicetak pada tanggal ini.');
        }

        $data = [
            'title'         => 'Laporan Ketidakhadiran Siswa', // Fix: Menghindari error undefined variable $title
            'riwayatJurnal' => $riwayatJurnal,
            'tanggal'       => $tanggal,
            'guru'          => $guru,
            'nama_guru'     => $guru->nama,
            'nip'           => $guru->nip ?? '-',
            'periode'       => Carbon::parse($tanggal)->translatedFormat('d F Y'),
        ];

        // MENGARAH KE guru.absensi.pdf (pdf.blade.php)
        return Pdf::loadView('guru.absensi.pdf', $data)
                  ->setPaper('a4', 'portrait')
                  ->stream("Ketidakhadiran_".$tanggal.".pdf");
    }

    /**
     * PROSES: Cetak PDF Rekap Jurnal Lengkap
     * MENGGUNAKAN VIEW: rekap_pdf.blade.php
     */
    public function cetakPdf(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        Carbon::setLocale('id');
        
        $tanggal = $request->query('tanggal', Carbon::today()->toDateString());

        $jurnals = Materi::where('guru_id', $guru->id)
            ->whereDate('tanggal', $tanggal)
            ->with(['absensi' => function($query) {
                $query->whereIn('status', ['sakit', 'izin', 'alfa'])->with('siswa');
            }])
            ->orderBy('created_at', 'asc')
            ->get();

        if ($jurnals->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data jurnal pada tanggal ini');
        }
        
        $data = [
            'title'     => 'Laporan Jurnal Mengajar',
            'jurnals'   => $jurnals, 
            'guru'      => $guru,
            'nama_guru' => $guru->nama,
            'nip'       => $guru->nip ?? '-',
            'periode'   => Carbon::parse($tanggal)->translatedFormat('d F Y'),
            'tanggal'   => Carbon::today()->translatedFormat('d F Y'),
        ];

        // MENGARAH KE guru.absensi.rekap_pdf (rekap_pdf.blade.php)
        return Pdf::loadView('guru.absensi.rekap_pdf', $data)
                  ->setPaper('a4', 'portrait')
                  ->download("Rekap_Jurnal_".$tanggal.".pdf");
    }

    /**
     * TAMPILAN: Pilih Kelas
     */
    public function selectClass()
    {
        $guru = Auth::guard('guru')->user();
        $kelasDiampu = is_array($guru->kelas) ? $guru->kelas : explode(',', $guru->kelas);
        $kelasDiampu = array_map('trim', $kelasDiampu);

        return view('guru.absensi.select', compact('kelasDiampu'));
    }

    /**
     * TAMPILAN: Form Input Absensi
     */
    public function create(Request $request)
    {
        $request->validate(['kelas' => 'required|string']);

        $kelas_nama = trim($request->kelas);
        $kelas = Kelas::where('nama_kelas', $kelas_nama)->first();

        if (!$kelas) {
            return redirect()->back()->with('error', "Data kelas $kelas_nama tidak ditemukan.");
        }

        $siswas = Siswa::where('kelas_id', $kelas->id)
                    ->orWhere('kelas', 'LIKE', '%' . $kelas_nama . '%')
                    ->orderBy('nama', 'asc')
                    ->get();

        return view('guru.absensi.index', [
            'siswas' => $siswas,
            'kelas_nama' => $kelas_nama,
            'kelas_id' => $kelas->id 
        ]);
    }

    /**
     * PROSES: Simpan Jurnal & Absensi
     */
    public function storeJurnal(Request $request)
    {
        $request->validate([
            'materi_kd'             => 'required|string|max:255',
            'kegiatan_pembelajaran' => 'required|string',
            'evaluasi'              => 'required|string',
            'mata_pelajaran'        => 'required|string|max:255',
            'kelas'                 => 'required|string',
            'absen'                 => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            $tanggalHariIni = Carbon::today()->toDateString();

            $materi = Materi::create([
                'guru_id'               => Auth::guard('guru')->id(),
                'materi_kd'             => $request->materi_kd,
                'kegiatan_pembelajaran' => $request->kegiatan_pembelajaran,
                'evaluasi'              => $request->evaluasi,
                'mata_pelajaran'        => $request->mata_pelajaran,
                'kelas'                 => $request->kelas,
                'tanggal'               => $tanggalHariIni,
            ]);

            $dataAbsensi = [];
            foreach ($request->absen as $siswa_id => $status) {
                $dataAbsensi[] = [
                    'materi_id'  => $materi->id,
                    'siswa_id'   => $siswa_id,
                    'status'     => $status,
                    'tanggal'    => $tanggalHariIni,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            Absensi::insert($dataAbsensi);
            DB::commit();

            return redirect()->route('guru.materi.index')
                             ->with('success', "Jurnal & Presensi berhasil disimpan!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal simpan jurnal: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    /**
     * PROSES: Hapus Jurnal
     */
    public function destroy($id)
    {
        try {
            $materi = Materi::where('id', $id)
                            ->where('guru_id', Auth::guard('guru')->id())
                            ->firstOrFail();
            
            Absensi::where('materi_id', $id)->delete();
            $materi->delete();

            return redirect()->back()->with('success', 'Riwayat jurnal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}