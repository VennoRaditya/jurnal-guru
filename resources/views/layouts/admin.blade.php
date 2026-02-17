<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        <aside class="w-72 bg-slate-900 text-white p-8 flex flex-col">
            <div class="mb-12">
                <h1 class="text-2xl font-black tracking-tighter text-blue-400">ADMIN<span class="text-white">SYS</span></h1>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Management System v1.0</p>
            </div>

            <nav class="space-y-3 flex-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-slate-800 text-slate-400' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.guru.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all {{ request()->routeIs('admin.guru.*') ? 'bg-blue-600' : 'hover:bg-slate-800 text-slate-400' }}">
                    Data Guru
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all {{ request()->routeIs('admin.siswa.*') ? 'bg-blue-600' : 'hover:bg-slate-800 text-slate-400' }}">
                    Data Murid
                </a>
            </nav>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-6 py-4 rounded-2xl text-sm font-bold text-rose-400 hover:bg-rose-500/10 transition-all">
                    Keluar Sistem
                </button>
            </form>
        </aside>

        <main class="flex-1 p-12">
            <header class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">@yield('header_title')</h2>
                    <p class="text-slate-400 font-medium">@yield('header_subtitle')</p>
                </div>
                <div class="bg-white p-2 rounded-2xl border border-slate-100 flex items-center gap-4 px-6">
                    <div class="text-right">
                        <p class="text-xs font-black text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Administrator</p>
                    </div>
                </div>
            </header>

            @yield('content')
        </main>
    </div>
</body>
</html>