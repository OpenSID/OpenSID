<?php
class Migrasi_2004_ke_2005 extends CI_model {

	public function up()
	{
	  // Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
		$this->db->query("ALTER TABLE kelompok_anggota MODIFY COLUMN no_anggota VARCHAR(20) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE inventaris_kontruksi MODIFY COLUMN kontruksi_beton TINYINT(1) DEFAULT 0");
		$this->db->query("ALTER TABLE mutasi_inventaris_asset MODIFY COLUMN harga_jual DOUBLE NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_asset MODIFY COLUMN sumbangkan VARCHAR(255) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_gedung MODIFY COLUMN harga_jual DOUBLE NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_gedung MODIFY COLUMN sumbangkan VARCHAR(255) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_jalan MODIFY COLUMN harga_jual DOUBLE NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_jalan MODIFY COLUMN sumbangkan VARCHAR(255) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_peralatan MODIFY COLUMN harga_jual DOUBLE NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_peralatan MODIFY COLUMN sumbangkan VARCHAR(255) NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_tanah MODIFY COLUMN harga_jual DOUBLE NULL DEFAULT NULL");
		$this->db->query("ALTER TABLE mutasi_inventaris_tanah MODIFY COLUMN sumbangkan VARCHAR(255) NULL DEFAULT NULL");
		// Perbaiki nama aset salah
		$this->db->where('id_aset', 3423)->update('tweb_aset', array('nama' => 'JALAN'));
        $this->db->where('id', 79)->update('setting_modul', array('url'=>'api_inventaris_kontruksi', 'aktif'=>'1'));
        // Buat tabel kotak_pesan
		$this->kotak_pesan(); 
	}

	private function kotak_pesan()
	{
        if (!$this->db->table_exists('kotak_pesan') )
		{
            // .buat tabel kotak_pesan
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 5,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'id_pengirim' => array(
                    'type' => 'INT',
                    'constraint' => 5
                ),
                'id_penerima' => array(
                    'type' => 'INT',
                    'constraint' => 5
                ),
                'subjek' => array(
                    'type' => 'TINYTEXT',
                    'null' => TRUE
                ),
                'isi_pesan' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'tipe' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default,' => 0
                ),
                'baca' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default,' => 1
                ),
                'status' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),
                'created_at' => array(
                    'type' => 'TIMESTAMP'
                ),
                'updated_at' => array(
                    'type' => 'TIMESTAMP'
                ),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('kotak_pesan');
        }

        if ($this->db->table_exists('kotak_pesan') )
		{
            // salin data kotak_pesan(775) dari tabel komentar
            $list_pesan = $this->db->where('id_artikel', 775)->get('komentar')->result_array();
            foreach ($list_pesan as $pesan) {
                $id = $this->db->where('nik', $pesan['email'])->get('tweb_penduduk')->row()->id;
				// karena tabel komentar tdk membedakan pengirim dan penerima maka perlu penyesuaian pd kotak_pesan
				// id_penerima penerima nilai 1 (admin)
				// sebelumnya tipe 1 = kiriman dr user dan 2 = kiriman dari admin
				if($pesan['tipe']==1){
					$id_pengirim = $id;
					$id_penerima = 1; // default 1 krn belum ada pembagian sebelumnya di tabel komentar
				}else{
					$id_pengirim = 1; // default 1 krn belum ada pembagian sebelumnya di tabel komentar
					$id_penerima = $id; 
                }

                $data = array(
                    'id_pengirim' => $id_pengirim,
                    'id_penerima' => $id_penerima,
                    'subjek' => $pesan['subjek'],
                    'isi_pesan' => $pesan['komentar'],
                    'tipe' => $pesan['tipe'], // penyesuaian dari field tipe, 1 = pesan masuk/admin | 2 = pesan keluar/user
                    'baca' => $pesan['status'], // penyesuaian dari field status, 1 = belum  | 2= sudah
                    'status' => $pesan['is_archived'], //penyesuaian dari field is_archived, 0 = tdk diarsipkan  | 1 = diarsipkan
                    'created_at' => $pesan['tgl_upload'],
                    'updated_at' => $pesan['updated_at']
                );
                $this->db->insert('kotak_pesan', $data);

				// hapus data(775) pd tabel komentar jika data sudah tersalin ke tabel kotak pesan
				// jika ditemuka komentar id_artikel 775 tp id_pengirim tdk ditemuka maka tetap akan dihapus agar tdk menjadi sampah pd dr tabel komentar
                $this->db->where('id', $pesan['id'])->delete('komentar');
            }
        }
    }
    
}