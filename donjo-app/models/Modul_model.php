<?php class Modul_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	function list_data()
	{
		$sql = "SELECT u.* FROM setting_modul u WHERE hidden = 0 AND parent = 0 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= ' ORDER BY urut';
		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['submodul'] = $this->list_sub_modul($data[$i]['id']);
		}

		return $data;
	}

	// Menampilkan menu dan sub menu halaman pengguna login berdasarkan daftar modul dan sub modul yang aktif.
	public function list_aktif()
	{
		if (empty($_SESSION['grup'])) return array();
		$aktif = array();
		$data = $this->db->where('aktif', 1)->where('parent', 0)
			->order_by('urut')
			->get('setting_modul')->result_array();
		for ($i=0; $i<count($data); $i++)
		{
			if ($this->ada_sub_modul($data[$i]['id']))
			{
				$data[$i]['modul'] = str_ireplace('[desa]', ucwords($this->setting->sebutan_desa), $data[$i]['modul']);
				$data[$i]['submodul'] = $this->list_sub_modul_aktif($data[$i]['id']);
				// Kelompok submenu yg kosong tidak dimasukkan
				if (!empty($data[$i]['submodul']) or !empty($data[$i]['url']))
					$aktif[] = $data[$i];
			}
			else
			{
				// Modul yang tidak boleh diakses tidak dimasukkan
				if ($this->user_model->hak_akses($_SESSION['grup'], $data[$i]['url'], 'b'))
				{
					$data[$i]['modul'] = str_ireplace('[desa]', ucwords($this->setting->sebutan_desa), $data[$i]['modul']);
					$aktif[] = $data[$i];
				}
			}
		}

		return $aktif;
	}

	private function ada_sub_modul($modul_id)
	{
		$jml = $this->db
			->select("count('id') as jml")
			->where('parent', $modul_id)
			->get('setting_modul')
			->row()->jml;

		return $jml > 0;
	}

	private function list_sub_modul_aktif($modul_id)
	{
		$this->db->where('aktif', 1);
		$data	= $this->list_sub_modul($modul_id);
		$aktif = array();
		foreach ($data as $sub_modul)
		{
			// Modul yang tidak boleh diakses tidak dimasukkan
			if ($this->user_model->hak_akses($_SESSION['grup'], $sub_modul['url'], 'b'))
				$aktif[] = $sub_modul;
		}

		return $aktif;
	}

	// Menampilkan tabel sub modul
	public function list_sub_modul($modul_id)
	{
		$data	= $this->db->select('*')
			->where('parent', $modul_id)
			->where('hidden <>', 2)
			->order_by('urut')
			->get('setting_modul')
			->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['modul'] = str_ireplace('[desa]', ucwords($this->setting->sebutan_desa), $data[$i]['modul']);
		}

		return $data;
	}

	public function autocomplete()
	{
		$status = $this->session->status;

		if ($status != '')
			$this->db->where('aktif', $status);

		$data = $this->db->select('modul')
			->where('hidden', 0)
			->where('parent', 0)
			->order_by('modul')
			->get('setting_modul')
			->result_array();

		return autocomplete_data_ke_str($data);
	}

	private function search_sql()
	{
		$cari = $this->session->cari;

		if (isset($cari))
		{
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.modul LIKE '$kw' OR u.url LIKE '$kw')";

			return $search_sql;
		}
	}

	private function filter_sql()
	{
		$status = $this->session->status;

		if (isset($status))
		{
			$filter_sql = " AND u.aktif = $status";

			return $filter_sql;
		}
	}

	public function get_data($id)
	{
		$data = $this->db->get_where('setting_modul', ['id' => $id])->row_array();

		return $data;
	 }

	public function update($id)
	{
		$data = $this->input->post();
		$data['modul'] = strip_tags($data['modul']);
		$data['ikon'] = strip_tags($data['ikon']);

		$outp = $this->db->where('id', $id)->update('setting_modul', $data);
		$this->lock($id, $data['aktif']);

		status_sukses($outp); //Tampilkan Pesan
	}

	private function set_aktif_submodul($id, $aktif)
	{
		$submodul = $this->db->select('id')->where('parent', $id)->get('setting_modul')->result_array();
		$list_submodul = array_column($submodul, 'id');
		if (empty($list_submodul)) return;

		foreach ($submodul as $modul)
		{
			$sub = $this->db->select('id')->where('parent', $modul['id'])->get('setting_modul')->result_array();
			$list_submodul = array_merge($list_submodul, array_column($sub, 'id'));
		}
		$list_id = implode(",", $list_submodul);
		$this->db->where("id IN (" . $list_id . ")")->update('setting_modul', array('aktif' => $aktif));
	}

	/*
		Setting modul yang diaktifkan sesuai dengan setting penggunaan_server,
		dan setting online_mode

		penggunaan_server:
		1 - offline saja di kantor desa
		2 - online saja di hosting
		3 - [lebih spesifik di 5 dan 6]
		4 - offline dan online di kantor desa
		5 - offline di kantor desa, dan ada online di hosting
		6 - online di hosting, dan ada offline di kantor desa

		offline_mode:
		0 - web bisa diakses publik
		1 - web hanya bisa diakses petugas web
		2 - web non-aktif sama sekali
	*/
	public function default_server()
	{
		switch ($this->setting->penggunaan_server) {
			case '1':
			case '5':
				$this->db->update('setting_modul', array('aktif' => 1));
				// Kalau web tidak diaktifkan sama sekali, non-aktifkan modul Admin Web
				if ($this->setting->offline_mode == 2)
				{
					$modul_web = 13;
					$this->db->where('id', $modul_web)
						->update('setting_modul', array('aktif' => 0));
					$this->set_aktif_submodul($modul_web, 0);
				}
				break;

			case '6':
				// Online digunakan hanya untuk publikasi web; admin penduduk dan lain-lain
				// dilakukan offline di kantor desa. Yaitu, hanya modul Admin Web yang aktif
				// Kecuali Pengaturan selalu aktif
					$modul_pengaturan = 11;
					$this->db->where('id <>', $modul_pengaturan)
						->where('parent <>', $modul_pengaturan)
						->update('setting_modul', array('aktif' => 0));
					$modul_web = 13;
					$this->db->where('id', $modul_web)
						->update('setting_modul', array('aktif' => 1));
					$this->set_aktif_submodul($modul_web, 1);
				break;

			default:
				# semua modul aktif
				$this->db->update('setting_modul', array('aktif' => 1));
				break;
		}
	}

	public function modul_aktif($controller)
	{
		$selalu_aktif = array('hom_sid', 'user_setting', 'notif');
		if (in_array($controller, $selalu_aktif)) return true;

		// Periksa apakah modulnya aktif atau tidak
		$aktif = $this->db->select('url')
			->where('aktif', 1)
			->get('setting_modul')
			->result_array();
		foreach ($aktif as $key => $modul)
		{
			// url ada yg berbentuk 'modul/clear'
			$aktif[$key] = explode('/', $modul['url'])[0];
		}

		return in_array($controller, $aktif);
	}

	/**
	 * @param $id id
	 * @param $val status : 1 = Unlock, 2 = Lock
	 */
	public function lock($id, $val)
	{
		$this->db
			->where('id', $id)
			->or_where('parent', $id)
			->update('setting_modul', ['aktif' => $val]);
	}

	public function list_icon()
	{
		$list_icon = array();

		$file = FCPATH.'assets/fonts/fontawesome.txt';

		if (file_exists($file))
		{
			$list_icon = file_get_contents($file);
			$list_icon = explode('.', $list_icon);
			$list_icon = array_map(function ($a) { return explode(':', $a)[0]; }, $list_icon);
			return $list_icon;
		}

		return FALSE;
	}

}
