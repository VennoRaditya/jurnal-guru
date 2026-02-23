@extends('layouts.app')

@section('title', 'Data Murid')

@section('content')
<div class="space-y-8 pb-20 px-2 md:px-0">
    
    {{-- Search & Statistics Card --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] p-10 overflow-hidden relative">
        {{-- Decorative Background --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-[5rem] z-0"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-end lg:items-center gap-8">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-slate-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-800 tracking-tighter uppercase">Direktori Siswa</h2>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Master Database Student</p>
                    </div>
                </div>
            </div>
            
            <div class="w-full lg:w-auto">
                <form action="{{ route('guru.siswa.index') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari Nama atau NIS..." 
                           class="w-full lg:w-80 bg-slate-50 border-2 border-transparent rounded-3x1 pl-14 pr-6 py-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-indigo-600/20 focus:ring-4 focus:ring-indigo-600/5 transition-all outline-none">
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <button type="submit" class="hidden">Cari</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="px-10 py-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="flex -space-x-3">
                    <div class="w-10 h-10 rounded-full border-4 border-white bg-indigo-100 flex items-center justify-center text-indigo-600 font-black text-xs">S</div>
                    <div class="w-10 h-10 rounded-full border-4 border-white bg-blue-100 flex items-center justify-center text-blue-600 font-black text-xs">M</div>
                    <div class="w-10 h-10 rounded-full border-4 border-white bg-emerald-100 flex items-center justify-center text-emerald-600 font-black text-xs">A</div>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 uppercase tracking-tighter text-sm">Database Terpusat</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Akademik Siswa Aktif</p>
                </div>
            </div>
            <div class="px-5 py-2.5 bg-indigo-50 rounded-2xl border border-indigo-100/50">
                <span class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em]">
                    {{ number_format($siswas->total(), 0, ',', '.') }} Total Murid
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="pl-12 pr-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Siswa</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">NIS / Identitas</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Kelas & Jurusan</th>
                        <th class="pr-12 pl-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($siswas as $s)
                    <tr class="group hover:bg-indigo-50/30 transition-all duration-300">
                        <td class="pl-12 pr-6 py-6">
                            <div class="flex items-center gap-4">
                                {{-- Initial Avatar --}}
                                <div class="w-12 h-12 rounded-2xl bg-linear-to-br from-slate-100 to-slate-200 border border-slate-200 flex items-center justify-center text-slate-500 font-black text-sm group-hover:from-indigo-600 group-hover:to-blue-600 group-hover:text-white group-hover:shadow-lg group-hover:shadow-indigo-200 transition-all duration-500">
                                    {{ substr($s->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-700 group-hover:text-indigo-600 transition-colors uppercase tracking-tight">{{ $s->nama }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Pelajar Aktif</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 border border-slate-100 text-slate-500 group-hover:bg-white transition-colors">
                                <span class="text-[11px] font-black tracking-widest font-mono">{{ $s->nis }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-block bg-white text-slate-700 border-2 border-slate-100 px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest group-hover:border-indigo-600/20 group-hover:text-indigo-600 transition-all">
                                {{ $s->kelas }}
                            </span>
                        </td>
                        <td class="pr-12 pl-6 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Verified</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-32 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 opacity-50">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <p class="text-[10px] font-black uppercase tracking-[0.3em]">Data tidak ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-12 py-10 bg-slate-50/50 border-t border-slate-100">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">
                    Menampilkan {{ $siswas->firstItem() ?? 0 }} - {{ $siswas->lastItem() ?? 0 }} dari {{ $siswas->total() }} Murid
                </p>
                <div class="pagination-premium">
                    {{ $siswas->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Pagination Styling */
    .pagination-premium .pagination { @apply flex gap-2; }
    .pagination-premium .page-item .page-link { @apply rounded-xl border-none bg-white text-[10px] font-black text-slate-500 px-4 py-2.5 shadow-sm hover:bg-indigo-600 hover:text-white transition-all; }
    .pagination-premium .page-item.active .page-link { @apply bg-indigo-600 text-white shadow-lg shadow-indigo-100; }
</style>
@endsection