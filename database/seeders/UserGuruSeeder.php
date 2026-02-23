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
        Guru::updateOrCreate(
            ['email' => 'pakandy@gmail.com'], // Cek berdasarkan email
            [
                'username' => 'guru123',
                'nama'     => 'Pak Andy',
                'password' => Hash::make('pakandy123'),
                'nip'      => '123456789',
                'mapel'    => 'Informatika',
            ]
        );

        // Data Bu Riri
        Guru::updateOrCreate(
            ['email' => 'bu.riri@sekolah.sch.id'], // Cek berdasarkan email
            [
                'username' => 'riri123',
                'nama'     => 'Bu Riri',
                'password' => Hash::make('buriri123'),
                'nip'      => '122334455',
                'mapel'    => 'Matematika',
            ]
        );
    }
}