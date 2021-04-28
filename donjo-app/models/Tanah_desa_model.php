<?php

class Tanah_desa_model extends CI_Model
{

	protected $table = 'tanah_desa';

	const ORDER_ABLE = [
		2	=> 'nama_pemilik_asal',
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function get_data(string $search = '')
	{
		$builder = $this->db
					->select('td.id, 
							td.nama_pemilik_asal,		
							p.nama,										
							td.luas,
							td.mutasi, 					
							td.keterangan')
					->from("{$this->table} td")
					->join('tweb_penduduk p', 'td.id_penduduk = p.id', 'left')
					->where('td.visible', 1);

		if (empty($search))
		{
			$search = $builder;
		}
		else
		{
			$search = $builder->group_start()
				->like('td.nama_pemilik_asal', $search)						
				->group_end();
		}
		
		$condition = $search;

		return $condition;
	}

	public function view_tanah_desa_by_id($id)
	{
		$this->db
				->select('td.*, p.nama, p.nik as nik_penduduk')
				->from("{$this->table} td")
				->join('tweb_penduduk p', 'td.id_penduduk = p.id', 'left')
        		->where('td.id', $id);
		$data = $this->db
				->get()
				->row();

		return $data;
	}

	public function add_tanah_desa()
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
			'id_penduduk' 			=> $data['id_penduduk'],		
			'nik' 					=> $data['nik'],		
			'jenis_pemilik' 		=> $data['jenis_pemilik'],
			'nama_pemilik_asal' 	=> $data['nama_pemilik_asal'],
			'luas' 					=> $data['luas'],
			'hak_milik' 			=> $data['hak_milik'],
			'hak_guna_bangunan' 	=> $data['hak_guna_bangunan'],
			'hak_pakai' 			=> $data['hak_pakai'],
			'hak_guna_usaha' 		=> $data['hak_guna_usaha'],
			'hak_pengelolaan' 		=> $data['hak_pengelolaan'],
			'hak_milik_adat' 		=> $data['hak_milik_adat'],
			'hak_verponding' 		=> $data['hak_verponding'],
			'tanah_negara' 			=> $data['tanah_negara'],
			'perumahan' 			=> $data['perumahan'],
			'perdagangan_jasa' 		=> $data['perdagangan_jasa'],
			'perkantoran' 			=> $data['perkantoran'],
			'industri' 				=> $data['industri'],
			'fasilitas_umum' 		=> $data['fasilitas_umum'],
			'sawah' 				=> $data['sawah'],
			'tegalan' 				=> $data['tegalan'],
			'perkebunan' 			=> $data['perkebunan'],
			'peternakan_perikanan'	=> $data['peternakan_perikanan'],
			'hutan_belukar'			=> $data['hutan_belukar'],
			'hutan_lebat_lindung'	=> $data['hutan_lebat_lindung'],
			'tanah_kosong'			=> $data['tanah_kosong'],
			'lain' 					=> $data['lain'],		
			'mutasi' 				=> $data['mutasi'],				
			'keterangan' 			=> $data['keterangan'],
			'created_by' 			=> $this->session->user,
			'updated_by' 			=> $this->session->user,
			'visible' 				=> $data['visible']
		);
				
		$hasil = $this->db->insert($this->table, $result);
		status_sukses($hasil);
	}

	public function delete_tanah_desa($id)
	{
		$hasil = $this->db->update($this->table, array('visible' => 0), array('id' => $id));
		status_sukses($hasil);
	}

	public function update_tanah_desa()
	{
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		$_SESSION['error_msg'] = '';

		$data = $_POST;
		$error_validasi = $this->validasi_data($data, $data['id']);

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
			'id_penduduk' 			=> $data['id_penduduk'],		
			'nik' 					=> $data['nik'],		
			'jenis_pemilik' 		=> $data['jenis_pemilik'],
			'nama_pemilik_asal' 	=> $data['nama_pemilik_asal'],
			'luas' 					=> $data['luas'],
			'hak_milik' 			=> $data['hak_milik'],
			'hak_guna_bangunan' 	=> $data['hak_guna_bangunan'],
			'hak_pakai' 			=> $data['hak_pakai'],
			'hak_guna_usaha' 		=> $data['hak_guna_usaha'],
			'hak_pengelolaan' 		=> $data['hak_pengelolaan'],
			'hak_milik_adat' 		=> $data['hak_milik_adat'],
			'hak_verponding' 		=> $data['hak_verponding'],
			'tanah_negara' 			=> $data['tanah_negara'],
			'perumahan' 			=> $data['perumahan'],
			'perdagangan_jasa' 		=> $data['perdagangan_jasa'],
			'perkantoran' 			=> $data['perkantoran'],
			'industri' 				=> $data['industri'],
			'fasilitas_umum' 		=> $data['fasilitas_umum'],
			'sawah' 				=> $data['sawah'],
			'tegalan' 				=> $data['tegalan'],
			'perkebunan' 			=> $data['perkebunan'],
			'peternakan_perikanan'	=> $data['peternakan_perikanan'],
			'hutan_belukar'			=> $data['hutan_belukar'],
			'hutan_lebat_lindung'	=> $data['hutan_lebat_lindung'],
			'tanah_kosong'			=> $data['tanah_kosong'],
			'lain' 					=> $data['lain'],		
			'mutasi' 				=> $data['mutasi'],				
			'keterangan' 			=> $data['keterangan'],
			'updated_at' 			=> date('Y-m-d H:i:s'),
			'updated_by' 			=> $this->session->user,
			'visible' 				=> $data['visible']
		);

		$id = $data['id'];

		$hasil = $this->db->update($this->table, $result, array('id' => $id));
		status_sukses($hasil);
	}

	private function validasi_data(&$data, $id=0)
	{
		$valid = array();
		$nik_check = NULL;

		if (preg_match("/[^a-zA-Z '\.,\-]/", $data['pemilik_asal']))
		{
			array_push($valid, "Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip");
		}

		if(empty($data['penduduk']))
		{
			if(isset($data['nik']))
			{
				if($error_nik = $this->nik_error($data['nik'], 'NIK'))
				{
					array_push($valid, $error_nik);
				}
				else
				{
					// add
					if($id==0)
					{
						$nik_check = $this->nik_warga_luar_checking($data['nik']);
						if($nik_check)
						{
							array_push($valid, "NIK {$data['nik']} sudah digunakan");
						}
					}else{
					// update
						$nik_old_check = $this->nik_warga_luar_old_checking($data['nik'], $id);
						if(!$nik_old_check)
						{
							$nik_check = $this->nik_warga_luar_checking($data['nik']);
							if($nik_check)
							{
								array_push($valid, "NIK {$data['nik']} sudah digunakan");
							}
						}

					}
					
				}
	
			}
			else
			{
				array_push($valid,"NIK Kosong");
			}
		}
		//  steril data
		$data['id_penduduk'] 			= empty($data['penduduk'])? 0 : $data['penduduk'];
		$data['nik'] 					= empty(bilangan($data['nik']))? 0 : bilangan($data['nik']);
		$data['jenis_pemilik'] 			= bilangan($data['jenis_pemilik']);
		$data['nama_pemilik_asal'] 		= nama(strtoupper($data['pemilik_asal']));
		$data['luas'] 					= bilangan($data['luas']);
		$data['hak_milik'] 				= bilangan($data['hak_milik']);
		$data['hak_guna_bangunan'] 		= bilangan($data['hak_guna_bangunan']);
		$data['hak_pakai'] 				= bilangan($data['hak_pakai']);
		$data['hak_guna_usaha'] 		= bilangan($data['hak_guna_usaha']);
		$data['hak_pengelolaan'] 		= bilangan($data['hak_pengelolaan']);
		$data['hak_milik_adat'] 		= bilangan($data['hak_milik_adat']);
		$data['hak_verponding'] 		= bilangan($data['hak_verponding']);
		$data['tanah_negara'] 			= bilangan($data['tanah_negara']);
		$data['perumahan'] 				= bilangan($data['perumahan']);
		$data['perdagangan_jasa'] 		= bilangan($data['perdagangan_jasa']);
		$data['perkantoran'] 			= bilangan($data['perkantoran']);
		$data['industri'] 				= bilangan($data['industri']);
		$data['fasilitas_umum'] 		= bilangan($data['fasilitas_umum']);
		$data['sawah'] 					= bilangan($data['sawah']);
		$data['tegalan'] 				= bilangan($data['tegalan']);
		$data['perkebunan'] 			= bilangan($data['perkebunan']);
		$data['peternakan_perikanan'] 	= bilangan($data['peternakan_perikanan']);
		$data['hutan_belukar'] 			= bilangan($data['hutan_belukar']);
		$data['hutan_lebat_lindung'] 	= bilangan($data['hutan_lebat_lindung']);
		$data['tanah_kosong'] 			= bilangan($data['tanah_kosong']);
		$data['lain'] 					= bilangan($data['lain_lain']);
		$data['mutasi'] 				= strip_tags($data['mutasi']);
		$data['keterangan'] 			= strip_tags($data['keterangan']);
		$data['visible'] 				= 1;

		if (!empty($valid))
			$_SESSION['validation_error'] = true;

		return $valid;
	}

	private function nik_warga_luar_old_checking($nik, $id)
	{
		$this->db
				->select('td.nik')
				->from("{$this->table} td")					
				->where((['td.visible'=>1,'td.id'=>$id]))
				->limit(1);
		$data = $this->db
				->get()
				->result_array();

		if($nik==$data[0]['nik'])
		{
			return true;
		}else
		{
			return false;
		}
	}
	
	private function nik_warga_luar_checking($nik)
	{
		$this->db
				->select('td.nik')
				->from("{$this->table} td")					
				->where((['td.visible'=>1,'td.nik'=>$nik]))
				->limit(1);
		$data = $this->db
				->get()
				->result_array();
				
		return $data;
	}

	private function nik_error($nilai, $judul)
	{
		if (empty($nilai)) return false;
		if (!ctype_digit($nilai))
			return $judul . " hanya berisi angka";
		if (strlen($nilai) != 16 AND $nilai != '0')
			return $judul .  " panjangnya harus 16 atau bernilai 0";
		return false;
	}

	public function cetak_tanah_desa()
	{
		$this->db
				->select('td.*, p.nama')
				->from("{$this->table} td")	
				->join('tweb_penduduk p', 'td.id_penduduk = p.id', 'left')
				->where('td.visible', 1);
		$data = $this->db
				->get()
				->result_array();
				
		return $data;
	}

	private function get_penduduk_terdaftar()
	{
		$this->db
				->select('p.id')
				->from("{$this->table} td")	
				->join('tweb_penduduk p', 'td.id_penduduk = p.id')
				->where('td.visible', 1);
		$data = $this->db
				->get()
				->result_array();

		for ($i=0; $i < count($data) ; $i++) 
		{ 
			$result[$i] = $data[$i]['id'];
		}
		
		return $result;
	}

	private function with_current_penduduk($filter, $id)
	{
		$this->db
				->select('td.id_penduduk')
				->from("{$this->table} td")					
				->where((['td.visible'=>1,'td.id'=>$id]))
				->limit(1);
		$data = $this->db
				->get()
				->result_array();
		$result = array_diff($filter, [$data[0]['id_penduduk']]);
		return $result;
	}

	public function list_penduduk($id=''){
		$this->db
				->select('p.id, p.nama, p.nik')
				->from("tweb_penduduk p")	
				->order_by('p.nama', 'ASC');
		$data = $this->db
				->get()
				->result_array();

		$filter = $this->get_penduduk_terdaftar();
		if($id!='')
		{	
			$filter = $this->with_current_penduduk($filter,$id);
		}

		if(count($data)>0)
		{
			$j=0;
			for ($i=0; $i < count($data); $i++) 
			{ 
				if(!in_array($data[$i]['id'], $filter))
				{
					$result[$j]['id'] = $data[$i]['id'];
					$result[$j]['nama'] = $data[$i]['nama'];
					$result[$j]['nik'] = $data[$i]['nik'];
					$j++;
				}
			}
		}
				
		return $result;
	}

}