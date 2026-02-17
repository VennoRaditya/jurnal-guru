@extends('layouts.app')

@section('title', 'Riwayat Materi')
@section('header_title', 'Riwayat Pengajaran')
@section('header_subtitle', 'Daftar materi yang telah disampaikan di kelas.')

@section('content')
<div class="space-y-8">
    {{-- Alert Success --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-[2rem] text-sm font-bold flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-10 border-b border-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.246.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Jurnal Mengajar Guru</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5 font-medium italic">Data tersimpan secara otomatis di sistem cloud.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black bg-slate-100 text-slate-500 px-4 py-2 rounded-xl uppercase tracking-widest">
                    Total: {{ $riwayatMateri->total() }} Sesi
                </span>
                <a href="{{ route('guru.presensi.select') }}" class="bg-slate-900 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-md">
                    + Jurnal Baru
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu & Kelas</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Materi & Pembahasan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-center text-slate-400 uppercase tracking-widest">Statistik Absensi</th>
                        <th class="px-6 py-5 text-[10px] font-black text-right text-slate-400 uppercase tracking-widest">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($riwayatMateri as $materi)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-10 py-6">
                            <p class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($materi->tanggal)->translatedFormat('d M Y') }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[9px] font-bold bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md uppercase tracking-tighter">{{ $materi->kelas }}</span>
                                <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest">{{ $materi->mata_pelajaran }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-6 max-w-xs md:max-w-md">
                            <p class="text-sm font-bold text-slate-800 mb-1 leading-tight">{{ $materi->judul_materi }}</p>
                            <p class="text-[11px] text-slate-400 line-clamp-2 font-medium leading-relaxed italic">
                                "{{ $materi->pembahasan }}"
                            </p>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex justify-center gap-1.5">
                                {{-- Gunakan strtolower untuk memastikan pengecekan status aman --}}
                                <div title="Hadir" class="group/stat flex flex-col items-center">
                                    <span class="w-8 h-8 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black border border-emerald-100 group-hover/stat:bg-emerald-500 group-hover/stat:text-white transition-all shadow-sm">
                                        {{ $materi->presensis->whereIn('status', ['Hadir', 'hadir'])->count() }}
                                    </span>
                                </div>
                                <div title="Izin/Sakit" class="group/stat flex flex-col items-center">
                                    <span class="w-8 h-8 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-[10px] font-black border border-amber-100 group-hover/stat:bg-amber-500 group-hover/stat:text-white transition-all shadow-sm">
                                        {{ $materi->presensis->whereIn('status', ['Sakit', 'sakit', 'Izin', 'izin'])->count() }}
                                    </span>
                                </div>
                                <div title="Alfa" class="group/stat flex flex-col items-center">
                                    <span class="w-8 h-8 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 text-[10px] font-black border border-rose-100 group-hover/stat:bg-rose-500 group-hover/stat:text-white transition-all shadow-sm">
                                        {{ $materi->presensis->whereIn('status', ['Alfa', 'alfa'])->count() }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-900 text-white hover:bg-blue-600 transition-all shadow-lg shadow-slate-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-4 border border-dashed border-slate-200">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.246.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Arsip Masih Kosong</h4>
                                <p class="text-[10px] text-slate-300 mt-1 font-medium italic">Anda belum memiliki riwayat jurnal pengajaran.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-10 py-8 bg-slate-50/30 border-t border-slate-100">
            <div class="flex justify-center">
                {{ $riwayatMateri->links() }}
            </div>
        </div>
    </div>
</div>
@endsection