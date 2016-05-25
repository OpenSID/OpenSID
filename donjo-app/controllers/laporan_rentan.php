<?php
/*
 * Berkas default dari halaman web utk publik
 * 
 * Copyright 2013 
 * Rizka Himawan <himawan.rizka@gmail.com>
 * Muhammad Khollilurrohman <adsakle1@gmail.com>
 * Asep Nur Ajiyati <asepnurajiyati@gmail.com>
 *
 * statistik adalah software tak berbayar (Opensource) yang boleh digunakan oleh siapa saja selama bukan untuk kepentingan profit atau komersial.
 * Lisensi ini mengizinkan setiap orang untuk menggubah, memperbaiki, dan membuat ciptaan turunan bukan untuk kepentingan komersial
 * selama mereka mencantumkan asal pembuat kepada Anda dan melisensikan ciptaan turunan dengan syarat yang serupa dengan ciptaan asli.
 * Untuk mendapatkan statistik RESMI, Anda diharuskan mengirimkan surat permohonan ataupun izin statistik terlebih dahulu, 
 * aplikasi ini akan tetap bersifat opensource dan anda tidak dikenai biaya.
 * Bagaimana mendapatkan izin statistik, ikuti link dibawah ini:
 * http://lumbungkomunitas.net/bergabung/pendaftaran/daftar-online/
 * Creative Commons Attribution-NonCommercial 3.0 Unported License
 * statistik Opensource TIDAK BOLEH digunakan dengan tujuan profit atau segala usaha  yang bertujuan untuk mencari keuntungan. 
 * Pelanggaran HaKI (Hak Kekayaan Intelektual) merupakan tindakan  yang menghancurkan dan menghambat karya bangsa.
 */
?><?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_Rentan extends CI_Controller{

function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('laporan_bulanan_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		
		//Initialize Session ------------
		$_SESSION['success']  = 0;
		$_SESSION['per_page'] = 20;
		$_SESSION['cari']  = '';
		//-------------------------------
		
		$this->load->model('header_model');
	}
	

	function index($lap=0,$p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		if(isset($_SESSION['dusun']))
			$data['dusun'] = $_SESSION['dusun'];
		else $data['dusun'] = '';	
		
		$data['list_dusun'] = $this->laporan_bulanan_model->list_dusun();
		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['paging']  = $this->laporan_bulanan_model->paging($lap,$p,$o);
		$data['main']    = $this->laporan_bulanan_model->list_data($lap,$o, $data['paging']->offset, $data['paging']->per_page);
		//$data['keyword'] = $this->laporan_bulanan_model->autocomplete();
		$data['lap']=$lap;
		$nav['act']= 2;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('statistik/nav',$nav);
		$this->load->view('laporan/kelompok',$data);
		$this->load->view('footer');
	}
		
	function cetak(){
	
		if(isset($_SESSION['dusun']))
			$data['dusun'] = $_SESSION['dusun'];
		else $data['dusun'] = '';	
		
		$data['list_dusun'] = $this->laporan_bulanan_model->list_dusun();
		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['main']    = $this->laporan_bulanan_model->list_data();
		$this->load->view('statistik/laporan/kelompok_print',$data);
	}

	function excel(){
	
		if(isset($_SESSION['dusun']))
			$data['dusun'] = $_SESSION['dusun'];
		else $data['dusun'] = '';	
		
		$data['list_dusun'] = $this->laporan_bulanan_model->list_dusun();
		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['main']    = $this->laporan_bulanan_model->list_data();
		$this->load->view('statistik/laporan/kelompok_excel',$data);
	}
		
	function dusun(){
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect('laporan_rentan');
	}
}
