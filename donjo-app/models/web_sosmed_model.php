<?php
/*
 * Berkas default dari halaman web utk publik
 * 
 * Copyright 2013 
 * Rizka Himawan <himawan.rizka@gmail.com>
 * Muhammad Khollilurrohman <adsakle1@gmail.com>
 * Asep Nur Ajiyati <asepnurajiyati@gmail.com>
 *
 * SID adalah software tak berbayar (Opensource) yang boleh digunakan oleh siapa saja selama bukan untuk kepentingan profit atau komersial.
 * Lisensi ini mengizinkan setiap orang untuk menggubah, memperbaiki, dan membuat ciptaan turunan bukan untuk kepentingan komersial
 * selama mereka mencantumkan asal pembuat kepada Anda dan melisensikan ciptaan turunan dengan syarat yang serupa dengan ciptaan asli.
 * Untuk mendapatkan SID RESMI, Anda diharuskan mengirimkan surat permohonan ataupun izin SID terlebih dahulu, 
 * aplikasi ini akan tetap bersifat opensource dan anda tidak dikenai biaya.
 * Bagaimana mendapatkan izin SID, ikuti link dibawah ini:
 * http://lumbungkomunitas.net/bergabung/pendaftaran/daftar-online/
 * Creative Commons Attribution-NonCommercial 3.0 Unported License
 * SID Opensource TIDAK BOLEH digunakan dengan tujuan profit atau segala usaha  yang bertujuan untuk mencari keuntungan. 
 * Pelanggaran HaKI (Hak Kekayaan Intelektual) merupakan tindakan  yang menghancurkan dan menghambat karya bangsa.
 */
?>

<?php

class Web_sosmed_Model extends CI_Model{

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
