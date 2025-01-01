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

defined('BASEPATH') || exit('No direct script access allowed');

Route::group('', ['namespace' => 'fweb'], static function (): void {
    Route::get('/', 'Utama@index');

    // Rute untuk Artikel Lama
    Route::group('/first/artikel', static function (): void {
        Route::get('/', 'Artikel@utama');
        Route::get('/{id}', 'Artikel@index');
        Route::get('/{thn}/{bln}/{tgl}/{slug}', 'Artikel@index');
    });

    // Rute untuk Artikel Baru
    Route::group('/artikel', static function (): void {
        Route::get('/kategori/{id}/{p?}', 'Artikel@kategori');
        Route::get('{id}', 'Artikel@index');
        Route::get('{thn}/{bln}/{tgl}/{slug}', 'Artikel@index');
    });

    // Arsip Artikel
    Route::get('arsip', 'Arsip@index');

    // Kesehatan
    Route::get('data-kesehatan/cetak/{aksi?}', 'Kesehatan@cetak')->name('fweb.kesehatan.cetak');
    Route::post('data-kesehatan/scorecard', 'Kesehatan@scorecard')->name('fweb.kesehatan.scorecard');
    Route::get('data-kesehatan/{slug?}', 'Kesehatan@detail')->name('fweb.kesehatan.detail');

    // Kelompok
    Route::get('/data-kelompok/{slug?}', 'Kelompok@detail')->name('fweb.kelompok.detail');

    // Lembaga
    Route::get('/data-lembaga/{slug?}', 'Lembaga@detail')->name('fweb.lembaga.detail');

    // Status Desa
    Route::get('/status-idm/{tahun?}', 'Idm@index');
    Route::get('/status-sdgs', 'Sdgs@index');

    // Galeri
    Route::group('galeri', static function (): void {
        Route::get('', 'Galeri@index')->name('web.galeri.index');
        Route::get('{parent}', 'Galeri@detail')->name('web.galeri.detail');
    });

    // Inventaris
    Route::group('inventaris', static function (): void {
        Route::get('', 'Inventaris@index')->name('fweb.inventaris.index');
        Route::get('{slug}', 'Inventaris@detail')->name('fweb.inventaris.detail');
    });

    // Pengaduan
    Route::group('pengaduan', static function (): void {
        Route::post('/kirim', 'Pengaduan@kirim')->name('fweb.pengaduan.kirim');
        Route::get('/{p?}', 'Pengaduan@index')->name('fweb.pengaduan.index');
    });

    // Pemerintah
    Route::get('pemerintah', 'Pemerintah@index')->name('web.pemerintah.index');

    // SOTK
    Route::get('struktur-organisasi-dan-tata-kerja', 'Sotk@index')->name('web.sotk.index');

    // Statistik
    Route::get('first/statistik/{stat?}/{tipe?}', 'Statistik@index')->name('first.statistik');
    Route::get('data-statistik/{slug}/cetak/{aksi}', 'Statistik@cetak')->name('fweb.statistik.cetak');
    Route::get('data-statistik/{slug?}', 'Statistik@index')->name('fweb.statistik.index');
    Route::get('data-wilayah', 'WilayahAdministratif@index')->name('web.wilayah-administratif');
    Route::get('data-dpt', 'Dpt@index')->name('web.dpt');

    // Suplemen
    Route::get('first/suplemen/{slug?}', 'Suplemen@detail')->name('first.suplemen');
    Route::get('data-suplemen/{slug?}', 'Suplemen@detail')->name('web.suplemen.detail');

    // Lapak
    Route::get('lapak', 'Lapak@index')->name('web.lapak.index');

    // Pembangunan
    Route::group('pembangunan', static function (): void {
        Route::get('/', 'Pembangunan@index')->name('web.pembangunan.index');
        Route::get('/index', 'Pembangunan@index')->name('web.pembangunan.index-page');
        Route::get('/{slug}', 'Pembangunan@detail')->name('web.pembangunan.detail');
    });

    // Peta
    Route::get('peta', 'Peta@index')->name('web.peta.index');

    // Informasi Publik
    Route::get('informasi-publik', 'InformasiPublik@index')->name('web.informasi-publik.index');

    // Peraturan Desa
    Route::get('peraturan-desa', 'Peraturan@index')->name('web.peraturan.index');

    // Analisis
    Route::get('data_analisis', 'Analisis@index')->name('web.analisis.index');
    Route::get('jawaban_analisis', 'Analisis@jawaban')->name('web.analisis.jawaban');

    // Embed
    Route::get('embed', 'Embed@index')->name('web.embed.index');

    // Verifikasi Surat
    Route::get('/v/{alias?}', 'Verifikasi_surat@cek')->name('web.verifikasi_surat.cek');
    Route::get('/c1/{id_dokumen?}/{tipe?}', 'Verifikasi_surat@encode')->name('web.verifikasi_surat.encode');
    Route::get('/verifikasi-surat/{id_encoded?}', 'Verifikasi_surat@decode')->name('web.verifikasi_surat.decode');
    Route::get('/verifikasi-surat-dinas/{id_encoded?}', 'Verifikasi_surat@decodeSuratDinas')->name('web.verifikasi_surat.decode-surat-dinas');
});
