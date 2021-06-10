<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Lapak Admin
 *
 * donjo-app/controllers/Lapak_admin.php
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

class Lapak_admin extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->modul_ini = 324;
		$this->load->model(['lapak_model', 'penduduk_model']);
	}

	public function index()
	{
		redirect("$this->controller/produk");
	}

	// NAVIGASI
	public function navigasi()
	{
		$data = [
			'jml_produk' => $this->lapak_model->get_produk()->count_all_results(),
			'jml_pelapak' => $this->lapak_model->get_pelapak()->count_all_results(),
			'jml_kategori' => $this->lapak_model->get_kategori()->count_all_results(),
			'jml_pengaturan' => '.'
		];

		return $data;
	}

	// PRODUK
	public function produk()
	{
		$data['navigasi'] = $this->navigasi();

		if ($data['navigasi']['jml_pelapak'] <= 0) redirect("$this->controller/pelapak");
		if ($data['navigasi']['jml_kategori'] <= 0) redirect("$this->controller/kategori");

		if ($this->input->is_ajax_request())
		{
			$start = $this->input->post('start');
			$length = $this->input->post('length');
			$search = $this->input->post('search[value]');
			$order = $this->lapak_model::ORDER_ABLE_PRODUK[$this->input->post('order[0][column]')];
			$dir = $this->input->post('order[0][dir]');
			$id_pend = $this->input->post('id_pend');
			$id_produk_kategori = $this->input->post('id_produk_kategori');

			return $this->json_output(
				[
					'draw' => $this->input->post('draw'),
					'recordsTotal' => $this->lapak_model->get_produk()->count_all_results(),
					'recordsFiltered' => $this->lapak_model->get_produk($search, $id_pend, $id_produk_kategori)->count_all_results(),
					'data' => $this->lapak_model->get_produk($search, $id_pend, $id_produk_kategori)->order_by($order, $dir)->limit($length, $start)->get()->result(),
				]
			);
		}

		$data['pelapak'] = $this->lapak_model->get_pelapak()->get()->result();
		$data['kategori'] = $this->lapak_model->get_kategori()->get()->result();

		$this->render("$this->controller/produk/index", $data);
	}

	public function produk_form($id = '')
	{
		$this->redirect_hak_akses("u");

		if ($id)
		{
			$data['main'] = $this->lapak_model->produk_detail($id) ?? show_404();
			$data['aksi'] = 'Ubah';
			$data['form_action'] = site_url("$this->controller/produk_update/$id");
		}
		else
		{
			$data['main'] = NULL;
			$data['aksi'] = 'Tambah';
			$data['form_action'] = site_url("$this->controller/produk_insert");
		}

		$data['pelapak'] = $this->lapak_model->get_pelapak()->get()->result();
		$data['kategori'] = $this->lapak_model->get_kategori()->get()->result();
		$data['satuan'] = $this->lapak_model->get_satuan();

		$this->render("$this->controller/produk/form", $data);
	}

	public function produk_insert()
	{
		$this->redirect_hak_akses("u");
		$this->lapak_model->produk_insert();
		redirect("$this->controller/produk");
	}

	public function produk_update($id = '')
	{
		$this->redirect_hak_akses("u");
		$this->lapak_model->produk_update($id);
		redirect("$this->controller/produk");
	}

	public function produk_delete($id)
	{
		$this->redirect_hak_akses("h");
		$this->lapak_model->produk_delete($id);
		redirect("$this->controller/produk");
	}

	public function produk_delete_all()
	{
		$this->redirect_hak_akses("h");
		$this->lapak_model->produk_delete_all();
		redirect("$this->controller/produk");
	}

	public function produk_detail($id = 0)
	{
		$data['main'] = $this->lapak_model->produk_detail($id);

		$this->load->view("$this->controller/produk/detail", $data);
	}

	public function produk_status($id = 0, $status = 0)
	{
		$this->redirect_hak_akses("u");
		$this->lapak_model->produk_status($id, $status);
		redirect("$this->controller/produk");
	}

	// PELAPAK
	public function pelapak()
	{
		$data['navigasi'] = $this->navigasi();

		if ($this->input->is_ajax_request())
		{
			$start = $this->input->post('start');
			$length = $this->input->post('length');
			$search = $this->input->post('search[value]');
			$order = $this->lapak_model::ORDER_ABLE_PELAPAK[$this->input->post('order[0][column]')];
			$dir = $this->input->post('order[0][dir]');
			$id_pend = $this->input->post('id_pend');
			$kategori = $this->input->post('kategori');

			return $this->json_output(
				[
					'draw' => $this->input->post('draw'),
					'recordsTotal' => $this->lapak_model->get_pelapak()->count_all_results(),
					'recordsFiltered' => $this->lapak_model->get_pelapak($search)->count_all_results(),
					'data' => $this->lapak_model->get_pelapak($search)->order_by($order, $dir)->limit($length, $start)->get()->result(),
				]
			);
		}

		$this->render("$this->controller/pelapak/index", $data);
	}

	public function pelapak_form($id = '')
	{
		$this->redirect_hak_akses("u");

		if ($id)
		{
			$data['main'] = $this->lapak_model->pelapak_detail($id) ?? show_404();
			$data['form_action'] = site_url("$this->controller/pelapak_update/$id");
		}
		else
		{
			$data['main'] = NULL;
			$data['form_action'] = site_url("$this->controller/pelapak_insert");
		}

		$data['list_penduduk'] = $this->lapak_model->list_penduduk();

		$this->load->view("$this->controller/pelapak/modal_form", $data);
	}

	public function pelapak_maps($id = '')
	{
		$desa = $this->header['desa'];
		$pelapak = $this->lapak_model->pelapak_detail($id) ?? show_404();
		if ($pelapak) $penduduk = $this->penduduk_model->get_penduduk_map($pelapak->id_pend);
		
		switch (true)
		{
			case ($pelapak->lat || $pelapak->lng):
				$lat = $pelapak->lat;
				$lng = $pelapak->lng;
				$zoom = $pelapak->zoom ?? 10;
				break;

			case ($penduduk['lat'] || $penduduk['lng']):
				$lat = $penduduk['lat'];
				$lng = $penduduk['lng'];
				$zoom = $penduduk['zoom'] ?? 10;
				break;

			case ($desa['lat'] || $desa['lng']):
				$lat = $desa['lat'];
				$lng = $desa['lng'];
				$zoom = $desa['zoom'] ?? 10;
				break;

			default:
				$lat = -1.0546279422758742;
				$lng = 116.71875000000001;
				$zoom = 10;
				break;
		}

		$data['pelapak'] = $pelapak;
		$data['lokasi'] = [
			'ini' => $ini,
			'lat' => $lat,
			'lng' => $lng,
			'zoom' => $zoom
		];
		$data['desa'] = $desa;
		$data['wil_atas'] = $desa;
		$data['dusun_gis'] = $this->wilayah_model->list_dusun();
		$data['rw_gis'] = $this->wilayah_model->list_rw();
		$data['rt_gis'] = $this->wilayah_model->list_rt();
		$data['form_action'] = site_url("$this->controller/pelapak_update_maps/$id");

		$this->render("$this->controller/pelapak/maps", $data);
	}

	public function pelapak_insert()
	{
		$this->redirect_hak_akses("u");
		$this->lapak_model->pelapak_insert();
		redirect("$this->controller/pelapak");
	}

	public function pelapak_update_maps($id = '')
	{
		$this->redirect_hak_akses("u");
		$this->lapak_model->pelapak_update_maps($id);
		redirect("$this->controller/pelapak");
	}

	public function pelapak_update($id = '')
	{
		$this->redirect_hak_akses("u");
		$this->lapak_model->pelapak_update($id);
		redirect("$this->controller/pelapak");
	}

	public function pelapak_delete($id)
	{
		$this->redirect_hak_akses("h");
		$this->lapak_model->pelapak_delete($id);
		redirect("$this->controller/pelapak");
	}

	public function pelapak_delete_all()
	{
		$this->redirect_hak_akses("h");
		$this->lapak_model->pelapak_delete_all();
		redirect("$this->controller/pelapak");
	}

	public function pelapak_status($id = 0, $status = 0)
	{
		$this->redirect_hak_akses("u");
		$this->lapak_model->pelapak_status($id, $status);
		redirect("$this->controller/pelapak");
	}

	// KATEGORI
	public function kategori()
	{
		$data['navigasi'] = $this->navigasi();

		if ($this->input->is_ajax_request())
		{
			$start = $this->input->post('start');
			$length = $this->input->post('length');
			$search = $this->input->post('search[value]');
			$order = $this->lapak_model::ORDER_ABLE_KATEGORI[$this->input->post('order[0][column]')];
			$dir = $this->input->post('order[0][dir]');

			return $this->json_output(
				[
					'draw' => $this->input->post('draw'),
					'recordsTotal' => $this->lapak_model->get_kategori()->count_all_results(),
					'recordsFiltered' => $this->lapak_model->get_kategori($search)->count_all_results(),
					'data' => $this->lapak_model->get_kategori($search)->order_by($order, $dir)->limit($length, $start)->get()->result(),
				]
			);
		}

		$this->render("$this->controller/kategori/index", $data);
	}

	public function kategori_form($kategori = '')
	{
		$this->redirect_hak_akses("u");

		if ($kategori)
		{
			$data['main'] = $this->lapak_model->detail_kategori($kategori) ?? show_404();
			$data['form_action'] = site_url("$this->controller/kategori_update/$kategori");
		}
		else
		{
			$data['main'] = NULL;
			$data['form_action'] = site_url("$this->controller/kategori_insert");
		}

		$this->load->view("$this->controller/kategori/modal_form", $data);
	}

	public function kategori_insert()
	{
		$this->redirect_hak_akses("u");
		$this->lapak_model->kategori_insert();
		redirect("$this->controller/kategori");
	}

	public function kategori_update($kategori = '')
	{
		$this->redirect_hak_akses("u");
		$this->lapak_model->kategori_update($kategori);
		redirect("$this->controller/kategori");
	}

	public function kategori_delete($id)
	{
		$this->redirect_hak_akses("h");
		$this->lapak_model->kategori_delete($id);
		redirect("$this->controller/produk");
	}

	public function kategori_delete_all()
	{
		$this->redirect_hak_akses("h");
		$this->lapak_model->kategori_delete_all();
		redirect("$this->controller/produk");
	}

	// PENGATURAN
	public function pengaturan()
	{
		$data = ['kategori' => ['lapak']];

		$this->load->view("global/modal_setting", $data);
	}

}
