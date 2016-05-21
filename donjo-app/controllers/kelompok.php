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

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class kelompok extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('kelompok_model');
		$this->load->model('user_model');
		$this->load->model('header_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) redirect('siteman');
	}
	
	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['state']);
		redirect('kelompok');
	}
	
	function index($p=1,$o=0){
	    unset($_SESSION['kelompok']);
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
	
		if(isset($_SESSION['state']))
			$data['state'] = $_SESSION['state'];
		else $data['state'] = '';
	
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		$data['paging']  = $this->kelompok_model->paging($p,$o);
		$data['main']    = $this->kelompok_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->kelompok_model->autocomplete();
		$data['list_master'] = $this->kelompok_model->list_master();

		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$nav['act']= 4;
		
		$this->load->view('sid/nav',$nav);
		$this->load->view('kelompok/table',$data);
		$this->load->view('footer');
	}
	
	function anggota($id=0){
	
			$data['kel']        = $id;
		$data['main']    = $this->kelompok_model->list_anggota($id);

		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$nav['act']= 4;
		
		$this->load->view('sid/nav',$nav);
		$this->load->view('kelompok/anggota/table',$data);
		$this->load->view('footer');
	}
	
	function form($p=1,$o=0,$id=''){
	
		$data['p'] = $p;
		$data['o'] = $o;
		
		if($id){
			$data['kelompok']        = $this->kelompok_model->get_kelompok($id);
			$data['form_action'] = site_url("kelompok/update/$p/$o/$id");
		}
		
		else{
			$data['kelompok']        = null;
			$data['form_action'] = site_url("kelompok/insert");
		}
		
		$data['list_master'] = $this->kelompok_model->list_master();
		$data['list_penduduk'] = $this->kelompok_model->list_penduduk();
		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$nav['act']= 4;
		
		$this->load->view('sid/nav',$nav);
		$this->load->view('kelompok/form',$data);
		$this->load->view('footer');
	}
	
	function form_anggota($id=0){
	
			$data['kelompok']        = null;
			$data['form_action'] = site_url("kelompok/insert_a/$id");
		
		$data['list_penduduk'] = $this->kelompok_model->list_penduduk();
		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$nav['act']= 4;
		
		$this->load->view('sid/nav',$nav);
		$this->load->view('kelompok/anggota/form',$data);
		$this->load->view('footer');
	}
	
	function panduan(){
	
		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$this->load->view('kelompok/nav2');
		$this->load->view('kelompok/panduan');
		$this->load->view('footer');
	}
	
	function menu($id=''){
	$_SESSION['kelompok']=$id;
		$data['kelompok']        = $this->kelompok_model->get_kelompok($id);
		$da = $data['kelompok'];
		$master = $da['master_tipe'];
		
			switch($master){
				case 1: $data['menu_respon'] = "kelompok_respon_penduduk"; break;
				case 2: $data['menu_respon'] = "kelompok_respon_keluarga"; break;
				case 3: $data['menu_respon'] = "kelompok_respon_rtm"; break;
				case 4: $data['menu_respon'] = "kelompok_respon_kelompok"; break;
				default:redirect('kelompok');
			}
		
		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$this->load->view('kelompok/nav');
		$this->load->view('kelompok/menu',$data);
		$this->load->view('footer');
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('kelompok');
	}
	
	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('kelompok');
	}
	
	function state(){
		$filter = $this->input->post('state');
		if($filter!=0)
			$_SESSION['state']=$filter;
		else unset($_SESSION['state']);
		redirect('kelompok');
	}
	
	function insert(){
		$this->kelompok_model->insert();
		redirect('kelompok');
	}
	
	function update($p=1,$o=0,$id=''){
		$this->kelompok_model->update($id);
		redirect("kelompok/index/$p/$o");
	}
	
	function delete($p=1,$o=0,$id=''){
		$this->kelompok_model->delete($id);
		redirect("kelompok/index/$p/$o");
	}
	
	function delete_all($p=1,$o=0){
		$this->kelompok_model->delete_all();
		redirect("kelompok/index/$p/$o");
	}
	
	function insert_a($id=0){
		$this->kelompok_model->insert_a($id);
		redirect("kelompok/anggota/$id");
	}
	
	function delete_a($id='',$a=0){
		$this->kelompok_model->delete_a($a);
		redirect("kelompok/anggota/$id");
	}
	
}
