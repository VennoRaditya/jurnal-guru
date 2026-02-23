@extends('layouts.app')

@section('title', 'Riwayat Materi')
@section('header_title', 'Riwayat Pengajaran')
@section('header_subtitle', 'Kelola dan tinjau kembali jurnal pembelajaran Anda.')

@section('content')
<div class="space-y-6 md:space-y-8 pb-20 px-2 md:px-0">
    
    {{-- Alert Success --}}
    @if(session('success'))
        <div class="animate-in fade-in slide-in-from-top-4 duration-500 bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-5 rounded-4x1 text-[10px] font-black uppercase tracking-widest flex items-center shadow-sm">
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
                <div class="bg-slate-50 px-5 py-3 rounded-2xl border border-slate-100">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Entry</p>
                    <p class="text-sm font-black text-slate-700 leading-none">{{ $riwayatMateri->total() }} <span class="text-[10px] text-slate-400">Sessions</span></p>
                </div>
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
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Material Detail</th>
                        <th class="px-6 py-6 text-[10px] font-black text-center text-slate-400 uppercase tracking-[0.2em]">Attendance Stats</th>
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
                            <h4 class="text-sm font-black text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight mb-2">{{ $materi->judul_materi }}</h4>
                            <p class="text-[11px] text-slate-500 line-clamp-2 font-medium leading-relaxed italic border-l-2 border-slate-100 pl-3">
                                {{ $materi->pembahasan }}
                            </p>
                        </td>
                        <td class="px-6 py-8">
                            <div class="flex justify-center items-center gap-2">
                                {{-- Hadir --}}
                                <div class="flex flex-col items-center gap-1" title="Hadir">
                                    <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 text-[11px] font-black border border-emerald-100 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                                        {{ $materi->presensis->whereIn('status', ['Hadir', 'hadir'])->count() }}
                                    </div>
                                    <span class="text-[7px] font-black text-slate-300 uppercase tracking-tighter">Pres</span>
                                </div>
                                {{-- Izin/Sakit --}}
                                <div class="flex flex-col items-center gap-1" title="Izin/Sakit">
                                    <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-[11px] font-black border border-amber-100 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                        {{ $materi->presensis->whereIn('status', ['Sakit', 'sakit', 'Izin', 'izin'])->count() }}
                                    </div>
                                    <span class="text-[7px] font-black text-slate-300 uppercase tracking-tighter">Exc</span>
                                </div>
                                {{-- Alfa --}}
                                <div class="flex flex-col items-center gap-1" title="Alfa">
                                    <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 text-[11px] font-black border border-rose-100 group-hover:bg-rose-500 group-hover:text-white transition-all">
                                        {{ $materi->presensis->whereIn('status', ['Alfa', 'alfa'])->count() }}
                                    </div>
                                    <span class="text-[7px] font-black text-slate-300 uppercase tracking-tighter">Abs</span>
                                </div>
                            </div>
                        </td>
                        <td class="pr-12 pl-6 py-8 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Button View --}}
                                <a href="#" class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-white border border-slate-100 text-slate-400 hover:bg-slate-900 hover:text-white hover:shadow-2xl hover:shadow-slate-300 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

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
                        <td colspan="4" class="py-32">
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
                    Showing page {{ $riwayatMateri->currentPage() }} of {{ $riwayatMateri->lastPage() }}
                </p>
                <div class="pagination-premium">
                    {{ $riwayatMateri->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Pagination Styling */
    .pagination-premium .pagination { @apply flex gap-2; }
    .pagination-premium .page-item .page-link { @apply rounded-xl border-none bg-slate-100 text-[10px] font-black text-slate-500 px-4 py-2 hover:bg-indigo-600 hover:text-white transition-all; }
    .pagination-premium .page-item.active .page-link { @apply bg-indigo-600 text-white shadow-lg shadow-indigo-200; }
    
    /* SweetAlert Premium Customization */
    .swal2-popup.premium-swal {
        border-radius: 2rem !important;
        padding: 3rem !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .swal2-title.premium-title {
        font-weight: 900 !important;
        letter-spacing: -0.05em !important;
        text-transform: uppercase !important;
        color: #1e293b !important;
    }
    .swal2-confirm.premium-confirm {
        border-radius: 1rem !important;
        font-size: 10px !important;
        font-weight: 900 !important;
        letter-spacing: 0.2em !important;
        padding: 1rem 2rem !important;
        background-color: #f43f5e !important; /* rose-500 */
    }
    .swal2-cancel.premium-cancel {
        border-radius: 1rem !important;
        font-size: 10px !important;
        font-weight: 900 !important;
        letter-spacing: 0.2em !important;
        padding: 1rem 2rem !important;
        background-color: #64748b !important; /* slate-500 */
    }
</style>

{{-- Script for Delete Confirmation --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.delete-form');
                
                Swal.fire({
                    title: 'HAPUS RIWAYAT?',
                    text: "Seluruh data jurnal dan presensi siswa pada sesi ini akan dihapus secara permanen dari server.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'YA, HAPUS PERMANEN',
                    cancelButtonText: 'BATALKAN',
                    reverseButtons: true,
                    customClass: {
                        popup: 'premium-swal',
                        title: 'premium-title',
                        confirmButton: 'premium-confirm',
                        cancelButton: 'premium-cancel'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection