<?php

class Folder_desa_model extends CI_Model {

/*
	Dimasukkan di autoload. Supaya folder desa dibuat secara otomatis menggunakan
	desa-contoh apabila belum ada. Yaitu pada pertama kali menginstall OpenSID.

	Perubahan folder desa pada setiap rilis dilakukan sebagai bagian dari
	Database > Migrasi DB.
*/
	public function __construct()
	{
		parent::__construct();
		$this->periksa_folder_desa();
	}

	public function periksa_folder_desa()
	{
		$this->salin_contoh();
	}

	private function salin_contoh()
	{
		if (!file_exists('desa'))
		{
			mkdir('desa');
			xcopy('desa-contoh', 'desa');
		}
	}

	public function amankan_folder_desa()
	{
		$this->salin_file('desa', 'index.html', 'desa-contoh/index.html');
		$this->salin_file('desa/arsip', '.htaccess', 'desa-contoh/arsip/.htaccess');
		$this->salin_file('desa/upload', '.htaccess', 'desa-contoh/upload/media/.htaccess');
		$this->salin_file('desa/upload/dokumen', '.htaccess', 'desa-contoh/upload/dokumen/.htaccess', $ganti=true);
	}

	public function salin_file($cek, $cari, $contoh, $ganti=false)
	{
		if ( $ganti || ! file_exists("$cek/$cari")) copy($contoh, "$cek/$cari");

		foreach (glob("$cek/*", GLOB_ONLYDIR) as $folder)
		{
			$file = "$folder/$cari";

			if ( $ganti || ! file_exists($file)) copy($contoh, $file);

			$this->salin_file($folder, $cari, $contoh);
		}
	}

}
