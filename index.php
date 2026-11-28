<?php

/*
 |----------------------------------------------------------------------------
 | LAPISAN KOMPATIBILITAS — document root belum diarahkan ke public/
 |----------------------------------------------------------------------------
 | Entry point resmi adalah public/index.php dan document root web server
 | seharusnya menunjuk ke sana. Berkas ini hanya menjaga instalasi lama yang
 | memperbarui lewat `git pull` tetap hidup tanpa aksi manual — tanpa
 | composer install, tanpa perintah artisan, tanpa perubahan hosting.
 |
 | Situs berjalan, TETAPI .env dan desa/config/database.php masih dapat
 | diakses dari internet. Info Sistem menampilkan peringatan sampai document
 | root dipindahkan. Lihat documents/PANDUAN-STRUKTUR-PUBLIC.md
 |
 | VPS yang docroot-nya sudah di public/: berkas ini berada di luar docroot,
 | jadi tidak pernah dilayani dan tidak berefek apa pun.
 */

require __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php';
