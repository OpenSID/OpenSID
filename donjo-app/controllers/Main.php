<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Dashboard Admin
 *
 * donjo-app/controllers/Main.php
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

class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('config_model');
		$this->load->model('pamong_model');
		$this->load->model('track_model');
	}

	public function maintenance_mode()
	{
		if (isset($_SESSION['siteman']) AND $_SESSION['siteman'] == 1)
			redirect('main');
		$data['main'] = $this->config_model->get_data();
		$data['pamong_kades'] = $this->pamong_model->get_ttd();
		if (file_exists(FCPATH.'desa/offline_mode.php'))
			$this->load->view('../../desa/offline_mode', $data);
		else
			$this->load->view('offline_mode', $data);
	}

	public function index()
	{
		if (isset($_SESSION['siteman']) AND $_SESSION['siteman'] == 1)
		{
			$this->track_model->track_desa('main');
			$this->load->model('user_model');
			$grup = $this->user_model->sesi_grup($_SESSION['sesi']);
			switch ($grup)
			{
				case 1 : redirect('hom_sid'); break;
				case 2 : redirect('hom_sid'); break;
				case 3 : redirect('web'); break;
				case 4 : redirect('web'); break;
				case 5 : redirect('covid19'); break;
				default : redirect('siteman');
			}
		}
		else if ($this->setting->offline_mode > 0)
		{
			// Jika website hanya bisa diakses user, maka harus login dulu
			redirect('siteman');
		}
		else
		{
			redirect();
		}
	}
}
