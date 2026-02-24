@extends('layouts.admin')

@section('title', 'Kelola Guru')
@section('header_title', 'Data Tenaga Pengajar')
@section('header_subtitle', 'Manajemen akun dan data mata pelajaran guru.')

@section('content')
<div class="space-y-8 pb-20 px-2 md:px-0" x-data="{ level: '10' }">
    
    {{-- Form Tambah Guru (Registrasi Card) --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="bg-slate-900 px-10 py-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Registrasi Guru Baru</h3>
            </div>
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Admin Panel v.1.0</span>
        </div>
        
        <form action="{{ route('admin.guru.store') }}" method="POST" class="p-10">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Baris 1: Identitas --}}
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2">NIP</label>
                    <input type="text" name="nip" placeholder="Contoh: 1988..." required 
                        class="w-full bg-slate-50 border-2 border-transparent rounded-2xl px-6 py-4 text-xs font-bold focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2">Nama Lengkap & Gelar</label>
                    <input type="text" name="nama" placeholder="Nama Guru..." required 
                        class="w-full bg-slate-50 border-2 border-transparent rounded-2xl px-6 py-4 text-xs font-bold focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2">Bidang Studi (Mapel)</label>
                    <input type="text" name="mapel" placeholder="Contoh: Matematika" required 
                        class="w-full bg-slate-50 border-2 border-transparent rounded-2xl px-6 py-4 text-xs font-bold focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none">
                </div>

                {{-- Baris 2: Akun --}}
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2">Username Login</label>
                    <input type="text" name="username" placeholder="guru_kece" required 
                        class="w-full bg-slate-50 border-2 border-transparent rounded-2xl px-6 py-4 text-xs font-bold focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2">Password Akun</label>
                    <input type="password" name="password" placeholder="********" required 
                        class="w-full bg-slate-50 border-2 border-transparent rounded-2xl px-6 py-4 text-xs font-bold focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none">
                </div>

                {{-- Baris 3: Pemilihan Kelas (Dinamis dari DB) --}}
                <div class="md:col-span-3 mt-4 space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pilih Kelas yang Diajar</label>
                        <div class="flex gap-2 bg-slate-100 p-1 rounded-xl">
                            @foreach(['10', '11', '12'] as $t)
                                <button type="button" @click="level = '{{ $t }}'" 
                                    :class="level === '{{ $t }}' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'"
                                    class="px-4 py-1.5 rounded-lg text-[9px] font-black transition-all uppercase">
                                    Kelas {{ $t }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-[2.5rem] p-8 border border-slate-100">
                        @php 
                            // Grouping dinamis berdasarkan angka pertama di nama_kelas
                            $groupedKelas = $kelases->groupBy(function($item) {
                                return preg_replace('/[^0-9]/', '', explode(' ', $item->nama_kelas)[0]);
                            });
                        @endphp

                        @foreach(['10', '11', '12'] as $lvl)
                            <div x-show="level === '{{ $lvl }}'" 
                                 x-transition:enter="transition ease-out duration-300" 
                                 x-transition:enter-start="opacity-0 transform scale-95" 
                                 class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                
                                @if(isset($groupedKelas[$lvl]))
                                    @foreach($groupedKelas[$lvl] as $kelas)
                                        <label class="relative group">
                                            <input type="checkbox" name="kelas[]" value="{{ $kelas->nama_kelas }}" class="peer hidden">
                                            
                                            <div class="bg-white border-2 border-transparent peer-checked:border-blue-500 peer-checked:bg-blue-50/50 p-4 rounded-2xl transition-all cursor-pointer hover:shadow-md text-center">
                                                <span class="block text-[10px] font-black text-slate-700 uppercase">
                                                    {{ $kelas->nama_kelas }}
                                                </span>
                                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">Pilih Ruangan</span>
                                            </div>
                                            
                                            <div class="absolute -top-1 -right-1 bg-blue-600 text-white rounded-full p-1 opacity-0 peer-checked:opacity-100 transition-opacity">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                        </label>
                                    @endforeach
                                @else
                                    <div class="col-span-full py-10 text-center border-2 border-dashed border-slate-200 rounded-3xl">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase italic">Belum ada data kelas untuk level {{ $lvl }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-end">
                <button type="submit" class="w-full md:w-72 bg-blue-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-blue-200 active:scale-95">
                    Simpan Guru & Akun
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel List Guru --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="px-10 py-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 uppercase tracking-tighter text-sm">List Tenaga Pengajar</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Database Kepegawaian Sekolah</p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">{{ $gurus->total() }} Guru Terdaftar</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-50">
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Identitas (NIP)</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama & Username</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Spesialisasi</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kelas Ampuan</th>
                        <th class="pr-12 pl-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($gurus as $g)
                    <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                        <td class="px-10 py-6">
                            <span class="text-xs font-mono font-black text-slate-400 tracking-tighter group-hover:text-blue-600 transition-colors">#{{ $g->nip }}</span>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm font-black text-slate-700 uppercase tracking-tight">{{ $g->nama }}</p>
                            <p class="text-[9px] text-blue-500 font-bold uppercase tracking-widest">User: {{ $g->username }}</p>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-block bg-white border-2 border-slate-100 text-slate-600 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest group-hover:border-blue-500/20 group-hover:text-blue-600 transition-all">
                                {{ $g->mapel }}
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $assigned = is_array($g->kelas) ? $g->kelas : json_decode($g->kelas, true) ?? [];
                                @endphp
                                @forelse($assigned as $k)
                                    <span class="bg-slate-100 text-slate-500 px-2 py-1 rounded-md text-[8px] font-black uppercase tracking-tighter border border-slate-200">
                                        {{ $k }}
                                    </span>
                                @empty
                                    <span class="text-[9px] text-slate-300 italic">Belum setting kelas</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="pr-12 pl-6 py-6">
                            <div class="flex justify-end items-center gap-2">
                                <a href="#" class="p-2.5 rounded-xl bg-slate-50 text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus data guru ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2.5 rounded-xl bg-slate-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-32 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-4xl flex items-center justify-center mb-4 border-2 border-dashed border-slate-200">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Belum Ada Pengajar Terdaftar</h4>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-10 border-t border-slate-50 bg-slate-50/30">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">
                    Data dikelola secara real-time melalui sistem pusat
                </p>
                <div class="pagination-premium">
                    {{ $gurus->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Scrollbar for the Class Selection */
    .bg-slate-50::-webkit-scrollbar { width: 4px; }
    .bg-slate-50::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    
    /* Pagination Styling Custom */
    .pagination-premium nav { @apply flex justify-center; }
    .pagination-premium .pagination { @apply flex gap-2 border-none; }
    .pagination-premium .page-item .page-link { @apply rounded-xl border-none bg-white text-[10px] font-black text-slate-500 px-4 py-2.5 shadow-sm hover:bg-blue-600 hover:text-white transition-all; }
    .pagination-premium .page-item.active .page-link { @apply bg-blue-600 text-white shadow-lg shadow-blue-100; }
    .pagination-premium .page-item.disabled .page-link { @apply opacity-50 bg-transparent shadow-none; }
</style>
@endsection