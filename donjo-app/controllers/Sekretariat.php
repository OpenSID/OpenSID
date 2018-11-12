<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sekretariat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->grup = $this->user_model->sesi_grup($_SESSION['sesi']);
		if ($this->grup != (1 or 2 or 3))
		{
			if (empty($this->grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->modul_ini = 15;
		$this->controller = 'sekretariat';
	}

	public function index()
	{
		redirect('surat_masuk/clear');
	}

}
