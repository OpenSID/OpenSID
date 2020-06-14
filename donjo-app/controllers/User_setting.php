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
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('pass_baru', 'Kata Sandi Baru', 'required|callback_syarat_sandi');
		$this->form_validation->set_message('syarat_sandi','Harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil');

		if ($this->form_validation->run() !== true)
		{
			redirect($_SERVER['HTTP_REFERER']);
		}
		else
		{
			$this->user_model->update_setting($id);
			if ($this->session->success == -1)
			{
				redirect($_SERVER['HTTP_REFERER']);
			}
			else redirect("main");
		}
	}

	public function update_password($id = '')
	{
		$this->user_model->update_password($id);
		if ($this->session->success == -1)
		{
			redirect($_SERVER['HTTP_REFERER']);
		}
		else redirect("main");
	}

	// Kata sandi harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil
	public function syarat_sandi($str)
	{
		if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/', $str))
			return TRUE;
		else
			return FALSE;
	}

	public function change_pwd()
	{
		$id = $_SESSION['user'];
		$data['main'] = $this->user_model->get_user($id);
		$data['header'] = $this->config_model->get_data();
		$this->load->view('setting_pwd', $data);
	}

}
