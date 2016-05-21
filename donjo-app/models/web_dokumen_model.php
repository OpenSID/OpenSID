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

class Web_Dokumen_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function autocomplete(){
		$sql   = "SELECT satuan FROM dokumen
					UNION SELECT nama FROM dokumen";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['satuan']. "'";
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}
	
	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (satuan LIKE '$kw' OR nama LIKE '$kw')";
			return $search_sql;
			}
		}
	
	function filter_sql(){		
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND enabled = $kf";
		return $filter_sql;
		}
	}
	
	function paging($p=1,$o=0){
	
		$sql      = "SELECT COUNT(id) AS id FROM dokumen WHERE 1";
		$sql     .= $this->search_sql();     
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];
		
		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		
		return $this->paging;
	}
	
	function list_data($o=0,$offset=0,$limit=500){
	
		switch($o){
			case 1: $order_sql = ' ORDER BY nama'; break;
			case 2: $order_sql = ' ORDER BY nama DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			case 5: $order_sql = ' ORDER BY tgl_upload'; break;
			case 6: $order_sql = ' ORDER BY tgl_upload DESC'; break;
			default:$order_sql = ' ORDER BY id';
		}
	
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		$sql   = "SELECT * FROM dokumen WHERE 1 ";
			
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			
			if($data[$i]['enabled']==1)
				$data[$i]['aktif']="Yes";
			else
				$data[$i]['aktif']="No";
			
			$i++;
			$j++;
		}
		return $data;
	}
	
	function insert(){
		  $lokasi_file = $_FILES['satuan']['tmp_name'];
		  $tipe_file   = $_FILES['satuan']['type'];
		  $nama_file   = $_FILES['satuan']['name'];
		  if (!empty($lokasi_file)){
			if ($tipe_file == "application/x-download" 
					OR $tipe_file == "application/pdf" 
					OR $tipe_file == "application/zip"
 					OR $tipe_file == "application/ppt" 
					OR $tipe_file == "application/pptx"
 					OR $tipe_file == "application/rar"					
					OR $tipe_file == "application/excel" 
					OR $tipe_file == "application/msword" 
					OR $tipe_file == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" 
					OR $tipe_file == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" 
					OR $tipe_file == "text/rtf" 
					OR $tipe_file == "application/powerpoint" 
					OR $tipe_file == "application/vnd.ms-powerpoint" 
					OR $tipe_file == "application/vnd.ms-excel" 
					OR $tipe_file == "application/msexcel"
					OR $tipe_file == "application/x-zip"

){
				UploadDocument(underscore($nama_file));
				$data = $_POST;
				$data['satuan'] = underscore($nama_file);
				$outp = $this->db->insert('dokumen',$data);
				if($outp) $_SESSION['success']=1;
			} else {
				$_SESSION['success']=-1;
			}
		  }
	}
	
	function update($id=0){
		  $data = $_POST;
		  $lokasi_file = $_FILES['satuan']['tmp_name'];
		  $tipe_file   = $_FILES['satuan']['type'];
		  $nama_file   = $_FILES['satuan']['name'];
		  $old_file  = $data['old_file'];
		  if (!empty($lokasi_file)){
			if ($tipe_file == "application/x-download" 
					OR $tipe_file == "application/pdf" 
					OR $tipe_file == "application/zip"
 					OR $tipe_file == "application/ppt" 
					OR $tipe_file == "application/pptx"
 					OR $tipe_file == "application/rar"					
					OR $tipe_file == "application/excel" 
					OR $tipe_file == "application/msword" 
					OR $tipe_file == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" 
					OR $tipe_file == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" 
					OR $tipe_file == "text/rtf" 
					OR $tipe_file == "application/powerpoint" 
					OR $tipe_file == "application/vnd.ms-powerpoint" 
					OR $tipe_file == "application/vnd.ms-excel" 
					OR $tipe_file == "application/msexcel"
					OR $tipe_file == "application/x-zip"	){
				UploadDocument($nama_file,$old_file);
				unset($data['old_file']);
			} else {
				$_SESSION['success']=-1;
				$nama_file = $data['old_file'];
			}
		  }
		  
		$data['satuan'] = underscore($nama_file);
		$this->db->where('id',$id);
		$outp = $this->db->update('dokumen',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete($id=''){
		$sql  = "DELETE FROM dokumen WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM dokumen WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function dokumen_lock($id='',$val=0){
		
		$sql  = "UPDATE dokumen SET enabled=? WHERE id=?";
		$outp = $this->db->query($sql, array($val,$id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
		
	function get_dokumen($id=0){
		$sql   = "SELECT * FROM dokumen WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function dokumen_show(){
		$sql   = "SELECT * FROM dokumen WHERE enabled=?";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		return $data;
	}
}
?>
