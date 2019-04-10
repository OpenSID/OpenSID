<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Line extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('plan_line_model');
		$this->load->database();
		$this->modul_ini = 9;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('line');
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

		$data['paging'] = $this->plan_line_model->paging($p, $o);
		$data['main'] = $this->plan_line_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_line_model->autocomplete();

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 2;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('line/table', $data);
		$this->load->view('footer');
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['line'] = $this->plan_line_model->get_line($id);
			$data['form_action'] = site_url("line/update/$id/$p/$o");
		}
		else
		{
			$data['line'] = NULL;
			$data['form_action'] = site_url("line/insert");
		}

		$header = $this->header_model->get_data();

		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 2;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('line/form', $data);
		$this->load->view('footer');
	}

	public function sub_line($line = 1)
	{
		$data['subline'] = $this->plan_line_model->list_sub_line($line);
		$data['line'] = $this->plan_line_model->get_line($line);
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$nav['act_sub'] = 8;
		$nav['tip'] = 2;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('line/sub_line_table', $data);
		$this->load->view('footer');
	}

	public function ajax_add_sub_line($line = 0, $id = 0)
	{
		if ($id)
		{
			$data['line'] = $this->plan_line_model->get_line($id);
			$data['form_action'] = site_url("line/update_sub_line/$line/$id");
		}
		else
		{
			$data['line'] = NULL;
			$data['form_action'] = site_url("line/insert_sub_line/$line");
		}

		$this->load->view("line/ajax_add_sub_line_form", $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('line');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('line');
	}

	public function insert($tip = 1)
	{
		$this->plan_line_model->insert($tip);
		redirect("line/index/$tip");
	}

	public function update($id = '', $p = 1, $o = 0)
	{
		$this->plan_line_model->update($id);
		redirect("line/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "line/index/$p/$o");
		$this->plan_line_model->delete($id);
		redirect("line/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "line/index/$p/$o");
		$this->plan_line_model->delete_all();
		redirect("line/index/$p/$o");
	}

	public function line_lock($id = '')
	{
		$this->plan_line_model->line_lock($id, 1);
		redirect("line/index/$p/$o");
	}

	public function line_unlock($id = '')
	{
		$this->plan_line_model->line_lock($id, 2);
		redirect("line/index/$p/$o");
	}

	public function insert_sub_line($line = '')
	{
		$this->plan_line_model->insert_sub_line($line);
		redirect("line/sub_line/$line");
	}

	public function update_sub_line($line = '', $id = '')
	{
		$this->plan_line_model->update_sub_line($id);
		redirect("line/sub_line/$line");
	}

	public function delete_sub_line($line = '', $id = '')
	{
		$this->plan_line_model->delete_sub_line($id);
		redirect("line/sub_line/$line");
	}

	public function delete_all_sub_line($line = '')
	{
		$this->plan_line_model->delete_all_sub_line();
		redirect("line/sub_line/$line");
	}

	public function line_lock_sub_line($line = '', $id = '')
	{
		$this->plan_line_model->line_lock($id, 1);
		redirect("line/sub_line/$line");
	}

	public function line_unlock_sub_line($line = '', $id = '')
	{
		$this->plan_line_model->line_lock($id, 2);
		redirect("line/sub_line/$line");
	}
}
