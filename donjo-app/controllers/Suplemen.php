<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Kependudukan > Data Suplemen
 *
 * donjo-app/controllers/suplemen.php,
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

class Suplemen extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['suplemen_model', 'pamong_model']);
		$this->modul_ini = 2;
		$this->sub_modul_ini = 25;
	}

	public function index()
	{
		$this->session->per_page = 50;

		$sasaran = $this->input->post('sasaran');

		$data['suplemen'] = $this->suplemen_model->list_data($sasaran);
		$data['list_sasaran'] = unserialize(SASARAN);
		$data['set_sasaran'] = $sasaran;

		$this->render('suplemen/suplemen', $data);
	}

	public function form($id = '')
	{
		if ($id)
		{
			$data['suplemen'] = $this->suplemen_model->get_suplemen($id);
			$data['form_action'] = site_url("suplemen/ubah/$id");
		}
		else
		{
			$data['suplemen'] = NULL;
			$data['form_action'] = site_url("suplemen/tambah");
		}

		$data['list_sasaran'] = unserialize(SASARAN);
		$this->set_minsidebar(1);

		$this->render('suplemen/form', $data);
	}

	public function tambah()
	{
		$this->suplemen_model->create();
		redirect('suplemen');
	}

	public function ubah($id)
	{
		$this->suplemen_model->update($id);
		redirect('suplemen');
	}

	public function hapus($id)
	{
		$this->redirect_hak_akses('h');
		$this->suplemen_model->hapus($id);
		redirect('suplemen');
	}

	public function panduan()
	{
		$this->render('suplemen/panduan');
	}

	public function filter($filter)
	{
		## untuk filter pada data rincian suplemen
		$value = $this->input->post($filter);
		$id_rincian = $this->session->id_rincian;
		if ($value != '')
			$this->session->$filter = $value;
		else
			$this->session->unset_userdata($filter);
		redirect("suplemen/rincian/$id_rincian");
	}

	public function clear($id)
	{
		## untuk filter pada data rincian suplemen
		if ($id)
		{
			$this->session->id_rincian = $id;
			$this->session->unset_userdata('cari');
			redirect("suplemen/rincian/$id");
		}
	}

	public function rincian($id = '', $p = 1)
	{
		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data = $this->suplemen_model->get_rincian($p, $id);
		$data['sasaran'] = unserialize(SASARAN);
		$data['func'] = "rincian/$id";
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = ['20', '50', '100'];
		$data['cari'] = $this->session->cari;
		$this->set_minsidebar(1);

		$this->render('suplemen/suplemen_anggota', $data);
	}

	public function form_terdata($id)
	{
		$data['sasaran'] = unserialize(SASARAN);
		$data['suplemen'] = $this->suplemen_model->get_suplemen($id);
		$sasaran = $data['suplemen']['sasaran'];
		$data['list_sasaran'] = $this->suplemen_model->list_sasaran($id, $sasaran);
		if (isset($_POST['terdata']))
		{
			$data['individu'] = $this->suplemen_model->get_terdata($_POST['terdata'], $sasaran);
		}
		else
		{
			$data['individu'] = NULL;
		}

		$data['form_action'] = site_url("suplemen/add_terdata");

		$this->render('suplemen/form_terdata', $data);
	}

	public function terdata($sasaran = 0, $id = 0)
	{
		$data = $this->suplemen_model->get_terdata_suplemen($sasaran, $id);

		$this->render('suplemen/terdata', $data);
	}

	public function data_terdata($id = 0)
	{
		$data['terdata'] = $this->suplemen_model->get_suplemen_terdata_by_id($id);
		$data['suplemen'] = $this->suplemen_model->get_suplemen($data['terdata']['id_suplemen']);
		$data['individu'] = $this->suplemen_model->get_terdata($data['terdata']['id_terdata'], $data['suplemen']['sasaran']);

		$this->render('suplemen/data_terdata', $data);
	}

	public function edit_terdata_form($id = 0)
	{
		$data = $this->suplemen_model->get_suplemen_terdata_by_id($id);
		$data['form_action'] = site_url("suplemen/edit_terdata/$id");

		$this->load->view('suplemen/edit_terdata', $data);
	}

	public function add_terdata($id)
	{
		$this->suplemen_model->add_terdata($_POST, $id);
		redirect("suplemen/rincian/$id");
	}

	public function edit_terdata($id)
	{
		$this->suplemen_model->edit_terdata($_POST, $id);
		$id_suplemen = $_POST['id_suplemen'];
		redirect("suplemen/rincian/$id_suplemen");
	}

	public function hapus_terdata($id_suplemen, $id_terdata)
	{
		$this->redirect_hak_akses('h');
		$this->suplemen_model->hapus_terdata($id_terdata);
		redirect("suplemen/rincian/$id_suplemen");
	}

	/*
	* $aksi = cetak/unduh
	*/
	public function dialog_daftar($id = 0, $aksi = '')
	{
		$data['aksi'] = $aksi;
		$data['pamong'] = $this->pamong_model->list_data();
		$data['form_action'] = site_url("suplemen/daftar/$id/$aksi");

		$this->load->view('global/ttd_pamong', $data);
	}

	/*
	* $aksi = cetak/unduh
	*/
	public function daftar($id = 0, $aksi = '')
	{
		if ($id > 0)
		{
			$post = $this->input->post();
			$temp = $this->session->per_page;
			$this->session->per_page = 1000000000; // Angka besar supaya semua data terunduh
			$data = $this->suplemen_model->get_rincian(1, $id);
			$data['sasaran'] = unserialize(SASARAN);
			$data['config'] = $this->header['desa'];
			$data['pamong_ttd'] = $this->pamong_model->get_data($post['pamong_ttd']);
			$data['pamong_ketahui'] = $this->pamong_model->get_data($post['pamong_ketahui']);
			$data['aksi'] = $aksi;
			$this->session->per_page = $temp;

			//pengaturan data untuk format cetak/ unduh
			$data['file'] = "Laporan Suplemen ".$data['suplemen']['nama'];
			$data['isi'] = "suplemen/cetak";
			$data['letak_ttd'] = ['2', '2', '3'];

			$this->load->view('global/format_cetak', $data);
		}
	}

}
