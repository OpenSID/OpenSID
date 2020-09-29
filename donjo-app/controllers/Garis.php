<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File ini:
 *
 * Controller di Modul Pemetaan
 *
 * /donjo-app/controllers/Garis.php
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

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */

class Garis extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('wilayah_model');
		$this->load->model('config_model');
		$this->load->model('plan_lokasi_model');
		$this->load->model('plan_area_model');
		$this->load->model('plan_garis_model');
		$this->modul_ini = 9;
		$this->sub_modul_ini = 8;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['line']);
		unset($_SESSION['subline']);
		redirect('garis');
	}

	public function index($p=1, $o=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_SESSION['line']))
			$data['line'] = $_SESSION['line'];
		else $data['line'] = '';

		if (isset($_SESSION['subline']))
			$data['subline'] = $_SESSION['subline'];
		else $data['subline'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging'] = $this->plan_garis_model->paging($p,$o);
		$data['main'] = $this->plan_garis_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->plan_garis_model->autocomplete();
		$data['list_line'] = $this->plan_garis_model->list_line();
		$data['list_subline'] = $this->plan_garis_model->list_subline();

		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('garis/table', $data);
	}

	public function form($p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['desa'] = $this->config_model->get_data();
		$data['list_subline'] = $this->plan_garis_model->list_subline();
		$data['dusun'] = $this->plan_garis_model->list_dusun();
		if ($id)
		{
			$data['garis'] = $this->plan_garis_model->get_garis($id);
			$data['form_action'] = site_url("garis/update/$id/$p/$o");
		}
		else
		{
			$data['garis'] = null;
			$data['form_action'] = site_url("garis/insert");
		}
		
		$data['tip'] = 1;
		$this->set_minsidebar(1);
		$this->render('garis/form', $data);
	}

	public function ajax_garis_maps($p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		if ($id)
			$data['garis'] = $this->plan_garis_model->get_garis($id);
		else
			$data['garis'] = null;

		$data['desa'] = $this->config_model->get_data();
		$sebutan_desa = ucwords($this->setting->sebutan_desa);
		$data['wil_atas'] = $this->config_model->get_data();
		$data['dusun_gis'] = $this->wilayah_model->list_dusun();
		$data['rw_gis'] = $this->wilayah_model->list_rw_gis();
		$data['rt_gis'] = $this->wilayah_model->list_rt_gis();
		$data['all_lokasi'] = $this->plan_lokasi_model->list_data();
		$data['all_garis'] = $this->plan_garis_model->list_data();
		$data['all_area'] = $this->plan_area_model->list_data();
		$data['form_action'] = site_url("garis/update_maps/$p/$o/$id");

		$this->render("garis/maps", $data);
	}

	public function update_maps($p=1, $o=0, $id='')
	{
		$this->plan_garis_model->update_position($id);
		redirect("garis/index/$p/$o");
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('garis');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('garis');
	}

	public function line()
	{
		$line = $this->input->post('line');
		if ($line != 0)
			$_SESSION['line'] = $line;
		else unset($_SESSION['line']);
		redirect('garis');
	}

	public function subline()
	{
		unset($_SESSION['line']);
		$subline = $this->input->post('subline');
		if ($subline != 0)
			$_SESSION['subline'] = $subline;
		else unset($_SESSION['subline']);
		redirect('garis');
	}

	public function insert($tip=1)
	{
		$this->plan_garis_model->insert($tip);
		redirect("garis/index/$tip");
	}

	public function update($id='', $p=1, $o=0)
	{
		$this->plan_garis_model->update($id);
		redirect("garis/index/$p/$o");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "garis/index/$p/$o");
		$this->plan_garis_model->delete($id);
		redirect("garis/index/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "garis/index/$p/$o");
		$this->plan_garis_model->delete_all();
		redirect("garis/index/$p/$o");
	}

	public function garis_lock($id='')
	{
		$this->plan_garis_model->garis_lock($id, 1);
		redirect("garis/index/$p/$o");
	}

	public function garis_unlock($id='')
	{
		$this->plan_garis_model->garis_lock($id, 2);
		redirect("garis/index/$p/$o");
	}
}
