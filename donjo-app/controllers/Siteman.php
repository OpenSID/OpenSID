<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Siteman extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		session_start();
		siteman_timeout();
		$this->load->model('config_model');
		$this->load->model('user_model');
	}

	public function index()
	{
		unset($_SESSION['balik_ke']);
		$this->user_model->logout();
		$data['header'] = $this->config_model->get_data();

		//Initialize Session ------------
		if (!isset($_SESSION['siteman']))
		{
			// Belum ada session variable
			$this->session->set_userdata('siteman', 0);
			$this->session->set_userdata('siteman_try', 4);
			$this->session->set_userdata('siteman_wait', 0);
		}
		$_SESSION['success'] = 0;
		$_SESSION['per_page'] = 10;
		$_SESSION['cari'] = '';
		$_SESSION['pengumuman'] = 0;
		$_SESSION['sesi'] = "kosong";
		//-------------------------------

		$this->load->view('siteman', $data);
	}

	public function auth()
	{
		$this->user_model->siteman();

		if ($_SESSION['siteman'] != 1)
		{
			// Gagal otentifikasi
			redirect('siteman');
		}

		if (!$this->user_model->syarat_sandi() and !($this->session->user == 1 && $this->setting->demo_mode))
		{
			// Password tidak memenuhi syarat kecuali di website demo
			redirect('user_setting/change_pwd');
		}

		$_SESSION['dari_login'] = '1';
		// Notif bisa dipanggil sewaktu-waktu dan tidak digunakan untuk redirect
		if (isset($_SESSION['request_uri']) and strpos($_SESSION['request_uri'], 'notif/') === false)
		{
			$request_awal = str_replace(parse_url(site_url(), PHP_URL_PATH), '', $_SESSION['request_uri']);
			unset($_SESSION['request_uri']);
			redirect($request_awal);
		}
		else
		{
			unset($_SESSION['request_uri']);
			redirect('main');
		}
	}

	public function login()
	{
		$this->user_model->login();
		$data['header'] = $this->config_model->get_data();
		$this->load->view('siteman', $data);
	}

	public function flash()
	{
		$this->load->view('config');
	}

}
