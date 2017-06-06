<?php
// Init
if ( !file_exists('config.php') )
	exit(); // silent is gold!

require_once 'config.php';
if ( TEST_ENABLE != true )
	die('Test disabled');
	
if ( TEST_USERNAME == '' || TEST_PASSWORD == '') 
	die('Username or password empty.');

defined('BASEPATH') OR define('BASEPATH', NULL);
defined('FCPATH') OR define('FCPATH', NULL);
require_once '../desa/config/config.php';
require_once '../donjo-app/config/sid_ini.php';

if( !isset($_SERVER['HTTP_HOST']) ) 
	$_SERVER['HTTP_HOST'] = 'localhost';
	
define('TEST_HOST', 'http://' . $_SERVER['HTTP_HOST']);
define('TEST_ADMIN_TITLE', $config['admin_title']);
