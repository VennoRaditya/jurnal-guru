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
                    <div class="flex items-center gap-2 mt-0.5">
                        <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-[0.2em]">
                            {{ \Carbon\Carbon::parse($periodeInput)->translatedFormat('F Y') }}
                        </p>
                        @if(request('kelas'))
                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <span class="text-[10px] md:text-xs font-black text-indigo-500 uppercase tracking-[0.2em]">Kelas {{ request('kelas') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="{{ route('guru.absensi.rekap') }}" method="GET" id="filterForm" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <select name="kelas" onchange="this.form.submit()" 
                        class="w-full sm:w-40 bg-slate-50 border border-slate-100 rounded-2xl px-4 py-4 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all outline-none">
                        <option value="">Semua Kelas</option>
                        @php
                            $guru = Auth::guard('guru')->user();
                            $listKelas = is_array($guru->kelas) ? $guru->kelas : explode(',', $guru->kelas);
                        @endphp
                        @foreach($listKelas as $k)
                            @php $val = trim($k); @endphp
                            @if($val)
                                <option value="{{ $val }}" {{ request('kelas') == $val ? 'selected' : '' }}>Kelas {{ $val }}</option>
                            @endif
                        @endforeach
                    </select>

                    <input type="month" name="bulan_tahun" value="{{ $periodeInput }}" onchange="this.form.submit()"
                        class="w-full sm:w-48 bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all outline-none text-center">
                </form>

                <div class="flex gap-2 w-full sm:w-auto">
                    {{-- EXCEL --}}
                    <a href="{{ route('guru.absensi.exportExcel', ['bulan_tahun' => $periodeInput, 'kelas' => request('kelas')]) }}" 
                        class="flex-1 sm:flex-none bg-emerald-600 text-white px-5 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span>Excel</span>
                    </a>

                    {{-- PDF PORTRAIT (Hanya Absensi) --}}
                    <a href="{{ route('guru.absensi.absensiOnlyPdf', ['bulan_tahun' => $periodeInput, 'kelas' => request('kelas')]) }}" target="_blank"
                        class="flex-1 sm:flex-none bg-rose-600 text-white px-5 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-rose-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span>PDF Absen</span>
                    </a>
                </div>
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
        @php
            $labelKelas = is_object($jurnal->kelas) ? $jurnal->kelas->nama_kelas : ($jurnal->kelas ?? 'N/A');
        @endphp
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-6">
            
            <button onclick="toggleAccordion('jurnal-{{ $jurnal->id }}')" 
                class="w-full text-left p-6 md:p-8 bg-white hover:bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-6 transition-colors">
                
                <div class="flex items-center gap-5 w-full">
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100 shrink-0">
                        <span class="font-black text-xl tracking-tight">{{ strtoupper(substr($labelKelas, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest block">{{ $jurnal->mata_pelajaran }}</span>
                        <h3 class="text-lg font-black text-slate-800 uppercase leading-none mt-1 tracking-tight truncate">Kelas {{ $labelKelas }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold mt-2 leading-relaxed truncate uppercase">
                            <span class="text-slate-300">Materi:</span> {{ $jurnal->materi_kd }}
                        </p>
                    </div>
                    <div id="icon-jurnal-{{ $jurnal->id }}" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 transition-transform duration-300 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </button>

            <div id="content-jurnal-{{ $jurnal->id }}" class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out bg-slate-50/30">
                {{-- DESKTOP TABLE --}}
                <div class="hidden md:block">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest w-16 text-center">No</th>
                                <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Siswa</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($jurnal->absensi as $index => $abs)
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="px-8 py-5 text-center text-sm font-black text-slate-300">{{ $index + 1 }}</td>
                                <td class="px-4 py-5">
                                    <p class="text-sm font-black text-slate-700 uppercase tracking-tight">{{ $abs->siswa->nama ?? 'Siswa Terhapus' }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">NIS: {{ $abs->siswa->nis ?? '-' }}</p>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @php
                                        $status = strtolower($abs->status);
                                        $badgeStyle = match($status) {
                                            'sakit'     => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'izin'      => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'terlambat' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                            'alfa'      => 'bg-rose-50 text-rose-700 border-rose-100',
                                            'hadir'     => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            default     => 'bg-slate-50 text-slate-600 border-slate-100',
                                        };
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase border {{ $badgeStyle }}">
                                        {{ $abs->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button onclick="openEditModal('{{ $abs->id }}', '{{ addslashes($abs->siswa->nama ?? 'Siswa') }}', '{{ $abs->status }}')" 
                                            class="inline-flex items-center gap-2 p-3 bg-slate-100 hover:bg-indigo-600 rounded-xl text-slate-500 hover:text-white transition-all">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span class="text-[9px] font-black uppercase tracking-widest hidden lg:block">Edit</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center italic text-slate-400 text-[10px] font-black uppercase tracking-widest">Data Kosong</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW --}}
                <div class="md:hidden space-y-4 p-4">
                    @foreach($jurnal->absensi as $abs)
                    <div class="bg-white border border-slate-200/60 rounded-[2rem] p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 font-black text-[10px] shrink-0 border border-slate-200 uppercase">
                                    {{ substr($abs->siswa->nama ?? 'S', 0, 2) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-black text-slate-800 uppercase tracking-tight truncate leading-tight">
                                        {{ $abs->siswa->nama ?? 'Siswa Terhapus' }}
                                    </p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                        NIS: {{ $abs->siswa->nis ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <button onclick="openEditModal('{{ $abs->id }}', '{{ addslashes($abs->siswa->nama ?? 'Siswa') }}', '{{ $abs->status }}')" 
                                    class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center active:bg-indigo-600 active:text-white transition-all shrink-0">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between">
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Status</span>
                            @php
                                $mStatus = strtolower($abs->status ?? '');
                                $mBadge = match($mStatus) {
                                    'sakit'     => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'izin'      => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'terlambat' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'alfa'      => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'hadir'     => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    default     => 'bg-slate-50 text-slate-500 border-slate-100',
                                };
                            @endphp
                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase border shadow-sm {{ $mBadge }}">
                                {{ $abs->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    @empty
        <div class="text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-200 px-6">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
            </div>
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em]">Data belum tersedia untuk periode ini</h4>
        </div>
    @endforelse
</div>

{{-- MODAL EDIT STATUS --}}
<div id="modalEdit" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div id="modalOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md opacity-0 transition-opacity duration-300" onclick="closeEditModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white w-full max-w-sm rounded-[3rem] shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 pointer-events-auto" id="modalContainer">
            <div class="p-8">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Ubah Status</h3>
                        <p id="modalNamaSiswa" class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] mt-1"></p>
                    </div>
                    <button onclick="closeEditModal()" class="text-slate-300 hover:text-slate-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form id="formUpdateAbsensi" method="POST" onsubmit="handleFormSubmit(event)">
                    @csrf @method('PUT')
                    <div class="space-y-3">
                        @foreach(['Hadir', 'Sakit', 'Izin', 'Terlambat', 'Alfa'] as $st)
                        <label class="relative flex items-center p-4 rounded-2xl border-2 border-slate-50 cursor-pointer hover:border-indigo-100 transition-all group">
                            <input type="radio" name="status" value="{{ $st }}" class="sr-only peer">
                            <div class="flex items-center justify-between w-full z-10">
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest peer-checked:text-indigo-700">{{ $st }}</span>
                                <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center peer-checked:border-indigo-600 peer-checked:bg-indigo-600 transition-all">
                                    <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                            </div>
                            <div class="absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 transition-all"></div>
                        </label>
                        @endforeach
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mt-10">
                        <button type="button" onclick="closeEditModal()" class="py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Batal</button>
                        <button type="submit" id="btnUpdate" class="py-4 rounded-2xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-slate-200 active:scale-95 transition-transform">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleAccordion(id) {
        const content = document.getElementById('content-' + id);
        const icon = document.getElementById('icon-' + id);
        
        if (content.style.maxHeight && content.style.maxHeight !== '0px') {
            content.style.maxHeight = '0px';
            icon.style.transform = 'rotate(0deg)';
            icon.classList.remove('bg-indigo-600', 'text-white');
            icon.classList.add('bg-slate-50', 'text-slate-400');
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
            icon.style.transform = 'rotate(180deg)';
            icon.classList.remove('bg-slate-50', 'text-slate-400');
            icon.classList.add('bg-indigo-600', 'text-white');
        }
    }

    function openEditModal(id, nama, status) {
        const modal = document.getElementById('modalEdit');
        const overlay = document.getElementById('modalOverlay');
        const container = document.getElementById('modalContainer');
        const form = document.getElementById('formUpdateAbsensi');
        
        document.getElementById('modalNamaSiswa').innerText = nama;
        
        const baseUrl = "{{ url('guru/absensi/update') }}";
        form.action = `${baseUrl}/${id}`; 

        const radios = form.querySelectorAll('input[name="status"]');
        radios.forEach(r => {
            r.checked = (r.value.toLowerCase() === status.toLowerCase());
        });

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
        
        requestAnimationFrame(() => {
            overlay.classList.add('opacity-100');
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeEditModal() {
        const modal = document.getElementById('modalEdit');
        const overlay = document.getElementById('modalOverlay');
        const container = document.getElementById('modalContainer');
        
        overlay.classList.remove('opacity-100');
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        document.body.style.overflow = ''; 
        
        setTimeout(() => { 
            modal.classList.add('hidden'); 
        }, 300);
    }

    function handleFormSubmit(e) {
        const btn = document.getElementById('btnUpdate');
        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin h-4 w-4 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
    }
</script>
@endsection