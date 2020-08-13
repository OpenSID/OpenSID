<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Ekspedisi extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		// Untuk bisa menggunakan helper force_download()
		$this->load->helper('download');
		$this->load->model('surat_keluar_model');
		$this->load->model('ekspedisi_model');
		$this->load->model('klasifikasi_model');
		$this->load->model('config_model');
		$this->load->model('pamong_model');
		$this->load->model('header_model');
		$this->load->model('penomoran_surat_model');
		$this->modul_ini = 301;
		$this->sub_modul_ini = 302;
	}

	public function clear()
	{
		$this->session->per_page = 20;
		unset($this->session->cari);
		unset($this->session->filter);
		redirect('ekspedisi');
	}

	public function index($p = 1, $o = 2)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$data['per_page'] = $_SESSION['per_page'];
		$data['paging'] = $this->surat_keluar_model->paging($p, $o);
		$data['main'] = $this->ekspedisi_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['keyword'] = $this->surat_keluar_model->autocomplete();
		$header = $this->header_model->get_data();
		$data['main_content'] = 'ekspedisi/table';
		$data['subtitle'] = "Buku Ekspedisi";
		$data['selected_nav'] = 'ekspedisi';
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('bumindes/umum/main', $data);
		$this->load->view('footer');
	}

	public function form($p = 1, $o = 0, $id)
	{
		$data['klasifikasi'] = $this->klasifikasi_model->list_kode();
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['surat_keluar'] = $this->surat_keluar_model->get_surat_keluar($id);
			$data['form_action'] = site_url("ekspedisi/update/$p/$o/$id");
		}
		$header = $this->header_model->get_data();

		// Buang unique id pada link nama file
		$berkas = explode('__sid__', $data['surat_keluar']['tanda_terima']);
		$namaFile = $berkas[0];
		$ekstensiFile = explode('.', end($berkas));
		$ekstensiFile = end($ekstensiFile);
		$data['surat_keluar']['tanda_terima'] = $namaFile.'.'.$ekstensiFile;
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('ekspedisi/form', $data);
		$this->load->view('footer');
	}

	public function form_upload($p = 1, $o = 0, $url = '')
	{
		$data['form_action'] = site_url("surat_keluar/upload/$p/$o/$url");
		$this->load->view('surat_keluar/ajax-upload', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('surat_keluar');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0) $_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('surat_keluar');
	}

	public function update($p = 1, $o = 0, $id)
	{
		$this->ekspedisi_model->update($id);
		redirect("ekspedisi/index/$p/$o");
	}

	public function upload($p = 1, $o = 0, $url = '')
	{
		$this->surat_keluar_model->upload($url);
		redirect("surat_keluar/index/$p/$o");
	}

	public function dialog_cetak($o = 0)
	{
		$data['aksi'] = "Cetak";
		$data['pamong'] = $this->pamong_model->list_data(true);
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['form_action'] = site_url("surat_keluar/cetak/$o");
		$this->load->view('surat_keluar/ajax_cetak', $data);
	}

	public function dialog_unduh($o = 0)
	{
		$data['aksi'] = "Unduh";
		$data['pamong'] = $this->pamong_model->list_data(true);
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['form_action'] = site_url("surat_keluar/unduh/$o");
		$this->load->view('surat_keluar/ajax_cetak', $data);
	}

	public function cetak($o = 0)
	{
		$data['input'] = $_POST;
		$_SESSION['filter'] = $data['input']['tahun'];
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
		$data['desa'] = $this->config_model->get_data();
		$data['main'] = $this->surat_keluar_model->list_data($o, 0, 10000);
		$this->load->view('surat_keluar/surat_keluar_print', $data);
	}

	public function unduh($o = 0)
	{
		$data['input'] = $_POST;
		$_SESSION['filter'] = $data['input']['tahun'];
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
		$data['desa'] = $this->config_model->get_data();
		$data['main'] = $this->surat_keluar_model->list_data($o, 0, 10000);
		$this->load->view('surat_keluar/surat_keluar_excel', $data);
	}

	public function bukan_ekspedisi($p = 1, $o = 0, $id)
	{
		$this->surat_keluar_model->untuk_ekspedisi($id, $masuk = 0);
		redirect("ekspedisi/index/$p/$o");
	}

}
