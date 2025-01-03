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

// FRONTEND
Route::group('buku-tamu', ['namespace' => 'BukuTamu/FrontEnd'], static function (): void {
    Route::get('/', 'BukuTamuController@index')->name('fweb.buku_tamu.index');
    Route::post('/registrasi', 'BukuTamuController@registrasi')->name('fweb.buku_tamu.registrasi');
    Route::get('/kepuasan/{id?}', 'BukuTamuController@kepuasan')->name('fweb.buku_tamu.kepuasan');
    Route::match(['GET', 'POST'], '/jawaban/{id?}/{jawaban?}', 'BukuTamuController@jawaban')->name('fweb.buku_tamu.jawaban');
});

// BACKEND
// Tamu
Route::group('buku_tamu', ['namespace' => 'BukuTamu/BackEnd'], static function (): void {
    Route::get('/', 'TamuController@index')->name('buku_tamu.index');
    Route::get('/edit/{id}', 'TamuController@edit')->name('buku_tamu.edit');
    Route::post('/update/{id}', 'TamuController@update')->name('buku_tamu.update');
    Route::get('/delete/{id?}', 'TamuController@delete')->name('buku_tamu.delete');
    Route::post('/deleteAll', 'TamuController@delete')->name('buku_tamu.delete-all');
    Route::get('/cetak', 'TamuController@cetak')->name('buku_tamu.cetak');
    Route::get('/ekspor', 'TamuController@ekspor')->name('buku_tamu.ekspor');
});

// Kepuasan
Route::group('buku_kepuasan', ['namespace' => 'BukuTamu/BackEnd'], static function (): void {
    Route::get('/', 'KepuasanController@index')->name('buku_kepuasan.index');
    Route::get('/show/{id}', 'KepuasanController@show')->name('buku_kepuasan.show');
    Route::get('/datatables_show/{id}', 'KepuasanController@datatablesShow')->name('buku_kepuasan.datatables-show');
    Route::get('/delete/{id?}', 'KepuasanController@delete')->name('buku_kepuasan.delete');
    Route::post('/deleteAll', 'KepuasanController@deleteAll')->name('buku_kepuasan.delete-all');
});

// Pertanyaan
Route::group('buku_pertanyaan', ['namespace' => 'BukuTamu/BackEnd'], static function (): void {
    Route::get('/', 'PertanyaanController@index')->name('buku_pertanyaan.index');
    Route::get('/form/{id?}', 'PertanyaanController@form')->name('buku_pertanyaan.form');
    Route::post('/insert', 'PertanyaanController@insert')->name('buku_pertanyaan.insert');
    Route::post('/update/{id?}', 'PertanyaanController@update')->name('buku_pertanyaan.update');
    Route::get('/delete/{id?}', 'PertanyaanController@delete')->name('buku_pertanyaan.delete');
    Route::post('/delete', 'PertanyaanController@delete')->name('buku_pertanyaan.delete-all');
});

// Keperluan
Route::group('buku_keperluan', ['namespace' => 'BukuTamu/BackEnd'], static function (): void {
    Route::get('/', 'KeperluanController@index')->name('buku_keperluan.index');
    Route::get('/form/{id?}', 'KeperluanController@form')->name('buku_keperluan.form');
    Route::post('/insert', 'KeperluanController@insert')->name('buku_keperluan.insert');
    Route::post('/update/{id?}', 'KeperluanController@update')->name('buku_keperluan.update');
    Route::get('/delete/{id?}', 'KeperluanController@delete')->name('buku_keperluan.delete');
    Route::post('/delete', 'KeperluanController@delete')->name('buku_keperluan.delete-all');
});
