<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Komentar extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('web_komentar_model');
		$this->modul_ini = 13;
		$this->sub_modul_ini = 50;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter_status']);
		unset($_SESSION['filter_nik']);
		redirect('komentar');
	}

	public function index($p=1, $o=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter_status']))
			$data['filter_status'] = $_SESSION['filter_status'];
		else $data['filter_status'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging'] = $this->web_komentar_model->paging($p,$o);
		$data['main'] = $this->web_komentar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_komentar_model->autocomplete();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('komentar/table', $data);
		$this->load->view('footer');
	}

	public function form($p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['komentar'] = $this->web_komentar_model->get_komentar($id);
			$data['form_action'] = site_url("komentar/update/$id/$p/$o");
		}
		else
		{
			$data['komentar'] = null;
			$data['form_action'] = site_url("komentar/insert");
		}

		$data['list_kategori'] = $this->web_komentar_model->list_kategori(1);
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('komentar/form', $data);
		$this->load->view('footer');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('komentar');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter_status'] = $filter;
		else unset($_SESSION['filter_status']);
		redirect('komentar');
	}

	public function insert()
	{
		$this->web_komentar_model->insert();
		redirect('komentar');
	}

	public function update($id='', $p=1, $o=0)
	{
		$this->web_komentar_model->update($id);
		redirect("komentar/index/$p/$o");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "komentar/index/$p/$o");
		$this->web_komentar_model->delete($id);
		redirect("komentar/index/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "komentar/index/$p/$o");
		$this->web_komentar_model->delete_all();
		redirect("komentar/index/$p/$o");
	}

	public function komentar_lock($id='')
	{
		$this->web_komentar_model->komentar_lock($id, 1);
		redirect("komentar/index/$p/$o");
	}

	public function komentar_unlock($id='')
	{
		$this->web_komentar_model->komentar_lock($id, 2);
		redirect("komentar/index/$p/$o");
	}
}
