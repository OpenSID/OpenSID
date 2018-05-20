<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Analisis_klasifikasi extends CI_Controller{
	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('analisis_klasifikasi_model');
		$this->load->model('user_model');
		$this->load->model('header_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$_SESSION['submenu'] = "Data Klasifikasi";
		$_SESSION['asubmenu'] = "analisis_klasifikasi";
		$this->modul_ini = 5;
	}
	function clear(){
		unset($_SESSION['cari']);
		redirect('analisis_klasifikasi');
	}
	function leave(){
		$id=$_SESSION['analisis_master'];
		unset($_SESSION['analisis_master']);
		redirect("analisis_master/menu/$id");
	}
	function index($p=1,$o=0){
		unset($_SESSION['cari2']);
		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->analisis_klasifikasi_model->paging($p,$o);
		$data['main']    = $this->analisis_klasifikasi_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_klasifikasi_model->autocomplete();
		$data['analisis_master'] = $this->analisis_klasifikasi_model->get_analisis_master();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_klasifikasi/table',$data);
		$this->load->view('footer');
	}
	function form($p=1,$o=0,$id=''){
		$data['p'] = $p;
		$data['o'] = $o;

		if($id){
			$data['analisis_klasifikasi']        = $this->analisis_klasifikasi_model->get_analisis_klasifikasi($id);
			$data['form_action'] = site_url("analisis_klasifikasi/update/$p/$o/$id");
		}

		else{
			$data['analisis_klasifikasi']        = null;
			$data['form_action'] = site_url("analisis_klasifikasi/insert");
		}

		$data['analisis_master'] = $this->analisis_klasifikasi_model->get_analisis_master();
		$this->load->view('analisis_klasifikasi/ajax_form',$data);
	}
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis_klasifikasi');
	}
	function insert(){
		$this->analisis_klasifikasi_model->insert();
		redirect('analisis_klasifikasi');
	}
	function update($p=1,$o=0,$id=''){
		$this->analisis_klasifikasi_model->update($id);
		redirect("analisis_klasifikasi/index/$p/$o");
	}
	function delete($p=1,$o=0,$id=''){
		$this->analisis_klasifikasi_model->delete($id);
		redirect("analisis_klasifikasi/index/$p/$o");
	}
	function delete_all($p=1,$o=0){
		$this->analisis_klasifikasi_model->delete_all();
		redirect("analisis_klasifikasi/index/$p/$o");
	}
}
