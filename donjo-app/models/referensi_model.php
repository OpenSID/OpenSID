<?php class Referensi_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function list_data($tabel){
		$data = $this->db->select('*')->order_by('id')->get($tabel)->result_array();
		return $data;
	}

}

?>