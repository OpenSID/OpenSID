<?php
class Migrasi_2001_ke_2002 extends CI_model {

	public function up()
	{
		// Tambah kolom data siskeudes
		if (!$this->db->field_exists('ID_Bank','keuangan_ta_sts'))
		{
			$this->db->query("ALTER TABLE keuangan_ta_sts ADD ID_Bank varchar(10) NULL");
		}	
		$this->db->where('id', 51)->update('setting_modul', array('url'=>'gallery/clear', 'aktif'=>'1'));
	}
}
