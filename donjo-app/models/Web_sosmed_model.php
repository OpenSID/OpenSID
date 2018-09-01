<?php

class Web_sosmed_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function get_sosmed($id=0){
	
		$sql   = "SELECT * FROM media_sosial WHERE id = ?";
		$query = $this->db->query($sql,$id);
		$data=$query->row_array();
		
		return $data;
	}
	
	function list_sosmed(){
	
		$sql   = "SELECT * FROM media_sosial WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		return $data;
	}
	
	function update($id=0){
		
		$data = $_POST;
		
		$sql="SELECT * FROM media_sosial WHERE id =? ";
		$query = $this->db->query($sql,$id);
		$hasil=$query->result_array();
		
		if($hasil){
			$this->db->where('id',$id);
			$outp = $this->db->update('media_sosial',$data);
		}else{
			$outp = $this->db->insert('media_sosial',$data);
		}
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	

}
?>
