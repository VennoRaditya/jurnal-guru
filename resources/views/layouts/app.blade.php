<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') | JurnalGuru 43</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo43.png') }}">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    borderRadius: {
                        '4xl': '2.5rem',
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.02em;
            -webkit-tap-highlight-color: transparent;
        }

        /* --- GLOBAL LOADER CSS (FIXED) --- */
        #global-loader {
            display: flex;
            opacity: 1;
            visibility: visible;
            /* Transisi lebih halus */
            transition: opacity 0.4s ease-in-out, visibility 0.4s ease-in-out;
            background-color: #ffffff;
            /* Z-index sangat tinggi agar menutupi segalanya */
            z-index: 99999;
        }

        #global-loader.loader-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 0.5; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(0.9); opacity: 0.5; }
        }

        @keyframes loading-bar {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .loader-logo-pulse { animation: pulse-ring 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        .animate-loading-bar { animation: loading-bar 1s infinite linear; }

        /* --- UI COMPONENTS --- */
        .nav-active {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #1d4ed8;
            font-weight: 800;
        }

        .nav-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            height: 50%;
            width: 4px;
            background-color: #2563eb;
            border-radius: 0 4px 4px 0;
        }

        .sidebar-transition {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .no-scroll { overflow: hidden; }

        /* --- MODAL CSS --- */
        .modal-transition {
            transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 overflow-x-hidden">

    <div id="global-loader" class="fixed inset-0 flex flex-col items-center justify-center">
        <div class="flex flex-col items-center">
            <div class="relative mb-6">
                <div class="absolute -inset-3 border-2 border-blue-100 rounded-full animate-ping opacity-60"></div>
                <img src="{{ asset('images/logo43.png') }}" 
                     alt="Logo SMKN 43" 
                     class="w-20 h-20 object-contain relative z-10 loader-logo-pulse rounded-2xl p-1 bg-white shadow-xl"
                     onerror="this.src='https://ui-avatars.com/api/?name=SMKN43&background=2563eb&color=fff'">
            </div>
            
            <div class="w-56 h-1.5 bg-slate-100 rounded-full overflow-hidden relative shadow-inner">
                <div class="absolute top-0 left-0 h-full w-1/2 bg-blue-600 rounded-full animate-loading-bar"></div>
            </div>
            
            <p class="mt-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                Memuat <span class="text-blue-600">Halaman</span>
            </p>
        </div>
    </div>

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 hidden opacity-0 transition-opacity duration-300"></div>

    <aside id="sidebar" class="sidebar-transition w-72 bg-white h-screen fixed z-40 -translate-x-full md:translate-x-0 border-r border-slate-100 shadow-[20px_0_40px_rgba(0,0,0,0.02)] flex flex-col">
        <div class="p-6 grow overflow-y-auto">
            <div class="flex items-center gap-4 mb-10 pl-2">
                <div class="relative group">
                    <div class="absolute inset-0 bg-blue-600 rounded-2xl rotate-6 group-hover:rotate-0 transition-transform duration-300 opacity-10"></div>
                    <img src="{{ asset('images/logo_smkn_43.jpg') }}" 
                         alt="Logo" 
                         class="w-14 h-14 rounded-2xl object-cover shadow-lg relative z-10 border border-white"
                         onerror="this.src='https://ui-avatars.com/api/?name=SMKN43&background=2563eb&color=fff'">
                </div>
                <div>
                    <h1 class="text-lg font-black text-slate-900 italic leading-none">
                        Jurnal<span class="text-blue-600">Guru</span>
                    </h1>
                    <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-[0.3em] mt-1">
                        SMKN 43 Jakarta
                    </p>
                </div>
            </div>
            
            <nav class="space-y-1.5">
                <p class="px-4 text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] mb-4">Utama</p>
                
                @php
                    $navItems = [
                        ['route' => 'guru.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'label' => 'Dashboard'],
                        ['route' => 'guru.presensi.select', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'label' => 'Input Presensi'],
                        ['route' => 'guru.absensi.rekap', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Siswa Tidak Hadir'],
                        ['route' => 'guru.materi.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Riwayat Jurnal'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="group relative flex items-center gap-3 px-5 py-3.5 rounded-2xl text-sm transition-all duration-300 {{ request()->routeIs($item['route'].'*') ? 'nav-active shadow-sm shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="p-6 border-t border-slate-50 bg-slate-50/50">
            <div class="flex items-center gap-3 mb-6 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-sm shadow-lg uppercase shrink-0">
                    {{ substr(Auth::guard('guru')->user()->nama, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-black text-slate-800 uppercase leading-tight truncate">
                        {{ Auth::guard('guru')->user()->nama }}
                    </p>
                    <p class="text-[9px] font-bold text-slate-400 tracking-widest uppercase mt-0.5 truncate">
                        {{ Auth::guard('guru')->user()->mapel }}
                    </p>
                </div>
            </div>
            
            <button type="button" onclick="openLogoutModal()" class="w-full flex justify-center items-center gap-2 bg-slate-900 text-white py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-blue-600 transition-all duration-300 active:scale-95 shadow-lg shadow-slate-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </div>
    </aside>

    <main class="flex-1 md:ml-72 min-h-screen flex flex-col">
        <header class="h-20 md:h-24 glass-effect border-b border-slate-100 flex items-center justify-between px-4 md:px-8 sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 rounded-2xl bg-white text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-all border border-slate-100 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div>
                    <h2 class="text-sm md:text-xl font-black text-slate-900 tracking-tight truncate max-w-[200px] md:max-w-none">
                        @yield('header_title')
                    </h2>
                    <div class="hidden sm:flex items-center gap-2 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">SMKN 43 Jakarta • Portal Guru</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 md:gap-4">
                <div class="bg-white border border-slate-100 px-4 py-2 md:px-5 md:py-2.5 rounded-2xl flex items-center gap-3 shadow-sm">
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-[10px] md:text-xs font-black text-slate-700 uppercase tracking-tighter hidden md:block">
                        {{ date('l, d M Y') }}
                    </p>
                    <p class="text-[10px] font-black text-slate-700 uppercase tracking-tighter md:hidden">
                        {{ date('d/m/y') }}
                    </p>
                </div>
            </div>
        </header>

        <div class="p-4 md:p-8 grow">
            @yield('content')
        </div>

        <footer class="text-center p-6 text-[10px] text-slate-400 font-medium">
            &copy; {{ date('Y') }} JurnalGuru 43 - SMKN 43 Jakarta.
        </footer>
    </main>

    <div id="logoutModal" class="fixed inset-0 z-10000 items-center justify-center hidden opacity-0 modal-transition">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeLogoutModal()"></div>
        
        <div class="bg-white rounded-3xl p-8 m-4 shadow-2xl relative z-10 w-full max-w-sm transform scale-95 transition-transform duration-300">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
                
                <h3 class="text-xl font-black text-slate-900 mb-2">Konfirmasi Logout</h3>
                <p class="text-sm text-slate-500 mb-8">Apakah Anda yakin ingin keluar dari aplikasi? Pastikan semua pekerjaan sudah disimpan.</p>
                
                <div class="flex gap-3 w-full">
                    <button onclick="closeLogoutModal()" class="flex-1 bg-slate-100 text-slate-700 py-3.5 rounded-2xl text-xs font-black uppercase tracking-wider hover:bg-slate-200 transition-all active:scale-95">
                        Batal
                    </button>
                    <form action="{{ route('guru.logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 text-white py-3.5 rounded-2xl text-xs font-black uppercase tracking-wider hover:bg-red-700 transition-all active:scale-95 shadow-lg shadow-red-100">
                            Ya, Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const loader = document.getElementById('global-loader');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const body = document.body;
        const logoutModal = document.getElementById('logoutModal');

        // --- GLOBAL LOADER LOGIC ---
        const hideLoader = () => {
            loader.classList.add('loader-hidden');
            // Menghapus elemen setelah transisi selesai
            setTimeout(() => {
                loader.style.display = 'none';
            }, 400); // Harus sama dengan waktu transisi CSS
        };
        const showLoader = () => {
            loader.style.display = 'flex';
            // Perlu sedikit delay agar display flex terproses sebelum opacity berubah
            setTimeout(() => {
                loader.classList.remove('loader-hidden');
            }, 10);
        };

        // Hilang saat halaman selesai dimuat
        window.addEventListener('load', hideLoader);
        // Backup: Hilang otomatis setelah 5 detik
        setTimeout(hideLoader, 5000);

        // Interaksi Link: Tampilkan loader saat pindah halaman
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const target = this.getAttribute('target');
                
                if (href && !href.startsWith('#') && href !== 'javascript:void(0)' && target !== '_blank' && !this.hasAttribute('download')) {
                    if (this.href !== window.location.href) {
                        showLoader();
                    }
                }
            });
        });

        // Interaksi Form: Tampilkan loader saat submit
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const isModalForm = this.closest('.modal') || this.closest('[role="dialog"]') || this.closest('#logoutModal');
                const hasNoLoader = this.classList.contains('no-loader');
                const isFilterForm = this.getAttribute('method')?.toUpperCase() === 'GET';
                
                if (!isModalForm && !hasNoLoader && !isFilterForm) {
                    showLoader();
                }
            });
        });

        // Tombol Back Browser
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) hideLoader();
        });

        // --- SIDEBAR LOGIC ---
        function toggleSidebar() {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
                body.classList.add('no-scroll');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
                body.classList.remove('no-scroll');
            }
        }

        overlay.addEventListener('click', toggleSidebar);

        // --- MODAL LOGIC ---
        function openLogoutModal() {
            logoutModal.classList.remove('hidden');
            setTimeout(() => {
                logoutModal.classList.add('opacity-100');
                logoutModal.querySelector('div:last-child').classList.remove('scale-95');
            }, 10);
            body.classList.add('no-scroll');
        }

        function closeLogoutModal() {
            logoutModal.classList.remove('opacity-100');
            logoutModal.querySelector('div:last-child').classList.add('scale-95');
            setTimeout(() => {
                logoutModal.classList.add('hidden');
            }, 300);
            body.classList.remove('no-scroll');
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !logoutModal.classList.contains('hidden')) {
                closeLogoutModal();
            }
        });
    </script>
</body>
</html>