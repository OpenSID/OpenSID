<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Suplemen extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['config_model', 'suplemen_model', 'pamong_model']);
		$this->modul_ini = 2;
		$this->sub_modul_ini = 25;
	}

	public function index()
	{
		$this->session->per_page = 50;
		$data['suplemen'] = $this->suplemen_model->list_data();

		$this->load->view('header', $this->header);
		$this->load->view('nav');
		$this->load->view('suplemen/suplemen', $data);
		$this->load->view('footer');
	}

	public function form_terdata($id)
	{
		$data['sasaran'] = unserialize(SASARAN);
		$data['suplemen'] = $this->suplemen_model->get_suplemen($id);
		$sasaran = $data['suplemen']['sasaran'];
		$data['list_sasaran'] = $this->suplemen_model->list_sasaran($id, $sasaran);
		if (isset($_POST['terdata']))
		{
			$data['individu'] = $this->suplemen_model->get_terdata($_POST['terdata'], $sasaran);
		}
		else
		{
			$data['individu'] = NULL;
		}

		$data['form_action'] = site_url("suplemen/add_terdata");

		$this->load->view('header', $this->header);
		$this->load->view('nav');
		$this->load->view('suplemen/form_terdata', $data);
		$this->load->view('footer');
	}

	public function panduan()
	{
		$this->load->view('header', $this->header);
		$this->load->view('nav');
		$this->load->view('suplemen/panduan');
		$this->load->view('footer');
	}

	public function rincian($id, $p = 1)
	{
		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data = $this->suplemen_model->get_rincian($p, $id);
		$data['sasaran'] = unserialize(SASARAN);
		$data['func'] = "rincian/$id";
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = ['20', '50', '100'];
		$this->header['minsidebar'] = 1;

		$this->load->view('header', $this->header);
		$this->load->view('nav');
		$this->load->view('suplemen/suplemen_anggota', $data);
		$this->load->view('footer');
	}

	public function terdata($sasaran = 0, $id = 0)
	{
		$data = $this->suplemen_model->get_terdata_suplemen($sasaran, $id);

		$this->load->view('header', $this->header);
		$this->load->view('nav');
		$this->load->view('suplemen/terdata', $data);
		$this->load->view('footer');
	}

	public function data_terdata($id)
	{
		$data['terdata'] = $this->suplemen_model->get_suplemen_terdata_by_id($id);
		$data['suplemen'] = $this->suplemen_model->get_suplemen($data['terdata']['id_suplemen']);
		$data['individu'] = $this->suplemen_model->get_terdata($data['terdata']['id_terdata'], $data['suplemen']['sasaran']);

		$this->load->view('header', $this->header);
		$this->load->view('nav');
		$this->load->view('suplemen/data_terdata', $data);
		$this->load->view('footer');
	}

	public function add_terdata($id)
	{
		$this->suplemen_model->add_terdata($_POST, $id);
		redirect("suplemen/rincian/$id");
	}

	public function hapus_terdata($id_suplemen, $id_terdata)
	{
		$this->redirect_hak_akses('h');
		$this->suplemen_model->hapus_terdata($id_terdata);
		redirect("suplemen/rincian/$id_suplemen");
	}

	public function edit_terdata($id)
	{
		$this->suplemen_model->edit_terdata($_POST, $id);
		$id_suplemen = $_POST['id_suplemen'];
		redirect("suplemen/rincian/$id_suplemen");
	}

	public function edit_terdata_form($id = 0)
	{
		$data = $this->suplemen_model->get_suplemen_terdata_by_id($id);
		$data['form_action'] = site_url("suplemen/edit_terdata/$id");
		$this->load->view('suplemen/edit_terdata', $data);
	}

	public function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Data', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$data['form_action'] = "suplemen/create";
			$this->header['minsidebar'] = 1;

			$this->load->view('header', $this->header);
			$this->load->view('suplemen/form');
			$this->load->view('nav');
			$this->load->view('footer');
		}
		else
		{
			$this->suplemen_model->create();
			redirect("suplemen");
		}
	}

	public function edit($id)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Data', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$data['form_action'] = "suplemen/edit/$id";
			$data['suplemen'] = $this->suplemen_model->get_suplemen($id);
			$this->header['minsidebar'] = 1;

			$this->load->view('header', $this->header);
			$this->load->view('suplemen/form', $data);
			$this->load->view('nav');
			$this->load->view('footer');
		}
		else
		{
			$this->suplemen_model->update($id);
			redirect("suplemen");
		}
	}

	public function hapus($id)
	{
		$this->redirect_hak_akses('h');
		$this->suplemen_model->hapus($id);
		redirect("suplemen");
	}

	/*
	* $aksi = cetak/unduh
	*/
	public function dialog_daftar($id = 0, $aksi = '')
	{
		$data['aksi'] = ucwords($aksi);
		$data['pamong'] = $this->pamong_model->list_data();
		$data['form_action'] = site_url("suplemen/daftar/$id/$aksi");
		$this->load->view('global/confirm_ttd', $data);
	}

	/*
	* $aksi = cetak/unduh
	*/
	public function daftar($id = 0, $aksi = '')
	{
		if ($id > 0)
		{
			$post = $this->input->post();
			$temp = $this->session->per_page;
			$this->session->per_page = 1000000000; // Angka besar supaya semua data terunduh
			$data = $this->suplemen_model->get_rincian(1, $id);
			$data['sasaran'] = unserialize(SASARAN);
			$data['config'] = $this->config_model->get_data();
			$data['pamong_ttd'] = $this->pamong_model->get_data($post['pamong_ttd']);
			$data['pamong_ketahui'] = $this->pamong_model->get_data($post['pamong_ketahui']);
			$data['aksi'] = $aksi;
			$this->session->per_page = $temp;

			$this->load->view('suplemen/cetak', $data);
		}
	}

}
