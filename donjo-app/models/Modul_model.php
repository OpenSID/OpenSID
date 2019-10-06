<?php class Modul_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
    $this->load->model('user_model');
    // Terpaksa menjalankan migrasi, karena apabila kolom parent
    // belum ada, menu navigasi tidak bisa ditampilkan
    if (!$this->db->field_exists('parent', 'setting_modul'))
    {
      $this->load->model('database_model');
      $this->database_model->migrasi_db_cri();
    }
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
		$jml = $this->db->select("count('id') as jml")->
			where('parent', $modul_id)->
			get('setting_modul')->row()->jml;
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
	public function list_sub_modul($modul_id=1)
	{
		$data	= $this->db->select('*')
			->where('parent', $modul_id)
			->where('hidden <>', 2)
			->order_by('urut')->get('setting_modul')->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['modul'] = str_ireplace('[desa]', ucwords($this->setting->sebutan_desa), $data[$i]['modul']);
		}
		return $data;
	}

	public function autocomplete()
	{
		$sql = "SELECT modul FROM setting_modul WHERE hidden = 0
					UNION SELECT url FROM setting_modul WHERE  hidden = 0";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$outp = '';
		for ($i=0; $i<count($data); $i++)
		{
			$outp .= ",'" .$data[$i]['modul']. "'";
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';
		return $outp;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.modul LIKE '$kw' OR u.url LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.aktif = $kf";
			return $filter_sql;
		}
	}

	public function get_data($id=0)
	{
		$sql = "SELECT * FROM setting_modul WHERE id = ?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	 }

	public function update($id=0)
	{
		$data = $_POST;
		$data['modul'] = strip_tags($data['modul']);
		$data['ikon'] = strip_tags($data['ikon']);
		$aktif_lama = $this->db->select('aktif')
			->where('id', $id)
			->get('setting_modul')
			->row()->aktif;
		$this->db->where('id',$id);
		$outp = $this->db->update('setting_modul', $data);
		if ($data['aktif'] != $aktif_lama)
			$this->set_aktif_submodul($id, $data['aktif']);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
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

	public function delete($id='')
	{
		$sql = "DELETE FROM setting_modul WHERE id = ?";
		$outp = $this->db->query($sql, array($id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_all()
	{
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$sql = "DELETE FROM setting_modul WHERE id = ?";
				$outp = $this->db->query($sql, array($id));
			}
		}
		else $outp = false;

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
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
}
