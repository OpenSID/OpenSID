<?php
class Migrasi_1909_ke_1910 extends CI_model {

  public function up() {
  	// Penambahan widget peraturan desa
  	if ($this->db->table_exists('widget'))
	{
		$data = array(
			'isi' => 'peraturan_desa.php', 
			'enabled' => 1,
			'judul' => 'Peraturan Desa',
			'jenis_widget' => 2,
			'urut' => 17
		);
		$this->db->insert('widget', $data);
	}
  	// Penambahan Field Tahun pada table dokumen untuk keperluan filter JDIH
  	if ($this->db->table_exists('dokumen'))
	{
		$fields = array(
	        'tahun' => array(
                'type' => 'INT',
                'constraint' => '4'
	        )
		);
		$this->dbforge->add_column('dokumen',$fields);
	}
  	// Penambahan table dokumen_kategori untuk dynamic categories dokumen
  	if (!$this->db->table_exists('ref_dokumen'))
	{
		$fields = array(
	        'id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
	        ),
	        'kategori' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
	        )
		);

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('ref_dokumen');

		$object = array(
			array(
				'id' => 1,
				'kategori' => 'Dokumen Umum'
			),
			array(
				'id' => 2,
				'kategori' => 'SK Kades'
			),
			array(
				'id' => 3,
				'kategori' => 'Perdes'
			)
		);
		$this->db->insert_batch('ref_dokumen', $object);
	}

  	// Perubahan Sub Menu pada Sekretariat > SK Kades dan Perdes menjadi Sekretariat > Produk Hukum
  	if ($this->db->table_exists('setting_modul'))
	{
		$array = array( 59, 60 );
		$this->db->where_in('id', $array);
		$this->db->delete('setting_modul');

		$object = array(
			'id' => 95,
			'modul' => 'Peraturan Desa',
			'url' => 'dokumen_sekretariat/peraturan_desa',
			'aktif' => 1,
			'ikon' => 'fa-book',
			'urut' => 3,
			'level' => 2,
			'hidden' => 0,
			'ikon_kecil' => '',
			'parent' => 15
		);
		$this->db->insert('setting_modul', $object);
	}

		// Tambah tabel asuransi
		if (!$this->db->table_exists('tweb_penduduk_asuransi'))
		{
			$query = "
				CREATE TABLE `tweb_penduduk_asuransi` (
					`id` tinyint(5) NOT NULL AUTO_INCREMENT,
					`nama` varchar(50) NOT NULL,
					PRIMARY KEY (id)
				)
			";

			$this->db->query($query);

			$query = "INSERT INTO tweb_penduduk_asuransi (`id`, `nama`) VALUES
				(1, 'Tidak/Belum Punya'),
				(2, 'BPJS Penerima Bantuan Iuran'),
				(3, 'BPJS Non Penerima Bantuan Iuran'),
				(99, 'Asuransi Lainnya')
			";

			$this->db->query($query);
		}
		// Tambah kolom no_asuransi, id_asuransi
  	if (!$this->db->field_exists('id_asuransi', 'tweb_penduduk'))
  	{
  		$fields = array();
  		$fields['id_asuransi'] = array(
	        	'type' => 'tinyint',
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
	        	'constraint' => 100,
	        	'null' => TRUE,
	        	'default' => NULL
	        );
			$this->dbforge->add_column('tweb_penduduk', $fields);
  	}
  	if (!$this->db->field_exists('updated_at', 'komentar'))
  	{
			$this->dbforge->add_column("komentar", "updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
		}
		// Tambah setting server untuk menentukan setting modul default
		$query = $this->db->select('1')->where('key', 'penggunaan_server')->get('setting_aplikasi');
		$query->result() OR	$this->db->insert('setting_aplikasi', array('key'=>'penggunaan_server', 'value'=>'1	', 'jenis'=>'int', 'keterangan'=>"Setting penggunaan server", 'kategori'=>'sistem'));
  }
}