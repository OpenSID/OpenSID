<?php
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

defined('BASEPATH') OR exit('No direct script access allowed');

class Identitas_desa extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['config_model', 'wilayah_model', 'provinsi_model']);

		$this->modul_ini = 200;
		$this->sub_modul_ini = 17;
	}

	public function index()
	{
		$data['main'] = $this->config_model->get_data();
		$data['desa'] = ucwords($this->setting->sebutan_desa);
		$data['kecamatan'] = ucwords($this->setting->sebutan_kecamatan);
		$data['kabupaten'] = ucwords($this->setting->sebutan_kabupaten);

		$this->render('identitas_desa/index', $data);
	}

	public function form()
	{
		$data['main'] = $this->config_model->get_data();
		$data['desa'] = ucwords($this->setting->sebutan_desa);
		$data['kecamatan'] = ucwords($this->setting->sebutan_kecamatan);
		$data['kabupaten'] = ucwords($this->setting->sebutan_kabupaten);
		$data['list_provinsi'] = $this->provinsi_model->list_data();

		// Buat row data desa di form apabila belum ada data desa
		if ($data['main'])
			$data['form_action'] = site_url('identitas_desa/update/' . $data['main']['id']);
		else
			$data['form_action'] = site_url('identitas_desa/insert');

		$this->render('identitas_desa/form', $data);
	}

	public function insert()
	{
		$this->config_model->insert();
		redirect('identitas_desa');
	}

	public function update($id = 0)
	{
		$this->config_model->update($id);
		redirect('identitas_desa');
	}

	public function maps($tipe = 'kantor')
	{
		$data_desa = $this->config_model->get_data();
		$data['desa'] = $this->config_model->get_data();
		$data['wil_ini'] = $data_desa;
		$data['wil_atas']['lat'] = -1.0546279422758742;
		$data['wil_atas']['lng'] = 116.71875000000001;
		$data['wil_atas']['zoom'] = 4;
		$data['wil_atas'] = $this->config_model->get_data();
		$data['dusun_gis'] = $this->wilayah_model->list_dusun();
		$data['rw_gis'] = $this->wilayah_model->list_rw_gis();
		$data['rt_gis'] = $this->wilayah_model->list_rt_gis();
		$data['nama_wilayah'] = ucwords($this->setting->sebutan_desa . " " . $data_desa['nama_desa']);
		$data['wilayah'] = ucwords($this->setting->sebutan_desa . " " . $data_desa['nama_desa']);
		$data['breadcrumb'] = array(
			array('link' => site_url("identitas_desa"), 'judul' => "Identitas " . ucwords($this->setting->sebutan_desa)),
		);

		$data['form_action'] = site_url("identitas_desa/update_maps/$tipe");

		$this->render('sid/wilayah/maps_' . $tipe, $data);
	}

	public function update_maps($tipe = 'kantor')
	{
		if ($tipe = 'kantor')
			$this->config_model->update_kantor();
		else
			$this->config_model->update_wilayah();

		redirect("identitas_desa");
	}
}
