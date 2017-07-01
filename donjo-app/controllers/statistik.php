<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Statistik extends CI_Controller{

function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('laporan_penduduk_model');
		$this->load->model('program_bantuan_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		$_SESSION['per_page']= 500;
		$this->modul_ini = 3;
	}

	function index($lap=0,$o=0){
		// $data['kategori'] untuk pengaturan penampilan kelompok statistik di laman statistik

		$data['main'] = $this->laporan_penduduk_model->list_data($lap,$o);
		$data['lap']=$lap;
		$data['judul_kelompok'] = "Jenis Kelompok";
		$data['o']=$o;
		$this->get_data_stat($data, $lap);
		$nav['act']= 0;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('statistik/nav',$nav);
		$this->load->view('statistik/penduduk',$data);
		$this->load->view('footer');
	}

	function clear(){
		unset($_SESSION['log']);
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['sex']);
		unset($_SESSION['warganegara']);
		unset($_SESSION['cacat']);
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
		redirect('statistik');
	}

   function graph($lap=0){

		$data['main']    = $this->laporan_penduduk_model->list_data($lap);
		$data['lap']=$lap;
		$this->get_data_stat($data, $lap);
		$nav['act']= 0;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('statistik/nav',$nav);
		$this->load->view('statistik/penduduk_graph',$data);
		$this->load->view('footer');
	}

   function pie($lap=0){

		$data['main']    = $this->laporan_penduduk_model->list_data($lap);
		$data['lap']=$lap;
		$this->get_data_stat($data, $lap);
		$nav['act']= 0;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('statistik/nav',$nav);
		$this->load->view('statistik/penduduk_pie',$data);
		$this->load->view('footer');
	}

	private function get_data_stat(&$data, $lap){
		$data['stat'] = $this->laporan_penduduk_model->judul_statistik($lap);
		$data['list_bantuan'] = $this->program_bantuan_model->list_program(0);
		if ($lap>50) {
			// Untuk program bantuan, $lap berbentuk '50<program_id>'
			$program_id = preg_replace('/^50/', '', $lap);
			$data['program'] = $this->program_bantuan_model->get_sasaran($program_id);
			$data['judul_kelompok'] = $data['program']['judul_sasaran'];
			$data['kategori'] = 'bantuan';
		} elseif ($lap>20) {
			$data['kategori'] = 'keluarga';
		} else {
			$data['kategori'] = 'penduduk';
		}
	}

  function cetak($lap=0){
		$data['lap']=$lap;
		$data['stat'] = $this->laporan_penduduk_model->judul_statistik($lap);
		$data['config']  = $this->laporan_penduduk_model->get_config();
		$data['main']    = $this->laporan_penduduk_model->list_data($lap);
		$this->load->view('statistik/penduduk_print',$data);
	}

	function excel($lap=0){
		$data['lap']=$lap;
		$data['stat'] = $this->laporan_penduduk_model->judul_statistik($lap);
		$data['config']  = $this->laporan_penduduk_model->get_config();
		$data['main']    = $this->laporan_penduduk_model->list_data($lap);
		$this->load->view('statistik/penduduk_excel',$data);
	}

	function rentang_umur(){
		$data['lap']=13;
		$data['main']    = $this->laporan_penduduk_model->list_data_rentang();

		$header = $this->header_model->get_data();
		$menu['act']='2';

		$this->load->view('header', $header);
		//$this->load->view('statistik/menu');
		$this->load->view('statistik/nav',$menu);
		$this->load->view('statistik/rentang_umur',$data);
		$this->load->view('footer');
	}

	function form_rentang($id=0){

		if($id==0){
			$data['form_action'] = site_url("statistik/rentang_insert");
			$data['rentang']= $this->laporan_penduduk_model->get_rentang_terakhir();
			$data['rentang']['nama']="";
			$data['rentang']['sampai']="";
		}
		else{
			$data['form_action'] = site_url("statistik/rentang_update/$id");
			$data['rentang']     = $this->laporan_penduduk_model->get_rentang($id);
		}
		$this->load->view('statistik/ajax_rentang_form',$data);

	}

	function rentang_insert(){
		$data['insert'] = $this->laporan_penduduk_model->insert_rentang();
		redirect('statistik/rentang_umur');
	}

	function rentang_update($id=0){
		$this->laporan_penduduk_model->update_rentang($id);
		redirect('statistik/rentang_umur');
	}

	function rentang_delete($id=0){
		$this->laporan_penduduk_model->delete_rentang($id);
		redirect('statistik/rentang_umur');
	}

	function delete_all_rentang(){
		$this->laporan_penduduk_model->delete_all_rentang();
		redirect('statistik/rentang_umur');
	}

}
