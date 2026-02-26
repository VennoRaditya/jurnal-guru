<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | JurnalGuru 43</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.02em;
        }

        /* --- GLOBAL LOADER CSS --- */
        #global-loader {
            transition: opacity 0.5s ease-in-out, visibility 0.5s;
        }
        .loader-logo-pulse {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }
        @keyframes loading-bar {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(200%); }
        }
        .animate-loading-bar {
            animation: loading-bar 1.5s infinite linear;
        }
        /* ------------------------- */

        .nav-active {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #1d4ed8;
            font-weight: 800;
            border-right: 4px solid #2563eb;
        }

        .sidebar-transition {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .no-scroll { overflow: hidden; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 overflow-x-hidden">

    <div id="global-loader" class="fixed inset-0 z-9999 flex items-center justify-center bg-white shadow-2xl">
        <div class="flex flex-col items-center">
            <div class="relative mb-6">
                <div class="absolute -inset-4 border-2 border-blue-50 rounded-full animate-spin-slow opacity-50"></div>
                <img src="{{ asset('images/logo_smkn_43.jpg') }}" 
                     alt="Logo SMKN 43" 
                     class="w-24 h-24 object-contain relative z-10 loader-logo-pulse rounded-2xl">
            </div>
            
            <div class="w-48 h-1 bg-slate-100 rounded-full overflow-hidden relative">
                <div class="absolute top-0 left-0 h-full w-24 bg-blue-600 rounded-full animate-loading-bar"></div>
            </div>
            
            <p class="mt-4 text-[10px] font-black uppercase tracking-[0.4em] text-slate-400">
                Memproses <span class="text-blue-600 animate-pulse">Data...</span>
            </p>
        </div>
    </div>

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 hidden opacity-0 transition-opacity duration-300"></div>

    <aside id="sidebar" class="sidebar-transition w-72 bg-white h-screen fixed z-40 -translate-x-full md:translate-x-0 border-r border-slate-100 shadow-[20px_0_40px_rgba(0,0,0,0.02)] flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-4 mb-10">
                <div class="relative group">
                    <div class="absolute inset-0 bg-blue-600 rounded-2xl rotate-6 group-hover:rotate-0 transition-transform duration-300 opacity-10"></div>
                    <img src="{{ asset('images/logo_smkn_43.jpg') }}" 
                         alt="Logo SMKN 43" 
                         class="w-14 h-14 rounded-2xl object-cover shadow-lg shadow-blue-100 relative z-10 border border-white">
                </div>
                <div>
                    <h1 class="text-lg font-black text-slate-900 leading-none tracking-tighter italic">
                        Jurnal<span class="text-blue-600">Guru</span>
                    </h1>
                    <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-[0.3em] mt-1.5 leading-tight">
                        SMKN 43<br>JAKARTA
                    </p>
                </div>
            </div>
            
            <nav class="space-y-1.5">
                <p class="px-4 text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] mb-4">Utama</p>
                
                <a href="{{ route('guru.dashboard') }}" 
                   class="group flex items-center gap-3 px-5 py-4 rounded-2xl text-sm transition-all duration-300 {{ request()->routeIs('guru.dashboard') ? 'nav-active shadow-sm shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('guru.presensi.select') }}" 
                   class="group flex items-center gap-3 px-5 py-4 rounded-2xl text-sm transition-all duration-300 {{ request()->routeIs('guru.presensi.*') ? 'nav-active shadow-sm shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Input Presensi</span>
                </a>

                <a href="{{ route('guru.absensi.rekap') }}" 
                   class="group flex items-center gap-3 px-5 py-4 rounded-2xl text-sm transition-all duration-300 {{ request()->routeIs('guru.absensi.rekap') ? 'nav-active shadow-sm shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Siswa Tidak Hadir</span>
                </a>

                <a href="{{ route('guru.materi.index') }}" 
                   class="group flex items-center gap-3 px-5 py-4 rounded-2xl text-sm transition-all duration-300 {{ request()->routeIs('guru.materi.*') ? 'nav-active shadow-sm shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Riwayat Jurnal</span>
                </a>
            </nav>
        </div>

        <div class="mt-auto p-8 border-t border-slate-50 bg-slate-50/50 text-center">
            <div class="flex flex-col items-center mb-6">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white font-black text-sm shadow-lg mb-3">
                    {{ substr(Auth::guard('guru')->user()->nama, 0, 1) }}
                </div>
                <div class="w-full px-2">
                    <p class="text-[11px] font-black text-slate-800 uppercase leading-tight truncate">
                        {{ Auth::guard('guru')->user()->nama }}
                    </p>
                    <p class="text-[9px] font-bold text-slate-400 tracking-widest uppercase mt-1 truncate">
                        {{ Auth::guard('guru')->user()->mapel }}
                    </p>
                </div>
            </div>
            
            <form action="{{ route('guru.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-slate-900 text-white py-3.5 rounded-2xl text-[9px] font-black uppercase tracking-[0.2em] hover:bg-blue-600 transition-all duration-300 active:scale-95 shadow-lg shadow-slate-100">
                    Logout Securely
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 md:ml-72 min-h-screen flex flex-col">
        <header class="h-24 glass-effect border-b border-slate-100 flex items-center justify-between px-8 md:px-12 sticky top-0 z-10">
            <div class="flex items-center gap-6">
                <button onclick="toggleSidebar()" class="md:hidden p-3 rounded-xl bg-slate-50 text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div class="hidden sm:block">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">@yield('header_title')</h2>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">SMKN 43 Jakarta • Portal Akademik</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="hidden lg:flex flex-col text-right border-r pr-6 border-slate-100">
                    <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-0.5">ID Pegawai (NIP)</p>
                    <p class="text-xs font-black text-slate-700 tracking-widest italic">
                        {{ Auth::guard('guru')->user()->nip }}
                    </p>
                </div>

                <div class="bg-white border border-slate-100 px-5 py-2.5 rounded-2xl hidden md:flex items-center gap-3 shadow-sm">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-[10px] font-black text-slate-700 uppercase tracking-tighter">{{ date('l, d M Y') }}</p>
                </div>
            </div>
        </header>

        <div class="p-8 md:p-12 max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const loader = document.getElementById('global-loader');
        const body = document.body;

        // --- GLOBAL LOADER LOGIC ---
        // 1. Hilangkan loader saat halaman selesai dimuat
        window.addEventListener('load', function() {
            setTimeout(() => {
                loader.classList.add('opacity-0');
                setTimeout(() => {
                    loader.style.visibility = 'hidden';
                    loader.style.display = 'none';
                }, 500);
            }, 400); // Delay halus
        });

        // 2. Munculkan loader saat klik link (berpindah halaman)
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                // Filter: Jangan trigger loader jika link kosong, anchor (#), atau download
                if (href && !href.startsWith('#') && !this.target && !this.hasAttribute('download') && href !== 'javascript:void(0)') {
                    loader.style.display = 'flex';
                    loader.style.visibility = 'visible';
                    loader.classList.remove('opacity-0');
                }
            });
        });

        // 3. Munculkan loader saat submit form
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                loader.style.display = 'flex';
                loader.style.visibility = 'visible';
                loader.classList.remove('opacity-0');
            });
        });

        // --- SIDEBAR LOGIC ---
        function toggleSidebar() {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => { overlay.classList.add('opacity-100'); }, 10);
                body.classList.add('no-scroll');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => { overlay.classList.add('hidden'); }, 300);
                body.classList.remove('no-scroll');
            }
        }

        overlay.addEventListener('click', toggleSidebar);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                toggleSidebar();
            }
        });
    </script>
</body>
</html>