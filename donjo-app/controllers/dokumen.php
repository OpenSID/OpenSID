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

class Dokumen extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('web_dokumen_model');

	}
	
	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('dokumen');
	}
	
	function index($p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
	
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		$data['paging']  = $this->web_dokumen_model->paging($p,$o);
		$data['main']    = $this->web_dokumen_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_dokumen_model->autocomplete();

		$header = $this->header_model->get_data();
		$nav['act']=4;
		
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('dokumen/table',$data);
		$this->load->view('footer');
	}
	
	function form($p=1,$o=0,$id=''){
	
		$data['p'] = $p;
		$data['o'] = $o;
		
		if($id){
			$data['dokumen']        = $this->web_dokumen_model->get_dokumen($id);
			$data['form_action'] = site_url("dokumen/update/$id/$p/$o");
		}
		else{
			$data['dokumen']        = null;
			$data['form_action'] = site_url("dokumen/insert");
		}
		
		$header = $this->header_model->get_data();
		
		$nav['act']=4;
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('dokumen/form',$data);
		$this->load->view('footer');
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('dokumen');
	}
	
	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('dokumen');
	}
	
	function insert(){
		$this->web_dokumen_model->insert();
		redirect('dokumen');
	}
	
	function update($id='',$p=1,$o=0){
		$this->web_dokumen_model->update($id);
		redirect("dokumen/index/$p/$o");
	}
	
	function delete($p=1,$o=0,$id=''){
		$this->web_dokumen_model->delete($id);
		redirect("dokumen/index/$p/$o");
	}
	
	function delete_all($p=1,$o=0){
		$this->web_dokumen_model->delete_all();
		redirect("dokumen/index/$p/$o");
	}
	
	function dokumen_lock($id=''){
		$this->web_dokumen_model->dokumen_lock($id,1);
		redirect("dokumen/index/$p/$o");
	}

	function dokumen_unlock($id=''){
		$this->web_dokumen_model->dokumen_lock($id,2);
		redirect("dokumen/index/$p/$o");
	}
}
