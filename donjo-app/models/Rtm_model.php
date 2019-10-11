<?php class Rtm_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$sql = "SELECT t.nama
			FROM tweb_rtm u
			LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id
			WHERE 1  ";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$outp = '';
		for ($i=0; $i<count($data); $i++)
		{
			$outp .= ',"'.$data[$i]['nama'].'"';
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}

	private function dusun_sql()
	{
		if (isset($_SESSION['dusun']))
		{
			$kf = $_SESSION['dusun'];
			$dusun_sql = " AND c.dusun = '$kf'";
			return $dusun_sql;
		}
	}

	private function rw_sql()
	{
		if (isset($_SESSION['rw']))
		{
			$kf = $_SESSION['rw'];
			$rw_sql = " AND c.rw = '$kf'";
			return $rw_sql;
		}
	}

	private function rt_sql()
	{
		if (isset($_SESSION['rt']))
		{
			$kf = $_SESSION['rt'];
			$rt_sql = " AND c.rt = '$kf'";
			return $rt_sql;
		}
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql = " AND t.nama LIKE '$kw'";
			return $search_sql;
			}
		}

	private function jenis_sql()
	{
		if (isset($_SESSION['jenis']))
		{
			$kh = $_SESSION['jenis'];
			$jenis_sql = " AND jenis = $kh";
			return $jenis_sql;
		}
	}

	private function kelas_sql()
	{
		if (isset($_SESSION['kelas']))
		{
			$kh = $_SESSION['kelas'];
			$kelas_sql = " AND kelas_sosial= $kh";
			return $kelas_sql;
		}
	}

	public function paging($p=1, $o=0)
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
		$sql = " FROM tweb_rtm u
			LEFT JOIN tweb_penduduk t ON u.no_kk = t.id_rtm AND t.rtm_level = 1
			LEFT JOIN tweb_keluarga k ON t.id_kk = k.id
			LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id
			WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->kelas_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.no_kk'; break;
			case 2: $order_sql = ' ORDER BY u.no_kk DESC'; break;
			case 3: $order_sql = ' ORDER BY kepala_kk'; break;
			case 4: $order_sql = ' ORDER BY kepala_kk DESC'; break;
			case 5: $order_sql = ' ORDER BY g.nama'; break;
			case 6: $order_sql = ' ORDER BY g.nama DESC'; break;
			default:$order_sql = ' ';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$select_sql = "SELECT u.*, t.nama AS kepala_kk, t.nik, k.alamat AS alamat,
			(SELECT COUNT(id)
				FROM tweb_penduduk
				WHERE id_rtm = u.no_kk ) AS jumlah_anggota,
			c.dusun, c.rw, c.rt ";
		$sql = $select_sql . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if ($data[$i]['jumlah_anggota'] == 0)
				$data[$i]['jumlah_anggota'] = "-";

			$j++;
		}
		return $data;
	}

	public function insert()
	{
		$nik = $_POST['nik_kepala'];

		$kw = $this->get_kode_wilayah();
		$nortm = 100000 + $kk['id'];
		$nortm = substr($nortm, 1, 5);
		$rtm['no_kk'] = $kw."".$nortm;
		$rtm['nik_kepala'] = $nik;
		$outp = $this->db->insert('tweb_rtm', $rtm);

		$default['id_rtm'] = $rtm['no_kk'];
		$default['rtm_level'] = 1;
		$default['updated_at'] = date('Y-m-d H:i:s');
		$default['updated_by'] = $this->session->user;
		$this->db->where('id', $nik);
		$this->db->update('tweb_penduduk', $default);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete($id='')
	{
		$temp['id_rtm'] = 0;
		$temp['rtm_level'] = 0;
		$temp['updated_at'] = date('Y-m-d H:i:s');
		$temp['updated_by'] = $this->session->user;

		$this->db->where('id_rtm', $id);
		$outp = $this->db->update('tweb_penduduk', $temp);

		$sql = "DELETE FROM tweb_rtm WHERE id = ?";
		$outp = $this->db->query($sql, array($id));

		if (!$outp) $this->session->success = -1;
	}

	public function delete_all()
	{
		$id_cb = $_POST['id_cb'];

		foreach ($id_cb as $id)
		{
			$this->delete($id);
		}
	}

	public function add_anggota($id=0)
	{
		$data = $_POST;
		$no_rtm = $this->db->select('no_kk')
			->where('id', $id)
			->get('tweb_rtm')->row()->no_kk;
		$temp['id_rtm'] = $no_rtm;
		$temp['rtm_level'] = 2;
		$temp['updated_at'] = date('Y-m-d H:i:s');
		$temp['updated_by'] = $this->session->user;

		$this->db->where('id', $data['nik']);
		$outp = $this->db->update('tweb_penduduk', $temp);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function update_anggota($id=0, $id_kk)
	{
		$data = $_POST;

		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['updated_by'] = $this->session->user;
		$this->db->where('id', $id);
		$outp = $this->db->update('tweb_penduduk', $data);
		// Kalau menjadi kepala rumah tangga, tweb_rtm perlu diupdate juga
		if ($data['rtm_level'] == 1)
		{
			$this->db->where('id', $id_kk)->update('tweb_rtm', array('nik_kepala' => $id));
		}

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function rem_anggota($kk=0, $id=0)
	{
		$temp['id_rtm'] = 0;
		$temp['rtm_level'] = 0;
		$temp['updated_at'] = date('Y-m-d H:i:s');
		$temp['updated_by'] = $this->session->user;

		$pend = $this->rtm_model->get_anggota($id);
		$this->db->where('id', $id);
		$outp = $this->db->update('tweb_penduduk', $temp);
		if ($pend['rtm_level'] == '1')
		{
			$temp2['nik_kepala'] = 0;
			$this->db->where('id', $pend['id_rtm']);
			$outp = $this->db->update('tweb_rtm', $temp2);
		}

		if (!$outp) $this->session->success = -1;
	}

	public function rem_all_anggota($kk)
	{
		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->rem_anggota($kk, $id);
		}
	}

	public function get_dusun($id=0)
	{
		$sql = "SELECT * FROM tweb_rtm WHERE dusun_id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function get_rtm($id=0)
	{
		$sql = "SELECT * FROM tweb_rtm WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		return $data;
	}

	public function get_anggota($id=0)
	{
		$sql = "SELECT * FROM tweb_penduduk WHERE id_rtm = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		return $data;
	}

	private function get_kode_wilayah()
	{
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		$d = $query->row_array();
		$data = $d['kode_kabupaten'].$d['kode_kecamatan'].$d['kode_desa'];

		return $data;
	}

	public function list_penduduk_lepas()
	{
		$sql = "SELECT p.id, p.nik, p.nama, h.nama as kk_level
			FROM tweb_penduduk p
			LEFT JOIN tweb_penduduk_hubungan h ON p.kk_level = h.id
			WHERE (status = 1 OR status = 3) AND status_dasar = 1 AND id_rtm = 0";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['alamat'] = "Alamat :".$data[$i]['nama'];
			$data[$i]['nama'] = ''.$data[$i]['nama'].' - '.$data[$i]['kk_level'].'';
		}
		return $data;
	}

	public function list_anggota($id=0)
	{
		$sql = "SELECT b.dusun, b.rw, b.rt, u.id, nik, x.nama as sex, k.no_kk, u.rtm_level, tempatlahir, tanggallahir, a.nama as agama, d.nama as pendidikan, j.nama as pekerjaan, w.nama as status_kawin, f.nama as warganegara, nama_ayah, nama_ibu, g.nama as golongan_darah, u.nama, status, h.nama AS hubungan
			FROM tweb_penduduk u
			LEFT JOIN tweb_keluarga k ON u.id_kk = k.id
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_penduduk_pekerjaan j ON u.pekerjaan_id = j.id
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_golongan_darah g ON u.golongan_darah_id = g.id
			LEFT JOIN tweb_penduduk_kawin w ON u.status_kawin = w.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_rtm_hubungan h ON u.rtm_level = h.id
			LEFT JOIN tweb_rtm r ON u.id_rtm = r.no_kk
			LEFT JOIN tweb_wil_clusterdesa b ON u.id_cluster = b.id
			WHERE r.id = ? ORDER BY rtm_level";

		$query = $this->db->query($sql, array($id));
		$data = $query->result_array();

		//Formating Output
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['alamat'] = "Dusun ".$data[$i]['dusun'].", RW ".$data[$i]['rw'].", RT ".$data[$i]['rt'];
			$data[$i]['tanggallahir'] = tgl_indo($data[$i]['tanggallahir']);
		}
		return $data;
	}

	public function get_kepala_rtm($id, $is_no_kk=false)
	{
		$kolom_id = ($is_no_kk) ? "no_kk" : "id";
		$this->load->model('penduduk_model');
		$sql = "SELECT u.id, u.nik, u.nama, r.no_kk, u.tempatlahir, u.tanggallahir, (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(`tanggallahir`)), '%Y') + 0 FROM tweb_penduduk WHERE id = u.id) AS umur, d.nama as pendidikan, f.nama as warganegara, a.nama as agama, wil.rt, wil.rw, wil.dusun
			FROM tweb_rtm r
			LEFT JOIN tweb_penduduk u ON u.id = r.nik_kepala
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_wil_clusterdesa wil ON wil.id = u.id_cluster
			WHERE r.$kolom_id = $id LIMIT 1";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		$data['alamat_wilayah'] = $this->penduduk_model->get_alamat_wilayah($data['id']);
		return $data;
	}

  public function get_desa()
  {
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	public function list_hubungan()
	{
		$sql = "SELECT id, nama as hubungan FROM tweb_rtm_hubungan WHERE 1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function update_nokk($id=0)
	{
		$data = $_POST;

		$this->db->where("id", $id);
		$outp = $this->db->update("tweb_rtm", $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

}
?>
