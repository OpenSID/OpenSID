<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_pembangunan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->load->model('administrasi_tanah_desa_model');
		$this->load->model('administrasi_tanah_kas_desa_model');
		$this->load->model('pamong_model');
		$this->load->model('cdesa_model');

		$this->modul_ini = 301;
	}

	public function index()
	{
		$this->tables("tanah");
	}

	public function tables($page="tanah", $page_number=1, $offset=0)
	{
		$this->sub_modul_ini = 305;

		// set session
		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		// set session END

		// // load data for displaying at tables
		$data = array_merge($data, $this->load_data_tables($page, $page_number, $offset));

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	private function load_data_tables($page=null, $page_number=1, $offset=0)
	{
		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'tanah':
				$data = array_merge($data, $this->load_tanah_data_tables());
				break;

			case 'tanah_kas':
				$data = array_merge($data, $this->load_tanah_kas_data_tables());
				break;

			case 'mutasi_tanah_kas':
				$data = array_merge($data, $this->load_input_tanah_kas_mutasi_data_tables());
				break;

			default:
				$data = array_merge($data, $this->load_tanah_data_tables());
				break;
		}
		return $data;
	}

	private function load_tanah_data_tables()
	{
		$data = [
			'main_content' => "bumindes/pembangunan/tanah_di_desa/content_tanah_di_desa",
			'subtitle' => ["Buku Tanah di Desa",0],
			'main' => $this->administrasi_tanah_desa_model->list_tanah_desa(),
		];

		return $data;
	}

	private function load_tanah_kas_data_tables()
	{
		$data = [
			'main_content' => "bumindes/pembangunan/tanah_kas_desa/content_tanah_kas_desa",
			'subtitle' => ["Buku Tanah kas Desa",0],
			'main' => $this->administrasi_tanah_kas_desa_model->list_tanah_kas_desa(),
		];

		return $data;
	}

	private function load_input_tanah_kas_mutasi_data_tables()
	{
		$data = [
			'main_content' => "bumindes/pembangunan/mutasi_tanah_kas_desa/content_mutasi_tanah_kas_desa",
			'subtitle' => ["Buku Tanah kas Desa",0],		
		];

		return $data;
	}

	public function view_tanah_desa($id)
	{		
		$data = [
			'main_content' => "bumindes/pembangunan/tanah_di_desa/view_tanah_di_desa",			
			'subtitle' => ["bumindes_pembangunan/tables/tanah","Buku Tanah di Desa","Rincian Data"],
			'main' => $this->administrasi_tanah_desa_model->view_tanah_desa_by_id($id),
			'selected_nav' => 'tanah'
		];
		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function form_tanah_desa()
	{
		$data = [
			'penduduk' => $this->cdesa_model->list_penduduk(),
			'main_content' => "bumindes/pembangunan/tanah_di_desa/form_tambah_tanah_di_desa",
			'subtitle' => ["bumindes_pembangunan/tables/tanah","Buku Tanah di Desa","Isi Data"],
			'selected_nav' => 'tanah'
		];
		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function add_tanah_desa()
	{
		$data = $this->administrasi_tanah_desa_model->add_tanah_desa();

		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("bumindes_pembangunan/tables/tanah");
	}

	public function edit_tanah_desa($id)
	{	
		$data = [
			'main_content' => "bumindes/pembangunan/tanah_di_desa/edit_tanah_di_desa",
			'main' => $this->administrasi_tanah_desa_model->view_tanah_desa_by_id($id),
			'subtitle' => ["bumindes_pembangunan/tables/tanah","Buku Tanah di Desa","Ubah Data"],
			'selected_nav' => 'tanah'
		];
		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function update_tanah_desa()
	{		
		$data = $this->administrasi_tanah_desa_model->update_tanah_desa();
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("bumindes_pembangunan/tables/tanah");
	}

	public function delete_tanah_desa($id)
	{
		$this->redirect_hak_akses('h', 'bumindes_pembangunan/tables/tanah');
		$data = $this->administrasi_tanah_desa_model->delete_tanah_desa($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('bumindes_pembangunan/tables/tanah');
	}

	public function cetak_tanah_desa($date,$aksi)
	{
		$data = [	
			'aksi' => $aksi,		
			'config' => $this->header['desa'],
			'pamong_ketahui' => $this->pamong_model->get_ttd(),
			'pamong_ttd' => $this->pamong_model->get_ub(),
			'main' => $this->administrasi_tanah_desa_model->cetak_tanah_desa(),
			'bulan' => $this->session->filter_bulan,
			'tahun' => $this->session->filter_tahun,
			'tgl_cetak' => $date,	
			'file' => "Buku Tanah di Desa",
			'isi' => "bumindes/pembangunan/tanah_di_desa/tanah_di_desa_cetak",
			'letak_ttd' => ['1', '1', '7'],
		];		
		// var_dump($data);
		$this->load->view('global/format_cetak', $data);		
	}

	public function view_tanah_kas_desa($id)
	{
		$data = [
			'main_content' => "bumindes/pembangunan/tanah_kas_desa/view_tanah_kas_desa",
			'subtitle' => ["bumindes_pembangunan/tables/tanah_kas","Buku Tanah Kas Desa","Rincian Data"],
			'main' => $this->administrasi_tanah_desa_model->view_tanah_kas_desa_by_id($id),
			'selected_nav' => 'tanah_kas'
		];
		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}
	public function form_tanah_kas_desa()
	{		
		$data = [
			'main_content' => "bumindes/pembangunan/tanah_kas_desa/form_tambah_tanah_kas_desa",
			'subtitle' => ["bumindes_pembangunan/tables/tanah_kas","Buku Tanah Kas Desa","Isi Data"],
			'selected_nav' => 'tanah_kas'
		];
		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function add_tanah_kas_desa()
	{
		$data = $this->administrasi_tanah_desa_model->add_tanah_kas_desa();

		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("bumindes_pembangunan/tables/tanah_kas");
	}

	public function edit_tanah_kas_desa($id)
	{
		$data = [
			'main_content' => "bumindes/pembangunan/tanah_kas_desa/edit_tanah_kas_desa",
			'main' => $this->administrasi_tanah_desa_model->view_tanah_kas_desa_by_id($id),
			'subtitle' => ["bumindes_pembangunan/tables/tanah_kas","Buku Tanah Kas Desa","Ubah Data"],
			'selected_nav' => 'tanah_kas'
		];
		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function update_tanah_kas_desa()
	{		
		$data = $this->administrasi_tanah_desa_model->update_tanah_kas_desa();
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("bumindes_pembangunan/tables/tanah_kas");
	}

	public function delete_tanah_kas_desa($id)
	{
		$this->redirect_hak_akses('h', 'bumindes_pembangunan/tables/tanah_kas');
		$data = $this->administrasi_tanah_desa_model->delete_tanah_kas_desa($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('bumindes_pembangunan/tables/tanah_kas');
	}

	public function cetak_tanah_kas_desa($date,$aksi)
	{
		$data = [	
			'aksi' => $aksi,		
			'config' => $this->header['desa'],
			'pamong_ketahui' => $this->pamong_model->get_ttd(),
			'pamong_ttd' => $this->pamong_model->get_ub(),
			'main' => $this->administrasi_tanah_desa_model->cetak_tanah_kas_desa(),
			'bulan' => $this->session->filter_bulan,
			'tahun' => $this->session->filter_tahun,
			'tgl_cetak' => $date,	
			'file' => "Buku Tanah Kas Desa",
			'isi' => "bumindes/pembangunan/tanah_kas_desa/tanah_kas_desa_cetak",
			'letak_ttd' => ['1', '1', '8'],
		];		
		$this->load->view('global/format_cetak', $data);		
	}
}
