<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Sekretariat extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		$this->modul_ini = 15;
	}

	function index(){
		redirect('surat_masuk/clear');
	}

}
