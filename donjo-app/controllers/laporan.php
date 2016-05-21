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

class Laporan extends CI_Controller{

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
		$_SESSION['cari']  = '';
		//-------------------------------

		$this->load->model('header_model');
	}
        
    function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['sex']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['agama']);
		unset($_SESSION['umur_min']);
		unset($_SESSION['umur_max']);
		unset($_SESSION['pekerjaan_id']);
		unset($_SESSION['status']);
		unset($_SESSION['pendidikan_id']);
		unset($_SESSION['status_penduduk']);
		$_SESSION['bulanku']=date("n");
		$_SESSION['tahunku']=date("Y");
		$_SESSION['per_page'] = 200;
		redirect('laporan');
	}

	function index($lap=0,$p=1,$o=0){

		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		if(isset($_SESSION['bulanku']))
			$data['bulanku'] = $_SESSION['bulanku'];
		else $data['bulanku'] = date("n");	
		
		if(isset($_SESSION['tahunku']))
			$data['tahunku'] = $_SESSION['tahunku'];
		else $data['tahunku'] = date("Y");	
		
		$data['bulan']=$data['bulanku'];
		$data['tahun']=$data['tahunku'];
		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['penduduk_awal']    = $this->laporan_bulanan_model->penduduk_awal();
		$data['penduduk_akhir']    = $this->laporan_bulanan_model->penduduk_akhir();
		$data['kelahiran']    = $this->laporan_bulanan_model->kelahiran();
		$data['kematian']    = $this->laporan_bulanan_model->kematian();
		$data['pendatang']    = $this->laporan_bulanan_model->pendatang();
		$data['pindah']    = $this->laporan_bulanan_model->pindah();
		$data['hilang']    = $this->laporan_bulanan_model->hilang();
		$data['lap']=$lap;
		$nav['act']= 3;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('statistik/nav',$nav);
		$this->load->view('laporan/bulanan',$data);
		$this->load->view('footer');
		//unset($_SESSION['bulan']);
		//print_r(	$data['kelahiran'] );
	}

	function cetak($lap=0){

		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['bulan']=$_SESSION['bulanku'];
		$data['tahun']=$_SESSION['tahunku'];
		$data['bln'] = $this->laporan_bulanan_model->bulan($data['bulan']);
		$data['penduduk_awal']    = $this->laporan_bulanan_model->penduduk_awal();
		$data['penduduk_akhir']    = $this->laporan_bulanan_model->penduduk_akhir();
		$data['kelahiran']    = $this->laporan_bulanan_model->kelahiran();
		$data['kematian']    = $this->laporan_bulanan_model->kematian();
		$data['pendatang']    = $this->laporan_bulanan_model->pendatang();
		$data['pindah']    = $this->laporan_bulanan_model->pindah();
		$data['hilang']    = $this->laporan_bulanan_model->hilang();
		$data['lap']=$lap;
		$this->load->view('laporan/bulanan_print',$data);
	}
	

	function excel($lap=0){

		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['bulan']=$_SESSION['bulanku'];
		$data['tahun']=$_SESSION['tahunku'];
		$data['bln'] = $this->laporan_bulanan_model->bulan($data['bulan']);
		$data['penduduk_awal']    = $this->laporan_bulanan_model->penduduk_awal();
		$data['penduduk_akhir']    = $this->laporan_bulanan_model->penduduk_akhir();
		$data['kelahiran']    = $this->laporan_bulanan_model->kelahiran();
		$data['kematian']    = $this->laporan_bulanan_model->kematian();
		$data['pendatang']    = $this->laporan_bulanan_model->pendatang();
		$data['pindah']    = $this->laporan_bulanan_model->pindah();
		$data['hilang']    = $this->laporan_bulanan_model->hilang();
		$data['lap']=$lap;
		$this->load->view('statistik/laporan/bulanan_excel',$data);
	}	

	function bulan(){
		$bulanku= $this->input->post('bulan');
		if($bulanku!="")
			$_SESSION['bulanku']=$bulanku;
		else unset($_SESSION['bulanku']);
		
		$tahunku= $this->input->post('tahun');
		if($tahunku!="")
			$_SESSION['tahunku']=$tahunku;
		else unset($_SESSION['tahunku']);
		redirect('laporan');
	}
}
