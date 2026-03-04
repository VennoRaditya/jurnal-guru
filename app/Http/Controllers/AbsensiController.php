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
     * TAMPILAN: Riwayat Jurnal (FILTER BULAN/TAHUN)
     * Ditambahkan with('kelas') agar nama kelas tampil di tabel riwayat.
     */
    public function index(Request $request) 
    {
        $guru_id = Auth::guard('guru')->id();
        $periodeInput = $request->query('bulan_tahun', Carbon::today()->format('Y-m'));
        $date = Carbon::parse($periodeInput);

        $riwayatMateri = Materi::where('guru_id', $guru_id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month)
            ->with(['kelas', 'absensi' => function($query) {
                $query->whereIn('status', ['sakit', 'izin', 'alfa']);
            }])
            ->latest('tanggal')
            ->paginate(15)
            ->withQueryString(); 

        return view('guru.materi.index', compact('riwayatMateri', 'periodeInput'));
    }

    /**
     * TAMPILAN: Rekap Absensi Bulanan (HANYA Sakit, Izin, Alfa)
     */
    public function rekapHarian(Request $request)
    {
        $guru_id = Auth::guard('guru')->id();
        $periodeInput = $request->query('bulan_tahun', Carbon::today()->format('Y-m'));
        $date = Carbon::parse($periodeInput);

        $riwayatJurnal = Materi::where('guru_id', $guru_id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month)
            ->with(['kelas', 'absensi' => function ($query) {
                $query->whereIn('status', ['sakit', 'izin', 'alfa'])
                      ->with('siswa');
            }])
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('guru.siswa.absensi', compact('riwayatJurnal', 'periodeInput', 'date'));
    }

    /**
     * PROSES: Cetak PDF Rekap Ketidakhadiran Bulanan
     * FIX: Memuat relasi 'kelas' agar tidak muncul "Tanpa Kelas" di PDF.
     */
    public function cetakHarian(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        Carbon::setLocale('id');
        
        $periodeInput = $request->query('bulan_tahun', Carbon::today()->format('Y-m'));
        $date = Carbon::parse($periodeInput);

        $riwayatJurnal = Materi::where('guru_id', $guru->id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month)
            ->with(['kelas', 'absensi' => function ($query) {
                $query->whereIn('status', ['sakit', 'izin', 'alfa'])
                      ->with('siswa');
            }])
            ->orderBy('tanggal', 'asc')
            ->get();

        if ($riwayatJurnal->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data absensi untuk periode ini.');
        }

        $data = [
            'title'         => 'Laporan Ketidakhadiran Siswa Bulanan',
            'riwayatJurnal' => $riwayatJurnal,
            'month'         => (int)$date->month,
            'year'          => (int)$date->year,
            'nama_guru'     => $guru->nama,
            'nip'           => $guru->nip ?? '-',
            'tanggal'       => $periodeInput
        ];

        return Pdf::loadView('guru.absensi.pdf', $data)
                    ->setPaper('a4', 'portrait')
                    ->stream("Ketidakhadiran_Bulanan_".$periodeInput.".pdf");
    }

    /**
     * PROSES: Cetak PDF Rekap Jurnal Mengajar Bulanan
     */
    public function cetakPdf(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        Carbon::setLocale('id');
        
        $periodeInput = $request->query('bulan_tahun', Carbon::today()->format('Y-m'));
        $date = Carbon::parse($periodeInput);

        $riwayatJurnal = Materi::where('guru_id', $guru->id)
            ->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month)
            ->with(['kelas', 'absensi' => function($query) {
                $query->whereIn('status', ['sakit', 'izin', 'alfa'])->with('siswa');
            }])
            ->orderBy('tanggal', 'asc')
            ->get();

        if ($riwayatJurnal->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data jurnal untuk periode ini');
        }
        
        $data = [
            'title'         => 'Laporan Jurnal Mengajar Bulanan',
            'riwayatJurnal' => $riwayatJurnal, 
            'nama_guru'     => $guru->nama,
            'nip'           => $guru->nip ?? '-',
            'month'         => (int)$date->month,
            'year'          => (int)$date->year,
        ];

        return Pdf::loadView('guru.absensi.pdf', $data)
                    ->setPaper('a4', 'portrait')
                    ->download("Rekap_Jurnal_Bulanan_".$periodeInput.".pdf");
    }

    /**
     * PROSES: Simpan Jurnal & Absensi
     * FIX: Menambahkan pencarian kelas_id agar data tersimpan secara relasional.
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

            // Cari ID Kelas berdasarkan nama yang dikirim dari form
            $kelas = Kelas::where('nama_kelas', $request->kelas)->first();
            if (!$kelas) throw new \Exception("Kelas $request->kelas tidak ditemukan di database.");

            $materi = Materi::create([
                'guru_id'               => Auth::guard('guru')->id(),
                'kelas_id'              => $kelas->id, // Menyimpan Foreign Key
                'materi_kd'             => $request->materi_kd,
                'kegiatan_pembelajaran' => $request->kegiatan_pembelajaran,
                'evaluasi'              => $request->evaluasi,
                'mata_pelajaran'        => $request->mata_pelajaran,
                'kelas'                 => $request->kelas, // String backup
                'tanggal'               => $tanggalHariIni,
            ]);

            $semuaSiswa = Siswa::where('kelas_id', $kelas->id)->get();
            $dataAbsensi = [];

            foreach ($semuaSiswa as $siswa) {
                $status = 'hadir';
                if ($request->has('absen') && isset($request->absen[$siswa->id])) {
                    $status = $request->absen[$siswa->id];
                }

                $dataAbsensi[] = [
                    'materi_id'  => $materi->id,
                    'siswa_id'   => $siswa->id,
                    'status'     => strtolower($status), 
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
     * PROSES: Update Status Absensi per Siswa (MODAL EDIT)
     */
    public function updateAbsensi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Sakit,Izin,Alfa,Hadir,sakit,izin,alfa,hadir'
        ]);

        try {
            $absensi = Absensi::findOrFail($id);
            $namaSiswa = $absensi->siswa->nama ?? 'Siswa';
            
            $absensi->status = strtolower($request->status);
            $absensi->save();

            $statusText = ucfirst(strtolower($request->status));
            return redirect()->back()->with('success', "Status $namaSiswa berhasil diubah menjadi $statusText.");
        } catch (\Exception $e) {
            Log::error("Gagal update absensi ID $id: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
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
                        ->orderBy('nama', 'asc')
                        ->get();

        return view('guru.absensi.index', [
            'siswas' => $siswas,
            'kelas_nama' => $kelas_nama,
            'kelas_id' => $kelas->id 
        ]);
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
            Log::error("Gagal hapus jurnal $id: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}