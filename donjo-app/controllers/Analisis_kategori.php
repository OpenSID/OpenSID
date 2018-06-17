<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Analisis_kategori extends CI_Controller{
	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('analisis_kategori_model');
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
		$_SESSION['submenu'] = "Data Kategori";
		$_SESSION['asubmenu'] = "analisis_kategori";
		$this->modul_ini = 5;
	}
	function clear(){
		unset($_SESSION['cari']);
		redirect('analisis_kategori');
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

		$data['paging']  = $this->analisis_kategori_model->paging($p,$o);
		$data['main']    = $this->analisis_kategori_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_kategori_model->autocomplete();
		$data['analisis_master'] = $this->analisis_kategori_model->get_analisis_master();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_kategori/table',$data);
		$this->load->view('footer');
	}
	function form($p=1,$o=0,$id=''){
		$data['p'] = $p;
		$data['o'] = $o;

		if($id){
			$data['analisis_kategori']        = $this->analisis_kategori_model->get_analisis_kategori($id);
			$data['form_action'] = site_url("analisis_kategori/update/$p/$o/$id");
		}

		else{
			$data['analisis_kategori']        = null;
			$data['form_action'] = site_url("analisis_kategori/insert");
		}




		//$this->load->view('header', $header);
		//$this->load->view('analisis_master/nav');
		$this->load->view('analisis_kategori/ajax_form',$data);
		//$this->load->view('footer');
	}
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis_kategori');
	}
	function insert(){
		$this->analisis_kategori_model->insert();
		redirect('analisis_kategori');
	}
	function update($p=1,$o=0,$id=''){
		$this->analisis_kategori_model->update($id);
		redirect("analisis_kategori/index/$p/$o");
	}
	function delete($p=1,$o=0,$id=''){
		$this->analisis_kategori_model->delete($id);
		redirect("analisis_kategori/index/$p/$o");
	}
	function delete_all($p=1,$o=0){
		$this->analisis_kategori_model->delete_all();
		redirect("analisis_kategori/index/$p/$o");
	}
}
