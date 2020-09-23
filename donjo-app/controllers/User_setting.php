<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Pengaturan > Pengguna
 *
 * donjo-app/controllers/User_setting.php
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

class User_setting extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('config_model');
		$this->load->model('user_model');
	}

	public function index()
	{
		$id = $_SESSION['user'];
		$data['main'] = $this->user_model->get_user($id);
		$this->load->view('setting', $data);
	}

	public function update($id = '')
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('pass_baru', 'Kata Sandi Baru', 'required|callback_syarat_sandi');
		$this->form_validation->set_message('syarat_sandi','Harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil');

		if ($this->form_validation->run() !== true)
		{
			redirect($_SERVER['HTTP_REFERER']);
		}
		else
		{
			$this->user_model->update_setting($id);
			if ($this->session->success == -1)
			{
				redirect($_SERVER['HTTP_REFERER']);
			}
			else redirect("main");
		}
	}

	public function update_password($id = '')
	{
		$this->user_model->update_password($id);
		if ($this->session->success == -1)
		{
			redirect($_SERVER['HTTP_REFERER']);
		}
		else redirect("main");
	}

	// Kata sandi harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil
	public function syarat_sandi($str)
	{
		if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/', $str))
			return TRUE;
		else
			return FALSE;
	}

	public function change_pwd()
	{
		$id = $_SESSION['user'];
		$data['main'] = $this->user_model->get_user($id);
		$data['header'] = $this->config_model->get_data();
		$this->load->view('setting_pwd', $data);
	}
}
