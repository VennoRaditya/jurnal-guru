@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('header_title', 'Dashboard')
@section('header_subtitle', 'Pantau progres pembelajaran dan jurnal hari ini.')

@section('content')
<div class="px-2 md:px-0 pb-12 space-y-6 md:space-y-8">
    
    {{-- 1. HEADER SECTION: PROFIL & MAPEL --}}
    <div class="relative overflow-hidden bg-slate-900 rounded-[2.5rem] p-6 md:p-10 text-white shadow-2xl">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 bg-blue-500/20 text-blue-300 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-500/30">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    Guru Aktif • SMKN 43
                </div>
                <h1 class="text-3xl md:text-5xl font-black tracking-tighter uppercase italic leading-none">
                    {{ Auth::guard('guru')->user()->mapel }}
                </h1>
                <p class="text-slate-400 font-medium text-sm md:text-base tracking-tight">
                    Selamat mengajar, <span class="text-white">{{ Auth::guard('guru')->user()->nama }}</span> (NIP: {{ Auth::guard('guru')->user()->nip }})
                </p>
            </div>
            
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <a href="{{ route('guru.absensi.select') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-6 py-4 rounded-2xl font-black text-xs transition-all active:scale-95 uppercase tracking-widest shadow-lg shadow-blue-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Input Jurnal
                </a>
                <a href="{{ route('guru.materi.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white px-6 py-4 rounded-2xl font-black text-xs transition-all backdrop-blur-sm uppercase tracking-widest border border-white/10">
                    Riwayat
                </a>
            </div>
        </div>
        {{-- Dekorasi Background --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-600 rounded-full blur-[100px] opacity-20"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-indigo-600 rounded-full blur-[100px] opacity-20"></div>
    </div>

    {{-- 2. STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Card: Total Jurnal --}}
        <div class="bg-white p-6 rounded-4xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Jurnal</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl md:text-4xl font-black text-slate-900 leading-none">{{ $total_materi ?? 0 }}</span>
                <span class="text-xs font-bold text-slate-400 mb-1">Entri</span>
            </div>
        </div>

        {{-- Card: Jurnal Bulan Ini --}}
        <div class="bg-white p-6 rounded-4xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Bulan Ini</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl md:text-4xl font-black text-blue-600 leading-none">{{ $jurnal_bulan_ini ?? 0 }}</span>
                <span class="text-xs font-bold text-slate-400 mb-1">Materi</span>
            </div>
        </div>

        {{-- Card: Progres Administrasi (UPDATE: Pengganti Siswa Diampu) --}}
        <div class="bg-white p-6 rounded-4xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Progres Jurnal</p>
            <div class="flex items-end gap-2">
                @php
                    // Target asumsi 20 entri/hari kerja per bulan
                    $target = 20; 
                    $persen = ($jurnal_bulan_ini ?? 0) > 0 ? min(round(($jurnal_bulan_ini / $target) * 100), 100) : 0;
                @endphp
                <span class="text-3xl md:text-4xl font-black text-indigo-600 leading-none">{{ $persen }}%</span>
                <span class="text-xs font-bold text-slate-400 mb-1">Target</span>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-indigo-500 h-full rounded-full transition-all duration-500" style="width: {{ $persen }}%"></div>
            </div>
        </div>

        {{-- Card: Status Hari Ini --}}
        <div class="bg-white p-6 rounded-4xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status Hari Ini</p>
            <div class="flex flex-col gap-1">
                @if($sudah_isi_hari_ini)
                    <span class="inline-flex items-center text-emerald-600 font-black text-sm uppercase tracking-tighter italic">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        Tuntas
                    </span>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $presensi_hari_ini }} Kelas Terisi</p>
                @else
                    <span class="inline-flex items-center text-rose-600 font-black text-sm uppercase tracking-tighter italic">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        Belum Isi
                    </span>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Segera input jurnal</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        {{-- LEFT COLUMN: RECENT ACTIVITY --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-6 md:p-8 shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight italic uppercase">Jurnal Terbaru</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Riwayat Mengajar Anda</p>
                    </div>
                    <a href="{{ route('guru.materi.index') }}" class="p-3 bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white rounded-2xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($recent_materi as $materi)
                    <div class="group relative bg-slate-50/50 hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 p-5 rounded-4xl border border-slate-100 transition-all duration-300">
                        <div class="flex items-center gap-5">
                            <div class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-slate-100 text-slate-900 shrink-0 shadow-sm group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-colors">
                                <span class="text-xl font-black leading-none">{{ \Carbon\Carbon::parse($materi->tanggal)->format('d') }}</span>
                                <span class="text-[9px] font-black uppercase tracking-widest opacity-60">{{ \Carbon\Carbon::parse($materi->tanggal)->format('M') }}</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="bg-blue-100 text-blue-600 text-[9px] font-black px-2 py-0.5 rounded-md uppercase">
                                        {{ $materi->kelas->nama_kelas ?? $materi->kelas }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        {{ \Carbon\Carbon::parse($materi->tanggal)->translatedFormat('l') }}
                                    </span>
                                </div>
                                <h4 class="text-sm md:text-base font-black text-slate-800 truncate">{{ $materi->materi_kd }}</h4>
                                <p class="text-xs text-slate-500 line-clamp-1 mt-1 font-medium">{{ $materi->kegiatan_pembelajaran }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Belum ada riwayat jurnal</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: QUICK INFO --}}
        <div class="space-y-6">
            {{-- Info Card: Total Kelas --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm">
                <h3 class="text-lg font-black text-slate-900 tracking-tight italic uppercase mb-6">Info Mengajar</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                        <span class="text-xs font-bold text-slate-500 uppercase">Total Kelas</span>
                        <span class="text-lg font-black text-slate-900">{{ $total_kelas }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                        <span class="text-xs font-bold text-slate-500 uppercase">Input Hari Ini</span>
                        <span class="text-lg font-black text-blue-600">{{ $presensi_hari_ini }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Link PDF --}}
            <div class="group bg-linear-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden">
                <div class="relative z-10">
                    <h4 class="text-xl font-black uppercase italic leading-tight mb-2">Laporan<br>Bulanan</h4>
                    <p class="text-blue-100/70 text-xs font-medium mb-6">Unduh rekap jurnal periode {{ now()->translatedFormat('F Y') }}.</p>
                    
                    <a href="{{ route('guru.materi.cetak') }}?bulan_tahun={{ now()->format('Y-m') }}" class="inline-flex items-center justify-center w-full py-4 bg-white text-blue-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-[1.02] transition-transform shadow-lg shadow-black/10">
                        Cetak PDF
                    </a>
                </div>
                <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-white/10 rotate-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path></svg>
            </div>
        </div>
    </div>
</div>
@endsection