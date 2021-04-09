<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_tanah_desa extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->load->model('tanah_desa_model');
		$this->load->model('tanah_kas_desa_model');
		$this->load->model('pamong_model');
		$this->load->model('cdesa_model');

		$this->modul_ini = 301;
		$this->sub_modul_ini = 305;
	}

	public function index()
	{
		$this->tables("tanah");
	}

	public function tables($page="tanah")
	{
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

		$data = [
			'selected_nav' => $page,
			'main' => $this->tanah_desa_model->list_tanah_desa(),	
			'main_content' => "bumindes/pembangunan/tanah_di_desa/content_tanah_di_desa",
			'subtitle' => ["Buku Tanah di Desa",0],
		];

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function view_tanah_desa($id)
	{		
		$data = [
			'main' 		   => $this->tanah_desa_model->view_tanah_desa_by_id($id),
			'main_content' => "bumindes/pembangunan/tanah_di_desa/form_tanah_di_desa",
			'subtitle'	   => ["bumindes_tanah_desa/tables/tanah","Buku Tanah di Desa","Rincian Data"],
			'selected_nav' => 'tanah',
			'view_mark'	   => TRUE,
		];
		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function form($id = ''){
		if ($id)
		{
			$data = [
				'main' 		   => $this->tanah_desa_model->view_tanah_desa_by_id($id),
				'main_content' => "bumindes/pembangunan/tanah_di_desa/form_tanah_di_desa",				
				'subtitle' 	   => ["bumindes_tanah_desa/tables/tanah","Buku Tanah di Desa","Ubah Data"],
				'selected_nav' => 'tanah',
				'form_action'  => site_url("bumindes_tanah_desa/update_tanah_desa"), 
			];
		}
		else
		{
			$data = [
				'main' 		   => NULL,
				'main_content' => "bumindes/pembangunan/tanah_di_desa/form_tanah_di_desa",
				'subtitle'	   => ["bumindes_tanah_desa/tables/tanah","Buku Tanah di Desa","Isi Data"],
				'selected_nav' => 'tanah',
				'form_action'  => site_url("bumindes_tanah_desa/add_tanah_desa"),
			];

		}

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);		
	}

	public function add_tanah_desa()
	{
		$data = $this->tanah_desa_model->add_tanah_desa();

		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("bumindes_tanah_desa/tables/tanah");
	}

	public function update_tanah_desa()
	{		
		$data = $this->tanah_desa_model->update_tanah_desa();
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("bumindes_tanah_desa/tables/tanah");
	}

	public function delete_tanah_desa($id)
	{
		$this->redirect_hak_akses('h', 'bumindes_tanah_desa/tables/tanah');
		$data = $this->tanah_desa_model->delete_tanah_desa($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('bumindes_tanah_desa/tables/tanah');
	}

	public function ajax_cetak($aksi = '')
	{
		// pengaturan data untuk dialog cetak/unduh
		$data = [			
			'aksi' => $aksi,
			'form_action' => site_url("bumindes_tanah_desa/cetak_tanah_desa/$aksi"),			
			'isi' => "bumindes/pembangunan/tanah_di_desa/ajax_dialog_tanah_di_desa",
		];

		$this->load->view('global/dialog_cetak', $data);
	}

	public function cetak_tanah_desa($aksi = '')
	{
		$data = [	
			'aksi' => $aksi,		
			'config' => $this->header['desa'],
			'pamong_ketahui' => $this->pamong_model->get_ttd(),
			'pamong_ttd' => $this->pamong_model->get_ub(),
			'main' => $this->tanah_desa_model->cetak_tanah_desa(),
			'bulan' => $this->session->filter_bulan,
			'tahun' => $this->session->filter_tahun,
			'tgl_cetak' => $_POST['tgl_cetak'],	
			'file' => "Buku Tanah di Desa",
			'isi' => "bumindes/pembangunan/tanah_di_desa/tanah_di_desa_cetak",
			'letak_ttd' => ['1', '1', '7'],
		];					
		$this->load->view('global/format_cetak', $data);		
	}
}
