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
	}
}
