<?php
class Migrasi_1910_ke_1911 extends CI_model {

  public function up()
  {
  	// WNI sebagai nilai default untuk kolom kewarganegaraan
	  $this->dbforge->modify_column('tweb_penduduk', array('warganegara_id' => array('type' => 'TINYINT', 'constraint' => 4, 'null' => false, 'default' => 1)));
	  // Hapus modul yg salah tambah
	  $this->db->where('url', 'dokumen_sekretariat/peraturan_desa')->delete('setting_modul');
	  // Aktifkan submodul Pemetaan
		$submodul_peta = array('88'=>'plan', '89'=>'point', '90'=>'garis', '91'=>'line', '92'=>'area', '93'=>'polygon');
		foreach ($submodul_peta as $key => $submodul)
		{
			$modul_nonmenu = array(
				'id' => $key,
				'modul' => $submodul,
				'url' => $submodul,
				'aktif' => '1',
				'ikon' => '',
				'urut' => '',
				'level' => '',
				'parent' => '8',
				'hidden' => '2',
				'ikon_kecil' => ''
			);
			$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
			$this->db->query($sql);
		}
		// Update view supaya kolom baru ikut masuk
		$this->db->query("DROP VIEW penduduk_hidup");
		$this->db->query("CREATE VIEW penduduk_hidup AS SELECT * FROM tweb_penduduk WHERE status_dasar = 1");
		// Ubah kode surat
		$this->db
			->where('url_surat', 'surat_kuasa')
			->where('kode_surat', 'S-43')
			->update('tweb_surat_format', array('kode_surat' => 'S-47'));
		// Tambah surat
		$data = array(
			'nama'=>'Keterangan Kepemilikan Kendaraan',
			'url_surat'=>'surat_ket_kepemilikan_kendaraan',
			'kode_surat'=>'S-48',
			'jenis'=>1);
		$sql = $this->db->insert_string('tweb_surat_format', $data);
		$sql .= " ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis)";
		$this->db->query($sql);
  }
}
