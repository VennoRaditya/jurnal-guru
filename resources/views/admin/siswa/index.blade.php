@extends('layouts.admin')

@section('title', 'Data Siswa')
@section('header_title', 'Manajemen Murid')
@section('header_subtitle', 'Kelola data murid berdasarkan tingkatan dan jurusan.')

@section('content')
<div class="space-y-8">
    {{-- Form Tambah Siswa --}}
    <div class="bg-white rounded-[40px] border border-slate-100 shadow-sm p-8">
        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Registrasi Murid Baru</h3>
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-600 rounded-2xl text-xs font-bold border border-emerald-100 flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.siswa.store') }}" method="POST" id="formSiswa" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @csrf
            <input type="text" name="nis" value="{{ old('nis') }}" placeholder="NIS" required 
                class="bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
            
            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap" required 
                class="bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
            
            <select id="select_tingkat" name="tingkat_temp" required 
                class="bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
                <option value="">Pilih Tingkat</option>
                <option value="X" {{ request('tingkat') == 'X' ? 'selected' : '' }}>Kelas 10</option>
                <option value="XI" {{ request('tingkat') == 'XI' ? 'selected' : '' }}>Kelas 11</option>
                <option value="XII" {{ request('tingkat') == 'XII' ? 'selected' : '' }}>Kelas 12</option>
            </select>

            <select id="select_jurusan" name="jurusan_temp" required 
                class="bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
                <option value="">Pilih Jurusan</option>
                @foreach(['RPL', 'TKJ', 'AKL', 'MANLOG', 'BR'] as $j)
                    <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                Simpan Murid
            </button>
        </form>
    </div>

    {{-- Tabel List --}}
    <div class="bg-white rounded-[40px] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-blue-500 rounded-full shadow-lg shadow-blue-100"></div>
                <h3 class="font-black text-slate-800 uppercase tracking-tighter">
                    Daftar Murid: <span class="text-blue-600">{{ request('tingkat') }} {{ request('jurusan') }}</span>
                </h3>
            </div>
            <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-4 py-2 rounded-full uppercase tracking-widest">
                Total: {{ $siswas->total() }} Murid
            </span>
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
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-10 py-5 text-xs font-bold text-slate-500">{{ $s->nis }}</td>
                        <td class="px-6 py-5 text-sm font-bold text-slate-800">{{ $s->nama }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest">
                                {{ $s->kelas }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus murid ini?')">
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
                        <td colspan="4" class="px-10 py-20 text-center">
                            <p class="text-slate-400 italic text-xs uppercase tracking-widest font-bold">Data Kosong / Belum Filter</p>
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