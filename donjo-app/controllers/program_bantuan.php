<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Program_bantuan extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) redirect('siteman');
		$this->load->model('program_bantuan_model');
		$this->load->model('header_model');
		$this->load->model('program_bantuan_model');
		$this->modul_ini = 6;
	}

	public function index(){
		$_SESSION['per_page'] = 50;
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$data['tampil'] = 0;
		$data['program'] = $this->program_bantuan_model->get_program(1, false);

		$this->load->view('program_bantuan/program',$data);
		$this->load->view('footer');
	}

	function form($program_id){
		$data['program'] = $this->program_bantuan_model->get_program(1, $program_id);
		$sasaran = $data['program'][0]['sasaran'];
		if(isset($_POST['nik'])){
			$data['individu']=$this->program_bantuan_model->get_peserta($_POST['nik'],$sasaran);
		}else{
			$data['individu']=NULL;
		}

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);

		$data['form_action'] = site_url("program_bantuan/add_peserta");
		$this->load->view('program_bantuan/form',$data);
		$this->load->view('footer');
	}


	public function sasaran($sasaran=0){
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);

		$data['tampil'] = $sasaran;
		$data['program'] = $this->program_bantuan_model->list_program($sasaran);

		$this->load->view('program_bantuan/program',$data);
		$this->load->view('footer');
	}

	public function detail($p=1, $id){
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];

		$data['per_page'] = $_SESSION['per_page'];

		$data['program'] = $this->program_bantuan_model->get_program($p, $id);
		$data['paging'] = $data['program'][0]['paging'];
		$this->load->view('program_bantuan/detail',$data);
		$this->load->view('footer');
	}

	public function peserta($cat=0,$id=0){
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);

		$data = $this->program_bantuan_model->get_peserta_program($cat,$id);

		$this->load->view('program_bantuan/peserta',$data);
		$this->load->view('footer');
	}

	public function data_peserta($id){
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$data['peserta'] = $this->program_bantuan_model->get_program_peserta_by_id($id);
		$data['individu']=$this->program_bantuan_model->get_peserta($data['peserta']['peserta'],$data['peserta']['sasaran']);
		$data['detail'] = $this->program_bantuan_model->get_data_program($data['peserta']['program_id']);
		$this->load->view('program_bantuan/data_peserta',$data);
		$this->load->view('footer');
	}

	public function add_peserta($id){
		$this->program_bantuan_model->add_peserta($_POST,$id);
		redirect("program_bantuan/detail/1/$id");
	}

	public function hapus_peserta($id, $peserta_id) {
		$this->program_bantuan_model->hapus_peserta($peserta_id);
		redirect("program_bantuan/detail/1/$id");
	}

	public function edit_peserta($id){
		$this->program_bantuan_model->edit_peserta($_POST,$id);
		$program_id = $_POST['program_id'];
		redirect("program_bantuan/detail/1/$program_id");
	}

	public function edit_peserta_form($id=0){
		$data = $this->program_bantuan_model->get_program_peserta_by_id($id);
		$data['form_action'] = site_url("program_bantuan/edit_peserta/$id");
		$this->load->view('program_bantuan/edit_peserta',$data);
	}

	public function create(){

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Program', 'required');
		$this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
		$this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		if ($this->form_validation->run() === FALSE){
			$this->load->view('program_bantuan/create');
		}else{
			$this->program_bantuan_model->set_program();
			redirect("program_bantuan/");
		}
		$this->load->view('footer');
	}

	public function edit($id){
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Program', 'required');
		$this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
		$this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);

		$data['program'] = $this->program_bantuan_model->get_program(1, $id);

		if ($this->form_validation->run() === FALSE){
			$this->load->view('program_bantuan/edit',$data);
		}else{
			$this->program_bantuan_model->update_program($id);
			redirect("program_bantuan/");
//			$this->load->view('program_bantuan/formsuccess');
		}

		$this->load->view('footer');
	}
	public function update($id){
		$this->program_bantuan_model->update_program($id);
		redirect("program_bantuan/detail/1/".$id);
	}

	public function hapus($id){
		$this->program_bantuan_model->hapus_program($id);
		//$this->load->view('program_bantuan/formsuccess');
		redirect("program_bantuan/");
	}

	public function unduhsheet($id=0){
		if($id > 0){
			/*
			 * Print xls untuk data x
			 * */
			$data["sasaran"] = array("1"=>"Penduduk","2"=>"Keluarga / KK","3"=>"Rumah Tangga","4"=>"Kelompok/Organisasi Kemasyarakatan");
			$data['desa'] = $this->header_model->get_data();
			$data['peserta'] = $this->program_bantuan_model->get_program(1, $id);

			$this->load->view('program_bantuan/unduh-sheet',$data);

		}
	}
}
