<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Layanan Surat
 *
 * donjo-app/controllers/Surat.php
 *
 */
/*
 *  File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Surat extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('penduduk_model');
		$this->load->model('keluarga_model');
		$this->load->model('surat_model');
		$this->load->model('keluar_model');
		$this->load->model('config_model');
		$this->load->model('referensi_model');
		$this->load->model('penomoran_surat_model');
		$this->load->model('permohonan_surat_model');
		$this->modul_ini = 4;
		$this->sub_modul_ini = 31;
	}

	public function index()
	{

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

		$this->render('surat/format_surat', $data);
	}

	public function panduan()
	{
		$this->sub_modul_ini = 33;

		$this->render('surat/panduan');
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
		$data['form_action'] = site_url("surat/doc/$url");
		$data['masa_berlaku'] = $this->surat_model->masa_berlaku_surat($url);

		$this->set_minsidebar(1);
		$this->render("surat/form_surat", $data);
	}

	public function periksa_doc($id, $url)
	{
		// Ganti status menjadi 'Menunggu Tandatangan'
		$this->permohonan_surat_model->update_status($id, array('status' => 2));
		$this->cetak_doc($url);
	}

	public function doc($url = '')
	{
		$this->cetak_doc($url);
	}

	private function cetak_doc($url)
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

		if ($lampiran)
		{
			$nama_file = str_replace('rtf', 'zip', $nama_surat);
			$berkas_zip = array();
			$berkas_zip[] = LOKASI_ARSIP.$nama_surat;
			$berkas_zip[] = LOKASI_ARSIP.$lampiran;
			# Masukkan semua berkas ke dalam zip
			$berkas_zip = masukkan_zip($berkas_zip);
	    # Unduh berkas zip
	    header('Content-disposition: attachment; filename='.$nama_file.'.zip');
	    header('Content-type: application/zip');
			header($this->security->get_csrf_token_name().':'.$this->security->get_csrf_hash());
	    readfile($berkas_zip);
		}
		else
		{
			header($this->security->get_csrf_token_name().':'.$this->security->get_csrf_hash());
			header("location:".base_url(LOKASI_ARSIP.$nama_surat));
		}
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
