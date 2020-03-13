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

		// Buat tabel id C-DESA
		if (!$this->db->table_exists('data_persil_c_desa') )
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'c_desa' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unique' => TRUE,
				),
				'id_pend' => array(
					'type' => 'INT',
					'constraint' => 11,
					'null' => TRUE
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('data_persil_c_desa');
		}

		//tambahkan kolom untuk beberapa data persil
		if (!$this->db->field_exists('id_c_desa','data_persil'))
		{
			$fields = array(
				'id_c_desa' => array(
					'type' => 'INT', 
					'after' => 'id'
				),
				'pajak' => array(
					'type' => 'INT',
					'after' => 'persil_peruntukan_id',
					'null' => TRUE					
				),
				'lokasi' => array(
					'type' => 'TEXT',
					'after' => 'pemilik_luar',
					'null' => TRUE
				)
			);
			$this->dbforge->add_column('data_persil', $fields);
		}

		// Buat tabel mutasi Persil
		if (!$this->db->table_exists('data_persil_mutasi'))
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_persil' => array(
					'type' => 'INT',
					'constraint' => 5,
				),
				'jenis_mutasi' => array(
					'type' => 'TINYINT',
					'constraint' => 2,
				),
				'tanggalmutasi' => array(
					'type' => 'date',
					'null' => TRUE
				),
				'sebabmutasi' => array(
					'type' => 'VARCHAR',
					'constraint' => 20,
					'null' => TRUE					
				),
				'luasmutasi' => array(
					'type' => 'decimal',
					'constraint' => 7,
					'null' => TRUE	
				),
				'no_c_desa' => array(
					'type' => 'INT',
					'constraint' => 5,
					'null' => TRUE
				),
				'keterangan' => array(
					'type' => 'TEXT',
					'null' => TRUE	
				),
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('data_persil_mutasi');
		}

		//Tambah kolom di data_persil_mutasi jenis_mutasi Jika Sebelumnya Sudah migrasi
		if (!$this->db->field_exists('jenis_mutasi','data_persil_mutasi'))
		{
			$fields = array(
				'jenis_mutasi' => array(
					'type' => 'TINYINT',
					'constraint' => 2,
					'after' => 'id_persil'
				)
			);
			$this->dbforge->add_column('data_persil_mutasi', $fields);
		}

		//Ganti kolom untuk di data_persil_mutasi c_desa_awal menjadi no_c_desa
		if ($this->db->field_exists('c_desa_awal','data_persil_mutasi'))
		{
			$fields = array(
				'c_desa_awal' => array(
					'name' => 'no_c_desa',
					'type' => 'INT',
					'constraint' => 5,
					'null' => TRUE
				)
			);
			$this->dbforge->modify_column('data_persil_mutasi', $fields);
		}

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
}
