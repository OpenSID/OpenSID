<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Inventaris_peralatan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('inventaris_peralatan_model');
		$this->load->model('referensi_model');
		$this->load->model('config_model');
		$this->load->model('surat_model');
		$this->modul_ini = 15;
		$this->tab_ini = 2;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('Inventaris_peralatan');
	}

	public function index()
	{
		$data['main'] = $this->inventaris_peralatan_model->list_inventaris();
		$data['total'] = $this->inventaris_peralatan_model->sum_inventaris();
		$data['pamong'] = $this->surat_model->list_pamong();
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/peralatan/table', $data, $nav, TRUE);
	}

	public function view($id)
	{
		$data['main'] = $this->inventaris_peralatan_model->view($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/peralatan/view_inventaris', $data, $nav, TRUE);
	}

	public function view_mutasi($id)
	{
		$data['main'] = $this->inventaris_peralatan_model->view_mutasi($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 2;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/peralatan/view_mutasi', $data, $nav, TRUE);
	}

	public function edit($id)
	{
		$data['main'] = $this->inventaris_peralatan_model->view($id);
		$data['get_kode'] = $this->config_model->get_data();
		$data['aset'] = $this->inventaris_peralatan_model->list_aset();
		$data['count_reg'] = $this->inventaris_peralatan_model->count_reg();
		$data['kd_reg'] = $this->inventaris_peralatan_model->list_inventaris_kd_register();
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/peralatan/edit_inventaris', $data, $nav, TRUE);
	}

	public function edit_mutasi($id)
	{
		$data['main'] = $this->inventaris_peralatan_model->edit_mutasi($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 2;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/peralatan/edit_mutasi', $data, $nav, TRUE);
	}

	public function form()
	{
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		$data['main'] = $this->config_model->get_data();
		$data['aset'] = $this->inventaris_peralatan_model->list_aset();
		$data['count_reg'] = $this->inventaris_peralatan_model->count_reg();
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/peralatan/form_tambah', $data, $nav, TRUE);
	}

	public function form_mutasi($id)
	{
		$data['main'] = $this->inventaris_peralatan_model->view($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/peralatan/form_mutasi', $data, $nav, TRUE);
	}

	public function mutasi()
	{
		$data['main'] = $this->inventaris_peralatan_model->list_mutasi_inventaris();
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 2;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/peralatan/table_mutasi', $data, $nav, TRUE);
	}

	public function cetak($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['total'] = $this->inventaris_peralatan_model->sum_print($tahun);
		$data['print'] = $this->inventaris_peralatan_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_peralatan_model->pamong($penandatangan);
		$this->load->view('inventaris/peralatan/inventaris_print', $data);
	}

	public function download($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['total'] = $this->inventaris_peralatan_model->sum_print($tahun);
		$data['print'] = $this->inventaris_peralatan_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_peralatan_model->pamong($penandatangan);
		$this->load->view('inventaris/peralatan/inventaris_excel', $data);
	}

}
