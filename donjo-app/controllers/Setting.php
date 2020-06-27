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

	public function qrcode($aksi = NULL)
	{
		if($aksi == 'clear')
		{
			$this->session->unset_userdata('qrcode');
			redirect('setting/qrcode');
		}

		$this->modul_ini = 11;
		$this->sub_modul_ini = 212;

		$header = $this->header_model->get_data();

		$data['qrcode'] = $this->session->qrcode;
		$data['list_sizeqr'] = ['25', '50', '75', '100', '125', '150', '200', '225', '250'];
		$data['form_action'] = site_url("setting/qrcode_generate");

		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('setting/setting_qr', $data);
		$this->load->view('footer');
	}

	public function qrcode_generate()
	{
		$pathqr = LOKASI_MEDIA;

		$post = $this->input->post();

		$namaqr = $post['namaqr']; // Nama file gambar
		$isiqr = $post['isiqr']; // Isi / arti dr qrcode
		$logoqr = $post['logoqr']; // Logo yg disisipkan
		$sizeqr = $post['sizeqr']; // Ukuran qrcode
		$backqr = '#ffffff'; // Code warna default asli (#ffffff / putih)
		$foreqr = $post['foreqr']; // Code warna asli

		$namaqr = str_replace(' ', '_', $namaqr); // Normalkan nama file gambar

		$this->session->qrcode = [
			'namaqr' => $namaqr,
			'isiqr'  => $isiqr,
			'logoqr' => $logoqr,
			'sizeqr' => $sizeqr,
			'backqr' => $backqr,
			'qrcode' => $qrcode,
			'pathqr' => $pathqr.''.$namaqr.'.png'
		];

		if($post)
		{
			$this->session->success = 1;
			qrcode_generate($pathqr, $namaqr, $isiqr, $logoqr, $sizeqr, $backqr, $foreqr);
		}
		else
		{
			$this->session->success = -1;
		}

		redirect('setting/qrcode');
	}

	public function akas()
	{
		$data = $this->session->qrcode;

		echo json_encode($data, true);
	}

}
