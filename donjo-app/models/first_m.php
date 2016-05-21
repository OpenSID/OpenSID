<?php

class First_M extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function get_data(){
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
}

