@extends('layouts.app')

@section('title', 'Pilih Kelas')
@section('header_title', 'Mulai Presensi')
@section('header_subtitle', 'Pilih kelas yang akan Anda ajar hari ini.')

@section('content')
<div class="max-w-4xl mx-auto px-2 md:px-0 pb-20">
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.03)] overflow-hidden">
        
        {{-- Header Card --}}
        <div class="bg-linear-to-r from-indigo-600 to-blue-700 p-8 md:p-12 text-white relative"> {{-- Perbaikan: bg-gradient-to-r --}}
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center space-x-5">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-4x1 flex items-center justify-center border border-white/30 shadow-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black uppercase tracking-tighter">Konfigurasi Kelas</h3>
                        <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mt-1 opacity-80">
                            Pilih satu dari {{ count($kelasDiampu ?? []) }} kelas ampuan Anda
                        </p>
                    </div>
                </div>
            </div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <div class="p-8 md:p-12">
            <form action="{{ route('guru.presensi.create') }}" method="GET" class="space-y-10">
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between ml-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Daftar Kelas Anda</label>
                        <span class="w-24 h-px bg-slate-100"></span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($kelasDiampu ?? [] as $kelas)
                        <label class="relative cursor-pointer group">
                            {{-- PENTING: Gunakan trim pada value untuk mencegah spasi liar --}}
                            <input type="radio" name="kelas" value="{{ trim($kelas) }}" required class="peer hidden">
                            
                            <div class="p-6 text-center rounded-[2.5rem] border-2 border-slate-50 bg-slate-50 transition-all duration-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 group-hover:bg-white group-hover:shadow-xl group-hover:-translate-y-1">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors peer-checked:bg-indigo-600 peer-checked:text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-black uppercase tracking-tighter">{{ $kelas }}</p>
                            </div>
                        </label>
                        @empty
                        {{-- Keadaan jika array kosong --}}
                        <div class="col-span-full p-12 bg-rose-50 rounded-[3rem] text-center border-2 border-dashed border-rose-100">
                             <p class="text-rose-600 text-xs font-black uppercase tracking-widest">Belum ada kelas yang ditugaskan</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-50">
                    <button type="submit" @empty($kelasDiampu) disabled @endempty 
                        class="group relative w-full overflow-hidden {{ empty($kelasDiampu) ? 'bg-slate-200 cursor-not-allowed' : 'bg-slate-900' }} text-white py-6 rounded-4x1 text-xs font-black uppercase tracking-[0.3em] transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/20 active:scale-[0.98]">
                        <div class="relative z-10 flex items-center justify-center space-x-4">
                            <span>Mulai Isi Jurnal & Absen</span>
                            <svg class="w-5 h-5 transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                        <div class="absolute inset-0 bg-linear-to-r from-indigo-600 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </button>
                    
                    <a href="{{ route('guru.dashboard') }}" class="block text-center mt-8 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">
                        Kembali ke Beranda
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection