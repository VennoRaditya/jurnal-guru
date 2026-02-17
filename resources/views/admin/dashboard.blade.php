@extends('layouts.admin')

@section('title', 'Dashboard Pro')
@section('header_title', 'Analytics Overview')
@section('header_subtitle', 'Selamat datang kembali, Admin. Berikut adalah ringkasan data hari ini.')

@section('content')
<div class="space-y-10">
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Tenaga Pengajar</p>
                <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $total_guru }} <span class="text-lg text-slate-300 font-medium">Personel</span></h3>
            </div>
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-blue-50/50 rounded-full blur-3xl group-hover:bg-blue-100/50 transition-colors"></div>
        </div>

        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Siswa</p>
                <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $total_siswa }} <span class="text-lg text-slate-300 font-medium">Murid</span></h3>
            </div>
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-indigo-50/50 rounded-full blur-3xl group-hover:bg-indigo-100/50 transition-colors"></div>
        </div>

        <div class="bg-slate-900 p-8 rounded-[3rem] shadow-xl shadow-slate-900/20 relative overflow-hidden group">
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div>
                    <h4 class="text-white font-bold text-lg leading-tight">Laporan Presensi<br>Hari Ini</h4>
                    <p class="text-slate-500 text-xs mt-2 font-medium">Klik untuk melihat detail</p>
                </div>
                <a href="#" class="mt-4 inline-flex items-center justify-center w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-2xl backdrop-blur-md transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <div class="absolute right-0 bottom-0 opacity-10">
                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
            </div>
        </div>
    </div>

    {{-- Secondary Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10">
            <div class="flex justify-between items-center mb-8">
                <h3 class="font-black text-slate-800 uppercase tracking-tighter text-xl">Pendaftaran Guru Terakhir</h3>
                <a href="{{ route('admin.guru.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                {{-- Data Dummy/Terbaru --}}
                @foreach(\App\Models\Guru::latest()->take(3)->get() as $guru)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-3xl border border-transparent hover:border-slate-100 hover:bg-white transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center font-bold text-blue-600 shadow-sm">
                            {{ substr($guru->nama, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-800">{{ $guru->nama }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $guru->mapel }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-black text-slate-300">{{ $guru->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-blue-600 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-blue-200">
            <div class="relative z-10">
                <h3 class="text-2xl font-black leading-tight mb-4">Butuh Bantuan Teknis?</h3>
                <p class="text-blue-100 text-sm font-medium mb-8 leading-relaxed text-balance">Hubungi tim IT sekolah jika Anda mengalami kendala pada database atau sistem login.</p>
                <button class="bg-white text-blue-600 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-900/20 hover:bg-slate-50 transition-all">
                    Buka Support Ticket
                </button>
            </div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500 rounded-full blur-3xl opacity-50"></div>
        </div>
    </div>
</div>
@endsection