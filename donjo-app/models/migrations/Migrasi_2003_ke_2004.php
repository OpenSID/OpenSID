<?php
class Migrasi_2003_ke_2004 extends CI_model {

	public function up()
	{
		//ganti link = dpt dan kelas sosial pada tabel menu
		$this->db->where('link', 'dpt')->update('menu', array('link' => 'statistik/calon-pemilih'));
		$this->db->where('link', 'kelas_sosial')->update('menu', array('link' => 'statistik/kelas-sosial'));


		//ganti link = peraturan_desa dan informasi_publik pada tabel menu
		$this->db->where('link', 'peraturan_desa')->update('menu', array('link' => 'dokumen/produk-hukum'));
		$this->db->where('link', 'informasi_publik')->update('menu', array('link' => 'dokumen/informasi-publik'));	
	}
}
