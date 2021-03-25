<?php

class Administrasi_tanah_kas_desa_model extends CI_Model
{

	protected $table = 'administrasi_tanah_kas_desa';
	// protected $table_mutasi = 'mutasi_inventaris_tanah';

	public function __construct()
	{
		parent::__construct();
	}

	public function list_tanah_kas_desa()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->table.'.visible', 1);
		$data = $this->db->get()->result();
		
		return $data;
	}

}