@extends('layouts.app')

@section('title', 'Pilih Kelas')
@section('header_title', 'Mulai Presensi')
@section('header_subtitle', 'Pilih kelas yang akan Anda ajar hari ini.')

@section('content')
<div class="max-w-4xl mx-auto px-2 md:px-0 pb-20">
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.03)] overflow-hidden">
        
        {{-- Header Card dengan Gradient --}}
        <div class="bg-linear-to-r from-indigo-600 to-blue-700 p-8 md:p-12 text-white relative">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center space-x-5">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-4x1 flex items-center justify-center border border-white/30 shadow-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black uppercase tracking-tighter">Konfigurasi Kelas</h3>
                        <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mt-1 opacity-80">Siapkan Jurnal Pembelajaran Hari Ini</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <span class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border border-white/20">
                        Real-time Database
                    </span>
                </div>
            </div>
            
            {{-- Decorative Circles --}}
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <div class="p-8 md:p-12">
            <form action="{{ route('guru.presensi.create') }}" method="GET" class="space-y-10">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    
                    {{-- Seleksi Tingkat --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between ml-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pilih Tingkat</label>
                            <span class="w-8 h-px bg-slate-100"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach(['X' => '10', 'XI' => '11', 'XII' => '12'] as $val => $label)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="tingkat" value="{{ $val }}" required class="peer hidden">
                                <div class="p-4 text-center rounded-2xl border-2 border-slate-50 bg-slate-50 transition-all duration-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 group-hover:bg-white group-hover:shadow-md">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:text-indigo-600 mb-1">Kelas</p>
                                    <p class="text-xl font-black">{{ $label }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Dropdown Jurusan dengan Custom Style --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between ml-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pilih Jurusan</label>
                            <span class="w-8 h-px bg-slate-100"></span>
                        </div>
                        <div class="relative group">
                            <select name="jurusan" required class="appearance-none w-full bg-slate-50 border-2 border-transparent rounded-2xl px-6 py-5 text-sm font-black text-slate-700 focus:bg-white focus:border-indigo-600/20 focus:ring-4 focus:ring-indigo-600/5 transition-all outline-none cursor-pointer">
                                <option value="" disabled selected>Pilih Jurusan...</option>
                                @foreach(['RPL', 'TKJ', 'AKL', 'MANLOG', 'BR'] as $j)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <p class="text-[9px] text-slate-400 font-bold italic ml-1">*Pastikan jurusan sesuai dengan jadwal mengajar.</p>
                    </div>

                </div>

                {{-- Action Button --}}
                <div class="pt-6 border-t border-slate-50">
                    <button type="submit" class="group relative w-full overflow-hidden bg-slate-900 text-white py-6 rounded-4x1 text-xs font-black uppercase tracking-[0.3em] transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/20 active:scale-[0.98]">
                        <div class="relative z-10 flex items-center justify-center space-x-4">
                            <span>Buka Jurnal & Presensi</span>
                            <svg class="w-5 h-5 transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                        {{-- Button Glow Effect --}}
                        <div class="absolute inset-0 bg-linear-to-r from-indigo-600 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </button>
                    
                    <a href="{{ route('guru.dashboard') }}" class="block text-center mt-6 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">
                        Batal dan Kembali ke Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Info Footer (Mobile Only) --}}
    <div class="mt-8 md:hidden bg-blue-50 rounded-3xl p-6 border border-blue-100">
        <div class="flex items-center space-x-4 text-blue-600">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <p class="text-[10px] font-bold leading-relaxed">Sistem akan memuat daftar siswa berdasarkan tingkat dan jurusan yang Anda pilih.</p>
        </div>
    </div>
</div>

<style>
    /* Menghilangkan scrollbar pada dropdown jika diperlukan */
    select option {
        font-weight: 700;
        padding: 10px;
    }
</style>
@endsection