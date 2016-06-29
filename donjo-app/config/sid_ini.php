<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// Ambil setting SID khusus desa
define("LOKASI_SID_INI", 'desa/config/');

$config['sid'] = parse_ini_file(LOKASI_SID_INI."sid.ini");


/* End of file sid_ini.php */
/* Location: ./application/config/sid_ini.php */