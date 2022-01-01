<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_rencana_pembangunan extends Admin_Controller {
	private $list_session = ['tahun'];

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->modul_ini = 301;
		$this->sub_modul_ini = 305;
	}

	public function index($submenu = 'rencana')
	{
		$this->render('bumindes/pembangunan/main', [
			'selected_nav' => $submenu,
			'main_content' => 'bumindes/pembangunan/content_rencana'
		]);


	}

}
