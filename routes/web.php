<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruAuthController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SiswaController;

// 1. Redirect Halaman Utama
Route::get('/', function () {
    if (auth()->guard('guru')->check()) {
        return redirect()->route('guru.dashboard');
    }
    return redirect()->route('login');
});

// 2. Grouping Route Guru
Route::prefix('guru')->group(function () {
    
    // Guest Only: Khusus untuk guru yang BELUM login
    Route::middleware('guest:guru')->group(function () {
        // Diberikan nama 'login' agar dikenali otomatis oleh middleware auth Laravel
        Route::get('/login', [GuruAuthController::class, 'loginForm'])->name('login');
        Route::get('/login-guru', [GuruAuthController::class, 'loginForm'])->name('guru.login'); // Alias tetap ada
        Route::post('/login', [GuruAuthController::class, 'login'])->name('guru.login.submit');
    });

    // Auth Only: Khusus untuk guru yang SUDAH login
    Route::middleware('auth:guru')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('guru.dashboard');
        
        // --- FITUR SATU PINTU ---
        Route::get('/presensi/kelas/{kelas}', [AbsensiController::class, 'showByKelas'])->name('guru.presensi.kelas');
        Route::post('/jurnal/store', [AbsensiController::class, 'storeJurnal'])->name('guru.absensi.storeJurnal');

        // --- MANAJEMEN SISWA ---
        Route::prefix('siswa')->name('guru.siswa.')->group(function () {
            Route::get('/', [SiswaController::class, 'index'])->name('index');
            Route::post('/store', [SiswaController::class, 'store'])->name('store');
            Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('destroy');
        });

        // --- ARSIP MATERI ---
        Route::prefix('materi')->name('guru.materi.')->group(function () {
            Route::get('/', [MateriController::class, 'index'])->name('index'); 
            Route::get('/{id}/edit', [MateriController::class, 'edit'])->name('edit'); 
            Route::put('/{id}', [MateriController::class, 'update'])->name('update'); 
            Route::delete('/{id}', [MateriController::class, 'destroy'])->name('destroy'); 
        });

        // --- RIWAYAT ABSENSI ---
        Route::get('/absensi/riwayat', [AbsensiController::class, 'index'])->name('guru.absensi.index');
        
        // Logout
        Route::post('/logout', [GuruAuthController::class, 'logout'])->name('guru.logout');
    });
});