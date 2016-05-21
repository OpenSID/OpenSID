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

class Laporan_Perubahan extends CI_Controller{

function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('laporan_perubahan_model');
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

		$data['config'] = $this->laporan_perubahan_model->configku();
		$data['bln'] = $this->laporan_perubahan_model->bulan(date("m"));
		$data['main']    = $this->laporan_perubahan_model->list_data();
		$data['total']    = $this->laporan_perubahan_model->total_data();

		$data['lap']=$lap;
		$nav['act']= 3;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/laporan/perubahan',$data);
		$this->load->view('footer');
	}

	function cetak(){

		$data['config'] = $this->laporan_perubahan_model->configku();
		$data['bln'] = $this->laporan_perubahan_model->bulan(date("m"));
		$data['main']    = $this->laporan_perubahan_model->list_data();
		$data['total']    = $this->laporan_perubahan_model->total_data();

		$this->load->view('sid/laporan/perubahan_print',$data);
	}


}
