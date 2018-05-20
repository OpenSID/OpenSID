<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_setting extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=(1 OR 2 OR 3 OR 4 OR 5)) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('login');
		}
		$this->load->model('header_model');
	}

	function index(){
		$id = $_SESSION['user'];
		$header = $this->header_model->get_data();
		//$this->load->view('header', $header);

		$header = $this->header_model->get_data();
		$data['main'] = $this->user_model->get_user($id);

		$this->load->view('setting', $data);
		//$this->load->view('footer');

	}

	function update($id=''){
		$this->user_model->update_setting($id);
		redirect("main");
	}

}
