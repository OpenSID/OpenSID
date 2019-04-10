<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Area extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('plan_area_model');
		$this->load->database();
		$this->modul_ini = 9;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['polygon']);
		unset($_SESSION['subpolygon']);
		redirect('area');
	}

	public function index($p=1, $o=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_SESSION['polygon']))
			$data['polygon'] = $_SESSION['polygon'];
		else $data['polygon'] = '';

		if (isset($_SESSION['subpolygon']))
			$data['subpolygon'] = $_SESSION['subpolygon'];
		else $data['subpolygon'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging'] = $this->plan_area_model->paging($p,$o);
		$data['main'] = $this->plan_area_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_area_model->autocomplete();
		$data['list_polygon'] = $this->plan_area_model->list_polygon();
		$data['list_subpolygon'] = $this->plan_area_model->list_subpolygon();

		$header= $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 4;
		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('area/table',$data);
		$this->load->view('footer');
	}

	public function form($p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$data['desa'] = $this->plan_area_model->get_desa();
		$data['list_polygon'] = $this->plan_area_model->list_polygon();
		$data['dusun'] = $this->plan_area_model->list_dusun();

		if ($id)
		{
			$data['area'] = $this->plan_area_model->get_area($id);
			$data['form_action'] = site_url("area/update/$id/$p/$o");
		}
		else
		{
			$data['area'] = null;
			$data['form_action'] = site_url("area/insert");
		}

		$header= $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 4;
		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('area/form', $data);
		$this->load->view('footer');

	}

	public function ajax_area_maps($p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		if ($id)
			$data['area'] = $this->plan_area_model->get_area($id);
		else
			$data['area'] = null;

		$data['desa'] = $this->plan_area_model->get_desa();
		$data['form_action'] = site_url("area/update_maps/$p/$o/$id");
		$this->load->view("area/maps", $data);
	}

	public function update_maps($p=1, $o=0, $id='')
	{
		$this->plan_area_model->update_position($id);
		redirect("area/index/$p/$o");
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('area');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('area');
	}

	public function polygon()
	{
		$polygon = $this->input->post('polygon');
		if ($polygon != 0)
			$_SESSION['polygon'] = $polygon;
		else unset($_SESSION['polygon']);
		redirect('area');
	}

	public function subpolygon()
	{
		unset($_SESSION['polygon']);
		$subpolygon = $this->input->post('subpolygon');
		if ($subpolygon != 0)
			$_SESSION['subpolygon'] = $subpolygon;
		else unset($_SESSION['subpolygon']);
		redirect('area');
	}

	public function insert($tip=1)
	{
		$this->plan_area_model->insert($tip);
		redirect("area/index/$tip");
	}

	public function update($id='', $p=1, $o=0)
	{
		$this->plan_area_model->update($id);
		redirect("area/index/$p/$o");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "area/index/$p/$o");
		$this->plan_area_model->delete($id);
		redirect("area/index/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "area/index/$p/$o");
		$this->plan_area_model->delete_all();
		redirect("area/index/$p/$o");
	}

	public function area_lock($id='')
	{
		$this->plan_area_model->area_lock($id, 1);
		redirect("area/index/$p/$o");
	}

	public function area_unlock($id='')
	{
		$this->plan_area_model->area_lock($id, 2);
		redirect("area/index/$p/$o");
	}
}
