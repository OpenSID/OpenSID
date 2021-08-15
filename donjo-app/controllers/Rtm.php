<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Rumah Tangga
 *
 * donjo-app/controllers/Rtm.php,
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Rtm extends Admin_Controller {

	private $_set_page;
	private $_list_session;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['rtm_model', 'wilayah_model', 'program_bantuan_model']);
		$this->_set_page = ['50', '100', '200'];
		$this->_list_session = ['cari', 'dusun', 'rw', 'rt', 'order_by', 'id_bos', 'kelas', 'judul_statistik', 'sex', 'bdt']; // Session id_bos
		$this->modul_ini = 2;
		$this->sub_modul_ini = 23;
		$this->set_minsidebar(1);
	}

	public function clear()
	{
		$this->session->unset_userdata($this->_list_session);
		$this->session->per_page = $this->_set_page[0];
		$this->session->order_by = 1;
		redirect($this->controller);
	}

	public function index($p = 1)
	{
		foreach ($this->_list_session as $list)
		{
			if (in_array($list, ['dusun', 'rw', 'rt']))
				$$list = $this->session->$list;
			else
				$data[$list] = $this->session->$list ?: '';
		}

		if (isset($dusun))
		{
			$data['dusun'] = $dusun;
			$data['list_rw'] = $this->wilayah_model->list_rw($dusun);

			if (isset($rw))
			{
				$data['rw'] = $rw;
				$data['list_rt'] = $this->wilayah_model->list_rt($dusun, $rw);

				if (isset($rt))
					$data['rt'] = $rt;
				else $data['rt'] = '';
			}
			else $data['rw'] = '';
		}
		else
		{
			$data['dusun'] = $data['rw'] = $data['rt'] = '';
		}

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['func'] = 'index';
		$data['set_page'] = $this->_set_page;
		$data['paging'] = $this->rtm_model->paging($p);
		$data['main'] = $this->rtm_model->list_data($data['order_by'], $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->rtm_model->autocomplete();
		$data['list_dusun'] = $this->wilayah_model->list_dusun();
		$data['list_sex'] = $this->referensi_model->list_data('tweb_penduduk_sex');

		$this->render('sid/kependudukan/rtm', $data);
	}

	/*
	* $aksi = cetak/unduh
	*/
	public function daftar($aksi = '', $privasi_nik = 0)
	{
		$data['main'] = $this->rtm_model->list_data($this->session->order_by, 0);
		if ($privasi_nik == 1) $data['privasi_nik'] = true;
		$this->load->view("sid/kependudukan/rtm_$aksi", $data);
	}

	public function edit_nokk($id = 0)
	{
		$this->redirect_hak_akses('u');
		$data['kk'] = $this->rtm_model->get_rtm($id);
		$data['form_action'] = site_url("rtm/update_nokk/$id");
		$this->load->view('sid/kependudukan/ajax_edit_no_rtm', $data);
	}

	public function form_old($id = 0)
	{
		$this->redirect_hak_akses('u');
		$data['penduduk'] = $this->rtm_model->list_penduduk_lepas();
		$data['form_action'] = site_url("rtm/insert/$id");
		$this->load->view('sid/kependudukan/ajax_add_rtm', $data);
	}

	public function filter($filter = '', $order_by = '')
	{
		$value = $order_by ?: $this->input->post($filter);
		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect($this->controller);
	}

	public function dusun()
	{
		$this->session->unset_userdata(['rw', 'rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != '')
			$this->session->dusun = $dusun;
		else $this->session->unset_userdata('dusun');
		redirect($this->controller);
	}

	public function rw()
	{
		$this->session->unset_userdata('rt');
		$rw = $this->input->post('rw');
		if ($rw != '')
			$this->session->rw = $rw;
		else $this->session->unset_userdata('rw');
		redirect($this->controller);
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != '')
			$this->session->rt = $rt;
		else $this->session->unset_userdata('rt');
		redirect($this->controller);
	}

	public function insert()
	{
		$this->redirect_hak_akses('u');
		$this->rtm_model->insert();
		$this->session->order_by = 6;

		redirect($this->controller);
	}

	public function insert_by_kk()
	{
		$this->redirect_hak_akses('u');
		$this->rtm_model->insert_by_kk();
		$this->session->order_by = 6;

		redirect($this->controller);
	}

	public function insert_a()
	{
		$this->redirect_hak_akses('u');
		$this->rtm_model->insert_a();
		$this->session->order_by = 6;

		redirect($this->controller);
	}

	public function insert_new()
	{
		$this->redirect_hak_akses('u');
		$this->rtm_model->insert_new();
		$this->session->order_by = 6;

		redirect($this->controller);
	}

	public function update($id = 0)
	{
		$this->redirect_hak_akses('u');
		$this->rtm_model->update($id);
		redirect($this->controller);
	}

	public function update_nokk($id = 0)
	{
		$this->redirect_hak_akses('u');
		$this->rtm_model->update_nokk($id);
		redirect($this->controller);
	}

	public function delete($id = 0)
	{
		$this->redirect_hak_akses('h', 'rtm');
		$this->rtm_model->delete($id);
		redirect($this->controller);
	}

	public function delete_all()
	{
		$this->redirect_hak_akses('h', 'rtm');
		$this->rtm_model->delete_all();
		redirect($this->controller);
	}

	public function anggota($id = 0)
	{
		$data['kk'] = $id;

		$data['main'] = $this->rtm_model->list_anggota($id);
		$data['kepala_kk']= $this->rtm_model->get_kepala_rtm($id);
		$data['program'] = $this->program_bantuan_model->get_peserta_program(3, $data['kepala_kk']['no_kk']);
		
		$this->render('sid/kependudukan/rtm_anggota', $data);
	}

	public function ajax_add_anggota($id = 0)
	{
		$this->redirect_hak_akses('u');
		$data['main'] = $this->rtm_model->list_anggota($id);
		$kk = $this->rtm_model->get_kepala_rtm($id);
		if ($kk)
			$data['kepala_kk'] = $kk;
		else
			$data['kepala_kk'] = NULL;

		$data['penduduk'] = $this->rtm_model->list_penduduk_lepas();
		$data['form_action'] = site_url("rtm/add_anggota/$id");

		$this->load->view("sid/kependudukan/ajax_add_anggota_rtm_form", $data);
	}

	public function edit_anggota($id_rtm = 0, $id = 0)
	{
		$this->redirect_hak_akses('u');
		$data['hubungan'] = $this->rtm_model->list_hubungan();
		$data['main'] = $this->rtm_model->get_anggota($id);
		$data['form_action'] = site_url("rtm/update_anggota/$id_rtm/$id");
		$this->load->view("sid/kependudukan/ajax_edit_anggota_rtm", $data);
	}

	public function kartu_rtm($id = 0)
	{
		$data['id_kk'] = $id;

		$data['hubungan'] = $this->rtm_model->list_hubungan();
		$data['main'] = $this->rtm_model->list_anggota($id);
		$kk = $this->rtm_model->get_kepala_rtm($id);
		$data['desa'] = $this->header['desa'];

		if ($kk)
			$data['kepala_kk'] = $kk;
		else
			$data['kepala_kk'] = NULL;

		$data['penduduk'] = $this->rtm_model->list_penduduk_lepas();
		$data['form_action'] = site_url("rtm/print");

		$this->render("sid/kependudukan/kartu_rtm", $data);
	}

	public function cetak_kk($id = 0)
	{
		$data['id_kk'] = $id;

		$data['main'] = $this->rtm_model->list_anggota($id);
		$data['kepala_kk'] = $this->rtm_model->get_kepala_rtm($id);
		$data['desa'] = $this->header['desa'];
		$this->load->view("sid/kependudukan/cetak_rtm", $data);
	}

	public function add_anggota($id = 0)
	{
		$this->redirect_hak_akses('u');
		$this->rtm_model->add_anggota($id);
		redirect("rtm/anggota/$id");
	}

	public function update_anggota($id_rtm = 0, $id = 0)
	{
		$this->redirect_hak_akses('u');
		$this->rtm_model->update_anggota($id, $id_rtm);
		redirect("rtm/anggota/$id_rtm");
	}

	public function delete_anggota($kk = 0, $id = 0)
	{
		$this->redirect_hak_akses('h');
		$this->rtm_model->rem_anggota($kk, $id);
		redirect("rtm/anggota/$kk");
	}

	public function delete_all_anggota($kk = 0)
	{
		$this->redirect_hak_akses('h');
		$this->rtm_model->rem_all_anggota($kk);
		redirect("rtm/anggota/$kk");
	}

	public function ajax_cetak($aksi = '')
	{
		$data['aksi'] = $aksi;
		$data['form_action'] = site_url("rtm/daftar/$aksi");
		$data['form_action_privasi'] = site_url("rtm/daftar/$aksi/1");
		$this->load->view("sid/kependudukan/ajax_cetak_bersama", $data);
	}

	public function statistik($tipe = '0', $nomor = 0, $sex = NULL)
	{
		if ($sex == NULL)
		{
			if ($nomor != 0) $this->session->sex = $nomor;
			else $this->session->unset_userdata('sex');
			$this->session->unset_userdata('judul_statistik');
			redirect($this->controller);
		}

		$this->session->sex = ($sex == 0) ? NULL : $sex;

		switch ($tipe)
		{		
			case 'bdt':
				$session = 'bdt';
				$kategori = 'KLASIFIKASI BDT ';
				break;
		}

		$this->session->$session = ($nomor != TOTAL) ? $nomor : NULL;

		$judul = $this->rtm_model->get_judul_statistik($tipe, $nomor, $sex);
		
		$this->session->unset_userdata('judul_statistik');
		if ($judul['nama']) $this->session->judul_statistik = $kategori . $judul['nama'];

		redirect($this->controller);
	}
}
