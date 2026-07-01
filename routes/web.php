<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\PinjamanController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Admin\ItemSeragamController;
use App\Http\Controllers\Admin\RatController;
use App\Http\Controllers\Admin\SeragamAdminController;
use App\Http\Controllers\Admin\SiswaBaruController;
use App\Http\Controllers\Anggota\AnggotaDashboardController;
use App\Http\Controllers\Anggota\PengajuanAnggotaController;
use App\Http\Controllers\Anggota\SisaPinjamanController;
use App\Http\Controllers\Siswa\SeragamController;

// Root
Route::get('/', fn() => redirect('/login'));

// Auth Breeze
require __DIR__ . '/auth.php';

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === ADMIN ===
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Anggota
        Route::resource('anggota', AnggotaController::class);
        Route::get('anggota-export', [AnggotaController::class, 'export'])->name('anggota.export');


        // Pinjaman
        Route::resource('pinjaman', PinjamanController::class);
        Route::post('pinjaman/angsuran/{angsuran}/bayar', [PinjamanController::class, 'bayarAngsuran'])->name('pinjaman.bayar');

        // Pengajuan
        Route::get('pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::post('pengajuan/{id}/setujui', [PengajuanController::class, 'setujui'])->name('pengajuan.setujui');
        Route::post('pengajuan/{id}/tolak', [PengajuanController::class, 'tolak'])->name('pengajuan.tolak');
        Route::get('pengajuan/{id}/cetak', [PengajuanController::class, 'cetakFormPengajuan'])->name('pengajuan.cetak');
        Route::get('pengajuan/{id}', [PengajuanController::class, 'show'])->name('pengajuan.show');

        // RAT
        Route::resource('rat', RatController::class);
        Route::get('rat/{rat}/pdf', [RatController::class, 'cetakPdf'])->name('rat.pdf');

        // Siswa Baru
        Route::resource('siswa-baru', SiswaBaruController::class);

        
        // Seragam - urutan penting! spesifik dulu baru wildcard
        Route::get('seragam/export', [SeragamAdminController::class, 'export'])->name('seragam.export');
        Route::get('seragam/index', [SeragamAdminController::class, 'index'])->name('seragam.index');
        Route::get('seragam/{seragam}', [SeragamAdminController::class, 'show'])
            ->name('seragam.show');
        Route::post('seragam/{seragam}/validasi', [SeragamAdminController::class, 'validasi'])
            ->name('seragam.validasi');
        Route::get('seragam/{seragam}/kwitansi', [SeragamAdminController::class, 'cetakKwitansi'])
            ->name('seragam.kwitansi');

        // Item Seragam
        Route::resource('item-seragam', ItemSeragamController::class);
    });

// === ANGGOTA ===
Route::middleware(['auth', 'role:anggota'])
    ->prefix('anggota')
    ->name('anggota.')
    ->group(function () {
        Route::get('/dashboard', [AnggotaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/sisa-pinjaman', [SisaPinjamanController::class, 'index'])->name('sisa-pinjaman');
        Route::resource('pengajuan', PengajuanAnggotaController::class)
            ->only(['index', 'create', 'store', 'show']);
    });

// === SISWA BARU ===
Route::middleware(['auth', 'role:siswa_baru'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('seragam/tagihan', [SeragamController::class, 'tagihan'])->name('seragam.tagihan');
        Route::post('seragam/upload-bukti', [SeragamController::class, 'uploadBukti'])->name('seragam.upload');
    });