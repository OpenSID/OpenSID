<?php
class Migrasi_2004_ke_2005 extends CI_model {

	public function up()
	{
		$this->covid19();
		$this->covid19Monitoring();
		// MODUL BARU END

		// Penyesuaian url menu dgn submenu setelah hapus file sekretariat.php
		$this->db->where('id', 15)
			->set('url', 'surat_keluar/clear')
			->update('setting_modul');
	  	// Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
		$this->db->query("ALTER TABLE kelompok_anggota MODIFY COLUMN no_anggota VARCHAR(20) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE inventaris_kontruksi MODIFY COLUMN kontruksi_beton TINYINT(1) DEFAULT 0");
		$this->db->query("ALTER TABLE mutasi_inventaris_asset MODIFY COLUMN harga_jual DOUBLE NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_asset MODIFY COLUMN sumbangkan VARCHAR(255) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_gedung MODIFY COLUMN harga_jual DOUBLE NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_gedung MODIFY COLUMN sumbangkan VARCHAR(255) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_jalan MODIFY COLUMN harga_jual DOUBLE NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_jalan MODIFY COLUMN sumbangkan VARCHAR(255) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_peralatan MODIFY COLUMN harga_jual DOUBLE NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_peralatan MODIFY COLUMN sumbangkan VARCHAR(255) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_tanah MODIFY COLUMN harga_jual DOUBLE NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_tanah MODIFY COLUMN sumbangkan VARCHAR(255) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE polygon MODIFY COLUMN tipe INT(4) NULL DEFAULT 0");
		// Perbaiki nama aset salah
		$this->db->where('id_aset', 3423)->update('tweb_aset', array('nama' => 'JALAN'));
		$this->db->where('id', 79)->update('setting_modul', array('url'=>'api_inventaris_kontruksi', 'aktif'=>'1'));
		// Hapus field urut di tabel artikel krn tdk dibutuhkan
		if ($this->db->field_exists('urut', 'artikel'))
			$this->db->query('ALTER TABLE `artikel` DROP COLUMN `urut`');
		// Tambahkan field tipe di tabel media_sosial
		if (!$this->db->field_exists('tipe', 'media_sosial')){
			$this->db->query('ALTER TABLE media_sosial ADD COLUMN tipe TINYINT(1) NULL DEFAULT 1 AFTER nama');
		}
		// Tambah media sosial telegram
		$this->db->query('ALTER TABLE media_sosial MODIFY COLUMN link TEXT NULL');
		$data = array(
			'id' => '7',
			'gambar' => 'tg.png',
			'nama' => 'Telegram',
			'tipe' => '1',
			'enabled' => '2'
			);
		$sql = $this->db->insert_string('media_sosial', $data);
		$sql .= " ON DUPLICATE KEY UPDATE
				gambar = VALUES(gambar),
				nama = VALUES(nama),
				tipe = VALUES(tipe),
				enabled = VALUES(enabled)
				";
		$this->db->query($sql);

		// tambah field id_parent di tabel dokumen untuk merelasikan dokumen bersama anggota dengan kepala KK
		if (!$this->db->field_exists('id_parent', 'dokumen'))
		{
			$this->db->query('ALTER TABLE dokumen ADD COLUMN id_parent INT(11) NULL DEFAULT NULL AFTER id_syarat');
		}
		// Perbaharui view (digunakan juga untuk tambah field id_parent)
		$this->db->query("DROP VIEW dokumen_hidup");
		$this->db->query("CREATE VIEW dokumen_hidup AS SELECT * FROM dokumen WHERE deleted <> 1");
	}

	private function covid19()
	{
		// Menambahkan menu 'Group / Hak Akses' ke table 'setting_modul'
		$data[] = array(
			'id'=>'206',
			'modul' => 'Siaga Covid-19',
			'url' => 'covid19',
			'aktif' => '1',
			'ikon' => 'fa-heartbeat',
			'urut' => '0',
			'level' => '2',
			'hidden' => '0',
			'ikon_kecil' => 'fa fa-heartbeat',
			'parent' => 0
		);

    foreach ($data as $modul)
    {
			$sql = $this->db->insert_string('setting_modul', $modul);
			$sql .= " ON DUPLICATE KEY UPDATE
			id = VALUES(id),
			modul = VALUES(modul),
			url = VALUES(url),
			aktif = VALUES(aktif),
			ikon = VALUES(ikon),
			urut = VALUES(urut),
			level = VALUES(level),
			hidden = VALUES(hidden),
			ikon_kecil = VALUES(ikon_kecil),
			parent = VALUES(parent)";
			$this->db->query($sql);
    }

    // Tambah Tabel Covid-19
    if (!$this->db->table_exists('covid19_pemudik') )
		{
    	$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'null' => FALSE,
					'auto_increment' => TRUE
				),
				'id_terdata' => array(
					'type' => 'INT',
					'constraint' => 11,
					'null' => TRUE,
				),
				'tanggal_datang' => array(
					'type' => 'DATE',
					'null' => TRUE,
				),
				'asal_mudik' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
					'null' => TRUE,
				),
				'durasi_mudik' => array(
					'type' => 'VARCHAR',
					'constraint' => 50,
					'null' => TRUE,
				),
				'tujuan_mudik' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
					'null' => TRUE,
				),
				'keluhan_kesehatan' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
					'null' => TRUE,
				),
				'status_covid' => array(
					'type' => 'VARCHAR',
					'constraint' => 50,
					'null' => TRUE,
				),
				'no_hp' => array(
					'type' => 'VARCHAR',
					'constraint' => 20,
					'null' => TRUE,
				),
				'email' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
					'null' => TRUE,
				),
				'keterangan' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
					'null' => TRUE,
				),
			));
			$this->dbforge->add_key("id",true);
			$this->dbforge->create_table("covid19_pemudik", TRUE);
		}

		// add relational constraint antara covid19_pemudik dan tweb_penduduk
		if ($this->db->field_exists('id_terdata', 'covid19_pemudik'))
		{
			$this->dbforge->modify_column('covid19_pemudik', array(
				'id_terdata' => array(
					'name' => 'id_terdata',
					'type' => 'INT',
					'constraint' => 11
				)
			));
		}

		$query = $this->db->from('INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS')
			->where('CONSTRAINT_NAME', 'fk_pemudik_penduduk')
			->where('TABLE_NAME', 'covid19_pemudik')
			->get();
	  if ($query->num_rows() == 0)
	  {
			$this->dbforge->add_column('covid19_pemudik', array(
				"CONSTRAINT `fk_pemudik_penduduk` FOREIGN KEY (`id_terdata`) REFERENCES `tweb_penduduk`(`id`) ON DELETE CASCADE ON UPDATE CASCADE"
			));
		}

	}

	private function covid19Monitoring()
	{
		// Update Menu Siaga Covid-19 Menjadi Menu Parent
		$this->db->where('id', 206)
			->set('url', '')
			->update('setting_modul');


		// Tambah field wajib pantau di pemudik
  	if (!$this->db->field_exists('is_wajib_pantau', 'covid19_pemudik'))
  	{
			$this->dbforge->add_column('covid19_pemudik', array(
				'is_wajib_pantau' => array(
					'type' => 'VARCHAR',
					'constraint' => 20,
					'null' => TRUE,
				),
			));
  	}

		// Add Menu Child 'Pendataan' & 'Pemantauan'
		$data = array();
		$data[] = array(
			'id'=>'207',
			'modul' => 'Pendataan',
			'url' => 'covid19',
			'aktif' => '1',
			'ikon' => 'fa-list',
			'urut' => '1',
			'level' => '2',
			'hidden' => '0',
			'ikon_kecil' => 'fa fa-list',
			'parent' => 206);

		$data[] = array(
		'id'=>'208',
		'modul' => 'Pemantauan',
		'url' => 'covid19/pantau',
		'aktif' => '1',
		'ikon' => 'fa-check',
		'urut' => '2',
		'level' => '2',
		'hidden' => '0',
		'ikon_kecil' => 'fa fa-check',
		'parent' => 206);

		foreach ($data as $modul)
		{
			$sql = $this->db->insert_string('setting_modul', $modul);

			$sql .= " ON DUPLICATE KEY UPDATE
			id = VALUES(id),
			modul = VALUES(modul),
			url = VALUES(url),
			aktif = VALUES(aktif),
			ikon = VALUES(ikon),
			urut = VALUES(urut),
			level = VALUES(level),
			hidden = VALUES(hidden),
			ikon_kecil = VALUES(ikon_kecil),
			parent = VALUES(parent)";
			$this->db->query($sql);
		}

		// Tambah Tabel Pemantauan Covid-19
		if (!$this->db->table_exists('covid19_pantau'))
		{
			$this->dbforge->add_field(array(
				'id' => array(
				'type' => 'INT',
					'constraint' => 11,
					'null' => FALSE,
					'auto_increment' => TRUE
				),
				'id_pemudik' => array(
					'type' => 'INT',
					'constraint' => 11,
					'null' => TRUE,
				),
				'tanggal_jam' => array(
					'type' => 'DATETIME',
					'null' => TRUE,
				),
				'suhu_tubuh' => array(
					'type' => 'DECIMAL',
					'constraint' => '4,2',
					'null' => TRUE,
				),
				'batuk' => array(
					'type' => 'VARCHAR',
					'constraint' => 20,
					'null' => TRUE,
				),
				'flu' => array(
					'type' => 'VARCHAR',
					'constraint' => 20,
					'null' => TRUE,
				),
				'sesak_nafas' => array(
					'type' => 'VARCHAR',
					'constraint' => 20,
					'null' => TRUE,
				),
				'keluhan_lain' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
					'null' => TRUE,
				),
				'status_covid' => array(
					'type' => 'VARCHAR',
					'constraint' => 50,
					'null' => TRUE,
				),
			));
			$this->dbforge->add_key("id",true);
			$this->dbforge->create_table("covid19_pantau", TRUE);
		}

		// add relational constraint antara covid19_pantau dan covid19_pemudik
		if ($this->db->field_exists('id_pemudik', 'covid19_pantau'))
		{
			$this->dbforge->modify_column('covid19_pantau', array(
				'id_pemudik' => array(
					'name' => 'id_pemudik',
					'type' => 'INT',
					'constraint' => 11
				)
			));
		}

		$query = $this->db->from('INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS')
			->where('CONSTRAINT_NAME', 'fk_pantau_pemudik')
			->where('TABLE_NAME', 'covid19_pantau')
			->get();
	  if ($query->num_rows() == 0)
	  {
			$this->dbforge->add_column('covid19_pantau', array(
				"CONSTRAINT `fk_pantau_pemudik` FOREIGN KEY (`id_pemudik`) REFERENCES `covid19_pemudik`(`id`) ON DELETE CASCADE ON UPDATE CASCADE"
			));
		}

		// Tambah Tabel ref_status_covid
		if (!$this->db->table_exists('ref_status_covid'))
		{
			$this->dbforge->add_field(array(
				'id' => array(
				'type' => 'INT',
					'constraint' => 10,
					'null' => FALSE,
					'auto_increment' => TRUE
				),
				'nama' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'null' => FALSE,
				)
			));
			$this->dbforge->add_key("id",true);
			$this->dbforge->create_table("ref_status_covid", TRUE);
		}

		// Tambah Data di Tabel ref_status_covid
		$data = array();
		$data[] = array(
			'id'=>'1',
			'nama' => 'ODP');

		$data[] = array(
			'id'=>'2',
			'nama' => 'PDP');

		$data[] = array(
			'id'=>'3',
			'nama' => 'ODR');

		$data[] = array(
			'id'=>'4',
			'nama' => 'OTG');

		$data[] = array(
			'id'=>'5',
			'nama' => 'POSITIF');

		$data[] = array(
			'id'=>'6',
			'nama' => 'DLL');

		foreach ($data as $status)
		{
			$sql = $this->db->insert_string('ref_status_covid', $status);
			$sql .= " ON DUPLICATE KEY UPDATE
			id = VALUES(id),
			nama = VALUES(nama)";
			$this->db->query($sql);
		}

	}

}
