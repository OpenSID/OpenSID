<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Garis extends CI_Controller{

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
		$this->load->model('plan_garis_model');
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
		unset($_SESSION['line']);
		unset($_SESSION['subline']);
		redirect('garis');
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

		if(isset($_SESSION['line']))
			$data['line'] = $_SESSION['line'];
		else $data['line'] = '';

		if(isset($_SESSION['subline']))
			$data['subline'] = $_SESSION['subline'];
		else $data['subline'] = '';

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->plan_garis_model->paging($p,$o);
		$data['main']    = $this->plan_garis_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_garis_model->autocomplete();
		$data['list_line']        = $this->plan_garis_model->list_line();
		$data['list_subline']        = $this->plan_garis_model->list_subline();

		$header= $this->header_model->get_data();
		$nav['act']=1;

		$this->load->view('header', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('garis/table',$data);
		$this->load->view('footer');

	}

	function form($p=1,$o=0,$id=''){

		$data['desa'] = $this->plan_garis_model->get_desa();
		$data['list_line']        = $this->plan_garis_model->list_line();
		$data['dusun'] = $this->plan_garis_model->list_dusun();

		if($id){
			$data['garis']        = $this->plan_garis_model->get_garis($id);
			$data['form_action'] = site_url("garis/update/$id/$p/$o");
		}
		else{
			$data['garis']        = null;
			$data['form_action'] = site_url("garis/insert");
		}

		$header= $this->header_model->get_data();

		$nav['act']=1;
		$this->load->view('header', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('garis/form',$data);
		$this->load->view('footer');

	}

	function ajax_garis_maps($p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;
		if($id)
			$data['garis'] = $this->plan_garis_model->get_garis($id);
		else
			$data['garis'] = null;

		$data['desa'] = $this->plan_garis_model->get_desa();
		$data['form_action'] = site_url("garis/update_maps/$p/$o/$id");
		$this->load->view("garis/maps", $data);
	}

	function update_maps($p=1,$o=0,$id=''){
		$this->plan_garis_model->update_position($id);
		redirect("garis/index/$p/$o");
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('garis');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('garis');
	}

	function line(){
		$line = $this->input->post('line');
		if($line!=0)
			$_SESSION['line']=$line;
		else unset($_SESSION['line']);
		redirect('garis');
	}

	function subline(){
		unset($_SESSION['line']);
		$subline = $this->input->post('subline');
		if($subline!=0)
			$_SESSION['subline']=$subline;
		else unset($_SESSION['subline']);
		redirect('garis');
	}

	function insert($tip=1){
		$this->plan_garis_model->insert($tip);
		redirect("garis/index/$tip");
	}

	function update($id='',$p=1,$o=0){
		$this->plan_garis_model->update($id);
		redirect("garis/index/$p/$o");
	}

	function delete($p=1,$o=0,$id=''){
		$this->plan_garis_model->delete($id);
		redirect("garis/index/$p/$o");
	}

	function delete_all($p=1,$o=0){
		$this->plan_garis_model->delete_all();
		redirect("garis/index/$p/$o");
	}

	function garis_lock($id=''){
		$this->plan_garis_model->garis_lock($id,1);
		redirect("garis/index/$p/$o");
	}

	function garis_unlock($id=''){
		$this->plan_garis_model->garis_lock($id,2);
		redirect("garis/index/$p/$o");
	}
}
