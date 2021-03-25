<?php

class Administrasi_tanah_desa_model extends CI_Model
{

	protected $table = 'administrasi_tanah_desa';
	protected $table_kas = 'administrasi_tanah_kas_desa';

	public function __construct()
	{
		parent::__construct();
	}

	public function list_tanah_desa()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->table.'.visible', 1);
		$data = $this->db->get()->result();
		
		return $data;
	}

	public function view_tanah_desa_by_id($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
        $this->db->where($this->table.'.id', $id);
		$data = $this->db->get()->row();
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

	public function list_tanah_kas_desa()
	{
		$this->db->select('*');
		$this->db->from($this->table_kas);
		$this->db->where($this->table_kas.'.visible', 1);
		$data = $this->db->get()->result();
		
		return $data;
	}

	public function view_tanah_kas_desa_by_id($id)
	{
		$this->db->select('*');
		$this->db->from($this->table_kas);
        $this->db->where($this->table_kas.'.id', $id);
		$data = $this->db->get()->row();
		return $data;
	}

	public function add_tanah_kas_desa()
	{
		$data = array(
			'nama_pemilik_asal' => $this->input->post('pemilik_asal'),
			'letter_c' => $this->input->post('letter_c'),
			'persil' => $this->input->post('persil'),
			'kelas' => $this->input->post('kelas'),			
			'luas' => $this->input->post('luas'),
			'perolehan_tkd' => $this->input->post('perolehan_tkd'),
			'jenis_tkd' => $this->input->post('jenis_tkd'),
			'patok' => $this->input->post('patok_batas'),						
			'papan_nama' => $this->input->post('papan_nama'),						
			'tanggal_perolehan' => $this->input->post('tanggal_perolehan'),						
			'lokasi' => $this->input->post('lokasi'),
			'peruntukan' => $this->input->post('peruntukan'),
			'keterangan' => $this->input->post('keterangan'),
			'created_by' => $this->session->user,
			'updated_by' => $this->session->user,
			'status' => 0,
			'visible' => 1,	
		);		

		
		$this->db->insert($this->table_kas, $data);	
		$id = $this->db->insert_id();
		$inserted = $this->db->get_where($this->table_kas, array('id' => $id))->row();
		return $inserted;
	}

	public function delete_tanah_kas_desa($id)
	{
		$hasil = $this->db->update($this->table_kas, array('visible' => 0), array('id' => $id));
		return $hasil;
	}

	public function update_tanah_kas_desa()
	{
		$data = array(
			'nama_pemilik_asal' => $this->input->post('pemilik_asal'),
			'letter_c' => $this->input->post('letter_c'),
			'persil' => $this->input->post('persil'),
			'kelas' => $this->input->post('kelas'),			
			'luas' => $this->input->post('luas'),
			'perolehan_tkd' => $this->input->post('perolehan_tkd'),
			'jenis_tkd' => $this->input->post('jenis_tkd'),
			'patok' => $this->input->post('patok_batas'),						
			'papan_nama' => $this->input->post('papan_nama'),						
			'tanggal_perolehan' => $this->input->post('tanggal_perolehan'),						
			'lokasi' => $this->input->post('lokasi'),
			'peruntukan' => $this->input->post('peruntukan'),
			'keterangan' => $this->input->post('keterangan'),
			'updated_at' => date('Y-m-d H:i:s'),			
			'updated_by' => $this->session->user,
			'visible' => 1
		);
		$id = $this->input->post('id');

		$hasil = $this->db->update($this->table_kas, $data, array('id' => $id));
		return $hasil;
	}

	public function cetak_tanah_desa()
	{
		$this->db->select('*');
		$this->db->from($this->table);	
		$this->db->where($this->table.'.visible', 1);		
		$data = $this->db->get()->result_array();
		return $data;
	}

	public function cetak_tanah_kas_desa()
	{
		$this->db->select('*');
		$this->db->from($this->table_kas);	
		$this->db->where($this->table_kas.'.visible', 1);		
		$data = $this->db->get()->result_array();
		return $data;
	}


}