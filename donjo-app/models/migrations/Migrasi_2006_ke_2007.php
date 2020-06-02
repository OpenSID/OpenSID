<?php
class Migrasi_2006_ke_2007 extends CI_model {

	public function up()
	{
		// Ubah tipe data tautan pd tabel teks_berjalan (tautan berdasarkan id artikel)
		$this->db->query("ALTER TABLE teks_berjalan CHANGE COLUMN tautan tautan INT(11) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci'");;

	}

}
