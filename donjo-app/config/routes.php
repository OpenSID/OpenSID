<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'first';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['sitemap\.xml'] = "Sitemap/index";
$route['feed\.xml'] = "Feed/index";
$route ['ppid'] = "Api_informasi_publik/ppid";

// Artikel
$route['artikel/(:num)'] = 'first/artikel/$1'; // Contoh : artikel/1
$route['artikel/(:num)/(:num)/(:num)/(:any)'] = 'first/artikel/$4'; // Contoh : artikel/2020/5/15/contoh-artikel
// Artikel lama (Agar url lama masih dpt di akases)
$route['first/artikel/(:num)'] = 'first/artikel/$1'; // Contoh : Contoh : first/artikel/1
$route['first/artikel/(:num)/(:num)/(:num)/(:any)'] = 'first/artikel/$4'; // Contoh : first/artikel/2020/5/15/contoh-artikel

// Route bumindes
$route['bumindes_umum/([a-z_]+)/(:any)'] = "buku_umum/bumindes_umum/$1/$2";
$route['bumindes_umum/([a-z_]+)'] = "buku_umum/bumindes_umum/$1";
$route['bumindes_umum'] = "buku_umum/bumindes_umum";

$buku_umum = ['ekspedisi', 'lembaran_desa', 'pengurus', 'surat_keluar', 'surat_masuk'];
foreach ($buku_umum as $menu)
{
	$route["{$menu}/([a-z_]+)/(:any)/(:any)/(:any)"] = "buku_umum/{$menu}/$1/$2/$3/$4";
	$route["{$menu}/([a-z_]+)/(:any)/(:any)"] = "buku_umum/{$menu}/$1/$2/$3";
	$route["{$menu}/([a-z_]+)/(:any)"] = "buku_umum/{$menu}/$1/$2";
	$route["{$menu}/([a-z_]+)"] = "buku_umum/{$menu}/$1";
	$route["{$menu}"] = "buku_umum/{$menu}";
}

$route['dokumen_sekretariat/([a-z_]+)/(:any)/(:any)/(:any)/(:any)'] = "buku_umum/dokumen_sekretariat/$1/$2/$3/$4/$5";
$route['dokumen_sekretariat/([a-z_]+)/(:any)/(:any)/(:any)'] = "buku_umum/dokumen_sekretariat/$1/$2/$3/$4";
$route['dokumen_sekretariat/([a-z_]+)/(:any)/(:any)'] = "buku_umum/dokumen_sekretariat/$1/$2/$3";
$route['dokumen_sekretariat/([a-z_]+)/(:any)'] = "buku_umum/dokumen_sekretariat/$1/$2";
$route['dokumen_sekretariat/([a-z_]+)'] = "buku_umum/dokumen_sekretariat/$1";
$route['dokumen_sekretariat'] = "buku_umum/dokumen_sekretariat";

// Route untuk menghilangkan 'first' dari URL web
// Kategori artikel
$route['artikel/kategori/(:any)'] = 'first/kategori/$1'; // Contoh : Contoh : artikel/kategori/berita-desa
$route['artikel/kategori/(:any)/(:num)'] = 'first/kategori/$1/$2'; // Contoh : Contoh : artikel/kategori/berita-desa/1

$route['index/(:num)'] = 'first/index/$1';
$route['(:num)'] = 'first/index/$1';
$route['arsip'] = 'first/arsip';
$route['arsip/(:num)'] = 'first/arsip/$1';
$route['add_comment/(:any)'] = 'first/add_comment/$1';
$route['ambil_data_covid'] = 'first/ambil_data_covid';
$route['load_apbdes'] = 'first/load_apbdes';
$route['logout'] = 'first/logout';
$route['ganti'] = 'first/ganti';
$route['auth'] = 'first/auth';

// Halaman statis
$route['data-suplemen/(:num)'] = 'first/suplemen/$1';
$route['informasi_publik'] = 'first/informasi_publik';
$route['peraturan_desa'] = 'first/peraturan_desa';
$route['data_analisis'] = 'first/data_analisis';
$route['data_analisis/(.+)'] = 'first/data_analisis/$1';
$route['peta'] = 'first/peta';
$route['load_aparatur_desa'] = 'first/load_aparatur_desa';
$route['load_aparatur_wilayah/(.+)'] = 'first/load_aparatur_wilayah/$1';


// Mandiri Auth
$route['layanan-mandiri/masuk'] = 'layanan_mandiri/masuk'; // Tampilan Masuk
$route['layanan-mandiri/cek'] = 'layanan_mandiri/masuk/cek'; // Cek Masuk
// Mandiri Beranda
$route['layanan-mandiri'] = 'layanan_mandiri/beranda'; // Beranda/Halaman Awal
// Mandiri Profil
$route['layanan-mandiri/profil'] = 'layanan_mandiri/beranda/profil'; // Profil
$route['layanan-mandiri/cetak-biodata'] = 'layanan_mandiri/beranda/cetak_biodata'; // Cetak Penduduk
$route['layanan-mandiri/ganti-pin'] = 'layanan_mandiri/beranda/ganti_pin'; // Ganti PIN
$route['layanan-mandiri/proses-ganti-pin'] = 'layanan_mandiri/beranda/proses_ganti_pin'; // Proses Ganti PIN
$route['layanan-mandiri/unduh-berkas/(:num)'] = 'layanan_mandiri/beranda/unduh_berkas/$1'; // Proses Unduh Berkas
$route['layanan-mandiri/cetak-kk'] = 'layanan_mandiri/beranda/cetak_kk'; // Cetak KK
$route['layanan-mandiri/keluar'] = 'layanan_mandiri/beranda/keluar'; // Keluar
// Mandiri Pesan
$route['layanan-mandiri/pesan-masuk'] = 'layanan_mandiri/pesan/index/2'; // Pesan Masuk
$route['layanan-mandiri/pesan-keluar'] = 'layanan_mandiri/pesan/index/1'; // Pesan Keluar
$route['layanan-mandiri/pesan/tulis'] = 'layanan_mandiri/pesan/tulis'; // Tulis Pesan
$route['layanan-mandiri/pesan/balas'] = 'layanan_mandiri/pesan/tulis'; // Balas Pesan
$route['layanan-mandiri/pesan/kirim'] = 'layanan_mandiri/pesan/kirim'; // Kirim Pesan
$route['layanan-mandiri/pesan/baca/(:num)/(:num)'] = 'layanan_mandiri/pesan/baca/$1/$2'; // Baca Pesan
// Mandiri Surat
$route['layanan-mandiri/arsip-surat'] = 'layanan_mandiri/surat/index/2'; // Arsip Surat
$route['layanan-mandiri/permohonan-surat'] = 'layanan_mandiri/surat/index/1'; // Permohonan Surat
$route['layanan-mandiri/surat/buat-surat'] = 'layanan_mandiri/surat/buat_surat'; // Buat Surat Permohonan
$route['layanan-mandiri/surat/buat-surat/(:num)'] = 'layanan_mandiri/surat/buat_surat/$1'; //Ubah Surat Permohonan yg tdk lengkap
// Mandiri Bantuan
$route['layanan-mandiri/bantuan'] = 'layanan_mandiri/bantuan'; // Bantuan
