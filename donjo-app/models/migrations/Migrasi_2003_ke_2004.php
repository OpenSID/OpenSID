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
		// 
		// Tambahkan setting aplikasi untuk mengatur jenis url
		$query = $this->db->select('1')->where('key', 'jenis_url')->get('setting_aplikasi');
		if (!$query->result())
		{
			$data = array(
				'key' => 'jenis_url',
				'value' => $setting->value ?: 'slug',
				'jenis' => 'option-value',
				'keterangan' => 'Jenis url artikel'
			);
			$this->db->insert('setting_aplikasi', $data);
			$setting_id = $this->db->insert_id();
			$this->db->insert_batch(
				'setting_aplikasi_options',
				array(
					array('id_setting'=>$setting_id, 'value'=>'slug'),
					array('id_setting'=>$setting_id, 'value'=>'tahun/slug'),
					array('id_setting'=>$setting_id, 'value'=>'tahun/bulan/slug'),
					array('id_setting'=>$setting_id, 'value'=>'tahun/bulan/hari/slug'),
					array('id_setting'=>$setting_id, 'value'=>'id'),
					array('id_setting'=>$setting_id, 'value'=>'tahun/id'),
					array('id_setting'=>$setting_id, 'value'=>'tahun/bulan/id'),
					array('id_setting'=>$setting_id, 'value'=>'tahun/bulan/hari/id')
				)
			);
		}
	}
}