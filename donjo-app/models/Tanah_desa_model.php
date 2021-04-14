<?php

class Tanah_desa_model extends CI_Model
{

	protected $table = 'tanah_desa';

	public function __construct()
	{
		parent::__construct();
	}

	public function list_tanah_desa()
	{
		$this->db
				->select('*')
				->from($this->table)
				->where($this->table.'.visible', 1);
		$data = $this->db
				->get()
				->result();
		
		return $data;
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
			'letter_c' => $this->input->post('letter_c'),
			'persil' => $this->input->post('persil'),
			'nomor_sertif' => $this->input->post('no_sertif'),			
			'tanggal_sertif' => $this->input->post('tanggal_sertif'),
			'hak_tanah' => $this->input->post('hak_tanah'),
			'penggunaan_tanah' => $this->input->post('penggunaan_tanah'),
			'luas' => $this->input->post('luas'),
			'lain' => $this->input->post('lain'),						
			'keterangan' => $this->input->post('keterangan'),
			'created_by' => $this->session->user,
			'updated_by' => $this->session->user,
			'visible' => 1
		);

		$this->db->insert($this->table, array_filter($data));
		$id = $this->db->insert_id();
		$inserted = $this->db->get_where($this->table, array('id' => $id))->row();
		return $inserted;
	}

	public function delete_tanah_desa($id)
	{
		$hasil = $this->db->update($this->table, array('visible' => 0), array('id' => $id));
		return $hasil;
	}

	public function update_tanah_desa()
	{
		$data = array(
			'nama_pemilik_asal' => $this->input->post('pemilik_asal'),
			'letter_c' => $this->input->post('letter_c'),
			'persil' => $this->input->post('persil'),
			'nomor_sertif' => $this->input->post('no_sertif'),			
			'tanggal_sertif' => $this->input->post('tanggal_sertif'),
			'hak_tanah' => $this->input->post('hak_tanah'),
			'penggunaan_tanah' => $this->input->post('penggunaan_tanah'),
			'luas' => $this->input->post('luas'),
			'lain' => $this->input->post('lain'),						
			'keterangan' => $this->input->post('keterangan'),
			'updated_at' => date('Y-m-d H:i:s'),			
			'updated_by' => $this->session->user,
			'visible' => 1
		);
		$id = $this->input->post('id');

		$hasil = $this->db->update($this->table, $data, array('id' => $id));
		return $hasil;
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