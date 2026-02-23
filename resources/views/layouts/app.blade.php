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

        /* State Navigasi Aktif */
        .nav-active {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #1d4ed8;
            font-weight: 800;
            border-right: 4px solid #2563eb;
        }

        /* Animasi Transisi Sidebar */
        .sidebar-transition {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Efek Blur Header (Glassmorphism) */
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Mencegah scroll saat sidebar mobile terbuka */
        .no-scroll { overflow: hidden; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 overflow-x-hidden">

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
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('guru.presensi.select') }}" 
                   class="group flex items-center gap-3 px-5 py-4 rounded-2xl text-sm transition-all duration-300 {{ request()->is('guru/presensi*') ? 'nav-active shadow-sm shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>Presensi Siswa</span>
                </a>

                <a href="{{ route('guru.materi.index') }}" 
                   class="group flex items-center gap-3 px-5 py-4 rounded-2xl text-sm transition-all duration-300 {{ request()->is('guru/materi*') ? 'nav-active shadow-sm shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Materi Ajar</span>
                </a>
            </nav>
        </div>

        <div class="mt-auto p-8 border-t border-slate-50 bg-slate-50/50 text-center">
            <div class="flex flex-col items-center mb-6">
                <div class="w-12 h-12 rounded-2xl bg-linear-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-black text-sm shadow-lg mb-3">
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
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
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
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-[10px] font-black text-slate-700 uppercase tracking-tighter">
                        {{ date('l, d M Y') }}
                    </p>
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
        const body = document.body;

        function toggleSidebar() {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            
            if (isHidden) {
                // Membuka Sidebar
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => {
                    overlay.classList.add('opacity-100');
                }, 10);
                body.classList.add('no-scroll'); // Matikan scroll background
            } else {
                // Menutup Sidebar
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300);
                body.classList.remove('no-scroll'); // Aktifkan scroll kembali
            }
        }

        // Event: Klik di luar sidebar untuk menutup
        overlay.addEventListener('click', toggleSidebar);

        // Shortcut: Tekan 'Escape' untuk menutup sidebar
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                toggleSidebar();
            }
        });
    </script>

</body>
</html>