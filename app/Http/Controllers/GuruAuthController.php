<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use Illuminate\Support\Facades\Session;

class GuruAuthController extends Controller
{
    public function loginForm()
    {
        // Jika sudah login sebagai guru, langsung ke dashboard
        if (Auth::guard('guru')->check()) {
            return redirect()->route('guru.dashboard');
        }
        
        // Antisipasi: Jika sedang login sebagai ADMIN, logoutkan dulu atau redirect
        if (Auth::guard('web')->check()) {
            // Bisa pilih: logout admin dulu atau biarkan. 
            // Tapi sebaiknya admin tidak bisa akses halaman login guru tanpa logout.
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

        $remember = $request->has('remember');

        // 2. Eksekusi Login dengan Guard 'guru'
        if (Auth::guard('guru')->attempt($credentials, $remember)) {
            
            // WAJIB: Regenerate session untuk keamanan dan menghindari 419 setelah login
            $request->session()->regenerate();

            return redirect()->intended(route('guru.dashboard'))
                             ->with('success', 'Selamat datang kembali!');
        }

        // --- LOGIKA PENGECEKAN ERROR SPESIFIK ---
        $user = Guru::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar dalam sistem Guru.');
        }

        // Jika email ada tapi gagal login, berarti password salah
        return back()->withInput($request->only('email'))
                     ->with('error', 'Password yang Anda masukkan salah!');
    }

    public function logout(Request $request)
    {
        Auth::guard('guru')->logout();
        
        // Hapus semua session untuk memastikan tidak ada sisa token yang bentrok
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')->with('success', 'Berhasil logout dari Portal Guru.');
    }
}