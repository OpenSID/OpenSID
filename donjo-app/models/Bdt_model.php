<?php class Bdt_model extends CI_Model {

	private $jml_baris;
	private $baris_pertama;
	private $kolom;
	private $kolom_subjek;
	private $kolom_indikator_pertama;
	private $abaikan;
	private $list_subjek;
	private $list_id_subjek;

	public function __construct()
	{
		parent::__construct();

		$this->kolom = array(
			'id_rtm' => 2,
			'nama' => 80,
			'nik' => 81,
			'rtm_level' => 82,
			'awal_respon_rt' => 14,
			'awal_respon_penduduk' => 82
		);
	}

	private function file_import_valid()
	{
		// error 1 = UPLOAD_ERR_INI_SIZE; lihat Upload.php
		// TODO: pakai cara upload yg disediakan Codeigniter
		if ($_FILES['bdt']['error'] == 1)
		{
			$upload_mb = max_upload();
			$_SESSION['error_msg'].= " -> Ukuran file melebihi batas " . $upload_mb . " MB";
			$_SESSION['success']=-1;
			return false;
		}
	  $tipe_file = TipeFile($_FILES['bdt']);
		$mime_type_excel = array("application/vnd.ms-excel", "application/octet-stream");
		if(!in_array($tipe_file, $mime_type_excel))
		{
			$_SESSION['error_msg'].= " -> Jenis file salah: " . $tipe_file;
			$_SESSION['success']=-1;
			return false;
		}
		return true;
	}

	/*
	 * 1. Impor pengelompokan rumah tangga
	 * 2. Impor data BDT 2015 ke dalam analisis_respon
	 *
	 * Abaikan subjek di data BDT yang tidak ada di database
	*/
	public function impor()
	{
		$_SESSION['error_msg'] = '';
		$_SESSION['success'] = 1;
		if ($this->file_import_valid() == false)
		{
			return;
		}

		// Pakai parameter 'false' untuk mengurangi penggunaan memori
		// https://github.com/jasonrogena/php-excel-reader/issues/96
		$this->load->library('Spreadsheet_Excel_Reader');
		$data = new Spreadsheet_Excel_Reader($_FILES['bdt']['tmp_name'], false);
		// Baca jumlah baris berkas BDT
		$this->jml_baris = $data->rowcount($sheet_index=0);
		$this->baris_pertama = $this->cari_baris_pertama($data, $this->jml_baris);
		if ($this->baris_pertama <= 0)
		{
			$_SESSION['error_msg'].= " -> Tidak ada data";
			$_SESSION['success']=-1;
			return;
		}

		// BDT2015 terbatas pada subjek rumah tangga dan penduduk
		if ($_SESSION['subjek_tipe'] == 3)
		{
			// Rumah tangga
			$this->kolom_subjek = $this->kolom['id_rtm'];
			$this->kolom_indikator_pertama = $this->kolom['awal_respon_rt'];
		}
		else
		{
			// Penduduk
			$this->kolom_subjek = $this->kolom['nik'];
			$this->kolom_indikator_pertama = $this->kolom['awal_respon_penduduk'];
		}

		$data_sheet = $data->sheets[0]['cells'];
		$this->impor_respon($data_sheet);
	}

	private function impor_respon($data_sheet)
	{
		$gagal = 0;
		$ada = 0;
		$sudah_proses = array();
		$per = $this->analisis_respon_model->get_aktif_periode();
		$indikator = $this->db->where('id_master', $_SESSION['analisis_master'])
				->order_by('id')->get('analisis_indikator')
				->result_array();

		$respon = array();
		for ($i=$this->baris_pertama; $i<=$this->jml_baris; $i++)
		{
			$data_subjek = $this->tulis_rtm($data_sheet[$i], $rtm);
			if (!$data_subjek)
			{
				$gagal++;
				continue; // Jangan impor jika NIK tidak ada di database
			}
			// Proses setiap subjek sekali saja
			if (!in_array($data_sheet[$i][$this->kolom_subjek], $sudah_proses))
			{
				// $list_id_subjek[nik] = id-penduduk atau $list_id_subjek[id_rtm] = id-rumah-tangga
				if ($_SESSION['subjek_tipe'] == 3)
					$this->list_id_subjek[$data_sheet[$i][$this->kolom_subjek]] = $rtm;
				else
					$this->list_id_subjek[$data_sheet[$i][$this->kolom_subjek]] = $data_subjek['id_penduduk'];
				$this->siapkan_respon($indikator, $per, $data_sheet[$i], $respon);
				$sudah_proses[] = $data_sheet[$i][$this->kolom_subjek];
				$ada++;
			}
		}

		// echo '<br><br>';
		// echo var_dump($this->list_id_subjek);
		$this->hapus_respon($this->list_id_subjek);

		// echo '<br><br>';
		// echo var_dump($respon);

		if (!empty($respon))
			$outp = $this->db->insert_batch('analisis_respon', $respon);
		else
			$outp = false;
		$this->analisis_respon_model->pre_update();

		if (!$outp) $_SESSION['success'] = -1;

		$nama_subjek = ($_SESSION['subjek_tipe'] == 3) ? 'RUMAH TANGGA' : 'PENDUDUK';
		echo "<br>JUMLAH PENDUDUK GAGAL    : $gagal</br>";
		echo "<br>JUMLAH $nama_subjek BERHASIL : $ada</br>";
		echo "<a href='".site_url()."analisis_respon'>LANJUT</a>";
	}

	/*
		Hapus semua respon untuk semua subjek pada periode aktif
	*/
	private function hapus_respon($list_id_subjek)
	{
		if (empty($list_id_subjek)) return;

		$per = $this->analisis_respon_model->get_aktif_periode();
		$prefix = $list_id_subjek_str = '';
		foreach ($list_id_subjek as $subjek => $id)
		{
	    $list_id_subjek_str .= $prefix . "'" . $id . "'";
	    $prefix = ', ';
		}

		// echo '<br><br>';
		// echo var_dump($list_id_subjek_str);

		$this->db->where("id_subjek in($list_id_subjek_str)")
				->where('id_periode',$per)
				->delete('analisis_respon');
	}

	private function cari_baris_pertama($data, $jml_baris)
	{
		if ($jml_baris <=1 )
			return 0;

		$ada_baris = false;
		// Baris pertama baris judul kolom
		for ($i=2; $i<=$jml_baris; $i++)
		{
			// Baris kedua yang mungkin ditambahkan untuk memudahkan penomoran kolom
			if($data->val($i,1) == 'KOLOM' or empty($data->val($i, 1)))
			{
				continue;
			}
			$ada_baris = true;
			$baris_pertama = $i;
			break;
		}
		if ($ada_baris) return $baris_pertama;
		else return 0;
	}

	private function tulis_rtm($baris, &$rtm)
	{
		$id_rtm = $baris[$this->kolom['id_rtm']];
		$rtm_level = $baris[$this->kolom['rtm_level']];
		if ($rtm_level > 1) $rtm_level = 2; //Hanya rekam kepala & anggota rumah tangga
		$nik = $baris[$this->kolom['nik']];

		$query = $this->db->where('nik', $nik)->get('tweb_penduduk');
		if ($query->num_rows() == 0)
		{
			// Laporkan penduduk BDT tidak ada di database
			echo "<a style='color: red;'>".$id_rtm." ".$rtm_level." ".$nik." ".$baris[$this->kolom['nama']]." == tidak ditemukan di database penduduk. </a><br>";
			return false;
		}
		else
		{
			// echo "<a>".$id_rtm." ".$rtm_level." ".$nik." ".$baris[$this->kolom['nama']]." == ok. </a><br>";
			$rtm = $this->db->where('no_kk', $id_rtm)->get('tweb_rtm')->row()->id;
			if ($rtm)
			{
				// Update rumah tangga
				if ($rtm_level == 1)
				{
					$this->db->where('id', $rtm)
						->update('tweb_rtm',array('nik_kepala' => $query->row()->id));
				}
			}
			else
			{
				// Tambah rumah tangga
				$rtm_data = array();
				$rtm_data['no_kk'] = $id_rtm;
				if ($rtm_level == 1) $rtm_data['nik_kepala'] = $query->row()->id;
	      $this->db->insert('tweb_rtm', $rtm_data);
	      $rtm = $this->db->insert_id();
			}
		}
		$penduduk = array();
		$penduduk['id_rtm'] = $id_rtm;
		$penduduk['rtm_level'] = $rtm_level;
		$penduduk['updated_at'] = date('Y-m-d H:i:s');
		$penduduk['updated_by'] = $this->session->user;
		$this->db->where('nik', $nik)->update('tweb_penduduk', $penduduk);
		$penduduk['id_penduduk'] = $query->row()->id;
		return $penduduk;
	}

	private function siapkan_respon($indikator, $per, $baris, &$respon)
	{
		foreach ($indikator as $key => $indi)
		{
			$isi = $baris[$this->kolom_indikator_pertama + $key];
			switch ($indi['id_tipe'])
			{
				case 1:
					$list_parameter = $this->parameter_pilihan_tunggal($indi['id'], $isi);
					break;
				case 2:
					$list_parameter = $this->parameter_pilihan_ganda($indi['id'], $isi);
					break;

				default:
					$list_parameter = $this->parameter_isian($indi['id'], $isi);
					break;
			}
			// Himpun respon untuk semua indikator untuk semua baris
			foreach ($list_parameter as $parameter)
			{
				if (!empty($parameter))
				{
					$respon[] = array(
						'id_indikator' 	=> $indi['id'],
						'id_subjek'			=> $this->list_id_subjek[$baris[$this->kolom_subjek]],
						'id_periode'		=> $per,
						'id_parameter' 	=> $parameter
					);
				}
			}
		}
	}

	private function parameter_pilihan_tunggal($id_indikator, $isi)
	{
		$param = $this->db->select('id')
				->where('id_indikator',$id_indikator)
				->where('kode_jawaban',$isi)
				->get('analisis_parameter')
				->row_array();
		if ($param)
		{
			$in_param = $param['id'];
		}
		else
		{
			if ($isi == "")
				$in_param = 0;
			else
				$in_param = -1;
		}
		return array($in_param);
	}

	private function parameter_pilihan_ganda($id_indikator, $isi)
	{
		if (empty($isi)) return array(null);
		$id_isi = explode(",", $isi);
		$in_param = array();
		foreach ($id_isi as $isi)
		{
			$param = $this->db->select('id')
					->where('id_indikator', $id_indikator)
					->where('kode_jawaban', $isi)
					->get('analisis_parameter')
					->row_array();
			if ($param['id'] != "")
			{
				$in_param[] = $param['id'];
			}
		}
		return $in_param;
	}

	private function parameter_isian($id_indikator, $isi)
	{
		if (empty($isi)) return array(null);
		$param = $this->db->select('id')
				->where('id_indikator', $id_indikator)
				->where('jawaban', $isi)
				->get('analisis_parameter')
				->row_array();

		// apakah sdh ada jawaban yg sama
		if ($param)
		{
			$in_param = $param['id'];
		}
		else
		{
			// simpan setiap jawaban yang baru
			$parameter = array();
			$parameter['jawaban']	= $isi;
			$parameter['id_indikator'] = $id_indikator;
			$parameter['asign']	= 0;
			$this->db->insert('analisis_parameter', $parameter);
			$in_param = $this->db->insert_id();
		}
		return array($in_param);
	}
}
?>
