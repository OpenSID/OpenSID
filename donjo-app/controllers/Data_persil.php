<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Data_persil extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->load->model('data_persil_model');
		$this->load->model('penduduk_model');
		$this->controller = 'data_persil';
		$this->modul_ini = 7;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		$_SESSION['per_page'] = 20;
		redirect('data_persil');
	}

	public function index($kat=0, $mana=0, $page=1, $o=0)
	{
		$data['kat'] = $kat;
		$data['mana'] = $mana;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data["desa"] = $this->config_model->get_data();
		$data['paging']  = $this->data_persil_model->paging($kat, $mana, $page);
		$data["persil"] = $this->data_persil_model->list_persil($kat, $mana, $data['paging']->offset, $data['paging']->per_page);
		$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
		$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
		$data['keyword'] = $this->data_persil_model->autocomplete();
		$nav['act'] = 7;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('data_persil/persil', $data, $nav);
	}

	public function import()
	{
		$data['form_action'] = site_url("data_persil/import_proses");
		$this->load->view('data_persil/import', $data);
	}

	public function search(){
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('data_persil');
	}

	public function detail($id=0)
	{
		$data["persil_detail"] = $this->data_persil_model->get_persil($id);
		$data["persil_lokasi"] = $this->data_persil_model->list_dusunrwrt();
		$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
		$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
		$nav['act'] = 7;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('data_persil/detail', $data, $nav);
	}

	public function create($id=0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Persil', 'required');

		$data["penduduk"] = $this->data_persil_model->list_penduduk();
		$data["persil_detail"] = $this->data_persil_model->get_persil($id);
		if ($id > 0)
		{
			$data['pemilik'] = $this->data_persil_model->get_penduduk($data["persil_detail"]["id_pend"]);
			$data['pemilik']['nik_lama'] = $data['pemilik']['nik'];
		}
		else
		{
			$data['pemilik'] = false;
		}
		if (isset($_POST['nik']))
		{
			$data['pemilik'] = $this->data_persil_model->get_penduduk($_POST['nik'], $nik=true);
		}
		$data["persil_lokasi"] = $this->data_persil_model->list_dusunrwrt();
		$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
		$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
		$nav['act'] = 7;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('data_persil/create', $data, $nav);
	}

	public function create_ext($id=0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Persil', 'required');

		$data["penduduk"] = $this->data_persil_model->list_penduduk();
		$data["persil_detail"] = $this->data_persil_model->get_persil($id);
		$data["persil_lokasi"] = $this->data_persil_model->list_dusunrwrt();
		$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
		$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
		$nav['act'] = 7;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('data_persil/create_ext', $data, $nav);
	}

	public function simpan_persil($page=1)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Persil', 'required');
		$data["hasil"] = $this->data_persil_model->simpan_persil();
		redirect("data_persil/clear");
	}

	public function persil_jenis($id=0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Persil', 'required');

		$nav['act'] = 7;
		$data["id"] = $id;
		if ($this->form_validation->run() === FALSE)
		{
			$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
			$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
			$data["persil_jenis_detail"] = $this->data_persil_model->get_persil_jenis($id);
			$data["hasil"] = false;
			$tampil = 'data_persil/persil_jenis';
		}
		else
		{
			$data["hasil"] = $this->data_persil_model->update_persil_jenis($id);
			$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
			$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
			$data["persil_jenis_detail"] = $this->data_persil_model->get_persil_jenis($id);
			$tampil = 'data_persil/persil_jenis';
		}
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view($tampil, $data, $nav);
	}

	public function hapus_persil_jenis($id){
		$this->redirect_hak_akses('h', "data_persil/persil_jenis");
		$this->data_persil_model->hapus_jenis($id);
		redirect("data_persil/persil_jenis");
	}

	public function persil_peruntukan($id=0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Persil', 'required');
		$nav['act'] = 7;
		$data["id"] = $id;
		if ($this->form_validation->run() === FALSE)
		{
			$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
			$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
			$data["persil_peruntukan_detail"] = $this->data_persil_model->get_persil_peruntukan($id);
			$data["hasil"] = false;
			$tampil = 'data_persil/persil_peruntukan';
		}
		else
		{
			$data["hasil"] = $this->data_persil_model->update_persil_peruntukan($id);
			$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
			$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
			$data["persil_peruntukan_detail"] = $this->data_persil_model->get_persil_peruntukan($id);
			$tampil = 'data_persil/persil_peruntukan';
		}
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view($tampil, $data, $nav, TRUE);
	}

	public function panduan()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$nav['act'] = 7;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('data_persil/panduan', $data, $nav);
	}

	public function hapus_persil_peruntukan($id)
	{
		$this->redirect_hak_akses('h', "data_persil/persil_peruntukan");
		$this->data_persil_model->hapus_peruntukan($id);
		redirect("data_persil/persil_peruntukan");
	}

	public function hapus($id)
	{
		$this->redirect_hak_akses('h', "data_persil");
		$this->data_persil_model->hapus_persil($id);
		redirect("data_persil");
	}

	public function import_proses()
	{
		$this->data_persil_model->impor_persil();
		redirect("data_persil");
	}

	public function cetak($o=0)
	{
		$data['data_persil'] = $this->data_persil_model->list_persil('', $o, 0, 10000);
		$this->load->view('data_persil/persil_print', $data);
	}

	public function excel($o=0)
	{
		$data['data_persil'] = $this->data_persil_model->list_persil('', $o, 0, 10000);
		$this->load->view('data_persil/persil_excel', $data);
	}

}

?>
