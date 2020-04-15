<?php
// -------------------------------------------------------------------------
// Konfigurasi database dalam file ini menggantikan konfigurasi di file asli
// SID di donjo-app/config/database.php.
//
// Letakkan username, password dan database sebetulnya di file ini.
// File ini JANGAN di-commit ke GIT. TAMBAHKAN di .gitignore
// -------------------------------------------------------------------------

// Data Konfigurasi MySQL yang disesuaikan

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'opensid';

/*
| Whether to force 'Strict Mode' connections, good for ensuring strict SQL while developing an application.
| Sesuaikan dengan ketentuan hosting
*/ 
$db['default']['stricton'] = TRUE;
?>