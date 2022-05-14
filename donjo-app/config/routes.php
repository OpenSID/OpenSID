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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

$route['default_controller']   = 'first';
$route['404_override']         = '';
$route['translate_uri_dashes'] = false;

$route['sitemap\.xml'] = 'Sitemap/index';
$route['feed\.xml']    = 'Feed/index';
$route['ppid']         = 'Api_informasi_publik/ppid';

// Artikel
$route['artikel/(:num)']                      = 'first/artikel/$1'; // Contoh : artikel/1
$route['artikel/(:num)/(:num)/(:num)/(:any)'] = 'first/artikel/$4'; // Contoh : artikel/2020/5/15/contoh-artikel
// Artikel lama (Agar url lama masih dpt di akases)
$route['first/artikel/(:num)']                      = 'first/artikel/$1'; // Contoh : Contoh : first/artikel/1
$route['first/artikel/(:num)/(:num)/(:num)/(:any)'] = 'first/artikel/$4'; // Contoh : first/artikel/2020/5/15/contoh-artikel

// Route bumindes
$route['bumindes_umum/([a-z_]+)/(:any)'] = 'buku_umum/bumindes_umum/$1/$2';
$route['bumindes_umum/([a-z_]+)']        = 'buku_umum/bumindes_umum/$1';
$route['bumindes_umum']                  = 'buku_umum/bumindes_umum';

$route['bumindes_arsip']               = 'bumindes_arsip/index';
$route['bumindes_arsip/(:num)']        = 'bumindes_arsip/index/$1';
$route['bumindes_arsip/(:num)/(:num)'] = 'bumindes_arsip/index/$1/$2';

$buku_umum = ['ekspedisi', 'lembaran_desa', 'pengurus', 'surat_keluar', 'surat_masuk'];

foreach ($buku_umum as $menu) {
    $route["{$menu}/([a-z_]+)/(:any)/(:any)/(:any)"] = "buku_umum/{$menu}/$1/$2/$3/$4";
    $route["{$menu}/([a-z_]+)/(:any)/(:any)"]        = "buku_umum/{$menu}/$1/$2/$3";
    $route["{$menu}/([a-z_]+)/(:any)"]               = "buku_umum/{$menu}/$1/$2";
    $route["{$menu}/([a-z_]+)"]                      = "buku_umum/{$menu}/$1";
    $route["{$menu}"]                                = "buku_umum/{$menu}";
}

$route['dokumen_sekretariat/([a-z_]+)/(:any)/(:any)/(:any)/(:any)'] = 'buku_umum/dokumen_sekretariat/$1/$2/$3/$4/$5';
$route['dokumen_sekretariat/([a-z_]+)/(:any)/(:any)/(:any)']        = 'buku_umum/dokumen_sekretariat/$1/$2/$3/$4';
$route['dokumen_sekretariat/([a-z_]+)/(:any)/(:any)']               = 'buku_umum/dokumen_sekretariat/$1/$2/$3';
$route['dokumen_sekretariat/([a-z_]+)/(:any)']                      = 'buku_umum/dokumen_sekretariat/$1/$2';
$route['dokumen_sekretariat/([a-z_]+)']                             = 'buku_umum/dokumen_sekretariat/$1';
$route['dokumen_sekretariat']                                       = 'buku_umum/dokumen_sekretariat';

// Route untuk menghilangkan 'first' dari URL web
// Kategori artikel
$route['artikel/kategori/(:any)']        = 'first/kategori/$1'; // Contoh : Contoh : artikel/kategori/berita-desa
$route['artikel/kategori/(:any)/(:num)'] = 'first/kategori/$1/$2'; // Contoh : Contoh : artikel/kategori/berita-desa/1

$route['index/(:num)']       = 'first/index/$1';
$route['(:num)']             = 'first/index/$1';
$route['arsip']              = 'first/arsip';
$route['arsip/(:num)']       = 'first/arsip/$1';
$route['add_comment/(:any)'] = 'first/add_comment/$1';
$route['ambil_data_covid']   = 'first/ambil_data_covid';
$route['load_apbdes']        = 'first/load_apbdes';
$route['logout']             = 'first/logout';
$route['ganti']              = 'first/ganti';
$route['auth']               = 'first/auth';

// Halaman statis
$route['data-wilayah']               = 'first/wilayah';
$route['data-kelompok/(:num)']       = 'first/kelompok/$1';
$route['informasi_publik']           = 'first/informasi_publik';
$route['peraturan_desa']             = 'first/peraturan_desa';
$route['data_analisis']              = 'first/data_analisis';
$route['data_analisis/(.+)']         = 'first/data_analisis/$1';
$route['jawaban_analisis/(.+)']      = 'first/jawaban_analisis/$1';
$route['peta']                       = 'first/peta';
$route['load_aparatur_desa']         = 'first/load_aparatur_desa';
$route['load_aparatur_wilayah/(.+)'] = 'first/load_aparatur_wilayah/$1';
$route['status-sdgs']                = 'first/status_sdgs';

// WEB --------------------------------------------------------------
// Pembangunan
$route['pembangunan']              = WEB . '/pembangunan';
$route['pembangunan/index/(:num)'] = WEB . '/pembangunan/index/$1';
$route['pembangunan/(:any)']       = WEB . '/pembangunan/detail/$1';

// Lapak
$route['lapak']        = WEB . '/lapak';
$route['lapak/(:num)'] = WEB . '/lapak/index/$1';

// Pengaduan
$route['pengaduan']        = WEB . '/pengaduan';
$route['pengaduan/(:num)'] = WEB . '/pengaduan/index/$1';
$route['pengaduan/kirim']  = WEB . '/pengaduan/kirim';

// Surat
$route['v/(:any)']                = WEB . '/verifikasi_surat/cek/$1';
$route['c1/(:any)']               = WEB . '/verifikasi_surat/encode/$1';
$route['verifikasi-surat/(:any)'] = WEB . '/verifikasi_surat/decode/$1';

// Galeri
$route['galeri/(:num)/index/(:num)'] = WEB . '/galeri/detail/$1/$2';
$route['galeri/(:num)']              = WEB . '/galeri/detail/$1';
$route['galeri/index/(:num)']        = WEB . '/galeri/index/$1';
$route['galeri']                     = WEB . '/galeri';

// Suplemen
$route['data-suplemen/(:any)'] = WEB . '/suplemen/detail/$1';

// Kelompok
$route['data-kelompok/(:any)'] = WEB . '/kelompok/detail/$1';

// Vaksin
$route['data-vaksinasi'] = WEB . '/vaksin';

// Halaman Layanan Mandiri
// Auth
$mandiri                                                      = 'layanan-mandiri';
$route[$mandiri . '/masuk']                                   = MANDIRI . '/masuk';
$route[$mandiri . '/cek']                                     = MANDIRI . '/masuk/cek';
$route[$mandiri . '/masuk-ektp']                              = MANDIRI . '/masuk_ektp';
$route[$mandiri . '/cek-ektp']                                = MANDIRI . '/masuk_ektp/cek_ektp';
$route[$mandiri . '/daftar']                                  = MANDIRI . '/daftar';
$route[$mandiri . '/proses-daftar']                           = MANDIRI . '/daftar/proses_daftar';
$route[$mandiri . '/daftar/verifikasi']                       = MANDIRI . '/daftar_verifikasi';
$route[$mandiri . '/daftar/verifikasi/telegram']              = MANDIRI . '/daftar_verifikasi/telegram';
$route[$mandiri . '/daftar/verifikasi/telegram/kirim-userid'] = MANDIRI . '/daftar_verifikasi/kirim_otp_telegram';
$route[$mandiri . '/daftar/verifikasi/telegram/kirim-otp']    = MANDIRI . '/daftar_verifikasi/verifikasi_telegram';
$route[$mandiri . '/daftar/verifikasi/email']                 = MANDIRI . '/daftar_verifikasi/email';
$route[$mandiri . '/daftar/verifikasi/email/kirim-email']     = MANDIRI . '/daftar_verifikasi/kirim_otp_email';
$route[$mandiri . '/daftar/verifikasi/email/kirim-otp']       = MANDIRI . '/daftar_verifikasi/verifikasi_email';
$route[$mandiri . '/lupa-pin']                                = MANDIRI . '/masuk/lupa_pin';
$route[$mandiri . '/cek-pin']                                 = MANDIRI . '/masuk/cek_pin';
// Beranda
$route['layanan-mandiri']             = MANDIRI . '/beranda';
$route[$mandiri . '/pendapat/(:num)'] = MANDIRI . '/beranda/pendapat/$1';
// Profil
$route[$mandiri . '/profil']           = MANDIRI . '/beranda/profil';
$route[$mandiri . '/cetak-biodata']    = MANDIRI . '/beranda/cetak_biodata';
$route[$mandiri . '/ganti-pin']        = MANDIRI . '/beranda/ganti_pin';
$route[$mandiri . '/proses-ganti-pin'] = MANDIRI . '/beranda/proses_ganti_pin';
$route[$mandiri . '/cetak-kk']         = MANDIRI . '/beranda/cetak_kk';
$route[$mandiri . '/keluar']           = MANDIRI . '/beranda/keluar';
// Pesan
$route[$mandiri . '/pesan-masuk']              = MANDIRI . '/pesan/index/2';
$route[$mandiri . '/pesan-keluar']             = MANDIRI . '/pesan/index/1';
$route[$mandiri . '/pesan/tulis']              = MANDIRI . '/pesan/tulis/1';
$route[$mandiri . '/pesan/balas']              = MANDIRI . '/pesan/tulis/2';
$route[$mandiri . '/pesan/kirim']              = MANDIRI . '/pesan/kirim';
$route[$mandiri . '/pesan/baca/(:num)/(:num)'] = MANDIRI . '/pesan/baca/$1/$2';
// Surat
$route[$mandiri . '/arsip-surat']       = MANDIRI . '/surat/index/2';
$route[$mandiri . '/permohonan-surat']  = MANDIRI . '/surat/index/1';
$route[$mandiri . '/surat/buat']        = MANDIRI . '/surat/buat';
$route[$mandiri . '/surat/buat/(:num)'] = MANDIRI . '/surat/buat/$1';
$route[$mandiri . '/surat/form']        = MANDIRI . '/surat/form';
$route[$mandiri . '/surat/form/(:num)'] = MANDIRI . '/surat/form/$1';
// Dokumen
$route[$mandiri . '/dokumen']              = MANDIRI . '/dokumen';
$route[$mandiri . '/dokumen/form']         = MANDIRI . '/dokumen/form';
$route[$mandiri . '/dokumen/form/(:num)']  = MANDIRI . '/dokumen/form/$1';
$route[$mandiri . '/dokumen/tambah']       = MANDIRI . '/dokumen/tambah';
$route[$mandiri . '/dokumen/ubah/(:num)']  = MANDIRI . '/dokumen/ubah/$1';
$route[$mandiri . '/dokumen/hapus/(:num)'] = MANDIRI . '/dokumen/hapus/$1';
$route[$mandiri . '/dokumen/unduh/(:num)'] = MANDIRI . '/dokumen/unduh/$1';
// Lapak
$route[$mandiri . '/lapak']        = MANDIRI . '/lapak';
$route[$mandiri . '/lapak/(:num)'] = MANDIRI . '/lapak/index/$1';
//Verifikasi
$route[$mandiri . '/verifikasi']                       = MANDIRI . '/verifikasi';
$route[$mandiri . '/verifikasi/telegram']              = MANDIRI . '/verifikasi/telegram';
$route[$mandiri . '/verifikasi/telegram/kirim-userid'] = MANDIRI . '/verifikasi/kirim_otp_telegram';
$route[$mandiri . '/verifikasi/telegram/kirim-otp']    = MANDIRI . '/verifikasi/verifikasi_telegram';
$route[$mandiri . '/verifikasi/email']                 = MANDIRI . '/verifikasi/email';
$route[$mandiri . '/verifikasi/email/kirim-email']     = MANDIRI . '/verifikasi/kirim_otp_email';
$route[$mandiri . '/verifikasi/email/kirim-otp']       = MANDIRI . '/verifikasi/verifikasi_email';

// Bantuan
$route[$mandiri . '/bantuan'] = MANDIRI . '/bantuan';

// Kehadiran Perangkat Desa
$route[$mandiri . '/kehadiran']              = MANDIRI . '/kehadiran_perangkat';
$route[$mandiri . '/kehadiran/lapor/(:num)'] = MANDIRI . '/kehadiran_perangkat/lapor/$1';

// Peringatan
$route['peringatan'] = 'pelanggan/peringatan';

// Koneksi Database
$route['koneksi-database'] = 'Koneksi_database/index';

// GROUP ROUTES
foreach (glob(APPPATH . '/config/routes/*.php') as $routes_file) {
    require_once $routes_file;
}
