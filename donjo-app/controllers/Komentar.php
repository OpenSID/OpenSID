<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Komentar extends Admin_Controller {
	
	private $_kembali;

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('web_komentar_model');
		$this->modul_ini = 13;
		$this->_kembali = $_SERVER['HTTP_REFERER'];
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter_status']);
		$_SESSION['filter_user'] = 0;
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
		$this->load->view('komentar/table', $data);
		$this->load->view('footer');
	}

	public function balas($id='')
	{
		$data['form_action'] 	= site_url("komentar/insert/".$id);

		$this->load->view('komentar/modal_balas', $data);
	}

	public function ubah($id='')
	{
		$data['komentar'] 		= $this->web_komentar_model->get_komentar($id);
		$data['form_action'] 	= site_url("komentar/update/".$id);

		$this->load->view('komentar/modal_balas', $data);
	}

	public function insert($id='')
	{
		$this->web_komentar_model->insert($id);
		redirect('komentar');
	}

	public function update($id='')
	{
		$this->web_komentar_model->update($id);
		redirect('komentar');
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

	public function filter_user()
	{
		$filter_user = $this->input->post('filter_user');
		$_SESSION['filter_user'] = $filter_user;
		redirect('komentar');
	}

	public function delete($id='')
	{
		$this->redirect_hak_akses('h', $this->_kembali);
		$this->web_komentar_model->delete($id);
		redirect($this->_kembali);
	}

	public function delete_all()
	{
		$this->redirect_hak_akses('h', $this->_kembali);
		$this->web_komentar_model->delete_all();
		redirect($this->_kembali);
	}

	public function status($id='', $status)
	{
		$this->web_komentar_model->komentar_lock($id, $status);
		redirect($this->_kembali);
	}
}
