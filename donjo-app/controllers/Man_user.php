<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Man_user extends CI_Controller{

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
		$this->modul_ini = 11;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('man_user');
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

		$data['paging']  = $this->user_model->paging($p,$o);
		$data['main']    = $this->user_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->user_model->autocomplete();

		$header = $this->header_model->get_data();
		$menu['act']='man_user';

		$this->load->view('header', $header);
		$this->load->view('man_user/nav');
		$this->load->view('man_user/manajemen_user_table',$data);
		$this->load->view('footer');
	}

	function form($p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;

		if($id){
			$data['user']        = $this->user_model->get_user($id);
			$data['form_action'] = site_url("man_user/update/$p/$o/$id");
		}

		else{
			$data['user']        = null;
			$data['form_action'] = site_url("man_user/insert");
		}

		$data['grup'] = $this->user_model->list_grup();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('man_user/nav');
		$this->load->view('man_user/manajemen_user_form',$data);
		$this->load->view('footer');
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('man_user');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('man_user');
	}

	function insert(){
		$this->user_model->insert();
		redirect('man_user');
	}

	function update($p=1,$o=0,$id=''){
		$this->user_model->update($id);
		redirect("man_user/index/$p/$o");
	}

	function delete($p=1,$o=0,$id=''){
		$this->user_model->delete($id);
		redirect("man_user/index/$p/$o");
	}

	function delete_all($p=1,$o=0){
		$this->user_model->delete_all();
		redirect("man_user/index/$p/$o");
	}

	function user_lock($id=''){
		$this->user_model->user_lock($id,0);
		redirect("man_user/index/$p/$o");
	}

	function user_unlock($id=''){
		$this->user_model->user_lock($id,1);
		redirect("man_user/index/$p/$o");
	}

}
