<?php

class Tanah_kas_desa_model extends CI_Model
{

	protected $table = 'tanah_kas_desa';

	const ORDER_ABLE = [
		2	=> 'nama_pemilik_asal',
		6  => 'tanggal_perolehan',
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function get_data(string $search = '')
	{
		$builder = $this->db
					->select('tkd.id, 
							tkd.nama_pemilik_asal, 
							tkd.letter_c,							
							tkd.kelas, 							
							tkd.lokasi, 
							tkd.luas,							
							tkd.tanggal_perolehan,
							tkd.mutasi, 
							tkd.keterangan')
					->from("{$this->table} tkd")
					->where('tkd.visible', 1);

		if (empty($search))
		{
			$search = $builder;
		}
		else
		{
			$search = $builder->group_start()
				->like('tkd.nama_pemilik_asal', $search)
				->or_like('tkd.letter_c', $search)				
				->group_end();
		}
		
		$condition = $search;

		return $condition;
	}

	public function view_tanah_kas_desa_by_id($id)
	{
		$this->db
				->select('*')
				->from($this->table)
        		->where($this->table.'.id', $id);
		$data = $this->db
				->get()
				->row();

		return $data;
	}

	public function add_tanah_kas_desa()
	{
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		$_SESSION['error_msg'] = '';

		$data = $_POST;
		$error_validasi = $this->validasi_data($data);

		if (!empty($error_validasi))
		{
			foreach ($error_validasi as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		$result = array(
			'nama_pemilik_asal' 	=> $data['nama_pemilik_asal'],
			'letter_c' 				=> $data['letter_c'],	
			'kelas' 				=> $data['kelas'],			
			'luas' 					=> $data['luas'],
			'asli_milik_desa' 		=> $data['asli_milik_desa'],
			'pemerintah'	 		=> $data['pemerintah'],
			'provinsi'		 		=> $data['provinsi'],
			'kabupaten_kota'		=> $data['kabupaten_kota'],
			'lain_lain'				=> $data['lain_lain'],
			'sawah'					=> $data['sawah'],
			'tegal'					=> $data['tegal'],
			'kebun'					=> $data['kebun'],
			'tambak_kolam'			=> $data['tambak_kolam'],
			'tanah_kering_darat'	=> $data['tanah_kering_darat'],
			'ada_patok'				=> $data['ada_patok'],
			'tidak_ada_patok'		=> $data['tidak_ada_patok'],
			'ada_papan_nama'		=> $data['ada_papan_nama'],
			'tidak_ada_papan_nama'	=> $data['tidak_ada_papan_nama'],					
			'tanggal_perolehan' 	=> $data['tanggal_perolehan'],						
			'lokasi' 				=> $data['lokasi'],
			'peruntukan' 			=> $data['peruntukan'],
			'mutasi' 				=> $data['mutasi'],
			'keterangan' 			=> $data['keterangan'],
			'created_by' 			=> $this->session->user,
			'updated_by' 			=> $this->session->user,
			'visible' 				=> $data['visible'],	
		);		
		
		$hasil = $this->db->insert($this->table, $result);	
		status_sukses($hasil);
	}

	public function delete_tanah_kas_desa($id)
	{
		$hasil = $this->db->update($this->table, array('visible' => 0), array('id' => $id));
		status_sukses($hasil);
	}

	public function update_tanah_kas_desa()
	{
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		$_SESSION['error_msg'] = '';

		$data = $_POST;
		$error_validasi = $this->validasi_data($data,$data['id']);

		if (!empty($error_validasi))
		{
			foreach ($error_validasi as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		$result = array(
			'nama_pemilik_asal' 	=> $data['nama_pemilik_asal'],
			'letter_c' 				=> $data['letter_c'],	
			'kelas' 				=> $data['kelas'],			
			'luas' 					=> $data['luas'],
			'asli_milik_desa' 		=> $data['asli_milik_desa'],
			'pemerintah'	 		=> $data['pemerintah'],
			'provinsi'		 		=> $data['provinsi'],
			'kabupaten_kota'		=> $data['kabupaten_kota'],
			'lain_lain'				=> $data['lain_lain'],
			'sawah'					=> $data['sawah'],
			'tegal'					=> $data['tegal'],
			'kebun'					=> $data['kebun'],
			'tambak_kolam'			=> $data['tambak_kolam'],
			'tanah_kering_darat'	=> $data['tanah_kering_darat'],
			'ada_patok'				=> $data['ada_patok'],
			'tidak_ada_patok'		=> $data['tidak_ada_patok'],
			'ada_papan_nama'		=> $data['ada_papan_nama'],
			'tidak_ada_papan_nama'	=> $data['tidak_ada_papan_nama'],					
			'tanggal_perolehan' 	=> $data['tanggal_perolehan'],						
			'lokasi' 				=> $data['lokasi'],
			'peruntukan' 			=> $data['peruntukan'],
			'mutasi' 				=> $data['mutasi'],
			'keterangan' 			=> $data['keterangan'],
			'updated_at' 			=> date('Y-m-d H:i:s'),
			'updated_by' 			=> $this->session->user,
			'visible' 				=> $data['visible'],	
		);				

		$id =$data['id'];	
		$hasil = $this->db->update($this->table, $result, array('id' => $id));
		status_sukses($hasil);
	}

	private function validasi_data(&$data, $id=0){
		$valid = array();

		// add
		if($id==0)
		{
			$check_letterc_persil = $this->check_letterc_persil($data['letter_c_persil']);
			if(count($check_letterc_persil)>0)
			{
				array_push($valid, "Letter C / Persil {$data['letter_c_persil']} sudah digunakan");
			}
		}else{
		// update
			$check_old_letterc_persil = $this->check_old_letterc_persil($data['letter_c_persil'],$id);
			if(!$check_old_letterc_persil)
			{
				$check_letterc_persil = $this->check_letterc_persil($data['letter_c_persil']);
				if(count($check_letterc_persil)>0)
				{
					array_push($valid, "Letter C / Persil {$data['letter_c_persil']} sudah digunakan");
				}
			}
		}

		if (preg_match("/[^a-zA-Z '\.,\-]/", $data['pemilik_asal']))
		{
			array_push($valid, "Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip");
		}

		$data['nama_pemilik_asal'] 		= nama(strtoupper($data['pemilik_asal']));
		$data['letter_c']				= bilangan($data['letter_c_persil']);
		$data['kelas']					= strip_tags($data['kelas']);
		$data['luas']					= bilangan($data['luas']);
		$data['asli_milik_desa']		= bilangan($data['asli_milik_desa']);
		$data['pemerintah']				= bilangan($data['pemerintah']);
		$data['provinsi']				= bilangan($data['provinsi']);
		$data['kabupaten_kota']			= bilangan($data['kabupaten_kota']);
		$data['lain_lain']				= bilangan($data['lain_lain']);
		$data['sawah']					= bilangan($data['sawah']);
		$data['tegal']					= bilangan($data['tegal']);
		$data['kebun']					= bilangan($data['kebun']);
		$data['tambak_kolam']			= bilangan($data['tambak_kolam']);
		$data['tanah_kering_darat']		= bilangan($data['tanah_kering_darat']);
		$data['ada_patok']				= bilangan($data['ada_patok']);
		$data['tidak_ada_patok']		= bilangan($data['tidak_ada_patok']);
		$data['ada_papan_nama']			= bilangan($data['ada_papan_nama']);
		$data['tidak_ada_papan_nama']	= bilangan($data['tidak_ada_papan_nama']);
		$data['tanggal_perolehan']		= $data['tanggal_perolehan'];
		$data['lokasi']					= strip_tags($data['lokasi']);
		$data['peruntukan']				= strip_tags($data['peruntukan']);
		$data['mutasi']					= strip_tags($data['mutasi']);
		$data['keterangan']				= strip_tags($data['keterangan']);
		$data['created_by']				= $this->session->user;
		$data['updated_by']				= $this->session->user;
		$data['visible']				= 1;

		if (!empty($valid))
			$_SESSION['validation_error'] = true;

		return $valid;
	}

	private function check_old_letterc_persil($letterC_persil, $id)
	{	
		$this->db
				->select('tkd.letter_c')
				->from("{$this->table} tkd")					
				->where((['tkd.visible'=>1,'tkd.id'=>$id]))
				->limit(1);
		$data = $this->db
				->get()
				->result_array();
		if($letterC_persil==$data[0]['letter_c'])
		{
			return true;
		}else
		{
			return false;
		}
	}

	private function check_letterc_persil($letterC_persil)
	{
		$this->db
				->select('tkd.letter_c')
				->from("{$this->table} tkd")					
				->where((['tkd.visible'=>1,'tkd.letter_c'=>$letterC_persil]))
				->limit(1);
		$data = $this->db
				->get()
				->result_array();

		return $data;
	}

	public function cetak_tanah_kas_desa()
	{
		$this->db
				->select('*')
				->from($this->table)	
				->where($this->table.'.visible', 1);		
		$data = $this->db
				->get()
				->result_array();
				
		return $data;
	}

	public function list_letter_c()
	{
		$this->db
				->select('c.id, c.nomor, c.nama_kepemilikan')
				->from("cdesa c");
		$data = $this->db
				->get()
				->result_array();
				
		return $data;
	}

}