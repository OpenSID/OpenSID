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
$route['default_controller'] = 'main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['sitemap\.xml'] = "Sitemap/index";
$route['feed\.xml'] = "Feed/index";
$route ['ppid'] = "Api_informasi_publik/ppid";

// ARTIKEL WEB
// Route baru
$route['artikel/(:num)'] = 'first/artikel/$1'; // Contoh : artikel/1
$route['artikel/(:num)/(:num)/(:num)/(:any)'] = 'first/artikel/$4'; // Contoh : artikel/2020/5/15/contoh-artikel

// Route lama (Agar url lama masih dpt di akases)
$route['first/artikel/(:num)'] = 'first/artikel/$1'; // Contoh : Contoh : first/artikel/1
$route['first/artikel/(:num)/(:num)/(:num)/(:any)'] = 'first/artikel/$4'; // Contoh : first/artikel/2020/5/15/contoh-artikel

$route['bumindes_umum/([a-z_]+)/(:any)'] = "buku_umum/bumindes_umum/$1/$2";
$route['bumindes_umum/([a-z_]+)'] = "buku_umum/bumindes_umum/$1";
$route['bumindes_umum'] = "buku_umum/bumindes_umum";

$route['pengurus/([a-z_]+)/(:any)/(:any)/(:any)'] = "buku_umum/pengurus/$1/$2/$3/$4";
$route['pengurus/([a-z_]+)/(:any)/(:any)'] = "buku_umum/pengurus/$1/$2/$3";
$route['pengurus/([a-z_]+)/(:any)'] = "buku_umum/pengurus/$1/$2";
$route['pengurus/([a-z_]+)'] = "buku_umum/pengurus/$1";
$route['pengurus'] = "buku_umum/pengurus";

$route['surat_keluar/([a-z_]+)/(:any)/(:any)/(:any)'] = "buku_umum/surat_keluar/$1/$2/$3/$4";
$route['surat_keluar/([a-z_]+)/(:any)/(:any)'] = "buku_umum/surat_keluar/$1/$2/$3";
$route['surat_keluar/([a-z_]+)/(:any)'] = "buku_umum/surat_keluar/$1/$2";
$route['surat_keluar/([a-z_]+)'] = "buku_umum/surat_keluar/$1";
$route['surat_keluar'] = "buku_umum/surat_keluar";

$route['ekspedisi/([a-z_]+)/(:any)/(:any)/(:any)'] = "buku_umum/ekspedisi/$1/$2/$3/$4";
$route['ekspedisi/([a-z_]+)/(:any)/(:any)'] = "buku_umum/ekspedisi/$1/$2/$3";
$route['ekspedisi/([a-z_]+)/(:any)'] = "buku_umum/ekspedisi/$1/$2";
$route['ekspedisi/([a-z_]+)'] = "buku_umum/ekspedisi/$1";
$route['ekspedisi'] = "buku_umum/ekspedisi";

$route['surat_masuk/([a-z_]+)/(:any)/(:any)/(:any)'] = "buku_umum/surat_masuk/$1/$2/$3/$4";
$route['surat_masuk/([a-z_]+)/(:any)/(:any)'] = "buku_umum/surat_masuk/$1/$2/$3";
$route['surat_masuk/([a-z_]+)/(:any)'] = "buku_umum/surat_masuk/$1/$2";
$route['surat_masuk/([a-z_]+)'] = "buku_umum/surat_masuk/$1";
$route['surat_masuk'] = "buku_umum/surat_masuk";

$route['dokumen_sekretariat/([a-z_]+)/(:any)/(:any)/(:any)/(:any)'] = "buku_umum/dokumen_sekretariat/$1/$2/$3/$4/$5";
$route['dokumen_sekretariat/([a-z_]+)/(:any)/(:any)/(:any)'] = "buku_umum/dokumen_sekretariat/$1/$2/$3/$4";
$route['dokumen_sekretariat/([a-z_]+)/(:any)/(:any)'] = "buku_umum/dokumen_sekretariat/$1/$2/$3";
$route['dokumen_sekretariat/([a-z_]+)/(:any)'] = "buku_umum/dokumen_sekretariat/$1/$2";
$route['dokumen_sekretariat/([a-z_]+)'] = "buku_umum/dokumen_sekretariat/$1";
$route['dokumen_sekretariat'] = "buku_umum/dokumen_sekretariat";
