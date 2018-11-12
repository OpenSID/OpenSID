<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengurus extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup = $this->user_model->sesi_grup($_SESSION['sesi']);
		if ($grup != 1 AND $grup != 2)
		{
			if (empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('pamong_model');
		$this->load->model('header_model');
		$this->modul_ini = 200;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('pengurus');
	}

	public function index()
	{
		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		$data['main'] = $this->pamong_model->list_data();
		$data['keyword'] = $this->pamong_model->autocomplete();
		$header = $this->header_model->get_data();

		// Menampilkan menu dan sub menu aktif
		$nav['act'] = 1;
		$nav['act_sub'] = 18;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('home/pengurus', $data);
		$this->load->view('footer');
	}

	public function form($id = '')
	{
		if ($id)
		{
			$data['pamong'] = $this->pamong_model->get_data($id);
			$data['form_action'] = site_url("pengurus/update/$id");
		}
		else
		{
			$data['pamong'] = NULL;
			$data['form_action'] = site_url("pengurus/insert");
		}

		$header = $this->header_model->get_data();
		// Menampilkan menu dan sub menu aktif
		$nav['act'] = 1;
		$nav['act_sub'] = 18;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('home/pengurus_form', $data);
		$this->load->view('footer');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != "")
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('pengurus');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('pengurus');
	}

	public function insert()
	{
		$this->pamong_model->insert();
		redirect('pengurus');
	}

	public function update($id = '')
	{
		$this->pamong_model->update($id);
		redirect('pengurus');
	}

	public function delete($id = '')
	{
		$_SESSION['success'] = 1;
		$outp = $this->pamong_model->delete($id);
		if (!$outp) $_SESSION['success'] = -1;
		redirect('pengurus');
	}

	public function delete_all()
	{
		$this->pamong_model->delete_all();
		redirect('pengurus');
	}

	public function ttd_on($id = '')
	{
		$this->pamong_model->ttd($id, 1);
		redirect('pengurus');
	}

	public function ttd_off($id = '')
	{
		$this->pamong_model->ttd($id, 0);
		redirect('pengurus');
	}
}
