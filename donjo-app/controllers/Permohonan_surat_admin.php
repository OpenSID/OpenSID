<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk admin Layanan Mandiri
 *
 * donjo-app/controllers/Permohonan_surat_admin.php
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

class Permohonan_surat_admin extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('permohonan_surat_model');
		$this->load->model('penduduk_model');
		$this->load->model('surat_model');
		$this->load->model('keluarga_model');
		$this->load->model('config_model');
		$this->load->model('pamong_model');
		$this->load->model('referensi_model');

		$this->load->model('lapor_model');
		$this->modul_ini = 14;
		$this->sub_modul_ini = 98;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect($this->controller);
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

		$data['list_status_permohonan'] = $this->referensi_model->list_ref_flip(STATUS_PERMOHONAN);
		$data['func'] = 'index';
		$data['paging'] = $this->permohonan_surat_model->paging($p, $o);
		$data['main'] = $this->permohonan_surat_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->permohonan_surat_model->autocomplete();

		$this->render('mandiri/permohonan_surat', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect($this->controller);
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != '')
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect($this->controller);
	}

	public function periksa($id)
	{
		$periksa = $this->db->where('id', $id)
			->get('permohonan_surat')
			->row_array();
		$surat = $this->db->where('id', $periksa['id_surat'])
			->get('tweb_surat_format')
			->row_array();
		$data['url'] = $surat['url_surat'];
		$url = $data['url'];

		$data['list_dokumen'] = $this->penduduk_model->list_dokumen($_SESSION['id']);
		$data['individu'] = $this->surat_model->get_penduduk($periksa['id_pemohon']);
		$data['anggota'] = $this->keluarga_model->list_anggota($data['individu']['id_kk']);
		$this->get_data_untuk_form($url, $data);
		$data['isian_form'] = json_encode($this->ambil_isi_form($periksa['isian_form']));
		$data['periksa'] = $periksa;

		$data['syarat_permohonan'] = $this->permohonan_surat_model->get_syarat_permohonan($id);
		$data['masa_berlaku'] = $this->surat_model->masa_berlaku_surat($url);
		$data['surat_url'] = rtrim($_SERVER['REQUEST_URI'], "/clear");
		$data['form_action'] = site_url("surat/periksa_doc/$id/$url");
		$data['form_surat'] = "surat/form_surat.php";
		$data['data'] = $data;

		$this->render('mandiri/periksa_surat', $data);
	}

	public function belum_lengkap($id)
	{
		$this->permohonan_surat_model->update_status($id, array('status' => 1));
		redirect('permohonan_surat_admin/index');
	}

	public function update_status($id, $status='')
	{
		if ($status)
		{
			$this->permohonan_surat_model->update_status($id, array('status' => $status));
		}
		redirect('permohonan_surat_admin/index');
	}

	private function get_data_untuk_form($url, &$data)
	{
		$this->load->model('pamong_model');
		$data['surat_terakhir'] = $this->surat_model->get_last_nosurat_log($url);
		$data['surat'] = $this->surat_model->get_surat($url);
		$data['input'] = $this->input->post();
		$data['input']['nomor'] = $data['surat_terakhir']['no_surat_berikutnya'];
		$data['format_nomor_surat'] = $this->penomoran_surat_model->format_penomoran_surat($data);
		$data['lokasi'] = $this->config_model->get_data();
		$data['pamong'] = $this->surat_model->list_pamong();
		$pamong_ttd = $this->pamong_model->get_ttd();
		$pamong_ub = $this->pamong_model->get_ub();
		$data_form = $this->surat_model->get_data_form($url);
		if (is_file($data_form))
			include($data_form);
	}

	private function ambil_isi_form($isian_form)
	{
		$isian_form = json_decode($isian_form, true);
		$hapus = array('url_surat', 'url_remote', 'nik', 'id_surat', 'nomor', 'pilih_atas_nama', 'pamong', 'pamong_nip', 'jabatan', 'pamong_id');
		foreach ($hapus as $kolom)
		{
			unset($isian_form[$kolom]);
		}
		return $isian_form;
	}

	public function delete($id_permohonan)
	{
		$this->session->unset_userdata('success');
		$this->session->unset_userdata('error_msg');
		$this->permohonan_surat_model->delete($id_permohonan);
		redirect($_SERVER['HTTP_REFERER']);
	}

}
