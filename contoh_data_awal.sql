#
# TABLE STRUCTURE FOR: analisis_indikator
#

DROP TABLE IF EXISTS analisis_indikator;

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

INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('1', '2', '1', 'kepemilikan rumah', '1', '1', '1', '1', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('2', '2', '2', 'penghasilan perbulan', '1', '4', '1', '2', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('3', '3', '1', 'Jumlah Penghasilan Perbulan', '3', '0', '0', '3', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('4', '3', '2', 'Jumlah Pengeluaran Perbulan', '3', '0', '0', '3', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('5', '3', '3', 'Status Kepemilikan Rumah?*', '1', '0', '0', '3', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('6', '3', '4', 'Kategori KK', '1', '0', '0', '3', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('7', '3', '5', 'Penerima Raskin', '1', '0', '0', '3', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('8', '3', '6', 'Penerima BLT/BLSM', '1', '0', '0', '3', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('9', '3', '7', 'Peserta BPJS/Jamkesmas/Jamkesda', '1', '0', '0', '3', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('10', '3', '8', 'Sumber Air Minum?*', '1', '0', '0', '4', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('11', '3', '9', 'Keterangan', '2', '0', '0', '4', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('12', '3', '10', 'Jenis Lahan', '1', '0', '0', '5', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('13', '3', '11', 'Luas Lahan', '1', '0', '0', '5', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('14', '3', '12', 'Jenis Komoditas', '1', '0', '0', '6', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('15', '3', '13', 'Produksi', '3', '0', '0', '6', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('16', '3', '14', 'Satuan', '1', '0', '0', '6', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('17', '3', '15', 'Nilai (Rp)', '3', '0', '0', '6', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('18', '3', '16', 'Pemasaran Hasil', '1', '0', '0', '6', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('19', '3', '17', 'Jenis Komoditas', '1', '0', '0', '7', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('20', '3', '18', 'Jumlah Pohon', '3', '0', '0', '7', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('21', '3', '19', 'Produksi', '3', '0', '0', '7', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('22', '3', '20', 'Satuan', '1', '0', '0', '7', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('23', '3', '21', 'Nilai (Rp)', '3', '0', '0', '7', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('24', '3', '22', 'Pemasaran Hasil', '1', '0', '0', '7', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('25', '3', '23', 'Jenis Komoditas', '1', '0', '0', '8', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('26', '3', '24', 'Produksi', '3', '0', '0', '8', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('27', '3', '25', 'Satuan', '1', '0', '0', '8', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('28', '3', '26', 'Nilai (Rp)', '3', '0', '0', '8', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('29', '3', '27', 'Pemasaran Hasil', '1', '0', '0', '8', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('30', '3', '28', 'Jenis Komoditas', '1', '0', '0', '9', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('31', '3', '29', 'Produksi', '3', '0', '0', '9', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('32', '3', '30', 'Satuan', '1', '0', '0', '9', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('33', '3', '31', 'Nilai (Rp)', '3', '0', '0', '9', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('34', '3', '32', 'Pemasaran Hasil', '1', '0', '0', '9', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('35', '3', '33', 'Jenis Komoditas', '1', '0', '0', '10', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('36', '3', '34', 'Produksi', '3', '0', '0', '10', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('37', '3', '35', 'Satuan', '1', '0', '0', '10', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('38', '3', '36', 'Nilai (Rp)', '3', '0', '0', '10', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('39', '3', '37', 'Pemasaran Hasil', '1', '0', '0', '10', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('40', '3', '38', 'Jenis Komoditas', '1', '0', '0', '11', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('41', '3', '39', 'Produksi', '3', '0', '0', '11', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('42', '3', '40', 'Satuan', '1', '0', '0', '11', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('43', '3', '41', 'Nilai (Rp)', '3', '0', '0', '11', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('44', '3', '42', 'Pemasaran Hasil', '1', '0', '0', '11', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('45', '3', '43', 'Jenis Komoditas', '1', '0', '0', '12', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('46', '3', '44', 'Produksi', '3', '0', '0', '12', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('47', '3', '45', 'Satuan', '1', '0', '0', '12', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('48', '3', '46', 'Nilai (Rp)', '3', '0', '0', '12', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('49', '3', '47', 'Pemasaran Hasil', '1', '0', '0', '12', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('50', '3', '48', 'Jenis Bahan Galian', '1', '0', '0', '13', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('51', '3', '49', 'Milik Perorangan (Ha)', '3', '0', '0', '13', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('52', '3', '50', 'Milik Adat (Ha)', '3', '0', '0', '13', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('53', '3', '51', 'Satuan', '1', '0', '0', '13', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('54', '3', '52', 'Pemasaran', '1', '0', '0', '13', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('55', '3', '53', 'Jenis Komoditas', '1', '0', '0', '14', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('56', '3', '54', 'Produksi', '3', '0', '0', '14', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('57', '3', '55', 'Satuan', '1', '0', '0', '14', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('58', '3', '56', 'Nilai (Rp)', '3', '0', '0', '14', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('59', '3', '57', 'Pemasaran Hasil', '1', '0', '0', '14', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('60', '3', '58', 'Nama Alat', '1', '0', '0', '15', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('61', '3', '59', 'Jumlah', '3', '0', '0', '15', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('62', '3', '60', 'Pemanfaatan Sungai/Waduk DLL', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('63', '3', '61', 'Lembaga Pendidikan', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('64', '3', '62', 'Penguasaan Aset Tanah', '1', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('65', '3', '63', 'Aset Sarana Transportasi Umum', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('66', '3', '64', 'Aset Sarana Produksi', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('67', '3', '65', 'Aset Rumah (Dinding)', '1', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('68', '3', '66', 'Aset Rumah (Lantai)', '1', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('69', '3', '67', 'Aset Rumah (Atap)', '1', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('70', '3', '68', 'Aset Lainnya', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('71', '3', '69', 'Kualitas Ibu Hamil', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('72', '3', '70', 'Kualitas Bayi', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('73', '3', '71', 'Tempat Persalinan', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('74', '3', '72', 'Pertolongan Persalinan', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('75', '3', '73', 'Cakupan Imunisasi', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('76', '3', '74', 'Penderita Sakit Kelainan', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('77', '3', '75', 'Perilaku Hidup Bersih', '1', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('78', '3', '76', 'Pola Makan', '1', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('79', '3', '77', 'Kebiasaan Berobat', '1', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('80', '3', '78', 'Status Gizi Balita', '1', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('81', '3', '79', 'Jenis Penyakit', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('82', '3', '80', 'Kerukunan', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('83', '3', '81', 'Perkelahian', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('84', '3', '82', 'Pencurian', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('85', '3', '83', 'Penjarahan', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('86', '3', '84', 'Perjudian', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('87', '3', '85', 'Pemakaian Miras dan Narkoba', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('88', '3', '86', 'Pembunuhan', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('89', '3', '87', 'Penculikan', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('90', '3', '88', 'Kejahatan Seksual', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('91', '3', '89', 'Kekerasan Dalam Rumah Tangga', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('92', '3', '90', 'Masalah Kesejahteraan Keluarga', '2', '0', '0', '16', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('93', '4', '1', 'Nomor Akte Kelahiran', '4', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('94', '4', '2', 'Hubungan dengan Kepala Keluarga', '1', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('95', '4', '3', 'Status Perkawinan', '1', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('96', '4', '4', 'Agama dan Aliran Kepercayaan', '1', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('97', '4', '5', 'Golongan Darah', '1', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('98', '4', '6', 'Kewarganegaraan', '1', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('99', '4', '7', 'Etnis/Suku', '4', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('100', '4', '8', 'Pendidikan Umum Terakhir', '1', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('101', '4', '9', 'Mata Pencaharian Pokok/Pekerjaan', '1', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('102', '4', '10', 'Nama Bapak Kandung', '4', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('103', '4', '11', 'Nama Ibu Kandung', '4', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('104', '4', '12', 'Akseptor KB', '1', '0', '0', '17', '0', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('105', '4', '13', 'Cacat Fisik', '2', '0', '0', '17', '0', '1');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('106', '4', '14', 'Cacat Mental', '2', '0', '0', '17', '0', '1');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('107', '4', '15', 'Kedudukan Anggota Keluarga sebagai Wajib Pajak dan Retribusi', '2', '0', '0', '17', '0', '1');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('108', '4', '16', 'Lembaga Pemerintahan Yang Diikuti Anggota Keluarga', '2', '0', '0', '17', '0', '1');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('109', '4', '17', 'Lembaga Kemasyarakatan Yang Diikuti Anggota Keluarga', '2', '0', '0', '17', '0', '1');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES ('110', '4', '18', 'Lembaga Ekonomi Yang Dimiliki Anggota Keluarga', '2', '0', '0', '17', '0', '1');


#
# TABLE STRUCTURE FOR: analisis_kategori_indikator
#

DROP TABLE IF EXISTS analisis_kategori_indikator;

CREATE TABLE `analisis_kategori_indikator` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `id_master` tinyint(4) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `kategori_kode` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_master` (`id_master`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('1', '2', 'Aset', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('2', '2', 'Penghasilan', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('3', '3', 'PENGHASILAN DAN PENGELUARAN KELUARGA', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('4', '3', 'SUMBER AIR MINUM KELUARGA', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('5', '3', 'KEPEMILIKAN LAHAN KELUARGA', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('6', '3', 'PRODUKSI TANAMAN PANGAN', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('7', '3', 'PRODUKSI BUAH-BUAHAN', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('8', '3', 'PRODUKSI TANAMAN OBAT', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('9', '3', 'PRODUKSI PERKEBUNAN', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('10', '3', 'PRODUKSI HASIL HUTAN', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('11', '3', 'JENIS TERNAK', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('12', '3', 'PRODUKSI PERIKANAN', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('13', '3', 'PRODUKSI BAHAN GALIAN', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('14', '3', 'PENGOLAHAN HASIL TERNAK', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('15', '3', 'ALAT PRODUKSI PERIKANAN', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('16', '3', 'PEMANFAATAN AIR, ASET RUMAH DLL', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('17', '4', 'Data Anggota Keluarga', '');


#
# TABLE STRUCTURE FOR: analisis_klasifikasi
#

DROP TABLE IF EXISTS analisis_klasifikasi;

CREATE TABLE `analisis_klasifikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_master` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `minval` double(5,2) NOT NULL,
  `maxval` double(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_master` (`id_master`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO analisis_klasifikasi (`id`, `id_master`, `nama`, `minval`, `maxval`) VALUES ('1', '2', 'Miskin', '5.00', '10.00');
INSERT INTO analisis_klasifikasi (`id`, `id_master`, `nama`, `minval`, `maxval`) VALUES ('2', '2', 'Sedang', '11.00', '20.00');
INSERT INTO analisis_klasifikasi (`id`, `id_master`, `nama`, `minval`, `maxval`) VALUES ('3', '2', 'Kaya', '21.00', '25.00');


#
# TABLE STRUCTURE FOR: analisis_master
#

DROP TABLE IF EXISTS analisis_master;

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

INSERT INTO analisis_master (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`, `format_impor`, `jenis`) VALUES ('1', 'Analisis Keahlian Individu', '1', '1', '<p>survey</p>', '00000', '0', '1', '0', '0', '2');
INSERT INTO analisis_master (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`, `format_impor`, `jenis`) VALUES ('2', 'AKP Lombok Tengah', '2', '1', '<p>keterangan</p>', '00000', '0', '1', '0', '0', '2');
INSERT INTO analisis_master (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`, `format_impor`, `jenis`) VALUES ('3', 'Data Dasar Keluarga (Prodeskel)', '2', '1', 'Pendataan Profil Desa', 'DDK02', '0', '', '0', '0', '1');
INSERT INTO analisis_master (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`, `format_impor`, `jenis`) VALUES ('4', 'Data Anggota Keluarga (Prodeskel)', '1', '1', 'Pendataan Profil Desa', 'DAK02', '0', '', '0', '0', '1');


#
# TABLE STRUCTURE FOR: analisis_parameter
#

DROP TABLE IF EXISTS analisis_parameter;

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

INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1', '1', 'milik sendiri', '5', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('2', '1', 'milik orang tua', '4', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('3', '1', 'kontrak', '1', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('4', '2', '< Rp.500.000,-', '1', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('5', '2', 'Rp 500.000,- sampa Rp 1.000.000,-', '3', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('6', '2', 'diatas Rp 2.000.000,-', '5', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('7', '5', 'Milik Sendiri', '0', '169', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('8', '5', 'Milik Orang Tua', '0', '170', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('9', '5', 'Milik Keluarga', '0', '171', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('10', '5', 'Sewa/Kontrak', '0', '172', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('11', '5', 'Pinjam Pakai', '0', '173', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('12', '6', 'Pra Sejahtera', '0', '0', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('13', '6', 'Sejahtera 1', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('14', '6', 'Sejahtera 2', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('15', '6', 'Sejahtera 3+', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('16', '7', 'Ya', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('17', '7', 'Tidak', '0', '0', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('18', '8', 'Ya', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('19', '8', 'Tidak', '0', '0', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('20', '9', 'Ya', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('21', '9', 'Tidak', '0', '0', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('22', '10', 'Bak penampung air hujan', '0', '503', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('23', '10', 'Beli dari tangki swasta', '0', '504', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('24', '10', 'Depot isi ulang', '0', '505', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('25', '10', 'Embung', '0', '502', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('26', '10', 'Hidran umum', '0', '498', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('27', '10', 'Mata air', '0', '495', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('28', '10', 'PAM', '0', '499', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('29', '10', 'Pipa', '0', '500', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('30', '10', 'Sumber Air Resapan Umum', '0', '1741', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('31', '10', 'Sumur gali', '0', '496', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('32', '10', 'Sumur pompa', '0', '497', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('33', '10', 'Sungai', '0', '501', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('34', '11', 'Baik', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('35', '11', 'Berasa', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('36', '11', 'Berwarna', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('37', '11', 'Berbau', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('38', '12', 'Hutan', '0', '952', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('39', '12', 'Perkebunan', '0', '951', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('40', '12', 'Tanaman Pangan', '0', '950', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('41', '13', 'Memiliki kurang 0,5 ha', '0', '1732', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('42', '13', 'Memiliki 0,5 - 1,0 ha', '0', '1733', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('43', '13', 'Memiliki lebih dari 1,0 ha', '0', '1734', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('44', '13', 'Tidak memiliki', '0', '1735', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('45', '14', 'Bawah Merah', '0', '12', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('46', '14', 'Bawang Putih', '0', '13', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('47', '14', 'Bayam', '0', '22', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('48', '14', 'Brocoli', '0', '20', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('49', '14', 'Buncis', '0', '19', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('50', '14', 'Cabe', '0', '11', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('51', '14', 'Jagung', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('52', '14', 'Jamur', '0', '78', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('53', '14', 'Jeruk Nipis', '0', '48', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('54', '14', 'Kacang Hijau', '0', '253', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('55', '14', 'Kacang Kedelai', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('56', '14', 'Kacang Merah', '0', '6', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('57', '14', 'Kacang Panjang', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('58', '14', 'Kacang Tanah', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('59', '14', 'Kacang Turis', '0', '24', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('60', '14', 'Kangkung', '0', '23', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('61', '14', 'Kemiri', '0', '96', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('62', '14', 'Kentang', '0', '16', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('63', '14', 'Kubis', '0', '17', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('64', '14', 'Mentimun', '0', '18', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('65', '14', 'Padi Ladang', '0', '8', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('66', '14', 'Padi Sawah', '0', '7', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('67', '14', 'Sawi', '0', '15', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('68', '14', 'Selada', '0', '26', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('69', '14', 'Terong', '0', '21', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('70', '14', 'Tomat', '0', '14', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('71', '14', 'Tumpang Sari', '0', '29', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('72', '14', 'Ubi Jalar', '0', '10', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('73', '14', 'Ubi Kayu', '0', '9', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('74', '14', 'Umbi-Umbian Lain', '0', '25', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('75', '14', 'Wortel', '0', '28', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('76', '16', 'BATANG/TH', '0', '1746', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('77', '16', 'BUAH/TH ', '0', '1013', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('78', '16', 'EKOR/TH ', '0', '1745', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('79', '16', 'JENIS/TH', '0', '965', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('80', '16', 'KG/TH', '0', '960', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('81', '16', 'LITER/TH', '0', '962', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('82', '16', 'M/TH', '0', '963', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('83', '16', 'M3/TH', '0', '961', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('84', '16', 'TON/TH', '0', '966', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('85', '16', 'UNIT/TH', '0', '964', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('86', '18', 'Dijual ke Lumbung Pangan Desa/kel', '0', '493', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('87', '18', 'Dijual ke pasar', '0', '489', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('88', '18', 'Dijual langsung ke konsumen', '0', '488', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('89', '18', 'Dijual melalui KUD', '0', '490', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('90', '18', 'Dijual melalui Pengecer', '0', '492', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('91', '18', 'Dijual melalui Tengkulak', '0', '491', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('92', '18', 'Tidak dijual', '0', '494', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('93', '19', 'Alpokat', '0', '31', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('94', '19', 'Anggur', '0', '54', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('95', '19', 'Apel', '0', '36', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('96', '19', 'Belimbing', '0', '38', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('97', '19', 'Duku', '0', '41', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('98', '19', 'Durian', '0', '39', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('99', '19', 'Gandaria', '0', '258', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('100', '19', 'Jambu air', '0', '50', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('101', '19', 'Jambu klutuk', '0', '57', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('102', '19', 'Jambu Mete', '0', '88', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('103', '19', 'Jeruk', '0', '30', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('104', '19', 'Kedondong', '0', '53', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('105', '19', 'Kesemek', '0', '257', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('106', '19', 'Kokosan', '0', '42', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('107', '19', 'Lengkeng', '0', '45', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('108', '19', 'Limau', '0', '47', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('109', '19', 'Mangga', '0', '32', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('110', '19', 'Manggis', '0', '34', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('111', '19', 'Markisa', '0', '44', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('112', '19', 'Matoa', '0', '249', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('113', '19', 'Melinjo', '0', '55', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('114', '19', 'Melon', '0', '49', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('115', '19', 'Murbei', '0', '58', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('116', '19', 'Nangka', '0', '51', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('117', '19', 'Nenas', '0', '56', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('118', '19', 'Pepaya', '0', '37', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('119', '19', 'Pisang', '0', '43', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('120', '19', 'Rambutan', '0', '33', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('121', '19', 'Salak', '0', '35', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('122', '19', 'Sawo', '0', '40', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('123', '19', 'Semangka', '0', '46', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('124', '19', 'Sirsak', '0', '52', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('125', '19', 'Stroberi', '0', '255', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('126', '19', 'Talas', '0', '27', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('127', '22', 'BATANG/TH', '0', '1746', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('128', '22', 'BUAH/TH ', '0', '1013', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('129', '22', 'EKOR/TH ', '0', '1745', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('130', '22', 'JENIS/TH', '0', '965', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('131', '22', 'KG/TH', '0', '960', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('132', '22', 'LITER/TH', '0', '962', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('133', '22', 'M/TH', '0', '963', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('134', '22', 'M3/TH', '0', '961', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('135', '22', 'TON/TH', '0', '966', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('136', '22', 'UNIT/TH', '0', '964', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('137', '24', 'Dijual ke Lumbung Pangan Desa/kel', '0', '493', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('138', '24', 'Dijual ke pasar', '0', '489', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('139', '24', 'Dijual langsung ke konsumen', '0', '488', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('140', '24', 'Dijual melalui KUD', '0', '490', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('141', '24', 'Dijual melalui Pengecer', '0', '492', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('142', '24', 'Dijual melalui Tengkulak', '0', '491', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('143', '24', 'Tidak dijual', '0', '494', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('144', '25', 'Akar Wangi', '0', '76', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('145', '25', 'Buah Merah', '0', '65', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('146', '25', 'Daun Dewa', '0', '63', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('147', '25', 'Daun Sereh', '0', '74', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('148', '25', 'Daun Sirih', '0', '72', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('149', '25', 'Dewi-Dewi', '0', '79', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('150', '25', 'Jahe', '0', '59', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('151', '25', 'Jamur', '0', '252', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('152', '25', 'Kayu Manis', '0', '73', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('153', '25', 'Kencur', '0', '77', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('154', '25', 'Kumis Kucing', '0', '64', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('155', '25', 'Kunyit', '0', '60', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('156', '25', 'Lengkuas', '0', '61', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('157', '25', 'Mahkota Dewa', '0', '75', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('158', '25', 'Mengkudu', '0', '62', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('159', '25', 'Sambiloto', '0', '66', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('160', '25', 'Temu Hitam', '0', '68', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('161', '25', 'Temu Kunci', '0', '71', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('162', '25', 'Temu Putih', '0', '69', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('163', '25', 'Temu Putri', '0', '70', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('164', '25', 'Temulawak', '0', '67', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('165', '27', 'BATANG/TH', '0', '1746', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('166', '27', 'BUAH/TH ', '0', '1013', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('167', '27', 'EKOR/TH ', '0', '1745', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('168', '27', 'JENIS/TH', '0', '965', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('169', '27', 'KG/TH', '0', '960', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('170', '27', 'LITER/TH', '0', '962', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('171', '27', 'M/TH', '0', '963', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('172', '27', 'M3/TH', '0', '961', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('173', '27', 'TON/TH', '0', '966', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('174', '27', 'UNIT/TH', '0', '964', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('175', '29', 'Dijual ke Lumbung Pangan Desa/kel', '0', '493', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('176', '29', 'Dijual ke pasar', '0', '489', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('177', '29', 'Dijual langsung ke konsumen', '0', '488', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('178', '29', 'Dijual melalui KUD', '0', '490', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('179', '29', 'Dijual melalui Pengecer', '0', '492', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('180', '29', 'Dijual melalui Tengkulak', '0', '491', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('181', '29', 'Tidak dijual', '0', '494', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('182', '30', 'Cengkeh', '0', '83', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('183', '30', 'Coklat', '0', '84', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('184', '30', 'Jarak kepyar', '0', '93', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('185', '30', 'Jarak pagar', '0', '92', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('186', '30', 'Kacang mede', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('187', '30', 'Kapuk', '0', '95', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('188', '30', 'Karet', '0', '87', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('189', '30', 'Kelapa', '0', '80', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('190', '30', 'Kelapa sawit', '0', '81', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('191', '30', 'Kemiri', '0', '256', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('192', '30', 'Kopi', '0', '82', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('193', '30', 'Lada', '0', '86', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('194', '30', 'Pala', '0', '90', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('195', '30', 'Pinang', '0', '85', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('196', '30', 'Tebu', '0', '94', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('197', '30', 'Teh', '0', '97', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('198', '30', 'Tembakau', '0', '89', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('199', '30', 'Vanili', '0', '91', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('200', '32', 'BATANG/TH', '0', '1746', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('201', '32', 'BUAH/TH ', '0', '1013', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('202', '32', 'EKOR/TH ', '0', '1745', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('203', '32', 'JENIS/TH', '0', '965', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('204', '32', 'KG/TH', '0', '960', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('205', '32', 'LITER/TH', '0', '962', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('206', '32', 'M/TH', '0', '963', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('207', '32', 'M3/TH', '0', '961', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('208', '32', 'TON/TH', '0', '966', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('209', '32', 'UNIT/TH', '0', '964', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('210', '34', 'Dijual ke Lumbung Pangan Desa/kel', '0', '493', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('211', '34', 'Dijual ke pasar', '0', '489', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('212', '34', 'Dijual langsung ke konsumen', '0', '488', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('213', '34', 'Dijual melalui KUD', '0', '490', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('214', '34', 'Dijual melalui Pengecer', '0', '492', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('215', '34', 'Dijual melalui Tengkulak', '0', '491', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('216', '34', 'Tidak dijual', '0', '494', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('217', '35', 'Arang', '0', '121', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('218', '35', 'Bambu', '0', '102', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('219', '35', 'Cemara', '0', '109', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('220', '35', 'Damar', '0', '101', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('221', '35', 'Enau', '0', '107', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('222', '35', 'Gambir', '0', '117', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('223', '35', 'Gula enau', '0', '119', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('224', '35', 'Gula lontar', '0', '120', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('225', '35', 'Ijuk Enau', '0', '245', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('226', '35', 'Jati', '0', '103', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('227', '35', 'Kayu', '0', '98', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('228', '35', 'Kayu Bakar', '0', '247', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('229', '35', 'Kayu besi', '0', '114', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('230', '35', 'Kayu cendana', '0', '110', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('231', '35', 'Kayu gaharu', '0', '111', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('232', '35', 'Kayu Sengon', '0', '246', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('233', '35', 'Kayu ulin', '0', '115', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('234', '35', 'Kemenyan', '0', '116', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('235', '35', 'Lontar', '0', '105', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('236', '35', 'Madu lebah', '0', '99', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('237', '35', 'Mahoni', '0', '108', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('238', '35', 'Meranti', '0', '113', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('239', '35', 'Minyak kayu putih', '0', '118', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('240', '35', 'Nilam', '0', '104', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('241', '35', 'Rotan', '0', '100', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('242', '35', 'Rumbia', '0', '259', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('243', '35', 'Sagu', '0', '106', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('244', '35', 'Sarang burung', '0', '112', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('245', '37', 'BATANG/TH', '0', '1746', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('246', '37', 'BUAH/TH ', '0', '1013', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('247', '37', 'EKOR/TH ', '0', '1745', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('248', '37', 'JENIS/TH', '0', '965', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('249', '37', 'KG/TH', '0', '960', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('250', '37', 'LITER/TH', '0', '962', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('251', '37', 'M/TH', '0', '963', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('252', '37', 'M3/TH', '0', '961', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('253', '37', 'TON/TH', '0', '966', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('254', '37', 'UNIT/TH', '0', '964', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('255', '39', 'Dijual ke Lumbung Pangan Desa/kel', '0', '493', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('256', '39', 'Dijual ke pasar', '0', '489', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('257', '39', 'Dijual langsung ke konsumen', '0', '488', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('258', '39', 'Dijual melalui KUD', '0', '490', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('259', '39', 'Dijual melalui Pengecer', '0', '492', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('260', '39', 'Dijual melalui Tengkulak', '0', '491', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('261', '39', 'Tidak dijual', '0', '494', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('262', '40', 'Angsa', '0', '131', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('263', '40', 'Anjing', '0', '135', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('264', '40', 'Ayam kampung', '0', '125', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('265', '40', 'Babi', '0', '124', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('266', '40', 'Bebek', '0', '127', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('267', '40', 'Buaya', '0', '145', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('268', '40', 'Burung beo', '0', '142', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('269', '40', 'Burung cendrawasih', '0', '140', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('270', '40', 'Burung kakatua', '0', '141', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('271', '40', 'Burung langka lainnya', '0', '144', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('272', '40', 'Burung merak', '0', '143', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('273', '40', 'Burung Merpati', '0', '244', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('274', '40', 'Burung onta', '0', '138', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('275', '40', 'Burung puyuh', '0', '132', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('276', '40', 'Domba', '0', '130', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('277', '40', 'Jenis ayam broiler', '0', '126', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('278', '40', 'Kambing', '0', '129', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('279', '40', 'Kelinci', '0', '133', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('280', '40', 'Kerbau', '0', '123', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('281', '40', 'Kucing', '0', '136', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('282', '40', 'Kuda', '0', '128', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('283', '40', 'Sapi', '0', '122', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('284', '40', 'Tuna', '0', '146', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('285', '40', 'Ular cobra', '0', '137', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('286', '40', 'Ular pithon', '0', '139', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('287', '42', 'BATANG/TH', '0', '1746', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('288', '42', 'BUAH/TH ', '0', '1013', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('289', '42', 'EKOR/TH ', '0', '1745', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('290', '42', 'JENIS/TH', '0', '965', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('291', '42', 'KG/TH', '0', '960', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('292', '42', 'LITER/TH', '0', '962', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('293', '42', 'M/TH', '0', '963', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('294', '42', 'M3/TH', '0', '961', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('295', '42', 'TON/TH', '0', '966', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('296', '42', 'UNIT/TH', '0', '964', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('297', '44', 'Dijual ke Lumbung Pangan Desa/kel', '0', '493', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('298', '44', 'Dijual ke pasar', '0', '489', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('299', '44', 'Dijual langsung ke konsumen', '0', '488', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('300', '44', 'Dijual melalui KUD', '0', '490', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('301', '44', 'Dijual melalui Pengecer', '0', '492', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('302', '44', 'Dijual melalui Tengkulak', '0', '491', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('303', '44', 'Tidak dijual', '0', '494', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('304', '45', 'Ayam-ayam', '0', '168', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('305', '45', 'Bandeng', '0', '171', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('306', '45', 'Barabara', '0', '165', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('307', '45', 'Baronang', '0', '160', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('308', '45', 'Bawal', '0', '159', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('309', '45', 'Belanak', '0', '155', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('310', '45', 'Belut', '0', '184', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('311', '45', 'Cucut', '0', '166', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('312', '45', 'Cumi', '0', '156', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('313', '45', 'Gabus', '0', '179', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('314', '45', 'Gurame', '0', '183', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('315', '45', 'Gurita', '0', '157', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('316', '45', 'Hiu', '0', '149', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('317', '45', 'Ikan ekor kuning', '0', '162', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('318', '45', 'Jambal', '0', '152', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('319', '45', 'Kakap', '0', '150', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('320', '45', 'Katak', '0', '188', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('321', '45', 'Kembung', '0', '161', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('322', '45', 'Kepiting', '0', '174', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('323', '45', 'Kerang', '0', '173', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('324', '45', 'Sunuk', '0', '163', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('325', '45', 'Kodok', '0', '187', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('326', '45', 'Kulit kerang', '0', '209', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('327', '45', 'Kuwe', '0', '154', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('328', '45', 'Layur', '0', '167', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('329', '45', 'Lele', '0', '178', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('330', '45', 'Mas', '0', '175', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('331', '45', 'Mujair', '0', '177', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('332', '45', 'Nener', '0', '172', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('333', '45', 'Nila', '0', '181', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('334', '45', 'Pari', '0', '153', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('335', '45', 'Patin', '0', '180', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('336', '45', 'Penyu', '0', '185', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('337', '45', 'Rajungan', '0', '176', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('338', '45', 'Rumput laut', '0', '186', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('339', '45', 'Salmon', '0', '147', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('340', '45', 'Sarden', '0', '158', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('341', '45', 'Sepat', '0', '182', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('342', '45', 'Tembang', '0', '170', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('343', '45', 'Tenggiri', '0', '151', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('344', '45', 'Teri', '0', '254', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('345', '45', 'Teripang', '0', '164', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('346', '45', 'Tongkol/cakalang', '0', '148', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('347', '45', 'Tuna', '0', '251', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('348', '45', 'Udang/lobster', '0', '169', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('349', '47', 'BATANG/TH', '0', '1746', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('350', '47', 'BUAH/TH ', '0', '1013', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('351', '47', 'EKOR/TH ', '0', '1745', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('352', '47', 'JENIS/TH', '0', '965', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('353', '47', 'KG/TH', '0', '960', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('354', '47', 'LITER/TH', '0', '962', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('355', '47', 'M/TH', '0', '963', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('356', '47', 'M3/TH', '0', '961', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('357', '47', 'TON/TH', '0', '966', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('358', '47', 'UNIT/TH', '0', '964', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('359', '49', 'Dijual ke Lumbung Pangan Desa/kel', '0', '493', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('360', '49', 'Dijual ke pasar', '0', '489', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('361', '49', 'Dijual langsung ke konsumen', '0', '488', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('362', '49', 'Dijual melalui KUD', '0', '490', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('363', '49', 'Dijual melalui Pengecer', '0', '492', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('364', '49', 'Dijual melalui Tengkulak', '0', '491', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('365', '49', 'Tidak dijual', '0', '494', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('366', '50', 'Aluminium', '0', '189', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('367', '50', 'Batu apung', '0', '190', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('368', '50', 'Batu cadas', '0', '191', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('369', '50', 'Batu Gamping', '0', '192', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('370', '50', 'Batu Gips', '0', '193', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('371', '50', 'Batu Granit', '0', '194', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('372', '50', 'Batu gunung', '0', '195', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('373', '50', 'Batu kali', '0', '196', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('374', '50', 'Batu kapur', '0', '197', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('375', '50', 'Batu marmer', '0', '198', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('376', '50', 'Batu Putih', '0', '199', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('377', '50', 'Batu Trass', '0', '200', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('378', '50', 'Batubara', '0', '201', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('379', '50', 'Belerang', '0', '202', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('380', '50', 'Biji Besi', '0', '203', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('381', '50', 'Bouxit', '0', '204', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('382', '50', 'Emas', '0', '205', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('383', '50', 'Garam', '0', '206', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('384', '50', 'Gas Alam', '0', '207', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('385', '50', 'Gips', '0', '208', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('386', '50', 'Kuningan', '0', '210', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('387', '50', 'Mangan', '0', '212', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('388', '50', 'Minyak', '0', '233', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('389', '50', 'Minyak Bumi', '0', '213', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('390', '50', 'Nikel', '0', '214', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('391', '50', 'Pasir', '0', '215', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('392', '50', 'Pasir Batu', '0', '216', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('393', '50', 'Pasir Besi', '0', '217', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('394', '50', 'Pasir kwarsa', '0', '218', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('395', '50', 'Perak', '0', '219', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('396', '50', 'Perunggu', '0', '220', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('397', '50', 'Tanah Garam', '0', '221', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('398', '50', 'Tanah liat', '0', '222', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('399', '50', 'Tembaga', '0', '223', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('400', '50', 'Timah', '0', '224', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('401', '50', 'Uranium', '0', '225', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('402', '53', 'BATANG/TH', '0', '1746', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('403', '53', 'BUAH/TH ', '0', '1013', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('404', '53', 'EKOR/TH ', '0', '1745', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('405', '53', 'JENIS/TH', '0', '965', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('406', '53', 'KG/TH', '0', '960', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('407', '53', 'LITER/TH', '0', '962', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('408', '53', 'M/TH', '0', '963', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('409', '53', 'M3/TH', '0', '961', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('410', '53', 'TON/TH', '0', '966', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('411', '53', 'UNIT/TH', '0', '964', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('412', '54', 'Dijual ke Lumbung Pangan Desa/kel', '0', '493', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('413', '54', 'Dijual ke pasar', '0', '489', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('414', '54', 'Dijual langsung ke konsumen', '0', '488', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('415', '54', 'Dijual melalui KUD', '0', '490', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('416', '54', 'Dijual melalui Pengecer', '0', '492', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('417', '54', 'Dijual melalui Tengkulak', '0', '491', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('418', '54', 'Tidak dijual', '0', '494', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('419', '55', 'Air liur burung walet', '0', '232', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('420', '55', 'Bulu', '0', '231', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('421', '55', 'Burung walet', '0', '134', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('422', '55', 'Cinderamata', '0', '235', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('423', '55', 'Daging', '0', '229', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('424', '55', 'Hiasan/lukisan', '0', '234', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('425', '55', 'Kerupuk Kulit', '0', '248', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('426', '55', 'Kulit', '0', '227', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('427', '55', 'Madu', '0', '230', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('428', '55', 'Susu', '0', '226', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('429', '55', 'Telur', '0', '228', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('430', '57', 'BATANG/TH', '0', '1746', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('431', '57', 'BUAH/TH ', '0', '1013', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('432', '57', 'EKOR/TH ', '0', '1745', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('433', '57', 'JENIS/TH', '0', '965', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('434', '57', 'KG/TH', '0', '960', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('435', '57', 'LITER/TH', '0', '962', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('436', '57', 'M/TH', '0', '963', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('437', '57', 'M3/TH', '0', '961', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('438', '57', 'TON/TH', '0', '966', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('439', '57', 'UNIT/TH', '0', '964', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('440', '59', 'Dijual ke Lumbung Pangan Desa/kel', '0', '493', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('441', '59', 'Dijual ke pasar', '0', '489', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('442', '59', 'Dijual langsung ke konsumen', '0', '488', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('443', '59', 'Dijual melalui KUD', '0', '490', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('444', '59', 'Dijual melalui Pengecer', '0', '492', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('445', '59', 'Dijual melalui Tengkulak', '0', '491', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('446', '59', 'Tidak dijual', '0', '494', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('447', '60', 'Jala', '0', '405', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('448', '60', 'Jermal', '0', '402', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('449', '60', 'Karamba', '0', '400', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('450', '60', 'Pancing', '0', '403', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('451', '60', 'Pukat', '0', '404', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('452', '60', 'Tambak', '0', '401', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('453', '62', 'Air minum/air baku', '0', '511', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('454', '62', 'Buang air besar', '0', '514', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('455', '62', 'Cuci dan mandi', '0', '512', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('456', '62', 'Irigasi', '0', '513', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('457', '62', 'Pembangkit listrik', '0', '515', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('458', '62', 'Prasarana transportasi', '0', '516', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('459', '62', 'Sumber air panas', '0', '517', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('460', '62', 'Usaha Perikanan', '0', '510', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('461', '63', 'Biara', '0', '687', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('462', '63', 'Kursus Bahasa', '0', '697', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('463', '63', 'Kursus Bela Diri', '0', '703', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('464', '63', 'Kursus Komputer', '0', '700', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('465', '63', 'Kursus Mengemudi', '0', '701', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('466', '63', 'Kursus Menjahit', '0', '698', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('467', '63', 'Kursus Montir', '0', '699', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('468', '63', 'Kursus Satpam', '0', '702', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('469', '63', 'Lembaga Kursus Keterampilan Swasta Katolik', '0', '692', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('470', '63', 'Lembaga Pendidikan Swasta Budha', '0', '695', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('471', '63', 'Lembaga Pendidikan Swasta Hindu', '0', '694', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('472', '63', 'Lembaga Pendidikan Swasta Konghucu', '0', '696', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('473', '63', 'Lembaga Pendidikan Swasta Kristen Protestan', '0', '693', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('474', '63', 'Madrasah Aliyah', '0', '682', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('475', '63', 'Madrasah Ibtidaiyah', '0', '680', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('476', '63', 'Madrasah Tsanawiyah', '0', '681', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('477', '63', 'Perguruan Tinggi', '0', '676', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('478', '63', 'Perguruan Tinggi Swasta Katolik', '0', '688', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('479', '63', 'Pondok Pesantren', '0', '677', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('480', '63', 'Rhaudatul Athfal (Tk)', '0', '679', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('481', '63', 'SD/Sederajat', '0', '673', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('482', '63', 'Sekolah Dasar Swasta Katolik', '0', '689', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('483', '63', 'Sekolah Tinggi Agama Islam', '0', '683', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('484', '63', 'Seminari Menengah', '0', '685', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('485', '63', 'Seminari Tinggi', '0', '686', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('486', '63', 'SLTA Swasta Katolik', '0', '691', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('487', '63', 'SLTP Swasta Katolik', '0', '690', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('488', '63', 'SMA/Sederajat', '0', '675', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('489', '63', 'SMP/Sederajat', '0', '674', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('490', '63', 'Taman Pendidikan Alqur?an', '0', '678', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('491', '63', 'TK/Preschool/Play Group', '0', '672', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('492', '63', 'Universitas Swasta Islam', '0', '684', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('493', '64', 'Tidak memiliki tanah', '0', '704', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('494', '64', 'Memiliki tanah kurang dari 0,1 ha', '0', '1744', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('495', '64', 'Memiliki tanah antara 0,1 - 0,2 ha', '0', '705', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('496', '64', 'Memiliki tanah antara 0,2 - 0,3 ha', '0', '706', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('497', '64', 'Memiliki tanah antara 0,3 - 0,4 ha', '0', '707', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('498', '64', 'Memiliki tanah antara 0,4 - 0,5 ha', '0', '708', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('499', '64', 'Memiliki tanah antara 0,5 - 0,6 ha', '0', '709', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('500', '64', 'Memiliki tanah antara 0,6 - 0,7 ha', '0', '710', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('501', '64', 'Memiliki tanah antara 0,7 - 0,8 ha', '0', '711', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('502', '64', 'Memiliki tanah antara 0,8 - 0,9 ha', '0', '712', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('503', '64', 'Memiliki tanah antara 0,9 - 1,0 ha', '0', '713', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('504', '64', 'Memiliki tanah antara 1,0 - 5,0 ha', '0', '714', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('505', '64', 'Memiliki tanah lebih dari 5,0 ha', '0', '715', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('506', '65', 'Memiiki cidemo/andong/dokar  ', '0', '718', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('507', '65', 'Memiliki bajaj/kancil', '0', '723', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('508', '65', 'Memiliki becak', '0', '717', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('509', '65', 'Memiliki bus penumpang/angkutan orang/barang', '0', '721', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('510', '65', 'Memiliki ojek motor/sepeda motor/bentor', '0', '716', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('511', '65', 'Memiliki perahu tidak bermotor', '0', '719', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('512', '65', 'Memiliki sepeda dayung', '0', '722', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('513', '65', 'Memiliki tongkang', '0', '720', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('514', '66', 'Memiliki alat pengolahan hasil hutan  ', '0', '731', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('515', '66', 'Memiliki alat pengolahan hasil perikanan  ', '0', '728', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('516', '66', 'Memiliki alat pengolahan hasil perkebunan', '0', '730', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('517', '66', 'Memiliki alat pengolahan hasil peternakan  ', '0', '729', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('518', '66', 'Memiliki alat produksi dan pengolah hasil Industri kerajinan keluarga skala kecil dan menengah  ', '0', '733', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('519', '66', 'Memiliki alat produksi dan pengolah hasil pertambangan  ', '0', '732', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('520', '66', 'Memiliki alat produksi dan pengolahan hasil industri bahan bakar dan gas skala keluarga/rumah tangga  ', '0', '734', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('521', '66', 'Memiliki kapal penangkap ikan  ', '0', '727', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('522', '66', 'Memiliki pabrik pengolahan hasil pertanian  ', '0', '726', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('523', '66', 'Memiliki penggilingan padi  ', '0', '724', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('524', '66', 'Memiliki traktor', '0', '725', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('525', '67', 'Bambu', '0', '737', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('526', '67', 'Dedaunan', '0', '740', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('527', '67', 'Kayu', '0', '736', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('528', '67', 'Pelepah kelapa/lontar/gebang  ', '0', '739', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('529', '67', 'Tanah Liat', '0', '738', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('530', '67', 'Tembok', '0', '735', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('531', '68', 'Kayu', '0', '743', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('532', '68', 'Keramik', '0', '741', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('533', '68', 'Semen', '0', '742', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('534', '68', 'Tanah', '0', '744', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('535', '69', 'Asbes', '0', '747', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('536', '69', 'Bambu', '0', '749', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('537', '69', 'Daun ilalang ', '0', '7752', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('538', '69', 'Daun lontar/gebang/enau  ', '0', '751', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('539', '69', 'Genteng', '0', '745', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('540', '69', 'Kayu', '0', '750', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('541', '69', 'Seng', '0', '746', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('542', '70', 'Berlangganan koran/majalah', '0', '787', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('543', '70', 'Memiliki buku surat berharga', '0', '766', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('544', '70', 'Memiliki buku tabungan bank', '0', '765', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('545', '70', 'Memiliki hiasan emas/berlian', '0', '764', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('546', '70', 'Memiliki HP CDMA', '0', '784', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('547', '70', 'Memiliki HP GSM', '0', '783', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('548', '70', 'Memiliki kapal barang', '0', '757', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('549', '70', 'Memiliki kapal penumpang', '0', '758', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('550', '70', 'Memiliki kapal pesiar', '0', '759', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('551', '70', 'Memiliki mobil pribadi dan sejenisnya', '0', '755', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('552', '70', 'Memiliki parabola', '0', '786', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('553', '70', 'Memiliki perahu bermotor', '0', '756', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('554', '70', 'Memiliki perusahaan industri besar', '0', '770', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('555', '70', 'Memiliki perusahaan industri kecil', '0', '772', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('556', '70', 'Memiliki perusahaan industri menengah', '0', '771', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('557', '70', 'Memiliki saham di perusahaan', '0', '781', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('558', '70', 'Memiliki sepeda motor pribadi', '0', '754', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('559', '70', 'Memiliki sertifikat bangunan', '0', '769', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('560', '70', 'Memiliki sertifikat deposito', '0', '767', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('561', '70', 'Memiliki sertifikat tanah', '0', '768', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('562', '70', 'Memiliki ternak besar', '0', '762', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('563', '70', 'Memiliki ternak kecil', '0', '763', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('564', '70', 'Memiliki TV dan elektronik sejenis lainnya', '0', '753', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('565', '70', 'Memiliki usaha di pasar desa', '0', '779', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('566', '70', 'Memiliki usaha di pasar swalayan', '0', '777', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('567', '70', 'Memiliki usaha di pasar tradisional', '0', '778', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('568', '70', 'Memiliki usaha pasar swalayan', '0', '776', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('569', '70', 'Memiliki usaha perikanan', '0', '773', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('570', '70', 'Memiliki usaha perkebunan', '0', '775', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('571', '70', 'Memiliki usaha peternakan', '0', '774', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('572', '70', 'Memiliki usaha transportasi/pengangkutan', '0', '780', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('573', '70', 'Memiliki Usaha Wartel', '0', '785', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('574', '70', 'Memiliki/menyewa helikopter pribadi', '0', '760', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('575', '70', 'Memiliki/menyewa pesawat terbang pribadi', '0', '761', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('576', '70', 'Pelanggan Telkom', '0', '782', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('577', '71', 'Ibu hamil melahirkan', '0', '796', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('578', '71', 'Ibu hamil periksa di Bidan Praktek', '0', '792', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('579', '71', 'Ibu hamil periksa di Dokter Praktek', '0', '791', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('580', '71', 'Ibu hamil periksa di Dukun Terlatih', '0', '793', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('581', '71', 'Ibu hamil periksa di Posyandu', '0', '788', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('582', '71', 'Ibu hamil periksa di Puskesmas', '0', '789', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('583', '71', 'Ibu hamil periksa di Rumah Sakit', '0', '790', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('584', '71', 'Ibu hamil tidak periksa kesehatan', '0', '794', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('585', '71', 'Ibu hamil yang meninggal', '0', '795', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('586', '71', 'Ibu nifas sakit', '0', '797', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('587', '71', 'Ibu nifas sehat', '0', '799', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('588', '71', 'Kematian ibu nifas', '0', '798', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('589', '71', 'Kematian ibu saat melahirkan', '0', '800', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('590', '72', 'Bayi 0-5 tahun hidup yang menderita kelainan organ tubuh, fisik dan mental  ', '0', '807', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('591', '72', 'Bayi lahir berat kurang dari 2,5 kg', '0', '805', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('592', '72', 'Bayi lahir berat lebih dari 4 kg', '0', '806', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('593', '72', 'Bayi lahir hidup cacat', '0', '803', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('594', '72', 'Bayi lahir hidup normal', '0', '802', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('595', '72', 'Bayi lahir mati', '0', '804', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('596', '72', 'Keguguran kandungan', '0', '801', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('597', '73', 'Rumah dukun', '0', '815', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('598', '73', 'Rumah sendiri', '0', '816', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('599', '73', 'Tempat persalinan Balai Kesehatan Ibu Anak', '0', '812', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('600', '73', 'Tempat persalinan Polindes', '0', '811', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('601', '73', 'Tempat persalinan Puskesmas', '0', '810', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('602', '73', 'Tempat persalinan Rumah Bersalin', '0', '809', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('603', '73', 'Tempat persalinan rumah praktek bidan', '0', '813', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('604', '73', 'Tempat persalinan Rumah Sakit Umum', '0', '808', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('605', '73', 'Tempat praktek dokter', '0', '814', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('606', '74', 'Persalinan ditolong bidan', '0', '818', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('607', '74', 'Persalinan ditolong Dokter', '0', '817', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('608', '74', 'Persalinan ditolong dukun bersalin', '0', '820', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('609', '74', 'Persalinan ditolong keluarga', '0', '821', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('610', '74', 'Persalinan ditolong perawat', '0', '819', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('611', '75', 'BCG', '0', '823', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('612', '75', 'Cacar', '0', '830', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('613', '75', 'Campak', '0', '829', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('614', '75', 'DPT-1', '0', '822', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('615', '75', 'DPT-2', '0', '825', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('616', '75', 'DPT-3', '0', '828', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('617', '75', 'Polio -1', '0', '824', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('618', '75', 'Polio-2', '0', '826', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('619', '75', 'Polio-3', '0', '827', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('620', '75', 'Sudah Semua', '0', '831', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('621', '76', 'Busung Lapar', '0', '838', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('622', '76', 'Cikungunya', '0', '836', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('623', '76', 'Demam Berdarah', '0', '833', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('624', '76', 'Flu Burung', '0', '837', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('625', '76', 'Kelainan fisik', '0', '841', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('626', '76', 'Kelainan mental', '0', '842', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('627', '76', 'Kelaparan', '0', '839', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('628', '76', 'Kolera', '0', '834', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('629', '76', 'Kulit Bersisik', '0', '840', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('630', '76', 'Muntaber', '0', '832', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('631', '76', 'Polio', '0', '835', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('632', '77', 'Biasa buang air besar di sungai/parit/kebun/hutan  ', '0', '845', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('633', '77', 'Memiliki WC yang darurat/kurang memenuhi standar kesehatan  ', '0', '844', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('634', '77', 'Memiliki WC yang permanen/semipermanen  ', '0', '843', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('635', '77', 'Menggunakan fasilitas MCK umum  ', '0', '846', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('636', '78', 'Belum tentu sehari makan 1 kali  ', '0', '851', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('637', '78', 'Kebiasaan makan dalam sehari 1 kali  ', '0', '847', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('638', '78', 'Kebiasaan makan sehari 2 kali  ', '0', '848', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('639', '78', 'Kebiasaan makan sehari 3 kali  ', '0', '849', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('640', '78', 'Kebiasaan makan sehari lebih dari 3 kali  ', '0', '850', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('641', '79', 'Dokter/puskesmas/mantri kesehatan/perawat/ bidan/ posyandu  ', '0', '853', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('642', '79', 'Dukun Terlatih  ', '0', '852', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('643', '79', 'Obat tradisional dari dukun pengobatan alternatif  ', '0', '854', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('644', '79', 'Obat tradisional dari keluarga sendiri  ', '0', '856', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('645', '79', 'Paranormal  ', '0', '855', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('646', '79', 'Tidak diobati  ', '0', '857', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('647', '80', 'Balita bergizi baik  ', '0', '859', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('648', '80', 'Balita bergizi buruk  ', '0', '858', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('649', '80', 'Balita bergizi kurang  ', '0', '860', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('650', '80', 'Balita bergizi lebih', '0', '861', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('651', '81', 'Asma', '0', '874', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('652', '81', 'Diabetes Melitus', '0', '867', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('653', '81', 'Gila/stress', '0', '872', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('654', '81', 'Ginjal', '0', '868', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('655', '81', 'HIV/AIDS', '0', '871', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('656', '81', 'Jantung', '0', '862', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('657', '81', 'Kanker', '0', '865', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('658', '81', 'Lepra/Kusta', '0', '870', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('659', '81', 'Lever', '0', '863', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('660', '81', 'Malaria', '0', '869', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('661', '81', 'Paru-paru', '0', '864', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('662', '81', 'Stroke', '0', '866', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('663', '81', 'TBC', '0', '873', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('664', '82', 'Anak yatim/piatu dalam keluarga akibat konflik Sara  ', '0', '878', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('665', '82', 'Janda/duda dalam keluarga akibat konflik Sara  ', '0', '877', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('666', '82', 'Korban luka dalam keluarga akibat konflik Sara  ', '0', '875', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('667', '82', 'Korban meninggal dalam keluarga akibat konflik Sara ', '0', '876', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('668', '83', 'Korban jiwa akibat perkelahian dalam keluarga  ', '0', '879', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('669', '83', 'Korban luka parah akibat perkelahian dalam keluarga ', '0', '880', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('670', '84', 'Korban pencurian, perampokan dalam keluarga  ', '0', '881', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('671', '85', 'Korban penjarahan yang pelakunya anggota keluarga  ', '0', '882', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('672', '85', 'Korban penjarahan yang pelakunya bukan anggota keluarga  ', '0', '883', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('673', '86', 'Anggota keluarga yang memiliki kebiasaan berjudi', '0', '884', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('674', '87', 'Anggota keluarga mengkonsumsi Miras yang dilarang  ', '0', '885', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('675', '87', 'Anggota keluarga yang mengkonsumsi Narkoba ', '0', '886', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('676', '88', 'Korban pembunuhan dalam keluarga yang pelakunya anggota keluarga  ', '0', '887', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('677', '88', 'Korban pembunuhan dalam keluarga yang pelakunya bukan anggota keluarga', '0', '888', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('678', '89', 'Korban penculikan yang pelakunya anggota keluarga  ', '0', '889', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('679', '89', 'Korban penculikan yang pelakunya bukan anggota keluarga  ', '0', '890', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('680', '90', 'Korban kehamilan di luar nikah yang sah menurut hukum adat  ', '0', '893', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('681', '90', 'Korban kehamilan yang tidak dinikahi pelakunya  ', '0', '894', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('682', '90', 'Korban kehamilan yang tidak/belum disahkan secara hukum agama dan hukum negara  ', '0', '895', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('683', '90', 'Korban perkosaan/pelecehan seksual yang pelakunya anggota keluarga  ', '0', '891', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('684', '90', 'Korban perkosaan/pelecehan seksual yang pelakunya bukan anggota keluarga  ', '0', '892', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('685', '91', 'Adanya pemukulan/tindakan fisik antara anak dengan anggota keluarga lain  ', '0', '903', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('686', '91', 'Adanya pemukulan/tindakan fisik antara anak dengan orang tua  ', '0', '901', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('687', '91', 'Adanya pemukulan/tindakan fisik antara anak dengan pembantu  ', '0', '905', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('688', '91', 'Adanya pemukulan/tindakan fisik antara orang tua dengan anak  ', '0', '902', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('689', '91', 'Adanya pemukulan/tindakan fisik antara orang tua dengan orang tua  ', '0', '904', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('690', '91', 'Adanya pemukulan/tindakan fisik antara orang tua dengan pembantu  ', '0', '906', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('691', '91', 'Adanya pertengkaran dalam keluarga antara anak dan anak  ', '0', '897', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('692', '91', 'Adanya pertengkaran dalam keluarga antara anak dan anggota keluarga lain  ', '0', '900', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('693', '91', 'Adanya pertengkaran dalam keluarga antara anak dan orang tua  ', '0', '896', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('694', '91', 'Adanya pertengkaran dalam keluarga antara anak dan pembantu  ', '0', '899', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('695', '91', 'Adanya pertengkaran dalam keluarga antara ayah dan ibu/orang tua ', '0', '898', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('696', '92', 'Ada anak anggota keluarga yang mengemis', '0', '918', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('697', '92', 'Ada anak dan anggota keluarga yang menjadi pengamen', '0', '919', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('698', '92', 'Ada anak yang membantu orang tua mendapatkan penghasilan', '0', '934', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('699', '92', 'Ada anggota keluarga eks narapidana', '0', '936', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('700', '92', 'Ada anggota keluarga yang bermalam/tidur di jalanan/emperan toko/pusat keramaian/kolong jembatan', '0', '916', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('701', '92', 'Ada anggota keluarga yang cacat fisik', '0', '921', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('702', '92', 'Ada anggota keluarga yang cacat mental', '0', '922', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('703', '92', 'Ada anggota keluarga yang gila/stres', '0', '920', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('704', '92', 'Ada anggota keluarga yang kelainan kulit', '0', '923', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('705', '92', 'Ada anggota keluarga yang menganggur', '0', '933', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('706', '92', 'Ada anggota keluarga yang mengemis', '0', '915', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('707', '92', 'Ada anggota keluarga yang menjadi pengamen', '0', '924', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('708', '92', 'Ada anggota keluarga yang termasuk manusia lanjut usia (di atas 60 thn)', '0', '917', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('709', '92', 'Anggota keluarga yatim/piatu', '0', '925', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('710', '92', 'Keluarga duda', '0', '927', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('711', '92', 'Keluarga janda', '0', '926', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('712', '92', 'Kepala keluarga perempuan', '0', '935', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('713', '92', 'Tinggal di bantaran sungai', '0', '928', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('714', '92', 'Tinggal di daerah kawasan kering, tandus dan kritis', '0', '947', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('715', '92', 'Tinggal di daerah rawan bencana tsunami', '0', '938', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('716', '92', 'Tinggal di desa/kelurahan rawan air bersih', '0', '944', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('717', '92', 'Tinggal di desa/kelurahan rawan banjir', '0', '937', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('718', '92', 'Tinggal di desa/kelurahan rawan bencana kekeringan', '0', '945', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('719', '92', 'Tinggal di desa/kelurahan rawan gagal tanam/panen', '0', '946', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('720', '92', 'Tinggal di desa/kelurahan rawan gunung meletus', '0', '939', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('721', '92', 'Tinggal di desa/kelurahan rawan kelaparan', '0', '943', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('722', '92', 'Tinggal di jalur hijau', '0', '929', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('723', '92', 'Tinggal di jalur rawan gempa bumi', '0', '940', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('724', '92', 'Tinggal di kawasan jalur rel kereta api', '0', '930', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('725', '92', 'Tinggal di kawasan jalur sutet', '0', '931', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('726', '92', 'Tinggal di kawasan kumuh dan padat pemukiman', '0', '932', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('727', '92', 'Tinggal di kawasan rawan kebakaran', '0', '942', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('728', '92', 'Tinggal di kawasan rawan tanah longsor', '0', '941', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('729', '94', 'Kepala Keluarga', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('730', '94', 'Suami', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('731', '94', 'Istri', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('732', '94', 'Anak Kandung', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('733', '94', 'Anak Angkat', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('734', '94', 'Ayah', '0', '6', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('735', '94', 'Ibu', '0', '7', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('736', '94', 'Paman', '0', '8', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('737', '94', 'Tante', '0', '9', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('738', '94', 'Kakak', '0', '10', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('739', '94', 'Adik', '0', '11', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('740', '94', 'Kakek', '0', '12', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('741', '94', 'Nenek', '0', '13', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('742', '94', 'Sepupu', '0', '14', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('743', '94', 'Keponakan', '0', '15', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('744', '94', 'Teman', '0', '16', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('745', '94', 'Mertua', '0', '17', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('746', '94', 'Cucu', '0', '18', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('747', '94', 'Famili lain', '0', '19', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('748', '94', 'Menantu', '0', '21', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('749', '94', 'Lainnya', '0', '22', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('750', '94', 'Anak Tiri', '0', '23', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('751', '95', 'Kawin', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('752', '95', 'Belum Kawin', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('753', '95', 'Janda/Duda', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('754', '96', 'Islam', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('755', '96', 'Kristen Protestan', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('756', '96', 'Kristen Katolik', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('757', '96', 'Hindu', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('758', '96', 'Budha', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('759', '96', 'Konghucu', '0', '6', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('760', '96', 'Aliran Kepercayaan Kepada Tuhan YME', '0', '7', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('761', '97', 'O', '0', '0', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('762', '97', 'A', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('763', '97', 'B', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('764', '97', 'AB', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('765', '97', 'Tidak Tahu', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('766', '98', 'Warga Negara Indonesia', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('767', '98', 'Warga Negara Asing', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('768', '98', 'Dwi Kewarganegaraan', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('769', '100', 'Belum masuk TK/Kelompok Bermain', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('770', '100', 'Sedang TK/Kelompok Bermain', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('771', '100', 'Tidak pernah sekolah', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('772', '100', 'Sedang SD/sederajat', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('773', '100', 'Tamat SD/sederajat', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('774', '100', 'Tidak tamat SD/sederajat', '0', '6', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('775', '100', 'Sedang SLTP/Sederajat', '0', '7', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('776', '100', 'Tamat SLTP/sederajat', '0', '8', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('777', '100', 'Sedang SLTA/sederajat', '0', '9', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('778', '100', 'Tamat SLTA/sederajat', '0', '10', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('779', '100', 'Sedang D-1/sederajat', '0', '11', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('780', '100', 'Tamat D-1/sederajat', '0', '12', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('781', '100', 'Sedang D-2/sederajat', '0', '13', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('782', '100', 'Tamat D-2/sederajat', '0', '14', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('783', '100', 'Sedang D-3/sederajat', '0', '15', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('784', '100', 'Tamat D-4/sederajat', '0', '16', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('785', '100', 'Sedang S-1/sederajat', '0', '17', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('786', '100', 'Tamat S-1/sederajat', '0', '18', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('787', '100', 'Sedang S-2/sederajat', '0', '19', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('788', '100', 'Tamat S-2/sederajat', '0', '20', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('789', '100', 'Sedang S-3/sederajat', '0', '21', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('790', '100', 'Tamat S-3/sederajat', '0', '22', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('791', '100', 'Sedang SLB A/sederajat', '0', '23', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('792', '100', 'Tamat SLB A/sederajat', '0', '24', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('793', '100', 'Sedang SLB B/sederajat', '0', '25', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('794', '100', 'Tamat SLB B/sederajat', '0', '26', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('795', '100', 'Sedang SLB C/sederajat', '0', '27', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('796', '100', 'Tamat SLB C/sederajat', '0', '28', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('797', '100', 'Tidak dapat membaca dan menulis huruf Latin/Arab', '0', '29', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('798', '100', 'Tamat D-3/sederajat', '0', '30', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('799', '101', 'Petani', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('800', '101', 'Buruh Tani', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('801', '101', 'Buruh Migran Perempuan', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('802', '101', 'Buruh Migran laki-laki', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('803', '101', 'Pegawai Negeri Sipil', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('804', '101', 'Karyawan Swasta', '0', '6', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('805', '101', 'Pengrajin', '0', '7', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('806', '101', 'Pedagang barang kelontong', '0', '8', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('807', '101', 'Peternak', '0', '9', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('808', '101', 'Nelayan', '0', '10', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('809', '101', 'Montir', '0', '11', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('810', '101', 'Dokter swasta', '0', '12', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('811', '101', 'Perawat swasta', '0', '13', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('812', '101', 'Bidan swasta', '0', '14', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('813', '101', 'Ahli Pengobatan Alternatif', '0', '15', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('814', '101', 'TNI', '0', '16', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('815', '101', 'POLRI', '0', '17', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('816', '101', 'Pengusaha kecil, menengah dan besar', '0', '18', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('817', '101', 'Guru swasta', '0', '19', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('818', '101', 'Dosen swasta', '0', '20', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('819', '101', 'Seniman/artis', '0', '21', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('820', '101', 'Pedagang Keliling', '0', '22', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('821', '101', 'Penambang', '0', '23', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('822', '101', 'Tukang Kayu', '0', '24', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('823', '101', 'Tukang Batu', '0', '25', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('824', '101', 'Tukang cuci', '0', '26', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('825', '101', 'Pembantu rumah tangga', '0', '27', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('826', '101', 'Pengacara', '0', '28', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('827', '101', 'Notaris', '0', '29', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('828', '101', 'Dukun Tradisional', '0', '30', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('829', '101', 'Arsitektur/Desainer', '0', '31', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('830', '101', 'Karyawan Perusahaan Swasta', '0', '32', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('831', '101', 'Karyawan Perusahaan Pemerintah', '0', '33', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('832', '101', 'Wiraswasta', '0', '34', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('833', '101', 'Konsultan Manajemen dan Teknis', '0', '35', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('834', '101', 'Tidak Mempunyai Pekerjaan Tetap', '0', '36', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('835', '101', 'Belum Bekerja', '0', '37', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('836', '101', 'Pelajar', '0', '38', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('837', '101', 'Ibu Rumah Tangga', '0', '39', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('838', '101', 'Purnawirawan/Pensiunan', '0', '40', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('839', '101', 'Perangkat Desa', '0', '41', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('840', '101', 'Buruh Harian Lepas', '0', '42', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('841', '101', 'Pemilik perusahaan', '0', '55', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('842', '101', 'Pengusaha perdagangan hasil bumi', '0', '56', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('843', '101', 'Buruh jasa perdagangan hasil bumi', '0', '57', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('844', '101', 'Pemilik usaha jasa transportasi dan perhubungan', '0', '58', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('845', '101', 'Buruh usaha jasa transportasi dan perhubungan', '0', '59', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('846', '101', 'Pemilik usaha informasi dan komunikasi', '0', '60', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('847', '101', 'Buruh usaha jasa informasi dan komunikasi', '0', '61', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('848', '101', 'Kontraktor', '0', '62', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('849', '101', 'Pemilik usaha jasa hiburan dan pariwisata', '0', '63', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('850', '101', 'Buruh usaha jasa hiburan dan pariwisata', '0', '64', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('851', '101', 'Pemilik usaha hotel dan penginapan lainnya ', '0', '65', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('852', '101', 'Buruh usaha hotel dan penginapan lainnya', '0', '66', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('853', '101', 'Pemilik usaha warung, rumah makan dan restoran', '0', '67', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('854', '101', 'Dukun/paranormal/supranatural', '0', '68', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('855', '101', 'Jasa pengobatan alternatif', '0', '69', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('856', '101', 'Sopir', '0', '70', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('857', '101', 'Usaha jasa pengerah tenaga kerja', '0', '71', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('858', '101', 'Jasa penyewaan peralatan pesta', '0', '74', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('859', '101', 'Pemulung', '0', '75', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('860', '101', 'Pengrajin industri rumah tangga lainnya', '0', '76', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('861', '101', 'Tukang Anyaman', '0', '77', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('862', '101', 'Tukang Jahit', '0', '78', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('863', '101', 'Tukang Kue', '0', '79', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('864', '101', 'Tukang Rias', '0', '80', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('865', '101', 'Tukang Sumur', '0', '81', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('866', '101', 'Jasa Konsultansi Manajemen dan Teknis ', '0', '82', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('867', '101', 'Juru Masak', '0', '83', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('868', '101', 'Karyawan Honorer', '0', '84', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('869', '101', 'Pialang', '0', '85', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('870', '101', 'Pskiater/Psikolog', '0', '86', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('871', '101', 'Wartawan', '0', '87', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('872', '101', 'Tukang Cukur', '0', '88', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('873', '101', 'Tukang Las', '0', '89', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('874', '101', 'Tukang Gigi', '0', '90', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('875', '101', 'Tukang Listrik', '0', '91', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('876', '101', 'Pemuka Agama', '0', '92', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('877', '101', 'Anggota Legislatif', '0', '93', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('878', '101', 'Kepala Daerah', '0', '94', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('879', '101', 'Apoteker', '0', '96', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('880', '101', 'Presiden', '0', '97', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('881', '101', 'Wakil presiden', '0', '98', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('882', '101', 'Anggota Mahkamah Konstitusi', '0', '99', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('883', '101', 'Anggota Kabinet Kementrian', '0', '100', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('884', '101', 'Duta besar', '0', '101', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('885', '101', 'Gubernur', '0', '102', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('886', '101', 'Wakil bupati', '0', '103', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('887', '101', 'Pilot', '0', '104', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('888', '101', 'Penyiar radio', '0', '105', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('889', '101', 'Pelaut', '0', '106', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('890', '101', 'Peneliti', '0', '107', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('891', '101', 'Satpam/Security', '0', '108', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('892', '101', 'Wakil Gubernur', '0', '109', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('893', '101', 'Bupati/Walikota', '0', '110', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('894', '101', 'Akuntan', '0', '112', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('895', '104', 'Menggunakan alat kontrasepsi Suntik', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('896', '104', 'Menggunakan alat kontrasepsi Spiral', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('897', '104', 'Menggunakan alat kontrasepsi Kondom', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('898', '104', 'Menggunakan alat kontrasepsi vasektomi', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('899', '104', 'Menggunakan alat kontrasepsi Tubektomi', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('900', '104', 'Menggunakan alat kontrasepsi Pil', '0', '6', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('901', '104', 'Menggunakan metode KB Alamiah/Kalender', '0', '7', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('902', '104', 'Menggunakan obat tradisional', '0', '8', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('903', '104', 'Tidak Menggunakan alat kontrasepsi /metode KBA', '0', '9', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('904', '104', 'Susuk KB (Implant)', '0', '10', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('905', '104', 'Tidak Menjawab', '0', '11', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('906', '105', 'Tuna Rungu', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('907', '105', 'Tuna Wicara', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('908', '105', 'Tuna Netra', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('909', '105', 'Lumpuh', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('910', '105', 'Sumbing', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('911', '106', 'Idiot', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('912', '106', 'Gila', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('913', '106', 'Stress', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('914', '107', 'Wajib Pajak Bumi dan Bangunan', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('915', '107', 'Wajib Pajak Penghasilan Perorangan', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('916', '107', 'Wajib Pajak Badan/Perusahaan', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('917', '107', 'Wajib Pajak Kendaraan Bermotor', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('918', '107', 'Wajib Retribusi Kebersihan', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('919', '107', 'Wajib Retribusi Keamanan', '0', '6', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('920', '108', 'Kepala Desa/Lurah', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('921', '108', 'Sekretaris Desa/Kelurahan', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('922', '108', 'Kepala Urusan', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('923', '108', 'Kepala Dusun/Lingkungan', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('924', '108', 'Staf Desa/Kelurahan', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('925', '108', 'Ketua BPD', '0', '6', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('926', '108', 'Wakil Ketua BPD', '0', '7', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('927', '108', 'Sekretaris BPD', '0', '8', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('928', '108', 'Anggota BPD', '0', '9', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('929', '109', 'Pengurus RT', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('930', '109', 'Anggota Pengurus RT', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('931', '109', 'Pengurus RW', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('932', '109', 'Anggota Pengurus RW', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('933', '109', 'Pengurus LKMD/K/LPM', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('934', '109', 'Anggota LKMD/K/LPM', '0', '6', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('935', '109', 'Pengurus PKK', '0', '7', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('936', '109', 'Anggota PKK', '0', '8', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('937', '109', 'Pengurus Lembaga Adat', '0', '9', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('938', '109', 'Pengurus Karang Taruna', '0', '10', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('939', '109', 'Anggota Karang Taruna', '0', '11', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('940', '109', 'Pengurus Hansip/Linmas', '0', '12', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('941', '109', 'Pengurus Poskamling', '0', '13', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('942', '109', 'Pengurus Organisasi Perempuan', '0', '14', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('943', '109', 'Anggota Organisasi Perempuan', '0', '15', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('944', '109', 'Pengurus Organisasi Bapak-bapak', '0', '16', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('945', '109', 'Anggota Organisasi Bapak-bapak', '0', '17', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('946', '109', 'Pengurus Organisasi keagamaan', '0', '18', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('947', '109', 'Anggota Organisasi keagamaan', '0', '19', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('948', '109', 'Pengurus Organisasi profesi wartawan', '0', '20', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('949', '109', 'Anggota Organisasi profesi wartawan', '0', '21', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('950', '109', 'Pengurus Posyandu', '0', '22', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('951', '109', 'Pengurus Posyantekdes', '0', '23', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('952', '109', 'Pengurus Organisasi Kelompok Tani/Nelayan', '0', '24', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('953', '109', 'Anggota Organisasi Kelompok Tani/Nelayan', '0', '25', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('954', '109', 'Pengurus Lembaga Gotong royong', '0', '26', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('955', '109', 'Anggota Lembaga Gotong royong', '0', '27', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('956', '109', 'Pengurus Organisasi Profesi guru', '0', '28', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('957', '109', 'Anggota Organisasi Profesi guru', '0', '29', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('958', '109', 'Pengurus Organisasi profesi dokter/tenaga medis', '0', '30', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('959', '109', 'Anggota Organisasi profesi/tenaga medis', '0', '31', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('960', '109', 'Pengurus organisasi pensiunan', '0', '32', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('961', '109', 'Anggota organisasi pensiunan', '0', '33', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('962', '109', 'Pengurus organisasi pemirsa/pendengar', '0', '34', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('963', '109', 'Anggota organisasi pemirsa/pendengar', '0', '35', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('964', '109', 'Pengurus lembaga pencinta alam', '0', '36', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('965', '109', 'Anggota organisasi pencinta alam', '0', '37', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('966', '109', 'Pengurus organisasi pengembangan ilmu pengetahuan', '0', '38', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('967', '109', 'Anggota organisasi pengembangan ilmu pengetaahuan', '0', '39', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('968', '109', 'Pemilik yayasan', '0', '40', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('969', '109', 'Pengurus yayasan', '0', '41', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('970', '109', 'Anggota yayasan', '0', '42', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('971', '109', 'Pengurus Satgas Kebersihan', '0', '43', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('972', '109', 'Anggota Satgas Kebersihan', '0', '44', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('973', '109', 'Pengurus Satgas Kebakaran', '0', '45', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('974', '109', 'Anggota Satgas Kebakaran', '0', '46', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('975', '109', 'Pengurus Posko Penanggulangan Bencana', '0', '47', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('976', '109', 'Anggota Tim Penanggulangan Bencana', '0', '48', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('977', '110', 'Koperasi', '0', '1', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('978', '110', 'Unit Usaha Simpan Pinjam', '0', '2', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('979', '110', 'Industri Kerajinan Tangan', '0', '3', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('980', '110', 'Industri Pakaian', '0', '4', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('981', '110', 'Industri Usaha Makanan', '0', '5', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('982', '110', 'Industri Alat Rumah Tangga', '0', '6', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('983', '110', 'Industri Usaha Bahan Bangunan', '0', '7', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('984', '110', 'Industri Alat Pertanian', '0', '8', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('985', '110', 'Restoran', '0', '9', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('986', '110', 'Toko/ Swalayan', '0', '10', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('987', '110', 'Warung Kelontongan/Kios', '0', '11', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('988', '110', 'Angkutan Darat', '0', '12', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('989', '110', 'Angkutan Sungai', '0', '13', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('990', '110', 'Angkutan Laut', '0', '14', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('991', '110', 'Angkutan Udara', '0', '15', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('992', '110', 'Jasa Ekspedisi/Pengiriman Barang', '0', '16', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('993', '110', 'Tukang Sumur', '0', '17', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('994', '110', 'Usaha Pasar Harian', '0', '18', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('995', '110', 'Usaha Pasar Mingguan', '0', '19', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('996', '110', 'Usaha Pasar Ternak', '0', '20', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('997', '110', 'Usaha Pasar Hasil Bumi Dan Tambang', '0', '21', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('998', '110', 'Usaha Perdagangan Antar Pulau', '0', '22', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('999', '110', 'Pengijon', '0', '23', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1000', '110', 'Pedagang Pengumpul/Tengkulak', '0', '24', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1001', '110', 'Usaha Peternakan', '0', '25', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1002', '110', 'Usaha Perikanan', '0', '26', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1003', '110', 'Usaha Perkebunan', '0', '27', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1004', '110', 'Kelompok Simpan Pinjam', '0', '28', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1005', '110', 'Usaha Minuman', '0', '29', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1006', '110', 'Industri Farmasi', '0', '30', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1007', '110', 'Industri Karoseri', '0', '31', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1008', '110', 'Penitipan Kendaraan Bermotor', '0', '32', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1009', '110', 'Industri Perakitan Elektronik', '0', '33', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1010', '110', 'Pengolahan Kayu', '0', '34', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1011', '110', 'Bioskop', '0', '35', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1012', '110', 'Film Keliling', '0', '36', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1013', '110', 'Sandiwara/Drama', '0', '37', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1014', '110', 'Group Lawak', '0', '38', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1015', '110', 'Jaipongan', '0', '39', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1016', '110', 'Wayang Orang/Golek', '0', '40', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1017', '110', 'Group Musik/Band', '0', '41', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1018', '110', 'Group Vokal/Paduan Suara', '0', '42', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1019', '110', 'Usaha Persewaan Tenaga Listrik', '0', '43', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1020', '110', 'Usaha Pengecer Gas Dan Bahan Bakar Minyak', '0', '44', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1021', '110', 'Usaha Air Minum Dalam Kemasan', '0', '45', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1022', '110', 'Tukang Kayu', '0', '46', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1023', '110', 'Tukang Batu', '0', '47', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1024', '110', 'Tukang Jahit/Bordir', '0', '48', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1025', '110', 'Tukang Cukur', '0', '49', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1026', '110', 'Tukang Service Elektronik', '0', '50', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1027', '110', 'Tukang Besi', '0', '51', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1028', '110', 'Tukang Pijat/Urut', '0', '52', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1029', '110', 'Tukang Sumur', '0', '53', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1030', '110', 'Notaris', '0', '54', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1031', '110', 'Pengacara/Advokat', '0', '55', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1032', '110', 'Konsultan Manajemen', '0', '56', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1033', '110', 'Konsultan Teknis', '0', '57', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1034', '110', 'Pejabat Pembuat Akta Tanah', '0', '58', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1035', '110', 'Losmen', '0', '59', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1036', '110', 'Wisma', '0', '60', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1037', '110', 'Asrama', '0', '61', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1038', '110', 'Persewaan Kamar', '0', '62', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1039', '110', 'Kontrakan Rumah', '0', '63', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1040', '110', 'Mess', '0', '64', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1041', '110', 'Hotel', '0', '65', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1042', '110', 'Home Stay', '0', '66', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1043', '110', 'Villa', '0', '67', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1044', '110', 'Town House', '0', '68', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1045', '110', 'Usaha Asuransi', '0', '69', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1046', '110', 'Lembaga Keuangan Bukan Bank', '0', '70', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1047', '110', 'Lembaga Perkreditan Rakyat', '0', '71', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1048', '110', 'Pegadaian', '0', '72', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1049', '110', 'Bank Perkreditan Rakyat', '0', '73', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1050', '110', 'Usaha Penyewaan Alat Pesta', '0', '74', '1');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1051', '110', 'Usaha Pengolahan dan Penjualan Hasil Hutan', '0', '75', '1');


#
# TABLE STRUCTURE FOR: analisis_partisipasi
#

DROP TABLE IF EXISTS analisis_partisipasi;

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

DROP TABLE IF EXISTS analisis_periode;

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

INSERT INTO analisis_periode (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES ('1', '2', 'Pendataan 2014', '2', '2', 'ket', '2014');
INSERT INTO analisis_periode (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES ('2', '2', 'Pendataan 2015', '1', '1', 'nnn', '2015');
INSERT INTO analisis_periode (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES ('3', '3', 'Data Dasar Keluarga ', '1', '1', 'Pendataan Profil Desa', '2018');
INSERT INTO analisis_periode (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES ('4', '4', 'Data Anggota Keluarga', '1', '1', 'Pendataan Profil Desa', '2018');


#
# TABLE STRUCTURE FOR: analisis_ref_state
#

DROP TABLE IF EXISTS analisis_ref_state;

CREATE TABLE `analisis_ref_state` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO analisis_ref_state (`id`, `nama`) VALUES ('1', 'Belum Entri / Pendataan');
INSERT INTO analisis_ref_state (`id`, `nama`) VALUES ('2', 'Sedang Dalam Pendataan');
INSERT INTO analisis_ref_state (`id`, `nama`) VALUES ('3', 'Selesai Entri / Pendataan');


#
# TABLE STRUCTURE FOR: analisis_ref_subjek
#

DROP TABLE IF EXISTS analisis_ref_subjek;

CREATE TABLE `analisis_ref_subjek` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `subjek` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO analisis_ref_subjek (`id`, `subjek`) VALUES ('1', 'Penduduk');
INSERT INTO analisis_ref_subjek (`id`, `subjek`) VALUES ('2', 'Keluarga / KK');
INSERT INTO analisis_ref_subjek (`id`, `subjek`) VALUES ('3', 'Rumah Tangga');
INSERT INTO analisis_ref_subjek (`id`, `subjek`) VALUES ('4', 'Kelompok');


#
# TABLE STRUCTURE FOR: analisis_respon
#

DROP TABLE IF EXISTS analisis_respon;

CREATE TABLE `analisis_respon` (
  `id_indikator` int(11) NOT NULL,
  `id_parameter` int(11) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL,
  KEY `id_parameter` (`id_parameter`,`id_subjek`),
  KEY `id_periode` (`id_periode`),
  KEY `id_indikator` (`id_indikator`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '1', '129', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '6', '129', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '3', '254', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '4', '254', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '1', '298', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '5', '298', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '1', '304', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '5', '304', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '3', '308', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '6', '308', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '1', '309', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '4', '309', '1');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '3', '129', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '4', '129', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '1', '254', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '6', '254', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '2', '298', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '6', '298', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '3', '304', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '6', '304', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '2', '308', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '6', '308', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('1', '3', '309', '2');
INSERT INTO analisis_respon (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES ('2', '6', '309', '2');


#
# TABLE STRUCTURE FOR: analisis_respon_bukti
#

DROP TABLE IF EXISTS analisis_respon_bukti;

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

DROP TABLE IF EXISTS analisis_respon_hasil;

CREATE TABLE `analisis_respon_hasil` (
  `id_master` tinyint(4) NOT NULL,
  `id_periode` tinyint(4) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `akumulasi` double(8,3) NOT NULL,
  `tgl_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id_master` (`id_master`,`id_periode`,`id_subjek`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '1', '129', '25.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '1', '254', '5.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '1', '298', '17.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '1', '304', '17.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '1', '308', '21.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '1', '309', '9.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '2', '129', '5.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '2', '254', '25.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '2', '298', '24.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '2', '304', '21.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '2', '308', '24.000', '0000-00-00 00:00:00');
INSERT INTO analisis_respon_hasil (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES ('2', '2', '309', '21.000', '0000-00-00 00:00:00');


#
# TABLE STRUCTURE FOR: analisis_tipe_indikator
#

DROP TABLE IF EXISTS analisis_tipe_indikator;

CREATE TABLE `analisis_tipe_indikator` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `tipe` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO analisis_tipe_indikator (`id`, `tipe`) VALUES ('1', 'Pilihan (Tunggal)');
INSERT INTO analisis_tipe_indikator (`id`, `tipe`) VALUES ('2', 'Pilihan (Multivalue)');
INSERT INTO analisis_tipe_indikator (`id`, `tipe`) VALUES ('3', 'Isian Angka');
INSERT INTO analisis_tipe_indikator (`id`, `tipe`) VALUES ('4', 'Isian Tulisan');


#
# TABLE STRUCTURE FOR: area
#

DROP TABLE IF EXISTS area;

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

INSERT INTO area (`id`, `nama`, `path`, `enabled`, `ref_polygon`, `foto`, `id_cluster`, `desk`) VALUES ('1', 'Area 1', '[[[-8.478525723657054,116.05240345001222],[-8.477591903247376,116.04287624359132],[-8.481412063305804,116.04055881500244],[-8.484553055345845,116.04768276214601]]]', '1', '0', '', '0', 'Area 1');
INSERT INTO area (`id`, `nama`, `path`, `enabled`, `ref_polygon`, `foto`, `id_cluster`, `desk`) VALUES ('2', 'Area 2', '[[[-8.494865867509324,116.05296134948732],[-8.501487264597221,116.0522747039795],[-8.501147708551757,116.04626655578615],[-8.491130670045568,116.0412883758545]]]', '1', '0', '', '0', 'Area 2');


#
# TABLE STRUCTURE FOR: artikel
#

DROP TABLE IF EXISTS artikel;

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
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8;

INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('7', '', '<p><strong>Awal mula SID</strong><br /> \"Awalnya ada keinginan dari pemerintah Desa Balerante yang berharap pelayanan pemerintah desa bisa seperti pengunjung rumah sakit yang ingin mencari data pasien rawat inap, tinggal ketik nama di komputer, maka data tersebut akan keluar\"<br /> (Mart Widarto, pengelola Program Lumbung Komunitas)<br /> Program ini mulai dibuat dari awal 2006: <br /> 1. (2006) komunitas melakukan komunikasi dan diskusi lepas tentang sebuah sistem yang bisa digunakan untuk menyimpan data.<br /> 2. (2008) Rangkaian FDG dengan pemerintah desa membahas tentang tata kelola pendokumentasian di desa<br /> 3. (2009) Ujicoba SID yang sudah dikembangkan di balerante<br /> 4. (2009-2010) Membangun SID (aplikasi) dibeberapa desa yang lain: terong (bantul), Nglegi (Gunungkidul) <br /> 5. (2011) Kandangan (Temanggung) Gilangharjo (bantul) Girikarto (gunungkidul) Talun (klaten) Pager Gunung (magelang) <br /> 6. hingga saat ini 2013 sudah banyak desa pengguna SID.<br /> <br /> <strong>SID sebagai tanggapan atas kebutuhan:</strong><br /> Kalau dulu untuk mencari data penduduk menurut kelompok umur saja kesulitan karena tidak mempunyai databasenya. Dengan adanya SID menjadi lebih mudah.<br /> (Nuryanto, Kabag Pelayanan Pemdes Terong)<br /> <br /> Membangun sebuah sistem bukan hanya membuatkan software dan meninggalkan begitu saja, namun ada upaya untuk memadukan sistem dengan kebutuhan yang ada pada desa. sehingga software dapat memenuhi kebutuhan yang telah ada bukan memaksakan desa untuk mengikuti dan berpindah sistem. inilah yang melatari combine melaksanakan alur pengaplikasian software.<br /> 1. Bentuk tim kerja bersama pemerintah desa<br /> 2. Diskusikan basis data apa saja yang diperlukan untuk warga<br /> 3. Himpun data kependudukan warga dari Kartu Keluarga (KK)<br /> 4. Daftarkan proyek SID dan dapatkan aplikasi softwarenya di http://abcd.lumbungkomunitas.net<br /> 5. Install aplikasi software SID di komputer desa<br /> 6. Entry data penduduk ke SID<br /> 7. Basis data kependudukan sudah bisa dimanfaatkan<br /> 8. Diskusikan rencana pengembangan SID sesuai kebutuhan desa<br /> 9. Sebarluaskan informasi desa melalui beragam media untuk warga<br /> <br /> Pemberdayaan data desa yang dibangun diharapkan dapat menjunjung kesejahteraan masyarakat desa, data-data tersebut dapat diperuntukkan untuk riset lebih lanjut tentang kemiskinan, tanggap bencana, sumberdaya desa yang bisa diekspose keluar dan dengan menghubungkan dari desa ke desa dapat mencontohkan banyak hal dalam keberhasilan pemberdayaannya.<br /> (sumber: Buku Sistem Informasi Desa) <br /> <strong><br /></strong></p>', '1', '2013-03-31 20:31:04', '999', '1', 'Awal mula SID', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('32', '', '<div class=\"contentText\">\r\n<div align=\"justify\">Bagian ini berisi informasi dasar mengenai desa kami. Sila klik pada tautan berikut untuk mendapatkan informasi yang lebih rinci.</div>\r\n<div align=\"justify\">&nbsp;</div>\r\n<div align=\"justify\"><ol>\r\n<li>Sejarah Desa</li>\r\n<li>Profil Wilayah Desa</li>\r\n<li>Profil Masyarakat Desa</li>\r\n<li>Profil Potensi Desa</li>\r\n</ol></div>\r\n</div>', '1', '2013-07-29 17:46:44', '999', '1', 'Profil Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('34', '', '<p style=\"text-align: justify;\"><span style=\"color: #ff0000;\"><strong>Contoh (Sila edit halaman ini sesuai dengan deskripsi desa ini)!</strong></span></p>\r\n<p style=\"text-align: justify;\">Berdasarkan data desa pada bulan Februari 2010, jumlah penduduk Desa Terong sebanyak 6484 orang. Jumlah Kepala Keluarga (KK) sebanyak 1605 KK.</p>\r\n<p style=\"text-align: justify;\">Jumlah penduduk Desa Terong usia produktif pada tahun 2009 adalah 4746 orang. Jumlah angkatan kerja tersebut jika dilihat berdasarkan tingkat pendidikannya adalah sebagai berikut:</p>\r\n<table style=\"width: 100%;\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">\r\n<tbody>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\"><strong>No.</strong></p>\r\n</td>\r\n<td style=\"width: 42%;\">\r\n<p style=\"text-align: center;\"><strong>Angkatan Kerja</strong></p>\r\n</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\"><strong>L</strong></p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\"><strong>P</strong></p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\"><strong>Jumlah</strong></p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">1</p>\r\n</td>\r\n<td style=\"width: 42%;\">Tidak Tamat SD</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">59</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">56</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">115</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">2</p>\r\n</td>\r\n<td style=\"width: 42%;\">SD</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">880</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">792</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1672</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">3</p>\r\n</td>\r\n<td style=\"width: 42%;\">SLTP</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">813</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">683</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1496</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">4</p>\r\n</td>\r\n<td style=\"width: 42%;\">SLTA</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">725</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">673</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1398</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">5</p>\r\n</td>\r\n<td style=\"width: 42%;\">Akademi</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">13</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">11</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">24</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">6</p>\r\n</td>\r\n<td style=\"width: 42%;\">Perguruan Tinggi</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">23</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">18</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">41</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 50%;\" colspan=\"2\">\r\n<p style=\"text-align: center;\">Jumlah Total</p>\r\n</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">2513</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">2233</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">4746</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Profil sosial masyarakat</strong></p>\r\n<p style=\"text-align: justify;\">Dalam aktivitas keseharian, masyarakat Desa Terong sangat taat dalam menjalankan ibadah keagamaan. Setiap Rukung Tetangga (RT) dan pedukuhan memiliki kelompok-kelompok pengajian. Pada peringatan hari besar Islam, penduduk Desa Terong kerap menggelar acara peringatan dan karnaval budaya dengan tema yang disesuaikan dengan hari besar keagamaan. Sebagian besar warga Desa Terong terafiliasi pada organisasi kemasyarakat Islam Muhammadiyah.</p>\r\n<p style=\"text-align: justify;\">Gelaran perayaan lain selalu dilakukan dalam rangka memperingati Hari Kemerdekaan Republik Indonesia. Setiap pedukuhan akan turut serta dan semangat menampilkan ciri khasnya dalam acara peringatan dan karnaval budaya.</p>\r\n<p style=\"text-align: justify;\">Kelompok pemuda di Desa Terong yang tergabung dalam kelompok pegiat Karang Taruna menjadi aktor utama dalam banyak kegiatan desa. Kelompok ini aktif menggelar program kegiatan untuk isu demokrasi kepada warga, penguatan ekonomi produktif, pelatihan penanggulangan bencana, dan kampanye Gerakan Remaja Sayang Ibu (GEMAS).</p>\r\n<p style=\"text-align: justify;\">Sejumlah penduduk Desa Terong bekerja merantau di daerah di luar Yogyakarta. Namun, ikatan sosial mereka terhadap tanah kelahiran tetap tinggi. Penduduk asli Desa Terong yang tinggal di Jakarta dan sekitarnya misalnya, mereka membentuk paguyuban untuk memelihara silaturahmi antar sesama warga perantauan. Setiap bulan diadakan kegiatan arisan keliling secara bergilir di setiap tempat anggotanya. Setiap dua tahun sekali diadakan pula kegiatan mudik bersama ke kampung halaman di Desa Terong</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Profil politik masyarakat</strong></p>\r\n<p style=\"text-align: justify;\">Warga Desa Terong dikenal sebagai kelompok masyarakat yang paling aktif dan memiliki potensi tertinggi untuk berpartisipasi dalam pemberian suara untuk Pemilihan Umum dan Pemilihan Kepala Daerah Langsung. Tingkat partisipasi warga di desa ini terbanyak jika dibandingkan dengan desa lain di Kecamatan Dlingo, Bantul.</p>\r\n<p style=\"text-align: justify;\">Warga Desa Terong sangat aktif dalam mengawal proses penyusunan Rancangan Undang-Undang (RUU) Keistimewaan Yogyakarta. Banyak warga Desa Terong yang tergabung dalam Gerakan Rakyat Yogyakarta (GRY) dan aktif dalam beragam kegiatan serta demontrasi mendukung penetapan keistimewaan Yogyakarta. Kepala Desa Terong Sudirman Alfian merupakan Ketua Paguyuban Lurah dan Pamong Desa Ing Sedya Memetri Asrining Yogyakarta (ISMAYA) se Daerah Istimewa Yogyakarta (DIY). Beliau ditunjuk pula sebagai anggota tim perumus RUU Keistimewaan Yogyakarta bersi masyarakat Yogyakarta. Salah satu hal yang diperjuangkan dalam RUU tersebut adalah tidak adanya pelaksanaan pemilihan kepala daerah langsung dalam pemilihan Gubernur DIY; dengan mempertahankan konsep dwi tunggal Sri Sultan Hamengku Buwono dan Paku Alam sebagai Gubernur dan Wakil Bubernur DIY.</p>\r\n<p style=\"text-align: justify;\">Permasalahan mendasar yang ada di Desa Terong adalah tidak imbangnya jumlah pencari kerja dengan jumlah lapangan kerja yang tersedia. Sekalipun jumlah pengangguran di Desa Terong pada Tahun 2009 hanya orang tetapi kebanyakan mereka bekerja di luar Desa. Jadi, perlu gerakan kembali ke Desa serta menarik sumber-sumber ekonomi ke desa agar pencari kerja tidak banyak tersedot ke luar Desa.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Sumber:<br />Laporan Pertanggung Jawaban Lurah Desa Terong, Kecamatan Dlingo, Kabupaten Bantul tahun 2009.</p>', '1', '2013-07-29 18:13:36', '999', '1', 'Profil Masyarakat Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('36', '', '<p>Kontak kami berisi cara menghubungi desa, seperti contoh berikut :</p>\r\n<p>Alamat : Jl desa no 01</p>\r\n<p>No Telepon : 081xxxxxxxxx</p>\r\n<p>Email : xx@desa.com</p>', '1', '2013-07-29 18:28:31', '999', '1', 'Kontak Kami', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('37', '', '<p><span style=\"color: #ff0000;\"><strong>Contoh (Sila edit halaman ini dan sesuaikan dengan deskripsi untuk desa ini)!</strong></span><br /><br />Susunan Organisasi Badan Permusyawaratan Desa:</p>\r\n<p>Ketua</p>\r\n<p>Sekretaris</p>\r\n<p>Anggota</p>', '1', '2013-07-29 18:33:33', '999', '1', 'Badan Permusyawaratan Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('38', '', '<p>Berisi data lembaga yang ada di desa beserta deskripsi dan susunan pengurusnya</p>', '1', '2013-07-29 18:38:33', '999', '1', 'Lembaga Kemasyarakatan', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('41', '', '<p>Agenda Bulan Agustus :</p>\r\n<p>01/08/2013 : Rapat rutin</p>\r\n<p>04/08/2013 : Pertemuan Pengurus</p>\r\n<p>05/08/2013 : Seminar</p>\r\n<p>&nbsp;</p>', '1', '2013-07-30 14:08:52', '1000', '1', 'Agenda', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('42', '', '<p>Daftar Undang Undang Desa</p>\n<p><a href=\"../../../../../../assets/front/dokumen/Profil_SSN_SMP1Kepil.pdf\">1. UU No desa</a></p>\n<p>berisi asf basdaf.</p>\n<p>&nbsp;</p>\n<p><a href=\"kebumenkab.go.id/uu.pdf\">2.UU Perdangangan</a></p>', '1', '2014-04-20 18:21:56', '999', '1', 'Undang Undang', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('43', '', '<p>Isi Peraturan Pemerintah</p>', '1', '2014-04-20 18:24:01', '999', '1', 'Peraturan Pemerintah', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('44', '', '<p>Isi Peraturan Desa</p>', '1', '2014-04-20 18:24:35', '999', '1', 'Peraturan Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('45', '', '<p>Isi Peraturan Kepala Desa</p>', '1', '2014-04-20 18:25:04', '999', '1', 'Peraturan Kepala Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('46', '', '<p>Isi Keputusan kepala desa</p>', '1', '2014-04-20 18:25:36', '999', '1', 'Keputusan Kepala Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('47', '', '<p>Isi Panduan</p>', '1', '2014-04-20 18:38:10', '999', '1', 'Panduan', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('51', '', '<p>Wahai masyarakat yang ber,,,,,,,,,,,,,,,,,,,,</p>\n<p>no hp : 097867575</p>\n<p>email: jkgkgkg</p>\n<p>ato komentar di bawah ini :</p>', '1', '2014-04-22 10:11:20', '999', '1', 'Pengaduan', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('59', '', '<ol>\r\n<li><strong>a.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Jumlah Penduduk</strong></li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<table style=\"width: 372px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah jiwa</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah laki-laki</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah perempuan</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah Kepala Keluarga</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">KK</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li><strong>b.&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Tingkat Pendidikan</strong></li>\r\n</ol>\r\n<table style=\"width: 373px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Belum sekolah</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Usia 7-45 tahun tidak pernah sekolah</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pernah sekolah SD tetapi tidak tamat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pendidikan terakhir</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Tamat SD/sederajat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">SLTP/sederajat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">SLTA/sederajat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">D-1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">D-2</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">D-3</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">S-1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">S-2</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">S-3</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li><strong>c.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Mata Pencaharian</strong></li>\r\n</ol>\r\n<p><strong>&nbsp;</strong></p>\r\n<table style=\"width: 372px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Petani</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">246</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Buruh tani</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">125</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Buruh/swasta</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">136</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pegawai Negeri</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">35</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pengrajin</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">&nbsp;</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pedagang</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">9</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Peternak</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">-</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Nelayan</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">-</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Montir</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">8</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Dokter</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">-</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">POLRI/ABRI</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pensiunan</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">36</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Perangkat Desa</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">15</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pembuat Bata</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">3</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><strong>&nbsp;</strong></p>\r\n<ol>\r\n<li><strong>d.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>AGAMA</strong></li>\r\n</ol>\r\n<table style=\"width: 372px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Islam</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">2215</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Kristen</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">5</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Katholik</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">&nbsp;</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Hindu</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">&nbsp;</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Budha</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><strong>&nbsp;</strong></p>\r\n<p><strong>&nbsp;</strong></p>', '1', '2014-04-30 18:23:24', '999', '1', 'Profil Potensi Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('62', '', '<p>Lembaga Kemasyarakatan Desa (LKMD) adalah salah satu lembaga kemasyaratan yang berada di desa.</p>\n<p>TUGAS LKMD</p>\n<ol>\n<li>menyusun rencana pembangunan secara partisipatif,</li>\n<li>menggerakkan swadaya gotong royong masyarakat,</li>\n<li>melaksanakan dan</li>\n<li>mengendalikan pembangunan.</li>\n</ol>\n<p align=\"left\">FUNGSI LKMD</p>\n<ol>\n<li>penampungan dan penyaluran aspirasi masyarakat dalam pembangunan;</li>\n<li>penanaman dan pemupukan rasa persatuan dan kesatuan masyarakat dalam kerangka memperkokoh Negara Kesatuan Republik Indonesia;</li>\n<li>peningkatan kualitas dan percepatan pelayanan pemerintah kepada masyarakat;</li>\n<li>penyusunan rencana, pelaksanaan, pelestarian dan pengembangan hasil-hasil pembangunan secara partisipatif;</li>\n<li>penumbuhkembangan dan penggerak prakarsa, partisipasi, serta swadaya gotong royong masyarakat; dan</li>\n<li>penggali, pendayagunaan dan pengembangan potensi sumber daya alam serta keserasian lingkungan hidup.</li>\n</ol>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', '1', '2014-04-30 18:39:07', '999', '1', 'LKMD', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('63', '', '<p>TUGAS PKK</p>\n<ol>\n<li>menyusun rencana kerja PKK Desa/Kelurahan, sesuai dengan basil Rakerda Kabupaten/Kota;</li>\n<li>melaksanakan kegiatan sesuai jadwal yang disepakati;</li>\n<li>menyuluh dan menggerakkan kelompok-kelompok PKK Dusun/Lingkungan, RW, RT dan dasa wisma agar dapat mewujudkan kegiatan-kegiatan yang telah disusun dan disepakati;</li>\n<li>menggali, menggerakan dan mengembangkan potensi masyarakat, khususnya keluarga untuk meningkatkan kesejahteraan keluarga sesuai dengan kebijaksanaan yang telah ditetapkan;</li>\n<li>melaksanakan kegiatan penyuluhan kepada keluarga-keluarga yang mencakup kegiatan bimbingan dan motivasi dalam upaya mencapai keluarga sejahtera;.</li>\n<li>mengadakan pembinaan dan bimbingan mengenai pelaksanaan program kerja;</li>\n<li>berpartisipasi dalam pelaksanaan program instansi yang berkaitan dengan kesejahteraan keluarga di desa/kelurahan;</li>\n<li>membuat laporan basil kegiatan kepada Tim Penggerak PKK Kecamatan dengan tembusan kepada Ketua Dewan Penyantun Tim Penggerak PKK setempat;</li>\n<li>melaksanakan tertib administrasi; dan</li>\n<li>mengadakan konsultasi dengan Ketua Dewan Penyantun Tim Penggerak PKK setempat.</li>\n</ol>\n<p>&nbsp;</p>\n<p>FUNGSI PKK</p>\n<ol>\n<li>penyuluh, motivator dan penggerak masyarakat agar mau dan mampu melaksanakan program PKK; dan</li>\n<li>fasilitator, perencana, pelaksana, pengendali, pembina dan pembimbing Gerakan PKK.</li>\n</ol>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"center\"><strong>Alamatn</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"center\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', '2', '2014-04-30 18:41:13', '999', '1', 'PKK', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('64', '', '<p align=\"left\">TUGAS &nbsp;KARANGTARUNA</p>\n<p align=\"left\">menanggulangi berbagai masalah kesejahteraan sosial terutama yang dihadapi generasi muda, baik yang bersifat preventif, rehabilitatif, maupun pengembangan potensi generasi muda di lingkungannya</p>\n<p align=\"left\">FUNGSI KARANGTAURNA</p>\n<ol>\n<li>penyelenggara usaha kesejahteraan sosial;</li>\n<li>penyelenggara pendidikan dan pelatihan bagi masyarakat;</li>\n<li>penyelenggara pemberdayaan masyarakat terutama generasi muda di lingkungannya secara komprehensif, terpadu dan terarah serta berkesinambungan;</li>\n<li>penyelenggara kegiatan pengembangan jiwa kewirausahaan bagi generasi muda di lingkungannya;</li>\n<li>penanaman pengertian, memupuk dan meningkatkan kesadaran tanggung jawab sosial generasi muda;</li>\n<li>penumbuhan dan pengembangan semangat kebersamaan, jiwa kekeluargaan, kesetiakawanan sosial dan memperkuat nilai-nilai kearifan dalam bingkai Negara Kesatuan Republik Indonesia;</li>\n</ol>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', '1', '2014-04-30 18:44:28', '999', '1', 'Karang Taruna', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('65', '', '<p align=\"left\">TUGAS RT/RW</p>\n<p align=\"left\">membantu Pemerintah Desa dan Lurah dalam penyelenggaraan urusan pemerintahan</p>\n<p align=\"left\">FUNGSI PKK</p>\n<ol>\n<li>pendataan kependudukan dan pelayanan administrasi pemerintahan lainnya;</li>\n<li>pemeliharaan keamanan, ketertiban dan kerukunan hidup antar warga;</li>\n<li>pembuatan gagasan dalam pelaksanaan pembangunan dengan mengembangkan aspirasi dan swadaya murni masyarakat; dan</li>\n<li>penggerak swadaya gotong royong dan partisipasi masyarakat di wilayahnya.</li>\n</ol>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\"><strong>Nama Pejabat</strong></p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">Ketua RW 1</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">Ketua RW 1 Rt 01</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">Ketua RW 1 Rt 02</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', '1', '2014-04-30 18:45:19', '999', '1', 'RT RW', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('66', '', '<p class=\"Default\">&nbsp;</p>\n<p class=\"Default\">Tim Koordinasi Percepatan Penanggulangan Kemiskinan Desa yang selanjutnya disingkat TKP2KDes adalah wadah koordinasi lintas sektor dan lintas pemangku kepentingan untuk percepatan penanggulangan kemiskinan di desa.</p>\n<p class=\"Default\">TKP2KDes bertugas mengkoordinasikan perencanaan, pengorganisasian, pelaksanaan dan pengendalian program penanggulangan kemiskinan di tingkat Desa.</p>\n<p>( Perda Kabupaten Kebumen Nomor 20 Tahun 2012 Tentang Percepatan Penanggulangan Kemiskinan )</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip; Nomor : &hellip;&hellip;tanggal &hellip;&hellip;.. bulan&hellip;.. tentang &hellip;..</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', '2', '2014-04-30 18:46:01', '999', '1', 'TKP2KDes', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('67', '', '<p class=\"Default\">&nbsp;</p>\n<p class=\"Default\">Kelompok Perlindungan Anak Desa atau Kelurahan yang selanjutnya disingkat KPAD atau KPAK adalah lembaga perlindungan anak berbasis masyarakat yang berkedudukan dan melakukan kerja&ndash;kerja perlindungan anak di wilayah desa atau kelurahan tempat anak bertempat tinggal&nbsp;&nbsp;&nbsp;&nbsp; ( Perda Kaupaten Kebumen No 3 Tahun 2013 Tentang Penyelenggaraan Perlindungan Anak&nbsp; )</p>\n<p>TUGAS-TUGAS KPAD</p>\n<p>1.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sosialisasi</p>\n<ol>\n<li>Mensosialisasikan kepada masyarakat tentang hak-hak anak</li>\n<li>Mempromosikan CHILD RIGHTS dan CHILD PROTECTION</li>\n<li>Melakukan upaya pencegahan, respon dan penanganan kasus kasus kekerasan terhadap anak dan masalah anak.</li>\n</ol>\n<p>1.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mediasi</p>\n<ol>\n<li>Mengedepankan upaya musyawarah dan mufakat (Rembug Desa)&nbsp; dalam menyelesaikan masalah &ndash; (Restorative Justive)</li>\n<li>Melakukan koordinasi dengan pihak terkait di level desa, kecamatan dan kabupaten dalam upaya perlindungan anak.</li>\n<li>Melakukan pendampingan kasus (dari pelaporan &ndash; medis &ndash; psikologi - reintegrasi)</li>\n</ol>\n<p>1.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fasilitasi</p>\n<ol>\n<li>Memfasilitasi terbentuknya kelompok anak di desa sebagai media partisipasi anak</li>\n<li>Memfasilitasi partisipasi anak untuk terlibat dalam penyusunan perencanaan pembangunan yang berbasis hak anak (penyusunan RPJMDesa)</li>\n</ol>\n<p>1.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Dokumentasi</p>\n<ol>\n<li>Mendokumentasikan semua proses yang dilakukan (Kegiatan Promosi; Penanganan Kasus dan mencatat kasus yang dilaporkan; Perkembangan Kasus, Pertemuan,dll)</li>\n</ol>\n<p>1.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Advokasi</p>\n<ol>\n<li>Mendorong adanya kebijakan dan penganggaran untuk perlindungan anak di level desa</li>\n<li>Menerima pengaduan kasus dan konsultasi tentang perlindungan anak</li>\n<li>Berhubungan dengan P2TP2A dan LPA untuk pendampingan hukum kasus anak (korban dan atau pelaku)</li>\n</ol>\n<p class=\"Default\">&nbsp;</p>\n<p class=\"Default\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', '2', '2014-04-30 18:47:21', '999', '1', 'KPAD', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('68', '', '<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK TERNAK &hellip;..</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang</p>\n<p align=\"center\">&hellip;&hellip;&hellip;&hellip;&hellip;.. &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK WANITA TANI TERNAK&nbsp; &hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align=\"center\">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p>&nbsp;</p>', '2', '2014-04-30 18:47:58', '999', '1', 'Kelompok Ternak', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('69', '', '<p align=\"center\"><strong>DAFTAR NAMA PENGURUS GAPOKTAN</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang</p>\n<p align=\"center\">&hellip;&hellip;&hellip;&hellip;&hellip;.. &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK TANI &hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align=\"center\">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK TANI &hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align=\"center\">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>', '1', '2014-04-30 18:48:39', '999', '1', 'Kelompok Tani', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('70', '', '<p>Linmas</p>', '1', '2014-04-30 18:53:18', '999', '1', 'LinMas', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('71', '', '<p>Kelompok Ekonomi Lainya</p>', '2', '2014-04-30 18:54:20', '999', '1', 'Kelompok Ekonomi Lainya', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('83', '', '<p>Tiap hari rapat sampai kumat</p>', '1', '2014-11-06 18:17:52', '1000', '1', 'Rapat Lagi', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('85', '1471927406download (1).jpg', '<div class=\"contentText\">\r\n<div align=\"justify\">Bagian ini berisi informasi mengenai PemerintahanDesa. Sila klik pada tautan berikut untuk mendapatkan informasi yang lebih rinci.</div>\r\n<div align=\"justify\">&nbsp;</div>\r\n<ol>\r\n<li>Visi dan Misi</li>\r\n<li>Pemerintah Desa</li>\r\n<li>Badan Permusyawaratan Desa</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI dan MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Senggigi Berseri\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>(Bersih, Relegius, Sejahtera, Rapi, dan Indah)</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Terwujudnya masyarakat Desa Senggigi yang Bersih, Relegius, Sejahtra, Rapi dan Indah melalui Akselerasi Pembangunan yang berbasis Keagamaan, Budaya Hukum dan Berwawasan Lingkungan dengan berorentasi pada peningkatan Kinerja Aparatur dan Pemberdayaan Masyarakat\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Misi dan Program Desa Senggigi</strong></p>\r\n<p style=\"text-align: justify;\">Dan untuk melaksanakan visi Desa Senggigi dilaksanakan misi dan program sebagai berikut:</p>\r\n<p style=\"text-align: justify;\">1. Pembangunan Jangka Panjang</p>\r\n<ul>\r\n<li style=\"text-align: justify;\">Melanjutkan Pembangunan Desa yang belum terlaksana</li>\r\n<li style=\"text-align: justify;\">Meningkatkan Kerjasama antara pemerintah Desa dengan Lembaga desa yang ada</li>\r\n<li style=\"text-align: justify;\">Meningkatkan Kesejahtraan Masyarakat desa dengan meningkatkan sarana dan prasarana ekonomi warga.&nbsp;</li>\r\n</ul>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">2. Pembangunan Jangka Pendek &nbsp;</p>\r\n<ul>\r\n<li style=\"text-align: justify;\">Mengembangkan dan Menjaga serta melestarikan ada istiadat desa terutama yang telah mengakar di desa senggigi.&nbsp;</li>\r\n<li style=\"text-align: justify;\">Meningkatkan pelayanan dalam bidang pemerintahan kepada warga masyarakat&nbsp;</li>\r\n<li style=\"text-align: justify;\">Meningkatkan sarana dan prasarana ekonomi warga desa dengan perbaikan prasarana dan sarana ekonomi.</li>\r\n<li style=\"text-align: justify;\">Meningkatkan sarana dan prasarana pendidikan guna peningkatan sumber daya manusia desa senggigi.&nbsp;</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>Kepala Desa Senggigi</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>Muhammad Ilham</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n</div>', '1', '2014-11-07 10:53:54', '999', '1', 'Pemerintahan Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('87', '', '<p>bla bla bla</p>', '1', '2014-12-10 16:59:20', '16', '1', 'Sejumlah Tukang Becak Merampok Indoapril', '0', '', '', '', 'ddd.xls', 'hahaha', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('90', '1471968200IMG-20160823-WA0007.jpg', '', '1', '2016-08-24 00:03:21', '5', '1', 'PERDES PHBS ', '3', '1471968200IMG-20160823-WA0012.jpg', '1471968200', '1471968200', 'PERDES PHBS.docx', 'PERDES PHBS ', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('92', '1472006396', '<p><strong>Susunan Organisasi Pemerintah Desa Senggigi</strong><br /><br />Kepala Desa: MUHAMMAD ILHAM<br />Sekretaris Desa:&nbsp;<span>MUSTAHIQ, S.Adm</span><br />Kepala Urusan Pemerintahan: SYAFRUDIN<br />Kepala Urusan&nbsp;Pembangunan: SYAFI\'I, SE<br />Kepala Urusan&nbsp;Kesra: HAMIDIAH<br />Kepala Urusan&nbsp;Keuangan: MARDIANA<br />Kepala Urusan&nbsp;Trantib: SUPARDI RUSTAM<br />Kepala Urusan&nbsp;Umum: MAHRUP<br /><br /></p>', '1', '2016-08-24 10:39:56', '999', '1', 'Pemerintah Desa', '0', '1472006396', '1472006396', '1472006396', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('93', '1472006908', '<p style=\"text-align: center;\"><strong>VISI dan MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Senggigi Berseri\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>(Bersih, Relegius, Sejahtera, Rapi, dan Indah)</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Terwujudnya masyarakat Desa Senggigi yang Bersih, Relegius, Sejahtera, Rapi dan Indah melalui Akselerasi Pembangunan yang berbasis Keagamaan, Budaya Hukum dan Berwawasan Lingkungan dengan berorentasi pada peningkatan Kinerja Aparatur dan Pemberdayaan Masyarakat\"</strong></p>\r\n<p>&nbsp;&nbsp;</p>\r\n<p><strong>MISI</strong></p>\r\n<p><strong>Misi dan Program Desa Senggigi</strong></p>\r\n<p>Dan untuk melaksanakan visi Desa Senggigi dilaksanakan misi dan program sebagai berikut:</p>\r\n<p>1. Pembangunan Jangka Panjang</p>\r\n<p>&nbsp; &nbsp; - Melanjutkan pembangunan desa yang belum terlaksana.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan kerjasama antara pemerintah desa dengan lembaga desa yang ada.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan kesejahtraan masyarakat desa dengan meningkatkan sarana dan prasarana ekonomi warga.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>2. Pembangunan Jangka Pendek &nbsp;</p>\r\n<p>&nbsp; &nbsp; - Mengembangkan dan Menjaga serta melestarikan ada istiadat desa terutama yang telah mengakar di desa senggigi.&nbsp;</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan pelayanan dalam bidang pemerintahan kepada warga masyarakat&nbsp;</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan sarana dan prasarana ekonomi warga desa dengan perbaikan prasarana dan sarana ekonomi.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan sarana dan prasarana pendidikan guna peningkatan sumber daya manusia Desa Senggigi.&nbsp;</p>', '1', '2016-08-24 10:48:28', '999', '1', 'Visi dan Misi', '0', '1472006908', '1472006908', '1472006908', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('94', '1527544437_gotong-royong-pantai.jpeg', '<p style=\"text-align: justify;\">Gotong royong yang dulu digagas pertama kali oleh pendiri bangsa, Ir. Soekarno kian hari semakin terkikis dengan budaya individual ditengah persaingan yang begitu ketat dalam mencapai tujuan tertentu, kenyataan inilah yang membuat nilai-nilai sosial ditatanan masyarakat yang sejak dulu sudah ditanamkan oleh nenek moyang kita luntur seiring dengan perkembangan jaman. padahal untuk mencapai suatu tujuan dan cita-cita bersama seharusnya dikerjakan secara bersama-sama.&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Maka dengan kenyataan inilah, pemerintah desa senggigi kembali melakukan sebuah inovasi baru dalam merangkul masyarakat agar terus menanamkan kebudayaan \"Gotong Raoyong\". kegitan gotong royong dalam pembangunan jalan desa sedikitnya melibatkan hampir 95% masyarakat senggigi, kebersamaan dan ikatan persaudaraan atas kepentingan bersama menjadi satu ketika semua masyarakat desa terlibat aktif, tanpa harus melihat tatanan dan golongan yang ada. masyarakat saling bahu membahu seiring kegitan gotong royong.&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', '1', '2016-08-24 11:02:44', '1', '1', 'Membangun Desa Lewat Gotong Royong', '3', '1472782825artikel-2-2.jpeg', '1472007764', '1472007764', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('95', '1527540313_kemerdekaan-pantai.jpg', '<p>Desa Senggigi ikut memeriahkan perayaan 17 Agustus 2016 sebagai hari jadi Indonesia yang ke 71 melalui kegiatan Karnaval yang diselenggarakan oleh Camat Batulayar Kabupaten Lombok Barat NTB. Acara karnaval dilaksanakan pada hari Rabu, 17 Agustus 2016 dimulai pukul 15.30 s/d 17.00 wita. Masing-masing desa berkumpul disekitaran kantor Camat Batulayar, dan berjalan menuju Taman Bale Pelangi Desa Sandik sebagai pusat titik kumpul seluruh peserta karnaval.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Dalam karnaval ini, Desa Senggigi melibatkan berbagai unsur masyarakat seperti tokoh masyarakat, perempuan, pemuda dan anak-anak dengan menggunakan baju adat dan berbagai macam asesoris hari kemerdekaan, kegitan tersebut adalah salah satu cara bagaimana memupuk semangat bagi setiap warga negara, khususnya kaum muda sebagai harapan bangsa, yang kian hari semakin terkikis dengan pengaruh global saat ini.</p>\r\n<p>&nbsp;</p>\r\n<p>Lewat karang taruna desa senggigi, pemupukan pemberian semangat dalam berpacu memajukan desa dan bangsa terus dilakukan, berbagai macam kegiatan tahapan dalam pelaksanaan hari kemerdekaan terus di lakukan.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', '1', '2016-08-24 13:05:21', '1', '1', 'Perayaan Hari Kemerdekaan 2016', '3', '1472782634galeri-1-2.jpeg', '1472015120', '1472015120', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('96', '1472782915artikel-3-1.jpeg', '<p>Dalam rapat pembahasan komitmen perekrutan karyawan hotel pada tanggal 24 Agustus 2016 di kantor desa sengigi telah menyepakati beberapa komitmen bersama diantaranya sebagai berikut:</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>1. Dalam perekrutan karyawan, pihak hotel harus memprioritaskan masyarakat senggigi minimal 35%</p>\n<p>2. Pihak Hotel harus mengikuti program perencanaan tenaga kerja desa senggigi sesua dengan VISI dan MISI desa</p>\n<p>3. Pihak hotel harus melakukan kordinasi dengan pemerintah desa ketika perekrutan karyawan&nbsp;</p>\n<p>4. Pihak Hotel harus melakukan pelatihan bagi calon karyawan, khususnya karyawan yang berasal dari desa sengggigi</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>Bagi rekan-rekan pemuda dan masyarakat harap melakukan kordinasi dengan pemerintah desa terkait dengan beberapa hasil pertemuan dalam membangun komitme dengan pihak hotel, jika ada hal mendesak terkait beberapa syarat ketentuan perekrutan, rekan-rekan pemuda dan masyarakat bisa menghubungi kami di kantor desa..</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>', '1', '2016-08-24 13:55:10', '4', '1', 'Rapat membangun Komitmen antara Karang Taruna Desa Senggigi dengan Taruna Hotel', '3', '1472018109IMG-20160824-WA0000.jpg', '1472018109', '1472018109', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('97', '1472019299', '<p>Halaman ini berisi tautan menuju informasi mengenai Basis Data Desa. Ada dua jenis data yang dimuat dalam sistem ini, yakni basis data kependudukan dan basis data sumber daya desa. Sila klik pada tautan berikut untuk mendapatkan tampilan data statistik per kategori.</p>\r\n<ol>\r\n<li>Data Wilayah Administratif&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Pendidikan&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Pekerjaan&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Golongan Darah&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Agama&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Jenis Kelamin&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Kelompok Umur&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Penerima Raskin&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Penerima BPJS &nbsp; &nbsp; &nbsp; &nbsp;</li>\r\n<li>Data Warga Negara &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;</li>\r\n</ol>\r\n<p>Data yang tampil adalah statistik yang didapatkan dari proses olah data dasar yang dilakukan secara&nbsp;<em>offline</em>&nbsp;di kantor desa secara rutin/harian. Data dasar di kantor desa diunggah ke dalam sistem&nbsp;<em>online</em>&nbsp;di website ini secara berkala. Sila hubungi kontak pemerintah desa untuk mendapatkan data dan informasi desa termutakhir.</p>', '1', '2016-08-24 14:14:59', '999', '1', 'Data Desa', '0', '1472019299', '1472019299', '1472019299', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('98', '1472192894', '<p>Wilayah desa berisi tentang penjelasan dan deskripsi letak wilayah desa. contohnya sebagai berikut :<br />Batas-batas :<br />Utara&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kelurahan a<br />Timur &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Desa b<br />Selatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Desa c<br />Barat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kelurahan d dan Desa e<br />Luas Wilayah Desa Penglatan&nbsp;&nbsp; : 186,193 Ha<br />Letak Dan Batas Desa x<br />Desa Penglatan terletak pada posisi 115. 7.20 LS 8. 7.10 BT, dengan ketinggian kurang lebih 250 M diatas permukaan laut.</p>\r\n<p>Peta Desa:</p>\r\n<p><iframe src=\"https://www.google.co.id/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Logandu,+Karanggayam&amp;aq=0&amp;oq=logandu&amp;sll=-2.550221,118.015568&amp;sspn=52.267573,80.332031&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&amp;z=14&amp;ll=-7.55854,109.634173&amp;output=embed\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" width=\"600\" height=\"450\"></iframe></p>', '1', '2016-08-26 14:28:14', '999', '1', 'Wilayah Desa', '0', '1472192894', '1472192894', '1472192894', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('99', '1472228892Raja Lombok 1902.jpg', '<p style=\"text-align: justify;\" align=\"center\">Sejarah telah mencatat bahwa Pulau Lombok pernah menjadi wilayah kekuasaan Kerajaan Karang Asem Bali yang berkedudukan di Cakranegara dengan seorang raja bernama Anak Agung Gde Jelantik. Berakhirnya <strong>kekuasaan</strong> Kerajaan Karang Asem Bali di Pulau Lombok setelah datangnya Belanda pada Tahun 1891, dimana Belanda pada waktu itu ingin menguasai Pulau Lombok dengan dalih pura-pura membantu rakyat Lombok yang dianggap tertindas oleh Pemerintahan Raja Lombok yaitu Anak Agung Gede Jelantik.</p>\r\n<p style=\"text-align: justify;\">Pada masa kekuasaan Raja Lombok yaitu Anak Agung Gde Jelantik, wilayah Desa Senggigi ( Dusun Mangsit, Kerandangan, Senggigi dan Dusun Loco) masih bergabung dengan Desa Senteluk yang sekarang menjadi Desa Meninting . Sedangkan pada tahun 1962 Desa Senteluk pecah menjadi 2 (Dua) desa yaitu Desa Meninting dan Desa Batulayar dan Dusun Mangsit,Kerandangan,Senggigi dan Dusun Loco bergabung ke Desa Batulayar.&nbsp;</p>\r\n<p style=\"text-align: justify;\">Pemberian nama Desa Batulayar pada waktu itu yang lazim disebut dengan Pemusungan/Kepala Dea Batulayar berdasarkan hasil musyawarah nama Batulayar diambil dari nama tempat yang amat terkenal yaitu Makam Batulayar yang sampai saat ini banyak dikunjungi oleh masyarakat Pulau Lombok pada khususnya dan Masyarakat Nusa Tenggara Barat pada umumnya.</p>\r\n<p style=\"text-align: justify;\">Pada tahun 2001 Desa Batulayar dimekarkan menjadi 2 (dua) yaitu Desa Batulayar (sebagai Desa Induk) dan Desa Senggigi (sebagai Desa Persiapan) dengan SK.Bupati No : 30 Tahun 2001 tanggal 17 Mei 2001, yang pada waktu itu yang menjadi pejabat Kepala Desa Senggigi ialah <strong>H. ARIF RAHMAN, S.IP</strong>., dengan jumlah dusun sebanyak 3 dusun, yaitu :</p>\r\n<p>1. Dusun Senggigi</p>\r\n<p>2. Dusun Kerandangan</p>\r\n<p>3. Dusun Mangsit</p>\r\n<p>Selanjutnya pada tanggal 30 Juli 2003 Pejabat Kepala Desa Senggigi dari <strong>H. ARIF RAHMAN, S.IP</strong> diganti oleh Saudara<strong> ARIFIN</strong> dengan SK. Bupati Lombok Barat No : 409/66/pem/2003. Berhubung Desa Senggigi masih bersifat Desa Persiapan, maka berdasarkan hasil musyawarah desa, tertanggal 15 Desember 2003 , maka pada tanggal 22 Desember 2003 Desa Senggigi mengadakan Pemilihan Kepala Desa devinitif yang pertama kali dipimpin oleh&nbsp;<strong>HAJI JUNAIDI</strong>&nbsp;terpilih&nbsp;dengan SK. Bupati Lombok Barat No :01/01/Pem/2004 tertanggal 2 Januari 2004&nbsp;sampai pada tahun 2008.&nbsp;</p>\r\n<p style=\"text-align: justify;\">Selanjutnya pada tahun 2008, Desa Senggigi mengadakan pemilihan Kepala Desa Senggigi yang kedua dan dimenangkan oleh Bapak <strong>H. MUTAKIR AHMAD</strong>&nbsp;dengan&nbsp;SK. Bupati Lombok Barat No :1320/48/Pem./2008 tertanggal 23 Desember 2008, Periode 2008-2014. &nbsp;Kemudian Kepala desa terpilih Periode 2015 s/d 2021&nbsp;adalah <strong>MUHAMMAD ILHAM</strong>&nbsp;dengan SK. Bupati Lombok Barat No : 160/04/BPMPD/15 tanggal 27 Januari 2015 kini baru menjabat 2 (dua) bulan.</p>\r\n<p style=\"text-align: justify;\">Demikian selanyang pandang atau sejarah singkat Desa Senggigi yang dapat kami sampaikan kepada para pegiat Medsos, semoga dapat bermanfaat untuk kita semua, terima kasih.</p>\r\n<p style=\"text-align: justify;\" align=\"center\">&nbsp;</p>\r\n<p style=\"text-align: justify;\" align=\"center\">&nbsp;</p>', '1', '2016-08-26 15:38:09', '999', '1', 'Sejarah Desa', '3', '1472229325490125_20121123041539.jpg', '1472197089', '1472197089', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('100', '1473071921', '<p>Ini contoh teks berjalan. Isi dengan tulisan yang menampilkan suatu ciri atau kegiatan penting di desa anda.</p>', '1', '2016-09-05 10:38:41', '22', '1', 'Contoh teks berjalan', '0', '1473071921', '1473071921', '1473071921', '', '', NULL, '3', '1');


#
# TABLE STRUCTURE FOR: captcha_codes
#

DROP TABLE IF EXISTS captcha_codes;

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

INSERT INTO captcha_codes (`id`, `namespace`, `code`, `code_display`, `created`, `audio_data`) VALUES ('10.0.2.2', 'default', '2ypo6p', '2yPo6P', '1527544062', NULL);


#
# TABLE STRUCTURE FOR: config
#

DROP TABLE IF EXISTS config;

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

INSERT INTO config (`id`, `nama_desa`, `kode_desa`, `nama_kepala_desa`, `nip_kepala_desa`, `kode_pos`, `nama_kecamatan`, `kode_kecamatan`, `nama_kepala_camat`, `nip_kepala_camat`, `nama_kabupaten`, `kode_kabupaten`, `nama_propinsi`, `kode_propinsi`, `logo`, `lat`, `lng`, `zoom`, `map_tipe`, `path`, `alamat_kantor`, `g_analytic`, `email_desa`, `telepon`, `website`) VALUES ('1', 'Senggig1 ', '05', 'Muhammad Ilham ', '--', '83355', 'Batulay4r ', '14', 'Bambang Budi Sanyoto, S. H', '-', 'Lombok Bar4t ', '01', '', '52', 'opensid_logo__sid__bXziTU1.png', '-8.48782268404703', '116.04083776474', '13', 'HYBRID', '[[[-8.470247273601585,116.03699684143068],[-8.471775371367853,116.04249000549318],[-8.474831548688417,116.04557991027833],[-8.47754813036,116.04334831237793],[-8.478736628804842,116.0522747039795],[-8.48688623339785,116.04712486267091],[-8.492319207044495,116.04626655578615],[-8.492319207044495,116.04866981506349],[-8.490281850938663,116.05433464050294],[-8.499110315926593,116.06446266174318],[-8.507429260374638,116.06068611145021],[-8.509466525358253,116.05605125427248],[-8.501656950751967,116.04969978332521],[-8.501656950751967,116.046781539917],[-8.503694246430312,116.04454994201662],[-8.496820982890759,116.0453224182129],[-8.494953428786745,116.03931427001955],[-8.48986005320605,116.0365676879883],[-8.48493639256516,116.03364944458009],[-8.47975533883251,116.03768348693849]]]', 'Jl. Raya Senggigi Km 10 Kerandangan ', 'gsgsdgsdgsg', '', '', '');


#
# TABLE STRUCTURE FOR: data_persil
#

DROP TABLE IF EXISTS data_persil;

CREATE TABLE `data_persil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(64) NOT NULL,
  `nama` varchar(128) NOT NULL COMMENT 'nomer persil',
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
  `jenis_pemilik` tinyint(2) NOT NULL DEFAULT '1',
  `pemilik_luar` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: data_persil_jenis
#

DROP TABLE IF EXISTS data_persil_jenis;

CREATE TABLE `data_persil_jenis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(128) NOT NULL,
  `ndesc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: data_persil_peruntukan
#

DROP TABLE IF EXISTS data_persil_peruntukan;

CREATE TABLE `data_persil_peruntukan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(128) NOT NULL,
  `ndesc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: detail_log_penduduk
#

DROP TABLE IF EXISTS detail_log_penduduk;

CREATE TABLE `detail_log_penduduk` (
  `id` int(10) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: dokumen
#

DROP TABLE IF EXISTS dokumen;

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

INSERT INTO dokumen (`id`, `satuan`, `nama`, `enabled`, `tgl_upload`, `id_pend`, `kategori`, `attr`) VALUES ('2', 'SK+TIM+Penyusun+RPJMDes+Tahun+2017_uwdc6N_grafik-statistik-ada-jumlah.png', 'SK TIM Penyusun RPJMDes Tahun 2017', '1', '2018-05-28 06:49:28', '0', '2', '{\"uraian\":\"SK TIM Penyusun RPJMDes Tahun 2017\",\"no_kep_kades\":\"1\",\"tgl_kep_kades\":\"13-01-2017\",\"no_lapor\":\"1\",\"tgl_lapor\":\"13-01-2017\",\"keterangan\":\"Sudah Terbit\"}');
INSERT INTO dokumen (`id`, `satuan`, `nama`, `enabled`, `tgl_upload`, `id_pend`, `kategori`, `attr`) VALUES ('3', 'SK+Pengangkatan+RT+dan+Pemberentian+RT+Baru_OzjhwE_surat-kk-peraturan.jpg', 'SK Pengangkatan RT dan Pemberentian RT Baru', '1', '2018-05-28 06:51:53', '0', '2', '{\"uraian\":\"SK Pengangkatan RT dan Pemberentian RT Baru\",\"no_kep_kades\":\"2\",\"tgl_kep_kades\":\"14-01-2017\",\"no_lapor\":\"2\",\"tgl_lapor\":\"14-01-2017\",\"keterangan\":\"Sudah Terbit\"}');
INSERT INTO dokumen (`id`, `satuan`, `nama`, `enabled`, `tgl_upload`, `id_pend`, `kategori`, `attr`) VALUES ('4', 'Perdes+SPJ+Tentang+Keuang+Desa+Tahun+2016_cXJUfP_user-setting-pengaturan.png', 'Perdes SPJ Tentang Keuang Desa Tahun 2016', '1', '2018-05-28 06:57:37', '0', '3', '{\"uraian\":\"Perdes SPJ Tentang Keuang Desa Tahun 2016\",\"jenis_peraturan\":\"Perdes SPJ Tahun 2016\",\"no_ditetapkan\":\"1\",\"tgl_ditetapkan\":\"09-01-2016\",\"tgl_kesepakatan\":\"05-01-2016\",\"no_lapor\":\"1\",\"tgl_lapor\":\"05-01-2016\",\"no_lembaran_desa\":\"1\",\"tgl_lembaran_desa\":\"05-01-2017\",\"no_berita_desa\":\"1\",\"tgl_berita_desa\":\"05-01-2017\",\"keterangan\":\"Sudah Terbit\"}');
INSERT INTO dokumen (`id`, `satuan`, `nama`, `enabled`, `tgl_upload`, `id_pend`, `kategori`, `attr`) VALUES ('5', 'RPJMDes+Miau+Merah+Tahun+2016+s%2Fd+2022_fMaZGt_cetak-log-penduduk.png', 'RPJMDes Miau Merah Tahun 2016 s/d 2022', '1', '2018-05-28 07:09:56', '0', '3', '{\"uraian\":\"Rencana Pembangunan Jangka Menengah Desa\",\"jenis_peraturan\":\"RPJMDes\",\"no_ditetapkan\":\"2\",\"tgl_ditetapkan\":\"13-01-2017\",\"tgl_kesepakatan\":\"13-01-2017\",\"no_lapor\":\"2\",\"tgl_lapor\":\"13-01-2017\",\"no_lembaran_desa\":\"2\",\"tgl_lembaran_desa\":\"14-01-2017\",\"no_berita_desa\":\"2\",\"tgl_berita_desa\":\"14-01-2017\",\"keterangan\":\"Sudah Terbit\"}');


#
# TABLE STRUCTURE FOR: gambar_gallery
#

DROP TABLE IF EXISTS gambar_gallery;

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

INSERT INTO gambar_gallery (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES ('28', '0', 'galeri-1-1.jpg', 'Karnaval Hari Kemerdekaan ', '1', '2016-08-26 14:53:51', '0', NULL);
INSERT INTO gambar_gallery (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES ('29', '0', '', 'Panorama Wisata ', '1', '2016-08-26 14:55:31', '0', NULL);
INSERT INTO gambar_gallery (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES ('30', '28', 'IMG-20160823-WA0116.jpg', 'Karnaval baju adat', '1', '2016-08-26 14:57:10', '2', NULL);
INSERT INTO gambar_gallery (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES ('31', '28', 'galeri-1-2.jpeg', 'Kemeriahan Karnaval', '2', '2016-08-26 14:58:16', '2', NULL);
INSERT INTO gambar_gallery (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES ('32', '29', 'galeri-2-2.jpeg', 'Pantai indah', '1', '2016-09-02 02:14:06', '2', NULL);
INSERT INTO gambar_gallery (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES ('33', '29', 'galeri-2-3.jpeg', 'Kolam renang impian', '1', '2016-09-02 02:14:28', '2', NULL);
INSERT INTO gambar_gallery (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES ('34', '0', '', 'Kegiatan Kantor Desa', '2', '2016-09-02 06:24:59', '0', NULL);
INSERT INTO gambar_gallery (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`) VALUES ('35', '28', '', 'Tarian adat', '1', '2016-09-02 07:32:55', '2', NULL);


#
# TABLE STRUCTURE FOR: garis
#

DROP TABLE IF EXISTS garis;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: gis_simbol
#

DROP TABLE IF EXISTS gis_simbol;

CREATE TABLE `gis_simbol` (
  `simbol` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO gis_simbol (`simbol`) VALUES ('accident.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('accident_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('administration.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('administration_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('aestheticscenter.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('agriculture.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('agriculture2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('agriculture3.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('agriculture4.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('aircraft-small.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('airplane-sport.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('airplane-tourism.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('airport-apron.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('airport-runway.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('airport-terminal.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('airport.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('airport_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('amphitheater-tourism.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('amphitheater.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('ancientmonument.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('ancienttemple.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('ancienttempleruin.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('animals.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('animals_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('anniversary.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('apartment.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('apartment_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('aquarium.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('arch.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('archery.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('artgallery.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('atm.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('atv.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('audio.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('australianfootball.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bags.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bank.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bank_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bankeuro.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bankpound.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bar.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bar_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('baseball.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('basketball.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('baskteball2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('beach.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('beach_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('beautiful.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('beautiful_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bench.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('biblio.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bicycleparking.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bigcity.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('billiard.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bobsleigh.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bomb.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bookstore.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bowling.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bowling_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('boxing.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bread.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bread_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bridge.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bridgemodern.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bullfight.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bungalow.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bus.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('bus_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('butcher.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cabin.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cablecar.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('camping.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('camping_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('campingsite.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('canoe.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('car.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('car_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('carrental.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('carrepair.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('carrepair_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('carwash.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('casino.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('casino_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('castle.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cathedral.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cathedral2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cave.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cemetary.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('chapel.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('church.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('church2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('church_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cinema.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cinema_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('circus.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('citysquare.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('climbing.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('clothes-female.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('clothes-male.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('clothes.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('clothes_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('clouds.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('clouds_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cloudsun.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cloudsun_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('club.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('club_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cluster.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cluster2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cluster3.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cluster4.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cluster5.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cocktail.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('coffee.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('coffee_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('communitycentre.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('company.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('company_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('computer.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('computer_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('concessionaire.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('conference.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('construction.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('convenience.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('convent.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('corral.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('country.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('court.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cricket.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cross.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('crossingguard.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cruise.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('currencyexchange.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('customs.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cycling.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cycling_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cyclingfeedarea.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cyclingmountain1.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cyclingmountain2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cyclingmountain3.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cyclingmountain4.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cyclingmountainnotrated.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cyclingsport.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cyclingsprint.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('cyclinguncategorized.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('dam.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('dancinghall.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('dates.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('dates_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('daycare.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-dim.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-dom.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-jeu.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-jue.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-lun.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-mar.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-mer.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-mie.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-qua.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-qui.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-sab.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-sam.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-seg.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-sex.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-ter.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-ven.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('days-vie.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('default.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('dentist.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('deptstore.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('disability.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('disability_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('disabledparking.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('diving.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('doctor.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('doctor_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('dog-leash.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('dog-offleash.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('door.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('down.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('downleft.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('downright.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('downthenleft.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('downthenright.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('drinkingfountain.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('drinkingwater.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('drugs.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('drugs_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('elevator.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('embassy.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('emblem-art.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('emblem-photos.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('entrance.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('escalator-down.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('escalator-up.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('exit.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('expert.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('explosion.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('face-devilish.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('face-embarrassed.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('factory.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('factory_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fallingrocks.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('family.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('farm.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('farm_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fastfood.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fastfood_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('festival-itinerant.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('festival.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('findajob.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('findjob.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('findjob_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fire-extinguisher.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fire.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('firemen.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('firemen_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fireworks.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('firstaid.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fishing.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fishing_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fishingshop.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fitnesscenter.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fjord.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('flood.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('flowers.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('flowers_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('followpath.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('foodtruck.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('forest.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fortress.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fossils.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('fountain.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('friday.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('friday_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('friends.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('friends_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('garden.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('gateswalls.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('gazstation.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('gazstation_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('geyser.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('gifts.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('girlfriend.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('girlfriend_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('glacier.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('golf.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('golf_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('gondola.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('gourmet.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('grocery.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('gun.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('gym.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hairsalon.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('handball.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hanggliding.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hats.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('headstone.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('headstonejewish.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('helicopter.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('highway.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('highway_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hiking-tourism.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hiking.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hiking_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('historicalquarter.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('home.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('home_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('horseriding.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('horseriding_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hospital.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hospital_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hostel.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hotairballoon.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hotel.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hotel1star.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hotel2stars.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hotel3stars.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hotel4stars.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hotel5stars.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hotel_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('house.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('hunting.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('icecream.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('icehockey.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('iceskating.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('im-user.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('index.html');
INSERT INTO gis_simbol (`simbol`) VALUES ('info.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('info_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('jewelry.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('jewishquarter.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('jogging.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('judo.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('justice.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('justice_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('karate.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('karting.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('kayak.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('laboratory.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('lake.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('laundromat.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('left.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('leftthendown.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('leftthenup.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('library.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('library_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('lighthouse.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('liquor.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('lock.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('lockerrental.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('magicshow.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('mainroad.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('massage.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('military.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('military_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('mine.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('mobilephonetower.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('modernmonument.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('moderntower.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('monastery.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('monday.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('monday_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('monument.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('mosque.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('motorbike.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('motorcycle.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('movierental.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('museum-archeological.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('museum-art.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('museum-crafts.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('museum-historical.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('museum-naval.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('museum-science.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('museum-war.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('museum.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('museum_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('music-classical.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('music-hiphop.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('music-live.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('music-rock.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('music.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('music_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('nanny.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('newsagent.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('nordicski.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('nursery.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('observatory.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('oilpumpjack.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('olympicsite.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('ophthalmologist.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('pagoda.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('paint.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('palace.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('panoramic.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('panoramic180.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('park-urban.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('park.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('park_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('parkandride.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('parking.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('parking_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('party.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('patisserie.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('pedestriancrossing.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('pend.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('pens.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('perfumery.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('personal.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('personalwatercraft.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('petroglyphs.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('pets.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('phones.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('photo.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('photodown.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('photodownleft.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('photodownright.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('photography.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('photoleft.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('photoright.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('photoup.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('photoupleft.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('photoupright.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('picnic.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('pizza.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('pizza_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('places-unvisited.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('places-visited.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('planecrash.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('playground.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('playground_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('poker.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('poker_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('police.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('police2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('police_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('pool-indoor.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('pool.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('pool_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('port.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('port_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('postal.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('postal_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('powerlinepole.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('powerplant.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('powersubstation.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('prison.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('publicart.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('racing.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('radiation.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('rain_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('rain_3.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('rattlesnake.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('realestate.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('realestate_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('recycle.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('recycle_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('recycle_3.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('regroup.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('regulier.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('resort.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurant-barbecue.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurant-buffet.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurant-fish.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurant-romantic.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurant.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurant_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantafrican.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantchinese.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantchinese_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantfishchips.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantgourmet.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantgreek.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantindian.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantitalian.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantjapanese.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantjapanese_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantkebab.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantkorean.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantmediterranean.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantmexican.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantthai.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('restaurantturkish.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('revolution.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('right.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('rightthendown.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('rightthenup.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('riparian.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('ropescourse.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('rowboat.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('rugby.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('ruins.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sailboat-sport.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sailboat-tourism.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sailboat.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('salle-fete.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('satursday.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('satursday_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sauna.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('school.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('school_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('schrink.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('schrink_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sciencecenter.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('seals.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('seniorsite.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('shadow.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('shelter-picnic.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('shelter-sleeping.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('shoes.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('shoes_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('shoppingmall.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('shore.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('shower.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sight.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('skateboarding.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('skiing.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('skiing_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('skijump.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('skilift.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('smallcity.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('smokingarea.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sneakers.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('snow.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('snowboarding.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('snowmobiling.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('snowshoeing.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('soccer.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('soccer2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('soccer_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('spaceport.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('spectacle.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed100.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed110.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed120.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed130.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed20.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed30.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed40.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed50.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed60.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed70.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed80.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speed90.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('speedhump.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('spelunking.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('stadium.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('statue.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('steamtrain.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('stop.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('stoplight.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('stoplight_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('strike.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('strike1.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('subway.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sun.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sun_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sunday.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('sunday_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('supermarket.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('supermarket_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('surfing.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('suv.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('synagogue.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tailor.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tapas.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('taxi.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('taxi_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('taxiway.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('teahouse.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('telephone.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('templehindu.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tennis.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tennis2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tennis_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tent.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('terrace.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('text.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('textiles.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('theater.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('theater_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('themepark.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('thunder.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('thunder_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('thursday.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('thursday_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('toilets.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('toilets_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tollstation.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tools.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tower.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('toys.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('toys_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('trafficenforcementcamera.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('train.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('train_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tram.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('trash.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('truck.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('truck_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tuesday.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tuesday_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('tunnel.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('turnleft.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('turnright.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('university.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('university_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('unnamed.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('up.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('upleft.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('upright.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('upthenleft.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('upthenright.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('usfootball.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('vespa.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('vet.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('video.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('videogames.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('videogames_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('villa.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('waitingroom.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('water.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('waterfall.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('watermill.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('waterpark.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('waterskiing.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('watertower.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('waterwell.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('waterwellpump.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('wedding.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('wednesday.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('wednesday_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('wetland.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('white1.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('white20.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('wifi.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('wifi_2.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('windmill.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('windsurfing.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('windturbine.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('winery.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('wineyard.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('workoffice.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('world.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('worldheritagesite.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('yoga.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('youthhostel.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('zipline.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('zoo.png');
INSERT INTO gis_simbol (`simbol`) VALUES ('zoo_2.png');


#
# TABLE STRUCTURE FOR: inbox
#

DROP TABLE IF EXISTS inbox;

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

DROP TABLE IF EXISTS inventaris_asset;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO inventaris_asset (`id`, `nama_barang`, `kode_barang`, `register`, `jenis`, `judul_buku`, `spesifikasi_buku`, `asal_daerah`, `pencipta`, `bahan`, `jenis_hewan`, `ukuran_hewan`, `jenis_tumbuhan`, `ukuran_tumbuhan`, `jumlah`, `tahun_pengadaan`, `asal`, `harga`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `visible`) VALUES ('1', 'Server SID', '2', '1', 'Barang Kesenian', '', '', '', '', '', '', '', '', '', '23', '2018', 'Bantuan Pemerintah', '343', 'dsff', '2018-06-20 02:14:04', '0', '0000-00-00 00:00:00', '0', '1', '0');


#
# TABLE STRUCTURE FOR: inventaris_gedung
#

DROP TABLE IF EXISTS inventaris_gedung;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO inventaris_gedung (`id`, `nama_barang`, `kode_barang`, `register`, `kondisi_bangunan`, `kontruksi_bertingkat`, `kontruksi_beton`, `luas_bangunan`, `letak`, `tanggal_dokument`, `no_dokument`, `luas`, `status_tanah`, `kode_tanah`, `asal`, `harga`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `visible`) VALUES ('1', 'sdf', 'sdfs', 'sdfs', 'Rusak Ringan', '1', '0', '234', 'sdfsfs', '2018-06-14', 'sdfs', '234', 'Tanah Negara', 'sdfs', 'Bantuan Pemerintah', '234', 'xdvs', '2018-06-20 06:40:14', '0', '0000-00-00 00:00:00', '0', '1', '0');
INSERT INTO inventaris_gedung (`id`, `nama_barang`, `kode_barang`, `register`, `kondisi_bangunan`, `kontruksi_bertingkat`, `kontruksi_beton`, `luas_bangunan`, `letak`, `tanggal_dokument`, `no_dokument`, `luas`, `status_tanah`, `kode_tanah`, `asal`, `harga`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `visible`) VALUES ('2', 'what', '234rf', '234', 'Baik', '1', '1', '234', 'sdfds', '2018-06-06', '2342', '234', 'Tanah milik Pemda', '234', 'Bantuan Pemerintah', '32', 'sdfsd', '2018-06-20 07:17:15', '0', '0000-00-00 00:00:00', '0', '0', '0');


#
# TABLE STRUCTURE FOR: inventaris_jalan
#

DROP TABLE IF EXISTS inventaris_jalan;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO inventaris_jalan (`id`, `nama_barang`, `kode_barang`, `register`, `kontruksi`, `panjang`, `lebar`, `luas`, `letak`, `tanggal_dokument`, `no_dokument`, `status_tanah`, `kode_tanah`, `kondisi`, `asal`, `harga`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `visible`) VALUES ('1', 'sdfssdf', '3rrw', 'sdfsfs', 'sdfsf', '234', '444', '555', 'sdf', '2018-06-07', '24', 'Tanah milik Pemda', 'sfsfsf', 'Baik', 'Bantuan Pemerintah', '343452', 'xvsfvsf', '2018-06-20 07:25:30', '0', '0000-00-00 00:00:00', '0', '1', '0');


#
# TABLE STRUCTURE FOR: inventaris_kontruksi
#

DROP TABLE IF EXISTS inventaris_kontruksi;

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

DROP TABLE IF EXISTS inventaris_peralatan;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO inventaris_peralatan (`id`, `nama_barang`, `kode_barang`, `register`, `merk`, `ukuran`, `bahan`, `tahun_pengadaan`, `no_pabrik`, `no_rangka`, `no_mesin`, `no_polisi`, `no_bpkb`, `asal`, `harga`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `visible`) VALUES ('1', 'Server SID', '234', '1', 'merek', '2', 'b', '2018', '', '', '', '', '', 'Pembelian Sendiri', '3000', 'whatever', '2018-06-18 01:38:09', '0', '0000-00-00 00:00:00', '0', '1', '0');
INSERT INTO inventaris_peralatan (`id`, `nama_barang`, `kode_barang`, `register`, `merk`, `ukuran`, `bahan`, `tahun_pengadaan`, `no_pabrik`, `no_rangka`, `no_mesin`, `no_polisi`, `no_bpkb`, `asal`, `harga`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `visible`) VALUES ('2', 'Apa saja', '2', '2', 'whatever', '45', 'ok', '2017', '', '', '', '', '', 'Pembelian Sendiri', '4000', 'Something', '2018-06-18 01:44:57', '0', '0000-00-00 00:00:00', '0', '0', '0');
INSERT INTO inventaris_peralatan (`id`, `nama_barang`, `kode_barang`, `register`, `merk`, `ukuran`, `bahan`, `tahun_pengadaan`, `no_pabrik`, `no_rangka`, `no_mesin`, `no_polisi`, `no_bpkb`, `asal`, `harga`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `visible`) VALUES ('3', 'something', '34', '3', 'anything', '343', 'sfd', '2017', '', '', '', '', '', 'Pembelian Sendiri', '343', 'sdfsf', '2018-06-18 05:48:13', '0', '0000-00-00 00:00:00', '0', '0', '0');
INSERT INTO inventaris_peralatan (`id`, `nama_barang`, `kode_barang`, `register`, `merk`, `ukuran`, `bahan`, `tahun_pengadaan`, `no_pabrik`, `no_rangka`, `no_mesin`, `no_polisi`, `no_bpkb`, `asal`, `harga`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `visible`) VALUES ('4', 'dfsdfs', '2342', '345', 'merek', '234234', 'sdfsf', '2018', '23423', 'werwer234234', '34234', 'w2342424', 'r5rr2', 'Bantuan Pemerintah', '5345345', 'dfgdgd', '2018-06-20 11:21:00', '0', '0000-00-00 00:00:00', '0', '0', '0');


#
# TABLE STRUCTURE FOR: inventaris_tanah
#

DROP TABLE IF EXISTS inventaris_tanah;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO inventaris_tanah (`id`, `nama_barang`, `kode_barang`, `register`, `luas`, `tahun_pengadaan`, `letak`, `hak`, `no_sertifikat`, `tanggal_sertifikat`, `penggunaan`, `asal`, `harga`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `visible`) VALUES ('1', 'Server SID', '234', '1', '122', '2018', '53 Giffard Street', 'Hak Pakai', '343', '2018-06-06', 'Permukiman', 'Bantuan Pemerintah', '34544', 'dfsdfs', '2018-06-15 05:55:30', '0', '0000-00-00 00:00:00', '0', '1', '0');
INSERT INTO inventaris_tanah (`id`, `nama_barang`, `kode_barang`, `register`, `luas`, `tahun_pengadaan`, `letak`, `hak`, `no_sertifikat`, `tanggal_sertifikat`, `penggunaan`, `asal`, `harga`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `visible`) VALUES ('2', 'ewwer', '3453', '3r43r3', '23452', '2017', 'fffsfs', 'Hak Pakai', '23423', '2018-06-07', 'Permukiman', 'Bantuan Provinsi', '2342342', 'dfgdfgdfg', '2018-06-20 12:09:03', '0', '0000-00-00 00:00:00', '0', '0', '0');


#
# TABLE STRUCTURE FOR: kategori
#

DROP TABLE IF EXISTS kategori;

CREATE TABLE `kategori` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(100) NOT NULL,
  `tipe` int(4) NOT NULL DEFAULT '1',
  `urut` tinyint(4) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `parrent` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

INSERT INTO kategori (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES ('1', 'Berita Desa', '1', '1', '1', '0');
INSERT INTO kategori (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES ('2', 'Produk Desa', '1', '3', '2', '0');
INSERT INTO kategori (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES ('4', 'Agenda Desa', '2', '2', '1', '0');
INSERT INTO kategori (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES ('5', 'Peraturan Desa', '2', '5', '1', '0');
INSERT INTO kategori (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES ('6', 'Laporan Desa', '2', '6', '2', '0');
INSERT INTO kategori (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES ('8', 'Panduan Layanan Desa', '2', '3', '2', '0');
INSERT INTO kategori (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES ('17', 'Peraturan Kebersihan Desa', '1', '0', '2', '5');
INSERT INTO kategori (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES ('20', 'Berita Lokal', '1', '0', '2', '1');
INSERT INTO kategori (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES ('21', 'Berita Kriminal', '1', '0', '2', '1');
INSERT INTO kategori (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`) VALUES ('22', 'teks_berjalan', '1', '0', '1', '0');


#
# TABLE STRUCTURE FOR: kelompok
#

DROP TABLE IF EXISTS kelompok;

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

DROP TABLE IF EXISTS kelompok_anggota;

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

DROP TABLE IF EXISTS kelompok_master;

CREATE TABLE `kelompok_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kelompok` varchar(50) NOT NULL,
  `deskripsi` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO kelompok_master (`id`, `kelompok`, `deskripsi`) VALUES ('1', 'Kelompok Ternak', '<p>Kelompok yang memelihara ternak</p>');


#
# TABLE STRUCTURE FOR: klasifikasi_analisis_keluarga
#

DROP TABLE IF EXISTS klasifikasi_analisis_keluarga;

CREATE TABLE `klasifikasi_analisis_keluarga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) NOT NULL,
  `jenis` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: komentar
#

DROP TABLE IF EXISTS komentar;

CREATE TABLE `komentar` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_artikel` int(7) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `komentar` text NOT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enabled` int(2) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO komentar (`id`, `id_artikel`, `owner`, `email`, `komentar`, `tgl_upload`, `enabled`) VALUES ('8', '95', 'Penduduk Biasa', 'penduduk@desaku.desa.id', 'Selamat atas keberhasilan Senggigi merayakan Hari Kemerdeakaan 2016!', '2016-09-14 06:09:16', '1');
INSERT INTO komentar (`id`, `id_artikel`, `owner`, `email`, `komentar`, `tgl_upload`, `enabled`) VALUES ('9', '775', 'AHMAD ALLIF RIZKI', '5201140706966997', 'Harap alamat keluarga kami diperbaik menjadi RT 002 Dusun Mangsit. \n\nTerima kasih.', '2016-09-14 07:44:59', '1');
INSERT INTO komentar (`id`, `id_artikel`, `owner`, `email`, `komentar`, `tgl_upload`, `enabled`) VALUES ('10', '775', 'DENATUL SUARTINI', '3275014601977005', 'Saya ke kantor desa kemarin jam 12:30 siang, tetapi tidak ada orang. Anak kami akan pergi ke Yogyakarta untuk kuliah selama 4 tahun. Apakah perlu kami laporkan?', '2016-09-14 10:49:34', '2');
INSERT INTO komentar (`id`, `id_artikel`, `owner`, `email`, `komentar`, `tgl_upload`, `enabled`) VALUES ('11', '775', 'DENATUL SUARTINI', '3275014601977005', 'Laporan ini tidak relevan. Hanya berisi komentar saja.', '2016-09-14 11:05:02', '2');


#
# TABLE STRUCTURE FOR: kontak
#

DROP TABLE IF EXISTS kontak;

CREATE TABLE `kontak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pend` int(11) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: kontak_grup
#

DROP TABLE IF EXISTS kontak_grup;

CREATE TABLE `kontak_grup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_grup` varchar(30) NOT NULL,
  `id_kontak` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: line
#

DROP TABLE IF EXISTS line;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO line (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES ('1', 'Jalan', '', 'FFCD42', '0', '1', '1');
INSERT INTO line (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES ('2', 'Jalan Raya', '', 'FFCD42', '2', '66', '1');
INSERT INTO line (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES ('3', 'Jalan Kampung', '', '', '2', '66', '1');
INSERT INTO line (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES ('4', 'Ring Road', '', '', '2', '66', '1');
INSERT INTO line (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES ('5', 'Sungai', '', 'FFFFFF', '0', '1', '1');
INSERT INTO line (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES ('6', 'Selokan', '', '', '2', '70', '1');


#
# TABLE STRUCTURE FOR: log_bulanan
#

DROP TABLE IF EXISTS log_bulanan;

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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('1', '97', '46', '51', '37', '2017-04-11 02:01:54', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('2', '97', '46', '51', '37', '2017-05-10 21:03:26', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('3', '97', '46', '51', '37', '2017-06-05 10:08:30', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('4', '97', '46', '51', '37', '2017-07-03 12:19:17', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('5', '97', '46', '51', '37', '2017-08-01 01:37:30', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('6', '97', '46', '51', '37', '2017-09-05 06:13:41', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('7', '97', '46', '51', '37', '2017-10-29 09:37:57', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('8', '97', '46', '51', '37', '2017-11-28 01:51:11', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('9', '97', '46', '51', '37', '2017-12-27 05:03:39', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('10', '97', '46', '51', '37', '2018-01-26 05:30:07', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('11', '97', '46', '51', '37', '2018-03-01 05:47:41', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('12', '97', '46', '51', '37', '2018-03-31 22:40:49', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('13', '97', '46', '51', '37', '2018-03-31 22:40:52', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('14', '97', '46', '51', '37', '2018-03-31 22:40:52', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('15', '97', '46', '51', '37', '2018-03-31 22:40:55', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('16', '97', '46', '51', '37', '2018-03-31 22:40:57', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('17', '97', '46', '51', '37', '2018-03-31 22:40:58', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('18', '97', '46', '51', '37', '2018-03-31 22:40:59', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('19', '97', '46', '51', '37', '2018-03-31 22:41:03', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('20', '97', '46', '51', '37', '2018-03-31 22:41:03', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('21', '97', '46', '51', '37', '2018-03-31 22:41:10', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('22', '97', '46', '51', '37', '2018-03-31 22:41:13', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('23', '97', '46', '51', '37', '2018-03-31 22:41:14', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('24', '97', '46', '51', '37', '2018-04-26 06:39:57', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('25', '97', '46', '51', '37', '2018-05-16 17:50:29', '28', '9', NULL, NULL);
INSERT INTO log_bulanan (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES ('26', '97', '46', '51', '37', '2018-06-28 03:28:20', '28', '9', '0', '0');


#
# TABLE STRUCTURE FOR: log_keluarga
#

DROP TABLE IF EXISTS log_keluarga;

CREATE TABLE `log_keluarga` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_kk` int(11) NOT NULL,
  `kk_sex` tinyint(2) DEFAULT NULL,
  `id_peristiwa` int(4) NOT NULL,
  `tgl_peristiwa` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_kk` (`id_kk`,`id_peristiwa`,`tgl_peristiwa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: log_penduduk
#

DROP TABLE IF EXISTS log_penduduk;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: log_perubahan_penduduk
#

DROP TABLE IF EXISTS log_perubahan_penduduk;

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

DROP TABLE IF EXISTS log_surat;

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

INSERT INTO log_surat (`id`, `id_format_surat`, `id_pend`, `id_pamong`, `id_user`, `tanggal`, `bulan`, `tahun`, `no_surat`, `nama_surat`, `lampiran`, `nik_non_warga`, `nama_non_warga`) VALUES ('6', '89', '1', '14', '1', '2017-12-31 22:26:52', '12', '2017', '234', 'surat_permohonan_perubahan_kartu_keluarga_5201142005716996_2017-12-31_234.rtf', 'surat_permohonan_perubahan_kartu_keluarga_5201142005716996_2017-12-31_234_lampiran.pdf', NULL, NULL);


#
# TABLE STRUCTURE FOR: lokasi
#

DROP TABLE IF EXISTS lokasi;

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

INSERT INTO lokasi (`id`, `desk`, `nama`, `enabled`, `lat`, `lng`, `ref_point`, `foto`, `id_cluster`) VALUES ('1', 'Sekolah Menengah Pertama', 'SMP', '1', '-8.49563254042209', '116.04755401611328', '5', '', '0');
INSERT INTO lokasi (`id`, `desk`, `nama`, `enabled`, `lat`, `lng`, `ref_point`, `foto`, `id_cluster`) VALUES ('2', 'Sekolah Menengah Atas', 'SMA', '1', '-8.485106175017545', '116.04549407958986', '4', '', '0');
INSERT INTO lokasi (`id`, `desk`, `nama`, `enabled`, `lat`, `lng`, `ref_point`, `foto`, `id_cluster`) VALUES ('3', 'Sarana Pendidikan', 'SP', '1', '-8.478145032940077', '116.0394859313965', '10', '', '0');


#
# TABLE STRUCTURE FOR: media_sosial
#

DROP TABLE IF EXISTS media_sosial;

CREATE TABLE `media_sosial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gambar` text NOT NULL,
  `link` text NOT NULL,
  `nama` varchar(100) NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO media_sosial (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES ('1', 'fb.png', 'https://www.facebook.com/groups/OpenSID/', 'Facebook', '1');
INSERT INTO media_sosial (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES ('2', 'twt.png', '', 'Twitter', '1');
INSERT INTO media_sosial (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES ('3', 'goo.png', '', 'Google Plus', '1');
INSERT INTO media_sosial (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES ('4', 'yb.png', '', 'YouTube', '1');
INSERT INTO media_sosial (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES ('5', 'ins.png', '', 'Instagram', '1');


#
# TABLE STRUCTURE FOR: menu
#

DROP TABLE IF EXISTS menu;

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

INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('16', 'Profil Desa', 'artikel/32', '1', '1', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('17', 'Pemerintahan Desa', 'artikel/85', '1', '1', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('19', 'Lembaga Masyarakat', 'artikel/38', '1', '1', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('23', 'Teras Desa', '', '2', '1', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('24', 'Data Desa', 'artikel/97', '1', '1', '0', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('31', 'Data Wilayah Administratif', 'wilayah', '3', '24', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('32', 'Data Pendidikan dalam KK', 'statistik/0', '3', '24', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('33', 'Data Pendidikan Ditempuh', 'statistik/14', '3', '24', '0', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('34', 'Data Pekerjaan', 'statistik/1', '3', '24', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('35', 'Data Agama', 'statistik/3', '3', '24', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('36', 'Data Jenis Kelamin', 'statistik/4', '3', '24', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('40', 'Data Golongan Darah', 'statistik/7', '3', '24', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('51', 'Data Kelompok Umur', 'statistik/12', '3', '24', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('52', 'Data Penerima Raskin', 'statistik_k/2', '3', '24', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('53', 'Data Penerima Jamkesmas', 'statistik_k/3', '3', '24', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('55', 'Profil Wilayah Desa', 'artikel/33', '3', '16', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('56', 'Profil Masyarakat Desa', 'artikel/34', '3', '16', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('57', 'Visi dan Misi', 'artikel/93', '3', '17', '0', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('58', 'Pemerintah Desa', 'artikel/92', '3', '17', '0', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('59', 'Badan Permusyawaratan Desa', 'artikel/37', '3', '17', '0', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('62', 'Berita Desa', '', '2', '1', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('63', 'Agenda Desa', 'artikel/41', '2', '1', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('64', 'Peraturan Desa', 'peraturan', '2', '1', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('65', 'Panduan Layanan Desa', '#', '2', '1', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('66', 'Produk Desa', 'produk', '2', '1', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('68', 'Undang undang', 'artikel/42', '3', '64', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('69', 'Peraturan Pemerintah', 'artikel/43', '3', '64', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('70', 'Peraturan Daerah', '', '3', '64', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('71', 'Peraturan Bupati', '', '3', '64', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('72', 'Peraturan Bersama KaDes', '', '3', '64', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('73', 'Informasi Publik', '#', '2', '1', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('75', 'Rencana Kerja Anggaran', '', '3', '73', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('76', 'RAPB Desa', '', '3', '73', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('77', 'APB Desa', '', '3', '73', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('78', 'DPA', '', '3', '73', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('80', 'Profil Potensi Desa', 'artikel/59', '3', '16', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('84', 'LKMD', 'artikel/62', '3', '18', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('85', 'PKK', 'artikel/63', '3', '18', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('86', 'Karang Taruna', 'artikel/64', '3', '18', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('87', 'RT RW', 'artikel/65', '3', '18', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('88', 'Linmas', 'artikel/70', '3', '18', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('89', 'TKP2KDes', 'artikel/66', '3', '18', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('90', 'KPAD', 'artikel/67', '3', '18', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('91', 'Kelompok Ternak', 'artikel/68', '3', '18', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('92', 'Kelompok Tani', 'artikel/69', '3', '18', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('93', 'Kelompok Ekonomi Lainya', 'artikel/71', '3', '18', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('98', 'LKPJ', '', '3', '73', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('99', 'LPPD', '', '3', '73', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('100', 'ILPPD', '', '3', '73', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('101', 'Peraturan Desa', 'artikel/44', '3', '64', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('102', 'Peraturan Kepala Desa', 'artikel/45', '3', '64', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('103', 'Keputusan Kepala Desa', 'artikel/46', '3', '64', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('104', 'PBB', '', '3', '73', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('106', 'Data Warga Negara', 'statistik/13', '3', '24', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('108', 'Data Kelas Sosial', 'statistik_k/1', '3', '24', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('109', 'Kontak', 'artikel/36', '1', '1', '1', '2', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('110', 'Peraturan Desa', 'peraturan', '3', '66', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('112', 'Coba', 'coba', '2', '1', '1', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('113', '', '', '3', '109', '0', '1', NULL);
INSERT INTO menu (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES ('114', 'Sejarah Desa', 'artikel/99', '3', '16', '0', '1', NULL);


#
# TABLE STRUCTURE FOR: mutasi_inventaris_asset
#

DROP TABLE IF EXISTS mutasi_inventaris_asset;

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

DROP TABLE IF EXISTS mutasi_inventaris_gedung;

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

DROP TABLE IF EXISTS mutasi_inventaris_jalan;

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

DROP TABLE IF EXISTS mutasi_inventaris_peralatan;

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

DROP TABLE IF EXISTS mutasi_inventaris_tanah;

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
# TABLE STRUCTURE FOR: outbox
#

DROP TABLE IF EXISTS outbox;

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

DROP TABLE IF EXISTS pertanyaan;

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

DROP TABLE IF EXISTS point;

CREATE TABLE `point` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `tipe` int(4) NOT NULL,
  `parrent` int(4) NOT NULL DEFAULT '1',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parrent` (`parrent`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

INSERT INTO point (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES ('1', 'Sarana Pendidikan', 'face-embarrassed.png', '0', '1', '1');
INSERT INTO point (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES ('2', 'Sarana Transportasi', 'face-devilish.png', '0', '1', '1');
INSERT INTO point (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES ('3', 'Sarana Kesehatan', 'emblem-photos.png', '0', '1', '1');
INSERT INTO point (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES ('4', 'SMA', 'gateswalls.png', '2', '38', '1');
INSERT INTO point (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES ('5', 'SMP (Sekolah Menengah Pertama)', 'arch.png', '2', '38', '1');
INSERT INTO point (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES ('6', 'Masjid', 'mosque.png', '2', '54', '1');
INSERT INTO point (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES ('7', 'Tempat Ibadah', 'emblem-art.png', '0', '1', '1');
INSERT INTO point (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES ('8', 'Kuil', 'moderntower.png', '2', '54', '1');
INSERT INTO point (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES ('9', 'RS', 'accerciser.png', '2', '40', '1');
INSERT INTO point (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES ('10', 'Sarana Pendidikan', 'cabin.png', '2', '38', '1');


#
# TABLE STRUCTURE FOR: polygon
#

DROP TABLE IF EXISTS polygon;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO polygon (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES ('1', 'rawan topan', '', '7C78FF', '0', '1', '1');
INSERT INTO polygon (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES ('2', 'jalur selokan', '', 'F4FF59', '0', '1', '1');


#
# TABLE STRUCTURE FOR: program
#

DROP TABLE IF EXISTS program;

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

INSERT INTO program (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`) VALUES ('1', 'Raskin', '2', '', '2015-12-13', '2017-12-13', '0', NULL);
INSERT INTO program (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`) VALUES ('2', 'BLSM', '2', '', '2015-12-13', '2017-12-13', '0', NULL);
INSERT INTO program (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`) VALUES ('3', 'PKH', '2', '', '2015-12-13', '2017-12-13', '0', NULL);
INSERT INTO program (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`) VALUES ('4', 'Bedah Rumah', '2', '', '2015-12-13', '2017-12-13', '0', NULL);
INSERT INTO program (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`) VALUES ('5', 'JAMKESMAS', '1', '', '2015-12-13', '2017-12-13', '0', NULL);


#
# TABLE STRUCTURE FOR: program_peserta
#

DROP TABLE IF EXISTS program_peserta;

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

INSERT INTO program_peserta (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES ('2', '5201140104126994', '1', '2', NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO program_peserta (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES ('3', '5201140105136997', '1', '2', NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO program_peserta (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES ('4', '5201140104126995', '2', '2', NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO program_peserta (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES ('5', '5201140105136997', '2', '2', NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO program_peserta (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES ('6', '5201140104126995', '3', '2', NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO program_peserta (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES ('7', '5201140105136997', '3', '2', NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO program_peserta (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES ('8', '5201140104166999', '4', '2', NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO program_peserta (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES ('9', '5201140105136997', '4', '2', NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO program_peserta (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES ('10', '5201142005716996', '5', '2', NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO program_peserta (`id`, `peserta`, `program_id`, `sasaran`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES ('11', '5201140706966997', '5', '2', NULL, NULL, NULL, NULL, NULL, NULL, '');


#
# TABLE STRUCTURE FOR: provinsi
#

DROP TABLE IF EXISTS provinsi;

CREATE TABLE `provinsi` (
  `kode` tinyint(2) NOT NULL DEFAULT '0',
  `nama` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO provinsi (`kode`, `nama`) VALUES ('11', 'Aceh');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('12', 'Sumatera Utara');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('13', 'Sumatera Barat');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('14', 'Riau');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('15', 'Jambi');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('16', 'Sumatera Selatan');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('17', 'Bengkulu');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('18', 'Lampung');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('19', 'Kepulauan Bangka Belitung');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('21', 'Kepulauan Riau');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('31', 'DKI Jakarta');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('32', 'Jawa Barat');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('33', 'Jawa Tengah');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('34', 'DI Yogyakarta');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('35', 'Jawa Timur');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('36', 'Banten');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('51', 'Bali');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('52', 'Nusa Tenggara Barat');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('53', 'Nusa Tenggara Timur');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('61', 'Kalimantan Barat');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('62', 'Kalimantan Tengah');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('63', 'Kalimantan Selatan');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('64', 'Kalimantan Timur');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('65', 'Kalimantan Utara');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('71', 'Sulawesi Utara');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('72', 'Sulawesi Tengah');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('73', 'Sulawesi Selatan');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('74', 'Sulawesi Tenggara');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('75', 'Gorontalo');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('76', 'Sulawesi Barat');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('81', 'Maluku');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('82', 'Maluku Utara');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('91', 'Papua');
INSERT INTO provinsi (`kode`, `nama`) VALUES ('92', 'Papua Barat');


#
# TABLE STRUCTURE FOR: sentitems
#

DROP TABLE IF EXISTS sentitems;

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

DROP TABLE IF EXISTS setting_aplikasi;

CREATE TABLE `setting_aplikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `jenis` varchar(30) DEFAULT NULL,
  `kategori` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('1', 'sebutan_kabupaten', 'kabupaten', 'Pengganti sebutan wilayah kabupaten', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('2', 'sebutan_kabupaten_singkat', 'kab.', 'Pengganti sebutan singkatan wilayah kabupaten', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('3', 'sebutan_kecamatan', 'kecamatan', 'Pengganti sebutan wilayah kecamatan', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('4', 'sebutan_kecamatan_singkat', 'kec.', 'Pengganti sebutan singkatan wilayah kecamatan', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('5', 'sebutan_desa', 'desa', 'Pengganti sebutan wilayah desa', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('6', 'sebutan_dusun', 'dusun', 'Pengganti sebutan wilayah dusun', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('7', 'sebutan_camat', 'camat', 'Pengganti sebutan jabatan camat', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('8', 'website_title', 'Website Resmi', 'Judul tab browser modul web', '', 'web');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('9', 'login_title', 'OpenSID', 'Judul tab browser halaman login modul administrasi', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('10', 'admin_title', 'Sistem Informasi Desa', 'Judul tab browser modul administrasi', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('11', 'web_theme', 'default', 'Tema penampilan modul web', '', 'web');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('12', 'offline_mode', '0', 'Apakah modul web akan ditampilkan atau tidak', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('13', 'enable_track', '1', 'Apakah akan mengirimkan data statistik ke tracker', 'boolean', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('14', 'dev_tracker', '', 'Host untuk tracker pada development', '', 'development');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('15', 'nomor_terakhir_semua_surat', '0', 'Gunakan nomor surat terakhir untuk seluruh surat tidak per jenis surat', 'boolean', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('16', 'google_key', '', 'Google API Key untuk Google Maps', '', 'web');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('17', 'libreoffice_path', '', 'Path tempat instal libreoffice di server SID', '', '');
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('18', 'sumber_gambar_slider', '1', 'Sumber gambar slider besar', NULL, NULL);
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('19', 'sebutan_singkatan_kadus', 'kawil', 'Sebutan singkatan jabatan kepala dusun', NULL, NULL);
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('20', 'current_version', '18.06', 'Versi sekarang untuk migrasi', NULL, NULL);
INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('21', 'timezone', 'Asia/Jakarta', 'Zona waktu perekaman waktu dan tanggal', NULL, NULL);


#
# TABLE STRUCTURE FOR: setting_modul
#

DROP TABLE IF EXISTS setting_modul;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('1', 'SID Home', 'hom_desa', '1', 'go-home-5.png', '1', '2', '1', 'fa fa-home fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('2', 'Penduduk', 'penduduk/clear', '1', 'preferences-contact-list.png', '2', '2', '0', 'fa fa-group fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('3', 'Statistik', 'statistik', '1', 'statistik.png', '3', '2', '0', 'fa fa-bar-chart fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('4', 'Cetak Surat', 'surat', '1', 'applications-office-5.png', '4', '2', '0', 'fa fa-print fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('5', 'Analisis', 'analisis_master/clear', '1', 'analysis.png', '7', '2', '0', 'fa fa-dashboard fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('6', 'Bantuan', 'program_bantuan/clear', '1', 'program.png', '8', '2', '0', 'fa fa-folder-open fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('7', 'Persil', 'data_persil/clear', '1', 'persil.png', '9', '2', '0', 'fa fa-road fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('8', 'Plan', 'plan', '1', 'plan.png', '10', '2', '0', 'fa fa-sitemap fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('9', 'Peta', 'gis', '1', 'gis.png', '11', '2', '0', 'fa fa-map fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('10', 'SMS', 'sms', '1', 'mail-send-receive.png', '12', '2', '0', 'fa fa-envelope-o fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('11', 'Pengguna', 'man_user/clear', '1', 'system-users.png', '13', '1', '1', 'fa fa-user-plus fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('12', 'Database', 'database', '1', 'database.png', '14', '1', '0', 'fa fa-database fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('13', 'Admin Web', 'web', '1', 'message-news.png', '15', '4', '0', 'fa fa-cloud fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('14', 'Laporan', 'lapor', '1', 'mail-reply-all.png', '16', '2', '0', 'fa fa-comments fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('15', 'Sekretariat', 'sekretariat', '1', 'document-open-8.png', '5', '2', '0', 'fa fa-file fa-lg');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`) VALUES ('16', 'Inventaris', 'inventaris_tanah', '1', 'inventaris.png', '6', '2', '0', 'fa fa-money');


#
# TABLE STRUCTURE FOR: setting_sms
#

DROP TABLE IF EXISTS setting_sms;

CREATE TABLE `setting_sms` (
  `autoreply_text` varchar(160) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO setting_sms (`autoreply_text`) VALUES ('Terima kasih pesan Anda telah kami terima.');


#
# TABLE STRUCTURE FOR: surat_masuk
#

DROP TABLE IF EXISTS surat_masuk;

CREATE TABLE `surat_masuk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_urut` smallint(5) DEFAULT NULL,
  `tanggal_penerimaan` date NOT NULL,
  `nomor_surat` varchar(20) DEFAULT NULL,
  `kode_surat` varchar(10) DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `pengirim` varchar(100) DEFAULT NULL,
  `isi_singkat` varchar(200) DEFAULT NULL,
  `disposisi_kepada` varchar(50) DEFAULT NULL,
  `isi_disposisi` varchar(200) DEFAULT NULL,
  `berkas_scan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: sys_traffic
#

DROP TABLE IF EXISTS sys_traffic;

CREATE TABLE `sys_traffic` (
  `Tanggal` date NOT NULL,
  `ipAddress` text NOT NULL,
  `Jumlah` int(10) NOT NULL,
  PRIMARY KEY (`Tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-11-15', '::1{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-11-16', '::1{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-11-18', '', '3');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-11-21', '', '3');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-11-26', '::1{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-03', '127.0.0.1{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-04', '', '3');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-05', '', '5');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-06', '127.0.0.1{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-08', '127.0.0.1{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-09', '127.0.0.1{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2014-12-10', '', '3');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-25', '', '2');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-26', '', '4');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-27', '', '2');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-28', '10.0.2.2{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-29', '', '2');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-30', '10.0.2.2{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-05-31', '', '3');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-06-01', '10.0.2.2{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-23', '', '6');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-24', '', '7');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-26', '', '8');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-27', '192.168.1.66{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-28', '', '3');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-29', '', '2');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-30', '', '3');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-08-31', '127.0.0.1{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-02', '', '4');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-03', '', '2');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-04', '10.0.2.2{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-05', '', '2');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-07', '', '2');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-08', '10.0.2.2{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-09', '', '4');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-10', '', '4');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-11', '', '2');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2016-09-14', '', '4');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2017-07-16', '10.0.2.2{}', '1');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2018-05-28', '', '3');
INSERT INTO sys_traffic (`Tanggal`, `ipAddress`, `Jumlah`) VALUES ('2018-05-29', '10.0.2.2{}', '1');


#
# TABLE STRUCTURE FOR: tweb_cacat
#

DROP TABLE IF EXISTS tweb_cacat;

CREATE TABLE `tweb_cacat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO tweb_cacat (`id`, `nama`) VALUES ('1', 'CACAT FISIK');
INSERT INTO tweb_cacat (`id`, `nama`) VALUES ('2', 'CACAT NETRA/BUTA');
INSERT INTO tweb_cacat (`id`, `nama`) VALUES ('3', 'CACAT RUNGU/WICARA');
INSERT INTO tweb_cacat (`id`, `nama`) VALUES ('4', 'CACAT MENTAL/JIWA');
INSERT INTO tweb_cacat (`id`, `nama`) VALUES ('5', 'CACAT FISIK DAN MENTAL');
INSERT INTO tweb_cacat (`id`, `nama`) VALUES ('6', 'CACAT LAINNYA');
INSERT INTO tweb_cacat (`id`, `nama`) VALUES ('7', 'TIDAK CACAT');


#
# TABLE STRUCTURE FOR: tweb_cara_kb
#

DROP TABLE IF EXISTS tweb_cara_kb;

CREATE TABLE `tweb_cara_kb` (
  `id` tinyint(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `sex` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO tweb_cara_kb (`id`, `nama`, `sex`) VALUES ('1', 'Pil', '2');
INSERT INTO tweb_cara_kb (`id`, `nama`, `sex`) VALUES ('2', 'IUD', '2');
INSERT INTO tweb_cara_kb (`id`, `nama`, `sex`) VALUES ('3', 'Suntik', '2');
INSERT INTO tweb_cara_kb (`id`, `nama`, `sex`) VALUES ('4', 'Kondom', '1');
INSERT INTO tweb_cara_kb (`id`, `nama`, `sex`) VALUES ('5', 'Susuk KB', '2');
INSERT INTO tweb_cara_kb (`id`, `nama`, `sex`) VALUES ('6', 'Sterilisasi Wanita', '2');
INSERT INTO tweb_cara_kb (`id`, `nama`, `sex`) VALUES ('7', 'Sterilisasi Pria', '1');
INSERT INTO tweb_cara_kb (`id`, `nama`, `sex`) VALUES ('99', 'Lainnya', '3');


#
# TABLE STRUCTURE FOR: tweb_desa_pamong
#

DROP TABLE IF EXISTS tweb_desa_pamong;

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
  PRIMARY KEY (`pamong_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('14', 'Muhammad Ilham ', '', '', 'Kepala Desa', '1', '2014-04-20', '1', 'CjR9Xl_kades.jpg');
INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('20', 'Mustahiq S.Adm', '197905062010011007', '5201140506790001', 'Sekretaris Desa', '1', '2016-08-23', NULL, '');
INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('21', 'Syafruddin ', '-', '5201140911720004', 'Kaur Pemerintahan ', '1', '2016-08-23', NULL, '');
INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('22', 'Supardi Rustam ', '-', '5201140101710003', 'Kaur Umum ', '1', '2016-08-23', NULL, '');
INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('23', 'Mardiana ', '-', '5201145203810001', 'Kaur Keuangan', '1', '2016-08-23', NULL, 'cNzva0_bendahara.jpg');
INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('24', 'Syafi-i. SE ', '-', '5201140506730002', 'Kaur Pembangunan ', '1', '2016-08-23', NULL, '');
INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('25', 'Mahrup ', '', '', 'Kaur Keamanan dan Ketertiban', '1', '2016-08-23', NULL, '');


#
# TABLE STRUCTURE FOR: tweb_golongan_darah
#

DROP TABLE IF EXISTS tweb_golongan_darah;

CREATE TABLE `tweb_golongan_darah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('1', 'A');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('2', 'B');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('3', 'AB');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('4', 'O');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('5', 'A+');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('6', 'A-');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('7', 'B+');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('8', 'B-');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('9', 'AB+');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('10', 'AB-');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('11', 'O+');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('12', 'O-');
INSERT INTO tweb_golongan_darah (`id`, `nama`) VALUES ('13', 'TIDAK TAHU');


#
# TABLE STRUCTURE FOR: tweb_keluarga
#

DROP TABLE IF EXISTS tweb_keluarga;

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

INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('1', '5201140104126994', '1', '2016-09-14 13:28:03', NULL, NULL, NULL, '4');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('2', '5201140104126995', '5', '2016-09-14 13:28:03', NULL, NULL, NULL, '8');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('3', '5201140104166999', '9', '2016-09-14 13:28:03', NULL, NULL, NULL, '12');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('4', '5201140105136997', '12', '2016-09-14 13:28:03', NULL, NULL, NULL, '16');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('5', '5201140106166996', '16', '2016-09-14 13:28:03', NULL, NULL, NULL, '8');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('6', '5201140106167002', '17', '2016-09-14 13:28:03', NULL, NULL, NULL, '17');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('7', '5201140106167003', '19', '2016-09-14 13:28:03', NULL, NULL, NULL, '16');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('8', '5201140107126996', '21', '2016-09-14 13:28:03', NULL, NULL, NULL, '18');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('9', '5201140108146995', '25', '2016-09-14 13:28:03', NULL, NULL, NULL, '18');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('10', '5201140109126996', '26', '2016-09-14 13:28:03', NULL, NULL, NULL, '19');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('11', '5201140109156994', '30', '2016-09-14 13:28:03', NULL, NULL, NULL, '19');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('12', '5201140110137011', '32', '2016-09-14 13:28:03', NULL, NULL, NULL, '20');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('13', '5201140110137038', '35', '2016-09-14 13:28:03', NULL, NULL, NULL, '18');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('14', '5201140110156997', '37', '2016-09-14 13:28:03', NULL, NULL, NULL, '18');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('15', '5201140111126997', '38', '2016-09-14 13:28:03', NULL, NULL, NULL, '17');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('16', '5201140111126999', '39', '2016-09-14 13:28:03', NULL, NULL, NULL, '21');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('17', '5201140112107003', '42', '2016-09-14 13:28:03', NULL, NULL, NULL, '12');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('18', '5201140112126998', '45', '2016-09-14 13:28:03', NULL, NULL, NULL, '22');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('19', '5201140202167000', '51', '2016-09-14 13:28:03', NULL, NULL, NULL, '23');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('20', '5201140202167002', '52', '2016-09-14 13:28:03', NULL, NULL, NULL, '24');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('21', '5201140203136994', '55', '2016-09-14 13:28:03', NULL, NULL, NULL, '8');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('22', '5201140203136995', '56', '2016-09-14 13:28:03', NULL, NULL, NULL, '16');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('23', '5201140203167003', '59', '2016-09-14 13:28:03', NULL, NULL, NULL, '23');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('24', '5201140204166994', '61', '2016-09-14 13:28:03', NULL, NULL, NULL, '25');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('25', '5201140205156994', '62', '2016-09-14 13:28:03', NULL, NULL, NULL, '26');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('26', '5201140205156995', '65', '2016-09-14 13:28:03', NULL, NULL, NULL, '26');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('27', '5201140205156996', '68', '2016-09-14 13:28:03', NULL, NULL, NULL, '25');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('28', '5201140205156997', '71', '2016-09-14 13:28:03', NULL, NULL, NULL, '25');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('29', '5201140206157000', '74', '2016-09-14 13:28:03', NULL, NULL, NULL, '17');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('30', '5201140206157004', '76', '2016-09-14 13:28:03', NULL, NULL, NULL, '27');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('31', '5201140207156998', '77', '2016-09-14 13:28:03', NULL, NULL, NULL, '28');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('32', '5201140207157000', '80', '2016-09-14 13:28:03', NULL, NULL, NULL, '29');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('33', '5201140209156996', '83', '2016-09-14 13:28:03', NULL, NULL, NULL, '30');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('34', '5201140210137022', '84', '2016-09-14 13:28:03', NULL, NULL, NULL, '29');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('35', '5201140211117001', '88', '2016-09-14 13:28:03', NULL, NULL, NULL, '31');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('36', '5201140211117002', '91', '2016-09-14 13:28:03', NULL, NULL, NULL, '31');
INSERT INTO tweb_keluarga (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES ('37', '5201140211117003', '95', '2016-09-14 13:28:03', NULL, NULL, NULL, '31');


#
# TABLE STRUCTURE FOR: tweb_keluarga_sejahtera
#

DROP TABLE IF EXISTS tweb_keluarga_sejahtera;

CREATE TABLE `tweb_keluarga_sejahtera` (
  `id` int(10) NOT NULL DEFAULT '0',
  `nama` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tweb_keluarga_sejahtera (`id`, `nama`) VALUES ('1', 'Keluarga Pra Sejahtera');
INSERT INTO tweb_keluarga_sejahtera (`id`, `nama`) VALUES ('2', 'Keluarga Sejahtera I');
INSERT INTO tweb_keluarga_sejahtera (`id`, `nama`) VALUES ('3', 'Keluarga Sejahtera II');
INSERT INTO tweb_keluarga_sejahtera (`id`, `nama`) VALUES ('4', 'Keluarga Sejahtera III');
INSERT INTO tweb_keluarga_sejahtera (`id`, `nama`) VALUES ('5', 'Keluarga Sejahtera III Plus');


#
# TABLE STRUCTURE FOR: tweb_penduduk
#

DROP TABLE IF EXISTS tweb_penduduk;

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

INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('1', 'AHLUL', '5201142005716996', '1', '1', '0', '0', '1', 'MANGSIT', '1970-05-20', '1', '3', '18', '26', '2', '1', '', '0', '', '', 'ARFAH', 'RAISAH', '', '13', '4', '1', '', '', '1', '0', '0', '0', '', '', NULL, '', NULL, '0', NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('2', 'AHMAD ALLIF RIZKI', '5201140706966997', '1', '4', '0', '0', '1', 'MANGSIT', '1995-06-07', '1', '1', '18', '1', '1', '1', '', '0', '', '', 'AHLUL', 'RUSDAH', '', '13', '4', '1', '', '', '1', '0', '0', '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('3', 'AHMAD HABIB', '5201140301916995', '1', '4', '0', '0', '1', 'MANGSIT', '1990-01-03', '1', '3', '18', '1', '1', '1', NULL, NULL, '', '', 'AHLUL', 'RUSDAH', '', '13', '4', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('4', 'ADINI SEPTIA LISTA', '5201145003976995', '1', '4', '0', '0', '2', 'MANGSIT', '1996-03-10', '1', '4', '18', '2', '2', '1', NULL, NULL, '', '', 'AHLUL', 'RUSDAH', '', '13', '4', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('5', 'AHYAR', '5201141003666996', '2', '1', '0', '0', '1', 'JAKARTA', '1965-03-10', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'PAIMUN', 'SUPINAH', '', '13', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('6', 'APTA MADA RIZKY ALAMSYAH', '5201141412121724', '2', '4', '0', '0', '1', 'DEPOK', '2002-12-14', '1', '2', '18', '3', '1', '1', NULL, NULL, '', '', 'AHYAR', 'ALIYAH', '', '13', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('7', 'ALIYAH', '5201144609786995', '2', '3', '0', '0', '2', 'BEKASI', '1977-09-06', '1', '5', '18', '2', '2', '1', NULL, NULL, '', '', 'TAGOR SIPAHUTAR', 'AMAHWATI', '', '13', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('8', 'ALPIANI', '5201144301171725', '2', '4', '0', '0', '2', 'BOGOR', '2007-01-03', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'AHYAR', 'ALIYAH', '', '13', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('9', 'ASHARI', '5201140107867064', '3', '1', '0', '0', '1', 'KERANDANGAN', '1985-12-30', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'H. ABDUL KARIM', 'RADIAH', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('10', 'BACHTIAR HADI', '5201142210181724', '3', '4', '0', '0', '1', 'MATARAM', '2008-10-22', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'ASHARI', 'ANGGUN LESTARI PRATAMA', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('11', 'ANGGUN LESTARI PRATAMA', '5201146510916995', '3', '3', '0', '0', '2', 'SENGGIGI', '1990-10-25', '1', '4', '18', '88', '2', '1', NULL, NULL, '', '', 'SADIRAH', 'HJ. ROHANI', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('12', 'DAHRI', '5201143112797117', '4', '1', '0', '0', '1', 'MASBAGIK', '1978-12-31', '1', '3', '18', '88', '2', '1', NULL, NULL, '', '', 'AMAQ SAHMINI', 'INAQ SAHMINI', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('13', 'ERLANGGA DWIKO SAPUTRO', '5201140705156994', '4', '4', '0', '0', '1', 'MENINTING', '2014-05-07', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'DAHRI', 'ASMIATUN', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('14', 'FARIDAH', '5201141107101724', '4', '4', '0', '0', '1', 'MASBAGIK', '2000-07-11', '1', '3', '18', '3', '1', '1', NULL, NULL, '', '', 'DAHRI', 'ASMIATUN', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('15', 'ASMIATUN', '5201147112817188', '4', '3', '0', '0', '2', 'MASBAGIK', '1980-12-31', '1', '4', '18', '2', '2', '1', NULL, NULL, '', '', 'AMAQ MUJAENI', 'INAQ SAHMINI', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('16', 'BAIQ OLIVIA APRILLIANI', '5201145211486994', '5', '1', '0', '0', '2', 'SENGGIGI', '1947-11-12', '1', '1', '18', '1', '4', '1', NULL, NULL, '', '', 'AMAQ SINAREP', 'INAQ SINAREP', '', '13', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('17', 'FAUZI', '5201141210906998', '6', '1', '0', '0', '1', 'KERANDANGAN', '1989-10-12', '1', '5', '18', '3', '1', '1', NULL, NULL, '', '', 'SABLI', 'RAOHUN', '', '13', '17', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('18', 'DELLA MAHARANI NINGSIH', '5201147112947048', '6', '9', '0', '0', '2', 'KERANDANGAN', '1993-12-31', '1', '4', '18', '1', '1', '1', NULL, NULL, '', '', 'SABLI', 'RAOHUN', '', '13', '17', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('19', 'HAERUL FATONI', '5201142911936995', '7', '1', '0', '0', '1', 'SENGGIGI', '1992-11-29', '1', '5', '18', '15', '2', '1', NULL, NULL, '', '', 'ANGKASAH', 'MARJANAH', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('20', 'DENATUL SUARTINI', '3275014601977005', '7', '3', '0', '0', '2', 'JAKARTA', '1996-01-06', '1', '5', '18', '2', '2', '1', NULL, NULL, '', '', 'G. AMIN. P', 'NGATI', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('21', 'HERI IRAWAN', '5201140607636994', '8', '1', '0', '0', '1', 'TELOKE', '1962-07-06', '1', '3', '18', '9', '2', '1', NULL, NULL, '', '', 'AMAK MAJUMI', 'INAK MIDAH', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('22', 'HERMAN', '5201140305936994', '8', '4', '0', '0', '1', 'SENGGIGI', '1992-05-03', '1', '4', '18', '1', '1', '1', NULL, NULL, '', '', 'HERI IRAWAN', 'DEWI SAULINA', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('23', 'DEWI SAULINA', '5201144808686994', '8', '3', '0', '0', '2', 'KEKERAN', '1967-08-08', '1', '1', '18', '2', '2', '1', NULL, NULL, '', '', 'H. ZAENUDIN', 'INAK NAH', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('24', 'ELOK KHALISA SABRINA', '5201144408886994', '8', '4', '0', '0', '2', 'SENGGIGI', '1987-08-04', '1', '4', '18', '88', '1', '1', NULL, NULL, '', '', 'SERIMAN', 'DEWI SAULINA', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('25', 'I KETUT PAHING', '5201142210806997', '9', '1', '0', '0', '1', 'MATARAM', '1979-10-22', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', '-', '-', '', '2', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('26', 'IDA BAGUS MAS BUJANA', '5201143112707040', '10', '1', '0', '0', '1', 'APIT AIK', '1969-12-31', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'SAHMIN', 'MAOSIN', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('27', 'JOKO PATMOTO', '5201141009146994', '10', '4', '0', '0', '1', 'MANGSIT', '2013-09-10', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'IDA BAGUS MAS BUJANA', 'FITRIANI', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('28', 'KOMANG SALUN', '5201143105121724', '10', '4', '0', '0', '1', 'KAYANGAN', '2002-05-31', '1', '2', '18', '3', '1', '1', NULL, NULL, '', '', 'AMILUDIN', 'FITRIANI', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('29', 'FITRIANI', '5201145107836994', '10', '3', '0', '0', '2', 'KAYANGAN', '1982-07-11', '1', '4', '18', '2', '2', '1', NULL, NULL, '', '', 'REMBUK', 'SITIAH', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('30', 'LALU WAWAN DININGRAT', '5201141206886994', '11', '1', '0', '0', '1', 'MANGSIT', '1987-06-12', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'MAHSUN SUBUH', 'SARDIAH', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('31', 'FITRIANI', '5271016801926995', '11', '3', '0', '0', '2', 'MATARAM', '1991-01-28', '1', '5', '18', '15', '2', '1', NULL, NULL, '', '', 'UMAR', 'RUMINSIH', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('32', 'M. FA\'IZ AZAMI', '5201143112897123', '12', '1', '0', '0', '1', 'GEGELANG', '1988-12-31', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'SAREH', 'SUTIMAH', '', '13', '20', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('33', 'HILMIATI', '5201146402906994', '12', '3', '0', '0', '2', 'LOCO', '1989-02-24', '1', '4', '18', '88', '2', '1', NULL, NULL, '', '', 'H. MANSYUR', 'HJ. SA\'ADAH', '', '13', '20', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('34', 'HJ. PARIDAH', '5201144912146994', '12', '4', '0', '0', '2', 'MENINTING', '2013-12-09', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'M. FA\'IZ AZAMI', 'HILMIATI', '', '13', '20', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('35', 'HJ. SAMIRAH', '5201147112767266', '13', '1', '0', '0', '2', 'SENGGIGI', '1975-12-31', '1', '3', '18', '15', '3', '1', NULL, NULL, '', '', 'DAMSYAH', 'MARWIYAH', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('36', 'HUR MINAH', '5201144504131726', '13', '4', '0', '0', '2', 'SENGGIGI', '2003-04-05', '1', '3', '18', '3', '1', '1', NULL, NULL, '', '', 'MARSINI', 'KHODIJAH', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('37', 'HUSNAH', '5201145905936994', '14', '1', '0', '0', '2', 'LOTIM', '1992-05-19', '1', '4', '18', '88', '1', '1', NULL, NULL, '', '', '-', '-', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('38', 'IDA AYU OKA SUKERTI', '5201147112587053', '15', '1', '0', '0', '2', 'KERANDANGAN', '1957-12-31', '1', '3', '18', '88', '4', '1', NULL, NULL, '', '', 'ANGGRAH', 'HABIBAH', '', '13', '17', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('39', 'M. JAYADI', '5201143112837098', '16', '1', '0', '0', '1', 'SENGGIGI', '1982-12-31', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'IKHSAN', 'SAIDAH', '', '13', '21', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('40', 'JARIYAH', '5201145406916994', '16', '3', '0', '0', '2', 'SENGGIGI', '1990-06-14', '1', '4', '18', '2', '2', '1', NULL, NULL, '', '', 'SEGEP', 'HURNIWATI', '', '13', '21', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('41', 'LIHAM SATUN', '5201147112116995', '16', '4', '0', '0', '2', 'MATARAM', '2010-12-31', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'M. JAYADI', 'JARIYAH', '', '13', '21', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('42', 'M. NUR SAHID', '5201140507916996', '17', '1', '0', '0', '1', 'KERANDANGAN', '1990-07-05', '1', '4', '18', '88', '2', '1', NULL, NULL, '', '', '-', '-', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('43', 'MADE ASTAWE', '5201142503181724', '17', '4', '0', '0', '1', 'KERANDANGAN', '2008-03-25', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'M. NUR SAHID', 'MAISAH', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('44', 'MAISAH', '5201144605936994', '17', '3', '0', '0', '2', 'KERANDANGAN', '1992-05-06', '4', '1', '18', '88', '2', '1', NULL, NULL, '', '', '-', '-', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('45', 'MARSUNIN YOGA PRATAMA', '5201143112677056', '18', '1', '0', '0', '1', 'PEJARAKAN', '1966-12-31', '1', '3', '18', '9', '2', '1', NULL, NULL, '', '', 'MISRAH', 'INAQ MISDAH', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('46', 'MARZUKI', '5201141003966996', '18', '4', '0', '0', '1', 'LOCO', '1995-03-10', '1', '5', '18', '3', '1', '1', NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('47', 'MUHAMAD HAMDI', '5201141706986996', '18', '4', '0', '0', '1', 'LOCO', '1997-06-17', '1', '4', '18', '3', '1', '1', NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('48', 'MARLIA SAJIDA', '5201147112707088', '18', '3', '0', '0', '2', 'PEJARAKAN', '1969-12-31', '1', '3', '18', '2', '2', '1', NULL, NULL, '', '', 'H. ZAINUDIN', 'INAQ NAH', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('49', 'MIRA FANDA', '5201146704906995', '18', '4', '0', '0', '2', 'LOCO', '1989-04-27', '1', '5', '18', '88', '4', '1', NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('50', 'MUNAAH', '5201146304171724', '18', '4', '0', '0', '2', 'LOCO', '2007-04-23', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('51', 'MUHAMAD KABIR', '5201140107917031', '19', '1', '0', '0', '1', 'SENGGIGI', '1985-12-31', '1', '3', '18', '88', '2', '1', NULL, NULL, '', '', 'MUNIAH', 'SALIKIN', '', '13', '23', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('52', 'MUHAMAD SUHAD', '5201141704876995', '20', '1', '0', '0', '1', 'SENGGIGI', '1982-12-10', '1', '5', '18', '15', '2', '1', NULL, NULL, '', '', 'MUNIAH', 'HAJRIAH', '', '13', '24', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('53', 'MUHAMMAD HAIKAL FIRMANSYAH', '5201140308151724', '20', '4', '0', '0', '1', 'LOCO', '2005-08-03', '1', '2', '18', '1', '1', '1', NULL, NULL, '', '', 'MUHAMAD SUHAD', 'KHADIJAH', '', '13', '24', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('54', 'MURNIAH', '5201145904846994', '20', '3', '0', '0', '2', 'SETANGI', '1991-03-04', '1', '4', '18', '2', '2', '1', NULL, NULL, '', '', 'SAHABUDIN', 'SAKMAH', '', '13', '24', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('55', 'MURNIATI SAGITA', '5201144112726996', '21', '1', '0', '0', '2', 'YOGYAKARTA', '1971-12-01', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'UMAR SANTOSA', 'MIRANTI', '', '1', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('56', 'MUHAMMAD RIFAI', '5201143105926995', '22', '1', '0', '0', '1', 'LOCO', '1991-05-31', '4', '4', '18', '88', '2', '1', NULL, NULL, '', '', 'I WAYAN MERTI', 'NI NYOMAN RENI', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('57', 'NADIA ROSDIANA', '5201144305936996', '22', '3', '0', '0', '2', 'MATARAM', '1992-05-03', '4', '4', '18', '2', '2', '1', NULL, NULL, '', '', 'I WAYAN PARTA', 'NI NENGAH SUDINI', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('58', 'NI KOMANG PONIASIH', '5201146206126994', '22', '4', '0', '0', '2', 'MATARAM', '2011-06-22', '4', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'MURNIATI SAGITA', 'NADIA ROSDIANA', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('59', 'MUHAMMAD WIRDA MAULANA IBRAHIM', '5201143112417056', '23', '1', '0', '0', '1', 'SENGGIGI', '1940-12-31', '1', '1', '18', '9', '2', '1', NULL, NULL, '', '', 'AMAQ SUN -ALM-', 'INAQ SUN -ALM-', '', '13', '23', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('60', 'NI LUH NITA SARI', '5201147112466997', '23', '3', '0', '0', '2', 'SENTELUK', '1945-12-31', '1', '1', '18', '2', '2', '1', NULL, NULL, '', '', 'AMAQ IRAH', 'INAQ IRAH', '', '13', '23', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('61', 'NI NENGAH AYU KARSINI', '5201145505946996', '24', '1', '0', '0', '2', 'SENGGIGI', '1993-05-15', '1', '2', '18', '15', '1', '1', NULL, NULL, '', '', 'H HAMDANI', 'SANERIAH', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('62', 'MUKSAN', '5201143112957094', '25', '1', '0', '0', '1', 'MANGSIT', '1994-12-31', '1', '4', '18', '88', '2', '1', NULL, NULL, '', '', 'MISDAH', 'RABIAH', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('63', 'NURHAYATI', '5201145509146994', '25', '4', '0', '0', '2', 'MENINTING', '2013-09-15', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'MUKSAN', 'NUR\'AINI', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('64', 'MURSIDIN', '5201142204966994', '26', '4', '0', '0', '1', 'MANGSIT', '1995-04-22', '1', '3', '18', '11', '1', '1', NULL, NULL, '', '', 'RUSNAH (ALM)', 'NURHIDAYAH', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('65', 'NURHIDAYAH', '5201144209766995', '26', '1', '0', '0', '2', 'MANGSIT', '1975-09-02', '1', '3', '18', '2', '4', '1', NULL, NULL, '', '', 'ISMAIL', 'JUMINAH', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('66', 'NURJANAH', '5201145005101724', '26', '4', '0', '0', '2', 'MONTONG', '2000-05-10', '1', '4', '18', '3', '1', '1', NULL, NULL, '', '', 'RUSNAH (ALM)', 'NURHIDAYAH', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('67', 'NURUL AINUN', '5201144108121724', '26', '4', '0', '0', '2', 'MANGSIT', '2002-08-01', '1', '2', '18', '3', '1', '1', NULL, NULL, '', '', 'RUSNAH', 'NURHIDAYAH', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('68', 'MUSAHAB', '5201141607936996', '27', '1', '0', '0', '1', 'LOTENG', '1992-07-16', '1', '6', '18', '88', '2', '1', NULL, NULL, '', '', 'LALU ROSIDI', 'BQ. ALISA', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('69', 'NURUL FAIZAH', '5201145003936994', '27', '3', '0', '0', '2', 'SENGGIGI', '1992-03-10', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'SAHUR', 'SARE\'AH', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('70', 'NURUL HIDAYATI', '5201147004136996', '27', '4', '0', '0', '2', 'MATARAM', '2012-04-30', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'MUSAHAB', 'NURUL FAIZAH', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('71', 'NAPIAH', '5201141303906995', '28', '1', '0', '0', '1', 'SENGGIGI', '1989-03-13', '1', '4', '18', '11', '2', '1', NULL, NULL, '', '', 'MUNIAH', 'HAJARIAH', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('72', 'RACHEL YULIANTI', '5201146507966995', '28', '3', '0', '0', '2', 'MELASE', '1995-07-25', '1', '4', '18', '2', '2', '1', NULL, NULL, '', '', 'LUKMAN', 'MUSNAH', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('73', 'RAISHA MAULIDYA', '5201144701156995', '28', '4', '0', '0', '2', 'MENINTING', '2014-01-07', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'NAPIAH', 'RACHEL YULIANTI', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('74', 'PATANUL HUSNUL', '5201143112667000', '29', '1', '0', '0', '1', 'JAWA TIMUR', '1965-12-31', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'AHMAD', 'ASIH', '', '13', '17', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('75', 'RATNAWATY', '5201145512796995', '29', '3', '0', '0', '2', 'KERANDANGAN', '1978-12-15', '1', '5', '18', '84', '2', '1', NULL, NULL, '', '', 'JUM', 'REMAH', '', '13', '17', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('76', 'RABITAH', '5201140312896994', '30', '1', '0', '0', '1', 'KERANDANGAN', '1988-12-03', '4', '4', '18', '88', '1', '1', NULL, NULL, '', '', '-', '-', '', '13', '27', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('77', 'ROMI FAISAL', '5201141506856997', '31', '1', '0', '0', '1', 'MANGSIT', '1984-06-15', '1', '3', '18', '15', '2', '1', NULL, NULL, '', '', 'MUNTAHAR', 'MAKNAH', '', '13', '28', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('78', 'RAUDATUL ILMI', '5201145808816994', '31', '3', '0', '0', '2', 'IRENG DAYE', '1980-08-18', '1', '4', '18', '2', '2', '1', NULL, NULL, '', '', 'MUDAHIR', 'RUMISAH', '', '13', '28', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('79', 'ROHANI', '5201144306116994', '31', '4', '0', '0', '2', 'MANGSIT', '2010-06-03', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'ROMI FAISAL', 'RAUDATUL ILMI', '', '13', '28', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('80', 'RUKIAH', '5201145909946994', '32', '1', '0', '0', '2', 'SERANG', '1993-09-19', '1', '4', '18', '88', '3', '1', NULL, NULL, '', '', '-', '-', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('81', 'RUMALI', '5201144507886994', '32', '9', '0', '0', '2', 'JAKARTA', '1987-07-05', '1', '4', '18', '88', '1', '1', NULL, NULL, '', '', '-', '-', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('82', 'RONI', '5201140301836997', '33', '4', '0', '0', '1', 'DENPASAR', '1982-01-03', '4', '5', '18', '15', '1', '1', NULL, NULL, '', '', 'IDA BAGUS PUTU WIADNYA', 'RUSMAYANTI', '', '13', '30', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('83', 'RUSMAYANTI', '5201145003546994', '33', '1', '0', '0', '2', 'DENPASAR', '1953-03-10', '4', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'IDA BAGUS MAS', 'IDA AYU RAKA', '', '13', '30', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('84', 'RUSNI', '5201143112707180', '34', '1', '0', '0', '1', 'KEKERAN', '1969-12-31', '1', '3', '18', '9', '2', '1', NULL, NULL, '', '', '-', '-', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('85', 'SAPIAH', '5201147011726994', '34', '3', '0', '0', '2', 'KEKERAN', '1971-11-30', '1', '3', '18', '2', '2', '1', NULL, NULL, '', '', '-', '-', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('86', 'SAPINAH', '5201145701966994', '34', '4', '0', '0', '2', 'SENGGIGI', '1995-01-17', '1', '5', '18', '3', '1', '1', NULL, NULL, '', '', 'RUSNI', 'SAPIAH', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('87', 'SARRA LANGELAND', '5201145111946996', '34', '4', '0', '0', '2', 'SENGGIGI', '1993-11-11', '1', '5', '18', '3', '1', '1', NULL, NULL, '', '', 'RUSNI', 'SAPIAH', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('88', 'SAHRONI', '5201143112617096', '35', '1', '0', '0', '1', 'MEDAS', '1960-12-31', '1', '4', '18', '88', '2', '1', NULL, NULL, '', '', 'SADIYAH', 'INAQ SADIAH', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('89', 'SERIMAN', '5201141012846995', '35', '4', '0', '0', '1', 'SENGGIGI', '1983-12-10', '1', '5', '18', '15', '1', '1', NULL, NULL, '', '', 'SAHRONI', 'NURLAELA', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('90', 'SUNYOTOH', '5201143112817139', '35', '4', '0', '0', '1', 'MEDAS', '1980-12-31', '1', '5', '18', '15', '1', '1', NULL, NULL, '', '', 'SAHRONI', 'NURLAELA', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('91', 'SYARIFUL KALAM', '5201141707776994', '36', '1', '0', '0', '1', 'SENGGIGI', '1976-07-17', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'H. ABDURAHMAN', 'NAFISAH', '', '1', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('92', 'SITI AISYAH', '5201146210776994', '36', '3', '0', '0', '2', 'SUKARAJA', '1976-10-22', '1', '4', '18', '2', '2', '1', NULL, NULL, '', '', 'AMINALLOH', 'RAEHAN', '', '2', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('93', 'SITI PAOZIAH', '5201146312161724', '36', '4', '0', '0', '2', 'SENGGIGI', '2006-12-23', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'SYARIFUL KALAM', 'SITI AISYAH', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('94', 'SUKMA UTAMI', '5201144607996998', '36', '4', '0', '0', '2', 'SENGGIGI', '1998-07-06', '1', '4', '18', '3', '1', '1', NULL, NULL, '', '', 'SYARIFUL KALAM', 'SITI AISYAH', '', '5', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('95', 'WAHID ALIAS H. MAHSUN', '5201141212816996', '37', '1', '0', '0', '1', 'SENGGIGI', '1980-12-12', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'H. ABDURRAHMAN', 'NAFISAH', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('96', 'WAYAN EKA PRAWATA', '5201142003136994', '37', '4', '0', '0', '1', 'GUNUNG SARI', '2012-03-20', '1', '1', '18', '1', '1', '1', NULL, NULL, '', '', 'WAHID ALIAS H. MAHSUN', 'ULFA WIDIAWATI', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`) VALUES ('97', 'ULFA WIDIAWATI', '5201145203896994', '37', '3', '0', '0', '2', 'JOHAR PELITA', '1988-03-12', '1', '5', '18', '88', '2', '1', NULL, NULL, '', '', 'ZAMHARIN', 'SITIYAH', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, '0', '0', '', '0', '0', '0', '0', '', '');


#
# TABLE STRUCTURE FOR: tweb_penduduk_agama
#

DROP TABLE IF EXISTS tweb_penduduk_agama;

CREATE TABLE `tweb_penduduk_agama` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO tweb_penduduk_agama (`id`, `nama`) VALUES ('1', 'ISLAM');
INSERT INTO tweb_penduduk_agama (`id`, `nama`) VALUES ('2', 'KRISTEN');
INSERT INTO tweb_penduduk_agama (`id`, `nama`) VALUES ('3', 'KATHOLIK');
INSERT INTO tweb_penduduk_agama (`id`, `nama`) VALUES ('4', 'HINDU');
INSERT INTO tweb_penduduk_agama (`id`, `nama`) VALUES ('5', 'BUDHA');
INSERT INTO tweb_penduduk_agama (`id`, `nama`) VALUES ('6', 'KHONGHUCU');
INSERT INTO tweb_penduduk_agama (`id`, `nama`) VALUES ('7', 'Kepercayaan Terhadap Tuhan YME / Lainnya');


#
# TABLE STRUCTURE FOR: tweb_penduduk_hubungan
#

DROP TABLE IF EXISTS tweb_penduduk_hubungan;

CREATE TABLE `tweb_penduduk_hubungan` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('1', 'KEPALA KELUARGA');
INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('2', 'SUAMI');
INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('3', 'ISTRI');
INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('4', 'ANAK');
INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('5', 'MENANTU');
INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('6', 'CUCU');
INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('7', 'ORANGTUA');
INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('8', 'MERTUA');
INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('9', 'FAMILI LAIN');
INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('10', 'PEMBANTU');
INSERT INTO tweb_penduduk_hubungan (`id`, `nama`) VALUES ('11', 'LAINNYA');


#
# TABLE STRUCTURE FOR: tweb_penduduk_kawin
#

DROP TABLE IF EXISTS tweb_penduduk_kawin;

CREATE TABLE `tweb_penduduk_kawin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO tweb_penduduk_kawin (`id`, `nama`) VALUES ('1', 'BELUM KAWIN');
INSERT INTO tweb_penduduk_kawin (`id`, `nama`) VALUES ('2', 'KAWIN');
INSERT INTO tweb_penduduk_kawin (`id`, `nama`) VALUES ('3', 'CERAI HIDUP');
INSERT INTO tweb_penduduk_kawin (`id`, `nama`) VALUES ('4', 'CERAI MATI');


#
# TABLE STRUCTURE FOR: tweb_penduduk_mandiri
#

DROP TABLE IF EXISTS tweb_penduduk_mandiri;

CREATE TABLE `tweb_penduduk_mandiri` (
  `nik` decimal(16,0) NOT NULL,
  `pin` char(32) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `tanggal_buat` datetime DEFAULT NULL,
  `id_pend` int(9) NOT NULL,
  PRIMARY KEY (`id_pend`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tweb_penduduk_mandiri (`nik`, `pin`, `last_login`, `tanggal_buat`, `id_pend`) VALUES ('5201140706966997', '3645e735f033e8482be0c7993fcba946', '2016-09-14 12:53:47', '2016-09-14 06:06:32', '2');
INSERT INTO tweb_penduduk_mandiri (`nik`, `pin`, `last_login`, `tanggal_buat`, `id_pend`) VALUES ('3275014601977005', '3645e735f033e8482be0c7993fcba946', '2016-09-14 12:51:53', '2016-09-14 10:10:47', '20');


#
# TABLE STRUCTURE FOR: tweb_penduduk_map
#

DROP TABLE IF EXISTS tweb_penduduk_map;

CREATE TABLE `tweb_penduduk_map` (
  `id` int(11) NOT NULL,
  `lat` varchar(24) NOT NULL,
  `lng` varchar(24) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO tweb_penduduk_map (`id`, `lat`, `lng`) VALUES ('7', '-8.495339739996284', '116.05516478419307');
INSERT INTO tweb_penduduk_map (`id`, `lat`, `lng`) VALUES ('3', '-8.496679059709217', '116.05342939496042');


#
# TABLE STRUCTURE FOR: tweb_penduduk_pekerjaan
#

DROP TABLE IF EXISTS tweb_penduduk_pekerjaan;

CREATE TABLE `tweb_penduduk_pekerjaan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('1', 'BELUM/TIDAK BEKERJA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('2', 'MENGURUS RUMAH TANGGA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('3', 'PELAJAR/MAHASISWA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('4', 'PENSIUNAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('5', 'PEGAWAI NEGERI SIPIL (PNS)');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('6', 'TENTARA NASIONAL INDONESIA (TNI)');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('7', 'KEPOLISIAN RI (POLRI)');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('8', 'PERDAGANGAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('9', 'PETANI/PERKEBUNAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('10', 'PETERNAK');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('11', 'NELAYAN/PERIKANAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('12', 'INDUSTRI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('13', 'KONSTRUKSI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('14', 'TRANSPORTASI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('15', 'KARYAWAN SWASTA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('16', 'KARYAWAN BUMN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('17', 'KARYAWAN BUMD');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('18', 'KARYAWAN HONORER');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('19', 'BURUH HARIAN LEPAS');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('20', 'BURUH TANI/PERKEBUNAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('21', 'BURUH NELAYAN/PERIKANAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('22', 'BURUH PETERNAKAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('23', 'PEMBANTU RUMAH TANGGA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('24', 'TUKANG CUKUR');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('25', 'TUKANG LISTRIK');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('26', 'TUKANG BATU');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('27', 'TUKANG KAYU');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('28', 'TUKANG SOL SEPATU');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('29', 'TUKANG LAS/PANDAI BESI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('30', 'TUKANG JAHIT');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('31', 'TUKANG GIGI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('32', 'PENATA RIAS');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('33', 'PENATA BUSANA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('34', 'PENATA RAMBUT');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('35', 'MEKANIK');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('36', 'SENIMAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('37', 'TABIB');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('38', 'PARAJI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('39', 'PERANCANG BUSANA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('40', 'PENTERJEMAH');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('41', 'IMAM MASJID');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('42', 'PENDETA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('43', 'PASTOR');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('44', 'WARTAWAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('45', 'USTADZ/MUBALIGH');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('46', 'JURU MASAK');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('47', 'PROMOTOR ACARA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('48', 'ANGGOTA DPR-RI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('49', 'ANGGOTA DPD');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('50', 'ANGGOTA BPK');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('51', 'PRESIDEN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('52', 'WAKIL PRESIDEN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('53', 'ANGGOTA MAHKAMAH KONSTITUSI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('54', 'ANGGOTA KABINET KEMENTERIAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('55', 'DUTA BESAR');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('56', 'GUBERNUR');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('57', 'WAKIL GUBERNUR');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('58', 'BUPATI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('59', 'WAKIL BUPATI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('60', 'WALIKOTA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('61', 'WAKIL WALIKOTA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('62', 'ANGGOTA DPRD PROVINSI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('63', 'ANGGOTA DPRD KABUPATEN/KOTA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('64', 'DOSEN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('65', 'GURU');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('66', 'PILOT');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('67', 'PENGACARA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('68', 'NOTARIS');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('69', 'ARSITEK');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('70', 'AKUNTAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('71', 'KONSULTAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('72', 'DOKTER');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('73', 'BIDAN');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('74', 'PERAWAT');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('75', 'APOTEKER');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('76', 'PSIKIATER/PSIKOLOG');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('77', 'PENYIAR TELEVISI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('78', 'PENYIAR RADIO');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('79', 'PELAUT');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('80', 'PENELITI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('81', 'SOPIR');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('82', 'PIALANG');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('83', 'PARANORMAL');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('84', 'PEDAGANG');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('85', 'PERANGKAT DESA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('86', 'KEPALA DESA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('87', 'BIARAWATI');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('88', 'WIRASWASTA');
INSERT INTO tweb_penduduk_pekerjaan (`id`, `nama`) VALUES ('89', 'LAINNYA');


#
# TABLE STRUCTURE FOR: tweb_penduduk_pendidikan
#

DROP TABLE IF EXISTS tweb_penduduk_pendidikan;

CREATE TABLE `tweb_penduduk_pendidikan` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('1', 'BELUM MASUK TK/KELOMPOK BERMAIN');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('2', 'SEDANG TK/KELOMPOK BERMAIN');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('3', 'TIDAK PERNAH SEKOLAH');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('4', 'SEDANG SD/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('5', 'TIDAK TAMAT SD/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('6', 'SEDANG SLTP/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('7', 'SEDANG SLTA/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('8', 'SEDANG  D-1/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('9', 'SEDANG D-2/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('10', 'SEDANG D-3/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('11', 'SEDANG  S-1/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('12', 'SEDANG S-2/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('13', 'SEDANG S-3/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('14', 'SEDANG SLB A/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('15', 'SEDANG SLB B/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('16', 'SEDANG SLB C/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('17', 'TIDAK DAPAT MEMBACA DAN MENULIS HURUF LATIN/ARAB');
INSERT INTO tweb_penduduk_pendidikan (`id`, `nama`) VALUES ('18', 'TIDAK SEDANG SEKOLAH');


#
# TABLE STRUCTURE FOR: tweb_penduduk_pendidikan_kk
#

DROP TABLE IF EXISTS tweb_penduduk_pendidikan_kk;

CREATE TABLE `tweb_penduduk_pendidikan_kk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO tweb_penduduk_pendidikan_kk (`id`, `nama`) VALUES ('1', 'TIDAK / BELUM SEKOLAH');
INSERT INTO tweb_penduduk_pendidikan_kk (`id`, `nama`) VALUES ('2', 'BELUM TAMAT SD/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan_kk (`id`, `nama`) VALUES ('3', 'TAMAT SD / SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan_kk (`id`, `nama`) VALUES ('4', 'SLTP/SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan_kk (`id`, `nama`) VALUES ('5', 'SLTA / SEDERAJAT');
INSERT INTO tweb_penduduk_pendidikan_kk (`id`, `nama`) VALUES ('6', 'DIPLOMA I / II');
INSERT INTO tweb_penduduk_pendidikan_kk (`id`, `nama`) VALUES ('7', 'AKADEMI/ DIPLOMA III/S. MUDA');
INSERT INTO tweb_penduduk_pendidikan_kk (`id`, `nama`) VALUES ('8', 'DIPLOMA IV/ STRATA I');
INSERT INTO tweb_penduduk_pendidikan_kk (`id`, `nama`) VALUES ('9', 'STRATA II');
INSERT INTO tweb_penduduk_pendidikan_kk (`id`, `nama`) VALUES ('10', 'STRATA III');


#
# TABLE STRUCTURE FOR: tweb_penduduk_sex
#

DROP TABLE IF EXISTS tweb_penduduk_sex;

CREATE TABLE `tweb_penduduk_sex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO tweb_penduduk_sex (`id`, `nama`) VALUES ('1', 'LAKI-LAKI');
INSERT INTO tweb_penduduk_sex (`id`, `nama`) VALUES ('2', 'PEREMPUAN');


#
# TABLE STRUCTURE FOR: tweb_penduduk_status
#

DROP TABLE IF EXISTS tweb_penduduk_status;

CREATE TABLE `tweb_penduduk_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO tweb_penduduk_status (`id`, `nama`) VALUES ('1', 'TETAP');
INSERT INTO tweb_penduduk_status (`id`, `nama`) VALUES ('2', 'TIDAK AKTIF');
INSERT INTO tweb_penduduk_status (`id`, `nama`) VALUES ('3', 'PENDATANG');


#
# TABLE STRUCTURE FOR: tweb_penduduk_umur
#

DROP TABLE IF EXISTS tweb_penduduk_umur;

CREATE TABLE `tweb_penduduk_umur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(25) DEFAULT NULL,
  `dari` int(11) DEFAULT NULL,
  `sampai` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('1', 'BALITA', '0', '5', '0');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('2', 'ANAK-ANAK', '6', '17', '0');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('3', 'DEWASA', '18', '30', '0');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('4', 'TUA', '31', '120', '0');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('6', 'Di bawah 1 Tahun', '0', '1', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('9', '2 s/d 4 Tahun', '2', '4', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('12', '5 s/d 9 Tahun', '5', '9', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('13', '10 s/d 14 Tahun', '10', '14', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('14', '15 s/d 19 Tahun', '15', '19', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('15', '20 s/d 24 Tahun', '20', '24', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('16', '25 s/d 29 Tahun', '25', '29', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('17', '30 s/d 34 Tahun', '30', '34', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('18', '35 s/d 39 Tahun ', '35', '39', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('19', '40 s/d 44 Tahun', '40', '44', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('20', '45 s/d 49 Tahun', '45', '49', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('21', '50 s/d 54 Tahun', '50', '54', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('22', '55 s/d 59 Tahun', '55', '59', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('23', '60 s/d 64 Tahun', '60', '64', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('24', '65 s/d 69 Tahun', '65', '69', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('25', '70 s/d 74 Tahun', '70', '74', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('26', 'Di atas 75 Tahun', '75', '99999', '1');


#
# TABLE STRUCTURE FOR: tweb_penduduk_warganegara
#

DROP TABLE IF EXISTS tweb_penduduk_warganegara;

CREATE TABLE `tweb_penduduk_warganegara` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO tweb_penduduk_warganegara (`id`, `nama`) VALUES ('1', 'WNI');
INSERT INTO tweb_penduduk_warganegara (`id`, `nama`) VALUES ('2', 'WNA');
INSERT INTO tweb_penduduk_warganegara (`id`, `nama`) VALUES ('3', 'DUA KEWARGANEGARAAN');


#
# TABLE STRUCTURE FOR: tweb_rtm
#

DROP TABLE IF EXISTS tweb_rtm;

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

DROP TABLE IF EXISTS tweb_rtm_hubungan;

CREATE TABLE `tweb_rtm_hubungan` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO tweb_rtm_hubungan (`id`, `nama`) VALUES ('1', 'Kepala Rumah Tangga');
INSERT INTO tweb_rtm_hubungan (`id`, `nama`) VALUES ('2', 'Anggota');


#
# TABLE STRUCTURE FOR: tweb_sakit_menahun
#

DROP TABLE IF EXISTS tweb_sakit_menahun;

CREATE TABLE `tweb_sakit_menahun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('1', 'JANTUNG');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('2', 'LEVER');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('3', 'PARU-PARU');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('4', 'KANKER');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('5', 'STROKE');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('6', 'DIABETES MELITUS');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('7', 'GINJAL');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('8', 'MALARIA');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('9', 'LEPRA/KUSTA');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('10', 'HIV/AIDS');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('11', 'GILA/STRESS');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('12', 'TBC');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('13', 'ASTHMA');
INSERT INTO tweb_sakit_menahun (`id`, `nama`) VALUES ('14', 'TIDAK ADA/TIDAK SAKIT');


#
# TABLE STRUCTURE FOR: tweb_status_dasar
#

DROP TABLE IF EXISTS tweb_status_dasar;

CREATE TABLE `tweb_status_dasar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO tweb_status_dasar (`id`, `nama`) VALUES ('1', 'HIDUP');
INSERT INTO tweb_status_dasar (`id`, `nama`) VALUES ('2', 'MATI');
INSERT INTO tweb_status_dasar (`id`, `nama`) VALUES ('3', 'PINDAH');
INSERT INTO tweb_status_dasar (`id`, `nama`) VALUES ('4', 'HILANG');


#
# TABLE STRUCTURE FOR: tweb_status_ktp
#

DROP TABLE IF EXISTS tweb_status_ktp;

CREATE TABLE `tweb_status_ktp` (
  `id` tinyint(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `ktp_el` tinyint(4) NOT NULL,
  `status_rekam` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO tweb_status_ktp (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES ('1', 'BELUM REKAM', '1', '2');
INSERT INTO tweb_status_ktp (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES ('2', 'SUDAH REKAM', '2', '3');
INSERT INTO tweb_status_ktp (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES ('3', 'CARD PRINTED', '2', '4');
INSERT INTO tweb_status_ktp (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES ('4', 'PRINT READY RECORD', '2', '5');
INSERT INTO tweb_status_ktp (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES ('5', 'CARD SHIPPED', '2', '6');
INSERT INTO tweb_status_ktp (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES ('6', 'SENT FOR CARD PRINTING', '2', '7');
INSERT INTO tweb_status_ktp (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES ('7', 'CARD ISSUED', '2', '8');


#
# TABLE STRUCTURE FOR: tweb_surat_atribut
#

DROP TABLE IF EXISTS tweb_surat_atribut;

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

DROP TABLE IF EXISTS tweb_surat_format;

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
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;

INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('1', 'Keterangan Pengantar', 'surat_ket_pengantar', 'S-01', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('2', 'Keterangan Penduduk', 'surat_ket_penduduk', 'S-02', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('3', 'Biodata Penduduk', 'surat_bio_penduduk', 'S-03', 'f-1.01.php', '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('5', 'Keterangan Pindah Penduduk', 'surat_ket_pindah_penduduk', 'S-04', 'f-1.08.php,f-1.25.php', '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('6', 'Keterangan Jual Beli', 'surat_ket_jual_beli', 'S-05', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('8', 'Pengantar Surat Keterangan Catatan Kepolisian', 'surat_ket_catatan_kriminal', 'S-07', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('9', 'Keterangan KTP dalam Proses', 'surat_ket_ktp_dalam_proses', 'S-08', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('10', 'Keterangan Beda Identitas', 'surat_ket_beda_nama', 'S-09', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('11', 'Keterangan Bepergian / Jalan', 'surat_jalan', 'S-10', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('12', 'Keterangan Kurang Mampu', 'surat_ket_kurang_mampu', 'S-11', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('13', 'Pengantar Izin Keramaian', 'surat_izin_keramaian', 'S-12', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('14', 'Pengantar Laporan Kehilangan', 'surat_ket_kehilangan', 'S-13', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('15', 'Keterangan Usaha', 'surat_ket_usaha', 'S-14', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('16', 'Keterangan JAMKESOS', 'surat_ket_jamkesos', 'S-15', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('17', 'Keterangan Domisili Usaha', 'surat_ket_domisili_usaha', 'S-16', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('18', 'Keterangan Kelahiran', 'surat_ket_kelahiran', 'S-17', 'f-2.01.php', '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('20', 'Permohonan Akta Lahir', 'surat_permohonan_akta', 'S-18', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('21', 'Pernyataan Belum Memiliki Akta Lahir', 'surat_pernyataan_akta', 'S-19', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('22', 'Permohonan Duplikat Kelahiran', 'surat_permohonan_duplikat_kelahiran', 'S-20', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('24', 'Keterangan Kematian', 'surat_ket_kematian', 'S-21', 'f-2.29.php', '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('25', 'Keterangan Lahir Mati', 'surat_ket_lahir_mati', 'S-22', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('26', 'Keterangan Untuk Nikah (N-1 s/d N-7)', 'surat_ket_nikah', 'S-23', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('33', 'Keterangan Pergi Kawin', 'surat_ket_pergi_kawin', 'S-30', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('35', 'Keterangan Wali Hakim', 'surat_ket_wali_hakim', 'S-32', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('36', 'Permohonan Duplikat Surat Nikah', 'surat_permohonan_duplikat_surat_nikah', 'S-33', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('37', 'Permohonan Cerai', 'surat_permohonan_cerai', 'S-34', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('38', 'Keterangan Pengantar Rujuk/Cerai', 'surat_ket_rujuk_cerai', 'S-35', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('45', 'Permohonan Kartu Keluarga', 'surat_permohonan_kartu_keluarga', 'S-36', 'f-1.15.php,f-1.01.php', '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('51', 'Domisili Usaha Non-Warga', 'surat_domisili_usaha_non_warga', 'S-37', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('76', 'Keterangan Beda Identitas KIS', 'surat_ket_beda_identitas_kis', 'S-38', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('85', 'Keterangan Izin Orang Tua/Suami/Istri', 'surat_izin_orangtua_suami_istri', 'S-39', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('86', 'Pernyataan Penguasaan Fisik Bidang Tanah (SPORADIK)', 'surat_sporadik', 'S-40', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('89', 'Permohonan Perubahan Kartu Keluarga', 'surat_permohonan_perubahan_kartu_keluarga', 'S-41', 'f-1.16.php,f-1.01.php', '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('90', 'Izin Mengambil Kayu', 'surat_izin_mengambil_kayu', '', NULL, '0', '0', '2');


#
# TABLE STRUCTURE FOR: tweb_wil_clusterdesa
#

DROP TABLE IF EXISTS tweb_wil_clusterdesa;

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

INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('1', '0', '0', 'MANGSIT', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('2', '0', '-', 'MANGSIT', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('3', '-', '-', 'MANGSIT', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('4', '004', '-', 'MANGSIT', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('5', '0', '0', 'SENGGIGI', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('6', '0', '-', 'SENGGIGI', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('7', '-', '-', 'SENGGIGI', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('8', '001', '-', 'SENGGIGI', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('9', '0', '0', 'KERANDANGAN', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('10', '0', '-', 'KERANDANGAN', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('11', '-', '-', 'KERANDANGAN', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('12', '002', '-', 'KERANDANGAN', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('13', '0', '0', 'LOCO', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('14', '0', '-', 'LOCO', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('15', '-', '-', 'LOCO', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('16', '003', '-', 'LOCO', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('17', '001', '-', 'KERANDANGAN', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('18', '005', '-', 'SENGGIGI', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('19', '005', '-', 'MANGSIT', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('20', '005', '-', 'LOCO', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('21', '003', '-', 'SENGGIGI', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('22', '002', '-', 'LOCO', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('23', '004', '-', 'SENGGIGI', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('24', '004', '-', 'LOCO', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('25', '001', '-', 'LOCO', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('26', '002', '-', 'MANGSIT', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('27', '004', '-', 'KERANDANGAN', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('28', '003', '-', 'MANGSIT', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('29', '006', '-', 'SENGGIGI', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('30', '006', '-', 'KERANDANGAN', '0', '', '', '0', '', '');
INSERT INTO tweb_wil_clusterdesa (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES ('31', '002', '-', 'SENGGIGI', '0', '', '', '0', '', '');


#
# TABLE STRUCTURE FOR: user
#

DROP TABLE IF EXISTS user;

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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

INSERT INTO user (`id`, `username`, `password`, `id_grup`, `email`, `last_login`, `active`, `nama`, `company`, `phone`, `foto`, `session`) VALUES ('1', 'admin', '$2y$10$CfFhuvLXa3RNotqOPYyW2.JujLbAbZ4YO0PtxIRBz4QDLP0/pfH6.', '1', 'admin@combine.or.id', '2018-05-28 09:48:41', '1', 'Administrator', 'ADMIN', '321', 'favicon.png', 'a8d4080245664ed2049c1b2ded7cac30');


#
# TABLE STRUCTURE FOR: user_grup
#

DROP TABLE IF EXISTS user_grup;

CREATE TABLE `user_grup` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO user_grup (`id`, `nama`) VALUES ('1', 'Administrator');
INSERT INTO user_grup (`id`, `nama`) VALUES ('2', 'Operator');
INSERT INTO user_grup (`id`, `nama`) VALUES ('3', 'Redaksi');
INSERT INTO user_grup (`id`, `nama`) VALUES ('4', 'Kontributor');


#
# TABLE STRUCTURE FOR: widget
#

DROP TABLE IF EXISTS widget;

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

INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('1', '<p><iframe src=\"https://www.google.co.id/maps?f=q&source=s_q&hl=en&geocode=&q=Logandu,+Karanggayam&aq=0&oq=logan&sll=-2.550221,118.015568&sspn=52.267573,80.332031&t=h&ie=UTF8&hq=&hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&ll=-7.55854,109.634173&spn=0.052497,0.078449&z=14&output=embed\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" width=\"100%\"></iframe></p> ', '2', 'Peta Desa', '3', '2', '', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('2', 'layanan_mandiri.php', '1', 'Layanan Mandiri', '1', '5', 'mandiri', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('3', 'agenda.php', '1', 'Agenda', '1', '7', 'web/index/1000', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('4', 'galeri.php', '1', 'Galeri', '1', '8', 'gallery', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('5', 'statistik.php', '1', 'Statistik', '1', '9', '', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('6', 'komentar.php', '1', 'Komentar', '1', '10', 'komentar', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('7', 'media_sosial.php', '1', 'Media Sosial', '1', '11', 'sosmed', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('8', 'peta_lokasi_kantor.php', '1', 'Peta Lokasi Kantor', '1', '12', 'hom_desa', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('9', 'statistik_pengunjung.php', '1', 'Statistik Pengunjung', '1', '13', '', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('10', 'arsip_artikel.php', '1', 'Arsip Artikel', '1', '14', '', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('11', 'aparatur_desa.php', '1', 'Aparatur Desa', '1', '4', 'pengurus', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('12', 'sinergi_program.php', '1', 'Sinergi Program', '1', '6', 'web_widget/admin/sinergi_program', '[]');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('13', 'menu_kategori.php', '1', 'Menu Kategori', '1', '3', '', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('14', 'peta_wilayah_desa.php', '1', 'Peta Wilayah Desa', '1', '1', 'hom_desa/konfigurasi', '');


DROP VIEW IF EXISTS data_surat;
DROP TABLE IF EXISTS mutasi_inventaris;
DROP TABLE IF EXISTS inventaris;
#
# TABLE STRUCTURE FOR: jenis_barang
#

DROP TABLE IF EXISTS jenis_barang;

CREATE TABLE `jenis_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS suplemen_terdata;
#
# TABLE STRUCTURE FOR: suplemen
#

DROP TABLE IF EXISTS suplemen;

CREATE TABLE `suplemen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `keterangan` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: inventaris
#

DROP TABLE IF EXISTS inventaris;

CREATE TABLE `inventaris` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_barang` int(6) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `tanggal_pengadaan` date NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `asal_barang` tinyint(2) NOT NULL,
  `jml_barang` int(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_jenis_barang` (`id_jenis_barang`),
  CONSTRAINT `inventaris_ibfk_1` FOREIGN KEY (`id_jenis_barang`) REFERENCES `jenis_barang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: mutasi_inventaris
#

DROP TABLE IF EXISTS mutasi_inventaris;

CREATE TABLE `mutasi_inventaris` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_barang` int(6) DEFAULT NULL,
  `tanggal_mutasi` date NOT NULL,
  `jenis_mutasi` tinyint(2) DEFAULT NULL,
  `jenis_penghapusan` tinyint(2) DEFAULT NULL,
  `jml_mutasi` int(6) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `mutasi_inventaris_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `inventaris` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: suplemen_terdata
#

DROP TABLE IF EXISTS suplemen_terdata;

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

