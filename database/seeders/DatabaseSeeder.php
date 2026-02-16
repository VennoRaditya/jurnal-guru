<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserGuruSeeder::class, // Tambahkan ini agar perintah db:seed jalan
        ]);
    }
}