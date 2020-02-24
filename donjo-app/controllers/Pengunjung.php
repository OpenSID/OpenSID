<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengunjung extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('web_pengunjung_model');
		$this->load->model('pamong_model');
		$this->modul_ini = 13;
	}

	public function index()//bagus
	{
		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
		
		//cat: -0 days(hari ini), -1 days(kemarin), -7 days(minggu ini)
		
		$data['main'] = $this->web_pengunjung_model->get_pengunjung();		
		$data['hari_ini'] = $this->web_pengunjung_model->get_count('-0 days');
		$data['kemarin'] = $this->web_pengunjung_model->get_count('-1 days');
		$data['minggu_ini'] = $this->web_pengunjung_model->get_count('-7 days');
		$data['bulan_ini'] = $this->web_pengunjung_model->get_count('2');
		$data['tahun_ini'] = $this->web_pengunjung_model->get_count('3');
		$data['jumlah'] = $this->web_pengunjung_model->get_count('1');
	
		$header = $this->header_model->get_data();
		$nav['act'] = 13;
		$nav['act_sub'] = 205;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('pengunjung/table', $data);
		$this->load->view('footer');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('pengunjung');
	}
	
	public function clear()
	{
		unset($_SESSION['filter']);
		redirect('pengunjung');
	}

	public function cetak()
	{
		$data['main'] = $this->web_pengunjung_model->get_pengunjung($filter);
		$this->load->view('pengunjung/print', $data);
	}
	
	public function unduh()
	{		
		$data['aksi'] = 'unduh';
		$data['filename'] = underscore('Laporan Data Statistik Pengunjung Website '.ucwords($_SESSION['judul']));
		$data['main'] = $this->web_pengunjung_model->get_pengunjung($filter);
		$this->load->view('pengunjung/excel', $data);
	}
}
