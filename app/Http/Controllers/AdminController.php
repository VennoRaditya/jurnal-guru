<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // --- AUTHENTICATION ---

    public function loginPage() 
    {
        return view('admin.login'); 
    }

    public function loginSubmit(Request $request) 
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Email atau Password Admin salah!');
    }

    public function logout(Request $request) 
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // --- DASHBOARD ---

    public function dashboard() 
    {
        // Mengambil log aktivitas terbaru untuk ditampilkan
        $last_log = ActivityLog::latest()->first();

        return view('admin.dashboard', [
            'total_guru' => Guru::count(),
            'total_siswa' => Siswa::count(),
            'total_kelas' => Kelas::count(),
            'recent_teachers' => Guru::latest()->take(3)->get(),
            'last_log' => $last_log, 
        ]);
    }

    // --- MANAJEMEN SISWA ---

    public function siswaIndex(Request $request) 
    {
        $query = Siswa::query();
        if ($request->tingkat && $request->jurusan) {
            $query->where('kelas', 'LIKE', $request->tingkat . ' ' . $request->jurusan . '%');
        }
        $siswas = $query->paginate(10);
        return view('admin.siswa.index', compact('siswas'));
    }

    public function siswaStore(Request $request) 
    {
        $request->validate([
            'nis' => 'required|unique:siswas',
            'nama' => 'required',
            'tingkat_temp' => 'required',
            'jurusan_temp' => 'required',
        ]);

        Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->tingkat_temp . ' ' . $request->jurusan_temp
        ]);

        // Tambahkan log aktivitas
        ActivityLog::create([
            'description' => 'Siswa baru ditambahkan: ' . $request->nama
        ]);

        return back()->with('success', 'Data murid berhasil ditambahkan.');
    }

    public function siswaDestroy($id) 
    {
        $siswa = Siswa::findOrFail($id);
        $namaSiswa = $siswa->nama;
        $siswa->delete();

        // Tambahkan log aktivitas
        ActivityLog::create([
            'description' => 'Data siswa dihapus: ' . $namaSiswa
        ]);

        return back()->with('success', 'Data murid berhasil dihapus.');
    }

    // --- MANAJEMEN GURU ---

    public function guruIndex() 
    {
        $gurus = Guru::latest()->paginate(10);
        return view('admin.guru', compact('gurus'));
    }

    public function guruStore(Request $request)
    {
        $request->validate([
            'nip'   => 'required|unique:gurus,nip',
            'nama'  => 'required',
            'mapel' => 'required',
        ]);

        $cleanName = strtolower(str_replace(' ', '.', $request->nama));
        $generatedEmail = $cleanName . '@sekolah.sch.id';

        Guru::create([
            'nip'      => $request->nip,
            'nama'     => $request->nama,
            'mapel'    => $request->mapel,
            'email'    => $generatedEmail,
            'password' => Hash::make($request->nip),
        ]);

        // Tambahkan log aktivitas
        ActivityLog::create([
            'description' => 'Guru baru ditambahkan: ' . $request->nama
        ]);

        return back()->with('success', 'Data guru ' . $request->nama . ' berhasil ditambahkan.');
    }

    public function guruUpdate(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nip'   => 'required|unique:gurus,nip,' . $guru->id,
            'nama'  => 'required',
            'mapel' => 'required',
        ]);

        $guru->update([
            'nip'   => $request->nip,
            'nama'  => $request->nama,
            'mapel' => $request->mapel,
        ]);

        // Tambahkan log aktivitas
        ActivityLog::create([
            'description' => 'Data guru diubah: ' . $request->nama
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function guruDestroy($id)
    {
        $guru = Guru::findOrFail($id);
        $namaGuru = $guru->nama;
        $guru->delete();

        // Tambahkan log aktivitas
        ActivityLog::create([
            'description' => 'Data guru dihapus: ' . $namaGuru
        ]);

        return back()->with('success', 'Data guru berhasil dihapus dari sistem.');
    }
}