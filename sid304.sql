-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2016 at 11:10 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sid304`
--

-- --------------------------------------------------------

--
-- Table structure for table `analisis_indikator`
--

CREATE TABLE IF NOT EXISTS `analisis_indikator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_master` int(11) NOT NULL,
  `nomor` decimal(3,0) NOT NULL,
  `pertanyaan` varchar(400) NOT NULL,
  `id_tipe` tinyint(4) NOT NULL DEFAULT '1',
  `bobot` tinyint(4) NOT NULL DEFAULT '0',
  `act_analisis` tinyint(1) NOT NULL DEFAULT '2',
  `id_kategori` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_master` (`id_master`,`id_tipe`),
  KEY `id_tipe` (`id_tipe`),
  KEY `id_kategori` (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_kategori_indikator`
--

CREATE TABLE IF NOT EXISTS `analisis_kategori_indikator` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `id_master` tinyint(4) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_master` (`id_master`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_klasifikasi`
--

CREATE TABLE IF NOT EXISTS `analisis_klasifikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_master` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `minval` double(5,2) NOT NULL,
  `maxval` double(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_master` (`id_master`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_master`
--

CREATE TABLE IF NOT EXISTS `analisis_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(40) NOT NULL,
  `subjek_tipe` tinyint(4) NOT NULL,
  `lock` tinyint(1) NOT NULL DEFAULT '1',
  `deskripsi` text NOT NULL,
  `kode_analiusis` varchar(5) NOT NULL DEFAULT '00000',
  `id_kelompok` int(11) NOT NULL,
  `pembagi` varchar(10) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_parameter`
--

CREATE TABLE IF NOT EXISTS `analisis_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_indikator` int(11) NOT NULL,
  `jawaban` varchar(200) NOT NULL,
  `nilai` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_indikator` (`id_indikator`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_partisipasi`
--

CREATE TABLE IF NOT EXISTS `analisis_partisipasi` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_periode`
--

CREATE TABLE IF NOT EXISTS `analisis_periode` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_ref_state`
--

CREATE TABLE IF NOT EXISTS `analisis_ref_state` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `analisis_ref_state`
--

INSERT INTO `analisis_ref_state` (`id`, `nama`) VALUES
(1, 'Belum Entri / Pendataan'),
(2, 'Sedang Dalam Pendataan'),
(3, 'Selesai Entri / Pendataan');

-- --------------------------------------------------------

--
-- Table structure for table `analisis_ref_subjek`
--

CREATE TABLE IF NOT EXISTS `analisis_ref_subjek` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `subjek` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `analisis_ref_subjek`
--

INSERT INTO `analisis_ref_subjek` (`id`, `subjek`) VALUES
(1, 'Penduduk'),
(2, 'Keluarga / KK'),
(3, 'Rumah Tangga'),
(4, 'Kelompok');

-- --------------------------------------------------------

--
-- Table structure for table `analisis_respon`
--

CREATE TABLE IF NOT EXISTS `analisis_respon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_indikator` int(11) NOT NULL,
  `id_parameter` int(11) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_parameter` (`id_parameter`,`id_subjek`),
  KEY `id_periode` (`id_periode`),
  KEY `id_indikator` (`id_indikator`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_respon_hasil`
--

CREATE TABLE IF NOT EXISTS `analisis_respon_hasil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_master` tinyint(4) NOT NULL,
  `id_periode` tinyint(4) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `akumulasi` double(8,3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_tipe_indikator`
--

CREATE TABLE IF NOT EXISTS `analisis_tipe_indikator` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `tipe` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `analisis_tipe_indikator`
--

INSERT INTO `analisis_tipe_indikator` (`id`, `tipe`) VALUES
(1, 'Pilihan (Tunggal)'),
(2, 'Pilihan (Multivalue)'),
(3, 'Isian Angka'),
(4, 'Isian Tulisan');

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE IF NOT EXISTS `area` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `path` text NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  `ref_polygon` int(9) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `id_cluster` int(11) NOT NULL,
  `desk` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE IF NOT EXISTS `artikel` (
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=86 ;

--
-- Dumping data for table `artikel`
--

INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`) VALUES
(7, '', '<p><strong>Awal mula SID</strong><br /> "Awalnya ada keinginan dari pemerintah Desa Balerante yang berharap pelayanan pemerintah desa bisa seperti pengunjung rumah sakit yang ingin mencari data pasien rawat inap, tinggal ketik nama di komputer, maka data tersebut akan keluar"<br /> (Mart Widarto, pengelola Program Lumbung Komunitas)<br /> Program ini mulai dibuat dari awal 2006: <br /> 1. (2006) komunitas melakukan komunikasi dan diskusi lepas tentang sebuah sistem yang bisa digunakan untuk menyimpan data.<br /> 2. (2008) Rangkaian FDG dengan pemerintah desa membahas tentang tata kelola pendokumentasian di desa<br /> 3. (2009) Ujicoba SID yang sudah dikembangkan di balerante<br /> 4. (2009-2010) Membangun SID (aplikasi) dibeberapa desa yang lain: terong (bantul), Nglegi (Gunungkidul) <br /> 5. (2011) Kandangan (Temanggung) Gilangharjo (bantul) Girikarto (gunungkidul) Talun (klaten) Pager Gunung (magelang) <br /> 6. hingga saat ini 2013 sudah banyak desa pengguna SID.<br /> <br /> <strong>SID sebagai tanggapan atas kebutuhan:</strong><br /> Kalau dulu untuk mencari data penduduk menurut kelompok umur saja kesulitan karena tidak mempunyai databasenya. Dengan adanya SID menjadi lebih mudah.<br /> (Nuryanto, Kabag Pelayanan Pemdes Terong)<br /> <br /> Membangun sebuah sistem bukan hanya membuatkan software dan meninggalkan begitu saja, namun ada upaya untuk memadukan sistem dengan kebutuhan yang ada pada desa. sehingga software dapat memenuhi kebutuhan yang telah ada bukan memaksakan desa untuk mengikuti dan berpindah sistem. inilah yang melatari combine melaksanakan alur pengaplikasian software.<br /> 1. Bentuk tim kerja bersama pemerintah desa<br /> 2. Diskusikan basis data apa saja yang diperlukan untuk warga<br /> 3. Himpun data kependudukan warga dari Kartu Keluarga (KK)<br /> 4. Daftarkan proyek SID dan dapatkan aplikasi softwarenya di http://abcd.lumbungkomunitas.net<br /> 5. Install aplikasi software SID di komputer desa<br /> 6. Entry data penduduk ke SID<br /> 7. Basis data kependudukan sudah bisa dimanfaatkan<br /> 8. Diskusikan rencana pengembangan SID sesuai kebutuhan desa<br /> 9. Sebarluaskan informasi desa melalui beragam media untuk warga<br /> <br /> Pemberdayaan data desa yang dibangun diharapkan dapat menjunjung kesejahteraan masyarakat desa, data-data tersebut dapat diperuntukkan untuk riset lebih lanjut tentang kemiskinan, tanggap bencana, sumberdaya desa yang bisa diekspose keluar dan dengan menghubungkan dari desa ke desa dapat mencontohkan banyak hal dalam keberhasilan pemberdayaannya.<br /> (sumber: Buku Sistem Informasi Desa) <br /> <strong><br /></strong></p>', 1, '2013-03-31 12:31:04', 999, 1, 'Awal mula SID', 0, '', '', '', '', ''),
(8, '', '<p style="text-align: justify;"><span style="color: #ff0000;"><strong>Contoh (Sila edit halaman ini dan sesuaikan dengan visi dan misi desa Anda!)</strong></span></p>\r\n<h2 style="text-align: justify;">Visi Pemerintah Desa Kandangan</h2>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<div style="text-align: justify;">\r\n<p>Agar penyelenggaraan pemerintahan Desa Kandangan dapat terarah dan berkelanjutan maka diperlukan adanya Visi Desa. Visi Desa Kandangan adalah &rdquo;DESAKU BERSATU,MAJU, DAN SEJAHTERA&rdquo;</p>\r\n</div>\r\n<div style="text-align: justify;">&nbsp;</div>\r\n<div style="text-align: justify;">Visi tersebut mengandung filosofi dasar, yaitu :</div>\r\n<div style="text-align: justify;"><ol>\r\n<li>Untuk Desa Kandangan yang lebih baik maka perlu adanya tekad semua komponen baik Pemerintah Desa maupun masyarakat untuk &rdquo;BERSATU&rdquo;. Hal ini mengandung makna menyatukan semua potensi &nbsp;sumber daya manusia untuk berupaya mengelola Desa Malebo secara terarah didasarkan pada program yang mantap, pelaksanaan yang tepat, serta pengawasan yang ketat sehingga &rdquo;Kemajuan&rdquo; dapat dicapai.</li>\r\n<li>Masyarakat yang &rdquo;MAJU&rdquo; mengandung makna terwujudnya kondisi masyarakat yang berkembang dan berorientasi pada upaya memajukan desa dengan dilandasi sikap disiplin, bekerja keras, dan gemar membaca/meningkatkan kapasitas dan kapabilitas diri. Kondisi ini akan mengantar pada terwujudnya masyarakat yang &rdquo;SEJAHTERA&rdquo;.</li>\r\n<li>"SEJAHTERA&rdquo; mengandung arti tercukupinya kebutuhan pokok material dan spiritual bagi masyarakat, yang ditandai dengan meningkatnya kehidupan perekonomian, derajat kesehatan dan kualitas sumberdaya manusia bagi seluruh masyarakat yang di dukung oleh kepastian hukum dan hak azasi manusia.&nbsp;</li>\r\n</ol></div>\r\n<div style="text-align: justify;">&nbsp;</div>\r\n<div style="text-align: justify;">&nbsp;</div>\r\n<h2 style="text-align: justify;">Misi Pemerintah Desa Kandangan</h2>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<div style="text-align: justify;">\r\n<p>Misi adalah rumusan umum mengenai upaya-upaya yang akan dilaksanakan untuk mewujudkan visi. Misi Desa Kandangan jangka menengah tahun 2009-2013 adalah:</p>\r\n</div>\r\n<div style="text-align: justify;"><ol>\r\n<li>Meningkatkan kualitas iman dan taqwa melalui pembinaan dan pengembangan kehidupan beragama dan kerukunan umat beragama.</li>\r\n<li>Mewujudkan pemerintahan desa yang baik dalam rangka optimalisasi pelayanan masyarakat</li>\r\n<li>Meningkatkan pemberdayaan lembaga kemasyarakatan desa</li>\r\n<li>Meningkatkan sarana dan prasarana pelayanan dasar dan infrastruktur perekonomian&nbsp;</li>\r\n<li>Meningkatkan pemberdayaan lembaga ekonomi desa dan seluruh potensi ekonomi kerakyatan</li>\r\n<li>Meningkatkan kualitas dan pelestarian lingkungan hidup</li>\r\n<li>Meningkatkan kemajuan dan kemandirian rumah tangga miskin</li>\r\n<li>Mempertahankan nilai-nilai luhur dan adat-istiadat budaya desa</li>\r\n</ol></div>\r\n<div style="text-align: justify;">&nbsp;</div>\r\n<div style="text-align: justify;">&nbsp;</div>\r\n<div style="text-align: justify;">Dalam mewujudkan visi melalui pelaksanaan misi yang telah ditetapkan maka perlu adanya kerangka yang jelas mengenai tujuan yang akan dicapai. Tujuan &nbsp;masing-masing misi adalah sebagai berikut:</div>\r\n<div><ol>\r\n<li style="text-align: justify;"><span style="text-decoration: underline;">Misi Pertama</span>: &nbsp;meningkatkan kualitas iman dan taqwa melalui pembinaan dan pengembangan kehidupan beragama dan kerukunan umat beragama mempunyai tujuan untuk meningkatkan kualitas kehidupan beragama yang didasarkan pada pemahaman dan pengamalan ajaran agama sesuai dengan keyakinannya masing-masing dalam nuansa kehidupan yang sejuk dan penuh toleransi.&nbsp;</li>\r\n<li style="text-align: justify;"><span style="text-decoration: underline;">Misi Kedua</span>: Mewujudkan pemerintahan desa yang baik dalam rangka optimalisasi pelayanan masyarakat mempunyai tujuan untuk meningkatkan kualitas aparat pemerintah desa dan badan permusyawaratan desa sehingga mampu memahami tugas, pokok, dan fungsinya, serta menjalin kerjasama yang baik dalam memberikan pelayanan kepada masyarakat secara cepat, tepat, dan transparan.&nbsp;</li>\r\n<li style="text-align: justify;"><span style="text-decoration: underline;">Misi Ketiga</span>: &nbsp;mempunyai tujuan untuk meningkatkan kualitas anggota dan pengurus lembaga kemasyarakatan, mewujudkan harmonisasi antar lembaga kemasyarakatan desa, maupun dengan unsur pemerintahan desa dalam perencanaan dan pelaksanaan pembangunan desa.</li>\r\n<li style="text-align: justify;"><span style="text-decoration: underline;">Misi Keempat</span>: &nbsp;meningkatkan sarana dan prasarana pelayanan dasar dan infrastruktur perekonomian mempunyai tujuan untuk meningkatkan kulaitas sumber daya maniusia, derajat kesehatan masyarakat yang berkualitas, kelancaran perekonomian desa, dan meningkatkan kesejahteraan masyarakat.</li>\r\n<li style="text-align: justify;"><span style="text-decoration: underline;">Misi Kelima</span>: &nbsp;meningkatkan pemberdayaan lembaga ekonomi desa &nbsp;dan seluruh potensi ekonomi kerakyatan mempunyai tujuan untuk menggerakkan seluruh potensi ekonomi kerakyatan dan lembaga ekonomi desa sehingga mampu meningkatkan kesejahteraan masyarakat, mengurangi pengangguran, dan meningkatkan pendapatan asli desa</li>\r\n<li style="text-align: justify;"><span style="text-decoration: underline;">Misi Keenam</span>: Meningkatkan kualitas dan pelestarian lingkungan hidup mempunyai tujuan untuk melestarikan lingkungan hidup dan memanfaatkannya sebagaimana mestinya serta mengurangi adanya pencemaran lingkungan sehingga menciptakan Desa Kandangan yang sejuk dan indah.</li>\r\n<li style="text-align: justify;"><span style="text-decoration: underline;">Misi Ketujuh</span>: Meningkatkan kemajuan dan kemandirian rumah tangga miskin mempunyai tujuan untuk &nbsp;memotifasi, menggerakkan &nbsp;dan membina masyarakat miskin supaya lebih maju dan mampu meningkatkan kesejahteraan dan ekonomi dengan berwirausaha secara mandiri.&nbsp;</li>\r\n<li style="text-align: justify;"><span style="text-decoration: underline;">Misi Kedelapan</span>: Mempertahankan nilai-nilai luhur dan adat-istiadat budaya desa mempunyai tujuan untuk mempertahankan dan melestarikan nilai nilai luhur, adat istiadat serta budaya yang sudah berjalan di Desa.</li>\r\n</ol></div>', 1, '2013-04-01 07:14:06', 999, 1, 'Visi Misi', 0, '', '', '', '', ''),
(32, '', '<div class="contentText">\r\n<div align="justify">Bagian ini berisi informasi dasar mengenai desa kami. Sila klik pada tautan berikut untuk mendapatkan informasi yang lebih rinci.</div>\r\n<div align="justify">&nbsp;</div>\r\n<div align="justify"><ol>\r\n<li>Sejarah Desa</li>\r\n<li>Profil Wilayah Desa</li>\r\n<li>Profil Masyarakat Desa</li>\r\n<li>Profil Potensi Desa</li>\r\n</ol></div>\r\n</div>', 1, '2013-07-29 09:46:44', 999, 1, 'Profil Desa', 0, '', '', '', '', ''),
(33, '', '<p>Wilayah desa berisi tentang penjelasan dan deskripsi letak wilayah desa. contohnya sebagai berikut :<br />Batas-batas :<br />Utara&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kelurahan a<br />Timur&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Desa b<br />Selatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Desa c<br />Barat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kelurahan d dan Desa e<br />Luas Wilayah Desa Penglatan&nbsp;&nbsp; : 186,193 Ha<br />Letak Dan Batas Desa x<br />Desa Penglatan terletak pada posisi 115. 7.20 LS 8. 7.10 BT, dengan ketinggian kurang lebih 250 M diatas permukaan laut.</p>\n<p>Peta Desa:</p>\n<p><iframe src="https://www.google.co.id/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Logandu,+Karanggayam&amp;aq=0&amp;oq=logandu&amp;sll=-2.550221,118.015568&amp;sspn=52.267573,80.332031&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&amp;z=14&amp;ll=-7.55854,109.634173&amp;output=embed" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" width="600" height="450"></iframe></p>', 1, '2013-07-29 10:05:37', 999, 1, 'Wilayah Desa', 0, '', '', '', '', ''),
(34, '', '<p style="text-align: justify;"><span style="color: #ff0000;"><strong>Contoh (Sila edit halaman ini sesuai dengan deskripsi desa ini)!</strong></span></p>\r\n<p style="text-align: justify;">Berdasarkan data desa pada bulan Februari 2010, jumlah penduduk Desa Terong sebanyak 6484 orang. Jumlah Kepala Keluarga (KK) sebanyak 1605 KK.</p>\r\n<p style="text-align: justify;">Jumlah penduduk Desa Terong usia produktif pada tahun 2009 adalah 4746 orang. Jumlah angkatan kerja tersebut jika dilihat berdasarkan tingkat pendidikannya adalah sebagai berikut:</p>\r\n<table style="width: 100%;" border="1" cellspacing="0" cellpadding="4">\r\n<tbody>\r\n<tr valign="top">\r\n<td style="width: 8%;">\r\n<p style="text-align: center;"><strong>No.</strong></p>\r\n</td>\r\n<td style="width: 42%;">\r\n<p style="text-align: center;"><strong>Angkatan Kerja</strong></p>\r\n</td>\r\n<td style="width: 17%;">\r\n<p style="text-align: center;"><strong>L</strong></p>\r\n</td>\r\n<td style="width: 18%;">\r\n<p style="text-align: center;"><strong>P</strong></p>\r\n</td>\r\n<td style="width: 15%;">\r\n<p style="text-align: center;"><strong>Jumlah</strong></p>\r\n</td>\r\n</tr>\r\n<tr valign="top">\r\n<td style="width: 8%;">\r\n<p style="text-align: center;">1</p>\r\n</td>\r\n<td style="width: 42%;">Tidak Tamat SD</td>\r\n<td style="width: 17%;">\r\n<p style="text-align: center;">59</p>\r\n</td>\r\n<td style="width: 18%;">\r\n<p style="text-align: center;">56</p>\r\n</td>\r\n<td style="width: 15%;">\r\n<p style="text-align: center;">115</p>\r\n</td>\r\n</tr>\r\n<tr valign="top">\r\n<td style="width: 8%;">\r\n<p style="text-align: center;">2</p>\r\n</td>\r\n<td style="width: 42%;">SD</td>\r\n<td style="width: 17%;">\r\n<p style="text-align: center;">880</p>\r\n</td>\r\n<td style="width: 18%;">\r\n<p style="text-align: center;">792</p>\r\n</td>\r\n<td style="width: 15%;">\r\n<p style="text-align: center;">1672</p>\r\n</td>\r\n</tr>\r\n<tr valign="top">\r\n<td style="width: 8%;">\r\n<p style="text-align: center;">3</p>\r\n</td>\r\n<td style="width: 42%;">SLTP</td>\r\n<td style="width: 17%;">\r\n<p style="text-align: center;">813</p>\r\n</td>\r\n<td style="width: 18%;">\r\n<p style="text-align: center;">683</p>\r\n</td>\r\n<td style="width: 15%;">\r\n<p style="text-align: center;">1496</p>\r\n</td>\r\n</tr>\r\n<tr valign="top">\r\n<td style="width: 8%;">\r\n<p style="text-align: center;">4</p>\r\n</td>\r\n<td style="width: 42%;">SLTA</td>\r\n<td style="width: 17%;">\r\n<p style="text-align: center;">725</p>\r\n</td>\r\n<td style="width: 18%;">\r\n<p style="text-align: center;">673</p>\r\n</td>\r\n<td style="width: 15%;">\r\n<p style="text-align: center;">1398</p>\r\n</td>\r\n</tr>\r\n<tr valign="top">\r\n<td style="width: 8%;">\r\n<p style="text-align: center;">5</p>\r\n</td>\r\n<td style="width: 42%;">Akademi</td>\r\n<td style="width: 17%;">\r\n<p style="text-align: center;">13</p>\r\n</td>\r\n<td style="width: 18%;">\r\n<p style="text-align: center;">11</p>\r\n</td>\r\n<td style="width: 15%;">\r\n<p style="text-align: center;">24</p>\r\n</td>\r\n</tr>\r\n<tr valign="top">\r\n<td style="width: 8%;">\r\n<p style="text-align: center;">6</p>\r\n</td>\r\n<td style="width: 42%;">Perguruan Tinggi</td>\r\n<td style="width: 17%;">\r\n<p style="text-align: center;">23</p>\r\n</td>\r\n<td style="width: 18%;">\r\n<p style="text-align: center;">18</p>\r\n</td>\r\n<td style="width: 15%;">\r\n<p style="text-align: center;">41</p>\r\n</td>\r\n</tr>\r\n<tr valign="top">\r\n<td style="width: 50%;" colspan="2">\r\n<p style="text-align: center;">Jumlah Total</p>\r\n</td>\r\n<td style="width: 17%;">\r\n<p style="text-align: center;">2513</p>\r\n</td>\r\n<td style="width: 18%;">\r\n<p style="text-align: center;">2233</p>\r\n</td>\r\n<td style="width: 15%;">\r\n<p style="text-align: center;">4746</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p style="text-align: justify;"><strong>Profil sosial masyarakat</strong></p>\r\n<p style="text-align: justify;">Dalam aktivitas keseharian, masyarakat Desa Terong sangat taat dalam menjalankan ibadah keagamaan. Setiap Rukung Tetangga (RT) dan pedukuhan memiliki kelompok-kelompok pengajian. Pada peringatan hari besar Islam, penduduk Desa Terong kerap menggelar acara peringatan dan karnaval budaya dengan tema yang disesuaikan dengan hari besar keagamaan. Sebagian besar warga Desa Terong terafiliasi pada organisasi kemasyarakat Islam Muhammadiyah.</p>\r\n<p style="text-align: justify;">Gelaran perayaan lain selalu dilakukan dalam rangka memperingati Hari Kemerdekaan Republik Indonesia. Setiap pedukuhan akan turut serta dan semangat menampilkan ciri khasnya dalam acara peringatan dan karnaval budaya.</p>\r\n<p style="text-align: justify;">Kelompok pemuda di Desa Terong yang tergabung dalam kelompok pegiat Karang Taruna menjadi aktor utama dalam banyak kegiatan desa. Kelompok ini aktif menggelar program kegiatan untuk isu demokrasi kepada warga, penguatan ekonomi produktif, pelatihan penanggulangan bencana, dan kampanye Gerakan Remaja Sayang Ibu (GEMAS).</p>\r\n<p style="text-align: justify;">Sejumlah penduduk Desa Terong bekerja merantau di daerah di luar Yogyakarta. Namun, ikatan sosial mereka terhadap tanah kelahiran tetap tinggi. Penduduk asli Desa Terong yang tinggal di Jakarta dan sekitarnya misalnya, mereka membentuk paguyuban untuk memelihara silaturahmi antar sesama warga perantauan. Setiap bulan diadakan kegiatan arisan keliling secara bergilir di setiap tempat anggotanya. Setiap dua tahun sekali diadakan pula kegiatan mudik bersama ke kampung halaman di Desa Terong</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p style="text-align: justify;"><strong>Profil politik masyarakat</strong></p>\r\n<p style="text-align: justify;">Warga Desa Terong dikenal sebagai kelompok masyarakat yang paling aktif dan memiliki potensi tertinggi untuk berpartisipasi dalam pemberian suara untuk Pemilihan Umum dan Pemilihan Kepala Daerah Langsung. Tingkat partisipasi warga di desa ini terbanyak jika dibandingkan dengan desa lain di Kecamatan Dlingo, Bantul.</p>\r\n<p style="text-align: justify;">Warga Desa Terong sangat aktif dalam mengawal proses penyusunan Rancangan Undang-Undang (RUU) Keistimewaan Yogyakarta. Banyak warga Desa Terong yang tergabung dalam Gerakan Rakyat Yogyakarta (GRY) dan aktif dalam beragam kegiatan serta demontrasi mendukung penetapan keistimewaan Yogyakarta. Kepala Desa Terong Sudirman Alfian merupakan Ketua Paguyuban Lurah dan Pamong Desa Ing Sedya Memetri Asrining Yogyakarta (ISMAYA) se Daerah Istimewa Yogyakarta (DIY). Beliau ditunjuk pula sebagai anggota tim perumus RUU Keistimewaan Yogyakarta bersi masyarakat Yogyakarta. Salah satu hal yang diperjuangkan dalam RUU tersebut adalah tidak adanya pelaksanaan pemilihan kepala daerah langsung dalam pemilihan Gubernur DIY; dengan mempertahankan konsep dwi tunggal Sri Sultan Hamengku Buwono dan Paku Alam sebagai Gubernur dan Wakil Bubernur DIY.</p>\r\n<p style="text-align: justify;">Permasalahan mendasar yang ada di Desa Terong adalah tidak imbangnya jumlah pencari kerja dengan jumlah lapangan kerja yang tersedia. Sekalipun jumlah pengangguran di Desa Terong pada Tahun 2009 hanya orang tetapi kebanyakan mereka bekerja di luar Desa. Jadi, perlu gerakan kembali ke Desa serta menarik sumber-sumber ekonomi ke desa agar pencari kerja tidak banyak tersedot ke luar Desa.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p style="text-align: justify;">Sumber:<br />Laporan Pertanggung Jawaban Lurah Desa Terong, Kecamatan Dlingo, Kabupaten Bantul tahun 2009.</p>', 1, '2013-07-29 10:13:36', 999, 1, 'Profil Masyarakat Desa', 0, '', '', '', '', ''),
(35, '', '<p><span style="color: #ff0000;"><strong>Contoh (Sila edit halaman ini dan sesuaikan dengan deskripsi untuk desa ini)!</strong></span><br /><br />Susunan Organisasi Pemerintah Desa Terong<br /><br />Kepala Desa: WELASIMAN (2012 - 2018)<br />Sekretaris Desa / Carik: PONIRAN<br />Kepala Bagian Pemerintahan: KEMIJO<br />Kepala Bagian Pembangunan: SUGIYARTO<br />Kepala Bagian Kesra: LANJAR NURHADI<br />Kepala Bagian Keuangan: NGATINI,Ny<br />Kepala Bagian Pelayanan: NURYANTO.S.Pd<br />Staf: JAKA SUKISTYO<br />Staf: SUWOTO<br />Kepala Urusan TU BPD: AGUS KAHARUDDIN<br /><br />Dukuh Kebokuning: TULUS<br />Dukuh Saradan: SUPARDI<br />Dukuh Pancuran: SUGIYADI<br />Dukuh Rejosari: SUKAMDAM<br />Dukuh Terong II: SUDARSONO<br />Dukuh Terong I: RIBUT RIYANTO<br />Dukuh Pencitrejo: SUDARI<br />Dukuh Sendangsari: SUYADI<br />Dukuh Ngenep: SUTAYA<br /><br />Pemerintah Desa Terong memiliki kantor yang memadaiyang terletak di pinggir jalan raya Patuk - Dlingo Km 6.5 di Dusun Terong II, Terong, Dlingo, Bantul, D.I. Yogyakarta. Kompleks kantor desa ini terdiri dari ruang kepala desa, ruang kepala urusan, ruang rapat, gudang, dan pendapa beratap joglo, serta sebuah ruang studio radio siaran komunitas MSP FM. Warga Desa Terong kerap melakukan beragam pertemuan dan kegiatan desa di pendapa ini. Pelayanan di kantor desa ini dilakukan pada setiap hari Senin - Sabtu pukul 08.00 - 13.00.<br /><br /><br />Sumber: Laporan Lomba Desa 2010</p>', 1, '2013-07-29 10:18:20', 999, 1, 'Pemerintah Desa', 0, '', '', '', '', ''),
(36, '', '<p>Kontak kami berisi cara menghubungi desa, seperti contoh berikut :</p>\r\n<p>Alamat : Jl desa no 01</p>\r\n<p>No Telepon : 081xxxxxxxxx</p>\r\n<p>Email : xx@desa.com</p>', 1, '2013-07-29 10:28:31', 999, 1, 'Kontak Kami', 0, '', '', '', '', ''),
(37, '', '<p><span style="color: #ff0000;"><strong>Contoh (Sila edit halaman ini dan sesuaikan dengan deskripsi untuk desa ini)!</strong></span><br /><br />Susunan Organisasi Badan Permusyawaratan Desa:</p>\r\n<p>Ketua</p>\r\n<p>Sekretaris</p>\r\n<p>Anggota</p>', 1, '2013-07-29 10:33:33', 999, 1, 'Badan Permusyawaratan Desa', 0, '', '', '', '', ''),
(38, '', '<p>Berisi data lembaga yang ada di desa beserta deskripsi dan susunan pengurusnya</p>', 1, '2013-07-29 10:38:33', 999, 1, 'Lembaga Kemasyarakatan', 0, '', '', '', '', ''),
(40, '', '<p>Berisi tentang peraturan yang ada di Desa</p>', 1, '2013-07-29 11:06:50', 1001, 1, 'Peraturan', 0, '', '', '', '', ''),
(41, '', '<p>Agenda Bulan Agustus :</p>\r\n<p>01/08/2013 : Rapat rutin</p>\r\n<p>04/08/2013 : Pertemuan Pengurus</p>\r\n<p>05/08/2013 : Seminar</p>\r\n<p>&nbsp;</p>', 1, '2013-07-30 06:08:52', 1000, 1, 'Agenda', 0, '', '', '', '', ''),
(42, '', '<p>Daftar Undang Undang Desa</p>\n<p><a href="../../../../../../assets/front/dokumen/Profil_SSN_SMP1Kepil.pdf">1. UU No desa</a></p>\n<p>berisi asf basdaf.</p>\n<p>&nbsp;</p>\n<p><a href="kebumenkab.go.id/uu.pdf">2.UU Perdangangan</a></p>', 1, '2014-04-20 10:21:56', 999, 1, 'Undang Undang', 0, '', '', '', '', ''),
(43, '', '<p>Isi Peraturan Pemerintah</p>', 1, '2014-04-20 10:24:01', 999, 1, 'Peraturan Pemerintah', 0, '', '', '', '', ''),
(44, '', '<p>Isi Peraturan Desa</p>', 1, '2014-04-20 10:24:35', 999, 1, 'Peraturan Desa', 0, '', '', '', '', ''),
(45, '', '<p>Isi Peraturan Kepala Desa</p>', 1, '2014-04-20 10:25:04', 999, 1, 'Peraturan Kepala Desa', 0, '', '', '', '', ''),
(46, '', '<p>Isi Keputusan kepala desa</p>', 1, '2014-04-20 10:25:36', 999, 1, 'Keputusan Kepala Desa', 0, '', '', '', '', ''),
(47, '', '<p>Isi Panduan</p>', 1, '2014-04-20 10:38:10', 999, 1, 'Panduan', 0, '', '', '', '', ''),
(51, '', '<p>Wahai masyarakat yang ber,,,,,,,,,,,,,,,,,,,,</p>\n<p>no hp : 097867575</p>\n<p>email: jkgkgkg</p>\n<p>ato komentar di bawah ini :</p>', 1, '2014-04-22 02:11:20', 999, 1, 'Pengaduan', 0, '', '', '', '', ''),
(54, '', '<p><img title="Cool" src="../../../../../../assets/tiny_mce/plugins/emotions/img/smiley-cool.gif" alt="Cool" border="0" />hui...</p>\r\n<p><img title="Kiss" src="../../../../../../assets/tiny_mce/plugins/emotions/img/smiley-kiss.gif" alt="Kiss" border="0" />hui juga,</p>\r\n<p><img title="Cool" src="../../../../../../assets/tiny_mce/plugins/emotions/img/smiley-cool.gif" alt="Cool" border="0" />dah makan belum?</p>\r\n<p><img title="Kiss" src="../../../../../../assets/tiny_mce/plugins/emotions/img/smiley-kiss.gif" alt="Kiss" border="0" />belum,,,</p>\r\n<p><img title="Cool" src="../../../../../../assets/tiny_mce/plugins/emotions/img/smiley-cool.gif" alt="Cool" border="0" />we walk the walk, can we talk the talk,,,</p>\r\n<p><img title="Kiss" src="../../../../../../assets/tiny_mce/plugins/emotions/img/smiley-kiss.gif" alt="Kiss" border="0" />ndas mu,,,</p>\r\n<p>&nbsp;</p>', 1, '2014-04-30 02:08:51', 1003, 1, 'Contoh Manual Widget', 0, '', '', '', '', ''),
(55, '', '<p><iframe src="https://www.google.co.id/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Logandu,+Karanggayam&amp;aq=0&amp;oq=logan&amp;sll=-2.550221,118.015568&amp;sspn=52.267573,80.332031&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&amp;ll=-7.55854,109.634173&amp;spn=0.052497,0.078449&amp;z=14&amp;output=embed" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" width="298" height="280"></iframe></p>', 1, '2014-04-30 02:34:28', 1003, 1, 'Peta Desa', 0, '', '', '', '', ''),
(56, '', '<p><iframe src="http://www.youtube.com/v/L9XFIL4aJoI" frameborder="0" width="560" height="310"></iframe></p>', 1, '2014-04-30 04:09:50', 1, 1, 'Petani Rock', 0, '', '', '', '', ''),
(57, '', '<p style="text-align: justify;"><span style="background-color: #ffffff; color: #ff0000;"><strong><strong>Contoh (Sila edit halaman ini sesuai dengan deskripsi desa ini)!</strong></strong></span></p>\r\n<p style="text-align: justify;"><span style="background-color: #ffffff;">Saat terjadi geger <em>Suroyudo</em> di Kerajaan <em>Mataram</em>, salah seorang prajurit yang bernama Ki Potrojiwo menyingkir ke bagian timur wilayah kerajaan Mataram dengan membawa serta isteri dan seorang anak perempuannya yang bernama Nyi Jopotro dan cucu laki-lakinya yang bernama Trononggo, anak dari Nyi Jopotro. Setelah Ki Potrojiwo meninggal dan dimakamkan di Gunung Sentono di wilayah Piyungan, Nyi Jopotro bersama Trononggo menyingkir lebih ke timur lagi dari wilayah kekuasaan Kerajaan Mataram. Mereka masuk hutan belantara, naik ke gunung yang sekarang disebut <em>Cinomati</em> dan sampailah di sebuah wilayah yang hanya ada semak belukar di tumbuhi tanaman liar terong hutan. Tempat tersebut oleh Nyi Jopotro dinamai sebagai <em>Alas Terong</em> .</span></p>\r\n<p style="text-align: justify;">Alas Terong yang terletak diperbukitan dan jauh dari pusat kekuasaan Kerajaan Mataram, dipilih oleh Nyi Jopotro dan Trononggo untuk menjadi tempat tinggalnya yang baru. Seiring dengan berjalannya waktu, kemudian ada beberapa orang yang kemudian datang ke alas Terong, baik yang datang dari arah barat dan juga dari arah utara dan selanjutnya bertempat tinggal di alas Terong. Berkumpulah mereka menjadi penghuni alas Terong dan melakukan interaksi sosial di sana. Dari interaksi sosial dengan masyarakat di alas Terong, kemudian Trononggo menikahi seorang perempuan dan memiliki dua orang anak, yaitu Trosentono dan Tromenggolo.</p>\r\n<p style="text-align: justify;">Ketika Trononggo telah lanjut usia, dia menunjuk Trosentono untuk menjadi pemimpin masyarakat alas Terong yang disebut <em>Bekel</em> . Menurut beberapa sumber Trosentono menjadi Bekel di Terong yang pertama dan masa tugasnya antara Tahun 1912 sampai dengan 1930, kemudian pada Tahun 1930 kedudukan Bekel Terong digantikan oleh <em>Demang</em> Harjoutomo anak laki laki Trosentono sampai Tahun 1951, setelah Demang Harjoutomo purna digantikan oleh Joyo Wiyarjo anak Mertomenggolo atau cucu dari Tromenggolo. Sejak kepemimpinan Joyo Wiyarjo maka sebutan Bekel berubah menjadi <em>Lurah</em>.</p>\r\n<p style="text-align: justify;">Lurah Joyo Wiyarjo memangku jabatan mulai Tahun 1951 sampai dengan 1963. Kemudian sejak Tahun 1963 atas kepercayaan Penewu Kapanewon Kota Gede Sk, lurah Terong di percayakan kepada Harjosuwarno hingga Tahun l992 .</p>\r\n<p style="text-align: justify;">PadaTahun 1974 ketika berdirinya Kecamatan Dlingo maka Kelurahan Terong yang semula berada di wilayah Kecamatan Kotagede Sk, kemudian menjadi bagian dari wilayah Kecamatan Dlingo Kabupaten Bantul, dengan Lurah Harjosuwarno dan purna pada Tahun 1992. Berdasarkan Undang-Undang Republik Indonesia No. 5 Tahun 1979 proses demokrasi dalam pengisian lurah desa (Kepala Desa sesuai sebutan pada UU no.5 1979), menggunakan sistim pemilihan langsung dan dalam proses pemilihan tersebut terpilihlah Sudirman sebagai Kepala Desa Terong masa bakti 1994 &ndash; 2002. Setelah selesai masa jabatan Sudirman pada Tahun 2002 dan dilakukan pemilihan Kepala Desa Terong pada tahun tersebut Sudirman terpilih kembali sebagai Kepala Desa Terong (Lurah Desa Terong), melalui proses pemilihan langsung melawan kotak kosong.</p>\r\n<p style="text-align: justify;">Pada bulan Mei 2012 masa kepemimpinan Sudirman S.E sebagai Lurah Desa Terong berakhir, sebelum berakhirnya masa jabatan Lurah Sudirman S.E, BPD ( Badan Permusyawaratan Desa ) desa Terong membentuk Panitia Pemilihan Lurah Desa Terong pada tanggal 06 Maret 2012, dalam Perjalanannya Panitia Pemilihan Lurah sampai ditutupnya masa Perpanjangan Penjaringan Lurah tanggal 25 April 2012. Dengan kejadian tersebut akhirnya BPD desa Terong mengusulkan kepada Camat Dlingo, agar Poniran ( Carik ), diangkat sebagai Penjabat Lurah Desa Terong mulai bulan Mei 2012.</p>\r\n<p style="text-align: justify;">Tanggal 10 Oktober 2012 Panitia Pemilihan Lurah akhirnya membuka kembali Penjaringan Bakal Calon Lurah Desa Terong, yang pada akhirnya mendapatkan 3 nama Bakal Calon yakni, Welasiman, Sukamdam dan Sugiyono S.E. Pada proses pemungutan suara tanggal 4 November 2012, akhirnya Panitia Pemilihan Lurah, mendapatkan calon Lurah terpilih yaitu Welasiman.</p>\r\n<p style="text-align: justify;">Lurah desa Terong yang semenjak bulan Mei 2012 dijabatkan kepada <em>Poniran</em>, akhirnya berakhir pada tanggal 20 November 2012 yang ditandai dengan dilantiknya Lurah Desa Terong hasil Pemilihan, yaitu Welasiman dengan masa bhakti 2012 sampai dengan 2018.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p style="text-align: justify;">Daftar Lurah atau Kepala Desa Terong dari Tahun 1912 :</p>\r\n<ol style="text-align: justify;">\r\n<li>Trosentono (Bekel) ( 1912 &ndash; 1930 )</li>\r\n<li>Demang Harjo Utomo ( 1930 &ndash; 1950 )</li>\r\n<li>Joyo Wiyarjo ( 1950 &ndash; 1966 )</li>\r\n<li>Harjo Suwarno tahun ( 1966 &ndash; 1992 )</li>\r\n<li>Sudirman ( 1994 &ndash; 2002 )</li>\r\n<li>Ngabehi Sudirman Wiro Mandoyo 2002 &ndash; 2012 (Sudirman, S.E.) setelah mendapat kekancingan nama dari Kadipaten Pakualaman.</li>\r\n<li>Welasiman ( 2012-2018 )</li>\r\n</ol>\r\n<p style="text-align: justify;">&nbsp;PEMBAGIAN KRING DESA TERONG :</p>\r\n<ol style="text-align: justify;">\r\n<li>Kring I : Marto Jemiko membawahi Pencitrejo</li>\r\n<li>Kring II : Karto Rumekso membawahi Sendangsari</li>\r\n<li>Kring III : Hadi Suprapto membawahi Terong I dan Kebokuning</li>\r\n<li>Kring IV : Hadi Sumarto membawahi Terong II dan Rejosari</li>\r\n<li>Kring V : Sowiyarjo membawahi Saradan dan Pancuran</li>\r\n</ol>', 1, '2014-04-30 10:20:39', 999, 1, 'Sejarah Desa', 0, '', '', '', '', ''),
(59, '', '<ol>\r\n<li><strong>a.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Jumlah Penduduk</strong></li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<table style="width: 372px;" border="0" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Jumlah jiwa</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Jumlah laki-laki</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Jumlah perempuan</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Jumlah Kepala Keluarga</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">KK</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li><strong>b.&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Tingkat Pendidikan</strong></li>\r\n</ol>\r\n<table style="width: 373px;" border="0" cellspacing="0" cellpadding="0" align="left">\r\n<tbody>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Belum sekolah</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Usia 7-45 tahun tidak pernah sekolah</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Pernah sekolah SD tetapi tidak tamat</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Pendidikan terakhir</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Tamat SD/sederajat</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">SLTP/sederajat</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">SLTA/sederajat</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">D-1</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">D-2</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">D-3</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">S-1</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">S-2</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">S-3</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="121">&nbsp;</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li><strong>c.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Mata Pencaharian</strong></li>\r\n</ol>\r\n<p><strong>&nbsp;</strong></p>\r\n<table style="width: 372px;" border="0" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Petani</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">246</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Buruh tani</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">125</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Buruh/swasta</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">136</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Pegawai Negeri</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">35</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Pengrajin</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">&nbsp;</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Pedagang</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">9</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Peternak</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">-</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Nelayan</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">-</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Montir</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">8</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Dokter</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">-</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">POLRI/ABRI</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">1</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Pensiunan</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">36</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Perangkat Desa</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">15</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Pembuat Bata</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">3</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><strong>&nbsp;</strong></p>\r\n<ol>\r\n<li><strong>d.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>AGAMA</strong></li>\r\n</ol>\r\n<table style="width: 372px;" border="0" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Islam</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">2215</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Kristen</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">5</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Katholik</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">&nbsp;</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Hindu</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">&nbsp;</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="bottom" nowrap="nowrap" width="168">\r\n<p align="right">Budha</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="120">\r\n<p align="right">1</p>\r\n</td>\r\n<td valign="bottom" nowrap="nowrap" width="84">\r\n<p align="right">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><strong>&nbsp;</strong></p>\r\n<p><strong>&nbsp;</strong></p>', 1, '2014-04-30 10:23:24', 999, 1, 'Profil Potensi Desa', 0, '', '', '', '', ''),
(62, '', '<p>Lembaga Kemasyarakatan Desa (LKMD) adalah salah satu lembaga kemasyaratan yang berada di desa.</p>\n<p>TUGAS LKMD</p>\n<ol>\n<li>menyusun rencana pembangunan secara partisipatif,</li>\n<li>menggerakkan swadaya gotong royong masyarakat,</li>\n<li>melaksanakan dan</li>\n<li>mengendalikan pembangunan.</li>\n</ol>\n<p align="left">FUNGSI LKMD</p>\n<ol>\n<li>penampungan dan penyaluran aspirasi masyarakat dalam pembangunan;</li>\n<li>penanaman dan pemupukan rasa persatuan dan kesatuan masyarakat dalam kerangka memperkokoh Negara Kesatuan Republik Indonesia;</li>\n<li>peningkatan kualitas dan percepatan pelayanan pemerintah kepada masyarakat;</li>\n<li>penyusunan rencana, pelaksanaan, pelestarian dan pengembangan hasil-hasil pembangunan secara partisipatif;</li>\n<li>penumbuhkembangan dan penggerak prakarsa, partisipasi, serta swadaya gotong royong masyarakat; dan</li>\n<li>penggali, pendayagunaan dan pengembangan potensi sumber daya alam serta keserasian lingkungan hidup.</li>\n</ol>\n<p align="center"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align="center">&nbsp;</p>\n<table border="0" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="center"><strong>No</strong></p>\n</td>\n<td valign="top" width="162">\n<p align="center"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="180">\n<p align="center"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign="top" width="241">\n<p align="center"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 10:39:07', 999, 1, 'LKMD', 0, '', '', '', '', '');
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`) VALUES
(63, '', '<p>TUGAS PKK</p>\n<ol>\n<li>menyusun rencana kerja PKK Desa/Kelurahan, sesuai dengan basil Rakerda Kabupaten/Kota;</li>\n<li>melaksanakan kegiatan sesuai jadwal yang disepakati;</li>\n<li>menyuluh dan menggerakkan kelompok-kelompok PKK Dusun/Lingkungan, RW, RT dan dasa wisma agar dapat mewujudkan kegiatan-kegiatan yang telah disusun dan disepakati;</li>\n<li>menggali, menggerakan dan mengembangkan potensi masyarakat, khususnya keluarga untuk meningkatkan kesejahteraan keluarga sesuai dengan kebijaksanaan yang telah ditetapkan;</li>\n<li>melaksanakan kegiatan penyuluhan kepada keluarga-keluarga yang mencakup kegiatan bimbingan dan motivasi dalam upaya mencapai keluarga sejahtera;.</li>\n<li>mengadakan pembinaan dan bimbingan mengenai pelaksanaan program kerja;</li>\n<li>berpartisipasi dalam pelaksanaan program instansi yang berkaitan dengan kesejahteraan keluarga di desa/kelurahan;</li>\n<li>membuat laporan basil kegiatan kepada Tim Penggerak PKK Kecamatan dengan tembusan kepada Ketua Dewan Penyantun Tim Penggerak PKK setempat;</li>\n<li>melaksanakan tertib administrasi; dan</li>\n<li>mengadakan konsultasi dengan Ketua Dewan Penyantun Tim Penggerak PKK setempat.</li>\n</ol>\n<p>&nbsp;</p>\n<p>FUNGSI PKK</p>\n<ol>\n<li>penyuluh, motivator dan penggerak masyarakat agar mau dan mampu melaksanakan program PKK; dan</li>\n<li>fasilitator, perencana, pelaksana, pengendali, pembina dan pembimbing Gerakan PKK.</li>\n</ol>\n<p align="center"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align="center">&nbsp;</p>\n<table border="0" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="center"><strong>No</strong></p>\n</td>\n<td valign="top" width="162">\n<p align="center"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="180">\n<p align="center"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign="top" width="241">\n<p align="center"><strong>Alamatn</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="center">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="center">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="center">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="180">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="241">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 10:41:13', 999, 1, 'PKK', 0, '', '', '', '', ''),
(64, '', '<p align="left">TUGAS &nbsp;KARANGTARUNA</p>\n<p align="left">menanggulangi berbagai masalah kesejahteraan sosial terutama yang dihadapi generasi muda, baik yang bersifat preventif, rehabilitatif, maupun pengembangan potensi generasi muda di lingkungannya</p>\n<p align="left">FUNGSI KARANGTAURNA</p>\n<ol>\n<li>penyelenggara usaha kesejahteraan sosial;</li>\n<li>penyelenggara pendidikan dan pelatihan bagi masyarakat;</li>\n<li>penyelenggara pemberdayaan masyarakat terutama generasi muda di lingkungannya secara komprehensif, terpadu dan terarah serta berkesinambungan;</li>\n<li>penyelenggara kegiatan pengembangan jiwa kewirausahaan bagi generasi muda di lingkungannya;</li>\n<li>penanaman pengertian, memupuk dan meningkatkan kesadaran tanggung jawab sosial generasi muda;</li>\n<li>penumbuhan dan pengembangan semangat kebersamaan, jiwa kekeluargaan, kesetiakawanan sosial dan memperkuat nilai-nilai kearifan dalam bingkai Negara Kesatuan Republik Indonesia;</li>\n</ol>\n<p align="left">&nbsp;</p>\n<p align="center"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align="center">&nbsp;</p>\n<table border="0" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="center"><strong>No</strong></p>\n</td>\n<td valign="top" width="162">\n<p align="center"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="192">\n<p align="center"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign="top" width="229">\n<p align="center"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">1</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">2</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">3</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 10:44:28', 999, 1, 'Karang Taruna', 0, '', '', '', '', ''),
(65, '', '<p align="left">TUGAS RT/RW</p>\n<p align="left">membantu Pemerintah Desa dan Lurah dalam penyelenggaraan urusan pemerintahan</p>\n<p align="left">FUNGSI PKK</p>\n<ol>\n<li>pendataan kependudukan dan pelayanan administrasi pemerintahan lainnya;</li>\n<li>pemeliharaan keamanan, ketertiban dan kerukunan hidup antar warga;</li>\n<li>pembuatan gagasan dalam pelaksanaan pembangunan dengan mengembangkan aspirasi dan swadaya murni masyarakat; dan</li>\n<li>penggerak swadaya gotong royong dan partisipasi masyarakat di wilayahnya.</li>\n</ol>\n<p align="left">&nbsp;</p>\n<p align="center"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align="center">&nbsp;</p>\n<table border="0" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="left"><strong>No</strong></p>\n</td>\n<td valign="top" width="186">\n<p align="left"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="204">\n<p align="left"><strong>Nama Pejabat</strong></p>\n</td>\n<td valign="top" width="193">\n<p align="left"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">1</p>\n</td>\n<td valign="top" width="186">\n<p align="left">Ketua RW 1</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">2</p>\n</td>\n<td valign="top" width="186">\n<p align="left">Ketua RW 1 Rt 01</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">3</p>\n</td>\n<td valign="top" width="186">\n<p align="left">Ketua RW 1 Rt 02</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="186">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="204">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="193">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 10:45:19', 999, 1, 'RT RW', 0, '', '', '', '', ''),
(66, '', '<p class="Default">&nbsp;</p>\n<p class="Default">Tim Koordinasi Percepatan Penanggulangan Kemiskinan Desa yang selanjutnya disingkat TKP2KDes adalah wadah koordinasi lintas sektor dan lintas pemangku kepentingan untuk percepatan penanggulangan kemiskinan di desa.</p>\n<p class="Default">TKP2KDes bertugas mengkoordinasikan perencanaan, pengorganisasian, pelaksanaan dan pengendalian program penanggulangan kemiskinan di tingkat Desa.</p>\n<p>( Perda Kabupaten Kebumen Nomor 20 Tahun 2012 Tentang Percepatan Penanggulangan Kemiskinan )</p>\n<p align="center"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip; Nomor : &hellip;&hellip;tanggal &hellip;&hellip;.. bulan&hellip;.. tentang &hellip;..</p>\n<p align="center">&nbsp;</p>\n<p align="center">&nbsp;</p>\n<table border="0" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="left"><strong>No</strong></p>\n</td>\n<td valign="top" width="162">\n<p align="center"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="192">\n<p align="center"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign="top" width="229">\n<p align="center"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">1</p>\n</td>\n<td valign="top" width="162">\n<p align="center">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="center">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="center">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">2</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">3</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 10:46:01', 999, 1, 'TKP2KDes', 0, '', '', '', '', ''),
(67, '', '<p class="Default">&nbsp;</p>\n<p class="Default">Kelompok Perlindungan Anak Desa atau Kelurahan yang selanjutnya disingkat KPAD atau KPAK adalah lembaga perlindungan anak berbasis masyarakat yang berkedudukan dan melakukan kerja&ndash;kerja perlindungan anak di wilayah desa atau kelurahan tempat anak bertempat tinggal&nbsp;&nbsp;&nbsp;&nbsp; ( Perda Kaupaten Kebumen No 3 Tahun 2013 Tentang Penyelenggaraan Perlindungan Anak&nbsp; )</p>\n<p>TUGAS-TUGAS KPAD</p>\n<p>1.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sosialisasi</p>\n<ol>\n<li>Mensosialisasikan kepada masyarakat tentang hak-hak anak</li>\n<li>Mempromosikan CHILD RIGHTS dan CHILD PROTECTION</li>\n<li>Melakukan upaya pencegahan, respon dan penanganan kasus kasus kekerasan terhadap anak dan masalah anak.</li>\n</ol>\n<p>1.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mediasi</p>\n<ol>\n<li>Mengedepankan upaya musyawarah dan mufakat (Rembug Desa)&nbsp; dalam menyelesaikan masalah &ndash; (Restorative Justive)</li>\n<li>Melakukan koordinasi dengan pihak terkait di level desa, kecamatan dan kabupaten dalam upaya perlindungan anak.</li>\n<li>Melakukan pendampingan kasus (dari pelaporan &ndash; medis &ndash; psikologi - reintegrasi)</li>\n</ol>\n<p>1.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fasilitasi</p>\n<ol>\n<li>Memfasilitasi terbentuknya kelompok anak di desa sebagai media partisipasi anak</li>\n<li>Memfasilitasi partisipasi anak untuk terlibat dalam penyusunan perencanaan pembangunan yang berbasis hak anak (penyusunan RPJMDesa)</li>\n</ol>\n<p>1.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Dokumentasi</p>\n<ol>\n<li>Mendokumentasikan semua proses yang dilakukan (Kegiatan Promosi; Penanganan Kasus dan mencatat kasus yang dilaporkan; Perkembangan Kasus, Pertemuan,dll)</li>\n</ol>\n<p>1.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Advokasi</p>\n<ol>\n<li>Mendorong adanya kebijakan dan penganggaran untuk perlindungan anak di level desa</li>\n<li>Menerima pengaduan kasus dan konsultasi tentang perlindungan anak</li>\n<li>Berhubungan dengan P2TP2A dan LPA untuk pendampingan hukum kasus anak (korban dan atau pelaku)</li>\n</ol>\n<p class="Default">&nbsp;</p>\n<p class="Default">&nbsp;</p>\n<p align="center"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align="center">&nbsp;</p>\n<table border="1" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="left"><strong>No</strong></p>\n</td>\n<td valign="top" width="162">\n<p align="left"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="192">\n<p align="left"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign="top" width="229">\n<p align="left"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">1</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">2</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">3</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p align="left">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 10:47:21', 999, 1, 'KPAD', 0, '', '', '', '', ''),
(68, '', '<p align="center"><strong>DAFTAR NAMA PENGURUS KELOMPOK TERNAK &hellip;..</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang</p>\n<p align="center">&hellip;&hellip;&hellip;&hellip;&hellip;.. &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align="center">&nbsp;</p>\n<table border="0" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="center"><strong>No</strong></p>\n</td>\n<td valign="top" width="162">\n<p align="center"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="192">\n<p align="center"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign="top" width="229">\n<p align="center"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">1</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">2</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">3</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align="left">&nbsp;</p>\n<p align="center"><strong>DAFTAR NAMA PENGURUS KELOMPOK WANITA TANI TERNAK&nbsp; &hellip;&hellip;.</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align="center">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align="center">&nbsp;</p>\n<table border="0" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="left"><strong>No</strong></p>\n</td>\n<td valign="top" width="162">\n<p align="left"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="192">\n<p align="left"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign="top" width="229">\n<p align="left"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">1</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">2</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">3</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align="center">&nbsp;</p>\n<p align="center">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 10:47:58', 999, 1, 'Kelompok Ternak', 0, '', '', '', '', '');
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`) VALUES
(69, '', '<p align="center"><strong>DAFTAR NAMA PENGURUS GAPOKTAN</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang</p>\n<p align="center">&hellip;&hellip;&hellip;&hellip;&hellip;.. &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align="center">&nbsp;</p>\n<table border="0" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="center"><strong>No</strong></p>\n</td>\n<td valign="top" width="162">\n<p align="center"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="192">\n<p align="center"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign="top" width="229">\n<p align="center"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">1</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">2</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">3</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align="left">&nbsp;</p>\n<p align="center"><strong>DAFTAR NAMA PENGURUS KELOMPOK TANI &hellip;&hellip;.</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align="center">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align="center">&nbsp;</p>\n<table border="0" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="center"><strong>No</strong></p>\n</td>\n<td valign="top" width="162">\n<p align="center"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="192">\n<p align="center"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign="top" width="229">\n<p align="center"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">1</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">2</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">3</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align="center">&nbsp;</p>\n<p align="center">&nbsp;</p>\n<p align="center">&nbsp;</p>\n<p align="center">&nbsp;</p>\n<p align="center">&nbsp;</p>\n<p align="center">&nbsp;</p>\n<p align="center">&nbsp;</p>\n<p align="center"><strong>DAFTAR NAMA PENGURUS KELOMPOK TANI &hellip;&hellip;.</strong></p>\n<p align="center"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align="center"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align="center">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align="center">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align="center">&nbsp;</p>\n<table border="0" cellspacing="0" cellpadding="0">\n<tbody>\n<tr>\n<td valign="top" width="55">\n<p align="center"><strong>No</strong></p>\n</td>\n<td valign="top" width="162">\n<p align="center"><strong>Jabatan</strong></p>\n</td>\n<td valign="top" width="192">\n<p align="center"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign="top" width="229">\n<p align="center"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">1</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">2</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="center">3</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign="top" width="55">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="162">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="192">\n<p align="left">&nbsp;</p>\n</td>\n<td valign="top" width="229">\n<p align="left">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>', 1, '2014-04-30 10:48:39', 999, 1, 'Kelompok Tani', 0, '', '', '', '', ''),
(70, '', '<p>Linmas</p>', 1, '2014-04-30 10:53:18', 999, 1, 'LinMas', 0, '', '', '', '', ''),
(71, '', '<p>Kelompok Ekonomi Lainya</p>', 1, '2014-04-30 10:54:20', 999, 1, 'Kelompok Ekonomi Lainya', 0, '', '', '', '', ''),
(72, 'Airterjun Selapohon.jpg', '<p style="text-align: justify;">Sebagai tindak lanjut dari tahapan pelaksanaan program TKP2KDes dan pengembangan SID di Kabupate Kebumen, Pemerintah Desa Logandu Kecamatan bekerjasama dengan Formasi Kebumen dan Combine Resource Institution (CRI) Jogjakarta mengadakan sosialisasi tentang SID dan TKP2KDes&nbsp;kepada lembaga desa dan warga masyarakat desa Logandu. Kegiatan yang dilaksanakan pada hari Selasa, 26 Maret 2014 di Balai Desa Logandu Kecamatan Karanggayam, Kebumen dihadiri oleh sedikitnya 60 orang peserta yang terdiri dari unsur perangkat desa, lembaga desa, tokoh agama dan warga masyarakat.</p>\r\n<div style="text-align: justify;">Dalam paparannya tentang Kebijakan Desa terkait dengan Penanggulangan Kemiskinan, Sarlan (Kepala Desa Logandu) menyampaikan bahwa, &ldquo;banyak program penanggulangan kemiskinan yang telah digelontorkan oleh pemerintah kepada masyarakat, tetapi masih banyak yang tidak tepat sasaran dan target capaiannya belum imbang dengan besaran dana yang dikeluarkan&rdquo;. Mengapa demikian? Salahsatunya adalah data yang ada masih berbeda-beda antara dinas yang satu dengan yang lain. Disisi lain data yang digunakan masih menggunakan data dari BPS yang update datanya 4 tahun sekali.</div>\r\n<div style="text-align: justify;">&ldquo;Saya menyambut baik dengan dibentuknya TKP2KDes (Tim Koordinasi Percepatan Penanggulangan Kemiskinan Desa) dan SID (Sistem Informasi Desa), dengan harapan data kemiskinan bisa terakomodir dan valid serta program kemiskinan bisa terkoordinasikan&rdquo;.</div>\r\n<div style="text-align: justify;">&nbsp;</div>\r\n<div style="text-align: justify;">\r\n<div>Terkait dengan SID, Irman Ariadi (CRI) menjelaskan bahwa manfaat dari SID adalah membantu desa terkait dengan validitas dan update data, mempermudah dan mempercepat proses pelayanan kepada masyarakat. Juga sebagai media untuk mempromosikan potensi dan kegiatan yang dilaksanakan desa.&nbsp;Sedangkan tentang TKP2KDes dipaparkan oleh Muhtar Syaiful Anam (Formasi) yang lebih menekankan pada alur pencacahan/pendataan penduduk miskin.</div>\r\n<div>&nbsp;</div>\r\n<div>\r\n<div>Diakhir sosialisasi dibentuk&nbsp;Timpendataan penduduk miskin Desa Logandu yang terdiri dari 11 orang, yaitu: Mardiadi dan Mahdi Fathurrahman (Operator SID), Siti Hayati, Wiwit Sutanti, Lasimin, Tasinah, Nasim, Sonni Setiawan, Sarimun (TKP2KDes), Mahudin dari unsur BPD dan Sugiman dari unsur Perangkat Desa.</div>\r\n<div>&ldquo;Kerjasama yang baik antara Pemerintah Desa, Tim Pencacah dan warga masyarakat menjadi kunci keberhasilan dalam pelaksanaan program TKP2KDes dan SID&rdquo;, pesan Sarlan (Kepala Desa Logandu) dalam menutup acara sosialisasi.</div>\r\n</div>\r\n</div>', 1, '2014-05-02 05:57:23', 1, 1, 'Kostumize Widget', 0, '', 'Senja Pantaikelapa.jpg', '', '', ''),
(73, 'sky.jpg', '<p><strong>Tentang SID</strong> <br /> Sistem Informasi Desa (SID) adalah sebuah platform teknologi informasi komunikasi untuk mendukung pengelolaan sumber daya komunitas di tingkat desa. Ini bersifat terbuka bagi siapa saja yang akan bergabung dalam gerakan membangun kemandirian komunitas. Konsep pengelolaan sumber daya berada dalam payung besar gagasan Lumbung Komunitas yang dikelola oleh COMBINE Resource Institution.<br /> <strong><br /></strong> <strong>Sekilas pandang sistem informasi desa</strong><br /> Aplikasi Gratis untuk desa yang berbasis Opensource, SID merupakan inisiatif Combine resource institution, sebuah organisasi masyarakat sipil yang sangat tekun dan dedikatif dalam mengembangkan informasi dan komunikasi alternatif berbasis masyarakat.<br /> <strong><br /></strong> <strong>Awal mula SID</strong><br /> "Awalnya ada keinginan dari pemerintah Desa Balerante yang berharap pelayanan pemerintah desa bisa seperti pengunjung rumah sakit yang ingin mencari data pasien rawat inap, tinggal ketik nama di komputer, maka data tersebut akan keluar"<br /> (Mart Widarto, pengelola Program Lumbung Komunitas)<br /> Program ini mulai dibuat dari awal 2006: <br /> 1. (2006) komunitas melakukan komunikasi dan diskusi lepas tentang sebuah sistem yang bisa digunakan untuk menyimpan data.<br /> 2. (2008) Rangkaian FDG dengan pemerintah desa membahas tentang tata kelola pendokumentasian di desa<br /> 3. (2009) Ujicoba SID yang sudah dikembangkan di balerante<br /> 4. (2009-2010) Membangun SID (aplikasi) dibeberapa desa yang lain: terong (bantul), Nglegi (Gunungkidul) <br /> 5. (2011) Kandangan (Temanggung) Gilangharjo (bantul) Girikarto (gunungkidul) Talun (klaten) Pager Gunung (magelang) <br /> 6. hingga saat ini 2013 sudah banyak desa pengguna SID.<br /> <br /> <strong>SID sebagai tanggapan atas kebutuhan:</strong><br /> Kalau dulu untuk mencari data penduduk menurut kelompok umur saja kesulitan karena tidak mempunyai databasenya. Dengan adanya SID menjadi lebih mudah.<br /> (Nuryanto, Kabag Pelayanan Pemdes Terong)<br /> <br /> Membangun sebuah sistem bukan hanya membuatkan software dan meninggalkan begitu saja, namun ada upaya untuk memadukan sistem dengan kebutuhan yang ada pada desa. sehingga software dapat memenuhi kebutuhan yang telah ada bukan memaksakan desa untuk mengikuti dan berpindah sistem. inilah yang melatari combine melaksanakan alur pengaplikasian software.<br /> 1. Bentuk tim kerja bersama pemerintah desa<br /> 2. Diskusikan basis data apa saja yang diperlukan untuk warga<br /> 3. Himpun data kependudukan warga dari Kartu Keluarga (KK)<br /> 4. Daftarkan proyek SID dan dapatkan aplikasi softwarenya di http://abcd.lumbungkomunitas.net<br /> 5. Install aplikasi software SID di komputer desa<br /> 6. Entry data penduduk ke SID<br /> 7. Basis data kependudukan sudah bisa dimanfaatkan<br /> 8. Diskusikan rencana pengembangan SID sesuai kebutuhan desa<br /> 9. Sebarluaskan informasi desa melalui beragam media untuk warga<br /> (Elanto Wijoyono, pengelola Program Lumbung Komunitas)<br /> <br /> Pemberdayaan data desa yang dibangun diharapkan dapat menjunjung kesejahteraan masyarakat desa, data-data tersebut dapat diperuntukkan untuk riset lebih lanjut tentang kemiskinan, tanggap bencana, sumberdaya desa yang bisa diekspose keluar dan dengan menghubungkan dari desa ke desa dapat mencontohkan banyak hal dalam keberhasilan pemberdayaannya.<br /> (sumber: Buku Sistem Informasi Desa) <br /> <strong><br /></strong> <strong>Hal-hal penting yang harus diperhatikan dalam mengaplikasikan SID</strong><br /> 1. SID mencoba masuk keranah paling kecil dari penduduk, dengan tujuan menyatukan warga masyarakat bersama perangkat desa bersama-sama memajukan desa dengan memanfaatkan informasi dan jaringan. Konsep gotong royong sebagai kearifan lokal dipertahankan dan dikuatkan kembali.<br /> 2. Data yang dikumpulkan dijaga kerahasiaanya dari publik, Keamanan data pribadi warga mengacu pada Undang-Undang Nomor 23 Tahun 2006 tentang Administrasi Kependudukan. Penerapan sistem informasi administrasi kependudukan diatur dalam Keputusan Presiden (Keppres) No. 88/2004 tentang pengelolaan administrasi kependudukan, Undang-Undang (UU) No. 23 tahun 2006 tentang administrasi kependudukan dan Peraturan Menteri Dalam Negeri (Permendagri) No. 18/2005 serta Peraturan Pemerintah (PP) No. 37 tahun 2007 tentang administrasi kependudukan. Pencatatan data penduduk suatu daerah yang melalui sistem informasi administrasi kependudukan menjadi tanggung jawab pemerintah kabupaten dan kota dimana dalam pelaksanaannya diawali dari desa dan kelurahan sebagai awal dari pendataan penduduk disuatu daerah.<br /> 3. Software SID hanyalah sebuah alat, yang membutuhkan ketrampilan dari pengguna alat. alat ini bisa menjadi kekuatan desa apabila digunakan dengan semestinya, namun juga bisa menjadi bumerang ketika jatuh ketangan yang salah.<br /> <strong><br /></strong> <strong>Pengembangan dengan ACCESS</strong><br /> ACCESS yang memiliki pengalaman melakukan participatory poverty assessment dengan pendekatan Community Led Action and participatory Process (CLAPP) dimana melibatkan warga secara partisipatif dalam menyusun dan menghasilkan data yang valid. Yang kemudian Memanfaatkan SAID (Sistem Administrasi dan Informasi Desa) sebagai &acirc;&euro;&oelig;mesin&acirc;&euro; untuk memanfaatkan database partisipatif yang diperoleh. <br /> ACCESS bekerjasama dengan Combine Resource Institution (CRI) bersama mengembangkan SAID di sejumlah desa di wilayah dampingan ACCESS, dan beberapa daerah telah menyatakan minatnya sebagai bagian dari upaya mereka menyediakan informasi terbaru yang lebih akurat di level yang paling bawah yaitu desa/kelurahan. Hal ini dirasakan karena Indonesia masih menghadapi kesulitan terkait dengan ketepatan data dan SAID membantu menyediakan data tersebut dari desa ke atas.</p>', 1, '2014-05-16 06:21:08', 1, 1, 'Selamat Bergabung dengan SID', 0, '', 'Sunset Kelapa.jpg', '', '', ''),
(74, 'DSC_0050.JPG', '<p style="text-align: justify;">This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed. If you have received this email in error please notify the system administrator. Please note that any views or opinions presented in this email are solely those of the author and do not necessarily represent those of the company. Finally, the recipient should check this email and any attachments for the presence of viruses. The company accepts no liability for any damage caused by any virus transmitted by this email. <br />+++----Pernyataan_Email_Bahasa_Indonesia-----++<br />Email ini dan setiap berkas di dalamnya bersifat rahasia dan hanya ditujukan untuk digunakan oleh individu ataupun entitas yang tersebut dalam alamat tujuan. Jika anda menerima email ini karena suatu kesalahan/kegagalan mohon disampaikan kepada pengelola email server kami. Mohon dipahami bahwa setiap pandangan ataupun opini yang terdapat dalam email ini adalah semata-mata buah pikiran penulis dan tidak mengatasnamakan lembaga kecuali disebutkhusus. <br />Mohon penerima email meninjau ulang keberadaan virus dalam email dan berkas lampiran yang terdapat didalamnya. Lembaga kami tidak bertanggungjawab bila ada kerusakan yang disebabkan oleh virus yang tersebarkan melalui email ini.</p>', 1, '2014-10-03 12:41:47', 1, 1, 'Coba Upload Dokumen', 0, '', '', '', 'inn pricelist.xlsx', 'Contoh Nama Dokumen'),
(79, 'Koala.jpg', '<p style="text-align: justify;">This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed. If you have received this email in error please notify the system administrator. Please note that any views or opinions presented in this email are solely those of the author and do not necessarily represent those of the company. Finally, the recipient should check this email and any attachments for the presence of viruses. The company accepts no liability for any damage caused by any virus transmitted by this email.</p>', 1, '2014-10-24 09:57:06', 7, 1, 'Bakpia Ketan', 0, '', '', '', '', ''),
(80, 'Hydrangeas.jpg', '<p><span class="st">Kita perlu melakukan kerja sama. Kerja sama dapat dilakukan dalam bentuk <em>kerja bakti</em>. Dengan bekerja sama semua pekerjaan berat menjadi ringan.</span></p>', 1, '2014-11-06 07:27:29', 1000, 1, 'Kerja Bakti', 1, '', '', '', '', ''),
(81, 'Lighthouse.jpg', '<p>Jelajah Desa merupakan paket wisata menjelajah Desa Wisata Bumi Pertiwi mengunjungi masing sanggar dan menikmati alam desa.<br />Jelajah Desa ini masing-masing peserta dikenai biaya: Rp. 15.000,-/ Orang.<br />Dengan peserta minimal 10 Orang<br />Fasilitas : Pemandu</p>', 1, '2014-11-06 07:33:38', 7, 1, 'Jelajah Desa yang Mengesankan', 0, '', '', '', '', ''),
(82, '', '<p style="text-align: justify;">Halaman ini berisi tautan menuju informasi mengenai Basis Data Desa. Ada dua jenis data yang dimuat dalam sistem ini, yakni basis data kependudukan dan basis data sumber daya desa. Sila klik pada tautan berikut untuk mendapatkan tampilan data statistik per kategori.</p>\r\n<ol>\r\n<li style="text-align: justify;">Data Wilayah Administratif&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li style="text-align: justify;">Data Pendidikan&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li style="text-align: justify;">Data Pekerjaan&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li style="text-align: justify;">Data Golongan Darah&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li style="text-align: justify;">Data Agama&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li style="text-align: justify;">Data Jenis Kelamin&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li style="text-align: justify;">Data Kelompok Umur&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li style="text-align: justify;">Data Penerima Raskin&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li style="text-align: justify;">Data Penerima Jamkesmas&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li style="text-align: justify;">Data Warga Negara&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li style="text-align: justify;">Data Kelas Sosial&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n</ol>\r\n<p style="text-align: justify;">Data yang tampil adalah statistik yang didapatkan dari proses olah data dasar yang dilakukan secara <em>offline</em> di kantor desa secara rutin/harian. Data dasar di kantor desa diunggah ke dalam sistem <em>online</em> di website ini secara berkala. Sila hubungi kontak pemerintah desa untuk mendapatkan data dan informasi desa termutakhir.</p>', 1, '2014-11-06 08:26:37', 999, 1, 'Data Desa', 0, '', '', '', '', ''),
(83, '', '<p>Tiap hari rapat sampai kumat</p>', 1, '2014-11-06 10:17:52', 1000, 1, 'Rapat Lagi', 0, '', '', '', '', ''),
(84, 'Penguins.jpg', '<p style="text-align: justify;">1. Pada komputer/PC server, buka web-browser (direkomendasikan <span style="text-decoration: underline;">Mozilla Firefox</span> atau <span style="text-decoration: underline;">Chrome</span>)</p>\r\n<p style="text-align: justify; padding-left: 30px;">Ketik:<span style="color: #0000ff;"> localhot/phpmyadmin</span></p>\r\n<p>2. Muncul halaman <span style="text-decoration: underline;">PHPMyAdmin</span>. Periksa kolom kiri.</p>\r\n<p style="padding-left: 30px;">Klik/Pilih: &ldquo;sid&rdquo;</p>\r\n<p>3. Muncul halaman yang menampilkan deretan isi tabel &ldquo;<span style="text-decoration: underline;">sid</span>&ldquo;.</p>\r\n<p style="padding-left: 30px;">Klik/Pilih: &ldquo;Export&rdquo; pada bagian menu atas di kolom kanan</p>\r\n<p>4. Muncul halaman <span style="text-decoration: underline;">Export</span>. Kolom isian yang ada tak perlu diubah. Lihat bagian kanan bawah.</p>\r\n<p style="padding-left: 30px;">Klik: &ldquo;Go&rdquo;</p>\r\n<p>5. Muncul jendela pilihan lokasi untuk menyimpan file database.</p>\r\n<p style="padding-left: 30px;">Klik: &ldquo;Save&rdquo;<br /> Klik: &ldquo;OK&rdquo;</p>\r\n<p>6. File database SID 3.0 akan tersimpan otomatis di folder Download (tergantung setting komputer masing-masing). File tersimpan dalam sebagai &ldquo;sid.sql&rdquo;</p>\r\n<p>7. Simpan/amankan file &ldquo;sid.sql&rdquo; di media lain: Hard-disk eksternal, CD, <em>online storage</em>, dan sebagainya.</p>\r\n<p>8. Lakukan proses <em>back-up</em> database ini secara berkala.</p>\r\n<p>.<br /> Selamat mencoba!</p>', 1, '2014-11-06 10:25:34', 1004, 1, 'Panduan Back-Up Data (Export Database) SID 3.0', 0, '', '', '', '', ''),
(85, '', '<div class="contentText">\r\n<div align="justify">Bagian ini berisi informasi mengenai PemerintahanDesa. Sila klik pada tautan berikut untuk mendapatkan informasi yang lebih rinci.</div>\r\n<div align="justify">&nbsp;</div>\r\n<ol>\r\n<li>Visi dan Misi</li>\r\n<li>Pemerintah Desa</li>\r\n<li>Badan Permusyawaratan Desa</li>\r\n</ol></div>', 1, '2014-11-07 02:53:54', 999, 1, 'Pemerintahan Desa', 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `nama_desa`, `kode_desa`, `nama_kepala_desa`, `nip_kepala_desa`, `kode_pos`, `nama_kecamatan`, `kode_kecamatan`, `nama_kepala_camat`, `nip_kepala_camat`, `nama_kabupaten`, `kode_kabupaten`, `nama_propinsi`, `kode_propinsi`, `logo`, `lat`, `lng`, `zoom`, `map_tipe`, `path`, `alamat_kantor`, `g_analytic`) VALUES
(1, 'Bumi Pertiwi', '009', 'Bejo Mulyo Rekoso', '--', '54365', 'Nusantara', '21', 'Bambang Budi Sanyoto, S. H', '-', 'Sejahtera', '05', 'Santosa', '33', 'gkk.png', '-7.816009896489696', '110.36447630295561', 12, 'roadmap', '(-7.743118383096033, 110.34478571265936);(-7.748901613635884, 110.3416958078742);(-7.755705312573212, 110.33826258033514);(-7.764549956678239, 110.33551599830389);(-7.7730542466061285, 110.33242609351873);(-7.780197716998335, 110.33208277076483);(-7.7893820001133784, 110.33105280250311);(-7.798566081843957, 110.3307094797492);(-7.801967542443794, 110.32658960670233);(-7.80604925864044, 110.32555963844061);(-7.81659350759561, 110.32418634742498);(-7.822715852513199, 110.32452967017889);(-7.823736234596529, 110.3303661569953);(-7.826797365863762, 110.33757593482733);(-7.827817737957529, 110.35577204078436);(-7.832579441358312, 110.35989191383123);(-7.8359806247460675, 110.36160852760077);(-7.83836143658561, 110.37190821021795);(-7.834960272646731, 110.38289453834295);(-7.834960272646731, 110.3911342844367);(-7.837681206020989, 110.39319422096014);(-7.8380213214422705, 110.40521051734686);(-7.8216954679332495, 110.41104700416327);(-7.80264783124394, 110.40898706763983);(-7.782578846759991, 110.41070368140936);(-7.783599326803616, 110.42821314185858);(-7.765570480561039, 110.43233301490545);(-7.758766941202343, 110.40315058082342);(-7.758086581210773, 110.39388086646795);(-7.760127657883125, 110.38667108863592);(-7.750942734864957, 110.37911798804998);(-7.748561425802341, 110.36504175513983);(-7.745159532352903, 110.35371210426092);', 'Jl. Menuju Angin No. 66 Semakin Tinggi, Nusantara (0274) 983842', 'gsgsdgsdgsg');

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_surat`
--
CREATE TABLE IF NOT EXISTS `data_surat` (
`id` int(11)
,`nama` varchar(100)
,`sex` varchar(15)
,`tempatlahir` varchar(100)
,`tanggallahir` date
,`umur` double(17,0)
,`status_kawin` varchar(100)
,`warganegara` varchar(25)
,`agama` varchar(100)
,`pendidikan` varchar(50)
,`pekerjaan` varchar(100)
,`nik` decimal(16,0)
,`rt` varchar(10)
,`rw` varchar(10)
,`dusun` varchar(50)
,`no_kk` varchar(160)
,`kepala_kk` varchar(100)
);
-- --------------------------------------------------------

--
-- Table structure for table `detail_log_penduduk`
--

CREATE TABLE IF NOT EXISTS `detail_log_penduduk` (
  `id` int(10) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE IF NOT EXISTS `dokumen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `satuan` varchar(200) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT '1',
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gambar_gallery`
--

CREATE TABLE IF NOT EXISTS `gambar_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parrent` int(4) NOT NULL,
  `gambar` varchar(200) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT '1',
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipe` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parrent` (`parrent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `gambar_gallery`
--

INSERT INTO `gambar_gallery` (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`) VALUES
(8, 7, 'C360_2014-05-07-16-53-18-469.jpg', 'Coba 1', 1, '2014-05-01 13:23:43', 2),
(9, 7, 'DSC_0313.JPG', 'Coba 2', 1, '2014-05-01 13:24:24', 2),
(11, 10, 'fgdgd.JPG', 'Contoh 1', 1, '2014-05-01 13:27:52', 2),
(12, 10, 'jjj.JPG', 'Contoh 2', 1, '2014-05-01 13:30:54', 2),
(13, 0, '3.jpg', '2014', 1, '2014-10-22 04:45:45', 0),
(14, 13, 'milky-way-above-the-fir-forest-nature-hd-wallpaper-2560x1600-2492.jpg', 'Kaya', 1, '2014-10-22 04:46:02', 2),
(15, 0, '3.jpg', 'aaaaaaaaaaaaaaa', 1, '2014-10-22 04:47:28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `garis`
--

CREATE TABLE IF NOT EXISTS `garis` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `path` text NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  `ref_line` int(9) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `desk` text NOT NULL,
  `id_cluster` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE IF NOT EXISTS `inbox` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE IF NOT EXISTS `kategori` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(100) NOT NULL,
  `tipe` int(4) NOT NULL DEFAULT '1',
  `urut` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1005 ;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`) VALUES
(1, 'Berita Desa', 1, 1),
(7, 'Produk Desa', 1, 3),
(999, 'Manajemen Halaman Statis', 2, 20),
(1000, 'Agenda Desa', 2, 2),
(1001, 'Peraturan Desa', 2, 5),
(1002, 'Laporan Desa', 2, 6),
(1003, 'Customizable Widget', 2, 21),
(1004, 'Panduan Layanan Desa', 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `kelompok`
--

CREATE TABLE IF NOT EXISTS `kelompok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_master` int(11) NOT NULL,
  `id_ketua` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_ketua` (`id_ketua`),
  KEY `id_master` (`id_master`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_anggota`
--

CREATE TABLE IF NOT EXISTS `kelompok_anggota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kelompok` int(11) NOT NULL,
  `id_penduduk` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_kelompok` (`id_kelompok`,`id_penduduk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_master`
--

CREATE TABLE IF NOT EXISTS `kelompok_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kelompok` varchar(50) NOT NULL,
  `deskripsi` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `kelompok_master`
--

INSERT INTO `kelompok_master` (`id`, `kelompok`, `deskripsi`) VALUES
(1, 'Kelompok Ternak', '<p>Kelompok yang memelihara ternak</p>');

-- --------------------------------------------------------

--
-- Table structure for table `klasifikasi_analisis_keluarga`
--

CREATE TABLE IF NOT EXISTS `klasifikasi_analisis_keluarga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) NOT NULL,
  `jenis` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE IF NOT EXISTS `komentar` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_artikel` int(7) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `komentar` text NOT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enabled` int(2) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `komentar`
--

INSERT INTO `komentar` (`id`, `id_artikel`, `owner`, `email`, `komentar`, `tgl_upload`, `enabled`) VALUES
(1, 74, 'Sansan Wawa', 'sanwa@ok.com', 'artikel yang inspiratif :)', '2014-11-06 06:06:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kontak`
--

CREATE TABLE IF NOT EXISTS `kontak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pend` int(11) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `kontak_grup`
--

CREATE TABLE IF NOT EXISTS `kontak_grup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_grup` varchar(30) NOT NULL,
  `id_kontak` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `line`
--

CREATE TABLE IF NOT EXISTS `line` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT 'ff0000',
  `tipe` int(4) NOT NULL,
  `parrent` int(4) DEFAULT '1',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parrent` (`parrent`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `line`
--

INSERT INTO `line` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES
(1, 'Jalan', '', 'FFCD42', 0, 1, 1),
(2, 'Jalan Raya', '', 'FFCD42', 2, 66, 1),
(3, 'Jalan Kampung', '', '', 2, 66, 1),
(4, 'Ring Road', '', '', 2, 66, 1),
(5, 'Sungai', '', 'FFFFFF', 0, 1, 1),
(6, 'Selokan', '', '', 2, 70, 1);

-- --------------------------------------------------------

--
-- Table structure for table `log_bulanan`
--

CREATE TABLE IF NOT EXISTS `log_bulanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pend` int(11) NOT NULL,
  `lk` int(11) NOT NULL,
  `pr` int(11) NOT NULL,
  `kk` int(11) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `log_bulanan`
--

INSERT INTO `log_bulanan` (`id`, `pend`, `lk`, `pr`, `kk`, `tgl`) VALUES
(1, 0, 0, 0, 0, '2016-05-21 09:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `log_penduduk`
--

CREATE TABLE IF NOT EXISTS `log_penduduk` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_pend` int(11) NOT NULL,
  `id_detail` int(4) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bulan` varchar(2) NOT NULL,
  `tahun` varchar(4) NOT NULL,
  `tgl_peristiwa` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_pend` (`id_pend`,`id_detail`,`tgl_peristiwa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_perubahan_penduduk`
--

CREATE TABLE IF NOT EXISTS `log_perubahan_penduduk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pend` int(11) NOT NULL,
  `id_cluster` varchar(200) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_surat`
--

CREATE TABLE IF NOT EXISTS `log_surat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_format_surat` int(3) NOT NULL,
  `id_pend` int(11) NOT NULL,
  `id_pamong` int(4) NOT NULL,
  `id_user` int(4) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bulan` varchar(2) DEFAULT NULL,
  `tahun` varchar(4) DEFAULT NULL,
  `no_surat` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE IF NOT EXISTS `lokasi` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `media_sosial`
--

CREATE TABLE IF NOT EXISTS `media_sosial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gambar` text NOT NULL,
  `link` text NOT NULL,
  `nama` varchar(100) NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `media_sosial`
--

INSERT INTO `media_sosial` (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES
(1, 'fb.png', 'https://www.facebook.com/combine.ri', 'Facebook', 1),
(2, 'twt.png', 'https://twitter.com/combineri', 'Twitter', 1),
(3, 'goo.png', 'http://plus.google.com/', 'Google Pluss', 1),
(4, 'yb.png', 'http://www.youtube.com/channel/UCk3eN9fI_eLGYzAn_lOlgXQ', 'Youtube', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `link` varchar(500) NOT NULL,
  `tipe` int(4) NOT NULL,
  `parrent` int(4) NOT NULL DEFAULT '1',
  `link_tipe` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=110 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`) VALUES
(16, 'Profil Desa', 'artikel/32', 1, 1, 1, 1),
(17, 'Pemerintahan Desa', 'artikel/85', 1, 1, 1, 1),
(19, 'Lembaga Masyarakat', 'artikel/38', 1, 1, 1, 1),
(23, 'Teras Desa', '', 2, 1, 1, 1),
(24, 'Data Desa', 'artikel/82', 1, 1, 1, 1),
(31, 'Data Wilayah Administratif', 'statistik/15', 3, 24, 1, 1),
(32, 'Data Pendidikan dalam KK', 'statistik/0', 3, 24, 1, 1),
(33, 'Data Pendidikan Ditempuh', 'statistik/17', 3, 24, 1, 1),
(34, 'Data Pekerjaan', 'statistik/1', 3, 24, 1, 1),
(35, 'Data Agama', 'statistik/3', 3, 24, 1, 1),
(36, 'Data Jenis Kelamin', 'statistik/4', 3, 24, 1, 1),
(40, 'Data Golongan Darah', 'statistik/7', 3, 24, 1, 1),
(51, 'Data Kelompok Umur', 'statistik/12', 3, 24, 1, 1),
(52, 'Data Penerima Raskin', 'statistik_k/2', 3, 24, 1, 1),
(53, 'Data Penerima Jamkesmas', 'statistik_k/3', 3, 24, 1, 1),
(54, 'Sejarah Desa', 'artikel/57', 3, 16, 1, 1),
(55, 'Profil Wilayah Desa', 'artikel/33', 3, 16, 1, 1),
(56, 'Profil Masyarakat Desa', 'artikel/34', 3, 16, 1, 1),
(57, 'Visi dan Misi', 'artikel/8', 3, 17, 1, 1),
(58, 'Pemerintah Desa', 'artikel/35', 3, 17, 1, 1),
(59, 'Badan Permusyawaratan Desa', 'artikel/37', 3, 17, 1, 1),
(62, 'Berita Desa', '', 2, 1, 1, 1),
(63, 'Agenda Desa', 'artikel/41', 2, 1, 1, 2),
(64, 'Peraturan Desa', 'peraturan', 2, 1, 1, 1),
(65, 'Panduan Layanan Desa', '#', 2, 1, 1, 1),
(66, 'Produk Desa', 'produk', 2, 1, 1, 1),
(68, 'Undang undang', 'artikel/42', 3, 64, 1, 2),
(69, 'Peraturan Pemerintah', 'artikel/43', 3, 64, 1, 2),
(70, 'Peraturan Daerah', '', 3, 64, 1, 2),
(71, 'Peraturan Bupati', '', 3, 64, 1, 2),
(72, 'Peraturan Bersama KaDes', '', 3, 64, 1, 2),
(73, 'Informasi Publik', '#', 2, 1, 1, 1),
(75, 'Rencana Kerja Anggaran', '', 3, 73, 1, 1),
(76, 'RAPB Desa', '', 3, 73, 1, 1),
(77, 'APB Desa', '', 3, 73, 1, 1),
(78, 'DPA', '', 3, 73, 1, 1),
(80, 'Profil Potensi Desa', 'artikel/59', 3, 16, 1, 1),
(84, 'LKMD', 'artikel/62', 3, 18, 1, 1),
(85, 'PKK', 'artikel/63', 3, 18, 1, 1),
(86, 'Karang Taruna', 'artikel/64', 3, 18, 1, 1),
(87, 'RT RW', 'artikel/65', 3, 18, 1, 1),
(88, 'Linmas', 'artikel/70', 3, 18, 1, 1),
(89, 'TKP2KDes', 'artikel/66', 3, 18, 1, 1),
(90, 'KPAD', 'artikel/67', 3, 18, 1, 1),
(91, 'Kelompok Ternak', 'artikel/68', 3, 18, 1, 1),
(92, 'Kelompok Tani', 'artikel/69', 3, 18, 1, 1),
(93, 'Kelompok Ekonomi Lainya', 'artikel/71', 3, 18, 1, 1),
(98, 'LKPJ', '', 3, 73, 1, 1),
(99, 'LPPD', '', 3, 73, 1, 1),
(100, 'ILPPD', '', 3, 73, 1, 1),
(101, 'Peraturan Desa', 'artikel/44', 3, 64, 1, 2),
(102, 'Peraturan Kepala Desa', 'artikel/45', 3, 64, 1, 2),
(103, 'Keputusan Kepala Desa', 'artikel/46', 3, 64, 1, 2),
(104, 'PBB', '', 3, 73, 1, 1),
(106, 'Data Warga Negara', 'statistik/13', 3, 24, 1, 1),
(108, 'Data Kelas Sosial', 'statistik_k/1', 3, 24, 1, 2),
(109, 'Kontak', 'artikel/36', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `outbox`
--

CREATE TABLE IF NOT EXISTS `outbox` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pertanyaan`
--

CREATE TABLE IF NOT EXISTS `pertanyaan` (
  `1` int(2) DEFAULT NULL,
  `Pendapatan perkapita perbulan` varchar(87) DEFAULT NULL,
  `36` int(2) DEFAULT NULL,
  `15` int(2) DEFAULT NULL,
  `24` int(2) DEFAULT NULL,
  `23` int(2) DEFAULT NULL,
  `26` int(2) DEFAULT NULL,
  `28` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `point`
--

CREATE TABLE IF NOT EXISTS `point` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `tipe` int(4) NOT NULL,
  `parrent` int(4) NOT NULL DEFAULT '1',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parrent` (`parrent`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `point`
--

INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES
(1, 'Sarana Pendidikan', 'face-embarrassed.png', 0, 1, 1),
(2, 'Sarana Transportasi', 'face-devilish.png', 0, 1, 1),
(3, 'Sarana Kesehatan', 'emblem-photos.png', 0, 1, 1),
(4, 'SMA', 'gateswalls.png', 2, 38, 1),
(5, 'SMP (Sekolah Menengah Pertama)', 'arch.png', 2, 38, 1),
(6, 'Masjid', 'mosque.png', 2, 54, 1),
(7, 'Tempat Ibadah', 'emblem-art.png', 0, 1, 1),
(8, 'Kuil', 'moderntower.png', 2, 54, 1),
(9, 'RS', 'accerciser.png', 2, 40, 1),
(10, 'Sarana Pendidikan', 'cabin.png', 2, 38, 1);

-- --------------------------------------------------------

--
-- Table structure for table `polygon`
--

CREATE TABLE IF NOT EXISTS `polygon` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT 'ff0000',
  `tipe` int(4) NOT NULL,
  `parrent` int(4) DEFAULT '1',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parrent` (`parrent`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `polygon`
--

INSERT INTO `polygon` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES
(1, 'rawan topan', '', '7C78FF', 0, 1, 1),
(2, 'jalur selokan', '', 'F4FF59', 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ref_bedah_rumah`
--

CREATE TABLE IF NOT EXISTS `ref_bedah_rumah` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ref_bedah_rumah`
--

INSERT INTO `ref_bedah_rumah` (`id`, `nama`) VALUES
(1, 'Ya'),
(2, 'Tidak');

-- --------------------------------------------------------

--
-- Table structure for table `ref_blt`
--

CREATE TABLE IF NOT EXISTS `ref_blt` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ref_blt`
--

INSERT INTO `ref_blt` (`id`, `nama`) VALUES
(1, 'Ya'),
(2, 'Tidak');

-- --------------------------------------------------------

--
-- Table structure for table `ref_jamkesmas`
--

CREATE TABLE IF NOT EXISTS `ref_jamkesmas` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `nama` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ref_jamkesmas`
--

INSERT INTO `ref_jamkesmas` (`id`, `nama`) VALUES
(1, 'Ya'),
(2, 'Tidak'),
(3, 'LAINNYA');

-- --------------------------------------------------------

--
-- Table structure for table `ref_kelas_sosial`
--

CREATE TABLE IF NOT EXISTS `ref_kelas_sosial` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ref_kelas_sosial`
--

INSERT INTO `ref_kelas_sosial` (`id`, `nama`) VALUES
(1, 'Tidak Miskin'),
(2, 'Sedang'),
(3, 'Miskin'),
(4, 'Sangat Miskin');

-- --------------------------------------------------------

--
-- Table structure for table `ref_pkh`
--

CREATE TABLE IF NOT EXISTS `ref_pkh` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ref_pkh`
--

INSERT INTO `ref_pkh` (`id`, `nama`) VALUES
(1, 'Ya'),
(2, 'Tidak');

-- --------------------------------------------------------

--
-- Table structure for table `ref_raskin`
--

CREATE TABLE IF NOT EXISTS `ref_raskin` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ref_raskin`
--

INSERT INTO `ref_raskin` (`id`, `nama`) VALUES
(1, 'Ya'),
(2, 'Tidak');

-- --------------------------------------------------------

--
-- Table structure for table `sentitems`
--

CREATE TABLE IF NOT EXISTS `sentitems` (
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

-- --------------------------------------------------------

--
-- Table structure for table `setting_sms`
--

CREATE TABLE IF NOT EXISTS `setting_sms` (
  `autoreply_text` varchar(160) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting_sms`
--

INSERT INTO `setting_sms` (`autoreply_text`) VALUES
('Terima kasih pesan Anda telah kami terima.');

-- --------------------------------------------------------

--
-- Table structure for table `sys_traffic`
--

CREATE TABLE IF NOT EXISTS `sys_traffic` (
  `Tanggal` date NOT NULL,
  `ipAddress` text NOT NULL,
  `Jumlah` int(10) NOT NULL,
  PRIMARY KEY (`Tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_traffic`
--

INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES
('2014-11-15', '::1{}', 1),
('2014-11-16', '::1{}', 1),
('2014-11-18', '', 3),
('2014-11-21', '', 3),
('2016-05-21', '::1{}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tweb_alamat_sekarang`
--

CREATE TABLE IF NOT EXISTS `tweb_alamat_sekarang` (
  `id` int(11) NOT NULL,
  `jalan` varchar(100) NOT NULL,
  `rt` varchar(100) NOT NULL,
  `rw` varchar(100) NOT NULL,
  `dusun` varchar(100) NOT NULL,
  `desa` varchar(100) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kabupaten` varchar(100) NOT NULL,
  `provinsi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_cacat`
--

CREATE TABLE IF NOT EXISTS `tweb_cacat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tweb_cacat`
--

INSERT INTO `tweb_cacat` (`id`, `nama`) VALUES
(1, 'CACAT FISIK'),
(2, 'CACAT NETRA/BUTA'),
(3, 'CACAT RUNGU/WICARA'),
(4, 'CACAT MENTAL/JIWA'),
(5, 'CACAT FISIK DAN MENTAL'),
(6, 'CACAT LAINNYA'),
(7, 'TIDAK CACAT');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_desa_pamong`
--

CREATE TABLE IF NOT EXISTS `tweb_desa_pamong` (
  `pamong_id` int(5) NOT NULL AUTO_INCREMENT,
  `pamong_nama` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_nip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_nik` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `jabatan` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0',
  `pamong_status` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pamong_tgl_terdaftar` date DEFAULT NULL,
  PRIMARY KEY (`pamong_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `tweb_desa_pamong`
--

INSERT INTO `tweb_desa_pamong` (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`) VALUES
(14, 'Samiran', '', '', 'Kepala Desa', '1', '2014-04-20');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_golongan_darah`
--

CREATE TABLE IF NOT EXISTS `tweb_golongan_darah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `tweb_golongan_darah`
--

INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'AB'),
(4, 'O'),
(5, 'A+'),
(6, 'A-'),
(7, 'B+'),
(8, 'B-'),
(9, 'AB+'),
(10, 'AB-'),
(11, 'O+'),
(12, 'O-'),
(13, 'TIDAK TAHU');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_keluarga`
--

CREATE TABLE IF NOT EXISTS `tweb_keluarga` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `no_kk` varchar(160) DEFAULT NULL,
  `nik_kepala` varchar(200) DEFAULT NULL,
  `tgl_daftar` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `kelas_sosial` int(4) DEFAULT NULL,
  `raskin` int(4) NOT NULL DEFAULT '2',
  `id_bedah_rumah` int(2) NOT NULL DEFAULT '2',
  `id_pkh` int(2) NOT NULL DEFAULT '2',
  `id_blt` int(2) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `nik_kepala` (`nik_kepala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `nik` decimal(16,0) NOT NULL,
  `id_kk` int(11) DEFAULT '0',
  `kk_level` tinyint(2) NOT NULL DEFAULT '0',
  `id_rtm` int(11) NOT NULL,
  `rtm_level` int(11) NOT NULL,
  `sex` tinyint(4) unsigned DEFAULT NULL,
  `tempatlahir` varchar(100) NOT NULL,
  `tanggallahir` date NOT NULL,
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
  `jamkesmas` int(11) NOT NULL DEFAULT '2',
  `akta_lahir` varchar(40) NOT NULL,
  `akta_perkawinan` varchar(40) NOT NULL,
  `tanggalperkawinan` date NOT NULL,
  `akta_perceraian` varchar(40) NOT NULL,
  `tanggalperceraian` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_agama`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_agama` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tweb_penduduk_agama`
--

INSERT INTO `tweb_penduduk_agama` (`id`, `nama`) VALUES
(1, 'ISLAM'),
(2, 'KRISTEN'),
(3, 'KATHOLIK'),
(4, 'HINDU'),
(5, 'BUDHA'),
(6, 'KHONGHUCU'),
(7, 'Kepercayaan Terhadap Tuhan YME / Lainnya');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_hubungan`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_hubungan` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tweb_penduduk_hubungan`
--

INSERT INTO `tweb_penduduk_hubungan` (`id`, `nama`) VALUES
(1, 'KEPALA KELUARGA'),
(2, 'SUAMI'),
(3, 'ISTRI'),
(4, 'ANAK'),
(5, 'MENANTU'),
(6, 'CUCU'),
(7, 'ORANGTUA'),
(8, 'MERTUA'),
(9, 'FAMILI LAIN'),
(10, 'PEMBANTU'),
(11, 'LAINNYA');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_kawin`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_kawin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tweb_penduduk_kawin`
--

INSERT INTO `tweb_penduduk_kawin` (`id`, `nama`) VALUES
(1, 'BELUM KAWIN'),
(2, 'KAWIN'),
(3, 'CERAI HIDUP'),
(4, 'CERAI MATI');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_pekerjaan`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_pekerjaan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=89 ;

--
-- Dumping data for table `tweb_penduduk_pekerjaan`
--

INSERT INTO `tweb_penduduk_pekerjaan` (`id`, `nama`) VALUES
(1, 'BELUM/TIDAK BEKERJA'),
(2, 'MENGURUS RUMAH TANGGA'),
(3, 'PELAJAR/MAHASISWA'),
(4, 'PENSIUNAN'),
(5, 'PEGAWAI NEGERI SIPIL (PNS)'),
(6, 'TENTARA NASIONAL INDONESIA (TNI)'),
(7, 'KEPOLISIAN RI (POLRI)'),
(8, 'PERDAGANGAN'),
(9, 'PETANI/PERKEBUNAN'),
(10, 'PETERNAK'),
(11, 'NELAYAN/PERIKANAN'),
(12, 'INDUSTRI'),
(13, 'KONSTRUKSI'),
(14, 'TRANSPORTASI'),
(15, 'KARYAWAN SWASTA'),
(16, 'KARYAWAN BUMN'),
(17, 'KARYAWAN BUMD'),
(18, 'KARYAWAN HONORER'),
(19, 'BURUH HARIAN LEPAS'),
(20, 'BURUH TANI/PERKEBUNAN'),
(21, 'BURUH NELAYAN/PERIKANAN'),
(22, 'BURUH PETERNAKAN'),
(23, 'PEMBANTU RUMAH TANGGA'),
(24, 'TUKANG CUKUR'),
(25, 'TUKANG LISTRIK'),
(26, 'TUKANG BATU'),
(27, 'TUKANG KAYU'),
(28, 'TUKANG SOL SEPATU'),
(29, 'TUKANG LAS/PANDAI BESI'),
(30, 'TUKANG JAHIT'),
(31, 'TUKANG GIGI'),
(32, 'PENATA RIAS'),
(33, 'PENATA BUSANA'),
(34, 'PENATA RAMBUT'),
(35, 'MEKANIK'),
(36, 'SENIMAN'),
(37, 'TABIB'),
(38, 'PARAJI'),
(39, 'PERANCANG BUSANA'),
(40, 'PENTERJEMAH'),
(41, 'IMAM MASJID'),
(42, 'PENDETA'),
(43, 'PASTOR'),
(44, 'WARTAWAN'),
(45, 'USTADZ/MUBALIGH'),
(46, 'JURU MASAK'),
(47, 'PROMOTOR ACARA'),
(48, 'ANGGOTA DPR-RI'),
(49, 'ANGGOTA DPD'),
(50, 'ANGGOTA BPK'),
(51, 'PRESIDEN'),
(52, 'WAKIL PRESIDEN'),
(53, 'ANGGOTA MAHKAMAH KONSTITUSI'),
(54, 'ANGGOTA KABINET KEMENTERIAN'),
(55, 'DUTA BESAR'),
(56, 'GUBERNUR'),
(57, 'WAKIL GUBERNUR'),
(58, 'BUPATI'),
(59, 'WAKIL BUPATI'),
(60, 'WALIKOTA'),
(61, 'WAKIL WALIKOTA'),
(62, 'ANGGOTA DPRD PROVINSI'),
(63, 'ANGGOTA DPRD KABUPATEN/KOTA'),
(64, 'DOSEN'),
(65, 'GURU'),
(66, 'PILOT'),
(67, 'PENGACARA'),
(68, 'NOTARIS'),
(69, 'ARSITEK'),
(70, 'AKUNTAN'),
(71, 'KONSULTAN'),
(72, 'DOKTER'),
(73, 'BIDAN'),
(74, 'PERAWAT'),
(75, 'APOTEKER'),
(76, 'PSIKIATER/PSIKOLOG'),
(77, 'PENYIAR TELEVISI'),
(78, 'PENYIAR RADIO'),
(79, 'PELAUT'),
(80, 'PENELITI'),
(81, 'SOPIR'),
(82, 'PIALANG'),
(83, 'PARANORMAL'),
(84, 'PEDAGANG'),
(85, 'PERANGKAT DESA'),
(86, 'KEPALA DESA'),
(87, 'BIARAWATI'),
(88, 'WIRASWASTA');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_pendidikan`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_pendidikan` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=19 ;

--
-- Dumping data for table `tweb_penduduk_pendidikan`
--

INSERT INTO `tweb_penduduk_pendidikan` (`id`, `nama`) VALUES
(1, 'BELUM MASUK TK/KELOMPOK BERMAIN'),
(2, 'SEDANG TK/KELOMPOK BERMAIN'),
(3, 'TIDAK PERNAH SEKOLAH'),
(4, 'SEDANG SD/SEDERAJAT'),
(5, 'TIDAK TAMAT SD/SEDERAJAT'),
(6, 'SEDANG SLTP/SEDERAJAT'),
(7, 'SEDANG SLTA/SEDERAJAT'),
(8, 'SEDANG  D-1/SEDERAJAT'),
(9, 'SEDANG D-2/SEDERAJAT'),
(10, 'SEDANG D-3/SEDERAJAT'),
(11, 'SEDANG  S-1/SEDERAJAT'),
(12, 'SEDANG S-2/SEDERAJAT'),
(13, 'SEDANG S-3/SEDERAJAT'),
(14, 'SEDANG SLB A/SEDERAJAT'),
(15, 'SEDANG SLB B/SEDERAJAT'),
(16, 'SEDANG SLB C/SEDERAJAT'),
(17, 'TIDAK DAPAT MEMBACA DAN MENULIS HURUF LATIN/ARAB'),
(18, 'TIDAK SEDANG SEKOLAH');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_pendidikan_kk`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_pendidikan_kk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tweb_penduduk_pendidikan_kk`
--

INSERT INTO `tweb_penduduk_pendidikan_kk` (`id`, `nama`) VALUES
(1, 'TIDAK / BELUM SEKOLAH'),
(2, 'BELUM TAMAT SD/SEDERAJAT'),
(3, 'TAMAT SD / SEDERAJAT'),
(4, 'SLTP/SEDERAJAT'),
(5, 'SLTA / SEDERAJAT'),
(6, 'DIPLOMA I / II'),
(7, 'AKADEMI/ DIPLOMA III/S. MUDA'),
(8, 'DIPLOMA IV/ STRATA I'),
(9, 'STRATA II'),
(10, 'STRATA III');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_sex`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_sex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tweb_penduduk_sex`
--

INSERT INTO `tweb_penduduk_sex` (`id`, `nama`) VALUES
(1, 'LAKI-LAKI'),
(2, 'PEREMPUAN');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_status`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tweb_penduduk_status`
--

INSERT INTO `tweb_penduduk_status` (`id`, `nama`) VALUES
(1, 'TETAP'),
(2, 'TIDAK AKTIF'),
(3, 'PENDATANG');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_umur`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_umur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(25) DEFAULT NULL,
  `dari` int(11) DEFAULT NULL,
  `sampai` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `tweb_penduduk_umur`
--

INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES
(1, 'BALITA', 0, 5, NULL),
(2, 'ANAK-ANAK', 6, 17, NULL),
(3, 'DEWASA', 18, 30, NULL),
(4, 'TUA', 31, 120, NULL),
(6, 'Bayi ( < 1 ) Tahun', 0, 1, 1),
(9, 'Balita ( 2 > 4 ) Tahun', 2, 4, 1),
(12, 'Anak-anak ( 5 > 9 ) Tahun', 5, 9, 1),
(13, 'Anak-anak ( 10 > 14 ) Tah', 10, 14, 1),
(14, 'Remaja ( 15 > 19 ) Tahun', 15, 19, 1),
(15, 'Remaja ( 20 > 24 ) Tahun', 20, 24, 1),
(16, 'Dewasa ( 25 > 29 ) Tahun', 25, 29, 1),
(17, 'Dewasa ( 30 > 34 ) Tahun', 30, 34, 1),
(18, 'Dewasa ( 35 > 39 ) Tahun ', 35, 39, 1),
(19, 'Dewasa ( 40 > 44 ) Tahun', 40, 44, 1),
(20, 'Tua ( 45 > 49 ) Tahun', 45, 49, 1),
(21, 'Tua ( 50 > 54 ) Tahun', 50, 54, 1),
(22, 'Tua ( 55 > 59 ) Tahun', 55, 59, 1),
(23, 'Tua ( 60 > 64 ) Tahun', 60, 64, 1),
(24, 'Tua ( 65 > 69 ) Tahun', 65, 69, 1),
(25, 'Tua ( 70 > 74 ) Tahun', 70, 74, 1),
(26, 'Lansia ( > 75 ) Tahun', 75, 130, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_warganegara`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_warganegara` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tweb_penduduk_warganegara`
--

INSERT INTO `tweb_penduduk_warganegara` (`id`, `nama`) VALUES
(1, 'WNI'),
(2, 'WNA'),
(3, 'DUA KEWARGANEGARAAN');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_rtm`
--

CREATE TABLE IF NOT EXISTS `tweb_rtm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik_kepala` int(11) NOT NULL,
  `no_kk` varchar(20) NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `kelas_sosial` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_rtm_hubungan`
--

CREATE TABLE IF NOT EXISTS `tweb_rtm_hubungan` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tweb_rtm_hubungan`
--

INSERT INTO `tweb_rtm_hubungan` (`id`, `nama`) VALUES
(1, 'Kepala Rumah Tangga'),
(2, 'Anggota');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_sakit_menahun`
--

CREATE TABLE IF NOT EXISTS `tweb_sakit_menahun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `tweb_sakit_menahun`
--

INSERT INTO `tweb_sakit_menahun` (`id`, `nama`) VALUES
(1, 'JANTUNG'),
(2, 'LEVER'),
(3, 'PARU-PARU'),
(4, 'KANKER'),
(5, 'STROKE'),
(6, 'DIABETES MELITUS'),
(7, 'GINJAL'),
(8, 'MALARIA'),
(9, 'LEPRA/KUSTA'),
(10, 'HIV/AIDS'),
(11, 'GILA/STRESS'),
(12, 'TBC'),
(13, 'ASTHMA'),
(14, 'TIDAK ADA/TIDAK SAKIT');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_status_dasar`
--

CREATE TABLE IF NOT EXISTS `tweb_status_dasar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tweb_status_dasar`
--

INSERT INTO `tweb_status_dasar` (`id`, `nama`) VALUES
(1, 'HIDUP'),
(2, 'MATI'),
(3, 'PINDAH'),
(4, 'HILANG');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_surat_format`
--

CREATE TABLE IF NOT EXISTS `tweb_surat_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `url_surat` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `tweb_surat_format`
--

INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`) VALUES
(1, 'Keterangan Pengantar', 'surat_ket_pengantar'),
(2, 'Keterangan Penduduk', 'surat_ket_penduduk'),
(3, 'Biodata Penduduk', 'surat_bio_penduduk'),
(5, 'Keterangan Pindah Penduduk', 'surat_ket_pindah_penduduk'),
(6, 'Keterangan Jual Beli', 'surat_ket_jual_beli'),
(7, 'Pengantar Pindah Antar Kabupaten/ Provinsi', 'surat_pindah_antar_kab_prov'),
(8, 'Pengantar SKCK', 'surat_ket_catatan_kriminal'),
(9, 'Keterangan KTP dalam Proses', 'surat_ket_ktp_dlm_proses'),
(10, 'Keterangan Beda Nama', 'surat_ket_beda_nama'),
(11, 'Keterangan Bepergian / Jalan', 'surat_jalan'),
(12, 'Keterangan Kurang Mampu', 'surat_ket_kurang_mampu'),
(13, 'Pengantar Izin Keramaian', 'surat_izin_keramaian'),
(14, 'Pengantar Laporan Kehilangan', 'surat_lap_kehilangan'),
(15, 'Keterangan Usaha', 'surat_ket_usaha'),
(16, 'Keterangan JAMKESOS', 'surat_ket_jamkesos'),
(17, 'Keterangan Domisili Usaha', 'surat_ket_domisili_usaha'),
(18, 'Keterangan Kelahiran', 'surat_ket_kelahiran'),
(19, 'Keterangan Kehilangan', 'surat_ket_kehilangan'),
(20, 'Permohonan Akta Lahir', 'surat_permohonan_akta'),
(21, 'Pernyataan Akta Lahir', 'surat_pernyataan_akta'),
(22, 'Permohonan Duplikat Kelahiran', 'surat_permohonan_duplikat_kelahiran'),
(24, 'Keterangan Kematian', 'surat_ket_kematian'),
(25, 'Keterangan Lahir Mati', 'surat_ket_lahir_mati'),
(26, 'Keterangan Untuk Nikah (N-1)', 'surat_ket_nikah'),
(27, 'Keterangan Asal Usul (N-2)', 'surat_ket_asalusul'),
(28, 'Persetujuan Mempelai (N-3)', 'surat_persetujuan_mempelai'),
(29, 'Keterangan Tentang Orang Tua (N-4)', 'surat_ket_orangtua'),
(30, 'Izin Orang Tua(N-5)', 'surat_izin_orangtua'),
(31, 'Keterangan Kematian Suami/Istri(N-6)', 'surat_ket_kematian_suami_istri'),
(32, 'Pemberitahuan Kehendak Nikah (N-7)', 'surat_kehendak_nikah'),
(33, 'Keterangan Pergi Kawin', 'surat_ket_pergi_kawin'),
(34, 'Keterangan Wali', 'surat_wali'),
(35, 'Keterangan Wali Hakim', 'surat_wali_hakim'),
(36, 'Pengantar Permohonan Duplikat Surat Nikah', 'surat_permohonan_duplikat_surat_nikah'),
(37, 'Permohonan Cerai', 'surat_permohonan_cerai'),
(38, 'Keterangan Pengantar Rujuk/Cerai', 'surat_ket_rujuk_cerai'),
(39, 'Ubah Sesuaikan', 'surat_ubah_sesuaikan');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_wil_clusterdesa`
--

CREATE TABLE IF NOT EXISTS `tweb_wil_clusterdesa` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `id_grup`, `email`, `last_login`, `active`, `nama`, `company`, `phone`, `foto`, `session`) VALUES
(1, 'admin', 'edc64352387aa108dac115ec1493d5d4', 1, 'admin@combine.or.id', '2014-11-22 02:55:27', 1, 'Administrator', 'ADMIN', '321', 'favicon.png', 'a8d4080245664ed2049c1b2ded7cac30'),
(56, 'redaksi', 'd8578edf8458ce06fbc5bb76a58c5ca4', 3, '', '2014-10-24 20:15:38', 1, 'Redaksi', NULL, '', '', '39b3cc1ca3f8b095a171b19b1f307358'),
(57, 'operator', 'd8578edf8458ce06fbc5bb76a58c5ca4', 2, '', '2014-10-24 20:17:42', 1, 'Operator Desa', NULL, '', '', '9304194147916440da8be6d8f26f62f0'),
(58, 'Master Admin', 'ce632afb06b2ef65397b1aecf7bbd39c', 1, 'iariadi@gmail.com', '0000-00-00 00:00:00', 0, 'Admin', NULL, '0813299237471', '', 'de750f7c7aa3412540dcfb33a8218ccf');

-- --------------------------------------------------------

--
-- Table structure for table `user_grup`
--

CREATE TABLE IF NOT EXISTS `user_grup` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_grup`
--

INSERT INTO `user_grup` (`id`, `nama`) VALUES
(1, 'Administrator'),
(2, 'Operator'),
(3, 'Redaksi');

-- --------------------------------------------------------

--
-- Structure for view `data_surat`
--
DROP TABLE IF EXISTS `data_surat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `data_surat` AS select `u`.`id` AS `id`,`u`.`nama` AS `nama`,`x`.`nama` AS `sex`,`u`.`tempatlahir` AS `tempatlahir`,`u`.`tanggallahir` AS `tanggallahir`,(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) from `tweb_penduduk` where (`tweb_penduduk`.`id` = `u`.`id`)) AS `umur`,`w`.`nama` AS `status_kawin`,`f`.`nama` AS `warganegara`,`a`.`nama` AS `agama`,`d`.`nama` AS `pendidikan`,`j`.`nama` AS `pekerjaan`,`u`.`nik` AS `nik`,`c`.`rt` AS `rt`,`c`.`rw` AS `rw`,`c`.`dusun` AS `dusun`,`k`.`no_kk` AS `no_kk`,(select `tweb_penduduk`.`nama` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `k`.`nik_kepala`)) AS `kepala_kk` from ((((((((`tweb_penduduk` `u` left join `tweb_penduduk_sex` `x` on((`u`.`sex` = `x`.`id`))) left join `tweb_penduduk_kawin` `w` on((`u`.`status_kawin` = `w`.`id`))) left join `tweb_penduduk_agama` `a` on((`u`.`agama_id` = `a`.`id`))) left join `tweb_penduduk_pendidikan_kk` `d` on((`u`.`pendidikan_kk_id` = `d`.`id`))) left join `tweb_penduduk_pekerjaan` `j` on((`u`.`pekerjaan_id` = `j`.`id`))) left join `tweb_wil_clusterdesa` `c` on((`u`.`id_cluster` = `c`.`id`))) left join `tweb_keluarga` `k` on((`u`.`id_kk` = `k`.`id`))) left join `tweb_penduduk_warganegara` `f` on((`u`.`warganegara_id` = `f`.`id`)));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
