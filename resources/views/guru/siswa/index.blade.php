@extends('layouts.admin')

@section('title', 'Data Siswa')
@section('header_title', 'Manajemen Murid')

@section('content')
<div class="space-y-8">
    {{-- BARIS ATAS: INPUT MANUAL & IMPORT EXCEL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- 1. FORM INPUT MANUAL (KIRI) --}}
        <div class="lg:col-span-2 bg-white rounded-[40px] border border-slate-100 shadow-sm p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1.5 h-4 bg-blue-600 rounded-full"></div>
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Registrasi Murid Baru</h3>
            </div>
            
            <form action="{{ route('guru.siswa.store') }}" method="POST" id="formSiswa" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div class="space-y-1">
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Nomor Induk Siswa (NIS)" required 
                        class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                
                <div class="space-y-1">
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap" required 
                        class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <div class="space-y-1">
                    <select name="jk" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
                        <option value="">Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                {{-- PILIH KELAS BERDASARKAN KELAS AMPUAN GURU --}}
                <div class="space-y-1">
                    <select name="kelas" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
                        <option value="">Pilih Kelas Ampuan</option>
                        @foreach($kelasDiampu as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="md:col-span-2 bg-blue-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                    Simpan Data Secara Manual
                </button>
            </form>
        </div>

        {{-- 2. FORM IMPORT EXCEL (KANAN) --}}
        <div class="bg-slate-900 rounded-[40px] p-8 text-white relative overflow-hidden group border border-slate-800 shadow-2xl">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-600 rounded-full blur-[80px] opacity-20 group-hover:opacity-40 transition-opacity"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <h3 class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-4">Input Massal (Excel)</h3>
                
                <form action="{{ route('guru.siswa.import') }}" method="POST" enctype="multipart/form-data" class="h-full flex flex-col">
                    @csrf
                    {{-- Dropdown Kelas untuk Import --}}
                    <select name="kelas" required class="w-full bg-white/10 border-none rounded-2xl px-6 py-4 text-xs font-bold text-white mb-4 outline-none">
                        <option value="" class="text-slate-900">Import Ke Kelas Mana?</option>
                        @foreach($kelasDiampu as $k)
                            <option value="{{ $k }}" class="text-slate-900">{{ $k }}</option>
                        @endforeach
                    </select>

                    <div class="relative group/input mb-4">
                        <input type="file" name="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                        <div class="bg-white/5 border-2 border-dashed border-slate-700 rounded-2xl p-6 text-center group-hover/input:border-blue-500 transition-colors">
                            <svg class="w-6 h-6 text-slate-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Pilih File Excel</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-white text-slate-900 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-50 transition-all active:scale-95 mt-auto">
                        Import Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- TABEL LIST DENGAN FILTER --}}
    <div class="bg-white rounded-[40px] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 uppercase tracking-tighter text-lg">Basis Data Siswa</h3>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kelas Ampuan Anda</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                <select id="filter_kelas" class="bg-transparent border-none text-[10px] font-black uppercase tracking-widest outline-none focus:ring-0 cursor-pointer">
                    <option value="">Semua Kelas Ampuan</option>
                    @foreach($kelasDiampu as $k)
                        <option value="{{ $k }}" {{ request('kelas_filter') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">NIS</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Murid</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">JK</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kelas</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($siswas as $s)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-10 py-5 text-xs font-bold text-slate-400 group-hover:text-blue-600 transition-colors">{{ $s->nis }}</td>
                        <td class="px-6 py-5 text-sm font-bold text-slate-800">{{ $s->nama }}</td>
                        <td class="px-6 py-5 text-center text-xs font-bold text-slate-500">{{ $s->jk }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-slate-100 text-slate-600 group-hover:bg-blue-600 group-hover:text-white px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                {{ $s->kelas }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <form action="{{ route('guru.siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus murid ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center ml-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    {{-- Kosong --}}
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-8 border-t border-slate-50 bg-slate-50/30">
            {{ $siswas->appends(request()->all())->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterKelas = document.getElementById('filter_kelas');

        filterKelas.addEventListener('change', function() {
            const val = this.value;
            const url = new URL(window.location.href);
            if(val) url.searchParams.set('kelas_filter', val); else url.searchParams.delete('kelas_filter');
            url.searchParams.delete('page');
            window.location.href = url.href;
        });
    });
</script>
@endsection