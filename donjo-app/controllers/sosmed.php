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

class sosmed extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('web_sosmed_model');
	
	}
	
	function index(){		
		$data['main']    = $this->web_sosmed_model->get_sosmed(1);
		$id=$data['main']['id'];
		$data['form_action'] = site_url("sosmed/update/1");
		$header = $this->header_model->get_data();
		$nav['act']=6;
		
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('sosmed/facebook',$data);
		$this->load->view('footer');
	}
	
	function twitter(){		
		$data['main']    = $this->web_sosmed_model->get_sosmed(2);
		$id=$data['main']['id'];
		$data['form_action'] = site_url("sosmed/update/2/$id");
		$header = $this->header_model->get_data();
		$nav['act']=6;
		
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('sosmed/twitter',$data);
		$this->load->view('footer');
	}
	
	function instagram(){		
		$data['main']    = $this->web_sosmed_model->get_sosmed(3);
		$data['form_action'] = site_url("sosmed/update/3");
		$header = $this->header_model->get_data();
		$nav['act']=6;
		
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('sosmed/instagram',$data);
		$this->load->view('footer');
	}
	
	function google(){		
		$data['main']    = $this->web_sosmed_model->get_sosmed(3);
		$data['form_action'] = site_url("sosmed/update/3");
		$header = $this->header_model->get_data();
		$nav['act']=6;
		
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('sosmed/instagram',$data);
		$this->load->view('footer');
	}
	
	function youtube(){		
		$data['main']    = $this->web_sosmed_model->get_sosmed(4);
		$data['form_action'] = site_url("sosmed/update/4");
		$header = $this->header_model->get_data();
		$nav['act']=6;
		
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('sosmed/youtube',$data);
		$this->load->view('footer');
	}
	
	function update($id=''){
		$this->web_sosmed_model->update($id);
		if($id=='1'){
			redirect("sosmed");
			}elseif($id=='2'){
			redirect("sosmed/twitter");
			}elseif($id=='3'){
			redirect("sosmed/instagram");
		}else{
			redirect("sosmed/youtube");
		}
	}
	
}
