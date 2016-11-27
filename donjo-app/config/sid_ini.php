<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// Ambil setting SID khusus desa
define("LOKASI_SID_INI", 'desa/config/');

// Konfigurasi default
$config['sebutan_desa'] = 'desa';
$config['sebutan_dusun'] = 'dusun';
$config['login_title'] = 'OpenSID';
$config['admin_title'] = 'Sistem Informasi Desa';

// Konfigurasi tambahan untuk aplikasi
$extra_app_config = FCPATH . LOKASI_SID_INI . 'config.php';
if (is_file($extra_app_config)) {
	require_once($extra_app_config);
} else {
  // Harus ada config. Config ini tidak dipakai.
  $config['ini'] = '';
}

/* End of file sid_ini.php */
/* Location: ./application/config/sid_ini.php */