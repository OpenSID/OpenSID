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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

Route::group('dtsen', static function (): void {
    Route::get('/', 'DTSEN/BackEnd/PendataanController@index');
    Route::group('/pendataan', ['namespace' => 'DTSEN/BackEnd'], static function (): void {
        Route::get('/', 'PendataanController@index')->name('dtsen_pendataan.index');
        Route::get('/datatables', 'PendataanController@datatables')->name('dtsen_pendataan.datatables');
        Route::get('/listAnggota/{id_dtsen}', 'PendataanController@listAnggota')->name('dtsen_pendataan.listAnggota');
        Route::get('/loadRecentInfo', 'PendataanController@loadRecentInfo')->name('dtsen_pendataan.loadRecentInfo');
        Route::get('/loadRecentImpor', 'PendataanController@loadRecentImpor')->name('dtsen_pendataan.loadRecentImpor');
        Route::get('/ekspor', 'PendataanController@ekspor')->name('dtsen_pendataan.ekspor');
        Route::match(['GET', 'POST'], '/cetak2/{id?}', 'PendataanController@cetak2')->name('dtsen_pendataan.cetak2');
        Route::match(['GET', 'POST'], '/new/{id_rtm}', 'PendataanController@new')->name('dtsen_pendataan.new');
        Route::get('/latest/{id_rtm}', 'PendataanController@latest')->name('dtsen_pendataan.latest');
        Route::get('/form/{id}', 'PendataanController@form')->name('dtsen_pendataan.form');
        Route::post('/savePengaturan/{versi_dtsen}', 'PendataanController@savePengaturan')->name('dtsen_pendataan.savePengaturan');
        Route::post('/save/{id}', 'PendataanController@save')->name('dtsen_pendataan.save');
        Route::post('/delete/{id}', 'PendataanController@delete')->name('dtsen_pendataan.delete');
        Route::post('/remove/{id}', 'PendataanController@remove')->name('dtsen_pendataan.remove');
    });

    Route::group('/laporan', ['namespace' => 'DTSEN/BackEnd'], static function (): void {
        Route::get('/', 'LaporanController@index')->name('dtsen_laporan.index');
        Route::get('/datatables', 'LaporanController@datatables')->name('dtsen_laporan.datatables');
    });
});
