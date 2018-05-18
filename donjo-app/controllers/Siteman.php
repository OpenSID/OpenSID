<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Siteman extends CI_Controller {

	function __construct(){
		parent::__construct();
		session_start();
		siteman_timeout();
		$this->load->model('header_model');
		$this->load->model('user_model');
		$this->load->model('track_model');
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
		$this->track_model->track_desa('siteman');
	}

	function auth(){
		$this->user_model->siteman();

		if($_SESSION['siteman'] == 1) {
			$this->user_model->validate_admin_has_changed_password();
			$_SESSION['dari_login'] = '1';
			if(isset($_SESSION['request_uri'])){
				$request_awal = str_replace(parse_url(site_url(),PHP_URL_PATH),'',$_SESSION['request_uri']);
				unset($_SESSION['request_uri']);
				redirect($request_awal);
			} else
				redirect('main');
		}
		else
			redirect('siteman');
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
