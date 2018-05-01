<?php class Referensi_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function list_data($tabel){
		$data = $this->db->select('*')->order_by('id')->get($tabel)->result_array();
		return $data;
	}

	function list_kode_array($s_array){
		$list = array_flip(unserialize($s_array));
		return $list;
	}

	function list_wajib_ktp(){
		$wajib_ktp = array_flip(unserialize(WAJIB_KTP));
		return $wajib_ktp;
	}

	function list_ktp_el(){
		$ktp_el = array_flip(unserialize(KTP_EL));
		return $ktp_el;
	}

	function list_status_rekam(){
		$status_rekam = array_flip(unserialize(STATUS_REKAM));
		return $status_rekam;
	}
}

?>
