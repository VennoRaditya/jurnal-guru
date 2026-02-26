    @extends('layouts.app')

    @section('content')
    <div class="max-w-6xl mx-auto px-4 md:px-0 pb-20">
        {{-- HEADER & FILTER SECTION --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">Daftar Ketidakhadiran Siswa</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">
                        Data Sakit, Izin, & Alfa — {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                {{-- Form Filter Tanggal --}}
                <form action="{{ route('guru.absensi.rekap') }}" method="GET" id="filter-form" class="flex flex-1 sm:flex-initial gap-2 bg-white p-2 rounded-2xl shadow-sm border border-slate-100 w-full">
                    <input type="date" 
                        name="tanggal" 
                        value="{{ $tanggal }}" 
                        onchange="document.getElementById('filter-form').submit()"
                        class="flex-1 text-xs font-bold border-none focus:ring-0 cursor-pointer bg-transparent">
                    <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-slate-700 transition-colors">
                        Filter
                    </button>
                </form>

                {{-- Tombol PDF --}}
<a href="{{ route('guru.absensi.cetakHarian', ['tanggal' => $tanggal]) }}" 
   target="_blank"
   class="w-full sm:w-auto bg-rose-500 text-white px-6 py-4 rounded-3xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-rose-200 hover:bg-rose-600 transition-all flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
    </svg>
    Cetak PDF Ketidakhadiran
</a>
            </div>
        </div>

        @forelse($riwayatJurnal as $jurnal)
        <div class="bg-white rounded-4x1 md:rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-8">
            {{-- Header Jurnal (Info Kelas) --}}
            <div class="p-6 bg-slate-50 border-b border-slate-100 flex flex-col md:flex-row justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-100 shrink-0">
                        <span class="font-black text-lg">{{ substr($jurnal->kelas, 0, 1) }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">{{ $jurnal->mata_pelajaran }}</span>
                        <h3 class="text-lg font-black text-slate-800 uppercase italic leading-none">Kelas: {{ $jurnal->kelas }}</h3>
                    </div>
                </div>
                <div class="md:text-right border-t md:border-t-0 pt-4 md:pt-0">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Materi / Kompetensi Dasar</p>
                    <p class="text-xs font-bold text-slate-600 uppercase">{{ $jurnal->materi_kd }}</p>
                </div>
            </div>

            {{-- DESKTOP VIEW (Table) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                            <th class="px-8 py-4 w-16 text-center">No</th>
                            <th class="px-4 py-4">Nama Siswa</th>
                            <th class="px-8 py-4 text-center">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @php $no = 1; @endphp
                        @forelse($jurnal->absensi as $abs)
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-8 py-4 text-center text-xs font-bold text-slate-400">{{ $no++ }}</td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-black text-slate-700 uppercase">{{ $abs->siswa->nama ?? 'Siswa Terhapus' }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">NIS: {{ $abs->siswa->nis ?? '-' }}</p>
                            </td>
                            <td class="px-8 py-4 text-center">
                                @php
                                    $status = strtolower($abs->status);
                                    $badgeStyle = match($status) {
                                        'sakit' => 'bg-amber-100 text-amber-600 border-amber-200',
                                        'izin'  => 'bg-blue-100 text-blue-600 border-blue-200',
                                        'alfa'  => 'bg-rose-100 text-rose-600 border-rose-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase border {{ $badgeStyle }}">
                                    {{ $abs->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-10 text-center">
                                <p class="text-xs font-bold uppercase tracking-widest italic text-emerald-500">Nihil - Semua Siswa Hadir</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE VIEW (List Cards) --}}
            <div class="md:hidden divide-y divide-slate-50">
                @php $mNo = 1; @endphp
                @forelse($jurnal->absensi as $abs)
                <div class="p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <span class="text-[10px] font-black text-slate-300">{{ $mNo++ }}</span>
                        <div class="min-w-0">
                            <p class="text-xs font-black text-slate-700 uppercase truncate">{{ $abs->siswa->nama ?? 'Siswa Terhapus' }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">NIS: {{ $abs->siswa->nis ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        @php
                            $status = strtolower($abs->status);
                            $mBadge = match($status) {
                                'sakit' => 'text-amber-600 bg-amber-50',
                                'izin'  => 'text-blue-600 bg-blue-50',
                                'alfa'  => 'text-rose-600 bg-rose-50',
                                default => 'text-slate-600 bg-slate-50',
                            };
                        @endphp
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-[10px] font-black uppercase {{ $mBadge }} border border-current opacity-80">
                            {{ substr($status, 0, 1) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500">Semua Siswa Hadir</p>
                </div>
                @endforelse
            </div>
        </div>
        @empty
        {{-- EMPTY STATE --}}
        <div class="text-center py-32 bg-white rounded-[3rem] border border-dashed border-slate-200">
            <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 0h6" />
                </svg>
            </div>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Tidak ada data jurnal pada tanggal ini</p>
            <p class="text-[9px] text-slate-300 font-bold uppercase mt-1">Silahkan pilih tanggal lain atau cek input presensi</p>
        </div>
        @endforelse
    </div>
    @endsection