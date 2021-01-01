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
		$this->modul_ini = 301;
		$this->sub_modul_ini = 302;
	}

	public function clear()
	{
		$this->session->per_page = 20;
		$this->session->cari = NULL;
		$this->session->filter = NULL;
		redirect('ekspedisi');
	}

	public function index($p = 1, $o = 2)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$data['cari'] = $this->session->cari ?: '';
		$data['filter'] = $this->session->filter ?: '';
		$this->session->per_page = $this->input->post('per_page') ?: NULL;

		$data['per_page'] = $this->session->per_page;
		$data['paging'] = $this->ekspedisi_model->paging($p, $o);
		$data['main'] = $this->ekspedisi_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['tahun_surat'] = $this->ekspedisi_model->list_tahun_surat();
		$data['keyword'] = $this->ekspedisi_model->autocomplete();
		$data['main_content'] = 'ekspedisi/table';
		$data['subtitle'] = "Buku Ekspedisi";
		$data['selected_nav'] = 'ekspedisi';
		$this->set_minsidebar(1);

		$this->load->view('header', $this->header);
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

		// Buang unique id pada link nama file
		$berkas = explode('__sid__', $data['surat_keluar']['tanda_terima']);
		$namaFile = $berkas[0];
		$ekstensiFile = explode('.', end($berkas));
		$ekstensiFile = end($ekstensiFile);
		$data['surat_keluar']['tanda_terima'] = $namaFile.'.'.$ekstensiFile;
		$this->set_minsidebar(1);

		$this->load->view('header', $this->header);
		$this->load->view('nav', $nav);
		$this->load->view('ekspedisi/form', $data);
		$this->load->view('footer');
	}

	public function search()
	{
		$this->session->cari = $this->input->post('cari') ?: NULL;
		redirect('ekspedisi');
	}

	public function filter()
	{
		$this->session->filter = $this->input->post('filter') ?: NULL;
		redirect('ekspedisi');
	}

	public function update($p = 1, $o = 0, $id)
	{
		$this->ekspedisi_model->update($id);
		redirect("ekspedisi/index/$p/$o");
	}

	public function dialog($aksi = "cetak", $o = 0)
	{
		$data['aksi'] = $aksi;
		$data['pamong'] = $this->pamong_model->list_data();
		$data['tahun_surat'] = $this->ekspedisi_model->list_tahun_surat();
		$data['form_action'] = site_url("ekspedisi/daftar/$aksi/$o");
		$this->load->view('ekspedisi/ajax_cetak', $data);
	}

	public function daftar($aksi = "cetak", $o = 1)
	{
		$data['input'] = $_POST;
		$_SESSION['filter'] = $data['input']['tahun'];
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
		$data['desa'] = $this->config_model->get_data();
		$data['main'] = $this->ekspedisi_model->list_data($o, 0, 10000);
		$this->load->view("ekspedisi/ekspedisi_$aksi", $data);
	}

	/**
	 * Unduh berkas tanda terima berdasarkan kolom surat_keluar.id
	 * @param   integer  $id  ID surat_keluar
	 * @return  void
	 */
	public function unduh_tanda_terima($id)
	{
		// Ambil nama berkas dari database
		$berkas = $this->ekspedisi_model->get_tanda_terima($id);
		ambilBerkas($berkas, 'surat_keluar', '__sid__');
	}

	public function bukan_ekspedisi($p = 1, $o = 0, $id)
	{
		$this->surat_keluar_model->untuk_ekspedisi($id, $masuk = 0);
		redirect("ekspedisi/index/$p/$o");
	}

}
