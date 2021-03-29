<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_tanah_kas_desa extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->load->model('tanah_kas_desa_model');
		$this->load->model('pamong_model');
		$this->load->model('cdesa_model');

		$this->modul_ini = 301;
		$this->sub_modul_ini = 305;
	}

	public function index()
	{
		$this->tables("tanah_kas");
	}

	public function tables($page="tanah_kas")
	{
		$data = [
			'selected_nav' => $page,
			'main_content' => "bumindes/pembangunan/tanah_kas_desa/content_tanah_kas_desa",
			'subtitle' => ["Buku Tanah kas Desa",0],
			'main' => $this->tanah_kas_desa_model->list_tanah_kas_desa(),
		];

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function view_tanah_kas_desa($id)
	{
		$data = [
			'main' 		   => $this->tanah_kas_desa_model->view_tanah_kas_desa_by_id($id),
			'main_content' => "bumindes/pembangunan/tanah_kas_desa/form_tanah_kas_desa",
			'subtitle'	   => ["bumindes_tanah_kas_desa/tables/tanah_kas","Buku Tanah Kas Desa","Rincian Data"],
			'selected_nav' => 'tanah_kas',
			'view_mark'	   => TRUE,
		];

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function form($id = ''){
		if ($id)
		{
			$data = [
				'main' 		   => $this->tanah_kas_desa_model->view_tanah_kas_desa_by_id($id),
				'main_content' => "bumindes/pembangunan/tanah_kas_desa/form_tanah_kas_desa",
				'subtitle'	   => ["bumindes_tanah_kas_desa/tables/tanah_kas","Buku Tanah Kas Desa","Ubah Data"],
				'selected_nav' => 'tanah_kas',
				'form_action'  => site_url("bumindes_tanah_kas_desa/update_tanah_kas_desa"), 
			];
		}
		else
		{
			$data = [
				'main' 		   => NULL,
				'main_content' => "bumindes/pembangunan/tanah_kas_desa/form_tanah_kas_desa",
				'subtitle'	   => ["bumindes_tanah_kas_desa/tables/tanah_kas","Buku Tanah Kas Desa","Isi Data"],
				'selected_nav' => 'tanah_kas',
				'form_action'  => site_url("bumindes_tanah_kas_desa/add_tanah_kas_desa"),
			];

		}

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);		
	}

	public function add_tanah_kas_desa()
	{
		$data = $this->tanah_kas_desa_model->add_tanah_kas_desa();

		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("bumindes_tanah_kas_desa/tables/tanah_kas");
	}

	public function update_tanah_kas_desa()
	{		
		$data = $this->tanah_kas_desa_model->update_tanah_kas_desa();
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("bumindes_tanah_kas_desa/tables/tanah_kas");
	}

	public function delete_tanah_kas_desa($id)
	{
		$this->redirect_hak_akses('h', 'bumindes_tanah_kas_desa/tables/tanah_kas');
		$data = $this->tanah_kas_desa_model->delete_tanah_kas_desa($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('bumindes_tanah_kas_desa/tables/tanah_kas');
	}

	public function ajax_cetak_tanah_kas_desa($aksi = '')
	{
		// pengaturan data untuk dialog cetak/unduh
		$data = [			
			'aksi' => $aksi,
			'form_action' => site_url("bumindes_tanah_kas_desa/cetak_tanah_kas_desa/$aksi"),			
			'isi' => "bumindes/pembangunan/tanah_kas_desa/ajax_dialog_tanah_kas_desa",
		];

		$this->load->view('global/dialog_cetak', $data);
	}

	public function cetak_tanah_kas_desa($aksi)
	{
		$data = [	
			'aksi' => $aksi,		
			'config' => $this->header['desa'],
			'pamong_ketahui' => $this->pamong_model->get_ttd(),
			'pamong_ttd' => $this->pamong_model->get_ub(),
			'main' => $this->tanah_kas_desa_model->cetak_tanah_kas_desa(),
			'bulan' => $this->session->filter_bulan,
			'tahun' => $this->session->filter_tahun,
			'tgl_cetak' => $_POST['tgl_cetak'],	
			'file' => "Buku Tanah Kas Desa",
			'isi' => "bumindes/pembangunan/tanah_kas_desa/tanah_kas_desa_cetak",
			'letak_ttd' => ['1', '1', '8'],
		];		
		$this->load->view('global/format_cetak', $data);		
	}
}
