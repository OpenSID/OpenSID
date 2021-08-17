<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Pembangunan
 *
 * donjo-app/controllers/Pembangunan_dokumentasi.php
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

class Pembangunan_dokumentasi extends Admin_Controller
{
	protected $table = 'pembangunan_ref_dokumentasi';

	public function __construct()
	{
		parent::__construct();

		$this->modul_ini = 220;

		$this->load->library('upload');
		$this->load->model('referensi_model');
		$this->load->model('pembangunan_model');
		$this->load->model('pembangunan_dokumentasi_model', 'model');
	}

	public function show($id = null)
	{
		$pembangunan = $this->pembangunan_model->find($id);
		$_SESSION['id_pembangunan'] = $id;

		if ($this->input->is_ajax_request())
		{
			$start = $this->input->post('start');
			$length = $this->input->post('length');
			$search = $this->input->post('search[value]');
			$order = $this->model::ORDER_ABLE[$this->input->post('order[0][column]')];
			$dir = $this->input->post('order[0][dir]');

			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'draw'            => $this->input->post('draw'),
					'recordsTotal'    => $this->model->get_data($id)->count_all_results(),
					'recordsFiltered' => $this->model->get_data($id, $search)->count_all_results(),
					'data'            => $this->model->get_data($id, $search)->order_by($order, $dir)->limit($length, $start)->get()->result(),
				]));
		}

		$this->render('pembangunan/dokumentasi/index', [
			'pembangunan' => $pembangunan,
		]);
	}

	public function form($id = '')
	{
		if ($id)
		{
			$id_pembangunan = $_SESSION['id_pembangunan'];
			$data['id_pembangunan'] = $_SESSION['id_pembangunan'];
			$data['main'] = $this->model->find($id);
			$data['persentase'] = $this->referensi_model->list_ref(STATUS_PEMBANGUNAN);
			$data['form_action'] = site_url("pembangunan_dokumentasi/update/$id/$id_pembangunan");
		}
		else
		{
			$id_pembangunan = $_SESSION['id_pembangunan'];
			$data['id_pembangunan'] = $_SESSION['id_pembangunan'];
			$data['main'] = NULL;
			$data['persentase'] = $this->referensi_model->list_ref(STATUS_PEMBANGUNAN);
			$data['form_action'] = site_url("pembangunan_dokumentasi/insert/$id_pembangunan");
		}

		$this->render('pembangunan/dokumentasi/form', $data);
	}

	public function insert($id_pembangunan = '')
	{
		$this->model->insert($id_pembangunan);
		redirect("pembangunan_dokumentasi/show/$id_pembangunan");
	}

	public function update($id = '', $id_pembangunan = '')
	{
		$this->model->update($id, $id_pembangunan);
		redirect("pembangunan_dokumentasi/show/$id_pembangunan");
	}

	public function delete($id_pembangunan, $id)
	{
		$this->model->delete($id);

		if ($this->db->affected_rows())
		{
			$this->session->success = 4;
		}
		else
		{
			$this->session->success = -4;
		}

		redirect("pembangunan_dokumentasi/show/{$id_pembangunan}");
	}

}
