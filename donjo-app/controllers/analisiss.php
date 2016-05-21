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

class Pengurus extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('pamong_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) redirect('siteman');
		$this->load->model('header_model');		
	}
		
	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('pengurus');
	}
	
	function index(){
			
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
	
		$data['main'] = $this->pamong_model->list_data();
		$data['keyword'] = $this->pamong_model->autocomplete();
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		
		$this->load->view('header',$header);
		
		$this->load->view('home/nav',$nav);
		$this->load->view('home/pengurus',$data);
		$this->load->view('footer');
	}
		
	function form($id=''){
		
		if($id){
			$data['pamong']          = $this->pamong_model->get_data($id);
			$data['form_action'] = site_url("pengurus/update/$id");
		}
		else{
			$data['pamong']          = null;
			$data['form_action'] = site_url("pengurus/insert");
		}
		
		$header = $this->header_model->get_data();
		
		$this->load->view('header',$header);
		
		$nav['act']= 1;
		$this->load->view('home/nav',$nav);
		$this->load->view('home/pengurus_form',$data);
		$this->load->view('footer');
	}
	
	function filter(){
		$filter = $this->input->post('filter');
		if($filter!="")
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('pengurus');
	}
	
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('pengurus');
	}
	
	function insert(){
		$this->pamong_model->insert();
		redirect('pengurus');
	}
	
	function update($id=''){
		$this->pamong_model->update($id);
		redirect('pengurus');
	}
	
	function delete($id=''){
		$this->pamong_model->delete($id);
		redirect('pengurus');
	}
	
	function delete_all(){
		$this->pamong_model->delete_all();
		redirect('pengurus');
	}	
	
}
