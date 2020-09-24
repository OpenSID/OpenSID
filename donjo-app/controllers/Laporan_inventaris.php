<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Inventaris
 *
 * donjo-app/controllers/Laporan_inventaris.php
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

class Laporan_inventaris extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('inventaris_laporan_model');
		$this->load->model('referensi_model');
		$this->load->model('config_model');
		$this->load->model('surat_model');
		$this->modul_ini = 15;
		$this->sub_modul_ini = 61;
		$this->tab_ini = 7;
	}

	public function index()
	{
		$data['pamong'] = $this->surat_model->list_pamong();

		$data = array_merge($data, $this->inventaris_laporan_model->laporan_inventaris());
		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('inventaris/laporan/table', $data);
	}

	public function cetak($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['tahun'] = $tahun;
		$data['pamong'] = $this->inventaris_laporan_model->pamong($penandatangan);
		$data = array_merge($data, $this->inventaris_laporan_model->cetak_inventaris($tahun));
		$this->load->view('inventaris/laporan/inventaris_print', $data);
	}

	public function download($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['tahun'] = $tahun;
		$data['pamong'] = $this->inventaris_laporan_model->pamong($penandatangan);
		$data = array_merge($data, $this->inventaris_laporan_model->cetak_inventaris($tahun));
		$this->load->view('inventaris/laporan/inventaris_excel', $data);
	}

	public function mutasi()
	{
		$data['pamong'] = $this->surat_model->list_pamong();

		$data['tip'] = 2;
		$this->set_minsidebar(1);
		$data = array_merge($data, $this->inventaris_laporan_model->mutasi_laporan_inventaris());

		$this->render('inventaris/laporan/table_mutasi', $data);
	}

	public function cetak_mutasi($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['tahun'] = $tahun;
		$data['pamong'] = $this->inventaris_laporan_model->pamong($penandatangan);
		$data = array_merge($data, $this->inventaris_laporan_model->mutasi_cetak_inventaris($tahun));
		$this->load->view('inventaris/laporan/inventaris_print_mutasi', $data);
	}

	public function download_mutasi($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['tahun'] = $tahun;
		$data['pamong'] = $this->inventaris_laporan_model->pamong($penandatangan);
		$data = array_merge($data, $this->inventaris_laporan_model->mutasi_cetak_inventaris($tahun));
		$this->load->view('inventaris/laporan/inventaris_excel_mutasi', $data);
	}
}