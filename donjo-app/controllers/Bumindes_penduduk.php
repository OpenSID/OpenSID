<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_penduduk extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');

		$this->modul_ini = 301;
	}

	public function index()
	{
		$this->tables("induk");
	}

	public function tables($page="induk", $page_number=1, $offset=0)
	{
		$this->sub_modul_ini = 303;

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
		$this->load->view('bumindes/penduduk/main', $data);
		$this->load->view('footer');
	}

	private function load_data_tables($page=null, $page_number=1, $offset=0)
	{
		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'induk':
				$data = array_merge($data, $this->load_induk_data_tables($page_number, $offset));
				break;

			case 'mutasi':
				$data = array_merge($data, $this->load_mutasi_data_tables($page_number, $offset));
				break;

			case 'rekapitulasi':
				$data = array_merge($data, $this->load_rekapitulasi_data_tables($page_number, $offset));
				break;

			case 'sementara':
				$data = array_merge($data, $this->load_sementara_data_tables($page_number, $offset));
				break;

			case 'ktpkk':
				$data = array_merge($data, $this->load_ktpkk_data_tables($page_number, $offset));
				break;

			default:
				$data = array_merge($data, $this->load_induk_data_tables($page_number, $offset));
				break;
		}
		return $data;
	}

	private function load_induk_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/content_induk";
		$data['subtitle'] = "Buku Induk Penduduk";

		return $data;
	}

	private function load_mutasi_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/content_mutasi";
		$data['subtitle'] = "Buku Mutasi Penduduk";

		return $data;
	}

	private function load_rekapitulasi_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/content_rekapitulasi";
		$data['subtitle'] = "Buku Rekapitulasi Jumlah Penduduk";

		return $data;
	}

	private function load_sementara_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/content_sementara";
		$data['subtitle'] = "Buku Penduduk Sementara";

		return $data;
	}

	private function load_ktpkk_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/content_ktp_kk";
		$data['subtitle'] = "Buku KTP dan KK";

		return $data;
	}

}
