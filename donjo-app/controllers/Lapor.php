<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Lapor extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('web_komentar_model');
		$this->modul_ini = 14;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('lapor');
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

		$data['paging']  = $this->web_komentar_model->paging($p,$o);
		$data['main']    = $this->web_komentar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page,2);
		$data['keyword'] = $this->web_komentar_model->autocomplete();

		$header = $this->header_model->get_data();
		$nav['act']=0;

		$this->load->view('header', $header);
		$this->load->view('lapor/nav',$nav);
		$this->load->view('lapor/table',$data);
		$this->load->view('footer');
	}

	function form($p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;

		if($id){
			$data['komentar']        = $this->web_komentar_model->get_komentar($id);
			$data['form_action'] = site_url("komentar/update/$id/$p/$o");
		}
		else{
			$data['komentar']        = null;
			$data['form_action'] = site_url("komentar/insert");
		}

		$data['list_kategori']     = $this->web_komentar_model->list_kategori(1);

		$header = $this->header_model->get_data();

		$nav['act']=2;
		$this->load->view('header', $header);
		$this->load->view('web/spacer');
		$this->load->view('web/nav',$nav);
		$this->load->view('lapor/form',$data);
		$this->load->view('footer');
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('lapor');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('lapor');
	}

	function insert(){
		$this->web_komentar_model->insert();
		redirect('lapor');
	}

	function update($id='',$p=1,$o=0){
		$this->web_komentar_model->update($id);
		redirect("lapor/index/$p/$o");
	}

	function delete($p=1,$o=0,$id=''){
		$this->web_komentar_model->delete($id);
		redirect("lapor/index/$p/$o");
	}

	function delete_all($p=1,$o=0){
		$this->web_komentar_model->delete_all();
		redirect("lapor/index/$p/$o");
	}

	function komentar_lock($id=''){
		$this->web_komentar_model->komentar_lock($id,1);
		redirect("lapor/index/$p/$o");
	}

	function komentar_unlock($id=''){
		$this->web_komentar_model->komentar_lock($id,2);
		redirect("lapor/index/$p/$o");
	}
}
