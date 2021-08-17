<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Buku Administrasi Penduduk > Buku Penduduk Sementara
 *
 * donjo-app/views/controllers/Bumindes_penduduk_sementara.php,
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

class Bumindes_penduduk_sementara extends Admin_Controller {

	private $_set_page;
	private $_list_session;

	public function __construct()
	{
		parent::__construct();

		$this->load->model(['pamong_model', 'penduduk_model', 'referensi_model']);

		$this->modul_ini = 301;
		$this->sub_modul_ini = 303;

		$this->_set_page = ['10', '20', '50', '100'];
		$this->_list_session = ['filter', 'status_dasar', 'sex', 'agama', 'dusun', 'rw', 'rt', 'cari', 'umur_min', 'umur_max', 'umurx', 'pekerjaan_id', 'status', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk', 'judul_statistik', 'cacat', 'cara_kb_id', 'akta_kelahiran', 'status_ktp', 'id_asuransi', 'status_covid', 'penerima_bantuan', 'log', 'warganegara', 'menahun', 'hubungan', 'golongan_darah', 'hamil', 'kumpulan_nik'];

		$_SESSION['per_page'] = 10;
	}

	public function index($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/penduduk/sementara/content_sementara";
		$data['subtitle'] = "Buku Penduduk Sementara";
		$data['selected_nav'] = 'sementara';
		$data['p'] = $page_number;
		$data['o'] = $offset;

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

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['func'] = 'index';
		$data['set_page'] = $this->_set_page;
		$data['paging'] = $this->penduduk_model->paging($page_number, $offset);

		// hanya menampilkan data status_dasar 1 dan status_penduduk 1
		$this->session->status_dasar = 1;
		$this->session->status_penduduk = 1;
		$data['main'] = $this->penduduk_model->list_data($offset, $data['paging']->offset, $data['paging']->per_page);
		
		$this->set_minsidebar(1);
		$this->render('bumindes/penduduk/main', $data);
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
		redirect('bumindes_penduduk_sementara');
	}

	public function ajax_cetak($o = 0, $aksi = '')
	{
		$data['o'] = $o;
		$data['aksi'] = $aksi;
		$data['form_action'] = site_url("bumindes_penduduk_sementara/cetak/$o/$aksi");
		$data['form_action_privasi'] = site_url("bumindes_penduduk_sementara/cetak/$o/$aksi/1");

		$this->load->view("bumindes/penduduk/sementara/ajax_cetak_bersama", $data);
	}

	public function cetak($o = 0, $aksi = '', $privasi_nik = 0)
	{
		$data['main'] = $this->penduduk_model->list_data($o, 0);
		$data['desa'] = $this->header['desa'];
		$data['pamong_ketahui'] = $this->pamong_model->get_ttd();
		$data['pamong_ttd'] = $this->pamong_model->get_ub();

		if ($privasi_nik == 1) $data['privasi_nik'] = true;
		$this->load->view("bumindes/penduduk/sementara/content_sementara_".$aksi, $data);
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
		redirect('bumindes_penduduk_sementara');
	}
}
