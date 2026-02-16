@extends('layouts.app')

@section('title', 'Panel Admin Guru')

@section('header_title', 'Panel Admin Guru')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-blue-700 mb-1">Manajemen Guru</h1>
        <p class="text-slate-500 text-sm">Kelola data guru, edit, tambah, dan hapus dengan mudah.</p>
    </div>
    <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-gradient-to-r from-blue-600 to-blue-400 text-white px-6 py-2 rounded-lg font-bold shadow hover:from-blue-700 hover:to-blue-500 transition">Tambah Guru</button>
</div>

<div class="bg-white rounded-2xl shadow-lg p-6 overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-blue-50">
            <tr>
                <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase">Nama</th>
                <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase">NIP</th>
                <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase">Email</th>
                <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase">Mata Pelajaran</th>
                <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <tr class="bg-yellow-50 font-bold">
                <td class="px-4 py-3">Admin 1</td>
                <td class="px-4 py-3">-</td>
                <td class="px-4 py-3">admin1gmail.com</td>
                <td class="px-4 py-3">-</td>
                <td class="px-4 py-3 text-slate-500">Password: admin 1</td>
            </tr>
            @forelse($gurus as $guru)
            <tr class="hover:bg-blue-50/30 transition">
                <td class="px-4 py-3 font-semibold">{{ $guru->nama }}</td>
                <td class="px-4 py-3">{{ $guru->nip }}</td>
                <td class="px-4 py-3">{{ $guru->email }}</td>
                <td class="px-4 py-3">{{ $guru->mata_pelajaran }}</td>
                <td class="px-4 py-3 flex gap-2">
                    <button onclick="editGuru(@json($guru))" class="bg-yellow-400 text-white px-3 py-1 rounded shadow hover:bg-yellow-500">Edit</button>
                    <form action="{{ route('guru.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Yakin hapus guru ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-rose-600 text-white px-3 py-1 rounded shadow hover:bg-rose-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-slate-400 py-8">Belum ada data guru.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Guru -->
<div id="modal-tambah" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 w-full max-w-md relative shadow-xl">
        <button onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="absolute top-2 right-2 text-slate-400 hover:text-slate-700 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-blue-700">Tambah Guru</h2>
        <form action="{{ route('guru.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="nama" placeholder="Nama" class="w-full border border-slate-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
            <input type="text" name="nip" placeholder="NIP" class="w-full border border-slate-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
            <input type="email" name="email" placeholder="Email" class="w-full border border-slate-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
            <input type="text" name="mata_pelajaran" placeholder="Mata Pelajaran" class="w-full border border-slate-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
            <input type="password" name="password" placeholder="Password" class="w-full border border-slate-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-400 text-white px-4 py-2 rounded-lg font-bold hover:from-blue-700 hover:to-blue-500 transition">Simpan</button>
        </form>
    </div>
</div>

<!-- Modal Edit Guru (akan diisi via JS) -->
<div id="modal-edit" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 w-full max-w-md relative shadow-xl">
        <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="absolute top-2 right-2 text-slate-400 hover:text-slate-700 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-blue-700">Edit Guru</h2>
        <form id="form-edit-guru" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="text" name="nama" id="edit-nama" class="w-full border border-slate-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
            <input type="text" name="nip" id="edit-nip" class="w-full border border-slate-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
            <input type="email" name="email" id="edit-email" class="w-full border border-slate-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
            <input type="text" name="mata_pelajaran" id="edit-mata_pelajaran" class="w-full border border-slate-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
            <input type="password" name="password" id="edit-password" class="w-full border border-slate-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" placeholder="Kosongkan jika tidak ganti">
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-400 text-white px-4 py-2 rounded-lg font-bold hover:from-blue-700 hover:to-blue-500 transition">Update</button>
        </form>
    </div>
</div>

<script>
function editGuru(guru) {
    document.getElementById('modal-edit').classList.remove('hidden');
    document.getElementById('edit-nama').value = guru.nama;
    document.getElementById('edit-nip').value = guru.nip;
    document.getElementById('edit-email').value = guru.email;
    document.getElementById('edit-mata_pelajaran').value = guru.mata_pelajaran;
    document.getElementById('edit-password').value = '';
    document.getElementById('form-edit-guru').action = '/guru/' + guru.id;
}
</script>
@endsection
