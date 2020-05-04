<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_umum extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->load->model('pamong_model');
		$this->load->model('web_dokumen_model');
		$this->load->model('surat_masuk_model');
		$this->load->model('surat_keluar_model');

		$this->modul_ini = 301;
	}

	public function index()
	{
		$this->peraturan();
	}

	// Menu Buku Peraturan di Desa #2773
	public function peraturan($page_number=1, $offset=0)
	{
		// load data for displaying at tables
		$this->load_data_tables("peraturan", $page_number, $offset);
	}

	// Menu Buku Keputusan Kepala Desa #2773
	public function keputusan($page_number=1, $offset=0)
	{
		// load data for displaying at tables
		$this->load_data_tables("keputusan", $page_number, $offset);
	}

	// Menu Buku Aparat Pemerintah Desa #2773
	public function aparat($page_number=1, $offset=0)
	{
		// load data for displaying at tables
		$this->load_data_tables("aparat", $page_number, $offset);
	}

	// Menu Buku Agenda #2773
	public function agenda($page_number=1, $offset=0)
	{
		// load data for displaying at tables
		$this->load_data_tables("agenda", $page_number, $offset);
	}

	// Menu Buku Ekspedisi #2774
	public function ekspedisi($page_number=1, $offset=0)
	{
		// load data for displaying at tables
		$this->load_data_tables("ekspedisi", $page_number, $offset);
	}

	// Menu Buku Lembaran Desa dan Berita Desa #2775
	public function berita($page_number=1, $offset=0)
	{
		// load data for displaying at tables
		$this->load_data_tables("berita", $page_number, $offset);
	}

	private function load_data_tables($page=null, $page_number=1, $offset=0)
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

		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'peraturan':
				$data = array_merge($data, $this->load_peraturan_data_tables($page_number, $offset));
				break;

			case 'keputusan':
				$data = array_merge($data, $this->load_keputusan_data_tables($page_number, $offset));
				break;

			case 'aparat':
				$data = array_merge($data, $this->load_aparat_data_tables($page_number, $offset));
				break;

			case 'agenda':
				$data = array_merge($data, $this->load_agenda_data_tables($page_number, $offset));
				break;

			case 'ekspedisi':
				$data = array_merge($data, $this->load_ekspedisi_data_tables($page_number, $offset));
				break;

			case 'berita':
				$data = array_merge($data, $this->load_berita_data_tables($page_number, $offset));
				break;

			default:
				$data = array_merge($data, $this->load_peraturan_data_tables($page_number, $offset));
				break;
		}

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('buku/umum/main', $data);
		$this->load->view('footer');
	}

	private function load_peraturan_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "buku/umum/content_dokumen_desa";
		$data['subtitle'] = "Buku Peraturan Desa";

		$data['kat'] = '3';
		$data['kat_nama'] = $this->web_dokumen_model->kat_nama('3');
		$data['paging'] = $this->web_dokumen_model->paging('3', $page_number, $offset);
		$data['main'] = $this->web_dokumen_model->list_data('3', $o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_dokumen_model->autocomplete();

		return $data;
	}

	private function load_keputusan_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "buku/umum/content_dokumen_desa";
		$data['subtitle'] = "Buku Keputusan Kepala Desa";

		$data['kat'] = '2';
		$data['kat_nama'] = $this->web_dokumen_model->kat_nama('2');
		$data['paging'] = $this->web_dokumen_model->paging('2', $page_number, $offset);
		$data['main'] = $this->web_dokumen_model->list_data('2', $o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_dokumen_model->autocomplete();

		return $data;
	}

	private function load_aparat_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "buku/umum/content_pemerintah_desa";
		$data['subtitle'] = "Buku Aparat Pemerintah Desa";

		$data['main'] = $this->pamong_model->list_data();
		$data['keyword'] = $this->pamong_model->autocomplete();

		return $data;
	}

	private function load_agenda_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "buku/umum/content_agenda_desa";
		$data['subtitle'] = "Buku Agenda";

		$data['p'] = $page_number;
		$data['o'] = $offset;

		// surat masuk
		$data['paging'] = $this->surat_masuk_model->paging($page_number, $offset);
		$data['main']['in'] = $this->surat_masuk_model->list_data($offset, $data['paging']->offset, $data['paging']->per_page);
		$data['tahun_penerimaan'] = $this->surat_masuk_model->list_tahun_penerimaan();
		$data['keyword']['in'] = $this->surat_masuk_model->autocomplete();

		// surat keluar
		$data['paging'] = $this->surat_keluar_model->paging($page_number, $offset);
		$data['main']['out'] = $this->surat_keluar_model->list_data($offset, $data['paging']->offset, $data['paging']->per_page);
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['keyword']['out'] = $this->surat_keluar_model->autocomplete();

		return $data;
	}

	private function load_ekspedisi_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "buku/umum/content_ekspedisi";
		$data['subtitle'] = "Buku Ekspedisi";

		return $data;
	}

	private function load_berita_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "buku/umum/content_berita";
		$data['subtitle'] = "Buku Lembaran Desa dan Berita Desa";

		return $data;
	}

}
