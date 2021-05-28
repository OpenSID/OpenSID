<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_lain extends Admin_Controller {
	private $list_session = ['tahun'];

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->load->model('pamong_model');
		$this->load->model('inventaris_laporan_model');
		$this->modul_ini = 301;
	}

	public function index()
	{
		$this->tables("inventaris");
	}

	public function tables($page="inventaris", $page_number=1, $offset=0)
	{
		$this->sub_modul_ini = 306;
		$tahun = (isset($this->session->tahun)) ? $this->session->tahun : date("Y") ;
		$data['subtitle'] = "Buku Inventaris dan Kekayaan Desa";
		// set session
		foreach ($this->list_session as $list) 
		{
			$data[$list] = $this->session->$list ?: '';
		}
		// set session END
 		$pamong = $this->pamong_model->list_data();
		$data['kades'] = $pamong;

		$data['sekdes'] = $pamong;

		$data['data'] = $this->inventaris_laporan_model->permen_47($tahun,null);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('bumindes/lain/main', $data);
		$this->load->view('footer');
	}

	private function load_data_tables($page=null, $page_number=1, $offset=0)
	{
		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'inventaris':
				$data = array_merge($data, $this->load_inventaris_data_tables($page_number, $offset));
				break;

			default:
				$data = array_merge($data, $this->load_inventaris_data_tables($page_number, $offset));
				break;
		}
		return $data;
	}

	private function load_inventaris_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/lain/content_inventaris";
		$data['subtitle'] = "Buku Inventaris dan Kekayaan Desa";

		return $data;
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
 		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('bumindes_lain/');
	}

}
