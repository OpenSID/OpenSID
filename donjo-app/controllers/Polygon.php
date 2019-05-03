<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Polygon extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('plan_polygon_model');
		$this->load->database();
		$this->modul_ini = 9;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('polygon');
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
		$data['paging'] = $this->plan_polygon_model->paging($p, $o);
		$data['main'] = $this->plan_polygon_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_polygon_model->autocomplete();

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 5;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('polygon/table', $data);
		$this->load->view('footer');
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['polygon'] = $this->plan_polygon_model->get_polygon($id);
			$data['form_action'] = site_url("polygon/update/$id/$p/$o");
		}
		else
		{
			$data['polygon'] = NULL;
			$data['form_action'] = site_url("polygon/insert");
		}

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 5;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('polygon/form', $data);
		$this->load->view('footer');
	}

	public function sub_polygon($polygon = 1)
	{
		$data['subpolygon'] = $this->plan_polygon_model->list_sub_polygon($polygon);
		$data['polygon'] = $this->plan_polygon_model->get_polygon($polygon);
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 5;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('polygon/sub_polygon_table', $data);
		$this->load->view('footer');
	}

	public function ajax_add_sub_polygon($polygon = 0, $id = 0)
	{
		if ($id)
		{
			$data['polygon'] = $this->plan_polygon_model->get_polygon($id);
			$data['form_action'] = site_url("polygon/update_sub_polygon/$polygon/$id");
		}
		else
		{
			$data['polygon'] = NULL;
			$data['form_action'] = site_url("polygon/insert_sub_polygon/$polygon");
		}

		$this->load->view("polygon/ajax_add_sub_polygon_form", $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('polygon');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('polygon');
	}

	public function insert($tip = 1)
	{
		$this->plan_polygon_model->insert($tip);
		redirect("polygon/index/$tip");
	}

	public function update($id = '', $p = 1, $o = 0)
	{
		$this->plan_polygon_model->update($id);
		redirect("polygon/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "polygon/index/$p/$o");
		$this->plan_polygon_model->delete($id);
		redirect("polygon/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "polygon/index/$p/$o");
		$this->plan_polygon_model->delete_all();
		redirect("polygon/index/$p/$o");
	}

	public function polygon_lock($id = '')
	{
		$this->plan_polygon_model->polygon_lock($id, 1);
		redirect("polygon/index/$p/$o");
	}

	public function polygon_unlock($id = '')
	{
		$this->plan_polygon_model->polygon_lock($id, 2);
		redirect("polygon/index/$p/$o");
	}

	public function insert_sub_polygon($polygon = '')
	{
		$this->plan_polygon_model->insert_sub_polygon($polygon);
		redirect("polygon/sub_polygon/$polygon");
	}

	public function update_sub_polygon($polygon = '', $id = '')
	{
		$this->plan_polygon_model->update_sub_polygon($id);
		redirect("polygon/sub_polygon/$polygon");
	}

	public function delete_sub_polygon($polygon = '', $id = '')
	{
		$this->redirect_hak_akses('h', "polygon/sub_polygon/$polygon");
		$this->plan_polygon_model->delete_sub_polygon($id);
		redirect("polygon/sub_polygon/$polygon");
	}

	public function delete_all_sub_polygon($polygon = '')
	{
		$this->redirect_hak_akses('h', "polygon/sub_polygon/$polygon");
		$this->plan_polygon_model->delete_all_sub_polygon();
		redirect("polygon/sub_polygon/$polygon");
	}

	public function polygon_lock_sub_polygon($polygon = '', $id = '')
	{
		$this->plan_polygon_model->polygon_lock($id, 1);
		redirect("polygon/sub_polygon/$polygon");
	}

	public function polygon_unlock_sub_polygon($polygon = '', $id = '')
	{
		$this->plan_polygon_model->polygon_lock($id, 2);
		redirect("polygon/sub_polygon/$polygon");
	}
}
