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
			if (in_array($list, ['dusun', 'rw', 'rt']))
				$$list = $this->session->$list;
			else
				$data[$list] = $this->session->$list ?: '';
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

		$data['func'] = 'index';
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = $this->set_page;
		$data['paging'] = $this->penduduk_log_model->paging($p, $o);
		$data['main'] = $this->penduduk_log_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->penduduk_model->autocomplete();
		$data['list_status_dasar'] = $this->referensi_model->list_data('tweb_status_dasar');
		$data['list_sex'] = $this->referensi_model->list_data('tweb_penduduk_sex');
		$data['list_agama'] = $this->referensi_model->list_data('tweb_penduduk_agama');
		$data['list_dusun'] = $this->penduduk_model->list_dusun();
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('penduduk_log/penduduk_log', $data);
		$this->load->view('footer');
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('penduduk_log');
	}

	public function dusun()
	{
		$this->session->unset_userdata(['rw', 'rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$this->session->dusun = $dusun;
		else $this->session->unset_userdata('dusun');
		redirect('penduduk_log');
	}

	public function rw()
	{
		$this->session->unset_userdata('rt');
		$rw = $this->input->post('rw');
		if ($rw != "")
			$this->session->rw = $rw;
		else $this->session->unset_userdata('rw');
		redirect('penduduk_log');
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$this->session->rt = $rt;
		else $this->session->unset_userdata('rt');
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
