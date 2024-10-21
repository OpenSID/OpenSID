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

Route::group('analisis_master', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'Analisis_master@index')->name('analisis_master.index-default');
    Route::get('clear', 'Analisis_master@index')->name('analisis_master.clear');
    Route::get('datatables', 'Analisis_master@datatables')->name('analisis_master.datatables');
    Route::get('form/{id?}', 'Analisis_master@form')->name('analisis_master.form');
    Route::post('insert', 'Analisis_master@insert')->name('analisis_master.insert');
    Route::post('update/{id?}', 'Analisis_master@update')->name('analisis_master.update');
    Route::get('delete/{id?}', 'Analisis_master@delete')->name('analisis_master.delete');
    Route::post('delete', 'Analisis_master@delete')->name('analisis_master.delete-all');
    Route::get('lock/{id}', 'Analisis_master@lock')->name('analisis_master.lock');
    Route::get('panduan', 'Analisis_master@panduan')->name('analisis_master.panduan');
    Route::get('import_analisis', 'Analisis_master@import_analisis')->name('analisis_master.import_analisis');
    Route::post('import', 'Analisis_master@import')->name('analisis_master.import');
    Route::get('ekspor/{id}', 'Analisis_master@ekspor')->name('analisis_master.ekspor');
    Route::get('import_gform/{id?}', 'Analisis_master@import_gform')->name('analisis_master.import_gform');
    Route::get('menu/{id?}', 'Analisis_master@menu')->name('analisis_master.menu');
    Route::post('exec_import_gform', 'Analisis_master@exec_import_gform')->name('analisis_master.exec_import_gform');
    Route::post('save_import_gform/{id?}', 'Analisis_master@save_import_gform')->name('analisis_master.save_import_gform');
    Route::match(['GET', 'POST'], '/update_gform/{id?}', 'Analisis_master@update_gform')->name('analisis_master.update_gform');
});

Route::group('analisis_indikator/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'Analisis_indikator@index')->name('analisis_indikator.index-default');
    Route::get('datatables', 'Analisis_indikator@datatables')->name('analisis_indikator.datatables');
    Route::get('form/{id?}', 'Analisis_indikator@form')->name('analisis_indikator.form');
    Route::post('insert', 'Analisis_indikator@insert')->name('analisis_indikator.insert');
    Route::post('update/{id?}', 'Analisis_indikator@update')->name('analisis_indikator.update');
    Route::get('delete/{id?}', 'Analisis_indikator@delete')->name('analisis_indikator.delete');
    Route::post('delete', 'Analisis_indikator@delete')->name('analisis_indikator.delete-all');
    Route::group('parameter/{indikator}', static function (): void {
        Route::get('', 'Analisis_parameter@index')->name('analisis_parameter.index-default');
        Route::get('datatables', 'Analisis_parameter@datatables')->name('analisis_parameter.datatables');
        Route::get('form/{id?}', 'Analisis_parameter@form')->name('analisis_parameter.form');
        Route::post('insert', 'Analisis_parameter@insert')->name('analisis_parameter.insert');
        Route::post('update/{id?}', 'Analisis_parameter@update')->name('analisis_parameter.update');
        Route::get('delete/{id?}', 'Analisis_parameter@delete')->name('analisis_parameter.delete');
        Route::post('delete', 'Analisis_parameter@delete')->name('analisis_parameter.delete-all');
    });
});

Route::group('analisis_kategori/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'Analisis_kategori@index')->name('analisis_kategori.index-default');
    Route::get('datatables', 'Analisis_kategori@datatables')->name('analisis_kategori.datatables');
    Route::get('form/{id?}', 'Analisis_kategori@form')->name('analisis_kategori.form');
    Route::post('insert', 'Analisis_kategori@insert')->name('analisis_kategori.insert');
    Route::post('update/{id?}', 'Analisis_kategori@update')->name('analisis_kategori.update');
    Route::get('delete/{id?}', 'Analisis_kategori@delete')->name('analisis_kategori.delete');
    Route::post('delete', 'Analisis_kategori@delete')->name('analisis_kategori.delete-all');
});

Route::group('analisis_klasifikasi/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'Analisis_klasifikasi@index')->name('analisis_klasifikasi.index-default');
    Route::get('datatables', 'Analisis_klasifikasi@datatables')->name('analisis_klasifikasi.datatables');
    Route::get('form/{id?}', 'Analisis_klasifikasi@form')->name('analisis_klasifikasi.form');
    Route::post('insert', 'Analisis_klasifikasi@insert')->name('analisis_klasifikasi.insert');
    Route::post('update/{id?}', 'Analisis_klasifikasi@update')->name('analisis_klasifikasi.update');
    Route::get('delete/{id?}', 'Analisis_klasifikasi@delete')->name('analisis_klasifikasi.delete');
    Route::post('delete', 'Analisis_klasifikasi@delete')->name('analisis_klasifikasi.delete-all');
});

Route::group('analisis_respon/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'Analisis_respon@index');
    Route::get('datatables', 'Analisis_respon@datatables')->name('analisis_respon.datatables');
    Route::get('form/{id}/{fs?}', 'Analisis_respon@form')->name('analisis_respon.form');
    Route::get('perbaharui/{id_subjek}', 'Analisis_respon@perbaharui')->name('analisis_respon.perbaharui');
    Route::post('update/{id}', 'Analisis_respon@update')->name('analisis_respon.update');
    Route::get('aturan_unduh', 'Analisis_respon@aturan_unduh')->name('analisis_respon.aturan_unduh');
    Route::get('data_ajax', 'Analisis_respon@data_ajax')->name('analisis_respon.data_ajax');
    Route::post('data_unduh', 'Analisis_respon@data_unduh')->name('analisis_respon.data_unduh');
    Route::get('import/{op?}', 'Analisis_respon@import')->name('analisis_respon.import');
    Route::post('import_proses/{op?}', 'Analisis_respon@import_proses')->name('analisis_respon.import_proses');
    Route::get('form_impor_bdt/{id?}', 'Analisis_respon@form_impor_bdt')->name('analisis_respon.form_impor_bdt');
    Route::post('impor_bdt', 'Analisis_respon@impor_bdt')->name('analisis_respon.impor_bdt');
    Route::get('unduh_form_bdt/{id?}', 'Analisis_respon@unduh_form_bdt')->name('analisis_respon.unduh_form_bdt');
    Route::group('child', static function (): void {
        Route::get('form/{id}/{idc?}', 'Analisis_respon_child@formChild')->name('analisis_respon.form_child');
        Route::post('update/{id}/{idc?}', 'Analisis_respon_child@updateChild')->name('analisis_respon.update_child');
    });
});
Route::group('analisis_periode/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'Analisis_periode@index')->name('analisis_periode.index-default');
    Route::get('datatables', 'Analisis_periode@datatables')->name('analisis_periode.datatables');
    Route::get('form/{id?}', 'Analisis_periode@form')->name('analisis_periode.form');
    Route::post('insert', 'Analisis_periode@insert')->name('analisis_periode.insert');
    Route::post('update/{id?}', 'Analisis_periode@update')->name('analisis_periode.update');
    Route::get('lock/{id}', 'Analisis_periode@lock')->name('analisis_periode.lock');
    Route::get('delete/{id?}', 'Analisis_periode@delete')->name('analisis_periode.delete');
    Route::post('delete', 'Analisis_periode@delete')->name('analisis_periode.delete-all');
});

Route::group('analisis_laporan/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'Analisis_laporan@index');
    Route::get('datatables', 'Analisis_laporan@datatables')->name('analisis_laporan.datatables');
    Route::get('form/{id}', 'Analisis_laporan@form')->name('analisis_laporan.form');
    Route::get('dialog_kuisioner/{id}/{aksi?}', 'Analisis_laporan@dialog_kuisioner')->name('analisis_laporan.dialog_kuisioner');
    Route::post('daftar/{id}/{aksi?}', 'Analisis_laporan@daftar')->name('analisis_laporan.daftar');
    Route::get('dialog/{aksi?}', 'Analisis_laporan@dialog')->name('analisis_laporan.dialog');
    Route::post('cetak/{aksi?}', 'Analisis_laporan@cetak')->name('analisis_laporan.cetak');
    Route::get('multi_jawab', 'Analisis_laporan@multi_jawab')->name('analisis_laporan.multi_jawab');
    Route::post('multi_exec', 'Analisis_laporan@multi_exec')->name('analisis_laporan.multi_exec');
    Route::get('ajax_multi_jawab', 'Analisis_laporan@ajax_multi_jawab')->name('analisis_laporan.ajax_multi_jawab');
    Route::post('multi_jawab_proses', 'Analisis_laporan@multi_jawab_proses')->name('analisis_laporan.multi_jawab_proses');
});

Route::group('analisis_statistik_jawaban/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'Analisis_statistik_jawaban@index');
    Route::get('datatables', 'Analisis_statistik_jawaban@datatables')->name('analisis_statistik_jawaban.datatables');
    Route::get('grafik_parameter/{id?}', 'Analisis_statistik_jawaban@grafik_parameter')->name('analisis_statistik_jawaban.grafik_parameter');
    Route::get('subjek_parameter/{id}/{par}', 'Analisis_statistik_jawaban@subjek_parameter')->name('analisis_statistik_jawaban.subjek_parameter');
    Route::post('cetak', 'Analisis_statistik_jawaban@cetak')->name('analisis_statistik_jawaban.cetak');
    Route::get('cetak_subjek/{id}/{par}/{tipe?}', 'Analisis_statistik_jawaban@cetak_subjek')->name('analisis_statistik_jawaban.cetak_subjek');
});
