<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rtm extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('rtm_model');
		$this->load->model('penduduk_model');
		$this->modul_ini = 2;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['id_bos']);
		$_SESSION['per_page'] = 100;
		redirect('rtm');
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

		if (isset($_SESSION['kelas']))
			$data['kelas'] = $_SESSION['kelas'];
		else $data['kelas'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$data['per_page'] = $_SESSION['per_page'];

		if (isset($_SESSION['dusun']))
		{
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);

			if (isset($_SESSION['rw']))
			{
				$data['rw'] = $_SESSION['rw'];
				$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'], $data['rw']);

				if (isset($_SESSION['rt']))
					$data['rt'] = $_SESSION['rt'];
				else $data['rt'] = '';

			}
			else $data['rw'] = '';
		}
		else
		{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		$data['paging']  = $this->rtm_model->paging($p, $o);
		$data['main'] = $this->rtm_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->rtm_model->autocomplete();
		$data['list_dusun'] = $this->penduduk_model->list_dusun();

		$nav['act'] = 2;
		$nav['act_sub'] = 23;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sid/kependudukan/rtm', $data);
		$this->load->view('footer');
	}

	public function cetak($o = 0)
	{
		$data['main'] = $this->rtm_model->list_data($o, 0, 10000);
		$this->load->view('sid/kependudukan/rtm_print', $data);
	}

	public function excel($o = 0)
	{
		$data['main'] = $this->rtm_model->list_data($o, 0, 10000);
		$this->load->view('sid/kependudukan/rtm_excel', $data);
	}

	public function edit_nokk($p = 1, $o = 0, $id = 0)
	{
		$data['kk'] = $this->rtm_model->get_rtm($id);
		$data['form_action'] = site_url("rtm/update_nokk/$id");
		$this->load->view('sid/kependudukan/ajax_edit_no_rtm', $data);
	}

	public function form_old($p = 1, $o = 0, $id = 0)
	{
		$data['penduduk'] = $this->rtm_model->list_penduduk_lepas();
		$data['form_action'] = site_url("rtm/insert/$id");
		$this->load->view('sid/kependudukan/ajax_add_rtm', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('rtm');
	}

	public function dusun()
	{
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect('rtm');
	}

	public function rw()
	{
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if ($rw != "")
			$_SESSION['rw'] = $rw;
		else unset($_SESSION['rw']);
		redirect('rtm');
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$_SESSION['rt'] = $rt;
		else unset($_SESSION['rt']);
		redirect('rtm');
	}

	public function insert()
	{
		$this->rtm_model->insert();
		redirect('rtm');
	}

	public function insert_by_kk()
	{
		$this->rtm_model->insert_by_kk();
		redirect('rtm');
	}

	public function insert_a()
	{
		$this->rtm_model->insert_a();
		redirect('rtm');
	}

	public function insert_new()
	{
		$this->rtm_model->insert_new();
		redirect('rtm');
	}

	public function update($id = '')
	{
		$this->rtm_model->update($id);
		redirect('rtm');
	}

	public function update_nokk($id = '')
	{
		$this->rtm_model->update_nokk($id);
		redirect('rtm');
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->session->success = 1;
		$this->redirect_hak_akses('h', 'rtm');
		$this->rtm_model->delete($id);
		redirect('rtm');
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->session->success = 1;
		$this->redirect_hak_akses('h', 'rtm');
		$this->rtm_model->delete_all();
		redirect('rtm');
	}

	public function anggota($p = 1, $o = 0, $id = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['kk'] = $id;

		$data['main'] = $this->rtm_model->list_anggota($id);
		$data['kepala_kk']= $this->rtm_model->get_kepala_rtm($id);

		$nav['act'] = 2;
		$nav['act_sub'] = 23;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sid/kependudukan/rtm_anggota', $data);
		$this->load->view('footer');
	}

	public function ajax_add_anggota($p = 1, $o = 0, $id = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$data['main'] = $this->rtm_model->list_anggota($id);
		$kk = $this->rtm_model->get_kepala_rtm($id);
		if ($kk)
			$data['kepala_kk'] = $kk;
		else
			$data['kepala_kk'] = NULL;

		$data['penduduk'] = $this->rtm_model->list_penduduk_lepas();
		$data['form_action'] = site_url("rtm/add_anggota/$p/$o/$id");

		$this->load->view("sid/kependudukan/ajax_add_anggota_rtm_form", $data);
	}

	public function edit_anggota($p = 1, $o = 0, $id_kk = 0, $id = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$data['hubungan'] = $this->rtm_model->list_hubungan();
		$data['main'] = $this->rtm_model->get_anggota($id);
		$data['form_action'] = site_url("rtm/update_anggota/$p/$o/$id_kk/$id");
		$this->load->view("sid/kependudukan/ajax_edit_anggota_rtm", $data);
	}

	public function kartu_rtm($p = 1, $o = 0, $id = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['id_kk'] = $id;

		$data['hubungan'] = $this->rtm_model->list_hubungan();
		$data['main'] = $this->rtm_model->list_anggota($id);
		$kk = $this->rtm_model->get_kepala_rtm($id);
		$data['desa'] = $this->rtm_model->get_desa();

		if ($kk)
			$data['kepala_kk'] = $kk;
		else
			$data['kepala_kk'] = NULL;

		$data['penduduk'] = $this->rtm_model->list_penduduk_lepas();
		$nav['act'] = 2;
		$nav['act_sub'] = 23;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$data['form_action'] = site_url("rtm/print");

		$this->load->view("sid/kependudukan/kartu_rtm", $data);
		$this->load->view('footer');
	}

	public function cetak_kk($id = 0)
	{
		$data['id_kk'] = $id;

		$data['main'] = $this->rtm_model->list_anggota($id);
		$kk = $this->rtm_model->get_kepala_rtm($id);
		$data['desa'] = $this->rtm_model->get_desa();
		$data['kepala_kk'] = $kk;
		$nav['act'] = 3;
		$header = $this->header_model->get_data();
		$this->load->view("sid/kependudukan/cetak_rtm", $data);
	}

	public function add_anggota($p = 1, $o = 0, $id = 0)
	{
		$this->rtm_model->add_anggota($id);
		redirect("rtm/anggota/$p/$o/$id");
	}

	public function update_anggota($p = 1, $o = 0, $id_kk = 0, $id = 0)
	{
		$this->rtm_model->update_anggota($id, $id_kk);
		redirect("rtm/anggota/$p/$o/$id_kk");
	}

	public function delete_anggota($p = 1, $o = 0, $kk = 0, $id = '')
	{
		$this->session->success = 1;
		$this->redirect_hak_akses('h', "rtm/anggota/$p/$o/$kk");
		$this->rtm_model->rem_anggota($kk, $id);
		redirect("rtm/anggota/$p/$o/$kk");
	}

	public function delete_all_anggota($p = 1, $o = 0, $kk = 0)
	{
		$this->session->success = 1;
		$this->redirect_hak_akses('h', "rtm/anggota/$p/$o/$kk");
		$this->rtm_model->rem_all_anggota($kk);
		redirect("rtm/anggota/$p/$o/$kk");
	}

	/*
		TODO: aktifkan di menu. Kalau tidak diperlukan lagi, hapus
	*/
	public function cetak_statistik($tipe = 0)
	{
		$data['main'] = $this->rtm_model->list_data_statistik($tipe);
		$this->load->view('sid/kependudukan/rtm_print', $data);
	}
}
