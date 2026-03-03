@extends('layouts.app')

@section('title', 'Pilih Kelas')
@section('header_title', 'Mulai Presensi')

@section('content')
<div class="max-w-4xl mx-auto px-0 md:px-4 pb-20">
    <div class="bg-white md:rounded-[3rem] rounded-t-[2.5rem] border border-slate-100 shadow-[0_15px_35px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Header Card --}}
        <div class="bg-slate-900 p-8 md:p-12 text-white relative overflow-hidden">
            <div class="relative z-10 flex items-center gap-5 md:gap-6">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md rounded-3xl md:rounded-4x1 flex items-center justify-center border border-white/10">
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl md:text-4xl font-black uppercase tracking-tighter">Pilih Kelas</h3>
                    <p class="text-slate-300 text-[10px] md:text-xs font-bold uppercase tracking-[0.25em] mt-1">
                        {{ count($kelasDiampu ?? []) }} Kelas Tersedia
                    </p>
                </div>
            </div>
            {{-- Dekorasi Minimalis --}}
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-600/20 rounded-full blur-3xl"></div>
        </div>

        <div class="p-6 md:p-10">
            <form action="{{ route('guru.presensi.create') }}" method="GET" class="space-y-8 md:space-y-10">
                
                {{-- Grid Kelas --}}
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                    @forelse($kelasDiampu ?? [] as $kelas)
                    <label class="relative cursor-pointer group block">
                        <input type="radio" name="kelas" value="{{ trim($kelas) }}" required class="peer hidden">
                        
                        {{-- Card Kelas --}}
                        <div class="h-full p-6 md:p-8 text-center rounded-4x1 md:rounded-[2.5rem] border-2 border-slate-100 bg-white transition-all duration-300 
                            peer-checked:border-blue-600 peer-checked:bg-blue-50/50 peer-checked:shadow-lg peer-checked:shadow-blue-500/10
                            hover:border-slate-300 hover:shadow-md">
                            
                            {{-- Icon Indicator --}}
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-5 border border-slate-200 transition-all duration-300
                                peer-checked:bg-blue-600 peer-checked:border-blue-700 peer-checked:shadow-inner group-hover:scale-105">
                                <span class="text-slate-500 font-black text-2xl peer-checked:text-white transition-colors">
                                    {{ substr(trim($kelas), 0, 1) }}
                                </span>
                            </div>
                            
                            <p class="text-sm md:text-base font-black text-slate-900 uppercase tracking-tight leading-tight peer-checked:text-blue-700">
                                {{ $kelas }}
                            </p>
                        </div>
                    </label>
                    @empty
                    <div class="col-span-full p-10 md:p-12 bg-amber-50 rounded-4x1 text-center border-2 border-dashed border-amber-200">
                         <div class="w-16 h-16 bg-amber-100 rounded-3xl flex items-center justify-center mx-auto mb-6 text-amber-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                         </div>
                         <p class="text-amber-800 text-xs font-black uppercase tracking-[0.15em]">
                            Belum ada kelas yang ditugaskan.<br>
                            <span class="font-bold opacity-70">Silahkan hubungi Admin Kurikulum.</span>
                         </p>
                    </div>
                    @endforelse
                </div>

                {{-- Footer Action --}}
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" @empty($kelasDiampu) disabled @endempty 
                        class="group relative w-full overflow-hidden {{ empty($kelasDiampu) ? 'bg-slate-200 cursor-not-allowed text-slate-400' : 'bg-slate-900 text-white' }} 
                        py-5 md:py-6 rounded-2xl md:rounded-3xl text-xs md:text-sm font-black uppercase tracking-[0.2em] 
                        transition-all duration-300 hover:shadow-xl hover:shadow-slate-900/10 active:scale-[0.98]">
                        
                        <div class="relative z-10 flex items-center justify-center gap-3">
                            <span>Lanjutkan Presensi</span>
                            <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </div>
                        
                        @if(!empty($kelasDiampu))
                        <div class="absolute inset-0 bg-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        @endif
                    </button>
                    
                    <div class="text-center mt-6">
                        <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center gap-2 text-[10px] md:text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection