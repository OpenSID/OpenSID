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
        Route::post('/batal-daftar', 'Dev_modul@batalDaftar')->name('dev_modul.batal_daftar');
    });
}
