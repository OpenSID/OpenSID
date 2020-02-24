<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tema extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('theme_model');
		$this->modul_ini = 13;
	}

	public function index()//bagus
	{
		$data['list_tema'] = $this->theme_model->list_all();//daftar tema
		$data['tema_aktif'] = $this->theme_model->active();//tema aktif
		$this->setting_model->load_options();
		
		$header = $this->header_model->get_data();
		$nav['act'] = 13;
		$nav['act_sub'] = 205;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('tema/table', $data);
		$this->load->view('footer');
	}
}
