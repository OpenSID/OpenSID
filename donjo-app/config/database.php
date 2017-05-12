<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$active_group = 'default';
$active_record = TRUE;

$konfigurasi_db = KonfigurasiDatabase();
if(is_file($konfigurasi_db)) {
  include($konfigurasi_db);
}else{
  $db['default']['hostname'] = 'localhost';
  $db['default']['username'] = 'root';
  $db['default']['password'] = '';
  $db['default']['database'] = 'opensid';
}
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;
