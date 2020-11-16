<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Layanan Surat
 *
 * donjo-app/controllers/Surat_master.php
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

class Surat_master extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('surat_master_model');
		$this->load->model('klasifikasi_model');
		$this->load->model('referensi_model');

		$this->load->model('lapor_model');
		$this->modul_ini = 4;
		$this->sub_modul_ini = 30;
	}

	public function clear($id = 0)
	{
		$_SESSION['per_page'] = 20;
		$_SESSION['surat'] = $id;
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('surat_master');
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
		$data['paging'] = $this->surat_master_model->paging($p, $o);
		$data['main'] = $this->surat_master_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->surat_master_model->autocomplete();
		$this->set_minsidebar(1);
		$this->render('surat_master/table', $data);
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['klasifikasi'] = $this->klasifikasi_model->list_kode();
		$data['list_ref_syarat'] = $this->lapor_model->get_surat_ref_all();
		$data['list_ref_masa'] = $this->referensi_model->list_ref(MASA_BERLAKU);

		if ($id)
		{
			$data['surat_master'] = $this->surat_master_model->get_surat_format($id);
			$data['form_action'] = site_url("surat_master/update/$p/$o/$id");
			$data['syarat_surat'] = $this->lapor_model->get_current_surat_ref($id);
		}
		else
		{
			$data['surat_master'] = NULL;
			$data['form_action'] = site_url("surat_master/insert");
			$data['syarat_surat'] = NULL;
		}

		$this->set_minsidebar(1);
		$this->render('surat_master/form', $data);
	}

	public function form_upload($p = 1, $o = 0, $url = '')
	{
		$data['form_action'] = site_url("surat_master/upload/$p/$o/$url");
		$this->load->view('surat_master/ajax-upload', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('surat_master');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('surat_master');
	}

	public function insert()
	{
		$syarat = $this->input->post('syarat');
		$mandiri = $this->input->post('mandiri');
		unset($_POST['syarat']);
		$this->surat_master_model->insert();
		$id = $this->db->insert_id();
		$this->lapor_model->update_syarat_surat($id, $syarat, $mandiri);
		redirect('surat_master');
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$syarat = $this->input->post('syarat');
		$mandiri = $this->input->post('mandiri');
		unset($_POST['syarat']);
		$this->surat_master_model->update($id);
		$this->lapor_model->update_syarat_surat($id, $syarat, $mandiri);
		redirect("surat_master/index/$p/$o");
	}

	public function upload($p = 1, $o = 0, $url = '')
	{
		$this->surat_master_model->upload($url);
		redirect("surat_master/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "surat_master/index/$p/$o");
		$this->surat_master_model->delete($id);
		redirect("surat_master/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "surat_master/index/$p/$o");
		$this->surat_master_model->delete_all();
		redirect("surat_master/index/$p/$o");
	}

	public function p_insert($in = '')
	{
		$this->surat_master_model->p_insert($in);
		redirect("surat_master/atribut/$in");
	}

	public function p_update($in = '', $id = '')
	{
		$this->surat_master_model->p_update($id);
		redirect("surat_master/atribut/$in");
	}

	public function p_delete($in = '', $id = '')
	{
		$this->redirect_hak_akses('h', "surat_master/atribut/$in");
		$this->surat_master_model->p_delete($id);
		redirect("surat_master/atribut/$in");
	}

	public function p_delete_all()
	{
		$this->redirect_hak_akses('h', "surat_master/atribut/$in");
		$this->surat_master_model->p_delete_all();
		redirect("surat_master/atribut/$in");
	}

	public function kode_isian($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		if ($id)
		{
			$data['surat_master'] = $this->surat_master_model->get_surat_format($id);
			$data['inputs'] = $this->surat_master_model->get_kode_isian($data['surat_master']);
		}

		$this->set_minsidebar(1);
		$this->render('surat_master/kode_isian', $data);
	}

	public function lock($id = 0, $k = 0)
	{
		$this->surat_master_model->lock($id, $k);
		redirect("surat_master");
	}

	public function favorit($id = 0, $k = 0)
	{
		$this->surat_master_model->favorit($id, $k);
		redirect("surat_master");
	}

}
