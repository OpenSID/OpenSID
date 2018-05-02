<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Analisis_indikator extends CI_Controller{
	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('analisis_indikator_model');
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
		$_SESSION['submenu'] = "Data Indikator";
		$_SESSION['asubmenu'] = "analisis_indikator";
		$this->modul_ini = 5;
	}
	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['tipe']);
		unset($_SESSION['kategori']);
		redirect('analisis_indikator');
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

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
		if(isset($_SESSION['tipe']))
			$data['tipe'] = $_SESSION['tipe'];
		else $data['tipe'] = '';
		if(isset($_SESSION['kategori']))
			$data['kategori'] = $_SESSION['kategori'];
		else $data['kategori'] = '';
		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->analisis_indikator_model->paging($p,$o);
		$data['main']    = $this->analisis_indikator_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_indikator_model->autocomplete();
		$data['analisis_master'] = $this->analisis_indikator_model->get_analisis_master();
		$data['list_tipe'] = $this->analisis_indikator_model->list_tipe();
		$data['list_kategori'] = $this->analisis_indikator_model->list_kategori();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_indikator/table',$data);
		$this->load->view('footer');
	}
	function form($p=1,$o=0,$id=''){
		$data['p'] = $p;
		$data['o'] = $o;

		if($id){
			$data['analisis_indikator']        = $this->analisis_indikator_model->get_analisis_indikator($id);
			$data['form_action'] = site_url("analisis_indikator/update/$p/$o/$id");
		}

		else{
			$data['analisis_indikator']        = null;
			$data['form_action'] = site_url("analisis_indikator/insert");
		}

		$data['list_kategori'] = $this->analisis_indikator_model->list_kategori();
		$header = $this->header_model->get_data();
		$data['analisis_master'] = $this->analisis_indikator_model->get_analisis_master();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_indikator/form',$data);
		$this->load->view('footer');
	}
	function parameter($id=''){
		$ai  = $this->analisis_indikator_model->get_analisis_indikator($id);
		if($ai['id_tipe']==3 OR $ai['id_tipe']==4)
		redirect('analisis_indikator');

		$data['analisis_indikator']        = $this->analisis_indikator_model->get_analisis_indikator($id);
		$data['analisis_master'] = $this->analisis_indikator_model->get_analisis_master();
		$data['main']        = $this->analisis_indikator_model->list_indikator($id);

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_indikator/parameter/table',$data);
		$this->load->view('footer');
	}
	function form_parameter($in='',$id=''){
		if($id){
			$data['analisis_parameter']        = $this->analisis_indikator_model->get_analisis_parameter($id);
			$data['form_action'] = site_url("analisis_indikator/p_update/$in/$id");
		}

		else{
			$data['analisis_parameter']        = null;
			$data['form_action'] = site_url("analisis_indikator/p_insert/$in");
		}

		$data['analisis_master'] = $this->analisis_indikator_model->get_analisis_master();
		$data['analisis_indikator']        = $this->analisis_indikator_model->get_analisis_indikator($in);

	//	$this->load->view('header', $header);
	//	$this->load->view('analisis_master/nav');
		$this->load->view('analisis_indikator/parameter/ajax_form',$data);
	//	$this->load->view('footer');
	}
	function menu($id=''){
		$data['analisis_indikator']        = $this->analisis_indikator_model->get_analisis_indikator($id);

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_indikator/menu',$data);
		$this->load->view('footer');
	}
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis_indikator');
	}
	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('analisis_indikator');
	}
	function tipe(){
		$filter = $this->input->post('tipe');
		if($filter!=0)
			$_SESSION['tipe']=$filter;
		else unset($_SESSION['tipe']);
		redirect('analisis_indikator');
	}
	function kategori(){
		$filter = $this->input->post('kategori');
		if($filter!=0)
			$_SESSION['kategori']=$filter;
		else unset($_SESSION['kategori']);
		redirect('analisis_indikator');
	}
	function insert(){
		$this->analisis_indikator_model->insert();
		redirect('analisis_indikator');
	}
	function update($p=1,$o=0,$id=''){
		$this->analisis_indikator_model->update($id);
		redirect("analisis_indikator/index/$p/$o");
	}
	function delete($p=1,$o=0,$id=''){
		$this->analisis_indikator_model->delete($id);
		redirect("analisis_indikator/index/$p/$o");
	}
	function delete_all($p=1,$o=0){
		$this->analisis_indikator_model->delete_all();
		redirect("analisis_indikator/index/$p/$o");
	}
	function p_insert($in=''){
		$this->analisis_indikator_model->p_insert($in);
		redirect("analisis_indikator/parameter/$in");
	}
	function p_update($in='',$id=''){
		$this->analisis_indikator_model->p_update($id);
		redirect("analisis_indikator/parameter/$in");
	}
	function p_delete($in='',$id=''){
		$this->analisis_indikator_model->p_delete($id);
		redirect("analisis_indikator/parameter/$in");
	}
	function p_delete_all($in=''){
		$this->analisis_indikator_model->p_delete_all();
		redirect("analisis_indikator/parameter/$in");
	}
}
