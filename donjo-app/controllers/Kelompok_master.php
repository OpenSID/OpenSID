<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Kelompok
 *
 * donjo-app/controllers/Kelompok_master.php
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

class Kelompok_master extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('kelompok_master_model');

		$this->modul_ini = 2;
		$this->sub_modul_ini = 24;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['state']);
		redirect('kelompok_master');
	}

	public function index($p=1, $o=0)
	{
    unset($_SESSION['kelompok_master']);
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

		$data['paging']  = $this->kelompok_master_model->paging($p,$o);
		$data['main']    = $this->kelompok_master_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->kelompok_master_model->autocomplete();
		$this->set_minsidebar(1);
		$this->render('kelompok_master/table', $data);
	}

	public function form($p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['kelompok_master'] = $this->kelompok_master_model->get_kelompok_master($id);
			$data['form_action'] = site_url("kelompok_master/update/$p/$o/$id");
		}
		else
		{
			$data['kelompok_master'] = null;
			$data['form_action'] = site_url("kelompok_master/insert");
		}

		$this->set_minsidebar(1);
		$this->render('kelompok_master/form', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('kelompok_master');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('kelompok_master');
	}

	public function state(){
		$filter = $this->input->post('state');
		if ($filter != 0)
			$_SESSION['state'] = $filter;
		else unset($_SESSION['state']);
		redirect('kelompok_master');
	}

	public function insert()
	{
		$this->kelompok_master_model->insert();
		redirect('kelompok_master');
	}

	public function update($p=1, $o=0, $id='')
	{
		$this->kelompok_master_model->update($id);
		redirect("kelompok_master/index/$p/$o");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "kelompok_master/index/$p/$o");
		$this->kelompok_master_model->delete($id);
		redirect("kelompok_master/index/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "kelompok_master/index/$p/$o");
		$this->kelompok_master_model->delete_all();
		redirect("kelompok_master/index/$p/$o");
	}
}
