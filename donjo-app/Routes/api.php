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

// Internal API
Route::group('internal_api', ['namespace' => 'internal_api'], static function (): void {
    // Wilayah
    Route::group('wilayah', static function (): void {
        Route::match(['GET', 'POST'], 'get_rw', 'Wilayah@get_rw');
        Route::match(['GET', 'POST'], 'get_rt', 'Wilayah@get_rt');
        Route::match(['GET', 'POST'], 'administratif', 'Wilayah@administratif')->name('api.wilayah.administratif');
    });

    Route::match(['GET', 'POST'], 'apipenduduksuplemen', 'Suplemen@apipenduduksuplemen');

    // Pengaduan
    Route::match(['GET', 'POST'], 'pengaduan', 'Pengaduan@index');

    // Pembangunan
    Route::match(['GET', 'POST'], 'pembangunan', 'Pembangunan@index')->name('api.pembangunan');

    // Arsip Artikel
    Route::match(['GET', 'POST'], 'arsip', 'Artikel@index');

    // Bantuan
    Route::match(['GET', 'POST'], 'peserta_bantuan/{key}', 'BantuanPeserta@index');

    // Status Desa
    Route::match(['GET', 'POST'], 'sdgs', 'Sdgs@index')->name('api.sdgs');
    Route::match(['GET', 'POST'], 'idm/{tahun}', 'Idm@index')->name('api.idm');

    // Inventaris
    Route::match(['GET', 'POST'], 'inventaris', 'Inventaris@index')->name('api.inventaris');
    Route::match(['GET', 'POST'], 'inventaris-tanah', 'InventarisTanah@index')->name('api.inventaris-tanah');
    Route::match(['GET', 'POST'], 'inventaris-asset', 'InventarisAsset@index')->name('api.inventaris-asset');
    Route::match(['GET', 'POST'], 'inventaris-gedung', 'InventarisGedung@index')->name('api.inventaris-gedung');
    Route::match(['GET', 'POST'], 'inventaris-jalan', 'InventarisJalan@index')->name('api.inventaris-jalan');
    Route::match(['GET', 'POST'], 'inventaris-peralatan', 'InventarisPeralatan@index')->name('api.inventaris-peralatan');
    Route::match(['GET', 'POST'], 'inventaris-kontruksi', 'InventarisKontruksi@index')->name('api.inventaris-kontruksi');

    // Stunting
    Route::match(['GET', 'POST'], 'stunting', 'Stunting@index')->name('api.stunting');

    // DPT
    Route::match(['GET', 'POST'], 'dpt', 'Dpt@index')->name('api.dpt');

    // Kelompok
    Route::match(['GET', 'POST'], '/kelompok/{slug}', 'Kelompok@detail')->name('api.kelompok.detail');
    Route::match(['GET', 'POST'], '/kelompok/anggota/{slug}', 'Kelompok@anggota')->name('api.kelompok.anggota');

    // Lembaga
    Route::match(['GET', 'POST'], '/lembaga/{slug}', 'Lembaga@detail')->name('api.lembaga.detail');
    Route::match(['GET', 'POST'], '/lembaga/anggota/{slug}', 'Lembaga@anggota')->name('api.lembaga.anggota');

    // Informasi Publik
    Route::match(['GET', 'POST'], 'informasi-publik', 'InformasiPublik@index')->name('api.informasi-publik');

    // Produk Hukum
    Route::group('produk-hukum', static function (): void {
        Route::match(['GET', 'POST'], '/', 'ProdukHukum@index')->name('api.produk-hukum');
        Route::match(['GET', 'POST'], 'tahun', 'ProdukHukum@tahun')->name('api.tahun-produk-hukum');
        Route::match(['GET', 'POST'], 'kategori', 'ProdukHukum@kategori')->name('api.kategori-produk-hukum');
    });

    // Peta
    Route::match(['GET', 'POST'], 'peta', 'Peta@index')->name('api.peta');

    // Statistik
    Route::match(['GET', 'POST'], 'statistik/{key}', 'Statistik@index');

    // Pemerintah
    Route::match(['GET', 'POST'], 'pemerintah', 'Pemerintah@index')->name('api.pemerintah');

    // Verifikasi surat
    Route::match(['GET', 'POST'], 'verifikasi-surat', 'LogSurat@verifikasi')->name('api.verifikasi-surat');
    Route::match(['GET', 'POST'], 'verifikasi-surat-dinas', 'LogSuratDinas@verifikasi')->name('api.verifikasi-surat-dinas');

    // Galeri
    Route::group('galeri', static function (): void {
        Route::match(['GET', 'POST'], '/', 'Galeri@index')->name('api.galeri');
        Route::match(['GET', 'POST'], '/{parent}', 'Galeri@detail')->name('api.galeri.detail');
    });

    // Suplemen
    Route::group('suplemen', static function (): void {
        Route::match(['GET', 'POST'], '/', 'Suplemen@list')->name('api.suplemen');
        Route::match(['GET', 'POST'], '{suplemen}', 'Suplemen@anggota')->name('api.suplemen.anggota');
    });

    // Analisis
    Route::group('analisis', static function (): void {
        Route::get('master', 'Analisis@master')->name('api.analisis.master');
        Route::match(['GET', 'POST'], 'indikator', 'Analisis@indikator')->name('api.analisis.indikator');
        Route::match(['GET', 'POST'], 'jawaban', 'Analisis@jawaban')->name('api.analisis.jawaban');
    });

    // Rute untuk PPID
    Route::match(['GET', 'POST'], 'ppid', 'Api_informasi_publik@ppid');
});

// Eksternal API
Route::group('external_api', ['namespace' => 'external_api'], static function (): void {
    // Sign
    Route::get('sign/pdf', 'Sign@pdf');
    // Surat Kecamatan
    Route::group('surat_kecamatan', static function (): void {
        Route::post('/kirim', 'Surat_kecamatan@kirim');
        Route::get('/download/{nomor?}', 'Surat_kecamatan@download');
    });

    // TTE
    Route::group('tte', static function (): void {
        Route::get('/periksa_status/{nik?}', 'Tte@periksa_status');
        Route::post('/sign_invisible', 'Tte@sign_invisible');
        Route::post('/sign_visible', 'Tte@sign_visible');
    });
});

// API Publik
Route::group('', ['namespace' => 'fweb'], static function (): void {
    Route::group('api/v1', static function (): void {
        Route::match(['GET', 'POST'], 'sdgs', 'Sdgs@api_sdgs');
    });
});
