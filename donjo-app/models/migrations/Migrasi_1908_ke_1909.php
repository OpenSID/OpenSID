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
  	// Tambah kolom slug untuk artikel
  	if (!$this->db->field_exists('slug', 'artikel'))
  	{
			$fields = array();
			$fields['slug'] = array(
					'type' => 'varchar',
					'constraint' => 200,
				  'null' => TRUE,
				  'default' => NULL
			);
			$this->dbforge->add_column('artikel', $fields);
		}
		// Tambahkan slug untuk setiap artikel yg belum memiliki
		$list_artikel = $this->db->select('id, judul, slug')->get('artikel')->result_array();
		foreach ($list_artikel as $artikel)
		{
			if (!empty($artikel['slug'])) continue;
			$slug = url_title($artikel['judul'], 'dash', TRUE);
			$this->db->where('id', $artikel['id'])->update('artikel', array('slug' => $slug));
		}
		//tambah kolom keterangan untuk log_surat
		if (!$this->db->field_exists('keterangan', 'log_surat'))
		{
			$fields = array();
			$fields['keterangan'] = array(
				'type' => 'varchar',
				'constraint' => 200,
				'null' => TRUE,
				'default' => NULL
			);
			$this->dbforge->add_column('log_surat', $fields);
		}
	}
}
