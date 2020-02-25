<?php
class Migrasi_2002_ke_2003 extends CI_model {

	public function up()
	{
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
		if (!$this->db->field_exists('hits','artikel'))
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
}
