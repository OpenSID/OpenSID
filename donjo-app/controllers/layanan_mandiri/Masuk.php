<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Masuk Layanan Mandiri
 *
 * donjo-app/controllers/layanan_mandiri/Masuk.php
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

class Masuk extends Web_Controller
{

	private $cek_anjungan;

	public function __construct()
	{
		parent::__construct();
		mandiri_timeout();
		$this->session->login_ektp = FALSE;
		$this->load->model(['config_model', 'anjungan_model', 'mandiri_model', 'theme_model']);
		$this->cek_anjungan = $this->anjungan_model->cek_anjungan();
		if ($this->setting->layanan_mandiri == 0 && ! $this->cek_anjungan) show_404();
	}

	public function index()
	{
		if ($this->session->mandiri == 1) redirect('layanan-mandiri');

		//Initialize Session ------------
		$this->session->unset_userdata('balik_ke');
		if ( ! isset($this->session->mandiri))
		{
			// Belum ada session variable
			$this->session->mandiri = 0;
			$this->session->mandiri_try = 4;
			$this->session->mandiri_wait = 0;
			$this->session->login_ektp = FALSE;
		}

		$data = [
			'header' => $this->config_model->get_data(),
			'latar_login_mandiri' => $this->theme_model->latar_login_mandiri(),
			'cek_anjungan' => $this->cek_anjungan,
			'form_action' => site_url('layanan-mandiri/cek')
		];

		$this->load->view('layanan_mandiri/masuk', $data);
	}

	public function cek()
	{
		$this->mandiri_model->siteman();
		redirect('layanan-mandiri');
	}

}
