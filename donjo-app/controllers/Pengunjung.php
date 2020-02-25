<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengunjung extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('web_pengunjung_model');
		$this->modul_ini = 13;
	}

	public function index(//bagus
	{		
		$data['hari_ini'] = $this->web_pengunjung_model->get_count('1');
		$data['kemarin'] = $this->web_pengunjung_model->get_count('2');
		$data['minggu_ini'] = $this->web_pengunjung_model->get_count('3');
		$data['bulan_ini'] = $this->web_pengunjung_model->get_count('4');
		$data['tahun_ini'] = $this->web_pengunjung_model->get_count('5');
		$data['jumlah'] = $this->web_pengunjung_model->get_count('');
		
		$data['main'] = $this->web_pengunjung_model->get_pengunjung($_SESSION['id']);		
		
		$header = $this->header_model->get_data();
		$nav['act'] = 13;
		$nav['act_sub'] = 205;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('pengunjung/table', $data);
		$this->load->view('footer');
	}
	
	public function detail($id='')
	{
		$_SESSION['id'] = $id;
		
		redirect('pengunjung');
	}
	
	public function clear()
	{
		unset($_SESSION['id']);
		unset($_SESSION['filter']);
		redirect('pengunjung');
	}

	public function cetak()
	{
		$data['config'] = ambil_config();
		$data['main'] = $this->web_pengunjung_model->get_pengunjung(($_SESSION['id']));
		$this->load->view('pengunjung/print', $data);
	}
	
	public function unduh()
	{		
		$data['aksi'] = 'unduh';
		$data['config'] = ambil_config();
		$data['filename'] = underscore('Laporan Data Statistik Pengunjung Website');
		$data['main'] = $this->web_pengunjung_model->get_pengunjung(($_SESSION['id']));
		$this->load->view('pengunjung/excel', $data);
	}
}
