<?php
class Migrasi_2004_ke_2005 extends CI_model {

	public function up()
	{
	  // Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
		$this->db->query("ALTER TABLE kelompok_anggota MODIFY COLUMN no_anggota VARCHAR(20) NULL DEFAULT NULL");
	}
	
}
