<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_keuangan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('header_model');
		$this->modul_ini = 301;
		$this->sub_modul_ini = 304;
	}

	public function index($submenu = 'apbd')
	{
		$this->render('bumindes/keuangan/main', [
			'selected_nav' => $submenu,
			'main_content' => 'bumindes/keuangan/content_keuangan'
		]);
	}

}
