<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mandiri extends Admin_Controller {

	private $kembali;

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('mandiri_model');
		$this->load->model('header_model');
		$this->modul_ini = 14;
		$this->sub_modul_ini = 56;
		$this->kembali = $_SERVER['HTTP_REFERER'];
	}

	public function clear()
	{
		unset($_SESSION['cari']);
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

	public function ajax_pin($id_pend = '')
	{
		if ($id_pend)
		{
			$data['penduduk'] = $this->mandiri_model->get_penduduk($id_pend);
			$data['id_pend'] = $id_pend;
			$data['form_action'] = site_url("mandiri/update/$id_pend");	
		}
		else
		{
			$data['penduduk'] = $this->mandiri_model->list_penduduk();
			$data['id_pend'] = NULL;
			$data['form_action'] = site_url("mandiri/insert");
			
		}
		$this->load->view('mandiri/ajax_pin', $data);
	}

	public function insert()
	{
		$pin = $this->mandiri_model->insert();

		status_sukses($pin); //Tampilkan Pesan

		$_SESSION['pin'] = $pin;
		redirect('mandiri');
	}

	public function update($id_pend)
	{
		$pin = $this->mandiri_model->update($id_pend);

		status_sukses($pin); //Tampilkan Pesan

		$_SESSION['pin'] = $pin;
		redirect('mandiri');
	}

	public function delete($id = '')
	{
		$this->redirect_hak_akses('h', $this->kembali);
		$this->mandiri_model->delete($id);		
		redirect($this->kembali);
	}
}
