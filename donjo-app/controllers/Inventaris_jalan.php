<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class inventaris_jalan extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if ($grup!=1 AND $grup!=2)
		{
			$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('inventaris_jalan_model');
		$this->load->model('referensi_model');
		$this->load->model('config_model');
		$this->load->model('surat_model');
		$this->modul_ini = 16;
		$this->tab_ini = 4;
		$this->controller = 'inventaris_jalan';
	}

	function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('inventaris');
	}

	function index()
	{
		$data['main'] = $this->inventaris_jalan_model->list_inventaris();
		$data['total'] = $this->inventaris_jalan_model->sum_inventaris();
		$data['pamong'] = $this->surat_model->list_pamong();
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/jalan/table',$data);
		$this->load->view('footer');
	}

	function view($id)
	{
		$data['main'] = $this->inventaris_jalan_model->view($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/jalan/view_inventaris',$data);
		$this->load->view('footer');
	}

	function view_mutasi($id)
	{
		$data['main'] = $this->inventaris_jalan_model->view_mutasi($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/jalan/view_mutasi',$data);
		$this->load->view('footer');
	}

	function edit($id)
	{
		$data['main'] = $this->inventaris_jalan_model->view($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/jalan/edit_inventaris',$data);
		$this->load->view('footer');
	}

	function edit_mutasi($id)
	{
		$data['main'] = $this->inventaris_jalan_model->edit_mutasi($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/jalan/edit_mutasi',$data);
		$this->load->view('footer');
	}
	function form()
	{
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/jalan/form_tambah',$data);
		$this->load->view('footer');
	}

	function form_mutasi($id)
	{
		$data['main'] = $this->inventaris_jalan_model->view($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/jalan/form_mutasi',$data);
		$this->load->view('footer');
	}

	function mutasi()
	{
		$data['main'] = $this->inventaris_jalan_model->list_mutasi_inventaris();
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/jalan/table_mutasi',$data);
		$this->load->view('footer');
	}

	function cetak($tahun, $penandatangan)
	{
		$data['header'] = $this->header_model->get_config();
		$data['total'] = $this->inventaris_jalan_model->sum_print($tahun);
		$data['print'] = $this->inventaris_jalan_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_jalan_model->pamong($penandatangan);
		$this->load->view('inventaris/jalan/inventaris_print',$data);
	}

	function download($tahun, $penandatangan)
	{
		$data['header'] = $this->header_model->get_config();
		$data['total'] = $this->inventaris_jalan_model->sum_print($tahun);
		$data['print'] = $this->inventaris_jalan_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_jalan_model->pamong($penandatangan);
		$this->load->view('inventaris/jalan/inventaris_excel',$data);
	}
}