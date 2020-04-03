<?php
class Migrasi_2004_ke_2005 extends CI_model {

	public function up()
	{
		// Perubahan modul komentar
		$this->komentar();
	}

	private function komentar()
	{
		// Tambal field id_balas
		if (!$this->db->field_exists('id_balas','komentar'))
		{
			$this->db->query("ALTER TABLE komentar ADD id_balas int(5) default 0");
		}
		
		// Ubah tipe nilai NULL jadi 0
		$this->db->where('tipe', NULL)
			->set('tipe', '0')
			->update('komentar');
	}
}