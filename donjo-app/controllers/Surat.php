<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('penduduk_model');
		$this->load->model('keluarga_model');
		$this->load->model('surat_model');
		$this->load->model('keluar_model');
		$this->load->model('config_model');
		$this->load->model('referensi_model');
		$this->load->model('penomoran_surat_model');
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
		unset($_SESSION['id_pemberi_kuasa']);
		unset($_SESSION['id_penerima_kuasa']);

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
		$data['input']['atas_nama'] = preg_replace('/\(.+\)/', '', $data['input']['pilih_atas_nama']);
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
		$log_surat['id_pamong'] = $_POST['pamong_id'];
		$log_surat['id_user'] = $_SESSION['user'];
		$log_surat['no_surat'] = $_POST['nomor'];
		$id = $_POST['nik'];
		$keperluan = $_POST['keperluan'];
		$keterangan = $_POST['keterangan'];
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
			case 'surat_kuasa':
				// id-nya pemberi kuasa atau penerima kuasa
				if (!$id) $id = $_POST['id_pemberi_kuasa'];
				if (!$id) $id = $_POST['id_penerima_kuasa'];
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

		$log_surat['keterangan'] = $keterangan ? $keterangan : $keperluan;
		$nama_surat = $this->keluar_model->nama_surat_arsip($url, $nik, $_POST['nomor']);
		$lampiran = '';
		$this->surat_model->buat_surat($url, $nama_surat, $lampiran);
		$log_surat['nama_surat'] = $nama_surat;
		$log_surat['lampiran'] = $lampiran;
		$this->keluar_model->log_surat($log_surat);

		header("location:".base_url(LOKASI_ARSIP.$nama_surat));
	}

	public function nomor_surat_duplikat()
	{
		$hasil = $this->penomoran_surat_model->nomor_surat_duplikat('log_surat', $_POST['nomor'], $_POST['url']);
   	echo $hasil ? 'false' : 'true';
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
		$this->load->model('pamong_model');
		$data['surat_terakhir'] = $this->surat_model->get_last_nosurat_log($url);
		$data['surat'] = $this->surat_model->get_surat($url);
		$data['input'] = $this->input->post();
		$data['input']['nomor'] = $data['surat_terakhir']['no_surat_berikutnya'];
		$data['format_nomor_surat'] = $this->penomoran_surat_model->format_penomoran_surat($data);
		$data['lokasi'] = $this->config_model->get_data();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		$pamong_ttd = $this->pamong_model->get_ttd();
		$pamong_ub = $this->pamong_model->get_ub();
		$data['perempuan'] = $this->surat_model->list_penduduk_perempuan();
		if ($pamong_ttd)
		{
			$str_ttd = ucwords($pamong_ttd['jabatan'].' '.$data['lokasi']['nama_desa']);
			$data['atas_nama'][] = "a.n {$str_ttd}";
			if ($pamong_ub)
				$data['atas_nama'][] = "u.b {$pamong_ub['jabatan']}";
		}
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

	/*
		Ajax POST data:
		url -- url surat
		nomor -- nomor surat
	*/
	public function format_nomor_surat()
	{
		$data['surat'] = $this->surat_model->get_surat($this->input->post('url'));
		$data['input']['nomor'] = $this->input->post('nomor');
		$format_nomor = $this->penomoran_surat_model->format_penomoran_surat($data);
		echo json_encode($format_nomor);
	}

	/*
		Ajax url query data:
		q -- kata pencarian
		page -- nomor paginasi
	*/
	public function list_penduduk_ajax()
	{
		$cari = $this->input->get('q');
		$page = $this->input->get('page');
		$filter_sex = $this->input->get('filter_sex');
		$filter['sex'] = ($filter_sex == 'perempuan') ? 2 : $filter_sex;
		$penduduk = $this->surat_model->list_penduduk_ajax($cari, $filter, $page);
		echo json_encode($penduduk);
	}

	// list untuk dropdown arsip layanan tampil hanya yg bersurat saja
	public function list_penduduk_bersurat_ajax()
	{
		$cari = $this->input->get('q');
		$page = $this->input->get('page');
		$penduduk = $this->surat_model->list_penduduk_bersurat_ajax($cari,$page);
		echo json_encode($penduduk);
	}

}
