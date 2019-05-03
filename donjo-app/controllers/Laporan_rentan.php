<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_rentan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('laporan_bulanan_model');

		//Initialize Session ------------
		$_SESSION['success'] = 0;
		$_SESSION['per_page'] = 20;
		$_SESSION['cari'] = '';
		//-------------------------------

		$this->modul_ini = 3;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		redirect('laporan_rentan');
	}

	public function index()
	{
		if (isset($_SESSION['dusun']))
			$data['dusun'] = $_SESSION['dusun'];
		else $data['dusun'] = '';

		$data['list_dusun'] = $this->laporan_bulanan_model->list_dusun();
		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['main'] = $this->laporan_bulanan_model->list_data();

		$nav['act'] = 3;
		$nav['act_sub'] = 29;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('laporan/kelompok', $data);
		$this->load->view('footer');
	}

	public function cetak()
	{
		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['main'] = $this->laporan_bulanan_model->list_data();
		$this->load->view('laporan/kelompok_print', $data);
	}

	public function excel()
	{
		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['main'] = $this->laporan_bulanan_model->list_data();
		$this->load->view('laporan/kelompok_excel', $data);
	}

	public function dusun()
	{
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect('laporan_rentan');
	}
}
