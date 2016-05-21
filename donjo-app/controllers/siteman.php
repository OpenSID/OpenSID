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

class Siteman extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('user_model');
	}
	
	function index(){
		$this->user_model->logout();
		$header = $this->header_model->get_config();
		
		//Initialize Session ------------
		if(!isset($_SESSION['siteman']))
		$_SESSION['siteman']=0;
		$_SESSION['success']  = 0;
		$_SESSION['per_page'] = 10;
		$_SESSION['cari']  = '';
		$_SESSION['pengumuman'] = 0;
		$_SESSION['sesi'] = "kosong";
		//-------------------------------
		
		$this->load->view('siteman',$header);
		$_SESSION['siteman']=0;
	}
	
	function auth(){
		$this->user_model->siteman();
		redirect('main');
	}
	
	function login(){
		$this->user_model->login();
		$header = $this->header_model->get_config();
		$this->load->view('siteman',$header);
	}

	function flash(){
		$this->load->view('config');
	}
	
}
