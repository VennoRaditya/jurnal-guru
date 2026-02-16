<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru; // Pastikan Model di-import

class GuruAuthController extends Controller
{
    public function loginForm()
    {
        // Jika sudah login, jangan kasih lihat halaman login lagi, langsung ke dashboard
        if (Auth::guard('guru')->check()) {
            return redirect()->route('guru.dashboard');
        }
        return view('guru.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Ambil nilai "Remember Me" dari checkbox (jika ada di form)
        $remember = $request->has('remember');

        // 2. Coba Login menggunakan Guard 'guru'
        if (Auth::guard('guru')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Berikan notifikasi sukses (optional)
            return redirect()->intended('/guru/dashboard')
                             ->with('success', 'Selamat datang kembali!');
        }

        // --- BAGIAN DEBUGGING (Hapus jika sudah berhasil login) ---
        $userExists = Guru::where('email', $request->email)->first();
        if (!$userExists) {
            return back()->with('error', 'Email tidak terdaftar di database.');
        }
        // Jika user ada tapi gagal, berarti password di database tidak cocok dengan input
        // ---------------------------------------------------------

        // 3. Jika gagal umum
        return back()->withInput($request->only('email'))
                     ->with('error', 'Email atau Password salah!');
    }

    public function logout(Request $request)
    {
        Auth::guard('guru')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/guru/login')->with('success', 'Anda telah berhasil keluar.');
    }
}