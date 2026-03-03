@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Analytics Overview')
@section('header_subtitle', 'Selamat datang kembali, Admin.')

@section('content')
<div class="space-y-6 md:space-y-10 pb-10 px-2 md:px-0">
    
    {{-- 1. STAT CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
        
        {{-- Card: Tenaga Pengajar --}}
        <div class="group relative bg-white p-6 md:p-7 rounded-[2.5rem] border border-slate-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all duration-500 hover:shadow-blue-500/10 hover:-translate-y-1 overflow-hidden animate-fade-in-up" style="animation-delay: 100ms">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="flex h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Tenaga Pengajar</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $total_guru ?? 0 }}</h3>
                    <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Orang</span>
                </div>
            </div>
        </div>

        {{-- Card: Total Siswa --}}
        <div class="group relative bg-white p-6 md:p-7 rounded-[2.5rem] border border-slate-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all duration-500 hover:shadow-indigo-500/10 hover:-translate-y-1 overflow-hidden animate-fade-in-up" style="animation-delay: 200ms">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="bg-indigo-100 text-indigo-600 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Active</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Siswa</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $total_siswa ?? 0 }}</h3>
                    <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Murid</span>
                </div>
            </div>
        </div>

        {{-- Card: Total Kelas --}}
        <div class="group relative bg-white p-6 md:p-7 rounded-[2.5rem] border border-slate-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all duration-500 hover:shadow-purple-500/10 hover:-translate-y-1 overflow-hidden animate-fade-in-up" style="animation-delay: 300ms">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Kelas</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $total_kelas ?? 0 }}</h3>
                    <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Ruangan</span>
                </div>
            </div>
        </div>

        {{-- Card: Log Sistem --}}
        <div class="group relative bg-white p-6 md:p-7 rounded-[2.5rem] border border-slate-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all duration-500 hover:shadow-amber-500/10 hover:-translate-y-1 overflow-hidden animate-fade-in-up" style="animation-delay: 400ms">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Log Terakhir</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-sm font-black text-slate-800 tracking-tight truncate" title="{{ $last_log->description ?? 'Tidak ada aktivitas' }}">
                        {{ $last_log->description ?? 'Sistem Berjalan Normal' }}
                    </h3>
                </div>
                <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">
                    {{ $last_log ? $last_log->created_at->diffForHumans() : 'Standby' }}
                </p>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT AREA --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Tabel Guru Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 p-6 md:p-8 shadow-[0_10px_30px_rgba(0,0,0,0.03)] animate-fade-in-up" style="animation-delay: 500ms">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h3 class="font-black text-slate-800 uppercase tracking-tighter text-xl italic">Guru Terbaru</h3>
                    <p class="text-slate-400 text-[10px] font-bold mt-1 uppercase tracking-widest">Penambahan kepegawaian terakhir</p>
                </div>
                <a href="{{ route('admin.guru.index') }}" class="w-full md:w-auto text-center bg-slate-900 text-[10px] font-black text-white px-6 py-3 rounded-xl uppercase tracking-widest hover:bg-blue-600 transition-all active:scale-95">
                    Kelola Semua
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recent_teachers as $index => $guru)
                <div class="flex items-center justify-between p-5 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-blue-100 hover:bg-blue-50/30 transition-all animate-fade-in-right" style="animation-delay: {{ 600 + ($index * 100) }}ms">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center font-black text-blue-600 text-lg border border-blue-100 shadow-inner">
                            {{ substr($guru->nama, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-800">{{ $guru->nama }}</p>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ $guru->mapel }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-white bg-blue-500 px-3 py-1 rounded-full uppercase tracking-wider">
                            New
                        </span>
                        <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">{{ $guru->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-slate-400 border-2 border-dashed border-slate-100 rounded-3xl">
                    <p class="text-xs font-bold uppercase tracking-widest">Belum ada data guru terbaru</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- 3. QUICK ACTIONS & STATUS --}}
        <div class="flex flex-col gap-6">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] animate-fade-in-up" style="animation-delay: 600ms">
                <h3 class="text-xl font-black text-slate-800 tracking-tight mb-5 italic">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.guru.index') }}" class="flex flex-col items-center justify-center gap-3 p-5 bg-slate-50 rounded-2xl hover:bg-blue-50 transition-all group border border-slate-100 hover:border-blue-100">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-700 text-center uppercase tracking-wider">Add Guru</span>
                    </a>
                    <a href="{{ route('admin.siswa.index') }}" class="flex flex-col items-center justify-center gap-3 p-5 bg-slate-50 rounded-2xl hover:bg-indigo-50 transition-all group border border-slate-100 hover:border-indigo-100">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-700 text-center uppercase tracking-wider">Add Siswa</span>
                    </a>
                </div>
            </div>

            {{-- System Status --}}
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl animate-fade-in-up relative overflow-hidden" style="animation-delay: 700ms">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-black tracking-tight uppercase italic text-blue-400">System Status</h3>
                        <span class="text-[9px] font-black text-emerald-400 bg-emerald-950 px-3 py-1 rounded-full uppercase tracking-widest border border-emerald-500/30">Online</span>
                    </div>
                    <p class="text-slate-400 text-xs mb-4 font-medium">Semua layanan database dan server berjalan normal.</p>
                    <div class="w-full bg-slate-800 rounded-full h-1.5 mb-2">
                        <div class="bg-blue-500 h-1.5 rounded-full shadow-[0_0_10px_rgba(59,130,246,0.5)]" style="width: 100%"></div>
                    </div>
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Uptime: 99.9%</p>
                </div>
                {{-- Decorative Circle --}}
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-blue-600 rounded-full blur-[80px] opacity-20"></div>
            </div>
        </div>
    </div>
</div>

{{-- 4. CSS UNTUK ANIMASI --}}
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(-15px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    .animate-fade-in-right {
        opacity: 0;
        animation: fadeInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endsection