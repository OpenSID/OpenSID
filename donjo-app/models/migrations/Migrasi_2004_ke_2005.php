<?php
class Migrasi_2004_ke_2005 extends CI_model {

	public function up()
	{
		// Penyesuaian url menu dgn submenu setelah hapus file sekretariat.php
		$this->db->where('id', 15)
			->set('url', 'surat_keluar/clear')
			->update('setting_modul');
	}
	
}
