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
			'k.alamat', 'c.dusun', 'c.rw', 'c.rt', 'p.nama', 'k.no_kk', 'p.nik', 'p.sex', 'p.tempatlahir', 'p.tanggallahir', 'p.agama_id', 'p.pendidikan_kk_id', 'p.pendidikan_sedang_id', 'p.pekerjaan_id', 'p.status_kawin', 'p.kk_level', 'p.warganegara_id', 'p.nama_ayah', 'p.nama_ibu', 'p.golongan_darah_id', 'p.akta_lahir', 'p.dokumen_pasport', 'p.tanggal_akhir_paspor', 'p.dokumen_kitas', 'p.ayah_nik', 'p.ibu_nik', 'p.akta_perkawinan', 'p.tanggalperkawinan', 'p.akta_perceraian', 'p.tanggalperceraian', 'p.cacat_id', 'p.cara_kb_id', 'p.hamil', 'p.id', 'p.foto', 'p.status_dasar', 'p.ktp_el', 'p.status_rekam', 'p.status_dasar', 'p.alamat_sekarang', 'p.created_at', 'p.updated_at', "CONCAT('{$kode_desa}') as desa_id"])
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

		// Cek tabel yang memiliki FK (SELECT DISTINCT TABLE_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'nama_database')
        // Kalau ada ketergantungan beruntun, urut dengan yg tergantung di belakang
		$ada_foreign_key = [
            'suplemen_terdata',
            'kontak',
            'anggota_grup_kontak',
            'mutasi_inventaris_asset',
            'mutasi_inventaris_gedung',
            'mutasi_inventaris_jalan',
            'mutasi_inventaris_peralatan',
            'mutasi_inventaris_tanah',
            'disposisi_surat_masuk',
            'tweb_penduduk_mandiri',
            'setting_aplikasi_options',
            'log_penduduk',
            'agenda',
            'syarat_surat',
            'covid19_pemudik',
            'covid19_pantau',
            'kelompok_anggota',
            'log_keluarga',
            'grup_akses',
            'produk',
            'keuangan_ref_bank_desa',
            'keuangan_ref_bel_operasional',
            'keuangan_ref_bidang',
            'keuangan_ref_bunga',
            'keuangan_ref_desa',
            'keuangan_ref_kecamatan',
            'keuangan_ref_kegiatan',
            'keuangan_ref_korolari',
            'keuangan_ref_neraca_close',
            'keuangan_ref_perangkat',
            'keuangan_ref_potongan',
            'keuangan_ref_rek1',
            'keuangan_ref_rek2',
            'keuangan_ref_rek3',
            'keuangan_ref_rek4',
            'keuangan_ref_sbu',
            'keuangan_ref_sumber',
            'keuangan_ta_anggaran',
            'keuangan_ta_anggaran_log',
            'keuangan_ta_anggaran_rinci',
            'keuangan_ta_bidang',
            'keuangan_ta_desa',
            'keuangan_ta_jurnal_umum',
            'keuangan_ta_jurnal_umum_rinci',
            'keuangan_ta_kegiatan',
            'keuangan_ta_mutasi',
            'keuangan_ta_pajak',
            'keuangan_ta_pajak_rinci',
            'keuangan_ta_pemda',
            'keuangan_ta_pencairan',
            'keuangan_ta_perangkat',
            'keuangan_ta_rab',
            'keuangan_ta_rab_rinci',
            'keuangan_ta_rab_sub',
            'keuangan_ta_rpjm_bidang',
            'keuangan_ta_rpjm_kegiatan',
            'keuangan_ta_rpjm_misi',
            'keuangan_ta_rpjm_pagu_indikatif',
            'keuangan_ta_rpjm_pagu_tahunan',
            'keuangan_ta_rpjm_sasaran',
            'keuangan_ta_rpjm_tujuan',
            'keuangan_ta_rpjm_visi',
            'keuangan_ta_saldo_awal',
            'keuangan_ta_spj',
            'keuangan_ta_spj_bukti',
            'keuangan_ta_spj_rinci',
            'keuangan_ta_spj_sisa',
            'keuangan_ta_spjpot',
            'keuangan_ta_spp',
            'keuangan_ta_spp_rinci',
            'keuangan_ta_sppbukti',
            'keuangan_ta_spppot',
            'keuangan_ta_sts',
            'keuangan_ta_sts_rinci',
            'keuangan_ta_tbp',
            'keuangan_ta_tbp_rinci',
            'keuangan_ta_triwulan',
            'keuangan_ta_triwulan_rinci',
            'cdesa_penduduk',
            'mutasi_cdesa',
        ];

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
		if ($ada_foreign_key) {
			foreach (array_reverse($ada_foreign_key) as $table) {
				$backup .= 'DROP TABLE IF EXISTS ' . $table . ";\n";
			}
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
		$backup = preg_replace("/utf8_general_ci;|cp850_general_ci;|utf8mb4_general_ci;|utf8mb4_unicode_ci;/", "", $backup);

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
			$this->load->library('upload');
			$this->uploadConfig = [
					'upload_path'   => sys_get_temp_dir(),
					'allowed_types' => 'sql', // File sql terdeteksi sebagai text/plain
					'file_ext'      => 'sql',
					'max_size'      => max_upload() * 1024,
			];
			$this->upload->initialize($this->uploadConfig);
			// Upload sukses
			if (! $this->upload->do_upload('userfile')) {
					$this->session->success   = -1;
					$this->session->error_msg = $this->upload->display_errors(null, null) . ': ' . $this->upload->file_type;

					return false;
			}
			$uploadData = $this->upload->data();
			$filename   = $this->uploadConfig['upload_path'] . '/' . $uploadData['file_name'];

			return $this->proses_restore($filename);
	}

	public function proses_restore($filename = null)
	{
			if (! $filename) {
					return false;
			}

			$lines = file($filename);

			if (count($lines) < 20) {
					$_SESSION['success']   = -1;
					$_SESSION['error_msg'] = 'Sepertinya bukan file backup';

					return false;
			}

			$_SESSION['success'] = 1;
			$this->drop_views();
			$this->drop_tables();

			$query = '';

			foreach ($lines as $key => $sql_line) {
					// Abaikan baris apabila kosong atau komentar
					$sql_line = trim($sql_line);
					$sql_line = preg_replace('/ALGORITHM=UNDEFINED DEFINER=.* SQL SECURITY DEFINER /', '', $sql_line);
					$sql_line = preg_replace('/utf8_general_ci;|utf8mb4_general_ci;|utf8mb4_unicode_ci;/', '', $sql_line);

					if ($sql_line != '' && (strpos($sql_line, '--') === false || strpos($sql_line, '--') != 0) && $sql_line[0] != '#') {
							$query .= $sql_line;
							if (substr(rtrim($query), -1) == ';') {
									$result = $this->db->simple_query($query);
									if (! $result) {
											$_SESSION['success'] = -1;
											$error               = $this->db->error();
											log_message('error', '<br><br>[' . $key . ']>>>>>>>> Error: ' . $query . '<br>');
											log_message('error', $error['message'] . '<br>'); // (mysql_error equivalent)
											log_message('error', $error['code'] . '<br>'); // (mysql_errno equivalent)
									}
									$query = '';
							}
					}
			}

			return true;
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

	/**
	 * Sinkronasi Data dan Foto Penduduk ke OpenDK.
	 *
	 * @return array
	 */
	public function hapus_penduduk_sinkronasi_opendk()
	{
		$desa = $this->db
		->select('kode_desa')
		->get('config')
		->row();
		$kode_desa = kode_wilayah($desa->kode_desa);

		$data_hapus = $this->db->select([
		  "CONCAT('{$kode_desa}') as desa_id",
			'p.id_pend as id_pend_desa',
			'p.foto',
		])
		->from('log_hapus_penduduk p')
		->get()
		->result_array();

		$response['hapus_penduduk'] = $data_hapus;
		return $response;
	}

	public function tambah_penduduk_sinkronasi_opendk()
	{
		$data = $this->db->select(['k.alamat', 'c.dusun', 'c.rw', 'c.rt', 'p.nama', 'k.no_kk', 'p.nik', 'p.sex', 'p.tempatlahir', 'p.tanggallahir', 'p.agama_id', 'p.pendidikan_kk_id', 'p.pendidikan_sedang_id', 'p.pekerjaan_id', 'p.status_kawin', 'p.kk_level', 'p.warganegara_id', 'p.nama_ayah', 'p.nama_ibu', 'p.golongan_darah_id', 'p.akta_lahir', 'p.dokumen_pasport', 'p.tanggal_akhir_paspor', 'p.dokumen_kitas', 'p.ayah_nik', 'p.ibu_nik', 'p.akta_perkawinan', 'p.tanggalperkawinan', 'p.akta_perceraian', 'p.tanggalperceraian', 'p.cacat_id', 'p.cara_kb_id', 'p.hamil', 'p.id', 'p.foto', 'p.status_dasar', 'p.ktp_el', 'p.status_rekam', 'p.alamat_sekarang', 'p.created_at', 'p.updated_at'])
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
}
