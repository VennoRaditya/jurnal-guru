@extends('layouts.app')

@section('title', 'Rekap Absensi Bulanan')
@section('header_title', 'Rekap Absensi')
@section('header_subtitle', 'Tinjau dan kelola presensi siswa secara mendetail.')

@section('content')
<div class="space-y-6 md:space-y-8 pb-20 px-4 md:px-0">
    
    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
        <div class="animate-in fade-in slide-in-from-top-4 duration-500 bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-5 rounded-4xl text-[10px] font-black uppercase tracking-widest flex items-center shadow-sm">
            <div class="w-8 h-8 bg-emerald-500 rounded-xl flex items-center justify-center text-white mr-4 shadow-lg shadow-emerald-200 shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    {{-- HEADER & FILTER SECTION --}}
    <div class="bg-white p-6 md:p-8 rounded-[2.5rem] md:rounded-[3rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)]">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 shrink-0 border border-rose-100">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl md:text-2xl font-black text-slate-800 uppercase tracking-tighter leading-tight">Rekap Absensi Bulanan</h2>
                    <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-[0.2em] mt-0.5">
                        {{ \Carbon\Carbon::parse($periodeInput)->translatedFormat('F Y') }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="{{ route('guru.absensi.rekap') }}" method="GET" id="filter-form" class="w-full sm:w-auto">
                    <input type="month" 
                        name="bulan_tahun" 
                        value="{{ $periodeInput }}" 
                        onchange="this.form.submit()"
                        class="w-full sm:w-56 bg-slate-50 border border-slate-50 rounded-2xl px-5 py-4 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all outline-none text-center">
                </form>

                <a href="{{ route('guru.absensi.cetakHarian', ['bulan_tahun' => $periodeInput]) }}" 
                   target="_blank"
                   class="w-full sm:w-auto bg-slate-900 text-white px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-slate-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak PDF</span>
                </a>
            </div>
        </div>
    </div>

    {{-- CONTENT SECTION --}}
    @forelse($riwayatJurnal->groupBy('tanggal') as $tgl => $kumpulanJurnal)
        <div class="my-10 flex items-center gap-4">
            <div class="h-px bg-slate-200 flex-1"></div>
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] bg-slate-100 px-4 py-1.5 rounded-full">
                {{ \Carbon\Carbon::parse($tgl)->translatedFormat('l, d F Y') }}
            </span>
            <div class="h-px bg-slate-200 flex-1"></div>
        </div>

        @foreach($kumpulanJurnal as $jurnal)
        <div class="bg-white rounded-4xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            {{-- Journal Header Card --}}
            <div class="p-6 md:p-8 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-indigo-600 rounded-3xl flex items-center justify-center text-white shadow-xl shadow-indigo-100 shrink-0">
                        <span class="font-black text-xl tracking-tight">{{ $jurnal->kelas }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest block">{{ $jurnal->mata_pelajaran }}</span>
                        <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase leading-none mt-1 tracking-tight">Kelas {{ $jurnal->kelas }}</h3>
                        <p class="text-[11px] text-slate-500 font-medium mt-2 leading-relaxed">
                            <span class="font-black text-slate-400">MATERI:</span> {{ $jurnal->materi_kd }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- TABLE/MOBILE CONTENT --}}
            <div class="p-4 md:p-0">
                {{-- DESKTOP: Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/30">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest w-16 text-center">No</th>
                                <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Siswa</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @php $no = 1; @endphp
                            @forelse($jurnal->absensi as $abs)
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="px-8 py-5 text-center text-sm font-black text-slate-400">{{ $no++ }}</td>
                                <td class="px-4 py-5">
                                    <p class="text-sm font-black text-slate-700 uppercase tracking-tight">{{ $abs->siswa->nama ?? 'Siswa Terhapus' }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">NIS: {{ $abs->siswa->nis ?? '-' }}</p>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @php
                                        $status = strtolower($abs->status);
                                        $badgeStyle = match($status) {
                                            'sakit' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'izin'  => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'alfa'  => 'bg-rose-100 text-rose-700 border-rose-200',
                                            'hadir' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                                        };
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase border {{ $badgeStyle }}">
                                        {{ $abs->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button onclick="openEditModal('{{ $abs->id }}', '{{ $abs->siswa->nama }}', '{{ $abs->status }}')" 
                                            class="p-3 bg-slate-100 hover:bg-indigo-600 rounded-xl text-slate-500 hover:text-white transition-all">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center italic text-slate-400 text-xs font-bold uppercase tracking-widest">Nihil (Hadir Semua)</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE: Stacked Cards --}}
                <div class="md:hidden space-y-3">
                    @forelse($jurnal->absensi as $abs)
                    <div class="bg-white border border-slate-100 rounded-3xl p-5 flex items-center justify-between gap-4 shadow-sm">
                        <div>
                            <p class="text-xs font-black text-slate-700 uppercase tracking-tight line-clamp-1">{{ $abs->siswa->nama ?? 'Siswa Terhapus' }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">NIS: {{ $abs->siswa->nis ?? '-' }}</p>
                            @php
                                $status = strtolower($abs->status);
                                $badgeStyle = match($status) {
                                    'sakit' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'izin'  => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'alfa'  => 'bg-rose-100 text-rose-700 border-rose-200',
                                    'hadir' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                            @endphp
                            <span class="inline-block mt-2 px-3 py-1 rounded-full text-[8px] font-black uppercase border {{ $badgeStyle }}">
                                {{ $abs->status }}
                            </span>
                        </div>
                        <button onclick="openEditModal('{{ $abs->id }}', '{{ $abs->siswa->nama }}', '{{ $abs->status }}')" 
                                class="p-3 bg-slate-100 rounded-xl text-slate-500 shrink-0">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                    </div>
                    @empty
                    <div class="text-center py-6 text-slate-400 text-xs font-bold uppercase tracking-widest">Nihil</div>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    @empty
        <div class="text-center py-24 bg-white rounded-[2.5rem] border border-dashed border-slate-200 px-6">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Data Belum Tersedia</h4>
            <p class="text-slate-400 text-[11px] mt-1">Belum ada jurnal pengajaran di bulan ini.</p>
        </div>
    @endforelse
</div>

{{-- MODAL EDIT STATUS --}}
<div id="modalEdit" class="fixed inset-0 z-1000 hidden">
    {{-- Overlay --}}
    <div id="modalOverlay" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeEditModal()"></div>
    
    <div class="relative flex items-center justify-center min-h-screen p-4 pointer-events-none">
        <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 pointer-events-auto" id="modalContainer">
            <div class="p-8">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Ubah Status</h3>
                        <p id="modalNamaSiswa" class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] mt-1"></p>
                    </div>
                    <button onclick="closeEditModal()" class="text-slate-300 hover:text-slate-500 rounded-full p-1 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="formUpdateAbsensi" method="POST" onsubmit="handleFormSubmit(event)">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-3">
                        @foreach(['Hadir', 'Sakit', 'Izin', 'Alfa'] as $st)
                        <label class="relative flex items-center p-4 rounded-2xl border-2 border-slate-100 cursor-pointer hover:border-indigo-200 transition-all has-checked:border-indigo-600 has-checked:bg-indigo-50 group">
                            <input type="radio" name="status" value="{{ $st }}" class="hidden peer">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-xs font-black text-slate-600 uppercase tracking-widest peer-checked:text-indigo-700">{{ $st }}</span>
                                <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center peer-checked:border-indigo-600 peer-checked:bg-indigo-600">
                                    <div class="w-2.5 h-2.5 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-10">
                        <button type="button" onclick="closeEditModal()" class="py-4 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-slate-100 transition-all">
                            Batal
                        </button>
                        <button type="submit" id="btnUpdate" class="py-4 rounded-xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100 active:scale-95">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, nama, status) {
        const modal = document.getElementById('modalEdit');
        const overlay = document.getElementById('modalOverlay');
        const container = document.getElementById('modalContainer');
        const form = document.getElementById('formUpdateAbsensi');
        const btn = document.getElementById('btnUpdate');
        
        // Reset button state
        btn.disabled = false;
        btn.innerText = "Update";

        document.getElementById('modalNamaSiswa').innerText = nama;
        form.action = `/guru/siswa/absensi/rekap/update/${id}`; 

        const radios = form.querySelectorAll('input[name="status"]');
        radios.forEach(r => {
            if(r.value.toLowerCase() === status.toLowerCase()) r.checked = true;
        });

        modal.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.add('opacity-100');
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeEditModal() {
        const modal = document.getElementById('modalEdit');
        const overlay = document.getElementById('modalOverlay');
        const container = document.getElementById('modalContainer');
        
        overlay.classList.remove('opacity-100');
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function handleFormSubmit(e) {
        const btn = document.getElementById('btnUpdate');
        btn.disabled = true;
        btn.innerText = "Proses...";
        
        // Menjalankan animasi tutup modal sambil form melakukan submit ke server
        setTimeout(() => {
            closeEditModal();
        }, 500);
    }
</script>
@endsection