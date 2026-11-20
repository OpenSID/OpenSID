<?php

use App\Http\Controllers\Admin\AcakController;
use App\Http\Controllers\Admin\LaporanPerkembanganController;
use App\Http\Controllers\Admin\SyaratSuratController;
use Illuminate\Support\Facades\Route;

Route::prefix('surat_mohon')->group(static function (): void {
    Route::get('/', [SyaratSuratController::class, 'index'])->middleware(['can:daftar-persyaratan:baca'])->name('surat_mohon.index');
    Route::post('/datatables', [SyaratSuratController::class, 'datatables'])->middleware(['can:daftar-persyaratan:baca'])->name('surat_mohon.datatables');
    Route::get('/form/{syaratSurat?}', [SyaratSuratController::class, 'form'])->middleware(['can:daftar-persyaratan:ubah'])->name('surat_mohon.form');
    Route::post('/insert', [SyaratSuratController::class, 'store'])->middleware(['can:daftar-persyaratan:ubah'])->name('surat_mohon.insert');
    Route::post('/update/{syaratSurat}', [SyaratSuratController::class, 'update'])->middleware(['can:daftar-persyaratan:ubah'])->name('surat_mohon.update');
    Route::get('/delete/{syaratSurat}', [SyaratSuratController::class, 'destroy'])->middleware(['can:daftar-persyaratan:hapus'])->name('surat_mohon.delete');
    Route::get('/deleteAll', [SyaratSuratController::class, 'destroyAll'])->middleware(['can:daftar-persyaratan:hapus'])->name('surat_mohon.delete_all');
});

Route::prefix('laporan-perkembangan')->group(static function (): void {
    Route::get('/', [LaporanPerkembanganController::class, 'index'])->middleware(['can:laporan-perkembangan:baca'])->name('laporan-perkembangan.index');
    Route::get('/data', [LaporanPerkembanganController::class, 'data'])->middleware(['can:laporan-perkembangan:baca'])->name('laporan-perkembangan.data');
});

Route::prefix('acak-data')->group(static function (): void {
    Route::get('/', [AcakController::class, 'index'])->middleware(['can:acak-data:baca'])->name('acak.index');
    Route::get('/jalankan', [AcakController::class, 'jalankan'])->middleware(['can:acak-data:baca'])->name('acak.jalankan');
    Route::get('/status', [AcakController::class, 'status'])->middleware(['can:acak-data:baca'])->name('acak.status');
    Route::get('/hasil', [AcakController::class, 'hasil'])->middleware(['can:acak-data:baca'])->name('acak.hasil');
});
