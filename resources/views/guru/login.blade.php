<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Guru | Jurnal Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="login-card p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] w-full max-w-md fade-in">
        
        <div class="flex justify-center mb-8">
            <div class="relative">
                <div class="absolute inset-0 bg-blue-500 blur-2xl opacity-20 animate-pulse"></div>
                <div class="relative bg-linear-to-tr from-blue-600 to-indigo-600 p-4 rounded-3x1 shadow-xl shadow-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Portal Pengajar</h2>
            <p class="text-slate-500 mt-2 font-medium text-sm px-4 leading-relaxed">Kelola jurnal mengajar dan absensi digital dalam satu pintu.</p>
        </div>

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-100 text-rose-600 p-4 mb-6 rounded-2xl flex items-center animate-shake">
                <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-xs font-bold uppercase tracking-wide">{{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('guru.login.submit') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] ml-1">Email Sekolah</label>
                <input type="email" name="email" 
                    class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-slate-700 placeholder-slate-300" 
                    placeholder="nama@sekolah.sch.id" required>
            </div>

            <div class="space-y-2">
                <div class="flex justify-between items-center ml-1">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Kata Sandi</label>
                    <a href="#" class="text-[10px] font-black text-blue-600 hover:text-indigo-600 transition uppercase tracking-widest">Lupa?</a>
                </div>
                <div class="relative group">
                    <input type="password" id="password" name="password" 
                        class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-slate-700 placeholder-slate-300" 
                        placeholder="••••••••" required>
                    <button type="button" onclick="togglePassword()" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 hover:text-blue-600 transition">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center ml-1">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-blue-600 border-slate-200 rounded-lg focus:ring-blue-500 focus:ring-offset-0 transition cursor-pointer">
                <label for="remember" class="ml-3 text-xs text-slate-500 font-bold cursor-pointer">Tetap masuk di perangkat ini</label>
            </div>

            <button type="submit" 
                class="w-full bg-slate-900 hover:bg-blue-600 text-white font-bold py-5 rounded-2xl transition-all duration-300 shadow-xl shadow-slate-900/10 hover:shadow-blue-500/20 active:scale-[0.98] text-sm uppercase tracking-widest">
                Masuk Ke Dashboard
            </button>
        </form>

        <div class="relative my-10">
            <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t border-slate-100"></span>
            </div>
            <div class="relative flex justify-center text-[10px] uppercase font-black tracking-[0.3em]">
                <span class="bg-white px-6 text-slate-300">Akses Lain</span>
            </div>
        </div>

        <a href="{{ route('admin.login') }}" 
            class="group flex items-center justify-center w-full bg-white border border-slate-100 hover:border-blue-500/30 text-slate-600 font-bold py-4 rounded-2xl transition-all duration-300 active:scale-[0.98] text-[11px] uppercase tracking-widest shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Mode Administrator
        </a>

        <div class="mt-10 text-center">
            <p class="text-slate-300 text-[9px] font-black uppercase tracking-[0.2em]">
                &copy; 2026 IT Management System
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>

</body>
</html>