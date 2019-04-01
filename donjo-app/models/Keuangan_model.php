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
	public function convertMDE($inputFiles,$data)
	{
		// print_r($data['tahun_anggaran']);die();
		$inputFiles = ['upload/keuangan/DataAPBDES2018Banglidemulih.mde'];
		$client = new Client('freemium');
  	$tables = $client->getDatabaseTables($inputFiles);
		// print_r($tables);die();
		if ($tables) {
			$versi_siskeudes = $client->getDatabaseTableRows($inputFiles,'Ref_Version');
			if ($versi_siskeudes) {
				$data2['versi_database'] = $versi_siskeudes[1][0];
				$data2['tahun_anggaran'] = $data['tahun_anggaran'];
				if ($this->db->insert('keuangan_master', $data2)) {
					$anggaran = $client->getDatabaseTableRows($inputFiles,'Ta_Anggaran');
					if ($anggaran) {
						$i = 1;
						$id_master_keuangan = $this->db->insert_id();
						foreach ($anggaran as $value) {
							if ($i > 1) {
								$dataanggaran['id_keuangan_master'] = $id_master_keuangan;
								$dataanggaran['KURincianSD'] = $value[2];
								$dataanggaran['KD_Rincian'] = $value[3];
								$dataanggaran['RincianSD'] = $value[4];
								$dataanggaran['anggaran'] = $value[5];
								$dataanggaran['anggaranPAK'] = $value[6];
								$dataanggaran['anggaranStlhPAK'] = $value[7];
								$dataanggaran['Belanja'] = $value[8];
								$dataanggaran['Kd_keg'] = $value[9];
								$dataanggaran['SumberDana'] = $value[10];
								$dataanggaran['kd_desa'] = $value[11];
								$dataanggaran['tgl_posting'] = $value[12];
								$this->db->insert('keuangan_anggaran', $dataanggaran);
							}
							$i++;
						}
		
					}

				}

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

	public function cekMasterKeuangan($versi_database,$tahun_anggaran)
	{
		$sql = "SELECT * FROM keuangan_master WHERE versi_database like '".$versi_database."%' and tahun_anggaran = '".$tahun_anggaran."'  ";
		$query	= $this->db->query($sql);
		$data	= $query->result_array();
		return $data;
	}

}
