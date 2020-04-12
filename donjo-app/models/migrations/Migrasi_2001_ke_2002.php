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
		// Tambahkan slug untuk setiap artikel agenda yg belum memiliki
		$list_artikel = $this->db->select('id, judul, slug')
			->where('slug is NULL')->where('id_kategori', AGENDA)
			->get('artikel')->result_array();
		foreach ($list_artikel as $artikel)
		{
			$slug = url_title($artikel['judul'], 'dash', TRUE);
			$this->db->where('id', $artikel['id'])->update('artikel', array('slug' => $slug));
		}
	}
	
}
