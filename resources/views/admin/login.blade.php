<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin System | Secure Authorization</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617;
            background-image: 
                radial-gradient(at 0% 0%, rgba(30, 64, 175, 0.2) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(76, 29, 149, 0.2) 0px, transparent 50%);
            /* Diubah agar bisa scroll */
            min-height: 100vh;
            overflow-y: auto;
        }

        /* Sembunyikan scrollbar agar tetap estetik (opsional) */
        body::-webkit-scrollbar {
            width: 5px;
        }
        body::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.2);
            border-radius: 10px;
        }

        .bg-grid {
            position: fixed; /* Fixed agar grid tidak ikut tergulung habis */
            inset: 0;
            background-image: linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(circle at center, black, transparent 80%);
            z-index: -1;
        }

        .admin-card {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 10;
        }

        .admin-card::after {
            content: "";
            position: absolute;
            top: -100%;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent, rgba(59, 130, 246, 0.05), transparent);
            animation: scan 4s linear infinite;
            pointer-events: none;
        }

        @keyframes scan {
            0% { top: -100%; }
            100% { top: 100%; }
        }

        .input-dark {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f8fafc;
            transition: all 0.3s ease;
        }

        .input-dark:focus {
            border-color: #3b82f6;
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.15);
        }

        .btn-execute {
            background: white;
            color: black;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-execute:hover {
            background: #3b82f6;
            color: white;
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.4);
            transform: scale(1.02);
        }

        .fade-in-blur {
            animation: fadeInBlur 1s ease-out forwards;
        }

        @keyframes fadeInBlur {
            from { opacity: 0; filter: blur(10px); transform: scale(0.95); }
            to { opacity: 1; filter: blur(0); transform: scale(1); }
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-6">

    <div class="bg-grid"></div>

    <div class="py-12 w-full flex justify-center">
        <div class="admin-card p-10 md:p-14 rounded-4x1 w-full max-w-md fade-in-blur overflow-hidden border-t-4 border-blue-600">
            
            <div class="flex flex-col items-center mb-10">
                <div class="relative mb-6">
                    <div class="absolute inset-0 bg-blue-500 rounded-full blur-2xl opacity-20 animate-pulse"></div>
                    <div class="relative w-16 h-16 bg-blue-600/10 border border-blue-500/30 rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-black text-white tracking-[0.2em] uppercase text-center">Security Core</h2>
                <div class="flex items-center gap-2 mt-2 justify-center">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                    <p class="text-emerald-500 text-[10px] font-black uppercase tracking-widest">System Online</p>
                </div>
            </div>

            @if(session('error'))
                <div class="mb-8 p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-center gap-3">
                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider">{{ session('error') }}</p>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.25em] ml-1">Terminal ID</label>
                    <input type="email" name="email" placeholder="admin@root.sys" required 
                        class="input-dark w-full rounded-xl px-6 py-4 text-xs font-bold outline-none">
                </div>
                
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.25em] ml-1">Access Key</label>
                    <input type="password" name="password" placeholder="••••••••" required 
                        class="input-dark w-full rounded-xl px-6 py-4 text-xs font-bold outline-none">
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-execute w-full py-5 rounded-xl text-[11px] font-black uppercase tracking-[0.3em] active:scale-95 shadow-lg">
                        Initialize Auth
                    </button>
                </div>
                
                <div class="pt-8 border-t border-slate-800/50 mt-4">
                    <a href="{{ route('login') }}" class="group flex items-center justify-center gap-3 text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-[0.2em] transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Personnel Portal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="w-full flex justify-between px-12 pb-8 pointer-events-none opacity-20 mt-auto">
        <div class="text-[10px] font-mono text-blue-500 uppercase tracking-[0.5em]">Auth_Protocol: V.2.0.4</div>
        <div class="text-[10px] font-mono text-blue-500 uppercase tracking-[0.5em]">Sector_43: Active</div>
    </div>

</body>
</html>