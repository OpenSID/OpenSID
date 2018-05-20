<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Line extends CI_Controller{

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
		$this->load->model('plan_line_model');
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
		redirect('line');
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

		$data['paging']  = $this->plan_line_model->paging($p,$o);
		$data['main']    = $this->plan_line_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_line_model->autocomplete();

		$header = $this->header_model->get_data();
		$nav['act']=2;

		$this->load->view('header', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('line/table',$data);
		$this->load->view('footer');

	}

	function form($p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;

		//$data['link']        = $this->plan_line_model->list_link();

		if($id){
			$data['line']        = $this->plan_line_model->get_line($id);
			$data['form_action'] = site_url("line/update/$id/$p/$o");
		}
		else{
			$data['line']        = null;
			$data['form_action'] = site_url("line/insert");
		}

		$header= $this->header_model->get_data();

		$nav['act']=2;
		$this->load->view('header', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('line/form',$data);
		$this->load->view('footer');

	}

	function sub_line($line=1){

		$data['subline']    = $this->plan_line_model->list_sub_line($line);
		$data['line'] = $line;
		$header= $this->header_model->get_data();
		$nav['act']=2;

		$this->load->view('header-gis', $header);

		$this->load->view('plan/nav',$nav);
		$this->load->view('line/sub_line_table',$data);
		$this->load->view('footer');

	}

	function ajax_add_sub_line($line=0,$id=0){

		//$data['line'] = $line;

		//$data['link']        = $this->plan_line_model->list_link();

		if($id){
			$data['line']        = $this->plan_line_model->get_line($id);
			$data['form_action'] = site_url("line/update_sub_line/$line/$id");
		}
		else{
			$data['line']        = null;
			$data['form_action'] = site_url("line/insert_sub_line/$line");
		}

		$this->load->view("line/ajax_add_sub_line_form",$data);
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('line');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('line');
	}

	function insert($tip=1){
		$this->plan_line_model->insert($tip);
		redirect("line/index/$tip");
	}

	function update($id='',$p=1,$o=0){
		$this->plan_line_model->update($id);
		redirect("line/index/$p/$o");
	}

	function delete($p=1,$o=0,$id=''){
		$this->plan_line_model->delete($id);
		redirect("line/index/$p/$o");
	}

	function delete_all($p=1,$o=0){
		$this->plan_line_model->delete_all();
		redirect("line/index/$p/$o");
	}

	function line_lock($id=''){
		$this->plan_line_model->line_lock($id,1);
		redirect("line/index/$p/$o");
	}

	function line_unlock($id=''){
		$this->plan_line_model->line_lock($id,2);
		redirect("line/index/$p/$o");
	}

	function insert_sub_line($line=''){
		$this->plan_line_model->insert_sub_line($line);
		redirect("line/sub_line/$line");
	}

	function update_sub_line($line='',$id=''){
		$this->plan_line_model->update_sub_line($id);
		redirect("line/sub_line/$line");
	}

	function delete_sub_line($line='',$id=''){
		$this->plan_line_model->delete_sub_line($id);
		redirect("line/sub_line/$line");
	}

	function delete_all_sub_line($line=''){
		$this->plan_line_model->delete_all_sub_line();
		redirect("line/sub_line/$line");
	}

	function line_lock_sub_line($line='',$id=''){
		$this->plan_line_model->line_lock($id,1);
		redirect("line/sub_line/$line");
	}

	function line_unlock_sub_line($line='',$id=''){
		$this->plan_line_model->line_lock($id,2);
		redirect("line/sub_line/$line");
	}
}
