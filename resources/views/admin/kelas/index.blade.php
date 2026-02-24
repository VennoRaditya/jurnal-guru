@extends('layouts.admin')

@section('title', 'Manajemen Kelas')
@section('header_title', 'Data Ruang Kelas & Siswa')
@section('header_subtitle', 'Monitoring distribusi siswa di setiap kejuruan.')

@section('content')

{{-- Alert Notifikasi --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-500 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-500/20">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-rose-500 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-rose-500/20">
        {{ session('error') }}
    </div>
@endif

{{-- Master Container --}}
<div class="space-y-8 pb-20 px-2 md:px-0" x-data="{ 
    selectedKelas: null, 
    selectedKelasId: null,
    loading: false, 
    siswas: [], 
    openModalKelas: false,
    openModalSiswa: false,
    tabSiswa: 'manual',
    fileName: '' 
}">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tighter uppercase">Daftar Ruang Kelas</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Total: {{ count($kelases) }} Ruangan Tersedia</p>
        </div>
        <button @click="openModalKelas = true" class="bg-blue-600 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/20 active:scale-95 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
            Buka Kelas Baru
        </button>
    </div>

    {{-- Grid Kartu Kelas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @forelse($kelases as $k)
        <div class="group bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-500 cursor-pointer relative overflow-hidden"
             @click="selectedKelas = '{{ $k->nama_kelas }}'; 
                    selectedKelasId = '{{ $k->id }}'; 
                    loading = true; 
                    fetch('/admin/kelas-manage/{{ $k->id }}/siswa')
                        .then(res => res.json())
                        .then(data => { siswas = data; loading = false; })">
            
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-50 rounded-full group-hover:bg-blue-50 transition-colors duration-500"></div>

            <div class="relative z-10">
                <div class="w-12 h-12 {{ str_contains($k->nama_kelas, '10') ? 'bg-indigo-500' : (str_contains($k->nama_kelas, '11') ? 'bg-amber-500' : 'bg-rose-500') }} rounded-2xl flex items-center justify-center text-white shadow-lg mb-6 group-hover:scale-110 transition-transform">
                    <span class="font-black text-xs">{{ explode(' ', $k->nama_kelas)[0] }}</span>
                </div>
                
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tighter mb-1">{{ $k->nama_kelas }}</h3>
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $k->siswas_count ?? 0 }} Siswa Terdaftar</p>
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest group-hover:translate-x-2 transition-transform">Lihat Anggota →</span>
                    <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" @click.stop>
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus seluruh data kelas?')" class="text-slate-300 hover:text-rose-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center">
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-tighter">Database Kelas Kosong</h4>
        </div>
        @endforelse
    </div>

    {{-- MODAL TAMBAH KELAS BARU --}}
    <div x-show="openModalKelas" class="fixed inset-0 z-120 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-6">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModalKelas = false"></div>
            <div class="bg-white w-full max-w-lg rounded-[3rem] shadow-2xl relative z-10 overflow-hidden"
                 x-show="openModalKelas" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8">
                <div class="p-10">
                    <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tighter mb-8">Buka Kelas Baru</h3>
                    <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Nama Kelas</label>
                            <input type="text" name="nama_kelas" placeholder="CONTOH: 10 PPLG 1" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold uppercase focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="openModalKelas = false" class="flex-1 py-4 text-[10px] font-black uppercase text-slate-400">Batal</button>
                            <button type="submit" class="flex-2 bg-blue-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase shadow-lg shadow-blue-500/20">Simpan Kelas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH SISWA --}}
    <div x-show="openModalSiswa" class="fixed inset-0 z-120 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-6">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModalSiswa = false; fileName = ''"></div>
            <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl relative z-10 overflow-hidden"
                 x-show="openModalSiswa" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8">
                <div class="p-10">
                    <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tighter" x-text="'Kelola Siswa - ' + selectedKelas"></h3>
                    
                    <div class="flex gap-2 mt-8 bg-slate-50 p-1.5 rounded-2xl">
                        <button @click="tabSiswa = 'manual'" :class="tabSiswa === 'manual' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400'" class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase transition-all">Input Manual</button>
                        <button @click="tabSiswa = 'excel'" :class="tabSiswa === 'excel' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400'" class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase transition-all">Import Excel</button>
                    </div>

                    {{-- Form Manual --}}
                    <div x-show="tabSiswa === 'manual'" class="mt-8">
                        <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="kelas_id" :value="selectedKelasId">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="nis" placeholder="NIS/NISN" required class="bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold uppercase focus:ring-2 focus:ring-blue-500">
                                <select name="jk" class="bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500">
                                    <option value="L">LAKI-LAKI</option>
                                    <option value="P">PEREMPUAN</option>
                                </select>
                            </div>
                            <input type="text" name="nama" placeholder="NAMA LENGKAP" required class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold uppercase focus:ring-2 focus:ring-blue-500">
                            <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-blue-500/20 active:scale-95 transition-all">Simpan Siswa</button>
                        </form>
                    </div>

                    {{-- Form Excel --}}
                    <div x-show="tabSiswa === 'excel'" class="mt-8">
                        <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-center">
                            @csrf
                            <input type="hidden" name="kelas_id" :value="selectedKelasId">
                            <div class="border-2 border-dashed rounded-3xl p-10 transition-all group relative"
                                 :class="fileName ? 'border-emerald-400 bg-emerald-50/30' : 'border-slate-200 hover:border-blue-400 bg-slate-50/50'">
                                <input type="file" name="file" id="excelFile" class="hidden" required @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                                <label for="excelFile" class="cursor-pointer block">
                                    <template x-if="!fileName">
                                        <div class="space-y-3">
                                            <svg class="w-12 h-12 text-slate-300 mx-auto group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"/></svg>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pilih file .xlsx / .csv</p>
                                        </div>
                                    </template>
                                    <template x-if="fileName">
                                        <div class="space-y-3">
                                            <div class="w-12 h-12 bg-emerald-500 rounded-2xl mx-auto flex items-center justify-center text-white shadow-lg">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest truncate px-4" x-text="fileName"></p>
                                        </div>
                                    </template>
                                </label>
                            </div>
                            <div class="flex flex-col gap-4">
                                <button type="submit" :disabled="!fileName" :class="!fileName ? 'opacity-50 bg-slate-400' : 'bg-emerald-500 shadow-emerald-500/20'" class="w-full text-white py-4 rounded-xl text-[10px] font-black uppercase shadow-lg transition-all">Proses Import Data</button>
                                <a href="{{ route('admin.siswa.template') }}" class="text-[9px] font-black text-blue-600 uppercase tracking-widest hover:underline">Download Template Excel (.CSV)</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Panel Detail Siswa --}}
    <div x-show="selectedKelas" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8"
         class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_40px_80px_rgba(0,0,0,0.05)] overflow-hidden" x-cloak>
        
        <div class="bg-slate-900 px-10 py-8 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button @click="selectedKelas = null" class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h3 class="text-white font-black uppercase tracking-widest text-sm" x-text="'Anggota ' + selectedKelas"></h3>
            </div>
            <button @click="openModalSiswa = true" class="bg-blue-600 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all">
                + Tambah / Import Siswa
            </button>
        </div>

        <div class="p-4">
            <div x-show="loading" class="py-20 text-center">
                <div class="inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            </div>

            <table x-show="!loading" class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">NISN</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">L/P</th>
                        <th class="pr-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <template x-for="(s, index) in siswas" :key="s.id">
                        <tr class="group hover:bg-slate-50/50">
                            <td class="px-8 py-6 text-xs font-black text-slate-400" x-text="String(index + 1).padStart(2, '0')"></td>
                            <td class="px-6 py-6 font-mono text-xs font-bold text-slate-600" x-text="s.nis"></td>
                            <td class="px-6 py-6">
                                <span class="text-sm font-black text-slate-700 uppercase" x-text="s.nama"></span>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span :class="s.jk === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600'" class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase" x-text="s.jk"></span>
                            </td>
                            <td class="pr-10 py-6 text-right">
                                <form :action="'/admin/siswa-manage/' + s.id" method="POST" @submit.prevent="if(confirm('Hapus siswa?')) $el.submit()">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection