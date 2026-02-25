@extends('layouts.app')

@section('title', 'Riwayat Jurnal Pengajaran')
@section('header_title', 'Riwayat Pengajaran')
@section('header_subtitle', 'Kelola dan tinjau kembali jurnal pembelajaran Anda.')

@section('content')
<div class="space-y-6 md:space-y-8 pb-20 px-2 md:px-0">
    
    {{-- Alert Success --}}
    @if(session('success'))
        <div class="animate-in fade-in slide-in-from-top-4 duration-500 bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-5 rounded-[2rem] text-[10px] font-black uppercase tracking-widest flex items-center shadow-sm">
            <div class="w-8 h-8 bg-emerald-500 rounded-xl flex items-center justify-center text-white mr-4 shadow-lg shadow-emerald-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Table Header --}}
        <div class="p-8 md:p-12 border-b border-slate-50 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center space-x-5">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-2xl shadow-indigo-200">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Log Jurnal Pengajar</h3>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">Archived Teaching Activities</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Tombol PDF Rekap Mingguan --}}
                <a href="{{ route('guru.rekap.download') }}" class="group bg-rose-500 text-white px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-rose-600 transition-all shadow-xl shadow-rose-100 flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Rekap Mingguan (PDF)
                </a>

                <a href="{{ route('guru.presensi.select') }}" class="group bg-slate-900 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 flex items-center gap-2">
                    <span class="text-lg leading-none">+</span>
                    New Journal
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
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
                                    {{ $materi->presensis->whereIn('status', ['Hadir', 'hadir'])->count() }}
                                </div>
                                <div class="w-8 h-8 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-[10px] font-black border border-amber-100" title="Izin/Sakit">
                                    {{ $materi->presensis->whereIn('status', ['Sakit', 'sakit', 'Izin', 'izin'])->count() }}
                                </div>
                                <div class="w-8 h-8 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 text-[10px] font-black border border-rose-100" title="Alfa">
                                    {{ $materi->presensis->whereIn('status', ['Alfa', 'alfa'])->count() }}
                                </div>
                            </div>
                        </td>
                        <td class="pr-12 pl-6 py-8 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Button Delete --}}
                                <form action="{{ route('guru.materi.destroy', $materi->id) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-btn inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-rose-50 border border-rose-100 text-rose-500 hover:bg-rose-500 hover:text-white hover:shadow-2xl hover:shadow-rose-200 transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-32">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-6 border-2 border-dashed border-slate-200">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.246.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-black text-slate-400 uppercase tracking-[0.3em]">No Journals Yet</h4>
                                <p class="text-xs text-slate-300 mt-2 font-bold max-w-xs leading-relaxed uppercase tracking-widest">Mulai mengajar dan simpan jurnal Anda untuk melihat riwayat di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-12 py-10 bg-slate-50/50 border-t border-slate-50">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Showing {{ $riwayatMateri->firstItem() }} to {{ $riwayatMateri->lastItem() }} of {{ $riwayatMateri->total() }} Entries
                </p>
                <div class="pagination-premium">
                    {{ $riwayatMateri->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Pagination Styling agar Premium */
    .pagination-premium nav div:first-child { display: none; }
    .pagination-premium nav div:last-child { @apply flex items-center gap-2; }
    .pagination-premium span, .pagination-premium a { 
        @apply rounded-xl border-none bg-white text-[10px] font-black text-slate-500 px-4 py-2 shadow-sm transition-all; 
    }
    .pagination-premium .bg-white { @apply bg-indigo-600 text-white shadow-lg shadow-indigo-100; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'HAPUS RIWAYAT?',
                    text: "Data jurnal dan presensi pada sesi ini akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'HAPUS',
                    cancelButtonText: 'BATAL',
                    customClass: {
                        popup: 'rounded-[2rem] p-10',
                        confirmButton: 'bg-rose-500 rounded-xl px-6 py-3 font-black text-[10px] tracking-widest',
                        cancelButton: 'bg-slate-500 rounded-xl px-6 py-3 font-black text-[10px] tracking-widest'
                    }
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
</script>
@endsection