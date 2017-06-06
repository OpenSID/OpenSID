<?php

// Dibutuhkan ketika menggunakan commandline
if( !isset($_SERVER['HTTP_HOST']) ) {
	// Di document_root
	$_SERVER['HTTP_HOST'] = 'localhost';
	// Menggunakan folder
	//$_SERVER['HTTP_HOST'] = 'localhost/OpenSID';
}

// Hidupkan / matikan test. Setelah melakukan test, sebaiknya didisable, untuk keamanan.
define('TEST_ENABLE', false);

// Username & Password untuk login admin
define('TEST_USERNAME', ''); 
define('TEST_PASSWORD', '');
