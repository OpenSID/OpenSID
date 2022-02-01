<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bumindes_inventaris_kekayaan extends Admin_Controller {

	private $list_session = ['tahun'];

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['pamong_model', 'inventaris_laporan_model']);
		$this->modul_ini = 301;
		$this->sub_modul_ini = 302;
		$this->set_minsidebar(1);
	}

	public function index()
	{
		$tahun = $this->session->tahun ?: date("Y");
 		$pamong = $this->pamong_model->list_data();

 		$data = [
			'subtitle' => 'Buku Inventaris dan Kekayaan Desa',
			'selected_nav' => 'inventaris',
			'main_content' => 'bumindes/umum/content_inventaris',
			'min_tahun' => $this->inventaris_laporan_model->min_tahun(),
			'data' => $this->inventaris_laporan_model->permen_47($tahun, null),
			'kades' => $data['sekdes'] = $pamong,
			'sekdes' => $data['sekdes'] = $pamong,
			'tahun' => $tahun
		];

		$this->render('bumindes/umum/main', $data);
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != "")
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('bumindes_inventaris_kekayaan');
	}

}
