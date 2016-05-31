<?php

class First_Keluarga_M extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function list_raskin($tipex=0){
		
		$dus = "";
		$rw = "";
		$rt = "";
		
		if(isset($_SESSION['dusun']))
			$dus = " AND c.dusun = '$_SESSION[dusun]'";
	
		if(isset($_SESSION['rw']))
			$rw = " AND c.rw = '$_SESSION[rw]'";
	
		if(isset($_SESSION['rt']))
			$rt = " AND c.rt = '$_SESSION[rt]'";
			
	switch($tipex):
		case 1: 
			$sql   = "SELECT s.*,
			(SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk c ON u.nik_kepala=c.id WHERE u.kelas_sosial = s.id) as jumlah FROM klasifikasi_analisis_keluarga s WHERE 1";
			break;
		case 2: 
			$sql   = "SELECT s.*,s.id as jumlah,
			(SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk c ON u.nik_kepala=c.id WHERE u.kelas_sosial = s.id) as raskin FROM ref_raskin s WHERE 1";
			break;
		case 3: 
			$sql   = "SELECT s.*,s.id as jumlah,
			(SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk c ON u.nik_kepala=c.id WHERE u.kelas_sosial = s.id) as jamkesmas FROM ref_jamkesmas s WHERE 1";
			break;
		endswitch;
		
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$hasil = $sql;
			$hasil = $query->result_array();
		}else{
			$hasil = false;
		}
		return $hasil;
	}
}

