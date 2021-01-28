<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Buku Administrasi Penduduk > Buku Induk Penduduk
 *
 * donjo-app/views/controllers/Bumindes_penduduk_induk.php,
 *
 */

/**
 *
 * File ini bagian dari:
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

class Bumindes_penduduk_induk extends Admin_Controller {

	private $_set_page;
	private $_list_session;

	public function __construct()
	{
		parent::__construct();

		$this->load->model(['pamong_model', 'penduduk_model']);

		$this->modul_ini = 301;
		$this->sub_modul_ini = 303;

		$this->_set_page = ['10', '20', '50', '100'];
		$this->_list_session = ['filter_tahun', 'filter_bulan', 'filter', 'status_dasar', 'cari', 'status', 'status_penduduk'];

		$this->session->per_page = 10;
	}

	public function index($page_number=1, $offset=0)
	{
		// Uncomment untuk menampilkan profil eksekusi
		// $this->output->enable_profiler(TRUE);

		$data['main_content'] = "bumindes/penduduk/induk/content_induk";
		$data['subtitle'] = "Buku Induk Penduduk";
		$data['selected_nav'] = 'induk';
		$data['p'] = $page_number;
		$data['o'] = $offset;

		// set session
		if ($this->session->cari)
			$data['cari'] = $this->session->cari;
		else $data['cari'] = '';

		if ($this->session->filter)
			$data['filter'] = $this->session->filter;
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $this->session->per_page;
		// set session END

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		// Set session untuk bulan dan tahun
		if ($this->session->filter_bulan)
			$data['bulan'] = $this->session->filter_bulan;
		if ($this->session->filter_tahun)
			$data['tahun'] = $this->session->filter_tahun;

		// Hanya menampilkan data status_dasar HIDUP, HILANG
		$this->session->status_dasar = array(1, 4);

		// Menampilkan hanya status penduduk TETAP
		$this->session->status_penduduk = 1;

		$data['func'] = 'index';
		$data['set_page'] = $this->_set_page;
		$data['paging'] = $this->penduduk_model->paging($page_number, $offset);

		$data['main'] = $this->penduduk_model->list_data($offset, $data['paging']->offset, $data['paging']->per_page);
		$data['list_tahun'] = $this->penduduk_log_model->list_tahun();

		$this->set_minsidebar(1);
		$this->render('bumindes/penduduk/main', $data);
	}

	private function clear_session()
	{
		$this->session->unset_userdata($this->_list_session);
		$this->session->per_page = $this->_set_page[0];
	}

	public function clear()
	{
		$this->clear_session();
		// Set default filter ke tahun dan bulan sekarang
		$this->session->filter_tahun = date('Y');
		$this->session->filter_bulan = date('m');
		redirect('bumindes_penduduk_induk');
	}

	public function ajax_cetak($o = 0, $aksi = '')
	{
		$data['o'] = $o;
		
		// pengaturan data untuk dialog cetak/unduh
		$data['aksi'] = $aksi;
		$data['form_action'] = site_url("bumindes_penduduk_induk/cetak/$o/$aksi");
		$data['form_action_privasi'] = site_url("bumindes_penduduk_induk/cetak/$o/$aksi/1");
		$data['isi'] = "bumindes/penduduk/induk/ajax_cetak_bersama";

		$this->load->view('global/dialog_cetak', $data);
	}

	public function cetak($o = 0, $aksi = '', $privasi_nik = 0)
	{
		$data['aksi'] = $aksi;
		$data['config'] = $this->header['desa'];
		$data['pamong_ketahui'] = $this->pamong_model->get_ttd();
		$data['pamong_ttd'] = $this->pamong_model->get_ub();
		$data['main'] = $this->penduduk_model->list_data($o, NULL, NULL);
		$data['bulan'] = $this->session->filter_bulan;
		$data['tahun'] = $this->session->filter_tahun;
		$data['tgl_cetak'] = $_POST['tgl_cetak'];
		if ($privasi_nik == 1) $data['privasi_nik'] = true;

		// pengaturan data untuk format cetak/unduh
		$data['file'] = "Buku Induk Kependudukan";
		$data['isi'] = "bumindes/penduduk/induk/content_induk_cetak";
		$data['letak_ttd'] = ['2', '2', '9'];

		$this->load->view('global/format_cetak', $data);
	}

	public function autocomplete()
	{
		$data = $this->penduduk_model->autocomplete($this->input->post('cari'));
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != "")
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('bumindes_penduduk_induk');
	}
}
