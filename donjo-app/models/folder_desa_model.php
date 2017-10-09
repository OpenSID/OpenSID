<?php class Folder_desa_Model extends CI_Model{

/*
	Dimasukkan di autoload. Supaya folder desa dibuat secara otomatis menggunakan
	desa-contoh apabila belum ada. Yaitu pada pertama kali menginstall OpenSID.

	Perubahan folder desa pada setiap rilis dilakukan sebagai bagian dari
	Database > Migrasi DB.
*/
	function __construct(){
		parent::__construct();
		$this->periksa_folder_desa();
	}

	function periksa_folder_desa() {
		$this->salin_contoh();
	}

	private function salin_contoh() {
		if (!file_exists('/desa')) {
			mkdir('desa');
			xcopy('desa-contoh', 'desa');
		}
	}


}