<?php
class Migrasi_2006_ke_2007 extends CI_model {

	public function up()
	{
		$this->konfigurasi_web();
	}

	private function konfigurasi_web()
	{
	// Tambah menu Admin Web -> Konfigurasi
	$query = "
		INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `parent`, `hidden`, `ikon_kecil`) VALUES
		('211', 'Konfigurasi', 'setting/konfigurasi', '1', 'fa-gear', '11', '4', '13', '0', 'fa-gear')
		ON DUPLICATE KEY UPDATE url = VALUES(url);
	";
	$this->db->query($query);

	// Tambah parameter konfigurasi (sebelumnya parameter conf ini ada di /desa/config/config.php)
	$query = "
	INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES
	(31, 'daftar_bantuan', '1', 'Apakah akan tampilkan daftar penerima bantuan di statistik halaman muka', 'boolean', 'conf_web'),
	(32, 'apbdes_footer', '1', 'Apakah akan tampilkan grafik APBDes di halaman muka', 'boolean', 'conf_web'),
	(33, 'apbdes_footer_all', '0', 'Apakah akan tampilkan grafik APBDes di semua halaman', 'boolean', 'conf_web'),
	(34, 'apbdes_manual_input', '1', 'Apakah akan tampilkan grafik APBDes yang diinput secara manual', 'boolean', 'conf_web'),
	(35, 'covid_data', '1', 'Apakah akan tampilkan status Covid-19 Provinsi di halaman muka', 'boolean', 'conf_web'),
	(36, 'covid_desa', '1', 'Apakah akan tampilkan status Covid-19 Desa di halaman muka', 'boolean', 'conf_web'),
	(37, 'covid_rss', '0', 'Apakah akan tampilkan RSS Covid-19 di halaman muka', 'boolean', 'conf_web'),
	(38, 'provinsi_covid', '51', 'Kode provinsi status Covid-19 ', '', 'conf_web'),
	(39, 'statistik_chart_3d', '1', 'Apakah akan tampilkan Statistik Chart 3D', 'boolean', 'conf_web'),
	(40, 'daftar_bantuan_nama', '1', 'Apakah akan tampilkan Nama Peserta (bukan nama Kepala Keluarga) di daftar penerima bantuan di statistik halaman muka', 'boolean', 'conf_web')
	ON DUPLICATE KEY UPDATE url = VALUES(url);
	";
	$this->db->query($query);

	}

}
