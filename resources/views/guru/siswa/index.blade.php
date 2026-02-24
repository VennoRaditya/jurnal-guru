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
            
            <form action="{{ route('admin.siswa.store') }}" method="POST" id="formSiswa" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div class="space-y-1">
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Nomor Induk Siswa (NIS)" required 
                        class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                
                <div class="space-y-1">
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap" required 
                        class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                
                <select id="select_tingkat" name="tingkat_temp" required 
                    class="bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
                    <option value="">Pilih Tingkat</option>
                    <option value="X">Kelas 10</option>
                    <option value="XI">Kelas 11</option>
                    <option value="XII">Kelas 12</option>
                </select>

                <select id="select_jurusan" name="jurusan_temp" required 
                    class="bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
                    <option value="">Pilih Jurusan</option>
                    @foreach(['RPL', 'TKJ', 'AKL', 'MANLOG', 'BR'] as $j)
                        <option value="{{ $j }}">{{ $j }}</option>
                    @endforeach
                </select>

                <button type="submit" class="md:col-span-2 bg-blue-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                    Simpan Data Secara Manual
                </button>
            </form>
        </div>

        {{-- 2. FORM IMPORT EXCEL (KANAN) --}}
        <div class="bg-slate-900 rounded-[40px] p-8 text-white relative overflow-hidden group border border-slate-800 shadow-2xl">
            {{-- Efek Glow --}}
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-600 rounded-full blur-[80px] opacity-20 group-hover:opacity-40 transition-opacity"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <h3 class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-4">Input Massal (Excel)</h3>
                <p class="text-xs text-slate-400 leading-relaxed font-medium mb-6">
                    Unggah ratusan data murid sekaligus menggunakan file Excel/CSV.
                </p>

                {{-- Link Download Template --}}
                <a href="{{ route('admin.siswa.template') }}" class="inline-flex items-center gap-3 text-slate-300 hover:text-white transition-colors mb-8 group/link">
                    <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center group-hover/link:bg-blue-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest">Unduh Format</p>
                        <p class="text-[10px] text-slate-500 font-bold group-hover/link:text-blue-400 transition-colors">template_siswa.xlsx</p>
                    </div>
                </a>

                <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="mt-auto space-y-4">
                    @csrf
                    <div class="relative group/input">
                        <input type="file" name="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                        <div class="bg-white/5 border-2 border-dashed border-slate-700 rounded-2xl p-6 text-center group-hover/input:border-blue-500 transition-colors">
                            <svg class="w-6 h-6 text-slate-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Pilih File Excel</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-white text-slate-900 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-50 transition-all active:scale-95">
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
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total: {{ $siswas->total() }} Murid Terdaftar</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                <select id="filter_tingkat" class="bg-transparent border-none text-[10px] font-black uppercase tracking-widest outline-none focus:ring-0 cursor-pointer">
                    <option value="">Semua Kelas</option>
                    <option value="X" {{ request('tingkat') == 'X' ? 'selected' : '' }}>X (10)</option>
                    <option value="XI" {{ request('tingkat') == 'XI' ? 'selected' : '' }}>XI (11)</option>
                    <option value="XII" {{ request('tingkat') == 'XII' ? 'selected' : '' }}>XII (12)</option>
                </select>
                <div class="w-px h-4 bg-slate-200"></div>
                <select id="filter_jurusan" class="bg-transparent border-none text-[10px] font-black uppercase tracking-widest outline-none focus:ring-0 cursor-pointer">
                    <option value="">Semua Jurusan</option>
                    @foreach(['RPL', 'TKJ', 'AKL', 'MANLOG', 'BR'] as $j)
                        <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
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
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kelas</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($siswas as $s)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-10 py-5 text-xs font-bold text-slate-400 group-hover:text-blue-600 transition-colors">{{ $s->nis }}</td>
                        <td class="px-6 py-5 text-sm font-bold text-slate-800">{{ $s->nama }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-slate-100 text-slate-600 group-hover:bg-blue-600 group-hover:text-white px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                {{ $s->kelas }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus murid ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center ml-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[30px] flex items-center justify-center mb-4 text-slate-200">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <p class="text-slate-400 italic text-xs uppercase tracking-[0.2em] font-black">Data tidak ditemukan</p>
                            </div>
                        </td>
                    </tr>
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
        const filterTingkat = document.getElementById('filter_tingkat');
        const filterJurusan = document.getElementById('filter_jurusan');

        function applyFilter() {
            const t = filterTingkat.value;
            const j = filterJurusan.value;
            
            const url = new URL(window.location.href);
            if(t) url.searchParams.set('tingkat', t); else url.searchParams.delete('tingkat');
            if(j) url.searchParams.set('jurusan', j); else url.searchParams.delete('jurusan');
            url.searchParams.delete('page');
            window.location.href = url.href;
        }

        filterTingkat.addEventListener('change', applyFilter);
        filterJurusan.addEventListener('change', applyFilter);
    });
</script>
@endsection