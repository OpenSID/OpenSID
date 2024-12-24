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

// Internal API
Route::group('internal_api', ['namespace' => 'internal_api'], static function (): void {
    // Wilayah
    Route::get('wilayah/get_rw', 'Wilayah@get_rw');
    Route::get('wilayah/get_rt', 'Wilayah@get_rt');
});

// Eksternal API
Route::group('external_api', ['namespace' => 'external_api'], static function (): void {
    // Sign
    Route::get('sign/pdf', 'Sign@pdf');
    // Surat Kecamatan
    Route::group('surat_kecamatan', static function (): void {
        Route::post('/kirim', 'Surat_kecamatan@kirim');
        Route::get('/download/{jenis}/{nomor}/{desa}/{bulan}/{tahun}', 'Surat_kecamatan@download');
    });

    // TTE
    Route::group('tte', static function (): void {
        Route::get('/periksa_status/{nik?}', 'Tte@periksa_status');
        Route::post('/sign_invisible', 'Tte@sign_invisible');
        Route::post('/sign_visible', 'Tte@sign_visible');
    });
});
