-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table umum.setting_aplikasi
CREATE TABLE IF NOT EXISTS `setting_aplikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) DEFAULT NULL,
  `value` text,
  `keterangan` varchar(200) DEFAULT NULL,
  `jenis` varchar(30) DEFAULT NULL,
  `kategori` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=409 DEFAULT CHARSET=latin1;

-- Dumping data for table umum.setting_aplikasi: ~57 rows (approximately)
DELETE FROM `setting_aplikasi`;
/*!40000 ALTER TABLE `setting_aplikasi` DISABLE KEYS */;
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES
	(1, 'sebutan_kabupaten', 'kabupaten', 'Pengganti sebutan wilayah kabupaten', '', ''),
	(2, 'sebutan_kabupaten_singkat', 'kab.', 'Pengganti sebutan singkatan wilayah kabupaten', '', ''),
	(3, 'sebutan_kecamatan', 'kecamatan', 'Pengganti sebutan wilayah kecamatan', '', ''),
	(4, 'sebutan_kecamatan_singkat', 'kec.', 'Pengganti sebutan singkatan wilayah kecamatan', '', ''),
	(5, 'sebutan_desa', 'desa', 'Pengganti sebutan wilayah desa', '', ''),
	(6, 'sebutan_dusun', 'dusun', 'Pengganti sebutan wilayah dusun', '', ''),
	(7, 'sebutan_camat', 'camat', 'Pengganti sebutan jabatan camat', '', ''),
	(8, 'website_title', 'Website Resmi', 'Judul tab browser modul web', '', 'web'),
	(9, 'login_title', 'OpenSID', 'Judul tab browser halaman login modul administrasi', '', ''),
	(10, 'admin_title', 'Sistem Informasi', 'Judul tab browser modul administrasi', '', ''),
	(11, 'web_theme', 'natra', 'Tema penampilan modul web', '', 'web'),
	(12, 'offline_mode', '0', 'Apakah modul web akan ditampilkan atau tidak', 'option-kode', ''),
	(13, 'enable_track', '1', 'Apakah akan mengirimkan data statistik ke tracker', 'boolean', ''),
	(16, 'mapbox_key', 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', 'Mapbox API Key untuk peta', '', 'web'),
	(17, 'libreoffice_path', '', 'Path tempat instal libreoffice di server SID', '', ''),
	(18, 'sumber_gambar_slider', '2', 'Sumber gambar slider besar', NULL, NULL),
	(19, 'sebutan_singkatan_kadus', 'kawil', 'Sebutan singkatan jabatan kepala dusun', NULL, NULL),
	(20, 'current_version', '22.07', 'Versi sekarang untuk migrasi', NULL, 'readonly'),
	(21, 'timezone', 'Asia/Jakarta', 'Zona waktu perekaman waktu dan tanggal', NULL, NULL),
	(23, 'web_artikel_per_page', '8', 'Jumlah artikel dalam satu halaman', 'int', 'web_theme'),
	(24, 'penomoran_surat', '2', 'Penomoran surat mulai dari satu (1) setiap tahun', 'option', NULL),
	(25, 'dashboard_program_bantuan', '1', 'ID program bantuan yang ditampilkan di dashboard', 'int', 'dashboard'),
	(26, 'panjang_nomor_surat', '', 'Nomor akan diisi \'0\' di sebelah kiri, kalau perlu', 'int', 'surat'),
	(27, 'warna_tema_admin', 'skin-purple', 'Warna dasar tema komponen Admin', 'option-value', NULL),
	(28, 'format_nomor_surat', '[kode_surat]/[nomor_surat, 3]/[kode_desa]/[bulan_romawi]/[tahun]', 'Fomat penomoran surat', NULL, NULL),
	(30, 'penggunaan_server', '1	', 'Setting penggunaan server', 'int', 'sistem'),
	(31, 'daftar_penerima_bantuan', '1', 'Apakah akan tampilkan daftar penerima bantuan di statistik halaman muka', 'boolean', 'conf_web'),
	(32, 'apbdes_footer', '1', 'Apakah akan tampilkan grafik APBDes di halaman muka', 'boolean', 'conf_web'),
	(33, 'apbdes_footer_all', '1', 'Apakah akan tampilkan grafik APBDes di semua halaman', 'boolean', 'conf_web'),
	(34, 'apbdes_manual_input', '0', 'Apakah akan tampilkan grafik APBDes yang diinput secara manual', 'boolean', 'conf_web'),
	(35, 'covid_data', '0', 'Apakah akan tampilkan status Covid-19 Provinsi di halaman muka', 'boolean', 'conf_web'),
	(36, 'covid_desa', '0', 'Apakah akan tampilkan status Covid-19 Desa di halaman muka', 'boolean', 'conf_web'),
	(37, 'covid_rss', '0', 'Apakah akan tampilkan RSS Covid-19 di halaman muka', 'boolean', 'conf_web'),
	(38, 'provinsi_covid', '52', 'Kode provinsi status Covid-19 ', 'int', 'conf_web'),
	(39, 'statistik_chart_3d', '1', 'Apakah akan tampilkan Statistik Chart 3D', 'boolean', 'conf_web'),
	(40, 'sebutan_nip_desa', 'NIPD', 'Pengganti sebutan label niap/nipd', NULL, NULL),
	(43, 'token_opensid', '', 'Token OpenSID', '', 'sistem'),
	(44, 'layanan_mandiri', '1', 'Apakah layanan mandiri ditampilkan atau tidak', 'boolean', 'setting_mandiri'),
	(45, 'ukuran_lebar_bagan', '800', 'Ukuran Lebar Bagan (800 / 1200 / 1400)', 'int', 'conf_bagan'),
	(48, 'layanan_opendesa_token', '', 'Token pelanggan Layanan OpenDESA', 'textarea', 'pelanggan'),
	(55, 'api_opendk_server', '', 'Alamat Server OpenDK (contoh: https://demo.opendk.my.id)', NULL, 'opendk'),
	(56, 'api_opendk_key', '', 'OpenDK API Key untuk Sinkronisasi Data', NULL, 'opendk'),
	(57, 'api_opendk_user', '', 'Email Login Pengguna OpenDK', NULL, 'opendk'),
	(58, 'api_opendk_password', '', 'Password Login Pengguna OpenDK', NULL, 'opendk'),
	(65, 'sebutan_kepala_desa', 'Kepala', 'Pengganti sebutan jabatan Kepala Desa', NULL, NULL),
	(66, 'tgl_data_lengkap', NULL, 'Atur data tanggal sudah lengkap', 'datetime', NULL),
	(67, 'tgl_data_lengkap_aktif', '0', 'Aktif / Non-aktif data tanggal sudah lengkap', 'boolean', NULL),
	(100, 'api_gform_id_script', '', 'Script ID untuk Google API', NULL, 'setting_analisis'),
	(101, 'api_gform_credential', '', 'Credential untuk Google API', 'textarea', 'setting_analisis'),
	(102, 'api_gform_redirect_uri', 'https://berputar.opensid.or.id/index.php/first/get_form_info', 'Redirecet URI untuk Google API', NULL, 'setting_analisis'),
	(179, 'tampilkan_lapak_web', '1', 'Aktif / Non-aktif Lapak di Halaman Website Url Terpisah', 'boolean', 'lapak'),
	(180, 'pesan_singkat_wa', 'Saya ingin membeli [nama_produk] yang anda tawarkan di Lapak Desa [link_web]', 'Pesan Singkat WhatsApp', 'textarea', 'lapak'),
	(181, 'banyak_foto_tiap_produk', '3', 'Banyaknya foto tiap produk yang bisa di unggah', 'int', 'lapak'),
	(182, 'jumlah_produk_perhalaman', '10', 'Jumlah produk yang ditampilkan dalam satu halaman', 'int', 'lapak'),
	(406, 'telegram_token', '', 'Telgram token', NULL, 'sistem'),
	(407, 'telegram_user_id', '', 'Telgram user id untuk notifikasi ke pengguna', NULL, 'sistem'),
	(409, 'tampil_luas_peta', '0', 'Tampilkan Luas Peta', 'boolean', NULL);
/*!40000 ALTER TABLE `setting_aplikasi` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
