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
				->select('td.*, p.nama, p.nik')
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
		$data = array(
			'id_penduduk' 			=> empty($this->input->post('penduduk'))? 0 : $this->input->post('penduduk'),		
			'jenis_pemilik' 		=> $this->input->post('jenis_pemilik'),
			'nama_pemilik_asal' 	=> nama(strtoupper($this->input->post('pemilik_asal'))),
			'luas' 					=> bilangan($this->input->post('luas')),
			'hak_milik' 			=> bilangan($this->input->post('hak_milik')),
			'hak_guna_bangunan' 	=> bilangan($this->input->post('hak_guna_bangunan')),
			'hak_pakai' 			=> bilangan($this->input->post('hak_pakai')),
			'hak_guna_usaha' 		=> bilangan($this->input->post('hak_guna_usaha')),
			'hak_pengelolaan' 		=> bilangan($this->input->post('hak_pengelolaan')),
			'hak_milik_adat' 		=> bilangan($this->input->post('hak_milik_adat')),
			'hak_verponding' 		=> bilangan($this->input->post('hak_verponding')),
			'tanah_negara' 			=> bilangan($this->input->post('tanah_negara')),
			'perumahan' 			=> bilangan($this->input->post('perumahan')),
			'perdagangan_jasa' 		=> bilangan($this->input->post('perdagangan_jasa')),
			'perkantoran' 			=> bilangan($this->input->post('perkantoran')),
			'industri' 				=> bilangan($this->input->post('industri')),
			'fasilitas_umum' 		=> bilangan($this->input->post('fasilitas_umum')),
			'sawah' 				=> bilangan($this->input->post('sawah')),
			'tegalan' 				=> bilangan($this->input->post('tegalan')),
			'perkebunan' 			=> bilangan($this->input->post('perkebunan')),
			'peternakan_perikanan'	=> bilangan($this->input->post('peternakan_perikanan')),
			'hutan_belukar'			=> bilangan($this->input->post('hutan_belukar')),
			'hutan_lebat_lindung'	=> bilangan($this->input->post('hutan_lebat_lindung')),
			'tanah_kosong'			=> bilangan($this->input->post('tanah_kosong')),
			'lain' 					=> bilangan($this->input->post('lain_lain')),		
			'mutasi' 				=> strip_tags($this->input->post('mutasi')),				
			'keterangan' 			=> strip_tags($this->input->post('keterangan')),
			'created_by' 			=> $this->session->user,
			'updated_by' 			=> $this->session->user,
			'visible' 				=> 1
		);
	
		$hasil = $this->db->insert($this->table, $data);
		status_sukses($hasil);
	}

	public function delete_tanah_desa($id)
	{
		$hasil = $this->db->update($this->table, array('visible' => 0), array('id' => $id));
		status_sukses($hasil);
	}

	public function update_tanah_desa()
	{
		$data = array(
			'id_penduduk' 			=> empty($this->input->post('penduduk'))? 0 : $this->input->post('penduduk'),		
			'jenis_pemilik' 		=> $this->input->post('jenis_pemilik'),
			'nama_pemilik_asal' 	=> nama(strtoupper($this->input->post('pemilik_asal'))),
			'luas' 					=> bilangan($this->input->post('luas')),
			'hak_milik' 			=> bilangan($this->input->post('hak_milik')),
			'hak_guna_bangunan' 	=> bilangan($this->input->post('hak_guna_bangunan')),
			'hak_pakai' 			=> bilangan($this->input->post('hak_pakai')),
			'hak_guna_usaha' 		=> bilangan($this->input->post('hak_guna_usaha')),
			'hak_pengelolaan' 		=> bilangan($this->input->post('hak_pengelolaan')),
			'hak_milik_adat' 		=> bilangan($this->input->post('hak_milik_adat')),
			'hak_verponding' 		=> bilangan($this->input->post('hak_verponding')),
			'tanah_negara' 			=> bilangan($this->input->post('tanah_negara')),
			'perumahan' 			=> bilangan($this->input->post('perumahan')),
			'perdagangan_jasa' 		=> bilangan($this->input->post('perdagangan_jasa')),
			'perkantoran' 			=> bilangan($this->input->post('perkantoran')),
			'industri' 				=> bilangan($this->input->post('industri')),
			'fasilitas_umum' 		=> bilangan($this->input->post('fasilitas_umum')),
			'sawah' 				=> bilangan($this->input->post('sawah')),
			'tegalan' 				=> bilangan($this->input->post('tegalan')),
			'perkebunan' 			=> bilangan($this->input->post('perkebunan')),
			'peternakan_perikanan'	=> bilangan($this->input->post('peternakan_perikanan')),
			'hutan_belukar'			=> bilangan($this->input->post('hutan_belukar')),
			'hutan_lebat_lindung'	=> bilangan($this->input->post('hutan_lebat_lindung')),
			'tanah_kosong'			=> bilangan($this->input->post('tanah_kosong')),
			'lain' 					=> bilangan($this->input->post('lain_lain')),		
			'mutasi' 				=> strip_tags($this->input->post('mutasi')),				
			'keterangan' 			=> strip_tags($this->input->post('keterangan')),
			'updated_at' 			=> date('Y-m-d H:i:s'),			
			'updated_by' 			=> $this->session->user,
			'visible'				=> 1
		);
		$id = $this->input->post('id');

		$hasil = $this->db->update($this->table, $data, array('id' => $id));
		status_sukses($hasil);
	}

	public function cetak_tanah_desa()
	{
		$this->db
				->select('td.*, p.nama')
				->from("{$this->table} td")	
				->join('tweb_penduduk p', 'td.id_penduduk = p.id', 'left')
				->where('td.visible', 1)
				->order_by('nama_pemilik_asal', 'ASC');
		$data = $this->db
				->get()
				->result_array();
				
		return $data;
	}

	public function list_penduduk(){
		$this->db
				->select('p.id, p.nama, p.nik')
				->from("tweb_penduduk p")	
				->order_by('p.nama', 'ASC');
		$data = $this->db
				->get()
				->result_array();
				
		return $data;
	}

}