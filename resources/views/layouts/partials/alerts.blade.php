{{-- 1. Alert Sukses (Contoh: Berhasil Import, Berhasil Simpan) --}}
@if (session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm animate-[fadeIn_0.5s_ease-out]">
        <div class="flex-shrink-0 w-8 h-8 bg-emerald-500 rounded-xl flex items-center justify-center shadow-sm shadow-emerald-200">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-500 leading-none mb-1">Berhasil</span>
            <span class="text-sm font-bold leading-tight">{{ session('success') }}</span>
        </div>
    </div>
@endif

{{-- 2. Alert Error Tunggal (Contoh: Gagal Login, File Rusak) --}}
@if (session('error'))
    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center gap-3 shadow-sm animate-[fadeIn_0.5s_ease-out]">
        <div class="flex-shrink-0 w-8 h-8 bg-rose-500 rounded-xl flex items-center justify-center shadow-sm shadow-rose-200">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="text-[10px] font-black uppercase tracking-widest text-rose-500 leading-none mb-1">Terjadi Kesalahan</span>
            <span class="text-sm font-bold leading-tight">{{ session('error') }}</span>
        </div>
    </div>
@endif

{{-- 3. Alert Validasi Form (Contoh: NIS sudah ada, Input kosong) --}}
@if ($errors->any())
    <div class="mb-6 p-5 bg-amber-50 border border-amber-100 text-amber-800 rounded-2xl shadow-sm animate-[fadeIn_0.5s_ease-out]">
        <div class="flex items-center gap-3 mb-3">
            <div class="flex-shrink-0 w-8 h-8 bg-amber-500 rounded-xl flex items-center justify-center shadow-sm shadow-amber-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <span class="text-[11px] font-black uppercase tracking-[0.2em] text-amber-600">Periksa Form Anda</span>
        </div>
        <ul class="space-y-1 ml-11">
            @foreach ($errors->all() as $error)
                <li class="text-xs font-bold flex items-center gap-2">
                    <span class="w-1 h-1 bg-amber-400 rounded-full"></span>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>