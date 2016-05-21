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

class Analisis_kategori extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('analisis_kategori_model');
		$this->load->model('user_model');
		$this->load->model('header_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) redirect('siteman');
	}
	
	function clear($id=0){
		$_SESSION['analisis_master']=$id;
		unset($_SESSION['cari']);
		redirect('analisis_kategori');
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
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		$data['paging']  = $this->analisis_kategori_model->paging($p,$o);
		$data['main']    = $this->analisis_kategori_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_kategori_model->autocomplete();
		$data['analisis_master'] = $this->analisis_kategori_model->get_analisis_master();

		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_kategori/table',$data);
		$this->load->view('footer');
	}
	
	function form($p=1,$o=0,$id=''){
	
		$data['p'] = $p;
		$data['o'] = $o;
		
		if($id){
			$data['analisis_kategori']        = $this->analisis_kategori_model->get_analisis_kategori($id);
			$data['form_action'] = site_url("analisis_kategori/update/$p/$o/$id");
		}
		
		else{
			$data['analisis_kategori']        = null;
			$data['form_action'] = site_url("analisis_kategori/insert");
		}
		
		//$header = $this->header_model->get_data();
		//$data['analisis_master'] = $this->analisis_kategori_model->get_analisis_master();
		
		//$this->load->view('header', $header);
		//$this->load->view('analisis_master/nav');
		$this->load->view('analisis_kategori/ajax_form',$data);
		//$this->load->view('footer');
	}
	
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis_kategori');
	}
	
	function insert(){
		$this->analisis_kategori_model->insert();
		redirect('analisis_kategori');
	}
	
	function update($p=1,$o=0,$id=''){
		$this->analisis_kategori_model->update($id);
		redirect("analisis_kategori/index/$p/$o");
	}
	
	function delete($p=1,$o=0,$id=''){
		$this->analisis_kategori_model->delete($id);
		redirect("analisis_kategori/index/$p/$o");
	}
	
	function delete_all($p=1,$o=0){
		$this->analisis_kategori_model->delete_all();
		redirect("analisis_kategori/index/$p/$o");
	}
	
}
