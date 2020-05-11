<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_umum extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->load->model('pamong_model');
		$this->load->model('web_dokumen_model');
		$this->load->model('surat_masuk_model');
		$this->load->model('surat_keluar_model');
		$this->load->model('penduduk_model');
		$this->load->model('surat_masuk_model');
		$this->load->model('surat_keluar_model');
		$this->load->model('klasifikasi_model');
		$this->load->model('penomoran_surat_model');

		$this->modul_ini = 301;
	}

	public function index()
	{
		$this->tables("peraturan");
	}

	// TABLES
	public function tables($page="peraturan", $page_number=1, $offset=0)
	{
		$this->sub_modul_ini = 302;

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

		// load data for displaying at tables
		$data = array_merge($data, $this->load_data_tables($page, $page_number, $offset));

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('bumindes/umum/main', $data);
		$this->load->view('footer');
	}

	private function load_data_tables($page, $page_number, $offset)
	{
		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'peraturan':
				$data = array_merge($data, $this->load_peraturan_data_tables($page_number, $offset));
				break;

			case 'keputusan':
				$data = array_merge($data, $this->load_keputusan_data_tables($page_number, $offset));
				break;

			case 'aparat':
				$data = array_merge($data, $this->load_aparat_data_tables($page_number, $offset));
				break;

			case 'agenda_masuk':
				$data['selected_nav'] = "agenda";
				$data['selected_tab'] = $page;
				$data = array_merge($data, $this->load_agenda_data_tables($page_number, $offset));
				break;

			case 'agenda_keluar':
				$data['selected_nav'] = "agenda";
				$data['selected_tab'] = $page;
				$data = array_merge($data, $this->load_agenda_data_tables($page_number, $offset));
				break;

			case 'ekspedisi':
				$data = array_merge($data, $this->load_ekspedisi_data_tables($page_number, $offset));
				break;

			case 'berita':
				$data = array_merge($data, $this->load_berita_data_tables($page_number, $offset));
				break;

			default:
				$data = array_merge($data, $this->load_peraturan_data_tables($page_number, $offset));
				break;
		}
		return $data;
	}

	private function load_peraturan_data_tables($page_number, $offset)
	{
		$data['main_content'] = "bumindes/umum/content_dokumen_desa";
		$data['subtitle'] = "Buku Peraturan Desa";

		$data['p'] = $page_number;
		$data['o'] = $offset;
		$data['kat_id'] = "3";
		$data['kat'] = 'peraturan';
		$data['kat_nama'] = $this->web_dokumen_model->kat_nama('3');
		$data['paging'] = $this->web_dokumen_model->paging('3', $page_number, $offset);
		$data['main'] = $this->web_dokumen_model->list_data('3', $o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_dokumen_model->autocomplete();

		return $data;
	}

	private function load_keputusan_data_tables($page_number, $offset)
	{
		$data['main_content'] = "bumindes/umum/content_dokumen_desa";
		$data['subtitle'] = "Buku Keputusan Kepala Desa";

		$data['p'] = $page_number;
		$data['o'] = $offset;
		$data['kat_id'] = "2";
		$data['kat'] = 'keputusan';
		$data['kat_nama'] = $this->web_dokumen_model->kat_nama('2');
		$data['paging'] = $this->web_dokumen_model->paging('2', $page_number, $offset);
		$data['main'] = $this->web_dokumen_model->list_data('2', $o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_dokumen_model->autocomplete();

		return $data;
	}

	private function load_aparat_data_tables($page_number, $offset)
	{
		$data['main_content'] = "bumindes/umum/content_pemerintah_desa";
		$data['subtitle'] = "Buku Aparat Pemerintah Desa";

		$data['main'] = $this->pamong_model->list_data();
		$data['keyword'] = $this->pamong_model->autocomplete();

		return $data;
	}

	private function load_agenda_data_tables($page_number, $offset)
	{
		$data['main_content'] = "bumindes/umum/content_agenda_desa";
		$data['subtitle'] = "Buku Agenda";

		$data['p'] = $page_number;
		$data['o'] = $offset;

		// surat masuk
		$data['paging'] = $this->surat_masuk_model->paging($page_number, $offset);
		$data['main']['in'] = $this->surat_masuk_model->list_data($offset, $data['paging']->offset, $data['paging']->per_page);
		$data['tahun_penerimaan'] = $this->surat_masuk_model->list_tahun_penerimaan();
		$data['keyword']['in'] = $this->surat_masuk_model->autocomplete();

		// surat keluar
		$data['paging'] = $this->surat_keluar_model->paging($page_number, $offset);
		$data['main']['out'] = $this->surat_keluar_model->list_data($offset, $data['paging']->offset, $data['paging']->per_page);
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['keyword']['out'] = $this->surat_keluar_model->autocomplete();

		return $data;
	}

	private function load_ekspedisi_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/umum/content_ekspedisi";
		$data['subtitle'] = "Buku Ekspedisi";

		return $data;
	}

	private function load_berita_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/umum/content_berita";
		$data['subtitle'] = "Buku Lembaran Desa dan Berita Desa";

		return $data;
	}
	// TABLES END

	// FORM
	public function form($page="peraturan", $page_number=1, $offset=0, $key=null)
	{
		$this->sub_modul_ini = 302;

		$data = array();
		$data = array_merge($data, $this->load_form($page, $page_number, $offset, $key));

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('bumindes/umum/main', $data);
		$this->load->view('footer');
	}

	private function load_form($page, $page_number, $offset, $key)
	{
		$data['p'] = $page_number;
		$data['o'] = $offset;

		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'peraturan':
				$data = array_merge($data, $this->load_form_peraturan($page_number, $offset, $key));
				break;

			case 'keputusan':
				$data = array_merge($data, $this->load_form_keputusan($page_number, $offset, $key));
				break;

			case 'aparat':
				$data = array_merge($data, $this->load_form_aparat($page_number, $offset, $key));
				break;

			case 'agenda_masuk':
				$data['selected_nav'] = 'agenda';
				$data = array_merge($data, $this->load_form_agenda_masuk($page_number, $offset, $key));
				break;

			case 'agenda_keluar':
				$data['selected_nav'] = 'agenda';
				$data = array_merge($data, $this->load_form_agenda_keluar($page_number, $offset, $key));
				break;

			case 'ekspedisi':
				$data = array_merge($data, $this->load_form_ekspedisi($page_number, $offset, $key));
				break;

			case 'berita':
				$data = array_merge($data, $this->load_form_berita($page_number, $offset, $key));
				break;

			default:
				$data = array_merge($data, $this->load_form_peraturan($page_number, $offset, $key));
				break;
		}
		return $data;
	}

	function load_form_peraturan($page_number, $offset, $key)
	{
		$data['main_content'] = "bumindes/umum/form_dokumen_desa";
		$data['subtitle'] = "Form Peraturan Desa";

		$data['kat_id'] = "3";
		$data['kat'] = "peraturan";
		$data['kat_nama'] = $this->web_dokumen_model->kat_nama("3");
		$data['list_kategori'] = $this->web_dokumen_model->list_kategori();

		if ($key)
		{
			$data['dokumen'] = $this->web_dokumen_model->get_dokumen($key);
			$data['form_action'] = site_url("bumindes_umum/update/peraturan/$page_number/$offset/$key");
		}
		else
		{
			$data['dokumen'] = null;
			$data['form_action'] = site_url("bumindes_umum/insert/peraturan");
		}
		return $data;
	}

	function load_form_keputusan($page_number, $offset, $key)
	{
		$data['main_content'] = "bumindes/umum/form_dokumen_desa";
		$data['subtitle'] = "Form Keputusan Kepala Desa";

		$data['kat_id'] = "2";
		$data['kat'] = "keputusan";
		$data['kat_nama'] = $this->web_dokumen_model->kat_nama("2");
		$data['list_kategori'] = $this->web_dokumen_model->list_kategori();

		if ($key)
		{
			$data['dokumen'] = $this->web_dokumen_model->get_dokumen($key);
			$data['form_action'] = site_url("bumindes_umum/update/keputusan/$page_number/$offset/$key");
		}
		else
		{
			$data['dokumen'] = null;
			$data['form_action'] = site_url("bumindes_umum/insert/keputusan");
		}
		return $data;
	}

	function load_form_aparat($page_number, $offset, $key)
	{
		$data['main_content'] = "bumindes/umum/form_aparat";
		$data['subtitle'] = "Form Aparat Pemerintah Desa";

		// Select penduduk
		if (!empty($_POST['id_pend']))
			$data['individu'] = $this->penduduk_model->get_penduduk($_POST['id_pend']);
		else
			$data['individu'] = null;

		// Form update
		if ($key)
		{
			$data['pamong'] = $this->pamong_model->get_data($key);
			if (!isset($_POST['id_pend'])) $_POST['id_pend'] = $data['pamong']['id_pend'];
			$data['form_action'] = site_url("bumindes_umum/update/aparat/$page_number/$offset/$key");
		}
		else
		{
			$data['pamong'] = null;
			$data['form_action'] = site_url("bumindes_umum/insert/aparat");
		}

		$data['penduduk'] = $this->pamong_model->list_penduduk();
		$data['pendidikan_kk'] = $this->penduduk_model->list_pendidikan_kk();
		$data['agama'] = $this->penduduk_model->list_agama();

		return $data;
	}

	function load_form_agenda_masuk($page_number, $offset, $key)
	{
		$data['main_content'] = "bumindes/umum/form_surat_masuk";
		$data['subtitle'] = "Form Surat Masuk";

		$data['pengirim'] = $this->surat_masuk_model->autocomplete();
		$data['klasifikasi'] = $this->klasifikasi_model->list_kode();
		$data['p'] = $page_number;
		$data['o'] = $offset;

		if ($key)
		{
			$data['surat_masuk'] = $this->surat_masuk_model->get_surat_masuk($key);
			$data['form_action'] = site_url("bumindes_umum/update/agenda_masuk/$page_number/$offset/$key");
			$data['disposisi_surat_masuk'] = $this->surat_masuk_model->get_disposisi_surat_masuk($key);
		}
		else
		{
			$last_surat = $this->penomoran_surat_model->get_surat_terakhir('surat_masuk');
			$data['surat_masuk']['nomor_urut'] = $last_surat['no_surat'] + 1;
			$data['form_action'] = site_url("bumindes_umum/insert/agenda_masuk");
			$data['disposisi_surat_masuk'] = null;
		}
		$data['ref_disposisi'] = $this->surat_masuk_model->get_pengolah_disposisi();

		// Buang unique id pada link nama file
		$berkas = explode('__sid__', $data['surat_masuk']['berkas_scan']);
		$namaFile = $berkas[0];
		$ekstensiFile = explode('.', end($berkas));
		$ekstensiFile = end($ekstensiFile);
		$data['surat_masuk']['berkas_scan'] = $namaFile.'.'.$ekstensiFile;

		return $data;
	}

	function load_form_agenda_keluar($page_number, $offset, $key)
	{
		$data['main_content'] = "bumindes/umum/form_surat_keluar";
		$data['subtitle'] = "Form Surat Keluar";

		$data['tujuan'] = $this->surat_keluar_model->autocomplete();
		$data['klasifikasi'] = $this->klasifikasi_model->list_kode();
		$data['p'] = $page_number;
		$data['o'] = $offset;

		if ($key)
		{
			$data['surat_keluar'] = $this->surat_keluar_model->get_surat_keluar($key);
			$data['form_action'] = site_url("bumindes_umum/update/agenda_keluar/$page_number/$offset/$key");
		}
		else
		{
			$last_surat = $this->penomoran_surat_model->get_surat_terakhir('surat_keluar');
			$data['surat_keluar']['nomor_urut'] = $last_surat['no_surat'] + 1;
			$data['form_action'] = site_url("bumindes_umum/insert/agenda_keluar");
		}

		// Buang unique id pada link nama file
		$berkas = explode('__sid__', $data['surat_keluar']['berkas_scan']);
		$namaFile = $berkas[0];
		$ekstensiFile = explode('.', end($berkas));
		$ekstensiFile = end($ekstensiFile);
		$data['surat_keluar']['berkas_scan'] = $namaFile.'.'.$ekstensiFile;

		return $data;
	}

	function load_form_ekspedisi($page_number, $offset, $key)
	{

	}

	function load_form_berita($page_number, $offset, $key)
	{

	}

	// FORM END

	// INSERT
	public function insert($page)
	{
		switch (strtolower($page))
		{
			case 'peraturan':
			case 'keputusan':
				$_SESSION['success'] = 1;
				$kat = $this->input->post('kategori');
				$outp = $this->web_dokumen_model->insert();

				if (!$outp) $_SESSION['success'] = -1;

				redirect("bumindes_umum/tables/$page");
				break;

			case 'aparat':
				$this->pamong_model->insert();
				redirect("bumindes_umum/tables/$page");
				break;

			case 'agenda_masuk':
				$this->surat_masuk_model->insert();
				redirect('bumindes_umum/tables/agenda_masuk');
				break;

			case 'agenda_keluar':
				$this->surat_keluar_model->insert();
				redirect('bumindes_umum/tables/agenda_keluar');
				break;

			case 'ekspedisi':

				break;

			case 'berita':

				break;

			default:

				break;
		}
	}
	// INSERT END

	// DELETE
	public function delete($page, $p=1, $o=0, $id='')
	{
		switch (strtolower($page))
		{
			case 'peraturan':
			case 'keputusan':
				$this->redirect_hak_akses('h', "dokumen_sekretariat");
				$this->web_dokumen_model->delete($id);
				redirect("bumindes_umum/tables/$page/$p/$o");
				break;

			case 'aparat':
				$this->redirect_hak_akses('h', 'pengurus');
				$outp = $this->pamong_model->delete($id);
				redirect("bumindes_umum/tables/$page");
				break;

			case 'agenda_masuk':
				$this->redirect_hak_akses('h', "surat_masuk");
				$this->surat_masuk_model->delete($id);
				redirect("bumindes_umum/tables/agenda_masuk");
				break;

			case 'agenda_keluar':
				$this->redirect_hak_akses('h', "surat_keluar");
				$this->surat_keluar_model->delete($id);
				redirect("bumindes_umum/tables/agenda_keluar");
				break;

			case 'ekspedisi':

				break;

			case 'berita':

				break;

			default:

				break;
		}
	}

	public function delete_all($page, $p=1, $o=0)
	{
		switch (strtolower($page))
		{
			case 'peraturan':
			case 'keputusan':
				$this->redirect_hak_akses('h', "dokumen_sekretariat");
				$this->web_dokumen_model->delete_all();
				redirect("bumindes_umum/tables/$page/$p/$o");
				break;

			case 'aparat':
				$this->redirect_hak_akses('h', 'pengurus');
				$this->pamong_model->delete_all();
				redirect("bumindes_umum/tables/$page");
				break;

			case 'agenda_masuk':
				$this->redirect_hak_akses('h', "surat_masuk");
				$this->surat_masuk_model->delete_all();
				redirect("bumindes_umum/tables/agenda_masuk");
				break;

			case 'agenda_keluar':
				$this->redirect_hak_akses('h', "surat_masuk");
				$this->surat_masuk_model->delete_all();
				redirect("bumindes_umum/tables/agenda_keluar");
				break;

			case 'ekspedisi':

				break;

			case 'berita':

				break;

			default:

				break;
		}
	}

	// UPDATE
	public function update($page, $p=1, $o=0, $id='')
	{
		switch (strtolower($page))
		{
			case 'peraturan':
			case 'keputusan':
				$_SESSION['success'] = 1;
				$kategori = $this->input->post('kategori');
				if (!empty($kategori))
					$kat = $this->input->post('kategori');
		  		$outp = $this->web_dokumen_model->update($id);
				if (!$outp) $_SESSION['success'] = -1;
				redirect("bumindes_umum/tables/$page/$p/$o");
				break;

			case 'aparat':
				$this->pamong_model->update($id);
				redirect("bumindes_umum/tables/$page");
				break;

			case 'agenda_masuk':
				$this->surat_masuk_model->update($id);
				redirect("bumindes_umum/tables/agenda_masuk");
				break;

			case 'agenda_keluar':
				$this->surat_keluar_model->update($id);
				redirect("bumindes_umum/tables/agenda_keluar");
				break;

			case 'ekspedisi':

				break;

			case 'berita':

				break;

			default:

				break;
		}
	}
	// UPDATE END

	// DOKUMEN UNLOCK
	public function dokumen_lock($page, $id='')
	{
		$this->web_dokumen_model->dokumen_lock($id, 1);
		redirect("bumindes_umum/tables/$page");
	}

	public function dokumen_unlock($page, $id='')
	{
		$this->web_dokumen_model->dokumen_lock($id, 2);
		redirect("bumindes_umum/tables/$page");
	}

	// FILTER
	public function filter($page)
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect("bumindes_umum/tables/$page");
	}

	// SEARCH
	public function search($page)
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect("bumindes_umum/tables/$page");
	}

	// CETAK
	public function dialog_cetak($page, $o = 0)
	{
		switch (strtolower($page))
		{
			case 'aparat':
				$data['aksi'] = "Cetak";
				$data['pamong'] = $this->pamong_model->list_data(true);
				$data['form_action'] = site_url("pengurus/cetak/$o");
				$this->load->view('home/ajax_cetak_pengurus', $data);
				break;

			case 'agenda_masuk':
				$data['aksi'] = "Cetak";
				$data['pamong'] = $this->pamong_model->list_data(true);
				$data['tahun_surat'] = $this->surat_masuk_model->list_tahun_surat();
				$data['form_action'] = site_url("surat_masuk/cetak/$o");
				$this->load->view('surat_masuk/ajax_cetak', $data);
				break;

			case 'agenda_keluar':
				$data['aksi'] = "Cetak";
				$data['pamong'] = $this->pamong_model->list_data(true);
				$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
				$data['form_action'] = site_url("surat_keluar/cetak/$o");
				$this->load->view('surat_keluar/ajax_cetak', $data);
				break;

			case 'ekspedisi':

				break;

			case 'berita':

				break;

			default:

				break;
		}

	}

	public function dialog_unduh($page, $o = 0)
	{
		switch (strtolower($page))
		{
			case 'aparat':
				$data['aksi'] = "Unduh";
				$data['pamong'] = $this->pamong_model->list_data(true);
				$data['form_action'] = site_url("pengurus/unduh/$o");
				$this->load->view('home/ajax_cetak_pengurus', $data);
				break;

			case 'agenda_masuk':
				$data['aksi'] = "Unduh";
				$data['pamong'] = $this->pamong_model->list_data(true);
				$data['tahun_surat'] = $this->surat_masuk_model->list_tahun_surat();
				$data['form_action'] = site_url("surat_masuk/unduh/$o");
				$this->load->view('surat_masuk/ajax_cetak', $data);
				break;

			case 'agenda_keluar':
				$data['aksi'] = "Unduh";
				$data['pamong'] = $this->pamong_model->list_data(true);
				$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
				$data['form_action'] = site_url("surat_keluar/unduh/$o");
				$this->load->view('surat_keluar/ajax_cetak', $data);
				break;

			case 'ekspedisi':

				break;

			case 'berita':

				break;

			default:

				break;
		}
	}

	public function urut($id = 0, $arah = 0)
	{
		$this->pamong_model->urut($id, $arah);
		redirect("bumindes_umum/tables/aparat");
	}

	public function ttd_on($id = '')
	{
		$this->pamong_model->ttd($id, 1);
		redirect("bumindes_umum/tables/aparat");
	}

	public function ttd_off($id = '')
	{
		$this->pamong_model->ttd($id, 0);
		redirect("bumindes_umum/tables/aparat");
	}

	public function ub_on($id = '')
	{
		$this->pamong_model->ub($id, 1);
		redirect("bumindes_umum/tables/aparat");
	}

	public function ub_off($id = '')
	{
		$this->pamong_model->ub($id, 0);
		redirect("bumindes_umum/tables/aparat");
	}

	public function unduh_berkas_scan($idSuratMasuk)
	{
		// Ambil nama berkas dari database
		$berkas = $this->surat_keluar_model->getNamaBerkasScan($idSuratMasuk);
		ambilBerkas($berkas, 'surat_keluar', '__sid__');
	}

}
