<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_keuangan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');

		$this->modul_ini = 301;
	}

	public function index()
	{
		$this->siskeudes();
	}

	// Menu Buku Peraturan di Desa #2773
	public function siskeudes($page_number=1, $offset=0)
	{
		// load data for displaying at tables
		$this->load_data_tables("siskeudes", $page_number, $offset);

	}

	private function load_data_tables($page=null, $page_number=1, $offset=0)
	{
		$this->sub_modul_ini = 304;

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
			case 'siskeudes':
				$data = array_merge($data, $this->load_siskeudes_data_tables($page_number, $offset));
				break;

			default:
				$data = array_merge($data, $this->load_siskeudes_data_tables($page_number, $offset));
				break;
		}

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('buku/keuangan/main', $data);
		$this->load->view('footer');
	}

	private function load_siskeudes_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "buku/keuangan/content_siskeudes";
		$data['subtitle'] = "Buku Keuangan Desa";

		return $data;
	}

}
