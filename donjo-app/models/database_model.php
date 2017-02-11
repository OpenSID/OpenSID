<?php class Database_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  function migrasi_db_cri() {
    $this->migrasi_cri_lama();
    $this->migrasi_03_ke_04();
    $this->migrasi_08_ke_081();
    $this->migrasi_082_ke_09();
    $this->migrasi_092_ke_010();
    $this->migrasi_010_ke_10();
    $this->migrasi_10_ke_11();
    $this->migrasi_111_ke_12();
    $this->migrasi_124_ke_13();
    $this->migrasi_13_ke_14();
    $this->migrasi_14_ke_15();
    $this->migrasi_15_ke_16();
    $this->migrasi_16_ke_17();
    $this->migrasi_17_ke_18();
    $this->migrasi_18_ke_19();
    $this->migrasi_19_ke_110();
  }

  // Berdasarkan analisa database yang dikirim oleh AdJie Reverb Impulse
  function migrasi_cri_lama(){
    if (!$this->db->field_exists('enabled', 'kategori')) {
      $query = "ALTER TABLE kategori ADD enabled tinyint(4) DEFAULT 1";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('parrent', 'kategori')) {
      $query = "ALTER TABLE kategori ADD parrent tinyint(4) DEFAULT 0";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('kode_surat', 'tweb_surat_format')) {
      $query = "ALTER TABLE tweb_surat_format ADD kode_surat varchar(10)";
      $this->db->query($query);
    }
  }

  function migrasi_03_ke_04(){
    $query = "
      CREATE TABLE IF NOT EXISTS `tweb_penduduk_mandiri` (
        `nik` decimal(16,0) NOT NULL,
        `pin` char(32) NOT NULL,
        `last_login` datetime,
        `tanggal_buat` date NOT NULL,
        PRIMARY KEY  (`nik`)
      );
    ";
    $this->db->query($query);

    $query = "
      CREATE TABLE IF NOT EXISTS `program` (
        `id` int NOT NULL AUTO_INCREMENT,
        `nama` varchar(100) NOT NULL,
        `sasaran` tinyint,
        `ndesc` varchar(200),
        `sdate` date NOT NULL,
        `edate` date NOT NULL,
        `userid` mediumint NOT NULL,
        `status` int(10),
        PRIMARY KEY  (`id`)
      );
    ";
    $this->db->query($query);

    $query = "
      CREATE TABLE IF NOT EXISTS `program_peserta` (
        `id` int NOT NULL AUTO_INCREMENT,
        `peserta` decimal(16,0) NOT NULL,
        `program_id` int NOT NULL,
        `sasaran` tinyint,
        PRIMARY KEY  (`id`)
      );
    ";
    $this->db->query($query);

    $query = "
      CREATE TABLE IF NOT EXISTS `data_persil` (
        `id` int NOT NULL AUTO_INCREMENT,
        `nik` decimal(16,0) NOT NULL,
        `nama` varchar(100) NOT NULL,
        `persil_jenis_id` int NOT NULL,
        `id_clusterdesa` int NOT NULL,
        `luas` int,
        `no_sppt_pbb` int,
        `kelas` varchar(50),
        `persil_peruntukan_id` int NOT NULL,
        `alamat_ext` varchar(100),
        `userID` mediumint,
        PRIMARY KEY  (`id`)
      );
    ";
    $this->db->query($query);

    $query = "
      CREATE TABLE IF NOT EXISTS `data_persil_peruntukan` (
        `id` int NOT NULL AUTO_INCREMENT,
        `nama` varchar(100) NOT NULL,
        `ndesc` varchar(200),
        PRIMARY KEY  (`id`)
      );
    ";
    $this->db->query($query);

    $query = "
      CREATE TABLE IF NOT EXISTS `data_persil_jenis` (
        `id` int NOT NULL AUTO_INCREMENT,
        `nama` varchar(100) NOT NULL,
        `ndesc` varchar(200),
        PRIMARY KEY  (`id`)
      );
    ";
    $this->db->query($query);
  }

  function migrasi_08_ke_081() {
    if (!$this->db->field_exists('nama_surat', 'log_surat')) {
      $query = "ALTER TABLE `log_surat` ADD `nama_surat` varchar(100)";
      $this->db->query($query);
    }
  }

  function migrasi_082_ke_09() {
    if (!$this->db->field_exists('catatan', 'log_penduduk')) {
      $query = "ALTER TABLE `log_penduduk` ADD `catatan` text";
      $this->db->query($query);
    }
  }

  function migrasi_092_ke_010() {
    // CREATE UNIQUE INDEX migrasi_0_10_url_surat ON tweb_surat_format (url_surat);

    // Hapus surat duplikat
    $kriteria = array('id' => 19, 'url_surat' => 'surat_ket_kehilangan');
    $this->db->where($kriteria);
    $this->db->delete('tweb_surat_format');

    $query = "
      INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`) VALUES
      (1, 'Keterangan Pengantar', 'surat_ket_pengantar', 'S-01'),
      (2, 'Keterangan Penduduk', 'surat_ket_penduduk', 'S-02'),
      (3, 'Biodata Penduduk', 'surat_bio_penduduk', 'S-03'),
      (5, 'Keterangan Pindah Penduduk', 'surat_ket_pindah_penduduk', 'S-04'),
      (6, 'Keterangan Jual Beli', 'surat_ket_jual_beli', 'S-05'),
      (7, 'Pengantar Pindah Antar Kabupaten/ Provinsi', 'surat_pindah_antar_kab_prov', 'S-06'),
      (8, 'Pengantar Surat Keterangan Catatan Kepolisian', 'surat_ket_catatan_kriminal', 'S-07'),
      (9, 'Keterangan KTP dalam Proses', 'surat_ket_ktp_dalam_proses', 'S-08'),
      (10, 'Keterangan Beda Identitas', 'surat_ket_beda_nama', 'S-09'),
      (11, 'Keterangan Bepergian / Jalan', 'surat_jalan', 'S-10'),
      (12, 'Keterangan Kurang Mampu', 'surat_ket_kurang_mampu', 'S-11'),
      (13, 'Pengantar Izin Keramaian', 'surat_izin_keramaian', 'S-12'),
      (14, 'Pengantar Laporan Kehilangan', 'surat_ket_kehilangan', 'S-13'),
      (15, 'Keterangan Usaha', 'surat_ket_usaha', 'S-14'),
      (16, 'Keterangan JAMKESOS', 'surat_ket_jamkesos', 'S-15'),
      (17, 'Keterangan Domisili Usaha', 'surat_ket_domisili_usaha', 'S-16'),
      (18, 'Keterangan Kelahiran', 'surat_ket_kelahiran', 'S-17'),
      (20, 'Permohonan Akta Lahir', 'surat_permohonan_akta', 'S-18'),
      (21, 'Pernyataan Belum Memiliki Akta Lahir', 'surat_pernyataan_akta', 'S-19'),
      (22, 'Permohonan Duplikat Kelahiran', 'surat_permohonan_duplikat_kelahiran', 'S-20'),
      (24, 'Keterangan Kematian', 'surat_ket_kematian', 'S-21'),
      (25, 'Keterangan Lahir Mati', 'surat_ket_lahir_mati', 'S-22'),
      (26, 'Keterangan Untuk Nikah (N-1)', 'surat_ket_nikah', 'S-23'),
      (27, 'Keterangan Asal Usul (N-2)', 'surat_ket_asalusul', 'S-24'),
      (28, 'Persetujuan Mempelai (N-3)', 'surat_persetujuan_mempelai', 'S-25'),
      (29, 'Keterangan Tentang Orang Tua (N-4)', 'surat_ket_orangtua', 'S-26'),
      (30, 'Keterangan Izin Orang Tua(N-5)', 'surat_izin_orangtua', 'S-27'),
      (31, 'Keterangan Kematian Suami/Istri(N-6)', 'surat_ket_kematian_suami_istri', 'S-28'),
      (32, 'Pemberitahuan Kehendak Nikah (N-7)', 'surat_kehendak_nikah', 'S-29'),
      (33, 'Keterangan Pergi Kawin', 'surat_ket_pergi_kawin', 'S-30'),
      (34, 'Keterangan Wali', 'surat_ket_wali', 'S-31'),
      (35, 'Keterangan Wali Hakim', 'surat_ket_wali_hakim', 'S-32'),
      (36, 'Permohonan Duplikat Surat Nikah', 'surat_permohonan_duplikat_surat_nikah', 'S-33'),
      (37, 'Permohonan Cerai', 'surat_permohonan_cerai', 'S-34'),
      (38, 'Keterangan Pengantar Rujuk/Cerai', 'surat_ket_rujuk_cerai', 'S-35'),
      (39, 'Ubah Sesuaikan', 'surat_ubah_sesuaikan', 'S-36')
      ON DUPLICATE KEY UPDATE
        nama = VALUES(nama),
        url_surat = VALUES(url_surat);
    ";
    $this->db->query($query);

    // DROP INDEX migrasi_0_10_url_surat ON tweb_surat_format;

    /* Jangan buat index unik kode_surat, karena kolom ini digunakan
       untuk merekam klasifikasi surat yang tidak unik. */
    // $db = $this->db->database;
    // $query = "
    //   SELECT COUNT(1) IndexIsThere FROM INFORMATION_SCHEMA.STATISTICS
    //   WHERE table_schema=? AND table_name='tweb_surat_format' AND index_name='kode_surat';
    // ";
    // $hasil = $this->db->query($query, $db);
    // $data = $hasil->row_array();
    // if ($data['IndexIsThere'] == 0) {
    //   $query = "
    //     CREATE UNIQUE INDEX kode_surat ON tweb_surat_format (kode_surat);
    //   ";
    //   $this->db->query($query);
    // }

    if (!$this->db->field_exists('tgl_cetak_kk', 'tweb_keluarga')) {
      $query = "ALTER TABLE tweb_keluarga ADD tgl_cetak_kk datetime";
      $this->db->query($query);
    }
    $query = "ALTER TABLE tweb_penduduk_mandiri MODIFY tanggal_buat datetime";
    $this->db->query($query);
  }

  function migrasi_010_ke_10() {
    $query = "
      INSERT INTO tweb_penduduk_pekerjaan(id, nama) VALUES (89, 'LAINNYA')
      ON DUPLICATE KEY UPDATE
        id = VALUES(id),
        nama = VALUES(nama);
    ";
    $this->db->query($query);
  }

  function migrasi_10_ke_11() {
    if (!$this->db->field_exists('kk_lk', 'log_bulanan')) {
      $query = "ALTER TABLE log_bulanan ADD kk_lk int(11)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('kk_pr', 'log_bulanan')) {
      $query = "ALTER TABLE log_bulanan ADD kk_pr int(11)";
      $this->db->query($query);
    }

    if (!$this->db->field_exists('urut', 'artikel')) {
      $query = "ALTER TABLE artikel ADD urut int(5)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('jenis_widget', 'artikel')) {
      $query = "ALTER TABLE artikel ADD jenis_widget tinyint(2) NOT NULL DEFAULT 3";
      $this->db->query($query);
    }

    if (!$this->db->table_exists('log_keluarga') ) {
      $query = "
        CREATE TABLE `log_keluarga` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `id_kk` int(11) NOT NULL,
          `kk_sex` tinyint(2) NOT NULL,
          `id_peristiwa` int(4) NOT NULL,
          `tgl_peristiwa` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `id_kk` (`id_kk`,`id_peristiwa`,`tgl_peristiwa`)
        ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
      ";
      $this->db->query($query);
    }

    $query = "
      DROP VIEW IF EXISTS data_surat;
    ";
    $this->db->query($query);

    $query = "
      DROP TABLE IF EXISTS data_surat;
    ";
    $this->db->query($query);

    $query = "
      CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `data_surat` AS select `u`.`id` AS `id`,`u`.`nama` AS `nama`,`x`.`nama` AS `sex`,`u`.`tempatlahir` AS `tempatlahir`,`u`.`tanggallahir` AS `tanggallahir`,(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) from `tweb_penduduk` where (`tweb_penduduk`.`id` = `u`.`id`)) AS `umur`,`w`.`nama` AS `status_kawin`,`f`.`nama` AS `warganegara`,`a`.`nama` AS `agama`,`d`.`nama` AS `pendidikan`,`j`.`nama` AS `pekerjaan`,`u`.`nik` AS `nik`,`c`.`rt` AS `rt`,`c`.`rw` AS `rw`,`c`.`dusun` AS `dusun`,`k`.`no_kk` AS `no_kk`,(select `tweb_penduduk`.`nama` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `k`.`nik_kepala`)) AS `kepala_kk` from ((((((((`tweb_penduduk` `u` left join `tweb_penduduk_sex` `x` on((`u`.`sex` = `x`.`id`))) left join `tweb_penduduk_kawin` `w` on((`u`.`status_kawin` = `w`.`id`))) left join `tweb_penduduk_agama` `a` on((`u`.`agama_id` = `a`.`id`))) left join `tweb_penduduk_pendidikan_kk` `d` on((`u`.`pendidikan_kk_id` = `d`.`id`))) left join `tweb_penduduk_pekerjaan` `j` on((`u`.`pekerjaan_id` = `j`.`id`))) left join `tweb_wil_clusterdesa` `c` on((`u`.`id_cluster` = `c`.`id`))) left join `tweb_keluarga` `k` on((`u`.`id_kk` = `k`.`id`))) left join `tweb_penduduk_warganegara` `f` on((`u`.`warganegara_id` = `f`.`id`)));
    ";
    $this->db->query($query);

    $system_widgets = array(
      'Layanan Mandiri'      => 'layanan_mandiri.php',
      'Agenda'               => 'agenda.php',
      'Galeri'               => 'galeri.php',
      'Statistik'            => 'statistik.php',
      'Komentar'             => 'komentar.php',
      'Media Sosial'         => 'media_sosial.php',
      'Peta Lokasi Kantor'   => 'peta_lokasi_kantor.php',
      'Statistik Pengunjung' => 'statistik_pengunjung.php',
      'Arsip Artikel'        => 'arsip_artikel.php'
    );

    foreach($system_widgets as $key => $value) {
      $this->db->select('id');
      $this->db->where(array('isi' => $value, 'id_kategori' => 1003));
      $q = $this->db->get('artikel');
      $widget = $q->row_array();
      if (!$widget['id']) {
        $query = "
          INSERT INTO artikel (judul,isi,enabled,id_kategori,urut,jenis_widget)
          VALUES ('$key','$value',1,1003,1,1);";
        $this->db->query($query);
      }
    }
  }

  function migrasi_111_ke_12() {
    if (!$this->db->field_exists('alamat', 'tweb_keluarga')) {
      $query = "ALTER TABLE tweb_keluarga ADD alamat varchar(200)";
      $this->db->query($query);
    }
  }

  function migrasi_124_ke_13() {
    if (!$this->db->field_exists('urut', 'menu')) {
      $query = "ALTER TABLE menu ADD urut int(5)";
      $this->db->query($query);
    }
  }

  function migrasi_13_ke_14() {
    $query = "
      INSERT INTO user_grup (id, nama) VALUES (4, 'Kontributor')
      ON DUPLICATE KEY UPDATE
        id = VALUES(id),
        nama = VALUES(nama);
    ";
    $this->db->query($query);

    // Buat tanggalperkawinan dan tanggalperceraian boleh NULL
    $query = "ALTER TABLE tweb_penduduk CHANGE tanggalperkawinan tanggalperkawinan DATE NULL DEFAULT NULL;";
    $this->db->query($query);
    $query = "ALTER TABLE tweb_penduduk CHANGE tanggalperceraian tanggalperceraian DATE NULL DEFAULT NULL;";
    $this->db->query($query);

     // Ubah tanggal menjadi NULL apabila 0000-00-00
    $query = "UPDATE tweb_penduduk SET tanggalperkawinan=NULL WHERE tanggalperkawinan='0000-00-00' OR tanggalperkawinan='00-00-0000';";
    $this->db->query($query);
    $query = "UPDATE tweb_penduduk SET tanggalperceraian=NULL WHERE tanggalperceraian='0000-00-00' OR tanggalperceraian='00-00-0000';";
    $this->db->query($query);
  }

  function migrasi_14_ke_15() {
    // Tambah kolom di tabel tweb_penduduk
    if (!$this->db->field_exists('cara_kb_id', 'tweb_penduduk')) {
      $query = "ALTER TABLE tweb_penduduk ADD cara_kb_id tinyint(2) NULL DEFAULT NULL;";
      $this->db->query($query);
    }

     // Tambah tabel cara_kb
    $query = "DROP TABLE IF EXISTS tweb_cara_kb;";
    $this->db->query($query);

    $query = "
      CREATE TABLE tweb_cara_kb (
        id tinyint(5) NOT NULL AUTO_INCREMENT,
        nama varchar(50) NOT NULL,
        sex tinyint(2),
        PRIMARY KEY (id)
      ) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
    ";
    $this->db->query($query);

    $query = "
      INSERT INTO tweb_cara_kb (id, nama, sex) VALUES
      (1, 'Pil', 2),
      (2, 'IUD', 2),
      (3, 'Suntik', 2),
      (4, 'Kondom', 1),
      (5, 'Susuk KB', 2),
      (6, 'Sterilisasi Wanita', 2),
      (7, 'Sterilisasi Pria', 1),
      (99, 'Lainnya', 3);
    ";
    $this->db->query($query);

     // Ubah tanggallahir supaya tidak tampil apabila kosong
    $query = "ALTER TABLE tweb_penduduk CHANGE tanggallahir tanggallahir DATE NULL DEFAULT NULL;";
    $this->db->query($query);
    $query = "
      UPDATE tweb_penduduk SET tanggallahir=NULL
      WHERE tanggallahir='0000-00-00' OR tanggallahir='00-00-0000';
    ";
    $this->db->query($query);
  }

  function migrasi_15_ke_16(){
    // Buat kk_sex boleh NULL
    $query = "ALTER TABLE log_keluarga CHANGE kk_sex kk_sex tinyint(2) NULL DEFAULT NULL;";
    $this->db->query($query);

    // ==== Gabung program bantuan keluarga statik ke dalam modul Program Bantuan

    $program_keluarga = array(
      "Raskin" => "raskin",
      "BLSM"   => "id_blt",
      "PKH"    => "id_pkh",
      "Bedah Rumah" => "id_bedah_rumah"
    );
    foreach ($program_keluarga as $key => $value) {
      // cari keluarga anggota program
      if (!$this->db->field_exists($value, 'tweb_keluarga')) continue;

      $this->db->select("no_kk");
      $this->db->where("$value",1);
      $q = $this->db->get("tweb_keluarga");
      if ( $q->num_rows() > 0 ) {
        // buat program
        $data = array(
          'sasaran' => 2,
          'nama' => $key,
          'ndesc' => '',
          'userid' => 0,
          'sdate' => date("Y-m-d",strtotime("-1 year")),
          'edate' => date("Y-m-d",strtotime("+1 year"))
        );
        $this->db->insert('program', $data);
        $id_program = $this->db->insert_id();
        // untuk setiap keluarga anggota program buat program_peserta
        $data = $q->result_array();
        foreach ($data as $peserta_keluarga) {
          $peserta = array(
            'peserta' => $peserta_keluarga['no_kk'],
            'program_id' => $id_program,
            'sasaran' => 2
          );
          $this->db->insert('program_peserta', $peserta);
        }
      }
      // Hapus kolom program di tweb_keluarga
      $sql = "ALTER TABLE tweb_keluarga DROP COLUMN $value";
      $this->db->query($sql);
    }
    // ==== Gabung program bantuan penduduk statik ke dalam modul Program Bantuan

    $program_penduduk = array(
      "JAMKESMAS" => "jamkesmas"
    );
    foreach ($program_penduduk as $key => $value) {
      // cari penduduk anggota program
      if (!$this->db->field_exists($value, 'tweb_penduduk')) continue;

      $this->db->select("nik");
      $this->db->where("$value",1);
      $q = $this->db->get("tweb_penduduk");
      if ( $q->num_rows() > 0 ) {
        // buat program
        $data = array(
          'sasaran' => 1,
          'nama' => $key,
          'ndesc' => '',
          'userid' => 0,
          'sdate' => date("Y-m-d",strtotime("-1 year")),
          'edate' => date("Y-m-d",strtotime("+1 year"))
        );
        $this->db->insert('program', $data);
        $id_program = $this->db->insert_id();
        // untuk setiap penduduk anggota program buat program_peserta
        $data = $q->result_array();
        foreach ($data as $peserta_penduduk) {
          $peserta = array(
            'peserta' => $peserta_penduduk['nik'],
            'program_id' => $id_program,
            'sasaran' => 2
          );
          $this->db->insert('program_peserta', $peserta);
        }
      }
      // Hapus kolom program di tweb_penduduk
      $sql = "ALTER TABLE tweb_penduduk DROP COLUMN $value";
      $this->db->query($sql);
    }
  }

  function migrasi_16_ke_17(){
    // Tambahkan id_cluster ke tabel keluarga
    if (!$this->db->field_exists('id_cluster', 'tweb_keluarga')) {
      $query = "ALTER TABLE tweb_keluarga ADD id_cluster int(11);";
      $this->db->query($query);

      // Untuk setiap keluarga
      $query = $this->db->get('tweb_keluarga');
      $data = $query->result_array();
      foreach ($data as $keluarga) {
        // Ambil id_cluster kepala keluarga
        $this->db->select('id_cluster');
        $this->db->where('id', $keluarga['nik_kepala']);
        $query = $this->db->get('tweb_penduduk');
        $kepala_kk = $query->row_array();
        // Tulis id_cluster kepala keluarga ke keluarga
        $this->db->where('id', $keluarga['id']);
        $this->db->update('tweb_keluarga', array('id_cluster' => $kepala_kk['id_cluster']));
      }
    }
  }

  function migrasi_17_ke_18() {
    // Tambah lampiran surat dgn template html2pdf
    if (!$this->db->field_exists('lampiran', 'log_surat')) {
      $query = "ALTER TABLE `log_surat` ADD `lampiran` varchar(100)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('lampiran', 'tweb_surat_format')) {
      $query = "ALTER TABLE `tweb_surat_format` ADD `lampiran` varchar(100)";
      $this->db->query($query);
    }
    $query = "
      INSERT INTO `tweb_surat_format` (`id`, `url_surat`, `lampiran`) VALUES
      (5, 'surat_ket_pindah_penduduk', 'f-1.08.php')
      ON DUPLICATE KEY UPDATE
        url_surat = VALUES(url_surat),
        lampiran = VALUES(lampiran);
    ";
    $this->db->query($query);
  }

  function migrasi_18_ke_19() {
    // Hapus index unik untuk kode_surat kalau sempat dibuat sebelumnya
    $db = $this->db->database;
    $query = "
      SELECT COUNT(1) IndexIsThere FROM INFORMATION_SCHEMA.STATISTICS
      WHERE table_schema=? AND table_name='tweb_surat_format' AND index_name='kode_surat';
    ";
    $hasil = $this->db->query($query, $db);
    $data = $hasil->row_array();
    if ($data['IndexIsThere'] > 0) {
      $query = "
        DROP INDEX kode_surat ON tweb_surat_format;
      ";
      $this->db->query($query);
    }

    // Hapus tabel yang tidak terpakai lagi
    $query = "DROP TABLE IF EXISTS ref_bedah_rumah, ref_blt, ref_jamkesmas, ref_pkh, ref_raskin, tweb_alamat_sekarang";
    $this->db->query($query);
  }

  function migrasi_19_ke_110() {
    // Tambah nomor id_kartu untuk peserta program bantuan
    if (!$this->db->field_exists('no_id_kartu', 'program_peserta')) {
      $query = "ALTER TABLE program_peserta ADD no_id_kartu varchar(30)";
      $this->db->query($query);
    }
  }

  function kosongkan_db(){
    $table_lookup = array(
      "analisis_ref_state",
      "analisis_ref_subjek",
      "analisis_tipe_indikator",
      "artikel", //remove everything except widgets 1003
      "data_surat", // view
      "media_sosial", //?
      "tweb_cacat",
      "tweb_cara_kb",
      "tweb_golongan_darah",
      "tweb_penduduk_agama",
      "tweb_penduduk_hubungan",
      "tweb_penduduk_kawin",
      "tweb_penduduk_pekerjaan",
      "tweb_penduduk_pendidikan",
      "tweb_penduduk_pendidikan_kk",
      "tweb_penduduk_sex",
      "tweb_penduduk_status",
      "tweb_penduduk_umur",
      "tweb_penduduk_warganegara",
      "tweb_rtm_hubungan",
      "tweb_sakit_menahun",
      "tweb_status_dasar",
      "tweb_surat_format",
      "user",
      "user_grup"
    );

    // Hapus semua artikel kecuali artikel widget dengan kategori 1003
    $this->db->where("id_kategori !=", "1003");
    $query = $this->db->delete('artikel');
    // Hapus semua tabel kecuali table lookup
    $semua_table = $this->db->list_tables();
    foreach ($semua_table as $table){
      if (!in_array($table, $table_lookup)) {
        $query = "TRUNCATE ".$table;
        $this->db->query($query);
      }
    }
  }

}
?>
