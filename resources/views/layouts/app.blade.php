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
        /* --- CORE BASE --- */
        html { scrollbar-gutter: stable; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.02em;
            -webkit-tap-highlight-color: transparent;
            overflow-x: hidden;
        }

        /* --- GLOBAL LOADER --- */
        #global-loader {
            display: flex;
            position: fixed;
            inset: 0;
            background-color: #ffffff;
            z-index: 99999;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }

        #global-loader.loader-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.02); opacity: 0.8; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }

        @keyframes loading-bar {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .loader-logo-pulse { animation: pulse-ring 2s ease-in-out infinite; }
        .animate-loading-bar { animation: loading-bar 1.5s infinite linear; }

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

        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .no-scroll { overflow: hidden !important; }

        /* Modal Base */
        .modal-container {
            display: none;
            transition: all 0.3s ease-in-out;
        }
        .modal-container.active {
            display: flex;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900">

    <div id="global-loader" class="flex flex-col items-center justify-center">
        <div class="flex flex-col items-center w-full max-w-60 px-6">
            <div class="relative mb-8">
                <div class="absolute -inset-4 border-2 border-blue-100 rounded-full animate-ping opacity-40"></div>
                <img src="{{ asset('images/logo43.png') }}" 
                     alt="Logo SMKN 43" 
                     class="w-20 h-20 object-contain relative z-10 loader-logo-pulse rounded-2xl p-1 bg-white shadow-xl"
                     onerror="this.src='https://ui-avatars.com/api/?name=43&background=2563eb&color=fff'">
            </div>
            <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden relative">
                <div class="absolute top-0 left-0 h-full w-1/2 bg-blue-600 rounded-full animate-loading-bar"></div>
            </div>
            <p class="mt-6 text-[9px] font-black uppercase tracking-[0.4em] text-slate-400 text-center">
                Memproses <span class="text-blue-600">Data</span>
            </p>
        </div>
    </div>

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 hidden opacity-0 transition-opacity duration-300"></div>

    <aside id="sidebar" class="sidebar-transition w-72 bg-white h-screen fixed z-40 -translate-x-full md:translate-x-0 border-r border-slate-100 shadow-[20px_0_40px_rgba(0,0,0,0.02)] flex flex-col">
        <div class="p-6 grow overflow-y-auto">
            <div class="flex items-center gap-4 mb-10 pl-2">
                <div class="relative group">
                    <div class="absolute inset-0 bg-blue-600 rounded-2xl rotate-6 group-hover:rotate-0 transition-transform duration-300 opacity-10"></div>
                    <img src="{{ asset('images/logo43.png') }}" alt="Logo" class="w-14 h-14 rounded-2xl object-cover shadow-lg relative z-10 border border-white" onerror="this.src='https://ui-avatars.com/api/?name=43&background=2563eb&color=fff'">
                </div>
                <div>
                    <h1 class="text-lg font-black text-slate-900 italic leading-none">Jurnal<span class="text-blue-600">Guru</span></h1>
                    <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-[0.3em] mt-1">SMKN 43 Jakarta</p>
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
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="p-6 border-t border-slate-50 bg-slate-50/50">
            <div class="flex items-center gap-3 mb-6 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-md">
                    {{ substr(Auth::guard('guru')->user()->nama, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-black text-slate-800 uppercase leading-tight truncate">{{ Auth::guard('guru')->user()->nama }}</p>
                    <p class="text-[9px] font-bold text-slate-400 tracking-widest uppercase mt-0.5 truncate">{{ Auth::guard('guru')->user()->mapel }}</p>
                </div>
            </div>
            <button type="button" onclick="openLogoutModal()" class="w-full flex justify-center items-center gap-2 bg-slate-900 text-white py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-rose-600 transition-all active:scale-95 shadow-lg shadow-slate-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </div>
    </aside>

    <main class="flex-1 md:ml-72 min-h-screen flex flex-col">
        <header class="h-20 md:h-24 glass-effect border-b border-slate-100 flex items-center justify-between px-4 md:px-8 sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 rounded-2xl bg-white text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-all border border-slate-100 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h2 class="text-sm md:text-xl font-black text-slate-900 tracking-tight truncate max-w-[150px] md:max-w-none">@yield('header_title')</h2>
                    <p class="hidden sm:block text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">SMKN 43 Jakarta • Portal Guru</p>
                </div>
            </div>
            <div class="bg-white border border-slate-100 px-3 py-2 md:px-5 md:py-2.5 rounded-2xl flex items-center gap-3 shadow-sm">
                <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-[10px] md:text-xs font-black text-slate-700 uppercase tracking-tighter">{{ date('d M Y') }}</p>
            </div>
        </header>

        <div class="p-4 md:p-8 grow">
            @yield('content')
        </div>

        <footer class="text-center p-6 text-[10px] text-slate-400 font-medium tracking-widest uppercase">
            &copy; {{ date('Y') }} RPL SMKN 43 Jakarta
        </footer>
    </main>

    <div id="logoutModal" class="modal-container fixed inset-0 z-[100] items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" onclick="closeLogoutModal()"></div>
        <div class="bg-white rounded-[2.5rem] p-10 m-4 shadow-[0_20px_50px_rgba(0,0,0,0.15)] relative z-10 w-full max-w-[380px] transform scale-90 transition-all duration-300 border border-slate-50" id="logoutModalContent">
            <div class="flex flex-col items-center text-center">
                <div class="relative mb-8">
                    <div class="absolute inset-0 bg-rose-500 rounded-3xl rotate-12 opacity-10 animate-pulse"></div>
                    <div class="w-20 h-20 rounded-3xl bg-rose-50 flex items-center justify-center text-rose-500 relative z-10">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Sudah Selesai?</h3>
                <p class="text-[13px] font-medium text-slate-500 mb-10 leading-relaxed px-4">Pastikan semua catatan jurnal dan presensi siswa hari ini telah tersimpan.</p>
                <div class="grid grid-cols-1 gap-3 w-full">
                    <form action="{{ route('guru.logout') }}" method="POST" class="w-full order-1">
                        @csrf
                        <button type="submit" class="w-full bg-rose-500 text-white py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-rose-600 active:scale-95 transition-all shadow-xl shadow-rose-200">Ya, Akhiri Sesi</button>
                    </form>
                    <button onclick="closeLogoutModal()" class="w-full bg-slate-100 text-slate-600 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 active:scale-95 transition-all order-2">Nanti Saja</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ui = {
            loader: document.getElementById('global-loader'),
            sidebar: document.getElementById('sidebar'),
            overlay: document.getElementById('sidebarOverlay'),
            logoutModal: document.getElementById('logoutModal'),
            logoutModalContent: document.getElementById('logoutModalContent'),
            body: document.body
        };

        // --- Loader Logic ---
        const hideLoader = () => {
            if (ui.loader) {
                ui.loader.classList.add('loader-hidden');
                ui.body.classList.remove('no-scroll');
            }
        };
        const showLoader = () => {
            if (ui.loader) {
                ui.loader.classList.remove('loader-hidden');
                ui.body.classList.add('no-scroll');
            }
        };

        window.addEventListener('load', () => setTimeout(hideLoader, 300));
        setTimeout(hideLoader, 8000); // Safety timeout

        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (!link) return;
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript') || link.getAttribute('target') === '_blank') return;
            if (link.hostname === window.location.hostname) showLoader();
        });

        // --- Sidebar Logic ---
        function toggleSidebar() {
            const isHidden = ui.sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                ui.sidebar.classList.remove('-translate-x-full');
                ui.overlay.classList.remove('hidden');
                setTimeout(() => ui.overlay.classList.add('opacity-100'), 10);
                ui.body.classList.add('no-scroll');
            } else {
                ui.sidebar.classList.add('-translate-x-full');
                ui.overlay.classList.remove('opacity-100');
                setTimeout(() => ui.overlay.classList.add('hidden'), 300);
                ui.body.classList.remove('no-scroll');
            }
        }
        ui.overlay.addEventListener('click', toggleSidebar);

        // --- Modal Logout Logic ---
        function openLogoutModal() {
            ui.logoutModal.classList.add('active', 'flex');
            ui.logoutModal.classList.remove('pointer-events-none');
            ui.body.classList.add('no-scroll');
            setTimeout(() => {
                ui.logoutModal.classList.add('opacity-100');
                ui.logoutModalContent.classList.remove('scale-90');
                ui.logoutModalContent.classList.add('scale-100');
            }, 50);
        }

        function closeLogoutModal() {
            ui.logoutModal.classList.remove('opacity-100');
            ui.logoutModalContent.classList.remove('scale-100');
            ui.logoutModalContent.classList.add('scale-90');
            setTimeout(() => {
                ui.logoutModal.classList.remove('active', 'flex');
                ui.logoutModal.classList.add('pointer-events-none');
                ui.body.classList.remove('no-scroll');
            }, 300);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && ui.logoutModal.classList.contains('active')) closeLogoutModal();
        });
    </script>
</body>
</html>