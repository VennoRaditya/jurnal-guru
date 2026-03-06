<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruAuthController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KelasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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
//                 PANEL ADMIN
// ==========================================
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'loginPage'])->name('admin.login');
        Route::post('/login', [AdminController::class, 'loginSubmit'])->name('admin.login.submit');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // GURU MANAGE
        Route::prefix('guru')->name('admin.guru.')->group(function () {
            Route::get('/', [GuruAuthController::class, 'index'])->name('index');
            Route::post('/', [GuruAuthController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [GuruAuthController::class, 'edit'])->name('edit');
            Route::put('/{id}', [GuruAuthController::class, 'update'])->name('update');
            Route::delete('/{id}', [GuruAuthController::class, 'destroy'])->name('destroy');
        });

        // KELAS MANAGE
        Route::prefix('kelas-manage')->name('admin.kelas.')->group(function () {
            Route::get('/', [KelasController::class, 'index'])->name('index');
            Route::post('/store', [KelasController::class, 'store'])->name('store');
            Route::delete('/{kelas}', [KelasController::class, 'destroy'])->name('destroy');
            Route::delete('/{id}/clear', [KelasController::class, 'clearSiswa'])->name('clear-siswa');
            Route::get('/{id}/siswa', [KelasController::class, 'getSiswa'])->name('getSiswa');
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
//                 PANEL GURU
// ==========================================
Route::prefix('guru')->group(function () {
    Route::middleware('guest:guru')->group(function () {
        Route::get('/login', [GuruAuthController::class, 'loginForm'])->name('guru.login');
        Route::post('/login', [GuruAuthController::class, 'login'])->name('guru.login.submit');
    });

    Route::middleware('auth:guru')->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('guru.dashboard');

        // --- MANAJEMEN ABSENSI (INPUT, REKAP & EXPORT) ---
        Route::prefix('absensi')->name('guru.absensi.')->group(function() {
            // Input Presensi Baru
            Route::get('/pilih-kelas', [AbsensiController::class, 'selectClass'])->name('select');
            Route::get('/isi-jurnal', [AbsensiController::class, 'create'])->name('create');
            Route::post('/store', [AbsensiController::class, 'storeJurnal'])->name('storeJurnal');

            // Rekap & Update Status
            Route::get('/rekap', [AbsensiController::class, 'rekapHarian'])->name('rekap');
            Route::put('/update/{id}', [AbsensiController::class, 'updateAbsensi'])->name('update');
            
            // Fitur Cetak & Export
            Route::get('/export-excel', [AbsensiController::class, 'exportExcel'])->name('exportExcel');
            
            // PDF Landscape (Rekap Lengkap Jurnal + Absen)
            Route::get('/rekap-pdf', [AbsensiController::class, 'rekapPdf'])->name('rekapPdf'); 
            Route::get('/cetak-pdf', [AbsensiController::class, 'rekapPdf'])->name('cetakPdf'); 
            
            // PDF Portrait (Khusus Rekapitulasi Ketidakhadiran S, I, A, T)
            Route::get('/cetak-portrait', [AbsensiController::class, 'absensiPdf'])->name('absensiOnlyPdf'); 
        });

        // --- MANAJEMEN MATERI / RIWAYAT JURNAL ---
        Route::prefix('materi')->name('guru.materi.')->group(function () {
            Route::get('/', [AbsensiController::class, 'index'])->name('index'); 
            Route::get('/cetak', [AbsensiController::class, 'rekapPdf'])->name('cetak');
            Route::get('/{id}', [MateriController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [MateriController::class, 'edit'])->name('edit'); 
            Route::put('/{id}', [MateriController::class, 'update'])->name('update'); 
            Route::delete('/{id}', [AbsensiController::class, 'destroy'])->name('destroy'); 
        });

        // ============================================================
        //  ALIAS / FALLBACK ROUTES (MENCEGAH ERROR "Route Not Found")
        // ============================================================
        
        // Membungkus alias dalam satu name group "guru.presensi."
        Route::name('guru.presensi.')->group(function () {
            // Mengatasi Error: Route [guru.presensi.create] not defined
            Route::get('/absensi/isi-jurnal/create-alias', [AbsensiController::class, 'create'])->name('create');
            
            // Mengatasi Error: Route [guru.presensi.select] not defined
            Route::get('/absensi/pilih-kelas/select-alias', [AbsensiController::class, 'selectClass'])->name('select');

            // Mengatasi Error: Route [guru.presensi.storeJurnal] not defined
            Route::post('/absensi/store/alias', [AbsensiController::class, 'storeJurnal'])->name('storeJurnal');
        });

        // Alias tambahan untuk cetak jika dipanggil dari luar prefix absensi
        Route::get('/cetak-pdf-rekap-absen', [AbsensiController::class, 'absensiPdf'])->name('guru.absensi.cetakPdf');

        Route::post('/logout', [GuruAuthController::class, 'logout'])->name('guru.logout');
    });
});