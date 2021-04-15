<?php

class Tanah_desa_model extends CI_Model
{

	protected $table = 'tanah_desa';

	const ORDER_ABLE = [
		2	=> 'nama_pemilik_asal',
		3	=> 'letter_c',
		3	=> 'nomor_sertif',
		7  	=> 'tanggal_sertif',
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
							td.letter_c,
							td.nomor_sertif, 
							td.hak_tanah, 
							td.penggunaan_tanah, 
							td.luas, 
							td.tanggal_sertif, 
							td.keterangan')
					->from("{$this->table} td")
					->where('td.visible', 1);

		if (empty($search))
		{
			$search = $builder;
		}
		else
		{
			$search = $builder->group_start()
				->like('td.nama_pemilik_asal', $search)
				->or_like('td.letter_c', $search)
				->or_like('td.nomor_sertif', $search)
				->group_end();
		}
		
		$condition = $search;

		return $condition;
	}

	public function view_tanah_desa_by_id($id)
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

	public function add_tanah_desa()
	{
		$data = array(
			'nama_pemilik_asal' => $this->input->post('pemilik_asal'),
			'letter_c' 			=> $this->input->post('letter_c'),
			'persil' 			=> $this->input->post('persil'),
			'nomor_sertif' 		=> $this->input->post('no_sertif'),			
			'tanggal_sertif' 	=> $this->input->post('tanggal_sertif'),
			'hak_tanah' 		=> $this->input->post('hak_tanah'),
			'penggunaan_tanah' 	=> $this->input->post('penggunaan_tanah'),
			'luas' 				=> $this->input->post('luas'),
			'lain' 				=> $this->input->post('lain'),						
			'keterangan' 		=> $this->input->post('keterangan'),
			'created_by' 		=> $this->session->user,
			'updated_by' 		=> $this->session->user,
			'visible' 			=> 1
		);

		$hasil = $this->db->insert($this->table, array_filter($data));
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
			'nama_pemilik_asal' => $this->input->post('pemilik_asal'),
			'letter_c' 			=> $this->input->post('letter_c'),
			'persil' 			=> $this->input->post('persil'),
			'nomor_sertif' 		=> $this->input->post('no_sertif'),			
			'tanggal_sertif' 	=> $this->input->post('tanggal_sertif'),
			'hak_tanah' 		=> $this->input->post('hak_tanah'),
			'penggunaan_tanah' 	=> $this->input->post('penggunaan_tanah'),
			'luas' 				=> $this->input->post('luas'),
			'lain' 				=> $this->input->post('lain'),						
			'keterangan' 		=> $this->input->post('keterangan'),
			'updated_at' 		=> date('Y-m-d H:i:s'),			
			'updated_by' 		=> $this->session->user,
			'visible' => 1
		);
		$id = $this->input->post('id');

		$hasil = $this->db->update($this->table, $data, array('id' => $id));
		status_sukses($hasil);
	}

	public function cetak_tanah_desa()
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

}