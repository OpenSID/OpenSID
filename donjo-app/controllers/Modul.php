<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Modul extends Admin_Controller {

	private $list_session;
	private $header;

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model(['modul_model', 'header_model']);
		$this->modul_ini = 11;
		$this->sub_modul_ini = 42;
		$this->list_session = ['status', 'cari', 'module'];
		// TODO: Hapus header_model jika sudah dibuatkan librari tempalte admin
		$this->header = $this->header_model->get_data();
	}

	public function clear()
	{
		$this->session->unset_userdata($this->list_session);
		redirect('modul');
	}

	public function index()
	{
		$id = $this->session->module;

		if (!$id)
		{
			foreach ($this->list_session as $list)
			{
				$data[$list] = $this->session->$list ?: '';
			}

			$data['sub_modul'] = NULL;
			$data['main'] = $this->modul_model->list_data();
			$data['keyword'] = $this->modul_model->autocomplete();
		}
		else
		{
			$data['sub_modul'] = $this->modul_model->get_data($id);
			$data['main'] = $this->modul_model->list_sub_modul($id);
		}

		$this->load->view('header', $this->header);
		$this->load->view('nav');
		$this->load->view('setting/modul/table', $data);
		$this->load->view('footer');
	}

	public function form($id = '')
	{
		$data['list_icon'] = $this->modul_model->list_icon();
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

		$this->load->view('header', $this->header);
		$this->load->view('nav');
		$this->load->view('setting/modul/form', $data);
		$this->load->view('footer');
	}

	public function sub_modul($id = '')
	{
		$this->session->module = $id;

		redirect('modul');
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('modul');
	}

	public function update($id = '')
	{
		$this->modul_model->update($id);
		$parent = $this->input->post('parent');
		if ($parent == 0)
			redirect("modul");
		else
			redirect("modul/sub_modul/$parent");
	}

	public function lock($id = 0, $val = 1)
	{
		$this->modul_model->lock($id, $val);
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function ubah_server()
	{
		$this->setting_model->update_penggunaan_server();
		redirect('modul');
	}

	public function default_server()
	{
		$this->modul_model->default_server();
		$this->clear();
	}

}
