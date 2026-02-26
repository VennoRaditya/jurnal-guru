@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Halo, ' . Auth::guard('guru')->user()->nama)

@section('content')
<div class="space-y-6 md:space-y-10 px-0">
    
    {{-- Header Section (Mobile Optimized) --}}
    <div class="md:hidden mb-2 px-1">
        <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-1">Overview Akademik</p>
        <h2 class="text-2xl font-black text-slate-900 tracking-tighter">Statistik Pengajar</h2>
    </div>

    {{-- Stat Cards Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6">
        {{-- Card: Total Murid --}}
        <div class="bg-white p-5 md:p-8 rounded-4x1 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <div class="w-9 h-9 md:w-12 md:h-12 bg-blue-50 text-blue-600 rounded-xl md:rounded-2xl flex items-center justify-center mb-3 md:mb-6">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <p class="text-[9px] md:text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Murid</p>
                <h3 class="text-2xl md:text-4xl font-black text-slate-900 tracking-tighter">{{ $total_siswa }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-blue-50/50 rounded-full blur-xl"></div>
        </div>

        {{-- Card: Jurnal Terisi --}}
        <div class="bg-white p-5 md:p-8 rounded-4x1 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <div class="w-9 h-9 md:w-12 md:h-12 bg-emerald-50 text-emerald-600 rounded-xl md:rounded-2xl flex items-center justify-center mb-3 md:mb-6">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-[9px] md:text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Jurnal Terisi</p>
                <h3 class="text-2xl md:text-4xl font-black text-slate-900 tracking-tighter">{{ $total_materi }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-emerald-50/50 rounded-full blur-xl"></div>
        </div>

        {{-- Card: Mapel (Full Width on Mobile) --}}
        <div class="col-span-2 lg:col-span-1 bg-white p-5 md:p-8 rounded-4x1 border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-9 h-9 md:w-12 md:h-12 bg-indigo-50 text-indigo-600 rounded-xl md:rounded-2xl flex items-center justify-center">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Mata Pelajaran</p>
                    <h3 class="text-sm md:text-xl font-black text-slate-900 tracking-tight leading-tight uppercase italic">{{ Auth::guard('guru')->user()->mapel }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 md:gap-10">
        
        {{-- Recent Jurnal Section --}}
        <div class="lg:col-span-3 order-2 lg:order-1">
            <div class="flex justify-between items-end mb-6 px-1">
                <div>
                    <h3 class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Aktivitas Terbaru</h3>
                    <h4 class="text-lg md:text-xl font-black text-slate-900">Riwayat Jurnal</h4>
                </div>
                <a href="{{ route('guru.materi.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-4 py-2 rounded-full hover:bg-blue-600 hover:text-white transition-all">
                    Lihat Semua
                </a>
            </div>
            
            <div class="space-y-3">
                @forelse($recent_materi as $materi)
                <div class="group bg-white p-4 rounded-3xl border border-slate-100 shadow-sm transition-all active:scale-[0.98] flex items-center gap-4">
                    {{-- Ganti bagian ini --}}
<span class="text-sm font-black leading-none">
    {{ \Carbon\Carbon::parse($materi->tanggal)->format('d') }}
</span>
<span class="text-[8px] font-black uppercase tracking-tighter">
    {{ \Carbon\Carbon::parse($materi->tanggal)->format('M') }}
</span>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-black text-slate-800 truncate leading-tight mb-1">{{ $materi->materi_kd }}</h4>
                        <div class="flex items-center gap-2 text-[9px] font-bold uppercase tracking-widest text-slate-400">
                            <span class="text-blue-600">Kelas {{ $materi->kelas }}</span>
                            <span>•</span>
                            <span class="truncate">{{ $materi->mata_pelajaran }}</span>
                        </div>
                    </div>

                    {{-- Arrow --}}
                    <div class="text-slate-300 group-hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center bg-white rounded-4x1 border-2 border-dashed border-slate-100">
                    <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Belum ada data jurnal</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Call to Action Card (Floating Style for Mobile) --}}
        <div class="lg:col-span-2 order-1 lg:order-2">
            <div class="sticky top-28">
                <div class="bg-slate-900 p-6 md:p-10 rounded-[2.5rem] shadow-xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="inline-flex p-3 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 mb-4 md:mb-6">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-xl md:text-3xl font-black text-white tracking-tight mb-2">Input Jurnal & Absen</h3>
                        <p class="text-slate-400 text-[11px] md:text-sm leading-relaxed mb-6 md:mb-8 font-medium">Lengkapi administrasi mengajar Anda hari ini dengan cepat dan mudah.</p>
                        
                        <a href="{{ route('guru.presensi.select') }}" class="w-full inline-flex items-center justify-center bg-blue-600 text-white px-6 py-4 md:py-5 rounded-2xl md:rounded-3xl font-black text-[10px] md:text-[11px] hover:bg-white hover:text-slate-900 transition-all duration-300 uppercase tracking-[0.2em] shadow-lg shadow-blue-500/20">
                            Presensi Sekarang
                        </a>
                    </div>
                    
                    {{-- Abstract Decoration --}}
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-600/20 rounded-full blur-[50px]"></div>
                </div>

                {{-- Quick Tip Mobile Only --}}
                <div class="mt-4 p-4 bg-blue-50/50 border border-blue-100 rounded-3xl md:hidden">
                    <div class="flex items-center gap-3">
                        <span class="shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/></svg>
                        </span>
                        <p class="text-[10px] font-bold text-blue-700 leading-tight">Pastikan sinyal stabil saat melakukan submit absensi siswa.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection