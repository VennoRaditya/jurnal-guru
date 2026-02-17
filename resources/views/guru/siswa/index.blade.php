@extends('layouts.app') {{-- Pastikan ini pakai layout guru/user Anda --}}

@section('title', 'Daftar Siswa')

@section('content')
<div class="space-y-6">
    {{-- Header & Search --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Data Murid</h2>
                <p class="text-slate-400 text-xs font-medium">Informasi daftar siswa seluruh kelas.</p>
            </div>
            
            {{-- Form Pencarian Sederhana --}}
            <form action="{{ route('guru.siswa.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau NIS..." 
                    class="bg-slate-50 border-none rounded-2xl px-6 py-3 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none w-full md:w-64">
                <button type="submit" class="bg-slate-800 text-white px-6 rounded-2xl text-xs font-bold hover:bg-slate-900 transition-all">
                    Cari
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel List (Tanpa Form Tambah & Tanpa Tombol Aksi) --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div>
                <h3 class="font-black text-slate-800 uppercase tracking-tighter text-sm">List Database Siswa</h3>
            </div>
            <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-4 py-2 rounded-full uppercase tracking-widest">
                {{ $siswas->total() }} Murid Terdaftar
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">NIS</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kelas / Jurusan</th>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($siswas as $s)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-10 py-5 text-xs font-bold text-slate-500 tracking-wider">{{ $s->nis }}</td>
                        <td class="px-6 py-5 text-sm font-bold text-slate-800">{{ $s->nama }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest">
                                {{ $s->kelas }}
                            </span>
                        </td>
                        <td class="px-10 py-5 text-right">
                            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Aktif</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-20 text-center">
                            <p class="text-slate-400 italic text-xs font-bold uppercase tracking-widest">Tidak ada data ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="p-8 border-t border-slate-50 bg-slate-50/30">
            {{ $siswas->appends(request()->all())->links() }}
        </div>
    </div>
</div>
@endsection