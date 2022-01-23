<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Analisis
 *
 * donjo-app/controllers/Analisis_master.php
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

require_once 'vendor/google-api-php-client/vendor/autoload.php';

class Analisis_master extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('analisis_master_model');
		$this->load->model('analisis_import_model');

		unset($_SESSION['submenu']);
		unset($_SESSION['asubmenu']);
		$this->modul_ini = 5;
		$this->sub_modul_ini = 110;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['state']);
		redirect('analisis_master');
	}

	public function index($p=1, $o=0)
	{
		$this->session->unset_userdata(['analisis_master', 'analisis_nama']);

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

		$data['paging'] = $this->analisis_master_model->paging($p,$o);
		$data['data_import'] = $this->session->data_import;
		$data['list_error'] = $this->session->list_error;
		$data['keyword'] = $this->analisis_master_model->autocomplete();
		$data['list_subjek'] = $this->analisis_master_model->list_subjek();
		$data['main'] = $this->analisis_master_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);

		$this->session->unset_userdata('list_error');

		$this->set_minsidebar(1);

		$this->render('analisis_master/table', $data);
	}

	public function form($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('u');
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['analisis_master'] = $this->analisis_master_model->get_analisis_master($id);
			$data['form_action'] = site_url("analisis_master/update/$p/$o/$id");
		}
		else
		{
			$data['analisis_master'] = null;
			$data['form_action'] = site_url("analisis_master/insert");
		}

		$data['list_format_impor'] = array('1' => 'BDT 2015');
		$data['list_kelompok'] = $this->analisis_master_model->list_kelompok();
		$data['list_analisis'] = $this->analisis_master_model->list_analisis_child();
		$this->set_minsidebar(1);
		$this->render('analisis_master/form', $data);
	}

	public function panduan()
	{
		$this->set_minsidebar(1);
		$this->render('analisis_master/panduan');
	}

	public function import_analisis()
	{
		$this->redirect_hak_akses('u');
		$this->set_minsidebar(1);
		$data['form_action'] = site_url("analisis_master/import");
		$this->load->view('analisis_master/import', $data);
	}

	public function import_gform()
	{
		$this->redirect_hak_akses('u');
		$this->set_minsidebar(1);
		$data['form_action'] = site_url("analisis_master/exec_import_gform");
		$this->load->view('analisis_master/import_gform', $data);
	}

	public function menu($id='')
	{
		$_SESSION['analisis_master'] = $id;
		$data['analisis_master'] = $this->analisis_master_model->get_analisis_master($id);
		$_SESSION['analisis_nama'] = $data['analisis_master']['nama'];
		$da = $data['analisis_master'];
		$subjek = $da['subjek_tipe'];
		$_SESSION['subjek_tipe'] = $subjek;

		switch ($subjek)
		{
			case 1:
				$data['menu_respon'] = "analisis_respon_penduduk";
				$data['menu_laporan'] = "analisis_laporan_penduduk";
				break;
			case 2:
				$data['menu_respon'] = "analisis_respon_keluarga";
				$data['menu_laporan'] = "analisis_laporan_keluarga";
				break;
			case 3:
				$data['menu_respon'] = "analisis_respon_rtm";
				$data['menu_laporan'] = "analisis_laporan_rtm";
				break;
			case 4:
				$data['menu_respon'] = "analisis_respon_kelompok";
				$data['menu_laporan'] = "analisis_laporan_kelompok";
				break;
			default:
				redirect('analisis_master');
		}
		$data['menu_respon'] = "analisis_respon";
		$data['menu_laporan'] = "analisis_laporan";

		/* TODO: Periksa apakah perlu lakukan pre_update */
		// $this->load->model('analisis_respon_model');
		// $this->analisis_respon_model->pre_update();
		$this->set_minsidebar(1);
		$this->render('analisis_master/menu', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis_master');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('analisis_master');
	}

	public function state()
	{
		$filter = $this->input->post('state');
		if ($filter != 0)
			$_SESSION['state']=$filter;
		else unset($_SESSION['state']);
		redirect('analisis_master');
	}

	public function insert()
	{
		$this->redirect_hak_akses('u');
		$this->analisis_master_model->insert();
		redirect('analisis_master');
	}

	public function import()
	{
		$this->redirect_hak_akses('u');
		$this->analisis_import_model->import_excel();
		redirect('analisis_master');
	}

	/**
		1. Credential
		2. Id script
		3. Redirect URI

		- Jika 1 dan 2 diisi (asumsi user pakai akun google sendiri) eksekusi dari nilai yg diisi user. Abaikan isisan 3. Redirect ambil dari isian 1
		- Jika 1 dan 2 kosong. 3 diisi. Import gform langsung menuju redirect field 3
		- Jika semua tidak terisi (asumsi opensid ini yang jalan di server OpenDesa) ambil credential setting di file config
	*/
	private function get_redirect_uri()
	{
		if ($this->setting->api_gform_credential)
		{
			$api_gform_credential = $this->setting->api_gform_credential;
		}
		elseif (empty($this->setting->api_gform_redirect_uri))
		{
			$api_gform_credential = config_item('api_gform_credential');
		}
		if ($api_gform_credential)
		{
			$credential_data = json_decode(str_replace('\"' , '"', $api_gform_credential), true);
			$redirect_uri = $credential_data['web']['redirect_uris'][0];
		}
		if (empty($redirect_uri)) $redirect_uri = $this->setting->api_gform_redirect_uri;

		return $redirect_uri;
	}

	public function exec_import_gform()
	{
		$this->redirect_hak_akses('u');
		$this->session->google_form_id = $this->input->post('input-form-id');

		$REDIRECT_URI = $this->get_redirect_uri();
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$self_link = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

		if ($this->input->get('outsideRetry') == "true")
		{
			$url = $REDIRECT_URI . '?formId=' . $this->input->get('formId') . '&redirectLink=' . $self_link . '&outsideRetry=true&code=' . $this->input->get('code');

			$client = new Google\Client();
			$httpClient = $client->authorize();
			$response = $httpClient->get($url);

			$variabel = json_decode($response->getBody(), true);
			$this->session->data_import = $variabel;
			$this->session->gform_id = $this->input->get('formId');
			$this->session->success = 5;
			redirect('analisis_master');
		}
		else
		{
			$url = $REDIRECT_URI . '?formId=' . $this->input->post('input-form-id') . '&redirectLink=' . $self_link ;
			header('Location: ' . $url);
		}
	}

	public function update($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('u');
		$this->analisis_master_model->update($id);
		redirect("analisis_master/index/$p/$o");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "analisis_master/index/$p/$o");
		$this->analisis_master_model->delete($id);
		redirect("analisis_master/index/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "analisis_master/index/$p/$o");
		$this->analisis_master_model->delete_all();
		redirect("analisis_master/index/$p/$o");
	}

	public function save_import_gform()
	{
		$this->redirect_hak_akses('u');
		$this->analisis_import_model->save_import_gform();
		$this->session->unset_userdata('data_import');
		redirect('analisis_master');
	}

	public function update_gform($id=0)
	{
		$this->redirect_hak_akses('u');
		$this->session->google_form_id = $this->analisis_master_model->get_analisis_master($id)['gform_id'];

		$REDIRECT_URI = $this->get_redirect_uri();
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$self_link = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

		if ($this->input->get('outsideRetry') == "true")
		{
			$url = $REDIRECT_URI . '?formId=' . $this->input->get('formId') . '&redirectLink=' . $self_link . '&outsideRetry=true&code=' . $this->input->get('code');

			$client = new Google\Client();
			$httpClient = $client->authorize();
			$response = $httpClient->get($url);

			$variabel = json_decode($response->getBody(), true);
			$this->session->data_import = $variabel;
			$this->analisis_import_model->update_import_gform($id, $variabel);

			redirect('analisis_master');
		}
		else
		{
			$url = $REDIRECT_URI . '?formId=' . $this->session->google_form_id . '&redirectLink=' . $self_link ;
			header('Location: ' . $url);
		}

	}
}
