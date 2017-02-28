<?php
// ----------------------------------------------------------------------------
// Konfigurasi aplikasi dalam berkas ini merupakan setting konfigurasi tambahan
// SID. Letakkan setting konfigurasi ini di desa/config/config.php.
// ----------------------------------------------------------------------------

// key dari layanan google map
$config['google_key'] = 'ganti-dengan-google-api-key';
$config['libreoffice_path'] = "C:\Program Files (x86)\LibreOffice 4\program";

// penyesuaian judul yang muncul di browser
$config['website_title'] = 'Website Resmi Desa Kami';
$config['login_title'] = 'OpenSID';
$config['admin_title'] = 'Sistem Informasi Desa';

// untuk mengganti penamaan wilayah 'desa'
$config['sebutan_desa'] = 'nagari';
$config['sebutan_dusun'] = 'jorong';

// apakah hanya akan menggunakan localhost saja?
$config['offline_mode'] = FALSE;

// Apakah akan mengirimkan data statistik ke server sid?
$config['enable_track'] = TRUE;
