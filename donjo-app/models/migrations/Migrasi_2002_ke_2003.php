<?php
class Migrasi_2002_ke_2003 extends CI_model {

	public function up()
	{
		$this->surat_mandiri();
		$this->surat_mandiri_tersedia();
		$this->mailbox();

		// Hapus setting tombol cetak surat langsung
		$this->db->where('key', 'tombol_cetak_surat')
			->delete('setting_aplikasi');
		// Setting nilai default supaya tidak error pada sql strict mode
		$fields = array();
		$fields['parrent'] = array('type' => 'INT', 'constraint' => 4, 'default' => 0);
		$fields['tipe'] = array('type' => 'INT', 'constraint' => 4, 'default' => 0);
	  $this->dbforge->modify_column('gambar_gallery', $fields);
		$fields = array();
		$fields['kode_surat'] = array('type' => 'VARCHAR', 'constraint' => 10, 'null' => TRUE, 'default' => NULL);
	  $this->dbforge->modify_column('tweb_surat_format', $fields);
		// Tambah kolom hits pada artikel
		if (!$this->db->field_exists('hit','artikel'))
		{
			$this->db->query("ALTER TABLE artikel ADD COLUMN hit INT NULL DEFAULT '0'");
		}
		// Tambah Modul Pengunjung pada Admin WEB
		$data = array(
				'id' => 205,
				'modul' => 'Pengunjung',
				'url' => 'pengunjung/clear',
				'aktif' => 1,
				'ikon' => 'fa-bar-chart',
				'urut' => 10,
				'level' => 4,
				'hidden' => 0,
				'ikon_kecil' => '',
				'parent' => 13
				);
		$sql = $this->db->insert_string('setting_modul', $data);
		$sql .= " ON DUPLICATE KEY UPDATE
				modul = VALUES(modul),
				aktif = VALUES(aktif),
				ikon = VALUES(ikon),
				urut = VALUES(urut),
				level = VALUES(level),
				hidden = VALUES(hidden),
				ikon_kecil = VALUES(ikon_kecil),
				parent = VALUES(parent)
				";
		$this->db->query($sql);
	}

	private function surat_mandiri()
	{
    // Table ref_syarat_surat tempat nama dokumen sbg syarat Permohonan surat
		if (!$this->db->table_exists('ref_syarat_surat') )
		{
	    $this->dbforge->add_field(array(
				'ref_syarat_id' => array(
					'type' => 'INT',
					'constraint' => 1,
					'unsigned' => TRUE,
					'null' => FALSE,
					'auto_increment' => TRUE
				),
				'ref_syarat_nama' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
					'null' => FALSE,
				),
			));
			$this->dbforge->add_key("ref_syarat_id",true);
			$this->dbforge->create_table("ref_syarat_surat", TRUE);

	    // Menambahkan Data Table ref_syarat_surat
	    $query = "
	    INSERT INTO `ref_syarat_surat` (`ref_syarat_id`, `ref_syarat_nama`) VALUES
		    (1, 'Surat Pengantar RT/RW'),
		    (2, 'Fotokopi KK'),
		    (3, 'Fotokopi KTP'),
		    (4, 'Fotokopi Surat Nikah/Akta Nikah/Kutipan Akta Perkawinan'),
		    (5, 'Fotokopi Akta Kelahiran/Surat Kelahiran bagi keluarga yang mempunyai anak'),
		    (6, 'Surat Pindah Datang dari tempat asal'),
		    (7, 'Surat Keterangan Kematian dari Rumah Sakit, Rumah Bersalin Puskesmas, atau visum Dokter'),
		    (8, 'Surat Keterangan Cerai'),
		    (9, 'Fotokopi Ijasah Terakhir'),
		    (10, 'SK. PNS/KARIP/SK. TNI – POLRI'),
		    (11, 'Surat Keterangan Kematian dari Kepala Desa/Kelurahan'),
		    (12, 'Surat imigrasi / STMD (Surat Tanda Melapor Diri)');
	    ";
	    $this->db->query($query);
	  }

    // Table syarat_surat sbg link antara surat yg dimohon dan dokumen yg diperlukan
		if (!$this->db->table_exists('syarat_surat') )
		{
	    $this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 10,
					'null' => FALSE,
					'auto_increment' => TRUE
				),
				'surat_format_id' => array(
					'type' => 'INT',
					'constraint' => 10,
					'null' => FALSE,

				),
				'ref_syarat_id' => array(
					'type' => 'INT',
					'constraint' => 10,
					'null' => FALSE,

				),
			));
			$this->dbforge->add_key("id",true);
			$this->dbforge->create_table("syarat_surat", TRUE);
			$this->dbforge->add_column(
				'syarat_surat',
				array("CONSTRAINT `id_surat_format` FOREIGN KEY (`surat_format_id`) REFERENCES `tweb_surat_format` (`id`) ON DELETE CASCADE ON UPDATE CASCADE")
			);
		}

    // Menambahkan menu 'Group / Hak Akses' ke table 'setting_modul'
    $data = array();
    $data[] = array(
      'id'=>'97',
      'modul' => 'Daftar Persyaratan',
      'url' => 'surat_mohon',
      'aktif' => '1',
      'ikon' => 'fa fa-book',
      'urut' => '5',
      'level' => '2',
      'hidden' => '0',
      'ikon_kecil' => '',
      'parent' => 4);

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

    // Tambah kolom tanda surat yg tersedia untuk layanan mandiri
		if (!$this->db->field_exists('mandiri','tweb_surat_format'))
		{
			$this->db->query("ALTER TABLE tweb_surat_format ADD mandiri tinyint(1) default 0");
		}

    // Tabel mendaftarkan permohonan surat dari layanan mandiri
		if (!$this->db->table_exists('permohonan_surat'))
		{
	    $this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'auto_increment' => TRUE
				),
				'id_pemohon' => array(
					'type' => 'INT',
					'constraint' => 11,
					'null' => FALSE
				),
				'id_surat' => array(
					'type' => 'INT',
					'constraint' => 11,
					'null' => FALSE
				),
				'isian_form' => array(
					'type' => 'TEXT'
				),
				'status' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 0
				),
				'keterangan' => array(
					'type' => 'TEXT',
					'null' => TRUE
				),
				'no_hp_aktif' => array(
					'type' => 'VARCHAR',
					'constraint' => 50
				),
				'syarat' => array(
					'type' => 'TEXT'
				)
			));
			$this->dbforge->add_field("created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
			$this->dbforge->add_field("updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
			$this->dbforge->add_key("id", true);
			$this->dbforge->create_table("permohonan_surat", TRUE);
		}
		// Menu permohonan surat untuk operator
		$modul = array(
			'id' => '98',
			'modul' => 'Permohonan Surat',
			'url' => 'permohonan_surat_admin/clear',
			'aktif' => '1',
			'ikon' => 'fa-files-o',
			'urut' => '0',
			'level' => '0',
			'parent' => '14',
			'hidden' => '0',
			'ikon_kecil' => ''
		);
		$sql = $this->db->insert_string('setting_modul', $modul) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), ikon = VALUES(ikon), parent = VALUES(parent)";
		$this->db->query($sql);
	}

	private function surat_mandiri_tersedia()
	{
		// Surat yg tersedia di permohonan surat melalui layanan mandiri plus syarat masing2
		$surat_tersedia = array(
			1 => array(1, 2, 3), //surat_ket_pengantar
			2 => array(2, 3), //surat_ket_penduduk
			3 => array(2, 3), //surat_bio_penduduk
			5 => array(1, 2, 3), //surat_ket_pindah_penduduk
			6 => array(1, 2, 3), //surat_ket_jual_beli
			8 => array(1, 2, 3), //surat_ket_catatan_kriminal
			9 => array(2, 3), //surat_ket_ktp_dalam_proses
			10 => array(1, 2, 3), //surat_ket_beda_nama
			11 => array(1, 2, 3), //surat_jalan
			12 => array(1, 2, 3), //surat_ket_kurang_mampu
			13 => array(1, 2, 3) //surat_izin_keramaian
		);
		foreach ($surat_tersedia as $surat_format_id => $list_syarat)
		{
			$this->db->where('id', $surat_format_id)->update('tweb_surat_format', array('mandiri' => 1));
			foreach ($list_syarat as $syarat_id)
			{
				$ada = $this->db->where('surat_format_id', $surat_format_id)->where('ref_syarat_id', $syarat_id)
					->get('syarat_surat')->num_rows();
				if (!$ada)
				{
					$this->db->insert('syarat_surat', array('surat_format_id' => $surat_format_id, 'ref_syarat_id' => $syarat_id));
				}
			}
		}
	}

	private function mailbox()
	{
		$modul_mailbox = array(
			'modul' => 'Kotak Pesan',
			'url' => 'mailbox/clear'
		);

		$this->db
			->where('id', '55')
			->update('setting_modul', $modul_mailbox);

		// Tambahkan kolom untuk menandai apakah pesan diarsipkan atau belum
		if (!$this->db->field_exists('is_archived', 'komentar')) 
		{
			$fields = array(
				'is_archived' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 0
				)
			);
			$this->dbforge->add_column('komentar', $fields);
		}

		// ubah nama kolom menjadi status untuk penanda status di mailbox
		if ($this->db->field_exists('enabled', 'komentar')) 
		{
			$this->dbforge->modify_column('komentar', array(
				'enabled' => array(
					'name' => 'status',
					'type' => 'TINYINT',
					'constraint' => 1
				)
			));
		}

		// Tambahkan kolom tipe untuk membedakan pesan inbox dan outbox
		if (!$this->db->field_exists('tipe', 'komentar')) 
		{
			$fields = array(
				'tipe' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'after' => 'status'
				)
			);
			$this->dbforge->add_column('komentar', $fields);
		}

		// Paksa data lapor yang sudah ada memiliki tipe inbox
		$tipe = array(
			'tipe' => '1',
		);
		$this->db
			->where('id_artikel', '775')
			->where('tipe', NULL)
			->update('komentar', $tipe);

		// Tambahkan kolom subjek untuk digunakan di menu mailbox
		if (!$this->db->field_exists('subjek', 'komentar')) 
		{
			$this->dbforge->add_column('komentar', array(
				'subjek' => array(
					'type' => 'TINYTEXT',
					'after' => 'email'
				)
			));
		}

		$subjek = array(
			'subjek' => 'Tidak ada subjek pesan',
		);
		$this->db
			->where('id_artikel', '775')
			->where('subjek', NULL)
			->update('komentar', $subjek);

		// Tambahkan kolom id_syarat untuk link ke dokumen syarat
		if (!$this->db->field_exists('id_syarat', 'dokumen')) 
		{
			$fields = array(
				'id_syarat' => array(
					'type' => 'INT',
					'constraint' => 11,
					'after' => 'deleted'
				)
			);
			$this->dbforge->add_column('dokumen', $fields);
		}
	}

}
