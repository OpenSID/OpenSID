<?php
class Migrasi_1911_ke_1912 extends CI_model {

  public function up()
  {
  	// Perbaiki form admin data keuangan
		$this->db->where('isi','keuangan.php')->update('widget',array('form_admin'=>'keuangan/impor_data'));
		// Buat kolom tweb_rtm.no_kk menjadi unique
		$fields = array();
		$fields['no_kk'] = array(
				'type' => 'VARCHAR',
				'constraint' => 30,
			  'null' => FALSE,
				'unique' => TRUE
		);
	  $this->dbforge->modify_column('tweb_rtm', $fields);
		// Perbaiki nilai default kolom untuk sql_mode STRICT_TRANS_TABLE
	  $this->dbforge->modify_column('inbox', 'ReceivingDateTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_asset', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_gedung', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_jalan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_kontruksi', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_peralatan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_tanah', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('outbox', 'InsertIntoDB TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('outbox', 'SendingDateTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('outbox', 'SendingTimeOut TIMESTAMP NULL DEFAULT NULL');
	  $this->dbforge->modify_column('sentitems', 'InsertIntoDB TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('sentitems', 'SendingDateTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('teks_berjalan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('mutasi_inventaris_asset', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('mutasi_inventaris_gedung', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('mutasi_inventaris_jalan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('mutasi_inventaris_peralatan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('mutasi_inventaris_tanah', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  // Pindahkan submenu Informasi Publik ke menu Sekretariat
		$this->db->where('id', '52')->update('setting_modul', array('parent' => 15, 'urut' => 4));
		// Pindahkan kolom untuk kategori informasi publik
  	if (!$this->db->field_exists('kategori_info_publik','dokumen'))
		{
			$fields = array(
        'kategori_info_publik' => array(
          'type' => 'TINYINT',
          'constraint' => '4',
          'null' => TRUE,
          'default' => NULL
        )
			);
			$this->dbforge->add_column('dokumen',$fields);
			// Pindahkan isi kolom sebelumnya
			$dokumen = $this->db->select('id, attr')->get('dokumen')->result_array();
			foreach ($dokumen as $dok)
			{
				$attr = json_decode($dok['attr'], true);
				$kat = $attr['kategori_publik'];
				unset($attr['kategori_publik']);
				$this->db->where('id', $dok['id'])
					->update('dokumen', array('kategori_info_publik' => $kat, 'attr' => json_encode($attr)));
			}
		}
		// Isi kategori_info_publik untuk semua dokumen SK Kades dan Perdes sebagai 'Informasi Setiap Saat'
		$this->db->where('kategori_info_publik IS NULL')
			->where("kategori IN (2,3)")
			->update('dokumen', array('kategori_info_publik' => '3'));
	}
}
