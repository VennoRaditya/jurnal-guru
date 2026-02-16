<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;

class UserGuruSeeder extends Seeder
{
    public function run(): void
{
    \App\Models\Guru::create([
        'nama' => 'Pak Andy',
        'nip'  => '12345678',
        'email' => 'admin@gmail.com',
        'password' => bcrypt('password'), // Wajib di-bcrypt!
        'mata_pelajaran' => 'IPAS',
    ]);
}
}