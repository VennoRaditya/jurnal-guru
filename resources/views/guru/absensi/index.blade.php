@extends('layouts.app')

@section('title', 'Input Presensi & Jurnal')

{{-- 1. PERBAIKAN: Gunakan optional chaining agar tidak error jika $kelas_nama null --}}
@section('header_title', 'Kelas ' . ($kelas_nama ?? 'Tidak Terdeteksi'))
@section('header_subtitle', 'Silahkan lengkapi laporan pembelajaran dan absensi hari ini.')

@section('content')
<div class="max-w-5xl mx-auto pb-24 md:pb-12 px-4 md:px-0">
    {{-- ALERT: Notifikasi Error atau Sukses --}}
    @if($errors->any() || session('error'))
        <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-rose-50 border border-rose-100 rounded-3xl p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-rose-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black text-rose-700 uppercase tracking-widest mb-1">Terjadi Kesalahan</h4>
                        @if(session('error'))
                            <p class="text-xs text-rose-600/80 font-bold tracking-tight">{{ session('error') }}</p>
                        @else
                            <ul class="text-[11px] text-rose-600/80 font-bold list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('guru.presensi.storeJurnal') }}" method="POST" class="space-y-8" id="formPresensi">
        @csrf
        {{-- Pastikan kelas_nama ada, jika tidak, bisa menyebabkan error validasi di server --}}
        <input type="hidden" name="kelas" value="{{ $kelas_nama ?? '' }}">

        {{-- BAGIAN 1: JURNAL PEMBELAJARAN --}}
        <div class="bg-white rounded-4x1 md:rounded-[3.5rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] p-6 md:p-14 relative overflow-hidden group">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-50 group-hover:opacity-80 transition-opacity"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-blue-600 rounded-2xl md:rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-blue-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase tracking-tighter">Jurnal</h3>
                        <p class="text-[9px] md:text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Laporan Aktivitas Akademik</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Mata Pelajaran</label>
                        <input type="text" name="mata_pelajaran" required value="{{ old('mata_pelajaran', Auth::guard('guru')->user()->mapel ?? '') }}" 
                            class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-2xl md:rounded-3xl px-5 py-4 text-sm font-black text-slate-700 focus:bg-white focus:border-blue-500/20 transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Materi / KD</label>
                        <input type="text" name="materi_kd" required value="{{ old('materi_kd') }}" placeholder="Misal: KD 3.2 Pemrograman Web"
                            class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-2xl md:rounded-3xl px-5 py-4 text-sm font-black text-slate-700 focus:bg-white focus:border-blue-500/20 transition-all outline-none">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Kegiatan Pembelajaran</label>
                        <textarea name="kegiatan_pembelajaran" required rows="3" placeholder="Gambarkan aktivitas hari ini..."
                            class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-2xl md:rounded-3xl px-5 py-4 text-sm font-bold text-slate-600 focus:bg-white focus:border-blue-500/20 transition-all outline-none min-h-[100px]">{{ old('kegiatan_pembelajaran') }}</textarea>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Penilaian / Evaluasi</label>
                        <textarea name="evaluasi" required rows="3" placeholder="Catatan hasil penilaian..."
                            class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-2xl md:rounded-3xl px-5 py-4 text-sm font-bold text-slate-600 focus:bg-white focus:border-blue-500/20 transition-all outline-none min-h-[100px]">{{ old('evaluasi') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: PRESENSI SISWA --}}
        <div class="bg-white rounded-4x1 md:rounded-[3.5rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden transition-all">
            <div class="p-6 md:p-12 border-b border-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-emerald-500 rounded-2xl md:rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-emerald-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase tracking-tighter">Presensi</h3>
                        <p class="text-[9px] md:text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">
                            {{ count($siswas ?? []) }} Siswa Terdaftar
                        </p>
                    </div>
                </div>
            </div>

            {{-- Versi Desktop: Tabel --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="pl-12 pr-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.25em]">Siswa</th>
                            <th class="px-6 py-5 text-[10px] font-black text-center text-slate-400 uppercase tracking-[0.25em]">Ketidakhadiran (S/I/A)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswas ?? [] as $siswa)
                        <tr class="hover:bg-blue-50/30 transition-all">
                            <td class="pl-12 pr-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-400 uppercase">
                                        {{ substr($siswa->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-700 truncate">{{ $siswa->nama }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">NIS: {{ $siswa->nis }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- PERUBAHAN: Tombol Hadir Dihapus, Siswa default Hadir --}}
                                    @foreach([
                                        'sakit' => ['bg-amber-500', 'S'], 
                                        'izin'  => ['bg-blue-500', 'I'], 
                                        'alfa'  => ['bg-rose-500', 'A']
                                    ] as $status => $attr)
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="absen[{{ $siswa->id }}]" value="{{ $status }}" 
                                                class="sr-only peer">

                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center border-2 
                                                peer-checked:border-transparent 
                                                peer-checked:{{ $attr[0] }} 
                                                peer-checked:text-white 
                                                peer-checked:shadow-lg 
                                                peer-checked:scale-105
                                                border-slate-100 text-slate-300 bg-white
                                                transition-all">
                                                <span class="text-xs font-black">{{ $attr[1] }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                    <span class="text-xs font-bold text-slate-400 ml-2">Default: Hadir</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="py-10 text-center text-xs text-slate-400">Tidak ada siswa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Versi Mobile: Kartu --}}
            <div class="md:hidden p-4 space-y-3">
                @forelse($siswas ?? [] as $siswa)
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center font-black text-slate-400 uppercase shadow-inner">
                            {{ substr($siswa->nama, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-800 truncate">{{ $siswa->nama }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">NIS: {{ $siswa->nis }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-2 pt-2 border-t border-slate-100">
                        {{-- PERUBAHAN: Tombol Hadir Dihapus (Mobile) --}}
                        @foreach([
                            'sakit' => ['bg-amber-500', 'S'], 
                            'izin'  => ['bg-blue-500', 'I'], 
                            'alfa'  => ['bg-rose-500', 'A']
                        ] as $status => $attr)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="absen[{{ $siswa->id }}]" value="{{ $status }}" 
                                    class="sr-only peer">
                        
                                <div class="w-full py-3 rounded-xl flex items-center justify-center border-2 
                                    peer-checked:border-transparent 
                                    peer-checked:{{ $attr[0] }} 
                                    peer-checked:text-white 
                                    peer-checked:shadow-md 
                                    border-slate-200 text-slate-400 bg-white
                                    transition-all">
                                    <span class="text-xs font-black">{{ $attr[1] }}</span>
                                </div>
                                <span class="absolute -top-5 left-1/2 -translate-x-1/2 text-[8px] font-bold text-slate-500 opacity-0 peer-checked:opacity-100 transition-opacity">{{ ucfirst($status) }}</span>
                            </label>
                        @endforeach
                        <div class="col-span-4 text-center text-[10px] font-bold text-slate-400 mt-1">Default: Hadir</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-xs text-slate-400">Tidak ada siswa.</div>
                @endforelse
            </div>

            {{-- Tombol Submit (Sticky di Mobile) --}}
            <div class="fixed md:relative bottom-0 left-0 right-0 p-4 md:p-12 bg-white md:bg-transparent border-t md:border-t-0 border-slate-100 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] md:shadow-none z-50">
                <button type="submit" @if(empty($siswas) || $siswas->isEmpty()) disabled @endif id="btnSubmit"
                    class="group relative w-full md:w-auto overflow-hidden {{ (empty($siswas) || $siswas->isEmpty()) ? 'bg-slate-300' : 'bg-slate-900' }} text-white px-10 py-5 md:py-6 rounded-2xl md:rounded-3xl text-xs md:text-sm font-black uppercase tracking-widest transition-all hover:bg-blue-600 active:scale-95">
                    <span class="relative z-10 flex items-center justify-center gap-3">
                        <span id="btnText">Simpan Data & Absensi</span>
                        <svg id="loadingIcon" class="hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('formPresensi').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        const text = document.getElementById('btnText');
        const icon = document.getElementById('loadingIcon');
        
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');
        text.innerText = 'Menyimpan...';
        icon.classList.remove('hidden');
    });
</script>
@endsection