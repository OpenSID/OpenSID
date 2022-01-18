<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * File ini:
 *
 * Controller di Modul Identitas Desa
 *
 * donjo-app/controllers/Identitas_desa.php
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

class Identitas_desa extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['wilayah_model', 'pamong_model']);
		$this->modul_ini = 200;
		$this->sub_modul_ini = 17;
	}

	public function index()
	{
		$data['main'] = $this->header['desa'];
		$data['desa'] = ucwords($this->setting->sebutan_desa);
		$data['kecamatan'] = ucwords($this->setting->sebutan_kecamatan);
		$data['kabupaten'] = ucwords($this->setting->sebutan_kabupaten);

		$this->render("$this->controller/index", $data);
	}

	public function form()
	{
		$this->redirect_hak_akses('u');
		$data['main'] = $this->header['desa'];
		$data['desa'] = ucwords($this->setting->sebutan_desa);
		$data['kecamatan'] = ucwords($this->setting->sebutan_kecamatan);
		$data['kabupaten'] = ucwords($this->setting->sebutan_kabupaten);
		$data['pamong'] = $this->pamong_model->list_data();
		$this->session->kades_lama = $data['main']['pamong_id'];

		if ($data['main'])
			$data['form_action'] = site_url("$this->controller/update/" . $data['main']['id']);
		else
			$data['form_action'] = site_url("$this->controller/insert");

		$this->render("$this->controller/form", $data);
	}

	public function insert()
	{
		$this->redirect_hak_akses('u');
		$this->config_model->insert();
		redirect("$this->controller");
	}

	public function update($id = 0)
	{
		$this->redirect_hak_akses('u');
		$this->config_model->update($id);
		redirect("$this->controller");
	}

	public function maps($tipe = 'kantor')
	{
		$data_desa = $this->header['desa'];
		$data['desa'] = $data_desa;
		$data['poly'] = ($tipe == 'wilayah') ? 'multi' : 'poly';
		$data['wil_ini'] = $data_desa;
		$data['wil_atas'] = $data_desa;
		$data['dusun_gis'] = $this->wilayah_model->list_dusun();
		$data['rw_gis'] = $this->wilayah_model->list_rw();
		$data['rt_gis'] = $this->wilayah_model->list_rt();
		$data['nama_wilayah'] = ucwords($this->setting->sebutan_desa . " " . $data_desa['nama_desa']);
		$data['wilayah'] = ucwords($this->setting->sebutan_desa . " " . $data_desa['nama_desa']);
		$data['breadcrumb'] = array(
			array('link' => site_url("$this->controller"), 'judul' => "Identitas " . ucwords($this->setting->sebutan_desa)),
		);

		$data['form_action'] = site_url("$this->controller/update_maps/$tipe");

		$this->render('sid/wilayah/maps_' . $tipe, $data);
	}

	public function update_maps($tipe = 'kantor')
	{
		$this->redirect_hak_akses('u');
		if ($tipe = 'kantor')
			$this->config_model->update_kantor();
		else
			$this->config_model->update_wilayah();

		redirect("$this->controller");
	}

	public function kosongkan($id = '')
	{
		$this->redirect_hak_akses('u');
		$this->config_model->kosongkan_path($id);
		redirect($_SERVER['HTTP_REFERER']);
	}
}
