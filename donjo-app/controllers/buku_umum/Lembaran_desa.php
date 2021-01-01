<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Lembaran Desa
 *
 * donjo-app/controllers/buku_umum/Lembaran_desa.php
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

class Lembaran_desa extends Admin_Controller {

	private $_set_page;
	private $_list_session;

	public function __construct()
	{
		parent::__construct();

		$this->load->model(['web_dokumen_model', 'referensi_model', 'pamong_model']);
		$this->modul_ini = 301;
		$this->sub_modul_ini = 302;
		$this->_list_session = ['filter', 'cari', 'jenis_peraturan'];
		$this->_set_page = ['20', '50', '100'];
	}

	// Buku Lembaran Desa dan Berita Desa
	public function index($p=1, $o=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$kat = 3;

		$data['cari'] = $this->session->cari ?: '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging'] = $this->web_dokumen_model->paging($kat, $p, $o);
		$data['main'] = $this->web_dokumen_model->list_data($kat, $o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_dokumen_model->autocomplete();
		$data['submenu'] = $this->referensi_model->list_data('ref_dokumen');
		$data['jenis_peraturan'] = $this->referensi_model->list_ref(JENIS_PERATURAN_DESA);
		$data['sub_kategori'] = $_SESSION['sub_kategori'];
    $_SESSION['menu_kategori'] = TRUE;

		foreach ($data['submenu'] as $s)
		{
			if ($kat == $s['id'])
			{
				$_SESSION['submenu'] = $s['id'];
				$_SESSION['sub_kategori'] = $s['kategori'];
				$_SESSION['kode_kategori'] = $s['id'];
			}
		}

		$data['main_content'] = 'dokumen/table_lembaran_desa';
		$data['subtitle'] = "Buku Lembaran Desa Dan Berita Desa";
		$data['selected_nav'] = 'lembaran';
		$this->set_minsidebar(1);

		$this->load->view('header', $this->header);
		$this->load->view('nav', $nav);
		$this->load->view('bumindes/umum/main', $data);
		$this->load->view('footer');
	}

	public function clear()
	{
		$this->session->unset_userdata($this->_list_session);
		$this->session->per_page = $this->_set_page[0];
		redirect("lembaran_desa");
	}

	public function form($p=1, $o=0, $id='')
	{
		$data['kat'] = 3;
		$data['list_kategori'] = $this->web_dokumen_model->list_kategori();
		$data['jenis_peraturan'] = $this->referensi_model->list_ref(JENIS_PERATURAN_DESA);

		if ($id)
		{
			$data['dokumen'] = $this->web_dokumen_model->get_dokumen($id);
			$data['form_action'] = site_url("lembaran_desa/update/$id/$p/$o");
			if ($jenis_peraturan = $data['dokumen']['attr']['jenis_peraturan'] and !in_array($jenis_peraturan, $data['jenis_peraturan']))
			{
				$data['jenis_peraturan'][] = $jenis_peraturan;
			}
		}
		$data['kat_nama'] = 'Lembaran Desa';
		$data['kembali_ke'] = site_url("lembaran_desa/index/$p/$o");

		$this->render('dokumen/form', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		$this->session->cari = $cari ?: null;
		redirect("lembaran_desa/index");
	}

	public function filter($filter = 'filter')
	{
		$this->session->$filter = $this->input->post($filter);
		redirect("lembaran_desa/index");
	}

	public function update($id='', $p=1, $o=0)
	{
		$this->session->success = 1;
		$outp = $this->web_dokumen_model->update($id);
		status_sukses($outp);
		redirect("lembaran_desa/index/$p/$o");
	}

	public function lock($id, $val = 1)
	{
		$this->web_dokumen_model->dokumen_lock($id, $val);
		redirect("lembaran_desa");
	}

	public function dialog_daftar($aksi = "cetak", $o = 0)
	{
		$data['aksi'] = $aksi;
		$data['form_action'] = site_url("lembaran_desa/daftar/$aksi/$o");
		$data['jenis_peraturan'] = $this->referensi_model->list_ref(JENIS_PERATURAN_DESA);
		$data['pamong'] = $this->pamong_model->list_data();
		$data['tahun_laporan'] = $this->web_dokumen_model->list_tahun($kat = 3);
		$this->load->view('dokumen/dialog_cetak', $data);
	}

	public function daftar($aksi = "cetak", $o = 1)
	{
		$data = $this->data_cetak($aksi);
		$template = $data['template'];
		$this->load->view("dokumen/$template", $data);
	}

	private function data_cetak($aksi)
	{
		$post = $this->input->post();
		$data['main'] = $this->web_dokumen_model->data_cetak($kat = 3, $post['tahun'], $post['jenis_peraturan']);
		$data['input'] = $post;
		$data['pamong'] = $this->pamong_model->list_data();
		$data['tahun'] = $post['tahun'];
		$data['desa'] = $this->config_model->get_data();
		$data['aksi'] = $aksi;
		$data['template'] = 'lembaran_desa_print';
		return $data;
	}

}
