@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Halo, ' . Auth::guard('guru')->user()->nama)
@section('header_subtitle', 'Pantau aktivitas pengajar dan statistik siswa secara real-time.')

@section('content')
<div class="space-y-6 md:space-y-8 px-1 md:px-0">
    
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        
        {{-- Card: Total Murid --}}
        <div class="group relative bg-white p-6 md:p-8 rounded-4x1 border border-slate-100 shadow-[0_10px_40px_rgba(0,0,0,0.03)] transition-all duration-500 hover:shadow-blue-500/10 hover:-translate-y-1 overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="text-[9px] md:text-[10px] font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase tracking-widest">Global Data</span>
                </div>
                <p class="text-[10px] md:text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Murid</p>
                <h3 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tighter">{{ $total_siswa }}</h3>
            </div>
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50/50 rounded-full blur-2xl transition-transform group-hover:scale-150"></div>
        </div>

        {{-- Card: Jurnal Terisi --}}
        <div class="group relative bg-white p-6 md:p-8 rounded-4x1 border border-slate-100 shadow-[0_10px_40px_rgba(0,0,0,0.03)] transition-all duration-500 hover:shadow-emerald-500/10 hover:-translate-y-1 overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-[9px] md:text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-widest">All Materials</span>
                </div>
                <p class="text-[10px] md:text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Jurnal Terisi</p>
                <h3 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tighter">{{ $total_materi }}</h3>
            </div>
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50/50 rounded-full blur-2xl transition-transform group-hover:scale-150"></div>
        </div>

        {{-- Card: Info Mapel --}}
        <div class="group relative bg-white p-6 md:p-8 rounded-4x1 border border-slate-100 shadow-[0_10px_40px_rgba(0,0,0,0.03)] transition-all duration-500 hover:shadow-indigo-500/10 hover:-translate-y-1 overflow-hidden sm:col-span-2 lg:col-span-1">
            <div class="relative z-10 h-full">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-[9px] md:text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-widest">{{ Auth::guard('guru')->user()->nip }}</span>
                </div>
                <p class="text-[10px] md:text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Mata Pelajaran</p>
                <h3 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight leading-tight line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ Auth::guard('guru')->user()->mapel }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 md:gap-10">
        
        {{-- Recent Jurnal --}}
        <div class="lg:col-span-3">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-[0.2em]">Jurnal Terbaru</h3>
                    <div class="h-1 w-10 bg-blue-600 rounded-full mt-1"></div>
                </div>
                <a href="{{ route('guru.materi.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-700 transition flex items-center gap-1 group">
                    View All
                    <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($recent_materi as $materi)
                <div class="group flex flex-col sm:flex-row sm:items-center p-4 md:p-5 bg-white rounded-3xl border border-slate-50 hover:border-blue-100 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                    <div class="flex items-center mb-3 sm:mb-0">
                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex flex-col items-center justify-center text-slate-500 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 mr-4 font-black text-[9px] uppercase leading-none">
                            <span class="text-sm mb-0.5">{{ $materi->created_at->format('d') }}</span>
                            <span>{{ $materi->created_at->format('M') }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-slate-800 leading-tight group-hover:text-blue-600 transition-colors">{{ $materi->judul_materi }}</h4>
                            <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest flex items-center gap-2">
                                <span class="text-blue-600">Kelas {{ $materi->kelas }}</span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span>{{ $materi->created_at->diffForHumans() }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="sm:ml-auto flex justify-between sm:justify-end items-center">
                        <span class="text-[8px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 px-3 py-1.5 rounded-full">Stored</span>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center bg-slate-50 rounded-4x1 border-2 border-dashed border-slate-200">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Belum ada jurnal yang diinput.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Call to Action Card --}}
        <div class="lg:col-span-2">
            <div class="sticky top-6">
                <div class="bg-slate-900 p-8 md:p-10 rounded-[3rem] shadow-2xl relative overflow-hidden flex flex-col min-h-80 justify-between border border-slate-800 group">
                    <div class="relative z-10">
                        <div class="inline-flex p-3 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 mb-6">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-3xl font-black text-white tracking-tight mb-4">Lengkapi Jurnal Hari Ini</h3>
                        <p class="text-slate-400 text-sm leading-relaxed font-medium mb-8">Pastikan data presensi dan materi tersimpan untuk akurasi laporan bulanan Anda.</p>
                    </div>
                    
                    <div class="relative z-10">
                        <a href="{{ route('guru.presensi.select') }}" class="w-full inline-flex items-center justify-center bg-blue-600 text-white px-8 py-5 rounded-3x1 font-black text-[11px] hover:bg-white hover:text-slate-900 transition-all duration-300 shadow-xl shadow-blue-500/20 uppercase tracking-[0.2em]">
                            Input Absensi Sekarang
                        </a>
                    </div>
                    
                    {{-- Decoration --}}
                    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-blue-600/20 rounded-full blur-[80px] group-hover:bg-blue-600/30 transition-all duration-700"></div>
                    <div class="absolute -left-10 -top-10 w-40 h-40 bg-indigo-600/10 rounded-full blur-[60px]"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection