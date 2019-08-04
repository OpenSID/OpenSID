<?php
class Migrasi_1908_ke_1909 extends CI_model {

  public function up() {
		if (!$this->db->table_exists('keluarga_aktif'))
		{
	  	$sql = "CREATE VIEW keluarga_aktif AS SELECT k.*
	  			FROM tweb_keluarga k
	  			LEFT JOIN tweb_penduduk p ON k.nik_kepala = p.id
	  			WHERE p.status_dasar = 1";
			$this->db->query($sql);
		}
  }
}
