<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Teks_berjalan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('header_model');
		$this->load->model('teks_berjalan_model');
		$this->load->model('web_artikel_model');
		$this->modul_ini = 13;
		$this->sub_modul_ini = 64;
	}

	public function index()
	{
		$data['main'] = $this->teks_berjalan_model->list_data();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('web/teks_berjalan/table', $data);
		$this->load->view('footer');
	}

	public function form($id = '')
	{
		$data['list_artikel'] = $this->web_artikel_model->list_data(999, 6, 0);

		if ($id)
		{
			$data['teks'] = $this->teks_berjalan_model->get_teks($id);
			$data['form_action'] = site_url("teks_berjalan/update/$id");
		}
		else
		{
			$data['teks'] = null;
			$data['form_action'] = site_url("teks_berjalan/insert");
		}

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('web/teks_berjalan/form', $data);
		$this->load->view('footer');
	}

	public function insert()
	{
		$this->teks_berjalan_model->insert();
		redirect("teks_berjalan");
	}

	public function update($id = '')
	{
		$this->teks_berjalan_model->update($id);
		redirect("teks_berjalan");
	}

	public function delete($id = '')
	{
		$this->redirect_hak_akses('h', "teks_berjalan");
		$this->teks_berjalan_model->delete($id);
		redirect("teks_berjalan");
	}

	public function delete_all()
	{
		$this->redirect_hak_akses('h', "teks_berjalan");
		$this->teks_berjalan_model->delete_all();
		redirect("teks_berjalan");
	}

	public function urut($id = 0, $arah = 0)
	{
		$urut = $this->teks_berjalan_model->urut($id, $arah);
 		redirect("teks_berjalan/index/$page");
	}

	public function lock($id = 0, $val = 1)
	{
		$this->teks_berjalan_model->lock($id, $val);
		redirect("teks_berjalan");
	}

}
