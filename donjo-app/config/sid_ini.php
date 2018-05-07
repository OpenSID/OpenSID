<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Konfigurasi aplikasi di simpan di tabel setting_aplikasi dan dibaca di
| setting_aplikasi.php.
| File ini berisi setting khusus yang tidak disimpan di database.
| Untuk mengubah letakkan setting yang diinginkan di desa/config/config.php
|--------------------------------------------------------------------------
*/
// Folder penyimpanan lokal, upload, kustomisasi dll. atau yang sebelumnya dikatakan FOLDER DESA.
define("STORAGE_PATH", APPPATH .'../desa/');
define("LOKASI_CONFIG_DESA", STORAGE_PATH .'config/');

/*
|--------------------------------------------------------------------------
| Ambil setting konfigurasi dari database
|--------------------------------------------------------------------------
*/
$config["useDatabaseConfig"] = true;

/*
	Uncomment baris berikut untuk menampilkan setting development
	di halaman setting aplikasi.
	Perlu di-setting di sini karena index.php dijalankan
	sesudah pembacaan konfigurasi dari database di setting_model.php
*/
// $config["environment"] = "development";


// Untuk situs yang digunakan untuk demo, seperti http://sid.bangundesa.info,
// buat setting berikut menjadi 'y'
$config['demo'] = '';

$config['defaultAdminAuthInfo'] = array(
    'username' => 'admin',
    'password'=> 'sid304'
);

// ==========================================================================

// Konfigurasi tambahan untuk aplikasi
$extra_app_config = LOKASI_CONFIG_DESA . 'config.php';
if (is_file($extra_app_config)) {
	require_once($extra_app_config);
} else {
  // Harus ada config. Config ini tidak dipakai.
  $config['ini'] = '';
}

/**
  Hapus index.php dari url bila ditemukan .htaccess
  Untuk menggunakan fitur ini, pastikan konfigurasi apache di server SID
  mengizinkan penggunaan .htaccess
*/
if(file_exists(FCPATH.'.htaccess'))
	$config['index_page'] = '';

/* End of file sid_ini.php */
/* Location: ./application/config/sid_ini.php */
