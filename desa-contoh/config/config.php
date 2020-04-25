<?php
// ----------------------------------------------------------------------------
// Konfigurasi aplikasi dalam berkas ini merupakan setting konfigurasi tambahan
// SID. Letakkan setting konfigurasi ini di desa/config/config.php.
// ----------------------------------------------------------------------------

/*
	Uncomment jika situs ini untuk demo. Pada demo, user admin tidak bisa dihapus
	dan username/password tidak bisa diubah
*/
// $config['demo'] = 'y';

/*
	Password untuk File Manager yg digunakan pada form isian artikel.
	Gunakan password yg sulit ditebak manusia maupun program otomatis.
*/
	$config['file_manager'] = "GantiKunciDesa";

// Setting ini untuk menentukan user yang dipercaya. User dengan id di setting ini
// dapat membuat artikel berisi video yang aktif ditampilkan di Web.
// Misalnya, ganti dengan id = 1 jika ingin membuat pengguna admin sebagai pengguna terpecaya.
	$config['user_admin'] = 0;
	
/*
	Setting untuk tampilkan data Covid-19. Untuk menyembunyikan ganti menjadi nilai 0;
	Untuk menampilkan data provinsi, gunakan setting 'provinsi_covid'.
	Kode provinsi sesuai dengan yg di http://pusatkrisis.kemkes.go.id/daftar-kode-provinsi
*/
	$config['covid_data'] = 1;
	$config['provinsi_covid'] = 51; // kode provinsi. Comment baris ini untuk menampilkan data Indonesia
	$config['covid_desa'] = 1; // Tampilkan status COVID-19 dari data OpenSID desa
	
