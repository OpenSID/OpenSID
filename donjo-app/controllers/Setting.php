<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('setting_model');
		$this->load->model('theme_model');
		$this->modul_ini = 11;
		$this->sub_modul_ini = 43;
	}

	public function index()
	{
		$data['list_tema'] = $this->theme_model->list_all();
		$this->setting_model->load_options();

		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('setting/setting_form', $data);
	}

	public function update()
	{
		$this->setting_model->update($this->input->post());
		redirect('setting');
	}

	public function info_sistem()
	{
		$this->sub_modul_ini = 46;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('setting/info_php', $data, TRUE);
	}

}
