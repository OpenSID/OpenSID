<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hom_sid extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->modul_ini = 1;
	}

	function index(){
		// Pengambilan data penduduk untuk ditampilkan widget Halaman Dashboard (modul Home SID)
		$data['penduduk'] = $this->header_model->penduduk_total();
		$data['keluarga'] = $this->header_model->keluarga_total();
		$data['miskin'] = $this->header_model->miskin_total();
		$data['kelompok'] = $this->header_model->kelompok_total();
		$data['rtm'] = $this->header_model->rtm_total();
		$data['dusun'] = $this->header_model->dusun_total();
		// Menampilkan menu dan sub menu aktif
		$nav['act']= 1;
		$nav['act_sub'] = 16;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('nav',$nav);
		$this->load->view('home/desa',$data);
		$this->load->view('footer');
	}

	function donasi(){
		// Menampilkan menu dan sub menu aktif
		$nav['act']= 1;
		$nav['act_sub'] = 19;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('nav',$nav);
		$this->load->view('home/donasi');
		$this->load->view('footer');
	}

}
