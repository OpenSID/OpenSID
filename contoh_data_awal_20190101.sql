DROP VIEW IF EXISTS data_surat;
DROP VIEW IF EXISTS daftar_kontak;
DROP VIEW IF EXISTS daftar_anggota_grup;
DROP VIEW IF EXISTS daftar_grup;
DROP VIEW IF EXISTS penduduk_hidup;
DROP TABLE IF EXISTS setting_aplikasi_options;
DROP TABLE IF EXISTS data_persil;
DROP TABLE IF EXISTS tweb_penduduk_mandiri;
DROP TABLE IF EXISTS disposisi_surat_masuk;
DROP TABLE IF EXISTS mutasi_inventaris_tanah;
DROP TABLE IF EXISTS mutasi_inventaris_peralatan;
DROP TABLE IF EXISTS mutasi_inventaris_jalan;
DROP TABLE IF EXISTS mutasi_inventaris_gedung;
DROP TABLE IF EXISTS mutasi_inventaris_asset;
DROP TABLE IF EXISTS anggota_grup_kontak;
DROP TABLE IF EXISTS kontak;
DROP TABLE IF EXISTS suplemen_terdata;
#
# TABLE STRUCTURE FOR: analisis_indikator
#

DROP TABLE IF EXISTS `analisis_indikator`;

CREATE TABLE `analisis_indikator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_master` int(11) NOT NULL,
  `nomor` int(3) NOT NULL,
  `pertanyaan` varchar(400) NOT NULL,
  `id_tipe` tinyint(4) NOT NULL DEFAULT '1',
  `bobot` tinyint(4) NOT NULL DEFAULT '0',
  `act_analisis` tinyint(1) NOT NULL DEFAULT '2',
  `id_kategori` tinyint(4) NOT NULL,
  `is_publik` tinyint(1) NOT NULL DEFAULT '0',
  `is_teks` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_master` (`id_master`,`id_tipe`),
  KEY `id_tipe` (`id_tipe`),
  KEY `id_kategori` (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=latin1;

INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (1, 2, 1, 'kepemilikan rumah', 1, 1, 1, 1, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (2, 2, 2, 'penghasilan perbulan', 1, 4, 1, 2, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (3, 3, 1, 'Jumlah Penghasilan Perbulan', 3, 0, 0, 3, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (4, 3, 2, 'Jumlah Pengeluaran Perbulan', 3, 0, 0, 3, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (5, 3, 3, 'Status Kepemilikan Rumah?*', 1, 0, 0, 3, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (6, 3, 4, 'Kategori KK', 1, 0, 0, 3, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (7, 3, 5, 'Penerima Raskin', 1, 0, 0, 3, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (8, 3, 6, 'Penerima BLT/BLSM', 1, 0, 0, 3, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (9, 3, 7, 'Peserta BPJS/Jamkesmas/Jamkesda', 1, 0, 0, 3, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (10, 3, 8, 'Sumber Air Minum?*', 1, 0, 0, 4, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (11, 3, 9, 'Keterangan', 2, 0, 0, 4, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (12, 3, 10, 'Jenis Lahan', 1, 0, 0, 5, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (13, 3, 11, 'Luas Lahan', 1, 0, 0, 5, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (14, 3, 12, 'Jenis Komoditas', 1, 0, 0, 6, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (15, 3, 13, 'Produksi', 3, 0, 0, 6, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (16, 3, 14, 'Satuan', 1, 0, 0, 6, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (17, 3, 15, 'Nilai (Rp)', 3, 0, 0, 6, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (18, 3, 16, 'Pemasaran Hasil', 1, 0, 0, 6, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (19, 3, 17, 'Jenis Komoditas', 1, 0, 0, 7, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (20, 3, 18, 'Jumlah Pohon', 3, 0, 0, 7, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (21, 3, 19, 'Produksi', 3, 0, 0, 7, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (22, 3, 20, 'Satuan', 1, 0, 0, 7, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (23, 3, 21, 'Nilai (Rp)', 3, 0, 0, 7, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (24, 3, 22, 'Pemasaran Hasil', 1, 0, 0, 7, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (25, 3, 23, 'Jenis Komoditas', 1, 0, 0, 8, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (26, 3, 24, 'Produksi', 3, 0, 0, 8, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (27, 3, 25, 'Satuan', 1, 0, 0, 8, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (28, 3, 26, 'Nilai (Rp)', 3, 0, 0, 8, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (29, 3, 27, 'Pemasaran Hasil', 1, 0, 0, 8, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (30, 3, 28, 'Jenis Komoditas', 1, 0, 0, 9, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (31, 3, 29, 'Produksi', 3, 0, 0, 9, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (32, 3, 30, 'Satuan', 1, 0, 0, 9, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (33, 3, 31, 'Nilai (Rp)', 3, 0, 0, 9, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (34, 3, 32, 'Pemasaran Hasil', 1, 0, 0, 9, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (35, 3, 33, 'Jenis Komoditas', 1, 0, 0, 10, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (36, 3, 34, 'Produksi', 3, 0, 0, 10, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (37, 3, 35, 'Satuan', 1, 0, 0, 10, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (38, 3, 36, 'Nilai (Rp)', 3, 0, 0, 10, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (39, 3, 37, 'Pemasaran Hasil', 1, 0, 0, 10, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (40, 3, 38, 'Jenis Komoditas', 1, 0, 0, 11, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (41, 3, 39, 'Produksi', 3, 0, 0, 11, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (42, 3, 40, 'Satuan', 1, 0, 0, 11, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (43, 3, 41, 'Nilai (Rp)', 3, 0, 0, 11, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (44, 3, 42, 'Pemasaran Hasil', 1, 0, 0, 11, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (45, 3, 43, 'Jenis Komoditas', 1, 0, 0, 12, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (46, 3, 44, 'Produksi', 3, 0, 0, 12, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (47, 3, 45, 'Satuan', 1, 0, 0, 12, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (48, 3, 46, 'Nilai (Rp)', 3, 0, 0, 12, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (49, 3, 47, 'Pemasaran Hasil', 1, 0, 0, 12, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (50, 3, 48, 'Jenis Bahan Galian', 1, 0, 0, 13, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (51, 3, 49, 'Milik Perorangan (Ha)', 3, 0, 0, 13, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (52, 3, 50, 'Milik Adat (Ha)', 3, 0, 0, 13, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (53, 3, 51, 'Satuan', 1, 0, 0, 13, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (54, 3, 52, 'Pemasaran', 1, 0, 0, 13, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (55, 3, 53, 'Jenis Komoditas', 1, 0, 0, 14, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (56, 3, 54, 'Produksi', 3, 0, 0, 14, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (57, 3, 55, 'Satuan', 1, 0, 0, 14, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (58, 3, 56, 'Nilai (Rp)', 3, 0, 0, 14, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (59, 3, 57, 'Pemasaran Hasil', 1, 0, 0, 14, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (60, 3, 58, 'Nama Alat', 1, 0, 0, 15, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (61, 3, 59, 'Jumlah', 3, 0, 0, 15, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (62, 3, 60, 'Pemanfaatan Sungai/Waduk DLL', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (63, 3, 61, 'Lembaga Pendidikan', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (64, 3, 62, 'Penguasaan Aset Tanah', 1, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (65, 3, 63, 'Aset Sarana Transportasi Umum', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (66, 3, 64, 'Aset Sarana Produksi', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (67, 3, 65, 'Aset Rumah (Dinding)', 1, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (68, 3, 66, 'Aset Rumah (Lantai)', 1, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (69, 3, 67, 'Aset Rumah (Atap)', 1, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (70, 3, 68, 'Aset Lainnya', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (71, 3, 69, 'Kualitas Ibu Hamil', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (72, 3, 70, 'Kualitas Bayi', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (73, 3, 71, 'Tempat Persalinan', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (74, 3, 72, 'Pertolongan Persalinan', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (75, 3, 73, 'Cakupan Imunisasi', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (76, 3, 74, 'Penderita Sakit Kelainan', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (77, 3, 75, 'Perilaku Hidup Bersih', 1, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (78, 3, 76, 'Pola Makan', 1, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (79, 3, 77, 'Kebiasaan Berobat', 1, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (80, 3, 78, 'Status Gizi Balita', 1, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (81, 3, 79, 'Jenis Penyakit', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (82, 3, 80, 'Kerukunan', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (83, 3, 81, 'Perkelahian', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (84, 3, 82, 'Pencurian', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (85, 3, 83, 'Penjarahan', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (86, 3, 84, 'Perjudian', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (87, 3, 85, 'Pemakaian Miras dan Narkoba', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (88, 3, 86, 'Pembunuhan', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (89, 3, 87, 'Penculikan', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (90, 3, 88, 'Kejahatan Seksual', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (91, 3, 89, 'Kekerasan Dalam Rumah Tangga', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (92, 3, 90, 'Masalah Kesejahteraan Keluarga', 2, 0, 0, 16, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (93, 4, 1, 'Nomor Akte Kelahiran', 4, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (94, 4, 2, 'Hubungan dengan Kepala Keluarga', 1, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (95, 4, 3, 'Status Perkawinan', 1, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (96, 4, 4, 'Agama dan Aliran Kepercayaan', 1, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (97, 4, 5, 'Golongan Darah', 1, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (98, 4, 6, 'Kewarganegaraan', 1, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (99, 4, 7, 'Etnis/Suku', 4, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (100, 4, 8, 'Pendidikan Umum Terakhir', 1, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (101, 4, 9, 'Mata Pencaharian Pokok/Pekerjaan', 1, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (102, 4, 10, 'Nama Bapak Kandung', 4, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (103, 4, 11, 'Nama Ibu Kandung', 4, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (104, 4, 12, 'Akseptor KB', 1, 0, 0, 17, 0, 0);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (105, 4, 13, 'Cacat Fisik', 2, 0, 0, 17, 0, 1);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (106, 4, 14, 'Cacat Mental', 2, 0, 0, 17, 0, 1);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (107, 4, 15, 'Kedudukan Anggota Keluarga sebagai Wajib Pajak dan Retribusi', 2, 0, 0, 17, 0, 1);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (108, 4, 16, 'Lembaga Pemerintahan Yang Diikuti Anggota Keluarga', 2, 0, 0, 17, 0, 1);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (109, 4, 17, 'Lembaga Kemasyarakatan Yang Diikuti Anggota Keluarga', 2, 0, 0, 17, 0, 1);
INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES (110, 4, 18, 'Lembaga Ekonomi Yang Dimiliki Anggota Keluarga', 2, 0, 0, 17, 0, 1);


#
# TABLE STRUCTURE FOR: analisis_kategori_indikator
#

DROP TABLE IF EXISTS `analisis_kategori_indikator`;

CREATE TABLE `analisis_kategori_indikator` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `id_master` tinyint(4) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `kategori_kode` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_master` (`id_master`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (1, 2, 'Aset', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (2, 2, 'Penghasilan', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (3, 3, 'PENGHASILAN DAN PENGELUARAN KELUARGA', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (4, 3, 'SUMBER AIR MINUM KELUARGA', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (5, 3, 'KEPEMILIKAN LAHAN KELUARGA', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (6, 3, 'PRODUKSI TANAMAN PANGAN', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (7, 3, 'PRODUKSI BUAH-BUAHAN', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (8, 3, 'PRODUKSI TANAMAN OBAT', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (9, 3, 'PRODUKSI PERKEBUNAN', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (10, 3, 'PRODUKSI HASIL HUTAN', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (11, 3, 'JENIS TERNAK', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (12, 3, 'PRODUKSI PERIKANAN', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (13, 3, 'PRODUKSI BAHAN GALIAN', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (14, 3, 'PENGOLAHAN HASIL TERNAK', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (15, 3, 'ALAT PRODUKSI PERIKANAN', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (16, 3, 'PEMANFAATAN AIR, ASET RUMAH DLL', '');
INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES (17, 4, 'Data Anggota Keluarga', '');


#
# TABLE STRUCTURE FOR: analisis_klasifikasi
#

DROP TABLE IF EXISTS `analisis_klasifikasi`;

CREATE TABLE `analisis_klasifikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_master` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `minval` double(5,2) NOT NULL,
  `maxval` double(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_master` (`id_master`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `analisis_klasifikasi` (`id`, `id_master`, `nama`, `minval`, `maxval`) VALUES (1, 2, 'Miskin', '5.00', '10.00');
INSERT INTO `analisis_klasifikasi` (`id`, `id_master`, `nama`, `minval`, `maxval`) VALUES (2, 2, 'Sedang', '11.00', '20.00');
INSERT INTO `analisis_klasifikasi` (`id`, `id_master`, `nama`, `minval`, `maxval`) VALUES (3, 2, 'Kaya', '21.00', '25.00');


#
# TABLE STRUCTURE FOR: analisis_master
#

DROP TABLE IF EXISTS `analisis_master`;

CREATE TABLE `analisis_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(40) NOT NULL,
  `subjek_tipe` tinyint(4) NOT NULL,
  `lock` tinyint(1) NOT NULL DEFAULT '1',
  `deskripsi` text NOT NULL,
  `kode_analisis` varchar(5) NOT NULL DEFAULT '00000',
  `id_kelompok` int(11) NOT NULL,
  `pembagi` varchar(10) NOT NULL DEFAULT '100',
  `id_child` smallint(4) NOT NULL,
  `format_impor` tinyint(2) NOT NULL,
  `jenis` tinyint(2) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `analisis_master` (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`, `format_impor`, `jenis`) VALUES (1, 'Analisis Keahlian Individu', 1, 1, '<p>survey</p>', '00000', 0, '1', 0, 0, 2);
INSERT INTO `analisis_master` (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`, `format_impor`, `jenis`) VALUES (2, 'AKP Lombok Tengah', 2, 1, '<p>keterangan</p>', '00000', 0, '1', 0, 0, 2);
INSERT INTO `analisis_master` (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`, `format_impor`, `jenis`) VALUES (3, 'Data Dasar Keluarga (Prodeskel)', 2, 1, 'Pendataan Profil Desa', 'DDK02', 0, '', 0, 0, 1);
INSERT INTO `analisis_master` (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`, `format_impor`, `jenis`) VALUES (4, 'Data Anggota Keluarga (Prodeskel)', 1, 1, 'Pendataan Profil Desa', 'DAK02', 0, '', 0, 0, 1);


#
# TABLE STRUCTURE FOR: analisis_parameter
#

DROP TABLE IF EXISTS `analisis_parameter`;

CREATE TABLE `analisis_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_indikator` int(11) NOT NULL,
  `jawaban` varchar(200) NOT NULL,
  `nilai` tinyint(4) NOT NULL DEFAULT '0',
  `kode_jawaban` int(3) NOT NULL,
  `asign` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_indikator` (`id_indikator`)
) ENGINE=InnoDB AUTO_INCREMENT=1052 DEFAULT CHARSET=latin1;

INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1, 1, 'milik sendiri', 5, 1, 0);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (2, 1, 'milik orang tua', 4, 2, 0);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (3, 1, 'kontrak', 1, 3, 0);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (4, 2, '< Rp.500.000,-', 1, 1, 0);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (5, 2, 'Rp 500.000,- sampa Rp 1.000.000,-', 3, 2, 0);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (6, 2, 'diatas Rp 2.000.000,-', 5, 3, 0);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (7, 5, 'Milik Sendiri', 0, 169, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (8, 5, 'Milik Orang Tua', 0, 170, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (9, 5, 'Milik Keluarga', 0, 171, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (10, 5, 'Sewa/Kontrak', 0, 172, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (11, 5, 'Pinjam Pakai', 0, 173, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (12, 6, 'Pra Sejahtera', 0, 0, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (13, 6, 'Sejahtera 1', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (14, 6, 'Sejahtera 2', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (15, 6, 'Sejahtera 3+', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (16, 7, 'Ya', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (17, 7, 'Tidak', 0, 0, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (18, 8, 'Ya', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (19, 8, 'Tidak', 0, 0, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (20, 9, 'Ya', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (21, 9, 'Tidak', 0, 0, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (22, 10, 'Bak penampung air hujan', 0, 503, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (23, 10, 'Beli dari tangki swasta', 0, 504, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (24, 10, 'Depot isi ulang', 0, 505, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (25, 10, 'Embung', 0, 502, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (26, 10, 'Hidran umum', 0, 498, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (27, 10, 'Mata air', 0, 495, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (28, 10, 'PAM', 0, 499, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (29, 10, 'Pipa', 0, 500, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (30, 10, 'Sumber Air Resapan Umum', 0, 1741, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (31, 10, 'Sumur gali', 0, 496, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (32, 10, 'Sumur pompa', 0, 497, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (33, 10, 'Sungai', 0, 501, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (34, 11, 'Baik', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (35, 11, 'Berasa', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (36, 11, 'Berwarna', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (37, 11, 'Berbau', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (38, 12, 'Hutan', 0, 952, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (39, 12, 'Perkebunan', 0, 951, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (40, 12, 'Tanaman Pangan', 0, 950, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (41, 13, 'Memiliki kurang 0,5 ha', 0, 1732, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (42, 13, 'Memiliki 0,5 - 1,0 ha', 0, 1733, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (43, 13, 'Memiliki lebih dari 1,0 ha', 0, 1734, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (44, 13, 'Tidak memiliki', 0, 1735, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (45, 14, 'Bawah Merah', 0, 12, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (46, 14, 'Bawang Putih', 0, 13, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (47, 14, 'Bayam', 0, 22, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (48, 14, 'Brocoli', 0, 20, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (49, 14, 'Buncis', 0, 19, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (50, 14, 'Cabe', 0, 11, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (51, 14, 'Jagung', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (52, 14, 'Jamur', 0, 78, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (53, 14, 'Jeruk Nipis', 0, 48, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (54, 14, 'Kacang Hijau', 0, 253, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (55, 14, 'Kacang Kedelai', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (56, 14, 'Kacang Merah', 0, 6, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (57, 14, 'Kacang Panjang', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (58, 14, 'Kacang Tanah', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (59, 14, 'Kacang Turis', 0, 24, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (60, 14, 'Kangkung', 0, 23, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (61, 14, 'Kemiri', 0, 96, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (62, 14, 'Kentang', 0, 16, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (63, 14, 'Kubis', 0, 17, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (64, 14, 'Mentimun', 0, 18, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (65, 14, 'Padi Ladang', 0, 8, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (66, 14, 'Padi Sawah', 0, 7, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (67, 14, 'Sawi', 0, 15, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (68, 14, 'Selada', 0, 26, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (69, 14, 'Terong', 0, 21, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (70, 14, 'Tomat', 0, 14, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (71, 14, 'Tumpang Sari', 0, 29, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (72, 14, 'Ubi Jalar', 0, 10, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (73, 14, 'Ubi Kayu', 0, 9, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (74, 14, 'Umbi-Umbian Lain', 0, 25, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (75, 14, 'Wortel', 0, 28, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (76, 16, 'BATANG/TH', 0, 1746, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (77, 16, 'BUAH/TH ', 0, 1013, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (78, 16, 'EKOR/TH ', 0, 1745, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (79, 16, 'JENIS/TH', 0, 965, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (80, 16, 'KG/TH', 0, 960, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (81, 16, 'LITER/TH', 0, 962, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (82, 16, 'M/TH', 0, 963, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (83, 16, 'M3/TH', 0, 961, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (84, 16, 'TON/TH', 0, 966, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (85, 16, 'UNIT/TH', 0, 964, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (86, 18, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (87, 18, 'Dijual ke pasar', 0, 489, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (88, 18, 'Dijual langsung ke konsumen', 0, 488, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (89, 18, 'Dijual melalui KUD', 0, 490, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (90, 18, 'Dijual melalui Pengecer', 0, 492, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (91, 18, 'Dijual melalui Tengkulak', 0, 491, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (92, 18, 'Tidak dijual', 0, 494, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (93, 19, 'Alpokat', 0, 31, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (94, 19, 'Anggur', 0, 54, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (95, 19, 'Apel', 0, 36, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (96, 19, 'Belimbing', 0, 38, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (97, 19, 'Duku', 0, 41, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (98, 19, 'Durian', 0, 39, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (99, 19, 'Gandaria', 0, 258, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (100, 19, 'Jambu air', 0, 50, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (101, 19, 'Jambu klutuk', 0, 57, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (102, 19, 'Jambu Mete', 0, 88, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (103, 19, 'Jeruk', 0, 30, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (104, 19, 'Kedondong', 0, 53, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (105, 19, 'Kesemek', 0, 257, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (106, 19, 'Kokosan', 0, 42, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (107, 19, 'Lengkeng', 0, 45, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (108, 19, 'Limau', 0, 47, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (109, 19, 'Mangga', 0, 32, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (110, 19, 'Manggis', 0, 34, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (111, 19, 'Markisa', 0, 44, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (112, 19, 'Matoa', 0, 249, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (113, 19, 'Melinjo', 0, 55, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (114, 19, 'Melon', 0, 49, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (115, 19, 'Murbei', 0, 58, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (116, 19, 'Nangka', 0, 51, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (117, 19, 'Nenas', 0, 56, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (118, 19, 'Pepaya', 0, 37, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (119, 19, 'Pisang', 0, 43, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (120, 19, 'Rambutan', 0, 33, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (121, 19, 'Salak', 0, 35, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (122, 19, 'Sawo', 0, 40, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (123, 19, 'Semangka', 0, 46, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (124, 19, 'Sirsak', 0, 52, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (125, 19, 'Stroberi', 0, 255, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (126, 19, 'Talas', 0, 27, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (127, 22, 'BATANG/TH', 0, 1746, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (128, 22, 'BUAH/TH ', 0, 1013, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (129, 22, 'EKOR/TH ', 0, 1745, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (130, 22, 'JENIS/TH', 0, 965, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (131, 22, 'KG/TH', 0, 960, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (132, 22, 'LITER/TH', 0, 962, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (133, 22, 'M/TH', 0, 963, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (134, 22, 'M3/TH', 0, 961, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (135, 22, 'TON/TH', 0, 966, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (136, 22, 'UNIT/TH', 0, 964, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (137, 24, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (138, 24, 'Dijual ke pasar', 0, 489, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (139, 24, 'Dijual langsung ke konsumen', 0, 488, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (140, 24, 'Dijual melalui KUD', 0, 490, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (141, 24, 'Dijual melalui Pengecer', 0, 492, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (142, 24, 'Dijual melalui Tengkulak', 0, 491, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (143, 24, 'Tidak dijual', 0, 494, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (144, 25, 'Akar Wangi', 0, 76, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (145, 25, 'Buah Merah', 0, 65, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (146, 25, 'Daun Dewa', 0, 63, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (147, 25, 'Daun Sereh', 0, 74, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (148, 25, 'Daun Sirih', 0, 72, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (149, 25, 'Dewi-Dewi', 0, 79, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (150, 25, 'Jahe', 0, 59, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (151, 25, 'Jamur', 0, 252, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (152, 25, 'Kayu Manis', 0, 73, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (153, 25, 'Kencur', 0, 77, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (154, 25, 'Kumis Kucing', 0, 64, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (155, 25, 'Kunyit', 0, 60, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (156, 25, 'Lengkuas', 0, 61, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (157, 25, 'Mahkota Dewa', 0, 75, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (158, 25, 'Mengkudu', 0, 62, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (159, 25, 'Sambiloto', 0, 66, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (160, 25, 'Temu Hitam', 0, 68, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (161, 25, 'Temu Kunci', 0, 71, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (162, 25, 'Temu Putih', 0, 69, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (163, 25, 'Temu Putri', 0, 70, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (164, 25, 'Temulawak', 0, 67, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (165, 27, 'BATANG/TH', 0, 1746, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (166, 27, 'BUAH/TH ', 0, 1013, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (167, 27, 'EKOR/TH ', 0, 1745, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (168, 27, 'JENIS/TH', 0, 965, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (169, 27, 'KG/TH', 0, 960, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (170, 27, 'LITER/TH', 0, 962, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (171, 27, 'M/TH', 0, 963, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (172, 27, 'M3/TH', 0, 961, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (173, 27, 'TON/TH', 0, 966, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (174, 27, 'UNIT/TH', 0, 964, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (175, 29, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (176, 29, 'Dijual ke pasar', 0, 489, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (177, 29, 'Dijual langsung ke konsumen', 0, 488, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (178, 29, 'Dijual melalui KUD', 0, 490, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (179, 29, 'Dijual melalui Pengecer', 0, 492, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (180, 29, 'Dijual melalui Tengkulak', 0, 491, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (181, 29, 'Tidak dijual', 0, 494, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (182, 30, 'Cengkeh', 0, 83, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (183, 30, 'Coklat', 0, 84, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (184, 30, 'Jarak kepyar', 0, 93, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (185, 30, 'Jarak pagar', 0, 92, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (186, 30, 'Kacang mede', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (187, 30, 'Kapuk', 0, 95, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (188, 30, 'Karet', 0, 87, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (189, 30, 'Kelapa', 0, 80, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (190, 30, 'Kelapa sawit', 0, 81, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (191, 30, 'Kemiri', 0, 256, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (192, 30, 'Kopi', 0, 82, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (193, 30, 'Lada', 0, 86, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (194, 30, 'Pala', 0, 90, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (195, 30, 'Pinang', 0, 85, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (196, 30, 'Tebu', 0, 94, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (197, 30, 'Teh', 0, 97, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (198, 30, 'Tembakau', 0, 89, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (199, 30, 'Vanili', 0, 91, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (200, 32, 'BATANG/TH', 0, 1746, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (201, 32, 'BUAH/TH ', 0, 1013, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (202, 32, 'EKOR/TH ', 0, 1745, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (203, 32, 'JENIS/TH', 0, 965, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (204, 32, 'KG/TH', 0, 960, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (205, 32, 'LITER/TH', 0, 962, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (206, 32, 'M/TH', 0, 963, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (207, 32, 'M3/TH', 0, 961, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (208, 32, 'TON/TH', 0, 966, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (209, 32, 'UNIT/TH', 0, 964, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (210, 34, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (211, 34, 'Dijual ke pasar', 0, 489, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (212, 34, 'Dijual langsung ke konsumen', 0, 488, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (213, 34, 'Dijual melalui KUD', 0, 490, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (214, 34, 'Dijual melalui Pengecer', 0, 492, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (215, 34, 'Dijual melalui Tengkulak', 0, 491, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (216, 34, 'Tidak dijual', 0, 494, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (217, 35, 'Arang', 0, 121, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (218, 35, 'Bambu', 0, 102, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (219, 35, 'Cemara', 0, 109, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (220, 35, 'Damar', 0, 101, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (221, 35, 'Enau', 0, 107, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (222, 35, 'Gambir', 0, 117, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (223, 35, 'Gula enau', 0, 119, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (224, 35, 'Gula lontar', 0, 120, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (225, 35, 'Ijuk Enau', 0, 245, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (226, 35, 'Jati', 0, 103, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (227, 35, 'Kayu', 0, 98, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (228, 35, 'Kayu Bakar', 0, 247, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (229, 35, 'Kayu besi', 0, 114, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (230, 35, 'Kayu cendana', 0, 110, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (231, 35, 'Kayu gaharu', 0, 111, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (232, 35, 'Kayu Sengon', 0, 246, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (233, 35, 'Kayu ulin', 0, 115, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (234, 35, 'Kemenyan', 0, 116, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (235, 35, 'Lontar', 0, 105, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (236, 35, 'Madu lebah', 0, 99, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (237, 35, 'Mahoni', 0, 108, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (238, 35, 'Meranti', 0, 113, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (239, 35, 'Minyak kayu putih', 0, 118, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (240, 35, 'Nilam', 0, 104, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (241, 35, 'Rotan', 0, 100, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (242, 35, 'Rumbia', 0, 259, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (243, 35, 'Sagu', 0, 106, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (244, 35, 'Sarang burung', 0, 112, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (245, 37, 'BATANG/TH', 0, 1746, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (246, 37, 'BUAH/TH ', 0, 1013, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (247, 37, 'EKOR/TH ', 0, 1745, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (248, 37, 'JENIS/TH', 0, 965, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (249, 37, 'KG/TH', 0, 960, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (250, 37, 'LITER/TH', 0, 962, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (251, 37, 'M/TH', 0, 963, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (252, 37, 'M3/TH', 0, 961, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (253, 37, 'TON/TH', 0, 966, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (254, 37, 'UNIT/TH', 0, 964, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (255, 39, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (256, 39, 'Dijual ke pasar', 0, 489, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (257, 39, 'Dijual langsung ke konsumen', 0, 488, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (258, 39, 'Dijual melalui KUD', 0, 490, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (259, 39, 'Dijual melalui Pengecer', 0, 492, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (260, 39, 'Dijual melalui Tengkulak', 0, 491, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (261, 39, 'Tidak dijual', 0, 494, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (262, 40, 'Angsa', 0, 131, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (263, 40, 'Anjing', 0, 135, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (264, 40, 'Ayam kampung', 0, 125, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (265, 40, 'Babi', 0, 124, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (266, 40, 'Bebek', 0, 127, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (267, 40, 'Buaya', 0, 145, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (268, 40, 'Burung beo', 0, 142, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (269, 40, 'Burung cendrawasih', 0, 140, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (270, 40, 'Burung kakatua', 0, 141, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (271, 40, 'Burung langka lainnya', 0, 144, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (272, 40, 'Burung merak', 0, 143, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (273, 40, 'Burung Merpati', 0, 244, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (274, 40, 'Burung onta', 0, 138, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (275, 40, 'Burung puyuh', 0, 132, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (276, 40, 'Domba', 0, 130, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (277, 40, 'Jenis ayam broiler', 0, 126, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (278, 40, 'Kambing', 0, 129, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (279, 40, 'Kelinci', 0, 133, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (280, 40, 'Kerbau', 0, 123, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (281, 40, 'Kucing', 0, 136, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (282, 40, 'Kuda', 0, 128, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (283, 40, 'Sapi', 0, 122, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (284, 40, 'Tuna', 0, 146, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (285, 40, 'Ular cobra', 0, 137, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (286, 40, 'Ular pithon', 0, 139, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (287, 42, 'BATANG/TH', 0, 1746, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (288, 42, 'BUAH/TH ', 0, 1013, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (289, 42, 'EKOR/TH ', 0, 1745, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (290, 42, 'JENIS/TH', 0, 965, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (291, 42, 'KG/TH', 0, 960, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (292, 42, 'LITER/TH', 0, 962, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (293, 42, 'M/TH', 0, 963, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (294, 42, 'M3/TH', 0, 961, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (295, 42, 'TON/TH', 0, 966, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (296, 42, 'UNIT/TH', 0, 964, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (297, 44, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (298, 44, 'Dijual ke pasar', 0, 489, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (299, 44, 'Dijual langsung ke konsumen', 0, 488, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (300, 44, 'Dijual melalui KUD', 0, 490, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (301, 44, 'Dijual melalui Pengecer', 0, 492, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (302, 44, 'Dijual melalui Tengkulak', 0, 491, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (303, 44, 'Tidak dijual', 0, 494, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (304, 45, 'Ayam-ayam', 0, 168, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (305, 45, 'Bandeng', 0, 171, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (306, 45, 'Barabara', 0, 165, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (307, 45, 'Baronang', 0, 160, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (308, 45, 'Bawal', 0, 159, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (309, 45, 'Belanak', 0, 155, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (310, 45, 'Belut', 0, 184, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (311, 45, 'Cucut', 0, 166, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (312, 45, 'Cumi', 0, 156, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (313, 45, 'Gabus', 0, 179, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (314, 45, 'Gurame', 0, 183, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (315, 45, 'Gurita', 0, 157, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (316, 45, 'Hiu', 0, 149, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (317, 45, 'Ikan ekor kuning', 0, 162, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (318, 45, 'Jambal', 0, 152, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (319, 45, 'Kakap', 0, 150, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (320, 45, 'Katak', 0, 188, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (321, 45, 'Kembung', 0, 161, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (322, 45, 'Kepiting', 0, 174, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (323, 45, 'Kerang', 0, 173, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (324, 45, 'Sunuk', 0, 163, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (325, 45, 'Kodok', 0, 187, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (326, 45, 'Kulit kerang', 0, 209, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (327, 45, 'Kuwe', 0, 154, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (328, 45, 'Layur', 0, 167, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (329, 45, 'Lele', 0, 178, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (330, 45, 'Mas', 0, 175, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (331, 45, 'Mujair', 0, 177, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (332, 45, 'Nener', 0, 172, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (333, 45, 'Nila', 0, 181, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (334, 45, 'Pari', 0, 153, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (335, 45, 'Patin', 0, 180, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (336, 45, 'Penyu', 0, 185, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (337, 45, 'Rajungan', 0, 176, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (338, 45, 'Rumput laut', 0, 186, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (339, 45, 'Salmon', 0, 147, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (340, 45, 'Sarden', 0, 158, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (341, 45, 'Sepat', 0, 182, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (342, 45, 'Tembang', 0, 170, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (343, 45, 'Tenggiri', 0, 151, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (344, 45, 'Teri', 0, 254, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (345, 45, 'Teripang', 0, 164, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (346, 45, 'Tongkol/cakalang', 0, 148, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (347, 45, 'Tuna', 0, 251, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (348, 45, 'Udang/lobster', 0, 169, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (349, 47, 'BATANG/TH', 0, 1746, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (350, 47, 'BUAH/TH ', 0, 1013, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (351, 47, 'EKOR/TH ', 0, 1745, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (352, 47, 'JENIS/TH', 0, 965, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (353, 47, 'KG/TH', 0, 960, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (354, 47, 'LITER/TH', 0, 962, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (355, 47, 'M/TH', 0, 963, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (356, 47, 'M3/TH', 0, 961, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (357, 47, 'TON/TH', 0, 966, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (358, 47, 'UNIT/TH', 0, 964, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (359, 49, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (360, 49, 'Dijual ke pasar', 0, 489, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (361, 49, 'Dijual langsung ke konsumen', 0, 488, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (362, 49, 'Dijual melalui KUD', 0, 490, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (363, 49, 'Dijual melalui Pengecer', 0, 492, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (364, 49, 'Dijual melalui Tengkulak', 0, 491, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (365, 49, 'Tidak dijual', 0, 494, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (366, 50, 'Aluminium', 0, 189, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (367, 50, 'Batu apung', 0, 190, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (368, 50, 'Batu cadas', 0, 191, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (369, 50, 'Batu Gamping', 0, 192, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (370, 50, 'Batu Gips', 0, 193, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (371, 50, 'Batu Granit', 0, 194, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (372, 50, 'Batu gunung', 0, 195, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (373, 50, 'Batu kali', 0, 196, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (374, 50, 'Batu kapur', 0, 197, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (375, 50, 'Batu marmer', 0, 198, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (376, 50, 'Batu Putih', 0, 199, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (377, 50, 'Batu Trass', 0, 200, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (378, 50, 'Batubara', 0, 201, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (379, 50, 'Belerang', 0, 202, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (380, 50, 'Biji Besi', 0, 203, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (381, 50, 'Bouxit', 0, 204, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (382, 50, 'Emas', 0, 205, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (383, 50, 'Garam', 0, 206, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (384, 50, 'Gas Alam', 0, 207, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (385, 50, 'Gips', 0, 208, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (386, 50, 'Kuningan', 0, 210, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (387, 50, 'Mangan', 0, 212, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (388, 50, 'Minyak', 0, 233, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (389, 50, 'Minyak Bumi', 0, 213, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (390, 50, 'Nikel', 0, 214, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (391, 50, 'Pasir', 0, 215, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (392, 50, 'Pasir Batu', 0, 216, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (393, 50, 'Pasir Besi', 0, 217, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (394, 50, 'Pasir kwarsa', 0, 218, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (395, 50, 'Perak', 0, 219, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (396, 50, 'Perunggu', 0, 220, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (397, 50, 'Tanah Garam', 0, 221, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (398, 50, 'Tanah liat', 0, 222, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (399, 50, 'Tembaga', 0, 223, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (400, 50, 'Timah', 0, 224, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (401, 50, 'Uranium', 0, 225, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (402, 53, 'BATANG/TH', 0, 1746, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (403, 53, 'BUAH/TH ', 0, 1013, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (404, 53, 'EKOR/TH ', 0, 1745, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (405, 53, 'JENIS/TH', 0, 965, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (406, 53, 'KG/TH', 0, 960, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (407, 53, 'LITER/TH', 0, 962, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (408, 53, 'M/TH', 0, 963, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (409, 53, 'M3/TH', 0, 961, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (410, 53, 'TON/TH', 0, 966, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (411, 53, 'UNIT/TH', 0, 964, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (412, 54, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (413, 54, 'Dijual ke pasar', 0, 489, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (414, 54, 'Dijual langsung ke konsumen', 0, 488, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (415, 54, 'Dijual melalui KUD', 0, 490, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (416, 54, 'Dijual melalui Pengecer', 0, 492, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (417, 54, 'Dijual melalui Tengkulak', 0, 491, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (418, 54, 'Tidak dijual', 0, 494, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (419, 55, 'Air liur burung walet', 0, 232, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (420, 55, 'Bulu', 0, 231, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (421, 55, 'Burung walet', 0, 134, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (422, 55, 'Cinderamata', 0, 235, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (423, 55, 'Daging', 0, 229, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (424, 55, 'Hiasan/lukisan', 0, 234, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (425, 55, 'Kerupuk Kulit', 0, 248, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (426, 55, 'Kulit', 0, 227, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (427, 55, 'Madu', 0, 230, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (428, 55, 'Susu', 0, 226, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (429, 55, 'Telur', 0, 228, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (430, 57, 'BATANG/TH', 0, 1746, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (431, 57, 'BUAH/TH ', 0, 1013, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (432, 57, 'EKOR/TH ', 0, 1745, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (433, 57, 'JENIS/TH', 0, 965, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (434, 57, 'KG/TH', 0, 960, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (435, 57, 'LITER/TH', 0, 962, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (436, 57, 'M/TH', 0, 963, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (437, 57, 'M3/TH', 0, 961, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (438, 57, 'TON/TH', 0, 966, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (439, 57, 'UNIT/TH', 0, 964, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (440, 59, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (441, 59, 'Dijual ke pasar', 0, 489, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (442, 59, 'Dijual langsung ke konsumen', 0, 488, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (443, 59, 'Dijual melalui KUD', 0, 490, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (444, 59, 'Dijual melalui Pengecer', 0, 492, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (445, 59, 'Dijual melalui Tengkulak', 0, 491, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (446, 59, 'Tidak dijual', 0, 494, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (447, 60, 'Jala', 0, 405, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (448, 60, 'Jermal', 0, 402, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (449, 60, 'Karamba', 0, 400, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (450, 60, 'Pancing', 0, 403, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (451, 60, 'Pukat', 0, 404, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (452, 60, 'Tambak', 0, 401, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (453, 62, 'Air minum/air baku', 0, 511, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (454, 62, 'Buang air besar', 0, 514, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (455, 62, 'Cuci dan mandi', 0, 512, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (456, 62, 'Irigasi', 0, 513, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (457, 62, 'Pembangkit listrik', 0, 515, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (458, 62, 'Prasarana transportasi', 0, 516, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (459, 62, 'Sumber air panas', 0, 517, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (460, 62, 'Usaha Perikanan', 0, 510, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (461, 63, 'Biara', 0, 687, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (462, 63, 'Kursus Bahasa', 0, 697, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (463, 63, 'Kursus Bela Diri', 0, 703, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (464, 63, 'Kursus Komputer', 0, 700, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (465, 63, 'Kursus Mengemudi', 0, 701, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (466, 63, 'Kursus Menjahit', 0, 698, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (467, 63, 'Kursus Montir', 0, 699, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (468, 63, 'Kursus Satpam', 0, 702, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (469, 63, 'Lembaga Kursus Keterampilan Swasta Katolik', 0, 692, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (470, 63, 'Lembaga Pendidikan Swasta Budha', 0, 695, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (471, 63, 'Lembaga Pendidikan Swasta Hindu', 0, 694, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (472, 63, 'Lembaga Pendidikan Swasta Konghucu', 0, 696, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (473, 63, 'Lembaga Pendidikan Swasta Kristen Protestan', 0, 693, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (474, 63, 'Madrasah Aliyah', 0, 682, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (475, 63, 'Madrasah Ibtidaiyah', 0, 680, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (476, 63, 'Madrasah Tsanawiyah', 0, 681, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (477, 63, 'Perguruan Tinggi', 0, 676, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (478, 63, 'Perguruan Tinggi Swasta Katolik', 0, 688, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (479, 63, 'Pondok Pesantren', 0, 677, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (480, 63, 'Rhaudatul Athfal (Tk)', 0, 679, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (481, 63, 'SD/Sederajat', 0, 673, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (482, 63, 'Sekolah Dasar Swasta Katolik', 0, 689, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (483, 63, 'Sekolah Tinggi Agama Islam', 0, 683, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (484, 63, 'Seminari Menengah', 0, 685, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (485, 63, 'Seminari Tinggi', 0, 686, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (486, 63, 'SLTA Swasta Katolik', 0, 691, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (487, 63, 'SLTP Swasta Katolik', 0, 690, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (488, 63, 'SMA/Sederajat', 0, 675, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (489, 63, 'SMP/Sederajat', 0, 674, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (490, 63, 'Taman Pendidikan Alqur?an', 0, 678, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (491, 63, 'TK/Preschool/Play Group', 0, 672, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (492, 63, 'Universitas Swasta Islam', 0, 684, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (493, 64, 'Tidak memiliki tanah', 0, 704, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (494, 64, 'Memiliki tanah kurang dari 0,1 ha', 0, 1744, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (495, 64, 'Memiliki tanah antara 0,1 - 0,2 ha', 0, 705, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (496, 64, 'Memiliki tanah antara 0,2 - 0,3 ha', 0, 706, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (497, 64, 'Memiliki tanah antara 0,3 - 0,4 ha', 0, 707, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (498, 64, 'Memiliki tanah antara 0,4 - 0,5 ha', 0, 708, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (499, 64, 'Memiliki tanah antara 0,5 - 0,6 ha', 0, 709, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (500, 64, 'Memiliki tanah antara 0,6 - 0,7 ha', 0, 710, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (501, 64, 'Memiliki tanah antara 0,7 - 0,8 ha', 0, 711, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (502, 64, 'Memiliki tanah antara 0,8 - 0,9 ha', 0, 712, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (503, 64, 'Memiliki tanah antara 0,9 - 1,0 ha', 0, 713, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (504, 64, 'Memiliki tanah antara 1,0 - 5,0 ha', 0, 714, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (505, 64, 'Memiliki tanah lebih dari 5,0 ha', 0, 715, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (506, 65, 'Memiiki cidemo/andong/dokar  ', 0, 718, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (507, 65, 'Memiliki bajaj/kancil', 0, 723, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (508, 65, 'Memiliki becak', 0, 717, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (509, 65, 'Memiliki bus penumpang/angkutan orang/barang', 0, 721, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (510, 65, 'Memiliki ojek motor/sepeda motor/bentor', 0, 716, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (511, 65, 'Memiliki perahu tidak bermotor', 0, 719, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (512, 65, 'Memiliki sepeda dayung', 0, 722, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (513, 65, 'Memiliki tongkang', 0, 720, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (514, 66, 'Memiliki alat pengolahan hasil hutan  ', 0, 731, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (515, 66, 'Memiliki alat pengolahan hasil perikanan  ', 0, 728, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (516, 66, 'Memiliki alat pengolahan hasil perkebunan', 0, 730, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (517, 66, 'Memiliki alat pengolahan hasil peternakan  ', 0, 729, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (518, 66, 'Memiliki alat produksi dan pengolah hasil Industri kerajinan keluarga skala kecil dan menengah  ', 0, 733, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (519, 66, 'Memiliki alat produksi dan pengolah hasil pertambangan  ', 0, 732, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (520, 66, 'Memiliki alat produksi dan pengolahan hasil industri bahan bakar dan gas skala keluarga/rumah tangga  ', 0, 734, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (521, 66, 'Memiliki kapal penangkap ikan  ', 0, 727, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (522, 66, 'Memiliki pabrik pengolahan hasil pertanian  ', 0, 726, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (523, 66, 'Memiliki penggilingan padi  ', 0, 724, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (524, 66, 'Memiliki traktor', 0, 725, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (525, 67, 'Bambu', 0, 737, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (526, 67, 'Dedaunan', 0, 740, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (527, 67, 'Kayu', 0, 736, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (528, 67, 'Pelepah kelapa/lontar/gebang  ', 0, 739, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (529, 67, 'Tanah Liat', 0, 738, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (530, 67, 'Tembok', 0, 735, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (531, 68, 'Kayu', 0, 743, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (532, 68, 'Keramik', 0, 741, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (533, 68, 'Semen', 0, 742, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (534, 68, 'Tanah', 0, 744, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (535, 69, 'Asbes', 0, 747, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (536, 69, 'Bambu', 0, 749, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (537, 69, 'Daun ilalang ', 0, 7752, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (538, 69, 'Daun lontar/gebang/enau  ', 0, 751, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (539, 69, 'Genteng', 0, 745, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (540, 69, 'Kayu', 0, 750, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (541, 69, 'Seng', 0, 746, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (542, 70, 'Berlangganan koran/majalah', 0, 787, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (543, 70, 'Memiliki buku surat berharga', 0, 766, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (544, 70, 'Memiliki buku tabungan bank', 0, 765, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (545, 70, 'Memiliki hiasan emas/berlian', 0, 764, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (546, 70, 'Memiliki HP CDMA', 0, 784, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (547, 70, 'Memiliki HP GSM', 0, 783, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (548, 70, 'Memiliki kapal barang', 0, 757, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (549, 70, 'Memiliki kapal penumpang', 0, 758, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (550, 70, 'Memiliki kapal pesiar', 0, 759, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (551, 70, 'Memiliki mobil pribadi dan sejenisnya', 0, 755, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (552, 70, 'Memiliki parabola', 0, 786, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (553, 70, 'Memiliki perahu bermotor', 0, 756, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (554, 70, 'Memiliki perusahaan industri besar', 0, 770, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (555, 70, 'Memiliki perusahaan industri kecil', 0, 772, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (556, 70, 'Memiliki perusahaan industri menengah', 0, 771, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (557, 70, 'Memiliki saham di perusahaan', 0, 781, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (558, 70, 'Memiliki sepeda motor pribadi', 0, 754, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (559, 70, 'Memiliki sertifikat bangunan', 0, 769, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (560, 70, 'Memiliki sertifikat deposito', 0, 767, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (561, 70, 'Memiliki sertifikat tanah', 0, 768, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (562, 70, 'Memiliki ternak besar', 0, 762, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (563, 70, 'Memiliki ternak kecil', 0, 763, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (564, 70, 'Memiliki TV dan elektronik sejenis lainnya', 0, 753, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (565, 70, 'Memiliki usaha di pasar desa', 0, 779, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (566, 70, 'Memiliki usaha di pasar swalayan', 0, 777, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (567, 70, 'Memiliki usaha di pasar tradisional', 0, 778, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (568, 70, 'Memiliki usaha pasar swalayan', 0, 776, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (569, 70, 'Memiliki usaha perikanan', 0, 773, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (570, 70, 'Memiliki usaha perkebunan', 0, 775, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (571, 70, 'Memiliki usaha peternakan', 0, 774, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (572, 70, 'Memiliki usaha transportasi/pengangkutan', 0, 780, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (573, 70, 'Memiliki Usaha Wartel', 0, 785, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (574, 70, 'Memiliki/menyewa helikopter pribadi', 0, 760, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (575, 70, 'Memiliki/menyewa pesawat terbang pribadi', 0, 761, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (576, 70, 'Pelanggan Telkom', 0, 782, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (577, 71, 'Ibu hamil melahirkan', 0, 796, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (578, 71, 'Ibu hamil periksa di Bidan Praktek', 0, 792, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (579, 71, 'Ibu hamil periksa di Dokter Praktek', 0, 791, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (580, 71, 'Ibu hamil periksa di Dukun Terlatih', 0, 793, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (581, 71, 'Ibu hamil periksa di Posyandu', 0, 788, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (582, 71, 'Ibu hamil periksa di Puskesmas', 0, 789, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (583, 71, 'Ibu hamil periksa di Rumah Sakit', 0, 790, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (584, 71, 'Ibu hamil tidak periksa kesehatan', 0, 794, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (585, 71, 'Ibu hamil yang meninggal', 0, 795, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (586, 71, 'Ibu nifas sakit', 0, 797, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (587, 71, 'Ibu nifas sehat', 0, 799, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (588, 71, 'Kematian ibu nifas', 0, 798, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (589, 71, 'Kematian ibu saat melahirkan', 0, 800, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (590, 72, 'Bayi 0-5 tahun hidup yang menderita kelainan organ tubuh, fisik dan mental  ', 0, 807, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (591, 72, 'Bayi lahir berat kurang dari 2,5 kg', 0, 805, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (592, 72, 'Bayi lahir berat lebih dari 4 kg', 0, 806, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (593, 72, 'Bayi lahir hidup cacat', 0, 803, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (594, 72, 'Bayi lahir hidup normal', 0, 802, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (595, 72, 'Bayi lahir mati', 0, 804, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (596, 72, 'Keguguran kandungan', 0, 801, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (597, 73, 'Rumah dukun', 0, 815, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (598, 73, 'Rumah sendiri', 0, 816, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (599, 73, 'Tempat persalinan Balai Kesehatan Ibu Anak', 0, 812, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (600, 73, 'Tempat persalinan Polindes', 0, 811, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (601, 73, 'Tempat persalinan Puskesmas', 0, 810, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (602, 73, 'Tempat persalinan Rumah Bersalin', 0, 809, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (603, 73, 'Tempat persalinan rumah praktek bidan', 0, 813, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (604, 73, 'Tempat persalinan Rumah Sakit Umum', 0, 808, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (605, 73, 'Tempat praktek dokter', 0, 814, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (606, 74, 'Persalinan ditolong bidan', 0, 818, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (607, 74, 'Persalinan ditolong Dokter', 0, 817, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (608, 74, 'Persalinan ditolong dukun bersalin', 0, 820, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (609, 74, 'Persalinan ditolong keluarga', 0, 821, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (610, 74, 'Persalinan ditolong perawat', 0, 819, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (611, 75, 'BCG', 0, 823, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (612, 75, 'Cacar', 0, 830, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (613, 75, 'Campak', 0, 829, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (614, 75, 'DPT-1', 0, 822, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (615, 75, 'DPT-2', 0, 825, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (616, 75, 'DPT-3', 0, 828, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (617, 75, 'Polio -1', 0, 824, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (618, 75, 'Polio-2', 0, 826, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (619, 75, 'Polio-3', 0, 827, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (620, 75, 'Sudah Semua', 0, 831, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (621, 76, 'Busung Lapar', 0, 838, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (622, 76, 'Cikungunya', 0, 836, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (623, 76, 'Demam Berdarah', 0, 833, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (624, 76, 'Flu Burung', 0, 837, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (625, 76, 'Kelainan fisik', 0, 841, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (626, 76, 'Kelainan mental', 0, 842, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (627, 76, 'Kelaparan', 0, 839, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (628, 76, 'Kolera', 0, 834, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (629, 76, 'Kulit Bersisik', 0, 840, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (630, 76, 'Muntaber', 0, 832, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (631, 76, 'Polio', 0, 835, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (632, 77, 'Biasa buang air besar di sungai/parit/kebun/hutan  ', 0, 845, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (633, 77, 'Memiliki WC yang darurat/kurang memenuhi standar kesehatan  ', 0, 844, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (634, 77, 'Memiliki WC yang permanen/semipermanen  ', 0, 843, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (635, 77, 'Menggunakan fasilitas MCK umum  ', 0, 846, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (636, 78, 'Belum tentu sehari makan 1 kali  ', 0, 851, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (637, 78, 'Kebiasaan makan dalam sehari 1 kali  ', 0, 847, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (638, 78, 'Kebiasaan makan sehari 2 kali  ', 0, 848, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (639, 78, 'Kebiasaan makan sehari 3 kali  ', 0, 849, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (640, 78, 'Kebiasaan makan sehari lebih dari 3 kali  ', 0, 850, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (641, 79, 'Dokter/puskesmas/mantri kesehatan/perawat/ bidan/ posyandu  ', 0, 853, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (642, 79, 'Dukun Terlatih  ', 0, 852, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (643, 79, 'Obat tradisional dari dukun pengobatan alternatif  ', 0, 854, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (644, 79, 'Obat tradisional dari keluarga sendiri  ', 0, 856, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (645, 79, 'Paranormal  ', 0, 855, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (646, 79, 'Tidak diobati  ', 0, 857, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (647, 80, 'Balita bergizi baik  ', 0, 859, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (648, 80, 'Balita bergizi buruk  ', 0, 858, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (649, 80, 'Balita bergizi kurang  ', 0, 860, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (650, 80, 'Balita bergizi lebih', 0, 861, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (651, 81, 'Asma', 0, 874, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (652, 81, 'Diabetes Melitus', 0, 867, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (653, 81, 'Gila/stress', 0, 872, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (654, 81, 'Ginjal', 0, 868, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (655, 81, 'HIV/AIDS', 0, 871, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (656, 81, 'Jantung', 0, 862, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (657, 81, 'Kanker', 0, 865, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (658, 81, 'Lepra/Kusta', 0, 870, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (659, 81, 'Lever', 0, 863, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (660, 81, 'Malaria', 0, 869, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (661, 81, 'Paru-paru', 0, 864, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (662, 81, 'Stroke', 0, 866, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (663, 81, 'TBC', 0, 873, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (664, 82, 'Anak yatim/piatu dalam keluarga akibat konflik Sara  ', 0, 878, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (665, 82, 'Janda/duda dalam keluarga akibat konflik Sara  ', 0, 877, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (666, 82, 'Korban luka dalam keluarga akibat konflik Sara  ', 0, 875, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (667, 82, 'Korban meninggal dalam keluarga akibat konflik Sara ', 0, 876, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (668, 83, 'Korban jiwa akibat perkelahian dalam keluarga  ', 0, 879, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (669, 83, 'Korban luka parah akibat perkelahian dalam keluarga ', 0, 880, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (670, 84, 'Korban pencurian, perampokan dalam keluarga  ', 0, 881, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (671, 85, 'Korban penjarahan yang pelakunya anggota keluarga  ', 0, 882, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (672, 85, 'Korban penjarahan yang pelakunya bukan anggota keluarga  ', 0, 883, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (673, 86, 'Anggota keluarga yang memiliki kebiasaan berjudi', 0, 884, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (674, 87, 'Anggota keluarga mengkonsumsi Miras yang dilarang  ', 0, 885, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (675, 87, 'Anggota keluarga yang mengkonsumsi Narkoba ', 0, 886, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (676, 88, 'Korban pembunuhan dalam keluarga yang pelakunya anggota keluarga  ', 0, 887, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (677, 88, 'Korban pembunuhan dalam keluarga yang pelakunya bukan anggota keluarga', 0, 888, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (678, 89, 'Korban penculikan yang pelakunya anggota keluarga  ', 0, 889, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (679, 89, 'Korban penculikan yang pelakunya bukan anggota keluarga  ', 0, 890, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (680, 90, 'Korban kehamilan di luar nikah yang sah menurut hukum adat  ', 0, 893, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (681, 90, 'Korban kehamilan yang tidak dinikahi pelakunya  ', 0, 894, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (682, 90, 'Korban kehamilan yang tidak/belum disahkan secara hukum agama dan hukum negara  ', 0, 895, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (683, 90, 'Korban perkosaan/pelecehan seksual yang pelakunya anggota keluarga  ', 0, 891, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (684, 90, 'Korban perkosaan/pelecehan seksual yang pelakunya bukan anggota keluarga  ', 0, 892, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (685, 91, 'Adanya pemukulan/tindakan fisik antara anak dengan anggota keluarga lain  ', 0, 903, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (686, 91, 'Adanya pemukulan/tindakan fisik antara anak dengan orang tua  ', 0, 901, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (687, 91, 'Adanya pemukulan/tindakan fisik antara anak dengan pembantu  ', 0, 905, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (688, 91, 'Adanya pemukulan/tindakan fisik antara orang tua dengan anak  ', 0, 902, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (689, 91, 'Adanya pemukulan/tindakan fisik antara orang tua dengan orang tua  ', 0, 904, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (690, 91, 'Adanya pemukulan/tindakan fisik antara orang tua dengan pembantu  ', 0, 906, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (691, 91, 'Adanya pertengkaran dalam keluarga antara anak dan anak  ', 0, 897, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (692, 91, 'Adanya pertengkaran dalam keluarga antara anak dan anggota keluarga lain  ', 0, 900, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (693, 91, 'Adanya pertengkaran dalam keluarga antara anak dan orang tua  ', 0, 896, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (694, 91, 'Adanya pertengkaran dalam keluarga antara anak dan pembantu  ', 0, 899, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (695, 91, 'Adanya pertengkaran dalam keluarga antara ayah dan ibu/orang tua ', 0, 898, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (696, 92, 'Ada anak anggota keluarga yang mengemis', 0, 918, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (697, 92, 'Ada anak dan anggota keluarga yang menjadi pengamen', 0, 919, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (698, 92, 'Ada anak yang membantu orang tua mendapatkan penghasilan', 0, 934, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (699, 92, 'Ada anggota keluarga eks narapidana', 0, 936, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (700, 92, 'Ada anggota keluarga yang bermalam/tidur di jalanan/emperan toko/pusat keramaian/kolong jembatan', 0, 916, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (701, 92, 'Ada anggota keluarga yang cacat fisik', 0, 921, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (702, 92, 'Ada anggota keluarga yang cacat mental', 0, 922, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (703, 92, 'Ada anggota keluarga yang gila/stres', 0, 920, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (704, 92, 'Ada anggota keluarga yang kelainan kulit', 0, 923, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (705, 92, 'Ada anggota keluarga yang menganggur', 0, 933, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (706, 92, 'Ada anggota keluarga yang mengemis', 0, 915, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (707, 92, 'Ada anggota keluarga yang menjadi pengamen', 0, 924, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (708, 92, 'Ada anggota keluarga yang termasuk manusia lanjut usia (di atas 60 thn)', 0, 917, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (709, 92, 'Anggota keluarga yatim/piatu', 0, 925, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (710, 92, 'Keluarga duda', 0, 927, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (711, 92, 'Keluarga janda', 0, 926, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (712, 92, 'Kepala keluarga perempuan', 0, 935, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (713, 92, 'Tinggal di bantaran sungai', 0, 928, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (714, 92, 'Tinggal di daerah kawasan kering, tandus dan kritis', 0, 947, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (715, 92, 'Tinggal di daerah rawan bencana tsunami', 0, 938, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (716, 92, 'Tinggal di desa/kelurahan rawan air bersih', 0, 944, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (717, 92, 'Tinggal di desa/kelurahan rawan banjir', 0, 937, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (718, 92, 'Tinggal di desa/kelurahan rawan bencana kekeringan', 0, 945, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (719, 92, 'Tinggal di desa/kelurahan rawan gagal tanam/panen', 0, 946, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (720, 92, 'Tinggal di desa/kelurahan rawan gunung meletus', 0, 939, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (721, 92, 'Tinggal di desa/kelurahan rawan kelaparan', 0, 943, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (722, 92, 'Tinggal di jalur hijau', 0, 929, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (723, 92, 'Tinggal di jalur rawan gempa bumi', 0, 940, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (724, 92, 'Tinggal di kawasan jalur rel kereta api', 0, 930, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (725, 92, 'Tinggal di kawasan jalur sutet', 0, 931, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (726, 92, 'Tinggal di kawasan kumuh dan padat pemukiman', 0, 932, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (727, 92, 'Tinggal di kawasan rawan kebakaran', 0, 942, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (728, 92, 'Tinggal di kawasan rawan tanah longsor', 0, 941, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (729, 94, 'Kepala Keluarga', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (730, 94, 'Suami', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (731, 94, 'Istri', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (732, 94, 'Anak Kandung', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (733, 94, 'Anak Angkat', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (734, 94, 'Ayah', 0, 6, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (735, 94, 'Ibu', 0, 7, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (736, 94, 'Paman', 0, 8, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (737, 94, 'Tante', 0, 9, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (738, 94, 'Kakak', 0, 10, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (739, 94, 'Adik', 0, 11, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (740, 94, 'Kakek', 0, 12, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (741, 94, 'Nenek', 0, 13, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (742, 94, 'Sepupu', 0, 14, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (743, 94, 'Keponakan', 0, 15, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (744, 94, 'Teman', 0, 16, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (745, 94, 'Mertua', 0, 17, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (746, 94, 'Cucu', 0, 18, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (747, 94, 'Famili lain', 0, 19, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (748, 94, 'Menantu', 0, 21, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (749, 94, 'Lainnya', 0, 22, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (750, 94, 'Anak Tiri', 0, 23, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (751, 95, 'Kawin', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (752, 95, 'Belum Kawin', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (753, 95, 'Janda/Duda', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (754, 96, 'Islam', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (755, 96, 'Kristen Protestan', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (756, 96, 'Kristen Katolik', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (757, 96, 'Hindu', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (758, 96, 'Budha', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (759, 96, 'Konghucu', 0, 6, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (760, 96, 'Aliran Kepercayaan Kepada Tuhan YME', 0, 7, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (761, 97, 'O', 0, 0, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (762, 97, 'A', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (763, 97, 'B', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (764, 97, 'AB', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (765, 97, 'Tidak Tahu', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (766, 98, 'Warga Negara Indonesia', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (767, 98, 'Warga Negara Asing', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (768, 98, 'Dwi Kewarganegaraan', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (769, 100, 'Belum masuk TK/Kelompok Bermain', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (770, 100, 'Sedang TK/Kelompok Bermain', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (771, 100, 'Tidak pernah sekolah', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (772, 100, 'Sedang SD/sederajat', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (773, 100, 'Tamat SD/sederajat', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (774, 100, 'Tidak tamat SD/sederajat', 0, 6, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (775, 100, 'Sedang SLTP/Sederajat', 0, 7, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (776, 100, 'Tamat SLTP/sederajat', 0, 8, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (777, 100, 'Sedang SLTA/sederajat', 0, 9, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (778, 100, 'Tamat SLTA/sederajat', 0, 10, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (779, 100, 'Sedang D-1/sederajat', 0, 11, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (780, 100, 'Tamat D-1/sederajat', 0, 12, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (781, 100, 'Sedang D-2/sederajat', 0, 13, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (782, 100, 'Tamat D-2/sederajat', 0, 14, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (783, 100, 'Sedang D-3/sederajat', 0, 15, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (784, 100, 'Tamat D-4/sederajat', 0, 16, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (785, 100, 'Sedang S-1/sederajat', 0, 17, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (786, 100, 'Tamat S-1/sederajat', 0, 18, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (787, 100, 'Sedang S-2/sederajat', 0, 19, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (788, 100, 'Tamat S-2/sederajat', 0, 20, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (789, 100, 'Sedang S-3/sederajat', 0, 21, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (790, 100, 'Tamat S-3/sederajat', 0, 22, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (791, 100, 'Sedang SLB A/sederajat', 0, 23, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (792, 100, 'Tamat SLB A/sederajat', 0, 24, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (793, 100, 'Sedang SLB B/sederajat', 0, 25, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (794, 100, 'Tamat SLB B/sederajat', 0, 26, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (795, 100, 'Sedang SLB C/sederajat', 0, 27, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (796, 100, 'Tamat SLB C/sederajat', 0, 28, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (797, 100, 'Tidak dapat membaca dan menulis huruf Latin/Arab', 0, 29, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (798, 100, 'Tamat D-3/sederajat', 0, 30, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (799, 101, 'Petani', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (800, 101, 'Buruh Tani', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (801, 101, 'Buruh Migran Perempuan', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (802, 101, 'Buruh Migran laki-laki', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (803, 101, 'Pegawai Negeri Sipil', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (804, 101, 'Karyawan Swasta', 0, 6, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (805, 101, 'Pengrajin', 0, 7, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (806, 101, 'Pedagang barang kelontong', 0, 8, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (807, 101, 'Peternak', 0, 9, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (808, 101, 'Nelayan', 0, 10, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (809, 101, 'Montir', 0, 11, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (810, 101, 'Dokter swasta', 0, 12, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (811, 101, 'Perawat swasta', 0, 13, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (812, 101, 'Bidan swasta', 0, 14, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (813, 101, 'Ahli Pengobatan Alternatif', 0, 15, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (814, 101, 'TNI', 0, 16, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (815, 101, 'POLRI', 0, 17, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (816, 101, 'Pengusaha kecil, menengah dan besar', 0, 18, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (817, 101, 'Guru swasta', 0, 19, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (818, 101, 'Dosen swasta', 0, 20, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (819, 101, 'Seniman/artis', 0, 21, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (820, 101, 'Pedagang Keliling', 0, 22, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (821, 101, 'Penambang', 0, 23, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (822, 101, 'Tukang Kayu', 0, 24, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (823, 101, 'Tukang Batu', 0, 25, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (824, 101, 'Tukang cuci', 0, 26, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (825, 101, 'Pembantu rumah tangga', 0, 27, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (826, 101, 'Pengacara', 0, 28, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (827, 101, 'Notaris', 0, 29, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (828, 101, 'Dukun Tradisional', 0, 30, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (829, 101, 'Arsitektur/Desainer', 0, 31, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (830, 101, 'Karyawan Perusahaan Swasta', 0, 32, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (831, 101, 'Karyawan Perusahaan Pemerintah', 0, 33, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (832, 101, 'Wiraswasta', 0, 34, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (833, 101, 'Konsultan Manajemen dan Teknis', 0, 35, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (834, 101, 'Tidak Mempunyai Pekerjaan Tetap', 0, 36, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (835, 101, 'Belum Bekerja', 0, 37, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (836, 101, 'Pelajar', 0, 38, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (837, 101, 'Ibu Rumah Tangga', 0, 39, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (838, 101, 'Purnawirawan/Pensiunan', 0, 40, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (839, 101, 'Perangkat Desa', 0, 41, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (840, 101, 'Buruh Harian Lepas', 0, 42, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (841, 101, 'Pemilik perusahaan', 0, 55, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (842, 101, 'Pengusaha perdagangan hasil bumi', 0, 56, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (843, 101, 'Buruh jasa perdagangan hasil bumi', 0, 57, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (844, 101, 'Pemilik usaha jasa transportasi dan perhubungan', 0, 58, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (845, 101, 'Buruh usaha jasa transportasi dan perhubungan', 0, 59, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (846, 101, 'Pemilik usaha informasi dan komunikasi', 0, 60, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (847, 101, 'Buruh usaha jasa informasi dan komunikasi', 0, 61, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (848, 101, 'Kontraktor', 0, 62, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (849, 101, 'Pemilik usaha jasa hiburan dan pariwisata', 0, 63, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (850, 101, 'Buruh usaha jasa hiburan dan pariwisata', 0, 64, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (851, 101, 'Pemilik usaha hotel dan penginapan lainnya ', 0, 65, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (852, 101, 'Buruh usaha hotel dan penginapan lainnya', 0, 66, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (853, 101, 'Pemilik usaha warung, rumah makan dan restoran', 0, 67, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (854, 101, 'Dukun/paranormal/supranatural', 0, 68, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (855, 101, 'Jasa pengobatan alternatif', 0, 69, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (856, 101, 'Sopir', 0, 70, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (857, 101, 'Usaha jasa pengerah tenaga kerja', 0, 71, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (858, 101, 'Jasa penyewaan peralatan pesta', 0, 74, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (859, 101, 'Pemulung', 0, 75, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (860, 101, 'Pengrajin industri rumah tangga lainnya', 0, 76, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (861, 101, 'Tukang Anyaman', 0, 77, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (862, 101, 'Tukang Jahit', 0, 78, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (863, 101, 'Tukang Kue', 0, 79, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (864, 101, 'Tukang Rias', 0, 80, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (865, 101, 'Tukang Sumur', 0, 81, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (866, 101, 'Jasa Konsultansi Manajemen dan Teknis ', 0, 82, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (867, 101, 'Juru Masak', 0, 83, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (868, 101, 'Karyawan Honorer', 0, 84, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (869, 101, 'Pialang', 0, 85, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (870, 101, 'Pskiater/Psikolog', 0, 86, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (871, 101, 'Wartawan', 0, 87, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (872, 101, 'Tukang Cukur', 0, 88, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (873, 101, 'Tukang Las', 0, 89, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (874, 101, 'Tukang Gigi', 0, 90, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (875, 101, 'Tukang Listrik', 0, 91, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (876, 101, 'Pemuka Agama', 0, 92, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (877, 101, 'Anggota Legislatif', 0, 93, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (878, 101, 'Kepala Daerah', 0, 94, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (879, 101, 'Apoteker', 0, 96, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (880, 101, 'Presiden', 0, 97, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (881, 101, 'Wakil presiden', 0, 98, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (882, 101, 'Anggota Mahkamah Konstitusi', 0, 99, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (883, 101, 'Anggota Kabinet Kementrian', 0, 100, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (884, 101, 'Duta besar', 0, 101, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (885, 101, 'Gubernur', 0, 102, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (886, 101, 'Wakil bupati', 0, 103, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (887, 101, 'Pilot', 0, 104, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (888, 101, 'Penyiar radio', 0, 105, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (889, 101, 'Pelaut', 0, 106, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (890, 101, 'Peneliti', 0, 107, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (891, 101, 'Satpam/Security', 0, 108, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (892, 101, 'Wakil Gubernur', 0, 109, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (893, 101, 'Bupati/Walikota', 0, 110, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (894, 101, 'Akuntan', 0, 112, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (895, 104, 'Menggunakan alat kontrasepsi Suntik', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (896, 104, 'Menggunakan alat kontrasepsi Spiral', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (897, 104, 'Menggunakan alat kontrasepsi Kondom', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (898, 104, 'Menggunakan alat kontrasepsi vasektomi', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (899, 104, 'Menggunakan alat kontrasepsi Tubektomi', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (900, 104, 'Menggunakan alat kontrasepsi Pil', 0, 6, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (901, 104, 'Menggunakan metode KB Alamiah/Kalender', 0, 7, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (902, 104, 'Menggunakan obat tradisional', 0, 8, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (903, 104, 'Tidak Menggunakan alat kontrasepsi /metode KBA', 0, 9, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (904, 104, 'Susuk KB (Implant)', 0, 10, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (905, 104, 'Tidak Menjawab', 0, 11, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (906, 105, 'Tuna Rungu', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (907, 105, 'Tuna Wicara', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (908, 105, 'Tuna Netra', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (909, 105, 'Lumpuh', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (910, 105, 'Sumbing', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (911, 106, 'Idiot', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (912, 106, 'Gila', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (913, 106, 'Stress', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (914, 107, 'Wajib Pajak Bumi dan Bangunan', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (915, 107, 'Wajib Pajak Penghasilan Perorangan', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (916, 107, 'Wajib Pajak Badan/Perusahaan', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (917, 107, 'Wajib Pajak Kendaraan Bermotor', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (918, 107, 'Wajib Retribusi Kebersihan', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (919, 107, 'Wajib Retribusi Keamanan', 0, 6, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (920, 108, 'Kepala Desa/Lurah', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (921, 108, 'Sekretaris Desa/Kelurahan', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (922, 108, 'Kepala Urusan', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (923, 108, 'Kepala Dusun/Lingkungan', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (924, 108, 'Staf Desa/Kelurahan', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (925, 108, 'Ketua BPD', 0, 6, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (926, 108, 'Wakil Ketua BPD', 0, 7, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (927, 108, 'Sekretaris BPD', 0, 8, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (928, 108, 'Anggota BPD', 0, 9, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (929, 109, 'Pengurus RT', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (930, 109, 'Anggota Pengurus RT', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (931, 109, 'Pengurus RW', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (932, 109, 'Anggota Pengurus RW', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (933, 109, 'Pengurus LKMD/K/LPM', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (934, 109, 'Anggota LKMD/K/LPM', 0, 6, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (935, 109, 'Pengurus PKK', 0, 7, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (936, 109, 'Anggota PKK', 0, 8, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (937, 109, 'Pengurus Lembaga Adat', 0, 9, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (938, 109, 'Pengurus Karang Taruna', 0, 10, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (939, 109, 'Anggota Karang Taruna', 0, 11, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (940, 109, 'Pengurus Hansip/Linmas', 0, 12, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (941, 109, 'Pengurus Poskamling', 0, 13, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (942, 109, 'Pengurus Organisasi Perempuan', 0, 14, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (943, 109, 'Anggota Organisasi Perempuan', 0, 15, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (944, 109, 'Pengurus Organisasi Bapak-bapak', 0, 16, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (945, 109, 'Anggota Organisasi Bapak-bapak', 0, 17, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (946, 109, 'Pengurus Organisasi keagamaan', 0, 18, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (947, 109, 'Anggota Organisasi keagamaan', 0, 19, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (948, 109, 'Pengurus Organisasi profesi wartawan', 0, 20, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (949, 109, 'Anggota Organisasi profesi wartawan', 0, 21, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (950, 109, 'Pengurus Posyandu', 0, 22, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (951, 109, 'Pengurus Posyantekdes', 0, 23, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (952, 109, 'Pengurus Organisasi Kelompok Tani/Nelayan', 0, 24, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (953, 109, 'Anggota Organisasi Kelompok Tani/Nelayan', 0, 25, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (954, 109, 'Pengurus Lembaga Gotong royong', 0, 26, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (955, 109, 'Anggota Lembaga Gotong royong', 0, 27, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (956, 109, 'Pengurus Organisasi Profesi guru', 0, 28, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (957, 109, 'Anggota Organisasi Profesi guru', 0, 29, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (958, 109, 'Pengurus Organisasi profesi dokter/tenaga medis', 0, 30, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (959, 109, 'Anggota Organisasi profesi/tenaga medis', 0, 31, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (960, 109, 'Pengurus organisasi pensiunan', 0, 32, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (961, 109, 'Anggota organisasi pensiunan', 0, 33, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (962, 109, 'Pengurus organisasi pemirsa/pendengar', 0, 34, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (963, 109, 'Anggota organisasi pemirsa/pendengar', 0, 35, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (964, 109, 'Pengurus lembaga pencinta alam', 0, 36, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (965, 109, 'Anggota organisasi pencinta alam', 0, 37, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (966, 109, 'Pengurus organisasi pengembangan ilmu pengetahuan', 0, 38, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (967, 109, 'Anggota organisasi pengembangan ilmu pengetaahuan', 0, 39, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (968, 109, 'Pemilik yayasan', 0, 40, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (969, 109, 'Pengurus yayasan', 0, 41, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (970, 109, 'Anggota yayasan', 0, 42, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (971, 109, 'Pengurus Satgas Kebersihan', 0, 43, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (972, 109, 'Anggota Satgas Kebersihan', 0, 44, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (973, 109, 'Pengurus Satgas Kebakaran', 0, 45, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (974, 109, 'Anggota Satgas Kebakaran', 0, 46, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (975, 109, 'Pengurus Posko Penanggulangan Bencana', 0, 47, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (976, 109, 'Anggota Tim Penanggulangan Bencana', 0, 48, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (977, 110, 'Koperasi', 0, 1, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (978, 110, 'Unit Usaha Simpan Pinjam', 0, 2, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (979, 110, 'Industri Kerajinan Tangan', 0, 3, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (980, 110, 'Industri Pakaian', 0, 4, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (981, 110, 'Industri Usaha Makanan', 0, 5, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (982, 110, 'Industri Alat Rumah Tangga', 0, 6, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (983, 110, 'Industri Usaha Bahan Bangunan', 0, 7, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (984, 110, 'Industri Alat Pertanian', 0, 8, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (985, 110, 'Restoran', 0, 9, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (986, 110, 'Toko/ Swalayan', 0, 10, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (987, 110, 'Warung Kelontongan/Kios', 0, 11, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (988, 110, 'Angkutan Darat', 0, 12, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (989, 110, 'Angkutan Sungai', 0, 13, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (990, 110, 'Angkutan Laut', 0, 14, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (991, 110, 'Angkutan Udara', 0, 15, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (992, 110, 'Jasa Ekspedisi/Pengiriman Barang', 0, 16, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (993, 110, 'Tukang Sumur', 0, 17, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (994, 110, 'Usaha Pasar Harian', 0, 18, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (995, 110, 'Usaha Pasar Mingguan', 0, 19, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (996, 110, 'Usaha Pasar Ternak', 0, 20, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (997, 110, 'Usaha Pasar Hasil Bumi Dan Tambang', 0, 21, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (998, 110, 'Usaha Perdagangan Antar Pulau', 0, 22, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (999, 110, 'Pengijon', 0, 23, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1000, 110, 'Pedagang Pengumpul/Tengkulak', 0, 24, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1001, 110, 'Usaha Peternakan', 0, 25, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1002, 110, 'Usaha Perikanan', 0, 26, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1003, 110, 'Usaha Perkebunan', 0, 27, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1004, 110, 'Kelompok Simpan Pinjam', 0, 28, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1005, 110, 'Usaha Minuman', 0, 29, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1006, 110, 'Industri Farmasi', 0, 30, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1007, 110, 'Industri Karoseri', 0, 31, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1008, 110, 'Penitipan Kendaraan Bermotor', 0, 32, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1009, 110, 'Industri Perakitan Elektronik', 0, 33, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1010, 110, 'Pengolahan Kayu', 0, 34, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1011, 110, 'Bioskop', 0, 35, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1012, 110, 'Film Keliling', 0, 36, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1013, 110, 'Sandiwara/Drama', 0, 37, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1014, 110, 'Group Lawak', 0, 38, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1015, 110, 'Jaipongan', 0, 39, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1016, 110, 'Wayang Orang/Golek', 0, 40, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1017, 110, 'Group Musik/Band', 0, 41, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1018, 110, 'Group Vokal/Paduan Suara', 0, 42, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1019, 110, 'Usaha Persewaan Tenaga Listrik', 0, 43, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1020, 110, 'Usaha Pengecer Gas Dan Bahan Bakar Minyak', 0, 44, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1021, 110, 'Usaha Air Minum Dalam Kemasan', 0, 45, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1022, 110, 'Tukang Kayu', 0, 46, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1023, 110, 'Tukang Batu', 0, 47, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1024, 110, 'Tukang Jahit/Bordir', 0, 48, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1025, 110, 'Tukang Cukur', 0, 49, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1026, 110, 'Tukang Service Elektronik', 0, 50, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1027, 110, 'Tukang Besi', 0, 51, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1028, 110, 'Tukang Pijat/Urut', 0, 52, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1029, 110, 'Tukang Sumur', 0, 53, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1030, 110, 'Notaris', 0, 54, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1031, 110, 'Pengacara/Advokat', 0, 55, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1032, 110, 'Konsultan Manajemen', 0, 56, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1033, 110, 'Konsultan Teknis', 0, 57, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1034, 110, 'Pejabat Pembuat Akta Tanah', 0, 58, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1035, 110, 'Losmen', 0, 59, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1036, 110, 'Wisma', 0, 60, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1037, 110, 'Asrama', 0, 61, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1038, 110, 'Persewaan Kamar', 0, 62, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1039, 110, 'Kontrakan Rumah', 0, 63, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1040, 110, 'Mess', 0, 64, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1041, 110, 'Hotel', 0, 65, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1042, 110, 'Home Stay', 0, 66, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1043, 110, 'Villa', 0, 67, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1044, 110, 'Town House', 0, 68, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1045, 110, 'Usaha Asuransi', 0, 69, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1046, 110, 'Lembaga Keuangan Bukan Bank', 0, 70, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1047, 110, 'Lembaga Perkreditan Rakyat', 0, 71, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1048, 110, 'Pegadaian', 0, 72, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1049, 110, 'Bank Perkreditan Rakyat', 0, 73, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1050, 110, 'Usaha Penyewaan Alat Pesta', 0, 74, 1);
INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES (1051, 110, 'Usaha Pengolahan dan Penjualan Hasil Hutan', 0, 75, 1);


#
# TABLE STRUCTURE FOR: analisis_partisipasi
#

DROP TABLE IF EXISTS `analisis_partisipasi`;

CREATE TABLE `analisis_partisipasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_subjek` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL,
  `id_klassifikasi` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_subjek` (`id_subjek`,`id_master`,`id_periode`,`id_klassifikasi`),
  KEY `id_master` (`id_master`),
  KEY `id_periode` (`id_periode`),
  KEY `id_klassifikasi` (`id_klassifikasi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: analisis_periode
#

DROP TABLE IF EXISTS `analisis_periode`;

CREATE TABLE `analisis_periode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_master` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `id_state` tinyint(4) NOT NULL DEFAULT '1',
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` varchar(100) NOT NULL,
  `tahun_pelaksanaan` year(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_master` (`id_master`),
  KEY `id_state` (`id_state`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `analisis_periode` (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES (1, 2, 'Pendataan 2014', 2, 2, 'ket', '2014');
INSERT INTO `analisis_periode` (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES (2, 2, 'Pendataan 2015', 1, 1, 'nnn', '2015');
INSERT INTO `analisis_periode` (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES (3, 3, 'Data Dasar Keluarga ', 1, 1, 'Pendataan Profil Desa', '2018');
INSERT INTO `analisis_periode` (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES (4, 4, 'Data Anggota Keluarga', 1, 1, 'Pendataan Profil Desa', '2018');


#
# TABLE STRUCTURE FOR: analisis_ref_state
#

DROP TABLE IF EXISTS `analisis_ref_state`;

CREATE TABLE `analisis_ref_state` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `analisis_ref_state` (`id`, `nama`) VALUES (1, 'Belum Entri / Pendataan');
INSERT INTO `analisis_ref_state` (`id`, `nama`) VALUES (2, 'Sedang Dalam Pendataan');
INSERT INTO `analisis_ref_state` (`id`, `nama`) VALUES (3, 'Selesai Entri / Pendataan');


#
# TABLE STRUCTURE FOR: analisis_ref_subjek
#

DROP TABLE IF EXISTS `analisis_ref_subjek`;

CREATE TABLE `analisis_ref_subjek` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `subjek` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `analisis_ref_subjek` (`id`, `subjek`) VALUES (1, 'Penduduk');
INSERT INTO `analisis_ref_subjek` (`id`, `subjek`) VALUES (2, 'Keluarga / KK');
INSERT INTO `analisis_ref_subjek` (`id`, `subjek`) VALUES (3, 'Rumah Tangga');
INSERT INTO `analisis_ref_subjek` (`id`, `subjek`) VALUES (4, 'Kelompok');


#
# TABLE STRUCTURE FOR: analisis_respon
#

DROP TABLE IF EXISTS `analisis_respon`;

CREATE TABLE `analisis_respon` (
  `id_indikator` int(11) NOT NULL,
  `id_parameter` int(11) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL,
  KEY `id_parameter` (`id_parameter`,`id_subjek`),
  KEY `id_periode` (`id_periode`),
  KEY `id_indikator` (`id_indikator`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `analisis_respon` (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES (1, 1, 1, 2);
INSERT INTO `analisis_respon` (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES (2, 6, 1, 2);
INSERT INTO `analisis_respon` (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES (1, 3, 2, 2);
INSERT INTO `analisis_respon` (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES (2, 5, 2, 2);
INSERT INTO `analisis_respon` (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES (1, 2, 3, 2);
INSERT INTO `analisis_respon` (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES (2, 4, 3, 2);
INSERT INTO `analisis_respon` (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES (1, 2, 4, 2);
INSERT INTO `analisis_respon` (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES (2, 4, 4, 2);


#
# TABLE STRUCTURE FOR: analisis_respon_bukti
#

DROP TABLE IF EXISTS `analisis_respon_bukti`;

CREATE TABLE `analisis_respon_bukti` (
  `id_master` tinyint(4) NOT NULL,
  `id_periode` tinyint(4) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `pengesahan` varchar(100) NOT NULL,
  `tgl_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: analisis_respon_hasil
#

DROP TABLE IF EXISTS `analisis_respon_hasil`;

CREATE TABLE `analisis_respon_hasil` (
  `id_master` tinyint(4) NOT NULL,
  `id_periode` tinyint(4) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `akumulasi` double(8,3) NOT NULL,
  `tgl_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id_master` (`id_master`,`id_periode`,`id_subjek`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 1, 129, '25.000', '0000-00-00 00:00:00');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 1, 254, '5.000', '0000-00-00 00:00:00');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 1, 298, '17.000', '0000-00-00 00:00:00');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 1, 304, '17.000', '0000-00-00 00:00:00');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 1, 308, '21.000', '0000-00-00 00:00:00');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 1, 309, '9.000', '0000-00-00 00:00:00');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 2, 1, '25.000', '2018-08-20 05:15:33');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 2, 2, '13.000', '2018-08-20 05:15:50');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 2, 3, '8.000', '2018-08-20 05:16:04');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 2, 4, '8.000', '2018-08-20 05:16:23');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 2, 129, '5.000', '2018-08-20 05:14:24');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 2, 254, '25.000', '2018-08-20 05:14:24');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 2, 298, '24.000', '2018-08-20 05:14:24');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 2, 304, '21.000', '2018-08-20 05:14:24');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 2, 308, '24.000', '2018-08-20 05:14:24');
INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES (2, 2, 309, '21.000', '2018-08-20 05:14:24');


#
# TABLE STRUCTURE FOR: analisis_tipe_indikator
#

DROP TABLE IF EXISTS `analisis_tipe_indikator`;

CREATE TABLE `analisis_tipe_indikator` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `tipe` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `analisis_tipe_indikator` (`id`, `tipe`) VALUES (1, 'Pilihan (Tunggal)');
INSERT INTO `analisis_tipe_indikator` (`id`, `tipe`) VALUES (2, 'Pilihan (Multivalue)');
INSERT INTO `analisis_tipe_indikator` (`id`, `tipe`) VALUES (3, 'Isian Angka');
INSERT INTO `analisis_tipe_indikator` (`id`, `tipe`) VALUES (4, 'Isian Tulisan');


#
# TABLE STRUCTURE FOR: area
#

DROP TABLE IF EXISTS `area`;

CREATE TABLE `area` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `path` text NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  `ref_polygon` int(9) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `id_cluster` int(11) NOT NULL,
  `desk` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `area` (`id`, `nama`, `path`, `enabled`, `ref_polygon`, `foto`, `id_cluster`, `desk`) VALUES (1, 'Area 1', '[[[-8.478525723657054,116.05240345001222],[-8.477591903247376,116.04287624359132],[-8.481412063305804,116.04055881500244],[-8.484553055345845,116.04768276214601]]]', 1, 3, '', 0, 'Area 1');
INSERT INTO `area` (`id`, `nama`, `path`, `enabled`, `ref_polygon`, `foto`, `id_cluster`, `desk`) VALUES (2, 'Area 2', '[[[-8.494865867509324,116.05296134948732],[-8.501487264597221,116.0522747039795],[-8.501147708551757,116.04626655578615],[-8.491130670045568,116.0412883758545]]]', 1, 0, '', 0, 'Area 2');


#
# TABLE STRUCTURE FOR: artikel
#

DROP TABLE IF EXISTS `artikel`;

CREATE TABLE `artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gambar` varchar(200) NOT NULL,
  `isi` text NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT '1',
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_kategori` int(4) NOT NULL,
  `id_user` int(4) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `headline` int(1) NOT NULL DEFAULT '0',
  `gambar1` varchar(200) NOT NULL,
  `gambar2` varchar(200) NOT NULL,
  `gambar3` varchar(200) NOT NULL,
  `dokumen` varchar(400) NOT NULL,
  `link_dokumen` varchar(200) NOT NULL,
  `urut` int(5) DEFAULT NULL,
  `jenis_widget` tinyint(2) NOT NULL DEFAULT '3',
  `boleh_komentar` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (7, '', '<p><strong>Awal mula SID</strong><br /> \"Awalnya ada keinginan dari pemerintah Desa Balerante yang berharap pelayanan pemerintah desa bisa seperti pengunjung rumah sakit yang ingin mencari data pasien rawat inap, tinggal ketik nama di komputer, maka data tersebut akan keluar\"<br /> (Mart Widarto, pengelola Program Lumbung Komunitas)<br /> Program ini mulai dibuat dari awal 2006: <br /> 1. (2006) komunitas melakukan komunikasi dan diskusi lepas tentang sebuah sistem yang bisa digunakan untuk menyimpan data.<br /> 2. (2008) Rangkaian FDG dengan pemerintah desa membahas tentang tata kelola pendokumentasian di desa<br /> 3. (2009) Ujicoba SID yang sudah dikembangkan di balerante<br /> 4. (2009-2010) Membangun SID (aplikasi) dibeberapa desa yang lain: terong (bantul), Nglegi (Gunungkidul) <br /> 5. (2011) Kandangan (Temanggung) Gilangharjo (bantul) Girikarto (gunungkidul) Talun (klaten) Pager Gunung (magelang) <br /> 6. hingga saat ini 2013 sudah banyak desa pengguna SID.<br /> <br /> <strong>SID sebagai tanggapan atas kebutuhan:</strong><br /> Kalau dulu untuk mencari data penduduk menurut kelompok umur saja kesulitan karena tidak mempunyai databasenya. Dengan adanya SID menjadi lebih mudah.<br /> (Nuryanto, Kabag Pelayanan Pemdes Terong)<br /> <br /> Membangun sebuah sistem bukan hanya membuatkan software dan meninggalkan begitu saja, namun ada upaya untuk memadukan sistem dengan kebutuhan yang ada pada desa. sehingga software dapat memenuhi kebutuhan yang telah ada bukan memaksakan desa untuk mengikuti dan berpindah sistem. inilah yang melatari combine melaksanakan alur pengaplikasian software.<br /> 1. Bentuk tim kerja bersama pemerintah desa<br /> 2. Diskusikan basis data apa saja yang diperlukan untuk warga<br /> 3. Himpun data kependudukan warga dari Kartu Keluarga (KK)<br /> 4. Daftarkan proyek SID dan dapatkan aplikasi softwarenya di http://abcd.lumbungkomunitas.net<br /> 5. Install aplikasi software SID di komputer desa<br /> 6. Entry data penduduk ke SID<br /> 7. Basis data kependudukan sudah bisa dimanfaatkan<br /> 8. Diskusikan rencana pengembangan SID sesuai kebutuhan desa<br /> 9. Sebarluaskan informasi desa melalui beragam media untuk warga<br /> <br /> Pemberdayaan data desa yang dibangun diharapkan dapat menjunjung kesejahteraan masyarakat desa, data-data tersebut dapat diperuntukkan untuk riset lebih lanjut tentang kemiskinan, tanggap bencana, sumberdaya desa yang bisa diekspose keluar dan dengan menghubungkan dari desa ke desa dapat mencontohkan banyak hal dalam keberhasilan pemberdayaannya.<br /> (sumber: Buku Sistem Informasi Desa) <br /> <strong><br /></strong></p>', 1, '2013-03-31 20:31:04', 999, 1, 'Awal mula SID', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (32, '', '<div class=\"contentText\">\r\n<div align=\"justify\">Bagian ini berisi informasi dasar mengenai desa kami. Sila klik pada tautan berikut untuk mendapatkan informasi yang lebih rinci.</div>\r\n<div align=\"justify\">&nbsp;</div>\r\n<div align=\"justify\"><ol>\r\n<li>Sejarah Desa</li>\r\n<li>Profil Wilayah Desa</li>\r\n<li>Profil Masyarakat Desa</li>\r\n<li>Profil Potensi Desa</li>\r\n</ol></div>\r\n</div>', 1, '2013-07-29 17:46:44', 999, 1, 'Profil Desa', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (34, '', '<p style=\"text-align: justify;\"><span style=\"color: #ff0000;\"><strong>Contoh (Sila edit halaman ini sesuai dengan deskripsi desa ini)!</strong></span></p>\r\n<p style=\"text-align: justify;\">Berdasarkan data desa pada bulan Februari 2010, jumlah penduduk Desa Terong sebanyak 6484 orang. Jumlah Kepala Keluarga (KK) sebanyak 1605 KK.</p>\r\n<p style=\"text-align: justify;\">Jumlah penduduk Desa Terong usia produktif pada tahun 2009 adalah 4746 orang. Jumlah angkatan kerja tersebut jika dilihat berdasarkan tingkat pendidikannya adalah sebagai berikut:</p>\r\n<table style=\"width: 100%;\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">\r\n<tbody>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\"><strong>No.</strong></p>\r\n</td>\r\n<td style=\"width: 42%;\">\r\n<p style=\"text-align: center;\"><strong>Angkatan Kerja</strong></p>\r\n</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\"><strong>L</strong></p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\"><strong>P</strong></p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\"><strong>Jumlah</strong></p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">1</p>\r\n</td>\r\n<td style=\"width: 42%;\">Tidak Tamat SD</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">59</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">56</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">115</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">2</p>\r\n</td>\r\n<td style=\"width: 42%;\">SD</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">880</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">792</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1672</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">3</p>\r\n</td>\r\n<td style=\"width: 42%;\">SLTP</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">813</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">683</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1496</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">4</p>\r\n</td>\r\n<td style=\"width: 42%;\">SLTA</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">725</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">673</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1398</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">5</p>\r\n</td>\r\n<td style=\"width: 42%;\">Akademi</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">13</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">11</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">24</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">6</p>\r\n</td>\r\n<td style=\"width: 42%;\">Perguruan Tinggi</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">23</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">18</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">41</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 50%;\" colspan=\"2\">\r\n<p style=\"text-align: center;\">Jumlah Total</p>\r\n</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">2513</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">2233</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">4746</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Profil sosial masyarakat</strong></p>\r\n<p style=\"text-align: justify;\">Dalam aktivitas keseharian, masyarakat Desa Terong sangat taat dalam menjalankan ibadah keagamaan. Setiap Rukung Tetangga (RT) dan pedukuhan memiliki kelompok-kelompok pengajian. Pada peringatan hari besar Islam, penduduk Desa Terong kerap menggelar acara peringatan dan karnaval budaya dengan tema yang disesuaikan dengan hari besar keagamaan. Sebagian besar warga Desa Terong terafiliasi pada organisasi kemasyarakat Islam Muhammadiyah.</p>\r\n<p style=\"text-align: justify;\">Gelaran perayaan lain selalu dilakukan dalam rangka memperingati Hari Kemerdekaan Republik Indonesia. Setiap pedukuhan akan turut serta dan semangat menampilkan ciri khasnya dalam acara peringatan dan karnaval budaya.</p>\r\n<p style=\"text-align: justify;\">Kelompok pemuda di Desa Terong yang tergabung dalam kelompok pegiat Karang Taruna menjadi aktor utama dalam banyak kegiatan desa. Kelompok ini aktif menggelar program kegiatan untuk isu demokrasi kepada warga, penguatan ekonomi produktif, pelatihan penanggulangan bencana, dan kampanye Gerakan Remaja Sayang Ibu (GEMAS).</p>\r\n<p style=\"text-align: justify;\">Sejumlah penduduk Desa Terong bekerja merantau di daerah di luar Yogyakarta. Namun, ikatan sosial mereka terhadap tanah kelahiran tetap tinggi. Penduduk asli Desa Terong yang tinggal di Jakarta dan sekitarnya misalnya, mereka membentuk paguyuban untuk memelihara silaturahmi antar sesama warga perantauan. Setiap bulan diadakan kegiatan arisan keliling secara bergilir di setiap tempat anggotanya. Setiap dua tahun sekali diadakan pula kegiatan mudik bersama ke kampung halaman di Desa Terong</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Profil politik masyarakat</strong></p>\r\n<p style=\"text-align: justify;\">Warga Desa Terong dikenal sebagai kelompok masyarakat yang paling aktif dan memiliki potensi tertinggi untuk berpartisipasi dalam pemberian suara untuk Pemilihan Umum dan Pemilihan Kepala Daerah Langsung. Tingkat partisipasi warga di desa ini terbanyak jika dibandingkan dengan desa lain di Kecamatan Dlingo, Bantul.</p>\r\n<p style=\"text-align: justify;\">Warga Desa Terong sangat aktif dalam mengawal proses penyusunan Rancangan Undang-Undang (RUU) Keistimewaan Yogyakarta. Banyak warga Desa Terong yang tergabung dalam Gerakan Rakyat Yogyakarta (GRY) dan aktif dalam beragam kegiatan serta demontrasi mendukung penetapan keistimewaan Yogyakarta. Kepala Desa Terong Sudirman Alfian merupakan Ketua Paguyuban Lurah dan Pamong Desa Ing Sedya Memetri Asrining Yogyakarta (ISMAYA) se Daerah Istimewa Yogyakarta (DIY). Beliau ditunjuk pula sebagai anggota tim perumus RUU Keistimewaan Yogyakarta bersi masyarakat Yogyakarta. Salah satu hal yang diperjuangkan dalam RUU tersebut adalah tidak adanya pelaksanaan pemilihan kepala daerah langsung dalam pemilihan Gubernur DIY; dengan mempertahankan konsep dwi tunggal Sri Sultan Hamengku Buwono dan Paku Alam sebagai Gubernur dan Wakil Bubernur DIY.</p>\r\n<p style=\"text-align: justify;\">Permasalahan mendasar yang ada di Desa Terong adalah tidak imbangnya jumlah pencari kerja dengan jumlah lapangan kerja yang tersedia. Sekalipun jumlah pengangguran di Desa Terong pada Tahun 2009 hanya orang tetapi kebanyakan mereka bekerja di luar Desa. Jadi, perlu gerakan kembali ke Desa serta menarik sumber-sumber ekonomi ke desa agar pencari kerja tidak banyak tersedot ke luar Desa.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Sumber:<br />Laporan Pertanggung Jawaban Lurah Desa Terong, Kecamatan Dlingo, Kabupaten Bantul tahun 2009.</p>', 1, '2013-07-29 18:13:36', 999, 1, 'Profil Masyarakat Desa', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (36, '', '<p>Kontak kami berisi cara menghubungi desa, seperti contoh berikut :</p>\r\n<p>Alamat : Jl desa no 01</p>\r\n<p>No Telepon : 081xxxxxxxxx</p>\r\n<p>Email : xx@desa.com</p>', 1, '2013-07-29 18:28:31', 999, 1, 'Kontak Kami', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (37, '', '<p><span style=\"color: #ff0000;\"><strong>Contoh (Sila edit halaman ini dan sesuaikan dengan deskripsi untuk desa ini)!</strong></span><br /><br />Susunan Organisasi Badan Permusyawaratan Desa:</p>\r\n<p>Ketua</p>\r\n<p>Sekretaris</p>\r\n<p>Anggota</p>', 1, '2013-07-29 18:33:33', 999, 1, 'Badan Permusyawaratan Desa', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (38, '', '<p>Berisi data lembaga yang ada di desa beserta deskripsi dan susunan pengurusnya</p>', 1, '2013-07-29 18:38:33', 999, 1, 'Lembaga Kemasyarakatan', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (41, '', '<p>Agenda Bulan Agustus :</p>\r\n<p>01/08/2013 : Rapat rutin</p>\r\n<p>04/08/2013 : Pertemuan Pengurus</p>\r\n<p>05/08/2013 : Seminar</p>\r\n<p>&nbsp;</p>', 1, '2013-07-30 14:08:52', 1000, 1, 'Agenda', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (42, '', '<p>Daftar Undang Undang Desa</p>\n<p><a href=\"../../../../../../assets/front/dokumen/Profil_SSN_SMP1Kepil.pdf\">1. UU No desa</a></p>\n<p>berisi asf basdaf.</p>\n<p>&nbsp;</p>\n<p><a href=\"kebumenkab.go.id/uu.pdf\">2.UU Perdangangan</a></p>', 1, '2014-04-20 18:21:56', 999, 1, 'Undang Undang', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (43, '', '<p>Isi Peraturan Pemerintah</p>', 1, '2014-04-20 18:24:01', 999, 1, 'Peraturan Pemerintah', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (44, '', '<p>Isi Peraturan Desa</p>', 1, '2014-04-20 18:24:35', 999, 1, 'Peraturan Desa', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (45, '', '<p>Isi Peraturan Kepala Desa</p>', 1, '2014-04-20 18:25:04', 999, 1, 'Peraturan Kepala Desa', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (46, '', '<p>Isi Keputusan kepala desa</p>', 1, '2014-04-20 18:25:36', 999, 1, 'Keputusan Kepala Desa', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (47, '', '<p>Isi Panduan</p>', 1, '2014-04-20 18:38:10', 999, 1, 'Panduan', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (51, '', '<p>Wahai masyarakat yang ber,,,,,,,,,,,,,,,,,,,,</p>\n<p>no hp : 097867575</p>\n<p>email: jkgkgkg</p>\n<p>ato komentar di bawah ini :</p>', 1, '2014-04-22 10:11:20', 999, 1, 'Pengaduan', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (59, '', '<ol>\r\n<li><strong>a.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Jumlah Penduduk</strong></li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<table style=\"width: 372px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah jiwa</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah laki-laki</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah perempuan</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah Kepala Keluarga</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">KK</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li><strong>b.&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Tingkat Pendidikan</strong></li>\r\n</ol>\r\n<table style=\"width: 373px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Belum sekolah</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Usia 7-45 tahun tidak pernah sekolah</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pernah sekolah SD tetapi tidak tamat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pendidikan terakhir</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Tamat SD/sederajat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">SLTP/sederajat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">SLTA/sederajat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">D-1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">D-2</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">D-3</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">S-1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">S-2</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">S-3</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li><strong>c.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Mata Pencaharian</strong></li>\r\n</ol>\r\n<p><strong>&nbsp;</strong></p>\r\n<table style=\"width: 372px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Petani</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">246</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Buruh tani</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">125</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Buruh/swasta</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">136</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pegawai Negeri</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">35</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pengrajin</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">&nbsp;</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pedagang</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">9</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Peternak</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">-</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Nelayan</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">-</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Montir</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">8</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Dokter</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">-</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">POLRI/ABRI</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pensiunan</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">36</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Perangkat Desa</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">15</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pembuat Bata</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">3</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><strong>&nbsp;</strong></p>\r\n<ol>\r\n<li><strong>d.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>AGAMA</strong></li>\r\n</ol>\r\n<table style=\"width: 372px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Islam</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">2215</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Kristen</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">5</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Katholik</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">&nbsp;</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Hindu</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">&nbsp;</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Budha</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><strong>&nbsp;</strong></p>\r\n<p><strong>&nbsp;</strong></p>', 1, '2014-04-30 18:23:24', 999, 1, 'Profil Potensi Desa', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (62, '', '<p>Lembaga Kemasyarakatan Desa (LKMD) adalah salah satu lembaga kemasyaratan yang berada di desa.</p>\n<p>TUGAS LKMD</p>\n<ol>\n<li>menyusun rencana pembangunan secara partisipatif,</li>\n<li>menggerakkan swadaya gotong royong masyarakat,</li>\n<li>melaksanakan dan</li>\n<li>mengendalikan pembangunan.</li>\n</ol>\n<p align=\"left\">FUNGSI LKMD</p>\n<ol>\n<li>penampungan dan penyaluran aspirasi masyarakat dalam pembangunan;</li>\n<li>penanaman dan pemupukan rasa persatuan dan kesatuan masyarakat dalam kerangka memperkokoh Negara Kesatuan Republik Indonesia;</li>\n<li>peningkatan kualitas dan percepatan pelayanan pemerintah kepada masyarakat;</li>\n<li>penyusunan rencana, pelaksanaan, pelestarian dan pengembangan hasil-hasil pembangunan secara partisipatif;</li>\n<li>penumbuhkembangan dan penggerak prakarsa, partisipasi, serta swadaya gotong royong masyarakat; dan</li>\n<li>penggali, pendayagunaan dan pengembangan potensi sumber daya alam serta keserasian lingkungan hidup.</li>\n</ol>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 18:39:07', 999, 1, 'LKMD', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (63, '', '<p>TUGAS PKK</p>\n<ol>\n<li>menyusun rencana kerja PKK Desa/Kelurahan, sesuai dengan basil Rakerda Kabupaten/Kota;</li>\n<li>melaksanakan kegiatan sesuai jadwal yang disepakati;</li>\n<li>menyuluh dan menggerakkan kelompok-kelompok PKK Dusun/Lingkungan, RW, RT dan dasa wisma agar dapat mewujudkan kegiatan-kegiatan yang telah disusun dan disepakati;</li>\n<li>menggali, menggerakan dan mengembangkan potensi masyarakat, khususnya keluarga untuk meningkatkan kesejahteraan keluarga sesuai dengan kebijaksanaan yang telah ditetapkan;</li>\n<li>melaksanakan kegiatan penyuluhan kepada keluarga-keluarga yang mencakup kegiatan bimbingan dan motivasi dalam upaya mencapai keluarga sejahtera;.</li>\n<li>mengadakan pembinaan dan bimbingan mengenai pelaksanaan program kerja;</li>\n<li>berpartisipasi dalam pelaksanaan program instansi yang berkaitan dengan kesejahteraan keluarga di desa/kelurahan;</li>\n<li>membuat laporan basil kegiatan kepada Tim Penggerak PKK Kecamatan dengan tembusan kepada Ketua Dewan Penyantun Tim Penggerak PKK setempat;</li>\n<li>melaksanakan tertib administrasi; dan</li>\n<li>mengadakan konsultasi dengan Ketua Dewan Penyantun Tim Penggerak PKK setempat.</li>\n</ol>\n<p>&nbsp;</p>\n<p>FUNGSI PKK</p>\n<ol>\n<li>penyuluh, motivator dan penggerak masyarakat agar mau dan mampu melaksanakan program PKK; dan</li>\n<li>fasilitator, perencana, pelaksana, pengendali, pembina dan pembimbing Gerakan PKK.</li>\n</ol>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"center\"><strong>Alamatn</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"center\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 2, '2014-04-30 18:41:13', 999, 1, 'PKK', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (64, '', '<p align=\"left\">TUGAS &nbsp;KARANGTARUNA</p>\n<p align=\"left\">menanggulangi berbagai masalah kesejahteraan sosial terutama yang dihadapi generasi muda, baik yang bersifat preventif, rehabilitatif, maupun pengembangan potensi generasi muda di lingkungannya</p>\n<p align=\"left\">FUNGSI KARANGTAURNA</p>\n<ol>\n<li>penyelenggara usaha kesejahteraan sosial;</li>\n<li>penyelenggara pendidikan dan pelatihan bagi masyarakat;</li>\n<li>penyelenggara pemberdayaan masyarakat terutama generasi muda di lingkungannya secara komprehensif, terpadu dan terarah serta berkesinambungan;</li>\n<li>penyelenggara kegiatan pengembangan jiwa kewirausahaan bagi generasi muda di lingkungannya;</li>\n<li>penanaman pengertian, memupuk dan meningkatkan kesadaran tanggung jawab sosial generasi muda;</li>\n<li>penumbuhan dan pengembangan semangat kebersamaan, jiwa kekeluargaan, kesetiakawanan sosial dan memperkuat nilai-nilai kearifan dalam bingkai Negara Kesatuan Republik Indonesia;</li>\n</ol>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 18:44:28', 999, 1, 'Karang Taruna', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (65, '', '<p align=\"left\">TUGAS RT/RW</p>\n<p align=\"left\">membantu Pemerintah Desa dan Lurah dalam penyelenggaraan urusan pemerintahan</p>\n<p align=\"left\">FUNGSI PKK</p>\n<ol>\n<li>pendataan kependudukan dan pelayanan administrasi pemerintahan lainnya;</li>\n<li>pemeliharaan keamanan, ketertiban dan kerukunan hidup antar warga;</li>\n<li>pembuatan gagasan dalam pelaksanaan pembangunan dengan mengembangkan aspirasi dan swadaya murni masyarakat; dan</li>\n<li>penggerak swadaya gotong royong dan partisipasi masyarakat di wilayahnya.</li>\n</ol>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\"><strong>Nama Pejabat</strong></p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">Ketua RW 1</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">Ketua RW 1 Rt 01</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">Ketua RW 1 Rt 02</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 18:45:19', 999, 1, 'RT RW', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (66, '', '<p class=\"Default\">&nbsp;</p>\n<p class=\"Default\">Tim Koordinasi Percepatan Penanggulangan Kemiskinan Desa yang selanjutnya disingkat TKP2KDes adalah wadah koordinasi lintas sektor dan lintas pemangku kepentingan untuk percepatan penanggulangan kemiskinan di desa.</p>\n<p class=\"Default\">TKP2KDes bertugas mengkoordinasikan perencanaan, pengorganisasian, pelaksanaan dan pengendalian program penanggulangan kemiskinan di tingkat Desa.</p>\n<p>( Perda Kabupaten Kebumen Nomor 20 Tahun 2012 Tentang Percepatan Penanggulangan Kemiskinan )</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip; Nomor : &hellip;&hellip;tanggal &hellip;&hellip;.. bulan&hellip;.. tentang &hellip;..</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 2, '2014-04-30 18:46:01', 999, 1, 'TKP2KDes', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (67, '', '<p class=\"Default\">&nbsp;</p>\n<p class=\"Default\">Kelompok Perlindungan Anak Desa atau Kelurahan yang selanjutnya disingkat KPAD atau KPAK adalah lembaga perlindungan anak berbasis masyarakat yang berkedudukan dan melakukan kerja&ndash;kerja perlindungan anak di wilayah desa atau kelurahan tempat anak bertempat tinggal&nbsp;&nbsp;&nbsp;&nbsp; ( Perda Kaupaten Kebumen No 3 Tahun 2013 Tentang Penyelenggaraan Perlindungan Anak&nbsp; )</p>\n<p>TUGAS-TUGAS KPAD</p>\n<p>1.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sosialisasi</p>\n<ol>\n<li>Mensosialisasikan kepada masyarakat tentang hak-hak anak</li>\n<li>Mempromosikan CHILD RIGHTS dan CHILD PROTECTION</li>\n<li>Melakukan upaya pencegahan, respon dan penanganan kasus kasus kekerasan terhadap anak dan masalah anak.</li>\n</ol>\n<p>1.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mediasi</p>\n<ol>\n<li>Mengedepankan upaya musyawarah dan mufakat (Rembug Desa)&nbsp; dalam menyelesaikan masalah &ndash; (Restorative Justive)</li>\n<li>Melakukan koordinasi dengan pihak terkait di level desa, kecamatan dan kabupaten dalam upaya perlindungan anak.</li>\n<li>Melakukan pendampingan kasus (dari pelaporan &ndash; medis &ndash; psikologi - reintegrasi)</li>\n</ol>\n<p>1.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fasilitasi</p>\n<ol>\n<li>Memfasilitasi terbentuknya kelompok anak di desa sebagai media partisipasi anak</li>\n<li>Memfasilitasi partisipasi anak untuk terlibat dalam penyusunan perencanaan pembangunan yang berbasis hak anak (penyusunan RPJMDesa)</li>\n</ol>\n<p>1.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Dokumentasi</p>\n<ol>\n<li>Mendokumentasikan semua proses yang dilakukan (Kegiatan Promosi; Penanganan Kasus dan mencatat kasus yang dilaporkan; Perkembangan Kasus, Pertemuan,dll)</li>\n</ol>\n<p>1.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Advokasi</p>\n<ol>\n<li>Mendorong adanya kebijakan dan penganggaran untuk perlindungan anak di level desa</li>\n<li>Menerima pengaduan kasus dan konsultasi tentang perlindungan anak</li>\n<li>Berhubungan dengan P2TP2A dan LPA untuk pendampingan hukum kasus anak (korban dan atau pelaku)</li>\n</ol>\n<p class=\"Default\">&nbsp;</p>\n<p class=\"Default\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 2, '2014-04-30 18:47:21', 999, 1, 'KPAD', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (68, '', '<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK TERNAK &hellip;..</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang</p>\n<p align=\"center\">&hellip;&hellip;&hellip;&hellip;&hellip;.. &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK WANITA TANI TERNAK&nbsp; &hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align=\"center\">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p>&nbsp;</p>', 2, '2014-04-30 18:47:58', 999, 1, 'Kelompok Ternak', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (69, '', '<p align=\"center\"><strong>DAFTAR NAMA PENGURUS GAPOKTAN</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang</p>\n<p align=\"center\">&hellip;&hellip;&hellip;&hellip;&hellip;.. &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK TANI &hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align=\"center\">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK TANI &hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align=\"center\">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>', 1, '2014-04-30 18:48:39', 999, 1, 'Kelompok Tani', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (70, '', '<p>Linmas</p>', 1, '2014-04-30 18:53:18', 999, 1, 'LinMas', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (71, '', '<p>Kelompok Ekonomi Lainya</p>', 2, '2014-04-30 18:54:20', 999, 1, 'Kelompok Ekonomi Lainya', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (83, '', '<p>Tiap hari rapat sampai kumat</p>', 1, '2014-11-06 18:17:52', 1000, 1, 'Rapat Lagi', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (85, '1471927406download (1).jpg', '<div class=\"contentText\">\r\n<div align=\"justify\">Bagian ini berisi informasi mengenai PemerintahanDesa. Sila klik pada tautan berikut untuk mendapatkan informasi yang lebih rinci.</div>\r\n<div align=\"justify\">&nbsp;</div>\r\n<ol>\r\n<li>Visi dan Misi</li>\r\n<li>Pemerintah Desa</li>\r\n<li>Badan Permusyawaratan Desa</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI dan MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Senggigi Berseri\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>(Bersih, Relegius, Sejahtera, Rapi, dan Indah)</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Terwujudnya masyarakat Desa Senggigi yang Bersih, Relegius, Sejahtra, Rapi dan Indah melalui Akselerasi Pembangunan yang berbasis Keagamaan, Budaya Hukum dan Berwawasan Lingkungan dengan berorentasi pada peningkatan Kinerja Aparatur dan Pemberdayaan Masyarakat\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Misi dan Program Desa Senggigi</strong></p>\r\n<p style=\"text-align: justify;\">Dan untuk melaksanakan visi Desa Senggigi dilaksanakan misi dan program sebagai berikut:</p>\r\n<p style=\"text-align: justify;\">1. Pembangunan Jangka Panjang</p>\r\n<ul>\r\n<li style=\"text-align: justify;\">Melanjutkan Pembangunan Desa yang belum terlaksana</li>\r\n<li style=\"text-align: justify;\">Meningkatkan Kerjasama antara pemerintah Desa dengan Lembaga desa yang ada</li>\r\n<li style=\"text-align: justify;\">Meningkatkan Kesejahtraan Masyarakat desa dengan meningkatkan sarana dan prasarana ekonomi warga.&nbsp;</li>\r\n</ul>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">2. Pembangunan Jangka Pendek &nbsp;</p>\r\n<ul>\r\n<li style=\"text-align: justify;\">Mengembangkan dan Menjaga serta melestarikan ada istiadat desa terutama yang telah mengakar di desa senggigi.&nbsp;</li>\r\n<li style=\"text-align: justify;\">Meningkatkan pelayanan dalam bidang pemerintahan kepada warga masyarakat&nbsp;</li>\r\n<li style=\"text-align: justify;\">Meningkatkan sarana dan prasarana ekonomi warga desa dengan perbaikan prasarana dan sarana ekonomi.</li>\r\n<li style=\"text-align: justify;\">Meningkatkan sarana dan prasarana pendidikan guna peningkatan sumber daya manusia desa senggigi.&nbsp;</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>Kepala Desa Senggigi</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>Muhammad Ilham</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n</div>', 1, '2014-11-07 10:53:54', 999, 1, 'Pemerintahan Desa', 0, '', '', '', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (90, '1471968200IMG-20160823-WA0007.jpg', '', 1, '2016-08-24 00:03:21', 5, 1, 'PERDES PHBS ', 3, '1471968200IMG-20160823-WA0012.jpg', '1471968200', '1471968200', 'PERDES PHBS.docx', 'PERDES PHBS ', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (92, '1472006396', '<p><strong>Susunan Organisasi Pemerintah Desa Senggigi</strong><br /><br />Kepala Desa: MUHAMMAD ILHAM<br />Sekretaris Desa:&nbsp;<span>MUSTAHIQ, S.Adm</span><br />Kepala Urusan Pemerintahan: SYAFRUDIN<br />Kepala Urusan&nbsp;Pembangunan: SYAFI\'I, SE<br />Kepala Urusan&nbsp;Kesra: HAMIDIAH<br />Kepala Urusan&nbsp;Keuangan: MARDIANA<br />Kepala Urusan&nbsp;Trantib: SUPARDI RUSTAM<br />Kepala Urusan&nbsp;Umum: MAHRUP<br /><br /></p>', 1, '2016-08-24 10:39:56', 999, 1, 'Pemerintah Desa', 0, '1472006396', '1472006396', '1472006396', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (93, '1472006908', '<p style=\"text-align: center;\"><strong>VISI dan MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Senggigi Berseri\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>(Bersih, Relegius, Sejahtera, Rapi, dan Indah)</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Terwujudnya masyarakat Desa Senggigi yang Bersih, Relegius, Sejahtera, Rapi dan Indah melalui Akselerasi Pembangunan yang berbasis Keagamaan, Budaya Hukum dan Berwawasan Lingkungan dengan berorentasi pada peningkatan Kinerja Aparatur dan Pemberdayaan Masyarakat\"</strong></p>\r\n<p>&nbsp;&nbsp;</p>\r\n<p><strong>MISI</strong></p>\r\n<p><strong>Misi dan Program Desa Senggigi</strong></p>\r\n<p>Dan untuk melaksanakan visi Desa Senggigi dilaksanakan misi dan program sebagai berikut:</p>\r\n<p>1. Pembangunan Jangka Panjang</p>\r\n<p>&nbsp; &nbsp; - Melanjutkan pembangunan desa yang belum terlaksana.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan kerjasama antara pemerintah desa dengan lembaga desa yang ada.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan kesejahtraan masyarakat desa dengan meningkatkan sarana dan prasarana ekonomi warga.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>2. Pembangunan Jangka Pendek &nbsp;</p>\r\n<p>&nbsp; &nbsp; - Mengembangkan dan Menjaga serta melestarikan ada istiadat desa terutama yang telah mengakar di desa senggigi.&nbsp;</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan pelayanan dalam bidang pemerintahan kepada warga masyarakat&nbsp;</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan sarana dan prasarana ekonomi warga desa dengan perbaikan prasarana dan sarana ekonomi.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan sarana dan prasarana pendidikan guna peningkatan sumber daya manusia Desa Senggigi.&nbsp;</p>', 1, '2016-08-24 10:48:28', 999, 1, 'Visi dan Misi', 0, '1472006908', '1472006908', '1472006908', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (94, '1527544437_gotong-royong-pantai.jpeg', '<p style=\"text-align: justify;\">Gotong royong yang dulu digagas pertama kali oleh pendiri bangsa, Ir. Soekarno kian hari semakin terkikis dengan budaya individual ditengah persaingan yang begitu ketat dalam mencapai tujuan tertentu, kenyataan inilah yang membuat nilai-nilai sosial ditatanan masyarakat yang sejak dulu sudah ditanamkan oleh nenek moyang kita luntur seiring dengan perkembangan jaman. padahal untuk mencapai suatu tujuan dan cita-cita bersama seharusnya dikerjakan secara bersama-sama.&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Maka dengan kenyataan inilah, pemerintah desa senggigi kembali melakukan sebuah inovasi baru dalam merangkul masyarakat agar terus menanamkan kebudayaan \"Gotong Raoyong\". kegitan gotong royong dalam pembangunan jalan desa sedikitnya melibatkan hampir 95% masyarakat senggigi, kebersamaan dan ikatan persaudaraan atas kepentingan bersama menjadi satu ketika semua masyarakat desa terlibat aktif, tanpa harus melihat tatanan dan golongan yang ada. masyarakat saling bahu membahu seiring kegitan gotong royong.&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', 1, '2016-08-24 11:02:44', 1, 1, 'Membangun Desa Lewat Gotong Royong', 3, '1472782825artikel-2-2.jpeg', '1472007764', '1472007764', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (95, '1527540313_kemerdekaan-pantai.jpg', '<p>Desa Senggigi ikut memeriahkan perayaan 17 Agustus 2016 sebagai hari jadi Indonesia yang ke 71 melalui kegiatan Karnaval yang diselenggarakan oleh Camat Batulayar Kabupaten Lombok Barat NTB. Acara karnaval dilaksanakan pada hari Rabu, 17 Agustus 2016 dimulai pukul 15.30 s/d 17.00 wita. Masing-masing desa berkumpul disekitaran kantor Camat Batulayar, dan berjalan menuju Taman Bale Pelangi Desa Sandik sebagai pusat titik kumpul seluruh peserta karnaval.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Dalam karnaval ini, Desa Senggigi melibatkan berbagai unsur masyarakat seperti tokoh masyarakat, perempuan, pemuda dan anak-anak dengan menggunakan baju adat dan berbagai macam asesoris hari kemerdekaan, kegitan tersebut adalah salah satu cara bagaimana memupuk semangat bagi setiap warga negara, khususnya kaum muda sebagai harapan bangsa, yang kian hari semakin terkikis dengan pengaruh global saat ini.</p>\r\n<p>&nbsp;</p>\r\n<p>Lewat karang taruna desa senggigi, pemupukan pemberian semangat dalam berpacu memajukan desa dan bangsa terus dilakukan, berbagai macam kegiatan tahapan dalam pelaksanaan hari kemerdekaan terus di lakukan.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', 1, '2016-08-24 13:05:21', 1, 1, 'Perayaan Hari Kemerdekaan 2016', 3, '1472782634galeri-1-2.jpeg', '1472015120', '1472015120', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (96, '1472782915artikel-3-1.jpeg', '<p>Dalam rapat pembahasan komitmen perekrutan karyawan hotel pada tanggal 24 Agustus 2016 di kantor desa sengigi telah menyepakati beberapa komitmen bersama diantaranya sebagai berikut:</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>1. Dalam perekrutan karyawan, pihak hotel harus memprioritaskan masyarakat senggigi minimal 35%</p>\n<p>2. Pihak Hotel harus mengikuti program perencanaan tenaga kerja desa senggigi sesua dengan VISI dan MISI desa</p>\n<p>3. Pihak hotel harus melakukan kordinasi dengan pemerintah desa ketika perekrutan karyawan&nbsp;</p>\n<p>4. Pihak Hotel harus melakukan pelatihan bagi calon karyawan, khususnya karyawan yang berasal dari desa sengggigi</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>Bagi rekan-rekan pemuda dan masyarakat harap melakukan kordinasi dengan pemerintah desa terkait dengan beberapa hasil pertemuan dalam membangun komitme dengan pihak hotel, jika ada hal mendesak terkait beberapa syarat ketentuan perekrutan, rekan-rekan pemuda dan masyarakat bisa menghubungi kami di kantor desa..</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>', 1, '2016-08-24 13:55:10', 4, 1, 'Rapat membangun Komitmen antara Karang Taruna Desa Senggigi dengan Taruna Hotel', 3, '1472018109IMG-20160824-WA0000.jpg', '1472018109', '1472018109', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (97, '1472019299', '<p>Halaman ini berisi tautan menuju informasi mengenai Basis Data Desa. Ada dua jenis data yang dimuat dalam sistem ini, yakni basis data kependudukan dan basis data sumber daya desa. Sila klik pada tautan berikut untuk mendapatkan tampilan data statistik per kategori.</p>\r\n<ol>\r\n<li>Data Wilayah Administratif&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Pendidikan&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Pekerjaan&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Golongan Darah&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Agama&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Jenis Kelamin&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Kelompok Umur&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Penerima Raskin&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Penerima BPJS &nbsp; &nbsp; &nbsp; &nbsp;</li>\r\n<li>Data Warga Negara &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;</li>\r\n</ol>\r\n<p>Data yang tampil adalah statistik yang didapatkan dari proses olah data dasar yang dilakukan secara&nbsp;<em>offline</em>&nbsp;di kantor desa secara rutin/harian. Data dasar di kantor desa diunggah ke dalam sistem&nbsp;<em>online</em>&nbsp;di website ini secara berkala. Sila hubungi kontak pemerintah desa untuk mendapatkan data dan informasi desa termutakhir.</p>', 1, '2016-08-24 14:14:59', 999, 1, 'Data Desa', 0, '1472019299', '1472019299', '1472019299', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (98, '1472192894', '<p>Wilayah desa berisi tentang penjelasan dan deskripsi letak wilayah desa. contohnya sebagai berikut :<br />Batas-batas :<br />Utara&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kelurahan a<br />Timur &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Desa b<br />Selatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Desa c<br />Barat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kelurahan d dan Desa e<br />Luas Wilayah Desa Penglatan&nbsp;&nbsp; : 186,193 Ha<br />Letak Dan Batas Desa x<br />Desa Penglatan terletak pada posisi 115. 7.20 LS 8. 7.10 BT, dengan ketinggian kurang lebih 250 M diatas permukaan laut.</p>\r\n<p>Peta Desa:</p>\r\n<p><iframe src=\"https://www.google.co.id/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Logandu,+Karanggayam&amp;aq=0&amp;oq=logandu&amp;sll=-2.550221,118.015568&amp;sspn=52.267573,80.332031&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&amp;z=14&amp;ll=-7.55854,109.634173&amp;output=embed\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" width=\"600\" height=\"450\"></iframe></p>', 1, '2016-08-26 14:28:14', 999, 1, 'Wilayah Desa', 0, '1472192894', '1472192894', '1472192894', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (99, '1472228892Raja Lombok 1902.jpg', '<p style=\"text-align: justify;\" align=\"center\">Sejarah telah mencatat bahwa Pulau Lombok pernah menjadi wilayah kekuasaan Kerajaan Karang Asem Bali yang berkedudukan di Cakranegara dengan seorang raja bernama Anak Agung Gde Jelantik. Berakhirnya <strong>kekuasaan</strong> Kerajaan Karang Asem Bali di Pulau Lombok setelah datangnya Belanda pada Tahun 1891, dimana Belanda pada waktu itu ingin menguasai Pulau Lombok dengan dalih pura-pura membantu rakyat Lombok yang dianggap tertindas oleh Pemerintahan Raja Lombok yaitu Anak Agung Gede Jelantik.</p>\r\n<p style=\"text-align: justify;\">Pada masa kekuasaan Raja Lombok yaitu Anak Agung Gde Jelantik, wilayah Desa Senggigi ( Dusun Mangsit, Kerandangan, Senggigi dan Dusun Loco) masih bergabung dengan Desa Senteluk yang sekarang menjadi Desa Meninting . Sedangkan pada tahun 1962 Desa Senteluk pecah menjadi 2 (Dua) desa yaitu Desa Meninting dan Desa Batulayar dan Dusun Mangsit,Kerandangan,Senggigi dan Dusun Loco bergabung ke Desa Batulayar.&nbsp;</p>\r\n<p style=\"text-align: justify;\">Pemberian nama Desa Batulayar pada waktu itu yang lazim disebut dengan Pemusungan/Kepala Dea Batulayar berdasarkan hasil musyawarah nama Batulayar diambil dari nama tempat yang amat terkenal yaitu Makam Batulayar yang sampai saat ini banyak dikunjungi oleh masyarakat Pulau Lombok pada khususnya dan Masyarakat Nusa Tenggara Barat pada umumnya.</p>\r\n<p style=\"text-align: justify;\">Pada tahun 2001 Desa Batulayar dimekarkan menjadi 2 (dua) yaitu Desa Batulayar (sebagai Desa Induk) dan Desa Senggigi (sebagai Desa Persiapan) dengan SK.Bupati No : 30 Tahun 2001 tanggal 17 Mei 2001, yang pada waktu itu yang menjadi pejabat Kepala Desa Senggigi ialah <strong>H. ARIF RAHMAN, S.IP</strong>., dengan jumlah dusun sebanyak 3 dusun, yaitu :</p>\r\n<p>1. Dusun Senggigi</p>\r\n<p>2. Dusun Kerandangan</p>\r\n<p>3. Dusun Mangsit</p>\r\n<p>Selanjutnya pada tanggal 30 Juli 2003 Pejabat Kepala Desa Senggigi dari <strong>H. ARIF RAHMAN, S.IP</strong> diganti oleh Saudara<strong> ARIFIN</strong> dengan SK. Bupati Lombok Barat No : 409/66/pem/2003. Berhubung Desa Senggigi masih bersifat Desa Persiapan, maka berdasarkan hasil musyawarah desa, tertanggal 15 Desember 2003 , maka pada tanggal 22 Desember 2003 Desa Senggigi mengadakan Pemilihan Kepala Desa devinitif yang pertama kali dipimpin oleh&nbsp;<strong>HAJI JUNAIDI</strong>&nbsp;terpilih&nbsp;dengan SK. Bupati Lombok Barat No :01/01/Pem/2004 tertanggal 2 Januari 2004&nbsp;sampai pada tahun 2008.&nbsp;</p>\r\n<p style=\"text-align: justify;\">Selanjutnya pada tahun 2008, Desa Senggigi mengadakan pemilihan Kepala Desa Senggigi yang kedua dan dimenangkan oleh Bapak <strong>H. MUTAKIR AHMAD</strong>&nbsp;dengan&nbsp;SK. Bupati Lombok Barat No :1320/48/Pem./2008 tertanggal 23 Desember 2008, Periode 2008-2014. &nbsp;Kemudian Kepala desa terpilih Periode 2015 s/d 2021&nbsp;adalah <strong>MUHAMMAD ILHAM</strong>&nbsp;dengan SK. Bupati Lombok Barat No : 160/04/BPMPD/15 tanggal 27 Januari 2015 kini baru menjabat 2 (dua) bulan.</p>\r\n<p style=\"text-align: justify;\">Demikian selanyang pandang atau sejarah singkat Desa Senggigi yang dapat kami sampaikan kepada para pegiat Medsos, semoga dapat bermanfaat untuk kita semua, terima kasih.</p>\r\n<p style=\"text-align: justify;\" align=\"center\">&nbsp;</p>\r\n<p style=\"text-align: justify;\" align=\"center\">&nbsp;</p>', 1, '2016-08-26 15:38:09', 999, 1, 'Sejarah Desa', 3, '1472229325490125_20121123041539.jpg', '1472197089', '1472197089', '', '', NULL, 3, 1);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES (100, '1473071921', '<p>Ini contoh teks berjalan. Isi dengan tulisan yang menampilkan suatu ciri atau kegiatan penting di desa anda.</p>', 1, '2016-09-05 10:38:41', 22, 1, 'Contoh teks berjalan', 0, '1473071921', '1473071921', '1473071921', '', '', NULL, 3, 1);


#
# TABLE STRUCTURE FOR: captcha_codes
#

DROP TABLE IF EXISTS `captcha_codes`;

CREATE TABLE `captcha_codes` (
  `id` varchar(40) NOT NULL,
  `namespace` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL,
  `code_display` varchar(32) NOT NULL,
  `created` int(11) NOT NULL,
  `audio_data` mediumblob,
  PRIMARY KEY (`id`,`namespace`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `captcha_codes` (`id`, `namespace`, `code`, `code_display`, `created`, `audio_data`) VALUES ('10.0.2.2', 'default', '2ypo6p', '2yPo6P', 1527544062, NULL);


#
# TABLE STRUCTURE FOR: config
#

DROP TABLE IF EXISTS `config`;

CREATE TABLE `config` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nama_desa` varchar(100) NOT NULL,
  `kode_desa` varchar(100) NOT NULL,
  `nama_kepala_desa` varchar(100) NOT NULL,
  `nip_kepala_desa` varchar(100) NOT NULL,
  `kode_pos` varchar(6) NOT NULL,
  `nama_kecamatan` varchar(100) NOT NULL,
  `kode_kecamatan` varchar(100) NOT NULL,
  `nama_kepala_camat` varchar(100) NOT NULL,
  `nip_kepala_camat` varchar(100) NOT NULL,
  `nama_kabupaten` varchar(100) NOT NULL,
  `kode_kabupaten` varchar(100) NOT NULL,
  `nama_propinsi` varchar(100) NOT NULL,
  `kode_propinsi` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `lng` varchar(20) NOT NULL,
  `zoom` tinyint(4) NOT NULL,
  `map_tipe` varchar(20) NOT NULL,
  `path` text NOT NULL,
  `alamat_kantor` varchar(200) DEFAULT NULL,
  `g_analytic` varchar(200) NOT NULL,
  `email_desa` varchar(50) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `config` (`id`, `nama_desa`, `kode_desa`, `nama_kepala_desa`, `nip_kepala_desa`, `kode_pos`, `nama_kecamatan`, `kode_kecamatan`, `nama_kepala_camat`, `nip_kepala_camat`, `nama_kabupaten`, `kode_kabupaten`, `nama_propinsi`, `kode_propinsi`, `logo`, `lat`, `lng`, `zoom`, `map_tipe`, `path`, `alamat_kantor`, `g_analytic`, `email_desa`, `telepon`, `website`) VALUES (1, 'Senggig1 ', '05', 'Muhammad Ilham ', '--', '83355', 'Batulay4r ', '14', 'Bambang Budi Sanyoto, S. H', '-', 'Lombok Bar4t ', '01', '', '52', 'opensid_logo__sid__bXziTU1.png', '-8.48782268404703', '116.04083776474', 13, 'HYBRID', '[[[-8.470247273601585,116.03699684143068],[-8.471775371367853,116.04249000549318],[-8.474831548688417,116.04557991027833],[-8.47754813036,116.04334831237793],[-8.478736628804842,116.0522747039795],[-8.48688623339785,116.04712486267091],[-8.492319207044495,116.04626655578615],[-8.492319207044495,116.04866981506349],[-8.490281850938663,116.05433464050294],[-8.499110315926593,116.06446266174318],[-8.507429260374638,116.06068611145021],[-8.509466525358253,116.05605125427248],[-8.501656950751967,116.04969978332521],[-8.501656950751967,116.046781539917],[-8.503694246430312,116.04454994201662],[-8.496820982890759,116.0453224182129],[-8.494953428786745,116.03931427001955],[-8.48986005320605,116.0365676879883],[-8.48493639256516,116.03364944458009],[-8.47975533883251,116.03768348693849]]]', 'Jl. Raya Senggigi Km 10 Kerandangan ', 'gsgsdgsdgsg', '', '', '');


#
# TABLE STRUCTURE FOR: data_persil_jenis
#

DROP TABLE IF EXISTS `data_persil_jenis`;

CREATE TABLE `data_persil_jenis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(128) NOT NULL,
  `ndesc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: data_persil_peruntukan
#

DROP TABLE IF EXISTS `data_persil_peruntukan`;

CREATE TABLE `data_persil_peruntukan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(128) NOT NULL,
  `ndesc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: detail_log_penduduk
#

DROP TABLE IF EXISTS `detail_log_penduduk`;

CREATE TABLE `detail_log_penduduk` (
  `id` int(10) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: dokumen
#

DROP TABLE IF EXISTS `dokumen`;

CREATE TABLE `dokumen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `satuan` varchar(200) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT '1',
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_pend` int(11) NOT NULL DEFAULT '0',
  `kategori` tinyint(3) NOT NULL DEFAULT '1',
  `attr` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `dokumen` (`id`, `satuan`, `nama`, `enabled`, `tgl_upload`, `id_pend`, `kategori`, `attr`) VALUES (2, 'SK+TIM+Penyusun+RPJMDes+Tahun+2017_uwdc6N_grafik-statistik-ada-jumlah.png', 'SK TIM Penyusun RPJMDes Tahun 2017', 1, '2018-05-28 06:49:28', 0, 2, '{\"uraian\":\"SK TIM Penyusun RPJMDes Tahun 2017\",\"no_kep_kades\":\"1\",\"tgl_kep_kades\":\"13-01-2017\",\"no_lapor\":\"1\",\"tgl_lapor\":\"13-01-2017\",\"keterangan\":\"Sudah Terbit\"}');
INSERT INTO `dokumen` (`id`, `satuan`, `nama`, `enabled`, `tgl_upload`, `id_pend`, `kategori`, `attr`) VALUES (3, 'SK+Pengangkatan+RT+dan+Pemberentian+RT+Baru_OzjhwE_surat-kk-peraturan.jpg', 'SK Pengangkatan RT dan Pemberentian RT Baru', 1, '2018-05-28 06:51:53', 0, 2, '{\"uraian\":\"SK Pengangkatan RT dan Pemberentian RT Baru\",\"no_kep_kades\":\"2\",\"tgl_kep_kades\":\"14-01-2017\",\"no_lapor\":\"2\",\"tgl_lapor\":\"14-01-2017\",\"keterangan\":\"Sudah Terbit\"}');
INSERT INTO `dokumen` (`id`, `satuan`, `nama`, `enabled`, `tgl_upload`, `id_pend`, `kategori`, `attr`) VALUES (4, 'Perdes+SPJ+Tentang+Keuang+Desa+Tahun+2016_cXJUfP_user-setting-pengaturan.png', 'Perdes SPJ Tentang Keuang Desa Tahun 2016', 1, '2018-05-28 06:57:37', 0, 3, '{\"uraian\":\"Perdes SPJ Tentang Keuang Desa Tahun 2016\",\"jenis_peraturan\":\"Perdes SPJ Tahun 2016\",\"no_ditetapkan\":\"1\",\"tgl_ditetapkan\":\"09-01-2016\",\"tgl_kesepakatan\":\"05-01-2016\",\"no_lapor\":\"1\",\"tgl_lapor\":\"05-01-2016\",\"no_lembaran_desa\":\"1\",\"tgl_lembaran_desa\":\"05-01-2017\",\"no_berita_desa\":\"1\",\"tgl_berita_desa\":\"05-01-2017\",\"keterangan\":\"Sudah Terbit\"}');
INSERT INTO `dokumen` (`id`, `satuan`, `nama`, `enabled`, `tgl_upload`, `id_pend`, `kategori`, `attr`) VALUES (5, 'RPJMDes+Miau+Merah+Tahun+2016+s%2Fd+2022_fMaZGt_cetak-log-penduduk.png', 'RPJMDes Miau Merah Tahun 2016 s/d 2022', 1, '2018-05-28 07:09:56', 0, 3, '{\"uraian\":\"Rencana Pembangunan Jangka Menengah Desa\",\"jenis_peraturan\":\"RPJMDes\",\"no_ditetapkan\":\"2\",\"tgl_ditetapkan\":\"13-01-2017\",\"tgl_kesepakatan\":\"13-01-2017\",\"no_lapor\":\"2\",\"tgl_lapor\":\"13-01-2017\",\"no_lembaran_desa\":\"2\",\"tgl_lembaran_desa\":\"14-01-2017\",\"no_berita_desa\":\"2\",\"tgl_berita_desa\":\"14-01-2017\",\"keterangan\":\"Sudah Terbit\"}');


#
# TABLE STRUCTURE FOR: gambar_gallery
#

DROP TABLE IF EXISTS `gambar_gallery`;

CREATE TABLE `gambar_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parrent` int(4) NOT NULL,
  `gambar` varchar(200) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT '1',
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipe` int(4) NOT NULL,
  `slider` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parrent` (`parrent`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

INSERT INTO `gambar_gallery` (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES (28, 0, 'galeri-1-1.jpg', 'Karnaval Hari Kemerdekaan ', 1, '2016-08-26 14:53:51', 0, NULL);
INSERT INTO `gambar_gallery` (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES (29, 0, '', 'Panorama Wisata ', 1, '2016-08-26 14:55:31', 0, NULL);
INSERT INTO `gambar_gallery` (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES (30, 28, 'IMG-20160823-WA0116.jpg', 'Karnaval baju adat', 1, '2016-08-26 14:57:10', 2, NULL);
INSERT INTO `gambar_gallery` (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES (31, 28, 'galeri-1-2.jpeg', 'Kemeriahan Karnaval', 2, '2016-08-26 14:58:16', 2, NULL);
INSERT INTO `gambar_gallery` (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES (32, 29, 'galeri-2-2.jpeg', 'Pantai indah', 1, '2016-09-02 02:14:06', 2, NULL);
INSERT INTO `gambar_gallery` (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES (33, 29, 'galeri-2-3.jpeg', 'Kolam renang impian', 1, '2016-09-02 02:14:28', 2, NULL);
INSERT INTO `gambar_gallery` (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES (34, 0, '', 'Kegiatan Kantor Desa', 2, '2016-09-02 06:24:59', 0, NULL);
INSERT INTO `gambar_gallery` (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES (35, 28, '', 'Tarian adat', 1, '2016-09-02 07:32:55', 2, NULL);


#
# TABLE STRUCTURE FOR: garis
#

DROP TABLE IF EXISTS `garis`;

CREATE TABLE `garis` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `path` text NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  `ref_line` int(9) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `desk` text NOT NULL,
  `id_cluster` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `garis` (`id`, `nama`, `path`, `enabled`, `ref_line`, `foto`, `desk`, `id_cluster`) VALUES (1, 'Jalan 1', '', 1, 1, '', '', 0);


#
# TABLE STRUCTURE FOR: gis_simbol
#

DROP TABLE IF EXISTS `gis_simbol`;

CREATE TABLE `gis_simbol` (
  `simbol` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `gis_simbol` (`simbol`) VALUES ('accident.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('accident_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('administration.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('administration_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('aestheticscenter.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('agriculture.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('agriculture2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('agriculture3.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('agriculture4.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('aircraft-small.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('airplane-sport.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('airplane-tourism.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('airport-apron.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('airport-runway.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('airport-terminal.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('airport.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('airport_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('amphitheater-tourism.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('amphitheater.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('ancientmonument.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('ancienttemple.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('ancienttempleruin.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('animals.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('animals_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('anniversary.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('apartment.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('apartment_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('aquarium.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('arch.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('archery.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('artgallery.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('atm.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('atv.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('audio.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('australianfootball.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bags.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bank.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bank_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bankeuro.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bankpound.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bar.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bar_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('baseball.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('basketball.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('baskteball2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('beach.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('beach_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('beautiful.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('beautiful_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bench.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('biblio.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bicycleparking.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bigcity.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('billiard.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bobsleigh.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bomb.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bookstore.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bowling.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bowling_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('boxing.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bread.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bread_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bridge.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bridgemodern.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bullfight.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bungalow.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bus.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('bus_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('butcher.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cabin.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cablecar.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('camping.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('camping_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('campingsite.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('canoe.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('car.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('car_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('carrental.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('carrepair.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('carrepair_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('carwash.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('casino.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('casino_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('castle.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cathedral.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cathedral2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cave.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cemetary.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('chapel.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('church.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('church2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('church_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cinema.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cinema_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('circus.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('citysquare.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('climbing.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('clothes-female.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('clothes-male.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('clothes.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('clothes_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('clouds.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('clouds_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cloudsun.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cloudsun_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('club.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('club_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cluster.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cluster2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cluster3.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cluster4.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cluster5.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cocktail.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('coffee.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('coffee_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('communitycentre.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('company.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('company_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('computer.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('computer_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('concessionaire.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('conference.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('construction.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('convenience.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('convent.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('corral.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('country.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('court.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cricket.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cross.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('crossingguard.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cruise.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('currencyexchange.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('customs.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cycling.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cycling_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cyclingfeedarea.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cyclingmountain1.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cyclingmountain2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cyclingmountain3.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cyclingmountain4.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cyclingmountainnotrated.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cyclingsport.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cyclingsprint.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('cyclinguncategorized.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('dam.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('dancinghall.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('dates.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('dates_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('daycare.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-dim.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-dom.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-jeu.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-jue.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-lun.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-mar.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-mer.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-mie.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-qua.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-qui.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-sab.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-sam.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-seg.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-sex.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-ter.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-ven.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('days-vie.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('default.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('dentist.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('deptstore.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('disability.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('disability_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('disabledparking.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('diving.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('doctor.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('doctor_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('dog-leash.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('dog-offleash.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('door.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('down.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('downleft.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('downright.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('downthenleft.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('downthenright.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('drinkingfountain.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('drinkingwater.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('drugs.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('drugs_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('elevator.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('embassy.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('emblem-art.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('emblem-photos.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('entrance.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('escalator-down.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('escalator-up.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('exit.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('expert.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('explosion.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('face-devilish.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('face-embarrassed.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('factory.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('factory_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fallingrocks.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('family.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('farm.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('farm_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fastfood.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fastfood_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('festival-itinerant.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('festival.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('findajob.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('findjob.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('findjob_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fire-extinguisher.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fire.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('firemen.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('firemen_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fireworks.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('firstaid.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fishing.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fishing_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fishingshop.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fitnesscenter.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fjord.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('flood.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('flowers.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('flowers_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('followpath.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('foodtruck.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('forest.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fortress.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fossils.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('fountain.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('friday.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('friday_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('friends.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('friends_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('garden.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('gateswalls.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('gazstation.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('gazstation_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('geyser.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('gifts.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('girlfriend.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('girlfriend_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('glacier.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('golf.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('golf_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('gondola.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('gourmet.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('grocery.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('gun.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('gym.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hairsalon.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('handball.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hanggliding.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hats.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('headstone.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('headstonejewish.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('helicopter.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('highway.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('highway_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hiking-tourism.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hiking.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hiking_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('historicalquarter.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('home.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('home_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('horseriding.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('horseriding_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hospital.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hospital_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hostel.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hotairballoon.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hotel.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hotel1star.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hotel2stars.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hotel3stars.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hotel4stars.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hotel5stars.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hotel_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('house.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('hunting.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('icecream.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('icehockey.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('iceskating.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('im-user.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('index.html');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('info.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('info_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('jewelry.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('jewishquarter.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('jogging.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('judo.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('justice.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('justice_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('karate.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('karting.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('kayak.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('laboratory.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('lake.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('laundromat.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('left.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('leftthendown.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('leftthenup.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('library.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('library_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('lighthouse.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('liquor.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('lock.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('lockerrental.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('magicshow.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('mainroad.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('massage.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('military.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('military_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('mine.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('mobilephonetower.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('modernmonument.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('moderntower.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('monastery.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('monday.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('monday_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('monument.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('mosque.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('motorbike.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('motorcycle.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('movierental.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('museum-archeological.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('museum-art.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('museum-crafts.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('museum-historical.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('museum-naval.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('museum-science.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('museum-war.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('museum.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('museum_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('music-classical.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('music-hiphop.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('music-live.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('music-rock.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('music.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('music_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('nanny.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('newsagent.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('nordicski.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('nursery.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('observatory.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('oilpumpjack.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('olympicsite.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('ophthalmologist.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('pagoda.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('paint.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('palace.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('panoramic.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('panoramic180.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('park-urban.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('park.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('park_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('parkandride.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('parking.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('parking_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('party.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('patisserie.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('pedestriancrossing.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('pend.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('pens.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('perfumery.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('personal.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('personalwatercraft.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('petroglyphs.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('pets.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('phones.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('photo.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('photodown.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('photodownleft.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('photodownright.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('photography.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('photoleft.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('photoright.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('photoup.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('photoupleft.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('photoupright.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('picnic.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('pizza.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('pizza_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('places-unvisited.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('places-visited.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('planecrash.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('playground.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('playground_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('poker.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('poker_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('police.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('police2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('police_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('pool-indoor.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('pool.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('pool_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('port.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('port_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('postal.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('postal_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('powerlinepole.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('powerplant.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('powersubstation.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('prison.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('publicart.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('racing.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('radiation.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('rain_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('rain_3.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('rattlesnake.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('realestate.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('realestate_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('recycle.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('recycle_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('recycle_3.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('regroup.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('regulier.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('resort.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurant-barbecue.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurant-buffet.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurant-fish.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurant-romantic.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurant.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurant_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantafrican.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantchinese.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantchinese_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantfishchips.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantgourmet.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantgreek.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantindian.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantitalian.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantjapanese.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantjapanese_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantkebab.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantkorean.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantmediterranean.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantmexican.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantthai.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('restaurantturkish.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('revolution.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('right.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('rightthendown.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('rightthenup.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('riparian.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('ropescourse.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('rowboat.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('rugby.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('ruins.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sailboat-sport.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sailboat-tourism.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sailboat.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('salle-fete.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('satursday.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('satursday_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sauna.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('school.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('school_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('schrink.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('schrink_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sciencecenter.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('seals.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('seniorsite.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('shadow.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('shelter-picnic.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('shelter-sleeping.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('shoes.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('shoes_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('shoppingmall.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('shore.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('shower.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sight.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('skateboarding.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('skiing.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('skiing_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('skijump.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('skilift.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('smallcity.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('smokingarea.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sneakers.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('snow.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('snowboarding.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('snowmobiling.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('snowshoeing.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('soccer.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('soccer2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('soccer_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('spaceport.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('spectacle.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed100.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed110.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed120.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed130.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed20.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed30.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed40.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed50.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed60.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed70.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed80.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speed90.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('speedhump.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('spelunking.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('stadium.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('statue.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('steamtrain.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('stop.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('stoplight.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('stoplight_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('strike.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('strike1.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('subway.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sun.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sun_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sunday.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('sunday_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('supermarket.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('supermarket_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('surfing.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('suv.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('synagogue.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tailor.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tapas.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('taxi.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('taxi_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('taxiway.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('teahouse.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('telephone.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('templehindu.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tennis.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tennis2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tennis_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tent.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('terrace.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('text.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('textiles.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('theater.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('theater_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('themepark.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('thunder.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('thunder_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('thursday.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('thursday_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('toilets.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('toilets_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tollstation.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tools.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tower.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('toys.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('toys_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('trafficenforcementcamera.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('train.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('train_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tram.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('trash.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('truck.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('truck_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tuesday.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tuesday_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('tunnel.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('turnleft.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('turnright.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('university.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('university_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('unnamed.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('up.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('upleft.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('upright.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('upthenleft.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('upthenright.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('usfootball.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('vespa.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('vet.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('video.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('videogames.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('videogames_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('villa.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('waitingroom.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('water.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('waterfall.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('watermill.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('waterpark.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('waterskiing.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('watertower.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('waterwell.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('waterwellpump.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('wedding.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('wednesday.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('wednesday_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('wetland.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('white1.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('white20.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('wifi.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('wifi_2.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('windmill.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('windsurfing.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('windturbine.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('winery.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('wineyard.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('workoffice.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('world.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('worldheritagesite.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('yoga.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('youthhostel.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('zipline.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('zoo.png');
INSERT INTO `gis_simbol` (`simbol`) VALUES ('zoo_2.png');


#
# TABLE STRUCTURE FOR: inbox
#

DROP TABLE IF EXISTS `inbox`;

CREATE TABLE `inbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ReceivingDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Text` text NOT NULL,
  `SenderNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text NOT NULL,
  `SMSCNumber` varchar(20) NOT NULL DEFAULT '',
  `Class` int(11) NOT NULL DEFAULT '-1',
  `TextDecoded` text NOT NULL,
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RecipientID` text NOT NULL,
  `Processed` enum('false','true') NOT NULL DEFAULT 'false',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: inventaris_asset
#

DROP TABLE IF EXISTS `inventaris_asset`;

CREATE TABLE `inventaris_asset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(255) NOT NULL,
  `kode_barang` varchar(64) NOT NULL,
  `register` varchar(64) NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `judul_buku` varchar(255) DEFAULT NULL,
  `spesifikasi_buku` varchar(255) DEFAULT NULL,
  `asal_daerah` varchar(255) DEFAULT NULL,
  `pencipta` varchar(255) DEFAULT NULL,
  `bahan` varchar(255) DEFAULT NULL,
  `jenis_hewan` varchar(255) DEFAULT NULL,
  `ukuran_hewan` varchar(255) DEFAULT NULL,
  `jenis_tumbuhan` varchar(255) DEFAULT NULL,
  `ukuran_tumbuhan` varchar(255) DEFAULT NULL,
  `jumlah` int(64) NOT NULL,
  `tahun_pengadaan` year(4) NOT NULL,
  `asal` varchar(255) NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: inventaris_gedung
#

DROP TABLE IF EXISTS `inventaris_gedung`;

CREATE TABLE `inventaris_gedung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(255) NOT NULL,
  `kode_barang` varchar(64) NOT NULL,
  `register` varchar(64) NOT NULL,
  `kondisi_bangunan` varchar(255) NOT NULL,
  `kontruksi_bertingkat` varchar(255) NOT NULL,
  `kontruksi_beton` int(1) NOT NULL,
  `luas_bangunan` int(64) NOT NULL,
  `letak` varchar(255) NOT NULL,
  `tanggal_dokument` date DEFAULT NULL,
  `no_dokument` varchar(255) DEFAULT NULL,
  `luas` int(64) DEFAULT NULL,
  `status_tanah` varchar(255) DEFAULT NULL,
  `kode_tanah` varchar(255) DEFAULT NULL,
  `asal` varchar(255) NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: inventaris_jalan
#

DROP TABLE IF EXISTS `inventaris_jalan`;

CREATE TABLE `inventaris_jalan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(255) NOT NULL,
  `kode_barang` varchar(64) NOT NULL,
  `register` varchar(64) NOT NULL,
  `kontruksi` varchar(255) NOT NULL,
  `panjang` int(64) NOT NULL,
  `lebar` int(64) NOT NULL,
  `luas` int(64) NOT NULL,
  `letak` text,
  `tanggal_dokument` date NOT NULL,
  `no_dokument` varchar(255) DEFAULT NULL,
  `status_tanah` varchar(255) DEFAULT NULL,
  `kode_tanah` varchar(255) DEFAULT NULL,
  `kondisi` varchar(255) NOT NULL,
  `asal` varchar(255) NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: inventaris_kontruksi
#

DROP TABLE IF EXISTS `inventaris_kontruksi`;

CREATE TABLE `inventaris_kontruksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(255) NOT NULL,
  `kondisi_bangunan` varchar(255) NOT NULL,
  `kontruksi_bertingkat` varchar(255) NOT NULL,
  `kontruksi_beton` int(1) NOT NULL,
  `luas_bangunan` int(64) NOT NULL,
  `letak` varchar(255) NOT NULL,
  `tanggal_dokument` date DEFAULT NULL,
  `no_dokument` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status_tanah` varchar(255) DEFAULT NULL,
  `kode_tanah` varchar(255) DEFAULT NULL,
  `asal` varchar(255) NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: inventaris_peralatan
#

DROP TABLE IF EXISTS `inventaris_peralatan`;

CREATE TABLE `inventaris_peralatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(255) NOT NULL,
  `kode_barang` varchar(64) NOT NULL,
  `register` varchar(64) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `ukuran` text NOT NULL,
  `bahan` text NOT NULL,
  `tahun_pengadaan` year(4) NOT NULL,
  `no_pabrik` varchar(255) DEFAULT NULL,
  `no_rangka` varchar(255) DEFAULT NULL,
  `no_mesin` varchar(255) DEFAULT NULL,
  `no_polisi` varchar(255) DEFAULT NULL,
  `no_bpkb` varchar(255) DEFAULT NULL,
  `asal` varchar(255) NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: inventaris_tanah
#

DROP TABLE IF EXISTS `inventaris_tanah`;

CREATE TABLE `inventaris_tanah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(255) NOT NULL,
  `kode_barang` varchar(64) NOT NULL,
  `register` varchar(64) NOT NULL,
  `luas` int(64) NOT NULL,
  `tahun_pengadaan` year(4) NOT NULL,
  `letak` varchar(255) NOT NULL,
  `hak` varchar(255) NOT NULL,
  `no_sertifikat` varchar(255) NOT NULL,
  `tanggal_sertifikat` date NOT NULL,
  `penggunaan` varchar(255) NOT NULL,
  `asal` varchar(255) NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: kategori
#

DROP TABLE IF EXISTS `kategori`;

CREATE TABLE `kategori` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(100) NOT NULL,
  `tipe` int(4) NOT NULL DEFAULT '1',
  `urut` tinyint(4) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `parrent` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES (1, 'Berita Desa', 1, 1, 1, 0);
INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES (2, 'Produk Desa', 1, 3, 2, 0);
INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES (4, 'Agenda Desa', 2, 2, 1, 0);
INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES (5, 'Peraturan Desa', 2, 5, 1, 0);
INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES (6, 'Laporan Desa', 2, 6, 2, 0);
INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES (8, 'Panduan Layanan Desa', 2, 3, 2, 0);
INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES (17, 'Peraturan Kebersihan Desa', 1, 0, 2, 5);
INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES (20, 'Berita Lokal', 1, 0, 2, 1);
INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES (21, 'Berita Kriminal', 1, 0, 2, 1);
INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES (22, 'teks_berjalan', 1, 0, 1, 0);


#
# TABLE STRUCTURE FOR: kelompok
#

DROP TABLE IF EXISTS `kelompok`;

CREATE TABLE `kelompok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_master` int(11) NOT NULL,
  `id_ketua` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `kode` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_ketua` (`id_ketua`),
  KEY `id_master` (`id_master`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: kelompok_anggota
#

DROP TABLE IF EXISTS `kelompok_anggota`;

CREATE TABLE `kelompok_anggota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kelompok` int(11) NOT NULL,
  `id_penduduk` int(11) NOT NULL,
  `no_anggota` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_kelompok` (`id_kelompok`,`id_penduduk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: kelompok_master
#

DROP TABLE IF EXISTS `kelompok_master`;

CREATE TABLE `kelompok_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kelompok` varchar(50) NOT NULL,
  `deskripsi` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `kelompok_master` (`id`, `kelompok`, `deskripsi`) VALUES (1, 'Kelompok Ternak', '<p>Kelompok yang memelihara ternak</p>');


#
# TABLE STRUCTURE FOR: klasifikasi_analisis_keluarga
#

DROP TABLE IF EXISTS `klasifikasi_analisis_keluarga`;

CREATE TABLE `klasifikasi_analisis_keluarga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) NOT NULL,
  `jenis` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: klasifikasi_surat
#

DROP TABLE IF EXISTS `klasifikasi_surat`;

CREATE TABLE `klasifikasi_surat` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `uraian` mediumtext NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2335 DEFAULT CHARSET=latin1;

INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1, '000', 'UMUM', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2, '001', 'Lambang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (3, '001.1', 'Garuda', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (4, '001.2', 'Bendera Kebangsaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (5, '001.3', 'Lagu Kebangsaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (6, '001.4', 'Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (7, '001.31', 'Provinsi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (8, '001.32', 'Kabupaten/Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (9, '002', 'Tanda Kehormatan/Penghargaan untuk pegawai ', 'lihat 861.1', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (10, '002.1', 'Bintang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (11, '002.2', 'Satyalencana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (12, '002.3', 'Samkarya Nugraha', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (13, '002.4', 'Monumen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (14, '002.5', 'Penghargaan Secara Adat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (15, '002.6', 'Penghargaan lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (16, '003', 'Hari Raya/Besar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (17, '003.1', 'Nasional 17 Agustus, Hari Pahlawan, dan sebagainya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (18, '003.2', 'Hari Raya Keagamaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (19, '003.3', 'Hari Ulang Tahun', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (20, '003.4', 'Hari-hari Besar Internasional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (21, '004', 'Ucapan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (22, '004.1', 'Ucapan Terima Kasih', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (23, '004.2', 'Ucapan Selamat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (24, '004.3', 'Ucapan Belasungkawa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (25, '004.4', 'Ucapan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (26, '005', 'Undangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (27, '006', 'Tanda Jabatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (28, '006.1', 'Pamong Praja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (29, '006.2', 'Tanda Pengenal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (30, '006.3', 'Pejabat lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (31, '007', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (32, '008', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (33, '009', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (34, '010', 'URUSAN DALAM ', 'Gedung Kantor/Termasuk Instalasi Prasarana Fisik Pamong', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (35, '011', 'Kantor Dinas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (36, '012', 'Rumah Dinas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (37, '012.1', 'Tanah Untuk Rumah Dinas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (38, '012.2', 'Perabot Rumah Dinas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (39, '012.3', 'Rumah Dinas Golongan 1', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (40, '012.4', 'Rumah Dinas Golongan 2', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (41, '012.5', 'Rumah Dinas Golongan 3', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (42, '012.6', 'Rumah/Bangunan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (43, '012.7', 'Rumah Pejabat Negara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (44, '013', 'Mess/Guest House', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (45, '014', 'Rumah Susun/Apartemen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (46, '015', 'Penerangan Listrik/Jasa Listrik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (47, '016', 'Telepon/Faximile/Internet', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (48, '017', 'Keamanan/Ketertiban Kantor', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (49, '018', 'Kebersihan Kantor', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (50, '019', 'Protokol', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (51, '019.1', 'Upacara Bendera', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (52, '019.2', 'Tata Tempat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (53, '019.21', 'Pemasangan Gambar Presiden/Wakil Presiden', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (54, '019.3', 'Audiensi / Menghadap Pimpinan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (55, '019.4', 'Alamat-Alamat Kantor Pejabat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (56, '019.5', 'Bandir/Umbul-Umbul/Spanduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (57, '020', 'PERALATAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (58, '020.1', 'Penawaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (59, '021', 'Alat Tulis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (60, '022', 'Mesin Kantor', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (61, '023', 'Perabot Kantor', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (62, '024', 'Alat Angkutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (63, '025', 'Pakaian Dinas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (64, '026', 'Senjata', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (65, '027', 'Pengadaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (66, '028', 'Inventaris', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (67, '029', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (68, '030', 'KEKAYAAN DAERAH', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (69, '031', 'Sumber Daya Alam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (70, '032', 'Asset Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (71, '033', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (72, '034', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (73, '035', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (74, '036', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (75, '040', 'PERPUSTAKAAN DOKUMENTASI / KEARSIPAN / SANDI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (76, '041', 'Perpustakaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (77, '041.1', 'Umum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (78, '041.2', 'Khusus', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (79, '041.3', 'Perguruan Tinggi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (80, '041.4', 'Sekolah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (81, '041.5', 'Keliling', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (82, '042', 'Dokumentasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (83, '043', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (84, '044', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (85, '045', 'Kearsipan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (86, '045.1', 'Pola Klasifikasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (87, '045.2', 'Penataan Berkas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (88, '045.3', 'Penyusutan Arsip', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (89, '045.31', 'Jadwal Retensi Arsip', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (90, '045.32', 'Pemindahan Arsip', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (91, '045.33', 'Penilaian Arsip', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (92, '045.34', 'Pemusnahan Arsip', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (93, '045.35', 'Penyerahan Arsip', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (94, '045.36', 'Berita Acara Penyusutan Arsip', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (95, '045.37', 'Daftar Pencarian Arsip', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (96, '045.4', 'Pembinaan Kearsipan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (97, '045.41', 'Bimbingan Teknis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (98, '045.5', 'Pemeliharaan /Perawatan Arsip', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (99, '045.6', 'Pengawetan/Fumigasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (100, '046', 'Sandi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (101, '047', 'Website', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (102, '048', 'Pengelolaan Data', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (103, '049', 'Jaringan Komunikasi Data', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (104, '050', 'PERENCANAAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (105, '050.1', 'Repelita/8 Sukses', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (106, '050.11', 'Pelita Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (107, '050.12', 'Bantuan Pembangunan Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (108, '050.13', 'Bappeda', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (109, '051', 'Proyek Bidang Pemerintahan, ', 'Klasifikasikan Disini : Proyek Prasaran Fisik Pemerintahan, Tambahkan Perincian 100 Pada 051 Contoh: Proyek Kepenjaraan 051.86', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (110, '052', 'Bidang Politik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (111, '053', 'Bidang Keamanan Dan Ketertiban', 'Tambahkan Perincian 300 Pada 053 Contoh: Proyek Ketataprajaan 053.311 ', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (112, '054', 'Bidang Kesejahteraan Rakyat ', 'Tambahkan Peincian 400 pada 054 Contoh: Proyek Resettlement Desa 054.671', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (113, '055', 'Bidang Perekonomian ', 'Tambahkan Perincian 500 Pada 055 Contoh: Proyek Pasar 055.112', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (114, '056', 'Bidang Pekerjaan Umum ', 'Tambahkan Perincian 600 pada 056 Contoh: Proyek Jembatan 056.3', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (115, '057', 'Bidang Pengawasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (116, '058', 'Bidang Kepegawaian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (117, '059', 'Bidang Keuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (118, '060', 'ORGANISASI / KETATALAKSANAAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (119, '060.1', 'Program Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (120, '061', 'Organisasi Instansi Pemerintah (struktur organisasi)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (121, '061.1', 'Susunan dan Tata Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (122, '061.2', 'Tata Tertib Kantor, Jam Kerja di Bulan Puasa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (123, '062', 'Organisasi Badan Non Pemerintah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (124, '063', 'Organisasi Badan Internasional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (125, '064', 'Organisasi Semi Pemerintah, BKS-AKSI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (126, '065', 'Ketatalaksanaan / Tata Naskah / Sistem', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (127, '066', 'Stempel Dinas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (128, '067', 'Pelayanan Umum / Pelayanan Publik / Analisis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (129, '068', 'Komputerisasi / Siskomdagri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (130, '069', 'Standar Pelayanan Minimal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (131, '070', 'PENELITIAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (132, '071', 'Riset', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (133, '072', 'Survey', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (134, '073', 'Kajian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (135, '074', 'Kerjasama Penelitian Dengan Perguruan Tinggi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (136, '075', 'Kementerian Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (137, '076', 'Non Kementerian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (138, '077', 'Provinsi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (139, '078', 'Kabupaten/Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (140, '079', 'Kecamatan /Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (141, '080', 'KONFERENSI / RAPAT / SEMINAR', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (142, '081', 'Gubernur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (143, '082', 'Bupati / Walikota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (144, '083', 'Komponen, Eselon Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (145, '084', 'Instansi Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (146, '085', 'Internasional Di Dalam Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (147, '086', 'Internasional Di Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (148, '087', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (149, '088', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (150, '089', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (151, '090', 'PERJALANAN DINAS', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (152, '091', 'Perjalanan Presiden/Wakil Presiden Ke Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (153, '092', 'Perjalanan Menteri Ke Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (154, '093', 'Perjalanan Pejabat Tinggi (Pejabat Eselon 1)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (155, '094', 'Perjalanan Pegawai Termasuk Pemanggilan Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (156, '095', 'Perjalanan Tamu Asing Ke Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (157, '096', 'Perjalanan Presiden/Wakil Presiden Ke Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (158, '097', 'Perjalanan Menteri Ke Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (159, '098', 'Perjalanan Pejabat Tinggi Ke Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (160, '099', 'Perjalanan Pegawai ke Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (161, '100', 'PEMERINTAHAN', 'Meliputi: Tata Praja, Legislatif, Yudikatif, Hubungan luar', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (162, '101', 'negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (163, '102', 'GDN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (164, '103', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (165, '104', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (166, '105', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (167, '110', 'PEMERINTAHAN PUSAT', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (168, '111', 'Presiden', 'Meliputi: pencalonan, pengangkatan, pelantikan, sumpah, dan serah jabatan', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (169, '111.1', 'Pertanggung jawaban presiden kpd MPR', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (170, '111.2', 'Amanat Presiden/Amanat Kenegaraan/Pidato', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (171, '112', 'Wakil Presiden', 'Meliputi: pencalonan, pengangkatan, pelantikan, sumpah, dan serah jabatan', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (172, '112.1', 'Pertanggung jawaban wakil presiden kepada MPR', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (173, '112.2', 'Amanat Wakil Presiden/Amanat Kenegaraan/Pidato', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (174, '113', 'Susunan Kabinet', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (175, '113.1', 'Reshuffle', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (176, '113.2', 'Penunjukan Menteri ad interim', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (177, '113.3', 'Sidang Kabinet', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (178, '114', 'Kementerian Dalam Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (179, '114.1', 'Amanat Menteri Dalam Negeri/Sambutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (180, '115', 'Kementerian lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (181, '116', 'Lembaga Tinggi Negara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (182, '117', 'Lembaga Non Kementerian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (183, '118', 'Otonomi/Desentralisasi/Dekonsentrasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (184, '119', 'Kerjasama Antar Kementerian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (185, '120', 'PEMERINTAH PROVINSI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (186, '120.04', 'Laporan daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (187, '120.42', 'Monografi tambahkan kode wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (188, '120.1', 'Koordinasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (189, '120.2', 'Instansi Tingkat Provinsi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (190, '120.21', 'Dinas Otonomi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (191, '120.22', 'Instansi Vertikal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (192, '120.23', 'Kerjasama antar Provinsi/Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (193, '121', 'Gubernur tambahkan kode wilayah, ', 'Meliputi: Pencalonan, Pengangkatan, Meninggal, Pelantikan, Pemberhentian, Serah Terima Jabatan dan sebagainya.', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (194, '122', 'Wakil Gubernur tambahkan kode wilayah, ', 'Meliputi: Pencalonan, Pengangkatan, Meninggal, Pelantikan, Pemberhentian, Serah Terima Jabatan  dan sebagainya.', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (195, '123', 'Sekretaris Wilayah tambahkan kode wilayah, ', 'Meliputi: Pencalonan, Pengangkatan, Meninggal, Pelantikan, Pemberhentian, Serah Terima Jabatan dan sebagainya.', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (196, '124', 'Pembentukan/Pemekaran Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (197, '124.1', 'Pembinaan/Perubahan Nama kepada: Daerah, Kota,Benda, Geografis, Gunung, Sungai, Pulau, Selat, Batas laut, dan sebagainya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (198, '124.2', 'Pemekaran  Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (199, '124.3', 'Forum Koordinasi lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (200, '125', 'Pembentukan Pemekaran Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (201, '125.1', 'Pembinaan/Perubahan Nama Kepada: Daerah, Kota, Benda, Geografis, Gunung, Sungai, Pulau, Selat, Batas Laut, dan sebagainya.', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (202, '125.2', 'Pembentukan Wialayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (203, '125.3', 'Pemindahan Ibukota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (204, '125.4', 'Perubahan batas Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (205, '125.5', 'Pemekaran Wialayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (206, '126', 'Pembagian Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (207, '127', 'Penyerahan Urusan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (208, '128', 'Swaparaja/Penataan Wilayah/Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (209, '129', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (210, '130', 'PEMERINTAH KABUPATEN / KOTA', 'Bupati /Walikota, Tambahkan Kode Wilayah, Meliputi: Pencalonan,Pengangkatan, Meninggal, Pelantikan,', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (211, '131', 'Pemberhentian, Serah Terima Jabatan, dsb', 'Sambutan / Pengarahan / Amanat Wakil Bupati /Walikota, Tambahkan Kode Wilayah, Meliputi: Pencalonan, Pengangkatan, Meninggal, Pelantikan,', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (212, '132', 'Pemberhentian, Serah Terima Jabatan, Sekretaris Daerah Kabupaten/Kota, Tambahkan Kode Wilayah, ', 'Meliputi: Pencalonan, Pengangkatan, Meninggal,', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (213, '133', 'Pelantikan, Pemberhentian, Serah Terima Jabatan,.', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (214, '134', 'Forum Koordinasi Pemerintah Di Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (215, '134.1', 'Muspida', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (216, '134.2', 'Forum PAN (Panitian Anggaran Nasional)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (217, '134.3', 'Forum Koordinasi Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (218, '134.4', 'Kerjasama antar Kabupaten/Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (219, '135', 'Pembentukan / Pemekaran Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (220, '135.1', 'Pemindahan Ibukota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (221, '135.2', 'Pembentukan Wilayah Pembantu Bupati/Walikota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (222, '135.3', 'Pemabagian Wilayah Kabupaten/Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (223, '135.4', 'Perubahan Batas Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (224, '135.5', 'Pemekaran Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (225, '135.6', 'Permasalahan Batas Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (226, '135.7', 'Pembentukan Ibukota Kabupaten/Kota Pemberian dan Penggantian Nama Kabupaten/Kota, Daerah,', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (227, '135.8', 'Jalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (228, '136', 'Pembagian Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (229, '137', 'Penyerahan Urusan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (230, '138', 'Pemerintah Wilayah Kecamatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (231, '138.1', 'Sambutan / Pengarahan / Amanat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (232, '138.2', 'Pembentukan Kecamatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (233, '138.3', 'Pemekaran Kecamatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (234, '138.4', 'Perluasan/Perubahan Batas Wilayah Kecamatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (235, '138.5', 'Pembentukan Perwakilan Kecamatan/Kemantren', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (236, '138.6', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (237, '138.7', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (238, '139', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (239, '140', 'PEMERINTAHAN DESA / KELURAHAN', 'Pamong Desa, Meliputi: Pencalonan, Pemilihan, Meninggal,', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (240, '141', 'Pengangkatan, Pemberhenian, dan sebagainya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (241, '142', 'Penghasilan Pamong Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (242, '143', 'Kekayaan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (243, '144', 'Dewan Tingkat Desa, Dewan Marga, Rembug Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (244, '145', 'Administrasi Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (245, '146', 'Kewilayahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (246, '146.1', 'Pembentukan Desa/Kelurahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (247, '146.2', 'Pemekaran Desa/Kelurahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (248, '146.3', 'Perubahan Batas Wilayah / Perluasan Desa / Kelurahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (249, '146.4', 'Perubahan Nama Desa / Kelurahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (250, '146.5', 'Kerjasama Antar Desa / Kelurahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (251, '147', 'Lembaga-lembaga Tingkat Desa', 'Jangan Klasifikasikan Disini, Lihat 410 Dengan Perinciannya', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (252, '148', 'Perangkat Kelurahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (253, '148.1', 'Kepala Kelurahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (254, '148.2', 'Sekretaris Kelurahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (255, '148.3', 'Staf Kelurahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (256, '149.1', 'Dewan Kelurahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (257, '149.2', 'Rukun Tetangga', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (258, '149.3', 'Rukun Warga', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (259, '149.4', 'Rukun Kampung', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (260, '150', 'LEGISLATIF MPR / DPR / DPD', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (261, '151', 'Keanggotaan MPR', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (262, '151.1', 'Pencalonan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (263, '151.2', 'Pemberhentian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (264, '151.3', 'Recall', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (265, '151.4', 'Pelanggaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (266, '152', 'Persidangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (267, '153', 'Kesejahteraan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (268, '153.1', 'Keuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (269, '153.2', 'Penghargaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (270, '154', 'Hak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (271, '155', 'Keanggotaan DPR ', 'Pencalonan Pengangkatan Persidangan Sidang Pleno Dengar Pendapat/Rapat Komisi', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (272, '156', 'Reses', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (273, '157', 'Kesejahteraan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (274, '157.1', 'Keuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (275, '157.2', 'Penghargaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (276, '158', 'Jawaban Pemerintah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (277, '159', 'Hak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (278, '160', 'DPRD PROVINSI TAMBAHKAN KODE WILAYAH', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (279, '161', 'Keanggotaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (280, '161.1', 'Pencalonan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (281, '161.2', 'Pengangkatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (282, '161.3', 'Pemberhentian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (283, '161.4', 'Recall', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (284, '161.5', 'Meninggal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (285, '161.6', 'Pelanggaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (286, '162', 'Persidangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (287, '162.1', 'Reses', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (288, '163', 'Kesejahteraan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (289, '163.1', 'Keuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (290, '163.2', 'Penghargaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (291, '164', 'Hak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (292, '165', 'Sekretaris DPRD Provinsi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (293, '166', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (294, '167', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (295, '168', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (296, '169', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (297, '170', 'DPRD KABUPATEN TAMBAHKAN KODE WILAYAH', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (298, '171', 'Keanggotaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (299, '171.1', 'Pencalonan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (300, '171.2', 'Pengangkatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (301, '171.3', 'Pemberhentian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (302, '171.4', 'Recall', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (303, '171.5', 'Pelanggaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (304, '172', 'Persidangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (305, '173', 'Kesejahteraan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (306, '173.1', 'Keuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (307, '173.2', 'Penghargaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (308, '174', 'Hak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (309, '175', 'Sekretaris DPRD Kabupaten/Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (310, '176', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (311, '177', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (312, '178', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (313, '180', 'HUKUM', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (314, '180.1', 'Kontitusi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (315, '180.11', 'Dasar Hukum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (316, '180.12', 'Undang-Undang Dasar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (317, '180.2', 'GBHN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (318, '180.3', 'Amnesti, Abolisi dan Grasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (319, '181', 'Perdata', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (320, '181.1', 'Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (321, '181.2', 'Rumah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (322, '181.3', 'Utang/Piutang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (323, '181.31', 'Gadai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (324, '181.32', 'Hipotik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (325, '181.4', 'Notariat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (326, '182', 'Pidana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (327, '182.1', 'Penyidik Pegawai Negeri Sipil (PPNS)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (328, '183', 'Peradilan', 'Peradilan Agama Islam 451.6, Peradilan Perkara Tanah 593.71', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (329, '183.1', 'Bantuan Hukum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (330, '184', 'Hukum Internasional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (331, '185', 'Imigrasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (332, '185.1', 'Visa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (333, '185.2', 'Pasport', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (334, '185.3', 'Exit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (335, '185.4', 'Reentry', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (336, '185.5', 'Lintas Batas/Batas Antar Negara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (337, '186', 'Kepenjaraan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (338, '187', 'Kejaksaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (339, '188', 'Peraturan Perundang-Undangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (340, '188.1', 'TAP MPR', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (341, '188.2', 'Undang-Undang Dasar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (342, '188.3', 'Peraturan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (343, '188.31', 'Peraturan Pemerintah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (344, '188.32', 'Peraturan Menteri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (345, '188.33', 'Peraturan Lembaga Non Departemen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (346, '188.34', 'Peraturan Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (347, '188.341', 'Peraturan Provinsi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (348, '188.342', 'Peraturan Kabupaten/Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (349, '188.4', 'Keputusan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (350, '188.41', 'Presiden', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (351, '188.42', 'Menteri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (352, '188.43', 'Lembaga Non Departemen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (353, '188.44', 'Gubernur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (354, '188.45', 'Bupati/Walikota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (355, '188.5', 'Instruksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (356, '188.51', 'Presiden', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (357, '188.52', 'Menteri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (358, '188.53', 'Lembaga Non Departemen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (359, '188.54', 'Gubernur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (360, '188.55', 'Bupati/Walikota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (361, '189', 'Hukum Adat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (362, '189.1', 'Tokoh Adat/Masyarakat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (363, '190', 'HUBUNGAN LUAR NEGERI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (364, '191', 'Perwakilan Asing', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (365, '192', 'Tamu Negara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (366, '193', 'Kerjasama Dengan Negara Asing', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (367, '193.1', 'Asean', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (368, '193.2', 'Bantuan Luar Negeri/Hibah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (369, '194', 'Perwakilan RI Di Luar Negeri/Hibah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (370, '195', 'PBB', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (371, '196', 'Laporan Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (372, '197', 'Hutang Luar Negeri PHLN/LOAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (373, '198', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (374, '199', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (375, '200', 'POLITIK', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (376, '201', 'Kebijaksanaan umum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (377, '202', 'Orde baru', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (378, '203', 'Reformasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (379, '204', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (380, '205', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (381, '206', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (382, '210', 'KEPARTAIAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (383, '211', 'Lambang partai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (384, '212', 'Kartu tanda anggota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (385, '213', 'Bantuan keuangan parpol', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (386, '214', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (387, '215', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (388, '216', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (389, '220', 'ORGANISASI KEMASYARAKATAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (390, '221', 'Berdasarkan perjuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (391, '221.1', 'Perintis kemerdekaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (392, '221.2', 'angkatan 45', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (393, '221.3', 'Veteran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (394, '222', 'Berdasarkan Kekaryaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (395, '222.1', 'PEPABRI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (396, '222.2', 'Wredatama', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (397, '223', 'Berdasarkan kerohanian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (398, '224', 'Lembaga adat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (399, '225', 'Lembaga Swadaya Masyarakat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (400, '226', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (401, '230', 'ORGANISASI PROFESI DAN FUNGSIONAL', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (402, '231', 'Ikatan Dokter Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (403, '232', 'Persatuan Guru Republik Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (404, '233', 'PERSATUAN SARJANA HUKUM INDONESIA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (405, '234', 'Persatuan Advokat Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (406, '235', 'Lembaga Bantuan Hukum Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (407, '236', 'Korps Pegawai Republik Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (408, '237', 'Persatuan Wartawan Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (409, '238', 'Ikatan Cendikiawan Muslim Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (410, '239', 'Organisasi Profesi Dan Fungsional Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (411, '240', 'ORGANISASI PEMUDA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (412, '241', 'Komite Nasional Pemuda Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (413, '242', 'Organisasi Mahasiswa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (414, '243', 'Organisasi Pelajar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (415, '244', 'Gerakan Pemuda Ansor', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (416, '245', 'Gerakan Pemuda Islam Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (417, '246', 'Gerakan Pemuda Marhaenis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (418, '247', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (419, '248', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (420, '250', 'ORGANISASI BURUH, TANI, NELAYAN DAN ANGKUTAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (421, '251', 'Federasi Buruh Seluruh Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (422, '252', 'Organisasi Buruh Internasional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (423, '253', 'Himpunan Kerukunan Tani', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (424, '254', 'Himpunan Nelayan Seluruh Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (425, '255', 'Keluarga Sopir Proporsional Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (426, '256', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (427, '257', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (428, '258', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (429, '260', 'ORGANISASI WANITA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (430, '261', 'Dharma Wanita', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (431, '262', 'Persatuan Wanita Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (432, '263', 'Pemberdayaan Perempuan (wanita)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (433, '264', 'Kongres Wanita', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (434, '265', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (435, '266', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (436, '267', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (437, '268', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (438, '269', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (439, '270', 'PEMILIHAN UMUM', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (440, '271', 'Pencalonan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (441, '272', 'Nomor Urut Partai / Tanda Gambar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (442, '273', 'Kampanye', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (443, '274', 'Petugas Pemilu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (444, '275', 'Pemilih / Daftar Pemilih', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (445, '276', 'Sarana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (446, '276.1', 'TPS', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (447, '276.2', 'Kendaraan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (448, '276.3', 'Surat Suara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (449, '276.4', 'Kotak Suara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (450, '276.5', 'Dana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (451, '277', 'Pemungutan Suara / Perhitungan Suara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (452, '278', 'Penetapan Hasil Pemilu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (453, '279', 'Penetapan Perolehan Jumlah Kursi Dan Calon Terpilih', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (454, '280', 'Pengucapan Sumpah Janji MPR,DPR,DPD', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (455, '281', '', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (456, '282', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (457, '283', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (458, '284', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (459, '300', 'KEAMANAN / KETERTIBAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (460, '301', 'Keamanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (461, '302', 'Ketertiban', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (462, '303', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (463, '310', 'PERTAHANAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (464, '311', 'Darat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (465, '312', 'Laut', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (466, '313', 'Udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (467, '314', 'Perbatasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (468, '315', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (469, '316', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (470, '317', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (471, '320', 'KEMILITERAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (472, '321', 'Latihan Militer', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (473, '322', 'Wajib Militer', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (474, '323', 'Operasi Militer', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (475, '324', 'Kekaryaan TNI Pejabat Sipil dari TNI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (476, '324.1', 'TMD', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (477, '325', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (478, '326', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (479, '327', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (480, '328', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (481, '330', 'KEAMANAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (482, '331', 'Kepolisian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (483, '331.1', 'Polisi Pamong Praja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (484, '331.2', 'Kamra', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (485, '331.3', 'Kamling', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (486, '331.4', 'Jaga Wana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (487, '332', 'Huru-Hara / Demonstrasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (488, '333', 'Senjata Api Tajam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (489, '334', 'Bahan Peledak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (490, '335', 'Perjudian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (491, '336', 'Surat-Surat Kaleng', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (492, '337', 'Pengaduan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (493, '338', 'Himbauan / Larangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (494, '339', 'Teroris', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (495, '340', 'PERTAHANAN SIPIL', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (496, '341', 'Perlindungan Sipil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (497, '342', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (498, '343', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (499, '344', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (500, '350', 'KEJAHATAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (501, '351', 'Makar / Pemberontak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (502, '352', 'Pembunuhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (503, '353', 'Penganiayaan, Pencurian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (504, '354', 'Subversi / Penyelundupan / Narkotika', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (505, '355', 'Pemalsuan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (506, '356', 'Korupsi / Penyelewengan / Penyalahgunaan Jabatan / KKN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (507, '357', 'Pemerkosaan / Perbuatan Cabul', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (508, '358', 'Kenakalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (509, '359', 'Kejahatan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (510, '360', 'BENCANA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (511, '361', 'Gunung Berapi / Gempa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (512, '362', 'Banjir / Tanah Longsor', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (513, '363', 'Angin Topan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (514, '364', 'Kebakaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (515, '364.1', 'Pemadam Kebakaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (516, '365', 'Kekeringan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (517, '366', 'Tsunami', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (518, '367', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (519, '368', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (520, '370', 'KECELAKAAN / SAR', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (521, '371', 'Darat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (522, '372', 'Udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (523, '373', 'Laut', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (524, '374', 'Sungai / Danau', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (525, '375', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (526, '376', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (527, '377', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (528, '380', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (529, '381', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (530, '382', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (531, '383', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (532, '390', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (533, '391', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (534, '392', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (535, '393', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (536, '400', 'KESEJAHTERAAN RAKYAT', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (537, '401', 'Keluarga Miskin', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (538, '402', 'PNPM Mandiri Pedesaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (539, '403', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (540, '404', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (541, '410', 'PEMBANGUNAN DESA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (542, '411', 'Pembinaan Usaha Gotong Royong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (543, '411.1', 'Swadaya Gotong Royong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (544, '411.11', 'Penataan Gotong Royong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (545, '411.12', 'Gotong Royong Dinamis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (546, '411.13', 'Gotong Royong Statis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (547, '411.14', 'Pungutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (548, '411.2', 'Lembaga Sosial Desa (LSD)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (549, '411.21', 'Pembinaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (550, '411.22', 'Klasifikasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (551, '411.23', 'Proyek', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (552, '411.24', 'Musyawarah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (553, '411.3', 'Latihan Kerja Masyarakat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (554, '411.31', 'Kader Masyarakat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (555, '411.32', 'Kuliah Kerja Nyata (KKN)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (556, '411.33', 'Pusat Latihan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (557, '411.34', 'Kursus-Kursus', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (558, '411.35', 'Kurikulum / Sylabus', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (559, '411.36', 'Ketrampilan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (560, '411.37', 'Pramuka', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (561, '411.4', 'Pembinaan Kesejahteraan Keluarga (PKK)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (562, '411.41', 'Program', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (563, '411.42', 'Pembinaan Organisasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (564, '411.43', 'Kegiatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (565, '411.5', 'Penyuluhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (566, '411.51', 'Publikasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (567, '411.52', 'Peragaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (568, '411.53', 'Sosio Drama', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (569, '411.54', 'Siaran Pedesaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (570, '411.55', 'Penyuluhan Lapangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (571, '411.6', 'Kelembagaan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (572, '411.61', 'Kelompok Tani', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (573, '411.62', 'Rukun Tani', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (574, '411.63', 'Subak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (575, '411.64', 'Dharma Tirta', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (576, '412', 'Perekonomian Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (577, '412.1', 'Produksi Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (578, '412.11', 'Pengolahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (579, '412.12', 'Pemasaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (580, '412.2', 'Keuangan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (581, '412.21', 'Perkreditan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (582, '412.22', 'Inventarisasi Data', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (583, '412.23', 'Perkembangan / Pelaksanaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (584, '412.24', 'Bantuan / Stimulans', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (585, '412.25', 'Petunjuk / Pembinaan Pelaksanaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (586, '412.3', 'Koperasi Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (587, '412.31', 'Badan Usaha Unit Desa (BUUD)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (588, '412.32', 'Koperasi Usaha Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (589, '412.4', 'Penataan Bantuan Pembangunan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (590, '412.41', 'Jumlah Desa Yang Diberi Bantuan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (591, '412.42', 'Pengarahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (592, '412.43', 'Pusat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (593, '412.44', 'Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (594, '412.5', 'Alokasi Bantuan Pembangunan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (595, '412.51', 'Pusat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (596, '412.52', 'Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (597, '412.6', 'Pelaksanaan Bantuan Pembangunan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (598, '412.61', 'Bantuan Langsung', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (599, '412.62', 'Bantuan Keserasian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (600, '412.63', 'Bantuan Juara Lomba Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (601, '413', 'Prasarana Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (602, '413.1', 'Prasarana Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (603, '413.11', 'Pembinaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (604, '413.12', 'Bimbingan Teknis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (605, '413.2', 'Pemukiman Kembali Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (606, '413.21', 'Lokasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (607, '413.22', 'Diskusi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (608, '413.23', 'Pelaksanaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (609, '413.3', 'Masyarakat Pradesa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (610, '413.31', 'Pembinaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (611, '413.32', 'Penyuluhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (612, '413.4', 'Pemugaran Perumahan Dan Lingkungan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (613, '413.41', 'Rumah Sehat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (614, '413.42', 'Proyek Perintis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (615, '413.43', 'Pelaksanaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (616, '413.44', 'Pengembangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (617, '413.45', 'Perbaikan Kampung', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (618, '414', 'Pengembangan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (619, '414.1', 'Tingkat Perkembangan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (620, '414.11', 'Jumlah Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (621, '414.12', 'Pemekaran Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (622, '414.13', 'Pembentukan Desa Baru', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (623, '414.14', 'Evaluasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (624, '414.15', 'Bagan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (625, '414.2', 'Unit Desa Kerja Pembangunan (UDKP)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (626, '414.21', 'Penyuluhan Program', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (627, '414.22', 'Lokasi UDKP', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (628, '414.23', 'Pelaksanaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (629, '414.24', 'Bimbingan/Pembinaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (630, '414.25', 'Evaluasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (631, '414.3', 'Tata Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (632, '414.31', 'Inventarisasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (633, '414.32', 'Penyusunan Pola Tata Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (634, '414.33', 'Aplikasi Tata Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (635, '414.34', 'Pemetaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (636, '414.35', 'Pedoman Pelaksanaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (637, '414.36', 'Evaluasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (638, '414.4', 'Perlombaan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (639, '414.41', 'Pedoman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (640, '414.42', 'Penilaian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (641, '414.43', 'Kejuaraan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (642, '414.44', 'Piagam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (643, '415', 'Koordinasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (644, '415.1', 'Sektor Khusus', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (645, '415.2', 'Rapat Koordinasi Horizontal (RKH)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (646, '415.3', 'Tim Koordinasi Pusat (TKP)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (647, '415.4', 'Kerjasama', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (648, '415.41', 'Luar Negeri (UNICEF)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (649, '415.42', 'Perguruan Tinggi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (650, '415.43', 'Kementerian / Lembaga Non Kementerian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (651, '416', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (652, '417', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (653, '418', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (654, '420', 'PENDIDIKAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (655, '420.1', 'Pendidikan Khusus Klasifikasi Disini Pendidikan Putra/I Irja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (656, '421', 'Sekolah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (657, '421.1', 'Pra Sekolah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (658, '421.2', 'Sekolah Dasar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (659, '421.3', 'Sekolah Menengah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (660, '421.4', 'Sekolah Tinggi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (661, '421.5', 'Sekolah Kejuruan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (662, '421.6', 'Kegiatan Sekolah, Dies Natalis Lustrum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (663, '421.7', 'Kegiatan Pelajar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (664, '421.71', 'Reuni Darmawisata', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (665, '421.72', 'Pelajar Teladan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (666, '421.73', 'Resimen Mahasiswa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (667, '421.8', 'Sekolah Pendidikan Luar Biasa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (668, '421.9', 'Pendidikan Luar Sekolah / Pemberantasan Buta Huruf', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (669, '422', 'Administrasi Sekolah', 'Persyaratan Masuk Sekolah, Testing, Ujian, Pendaftaran,', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (670, '422.1', 'Mapras, Perpeloncoan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (671, '422.2', 'Tahun Pelajaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (672, '422.3', 'Hari Libur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (673, '422.4', 'Uang Sekolah, Klasifikasi Disini SPP', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (674, '422.5', 'Beasiswa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (675, '423', 'Metode Belajar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (676, '423.1', 'Kuliah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (677, '423.2', 'Ceramah, Simposium', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (678, '423.3', 'Diskusi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (679, '423.4', 'Kuliah Lapangan, Widyawisata, KKN, Studi Tur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (680, '423.5', 'Kurikulum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (681, '423.6', 'Karya Tulis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (682, '423.7', 'Ujian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (683, '424', 'Tenaga Pengajar, Guru, Dosen, Dekan, Rektor', 'Klasifikasi Disini: Guru Teladan', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (684, '425', 'Sarana Pendidikan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (685, '425.1', 'Gedung', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (686, '425.11', 'Gedung Sekolah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (687, '425.12', 'Kampus', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (688, '425.13', 'Pusat Kegiatan Mahasiswa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (689, '425.2', 'Buku', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (690, '425.3', 'Perlengkapan Sekolah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (691, '426', 'Keolahragaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (692, '426.1', 'Cabang Olah Raga', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (693, '426.2', 'Sarana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (694, '426.21', 'Gedung Olah Raga', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (695, '426.22', 'Stadion', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (696, '426.23', 'Lapangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (697, '426.24', 'Kolam renang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (698, '426.3', 'Pesta Olah Raga, ', 'Klasifikasi Disini: PON, Porsade, Olimpiade, dsb', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (699, '426.4', 'KONI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (700, '427', 'Kepramukaan Meliputi: Organisasi Dan Kegiatan Remaja', 'Klasifikasi Disini: Gelanggang Remaja', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (701, '428', 'Kepramukaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (702, '429', 'Pendidikan  Kedinasan Untuk Depdagri, Lihat 890', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (703, '430', 'KEBUDAYAAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (704, '431', 'Kesenian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (705, '431.1', 'Cabang Kesenian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (706, '431.2', 'Sarana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (707, '431.21', 'Gedung Kesenian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (708, '432', 'Kepurbakalaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (709, '432.1', 'Museum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (710, '432.2', 'Peninggalan Kuno', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (711, '432.21', 'Candi Termasuk Pemugaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (712, '432.22', 'Benda', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (713, '433', 'Sejarah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (714, '434', 'Bahasa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (715, '435', 'Usaha Pertunjukan, Hiburan, Kesenangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (716, '436', 'Kepercayaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (717, '437', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (718, '438', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (719, '439', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (720, '440', 'KESEHATAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (721, '441', 'Pembinaan Kesehatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (722, '441.1', 'Gizi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (723, '441.2', 'Mata', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (724, '441.3', 'Jiwa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (725, '441.4', 'Kanker', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (726, '441.5', 'Usaha Kegiatan Sekolah (UKS)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (727, '441.6', 'Perawatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (728, '441.7', 'Penyuluhan Kesehatan Masyarakat (PKM)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (729, '441.8', 'Pekan Imunisasi Nasional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (730, '442', 'Obat-obatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (731, '442.1', 'Pengadaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (732, '442.2', 'Penyimpanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (733, '443', 'Penyakit Menular', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (734, '443.1', 'Pencegahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (735, '443.2', 'Pemberantasan dan Pencegahan Penyakit Menular Langsung (P2ML)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (736, '443.21', 'Kusta', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (737, '443.22', 'Kelamin', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (738, '443.23', 'Frambosia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (739, '443.24', 'TBC / AIDS / HIV', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (740, '443.3', 'Epidemiologi dan Karantina (Epidka)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (741, '443.31', 'Kholera', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (742, '443.32', 'Imunisasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (743, '443.33', 'Survailense', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (744, '443.34', 'Rabies (Anjing Gila) Antraks', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (745, '443.4', 'Pemberantasan & Pencegahan Penyakit Menular Sumber Binatang (P2B)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (746, '443.41', 'Malaria', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (747, '443.42', 'Dengue Faemorrhagic Fever (Demam Berdarah HDF)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (748, '443.43', 'Filaria', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (749, '443.44', 'Serangga', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (750, '443.5', 'Hygiene Sanitasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (751, '443.51', 'Tempat-tempat Pembuatan Dan Penjualan Makanan dan Minuman (TPPMM)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (752, '443.52', 'Sarana Air Minum Dan Jamban Keluarga (Samijaga)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (753, '443.53', 'Pestisida', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (754, '444', 'Gizi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (755, '444.1', ' Kekurangan Makanan Bahaya Kelaparan, Busung Lapar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (756, '444.2', 'Keracunan Makanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (757, '444.3', 'Menu Makanan Rakyat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (758, '444.4', 'Badan Perbaikan Gizi Daerah (BPGD)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (759, '444.5', 'Program Makanan Tambahn Anak Sekolah (PMT-AS)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (760, '445', 'Rumah Sakit, Balai Kesehatan, PUSKESMAS, PUSKESMAS, Keliling, Poliklinik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (761, '446', 'Tenaga Medis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (762, '448', 'Pengobatan Tadisional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (763, '448.1', 'Pijat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (764, '448.2', 'Tusuk Jarum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (765, '448.3', 'Jamu Tradisional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (766, '448.4', 'Dukun / Paranormal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (767, '450', 'AGAMA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (768, '451', 'Islam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (769, '451.1', 'Peribadatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (770, '451.11', 'Sholat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (771, '451.12', 'Zakat Fitrah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (772, '451.13', 'Puasa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (773, '451.14', 'MTQ', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (774, '451.2', 'Rumah Ibadah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (775, '451.3', 'Tokoh Agama', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (776, '451.4', 'Pendidikan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (777, '451.41', 'Tinggi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (778, '451.42', 'Menengah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (779, '451.43', 'Dasar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (780, '451.44', 'Pondok Pesantren', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (781, '451.45', 'Gedung Sekolah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (782, '451.46', 'Tenaga Pengajar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (783, '451.47', 'Buku', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (784, '451.48', 'Dakwah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (785, '451.49', 'Organisasi / Lembaga Pendidikan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (786, '451.5', 'Harta Agama Wakaf, Baitulmal, dsb', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (787, '451.6', 'Peradilan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (788, '451.7', 'Organisasi Keagamaan Bukan Politik Majelis Ulama', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (789, '451.8', 'Mazhab', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (790, '452', 'Protestan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (791, '452.1', 'Peribadatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (792, '452.2', 'Rumah Ibadah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (793, '452.3', 'Tokoh Agama, Rohaniawan, Pendeta, Domine', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (794, '452.4', 'Mazhab', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (795, '452.5', 'Organisasi Gerejani', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (796, '453', 'Katolik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (797, '453.1', 'Peribadatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (798, '453.2', 'Rumah Ibadah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (799, '453.3', 'Tokoh Agama, Rohaniawan, Pendeta, Pastor', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (800, '453.4', 'Mazhab', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (801, '453.5', 'Organisasi Gerejani', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (802, '454', 'Hindu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (803, '454.1', 'Peribadatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (804, '454.2', 'Rumah Ibadah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (805, '454.3', 'Tokoh Agama, Rohaniawan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (806, '454.4', 'Mazhab', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (807, '454.5', 'Organisasi Keagamaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (808, '455', 'Budha', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (809, '455.1', 'Peribadatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (810, '455.2', 'Rumah Ibadah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (811, '455.3', 'Tokoh Agama, Rohaniawan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (812, '455.4', 'Mazhab', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (813, '455.5', 'Organisasi Keagamaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (814, '456', 'Urusan Haji', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (815, '456.1', 'ONH', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (816, '456.2', 'Manasik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (817, '457', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (818, '458', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (819, '458', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (820, '460', 'SOSIAL', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (821, '461', 'Rehabilitasi Penderita Cacat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (822, '461.1', 'Cacat Maat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (823, '461.2', 'Cacat Tubuh', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (824, '461.3', 'Cacat Mental', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (825, '461.4', 'Bisul/Tuli', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (826, '462', 'Tuna Sosial', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (827, '462.1', 'Gelandangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (828, '462.2', 'Pengemis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (829, '462.3', 'Tuna Susila', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (830, '462.4', 'Anak Nakal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (831, '463', 'Kesejahteraan Anak / Keluarga', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (832, '463.1', 'Anak Putus Sekolah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (833, '463.2', 'Ibu Teladan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (834, '463.3', 'Anak Asuh', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (835, '464', 'Pembinaan Pahlawan', 'Pahlawan Meliputi: Penghargaan Kepada Pahlawan,', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (836, '464.1', 'Tunjangan Kepada Pahlawan Dan Jandanya', 'Perintis Kemerdekaan Meliputi: Pembinaan, Penghargaan', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (837, '464.2', 'Dan Tunjangan Kepada Perintis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (838, '464.3', 'Cacat Veteran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (839, '465', 'Kesejahteraan Sosial', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (840, '465.1', 'Lanjut Usia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (841, '465.2', 'Korban Kekacauan, Pengungsi, Repatriasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (842, '466', 'Sumbangan Sosial', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (843, '466.1', 'Korban Bencana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (844, '466.2', 'Pencarian Dana Untuk Sumbangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (845, '466.3', 'Meliputi: Penyelenggaraan Undian, Ketangkasan, Bazar, dsb', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (846, '466.4', 'Panti Asuhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (847, '466.5', 'Panti Jompo', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (848, '467', ' Bimbingan Sosial', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (849, '467.1', 'Masyarakat Suku Terasing Meliputi: Bimbingan, Pendidikan, Kesehatan, Pemukiman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (850, '468', 'PMI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (851, '469', 'Makam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (852, '469.1', 'Umum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (853, '469.2', 'Pahlawan Meliputi: Penghargaan Kepada Pahlawan, Tunjangan Kpd Pahlawan Dan Jandanya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (854, '469.3', 'Khusus Keluarga Raja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (855, '469.4', 'Krematorium', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (856, '470', 'KEPENDUDUKAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (857, '471', 'Pendaftaran Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (858, '471.1', 'Identitas Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (859, '471.11', 'Biodata', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (860, '471.12', 'Nomor Induk Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (861, '471.13', 'Kartu Tanda Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (862, '471.14', 'Kartu Keluarga', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (863, '471.15', 'Advokasi Indentitas Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (864, '471.2', 'Perpindahan Penduduk Dalam Wilayah Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (865, '471.21', 'Perpindahan Penduduk WNI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (866, '471.22', 'Perpindahan Penduduk WNA Dalam Wilayah Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (867, '471.23', 'Perpindahan Penduduk WNA dan WNI Tinggal Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (868, '471.24', 'Daerah Terbelakan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (869, '471.25', 'Bedol Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (870, '471.3', 'Perpindahan Penduduk Antar Negara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (871, '471.31', 'Penduduk Indonesia Ke Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (872, '471.32', 'Orang Asing Tinggal Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (873, '471.33', 'Orang Asing Tinggal Tetap', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (874, '471.34', 'Perpindahan Penduduk Antar Negara Di Wilayah Pembatasan Antar Negara (Pelintas Batas Tradisional)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (875, '471.4', 'Pendaftaran Pengungsi Dan Penduduk Rentan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (876, '471.41', 'Akibat Bencana Alam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (877, '471.42', 'Akibat Kerusuhan Sosial', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (878, '471.43', 'Pendaftaran Penduduk Daerah Terbelakang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (879, '471.44', 'Pendaftaran Penduduk Rentan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (880, '472', 'Pencatatan Sipil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (881, '472.1', 'Kelahiran, Kematian Dan Advokasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (882, '472.11', 'Kelahiran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (883, '472.12', 'Kematian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (884, '472.13', 'Advokasi Kelahiran Dan Kematian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (885, '472.2', 'Perkawinan, Peceraian Dan Advokasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (886, '472.3', 'Perkawinan Agama Islam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (887, '472.4', 'Perkawinan Agama Non Islam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (888, '472.5', 'Perceraian Agama Islam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (889, '472.6', 'Perceraian Agama Non Islam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (890, '472.7', 'Advokasi Perkawinan Dan Perceraian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (891, '472.3', 'Pengangkatan, Pengakuan, Dan Pengesahan Anak Serta Perubahan Dan Pembatalan Akta Dan Advokasi Pengangkatan Anak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (892, '472.31', 'Pengangkatan Anak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (893, '472.32', 'Pengakuan Anak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (894, '472.33', 'Pengesahan Anak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (895, '472.34', 'Perubahan Anak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (896, '472.35', 'Pembatalan Anak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (897, '472.36', 'Advokasi Pengurusan Pengangkatan, Pengakuan Dan Pengesahan Anak Serta Perubahan Dan Pembatalan Akta', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (898, '472.4', 'Pencatatan Kewarganegaraan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (899, '472.41', 'Akibat Perkawinan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (900, '472.42', 'Akibat Kelahiran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (901, '472.43', 'Non Perkawinan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (902, '472.44', 'Non Kelahiran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (903, '472.45', 'Perubahan WNI ke WNA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (904, '473', 'Informasi Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (905, '473.1', 'Teknologi Informasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (906, '473.11', 'Perangkat Keras', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (907, '473.12', 'Perangkat Lunak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (908, '473.13', 'Jaringan Komunikasi Data', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (909, '473.2', 'Kelembagaan Dan Sumber Daya Informasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (910, '473.21', 'Daerah Maju', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (911, '473.22', 'Daerah Berkembang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (912, '473.23', 'Daerah Terbelakang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (913, '473.3', 'Pengolahan Data Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (914, '473.31', 'Pendaftaran Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (915, '473.32', 'Kejadian Vital Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (916, '473.33', 'Penduduk Non Registrasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (917, '473.4', 'Pelayanan Informasi Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (918, '473.41', 'Media Elektronik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (919, '473.42', 'Media Cetak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (920, '473.43', 'Outlet', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (921, '474', 'Perkembangan Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (922, '474.1', 'Pengarahan Kuantitas Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (923, '474.11', 'Struktur Jumlah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (924, '474.12', 'Komposisi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (925, '474.13', 'Fertilitas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (926, '474.14', 'Kesehatan Reproduksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (927, '474.15', 'Morbiditas Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (928, '474.16', 'Mortalitas Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (929, '474.2', 'Pengembangan Kuantitas Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (930, '474.21', 'Anak dan Remaja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (931, '474.22', 'Penduduk Usia Produktif', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (932, '474.23', 'Penduduk Lanjut Usia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (933, '474.24', 'Gender', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (934, '474.3', 'Penataan Persebaran Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (935, '474.31', 'Migrasi Antar Wilayah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (936, '474.32', 'Migrasi Internasional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (937, '474.33', 'Urbanisasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (938, '474.34', 'Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (939, '474.35', 'Migrasi Non Permanen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (940, '474.4', 'Perlindungan Pemberdayaan Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (941, '474.41', 'Pengembangan Sistem Pelindungan Penduduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (942, '474.42', 'Pelayanan Kelembagaan Ekonomi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (943, '474.43', 'Pelayanan Kelembagaan Sosial Budaya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (944, '474.44', 'Partisipasi Masyarakat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (945, '474.5', 'Pengembangan Wawasan Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (946, '474.51', 'Pendidikan Jalur Sekolah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (947, '474.52', 'Pendidikan Jalur Luar Sekolah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (948, '474.53', 'Pendidikan Jalur Masyarakat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (949, '474.54', 'Pembangunan Berwawasan Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (950, '475', 'Proyeksi Dan Penyerasian Kebijakan Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (951, '475.1', 'Indikator Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (952, '475.11', 'Perumusan Penetapan Dan Pengembangan Indikator Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (953, '475.12', 'Pemanfaatan Indikator Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (954, '475.13', 'Sosialisasi Indikator Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (955, '475.2', 'Proyeksi Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (956, '475.21', 'Penyusunan Dan Pengembangan Proyeksi Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (957, '475.22', 'Pemanfaatan Proyeksi Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (958, '475.3', 'Analisis Dampak Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (959, '475.31', 'Penyusunan Dan Pengembangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (960, '475.32', 'Pemanfaatan Analisis Dampak Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (961, '475.4', 'Penyerasian Kebijakan Lembaga Non Pemerintah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (962, '475.41', 'Lembaga Internasioanal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (963, '475.42', 'Lembaga Masyarakat Dan Nirlaba', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (964, '475.43', 'Lembaga Usaha Swasta', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (965, '475.5', 'Penyerasian Kebijakan Lembaga Pemerintah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (966, '475.51', 'Lembaga Pemerintah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (967, '475.52', 'Pemerintah Provinsidan Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (968, '475.53', 'Pemerintah Kabupaten', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (969, '475.6', 'Analisis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (970, '476', 'Monitoring', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (971, '477', 'Evaluasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (972, '478', 'Dokumentasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (973, '479', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (974, '480', 'MEDIA MASSA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (975, '481', 'Penerbitan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (976, '481.1', 'Surat Kabar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (977, '481.2', 'Majalah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (978, '481.3', 'Buku', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (979, '481.4', 'Penerjemahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (980, '482', 'Radio', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (981, '482.1', 'RRI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (982, '482.11', 'Siaran Pedesaan Jgn Diklasifikasikan Disini', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (983, '482.2', 'Non RRI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (984, '482.3', 'Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (985, '483', 'Televisi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (986, '484', 'Film', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (987, '485', 'Pers', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (988, '485.1', 'Kewartawanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (989, '485.2', 'Wawancara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (990, '485.3', 'Informasi Nasional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (991, '486', 'Grafika', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (992, '487', 'Penerangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (993, '487.1', 'Pameran Non Komersil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (994, '488', 'Operation Room', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (995, '489', 'Hubungan Masyarakat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (996, '490', 'Pengaduan Masyarakat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (997, '491', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (998, '492', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (999, '500', 'PEREKONOMIAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1000, '500.1', 'Dewan Stabilisasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1001, '501', 'Pengadaan Pangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1002, '502', 'Pengadaan Sandang Perizinan Pada Umumnya Untuk Perizinan Suatu Bidang,', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1003, '503', 'Kalsifikasikan Masalahnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1004, '504', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1005, '505', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1006, '506', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1007, '510', 'PERDAGANGAN', 'Klasifikasikan Disini: Tata Niaga', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1008, '510.1', 'Promosi Perdagangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1009, '510.11', 'Pekan Raya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1010, '510.12', 'Iklan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1011, '510.13', 'Pameran Non Komersil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1012, '510.2', 'Pelelangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1013, '510.3', 'Tera', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1014, '511', 'Pemasaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1015, '511.1', 'Sembilan Bahan Pokok, Tambahkan Kode Wilayah : Beras, Garam, Tanah, Minyak Goreng', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1016, '511.2', 'Pasar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1017, '511.3', 'Pertokoan, Kaki Lima, Kios', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1018, '512', 'Ekspor', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1019, '513', 'Impor', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1020, '514', 'Perdagangan Antar Pulau', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1021, '515', 'Perdagangan Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1022, '516', 'Pergudangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1023, '517', 'Aneka Usaha Perdagangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1024, '518', 'Koperasi untuk BUUD, KUD lihat ( 412.31-412.32)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1025, '519', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1026, '520', 'PERTANIAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1027, '521', 'Tanaman Pangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1028, '521.1', 'Program', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1029, '521.11', 'Bimas / Inmas Termasuk Kredit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1030, '521.12', 'Penyuluhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1031, '521.2', 'Produksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1032, '521.21', 'Padi / Panen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1033, '521.22', 'Palawija', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1034, '521.23', 'Jagung', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1035, '521.24', 'Ketela Pohon / Ubi-Ubian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1036, '521.25', 'Hortikultura', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1037, '521.26', 'Sayuran / Buah-Buahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1038, '521.27', 'Tanaman Hias', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1039, '521.28', 'Pembudidayaan Rumput Laut', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1040, '521.3', 'Saran Usaha Pertanian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1041, '521.31', 'Peralatan Meliputi: Traktor Dan Peralatan Lainya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1042, '521.32', 'Pembibitan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1043, '521.33', 'Pupuk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1044, '521.4', 'Perlindungan Tanaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1045, '521.41', 'Penyakit, Penyakit Daun, Penyakit Batang Hama, Serangga, Wereng, Walang Sangit, Tungru, Tikus Dan Sejenisnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1046, '521.42', 'Pemberantasan Hama Meliputi: Penyemprotan, Penyiangan, Geropyokan, Sparayer,', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1047, '521.43', 'Pemberantasan Melalui Udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1048, '521.44', 'Pestisida', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1049, '521.5', 'Tanah Pertanian Pangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1050, '521.51', 'Persawahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1051, '521.52', 'Perladangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1052, '521.53', 'Kebun', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1053, '521.54', 'Rumpun Ikan Laut', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1054, '521.55', 'KTA/Lahan Kritis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1055, '521.6', 'Pengusaha Petani', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1056, '521.7', 'Bina Usaha', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1057, '521.71', 'Pasca Panen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1058, '521.72', 'Pemasaran Hasil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1059, '522', 'Kehutanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1060, '522.1', 'Program', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1061, '522.11', 'Hak Pengusahaan Hutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1062, '522.12', 'Tata Guna Hutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1063, '522.13', 'Perpetaan Hutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1064, '522.14', 'Tumpangsari', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1065, '522.2', 'Produksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1066, '522.21', 'Kayu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1067, '522.22', 'Non Kayu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1068, '522.3', 'Sarana  Usaha  Kehutanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1069, '522.4', 'Penghijauan, Reboisasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1070, '522.5', 'Kelestarian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1071, '522.51', 'Cagar Alam, Marga Satwa, Suaka Marga Satwa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1072, '522.52', 'Berburu Meliputi Larangan Dan Ijin Berburu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1073, '522.53', 'Kebun Binatang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1074, '522.54', 'Konservasi Lahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1075, '522.6', 'Penyakit/Hama', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1076, '522.7', 'Jenis-jenis Hutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1077, '522.71', 'Hutan Hidup', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1078, '522.72', 'Hutan Wisata', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1079, '522.73', 'Hutan Produksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1080, '522.74', 'Hutan Lindung', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1081, '523', 'Perikanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1082, '523.1', 'Program', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1083, '523.11', 'Penyuluhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1084, '523.12', 'Teknologi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1085, '523.2', 'Produksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1086, '523.21', 'Pelelangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1087, '523.3', 'Usaha Perikanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1088, '523.31', 'Pembibitan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1089, '523.32', 'Daerah Penagkapan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1090, '523.33', 'Pertambakan Meliputi: ( Tambak Ikan Air Deras, Tambak Udang dll )', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1091, '523.34', 'Jaring Terapung', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1092, '523.4', 'Sarana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1093, '523.41', 'Peralatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1094, '523.42', 'Kapal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1095, '523.43', 'Pelabuhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1096, '523.5', 'Pengusaha', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1097, '523.6', 'Nelayan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1098, '524', 'Peternakan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1099, '524.1', 'Produksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1100, '524.11', 'Susu Ternak Rakyat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1101, '524.12', 'Telur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1102, '524.13', 'Daging', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1103, '524.14', 'Kulit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1104, '524.2', 'Sarana Usaha Ternak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1105, '524.21', 'Pembibitan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1106, '524.22', 'Kandang Ternak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1107, '524.3', 'Kesehatan Hewan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1108, '524.31', 'Penyakit Hewan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1109, '524.32', 'Pos Kesehatan Hewan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1110, '524.33', 'Tesi Pullorum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1111, '524.34', 'Karantina', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1112, '524.35', 'Pemberantasan Penyakit Hewan Termasuk Usaha Pencegahannya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1113, '524.4', 'Perunggasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1114, '524.5', 'Pengembangan Ternak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1115, '524.51', 'Inseminasi Buatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1116, '524.52', 'Pembibitan / Bibit Unggul', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1117, '524.53', 'Penyebaran Ternak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1118, '524.6', 'Makanan Ternak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1119, '524.7', 'Tempat Pemotongan Hewan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1120, '524.8', 'Data Peternakan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1121, '525', 'Perkebunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1122, '525.1', 'Program', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1123, '525.2', 'Produksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1124, '525.21', 'Karet', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1125, '525.22', 'The', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1126, '525.23', 'Tembakau', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1127, '525.24', 'Tebu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1128, '525.25', 'Cengkeh', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1129, '525.26', 'Kopra', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1130, '525.27', 'Kopi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1131, '525.28', 'Coklat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1132, '525.29', 'Aneka Tanaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1133, '526', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1134, '527', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1135, '528', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1136, '530', 'PERINDUSTRIAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1137, '530.08', 'Undang-Undang Gangguan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1138, '531', 'Industri Logam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1139, '532', 'Industri Mesin/Elektronik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1140, '533', 'Industri Kimia/Farmasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1141, '534', 'Industri Tekstil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1142, '535', 'Industri Makanan / Minuman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1143, '536', 'Aneka Industri / Perusahaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1144, '537', 'Aneka Kerajinan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1145, '538', 'Usaha Negara / BUMN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1146, '538.1', 'Perjan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1147, '538.2', 'Perum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1148, '538.3', 'Persero / PT, CV', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1149, '539', 'Perusahaan Daerah / BUMD/BULD', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1150, '540', 'PERTAMBANGAN / KESAMUDRAAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1151, '541', 'Minyak Bumi / Bensin', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1152, '541.1', 'Pengusahaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1153, '542', 'Gas bumi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1154, '542.1', 'Eksploitasi / Pengeboran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1155, '542.11', 'Kontrak Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1156, '542.2', 'Penogolahan,', 'Meliputi :Tangki, Pompa, Tanker', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1157, '543', 'Aneka Tambang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1158, '543.1', 'Timah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1159, '543.2', 'Alumunium, Boxit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1160, '543.3', 'Besi Termasuk Besi Tua', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1161, '543.4', 'Tembaga', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1162, '543.5', 'Batu Bara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1163, '544', 'Logam Mulia,Emas,Intan,Perak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1164, '545', 'Logam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1165, '546', 'Geologi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1166, '546.1', 'Vulkanologi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1167, '546.11', 'Pengawasan Gunung Berapi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1168, '546.2', 'Sumur Artesis, Air Bawah Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1169, '547', 'Hidrologi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1170, '548', 'Kesamudraan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1171, '549', 'Pesisir Pantai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1172, '550', 'PERHUBUNGAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1173, '551', 'Perhubungan Darat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1174, '551.1', 'Lalu Lintas Jalan Raya, Sungai, Danau', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1175, '551.11', 'Keamanan Lalu Lintas, Rambu-Rambu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1176, '551.2', 'Angkutan Jalan Raya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1177, '551.21', 'Perizinan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1178, '551.22', 'Terminal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1179, '551.23', 'Alat Angkutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1180, '551.3', 'Angkutan Sungai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1181, '551.31', 'Perizinan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1182, '551.32', 'Terminal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1183, '551.33', 'Pelabuhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1184, '551.4', 'Angkutan Danau', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1185, '551.41', 'Perizinan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1186, '551.42', 'Terminal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1187, '551.43', 'Pelabuhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1188, '551.5', 'Feri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1189, '551.51', 'Perizinan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1190, '551.52', 'Terminal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1191, '551.53', 'Pelabuhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1192, '551.6', 'Perkereta-Apian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1193, '552', 'Perhubungan Laut', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1194, '552.1', 'Lalu Lintas Angkutan Laut, Pelayanan Umum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1195, '552.11', 'Keamanan Lalu Lintas, Rambu-Rambu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1196, '552.12', 'Pelayaran Dalam Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1197, '552.13', 'Pelayaran Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1198, '552.2', 'Perkapalan Alat Angkutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1199, '552.3', 'Pelabuhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1200, '552.4', 'Pengerukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1201, '552.5', 'Penjagaan Pantai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1202, '553', 'Perhubungan Udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1203, '553.1', 'Lalu Lintas Udara / Keamanan Lalu Lintas Udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1204, '553.2', 'Pelabuhan Udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1205, '553.3', 'Alat Angkutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1206, '554', 'Pos', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1207, '555', 'Telekomunikasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1208, '555.1', 'Telepon', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1209, '555.2', 'Telegram', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1210, '555.3', 'Telex / SSB, Faximile', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1211, '555.4', 'Satelit, Internet', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1212, '555.5', 'Stasiun Bumi, Parabola', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1213, '556', 'Pariwisata dan Rekreasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1214, '556.1', 'Obyek Kepariwisataan Taman Mini Indonesia Indah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1215, '556.2', 'Perhotelan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1216, '556.3', 'Travel service', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1217, '556.4', 'Tempat Rekreasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1218, '557', 'Meteorologi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1219, '557.1', 'Ramalan Cuaca', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1220, '557.2', 'Curah Hujan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1221, '557.3', 'Kemarau Panjang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1222, '558', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1223, '559', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1224, '560', 'TENAGA KERJA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1225, '560.1', 'Pengangguran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1226, '561', 'Upah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1227, '562', 'Penempatan Tenaga Kerja, TKI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1228, '563', 'Latihan Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1229, '564', 'Tenaga Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1230, '564.1', 'Butsi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1231, '564.2', 'Padat Karya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1232, '565', 'Perselisihan Perburuhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1233, '566', 'Keselamatan Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1234, '567', 'Pemutusan Hubungan Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1235, '568', 'kesejahteraan Buruh', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1236, '569', 'Tenaga Orang Asing', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1237, '570', 'PERMODALAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1238, '571', 'Modal Domestik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1239, '572', 'Modal Asing', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1240, '573', 'Modal Patungan (Joint Venture) / Penyertaan Modal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1241, '574', 'Pasar Uang Dan Modal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1242, '575', 'Saham', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1243, '576', 'Belanja Modal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1244, '577', 'Modal Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1245, '580', 'PERBANKAN / MONETER', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1246, '581', 'Kredit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1247, '582', 'Investasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1248, '583', 'Pembukaan ,Perubahan,Penutupan Rekening, Deposito', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1249, '584', 'Bank Pembangunan Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1250, '585', 'Asuransi Dana Kecelakaan Lalu Lintas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1251, '586', 'Alat Pembayaran, Cek, Giro, Wesel, Transfer', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1252, '587', 'Fiskal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1253, '588', 'Hutang Negara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1254, '589', 'Moneter', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1255, '590', 'AGRARIA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1256, '591', 'Tataguna Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1257, '591.1', 'Pemetaan dan Pengukuran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1258, '591.2', 'Perpetaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1259, '591.3', 'penyediaan Data', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1260, '591.4', 'Fatwa Tata Guna Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1261, '591.5', 'Tanah Kritis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1262, '592', 'Landreform', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1263, '592.1', 'Redistribusi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1264, '592.11', 'Pendaftaran Pemilikan Dan Pengurusan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1265, '592.12', 'Penentuan Tanah Obyek Landreform', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1266, '592.13', 'Pembagian Tanah Obyek Landreform', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1267, '592.14', 'Sengketa Redistribusi Tanah Obyek Landreform', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1268, '592.2', 'Ganti Rugi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1269, '592.21', 'Ganti Rugi Tanah Kelebihan', 'Meliputi : Sengketa Ganti Rugi Tanah Kelebihan Tanah', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1270, '592.22', 'Ganti Rugi Tanah Absentee', 'Meliputi : Sengketa Ganti Rugi Tanah Absentee', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1271, '592.23', 'Ganti Rugi Tanah Partikelir', 'Meliputi : Sengketa Ganti Rugi Tanah Partikelir', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1272, '592.3', 'Bagi Hasil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1273, '592.31', 'Penetapan Imbangan Bagi Hasil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1274, '592.32', 'Pelaksanaan Perjanjian Bagi Hasil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1275, '592.33', 'Sengketa Perjanjian Bagi Hasil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1276, '592.4', 'Gadai Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1277, '592.41', 'Pendaftaran Pemilikan Dan Pengurusan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1278, '592.42', 'Pelaksanaan Gadai Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1279, '592.43', 'Sengketa Gadai Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1280, '592.5', 'Bimbingan dan Penyuluhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1281, '592.6', 'Pengembangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1282, '592.7', 'Yayasan Dana Landreform', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1283, '593', 'Pengurusan Hak-Hak Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1284, '593.01', 'Penyusunan Program Dan Bimbingan Teknis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1285, '593.1', 'Sewa Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1286, '593.11', 'Sewa Tanah Untuk Tanaman Tertentu, Tebu, Tembakau, Rosela, Chorcorus', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1287, '593.2', 'Hak Milik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1288, '593.21', 'Perorangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1289, '593.22', 'Badan Hukum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1290, '593.3', 'Hak Pakai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1291, '593.31', 'Perorangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1292, '593.311', 'Warga Negara Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1293, '593.312', 'Warga Negara Asing', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1294, '593.32', 'Badan Hukum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1295, '593.321', 'Badan Hukum Indonesia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1296, '593.322', 'Badan Hukum Asing, Kedutaan, Konsulat Kantor Dagang Asing', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1297, '593.33', 'Tanah Gedung-Gedung Negara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1298, '593.4', 'Guna Usaha', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1299, '593.41', 'Perkebunan Besar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1300, '593.42', 'Perkebunan Rakyat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1301, '593.43', 'Peternakan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1302, '593.44', 'Perikanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1303, '593.45', 'Kehutanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1304, '593.5', 'Hak Guna Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1305, '593.51', 'Perorangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1306, '593.52', 'Badan Hukum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1307, '593.53', 'P3MB (Panitia Pelaksana Penguasaan Milik Belanda)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1308, '593.54', 'Badan Hukum Asing Belanda-Prrk No 5165', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1309, '593.55', 'Pemulihan Hak (Pen Pres 4/1960)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1310, '593.6', 'Hak Pengelolaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1311, '593.61', 'PN Perumnas, Bonded Ware House, Industrial Estate, Real Estate', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1312, '593.62', 'Perusahaan Daerah Pembangunan Perumahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1313, '593.7', 'Sengketa Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1314, '593.71', 'Peradilan Perkara Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1315, '593.8', 'Pencabutan dan Pembebasan Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1316, '593.81', 'Pencabutan Hak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1317, '593.82', 'Pembebasan Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1318, '593.83', 'Ganti Rugi Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1319, '594', 'Pendaftaran Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1320, '594.1', 'Pengukuran / Pemetaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1321, '594.11', 'Fotogrametri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1322, '594.12', 'Terristris', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1323, '594.13', 'Triangulasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1324, '594.14', 'Peralatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1325, '594.2', 'Dana Pengukuran (Permen Agraria No. 61/1965)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1326, '594.3', 'Sertifikat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1327, '594.4', 'Pejabat Pembuat Akta Tanah (PPAT)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1328, '595', 'Lahan Transmigrasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1329, '595.1', 'Tataguna Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1330, '595.2', 'Landreform', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1331, '595.3', 'Pengurusan Hak-Hak Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1332, '595.4', 'Pendaftaran Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1333, '596', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1334, '597', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1335, '598', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1336, '599', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1337, '600', 'PEKERJAAN UMUM DAN KETENAGAKERJAAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1338, '601', 'Tata Bangunan Konstruksi Dan Industri Konstruksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1339, '602', 'Kontraktor Pemborong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1340, '602.1', 'Tender', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1341, '602.2', 'Pennunjukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1342, '602.3', 'Prakualifikasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1343, '602.31', 'Daftar Rekanan Mampu (DRM)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1344, '602.32', 'Tanda Daftar Rekanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1345, '603', 'Arsitektur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1346, '604', 'Bahan Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1347, '604.1', 'Tanah Dan Batu Seperti: Batu Belah, Steen Slaag, Split dsb', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1348, '604.2', 'Aspal, Aspal Buatan, Aspal Alam (butas)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1349, '604.3', 'Besi Dan Logam Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1350, '604.31', 'Besi Beton', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1351, '604.32', 'Besi Profil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1352, '604.33', 'Paku', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1353, '604.34', 'Alumunium, Profil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1354, '604.4', 'Bahan-Bahan Pelindung Dan Pengawet ', '(Cat, Tech Til, Pengawet Kayu)', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1355, '604.5', 'Semen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1356, '604.6', 'Kayu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1357, '604.7', 'Bahan Penutup Atap ', '(Genting, Asbes Gelombang, Seng Dan Sebagainya)', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1358, '604.8', 'Alat-Alat Penggantung Dan Pengunci', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1359, '604.9', 'Bahan-Bahan Bangunan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1360, '605', 'Instalasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1361, '605.1', 'Instalasi Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1362, '605.2', 'Instalasi Listrik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1363, '605.3', 'Instalasi Air Sanitasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1364, '605.4', 'Instalasi Pengatur Udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1365, '605.5', 'Instalasi Akustik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1366, '605.6', 'Instalasi Cahaya / Penerangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1367, '606', 'Konstruksi Pencegahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1368, '606.1', 'Konstruksi Pencegahan Terhadap Kebakaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1369, '606.2', 'Konstruksi Pencegahan Terhadap Gempa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1370, '606.3', 'Konstruksi Penegahan Terhadap Angin Udara/Panas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1371, '606.4', 'Konstruksi Pencegahan Terhadap Kegaduhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1372, '606.5', 'Konstruksi Pencegahan Terhadap Gas/Explosive', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1373, '606.6', 'Konstruksi Pencegahan Terhadap Serangga', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1374, '606.7', 'Konstruksi Pencegahan Terhadap Radiasi Atom', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1375, '607', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1376, '608', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1377, '609', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1378, '610', 'PENGAIRAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1379, '611', 'Irigasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1380, '611.1', 'Bangunan Waduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1381, '611.11', 'Bendungan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1382, '611.12', 'Tanggul', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1383, '611.13', 'Pelimpahan Banjir', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1384, '611.14', 'Menara Pengambilan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1385, '611.2', 'Bangunan Pengambilan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1386, '611.21', 'Bendungan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1387, '611.22', 'Bendungan Dengan Pintu Bilas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1388, '611.23', 'Bendungan Dengan Pompa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1389, '611.24', 'Pengambilan Bebas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1390, '611.25', 'Pengambilan Bebas Dengan Pompa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1391, '611.26', 'Sumur Dengan Pompa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1392, '611.27', 'Kantung Lumpur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1393, '611.28', 'Slit Ekstrator', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1394, '611.29', 'Escope Channel', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1395, '611.3', 'Bangunan Pembawa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1396, '611.31', 'Saluran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1397, '611.311', 'Saluran Induk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1398, '611.312', 'Saluran Sekunder', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1399, '611.313', 'Suplesi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1400, '611.314', 'Tersier', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1401, '611.315', 'Saluran Kwarter', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1402, '611.316', 'Saluran Pasangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1403, '611.317', 'Saluran Tertutup / Terowongan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1404, '611.32', 'Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1405, '611.321', 'Bangunan Bagi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1406, '611.322', 'Bangunan Bagi Dan Sadap', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1407, '611.323', 'Bangunan Sadap', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1408, '611.324', 'Bangunan Check', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1409, '611.325', 'Bangunan Terjun', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1410, '611.33', 'Box Tersier', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1411, '611.34', 'Got Miring', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1412, '611.35', 'Talang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1413, '611.36', 'Syphon', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1414, '611.37', 'Gorong-Gorong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1415, '611.38', 'Pelimpah Samping', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1416, '611.4', 'Bangunan Pembuang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1417, '611.41', 'Saluran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1418, '611.411', 'Saluran Pembuang Induk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1419, '611.412', 'Saluran Pembuang Sekunder', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1420, '611.413', 'Saluran Tersier', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1421, '611.42', 'Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1422, '611.421', 'Bangunan Outlet', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1423, '611.422', 'Bangunan Terjun', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1424, '611.423', 'Bangunan Penahan Banjir', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1425, '611.43', 'Gorong-Gorong Pembuang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1426, '611.44', 'Talang Pembuang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1427, '611.45', 'Syphon Pembuang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1428, '611.5', 'Bangunan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1429, '611.51', 'Jalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1430, '611.511', 'Jalan Inspeksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1431, '611.512', 'Jalan Logistik Waduk Lapangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1432, '611.52', 'Jembatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1433, '611.521', 'Jembatan Inspeksi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1434, '611.522', 'Jembatan Hewan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1435, '611.53', 'Tangga Cuci', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1436, '611.54', 'Kubangan Kerbau', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1437, '611.55', 'Waduk Lapangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1438, '611.56', 'Bangunan Penunjang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1439, '611.57', 'Jaringan Telepon', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1440, '611.58', 'Stasiun Agro', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1441, '612', 'Folder', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1442, '612.1', 'Tanggul Keliling', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1443, '612.11', 'Tanggul', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1444, '612.12', 'Bangunan Penutup Sungai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1445, '612.13', 'Jembatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1446, '612.2', 'Bangunan Pembawa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1447, '612.21', 'Saluran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1448, '612.211', 'Saluran Muka', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1449, '612.212', 'Saluran Pembawa Waduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1450, '612.213', 'Saluran Pembawa Sekunder', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1451, '612.22', 'Stasiun Pompa Pemasukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1452, '612.23', 'Bangunan Bagi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1453, '612.24', 'Gorong-Gorong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1454, '612.25', 'Syphon', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1455, '612.3', 'Bangunan Pembuang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1456, '612.31', 'Stasiun Pompa Pembuang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1457, '612.32', 'Saluran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1458, '612.321', 'Saluran Pembuang Induk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1459, '612.322', 'Saluran Pembuang Sekunder', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1460, '612.33', 'Pintu Air Pembuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1461, '612.34', 'Gorong-Gorong Pembuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1462, '612.35', 'Syphon Pembuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1463, '612.4', 'Bangunan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1464, '612.41', 'Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1465, '612.411', 'Bangunan Pengukur Air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1466, '612.412', 'Bangunan Pengukur Curah Hujan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1467, '612.413', 'Bangunan Gudang Stasiun Pompa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1468, '612.414', 'Bangunan Listrik Stasiun Pompa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1469, '612.42', 'Rumah Petugas Aksploitasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1470, '613', 'Pasang Surut', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1471, '613.1', 'Bangunan Pembawa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1472, '613.11', 'Saluran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1473, '613.111', 'Saluran Pembawa Induk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1474, '613.112', 'Saluran Pembawa Sekunder', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1475, '613.113', 'Saluran Pembawa Tersier', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1476, '613.114', 'Saluran penyimpanan air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1477, '613.12', 'Bangunan Pintu Pemasukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1478, '613.2', 'Bangunan Pembuang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1479, '613.21', 'Saluran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1480, '613.211', 'Saluran Pembuang Induk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1481, '613.212', 'Saluran Pembuang Sekunder', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1482, '613.213', 'Saluran Pembuang Tersier', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1483, '613.214', 'Saluran Pengumpul Air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1484, '613.22', 'Bangunan Pintu Pembuang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1485, '613.3', 'Bangunan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1486, '613.31', 'Kolam Pasang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1487, '613.32', 'Saluran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1488, '613.321', 'Saluran Lalu Lintas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1489, '613.322', 'Saluran Muka', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1490, '613.33', 'Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1491, '613.331', 'Bangunan Penangkis Kotoran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1492, '613.332', 'Bangunan Pengukur Muka Air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1493, '613.333', 'Bangunan Pengukur Curah Hujan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1494, '613.34', 'Jalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1495, '613.35', 'Jembatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1496, '614', 'Pengendalian Sungai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1497, '614.1', 'Bangunan Pengaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1498, '614.11', 'Tanggul Banjir', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1499, '614.12', 'Pintu Pengatur Banjir', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1500, '614.13', 'Klep Pengatur Banjir', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1501, '614.14', 'Tembok Pengaman Talud', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1502, '614.15', 'Krib', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1503, '614.16', 'Kantung Lumpur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1504, '614.17', 'Check-Dam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1505, '614.18', 'Syphon', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1506, '614.2', 'Saluran Pengaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1507, '614.21', 'Saluran Banjir', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1508, '614.22', 'Saluran Drainage', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1509, '614.23', 'Corepure', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1510, '614.3', 'Bangunan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1511, '614.31', 'Warning System', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1512, '614.32', 'Stasiun', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1513, '614.321', 'Stasiun Pengukur Curah Hujan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1514, '614.322', 'Stasiun Pengukur Air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1515, '614.323', 'Stasiun Pengukur Cuaca', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1516, '614.324', 'Stasiun Pos Penjagaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1517, '615', 'Pengamanan Pantai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1518, '615.1', 'Tanggul', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1519, '615.2', 'Krib', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1520, '615.3', 'Bangunan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1521, '616', 'Air Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1522, '616.1', 'Stasiun Pompa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1523, '616.2', 'Bangunan Pembawa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1524, '616.3', 'Bangunan Pembuang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1525, '616.4', 'Bangunan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1526, '617', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1527, '618', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1528, '619', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1529, '620', 'JALAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1530, '621', 'Jalan Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1531, '621.1', 'Daerah Penguasaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1532, '621.11', 'Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1533, '621.12', 'Tanaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1534, '621.13', 'Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1535, '621.2', 'Bangunan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1536, '621.21', 'Jalan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1537, '621.22', 'Jembatan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1538, '621.23', 'Kantor Proyek', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1539, '621.24', 'Gedung Proyek', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1540, '621.25', 'Barak Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1541, '621.26', 'Laboratorium Lapangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1542, '621.27', 'Rumah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1543, '621.3', 'Badan Jalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1544, '621.31', 'Pekerjaan Tanah (Earth Work)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1545, '621.32', 'Stabilisasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1546, '621.4', 'Perkerasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1547, '621.41', 'Lapis Pondasi Bawah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1548, '621.42', 'Lapis Pondasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1549, '621.43', 'Lapis Permukaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1550, '621.5', 'Drainage', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1551, '621.51', 'Parit Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1552, '621.52', 'Gorong-Gorong (Culvert)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1553, '621.6', 'Buku Trotuir', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1554, '621.61', 'Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1555, '621.62', 'Perkerasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1556, '621.63', 'Pasangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1557, '621.7', 'Median', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1558, '621.71', 'Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1559, '621.72', 'Tanaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1560, '621.73', 'Perkerasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1561, '621.74', 'Pasangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1562, '621.8', 'Daerah Samping', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1563, '621.81', 'Tanaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1564, '621.82', 'Pagar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1565, '621.9', 'Bangunan Pelengkap Dan Pengamanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1566, '621.91', 'Rambu-Rambu/Tanda-Tanda Lalu Lintas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1567, '621.92', 'Lampu Penerangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1568, '621.93', 'Lampu Pengatur Lalu Lintas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1569, '621.94', 'Patok-Patok KM', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1570, '621.95', 'Patok-Patok ROW (Sempadan)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1571, '621.96', 'Rel Pengamanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1572, '621.97', 'Pagar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1573, '621.98', 'Turap Penahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1574, '621.99', 'Bronjong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1575, '622', 'Jalan Luar Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1576, '622.1', 'Daerah Penguasaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1577, '622.11', 'Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1578, '622.12', 'Tanaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1579, '622.13', 'Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1580, '622.2', 'Bangunan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1581, '622.21', 'Jalan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1582, '622.22', 'Jembatan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1583, '622.23', 'Kantor Proyek', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1584, '622.24', 'Gudang Proyek', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1585, '622.25', 'Barak Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1586, '622.26', 'Laboratorium Lapangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1587, '622.27', 'Rumah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1588, '622.3', 'Badan Jalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1589, '622.31', 'Pekerjaan Tanah (Earth Work)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1590, '622.32', 'Stabilisasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1591, '622.4', 'Perkerasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1592, '622.41', 'Lapis Pondasi Bawah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1593, '622.42', 'Lapis Pondasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1594, '622.43', 'Lapis Permukaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1595, '622.5', 'Drainage', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1596, '622.51', 'Parit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1597, '622.52', 'Gorong-Gorong (Culvert)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1598, '622.53', 'Sub Drainage', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1599, '622.6', 'Trotoar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1600, '622.61', 'Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1601, '622.62', 'Perkerasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1602, '622.7', 'Median', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1603, '622.71', 'Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1604, '622.72', 'Tanaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1605, '622.73', 'Perkerasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1606, '622.74', 'Pasangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1607, '622.8', 'Daerah Samping', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1608, '622.81', 'Tanaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1609, '622.82', 'Pagar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1610, '622.9', 'Bangunan Pelengkap Dan Pengamanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1611, '622.91', 'Rambu-Rambu/Tanda-Tanda Lalu Lintas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1612, '622.92', 'Lampu Penerangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1613, '622.93', 'Lampu Pengatur Lalu Lintas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1614, '622.94', 'Patok-Patok KM', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1615, '622.95', 'Patok-Patok ROW (Sempadan)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1616, '622.96', 'Rel Pengamanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1617, '622.97', 'Pagar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1618, '622.98', 'Turap Penahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1619, '622.99', 'Bronjong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1620, '623', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1621, '623', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1622, '623', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1623, '630', 'JEMBATAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1624, '631', 'Jembatan Pada Jalan Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1625, '631.1', 'Daerah Penguasaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1626, '631.11', 'Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1627, '631.12', 'Tanaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1628, '631.13', 'Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1629, '631.2', 'Bangunan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1630, '631.21', 'Jalan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1631, '631.22', 'Jembatan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1632, '631.23', 'Kantor Proyek', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1633, '631.24', 'Gudang Proyek', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1634, '631.25', 'Barak Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1635, '631.26', 'Laboratorium Lapangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1636, '631.27', 'Rumah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1637, '631.3', 'Pekerjaan Tanah (Earth Work)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1638, '631.31', 'Galian Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1639, '631.32', 'Timbunan Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1640, '631.4', 'Pondasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1641, '631.41', 'Pondasi Kepala Jalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1642, '631.42', 'Pondasi Pilar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1643, '631.43', 'Angker', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1644, '631.5', 'Bangunan Bawah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1645, '631.51', 'Kepala Jembatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1646, '631.52', 'Pilar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1647, '631.53', 'Piloon', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1648, '631.54', 'Landasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1649, '631.6', 'Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1650, '631.61', 'Gelagar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1651, '631.62', 'Lantai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1652, '631.63', 'Perkerasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1653, '631.64', 'Jalan Orang / Trotoar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1654, '631.65', 'Sandaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1655, '631.66', 'Talang air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1656, '631.7', 'Bangunan / Pengaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1657, '631.71', 'Turap Penahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1658, '631.72', 'Bronjong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1659, '631.73', '', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1660, '631.74', 'Kist Dam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1661, '631.75', 'Corepure', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1662, '631.76', 'Krib', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1663, '631.8', 'Bangunan Pelengkap', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1664, '631.81', 'Rambu-Rambu/Tanda-Tanda Lalu Lintas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1665, '631.82', 'Lampu Penerangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1666, '631.83', 'Lampu Pengatur Lalu Lintas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1667, '631.84', 'Patok Pengaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1668, '631.85', 'Patok ROW (Sempadan)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1669, '631.86', 'Pagar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1670, '631.9', 'Oprit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1671, '631.91', 'Badan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1672, '631.92', 'Perkerasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1673, '631.93', 'Drainage', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1674, '631.94', 'Baku', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1675, '631.95', 'Median', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1676, '632', 'Jembatan Pada Jalan Luar Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1677, '632.1', 'Daerah Penguasaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1678, '632.11', 'Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1679, '632.12', 'Tanaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1680, '632.13', 'Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1681, '632.2', 'Bangunan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1682, '632.21', 'Jalan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1683, '632.22', 'Jembatan Sementara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1684, '632.23', 'Kantor Proyek', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1685, '632.24', 'Gudang Proyek', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1686, '632.25', 'Barak Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1687, '632.26', 'Laboratorium Lapangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1688, '632.27', 'Rumah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1689, '632.3', 'Pekerjaan Tanah (Earth Work)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1690, '632.31', 'Galian Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1691, '632.32', 'Timnunan Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1692, '632.4', 'Pondasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1693, '632.41', 'Pondasi Kepala Jembatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1694, '632.42', 'Pondasi Pilar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1695, '632.43', 'Pondasi Angker', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1696, '632.5', 'Bangunan Bawah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1697, '632.51', 'Kepala Jembatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1698, '632.52', 'Pilar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1699, '632.53', 'Piloon', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1700, '632.54', 'Landasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1701, '632.6', 'Bangunan Atas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1702, '632.61', 'Gelagar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1703, '632.62', 'Lantai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1704, '632.63', 'Perkerasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1705, '632.64', 'Jalan Orang / Trotoar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1706, '632.65', 'Sandaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1707, '632.66', 'Talang Air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1708, '632.7', 'Bangunan Pengaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1709, '632.71', 'Turap / Penahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1710, '632.72', 'Bronjong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1711, '632.73', 'Stek Dam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1712, '632.74', 'Kist Dam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1713, '632.75', 'Corepure', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1714, '632.76', 'Krib', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1715, '632.8', 'Bangunan Pelengkap', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1716, '632.81', 'Rambu-Rambu/Tanda-Tanda Lalu Lintas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1717, '632.82', 'Lampu Penerangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1718, '632.83', 'Lampu Pengatur Lalu Lintas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1719, '632.84', 'Patok Pengaman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1720, '632.85', 'Patok ROW (Sempadan)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1721, '632.86', 'Pagar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1722, '632.9', 'Oprit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1723, '632.91', 'Badan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1724, '632.92', 'Perkerasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1725, '632.93', 'Drainage', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1726, '632.94', 'Baku', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1727, '632.95', 'Median', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1728, '633', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1729, '634', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1730, '635', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1731, '640', 'BANGUNAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1732, '640.1', 'Gedung Pengadilan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1733, '640.2', 'Rumah Pejabat Negara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1734, '640.3', 'Gedung DPR', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1735, '640.4', 'Gedung Balai Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1736, '640.5', 'Penjara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1737, '640.6', 'Perkantoran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1738, '642', 'Bangunan Pendidikan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1739, '642.1', 'Taman Kanak-Kanak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1740, '642.2', 'SD & SEKOLAH MENENGAH', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1741, '642.3', 'Perguruan Tinggi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1742, '643', 'Bangunan Rekreasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1743, '643.1', 'BANGUNAN OLAH RAGA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1744, '643.2', 'Gedung Kesenian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1745, '643.3', 'Gedung Pemancar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1746, '644', 'Bangunan Perdagangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1747, '644.1', 'Pusat Perbelanjaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1748, '644.2', 'Gedung Perdagangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1749, '644.3', 'Bank', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1750, '644.4', 'Pekantoran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1751, '645', 'Bangunan Pelayanan Umum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1752, '645.1', 'MCK', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1753, '645.2', 'Gedung Parkir', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1754, '645.3', 'Rumah Sakit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1755, '645.4', 'Gedung Telkom', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1756, '645.5', 'Terminal Angkutan udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1757, '645.6', 'Terminal Angkutan udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1758, '645.7', 'Terminal Angkutan Darat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1759, '645.8', 'Bangunan Keagamaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1760, '646', 'Bangunan Peninggalan Sejarah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1761, '646.1', 'Monumen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1762, '646.2', 'Candi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1763, '646.3', 'Keraton', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1764, '646.4', 'Rumah Tradisional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1765, '647', 'Bangunan Industri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1766, '648', 'Bangunan Tempat Tinggal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1767, '648.1', 'Rumah Perkotaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1768, '648.11', 'Inti / Sederhana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1769, '648.12', 'Sedang / Mewah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1770, '648.2', 'Rumah Pedesaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1771, '648.21', 'Rumah Contoh', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1772, '648.3', 'Real Estate', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1773, '648.4', 'Bapetarum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1774, '649', 'Elemen Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1775, '649.1', 'Pondasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1776, '649.11', 'Di Atas Tiang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1777, '649.2', 'Dinding', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1778, '649.21', 'Penahan Beban', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1779, '649.22', 'Tidak Menahan Beban', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1780, '649.3', 'Atap', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1781, '649.4', 'Lantai / Langit-Langit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1782, '649.41', 'Supended', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1783, '649.42', 'Solit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1784, '649.5', 'Pintu / Jendela', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1785, '649.51', 'Pintu Harmonik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1786, '649.52', 'Pintu Biasa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1787, '649.53', 'Pintu Sorong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1788, '649.54', 'Pintu Kayu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1789, '649.55', 'Jendela Sorong', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1790, '649.56', 'Jendela Vertikal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1791, '650', 'TATA KOTA', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1792, '651', 'Daerah Perdagangan / Pelabuhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1793, '651.1', 'Daerah Pusat Perbelanjaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1794, '651.2', 'Daerah Perkotaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1795, '652', 'Daerah Pemerintah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1796, '653', 'Daerah Perumahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1797, '653.1', 'Kepadatan Rendah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1798, '653.2', 'Kepadatan Tinggi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1799, '654', 'Daerah Industri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1800, '654.1', 'Industri Berat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1801, '654.2', 'Industri Ringan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1802, '654.3', 'Industri Ringan (Home Industry)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1803, '655', 'Daerah Rekreasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1804, '655.1', 'Public Garden', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1805, '655.2', 'Sport & Playing Fields', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1806, '655.3', 'Open Space', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1807, '656', 'Transportasi (Tata Letak)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1808, '656.1', 'Jaringan Jalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1809, '656.11', 'Penerangan Jalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1810, '656.2', 'Jaringan Kereta Api', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1811, '656.3', 'Jaringan Sungai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1812, '657', 'Assaineering', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1813, '657.1', 'Saluran Pengumpulan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1814, '657.2', 'Instalasi Pengolahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1815, '657.21', 'Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1816, '657.211', 'Bangunan Penyaringan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1817, '657.212', 'Bangunan Penghancur Kotoran / Sampah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1818, '657.213', 'Bangunan Pengendap', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1819, '657.214', 'Bangunan Pengering Lumpur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1820, '657.22', 'Unit Densifektan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1821, '657.23', 'Unit Perpompaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1822, '658', 'Kesehatan Lingkungan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1823, '658.1', 'Persampahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1824, '658.11', 'Bangunan Pengumpul', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1825, '658.12', 'Bangunan Pemusnahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1826, '658.2', 'Pengotoran Udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1827, '658.3', 'pengotoran Air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1828, '658.31', 'Air Buangan Industri Limbah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1829, '658.4', 'Kegaduhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1830, '658.5', 'Kebersihan Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1831, '659', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1832, '660', 'TATA LINGKUNGAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1833, '660.1', 'Persampahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1834, '660.2', 'Kebersihan Lingkungan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1835, '660.3', 'Pencemaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1836, '660.31', 'Pecemaran Air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1837, '660.32', 'Pencemaran Udara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1838, '661', 'Daerah Hutan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1839, '662', 'Daerah Pertanian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1840, '663', 'Daerah Pemikiman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1841, '664', 'Pusat Pertumbuhan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1842, '665', 'Transportasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1843, '665.1', 'Jaringan Jalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1844, '665.2', 'Jaringan Kereta Api', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1845, '665.3', 'Jaringan Sungai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1846, '666', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1847, '667', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1848, '668', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1849, '670', 'KETENAGAAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1850, '671', 'Listrik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1851, '671.1', 'Kelistrikan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1852, '671.11', 'Kelisrikan PLN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1853, '671.12', 'Kelistrikan Non PLN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1854, '671.2', 'Pembangkit Tenaga Listrik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1855, '671.21', 'PLTA  ( Pembangkit  Listrik Tenaga Air )', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1856, '671.22', 'PLTD  ( Pembangkit Listrik Tenaga Diesel )', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1857, '671.23', 'PLTG P ( Pembangkit Listrik Tenaga Gas )', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1858, '671.24', 'PLTM ( Pembangkit  Listrik Tenaga Matahari )', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1859, '671.25', 'PLTN ( Pembangkit Listrik Tenaga Nuklir )', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1860, '671.26', 'PLTPB ( Pembangkit Listrik Tenaga Uap )', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1861, '671.3', 'Transmisi Tenaga Listrik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1862, '671.31', 'Gardu Induk/Gardu Penghubung/Gardu Trafo', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1863, '671.32', 'Saluran Udara Tegangan Tinggi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1864, '671.33', 'Kabel Bawah Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1865, '671.4', 'Distribusi Tenaga Listrik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1866, '671.41', 'Gardu Distribusi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1867, '671.42', 'Tegangan Rendah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1868, '671.43', 'Tegangan Menengah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1869, '671.44', 'Jaringan Bawah Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1870, '671.5', 'Pengusahaan Listrik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1871, '671.51', 'Sambungan Listrik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1872, '671.52', 'Penjualan Tenaga Listrik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1873, '671.53', 'Tarif Listrik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1874, '672', 'Tenaga Air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1875, '673', 'Tenaga Minyak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1876, '674', 'Tenaga Gas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1877, '675', 'Tenaga Matahari', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1878, '676', 'Tenaga Nuklir', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1879, '677', 'Tenaga Panas Bumi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1880, '678', 'Tenaga Uap', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1881, '679', 'Tenaga Lainya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1882, '680', 'PERALATAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1883, '681', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1884, '682', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1885, '683', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1886, '690', 'AIR MINUM', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1887, '691', 'Intake', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1888, '691.1', 'Broncaptering', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1889, '691.2', 'Sumur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1890, '691.3', 'Bendungan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1891, '691.4', 'Saringan (screen)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1892, '691.5', 'Pintu air', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1893, '691.6', 'Saluran Pembawa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1894, '691.7', 'Alat Ukur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1895, '691.8', 'Perpompaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1896, '692', 'Transmisi Air Baku', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1897, '692.1', 'Perpipaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1898, '692.2', 'Katup Udara (Air Relief)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1899, '692.3', 'Katup Penguras (Blow Off)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1900, '692.4', 'Bak Pelepas Tekanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1901, '692.5', 'Jembatan Pipa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1902, '692.6', 'Syphon', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1903, '693', 'Instalasi Pengelolaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1904, '693.1', 'Bangunan Ukur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1905, '693.2', 'Bangunan Aerasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1906, '693.3', 'Bangunan Pengendapan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1907, '693.4', 'Bangunan Pembubuh Bahan Kimia', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1908, '693.5', 'Bangunan Pengaduk', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1909, '693.6', 'Bangunan Saringan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1910, '693.7', 'Perpompaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1911, '693.8', 'Clear Hell', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1912, '694', 'Distribusi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1913, '694.1', 'Reservoir Menara Bawah Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1914, '694.11', 'Menara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1915, '694.12', 'reservoir di Bawah Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1916, '694.2', 'Perpipaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1917, '694.3', 'Perpompaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1918, '694.4', 'Jembatan Pipa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1919, '694.5', 'Syphon', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1920, '694.6', 'Hydran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1921, '694.61', 'Hydran Umum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1922, '694.62', 'Hydran Kebakaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1923, '694.7', 'Katup', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1924, '694.71', 'Katup Udara (Air Relief)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1925, '694.72', 'Katup Pelepas (Blow Off)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1926, '694.8', 'Bak Pelepas Tekanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1927, '695', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1928, '696', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1929, '697', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1930, '698', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1931, '699', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1932, '700', 'PENGAWASAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1933, '701', 'Bidang Urusan Dalam', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1934, '702', 'Bidang Peralatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1935, '703', 'Bidang Kekayaan Daerah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1936, '704', 'Bidang Perpustakaan / Dokumentasi / Kearsipan Sandi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1937, '705', 'Bidang Perencanaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1938, '706', 'Bidang Organisasi / Ketatalaksanaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1939, '707', 'Bidang Penelitian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1940, '708', 'Bidang Konferensi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1941, '709', 'Bidang Perjalanan Dinas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1942, '710', 'BIDANG PEMERINTAHAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1943, '711', 'Bidang Pemerintahan Pusat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1944, '712', 'Bidang Pemerintahan Provinsi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1945, '713', 'Bidang Pemerintahan Kabupaten / Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1946, '714', 'Bidang Pemerintahan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1947, '715', 'Bidang MPR / DPR', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1948, '716', 'Bidang DPRD Provinsi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1949, '717', 'Bidang DPRD Kabupaten / Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1950, '718', 'Bidang Hukum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1951, '719', 'Bidang Hubungan Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1952, '720', 'BIDANG POLITIK', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1953, '721', 'Bidang Kepartaian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1954, '722', 'Bidang Organisasi Kemasyarakatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1955, '723', 'Bidang Organisasi Profesi Dan Fungsional', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1956, '724', 'Bidang Organisasi Pemuda', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1957, '725', 'Bidang Organisasi Buruh, Tani, Dan Nelayan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1958, '726', 'Bidang Organisasi Wanita', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1959, '727', 'Bidang Pemilihan Umum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1960, '730', 'BIDANG KEAMANAN/KETERTIBAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1961, '731', 'Bidang Pertahanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1962, '732', 'Bidang Kemiliteran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1963, '733', 'Bidang Perlindungan Masyarakat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1964, '734', 'Bidang Kemanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1965, '735', 'bidang Kejahatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1966, '736', 'Bidang Bencana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1967, '737', 'Bidang Kecelakaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1968, '738', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1969, '739', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1970, '740', 'BIDANG KESEJAHTERAAN RAKYAT', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1971, '741', 'Bidang Pembagunan Desa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1972, '742', 'Bidang Pendidikan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1973, '743', 'Bidang Kebudayaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1974, '744', 'Bidang Kesehatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1975, '745', 'Bidang Agama', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1976, '746', 'Bidang Sosial', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1977, '747', 'Bidang Kependudukan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1978, '748', 'Bidang Media Massa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1979, '749', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1980, '750', 'BIDANG PEREKONOMIAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1981, '751', 'Bidang Perdagangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1982, '752', 'Bidang Pertanian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1983, '753', 'Bidang Perindustrian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1984, '754', 'Bidang Pertambangan / Kesamudraan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1985, '755', 'Bidang Perhubungan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1986, '756', 'Bidang Tenaga Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1987, '757', 'Bidang Permodalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1988, '758', 'Bidang Perbankan / Moneter', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1989, '759', 'Bidang Agraria', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1990, '760', 'BIDANG PEKERJAAN UMUM', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1991, '761', 'Bidang Pengairan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1992, '762', 'Bidang Jalan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1993, '763', 'Bidang Jembatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1994, '764', 'Bidang Bangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1995, '765', 'Bidang Tata Kota', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1996, '766', 'Bidang Lingkungan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1997, '767', 'Bidang Ketenagaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1998, '768', 'Bidang Peralatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (1999, '769', 'Bidang Air Minum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2000, '770', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2001, '771', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2002, '772', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2003, '780', 'BIDANG KEPEGAWAIAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2004, '781', 'Bidang Pengadaan Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2005, '782', 'Bidang Mutasi Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2006, '783', 'Bidang Kedudukan Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2007, '784', 'Bidang Kesejahteran Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2008, '785', 'Bidang Cuti', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2009, '786', 'Bidang Penilaian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2010, '787', 'Bidang Tata Usaha Kepegawaian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2011, '788', 'Bidang Pemberhentian Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2012, '789', 'Bidang Pendidikan Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2013, '790', 'BIDANG KEUANGAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2014, '791', 'Bidang Anggaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2015, '792', 'Bidang Otorisasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2016, '793', 'Bidang Verifikasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2017, '794', 'Bidang Pembukuan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2018, '795', 'Bidang Perbendaharaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2019, '796', 'Bidang Pembina Kebendaharaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2020, '797', 'Bidang Pendapatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2021, '798', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2022, '799', 'Bidang Bendaharaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2023, '800', 'KEPEGAWAIAN', 'Klasifikasi Disini: Kebijaksanaan Kepegawaian', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2024, '800.1', 'Perencanaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2025, '800.2', 'Penelitian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2026, '800.043', 'Pengaduan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2027, '800.05', 'Tim', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2028, '800.07', 'Statistik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2029, '800.08', 'Peraturan Perundang-Undangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2030, '810', 'PENGADAAN', 'Meliputi: Lamaran, Pengujian Kesehatan, Dan Pengangkatan Calon Pegawai', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2031, '811', 'Lamaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2032, '811.1', 'Testing', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2033, '811.2', 'Screening', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2034, '811.3', 'Panggilan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2035, '812', 'Pengujian Kesehatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2036, '813', 'Pengangkatan Calon Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2037, '813.1', 'Pengangkatan Calon Pegawai Golongan 1', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2038, '813.2', 'Pengangkatan Calon Pegawai Golongan II', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2039, '813.3', 'Pengangkatan Calon Pegawai Golongan III', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2040, '813.4', 'Pengangkatan Calon Pegawai Golongan IV', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2041, '813.5', 'Pengangkatan Calon Guru Inpres', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2042, '814', 'Pengangkatan Tenaga Lepas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2043, '814.1', 'Pengangkatan Tenaga Bulanan / Tenaga Kontrak', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2044, '814.2', 'Pengangkatan Tenaga Harian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2045, '814.3', 'Pengangkatan Tenaga Pensiunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2046, '815', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2047, '816', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2048, '817', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2049, '820', 'MUTASI', 'Meliputi: Pengangkatan, Kenaikan Gaji Berkala, Kenaikan Pangkat, Pemindahan, Pelimpahan Datasering, Tugas Belajar Dan Wajib Militer', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2050, '821', 'Pengangkatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2051, '821.1', 'Pengangkatan Menjadi Pegawai Negeri Tetap', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2052, '821.11', 'Pengangkatan Menjadi Pegawai Negeri Golongan 1', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2053, '821.12', 'Pengangkatan Menjadi Pegawai Negeri Golongan 2', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2054, '821.13', 'Pengangkatan Menjadi Pegawai Negeri Golongan 3', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2055, '821.14', 'Pengangkatan Menjadi Pegawai Negeri Golongan 4', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2056, '821.15', 'Pengangkatan Menjadi Pegawai Negeri Sipil Yang Cuti Di Luar Tanggungan Negara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2057, '821.2', 'Pengangkatan Dalam Jabatan, Pembebasan Dari Jabatan, Berita Acara Serah Terima Jabatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2058, '821.21', 'Sekjen/Dirjen/Irjen/Kabag', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2059, '821.22', 'Kepala Biro/Direktur/Inspektur/Kepala Pusat/Sekretaris/Kepala Dinas/Asisten Sekwilda', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2060, '821.23', 'Kepala Bagian/Kepala Sub Direktorat/Kepala Bidang/Inspektur Pembantu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2061, '821.24', 'Kepala Subbagian/Kepala Seksi/Kepala Sub Bidang/Pemeriksa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2062, '821.25', 'Residen/Pembantu Gubernur', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2063, '821.26', 'Wedana/Pembantu Bupati', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2064, '821.27', 'Camat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2065, '821.28', 'Lurah Administratif (Lurah Desa)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2066, '821.29', 'Jabatan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2067, '822', 'Kenaikan Gaji Berkala', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2068, '822.1', 'Pegawai Golongan 1', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2069, '822.2', 'Pegawai Golongan 2', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2070, '822.3', 'Pegawai Golongan 3', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2071, '822.4', 'Pegawai Golongan 4', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2072, '823', 'Kenaikan Pangkat / Pengangkatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2073, '823.1', 'Pegawai Golongan 1', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2074, '823.2', 'Pegawai Golongan 2', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2075, '823.3', 'Pegawai Golongan 3', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2076, '823.4', 'Pegawai Golongan 4', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2077, '824', 'Pemindahan / Pelimpahan / Perbantuan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2078, '824.1', 'Pegawai Golongan 1', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2079, '824.2', 'Pegawai Golongan 2', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2080, '824.3', 'Pegawai Golongan 3', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2081, '824.4', 'Pegawai Golongan 4', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2082, '824.5', 'Lolos Butuh', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2083, '824.6', 'Kurikulum dan Silabi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2084, '824.7', 'Proposal (TOR)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2085, '825', 'Datasering dan Penempatan Kembali', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2086, '826', 'Penunjukan Tugas Belajar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2087, '826.1', 'Dalam Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2088, '826.2', 'Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2089, '826.3', 'Tunjangan Belajar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2090, '826.4', 'Penempatan Kembali', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2091, '827', 'Wajib Militer', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2092, '828', 'Mutasi Dengan Instansi Lain', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2093, '829', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2094, '830', 'KEDUDUKAN', 'Meliputi: Perhitungan Masa Kerja, Penyesuaian Pangkat/Gaji, Penghargaan Ijasah, Dan Jenjang Pangkat', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2095, '831', 'Perhitungan Masa Kerja', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2096, '832', 'Penyesuaian Pangkat / Gaji', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2097, '832.1', 'Pegawai Golongan 1', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2098, '832.2', 'Pegawai Golongan 2', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2099, '832.3', 'Pegawai Golongan 3', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2100, '832.4', 'Pegawai Golongan 4', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2101, '833', 'Penghargaan Ijazah / Penyesuaian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2102, '834', 'Jenjang Pangkat / Eselonering', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2103, '835', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2104, '836', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2105, '837', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2106, '840', 'KESEJAHTERAAN PEGAWAI', 'Meliputi: Tunjangan, Dana, Perawatan Kesehatan, Koperasi, Distribusi, Permahan/Tanah, Bantuan Sosial, Rekreasi Dan Dispensasi.', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2107, '841', 'Tunjangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2108, '841.1', 'Jabatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2109, '841.2', 'Kehormatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2110, '841.3', 'Kematian/Uang Duka', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2111, '841.4', 'Tunjangan Hari Raya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2112, '841.5', 'Perjalanan Dinas Tetap/Cuti/Pindah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2113, '841.6', 'Keluarga', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2114, '841.7', 'Sandang, Pangan, Papan (Bapertarum)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2115, '842', 'Dana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2116, '842.1', 'Taspen', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2117, '842.2', 'Kesehatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2118, '842.3', 'Asuransi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2119, '843', 'Perawatan Kesehatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2120, '843.1', 'Poliklinik', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2121, '843.2', 'Perawatan Dokter', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2122, '843.3', 'Obat-Obatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2123, '843.4', 'Keluarga Berencana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2124, '844', 'Koperasi / Distribusi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2125, '844.1', 'Distribusi Pangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2126, '844.2', 'Distribusi Sandang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2127, '844.3', 'Distribusi Papan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2128, '845', 'Perumahan/Tanah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2129, '845.1', 'Perumahan Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2130, '845.2', 'Tanah Kapling', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2131, '845.3', 'Losmen/Hotel', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2132, '846', 'Bantuan Sosial', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2133, '846.1', 'Bantuan Kebakaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2134, '846.2', 'Bantuan Kebanjiran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2135, '847', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2136, '848', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2137, '849', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2138, '850', 'CUTI ', 'Meliputi Cuti Tahunan, Cuti Besar, Cuti Sakit, Cuti Hamil, Cuti Naik Haji, Cuti Diluar Tanggungan Negara Dan Cuti Alasan Lain', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2139, '851', 'Cuti Tahunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2140, '852', 'Cuti Besar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2141, '853', 'Cuti Sakit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2142, '854', 'Cuti Hamil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2143, '855', 'Cuti Naik Haji/Umroh', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2144, '856', 'Cuti Di Luar Tangungan Neagara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2145, '857', 'Cuti Alasan Lain/Alasan Penting', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2146, '858', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2147, '859', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2148, '860', 'PENILAIAN', 'Meliputi: Penghargaan, Hukuman, Konduite, Ujian Dinas,Penilaian Kakayaan Pribadi Dan Rehabilitasi', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2149, '861', 'Penghargaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2150, '861.1', 'Bintang/Satyalencana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2151, '861.2', 'Kenaikan Pangkat Anumerta', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2152, '861.3', 'Kenaikan Gaji Istimewa', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2153, '861.4', 'Hadiah Berupa Uang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2154, '861.5', 'Pegawai Teladan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2155, '862', 'Hukuman', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2156, '862.1', 'Teguran Peringatan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2157, '862.2', 'Penundaan Kenaikan Gaji', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2158, '862.3', 'Penurunan Pangkat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2159, '862.4', 'Pemindahan', 'Catatan: Pemberhentian Untuk Sementara Waktu Dan Pemberhentian Tidak Dengan Hormat Lihat 887 Dan 888', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2160, '863', 'Konduite, DP3, Disiplin Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2161, '864', 'Ujian Dinas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2162, '864.1', 'Tingkat 1', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2163, '864.2', 'Tingkat 2', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2164, '864.3', 'Tingkat 3', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2165, '865', 'Penilaian Kehidupan Pegawai Negeri', 'Meliputi: Petunjuk Pelaksanaan Hidup Sederhana, Penilaian Kekayaan Pribadi ( LP2P )', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2166, '866', 'Rehabilitasi / Pengaktifan Kembali', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2167, '867', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2168, '868', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2169, '869', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2170, '870', 'TATA USAHA KEPEGAWAIAN', 'Meliputi: Formasi, Bezetting, Registrasi,Daftar, Riwayat Hidup, Hak, Penggajian, Sumpah,/Janji Dan Korps Pegawai', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2171, '871', 'Formasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2172, '872', 'Bezetting/Daftar Urut Kepegawaian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2173, '873', 'Registrasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2174, '873.1', 'NIP', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2175, '873.2', 'KARPEG', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2176, '873.3', 'Legitiminasi/Tanda Pengenal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2177, '873.4', 'Daftar Keluarga, Perkawinan, Perceraian, Karis, Karsu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2178, '874', 'Daftar Riwayat Pekerjaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2179, '874.1', 'Tanggal Lahir', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2180, '874.2', 'Penggantian Nama', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2181, '874.3', 'Izin kepartaian Organisasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2182, '875', 'Kewenangan Mutasi Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2183, '875.1', 'Pelimpahan Wewenang', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2184, '875.2', 'Specimen Tanda Tangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2185, '876', 'Penggajian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2186, '876.1', 'SKPP', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2187, '877', 'Sumpah/Janji', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2188, '878', 'Korps Pegawai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2189, '879', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2190, '880', 'PEMBERHENTIAN PEGAWAI', 'Meliputi Atas  Pemberhentian,Permintaan Sendiri, Dengan Hak Pensiun, Karena Meninggal Dunia, Alasan Lain, Dengan Diberi Uang Pesangon, Uang Tnggu Untuk Sementara Waktu Dan Pemberhentian Tidak Dengan  Hormat', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2191, '881', 'Permintaan Sendiri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2192, '882', 'Dengan Hak Pensiun', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2193, '882.1', 'Pemberhentian Dengan Hak Pensiun Pegawai Negeri Golongan 1', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2194, '882.2', 'Pemberhentian Dengan Hak Pensiun Pegawai Negeri Golongan 2', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2195, '882.3', 'Pemberhentian Dengan Hak Pensiun Pegawai Negeri Golongan 3', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2196, '882.4', 'Pemberhentian Dengan Hak Pensiun Pegawai Negeri Golongan 4', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2197, '882.5', 'Pensiun Janda / Duda', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2198, '882.6', 'Pensiun Yatim Piatu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2199, '882.7', 'Uang Muka Pensiun', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2200, '883', 'Karena Meninggal', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2201, '883.1', 'Karena Meninggal Dalam Tugas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2202, '884', 'Alasan Lain', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2203, '885', 'Uang Pesangon', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2204, '886', 'Uang Tunggu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2205, '887', 'Untuk Sementara Waktu', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2206, '888', 'Tidak Dengan Hormat', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2207, '889', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2208, '890', 'PENDIDIKAN PEGAWAI', 'Meliputi: Perencanaan, Pendidikan Reguler, Pendidikan Non-Reguler, Pendidikan Ke Luar Negeri, Metode, Tenaga Pengajar, Administrasi Pendidikan, Fasilitas Sarana Pendidikan', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2209, '891', 'Perencanaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2210, '891.1', 'Program', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2211, '891.2', 'Kurikulum dan Silabi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2212, '891.3', 'Proposal ( TOR )', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2213, '892', 'Pendidikan _Egular / Kader', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2214, '892.1', 'IPDN / APDN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2215, '892.2', 'Kursus-Kursus Reguler', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2216, '893', 'Pendidikan dan Pelatihan / Non Reguler', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2217, '893.1', 'LEMHANAS', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2218, '893.2', 'Pendidikan dan Pelatihan Struktural, SPATI, SPAMEN, SPAMA, ADUMLA, ADUM', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2219, '893.3', 'Kursus-Kursus / Penataran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2220, '893.4', 'Diklat Tehnik, Fungsional Dan Manajemen Pemerintahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2221, '893.5', 'Diklat Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2222, '894', 'Pendidikan Luar Negeri', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2223, '894.1', 'Berkesinambungan / Berkala / Bergelar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2224, '894.2', 'Non Gelar / Diploma', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2225, '895', 'Metode', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2226, '895.1', 'Kuliah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2227, '895.2', 'Ceramah, Simposium', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2228, '895.3', 'Diskusi, Raker, Seminar, Lokakarya, Orientasi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2229, '895.4', 'Studi Lapangan, Kkn, Widyawisata', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2230, '895.5', 'Tanya Jawab / Sylabi / Modul / Kursil', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2231, '895.7', 'Penugasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2232, '895.8', 'Gladi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2233, '896', 'Tenaga Pengajar / Widyaiswara/Narasumber', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2234, '896.1', 'Moderator', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2235, '897', 'Administrasi Pendidikan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2236, '897.1', 'Tahun Pelajaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2237, '897.2', 'Persyaratan, Pendaftaran, Testing, Ujian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2238, '897.3', 'STTP', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2239, '897.4', 'Penilaian Angka Kredit', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2240, '897.5', 'Laporan Pendidikan Dan Pelatihan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2241, '898', 'Fasilitas Belajar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2242, '898.1', 'Tunjangan Belajar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2243, '898.2', 'Asrama', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2244, '898.3', 'Uang Makan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2245, '898.4', 'Uang Transport', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2246, '898.5', 'Uang Buku', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2247, '898.6', 'Uang Ujian', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2248, '898.7', 'Uang Semester / Uang Kuliah', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2249, '898.8', 'Uang Saku', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2250, '899', 'Sarana', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2251, '899.1', 'Bantuan Sarana Belajar', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2252, '899.2', 'Bantuan Alat-Alat Tulis', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2253, '899.3', 'Bantuan Sarana Belajar Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2254, '900', 'KEUANGAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2255, '901', 'Nota Keuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2256, '902', 'APBN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2257, '903', 'APBD', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2258, '904', 'APBN-P', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2259, '905', 'Dana Alokasi Umum', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2260, '906', 'Dana Alokasi Khusus', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2261, '907', 'Dekonsentrasi (Pelimpahan Dana Dari Pusat Ke Daerah)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2262, '907', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2263, '908', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2264, '910', 'ANGGARAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2265, '911', 'Rutin', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2266, '912', 'Pembangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2267, '913', 'Anggaran Belanja Tambahan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2268, '914', 'Daftar Isian Kegiatan (DIK)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2269, '914.1', 'Daftar Usulan Kegiatan (DUK)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2270, '915', 'Daftar Isian Poyek (DIP)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2271, '915.1', 'Daftar Usulan Proyek (DUP)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2272, '915.2', 'Daftar Isian Pengguna Anggaran (DIPA)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2273, '916', 'Revisi Anggaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2274, '917', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2275, '918', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2276, '920', 'OTORISASI / SKO', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2277, '921', 'Rutin', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2278, '922', 'Pembangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2279, '923', 'SIAP', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2280, '924', 'Ralat SKO', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2281, '925', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2282, '926', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2283, '927', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2284, '930', 'VERIFIKASI', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2285, '931', 'SPM Rutin (daftar p8)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2286, '932', 'SPM Pembangunan (daftar p8)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2287, '933', 'Penerimaan (daftar p6. p7)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2288, '934', 'SPJ Rutin', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2289, '935', 'SPJ Pembangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2290, '936', 'Nota Pemeriksaan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2291, '937', 'SP Pemindahan Pembukuan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2292, '938', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2293, '939', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2294, '940', 'PEMBUKUAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2295, '941', 'Penyusunan Perhitungan Anggaran', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2296, '942', 'Permintaan  Data Anggaran Laporan Fisik Pembangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2297, '943', 'Laporan Fisik Pembangunan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2298, '944', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2299, '945', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2300, '950', 'PERBENDAHARAAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2301, '951', 'Tuntutan Ganti Rugi (ICW Pasal 74)', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2302, '952', 'Tuntutan Bendaharawan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2303, '953', 'Penghapusan Kekayaan Negara', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2304, '954', 'Pengangkatan/Penggantian Pemimpin Proyak Dan Pengangkatan/Pemberhentian Bendaharawan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2305, '955', 'Spesimen Tanda Tangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2306, '956', 'Surat Tagihan Piutang, Ikhtisar Bulanan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2307, '957', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2308, '958', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2309, '959', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2310, '960', 'PEMBINAAN KEBENDAHARAAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2311, '961', 'Pemeriksaan Kas Dan Hasil Pemeriksaan Kas', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2312, '962', 'Pemeriksaan Administrasi Bendaharawan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2313, '963', 'Laporan Keuangan Bendaharawan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2314, '964', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2315, '965', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2316, '966', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2317, '970', 'PENDAPATAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2318, '971', 'Perimbangan Keuangan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2319, '972', 'Subsidi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2320, '973', 'Pajak,Ipeda, IHH,IHPH', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2321, '974', 'Retribusi', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2322, '975', 'Bea', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2323, '976', 'Cukai', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2324, '977', 'Pungutan / PNBP', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2325, '978', 'Bantuan Presiden, Menteri Dan Bantuan Lainnya', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2326, '979', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2327, '980', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2328, '981', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2329, '990', 'BENDAHARAWAN', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2330, '991', 'SKPP / SPP', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2331, '992', 'Teguran SPJ', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2332, '993', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2333, '994', '-', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES (2334, '995', '-', '-', 1);


#
# TABLE STRUCTURE FOR: komentar
#

DROP TABLE IF EXISTS `komentar`;

CREATE TABLE `komentar` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_artikel` int(7) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `komentar` text NOT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enabled` int(2) NOT NULL DEFAULT '2',
  `no_hp` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO `komentar` (`id`, `id_artikel`, `owner`, `email`, `komentar`, `tgl_upload`, `enabled`, `no_hp`) VALUES (8, 95, 'Penduduk Biasa', 'penduduk@desaku.desa.id', 'Selamat atas keberhasilan Senggigi merayakan Hari Kemerdeakaan 2016!', '2016-09-14 06:09:16', 1, NULL);
INSERT INTO `komentar` (`id`, `id_artikel`, `owner`, `email`, `komentar`, `tgl_upload`, `enabled`, `no_hp`) VALUES (9, 775, 'AHMAD ALLIF RIZKI', '5201140706966997', 'Harap alamat keluarga kami diperbaik menjadi RT 002 Dusun Mangsit. \n\nTerima kasih.', '2016-09-14 07:44:59', 1, NULL);
INSERT INTO `komentar` (`id`, `id_artikel`, `owner`, `email`, `komentar`, `tgl_upload`, `enabled`, `no_hp`) VALUES (10, 775, 'DENATUL SUARTINI', '3275014601977005', 'Saya ke kantor desa kemarin jam 12:30 siang, tetapi tidak ada orang. Anak kami akan pergi ke Yogyakarta untuk kuliah selama 4 tahun. Apakah perlu kami laporkan?', '2016-09-14 10:49:34', 2, NULL);
INSERT INTO `komentar` (`id`, `id_artikel`, `owner`, `email`, `komentar`, `tgl_upload`, `enabled`, `no_hp`) VALUES (11, 775, 'DENATUL SUARTINI', '3275014601977005', 'Laporan ini tidak relevan. Hanya berisi komentar saja.', '2016-09-14 11:05:02', 2, NULL);


#
# TABLE STRUCTURE FOR: kontak_grup
#

DROP TABLE IF EXISTS `kontak_grup`;

CREATE TABLE `kontak_grup` (
  `id_grup` int(11) NOT NULL AUTO_INCREMENT,
  `nama_grup` varchar(30) NOT NULL,
  PRIMARY KEY (`id_grup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: line
#

DROP TABLE IF EXISTS `line`;

CREATE TABLE `line` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT 'ff0000',
  `tipe` int(4) NOT NULL,
  `parrent` int(4) DEFAULT '1',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parrent` (`parrent`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

INSERT INTO `line` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (1, 'Jalan', '', '#FFCD42', 0, 1, 1);
INSERT INTO `line` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (2, 'Jalan Raya', '', '#FFCD42', 2, 1, 1);
INSERT INTO `line` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (3, 'Jalan Kampung', '', '', 2, 1, 1);
INSERT INTO `line` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (4, 'Ring Road', '', '', 2, 1, 1);
INSERT INTO `line` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (5, 'Sungai', '', '#FFFFFF', 0, 1, 1);
INSERT INTO `line` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (6, 'Selokan', '', '', 2, 5, 1);
INSERT INTO `line` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (7, 'Jalan setapak', '', '#d45dd6', 0, 1, 1);
INSERT INTO `line` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (8, 'Kali', '', '#16d958', 2, 5, 1);


#
# TABLE STRUCTURE FOR: log_bulanan
#

DROP TABLE IF EXISTS `log_bulanan`;

CREATE TABLE `log_bulanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pend` int(11) NOT NULL,
  `wni_lk` int(11) DEFAULT NULL,
  `wni_pr` int(11) DEFAULT NULL,
  `kk` int(11) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `kk_lk` int(11) DEFAULT NULL,
  `kk_pr` int(11) DEFAULT NULL,
  `wna_lk` int(11) DEFAULT NULL,
  `wna_pr` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (1, 97, 46, 51, 37, '2017-04-11 02:01:54', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (2, 97, 46, 51, 37, '2017-05-10 21:03:26', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (3, 97, 46, 51, 37, '2017-06-05 10:08:30', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (4, 97, 46, 51, 37, '2017-07-03 12:19:17', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (5, 97, 46, 51, 37, '2017-08-01 01:37:30', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (6, 97, 46, 51, 37, '2017-09-05 06:13:41', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (7, 97, 46, 51, 37, '2017-10-29 09:37:57', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (8, 97, 46, 51, 37, '2017-11-28 01:51:11', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (9, 97, 46, 51, 37, '2017-12-27 05:03:39', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (10, 97, 46, 51, 37, '2018-01-26 05:30:07', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (11, 97, 46, 51, 37, '2018-03-01 05:47:41', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (12, 97, 46, 51, 37, '2018-03-31 22:40:49', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (13, 97, 46, 51, 37, '2018-03-31 22:40:52', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (14, 97, 46, 51, 37, '2018-03-31 22:40:52', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (15, 97, 46, 51, 37, '2018-03-31 22:40:55', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (16, 97, 46, 51, 37, '2018-03-31 22:40:57', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (17, 97, 46, 51, 37, '2018-03-31 22:40:58', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (18, 97, 46, 51, 37, '2018-03-31 22:40:59', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (19, 97, 46, 51, 37, '2018-03-31 22:41:03', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (20, 97, 46, 51, 37, '2018-03-31 22:41:03', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (21, 97, 46, 51, 37, '2018-03-31 22:41:10', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (22, 97, 46, 51, 37, '2018-03-31 22:41:13', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (23, 97, 46, 51, 37, '2018-03-31 22:41:14', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (24, 97, 46, 51, 37, '2018-04-26 06:39:57', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (25, 97, 46, 51, 37, '2018-05-16 17:50:29', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (26, 97, 46, 51, 37, '2018-06-01 20:39:41', 28, 9, NULL, NULL);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (27, 97, 46, 51, 37, '2018-07-06 10:00:35', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (28, 97, 46, 51, 37, '2018-08-20 05:14:04', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (29, 97, 46, 51, 37, '2018-09-28 10:40:30', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (30, 97, 46, 51, 37, '2018-10-10 06:03:30', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (31, 97, 46, 51, 37, '2018-10-31 21:04:15', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (32, 97, 46, 51, 37, '2018-10-31 21:04:26', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (33, 97, 46, 51, 37, '2018-10-31 21:04:26', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (34, 97, 46, 51, 37, '2018-10-31 21:04:36', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (35, 97, 46, 51, 37, '2018-10-31 21:04:39', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (36, 97, 46, 51, 37, '2018-10-31 21:04:41', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (37, 97, 46, 51, 37, '2018-10-31 21:04:41', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (38, 97, 46, 51, 37, '2018-10-31 21:04:44', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (39, 97, 46, 51, 37, '2018-10-31 21:04:46', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (40, 97, 46, 51, 37, '2018-10-31 21:05:58', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (41, 97, 46, 51, 37, '2018-10-31 21:54:59', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (42, 97, 46, 51, 37, '2018-10-31 21:55:09', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (43, 97, 46, 51, 37, '2018-10-31 21:55:09', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (44, 97, 46, 51, 37, '2018-10-31 21:55:12', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (45, 97, 46, 51, 37, '2018-10-31 21:55:23', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (46, 97, 46, 51, 37, '2018-10-31 21:55:24', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (47, 97, 46, 51, 37, '2018-10-31 21:56:14', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (48, 97, 46, 51, 37, '2018-11-29 23:31:05', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (49, 97, 46, 51, 37, '2018-12-05 10:37:13', 28, 9, 0, 0);
INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES (50, 97, 46, 51, 37, '2019-01-01 00:43:26', 28, 9, 0, 0);


#
# TABLE STRUCTURE FOR: log_keluarga
#

DROP TABLE IF EXISTS `log_keluarga`;

CREATE TABLE `log_keluarga` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_kk` int(11) NOT NULL,
  `kk_sex` tinyint(2) DEFAULT NULL,
  `id_peristiwa` int(4) NOT NULL,
  `tgl_peristiwa` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_kk` (`id_kk`,`id_peristiwa`,`tgl_peristiwa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: log_penduduk
#

DROP TABLE IF EXISTS `log_penduduk`;

CREATE TABLE `log_penduduk` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_pend` int(11) NOT NULL,
  `id_detail` int(4) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bulan` varchar(2) NOT NULL,
  `tahun` varchar(4) NOT NULL,
  `tgl_peristiwa` date NOT NULL,
  `catatan` text,
  `no_kk` decimal(16,0) DEFAULT NULL,
  `nama_kk` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_pend` (`id_pend`,`id_detail`,`tgl_peristiwa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: log_perubahan_penduduk
#

DROP TABLE IF EXISTS `log_perubahan_penduduk`;

CREATE TABLE `log_perubahan_penduduk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pend` int(11) NOT NULL,
  `id_cluster` varchar(200) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: log_surat
#

DROP TABLE IF EXISTS `log_surat`;

CREATE TABLE `log_surat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_format_surat` int(3) NOT NULL,
  `id_pend` int(11) DEFAULT NULL,
  `id_pamong` int(4) NOT NULL,
  `id_user` int(4) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bulan` varchar(2) DEFAULT NULL,
  `tahun` varchar(4) DEFAULT NULL,
  `no_surat` varchar(20) DEFAULT NULL,
  `nama_surat` varchar(100) DEFAULT NULL,
  `lampiran` varchar(100) DEFAULT NULL,
  `nik_non_warga` decimal(16,0) DEFAULT NULL,
  `nama_non_warga` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `log_surat` (`id`, `id_format_surat`, `id_pend`, `id_pamong`, `id_user`, `tanggal`, `bulan`, `tahun`, `no_surat`, `nama_surat`, `lampiran`, `nik_non_warga`, `nama_non_warga`) VALUES (6, 89, 1, 14, 1, '2017-12-31 22:26:52', '12', '2017', '234', 'surat_permohonan_perubahan_kartu_keluarga_5201142005716996_2017-12-31_234.rtf', 'surat_permohonan_perubahan_kartu_keluarga_5201142005716996_2017-12-31_234_lampiran.pdf', NULL, NULL);


#
# TABLE STRUCTURE FOR: lokasi
#

DROP TABLE IF EXISTS `lokasi`;

CREATE TABLE `lokasi` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `desk` text NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  `lat` varchar(30) NOT NULL,
  `lng` varchar(30) NOT NULL,
  `ref_point` int(9) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `id_cluster` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ref_point` (`ref_point`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `lokasi` (`id`, `desk`, `nama`, `enabled`, `lat`, `lng`, `ref_point`, `foto`, `id_cluster`) VALUES (1, 'Sekolah Menengah Pertama', 'SMP', 1, '-8.49563254042209', '116.04755401611328', 5, '', 0);
INSERT INTO `lokasi` (`id`, `desk`, `nama`, `enabled`, `lat`, `lng`, `ref_point`, `foto`, `id_cluster`) VALUES (2, 'Sekolah Menengah Atas', 'SMA', 1, '-8.485106175017545', '116.04549407958986', 4, '', 0);
INSERT INTO `lokasi` (`id`, `desk`, `nama`, `enabled`, `lat`, `lng`, `ref_point`, `foto`, `id_cluster`) VALUES (3, 'Sarana Pendidikan', 'Puskesmas Husada', 1, '-8.478145032940077', '116.0394859313965', 9, '', 0);


#
# TABLE STRUCTURE FOR: media_sosial
#

DROP TABLE IF EXISTS `media_sosial`;

CREATE TABLE `media_sosial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gambar` text NOT NULL,
  `link` text NOT NULL,
  `nama` varchar(100) NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `media_sosial` (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES (1, 'fb.png', 'https://www.facebook.com/groups/OpenSID/', 'Facebook', 1);
INSERT INTO `media_sosial` (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES (2, 'twt.png', '', 'Twitter', 1);
INSERT INTO `media_sosial` (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES (3, 'goo.png', '', 'Google Plus', 1);
INSERT INTO `media_sosial` (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES (4, 'yb.png', '', 'YouTube', 1);
INSERT INTO `media_sosial` (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES (5, 'ins.png', '', 'Instagram', 1);


#
# TABLE STRUCTURE FOR: menu
#

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `link` varchar(500) NOT NULL,
  `tipe` int(4) NOT NULL,
  `parrent` int(4) NOT NULL DEFAULT '1',
  `link_tipe` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` int(11) NOT NULL DEFAULT '1',
  `urut` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;

INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (16, 'Profil Desa', 'artikel/32', 1, 1, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (17, 'Pemerintahan Desa', 'artikel/85', 1, 1, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (19, 'Lembaga Masyarakat', 'artikel/38', 1, 1, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (23, 'Teras Desa', '', 2, 1, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (24, 'Data Desa', 'artikel/97', 1, 1, 0, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (31, 'Data Wilayah Administratif', 'wilayah', 3, 24, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (32, 'Data Pendidikan dalam KK', 'statistik/0', 3, 24, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (33, 'Data Pendidikan Ditempuh', 'statistik/14', 3, 24, 0, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (34, 'Data Pekerjaan', 'statistik/1', 3, 24, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (35, 'Data Agama', 'statistik/3', 3, 24, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (36, 'Data Jenis Kelamin', 'statistik/4', 3, 24, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (40, 'Data Golongan Darah', 'statistik/7', 3, 24, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (51, 'Data Kelompok Umur', 'statistik/12', 3, 24, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (52, 'Data Penerima Raskin', 'statistik_k/2', 3, 24, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (53, 'Data Penerima Jamkesmas', 'statistik_k/3', 3, 24, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (55, 'Profil Wilayah Desa', 'artikel/33', 3, 16, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (56, 'Profil Masyarakat Desa', 'artikel/34', 3, 16, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (57, 'Visi dan Misi', 'artikel/93', 3, 17, 0, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (58, 'Pemerintah Desa', 'artikel/92', 3, 17, 0, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (59, 'Badan Permusyawaratan Desa', 'artikel/37', 3, 17, 0, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (62, 'Berita Desa', '', 2, 1, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (63, 'Agenda Desa', 'artikel/41', 2, 1, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (64, 'Peraturan Desa', 'peraturan', 2, 1, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (65, 'Panduan Layanan Desa', '#', 2, 1, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (66, 'Produk Desa', 'produk', 2, 1, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (68, 'Undang undang', 'artikel/42', 3, 64, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (69, 'Peraturan Pemerintah', 'artikel/43', 3, 64, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (70, 'Peraturan Daerah', '', 3, 64, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (71, 'Peraturan Bupati', '', 3, 64, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (72, 'Peraturan Bersama KaDes', '', 3, 64, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (73, 'Informasi Publik', '#', 2, 1, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (75, 'Rencana Kerja Anggaran', '', 3, 73, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (76, 'RAPB Desa', '', 3, 73, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (77, 'APB Desa', '', 3, 73, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (78, 'DPA', '', 3, 73, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (80, 'Profil Potensi Desa', 'artikel/59', 3, 16, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (84, 'LKMD', 'artikel/62', 3, 18, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (85, 'PKK', 'artikel/63', 3, 18, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (86, 'Karang Taruna', 'artikel/64', 3, 18, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (87, 'RT RW', 'artikel/65', 3, 18, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (88, 'Linmas', 'artikel/70', 3, 18, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (89, 'TKP2KDes', 'artikel/66', 3, 18, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (90, 'KPAD', 'artikel/67', 3, 18, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (91, 'Kelompok Ternak', 'artikel/68', 3, 18, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (92, 'Kelompok Tani', 'artikel/69', 3, 18, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (93, 'Kelompok Ekonomi Lainya', 'artikel/71', 3, 18, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (98, 'LKPJ', '', 3, 73, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (99, 'LPPD', '', 3, 73, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (100, 'ILPPD', '', 3, 73, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (101, 'Peraturan Desa', 'artikel/44', 3, 64, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (102, 'Peraturan Kepala Desa', 'artikel/45', 3, 64, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (103, 'Keputusan Kepala Desa', 'artikel/46', 3, 64, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (104, 'PBB', '', 3, 73, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (106, 'Data Warga Negara', 'statistik/13', 3, 24, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (108, 'Data Kelas Sosial', 'statistik_k/1', 3, 24, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (109, 'Kontak', 'artikel/36', 1, 1, 1, 2, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (110, 'Peraturan Desa', 'peraturan', 3, 66, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (112, 'Coba', 'coba', 2, 1, 1, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (113, '', '', 3, 109, 0, 1, NULL);
INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES (114, 'Sejarah Desa', 'artikel/99', 3, 16, 0, 1, NULL);


#
# TABLE STRUCTURE FOR: outbox
#

DROP TABLE IF EXISTS `outbox`;

CREATE TABLE `outbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `InsertIntoDB` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SendingDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SendBefore` time NOT NULL DEFAULT '23:59:59',
  `SendAfter` time NOT NULL DEFAULT '00:00:00',
  `Text` text,
  `DestinationNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text,
  `Class` int(11) DEFAULT '-1',
  `TextDecoded` text NOT NULL,
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MultiPart` enum('false','true') DEFAULT 'false',
  `RelativeValidity` int(11) DEFAULT '-1',
  `SenderID` varchar(255) DEFAULT NULL,
  `SendingTimeOut` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `DeliveryReport` enum('default','yes','no') DEFAULT 'default',
  `CreatorID` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `outbox_date` (`SendingDateTime`,`SendingTimeOut`),
  KEY `outbox_sender` (`SenderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: pertanyaan
#

DROP TABLE IF EXISTS `pertanyaan`;

CREATE TABLE `pertanyaan` (
  `1` int(2) DEFAULT NULL,
  `Pendapatan perkapita perbulan` varchar(87) DEFAULT NULL,
  `36` int(2) DEFAULT NULL,
  `15` int(2) DEFAULT NULL,
  `24` int(2) DEFAULT NULL,
  `23` int(2) DEFAULT NULL,
  `26` int(2) DEFAULT NULL,
  `28` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: point
#

DROP TABLE IF EXISTS `point`;

CREATE TABLE `point` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `tipe` int(4) NOT NULL,
  `parrent` int(4) NOT NULL DEFAULT '1',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parrent` (`parrent`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES (1, 'Sarana Pendidikan', 'face-embarrassed.png', 0, 1, 1);
INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES (2, 'Sarana Transportasi', 'face-devilish.png', 0, 1, 1);
INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES (3, 'Sarana Kesehatan', 'emblem-photos.png', 0, 1, 1);
INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES (4, 'SMA', 'gateswalls.png', 2, 1, 1);
INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES (5, 'SMP (Sekolah Menengah Pertama)', 'arch.png', 2, 1, 1);
INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES (6, 'Masjid', 'mosque.png', 2, 7, 1);
INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES (7, 'Tempat Ibadah', 'emblem-art.png', 0, 1, 1);
INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES (8, 'Kuil', 'moderntower.png', 2, 7, 1);
INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES (9, 'RS', 'accerciser.png', 2, 3, 1);


#
# TABLE STRUCTURE FOR: polygon
#

DROP TABLE IF EXISTS `polygon`;

CREATE TABLE `polygon` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT 'ff0000',
  `tipe` int(4) NOT NULL,
  `parrent` int(4) DEFAULT '1',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parrent` (`parrent`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `polygon` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (1, 'rawan topan', '', '#7C78FF', 0, 1, 1);
INSERT INTO `polygon` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (2, 'jalur selokan', '', '#F4FF59', 0, 1, 1);
INSERT INTO `polygon` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES (3, 'Pemukiman rawan topan', '', '#db2121', 2, 1, 1);


#
# TABLE STRUCTURE FOR: program
#

DROP TABLE IF EXISTS `program`;

CREATE TABLE `program` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `ndesc` varchar(200) DEFAULT NULL,
  `sdate` date NOT NULL,
  `edate` date NOT NULL,
  `userid` mediumint(9) NOT NULL,
  `status` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO `program` (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`) VALUES (1, 'Raskin', 2, '', '2015-12-13', '2017-12-13', 0, NULL);
INSERT INTO `program` (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`) VALUES (2, 'BLSM', 2, '', '2015-12-13', '2017-12-13', 0, NULL);
INSERT INTO `program` (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`) VALUES (3, 'PKH', 2, '', '2015-12-13', '2017-12-13', 0, NULL);
INSERT INTO `program` (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`) VALUES (4, 'Bedah Rumah', 2, '', '2015-12-13', '2017-12-13', 0, NULL);
INSERT INTO `program` (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`) VALUES (5, 'JAMKESMAS', 1, '', '2015-12-13', '2017-12-13', 0, NULL);


#
# TABLE STRUCTURE FOR: program_peserta
#

DROP TABLE IF EXISTS `program_peserta`;

CREATE TABLE `program_peserta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peserta` decimal(16,0) NOT NULL,
  `program_id` int(11) NOT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `no_id_kartu` varchar(30) DEFAULT NULL,
  `kartu_nik` decimal(16,0) DEFAULT NULL,
  `kartu_nama` varchar(100) DEFAULT NULL,
  `kartu_tempat_lahir` varchar(100) DEFAULT NULL,
  `kartu_tanggal_lahir` date DEFAULT NULL,
  `kartu_alamat` varchar(200) DEFAULT NULL,
  `kartu_peserta` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES (2, '5201140104126994', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES (3, '5201140105136997', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES (4, '5201140104126995', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES (5, '5201140105136997', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES (6, '5201140104126995', 3, 2, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES (7, '5201140105136997', 3, 2, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES (8, '5201140104166999', 4, 2, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES (9, '5201140105136997', 4, 2, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES (10, '5201142005716996', 5, 2, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES (11, '5201140706966997', 5, 2, NULL, NULL, NULL, NULL, NULL, NULL, '');


#
# TABLE STRUCTURE FOR: provinsi
#

DROP TABLE IF EXISTS `provinsi`;

CREATE TABLE `provinsi` (
  `kode` tinyint(2) NOT NULL DEFAULT '0',
  `nama` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `provinsi` (`kode`, `nama`) VALUES (11, 'Aceh');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (12, 'Sumatera Utara');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (13, 'Sumatera Barat');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (14, 'Riau');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (15, 'Jambi');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (16, 'Sumatera Selatan');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (17, 'Bengkulu');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (18, 'Lampung');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (19, 'Kepulauan Bangka Belitung');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (21, 'Kepulauan Riau');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (31, 'DKI Jakarta');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (32, 'Jawa Barat');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (33, 'Jawa Tengah');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (34, 'DI Yogyakarta');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (35, 'Jawa Timur');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (36, 'Banten');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (51, 'Bali');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (52, 'Nusa Tenggara Barat');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (53, 'Nusa Tenggara Timur');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (61, 'Kalimantan Barat');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (62, 'Kalimantan Tengah');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (63, 'Kalimantan Selatan');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (64, 'Kalimantan Timur');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (65, 'Kalimantan Utara');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (71, 'Sulawesi Utara');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (72, 'Sulawesi Tengah');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (73, 'Sulawesi Selatan');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (74, 'Sulawesi Tenggara');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (75, 'Gorontalo');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (76, 'Sulawesi Barat');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (81, 'Maluku');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (82, 'Maluku Utara');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (91, 'Papua');
INSERT INTO `provinsi` (`kode`, `nama`) VALUES (92, 'Papua Barat');


#
# TABLE STRUCTURE FOR: sentitems
#

DROP TABLE IF EXISTS `sentitems`;

CREATE TABLE `sentitems` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `InsertIntoDB` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SendingDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DeliveryDateTime` timestamp NULL DEFAULT NULL,
  `Text` text NOT NULL,
  `DestinationNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text NOT NULL,
  `SMSCNumber` varchar(20) NOT NULL DEFAULT '',
  `Class` int(11) NOT NULL DEFAULT '-1',
  `TextDecoded` text NOT NULL,
  `ID` int(10) unsigned NOT NULL DEFAULT '0',
  `SenderID` varchar(255) NOT NULL,
  `SequencePosition` int(11) NOT NULL DEFAULT '1',
  `Status` enum('SendingOK','SendingOKNoReport','SendingError','DeliveryOK','DeliveryFailed','DeliveryPending','DeliveryUnknown','Error') NOT NULL DEFAULT 'SendingOK',
  `StatusError` int(11) NOT NULL DEFAULT '-1',
  `TPMR` int(11) NOT NULL DEFAULT '-1',
  `RelativeValidity` int(11) NOT NULL DEFAULT '-1',
  `CreatorID` text NOT NULL,
  PRIMARY KEY (`ID`,`SequencePosition`),
  KEY `sentitems_date` (`DeliveryDateTime`),
  KEY `sentitems_tpmr` (`TPMR`),
  KEY `sentitems_dest` (`DestinationNumber`),
  KEY `sentitems_sender` (`SenderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: setting_aplikasi
#

DROP TABLE IF EXISTS `setting_aplikasi`;

CREATE TABLE `setting_aplikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `jenis` varchar(30) DEFAULT NULL,
  `kategori` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (1, 'sebutan_kabupaten', 'kabupaten', 'Pengganti sebutan wilayah kabupaten', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (2, 'sebutan_kabupaten_singkat', 'kab.', 'Pengganti sebutan singkatan wilayah kabupaten', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (3, 'sebutan_kecamatan', 'kecamatan', 'Pengganti sebutan wilayah kecamatan', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (4, 'sebutan_kecamatan_singkat', 'kec.', 'Pengganti sebutan singkatan wilayah kecamatan', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (5, 'sebutan_desa', 'desa', 'Pengganti sebutan wilayah desa', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (6, 'sebutan_dusun', 'dusun', 'Pengganti sebutan wilayah dusun', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (7, 'sebutan_camat', 'camat', 'Pengganti sebutan jabatan camat', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (8, 'website_title', 'Website Resmi', 'Judul tab browser modul web', '', 'web');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (9, 'login_title', 'OpenSID', 'Judul tab browser halaman login modul administrasi', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (10, 'admin_title', 'Sistem Informasi Desa', 'Judul tab browser modul administrasi', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (11, 'web_theme', 'default', 'Tema penampilan modul web', '', 'web');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (12, 'offline_mode', '0', 'Apakah modul web akan ditampilkan atau tidak', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (13, 'enable_track', '1', 'Apakah akan mengirimkan data statistik ke tracker', 'boolean', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (14, 'dev_tracker', '', 'Host untuk tracker pada development', '', 'development');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (16, 'google_key', '', 'Google API Key untuk Google Maps', '', 'web');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (17, 'libreoffice_path', '', 'Path tempat instal libreoffice di server SID', '', '');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (18, 'sumber_gambar_slider', '1', 'Sumber gambar slider besar', NULL, NULL);
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (19, 'sebutan_singkatan_kadus', 'kawil', 'Sebutan singkatan jabatan kepala dusun', NULL, NULL);
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (20, 'current_version', '19.01', 'Versi sekarang untuk migrasi', NULL, NULL);
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (21, 'timezone', 'Asia/Jakarta', 'Zona waktu perekaman waktu dan tanggal', NULL, NULL);
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (22, 'tombol_cetak_surat', '0', 'Tampilkan tombol cetak langsung di form surat', 'boolean', NULL);
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (23, 'web_artikel_per_page', '8', 'Jumlah artikel dalam satu halaman', 'int', 'web_theme');
INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES (24, 'penomoran_surat', '2', 'Penomoran surat mulai dari satu (1) setiap tahun', 'option', NULL);


#
# TABLE STRUCTURE FOR: setting_modul
#

DROP TABLE IF EXISTS `setting_modul`;

CREATE TABLE `setting_modul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modul` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  `ikon` varchar(50) NOT NULL,
  `urut` tinyint(4) NOT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '2',
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `ikon_kecil` varchar(50) NOT NULL,
  `parent` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8;

INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (1, 'Home', 'hom_sid', 1, 'fa-home', 1, 2, 1, 'fa fa-home', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (2, 'Kependudukan', 'penduduk/clear', 1, 'fa-users', 3, 2, 0, 'fa fa-users', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (3, 'Statistik', 'statistik', 1, 'fa-line-chart', 4, 2, 0, 'fa fa-line-chart', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (4, 'Layanan Surat', 'surat', 1, 'fa-book', 5, 2, 0, 'fa fa-book', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (5, 'Analisis', 'analisis_master/clear', 1, '   fa-check-square-o', 6, 2, 0, 'fa fa-check-square-o', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (6, 'Bantuan', 'program_bantuan/clear', 1, 'fa-heart', 7, 2, 0, 'fa fa-heart', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (7, 'Pertanahan', 'data_persil/clear', 1, 'fa-map-signs', 8, 2, 0, 'fa fa-map-signs', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (8, 'Pengaturan Peta', 'plan', 1, 'fa-location-arrow', 9, 2, 0, 'fa fa-location-arrow', 9);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (9, 'Pemetaan', 'gis', 1, 'fa-globe', 10, 2, 0, 'fa fa-globe', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (10, 'SMS', 'sms', 1, 'fa-envelope', 11, 2, 0, 'fa fa-envelope', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (11, 'Pengaturan', 'man_user/clear', 1, 'fa-users', 12, 1, 1, 'fa-users', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (13, 'Admin Web', 'web', 1, 'fa-desktop', 14, 4, 0, 'fa fa-desktop', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (14, 'Layanan Mandiri', 'lapor', 1, 'fa-inbox', 15, 2, 0, 'fa fa-inbox', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (15, 'Sekretariat', 'sekretariat', 1, 'fa-archive', 5, 2, 0, 'fa fa-archive', 0);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (16, 'SID', 'hom_sid', 1, 'fa-globe', 1, 2, 0, '', 1);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (17, 'Identitas [Desa]', 'hom_desa/konfigurasi', 1, 'fa-id-card', 2, 2, 0, '', 200);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (18, 'Pemerintahan [Desa]', 'pengurus/clear', 1, 'fa-sitemap', 3, 2, 0, '', 200);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (19, 'Donasi', 'hom_sid/donasi', 1, 'fa-money', 4, 2, 0, '', 1);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (20, 'Wilayah Administratif', 'sid_core/clear', 1, 'fa-map', 2, 2, 0, '', 200);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (21, 'Penduduk', 'penduduk/clear', 1, 'fa-user', 2, 2, 0, '', 2);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (22, 'Keluarga', 'keluarga/clear', 1, 'fa-users', 3, 2, 0, '', 2);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (23, 'Rumah Tangga', 'rtm/clear', 1, 'fa-venus-mars', 4, 2, 0, '', 2);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (24, 'Kelompok', 'kelompok/clear', 1, 'fa-sitemap', 5, 2, 0, '', 2);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (25, 'Data Suplemen', 'suplemen', 1, 'fa-slideshare', 6, 2, 0, '', 2);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (26, 'Calon Pemilih', 'dpt/clear', 1, 'fa-podcast', 7, 2, 0, '', 2);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (27, 'Statistik Kependudukan', 'statistik', 1, 'fa-bar-chart', 1, 2, 0, '', 3);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (28, 'Laporan Bulanan', 'laporan/clear', 1, 'fa-file-text', 2, 2, 0, '', 3);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (29, 'Laporan Kelompok Rentan', 'laporan_rentan/clear', 1, 'fa-wheelchair', 3, 2, 0, '', 3);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (30, 'Pengaturan Surat', 'surat_master/clear', 1, 'fa-cog', 1, 2, 0, '', 4);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (31, 'Cetak Surat', 'surat', 1, 'fa-files-o', 2, 2, 0, '', 4);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (32, 'Arsip Layanan', 'keluar/clear', 1, 'fa-folder-open', 3, 2, 0, '', 4);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (33, 'Panduan', 'surat/panduan', 1, 'fa fa-book', 4, 2, 0, '', 4);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (39, 'SMS', 'sms', 1, 'fa-envelope-open-o', 1, 2, 0, '', 10);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (40, 'Daftar Kontak', 'sms/kontak', 1, 'fa-id-card-o', 2, 2, 0, '', 10);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (41, 'Pengaturan SMS', 'sms/setting', 1, 'fa-gear', 3, 2, 0, '', 10);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (42, 'Modul', 'modul/clear', 1, 'fa-tags', 1, 1, 0, '', 11);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (43, 'Aplikasi', 'setting', 1, 'fa-codepen', 2, 1, 0, '', 11);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (44, 'Pengguna', 'man_user', 1, 'fa-users', 3, 1, 0, '', 11);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (45, 'Database', 'database', 1, 'fa-database', 4, 1, 0, '', 11);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (46, 'Info Sistem', 'setting/info_sistem', 1, 'fa-server', 5, 1, 0, '', 11);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (47, 'Artikel', 'web/clear', 1, 'fa-file-movie-o', 1, 4, 0, '', 13);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (48, 'Widget', 'web_widget/clear', 1, 'fa-windows', 2, 4, 0, '', 13);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (49, 'Menu', 'menu/clear', 1, 'fa-bars', 3, 4, 0, '', 13);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (50, 'Komentar', 'komentar/clear', 1, 'fa-comments', 4, 4, 0, '', 13);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (51, 'Galeri', 'gallery', 1, 'fa-image', 5, 5, 0, '', 13);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (52, 'Dokumen', 'dokumen/clear', 1, 'fa-file-text', 6, 4, 0, '', 13);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (53, 'Media Sosial', 'sosmed', 1, 'fa-facebook', 7, 4, 0, '', 13);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (54, 'Slider', 'web/slider', 1, 'fa-film', 8, 4, 0, '', 13);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (55, 'Laporan Masuk', 'lapor', 1, 'fa-wechat', 1, 2, 0, '', 14);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (56, 'Pendaftar Layanan Mandiri', 'mandiri/clear', 1, 'fa-500px', 2, 2, 0, '', 14);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (57, 'Surat Masuk', 'surat_masuk/clear', 1, 'fa-sign-in', 1, 2, 0, '', 15);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (58, 'Surat Keluar', 'surat_keluar/clear', 1, 'fa-sign-out', 2, 2, 0, '', 15);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (59, 'SK Kades', 'dokumen_sekretariat/index/2', 1, 'fa-legal', 3, 2, 0, '', 15);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (60, 'Perdes', 'dokumen_sekretariat/index/3', 1, 'fa-newspaper-o', 4, 2, 0, '', 15);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (61, 'Inventaris', 'inventaris_tanah', 1, 'fa-cubes', 5, 2, 0, '', 15);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (62, 'Peta', 'gis', 1, 'fa-globe', 1, 2, 0, 'fa fa-globe', 9);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (63, 'Klasfikasi Surat', 'klasifikasi/clear', 1, 'fa-code', 10, 2, 0, 'fa-code', 15);
INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES (200, 'Info [Desa]', 'hom_desa', 1, 'fa-dashboard', 2, 2, 1, 'fa fa-home', 0);


#
# TABLE STRUCTURE FOR: setting_sms
#

DROP TABLE IF EXISTS `setting_sms`;

CREATE TABLE `setting_sms` (
  `autoreply_text` varchar(160) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `setting_sms` (`autoreply_text`) VALUES ('Terima kasih pesan Anda telah kami terima.');


#
# TABLE STRUCTURE FOR: suplemen
#

DROP TABLE IF EXISTS `suplemen`;

CREATE TABLE `suplemen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `keterangan` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: surat_keluar
#

DROP TABLE IF EXISTS `surat_keluar`;

CREATE TABLE `surat_keluar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_urut` smallint(5) DEFAULT NULL,
  `nomor_surat` varchar(20) DEFAULT NULL,
  `kode_surat` varchar(10) DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `tanggal_catat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tujuan` varchar(100) DEFAULT NULL,
  `isi_singkat` varchar(200) DEFAULT NULL,
  `berkas_scan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: surat_masuk
#

DROP TABLE IF EXISTS `surat_masuk`;

CREATE TABLE `surat_masuk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_urut` smallint(5) DEFAULT NULL,
  `tanggal_penerimaan` date NOT NULL,
  `nomor_surat` varchar(20) DEFAULT NULL,
  `kode_surat` varchar(10) DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `pengirim` varchar(100) DEFAULT NULL,
  `isi_singkat` varchar(200) DEFAULT NULL,
  `isi_disposisi` varchar(200) DEFAULT NULL,
  `berkas_scan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: sys_traffic
#

DROP TABLE IF EXISTS `sys_traffic`;

CREATE TABLE `sys_traffic` (
  `Tanggal` date NOT NULL,
  `ipAddress` text NOT NULL,
  `Jumlah` int(10) NOT NULL,
  PRIMARY KEY (`Tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-11-15', '::1{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-11-16', '::1{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-11-18', '', 3);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-11-21', '', 3);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-11-26', '::1{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-03', '127.0.0.1{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-04', '', 3);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-05', '', 5);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-06', '127.0.0.1{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-08', '127.0.0.1{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-09', '127.0.0.1{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-10', '', 3);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-25', '', 2);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-26', '', 4);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-27', '', 2);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-28', '10.0.2.2{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-29', '', 2);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-30', '10.0.2.2{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-31', '', 3);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-06-01', '10.0.2.2{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-23', '', 6);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-24', '', 7);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-26', '', 8);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-27', '192.168.1.66{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-28', '', 3);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-29', '', 2);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-30', '', 3);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-31', '127.0.0.1{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-02', '', 4);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-03', '', 2);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-04', '10.0.2.2{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-05', '', 2);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-07', '', 2);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-08', '10.0.2.2{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-09', '', 4);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-10', '', 4);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-11', '', 2);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-14', '', 4);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2017-07-16', '10.0.2.2{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2018-05-28', '', 3);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2018-05-29', '10.0.2.2{}', 1);
INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2018-11-30', '192.168.33.1{}', 1);


#
# TABLE STRUCTURE FOR: tweb_cacat
#

DROP TABLE IF EXISTS `tweb_cacat`;

CREATE TABLE `tweb_cacat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `tweb_cacat` (`id`, `nama`) VALUES (1, 'CACAT FISIK');
INSERT INTO `tweb_cacat` (`id`, `nama`) VALUES (2, 'CACAT NETRA/BUTA');
INSERT INTO `tweb_cacat` (`id`, `nama`) VALUES (3, 'CACAT RUNGU/WICARA');
INSERT INTO `tweb_cacat` (`id`, `nama`) VALUES (4, 'CACAT MENTAL/JIWA');
INSERT INTO `tweb_cacat` (`id`, `nama`) VALUES (5, 'CACAT FISIK DAN MENTAL');
INSERT INTO `tweb_cacat` (`id`, `nama`) VALUES (6, 'CACAT LAINNYA');
INSERT INTO `tweb_cacat` (`id`, `nama`) VALUES (7, 'TIDAK CACAT');


#
# TABLE STRUCTURE FOR: tweb_cara_kb
#

DROP TABLE IF EXISTS `tweb_cara_kb`;

CREATE TABLE `tweb_cara_kb` (
  `id` tinyint(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `sex` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_cara_kb` (`id`, `nama`, `sex`) VALUES (1, 'Pil', 2);
INSERT INTO `tweb_cara_kb` (`id`, `nama`, `sex`) VALUES (2, 'IUD', 2);
INSERT INTO `tweb_cara_kb` (`id`, `nama`, `sex`) VALUES (3, 'Suntik', 2);
INSERT INTO `tweb_cara_kb` (`id`, `nama`, `sex`) VALUES (4, 'Kondom', 1);
INSERT INTO `tweb_cara_kb` (`id`, `nama`, `sex`) VALUES (5, 'Susuk KB', 2);
INSERT INTO `tweb_cara_kb` (`id`, `nama`, `sex`) VALUES (6, 'Sterilisasi Wanita', 2);
INSERT INTO `tweb_cara_kb` (`id`, `nama`, `sex`) VALUES (7, 'Sterilisasi Pria', 1);
INSERT INTO `tweb_cara_kb` (`id`, `nama`, `sex`) VALUES (99, 'Lainnya', 3);


#
# TABLE STRUCTURE FOR: tweb_desa_pamong
#

DROP TABLE IF EXISTS `tweb_desa_pamong`;

CREATE TABLE `tweb_desa_pamong` (
  `pamong_id` int(5) NOT NULL AUTO_INCREMENT,
  `pamong_nama` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_nip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_nik` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `jabatan` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0',
  `pamong_status` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_tgl_terdaftar` date DEFAULT NULL,
  `pamong_ttd` tinyint(1) DEFAULT NULL,
  `foto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id_pend` int(11) DEFAULT NULL,
  `pamong_tempatlahir` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_tanggallahir` date DEFAULT NULL,
  `pamong_sex` tinyint(4) DEFAULT NULL,
  `pamong_pendidikan` int(10) DEFAULT NULL,
  `pamong_agama` int(10) DEFAULT NULL,
  `pamong_nosk` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_tglsk` date DEFAULT NULL,
  `pamong_masajab` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `urut` int(5) DEFAULT NULL,
  `pamong_niap` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_pangkat` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_nohenti` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_tglhenti` date DEFAULT NULL,
  PRIMARY KEY (`pamong_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tweb_desa_pamong` (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`, `id_pend`, `pamong_tempatlahir`, `pamong_tanggallahir`, `pamong_sex`, `pamong_pendidikan`, `pamong_agama`, `pamong_nosk`, `pamong_tglsk`, `pamong_masajab`, `urut`, `pamong_niap`, `pamong_pangkat`, `pamong_nohenti`, `pamong_tglhenti`) VALUES (14, 'Muhammad Ilham ', '', '', 'Kepala Desa', '1', '2014-04-20', 1, 'CjR9Xl_kades.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tweb_desa_pamong` (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`, `id_pend`, `pamong_tempatlahir`, `pamong_tanggallahir`, `pamong_sex`, `pamong_pendidikan`, `pamong_agama`, `pamong_nosk`, `pamong_tglsk`, `pamong_masajab`, `urut`, `pamong_niap`, `pamong_pangkat`, `pamong_nohenti`, `pamong_tglhenti`) VALUES (20, 'Mustahiq S.Adm', '197905062010011007', '5201140506790001', 'Sekretaris Desa', '1', '2016-08-23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tweb_desa_pamong` (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`, `id_pend`, `pamong_tempatlahir`, `pamong_tanggallahir`, `pamong_sex`, `pamong_pendidikan`, `pamong_agama`, `pamong_nosk`, `pamong_tglsk`, `pamong_masajab`, `urut`, `pamong_niap`, `pamong_pangkat`, `pamong_nohenti`, `pamong_tglhenti`) VALUES (21, 'Syafruddin ', '-', '5201140911720004', 'Kaur Pemerintahan ', '1', '2016-08-23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tweb_desa_pamong` (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`, `id_pend`, `pamong_tempatlahir`, `pamong_tanggallahir`, `pamong_sex`, `pamong_pendidikan`, `pamong_agama`, `pamong_nosk`, `pamong_tglsk`, `pamong_masajab`, `urut`, `pamong_niap`, `pamong_pangkat`, `pamong_nohenti`, `pamong_tglhenti`) VALUES (22, 'Supardi Rustam ', '-', '5201140101710003', 'Kaur Umum ', '1', '2016-08-23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tweb_desa_pamong` (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`, `id_pend`, `pamong_tempatlahir`, `pamong_tanggallahir`, `pamong_sex`, `pamong_pendidikan`, `pamong_agama`, `pamong_nosk`, `pamong_tglsk`, `pamong_masajab`, `urut`, `pamong_niap`, `pamong_pangkat`, `pamong_nohenti`, `pamong_tglhenti`) VALUES (23, 'Mardiana ', '-', '5201145203810001', 'Kaur Keuangan', '1', '2016-08-23', NULL, 'cNzva0_bendahara.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tweb_desa_pamong` (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`, `id_pend`, `pamong_tempatlahir`, `pamong_tanggallahir`, `pamong_sex`, `pamong_pendidikan`, `pamong_agama`, `pamong_nosk`, `pamong_tglsk`, `pamong_masajab`, `urut`, `pamong_niap`, `pamong_pangkat`, `pamong_nohenti`, `pamong_tglhenti`) VALUES (24, 'Syafi-i. SE ', '-', '5201140506730002', 'Kaur Pembangunan ', '1', '2016-08-23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tweb_desa_pamong` (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`, `id_pend`, `pamong_tempatlahir`, `pamong_tanggallahir`, `pamong_sex`, `pamong_pendidikan`, `pamong_agama`, `pamong_nosk`, `pamong_tglsk`, `pamong_masajab`, `urut`, `pamong_niap`, `pamong_pangkat`, `pamong_nohenti`, `pamong_tglhenti`) VALUES (25, 'Mahrup ', '', '', 'Kaur Keamanan dan Ketertiban', '1', '2016-08-23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);


#
# TABLE STRUCTURE FOR: tweb_golongan_darah
#

DROP TABLE IF EXISTS `tweb_golongan_darah`;

CREATE TABLE `tweb_golongan_darah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (1, 'A');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (2, 'B');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (3, 'AB');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (4, 'O');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (5, 'A+');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (6, 'A-');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (7, 'B+');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (8, 'B-');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (9, 'AB+');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (10, 'AB-');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (11, 'O+');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (12, 'O-');
INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES (13, 'TIDAK TAHU');


#
# TABLE STRUCTURE FOR: tweb_keluarga
#

DROP TABLE IF EXISTS `tweb_keluarga`;

CREATE TABLE `tweb_keluarga` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `no_kk` varchar(160) DEFAULT NULL,
  `nik_kepala` varchar(200) DEFAULT NULL,
  `tgl_daftar` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `kelas_sosial` int(4) DEFAULT NULL,
  `tgl_cetak_kk` datetime DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `id_cluster` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nik_kepala` (`nik_kepala`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (1, '5201140104126994', '1', '2016-09-14 13:28:03', NULL, NULL, NULL, 4);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (2, '5201140104126995', '5', '2016-09-14 13:28:03', NULL, NULL, NULL, 8);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (3, '5201140104166999', '9', '2016-09-14 13:28:03', NULL, NULL, NULL, 12);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (4, '5201140105136997', '12', '2016-09-14 13:28:03', NULL, NULL, NULL, 16);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (5, '5201140106166996', '16', '2016-09-14 13:28:03', NULL, NULL, NULL, 8);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (6, '5201140106167002', '17', '2016-09-14 13:28:03', NULL, NULL, NULL, 17);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (7, '5201140106167003', '19', '2016-09-14 13:28:03', NULL, NULL, NULL, 16);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (8, '5201140107126996', '21', '2016-09-14 13:28:03', NULL, NULL, NULL, 18);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (9, '5201140108146995', '25', '2016-09-14 13:28:03', NULL, NULL, NULL, 18);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (10, '5201140109126996', '26', '2016-09-14 13:28:03', NULL, NULL, NULL, 19);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (11, '5201140109156994', '30', '2016-09-14 13:28:03', NULL, NULL, NULL, 19);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (12, '5201140110137011', '32', '2016-09-14 13:28:03', NULL, NULL, NULL, 20);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (13, '5201140110137038', '35', '2016-09-14 13:28:03', NULL, NULL, NULL, 18);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (14, '5201140110156997', '37', '2016-09-14 13:28:03', NULL, NULL, NULL, 18);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (15, '5201140111126997', '38', '2016-09-14 13:28:03', NULL, NULL, NULL, 17);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (16, '5201140111126999', '39', '2016-09-14 13:28:03', NULL, NULL, NULL, 21);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (17, '5201140112107003', '42', '2016-09-14 13:28:03', NULL, NULL, NULL, 12);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (18, '5201140112126998', '45', '2016-09-14 13:28:03', NULL, NULL, NULL, 22);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (19, '5201140202167000', '51', '2016-09-14 13:28:03', NULL, NULL, NULL, 23);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (20, '5201140202167002', '52', '2016-09-14 13:28:03', NULL, NULL, NULL, 24);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (21, '5201140203136994', '55', '2016-09-14 13:28:03', NULL, NULL, NULL, 8);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (22, '5201140203136995', '56', '2016-09-14 13:28:03', NULL, NULL, NULL, 16);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (23, '5201140203167003', '59', '2016-09-14 13:28:03', NULL, NULL, NULL, 23);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (24, '5201140204166994', '61', '2016-09-14 13:28:03', NULL, NULL, NULL, 25);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (25, '5201140205156994', '62', '2016-09-14 13:28:03', NULL, NULL, NULL, 26);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (26, '5201140205156995', '65', '2016-09-14 13:28:03', NULL, NULL, NULL, 26);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (27, '5201140205156996', '68', '2016-09-14 13:28:03', NULL, NULL, NULL, 25);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (28, '5201140205156997', '71', '2016-09-14 13:28:03', NULL, NULL, NULL, 25);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (29, '5201140206157000', '74', '2016-09-14 13:28:03', NULL, NULL, NULL, 17);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (30, '5201140206157004', '76', '2016-09-14 13:28:03', NULL, NULL, NULL, 27);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (31, '5201140207156998', '77', '2016-09-14 13:28:03', NULL, NULL, NULL, 28);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (32, '5201140207157000', '80', '2016-09-14 13:28:03', NULL, NULL, NULL, 29);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (33, '5201140209156996', '83', '2016-09-14 13:28:03', NULL, NULL, NULL, 30);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (34, '5201140210137022', '84', '2016-09-14 13:28:03', NULL, NULL, NULL, 29);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (35, '5201140211117001', '88', '2016-09-14 13:28:03', NULL, NULL, NULL, 31);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (36, '5201140211117002', '91', '2016-09-14 13:28:03', NULL, NULL, NULL, 31);
INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES (37, '5201140211117003', '95', '2016-09-14 13:28:03', NULL, NULL, NULL, 31);


#
# TABLE STRUCTURE FOR: tweb_keluarga_sejahtera
#

DROP TABLE IF EXISTS `tweb_keluarga_sejahtera`;

CREATE TABLE `tweb_keluarga_sejahtera` (
  `id` int(10) NOT NULL DEFAULT '0',
  `nama` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tweb_keluarga_sejahtera` (`id`, `nama`) VALUES (1, 'Keluarga Pra Sejahtera');
INSERT INTO `tweb_keluarga_sejahtera` (`id`, `nama`) VALUES (2, 'Keluarga Sejahtera I');
INSERT INTO `tweb_keluarga_sejahtera` (`id`, `nama`) VALUES (3, 'Keluarga Sejahtera II');
INSERT INTO `tweb_keluarga_sejahtera` (`id`, `nama`) VALUES (4, 'Keluarga Sejahtera III');
INSERT INTO `tweb_keluarga_sejahtera` (`id`, `nama`) VALUES (5, 'Keluarga Sejahtera III Plus');


#
# TABLE STRUCTURE FOR: tweb_penduduk
#

DROP TABLE IF EXISTS `tweb_penduduk`;

CREATE TABLE `tweb_penduduk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `nik` decimal(16,0) NOT NULL,
  `id_kk` int(11) DEFAULT '0',
  `kk_level` tinyint(2) NOT NULL DEFAULT '0',
  `id_rtm` int(11) NOT NULL,
  `rtm_level` int(11) NOT NULL,
  `sex` tinyint(4) unsigned DEFAULT NULL,
  `tempatlahir` varchar(100) NOT NULL,
  `tanggallahir` date DEFAULT NULL,
  `agama_id` int(10) unsigned NOT NULL,
  `pendidikan_kk_id` int(10) unsigned NOT NULL,
  `pendidikan_sedang_id` int(10) unsigned NOT NULL,
  `pekerjaan_id` int(10) unsigned NOT NULL,
  `status_kawin` tinyint(4) unsigned NOT NULL,
  `warganegara_id` int(10) unsigned NOT NULL,
  `dokumen_pasport` varchar(45) DEFAULT NULL,
  `dokumen_kitas` int(10) DEFAULT NULL,
  `ayah_nik` varchar(16) NOT NULL,
  `ibu_nik` varchar(16) NOT NULL,
  `nama_ayah` varchar(100) NOT NULL,
  `nama_ibu` varchar(100) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `golongan_darah_id` int(11) NOT NULL,
  `id_cluster` int(11) NOT NULL,
  `status` int(10) unsigned DEFAULT NULL,
  `alamat_sebelumnya` varchar(200) NOT NULL,
  `alamat_sekarang` varchar(200) NOT NULL,
  `status_dasar` tinyint(4) NOT NULL DEFAULT '1',
  `hamil` int(1) DEFAULT NULL,
  `cacat_id` int(11) DEFAULT NULL,
  `sakit_menahun_id` int(11) NOT NULL,
  `akta_lahir` varchar(40) NOT NULL,
  `akta_perkawinan` varchar(40) NOT NULL,
  `tanggalperkawinan` date DEFAULT NULL,
  `akta_perceraian` varchar(40) NOT NULL,
  `tanggalperceraian` date DEFAULT NULL,
  `cara_kb_id` tinyint(2) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `tanggal_akhir_paspor` date DEFAULT NULL,
  `no_kk_sebelumnya` varchar(30) DEFAULT NULL,
  `ktp_el` tinyint(4) NOT NULL,
  `status_rekam` tinyint(4) NOT NULL DEFAULT '0',
  `waktu_lahir` varchar(5) NOT NULL,
  `tempat_dilahirkan` tinyint(2) NOT NULL,
  `jenis_kelahiran` tinyint(2) NOT NULL,
  `kelahiran_anak_ke` tinyint(2) NOT NULL,
  `penolong_kelahiran` tinyint(2) NOT NULL,
  `berat_lahir` varchar(10) NOT NULL,
  `panjang_lahir` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (1, 'AHLUL', '5201142005716996', 1, 1, 0, 0, 1, 'MANGSIT', '1970-05-20', 1, 3, 18, 26, 2, 1, '', 0, '', '', 'ARFAH', 'RAISAH', '', 13, 4, 1, '', '', 1, 0, 0, 0, '', '', NULL, '', NULL, 0, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (2, 'AHMAD ALLIF RIZKI', '5201140706966997', 1, 4, 0, 0, 1, 'MANGSIT', '1995-06-07', 1, 1, 18, 1, 1, 1, '', 0, '', '', 'AHLUL', 'RUSDAH', '', 13, 4, 1, '', '', 1, 0, 0, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (3, 'AHMAD HABIB', '5201140301916995', 1, 4, 0, 0, 1, 'MANGSIT', '1990-01-03', 1, 3, 18, 1, 1, 1, NULL, NULL, '', '', 'AHLUL', 'RUSDAH', '', 13, 4, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (4, 'ADINI SEPTIA LISTA', '5201145003976995', 1, 4, 0, 0, 2, 'MANGSIT', '1996-03-10', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'AHLUL', 'RUSDAH', '', 13, 4, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (5, 'AHYAR', '5201141003666996', 2, 1, 0, 0, 1, 'JAKARTA', '1965-03-10', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'PAIMUN', 'SUPINAH', '', 13, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (6, 'APTA MADA RIZKY ALAMSYAH', '5201141412121724', 2, 4, 0, 0, 1, 'DEPOK', '2002-12-14', 1, 2, 18, 3, 1, 1, NULL, NULL, '', '', 'AHYAR', 'ALIYAH', '', 13, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (7, 'ALIYAH', '5201144609786995', 2, 3, 0, 0, 2, 'BEKASI', '1977-09-06', 1, 5, 18, 2, 2, 1, NULL, NULL, '', '', 'TAGOR SIPAHUTAR', 'AMAHWATI', '', 13, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (8, 'ALPIANI', '5201144301171725', 2, 4, 0, 0, 2, 'BOGOR', '2007-01-03', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'AHYAR', 'ALIYAH', '', 13, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (9, 'ASHARI', '5201140107867064', 3, 1, 0, 0, 1, 'KERANDANGAN', '1985-12-30', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'H. ABDUL KARIM', 'RADIAH', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (10, 'BACHTIAR HADI', '5201142210181724', 3, 4, 0, 0, 1, 'MATARAM', '2008-10-22', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'ASHARI', 'ANGGUN LESTARI PRATAMA', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (11, 'ANGGUN LESTARI PRATAMA', '5201146510916995', 3, 3, 0, 0, 2, 'SENGGIGI', '1990-10-25', 1, 4, 18, 88, 2, 1, NULL, NULL, '', '', 'SADIRAH', 'HJ. ROHANI', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (12, 'DAHRI', '5201143112797117', 4, 1, 0, 0, 1, 'MASBAGIK', '1978-12-31', 1, 3, 18, 88, 2, 1, NULL, NULL, '', '', 'AMAQ SAHMINI', 'INAQ SAHMINI', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (13, 'ERLANGGA DWIKO SAPUTRO', '5201140705156994', 4, 4, 0, 0, 1, 'MENINTING', '2014-05-07', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'DAHRI', 'ASMIATUN', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (14, 'FARIDAH', '5201141107101724', 4, 4, 0, 0, 1, 'MASBAGIK', '2000-07-11', 1, 3, 18, 3, 1, 1, NULL, NULL, '', '', 'DAHRI', 'ASMIATUN', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (15, 'ASMIATUN', '5201147112817188', 4, 3, 0, 0, 2, 'MASBAGIK', '1980-12-31', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'AMAQ MUJAENI', 'INAQ SAHMINI', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (16, 'BAIQ OLIVIA APRILLIANI', '5201145211486994', 5, 1, 0, 0, 2, 'SENGGIGI', '1947-11-12', 1, 1, 18, 1, 4, 1, NULL, NULL, '', '', 'AMAQ SINAREP', 'INAQ SINAREP', '', 13, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (17, 'FAUZI', '5201141210906998', 6, 1, 0, 0, 1, 'KERANDANGAN', '1989-10-12', 1, 5, 18, 3, 1, 1, NULL, NULL, '', '', 'SABLI', 'RAOHUN', '', 13, 17, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (18, 'DELLA MAHARANI NINGSIH', '5201147112947048', 6, 9, 0, 0, 2, 'KERANDANGAN', '1993-12-31', 1, 4, 18, 1, 1, 1, NULL, NULL, '', '', 'SABLI', 'RAOHUN', '', 13, 17, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (19, 'HAERUL FATONI', '5201142911936995', 7, 1, 0, 0, 1, 'SENGGIGI', '1992-11-29', 1, 5, 18, 15, 2, 1, NULL, NULL, '', '', 'ANGKASAH', 'MARJANAH', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (20, 'DENATUL SUARTINI', '3275014601977005', 7, 3, 0, 0, 2, 'JAKARTA', '1996-01-06', 1, 5, 18, 2, 2, 1, NULL, NULL, '', '', 'G. AMIN. P', 'NGATI', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (21, 'HERI IRAWAN', '5201140607636994', 8, 1, 0, 0, 1, 'TELOKE', '1962-07-06', 1, 3, 18, 9, 2, 1, NULL, NULL, '', '', 'AMAK MAJUMI', 'INAK MIDAH', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (22, 'HERMAN', '5201140305936994', 8, 4, 0, 0, 1, 'SENGGIGI', '1992-05-03', 1, 4, 18, 1, 1, 1, NULL, NULL, '', '', 'HERI IRAWAN', 'DEWI SAULINA', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (23, 'DEWI SAULINA', '5201144808686994', 8, 3, 0, 0, 2, 'KEKERAN', '1967-08-08', 1, 1, 18, 2, 2, 1, NULL, NULL, '', '', 'H. ZAENUDIN', 'INAK NAH', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (24, 'ELOK KHALISA SABRINA', '5201144408886994', 8, 4, 0, 0, 2, 'SENGGIGI', '1987-08-04', 1, 4, 18, 88, 1, 1, NULL, NULL, '', '', 'SERIMAN', 'DEWI SAULINA', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (25, 'I KETUT PAHING', '5201142210806997', 9, 1, 0, 0, 1, 'MATARAM', '1979-10-22', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', '-', '-', '', 2, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (26, 'IDA BAGUS MAS BUJANA', '5201143112707040', 10, 1, 0, 0, 1, 'APIT AIK', '1969-12-31', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'SAHMIN', 'MAOSIN', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (27, 'JOKO PATMOTO', '5201141009146994', 10, 4, 0, 0, 1, 'MANGSIT', '2013-09-10', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'IDA BAGUS MAS BUJANA', 'FITRIANI', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (28, 'KOMANG SALUN', '5201143105121724', 10, 4, 0, 0, 1, 'KAYANGAN', '2002-05-31', 1, 2, 18, 3, 1, 1, NULL, NULL, '', '', 'AMILUDIN', 'FITRIANI', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (29, 'FITRIANI', '5201145107836994', 10, 3, 0, 0, 2, 'KAYANGAN', '1982-07-11', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'REMBUK', 'SITIAH', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (30, 'LALU WAWAN DININGRAT', '5201141206886994', 11, 1, 0, 0, 1, 'MANGSIT', '1987-06-12', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'MAHSUN SUBUH', 'SARDIAH', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (31, 'FITRIANI', '5271016801926995', 11, 3, 0, 0, 2, 'MATARAM', '1991-01-28', 1, 5, 18, 15, 2, 1, NULL, NULL, '', '', 'UMAR', 'RUMINSIH', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (32, 'M. FA\'IZ AZAMI', '5201143112897123', 12, 1, 0, 0, 1, 'GEGELANG', '1988-12-31', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'SAREH', 'SUTIMAH', '', 13, 20, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (33, 'HILMIATI', '5201146402906994', 12, 3, 0, 0, 2, 'LOCO', '1989-02-24', 1, 4, 18, 88, 2, 1, NULL, NULL, '', '', 'H. MANSYUR', 'HJ. SA\'ADAH', '', 13, 20, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (34, 'HJ. PARIDAH', '5201144912146994', 12, 4, 0, 0, 2, 'MENINTING', '2013-12-09', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'M. FA\'IZ AZAMI', 'HILMIATI', '', 13, 20, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (35, 'HJ. SAMIRAH', '5201147112767266', 13, 1, 0, 0, 2, 'SENGGIGI', '1975-12-31', 1, 3, 18, 15, 3, 1, NULL, NULL, '', '', 'DAMSYAH', 'MARWIYAH', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (36, 'HUR MINAH', '5201144504131726', 13, 4, 0, 0, 2, 'SENGGIGI', '2003-04-05', 1, 3, 18, 3, 1, 1, NULL, NULL, '', '', 'MARSINI', 'KHODIJAH', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (37, 'HUSNAH', '5201145905936994', 14, 1, 0, 0, 2, 'LOTIM', '1992-05-19', 1, 4, 18, 88, 1, 1, NULL, NULL, '', '', '-', '-', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (38, 'IDA AYU OKA SUKERTI', '5201147112587053', 15, 1, 0, 0, 2, 'KERANDANGAN', '1957-12-31', 1, 3, 18, 88, 4, 1, NULL, NULL, '', '', 'ANGGRAH', 'HABIBAH', '', 13, 17, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (39, 'M. JAYADI', '5201143112837098', 16, 1, 0, 0, 1, 'SENGGIGI', '1982-12-31', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'IKHSAN', 'SAIDAH', '', 13, 21, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (40, 'JARIYAH', '5201145406916994', 16, 3, 0, 0, 2, 'SENGGIGI', '1990-06-14', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'SEGEP', 'HURNIWATI', '', 13, 21, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (41, 'LIHAM SATUN', '5201147112116995', 16, 4, 0, 0, 2, 'MATARAM', '2010-12-31', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'M. JAYADI', 'JARIYAH', '', 13, 21, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (42, 'M. NUR SAHID', '5201140507916996', 17, 1, 0, 0, 1, 'KERANDANGAN', '1990-07-05', 1, 4, 18, 88, 2, 1, NULL, NULL, '', '', '-', '-', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (43, 'MADE ASTAWE', '5201142503181724', 17, 4, 0, 0, 1, 'KERANDANGAN', '2008-03-25', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'M. NUR SAHID', 'MAISAH', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (44, 'MAISAH', '5201144605936994', 17, 3, 0, 0, 2, 'KERANDANGAN', '1992-05-06', 4, 1, 18, 88, 2, 1, NULL, NULL, '', '', '-', '-', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (45, 'MARSUNIN YOGA PRATAMA', '5201143112677056', 18, 1, 0, 0, 1, 'PEJARAKAN', '1966-12-31', 1, 3, 18, 9, 2, 1, NULL, NULL, '', '', 'MISRAH', 'INAQ MISDAH', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (46, 'MARZUKI', '5201141003966996', 18, 4, 0, 0, 1, 'LOCO', '1995-03-10', 1, 5, 18, 3, 1, 1, NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (47, 'MUHAMAD HAMDI', '5201141706986996', 18, 4, 0, 0, 1, 'LOCO', '1997-06-17', 1, 4, 18, 3, 1, 1, NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (48, 'MARLIA SAJIDA', '5201147112707088', 18, 3, 0, 0, 2, 'PEJARAKAN', '1969-12-31', 1, 3, 18, 2, 2, 1, NULL, NULL, '', '', 'H. ZAINUDIN', 'INAQ NAH', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (49, 'MIRA FANDA', '5201146704906995', 18, 4, 0, 0, 2, 'LOCO', '1989-04-27', 1, 5, 18, 88, 4, 1, NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (50, 'MUNAAH', '5201146304171724', 18, 4, 0, 0, 2, 'LOCO', '2007-04-23', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (51, 'MUHAMAD KABIR', '5201140107917031', 19, 1, 0, 0, 1, 'SENGGIGI', '1985-12-31', 1, 3, 18, 88, 2, 1, NULL, NULL, '', '', 'MUNIAH', 'SALIKIN', '', 13, 23, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (52, 'MUHAMAD SUHAD', '5201141704876995', 20, 1, 0, 0, 1, 'SENGGIGI', '1982-12-10', 1, 5, 18, 15, 2, 1, NULL, NULL, '', '', 'MUNIAH', 'HAJRIAH', '', 13, 24, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (53, 'MUHAMMAD HAIKAL FIRMANSYAH', '5201140308151724', 20, 4, 0, 0, 1, 'LOCO', '2005-08-03', 1, 2, 18, 1, 1, 1, NULL, NULL, '', '', 'MUHAMAD SUHAD', 'KHADIJAH', '', 13, 24, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (54, 'MURNIAH', '5201145904846994', 20, 3, 0, 0, 2, 'SETANGI', '1991-03-04', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'SAHABUDIN', 'SAKMAH', '', 13, 24, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (55, 'MURNIATI SAGITA', '5201144112726996', 21, 1, 0, 0, 2, 'YOGYAKARTA', '1971-12-01', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'UMAR SANTOSA', 'MIRANTI', '', 1, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (56, 'MUHAMMAD RIFAI', '5201143105926995', 22, 1, 0, 0, 1, 'LOCO', '1991-05-31', 4, 4, 18, 88, 2, 1, NULL, NULL, '', '', 'I WAYAN MERTI', 'NI NYOMAN RENI', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (57, 'NADIA ROSDIANA', '5201144305936996', 22, 3, 0, 0, 2, 'MATARAM', '1992-05-03', 4, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'I WAYAN PARTA', 'NI NENGAH SUDINI', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (58, 'NI KOMANG PONIASIH', '5201146206126994', 22, 4, 0, 0, 2, 'MATARAM', '2011-06-22', 4, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'MURNIATI SAGITA', 'NADIA ROSDIANA', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (59, 'MUHAMMAD WIRDA MAULANA IBRAHIM', '5201143112417056', 23, 1, 0, 0, 1, 'SENGGIGI', '1940-12-31', 1, 1, 18, 9, 2, 1, NULL, NULL, '', '', 'AMAQ SUN -ALM-', 'INAQ SUN -ALM-', '', 13, 23, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (60, 'NI LUH NITA SARI', '5201147112466997', 23, 3, 0, 0, 2, 'SENTELUK', '1945-12-31', 1, 1, 18, 2, 2, 1, NULL, NULL, '', '', 'AMAQ IRAH', 'INAQ IRAH', '', 13, 23, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (61, 'NI NENGAH AYU KARSINI', '5201145505946996', 24, 1, 0, 0, 2, 'SENGGIGI', '1993-05-15', 1, 2, 18, 15, 1, 1, NULL, NULL, '', '', 'H HAMDANI', 'SANERIAH', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (62, 'MUKSAN', '5201143112957094', 25, 1, 0, 0, 1, 'MANGSIT', '1994-12-31', 1, 4, 18, 88, 2, 1, NULL, NULL, '', '', 'MISDAH', 'RABIAH', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (63, 'NURHAYATI', '5201145509146994', 25, 4, 0, 0, 2, 'MENINTING', '2013-09-15', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'MUKSAN', 'NUR\'AINI', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (64, 'MURSIDIN', '5201142204966994', 26, 4, 0, 0, 1, 'MANGSIT', '1995-04-22', 1, 3, 18, 11, 1, 1, NULL, NULL, '', '', 'RUSNAH (ALM)', 'NURHIDAYAH', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (65, 'NURHIDAYAH', '5201144209766995', 26, 1, 0, 0, 2, 'MANGSIT', '1975-09-02', 1, 3, 18, 2, 4, 1, NULL, NULL, '', '', 'ISMAIL', 'JUMINAH', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (66, 'NURJANAH', '5201145005101724', 26, 4, 0, 0, 2, 'MONTONG', '2000-05-10', 1, 4, 18, 3, 1, 1, NULL, NULL, '', '', 'RUSNAH (ALM)', 'NURHIDAYAH', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (67, 'NURUL AINUN', '5201144108121724', 26, 4, 0, 0, 2, 'MANGSIT', '2002-08-01', 1, 2, 18, 3, 1, 1, NULL, NULL, '', '', 'RUSNAH', 'NURHIDAYAH', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (68, 'MUSAHAB', '5201141607936996', 27, 1, 0, 0, 1, 'LOTENG', '1992-07-16', 1, 6, 18, 88, 2, 1, NULL, NULL, '', '', 'LALU ROSIDI', 'BQ. ALISA', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (69, 'NURUL FAIZAH', '5201145003936994', 27, 3, 0, 0, 2, 'SENGGIGI', '1992-03-10', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'SAHUR', 'SARE\'AH', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (70, 'NURUL HIDAYATI', '5201147004136996', 27, 4, 0, 0, 2, 'MATARAM', '2012-04-30', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'MUSAHAB', 'NURUL FAIZAH', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (71, 'NAPIAH', '5201141303906995', 28, 1, 0, 0, 1, 'SENGGIGI', '1989-03-13', 1, 4, 18, 11, 2, 1, NULL, NULL, '', '', 'MUNIAH', 'HAJARIAH', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (72, 'RACHEL YULIANTI', '5201146507966995', 28, 3, 0, 0, 2, 'MELASE', '1995-07-25', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'LUKMAN', 'MUSNAH', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (73, 'RAISHA MAULIDYA', '5201144701156995', 28, 4, 0, 0, 2, 'MENINTING', '2014-01-07', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'NAPIAH', 'RACHEL YULIANTI', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (74, 'PATANUL HUSNUL', '5201143112667000', 29, 1, 0, 0, 1, 'JAWA TIMUR', '1965-12-31', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'AHMAD', 'ASIH', '', 13, 17, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (75, 'RATNAWATY', '5201145512796995', 29, 3, 0, 0, 2, 'KERANDANGAN', '1978-12-15', 1, 5, 18, 84, 2, 1, NULL, NULL, '', '', 'JUM', 'REMAH', '', 13, 17, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (76, 'RABITAH', '5201140312896994', 30, 1, 0, 0, 1, 'KERANDANGAN', '1988-12-03', 4, 4, 18, 88, 1, 1, NULL, NULL, '', '', '-', '-', '', 13, 27, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (77, 'ROMI FAISAL', '5201141506856997', 31, 1, 0, 0, 1, 'MANGSIT', '1984-06-15', 1, 3, 18, 15, 2, 1, NULL, NULL, '', '', 'MUNTAHAR', 'MAKNAH', '', 13, 28, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (78, 'RAUDATUL ILMI', '5201145808816994', 31, 3, 0, 0, 2, 'IRENG DAYE', '1980-08-18', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'MUDAHIR', 'RUMISAH', '', 13, 28, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (79, 'ROHANI', '5201144306116994', 31, 4, 0, 0, 2, 'MANGSIT', '2010-06-03', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'ROMI FAISAL', 'RAUDATUL ILMI', '', 13, 28, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (80, 'RUKIAH', '5201145909946994', 32, 1, 0, 0, 2, 'SERANG', '1993-09-19', 1, 4, 18, 88, 3, 1, NULL, NULL, '', '', '-', '-', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (81, 'RUMALI', '5201144507886994', 32, 9, 0, 0, 2, 'JAKARTA', '1987-07-05', 1, 4, 18, 88, 1, 1, NULL, NULL, '', '', '-', '-', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (82, 'RONI', '5201140301836997', 33, 4, 0, 0, 1, 'DENPASAR', '1982-01-03', 4, 5, 18, 15, 1, 1, NULL, NULL, '', '', 'IDA BAGUS PUTU WIADNYA', 'RUSMAYANTI', '', 13, 30, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (83, 'RUSMAYANTI', '5201145003546994', 33, 1, 0, 0, 2, 'DENPASAR', '1953-03-10', 4, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'IDA BAGUS MAS', 'IDA AYU RAKA', '', 13, 30, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (84, 'RUSNI', '5201143112707180', 34, 1, 0, 0, 1, 'KEKERAN', '1969-12-31', 1, 3, 18, 9, 2, 1, NULL, NULL, '', '', '-', '-', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (85, 'SAPIAH', '5201147011726994', 34, 3, 0, 0, 2, 'KEKERAN', '1971-11-30', 1, 3, 18, 2, 2, 1, NULL, NULL, '', '', '-', '-', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (86, 'SAPINAH', '5201145701966994', 34, 4, 0, 0, 2, 'SENGGIGI', '1995-01-17', 1, 5, 18, 3, 1, 1, NULL, NULL, '', '', 'RUSNI', 'SAPIAH', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (87, 'SARRA LANGELAND', '5201145111946996', 34, 4, 0, 0, 2, 'SENGGIGI', '1993-11-11', 1, 5, 18, 3, 1, 1, NULL, NULL, '', '', 'RUSNI', 'SAPIAH', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (88, 'SAHRONI', '5201143112617096', 35, 1, 0, 0, 1, 'MEDAS', '1960-12-31', 1, 4, 18, 88, 2, 1, NULL, NULL, '', '', 'SADIYAH', 'INAQ SADIAH', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (89, 'SERIMAN', '5201141012846995', 35, 4, 0, 0, 1, 'SENGGIGI', '1983-12-10', 1, 5, 18, 15, 1, 1, NULL, NULL, '', '', 'SAHRONI', 'NURLAELA', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (90, 'SUNYOTOH', '5201143112817139', 35, 4, 0, 0, 1, 'MEDAS', '1980-12-31', 1, 5, 18, 15, 1, 1, NULL, NULL, '', '', 'SAHRONI', 'NURLAELA', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (91, 'SYARIFUL KALAM', '5201141707776994', 36, 1, 0, 0, 1, 'SENGGIGI', '1976-07-17', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'H. ABDURAHMAN', 'NAFISAH', '', 1, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (92, 'SITI AISYAH', '5201146210776994', 36, 3, 0, 0, 2, 'SUKARAJA', '1976-10-22', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'AMINALLOH', 'RAEHAN', '', 2, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (93, 'SITI PAOZIAH', '5201146312161724', 36, 4, 0, 0, 2, 'SENGGIGI', '2006-12-23', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'SYARIFUL KALAM', 'SITI AISYAH', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (94, 'SUKMA UTAMI', '5201144607996998', 36, 4, 0, 0, 2, 'SENGGIGI', '1998-07-06', 1, 4, 18, 3, 1, 1, NULL, NULL, '', '', 'SYARIFUL KALAM', 'SITI AISYAH', '', 5, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (95, 'WAHID ALIAS H. MAHSUN', '5201141212816996', 37, 1, 0, 0, 1, 'SENGGIGI', '1980-12-12', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'H. ABDURRAHMAN', 'NAFISAH', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (96, 'WAYAN EKA PRAWATA', '5201142003136994', 37, 4, 0, 0, 1, 'GUNUNG SARI', '2012-03-20', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'WAHID ALIAS H. MAHSUN', 'ULFA WIDIAWATI', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');
INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES (97, 'ULFA WIDIAWATI', '5201145203896994', 37, 3, 0, 0, 2, 'JOHAR PELITA', '1988-03-12', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'ZAMHARIN', 'SITIYAH', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, '', '');


#
# TABLE STRUCTURE FOR: tweb_penduduk_agama
#

DROP TABLE IF EXISTS `tweb_penduduk_agama`;

CREATE TABLE `tweb_penduduk_agama` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_penduduk_agama` (`id`, `nama`) VALUES (1, 'ISLAM');
INSERT INTO `tweb_penduduk_agama` (`id`, `nama`) VALUES (2, 'KRISTEN');
INSERT INTO `tweb_penduduk_agama` (`id`, `nama`) VALUES (3, 'KATHOLIK');
INSERT INTO `tweb_penduduk_agama` (`id`, `nama`) VALUES (4, 'HINDU');
INSERT INTO `tweb_penduduk_agama` (`id`, `nama`) VALUES (5, 'BUDHA');
INSERT INTO `tweb_penduduk_agama` (`id`, `nama`) VALUES (6, 'KHONGHUCU');
INSERT INTO `tweb_penduduk_agama` (`id`, `nama`) VALUES (7, 'Kepercayaan Terhadap Tuhan YME / Lainnya');


#
# TABLE STRUCTURE FOR: tweb_penduduk_hubungan
#

DROP TABLE IF EXISTS `tweb_penduduk_hubungan`;

CREATE TABLE `tweb_penduduk_hubungan` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (1, 'KEPALA KELUARGA');
INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (2, 'SUAMI');
INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (3, 'ISTRI');
INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (4, 'ANAK');
INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (5, 'MENANTU');
INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (6, 'CUCU');
INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (7, 'ORANGTUA');
INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (8, 'MERTUA');
INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (9, 'FAMILI LAIN');
INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (10, 'PEMBANTU');
INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES (11, 'LAINNYA');


#
# TABLE STRUCTURE FOR: tweb_penduduk_kawin
#

DROP TABLE IF EXISTS `tweb_penduduk_kawin`;

CREATE TABLE `tweb_penduduk_kawin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_penduduk_kawin` (`id`, `nama`) VALUES (1, 'BELUM KAWIN');
INSERT INTO `tweb_penduduk_kawin` (`id`, `nama`) VALUES (2, 'KAWIN');
INSERT INTO `tweb_penduduk_kawin` (`id`, `nama`) VALUES (3, 'CERAI HIDUP');
INSERT INTO `tweb_penduduk_kawin` (`id`, `nama`) VALUES (4, 'CERAI MATI');


#
# TABLE STRUCTURE FOR: tweb_penduduk_map
#

DROP TABLE IF EXISTS `tweb_penduduk_map`;

CREATE TABLE `tweb_penduduk_map` (
  `id` int(11) NOT NULL,
  `lat` varchar(24) NOT NULL,
  `lng` varchar(24) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `tweb_penduduk_map` (`id`, `lat`, `lng`) VALUES (7, '-8.495339739996284', '116.05516478419307');
INSERT INTO `tweb_penduduk_map` (`id`, `lat`, `lng`) VALUES (3, '-8.496679059709217', '116.05342939496042');


#
# TABLE STRUCTURE FOR: tweb_penduduk_pekerjaan
#

DROP TABLE IF EXISTS `tweb_penduduk_pekerjaan`;

CREATE TABLE `tweb_penduduk_pekerjaan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (1, 'BELUM/TIDAK BEKERJA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (2, 'MENGURUS RUMAH TANGGA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (3, 'PELAJAR/MAHASISWA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (4, 'PENSIUNAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (5, 'PEGAWAI NEGERI SIPIL (PNS)');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (6, 'TENTARA NASIONAL INDONESIA (TNI)');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (7, 'KEPOLISIAN RI (POLRI)');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (8, 'PERDAGANGAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (9, 'PETANI/PEKEBUN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (10, 'PETERNAK');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (11, 'NELAYAN/PERIKANAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (12, 'INDUSTRI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (13, 'KONSTRUKSI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (14, 'TRANSPORTASI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (15, 'KARYAWAN SWASTA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (16, 'KARYAWAN BUMN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (17, 'KARYAWAN BUMD');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (18, 'KARYAWAN HONORER');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (19, 'BURUH HARIAN LEPAS');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (20, 'BURUH TANI/PERKEBUNAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (21, 'BURUH NELAYAN/PERIKANAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (22, 'BURUH PETERNAKAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (23, 'PEMBANTU RUMAH TANGGA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (24, 'TUKANG CUKUR');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (25, 'TUKANG LISTRIK');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (26, 'TUKANG BATU');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (27, 'TUKANG KAYU');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (28, 'TUKANG SOL SEPATU');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (29, 'TUKANG LAS/PANDAI BESI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (30, 'TUKANG JAHIT');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (31, 'TUKANG GIGI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (32, 'PENATA RIAS');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (33, 'PENATA BUSANA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (34, 'PENATA RAMBUT');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (35, 'MEKANIK');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (36, 'SENIMAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (37, 'TABIB');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (38, 'PARAJI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (39, 'PERANCANG BUSANA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (40, 'PENTERJEMAH');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (41, 'IMAM MASJID');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (42, 'PENDETA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (43, 'PASTOR');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (44, 'WARTAWAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (45, 'USTADZ/MUBALIGH');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (46, 'JURU MASAK');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (47, 'PROMOTOR ACARA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (48, 'ANGGOTA DPR-RI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (49, 'ANGGOTA DPD');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (50, 'ANGGOTA BPK');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (51, 'PRESIDEN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (52, 'WAKIL PRESIDEN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (53, 'ANGGOTA MAHKAMAH KONSTITUSI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (54, 'ANGGOTA KABINET KEMENTERIAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (55, 'DUTA BESAR');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (56, 'GUBERNUR');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (57, 'WAKIL GUBERNUR');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (58, 'BUPATI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (59, 'WAKIL BUPATI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (60, 'WALIKOTA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (61, 'WAKIL WALIKOTA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (62, 'ANGGOTA DPRD PROVINSI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (63, 'ANGGOTA DPRD KABUPATEN/KOTA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (64, 'DOSEN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (65, 'GURU');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (66, 'PILOT');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (67, 'PENGACARA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (68, 'NOTARIS');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (69, 'ARSITEK');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (70, 'AKUNTAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (71, 'KONSULTAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (72, 'DOKTER');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (73, 'BIDAN');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (74, 'PERAWAT');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (75, 'APOTEKER');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (76, 'PSIKIATER/PSIKOLOG');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (77, 'PENYIAR TELEVISI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (78, 'PENYIAR RADIO');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (79, 'PELAUT');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (80, 'PENELITI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (81, 'SOPIR');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (82, 'PIALANG');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (83, 'PARANORMAL');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (84, 'PEDAGANG');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (85, 'PERANGKAT DESA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (86, 'KEPALA DESA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (87, 'BIARAWATI');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (88, 'WIRASWASTA');
INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES (89, 'LAINNYA');


#
# TABLE STRUCTURE FOR: tweb_penduduk_pendidikan
#

DROP TABLE IF EXISTS `tweb_penduduk_pendidikan`;

CREATE TABLE `tweb_penduduk_pendidikan` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (1, 'BELUM MASUK TK/KELOMPOK BERMAIN');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (2, 'SEDANG TK/KELOMPOK BERMAIN');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (3, 'TIDAK PERNAH SEKOLAH');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (4, 'SEDANG SD/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (5, 'TIDAK TAMAT SD/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (6, 'SEDANG SLTP/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (7, 'SEDANG SLTA/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (8, 'SEDANG  D-1/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (9, 'SEDANG D-2/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (10, 'SEDANG D-3/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (11, 'SEDANG  S-1/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (12, 'SEDANG S-2/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (13, 'SEDANG S-3/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (14, 'SEDANG SLB A/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (15, 'SEDANG SLB B/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (16, 'SEDANG SLB C/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (17, 'TIDAK DAPAT MEMBACA DAN MENULIS HURUF LATIN/ARAB');
INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES (18, 'TIDAK SEDANG SEKOLAH');


#
# TABLE STRUCTURE FOR: tweb_penduduk_pendidikan_kk
#

DROP TABLE IF EXISTS `tweb_penduduk_pendidikan_kk`;

CREATE TABLE `tweb_penduduk_pendidikan_kk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES (1, 'TIDAK / BELUM SEKOLAH');
INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES (2, 'BELUM TAMAT SD/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES (3, 'TAMAT SD / SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES (4, 'SLTP/SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES (5, 'SLTA / SEDERAJAT');
INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES (6, 'DIPLOMA I / II');
INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES (7, 'AKADEMI/ DIPLOMA III/S. MUDA');
INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES (8, 'DIPLOMA IV/ STRATA I');
INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES (9, 'STRATA II');
INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES (10, 'STRATA III');


#
# TABLE STRUCTURE FOR: tweb_penduduk_sex
#

DROP TABLE IF EXISTS `tweb_penduduk_sex`;

CREATE TABLE `tweb_penduduk_sex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_penduduk_sex` (`id`, `nama`) VALUES (1, 'LAKI-LAKI');
INSERT INTO `tweb_penduduk_sex` (`id`, `nama`) VALUES (2, 'PEREMPUAN');


#
# TABLE STRUCTURE FOR: tweb_penduduk_status
#

DROP TABLE IF EXISTS `tweb_penduduk_status`;

CREATE TABLE `tweb_penduduk_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `tweb_penduduk_status` (`id`, `nama`) VALUES (1, 'TETAP');
INSERT INTO `tweb_penduduk_status` (`id`, `nama`) VALUES (2, 'TIDAK AKTIF');
INSERT INTO `tweb_penduduk_status` (`id`, `nama`) VALUES (3, 'PENDATANG');


#
# TABLE STRUCTURE FOR: tweb_penduduk_umur
#

DROP TABLE IF EXISTS `tweb_penduduk_umur`;

CREATE TABLE `tweb_penduduk_umur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(25) DEFAULT NULL,
  `dari` int(11) DEFAULT NULL,
  `sampai` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (1, 'BALITA', 0, 5, 0);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (2, 'ANAK-ANAK', 6, 17, 0);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (3, 'DEWASA', 18, 30, 0);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (4, 'TUA', 31, 120, 0);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (6, 'Di bawah 1 Tahun', 0, 1, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (9, '2 s/d 4 Tahun', 2, 4, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (12, '5 s/d 9 Tahun', 5, 9, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (13, '10 s/d 14 Tahun', 10, 14, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (14, '15 s/d 19 Tahun', 15, 19, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (15, '20 s/d 24 Tahun', 20, 24, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (16, '25 s/d 29 Tahun', 25, 29, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (17, '30 s/d 34 Tahun', 30, 34, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (18, '35 s/d 39 Tahun ', 35, 39, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (19, '40 s/d 44 Tahun', 40, 44, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (20, '45 s/d 49 Tahun', 45, 49, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (21, '50 s/d 54 Tahun', 50, 54, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (22, '55 s/d 59 Tahun', 55, 59, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (23, '60 s/d 64 Tahun', 60, 64, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (24, '65 s/d 69 Tahun', 65, 69, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (25, '70 s/d 74 Tahun', 70, 74, 1);
INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES (26, 'Di atas 75 Tahun', 75, 99999, 1);


#
# TABLE STRUCTURE FOR: tweb_penduduk_warganegara
#

DROP TABLE IF EXISTS `tweb_penduduk_warganegara`;

CREATE TABLE `tweb_penduduk_warganegara` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `tweb_penduduk_warganegara` (`id`, `nama`) VALUES (1, 'WNI');
INSERT INTO `tweb_penduduk_warganegara` (`id`, `nama`) VALUES (2, 'WNA');
INSERT INTO `tweb_penduduk_warganegara` (`id`, `nama`) VALUES (3, 'DUA KEWARGANEGARAAN');


#
# TABLE STRUCTURE FOR: tweb_rtm
#

DROP TABLE IF EXISTS `tweb_rtm`;

CREATE TABLE `tweb_rtm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik_kepala` int(11) NOT NULL,
  `no_kk` varchar(20) NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `kelas_sosial` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: tweb_rtm_hubungan
#

DROP TABLE IF EXISTS `tweb_rtm_hubungan`;

CREATE TABLE `tweb_rtm_hubungan` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `tweb_rtm_hubungan` (`id`, `nama`) VALUES (1, 'Kepala Rumah Tangga');
INSERT INTO `tweb_rtm_hubungan` (`id`, `nama`) VALUES (2, 'Anggota');


#
# TABLE STRUCTURE FOR: tweb_sakit_menahun
#

DROP TABLE IF EXISTS `tweb_sakit_menahun`;

CREATE TABLE `tweb_sakit_menahun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (1, 'JANTUNG');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (2, 'LEVER');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (3, 'PARU-PARU');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (4, 'KANKER');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (5, 'STROKE');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (6, 'DIABETES MELITUS');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (7, 'GINJAL');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (8, 'MALARIA');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (9, 'LEPRA/KUSTA');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (10, 'HIV/AIDS');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (11, 'GILA/STRESS');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (12, 'TBC');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (13, 'ASTHMA');
INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES (14, 'TIDAK ADA/TIDAK SAKIT');


#
# TABLE STRUCTURE FOR: tweb_status_dasar
#

DROP TABLE IF EXISTS `tweb_status_dasar`;

CREATE TABLE `tweb_status_dasar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `tweb_status_dasar` (`id`, `nama`) VALUES (1, 'HIDUP');
INSERT INTO `tweb_status_dasar` (`id`, `nama`) VALUES (2, 'MATI');
INSERT INTO `tweb_status_dasar` (`id`, `nama`) VALUES (3, 'PINDAH');
INSERT INTO `tweb_status_dasar` (`id`, `nama`) VALUES (4, 'HILANG');
INSERT INTO `tweb_status_dasar` (`id`, `nama`) VALUES (9, 'TIDAK VALID');


#
# TABLE STRUCTURE FOR: tweb_status_ktp
#

DROP TABLE IF EXISTS `tweb_status_ktp`;

CREATE TABLE `tweb_status_ktp` (
  `id` tinyint(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `ktp_el` tinyint(4) NOT NULL,
  `status_rekam` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `tweb_status_ktp` (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES (1, 'BELUM REKAM', 1, '2');
INSERT INTO `tweb_status_ktp` (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES (2, 'SUDAH REKAM', 2, '3');
INSERT INTO `tweb_status_ktp` (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES (3, 'CARD PRINTED', 2, '4');
INSERT INTO `tweb_status_ktp` (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES (4, 'PRINT READY RECORD', 2, '5');
INSERT INTO `tweb_status_ktp` (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES (5, 'CARD SHIPPED', 2, '6');
INSERT INTO `tweb_status_ktp` (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES (6, 'SENT FOR CARD PRINTING', 2, '7');
INSERT INTO `tweb_status_ktp` (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES (7, 'CARD ISSUED', 2, '8');


#
# TABLE STRUCTURE FOR: tweb_surat_atribut
#

DROP TABLE IF EXISTS `tweb_surat_atribut`;

CREATE TABLE `tweb_surat_atribut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_surat` int(11) NOT NULL,
  `id_tipe` tinyint(4) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `long` tinyint(4) NOT NULL,
  `kode` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: tweb_surat_format
#

DROP TABLE IF EXISTS `tweb_surat_format`;

CREATE TABLE `tweb_surat_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `url_surat` varchar(100) NOT NULL,
  `kode_surat` varchar(10) NOT NULL,
  `lampiran` varchar(100) DEFAULT NULL,
  `kunci` tinyint(1) NOT NULL DEFAULT '0',
  `favorit` tinyint(1) NOT NULL DEFAULT '0',
  `jenis` tinyint(2) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_surat` (`url_surat`)
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8;

INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (1, 'Keterangan Pengantar', 'surat_ket_pengantar', 'S-01', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (2, 'Keterangan Penduduk', 'surat_ket_penduduk', 'S-02', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (3, 'Biodata Penduduk', 'surat_bio_penduduk', 'S-03', 'f-1.01.php', 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (5, 'Keterangan Pindah Penduduk', 'surat_ket_pindah_penduduk', 'S-04', 'f-1.08.php,f-1.25.php', 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (6, 'Keterangan Jual Beli', 'surat_ket_jual_beli', 'S-05', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (8, 'Pengantar Surat Keterangan Catatan Kepolisian', 'surat_ket_catatan_kriminal', 'S-07', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (9, 'Keterangan KTP dalam Proses', 'surat_ket_ktp_dalam_proses', 'S-08', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (10, 'Keterangan Beda Identitas', 'surat_ket_beda_nama', 'S-09', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (11, 'Keterangan Bepergian / Jalan', 'surat_jalan', 'S-10', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (12, 'Keterangan Kurang Mampu', 'surat_ket_kurang_mampu', 'S-11', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (13, 'Pengantar Izin Keramaian', 'surat_izin_keramaian', 'S-12', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (14, 'Pengantar Laporan Kehilangan', 'surat_ket_kehilangan', 'S-13', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (15, 'Keterangan Usaha', 'surat_ket_usaha', 'S-14', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (16, 'Keterangan JAMKESOS', 'surat_ket_jamkesos', 'S-15', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (17, 'Keterangan Domisili Usaha', 'surat_ket_domisili_usaha', 'S-16', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (18, 'Keterangan Kelahiran', 'surat_ket_kelahiran', 'S-17', 'f-2.01.php', 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (20, 'Permohonan Akta Lahir', 'surat_permohonan_akta', 'S-18', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (21, 'Pernyataan Belum Memiliki Akta Lahir', 'surat_pernyataan_akta', 'S-19', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (22, 'Permohonan Duplikat Kelahiran', 'surat_permohonan_duplikat_kelahiran', 'S-20', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (24, 'Keterangan Kematian', 'surat_ket_kematian', 'S-21', 'f-2.29.php', 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (25, 'Keterangan Lahir Mati', 'surat_ket_lahir_mati', 'S-22', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (26, 'Keterangan Untuk Nikah (N-1 s/d N-7)', 'surat_ket_nikah', 'S-23', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (33, 'Keterangan Pergi Kawin', 'surat_ket_pergi_kawin', 'S-30', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (35, 'Keterangan Wali Hakim', 'surat_ket_wali_hakim', 'S-32', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (36, 'Permohonan Duplikat Surat Nikah', 'surat_permohonan_duplikat_surat_nikah', 'S-33', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (37, 'Permohonan Cerai', 'surat_permohonan_cerai', 'S-34', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (38, 'Keterangan Pengantar Rujuk/Cerai', 'surat_ket_rujuk_cerai', 'S-35', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (45, 'Permohonan Kartu Keluarga', 'surat_permohonan_kartu_keluarga', 'S-36', 'f-1.15.php,f-1.01.php', 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (51, 'Domisili Usaha Non-Warga', 'surat_domisili_usaha_non_warga', 'S-37', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (76, 'Keterangan Beda Identitas KIS', 'surat_ket_beda_identitas_kis', 'S-38', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (85, 'Keterangan Izin Orang Tua/Suami/Istri', 'surat_izin_orangtua_suami_istri', 'S-39', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (86, 'Pernyataan Penguasaan Fisik Bidang Tanah (SPORADIK)', 'surat_sporadik', 'S-40', NULL, 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (89, 'Permohonan Perubahan Kartu Keluarga', 'surat_permohonan_perubahan_kartu_keluarga', 'S-41', 'f-1.16.php,f-1.01.php', 0, 0, 1);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (110, 'Non Warga', 'surat_non_warga', '', NULL, 0, 0, 2);
INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES (156, 'Keterangan Domisili', 'surat_ket_domisili', 'S-41', NULL, 0, 0, 1);


#
# TABLE STRUCTURE FOR: tweb_wil_clusterdesa
#

DROP TABLE IF EXISTS `tweb_wil_clusterdesa`;

CREATE TABLE `tweb_wil_clusterdesa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rt` varchar(10) NOT NULL DEFAULT '0',
  `rw` varchar(10) NOT NULL DEFAULT '0',
  `dusun` varchar(50) NOT NULL DEFAULT '0',
  `id_kepala` int(11) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `lng` varchar(20) NOT NULL,
  `zoom` int(5) NOT NULL,
  `path` text NOT NULL,
  `map_tipe` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rt` (`rt`,`rw`,`dusun`),
  KEY `id_kepala` (`id_kepala`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (1, '0', '0', 'MANGSIT', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (2, '0', '-', 'MANGSIT', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (3, '-', '-', 'MANGSIT', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (4, '004', '-', 'MANGSIT', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (5, '0', '0', 'SENGGIGI', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (6, '0', '-', 'SENGGIGI', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (7, '-', '-', 'SENGGIGI', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (8, '001', '-', 'SENGGIGI', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (9, '0', '0', 'KERANDANGAN', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (10, '0', '-', 'KERANDANGAN', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (11, '-', '-', 'KERANDANGAN', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (12, '002', '-', 'KERANDANGAN', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (13, '0', '0', 'LOCO', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (14, '0', '-', 'LOCO', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (15, '-', '-', 'LOCO', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (16, '003', '-', 'LOCO', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (17, '001', '-', 'KERANDANGAN', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (18, '005', '-', 'SENGGIGI', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (19, '005', '-', 'MANGSIT', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (20, '005', '-', 'LOCO', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (21, '003', '-', 'SENGGIGI', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (22, '002', '-', 'LOCO', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (23, '004', '-', 'SENGGIGI', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (24, '004', '-', 'LOCO', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (25, '001', '-', 'LOCO', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (26, '002', '-', 'MANGSIT', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (27, '004', '-', 'KERANDANGAN', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (28, '003', '-', 'MANGSIT', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (29, '006', '-', 'SENGGIGI', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (30, '006', '-', 'KERANDANGAN', 0, '', '', 0, '', '');
INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES (31, '002', '-', 'SENGGIGI', 0, '', '', 0, '', '');


#
# TABLE STRUCTURE FOR: user
#

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `id_grup` int(5) NOT NULL,
  `email` varchar(100) NOT NULL,
  `last_login` datetime NOT NULL,
  `active` tinyint(1) unsigned DEFAULT '0',
  `nama` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `foto` varchar(100) NOT NULL,
  `session` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `username`, `password`, `id_grup`, `email`, `last_login`, `active`, `nama`, `company`, `phone`, `foto`, `session`) VALUES (1, 'admin', '$2y$10$CfFhuvLXa3RNotqOPYyW2.JujLbAbZ4YO0PtxIRBz4QDLP0/pfH6.', 1, 'admin@combine.or.id', '2018-05-28 09:48:41', 1, 'Administrator', 'ADMIN', '321', 'favicon.png', 'a8d4080245664ed2049c1b2ded7cac30');


#
# TABLE STRUCTURE FOR: user_grup
#

DROP TABLE IF EXISTS `user_grup`;

CREATE TABLE `user_grup` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_grup` (`id`, `nama`) VALUES (1, 'Administrator');
INSERT INTO `user_grup` (`id`, `nama`) VALUES (2, 'Operator');
INSERT INTO `user_grup` (`id`, `nama`) VALUES (3, 'Redaksi');
INSERT INTO `user_grup` (`id`, `nama`) VALUES (4, 'Kontributor');


#
# TABLE STRUCTURE FOR: widget
#

DROP TABLE IF EXISTS `widget`;

CREATE TABLE `widget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isi` text,
  `enabled` int(2) DEFAULT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `jenis_widget` tinyint(2) NOT NULL DEFAULT '3',
  `urut` int(5) DEFAULT NULL,
  `form_admin` varchar(100) NOT NULL,
  `setting` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (1, '<p><iframe src=\"https://www.google.co.id/maps?f=q&source=s_q&hl=en&geocode=&q=Logandu,+Karanggayam&aq=0&oq=logan&sll=-2.550221,118.015568&sspn=52.267573,80.332031&t=h&ie=UTF8&hq=&hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&ll=-7.55854,109.634173&spn=0.052497,0.078449&z=14&output=embed\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" width=\"100%\"></iframe></p> ', 2, 'Peta Desa', 3, 2, '', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (2, 'layanan_mandiri.php', 1, 'Layanan Mandiri', 1, 5, 'mandiri', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (3, 'agenda.php', 1, 'Agenda', 1, 7, 'web/index/1000', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (4, 'galeri.php', 1, 'Galeri', 1, 8, 'gallery', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (5, 'statistik.php', 1, 'Statistik', 1, 9, '', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (6, 'komentar.php', 1, 'Komentar', 1, 10, 'komentar', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (7, 'media_sosial.php', 1, 'Media Sosial', 1, 11, 'sosmed', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (8, 'peta_lokasi_kantor.php', 1, 'Peta Lokasi Kantor', 1, 12, 'hom_desa', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (9, 'statistik_pengunjung.php', 1, 'Statistik Pengunjung', 1, 13, '', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (10, 'arsip_artikel.php', 1, 'Arsip Artikel', 1, 14, '', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (11, 'aparatur_desa.php', 1, 'Aparatur Desa', 1, 4, 'pengurus', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (12, 'sinergi_program.php', 1, 'Sinergi Program', 1, 6, 'web_widget/admin/sinergi_program', '[]');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (13, 'menu_kategori.php', 1, 'Menu Kategori', 1, 3, '', '');
INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES (14, 'peta_wilayah_desa.php', 1, 'Peta Wilayah Desa', 1, 1, 'hom_desa/konfigurasi', '');


#
# TABLE STRUCTURE FOR: suplemen_terdata
#

DROP TABLE IF EXISTS `suplemen_terdata`;

CREATE TABLE `suplemen_terdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_suplemen` int(10) DEFAULT NULL,
  `id_terdata` varchar(20) DEFAULT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_suplemen` (`id_suplemen`),
  CONSTRAINT `suplemen_terdata_ibfk_1` FOREIGN KEY (`id_suplemen`) REFERENCES `suplemen` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: kontak
#

DROP TABLE IF EXISTS `kontak`;

CREATE TABLE `kontak` (
  `id_kontak` int(11) NOT NULL AUTO_INCREMENT,
  `id_pend` int(11) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_kontak`),
  KEY `kontak_ke_tweb_penduduk` (`id_pend`),
  CONSTRAINT `kontak_ke_tweb_penduduk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: anggota_grup_kontak
#

DROP TABLE IF EXISTS `anggota_grup_kontak`;

CREATE TABLE `anggota_grup_kontak` (
  `id_grup_kontak` int(11) NOT NULL AUTO_INCREMENT,
  `id_grup` int(11) NOT NULL,
  `id_kontak` int(11) NOT NULL,
  PRIMARY KEY (`id_grup_kontak`),
  KEY `anggota_grup_kontak_ke_kontak` (`id_kontak`),
  KEY `anggota_grup_kontak_ke_kontak_grup` (`id_grup`),
  CONSTRAINT `anggota_grup_kontak_ke_kontak` FOREIGN KEY (`id_kontak`) REFERENCES `kontak` (`id_kontak`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `anggota_grup_kontak_ke_kontak_grup` FOREIGN KEY (`id_grup`) REFERENCES `kontak_grup` (`id_grup`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: mutasi_inventaris_asset
#

DROP TABLE IF EXISTS `mutasi_inventaris_asset`;

CREATE TABLE `mutasi_inventaris_asset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_inventaris_asset` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double NOT NULL,
  `sumbangkan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_mutasi_inventaris_asset` (`id_inventaris_asset`),
  CONSTRAINT `FK_mutasi_inventaris_asset` FOREIGN KEY (`id_inventaris_asset`) REFERENCES `inventaris_asset` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: mutasi_inventaris_gedung
#

DROP TABLE IF EXISTS `mutasi_inventaris_gedung`;

CREATE TABLE `mutasi_inventaris_gedung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_inventaris_gedung` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double NOT NULL,
  `sumbangkan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_mutasi_inventaris_gedung` (`id_inventaris_gedung`),
  CONSTRAINT `FK_mutasi_inventaris_gedung` FOREIGN KEY (`id_inventaris_gedung`) REFERENCES `inventaris_gedung` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: mutasi_inventaris_jalan
#

DROP TABLE IF EXISTS `mutasi_inventaris_jalan`;

CREATE TABLE `mutasi_inventaris_jalan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_inventaris_jalan` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double NOT NULL,
  `sumbangkan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_mutasi_inventaris_jalan` (`id_inventaris_jalan`),
  CONSTRAINT `FK_mutasi_inventaris_jalan` FOREIGN KEY (`id_inventaris_jalan`) REFERENCES `inventaris_jalan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: mutasi_inventaris_peralatan
#

DROP TABLE IF EXISTS `mutasi_inventaris_peralatan`;

CREATE TABLE `mutasi_inventaris_peralatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_inventaris_peralatan` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double NOT NULL,
  `sumbangkan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_mutasi_inventaris_peralatan` (`id_inventaris_peralatan`),
  CONSTRAINT `FK_mutasi_inventaris_peralatan` FOREIGN KEY (`id_inventaris_peralatan`) REFERENCES `inventaris_peralatan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: mutasi_inventaris_tanah
#

DROP TABLE IF EXISTS `mutasi_inventaris_tanah`;

CREATE TABLE `mutasi_inventaris_tanah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_inventaris_tanah` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double NOT NULL,
  `sumbangkan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_mutasi_inventaris_tanah` (`id_inventaris_tanah`),
  CONSTRAINT `FK_mutasi_inventaris_tanah` FOREIGN KEY (`id_inventaris_tanah`) REFERENCES `inventaris_tanah` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: disposisi_surat_masuk
#

DROP TABLE IF EXISTS `disposisi_surat_masuk`;

CREATE TABLE `disposisi_surat_masuk` (
  `id_disposisi` int(11) NOT NULL AUTO_INCREMENT,
  `id_surat_masuk` int(11) NOT NULL,
  `id_desa_pamong` int(11) DEFAULT NULL,
  `disposisi_ke` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_disposisi`),
  KEY `id_surat_fk` (`id_surat_masuk`),
  KEY `desa_pamong_fk` (`id_desa_pamong`),
  CONSTRAINT `desa_pamong_fk` FOREIGN KEY (`id_desa_pamong`) REFERENCES `tweb_desa_pamong` (`pamong_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_surat_fk` FOREIGN KEY (`id_surat_masuk`) REFERENCES `surat_masuk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: tweb_penduduk_mandiri
#

DROP TABLE IF EXISTS `tweb_penduduk_mandiri`;

CREATE TABLE `tweb_penduduk_mandiri` (
  `pin` char(32) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `tanggal_buat` datetime DEFAULT NULL,
  `id_pend` int(9) NOT NULL,
  PRIMARY KEY (`id_pend`),
  CONSTRAINT `id_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tweb_penduduk_mandiri` (`pin`, `last_login`, `tanggal_buat`, `id_pend`) VALUES ('3645e735f033e8482be0c7993fcba946', '2016-09-14 12:53:47', '2016-09-14 06:06:32', 2);
INSERT INTO `tweb_penduduk_mandiri` (`pin`, `last_login`, `tanggal_buat`, `id_pend`) VALUES ('3645e735f033e8482be0c7993fcba946', '2016-09-14 12:51:53', '2016-09-14 10:10:47', 20);


#
# TABLE STRUCTURE FOR: data_persil
#

DROP TABLE IF EXISTS `data_persil`;

CREATE TABLE `data_persil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pend` int(11) DEFAULT NULL,
  `nama` varchar(128) NOT NULL COMMENT 'nomer persil',
  `jenis_pemilik` int(2) NOT NULL,
  `persil_jenis_id` tinyint(2) NOT NULL,
  `id_clusterdesa` int(11) NOT NULL,
  `luas` decimal(7,2) NOT NULL,
  `no_sppt_pbb` varchar(128) NOT NULL,
  `kelas` varchar(128) DEFAULT NULL,
  `persil_peruntukan_id` tinyint(2) NOT NULL,
  `alamat_luar` varchar(100) DEFAULT NULL,
  `userID` mediumint(9) DEFAULT NULL,
  `peta` text,
  `rdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pemilik_luar` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pend` (`id_pend`),
  CONSTRAINT `persil_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: setting_aplikasi_options
#

DROP TABLE IF EXISTS `setting_aplikasi_options`;

CREATE TABLE `setting_aplikasi_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_setting` int(11) NOT NULL,
  `value` varchar(512) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_setting_fk` (`id_setting`),
  CONSTRAINT `id_setting_fk` FOREIGN KEY (`id_setting`) REFERENCES `setting_aplikasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `setting_aplikasi_options` (`id`, `id_setting`, `value`) VALUES (1, 24, 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk semua surat layanan');
INSERT INTO `setting_aplikasi_options` (`id`, `id_setting`, `value`) VALUES (2, 24, 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk setiap surat layanan dengan jenis yang sama');
INSERT INTO `setting_aplikasi_options` (`id`, `id_setting`, `value`) VALUES (3, 24, 'Nomor berurutan untuk keseluruhan surat layanan, masuk dan keluar');


#
# TABLE STRUCTURE FOR: daftar_kontak
#

DROP TABLE IF EXISTS `daftar_kontak`;

CREATE VIEW `daftar_kontak` AS select `a`.`id_kontak` AS `id_kontak`,`a`.`id_pend` AS `id_pend`,`b`.`nama` AS `nama`,`a`.`no_hp` AS `no_hp`,(case when (`b`.`sex` = '1') then 'Laki-laki' else 'Perempuan' end) AS `sex`,`b`.`alamat_sekarang` AS `alamat_sekarang` from (`kontak` `a` left join `tweb_penduduk` `b` on((`a`.`id_pend` = `b`.`id`)));



#
# TABLE STRUCTURE FOR: daftar_anggota_grup
#

DROP TABLE IF EXISTS `daftar_anggota_grup`;

CREATE VIEW `daftar_anggota_grup` AS select `a`.`id_grup_kontak` AS `id_grup_kontak`,`a`.`id_grup` AS `id_grup`,`c`.`nama_grup` AS `nama_grup`,`b`.`id_kontak` AS `id_kontak`,`b`.`nama` AS `nama`,`b`.`no_hp` AS `no_hp`,`b`.`sex` AS `sex`,`b`.`alamat_sekarang` AS `alamat_sekarang` from ((`anggota_grup_kontak` `a` left join `daftar_kontak` `b` on((`a`.`id_kontak` = `b`.`id_kontak`))) left join `kontak_grup` `c` on((`a`.`id_grup` = `c`.`id_grup`)));



#
# TABLE STRUCTURE FOR: daftar_grup
#

DROP TABLE IF EXISTS `daftar_grup`;

CREATE VIEW `daftar_grup` AS select `a`.`id_grup` AS `id_grup`,`a`.`nama_grup` AS `nama_grup`,(select count(`anggota_grup_kontak`.`id_kontak`) from `anggota_grup_kontak` where (`a`.`id_grup` = `anggota_grup_kontak`.`id_grup`)) AS `jumlah_anggota` from `kontak_grup` `a`;



#
# TABLE STRUCTURE FOR: penduduk_hidup
#

DROP TABLE IF EXISTS `penduduk_hidup`;

CREATE VIEW `penduduk_hidup` AS select `tweb_penduduk`.`id` AS `id`,`tweb_penduduk`.`nama` AS `nama`,`tweb_penduduk`.`nik` AS `nik`,`tweb_penduduk`.`id_kk` AS `id_kk`,`tweb_penduduk`.`kk_level` AS `kk_level`,`tweb_penduduk`.`id_rtm` AS `id_rtm`,`tweb_penduduk`.`rtm_level` AS `rtm_level`,`tweb_penduduk`.`sex` AS `sex`,`tweb_penduduk`.`tempatlahir` AS `tempatlahir`,`tweb_penduduk`.`tanggallahir` AS `tanggallahir`,`tweb_penduduk`.`agama_id` AS `agama_id`,`tweb_penduduk`.`pendidikan_kk_id` AS `pendidikan_kk_id`,`tweb_penduduk`.`pendidikan_sedang_id` AS `pendidikan_sedang_id`,`tweb_penduduk`.`pekerjaan_id` AS `pekerjaan_id`,`tweb_penduduk`.`status_kawin` AS `status_kawin`,`tweb_penduduk`.`warganegara_id` AS `warganegara_id`,`tweb_penduduk`.`dokumen_pasport` AS `dokumen_pasport`,`tweb_penduduk`.`dokumen_kitas` AS `dokumen_kitas`,`tweb_penduduk`.`ayah_nik` AS `ayah_nik`,`tweb_penduduk`.`ibu_nik` AS `ibu_nik`,`tweb_penduduk`.`nama_ayah` AS `nama_ayah`,`tweb_penduduk`.`nama_ibu` AS `nama_ibu`,`tweb_penduduk`.`foto` AS `foto`,`tweb_penduduk`.`golongan_darah_id` AS `golongan_darah_id`,`tweb_penduduk`.`id_cluster` AS `id_cluster`,`tweb_penduduk`.`status` AS `status`,`tweb_penduduk`.`alamat_sebelumnya` AS `alamat_sebelumnya`,`tweb_penduduk`.`alamat_sekarang` AS `alamat_sekarang`,`tweb_penduduk`.`status_dasar` AS `status_dasar`,`tweb_penduduk`.`hamil` AS `hamil`,`tweb_penduduk`.`cacat_id` AS `cacat_id`,`tweb_penduduk`.`sakit_menahun_id` AS `sakit_menahun_id`,`tweb_penduduk`.`akta_lahir` AS `akta_lahir`,`tweb_penduduk`.`akta_perkawinan` AS `akta_perkawinan`,`tweb_penduduk`.`tanggalperkawinan` AS `tanggalperkawinan`,`tweb_penduduk`.`akta_perceraian` AS `akta_perceraian`,`tweb_penduduk`.`tanggalperceraian` AS `tanggalperceraian`,`tweb_penduduk`.`cara_kb_id` AS `cara_kb_id`,`tweb_penduduk`.`telepon` AS `telepon`,`tweb_penduduk`.`tanggal_akhir_paspor` AS `tanggal_akhir_paspor`,`tweb_penduduk`.`no_kk_sebelumnya` AS `no_kk_sebelumnya`,`tweb_penduduk`.`ktp_el` AS `ktp_el`,`tweb_penduduk`.`status_rekam` AS `status_rekam`,`tweb_penduduk`.`waktu_lahir` AS `waktu_lahir`,`tweb_penduduk`.`tempat_dilahirkan` AS `tempat_dilahirkan`,`tweb_penduduk`.`jenis_kelahiran` AS `jenis_kelahiran`,`tweb_penduduk`.`kelahiran_anak_ke` AS `kelahiran_anak_ke`,`tweb_penduduk`.`penolong_kelahiran` AS `penolong_kelahiran`,`tweb_penduduk`.`berat_lahir` AS `berat_lahir`,`tweb_penduduk`.`panjang_lahir` AS `panjang_lahir` from `tweb_penduduk` where (`tweb_penduduk`.`status_dasar` = 1);



