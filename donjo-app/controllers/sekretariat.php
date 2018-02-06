<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Sekretariat extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$_SESSION['request_uri'] = $_SESSION['REQUEST_URI'];
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=(1 or 2 or 3)) {
			$_SESSION['request_uri'] = base_url();
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->modul_ini = 15;
	}

	function index(){
		redirect('surat_masuk/clear');
	}

}
