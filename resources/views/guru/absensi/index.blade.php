@extends('layouts.app')

@section('title', 'Input Presensi & Jurnal')

@section('header_title', 'Kelas ' . ($kelas_nama ?? 'Tidak Terdeteksi'))
@section('header_subtitle', 'Silahkan lengkapi laporan pembelajaran dan absensi hari ini.')

@section('content')
<div class="max-w-5xl mx-auto pb-20">
    {{-- ALERT: Notifikasi Error atau Sukses --}}
    @if($errors->any() || session('error'))
        <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-rose-50 border border-rose-100 rounded-4xl p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-rose-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-rose-700 uppercase tracking-widest mb-1">Terjadi Kesalahan</h4>
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

    <form action="{{ route('guru.presensi.storeJurnal') }}" method="POST" class="space-y-10">
        @csrf
        {{-- Hidden input agar ID Kelas/Nama Kelas terkirim ke Controller --}}
        <input type="hidden" name="kelas" value="{{ $kelas_nama }}">

        {{-- BAGIAN 1: JURNAL PEMBELAJARAN --}}
        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] p-8 md:p-12 relative overflow-hidden group">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-50 group-hover:opacity-80 transition-opacity"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-14 h-14 bg-blue-600 rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-blue-200 transition-transform group-hover:rotate-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Jurnal Pembelajaran</h3>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Laporan Aktivitas Akademik</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Mata Pelajaran --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Mata Pelajaran</label>
                        <input type="text" name="mata_pelajaran" required value="{{ old('mata_pelajaran', Auth::guard('guru')->user()->mapel ?? '') }}" 
                            class="w-full bg-slate-50/50 border-2 border-transparent rounded-3xl px-7 py-5 text-sm font-black text-slate-700 focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none">
                    </div>

                    {{-- Kompetensi Dasar / Materi --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Materi / Kompetensi Dasar</label>
                        <input type="text" name="materi_kd" required value="{{ old('materi_kd') }}" placeholder="Misal: KD 3.2 Pemrograman Web"
                            class="w-full bg-slate-50/50 border-2 border-transparent rounded-3xl px-7 py-5 text-sm font-black text-slate-700 focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none">
                    </div>

                    {{-- Kegiatan Pembelajaran --}}
                    <div class="md:col-span-2 space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Kegiatan Pembelajaran</label>
                        <textarea name="kegiatan_pembelajaran" required rows="3" placeholder="Gambarkan aktivitas belajar mengajar hari ini..."
                            class="w-full bg-slate-50/50 border-2 border-transparent rounded-[2.5rem] px-7 py-5 text-sm font-bold text-slate-600 focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none min-h-[120px]">{{ old('kegiatan_pembelajaran') }}</textarea>
                    </div>

                    {{-- Penilaian / Evaluasi --}}
                    <div class="md:col-span-2 space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Penilaian / Evaluasi</label>
                        <textarea name="evaluasi" required rows="3" placeholder="Catatan hasil penilaian atau respon siswa..."
                            class="w-full bg-slate-50/50 border-2 border-transparent rounded-[2.5rem] px-7 py-5 text-sm font-bold text-slate-600 focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none min-h-[120px]">{{ old('evaluasi') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: PRESENSI SISWA --}}
        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden transition-all">
            <div class="p-8 md:p-12 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-emerald-500 rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-emerald-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Presensi Siswa</h3>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">
                            Data Kehadiran: {{ count($siswas) }} Siswa Terdaftar
                        </p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="pl-12 pr-6 py-7 text-[10px] font-black text-slate-400 uppercase tracking-[0.25em]">Informasi Siswa</th>
                            <th class="px-6 py-7 text-[10px] font-black text-center text-slate-400 uppercase tracking-[0.25em]">Opsi Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($siswas as $siswa)
                        <tr class="hover:bg-blue-50/30 transition-all group">
                            <td class="pl-12 pr-6 py-7">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all uppercase">
                                        {{ substr($siswa->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-700 group-hover:text-blue-600 transition-colors uppercase tracking-tight">{{ $siswa->nama }}</p>
                                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-0.5">NIS: {{ $siswa->nis }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-7">
                                <div class="flex items-center justify-center space-x-2 md:space-x-4">
                                    @foreach(['hadir' => 'bg-emerald-500', 'sakit' => 'bg-amber-500', 'izin' => 'bg-blue-500', 'alfa' => 'bg-rose-500'] as $status => $color)
                                    <label class="relative flex flex-col items-center cursor-pointer group/item">
                                        <input type="radio" name="absen[{{ $siswa->id }}]" value="{{ $status }}" 
                                            {{ $status == 'hadir' ? 'checked' : '' }} class="hidden peer">
                                        
                                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-2xl flex items-center justify-center border-2 border-slate-100 text-slate-300 transition-all duration-300 peer-checked:border-transparent peer-checked:{{ $color }} peer-checked:text-white peer-checked:shadow-lg peer-checked:scale-110">
                                            <span class="text-[10px] font-black uppercase">{{ substr($status, 0, 1) }}</span>
                                        </div>
                                        <span class="text-[7px] font-black uppercase text-slate-300 mt-2 tracking-widest opacity-0 group-hover/item:opacity-100 peer-checked:opacity-100 peer-checked:text-slate-500 transition-all">{{ $status }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="py-32 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">
                                Tidak ada data siswa di kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-8 md:p-12 bg-slate-50/50 flex justify-end border-t border-slate-50">
                <button type="submit" @if($siswas->isEmpty()) disabled @endif 
                    class="group relative w-full md:w-auto overflow-hidden {{ $siswas->isEmpty() ? 'bg-slate-300' : 'bg-slate-900' }} text-white px-14 py-5 rounded-3xl text-[11px] font-black uppercase tracking-[0.2em] transition-all hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/30 active:scale-95">
                    <span class="relative z-10">Simpan Laporan & Absensi</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection