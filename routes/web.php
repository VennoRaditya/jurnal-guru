<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruAuthController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AdminController;

// --- REDIRECT UTAMA ---
Route::get('/', function () {
    if (auth()->guard('guru')->check()) {
        return redirect()->route('guru.dashboard');
    }
    if (auth()->guard('web')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// ==========================================
//               PANEL ADMIN
// ==========================================
Route::prefix('admin')->group(function () {
    
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'loginPage'])->name('admin.login');
        Route::post('/login', [AdminController::class, 'loginSubmit'])->name('admin.login.submit');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Manajemen Guru
        Route::prefix('guru-manage')->name('admin.guru.')->group(function () {
            Route::get('/', [AdminController::class, 'guruIndex'])->name('index');
            Route::post('/store', [AdminController::class, 'guruStore'])->name('store');
            Route::delete('/{id}', [AdminController::class, 'guruDestroy'])->name('destroy');
        });

        // Manajemen Siswa (Full Akses di Admin)
        Route::prefix('siswa-manage')->name('admin.siswa.')->group(function () {
            Route::get('/', [AdminController::class, 'siswaIndex'])->name('index');
            Route::post('/store', [AdminController::class, 'siswaStore'])->name('store');
            Route::delete('/{id}', [AdminController::class, 'siswaDestroy'])->name('destroy');
        });

        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});

// ==========================================
//               PANEL GURU
// ==========================================
Route::prefix('guru')->group(function () {
    
    Route::middleware('guest:guru')->group(function () {
        Route::get('/login', [GuruAuthController::class, 'loginForm'])->name('login');
        Route::post('/login', [GuruAuthController::class, 'login'])->name('guru.login.submit');
    });

    Route::middleware('auth:guru')->group(function () {
        
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('guru.dashboard');
        
        // --- DATA SISWA (Hanya Lihat - Read Only) ---
        // Menambahkan rute ini agar error Route NotFound hilang
        Route::get('/siswa', [AdminController::class, 'siswaIndex'])->name('guru.siswa.index');

        // --- FITUR PRESENSI ---
        Route::prefix('presensi')->name('guru.presensi.')->group(function () {
            Route::get('/pilih-kelas', [AbsensiController::class, 'selectClass'])->name('select');
            Route::get('/isi-jurnal', [AbsensiController::class, 'create'])->name('create');
            Route::post('/store', [AbsensiController::class, 'storeJurnal'])->name('storeJurnal');
        });

        // --- ARSIP & RIWAYAT ---
        Route::get('/riwayat-materi', [AbsensiController::class, 'index'])->name('guru.materi.index');
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