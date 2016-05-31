<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class analisis_laporan_keluarga extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('analisis_laporan_keluarga_model');
		$this->load->model('user_model');
		$this->load->model('header_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) redirect('siteman');
	}
	
	function clear($id=0){
		$_SESSION['analisis_master']=$id;
		unset($_SESSION['cari']);
		unset($_SESSION['klasifikasi']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$_SESSION['per_page'] = 50;
		redirect('analisis_laporan_keluarga');
	}
	
	function leave(){
		$id=$_SESSION['analisis_master'];
		unset($_SESSION['analisis_master']);
		redirect("analisis_master/menu/$id");
	}
	
	function index($p=1,$o=0){
	
		unset($_SESSION['cari2']);
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['klasifikasi']))
			$data['klasifikasi'] = $_SESSION['klasifikasi'];
		else $data['klasifikasi'] = '';
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_laporan_keluarga_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->analisis_laporan_keluarga_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		$data['list_dusun'] = $this->analisis_laporan_keluarga_model->list_dusun();
		$data['list_klasifikasi'] = $this->analisis_laporan_keluarga_model->list_klasifikasi();
		$data['paging']  = $this->analisis_laporan_keluarga_model->paging($p,$o);
		$data['main']    = $this->analisis_laporan_keluarga_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_laporan_keluarga_model->autocomplete();
		$data['analisis_master'] = $this->analisis_laporan_keluarga_model->get_analisis_master();
		$data['analisis_periode'] = $this->analisis_laporan_keluarga_model->get_periode();

		$header = $this->header_model->get_data();
		//print_r($data['main']);
		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_laporan_keluarga/table',$data);
		$this->load->view('footer');
	}
	
	function kuisioner($p=1,$o=0,$id=''){
	
		$data['p'] = $p;
		$data['o'] = $o;
		
		$data['analisis_master'] = $this->analisis_laporan_keluarga_model->get_analisis_master();
		$data['subjek']        = $this->analisis_laporan_keluarga_model->get_subjek($id);
		$data['list_jawab'] = $this->analisis_laporan_keluarga_model->list_indikator($id);
		$data['form_action'] = site_url("analisis_laporan_keluarga/update_kuisioner/$p/$o/$id");
		
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_laporan_keluarga/manajemen_kuisioner_form',$data);
		$this->load->view('footer');
	}
	
	function dusun(){
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect('analisis_laporan_keluarga');
	}
	
	function rw(){
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect('analisis_laporan_keluarga');
	}
	
	function rt(){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect('analisis_laporan_keluarga');
	}
	
	function klasifikasi(){
		$klasifikasi = $this->input->post('klasifikasi');
		if($klasifikasi!="")
			$_SESSION['klasifikasi']=$klasifikasi;
		else unset($_SESSION['klasifikasi']);
		redirect('analisis_laporan_keluarga');
	}
	
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis_laporan_keluarga');
	}
		
}
