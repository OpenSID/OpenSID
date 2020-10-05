<?php

/**
 * File ini:
 *
 * Controller untuk modul Kelompok
 *
 * donjo-app/controllers/Kelompok.php,
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

defined('BASEPATH') OR exit('No direct script access allowed');

class Kelompok extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('kelompok_model');
		$this->modul_ini = 2;
		$this->sub_modul_ini = 24;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['state']);
		redirect('kelompok');
	}

	public function index($p=1, $o=0)
	{
		unset($_SESSION['kelompok']);
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

		$data['paging'] = $this->kelompok_model->paging($p,$o);
		$data['main'] = $this->kelompok_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->kelompok_model->autocomplete();
		$data['list_master'] = $this->kelompok_model->list_master();
		$this->set_minsidebar(1);

		$this->render('kelompok/table', $data);
	}

	public function anggota($id=0)
	{
		$data['kelompok'] = $this->kelompok_model->get_kelompok($id);
		$data['main'] = $this->kelompok_model->list_anggota($id);
		$this->set_minsidebar(1);

		$this->render('kelompok/anggota/table', $data);
	}

	public function form($p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['kelompok'] = $this->kelompok_model->get_kelompok($id);
			$data['form_action'] = site_url("kelompok/update/$p/$o/$id");
		}
		else
		{
			$data['kelompok'] = null;
			$data['form_action'] = site_url("kelompok/insert");
		}

		$data['list_master'] = $this->kelompok_model->list_master();
		$data['list_penduduk'] = $this->kelompok_model->list_penduduk();
		$this->set_minsidebar(1);

		$this->render('kelompok/form', $data);
	}

	public function form_anggota($id=0, $id_a=0)
	{
		if ($id_a == 0)
		{
			$data['kelompok'] = $id;
			$data['pend'] = null;
			$data['list_penduduk'] = $this->kelompok_model->list_penduduk($ex_kelompok=$id);
			$data['form_action'] = site_url("kelompok/insert_a/$id");
		}
		else
		{
			$data['kelompok'] = $id;
			$data['pend'] = $this->kelompok_model->get_anggota($id, $id_a);
			$data['list_penduduk'] = $this->kelompok_model->list_penduduk();
			$data['form_action'] = site_url("kelompok/update_a/$id/$id_a");
		}
		$this->set_minsidebar(1);

		$this->render('kelompok/anggota/form', $data);
	}

	public function cetak()
	{
		$data['main'] = $this->kelompok_model->list_data();

		$this->load->view('kelompok/cetak', $data);
	}

	public function excel()
	{
		$data['main'] = $this->kelompok_model->list_data();

		$this->load->view('kelompok/excel', $data);
	}

	public function daftar($aksi = 'cetak', $id = 0)
	{
		$data['aksi'] = $aksi;
		$data['config'] = $this->config_model->get_data();
		$data['main'] = $this->kelompok_model->list_anggota($id);
		$data['kelompok'] = $this->kelompok_model->get_kelompok($id);

		$this->load->view('kelompok/anggota/cetak', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('kelompok');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('kelompok');
	}

	public function state()
	{
		$filter = $this->input->post('state');
		if ($filter != 0)
			$_SESSION['state'] = $filter;
		else unset($_SESSION['state']);
		redirect('kelompok');
	}

	public function insert()
	{
		$this->kelompok_model->insert();
		redirect('kelompok');
	}

	public function update($p=1, $o=0, $id='')
	{
		$this->kelompok_model->update($id);
		redirect("kelompok/index/$p/$o");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "kelompok/index/$p/$o");
		$this->kelompok_model->delete($id);
		redirect("kelompok/index/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h');
		$this->kelompok_model->delete_all();
		redirect("kelompok/index/$p/$o");
	}

	public function insert_a($id=0)
	{
		$this->kelompok_model->insert_a($id);
		redirect("kelompok/anggota/$id");
	}

	public function update_a($id='', $id_a=0)
	{
		$this->kelompok_model->update_a($id, $id_a);
		redirect("kelompok/anggota/$id");
	}

	public function delete_anggota($id = 0, $a=0)
	{
		$this->redirect_hak_akses('h');
		$this->kelompok_model->delete_anggota($a);
		redirect("kelompok/anggota/$id");
	}

	public function delete_anggota_all($id = 0)
	{
		$this->redirect_hak_akses('h');
		$this->kelompok_model->delete_anggota_all();
		redirect("kelompok/anggota/$id");
	}

	public function to_master($id=0)
	{
		$filter = $id;
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('kelompok');
	}
}
