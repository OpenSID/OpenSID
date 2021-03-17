<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Layanan Mandiri
 *
 * donjo-app/controllers/Mandiri_login.php
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

class Mandiri_login extends CI_Controller
{
	private $cek_anjungan;

	public function __construct()
	{
		parent::__construct();
		mandiri_timeout();
		$this->load->model(['config_model', 'mandiri_model', 'anjungan_model']);

		$this->cek_anjungan = $this->anjungan_model->cek_anjungan();
	}

	public function index()
	{
		if (isset($this->session->mandiri) and 1 == $this->session->mandiri)
		{
			redirect('mandiri_web/mandiri/1/1');
		}
		unset($this->session->balik_ke);
		$data['header'] = $this->config_model->get_data();
		//Initialize Session ------------
		if (! isset($this->session->mandiri))
		{
			// Belum ada session variable
			$this->session->mandiri = 0;
			$this->session->mandiri_try = 4;
			$this->session->mandiri_wait = 0;
		}

		$this->session->success = 0;
		//-------------------------------

		$data['cek_anjungan'] = $this->cek_anjungan;

		$this->load->view('mandiri_login', $data);
	}

	public function auth()
	{
		if ($this->session->mandiri_wait != 1)
		{
			$this->mandiri_model->siteman();
		}

		if ($this->session->lg == 1)
		{
			redirect('mandiri_web/ganti_pin');
		}

		if ($this->session->mandiri == 1)
		{
			redirect('mandiri_web');
		}
		else
		{
			redirect('mandiri_login');
		}
	}

}
