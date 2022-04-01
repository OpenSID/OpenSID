<?php

class Aset_model extends CI_Model
{

	protected $table = 'tweb_aset';

	public function list_aset($golongan = NULL)
	{
		$data = $this->db
			->where('golongan', $golongan)
			->get_where($this->table)
			->result_array();
			
		return $data;

	}	
}