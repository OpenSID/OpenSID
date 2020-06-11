<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_setting extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->load->model('user_model');
	}

	public function index()
	{
		$id = $_SESSION['user'];
		$data['main'] = $this->user_model->get_user($id);
		$this->load->view('setting', $data);
	}

	public function update($id = '')
	{
		$this->user_model->update_setting($id);
		$this->user_model->logout();
		redirect("main");
	}

	public function change_pwd()
	{
		$id = $_SESSION['user'];
		$data['main'] = $this->user_model->get_user($id);
		$data['header'] = $this->config_model->get_data();
		$this->load->view('setting_pwd', $data);
	}

}
