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
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(30, 64, 175, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(76, 29, 149, 0.15) 0px, transparent 50%);
        }
        .admin-card {
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .input-field:focus {
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="admin-card p-10 md:p-12 rounded-[3rem] w-full max-w-md relative overflow-hidden">
        {{-- Decorative Bar --}}
        <div class="absolute top-0 left-0 w-full h-2 bg-linear-to-r from-blue-600 via-purple-600 to-indigo-600"></div>

        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-900 rounded-3xl mb-6 shadow-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter uppercase mb-2">Central Admin</h2>
            <p class="text-slate-400 text-[11px] font-bold uppercase tracking-[0.2em]">Authorized Access Only</p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <p class="text-[11px] font-black text-rose-600 uppercase">{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Administrator ID</label>
                <input type="email" name="email" placeholder="admin@system.com" required 
                    class="input-field w-full bg-slate-50 border-2 border-transparent rounded-2xl px-6 py-4 text-xs font-bold text-slate-800 outline-none focus:border-blue-500/20 focus:bg-white transition-all duration-300">
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between items-center ml-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Security Code</label>
                </div>
                <input type="password" name="password" placeholder="••••••••" required 
                    class="input-field w-full bg-slate-50 border-2 border-transparent rounded-2xl px-6 py-4 text-xs font-bold text-slate-800 outline-none focus:border-blue-500/20 focus:bg-white transition-all duration-300">
            </div>

            <div class="py-2">
                <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 active:scale-95">
                    Execute Authorization
                </button>
            </div>
            
            <div class="pt-6 border-t border-slate-50">
                <a href="{{ route('login') }}" class="group flex items-center justify-center gap-2 text-[10px] font-black text-slate-400 hover:text-blue-600 uppercase tracking-[0.15em] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Portal Guru
                </a>
            </div>
        </form>
    </div>

    {{-- Footer Info --}}
    <div class="fixed bottom-8 text-center">
        <p class="text-slate-500 text-[9px] font-bold uppercase tracking-[0.3em] opacity-50">System Infrastructure v2.0.4</p>
    </div>

</body>
</html>