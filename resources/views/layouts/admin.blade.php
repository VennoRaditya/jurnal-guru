<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Admin Panel 43</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo43.png') }}">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            letter-spacing: -0.025em;
        }
        
        .sidebar-transition { 
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        .nav-link-active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0) 100%);
            color: #60a5fa;
            border-left: 4px solid #3b82f6;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        .no-scroll { overflow: hidden; }

        @keyframes pro-pulse {
            0%, 100% { opacity: 1; text-shadow: 0 0 10px rgba(59, 130, 246, 0.5); }
            50% { opacity: 0.7; text-shadow: 0 0 2px rgba(59, 130, 246, 0.2); }
        }
        .animate-pro {
            animation: pro-pulse 2s infinite ease-in-out;
        }

        .modal-transition {
            transition: opacity 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-[#f1f5f9] text-slate-900 overflow-x-hidden">
    
    <div id="adminOverlay" class="fixed inset-0 bg-slate-950/60 backdrop-blur-md z-30 hidden opacity-0 transition-opacity duration-300"></div>

    <div class="flex min-h-screen relative">
        
        {{-- SIDEBAR --}}
        <aside id="adminSidebar" class="sidebar-transition fixed inset-y-0 left-0 z-40 w-80 bg-[#0f172a] text-white flex flex-col -translate-x-full lg:translate-x-0 lg:sticky shadow-[10px_0_40px_rgba(0,0,0,0.1)] h-screen">
            
            <div class="p-10">
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-blue-500 rounded-2xl rotate-6 group-hover:rotate-0 transition-transform duration-300 opacity-20"></div>
                        <img src="{{ asset('images/logo43.png') }}" 
                             alt="Logo SMKN 43" 
                             class="w-12 h-12 rounded-2xl object-cover shadow-xl shadow-blue-900/40 relative z-10 border border-slate-700">
                    </div>
                    <div>
                        <h1 class="text-xl font-black tracking-tighter text-white italic">
                            ADMIN<span class="text-blue-500 animate-pro">PRO</span>
                        </h1>
                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.3em] leading-tight mt-1">
                            SMKN 43 JAKARTA
                        </p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto">
                <p class="px-6 text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] mb-4">Core Management</p>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Dashboard Analytics
                </a>

                <a href="{{ route('admin.guru.index') }}" 
                   class="group flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.guru.*') ? 'nav-link-active' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Tenaga Pengajar
                </a>

                <a href="{{ route('admin.kelas.index') }}" 
                   class="group flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.kelas.*') ? 'nav-link-active' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Ruang Kelas
                </a>
            </nav>

            <div class="p-6 mt-auto">
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-[2.5rem] p-6 text-center">
                    <div class="flex flex-col items-center mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center font-black text-sm shadow-lg mb-3 border border-white/10 text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden w-full">
                            <p class="text-[10px] font-black uppercase truncate tracking-wider text-white">{{ auth()->user()->name }}</p>
                            <p class="text-[8px] font-bold text-blue-400 uppercase tracking-[0.2em] mt-1 italic">System Overlord</p>
                        </div>
                    </div>
                    <button type="button" onclick="openLogoutModal()" class="w-full bg-rose-500/10 text-rose-400 py-3.5 rounded-2xl text-[9px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all duration-300 active:scale-95">
                        Terminate Session
                    </button>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 w-full flex flex-col min-w-0">
            <header class="glass-header border-b border-slate-200/60 sticky top-0 z-20 px-8 py-5">
                <div class="flex justify-between items-center max-w-7xl mx-auto w-full">
                    <div class="flex items-center gap-6">
                        <button onclick="toggleAdminSidebar()" class="lg:hidden p-3 bg-white text-slate-600 rounded-2xl hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-slate-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        
                        <div>
                            <h2 class="text-xl font-black text-slate-800 tracking-tighter uppercase leading-none">@yield('header_title')</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1.5 flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-blue-500"></span>
                                @yield('header_subtitle')
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="hidden md:flex flex-col text-right border-r pr-6 border-slate-200">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Server Status</span>
                            <span class="text-[9px] font-black text-emerald-500 uppercase flex items-center justify-end gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Operational
                            </span>
                        </div>
                        <div class="w-11 h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-blue-600 font-black shadow-sm uppercase text-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-8 md:p-12 max-w-7xl mx-auto w-full flex-1">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- MODAL LOGOUT (FIXED & IMPROVED) --}}
    <div id="logoutModal" class="fixed inset-0 z-100]hidden items-center justify-center modal-transition opacity-0 p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-md" onclick="closeLogoutModal()"></div>
        
        {{-- Modal Content --}}
        <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl relative z-10 w-full max-w-sm transform scale-95 transition-all duration-300">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-4x1 bg-rose-50 flex items-center justify-center mb-6 rotate-3">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                
                <h3 class="text-2xl font-black text-slate-900 mb-2 italic tracking-tighter uppercase">Terminate?</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest leading-relaxed mb-10">
                    Sesi Anda akan segera diakhiri dari sistem keamanan SMKN 43.
                </p>
                
                <div class="flex flex-col gap-3 w-full">
                    <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-rose-600 transition-all duration-300 active:scale-95 shadow-xl shadow-slate-200">
                            Confirm Termination
                        </button>
                    </form>
                    <button onclick="closeLogoutModal()" class="w-full bg-slate-100 text-slate-400 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 hover:text-slate-600 transition-all active:scale-95">
                        Stay Logged In
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');
        const body = document.body;
        const logoutModal = document.getElementById('logoutModal');
        const modalContent = logoutModal.querySelector('div:last-child');

        function toggleAdminSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
                body.classList.add('no-scroll');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                    body.classList.remove('no-scroll');
                }, 300);
            }
        }
        
        overlay.addEventListener('click', toggleAdminSidebar);

        // --- IMPROVED MODAL LOGIC ---
        function openLogoutModal() {
            logoutModal.classList.remove('hidden');
            logoutModal.classList.add('flex'); // Paksa flex agar center
            
            setTimeout(() => {
                logoutModal.classList.add('opacity-100');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
            body.classList.add('no-scroll');
        }

        function closeLogoutModal() {
            logoutModal.classList.remove('opacity-100');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                logoutModal.classList.remove('flex');
                logoutModal.classList.add('hidden');
                body.classList.remove('no-scroll');
            }, 300);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!sidebar.classList.contains('-translate-x-full')) toggleAdminSidebar();
                if (!logoutModal.classList.contains('hidden')) closeLogoutModal();
            }
        });
    </script>
</body>
</html>