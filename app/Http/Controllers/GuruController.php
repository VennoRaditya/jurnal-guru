<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruAuthController extends Controller
{
    public function loginForm()
    {
        // Bagus: Cek jika sudah login, jangan kasih form login lagi
        if (Auth::guard('guru')->check()) {
            return redirect()->route('guru.dashboard');
        }
        return view('guru.login');
    }

    public function login(Request $request)
    {
        // Validasi simpel biar aplikasi nggak crash kalau input kosong
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('guru')->attempt($credentials)) {
            // PENTING: Bikin session baru demi keamanan
            $request->session()->regenerate();

            // Arahkan ke URL dashboard yang benar (sesuai web.php kamu)
            return redirect()->intended('/guru/dashboard');
        }

        return back()->with('error', 'Email atau Password salah');
    }

    public function logout(Request $request)
    {
        Auth::guard('guru')->logout();

        // Bersihkan session total biar nggak nyangkut
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/guru/login');
    }
}