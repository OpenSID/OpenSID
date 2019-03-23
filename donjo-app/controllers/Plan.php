<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Plan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('plan_lokasi_model');
		$this->load->database();
		$this->modul_ini = 9;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['point']);
		unset($_SESSION['subpoint']);
		redirect('plan');
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

		if (isset($_SESSION['point']))
			$data['point'] = $_SESSION['point'];
		else $data['point'] = '';

		if (isset($_SESSION['subpoint']))
			$data['subpoint'] = $_SESSION['subpoint'];
		else $data['subpoint'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$data['per_page'] = $_SESSION['per_page'];

		$data['paging'] = $this->plan_lokasi_model->paging($p, $o);
		$data['main'] = $this->plan_lokasi_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_lokasi_model->autocomplete();
		$data['list_point'] = $this->plan_lokasi_model->list_point();
		$data['list_subpoint'] = $this->plan_lokasi_model->list_subpoint();

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 3;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('lokasi/table', $data);
		$this->load->view('footer');
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$data['desa'] = $this->plan_lokasi_model->get_desa();
		$data['list_point'] = $this->plan_lokasi_model->list_point();
		$data['dusun'] = $this->plan_lokasi_model->list_dusun();

		if ($id)
		{
			$data['lokasi'] = $this->plan_lokasi_model->get_lokasi($id);
			$data['form_action'] = site_url("plan/update/$id/$p/$o");
		}
		else
		{
			$data['lokasi'] = NULL;
			$data['form_action'] = site_url("plan/insert");
		}

		$header= $this->header_model->get_data();

		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 3;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('lokasi/form', $data);
		$this->load->view('footer');
	}

	public function ajax_lokasi_maps($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		if ($id)
			$data['lokasi'] = $this->plan_lokasi_model->get_lokasi($id);
		else
			$data['lokasi'] = NULL;

		$data['desa'] = $this->plan_lokasi_model->get_desa();
		$data['form_action'] = site_url("plan/update_maps/$p/$o/$id");
		$this->load->view("lokasi/maps", $data);
	}

	public function update_maps($p = 1, $o = 0, $id = '')
	{
		$this->plan_lokasi_model->update_position($id);
		redirect("plan/index/$p/$o");
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('plan');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('plan');
	}

	public function point()
	{
		$point = $this->input->post('point');
		if ($point != 0)
			$_SESSION['point'] = $point;
		else unset($_SESSION['point']);
		redirect('plan');
	}

	public function subpoint()
	{
		unset($_SESSION['point']);
		$subpoint = $this->input->post('subpoint');
		if ($subpoint != 0)
			$_SESSION['subpoint'] = $subpoint;
		else unset($_SESSION['subpoint']);
		redirect('plan');
	}

	public function insert($tip = 1)
	{
		$this->plan_lokasi_model->insert($tip);
		redirect("plan/index/$tip");
	}

	public function update($id = '', $p = 1, $o = 0)
	{
		$this->plan_lokasi_model->update($id);
		redirect("plan/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "plan/index/$p/$o");
		$this->plan_lokasi_model->delete($id);
		redirect("plan/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "plan/index/$p/$o");
		$this->plan_lokasi_model->delete_all();
		redirect("plan/index/$p/$o");
	}

	public function lokasi_lock($id = '')
	{
		$this->plan_lokasi_model->lokasi_lock($id, 1);
		redirect("plan/index/$p/$o");
	}

	public function lokasi_unlock($id = '')
	{
		$this->plan_lokasi_model->lokasi_lock($id, 2);
		redirect("plan/index/$p/$o");
	}
}
