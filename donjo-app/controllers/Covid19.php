<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Covid19 extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->load->model('covid19_model');
		$this->modul_ini = 206;
	}

	
	public function index()
	{
		$this->data_pemudik(1);
	}

	public function data_pemudik($p = 1)
	{
		if (isset($_POST['per_page'])) 
			$this->session->set_userdata('per_page', $_POST['per_page']);
		else 
			$this->session->set_userdata('per_page', 10);
		
		$data = $this->covid19_model->get_rincian_pemudik($p);
		$data['per_page'] = $this->session->userdata('per_page');

		$nav['act'] = 206;
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('covid19/data_pemudik', $data);
		$this->load->view('footer');
	}

	public function form_pemudik()
	{
		
		$data['list_penduduk'] = $this->covid19_model->list_penduduk_pemudik();

		if (isset($_POST['terdata']))
		{
			$data['individu'] = $this->covid19_model->get_pemudik($_POST['terdata']);
		}
		else
		{
			$data['individu'] = NULL;
		}

		$data['select_tujuan_mudik'] = $this->covid19_model->list_tujuan_mudik();
		$data['select_status_covid'] = $this->covid19_model->list_status_covid();

		$nav['act'] = 206;
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);

		$data['form_action'] = site_url("covid19/add_pemudik");
		$this->load->view('covid19/form_pemudik', $data);
		$this->load->view('footer');
	}

	public function add_pemudik()
	{
		$this->covid19_model->add_pemudik($_POST);
		redirect("covid19");
	}

	public function hapus_pemudik($id_pemudik)
	{
		$this->redirect_hak_akses('h', "covid19");
		$this->covid19_model->hapus_pemudik($id_pemudik);
		redirect("covid19");
	}

	public function edit_pemudik_form($id = 0)
	{
		$data = $this->covid19_model->get_pemudik_by_id($id);	
		$data['select_tujuan_mudik'] = $this->covid19_model->list_tujuan_mudik();
		$data['select_status_covid'] = $this->covid19_model->list_status_covid();
		
		$data['form_action'] = site_url("covid19/edit_pemudik/$id");
		$this->load->view('covid19/edit_pemudik', $data);
	}

	public function edit_pemudik($id)
	{
		$this->covid19_model->edit_pemudik($_POST, $id);
		redirect("covid19");
	}

	public function detil_pemudik($id)
	{
		$nav['act'] = 206;
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$data['terdata'] = $this->covid19_model->get_detil_pemudik_by_id($id);
		$data['individu'] = $this->covid19_model->get_pemudik($data['terdata']['id_terdata']);

		$data['terdata']['judul_terdata_nama'] = 'NIK';
		$data['terdata']['judul_terdata_info'] = 'Nama Terdata';
		$data['terdata']['terdata_nama'] = $data['individu']['nik'];
		$data['terdata']['terdata_info'] = $data['individu']['nama'];

		$this->load->view('covid19/detil_pemudik', $data);
		$this->load->view('footer');
	}

	public function unduhsheet()
	{
		/*
		 * Print xls untuk data x
		 * */
		$this->session->set_userdata('per_page', 0); // Unduh semua data
		$data = $this->covid19_model->get_rincian_pemudik(1);
		$data['desa'] = $this->header_model->get_data();
		$this->session->set_userdata('per_page', 10); // Kembalikan ke paginasi default

		$this->load->view('covid19/unduh-sheet', $data);
	}

}
