<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Surat Keluar
 *
 * donjo-app/controllers/Surat_keluar.php
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

class Surat_keluar extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		// Untuk bisa menggunakan helper force_download()
		$this->load->helper('download');
		$this->load->model('surat_keluar_model');
		$this->load->model('klasifikasi_model');
		$this->load->model('config_model');
		$this->load->model('pamong_model');

		$this->load->model('penomoran_surat_model');
		$this->modul_ini = 15;
		$this->sub_modul_ini = 58;
	}

	public function clear($id = 0)
	{
		$_SESSION['per_page'] = 20;
		$_SESSION['surat'] = $id;
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('surat_keluar');
	}

	public function index($p = 1, $o = 2)
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
		$data['paging'] = $this->surat_keluar_model->paging($p, $o);
		$data['main'] = $this->surat_keluar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['keyword'] = $this->surat_keluar_model->autocomplete();
		$this->set_minsidebar(1);
		$this->render('surat_keluar/table', $data);
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['tujuan'] = $this->surat_keluar_model->autocomplete();
		$data['klasifikasi'] = $this->klasifikasi_model->list_kode();
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['surat_keluar'] = $this->surat_keluar_model->get_surat_keluar($id);
			$data['form_action'] = site_url("surat_keluar/update/$p/$o/$id");
		}
		else
		{
			$last_surat = $this->penomoran_surat_model->get_surat_terakhir('surat_keluar');
			$data['surat_keluar']['nomor_urut'] = $last_surat['no_surat'] + 1;
			$data['form_action'] = site_url("surat_keluar/insert");
		}

		// Buang unique id pada link nama file
		$berkas = explode('__sid__', $data['surat_keluar']['berkas_scan']);
		$namaFile = $berkas[0];
		$ekstensiFile = explode('.', end($berkas));
		$ekstensiFile = end($ekstensiFile);
		$data['surat_keluar']['berkas_scan'] = $namaFile.'.'.$ekstensiFile;
		$this->set_minsidebar(1);

		$this->render('surat_keluar/form', $data);
	}

	public function form_upload($p = 1, $o = 0, $url = '')
	{
		$data['form_action'] = site_url("surat_keluar/upload/$p/$o/$url");
		$this->load->view('surat_keluar/ajax-upload', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('surat_keluar');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0) $_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('surat_keluar');
	}

	public function insert()
	{
		$this->surat_keluar_model->insert();
		redirect('surat_keluar');
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$this->surat_keluar_model->update($id);
		redirect("surat_keluar/index/$p/$o");
	}

	public function upload($p = 1, $o = 0, $url = '')
	{
		$this->surat_keluar_model->upload($url);
		redirect("surat_keluar/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "surat_keluar/index/$p/$o");
		$this->surat_keluar_model->delete($id);
		redirect("surat_keluar/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h',"surat_keluar/index/$p/$o");
		$this->surat_keluar_model->delete_all();
		redirect("surat_keluar/index/$p/$o");
	}

	public function dialog_cetak($o = 0)
	{
		$data['aksi'] = "Cetak";
		$data['pamong'] = $this->pamong_model->list_data();
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['form_action'] = site_url("surat_keluar/cetak/$o");
		$this->load->view('surat_keluar/ajax_cetak', $data);
	}

	public function dialog_unduh($o = 0)
	{
		$data['aksi'] = "Unduh";
		$data['pamong'] = $this->pamong_model->list_data();
		$data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
		$data['form_action'] = site_url("surat_keluar/unduh/$o");
		$this->load->view('surat_keluar/ajax_cetak', $data);
	}

	public function cetak($o = 0)
	{
		$data['input'] = $_POST;
		$_SESSION['filter'] = $data['input']['tahun'];
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
		$data['desa'] = $this->config_model->get_data();
		$data['main'] = $this->surat_keluar_model->list_data($o, 0, 10000);
		$this->load->view('surat_keluar/surat_keluar_print', $data);
	}

	public function unduh($o = 0)
	{
		$data['input'] = $_POST;
		$_SESSION['filter'] = $data['input']['tahun'];
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
		$data['desa'] = $this->config_model->get_data();
		$data['main'] = $this->surat_keluar_model->list_data($o, 0, 10000);
		$this->load->view('surat_keluar/surat_keluar_excel', $data);
	}

	/**
	 * Unduh berkas scan berdasarkan kolom surat_keluar.id
	 * @param   integer  $idSuratMasuk  Id berkas scan pada koloam surat_keluar.id
	 * @return  void
	 */
	public function unduh_berkas_scan($idSuratMasuk)
	{
		// Ambil nama berkas dari database
		$berkas = $this->surat_keluar_model->getNamaBerkasScan($idSuratMasuk);
		ambilBerkas($berkas, 'surat_keluar', '__sid__');
	}

	public function nomor_surat_duplikat()
	{
		if ($_POST['nomor_urut'] == $_POST['nomor_urut_lama'])
			$hasil = false;
		else
			$hasil = $this->penomoran_surat_model->nomor_surat_duplikat('surat_keluar', $_POST['nomor_urut']);
   	echo $hasil ? 'false' : 'true';
	}
}
