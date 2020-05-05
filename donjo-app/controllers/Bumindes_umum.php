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

			case 'agenda':
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

			case 'agenda':
				$data = array_merge($data, $this->load_form_agenda($page_number, $offset, $key));
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
			$data['form_action'] = site_url("bumindes_umum/update/peraturan/$key/$page_number/$offset");
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
			$data['form_action'] = site_url("bumindes_umum/update/keputusan/$key/$page_number/$offset");
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

	}

	function load_form_agenda($page_number, $offset, $key)
	{

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

				break;

			case 'agenda':

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

				break;

			case 'agenda':

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

				break;

			case 'agenda':

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
	public function update($page, $id='', $p=1, $o=0)
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

				break;

			case 'agenda':

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
	public function filter()
	{
		$filter = $this->input->post('filter');
		$page = $this->input->post('kategori');
		if ($filter != 0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect("bumindes_umum/tables/$page");
	}

	// SEARCH
	public function search()
	{
		$cari = $this->input->post('cari');
		$page = $this->input->post('kategori');
		if ($cari != '')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect("bumindes_umum/tables/$page");
	}

}
