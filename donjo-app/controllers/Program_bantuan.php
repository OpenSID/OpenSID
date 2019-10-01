<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Program_bantuan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('program_bantuan_model');
		$this->modul_ini = 6;
	}

	public function clear($id = 0)
	{
		$_SESSION['per_page'] = 20;
		$this->session->sasaran = '';
		redirect('program_bantuan');
	}

	public function filter($filter)
	{
		$_SESSION[$filter] = $this->input->post($filter);
		redirect('program_bantuan');
	}

	public function index($p = 1)
	{
		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$header = $this->header_model->get_data();
		$nav['act']= 6;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$data = $this->program_bantuan_model->get_program($p, FALSE);
		$data['tampil'] = 0;
		$data['list_sasaran'] = unserialize(SASARAN);
		$data['per_page'] = $_SESSION['per_page'];

		$this->load->view('program_bantuan/program', $data);
		$this->load->view('footer');
	}

	public function form($program_id)
	{
		$data['program'] = $this->program_bantuan_model->get_program(1, $program_id);
		$sasaran = $data['program'][0]['sasaran'];
		if (isset($_POST['nik']))
		{
			$data['individu'] = $this->program_bantuan_model->get_peserta($_POST['nik'], $sasaran);
		}
		else
		{
			$data['individu'] = NULL;
		}

		$header = $this->header_model->get_data();
		$nav['act'] = 6;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);

		$data['form_action'] = site_url("program_bantuan/add_peserta");
		$this->load->view('program_bantuan/form', $data);
		$this->load->view('footer');
	}

	public function panduan()
	{
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$nav['act'] = 6;

		$this->load->view('nav', $nav);
		$this->load->view('program_bantuan/panduan', $data);
		$this->load->view('footer');
	}

	private function detail_clear()
	{
		unset($_SESSION['cari_peserta']);
		$_SESSION['per_page'] = 20;
	}

	public function detail($p = 1, $id, $clear=false)
	{
		if ($clear)
			$this->detail_clear();
		else
		{
			if (isset($_SESSION['cari_peserta']))
				$data['cari_peserta'] = $_SESSION['cari_peserta'];
			else $data['cari_peserta'] = '';
		}

		$header = $this->header_model->get_data();
		$nav['act'] = 6;
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$data['per_page'] = $_SESSION['per_page'];
		$data['program'] = $this->program_bantuan_model->get_program($p, $id);
		$data['paging'] = $data['program'][0]['paging'];
		$this->load->view('program_bantuan/detail', $data);
		$this->load->view('footer');
	}

	public function peserta($cat = 0, $id = 0)
	{
		$header = $this->header_model->get_data();
		$nav['act']= 6;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);

		$data = $this->program_bantuan_model->get_peserta_program($cat, $id);

		$this->load->view('program_bantuan/peserta', $data);
		$this->load->view('footer');
	}

	public function data_peserta($id)
	{
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$data['peserta'] = $this->program_bantuan_model->get_program_peserta_by_id($id);
		$data['individu'] = $this->program_bantuan_model->get_peserta($data['peserta']['peserta'], $data['peserta']['sasaran']);
		$data['detail'] = $this->program_bantuan_model->get_data_program($data['peserta']['program_id']);
		$this->load->view('program_bantuan/data_peserta', $data);
		$this->load->view('footer');
	}

	public function add_peserta($id)
	{
		$this->program_bantuan_model->add_peserta($_POST, $id);
		redirect("program_bantuan/detail/1/$id/1");
	}

	public function hapus_peserta($id, $peserta_id)
	{
		$this->redirect_hak_akses('h', "program_bantuan/detail/1/$id");
		$this->program_bantuan_model->hapus_peserta($peserta_id);
		redirect("program_bantuan/detail/1/$id/1");
	}

	public function edit_peserta($id)
	{
		$this->program_bantuan_model->edit_peserta($_POST, $id);
		$program_id = $_POST['program_id'];
		redirect("program_bantuan/detail/1/$program_id");
	}

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

		$header = $this->header_model->get_data();

		$nav['act'] = 6;
		$data['asaldana'] = unserialize(ASALDANA);
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		if ($this->form_validation->run() === FALSE){
			$this->load->view('program_bantuan/create', $data);
		}
		else
		{
			$this->program_bantuan_model->set_program();
			redirect("program_bantuan/");
		}
		$this->load->view('footer');
	}

	public function edit($id)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Program', 'required');
		$this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
		$this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');
		$this->form_validation->set_rules('asaldana', 'Asal Dana', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		$header = $this->header_model->get_data();

		$nav['act'] = 6;
		$data['asaldana'] = unserialize(ASALDANA);
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$data['program'] = $this->program_bantuan_model->get_program(1, $id);

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('program_bantuan/edit', $data);
		}
		else
		{
			$this->program_bantuan_model->update_program($id);
			redirect("program_bantuan/");
		}

		$this->load->view('footer');
	}
	public function update($id)
	{
		$this->program_bantuan_model->update_program($id);
		redirect("program_bantuan/detail/1/".$id);
	}

	public function hapus($id)
	{
		$this->redirect_hak_akses('h', "program_bantuan/");
		$this->program_bantuan_model->hapus_program($id);
		redirect("program_bantuan/");
	}

	public function unduhsheet($id = 0)
	{
		if ($id > 0)
		{
			$temp = $_SESSION['per_page'];
			$_SESSION['per_page'] = 1000000000; // Angka besar supaya semua data terunduh
			/*
			 * Print xls untuk data x
			 * */
			$data["sasaran"] = array(
				"1" => "Penduduk",
				"2" => "Keluarga/KK",
				"3" => "Rumah Tangga",
				"4" => "Kelompok/Organisasi Kemasyarakatan"
			);
			$data['desa'] = $this->header_model->get_data();
			$data['peserta'] = $this->program_bantuan_model->get_program(1, $id);
			$_SESSION['per_page'] = $temp;
			$this->load->view('program_bantuan/unduh-sheet', $data);
		}
	}

	public function search_peserta()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari_peserta'] = $cari;
		else unset($_SESSION['cari_peserta']);
		redirect("program_bantuan/detail/1/".$this->input->post('id'));
	}
}
