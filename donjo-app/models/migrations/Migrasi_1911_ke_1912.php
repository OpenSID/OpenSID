<?php
class Migrasi_1911_ke_1912 extends CI_model {

  public function up()
  {
  	// Perbaiki form admin data keuangan
		$this->db->where('isi','keuangan.php')->update('widget',array('form_admin'=>'keuangan/impor_data'));
	}
}
