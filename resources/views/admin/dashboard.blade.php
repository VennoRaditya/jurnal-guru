@extends('layouts.admin')

@section('title', 'Dashboard Pro')
@section('header_title', 'Analytics Overview')
@section('header_subtitle', 'Selamat datang kembali, Admin.')

@section('content')
<div class="space-y-6 md:space-y-10 px-2 md:px-0">
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8">
        
        {{-- Card: Tenaga Pengajar --}}
        {{-- TAMBAHAN: animate-fade-in-up & delay 100ms --}}
        <div class="group relative bg-white p-6 md:p-8 rounded-4x1 md:rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-blue-500/10 hover:-translate-y-1 md:hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 100ms">
            <div class="relative z-10">
                <div class="flex items-center md:items-start justify-between mb-4 md:mb-8">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-linear-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="bg-blue-50 text-blue-600 text-[9px] md:text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Personnel</span>
                </div>
                <p class="text-[10px] md:text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Tenaga Pengajar</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl md:text-5xl font-black text-slate-800 tracking-tighter">{{ $total_guru }}</h3>
                    <span class="text-slate-400 font-bold text-xs md:text-sm uppercase tracking-widest">Orang</span>
                </div>
            </div>
            <div class="absolute -right-8 -bottom-8 w-24 h-24 md:w-32 md:h-32 bg-slate-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>

        {{-- Card: Total Siswa --}}
        {{-- TAMBAHAN: animate-fade-in-up & delay 200ms --}}
        <div class="group relative bg-white p-6 md:p-8 rounded-4x1 md:rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-indigo-500/10 hover:-translate-y-1 md:hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 200ms">
            <div class="relative z-10">
                <div class="flex items-center md:items-start justify-between mb-4 md:mb-8">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-linear-to-br from-indigo-500 to-purple-700 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="bg-indigo-50 text-indigo-600 text-[9px] md:text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Students</span>
                </div>
                <p class="text-[10px] md:text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Siswa</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl md:text-5xl font-black text-slate-800 tracking-tighter">{{ $total_siswa }}</h3>
                    <span class="text-slate-400 font-bold text-xs md:text-sm uppercase tracking-widest">Murid</span>
                </div>
            </div>
            <div class="absolute -right-8 -bottom-8 w-24 h-24 md:w-32 md:h-32 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>

        {{-- Card: Laporan Presensi --}}
        {{-- TAMBAHAN: animate-fade-in-up & delay 300ms --}}
        <div class="relative bg-slate-900 p-6 md:p-8 rounded-4x1 md:rounded-[2.5rem] shadow-xl overflow-hidden group animate-fade-in-up" style="animation-delay: 300ms">
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span class="text-emerald-500 text-[9px] font-black uppercase tracking-widest">Live</span>
                    </div>
                    <h4 class="text-white font-black text-xl md:text-2xl tracking-tight leading-tight">Presensi Hari Ini</h4>
                </div>
                <a href="#" class="mt-6 inline-flex items-center justify-center gap-3 bg-white text-slate-900 px-5 py-3 md:py-4 rounded-xl md:rounded-2xl font-black text-[9px] md:text-[10px] uppercase tracking-widest transition-all hover:scale-105 active:scale-95">
                    View Info
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Recent Teachers Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-10">
        {{-- TAMBAHAN: animate-fade-in-up & delay 400ms --}}
        <div class="lg:col-span-2 bg-white rounded-4x1 md:rounded-[2.5rem] border border-slate-100 p-6 md:p-10 shadow-sm animate-fade-in-up" style="animation-delay: 400ms">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h3 class="font-black text-slate-800 uppercase tracking-tighter text-xl md:text-2xl">Guru Terbaru</h3>
                    <p class="text-slate-400 text-[9px] font-bold mt-1 uppercase tracking-widest">Data 24 Jam Terakhir</p>
                </div>
                <a href="{{ route('admin.guru.index') }}" class="w-full md:w-auto text-center bg-slate-50 text-[9px] font-black text-slate-500 px-5 py-3 rounded-xl uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all">
                    View Archive
                </a>
            </div>

            <div class="space-y-3">
                @foreach(\App\Models\Guru::latest()->take(3)->get() as $index => $guru)
                {{-- TAMBAHAN: staggered delay untuk list item --}}
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-50 hover:border-blue-100 transition-all animate-fade-in-right" style="animation-delay: {{ 500 + ($index * 100) }}ms">
                    <div class="flex items-center gap-3 md:gap-5">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-500 text-sm">
                            {{ substr($guru->nama, 0, 1) }}
                        </div>
                        <div class="max-w-[120px] md:max-w-none">
                            <p class="text-sm font-black text-slate-800 truncate">{{ $guru->nama }}</p>
                            <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest">{{ $guru->mapel }}</p>
                        </div>
                    </div>
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-black text-slate-800 uppercase">{{ $guru->created_at->format('H:i') }}</p>
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $guru->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Help Card --}}
        {{-- TAMBAHAN: animate-fade-in-up & delay 600ms --}}
        <div class="bg-linear-to-br from-blue-600 to-indigo-700 rounded-4x1 md:rounded-[2.5rem] p-8 md:p-10 text-white shadow-lg flex flex-col justify-between animate-fade-in-up" style="animation-delay: 600ms">
            <div>
                <h3 class="text-2xl font-black leading-tight tracking-tight mb-3">Butuh Bantuan?</h3>
                <p class="text-blue-100/80 text-xs font-medium leading-relaxed">Hubungi Tim IT untuk kendala database sistem.</p>
            </div>
            <button class="mt-8 w-full bg-white text-blue-700 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all active:scale-95">
                Support Ticket
            </button>
        </div>
    </div>
</div>

{{-- CSS UNTUK ANIMASI --}}
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    
    .animate-fade-in-right {
        opacity: 0;
        animation: fadeInRight 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    /* Penyesuaian shadow dan transisi agar lebih smooth */
    .transition-all { transition-duration: 400ms !important; }
</style>
@endsection