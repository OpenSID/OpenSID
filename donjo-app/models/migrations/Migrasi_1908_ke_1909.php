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

	// Tambah tabel asuransi
	if (!$this->db->table_exists('tweb_penduduk_asuransi'))
	{
		$query = "
			CREATE TABLE `tweb_penduduk_asuransi` (
				`id` int(15) NOT NULL AUTO_INCREMENT,
				`nama` varchar(50) NOT NULL,
				PRIMARY KEY (id)
			)
				";

		$this->db->query($query);

		$this->db->truncate('tweb_penduduk_asuransi');
		$query = "INSERT INTO tweb_penduduk_asuransi (`id`, `nama`) VALUES
			(1, 'Tidak/Belum Punya'),
			(2, 'BPJS Kesehatan'),
			(3, 'Prudential Life Assurance'),
			(4, 'AXA Life Indonesia')
		";

		$this->db->query($query);
	}
	// Tambah kolom no_asuransi, id_asuransi
  	if (!$this->db->field_exists('id_asuransi', 'tweb_penduduk'))
  	{
  		$fields = array();
  		$fields['id_asuransi'] = array(
	        	'type' => 'char',
	        	'constraint' => 5,
	        	'null' => TRUE,
	        	'default' => NULL
	        );
			$this->dbforge->add_column('tweb_penduduk', $fields);
  	}
  	if (!$this->db->field_exists('no_asuransi', 'tweb_penduduk'))
  	{
  		$fields = array();
  		$fields['no_asuransi'] = array(
	        	'type' => 'char',
	        	'constraint' => 25,
	        	'null' => TRUE,
	        	'default' => NULL
	        );
			$this->dbforge->add_column('tweb_penduduk', $fields);
  	}
  }
}
