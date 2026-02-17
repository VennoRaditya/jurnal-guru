@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Halo, ' . Auth::guard('guru')->user()->nama)
@section('header_subtitle', 'Pantau aktivitas pengajar dan statistik siswa secara real-time.')

@section('content')
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Database Siswa - Sekarang Otomatis --}}
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition hover:shadow-md">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Murid</p>
            <h3 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $total_siswa }}</h3>
            <div class="mt-4 flex items-center">
                <span class="text-[11px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md text-uppercase">Data Global</span>
            </div>
        </div>

        {{-- Materi Terkirim - Sekarang Otomatis --}}
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition hover:shadow-md">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jurnal Terisi</p>
            <h3 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $total_materi }}</h3>
            <div class="mt-4 flex items-center">
                <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Total Materi</span>
            </div>
        </div>

        {{-- Info Mapel --}}
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition hover:shadow-md">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mata Pelajaran</p>
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight truncate">{{ Auth::guard('guru')->user()->mapel }}</h3>
            <div class="mt-4 flex items-center">
                <span class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md uppercase">{{ Auth::guard('guru')->user()->nip }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        {{-- Recent Jurnal --}}
        <div>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Jurnal Terakhir Anda</h3>
                <a href="{{ route('guru.materi.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lihat Riwayat</a>
            </div>
            
            <div class="space-y-3">
                @forelse($recent_materi as $materi)
                <div class="group flex items-center p-5 bg-white rounded-2xl border border-slate-100 hover:border-blue-200 transition-all cursor-default">
                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors mr-5 font-bold text-[10px] uppercase text-center leading-tight">
                        {{ $materi->created_at->format('d') }}<br>{{ $materi->created_at->format('M') }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-slate-800 leading-tight">{{ $materi->judul_materi }}</h4>
                        <p class="text-[11px] text-slate-400 mt-1 font-medium italic">Kelas {{ $materi->kelas }} • {{ $materi->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-tighter bg-emerald-50 px-2 py-1 rounded-full text-nowrap">Tersimpan</span>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-xs font-medium text-slate-400">Belum ada jurnal yang diinput.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Call to Action Card --}}
        <div class="bg-slate-900 p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden flex flex-col justify-center border border-slate-800">
            <div class="relative z-10">
                <h3 class="text-2xl font-bold text-white tracking-tight mb-3">Lengkapi Jurnal Hari Ini</h3>
                <p class="text-slate-400 mb-8 text-sm leading-relaxed max-w-xs font-medium">Jangan biarkan catatan mengajar Anda kosong. Input absensi dan materi sekarang juga.</p>
                <a href="{{ route('guru.presensi.select') }}" class="inline-flex items-center justify-center bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold text-xs hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 uppercase tracking-widest">
                    Mulai Absensi
                </a>
            </div>
            
            {{-- Decoration --}}
            <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl"></div>
            <div class="absolute right-10 top-10 w-20 h-20 border border-slate-800 rounded-full opacity-20"></div>
        </div>
    </div>
@endsection