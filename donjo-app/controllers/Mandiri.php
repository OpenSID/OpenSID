<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mandiri extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('mandiri_model');
		$this->load->model('header_model');
		$this->modul_ini = 14;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('mandiri');
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

		$data['paging'] = $this->mandiri_model->paging($p, $o);
		$data['main'] = $this->mandiri_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->mandiri_model->autocomplete();

		$header = $this->header_model->get_data();

		$nav['act'] = 14;
		$nav['act_sub'] = 56;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('mandiri/mandiri', $data);
		$this->load->view('footer');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('mandiri');
	}

	public function ajax_pin($p = 1, $o = 0, $id = 0)
	{
		$data['penduduk'] = $this->mandiri_model->list_penduduk();
		$data['form_action'] = site_url("mandiri/insert/$id");
		$this->load->view('mandiri/ajax_pin', $data);
	}

	public function insert()
	{
		$pin = $this->mandiri_model->insert();
		if ($pin)
		{
			$_SESSION['success'] = 1;
		}
		else
		{
			$_SESSION['success'] = -1;
		}

		$_SESSION['pin'] = $pin;
		redirect('mandiri');
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "mandiri");
		$outp = $this->mandiri_model->delete($id);
		if ($outp) $_SESSION['success'] = 1;
			else $_SESSION['success'] = -1;
		redirect("mandiri");
	}
}
