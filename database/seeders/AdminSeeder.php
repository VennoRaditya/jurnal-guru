<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Menggunakan updateOrCreate agar tidak error jika data sudah ada
        User::updateOrCreate(
            ['email' => 'admin@sekolah.sch.id'], // Kolom unik sebagai kunci pencarian
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}