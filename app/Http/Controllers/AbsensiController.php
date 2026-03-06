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
use App\Exports\AbsensiExport; 
use Maatwebsite\Excel\Facades\Excel; 
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * TAMPILAN: Riwayat Jurnal (Halaman Utama Jurnal)
     */
    public function index(Request $request) 
    {
        $guru_id = Auth::guard('guru')->id();
        $periodeInput = $request->query('bulan_tahun', Carbon::today()->format('Y-m'));
        
        try {
            $date = Carbon::parse($periodeInput . "-01");
        } catch (\Exception $e) {
            $date = Carbon::today();
            $periodeInput = $date->format('Y-m');
        }

        $riwayatMateri = Materi::where('guru_id', $guru_id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month)
            ->with(['kelas', 'absensi.siswa'])
            ->latest('tanggal')
            ->paginate(15)
            ->withQueryString(); 

        return view('guru.materi.index', compact('riwayatMateri', 'periodeInput'));
    }

    /**
     * TAMPILAN: Rekap Absensi Bulanan (Web View)
     */
    public function rekapHarian(Request $request)
    {
        $guru_id = Auth::guard('guru')->id();
        $periodeInput = $request->query('bulan_tahun', Carbon::today()->format('Y-m'));
        $kelasFilter = $request->query('kelas'); 
        
        try {
            $date = Carbon::parse($periodeInput . "-01");
        } catch (\Exception $e) {
            $date = Carbon::today();
            $periodeInput = $date->format('Y-m');
        }

        $query = Materi::where('guru_id', $guru_id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month);

        if ($kelasFilter && $kelasFilter !== '') {
            $query->where('kelas', $kelasFilter);
        }

        $riwayatJurnal = $query->with(['kelas', 'absensi.siswa'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.siswa.absensi', compact('riwayatJurnal', 'periodeInput', 'date', 'kelasFilter'));
    }

    /**
     * PROSES: Update Status Absensi via Modal
     */
    public function updateAbsensi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Hadir,Sakit,Izin,Terlambat,Alfa,hadir,sakit,izin,terlambat,alfa'
        ]);

        try {
            $absensi = Absensi::whereHas('materi', function($q) {
                $q->where('guru_id', Auth::guard('guru')->id());
            })->findOrFail($id);

            $absensi->status = strtolower($request->status);
            $absensi->save();

            return redirect()->back()->with('success', "Status absensi " . ($absensi->siswa->nama ?? '') . " berhasil diperbarui.");
        } catch (\Exception $e) {
            Log::error("Gagal update absensi: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * PROSES: Cetak PDF Rekap Bulanan (Jurnal & Absensi Lengkap)
     */
    public function rekapPdf(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        Carbon::setLocale('id');
        
        $periodeInput = $request->query('bulan_tahun', Carbon::today()->format('Y-m'));
        $kelasFilter = $request->query('kelas');
        
        try {
            $date = Carbon::parse($periodeInput . "-01");
        } catch (\Exception $e) {
            $date = Carbon::today();
        }

        $query = Materi::where('guru_id', $guru->id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month);

        if ($kelasFilter && $kelasFilter !== '') {
            $query->where('kelas', $kelasFilter);
        }

        $riwayatJurnal = $query->with(['kelas', 'absensi.siswa'])->orderBy('tanggal', 'asc')->get();

        $data = [
            'title'         => 'REKAP ABSENSI SISWA',
            'riwayatJurnal' => $riwayatJurnal,
            'date'          => $date, 
            'year'          => $date->year,
            'month'         => $date->month,
            'nama_guru'     => $guru->nama,
            'nip'           => $guru->nip ?? '-',
            'periode'       => $date->translatedFormat('F Y'),
            'kelas'         => $kelasFilter ?? 'Semua Kelas'
        ];

        return Pdf::loadView('guru.absensi.rekap_pdf', $data)
                    ->setPaper('a4', 'landscape') 
                    ->stream("Rekap_Absensi_".$periodeInput.".pdf");
    }

    /**
     * PROSES: Cetak PDF Khusus Absensi (Ketidakhadiran Siswa)
     * Ditujukan untuk file view: resources/views/guru/absensi/pdf.blade.php
     */
    public function absensiPdf(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        Carbon::setLocale('id');
        
        $periodeInput = $request->query('bulan_tahun', Carbon::today()->format('Y-m'));
        $kelasFilter = $request->query('kelas');
        
        try {
            $date = Carbon::parse($periodeInput . "-01");
        } catch (\Exception $e) {
            $date = Carbon::today();
        }

        $query = Materi::where('guru_id', $guru->id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month);

        if ($kelasFilter && $kelasFilter !== '') {
            $query->where('kelas', $kelasFilter);
        }

        $riwayatJurnal = $query->with(['kelas', 'absensi.siswa'])
            ->orderBy('tanggal', 'asc')
            ->get();

        $data = [
            'title'         => 'LAPORAN KETIDAKHADIRAN SISWA',
            'riwayatJurnal' => $riwayatJurnal,
            'date'          => $date, 
            'year'          => $date->year,
            'month'         => $date->month,
            'nama_guru'     => $guru->nama,
            'nip'           => $guru->nip ?? '-',
            'periode'       => $date->translatedFormat('F Y'),
            'kelas'         => $kelasFilter ?? 'Semua Kelas'
        ];

        return Pdf::loadView('guru.absensi.pdf', $data)
                    ->setPaper('a4', 'portrait') 
                    ->stream("Laporan_Absensi_".$periodeInput.".pdf");
    }

    /**
     * PROSES: Export Excel
     */
    public function exportExcel(Request $request)
    {
        $guru_id = Auth::guard('guru')->id();
        $periodeInput = $request->query('bulan_tahun', Carbon::today()->format('Y-m'));
        $kelasFilter = $request->query('kelas');

        try {
            $date = Carbon::parse($periodeInput . "-01");
        } catch (\Exception $e) {
            $date = Carbon::today();
        }

        $dataSiswa = Siswa::whereHas('absensi.materi', function($q) use ($guru_id, $date) {
                $q->where('guru_id', $guru_id)
                  ->whereYear('tanggal', $date->year)
                  ->whereMonth('tanggal', $date->month);
            })
            ->when($kelasFilter, function($q) use ($kelasFilter) {
                return $q->whereHas('kelas', function($k) use ($kelasFilter) {
                    $k->where('nama_kelas', $kelasFilter);
                });
            })
            ->with(['absensi' => function($q) use ($date, $guru_id) {
                $q->whereYear('tanggal', $date->year)
                  ->whereMonth('tanggal', $date->month)
                  ->whereHas('materi', function($m) use ($guru_id) {
                      $m->where('guru_id', $guru_id);
                  });
            }, 'kelas'])
            ->get();

        $fileName = 'Rekap_Absensi_' . ($kelasFilter ?? 'Semua_Kelas') . '_' . $periodeInput . '.xlsx';
        return Excel::download(new AbsensiExport($dataSiswa), $fileName);
    }

    /**
     * PROSES: Simpan Jurnal & Absensi (Input Harian)
     */
    public function storeJurnal(Request $request)
    {
        $request->validate([
            'materi_kd'             => 'required|string|max:255',
            'kegiatan_pembelajaran' => 'required|string',
            'evaluasi'              => 'required|string',
            'mata_pelajaran'        => 'required|string|max:255',
            'kelas'                 => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $tanggalHariIni = Carbon::today()->toDateString();

            $kelas = Kelas::where('nama_kelas', $request->kelas)->first();
            if (!$kelas) throw new \Exception("Kelas $request->kelas tidak ditemukan.");

            $materi = Materi::create([
                'guru_id'               => Auth::guard('guru')->id(),
                'kelas_id'              => $kelas->id,
                'materi_kd'             => $request->materi_kd,
                'kegiatan_pembelajaran' => $request->kegiatan_pembelajaran,
                'evaluasi'              => $request->evaluasi,
                'mata_pelajaran'        => $request->mata_pelajaran,
                'kelas'                 => $request->kelas,
                'tanggal'               => $tanggalHariIni,
            ]);

            $semuaSiswa = Siswa::where('kelas_id', $kelas->id)->get();
            $dataAbsensi = [];

            foreach ($semuaSiswa as $siswa) {
                $status = 'hadir';
                if ($request->has('absen') && isset($request->absen[$siswa->id])) {
                    $status = strtolower($request->absen[$siswa->id]);
                }

                $dataAbsensi[] = [
                    'materi_id'  => $materi->id,
                    'siswa_id'   => $siswa->id,
                    'status'     => $status, 
                    'tanggal'    => $tanggalHariIni,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($dataAbsensi)) {
                Absensi::insert($dataAbsensi);
            }

            DB::commit();
            return redirect()->route('guru.materi.index')->with('success', "Jurnal & Presensi berhasil disimpan!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal simpan jurnal: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * TAMPILAN: Pilih Kelas sebelum Absen
     */
    public function selectClass()
    {
        $guru = Auth::guard('guru')->user();
        $kelasDiampu = is_array($guru->kelas) ? $guru->kelas : explode(',', $guru->kelas);
        $kelasDiampu = array_map('trim', $kelasDiampu);
        
        return view('guru.absensi.select', compact('kelasDiampu'));
    }

    /**
     * TAMPILAN: Form Input Absensi & Jurnal
     */
    public function create(Request $request)
    {
        $request->validate(['kelas' => 'required|string']);
        $kelas_nama = trim($request->kelas);
        
        $kelas = Kelas::where('nama_kelas', $kelas_nama)->first();
        if (!$kelas) return redirect()->back()->with('error', "Data kelas tidak ditemukan.");

        $siswas = Siswa::where('kelas_id', $kelas->id)->orderBy('nama', 'asc')->get();
        
        return view('guru.absensi.index', [
            'siswas'     => $siswas,
            'kelas_nama' => $kelas_nama,
            'kelas_id'   => $kelas->id 
        ]);
    }

    /**
     * PROSES: Hapus Jurnal & Absensi Terkait
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $materi = Materi::where('id', $id)->where('guru_id', Auth::guard('guru')->id())->firstOrFail();
            
            Absensi::where('materi_id', $id)->delete();
            $materi->delete();
            
            DB::commit();
            return redirect()->back()->with('success', 'Riwayat jurnal berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal hapus jurnal: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}