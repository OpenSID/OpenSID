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

class widget extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('web_widget_model');
	
	}
	
	function index(){		
		$data['main']    = $this->web_widget_model->get_widget();
		$id=$data['main']['id'];
		$data['form_action'] = site_url("web/widget/update/1/$id");
		$header = $this->header_model->get_data();
		$nav['act']=5;
		
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('web/widget/facebook',$data);
		$this->load->view('footer');
	}
	
	function twitter(){		
		$data['main']    = $this->web_widget_model->get_widget();
		$id=$data['main']['id'];
		$data['form_action'] = site_url("web/widget/update/2/$id");
		$header = $this->header_model->get_data();
		$nav['act']=5;
		
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('web/widget/twitter',$data);
		$this->load->view('footer');
	}
	
	function update($tipe='',$id=''){
		$this->web_widget_model->update($id);
		if($tipe=='1'){
			redirect("web/widget");
			}else{
			redirect("web/widget/twitter");
		}
	}
	
}
