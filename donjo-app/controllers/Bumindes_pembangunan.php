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
		$this->tanah();
	}

	// Menu Buku Tanah di Desa #2836, Ikut menu apa?
	public function tanah($page_number=1, $offset=0)
	{
		// load data for displaying at tables
		$this->load_data_tables("tanah", $page_number, $offset);

	}

	// Menu Buku Tanah Kas Desa #2837, Ikut menu apa?
	public function tanah_kas($page_number=1, $offset=0)
	{
		// load data for displaying at tables
		$this->load_data_tables("tanah_kas", $page_number, $offset);
	}

	private function load_data_tables($page=null, $page_number=1, $offset=0)
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

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('buku/pembangunan/main', $data);
		$this->load->view('footer');
	}

	private function load_tanah_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "buku/pembangunan/content_tanah";
		$data['subtitle'] = "Buku Tanah di Desa";

		return $data;
	}

	private function load_tanah_kas_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "buku/pembangunan/content_tanah_kas";
		$data['subtitle'] = "Buku Tanah Kas Desa";

		return $data;
	}

}
