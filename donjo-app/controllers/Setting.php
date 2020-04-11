<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
HEAD
HEAD
		$this->load->model('setting_model');
=======

=======

		$this->load->model('header_model');
		$this->load->model('theme_model');
		$this->modul_ini = 11;
		$this->sub_modul_ini = 43;
	}

	public function index()
	{
HEAD
HEAD
		$nav['act'] = 11;
		$nav['act_sub'] = 43;
=======

=======

		$header = $this->header_model->get_data();
		$data['list_tema'] = $this->theme_model->list_all();
		$this->setting_model->load_options();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('setting/setting_form', $data);
		$this->load->view('footer');
	}

	public function update()
	{
		$this->setting_model->update($this->input->post());
		redirect('setting');
	}

	public function info_sistem()
	{
HEAD
HEAD
		$nav['act'] = 11;
		$nav['act_sub'] = 46;
=======
		$this->sub_modul_ini = 46;
		

=======
		$this->sub_modul_ini = 46;
		

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('setting/info_php');
		$this->load->view('footer');
	}

}
