<?php
class Migrasi_1910_ke_1911 extends CI_model {

  public function up()
  {
  	$this->jdih();
  }

  public function jdih()
  {
    // Hapus jenis dokumen umum
    if ($this->db->table_exists('ref_dokumen'))
    {
      $this->db->truncate('ref_dokumen');
      $object = array(
        array(
          'id' => 1,
          'kategori' => 'Dokumen Umum'
        ),
        array(
          'id' => 2,
          'kategori' => 'SK Kades'
        ),
        array(
          'id' => 3,
          'kategori' => 'Perdes'
        )
      );
      $this->db->insert_batch('ref_dokumen', $object);

    }
  	// Penambahan Field Tahun pada table dokumen untuk keperluan filter JDIH
		if ($this->db->table_exists('dokumen'))
		{
			$res = $this->db->get('dokumen')->result_array();

	  	if (!$this->db->field_exists('tahun','dokumen'))
			{
				$fields = array(
	        'tahun' => array(
              'type' => 'INT',
              'constraint' => '4'
	        )
				);
				$this->dbforge->add_column('dokumen',$fields);

				foreach ($res as $v)
				{
					$tgl =  json_decode($v['attr'], TRUE);
          if ($v['kategori'] == 2)
          {
            $tahun = date('Y',strtotime($tgl['tgl_kep_kades']));
          }
          elseif($v['kategori'] == 3)
          {
            $tahun = date('Y',strtotime($tgl['tgl_ditetapkan']));
          }

					$data = array(
						'tahun' => $tahun,
					);
					$this->db->where('id', $v['id']);
					$this->db->update('dokumen', $data);
				}
			}
			else
			{
				foreach ($res as $v)
				{
					$tgl =  json_decode($v['attr'], TRUE);
          if ($v['kategori'] == 2)
          {
            $tahun = date('Y',strtotime($tgl['tgl_kep_kades']));
          }
          elseif($v['kategori'] == 3)
          {
            $tahun = date('Y',strtotime($tgl['tgl_ditetapkan']));
          }

					$data = array(
						'tahun' => $tahun,
					);
					$this->db->where('id', $v['id']);
					$this->db->update('dokumen', $data);
				}
			}
		}

  	// Penambahan table dokumen_kategori untuk dynamic categories dokumen
		if (!$this->db->table_exists('ref_dokumen'))
		{
			$fields = array(
        'id' => array(
            'type' => 'INT',
            'constraint' => 5,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'kategori' => array(
            'type' => 'VARCHAR',
            'constraint' => '100'
        )
			);

			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('ref_dokumen');

			$object = array(
				array(
					'id' => 1,
					'kategori' => 'Dokumen Umum'
				),
				array(
					'id' => 2,
					'kategori' => 'SK Kades'
				),
				array(
					'id' => 3,
					'kategori' => 'Perdes'
				)
			);
			$this->db->insert_batch('ref_dokumen', $object);
		}

  	// Perubahan Sub Menu pada Sekretariat > SK Kades dan Perdes menjadi Sekretariat > Produk Hukum
		if ($this->db->table_exists('setting_modul'))
		{
			$array = array( 59, 60 );
			$this->db->where_in('id', $array);
			$this->db->delete('setting_modul');

			$object = array(
				'id' => 95,
				'modul' => 'Peraturan Desa',
				'url' => 'dokumen_sekretariat/peraturan_desa',
				'aktif' => 1,
				'ikon' => 'fa-book',
				'urut' => 3,
				'level' => 2,
				'hidden' => 0,
				'ikon_kecil' => '',
				'parent' => 15
			);
			$this->db->insert('setting_modul', $object);
		}
	}

  	// WNI sebagai nilai default untuk kolom kewarganegaraan
	  $this->dbforge->modify_column('tweb_penduduk', array('warganegara_id' => array('type' => 'TINYINT', 'constraint' => 4, 'null' => false, 'default' => 1)));
	  // Hapus modul yg salah tambah
	  $this->db->where('url', 'dokumen_sekretariat/peraturan_desa')->delete('setting_modul');
	  // Aktifkan submodul Pemetaan
		$submodul_peta = array('88'=>'plan', '89'=>'point', '90'=>'garis', '91'=>'line', '92'=>'area', '93'=>'polygon');
		foreach ($submodul_peta as $key => $submodul)
		{
			$modul_nonmenu = array(
				'id' => $key,
				'modul' => $submodul,
				'url' => $submodul,
				'aktif' => '1',
				'ikon' => '',
				'urut' => '',
				'level' => '',
				'parent' => '8',
				'hidden' => '2',
				'ikon_kecil' => ''
			);
			$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
			$this->db->query($sql);
		}
		// Update view supaya kolom baru ikut masuk
		$this->db->query("DROP VIEW penduduk_hidup");
		$this->db->query("CREATE VIEW penduduk_hidup AS SELECT * FROM tweb_penduduk WHERE status_dasar = 1");
		// Ubah kode surat
		$this->db
			->where('url_surat', 'surat_kuasa')
			->where('kode_surat', 'S-43')
			->update('tweb_surat_format', array('kode_surat' => 'S-47'));
		// Tambah surat
		$data = array(
			'nama'=>'Keterangan Kepemilikan Kendaraan',
			'url_surat'=>'surat_ket_kepemilikan_kendaraan',
			'kode_surat'=>'S-48',
			'jenis'=>1);
		$sql = $this->db->insert_string('tweb_surat_format', $data);
		$sql .= " ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis)";
		$this->db->query($sql);
  }
}
