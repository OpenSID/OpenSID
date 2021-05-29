<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_rencana_pembangunan extends Admin_Controller {
	private $list_session = ['tahun'];

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->load->model('pamong_model');
		$this->modul_ini = 301;
	}

	public function index()
	{
		$this->sub_modul_ini = 305;
		$tahun = (isset($this->session->tahun)) ? $this->session->tahun : date("Y") ;
		$data['subtitle'] = "Buku Rencana Kerja Pembangunan";
 		$pamong = $this->pamong_model->list_data();

		$this->render('bumindes/pembangunan/main', [
			'subtitle' => 'Buku Rencana Kerja Pembangunan',
			'selected_nav' => 'rencana',
			'main_content' => 'bumindes/pembangunan/content_rencana'
		]);


	}

}
