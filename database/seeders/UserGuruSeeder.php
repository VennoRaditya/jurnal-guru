<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;

class UserGuruSeeder extends Seeder
{
    public function run(): void
    {
        // Data Pak Andy
        Guru::create([
            'username' => 'guru123',
            'nama'     => 'Pak Andy',
            'email'    => 'pakandy@gmail.com',
            'password' => Hash::make('password123'),
            'nip'      => '123456789',
            'mapel'    => 'Informatika',
        ]);

        // Data Bu Riri
        Guru::create([
            'username' => 'riri123',
            'nama'     => 'Bu Riri',
            'email'    => 'bu.riri@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'nip'      => '122334455',
            'mapel'    => 'Matematika',
        ]);
    }
}