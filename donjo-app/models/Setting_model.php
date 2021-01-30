<?php defined('BASEPATH') OR exit('No direct script access allowed');

define("EKSTENSI_WAJIB", serialize(array(
	"curl",
	"fileinfo",
	"gd",
	"iconv",
	"json",
	"mbstring",
	"mysqli",
	"mysqlnd",
	"tidy",
	"zip"
)));
define("VERSI_PHP_MINIMAL", '7.2.0');
define("VERSI_MYSQL_MINIMAL", '5.6.5');

class Setting_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
		$pre = array();
		$CI = &get_instance();

		if ($this->setting or ! $this->db->table_exists('setting_aplikasi'))
		{
			return;
		}

		if ($this->config->item("useDatabaseConfig"))
		{
			$pr = $this->db
				->where("kategori is null or kategori <> 'sistem' and kategori <> 'conf_web' and kategori <> 'setting_mandiri' ")
				->order_by('key')->get("setting_aplikasi")->result();
			foreach ($pr as $p)
			{
				$pre[addslashes($p->key)] = addslashes($p->value);
			}
			$setting_sistem = $this->db
				->where('kategori', 'sistem')
				->order_by('key')->get("setting_aplikasi")->result();
			foreach ($setting_sistem as $p)
			{
				$pre[addslashes($p->key)] = addslashes($p->value);
			}
			$setting_web = $this->db
				->where('kategori', 'conf_web')
				->order_by('key')->get("setting_aplikasi")->result();
			foreach ($setting_web as $p)
			{
				$pre[addslashes($p->key)] = addslashes($p->value);
			}
			$setting_mandiri = $this->db
				->where('kategori', 'setting_mandiri')
				->order_by('key')->get("setting_aplikasi")->result();
			foreach ($setting_mandiri as $p)
			{
				$pre[addslashes($p->key)] = addslashes($p->value);
			}
			$setting_bagan = $this->db
				->where('kategori', 'conf_bagan')
				->order_by('key')->get("setting_aplikasi")->result();
			foreach ($setting_bagan as $p)
			{
				$pre[addslashes($p->key)] = addslashes($p->value);
			}
		}
		else
		{
			$pre = (object) $CI->config->config;
		}
		$CI->setting = (object) $pre;
		$CI->list_setting = $pr; // Untuk tampilan daftar setting
		$CI->list_setting_web = $setting_web; // Untuk tampilan daftar setting web
		$CI->list_setting_mandiri = $setting_mandiri; // Untuk tampilan daftar setting layanan mandiri
		$CI->list_setting_bagan = $setting_bagan; // Untuk tampilan bagan
		$this->apply_setting();
	}

	// Setting untuk PHP
	private function apply_setting()
	{
		//  https://stackoverflow.com/questions/16765158/date-it-is-not-safe-to-rely-on-the-systems-timezone-settings
		date_default_timezone_set($this->setting->timezone);//ganti ke timezone lokal
		// Ambil google api key dari desa/config/config.php kalau tidak ada di database
		if (empty($this->setting->google_key))
		{
			$this->setting->google_key = config_item('google_key');
		}
		// Ambil token tracksid dari desa/config/config.php kalau tidak ada di database
		if (empty($this->setting->token_opensid))
		{
			$this->setting->token_opensid = config_item('token_opensid');
		}
		// Ambil dev_tracker dari desa/config/config.php kalau tidak ada di database
		$this->setting->tracker = "https://pantau.opensid.my.id";
		if (empty($this->setting->dev_tracker))
		{
			$this->setting->dev_tracker = config_item('dev_tracker');
		}
		$this->setting->user_admin = config_item('user_admin');
		// Kalau folder tema ubahan tidak ditemukan, ganti dengan tema default
		$pos = strpos($this->setting->web_theme, 'desa/');
		if ($pos !== false)
		{
			$folder = FCPATH . '/desa/themes/' . substr($this->setting->web_theme, $pos + strlen('desa/'));
			if (!file_exists($folder))
			{
				$this->setting->web_theme = "default";
			}
		}
		$this->setting->demo_mode = config_item('demo_mode');
		$this->load->model('database_model');
		$this->database_model->cek_migrasi();
	}

	public function update_setting($data)
	{
		foreach ($data as $key => $value)
		{
			// Update setting yang diubah
			if ($this->setting->$key != $value)
			{
				$value = strip_tags($value);
				$this->update($key, $value);
				$this->setting->$key = $value;
				if ($key == 'enable_track') $this->notifikasi_tracker();
			}
		}
		$this->apply_setting();
	}

	private function notifikasi_tracker()
	{
		if ($this->setting->enable_track == 0)
		{
			// Notifikasi tracker dimatikan
			$notif = [
				'updated_at' => date("Y-m-d H:i:s"),
				'tgl_berikutnya' => date("Y-m-d H:i:s"),
				'aktif' => 1
			];
		}
		else
		{
			// Matikan notifikasi tracker yg sdh aktif
			$notif = [
				'updated_at' => date("Y-m-d H:i:s"),
				'aktif' => 0
			];
		}
		$this->db->where('kode', 'tracking_off')->update('notifikasi', $notif);
	}

	public function update($key = 'enable_track', $value = 1)
	{
		$this->session->success = 1;

		$outp = $this->db->where('key', $key)->update('setting_aplikasi', ['key' => $key, 'value' => $value]);

		if (!$outp) $this->session->success = -1;
	}

	public function aktifkan_tracking()
	{
		$outp = $this->db->where('key', 'enable_track')->update('setting_aplikasi', ['value' => 1]);
		status_sukses($outp);
	}


	public function update_slider()
	{
		$_SESSION['success'] = 1;
		$this->setting->sumber_gambar_slider = $this->input->post('pilihan_sumber');
		$outp = $this->db->where('key','sumber_gambar_slider')->update('setting_aplikasi', array('value'=>$this->input->post('pilihan_sumber')));
		if (!$outp) $_SESSION['success'] = -1;
	}

	/*
		Input post:
		- jenis_server dan server_mana menentukan setting penggunaan_server
		- offline_mode dan offline_mode_saja menentukan setting offline_mode
	*/
	public function update_penggunaan_server()
	{
		$_SESSION['success'] = 1;
		$mode = $this->input->post('offline_mode_saja');
		$this->setting->offline_mode = ($mode === '0' or $mode) ? $mode : $this->input->post('offline_mode');
		$out1 = $this->db->where('key','offline_mode')->update('setting_aplikasi', array('value'=>$this->setting->offline_mode));
		$penggunaan_server = $this->input->post('server_mana') ?: $this->input->post('jenis_server');
		$this->setting->penggunaan_server = $penggunaan_server;
		$out2 = $this->db->where('key','penggunaan_server')->update('setting_aplikasi', array('value'=>$penggunaan_server));
		if (!$out1 or !$out2) $_SESSION['success'] = -1;
	}

	public function load_options()
	{
		foreach ($this->list_setting as $i => $set)
		{
			if (in_array($set->jenis, array('option', 'option-value', 'option-kode')))
			{
				$this->list_setting[$i]->options = $this->get_options($set->id);
			}
		}
	}

	private function get_options($id)
	{
		$rows = $this->db->select('id, kode, value')
		                 ->where('id_setting', $id)
		                 ->get('setting_aplikasi_options')
		                 ->result();
		return $rows;
	}

	public function cek_ekstensi()
	{
		$e = get_loaded_extensions();
		usort($e, 'strcasecmp');
		$ekstensi = array_flip($e);
		$e = unserialize(EKSTENSI_WAJIB);
		usort($e, 'strcasecmp');
		$ekstensi_wajib = array_flip($e);
		$lengkap = true;
		foreach ($ekstensi_wajib as $key => $value)
		{
			$ekstensi_wajib[$key] = isset($ekstensi[$key]);
			$lengkap = $lengkap && $ekstensi_wajib[$key];
		}
		$data['lengkap'] = $lengkap;
		$data['ekstensi'] = $ekstensi_wajib;
		return $data;
	}

	public function cek_php()
	{
		$data['versi'] = phpversion();
		$data['versi_minimal'] = VERSI_PHP_MINIMAL;
		$data['sudah_ok'] = version_compare(phpversion(), VERSI_PHP_MINIMAL) > 0;
		return $data;
	}

	public function cek_mysql()
	{
		$data['versi'] = $this->db->query('SELECT VERSION() AS version')
			->row()->version;
		$data['versi_minimal'] = VERSI_MYSQL_MINIMAL;
		$data['sudah_ok'] = version_compare($data['versi'], VERSI_MYSQL_MINIMAL) > 0;
		return $data;
	}
}
