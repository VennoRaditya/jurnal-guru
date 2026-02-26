@extends('layouts.app')

@section('title', 'Pilih Kelas')
@section('header_title', 'Mulai Presensi')

@section('content')
<div class="max-w-4xl mx-auto px-0 md:px-4 pb-20">
    <div class="bg-white md:rounded-[3rem] rounded-t-[2.5rem] border-x border-t md:border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.03)] overflow-hidden">
        
        {{-- Header Card - Dibuat lebih ringkas di mobile --}}
        <div class="bg-linear-to-r from-indigo-600 to-blue-700 p-6 md:p-12 text-white relative">
            <div class="relative z-10 flex items-center gap-4 md:gap-6">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-white/20 backdrop-blur-xl rounded-2xl md:rounded-3xl flex items-center justify-center border border-white/30 shadow-xl">
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg md:text-2xl font-black uppercase tracking-tight">Pilih Kelas</h3>
                    <p class="text-indigo-100 text-[10px] md:text-xs font-bold uppercase tracking-widest mt-0.5 opacity-80">
                        {{ count($kelasDiampu ?? []) }} Kelas Terdaftar
                    </p>
                </div>
            </div>
            {{-- Dekorasi Abstract --}}
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        </div>

        <div class="p-6 md:p-12">
            <form action="{{ route('guru.presensi.create') }}" method="GET" class="space-y-8 md:space-y-10">
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Daftar Kelas Ampuan</label>
                        <div class="h-px flex-1 bg-slate-100 ml-4"></div>
                    </div>

                    {{-- Grid Kelas: 2 kolom di mobile agar hemat ruang --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                        @forelse($kelasDiampu ?? [] as $kelas)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="kelas" value="{{ trim($kelas) }}" required class="peer hidden">
                            
                            {{-- Card Kelas --}}
                            <div class="p-4 md:p-6 text-center rounded-4x1 border-2 border-slate-50 bg-slate-50 transition-all duration-300 
                                peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 
                                active:scale-95 md:group-hover:bg-white md:group-hover:shadow-xl md:group-hover:-translate-y-1">
                                
                                {{-- Icon Check --}}
                                <div class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl md:rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm 
                                    peer-checked:bg-indigo-600 peer-checked:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                
                                <p class="text-[11px] md:text-sm font-black uppercase tracking-tight truncate">{{ $kelas }}</p>
                            </div>
                        </label>
                        @empty
                        <div class="col-span-full p-8 md:p-12 bg-rose-50/50 rounded-[2.5rem] text-center border-2 border-dashed border-rose-100">
                             <p class="text-rose-600 text-[10px] font-black uppercase tracking-widest leading-loose">
                                Belum ada kelas yang ditugaskan.<br>Silahkan hubungi Admin Kurikulum.
                             </p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-50">
                    {{-- Button Utama --}}
                    <button type="submit" @empty($kelasDiampu) disabled @endempty 
                        class="group relative w-full overflow-hidden {{ empty($kelasDiampu) ? 'bg-slate-200 cursor-not-allowed' : 'bg-slate-900' }} 
                        text-white py-5 md:py-6 rounded-2xl md:rounded-4xl text-[11px] md:text-xs font-black uppercase tracking-[0.2em] 
                        transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/20 active:scale-[0.98]">
                        
                        <div class="relative z-10 flex items-center justify-center space-x-3">
                            <span>Lanjutkan Presensi</span>
                            <svg class="w-4 h-4 transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                        
                        <div class="absolute inset-0 bg-linear-to-r from-indigo-600 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </button>
                    
                    <a href="{{ route('guru.dashboard') }}" class="block text-center mt-6 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">
                        Batal & Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Menghilangkan scrollbar di mobile jika kelas terlalu banyak */
    .grid-container {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .grid-container::-webkit-scrollbar {
        display: none;
    }
</style>
@endsection