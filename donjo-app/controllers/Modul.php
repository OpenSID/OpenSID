<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Modul extends CI_Controller{
	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('modul_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('modul');
	}
	function index(){

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
		$data['main'] = $this->modul_model->list_data();
		$data['keyword'] = $this->modul_model->autocomplete();
		$nav['act']= 1;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);

		$this->load->view('setting/nav',$nav);
		$this->load->view('setting/modul/table',$data);
		$this->load->view('footer');
	}

	function form($id=''){

		if($id){
			$data['modul']          = $this->modul_model->get_data($id);
			$data['form_action'] = site_url("modul/update/$id");
		}
		else{
			$data['modul']          = null;
			$data['form_action'] = site_url("modul/insert");
		}

		$header = $this->header_model->get_data();

		$this->load->view('header',$header);

		$nav['act']= 1;
		$this->load->view('setting/nav',$nav);
		$this->load->view('setting/modul/form',$data);
		$this->load->view('footer');
	}
	function filter(){
		$filter = $this->input->post('filter');
		if($filter!="")
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('modul');
	}
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('modul');
	}
	function insert(){
		$this->modul_model->insert();
		redirect('modul');
	}
	function update($id=''){
		$this->modul_model->update($id);
		redirect('modul');
	}
	function delete($id=''){
		$this->modul_model->delete($id);
		redirect('modul');
	}
	function delete_all(){
		$this->modul_model->delete_all();
		redirect('modul');
	}
}
