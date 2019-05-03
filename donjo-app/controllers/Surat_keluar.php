<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_keluar extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		// Untuk bisa menggunakan helper force_download()
		$this->load->helper('download');
		$this->load->model('surat_keluar_model');
		$this->load->model('klasifikasi_model');
		$this->load->model('config_model');
		$this->load->model('pamong_model');
		$this->load->model('header_model');
		$this->load->model('penomoran_surat_model');
		$this->modul_ini = 15;
		$this->tab_ini = 2;
	}

	public function clear($id = 0)
	{
		$_SESSION['per_page'] = 20;
		$_SESSION['surat'] = $id;
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('surat_keluar');
	}

	public function index($p = 1, $o = 2)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$data['per_page'] = $_SESSION['per_page'];
		$data['paging'] = $this->surat_keluar_model->paging($p, $o);
		$data['main'] = $this->surat_keluar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['keyword'] = $this->surat_keluar_model->autocomplete();
		$header = $this->header_model->get_data();
		$nav['act'] = 15;
		$nav['act_sub'] = 58;
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('surat_keluar/table', $data);
		$this->load->view('footer');
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['tujuan'] = $this->surat_keluar_model->autocomplete();
		$data['klasifikasi'] = $this->klasifikasi_model->list_kode();
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['surat_keluar'] = $this->surat_keluar_model->get_surat_keluar($id);
			$data['form_action'] = site_url("surat_keluar/update/$p/$o/$id");
		}
		else
		{
			$last_surat = $this->penomoran_surat_model->get_surat_terakhir('surat_keluar');
			$data['surat_keluar']['nomor_urut'] = $last_surat['no_surat'] + 1;
			$data['form_action'] = site_url("surat_keluar/insert");
		}
		$header = $this->header_model->get_data();

		// Buang unique id pada link nama file
		$berkas = explode('__sid__', $data['surat_keluar']['berkas_scan']);
		$namaFile = $berkas[0];
		$ekstensiFile = explode('.', end($berkas));
		$ekstensiFile = end($ekstensiFile);
		$data['surat_keluar']['berkas_scan'] = $namaFile.'.'.$ekstensiFile;
		$nav['act'] = 15;
		$nav['act_sub'] = 58;
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$nav['act'] = $this->tab_ini;
		$this->load->view('nav', $nav);
		$this->load->view('surat_keluar/form', $data);
		$this->load->view('footer');
	}

	public function form_upload($p = 1, $o = 0, $url = '')
	{
		$data['form_action'] = site_url("surat_keluar/upload/$p/$o/$url");
		$this->load->view('surat_keluar/ajax-upload', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('surat_keluar');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0) $_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('surat_keluar');
	}

	public function insert()
	{
		$this->surat_keluar_model->insert();
		redirect('surat_keluar');
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$this->surat_keluar_model->update($id);
		redirect("surat_keluar/index/$p/$o");
	}

	public function upload($p = 1, $o = 0, $url = '')
	{
		$this->surat_keluar_model->upload($url);
		redirect("surat_keluar/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "surat_keluar/index/$p/$o");
		$this->surat_keluar_model->delete($id);
		redirect("surat_keluar/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h',"surat_keluar/index/$p/$o");
		$this->surat_keluar_model->delete_all();
		redirect("surat_keluar/index/$p/$o");
	}

	public function dialog_cetak($o = 0)
	{
		$data['aksi'] = "Cetak";
		$data['pamong'] = $this->pamong_model->list_data(true);
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['form_action'] = site_url("surat_keluar/cetak/$o");
		$this->load->view('surat_keluar/ajax_cetak', $data);
	}

	public function dialog_unduh($o = 0)
	{
		$data['aksi'] = "Unduh";
		$data['pamong'] = $this->pamong_model->list_data(true);
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['form_action'] = site_url("surat_keluar/unduh/$o");
		$this->load->view('surat_keluar/ajax_cetak', $data);
	}

	public function cetak($o = 0)
	{
		$data['input'] = $_POST;
		$_SESSION['filter'] = $data['input']['tahun'];
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
		$data['desa'] = $this->config_model->get_data();
		$data['main'] = $this->surat_keluar_model->list_data($o, 0, 10000);
		$this->load->view('surat_keluar/surat_keluar_print', $data);
	}

	public function unduh($o = 0)
	{
		$data['input'] = $_POST;
		$_SESSION['filter'] = $data['input']['tahun'];
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
		$data['desa'] = $this->config_model->get_data();
		$data['main'] = $this->surat_keluar_model->list_data($o, 0, 10000);
		$this->load->view('surat_keluar/surat_keluar_excel', $data);
	}

	/**
	 * Unduh berkas scan berdasarkan kolom surat_keluar.id
	 * @param   integer  $idSuratMasuk  Id berkas scan pada koloam surat_keluar.id
	 * @return  void
	 */
	public function unduh_berkas_scan($idSuratMasuk)
	{
		// Ambil nama berkas dari database
		$berkas = $this->surat_keluar_model->getNamaBerkasScan($idSuratMasuk);
		ambilBerkas($berkas, 'surat_keluar', '__sid__');
	}

	public function nomor_surat_duplikat()
	{
		if ($_POST['nomor_urut'] == $_POST['nomor_urut_lama'])
			$hasil = false;
		else
			$hasil = $this->penomoran_surat_model->nomor_surat_duplikat('surat_keluar', $_POST['nomor_urut']);
   	echo $hasil ? 'false' : 'true';
	}

}
