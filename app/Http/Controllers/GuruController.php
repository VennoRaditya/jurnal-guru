    <?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Validation\ValidationException;

    class GuruAuthController extends Controller
    {
        /**
         * Menampilkan halaman login khusus guru.
         */
        public function loginForm()
        {
            // Cegah guru yang sudah login mengakses halaman login kembali
            if (Auth::guard('guru')->check()) {
                return redirect()->route('guru.dashboard');
            }
            
            return view('guru.login');
        }

        /**
         * Proses autentikasi login guru.
         */
        public function login(Request $request)
        {
            // 1. Validasi Input
            $credentials = $request->validate([
                'username' => ['required', 'string'],
                'password' => ['required'],
            ], [
                'username.required' => 'Username wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]);

            // 2. Attempt Login menggunakan Guard Guru
            // Kita gunakan $request->has('remember') untuk fitur 'Ingat Saya'
            if (Auth::guard('guru')->attempt($credentials, $request->boolean('remember'))) {
                
                // 3. Keamanan Session
                $request->session()->regenerate();

                // 4. Redirect ke Dashboard
                // intended() akan mengirim user ke halaman yang mereka coba akses sebelumnya
                return redirect()->intended(route('guru.dashboard'))
                    ->with('success', 'Berhasil masuk. Selamat mengajar!');
            }

            // 5. Handle Gagal Login
            throw ValidationException::withMessages([
                'username' => ['Kredensial tersebut tidak cocok dengan data kami.'],
            ]);
        }

        /**
         * Proses logout guru.
         */
        public function logout(Request $request)
        {
            // Logout dari guard spesifik
            Auth::guard('guru')->logout();

            // Bersihkan session
            $request->session()->invalidate();

            // Regenerasi CSRF token untuk mencegah serangan CSRF pada session baru
            $request->session()->regenerateToken();

            return redirect()->route('guru.login')
                ->with('success', 'Anda telah berhasil keluar dari sistem.');
        }
    }