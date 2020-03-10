<?php
class Migrasi_2003_ke_2004 extends CI_model {

	public function up()
	{
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
}