<?php
class Migrasi_2003_ke_2004 extends CI_model {

	public function up()
	{
		$this->ubah_data_persil();
		
		// Ubah panjang jalan dari KM menjadi M.
		// Untuk mencegah diubah berkali-kali buat asumsi panjang jalan sebelum konversi maksimal 100 KM dan sesudah menggunakan M, minimal 100 M.
		$this->db->where('panjang < 100')
			->set('panjang', 'panjang * 1000', false)
			->update('inventaris_jalan');
  	// Urut tabel gambar_gallery
  	if (!$this->db->field_exists('urut', 'gambar_gallery'))
  	{
			// Tambah kolom
			$fields = array();
			$fields['urut'] = array(
					'type' => 'int',
					'constraint' => 5
			);
			$this->dbforge->add_column('gambar_gallery', $fields);
  	}
	  // Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
		$this->db->query("ALTER TABLE widget MODIFY COLUMN form_admin VARCHAR(100) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE widget MODIFY COLUMN setting TEXT NULL");	  
		$this->db->query("ALTER TABLE log_penduduk MODIFY COLUMN tgl_peristiwa DATETIME DEFAULT CURRENT_TIMESTAMP");
		//Ganti nama subfolder surat di folder desa
		rename('desa/surat', 'desa/template-surat');	
		//Ganti nama subfolder css/default di folder desa
		rename('desa/css/default', 'desa/css/klasik');
		$tema_aktif = $this->db->select('value')
			->where('key', 'web_theme')	
			->get('setting_aplikasi')->row()->value;
		if ($tema_aktif == 'default')
			$this->db->where('key', 'web_theme')
				->update('setting_aplikasi', array('value' => 'klasik'));
		//tambah kolom slug di tabel kategori
		if (!$this->db->field_exists('slug', 'kategori')) {
			$this->db->query("ALTER TABLE kategori ADD COLUMN slug VARCHAR(100) NULL");
		}
		// Tambahkan slug untuk setiap artikel agenda yg belum memiliki
		$list_kategori = $this->db->get('kategori')->result_array();
		foreach ($list_kategori as $kategori) {
			$slug = url_title($kategori['kategori'], 'dash', TRUE);
			$this->db->where('id', $kategori['id'])->update('kategori', array('slug' => $slug));
		}
	}

	private function ubah_data_persil()
	{
		// Buat tabel baru
		$this->buat_ref_persil_kelas();
		$this->buat_ref_persil_mutasi();
		$this->buat_ref_persil_jenis_mutasi();
		$this->buat_cdesa();
		$this->buat_cdesa_penduduk();
		$this->buat_persil();
		$this->buat_mutasi_cdesa();
		// Pindahkan data lama ke tabel baru

	}

	private function buat_ref_persil_kelas()
	{
		// Buat tabel jenis Kelas Persil
		if (!$this->db->table_exists('ref_persil_kelas'))
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'tipe' => array(
					'type' => 'VARCHAR',
					'constraint' => 20
				),
				'kode' => array(
					'type' => 'VARCHAR',
					'constraint' => 20
				),
				'ndesc' => array(
					'type' => 'text',
					'null' => TRUE
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('ref_persil_kelas');
		}
		else
		{
			$this->db->truncate('ref_persil_kelas');
		}

		$data = [
			['kode' => 'S-I', 'tipe' => 'BASAH', 'ndesc' => 'Persawahan Dekat dengan Pemukiman'],
			['kode' => 'S-II', 'tipe' => 'BASAH', 'ndesc' => 'Persawahan Agak Dekat dengan Pemukiman'],
			['kode' => 'S-III', 'tipe' => 'BASAH', 'ndesc' => 'Persawahan Jauh dengan Pemukiman'],
			['kode' => 'S-IV', 'tipe' => 'BASAH', 'ndesc' => 'Persawahan Sangat Jauh dengan Pemukiman'],
			['kode' => 'D-I', 'tipe' => 'KERING', 'ndesc' => 'Lahan Kering Dekat dengan Pemukiman'],
			['kode' => 'D-II', 'tipe' => 'KERING', 'ndesc' => 'Lahan Kering Agak Dekat dengan Pemukiman'],
			['kode' => 'D-III', 'tipe' => 'KERING', 'ndesc' => 'Lahan Kering Jauh dengan Pemukiman'],
			['kode' => 'D-IV', 'tipe' => 'KERING', 'ndesc' => 'Lahan Kering Sanga Jauh dengan Pemukiman'],
			];
		$this->db->insert_batch('ref_persil_kelas', $data);
	}

	private function buat_ref_persil_mutasi()
	{
		// Buat tabel ref Mutasi Persil
		if (!$this->db->table_exists('ref_persil_mutasi'))
		{
			$fields = array(
				'id' => array(
					'type' => 'TINYINT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'nama' => array(
					'type' => 'VARCHAR',
					'constraint' => 20
				),
				'ndesc' => array(
					'type' => 'text',
					'null' => TRUE
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('ref_persil_mutasi');
		}
		else
		{
			$this->db->truncate('ref_persil_mutasi');
		}

		$data = [
			['nama' => 'Jual Beli', 'ndesc' => 'Didapat dari proses Jual Beli'],
			['nama' => 'Hibah', 'ndesc' => 'Didapat dari proses Hibah'],
			['nama' => 'Waris', 'ndesc' => 'Didapat dari proses Waris'],
			];
		$this->db->insert_batch('ref_persil_mutasi', $data);		
	}

	private function buat_ref_persil_jenis_mutasi()
	{
		// Buat tabel Jenis Mutasi Persil
		if (!$this->db->table_exists('ref_persil_jenis_mutasi'))
		{
			$fields = array(
				'id' => array(
					'type' => 'TINYINT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'nama' => array(
					'type' => 'VARCHAR',
					'constraint' => 20
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('ref_persil_jenis_mutasi');
		}
		else
		{
			$this->db->truncate('ref_persil_jenis_mutasi');
		}

		$data = [
			['nama' => 'Penambahan'],
			['nama' => 'Pemecahan'],
			];
		$this->db->insert_batch('ref_persil_jenis_mutasi', $data);
	}

	private function buat_cdesa()
	{
		// Buat tabel C-DESA
		if (!$this->db->table_exists('cdesa') )
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'nomor' => array(
					'type' => 'VARCHAR',
					'constraint' => 20,
					'unique' => TRUE
				),
				'nama_kepemilikan' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'unique' => TRUE
				),
				'jenis_pemilik' => array(
					'type' => 'TINYINT',
					'constraint' => 1
				),
				'nama_pemilik_luar' => array(
					'type' => 'VARCHAR',
					'constraint' => 100
				),
				'alamat_pemilik_luar' => array(
					'type' => 'VARCHAR',
					'constraint' => 200
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('cdesa');
		}
	}

	private function buat_cdesa_penduduk()
	{
		// Buat tabel C-DESA
		if (!$this->db->table_exists('cdesa_penduduk') )
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_cdesa' => array(
					'type' => 'INT',
					'constraint' => 5,
				),
				'id_pend' => array(
					'type' => 'INT',
					'constraint' => 11
				),
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('cdesa_penduduk');
		}
	}

	private function buat_persil()
	{
		//tambahkan kolom untuk beberapa data persil
		if (!$this->db->table_exists('persil'))
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'nomor' => array(
					'type' => 'VARCHAR',
					'constraint' => 20,
					'unique' => TRUE,
				),
				'kelas' => array(
					'type' => 'INT',
					'constraint' => 5
				),
				'id_cluster' => array(
					'type' => 'INT',
					'constraint' => 11
				),
				'lokasi' => array(
					'type' => 'TEXT',
					'null' => TRUE
				),
				'path' => array(
					'type' => 'TEXT',
					'null' => TRUE
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('persil');
		}
	}

	private function buat_mutasi_cdesa()
	{
		// Buat tabel mutasi Persil
		if (!$this->db->table_exists('mutasi_cdesa'))
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_cdesa_masuk' => array(
					'type' => 'INT',
					'constraint' => 5,
					'null' => TRUE
				),
				'id_cdesa_keluar' => array(
					'type' => 'INT',
					'constraint' => 5,
					'null' => TRUE
				),
				'jenis_mutasi' => array(
					'type' => 'TINYINT',
					'constraint' => 2,
				),
				'tanggal_mutasi' => array(
					'type' => 'DATE',
					'null' => TRUE
				),
				'sebab' => array(
					'type' => 'TINYINT',
					'constraint' => 5
				),
				'keterangan' => array(
					'type' => 'TEXT',
					'null' => TRUE	
				),
				'id_persil' => array(
					'type' => 'INT',
					'constraint' => 11
				),
				'no_bidang_persil' => array(
					'type' => 'TINYINT',
					'constraint' => 3
				),
				'luas' => array(
					'type' => 'decimal',
					'constraint' => 7,
					'null' => TRUE	
				),
				'jenis_bidang_persil' => array(
					'type' => 'INT',
					'constraint' => 11
				),
				'peruntukan' => array(
					'type' => 'INT',
					'constraint' => 11
				),
				'no_objek_pajak' => array(
					'type' => 'VARCHAR',
					'constraint' => 30
				),
				'no_sppt_pbb' => array(
					'type' => 'VARCHAR',
					'constraint' => 30
				),
				'path' => array(
					'type' => 'TEXT',
					'null' => TRUE
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('mutasi_cdesa');
		}
	}
}
