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

class Hom_Desa extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('config_model');
	}
	
	function index(){
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$data['main'] = $this->config_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('home/nav',$nav);
		$this->load->view('home/konfigurasi_form',$data);
		$this->load->view('footer');
	}
	
	function about(){
		$nav['act']= 2;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('home/nav',$nav);
		$this->load->view('home/desa');
		$this->load->view('footer');
	}
	
	function insert(){
		$this->config_model->insert();
		redirect('hom_desa');
	}
	
	function update($id=''){
		$this->config_model->update($id);
		redirect("hom_desa");
	}
		
	function ajax_kantor_maps(){
		$data['desa'] = $this->config_model->get_data();
		$data['form_action'] = site_url("hom_desa/update_kantor_maps/");
		$this->load->view("home/ajax_kantor_desa_maps", $data);
	}
	
	function ajax_wilayah_maps(){
		$data['desa'] = $this->config_model->get_data();
		$data['form_action'] = site_url("hom_desa/update_wilayah_maps/");
		$this->load->view("home/ajax_wilayah_desa_maps", $data);
	}
	
	function update_kantor_maps(){
		$this->config_model->update_kantor();
		redirect("hom_desa");
	}
	
	function update_wilayah_maps(){
		$this->config_model->update_wilayah();
		redirect("hom_desa");
	}
	
	function upgrade_silent(){
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('home/nav',$nav);
		$this->load->view('home/upgrade_form');
		$this->load->view('footer');
	}	
		
	function upgrader(){
		$kode = $_POST['upkode'];
		if($kode=="formasikombinasi")
			$this->config_model->upgrade();
		else
			$_SESSION['success']=-1;
		
		redirect("hom_desa/upgrade_silent");
	}
	
	function kosong_pend(){
		$this->config_model->kosong_pend();
		redirect("hom_desa");
	}
		
}
