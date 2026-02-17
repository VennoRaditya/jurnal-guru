<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | JurnalGuru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.01em;
        }
        /* Style untuk menu yang aktif */
        .nav-active {
            background-color: #f1f5f9;
            color: #2563eb;
            font-weight: 600;
            border-left: 4px solid #2563eb;
        }
    </style>
</head>
<body class="bg-[#fcfdfe] text-slate-900">

    <aside class="w-64 bg-white h-screen border-r border-slate-200 hidden md:block fixed z-20">
        <div class="p-8">
            <h1 class="text-xl font-bold text-slate-900 tracking-tighter uppercase">Jurnal<span class="text-blue-600">Guru</span></h1>
        </div>
        
        <nav class="mt-4 px-3 space-y-1">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Main Menu</p>
            
            <a href="{{ route('guru.dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('guru.dashboard') ? 'nav-active shadow-sm shadow-blue-50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                Dashboard
            </a>
            
            <a href="{{ route('guru.presensi.select') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm transition-all duration-200 {{ request()->is('guru/presensi*') ? 'nav-active shadow-sm shadow-blue-50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                Presensi Siswa
            </a>

            <a href="{{ route('guru.materi.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm transition-all duration-200 {{ request()->is('guru/materi*') ? 'nav-active shadow-sm shadow-blue-50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                Materi Ajar
            </a>
        </nav>

        <div class="absolute bottom-0 w-full p-6 border-t border-slate-100 bg-slate-50/50">
            <div class="mb-4 px-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-loose">Account</p>
                <p class="text-xs font-semibold text-slate-700 truncate">{{ Auth::guard('guru')->user()->nama }}</p>
            </div>
            <form action="{{ route('guru.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-2 text-xs font-bold text-rose-600 hover:text-rose-700 transition uppercase tracking-wider">
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 md:ml-64 min-h-screen">
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-10 sticky top-0 z-10">
            <div>
                <h2 class="text-lg font-bold text-slate-800 tracking-tight">@yield('header_title')</h2>
            </div>
            
            <div class="flex items-center space-x-6">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-900 leading-none">{{ Auth::guard('guru')->user()->nip }}</p>
                    <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-1">{{ Auth::guard('guru')->user()->mapel }}</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center font-bold text-blue-600 text-sm shadow-sm uppercase">
                    {{ substr(Auth::guard('guru')->user()->nama, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="p-10 max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>

</body>
</html>