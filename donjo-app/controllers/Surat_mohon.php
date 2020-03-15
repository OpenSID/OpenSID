<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_mohon extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('lapor_model');
		$this->modul_ini = 4;
		$this->sub_modul_ini = 97;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('surat_mohon');
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

		$data['paging'] = $this->lapor_model->paging($p, $o);
		$data['main'] = $this->lapor_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->lapor_model->autocomplete();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('surat_master/surat_mohon_table', $data);
		$this->load->view('footer');
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['ref_syarat_surat'] = $this->lapor_model->get_surat($id);
			$data['form_action'] = site_url("surat_mohon/update/$p/$o/$id");
		}
		else
		{
			$data['ref_syarat_surat'] = NULL;
			$data['form_action'] = site_url("surat_mohon/insert");
		}

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('surat_master/surat_mohon_form', $data);
		$this->load->view('footer');
	}

	public function form_upload($p = 1, $o = 0, $url = '')
	{
		$data['form_action'] = site_url("surat_mohon/upload/$p/$o/$url");
		$this->load->view('surat_master/ajax-upload-mohon', $data);
	}

	public function upload($p = 1, $o = 0, $url = '')
	{
		$this->surat_master_model->upload($url);
		redirect("surat_mohon/index/$p/$o");
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('surat_mohon');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('surat_mohon');
	}

	public function insert()
	{
		$this->lapor_model->insert_ref_surat();
		redirect('surat_mohon');
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$this->lapor_model->update($id);
		redirect("surat_mohon/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "surat_mohon/index/$p/$o");
		$this->lapor_model->delete($id);
		redirect("surat_mohon/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "surat_mohon/index/$p/$o");
		$this->lapor_model->delete_all();
		redirect("surat_mohon/index/$p/$o");
	}

	public function user_lock($id = '')
	{
		$this->lapor_model->user_lock($id, 0);
		redirect("surat_mohon/index/$p/$o");
	}

	public function user_unlock($id = '')
	{
		$this->lapor_model->user_lock($id, 1);
		redirect("surat_mohon/index/$p/$o");
	}

}
