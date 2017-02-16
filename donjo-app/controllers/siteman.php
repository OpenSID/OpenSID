<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Siteman extends CI_Controller {
	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('user_model');
		$this->load->model('config_model');
	}
	function index(){
		$this->user_model->logout();
		$header = $this->header_model->get_config();
		
		
		if(!isset($_SESSION['siteman']))
		$_SESSION['siteman']=0;
		$_SESSION['success']  = 0;
		$_SESSION['per_page'] = 10;
		$_SESSION['cari']  = '';
		$_SESSION['pengumuman'] = 0;
		$_SESSION['sesi'] = "kosong";
		$_SESSION['timeout'] = 0;
		
		$this->load->view('siteman',$header);
		$_SESSION['siteman']=0;
	}
	function auth(){
		$this->config_model->do_reg();
		$this->user_model->siteman();
		redirect('main');
	}
	function login(){
		$this->user_model->logout();
		redirect('siteman');
	}
}