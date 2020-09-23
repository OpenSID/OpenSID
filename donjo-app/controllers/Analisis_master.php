<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Analisis
 *
 * donjo-app/controllers/Analisis_master.php
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

class Analisis_master extends Admin_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('analisis_master_model');
		$this->load->model('analisis_import_model');

		unset($_SESSION['submenu']);
		unset($_SESSION['asubmenu']);
		$this->modul_ini = 5;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['state']);
		redirect('analisis_master');
	}

	public function index($p=1, $o=0)
	{
    unset($_SESSION['analisis_master']);
    unset($_SESSION['analisis_nama']);
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_SESSION['state']))
			$data['state'] = $_SESSION['state'];
		else $data['state'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->analisis_master_model->paging($p,$o);
		$data['main']    = $this->analisis_master_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_master_model->autocomplete();
		$data['list_subjek'] = $this->analisis_master_model->list_subjek();
		$this->set_minsidebar(1);
		$this->render('analisis_master/table', $data);
	}

	public function form($p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['analisis_master'] = $this->analisis_master_model->get_analisis_master($id);
			$data['form_action'] = site_url("analisis_master/update/$p/$o/$id");
		}
		else
		{
			$data['analisis_master'] = null;
			$data['form_action'] = site_url("analisis_master/insert");
		}

		$data['list_format_impor'] = array('1' => 'BDT 2015');
		$data['list_kelompok'] = $this->analisis_master_model->list_kelompok();
		$data['list_analisis'] = $this->analisis_master_model->list_analisis_child();
		$this->set_minsidebar(1);
		$this->render('analisis_master/form', $data);
	}

	public function panduan()
	{
		$this->set_minsidebar(1);
		$this->render('analisis_master/panduan');
	}

	public function import_analisis()
	{
		$this->set_minsidebar(1);
		$data['form_action'] = site_url("analisis_master/import");
		$this->load->view('analisis_master/import', $data);
	}

	public function menu($id='')
	{
		$_SESSION['analisis_master'] = $id;
		$data['analisis_master'] = $this->analisis_master_model->get_analisis_master($id);
		$_SESSION['analisis_nama'] = $data['analisis_master']['nama'];
		$da = $data['analisis_master'];
		$subjek = $da['subjek_tipe'];
		$_SESSION['subjek_tipe'] = $subjek;

		switch ($subjek)
		{
			case 1:
				$data['menu_respon'] = "analisis_respon_penduduk";
				$data['menu_laporan'] = "analisis_laporan_penduduk";
				break;
			case 2:
				$data['menu_respon'] = "analisis_respon_keluarga";
				$data['menu_laporan'] = "analisis_laporan_keluarga";
				break;
			case 3:
				$data['menu_respon'] = "analisis_respon_rtm";
				$data['menu_laporan'] = "analisis_laporan_rtm";
				break;
			case 4:
				$data['menu_respon'] = "analisis_respon_kelompok";
				$data['menu_laporan'] = "analisis_laporan_kelompok";
				break;
			default:
				redirect('analisis_master');
		}
		$data['menu_respon'] = "analisis_respon";
		$data['menu_laporan'] = "analisis_laporan";

		/* TODO: Periksa apakah perlu lakukan pre_update */
		// $this->load->model('analisis_respon_model');
		// $this->analisis_respon_model->pre_update();
		$this->set_minsidebar(1);
		$this->render('analisis_master/menu', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis_master');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('analisis_master');
	}

	public function state()
	{
		$filter = $this->input->post('state');
		if ($filter != 0)
			$_SESSION['state']=$filter;
		else unset($_SESSION['state']);
		redirect('analisis_master');
	}

	public function insert()
	{
		$this->analisis_master_model->insert();
		redirect('analisis_master');
	}

	public function import()
	{
		$this->analisis_import_model->import_excel();
		redirect('analisis_master');
	}

	public function update($p=1, $o=0, $id='')
	{
		$this->analisis_master_model->update($id);
		redirect("analisis_master/index/$p/$o");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "analisis_master/index/$p/$o");
		$this->analisis_master_model->delete($id);
		redirect("analisis_master/index/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "analisis_master/index/$p/$o");
		$this->analisis_master_model->delete_all();
		redirect("analisis_master/index/$p/$o");
	}
}
