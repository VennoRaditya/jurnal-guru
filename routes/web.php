<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruAuthController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    if (auth()->guard('guru')->check()) {
        return redirect()->route('guru.dashboard');
    }
    return redirect()->route('login');
});

Route::prefix('guru')->group(function () {
    
    // Guest Only
    Route::middleware('guest:guru')->group(function () {
        Route::get('/login', [GuruAuthController::class, 'loginForm'])->name('login');
        Route::post('/login', [GuruAuthController::class, 'login'])->name('guru.login.submit');
    });

    // Auth Only
    Route::middleware('auth:guru')->group(function () {
        
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('guru.dashboard');
        
        // --- FITUR PRESENSI (Folder: guru/absensi) ---
        Route::prefix('presensi')->name('guru.presensi.')->group(function () {
            // Ini yang dipanggil Sidebar (Pilih Kelas)
            Route::get('/pilih-kelas', [AbsensiController::class, 'selectClass'])->name('select');
            // Ini yang dipanggil Form Select (Input Jurnal)
            Route::get('/isi-jurnal', [AbsensiController::class, 'create'])->name('create');
            Route::post('/store', [AbsensiController::class, 'storeJurnal'])->name('storeJurnal');
        });

        // --- MANAJEMEN SISWA ---
        Route::prefix('siswa')->name('guru.siswa.')->group(function () {
            Route::get('/', [SiswaController::class, 'index'])->name('index');
            Route::post('/store', [SiswaController::class, 'store'])->name('store');
            Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('destroy');
        });

        // --- ARSIP & RIWAYAT (Folder: guru/materi) ---
        // Perbaikan typo: 'materai' jadi 'materi'
        Route::get('/riwayat-materi', [AbsensiController::class, 'index'])->name('guru.materi.index');
        // Alias tambahan supaya link lama di sidebar lo (guru.absensi.index) nggak error
        Route::get('/arsip-absen', [AbsensiController::class, 'index'])->name('guru.absensi.index');

        // CRUD Materi
        Route::prefix('materi-manage')->name('guru.materi.')->group(function () {
            Route::get('/{id}/edit', [MateriController::class, 'edit'])->name('edit'); 
            Route::put('/{id}', [MateriController::class, 'update'])->name('update'); 
            Route::delete('/{id}', [MateriController::class, 'destroy'])->name('destroy'); 
        });

        Route::post('/logout', [GuruAuthController::class, 'logout'])->name('guru.logout');
    });
});