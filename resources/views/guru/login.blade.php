<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Guru | SMKN 43 Jakarta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            position: relative;
            overflow-x: hidden;
        }

        .bg-blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.15;
            animation: move 20s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(-10%, -10%); }
            to { transform: translate(20%, 20%); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-field:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
        }

        .logo-container {
            background: white;
            padding: 12px;
            border-radius: 50%;
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.3);
            border: 4px solid rgba(255, 255, 255, 0.1);
        }

        .fade-up {
            animation: fadeUp 0.8s ease-out forwards;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="bg-blob top-0 left-0"></div>
    <div class="bg-blob bottom-0 right-0" style="animation-delay: -10s; background: #8b5cf6;"></div>

    <div class="login-card p-8 md:p-12 rounded-[3rem] w-full max-w-[440px] fade-up">
        
        <div class="flex flex-col items-center mb-10">
            <div class="logo-container mb-6 group transition-transform duration-500 hover:scale-110">
                <img src="{{ asset('images/logo_smkn_43.jpg') }}" 
                     alt="Logo SMKN 43" 
                     class="h-20 w-20 object-contain mix-blend-multiply">
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Portal Guru</h1>
            <p class="text-slate-400 mt-2 text-sm font-medium text-center">
                SMK Negeri 43 Jakarta <br>
                <span class="text-blue-400">Digital Journal & Attendance</span>
            </p>
        </div>

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 mb-8 rounded-2xl flex items-center animate-bounce">
                <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-xs font-bold uppercase tracking-wider">{{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('guru.login.submit') }}" class="space-y-6">
            @csrf

            {{-- UPDATE: LOGIN MENGGUNAKAN USERNAME --}}
            <div class="space-y-2">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] ml-2">Username</label>
                <div class="relative">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <input type="text" name="username" required
                        class="input-field w-full pl-14 pr-6 py-4 rounded-2xl outline-none text-sm font-semibold" 
                        placeholder="Masukkan username">
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex justify-between items-center ml-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Secret Password</label>
                    <a href="#" class="text-[10px] font-bold text-blue-400 hover:text-blue-300 transition uppercase tracking-widest">Forgot?</a>
                </div>
                <div class="relative group">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input type="password" id="password" name="password" required
                        class="input-field w-full pl-14 pr-14 py-4 rounded-2xl outline-none text-sm font-semibold" 
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword()" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center ml-2">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-blue-500 focus:ring-blue-500/20">
                <label for="remember" class="ml-3 text-xs text-slate-400 font-medium cursor-pointer">Remember this session</label>
            </div>

            <button type="submit" 
                class="btn-primary w-full text-white font-extrabold py-5 rounded-2xl text-sm uppercase tracking-[0.2em] mt-4">
                Sign In Now
            </button>
        </form>

        <div class="relative my-10">
            <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t border-slate-800"></span>
            </div>
            <div class="relative flex justify-center text-[10px] uppercase font-bold tracking-[0.4em]">
                <span class="bg-[#1a2133] px-4 text-slate-600 rounded-full">Other Access</span>
            </div>
        </div>

        <a href="{{ route('admin.login') }}" 
            class="flex items-center justify-center w-full border border-slate-800 hover:border-slate-600 text-slate-400 hover:text-white font-bold py-4 rounded-2xl transition-all duration-300 text-[11px] uppercase tracking-widest gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Administrator Only
        </a>

        <div class="mt-10 text-center">
            <p class="text-slate-600 text-[9px] font-bold uppercase tracking-[0.3em]">
                &copy; 2026 IT Dev SMKN 43 Jakarta
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