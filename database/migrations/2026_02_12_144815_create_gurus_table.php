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
            
            // Perbaikan: Gunakan json() agar lebih optimal untuk data array dari checkbox
            // Jika database Anda versi sangat lama, bisa tetap pakai text()
            $table->json('kelas')->nullable(); 
            
            $table->string('email')->unique()->nullable(); 
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