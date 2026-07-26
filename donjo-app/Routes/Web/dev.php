<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

// Rute panel pengembangan "Sumber Modul" — HANYA di lingkungan development.
// Berkas ini di-export-ignore (tak ikut rilis); guard ganda demi aman.
if ((defined('ENVIRONMENT') ? constant('ENVIRONMENT') : null) === 'development') {
    Route::group('dev-modul', static function (): void {
        Route::get('/', 'Dev_modul@index')->name('dev_modul.index');
        Route::get('/katalog', 'Dev_modul@katalog')->name('dev_modul.katalog');
        Route::post('/sumber', 'Dev_modul@sumber')->name('dev_modul.sumber');
        Route::post('/daftar', 'Dev_modul@daftar')->name('dev_modul.daftar');
        Route::post('/daftar-lokal', 'Dev_modul@daftarLokal')->name('dev_modul.daftar_lokal');
        Route::post('/batal-daftar', 'Dev_modul@batalDaftar')->name('dev_modul.batal_daftar');
        Route::post('/simulasi-langganan', 'Dev_modul@simulasiLangganan')->name('dev_modul.simulasi_langganan');
        Route::post('/kosongkan-langganan', 'Dev_modul@kosongkanLangganan')->name('dev_modul.kosongkan_langganan');
    });

    // Emulator server Layanan lokal — dituju saat "Sumber paket" = bursa lokal
    // (base-URL dialihkan oleh PengalihLayananLokal). Publik (self-HTTP modul tak
    // membawa sesi admin); `Route::any` agar terima GET (browser) & POST (klien).
    Route::group('layanan-lokal/api/v1', static function (): void {
        Route::any('pelanggan/pemesanan', 'Layanan_lokal@pemesanan');
        Route::any('pelanggan/pemesanan/faktur', 'Layanan_lokal@faktur');
        Route::any('pelanggan/pemesanan/deskripsi-faktur', 'Layanan_lokal@faktur');
        Route::any('pelanggan/perpanjang', 'Layanan_lokal@perpanjang');
        Route::any('pelanggan/daftarhitam', 'Layanan_lokal@daftarhitam');
        Route::any('pelanggan/catat-versi', 'Layanan_lokal@catatVersi');
        Route::any('pelanggan/terdaftar', 'Layanan_lokal@terdaftar');
        Route::any('pelanggan/form-register', 'Layanan_lokal@formRegister');
        Route::any('pelanggan/register', 'Layanan_lokal@register');
    });
}
