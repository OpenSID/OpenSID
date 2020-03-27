<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Inventaris_tanah extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('inventaris_tanah_model');
		$this->load->model('referensi_model');
		$this->load->model('config_model');
		$this->load->model('surat_model');
		$this->modul_ini = 15;
		$this->sub_modul_ini = 61;
		$this->tab_ini = 1;
		$this->tipe = 'inventaris_tanah';
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('inventaris_tanah');
	}

	public function index()
	{
		$data['main'] = $this->inventaris_tanah_model->list_inventaris();
		$data['total'] = $this->inventaris_tanah_model->sum_inventaris();
		$data['pamong'] = $this->surat_model->list_pamong();
		$data['tip'] = 1;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/tanah/table', $data);
		$this->load->view('footer');
	}

	public function view($id)
	{
		$data['main'] = $this->inventaris_tanah_model->view($id);
		$data['tip'] = 1;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/tanah/view_inventaris', $data);
		$this->load->view('footer');
	}

	public function view_mutasi($id)
	{
		$data['main'] = $this->inventaris_tanah_model->view_mutasi($id);
		$data['tip'] = 2;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/tanah/view_mutasi', $data);
		$this->load->view('footer');
	}

	public function edit($id)
	{
		$data['main'] = $this->inventaris_tanah_model->view($id);
		$data['aset'] = $this->inventaris_tanah_model->list_aset();
		$data['count_reg'] = $this->inventaris_tanah_model->count_reg();
		$data['get_kode'] = $this->config_model->get_data();
		$data['kd_reg'] = $this->inventaris_tanah_model->list_inventaris_kd_register();
		$data['tip'] = 1;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/tanah/edit_inventaris', $data);
		$this->load->view('footer');
	}

	public function edit_mutasi($id)
	{
		$data['main'] = $this->inventaris_tanah_model->edit_mutasi($id);
		$data['tip'] = 2;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/tanah/edit_mutasi', $data);
		$this->load->view('footer');
	}

	public function form()
	{
		$data['tip'] = 1;
		$header = $this->header_model->get_data();
		$data['main'] = $this->config_model->get_data();
		$data['aset'] = $this->inventaris_tanah_model->list_aset();
		$data['count_reg'] = $this->inventaris_tanah_model->count_reg();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/tanah/form_tambah', $data);
		$this->load->view('footer');
	}

	public function form_mutasi($id)
	{
		$data['main'] = $this->inventaris_tanah_model->view($id);
		$data['tip'] = 2;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/tanah/form_mutasi', $data);
		$this->load->view('footer');
	}

	public function mutasi()
	{
		$data['main'] = $this->inventaris_tanah_model->list_mutasi_inventaris();
		$data['tip'] = 2;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/tanah/table_mutasi', $data);
		$this->load->view('footer');
	}

	public function cetak($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['total'] = $this->inventaris_tanah_model->sum_print($tahun);
		$data['print'] = $this->inventaris_tanah_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_tanah_model->pamong($penandatangan);
		$this->load->view('inventaris/tanah/inventaris_print', $data);
	}

	public function download($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['total'] = $this->inventaris_tanah_model->sum_print($tahun);
		$data['print'] = $this->inventaris_tanah_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_tanah_model->pamong($penandatangan);
		$this->load->view('inventaris/tanah/inventaris_excel', $data);
	}

}