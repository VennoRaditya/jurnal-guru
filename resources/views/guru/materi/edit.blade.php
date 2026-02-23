@extends('layouts.app')

@section('title', 'Edit Materi')
@section('header_title', 'Perbarui Jurnal')
@section('header_subtitle', 'Lakukan perubahan atau perbaikan pada riwayat materi pembelajaran.')

@section('content')
<div class="max-w-4xl mx-auto pb-20 px-2 md:px-0">
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Top Indicator --}}
        <div class="bg-amber-50 border-b border-amber-100 px-10 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
                <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Mode Pengeditan Aktif</p>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">ID Materi: #{{ $materi->id }}</p>
        </div>

        <div class="p-8 md:p-12">
            <form action="{{ route('guru.materi.update', $materi->id) }}" method="POST" class="space-y-10">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    {{-- Mata Pelajaran (Disabled look) --}}
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Mata Pelajaran</label>
                        <div class="relative group">
                            <input type="text" value="{{ $materi->mata_pelajaran }}" disabled 
                                   class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 text-sm font-black text-slate-400 cursor-not-allowed">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        </div>
                        <p class="text-[9px] text-slate-300 font-bold italic ml-1">*Mata pelajaran tidak dapat diubah</p>
                    </div>

                    {{-- Judul Materi --}}
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Judul Materi</label>
                        <input type="text" name="judul_materi" value="{{ old('judul_materi', $materi->judul_materi) }}" required
                               class="w-full bg-white border-2 border-slate-100 rounded-2xl px-6 py-4 text-sm font-black text-slate-700 focus:border-blue-600/20 focus:ring-4 focus:ring-blue-600/5 transition-all outline-none">
                    </div>
                </div>

                {{-- Ringkasan Pembahasan --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Ringkasan Pembahasan</label>
                    <textarea name="pembahasan" rows="6" required
                              class="w-full bg-white border-2 border-slate-100 rounded-4x1 p-8 text-sm font-bold text-slate-600 focus:border-blue-600/20 focus:ring-4 focus:ring-blue-600/5 transition-all outline-none resize-none leading-relaxed">{{ old('pembahasan', $materi->pembahasan) }}</textarea>
                </div>

                {{-- Footer Actions --}}
                <div class="pt-8 border-t border-slate-50 flex flex-col md:flex-row items-center justify-between gap-6">
                    <a href="{{ route('guru.materi.index') }}" class="group flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-rose-500 transition-all uppercase tracking-widest">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Batalkan Perubahan
                    </a>

                    <button type="submit" class="group relative w-full md:w-auto overflow-hidden bg-slate-900 text-white px-12 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/30 active:scale-95">
                        <span class="relative z-10">Simpan Pembaruan</span>
                        <div class="absolute inset-0 bg-linear-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection