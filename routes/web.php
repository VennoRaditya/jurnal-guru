<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruAuthController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KelasController;

// --- REDIRECT UTAMA ---
Route::get('/', function () {
    if (auth()->guard('guru')->check()) {
        return redirect()->route('guru.dashboard');
    }
    if (auth()->guard('web')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('guru.login'); 
});

Route::get('/login', function() {
    return redirect()->route('guru.login');
})->name('login');

// ==========================================
//                PANEL ADMIN
// ==========================================
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'loginPage'])->name('admin.login');
        Route::post('/login', [AdminController::class, 'loginSubmit'])->name('admin.login.submit');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // GURU MANAGE
        Route::prefix('guru-manage')->name('admin.guru.')->group(function () {
            Route::get('/', [GuruAuthController::class, 'index'])->name('index');
            Route::post('/store', [GuruAuthController::class, 'store'])->name('store');
            Route::delete('/{id}', [GuruAuthController::class, 'destroy'])->name('destroy');
        });

        // KELAS MANAGE
        Route::prefix('kelas-manage')->name('admin.kelas.')->group(function () {
            Route::get('/', [KelasController::class, 'index'])->name('index');
            Route::post('/store', [KelasController::class, 'store'])->name('store');
            Route::delete('/{id}', [KelasController::class, 'destroy'])->name('destroy'); // Parameter konsisten pakai {id}
            
            // Route untuk Fetch data siswa (Alpine.js)
            Route::get('/{id}/siswa', [SiswaController::class, 'getSiswaByKelas'])->name('getSiswa');
        });

        // SISWA MANAGE
        Route::prefix('siswa-manage')->name('admin.siswa.')->group(function () {
            Route::get('/', [SiswaController::class, 'index'])->name('index');
            Route::get('/template/download', [SiswaController::class, 'downloadTemplate'])->name('template');
            Route::post('/import/excel', [SiswaController::class, 'import'])->name('import');
            Route::post('/store', [SiswaController::class, 'store'])->name('store');
            Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('destroy');
        });

        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});

// ==========================================
//                PANEL GURU
// ==========================================
Route::prefix('guru')->group(function () {
    Route::middleware('guest:guru')->group(function () {
        Route::get('/login', [GuruAuthController::class, 'loginForm'])->name('guru.login');
        Route::post('/login', [GuruAuthController::class, 'login'])->name('guru.login.submit');
    });

    Route::middleware('auth:guru')->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('guru.dashboard');
        Route::get('/siswa', [SiswaController::class, 'index'])->name('guru.siswa.index');

        Route::prefix('presensi')->name('guru.presensi.')->group(function () {
            Route::get('/pilih-kelas', [AbsensiController::class, 'selectClass'])->name('select');
            Route::get('/isi-jurnal', [AbsensiController::class, 'create'])->name('create');
            Route::post('/store', [AbsensiController::class, 'storeJurnal'])->name('storeJurnal');
        });

        Route::get('/riwayat-materi', [AbsensiController::class, 'index'])->name('guru.materi.index');
        Route::get('/arsip-absen', [AbsensiController::class, 'index'])->name('guru.absensi.index');

        Route::prefix('materi-manage')->name('guru.materi.')->group(function () {
            Route::get('/{id}/edit', [MateriController::class, 'edit'])->name('edit'); 
            Route::put('/{id}', [MateriController::class, 'update'])->name('update'); 
            Route::delete('/{id}', [MateriController::class, 'destroy'])->name('destroy'); 
        });

        Route::post('/logout', [GuruAuthController::class, 'logout'])->name('guru.logout');
    });
});