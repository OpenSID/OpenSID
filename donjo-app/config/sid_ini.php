<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File ini berisi setting default konfigurasi aplikasi.
| Untuk mengubah letakkan setting yang diinginkan di desa/config/config.php
|--------------------------------------------------------------------------
*/

// Ambil setting SID khusus desa
define("LOKASI_SID_INI", 'desa/config/');
// Konfigurasi default
$config['sebutan_kabupaten'] = 'kabupaten';
$config['sebutan_kabupaten_singkat'] = 'kab.';
$config['sebutan_kecamatan'] = 'kecamatan';
$config['sebutan_kecamatan_singkat'] = 'kec.';
$config['sebutan_desa'] = 'desa';
$config['sebutan_dusun'] = 'dusun';
$config['sebutan_camat'] = 'camat';
$config['website_title'] = 'Website Resmi';
$config['login_title'] = 'OpenSID';
$config['admin_title'] = 'Sistem Informasi Desa';
/* untuk setting theme web */
$config['web_theme'] = 'default';
/*
|--------------------------------------------------------------------------
| Offline Mode
|--------------------------------------------------------------------------
|
| Jika aplikasi hanya digunakan secara offline (tidak akan ditampilkan di
| internet) aktifkan mode offline untuk redirect langsung ke /siteman dan tidak ke /first
|
*/
$config['offline_mode'] = FALSE;

// Apakah akan mengirimkan data statistik ke server sid?
$config['enable_track'] = TRUE;
$config['dev_tracker_ip'] = ''; // Untuk pengembang OpenSID, misalnya "33.33.33.10/tracksid"

// Gunakan log surat terakhir untuk seluruh surat, tanpa memilah jenis surat
// Default: FALSE (gunakan nomor surat terakhir menurut jenis surat)
$config['nomor_terakhir_semua_surat'] = FALSE;

// ==========================================================================

// Konfigurasi tambahan untuk aplikasi
$extra_app_config = FCPATH . LOKASI_SID_INI . 'config.php';
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
