<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Program_bantuan extends Admin_Controller {

	private $_set_page;
	private $_header;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['header_model', 'program_bantuan_model', 'config_model']);
		$this->modul_ini = 6;
		$this->_set_page = ['20', '50', '100'];
		$this->_header = $this->header_model->get_data();
	}

	public function clear()
	{
		$this->session->per_page = $this->_set_page[0];
		$this->session->sasaran = '';
		redirect('program_bantuan');
	}

	public function filter($filter = '')
	{
		$this->session->$filter = $this->input->post($filter);
		redirect('program_bantuan');
	}

	public function index($p = 1)
	{
		$this->detail_clear();

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data = $this->program_bantuan_model->get_program($p, FALSE);
		$data['tampil'] = 0;
		$data['list_sasaran'] = unserialize(SASARAN);
		$data['p'] = $p;
		$data['func'] = 'index';
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = $this->_set_page;

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('program_bantuan/program', $data);
		$this->load->view('footer');
	}

	public function form($program_id = 0)
	{
		$data['program'] = $this->program_bantuan_model->get_program(1, $program_id);
		$sasaran = $data['program'][0]['sasaran'];
		$nik = $this->input->post('nik');

		if (isset($nik))
		{
			$data['individu'] = $this->program_bantuan_model->get_peserta($nik, $sasaran);
		}
		else
		{
			$data['individu'] = NULL;
		}

		$data['form_action'] = site_url("program_bantuan/add_peserta/".$program_id);

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('program_bantuan/form', $data);
		$this->load->view('footer');
	}

	public function panduan()
	{
		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('program_bantuan/panduan', $data);
		$this->load->view('footer');
	}

	public function detail_clear()
	{
		$this->session->unset_userdata('cari');
		$this->session->per_page = $this->_set_page[0];
	}

	public function detail($program_id = 0, $p = 1)
	{
		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['cari'] = $this->session->cari ?: '';
		$data['program'] = $this->program_bantuan_model->get_program($p, $program_id);
		$data['keyword'] = $this->program_bantuan_model->autocomplete($program_id, $this->input->post('cari'));
		$data['paging'] = $data['program'][0]['paging'];
		$data['p'] = $p;
		$data['func'] = 'detail/'.$program_id;
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = $this->_set_page;
		$this->_header['minsidebar'] = 1;

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('program_bantuan/detail', $data);
		$this->load->view('footer');
	}

	// $id = program_peserta.id
	public function peserta($cat = 0, $id = 0)
	{
		$data = $this->program_bantuan_model->get_peserta_program($cat, $id);

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('program_bantuan/peserta', $data);
		$this->load->view('footer');
	}

	// $id = program_peserta.id
	public function data_peserta($id = 0)
	{
		$data['peserta'] = $this->program_bantuan_model->get_program_peserta_by_id($id);
		$data['individu'] = $this->program_bantuan_model->get_peserta($data['peserta']['peserta'], $data['peserta']['sasaran']);
		$data['detail'] = $this->program_bantuan_model->get_data_program($data['peserta']['program_id']);
		$this->_header['minsidebar'] = 1;

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('program_bantuan/data_peserta', $data);
		$this->load->view('footer');
	}

	public function add_peserta($program_id = 0)
	{
		$this->program_bantuan_model->add_peserta($program_id);

		$redirect = ($this->session->userdata('aksi') != 1) ? $_SERVER['HTTP_REFERER'] : "program_bantuan/detail/$program_id";

		$this->session->unset_userdata('aksi');

		redirect($redirect);
	}

	public function aksi($aksi = '', $program_id = 0)
	{
		$this->session->set_userdata('aksi', $aksi);

		redirect("program_bantuan/form/$program_id");
	}

	public function hapus_peserta($program_id = 0, $peserta_id = '')
	{
		$this->redirect_hak_akses('h', "program_bantuan/detail/$program_id");
		$this->program_bantuan_model->hapus_peserta($peserta_id);
		redirect("program_bantuan/detail/$program_id");
	}

	public function delete_all($program_id = 0)
	{
		$this->redirect_hak_akses('h', "program_bantuan/detail/$program_id");
		$this->program_bantuan_model->delete_all();
		redirect("program_bantuan/detail/$program_id");
	}

	// $id = program_peserta.id
	public function edit_peserta($id = 0)
	{
		$this->program_bantuan_model->edit_peserta($id);
		$program_id = $this->input->post('program_id');
		redirect("program_bantuan/detail/$program_id");
	}

	// $id = program_peserta.id
	public function edit_peserta_form($id = 0)
	{
		$data = $this->program_bantuan_model->get_program_peserta_by_id($id);
		$data['form_action'] = site_url("program_bantuan/edit_peserta/$id");
		$this->load->view('program_bantuan/edit_peserta', $data);
	}

	public function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Program', 'required');
		$this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
		$this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');
		$this->form_validation->set_rules('asaldana', 'Asal Dana', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		$data['asaldana'] = unserialize(ASALDANA);

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('header', $this->_header);
			$this->load->view('nav');
			$this->load->view('program_bantuan/create', $data);
			$this->load->view('footer');
		}
		else
		{
			$this->program_bantuan_model->set_program();
			redirect("program_bantuan");
		}
	}

	// $id = program.id
	public function edit($id = 0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Program', 'required');
		$this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
		$this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');
		$this->form_validation->set_rules('asaldana', 'Asal Dana', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		$data['asaldana'] = unserialize(ASALDANA);
		$data['program'] = $this->program_bantuan_model->get_program(1, $id);
		$data['jml'] = $this->program_bantuan_model->jml_peserta_program($id);

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('header', $this->_header);
			$this->load->view('nav');
			$this->load->view('program_bantuan/edit', $data);
			$this->load->view('footer');
		}
		else
		{
			$this->program_bantuan_model->update_program($id);
			redirect("program_bantuan");
		}
	}

	// $id = program.id
	public function update($id)
	{
		$this->program_bantuan_model->update_program($id);
		redirect("program_bantuan/detail/$id");
	}

	// $id = program.id
	public function hapus($id)
	{
		$this->redirect_hak_akses('h', "program_bantuan");
		$this->program_bantuan_model->hapus_program($id);
		redirect("program_bantuan");
	}

	/*
	 * $aksi = cetak/unduh
	 */
	public function daftar($program_id = 0, $aksi = '')
	{
		if ($program_id > 0)
		{
			$temp = $this->session->per_page;
			$this->session->per_page = 1000000000; // Angka besar supaya semua data terunduh
			$data["sasaran"] = array(
				"1" => "Penduduk",
				"2" => "Keluarga/KK",
				"3" => "Rumah Tangga",
				"4" => "Kelompok/Organisasi Kemasyarakatan"
			);

			$data['config'] = $this->config_model->get_data();
			$data['peserta'] = $this->program_bantuan_model->get_program(1, $program_id);
			$data['aksi'] = $aksi;
			$this->session->per_page = $temp;

			$this->load->view("program_bantuan/$aksi", $data);
		}
	}

	public function search($program_id = 0)
	{
		$cari = $this->input->post('cari');

		if ($cari != '')
			$this->session->cari = $cari;
		else $this->session->unset_userdata('cari');

		redirect("program_bantuan/detail/$program_id");
	}

}
