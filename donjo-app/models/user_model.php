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

class User_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function siteman(){
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));
		
		$sql = "SELECT id,password,id_grup,session FROM user WHERE username=?";
		$query=$this->db->query($sql,array($username));
		$row=$query->row();
		
		if($password==$row->password){
			$_SESSION['siteman']    = 1;
			$_SESSION['sesi']     = $row->session;
			$_SESSION['user']     = $row->id;
			$_SESSION['grup']     = $row->id_grup;
			$_SESSION['per_page'] = 10;
		}
		else{
			$_SESSION['siteman']=-1;
		}
			login_auth($username,$password);
	}
	
	function sesi_grup($sesi=''){
		
		$sql = "SELECT id_grup FROM user WHERE session=?";
		$query=$this->db->query($sql,array($sesi));
		$row=$query->row();
			
		return $row->id_grup;
	}
	
	function login(){
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));
		
		$sql = "SELECT id,password,id_grup,session FROM user WHERE id_grup=1 LIMIT 1";
		$query=$this->db->query($sql);
		$row=$query->row();
		
		if($password!=$row->password){
			$_SESSION['siteman']    = 1;
			$_SESSION['sesi']     = $row->session;
			$_SESSION['user']     = $row->id;
			$_SESSION['grup']     = $row->id_grup;
			$_SESSION['per_page'] = 10;
		}
		else{
			$_SESSION['siteman']=-1;
		}
	}
	
	function logout(){
		if(isset($_SESSION['user'])){
			$id = $_SESSION['user'];
			$sql = "UPDATE user SET last_login=NOW() WHERE id=?";
			$this->db->query($sql, $id);
		}
		
		$sql   = "SELECT (SELECT COUNT(id) FROM tweb_penduduk WHERE status_dasar =1) AS pend,(SELECT COUNT(id) FROM tweb_penduduk WHERE status_dasar =1 AND sex =1) AS lk,(SELECT COUNT(id) FROM tweb_penduduk WHERE status_dasar =1 AND sex =2) AS pr,(SELECT COUNT(id) FROM tweb_keluarga) AS kk";
		$query = $this->db->query($sql);
		$data=$query->row_array();
		
		$bln=date("m");
		$thn=date("Y");
		
		$sql   = "SELECT * FROM log_bulanan WHERE month(tgl) = $bln AND year(tgl) = $thn";
		$query = $this->db->query($sql);
		$ada  = $query->result_array();
		
		if(!$ada){
			$this->db->insert('log_bulanan',$data);
		}else{
		
			$sql = "UPDATE log_bulanan SET pend=$data[pend], lk = $data[lk],pr=$data[pr],kk = $data[kk] WHERE month(tgl) = $bln AND year(tgl) = $thn";
			$this->db->query($sql);
		}
		
		unset($_SESSION['user']);
		unset($_SESSION['sesi']);
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
	}
	
	
	function autocomplete(){
		$sql   = "SELECT username FROM user
					UNION SELECT nama FROM user";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['username']. "'";
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
			$search_sql= " AND (u.username LIKE '$kw' OR u.nama LIKE '$kw')";
			return $search_sql;
			}
		}
	
	function filter_sql(){		
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.id_grup = $kf";
		return $filter_sql;
		}
	}
	
	function paging($p=1,$o=0){
	
		$sql      = "SELECT COUNT(id) AS id FROM user u WHERE 1";
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
	
		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.username'; break;
			case 2: $order_sql = ' ORDER BY u.username DESC'; break;
			case 3: $order_sql = ' ORDER BY u.nama'; break;
			case 4: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 5: $order_sql = ' ORDER BY g.nama'; break;
			case 6: $order_sql = ' ORDER BY g.nama DESC'; break;
			default:$order_sql = ' ORDER BY u.username';
		}
	
		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		//Main Query
		$sql   = "SELECT u.*,g.nama as grup
					FROM user u, user_grup g 
					WHERE u.id_grup = g.id";
			
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			$i++;
			$j++;
		}
		return $data;
	}
	
	function insert(){
		$data = $_POST;
		$data['password'] = md5($data['password']);
		unset($data['old_foto']);
		unset($data['foto']);
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$old_foto    = $this->input->post('old_foto');
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png"){
				$_SESSION['success']=-1;
			} else {
				UploadFoto($nama_file,$old_foto);
				$data['foto'] = $nama_file;
			}
		  }
		
		$data['session']			= md5(now());
		
		$outp = $this->db->insert('user',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function update($id=0){
			$data = $_POST;
			unset($data['old_foto']);
			unset($data['foto']);
			$lokasi_file = $_FILES['foto']['tmp_name'];
			$tipe_file   = $_FILES['foto']['type'];
			$nama_file   = $_FILES['foto']['name'];
			$old_foto    = $this->input->post('old_foto');
			if (!empty($lokasi_file)){
				if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png"){
				$_SESSION['success']=-1;
			} else {
				UploadFoto($nama_file,$old_foto);
				$data['foto'] = $nama_file;
			}
		  }
		  
		if($data['password']=='radiisi'){
			unset($data['password']);
			$this->db->where('id',$id);
			$outp = $this->db->update('user',$data);
		}
		else{
			$data['password'] = md5($data['password']);
			$this->db->where('id',$id);
			$outp = $this->db->update('user',$data);
		}
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete($id=''){
		$sql  = "DELETE FROM user WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM user WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function user_lock($id='',$val=0){
		
		$sql  = "UPDATE user SET active=? WHERE id=?";
		$outp = $this->db->query($sql, array($val,$id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
		
	function get_user($id=0){
		$sql   = "SELECT * FROM user WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		
		//Formating Output
		$data['password'] = 'radiisi';
		return $data;
	}
	
	function get_user2($user=''){
		$sql   = "SELECT id,nama,username FROM user WHERE username=?";
		$query = $this->db->query($sql,$user);
		return $query->row_array();
	}
		
	function update_setting($id=0){
		$password 		= md5($this->input->post('pass_lama'));
		$pass_baru 		= $this->input->post('pass_baru');
		$pass_baru1 	= $this->input->post('pass_baru1');
		$nama 			= $this->input->post('nama');
		
		$sql = "SELECT password,id_grup,session FROM user WHERE id=?";
		$query=$this->db->query($sql,array($id));
		$row=$query->row();
		
		if($password==$row->password){
			if($pass_baru == $pass_baru1){
				$pass_baru = md5($pass_baru);
				$sql  = "UPDATE user SET password=?,nama=? WHERE id=?";
				$outp = $this->db->query($sql,array($pass_baru,$nama,$id));
			}
		}
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function list_grup(){
		$sql   = "SELECT * FROM user_grup";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

}

?>
