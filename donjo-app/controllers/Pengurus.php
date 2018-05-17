<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Pengurus extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('pamong_model');
		$this->load->model('header_model');
		$this->modul_ini = 1;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('pengurus');
	}

	function index(){

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		$data['main'] = $this->pamong_model->list_data();
		$data['keyword'] = $this->pamong_model->autocomplete();
		$header = $this->header_model->get_data();

		$nav['act']= 1;
		$this->load->view('header',$header);

		$this->load->view('home/nav',$nav);
		$this->load->view('home/pengurus',$data);
		$this->load->view('footer');
	}

	function form($id=''){

		if($id){
			$data['pamong']          = $this->pamong_model->get_data($id);
			$data['form_action'] = site_url("pengurus/update/$id");
		}
		else{
			$data['pamong']          = null;
			$data['form_action'] = site_url("pengurus/insert");
		}

		$header = $this->header_model->get_data();

		$this->load->view('header',$header);

		$nav['act']= 1;
		$this->load->view('home/nav',$nav);
		$this->load->view('home/pengurus_form',$data);
		$this->load->view('footer');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!="")
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('pengurus');
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('pengurus');
	}

	function insert(){
		$this->pamong_model->insert();
		redirect('pengurus');
	}

	function update($id=''){
		$this->pamong_model->update($id);
		redirect('pengurus');
	}

	function delete($id=''){
		$_SESSION['success']=1;
		$outp = $this->pamong_model->delete($id);
		if (!$outp) $_SESSION['success']=-1;
		redirect('pengurus');
	}

	function delete_all(){
		$this->pamong_model->delete_all();
		redirect('pengurus');
	}

	function ttd_on($id=''){
		$this->pamong_model->ttd($id,1);
		redirect('pengurus');
	}

	function ttd_off($id=''){
		$this->pamong_model->ttd($id,0);
		redirect('pengurus');
	}
}
