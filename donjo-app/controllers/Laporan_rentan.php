<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Laporan Kependudukan
 *
 * donjo-app/controllers/Laporan_rentan.php
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

class Laporan_rentan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('laporan_bulanan_model');
		$this->load->model('config_model');

		//Initialize Session ------------
		$_SESSION['success'] = 0;
		$_SESSION['per_page'] = 20;
		$_SESSION['cari'] = '';
		//-------------------------------

		$this->modul_ini = 3;
		$this->sub_modul_ini = 29;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		redirect('laporan_rentan');
	}

	public function index()
	{
		if (isset($_SESSION['dusun']))
			$data['dusun'] = $_SESSION['dusun'];
		else $data['dusun'] = '';

		$data['list_dusun'] = $this->laporan_bulanan_model->list_dusun();
		$data['config'] = $this->config_model->get_data();
		$data['main'] = $this->laporan_bulanan_model->list_data();
		$this->set_minsidebar(1);
		$this->render('laporan/kelompok', $data);
	}

	public function cetak()
	{
		$data['config'] = $this->config_model->get_data();
		$data['main'] = $this->laporan_bulanan_model->list_data();
		$this->load->view('laporan/kelompok_print', $data);
	}

	public function excel()
	{
		$data['config'] = $this->config_model->get_data();
		$data['main'] = $this->laporan_bulanan_model->list_data();
		$this->load->view('laporan/kelompok_excel', $data);
	}

	public function dusun()
	{
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect('laporan_rentan');
	}
}
