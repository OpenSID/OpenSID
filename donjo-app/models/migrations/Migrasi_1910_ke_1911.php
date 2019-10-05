<?php
class Migrasi_1910_ke_1911 extends CI_model {

  public function up()
  {
  	// WNI sebagai nilai default untuk kolom kewarganegaraan
	  $this->dbforge->modify_column('tweb_penduduk', array('warganegara_id' => array('type' => 'TINYINT', 'constraint' => 4, 'null' => false, 'default' => 1)));
	  // Aktifkan submodul Pemetaan
		$submodul_peta = array('88'=>'plan', '89'=>'point', '90'=>'garis', '91'=>'line', '92'=>'area', '93'=>'polygon');
		foreach ($submodul_peta as $key => $submodul)
		{
			$modul_nonmenu = array(
				'id' => $key,
				'modul' => $submodul,
				'url' => $submodul,
				'aktif' => '1',
				'ikon' => '',
				'urut' => '',
				'level' => '',
				'parent' => '8',
				'hidden' => '2',
				'ikon_kecil' => ''
			);
			$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
			$this->db->query($sql);
		}
  }
}
