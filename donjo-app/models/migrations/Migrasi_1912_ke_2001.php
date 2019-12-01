<?php
class Migrasi_1912_ke_2001 extends CI_model {

  public function up()
  {
  	// Penambahan karakter spasi atau titik padi NIP/NIAP
	  $this->dbforge->modify_column('tweb_desa_pamong','pamong_nip varchar(23)  NULL DEFAULT NULL');
	  $this->dbforge->modify_column('tweb_desa_pamong', 'pamong_niap varchar(23)  NULL DEFAULT NULL ');			
	}
}
