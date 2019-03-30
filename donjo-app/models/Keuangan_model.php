<?php
require 'vendor/autoload.php';

use RebaseData\Client;
class Keuangan_model extends CI_model {

	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * Hapus tabel klasifikasi_surat dan ganti isinya
	 * dengan data dari berkas csv.
	 * Baris pertama berisi nama kolom tabel.
	*/
	public function convertMDE($inputFiles)
	{
		// $inputFiles = ['upload/keuangan/DataAPBDES2018.mde'];
		$client = new Client('freemium');
  	$tables = $client->getDatabaseTables($inputFiles);
		if ($tables) {
			$versi_siskeudes = $client->getDatabaseTableRows($inputFiles,'Ref_Version');
			if ($versi_siskeudes) {
				$data2['versi'] = $versi_siskeudes[1][0];
				$data2['tgl_rilis'] = $versi_siskeudes[1][1];
				$outp = $this->db->insert('keuangan_ref_version', $data2);
			}else{
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = 'File Tidak Ada';
				return;
			}
		}else{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = 'File Tidak Ada';
			return;
		}

	}

}
