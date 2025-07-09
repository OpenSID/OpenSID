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

 // Lapak
Route::group('lapak_admin', ['namespace' => 'Lapak/BackEnd'], static function (): void {
    Route::get('/', static function (): void {
        redirect('lapak_admin/produk');
    });

    // produk
    Route::group('produk', static function (): void {
        Route::get('/', 'LapakAdminController@index')->name('lapak_admin.produk.index');
        Route::post('/', 'LapakAdminController@index')->name('lapak_admin.produk.datatables');

        Route::get('/dialog/{aksi?}', 'LapakAdminController@dialog')->name('lapak_admin.produk.dialog');
        Route::post('/aksi/{aksi?}', 'LapakAdminController@aksi')->name('lapak_admin.produk.aksi');
    });
    Route::get('/produk_form/{id?}', 'LapakAdminController@produkForm')->name('lapak_admin.form');
    Route::post('/produk_insert', 'LapakAdminController@produkUpdate')->name('lapak_admin.insert');
    Route::post('/produk_update/{id?}', 'LapakAdminController@produkUpdate')->name('lapak_admin.update');
    Route::get('/produk_delete/{id}', 'LapakAdminController@produkDelete')->name('lapak_admin.delete');
    Route::post('/produk_delete_all', 'LapakAdminController@produkDeleteAll')->name('lapak_admin.delete.all');
    Route::get('/produk_detail/{id?}', 'LapakAdminController@produkDetail')->name('lapak_admin.detail');
    Route::get('/produk_status/{id?}/{status?}', 'LapakAdminController@produkStatus')->name('lapak_admin.produk.status');

    // pelapak
    Route::group('pelapak', static function (): void {
        Route::get('/', 'LapakPelapakAdminController@index')->name('lapak_admin.pelapak.index');
        Route::post('/', 'LapakPelapakAdminController@index')->name('lapak_admin.pelapak.datatables');

        Route::get('/dialog/{aksi?}', 'LapakPelapakAdminController@dialog')->name('lapak_admin.pelapak.dialog');
        Route::post('/aksi/{aksi?}', 'LapakPelapakAdminController@aksi')->name('lapak_admin.pelapak.aksi');
    });
    Route::get('/pelapak_form/{id?}', 'LapakPelapakAdminController@pelapakForm')->name('lapak_admin.pelapak.form');
    Route::get('/pelapak_maps/{id?}', 'LapakPelapakAdminController@pelapakMaps')->name('lapak_admin.pelapak.maps');
    Route::post('/pelapak_insert', 'LapakPelapakAdminController@pelapakInsert')->name('lapak_admin.pelapak.insert');
    Route::match(['GET', 'POST'], '/pelapak_update_maps/{id?}', 'LapakPelapakAdminController@pelapakUpdateMaps')->name('lapak_admin.pelapak.update.maps');
    Route::match(['GET', 'POST'], '/pelapak_update/{id?}', 'LapakPelapakAdminController@pelapakUpdate')->name('lapak_admin.pelapak.update');
    Route::get('/pelapak_delete/{id?}', 'LapakPelapakAdminController@pelapakDelete')->name('lapak_admin.pelapak.delete');
    Route::post('/pelapak_delete_all', 'LapakPelapakAdminController@pelapakDeleteAll')->name('lapak_admin.pelapak.delete.all');
    Route::get('/pelapak_status/{id?}/{status?}', 'LapakPelapakAdminController@pelapakStatus')->name('lapak_admin.pelapak.status');

    // kategori
    Route::group('kategori', static function (): void {
        Route::get('/', 'LapakKategoriAdminController@index')->name('lapak_kategori.index');
        Route::post('/', 'LapakKategoriAdminController@index')->name('lapak_kategori.datatables');

        Route::get('/dialog/{aksi?}', 'LapakKategoriAdminController@dialog')->name('lapak_kategori.dialog');
        Route::post('/aksi/{aksi?}', 'LapakKategoriAdminController@aksi')->name('lapak_kategori.aksi');
    });
    Route::get('/kategori_form/{id?}', 'LapakKategoriAdminController@kategoriForm')->name('lapak_admin.kategori.form');
    Route::post('/kategori_insert', 'LapakKategoriAdminController@kategoriInsert')->name('lapak_admin.kategori.insert');
    Route::match(['GET', 'POST'], '/kategori_update/{id?}', 'LapakKategoriAdminController@kategoriUpdate')->name('lapak_admin.kategori.update');
    Route::get('/kategori_delete/{id?}', 'LapakKategoriAdminController@kategoriDelete')->name('lapak_admin.kategori.delete');
    Route::post('/kategori_delete_all', 'LapakKategoriAdminController@kategoriDeleteAll')->name('lapak_admin.kategori.delete.all');
    Route::get('/kategori_status/{id?}/{status?}', 'LapakKategoriAdminController@kategoriStatus')->name('lapak_admin.kategori.status');
});
