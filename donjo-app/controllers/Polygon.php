<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Polygon extends CI_Controller{

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
		$this->load->model('plan_polygon_model');
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
		redirect('polygon');
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

		$data['paging']  = $this->plan_polygon_model->paging($p,$o);
		$data['main']    = $this->plan_polygon_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_polygon_model->autocomplete();

		$header= $this->header_model->get_data();
		$nav['act']=5;

		$this->load->view('header', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('polygon/table',$data);
		$this->load->view('footer');

	}

	function form($p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;

		//$data['link']        = $this->plan_polygon_model->list_link();

		if($id){
			$data['polygon']        = $this->plan_polygon_model->get_polygon($id);
			$data['form_action'] = site_url("polygon/update/$id/$p/$o");
		}
		else{
			$data['polygon']        = null;
			$data['form_action'] = site_url("polygon/insert");
		}

		$header= $this->header_model->get_data();

		$nav['act']=5;
		$this->load->view('header', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('polygon/form',$data);
		$this->load->view('footer');

	}

	function sub_polygon($polygon=1){

		$data['subpolygon']    = $this->plan_polygon_model->list_sub_polygon($polygon);
		$data['polygon'] = $polygon;
		$header= $this->header_model->get_data();
		$nav['act']=5;

		$this->load->view('header-gis', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('polygon/sub_polygon_table',$data);
		$this->load->view('footer');

	}

	function ajax_add_sub_polygon($polygon=0,$id=0){

		if($id){
			$data['polygon']        = $this->plan_polygon_model->get_polygon($id);
			$data['form_action'] = site_url("polygon/update_sub_polygon/$polygon/$id");
		}
		else{
			$data['polygon']        = null;
			$data['form_action'] = site_url("polygon/insert_sub_polygon/$polygon");
		}

		$header= $this->header_model->get_data();

		$nav['act']=5;
		$this->load->view('header-gis', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view("polygon/ajax_add_sub_polygon_form",$data);

	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('polygon');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('polygon');
	}

	function insert($tip=1){
		$this->plan_polygon_model->insert($tip);
		redirect("polygon/index/$tip");
	}

	function update($id='',$p=1,$o=0){
		$this->plan_polygon_model->update($id);
		redirect("polygon/index/$p/$o");
	}

	function delete($p=1,$o=0,$id=''){
		$this->plan_polygon_model->delete($id);
		redirect("polygon/index/$p/$o");
	}

	function delete_all($p=1,$o=0){
		$this->plan_polygon_model->delete_all();
		redirect("polygon/index/$p/$o");
	}

	function polygon_lock($id=''){
		$this->plan_polygon_model->polygon_lock($id,1);
		redirect("polygon/index/$p/$o");
	}

	function polygon_unlock($id=''){
		$this->plan_polygon_model->polygon_lock($id,2);
		redirect("polygon/index/$p/$o");
	}

	function insert_sub_polygon($polygon=''){
		$this->plan_polygon_model->insert_sub_polygon($polygon);
		redirect("polygon/sub_polygon/$polygon");
	}

	function update_sub_polygon($polygon='',$id=''){
		$this->plan_polygon_model->update_sub_polygon($id);
		redirect("polygon/sub_polygon/$polygon");
	}

	function delete_sub_polygon($polygon='',$id=''){
		$this->plan_polygon_model->delete_sub_polygon($id);
		redirect("polygon/sub_polygon/$polygon");
	}

	function delete_all_sub_polygon($polygon=''){
		$this->plan_polygon_model->delete_all_sub_polygon();
		redirect("polygon/sub_polygon/$polygon");
	}

	function polygon_lock_sub_polygon($polygon='',$id=''){
		$this->plan_polygon_model->polygon_lock($id,1);
		redirect("polygon/sub_polygon/$polygon");
	}

	function polygon_unlock_sub_polygon($polygon='',$id=''){
		$this->plan_polygon_model->polygon_lock($id,2);
		redirect("polygon/sub_polygon/$polygon");
	}
}
