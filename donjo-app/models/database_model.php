<?php class Database_model extends CI_Model{

  private $engine = 'InnoDB';

  function __construct(){
    parent::__construct();

    $this->cek_engine_db();
    $this->load->dbforge();

  }

  function cek_engine_db() {
		$db_debug = $this->db->db_debug; //save setting
		$this->db->db_debug = FALSE; //disable debugging for queries

      $query = $this->db->query("SELECT table_name,`engine` FROM INFORMATION_SCHEMA.TABLES WHERE table_schema= '". $this->db->database ."'");
      if(!$this->db->_error_number()) {
      	$this->engine = $query->row()->engine;
      }

		$this->db->db_debug = $db_debug; //restore setting
  }

  function reset_setting_aplikasi() {
    $this->db->truncate('setting_aplikasi');
    $query = "
      INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`,`kategori`) VALUES
      (1, 'sebutan_kabupaten','kabupaten','Pengganti sebutan wilayah kabupaten','',''),
      (2, 'sebutan_kabupaten_singkat','kab.','Pengganti sebutan singkatan wilayah kabupaten','',''),
      (3, 'sebutan_kecamatan','kecamatan','Pengganti sebutan wilayah kecamatan','',''),
      (4, 'sebutan_kecamatan_singkat','kec.','Pengganti sebutan singkatan wilayah kecamatan','',''),
      (5, 'sebutan_desa','desa','Pengganti sebutan wilayah desa','',''),
      (6, 'sebutan_dusun','dusun','Pengganti sebutan wilayah dusun','',''),
      (7, 'sebutan_camat','camat','Pengganti sebutan jabatan camat','',''),
      (8, 'website_title','Website Resmi','Judul tab browser modul web','','web'),
      (9, 'login_title','OpenSID', 'Judul tab browser halaman login modul administrasi','',''),
      (10, 'admin_title','Sistem Informasi Desa','Judul tab browser modul administrasi','',''),
      (11, 'web_theme', 'default','Tema penampilan modul web','','web'),
      (12, 'offline_mode',FALSE,'Apakah modul web akan ditampilkan atau tidak','boolean',''),
      (13, 'enable_track',TRUE,'Apakah akan mengirimkan data statistik ke tracker','boolean',''),
      (14, 'dev_tracker','','Host untuk tracker pada development','','development'),
      (15, 'nomor_terakhir_semua_surat', FALSE,'Gunakan nomor surat terakhir untuk seluruh surat tidak per jenis surat','boolean',''),
      (16, 'google_key','','Google API Key untuk Google Maps','','web'),
      (17, 'libreoffice_path','','Path tempat instal libreoffice di server SID','','')
    ";
    $this->db->query($query);
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
    $this->migrasi_110_ke_111();
    $this->migrasi_111_ke_112();
    $this->migrasi_112_ke_113();
    $this->migrasi_113_ke_114();
    $this->migrasi_114_ke_115();
    $this->migrasi_115_ke_116();
    $this->migrasi_116_ke_117();
    $this->migrasi_117_ke_20();
    $this->migrasi_20_ke_21();
    $this->migrasi_21_ke_22();
    $this->migrasi_22_ke_23();
    $this->migrasi_23_ke_24();
  }

  function migrasi_23_ke_24(){
    // Tambah surat keterangan beda identitas KIS
    $data = array(
      'nama'=>'Keterangan Beda Identitas KIS',
      'url_surat'=>'surat_ket_beda_identitas_kis',
      'kode_surat'=>'S-38',
      'jenis'=>1);
    $sql = $this->db->insert_string('tweb_surat_format', $data);
    $sql .= " ON DUPLICATE KEY UPDATE
        nama = VALUES(nama),
        url_surat = VALUES(url_surat),
        kode_surat = VALUES(kode_surat),
        jenis = VALUES(jenis)";
    $this->db->query($sql);
    // Tambah setting sebutan kepala dusun
    $setting = $this->db->where('key','sebutan_kadus')->get('setting_aplikasi')->row()->id;
    if(!$setting){
      $this->db->insert('setting_aplikasi',array('key'=>'sebutan_singkatan_kadus','value'=>'kawil','keterangan'=>'Sebutan singkatan jabatan kepala dusun'));
    }
  }

  function migrasi_22_ke_23(){
    // Tambah widget menu_left untuk menampilkan menu kategori
    $widget = $this->db->select('id')->where('isi','menu_kategori.php')->get('widget')->row();
    if (!$widget->id) {
      $menu_kategori = array('judul'=>'Menu Kategori','isi'=>'menu_kategori.php','enabled'=>1,'urut'=>1,'jenis_widget'=>1);
      $this->db->insert('widget',$menu_kategori);
    }
    // Tambah tabel surat_masuk
    if (!$this->db->table_exists('surat_masuk') ) {
      $query = "
        CREATE TABLE `surat_masuk` (
          `id` int NOT NULL AUTO_INCREMENT,
          `nomor_urut` smallint(5),
          `tanggal_penerimaan` date NOT NULL,
          `nomor_surat` varchar(20),
          `kode_surat` varchar(10),
          `tanggal_surat` date NOT NULL,
          `pengirim` varchar(100),
          `isi_singkat` varchar(200),
          `disposisi_kepada` varchar(50),
          `isi_disposisi` varchar(200),
          `berkas_scan` varchar(100),
          PRIMARY KEY  (`id`)
        );
      ";
      $this->db->query($query);
    }
    // Artikel bisa di-comment atau tidak
    if (!$this->db->field_exists('boleh_komentar', 'artikel')) {
      $fields = array(
        'boleh_komentar' => array(
          'type' => 'tinyint',
          'constraint' => 1,
          'default' => 1
        )
      );
      $this->dbforge->add_column('artikel', $fields);
    }

  }

  function migrasi_21_ke_22(){
    // Tambah lampiran untuk Surat Keterangan Kelahiran
    $this->db->where('url_surat','surat_ket_kelahiran')->update('tweb_surat_format',array('lampiran'=>'f-kelahiran.php'));
    // Tambah setting sumber gambar slider
    $pilihan_sumber = $this->db->where('key','sumber_gambar_slider')->get('setting_aplikasi')->row()->id;
    if(!$pilihan_sumber){
      $this->db->insert('setting_aplikasi',array('key'=>'sumber_gambar_slider','value'=>1,'keterangan'=>'Sumber gambar slider besar'));
    }
    // Tambah gambar kartu peserta program bantuan
    if (!$this->db->field_exists('kartu_peserta', 'program_peserta')) {
      $fields = array(
        'kartu_peserta' => array(
          'type' => 'VARCHAR',
          'constraint' => 100
        )
      );
      $this->dbforge->add_column('program_peserta', $fields);
    }
  }

  function migrasi_20_ke_21(){
    if (!$this->db->table_exists('widget') ) {
      $query = "
        CREATE TABLE `widget` (
          `id` int NOT NULL AUTO_INCREMENT,
          `isi` text,
          `enabled` int(2),
          `judul` varchar(100),
          `jenis_widget` tinyint(2) NOT NULL DEFAULT 3,
          `urut` int(5),
          PRIMARY KEY  (`id`)
        );
      ";
      $this->db->query($query);
      // Pindahkan data widget dari tabel artikel ke tabel widget
      $widgets = $this->db->select('isi, enabled, judul, jenis_widget, urut')->where('id_kategori', 1003)->get('artikel')->result_array();
      foreach($widgets as $widget) {
        $this->db->insert('widget', $widget);
      }
      $this->db->where('id_kategori',1003)->delete('artikel');
      // Hapus kolom widget dari tabel artikel
      $kolom_untuk_dihapus = array("urut", "jenis_widget");
      foreach ($kolom_untuk_dihapus as $kolom){
        $this->dbforge->drop_column('artikel', $kolom);
      }
    }
    // Tambah tautan ke form administrasi widget
    if (!$this->db->field_exists('form_admin', 'widget')) {
      $fields = array(
        'form_admin' => array(
          'type' => 'VARCHAR',
          'constraint' => 100
        )
      );
      $this->dbforge->add_column('widget', $fields);
      $this->db->where('isi','layanan_mandiri.php')->update('widget',array('form_admin'=>'mandiri'));
      $this->db->where('isi','aparatur_desa.php')->update('widget',array('form_admin'=>'pengurus'));
      $this->db->where('isi','agenda.php')->update('widget',array('form_admin'=>'web/index/1000'));
      $this->db->where('isi','galeri.php')->update('widget',array('form_admin'=>'gallery'));
      $this->db->where('isi','komentar.php')->update('widget',array('form_admin'=>'komentar'));
      $this->db->where('isi','media_sosial.php')->update('widget',array('form_admin'=>'sosmed'));
      $this->db->where('isi','peta_lokasi_kantor.php')->update('widget',array('form_admin'=>'hom_desa'));
    }
    // Tambah kolom setting widget
    if (!$this->db->field_exists('setting', 'widget')) {
      $fields = array(
        'setting' => array(
          'type' => 'text'
        )
      );
      $this->dbforge->add_column('widget', $fields);
    }
    // Ubah nama widget menjadi sinergi_program
    $this->db->select('id')->where('isi','sinergitas_program.php')->update('widget', array('isi'=>'sinergi_program.php', 'judul'=>'Sinergi Program','form_admin'=>'web_widget/admin/sinergi_program'));
    // Tambah widget sinergi_program
    $widget = $this->db->select('id')->where('isi','sinergi_program.php')->get('widget')->row();
    if (!$widget->id) {
      $widget_baru = array('judul'=>'Sinergi Program','isi'=>'sinergi_program.php','enabled'=>1,'urut'=>1,'jenis_widget'=>1,'form_admin'=>'web_widget/admin/sinergi_program');
      $this->db->insert('widget',$widget_baru);
    }
  }

  function migrasi_117_ke_20(){
    if (!$this->db->table_exists('setting_aplikasi') ) {
      $query = "
        CREATE TABLE `setting_aplikasi` (
          `id` int NOT NULL AUTO_INCREMENT,
          `key` varchar(50),
          `value` varchar(200),
          `keterangan` varchar(200),
          `jenis` varchar(30),
          `kategori` varchar(30),
          PRIMARY KEY  (`id`)
        );
      ";
      $this->db->query($query);

      $this->reset_setting_aplikasi();
    }
    // Update untuk tambahan offline mode 2, sesudah masuk pra-rilis (ada yang sudah migrasi)
    $this->db->where('id',12)->update('setting_aplikasi',array('value'=>'0','jenis'=>''));
    // Update media_sosial
    $this->db->where('id',3)->update('media_sosial',array('nama'=>'Google Plus'));
    $this->db->where('id',4)->update('media_sosial',array('nama'=>'YouTube'));
    // Tambah widget aparatur_desa
    $widget = $this->db->select('id')->where(array('isi'=>'aparatur_desa.php', 'id_kategori'=>1003))->get('artikel')->row();
    if (!$widget->id) {
      $aparatur_desa = array('judul'=>'Aparatur Desa','isi'=>'aparatur_desa.php','enabled'=>1,'id_kategori'=>1003,'urut'=>1,'jenis_widget'=>1);
      $this->db->insert('artikel',$aparatur_desa);
    }
    // Tambah foto aparatur desa
    if (!$this->db->field_exists('foto', 'tweb_desa_pamong')) {
      $fields = array(
        'foto' => array(
          'type' => 'VARCHAR',
          'constraint' => 100
        )
      );
      $this->dbforge->add_column('tweb_desa_pamong', $fields);
    }
  }

  function migrasi_116_ke_117(){
    // Tambah kolom log_penduduk
    if (!$this->db->field_exists('no_kk', 'log_penduduk')) {
      $query = "ALTER TABLE log_penduduk ADD no_kk decimal(16,0)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('nama_kk', 'log_penduduk')) {
      $query = "ALTER TABLE log_penduduk ADD nama_kk varchar(100)";
      $this->db->query($query);
    }
    // Hapus surat_ubah_sesuaikan
    $this->db->where('url_surat', 'surat_ubah_sesuaikan')->delete('tweb_surat_format');
    // Tambah kolom log_surat untuk surat non-warga
    if (!$this->db->field_exists('nik_non_warga', 'log_surat')) {
      $query = "ALTER TABLE log_surat ADD nik_non_warga decimal(16,0)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('nama_non_warga', 'log_surat')) {
      $query = "ALTER TABLE log_surat ADD nama_non_warga varchar(100)";
      $this->db->query($query);
    }
    $query = "ALTER TABLE log_surat MODIFY id_pend int(11) DEFAULT NULL";
    $this->db->query($query);
    // Tambah contoh surat non-warga
    $query = "
      INSERT INTO tweb_surat_format(nama, url_surat, kode_surat, jenis) VALUES
      ('Domisili Usaha Non-Warga', 'surat_domisili_usaha_non_warga', 'S-37', 1)
      ON DUPLICATE KEY UPDATE
        nama = VALUES(nama),
        url_surat = VALUES(url_surat),
        kode_surat = VALUES(kode_surat),
        jenis = VALUES(jenis);
    ";
    $this->db->query($query);
  }

  function migrasi_115_ke_116(){
    // Ubah surat N-1 menjadi surat gabungan N-1 s/d N-7
    $this->db->where('url_surat','surat_ket_nikah')->update('tweb_surat_format',array('nama'=>'Keterangan Untuk Nikah (N-1 s/d N-7)'));
    // Hapus surat N-2 s/d N-7 yang sudah digabungkan ke surat_ket_nikah
    $this->db->where('url_surat','surat_ket_asalusul')->delete('tweb_surat_format');
    $this->db->where('url_surat','surat_persetujuan_mempelai')->delete('tweb_surat_format');
    $this->db->where('url_surat','surat_ket_orangtua')->delete('tweb_surat_format');
    $this->db->where('url_surat','surat_izin_orangtua')->delete('tweb_surat_format');
    $this->db->where('url_surat','surat_ket_kematian_suami_istri')->delete('tweb_surat_format');
    $this->db->where('url_surat','surat_kehendak_nikah')->delete('tweb_surat_format');
    $this->db->where('url_surat','surat_ket_wali')->delete('tweb_surat_format');
    // Tambah kolom untuk penandatangan surat
    if (!$this->db->field_exists('pamong_ttd', 'tweb_desa_pamong')) {
      $query = "ALTER TABLE tweb_desa_pamong ADD pamong_ttd tinyint(1)";
      $this->db->query($query);
    }
    // Hapus surat_pindah_antar_kab_prov
    $this->db->where('url_surat','surat_pindah_antar_kab_prov')->delete('tweb_surat_format');
  }

  function migrasi_114_ke_115(){
    // Tambah kolom untuk peserta program
    if (!$this->db->field_exists('kartu_nik', 'program_peserta')) {
      $query = "ALTER TABLE program_peserta ADD kartu_nik decimal(16,0)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('kartu_nama', 'program_peserta')) {
      $query = "ALTER TABLE program_peserta ADD kartu_nama varchar(100)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('kartu_tempat_lahir', 'program_peserta')) {
      $query = "ALTER TABLE program_peserta ADD kartu_tempat_lahir varchar(100)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('kartu_tanggal_lahir', 'program_peserta')) {
      $query = "ALTER TABLE program_peserta ADD kartu_tanggal_lahir date";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('kartu_alamat', 'program_peserta')) {
      $query = "ALTER TABLE program_peserta ADD kartu_alamat varchar(200)";
      $this->db->query($query);
    }
  }

  function migrasi_113_ke_114(){
    // Tambah kolom untuk slider
    if (!$this->db->field_exists('slider', 'gambar_gallery')) {
      $query = "ALTER TABLE gambar_gallery ADD slider tinyint(1)";
      $this->db->query($query);
    }
  }

  function migrasi_112_ke_113(){
    // Tambah data desa
    if (!$this->db->field_exists('nip_kepala_desa', 'config')) {
      $query = "ALTER TABLE config ADD nip_kepala_desa decimal(18,0)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('email_desa', 'config')) {
      $query = "ALTER TABLE config ADD email_desa varchar(50)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('telepon', 'config')) {
      $query = "ALTER TABLE config ADD telepon varchar(50)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('website', 'config')) {
      $query = "ALTER TABLE config ADD website varchar(100)";
      $this->db->query($query);
    }
    // Gabung F-1.15 dan F-1.01 menjadi satu lampiran surat_permohonan_kartu_keluarga
    $this->db->where('url_surat','surat_permohonan_kartu_keluarga')->update('tweb_surat_format',array('lampiran'=>'f-1.15.php,f-1.01.php'));
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
      (38, 'Keterangan Pengantar Rujuk/Cerai', 'surat_ket_rujuk_cerai', 'S-35')
      ON DUPLICATE KEY UPDATE
        nama = VALUES(nama),
        url_surat = VALUES(url_surat);
    ";
    $this->db->query($query);
    // surat_ubah_sesuaikan perlu ditangani berbeda, karena ada pengguna di mana
    // url surat_ubah_sesuaikan memiliki id yang bukan 39, sedangkan id 39 juga dipakai untuk surat lain
    $this->db->where('url_surat', 'surat_ubah_sesuaikan');
    $query = $this->db->get('tweb_surat_format');
    // Tambahkan surat_ubah_sesuaikan apabila belum ada
    if($query->num_rows()==0){
      $data = array(
        'nama' => 'Ubah Sesuaikan',
        'url_surat' => 'surat_ubah_sesuaikan',
        'kode_surat' => 'S-36'
      );
      $this->db->insert('tweb_surat_format', $data);
    }

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
        ) ENGINE=".$this->engine." AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
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
      ) ENGINE=".$this->engine." AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
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
        if (isset($kepala_kk['id_cluster'])) {
          $this->db->where('id', $keluarga['id']);
          $this->db->update('tweb_keluarga', array('id_cluster' => $kepala_kk['id_cluster']));
        }
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

  function migrasi_110_ke_111() {
    // Buat folder desa/upload/pengesahan apabila belum ada
    if (!file_exists(LOKASI_PENGESAHAN)) {
      mkdir(LOKASI_PENGESAHAN, 0755);
    }
    // Tambah akti/non-aktifkan dan pilihan favorit format surat
    if (!$this->db->field_exists('kunci', 'tweb_surat_format')) {
      $query = "ALTER TABLE tweb_surat_format ADD kunci tinyint(1) NOT NULL DEFAULT '0'";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('favorit', 'tweb_surat_format')) {
      $query = "ALTER TABLE tweb_surat_format ADD favorit tinyint(1) NOT NULL DEFAULT '0'";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('id_pend', 'dokumen')) {
      $query = "ALTER TABLE dokumen ADD id_pend int(11) NOT NULL DEFAULT '0'";
      $this->db->query($query);
    }

    if (!$this->db->table_exists('setting_modul') ) {
      $query = "
        CREATE TABLE `setting_modul` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `modul` varchar(50) NOT NULL,
          `url` varchar(50) NOT NULL,
          `aktif` tinyint(1) NOT NULL DEFAULT '0',
          `ikon` varchar(50) NOT NULL,
          `urut` tinyint(4) NOT NULL,
          `level` tinyint(1) NOT NULL DEFAULT '2',
          `hidden` tinyint(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
          ) ENGINE=".$this->engine." AUTO_INCREMENT=15 DEFAULT CHARSET=utf8
      ";
      $this->db->query($query);

      $query = "
        INSERT INTO setting_modul VALUES
        ('1','SID Home','hom_desa','1','go-home-5.png','1','2','1'),
        ('2','Penduduk','penduduk/clear','1','preferences-contact-list.png','2','2','0'),
        ('3','Statistik','statistik','1','statistik.png','3','2','0'),
        ('4','Cetak Surat','surat','1','applications-office-5.png','4','2','0'),
        ('5','Analisis','analisis_master/clear','1','analysis.png','5','2','0'),
        ('6','Bantuan','program_bantuan','1','program.png','6','2','0'),
        ('7','Persil','data_persil/clear','1','persil.png','7','2','0'),
        ('8','Plan','plan','1','plan.png','8','2','0'),
        ('9','Peta','gis','1','gis.png','9','2','0'),
        ('10','SMS','sms','1','mail-send-receive.png','10','2','0'),
        ('11','Pengguna','man_user/clear','1','system-users.png','11','1','1'),
        ('12','Database','database','1','database.png','12','1','0'),
        ('13','Admin Web','web','1','message-news.png','13','4','0'),
        ('14','Laporan','lapor','1','mail-reply-all.png','14','2','0');
      ";
      $this->db->query($query);
    }

    /**
      Sesuaikan data modul analisis dengan SID 3.10
    */

    // Tabel analisis_indikator
    $ubah_kolom = array(
      "`nomor` int(3) NOT NULL"
    );
    foreach ($ubah_kolom as $kolom_def){
      $query = "ALTER TABLE analisis_indikator MODIFY ".$kolom_def;
      $this->db->query($query);
    };
    if (!$this->db->field_exists('is_publik', 'analisis_indikator')) {
      $query = "ALTER TABLE analisis_indikator ADD `is_publik` tinyint(1) NOT NULL DEFAULT '0'";
      $this->db->query($query);
    }

    // Tabel analisis_kategori_indikator
    if (!$this->db->field_exists('kategori_kode', 'analisis_kategori_indikator')) {
      $query = "ALTER TABLE analisis_kategori_indikator ADD `kategori_kode` varchar(3) NOT NULL";
      $this->db->query($query);
    }

    // Tabel analisis_master
    if ($this->db->field_exists('kode_analiusis', 'analisis_master')) {
      $query = "ALTER TABLE analisis_master CHANGE `kode_analiusis` `kode_analisis` varchar(5) NOT NULL DEFAULT '00000'";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('id_child', 'analisis_master')) {
      $query = "ALTER TABLE analisis_master ADD `id_child` smallint(4) NOT NULL";
      $this->db->query($query);
    }

    // Tabel analisis_parameter
    if (!$this->db->field_exists('kode_jawaban', 'analisis_parameter')) {
      $query = "ALTER TABLE analisis_parameter ADD `kode_jawaban` int(3) NOT NULL";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('asign', 'analisis_parameter')) {
      $query = "ALTER TABLE analisis_parameter ADD `asign` tinyint(1) NOT NULL DEFAULT '0'";
      $this->db->query($query);
    }

    // Tabel analisis_respon
    $drop_kolom = array(
      "id",
      "tanggal_input"
    );
    foreach ($drop_kolom as $kolom_def){
      if ($this->db->field_exists($kolom_def, 'analisis_respon')) {
        $query = "ALTER TABLE analisis_respon DROP ".$kolom_def;
        $this->db->query($query);
      }
    };

    // Tabel analisis_respon_bukti
    $query = "
      CREATE TABLE IF NOT EXISTS `analisis_respon_bukti` (
        `id_master` tinyint(4) NOT NULL,
        `id_periode` tinyint(4) NOT NULL,
        `id_subjek` int(11) NOT NULL,
        `pengesahan` varchar(100) NOT NULL,
        `tgl_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=".$this->engine." DEFAULT CHARSET=utf8;
      ";
    $this->db->query($query);

    // Tabel analisis_respon_hasil
    if ($this->db->field_exists('id', 'analisis_respon_hasil')) {
      $query = "ALTER TABLE analisis_respon_hasil DROP `id`";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('tgl_update', 'analisis_respon_hasil')) {
      $query = "ALTER TABLE analisis_respon_hasil ADD `tgl_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP";
      $this->db->query($query);
    }
    $db = $this->db->database;
    $query = "
      SELECT COUNT(1) ConstraintSudahAda
      FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
      WHERE TABLE_SCHEMA = ?
      AND TABLE_NAME = 'analisis_respon_hasil'
      AND CONSTRAINT_NAME = 'id_master'
    ";
    $hasil = $this->db->query($query, $db);
    $data = $hasil->row_array();
    if ($data['ConstraintSudahAda'] == 0) {
      $query = "ALTER TABLE analisis_respon_hasil ADD CONSTRAINT `id_master` UNIQUE (`id_master`,`id_periode`,`id_subjek`)";
      $this->db->query($query);
    }

    /**
      Sesuaikan data modul persil dengan SID 3.10
    */

    // Tabel data_persil
    $ubah_kolom = array(
      "`nik` varchar(64) NOT NULL",
      "`nama` varchar(128) NOT NULL COMMENT 'nomer persil'",
      "`persil_jenis_id` tinyint(2) NOT NULL",
      "`luas` decimal(7,2) NOT NULL",
      "`kelas` varchar(128) DEFAULT NULL",
      "`no_sppt_pbb` varchar(128) NOT NULL",
      "`persil_peruntukan_id` tinyint(2) NOT NULL"
    );
    foreach ($ubah_kolom as $kolom_def){
      $query = "ALTER TABLE data_persil MODIFY ".$kolom_def;
      $this->db->query($query);
    };
    if (!$this->db->field_exists('peta', 'data_persil')) {
      $query = "ALTER TABLE data_persil ADD `peta` text";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('rdate', 'data_persil')) {
      $query = "ALTER TABLE data_persil ADD `rdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP";
      $this->db->query($query);
    }

    // Tabel data_persil_jenis
    $ubah_kolom = array(
      "`nama` varchar(128) NOT NULL",
      "`ndesc` text NOT NULL"
    );
    foreach ($ubah_kolom as $kolom_def){
      $query = "ALTER TABLE data_persil_jenis MODIFY ".$kolom_def;
      $this->db->query($query);
    };

    // Tabel data_persil_peruntukan
    $ubah_kolom = array(
      "`nama` varchar(128) NOT NULL",
      "`ndesc` text NOT NULL"
    );
    foreach ($ubah_kolom as $kolom_def){
      $query = "ALTER TABLE data_persil_peruntukan MODIFY ".$kolom_def;
      $this->db->query($query);
    };

    // Ubah surat keterangan pindah penduduk untuk bisa memilih format lampiran
    $query = "
      INSERT INTO `tweb_surat_format` (`id`, `url_surat`, `lampiran`) VALUES
      (5, 'surat_ket_pindah_penduduk', 'f-1.08.php,f-1.25.php')
      ON DUPLICATE KEY UPDATE
        url_surat = VALUES(url_surat),
        lampiran = VALUES(lampiran);
    ";
    $this->db->query($query);
  }

  function migrasi_111_ke_112() {
    // Ubah surat bio penduduk untuk menambah format lampiran
    $query = "
      INSERT INTO `tweb_surat_format` (`id`, `url_surat`, `lampiran`) VALUES
      (3, 'surat_bio_penduduk', 'f-1.01.php')
      ON DUPLICATE KEY UPDATE
        url_surat = VALUES(url_surat),
        lampiran = VALUES(lampiran);
    ";
    $this->db->query($query);

    // Tabel tweb_penduduk melengkapi data F-1.01
    if (!$this->db->field_exists('telepon', 'tweb_penduduk')) {
      $query = "ALTER TABLE tweb_penduduk ADD `telepon` varchar(20)";
      $this->db->query($query);
    }
    if (!$this->db->field_exists('tanggal_akhir_paspor', 'tweb_penduduk')) {
      $query = "ALTER TABLE tweb_penduduk ADD `tanggal_akhir_paspor` date";
      $this->db->query($query);
    }

    // Ketinggalan tabel gis_simbol
    if (!$this->db->table_exists('gis_simbol') ) {
      $query = "
        CREATE TABLE `gis_simbol` (
          `simbol` varchar(40) DEFAULT NULL
        ) ENGINE=".$this->engine." DEFAULT CHARSET=utf8;
      ";
      $this->db->query($query);
      // Isi dengan daftar icon yang ada di folder assets/images/gis/point
      $simbol_folder = FCPATH . 'assets/images/gis/point';
      $list_gis_simbol = scandir($simbol_folder);
      foreach ($list_gis_simbol as $simbol) {
        if ($simbol['0'] == '.') continue;
        $this->db->insert('gis_simbol', array('simbol' => $simbol));
      }
    }
    if (!$this->db->field_exists('jenis', 'tweb_surat_format')) {
      $query = "ALTER TABLE tweb_surat_format ADD jenis tinyint(2) NOT NULL DEFAULT 2";
      $this->db->query($query);
      // Update semua surat yang disediakan oleh rilis OpenSID
      $surat_sistem = array(
        'surat_ket_pengantar',
        'surat_ket_penduduk',
        'surat_bio_penduduk',
        'surat_ket_pindah_penduduk',
        'surat_ket_jual_beli',
        'surat_pindah_antar_kab_prov',
        'surat_ket_catatan_kriminal',
        'surat_ket_ktp_dalam_proses',
        'surat_ket_beda_nama',
        'surat_jalan',
        'surat_ket_kurang_mampu',
        'surat_izin_keramaian',
        'surat_ket_kehilangan',
        'surat_ket_usaha',
        'surat_ket_jamkesos',
        'surat_ket_domisili_usaha',
        'surat_ket_kelahiran',
        'surat_permohonan_akta',
        'surat_pernyataan_akta',
        'surat_permohonan_duplikat_kelahiran',
        'surat_ket_kematian',
        'surat_ket_lahir_mati',
        'surat_ket_nikah',
        'surat_ket_asalusul',
        'surat_persetujuan_mempelai',
        'surat_ket_orangtua',
        'surat_izin_orangtua',
        'surat_ket_kematian_suami_istri',
        'surat_kehendak_nikah',
        'surat_ket_pergi_kawin',
        'surat_ket_wali',
        'surat_ket_wali_hakim',
        'surat_permohonan_duplikat_surat_nikah',
        'surat_permohonan_cerai',
        'surat_ket_rujuk_cerai'
      );
      // Jenis surat yang bukan bagian rilis sistem sudah otomatis berisi nilai default (yaitu, 2)
      foreach ($surat_sistem as $url_surat) {
        $this->db->where('url_surat',$url_surat)->update('tweb_surat_format',array('jenis'=>1));
      }
    }
    // Tambah surat_permohonan_kartu_keluarga
    $this->db->where('url_surat', 'surat_ubah_sesuaikan')->update('tweb_surat_format',array('kode_surat' => 'P-01'));
    $query = "
      INSERT INTO tweb_surat_format (nama, url_surat, lampiran, kode_surat, jenis) VALUES
      ('Permohonan Kartu Keluarga', 'surat_permohonan_kartu_keluarga', 'f-1.15.php', 'S-36', 1)
      ON DUPLICATE KEY UPDATE
        nama = VALUES(nama),
        url_surat = VALUES(url_surat),
        lampiran = VALUES(lampiran),
        kode_surat = VALUES(kode_surat),
        jenis = VALUES(jenis);
    ";
    $this->db->query($query);
    // Tambah kolom no_kk_sebelumnya untuk penduduk yang pecah dari kartu keluarga
    if (!$this->db->field_exists('no_kk_sebelumnya', 'tweb_penduduk')) {
      $query = "ALTER TABLE tweb_penduduk ADD no_kk_sebelumnya varchar(30)";
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
      "setting_modul",
      "setting_aplikasi",
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
