<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Penduduk_log extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3)
		{
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}


		$this->load->model('referensi_model');
		$this->load->model('penduduk_model');
		$this->load->model('penduduk_log_model');
		$this->load->model('header_model');
		$this->modul_ini = 2;
	}

	function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['status_dasar']);
		unset($_SESSION['sex']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['agama']);
		unset($_SESSION['umur_min']);
		unset($_SESSION['umur_max']);
		unset($_SESSION['status']);
		unset($_SESSION['status_penduduk']);
		$_SESSION['per_page'] = 200;
		redirect('penduduk_log');
	}

	function index($p=1,$o=0)
	{
		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['status_dasar']))
			$data['status_dasar'] = $_SESSION['status_dasar'];
		else $data['status_dasar'] = '';

		if(isset($_SESSION['sex']))
			$data['sex'] = $_SESSION['sex'];
		else $data['sex'] = '';

		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);

		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'],$data['rw']);

		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';

			}else $data['rw'] = '';

		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}

		if(isset($_SESSION['agama']))
			$data['agama'] = $_SESSION['agama'];
		else $data['agama'] = '';

		if(isset($_SESSION['status']))
			$data['status'] = $_SESSION['status'];
		else $data['status'] = '';

		if(isset($_SESSION['status_penduduk']))
			$data['status_penduduk'] = $_SESSION['status_penduduk'];
		else $data['status_penduduk'] = '';

		// Hanya tampilkan penduduk yang status dasarnya bukan 'HIDUP'
		$_SESSION['log'] = 1;

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->penduduk_log_model->paging($p,$o);
		$data['main']    = $this->penduduk_log_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->penduduk_model->autocomplete();
		$data['list_status_dasar'] = $this->referensi_model->list_data('tweb_status_dasar');
		$data['list_agama'] = $this->penduduk_model->list_agama();
		$data['list_dusun'] = $this->penduduk_model->list_dusun();

		$header = $this->header_model->get_data();

		$nav['act']= 2;
		$this->load->view('header', $header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/penduduk_log',$data);
		$this->load->view('footer');
	}

	function search()
	{
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('penduduk_log');
	}

	function status_dasar(){
		$status_dasar = $this->input->post('status_dasar');
		if($status_dasar!="")
			$_SESSION['status_dasar']=$status_dasar;
		else unset($_SESSION['status_dasar']);
		redirect('penduduk_log');
	}

	function sex()
	{
		$sex = $this->input->post('sex');
		if($sex!="")
			$_SESSION['sex']=$sex;
		else unset($_SESSION['sex']);
		redirect('penduduk_log');
	}

	function agama()
	{
		$agama = $this->input->post('agama');
		if($agama!="")
			$_SESSION['agama']=$agama;
		else unset($_SESSION['agama']);
		redirect('penduduk_log');
	}

	function dusun()
	{
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect('penduduk_log');
	}

	function rw()
	{
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect('penduduk_log');
	}

	function rt()
	{
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect('penduduk_log');
	}

	function edit($p=1,$o=0,$id=0)
	{
		$data['log_status_dasar'] = $this->penduduk_log_model->get_log($id);
		$data['form_action'] = site_url("penduduk_log/update/$p/$o/$id");
		$this->load->view('penduduk_log/ajax_edit',$data);
	}

	function update($p=1,$o=0,$id='')
	{
		$this->penduduk_log_model->update($id);
		redirect("penduduk_log/index/$p/$o");
	}

	function kembalikan_status($id_log)
	{
		unset($_SESSION['success']);
		$this->penduduk_log_model->kembalikan_status($id_log);
		redirect("penduduk_log");
	}

	function kembalikan_status_all()
	{
		$this->penduduk_log_model->kembalikan_status_all();
		redirect("penduduk_log");
	}

	function cetak($o=0)
	{
		$data['main'] = $this->penduduk_log_model->list_data($o,0, 10000);
		$this->load->view('penduduk_log/penduduk_log_print',$data);
	}

	function excel($o=0)
	{
		$data['main'] = $this->penduduk_log_model->list_data($o,0, 10000);
		$this->load->view('penduduk_log/penduduk_log_excel',$data);
	}

}
