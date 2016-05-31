<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Analisis_grafik extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('analisis_grafik_model');
		$this->load->model('analisis_laporan_keluarga_model');
		$this->load->model('user_model');
		$this->load->model('header_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) redirect('siteman');
	}
	
	function clear($id=0){
		$_SESSION['analisis_master']=$id;
		unset($_SESSION['cari']);
		redirect('analisis_grafik');
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
		$data['paging']  = $this->analisis_grafik_model->paging($p,$o);
		$data['main']    = $this->analisis_grafik_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_grafik_model->autocomplete();
		$data['analisis_master'] = $this->analisis_grafik_model->get_analisis_master();

		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_grafik/table',$data);
		$this->load->view('footer');
	}
	
	function time($p=1,$o=0){
	
		unset($_SESSION['cari2']);
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		$data['paging']  = $this->analisis_grafik_model->paging($p,$o);
		$data['main']    = $this->analisis_grafik_model->list_data2($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_grafik_model->autocomplete();
		$data['analisis_master'] = $this->analisis_grafik_model->get_analisis_master();
		$data['periode'] = $this->analisis_grafik_model->list_periode();

		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_grafik/time',$data);
		$this->load->view('footer');
	}

	function dusun(){
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect('analisis_grafik');
	}
	
	function rw(){
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect('analisis_grafik');
	}
	
	function rt(){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect('analisis_grafik');
	}
	
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis_grafik');
	}
}