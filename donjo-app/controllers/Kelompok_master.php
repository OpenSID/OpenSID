<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Kelompok_master extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('kelompok_master_model');
		$this->load->model('header_model');
		$this->modul_ini = 2;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['state']);
		redirect('kelompok_master');
	}

	public function index($p=1, $o=0)
	{
    unset($_SESSION['kelompok_master']);
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
		if (isset($_SESSION['state']))
			$data['state'] = $_SESSION['state'];
		else $data['state'] = '';
		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->kelompok_master_model->paging($p,$o);
		$data['main']    = $this->kelompok_master_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->kelompok_master_model->autocomplete();
		$nav['act'] = 2;
		$nav['act_sub'] = 24;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('kelompok_master/table', $data);
		$this->load->view('footer');
	}

	public function form($p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['kelompok_master'] = $this->kelompok_master_model->get_kelompok_master($id);
			$data['form_action'] = site_url("kelompok_master/update/$p/$o/$id");
		}
		else
		{
			$data['kelompok_master'] = null;
			$data['form_action'] = site_url("kelompok_master/insert");
		}

		$nav['act'] = 2;
		$nav['act_sub'] = 24;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('kelompok_master/form', $data);
		$this->load->view('footer');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('kelompok_master');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('kelompok_master');
	}

	public function state(){
		$filter = $this->input->post('state');
		if ($filter != 0)
			$_SESSION['state'] = $filter;
		else unset($_SESSION['state']);
		redirect('kelompok_master');
	}

	public function insert()
	{
		$this->kelompok_master_model->insert();
		redirect('kelompok_master');
	}

	public function update($p=1, $o=0, $id='')
	{
		$this->kelompok_master_model->update($id);
		redirect("kelompok_master/index/$p/$o");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "kelompok_master/index/$p/$o");
		$this->kelompok_master_model->delete($id);
		redirect("kelompok_master/index/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "kelompok_master/index/$p/$o");
		$this->kelompok_master_model->delete_all();
		redirect("kelompok_master/index/$p/$o");
	}
}
