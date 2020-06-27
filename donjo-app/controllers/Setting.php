<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('theme_model');
		$this->modul_ini = 11;
		$this->sub_modul_ini = 43;
		$this->load->library('ciqrcode'); //pemanggilan library QR CODE
	}

	public function index()
	{
		$header = $this->header_model->get_data();
		$data['list_tema'] = $this->theme_model->list_all();
		$data['judul'] = 'Pengaturan Aplikasi';
		$data['list_setting'] = 'list_setting';
		$this->setting_model->load_options();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('setting/setting_form', $data);
		$this->load->view('footer');
	}

	public function update()
	{
		$this->setting_model->update($this->input->post());
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function info_sistem()
	{
		$this->sub_modul_ini = 46;

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('setting/info_php');
		$this->load->view('footer');
	}

	/* Pengaturan web */
	public function web()
	{
		$this->modul_ini = 13;
		$this->sub_modul_ini = 211;

		$header = $this->header_model->get_data();
		$data['list_tema'] = $this->theme_model->list_all();
		$data['judul'] = 'Pengaturan Halaman Web';
		$data['list_setting'] = 'list_setting_web';
		$this->setting_model->load_options();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('setting/setting_form', $data);
		$this->load->view('footer');
	}

	public function qrcode_setting()
	{
		$this->modul_ini = 11;
		$this->sub_modul_ini = 212;

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('setting/setting_qr');
		$this->load->view('footer');
	}

	public function qrcode_generate()
	{
		$namaqr = $this->input->post('namaqr');
		$isiqr = $this->input->post('isiqr');
		$logoqr = $this->input->post('logoqr');
		$sizeqr = $this->input->post('sizeqr');
		$backqr = $this->input->post('backqr');
		$foreqr = $this->input->post('foreqr');
		$backqr1 = preg_replace('/#/', '0x', $backqr);
		$foreqr1 = preg_replace('/#/', '0x', $foreqr);
		$this->session->namaqr = $namaqr;
		$this->session->isiqr = $isiqr;
		$this->session->logoqr = $logoqr;
		$this->session->sizeqr = $sizeqr;
		$this->session->backqr = $backqr;
		$this->session->backqr1 = $backqr1;
		$this->session->foreqr = $foreqr;
		$this->session->foreqr1 = $foreqr1;
		$this->session->qrcode = $namaqr;
		$data = $this->setting_model->qrcode_generate($namaqr, $isiqr, $logoqr, $sizeqr, $backqr, $foreqr, $backqr1, $foreqr1);
		echo json_encode($data);
		redirect($_SERVER['HTTP_REFERER']);
	}

}
