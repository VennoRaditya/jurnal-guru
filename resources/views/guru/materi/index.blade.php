@extends('layouts.app')

@section('title', 'Riwayat Jurnal Pengajaran')
@section('header_title', 'Riwayat Pengajaran')
@section('header_subtitle', 'Kelola dan tinjau kembali jurnal pembelajaran Anda secara bulanan.')

@section('content')
<div class="space-y-6 md:space-y-8 pb-20 px-4 md:px-0">
    
    {{-- Alert Success --}}
    @if(session('success'))
        <div class="animate-in fade-in slide-in-from-top-4 duration-500 bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-5 rounded-4x1 text-[10px] font-black uppercase tracking-widest flex items-center shadow-sm">
            <div class="w-8 h-8 bg-emerald-500 rounded-xl flex items-center justify-center text-white mr-4 shadow-lg shadow-emerald-200 shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] md:rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Table Header & Filter --}}
        <div class="p-6 md:p-12 border-b border-slate-50 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center space-x-5">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-2xl shadow-indigo-100 shrink-0">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase tracking-tighter leading-tight">Log Jurnal Pengajar</h3>
                    <p class="text-[9px] md:text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">Archived Teaching Activities</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                {{-- Form Filter Bulan --}}
                <form action="{{ route('guru.materi.index') }}" method="GET" id="filterForm" class="w-full sm:w-auto">
                    <input type="month" name="bulan_tahun" onchange="this.form.submit()"
                           value="{{ request('bulan_tahun', now()->format('Y-m')) }}"
                           class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3.5 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all outline-none">
                </form>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    {{-- Tombol Cetak PDF --}}
                    <form action="{{ route('guru.absensi.cetakPdf') }}" method="GET" class="flex-1 sm:flex-none">
                        <input type="hidden" name="bulan_tahun" value="{{ request('bulan_tahun', now()->format('Y-m')) }}">
                        <button type="submit" class="w-full bg-rose-50 text-rose-500 px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Cetak
                        </button>
                    </form>

                    <a href="{{ route('guru.presensi.select') }}" class="flex-1 sm:flex-none bg-slate-900 text-white px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all flex items-center justify-center gap-2">
                        <span>+ New</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- DESKTOP VIEW: Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="pl-12 pr-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Schedule & Class</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Material & Activities</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Evaluation</th>
                        <th class="px-6 py-6 text-[10px] font-black text-center text-slate-400 uppercase tracking-[0.2em]">Attendance</th>
                        <th class="pr-12 pl-6 py-6 text-[10px] font-black text-right text-slate-400 uppercase tracking-[0.2em]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($riwayatMateri as $materi)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="pl-12 pr-6 py-8">
                            <p class="text-sm font-black text-slate-700 uppercase tracking-tight">{{ \Carbon\Carbon::parse($materi->tanggal)->translatedFormat('d M Y') }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[9px] font-black bg-indigo-600 text-white px-2.5 py-1 rounded-lg shadow-sm">{{ $materi->kelas }}</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest line-clamp-1">{{ $materi->mata_pelajaran }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-8 max-w-xs">
                            <h4 class="text-sm font-black text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight mb-2 uppercase">{{ $materi->materi_kd }}</h4>
                            <p class="text-[11px] text-slate-500 line-clamp-2 font-medium leading-relaxed italic border-l-2 border-slate-100 pl-3">
                                {{ $materi->kegiatan_pembelajaran }}
                            </p>
                        </td>
                        <td class="px-6 py-8">
                            <p class="text-[11px] text-slate-600 font-bold leading-relaxed line-clamp-2">
                                {{ $materi->evaluasi }}
                            </p>
                        </td>
                        <td class="px-6 py-8">
                            <div class="flex justify-center items-center gap-1.5">
                                <div class="w-8 h-8 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black border border-emerald-100" title="Hadir">
                                    {{ $materi->absensi->where('status', 'hadir')->count() }}
                                </div>
                                <div class="w-8 h-8 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-[10px] font-black border border-amber-100" title="Izin/Sakit">
                                    {{ $materi->absensi->whereIn('status', ['sakit', 'izin'])->count() }}
                                </div>
                                <div class="w-8 h-8 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 text-[10px] font-black border border-rose-100" title="Alfa">
                                    {{ $materi->absensi->where('status', 'alfa')->count() }}
                                </div>
                            </div>
                        </td>
                        <td class="pr-12 pl-6 py-8 text-right">
                            <form action="{{ route('guru.materi.destroy', $materi->id) }}" method="POST" class="delete-form">
                                @csrf @method('DELETE')
                                <button type="button" class="delete-btn w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto md:ml-auto md:mr-0 hover:bg-rose-500 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE VIEW: Stacked Cards --}}
        <div class="md:hidden divide-y divide-slate-50">
            @forelse($riwayatMateri as $materi)
            <div class="p-6 space-y-4 hover:bg-slate-50 transition-colors">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-black text-slate-800 uppercase tracking-tight">{{ \Carbon\Carbon::parse($materi->tanggal)->translatedFormat('d M Y') }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[8px] font-black bg-slate-100 text-slate-500 px-2 py-0.5 rounded uppercase">{{ $materi->kelas }}</span>
                            <span class="text-[8px] font-black text-indigo-600 uppercase tracking-widest line-clamp-1">{{ $materi->mata_pelajaran }}</span>
                        </div>
                    </div>
                    <form action="{{ route('guru.materi.destroy', $materi->id) }}" method="POST" class="delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="delete-btn w-9 h-9 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center active:scale-90 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                </div>

                <div class="space-y-2">
                    <h4 class="text-xs font-black text-slate-700 leading-snug uppercase">{{ $materi->materi_kd }}</h4>
                    <p class="text-[10px] text-slate-400 font-medium leading-relaxed italic line-clamp-2">"{{ $materi->kegiatan_pembelajaran }}"</p>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-50">
                    <div class="flex items-center gap-1.5">
                        <div class="flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[9px] font-black border border-emerald-100">
                            <span>H: {{ $materi->absensi->where('status', 'hadir')->count() }}</span>
                        </div>
                        <div class="flex items-center gap-1 px-2 py-1 rounded-lg bg-amber-50 text-amber-600 text-[9px] font-black border border-amber-100">
                            <span>I/S: {{ $materi->absensi->whereIn('status', ['sakit', 'izin'])->count() }}</span>
                        </div>
                        <div class="flex items-center gap-1 px-2 py-1 rounded-lg bg-rose-50 text-rose-600 text-[9px] font-black border border-rose-100">
                            <span>A: {{ $materi->absensi->where('status', 'alfa')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-20 flex flex-col items-center justify-center text-center px-6">
                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-4 border border-slate-100">
                    <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.246.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum Ada Jurnal</h4>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="px-6 md:px-12 py-8 md:py-10 bg-slate-50/50 border-t border-slate-50">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Showing {{ $riwayatMateri->firstItem() ?? 0 }} to {{ $riwayatMateri->lastItem() ?? 0 }} of {{ $riwayatMateri->total() ?? 0 }} Entries
                </p>
                <div class="pagination-premium">
                    {{ $riwayatMateri->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .pagination-premium nav div:first-child { display: none; }
    .pagination-premium nav div:last-child { @apply flex items-center gap-1 md:gap-2; }
    .pagination-premium span, .pagination-premium a { 
        @apply rounded-xl border-none bg-white text-[9px] md:text-[10px] font-black text-slate-500 px-3 md:px-4 py-2 shadow-sm transition-all; 
    }
    .pagination-premium .active span { @apply bg-indigo-600 text-white shadow-lg shadow-indigo-100; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'HAPUS DATA?',
                    text: "Jurnal & presensi ini akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'HAPUS',
                    cancelButtonText: 'BATAL',
                    customClass: {
                        popup: 'rounded-[2rem] p-6 md:p-10',
                        confirmButton: 'bg-rose-500 rounded-xl px-6 py-3 font-black text-[10px] tracking-widest text-white mx-2',
                        cancelButton: 'bg-slate-500 rounded-xl px-6 py-3 font-black text-[10px] tracking-widest text-white mx-2'
                    },
                    buttonsStyling: false
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });
        });
    });
</script>
@endsection