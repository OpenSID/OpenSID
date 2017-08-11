<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class penduduk_log extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');

		$this->load->model('penduduk_model');
		$this->load->model('header_model');
		$this->modul_ini = 2;
	}

	function clear(){
		unset($_SESSION['log']);
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['sex']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['agama']);
		unset($_SESSION['umur_min']);
		unset($_SESSION['umur_max']);
		unset($_SESSION['pekerjaan_id']);
		unset($_SESSION['status']);
		unset($_SESSION['pendidikan_id']);
		unset($_SESSION['status_penduduk']);
		$_SESSION['per_page'] = 200;
		redirect('penduduk_log');
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

		if(isset($_SESSION['sex']))
			$data['sex'] = $_SESSION['sex'];
		else $data['sex'] = '';

		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);

		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'],$data['rw']);

		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';

			}else $data['rw'] = '';

		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		if(isset($_SESSION['agama']))
			$data['agama'] = $_SESSION['agama'];
		else $data['agama'] = '';

		if(isset($_SESSION['pekerjaan_id']))
			$data['pekerjaan_id'] = $_SESSION['pekerjaan_id'];
		else $data['pekerjaan_id'] = '';

		if(isset($_SESSION['status']))
			$data['status'] = $_SESSION['status'];
		else $data['status'] = '';

		if(isset($_SESSION['pendidikan_id']))
			$data['pendidikan_id'] = $_SESSION['pendidikan_id'];
		else $data['pendidikan_id'] = '';

		if(isset($_SESSION['status_penduduk']))
			$data['status_penduduk'] = $_SESSION['status_penduduk'];
		else $data['status_penduduk'] = '';

		// Hanya tampilkan penduduk yang status dasarnya bukan 'HIDUP'
		$_SESSION['log'] = 1;

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->penduduk_model->paging($p,$o,1);
		$data['main']    = $this->penduduk_model->list_data($o, $data['paging']->offset, $data['paging']->per_page,1);
		$data['keyword'] = $this->penduduk_model->autocomplete();
		$data['list_agama'] = $this->penduduk_model->list_agama();
		$data['list_dusun'] = $this->penduduk_model->list_dusun();

		$header = $this->header_model->get_data();

		$nav['act']= 2;
		$this->load->view('header', $header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/penduduk_log',$data);
		$this->load->view('footer');
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('penduduk_log');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!="")
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('penduduk_log');
	}

	function sex(){
		$sex = $this->input->post('sex');
		if($sex!="")
			$_SESSION['sex']=$sex;
		else unset($_SESSION['sex']);
		redirect('penduduk_log');
	}

	function agama(){
		$agama = $this->input->post('agama');
		if($agama!="")
			$_SESSION['agama']=$agama;
		else unset($_SESSION['agama']);
		redirect('penduduk_log');
	}

	function dusun(){
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect('penduduk_log');
	}

	function rw(){
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect('penduduk_log');
	}

	function rt(){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect('penduduk_log');
	}

	function edit_status_dasar($p=1,$o=0,$id=0){
		$data['nik'] = $this->penduduk_model->get_penduduk($id);
		$data['log_status_dasar'] = $this->penduduk_model->get_log_status_dasar($id);
		$data['form_action'] = site_url("penduduk_log/update_status_dasar/$p/$o/$id");
		$this->load->view('sid/kependudukan/ajax_edit_status_dasar',$data);
	}

	function update_status_dasar($p=1,$o=0,$id=''){
		$this->penduduk_model->update_status_dasar($id);
		redirect("penduduk_log/index/$p/$o");
	}

	function cetak($o=0){

		$data['main']    = $this->penduduk_model->list_data($o,0, 10000);

		$this->load->view('sid/kependudukan/penduduk_print',$data);
	}

	function delete_all($p=1,$o=0){
	$this->penduduk_model->delete_all();
		redirect("penduduk_log/index/$p/$o");
	}
}
