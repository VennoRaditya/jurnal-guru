@extends('layouts.app')

@section('title', 'Pilih Kelas')
@section('header_title', 'Mulai Presensi')
@section('header_subtitle', 'Pilih kelas yang akan Anda ajar hari ini.')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[40px] border border-slate-100 shadow-sm p-10">
        <div class="flex items-center space-x-4 mb-10">
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Pilih Kelas</h3>
                <p class="text-xs text-slate-400 font-medium">Data kelas diambil dari daftar siswa yang tersedia.</p>
            </div>
        </div>

        {{-- UPDATE ACTION: Mengarah ke guru.presensi.create sesuai web.php terbaru --}}
        <form action="{{ route('guru.presensi.create') }}" method="GET" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Dropdown Tingkat --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tingkat</label>
                    <select name="tingkat" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">Pilih Tingkat</option>
                        <option value="X">Kelas 10</option>
                        <option value="XI">Kelas 11</option>
                        <option value="XII">Kelas 12</option>
                    </select>
                </div>

                {{-- Dropdown Jurusan --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jurusan</label>
                    <select name="jurusan" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">Pilih Jurusan</option>
                        @foreach(['RPL', 'TKJ', 'AKL', 'MANLOG', 'BR'] as $j)
                            <option value="{{ $j }}">{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-5 rounded-3xl text-xs font-black uppercase tracking-[0.2em] hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 flex items-center justify-center space-x-3">
                <span>Buka Jurnal & Presensi</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>
</div>
@endsection