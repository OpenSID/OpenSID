<?php
// ----------------------------------------------------------------------------
// Konfigurasi aplikasi dalam berkas ini merupakan setting konfigurasi tambahan
// SID. Letakkan setting konfigurasi ini di desa/config/config.php.
// ----------------------------------------------------------------------------

// key dari layanan google map
$config['google_key'] = 'ganti-dengan-google-api-key';
$config['libreoffice_path'] = "C:\Program Files (x86)\LibreOffice 4\program";

// penyesuaian judul yang muncul di browser
$config['website_title'] = 'Website Resmi';
$config['login_title'] = 'Login OpenSID';
$config['admin_title'] = 'Sistem Informasi';

// untuk mengganti penamaan wilayah
$config['sebutan_kabupaten'] = 'kabupaten';
$config['sebutan_kabupaten_singkat'] = 'kab.';
$config['sebutan_kecamatan'] = 'kecamatan';
$config['sebutan_kecamatan_singkat'] = 'kec.';
$config['sebutan_desa'] = 'desa';
$config['sebutan_dusun'] = 'dusun';
$config['sebutan_camat'] = 'camat';

// apakah hanya akan menggunakan localhost saja?
$config['offline_mode'] = FALSE;

// Apakah akan mengirimkan data statistik ke server sid?
$config['enable_track'] = TRUE;

// Gunakan log surat terakhir untuk seluruh surat, tanpa memilah jenis surat
// Default: FALSE (gunakan nomor surat terakhir menurut jenis surat)
$config['nomor_terakhir_semua_surat'] = FALSE;

// Ganti tema penampilan web
// $config['web_theme'] = 'hadakewa';

