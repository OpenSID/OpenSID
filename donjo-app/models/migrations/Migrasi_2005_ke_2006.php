<?php
class Migrasi_2005_ke_2006 extends CI_model {

	public function up()
	{
		// Ubah nama kode status penduduk
		$this->db->where('id', 2)
			->update('tweb_penduduk_status', array('nama' => 'TIDAK TETAP'));
	}

}
