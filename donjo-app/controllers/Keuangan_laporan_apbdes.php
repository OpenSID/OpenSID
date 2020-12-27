<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Laporan APBDes
 *
 * donjo-app/controllers/laporan_apbdes.php
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

 require_once 'vendor/spout/src/Spout/Autoloader/autoload.php';

 use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
 use Box\Spout\Common\Entity\Row;

class Keuangan_laporan_apbdes extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('keuangan_laporan_apbdes_model');
		$this->load->library('zip');
		$this->modul_ini = 201;
		$this->sub_modul_ini = 315;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('keuangan_laporan_apbdes');
	}

	public function index($p = 1, $o = 0)
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

		$data['paging'] = $this->keuangan_laporan_apbdes_model->paging($p, $o);
		$data['main'] = $this->keuangan_laporan_apbdes_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->keuangan_laporan_apbdes_model->autocomplete();

		$this->render('keuangan/laporan_apbdes_table', $data);
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['keuangan_laporan_apbdes'] = $this->keuangan_laporan_apbdes_model->get_laporan($id);
			$data['form_action'] = site_url("keuangan_laporan_apbdes/update/$p/$o/$id");
		}
		else
		{
			$data['keuangan_laporan_apbdes'] = NULL;
			$data['form_action'] = site_url("keuangan_laporan_apbdes/insert");
		}

		$this->render('keuangan/laporan_apbdes_form', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('keuangan_laporan_apbdes');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('keuangan_laporan_apbdes');
	}

	public function insert()
	{
		$this->keuangan_laporan_apbdes_model->insert();
		redirect('keuangan_laporan_apbdes');
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$this->keuangan_laporan_apbdes_model->update($id);
		redirect("keuangan_laporan_apbdes/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "keuangan_laporan_apbdes/index/$p/$o");
		$this->keuangan_laporan_apbdes_model->delete($id);
		redirect("keuangan_laporan_apbdes/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "keuangan_laporan_apbdes/index/$p/$o");
		$this->keuangan_laporan_apbdes_model->delete_all();
		redirect("keuangan_laporan_apbdes/index/$p/$o");
	}

  public function show($id = '')
	{
		$data['dokumen'] = $this->keuangan_laporan_apbdes_model->get_laporan_show($id);
		$this->render('keuangan/show_laporan_apbdes', $data);
	}

}
