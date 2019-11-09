<?php
class Migrasi_1911_ke_1912 extends CI_model {

  public function up()
  {
  	// Perbaiki form admin data keuangan
		$this->db->where('isi','keuangan.php')->update('widget',array('form_admin'=>'keuangan/impor_data'));
		// Buat kolom tweb_rtm.no_kk menjadi unique
		$fields = array();
		$fields['no_kk'] = array(
				'type' => 'VARCHAR',
				'constraint' => 30,
			  'null' => FALSE,
				'unique' => TRUE
		);
	  $this->dbforge->modify_column('tweb_rtm', $fields);
	}
}
