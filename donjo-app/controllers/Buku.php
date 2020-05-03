<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Buku extends Admin_Controller {

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
		$this->umum();
	}

	public function umum($page=null, $page_number=1, $offset=0)
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
		switch ($page) 
		{
			case 'peraturan':
				$data['main_content'] = "buku/umum/content_dokumen_desa";
				$data['subtitle'] = "Buku Peraturan Desa";

				$data['kat'] = '3';
				$data['kat_nama'] = $this->web_dokumen_model->kat_nama('3');
				$data['paging'] = $this->web_dokumen_model->paging('3', $page_number, $offset);
				$data['main'] = $this->web_dokumen_model->list_data('3', $o, $data['paging']->offset, $data['paging']->per_page);
				$data['keyword'] = $this->web_dokumen_model->autocomplete();
				break;

			case 'keputusan':
				$data['main_content'] = "buku/umum/content_dokumen_desa";
				$data['subtitle'] = "Buku Keputusan Kepala Desa";

				$data['kat'] = '2';
				$data['kat_nama'] = $this->web_dokumen_model->kat_nama('2');
				$data['paging'] = $this->web_dokumen_model->paging('2', $page_number, $offset);
				$data['main'] = $this->web_dokumen_model->list_data('2', $o, $data['paging']->offset, $data['paging']->per_page);
				$data['keyword'] = $this->web_dokumen_model->autocomplete();
				break;

			case 'aparat':
				$data['main_content'] = "buku/umum/content_pemerintah_desa";
				$data['subtitle'] = "Buku Aparat Pemerintah Desa";

				$data['main'] = $this->pamong_model->list_data();
				$data['keyword'] = $this->pamong_model->autocomplete();
				break;
			
			case 'agenda':
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
				break;
			
			default:
				$data['main_content'] = "buku/umum/content_pemerintah_desa";
				$data['selected_nav'] = "peraturan";
				$data['subtitle'] = "Buku Peraturan Desa";
				break;
		}	

		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('buku/umum/main', $data);
		$this->load->view('footer');
	}

}
