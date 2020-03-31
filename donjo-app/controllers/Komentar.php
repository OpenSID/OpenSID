<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Komentar extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('web_komentar_model');
		$this->modul = 'komentar';
		$this->modul_ini = 13;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter_status']);
		$_SESSION['filter_user'] = 0;
		redirect($this->modul);
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

		if (isset($_SESSION['filter_user']))
			$data['filter_user'] = $_SESSION['filter_user'];
		else $data['filter_user'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging'] = $this->web_komentar_model->paging($p,$o);
		$data['main'] = $this->web_komentar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_komentar_model->autocomplete();

		$header = $this->header_model->get_data();
		$nav['act'] = 13;
		$nav['act_sub'] = 50;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view($this->modul.'/table', $data);
		$this->load->view('footer');
	}

	public function balas($id='')
	{
		$data['form_action'] 	= site_url($this->modul."/insert/".$id);

		$this->load->view($this->modul.'/modal_balas', $data);
	}

	public function ubah($id='')
	{
		$data['komentar'] 		= $this->web_komentar_model->get_komentar($id);
		$data['form_action'] 	= site_url($this->modul."/update/".$id);

		$this->load->view($this->modul.'/modal_balas', $data);
	}

	public function insert($id='')
	{
		$this->web_komentar_model->insert($id);
		redirect($this->modul);
	}

	public function update($id='')
	{
		$this->web_komentar_model->update($id);
		redirect($this->modul);
	}
	
	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect($this->modul);
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter_status'] = $filter;
		else unset($_SESSION['filter_status']);
		redirect($this->modul);
	}

	public function filter_user()
	{
		$filter_user = $this->input->post('filter_user');
		$_SESSION['filter_user'] = $filter_user;
		redirect($this->modul);
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h');
		$this->web_komentar_model->delete($id);
		redirect($this->modul.'/index/$p/$o');
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "komentar/index/$p/$o");
		$this->web_komentar_model->delete_all();
		redirect("komentar/index/$p/$o");
	}

	public function status($id='', $status)
	{
		$this->web_komentar_model->komentar_lock($id, $status);
		kembali();
	}
}
