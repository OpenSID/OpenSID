<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penduduk_log extends Admin_Controller {

	private $set_page;
	private $list_session;

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('referensi_model');
		$this->load->model('penduduk_model');
		$this->load->model('penduduk_log_model');
		$this->load->model('header_model');
		$this->modul_ini = 2;
		$this->sub_modul_ini = 21;
		$this->set_page = ['20', '50', '100'];
		$this->list_session = ['status_dasar', 'sex', 'agama', 'dusun', 'rw', 'rt', 'cari'];
	}

	public function clear()
	{
		$this->session->unset_userdata($this->list_session);
		$this->session->per_page = 20;
		redirect('penduduk_log');
	}

	public function index($p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		foreach ($this->list_session as $list)
		{
			if(in_array($list, ['dusun', 'rw', 'rt']))
				$$list = $this->session->userdata($list);
			else
				$data[$list] = $this->session->userdata($list) ?: '';
		}

		if (isset($dusun))
		{
			$data['dusun'] = $dusun;
			$data['list_rw'] = $this->penduduk_model->list_rw($dusun);

			if (isset($rw))
			{
				$data['rw'] = $rw;
				$data['list_rt'] = $this->penduduk_model->list_rt($dusun, $rw);

				if (isset($rt))
					$data['rt'] = $rt;
				else $data['rt'] = '';

			}
			else $data['rw'] = '';
		}
		else
		{
			$data['dusun'] = $data['rw'] = $data['rt'] = '';
		}

		// Hanya tampilkan penduduk yang status dasarnya bukan 'HIDUP'
		$this->session->log = 1;

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['per_page'] = $this->session->per_page;

		$data['set_page'] = $this->set_page;
		$data['paging'] = $this->penduduk_log_model->paging($p, $o);
		$data['main'] = $this->penduduk_log_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->penduduk_model->autocomplete();
		$data['list_status_dasar'] = $this->referensi_model->list_data('tweb_status_dasar');
		$data['list_agama'] = $this->referensi_model->list_data('tweb_penduduk_agama');
		$data['list_dusun'] = $this->penduduk_model->list_dusun();
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sid/kependudukan/penduduk_log', $data);
		$this->load->view('footer');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('penduduk_log');
	}

	public function status_dasar()
	{
		$status_dasar = $this->input->post('status_dasar');
		if ($status_dasar != "")
			$_SESSION['status_dasar'] = $status_dasar;
		else unset($_SESSION['status_dasar']);
		redirect('penduduk_log');
	}

	public function sex()
	{
		$sex = $this->input->post('sex');
		if ($sex != "")
			$_SESSION['sex'] = $sex;
		else unset($_SESSION['sex']);
		redirect('penduduk_log');
	}

	public function agama()
	{
		$agama = $this->input->post('agama');
		if ($agama != "")
			$_SESSION['agama'] = $agama;
		else unset($_SESSION['agama']);
		redirect('penduduk_log');
	}

	public function dusun()
	{
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect('penduduk_log');
	}

	public function rw()
	{
		$rw = $this->input->post('rw');
		if ($rw != "")
			$_SESSION['rw'] = $rw;
		else unset($_SESSION['rw']);
		redirect('penduduk_log');
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$_SESSION['rt'] = $rt;
		else unset($_SESSION['rt']);
		redirect('penduduk_log');
	}

	public function edit($p = 1, $o = 0, $id = 0)
	{
		$data['log_status_dasar'] = $this->penduduk_log_model->get_log($id);
		$data['list_ref_pindah'] = $this->referensi_model->list_data('ref_pindah');
		$data['form_action'] = site_url("penduduk_log/update/$p/$o/$id");
		$this->load->view('penduduk_log/ajax_edit', $data);
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$this->penduduk_log_model->update($id);
		redirect("penduduk_log/index/$p/$o");
	}

	public function kembalikan_status($id_log)
	{
		unset($_SESSION['success']);
		$this->penduduk_log_model->kembalikan_status($id_log);
		redirect("penduduk_log");
	}

	public function kembalikan_status_all()
	{
		$this->penduduk_log_model->kembalikan_status_all();
		redirect("penduduk_log");
	}

	public function cetak($o = 0)
	{
		$data['main'] = $this->penduduk_log_model->list_data($o, 0, 10000);
		$this->load->view('penduduk_log/penduduk_log_print', $data);
	}

	public function excel($o = 0)
	{
		$data['main'] = $this->penduduk_log_model->list_data($o, 0, 10000);
		$this->load->view('penduduk_log/penduduk_log_excel', $data);
	}

}
