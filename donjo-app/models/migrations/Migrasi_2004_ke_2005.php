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

	private function kotak_pesan()
	{
        if (!$this->db->table_exists('kotak_pesan') )
		{
			// .buat tabel kotak_pesan
			$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_pengirim' => array(
					'type' => 'INT',
					'constraint' => 5
				),
				'id_penerima' => array(
					'type' => 'INT',
					'constraint' => 5
				),
				'subjek' => array(
					'type' => 'TINYTEXT',
					'null' => TRUE
				),
				'isi_pesan' => array(
					'type' => 'TEXT',
					'null' => TRUE
				),
				'tipe' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default,' => 0
				),
				'baca' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default,' => 1
				),
				'status' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 1
				),
				'created_at' => array(
					'type' => 'TIMESTAMP'
				),
				'updated_at' => array(
					'type' => 'TIMESTAMP'
				),
			));
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('kotak_pesan');
		}

		if ($this->db->table_exists('kotak_pesan') )
		{
			// salin data kotak_pesan(775) dari tabel komentar
			$list_pesan = $this->db->where('id_artikel', 775)->get('komentar')->result_array();
			foreach ($list_pesan as $pesan) {
				$id = $this->db->where('nik', $pesan['email'])->get('tweb_penduduk')->row()->id;
				// karena tabel komentar tdk membedakan pengirim dan penerima maka perlu penyesuaian pd kotak_pesan
				// id_penerima penerima nilai 1 (admin) default
				// sebelumnya tipe 1 = kiriman dr user dan 2 = kiriman dari admin
				if($pesan['tipe']==1){
					$id_pengirim = $id;
					$id_penerima = 1; // default 1 krn belum ada pembagian sebelumnya di tabel komentar
				}else{
					$id_pengirim = 1; // default 1 krn belum ada pembagian sebelumnya di tabel komentar
					$id_penerima = $id; 
				}

				$data = array(
					'id_pengirim' => $id_pengirim,
					'id_penerima' => $id_penerima,
					'subjek' => $pesan['subjek'],
					'isi_pesan' => $pesan['komentar'],
					'tipe' => $pesan['tipe'], // penyesuaian dari field tipe, 1 = pesan masuk/admin | 2 = pesan keluar/user
					'baca' => $pesan['status'], // penyesuaian dari field status, 1 = belum  | 2= sudah
					'status' => $pesan['is_archived'], //penyesuaian dari field is_archived, 0 = tdk diarsipkan  | 1 = diarsipkan
					'created_at' => $pesan['tgl_upload'],
					'updated_at' => $pesan['updated_at']
				);
				$this->db->insert('kotak_pesan', $data);

				// hapus data(775) pd tabel komentar jika data sudah tersalin ke tabel kotak pesan
				// jika ditemuka komentar id_artikel 775 tp id_pengirim tdk ditemuka maka tetap akan dihapus agar tdk menjadi sampah pd dr tabel komentar
				$this->db->where('id', $pesan['id'])->delete('komentar');
			}
		}
	}
}
