<?php
class Migrasi_1909_ke_1910 extends CI_model {

  public function up() {
  	// Tambah modul Keuangan
  	$this->modul_keuangan();
		// Tambah tabel asuransi
		if (!$this->db->table_exists('tweb_penduduk_asuransi'))
		{
			$query = "
				CREATE TABLE `tweb_penduduk_asuransi` (
					`id` tinyint(5) NOT NULL AUTO_INCREMENT,
					`nama` varchar(50) NOT NULL,
					PRIMARY KEY (id)
				)
			";

			$this->db->query($query);

			$query = "INSERT INTO tweb_penduduk_asuransi (`id`, `nama`) VALUES
				(1, 'Tidak/Belum Punya'),
				(2, 'BPJS Penerima Bantuan Iuran'),
				(3, 'BPJS Non Penerima Bantuan Iuran'),
				(99, 'Asuransi Lainnya')
			";

			$this->db->query($query);
		}
		// Tambah kolom no_asuransi, id_asuransi
  	if (!$this->db->field_exists('id_asuransi', 'tweb_penduduk'))
  	{
  		$fields = array();
  		$fields['id_asuransi'] = array(
	        	'type' => 'tinyint',
	        	'constraint' => 5,
	        	'null' => TRUE,
	        	'default' => NULL
	        );
			$this->dbforge->add_column('tweb_penduduk', $fields);
  	}
  	if (!$this->db->field_exists('no_asuransi', 'tweb_penduduk'))
  	{
  		$fields = array();
  		$fields['no_asuransi'] = array(
	        	'type' => 'char',
	        	'constraint' => 100,
	        	'null' => TRUE,
	        	'default' => NULL
	        );
			$this->dbforge->add_column('tweb_penduduk', $fields);
  	}
  	if (!$this->db->field_exists('updated_at', 'komentar'))
  	{
			$this->dbforge->add_column("komentar", "updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
		}
		// Tambah setting server untuk menentukan setting modul default
		$query = $this->db->select('1')->where('key', 'penggunaan_server')->get('setting_aplikasi');
		$query->result() OR	$this->db->insert('setting_aplikasi', array('key'=>'penggunaan_server', 'value'=>'1	', 'jenis'=>'int', 'keterangan'=>"Setting penggunaan server", 'kategori'=>'sistem'));
		// Tambah controller yg merupakan submodul yg tidak tampil di menu utama
		$modul_nonmenu = array(
			'id' => '65',
			'modul' => 'Kategori',
			'url' => 'kategori',
			'aktif' => '1',
			'ikon' => '',
			'urut' => '',
			'level' => '',
			'parent' => '49',
			'hidden' => '2',
			'ikon_kecil' => ''
		);
		$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
		$this->db->query($sql);
		$modul_nonmenu = array(
			'id' => '66',
			'modul' => 'Log Penduduk',
			'url' => 'penduduk_log',
			'aktif' => '1',
			'ikon' => '',
			'urut' => '',
			'level' => '',
			'parent' => '21',
			'hidden' => '2',
			'ikon_kecil' => ''
		);
		$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
		$this->db->query($sql);
		$submodul_analisis = array('67'=>'analisis_kategori', '68'=>'analisis_indikator', '69'=>'analisis_klasifikasi', '70'=>'analisis_periode', '71'=>'analisis_respon', '72'=>'analisis_laporan', '73'=>'analisis_statistik_jawaban');
		foreach ($submodul_analisis as $key => $submodul)
		{
			$modul_nonmenu = array(
				'id' => $key,
				'modul' => $submodul,
				'url' => $submodul,
				'aktif' => '1',
				'ikon' => '',
				'urut' => '',
				'level' => '',
				'parent' => '5',
				'hidden' => '2',
				'ikon_kecil' => ''
			);
			$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
			$this->db->query($sql);
		}
		$modul_nonmenu = array(
			'id' => '74',
			'modul' => 'Wilayah',
			'url' => 'wilayah',
			'aktif' => '1',
			'ikon' => '',
			'urut' => '',
			'level' => '',
			'parent' => '21',
			'hidden' => '2',
			'ikon_kecil' => ''
		);
		$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
		$this->db->query($sql);
		$this->db->where('id', 2)->update('setting_modul', array('url'=>'', 'aktif'=>'1'));
		$submodul_inventaris = array('75'=>'api_inventaris_asset', '76'=>'api_inventaris_gedung', '77'=>'api_inventaris_gedung', '78'=>'api_inventaris_jalan', '79'=>'api_inventaris_konstruksi', '80'=>'api_inventaris_peralatan', '81'=>'api_inventaris_tanah', '82'=>'inventaris_asset', '83'=>'inventaris_gedung', '84'=>'inventaris_jalan', '85'=>'inventaris_kontruksi', '86'=>'inventaris_peralatan', '87'=>'laporan_inventaris');
		foreach ($submodul_inventaris as $key => $submodul)
		{
			$modul_nonmenu = array(
				'id' => $key,
				'modul' => $submodul,
				'url' => $submodul,
				'aktif' => '1',
				'ikon' => '',
				'urut' => '',
				'level' => '',
				'parent' => '61',
				'hidden' => '2',
				'ikon_kecil' => ''
			);
			$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
			$this->db->query($sql);
		}

	  // Ubah id rtm supaya bisa lebih panjang
	  $sql = "ALTER TABLE `tweb_rtm` CHANGE `no_kk` `no_kk` VARCHAR(30) NOT NULL";
	  $this->db->query($sql);
	  $sql = "ALTER TABLE `tweb_penduduk` CHANGE `id_rtm` `id_rtm` VARCHAR(30) NOT NULL";
	  $this->db->query($sql);
	  $sql = "ALTER TABLE `program_peserta` CHANGE `peserta` `peserta` VARCHAR(30) NOT NULL";
	  $this->db->query($sql);
	  $sql = "ALTER TABLE `program_peserta` CHANGE `kartu_nik` `kartu_nik` VARCHAR(30) NOT NULL";
	  $this->db->query($sql);

	  // ubah/perbaiki struktur database, table artikel
	  $this->db->query('ALTER TABLE artikel MODIFY gambar VARCHAR(200) DEFAULT NULL;');
	  $this->db->query('ALTER TABLE artikel MODIFY gambar1 VARCHAR(200) DEFAULT NULL;');
	  $this->db->query('ALTER TABLE artikel MODIFY gambar2 VARCHAR(200) DEFAULT NULL;');
	  $this->db->query('ALTER TABLE artikel MODIFY gambar3 VARCHAR(200) DEFAULT NULL;');
	  $this->db->query('ALTER TABLE artikel MODIFY dokumen VARCHAR(400) DEFAULT NULL;');
	  $this->db->query('ALTER TABLE artikel MODIFY link_dokumen VARCHAR(200) DEFAULT NULL;');

		// Hapus kolom artikel tidak digunakan
  	if ($this->db->field_exists('jenis_widget', 'artikel'))
  	{
			$this->dbforge->drop_column('artikel', 'jenis_widget');
  	}
  }


	private function modul_keuangan()
	{
		// Penambahan widget keuangan
		$widget = $this->db->select('id, isi')->where('isi', 'keuangan.php')->get('widget')->row();
		if (empty($widget))
		{
			$query = "
				INSERT INTO widget (`isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES
				('keuangan.php', '1', 'Keuangan', '1', '15', 'keuangan/widget', '');
			";
			$this->db->query($query);
		}
		// Tambah menu navigasi untuk keuangan
		$query = "
			INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `parent`, `hidden`, `ikon_kecil`) VALUES
			('201', 'Keuangan', 'keuangan', '1', 'fa-balance-scale', '6', '2', '0', '0', 'fa-balance-scale'),
			('202', 'Impor Data', 'keuangan/impor_data', '1', 'fa-cloud-upload', '6', '2', '201', '0', 'fa-cloud-upload'),
			('203', 'Laporan', 'keuangan/laporan', '1', 'fa-bar-chart', '6', '2', '201', '0', 'fa-bar-chart')
			ON DUPLICATE KEY UPDATE url = VALUES(url);
		";
		$this->db->query($query);
		$this->data_siskeudes();
		$this->data_siskeudes_2018();
	}

	private function data_siskeudes_2018()
	{
  	if ($this->db->field_exists('Alamat_Pemilik', 'keuangan_ref_bank_desa')) return;

		// Tambah kolom
		$fields = array();
		$fields['Kantor_Cabang'] = array('type' => 'VARCHAR', 'constraint' => 13);
		$fields['Nama_Pemilik'] = array('type' => 'VARCHAR', 'constraint' => 21);
		$fields['Alamat_Pemilik'] = array('type' => 'VARCHAR', 'constraint' => 12);
		$fields['No_Identitas'] = array('type' => 'INT');
		$fields['No_Telepon'] = array('type' => 'INT');
		$this->dbforge->add_column('keuangan_ref_bank_desa', $fields);

	}

	private function data_siskeudes()
	{
		//insert tabel2 untuk keuangan
		if (!$this->db->table_exists('keuangan_master') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_master` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`versi_database` varchar(50) NOT NULL,
				`tahun_anggaran` varchar(250) NOT NULL,
				`aktif` int(2) NOT NULL DEFAULT '1',
				`tanggal_impor` date NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_bank_desa
		if (!$this->db->table_exists('keuangan_ref_bank_desa') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_bank_desa` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(50) NOT NULL,
				`Kd_Desa` varchar(50) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`NoRek_Bank` varchar(100) NOT NULL,
				`Nama_Bank` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_bel_operasional
		if (!$this->db->table_exists('keuangan_ref_bel_operasional') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_bel_operasional` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`ID_Keg` varchar(50) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_bidang
		if (!$this->db->table_exists('keuangan_ref_bidang') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_bidang` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Bid` varchar(50) NOT NULL,
				`Nama_Bidang` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_bunga
		if (!$this->db->table_exists('keuangan_ref_bunga') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_bunga` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Bunga` varchar(50) NOT NULL,
				`Kd_Admin` varchar(50) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_desa
		if (!$this->db->table_exists('keuangan_ref_desa') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_desa` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Kec` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Nama_Desa` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_kecamatan
		if (!$this->db->table_exists('keuangan_ref_kecamatan') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_kecamatan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Kec` varchar(100) NOT NULL,
				`Nama_Kecamatan` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_kegiatan
		if (!$this->db->table_exists('keuangan_ref_kegiatan') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_kegiatan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Bid` varchar(100) NOT NULL,
				`ID_Keg` varchar(100) NOT NULL,
				`Nama_Kegiatan` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_korolari
		if (!$this->db->table_exists('keuangan_ref_korolari') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_korolari` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Kd_RekDB` varchar(100) NOT NULL,
				`Kd_RekKD` varchar(250) NOT NULL,
				`Jenis` int(11) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_neraca_close
		if (!$this->db->table_exists('keuangan_ref_neraca_close') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_neraca_close` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Kelompok` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_perangkat
		if (!$this->db->table_exists('keuangan_ref_perangkat') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_perangkat` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kode` varchar(100) NOT NULL,
				`Nama_Perangkat` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_potongan
		if (!$this->db->table_exists('keuangan_ref_potongan') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_potongan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Kd_Potongan` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_potongan
		if (!$this->db->table_exists('keuangan_ref_rek1') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_rek1` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Akun` varchar(100) NOT NULL,
				`Nama_Akun` varchar(100) NOT NULL,
				`NoLap` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_rek2
		if (!$this->db->table_exists('keuangan_ref_rek2') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_rek2` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Akun` varchar(100) NOT NULL,
				`Kelompok` varchar(100) NOT NULL,
				`Nama_Kelompok` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_rek3
		if (!$this->db->table_exists('keuangan_ref_rek3') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_rek3` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kelompok` varchar(100) NOT NULL,
				`Jenis` varchar(100) NOT NULL,
				`Nama_Jenis` varchar(100) NOT NULL,
				`Formula`int(11) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_rek4
		if (!$this->db->table_exists('keuangan_ref_rek4') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_rek4` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Jenis` varchar(100) NOT NULL,
				`Obyek` varchar(100) NOT NULL,
				`Nama_Obyek` varchar(100) NOT NULL,
				`Peraturan` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_sbu
		if (!$this->db->table_exists('keuangan_ref_sbu') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_sbu` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Kode_SBU` varchar(100) NOT NULL,
				`NoUrut_SBU` varchar(100) NOT NULL,
				`Nama_SBU` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				`Satuan` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ref_sumber
		if (!$this->db->table_exists('keuangan_ref_sumber') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ref_sumber` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kode` varchar(100) NOT NULL,
				`Nama_Sumber` varchar(100) NOT NULL,
				`Urut` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_anggaran
		if (!$this->db->table_exists('keuangan_ta_anggaran') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_anggaran` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`KdPosting` varchar(100) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`KURincianSD` varchar(100) NOT NULL,
				`KD_Rincian` varchar(100) NOT NULL,
				`RincianSD` varchar(100) NOT NULL,
				`Anggaran` varchar(100) NOT NULL,
				`AnggaranPAK` varchar(100) NOT NULL,
				`AnggaranStlhPAK` varchar(100) NOT NULL,
				`Belanja` varchar(100) NOT NULL,
				`Kd_keg` varchar(100) NOT NULL,
				`SumberDana` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`TglPosting` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert  keuangan_ta_anggaran_log
		if (!$this->db->table_exists(' keuangan_ta_anggaran_log') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_anggaran_log` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`KdPosting` varchar(100) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_Perdes` varchar(100) NOT NULL,
				`TglPosting` varchar(100) NOT NULL,
				`UserID` int(11) NOT NULL,
				`Kunci` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_anggaran_rinci
		if (!$this->db->table_exists('keuangan_ta_anggaran_rinci') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_anggaran_rinci` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`KdPosting` varchar(100) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Kd_SubRinci` varchar(100) NOT NULL,
				`No_Urut` varchar(100) NOT NULL,
				`Uraian` varchar(100) NOT NULL,
				`SumberDana` varchar(100) NOT NULL,
				`JmlSatuan` varchar(100) NOT NULL,
				`HrgSatuan` varchar(100) NOT NULL,
				`Satuan` varchar(100) NOT NULL,
				`Anggaran` varchar(100) NOT NULL,
				`JmlSatuanPAK` varchar(100) NOT NULL,
				`HrgSatuanPAK` varchar(100) NOT NULL,
				`AnggaranStlhPAK` varchar(100) NOT NULL,
				`AnggaranPAK` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_bidang
		if (!$this->db->table_exists('keuangan_ta_bidang') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_bidang` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Bid` varchar(100) NOT NULL,
				`Nama_Bidang` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_desa
		if (!$this->db->table_exists('keuangan_ta_desa') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_desa` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Nm_Kades` varchar(100) NOT NULL,
				`Jbt_Kades` varchar(100) NOT NULL,
				`Nm_Sekdes` varchar(100) NOT NULL,
				`NIP_Sekdes` varchar(100) NOT NULL,
				`Jbt_Sekdes` varchar(100) NOT NULL,
				`Nm_Kaur_Keu` varchar(100) NOT NULL,
				`Jbt_Kaur_Keu` varchar(100) NOT NULL,
				`Nm_Bendahara` varchar(100) NOT NULL,
				`Jbt_Bendahara` varchar(100) NOT NULL,
				`No_Perdes` varchar(100) NOT NULL,
				`Tgl_Perdes` varchar(100) NOT NULL,
				`No_Perdes_PB` varchar(100) NOT NULL,
				`Tgl_Perdes_PB` varchar(100) NOT NULL,
				`No_Perdes_PJ` varchar(100) NOT NULL,
				`Tgl_Perdes_PJ` varchar(100) NOT NULL,
				`Alamat` varchar(250) NOT NULL,
				`Ibukota` varchar(100) NOT NULL,
				`Status` varchar(100) NOT NULL,
				`NPWP` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_jurnal_umum
		if (!$this->db->table_exists('keuangan_ta_jurnal_umum') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_jurnal_umum` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`KdBuku` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Tanggal` varchar(100) NOT NULL,
				`JnsBukti` varchar(100) NOT NULL,
				`NoBukti` varchar(100) NOT NULL,
				`Keterangan` varchar(100) NOT NULL,
				`DK` varchar(100) NOT NULL,
				`Debet` varchar(100) NOT NULL,
				`Kredit` varchar(100) NOT NULL,
				`Jenis` varchar(100) NOT NULL,
				`Posted` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_jurnal_umum_rinci
		if (!$this->db->table_exists('keuangan_ta_jurnal_umum_rinci') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_jurnal_umum_rinci` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`NoBukti` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`RincianSD` varchar(100) NOT NULL,
				`NoID` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Akun` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Sumberdana` varchar(100) NOT NULL,
				`DK` varchar(100) NOT NULL,
				`Debet` varchar(100) NOT NULL,
				`Kredit` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_kegiatan
		if (!$this->db->table_exists('keuangan_ta_kegiatan') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_kegiatan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Bid` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`ID_Keg` varchar(100) NOT NULL,
				`Nama_Kegiatan` varchar(100) NOT NULL,
				`Pagu` varchar(100) NOT NULL,
				`Pagu_PAK` varchar(100) NOT NULL,
				`Nm_PPTKD` varchar(100) NOT NULL,
				`NIP_PPTKD` varchar(100) NOT NULL,
				`Lokasi` varchar(100) NOT NULL,
				`Waktu` varchar(100) NOT NULL,
				`Keluaran` varchar(100) NOT NULL,
				`Sumberdana` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_mutasi
		if (!$this->db->table_exists('keuangan_ta_mutasi') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_mutasi` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`Tgl_Bukti` varchar(100) NOT NULL,
				`Keterangan` varchar(100) NOT NULL,
				`Kd_Bank` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Sumberdana` varchar(100) NOT NULL,
				`Kd_Mutasi` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_pajak
		if (!$this->db->table_exists('keuangan_ta_pajak') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_pajak` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_SSP` varchar(100) NOT NULL,
				`Tgl_SSP` varchar(100) NOT NULL,
				`Keterangan` varchar(100) NOT NULL,
				`Nama_WP` varchar(100) NOT NULL,
				`Alamat_WP` varchar(100) NOT NULL,
				`NPWP` varchar(100) NOT NULL,
				`Kd_MAP` varchar(100) NOT NULL,
				`Nm_Penyetor` varchar(100) NOT NULL,
				`Jn_Transaksi` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Jumlah` varchar(100) NOT NULL,
				`KdBayar` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_pajak_rinci
		if (!$this->db->table_exists('keuangan_ta_pajak_rinci') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_pajak_rinci` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_SSP` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_pemda
		if (!$this->db->table_exists('keuangan_ta_pemda') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_pemda` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Prov` varchar(100) NOT NULL,
				`Kd_Kab` varchar(100) NOT NULL,
				`Nama_Pemda` varchar(100) NOT NULL,
				`Nama_Provinsi` varchar(100) NOT NULL,
				`Ibukota` varchar(100) NOT NULL,
				`Alamat` varchar(100) NOT NULL,
				`Nm_Bupati` varchar(100) NOT NULL,
				`Jbt_Bupati` varchar(100) NOT NULL,
				`Logo` varchar(100) NOT NULL,
				`C_Kode` varchar(100) NOT NULL,
				`C_Pemda` varchar(100) NOT NULL,
				`C_Data` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_pencairan
		if (!$this->db->table_exists('keuangan_ta_pencairan') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_pencairan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`No_Cek` varchar(100) NOT NULL,
				`No_SPP` varchar(100) NOT NULL,
				`Tgl_Cek` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Keterangan` varchar(100) NOT NULL,
				`Jumlah` varchar(100) NOT NULL,
				`Potongan` varchar(100) NOT NULL,
				`KdBayar` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_perangkat
		if (!$this->db->table_exists('keuangan_ta_perangkat') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_perangkat` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Jabatan` varchar(100) NOT NULL,
				`No_ID` varchar(100) NOT NULL,
				`Nama_Perangkat` varchar(100) NOT NULL,
				`Alamat_Perangkat` varchar(100) NOT NULL,
				`Nomor_HP` varchar(100) NOT NULL,
				`Rek_Bank` varchar(100) NOT NULL,
				`Nama_Bank` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rab
		if (!$this->db->table_exists('keuangan_ta_rab') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rab` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Anggaran` varchar(100) NOT NULL,
				`AnggaranPAK` varchar(100) NOT NULL,
				`AnggaranStlhPAK` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rab_rinci
		if (!$this->db->table_exists('keuangan_ta_rab_rinci') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rab_rinci` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Kd_SubRinci` varchar(100) NOT NULL,
				`No_Urut` varchar(100) NOT NULL,
				`SumberDana` varchar(100) NOT NULL,
				`Uraian` varchar(100) NOT NULL,
				`Satuan` varchar(100) NOT NULL,
				`JmlSatuan` varchar(100) NOT NULL,
				`HrgSatuan` varchar(100) NOT NULL,
				`Anggaran` varchar(100) NOT NULL,
				`JmlSatuanPAK` varchar(100) NOT NULL,
				`HrgSatuanPAK` varchar(100) NOT NULL,
				`AnggaranStlhPAK` varchar(100) NOT NULL,
				`AnggaranPAK` varchar(100) NOT NULL,
				`Kode_SBU` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rab_sub
		if (!$this->db->table_exists('keuangan_ta_rab_sub') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rab_sub` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Kd_SubRinci` varchar(100) NOT NULL,
				`Nama_SubRinci` varchar(100) NOT NULL,
				`Anggaran` varchar(100) NOT NULL,
				`AnggaranPAK` varchar(100) NOT NULL,
				`AnggaranStlhPAK` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rpjm_bidang
		if (!$this->db->table_exists('keuangan_ta_rpjm_bidang') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rpjm_bidang` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Bid` varchar(100) NOT NULL,
				`Nama_Bidang` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rpjm_kegiatan
		if (!$this->db->table_exists('keuangan_ta_rpjm_kegiatan') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rpjm_kegiatan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Bid` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`ID_Keg` varchar(100) NOT NULL,
				`Nama_Kegiatan` varchar(100) NOT NULL,
				`Lokasi` varchar(100) NOT NULL,
				`Keluaran` varchar(100) NOT NULL,
				`Kd_Sas` varchar(100) NOT NULL,
				`Sasaran` varchar(100) NOT NULL,
				`Tahun1` varchar(100) NOT NULL,
				`Tahun2` varchar(100) NOT NULL,
				`Tahun3` varchar(100) NOT NULL,
				`Tahun4` varchar(100) NOT NULL,
				`Tahun5` varchar(100) NOT NULL,
				`Tahun6` varchar(100) NOT NULL,
				`Swakelola` varchar(100) NOT NULL,
				`Kerjasama` varchar(100) NOT NULL,
				`Pihak_Ketiga` varchar(100) NOT NULL,
				`Sumberdana` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rpjm_misi
		if (!$this->db->table_exists('keuangan_ta_rpjm_misi') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rpjm_misi` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`ID_Misi` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`ID_Visi` varchar(100) NOT NULL,
				`No_Misi` varchar(100) NOT NULL,
				`Uraian_Misi` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rpjm_pagu_indikatif
		if (!$this->db->table_exists('keuangan_ta_rpjm_pagu_indikatif') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rpjm_pagu_indikatif` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Sumber` varchar(100) NOT NULL,
				`Tahun1` varchar(100) NOT NULL,
				`Tahun2` varchar(100) NOT NULL,
				`Tahun3` varchar(100) NOT NULL,
				`Tahun4` varchar(100) NOT NULL,
				`Tahun5` varchar(100) NOT NULL,
				`Tahun6` varchar(100) NOT NULL,
				`Pola` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rpjm_pagu_tahunan
		if (!$this->db->table_exists('keuangan_ta_rpjm_pagu_tahunan') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rpjm_pagu_tahunan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Tahun` varchar(100) NOT NULL,
				`Kd_Sumber` varchar(100) NOT NULL,
				`Biaya` varchar(100) NOT NULL,
				`Volume` varchar(100) NOT NULL,
				`Satuan` varchar(100) NOT NULL,
				`Lokasi_Spesifik` varchar(100) NOT NULL,
				`Jml_Sas_Pria` varchar(100) NOT NULL,
				`Jml_Sas_Wanita` varchar(100) NOT NULL,
				`Jml_Sas_ARTM` varchar(100) NOT NULL,
				`Waktu` varchar(100) NOT NULL,
				`Mulai` varchar(100) NOT NULL,
				`Selesai` varchar(100) NOT NULL,
				`Pola_Kegiatan` varchar(100) NOT NULL,
				`Pelaksana` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rpjm_sasaran
		if (!$this->db->table_exists('keuangan_ta_rpjm_sasaran') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rpjm_sasaran` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`ID_Sasaran` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`ID_Tujuan` varchar(100) NOT NULL,
				`No_Sasaran` varchar(100) NOT NULL,
				`Uraian_Sasaran` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rpjm_tujuan
		if (!$this->db->table_exists('keuangan_ta_rpjm_tujuan') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rpjm_tujuan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`ID_Tujuan` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`ID_Misi` varchar(100) NOT NULL,
				`No_Tujuan` varchar(100) NOT NULL,
				`Uraian_Tujuan` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_rpjm_visi
		if (!$this->db->table_exists('keuangan_ta_rpjm_visi') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_rpjm_visi` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`ID_Visi` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_Visi` varchar(100) NOT NULL,
				`Uraian_Visi` varchar(100) NOT NULL,
				`TahunA` varchar(100) NOT NULL,
				`TahunN` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_saldo_awal
		if (!$this->db->table_exists('keuangan_ta_saldo_awal') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_saldo_awal` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Jenis` varchar(100) NOT NULL,
				`Anggaran` varchar(100) NOT NULL,
				`Debet` varchar(100) NOT NULL,
				`Kredit` varchar(100) NOT NULL,
				`Tgl_Bukti` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_spj
		if (!$this->db->table_exists('keuangan_ta_spj') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_spj` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`No_SPJ` varchar(100) NOT NULL,
				`Tgl_SPJ` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_SPP` varchar(100) NOT NULL,
				`Keterangan` varchar(100) NOT NULL,
				`Jumlah` varchar(100) NOT NULL,
				`Potongan` varchar(100) NOT NULL,
				`Status` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_spjpot
		if (!$this->db->table_exists('keuangan_ta_spjpot') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_spjpot` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_SPJ` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_spj_bukti
		if (!$this->db->table_exists('keuangan_ta_spj_bukti') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_spj_bukti` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`No_SPJ` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`Tgl_Bukti` varchar(100) NOT NULL,
				`Sumberdana` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Nm_Penerima` varchar(100) NOT NULL,
				`Alamat` varchar(100) NOT NULL,
				`Rek_Bank` varchar(100) NOT NULL,
				`Nm_Bank` varchar(100) NOT NULL,
				`NPWP` varchar(100) NOT NULL,
				`Keterangan` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_spj_rinci
		if (!$this->db->table_exists('keuangan_ta_spj_rinci') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_spj_rinci` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`No_SPJ` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Sumberdana` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_SPP` varchar(100) NOT NULL,
				`JmlCair` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				`Alamat` varchar(100) NOT NULL,
				`Sisa` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_spj_sisa
		if (!$this->db->table_exists('keuangan_ta_spj_sisa') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_spj_sisa` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`Tgl_Bukti` varchar(100) NOT NULL,
				`No_SPJ` varchar(100) NOT NULL,
				`Tgl_SPJ` varchar(100) NOT NULL,
				`No_SPP` varchar(100) NOT NULL,
				`Tgl_SPP` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Keterangan` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_spp
		if (!$this->db->table_exists('keuangan_ta_spp') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_spp` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`No_SPP` varchar(100) NOT NULL,
				`Tgl_SPP` varchar(100) NOT NULL,
				`Jn_SPP` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Keterangan` varchar(100) NOT NULL,
				`Jumlah` varchar(100) NOT NULL,
				`Potongan` varchar(100) NOT NULL,
				`Status` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_spp_rinci
		if (!$this->db->table_exists('keuangan_ta_spp_rinci') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_spp_rinci` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_SPP` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Sumberdana` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_sppbukti
		if (!$this->db->table_exists('keuangan_ta_sppbukti') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_sppbukti` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_SPP` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Sumberdana` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`Tgl_Bukti` varchar(100) NOT NULL,
				`Nm_Penerima` varchar(100) NOT NULL,
				`Alamat` varchar(100) NOT NULL,
				`Rek_Bank` varchar(100) NOT NULL,
				`Nm_Bank` varchar(100) NOT NULL,
				`NPWP` varchar(100) NOT NULL,
				`Keterangan` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_spppot
		if (!$this->db->table_exists('keuangan_ta_spppot') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_spppot` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_SPP` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_sts
		if (!$this->db->table_exists('keuangan_ta_sts') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_sts` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`Tgl_Bukti` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Uraian` varchar(100) NOT NULL,
				`NoRek_Bank` varchar(100) NOT NULL,
				`Nama_Bank` varchar(100) NOT NULL,
				`Jumlah` varchar(100) NOT NULL,
				`Nm_Bendahara` varchar(100) NOT NULL,
				`Jbt_Bendahara` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_sts_rinci
		if (!$this->db->table_exists('keuangan_ta_sts_rinci') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_sts_rinci` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`No_TBP` varchar(100) NOT NULL,
				`Uraian` varchar(100) NOT NULL,
				`Nilai` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_tbp
		if (!$this->db->table_exists('keuangan_ta_tbp') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_tbp` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`Tgl_Bukti` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Uraian` varchar(100) NOT NULL,
				`Nm_Penyetor` varchar(100) NOT NULL,
				`Alamat_Penyetor` varchar(100) NOT NULL,
				`TTD_Penyetor` varchar(100) NOT NULL,
				`NoRek_Bank` varchar(100) NOT NULL,
				`Nama_Bank` varchar(100) NOT NULL,
				`Jumlah` varchar(100) NOT NULL,
				`Nm_Bendahara` varchar(100) NOT NULL,
				`Jbt_Bendahara` varchar(100) NOT NULL,
				`Status` varchar(100) NOT NULL,
				`KdBayar` varchar(100) NOT NULL,
				`Ref_Bayar` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_tbp_rinci
		if (!$this->db->table_exists('keuangan_ta_tbp_rinci') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_tbp_rinci` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`No_Bukti` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`RincianSD` varchar(100) NOT NULL,
				`SumberDana` varchar(100) NOT NULL,
				`nilai` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_triwulan
		if (!$this->db->table_exists('keuangan_ta_triwulan') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_triwulan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`KURincianSD` varchar(100) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Sifat` varchar(100) NOT NULL,
				`SumberDana` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Anggaran` varchar(100) NOT NULL,
				`AnggaranPAK` varchar(100) NOT NULL,
				`Tw1Rinci` varchar(100) NOT NULL,
				`Tw2Rinci` varchar(100) NOT NULL,
				`Tw3Rinci` varchar(100) NOT NULL,
				`Tw4Rinci` varchar(100) NOT NULL,
				`KunciData` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}

		//insert keuangan_ta_triwulan_rinci
		if (!$this->db->table_exists('keuangan_ta_triwulan_rinci') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `keuangan_ta_triwulan_rinci` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_keuangan_master` int(11) NOT NULL,
				`KdPosting` varchar(100) NOT NULL,
				`KURincianSD` varchar(100) NOT NULL,
				`Tahun` varchar(100) NOT NULL,
				`Sifat` varchar(100) NOT NULL,
				`SumberDana` varchar(100) NOT NULL,
				`Kd_Desa` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Anggaran` varchar(100) NOT NULL,
				`AnggaranPAK` varchar(100) NOT NULL,
				`Tw1Rinci` varchar(100) NOT NULL,
				`Tw2Rinci` varchar(100) NOT NULL,
				`Tw3Rinci` varchar(100) NOT NULL,
				`Tw4Rinci` varchar(100) NOT NULL,
				`KunciData` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}
	}

}
