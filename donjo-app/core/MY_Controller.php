<?php

/**
 * File ini:
 *
 * File ini controller utama yg mengatur controller lain
 *
 * donjo-app/core/MY_Controller.php
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	/*
	 * Common data
	 */
	public $user;
	public $settings;
	public $includes;
	public $current_uri;
	public $theme;
	public $template;
	public $error;

	/*
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model(['setting_model']);
		$this->setting_model->init();
	}

	/*
	 * Bersihkan session cluster wilayah
	 */
	public function clear_cluster_session()
	{
		$cluster_session = array('dusun', 'rw', 'rt');
		foreach ($cluster_session as $session)
		{
			$this->session->unset_userdata($session);
		}
	}

	public function json_output($parm, $header = 200)
	{
		$this->output
			->set_status_header($header)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($parm))
			->_display();
		exit();
	}

}

class Web_Controller extends MY_Controller {

	/*
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->controller = strtolower($this->router->fetch_class());
		// Gunakan tema klasik kalau setting tema kosong atau folder di desa/themes untuk tema pilihan tidak ada
		// if (empty($this->setting->web_theme) OR !is_dir(FCPATH.'desa/themes/'.$this->setting->web_theme))
		$theme = preg_replace("/desa\//","",strtolower($this->setting->web_theme)) ;
		$theme_folder = preg_match("/desa\//", strtolower($this->setting->web_theme)) ? "desa/themes" : "themes";
		if (empty($this->setting->web_theme) OR !is_dir(FCPATH.$theme_folder.'/'.$theme))
		{
			$this->theme = 'klasik';
			$this->theme_folder = 'themes';
		}
		else
		{
			$this->theme = $theme;
			$this->theme_folder = $theme_folder;
		}
		// Variabel untuk tema
		$this->template = "../../{$this->theme_folder}/{$this->theme}/template.php";
		$this->includes['folder_themes'] = '../../'.$this->theme_folder.'/'.$this->theme;
	}

	/*
	 * Jika file theme/view tidak ada, gunakan file klasik/view
	 * Supaya tidak semua layout atau partials harus diulangi untuk setiap tema
	 */
	public static function fallback_default($theme, $view)
	{
		$view = trim($view, '/');
		$theme_folder = self::get_instance()->theme_folder;
		$theme_view = "../../$theme_folder/$theme/$view";

		if (!is_file(APPPATH .'views/'. $theme_view))
		{
			$theme_view = "../../themes/klasik/$view";
		}

		return $theme_view;
	}

	/*
	 * Set Template
	 * sometime, we want to use different template for different page
	 * for example, 404 template, login template, full-width template, sidebar template, etc.
	 * so, use this function
	 * --------------------------------------
	 * @author	Arif Rahman Hakim
	 * @since	Version 3.1.0
	 * @access	public
	 * @param	string, template file name
	 * @return	chained object
	 */
	function set_template($template_file = 'template.php')
	{
		// make sure that $template_file has .php extension
		$template_file = substr( $template_file, -4 ) == '.php' ? $template_file : ( $template_file . ".php" );

		$template_file_path = FCPATH . $this->theme_folder . '/' . $this->theme . "/" . $template_file;
		if (is_file($template_file_path))
			$this->template = "../../{$this->theme_folder}/{$this->theme}/{$template_file}";
		else
			$this->template = '../../themes/klasik/' . $template_file;
	}

}

class Mandiri_Controller extends MY_Controller {

	public $header;
	public $cek_anjungan;
	public $is_login;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['config_model', 'anjungan_model']);

		$this->header = $this->config_model->get_data();
		$this->cek_anjungan = $this->anjungan_model->cek_anjungan();
		$this->is_login = $this->session->is_login;

		if ($this->setting->layanan_mandiri == 0 && ! $this->cek_anjungan) show_404();

		if ($this->session->mandiri != 1)
		{
			if ( ! $this->session->login_ektp)
			{
				redirect('layanan-mandiri/masuk');
			}
			else
			{
				redirect('layanan-mandiri/masuk_ektp');
			}
		}
	}

	public function render($view, Array $data = NULL)
	{
		$data['desa'] = $this->header;
		$data['cek_anjungan'] = $this->cek_anjungan;
		$data['konten'] = $view;
		$this->load->view('layanan_mandiri/template', $data);
	}

}

/*
 * Untuk API read-only, seperti Api_informasi_publik
 */
class Api_Controller extends MY_Controller {

	/*
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	protected function log_request()
	{
		$message = 'API Request '.$this->input->server('REQUEST_URI').' dari '.$this->input->ip_address();
		log_message('error', $message);
	}

}

class Admin_Controller extends MY_Controller {

	public $grup;
	public $CI = NULL;
	public $pengumuman = NULL;
	public $header;
	protected $nav = 'nav';
	protected $minsidebar = 0;
	public function __construct()
	{
		parent::__construct();
		$this->CI = CI_Controller::get_instance();
		$this->controller = strtolower($this->router->fetch_class());
		$this->load->model(['header_model', 'user_model', 'notif_model']);
		$this->grup	= $this->user_model->sesi_grup($_SESSION['sesi']);

		$this->load->model('modul_model');
		if ( ! $this->modul_model->modul_aktif($this->controller))
		{
			session_error("Fitur ini tidak aktif");
			redirect($_SERVER['HTTP_REFERER']);
		}
		if ( ! $this->user_model->hak_akses($this->grup, $this->controller, 'b'))
		{
			if (empty($this->grup))
			{
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
				redirect('siteman');
			}
			else
			{
				session_error("Anda tidak mempunyai akses pada fitur itu");
				unset($_SESSION['request_uri']);
				redirect('main');
			}
		}
		$this->cek_pengumuman();
		$this->header                           = $this->header_model->get_data();
		$this->header['notif_permohonan_surat'] = $this->notif_model->permohonan_surat_baru();
		$this->header['notif_inbox']            = $this->notif_model->inbox_baru();
		$this->header['notif_komentar']         = $this->notif_model->komentar_baru();
		$this->header['notif_langganan']        = $this->notif_model->status_langganan();
	}

	private function cek_pengumuman()
	{
		if ($this->grup == 1) // hanya utk user administrator
		{
			$notifikasi = $this->notif_model->get_semua_notif();
			foreach($notifikasi as $notif)
			{
				$this->pengumuman = $this->notif_model->notifikasi($notif);
				if ($notif['jenis'] == 'persetujuan') break;
			}
		}
	}

	// Untuk kasus di mana method controller berbeda hak_akses. Misalnya 'setting_qrcode' readonly, tetapi 'setting/analisis' boleh ubah
	protected function redirect_hak_akses_url($akses, $redirect = '', $controller = '')
	{
		$kembali = $_SERVER['HTTP_REFERER'];

		if (empty($controller))
			$controller = $this->controller;
		if ( ! $this->user_model->hak_akses_url($this->grup, $controller, $akses))
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			if (empty($this->grup)) redirect('siteman');
			empty($redirect) ? redirect($kembali) : redirect($redirect);
		}
	}

	protected function redirect_hak_akses($akses, $redirect = '', $controller = '')
	{
		$kembali = $_SERVER['HTTP_REFERER'];

		if (empty($controller))
			$controller = $this->controller;
		if ( ! $this->user_model->hak_akses($this->grup, $controller, $akses))
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			if (empty($this->grup)) redirect('siteman');
			empty($redirect) ? redirect($kembali) : redirect($redirect);
		}
	}

	// Untuk kasus di mana method controller berbeda hak_akses. Misalnya 'setting_qrcode' readonly, tetapi 'setting/analisis' boleh ubah
	public function cek_hak_akses_url($akses, $controller = '')
	{
		if (empty($controller))
			$controller = $this->controller;
		return $this->user_model->hak_akses_url($this->grup, $controller, $akses);
	}

	public function cek_hak_akses($akses, $controller = '')
	{
		if (empty($controller))
			$controller = $this->controller;
		return $this->user_model->hak_akses($this->grup, $controller, $akses);
	}

	public function render($view, Array $data = NULL)
	{
		$this->header['minsidebar'] = $this->get_minsidebar();
		$this->load->view('header', $this->header);
		$this->load->view('nav');
		$this->load->view($view, $data);
		$this->load->view('footer');
	}

	/**
	 * Get the value of minsidebar
	 */
	public function get_minsidebar()
	{
		return $this->minsidebar;
	}

	/**
	 * Set the value of minsidebar
	 *
	 * @return  self
	 */
	public function set_minsidebar($minsidebar)
	{
		$this->minsidebar = $minsidebar;
		$this->header['minsidebar'] = $this->get_minsidebar();

		return $this;
	}

}
