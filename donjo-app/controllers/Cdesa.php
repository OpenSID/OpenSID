<?php
/*
 * File ini:
 *
 * Controller untuk Modul Cdesa
 *
 * donjo-app/controllers/Cdesa.php
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

class Cdesa extends Admin_Controller {

	private $set_page;
	private $list_session;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('config_model');
		$this->load->model('data_persil_model');
		$this->load->model('cdesa_model');
		$this->load->model('penduduk_model');
		$this->load->model('referensi_model');
		$this->modul_ini = 7;
		$this->set_page = ['20', '50', '100'];
		$this->list_session = ['cari'];
	}

	public function clear()
	{
		$this->session->unset_userdata($this->list_session);
		$this->session->per_page = $this->set_page[0];
		redirect($this->controller);
	}

	// TODO: fix
	public function autocomplete()
	{
		$data = $this->cdesa_model->autocomplete($this->input->post('cari'));
		echo json_encode($data);
	}

	public function search(){
		$this->session->cari = $this->input->post('cari') ?: NULL;
		redirect('cdesa');
	}

	public function index($page=1, $o=0)
	{
		$this->tab_ini = 12;
		$this->set_minsidebar(1);

		$data['cari'] = isset($_SESSION['cari']) ? $_SESSION['cari'] : '';
		$_SESSION['per_page'] = $_POST['per_page'] ?: null;
		$data['per_page'] = $_SESSION['per_page'];

		$data['func'] = 'index';
		$data['set_page'] = $this->set_page;
		$data['paging']  = $this->cdesa_model->paging_c_desa($page);
		$data['keyword'] = $this->data_persil_model->autocomplete();
		$data["desa"] = $this->config_model->get_data();
		$data["cdesa"] = $this->cdesa_model->list_c_desa($data['paging']->offset, $data['paging']->per_page);
		$data["persil_kelas"] = $this->data_persil_model->list_persil_kelas();

		$this->render('data_persil/c_desa', $data);
	}

	public function rincian($id)
	{
		$this->tab_ini = 13;
		$data = array();
		$data['cdesa'] = $this->cdesa_model->get_cdesa($id);
		$data['pemilik'] = $this->cdesa_model->get_pemilik($id);
		$data['persil'] = $this->cdesa_model->get_list_persil($id);
		$this->render('data_persil/rincian', $data);
	}

	public function mutasi($id_cdesa, $id_persil)
	{

		$data = array();
		$data['cdesa'] = $this->cdesa_model->get_cdesa($id_cdesa);
		$data['pemilik'] = $this->cdesa_model->get_pemilik($id_cdesa);
		$data['mutasi'] = $this->cdesa_model->get_list_mutasi($id_cdesa, $id_persil);
		$data['persil'] = $this->data_persil_model->get_persil($id_persil);
		if (empty($data['cdesa'])) show_404();

		$this->render('data_persil/mutasi_persil', $data);
	}

	public function create($mode=0, $id=0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Tanah', 'required');

		$this->set_minsidebar(1);		$this->tab_ini = empty($mode) ? 10 : 12;

		$post = $this->input->post();
		$data = array();
		$data["mode"] = $mode;
		$data["penduduk"] = $this->cdesa_model->list_penduduk();
		if ($mode === 'edit')
		{
			$data['cdesa'] = $this->cdesa_model->get_cdesa($id);
			$this->ubah_pemilik($id, $data, $post);
		}
		else
		{
			switch ($post['jenis_pemilik'])
			{
				case '1':
					# Pemilik desa
					if (!empty($post['nik']))
					{
						$data['pemilik'] = $this->cdesa_model->get_penduduk($post['nik'], $nik=true);
					}
					break;
				case '2':
					# Pemilik luar desa
					$data['cdesa']['jenis_pemilik'] = 2;
					break;
			}
		}

		$this->render('data_persil/create', $data);
	}

	private function ubah_pemilik($id, &$data, $post)
	{
		$jenis_pemilik_baru = $post['jenis_pemilik'] ?: 0;

		switch ($jenis_pemilik_baru)
		{
			case '0':
				// Buka form ubah pertama kali
				if ($data['cdesa']['jenis_pemilik'] == 1)
				{
					$data['pemilik'] = $this->cdesa_model->get_pemilik($id);
				}
				break;
			case '1':
				// Ubah atau ambil pemilik desa
				$data['pemilik'] = $this->cdesa_model->get_pemilik($id);
				if ($post['nik'] and $$data['pemilik']['nik'] != $post['nik'])
				{
					$data['pemilik'] = $this->cdesa_model->get_penduduk($post['nik'], $nik=true);
				}
				$data['cdesa']['jenis_pemilik'] = $jenis_pemilik_baru;
				break;
			case '2':
				// Ubah pemilik luar
				$data['cdesa']['jenis_pemilik'] = $jenis_pemilik_baru;
				break;
		}
	}

	public function simpan_cdesa($page=1)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('c_desa','Nomor Surat C-DESA','required|trim|numeric');
		$this->form_validation->set_rules('c_desa', 'Username', 'callback_cek_nomor');

		if ($this->form_validation->run() != false)
		{
			$id_cdesa = $this->cdesa_model->simpan_cdesa();
			if ($this->input->post('id')) redirect("cdesa");
			else redirect("cdesa/create_mutasi/$id_cdesa");
		}
		else
		{
			$_SESSION["success"] = -1;
			$_SESSION["error_msg"] = trim(strip_tags(validation_errors()));
			$jenis_pemilik = $this->input->post('jenis_pemilik');
			$id	= $this->input->post('id');
			if ($jenis_pemilik == 1)
			{
				if ($id)
					redirect("cdesa/create/edit/".$id);
				else
					redirect("cdesa/create");
			}
			else
			{
				if ($id)
					redirect("cdesa/create/edit/".$id);
				else
					redirect("cdesa/create");
			}
		}
	}

	public function create_mutasi($id_cdesa, $id_persil='', $id_mutasi='')
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Tanah', 'required');

		$this->set_minsidebar(1);
		if (empty($id_persil)) $id_persil = $this->input->post('id_persil');

		if ($id_persil)
		{
			$data['persil'] = $this->data_persil_model->get_persil($id_persil);
		}
		else
		{
			$data['persil'] = NULL;
		}

		if ($id_mutasi)
		{
			$data['persil'] = $this->cdesa_model->get_persil($id_mutasi);
			$data["mutasi"] = $this->cdesa_model->get_mutasi($id_mutasi);
		}
		$data['cdesa'] = $this->cdesa_model->get_cdesa($id_cdesa);
		$data['list_cdesa'] = $this->cdesa_model->list_c_desa(0, 0, [$id_cdesa]);
		$data['pemilik'] = $this->cdesa_model->get_pemilik($id_cdesa);

		$data['list_persil'] = $this->data_persil_model->list_persil();
		$data["persil_lokasi"] = $this->data_persil_model->list_dusunrwrt();
		$data["persil_kelas"] = $this->referensi_model->list_by_id('ref_persil_kelas');
		$data["persil_sebab_mutasi"] = $this->referensi_model->list_by_id('ref_persil_mutasi');

		$this->render('data_persil/create_mutasi', $data);
	}

	public function simpan_mutasi($id_cdesa, $id_mutasi='')
	{
		$data = $this->cdesa_model->simpan_mutasi($id_cdesa, $id_mutasi, $this->input->post());
		if ($data['id_persil'])
			redirect("cdesa/mutasi/$id_cdesa/$data[id_persil]");
		else
			redirect("cdesa/rincian/$id_cdesa");
	}

	public function hapus_mutasi($cdesa, $id_mutasi)
	{
		$id_persil = $this->db->select('id_persil')
			->where('id', $id_mutasi)
			->get('mutasi_cdesa')
			->row()->id_persil;
		$this->db->where('id', $id_mutasi)
			->delete('mutasi_cdesa');
		redirect("cdesa/mutasi/$cdesa/$id_persil");
	}

	// TODO: gunakan pada waktu validasi C-Desa
	public function cek_nomor($nomor)
	{
		$id_cdesa = $this->input->post('id');
		if ($id_cdesa) $this->db->where('id <>', $id_cdesa);
		$ada = $this->db
			->where('nomor', $nomor)
			->get('cdesa')->num_rows();

		if ($ada)
		{
			$this->form_validation->set_message('cek_nomor', 'Nomor C-Desa sudah ada');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	// TODO: perbaiki
	public function panduan()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->set_minsidebar(1);
		$this->tab_ini = 15;
		$nav['act'] = 7;
		$this->render('data_persil/panduan');
	}

	public function hapus($id)
	{
		$this->redirect_hak_akses('h', "cdesa");
		$this->cdesa_model->hapus_cdesa($id);
		redirect("cdesa");
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
		$data['data_cdesa'] = $this->cdesa_model->list_c_desa();
		$this->load->view('data_persil/c_desa_cetak', $data);
	}

	public function unduh($o=0)
	{
		$data['data_cdesa'] = $this->cdesa_model->list_c_desa();
		$this->load->view('data_persil/c_desa_unduh', $data);
	}

	public function form_c_desa($id=0)
	{
		$data['desa'] = $this->config_model->get_data();
		$data['cdesa'] = $this->cdesa_model->get_cdesa($id);
		$data['basah'] = $this->cdesa_model->get_cetak_mutasi($id, 'BASAH');
		$data['kering'] = $this->cdesa_model->get_cetak_mutasi($id, 'KERING');
		$this->load->view('data_persil/c_desa_form_print', $data);
	}

	public function awal_persil($id_cdesa, $id_persil, $hapus=false)
	{
		$this->data_persil_model->awal_persil($id_cdesa, $id_persil, $hapus);
		redirect("cdesa/mutasi/$id_cdesa/$id_persil");
	}
}

?>
