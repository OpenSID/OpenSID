<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_pembangunan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');

		$this->modul_ini = 301;
	}

	public function index()
	{
		$this->tables("tanah");
	}

	public function tables($page="tanah", $page_number=1, $offset=0)
	{
		$this->sub_modul_ini = 305;

		// set session
		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		// set session END

		// load data for displaying at tables
		$data = array_merge($data, $this->load_data_tables($page, $page_number, $offset));

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('bumindes/pembangunan/main', $data);
		$this->load->view('footer');
	}

	private function load_data_tables($page=null, $page_number=1, $offset=0)
	{
		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'tanah':
				$data = array_merge($data, $this->load_tanah_data_tables($page_number, $offset));
				break;

			case 'tanah_kas':
				$data = array_merge($data, $this->load_tanah_kas_data_tables($page_number, $offset));
				break;

			default:
				$data = array_merge($data, $this->load_tanah_data_tables($page_number, $offset));
				break;
		}
		return $data;
	}

	private function load_tanah_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/pembangunan/content_tanah";
		$data['subtitle'] = "Buku Tanah di Desa";

		return $data;
	}

	private function load_tanah_kas_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/pembangunan/content_tanah_kas";
		$data['subtitle'] = "Buku Tanah Kas Desa";

		return $data;
	}

}
