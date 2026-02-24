<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('username')->unique(); 
            $table->string('nama');
            $table->string('mapel'); 
            // Tambahkan kolom kelas di bawah ini:
            // Kita gunakan text karena akan menyimpan data array/JSON
            $table->text('kelas')->nullable(); 
            $table->string('email')->unique()->nullable(); // nullable jika email tidak wajib
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};