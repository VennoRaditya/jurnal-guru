<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
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
        return view('admin.dashboard', [
            'total_guru' => Guru::count(),
            'total_siswa' => Siswa::count()
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

        return back()->with('success', 'Data murid berhasil ditambahkan.');
    }

    public function siswaDestroy($id) 
    {
        Siswa::destroy($id);
        return back()->with('success', 'Data murid berhasil dihapus.');
    }

    // --- MANAJEMEN GURU ---

    public function guruIndex() 
    {
        // Mengambil data guru terbaru dengan pagination
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

        // Logika Generate Email Otomatis: nama.guru@sekolah.sch.id
        $cleanName = strtolower(str_replace(' ', '.', $request->nama));
        $generatedEmail = $cleanName . '@sekolah.sch.id';

        Guru::create([
            'nip'      => $request->nip,
            'nama'     => $request->nama,
            'mapel'    => $request->mapel,
            'email'    => $generatedEmail,
            'password' => Hash::make($request->nip), // Password default adalah NIP
        ]);

        return back()->with('success', 'Data guru ' . $request->nama . ' berhasil ditambahkan.');
    }

    public function guruDestroy($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return back()->with('success', 'Data guru berhasil dihapus dari sistem.');
    }
}