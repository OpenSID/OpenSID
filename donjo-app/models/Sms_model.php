<?php class Sms_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('SenderNumber', 'inbox');
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' . $kw . '%';
			$search_sql = " AND (u.SenderNumber LIKE '$kw' OR u.TextDecoded LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql = " AND u.Class = $kf";
			return $filter_sql;
		}
	}

	public function paging($p = 1, $o = 0)
	{
		$sql = "SELECT COUNT(*) AS jml " . $this->list_data_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$sql = " FROM inbox u
			LEFT JOIN kontak k on u.SenderNumber = k.no_hp
			LEFT JOIN tweb_penduduk p on k.id_pend = p.id WHERE 1";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	function list_data($o = 0, $offset = 0, $limit = 500)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1:
				$order_sql = ' ORDER BY u.SenderNumber';
				break;
			case 2:
				$order_sql = ' ORDER BY u.SenderNumber DESC';
				break;
			case 3:
				$order_sql = ' ORDER BY u.Class';
				break;
			case 4:
				$order_sql = ' ORDER BY u.Class DESC';
				break;
			case 5:
				$order_sql = ' ORDER BY u.ReceivingDateTime';
				break;
			case 6:
				$order_sql = ' ORDER BY u.ReceivingDateTime DESC';
				break;
			default:
				$order_sql = ' ORDER BY u.ReceivingDateTime DESC';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' . $offset . ',' . $limit;

		//Main Query
		$sql = "SELECT p.nama, u.* " . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i = 0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	public function insert_autoreply()
	{
		$data  = $_POST;
		$sql = "DELETE FROM setting_sms";
		$query = $this->db->query($sql);
		$outp = $this->db->insert('setting_sms', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_autoreply()
	{
		$sql = "SELECT * FROM setting_sms LIMIT 1 ";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}

	public function paging_terkirim($p = 1, $o = 0)
	{
		$sql = "SELECT count(*) as jml " . $this->list_data_terkirim_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_terkirim_sql()
	{
		$sql = " FROM sentitems u
			LEFT JOIN kontak k on u.DestinationNumber = k.no_hp
			LEFT JOIN tweb_penduduk p on k.id_pend = p.id
			WHERE 1";
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data_terkirim($o = 0, $offset = 0, $limit = 500)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1:
				$order_sql = ' ORDER BY u.DestinationNumber';
				break;
			case 2:
				$order_sql = ' ORDER BY u.DestinationNumber DESC';
				break;
			case 3:
				$order_sql = ' ORDER BY u.Class';
				break;
			case 4:
				$order_sql = ' ORDER BY u.Class DESC';
				break;
			case 5:
				$order_sql = ' ORDER BY u.SendingDateTime';
				break;
			case 6:
				$order_sql = ' ORDER BY u.SendingDateTime DESC';
				break;
			default:
				$order_sql = ' ORDER BY u.SendingDateTime DESC';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' . $offset . ',' . $limit;

		//Main Query
		$sql = "SELECT p.nama, u.* " . $this->list_data_terkirim_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i = 0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	public function paging_tertunda($p = 1, $o = 0)
	{
		$sql = "SELECT count(*) as jml " . $this->list_data_tertunda_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_tertunda_sql()
	{
		$sql = " FROM outbox u
			LEFT JOIN kontak k on u.DestinationNumber = k.no_hp
			LEFT JOIN tweb_penduduk p on k.id_pend = p.id
			WHERE 1";
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data_tertunda($o = 0, $offset = 0, $limit = 500)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1:
				$order_sql = ' ORDER BY u.DestinationNumber';
				break;
			case 2:
				$order_sql = ' ORDER BY u.DestinationNumber DESC';
				break;
			case 3:
				$order_sql = ' ORDER BY u.Class';
				break;
			case 4:
				$order_sql = ' ORDER BY u.Class DESC';
				break;
			case 5:
				$order_sql = ' ORDER BY u.SendingDateTime';
				break;
			case 6:
				$order_sql = ' ORDER BY u.SendingDateTime DESC';
				break;
			default:
				$order_sql = ' ORDER BY u.SendingDateTime DESC';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' . $offset . ',' . $limit;

		//Main Query
		$sql = "SELECT p.nama, u.* " . $this->list_data_tertunda_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i = 0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	public function insert()
	{
		$data = $_POST;
		$outp = $this->db->insert('outbox', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update($id = 0)
	{
		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($Class = 0, $ID = '')
	{
		if ($Class == 2)
		{
			$sql = "DELETE FROM sentitems WHERE ID = ?";
		}
		elseif ($Class == 1)
		{
			$sql = "DELETE FROM inbox WHERE ID = ?";
		}
		else
		{
			$sql = "DELETE FROM outbox WHERE ID = ?";
		}
		$outp = $this->db->query($sql, array($ID));

		if ($outp)
			$_SESSION['success'] = 1;
		else
			$_SESSION['success'] = -1;
	}

	public function delete_all($Class = 0)
	{
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $ID)
			{
				if ($Class == 2)
				{
					$sql = "DELETE FROM sentitems WHERE ID = ?";
				}
				elseif ($Class == 1)
				{
					$sql = "DELETE FROM inbox WHERE ID = ?";
				}
				else
				{
					$sql = "DELETE FROM outbox WHERE ID = ?";
				}
				$outp = $this->db->query($sql, array($ID));
			}
		}
		else
			$outp = false;

		if ($outp)
			$_SESSION['success'] = 1;
		else
			$_SESSION['success'] = -1;
	}

	public function get_sms($Class = 0, $ID = 0)
	{
		if ($Class == 2)
		{
			$sql = "SELECT * FROM sentitems WHERE ID = ?";
		}
		elseif ($Class == 1)
		{
			$sql = "SELECT SenderNumber AS DestinationNumber,TextDecoded FROM inbox WHERE ID = ?";
		}
		else
		{
			$sql = "SELECT * FROM outbox WHERE ID = ?";
		}
		$query = $this->db->query($sql, array($ID));
		$data = $query->row_array();

		return $data;
	}

	public function list_nama()
	{
		$sql = "SELECT * FROM tweb_penduduk WHERE id NOT IN (SELECT id_pend FROM kontak)";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		return $data;
	}

	public function list_kontak()
	{
		$sql = "SELECT * FROM daftar_kontak ";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		return $data;
	}

	public function get_kontak($id = 0)
	{
		$sql = "SELECT * FROM daftar_kontak WHERE id_kontak = '$id'";

		$query = $this->db->query($sql);
		$data  = $query->row_array();
		return $data;
	}

	public function get_grup($id = 0)
	{
		$sql = "SELECT * FROM daftar_grup WHERE id_grup = '$id' ";

		$query = $this->db->query($sql);
		$data  = $query->row_array();
		return $data;
	}

	public function update_setting($ID = 0)
	{
		$password = md5($this->input->post('pass_lama'));
		$pass_baru = $this->input->post('pass_baru');
		$pass_baru1 = $this->input->post('pass_baru1');
		$nama = $this->input->post('nama');

		$sql = "SELECT password,id_grup,session FROM user WHERE id=?";
		$query = $this->db->query($sql, array($id));
		$row = $query->row();

		if ($password == $row->password)
		{
			if ($pass_baru == $pass_baru1)
			{
				$pass_baru = md5($pass_baru);
				$sql = "UPDATE user SET password = ?, nama = ? WHERE id = ?";
				$outp = $this->db->query($sql, array(
					$pass_baru,
					$nama,
					$id
				));
			}
		}

		status_sukses($outp); //Tampilkan Pesan
	}

	public function list_grup()
	{
		$sql = "SELECT * FROM user_grup";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function list_grup_kontak()
	{
		$sql = "SELECT * FROM daftar_grup";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	private function sex_sql()
	{
		if (isset($_SESSION['sex1']))
		{
			$kf = $_SESSION['sex1'];
			$sex_sql = " AND u.sex = $kf";
			return $sex_sql;
		}
	}

	private function dusun_sql()
	{
		if (isset($_SESSION['dusun1']))
		{
			$kf = $_SESSION['dusun1'];
			$dusun_sql = " AND a.dusun = '$kf'";
			return $dusun_sql;
		}
	}

	private function rw_sql()
	{
		if (isset($_SESSION['rw1']))
		{
			$kf = $_SESSION['rw1'];
			$rw_sql = " AND a.rw = '$kf'";
			return $rw_sql;
		}
	}

	private function rt_sql()
	{
		if (isset($_SESSION['rt1']))
		{
			$kf = $_SESSION['rt1'];
			$rt_sql = " AND a.rt = '$kf'";
			return $rt_sql;
		}
	}

	private function agama_sql()
	{
		if (isset($_SESSION['agama1']))
		{
			$kf = $_SESSION['agama1'];
			$agama_sql = " AND u.agama_id = $kf";
			return $agama_sql;
		}
	}

	private function pekerjaan_sql()
	{
		if (isset($_SESSION['pekerjaan1']))
		{
			$kf = $_SESSION['pekerjaan1'];
			$pekerjaan_sql = " AND u.pekerjaan_id = $kf";
			return $pekerjaan_sql;
		}
	}

	private function statuskawin_sql()
	{
		if (isset($_SESSION['status1']))
		{
			$kf = $_SESSION['status1'];
			$statuskawin_sql = " AND u.status_kawin = $kf";
			return $statuskawin_sql;
		}
	}

	private function pendidikan_sql()
	{
		if (isset($_SESSION['pendidikan1']))
		{
			$kf = $_SESSION['pendidikan1'];
			$pendidikan_sql = " AND u.pendidikan_id = $kf";
			return $pendidikan_sql;
		}
	}

	private function status_penduduk_sql()
	{
		if (isset($_SESSION['status_penduduk1']))
		{
			$kf = $_SESSION['status_penduduk1'];
			$status_penduduk_sql = " AND u.status = $kf";
			return $status_penduduk_sql;
		}
	}

	private function grup_sql()
	{
		if (isset($_SESSION['grup1']))
		{
			$kf = $_SESSION['grup1'];
			$grup_sql = " AND k.id IN (SELECT id_kontak FROM kontak_grup WHERE nama_grup = '$kf')";
			return $grup_sql;
		}
	}

	private function umur_max_sql()
	{
		if (isset($_SESSION['umur_max1']))
		{
			$kf = $_SESSION['umur_max1'];
			$umur_max_sql = " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) <= $kf";
			return $umur_max_sql;
		}
	}

	private function umur_min_sql()
	{
		if (isset($_SESSION['umur_min1']))
		{
			$kf = $_SESSION['umur_min1'];
			$umur_min_sql = " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= $kf";
			return $umur_min_sql;
		}
	}

	public function send_broadcast($o = 0)
	{
		$isi = $_SESSION['TextDecoded1'];
		//Main Query
		$sql = "SELECT no_hp
			FROM kontak k
			LEFT JOIN tweb_penduduk u on k.id_pend = u.id
			LEFT JOIN tweb_wil_clusterdesa a on u.id_cluster = a.id
			WHERE 1 ";

		$sql .= $this->sex_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= $this->agama_sql();
		$sql .= $this->umur_min_sql();
		$sql .= $this->umur_max_sql();
		$sql .= $this->pekerjaan_sql();
		$sql .= $this->statuskawin_sql();
		$sql .= $this->pendidikan_sql();
		$sql .= $this->status_penduduk_sql();
		$sql .= $this->grup_sql();

		$query = $this->db->query($sql);
		$data  = $query->result_array();

		foreach ($data as $hsl)
		{
			$no = $hsl['no_hp'];
			$sqlku = "INSERT INTO outbox(DestinationNumber,TextDecoded) values('$no','$isi')";
			$query = $this->db->query($sqlku);
		}
	}

	public function paging_kontak($p = 1, $o = 0)
	{
		$sql = "SELECT COUNT(*) as jml " . $this->list_data_kontak_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_kontak_sql()
	{
		$sql = " FROM daftar_kontak WHERE 1 ";
		$sql .= $this->search_kontak_sql();
		return $sql;
	}

	public function list_data_kontak($o = 0, $offset = 0, $limit = 500)
	{
		//Paging SQL
		$paging_sql = ' LIMIT ' . $offset . ',' . $limit;

		//Main Query
		$sql = "SELECT * " . $this->list_data_kontak_sql();
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data  = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i = 0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	private function search_kontak_sql()
	{
		if (isset($_SESSION['cari_kontak']))
		{
			$cari = $_SESSION['cari_kontak'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' . $kw . '%';
			$search_kontak_sql = " AND nama LIKE '$kw' OR no_hp LIKE '$kw' ";
			return $search_kontak_sql;
		}
	}

	public function insert_kontak()
	{
		$data = $_POST;
		$outp = $this->db->insert('kontak', $data);
	}

	public function update_kontak()
	{
		$data = $_POST;
		$outp = $this->db->where('id_kontak', $data['id_kontak'])->update('kontak', array(
			'no_hp' => $data['no_hp']
		));
	}

	public function delete_kontak($id = 0)
	{
		$this->db->query("DELETE FROM kontak WHERE id_kontak=$id");
	}

	public function delete_all_kontak()
	{
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			$list_id = implode(",", $id_cb);
			$this->db->query("DELETE FROM kontak WHERE id_kontak IN (" . $list_id . ")");
			$outp = true;
		}
		else
			$outp = false;

		status_sukses($outp); //Tampilkan Pesan
	}

	public function paging_grup($p = 1, $o = 0)
	{
		$sql = "SELECT COUNT(*) as jml " . $this->list_data_grup_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_grup_sql()
	{
		$sql = " FROM daftar_grup TB WHERE 1 ";
		$sql .= $this->search_grup_sql();
		return $sql;
	}

	public function list_data_grup($o = 0, $offset = 0, $limit = 500)
	{
		//Paging SQL
		$paging_sql = ' LIMIT ' . $offset . ',' . $limit;

		//Main Query
		$sql = "SELECT TB.* " . $this->list_data_grup_sql();
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i = 0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	public function insert_grup()
	{
		$data['nama_grup'] = $_POST['nama_grup'];
		$outp = $this->db->insert('kontak_grup', $data);
	}

	public function update_grup()
	{
		$nama_baru = $_POST['nama_grup'];
		$sql = "UPDATE kontak_grup SET nama_grup = '$nama_baru' WHERE id_grup = $_POST[id_grup]";
		$query = $this->db->query($sql);
	}

	public function delete_grup($id = 0)
	{
		$this->db->query("DELETE FROM kontak_grup WHERE id_grup = ".$id);
	}

	public function delete_all_grup()
	{
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			$list_id = implode(",", $id_cb);
			$this->db->query("DELETE FROM kontak_grup WHERE id_grup IN (" . $list_id . ")");
			$outp = true;
		}
		else
			$outp = false;

		if ($outp)
			$_SESSION['success'] = 1;
		else
			$_SESSION['success'] = -1;
	}

	private function search_grup_sql()
	{
		if (isset($_SESSION['cari_grup']))
		{
			$cari = $_SESSION['cari_grup'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' . $kw . '%';
			$search_grup_sql = " AND (nama_grup LIKE '$kw')";
			return $search_grup_sql;
		}
	}

	private function search_anggota_sql()
	{
		if (isset($_SESSION['cari_anggota']))
		{
			$cari = $_SESSION['cari_anggota'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' . $kw . '%';
			$search_anggota_sql = " AND (nama LIKE '$kw')";
			return $search_anggota_sql;
		}
	}

	public function paging_anggota($id = 0, $p = 1, $o = 0)
	{
		$sql = "SELECT COUNT(*) as jml " . $this->list_data_anggota_sql($id);
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_anggota_sql($id)
	{
		$sql = " FROM daftar_anggota_grup WHERE id_grup = $id ";
		$sql .= $this->search_anggota_sql();
		return $sql;
	}

	public function list_data_anggota($id = 0, $o = 0, $offset = 0, $limit = 500)
	{
		$paging_sql = ' LIMIT ' . $offset . ',' . $limit;

		$sql = "SELECT * " . $this->list_data_anggota_sql($id);
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		$j = $offset;
		for ($i=0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	public function list_data_nama($id = 0)
	{
		$sql = "SELECT * FROM daftar_kontak WHERE id_kontak NOT IN (SELECT id_kontak FROM anggota_grup_kontak WHERE id_grup = $id) ";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		return $data;
	}

	public function insert_anggota($grup)
	{
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			foreach ($id_cb as $a)
			{
				$sql  = "INSERT INTO anggota_grup_kontak(id_grup, id_kontak) VALUES($grup,$a)";
				$outp = $this->db->query($sql);
			}
		}
		else
			$outp = false;

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete_anggota($id = 0)
	{
		$sql = "DELETE FROM anggota_grup_kontak WHERE id_grup_kontak = $id";
		$query = $this->db->query($sql);
	}

	public function delete_all_anggota($grup = 0)
	{
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$sql  = "DELETE FROM anggota_grup_kontak WHERE id_grup_kontak = $id";
				$outp = $this->db->query($sql);
			}
		}
		else
			$outp = false;

		status_sukses($outp); //Tampilkan Pesan
	}

	public function paging_polling($p = 1, $o = 0)
	{
		$sql = "SELECT count(*) as jml " . $this->list_data_polling_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_polling_sql()
	{
		$sql = " FROM polling a WHERE 1 ";
		return $sql;
	}

	public function list_data_polling($o = 0, $offset = 0, $limit = 500)
	{
		$paging_sql = ' LIMIT ' . $offset . ',' . $limit;
		$select_sql = "SELECT a.*, (SELECT COUNT(b.id) FROM pertanyaan b WHERE b.id_polling = a.id_polling) as jumlah_pertanyaan ";

		$sql = $select_sql . $this->list_data_polling_sql();
		$sql .= $paging_sql;
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		//Formating Output
		$j = $offset;

		return $data;
	}

	public function get_data_polling($id = 0)
	{
		$sql = "SELECT * FROM polling WHERE id_polling = '$id'";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function insert_polling($id = 0)
	{
		$data = $_POST;
		if ($id == 0)
		{
			$outp = $this->db->insert('polling', $data);
		} else {
			$this->db->where('id_polling', $id);
			$outp = $this->db->update('polling', $data);
		}

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete_polling($id = 0)
	{
		$sql = "DELETE FROM polling WHERE id_polling = '$id' ";
		$query = $this->db->query($sql);
	}

	public function delete_all_polling()
	{
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$sql = "DELETE FROM  polling WHERE id_polling = '$id' ";
				$outp = $this->db->query($sql, array(
					$id
				));
			}
		} else
			$outp = false;

		status_sukses($outp); //Tampilkan Pesan
	}

	public function paging_pertanyaan($id = 0, $p = 1, $o = 0)
	{
		$sql = "SELECT COUNT(*) as jml " . $this->list_data_pertanyaan_sql($id);
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_pertanyaan_sql($id)
	{
		$sql = " FROM kontak_grup a
			LEFT JOIN kontak b ON a.id_kontak = b.id
			LEFT JOIN tweb_penduduk c ON b.id_pend = c.id
			WHERE a.id_kontak <> '0' AND nama_grup = '$id' ";
		$sql .= $this->search_anggota_sql();
		return $sql;
	}

	public function list_data_pertanyaan($id = 0, $o = 0, $offset = 0, $limit = 500)
	{
		$paging_sql = ' LIMIT ' . $offset . ',' . $limit;

		$select_sql = "SELECT a.*, c.*, b.*, (CASE when sex = '1' then 'Laki-laki' else 'Perempuan' end) as sex ";
		$sql = $select_sql . $this->list_data_pertanyaan_sql($id);
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		$j = $offset;

		return $data;
	}
}
?>
