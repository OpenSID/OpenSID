<?php
class Analisis_import_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Spreadsheet_Excel_Reader');
	}

	public function import_excel($file='', $kode='00000', $jenis=2)
	{
		if (empty($file)) $file = $_FILES['userfile']['tmp_name'];
		$data = new Spreadsheet_Excel_Reader($file);
		$sheet=0;

		$master['nama']	= $data->val(1, 2, $sheet);
		$master['subjek_tipe'] = $data->val(2, 2, $sheet);
		$master['lock']	= $data->val(3, 2, $sheet);
		$master['pembagi'] = $data->val(4, 2, $sheet);
		$master['deskripsi'] = $data->val(5, 2, $sheet);
		$master['kode_analisis'] = $kode;
		$master['jenis'] = $jenis;

		$outp = $this->db->insert('analisis_master',$master);
		$id_master = $this->db->insert_id();

		$periode['id_master']	= $id_master;
		$periode['nama'] = $data->val(6, 2, $sheet);
		$periode['tahun_pelaksanaan']	= $data->val(7, 2, $sheet);
		$periode['keterangan'] = $data->val(5, 2, $sheet);
		$periode['aktif']	= 1;
		$this->db->insert('analisis_periode', $periode);

		$sheet = 1;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		for ($i=2; $i<=$baris; $i++)
		{
			$sql = "SELECT * FROM analisis_kategori_indikator WHERE kategori=? AND id_master=?";
			$query = $this->db->query($sql, array($data->val($i, 3, $sheet), $id_master));
			$cek = $query->row_array();

			if (!$cek)
			{
				$kategori['id_master'] = $id_master;
				$kategori['kategori']	= $data->val($i, 3, $sheet);
				$this->db->insert('analisis_kategori_indikator', $kategori);
			}
		}

		for ($i=2; $i<=$baris; $i++)
		{
			$indikator['id_master']	= $id_master;
			$indikator['nomor']	= $data->val($i, 1, $sheet);
			$indikator['pertanyaan'] = $data->val($i, 2, $sheet);

			$sql = "SELECT * FROM analisis_kategori_indikator WHERE kategori=? AND id_master=?";
			$query = $this->db->query($sql, array($data->val($i, 3, $sheet), $id_master));
			$kategori = $query->row_array();

			$indikator['id_kategori']	= $kategori['id'];
			$indikator['id_tipe']	= $data->val($i, 4, $sheet);
			$indikator['bobot']	= $data->val($i, 5, $sheet) ?: 0;
			$indikator['act_analisis'] = $data->val($i, 6, $sheet) ?: 2;

			$this->db->insert('analisis_indikator', $indikator);
		}

		$sheet = 2;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		for ($i=2; $i<=$baris; $i++)
		{
			$kode	= explode(".", $data->val($i, 3, $sheet));

			$parameter['kode_jawaban'] = $data->val($i, 2, $sheet);
			$parameter['jawaban']	= $data->val($i, 3, $sheet);

			$sql = "SELECT id FROM analisis_indikator WHERE nomor=? AND id_master=?";
			$query = $this->db->query($sql, array($data->val($i, 1, $sheet), $id_master));
			$indikator = $query->row_array();

			$parameter['id_indikator'] = $indikator['id'];
			$parameter['nilai']	= $data->val($i, 4, $sheet) ?: 0;
			$parameter['asign']	= 1;

			$this->db->insert('analisis_parameter',$parameter);
		}

		$sheet = 3;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		for ($i=2; $i<=$baris; $i++)
		{
			$klasifikasi['id_master']	= $id_master;
			$klasifikasi['nama'] = $data->val($i, 1, $sheet);
			$klasifikasi['minval'] = $data->val($i, 2, $sheet);
			$klasifikasi['maxval'] = $data->val($i, 3, $sheet);

			$this->db->insert('analisis_klasifikasi', $klasifikasi);
		}

		status_sukses($outp); //Tampilkan Pesan

		return $id_master;
	}

	public function import_gform(){
		// Pengaturan Library Upload
		$this->load->library('upload');

		$config['upload_path']		= LOKASI_DOKUMEN;
		$config['allowed_types']	= 'xls|xlsx|xlsm|csv';
		$config['file_name']		= namafile('Import Response Google Form');

		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload('userfile'))
		{
			$this->session->error_msg = $this->upload->display_errors();
			$this->session->success = -1;
			return;
		}

		$upload = $this->upload->data();
		$file = LOKASI_DOKUMEN . $upload['file_name'];

		// Open File CSV
		$handle = fopen($file, "r");
		$list_data = array();
		$list_pertanyaan = array();
		$count_row = 1;

		while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row vales
		{
			if($count_row == 1)
			{
				foreach ($row as $value)
				{
					$temp = [
						'pertanyaan' 	=> $value,
						'unique_value' 	=> array()
					];
					array_push($list_pertanyaan, $temp);
				}
			}
			else
			{
				foreach ($row as $key => $value)
				{
					if(! in_array($value, $list_pertanyaan[$key]['unique_value']))
					{
						array_push($list_pertanyaan[$key]['unique_value'], $value);
					}
				}
				array_push($list_data, $row);
			}
				
			$count_row += 1;
		}

		$this->session->data_import = array(
			'pertanyaan' 	=> $list_pertanyaan,
			'jawaban'		=> $list_data
		);

		$this->session->success = 5;
		return '0';
	}

	public function save_import_gform(){
		// SIMPAN ANALISIS MASTER
		$data_analisis_master = [
			'nama' 			=> "Response Google Form " . date('dmY_His'), // TODO
			'subjek_tipe' 	=> 1, // TODO
			'id_kelompok' 	=> 0,
			'lock' 			=> 1,
			'format_impor' 	=> 0,
			'pembagi' 		=> 1,
			'id_child' 		=> 0,
			'deskripsi' 	=> ""
		];

		$outp = $this->db->insert('analisis_master', $data_analisis_master);
		$id_master = $this->db->insert_id();

		// SIMPAN KATEGORI ANALISIS
		$list_kategori = $this->input->post('kategori');
		$temp_unique_kategori = array();
		$list_unique_kategori = array();

		// Get Unique Value dari Kategori
		foreach ($list_kategori as $key => $val)
		{
			if($this->input->post('is_selected')[$key] == 'true')
			{
				if(! in_array($val, $temp_unique_kategori))
				{
					array_push($temp_unique_kategori, $val);
				}
			}
		}
		
		// Simpan Unique Value dari Kategori
		foreach ($temp_unique_kategori as $key => $val)
		{
			$data_kategori = [
				'id_master'		=> $id_master,
				'kategori' 		=> $val,
				'kategori_kode'	=> ""
			];

			$outp = $this->db->insert('analisis_kategori_indikator', $data_kategori);
			$id_kategori = $this->db->insert_id();

			$list_unique_kategori[$id_kategori] = $val;
		}

		// SIMPAN PERTANYAAN/INDIKATOR ANALISIS
		$count_indikator = 1;
		foreach ($this->input->post('pertanyaan') as $key => $val)
		{
			if($this->input->post('is_selected')[$key] == 'true')
			{
				$data_indikator = [
					'id_master'		=> $id_master,
					'nomor'			=> $count_indikator,
					'pertanyaan' 	=> $val,
					'id_tipe' 		=> $this->input->post('tipe')[$key],
					'bobot' 		=> $this->input->post('bobot')[$key],
					'act_analisis' 	=> 0,
					'id_kategori' 	=> array_search($this->input->post('kategori')[$key], $list_unique_kategori),
					'is_publik' 	=> 0,
					'is_teks' 		=> 0
				];

				if($data_indikator['id_tipe'] != 1)
				{
					$data_indikator['act_analisis']	= 2;
					$data_indikator['bobot'] 		= 0;
				}
	
				$outp = $this->db->insert('analisis_indikator', $data_indikator);
				$id_indikator = $this->db->insert_id();

				// Simpan Parameter untuk Tipe Pilihan Tunggal
				if($data_indikator['id_tipe'] == 1)
				{
					foreach ($this->input->post('unique-param-value-' . $key) as $param_key => $param_val)
					{
						$data_parameter = [
							'id_indikator'	=> $id_indikator,
							'jawaban'		=> $this->input->post('unique-param-value-' . $key)[$param_key],
							'nilai' 		=> $this->input->post('unique-param-nilai-' . $key)[$param_key],
							'kode_jawaban' 	=> ($param_key+1),
							'asign' 		=> 0
						];

						$outp = $this->db->insert('analisis_parameter', $data_parameter);
					}
				}
				
				$count_indikator += 1;
			}
		}
		
		status_sukses($outp);
	}
}
