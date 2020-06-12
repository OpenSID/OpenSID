<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Dpt extends Admin_Controller {

	private $header;
	private $set_page;
	private $list_session;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['penduduk_model', 'dpt_model', 'referensi_model', 'wilayah_model', 'header_model']);
		$this->modul_ini = 2;
		$this->sub_modul_ini = 26;
		$this->set_page = ['20', '50', '100'];
		// TODO: Hapus header_model jika sudah dibuatkan librari tempalte admin
		$this->header = $this->header_model->get_data();
		$this->list_session = ['cari', 'sex', 'dusun', 'rw', 'rt', 'tanggal_pemilihan', 'umurx', 'umur_min', 'umur_max', 'cacatx', 'menahunx', 'pekerjaan_id', 'status', 'agama', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk'];
	}

	public function clear()
	{
		$this->session->unset_userdata($this->list_session);
		$this->session->per_page = $this->set_page[0];
		redirect('dpt');
	}

	public function index($p=1, $o=0)
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

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['func'] = 'index';
		$data['set_page'] = $this->set_page;
		$data['per_page'] = $this->session->per_page;
		$data['list_jenis_kelamin'] = $this->referensi_model->list_data('tweb_penduduk_sex');
		$data['list_dusun'] = $this->wilayah_model->list_dusun();
		$data['paging'] = $this->dpt_model->paging($p, $o);
		$data['main'] = $this->dpt_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->dpt_model->autocomplete();

		$header['minsidebar'] = 1;

		$this->load->view('header', $this->header);
		$this->load->view('nav',$nav);
		$this->load->view('dpt/dpt', $data);
		$this->load->view('footer');
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('dpt');
	}

	public function dusun()
	{
		$this->session->unset_userdata(['rw', 'rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$this->session->dusun = $dusun;
		else $this->session->unset_userdata('dusun');
		redirect('dpt');
	}

	public function rw()
	{
		$this->session->unset_userdata('rt');
		$rw = $this->input->post('rw');
		if ($rw != "")
			$this->session->rw = $rw;
		else $this->session->unset_userdata('rw');
		redirect('dpt');
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$this->session->rt = $rt;
		else $this->session->unset_userdata('rt');
		redirect('dpt');
	}

	public function ajax_adv_search()
	{
		foreach ($this->list_session as $list)
		{
				$data[$list] = $this->session->$list ?: '';
		}

		$data['list_agama'] = $this->referensi_model->list_data('tweb_penduduk_agama');
		$data['list_pendidikan'] = $this->referensi_model->list_data('tweb_penduduk_pendidikan');
		$data['list_pendidikan_kk'] = $this->referensi_model->list_data('tweb_penduduk_pendidikan_kk');
		$data['list_pekerjaan'] = $this->referensi_model->list_data('tweb_penduduk_pekerjaan');
		$data['list_status_kawin'] = $this->referensi_model->list_data('tweb_penduduk_kawin');
		$data['list_status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status');
		$data['form_action'] = site_url("dpt/adv_search_proses");

		$this->load->view("sid/kependudukan/ajax_adv_search_form", $data);
	}

	public function adv_search_proses()
	{
		$adv_search = $_POST;
		$i = 0;
		while ($i++ < count($adv_search))
		{
			$col[$i] = key($adv_search);
				next($adv_search);
		}
		$i = 0;
		while ($i++ < count($col))
		{
			if ($adv_search[$col[$i]] == "")
			{
				UNSET($adv_search[$col[$i]]);
				UNSET($_SESSION[$col[$i]]);
			}
			else
			{
				$_SESSION[$col[$i]] = $adv_search[$col[$i]];
			}
		}

		redirect("dpt/index/1/$o");
	}

	public function cetak($o=0)
	{
		$data['main'] = $this->dpt_model->list_data($o, 0, 10000);
		$this->load->view('dpt/dpt_print', $data);
	}

	public function excel($o=0)
	{
		$data['main'] = $this->dpt_model->list_data($o, 0, 10000);
		$this->load->view('dpt/dpt_excel', $data);
	}

}
