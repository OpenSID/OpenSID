<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_inventaris extends CI_Controller
{

	function __construct()
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
		$this->load->model('inventaris_laporan_model');
		$this->load->model('referensi_model');
		$this->load->model('config_model');
		$this->load->model('surat_model');
		$this->modul_ini = 16;
		$this->tab_ini = 7;
		$this->controller = 'inventaris_laporan';
	}

	function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('inventaris');
	}

	function index()
	{
		$data['main'] = $this->inventaris_laporan_model->list_inventaris();
		$data['total'] = $this->inventaris_laporan_model->sum_inventaris();
		$data['pamong'] = $this->surat_model->list_pamong();
		$header = $this->header_model->get_data();

		$data = array_merge($data, $this->inventaris_laporan_model->laporan_inventaris());

		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/table',$data);
		$this->load->view('footer');
	}

	function view($id)
	{
		$data['main'] = $this->inventaris_laporan_model->view($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/view_inventaris',$data);
		$this->load->view('footer');
	}

	function view_mutasi($id)
	{
		$data['main'] = $this->inventaris_laporan_model->view_mutasi($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/view_mutasi',$data);
		$this->load->view('footer');
	}

	function edit($id)
	{
		$data['main'] = $this->inventaris_laporan_model->view($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/edit_inventaris',$data);
		$this->load->view('footer');
	}

	function edit_mutasi($id)
	{
		$data['main'] = $this->inventaris_laporan_model->edit_mutasi($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/edit_mutasi',$data);
		$this->load->view('footer');
	}

	function form()
	{
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/form_tambah',$data);
		$this->load->view('footer');
	}

	function form_mutasi($id)
	{
		$data['main'] = $this->inventaris_laporan_model->view($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/form_mutasi',$data);
		$this->load->view('footer');
	}

	function mutasi()
	{
		$data['main'] = $this->inventaris_laporan_model->list_mutasi_inventaris();
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/table_mutasi',$data);
		$this->load->view('footer');
	}

	function cetak($tahun, $penandatangan)
	{
		$data['header'] = $this->header_model->get_config();
		$data['total'] = $this->inventaris_laporan_model->sum_print($tahun);
		$data['tahun'] = $tahun;
		$data['pamong'] = $this->inventaris_laporan_model->pamong($penandatangan);
		$data = array_merge($data, $this->inventaris_laporan_model->cetak_inventaris($tahun));
		$this->load->view('inventaris/laporan/inventaris_print',$data);
	}

	function download($tahun, $penandatangan)
	{
		$data['header'] = $this->header_model->get_config();
		$data['total'] = $this->inventaris_laporan_model->sum_print($tahun);
		$data['tahun'] = $tahun;
		$data['pamong'] = $this->inventaris_laporan_model->pamong($penandatangan);
		$data = array_merge($data, $this->inventaris_laporan_model->cetak_inventaris($tahun));
		$this->load->view('inventaris/laporan/inventaris_excel',$data);
	}

}
