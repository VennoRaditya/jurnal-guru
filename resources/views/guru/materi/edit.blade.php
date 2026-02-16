    @extends('layouts.app')

@section('title', 'Edit Materi')
@section('header_title', 'Perbarui Jurnal')
@section('header_subtitle', 'Lakukan perubahan pada riwayat materi pembelajaran.')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
        <form action="{{ route('guru.materi.update', $materi->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Mata Pelajaran</label>
                    <input type="text" value="{{ $materi->mata_pelajaran }}" disabled 
                           class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-semibold text-slate-400 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Judul Materi</label>
                    <input type="text" name="judul_materi" value="{{ $materi->judul_materi }}" required
                           class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Ringkasan Pembahasan</label>
                <textarea name="pembahasan" rows="6" required
                          class="w-full bg-slate-50 border-none rounded-4xl p-6 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none resize-none">{{ $materi->pembahasan }}</textarea>
            </div>

            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('guru.materi.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition uppercase tracking-widest">Batal</a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-10 py-4 rounded-2xl text-xs font-bold uppercase tracking-[0.2em] hover:bg-slate-900 transition-all shadow-xl shadow-blue-500/10">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection