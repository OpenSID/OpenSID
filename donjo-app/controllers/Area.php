<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Area extends CI_Controller{

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
		$this->load->model('plan_area_model');
		//$this->output->enable_profiler(1);
		// Load library ion auth
/*		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');

		$this->config->item('ion_auth') ;*/
		$this->load->database();
		$this->modul_ini = 8;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['polygon']);
		unset($_SESSION['subpolygon']);
		redirect('area');
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

		if(isset($_SESSION['polygon']))
			$data['polygon'] = $_SESSION['polygon'];
		else $data['polygon'] = '';

		if(isset($_SESSION['subpolygon']))
			$data['subpolygon'] = $_SESSION['subpolygon'];
		else $data['subpolygon'] = '';

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->plan_area_model->paging($p,$o);
		$data['main']    = $this->plan_area_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_area_model->autocomplete();
		$data['list_polygon']        = $this->plan_area_model->list_polygon();
		$data['list_subpolygon']        = $this->plan_area_model->list_subpolygon();

		$header= $this->header_model->get_data();
		$nav['act']=4;

		$this->load->view('header', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('area/table',$data);
		$this->load->view('footer');

	}

	function form($p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;

		$data['desa'] = $this->plan_area_model->get_desa();
		$data['list_polygon']        = $this->plan_area_model->list_polygon();
		$data['dusun'] = $this->plan_area_model->list_dusun();

		if($id){
			$data['area']        = $this->plan_area_model->get_area($id);
			$data['form_action'] = site_url("area/update/$id/$p/$o");
		}
		else{
			$data['area']        = null;
			$data['form_action'] = site_url("area/insert");
		}

		$header= $this->header_model->get_data();

		$nav['act']=4;
		$this->load->view('header', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('area/form',$data);
		$this->load->view('footer');

	}

	function ajax_area_maps($p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;
		if($id)
			$data['area'] = $this->plan_area_model->get_area($id);
		else
			$data['area'] = null;

		$data['desa'] = $this->plan_area_model->get_desa();
		$data['form_action'] = site_url("area/update_maps/$p/$o/$id");
		$this->load->view("area/maps", $data);
	}

	function update_maps($p=1,$o=0,$id=''){
		$this->plan_area_model->update_position($id);
		redirect("area/index/$p/$o");
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('area');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('area');
	}

	function polygon(){
		$polygon = $this->input->post('polygon');
		if($polygon!=0)
			$_SESSION['polygon']=$polygon;
		else unset($_SESSION['polygon']);
		redirect('area');
	}

	function subpolygon(){
		unset($_SESSION['polygon']);
		$subpolygon = $this->input->post('subpolygon');
		if($subpolygon!=0)
			$_SESSION['subpolygon']=$subpolygon;
		else unset($_SESSION['subpolygon']);
		redirect('area');
	}

	function insert($tip=1){
		$this->plan_area_model->insert($tip);
		redirect("area/index/$tip");
	}

	function update($id='',$p=1,$o=0){
		$this->plan_area_model->update($id);
		redirect("area/index/$p/$o");
	}

	function delete($p=1,$o=0,$id=''){
		$this->plan_area_model->delete($id);
		redirect("area/index/$p/$o");
	}

	function delete_all($p=1,$o=0){
		$this->plan_area_model->delete_all();
		redirect("area/index/$p/$o");
	}

	function area_lock($id=''){
		$this->plan_area_model->area_lock($id,1);
		redirect("area/index/$p/$o");
	}

	function area_unlock($id=''){
		$this->plan_area_model->area_lock($id,2);
		redirect("area/index/$p/$o");
	}
}
