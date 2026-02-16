@extends('layouts.app')

@section('title', 'Input Jurnal & Presensi')
@section('header_title', 'Kelas ' . $kelas_nama)
@section('header_subtitle', 'Silakan isi materi pembelajaran dan kehadiran siswa.')

@section('content')
<form action="{{ route('guru.jurnal.store') }}" method="POST" class="space-y-8">
    @csrf
    <input type="hidden" name="kelas" value="{{ $kelas_nama }}">

    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
        <div class="flex items-center space-x-4 mb-8">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold">1</div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Ringkasan Materi</h3>
        </div>
        
        <div class="space-y-6">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Judul Materi</label>
                <input type="text" name="judul_materi" required
                       class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" 
                       placeholder="Misal: Dasar-dasar Routing Laravel">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Ringkasan Pembahasan</label>
                <textarea name="pembahasan" rows="4" required
                          class="w-full bg-slate-50 border-none rounded-3xl p-6 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none resize-none" 
                          placeholder="Tulis poin-poin penting hari ini..."></textarea>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-10 border-b border-slate-50 flex items-center space-x-4">
            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white font-bold">2</div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Daftar Hadir Siswa</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Siswa</th>
                        <th class="px-6 py-5 text-[10px] font-black text-center text-emerald-500 uppercase tracking-widest">Hadir</th>
                        <th class="px-6 py-5 text-[10px] font-black text-center text-blue-500 uppercase tracking-widest">Izin</th>
                        <th class="px-6 py-5 text-[10px] font-black text-center text-rose-500 uppercase tracking-widest">Alfa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($siswas as $siswa)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-10 py-5">
                            <p class="text-sm font-bold text-slate-700">{{ $siswa->nama }}</p>
                            <p class="text-[10px] text-slate-400 font-medium">NIS: {{ $siswa->nis }}</p>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <input type="radio" name="absen[{{ $siswa->id }}]" value="hadir" required class="w-5 h-5 accent-emerald-500">
                        </td>
                        <td class="px-6 py-5 text-center">
                            <input type="radio" name="absen[{{ $siswa->id }}]" value="izin" class="w-5 h-5 accent-blue-500">
                        </td>
                        <td class="px-6 py-5 text-center">
                            <input type="radio" name="absen[{{ $siswa->id }}]" value="alfa" class="w-5 h-5 accent-rose-500">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-10 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button type="submit" class="bg-slate-900 text-white px-12 py-4 rounded-2xl text-xs font-bold uppercase tracking-[0.2em] hover:bg-blue-600 transition-all shadow-xl">
                Simpan Jurnal & Absensi
            </button>
        </div>
    </div>
</form>
@endsection