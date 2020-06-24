<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Admin_Controller {

	private $_header;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['header_model', 'theme_model']);
		$this->_header = $this->header_model->get_data();
		$this->modul_ini = 11;
		$this->sub_modul_ini = 43;
	}

	public function index()
	{
		$data['list_tema'] = $this->theme_model->list_all();
		$data['judul'] = 'Pengaturan Aplikasi';
		$data['list_setting'] = 'list_setting';
		$this->setting_model->load_options();

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('setting/setting_form', $data);
		$this->load->view('footer');
	}

	public function update()
	{
		$this->setting_model->update_setting($this->input->post());
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function tracking()
	{
		$this->setting_model->update();
		redirect('setting');
	}

	public function info_sistem()
	{
		$this->sub_modul_ini = 46;

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('setting/info_php');
		$this->load->view('footer');
	}

	/* Pengaturan web */
	public function web()
	{
		$this->modul_ini = 13;
		$this->sub_modul_ini = 211;

		$data['judul'] = 'Pengaturan Halaman Web';
		$data['list_setting'] = 'list_setting_web';
		$this->setting_model->load_options();

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('setting/setting_form', $data);
		$this->load->view('footer');
	}

}
