<?php
// -------------------------------------------------------------------------
// Konfigurasi database dalam file ini menggantikan konfigurasi di file asli
// SID di donjo-app/config/database.php.
//
// Letakkan username, password dan database sebetulnya di file ini.
// File ini JANGAN di-commit ke GIT. TAMBAHKAN di .gitignore
// -------------------------------------------------------------------------
// Data Konfigurasi MySQL yang disesuaikan
$db['default']['hostname'] = "%DB_HOSTNAME%";
$db['default']['username'] = "%DB_USERNAME%";
$db['default']['password'] = "%DB_PASSWORD%";
$db['default']['database'] = "%DB_NAME%";