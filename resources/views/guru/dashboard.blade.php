@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Halo, ' . Auth::guard('guru')->user()->nama)
@section('header_subtitle', 'Pantau aktivitas pengajar dan statistik siswa secara real-time.')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition hover:shadow-md">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Database Siswa</p>
            <h3 class="text-3xl font-bold text-slate-900 tracking-tight">32</h3>
            <div class="mt-4 flex items-center">
                <span class="text-[11px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">XI RPL 1</span>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition hover:shadow-md">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Materi Terkirim</p>
            <h3 class="text-3xl font-bold text-slate-900 tracking-tight">12</h3>
            <div class="mt-4 flex items-center">
                <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Semester Genap</span>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition hover:shadow-md">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Rata-rata Presensi</p>
            <h3 class="text-3xl font-bold text-slate-900 tracking-tight">95%</h3>
            <div class="mt-4 flex items-center">
                <span class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">Hari Ini</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Jurnal Terakhir</h3>
                <a href="{{ route('guru.materi.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lihat Semua</a>
            </div>
            
            <div class="space-y-3">
                <div class="group flex items-center p-5 bg-white rounded-2xl border border-slate-100 hover:border-blue-200 transition-all cursor-default">
                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors mr-5 font-bold text-xs uppercase">
                        Jan
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-slate-800 leading-tight">Aljabar Linear Dasar</h4>
                        <p class="text-[11px] text-slate-400 mt-1 font-medium italic">Matematika • 2 jam yang lalu</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-tighter bg-emerald-50 px-2 py-1 rounded-full">Terkirim</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden flex flex-col justify-center border border-slate-800">
            <div class="relative z-10">
                <h3 class="text-2xl font-bold text-white tracking-tight mb-3">Lengkapi Jurnal Harian</h3>
                <p class="text-slate-400 mb-8 text-sm leading-relaxed max-w-xs font-medium">Pastikan semua data kehadiran dan materi telah tercatat sebelum menutup hari.</p>
                <a href="{{ route('guru.absensi.index') }}" class="inline-flex items-center justify-center bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold text-xs hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 uppercase tracking-widest">
                    Input Sekarang
                </a>
            </div>
            
            <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl"></div>
            <div class="absolute right-10 top-10 w-20 h-20 border border-slate-800 rounded-full opacity-20"></div>
        </div>
    </div>
@endsection