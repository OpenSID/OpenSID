<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Inventaris
 *
 * donjo-app/controllers/Inventaris_kontruksi.php
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

class Inventaris_kontruksi extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('inventaris_kontruksi_model');
		$this->load->model('referensi_model');
		$this->load->model('surat_model');
		$this->modul_ini = 15;
		$this->sub_modul_ini = 61;
		$this->tab_ini = 6;
		$this->tipe = 'inventaris_kontruksi';
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('inventaris');
	}

	public function index()
	{
		$data['main'] = $this->inventaris_kontruksi_model->list_inventaris();
		$data['total'] = $this->inventaris_kontruksi_model->sum_inventaris();
		$data['pamong'] = $this->surat_model->list_pamong();
		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('inventaris/kontruksi/table', $data);
	}

	public function view($id)
	{
		$data['main'] = $this->inventaris_kontruksi_model->view($id);
		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('inventaris/kontruksi/view_inventaris', $data);
	}

	public function view_mutasi($id)
	{
		$data['main'] = $this->inventaris_kontruksi_model->view_mutasi($id);
		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('inventaris/kontruksi/view_mutasi', $data);
	}

	public function edit($id)
	{
		$data['main'] = $this->inventaris_kontruksi_model->view($id);
		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('inventaris/kontruksi/edit_inventaris', $data);
	}

	public function edit_mutasi($id)
	{
		$data['main'] = $this->inventaris_kontruksi_model->edit_mutasi($id);
		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('inventaris/kontruksi/edit_mutasi', $data);
	}

	public function form()
	{
		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('inventaris/kontruksi/form_tambah', $data);
	}

	public function form_mutasi($id)
	{
		$data['main'] = $this->inventaris_kontruksi_model->view($id);
		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('inventaris/kontruksi/form_mutasi', $data);
	}

	public function mutasi()
	{
		$data['main'] = $this->inventaris_kontruksi_model->list_mutasi_inventaris();
		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('inventaris/kontruksi/table_mutasi', $data);
	}

	public function cetak($tahun, $penandatangan)
	{
		$data['header'] = $this->header['desa'];
		$data['total'] = $this->inventaris_kontruksi_model->sum_print($tahun);
		$data['print'] = $this->inventaris_kontruksi_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_kontruksi_model->pamong($penandatangan);
		$this->load->view('inventaris/kontruksi/inventaris_print', $data);
	}

	public function download($tahun, $penandatangan)
	{
		$data['header'] = $this->header['desa'];
		$data['total'] = $this->inventaris_kontruksi_model->sum_print($tahun);
		$data['print'] = $this->inventaris_kontruksi_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_kontruksi_model->pamong($penandatangan);
		$this->load->view('inventaris/kontruksi/inventaris_excel', $data);
	}
}
