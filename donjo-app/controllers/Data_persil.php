<?php
/*
 * File ini:
 *
 * Controller untuk Modul Persil
 *
 * donjo-app/controllers/Data_persil.php
 *
 */

/*
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
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Data_persil extends Admin_Controller {

	private $set_page;
	private $list_session;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('config_model');
		$this->load->model('data_persil_model');
		$this->load->model('cdesa_model');
		$this->load->model('penduduk_model');
		$this->controller = 'data_persil';
		$this->modul_ini = 7;
		$this->set_page = ['20', '50', '100'];
		$this->list_session = ['cari'];
	}

	public function clear()
	{
		$this->session->unset_userdata($this->list_session);
		$this->session->per_page = $this->set_page[0];
		redirect('data_persil');
	}

	// TODO: fix
	public function autocomplete()
	{
		$data = $this->data_persil_model->autocomplete($this->input->post('cari'));
		echo json_encode($data);
	}

	public function search(){
		$this->session->cari = $this->input->post('cari') ?: NULL;
		redirect('data_persil');
	}

	public function index($page=1, $o=0)
	{
		$this->set_minsidebar(1);
		$this->tab_ini = 13;

		$data['cari'] = htmlentities($_SESSION['cari']) ?: '';
		$this->session->per_page = $this->input->post('per_page') ?: null;
		$data['per_page'] = $this->session->per_page;

		$data['func'] = 'index';
		$data['set_page'] = $this->set_page;
		$data["desa"] = $this->config_model->get_data();
		$data['paging']  = $this->data_persil_model->paging($page);
		$data["persil"] = $this->data_persil_model->list_data($data['paging']->offset, $data['paging']->per_page);
		$data["persil_kelas"] = $this->data_persil_model->list_persil_kelas();
		$data['keyword'] = $this->data_persil_model->autocomplete();

		$this->render('data_persil/persil', $data);
	}

	public function rincian($id=0)
	{
		$this->tab_ini = 13;
		$data = [];
		$data['persil'] = $this->data_persil_model->get_persil($id);
		$data['mutasi'] = $this->data_persil_model->get_list_mutasi($id);
		$this->render('data_persil/rincian_persil', $data);
	}

	public function form($id='', $id_cdesa='')
	{
		$this->set_minsidebar(1);
		$this->tab_ini = 13;

		if ($id) $data["persil"] = $this->data_persil_model->get_persil($id);
		if ($id_cdesa) $data["id_cdesa"] = $id_cdesa;
		$data['list_cdesa'] = $this->cdesa_model->list_c_desa();
		$data["persil_lokasi"] = $this->data_persil_model->list_dusunrwrt();
		$data["persil_kelas"] = $this->data_persil_model->list_persil_kelas();
		$this->render('data_persil/form_persil', $data);
	}

	public function simpan_persil($page=1)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('no_persil','Nomor Surat Persil','required|trim|numeric');
		$this->form_validation->set_rules('nomor_urut_bidang','Nomor Urut Bidang','required|trim|numeric');
		$this->form_validation->set_rules('kelas','Kelas Tanah','required|trim|numeric');

		if ($this->form_validation->run() != false)
		{
			$id_persil = $this->data_persil_model->simpan_persil($this->input->post());
			$cdesa_awal = $this->input->post('cdesa_awal');
			if (!$this->input->post('id_persil') and $cdesa_awal)
				redirect("cdesa/mutasi/$cdesa_awal/$id_persil");
			else
				redirect("data_persil");
		}

		$this->session->success = -1;
		$this->session->error_msg = trim(strip_tags(validation_errors()));
		$id	= $this->input->post('id_persil');
		redirect("data_persil/form/".$id);
	}

	public function hapus($id)
	{
		$this->redirect_hak_akses('h', "data_persil");
		$this->data_persil_model->hapus($id);
		redirect("data_persil/clear");
	}

	public function import()
	{
		$data['form_action'] = site_url("data_persil/import_proses");
		$this->load->view('data_persil/import', $data);
	}

	public function import_proses()
	{
		$this->data_persil_model->impor_persil();
		redirect("data_persil");
	}

	public function cetak($o=0)
	{
		$data['persil'] = $this->data_persil_model->list_data(0, 10000);
    $data['persil_kelas'] = $this->data_persil_model->list_persil_kelas();
		$this->load->view('data_persil/persil_cetak', $data);
	}

	public function unduh($mode="", $o=0)
	{
		$data['persil'] = $this->data_persil_model->list_data(0, 10000);
    $data['persil_kelas'] = $this->data_persil_model->list_persil_kelas();
		$this->load->view('data_persil/persil_unduh', $data);
	}

	public function kelasid()
	{
		$data =[];
		$id = $this->input->post('id');
		$kelas = $this->data_persil_model->list_persil_kelas($id);
		foreach ($kelas as $key => $item)
		{
			$data[] = array('id' => $key, 'kode' => $item['kode'], 'ndesc' => $item['ndesc']);
		}
		echo json_encode($data);
	}
}

?>
