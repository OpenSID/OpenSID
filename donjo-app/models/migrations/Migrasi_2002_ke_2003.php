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
		// Menu PetaSID di halaman depan
		$modul_gis = array(
				'id'   => '117',
				'nama' => 'PetaSID',
				'link' => 'peta',
				'tipe' => '1',
				'parrent' => '1',
				'link_tipe' => '1',
				'enabled' => '1',
				'urut' => '8'
		);
		$sql = $this->db->insert_string('menu', $modul_gis);
		$this->db->query($sql);
	}
}
