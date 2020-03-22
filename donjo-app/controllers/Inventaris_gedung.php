<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Inventaris_gedung extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('inventaris_gedung_model');
		$this->load->model('referensi_model');
		$this->load->model('config_model');
		$this->load->model('surat_model');
		$this->modul_ini = 15;
		$this->tab_ini = 3;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('inventaris');
	}

	public function index()
	{
		$data['main'] = $this->inventaris_gedung_model->list_inventaris();
		$data['total'] = $this->inventaris_gedung_model->sum_inventaris();
		$data['pamong'] = $this->surat_model->list_pamong();
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/gedung/table', $data, TRUE);
	}

	public function view($id)
	{
		$data['main'] = $this->inventaris_gedung_model->view($id);
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/gedung/view_inventaris', $data, TRUE);
	}

	public function view_mutasi($id)
	{
		$data['main'] = $this->inventaris_gedung_model->view_mutasi($id);
		$data['tip'] = 2;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/gedung/view_mutasi', $data, TRUE);
	}

	public function edit($id)
	{
		$data['main'] = $this->inventaris_gedung_model->view($id);
		$data['aset'] = $this->inventaris_gedung_model->list_aset();
		$data['count_reg'] = $this->inventaris_gedung_model->count_reg();
		$data['get_kode'] = $this->config_model->get_data();
		$data['kd_reg'] = $this->inventaris_gedung_model->list_inventaris_kd_register();
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/gedung/edit_inventaris', $data, TRUE);
	}

	public function edit_mutasi($id)
	{
		$data['main'] = $this->inventaris_gedung_model->edit_mutasi($id);
		$data['tip'] = 2;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/gedung/edit_mutasi', $data, TRUE);
	}

	public function form()
	{
		$data['tip'] = 1;
		$data['main'] = $this->config_model->get_data();
		$data['aset'] = $this->inventaris_gedung_model->list_aset();
		$data['count_reg'] = $this->inventaris_gedung_model->count_reg();;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/gedung/form_tambah', $data, TRUE);
	}

	public function form_mutasi($id)
	{
		$data['main'] = $this->inventaris_gedung_model->view($id);
		$data['tip'] = 2;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/gedung/form_mutasi', $data, TRUE);
	}

	public function mutasi()
	{
		$data['main'] = $this->inventaris_gedung_model->list_mutasi_inventaris();
		$data['tip'] = 2;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/gedung/table_mutasi', $data, TRUE);
	}

	public function cetak($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['total'] = $this->inventaris_gedung_model->sum_print($tahun);
		$data['print'] = $this->inventaris_gedung_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_gedung_model->pamong($penandatangan);
		$this->load->view('inventaris/gedung/inventaris_print', $data);
	}

	public function download($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['total'] = $this->inventaris_gedung_model->sum_print($tahun);
		$data['print'] = $this->inventaris_gedung_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_gedung_model->pamong($penandatangan);
		$this->load->view('inventaris/gedung/inventaris_excel', $data);
	}

}