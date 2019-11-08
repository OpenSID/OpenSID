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

    // Menambahkan Jenis Informasi ke table 'ref_dokumen'
    $data = array();
    $data[] = array(
      'id'=>'4',
      'nama'=>'Informasi Berkala');
    $data[] = array(
      'id'=>'5',
      'nama'=>'Informasi Serta-merta');
    $data[] = array(
      'id'=>'6',
      'nama'=>'Informasi Setiap Saat');
    $data[] = array(
      'id'=>'7',
      'nama'=>'Informasi Dikecualikan');
      foreach ($data as $jenis)
      {
        $sql = $this->db->insert_string('ref_dokumen', $jenis);
        $sql .= " ON DUPLICATE KEY UPDATE
        id = VALUES(id),
        nama = VALUES(nama)";
        $this->db->query($sql);
      }

      // Ubah kategori data yg sudah ada dari 1 (Informasi Public) ke 4 (Informasi Berkala) di tabel dokumen
      $this->db->where('kategori', '1')->update('dokumen', array('kategori' => '4'));
	}
}
