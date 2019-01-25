<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lapor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->grup = $this->user_model->sesi_grup($_SESSION['sesi']);
		if ($this->grup != 1 AND $this->grup != 2 AND $this->grup != 3)
		{
			$this->auth = false;
		}
		else
		{
			$this->auth = true;
		}
		$this->load->model('header_model');
		$this->load->model('web_komentar_model');
		$this->load->model('lapor_model');
		$this->load->model('config_model');
		$this->modul_ini = 14;
	}

	private function redirect_auth()
	{
		if (empty($this->grup))
			$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
		else
			unset($_SESSION['request_uri']);
		redirect('siteman');
	}

	public function clear()
	{
		if (!$this->auth) $this->redirect_auth();
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('lapor');
	}

	public function index($p = 1, $o = 0)
	{
		if (!$this->auth) $this->redirect_auth();
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

		$data['paging'] = $this->web_komentar_model->paging($p, $o, 2);
		$data['main'] = $this->web_komentar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page, 2);
		$data['keyword'] = $this->web_komentar_model->autocomplete();

		$header = $this->header_model->get_data();
		$nav['act'] = 14;
		$nav['act_sub'] = 55;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('lapor/table', $data);
		$this->load->view('footer');
	}

	public function search()
	{
		if (!$this->auth) $this->redirect_auth();
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('lapor');
	}

	public function filter()
	{
		if (!$this->auth) $this->redirect_auth();
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('lapor');
	}

	/**
	 * Kirim laporan pengguna layanan mandiri
	 *
	 * Diakses dari web untuk pengguna layanan mandiri
	 * Tidak memerlukan login pengguna modul admin
	 */
	public function insert()
	{
		if ($_SESSION['mandiri'] != 1)
		{
			redirect('first');
		}

		$_SESSION['success'] = 1;
		$res = $this->lapor_model->insert();
		$data['data_config'] = $this->config_model->get_data();
		// cek kalau berhasil disimpan dalam database
		if ($res)
		{
			$this->session->set_flashdata('flash_message', 'Laporan anda telah berhasil dikirim dan akan segera diproses.');
		}
		else
		{
			$_SESSION['post'] = $_POST;
			if (!empty($_SESSION['validation_error']))
				$this->session->set_flashdata('flash_message', validation_errors());
			else
				$this->session->set_flashdata('flash_message', 'Laporan anda gagal dikirim. Silakan ulangi lagi.');
		}

		redirect("first/mandiri/1/3");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		if (!$this->auth) $this->redirect_auth();
		$this->web_komentar_model->delete($id);
		redirect("lapor/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		if (!$this->auth) $this->redirect_auth();
		$this->web_komentar_model->delete_all();
		redirect("lapor/index/$p/$o");
	}

	public function komentar_lock($id = '')
	{
		if (!$this->auth) $this->redirect_auth();
		$this->web_komentar_model->komentar_lock($id, 1);
		redirect("lapor/index/$p/$o");
	}

	public function komentar_unlock($id = '')
	{
		if (!$this->auth) $this->redirect_auth();
		$this->web_komentar_model->komentar_lock($id, 2);
		redirect("lapor/index/$p/$o");
	}
}
