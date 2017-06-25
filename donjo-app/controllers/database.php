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
		$this->load->model('database_model');
		$this->modul_ini = 12;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('export');
	}

	function index(){
		// Untuk development: menghapus session tracking. Tidak ada kaitan dengan database.
		// Di sini untuk kemudahan saja.
		// TODO: cari tempat yang lebih cocok
    if (defined('ENVIRONMENT') AND ENVIRONMENT == 'development') {
			unset($_SESSION['track_web']);
			unset($_SESSION['track_admin']);
    }

		$nav['act']= 1;
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('export/exp');
		$this->load->view('footer');
	}

	function import(){

		$nav['act']= 2;
		$data['form_action'] = site_url("database/import_dasar");
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('import/imp',$data);
		$this->load->view('footer');
	}

	function import_bip(){

		$nav['act']= 5;
		$data['form_action'] = site_url("database/import_data_bip");
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('import/bip',$data);
		$this->load->view('footer');
	}

	function import_ppls(){

		$nav['act']= 4;
		$data['form_action3'] = site_url("database/ppls_individu");
		$data['form_action2'] = site_url("database/ppls_rumahtangga");
		$data['form_action'] = site_url("database/ppls_kuisioner");
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('import/ppls',$data);
		$this->load->view('footer');
	}

	function migrasi_cri(){
		$nav['act']= 6;
		$data['form_action'] = site_url("database/migrasi_db_cri");
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('database/migrasi_cri',$data);
		$this->load->view('footer');
	}

	function backup(){

		$nav['act']= 3;
		$data['form_action'] = site_url("database/restore");
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('database/backup',$data);
		$this->load->view('footer');
	}


	function export_by_keluarga(){
		$this->export_model->export_by_keluarga();
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

	function import_data_bip(){
		$hapus = isset($_POST['hapus_data']);
		$this->import_model->import_bip($hapus);
		redirect('database/import_bip/1');
	}

	function migrasi_db_cri(){
		$this->database_model->migrasi_db_cri();
		redirect('database/migrasi_cri/1');
	}

	function kosongkan_db(){
		if($_SESSION['grup']!=1) {
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect('database/backup'); // hanya untuk administrator
		}
		$this->database_model->kosongkan_db();
		redirect('database/backup');
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
		if($_SESSION['grup']!=1) {
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect('database/backup'); // hanya untuk administrator
		}
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
