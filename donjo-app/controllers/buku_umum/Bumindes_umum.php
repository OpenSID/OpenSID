<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_umum extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');

		$this->modul_ini = 301;
	}

	public function index()
	{
		redirect('dokumen_sekretariat/peraturan_desa/3');
	}

	// TABLES
	public function tables($page="peraturan", $page_number=1, $offset=0)
	{
		$this->sub_modul_ini = 302;

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
		$this->load->view('bumindes/umum/main', $data);
		$this->load->view('footer');
	}

	private function load_data_tables($page, $page_number, $offset)
	{
		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'ekspedisi':
				$data = array_merge($data, $this->load_ekspedisi_data_tables($page_number, $offset));
				break;

			case 'berita':
				$data = array_merge($data, $this->load_berita_data_tables($page_number, $offset));
				break;

			default:
				$data = array_merge($data, $this->load_ekspedisi_data_tables($page_number, $offset));
				break;
		}
		return $data;
	}

	private function load_ekspedisi_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/umum/content_ekspedisi";
		$data['subtitle'] = "Buku Ekspedisi";

		return $data;
	}

	private function load_berita_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/umum/content_berita";
		$data['subtitle'] = "Buku Lembaran Desa dan Berita Desa";

		return $data;
	}
	// TABLES END

	// FORM
	public function form($page="peraturan", $page_number=1, $offset=0, $key=null)
	{
		$this->sub_modul_ini = 302;

		$data = array();
		$data = array_merge($data, $this->load_form($page, $page_number, $offset, $key));

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('bumindes/umum/main', $data);
		$this->load->view('footer');
	}

	private function load_form($page, $page_number, $offset, $key)
	{
		$data['p'] = $page_number;
		$data['o'] = $offset;

		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'ekspedisi':
				$data = array_merge($data, $this->load_form_ekspedisi($page_number, $offset, $key));
				break;

			case 'berita':
				$data = array_merge($data, $this->load_form_berita($page_number, $offset, $key));
				break;

			default:
				$data = array_merge($data, $this->load_form_peraturan($page_number, $offset, $key));
				break;
		}
		return $data;
	}

	function load_form_ekspedisi($page_number, $offset, $key)
	{

	}

	function load_form_berita($page_number, $offset, $key)
	{

	}

	// FORM END

	// INSERT
	public function insert($page)
	{
		switch (strtolower($page))
		{
			case 'ekspedisi':

				break;

			case 'berita':

				break;

			default:

				break;
		}
	}
	// INSERT END

	// DELETE
	public function delete($page, $p=1, $o=0, $id='')
	{
		switch (strtolower($page))
		{
			case 'ekspedisi':

				break;

			case 'berita':

				break;

			default:

				break;
		}
	}

	public function delete_all($page, $p=1, $o=0)
	{
		switch (strtolower($page))
		{
			case 'ekspedisi':

				break;

			case 'berita':

				break;

			default:

				break;
		}
	}

	// UPDATE
	public function update($page, $p=1, $o=0, $id='')
	{
		switch (strtolower($page))
		{
			case 'ekspedisi':

				break;

			case 'berita':

				break;

			default:

				break;
		}
	}
	// UPDATE END

}
