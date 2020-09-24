<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Laporan Kependudukan
 *
 * donjo-app/controllers/Laporan.php
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

class Laporan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('laporan_bulanan_model');
		$this->load->model('pamong_model');
		$this->load->model('config_model');
		$this->controller = 'laporan';

		//Initialize Session ------------
		$_SESSION['success'] = 0;
		$_SESSION['cari'] = '';
		//-------------------------------

		$this->modul_ini = 3;
		$this->sub_modul_ini = 28;
	}

	public function clear()
	{
		$_SESSION['bulanku'] = date("n");
		$_SESSION['tahunku'] = date("Y");
		$_SESSION['per_page'] = 200;
		redirect('laporan');
	}

	public function index($lap = 0, $p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		if (isset($_SESSION['bulanku']))
			$data['bulanku'] = $_SESSION['bulanku'];
		else
		{
			$data['bulanku'] = date("n");
			$_SESSION['bulanku'] = $data['bulanku'];
		}

		if (isset($_SESSION['tahunku']))
			$data['tahunku'] = $_SESSION['tahunku'];
		else
		{
			$data['tahunku'] = date("Y");
			$_SESSION['tahunku'] = $data['tahunku'];
		}

		$data['bulan'] = $data['bulanku'];
		$data['tahun'] = $data['tahunku'];
		$data['config'] = $this->config_model->get_data();
		$data['pamong'] = $this->pamong_model->list_data();
		$data['penduduk_awal'] = $this->laporan_bulanan_model->penduduk_awal();
		$data['penduduk_akhir'] = $this->laporan_bulanan_model->penduduk_akhir();
		$data['kelahiran'] = $this->laporan_bulanan_model->kelahiran();
		$data['kematian'] = $this->laporan_bulanan_model->kematian();
		$data['pendatang'] = $this->laporan_bulanan_model->pendatang();
		$data['pindah'] = $this->laporan_bulanan_model->pindah();
		$data['hilang'] = $this->laporan_bulanan_model->hilang();
		$data['lap'] = $lap;

		$this->render('laporan/bulanan', $data);
	}

	public function dialog_cetak()
	{
		$data['aksi'] = "Cetak";
		$data['pamong'] = $this->pamong_model->list_data();
		$data['form_action'] = site_url("laporan/cetak");
		$this->load->view('laporan/ajax_cetak', $data);
	}

	public function dialog_unduh()
	{
		$data['aksi'] = "Unduh";
		$data['pamong'] = $this->pamong_model->list_data();
		$data['form_action'] = site_url("laporan/unduh");
		$this->load->view('laporan/ajax_cetak', $data);
	}

	public function cetak()
	{
		$data = $this->data_cetak();
		$this->load->view('laporan/bulanan_print', $data);
	}

	public function unduh()
	{
		$data = $this->data_cetak();
		$this->load->view('laporan/bulanan_excel', $data);
	}

	private function data_cetak()
	{
		$data = array();
		$data['config'] = $this->config_model->get_data();
		$data['bulan'] = $_SESSION['bulanku'];
		$data['tahun'] = $_SESSION['tahunku'];
		$data['bln'] = $this->laporan_bulanan_model->bulan($data['bulan']);
		$data['penduduk_awal'] = $this->laporan_bulanan_model->penduduk_awal();
		$data['penduduk_akhir'] = $this->laporan_bulanan_model->penduduk_akhir();
		$data['kelahiran'] = $this->laporan_bulanan_model->kelahiran();
		$data['kematian'] = $this->laporan_bulanan_model->kematian();
		$data['pendatang'] = $this->laporan_bulanan_model->pendatang();
		$data['pindah'] = $this->laporan_bulanan_model->pindah();
		$data['rincian_pindah'] = $this->laporan_bulanan_model->rincian_pindah();
		$data['hilang'] = $this->laporan_bulanan_model->hilang();
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		return $data;
	}

	public function bulan()
	{
		$bulanku = $this->input->post('bulan');
		if ($bulanku != "")
			$_SESSION['bulanku'] = $bulanku;
		else unset($_SESSION['bulanku']);

		$tahunku = $this->input->post('tahun');
		if ($tahunku != "")
			$_SESSION['tahunku'] = $tahunku;
		else unset($_SESSION['tahunku']);
		redirect('laporan');
	}
}