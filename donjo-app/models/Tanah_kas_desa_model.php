<?php

class Tanah_kas_desa_model extends CI_Model
{

	protected $table = 'tanah_kas_desa';

	const ORDER_ABLE = [
		2	=> 'nama_pemilik_asal',
		3	=> 'letter_c',
		3	=> 'persil',
		7  => 'tanggal_perolehan',
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
			'nama_pemilik_asal' 	=> nama(strtoupper($this->input->post('pemilik_asal'))),
			'letter_c' 				=> bilangan($this->input->post('letter_c')),
			'persil' 				=> bilangan($this->input->post('persil')),
			'kelas' 				=> alfanumerik($this->input->post('kelas')),			
			'luas' 					=> bilangan($this->input->post('luas')),
			'asli_milik_desa' 		=> bilangan($this->input->post('asli_milik_desa')),
			'pemerintah'	 		=> bilangan($this->input->post('pemerintah')),
			'provinsi'		 		=> bilangan($this->input->post('provinsi')),
			'kabupaten_kota'		=> bilangan($this->input->post('kabupaten_kota')),
			'lain_lain'				=> bilangan($this->input->post('lain_lain')),
			'sawah'					=> bilangan($this->input->post('sawah')),
			'tegal'					=> bilangan($this->input->post('tegal')),
			'kebun'					=> bilangan($this->input->post('kebun')),
			'tambak_kolam'			=> bilangan($this->input->post('tambak_kolam')),
			'tanah_kering_darat'	=> bilangan($this->input->post('tanah_kering_darat')),
			'ada_patok'				=> bilangan($this->input->post('ada_patok')),
			'tidak_ada_patok'		=> bilangan($this->input->post('tidak_ada_patok')),
			'ada_papan_nama'		=> bilangan($this->input->post('ada_papan_nama')),
			'tidak_ada_papan_nama'	=> bilangan($this->input->post('tidak_ada_papan_nama')),					
			'tanggal_perolehan' 	=> $this->input->post('tanggal_perolehan'),						
			'lokasi' 				=> strip_tags($this->input->post('lokasi')),
			'peruntukan' 			=> strip_tags($this->input->post('peruntukan')),
			'mutasi' 				=> strip_tags($this->input->post('mutasi')),
			'keterangan' 			=> strip_tags($this->input->post('keterangan')),
			'created_by' 			=> $this->session->user,
			'updated_by' 			=> $this->session->user,
			'visible' 				=> 1,	
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
			'nama_pemilik_asal' 	=> nama(strtoupper($this->input->post('pemilik_asal'))),
			'letter_c' 				=> bilangan($this->input->post('letter_c')),
			'persil' 				=> bilangan($this->input->post('persil')),
			'kelas' 				=> alfanumerik($this->input->post('kelas')),			
			'luas' 					=> bilangan($this->input->post('luas')),
			'asli_milik_desa' 		=> bilangan($this->input->post('asli_milik_desa')),
			'pemerintah'	 		=> bilangan($this->input->post('pemerintah')),
			'provinsi'		 		=> bilangan($this->input->post('provinsi')),
			'kabupaten_kota'		=> bilangan($this->input->post('kabupaten_kota')),
			'lain_lain'				=> bilangan($this->input->post('lain_lain')),
			'sawah'					=> bilangan($this->input->post('sawah')),
			'tegal'					=> bilangan($this->input->post('tegal')),
			'kebun'					=> bilangan($this->input->post('kebun')),
			'tambak_kolam'			=> bilangan($this->input->post('tambak_kolam')),
			'tanah_kering_darat'	=> bilangan($this->input->post('tanah_kering_darat')),
			'ada_patok'				=> bilangan($this->input->post('ada_patok')),
			'tidak_ada_patok'		=> bilangan($this->input->post('tidak_ada_patok')),
			'ada_papan_nama'		=> bilangan($this->input->post('ada_papan_nama')),
			'tidak_ada_papan_nama'	=> bilangan($this->input->post('tidak_ada_papan_nama')),						
			'tanggal_perolehan' 	=> $this->input->post('tanggal_perolehan'),						
			'lokasi' 				=> alamat($this->input->post('lokasi')),
			'peruntukan' 			=> alamat($this->input->post('peruntukan')),
			'mutasi' 				=> alamat($this->input->post('mutasi')),
			'keterangan' 			=> alamat($this->input->post('keterangan')),
			'updated_at' 			=> date('Y-m-d H:i:s'),			
			'updated_by' 			=> $this->session->user,
			'visible' 				=> 1
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