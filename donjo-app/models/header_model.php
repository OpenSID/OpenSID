<?php
class Header_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
		
	function get_id_user($user=''){
		$sql   = "SELECT id FROM user WHERE username=?";
		$query = $this->db->query($sql,$user);
		$data = $query->row_array();
		return $data['id'];
	}	
	
	function get_data(){
		$id = $_SESSION['user'];

		//Get Last Login
		//$sql = "SELECT DATE_FORMAT(last_login, '%d-%m-%Y') AS tgl, TIME(last_login) AS waktu FROM user WHERE id=?";
		//$query = $this->db->query($sql, $id);
		//$row = $query->row_array();
		//$outp['last_login'] = nama_bulan($row['tgl']) .', '. $row['waktu'];
		
		//Get Nama User
		$sql   = "SELECT nama,foto FROM user WHERE id=?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		$outp['nama'] = $data['nama'];
		$outp['foto'] = $data['foto'];
		
		
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		$outp['desa'] = $query->row_array();
		
		//Get List Menu
		//$sql   = "SELECT * FROM menu";
		//$query = $this->db->query($sql);
		//$data  = $query->result_array();
		//$outp['menu'] = $data;
		
		//$outp['kont'] = 0;
		
		//if($aktif != 'kontribusi'){
			///$sql   = "SELECT COUNT(id_pertanyaan) as jml FROM pertanyaan_baru WHERE id_user = ?";
			//$query = $this->db->query($sql,$id);
			//$data  = $query->row_array();
			//$outp['kont'] = $data['jml'];
		//}
		return $outp;
	}
	
	function get_config(){
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		$outp['desa'] = $query->row_array();
		return $outp;
	}
}
