<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login Guru | SMKN 43 Jakarta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #ffffff;
            overflow-x: hidden;
            /* Mencegah bounce effect di iOS */
            position: fixed;
            width: 100%;
            height: 100%;
        }

        /* Blob dioptimalkan untuk mobile (lebih kecil & halus) */
        .bg-blob {
            position: absolute;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            filter: blur(60px);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.5;
            animation: move 15s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(-10%, -10%) rotate(0deg); }
            to { transform: translate(10%, 10%) rotate(90deg); }
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 2rem;
            /* Pada mobile, hilangkan border & shadow untuk kesan clean, 
               pada tablet ke atas gunakan style card */
        }

        @media (min-width: 640px) {
            body { background: #f8fafc; position: relative; }
            .login-container {
                background: white;
                border: 1px solid #e2e8f0;
                border-radius: 2.5rem;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
                margin: 2rem;
            }
        }

        .input-field {
            background: #f8fafc;
            border: 1.5px solid #f1f5f9;
            transition: all 0.2s ease;
            -webkit-appearance: none; /* Fix shadow di iOS */
        }

        .input-field:focus {
            background: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        }

        /* Menghilangkan delay tap di mobile */
        button, a {
            touch-action: manipulation;
        }

        .btn-active:active {
            transform: scale(0.96);
        }
    </style>
</head>
<body class="flex items-center justify-center">

    <div class="bg-blob -top-20 -left-20"></div>
    <div class="bg-blob -bottom-20 -right-20" style="background: #e0f2fe; animation-delay: -5s;"></div>

    <main class="login-container flex flex-col min-h-[100dvh] sm:min-h-0 justify-center">
        
        <div class="flex flex-col items-center mb-10">
            <div class="mb-6 inline-block p-4 bg-white rounded-3xl shadow-sm border border-slate-100">
                <img src="{{ asset('images/logo43.png') }}" 
                     alt="Logo SMKN 43" 
                     class="h-14 w-14 object-contain">
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Login Guru</h1>
            <p class="text-slate-500 mt-1 text-xs font-bold uppercase tracking-[0.15em] text-center">
                SMK Negeri 43 Jakarta
            </p>
            <div class="mt-3 px-3 py-1 bg-blue-50 rounded-full">
                <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Attendance System</span>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-100 text-rose-600 p-4 mb-6 rounded-2xl flex items-center animate-pulse">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <p class="text-[10px] font-black uppercase tracking-wider">{{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('guru.login.submit') }}" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Identity</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" name="username" required
                        class="input-field w-full pl-12 pr-4 py-4 rounded-2xl outline-none text-sm font-bold text-slate-800 placeholder:text-slate-300 placeholder:font-medium" 
                        placeholder="NIP / Username">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Access Key</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" id="password" name="password" required
                        class="input-field w-full pl-12 pr-12 py-4 rounded-2xl outline-none text-sm font-bold text-slate-800 placeholder:text-slate-300" 
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-300 active:text-blue-600 transition">
                        <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between py-2">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-5 h-5 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-500/20 transition-all">
                    <span class="ml-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Ingat Saya</span>
                </label>
            </div>

            <button type="submit" 
                class="btn-active w-full bg-blue-600 text-white font-black py-4 rounded-2xl text-xs uppercase tracking-[0.2em] shadow-lg shadow-blue-200 transition-all active:bg-blue-700">
                Otentikasi
            </button>
        </form>

        <div class="relative py-8">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
            <div class="relative flex justify-center"><span class="bg-white px-4 text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">Quick Links</span></div>
        </div>

        <a href="{{ route('admin.login') }}" 
            class="btn-active flex items-center justify-center w-full bg-slate-50 text-slate-500 font-bold py-4 rounded-2xl transition-all text-[10px] uppercase tracking-widest gap-3 border border-slate-100">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Admin Access
        </a>

        <footer class="mt-auto sm:mt-10 pt-8 text-center">
            <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">
                &copy; 2026 IT SMKN 43 Jakarta
            </p>
        </footer>

    </main>

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