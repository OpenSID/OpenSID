<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup = $this->user_model->sesi_grup($_SESSION['sesi']);
		if ($grup != 1 AND $grup != 2 AND $grup != 3)
		{
			if (empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('penduduk_model');
		$this->load->model('keluarga_model');
		$this->load->model('surat_model');
		$this->load->model('keluar_model');
		$this->load->model('config_model');
		$this->load->model('referensi_model');
		$this->modul_ini = 4;
	}

	public function index()
	{
		$header = $this->header_model->get_data();
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['menu_surat2'] = $this->surat_model->list_surat2();
		$data['surat_favorit'] = $this->surat_model->list_surat_fav();

		// Reset untuk surat yang menggunakan session variable
		unset($_SESSION['id_pria']);
		unset($_SESSION['id_wanita']);
		unset($_SESSION['id_ibu']);
		unset($_SESSION['id_bayi']);
		unset($_SESSION['id_saksi1']);
		unset($_SESSION['id_saksi2']);
		unset($_SESSION['id_pelapor']);
		unset($_SESSION['id_diberi_izin']);
		unset($_SESSION['post']);

		$nav['act'] = 4;
		$nav['act_sub'] = 31;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('surat/format_surat', $data);
		$this->load->view('footer');
	}

	public function panduan()
	{
		$nav['act'] = 4;
		$nav['act_sub'] = 33;
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('surat/panduan');
		$this->load->view('footer');
	}

	public function form($url = '', $clear = '')
	{
		$data['url'] = $url;
		$data['anchor'] = $this->input->post('anchor');
		if (!empty($_POST['nik']))
		{
			$data['individu'] = $this->surat_model->get_penduduk($_POST['nik']);
			$data['anggota'] = $this->keluarga_model->list_anggota($data['individu']['id_kk']);
		}
		else
		{
			$data['individu'] = NULL;
			$data['anggota'] = NULL;
		}
		$this->get_data_untuk_form($url, $data);

		$data['surat_terakhir'] = $this->surat_model->get_last_nosurat_log($url);
		$data['surat_url'] = rtrim($_SERVER['REQUEST_URI'], "/clear");
		$data['form_action'] = site_url("surat/cetak/$url");
		$data['form_action2'] = site_url("surat/doc/$url");
		$nav['act'] = 4;
		$nav['act_sub'] = 31;
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view("surat/form_surat", $data);
		$this->load->view('footer');
	}

	public function cetak($url = '')
	{
		$log_surat['url_surat'] = $url;
		$log_surat['pamong_nama'] = $_POST['pamong'];
		$log_surat['id_user'] = $_SESSION['user'];
		$log_surat['no_surat'] = $_POST['nomor'];

		$id = $_POST['nik'];
		$log_surat['id_pend'] = $id;
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));

		$data['data'] = $this->surat_model->get_data_surat($id);

		$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['kk'] = $this->surat_model->get_data_kk($id);
		$data['ayah'] = $this->surat_model->get_data_ayah($id);
		$data['ibu'] = $this->surat_model->get_data_ibu($id);

		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);

		$data['pengikut'] = $this->surat_model->pengikut();
		$data['anggota'] = $this->keluarga_model->list_anggota($data['kk']['id_kk']);
		$this->keluar_model->log_surat($log_surat);

		$data['url'] = $url;
		$this->load->view("surat/print_surat", $data);
	}

	public function doc($url = '')
	{
		$format = $this->surat_model->get_surat($url);
		$log_surat['url_surat'] = $format['id'];
		$log_surat['pamong_nama'] = $_POST['pamong'];
		$log_surat['id_user'] = $_SESSION['user'];
		$log_surat['no_surat'] = $_POST['nomor'];
		$id = $_POST['nik'];
		switch ($url)
		{
			case 'surat_ket_kelahiran':
				// surat_ket_kelahiran id-nya ibu atau bayi
				if (!$id) $id = $_SESSION['id_ibu'];
				if (!$id) $id = $_SESSION['id_bayi'];
				break;
			case 'surat_ket_nikah':
				// id-nya calon pasangan pria atau wanita
				if (!$id) $id = $_POST['id_pria'];
				if (!$id) $id = $_POST['id_wanita'];
				break;
			default:
				# code...
				break;
		}

		if ($id)
		{
			$log_surat['id_pend'] = $id;
			$nik = $this->db->select('nik')->where('id', $id)->get('tweb_penduduk')
					->row()->nik;
		}
		else
		{
			// Surat untuk non-warga
			$log_surat['nama_non_warga'] = $_POST['nama_non_warga'];
			$log_surat['nik_non_warga'] = $_POST['nik_non_warga'];
			$nik = $log_surat['nik_non_warga'];
		}

		$nama_surat = $this->keluar_model->nama_surat_arsip($url, $nik, $_POST['nomor']);
		$lampiran = '';
		$this->surat_model->buat_surat($url, $nama_surat, $lampiran);
		$log_surat['nama_surat'] = $nama_surat;
		$log_surat['lampiran'] = $lampiran;
		$this->keluar_model->log_surat($log_surat);

		header("location:".base_url(LOKASI_ARSIP.$nama_surat));
	}

	public function search()
	{
		$cari = $this->input->post('nik');
		if ($cari != '')
			redirect("surat/form/$cari");
		else
			redirect('surat');
	}

	private function get_data_untuk_form($url, &$data)
	{
		$data['lokasi'] = $this->config_model->get_data();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		$data['perempuan'] = $this->surat_model->list_penduduk_perempuan();

		$data_form = $this->surat_model->get_data_form($url);
		if (is_file($data_form))
			include($data_form);
	}

	public function favorit($id = 0, $k = 0)
	{
		$this->load->model('surat_master_model');
		$this->surat_master_model->favorit($id, $k);
		redirect("surat");
	}

}
