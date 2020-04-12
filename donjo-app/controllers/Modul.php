<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Modul extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('modul_model');
		$this->load->model('header_model');
		$this->modul_ini = 11;
		$this->sub_modul_ini = 42;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('modul');
	}

	public function index()
	{
		$list_session = array('cari', 'filter');
		foreach ($list_session as $session)
		{
			$data[$session] = $this->session->userdata($session) ?: '';
		}

		$data['main'] = $this->modul_model->list_data();
		$data['keyword'] = $this->modul_model->autocomplete();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('setting/modul/table', $data);
		$this->load->view('footer');
	}

	public function form($id = '')
	{
		if ($id)
		{
			$data['modul'] = $this->modul_model->get_data($id);
			$data['form_action'] = site_url("modul/update/$id");
		}
		else
		{
			$data['modul'] = NULL;
			$data['form_action'] = site_url("modul/insert");
		}
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('setting/modul/form', $data);
		$this->load->view('footer');
	}

	public function sub_modul($id = '')
	{
		$data['submodul'] = $this->modul_model->list_sub_modul($id);
		$data['modul'] = $this->modul_model->get_data($id);
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('setting/modul/sub_modul_table', $data);
		$this->load->view('footer');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != "")
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('modul');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('modul');
	}

	public function update($id = '')
	{
		$this->modul_model->update($id);
		if ($_POST['parent'] == 0)
			redirect("modul");
		else
		{
			redirect("modul/sub_modul/$_POST[parent]");
		}
	}

	public function ubah_server()
	{
		$this->setting_model->update_penggunaan_server();
		redirect('modul');
	}

	public function default_server()
	{
		$this->modul_model->default_server();
		redirect('modul');
	}
}
