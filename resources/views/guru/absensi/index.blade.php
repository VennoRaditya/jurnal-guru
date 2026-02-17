@extends('layouts.app')

@section('title', 'Input Presensi & Jurnal')
@section('header_title', 'Presensi Kelas ' . $kelas_nama)

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- ALERT: Pesan Error Validasi --}}
    @if($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-100 text-rose-600 px-6 py-4 rounded-3xl text-sm font-bold shadow-sm">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>Periksa kembali inputan Anda:</span>
            </div>
            <ul class="list-disc list-inside ml-7 opacity-80">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ALERT: Pesan Gagal Simpan dari Controller --}}
    @if(session('error'))
        <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-700 px-6 py-4 rounded-3xl text-sm font-bold shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('guru.presensi.storeJurnal') }}" method="POST">
        @csrf
        {{-- Hidden fields --}}
        <input type="hidden" name="kelas" value="{{ $kelas_nama }}">

        <div class="space-y-8">
            {{-- BAGIAN 1: INPUT JURNAL MATERI --}}
            <div class="bg-white rounded-[40px] border border-slate-100 shadow-sm p-8 md:p-10 transition-all hover:shadow-md">
                <div class="flex items-center space-x-4 mb-8">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Jurnal Pembelajaran</h3>
                        <p class="text-xs text-slate-400 font-medium">Informasikan materi yang Anda ajarkan hari ini.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Mata Pelajaran</label>
                        <input type="text" name="mata_pelajaran" required value="{{ old('mata_pelajaran') }}" placeholder="Contoh: Pemrograman Web"
                            class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:text-slate-300">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Judul Materi</label>
                        <input type="text" name="judul_materi" required value="{{ old('judul_materi') }}" placeholder="Contoh: Struktur Data Stack & Queue"
                            class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:text-slate-300">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Pembahasan / Ringkasan</label>
                    <textarea name="pembahasan" required rows="4" placeholder="Jelaskan poin-poin penting yang dibahas..."
                        class="w-full bg-slate-50 border-none rounded-3xl px-6 py-4 text-sm font-medium text-slate-600 focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:text-slate-300">{{ old('pembahasan') }}</textarea>
                </div>
            </div>

            {{-- BAGIAN 2: DAFTAR ABSENSI SISWA --}}
            <div class="bg-white rounded-[40px] border border-slate-100 shadow-sm overflow-hidden transition-all hover:shadow-md">
                <div class="p-8 md:p-10 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Presensi Siswa</h3>
                            <p class="text-xs text-slate-400 font-medium italic">Status default "Hadir". Ubah jika berhalangan.</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-4 py-2 rounded-full uppercase tracking-widest">
                        Total: {{ count($siswas) }} Siswa
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Siswa</th>
                                <th class="px-6 py-6 text-[10px] font-black text-center text-slate-400 uppercase tracking-[0.2em]">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($siswas as $siswa)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-10 py-6">
                                    <p class="text-sm font-bold text-slate-700">{{ $siswa->nama }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">NIS: {{ $siswa->nis }}</p>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center justify-center space-x-3 md:space-x-6">
                                        @foreach(['hadir', 'sakit', 'izin', 'alfa'] as $status)
                                        <label class="group flex flex-col items-center cursor-pointer">
                                            <input type="radio" name="absen[{{ $siswa->id }}]" value="{{ $status }}" 
                                                {{ $status == 'hadir' ? 'checked' : '' }} class="hidden peer">
                                            
                                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl flex items-center justify-center border-2 border-transparent transition-all mb-1
                                                {{ $status == 'hadir' ? 'bg-emerald-50 text-emerald-400 peer-checked:bg-emerald-500 peer-checked:text-white shadow-emerald-100' : '' }}
                                                {{ $status == 'sakit' ? 'bg-amber-50 text-amber-400 peer-checked:bg-amber-500 peer-checked:text-white shadow-amber-100' : '' }}
                                                {{ $status == 'izin' ? 'bg-blue-50 text-blue-400 peer-checked:bg-blue-600 peer-checked:text-white shadow-blue-100' : '' }}
                                                {{ $status == 'alfa' ? 'bg-rose-50 text-rose-400 peer-checked:bg-rose-500 peer-checked:text-white shadow-rose-100' : '' }}
                                                peer-checked:shadow-lg peer-checked:scale-110
                                            ">
                                                <span class="text-[10px] font-black uppercase">{{ substr($status, 0, 1) }}</span>
                                            </div>
                                            <span class="text-[8px] font-black uppercase text-slate-300 group-hover:text-slate-500 transition-colors">{{ $status }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-10 bg-slate-50/50 flex flex-col md:flex-row items-center justify-end gap-4 border-t border-slate-50">
                    <button type="submit" class="w-full md:w-auto bg-slate-900 text-white px-12 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-600 hover:scale-105 active:scale-95 transition-all shadow-xl shadow-slate-200">
                        Simpan Jurnal & Presensi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection