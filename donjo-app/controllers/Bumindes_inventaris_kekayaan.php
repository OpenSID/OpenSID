<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_inventaris_kekayaan extends Admin_Controller {
	private $list_session = ['tahun'];

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->load->model('pamong_model');
		$this->load->model('inventaris_laporan_model');
		$this->modul_ini = 302;
		$this->set_minsidebar(1);
	}

	public function index()
	{
		$this->sub_modul_ini = 322;
		$tahun = (isset($this->session->tahun)) ? $this->session->tahun : date("Y") ;
		$data['subtitle'] = "Buku Inventaris dan Kekayaan Desa";
		// set session
		foreach ($this->list_session as $list) 
		{
			$data[$list] = $this->session->$list ?: '';
		}
		// set session END
 		$pamong = $this->pamong_model->list_data();

		$this->render('bumindes/umum/main', [
			'subtitle' => 'Buku Inventaris dan Kekayaan Desa',
			'selected_nav' => 'inventaris',
			'main_content' => 'bumindes/umum/content_inventaris',
			'data' => $this->inventaris_laporan_model->permen_47($tahun,null),
			'kades' => $data['sekdes'] = $pamong,
			'sekdes' => $data['sekdes'] = $pamong
		]);


	}

}
