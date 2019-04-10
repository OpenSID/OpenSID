<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Point extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('plan_point_model');
		$this->load->database();
		$this->modul_ini = 9;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('point');
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

		$data['paging'] = $this->plan_point_model->paging($p, $o);
		$data['main'] = $this->plan_point_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_point_model->autocomplete();

		$header= $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 0;

		$this->load->view('header', $header);

		$this->load->view('nav', $nav);
		$this->load->view('point/table', $data);
		$this->load->view('footer');

	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['point'] = $this->plan_point_model->get_point($id);
			$data['form_action'] = site_url("point/update/$id/$p/$o");
		}
		else
		{
			$data['point'] = NULL;
			$data['form_action'] = site_url("point/insert");
		}

		$data['simbol'] = $this->plan_point_model->list_simbol();
		$header = $this->header_model->get_data();

		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 0;
		$this->load->view('header', $header);

		$this->load->view('nav', $nav);
		$this->load->view('point/form', $data);
		$this->load->view('footer');
	}

	public function sub_point($point = 1)
	{
		$data['subpoint'] = $this->plan_point_model->list_sub_point($point);
		$data['point'] = $this->plan_point_model->get_point($point);
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 0;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('point/sub_point_table', $data);
		$this->load->view('footer');
	}

	public function ajax_add_sub_point($point = 0, $id = 0)
	{
		if ($id)
		{
			$data['point'] = $this->plan_point_model->get_point($id);
			$data['form_action'] = site_url("point/update_sub_point/$point/$id");
		}
		else
		{
			$data['point'] = NULL;
			$data['form_action'] = site_url("point/insert_sub_point/$point");
		}

		$data['simbol'] = $this->plan_point_model->list_simbol();
		$this->load->view("point/ajax_add_sub_point_form", $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('point');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('point');
	}

	public function insert($tip = 1)
	{
		$this->plan_point_model->insert($tip);
		redirect("point/index/$tip");
	}

	public function update($id = '', $p = 1, $o = 0)
	{
		$this->plan_point_model->update($id);
		redirect("point/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "point/index/$p/$o");
		$this->plan_point_model->delete($id);
		redirect("point/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "point/index/$p/$o");
		$this->plan_point_model->delete_all();
		redirect("point/index/$p/$o");
	}

	public function point_lock($id = '')
	{
		$this->plan_point_model->point_lock($id, 1);
		redirect("point/index/$p/$o");
	}

	public function point_unlock($id = '')
	{
		$this->plan_point_model->point_lock($id, 2);
		redirect("point/index/$p/$o");
	}

	public function insert_sub_point($point = '')
	{
		$this->plan_point_model->insert_sub_point($point);
		redirect("point/sub_point/$point");
	}

	public function update_sub_point($point = '', $id = '')
	{
		$this->plan_point_model->update_sub_point($id);
		redirect("point/sub_point/$point");
	}

	public function delete_sub_point($point = '', $id = '')
	{
		$this->redirect_hak_akses('h', "point/sub_point/$point");
		$this->plan_point_model->delete_sub_point($id);
		redirect("point/sub_point/$point");
	}

	public function delete_all_sub_point($point = '')
	{
		$this->redirect_hak_akses('h', "point/sub_point/$point");
		$this->plan_point_model->delete_all_sub_point();
		redirect("point/sub_point/$point");
	}

	public function point_lock_sub_point($point = '', $id = '')
	{
		$this->plan_point_model->point_lock($id, 1);
		redirect("point/sub_point/$point");
	}

	public function point_unlock_sub_point($point = '', $id = '')
	{
		$this->plan_point_model->point_lock($id, 2);
		redirect("point/sub_point/$point");
	}
}
