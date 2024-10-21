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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

Route::group('anjungan', ['namespace' => 'Anjungan/Admin'], static function (): void {
    Route::get('/', 'Anjungan@index')->name('admin.anjungan.index');
    Route::get('/datatables', 'Anjungan@datatables')->name('admin.anjungan.datatables');
    Route::get('/form/{id?}', 'Anjungan@form')->name('admin.anjungan.form');
    Route::post('/insert', 'Anjungan@insert')->name('admin.anjungan.insert');
    Route::post('/update/{id?}', 'Anjungan@update')->name('admin.anjungan.update');
    Route::get('/delete/{id?}', 'Anjungan@delete')->name('admin.anjungan.delete');
    Route::post('/delete', 'Anjungan@delete')->name('admin.anjungan.delete-all');
    Route::get('/kunci/{id?}/{val?}', 'Anjungan@kunci')->name('admin.anjungan.kunci');
});

// Anjungan > Menu
Route::group('anjungan_menu', ['namespace' => 'Anjungan/Admin'], static function (): void {
    Route::get('/', 'Anjungan_menu@index')->name('anjungan_menu.index');
    Route::get('/datatables', 'Anjungan_menu@datatables')->name('anjungan_menu.datatables');
    Route::get('/form/{id?}', 'Anjungan_menu@form')->name('anjungan_menu.form');
    Route::post('/insert', 'Anjungan_menu@insert')->name('anjungan_menu.insert');
    Route::post('/update/{id?}', 'Anjungan_menu@update')->name('anjungan_menu.update');
    Route::get('/delete/{id?}', 'Anjungan_menu@delete')->name('anjungan_menu.delete');
    Route::post('/delete', 'Anjungan_menu@delete')->name('anjungan_menu.delete-all');
    Route::get('/lock/{id?}', 'Anjungan_menu@lock')->name('anjungan_menu.lock');
    Route::post('/tukar', 'Anjungan_menu@tukar')->name('anjungan_menu.tukar');
});

// Anjungan > Pengaturan
Route::group('anjungan_pengaturan', ['namespace' => 'Anjungan/Admin'], static function (): void {
    Route::get('/', 'Anjungan_pengaturan@index')->name('anjungan_pengaturan.index');
    Route::post('/update', 'Anjungan_pengaturan@update')->name('anjungan_pengaturan.update');
});

Route::group('anjungan-mandiri', ['namespace' => 'Anjungan'], static function (): void {
    Route::get('/', 'Anjungan@index')->name('anjungan.index');
    Route::get('/beranda', 'AnjunganBeranda@index')->name('anjungan.beranda.index');
    Route::get('/surat/{id?}', 'AnjunganSurat@buat')->name('anjungan.surat');
    Route::get('/surat/form/{id?}', 'AnjunganSurat@form')->name('anjungan.surat.form');
    Route::post('/surat/kirim', 'AnjunganSurat@kirim')->name('anjungan.surat.kirim');
    Route::get('/permohonan', 'AnjunganSurat@permohonan')->name('anjungan.permohonan');
});
