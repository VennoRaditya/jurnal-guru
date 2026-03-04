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
        Schema::table('materis', function (Blueprint $table) {
            // Tambahkan kolom kelas_id sebagai foreign key
            // nullable() digunakan agar data jurnal yang sudah ada sebelumnya tidak error
            $table->unsignedBigInteger('kelas_id')->nullable()->after('guru_id');

            // Jika Anda ingin membuat relasi foreign key secara formal ke tabel kelas:
            // $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            // Hapus foreign key jika tadi dibuat
            // $table->dropForeign(['kelas_id']);
            
            // Hapus kolom kelas_id
            $table->dropColumn('kelas_id');
        });
    }
};