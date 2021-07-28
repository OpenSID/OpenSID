<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *  File ini:
 *
 * Controller untuk modul Laporan APBDes
 *
 * donjo-app/controllers/laporan_apbdes.php
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

class Laporan_apbdes extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Laporan_apbdes_model', 'apbdes');
		$this->modul_ini = 201;
		$this->sub_modul_ini = 325;
	}

	public function index()
	{
		if ($this->input->is_ajax_request())
		{
			$start = $this->input->post('start');
			$length = $this->input->post('length');
			$search = $this->input->post('search[value]');
			$order = $this->apbdes::ORDER_ABLE_APBDES[$this->input->post('order[0][column]')];
			$dir = $this->input->post('order[0][dir]');
			$tahun = $this->input->post('filter-tahun');

			return $this->json_output(
				[
					'draw' => $this->input->post('draw'),
					'recordsTotal' => $this->apbdes->get_apbdes()->count_all_results(),
					'recordsFiltered' => $this->apbdes->get_apbdes($search, $tahun)->count_all_results(),
					'data' => $this->apbdes->get_apbdes($search, $tahun)->order_by($order, $dir)->limit($length, $start)->get()->result(),
				]
			);
		}

		$data['tahun'] = $this->apbdes->get_tahun();

		$this->render("keuangan/index", $data);
	}
	

	public function form(int $id = 0)
	{
		$this->redirect_hak_akses("u");

		if ($id)
		{
			$data['main'] = $this->apbdes->find($id) ?? show_404();
			$data['form_action'] = site_url("$this->controller/update/$id");
		}
		else
		{
			$data['main'] = NULL;
			$data['form_action'] = site_url("$this->controller/insert");
		}

		$data['tahun'] = $this->apbdes->get_tahun();

		$this->load->view("keuangan/modal_form", $data);
	}

	public function insert()
	{
		$this->redirect_hak_akses("u");
		$this->apbdes->insert();
		redirect($this->controller);
	}

	public function update(int $id = 0)
	{
		$this->redirect_hak_akses("u");
		$this->apbdes->update($id);
		redirect($this->controller);
	}

	public function delete(int $id = 0)
	{
		$this->redirect_hak_akses("h");
		$this->apbdes->delete($id);
		redirect($this->controller);
	}

	public function delete_all()
	{
		$this->redirect_hak_akses("h");
		$this->apbdes->delete_all();
		redirect($this->controller);
	}

	public function unduh(int $id = 0)
	{
		$nama_file = $this->apbdes->find($id)->nama_file;
		ambilBerkas($nama_file, $this->controller, NULL, LOKASI_DOKUMEN);	
	}

	public function kirim()
	{
		$this->redirect_hak_akses('u');

		foreach (glob(LOKASI_DOKUMEN . '*_opendk.zip') as $file) {
			if (file_exists($file)) {
				unlink($file);
				break;
			}
		}

		$desa_id = kode_wilayah($this->header['desa']['kode_desa']);
		$id = $this->input->post('id_cb');
		
		//Tambah/Ubah Data
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "{$this->setting->api_opendk_server}/api/v1/laporan-apbdes",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode(['desa_id' => $desa_id, 'laporan_apbdes' => $this->apbdes->opendk($id)]),
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json',
				'Content-Type: application/json',
				"Authorization: Bearer {$this->setting->api_opendk_key}",
			),
		));

		$response = json_decode(curl_exec($curl));
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ( ! curl_errno($curl) && $http_code !== 422)
		{
			// Ubah tgl kirim
			$this->apbdes->kirim($id);
		}

		curl_close($curl);
		$this->session->unset_userdata(['success', 'error_msg']);
		$this->session->set_flashdata("notif", $response);

		redirect($this->controller);
	}
}