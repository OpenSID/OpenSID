<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Plan extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('plan_lokasi_model');
		//$this->output->enable_profiler(1);
		// Load library ion auth
		//$this->load->library('ion_auth');
//		$this->load->library('session');
//		$this->load->library('form_validation');
//		$this->load->helper('url');

		//$this->config->item('ion_auth') ;
		$this->load->database();
		$this->modul_ini = 8;

	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['point']);
		unset($_SESSION['subpoint']);
		redirect('plan');
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

		if(isset($_SESSION['point']))
			$data['point'] = $_SESSION['point'];
		else $data['point'] = '';

		if(isset($_SESSION['subpoint']))
			$data['subpoint'] = $_SESSION['subpoint'];
		else $data['subpoint'] = '';

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->plan_lokasi_model->paging($p,$o);
		$data['main']    = $this->plan_lokasi_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_lokasi_model->autocomplete();
		$data['list_point']        = $this->plan_lokasi_model->list_point();
		$data['list_subpoint']        = $this->plan_lokasi_model->list_subpoint();

		$header = $this->header_model->get_data();

		$nav['act']=3;

		$this->load->view('header', $header);
		$this->load->view('plan/nav',$nav);
		$this->load->view('lokasi/table',$data);
		$this->load->view('footer');

	}

	function form($p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;

		$data['desa'] = $this->plan_lokasi_model->get_desa();
		$data['list_point']        = $this->plan_lokasi_model->list_point();
		$data['dusun'] = $this->plan_lokasi_model->list_dusun();

		if($id){
			$data['lokasi']        = $this->plan_lokasi_model->get_lokasi($id);
			$data['form_action'] = site_url("plan/update/$id/$p/$o");
		}
		else{
			$data['lokasi']        = null;
			$data['form_action'] = site_url("plan/insert");
		}

		$header= $this->header_model->get_data();

		$nav['act']=3;
		$this->load->view('header', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('lokasi/form',$data);
		$this->load->view('footer');

	}

	function ajax_lokasi_maps($p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;
		if($id)
			$data['lokasi'] = $this->plan_lokasi_model->get_lokasi($id);
		else
			$data['lokasi'] = null;

		$data['desa'] = $this->plan_lokasi_model->get_desa();
		$data['form_action'] = site_url("plan/update_maps/$p/$o/$id");
		$this->load->view("lokasi/maps", $data);
	}

	function update_maps($p=1,$o=0,$id=''){
		$this->plan_lokasi_model->update_position($id);
		redirect("plan/index/$p/$o");
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('plan');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('plan');
	}

	function point(){
		$point = $this->input->post('point');
		if($point!=0)
			$_SESSION['point']=$point;
		else unset($_SESSION['point']);
		redirect('plan');
	}

	function subpoint(){
		unset($_SESSION['point']);
		$subpoint = $this->input->post('subpoint');
		if($subpoint!=0)
			$_SESSION['subpoint']=$subpoint;
		else unset($_SESSION['subpoint']);
		redirect('plan');
	}

	function insert($tip=1){
		$this->plan_lokasi_model->insert($tip);
		redirect("plan/index/$tip");
	}

	function update($id='',$p=1,$o=0){
		$this->plan_lokasi_model->update($id);
		redirect("plan/index/$p/$o");
	}

	function delete($p=1,$o=0,$id=''){
		$this->plan_lokasi_model->delete($id);
		redirect("plan/index/$p/$o");
	}

	function delete_all($p=1,$o=0){
		$this->plan_lokasi_model->delete_all();
		redirect("plan/index/$p/$o");
	}

	function lokasi_lock($id=''){
		$this->plan_lokasi_model->lokasi_lock($id,1);
		redirect("plan/index/$p/$o");
	}

	function lokasi_unlock($id=''){
		$this->plan_lokasi_model->lokasi_lock($id,2);
		redirect("plan/index/$p/$o");
	}
}
