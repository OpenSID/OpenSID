<?php class Export_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('database_model');
	}

	/* ==================================================================================
		Export ke format Excel yang bisa diimpor mempergunakan Import Excel
	  Tabel: dari tweb_wil_clusterdesa, c; tweb_keluarga, k; tweb_penduduk:, p
	  Kolom: c.dusun,c.rw,c.rt,p.nama,k.no_kk,p.nik,p.sex,p.tempatlahir,p.tanggallahir,p.agama_id,p.pendidikan_kk_id,p.pendidikan_sedang_id,p.pekerjaan_id,p.status_kawin,p.kk_level,p.warganegara_id,p.nama_ayah,p.nama_ibu,p.golongan_darah_id
	*/

  private function bersihkanData(&$str, $key)
  {
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    // Kode yang tersimpan sebagai '0' harus '' untuk dibaca oleh Import Excel
    $kecuali = array('nik', 'no_kk');
    if ($str == "0" and !in_array($key, $kecuali)) $str = "";
  }

	// Export data penduduk ke format Import Excel
	public function export_excel($tgl_update = '')
	{
		$desa = $this->db
		->select('kode_desa')
		->get('config')
		->row();
		$kode_desa = kode_wilayah($desa->kode_desa);

		$data = $this->db->select([
			'k.alamat', 'c.dusun', 'c.rw', 'c.rt', 'p.nama', 'k.no_kk', 'p.nik', 'p.sex', 'p.tempatlahir', 'p.tanggallahir', 'p.agama_id', 'p.pendidikan_kk_id', 'p.pendidikan_sedang_id', 'p.pekerjaan_id', 'p.status_kawin', 'p.kk_level', 'p.warganegara_id', 'p.nama_ayah', 'p.nama_ibu', 'p.golongan_darah_id', 'p.akta_lahir', 'p.dokumen_pasport', 'p.tanggal_akhir_paspor', 'p.dokumen_kitas', 'p.ayah_nik', 'p.ibu_nik', 'p.akta_perkawinan', 'p.tanggalperkawinan', 'p.akta_perceraian', 'p.tanggalperceraian', 'p.cacat_id', 'p.cara_kb_id', 'p.hamil', 'p.id', 'p.foto', 'p.status_dasar', 'p.ktp_el', 'p.status_rekam', 'p.alamat_sekarang', 'p.created_at', 'p.updated_at', "CONCAT('{$kode_desa}') as desa_id"])
			->from('tweb_penduduk p')
			->join('tweb_keluarga k', 'k.id = p.id_kk', 'left')
			->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left')
			->order_by('k.no_kk ASC', 'p.kk_level ASC')
			->get()->result();

		for ($i=0; $i<count($data); $i++)
		{
			$baris = $data[$i];
			array_walk($baris, array($this, 'bersihkanData'));
			if (!empty($baris->tanggallahir))
				$baris->tanggallahir = date_format(date_create($baris->tanggallahir),"Y-m-d");
			if (!empty($baris->tanggalperceraian))
				$baris->tanggalperceraian = date_format(date_create($baris->tanggalperceraian),"Y-m-d");
			if (!empty($baris->tanggalperkawinan))
				$baris->tanggalperkawinan = date_format(date_create($baris->tanggalperkawinan),"Y-m-d");
			if (!empty($baris->tanggal_akhir_paspor))
				$baris->tanggal_akhir_paspor = date_format(date_create($baris->tanggal_akhir_paspor),"Y-m-d");
			if (empty($baris->dusun))
				$baris->dusun = '-';
			if (empty($baris->rt))
				$baris->rt = '-';
			if (empty($baris->rw))
				$baris->rw = '-';
			if (!empty($baris->foto))
				$baris->foto = 'kecil_' . $baris->foto;
			$data[$i] = $baris;
		}

		return $data;
	}

	public function export_csv($tgl_update = '')
	{
		$sql = "SELECT k.alamat, c.dusun, c.rw, c.rt, p.nama, k.no_kk, p.nik, p.sex, p.tempatlahir, p.tanggallahir, p.agama_id, p.pendidikan_kk_id, p.pendidikan_sedang_id, p.pekerjaan_id, p.status_kawin, p.kk_level, p.warganegara_id, p.nama_ayah, p.nama_ibu, p.golongan_darah_id, p.akta_lahir, p.dokumen_pasport, p.tanggal_akhir_paspor, p.dokumen_kitas, p.ayah_nik, p.ibu_nik, p.akta_perkawinan, p.tanggalperkawinan, p.akta_perceraian, p.tanggalperceraian, p.cacat_id, p.cara_kb_id, p.hamil, p.id, p.status_dasar, p.ktp_el, p.status_rekam, p.alamat_sekarang, p.created_at, p.updated_at

			FROM tweb_penduduk p
			LEFT JOIN tweb_keluarga k on k.id = p.id_kk
			LEFT JOIN tweb_wil_clusterdesa c on p.id_cluster = c.id
			ORDER BY k.no_kk, p.kk_level
		";
		$q = $this->db->query($sql);
		$data = $q->result_array();
		for ($i=0; $i<count($data); $i++)
		{
			$baris = $data[$i];
			array_walk($baris, array($this, 'bersihkanData'));
			if (!empty($baris['tanggallahir']))
				$baris['tanggallahir'] = date_format(date_create($baris['tanggallahir']),"Y-m-d");
			if (!empty($baris['tanggalperceraian']))
				$baris['tanggalperceraian'] = date_format(date_create($baris['tanggalperceraian']),"Y-m-d");
			if (!empty($baris['tanggalperkawinan']))
				$baris['tanggalperkawinan'] = date_format(date_create($baris['tanggalperkawinan']),"Y-m-d");
			if (!empty($baris['tanggal_akhir_paspor']))
				$baris['tanggal_akhir_paspor'] = date_format(date_create($baris['tanggal_akhir_paspor']),"Y-m-d");
			$data[$i] = $baris;
		}
		return $data;
	}

	// ====================== End export_by_keluarga ========================

	public function export_dasar()
	{
		$return = "";
		$return.=$this->_build_schema('tweb_penduduk', 'penduduk');
		$return.=$this->_build_schema('tweb_keluarga', 'keluarga');
		$return.=$this->_build_schema('tweb_wil_clusterdesa', 'cluster');

		Header('Content-type: application/octet-stream');
		Header('Content-Disposition: attachment; filename=data_dasar('.date("d-m-Y").').sid');
		echo $return;
	}

	private function do_backup($prefs)
	{
		$this->load->dbutil();
		$backup =& $this->dbutil->backup($prefs);
		return $backup;
	}

	/*
		Backup menggunakan CI dilakukan per table. Tidak memperhatikan relational constraint antara table. Jadi perlu disesuaikan supaya bisa di-impor menggunakan
		Database > Backup/Restore > Restore atau menggunakan phpmyadmin.

		TODO: cari cara backup yang menghasilkan .sql seperti menu export di phpmyadmin.
	*/
	public function backup()
	{
		// Tabel dengan foreign key dan
		// semua views ditambah di belakang.
		$views = $this->database_model->get_views();
		// Urutan kedua view berikut perlu diubah karena bergantungan
		$view1 = array_search('daftar_anggota_grup', $views);
		$view2 = array_search('daftar_kontak', $views);
		list($views[$view1], $views[$view2]) = array($views[$view2], $views[$view1]);

		// Kalau ada ketergantungan beruntun, urut dengan yg tergantung di belakang
		$ada_foreign_key = array('suplemen_terdata', 'kontak', 'anggota_grup_kontak', 'mutasi_inventaris_asset', 'mutasi_inventaris_gedung', 'mutasi_inventaris_jalan', 'mutasi_inventaris_peralatan', 'mutasi_inventaris_tanah', 'disposisi_surat_masuk', 'tweb_penduduk_mandiri', 'setting_aplikasi_options', 'log_penduduk', 'agenda',
			'syarat_surat', 'covid19_pemudik', 'covid19_pantau', 'kelompok_anggota');
		$prefs = array(
				'format'      => 'sql',
				'tables'			=> $ada_foreign_key,
			  );
		$tabel_foreign_key = $this->do_backup($prefs);
		$prefs = array(
				'format'      => 'sql',
				'tables'			=> $views,
				'add_drop'		=> FALSE,
				'add_insert'	=> FALSE
			  );
		$create_views = $this->do_backup($prefs);

		$backup = '';
		// Hapus semua views dulu
		foreach ($views as $view)
		{
			$backup .= "DROP VIEW IF EXISTS ".$view.";\n";
		}
		// Hapus tabel dgn foreign key
		foreach (array_reverse($ada_foreign_key) as $table)
		{
			$backup .= "DROP TABLE IF EXISTS ".$table.";\n";
		}

		// Semua views dan tabel dgn foreign key di-backup terpisah
		$prefs = array(
				'format'      => 'sql',
				'ignore'			=> array_merge(array('data_surat'), $views, $ada_foreign_key),
			  );
		$backup .= $this->do_backup($prefs);
		$backup .= $tabel_foreign_key;
		$backup .= $create_views;

		// Hilangkan ketentuan user dan baris-baris lain yang
		// dihasilkan oleh dbutil->backup untuk view karena bermasalah
		// pada waktu import dgn restore ataupun phpmyadmin
		$backup = preg_replace("/ALGORITHM=UNDEFINED DEFINER=.+SQL SECURITY DEFINER /", "", $backup);
		$backup = preg_replace("/utf8_general_ci;|utf8mb4_general_ci;|utf8mb4_unicode_ci;|cp850_general_ci;/", "", $backup);

		$db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.sql';
		$save = base_url().$db_name;

		$this->load->helper('file');
		write_file($save, $backup);
		$this->load->helper('download');
		force_download($db_name, $backup);

		if ($backup) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	private function drop_tables()
	{
		$this->db->simple_query('SET FOREIGN_KEY_CHECKS=0');
		$db = $this->db->database;
		$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$db'";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		foreach ($data AS $dat)
		{
			$tbl = $dat["TABLE_NAME"];
			$this->db->simple_query("DROP TABLE ".$tbl);
		}
		$this->db->simple_query('SET FOREIGN_KEY_CHECKS=1');
	}

	private function drop_views()
	{
		$this->db->simple_query('SET FOREIGN_KEY_CHECKS=0');
		$db = $this->db->database;
		$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'VIEW' AND TABLE_SCHEMA = '$db'";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		foreach ($data AS $dat)
		{
			$tbl = $dat["TABLE_NAME"];
			$this->db->simple_query("DROP VIEW ".$tbl);
		}
		$this->db->simple_query('SET FOREIGN_KEY_CHECKS=1');
	}

	public function restore()
	{
		$filename = $_FILES['userfile']['tmp_name'];
		if ($filename =='')
		{
			$this->session->success = -1;
			switch ($_FILES['userfile']['error'])
			{
				case UPLOAD_ERR_INI_SIZE:
					$this->session->error_msg = " --> File melebihi batas unggah. Ubah setting php.ini";
					break;

				default:
					$this->session->error_msg = " --> Ada error sewaktu unggah file";
					break;
			}
			return;
		}

		$this->drop_views();
		$this->drop_tables();

		$_SESSION['success'] = 1;
		$lines = file($filename);
		$query = "";
		foreach ($lines as $key => $sql_line)
		{
			// Abaikan baris apabila kosong atau komentar
			$sql_line = trim($sql_line);
			$sql_line = preg_replace("/ALGORITHM=UNDEFINED DEFINER=.* SQL SECURITY DEFINER /", "", $sql_line);
			$sql_line = preg_replace("/utf8_general_ci;|utf8mb4_general_ci;|utf8mb4_unicode_ci;/", "", $sql_line);

		  if ($sql_line != "" && (strpos($sql_line,"--") === false OR strpos($sql_line, "--") != 0) && $sql_line[0] != '#')
		  {
				$query .= $sql_line;
				if (substr(rtrim($query), -1) == ';')
				{
				  $result = $this->db->simple_query($query) ;
				  if (!$result)
				  {
				  	$_SESSION['success'] = -1;
				  	$error = $this->db->error();
				  	log_message('error', "<br><br>[".$key."]>>>>>>>> Error: ".$query.'<br>');
				  	log_message('error', $error['message'].'<br>'); // (mysql_error equivalent)
						log_message('error', $error['code'].'<br>'); // (mysql_errno equivalent)
				  }
				  $query = "";
				}
		  }
	 	}
	}

	private function _build_schema($nama_tabel, $nama_tanda) {
		$return = "";
		$result = $this->db->query("SELECT * FROM $nama_tabel");
		$fields = $this->db->field_data($nama_tabel);
		$num_fields = count($fields);

		$return.= "<$nama_tanda>\r\n";
		foreach($result->result() as $row) {
			$j=0;
			foreach($fields as $col) {
				$name = $col->name;
				if (isset($row->$name)) {
					$return.= $row->$name ;
				} else {
					$return.= '';
				}
				if ($j < ($num_fields-1)) {
					$return.= '+';
				}
				$j++;
			}
			$return.= "\r\n";
		}
		$return.="</$nama_tanda>\r\n";
		return $return;
	}

}
?>
