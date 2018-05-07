<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends CI_Controller{
	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('setting_model');
		$this->load->model('header_model');
		$this->load->model('theme_model');
	}

	function index(){
		$nav['act']= 2;
		$header = $this->header_model->get_data();
		$data['list_tema'] = $this->theme_model->list_all();

		$this->load->view('header',$header);

		$this->load->view('setting/nav',$nav);
		$this->load->view('setting/setting_form',$data);
		$this->load->view('footer');
	}

	function update(){
		$this->setting_model->update($this->input->post());
		redirect('setting');
	}

	function info_sistem(){
		$nav['act']= 3;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);

		$this->load->view('setting/nav',$nav);
		$this->load->view('setting/info_php');
		$this->load->view('footer');
	}

}
