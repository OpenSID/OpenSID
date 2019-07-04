<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('config_model');
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
				default :
				{
					if ($this->setting->offline_mode > 0)
					{
						redirect('siteman');
					}
					else
					{
						redirect('first');
					}
				}
			}

		// Jika offline_mode aktif, tidak perlu menampilkan halaman website
		}
		elseif ($this->setting->offline_mode == 2)
		{
			$data['main'] = $this->config_model->get_data();
			$this->load->view('offline_mode', $data);
		}
		elseif ($this->setting->offline_mode == 1)
		{
			// Jangan tampilkan website jika bukan admin/operator/redaksi
			$this->load->model('user_model');
			$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
			if ($grup != 1 AND $grup != 2 AND $grup != 3)
			{
				if (empty($grup))
					$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
				else
					unset($_SESSION['request_uri']);
				redirect('siteman');
			}
		}
	}
}
