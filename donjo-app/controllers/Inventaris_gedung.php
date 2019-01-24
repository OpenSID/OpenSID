<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Inventaris_gedung extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if ($grup != 1 AND $grup != 2)
		{
			$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('inventaris_gedung_model');
		$this->load->model('referensi_model');
		$this->load->model('config_model');
		$this->load->model('surat_model');
		$this->modul_ini = 15;
		$this->tab_ini = 3;
		$this->controller = 'inventaris_gedung';
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
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/gedung/table', $data);
		$this->load->view('footer');
	}

	public function view($id)
	{
		$data['main'] = $this->inventaris_gedung_model->view($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/gedung/view_inventaris', $data);
		$this->load->view('footer');
	}

	public function view_mutasi($id)
	{
		$data['main'] = $this->inventaris_gedung_model->view_mutasi($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 2;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/gedung/view_mutasi', $data);
		$this->load->view('footer');
	}

	public function edit($id)
	{
		$data['main'] = $this->inventaris_gedung_model->view($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/gedung/edit_inventaris', $data);
		$this->load->view('footer');
	}

	public function edit_mutasi($id)
	{
		$data['main'] = $this->inventaris_gedung_model->edit_mutasi($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 2;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/gedung/edit_mutasi', $data);
		$this->load->view('footer');
	}

	public function form()
	{
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/gedung/form_tambah', $data);
		$this->load->view('footer');
	}

	public function form_mutasi($id)
	{
		$data['main'] = $this->inventaris_gedung_model->view($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 2;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/gedung/form_mutasi', $data);
		$this->load->view('footer');
	}

	public function mutasi()
	{
		$data['main'] = $this->inventaris_gedung_model->list_mutasi_inventaris();

		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 2;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('inventaris/gedung/table_mutasi', $data);
		$this->load->view('footer');
	}

	public function cetak($tahun, $penandatangan)
	{
		$data['header'] = $this->header_model->get_config();
		$data['total'] = $this->inventaris_gedung_model->sum_print($tahun);
		$data['print'] = $this->inventaris_gedung_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_gedung_model->pamong($penandatangan);
		$this->load->view('inventaris/gedung/inventaris_print', $data);
	}

	public function download($tahun, $penandatangan)
	{
		$data['header'] = $this->header_model->get_config();
		$data['total'] = $this->inventaris_gedung_model->sum_print($tahun);
		$data['print'] = $this->inventaris_gedung_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_gedung_model->pamong($penandatangan);
		$this->load->view('inventaris/gedung/inventaris_excel', $data);
	}

}
