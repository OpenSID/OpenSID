<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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

class Kelompok extends Admin_Controller {

	private $_set_page;
	private $_list_session;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['kelompok_model','referensi_model', 'pamong_model']);
		$this->modul_ini = 2;
		$this->sub_modul_ini = 24;
		$this->_set_page = ['20', '50', '100'];
		$this->_list_session = ['cari', 'filter'];
	}

	public function clear()
	{
		$this->session->unset_userdata($this->_list_session);
		$this->session->per_page = $this->_set_page[0];
		redirect('kelompok');
	}

	public function index($p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		foreach ($this->_list_session as $list)
		{
			$data[$list] = $this->session->$list ?: '';
		}

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['func'] = 'index';
		$data['set_page'] = $this->_set_page;
		$data['filter'] = $this->session->filter;
		$data['paging'] = $this->kelompok_model->paging($p, $o);
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

	public function form($p = 1, $o = 0, $id = '')
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
			$data['kelompok'] = NULL;
			$data['form_action'] = site_url("kelompok/insert");
		}

		$data['list_master'] = $this->kelompok_model->list_master();
		$data['list_penduduk'] = $this->kelompok_model->list_penduduk();

		$this->set_minsidebar(1);
		$this->render('kelompok/form', $data);
	}

	public function aksi($aksi = '', $id = 0)
	{
		$this->session->set_userdata('aksi', $aksi);

		redirect("kelompok/form_anggota/$id");
	}

	public function form_anggota($id = 0, $id_a = 0)
	{
		if ($id_a == 0)
		{
			$data['kelompok'] = $id;
			$data['pend'] = NULL;
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

		$data['list_jabatan'] = $this->referensi_model->list_ref(JABATAN_KELOMPOK);

		$this->set_minsidebar(1);
		$data['list_jabatan'] = $this->referensi_model->list_ref(JABATAN_KELOMPOK);
		$this->render('kelompok/anggota/form', $data);
	}

	/*
	* $aksi = cetak/unduh
	*/
	public function dialog_anggota($aksi = 'cetak', $id = 0)
	{
		$data['aksi'] = ucwords($aksi);
		$data['pamong'] = $this->pamong_model->list_data();
		$data['form_action'] = site_url("kelompok/daftar_anggota/$aksi/$id");

		$this->load->view('global/ttd_pamong', $data);
	}

	/*
	* $aksi = cetak/unduh
	*/
	public function dialog($aksi = 'cetak')
	{
		$data['aksi'] = ucwords($aksi);
		$data['pamong'] = $this->pamong_model->list_data();
		$data['form_action'] = site_url("kelompok/daftar/$aksi");

		$this->load->view('global/ttd_pamong', $data);
	}

	public function daftar($aksi = 'cetak')
	{
		$post = $this->input->post();
		$data['aksi'] = $aksi;
		$data['config'] = $this->header['desa'];
		$data['pamong_ttd'] = $this->pamong_model->get_data($post['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($post['pamong_ketahui']);

		$data['main'] = $this->kelompok_model->list_data();

		$data['file'] = "Data Kelompok"; // nama file
		$data['isi'] = "kelompok/cetak";
		$data['letak_ttd'] = ['1', '1', '1'];

		$this->load->view('global/format_cetak', $data);
	}

	public function daftar_anggota($aksi = 'cetak', $id = 0)
	{
		$post = $this->input->post();
		$data['aksi'] = $aksi;
		$data['config'] = $this->header['desa'];
		$data['pamong_ttd'] = $this->pamong_model->get_data($post['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($post['pamong_ketahui']);

		$data['main'] = $this->kelompok_model->list_anggota($id);
		$data['kelompok'] = $this->kelompok_model->get_kelompok($id);

		$data['file'] = "Laporan Data Kelompok " . $data['kelompok']['nama']; // nama file
		$data['isi'] = "kelompok/anggota/cetak";
		$data['letak_ttd'] = ['2', '3', '2'];

		$this->load->view('global/format_cetak', $data);
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != "")
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('kelompok');
	}

	public function insert()
	{
		$this->kelompok_model->insert();
		redirect('kelompok');
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$this->kelompok_model->update($id);
		redirect("kelompok/index/$p/$o");
	}

	public function delete($id = '')
	{
		$this->redirect_hak_akses('h');
		$this->kelompok_model->delete($id);
		redirect("kelompok");
	}

	public function delete_all()
	{
		$this->redirect_hak_akses('h');
		$this->kelompok_model->delete_all();
		redirect("kelompok");
	}

	public function insert_a($id = 0)
	{
		$this->kelompok_model->insert_a($id);
		$redirect = ($this->session->aksi != 1) ? $_SERVER['HTTP_REFERER'] : "kelompok/anggota/$id";

		$this->session->unset_userdata('aksi');

		redirect($redirect);
	}

	public function update_a($id = '', $id_a = 0)
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
			$this->session->filter = $filter;
		else $this->session->unset_userdata(['filter']);
		redirect('kelompok');
	}
}
