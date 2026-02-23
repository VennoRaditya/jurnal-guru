<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Animasi Sidebar */
        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="bg-slate-50 overflow-x-hidden">
    
    <div id="adminOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 hidden opacity-0 transition-opacity duration-300"></div>

    <div class="flex min-h-screen relative">
        <aside id="adminSidebar" class="sidebar-transition fixed inset-y-0 left-0 z-40 w-72 bg-slate-900 text-white p-8 flex flex-col -translate-x-full lg:translate-x-0 lg:static">
            <div class="mb-12 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black tracking-tighter text-blue-400">ADMIN<span class="text-white">SYS</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Management System</p>
                </div>
                <button onclick="toggleAdminSidebar()" class="lg:hidden p-2 text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="space-y-3 flex-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 shadow-lg shadow-blue-600/20 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.guru.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all {{ request()->routeIs('admin.guru.*') ? 'bg-blue-600 shadow-lg shadow-blue-600/20 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
                    Data Guru
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all {{ request()->routeIs('admin.siswa.*') ? 'bg-blue-600 shadow-lg shadow-blue-600/20 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
                    Data Murid
                </a>
            </nav>

            <form action="{{ route('admin.logout') }}" method="POST" class="mt-auto">
                @csrf
                <button type="submit" class="w-full text-left px-6 py-4 rounded-2xl text-sm font-bold text-rose-400 hover:bg-rose-500/10 transition-all flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Sistem
                </button>
            </form>
        </aside>

        <main class="flex-1 w-full lg:max-w-[calc(100%-18rem)]">
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-20 px-6 md:px-12 py-6">
                <div class="flex justify-between items-center max-w-7xl mx-auto">
                    <div class="flex items-center gap-4">
                        <button onclick="toggleAdminSidebar()" class="lg:hidden p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        
                        <div class="hidden sm:block">
                            <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">@yield('header_title')</h2>
                            <p class="text-xs md:text-sm text-slate-400 font-medium">@yield('header_subtitle')</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-1.5 rounded-2xl border border-slate-100 flex items-center gap-3 pl-4 pr-2">
                        <div class="text-right hidden xs:block">
                            <p class="text-[10px] font-black text-slate-800 leading-none mb-1">{{ auth()->user()->name }}</p>
                            <span class="bg-blue-100 text-blue-600 text-[8px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider">Admin</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
                <div class="mt-4 sm:hidden">
                    <h2 class="text-lg font-black text-slate-800">@yield('header_title')</h2>
                </div>
            </header>

            <div class="p-6 md:p-12 max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');

        function toggleAdminSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 300);
            }
        }

        overlay.addEventListener('click', toggleAdminSidebar);
    </script>
</body>
</html>