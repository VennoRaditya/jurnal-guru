@extends('layouts.admin')

@section('title', 'Kelola Guru')
@section('header_title', 'Data Tenaga Pengajar')
@section('header_subtitle', 'Manajemen akun dan data mata pelajaran guru.')

@section('content')
<div class="space-y-8">
    {{-- Form Tambah Guru --}}
    <div class="bg-white rounded-[40px] border border-slate-100 shadow-sm p-8">
        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Registrasi Guru Baru</h3>
        <form action="{{ route('admin.guru.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf
            <input type="text" name="nip" placeholder="NIP" required class="bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
            <input type="text" name="nama" placeholder="Nama Lengkap" required class="bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
            <input type="text" name="mapel" placeholder="Mata Pelajaran" required class="bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
            <button type="submit" class="bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                Simpan Guru
            </button>
        </form>
    </div>

    {{-- Tabel List Guru --}}
    <div class="bg-white rounded-[40px] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">NIP</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Mata Pelajaran</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($gurus as $g)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-10 py-5 text-xs font-bold text-slate-500 tracking-wider">{{ $g->nip }}</td>
                        <td class="px-6 py-5 text-sm font-bold text-slate-800">{{ $g->nama }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest">{{ $g->mapel }}</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus data guru ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-600 transition-all hover:scale-110">
                                    <svg class="w-5 h-5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-20 text-center text-slate-400 italic text-xs">Belum ada data tenaga pengajar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-8 border-t border-slate-50 bg-slate-50/30">
            {{ $gurus->links() }}
        </div>
    </div>
</div>
@endsection