<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Menu
 *
 * donjo-app/controllers/Menu.php
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

class Menu extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('web_menu_model');
		$this->load->model('web_artikel_model');
		$this->load->model('web_kategori_model');
		$this->load->model('referensi_model');
		$this->load->model('kelompok_model');
		$this->load->model('laporan_penduduk_model');
		$this->load->model('keuangan_model');
		$this->modul_ini = 13;
		$this->sub_modul_ini = 49;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('menu');
	}

	public function index($tip = 1, $p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['tip'] = $tip;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging'] = $this->web_menu_model->paging($tip, $p, $o);
		$data['main'] = $this->web_menu_model->list_data($tip, $o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_menu_model->autocomplete($data['cari']);

		$this->render('menu/table', $data);
	}

	public function form($tip = 1, $id = '')
	{
		$data['link_tipe'] = $this->referensi_model->list_ref(LINK_TIPE);
		$data['artikel_statis'] = $this->web_artikel_model->list_artikel_statis();
		$data['kategori_artikel'] = $this->web_kategori_model->list_kategori();
		$data['statistik_penduduk'] = $this->referensi_model->list_ref(STAT_PENDUDUK);
		$data['statistik_keluarga'] = $this->referensi_model->list_ref(STAT_KELUARGA);
		$data['statistik_kategori_bantuan'] = $this->referensi_model->list_ref(STAT_BANTUAN);
		$data['statistik_program_bantuan'] = $this->program_bantuan_model->list_program(0);
		$data['kelompok'] = $this->kelompok_model->list_data();
		$data['statis_lainnya'] = $this->referensi_model->list_ref(STAT_LAINNYA);
		$data['artikel_keuangan'] = $this->keuangan_model->artikel_statis_keuangan();

		if ($id)
		{
			$data['submenu'] = $this->web_menu_model->get_menu($id);
			$data['form_action'] = site_url("menu/update/$tip/$id");
		}
		else
		{
			$data['submenu'] = NULL;
			$data['form_action'] = site_url("menu/insert/$tip");
		}

		$data['tip'] = $tip;

		$this->render('menu/form', $data);
	}

	public function sub_menu($tip = 1, $menu = 1)
	{
		$data['submenu'] = $this->web_menu_model->list_sub_menu($menu);
		$data['tip'] = $tip;
		$data['menu'] = $menu;

		$this->render('menu/sub_menu_table', $data);
	}

	public function ajax_add_sub_menu($tip = 1, $menu = '', $id = '')
	{
		$data['menu'] = $menu;
		$data['tip'] = $tip;

		$data['link_tipe'] = $this->referensi_model->list_ref(LINK_TIPE);
		$data['artikel_statis'] = $this->web_artikel_model->list_artikel_statis();
		$data['kategori_artikel'] = $this->web_kategori_model->list_kategori();
		$data['statistik_penduduk'] = $this->referensi_model->list_ref(STAT_PENDUDUK);
		$data['statistik_keluarga'] = $this->referensi_model->list_ref(STAT_KELUARGA);
		$data['statistik_kategori_bantuan'] = $this->referensi_model->list_ref(STAT_BANTUAN);
		$data['statistik_program_bantuan'] = $this->program_bantuan_model->list_program(0);
		$data['kelompok'] = $this->kelompok_model->list_data();
		$data['statis_lainnya'] = $this->referensi_model->list_ref(STAT_LAINNYA);
		$data['artikel_keuangan'] = $this->keuangan_model->artikel_statis_keuangan();

		if ($id)
		{
			$data['submenu'] = $this->web_menu_model->get_menu($id);
			$data['form_action'] = site_url("menu/update_sub_menu/$tip/$menu/$id");
		}
		else
		{
			$data['submenu'] = NULL;
			$data['form_action'] = site_url("menu/insert_sub_menu/$tip/$menu");
		}

		$this->load->view('menu/ajax_add_sub_menu_form', $data);
	}

	public function search($tip = 1)
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect("menu/index/$tip");
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('menu');
	}

	public function insert($tip = 1)
	{
		$this->web_menu_model->insert($tip);
		redirect("menu/index/$tip");
	}

	public function update($tip = 1, $id = '')
	{
		$this->web_menu_model->update($id);
		redirect("menu/index/$tip");
	}

	public function delete($tip = 1, $id = '')
	{
		$this->redirect_hak_akses('h', "menu/index/$tip");
		$this->web_menu_model->delete($id);
		redirect("menu/index/$tip");
	}

	public function delete_all($tip = 1, $p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "menu/index/$tip/$p/$o");
		$this->web_menu_model->delete_all();
		redirect("menu/index/$tip/$p/$o");
	}

	public function menu_lock($tip = 1, $id = '')
	{
		$this->web_menu_model->menu_lock($id, 1);
		redirect("menu/index/$tip/$p/$o");
	}

	public function menu_unlock($tip = 1, $id = '')
	{
		$this->web_menu_model->menu_lock($id, 2);
		redirect("menu/index/$tip/$p/$o");
	}

	public function insert_sub_menu($tip = 1, $menu = '')
	{
		$this->web_menu_model->insert_sub_menu($menu);
		redirect("menu/sub_menu/$tip/$menu");
	}

	public function update_sub_menu($tip = 1, $menu = '', $id = '')
	{
		$this->web_menu_model->update_sub_menu($id);
		redirect("menu/sub_menu/$tip/$menu");
	}

	public function delete_sub_menu($tip = '', $menu = '', $id = 0)
	{
		$this->redirect_hak_akses('h', "menu/sub_menu/$tip/$menu");
		$this->web_menu_model->delete($id);
		redirect("menu/sub_menu/$tip/$menu");
	}

	public function delete_all_sub_menu($tip = 1, $menu = '')
	{
		$this->redirect_hak_akses('h', "menu/sub_menu/$tip/$menu");
		$this->web_menu_model->delete_all();
		redirect("menu/sub_menu/$tip/$menu");
	}

	public function menu_lock_sub_menu($tip = 1, $menu = '', $id = '')
	{
		$this->web_menu_model->menu_lock($id, 1);
		redirect("menu/sub_menu/$tip/$menu");
	}

	public function menu_unlock_sub_menu($tip = 1, $menu = '', $id = '')
	{
		$this->web_menu_model->menu_lock($id, 2);
		redirect("menu/sub_menu/$tip/$menu");
	}

	public function urut($tip = 1, $id = 0, $arah = 0, $menu = '')
	{
		$this->web_menu_model->urut($id, $arah, $tip, $menu);
		if ($menu != '')
			redirect("menu/sub_menu/$tip/$menu");
		else
			redirect("menu/index/$tip");
	}
}
