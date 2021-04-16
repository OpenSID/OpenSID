<?php

class Tanah_kas_desa_model extends CI_Model
{

	protected $table = 'tanah_kas_desa';

	const ORDER_ABLE = [
		2	=> 'nama_pemilik_asal',
		3	=> 'letter_c',
		3	=> 'persil',
		10  => 'tanggal_perolehan',
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
							tkd.persil, 
							tkd.kelas, 
							tkd.perolehan_tkd,
							tkd.jenis_tkd,
							tkd.lokasi, 
							tkd.luas,
							tkd.patok,
							tkd.papan_nama, 
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
				->or_like('tkd.persil', $search)
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
		$data = array(
			'nama_pemilik_asal' => $this->input->post('pemilik_asal'),
			'letter_c' 			=> $this->input->post('letter_c'),
			'persil' 			=> $this->input->post('persil'),
			'kelas' 			=> $this->input->post('kelas'),			
			'luas' 				=> $this->input->post('luas'),
			'perolehan_tkd' 	=> $this->input->post('perolehan_tkd'),
			'jenis_tkd' 		=> $this->input->post('jenis_tkd'),
			'patok' 			=> $this->input->post('patok_batas'),						
			'papan_nama' 		=> $this->input->post('papan_nama'),						
			'tanggal_perolehan' => $this->input->post('tanggal_perolehan'),						
			'lokasi' 			=> $this->input->post('lokasi'),
			'peruntukan' 		=> $this->input->post('peruntukan'),
			'mutasi' 			=> $this->input->post('mutasi'),
			'keterangan' 		=> $this->input->post('keterangan'),
			'created_by' 		=> $this->session->user,
			'updated_by' 		=> $this->session->user,
			'visible' 			=> 1,	
		);		
		
		$hasil = $this->db->insert($this->table, $data);	
		status_sukses($hasil);
	}

	public function delete_tanah_kas_desa($id)
	{
		$hasil = $this->db->update($this->table, array('visible' => 0), array('id' => $id));
		status_sukses($hasil);
	}

	public function update_tanah_kas_desa()
	{
		$data = array(
			'nama_pemilik_asal' => $this->input->post('pemilik_asal'),
			'letter_c' 			=> $this->input->post('letter_c'),
			'persil' 			=> $this->input->post('persil'),
			'kelas' 			=> $this->input->post('kelas'),			
			'luas' 				=> $this->input->post('luas'),
			'perolehan_tkd' 	=> $this->input->post('perolehan_tkd'),
			'jenis_tkd' 		=> $this->input->post('jenis_tkd'),
			'patok' 			=> $this->input->post('patok_batas'),						
			'papan_nama' 		=> $this->input->post('papan_nama'),						
			'tanggal_perolehan' => $this->input->post('tanggal_perolehan'),						
			'lokasi' 			=> $this->input->post('lokasi'),
			'peruntukan' 		=> $this->input->post('peruntukan'),
			'mutasi' 			=> $this->input->post('mutasi'),
			'keterangan' 		=> $this->input->post('keterangan'),
			'updated_at' 		=> date('Y-m-d H:i:s'),			
			'updated_by' 		=> $this->session->user,
			'visible' 			=> 1
		);
		$id = $this->input->post('id');

		$hasil = $this->db->update($this->table, $data, array('id' => $id));
		status_sukses($hasil);
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