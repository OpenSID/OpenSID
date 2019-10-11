<?php class Pamong_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function list_data($aktif = false)
	{
		$sql = "SELECT u.*, p.nama as nama, p.nik as nik, p.tempatlahir, p.tanggallahir, x.nama AS sex, b.nama AS pendidikan_kk, g.nama AS agama, x2.nama AS pamong_sex, b2.nama AS pamong_pendidikan, g2.nama AS pamong_agama
			FROM tweb_desa_pamong u
			LEFT JOIN tweb_penduduk p ON u.id_pend = p.id
			LEFT JOIN tweb_penduduk_pendidikan_kk b ON p.pendidikan_kk_id = b.id
			LEFT JOIN tweb_penduduk_sex x ON p.sex = x.id
			LEFT JOIN tweb_penduduk_agama g ON p.agama_id = g.id
			LEFT JOIN tweb_penduduk_pendidikan_kk b2 ON u.pamong_pendidikan = b2.id
			LEFT JOIN tweb_penduduk_sex x2 ON u.pamong_sex = x2.id
			LEFT JOIN tweb_penduduk_agama g2 ON u.pamong_agama = g2.id
			WHERE 1";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql($aktif);
		$sql .= ' ORDER BY urut';

		$query = $this->db->query($sql);
		$data  = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			if (empty($data[$i]['id_pend']))
			{
				// Dari luar desa
				$data[$i]['nama'] = $data[$i]['pamong_nama'];
				$data[$i]['nik'] = $data[$i]['pamong_nik'];
				$data[$i]['tempatlahir'] = !empty($data[$i]['pamong_tempatlahir']) ? $data[$i]['pamong_tempatlahir'] : '-';
				$data[$i]['tanggallahir'] = $data[$i]['pamong_tanggallahir'];
				$data[$i]['sex'] = $data[$i]['pamong_sex'];
				$data[$i]['pendidikan_kk'] = $data[$i]['pamong_pendidikan'];
				$data[$i]['agama'] = $data[$i]['pamong_agama'];
				if (empty($data[$i]['pamong_nosk'])) $data[$i]['pamong_nosk'] = '-';
				if (empty($data[$i]['pamong_nohenti'])) $data[$i]['pamong_nohenti'] = '-';
			}
			else
			{
				if (empty($data[$i]['tempatlahir'])) $data[$i]['tempatlahir'] = '-';
			}
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

	public function autocomplete()
	{
		$sql = "SELECT * FROM
				(SELECT p.nama
					FROM tweb_desa_pamong u
					LEFT JOIN tweb_penduduk p ON u.id_pend = p.id) a
				UNION SELECT p.nik
					FROM tweb_desa_pamong u
					LEFT JOIN tweb_penduduk p ON u.id_pend = p.id
				UNION SELECT pamong_niap FROM tweb_desa_pamong
				UNION SELECT pamong_nip FROM tweb_desa_pamong";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$outp = '';
		for ($i=0; $i<count($data); $i++)
		{
			$outp .= ",'" .addslashes($data[$i]['nama']). "'";
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
			$search_sql = " AND (p.nama LIKE '$kw' OR u.pamong_niap LIKE '$kw' OR u.pamong_nip LIKE '$kw' OR p.nik LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql($aktif=false)
	{
		if ($aktif)
		{
			return " AND u.pamong_status = '1'";
		}
		if (!empty($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql = " AND u.pamong_status = $kf";
			return $filter_sql;
		}
	}

	public function get_data($id=0)
	{
		$sql = "SELECT u.*, p.nama as nama
			FROM tweb_desa_pamong u
			LEFT JOIN tweb_penduduk p ON u.id_pend = p.id
			WHERE pamong_id = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		$data['pamong_niap_nip'] = (!empty($data['pamong_nip']) and $data['pamong_nip'] != '-') ? $data['pamong_nip'] : $data['pamong_niap'];
		if (!empty($data['id_pend']))
		{
			// Dari database penduduk
			$data['pamong_nama'] = $data['nama'];
		}
		return $data;
	 }

	public function get_pamong_by_nama($nama='')
	{
		$pamong = $this->db->select('*')->from('tweb_desa_pamong')->where('pamong_nama', $nama)->limit(1)->get()->row_array();
		return $pamong;
	}

	public function insert()
	{
		$_SESSION['success'] = 1;
		$nama_file = '';
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];

		if (!empty($nama_file))
		{
		  $nama_file = urlencode(generator(6)."_".$_FILES['foto']['name']);
			if (!empty($lokasi_file) AND in_array($tipe_file, unserialize(MIME_TYPE_GAMBAR)))
			{
				UploadFoto($nama_file, $old_foto='', $tipe_file);
			}
			else
			{
				$nama_file = '';
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = " -> Jenis file salah: " . $tipe_file;
			}
		}

		$data = array();
		$data['foto'] = $nama_file;
		$data = $this->siapkan_data($data);
		// Beri urutan terakhir
		$data['urut'] = $this->urut_max() + 1;
		$data['pamong_tgl_terdaftar'] = date('Y-m-d');

		$outp = $this->db->insert('tweb_desa_pamong', $data);

		if (!$outp) $_SESSION['success'] = -1;
	}

	private function siapkan_data(&$data)
	{
		$data['id_pend'] = $this->input->post('id_pend');
		$this->data_pamong_asal($data);
		$data['pamong_nip'] = strip_tags($this->input->post('pamong_nip'));
		$data['pamong_niap'] = strip_tags($this->input->post('pamong_niap'));
		$data['jabatan'] = strip_tags($this->input->post('jabatan'));
		$data['pamong_pangkat'] = strip_tags($this->input->post('pamong_pangkat'));
		$data['pamong_status'] = $this->input->post('pamong_status');
		$data['pamong_nosk'] = strip_tags($this->input->post('pamong_nosk'));
		$data['pamong_tglsk'] = tgl_indo_in($this->input->post('pamong_tglsk'));
		$data['pamong_nohenti'] = !empty($this->input->post('pamong_nohenti')) ? strip_tags($this->input->post('pamong_nohenti')) : NULL;
		$data['pamong_tglhenti'] = !empty($this->input->post('pamong_tglhenti')) ?tgl_indo_in($this->input->post('pamong_tglhenti')) : NULL;
		$data['pamong_masajab'] = strip_tags($this->input->post('pamong_masajab'));
		return $data;
	}

	private function data_pamong_asal(&$data)
	{
		if (empty($data['id_pend']))
		{
			unset($data['id_pend']);
			$data['pamong_nama'] = strip_tags($this->input->post('pamong_nama'));
			$data['pamong_nik'] = strip_tags($this->input->post('pamong_nik'));
			$data['pamong_tempatlahir'] = strip_tags($this->input->post('pamong_tempatlahir'));
			$data['pamong_tanggallahir'] = tgl_indo_in($this->input->post('pamong_tanggallahir'));
			$data['pamong_sex'] = $this->input->post('pamong_sex');
			$data['pamong_pendidikan'] = $this->input->post('pamong_pendidikan');
			$data['pamong_agama'] = $this->input->post('pamong_agama');
		}
		else
		{
			$data['pamong_nama'] = null;
			$data['pamong_nik'] = null;
			$data['pamong_tempatlahir'] = null;
			$data['pamong_tanggallahir'] = null;
			$data['pamong_sex'] = null;
			$data['pamong_pendidikan'] = null;
			$data['pamong_agama'] = null;
		}
	}

	public function update($id=0)
	{
		$data = array();
		unset($_SESSION['validation_error']);
		$_SESSION['success'] = 1;;
		unset($_SESSION['error_msg']);
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];
		$old_foto = $this->input->post('old_foto');
		if (!empty($nama_file))
		{
			if (!empty($lokasi_file) AND in_array($tipe_file, unserialize(MIME_TYPE_GAMBAR)))
			{
			  $data['foto'] = urlencode(generator(6)."_".$nama_file);
				UploadFoto($data['foto'], $old_foto, $tipe_file);
			}
			else
			{
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = " -> Jenis file salah: " . $tipe_file;
			}
		}

		$data = $this->siapkan_data($data);
		$this->db->where("pamong_id", $id)->update('tweb_desa_pamong', $data);
	}

	public function delete($id='')
	{
		$foto = $this->db->select('foto')->where('pamong_id',$id)->get('tweb_desa_pamong')->row()->foto;
		if (!empty($foto))
		{
			unlink(LOKASI_USER_PICT.$foto);
			unlink(LOKASI_USER_PICT.'kecil_'.$foto);
		}
		$sql = "DELETE FROM tweb_desa_pamong WHERE pamong_id = ?";
		$outp = $this->db->query($sql,array($id));
		return $outp;
	}

	public function delete_all()
	{
		$_SESSION['success'] = 1;
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$outp = $this->delete($id);
				if (!$outp) $_SESSION['success'] = -1;
			}
		}
	}

	public function ttd($id='', $val=0)
	{
		if ($val == 1)
		{
			// Hanya satu pamong yang boleh digunakan sebagai ttd a.n / default
			$this->db->where('pamong_ttd', 1)->update('tweb_desa_pamong', array('pamong_ttd'=>0));
		}
		$this->db->where('pamong_id', $id)->update('tweb_desa_pamong', array('pamong_ttd'=>$val));
	}

	public function ub($id='', $val=0)
	{
		if ($val == 1)
		{
			// Hanya satu pamong yang boleh digunakan sebagai ttd u.b
			$this->db->where('pamong_ub', 1)->update('tweb_desa_pamong', array('pamong_ub'=>0));
		}
		$this->db->where('pamong_id', $id)->update('tweb_desa_pamong', array('pamong_ub'=>$val));
	}

	public function get_ttd()
	{
		$ttd = $this->db->where('pamong_ttd', 1)->get('tweb_desa_pamong')->row_array();
		return $ttd;
	}

	public function get_ub()
	{
		$ub = $this->db->where('pamong_ub', 1)->get('tweb_desa_pamong')->row_array();
		return $ub;
	}

  private function urut_max()
  {
    $this->db->select_max('urut');
    $query = $this->db->get('tweb_desa_pamong');
    $data = $query->row_array();
    return $data['urut'];
  }

	private function urut_semua()
	{
		$sql = "SELECT urut, COUNT(*) c FROM tweb_desa_pamong GROUP BY urut HAVING c > 1";
		$query = $this->db->query($sql);
		$urut_duplikat = $query->result_array();
		if ($urut_duplikat)
		{
			$this->db->select("pamong_id");
			$this->db->order_by("urut");
			$q = $this->db->get('tweb_desa_pamong');
			$pamong = $q->result_array();
			for ($i=0; $i<count($pamong); $i++)
			{
				$this->db->where('pamong_id', $pamong[$i]['pamong_id']);
				$data['urut'] = $i + 1;
				$this->db->update('tweb_desa_pamong', $data);
			}
		}
	}

	// $arah:
	//		1 - turun
	// 		2 - naik
	public function urut($id, $arah)
	{
		$this->urut_semua();
		$this->db->where('pamong_id', $id);
		$q = $this->db->get('tweb_desa_pamong');
		$pamong1 = $q->row_array();

		$this->db->select("pamong_id, urut");
		$this->db->order_by("urut");
		$q = $this->db->get('tweb_desa_pamong');
		$pamong = $q->result_array();
		for ($i=0; $i<count($pamong); $i++)
		{
			if ($pamong[$i]['pamong_id'] == $id)
				break;
		}

		if ($arah == 1)
		{
			if ($i >= count($pamong) - 1) return;
			$pamong2 = $pamong[$i + 1];
		}
		if ($arah == 2)
		{
			if ($i <= 0) return;
			$pamong2 = $pamong[$i - 1];
		}

		// Tukar urutan
		$this->db->where('pamong_id', $pamong2['pamong_id'])->
			update('tweb_desa_pamong', array('urut' => $pamong1['urut']));
		$this->db->where('pamong_id', $pamong1['pamong_id'])->
			update('tweb_desa_pamong', array('urut' => $pamong2['urut']));
	}

}
?>
