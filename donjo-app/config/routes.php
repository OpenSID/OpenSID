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


//Halaman Depan
// - Artikel
$route['artikel/([0-9-]+)/([0-9-]+)/([0-9-]+)/([a-z0-9-]+)'] = 'first/artikel/$4'; //first/artikel/thn/bln/hr/slug
$route['artikel/([0-9-]+)/([0-9-]+)/([a-z0-9-]+)'] = 'first/artikel/$3'; //first/artikel/thn/bln/slug
$route['artikel/([0-9-]+)/([a-z0-9-]+)'] = 'first/artikel/$2'; //first/artikel/thn/slug
$route['artikel/([a-z0-9-]+)'] = 'first/artikel/$1'; //first/artikel/slug

$route['artikel/([0-9-]+)/([0-9-]+)/([0-9-]+)/([0-9-]+)'] = 'first/artikel/$4'; //first/artikel/thn/bln/hr/id
$route['artikel/([0-9-]+)/([0-9-]+)/([0-9-]+)'] = 'first/artikel/$3'; //first/artikel/thn/bln/id
$route['index.php/artikel/([0-9-]+)/([0-9-]+)'] = 'first/artikel/$2'; //first/artikel/thn/id
$route['artikel/([0-9-]+)'] = 'first/artikel/$1'; //first/artikel/id

// - Statistik
$route['statistik/([0-9-]+)'] = 'first/statistik/$1'; //first/statistik/id

// - Kategori
$route['kategori/([0-9-]+)'] = 'first/kategori/$1'; //first/statistik/slug
$route['kategori/([a-z0-9-]+)'] = 'first/kategori/$1'; //first/statistik/id

// - Wilayah
$route['wilayah'] = 'first/wilayah'; //first/wilayah

// - Peraturan_desa
$route['peraturan-desa'] = 'first/peraturan_desa'; //first/peraturan_desa

// - Informasi publik
$route['informasi-publik'] = 'first/informasi_publik'; //first/informasi_publik

// - Wilayah
$route['arsip'] = 'first/arsip'; //first/arsip

// - Dpt
$route['dpt'] = 'first/dpt'; //first/dpt