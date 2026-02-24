<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas; // Pastikan Model Kelas sudah dibuat
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
        // Mengambil data guru dengan pagination
        $gurus = Guru::latest()->paginate(10);
        
        // Mengambil semua data kelas untuk ditampilkan di checkbox
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
            'password' => 'required|min:6',
            'kelas'    => 'nullable|array',
        ]);

        Guru::create([
            'nip'      => $request->nip,
            'nama'     => $request->nama,
            'mapel'    => $request->mapel,
            'username' => $request->username,
            'password' => Hash::make($request->password), // Password di-hash
            'kelas'    => $request->kelas, // Disimpan sebagai array (pastikan casts di model)
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
        if (Auth::guard('guru')->check()) {
            return redirect()->route('guru.dashboard');
        }
        return view('guru.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::guard('guru')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('guru.dashboard'))
                ->with('success', 'Berhasil masuk. Selamat mengajar!');
        }

        throw ValidationException::withMessages([
            'username' => ['Kredensial tersebut tidak cocok dengan data kami.'],
        ]);
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