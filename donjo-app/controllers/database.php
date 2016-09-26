<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Database extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->dbforge();
		//$this->load->model('wilayah_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('import_model');
		$this->load->model('export_model');

	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('export');
	}

	function index(){

		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$header['modul'] = 12;
		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('export/exp');
		$this->load->view('footer');
	}

	function import(){

		$nav['act']= 2;
		$data['form_action'] = site_url("database/import_dasar");
		$data['form_action2'] = site_url("database/import_siak");
		$header = $this->header_model->get_data();
		$header['modul'] = 12;
		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('import/imp',$data);
		$this->load->view('footer');
	}

	function import_ppls(){

		$nav['act']= 4;
		$data['form_action3'] = site_url("database/ppls_individu");
		$data['form_action2'] = site_url("database/ppls_rumahtangga");
		$data['form_action'] = site_url("database/ppls_kuisioner");
		$header = $this->header_model->get_data();
		$header['modul'] = 12;
		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('import/ppls',$data);
		$this->load->view('footer');
	}

	function backup(){

		$nav['act']= 3;
		$data['form_action'] = site_url("database/restore");
		$header = $this->header_model->get_data();
		$header['modul'] = 12;
		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('database/backup',$data);
		$this->load->view('footer');
	}


	function export_dasar(){
		$this->export_model->export_dasar();
	}

	function export_akp(){
		$this->export_model->export_akp();
	}

	function import2(){
		$nav['act']= 2;
		$data['form_action'] = site_url("database/import_dasar");
		$data['form_action2'] = site_url("database/import_akp");
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('export/nav',$nav);
		$this->load->view('export/imp',$data);
		$this->load->view('footer');

	}

	function pre_migrate(){
		$nav['act']= 3;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('export/nav',$nav);
		$this->load->view('export/mig');
		$this->load->view('footer');
	}

	function migrate(){
		//$this->wilayah_model->migrate();

		$this->dbforge->drop_table('tweb_dusun_x');
		$this->dbforge->drop_table('tweb_rw_x');
		$this->dbforge->drop_table('tweb_rt_x');
		$this->dbforge->drop_table('tweb_keluarga_x');
		$this->dbforge->drop_table('tweb_keluarga_x_pindah');
		$this->dbforge->drop_table('tweb_penduduk_x');
		$this->dbforge->drop_table('tweb_penduduk_x_pindah');
	}

	function import_dasar(){
		$hapus = isset($_POST['hapus_data']);
		$this->import_model->import_excel($hapus);
		redirect('database/import/1');
		//import_das();
	}

	function ppls_kuisioner(){
		$this->import_model->ppls_kuisioner();
		redirect('database/import_ppls/1');
		//import_das();
	}

	function ppls_individu(){
		$this->import_model->ppls_individu();
		redirect('database/import_ppls');
		//import_das();
	}

	function ppls_rumahtangga(){
		$this->import_model->ppls_rumahtangga();
		redirect('database/import_ppls/1');
		//import_das();
	}

	function import_siak(){
		$data["siak"] = $this->import_model->import_siak();
		$_SESSION["SIAK"] = $data["siak"];
		redirect('database/import/3');
	}

	function import_akp(){
		$this->import_model->import_akp();
		redirect('database/import');
	}

	function jos(){
		$this->export_model->analisis();
		redirect('database/import');
	}

	function exec_backup(){
		$this->export_model->backup();
	//	redirect('database/backup');
	}

	function restore(){
		$this->export_model->restore();
		if ($_SESSION['success'] == 1)
			redirect('database/backup');
	}

	function ces(){
		$this->export_model->lombok();
		redirect('database/import');
	}

	function surat(){
		$this->export_model->gawe_surat();
		//redirect('database/import');
	}

}
