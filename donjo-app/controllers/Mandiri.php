<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mandiri extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('mandiri_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->modul_ini = 14;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('mandiri');
	}

	function index($p=1,$o=0){

		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->mandiri_model->paging($p,$o);
		$data['main']    = $this->mandiri_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->mandiri_model->autocomplete();

		//$data['penduduk'] = $this->mandiri_model->list_penduduk();
		//$data['form_action'] = site_url("mandiri/insert/");
		$header = $this->header_model->get_data();

		$nav['act']= 1;
		$this->load->view('header', $header);

		$this->load->view('lapor/nav',$nav);
		$this->load->view('mandiri/mandiri',$data);
		$this->load->view('footer');
	}

	function ajax_pin($p=1,$o=0,$id=0){

		$data['penduduk'] = $this->mandiri_model->list_penduduk();
		$data['form_action'] = site_url("mandiri/insert/$id");
		$this->load->view('mandiri/ajax_pin',$data);

	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('mandiri');
	}

	function filter(){
		$filter = $this->input->post('nik');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('mandiri/perorangan');
	}

	function nik(){
		$nik = $this->input->post('nik');
		if($nik!=0)
			$_SESSION['nik']=$_POST['nik'];
		else unset($_SESSION['nik']);
		redirect('mandiri/perorangan');
	}

	function insert(){
		$pin = $this->mandiri_model->insert();
		$_SESSION['pin'] = $pin;
		redirect('mandiri');
	}

	function delete($p=1,$o=0,$id=''){
		$outp = $this->mandiri_model->delete($id);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
		redirect("mandiri");
	}

	function ajax_pin_show($pin=""){
		redirect('mandiri');
	}


}
