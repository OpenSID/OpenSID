<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('config_model');
	}

	public function maintenance_mode()
	{
		if (isset($_SESSION['siteman']) AND $_SESSION['siteman'] == 1)
			redirect('main');

		$this->load->model('config_model');
		$this->load->model('pamong_model');
		$data['main'] = $this->config_model->get_data();
		$data['pamong_kades'] = $this->pamong_model->get_ttd();
		if (file_exists(FCPATH.'desa/offline_mode.php'))
			$this->load->view('../../desa/offline_mode', $data);
		else
			$this->load->view('offline_mode', $data);
	}

	public function index()
	{
		if (isset($_SESSION['siteman']) AND $_SESSION['siteman'] == 1)
		{
			$this->load->model('user_model');
			$grup = $this->user_model->sesi_grup($_SESSION['sesi']);
			switch ($grup)
			{
				case 1 : redirect('hom_sid'); break;
				case 2 : redirect('hom_sid'); break;
				case 3 : redirect('web'); break;
				case 4 : redirect('web'); break;
				default : redirect('siteman');
			}

		}
		else if ($this->setting->offline_mode > 0)
		{
			// Jika website hanya bisa diakses user, maka harus login dulu
			redirect('siteman');
		}
		else
		{
			redirect('first');
		}
	}
}
