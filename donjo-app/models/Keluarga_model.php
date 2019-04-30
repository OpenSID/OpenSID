<?php class Keluarga_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('program_bantuan_model');
		$this->load->model('penduduk_model');
	}

	public function autocomplete()
	{
		$sql = "SELECT t.nama FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE 1  ";
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

	private function sex_sql()
	{
		if (isset($_SESSION['sex']))
		{
			$kf = $_SESSION['sex'];
			$sex_sql= " AND t.sex = '$kf'";
			return $sex_sql;
		}
	}

	/*
		1 - tampilkan keluarga di mana KK mempunyai status dasar 'hidup'
		2 - tampilkan keluarga di mana KK mempunyai status dasar 'hilang/pindah/mati'
		3 - tampilkan keluarga di mana KK tidak ada'
	*/
	private function status_dasar_sql()
	{
		if (isset($_SESSION['status_dasar']))
		{
			$kf = $_SESSION['status_dasar'];
			if ($kf == '1')	$status_dasar_sql = " AND t.status_dasar = 1 AND t.kk_level = 1";
			elseif ($kf == '3') $status_dasar_sql = 'AND (t.status_dasar IS NULL OR t.kk_level <> 1)';
			else $status_dasar_sql = " AND t.status_dasar <> 1";
			return $status_dasar_sql;
		}
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
			$search_sql = " AND (t.nama LIKE '$kw' OR u.no_kk LIKE '$kw' OR t.tag_id_card LIKE '$kw')";
			return $search_sql;
		}
	}

	private function jenis_sql()
	{
		if (isset($_SESSION['jenis']))
		{
			$kh = $_SESSION['jenis'];
			$jenis_sql= " AND jenis = $kh";
			return $jenis_sql;
		}
	}

	private function kelas_sql()
	{
		if (isset($_SESSION['kelas']))
		{
			$kh = $_SESSION['kelas'];
			if ($kh == BELUM_MENGISI)
				$sql = " AND (u.kelas_sosial IS NULL OR u.kelas_sosial = '')";
			else
				$sql = " AND kelas_sosial= $kh";
			return $sql;
		}
	}

	private function bos_sql()
	{
		if (isset($_SESSION['id_bos']))
		{
			$kh = $_SESSION['id_bos'];
			$bos_sql = " AND id_bos= $kh";
			return $bos_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(*) AS jml ".$this->list_data_sql();
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
		$sql = "FROM tweb_keluarga u
			LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id
			LEFT JOIN tweb_wil_clusterdesa c ON u.id_cluster = c.id
			WHERE 1 ";

		$sql .=	$this->search_sql();
		$sql .=	$this->status_dasar_sql();
		$sql .=	$this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .=	$this->rt_sql();
		$sql .=	$this->sex_sql();
		$sql .= $this->kelas_sql();
		$sql .= $this->bos_sql();
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
			default:$order_sql = ' ORDER BY u.no_kk DESC';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql = "SELECT u.*, t.nama AS kepala_kk, t.nik, t.tag_id_card, t.sex, t.status_dasar,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE id_kk = u.id AND status_dasar = 1) AS jumlah_anggota,
			c.dusun, c.rw, c.rt ";
		$sql .= $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if ($data[$i]['jumlah_anggota'] == 0)
				$data[$i]['jumlah_anggota'] = "-";

			if ($data[$i]['sex'] == 1)
				$data[$i]['sex'] = "LAKI-LAKI";
			else
				$data[$i]['sex'] = "PEREMPUAN";
			$j++;
		}
		return $data;
	}

	// Tambah keluarga baru dari penduduk lepas (status tetap atau pendatang)
	public function insert()
	{
		unset($_SESSION['error_msg']);
		$data = $_POST;

		$error_validasi = $this->validasi_data_keluarga($data);
		if (!empty($error_validasi))
		{
			foreach ($error_validasi as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success'] = -1;
			return;
		}

		$pend = $this->db->select('alamat_sekarang, id_cluster')->
			where('id', $data['nik_kepala'])->
			get('tweb_penduduk')->row_array();
		// Gunakan alamat penduduk sebagai alamat keluarga
		$data['alamat'] = $pend['alamat_sekarang'];
		$data['id_cluster'] = $pend['id_cluster'];

		$outp = $this->db->insert('tweb_keluarga', $data);
		$kk_id = $this->db->insert_id();

		$default['id_kk'] = $kk_id;
		$default['kk_level'] = 1;
		$default['status'] = 1; // statusnya menjadi tetap
		$this->db->where('id', $data['nik_kepala']);
		$this->db->update('tweb_penduduk', $default);

		$this->penduduk_model->tulis_log_penduduk($kk_id, '9', date('m'), date('Y'));

		$log['id_pend'] = 1;
		$log['id_cluster'] = 1;
		$log['tanggal'] = date("m-d-y");
		$outp = $this->db->insert('log_perubahan_penduduk', $log);

		// Untuk statistik perkembangan keluarga
		$this->log_keluarga($kk_id, $data['nik_kepala'], 1);

		if($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	private function validasi_data_keluarga($data)
	{
		$valid = array();
		if (isset($data['no_kk']))
		{
			if (!ctype_digit($data['no_kk']))
				array_push($valid, "Nomor KK hanya berisi angka");
			if (strlen($data['no_kk']) != 16 AND $data['no_kk'] != '0')
				array_push($valid, "Nomor KK panjangnya harus 16 atau 0");
			if ($this->db->select('no_kk')->from('tweb_keluarga')->where(array('no_kk'=>$data['no_kk']))->limit(1)->get()->row()->no_kk)
				array_push($valid, "Nomor KK {$data['no_kk']} sudah digunakan");
		}
		if (!empty($valid))
			$_SESSION['validation_error'] = true;
		return $valid;
	}

	public function insert_new()
	{
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		unset($_SESSION['error_msg']);
		$data = $_POST;

		$error_validasi = array_merge($this->penduduk_model->validasi_data_penduduk($data), $this->validasi_data_keluarga($data));
		if (!empty($error_validasi))
		{
			foreach ($error_validasi as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		$old_foto    = '';
		if (!empty($lokasi_file))
		{
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png")
			{
				unset($data['foto']);
			}
			else
			{
				UploadFoto($nama_file,$old_foto,$tipe_file);
				$data['foto'] = $nama_file;
			}
		}
		else
		{
			unset($data['foto']);
		}

		unset($data['file_foto']);
		unset($data['old_foto']);
		unset($data['nik_lama']);
		unset($data['kk_level_lama']);

		UNSET($data['dusun']);
		UNSET($data['rw']);
		UNSET($data['no_kk']);

		// Simpan alamat keluarga sebelum menulis penduduk
		$data2['alamat'] = $data['alamat'];
		UNSET($data['alamat']);

		if ($data['tanggallahir'] == '') unset($data['tanggallahir']);
		else $data['tanggallahir'] = tgl_indo_in($data['tanggallahir']);
		if ($data['tanggalperkawinan'] == '') unset($data['tanggalperkawinan']);
		else $data['tanggalperkawinan'] = tgl_indo_in($data['tanggalperkawinan']);
		if ($data['tanggalperceraian'] == '') unset($data['tanggalperceraian']);
		else $data['tanggalperceraian'] = tgl_indo_in($data['tanggalperceraian']);

		// Tulis penduduk baru sebagai kepala keluarga
		$data['kk_level'] = 1;
		$outp = $this->db->insert('tweb_penduduk', $data);
		$id_pend = $this->db->insert_id();
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;

		// Tulis keluarga baru
		$data2['nik_kepala'] = $id_pend;
		$data2['no_kk'] = $_POST['no_kk'];
		$data2['id_cluster'] = $data['id_cluster'];
		$outp = $this->db->insert('tweb_keluarga', $data2);
		$kk_id = $this->db->insert_id();

		// Update penduduk kaitkan dengan KK
		$default['id_kk'] = $kk_id;
		$this->db->where('id', $id_pend);
		$this->db->update('tweb_penduduk', $default);

		$satuan = $_POST['tanggallahir'];
		$blnlahir = substr($satuan,3,2);
		$thnlahir = substr($satuan,6,4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if (($blnlahir == $blnskrg) and ($thnlahir == $thnskrg))
		{
			$x['id_detail'] = '1';
		}
		else
		{
			$x['id_detail'] = '5';
		}

		$x['id_pend'] = $id_pend;
		$x['bulan'] = $blnskrg;
		$x['tahun'] = $thnskrg;
		$this->penduduk_model->tulis_log_penduduk_data($x);

		$log['id_pend'] = 1;
		$log['id_cluster'] = 1;
		$log['tanggal'] = date("m-d-y");
		$outp = $this->db->insert('log_perubahan_penduduk', $log);

		// Untuk statistik perkembangan keluarga
		$this->log_keluarga($kk_id, $data2['nik_kepala'], 1);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	/* 	Hapus keluarga:
			(1) Untuk setiap anggota keluarga lakukan rem_anggota (pecah kk).
			(2) Hapus keluarga
			$id adalah id tweb_keluarga
	*/
	public function delete($id='')
	{
		$nik_kepala = $this->db->select('nik_kepala')->where('id',$id)->get('tweb_keluarga')->row()->nik_kepala;
		$list_anggota = $this->db->select('id')->where('id_kk',$id)->get('tweb_penduduk')->result_array();
		foreach ($list_anggota as $anggota)
		{
			$this->rem_anggota($id,$anggota['id']);
		}
		$this->db->where('id',$id)->delete('tweb_keluarga');
		// Untuk statistik perkembangan keluarga
		$this->log_keluarga($id, $nik_kepala, 2);
	}

	public function delete_all()
	{
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$this->delete($id);
			}
		}
	}

	/* 	Untuk statistik perkembangan keluarga
	 		id_peristiwa:
	       1 - keluarga baru
	       2 - keluarga dihapus
	       3 - kepala keluarga status dasar kembali 'hidup' (salah mengisi di log_penduduk)
	       4 - kepala keluarga status dasar 'mati'
	       5 - kepala keluarga status dasar 'pindah'
	       6 - kepala keluarga status dasar 'hilang'
	*/
	public function log_keluarga($id, $kk, $id_peristiwa)
	{
		$this->db->select('sex');
		$this->db->where('id', $kk);
		$q = $this->db->get('tweb_penduduk');
		$penduduk = $q->row_array();
		$log_keluarga['id_kk'] = $id;
		$log_keluarga['kk_sex'] = $penduduk['sex'];
		$log_keluarga['id_peristiwa'] = $id_peristiwa;
		$log_keluarga['tgl_peristiwa'] = date('Y-m-d H:i:s');
		$outp = $this->db->insert('log_keluarga', $log_keluarga);
	}

	public function add_anggota($id=0)
	{
		$data = $_POST;
		$this->update_kk_level($data['nik'], $id, $data['kk_level'], null);

		$temp['id_kk'] = $id;
		$temp['kk_level'] = $data['kk_level'];

		$this->db->where('id', $data['nik']);
		$outp = $this->db->update('tweb_penduduk', $temp);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function update_kk_level($id, $id_kk, $kk_level, $kk_level_lama)
	{
		$outp = true;
		if ($kk_level == 1 and $kk_level_lama != 1)
		{
    	// Kalau ada penduduk lain yg juga Kepala Keluarga, ubah menjadi hubungan Lainnya
			$lvl['kk_level'] = 11;
			$this->db->where('id_kk', $id_kk);
			$this->db->where('kk_level', 1);
			$this->db->update('tweb_penduduk', $lvl);

			$nik['nik_kepala'] = $id;
			$this->db->where('id', $id_kk);
			$outp = $this->db->update('tweb_keluarga', $nik);
		}
    elseif ($kk_level_lama == 1 and $kk_level != 1)
    {
    	// Ubah kepala keluarga menjadi kosong
      $nik['nik_kepala'] = NULL;
      $this->db->where('id', $id_kk);
      $outp = $this->db->update('tweb_keluarga', $nik);
    }
    return $outp;
	}

	public function update_anggota($id=0)
	{
		$data = $_POST;

		$sql = "SELECT id_kk FROM tweb_penduduk WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$pend = $query->row_array();

		$this->update_kk_level($id, $pend['id_kk'], $data['kk_level'], $data['kk_level_lama']);
    unset($data['kk_level_lama']);

		$this->db->where('id', $id);
		$outp = $this->db->update('tweb_penduduk', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function rem_anggota($kk=0, $id=0)
	{
		$pend = $this->keluarga_model->get_anggota($id);
		$temp['no_kk_sebelumnya'] = $this->db->select('no_kk')->where('id',$kk)->get('tweb_keluarga')->row()->no_kk;
		$temp['id_kk'] = 0;
		$temp['kk_level'] = 0;
		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_penduduk', $temp);
		if ($pend['kk_level'] == '1')
		{
			$temp2['nik_kepala'] = 0;
			$this->db->where('id', $pend['id_kk']);
			$outp = $this->db->update('tweb_keluarga', $temp2);
		}

		$this->penduduk_model->tulis_log_penduduk($id, '7', date('m'), date('Y'));
	}

	public function rem_all_anggota($kk)
	{
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$this->rem_anggota($kk, $id);
			}
		}
	}

	public function get_dusun($id=0)
	{
		$sql = "SELECT * FROM tweb_keluarga WHERE dusun_id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function get_keluarga($id=0)
	{
		$sql = "SELECT k.*, b.dusun as dusun, b.rw as rw
			FROM tweb_keluarga k
			LEFT JOIN tweb_wil_clusterdesa b ON k.id_cluster = b.id
			WHERE k.id = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		$data['alamat_plus_dusun'] = $data['alamat'];
		$data['tgl_cetak_kk'] = tgl_indo_out($data['tgl_cetak_kk']);
		return $data;
	}

	public function get_data_cetak_kk($id=0)
	{
		$kk['id_kk'] = $id;
		$kk['main'] = $this->keluarga_model->list_anggota($id);
		$kk['kepala_kk'] = $this->keluarga_model->get_kepala_kk($id);
		$kk['desa'] = $this->keluarga_model->get_desa();
		$data['all_kk'][] = $kk;
		return $data;
	}

	public function get_data_cetak_kk_all()
	{
		$data = array();
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$kk = $this->get_data_cetak_kk($id);
				$data['all_kk'][] = $kk['all_kk'][0]; //Kumpulkan semua kk
			}
		}
		return $data;
	}

	public function get_anggota($id=0)
	{
		$sql = "SELECT * FROM tweb_penduduk WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function list_penduduk_lepas()
	{
		$sql = "SELECT u.id, u.nik, u.nama, u.alamat_sekarang as alamat, w.rt, w.rw, w.dusun
			FROM tweb_penduduk u
			LEFT JOIN tweb_wil_clusterdesa w ON u.id_cluster = w.id
			WHERE (status = 1 OR status = 3) AND id_kk = 0";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	// $options['dengan_kk'] = false jika hanya perlu tanggungan keluarga tanpa kepala keluarga
	// $options['pilih'] untuk membatasi ke nik tertentu saja
	public function list_anggota($id=0,$options=array('dengan_kk'=>true))
	{
		$sql = "SELECT u.*, u.sex as sex_id, u.status_kawin as status_kawin_id,
			(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,
			(CASE when u.status_kawin <> 2
				then w.nama
				else
					case when u.akta_perkawinan = ''
						then 'KAWIN TIDAK TERCATAT'
						else 'KAWIN TERCATAT'
					end
				end) as status_kawin,
			b.dusun, b.rw, b.rt, x.nama as sex, u.kk_level, a.nama as agama, d.nama as pendidikan,j.nama as pekerjaan, f.nama as warganegara, g.nama as golongan_darah, h.nama AS hubungan, k.alamat
			FROM tweb_penduduk u
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_penduduk_pekerjaan j ON u.pekerjaan_id = j.id
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_golongan_darah g ON u.golongan_darah_id = g.id
			LEFT JOIN tweb_penduduk_kawin w ON u.status_kawin = w.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_penduduk_hubungan h ON u.kk_level = h.id
			LEFT JOIN tweb_wil_clusterdesa b ON u.id_cluster = b.id
			LEFT JOIN tweb_keluarga k ON u.id_kk = k.id
			WHERE status = 1 AND status_dasar = 1 AND id_kk = ?";
		if ($options['dengan_kk'] !== NULL AND !$options['dengan_kk']) $sql .= " AND kk_level <> 1";
		if (!empty($options['pilih'])) $sql .= " AND u.nik IN (".$options['pilih'].")";
		$sql .= " ORDER BY kk_level, tanggallahir";
		$query = $this->db->query($sql, array($id));
		$data = $query->result_array();
		return $data;
	}

	// $id adalah id_kk : id dari tabel tweb_keluarga, kecuali
	// apabila $is_no_kk == true maka $id adalah no_kk
	public function get_kepala_kk($id, $is_no_kk = false)
	{
		$kolom_id = ($is_no_kk) ? "no_kk" : "id";
		$sql = "SELECT nik, u.id, u.nama, u.status_kawin as status_kawin_id, tempatlahir, tanggallahir, (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur, a.nama as agama, d.nama as pendidikan,j.nama as pekerjaan, x.nama as sex, w.nama as status_kawin, h.nama as hubungan, f.nama as warganegara, warganegara_id, nama_ayah, nama_ibu, g.nama as golongan_darah, c.rt as rt, c.rw as rw, c.dusun as dusun, (SELECT no_kk FROM tweb_keluarga WHERE $kolom_id = ?) AS no_kk, (SELECT alamat FROM tweb_keluarga WHERE $kolom_id = ?) AS alamat, (SELECT id FROM tweb_keluarga WHERE $kolom_id = ?) AS id_kk
			FROM tweb_penduduk u
			LEFT JOIN tweb_penduduk_pekerjaan j ON u.pekerjaan_id = j.id
			LEFT JOIN tweb_golongan_darah g ON u.golongan_darah_id = g.id
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_penduduk_kawin w ON u.status_kawin = w.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_penduduk_hubungan h ON u.kk_level = h.id
			LEFT JOIN tweb_wil_clusterdesa c ON (SELECT id_cluster from tweb_keluarga where $kolom_id = ?) = c.id
			WHERE u.id = (SELECT nik_kepala FROM tweb_keluarga WHERE $kolom_id = ?) ";
		$query = $this->db->query($sql,array($id,$id,$id,$id,$id));
		$data = $query->row_array();
		if ($data['dusun'] != '') $data['alamat_plus_dusun'] = trim($data['alamat'].' '.ucwords($this->setting->sebutan_dusun).' '.$data['dusun']);
		elseif ($data['alamat']) $data['alamat_plus_dusun'] = $data['alamat'];
		$data['alamat_wilayah'] = $this->get_alamat_wilayah($data['id_kk']);
		return $data;
	}

	public function get_kepala_a($id)
	{
		$sql = "SELECT u.*, c.*, k.no_kk, k.alamat
			FROM tweb_penduduk u
			LEFT JOIN tweb_keluarga k ON k.id = ?
			LEFT JOIN tweb_wil_clusterdesa c ON u.id_cluster = c.id WHERE u.id = (SELECT nik_kepala FROM tweb_keluarga WHERE id = ?) ";
		$query = $this->db->query($sql,array($id,$id));
		return $query->row_array();
	}

  public function get_desa()
  {
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	public function list_hubungan()
	{
		$sql = "SELECT *,nama as hubungan FROM tweb_penduduk_hubungan WHERE 1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	// Tambah anggota keluarga
	public function insert_a()
	{
		unset($_SESSION['validation_error']);
		$_SESSION['success'] = 1;
		unset($_SESSION['error_msg']);

		$data = $_POST;
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];
		$nama_file = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		if (!empty($lokasi_file))
		{
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png")
			{
				unset($data['foto']);
			}
			else
			{
				UploadFoto($nama_file,"",$tipe_file);
				$data['foto'] = $nama_file;
			}
		}
		else
		{
			unset($data['foto']);
		}

		unset($data['file_foto']);
		unset($data['old_foto']);
		unset($data['nik_lama']);

		$satuan = $_POST['tanggallahir'];
		$blnlahir = substr($satuan, 3, 2);
		$thnlahir= substr($satuan, 6, 4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if (($blnlahir == $blnskrg) and ($thnlahir == $thnskrg))
		{
			$id_detail='1';
		}
		else
		{
			$id_detail='5';
		}
		$data['tanggallahir'] = tgl_indo_in($data['tanggallahir']);

		$error_validasi = array_merge($this->penduduk_model->validasi_data_penduduk($data), $this->validasi_data_keluarga($data));
		if (!empty($error_validasi))
		{
			foreach ($error_validasi as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success'] = -1;
			return;
		}

		$outp = $this->db->insert('tweb_penduduk', $data);
		if (!$outp) $_SESSION = -1;

    $id_pend = $this->db->insert_id();
		$this->penduduk_model->tulis_log_penduduk($id_pend, $id_detail, $blnskrg, $thnskrg);
	}

	public function get_nokk($id)
	{
		$this->db->select('no_kk');
		$this->db->where('id', $id);
		$q = $this->db->get('tweb_keluarga');
		$kk = $q->row_array();
		return $kk['no_kk'];
	}

	private function cek_nokk($data)
	{
		$nokk_lama = $this->get_nokk($data['id']);
		if ($data['no_kk'] == $nokk_lama) return true; // Tidak berubah

		$error_validasi = $this->validasi_data_keluarga($data);
		if (!empty($error_validasi))
		{
			foreach ($error_validasi as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success'] = -1;
			return false;
		}
		return true;
	}

	public function update_nokk($id=0)
	{
		unset($_SESSION['error_msg']);
		$data = $_POST;

		if (!$this->cek_nokk($data)) return;

		// Pindah dusun/rw/rt anggota keluarga kalau berubah
		if ($data['id_cluster'] != $data['id_cluster_lama']){
			$this->keluarga_model->pindah_anggota_keluarga($id, $data['id_cluster']);
		}
		unset($data['dusun']);
		unset($data['rw']);
		unset($data['id_cluster_lama']);

		$id_program = $data['id_program'];
		unset($data['id_program']);
		// Update peserta program bantuan untuk kk ini
		$no_kk = $this->get_nokk($id);
		$program = $this->program_bantuan_model->list_program_keluarga($id);
		foreach ($program as $bantuan)
		{
			if (in_array($bantuan['id'], $id_program))
			{
				// Tambahkan ke program bantuan
				$this->program_bantuan_model->add_peserta(array('nik'=>$no_kk), $bantuan['id']);
			}
			else
			{
				// Hapus dari program bantuan
				$this->program_bantuan_model->hapus_peserta_program($no_kk, $bantuan['id']);
			}
		}
		if (!empty($data['tgl_cetak_kk'])) $data['tgl_cetak_kk'] = date("Y-m-d H:i:s", strtotime($data['tgl_cetak_kk']));
		else $data['tgl_cetak_kk'] = NULL;
		$this->db->where("id", $id);
		$outp=$this->db->update("tweb_keluarga", $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function pindah_keluarga($id_kk, $id_cluster)
	{
  	$this->db->where('id', $id_kk)->
	  	update('tweb_keluarga', array('id_cluster' => $id_cluster));
  	$this->pindah_anggota_keluarga($id_kk, $id_cluster);
	}

	private function pindah_anggota_keluarga($id_kk, $id_cluster)
	{
		// Ubah dusun/rw/rt untuk semua anggota keluarga
		if (!empty($id_cluster))
		{
			$this->db->where('id_kk', $id_kk);
			$data['id_cluster'] = $id_cluster;
			$outp = $this->db->update('tweb_penduduk', $data);

			// Tulis log pindah untuk setiap anggota keluarga
			$sql = "SELECT id FROM tweb_penduduk WHERE id_kk = $id_kk";
			$query = $this->db->query($sql);
			$data2 = $query->result_array();
			foreach ($data2 as $datanya)
			{
				$this->penduduk_model->tulis_log_penduduk($datanya[id], '6', date('m'), date('Y'));
			}
		}
	}

	public function get_alamat_wilayah($id_kk)
	{
		$sql = "SELECT a.dusun, a.rw, a.rt, k.alamat
				FROM tweb_keluarga k
				LEFT JOIN tweb_wil_clusterdesa a ON k.id_cluster = a.id
				WHERE k.id = ?";
		$query = $this->db->query($sql,$id_kk);
		$data  = $query->row_array();
		if (!isset($data['alamat'])) $data['alamat'] = '';
		if (!isset($data['rt'])) $data['rt'] = '';
		if (!isset($data['rw'])) $data['rw'] = '';
		if (!isset($data['dusun'])) $data['dusun'] = '';

		$alamat_wilayah= trim("$data[alamat] RT $data[rt] / RW $data[rw] ".ikut_case($data['dusun'],$this->setting->sebutan_dusun)." $data[dusun]");
		return $alamat_wilayah;
	}

	public function get_judul_statistik($tipe=0, $nomor=1, $sex=0)
	{
		if ($nomor == BELUM_MENGISI)
			$judul = array("nama" => "BELUM MENGISI");
		else
		{
			switch ($tipe)
			{
				case 'kelas_sosial':
					$sql = "SELECT * FROM tweb_keluarga_sejahtera WHERE id = ? ";
					break;
				case 21:
					$sql = "SELECT * FROM klasifikasi_analisis_keluarga WHERE id = ? and jenis = '1'  ";
					break;
				case 24:
					$sql = "SELECT * FROM ref_bos WHERE id = ?";
					break;
			}
			$query = $this->db->query($sql, $nomor);
			$judul = $query->row_array();
		}
		if ($sex == 1) $judul['nama'] .= " - LAKI-LAKI";
		elseif ($sex == 2) $judul['nama'] .= " - PEREMPUAN";
		return $judul;
	}

	public function get_data_unduh_kk($id)
	{
		$data = array();
		$data['desa'] = $this->get_desa();
		$data['id_kk'] = $id;
		$data['main'] = $this->list_anggota($id);
		$data['kepala_kk']= $this->get_kepala_kk($id);
		return $data;
	}

	public function unduh_kk($id='')
	{
		$id_cb = $_POST['id_cb'];
		if (empty($id) AND count($id_cb) == 1)
		{
			// Aksi borongan dengan satu KK saja
			$id = $id_cb[0];
		}
		if (empty($id))
		{
			// Aksi borongan lebih dari satu KK
			$berkas_kk = array();
			if (count($id_cb))
			{
				foreach ($id_cb as $id)
				{
					$data = $this->get_data_unduh_kk($id);
					$berkas_kk[] = $this->buat_berkas_kk($data);
				}
			}
			# Masukkan semua berkas ke dalam zip
			$berkas_kk = $this->masukkan_zip($berkas_kk);
	    # Unduh berkas zip
	    header('Content-disposition: attachment; filename=berkas_kk_'.date("d-m-Y").'.zip');
	    header('Content-type: application/zip');
	    readfile($berkas_kk);
		}
		else
		{
			// Satu kk
			$data = $this->get_data_unduh_kk($id);
			$berkas_kk = $this->buat_berkas_kk($data);
			header("location:".base_url($berkas_kk));
		}
	}

	private function buat_berkas_kk($data='')
	{
		$mypath="surat\\kk\\";

		$path = "".str_replace("\\","/", $mypath);
		$path_arsip = LOKASI_ARSIP;

		$file = $path."kk.rtf";
		if (!is_file($file))
		{
			return;
		}

		$nama = "";

		$handle = fopen($file,'r');
		$buffer = stream_get_contents($handle);
		$i = 0;
		foreach ($data['main'] AS $ranggota)
		{
			$i++;
			$nama .= $ranggota['nama']."\line ";
			$no .= $i."\line ";
			$hubungan .= $ranggota['hubungan']."\line ";
			$nik .= $ranggota['nik']."\line ";
			$sex .= $ranggota['sex']."\line ";
			$tempatlahir .= $ranggota['tempatlahir']."\line ";
			$tanggallahir .= tgl_indo($ranggota['tanggallahir'])."\line ";
			$agama .= $ranggota['agama']."\line ";
			$pendidikan .= $ranggota['pendidikan']."\line ";
			$pekerjaan .= $ranggota['pekerjaan']."\line ";
			$status_kawin .= $ranggota['status_kawin']."\line ";
			$warganegara .= $ranggota['warganegara']."\line ";
			$dokumen_pasport .= $ranggota['dokumen_pasport']."\line ";
			$dokumen_kitas .= $ranggota['dokumen_kitas']."\line ";
			$nama_ayah .= $ranggota['nama_ayah']."\line ";
			$nama_ibu .= $ranggota['nama_ibu']."\line ";

			if($ranggota['golongan_darah']!="TIDAK TAHU")
				$golongan_darah .= $ranggota['golongan_darah']."\line ";
			else
				$golongan_darah .= "- \line ";
		}

		$buffer = str_replace("[no]","$no", $buffer);
		$buffer = str_replace("[nama]","\caps $nama", $buffer);
		$buffer = str_replace("[hubungan]","$hubungan", $buffer);
		$buffer = str_replace("[nik]","$nik", $buffer);
		$buffer = str_replace("[sex]","$sex", $buffer);
		$buffer = str_replace("[agama]","$agama", $buffer);
		$buffer = str_replace("[pendidikan]","$pendidikan", $buffer);
		$buffer = str_replace("[pekerjaan]","$pekerjaan", $buffer);
		$buffer = str_replace("[tempatlahir]","\caps $tempatlahir", $buffer);
		$buffer = str_replace("[tanggallahir]","\caps $tanggallahir", $buffer);
		$buffer = str_replace("[kawin]","$status_kawin", $buffer);
		$buffer = str_replace("[warganegara]","$warganegara", $buffer);
		$buffer = str_replace("[pasport]","$dokumen_pasport", $buffer);
		$buffer = str_replace("[kitas]","$dokumen_kitas", $buffer);
		$buffer = str_replace("[ayah]","\caps $nama_ayah", $buffer);
		$buffer = str_replace("[ibu]","\caps $nama_ibu", $buffer);
		$buffer = str_replace("[darah]","$golongan_darah", $buffer);

		$h = $data['desa'];
		$k = $data['kepala_kk'];
		$tertanda = tgl_indo(date("Y m d"));
		$tertanda = $h['nama_desa'].", ".$tertanda;
		$buffer = str_replace("desa","\caps $h[nama_desa]", $buffer);
		$buffer = str_replace("alamat_plus_dusun","\caps $k[alamat_plus_dusun]", $buffer);
		$buffer = str_replace("prop","\caps $h[nama_propinsi]", $buffer);
		$buffer = str_replace("kab","\caps $h[nama_kabupaten]", $buffer);
		$buffer = str_replace("kec","\caps $h[nama_kecamatan]", $buffer);
		$buffer = str_replace("*camat","\caps $h[nama_kepala_camat]", $buffer);
		$buffer = str_replace("*kades","\caps $h[nama_kepala_desa]", $buffer);
		$buffer = str_replace("*rt","$k[rt]", $buffer);
		$buffer = str_replace("*rw","$k[rw]", $buffer);
		$buffer = str_replace("*kk","\caps $k[nama]", $buffer);
		$buffer = str_replace("no_kk","$k[no_kk]", $buffer);
		$buffer = str_replace("pos","$h[kode_pos]", $buffer);
		$buffer = str_replace("*tertanda","\caps $tertanda", $buffer);
		$buffer = str_replace("*nip_camat","$h[nip_kepala_camat]", $buffer);

		$berkas_arsip = $path_arsip."kk_$k[no_kk].rtf";
		$handle = fopen($berkas_arsip, 'w+');
		fwrite($handle,$buffer);
		fclose($handle);
		return $berkas_arsip;
	}

	private function masukkan_zip($files=array())
	{
    $zip = new ZipArchive();
    # create a temp file & open it
    $tmp_file = tempnam(sys_get_temp_dir(),'');
    $zip->open($tmp_file, ZipArchive::CREATE);

    # masukkan setiap berkas ke dalam zip
    foreach ($files as $file)
    {
        $download_file = file_get_contents($file);
        $zip->addFromString(basename($file), $download_file);
    }
    $zip->close();
    return $tmp_file;
	}

}
