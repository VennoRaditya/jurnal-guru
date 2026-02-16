@extends('layouts.app')

@section('title', 'Riwayat Materi')
@section('header_title', 'Riwayat Pengajaran')
@section('header_subtitle', 'Daftar materi yang telah disampaikan di kelas.')

@section('content')
<div class="space-y-8">
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-10 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center text-white font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.246.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Jurnal Mengajar Guru</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5 font-medium italic">Menampilkan riwayat materi per pertemuan.</p>
                </div>
            </div>
            <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-3 py-1 rounded-full uppercase tracking-widest">
                Total: {{ $riwayatMateri->total() }} Pertemuan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu & Kelas</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Materi & Pembahasan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-center text-slate-400 uppercase tracking-widest">Statistik Absensi</th>
                        <th class="px-6 py-5 text-[10px] font-black text-right text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($riwayatMateri as $materi)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-10 py-5">
                            <p class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($materi->tanggal)->format('d M Y') }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[9px] font-bold bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md uppercase">{{ $materi->kelas }}</span>
                                <span class="text-[9px] font-medium text-slate-400">{{ $materi->jam_mulai }} - {{ $materi->jam_selesai }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm font-bold text-slate-800 mb-1">{{ $materi->judul_materi }}</p>
                            <p class="text-xs text-slate-500 line-clamp-1 font-medium">{{ $materi->deskripsi_materi ?? 'Tidak ada deskripsi tambahan.' }}</p>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex justify-center gap-1">
                                {{-- Kita asumsikan ada relasi ke tabel presensi untuk menghitung jumlah --}}
                                <span title="Hadir" class="w-6 h-6 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 text-[9px] font-bold">{{ $materi->presensis->where('status', 'Hadir')->count() }}</span>
                                <span title="Sakit/Izin" class="w-6 h-6 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 text-[9px] font-bold">{{ $materi->presensis->whereIn('status', ['Sakit', 'Izin'])->count() }}</span>
                                <span title="Alfa" class="w-6 h-6 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 text-[9px] font-bold">{{ $materi->presensis->where('status', 'Alfa')->count() }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.246.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest italic">Belum ada riwayat jurnal mengajar.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-10 bg-slate-50/50 border-t border-slate-100">
            {{ $riwayatMateri->links() }}
        </div>
    </div>
</div>
@endsection