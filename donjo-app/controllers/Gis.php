<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Gis extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();

		// Jika offline_mode dalam level yang menyembunyikan website,
		// tidak perlu menampilkan halaman website
		if ($this->setting->offline_mode >= 2) {
			redirect('hom_desa');
			exit;
		}

		$this->load->model('user_model');


		$this->load->model('penduduk_model');
		$this->load->model('plan_lokasi_model');
		$this->load->model('plan_area_model');
		$this->load->model('plan_garis_model');
		$this->load->model('header_model');

		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		//$this->output->enable_profiler(1);
		// Load library ion auth
//		$this->load->library('ion_auth');
//		$this->load->library('session');
//		$this->load->library('form_validation');
//		$this->load->helper('url');
//
//		$this->config->item('ion_auth') ;
		$this->load->database();
	}

	function clear(){
		unset($_SESSION['log']);
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['sex']);
		unset($_SESSION['warganegara']);
		unset($_SESSION['fisik']);
		unset($_SESSION['mental']);
		unset($_SESSION['menahun']);
		unset($_SESSION['golongan_darah']);
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
		unset($_SESSION['layer_penduduk']);
		unset($_SESSION['layer_keluarga']);
		unset($_SESSION['layer_desa']);
		unset($_SESSION['layer_wilayah']);
		unset($_SESSION['layer_lokasi']);
		unset($_SESSION['layer_area']);
		$_SESSION['layer_keluarga'] == 0;
		redirect('gis');
	}

function index(){

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

		if(isset($_SESSION['layer_penduduk']))
			$data['layer_penduduk']=$_SESSION['layer_penduduk'];
		else $data['layer_penduduk']= 0;

		if(isset($_SESSION['layer_keluarga']))
			$data['layer_keluarga']=$_SESSION['layer_keluarga'];
		else $data['layer_keluarga']= 0;

		if(isset($_SESSION['layer_desa']))
			$data['layer_desa']=$_SESSION['layer_desa'];
		else $data['layer_desa']=0;

		if(isset($_SESSION['layer_wilayah']))
			$data['layer_wilayah']=$_SESSION['layer_wilayah'];
		else $data['layer_wilayah']=0;

		if(isset($_SESSION['layer_lokasi']))
			$data['layer_lokasi']=$_SESSION['layer_lokasi'];
		else $data['layer_lokasi']= 0;

		if(isset($_SESSION['layer_area']))
			$data['layer_area']=$_SESSION['layer_area'];
		else $data['layer_area']= 0;


		$data['list_dusun'] = $this->penduduk_model->list_dusun();
		$data['wilayah'] = $this->penduduk_model->list_wil();
		$data['list_agama'] = $this->penduduk_model->list_agama();
		$data['list_pendidikan_kk'] = $this->penduduk_model->list_pendidikan_kk();
		$data['desa'] = $this->penduduk_model->get_desa();
		$data['lokasi']  = $this->plan_lokasi_model->list_data();
		$data['garis']  = $this->plan_garis_model->list_data();
		$data['area']  = $this->plan_area_model->list_data();
		$data['penduduk'] = $this->penduduk_model->list_data_map();
		$data['keyword'] = $this->penduduk_model->autocomplete();
		$header = $this->header_model->get_data();
		//$header ['desa']= $this->header_model->get_data();
		$this->load->view('gis/header', $header);
		$this->load->view('gis/maps',$data);
		$this->load->view('footer');

	}


	function search(){
		$cari = $this->input->post('cari');
		if($cari!='') {
			$_SESSION['cari']=$cari;
			if(empty($_SESSION['layer_penduduk']) AND empty($_SESSION['layer_keluarga']))
				$_SESSION['layer_penduduk'] = 1;
		}
		else unset($_SESSION['cari']);
		redirect('gis');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!="") {
			$_SESSION['filter']=$filter;
			if(empty($_SESSION['layer_penduduk']) AND empty($_SESSION['layer_keluarga']))
				$_SESSION['layer_penduduk'] = 1;
		}
		else unset($_SESSION['filter']);
		redirect('gis');
	}

	function layer_penduduk(){
		$layer_penduduk = $this->input->post('layer_penduduk');
		if($layer_penduduk=="")
			$_SESSION['layer_penduduk']=0;
		else{
			$_SESSION['layer_penduduk']=1;
			$_SESSION['layer_keluarga']=0;
			}
		redirect('gis');
	}

	function layer_wilayah(){
		$layer_wilayah = $this->input->post('layer_wilayah');
		if($layer_wilayah=="")
			$_SESSION['layer_wilayah']=0;
		else $_SESSION['layer_wilayah']=1;
		redirect('gis');
	}

	function layer_area(){
		$layer_area = $this->input->post('layer_area');
		if($layer_area=="")
			$_SESSION['layer_area']=0;
		else $_SESSION['layer_area']=1;
		redirect('gis');
	}

	function layer_lokasi(){
		$layer_lokasi = $this->input->post('layer_lokasi');
		if($layer_lokasi=="")
			$_SESSION['layer_lokasi']=0;
		else $_SESSION['layer_lokasi']=1;
		redirect('gis');
	}

	function layer_keluarga(){
		$layer_keluarga = $this->input->post('layer_keluarga');
		if($layer_keluarga==""){
			$_SESSION['layer_keluarga']=0;
		}else{
			$_SESSION['layer_keluarga']=1;
			$_SESSION['layer_penduduk']=0;
		}
		redirect('gis');
	}

	function layer_desa(){
		$layer_desa = $this->input->post('layer_desa');
		if($layer_desa=="")
			$_SESSION['layer_desa']=0;
		else $_SESSION['layer_desa']=1;
		redirect('gis');
	}

	function sex(){
		$sex = $this->input->post('sex');
		if($sex!="") {
			$_SESSION['sex']=$sex;
			if(empty($_SESSION['layer_penduduk']) AND empty($_SESSION['layer_keluarga']))
				$_SESSION['layer_penduduk'] = 1;
		}
		else unset($_SESSION['sex']);
		redirect('gis');
	}

	function dusun(){
		$dusun = $this->input->post('dusun');
		if($dusun!="") {
			$_SESSION['dusun']=$dusun;
			if(empty($_SESSION['layer_penduduk']) AND empty($_SESSION['layer_keluarga']))
				$_SESSION['layer_penduduk'] = 1;
		}
		else unset($_SESSION['dusun']);
		redirect('gis');
	}

	function rw(){
		$rw = $this->input->post('rw');
		if($rw!="") {
			$_SESSION['rw']=$rw;
			if(empty($_SESSION['layer_penduduk']) AND empty($_SESSION['layer_keluarga']))
				$_SESSION['layer_penduduk'] = 1;
		}
		else unset($_SESSION['rw']);
		redirect('gis');
	}

	function rt(){
		$rt = $this->input->post('rt');
		if($rt!="") {
			$_SESSION['rt']=$rt;
			if(empty($_SESSION['layer_penduduk']) AND empty($_SESSION['layer_keluarga']))
				$_SESSION['layer_penduduk'] = 1;
		}
		else unset($_SESSION['rt']);
		redirect('gis');
	}

	function agama(){
		$agama = $this->input->post('agama');
		if($agama!="") {
			$_SESSION['agama']=$agama;
			if(empty($_SESSION['layer_penduduk']) AND empty($_SESSION['layer_keluarga']))
				$_SESSION['layer_penduduk'] = 1;
		}
		else unset($_SESSION['agama']);
		redirect('gis');
	}

	function ajax_adv_search(){
		$data['dusun'] = $this->penduduk_model->list_dusun();
		$data['agama'] = $this->penduduk_model->list_agama();
		$data['pendidikan_kk'] = $this->penduduk_model->list_pendidikan_kk();
		$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan();
		$data['form_action'] = site_url("gis/adv_search_proses");

		$this->load->view("gis/ajax_adv_search_form", $data);
	}

	function adv_search_proses(){

		$adv_search = $_POST;
		$i=0;
		while($i++ < count($adv_search)){
			$col[$i] = key($adv_search);
				next($adv_search);
		}
		$i=0;
		while($i++ < count($col)){
			if($adv_search[$col[$i]]=="")
				UNSET($adv_search[$col[$i]]);
			else {
				$_SESSION[$col[$i]]=$adv_search[$col[$i]];
				if(empty($_SESSION['layer_penduduk']) AND empty($_SESSION['layer_keluarga']))
				$_SESSION['layer_penduduk'] = 1;
			}
		}

		redirect('gis');
	}
}
