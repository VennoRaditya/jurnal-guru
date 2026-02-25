<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel gurus
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            
            // Kolom-kolom utama sesuai kebutuhan form jurnal
            $table->string('materi_kd');             // Untuk Materi/KD
            $table->text('kegiatan_pembelajaran');   // Untuk detail kegiatan
            $table->text('evaluasi');                // Untuk penilaian
            $table->string('mata_pelajaran');
            $table->string('kelas');
            $table->date('tanggal');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};