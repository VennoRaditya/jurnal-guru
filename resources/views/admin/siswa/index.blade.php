@extends('layouts.admin')

@section('title', 'Data Murid')
@section('header_title', 'Manajemen Murid')
@section('header_subtitle', 'Kelola data murid berdasarkan tingkatan dan jurusan.')

@section('content')
<div class="space-y-8 pb-20 px-2 md:px-0">
    
    {{-- Top Action Bar: Filter & Register --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- Card Registrasi (Left/Full) --}}
        <div class="lg:col-span-8 bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Registrasi Murid Baru</h3>
            </div>

            <form action="{{ route('admin.siswa.store') }}" method="POST" id="formSiswa" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @csrf
                <div class="md:col-span-1">
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="NIS" required 
                        class="w-full bg-slate-50 border-2 border-transparent rounded-2xl px-5 py-4 text-xs font-bold focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none">
                </div>
                
                <div class="md:col-span-2">
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap Murid" required 
                        class="w-full bg-slate-50 border-2 border-transparent rounded-2xl px-5 py-4 text-xs font-bold focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none">
                </div>

                <div class="hidden">
                    {{-- Hidden inputs untuk menjaga konteks tingkat & jurusan saat simpan --}}
                    <input type="hidden" name="tingkat_temp" value="{{ request('tingkat') }}">
                    <input type="hidden" name="jurusan_temp" value="{{ request('jurusan') }}">
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-xl shadow-slate-200 active:scale-95">
                        Daftarkan Ke Kelas Ini
                    </button>
                </div>
            </form>
        </div>

        {{-- Quick Filter Card (Right) --}}
        <div class="lg:col-span-4 bg-indigo-600 rounded-[3rem] p-8 shadow-xl shadow-indigo-100 relative overflow-hidden text-white">
            <div class="relative z-10">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] mb-4 opacity-80">Filter Tingkatan</h3>
                <div class="grid grid-cols-2 gap-3">
                    <select id="select_tingkat" class="bg-indigo-700/50 border-none rounded-2xl px-4 py-4 text-xs font-bold text-white outline-none focus:ring-2 focus:ring-white/20">
                        <option value="">Pilih Tingkat</option>
                        <option value="X" {{ request('tingkat') == 'X' ? 'selected' : '' }}>Kelas 10</option>
                        <option value="XI" {{ request('tingkat') == 'XI' ? 'selected' : '' }}>Kelas 11</option>
                        <option value="XII" {{ request('tingkat') == 'XII' ? 'selected' : '' }}>Kelas 12</option>
                    </select>

                    <select id="select_jurusan" class="bg-indigo-700/50 border-none rounded-2xl px-4 py-4 text-xs font-bold text-white outline-none focus:ring-2 focus:ring-white/20">
                        <option value="">Jurusan</option>
                        @foreach(['RPL', 'TKJ', 'AKL', 'MANLOG', 'BR'] as $j)
                            <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
        </div>
    </div>

    {{-- Tabel List --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="p-10 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-1.5 h-10 bg-blue-600 rounded-full shadow-lg shadow-blue-100"></div>
                <div>
                    <h3 class="font-black text-slate-800 uppercase tracking-tighter text-lg">
                        @if(request('tingkat') && request('jurusan'))
                            Database <span class="text-blue-600">{{ request('tingkat') }} {{ request('jurusan') }}</span>
                        @else
                            Semua Data Murid
                        @endif
                    </h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Menampilkan data validasi akademik</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="px-5 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        Total: {{ $siswas->total() }} Murid
                    </span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="pl-12 pr-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Identitas</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Murid</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Kelas</th>
                        <th class="pr-12 pl-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($siswas as $s)
                    <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                        <td class="pl-12 pr-6 py-6 text-xs font-mono font-black text-slate-400">
                            #{{ $s->nis }}
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm font-black text-slate-700 group-hover:text-blue-600 transition-colors uppercase tracking-tight">{{ $s->nama }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Siswa Reguler</p>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-block bg-blue-50 text-blue-600 border border-blue-100 px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest">
                                {{ $s->kelas }}
                            </span>
                        </td>
                        <td class="pr-12 pl-6 py-6 text-right">
                            <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus murid ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-3 rounded-2xl bg-slate-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                    <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-32 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-4x1 flex items-center justify-center mb-4 border-2 border-dashed border-slate-200">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Data Kosong atau Belum di-Filter</h4>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-10 border-t border-slate-50 bg-slate-50/30">
            {{ $siswas->appends(request()->all())->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selTingkat = document.getElementById('select_tingkat');
        const selJurusan = document.getElementById('select_jurusan');

        function handleFilter() {
            const t = selTingkat.value;
            const j = selJurusan.value;
            
            if (t && j) {
                const url = new URL(window.location.href);
                url.searchParams.set('tingkat', t);
                url.searchParams.set('jurusan', j);
                url.searchParams.delete('page');
                window.location.href = url.href;
            }
        }

        selTingkat.addEventListener('change', handleFilter);
        selJurusan.addEventListener('change', handleFilter);
    });
</script>
@endsection