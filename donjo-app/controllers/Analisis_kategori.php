<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Analisis
 *
 * donjo-app/controllers/Analisis_kategori.php
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

class Analisis_kategori extends Admin_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('analisis_kategori_model');

		$_SESSION['submenu'] = "Data Kategori";
		$_SESSION['asubmenu'] = "analisis_kategori";
		$this->modul_ini = 5;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		redirect('analisis_kategori');
	}

	public function leave()
	{
		$id = $_SESSION['analisis_master'];
		unset($_SESSION['analisis_master']);
		redirect("analisis_master/menu/$id");
	}

	public function index($p=1, $o=0)
	{
		unset($_SESSION['cari2']);
		$data['p'] = $p;
		$data['o'] = $o;

		if( isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging'] = $this->analisis_kategori_model->paging($p,$o);
		$data['main'] = $this->analisis_kategori_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_kategori_model->autocomplete();
		$data['analisis_master'] = $this->analisis_kategori_model->get_analisis_master();
		$this->set_minsidebar(1);
		$this->render('analisis_kategori/table', $data);
	}

	public function form($p=1, $o=0, $id=''){
		$data['p'] = $p;
		$data['o'] = $o;

		if($id)
		{
			$data['analisis_kategori'] = $this->analisis_kategori_model->get_analisis_kategori($id);
			$data['form_action'] = site_url("analisis_kategori/update/$p/$o/$id");
		}
		else
		{
			$data['analisis_kategori'] = null;
			$data['form_action'] = site_url("analisis_kategori/insert");
		}

		$this->load->view('analisis_kategori/ajax_form', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('analisis_kategori');
	}

	public function insert()
	{
		$this->analisis_kategori_model->insert();
		redirect('analisis_kategori');
	}

	public function update($p=1, $o=0, $id='')
	{
		$this->analisis_kategori_model->update($id);
		redirect("analisis_kategori/index/$p/$o");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "analisis_kategori/index/$p/$o");
		$this->analisis_kategori_model->delete($id);
		redirect("analisis_kategori/index/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "analisis_kategori/index/$p/$o");
		$this->analisis_kategori_model->delete_all();
		redirect("analisis_kategori/index/$p/$o");
	}
}
