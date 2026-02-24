<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class GuruAuthController extends Controller
{
    /**
     * Menampilkan daftar guru dan form registrasi (Halaman Kelola Guru)
     */
    public function index()
    {
        $gurus = Guru::latest()->paginate(10);
        $kelases = Kelas::all();

        return view('admin.guru', compact('gurus', 'kelases'));
    }

    /**
     * Menyimpan data guru baru dari form registrasi
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip'      => 'required|unique:gurus,nip',
            'nama'     => 'required|string|max:255',
            'mapel'    => 'required|string',
            'username' => 'required|unique:gurus,username',
            'email'    => 'nullable|email|unique:gurus,email', // Ditambahkan agar sinkron dengan migration
            'password' => 'required|min:6',
            'kelas'    => 'nullable|array',
        ]);

        Guru::create([
            'nip'      => $request->nip,
            'nama'     => $request->nama,
            'mapel'    => $request->mapel,
            'username' => $request->username,
            // Jika email kosong, kita buatkan dummy berdasarkan username
            'email'    => $request->email ?? $request->username . '@smkn43.sch.id', 
            'password' => Hash::make($request->password),
            'kelas'    => $request->kelas, 
        ]);

        return redirect()->back()->with('success', 'Guru baru berhasil didaftarkan!');
    }

    /**
     * Menghapus data guru
     */
    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return redirect()->back()->with('success', 'Data guru berhasil dihapus.');
    }

    /* --- LOGIC AUTHENTIKASI --- */

    public function loginForm()
    {
        // Jika sudah login, lempar ke dashboard
        if (Auth::guard('guru')->check()) {
            return redirect()->route('guru.dashboard');
        }
        return view('guru.login');
    }

    /**
     * Login menggunakan USERNAME
     */
    public function login(Request $request)
    {
        // Validasi input username dan password
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Attempt login menggunakan guard guru
        if (Auth::guard('guru')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('guru.dashboard'))
                ->with('success', 'Berhasil masuk. Selamat mengajar!');
        }

        // Jika gagal, kembalikan dengan pesan error
        return back()->with('error', 'Username atau password tidak cocok dengan data kami.');
    }

    public function logout(Request $request)
    {
        Auth::guard('guru')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('guru.login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}