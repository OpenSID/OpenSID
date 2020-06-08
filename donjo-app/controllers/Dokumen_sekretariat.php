<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Dokumen_sekretariat extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('web_dokumen_model');
		$this->load->model('referensi_model');
		$this->modul_ini = 15;
		$this->sub_modul_ini = 95;
	}

	public function index($kat=2, $p=1, $o=0)
	{
		redirect("dokumen_sekretariat/peraturan_desa/$kat/$p/$o");
	}

	// Produk Hukum Desa
	public function peraturan_desa($kat=2, $p=1, $o=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['kat'] = $kat;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['kat_nama'] = $this->web_dokumen_model->kat_nama($kat);
		$data['paging'] = $this->web_dokumen_model->paging($kat, $p, $o);
		$data['main'] = $this->web_dokumen_model->list_data($kat, $o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_dokumen_model->autocomplete();
		$data['submenu'] = $this->referensi_model->list_data('ref_dokumen');
		$data['sub_kategori'] = $_SESSION['sub_kategori'];
    $_SESSION['menu_kategori'] = TRUE;

		foreach ($data['submenu'] as $s)
		{
			if ($kat == $s['id'])
			{
				$_SESSION['submenu'] = $s['id'];
				$_SESSION['sub_kategori'] = $s['kategori'];
				$_SESSION['kode_kategori'] = $s['id'];
			}
		}

		$header = $this->header_model->get_data();
		$this->_set_tab($kat);
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('dokumen/table', $data);
		$this->load->view('footer');
	}

	public function clear($kat=2)
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect("dokumen_sekretariat/peraturan_desa/$kat");
	}

	public function form($kat=2, $p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['kat'] = $kat;
		$data['list_kategori'] = $this->web_dokumen_model->list_kategori();

		if ($id)
		{
			$data['dokumen'] = $this->web_dokumen_model->get_dokumen($id);
			$data['form_action'] = site_url("dokumen_sekretariat/update/$kat/$id/$p/$o");
		}
		else
		{
			$data['dokumen'] = null;
			$data['form_action'] = site_url("dokumen_sekretariat/insert");
		}
		$data['kat_nama'] = $this->web_dokumen_model->kat_nama($kat);
		$header = $this->header_model->get_data();
		$this->_set_tab($kat);

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('dokumen/form', $data);
		$this->load->view('footer');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		$kat = $this->input->post('kategori');
		if ($cari != '')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect("dokumen_sekretariat/index/$kat");
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		$kat = $this->input->post('kategori');
		if ($filter != 0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect("dokumen_sekretariat/index/$kat");
	}

	public function insert()
	{
		$_SESSION['success'] = 1;
		$kat = $this->input->post('kategori');
		$outp = $this->web_dokumen_model->insert();
		if (!$outp) $_SESSION['success'] = -1;
		redirect("dokumen_sekretariat/peraturan_desa/$kat");
	}

	public function update($kat, $id='', $p=1, $o=0)
	{
		$_SESSION['success'] = 1;
		$kategori = $this->input->post('kategori');
		if (!empty($kategori))
			$kat = $this->input->post('kategori');
		$outp = $this->web_dokumen_model->update($id);
		if (!$outp) $_SESSION['success'] = -1;
		redirect("dokumen_sekretariat/peraturan_desa/$kat/$p/$o");
	}

	public function delete($kat=1, $p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "dokumen_sekretariat/index/$kat/$p/$o");
		$this->web_dokumen_model->delete($id);
		redirect("dokumen_sekretariat/peraturan_desa/$kat/$p/$o");
	}

	public function delete_all($kat=1, $p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "dokumen_sekretariat/index/$kat/$p/$o");
		$this->web_dokumen_model->delete_all();
		redirect("dokumen_sekretariat/peraturan_desa/$kat/$p/$o");
	}

	public function dokumen_lock($kat=1, $id='')
	{
		$this->web_dokumen_model->dokumen_lock($id, 1);
		redirect("dokumen_sekretariat/peraturan_desa/$kat/");
	}

	public function dokumen_unlock($kat=1,$id='')
	{
		$this->web_dokumen_model->dokumen_lock($id, 2);
		redirect("dokumen_sekretariat/peraturan_desa/$kat/");
	}

	public function dialog_cetak($kat=1)
	{
		redirect("dokumen/dialog_cetak/$kat");
	}

	public function dialog_excel($kat=1)
	{
		redirect("dokumen/dialog_excel/$kat");
	}

	private function _set_tab($kat)
	{
		switch ($kat)
		{
			case '2':
				$this->tab_ini = 59;
				break;

			case '3':
				$this->tab_ini = 60;
				break;

			default:
				$this->tab_ini = 59;
				break;
		}
	}
}
