<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Suplemen extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('suplemen_model');
		$this->modul_ini = 2;
	}

	public function index(){
		$_SESSION['per_page'] = 50;
		$header = $this->header_model->get_data();
		$nav['act']= 6;

		$this->load->view('header', $header);
		$this->load->view('sid/nav',$nav);
		$data['suplemen'] = $this->suplemen_model->list_data();
		$this->load->view('suplemen/daftar',$data);
		$this->load->view('footer');
	}

	function form_terdata($id){
		$data['sasaran'] = unserialize(SASARAN);
		$data['suplemen'] = $this->suplemen_model->get_suplemen($id);
		$sasaran = $data['suplemen']['sasaran'];
		$data['list_sasaran'] = $this->suplemen_model->list_sasaran($id,$sasaran);
		if(isset($_POST['nik'])){
			$data['individu']=$this->suplemen_model->get_terdata($_POST['nik'],$sasaran);
		}else{
			$data['individu']=NULL;
		}
		$header = $this->header_model->get_data();
		$nav['act']= 6;
		$this->load->view('header', $header);
		$this->load->view('sid/nav',$nav);

		$data['form_action'] = site_url("suplemen/add_terdata");
		$this->load->view('suplemen/form_terdata',$data);
		$this->load->view('footer');
	}


	public function sasaran($sasaran=0){
		$header = $this->header_model->get_data();
		$nav['act']= 6;
		$this->load->view('header', $header);
		$this->load->view('sid/nav',$nav);

		$data['tampil'] = $sasaran;
		$data['program'] = $this->suplemen_model->list_suplemen($sasaran);

		$this->load->view('suplemen/suplemen',$data);
		$this->load->view('footer');
	}

	public function rincian($p=1, $id){
		$header = $this->header_model->get_data();
		$nav['act']= 6;
		$this->load->view('header', $header);
		$this->load->view('sid/nav',$nav);

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data = $this->suplemen_model->get_rincian($p, $id);
		$data['sasaran'] = unserialize(SASARAN);
		$data['per_page'] = $_SESSION['per_page'];
		$this->load->view('suplemen/rincian',$data);
		$this->load->view('footer');
	}

	public function terdata($sasaran=0,$id=0){
		$header = $this->header_model->get_data();
		$nav['act']= 6;
		$this->load->view('header', $header);
		$this->load->view('sid/nav',$nav);

		$data = $this->suplemen_model->get_terdata_suplemen($sasaran,$id);

		$this->load->view('suplemen/terdata',$data);
		$this->load->view('footer');
	}

	public function data_terdata($id){
		$header = $this->header_model->get_data();
		$nav['act']= 6;
		$this->load->view('header', $header);
		$this->load->view('sid/nav',$nav);
		$data['terdata'] = $this->suplemen_model->get_suplemen_terdata_by_id($id);
		$data['suplemen'] = $this->suplemen_model->get_suplemen($data['terdata']['id_suplemen']);
		$data['individu']=$this->suplemen_model->get_terdata($data['terdata']['id_terdata'],$data['suplemen']['sasaran']);
		$this->load->view('suplemen/data_terdata',$data);
		$this->load->view('footer');
	}

	public function add_terdata($id){
		$this->suplemen_model->add_terdata($_POST,$id);
		redirect("suplemen/rincian/1/$id");
	}

	public function hapus_terdata($id_suplemen,$terdata_id) {
		$this->suplemen_model->hapus_terdata($terdata_id);
		redirect("suplemen/rincian/1/$id_suplemen");
	}

	public function edit_terdata($id){
		$this->suplemen_model->edit_terdata($_POST,$id);
		$id_suplemen = $_POST['id_suplemen'];
		redirect("suplemen/rincian/1/$id_suplemen");
	}

	public function edit_terdata_form($id=0){
		$data = $this->suplemen_model->get_suplemen_terdata_by_id($id);
		$data['form_action'] = site_url("suplemen/edit_terdata/$id");
		$this->load->view('suplemen/edit_terdata',$data);
	}

	public function create(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Data', 'required');

		$header = $this->header_model->get_data();
		$nav['act']= 6;

		$this->load->view('header', $header);
		$this->load->view('sid/nav',$nav);
		$data['form_action'] = "suplemen/create";
		if ($this->form_validation->run() === FALSE){
			$this->load->view('suplemen/form');
		}else{
			$this->suplemen_model->create();
			redirect("suplemen/");
		}
		$this->load->view('footer');
	}

	public function edit($id){
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Data', 'required');

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);

		$data['form_action'] = "suplemen/edit/$id";
		$data['suplemen'] = $this->suplemen_model->get_suplemen($id);

		if ($this->form_validation->run() === FALSE){
			$this->load->view('suplemen/form',$data);
		}else{
			$this->suplemen_model->update($id);
			redirect("suplemen/");
		}

		$this->load->view('footer');
	}

	public function hapus($id){
		$this->suplemen_model->hapus($id);
		redirect("suplemen/");
	}

	public function unduhsheet($id=0){
		if($id > 0){
			/*
			 * Print xls untuk data x
			 * */
			$_SESSION['per_page'] = 0; // Unduh semua data
			$data = $this->suplemen_model->get_rincian(1, $id);
			$data['sasaran'] = unserialize(SASARAN);
			$data['desa'] = $this->header_model->get_data();
			$_SESSION['per_page'] = 50; // Kembalikan ke paginasi default

			$this->load->view('suplemen/unduh-sheet',$data);

		}
	}
}
