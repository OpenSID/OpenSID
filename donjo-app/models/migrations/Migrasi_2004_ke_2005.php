<?php
class Migrasi_2004_ke_2005 extends CI_model {

	public function up()
	{
		// MODUL BARU BEGIN
		$this->covid19();
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
		// Perbaiki nama aset salah
		$this->db->where('id_aset', 3423)->update('tweb_aset', array('nama' => 'JALAN'));
		$this->db->where('id', 79)->update('setting_modul', array('url'=>'api_inventaris_kontruksi', 'aktif'=>'1'));
		// Hapus field urut di tabel artikel krn tdk dibutuhkan
		if ($this->db->field_exists('urut', 'artikel'))
			$this->db->query('ALTER TABLE `artikel` DROP COLUMN `urut`');		
		
		// Perbaikan modul sms
		$this->sms();
	}
	
	private function covid19()
	{
		// Menambahkan menu 'Group / Hak Akses' ke table 'setting_modul'
	    $data = array();
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
	      'parent' => 0);

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
					'type' => 'VARCHAR',
					'constraint' => 20,
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
	}

	private function sms()
	{
		// Buat field id_pend pd tabel anggota_group_kontak untuk menyimpan data dari tabel kontak
		if (!$this->db->field_exists('id_pend', 'anggota_grup_kontak')) {
			$this->db->query("ALTER TABLE `anggota_grup_kontak` ADD COLUMN `id_pend` INT(11) NOT NULL, ADD INDEX `id_pend` (`id_pend`)");
		}
		
		// Pindahkan data tabel kontak (field no_hp, id_pend) ke tabel tweb_penduduk (filed telepon) dan tabel anggota_group_kontak (field id_kontak)
		$list_telepon = $this->db->get('kontak')->result_array();
		foreach ($list_telepon as $telepon) {
			$this->db->where('id', $telepon['id_pend'])->update('tweb_penduduk', array('telepon' => $telepon['no_hp']));
			$this->db->where('id_kontak', $telepon['id_kontak'])->update('anggota_grup_kontak', array('id_pend' => $telepon['id_pend']));
		}

		// Hapus field dan tabel yg sudah tdk dibutuhkan
		// Tabel kontak
		if ($this->db->table_exists('kontak'))
		{
			// Hapus foreign key agar bisa menghapus tabel kontak
			$this->db->query('ALTER TABLE `anggota_grup_kontak` DROP FOREIGN KEY `anggota_grup_kontak_ke_kontak`');
			$this->dbforge->drop_table('kontak', TRUE);
		}		
		// Field id_kontak pd tabel anggota_grup_kontak
		if ($this->db->field_exists('id_kontak', 'anggota_grup_kontak')){
			$this->db->query('ALTER TABLE `anggota_grup_kontak` DROP COLUMN `id_kontak`');
		}
	}
}
