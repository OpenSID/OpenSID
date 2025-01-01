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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

// BACKEND
// Kehadiran > Jam Kerja
Route::group('kehadiran_jam_kerja', ['namespace' => 'Kehadiran/BackEnd'], static function (): void {
    Route::get('/', 'JamKerjaController@index')->name('kehadiran_jam_kerja.index');
    Route::get('/datatables', 'JamKerjaController@datatables')->name('kehadiran_jam_kerja.datatables');
    Route::get('/form/{id}', 'JamKerjaController@form')->name('kehadiran_jam_kerja.form');
    Route::post('/update/{id}', 'JamKerjaController@update')->name('kehadiran_jam_kerja.update');
});

// Kehadiran > Hari Libur
Route::group('kehadiran_hari_libur', ['namespace' => 'Kehadiran/BackEnd'], static function (): void {
    Route::get('/', 'HariLiburController@index')->name('kehadiran_hari_libur.index');
    Route::get('/datatables', 'HariLiburController@datatables')->name('kehadiran_hari_libur.datatables');
    Route::get('/form/{id?}', 'HariLiburController@form')->name('kehadiran_hari_libur.form');
    Route::post('/create', 'HariLiburController@create')->name('kehadiran_hari_libur.create');
    Route::post('/update/{id}', 'HariLiburController@update')->name('kehadiran_hari_libur.update');
    Route::get('/delete/{id}', 'HariLiburController@delete')->name('kehadiran_hari_libur.delete');
    Route::post('/delete_all', 'HariLiburController@delete_all')->name('kehadiran_hari_libur.delete_all');
    Route::get('/import', 'HariLiburController@import')->name('kehadiran_hari_libur.import');
});

// Kehadiran > Rekapitulasi
Route::group('kehadiran_rekapitulasi', ['namespace' => 'Kehadiran/BackEnd'], static function (): void {
    Route::get('/', 'RekapitulasiController@index')->name('kehadiran_rekapitulasi.index');
    Route::get('/datatables', 'RekapitulasiController@datatables')->name('kehadiran_rekapitulasi.datatables');
    Route::get('/ekspor', 'RekapitulasiController@ekspor')->name('kehadiran_rekapitulasi.ekspor');
});

// Kehadiran > Pengaduan
Route::group('kehadiran_pengaduan', ['namespace' => 'Kehadiran/BackEnd'], static function (): void {
    Route::get('/', 'PengaduanController@index')->name('kehadiran_pengaduan.index');
    Route::get('/datatables', 'PengaduanController@datatables')->name('kehadiran_pengaduan.datatables');
    Route::get('/form/{id}', 'PengaduanController@form')->name('kehadiran_pengaduan.form');
    Route::post('/update/{id}', 'PengaduanController@update')->name('kehadiran_pengaduan.update');
});

// Kehadiran > Alasan Keluar
Route::group('kehadiran_keluar', ['namespace' => 'Kehadiran/BackEnd'], static function (): void {
    Route::get('/', 'AlasanKeluarController@index')->name('kehadiran_keluar.index');
    Route::get('/datatables', 'AlasanKeluarController@datatables')->name('kehadiran_keluar.datatables');
    Route::get('/form/{id?}', 'AlasanKeluarController@form')->name('kehadiran_keluar.form');
    Route::post('/create', 'AlasanKeluarController@create')->name('kehadiran_keluar.create');
    Route::post('/update/{id}', 'AlasanKeluarController@update')->name('kehadiran_keluar.update');
    Route::get('/delete/{id}', 'AlasanKeluarController@delete')->name('kehadiran_keluar.delete');
    Route::post('/delete_all', 'AlasanKeluarController@delete_all')->name('kehadiran_keluar.delete_all');
});

// FRONTEND
Route::group('kehadiran', ['namespace' => 'Kehadiran/FrontEnd'], static function (): void {
    Route::get('/', 'PerangkatController@index')->name('kehadiran.perangkat.index');
    Route::post('/cek/{ektp?}', 'PerangkatController@cek')->name('kehadiran.perangkat.cek');
    Route::get('/masuk-ektp', 'PerangkatController@masukEktp')->name('kehadiran.perangkat.masukEktp');
    Route::post('/cek-ektp', 'PerangkatController@cekEktp')->name('kehadiran.perangkat.cekEktp');
    Route::get('/masuk', 'PerangkatController@masuk')->name('kehadiran.perangkat.masuk');
    Route::match(['GET', 'POST'], '/check-in-out', 'PerangkatController@checkInOut')->name('kehadiran.perangkat.checkInOut');
    Route::get('/logout', 'PerangkatController@logout')->name('kehadiran.perangkat.logout');
});
