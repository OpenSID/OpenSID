<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mailbox extends Admin_Controller {

	private $kembali;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('header_model');
		$this->load->model('mandiri_model');
		$this->load->model('mailbox_model');
		$this->load->model('config_model');
		$this->modul_ini = 14;
		$this->sub_modul_ini = 55;
		$this->kembali = $_SERVER['HTTP_REFERER'];
	}

	public function clear($kat = 1, $p = 1, $o = 0)
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['filter_nik']);
		redirect("mailbox/index/$kat/$p/$o");
	}

	public function index($kat = 1, $p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['kat'] = $kat;

		$list_session = array('cari', 'filter', 'filter_nik');

		foreach ($list_session as $session) {
			$data[$session] = $this->session->userdata($session) ?: '';
		}

		if ($nik = $this->session->userdata('filter_nik')) {
			$data['individu'] = $this->mandiri_model->get_pendaftar_mandiri($nik);
		}

		if ($per_page = $this->input->post('per_page')) {
			$this->session->set_userdata('per_page', $per_page);
		}

		
		$data['per_page'] = $_SESSION['per_page'];
		$data['paging'] = $this->mailbox_model->paging($p, $o, $kat);
		$data['main'] = $this->mailbox_model->list_data($o, $data['paging']->offset, $data['paging']->per_page, $kat);
		$data['owner'] = $kat == 1 ? 'Pengirim' : 'Penerima';
		$data['keyword'] = $this->mailbox_model->autocomplete();
		$data['submenu'] = $this->mailbox_model->list_menu();
		$_SESSION['submenu'] = $kat;

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('mailbox/table', $data);
		$this->load->view('footer');
	}

	public function form()
	{
		if (!empty($nik = $this->input->post('nik'))) {
			$data['individu'] = $this->mandiri_model->get_pendaftar_mandiri($nik);
		}
		if (!empty($subjek = $this->input->post('subjek'))) {
			$data['subjek'] = $subjek;
		}
		$data['form_action'] = site_url("mailbox/kirim_pesan");
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('mailbox/form', $data);
		$this->load->view('footer');
	}

	public function kirim_pesan()
	{
		$data['id_pengirim'] = $_SESSION['user'];
		$data['id_penerima'] = $this->input->post('id_penerima');
		$this->mailbox_model->insert($data);
		redirect('mailbox/index/2');
	}

	public function baca_pesan($kat = 1, $id)
	{	
		$this->mailbox_model->baca($id, 1);
		
		$data['kat'] = $kat;
		$data['owner'] = $kat == 1 ? 'Pengirim' : 'Penerima';
		$data['pesan'] = $this->mailbox_model->get_mailbox($id, $kat);
		$data['tipe_mailbox'] = $this->mailbox_model->get_kat_nama($kat); 
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('mailbox/detail', $data);
		$this->load->view('footer');
	}

	public function filter_nik($kat = 1)
	{
		$nik = $this->input->post('nik');
		if (!empty($nik) AND $nik != 0) 
			$_SESSION['filter_nik'] = $nik;
		else unset($_SESSION['filter_nik']);
		redirect("mailbox/index/{$kat}");
	}

	public function filter($kat = 1)
	{
		$filter = $this->input->post('filter');
		if ($filter != '')
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect("mailbox/index/{$kat}");
	}

	public function search($kat = 1)
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['filter_nik'] = $nik;
		else unset($_SESSION['filter_nik']);
		redirect("mailbox/index/{$kat}");
	}	

	public function list_pendaftar_mandiri_ajax()
	{
		$cari = $this->input->get('q');
		$page = $this->input->get('page');
		$list_pendaftar_mandiri = $this->mandiri_model->list_data_ajax($cari, $page);
		echo json_encode($list_pendaftar_mandiri);
	}

	/**
	 * Kirim mailboxan pengguna layanan mandiri
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
		$res = $this->mailbox_model->insert();
		$data['data_config'] = $this->config_model->get_data();
		// cek kalau berhasil disimpan dalam database
		if ($res)
		{
			$this->session->set_flashdata('flash_message', 'pesan anda telah berhasil dikirim dan akan segera diproses.');
		}
		else
		{
			$_SESSION['post'] = $_POST;
			if (!empty($_SESSION['validation_error']))
				$this->session->set_flashdata('flash_message', validation_errors());
			else
				$this->session->set_flashdata('flash_message', 'pesan anda gagal dikirim. Silakan ulangi lagi.');
		}

		redirect("first/mandiri/1/3");
	}

	public function archive($id = '')
	{
		$this->redirect_hak_akses('h', $this->kembali);
		$this->mailbox_model->archive($id);
		redirect($this->kembali);
	}

	public function archive_all()
	{
		$this->redirect_hak_akses('h', $this->kembali);
		$this->mailbox_model->archive_all();
		redirect($this->kembali);
	}

	public function baca($id = '', $baca)
	{
		$this->mailbox_model->baca($id, $baca);
		redirect("mailbox");
	}
}
