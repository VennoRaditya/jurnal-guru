<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;

class GuruAuthController extends Controller
{
    public function index()
    {
        $gurus = Guru::latest()->paginate(10); 
        $kelases = Kelas::all(); 

        return view('admin.guru', compact('gurus', 'kelases')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'      => 'required|unique:gurus,nip',
            'nama'     => 'required|string|max:255',
            'mapel'    => 'required|string',
            'username' => 'required|unique:gurus,username',
            'password' => 'required|min:6',
            'kelas'    => 'nullable|array' 
        ], [
            'nip.unique'      => 'NIP sudah terdaftar di sistem.',
            'username.unique' => 'Username sudah digunakan guru lain.',
            'password.min'    => 'Password minimal 6 karakter.'
        ]);

        try {
            Guru::create([
                'nip'      => $request->nip,
                'nama'     => $request->nama,
                'mapel'    => $request->mapel,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'kelas'    => $request->kelas, // Laravel akan otomatis mengubah array ke JSON jika dikonfigurasi di Model
            ]);

            return back()->with('success', 'Akun Guru & Kelas Ampuan berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // --- TAMBAHAN: FITUR EDIT ---
    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        $kelases = Kelas::all();
        
        // Mengirim data guru, semua kelas, dan kelas yang sudah diampu (untuk checked di view)
        return view('admin.guru_edit', compact('guru', 'kelases'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nip'      => 'required|unique:gurus,nip,' . $guru->id, // Kecualikan diri sendiri
            'nama'     => 'required|string|max:255',
            'mapel'    => 'required|string',
            'username' => 'required|unique:gurus,username,' . $guru->id,
            'password' => 'nullable|min:6', // Password opsional saat edit
            'kelas'    => 'nullable|array'
        ]);

        try {
            $data = $request->except(['password', 'kelas']);

            // Update password jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Update kelas
            $data['kelas'] = $request->kelas ?? [];

            $guru->update($data);

            return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }
    // ----------------------------

    public function destroy($id)
    {
        try {
            Guru::findOrFail($id)->delete();
            return back()->with('success', 'Data guru berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data.');
        }
    }

    // --- LOGIC AUTH GURU (MENGGUNAKAN USERNAME) ---
    public function loginForm() {
        if (Auth::guard('guru')->check()) return redirect()->route('guru.dashboard');
        return view('guru.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('guru')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('guru.dashboard'))->with('success', 'Selamat datang!');
        }

        return back()->with('error', 'Username atau Password salah!');
    }

    public function logout(Request $request) {
        Auth::guard('guru')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('guru.login');
    }
}