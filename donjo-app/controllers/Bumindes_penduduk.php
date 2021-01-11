<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Kependudukan > Penduduk
 *
 * donjo-app/views/controllers/Penduduk.php,
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

class Bumindes_penduduk extends Admin_Controller {

	private $_set_page;
	private $_list_session;

	public function __construct()
	{
		parent::__construct();

		$this->load->model(['pamong_model', 'header_model', 'penduduk_model', 'keluarga_model', 'wilayah_model', 'referensi_model', 'web_dokumen_model', 'program_bantuan_model', 'lapor_model']);
		$this->load->library('session');

		$this->modul_ini = 301;
		$this->_set_page = ['20', '50', '100'];
		$this->_list_session = ['filter', 'status_dasar', 'sex', 'agama', 'dusun', 'rw', 'rt', 'cari', 'umur_min', 'umur_max', 'umurx', 'pekerjaan_id', 'status', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk', 'judul_statistik', 'cacat', 'cara_kb_id', 'akta_kelahiran', 'status_ktp', 'id_asuransi', 'status_covid', 'penerima_bantuan', 'log', 'warganegara', 'menahun', 'hubungan', 'golongan_darah', 'hamil', 'kumpulan_nik'];
	}

	// global function for all menu
	public function index($page='induk', $page_number=1, $offset=0)
	{
		$this->tables($page, $page_number, $offset);
	}

	private function clear_session()
	{
		$this->session->unset_userdata($this->_list_session);
		$this->session->status_dasar = 1; // default status dasar = hidup
		$this->session->per_page = $this->_set_page[0];
	}

	public function clear()
	{
		$this->clear_session();
		redirect('bumindes_penduduk');
	}

	public function tables($page='induk', $page_number=1, $offset=0)
	{
		$this->sub_modul_ini = 303;

		// set session
		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		// set session END

		// load data for displaying at tables
		$data = array_merge($data, $this->load_data_tables($page, $page_number, $offset));

		$this->set_minsidebar(1);
		$this->load->view('header', $this->header);
		$this->load->view('nav');
		$this->load->view('bumindes/penduduk/main', $data);
		$this->load->view('footer');
	}

	private function load_data_tables($page=null, $page_number=1, $offset=0)
	{
		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'induk':
				$data = array_merge($data, $this->load_induk_data_tables($page_number, $offset));
				break;

			case 'mutasi':
				$data = array_merge($data, $this->load_mutasi_data_tables($page_number, $offset));
				break;

			case 'rekapitulasi':
				$data = array_merge($data, $this->load_rekapitulasi_data_tables($page_number, $offset));
				break;

			case 'sementara':
				$data = array_merge($data, $this->load_sementara_data_tables($page_number, $offset));
				break;

			case 'ktpkk':
				$data = array_merge($data, $this->load_ktpkk_data_tables($page_number, $offset));
				break;

			default:
				$data = array_merge($data, $this->load_induk_data_tables($page_number, $offset));
				break;
		}
		return $data;
	}

	public function ajax_cetak($page='induk', $o = 0, $aksi = '')
	{
		$data['o'] = $o;
		$data['aksi'] = $aksi;
		$data['pamong'] = $this->pamong_model->list_data();
		$data['form_action'] = site_url("bumindes_penduduk/cetak/$page/$o/$aksi");
		$data['form_action_privasi'] = site_url("bumindes_penduduk/cetak/$page/$o/$aksi/1");

		$this->load->view("bumindes/penduduk/ajax_cetak_bersama", $data);
	}

	public function cetak($page='induk', $o = 0, $aksi = '', $privasi_nik = 0)
	{
		$data['main'] = $this->penduduk_model->list_data($o, 0);
		$data['desa'] = $this->config_model->get_data();
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);

		if ($privasi_nik == 1) $data['privasi_nik'] = true;
		$this->load->view("bumindes/penduduk/content_".$page."_".$aksi, $data);
	}

	public function autocomplete()
	{
		$data = $this->penduduk_model->autocomplete($this->input->post('cari'));
		echo json_encode($data);
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != "")
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('bumindes_penduduk');
	}

	// end global function

	// function for buku induk penduduk
	private function load_induk_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/content_induk";
		$data['subtitle'] = "Buku Induk Penduduk";
		$data['p'] = $page_number;
		$data['o'] = $offset;

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['func'] = 'tables/induk';
		$data['set_page'] = $this->_set_page;
		$data['paging'] = $this->penduduk_model->paging($page_number, $offset);
		$data['main'] = $this->penduduk_model->list_data($offset, $data['paging']->offset, $data['paging']->per_page);

		return $data;
	}
	// end function buku induk penduduk 

	// function for buku mutasi penduduk
	private function load_mutasi_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/content_mutasi";
		$data['subtitle'] = "Buku Mutasi Penduduk";

		return $data;
	}
	// end function buku mutasi penduduk

	// function for buku rekapitulasi penduduk
	private function load_rekapitulasi_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/content_rekapitulasi";
		$data['subtitle'] = "Buku Rekapitulasi Jumlah Penduduk";

		return $data;
	}
	// end function buku rekapitulasi penduduk

	// function for buku penduduk sementara
	private function load_sementara_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/content_sementara";
		$data['subtitle'] = "Buku Penduduk Sementara";

		return $data;
	}
	// end function buku penduduk sementara

	// function for buku ktp dan kk penduduk
	private function load_ktpkk_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/content_ktp_kk";
		$data['subtitle'] = "Buku KTP dan KK";

		return $data;
	}
	// end function buku ktp dan kk penduduk
}
