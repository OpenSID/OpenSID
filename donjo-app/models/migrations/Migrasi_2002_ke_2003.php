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
		// Tambah Modul Pengunjung pada Admin WEB
		if ($this->db->table_exists('setting_modul'))
		{
			$object = array(
				'id' => 205,
				'modul' => 'Pengunjung',
				'url' => 'pengunjung',
				'aktif' => 1,
				'ikon' => 'fa-bar-chart',
				'urut' => 10,
				'level' => 4,
				'hidden' => 0,
				'ikon_kecil' => '',
				'parent' => 13
			);
			$this->db->insert('setting_modul', $object);
		}
	}
}
