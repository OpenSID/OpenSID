<?php

class First_slide_m extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function slide_show(){
		$sql   = "SELECT * FROM gambar_slide WHERE enabled=?";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		return $data;
	}
}

