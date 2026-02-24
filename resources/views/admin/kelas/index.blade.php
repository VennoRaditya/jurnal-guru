@extends('layouts.admin')

@section('title', 'Manajemen Kelas')
@section('header_title', 'Data Ruang Kelas & Siswa')
@section('header_subtitle', 'Monitoring distribusi siswa di setiap kejuruan.')

@section('content')

{{-- Alert Notifikasi --}}
<div class="fixed top-5 right-5 z-200 space-y-3">
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="p-4 bg-emerald-500 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest shadow-2xl shadow-emerald-500/40 flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
</div>

<div class="pb-20 px-4 md:px-0" x-data="{ 
    selectedKelas: null, 
    selectedKelasId: null,
    loading: false, 
    siswas: [], 
    openModalKelas: false,
    openModalSiswa: false,
    tabSiswa: 'manual',
    fileName: '',
    
    async fetchSiswa(id, nama) {
        this.selectedKelas = nama;
        this.selectedKelasId = id;
        this.loading = true;
        try {
            let response = await fetch(`/admin/kelas-manage/${id}/siswa`);
            this.siswas = await response.json();
        } catch (e) { console.error('Gagal mengambil data'); }
        this.loading = false;
        
        // Scroll otomatis ke tabel
        $nextTick(() => { 
            document.getElementById('panel-siswa').scrollIntoView({ behavior: 'smooth' }); 
        });
    },

    init() {
        // Jika ada session last_kelas_id dari Controller, otomatis panggil fetchSiswa
        @if(session('last_kelas_id'))
            @php
                $lastKelas = \App\Models\Kelas::find(session('last_kelas_id'));
            @endphp
            @if($lastKelas)
                this.fetchSiswa('{{ $lastKelas->id }}', '{{ $lastKelas->nama_kelas }}');
            @endif
        @endif
    }
}">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <span class="text-blue-600 text-[10px] font-black uppercase tracking-[0.3em] mb-2 block">Database Akademik</span>
            <h2 class="text-4xl font-black text-slate-800 tracking-tighter uppercase leading-none">Ruang Kelas</h2>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-3 flex items-center gap-2">
                <span class="w-8 h-0.5 bg-slate-200"></span>
                {{ count($kelases) }} Ruangan Terdaftar
            </p>
        </div>
        <button @click="openModalKelas = true" 
                class="bg-slate-900 text-white px-8 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-blue-600 transition-all shadow-2xl shadow-slate-900/20 active:scale-95 flex items-center justify-center gap-3 group">
            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
            Tambah Kelas Baru
        </button>
    </div>

    {{-- Grid Kartu Kelas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($kelases as $k)
        <div class="group bg-white rounded-[2.5rem] p-1 border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 cursor-pointer relative"
             @click="fetchSiswa('{{ $k->id }}', '{{ $k->nama_kelas }}')">
            
            <div class="bg-white rounded-[2.3rem] p-7 relative overflow-hidden h-full border border-transparent group-hover:border-blue-50 transition-all">
                {{-- Badge Angka --}}
                <div class="absolute -right-2 -top-2 w-24 h-24 bg-slate-50 rounded-full group-hover:bg-blue-50/50 transition-colors duration-500"></div>

                <div class="relative z-10">
                    <div class="w-14 h-14 {{ str_contains($k->nama_kelas, '10') ? 'bg-indigo-600' : (str_contains($k->nama_kelas, '11') ? 'bg-amber-500' : 'bg-rose-500') }} rounded-2xl flex items-center justify-center text-white shadow-xl mb-8 group-hover:rotate-6 transition-transform">
                        <span class="font-black text-sm">{{ explode(' ', $k->nama_kelas)[0] }}</span>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-1">{{ $k->nama_kelas }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $k->siswas_count ?? 0 }} Siswa Aktif</p>

                    <div class="mt-10 flex justify-between items-center">
                        <span class="text-[9px] font-black text-blue-600 uppercase tracking-[0.15em] opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">Kelola Anggota →</span>
                        <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" @click.stop>
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus seluruh data kelas?')" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-32 bg-slate-50 rounded-[3rem] border-4 border-dashed border-slate-200 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-slate-200 rounded-full mb-6 flex items-center justify-center text-slate-400">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Belum ada kelas yang dibuat</h4>
        </div>
        @endforelse
    </div>

    {{-- MODAL TAMBAH KELAS --}}
    <div x-show="openModalKelas" class="fixed inset-0 z-150 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md" @click="openModalKelas = false"></div>
            <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl relative z-10 p-10"
                 x-show="openModalKelas" x-transition:enter="transition duration-300 transform" x-transition:enter-start="scale-95 opacity-0">
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tighter mb-8">Informasi Kelas</h3>
                <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-3 block">Identitas Nama Kelas</label>
                        <input type="text" name="nama_kelas" placeholder="MISAL: 12 TKJ 1" required 
                               class="w-full bg-slate-50 border-2 border-transparent rounded-2xl px-6 py-4 text-sm font-bold uppercase focus:bg-white focus:border-blue-500 transition-all outline-none">
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="openModalKelas = false" class="flex-1 text-[10px] font-black uppercase text-slate-400 tracking-widest hover:text-slate-600 transition-colors">Batal</button>
                        <button type="submit" class="flex-2 bg-blue-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-500/30 hover:bg-blue-700 transition-all">Buat Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Panel Detail Siswa --}}
    <div id="panel-siswa" x-show="selectedKelas" x-transition class="mt-20 scroll-mt-10" x-cloak>
        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
            <div class="bg-slate-900 px-10 py-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6">
                    <button @click="selectedKelas = null" class="group flex items-center gap-2">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 text-white flex items-center justify-center group-hover:bg-white group-hover:text-slate-900 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                    </button>
                    <div>
                        <h3 class="text-white font-black uppercase tracking-tighter text-2xl" x-text="selectedKelas"></h3>
                        <p class="text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Daftar Siswa Terverifikasi</p>
                    </div>
                </div>
                <button @click="openModalSiswa = true" class="bg-blue-600 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-500 transition-all active:scale-95 shadow-lg shadow-blue-600/20">
                    + Kelola Anggota
                </button>
            </div>

            <div class="p-8">
                <div x-show="loading" class="py-20 flex flex-col items-center gap-4">
                    <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest animate-pulse">Sinkronisasi Data...</p>
                </div>

                <div x-show="!loading" class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-slate-50">
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Rank</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">ID Registrasi</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Lengkap</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Gender</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="(s, index) in siswas" :key="s.id">
                                <tr class="group hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-6 text-[11px] font-black text-slate-300 group-hover:text-blue-600 transition-colors" x-text="String(index + 1).padStart(2, '0')"></td>
                                    <td class="px-6 py-6 font-mono text-xs font-bold text-slate-500" x-text="s.nis"></td>
                                    <td class="px-6 py-6">
                                        <span class="text-sm font-black text-slate-700 uppercase tracking-tight group-hover:text-blue-600 transition-colors" x-text="s.nama"></span>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <span :class="s.jk === 'L' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-pink-50 text-pink-600 border-pink-100'" 
                                              class="px-4 py-1.5 rounded-full text-[9px] font-black border" x-text="s.jk === 'L' ? 'LAKI-LAKI' : 'PEREMPUAN'"></span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <form :action="'/admin/siswa-manage/' + s.id" method="POST" @submit.prevent="if(confirm('Hapus siswa dari kelas ini?')) $el.submit()">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-10 h-10 inline-flex items-center justify-center text-slate-300 hover:bg-rose-500 hover:text-white rounded-xl transition-all group-hover:shadow-lg group-hover:shadow-rose-500/20">
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
    </div>

    {{-- MODAL KELOLA SISWA (IMPORT/MANUAL) --}}
    <div x-show="openModalSiswa" class="fixed inset-0 z-160 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md" @click="openModalSiswa = false; fileName = ''"></div>
            <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden"
                 x-show="openModalSiswa" x-transition:enter="transition duration-300 transform" x-transition:enter-start="translate-y-full">
                
                <div class="p-10">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <h3 class="text-3xl font-black text-slate-800 uppercase tracking-tighter" x-text="selectedKelas"></h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Registrasi Anggota Baru</p>
                        </div>
                        <button @click="openModalSiswa = false" class="text-slate-300 hover:text-slate-900 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <div class="flex gap-2 bg-slate-100 p-2 rounded-3x1 mb-10">
                        <button @click="tabSiswa = 'manual'" :class="tabSiswa === 'manual' ? 'bg-white shadow-md text-blue-600' : 'text-slate-500'" class="flex-1 py-4 rounded-[1.2rem] text-[10px] font-black uppercase tracking-widest transition-all">Input Manual</button>
                        <button @click="tabSiswa = 'excel'" :class="tabSiswa === 'excel' ? 'bg-white shadow-md text-blue-600' : 'text-slate-500'" class="flex-1 py-4 rounded-[1.2rem] text-[10px] font-black uppercase tracking-widest transition-all">Import CSV/Excel</button>
                    </div>

                    {{-- Form Manual --}}
                    <div x-show="tabSiswa === 'manual'" x-transition class="space-y-6">
                        <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="kelas_id" :value="selectedKelasId">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">NIS/NISN</label>
                                    <input type="text" name="nis" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-600">
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Jenis Kelamin</label>
                                    <select name="jk" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-600">
                                        <option value="L">LAKI-LAKI</option>
                                        <option value="P">PEREMPUAN</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Nama Lengkap Siswa</label>
                                <input type="text" name="nama" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold uppercase outline-none focus:ring-2 focus:ring-blue-600">
                            </div>
                            <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:bg-blue-600 transition-all mt-4">Daftarkan Siswa</button>
                        </form>
                    </div>

                    {{-- Form Excel --}}
                    <div x-show="tabSiswa === 'excel'" x-transition>
                        <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="kelas_id" :value="selectedKelasId">
                            <div class="relative group">
                                <input type="file" name="file" id="excelFile" class="hidden" required @change="fileName = $event.target.files[0].name">
                                <label for="excelFile" 
                                       class="border-4 border-dashed rounded-[2.5rem] p-12 flex flex-col items-center justify-center cursor-pointer transition-all"
                                       :class="fileName ? 'border-emerald-500 bg-emerald-50' : 'border-slate-100 bg-slate-50 group-hover:border-blue-200 group-hover:bg-blue-50/30'">
                                    
                                    <div :class="fileName ? 'bg-emerald-500' : 'bg-slate-200 group-hover:bg-blue-500'" class="w-16 h-16 rounded-3xl flex items-center justify-center text-white shadow-lg mb-4 transition-all">
                                        <svg x-show="!fileName" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-width="2" stroke-linecap="round"/></svg>
                                        <svg x-show="fileName" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"/></svg>
                                    </div>
                                    <p class="text-[10px] font-black uppercase tracking-widest" :class="fileName ? 'text-emerald-600' : 'text-slate-400 group-hover:text-blue-600'" x-text="fileName ? fileName : 'Klik untuk Unggah Berkas'"></p>
                                    <p class="text-[8px] font-bold text-slate-300 uppercase mt-2">Format: .XLSX / .CSV / .XLS</p>
                                </label>
                            </div>
                            <div class="space-y-4">
                                <button type="submit" :disabled="!fileName" :class="!fileName ? 'opacity-50 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700'" class="w-full text-white py-5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 transition-all">Mulai Import Data</button>
                                <a href="{{ route('admin.siswa.template') }}" class="flex items-center justify-center gap-2 text-[10px] font-black text-blue-600 uppercase tracking-widest">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2"/></svg>
                                    Unduh Template Berkas
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    /* Smooth transition for table hover */
    table tbody tr { transition: all 0.2s ease-in-out; }
    
    /* Custom Scrollbar for better UI */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f8fafc; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection