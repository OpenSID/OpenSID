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

class analisis_respon_keluarga extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('analisis_respon_keluarga_model');
		$this->load->model('user_model');
		$this->load->model('header_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) redirect('siteman');
	}
	
	function clear($id=0){
		$_SESSION['analisis_master']=$id;
		unset($_SESSION['cari']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$_SESSION['per_page'] = 50;
		redirect('analisis_respon_keluarga');
	}
	
	function leave(){
		$id=$_SESSION['analisis_master'];
		unset($_SESSION['analisis_master']);
		redirect("analisis_master/menu/$id");
	}
	
	function index($p=1,$o=0){
	
		unset($_SESSION['cari2']);
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_respon_keluarga_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->analisis_respon_keluarga_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		$data['list_dusun'] = $this->analisis_respon_keluarga_model->list_dusun();
		$data['paging']  = $this->analisis_respon_keluarga_model->paging($p,$o);
		$data['main']    = $this->analisis_respon_keluarga_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_respon_keluarga_model->autocomplete();
		$data['analisis_master'] = $this->analisis_respon_keluarga_model->get_analisis_master();
		$data['analisis_periode'] = $this->analisis_respon_keluarga_model->get_periode();

		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_respon_keluarga/table',$data);
		$this->load->view('footer');
	}
	
	function kuisioner($p=1,$o=0,$id=''){
	
		$data['p'] = $p;
		$data['o'] = $o;
		
		$data['analisis_master'] = $this->analisis_respon_keluarga_model->get_analisis_master();
		$data['subjek']        = $this->analisis_respon_keluarga_model->get_subjek($id);
		$data['list_jawab'] = $this->analisis_respon_keluarga_model->list_indikator($id);
		$data['form_action'] = site_url("analisis_respon_keluarga/update_kuisioner/$p/$o/$id");
		
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_respon_keluarga/manajemen_kuisioner_form',$data);
		$this->load->view('footer');
	}

	function update_kuisioner($p=1,$o=0,$id=''){
		$this->analisis_respon_keluarga_model->update_kuisioner($id);
		redirect("analisis_respon_keluarga/index/$p/$o");
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis_respon_keluarga');
	}
	
	function dusun(){
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect('analisis_respon_keluarga');
	}
	
	function rw(){
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect('analisis_respon_keluarga');
	}
	
	function rt(){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect('analisis_respon_keluarga');
	}
	
	function insert(){
		$this->analisis_respon_keluarga_model->insert();
		redirect('analisis_respon_keluarga');
	}
	
	function update($p=1,$o=0,$id=''){
		$this->analisis_respon_keluarga_model->update($id);
		redirect("analisis_respon_keluarga/index/$p/$o");
	}
	
	function delete($p=1,$o=0,$id=''){
		$this->analisis_respon_keluarga_model->delete($id);
		redirect("analisis_respon_keluarga/index/$p/$o");
	}
	
	function delete_all($p=1,$o=0){
		$this->analisis_respon_keluarga_model->delete_all();
		redirect("analisis_respon_keluarga/index/$p/$o");
	}
	
}