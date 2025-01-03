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
// Anjungan > Daftar Anjungan
Route::group('anjungan', ['namespace' => 'Anjungan/BackEnd'], static function (): void {
    Route::get('/', 'AnjunganController@index')->name('admin.anjungan.index');
    Route::get('/datatables', 'AnjunganController@datatables')->name('admin.anjungan.datatables');
    Route::get('/form/{id?}', 'AnjunganController@form')->name('admin.anjungan.form');
    Route::post('/insert', 'AnjunganController@insert')->name('admin.anjungan.insert');
    Route::post('/update/{id?}', 'AnjunganController@update')->name('admin.anjungan.update');
    Route::get('/delete/{id?}', 'AnjunganController@delete')->name('admin.anjungan.delete');
    Route::post('/delete', 'AnjunganController@delete')->name('admin.anjungan.delete-all');
    Route::get('/kunci/{id?}/{val?}', 'AnjunganController@kunci')->name('admin.anjungan.kunci');
});

// Anjungan > Menu
Route::group('anjungan_menu', ['namespace' => 'Anjungan/BackEnd'], static function (): void {
    Route::get('/', 'AnjunganMenuController@index')->name('anjungan_menu.index');
    Route::get('/datatables', 'AnjunganMenuController@datatables')->name('anjungan_menu.datatables');
    Route::get('/form/{id?}', 'AnjunganMenuController@form')->name('anjungan_menu.form');
    Route::post('/insert', 'AnjunganMenuController@insert')->name('anjungan_menu.insert');
    Route::post('/update/{id?}', 'AnjunganMenuController@update')->name('anjungan_menu.update');
    Route::get('/delete/{id?}', 'AnjunganMenuController@delete')->name('anjungan_menu.delete');
    Route::post('/delete', 'AnjunganMenuController@delete')->name('anjungan_menu.delete-all');
    Route::get('/lock/{id?}', 'AnjunganMenuController@lock')->name('anjungan_menu.lock');
    Route::post('/tukar', 'AnjunganMenuController@tukar')->name('anjungan_menu.tukar');
});

// Anjungan > Pengaturan
Route::group('anjungan_pengaturan', ['namespace' => 'Anjungan/BackEnd'], static function (): void {
    Route::get('/', 'AnjunganPengaturanController@index')->name('anjungan_pengaturan.index');
    Route::post('/update', 'AnjunganPengaturanController@update')->name('anjungan_pengaturan.update');
});

// FRONTEND
Route::group('anjungan-mandiri', ['namespace' => 'Anjungan/FrontEnd'], static function (): void {
    Route::get('/', 'AnjunganController@index')->name('anjungan.index');
    Route::get('/beranda', 'AnjunganBerandaController@index')->name('anjungan.beranda.index');
    Route::get('/surat/{id?}', 'AnjunganSuratController@buat')->name('anjungan.surat');
    Route::get('/surat/form/{id?}', 'AnjunganSuratController@form')->name('anjungan.surat.form');
    Route::post('/surat/kirim', 'AnjunganSuratController@kirim')->name('anjungan.surat.kirim');
    Route::get('/permohonan', 'AnjunganSuratController@permohonan')->name('anjungan.permohonan');
});
