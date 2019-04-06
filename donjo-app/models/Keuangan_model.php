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
					$anggaran = $client->getDatabaseTableRows($inputFiles,'Ta_RAB');
					if ($anggaran) {
						$i = 1;
						$id_master_keuangan = $this->db->insert_id();
						foreach ($anggaran as $value) {
							if ($i > 1) {
								$dataanggaran['id_keuangan_master'] = $id_master_keuangan;
								$dataanggaran['kd_desa'] = $value[1];
								$dataanggaran['kd_keg'] = $value[2];
								$dataanggaran['kd_rincian'] = $value[3];
								$dataanggaran['anggaran'] = $value[4];
								$dataanggaran['anggaranPAK'] = $value[5];
								$dataanggaran['anggaranStlhPAK'] = $value[6];
								$this->db->insert('keuangan_ta_rab', $dataanggaran);
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
	public function tahun_anggaran()
	{
		$this->db->select('tahun_anggaran');
		$result = $this->db->get('keuangan_master')->row();
		return $result->tahun_anggaran;
	}

	public function anggaran_keuangan()
	{
		$this->db->select_sum('anggaran');
		$this->db->where('keuangan_ta_rab.id_keuangan_master ', 1);
		$result = $this->db->get('keuangan_ta_rab')->row();
		return $result->anggaran;
	}

	public function anggaranPAK()
	{
		$this->db->select_sum('anggaranPAK');
		$this->db->where('keuangan_ta_rab.id_keuangan_master ', 1);
		$result = $this->db->get('keuangan_ta_rab')->row();
		return $result->anggaranPAK;
	}

	public function anggaranStlhPAK()
	{
		$this->db->select_sum('anggaranStlhPAK');
		$this->db->where('keuangan_ta_rab.id_keuangan_master ', 1);
		$result = $this->db->get('keuangan_ta_rab')->row();
		return $result->anggaranStlhPAK;
	}

	public function cekMasterKeuangan($versi_database,$tahun_anggaran)
	{
		$sql = "SELECT * FROM keuangan_master WHERE versi_database like '".$versi_database."%' and tahun_anggaran = '".$tahun_anggaran."'  ";
		$query	= $this->db->query($sql);
		$data	= $query->result_array();
		return $data;
	}

}
