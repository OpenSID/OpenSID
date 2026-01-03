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

// Internal API
Route::group('internal_api', ['namespace' => 'internal_api'], static function (): void {
    // Wilayah
    Route::group('wilayah', static function (): void {
        Route::get('get_rw', 'Wilayah@get_rw');
        Route::get('get_rt', 'Wilayah@get_rt');
        Route::get('administratif', 'Wilayah@administratif')->name('api.wilayah.administratif');
    });

    Route::get('apipenduduksuplemen', 'Suplemen@apipenduduksuplemen');

    // Pengaduan
    Route::get('pengaduan', 'Pengaduan@index');

    // Pembangunan
    Route::get('pembangunan', 'Pembangunan@index')->name('api.pembangunan');

    // Arsip Artikel
    Route::get('arsip', 'Artikel@index');

    // Bantuan
    Route::get('peserta_bantuan/{key}', 'BantuanPeserta@index');

    // Status Desa
    Route::get('sdgs', 'Sdgs@index')->name('api.sdgs');
    Route::get('idm/{tahun}', 'Idm@index')->name('api.idm');

    // Inventaris
    Route::get('inventaris', 'Inventaris@index')->name('api.inventaris');
    Route::get('inventaris-tanah', 'InventarisTanah@index')->name('api.inventaris-tanah');
    Route::get('inventaris-asset', 'InventarisAsset@index')->name('api.inventaris-asset');
    Route::get('inventaris-gedung', 'InventarisGedung@index')->name('api.inventaris-gedung');
    Route::get('inventaris-jalan', 'InventarisJalan@index')->name('api.inventaris-jalan');
    Route::get('inventaris-peralatan', 'InventarisPeralatan@index')->name('api.inventaris-peralatan');
    Route::get('inventaris-kontruksi', 'InventarisKontruksi@index')->name('api.inventaris-kontruksi');

    // Stunting
    Route::get('stunting', 'Stunting@index')->name('api.stunting');

    // DPT
    Route::get('dpt', 'Dpt@index')->name('api.dpt');

    // Lapak
    Route::group('lapak', static function (): void {
        Route::get('produk', 'Lapak@produk')->name('api.lapak.produk');
        Route::get('kategori', 'Lapak@kategori')->name('api.lapak.kategori');
        Route::get('pelapak', 'Lapak@pelapak')->name('api.lapak.pelapak');
    });

    // Kelompok
    Route::get('/kelompok/{slug}', 'Kelompok@detail')->name('api.kelompok.detail');
    Route::get('/kelompok/anggota/{slug}', 'Kelompok@anggota')->name('api.kelompok.anggota');

    // Lembaga
    Route::get('/lembaga/{slug}', 'Lembaga@detail')->name('api.lembaga.detail');
    Route::get('/lembaga/anggota/{slug}', 'Lembaga@anggota')->name('api.lembaga.anggota');

    // Informasi Publik
    Route::get('informasi-publik', 'InformasiPublik@index')->name('api.informasi-publik');

    // Produk Hukum
    Route::group('produk-hukum', static function (): void {
        Route::get('/', 'ProdukHukum@index')->name('api.produk-hukum');
        Route::get('tahun', 'ProdukHukum@tahun')->name('api.tahun-produk-hukum');
        Route::get('kategori', 'ProdukHukum@kategori')->name('api.kategori-produk-hukum');
    });

    // Peta
    Route::get('peta', 'Peta@index')->name('api.peta');

    // Statistik
    Route::get('statistik/{key}', 'Statistik@index');

    // Pemerintah
    Route::get('pemerintah', 'Pemerintah@index')->name('api.pemerintah');

    // Verifikasi surat
    Route::get('verifikasi-surat', 'LogSurat@verifikasi')->name('api.verifikasi-surat');
    Route::get('verifikasi-surat-dinas', 'LogSuratDinas@verifikasi')->name('api.verifikasi-surat-dinas');

    // Galeri
    Route::group('galeri', static function (): void {
        Route::get('/', 'Galeri@index')->name('api.galeri');
        Route::get('/{parent}', 'Galeri@detail')->name('api.galeri.detail');
    });

    // Suplemen
    Route::group('suplemen', static function (): void {
        Route::get('/', 'Suplemen@list')->name('api.suplemen');
        Route::get('{suplemen}', 'Suplemen@anggota')->name('api.suplemen.anggota');
    });

    // Analisis
    Route::group('analisis', static function (): void {
        Route::get('master', 'Analisis@master')->name('api.analisis.master');
        Route::get('indikator', 'Analisis@indikator')->name('api.analisis.indikator');
        Route::get('jawaban', 'Analisis@jawaban')->name('api.analisis.jawaban');
    });

    // Rute untuk PPID
    Route::get('ppid', 'Api_informasi_publik@ppid');
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

// API Publik
Route::group('', ['namespace' => 'fweb'], static function (): void {
    Route::group('api/v1', static function (): void {
        Route::get('sdgs', 'Sdgs@api_sdgs');
    });
});
