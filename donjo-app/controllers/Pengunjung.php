<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengunjung extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('web_pengunjung_model');
		$this->load->model('config_model');
		$this->modul_ini = 13;
		$this->sub_modul_ini = 205;
	}

	public function index()
	{		
		$data['hari_ini'] = $this->web_pengunjung_model->get_count('1');
		$data['kemarin'] = $this->web_pengunjung_model->get_count('2');
		$data['minggu_ini'] = $this->web_pengunjung_model->get_count('3');
		$data['bulan_ini'] = $this->web_pengunjung_model->get_count('4');
		$data['tahun_ini'] = $this->web_pengunjung_model->get_count('5');
		$data['jumlah'] = $this->web_pengunjung_model->get_count('');
		$data['main'] = $this->web_pengunjung_model->get_pengunjung($_SESSION['id']);		
		$header = $this->header_model->get_data();

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
		redirect('pengunjung');
	}

	public function cetak()
	{
		$data['config'] = $this->config_model->get_data();
		$data['main'] = $this->web_pengunjung_model->get_pengunjung(($_SESSION['id']));
		$this->load->view('pengunjung/print', $data);
	}
	
	public function unduh()
	{		
		$data['aksi'] = 'unduh';
		$data['config'] = $this->config_model->get_data();
		$data['filename'] = underscore('Laporan Data Statistik Pengunjung Website');
		$data['main'] = $this->web_pengunjung_model->get_pengunjung(($_SESSION['id']));
		$this->load->view('pengunjung/excel', $data);
	}
}
