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
  PRIMARY KEY (`id`),
  KEY `id_master` (`id_master`,`id_tipe`),
  KEY `id_tipe` (`id_tipe`),
  KEY `id_kategori` (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`) VALUES ('1', '2', '1', 'kepemilikan rumah', '1', '1', '1', '1', '0');
INSERT INTO analisis_indikator (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`) VALUES ('2', '2', '2', 'penghasilan perbulan', '1', '4', '1', '2', '0');


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('1', '2', 'Aset', '');
INSERT INTO analisis_kategori_indikator (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES ('2', '2', 'Penghasilan', '');


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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO analisis_master (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`) VALUES ('1', 'Analisis Keahlian Individu', '1', '1', '<p>survey</p>', '00000', '0', '1', '0');
INSERT INTO analisis_master (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`) VALUES ('2', 'AKP Lombok Tengah', '2', '1', '<p>keterangan</p>', '00000', '0', '1', '0');


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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('1', '1', 'milik sendiri', '5', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('2', '1', 'milik orang tua', '4', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('3', '1', 'kontrak', '1', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('4', '2', '< Rp.500.000,-', '1', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('5', '2', 'Rp 500.000,- sampa Rp 1.000.000,-', '3', '0', '0');
INSERT INTO analisis_parameter (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES ('6', '2', 'diatas Rp 2.000.000,-', '5', '0', '0');


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO analisis_periode (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES ('1', '2', 'Pendataan 2014', '2', '2', 'ket', '2014');
INSERT INTO analisis_periode (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES ('2', '2', 'Pendataan 2015', '1', '1', 'nnn', '2015');


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8;

INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('7', '', '<p><strong>Awal mula SID</strong><br /> \"Awalnya ada keinginan dari pemerintah Desa Balerante yang berharap pelayanan pemerintah desa bisa seperti pengunjung rumah sakit yang ingin mencari data pasien rawat inap, tinggal ketik nama di komputer, maka data tersebut akan keluar\"<br /> (Mart Widarto, pengelola Program Lumbung Komunitas)<br /> Program ini mulai dibuat dari awal 2006: <br /> 1. (2006) komunitas melakukan komunikasi dan diskusi lepas tentang sebuah sistem yang bisa digunakan untuk menyimpan data.<br /> 2. (2008) Rangkaian FDG dengan pemerintah desa membahas tentang tata kelola pendokumentasian di desa<br /> 3. (2009) Ujicoba SID yang sudah dikembangkan di balerante<br /> 4. (2009-2010) Membangun SID (aplikasi) dibeberapa desa yang lain: terong (bantul), Nglegi (Gunungkidul) <br /> 5. (2011) Kandangan (Temanggung) Gilangharjo (bantul) Girikarto (gunungkidul) Talun (klaten) Pager Gunung (magelang) <br /> 6. hingga saat ini 2013 sudah banyak desa pengguna SID.<br /> <br /> <strong>SID sebagai tanggapan atas kebutuhan:</strong><br /> Kalau dulu untuk mencari data penduduk menurut kelompok umur saja kesulitan karena tidak mempunyai databasenya. Dengan adanya SID menjadi lebih mudah.<br /> (Nuryanto, Kabag Pelayanan Pemdes Terong)<br /> <br /> Membangun sebuah sistem bukan hanya membuatkan software dan meninggalkan begitu saja, namun ada upaya untuk memadukan sistem dengan kebutuhan yang ada pada desa. sehingga software dapat memenuhi kebutuhan yang telah ada bukan memaksakan desa untuk mengikuti dan berpindah sistem. inilah yang melatari combine melaksanakan alur pengaplikasian software.<br /> 1. Bentuk tim kerja bersama pemerintah desa<br /> 2. Diskusikan basis data apa saja yang diperlukan untuk warga<br /> 3. Himpun data kependudukan warga dari Kartu Keluarga (KK)<br /> 4. Daftarkan proyek SID dan dapatkan aplikasi softwarenya di http://abcd.lumbungkomunitas.net<br /> 5. Install aplikasi software SID di komputer desa<br /> 6. Entry data penduduk ke SID<br /> 7. Basis data kependudukan sudah bisa dimanfaatkan<br /> 8. Diskusikan rencana pengembangan SID sesuai kebutuhan desa<br /> 9. Sebarluaskan informasi desa melalui beragam media untuk warga<br /> <br /> Pemberdayaan data desa yang dibangun diharapkan dapat menjunjung kesejahteraan masyarakat desa, data-data tersebut dapat diperuntukkan untuk riset lebih lanjut tentang kemiskinan, tanggap bencana, sumberdaya desa yang bisa diekspose keluar dan dengan menghubungkan dari desa ke desa dapat mencontohkan banyak hal dalam keberhasilan pemberdayaannya.<br /> (sumber: Buku Sistem Informasi Desa) <br /> <strong><br /></strong></p>', '1', '2013-03-31 20:31:04', '999', '1', 'Awal mula SID', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('32', '', '<div class=\"contentText\">\r\n<div align=\"justify\">Bagian ini berisi informasi dasar mengenai desa kami. Sila klik pada tautan berikut untuk mendapatkan informasi yang lebih rinci.</div>\r\n<div align=\"justify\">&nbsp;</div>\r\n<div align=\"justify\"><ol>\r\n<li>Sejarah Desa</li>\r\n<li>Profil Wilayah Desa</li>\r\n<li>Profil Masyarakat Desa</li>\r\n<li>Profil Potensi Desa</li>\r\n</ol></div>\r\n</div>', '1', '2013-07-29 17:46:44', '999', '1', 'Profil Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('34', '', '<p style=\"text-align: justify;\"><span style=\"color: #ff0000;\"><strong>Contoh (Sila edit halaman ini sesuai dengan deskripsi desa ini)!</strong></span></p>\r\n<p style=\"text-align: justify;\">Berdasarkan data desa pada bulan Februari 2010, jumlah penduduk Desa Terong sebanyak 6484 orang. Jumlah Kepala Keluarga (KK) sebanyak 1605 KK.</p>\r\n<p style=\"text-align: justify;\">Jumlah penduduk Desa Terong usia produktif pada tahun 2009 adalah 4746 orang. Jumlah angkatan kerja tersebut jika dilihat berdasarkan tingkat pendidikannya adalah sebagai berikut:</p>\r\n<table style=\"width: 100%;\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">\r\n<tbody>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\"><strong>No.</strong></p>\r\n</td>\r\n<td style=\"width: 42%;\">\r\n<p style=\"text-align: center;\"><strong>Angkatan Kerja</strong></p>\r\n</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\"><strong>L</strong></p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\"><strong>P</strong></p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\"><strong>Jumlah</strong></p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">1</p>\r\n</td>\r\n<td style=\"width: 42%;\">Tidak Tamat SD</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">59</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">56</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">115</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">2</p>\r\n</td>\r\n<td style=\"width: 42%;\">SD</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">880</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">792</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1672</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">3</p>\r\n</td>\r\n<td style=\"width: 42%;\">SLTP</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">813</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">683</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1496</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">4</p>\r\n</td>\r\n<td style=\"width: 42%;\">SLTA</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">725</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">673</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1398</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">5</p>\r\n</td>\r\n<td style=\"width: 42%;\">Akademi</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">13</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">11</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">24</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">6</p>\r\n</td>\r\n<td style=\"width: 42%;\">Perguruan Tinggi</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">23</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">18</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">41</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 50%;\" colspan=\"2\">\r\n<p style=\"text-align: center;\">Jumlah Total</p>\r\n</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">2513</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">2233</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">4746</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Profil sosial masyarakat</strong></p>\r\n<p style=\"text-align: justify;\">Dalam aktivitas keseharian, masyarakat Desa Terong sangat taat dalam menjalankan ibadah keagamaan. Setiap Rukung Tetangga (RT) dan pedukuhan memiliki kelompok-kelompok pengajian. Pada peringatan hari besar Islam, penduduk Desa Terong kerap menggelar acara peringatan dan karnaval budaya dengan tema yang disesuaikan dengan hari besar keagamaan. Sebagian besar warga Desa Terong terafiliasi pada organisasi kemasyarakat Islam Muhammadiyah.</p>\r\n<p style=\"text-align: justify;\">Gelaran perayaan lain selalu dilakukan dalam rangka memperingati Hari Kemerdekaan Republik Indonesia. Setiap pedukuhan akan turut serta dan semangat menampilkan ciri khasnya dalam acara peringatan dan karnaval budaya.</p>\r\n<p style=\"text-align: justify;\">Kelompok pemuda di Desa Terong yang tergabung dalam kelompok pegiat Karang Taruna menjadi aktor utama dalam banyak kegiatan desa. Kelompok ini aktif menggelar program kegiatan untuk isu demokrasi kepada warga, penguatan ekonomi produktif, pelatihan penanggulangan bencana, dan kampanye Gerakan Remaja Sayang Ibu (GEMAS).</p>\r\n<p style=\"text-align: justify;\">Sejumlah penduduk Desa Terong bekerja merantau di daerah di luar Yogyakarta. Namun, ikatan sosial mereka terhadap tanah kelahiran tetap tinggi. Penduduk asli Desa Terong yang tinggal di Jakarta dan sekitarnya misalnya, mereka membentuk paguyuban untuk memelihara silaturahmi antar sesama warga perantauan. Setiap bulan diadakan kegiatan arisan keliling secara bergilir di setiap tempat anggotanya. Setiap dua tahun sekali diadakan pula kegiatan mudik bersama ke kampung halaman di Desa Terong</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Profil politik masyarakat</strong></p>\r\n<p style=\"text-align: justify;\">Warga Desa Terong dikenal sebagai kelompok masyarakat yang paling aktif dan memiliki potensi tertinggi untuk berpartisipasi dalam pemberian suara untuk Pemilihan Umum dan Pemilihan Kepala Daerah Langsung. Tingkat partisipasi warga di desa ini terbanyak jika dibandingkan dengan desa lain di Kecamatan Dlingo, Bantul.</p>\r\n<p style=\"text-align: justify;\">Warga Desa Terong sangat aktif dalam mengawal proses penyusunan Rancangan Undang-Undang (RUU) Keistimewaan Yogyakarta. Banyak warga Desa Terong yang tergabung dalam Gerakan Rakyat Yogyakarta (GRY) dan aktif dalam beragam kegiatan serta demontrasi mendukung penetapan keistimewaan Yogyakarta. Kepala Desa Terong Sudirman Alfian merupakan Ketua Paguyuban Lurah dan Pamong Desa Ing Sedya Memetri Asrining Yogyakarta (ISMAYA) se Daerah Istimewa Yogyakarta (DIY). Beliau ditunjuk pula sebagai anggota tim perumus RUU Keistimewaan Yogyakarta bersi masyarakat Yogyakarta. Salah satu hal yang diperjuangkan dalam RUU tersebut adalah tidak adanya pelaksanaan pemilihan kepala daerah langsung dalam pemilihan Gubernur DIY; dengan mempertahankan konsep dwi tunggal Sri Sultan Hamengku Buwono dan Paku Alam sebagai Gubernur dan Wakil Bubernur DIY.</p>\r\n<p style=\"text-align: justify;\">Permasalahan mendasar yang ada di Desa Terong adalah tidak imbangnya jumlah pencari kerja dengan jumlah lapangan kerja yang tersedia. Sekalipun jumlah pengangguran di Desa Terong pada Tahun 2009 hanya orang tetapi kebanyakan mereka bekerja di luar Desa. Jadi, perlu gerakan kembali ke Desa serta menarik sumber-sumber ekonomi ke desa agar pencari kerja tidak banyak tersedot ke luar Desa.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Sumber:<br />Laporan Pertanggung Jawaban Lurah Desa Terong, Kecamatan Dlingo, Kabupaten Bantul tahun 2009.</p>', '1', '2013-07-29 18:13:36', '999', '1', 'Profil Masyarakat Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('36', '', '<p>Kontak kami berisi cara menghubungi desa, seperti contoh berikut :</p>\r\n<p>Alamat : Jl desa no 01</p>\r\n<p>No Telepon : 081xxxxxxxxx</p>\r\n<p>Email : xx@desa.com</p>', '1', '2013-07-29 18:28:31', '999', '1', 'Kontak Kami', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('37', '', '<p><span style=\"color: #ff0000;\"><strong>Contoh (Sila edit halaman ini dan sesuaikan dengan deskripsi untuk desa ini)!</strong></span><br /><br />Susunan Organisasi Badan Permusyawaratan Desa:</p>\r\n<p>Ketua</p>\r\n<p>Sekretaris</p>\r\n<p>Anggota</p>', '1', '2013-07-29 18:33:33', '999', '1', 'Badan Permusyawaratan Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('38', '', '<p>Berisi data lembaga yang ada di desa beserta deskripsi dan susunan pengurusnya</p>', '1', '2013-07-29 18:38:33', '999', '1', 'Lembaga Kemasyarakatan', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('40', '', '<p>Berisi tentang peraturan yang ada di Desa</p>', '1', '2013-07-29 19:06:50', '1001', '1', 'Peraturan', '0', '', '', '', '', '', NULL, '3', '1');
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
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('84', 'Penguins.jpg', '<p style=\"text-align: justify;\">1. Pada komputer/PC server, buka web-browser (direkomendasikan <span style=\"text-decoration: underline;\">Mozilla Firefox</span> atau <span style=\"text-decoration: underline;\">Chrome</span>)</p>\r\n<p style=\"text-align: justify; padding-left: 30px;\">Ketik:<span style=\"color: #0000ff;\"> localhot/phpmyadmin</span></p>\r\n<p>2. Muncul halaman <span style=\"text-decoration: underline;\">PHPMyAdmin</span>. Periksa kolom kiri.</p>\r\n<p style=\"padding-left: 30px;\">Klik/Pilih: &ldquo;sid&rdquo;</p>\r\n<p>3. Muncul halaman yang menampilkan deretan isi tabel &ldquo;<span style=\"text-decoration: underline;\">sid</span>&ldquo;.</p>\r\n<p style=\"padding-left: 30px;\">Klik/Pilih: &ldquo;Export&rdquo; pada bagian menu atas di kolom kanan</p>\r\n<p>4. Muncul halaman <span style=\"text-decoration: underline;\">Export</span>. Kolom isian yang ada tak perlu diubah. Lihat bagian kanan bawah.</p>\r\n<p style=\"padding-left: 30px;\">Klik: &ldquo;Go&rdquo;</p>\r\n<p>5. Muncul jendela pilihan lokasi untuk menyimpan file database.</p>\r\n<p style=\"padding-left: 30px;\">Klik: &ldquo;Save&rdquo;<br /> Klik: &ldquo;OK&rdquo;</p>\r\n<p>6. File database SID 3.0 akan tersimpan otomatis di folder Download (tergantung setting komputer masing-masing). File tersimpan dalam sebagai &ldquo;sid.sql&rdquo;</p>\r\n<p>7. Simpan/amankan file &ldquo;sid.sql&rdquo; di media lain: Hard-disk eksternal, CD, <em>online storage</em>, dan sebagainya.</p>\r\n<p>8. Lakukan proses <em>back-up</em> database ini secara berkala.</p>\r\n<p>.<br /> Selamat mencoba!</p>', '1', '2014-11-06 18:25:34', '1004', '1', 'Panduan Back-Up Data (Export Database) SID 3.0', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('85', '1471927406download (1).jpg', '<div class=\"contentText\">\r\n<div align=\"justify\">Bagian ini berisi informasi mengenai PemerintahanDesa. Sila klik pada tautan berikut untuk mendapatkan informasi yang lebih rinci.</div>\r\n<div align=\"justify\">&nbsp;</div>\r\n<ol>\r\n<li>Visi dan Misi</li>\r\n<li>Pemerintah Desa</li>\r\n<li>Badan Permusyawaratan Desa</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI dan MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Senggigi Berseri\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>(Bersih, Relegius, Sejahtera, Rapi, dan Indah)</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Terwujudnya masyarakat Desa Senggigi yang Bersih, Relegius, Sejahtra, Rapi dan Indah melalui Akselerasi Pembangunan yang berbasis Keagamaan, Budaya Hukum dan Berwawasan Lingkungan dengan berorentasi pada peningkatan Kinerja Aparatur dan Pemberdayaan Masyarakat\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Misi dan Program Desa Senggigi</strong></p>\r\n<p style=\"text-align: justify;\">Dan untuk melaksanakan visi Desa Senggigi dilaksanakan misi dan program sebagai berikut:</p>\r\n<p style=\"text-align: justify;\">1. Pembangunan Jangka Panjang</p>\r\n<ul>\r\n<li style=\"text-align: justify;\">Melanjutkan Pembangunan Desa yang belum terlaksana</li>\r\n<li style=\"text-align: justify;\">Meningkatkan Kerjasama antara pemerintah Desa dengan Lembaga desa yang ada</li>\r\n<li style=\"text-align: justify;\">Meningkatkan Kesejahtraan Masyarakat desa dengan meningkatkan sarana dan prasarana ekonomi warga.&nbsp;</li>\r\n</ul>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">2. Pembangunan Jangka Pendek &nbsp;</p>\r\n<ul>\r\n<li style=\"text-align: justify;\">Mengembangkan dan Menjaga serta melestarikan ada istiadat desa terutama yang telah mengakar di desa senggigi.&nbsp;</li>\r\n<li style=\"text-align: justify;\">Meningkatkan pelayanan dalam bidang pemerintahan kepada warga masyarakat&nbsp;</li>\r\n<li style=\"text-align: justify;\">Meningkatkan sarana dan prasarana ekonomi warga desa dengan perbaikan prasarana dan sarana ekonomi.</li>\r\n<li style=\"text-align: justify;\">Meningkatkan sarana dan prasarana pendidikan guna peningkatan sumber daya manusia desa senggigi.&nbsp;</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>Kepala Desa Senggigi</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>Muhammad Ilham</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n</div>', '1', '2014-11-07 10:53:54', '999', '1', 'Pemerintahan Desa', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('86', '', '', '1', '2014-12-10 15:00:22', '1006', '1', '', '0', '', '', '', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('87', '', '<p>bla bla bla</p>', '1', '2014-12-10 16:59:20', '16', '1', 'Sejumlah Tukang Becak Merampok Indoapril', '0', '', '', '', 'ddd.xls', 'hahaha', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('90', '1471968200IMG-20160823-WA0007.jpg', '', '1', '2016-08-24 00:03:21', '5', '1', 'PERDES PHBS ', '3', '1471968200IMG-20160823-WA0012.jpg', '1471968200', '1471968200', 'PERDES PHBS.docx', 'PERDES PHBS ', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('92', '1472006396', '<p><strong>Susunan Organisasi Pemerintah Desa Senggigi</strong><br /><br />Kepala Desa: MUHAMMAD ILHAM<br />Sekretaris Desa:&nbsp;<span>MUSTAHIQ, S.Adm</span><br />Kepala Urusan Pemerintahan: SYAFRUDIN<br />Kepala Urusan&nbsp;Pembangunan: SYAFI\'I, SE<br />Kepala Urusan&nbsp;Kesra: HAMIDIAH<br />Kepala Urusan&nbsp;Keuangan: MARDIANA<br />Kepala Urusan&nbsp;Trantib: SUPARDI RUSTAM<br />Kepala Urusan&nbsp;Umum: MAHRUP<br /><br /></p>', '1', '2016-08-24 10:39:56', '999', '1', 'Pemerintah Desa', '0', '1472006396', '1472006396', '1472006396', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('93', '1472006908', '<p style=\"text-align: center;\"><strong>VISI dan MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Senggigi Berseri\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>(Bersih, Relegius, Sejahtera, Rapi, dan Indah)</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Terwujudnya masyarakat Desa Senggigi yang Bersih, Relegius, Sejahtera, Rapi dan Indah melalui Akselerasi Pembangunan yang berbasis Keagamaan, Budaya Hukum dan Berwawasan Lingkungan dengan berorentasi pada peningkatan Kinerja Aparatur dan Pemberdayaan Masyarakat\"</strong></p>\r\n<p>&nbsp;&nbsp;</p>\r\n<p><strong>MISI</strong></p>\r\n<p><strong>Misi dan Program Desa Senggigi</strong></p>\r\n<p>Dan untuk melaksanakan visi Desa Senggigi dilaksanakan misi dan program sebagai berikut:</p>\r\n<p>1. Pembangunan Jangka Panjang</p>\r\n<p>&nbsp; &nbsp; - Melanjutkan pembangunan desa yang belum terlaksana.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan kerjasama antara pemerintah desa dengan lembaga desa yang ada.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan kesejahtraan masyarakat desa dengan meningkatkan sarana dan prasarana ekonomi warga.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>2. Pembangunan Jangka Pendek &nbsp;</p>\r\n<p>&nbsp; &nbsp; - Mengembangkan dan Menjaga serta melestarikan ada istiadat desa terutama yang telah mengakar di desa senggigi.&nbsp;</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan pelayanan dalam bidang pemerintahan kepada warga masyarakat&nbsp;</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan sarana dan prasarana ekonomi warga desa dengan perbaikan prasarana dan sarana ekonomi.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan sarana dan prasarana pendidikan guna peningkatan sumber daya manusia Desa Senggigi.&nbsp;</p>', '1', '2016-08-24 10:48:28', '999', '1', 'Visi dan Misi', '0', '1472006908', '1472006908', '1472006908', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('94', '1472782825artikel-2-1.jpg', '<p style=\"text-align: justify;\">Gotong royong yang dulu digagas pertama kali oleh pendiri bangsa, Ir. Soekarno kian hari semakin terkikis dengan budaya individual ditengah persaingan yang begitu ketat dalam mencapai tujuan tertentu, kenyataan inilah yang membuat nilai-nilai sosial ditatanan masyarakat yang sejak dulu sudah ditanamkan oleh nenek moyang kita luntur seiring dengan perkembangan jaman. padahal untuk mencapai suatu tujuan dan cita-cita bersama seharusnya dikerjakan secara bersama-sama.&nbsp;</p>\n<p style=\"text-align: justify;\">&nbsp;</p>\n<p style=\"text-align: justify;\">Maka dengan kenyataan inilah, pemerintah desa senggigi kembali melakukan sebuah inovasi baru dalam merangkul masyarakat agar terus menanamkan kebudayaan \"Gotong Raoyong\". kegitan gotong royong dalam pembangunan jalan desa sedikitnya melibatkan hampir 95% masyarakat senggigi, kebersamaan dan ikatan persaudaraan atas kepentingan bersama menjadi satu ketika semua masyarakat desa terlibat aktif, tanpa harus melihat tatanan dan golongan yang ada. masyarakat saling bahu membahu seiring kegitan gotong royong.&nbsp;</p>\n<p style=\"text-align: justify;\">&nbsp;</p>\n<p style=\"text-align: justify;\">&nbsp;</p>', '1', '2016-08-24 11:02:44', '1', '1', 'Membangun Desa Lewat Gotong Royong', '3', '1472782825artikel-2-2.jpeg', '1472007764', '1472007764', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('95', '1472782634galeri-1-1.jpg', '<p>Desa Senggigi ikut memeriahkan perayaan 17 Agustus 2016 sebagai hari jadi Indonesia yang ke 71 melalui kegiatan Karnaval yang diselenggarakan oleh Camat Batulayar Kabupaten Lombok Barat NTB. Acara karnaval dilaksanakan pada hari Rabu, 17 Agustus 2016 dimulai pukul 15.30 s/d 17.00 wita. Masing-masing desa berkumpul disekitaran kantor Camat Batulayar, dan berjalan menuju Taman Bale Pelangi Desa Sandik sebagai pusat titik kumpul seluruh peserta karnaval.&nbsp;</p>\n<p>&nbsp;</p>\n<p>Dalam karnaval ini, Desa Senggigi melibatkan berbagai unsur masyarakat seperti tokoh masyarakat, perempuan, pemuda dan anak-anak dengan menggunakan baju adat dan berbagai macam asesoris hari kemerdekaan, kegitan tersebut adalah salah satu cara bagaimana memupuk semangat bagi setiap warga negara, khususnya kaum muda sebagai harapan bangsa, yang kian hari semakin terkikis dengan pengaruh global saat ini.</p>\n<p>&nbsp;</p>\n<p>Lewat karang taruna desa senggigi, pemupukan pemberian semangat dalam berpacu memajukan desa dan bangsa terus dilakukan, berbagai macam kegiatan tahapan dalam pelaksanaan hari kemerdekaan terus di lakukan.&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>', '1', '2016-08-24 13:05:21', '1', '1', 'Perayaan Hari Kemerdekaan 2016', '3', '1472782634galeri-1-2.jpeg', '1472015120', '1472015120', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('96', '1472782915artikel-3-1.jpeg', '<p>Dalam rapat pembahasan komitmen perekrutan karyawan hotel pada tanggal 24 Agustus 2016 di kantor desa sengigi telah menyepakati beberapa komitmen bersama diantaranya sebagai berikut:</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>1. Dalam perekrutan karyawan, pihak hotel harus memprioritaskan masyarakat senggigi minimal 35%</p>\n<p>2. Pihak Hotel harus mengikuti program perencanaan tenaga kerja desa senggigi sesua dengan VISI dan MISI desa</p>\n<p>3. Pihak hotel harus melakukan kordinasi dengan pemerintah desa ketika perekrutan karyawan&nbsp;</p>\n<p>4. Pihak Hotel harus melakukan pelatihan bagi calon karyawan, khususnya karyawan yang berasal dari desa sengggigi</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>Bagi rekan-rekan pemuda dan masyarakat harap melakukan kordinasi dengan pemerintah desa terkait dengan beberapa hasil pertemuan dalam membangun komitme dengan pihak hotel, jika ada hal mendesak terkait beberapa syarat ketentuan perekrutan, rekan-rekan pemuda dan masyarakat bisa menghubungi kami di kantor desa..</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>', '1', '2016-08-24 13:55:10', '4', '1', 'Rapat membangun Komitmen antara Karang Taruna Desa Senggigi dengan Taruna Hotel', '3', '1472018109IMG-20160824-WA0000.jpg', '1472018109', '1472018109', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('97', '1472019299', '<p>Halaman ini berisi tautan menuju informasi mengenai Basis Data Desa. Ada dua jenis data yang dimuat dalam sistem ini, yakni basis data kependudukan dan basis data sumber daya desa. Sila klik pada tautan berikut untuk mendapatkan tampilan data statistik per kategori.</p>\r\n<ol>\r\n<li>Data Wilayah Administratif&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Pendidikan&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Pekerjaan&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Golongan Darah&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Agama&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Jenis Kelamin&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Kelompok Umur&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Penerima Raskin&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Penerima BPJS &nbsp; &nbsp; &nbsp; &nbsp;</li>\r\n<li>Data Warga Negara &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;</li>\r\n</ol>\r\n<p>Data yang tampil adalah statistik yang didapatkan dari proses olah data dasar yang dilakukan secara&nbsp;<em>offline</em>&nbsp;di kantor desa secara rutin/harian. Data dasar di kantor desa diunggah ke dalam sistem&nbsp;<em>online</em>&nbsp;di website ini secara berkala. Sila hubungi kontak pemerintah desa untuk mendapatkan data dan informasi desa termutakhir.</p>', '1', '2016-08-24 14:14:59', '999', '1', 'Data Desa', '0', '1472019299', '1472019299', '1472019299', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('98', '1472192894', '<p>Wilayah desa berisi tentang penjelasan dan deskripsi letak wilayah desa. contohnya sebagai berikut :<br />Batas-batas :<br />Utara&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kelurahan a<br />Timur &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Desa b<br />Selatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Desa c<br />Barat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kelurahan d dan Desa e<br />Luas Wilayah Desa Penglatan&nbsp;&nbsp; : 186,193 Ha<br />Letak Dan Batas Desa x<br />Desa Penglatan terletak pada posisi 115. 7.20 LS 8. 7.10 BT, dengan ketinggian kurang lebih 250 M diatas permukaan laut.</p>\r\n<p>Peta Desa:</p>\r\n<p><iframe src=\"https://www.google.co.id/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Logandu,+Karanggayam&amp;aq=0&amp;oq=logandu&amp;sll=-2.550221,118.015568&amp;sspn=52.267573,80.332031&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&amp;z=14&amp;ll=-7.55854,109.634173&amp;output=embed\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" width=\"600\" height=\"450\"></iframe></p>', '1', '2016-08-26 14:28:14', '999', '1', 'Wilayah Desa', '0', '1472192894', '1472192894', '1472192894', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('99', '1472228892Raja Lombok 1902.jpg', '<p style=\"text-align: justify;\" align=\"center\">Sejarah telah mencatat bahwa Pulau Lombok pernah menjadi wilayah kekuasaan Kerajaan Karang Asem Bali yang berkedudukan di Cakranegara dengan seorang raja bernama Anak Agung Gde Jelantik. Berakhirnya <strong>kekuasaan</strong> Kerajaan Karang Asem Bali di Pulau Lombok setelah datangnya Belanda pada Tahun 1891, dimana Belanda pada waktu itu ingin menguasai Pulau Lombok dengan dalih pura-pura membantu rakyat Lombok yang dianggap tertindas oleh Pemerintahan Raja Lombok yaitu Anak Agung Gede Jelantik.</p>\r\n<p style=\"text-align: justify;\">Pada masa kekuasaan Raja Lombok yaitu Anak Agung Gde Jelantik, wilayah Desa Senggigi ( Dusun Mangsit, Kerandangan, Senggigi dan Dusun Loco) masih bergabung dengan Desa Senteluk yang sekarang menjadi Desa Meninting . Sedangkan pada tahun 1962 Desa Senteluk pecah menjadi 2 (Dua) desa yaitu Desa Meninting dan Desa Batulayar dan Dusun Mangsit,Kerandangan,Senggigi dan Dusun Loco bergabung ke Desa Batulayar.&nbsp;</p>\r\n<p style=\"text-align: justify;\">Pemberian nama Desa Batulayar pada waktu itu yang lazim disebut dengan Pemusungan/Kepala Dea Batulayar berdasarkan hasil musyawarah nama Batulayar diambil dari nama tempat yang amat terkenal yaitu Makam Batulayar yang sampai saat ini banyak dikunjungi oleh masyarakat Pulau Lombok pada khususnya dan Masyarakat Nusa Tenggara Barat pada umumnya.</p>\r\n<p style=\"text-align: justify;\">Pada tahun 2001 Desa Batulayar dimekarkan menjadi 2 (dua) yaitu Desa Batulayar (sebagai Desa Induk) dan Desa Senggigi (sebagai Desa Persiapan) dengan SK.Bupati No : 30 Tahun 2001 tanggal 17 Mei 2001, yang pada waktu itu yang menjadi pejabat Kepala Desa Senggigi ialah <strong>H. ARIF RAHMAN, S.IP</strong>., dengan jumlah dusun sebanyak 3 dusun, yaitu :</p>\r\n<p>1. Dusun Senggigi</p>\r\n<p>2. Dusun Kerandangan</p>\r\n<p>3. Dusun Mangsit</p>\r\n<p>Selanjutnya pada tanggal 30 Juli 2003 Pejabat Kepala Desa Senggigi dari <strong>H. ARIF RAHMAN, S.IP</strong> diganti oleh Saudara<strong> ARIFIN</strong> dengan SK. Bupati Lombok Barat No : 409/66/pem/2003. Berhubung Desa Senggigi masih bersifat Desa Persiapan, maka berdasarkan hasil musyawarah desa, tertanggal 15 Desember 2003 , maka pada tanggal 22 Desember 2003 Desa Senggigi mengadakan Pemilihan Kepala Desa devinitif yang pertama kali dipimpin oleh&nbsp;<strong>HAJI JUNAIDI</strong>&nbsp;terpilih&nbsp;dengan SK. Bupati Lombok Barat No :01/01/Pem/2004 tertanggal 2 Januari 2004&nbsp;sampai pada tahun 2008.&nbsp;</p>\r\n<p style=\"text-align: justify;\">Selanjutnya pada tahun 2008, Desa Senggigi mengadakan pemilihan Kepala Desa Senggigi yang kedua dan dimenangkan oleh Bapak <strong>H. MUTAKIR AHMAD</strong>&nbsp;dengan&nbsp;SK. Bupati Lombok Barat No :1320/48/Pem./2008 tertanggal 23 Desember 2008, Periode 2008-2014. &nbsp;Kemudian Kepala desa terpilih Periode 2015 s/d 2021&nbsp;adalah <strong>MUHAMMAD ILHAM</strong>&nbsp;dengan SK. Bupati Lombok Barat No : 160/04/BPMPD/15 tanggal 27 Januari 2015 kini baru menjabat 2 (dua) bulan.</p>\r\n<p style=\"text-align: justify;\">Demikian selanyang pandang atau sejarah singkat Desa Senggigi yang dapat kami sampaikan kepada para pegiat Medsos, semoga dapat bermanfaat untuk kita semua, terima kasih.</p>\r\n<p style=\"text-align: justify;\" align=\"center\">&nbsp;</p>\r\n<p style=\"text-align: justify;\" align=\"center\">&nbsp;</p>', '1', '2016-08-26 15:38:09', '999', '1', 'Sejarah Desa', '3', '1472229325490125_20121123041539.jpg', '1472197089', '1472197089', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('100', '1473071921', '<p>Ini contoh teks berjalan. Isi dengan tulisan yang menampilkan suatu ciri atau kegiatan penting di desa anda.</p>', '1', '2016-09-05 10:38:41', '22', '1', 'Contoh teks berjalan', '0', '1473071921', '1473071921', '1473071921', '', '', NULL, '3', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('111', '', 'layanan_mandiri.php', '1', '2017-07-05 09:59:33', '1003', '0', 'Layanan Mandiri', '0', '', '', '', '', '', '1', '1', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('112', '', 'agenda.php', '1', '2017-07-05 09:59:33', '1003', '0', 'Agenda', '0', '', '', '', '', '', '1', '1', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('113', '', 'galeri.php', '1', '2017-07-05 09:59:33', '1003', '0', 'Galeri', '0', '', '', '', '', '', '1', '1', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('114', '', 'statistik.php', '1', '2017-07-05 09:59:33', '1003', '0', 'Statistik', '0', '', '', '', '', '', '1', '1', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('115', '', 'komentar.php', '1', '2017-07-05 09:59:33', '1003', '0', 'Komentar', '0', '', '', '', '', '', '1', '1', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('116', '', 'media_sosial.php', '1', '2017-07-05 09:59:33', '1003', '0', 'Media Sosial', '0', '', '', '', '', '', '1', '1', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('117', '', 'peta_lokasi_kantor.php', '1', '2017-07-05 09:59:33', '1003', '0', 'Peta Lokasi Kantor', '0', '', '', '', '', '', '1', '1', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('118', '', 'statistik_pengunjung.php', '1', '2017-07-05 09:59:33', '1003', '0', 'Statistik Pengunjung', '0', '', '', '', '', '', '1', '1', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('119', '', 'arsip_artikel.php', '1', '2017-07-05 09:59:33', '1003', '0', 'Arsip Artikel', '0', '', '', '', '', '', '1', '1', '1');
INSERT INTO artikel (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `urut`, `jenis_widget`, `boleh_komentar`) VALUES ('120', '', 'aparatur_desa.php', '1', '2017-07-05 09:59:33', '1003', '0', 'Aparatur Desa', '0', '', '', '', '', '', '1', '1', '1');


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

INSERT INTO config (`id`, `nama_desa`, `kode_desa`, `nama_kepala_desa`, `nip_kepala_desa`, `kode_pos`, `nama_kecamatan`, `kode_kecamatan`, `nama_kepala_camat`, `nip_kepala_camat`, `nama_kabupaten`, `kode_kabupaten`, `nama_propinsi`, `kode_propinsi`, `logo`, `lat`, `lng`, `zoom`, `map_tipe`, `path`, `alamat_kantor`, `g_analytic`, `email_desa`, `telepon`, `website`) VALUES ('1', 'Senggig1 ', '05', 'Muhammad Ilham ', '--', '83355', 'Batulay4r ', '14', 'Bambang Budi Sanyoto, S. H', '-', 'Lombok Bar4t ', '01', 'NT13 ', '52', 'opensid_logo.png', '-8.488005310891758', '116.0406072534065', '19', 'hybrid', '(-7.8312189550359586, 110.2565517439507);(-7.8676102927000695, 110.25380516191944);(-7.843803235881495, 110.29843711992726);(-7.831899196157655, 110.36504173418507);(-7.8169336350355465, 110.32933616777882);(-7.8169336350355465, 110.29775047441944);', 'Jl. Raya Senggigi Km 10 Kerandangan ', 'gsgsdgsdgsg', '', '', '');


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
  `alamat_ext` varchar(100) DEFAULT NULL,
  `userID` mediumint(9) DEFAULT NULL,
  `peta` text,
  `rdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
  `lk` int(11) NOT NULL,
  `pr` int(11) NOT NULL,
  `kk` int(11) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `kk_lk` int(11) DEFAULT NULL,
  `kk_pr` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO log_bulanan (`id`, `pend`, `lk`, `pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`) VALUES ('1', '97', '46', '51', '37', '2017-04-11 02:01:54', '28', '9');
INSERT INTO log_bulanan (`id`, `pend`, `lk`, `pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`) VALUES ('2', '97', '46', '51', '37', '2017-05-10 21:03:26', '28', '9');
INSERT INTO log_bulanan (`id`, `pend`, `lk`, `pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`) VALUES ('3', '97', '46', '51', '37', '2017-06-05 10:08:30', '28', '9');
INSERT INTO log_bulanan (`id`, `pend`, `lk`, `pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`) VALUES ('4', '97', '46', '51', '37', '2017-07-03 12:19:17', '28', '9');
INSERT INTO log_bulanan (`id`, `pend`, `lk`, `pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`) VALUES ('5', '97', '46', '51', '37', '2017-08-01 01:37:30', '28', '9');


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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO log_surat (`id`, `id_format_surat`, `id_pend`, `id_pamong`, `id_user`, `tanggal`, `bulan`, `tahun`, `no_surat`, `nama_surat`, `lampiran`, `nik_non_warga`, `nama_non_warga`) VALUES ('1', '1', '3', '14', '1', '2016-09-13 04:05:10', '09', '2016', 'KET/345', 'surat_ket_pengantar_5201140301900002_2016-09-13_KET-345.pdf', NULL, NULL, NULL);
INSERT INTO log_surat (`id`, `id_format_surat`, `id_pend`, `id_pamong`, `id_user`, `tanggal`, `bulan`, `tahun`, `no_surat`, `nama_surat`, `lampiran`, `nik_non_warga`, `nama_non_warga`) VALUES ('2', '15', '8', '14', '1', '2016-09-13 04:06:40', '09', '2016', 'USA/67/123', 'surat_ket_usaha_5201141412020001_2016-09-13_USA-67-123.pdf', NULL, NULL, NULL);
INSERT INTO log_surat (`id`, `id_format_surat`, `id_pend`, `id_pamong`, `id_user`, `tanggal`, `bulan`, `tahun`, `no_surat`, `nama_surat`, `lampiran`, `nik_non_warga`, `nama_non_warga`) VALUES ('3', '1', '18', '20', '1', '2016-09-13 04:07:53', '09', '2016', 'KET/346', 'surat_ket_pengantar_5201147112930055_2016-09-13_KET-346.pdf', NULL, NULL, NULL);
INSERT INTO log_surat (`id`, `id_format_surat`, `id_pend`, `id_pamong`, `id_user`, `tanggal`, `bulan`, `tahun`, `no_surat`, `nama_surat`, `lampiran`, `nik_non_warga`, `nama_non_warga`) VALUES ('4', '12', '9', '14', '1', '2016-09-13 04:09:02', '09', '2016', 'KM/104', 'surat_ket_kurang_mampu_5201140107850071_2016-09-13_KM-104.pdf', NULL, NULL, NULL);
INSERT INTO log_surat (`id`, `id_format_surat`, `id_pend`, `id_pamong`, `id_user`, `tanggal`, `bulan`, `tahun`, `no_surat`, `nama_surat`, `lampiran`, `nik_non_warga`, `nama_non_warga`) VALUES ('5', '14', '25', '22', '1', '2016-09-13 04:10:26', '09', '2016', 'HIL/503', 'surat_ket_kehilangan_5201142210790004_2016-09-13_HIL-503.pdf', NULL, NULL, NULL);


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO media_sosial (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES ('1', 'fb.png', 'https://www.facebook.com/groups/OpenSID/', 'Facebook', '1');
INSERT INTO media_sosial (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES ('2', 'twt.png', '', 'Twitter', '1');
INSERT INTO media_sosial (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES ('3', 'goo.png', '', 'Google Plus', '1');
INSERT INTO media_sosial (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES ('4', 'yb.png', '', 'YouTube', '1');


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
# TABLE STRUCTURE FOR: ref_kelas_sosial
#

DROP TABLE IF EXISTS ref_kelas_sosial;

CREATE TABLE `ref_kelas_sosial` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO ref_kelas_sosial (`id`, `nama`) VALUES ('1', 'Tidak Miskin');
INSERT INTO ref_kelas_sosial (`id`, `nama`) VALUES ('2', 'Sedang');
INSERT INTO ref_kelas_sosial (`id`, `nama`) VALUES ('3', 'Miskin');
INSERT INTO ref_kelas_sosial (`id`, `nama`) VALUES ('4', 'Sangat Miskin');


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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('1', 'SID Home', 'hom_desa', '1', 'go-home-5.png', '1', '2', '1');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('2', 'Penduduk', 'penduduk/clear', '1', 'preferences-contact-list.png', '2', '2', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('3', 'Statistik', 'statistik', '1', 'statistik.png', '3', '2', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('4', 'Cetak Surat', 'surat', '1', 'applications-office-5.png', '4', '2', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('5', 'Analisis', 'analisis_master/clear', '1', 'analysis.png', '5', '2', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('6', 'Bantuan', 'program_bantuan', '1', 'program.png', '6', '2', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('7', 'Persil', 'data_persil/clear', '1', 'persil.png', '7', '2', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('8', 'Plan', 'plan', '1', 'plan.png', '8', '2', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('9', 'Peta', 'gis', '1', 'gis.png', '9', '2', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('10', 'SMS', 'sms', '1', 'mail-send-receive.png', '10', '2', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('11', 'Pengguna', 'man_user/clear', '1', 'system-users.png', '11', '1', '1');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('12', 'Database', 'database', '1', 'database.png', '12', '1', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('13', 'Admin Web', 'web', '1', 'message-news.png', '13', '4', '0');
INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`) VALUES ('14', 'Laporan', 'lapor', '1', 'mail-reply-all.png', '14', '2', '0');


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

INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('14', 'Muhammad Ilham ', '', '', 'Kepala Desa', '1', '2014-04-20', '1', '');
INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('20', 'Mustahiq S.Adm', '197905062010011007', '5201140506790001', 'Sekretaris Desa', '1', '2016-08-23', NULL, '');
INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('21', 'Syafruddin ', '-', '5201140911720004', 'Kaur Pemerintahan ', '1', '2016-08-23', NULL, '');
INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('22', 'Supardi Rustam ', '-', '5201140101710003', 'Kaur Umum ', '1', '2016-08-23', NULL, '');
INSERT INTO tweb_desa_pamong (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`) VALUES ('23', 'Mardiana ', '-', '5201145203810001', 'Kaur Keuangan', '1', '2016-08-23', NULL, '');
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
  `pendidikan_id` int(10) unsigned NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('1', 'AHLUL', '5201142005716996', '1', '1', '0', '0', '1', 'MANGSIT', '1970-05-20', '1', '3', '0', '18', '26', '2', '1', '', '0', '', '', 'ARFAH', 'RAISAH', '', '13', '4', '1', '', '', '1', '0', '0', '0', '', '', NULL, '', NULL, '0', NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('2', 'AHMAD ALLIF RIZKI', '5201140706966997', '1', '4', '0', '0', '1', 'MANGSIT', '1995-06-07', '1', '1', '0', '18', '1', '1', '1', '', '0', '', '', 'AHLUL', 'RUSDAH', '', '13', '4', '1', '', '', '1', '0', '0', '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('3', 'AHMAD HABIB', '5201140301916995', '1', '4', '0', '0', '1', 'MANGSIT', '1990-01-03', '1', '3', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'AHLUL', 'RUSDAH', '', '13', '4', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('4', 'ADINI SEPTIA LISTA', '5201145003976995', '1', '4', '0', '0', '2', 'MANGSIT', '1996-03-10', '1', '4', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'AHLUL', 'RUSDAH', '', '13', '4', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('5', 'AHYAR', '5201141003666996', '2', '1', '0', '0', '1', 'JAKARTA', '1965-03-10', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'PAIMUN', 'SUPINAH', '', '13', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('6', 'APTA MADA RIZKY ALAMSYAH', '5201141412121724', '2', '4', '0', '0', '1', 'DEPOK', '2002-12-14', '1', '2', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'AHYAR', 'ALIYAH', '', '13', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('7', 'ALIYAH', '5201144609786995', '2', '3', '0', '0', '2', 'BEKASI', '1977-09-06', '1', '5', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'TAGOR SIPAHUTAR', 'AMAHWATI', '', '13', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('8', 'ALPIANI', '5201144301171725', '2', '4', '0', '0', '2', 'BOGOR', '2007-01-03', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'AHYAR', 'ALIYAH', '', '13', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('9', 'ASHARI', '5201140107867064', '3', '1', '0', '0', '1', 'KERANDANGAN', '1985-12-30', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'H. ABDUL KARIM', 'RADIAH', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('10', 'BACHTIAR HADI', '5201142210181724', '3', '4', '0', '0', '1', 'MATARAM', '2008-10-22', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'ASHARI', 'ANGGUN LESTARI PRATAMA', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('11', 'ANGGUN LESTARI PRATAMA', '5201146510916995', '3', '3', '0', '0', '2', 'SENGGIGI', '1990-10-25', '1', '4', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'SADIRAH', 'HJ. ROHANI', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('12', 'DAHRI', '5201143112797117', '4', '1', '0', '0', '1', 'MASBAGIK', '1978-12-31', '1', '3', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'AMAQ SAHMINI', 'INAQ SAHMINI', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('13', 'ERLANGGA DWIKO SAPUTRO', '5201140705156994', '4', '4', '0', '0', '1', 'MENINTING', '2014-05-07', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'DAHRI', 'ASMIATUN', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('14', 'FARIDAH', '5201141107101724', '4', '4', '0', '0', '1', 'MASBAGIK', '2000-07-11', '1', '3', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'DAHRI', 'ASMIATUN', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('15', 'ASMIATUN', '5201147112817188', '4', '3', '0', '0', '2', 'MASBAGIK', '1980-12-31', '1', '4', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'AMAQ MUJAENI', 'INAQ SAHMINI', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('16', 'BAIQ OLIVIA APRILLIANI', '5201145211486994', '5', '1', '0', '0', '2', 'SENGGIGI', '1947-11-12', '1', '1', '0', '18', '1', '4', '1', NULL, NULL, '', '', 'AMAQ SINAREP', 'INAQ SINAREP', '', '13', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('17', 'FAUZI', '5201141210906998', '6', '1', '0', '0', '1', 'KERANDANGAN', '1989-10-12', '1', '5', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'SABLI', 'RAOHUN', '', '13', '17', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('18', 'DELLA MAHARANI NINGSIH', '5201147112947048', '6', '9', '0', '0', '2', 'KERANDANGAN', '1993-12-31', '1', '4', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'SABLI', 'RAOHUN', '', '13', '17', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('19', 'HAERUL FATONI', '5201142911936995', '7', '1', '0', '0', '1', 'SENGGIGI', '1992-11-29', '1', '5', '0', '18', '15', '2', '1', NULL, NULL, '', '', 'ANGKASAH', 'MARJANAH', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('20', 'DENATUL SUARTINI', '3275014601977005', '7', '3', '0', '0', '2', 'JAKARTA', '1996-01-06', '1', '5', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'G. AMIN. P', 'NGATI', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('21', 'HERI IRAWAN', '5201140607636994', '8', '1', '0', '0', '1', 'TELOKE', '1962-07-06', '1', '3', '0', '18', '9', '2', '1', NULL, NULL, '', '', 'AMAK MAJUMI', 'INAK MIDAH', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('22', 'HERMAN', '5201140305936994', '8', '4', '0', '0', '1', 'SENGGIGI', '1992-05-03', '1', '4', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'HERI IRAWAN', 'DEWI SAULINA', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('23', 'DEWI SAULINA', '5201144808686994', '8', '3', '0', '0', '2', 'KEKERAN', '1967-08-08', '1', '1', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'H. ZAENUDIN', 'INAK NAH', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('24', 'ELOK KHALISA SABRINA', '5201144408886994', '8', '4', '0', '0', '2', 'SENGGIGI', '1987-08-04', '1', '4', '0', '18', '88', '1', '1', NULL, NULL, '', '', 'SERIMAN', 'DEWI SAULINA', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('25', 'I KETUT PAHING', '5201142210806997', '9', '1', '0', '0', '1', 'MATARAM', '1979-10-22', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', '-', '-', '', '2', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('26', 'IDA BAGUS MAS BUJANA', '5201143112707040', '10', '1', '0', '0', '1', 'APIT AIK', '1969-12-31', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'SAHMIN', 'MAOSIN', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('27', 'JOKO PATMOTO', '5201141009146994', '10', '4', '0', '0', '1', 'MANGSIT', '2013-09-10', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'IDA BAGUS MAS BUJANA', 'FITRIANI', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('28', 'KOMANG SALUN', '5201143105121724', '10', '4', '0', '0', '1', 'KAYANGAN', '2002-05-31', '1', '2', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'AMILUDIN', 'FITRIANI', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('29', 'FITRIANI', '5201145107836994', '10', '3', '0', '0', '2', 'KAYANGAN', '1982-07-11', '1', '4', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'REMBUK', 'SITIAH', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('30', 'LALU WAWAN DININGRAT', '5201141206886994', '11', '1', '0', '0', '1', 'MANGSIT', '1987-06-12', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'MAHSUN SUBUH', 'SARDIAH', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('31', 'FITRIANI', '5271016801926995', '11', '3', '0', '0', '2', 'MATARAM', '1991-01-28', '1', '5', '0', '18', '15', '2', '1', NULL, NULL, '', '', 'UMAR', 'RUMINSIH', '', '13', '19', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('32', 'M. FA\'IZ AZAMI', '5201143112897123', '12', '1', '0', '0', '1', 'GEGELANG', '1988-12-31', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'SAREH', 'SUTIMAH', '', '13', '20', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('33', 'HILMIATI', '5201146402906994', '12', '3', '0', '0', '2', 'LOCO', '1989-02-24', '1', '4', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'H. MANSYUR', 'HJ. SA\'ADAH', '', '13', '20', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('34', 'HJ. PARIDAH', '5201144912146994', '12', '4', '0', '0', '2', 'MENINTING', '2013-12-09', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'M. FA\'IZ AZAMI', 'HILMIATI', '', '13', '20', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('35', 'HJ. SAMIRAH', '5201147112767266', '13', '1', '0', '0', '2', 'SENGGIGI', '1975-12-31', '1', '3', '0', '18', '15', '3', '1', NULL, NULL, '', '', 'DAMSYAH', 'MARWIYAH', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('36', 'HUR MINAH', '5201144504131726', '13', '4', '0', '0', '2', 'SENGGIGI', '2003-04-05', '1', '3', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'MARSINI', 'KHODIJAH', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('37', 'HUSNAH', '5201145905936994', '14', '1', '0', '0', '2', 'LOTIM', '1992-05-19', '1', '4', '0', '18', '88', '1', '1', NULL, NULL, '', '', '-', '-', '', '13', '18', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('38', 'IDA AYU OKA SUKERTI', '5201147112587053', '15', '1', '0', '0', '2', 'KERANDANGAN', '1957-12-31', '1', '3', '0', '18', '88', '4', '1', NULL, NULL, '', '', 'ANGGRAH', 'HABIBAH', '', '13', '17', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('39', 'M. JAYADI', '5201143112837098', '16', '1', '0', '0', '1', 'SENGGIGI', '1982-12-31', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'IKHSAN', 'SAIDAH', '', '13', '21', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('40', 'JARIYAH', '5201145406916994', '16', '3', '0', '0', '2', 'SENGGIGI', '1990-06-14', '1', '4', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'SEGEP', 'HURNIWATI', '', '13', '21', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('41', 'LIHAM SATUN', '5201147112116995', '16', '4', '0', '0', '2', 'MATARAM', '2010-12-31', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'M. JAYADI', 'JARIYAH', '', '13', '21', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('42', 'M. NUR SAHID', '5201140507916996', '17', '1', '0', '0', '1', 'KERANDANGAN', '1990-07-05', '1', '4', '0', '18', '88', '2', '1', NULL, NULL, '', '', '-', '-', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('43', 'MADE ASTAWE', '5201142503181724', '17', '4', '0', '0', '1', 'KERANDANGAN', '2008-03-25', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'M. NUR SAHID', 'MAISAH', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('44', 'MAISAH', '5201144605936994', '17', '3', '0', '0', '2', 'KERANDANGAN', '1992-05-06', '4', '1', '0', '18', '88', '2', '1', NULL, NULL, '', '', '-', '-', '', '13', '12', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('45', 'MARSUNIN YOGA PRATAMA', '5201143112677056', '18', '1', '0', '0', '1', 'PEJARAKAN', '1966-12-31', '1', '3', '0', '18', '9', '2', '1', NULL, NULL, '', '', 'MISRAH', 'INAQ MISDAH', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('46', 'MARZUKI', '5201141003966996', '18', '4', '0', '0', '1', 'LOCO', '1995-03-10', '1', '5', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('47', 'MUHAMAD HAMDI', '5201141706986996', '18', '4', '0', '0', '1', 'LOCO', '1997-06-17', '1', '4', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('48', 'MARLIA SAJIDA', '5201147112707088', '18', '3', '0', '0', '2', 'PEJARAKAN', '1969-12-31', '1', '3', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'H. ZAINUDIN', 'INAQ NAH', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('49', 'MIRA FANDA', '5201146704906995', '18', '4', '0', '0', '2', 'LOCO', '1989-04-27', '1', '5', '0', '18', '88', '4', '1', NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('50', 'MUNAAH', '5201146304171724', '18', '4', '0', '0', '2', 'LOCO', '2007-04-23', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', '13', '22', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('51', 'MUHAMAD KABIR', '5201140107917031', '19', '1', '0', '0', '1', 'SENGGIGI', '1985-12-31', '1', '3', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'MUNIAH', 'SALIKIN', '', '13', '23', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('52', 'MUHAMAD SUHAD', '5201141704876995', '20', '1', '0', '0', '1', 'SENGGIGI', '1982-12-10', '1', '5', '0', '18', '15', '2', '1', NULL, NULL, '', '', 'MUNIAH', 'HAJRIAH', '', '13', '24', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('53', 'MUHAMMAD HAIKAL FIRMANSYAH', '5201140308151724', '20', '4', '0', '0', '1', 'LOCO', '2005-08-03', '1', '2', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'MUHAMAD SUHAD', 'KHADIJAH', '', '13', '24', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('54', 'MURNIAH', '5201145904846994', '20', '3', '0', '0', '2', 'SETANGI', '1991-03-04', '1', '4', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'SAHABUDIN', 'SAKMAH', '', '13', '24', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('55', 'MURNIATI SAGITA', '5201144112726996', '21', '1', '0', '0', '2', 'YOGYAKARTA', '1971-12-01', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'UMAR SANTOSA', 'MIRANTI', '', '1', '8', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('56', 'MUHAMMAD RIFAI', '5201143105926995', '22', '1', '0', '0', '1', 'LOCO', '1991-05-31', '4', '4', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'I WAYAN MERTI', 'NI NYOMAN RENI', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('57', 'NADIA ROSDIANA', '5201144305936996', '22', '3', '0', '0', '2', 'MATARAM', '1992-05-03', '4', '4', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'I WAYAN PARTA', 'NI NENGAH SUDINI', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('58', 'NI KOMANG PONIASIH', '5201146206126994', '22', '4', '0', '0', '2', 'MATARAM', '2011-06-22', '4', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'MURNIATI SAGITA', 'NADIA ROSDIANA', '', '13', '16', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('59', 'MUHAMMAD WIRDA MAULANA IBRAHIM', '5201143112417056', '23', '1', '0', '0', '1', 'SENGGIGI', '1940-12-31', '1', '1', '0', '18', '9', '2', '1', NULL, NULL, '', '', 'AMAQ SUN -ALM-', 'INAQ SUN -ALM-', '', '13', '23', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('60', 'NI LUH NITA SARI', '5201147112466997', '23', '3', '0', '0', '2', 'SENTELUK', '1945-12-31', '1', '1', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'AMAQ IRAH', 'INAQ IRAH', '', '13', '23', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('61', 'NI NENGAH AYU KARSINI', '5201145505946996', '24', '1', '0', '0', '2', 'SENGGIGI', '1993-05-15', '1', '2', '0', '18', '15', '1', '1', NULL, NULL, '', '', 'H HAMDANI', 'SANERIAH', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('62', 'MUKSAN', '5201143112957094', '25', '1', '0', '0', '1', 'MANGSIT', '1994-12-31', '1', '4', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'MISDAH', 'RABIAH', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('63', 'NURHAYATI', '5201145509146994', '25', '4', '0', '0', '2', 'MENINTING', '2013-09-15', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'MUKSAN', 'NUR\'AINI', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('64', 'MURSIDIN', '5201142204966994', '26', '4', '0', '0', '1', 'MANGSIT', '1995-04-22', '1', '3', '0', '18', '11', '1', '1', NULL, NULL, '', '', 'RUSNAH (ALM)', 'NURHIDAYAH', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('65', 'NURHIDAYAH', '5201144209766995', '26', '1', '0', '0', '2', 'MANGSIT', '1975-09-02', '1', '3', '0', '18', '2', '4', '1', NULL, NULL, '', '', 'ISMAIL', 'JUMINAH', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('66', 'NURJANAH', '5201145005101724', '26', '4', '0', '0', '2', 'MONTONG', '2000-05-10', '1', '4', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'RUSNAH (ALM)', 'NURHIDAYAH', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('67', 'NURUL AINUN', '5201144108121724', '26', '4', '0', '0', '2', 'MANGSIT', '2002-08-01', '1', '2', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'RUSNAH', 'NURHIDAYAH', '', '13', '26', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('68', 'MUSAHAB', '5201141607936996', '27', '1', '0', '0', '1', 'LOTENG', '1992-07-16', '1', '6', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'LALU ROSIDI', 'BQ. ALISA', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('69', 'NURUL FAIZAH', '5201145003936994', '27', '3', '0', '0', '2', 'SENGGIGI', '1992-03-10', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'SAHUR', 'SARE\'AH', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('70', 'NURUL HIDAYATI', '5201147004136996', '27', '4', '0', '0', '2', 'MATARAM', '2012-04-30', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'MUSAHAB', 'NURUL FAIZAH', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('71', 'NAPIAH', '5201141303906995', '28', '1', '0', '0', '1', 'SENGGIGI', '1989-03-13', '1', '4', '0', '18', '11', '2', '1', NULL, NULL, '', '', 'MUNIAH', 'HAJARIAH', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('72', 'RACHEL YULIANTI', '5201146507966995', '28', '3', '0', '0', '2', 'MELASE', '1995-07-25', '1', '4', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'LUKMAN', 'MUSNAH', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('73', 'RAISHA MAULIDYA', '5201144701156995', '28', '4', '0', '0', '2', 'MENINTING', '2014-01-07', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'NAPIAH', 'RACHEL YULIANTI', '', '13', '25', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('74', 'PATANUL HUSNUL', '5201143112667000', '29', '1', '0', '0', '1', 'JAWA TIMUR', '1965-12-31', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'AHMAD', 'ASIH', '', '13', '17', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('75', 'RATNAWATY', '5201145512796995', '29', '3', '0', '0', '2', 'KERANDANGAN', '1978-12-15', '1', '5', '0', '18', '84', '2', '1', NULL, NULL, '', '', 'JUM', 'REMAH', '', '13', '17', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('76', 'RABITAH', '5201140312896994', '30', '1', '0', '0', '1', 'KERANDANGAN', '1988-12-03', '4', '4', '0', '18', '88', '1', '1', NULL, NULL, '', '', '-', '-', '', '13', '27', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('77', 'ROMI FAISAL', '5201141506856997', '31', '1', '0', '0', '1', 'MANGSIT', '1984-06-15', '1', '3', '0', '18', '15', '2', '1', NULL, NULL, '', '', 'MUNTAHAR', 'MAKNAH', '', '13', '28', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('78', 'RAUDATUL ILMI', '5201145808816994', '31', '3', '0', '0', '2', 'IRENG DAYE', '1980-08-18', '1', '4', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'MUDAHIR', 'RUMISAH', '', '13', '28', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('79', 'ROHANI', '5201144306116994', '31', '4', '0', '0', '2', 'MANGSIT', '2010-06-03', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'ROMI FAISAL', 'RAUDATUL ILMI', '', '13', '28', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('80', 'RUKIAH', '5201145909946994', '32', '1', '0', '0', '2', 'SERANG', '1993-09-19', '1', '4', '0', '18', '88', '3', '1', NULL, NULL, '', '', '-', '-', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('81', 'RUMALI', '5201144507886994', '32', '9', '0', '0', '2', 'JAKARTA', '1987-07-05', '1', '4', '0', '18', '88', '1', '1', NULL, NULL, '', '', '-', '-', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('82', 'RONI', '5201140301836997', '33', '4', '0', '0', '1', 'DENPASAR', '1982-01-03', '4', '5', '0', '18', '15', '1', '1', NULL, NULL, '', '', 'IDA BAGUS PUTU WIADNYA', 'RUSMAYANTI', '', '13', '30', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('83', 'RUSMAYANTI', '5201145003546994', '33', '1', '0', '0', '2', 'DENPASAR', '1953-03-10', '4', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'IDA BAGUS MAS', 'IDA AYU RAKA', '', '13', '30', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('84', 'RUSNI', '5201143112707180', '34', '1', '0', '0', '1', 'KEKERAN', '1969-12-31', '1', '3', '0', '18', '9', '2', '1', NULL, NULL, '', '', '-', '-', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('85', 'SAPIAH', '5201147011726994', '34', '3', '0', '0', '2', 'KEKERAN', '1971-11-30', '1', '3', '0', '18', '2', '2', '1', NULL, NULL, '', '', '-', '-', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('86', 'SAPINAH', '5201145701966994', '34', '4', '0', '0', '2', 'SENGGIGI', '1995-01-17', '1', '5', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'RUSNI', 'SAPIAH', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('87', 'SARRA LANGELAND', '5201145111946996', '34', '4', '0', '0', '2', 'SENGGIGI', '1993-11-11', '1', '5', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'RUSNI', 'SAPIAH', '', '13', '29', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('88', 'SAHRONI', '5201143112617096', '35', '1', '0', '0', '1', 'MEDAS', '1960-12-31', '1', '4', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'SADIYAH', 'INAQ SADIAH', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('89', 'SERIMAN', '5201141012846995', '35', '4', '0', '0', '1', 'SENGGIGI', '1983-12-10', '1', '5', '0', '18', '15', '1', '1', NULL, NULL, '', '', 'SAHRONI', 'NURLAELA', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('90', 'SUNYOTOH', '5201143112817139', '35', '4', '0', '0', '1', 'MEDAS', '1980-12-31', '1', '5', '0', '18', '15', '1', '1', NULL, NULL, '', '', 'SAHRONI', 'NURLAELA', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('91', 'SYARIFUL KALAM', '5201141707776994', '36', '1', '0', '0', '1', 'SENGGIGI', '1976-07-17', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'H. ABDURAHMAN', 'NAFISAH', '', '1', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('92', 'SITI AISYAH', '5201146210776994', '36', '3', '0', '0', '2', 'SUKARAJA', '1976-10-22', '1', '4', '0', '18', '2', '2', '1', NULL, NULL, '', '', 'AMINALLOH', 'RAEHAN', '', '2', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('93', 'SITI PAOZIAH', '5201146312161724', '36', '4', '0', '0', '2', 'SENGGIGI', '2006-12-23', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'SYARIFUL KALAM', 'SITI AISYAH', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('94', 'SUKMA UTAMI', '5201144607996998', '36', '4', '0', '0', '2', 'SENGGIGI', '1998-07-06', '1', '4', '0', '18', '3', '1', '1', NULL, NULL, '', '', 'SYARIFUL KALAM', 'SITI AISYAH', '', '5', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('95', 'WAHID ALIAS H. MAHSUN', '5201141212816996', '37', '1', '0', '0', '1', 'SENGGIGI', '1980-12-12', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'H. ABDURRAHMAN', 'NAFISAH', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('96', 'WAYAN EKA PRAWATA', '5201142003136994', '37', '4', '0', '0', '1', 'GUNUNG SARI', '2012-03-20', '1', '1', '0', '18', '1', '1', '1', NULL, NULL, '', '', 'WAHID ALIAS H. MAHSUN', 'ULFA WIDIAWATI', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);
INSERT INTO tweb_penduduk (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`) VALUES ('97', 'ULFA WIDIAWATI', '5201145203896994', '37', '3', '0', '0', '2', 'JOHAR PELITA', '1988-03-12', '1', '5', '0', '18', '88', '2', '1', NULL, NULL, '', '', 'ZAMHARIN', 'SITIYAH', '', '13', '31', '1', '', '', '1', NULL, NULL, '0', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL);


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
  PRIMARY KEY (`nik`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tweb_penduduk_mandiri (`nik`, `pin`, `last_login`, `tanggal_buat`) VALUES ('3275014601977005', '3645e735f033e8482be0c7993fcba946', '2016-09-14 12:51:53', '2016-09-14 10:10:47');
INSERT INTO tweb_penduduk_mandiri (`nik`, `pin`, `last_login`, `tanggal_buat`) VALUES ('5201140706966997', '3645e735f033e8482be0c7993fcba946', '2016-09-14 12:53:47', '2016-09-14 06:06:32');


#
# TABLE STRUCTURE FOR: tweb_penduduk_map
#

DROP TABLE IF EXISTS tweb_penduduk_map;

CREATE TABLE `tweb_penduduk_map` (
  `id` int(11) NOT NULL,
  `lat` varchar(24) NOT NULL,
  `lng` varchar(24) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO tweb_penduduk_map (`id`, `lat`, `lng`) VALUES ('7', '-7.980601700336039', '110.22508726486967');
INSERT INTO tweb_penduduk_map (`id`, `lat`, `lng`) VALUES ('3', '-7.931639102143979', '110.21375761399077');


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

INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('1', 'BALITA', '0', '5', NULL);
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('2', 'ANAK-ANAK', '6', '17', NULL);
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('3', 'DEWASA', '18', '30', NULL);
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('4', 'TUA', '31', '120', NULL);
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('6', 'Bayi ( < 1 ) Tahun', '0', '1', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('9', 'Balita ( 2 > 4 ) Tahun', '2', '4', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('12', 'Anak-anak ( 5 > 9 ) Tahun', '5', '9', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('13', 'Anak-anak ( 10 > 14 ) Tah', '10', '14', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('14', 'Remaja ( 15 > 19 ) Tahun', '15', '19', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('15', 'Remaja ( 20 > 24 ) Tahun', '20', '24', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('16', 'Dewasa ( 25 > 29 ) Tahun', '25', '29', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('17', 'Dewasa ( 30 > 34 ) Tahun', '30', '34', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('18', 'Dewasa ( 35 > 39 ) Tahun ', '35', '39', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('19', 'Dewasa ( 40 > 44 ) Tahun', '40', '44', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('20', 'Tua ( 45 > 49 ) Tahun', '45', '49', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('21', 'Tua ( 50 > 54 ) Tahun', '50', '54', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('22', 'Tua ( 55 > 59 ) Tahun', '55', '59', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('23', 'Tua ( 60 > 64 ) Tahun', '60', '64', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('24', 'Tua ( 65 > 69 ) Tahun', '65', '69', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('25', 'Tua ( 70 > 74 ) Tahun', '70', '74', '1');
INSERT INTO tweb_penduduk_umur (`id`, `nama`, `dari`, `sampai`, `status`) VALUES ('26', 'Lansia ( > 75 ) Tahun', '75', '130', '1');


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
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

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
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('18', 'Keterangan Kelahiran', 'surat_ket_kelahiran', 'S-17', 'f-kelahiran.php', '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('20', 'Permohonan Akta Lahir', 'surat_permohonan_akta', 'S-18', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('21', 'Pernyataan Belum Memiliki Akta Lahir', 'surat_pernyataan_akta', 'S-19', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('22', 'Permohonan Duplikat Kelahiran', 'surat_permohonan_duplikat_kelahiran', 'S-20', NULL, '0', '0', '1');
INSERT INTO tweb_surat_format (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES ('24', 'Keterangan Kematian', 'surat_ket_kematian', 'S-21', NULL, '0', '0', '1');
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
  `password` varchar(40) NOT NULL,
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

INSERT INTO user (`id`, `username`, `password`, `id_grup`, `email`, `last_login`, `active`, `nama`, `company`, `phone`, `foto`, `session`) VALUES ('1', 'admin', 'edc64352387aa108dac115ec1493d5d4', '1', 'admin@combine.or.id', '2016-09-11 02:55:19', '1', 'Administrator', 'ADMIN', '321', 'favicon.png', 'a8d4080245664ed2049c1b2ded7cac30');
INSERT INTO user (`id`, `username`, `password`, `id_grup`, `email`, `last_login`, `active`, `nama`, `company`, `phone`, `foto`, `session`) VALUES ('56', 'redaksi', 'd8578edf8458ce06fbc5bb76a58c5ca4', '3', '', '2014-10-24 20:15:38', '1', 'Redaksi', NULL, '', '', '39b3cc1ca3f8b095a171b19b1f307358');
INSERT INTO user (`id`, `username`, `password`, `id_grup`, `email`, `last_login`, `active`, `nama`, `company`, `phone`, `foto`, `session`) VALUES ('57', 'operator', 'd8578edf8458ce06fbc5bb76a58c5ca4', '2', '', '2014-10-24 20:17:42', '1', 'Operator Desa', NULL, '', '', '9304194147916440da8be6d8f26f62f0');
INSERT INTO user (`id`, `username`, `password`, `id_grup`, `email`, `last_login`, `active`, `nama`, `company`, `phone`, `foto`, `session`) VALUES ('58', 'Master Admin', 'ce632afb06b2ef65397b1aecf7bbd39c', '1', 'iariadi@gmail.com', '0000-00-00 00:00:00', '0', 'Admin', NULL, '0813299237471', '', 'de750f7c7aa3412540dcfb33a8218ccf');


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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('1', '<p><iframe src=\"https://www.google.co.id/maps?f=q&source=s_q&hl=en&geocode=&q=Logandu,+Karanggayam&aq=0&oq=logan&sll=-2.550221,118.015568&sspn=52.267573,80.332031&t=h&ie=UTF8&hq=&hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&ll=-7.55854,109.634173&spn=0.052497,0.078449&z=14&output=embed\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" width=\"100%\"></iframe></p> ', '1', 'Peta Desa', '3', NULL, '', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('2', 'layanan_mandiri.php', '1', 'Layanan Mandiri', '1', '1', 'mandiri', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('3', 'agenda.php', '1', 'Agenda', '1', '2', 'web/index/1000', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('4', 'galeri.php', '1', 'Galeri', '1', '3', 'gallery', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('5', 'statistik.php', '1', 'Statistik', '1', '4', '', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('6', 'komentar.php', '1', 'Komentar', '1', '5', 'komentar', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('7', 'media_sosial.php', '1', 'Media Sosial', '1', '6', 'sosmed', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('8', 'peta_lokasi_kantor.php', '1', 'Peta Lokasi Kantor', '1', '7', 'hom_desa', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('9', 'statistik_pengunjung.php', '1', 'Statistik Pengunjung', '1', '8', '', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('10', 'arsip_artikel.php', '1', 'Arsip Artikel', '1', '9', '', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('11', 'aparatur_desa.php', '1', 'Aparatur Desa', '1', '1', 'pengurus', '');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('12', 'sinergi_program.php', '1', 'Sinergi Program', '1', '1', 'web_widget/admin/sinergi_program', '[]');
INSERT INTO widget (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES ('13', 'menu_kategori.php', '1', 'Menu Kategori', '1', '1', '', '');


DROP VIEW IF EXISTS data_surat;
#
# TABLE STRUCTURE FOR: data_surat
#

DROP TABLE IF EXISTS data_surat;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `data_surat` AS select `u`.`id` AS `id`,`u`.`nama` AS `nama`,`x`.`nama` AS `sex`,`u`.`tempatlahir` AS `tempatlahir`,`u`.`tanggallahir` AS `tanggallahir`,(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) from `tweb_penduduk` where (`tweb_penduduk`.`id` = `u`.`id`)) AS `umur`,`w`.`nama` AS `status_kawin`,`f`.`nama` AS `warganegara`,`a`.`nama` AS `agama`,`d`.`nama` AS `pendidikan`,`j`.`nama` AS `pekerjaan`,`u`.`nik` AS `nik`,`c`.`rt` AS `rt`,`c`.`rw` AS `rw`,`c`.`dusun` AS `dusun`,`k`.`no_kk` AS `no_kk`,(select `tweb_penduduk`.`nama` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `k`.`nik_kepala`)) AS `kepala_kk` from ((((((((`tweb_penduduk` `u` left join `tweb_penduduk_sex` `x` on((`u`.`sex` = `x`.`id`))) left join `tweb_penduduk_kawin` `w` on((`u`.`status_kawin` = `w`.`id`))) left join `tweb_penduduk_agama` `a` on((`u`.`agama_id` = `a`.`id`))) left join `tweb_penduduk_pendidikan_kk` `d` on((`u`.`pendidikan_kk_id` = `d`.`id`))) left join `tweb_penduduk_pekerjaan` `j` on((`u`.`pekerjaan_id` = `j`.`id`))) left join `tweb_wil_clusterdesa` `c` on((`u`.`id_cluster` = `c`.`id`))) left join `tweb_keluarga` `k` on((`u`.`id_kk` = `k`.`id`))) left join `tweb_penduduk_warganegara` `f` on((`u`.`warganegara_id` = `f`.`id`)));





