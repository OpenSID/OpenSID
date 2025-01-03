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

Route::group('analisis_master', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'AnalisisMasterController@index')->name('analisis_master.index-default');
    Route::get('clear', 'AnalisisMasterController@index')->name('analisis_master.clear');
    Route::get('datatables', 'AnalisisMasterController@datatables')->name('analisis_master.datatables');
    Route::get('form/{id?}', 'AnalisisMasterController@form')->name('analisis_master.form');
    Route::post('insert', 'AnalisisMasterController@insert')->name('analisis_master.insert');
    Route::post('update/{id?}', 'AnalisisMasterController@update')->name('analisis_master.update');
    Route::get('delete/{id?}', 'AnalisisMasterController@delete')->name('analisis_master.delete');
    Route::post('delete', 'AnalisisMasterController@delete')->name('analisis_master.delete-all');
    Route::get('lock/{id}', 'AnalisisMasterController@lock')->name('analisis_master.lock');
    Route::get('panduan', 'AnalisisMasterController@panduan')->name('analisis_master.panduan');
    Route::get('import_analisis', 'AnalisisMasterController@importAnalisis')->name('analisis_master.import_analisis');
    Route::post('import', 'AnalisisMasterController@import')->name('analisis_master.import');
    Route::get('ekspor/{id}', 'AnalisisMasterController@ekspor')->name('analisis_master.ekspor');
    Route::get('import_gform/{id?}', 'AnalisisMasterController@importGform')->name('analisis_master.import_gform');
    Route::get('menu/{id?}', 'AnalisisMasterController@menu')->name('analisis_master.menu');
    Route::post('exec_import_gform', 'AnalisisMasterController@execImportGform')->name('analisis_master.exec_import_gform');
    Route::post('save_import_gform/{id?}', 'AnalisisMasterController@saveImportGform')->name('analisis_master.save_import_gform');
    Route::match(['GET', 'POST'], '/update_gform/{id?}', 'AnalisisMasterController@updateGform')->name('analisis_master.update_gform');
});

Route::group('analisis_indikator/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'AnalisisIndikatorController@index')->name('analisis_indikator.index-default');
    Route::get('datatables', 'AnalisisIndikatorController@datatables')->name('analisis_indikator.datatables');
    Route::get('form/{id?}', 'AnalisisIndikatorController@form')->name('analisis_indikator.form');
    Route::post('insert', 'AnalisisIndikatorController@insert')->name('analisis_indikator.insert');
    Route::post('update/{id?}', 'AnalisisIndikatorController@update')->name('analisis_indikator.update');
    Route::get('delete/{id?}', 'AnalisisIndikatorController@delete')->name('analisis_indikator.delete');
    Route::post('delete', 'AnalisisIndikatorController@delete')->name('analisis_indikator.delete-all');
    Route::group('parameter/{indikator}', static function (): void {
        Route::get('', 'AnalisisParamterController@index')->name('analisis_parameter.index-default');
        Route::get('datatables', 'AnalisisParamterController@datatables')->name('analisis_parameter.datatables');
        Route::get('form/{id?}', 'AnalisisParamterController@form')->name('analisis_parameter.form');
        Route::post('insert', 'AnalisisParamterController@insert')->name('analisis_parameter.insert');
        Route::post('update/{id?}', 'AnalisisParamterController@update')->name('analisis_parameter.update');
        Route::get('delete/{id?}', 'AnalisisParamterController@delete')->name('analisis_parameter.delete');
        Route::post('delete', 'AnalisisParamterController@delete')->name('analisis_parameter.delete-all');
    });
});

Route::group('analisis_kategori/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'AnalisisKategoriController@index')->name('analisis_kategori.index-default');
    Route::get('datatables', 'AnalisisKategoriController@datatables')->name('analisis_kategori.datatables');
    Route::get('form/{id?}', 'AnalisisKategoriController@form')->name('analisis_kategori.form');
    Route::post('insert', 'AnalisisKategoriController@insert')->name('analisis_kategori.insert');
    Route::post('update/{id?}', 'AnalisisKategoriController@update')->name('analisis_kategori.update');
    Route::get('delete/{id?}', 'AnalisisKategoriController@delete')->name('analisis_kategori.delete');
    Route::post('delete', 'AnalisisKategoriController@delete')->name('analisis_kategori.delete-all');
});

Route::group('analisis_klasifikasi/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'AnalisisKlasifikasiController@index')->name('analisis_klasifikasi.index-default');
    Route::get('datatables', 'AnalisisKlasifikasiController@datatables')->name('analisis_klasifikasi.datatables');
    Route::get('form/{id?}', 'AnalisisKlasifikasiController@form')->name('analisis_klasifikasi.form');
    Route::post('insert', 'AnalisisKlasifikasiController@insert')->name('analisis_klasifikasi.insert');
    Route::post('update/{id?}', 'AnalisisKlasifikasiController@update')->name('analisis_klasifikasi.update');
    Route::get('delete/{id?}', 'AnalisisKlasifikasiController@delete')->name('analisis_klasifikasi.delete');
    Route::post('delete', 'AnalisisKlasifikasiController@delete')->name('analisis_klasifikasi.delete-all');
});

Route::group('analisis_respon/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'AnalisisResponController@index');
    Route::get('datatables', 'AnalisisResponController@datatables')->name('analisis_respon.datatables');
    Route::get('form/{id}/{fs?}', 'AnalisisResponController@form')->name('analisis_respon.form');
    Route::get('perbaharui/{id_subjek}', 'AnalisisResponController@perbaharui')->name('analisis_respon.perbaharui');
    Route::post('update/{id}', 'AnalisisResponController@update')->name('analisis_respon.update');
    Route::get('aturan_unduh', 'AnalisisResponController@aturan_unduh')->name('analisis_respon.aturan_unduh');
    Route::get('data_ajax', 'AnalisisResponController@dataAjax')->name('analisis_respon.data_ajax');
    Route::post('data_unduh', 'AnalisisResponController@dataUnduh')->name('analisis_respon.data_unduh');
    Route::get('import/{op?}', 'AnalisisResponController@import')->name('analisis_respon.import');
    Route::post('import_proses/{op?}', 'AnalisisResponController@importProses')->name('analisis_respon.import_proses');
    Route::get('form_impor_bdt/{id?}', 'AnalisisResponController@formImporBdt')->name('analisis_respon.form_impor_bdt');
    Route::post('impor_bdt', 'AnalisisResponController@imporBdt')->name('analisis_respon.impor_bdt');
    Route::group('child', static function (): void {
        Route::get('form/{id}/{idc?}', 'AnalisisResponChild@formChild')->name('analisis_respon.form_child');
        Route::post('update/{id}/{idc?}', 'AnalisisResponChild@updateChild')->name('analisis_respon.update_child');
    });
});

Route::group('analisis_periode/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'AnalisisPeriodeController@index')->name('analisis_periode.index-default');
    Route::get('datatables', 'AnalisisPeriodeController@datatables')->name('analisis_periode.datatables');
    Route::get('form/{id?}', 'AnalisisPeriodeController@form')->name('analisis_periode.form');
    Route::post('insert', 'AnalisisPeriodeController@insert')->name('analisis_periode.insert');
    Route::post('update/{id?}', 'AnalisisPeriodeController@update')->name('analisis_periode.update');
    Route::get('lock/{id}', 'AnalisisPeriodeController@lock')->name('analisis_periode.lock');
    Route::get('delete/{id?}', 'AnalisisPeriodeController@delete')->name('analisis_periode.delete');
    Route::post('delete', 'AnalisisPeriodeController@delete')->name('analisis_periode.delete-all');
});

Route::group('analisis_laporan/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'AnalisisLaporanController@index');
    Route::get('datatables', 'AnalisisLaporanController@datatables')->name('analisis_laporan.datatables');
    Route::get('form/{id}', 'AnalisisLaporanController@form')->name('analisis_laporan.form');
    Route::get('dialog_kuisioner/{id}/{aksi?}', 'AnalisisLaporanController@dialogKuisioner')->name('analisis_laporan.dialog_kuisioner');
    Route::post('daftar/{id}/{aksi?}', 'AnalisisLaporanController@daftar')->name('analisis_laporan.daftar');
    Route::get('dialog/{aksi?}', 'AnalisisLaporanController@dialog')->name('analisis_laporan.dialog');
    Route::post('cetak/{aksi?}', 'AnalisisLaporanController@cetak')->name('analisis_laporan.cetak');
    Route::get('multi_jawab', 'AnalisisLaporanController@multi_jawab')->name('analisis_laporan.multi_jawab');
    Route::post('multi_exec', 'AnalisisLaporanController@multi_exec')->name('analisis_laporan.multi_exec');
    Route::get('ajax_multi_jawab', 'AnalisisLaporanController@ajaxMultiJawab')->name('analisis_laporan.ajax_multi_jawab');
    Route::post('multi_jawab_proses', 'AnalisisLaporanController@multiJawabProses')->name('analisis_laporan.multi_jawab_proses');
});

Route::group('analisis_statistik_jawaban/{master}', ['namespace' => 'Analisis'], static function (): void {
    Route::get('', 'AnalisisStatistikJawabanController@index');
    Route::get('datatables', 'AnalisisStatistikJawabanController@datatables')->name('analisis_statistik_jawaban.datatables');
    Route::get('grafik_parameter/{id?}', 'AnalisisStatistikJawabanController@grafikParameter')->name('analisis_statistik_jawaban.grafik_parameter');
    Route::get('subjek_parameter/{id}/{par}', 'AnalisisStatistikJawabanController@subjekParameter')->name('analisis_statistik_jawaban.subjek_parameter');
    Route::post('cetak', 'AnalisisStatistikJawabanController@cetak')->name('analisis_statistik_jawaban.cetak');
    Route::get('cetak_subjek/{id}/{par}/{tipe?}', 'AnalisisStatistikJawabanController@cetakSubjek')->name('analisis_statistik_jawaban.cetak_subjek');
});
