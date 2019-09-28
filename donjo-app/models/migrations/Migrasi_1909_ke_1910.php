<?php
class Migrasi_1909_ke_1910 extends CI_model {

  public function up() {
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
		// Tambah controller yg merupakan submodul yg tidak tampil di menu utama
		$modul_nonmenu = array(
			'id' => '65',
			'modul' => 'Kategori',
			'url' => 'kategori',
			'aktif' => '1',
			'ikon' => '',
			'urut' => '',
			'level' => '',
			'parent' => '49',
			'hidden' => '2',
			'ikon_kecil' => ''
		);
		$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
		$this->db->query($sql);
		$modul_nonmenu = array(
			'id' => '66',
			'modul' => 'Log Penduduk',
			'url' => 'penduduk_log',
			'aktif' => '1',
			'ikon' => '',
			'urut' => '',
			'level' => '',
			'parent' => '21',
			'hidden' => '2',
			'ikon_kecil' => ''
		);
		$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
		$this->db->query($sql);
		$submodul_analisis = array('67'=>'analisis_kategori', '68'=>'analisis_indikator', '69'=>'analisis_klasifikasi', '70'=>'analisis_periode', '71'=>'analisis_respon', '72'=>'analisis_laporan', '73'=>'analisis_statistik_jawaban');
		foreach ($submodul_analisis as $key => $submodul)
		{
			$modul_nonmenu = array(
				'id' => $key,
				'modul' => $submodul,
				'url' => $submodul,
				'aktif' => '1',
				'ikon' => '',
				'urut' => '',
				'level' => '',
				'parent' => '5',
				'hidden' => '2',
				'ikon_kecil' => ''
			);
			$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
			$this->db->query($sql);
		}
		$modul_nonmenu = array(
			'id' => '74',
			'modul' => 'Wilayah',
			'url' => 'wilayah',
			'aktif' => '1',
			'ikon' => '',
			'urut' => '',
			'level' => '',
			'parent' => '21',
			'hidden' => '2',
			'ikon_kecil' => ''
		);
		$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
		$this->db->query($sql);
		$this->db->where('id', 2)->update('setting_modul', array('url'=>'', 'aktif'=>'1'));
	  // Ubah id rtm supaya bisa lebih panjang
	  $sql = "ALTER TABLE `tweb_rtm` CHANGE `no_kk` `no_kk` VARCHAR(30) NOT NULL";
	  $this->db->query($sql);
	  $sql = "ALTER TABLE `tweb_penduduk` CHANGE `id_rtm` `id_rtm` VARCHAR(30) NOT NULL";
	  $this->db->query($sql);
	  $sql = "ALTER TABLE `program_peserta` CHANGE `peserta` `peserta` VARCHAR(30) NOT NULL";
	  $this->db->query($sql);
	  $sql = "ALTER TABLE `program_peserta` CHANGE `kartu_nik` `kartu_nik` VARCHAR(30) NOT NULL";
	  $this->db->query($sql);

	  // ubah/perbaiki struktur database, table artikel
	  $this->db->query('ALTER TABLE artikel MODIFY gambar VARCHAR(200) DEFAULT NULL;');
	  $this->db->query('ALTER TABLE artikel MODIFY gambar1 VARCHAR(200) DEFAULT NULL;');
	  $this->db->query('ALTER TABLE artikel MODIFY gambar2 VARCHAR(200) DEFAULT NULL;');
	  $this->db->query('ALTER TABLE artikel MODIFY gambar3 VARCHAR(200) DEFAULT NULL;');
	  $this->db->query('ALTER TABLE artikel MODIFY dokumen VARCHAR(400) DEFAULT NULL;');
	  $this->db->query('ALTER TABLE artikel MODIFY link_dokumen VARCHAR(200) DEFAULT NULL;');

		// Hapus kolom artikel tidak digunakan
  	if ($this->db->field_exists('jenis_widget', 'artikel'))
  	{
			$this->dbforge->drop_column('artikel', 'jenis_widget');
  	}
  }
}
