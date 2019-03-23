<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_master extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('surat_master_model');
		$this->load->model('klasifikasi_model');
		$this->load->model('header_model');
		$this->modul_ini = 4;
	}

	public function clear($id = 0)
	{
		$_SESSION['per_page'] = 20;
		$_SESSION['surat'] = $id;
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('surat_master');
	}

	public function index($p = 1, $o = 0)
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
		$data['paging'] = $this->surat_master_model->paging($p, $o);
		$data['main'] = $this->surat_master_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->surat_master_model->autocomplete();
		$header = $this->header_model->get_data();
		$nav['act'] = 4;
		$nav['act_sub'] = 30;
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('surat_master/table', $data);
		$this->load->view('footer');
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['klasifikasi'] = $this->klasifikasi_model->list_kode();

		if ($id)
		{
			$data['surat_master'] = $this->surat_master_model->get_surat_format($id);
			$data['form_action'] = site_url("surat_master/update/$p/$o/$id");
		}
		else
		{
			$data['surat_master'] = NULL;
			$data['form_action'] = site_url("surat_master/insert");
		}

		$header = $this->header_model->get_data();
		$nav['act'] = 4;
		$nav['act_sub'] = 30;
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('surat_master/form', $data);
		$this->load->view('footer');
	}

	public function form_upload($p = 1, $o = 0, $url = '')
	{
		$data['form_action'] = site_url("surat_master/upload/$p/$o/$url");
		$this->load->view('surat_master/ajax-upload', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('surat_master');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('surat_master');
	}

	public function insert()
	{
		$this->surat_master_model->insert();
		redirect('surat_master');
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$this->surat_master_model->update($id);
		redirect("surat_master/index/$p/$o");
	}

	public function upload($p = 1, $o = 0, $url = '')
	{
		$this->surat_master_model->upload($url);
		redirect("surat_master/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "surat_master/index/$p/$o");
		$this->surat_master_model->delete($id);
		redirect("surat_master/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "surat_master/index/$p/$o");
		$this->surat_master_model->delete_all();
		redirect("surat_master/index/$p/$o");
	}

	public function p_insert($in = '')
	{
		$this->surat_master_model->p_insert($in);
		redirect("surat_master/atribut/$in");
	}

	public function p_update($in = '', $id = '')
	{
		$this->surat_master_model->p_update($id);
		redirect("surat_master/atribut/$in");
	}

	public function p_delete($in = '', $id = '')
	{
		$this->redirect_hak_akses('h', "surat_master/atribut/$in");
		$this->surat_master_model->p_delete($id);
		redirect("surat_master/atribut/$in");
	}

	public function p_delete_all()
	{
		$this->redirect_hak_akses('h', "surat_master/atribut/$in");
		$this->surat_master_model->p_delete_all();
		redirect("surat_master/atribut/$in");
	}

	public function kode_isian($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		if ($id)
		{
			$data['surat_master'] = $this->surat_master_model->get_surat_format($id);
			$data['inputs'] = $this->surat_master_model->get_kode_isian($data['surat_master']);
		}

		$header = $this->header_model->get_data();
		$nav['act'] = 4;
		$nav['act_sub'] = 30;
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('surat_master/kode_isian', $data);
		$this->load->view('footer');
	}

	public function lock($id = 0, $k = 0)
	{
		$this->surat_master_model->lock($id, $k);
		redirect("surat_master");
	}

	public function favorit($id = 0, $k = 0)
	{
		$this->surat_master_model->favorit($id, $k);
		redirect("surat_master");
	}

}
