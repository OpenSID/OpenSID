-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2019 at 11:23 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opensid1`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `id_artikel` int(11) NOT NULL,
  `tgl_agenda` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `koordinator_kegiatan` varchar(50) NOT NULL,
  `lokasi_kegiatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_indikator`
--

CREATE TABLE `analisis_indikator` (
  `id` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `nomor` int(3) NOT NULL,
  `pertanyaan` varchar(400) NOT NULL,
  `id_tipe` tinyint(4) NOT NULL DEFAULT 1,
  `bobot` tinyint(4) NOT NULL DEFAULT 0,
  `act_analisis` tinyint(1) NOT NULL DEFAULT 2,
  `id_kategori` tinyint(4) NOT NULL,
  `is_publik` tinyint(1) NOT NULL DEFAULT 0,
  `is_teks` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `analisis_indikator`
--

INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES
(0, 0, 1, 'Jumlah Penghasilan Perbulan', 3, 0, 0, 0, 0, 0),
(0, 0, 2, 'Jumlah Pengeluaran Perbulan', 3, 0, 0, 0, 0, 0),
(0, 0, 3, 'Status Kepemilikan Rumah?*', 1, 0, 0, 0, 0, 0),
(0, 0, 4, 'Kategori KK', 1, 0, 0, 0, 0, 0),
(0, 0, 5, 'Penerima Raskin', 1, 0, 0, 0, 0, 0),
(0, 0, 6, 'Penerima BLT/BLSM', 1, 0, 0, 0, 0, 0),
(0, 0, 7, 'Peserta BPJS/Jamkesmas/Jamkesda', 1, 0, 0, 0, 0, 0),
(0, 0, 8, 'Sumber Air Minum?*', 1, 0, 0, 0, 0, 0),
(0, 0, 9, 'Keterangan', 2, 0, 0, 0, 0, 0),
(0, 0, 10, 'Jenis Lahan', 1, 0, 0, 0, 0, 0),
(0, 0, 11, 'Luas Lahan', 1, 0, 0, 0, 0, 0),
(0, 0, 12, 'Jenis Komoditas', 1, 0, 0, 0, 0, 0),
(0, 0, 13, 'Produksi', 3, 0, 0, 0, 0, 0),
(0, 0, 14, 'Satuan', 1, 0, 0, 0, 0, 0),
(0, 0, 15, 'Nilai (Rp)', 3, 0, 0, 0, 0, 0),
(0, 0, 16, 'Pemasaran Hasil', 1, 0, 0, 0, 0, 0),
(0, 0, 17, 'Jenis Komoditas', 1, 0, 0, 0, 0, 0),
(0, 0, 18, 'Jumlah Pohon', 3, 0, 0, 0, 0, 0),
(0, 0, 19, 'Produksi', 3, 0, 0, 0, 0, 0),
(0, 0, 20, 'Satuan', 1, 0, 0, 0, 0, 0),
(0, 0, 21, 'Nilai (Rp)', 3, 0, 0, 0, 0, 0),
(0, 0, 22, 'Pemasaran Hasil', 1, 0, 0, 0, 0, 0),
(0, 0, 23, 'Jenis Komoditas', 1, 0, 0, 0, 0, 0),
(0, 0, 24, 'Produksi', 3, 0, 0, 0, 0, 0),
(0, 0, 25, 'Satuan', 1, 0, 0, 0, 0, 0),
(0, 0, 26, 'Nilai (Rp)', 3, 0, 0, 0, 0, 0),
(0, 0, 27, 'Pemasaran Hasil', 1, 0, 0, 0, 0, 0),
(0, 0, 28, 'Jenis Komoditas', 1, 0, 0, 0, 0, 0),
(0, 0, 29, 'Produksi', 3, 0, 0, 0, 0, 0),
(0, 0, 30, 'Satuan', 1, 0, 0, 0, 0, 0),
(0, 0, 31, 'Nilai (Rp)', 3, 0, 0, 0, 0, 0),
(0, 0, 32, 'Pemasaran Hasil', 1, 0, 0, 0, 0, 0),
(0, 0, 33, 'Jenis Komoditas', 1, 0, 0, 0, 0, 0),
(0, 0, 34, 'Produksi', 3, 0, 0, 0, 0, 0),
(0, 0, 35, 'Satuan', 1, 0, 0, 0, 0, 0),
(0, 0, 36, 'Nilai (Rp)', 3, 0, 0, 0, 0, 0),
(0, 0, 37, 'Pemasaran Hasil', 1, 0, 0, 0, 0, 0),
(0, 0, 38, 'Jenis Komoditas', 1, 0, 0, 0, 0, 0),
(0, 0, 39, 'Produksi', 3, 0, 0, 0, 0, 0),
(0, 0, 40, 'Satuan', 1, 0, 0, 0, 0, 0),
(0, 0, 41, 'Nilai (Rp)', 3, 0, 0, 0, 0, 0),
(0, 0, 42, 'Pemasaran Hasil', 1, 0, 0, 0, 0, 0),
(0, 0, 43, 'Jenis Komoditas', 1, 0, 0, 0, 0, 0),
(0, 0, 44, 'Produksi', 3, 0, 0, 0, 0, 0),
(0, 0, 45, 'Satuan', 1, 0, 0, 0, 0, 0),
(0, 0, 46, 'Nilai (Rp)', 3, 0, 0, 0, 0, 0),
(0, 0, 47, 'Pemasaran Hasil', 1, 0, 0, 0, 0, 0),
(0, 0, 48, 'Jenis Bahan Galian', 1, 0, 0, 0, 0, 0),
(0, 0, 49, 'Milik Perorangan (Ha)', 3, 0, 0, 0, 0, 0),
(0, 0, 50, 'Milik Adat (Ha)', 3, 0, 0, 0, 0, 0),
(0, 0, 51, 'Satuan', 1, 0, 0, 0, 0, 0),
(0, 0, 52, 'Pemasaran', 1, 0, 0, 0, 0, 0),
(0, 0, 53, 'Jenis Komoditas', 1, 0, 0, 0, 0, 0),
(0, 0, 54, 'Produksi', 3, 0, 0, 0, 0, 0),
(0, 0, 55, 'Satuan', 1, 0, 0, 0, 0, 0),
(0, 0, 56, 'Nilai (Rp)', 3, 0, 0, 0, 0, 0),
(0, 0, 57, 'Pemasaran Hasil', 1, 0, 0, 0, 0, 0),
(0, 0, 58, 'Nama Alat', 1, 0, 0, 0, 0, 0),
(0, 0, 59, 'Jumlah', 3, 0, 0, 0, 0, 0),
(0, 0, 60, 'Pemanfaatan Sungai/Waduk DLL', 2, 0, 0, 0, 0, 0),
(0, 0, 61, 'Lembaga Pendidikan', 2, 0, 0, 0, 0, 0),
(0, 0, 62, 'Penguasaan Aset Tanah', 1, 0, 0, 0, 0, 0),
(0, 0, 63, 'Aset Sarana Transportasi Umum', 2, 0, 0, 0, 0, 0),
(0, 0, 64, 'Aset Sarana Produksi', 2, 0, 0, 0, 0, 0),
(0, 0, 65, 'Aset Rumah (Dinding)', 1, 0, 0, 0, 0, 0),
(0, 0, 66, 'Aset Rumah (Lantai)', 1, 0, 0, 0, 0, 0),
(0, 0, 67, 'Aset Rumah (Atap)', 1, 0, 0, 0, 0, 0),
(0, 0, 68, 'Aset Lainnya', 2, 0, 0, 0, 0, 0),
(0, 0, 69, 'Kualitas Ibu Hamil', 2, 0, 0, 0, 0, 0),
(0, 0, 70, 'Kualitas Bayi', 2, 0, 0, 0, 0, 0),
(0, 0, 71, 'Tempat Persalinan', 2, 0, 0, 0, 0, 0),
(0, 0, 72, 'Pertolongan Persalinan', 2, 0, 0, 0, 0, 0),
(0, 0, 73, 'Cakupan Imunisasi', 2, 0, 0, 0, 0, 0),
(0, 0, 74, 'Penderita Sakit Kelainan', 2, 0, 0, 0, 0, 0),
(0, 0, 75, 'Perilaku Hidup Bersih', 1, 0, 0, 0, 0, 0),
(0, 0, 76, 'Pola Makan', 1, 0, 0, 0, 0, 0),
(0, 0, 77, 'Kebiasaan Berobat', 1, 0, 0, 0, 0, 0),
(0, 0, 78, 'Status Gizi Balita', 1, 0, 0, 0, 0, 0),
(0, 0, 79, 'Jenis Penyakit', 2, 0, 0, 0, 0, 0),
(0, 0, 80, 'Kerukunan', 2, 0, 0, 0, 0, 0),
(0, 0, 81, 'Perkelahian', 2, 0, 0, 0, 0, 0),
(0, 0, 82, 'Pencurian', 2, 0, 0, 0, 0, 0),
(0, 0, 83, 'Penjarahan', 2, 0, 0, 0, 0, 0),
(0, 0, 84, 'Perjudian', 2, 0, 0, 0, 0, 0),
(0, 0, 85, 'Pemakaian Miras dan Narkoba', 2, 0, 0, 0, 0, 0),
(0, 0, 86, 'Pembunuhan', 2, 0, 0, 0, 0, 0),
(0, 0, 87, 'Penculikan', 2, 0, 0, 0, 0, 0),
(0, 0, 88, 'Kejahatan Seksual', 2, 0, 0, 0, 0, 0),
(0, 0, 89, 'Kekerasan Dalam Rumah Tangga', 2, 0, 0, 0, 0, 0),
(0, 0, 90, 'Masalah Kesejahteraan Keluarga', 2, 0, 0, 0, 0, 0),
(0, 0, 1, 'Nomor Akte Kelahiran', 4, 0, 0, 0, 0, 0),
(0, 0, 2, 'Hubungan dengan Kepala Keluarga', 1, 0, 0, 0, 0, 0),
(0, 0, 3, 'Status Perkawinan', 1, 0, 0, 0, 0, 0),
(0, 0, 4, 'Agama dan Aliran Kepercayaan', 1, 0, 0, 0, 0, 0),
(0, 0, 5, 'Golongan Darah', 1, 0, 0, 0, 0, 0),
(0, 0, 6, 'Kewarganegaraan', 1, 0, 0, 0, 0, 0),
(0, 0, 7, 'Etnis/Suku', 4, 0, 0, 0, 0, 0),
(0, 0, 8, 'Pendidikan Umum Terakhir', 1, 0, 0, 0, 0, 0),
(0, 0, 9, 'Mata Pencaharian Pokok/Pekerjaan', 1, 0, 0, 0, 0, 0),
(0, 0, 10, 'Nama Bapak Kandung', 4, 0, 0, 0, 0, 0),
(0, 0, 11, 'Nama Ibu Kandung', 4, 0, 0, 0, 0, 0),
(0, 0, 12, 'Akseptor KB', 1, 0, 0, 0, 0, 0),
(0, 0, 13, 'Cacat Fisik', 2, 0, 0, 0, 0, 0),
(0, 0, 14, 'Cacat Mental', 2, 0, 0, 0, 0, 0),
(0, 0, 15, 'Kedudukan Anggota Keluarga sebagai Wajib Pajak dan Retribusi', 2, 0, 0, 0, 0, 0),
(0, 0, 16, 'Lembaga Pemerintahan Yang Diikuti Anggota Keluarga', 2, 0, 0, 0, 0, 0),
(0, 0, 17, 'Lembaga Kemasyarakatan Yang Diikuti Anggota Keluarga', 2, 0, 0, 0, 0, 0),
(0, 0, 18, 'Lembaga Ekonomi Yang Dimiliki Anggota Keluarga', 2, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `analisis_kategori_indikator`
--

CREATE TABLE `analisis_kategori_indikator` (
  `id` tinyint(4) NOT NULL,
  `id_master` tinyint(4) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `kategori_kode` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `analisis_kategori_indikator`
--

INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES
(0, 0, 'PENGHASILAN DAN PENGELUARAN KELUARGA', ''),
(0, 0, 'SUMBER AIR MINUM KELUARGA', ''),
(0, 0, 'KEPEMILIKAN LAHAN KELUARGA', ''),
(0, 0, 'PRODUKSI TANAMAN PANGAN', ''),
(0, 0, 'PRODUKSI BUAH-BUAHAN', ''),
(0, 0, 'PRODUKSI TANAMAN OBAT', ''),
(0, 0, 'PRODUKSI PERKEBUNAN', ''),
(0, 0, 'PRODUKSI HASIL HUTAN', ''),
(0, 0, 'JENIS TERNAK', ''),
(0, 0, 'PRODUKSI PERIKANAN', ''),
(0, 0, 'PRODUKSI BAHAN GALIAN', ''),
(0, 0, 'PENGOLAHAN HASIL TERNAK', ''),
(0, 0, 'ALAT PRODUKSI PERIKANAN', ''),
(0, 0, 'PEMANFAATAN AIR, ASET RUMAH DLL', ''),
(0, 0, 'Data Anggota Keluarga', '');

-- --------------------------------------------------------

--
-- Table structure for table `analisis_klasifikasi`
--

CREATE TABLE `analisis_klasifikasi` (
  `id` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `minval` double(5,2) NOT NULL,
  `maxval` double(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_master`
--

CREATE TABLE `analisis_master` (
  `id` int(11) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `subjek_tipe` tinyint(4) NOT NULL,
  `lock` tinyint(1) NOT NULL DEFAULT 1,
  `deskripsi` text NOT NULL,
  `kode_analisis` varchar(5) NOT NULL DEFAULT '00000',
  `id_kelompok` int(11) NOT NULL,
  `pembagi` varchar(10) NOT NULL DEFAULT '100',
  `id_child` smallint(4) NOT NULL,
  `format_impor` tinyint(2) NOT NULL,
  `jenis` tinyint(2) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `analisis_master`
--

INSERT INTO `analisis_master` (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`, `format_impor`, `jenis`) VALUES
(0, 'Data Dasar Keluarga (Prodeskel)', 2, 1, 'Pendataan Profil Desa', 'DDK02', 0, '', 0, 0, 1),
(0, 'Data Anggota Keluarga (Prodeskel)', 1, 1, 'Pendataan Profil Desa', 'DAK02', 0, '', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `analisis_parameter`
--

CREATE TABLE `analisis_parameter` (
  `id` int(11) NOT NULL,
  `id_indikator` int(11) NOT NULL,
  `jawaban` varchar(200) NOT NULL,
  `nilai` tinyint(4) NOT NULL DEFAULT 0,
  `kode_jawaban` int(3) NOT NULL,
  `asign` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `analisis_parameter`
--

INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES
(0, 0, 'Milik Sendiri', 0, 169, 1),
(0, 0, 'Milik Orang Tua', 0, 170, 1),
(0, 0, 'Milik Keluarga', 0, 171, 1),
(0, 0, 'Sewa/Kontrak', 0, 172, 1),
(0, 0, 'Pinjam Pakai', 0, 173, 1),
(0, 0, 'Pra Sejahtera', 0, 0, 1),
(0, 0, 'Sejahtera 1', 0, 1, 1),
(0, 0, 'Sejahtera 2', 0, 2, 1),
(0, 0, 'Sejahtera 3+', 0, 3, 1),
(0, 0, 'Ya', 0, 1, 1),
(0, 0, 'Tidak', 0, 0, 1),
(0, 0, 'Ya', 0, 1, 1),
(0, 0, 'Tidak', 0, 0, 1),
(0, 0, 'Ya', 0, 1, 1),
(0, 0, 'Tidak', 0, 0, 1),
(0, 0, 'Bak penampung air hujan', 0, 503, 1),
(0, 0, 'Beli dari tangki swasta', 0, 504, 1),
(0, 0, 'Depot isi ulang', 0, 505, 1),
(0, 0, 'Embung', 0, 502, 1),
(0, 0, 'Hidran umum', 0, 498, 1),
(0, 0, 'Mata air', 0, 495, 1),
(0, 0, 'PAM', 0, 499, 1),
(0, 0, 'Pipa', 0, 500, 1),
(0, 0, 'Sumber Air Resapan Umum', 0, 1741, 1),
(0, 0, 'Sumur gali', 0, 496, 1),
(0, 0, 'Sumur pompa', 0, 497, 1),
(0, 0, 'Sungai', 0, 501, 1),
(0, 0, 'Baik', 0, 1, 1),
(0, 0, 'Berasa', 0, 2, 1),
(0, 0, 'Berwarna', 0, 3, 1),
(0, 0, 'Berbau', 0, 4, 1),
(0, 0, 'Hutan', 0, 952, 1),
(0, 0, 'Perkebunan', 0, 951, 1),
(0, 0, 'Tanaman Pangan', 0, 950, 1),
(0, 0, 'Memiliki kurang 0,5 ha', 0, 1732, 1),
(0, 0, 'Memiliki 0,5 - 1,0 ha', 0, 1733, 1),
(0, 0, 'Memiliki lebih dari 1,0 ha', 0, 1734, 1),
(0, 0, 'Tidak memiliki', 0, 1735, 1),
(0, 0, 'Bawah Merah', 0, 12, 1),
(0, 0, 'Bawang Putih', 0, 13, 1),
(0, 0, 'Bayam', 0, 22, 1),
(0, 0, 'Brocoli', 0, 20, 1),
(0, 0, 'Buncis', 0, 19, 1),
(0, 0, 'Cabe', 0, 11, 1),
(0, 0, 'Jagung', 0, 1, 1),
(0, 0, 'Jamur', 0, 78, 1),
(0, 0, 'Jeruk Nipis', 0, 48, 1),
(0, 0, 'Kacang Hijau', 0, 253, 1),
(0, 0, 'Kacang Kedelai', 0, 2, 1),
(0, 0, 'Kacang Merah', 0, 6, 1),
(0, 0, 'Kacang Panjang', 0, 4, 1),
(0, 0, 'Kacang Tanah', 0, 3, 1),
(0, 0, 'Kacang Turis', 0, 24, 1),
(0, 0, 'Kangkung', 0, 23, 1),
(0, 0, 'Kemiri', 0, 96, 1),
(0, 0, 'Kentang', 0, 16, 1),
(0, 0, 'Kubis', 0, 17, 1),
(0, 0, 'Mentimun', 0, 18, 1),
(0, 0, 'Padi Ladang', 0, 8, 1),
(0, 0, 'Padi Sawah', 0, 7, 1),
(0, 0, 'Sawi', 0, 15, 1),
(0, 0, 'Selada', 0, 26, 1),
(0, 0, 'Terong', 0, 21, 1),
(0, 0, 'Tomat', 0, 14, 1),
(0, 0, 'Tumpang Sari', 0, 29, 1),
(0, 0, 'Ubi Jalar', 0, 10, 1),
(0, 0, 'Ubi Kayu', 0, 9, 1),
(0, 0, 'Umbi-Umbian Lain', 0, 25, 1),
(0, 0, 'Wortel', 0, 28, 1),
(0, 0, 'BATANG/TH', 0, 1746, 1),
(0, 0, 'BUAH/TH ', 0, 1013, 1),
(0, 0, 'EKOR/TH ', 0, 1745, 1),
(0, 0, 'JENIS/TH', 0, 965, 1),
(0, 0, 'KG/TH', 0, 960, 1),
(0, 0, 'LITER/TH', 0, 962, 1),
(0, 0, 'M/TH', 0, 963, 1),
(0, 0, 'M3/TH', 0, 961, 1),
(0, 0, 'TON/TH', 0, 966, 1),
(0, 0, 'UNIT/TH', 0, 964, 1),
(0, 0, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(0, 0, 'Dijual ke pasar', 0, 489, 1),
(0, 0, 'Dijual langsung ke konsumen', 0, 488, 1),
(0, 0, 'Dijual melalui KUD', 0, 490, 1),
(0, 0, 'Dijual melalui Pengecer', 0, 492, 1),
(0, 0, 'Dijual melalui Tengkulak', 0, 491, 1),
(0, 0, 'Tidak dijual', 0, 494, 1),
(0, 0, 'Alpokat', 0, 31, 1),
(0, 0, 'Anggur', 0, 54, 1),
(0, 0, 'Apel', 0, 36, 1),
(0, 0, 'Belimbing', 0, 38, 1),
(0, 0, 'Duku', 0, 41, 1),
(0, 0, 'Durian', 0, 39, 1),
(0, 0, 'Gandaria', 0, 258, 1),
(0, 0, 'Jambu air', 0, 50, 1),
(0, 0, 'Jambu klutuk', 0, 57, 1),
(0, 0, 'Jambu Mete', 0, 88, 1),
(0, 0, 'Jeruk', 0, 30, 1),
(0, 0, 'Kedondong', 0, 53, 1),
(0, 0, 'Kesemek', 0, 257, 1),
(0, 0, 'Kokosan', 0, 42, 1),
(0, 0, 'Lengkeng', 0, 45, 1),
(0, 0, 'Limau', 0, 47, 1),
(0, 0, 'Mangga', 0, 32, 1),
(0, 0, 'Manggis', 0, 34, 1),
(0, 0, 'Markisa', 0, 44, 1),
(0, 0, 'Matoa', 0, 249, 1),
(0, 0, 'Melinjo', 0, 55, 1),
(0, 0, 'Melon', 0, 49, 1),
(0, 0, 'Murbei', 0, 58, 1),
(0, 0, 'Nangka', 0, 51, 1),
(0, 0, 'Nenas', 0, 56, 1),
(0, 0, 'Pepaya', 0, 37, 1),
(0, 0, 'Pisang', 0, 43, 1),
(0, 0, 'Rambutan', 0, 33, 1),
(0, 0, 'Salak', 0, 35, 1),
(0, 0, 'Sawo', 0, 40, 1),
(0, 0, 'Semangka', 0, 46, 1),
(0, 0, 'Sirsak', 0, 52, 1),
(0, 0, 'Stroberi', 0, 255, 1),
(0, 0, 'Talas', 0, 27, 1),
(0, 0, 'BATANG/TH', 0, 1746, 1),
(0, 0, 'BUAH/TH ', 0, 1013, 1),
(0, 0, 'EKOR/TH ', 0, 1745, 1),
(0, 0, 'JENIS/TH', 0, 965, 1),
(0, 0, 'KG/TH', 0, 960, 1),
(0, 0, 'LITER/TH', 0, 962, 1),
(0, 0, 'M/TH', 0, 963, 1),
(0, 0, 'M3/TH', 0, 961, 1),
(0, 0, 'TON/TH', 0, 966, 1),
(0, 0, 'UNIT/TH', 0, 964, 1),
(0, 0, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(0, 0, 'Dijual ke pasar', 0, 489, 1),
(0, 0, 'Dijual langsung ke konsumen', 0, 488, 1),
(0, 0, 'Dijual melalui KUD', 0, 490, 1),
(0, 0, 'Dijual melalui Pengecer', 0, 492, 1),
(0, 0, 'Dijual melalui Tengkulak', 0, 491, 1),
(0, 0, 'Tidak dijual', 0, 494, 1),
(0, 0, 'Akar Wangi', 0, 76, 1),
(0, 0, 'Buah Merah', 0, 65, 1),
(0, 0, 'Daun Dewa', 0, 63, 1),
(0, 0, 'Daun Sereh', 0, 74, 1),
(0, 0, 'Daun Sirih', 0, 72, 1),
(0, 0, 'Dewi-Dewi', 0, 79, 1),
(0, 0, 'Jahe', 0, 59, 1),
(0, 0, 'Jamur', 0, 252, 1),
(0, 0, 'Kayu Manis', 0, 73, 1),
(0, 0, 'Kencur', 0, 77, 1),
(0, 0, 'Kumis Kucing', 0, 64, 1),
(0, 0, 'Kunyit', 0, 60, 1),
(0, 0, 'Lengkuas', 0, 61, 1),
(0, 0, 'Mahkota Dewa', 0, 75, 1),
(0, 0, 'Mengkudu', 0, 62, 1),
(0, 0, 'Sambiloto', 0, 66, 1),
(0, 0, 'Temu Hitam', 0, 68, 1),
(0, 0, 'Temu Kunci', 0, 71, 1),
(0, 0, 'Temu Putih', 0, 69, 1),
(0, 0, 'Temu Putri', 0, 70, 1),
(0, 0, 'Temulawak', 0, 67, 1),
(0, 0, 'BATANG/TH', 0, 1746, 1),
(0, 0, 'BUAH/TH ', 0, 1013, 1),
(0, 0, 'EKOR/TH ', 0, 1745, 1),
(0, 0, 'JENIS/TH', 0, 965, 1),
(0, 0, 'KG/TH', 0, 960, 1),
(0, 0, 'LITER/TH', 0, 962, 1),
(0, 0, 'M/TH', 0, 963, 1),
(0, 0, 'M3/TH', 0, 961, 1),
(0, 0, 'TON/TH', 0, 966, 1),
(0, 0, 'UNIT/TH', 0, 964, 1),
(0, 0, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(0, 0, 'Dijual ke pasar', 0, 489, 1),
(0, 0, 'Dijual langsung ke konsumen', 0, 488, 1),
(0, 0, 'Dijual melalui KUD', 0, 490, 1),
(0, 0, 'Dijual melalui Pengecer', 0, 492, 1),
(0, 0, 'Dijual melalui Tengkulak', 0, 491, 1),
(0, 0, 'Tidak dijual', 0, 494, 1),
(0, 0, 'Cengkeh', 0, 83, 1),
(0, 0, 'Coklat', 0, 84, 1),
(0, 0, 'Jarak kepyar', 0, 93, 1),
(0, 0, 'Jarak pagar', 0, 92, 1),
(0, 0, 'Kacang mede', 0, 5, 1),
(0, 0, 'Kapuk', 0, 95, 1),
(0, 0, 'Karet', 0, 87, 1),
(0, 0, 'Kelapa', 0, 80, 1),
(0, 0, 'Kelapa sawit', 0, 81, 1),
(0, 0, 'Kemiri', 0, 256, 1),
(0, 0, 'Kopi', 0, 82, 1),
(0, 0, 'Lada', 0, 86, 1),
(0, 0, 'Pala', 0, 90, 1),
(0, 0, 'Pinang', 0, 85, 1),
(0, 0, 'Tebu', 0, 94, 1),
(0, 0, 'Teh', 0, 97, 1),
(0, 0, 'Tembakau', 0, 89, 1),
(0, 0, 'Vanili', 0, 91, 1),
(0, 0, 'BATANG/TH', 0, 1746, 1),
(0, 0, 'BUAH/TH ', 0, 1013, 1),
(0, 0, 'EKOR/TH ', 0, 1745, 1),
(0, 0, 'JENIS/TH', 0, 965, 1),
(0, 0, 'KG/TH', 0, 960, 1),
(0, 0, 'LITER/TH', 0, 962, 1),
(0, 0, 'M/TH', 0, 963, 1),
(0, 0, 'M3/TH', 0, 961, 1),
(0, 0, 'TON/TH', 0, 966, 1),
(0, 0, 'UNIT/TH', 0, 964, 1),
(0, 0, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(0, 0, 'Dijual ke pasar', 0, 489, 1),
(0, 0, 'Dijual langsung ke konsumen', 0, 488, 1),
(0, 0, 'Dijual melalui KUD', 0, 490, 1),
(0, 0, 'Dijual melalui Pengecer', 0, 492, 1),
(0, 0, 'Dijual melalui Tengkulak', 0, 491, 1),
(0, 0, 'Tidak dijual', 0, 494, 1),
(0, 0, 'Arang', 0, 121, 1),
(0, 0, 'Bambu', 0, 102, 1),
(0, 0, 'Cemara', 0, 109, 1),
(0, 0, 'Damar', 0, 101, 1),
(0, 0, 'Enau', 0, 107, 1),
(0, 0, 'Gambir', 0, 117, 1),
(0, 0, 'Gula enau', 0, 119, 1),
(0, 0, 'Gula lontar', 0, 120, 1),
(0, 0, 'Ijuk Enau', 0, 245, 1),
(0, 0, 'Jati', 0, 103, 1),
(0, 0, 'Kayu', 0, 98, 1),
(0, 0, 'Kayu Bakar', 0, 247, 1),
(0, 0, 'Kayu besi', 0, 114, 1),
(0, 0, 'Kayu cendana', 0, 110, 1),
(0, 0, 'Kayu gaharu', 0, 111, 1),
(0, 0, 'Kayu Sengon', 0, 246, 1),
(0, 0, 'Kayu ulin', 0, 115, 1),
(0, 0, 'Kemenyan', 0, 116, 1),
(0, 0, 'Lontar', 0, 105, 1),
(0, 0, 'Madu lebah', 0, 99, 1),
(0, 0, 'Mahoni', 0, 108, 1),
(0, 0, 'Meranti', 0, 113, 1),
(0, 0, 'Minyak kayu putih', 0, 118, 1),
(0, 0, 'Nilam', 0, 104, 1),
(0, 0, 'Rotan', 0, 100, 1),
(0, 0, 'Rumbia', 0, 259, 1),
(0, 0, 'Sagu', 0, 106, 1),
(0, 0, 'Sarang burung', 0, 112, 1),
(0, 0, 'BATANG/TH', 0, 1746, 1),
(0, 0, 'BUAH/TH ', 0, 1013, 1),
(0, 0, 'EKOR/TH ', 0, 1745, 1),
(0, 0, 'JENIS/TH', 0, 965, 1),
(0, 0, 'KG/TH', 0, 960, 1),
(0, 0, 'LITER/TH', 0, 962, 1),
(0, 0, 'M/TH', 0, 963, 1),
(0, 0, 'M3/TH', 0, 961, 1),
(0, 0, 'TON/TH', 0, 966, 1),
(0, 0, 'UNIT/TH', 0, 964, 1),
(0, 0, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(0, 0, 'Dijual ke pasar', 0, 489, 1),
(0, 0, 'Dijual langsung ke konsumen', 0, 488, 1),
(0, 0, 'Dijual melalui KUD', 0, 490, 1),
(0, 0, 'Dijual melalui Pengecer', 0, 492, 1),
(0, 0, 'Dijual melalui Tengkulak', 0, 491, 1),
(0, 0, 'Tidak dijual', 0, 494, 1),
(0, 0, 'Angsa', 0, 131, 1),
(0, 0, 'Anjing', 0, 135, 1),
(0, 0, 'Ayam kampung', 0, 125, 1),
(0, 0, 'Babi', 0, 124, 1),
(0, 0, 'Bebek', 0, 127, 1),
(0, 0, 'Buaya', 0, 145, 1),
(0, 0, 'Burung beo', 0, 142, 1),
(0, 0, 'Burung cendrawasih', 0, 140, 1),
(0, 0, 'Burung kakatua', 0, 141, 1),
(0, 0, 'Burung langka lainnya', 0, 144, 1),
(0, 0, 'Burung merak', 0, 143, 1),
(0, 0, 'Burung Merpati', 0, 244, 1),
(0, 0, 'Burung onta', 0, 138, 1),
(0, 0, 'Burung puyuh', 0, 132, 1),
(0, 0, 'Domba', 0, 130, 1),
(0, 0, 'Jenis ayam broiler', 0, 126, 1),
(0, 0, 'Kambing', 0, 129, 1),
(0, 0, 'Kelinci', 0, 133, 1),
(0, 0, 'Kerbau', 0, 123, 1),
(0, 0, 'Kucing', 0, 136, 1),
(0, 0, 'Kuda', 0, 128, 1),
(0, 0, 'Sapi', 0, 122, 1),
(0, 0, 'Tuna', 0, 146, 1),
(0, 0, 'Ular cobra', 0, 137, 1),
(0, 0, 'Ular pithon', 0, 139, 1),
(0, 0, 'BATANG/TH', 0, 1746, 1),
(0, 0, 'BUAH/TH ', 0, 1013, 1),
(0, 0, 'EKOR/TH ', 0, 1745, 1),
(0, 0, 'JENIS/TH', 0, 965, 1),
(0, 0, 'KG/TH', 0, 960, 1),
(0, 0, 'LITER/TH', 0, 962, 1),
(0, 0, 'M/TH', 0, 963, 1),
(0, 0, 'M3/TH', 0, 961, 1),
(0, 0, 'TON/TH', 0, 966, 1),
(0, 0, 'UNIT/TH', 0, 964, 1),
(0, 0, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(0, 0, 'Dijual ke pasar', 0, 489, 1),
(0, 0, 'Dijual langsung ke konsumen', 0, 488, 1),
(0, 0, 'Dijual melalui KUD', 0, 490, 1),
(0, 0, 'Dijual melalui Pengecer', 0, 492, 1),
(0, 0, 'Dijual melalui Tengkulak', 0, 491, 1),
(0, 0, 'Tidak dijual', 0, 494, 1),
(0, 0, 'Ayam-ayam', 0, 168, 1),
(0, 0, 'Bandeng', 0, 171, 1),
(0, 0, 'Barabara', 0, 165, 1),
(0, 0, 'Baronang', 0, 160, 1),
(0, 0, 'Bawal', 0, 159, 1),
(0, 0, 'Belanak', 0, 155, 1),
(0, 0, 'Belut', 0, 184, 1),
(0, 0, 'Cucut', 0, 166, 1),
(0, 0, 'Cumi', 0, 156, 1),
(0, 0, 'Gabus', 0, 179, 1),
(0, 0, 'Gurame', 0, 183, 1),
(0, 0, 'Gurita', 0, 157, 1),
(0, 0, 'Hiu', 0, 149, 1),
(0, 0, 'Ikan ekor kuning', 0, 162, 1),
(0, 0, 'Jambal', 0, 152, 1),
(0, 0, 'Kakap', 0, 150, 1),
(0, 0, 'Katak', 0, 188, 1),
(0, 0, 'Kembung', 0, 161, 1),
(0, 0, 'Kepiting', 0, 174, 1),
(0, 0, 'Kerang', 0, 173, 1),
(0, 0, 'Sunuk', 0, 163, 1),
(0, 0, 'Kodok', 0, 187, 1),
(0, 0, 'Kulit kerang', 0, 209, 1),
(0, 0, 'Kuwe', 0, 154, 1),
(0, 0, 'Layur', 0, 167, 1),
(0, 0, 'Lele', 0, 178, 1),
(0, 0, 'Mas', 0, 175, 1),
(0, 0, 'Mujair', 0, 177, 1),
(0, 0, 'Nener', 0, 172, 1),
(0, 0, 'Nila', 0, 181, 1),
(0, 0, 'Pari', 0, 153, 1),
(0, 0, 'Patin', 0, 180, 1),
(0, 0, 'Penyu', 0, 185, 1),
(0, 0, 'Rajungan', 0, 176, 1),
(0, 0, 'Rumput laut', 0, 186, 1),
(0, 0, 'Salmon', 0, 147, 1),
(0, 0, 'Sarden', 0, 158, 1),
(0, 0, 'Sepat', 0, 182, 1),
(0, 0, 'Tembang', 0, 170, 1),
(0, 0, 'Tenggiri', 0, 151, 1),
(0, 0, 'Teri', 0, 254, 1),
(0, 0, 'Teripang', 0, 164, 1),
(0, 0, 'Tongkol/cakalang', 0, 148, 1),
(0, 0, 'Tuna', 0, 251, 1),
(0, 0, 'Udang/lobster', 0, 169, 1),
(0, 0, 'BATANG/TH', 0, 1746, 1),
(0, 0, 'BUAH/TH ', 0, 1013, 1),
(0, 0, 'EKOR/TH ', 0, 1745, 1),
(0, 0, 'JENIS/TH', 0, 965, 1),
(0, 0, 'KG/TH', 0, 960, 1),
(0, 0, 'LITER/TH', 0, 962, 1),
(0, 0, 'M/TH', 0, 963, 1),
(0, 0, 'M3/TH', 0, 961, 1),
(0, 0, 'TON/TH', 0, 966, 1),
(0, 0, 'UNIT/TH', 0, 964, 1),
(0, 0, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(0, 0, 'Dijual ke pasar', 0, 489, 1),
(0, 0, 'Dijual langsung ke konsumen', 0, 488, 1),
(0, 0, 'Dijual melalui KUD', 0, 490, 1),
(0, 0, 'Dijual melalui Pengecer', 0, 492, 1),
(0, 0, 'Dijual melalui Tengkulak', 0, 491, 1),
(0, 0, 'Tidak dijual', 0, 494, 1),
(0, 0, 'Aluminium', 0, 189, 1),
(0, 0, 'Batu apung', 0, 190, 1),
(0, 0, 'Batu cadas', 0, 191, 1),
(0, 0, 'Batu Gamping', 0, 192, 1),
(0, 0, 'Batu Gips', 0, 193, 1),
(0, 0, 'Batu Granit', 0, 194, 1),
(0, 0, 'Batu gunung', 0, 195, 1),
(0, 0, 'Batu kali', 0, 196, 1),
(0, 0, 'Batu kapur', 0, 197, 1),
(0, 0, 'Batu marmer', 0, 198, 1),
(0, 0, 'Batu Putih', 0, 199, 1),
(0, 0, 'Batu Trass', 0, 200, 1),
(0, 0, 'Batubara', 0, 201, 1),
(0, 0, 'Belerang', 0, 202, 1),
(0, 0, 'Biji Besi', 0, 203, 1),
(0, 0, 'Bouxit', 0, 204, 1),
(0, 0, 'Emas', 0, 205, 1),
(0, 0, 'Garam', 0, 206, 1),
(0, 0, 'Gas Alam', 0, 207, 1),
(0, 0, 'Gips', 0, 208, 1),
(0, 0, 'Kuningan', 0, 210, 1),
(0, 0, 'Mangan', 0, 212, 1),
(0, 0, 'Minyak', 0, 233, 1),
(0, 0, 'Minyak Bumi', 0, 213, 1),
(0, 0, 'Nikel', 0, 214, 1),
(0, 0, 'Pasir', 0, 215, 1),
(0, 0, 'Pasir Batu', 0, 216, 1),
(0, 0, 'Pasir Besi', 0, 217, 1),
(0, 0, 'Pasir kwarsa', 0, 218, 1),
(0, 0, 'Perak', 0, 219, 1),
(0, 0, 'Perunggu', 0, 220, 1),
(0, 0, 'Tanah Garam', 0, 221, 1),
(0, 0, 'Tanah liat', 0, 222, 1),
(0, 0, 'Tembaga', 0, 223, 1),
(0, 0, 'Timah', 0, 224, 1),
(0, 0, 'Uranium', 0, 225, 1),
(0, 0, 'BATANG/TH', 0, 1746, 1),
(0, 0, 'BUAH/TH ', 0, 1013, 1),
(0, 0, 'EKOR/TH ', 0, 1745, 1),
(0, 0, 'JENIS/TH', 0, 965, 1),
(0, 0, 'KG/TH', 0, 960, 1),
(0, 0, 'LITER/TH', 0, 962, 1),
(0, 0, 'M/TH', 0, 963, 1),
(0, 0, 'M3/TH', 0, 961, 1),
(0, 0, 'TON/TH', 0, 966, 1),
(0, 0, 'UNIT/TH', 0, 964, 1),
(0, 0, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(0, 0, 'Dijual ke pasar', 0, 489, 1),
(0, 0, 'Dijual langsung ke konsumen', 0, 488, 1),
(0, 0, 'Dijual melalui KUD', 0, 490, 1),
(0, 0, 'Dijual melalui Pengecer', 0, 492, 1),
(0, 0, 'Dijual melalui Tengkulak', 0, 491, 1),
(0, 0, 'Tidak dijual', 0, 494, 1),
(0, 0, 'Air liur burung walet', 0, 232, 1),
(0, 0, 'Bulu', 0, 231, 1),
(0, 0, 'Burung walet', 0, 134, 1),
(0, 0, 'Cinderamata', 0, 235, 1),
(0, 0, 'Daging', 0, 229, 1),
(0, 0, 'Hiasan/lukisan', 0, 234, 1),
(0, 0, 'Kerupuk Kulit', 0, 248, 1),
(0, 0, 'Kulit', 0, 227, 1),
(0, 0, 'Madu', 0, 230, 1),
(0, 0, 'Susu', 0, 226, 1),
(0, 0, 'Telur', 0, 228, 1),
(0, 0, 'BATANG/TH', 0, 1746, 1),
(0, 0, 'BUAH/TH ', 0, 1013, 1),
(0, 0, 'EKOR/TH ', 0, 1745, 1),
(0, 0, 'JENIS/TH', 0, 965, 1),
(0, 0, 'KG/TH', 0, 960, 1),
(0, 0, 'LITER/TH', 0, 962, 1),
(0, 0, 'M/TH', 0, 963, 1),
(0, 0, 'M3/TH', 0, 961, 1),
(0, 0, 'TON/TH', 0, 966, 1),
(0, 0, 'UNIT/TH', 0, 964, 1),
(0, 0, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(0, 0, 'Dijual ke pasar', 0, 489, 1),
(0, 0, 'Dijual langsung ke konsumen', 0, 488, 1),
(0, 0, 'Dijual melalui KUD', 0, 490, 1),
(0, 0, 'Dijual melalui Pengecer', 0, 492, 1),
(0, 0, 'Dijual melalui Tengkulak', 0, 491, 1),
(0, 0, 'Tidak dijual', 0, 494, 1),
(0, 0, 'Jala', 0, 405, 1),
(0, 0, 'Jermal', 0, 402, 1),
(0, 0, 'Karamba', 0, 400, 1),
(0, 0, 'Pancing', 0, 403, 1),
(0, 0, 'Pukat', 0, 404, 1),
(0, 0, 'Tambak', 0, 401, 1),
(0, 0, 'Air minum/air baku', 0, 511, 1),
(0, 0, 'Buang air besar', 0, 514, 1),
(0, 0, 'Cuci dan mandi', 0, 512, 1),
(0, 0, 'Irigasi', 0, 513, 1),
(0, 0, 'Pembangkit listrik', 0, 515, 1),
(0, 0, 'Prasarana transportasi', 0, 516, 1),
(0, 0, 'Sumber air panas', 0, 517, 1),
(0, 0, 'Usaha Perikanan', 0, 510, 1),
(0, 0, 'Biara', 0, 687, 1),
(0, 0, 'Kursus Bahasa', 0, 697, 1),
(0, 0, 'Kursus Bela Diri', 0, 703, 1),
(0, 0, 'Kursus Komputer', 0, 700, 1),
(0, 0, 'Kursus Mengemudi', 0, 701, 1),
(0, 0, 'Kursus Menjahit', 0, 698, 1),
(0, 0, 'Kursus Montir', 0, 699, 1),
(0, 0, 'Kursus Satpam', 0, 702, 1),
(0, 0, 'Lembaga Kursus Keterampilan Swasta Katolik', 0, 692, 1),
(0, 0, 'Lembaga Pendidikan Swasta Budha', 0, 695, 1),
(0, 0, 'Lembaga Pendidikan Swasta Hindu', 0, 694, 1),
(0, 0, 'Lembaga Pendidikan Swasta Konghucu', 0, 696, 1),
(0, 0, 'Lembaga Pendidikan Swasta Kristen Protestan', 0, 693, 1),
(0, 0, 'Madrasah Aliyah', 0, 682, 1),
(0, 0, 'Madrasah Ibtidaiyah', 0, 680, 1),
(0, 0, 'Madrasah Tsanawiyah', 0, 681, 1),
(0, 0, 'Perguruan Tinggi', 0, 676, 1),
(0, 0, 'Perguruan Tinggi Swasta Katolik', 0, 688, 1),
(0, 0, 'Pondok Pesantren', 0, 677, 1),
(0, 0, 'Rhaudatul Athfal (Tk)', 0, 679, 1),
(0, 0, 'SD/Sederajat', 0, 673, 1),
(0, 0, 'Sekolah Dasar Swasta Katolik', 0, 689, 1),
(0, 0, 'Sekolah Tinggi Agama Islam', 0, 683, 1),
(0, 0, 'Seminari Menengah', 0, 685, 1),
(0, 0, 'Seminari Tinggi', 0, 686, 1),
(0, 0, 'SLTA Swasta Katolik', 0, 691, 1),
(0, 0, 'SLTP Swasta Katolik', 0, 690, 1),
(0, 0, 'SMA/Sederajat', 0, 675, 1),
(0, 0, 'SMP/Sederajat', 0, 674, 1),
(0, 0, 'Taman Pendidikan Alqur?an', 0, 678, 1),
(0, 0, 'TK/Preschool/Play Group', 0, 672, 1),
(0, 0, 'Universitas Swasta Islam', 0, 684, 1),
(0, 0, 'Tidak memiliki tanah', 0, 704, 1),
(0, 0, 'Memiliki tanah kurang dari 0,1 ha', 0, 1744, 1),
(0, 0, 'Memiliki tanah antara 0,1 - 0,2 ha', 0, 705, 1),
(0, 0, 'Memiliki tanah antara 0,2 - 0,3 ha', 0, 706, 1),
(0, 0, 'Memiliki tanah antara 0,3 - 0,4 ha', 0, 707, 1),
(0, 0, 'Memiliki tanah antara 0,4 - 0,5 ha', 0, 708, 1),
(0, 0, 'Memiliki tanah antara 0,5 - 0,6 ha', 0, 709, 1),
(0, 0, 'Memiliki tanah antara 0,6 - 0,7 ha', 0, 710, 1),
(0, 0, 'Memiliki tanah antara 0,7 - 0,8 ha', 0, 711, 1),
(0, 0, 'Memiliki tanah antara 0,8 - 0,9 ha', 0, 712, 1),
(0, 0, 'Memiliki tanah antara 0,9 - 1,0 ha', 0, 713, 1),
(0, 0, 'Memiliki tanah antara 1,0 - 5,0 ha', 0, 714, 1),
(0, 0, 'Memiliki tanah lebih dari 5,0 ha', 0, 715, 1),
(0, 0, 'Memiiki cidemo/andong/dokar  ', 0, 718, 1),
(0, 0, 'Memiliki bajaj/kancil', 0, 723, 1),
(0, 0, 'Memiliki becak', 0, 717, 1),
(0, 0, 'Memiliki bus penumpang/angkutan orang/barang', 0, 721, 1),
(0, 0, 'Memiliki ojek motor/sepeda motor/bentor', 0, 716, 1),
(0, 0, 'Memiliki perahu tidak bermotor', 0, 719, 1),
(0, 0, 'Memiliki sepeda dayung', 0, 722, 1),
(0, 0, 'Memiliki tongkang', 0, 720, 1),
(0, 0, 'Memiliki alat pengolahan hasil hutan  ', 0, 731, 1),
(0, 0, 'Memiliki alat pengolahan hasil perikanan  ', 0, 728, 1),
(0, 0, 'Memiliki alat pengolahan hasil perkebunan', 0, 730, 1),
(0, 0, 'Memiliki alat pengolahan hasil peternakan  ', 0, 729, 1),
(0, 0, 'Memiliki alat produksi dan pengolah hasil Industri kerajinan keluarga skala kecil dan menengah  ', 0, 733, 1),
(0, 0, 'Memiliki alat produksi dan pengolah hasil pertambangan  ', 0, 732, 1),
(0, 0, 'Memiliki alat produksi dan pengolahan hasil industri bahan bakar dan gas skala keluarga/rumah tangga  ', 0, 734, 1),
(0, 0, 'Memiliki kapal penangkap ikan  ', 0, 727, 1),
(0, 0, 'Memiliki pabrik pengolahan hasil pertanian  ', 0, 726, 1),
(0, 0, 'Memiliki penggilingan padi  ', 0, 724, 1),
(0, 0, 'Memiliki traktor', 0, 725, 1),
(0, 0, 'Bambu', 0, 737, 1),
(0, 0, 'Dedaunan', 0, 740, 1),
(0, 0, 'Kayu', 0, 736, 1),
(0, 0, 'Pelepah kelapa/lontar/gebang  ', 0, 739, 1),
(0, 0, 'Tanah Liat', 0, 738, 1),
(0, 0, 'Tembok', 0, 735, 1),
(0, 0, 'Kayu', 0, 743, 1),
(0, 0, 'Keramik', 0, 741, 1),
(0, 0, 'Semen', 0, 742, 1),
(0, 0, 'Tanah', 0, 744, 1),
(0, 0, 'Asbes', 0, 747, 1),
(0, 0, 'Bambu', 0, 749, 1),
(0, 0, 'Daun ilalang ', 0, 7752, 1),
(0, 0, 'Daun lontar/gebang/enau  ', 0, 751, 1),
(0, 0, 'Genteng', 0, 745, 1),
(0, 0, 'Kayu', 0, 750, 1),
(0, 0, 'Seng', 0, 746, 1),
(0, 0, 'Berlangganan koran/majalah', 0, 787, 1),
(0, 0, 'Memiliki buku surat berharga', 0, 766, 1),
(0, 0, 'Memiliki buku tabungan bank', 0, 765, 1),
(0, 0, 'Memiliki hiasan emas/berlian', 0, 764, 1),
(0, 0, 'Memiliki HP CDMA', 0, 784, 1),
(0, 0, 'Memiliki HP GSM', 0, 783, 1),
(0, 0, 'Memiliki kapal barang', 0, 757, 1),
(0, 0, 'Memiliki kapal penumpang', 0, 758, 1),
(0, 0, 'Memiliki kapal pesiar', 0, 759, 1),
(0, 0, 'Memiliki mobil pribadi dan sejenisnya', 0, 755, 1),
(0, 0, 'Memiliki parabola', 0, 786, 1),
(0, 0, 'Memiliki perahu bermotor', 0, 756, 1),
(0, 0, 'Memiliki perusahaan industri besar', 0, 770, 1),
(0, 0, 'Memiliki perusahaan industri kecil', 0, 772, 1),
(0, 0, 'Memiliki perusahaan industri menengah', 0, 771, 1),
(0, 0, 'Memiliki saham di perusahaan', 0, 781, 1),
(0, 0, 'Memiliki sepeda motor pribadi', 0, 754, 1),
(0, 0, 'Memiliki sertifikat bangunan', 0, 769, 1),
(0, 0, 'Memiliki sertifikat deposito', 0, 767, 1),
(0, 0, 'Memiliki sertifikat tanah', 0, 768, 1),
(0, 0, 'Memiliki ternak besar', 0, 762, 1),
(0, 0, 'Memiliki ternak kecil', 0, 763, 1),
(0, 0, 'Memiliki TV dan elektronik sejenis lainnya', 0, 753, 1),
(0, 0, 'Memiliki usaha di pasar desa', 0, 779, 1),
(0, 0, 'Memiliki usaha di pasar swalayan', 0, 777, 1),
(0, 0, 'Memiliki usaha di pasar tradisional', 0, 778, 1),
(0, 0, 'Memiliki usaha pasar swalayan', 0, 776, 1),
(0, 0, 'Memiliki usaha perikanan', 0, 773, 1),
(0, 0, 'Memiliki usaha perkebunan', 0, 775, 1),
(0, 0, 'Memiliki usaha peternakan', 0, 774, 1),
(0, 0, 'Memiliki usaha transportasi/pengangkutan', 0, 780, 1),
(0, 0, 'Memiliki Usaha Wartel', 0, 785, 1),
(0, 0, 'Memiliki/menyewa helikopter pribadi', 0, 760, 1),
(0, 0, 'Memiliki/menyewa pesawat terbang pribadi', 0, 761, 1),
(0, 0, 'Pelanggan Telkom', 0, 782, 1),
(0, 0, 'Ibu hamil melahirkan', 0, 796, 1),
(0, 0, 'Ibu hamil periksa di Bidan Praktek', 0, 792, 1),
(0, 0, 'Ibu hamil periksa di Dokter Praktek', 0, 791, 1),
(0, 0, 'Ibu hamil periksa di Dukun Terlatih', 0, 793, 1),
(0, 0, 'Ibu hamil periksa di Posyandu', 0, 788, 1),
(0, 0, 'Ibu hamil periksa di Puskesmas', 0, 789, 1),
(0, 0, 'Ibu hamil periksa di Rumah Sakit', 0, 790, 1),
(0, 0, 'Ibu hamil tidak periksa kesehatan', 0, 794, 1),
(0, 0, 'Ibu hamil yang meninggal', 0, 795, 1),
(0, 0, 'Ibu nifas sakit', 0, 797, 1),
(0, 0, 'Ibu nifas sehat', 0, 799, 1),
(0, 0, 'Kematian ibu nifas', 0, 798, 1),
(0, 0, 'Kematian ibu saat melahirkan', 0, 800, 1),
(0, 0, 'Bayi 0-5 tahun hidup yang menderita kelainan organ tubuh, fisik dan mental  ', 0, 807, 1),
(0, 0, 'Bayi lahir berat kurang dari 2,5 kg', 0, 805, 1),
(0, 0, 'Bayi lahir berat lebih dari 4 kg', 0, 806, 1),
(0, 0, 'Bayi lahir hidup cacat', 0, 803, 1),
(0, 0, 'Bayi lahir hidup normal', 0, 802, 1),
(0, 0, 'Bayi lahir mati', 0, 804, 1),
(0, 0, 'Keguguran kandungan', 0, 801, 1),
(0, 0, 'Rumah dukun', 0, 815, 1),
(0, 0, 'Rumah sendiri', 0, 816, 1),
(0, 0, 'Tempat persalinan Balai Kesehatan Ibu Anak', 0, 812, 1),
(0, 0, 'Tempat persalinan Polindes', 0, 811, 1),
(0, 0, 'Tempat persalinan Puskesmas', 0, 810, 1),
(0, 0, 'Tempat persalinan Rumah Bersalin', 0, 809, 1),
(0, 0, 'Tempat persalinan rumah praktek bidan', 0, 813, 1),
(0, 0, 'Tempat persalinan Rumah Sakit Umum', 0, 808, 1),
(0, 0, 'Tempat praktek dokter', 0, 814, 1),
(0, 0, 'Persalinan ditolong bidan', 0, 818, 1),
(0, 0, 'Persalinan ditolong Dokter', 0, 817, 1),
(0, 0, 'Persalinan ditolong dukun bersalin', 0, 820, 1),
(0, 0, 'Persalinan ditolong keluarga', 0, 821, 1),
(0, 0, 'Persalinan ditolong perawat', 0, 819, 1),
(0, 0, 'BCG', 0, 823, 1),
(0, 0, 'Cacar', 0, 830, 1),
(0, 0, 'Campak', 0, 829, 1),
(0, 0, 'DPT-1', 0, 822, 1),
(0, 0, 'DPT-2', 0, 825, 1),
(0, 0, 'DPT-3', 0, 828, 1),
(0, 0, 'Polio -1', 0, 824, 1),
(0, 0, 'Polio-2', 0, 826, 1),
(0, 0, 'Polio-3', 0, 827, 1),
(0, 0, 'Sudah Semua', 0, 831, 1),
(0, 0, 'Busung Lapar', 0, 838, 1),
(0, 0, 'Cikungunya', 0, 836, 1),
(0, 0, 'Demam Berdarah', 0, 833, 1),
(0, 0, 'Flu Burung', 0, 837, 1),
(0, 0, 'Kelainan fisik', 0, 841, 1),
(0, 0, 'Kelainan mental', 0, 842, 1),
(0, 0, 'Kelaparan', 0, 839, 1),
(0, 0, 'Kolera', 0, 834, 1),
(0, 0, 'Kulit Bersisik', 0, 840, 1),
(0, 0, 'Muntaber', 0, 832, 1),
(0, 0, 'Polio', 0, 835, 1),
(0, 0, 'Biasa buang air besar di sungai/parit/kebun/hutan  ', 0, 845, 1),
(0, 0, 'Memiliki WC yang darurat/kurang memenuhi standar kesehatan  ', 0, 844, 1),
(0, 0, 'Memiliki WC yang permanen/semipermanen  ', 0, 843, 1),
(0, 0, 'Menggunakan fasilitas MCK umum  ', 0, 846, 1),
(0, 0, 'Belum tentu sehari makan 1 kali  ', 0, 851, 1),
(0, 0, 'Kebiasaan makan dalam sehari 1 kali  ', 0, 847, 1),
(0, 0, 'Kebiasaan makan sehari 2 kali  ', 0, 848, 1),
(0, 0, 'Kebiasaan makan sehari 3 kali  ', 0, 849, 1),
(0, 0, 'Kebiasaan makan sehari lebih dari 3 kali  ', 0, 850, 1),
(0, 0, 'Dokter/puskesmas/mantri kesehatan/perawat/ bidan/ posyandu  ', 0, 853, 1),
(0, 0, 'Dukun Terlatih  ', 0, 852, 1),
(0, 0, 'Obat tradisional dari dukun pengobatan alternatif  ', 0, 854, 1),
(0, 0, 'Obat tradisional dari keluarga sendiri  ', 0, 856, 1),
(0, 0, 'Paranormal  ', 0, 855, 1),
(0, 0, 'Tidak diobati  ', 0, 857, 1),
(0, 0, 'Balita bergizi baik  ', 0, 859, 1),
(0, 0, 'Balita bergizi buruk  ', 0, 858, 1),
(0, 0, 'Balita bergizi kurang  ', 0, 860, 1),
(0, 0, 'Balita bergizi lebih', 0, 861, 1),
(0, 0, 'Asma', 0, 874, 1),
(0, 0, 'Diabetes Melitus', 0, 867, 1),
(0, 0, 'Gila/stress', 0, 872, 1),
(0, 0, 'Ginjal', 0, 868, 1),
(0, 0, 'HIV/AIDS', 0, 871, 1),
(0, 0, 'Jantung', 0, 862, 1),
(0, 0, 'Kanker', 0, 865, 1),
(0, 0, 'Lepra/Kusta', 0, 870, 1),
(0, 0, 'Lever', 0, 863, 1),
(0, 0, 'Malaria', 0, 869, 1),
(0, 0, 'Paru-paru', 0, 864, 1),
(0, 0, 'Stroke', 0, 866, 1),
(0, 0, 'TBC', 0, 873, 1),
(0, 0, 'Anak yatim/piatu dalam keluarga akibat konflik Sara  ', 0, 878, 1),
(0, 0, 'Janda/duda dalam keluarga akibat konflik Sara  ', 0, 877, 1),
(0, 0, 'Korban luka dalam keluarga akibat konflik Sara  ', 0, 875, 1),
(0, 0, 'Korban meninggal dalam keluarga akibat konflik Sara ', 0, 876, 1),
(0, 0, 'Korban jiwa akibat perkelahian dalam keluarga  ', 0, 879, 1),
(0, 0, 'Korban luka parah akibat perkelahian dalam keluarga ', 0, 880, 1),
(0, 0, 'Korban pencurian, perampokan dalam keluarga  ', 0, 881, 1),
(0, 0, 'Korban penjarahan yang pelakunya anggota keluarga  ', 0, 882, 1),
(0, 0, 'Korban penjarahan yang pelakunya bukan anggota keluarga  ', 0, 883, 1),
(0, 0, 'Anggota keluarga yang memiliki kebiasaan berjudi', 0, 884, 1),
(0, 0, 'Anggota keluarga mengkonsumsi Miras yang dilarang  ', 0, 885, 1),
(0, 0, 'Anggota keluarga yang mengkonsumsi Narkoba ', 0, 886, 1),
(0, 0, 'Korban pembunuhan dalam keluarga yang pelakunya anggota keluarga  ', 0, 887, 1),
(0, 0, 'Korban pembunuhan dalam keluarga yang pelakunya bukan anggota keluarga', 0, 888, 1),
(0, 0, 'Korban penculikan yang pelakunya anggota keluarga  ', 0, 889, 1),
(0, 0, 'Korban penculikan yang pelakunya bukan anggota keluarga  ', 0, 890, 1),
(0, 0, 'Korban kehamilan di luar nikah yang sah menurut hukum adat  ', 0, 893, 1),
(0, 0, 'Korban kehamilan yang tidak dinikahi pelakunya  ', 0, 894, 1),
(0, 0, 'Korban kehamilan yang tidak/belum disahkan secara hukum agama dan hukum negara  ', 0, 895, 1),
(0, 0, 'Korban perkosaan/pelecehan seksual yang pelakunya anggota keluarga  ', 0, 891, 1),
(0, 0, 'Korban perkosaan/pelecehan seksual yang pelakunya bukan anggota keluarga  ', 0, 892, 1),
(0, 0, 'Adanya pemukulan/tindakan fisik antara anak dengan anggota keluarga lain  ', 0, 903, 1),
(0, 0, 'Adanya pemukulan/tindakan fisik antara anak dengan orang tua  ', 0, 901, 1),
(0, 0, 'Adanya pemukulan/tindakan fisik antara anak dengan pembantu  ', 0, 905, 1),
(0, 0, 'Adanya pemukulan/tindakan fisik antara orang tua dengan anak  ', 0, 902, 1),
(0, 0, 'Adanya pemukulan/tindakan fisik antara orang tua dengan orang tua  ', 0, 904, 1),
(0, 0, 'Adanya pemukulan/tindakan fisik antara orang tua dengan pembantu  ', 0, 906, 1),
(0, 0, 'Adanya pertengkaran dalam keluarga antara anak dan anak  ', 0, 897, 1),
(0, 0, 'Adanya pertengkaran dalam keluarga antara anak dan anggota keluarga lain  ', 0, 900, 1),
(0, 0, 'Adanya pertengkaran dalam keluarga antara anak dan orang tua  ', 0, 896, 1),
(0, 0, 'Adanya pertengkaran dalam keluarga antara anak dan pembantu  ', 0, 899, 1),
(0, 0, 'Adanya pertengkaran dalam keluarga antara ayah dan ibu/orang tua ', 0, 898, 1),
(0, 0, 'Ada anak anggota keluarga yang mengemis', 0, 918, 1),
(0, 0, 'Ada anak dan anggota keluarga yang menjadi pengamen', 0, 919, 1),
(0, 0, 'Ada anak yang membantu orang tua mendapatkan penghasilan', 0, 934, 1),
(0, 0, 'Ada anggota keluarga eks narapidana', 0, 936, 1),
(0, 0, 'Ada anggota keluarga yang bermalam/tidur di jalanan/emperan toko/pusat keramaian/kolong jembatan', 0, 916, 1),
(0, 0, 'Ada anggota keluarga yang cacat fisik', 0, 921, 1),
(0, 0, 'Ada anggota keluarga yang cacat mental', 0, 922, 1),
(0, 0, 'Ada anggota keluarga yang gila/stres', 0, 920, 1),
(0, 0, 'Ada anggota keluarga yang kelainan kulit', 0, 923, 1),
(0, 0, 'Ada anggota keluarga yang menganggur', 0, 933, 1),
(0, 0, 'Ada anggota keluarga yang mengemis', 0, 915, 1),
(0, 0, 'Ada anggota keluarga yang menjadi pengamen', 0, 924, 1),
(0, 0, 'Ada anggota keluarga yang termasuk manusia lanjut usia (di atas 60 thn)', 0, 917, 1),
(0, 0, 'Anggota keluarga yatim/piatu', 0, 925, 1),
(0, 0, 'Keluarga duda', 0, 927, 1),
(0, 0, 'Keluarga janda', 0, 926, 1),
(0, 0, 'Kepala keluarga perempuan', 0, 935, 1),
(0, 0, 'Tinggal di bantaran sungai', 0, 928, 1),
(0, 0, 'Tinggal di daerah kawasan kering, tandus dan kritis', 0, 947, 1),
(0, 0, 'Tinggal di daerah rawan bencana tsunami', 0, 938, 1),
(0, 0, 'Tinggal di desa/kelurahan rawan air bersih', 0, 944, 1),
(0, 0, 'Tinggal di desa/kelurahan rawan banjir', 0, 937, 1),
(0, 0, 'Tinggal di desa/kelurahan rawan bencana kekeringan', 0, 945, 1),
(0, 0, 'Tinggal di desa/kelurahan rawan gagal tanam/panen', 0, 946, 1),
(0, 0, 'Tinggal di desa/kelurahan rawan gunung meletus', 0, 939, 1),
(0, 0, 'Tinggal di desa/kelurahan rawan kelaparan', 0, 943, 1),
(0, 0, 'Tinggal di jalur hijau', 0, 929, 1),
(0, 0, 'Tinggal di jalur rawan gempa bumi', 0, 940, 1),
(0, 0, 'Tinggal di kawasan jalur rel kereta api', 0, 930, 1),
(0, 0, 'Tinggal di kawasan jalur sutet', 0, 931, 1),
(0, 0, 'Tinggal di kawasan kumuh dan padat pemukiman', 0, 932, 1),
(0, 0, 'Tinggal di kawasan rawan kebakaran', 0, 942, 1),
(0, 0, 'Tinggal di kawasan rawan tanah longsor', 0, 941, 1),
(0, 0, 'Kepala Keluarga', 0, 1, 1),
(0, 0, 'Suami', 0, 2, 1),
(0, 0, 'Istri', 0, 3, 1),
(0, 0, 'Anak Kandung', 0, 4, 1),
(0, 0, 'Anak Angkat', 0, 5, 1),
(0, 0, 'Ayah', 0, 6, 1),
(0, 0, 'Ibu', 0, 7, 1),
(0, 0, 'Paman', 0, 8, 1),
(0, 0, 'Tante', 0, 9, 1),
(0, 0, 'Kakak', 0, 10, 1),
(0, 0, 'Adik', 0, 11, 1),
(0, 0, 'Kakek', 0, 12, 1),
(0, 0, 'Nenek', 0, 13, 1),
(0, 0, 'Sepupu', 0, 14, 1),
(0, 0, 'Keponakan', 0, 15, 1),
(0, 0, 'Teman', 0, 16, 1),
(0, 0, 'Mertua', 0, 17, 1),
(0, 0, 'Cucu', 0, 18, 1),
(0, 0, 'Famili lain', 0, 19, 1),
(0, 0, 'Menantu', 0, 21, 1),
(0, 0, 'Lainnya', 0, 22, 1),
(0, 0, 'Anak Tiri', 0, 23, 1),
(0, 0, 'Kawin', 0, 1, 1),
(0, 0, 'Belum Kawin', 0, 2, 1),
(0, 0, 'Janda/Duda', 0, 3, 1),
(0, 0, 'Islam', 0, 1, 1),
(0, 0, 'Kristen Protestan', 0, 2, 1),
(0, 0, 'Kristen Katolik', 0, 3, 1),
(0, 0, 'Hindu', 0, 4, 1),
(0, 0, 'Budha', 0, 5, 1),
(0, 0, 'Konghucu', 0, 6, 1),
(0, 0, 'Aliran Kepercayaan Kepada Tuhan YME', 0, 7, 1),
(0, 0, 'O', 0, 0, 1),
(0, 0, 'A', 0, 1, 1),
(0, 0, 'B', 0, 2, 1),
(0, 0, 'AB', 0, 3, 1),
(0, 0, 'Tidak Tahu', 0, 4, 1),
(0, 0, 'Warga Negara Indonesia', 0, 1, 1),
(0, 0, 'Warga Negara Asing', 0, 2, 1),
(0, 0, 'Dwi Kewarganegaraan', 0, 3, 1),
(0, 0, 'Belum masuk TK/Kelompok Bermain', 0, 1, 1),
(0, 0, 'Sedang TK/Kelompok Bermain', 0, 2, 1),
(0, 0, 'Tidak pernah sekolah', 0, 3, 1),
(0, 0, 'Sedang SD/sederajat', 0, 4, 1),
(0, 0, 'Tamat SD/sederajat', 0, 5, 1),
(0, 0, 'Tidak tamat SD/sederajat', 0, 6, 1),
(0, 0, 'Sedang SLTP/Sederajat', 0, 7, 1),
(0, 0, 'Tamat SLTP/sederajat', 0, 8, 1),
(0, 0, 'Sedang SLTA/sederajat', 0, 9, 1),
(0, 0, 'Tamat SLTA/sederajat', 0, 10, 1),
(0, 0, 'Sedang D-1/sederajat', 0, 11, 1),
(0, 0, 'Tamat D-1/sederajat', 0, 12, 1),
(0, 0, 'Sedang D-2/sederajat', 0, 13, 1),
(0, 0, 'Tamat D-2/sederajat', 0, 14, 1),
(0, 0, 'Sedang D-3/sederajat', 0, 15, 1),
(0, 0, 'Tamat D-4/sederajat', 0, 16, 1),
(0, 0, 'Sedang S-1/sederajat', 0, 17, 1),
(0, 0, 'Tamat S-1/sederajat', 0, 18, 1),
(0, 0, 'Sedang S-2/sederajat', 0, 19, 1),
(0, 0, 'Tamat S-2/sederajat', 0, 20, 1),
(0, 0, 'Sedang S-3/sederajat', 0, 21, 1),
(0, 0, 'Tamat S-3/sederajat', 0, 22, 1),
(0, 0, 'Sedang SLB A/sederajat', 0, 23, 1),
(0, 0, 'Tamat SLB A/sederajat', 0, 24, 1),
(0, 0, 'Sedang SLB B/sederajat', 0, 25, 1),
(0, 0, 'Tamat SLB B/sederajat', 0, 26, 1),
(0, 0, 'Sedang SLB C/sederajat', 0, 27, 1),
(0, 0, 'Tamat SLB C/sederajat', 0, 28, 1),
(0, 0, 'Tidak dapat membaca dan menulis huruf Latin/Arab', 0, 29, 1),
(0, 0, 'Tamat D-3/sederajat', 0, 30, 1),
(0, 0, 'Petani', 0, 1, 1),
(0, 0, 'Buruh Tani', 0, 2, 1),
(0, 0, 'Buruh Migran Perempuan', 0, 3, 1),
(0, 0, 'Buruh Migran laki-laki', 0, 4, 1),
(0, 0, 'Pegawai Negeri Sipil', 0, 5, 1),
(0, 0, 'Karyawan Swasta', 0, 6, 1),
(0, 0, 'Pengrajin', 0, 7, 1),
(0, 0, 'Pedagang barang kelontong', 0, 8, 1),
(0, 0, 'Peternak', 0, 9, 1),
(0, 0, 'Nelayan', 0, 10, 1),
(0, 0, 'Montir', 0, 11, 1),
(0, 0, 'Dokter swasta', 0, 12, 1),
(0, 0, 'Perawat swasta', 0, 13, 1),
(0, 0, 'Bidan swasta', 0, 14, 1),
(0, 0, 'Ahli Pengobatan Alternatif', 0, 15, 1),
(0, 0, 'TNI', 0, 16, 1),
(0, 0, 'POLRI', 0, 17, 1),
(0, 0, 'Pengusaha kecil, menengah dan besar', 0, 18, 1),
(0, 0, 'Guru swasta', 0, 19, 1),
(0, 0, 'Dosen swasta', 0, 20, 1),
(0, 0, 'Seniman/artis', 0, 21, 1),
(0, 0, 'Pedagang Keliling', 0, 22, 1),
(0, 0, 'Penambang', 0, 23, 1),
(0, 0, 'Tukang Kayu', 0, 24, 1),
(0, 0, 'Tukang Batu', 0, 25, 1),
(0, 0, 'Tukang cuci', 0, 26, 1),
(0, 0, 'Pembantu rumah tangga', 0, 27, 1),
(0, 0, 'Pengacara', 0, 28, 1),
(0, 0, 'Notaris', 0, 29, 1),
(0, 0, 'Dukun Tradisional', 0, 30, 1),
(0, 0, 'Arsitektur/Desainer', 0, 31, 1),
(0, 0, 'Karyawan Perusahaan Swasta', 0, 32, 1),
(0, 0, 'Karyawan Perusahaan Pemerintah', 0, 33, 1),
(0, 0, 'Wiraswasta', 0, 34, 1),
(0, 0, 'Konsultan Manajemen dan Teknis', 0, 35, 1),
(0, 0, 'Tidak Mempunyai Pekerjaan Tetap', 0, 36, 1),
(0, 0, 'Belum Bekerja', 0, 37, 1),
(0, 0, 'Pelajar', 0, 38, 1),
(0, 0, 'Ibu Rumah Tangga', 0, 39, 1),
(0, 0, 'Purnawirawan/Pensiunan', 0, 40, 1),
(0, 0, 'Perangkat Desa', 0, 41, 1),
(0, 0, 'Buruh Harian Lepas', 0, 42, 1),
(0, 0, 'Pemilik perusahaan', 0, 55, 1),
(0, 0, 'Pengusaha perdagangan hasil bumi', 0, 56, 1),
(0, 0, 'Buruh jasa perdagangan hasil bumi', 0, 57, 1),
(0, 0, 'Pemilik usaha jasa transportasi dan perhubungan', 0, 58, 1),
(0, 0, 'Buruh usaha jasa transportasi dan perhubungan', 0, 59, 1),
(0, 0, 'Pemilik usaha informasi dan komunikasi', 0, 60, 1),
(0, 0, 'Buruh usaha jasa informasi dan komunikasi', 0, 61, 1),
(0, 0, 'Kontraktor', 0, 62, 1),
(0, 0, 'Pemilik usaha jasa hiburan dan pariwisata', 0, 63, 1),
(0, 0, 'Buruh usaha jasa hiburan dan pariwisata', 0, 64, 1),
(0, 0, 'Pemilik usaha hotel dan penginapan lainnya ', 0, 65, 1),
(0, 0, 'Buruh usaha hotel dan penginapan lainnya', 0, 66, 1),
(0, 0, 'Pemilik usaha warung, rumah makan dan restoran', 0, 67, 1),
(0, 0, 'Dukun/paranormal/supranatural', 0, 68, 1),
(0, 0, 'Jasa pengobatan alternatif', 0, 69, 1),
(0, 0, 'Sopir', 0, 70, 1),
(0, 0, 'Usaha jasa pengerah tenaga kerja', 0, 71, 1),
(0, 0, 'Jasa penyewaan peralatan pesta', 0, 74, 1),
(0, 0, 'Pemulung', 0, 75, 1),
(0, 0, 'Pengrajin industri rumah tangga lainnya', 0, 76, 1),
(0, 0, 'Tukang Anyaman', 0, 77, 1),
(0, 0, 'Tukang Jahit', 0, 78, 1),
(0, 0, 'Tukang Kue', 0, 79, 1),
(0, 0, 'Tukang Rias', 0, 80, 1),
(0, 0, 'Tukang Sumur', 0, 81, 1),
(0, 0, 'Jasa Konsultansi Manajemen dan Teknis ', 0, 82, 1),
(0, 0, 'Juru Masak', 0, 83, 1),
(0, 0, 'Karyawan Honorer', 0, 84, 1),
(0, 0, 'Pialang', 0, 85, 1),
(0, 0, 'Pskiater/Psikolog', 0, 86, 1),
(0, 0, 'Wartawan', 0, 87, 1),
(0, 0, 'Tukang Cukur', 0, 88, 1),
(0, 0, 'Tukang Las', 0, 89, 1),
(0, 0, 'Tukang Gigi', 0, 90, 1),
(0, 0, 'Tukang Listrik', 0, 91, 1),
(0, 0, 'Pemuka Agama', 0, 92, 1),
(0, 0, 'Anggota Legislatif', 0, 93, 1),
(0, 0, 'Kepala Daerah', 0, 94, 1),
(0, 0, 'Apoteker', 0, 96, 1),
(0, 0, 'Presiden', 0, 97, 1),
(0, 0, 'Wakil presiden', 0, 98, 1),
(0, 0, 'Anggota Mahkamah Konstitusi', 0, 99, 1),
(0, 0, 'Anggota Kabinet Kementrian', 0, 100, 1),
(0, 0, 'Duta besar', 0, 101, 1),
(0, 0, 'Gubernur', 0, 102, 1),
(0, 0, 'Wakil bupati', 0, 103, 1),
(0, 0, 'Pilot', 0, 104, 1),
(0, 0, 'Penyiar radio', 0, 105, 1),
(0, 0, 'Pelaut', 0, 106, 1),
(0, 0, 'Peneliti', 0, 107, 1),
(0, 0, 'Satpam/Security', 0, 108, 1),
(0, 0, 'Wakil Gubernur', 0, 109, 1),
(0, 0, 'Bupati/Walikota', 0, 110, 1),
(0, 0, 'Akuntan', 0, 112, 1),
(0, 0, 'Menggunakan alat kontrasepsi Suntik', 0, 1, 1),
(0, 0, 'Menggunakan alat kontrasepsi Spiral', 0, 2, 1),
(0, 0, 'Menggunakan alat kontrasepsi Kondom', 0, 3, 1),
(0, 0, 'Menggunakan alat kontrasepsi vasektomi', 0, 4, 1),
(0, 0, 'Menggunakan alat kontrasepsi Tubektomi', 0, 5, 1),
(0, 0, 'Menggunakan alat kontrasepsi Pil', 0, 6, 1),
(0, 0, 'Menggunakan metode KB Alamiah/Kalender', 0, 7, 1),
(0, 0, 'Menggunakan obat tradisional', 0, 8, 1),
(0, 0, 'Tidak Menggunakan alat kontrasepsi /metode KBA', 0, 9, 1),
(0, 0, 'Susuk KB (Implant)', 0, 10, 1),
(0, 0, 'Tidak Menjawab', 0, 11, 1),
(0, 0, 'Tuna Rungu', 0, 1, 1),
(0, 0, 'Tuna Wicara', 0, 2, 1),
(0, 0, 'Tuna Netra', 0, 3, 1),
(0, 0, 'Lumpuh', 0, 4, 1),
(0, 0, 'Sumbing', 0, 5, 1),
(0, 0, 'Idiot', 0, 1, 1),
(0, 0, 'Gila', 0, 2, 1),
(0, 0, 'Stress', 0, 3, 1),
(0, 0, 'Wajib Pajak Bumi dan Bangunan', 0, 1, 1),
(0, 0, 'Wajib Pajak Penghasilan Perorangan', 0, 2, 1),
(0, 0, 'Wajib Pajak Badan/Perusahaan', 0, 3, 1),
(0, 0, 'Wajib Pajak Kendaraan Bermotor', 0, 4, 1),
(0, 0, 'Wajib Retribusi Kebersihan', 0, 5, 1),
(0, 0, 'Wajib Retribusi Keamanan', 0, 6, 1),
(0, 0, 'Kepala Desa/Lurah', 0, 1, 1),
(0, 0, 'Sekretaris Desa/Kelurahan', 0, 2, 1),
(0, 0, 'Kepala Urusan', 0, 3, 1),
(0, 0, 'Kepala Dusun/Lingkungan', 0, 4, 1),
(0, 0, 'Staf Desa/Kelurahan', 0, 5, 1),
(0, 0, 'Ketua BPD', 0, 6, 1),
(0, 0, 'Wakil Ketua BPD', 0, 7, 1),
(0, 0, 'Sekretaris BPD', 0, 8, 1),
(0, 0, 'Anggota BPD', 0, 9, 1),
(0, 0, 'Pengurus RT', 0, 1, 1),
(0, 0, 'Anggota Pengurus RT', 0, 2, 1),
(0, 0, 'Pengurus RW', 0, 3, 1),
(0, 0, 'Anggota Pengurus RW', 0, 4, 1),
(0, 0, 'Pengurus LKMD/K/LPM', 0, 5, 1),
(0, 0, 'Anggota LKMD/K/LPM', 0, 6, 1),
(0, 0, 'Pengurus PKK', 0, 7, 1),
(0, 0, 'Anggota PKK', 0, 8, 1),
(0, 0, 'Pengurus Lembaga Adat', 0, 9, 1),
(0, 0, 'Pengurus Karang Taruna', 0, 10, 1),
(0, 0, 'Anggota Karang Taruna', 0, 11, 1),
(0, 0, 'Pengurus Hansip/Linmas', 0, 12, 1),
(0, 0, 'Pengurus Poskamling', 0, 13, 1),
(0, 0, 'Pengurus Organisasi Perempuan', 0, 14, 1),
(0, 0, 'Anggota Organisasi Perempuan', 0, 15, 1),
(0, 0, 'Pengurus Organisasi Bapak-bapak', 0, 16, 1),
(0, 0, 'Anggota Organisasi Bapak-bapak', 0, 17, 1),
(0, 0, 'Pengurus Organisasi keagamaan', 0, 18, 1),
(0, 0, 'Anggota Organisasi keagamaan', 0, 19, 1),
(0, 0, 'Pengurus Organisasi profesi wartawan', 0, 20, 1),
(0, 0, 'Anggota Organisasi profesi wartawan', 0, 21, 1),
(0, 0, 'Pengurus Posyandu', 0, 22, 1),
(0, 0, 'Pengurus Posyantekdes', 0, 23, 1),
(0, 0, 'Pengurus Organisasi Kelompok Tani/Nelayan', 0, 24, 1),
(0, 0, 'Anggota Organisasi Kelompok Tani/Nelayan', 0, 25, 1),
(0, 0, 'Pengurus Lembaga Gotong royong', 0, 26, 1),
(0, 0, 'Anggota Lembaga Gotong royong', 0, 27, 1),
(0, 0, 'Pengurus Organisasi Profesi guru', 0, 28, 1),
(0, 0, 'Anggota Organisasi Profesi guru', 0, 29, 1),
(0, 0, 'Pengurus Organisasi profesi dokter/tenaga medis', 0, 30, 1),
(0, 0, 'Anggota Organisasi profesi/tenaga medis', 0, 31, 1),
(0, 0, 'Pengurus organisasi pensiunan', 0, 32, 1),
(0, 0, 'Anggota organisasi pensiunan', 0, 33, 1),
(0, 0, 'Pengurus organisasi pemirsa/pendengar', 0, 34, 1),
(0, 0, 'Anggota organisasi pemirsa/pendengar', 0, 35, 1),
(0, 0, 'Pengurus lembaga pencinta alam', 0, 36, 1),
(0, 0, 'Anggota organisasi pencinta alam', 0, 37, 1),
(0, 0, 'Pengurus organisasi pengembangan ilmu pengetahuan', 0, 38, 1),
(0, 0, 'Anggota organisasi pengembangan ilmu pengetaahuan', 0, 39, 1),
(0, 0, 'Pemilik yayasan', 0, 40, 1),
(0, 0, 'Pengurus yayasan', 0, 41, 1),
(0, 0, 'Anggota yayasan', 0, 42, 1),
(0, 0, 'Pengurus Satgas Kebersihan', 0, 43, 1),
(0, 0, 'Anggota Satgas Kebersihan', 0, 44, 1),
(0, 0, 'Pengurus Satgas Kebakaran', 0, 45, 1),
(0, 0, 'Anggota Satgas Kebakaran', 0, 46, 1),
(0, 0, 'Pengurus Posko Penanggulangan Bencana', 0, 47, 1),
(0, 0, 'Anggota Tim Penanggulangan Bencana', 0, 48, 1),
(0, 0, 'Koperasi', 0, 1, 1),
(0, 0, 'Unit Usaha Simpan Pinjam', 0, 2, 1),
(0, 0, 'Industri Kerajinan Tangan', 0, 3, 1),
(0, 0, 'Industri Pakaian', 0, 4, 1),
(0, 0, 'Industri Usaha Makanan', 0, 5, 1),
(0, 0, 'Industri Alat Rumah Tangga', 0, 6, 1),
(0, 0, 'Industri Usaha Bahan Bangunan', 0, 7, 1),
(0, 0, 'Industri Alat Pertanian', 0, 8, 1),
(0, 0, 'Restoran', 0, 9, 1),
(0, 0, 'Toko/ Swalayan', 0, 10, 1),
(0, 0, 'Warung Kelontongan/Kios', 0, 11, 1),
(0, 0, 'Angkutan Darat', 0, 12, 1),
(0, 0, 'Angkutan Sungai', 0, 13, 1),
(0, 0, 'Angkutan Laut', 0, 14, 1),
(0, 0, 'Angkutan Udara', 0, 15, 1),
(0, 0, 'Jasa Ekspedisi/Pengiriman Barang', 0, 16, 1),
(0, 0, 'Tukang Sumur', 0, 17, 1),
(0, 0, 'Usaha Pasar Harian', 0, 18, 1),
(0, 0, 'Usaha Pasar Mingguan', 0, 19, 1),
(0, 0, 'Usaha Pasar Ternak', 0, 20, 1),
(0, 0, 'Usaha Pasar Hasil Bumi Dan Tambang', 0, 21, 1),
(0, 0, 'Usaha Perdagangan Antar Pulau', 0, 22, 1),
(0, 0, 'Pengijon', 0, 23, 1),
(0, 0, 'Pedagang Pengumpul/Tengkulak', 0, 24, 1),
(0, 0, 'Usaha Peternakan', 0, 25, 1),
(0, 0, 'Usaha Perikanan', 0, 26, 1),
(0, 0, 'Usaha Perkebunan', 0, 27, 1),
(0, 0, 'Kelompok Simpan Pinjam', 0, 28, 1),
(0, 0, 'Usaha Minuman', 0, 29, 1),
(0, 0, 'Industri Farmasi', 0, 30, 1),
(0, 0, 'Industri Karoseri', 0, 31, 1),
(0, 0, 'Penitipan Kendaraan Bermotor', 0, 32, 1),
(0, 0, 'Industri Perakitan Elektronik', 0, 33, 1),
(0, 0, 'Pengolahan Kayu', 0, 34, 1),
(0, 0, 'Bioskop', 0, 35, 1),
(0, 0, 'Film Keliling', 0, 36, 1),
(0, 0, 'Sandiwara/Drama', 0, 37, 1),
(0, 0, 'Group Lawak', 0, 38, 1),
(0, 0, 'Jaipongan', 0, 39, 1),
(0, 0, 'Wayang Orang/Golek', 0, 40, 1),
(0, 0, 'Group Musik/Band', 0, 41, 1),
(0, 0, 'Group Vokal/Paduan Suara', 0, 42, 1),
(0, 0, 'Usaha Persewaan Tenaga Listrik', 0, 43, 1),
(0, 0, 'Usaha Pengecer Gas Dan Bahan Bakar Minyak', 0, 44, 1),
(0, 0, 'Usaha Air Minum Dalam Kemasan', 0, 45, 1),
(0, 0, 'Tukang Kayu', 0, 46, 1),
(0, 0, 'Tukang Batu', 0, 47, 1),
(0, 0, 'Tukang Jahit/Bordir', 0, 48, 1),
(0, 0, 'Tukang Cukur', 0, 49, 1),
(0, 0, 'Tukang Service Elektronik', 0, 50, 1),
(0, 0, 'Tukang Besi', 0, 51, 1),
(0, 0, 'Tukang Pijat/Urut', 0, 52, 1),
(0, 0, 'Tukang Sumur', 0, 53, 1),
(0, 0, 'Notaris', 0, 54, 1),
(0, 0, 'Pengacara/Advokat', 0, 55, 1),
(0, 0, 'Konsultan Manajemen', 0, 56, 1),
(0, 0, 'Konsultan Teknis', 0, 57, 1),
(0, 0, 'Pejabat Pembuat Akta Tanah', 0, 58, 1),
(0, 0, 'Losmen', 0, 59, 1),
(0, 0, 'Wisma', 0, 60, 1),
(0, 0, 'Asrama', 0, 61, 1),
(0, 0, 'Persewaan Kamar', 0, 62, 1),
(0, 0, 'Kontrakan Rumah', 0, 63, 1),
(0, 0, 'Mess', 0, 64, 1),
(0, 0, 'Hotel', 0, 65, 1),
(0, 0, 'Home Stay', 0, 66, 1),
(0, 0, 'Villa', 0, 67, 1),
(0, 0, 'Town House', 0, 68, 1),
(0, 0, 'Usaha Asuransi', 0, 69, 1),
(0, 0, 'Lembaga Keuangan Bukan Bank', 0, 70, 1),
(0, 0, 'Lembaga Perkreditan Rakyat', 0, 71, 1),
(0, 0, 'Pegadaian', 0, 72, 1),
(0, 0, 'Bank Perkreditan Rakyat', 0, 73, 1),
(0, 0, 'Usaha Penyewaan Alat Pesta', 0, 74, 1),
(0, 0, 'Usaha Pengolahan dan Penjualan Hasil Hutan', 0, 75, 1);

-- --------------------------------------------------------

--
-- Table structure for table `analisis_partisipasi`
--

CREATE TABLE `analisis_partisipasi` (
  `id` int(11) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL,
  `id_klassifikasi` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_periode`
--

CREATE TABLE `analisis_periode` (
  `id` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `id_state` tinyint(4) NOT NULL DEFAULT 1,
  `aktif` tinyint(1) NOT NULL DEFAULT 0,
  `keterangan` varchar(100) NOT NULL,
  `tahun_pelaksanaan` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `analisis_periode`
--

INSERT INTO `analisis_periode` (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES
(0, 0, 'Data Dasar Keluarga ', 1, 1, 'Pendataan Profil Desa', 2018),
(0, 0, 'Data Anggota Keluarga', 1, 1, 'Pendataan Profil Desa', 2018);

-- --------------------------------------------------------

--
-- Table structure for table `analisis_ref_state`
--

CREATE TABLE `analisis_ref_state` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `analisis_ref_subjek` (
  `id` tinyint(4) NOT NULL,
  `subjek` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `analisis_respon` (
  `id_indikator` int(11) NOT NULL,
  `id_parameter` int(11) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_respon_bukti`
--

CREATE TABLE `analisis_respon_bukti` (
  `id_master` tinyint(4) NOT NULL,
  `id_periode` tinyint(4) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `pengesahan` varchar(100) NOT NULL,
  `tgl_update` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_respon_hasil`
--

CREATE TABLE `analisis_respon_hasil` (
  `id_master` tinyint(4) NOT NULL,
  `id_periode` tinyint(4) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `akumulasi` double(8,3) NOT NULL,
  `tgl_update` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `analisis_tipe_indikator`
--

CREATE TABLE `analisis_tipe_indikator` (
  `id` tinyint(4) NOT NULL,
  `tipe` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Table structure for table `anggota_grup_kontak`
--

CREATE TABLE `anggota_grup_kontak` (
  `id_grup_kontak` int(11) NOT NULL,
  `id_grup` int(11) NOT NULL,
  `id_kontak` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `id` int(4) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `path` text NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT 1,
  `ref_polygon` int(9) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `id_cluster` int(11) NOT NULL,
  `desk` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE `artikel` (
  `id` int(11) NOT NULL,
  `gambar` varchar(200) NOT NULL,
  `isi` text NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT 1,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_kategori` int(4) NOT NULL,
  `id_user` int(4) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `headline` int(1) NOT NULL DEFAULT 0,
  `gambar1` varchar(200) NOT NULL,
  `gambar2` varchar(200) NOT NULL,
  `gambar3` varchar(200) NOT NULL,
  `dokumen` varchar(400) NOT NULL,
  `link_dokumen` varchar(200) NOT NULL,
  `urut` int(5) DEFAULT NULL,
  `jenis_widget` tinyint(2) NOT NULL DEFAULT 3,
  `boleh_komentar` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `captcha_codes`
--

CREATE TABLE `captcha_codes` (
  `id` varchar(40) NOT NULL,
  `namespace` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL,
  `code_display` varchar(32) NOT NULL,
  `created` int(11) NOT NULL,
  `audio_data` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(5) NOT NULL,
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
  `website` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `daftar_kontak`
--

CREATE TABLE `daftar_kontak` (
  `id_kontak` int(11) DEFAULT NULL,
  `id_pend` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `sex` varchar(9) DEFAULT NULL,
  `alamat_sekarang` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_persil`
--

CREATE TABLE `data_persil` (
  `id` int(11) NOT NULL,
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
  `peta` text DEFAULT NULL,
  `rdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `pemilik_luar` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_persil_jenis`
--

CREATE TABLE `data_persil_jenis` (
  `id` int(11) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `ndesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_persil_peruntukan`
--

CREATE TABLE `data_persil_peruntukan` (
  `id` int(11) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `ndesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `detail_log_penduduk`
--

CREATE TABLE `detail_log_penduduk` (
  `id` int(10) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `disposisi_surat_masuk`
--

CREATE TABLE `disposisi_surat_masuk` (
  `id_disposisi` int(11) NOT NULL,
  `id_surat_masuk` int(11) NOT NULL,
  `id_desa_pamong` int(11) DEFAULT NULL,
  `disposisi_ke` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE `dokumen` (
  `id` int(11) NOT NULL,
  `satuan` varchar(200) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT 1,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_pend` int(11) NOT NULL DEFAULT 0,
  `kategori` tinyint(3) NOT NULL DEFAULT 1,
  `attr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gambar_gallery`
--

CREATE TABLE `gambar_gallery` (
  `id` int(11) NOT NULL,
  `parrent` int(4) NOT NULL,
  `gambar` varchar(200) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT 1,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipe` int(4) NOT NULL,
  `slider` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `garis`
--

CREATE TABLE `garis` (
  `id` int(4) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `path` text NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT 1,
  `ref_line` int(9) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `desk` text NOT NULL,
  `id_cluster` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gis_simbol`
--

CREATE TABLE `gis_simbol` (
  `simbol` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gis_simbol`
--

INSERT INTO `gis_simbol` (`simbol`) VALUES
('accident.png'),
('accident_2.png'),
('administration.png'),
('administration_2.png'),
('aestheticscenter.png'),
('agriculture.png'),
('agriculture2.png'),
('agriculture3.png'),
('agriculture4.png'),
('aircraft-small.png'),
('airplane-sport.png'),
('airplane-tourism.png'),
('airport-apron.png'),
('airport-runway.png'),
('airport-terminal.png'),
('airport.png'),
('airport_2.png'),
('amphitheater-tourism.png'),
('amphitheater.png'),
('ancientmonument.png'),
('ancienttemple.png'),
('ancienttempleruin.png'),
('animals.png'),
('animals_2.png'),
('anniversary.png'),
('apartment.png'),
('apartment_2.png'),
('aquarium.png'),
('arch.png'),
('archery.png'),
('artgallery.png'),
('atm.png'),
('atv.png'),
('audio.png'),
('australianfootball.png'),
('bags.png'),
('bank.png'),
('bank_2.png'),
('bankeuro.png'),
('bankpound.png'),
('bar.png'),
('bar_2.png'),
('baseball.png'),
('basketball.png'),
('baskteball2.png'),
('beach.png'),
('beach_2.png'),
('beautiful.png'),
('beautiful_2.png'),
('bench.png'),
('biblio.png'),
('bicycleparking.png'),
('bigcity.png'),
('billiard.png'),
('bobsleigh.png'),
('bomb.png'),
('bookstore.png'),
('bowling.png'),
('bowling_2.png'),
('boxing.png'),
('bread.png'),
('bread_2.png'),
('bridge.png'),
('bridgemodern.png'),
('bullfight.png'),
('bungalow.png'),
('bus.png'),
('bus_2.png'),
('butcher.png'),
('cabin.png'),
('cablecar.png'),
('camping.png'),
('camping_2.png'),
('campingsite.png'),
('canoe.png'),
('car.png'),
('car_2.png'),
('carrental.png'),
('carrepair.png'),
('carrepair_2.png'),
('carwash.png'),
('casino.png'),
('casino_2.png'),
('castle.png'),
('cathedral.png'),
('cathedral2.png'),
('cave.png'),
('cemetary.png'),
('chapel.png'),
('church.png'),
('church2.png'),
('church_2.png'),
('cinema.png'),
('cinema_2.png'),
('circus.png'),
('citysquare.png'),
('climbing.png'),
('clothes-female.png'),
('clothes-male.png'),
('clothes.png'),
('clothes_2.png'),
('clouds.png'),
('clouds_2.png'),
('cloudsun.png'),
('cloudsun_2.png'),
('club.png'),
('club_2.png'),
('cluster.png'),
('cluster2.png'),
('cluster3.png'),
('cluster4.png'),
('cluster5.png'),
('cocktail.png'),
('coffee.png'),
('coffee_2.png'),
('communitycentre.png'),
('company.png'),
('company_2.png'),
('computer.png'),
('computer_2.png'),
('concessionaire.png'),
('conference.png'),
('construction.png'),
('convenience.png'),
('convent.png'),
('corral.png'),
('country.png'),
('court.png'),
('cricket.png'),
('cross.png'),
('crossingguard.png'),
('cruise.png'),
('currencyexchange.png'),
('customs.png'),
('cycling.png'),
('cycling_2.png'),
('cyclingfeedarea.png'),
('cyclingmountain1.png'),
('cyclingmountain2.png'),
('cyclingmountain3.png'),
('cyclingmountain4.png'),
('cyclingmountainnotrated.png'),
('cyclingsport.png'),
('cyclingsprint.png'),
('cyclinguncategorized.png'),
('dam.png'),
('dancinghall.png'),
('dates.png'),
('dates_2.png'),
('daycare.png'),
('days-dim.png'),
('days-dom.png'),
('days-jeu.png'),
('days-jue.png'),
('days-lun.png'),
('days-mar.png'),
('days-mer.png'),
('days-mie.png'),
('days-qua.png'),
('days-qui.png'),
('days-sab.png'),
('days-sam.png'),
('days-seg.png'),
('days-sex.png'),
('days-ter.png'),
('days-ven.png'),
('days-vie.png'),
('default.png'),
('dentist.png'),
('deptstore.png'),
('disability.png'),
('disability_2.png'),
('disabledparking.png'),
('diving.png'),
('doctor.png'),
('doctor_2.png'),
('dog-leash.png'),
('dog-offleash.png'),
('door.png'),
('down.png'),
('downleft.png'),
('downright.png'),
('downthenleft.png'),
('downthenright.png'),
('drinkingfountain.png'),
('drinkingwater.png'),
('drugs.png'),
('drugs_2.png'),
('elevator.png'),
('embassy.png'),
('emblem-art.png'),
('emblem-photos.png'),
('entrance.png'),
('escalator-down.png'),
('escalator-up.png'),
('exit.png'),
('expert.png'),
('explosion.png'),
('face-devilish.png'),
('face-embarrassed.png'),
('factory.png'),
('factory_2.png'),
('fallingrocks.png'),
('family.png'),
('farm.png'),
('farm_2.png'),
('fastfood.png'),
('fastfood_2.png'),
('festival-itinerant.png'),
('festival.png'),
('findajob.png'),
('findjob.png'),
('findjob_2.png'),
('fire-extinguisher.png'),
('fire.png'),
('firemen.png'),
('firemen_2.png'),
('fireworks.png'),
('firstaid.png'),
('fishing.png'),
('fishing_2.png'),
('fishingshop.png'),
('fitnesscenter.png'),
('fjord.png'),
('flood.png'),
('flowers.png'),
('flowers_2.png'),
('followpath.png'),
('foodtruck.png'),
('forest.png'),
('fortress.png'),
('fossils.png'),
('fountain.png'),
('friday.png'),
('friday_2.png'),
('friends.png'),
('friends_2.png'),
('garden.png'),
('gateswalls.png'),
('gazstation.png'),
('gazstation_2.png'),
('geyser.png'),
('gifts.png'),
('girlfriend.png'),
('girlfriend_2.png'),
('glacier.png'),
('golf.png'),
('golf_2.png'),
('gondola.png'),
('gourmet.png'),
('grocery.png'),
('gun.png'),
('gym.png'),
('hairsalon.png'),
('handball.png'),
('hanggliding.png'),
('hats.png'),
('headstone.png'),
('headstonejewish.png'),
('helicopter.png'),
('highway.png'),
('highway_2.png'),
('hiking-tourism.png'),
('hiking.png'),
('hiking_2.png'),
('historicalquarter.png'),
('home.png'),
('home_2.png'),
('horseriding.png'),
('horseriding_2.png'),
('hospital.png'),
('hospital_2.png'),
('hostel.png'),
('hotairballoon.png'),
('hotel.png'),
('hotel1star.png'),
('hotel2stars.png'),
('hotel3stars.png'),
('hotel4stars.png'),
('hotel5stars.png'),
('hotel_2.png'),
('house.png'),
('hunting.png'),
('icecream.png'),
('icehockey.png'),
('iceskating.png'),
('im-user.png'),
('index.html'),
('info.png'),
('info_2.png'),
('jewelry.png'),
('jewishquarter.png'),
('jogging.png'),
('judo.png'),
('justice.png'),
('justice_2.png'),
('karate.png'),
('karting.png'),
('kayak.png'),
('laboratory.png'),
('lake.png'),
('laundromat.png'),
('left.png'),
('leftthendown.png'),
('leftthenup.png'),
('library.png'),
('library_2.png'),
('lighthouse.png'),
('liquor.png'),
('lock.png'),
('lockerrental.png'),
('magicshow.png'),
('mainroad.png'),
('massage.png'),
('military.png'),
('military_2.png'),
('mine.png'),
('mobilephonetower.png'),
('modernmonument.png'),
('moderntower.png'),
('monastery.png'),
('monday.png'),
('monday_2.png'),
('monument.png'),
('mosque.png'),
('motorbike.png'),
('motorcycle.png'),
('movierental.png'),
('museum-archeological.png'),
('museum-art.png'),
('museum-crafts.png'),
('museum-historical.png'),
('museum-naval.png'),
('museum-science.png'),
('museum-war.png'),
('museum.png'),
('museum_2.png'),
('music-classical.png'),
('music-hiphop.png'),
('music-live.png'),
('music-rock.png'),
('music.png'),
('music_2.png'),
('nanny.png'),
('newsagent.png'),
('nordicski.png'),
('nursery.png'),
('observatory.png'),
('oilpumpjack.png'),
('olympicsite.png'),
('ophthalmologist.png'),
('pagoda.png'),
('paint.png'),
('palace.png'),
('panoramic.png'),
('panoramic180.png'),
('park-urban.png'),
('park.png'),
('park_2.png'),
('parkandride.png'),
('parking.png'),
('parking_2.png'),
('party.png'),
('patisserie.png'),
('pedestriancrossing.png'),
('pend.png'),
('pens.png'),
('perfumery.png'),
('personal.png'),
('personalwatercraft.png'),
('petroglyphs.png'),
('pets.png'),
('phones.png'),
('photo.png'),
('photodown.png'),
('photodownleft.png'),
('photodownright.png'),
('photography.png'),
('photoleft.png'),
('photoright.png'),
('photoup.png'),
('photoupleft.png'),
('photoupright.png'),
('picnic.png'),
('pizza.png'),
('pizza_2.png'),
('places-unvisited.png'),
('places-visited.png'),
('planecrash.png'),
('playground.png'),
('playground_2.png'),
('poker.png'),
('poker_2.png'),
('police.png'),
('police2.png'),
('police_2.png'),
('pool-indoor.png'),
('pool.png'),
('pool_2.png'),
('port.png'),
('port_2.png'),
('postal.png'),
('postal_2.png'),
('powerlinepole.png'),
('powerplant.png'),
('powersubstation.png'),
('prison.png'),
('publicart.png'),
('racing.png'),
('radiation.png'),
('rain_2.png'),
('rain_3.png'),
('rattlesnake.png'),
('realestate.png'),
('realestate_2.png'),
('recycle.png'),
('recycle_2.png'),
('recycle_3.png'),
('regroup.png'),
('regulier.png'),
('resort.png'),
('restaurant-barbecue.png'),
('restaurant-buffet.png'),
('restaurant-fish.png'),
('restaurant-romantic.png'),
('restaurant.png'),
('restaurant_2.png'),
('restaurantafrican.png'),
('restaurantchinese.png'),
('restaurantchinese_2.png'),
('restaurantfishchips.png'),
('restaurantgourmet.png'),
('restaurantgreek.png'),
('restaurantindian.png'),
('restaurantitalian.png'),
('restaurantjapanese.png'),
('restaurantjapanese_2.png'),
('restaurantkebab.png'),
('restaurantkorean.png'),
('restaurantmediterranean.png'),
('restaurantmexican.png'),
('restaurantthai.png'),
('restaurantturkish.png'),
('revolution.png'),
('right.png'),
('rightthendown.png'),
('rightthenup.png'),
('riparian.png'),
('ropescourse.png'),
('rowboat.png'),
('rugby.png'),
('ruins.png'),
('sailboat-sport.png'),
('sailboat-tourism.png'),
('sailboat.png'),
('salle-fete.png'),
('satursday.png'),
('satursday_2.png'),
('sauna.png'),
('school.png'),
('school_2.png'),
('schrink.png'),
('schrink_2.png'),
('sciencecenter.png'),
('seals.png'),
('seniorsite.png'),
('shadow.png'),
('shelter-picnic.png'),
('shelter-sleeping.png'),
('shoes.png'),
('shoes_2.png'),
('shoppingmall.png'),
('shore.png'),
('shower.png'),
('sight.png'),
('skateboarding.png'),
('skiing.png'),
('skiing_2.png'),
('skijump.png'),
('skilift.png'),
('smallcity.png'),
('smokingarea.png'),
('sneakers.png'),
('snow.png'),
('snowboarding.png'),
('snowmobiling.png'),
('snowshoeing.png'),
('soccer.png'),
('soccer2.png'),
('soccer_2.png'),
('spaceport.png'),
('spectacle.png'),
('speed100.png'),
('speed110.png'),
('speed120.png'),
('speed130.png'),
('speed20.png'),
('speed30.png'),
('speed40.png'),
('speed50.png'),
('speed60.png'),
('speed70.png'),
('speed80.png'),
('speed90.png'),
('speedhump.png'),
('spelunking.png'),
('stadium.png'),
('statue.png'),
('steamtrain.png'),
('stop.png'),
('stoplight.png'),
('stoplight_2.png'),
('strike.png'),
('strike1.png'),
('subway.png'),
('sun.png'),
('sun_2.png'),
('sunday.png'),
('sunday_2.png'),
('supermarket.png'),
('supermarket_2.png'),
('surfing.png'),
('suv.png'),
('synagogue.png'),
('tailor.png'),
('tapas.png'),
('taxi.png'),
('taxi_2.png'),
('taxiway.png'),
('teahouse.png'),
('telephone.png'),
('templehindu.png'),
('tennis.png'),
('tennis2.png'),
('tennis_2.png'),
('tent.png'),
('terrace.png'),
('text.png'),
('textiles.png'),
('theater.png'),
('theater_2.png'),
('themepark.png'),
('thunder.png'),
('thunder_2.png'),
('thursday.png'),
('thursday_2.png'),
('toilets.png'),
('toilets_2.png'),
('tollstation.png'),
('tools.png'),
('tower.png'),
('toys.png'),
('toys_2.png'),
('trafficenforcementcamera.png'),
('train.png'),
('train_2.png'),
('tram.png'),
('trash.png'),
('truck.png'),
('truck_2.png'),
('tuesday.png'),
('tuesday_2.png'),
('tunnel.png'),
('turnleft.png'),
('turnright.png'),
('university.png'),
('university_2.png'),
('unnamed.png'),
('up.png'),
('upleft.png'),
('upright.png'),
('upthenleft.png'),
('upthenright.png'),
('usfootball.png'),
('vespa.png'),
('vet.png'),
('video.png'),
('videogames.png'),
('videogames_2.png'),
('villa.png'),
('waitingroom.png'),
('water.png'),
('waterfall.png'),
('watermill.png'),
('waterpark.png'),
('waterskiing.png'),
('watertower.png'),
('waterwell.png'),
('waterwellpump.png'),
('wedding.png'),
('wednesday.png'),
('wednesday_2.png'),
('wetland.png'),
('white1.png'),
('white20.png'),
('wifi.png'),
('wifi_2.png'),
('windmill.png'),
('windsurfing.png'),
('windturbine.png'),
('winery.png'),
('wineyard.png'),
('workoffice.png'),
('world.png'),
('worldheritagesite.png'),
('yoga.png'),
('youthhostel.png'),
('zipline.png'),
('zoo.png'),
('zoo_2.png');

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE `inbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ReceivingDateTime` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `Text` text NOT NULL,
  `SenderNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text NOT NULL,
  `SMSCNumber` varchar(20) NOT NULL DEFAULT '',
  `Class` int(11) NOT NULL DEFAULT -1,
  `TextDecoded` text NOT NULL,
  `ID` int(10) UNSIGNED NOT NULL,
  `RecipientID` text NOT NULL,
  `Processed` enum('false','true') NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inventaris_asset`
--

CREATE TABLE `inventaris_asset` (
  `id` int(11) NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventaris_gedung`
--

CREATE TABLE `inventaris_gedung` (
  `id` int(11) NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventaris_jalan`
--

CREATE TABLE `inventaris_jalan` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kode_barang` varchar(64) NOT NULL,
  `register` varchar(64) NOT NULL,
  `kontruksi` varchar(255) NOT NULL,
  `panjang` int(64) NOT NULL,
  `lebar` int(64) NOT NULL,
  `luas` int(64) NOT NULL,
  `letak` text DEFAULT NULL,
  `tanggal_dokument` date NOT NULL,
  `no_dokument` varchar(255) DEFAULT NULL,
  `status_tanah` varchar(255) DEFAULT NULL,
  `kode_tanah` varchar(255) DEFAULT NULL,
  `kondisi` varchar(255) NOT NULL,
  `asal` varchar(255) NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventaris_kontruksi`
--

CREATE TABLE `inventaris_kontruksi` (
  `id` int(11) NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventaris_peralatan`
--

CREATE TABLE `inventaris_peralatan` (
  `id` int(11) NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventaris_tanah`
--

CREATE TABLE `inventaris_tanah` (
  `id` int(11) NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(5) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `tipe` int(4) NOT NULL DEFAULT 1,
  `urut` tinyint(4) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `parrent` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kelompok`
--

CREATE TABLE `kelompok` (
  `id` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `id_ketua` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `kode` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_anggota`
--

CREATE TABLE `kelompok_anggota` (
  `id` int(11) NOT NULL,
  `id_kelompok` int(11) NOT NULL,
  `id_penduduk` int(11) NOT NULL,
  `no_anggota` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_master`
--

CREATE TABLE `kelompok_master` (
  `id` int(11) NOT NULL,
  `kelompok` varchar(50) NOT NULL,
  `deskripsi` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `klasifikasi_analisis_keluarga`
--

CREATE TABLE `klasifikasi_analisis_keluarga` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `jenis` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `klasifikasi_surat`
--

CREATE TABLE `klasifikasi_surat` (
  `id` int(4) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `uraian` mediumtext NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `id` int(5) NOT NULL,
  `id_artikel` int(7) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `komentar` text NOT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `enabled` int(2) NOT NULL DEFAULT 2,
  `no_hp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kontak`
--

CREATE TABLE `kontak` (
  `id_kontak` int(11) NOT NULL,
  `id_pend` int(11) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kontak_grup`
--

CREATE TABLE `kontak_grup` (
  `id_grup` int(11) NOT NULL,
  `nama_grup` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `line`
--

CREATE TABLE `line` (
  `id` int(4) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT 'ff0000',
  `tipe` int(4) NOT NULL,
  `parrent` int(4) DEFAULT 1,
  `enabled` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_bulanan`
--

CREATE TABLE `log_bulanan` (
  `id` int(11) NOT NULL,
  `pend` int(11) NOT NULL,
  `wni_lk` int(11) DEFAULT NULL,
  `wni_pr` int(11) DEFAULT NULL,
  `kk` int(11) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kk_lk` int(11) DEFAULT NULL,
  `kk_pr` int(11) DEFAULT NULL,
  `wna_lk` int(11) DEFAULT NULL,
  `wna_pr` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_bulanan`
--

INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES
(0, 0, 0, 0, 0, '2019-09-02 15:06:28', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `log_keluarga`
--

CREATE TABLE `log_keluarga` (
  `id` int(10) NOT NULL,
  `id_kk` int(11) NOT NULL,
  `kk_sex` tinyint(2) DEFAULT NULL,
  `id_peristiwa` int(4) NOT NULL,
  `tgl_peristiwa` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_penduduk`
--

CREATE TABLE `log_penduduk` (
  `id` int(10) NOT NULL,
  `id_pend` int(11) NOT NULL,
  `id_detail` int(4) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `bulan` varchar(2) NOT NULL,
  `tahun` varchar(4) NOT NULL,
  `tgl_peristiwa` date NOT NULL,
  `catatan` text DEFAULT NULL,
  `no_kk` decimal(16,0) DEFAULT NULL,
  `nama_kk` varchar(100) DEFAULT NULL,
  `ref_pindah` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `log_penduduk`
--

INSERT INTO `log_penduduk` (`id`, `id_pend`, `id_detail`, `tanggal`, `bulan`, `tahun`, `tgl_peristiwa`, `catatan`, `no_kk`, `nama_kk`, `ref_pindah`) VALUES
(1, 97, 7, '2019-08-30 08:11:03', '08', '2019', '0000-00-00', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `log_perubahan_penduduk`
--

CREATE TABLE `log_perubahan_penduduk` (
  `id` int(11) NOT NULL,
  `id_pend` int(11) NOT NULL,
  `id_cluster` varchar(200) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_surat`
--

CREATE TABLE `log_surat` (
  `id` int(11) NOT NULL,
  `id_format_surat` int(3) NOT NULL,
  `id_pend` int(11) DEFAULT NULL,
  `id_pamong` int(4) NOT NULL,
  `id_user` int(4) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `bulan` varchar(2) DEFAULT NULL,
  `tahun` varchar(4) DEFAULT NULL,
  `no_surat` varchar(20) DEFAULT NULL,
  `nama_surat` varchar(100) DEFAULT NULL,
  `lampiran` varchar(100) DEFAULT NULL,
  `nik_non_warga` decimal(16,0) DEFAULT NULL,
  `nama_non_warga` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE `lokasi` (
  `id` int(4) NOT NULL,
  `desk` text NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT 1,
  `lat` varchar(30) NOT NULL,
  `lng` varchar(30) NOT NULL,
  `ref_point` int(9) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `id_cluster` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media_sosial`
--

CREATE TABLE `media_sosial` (
  `id` int(11) NOT NULL,
  `gambar` text NOT NULL,
  `link` text NOT NULL,
  `nama` varchar(100) NOT NULL,
  `enabled` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `media_sosial`
--

INSERT INTO `media_sosial` (`id`, `gambar`, `link`, `nama`, `enabled`) VALUES
(1, 'fb.png', 'https://www.facebook.com/groups/OpenSID/', 'Facebook', 1),
(2, 'twt.png', '', 'Twitter', 1),
(3, 'goo.png', '', 'Google Plus', 1),
(4, 'yb.png', '', 'YouTube', 1),
(5, 'ins.png', '', 'Instagram', 1),
(6, 'wa.png', '', 'WhatsApp', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(4) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `link` varchar(500) NOT NULL,
  `tipe` int(4) NOT NULL,
  `parrent` int(4) NOT NULL DEFAULT 1,
  `link_tipe` tinyint(1) NOT NULL DEFAULT 0,
  `enabled` int(11) NOT NULL DEFAULT 1,
  `urut` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mutasi_inventaris_asset`
--

CREATE TABLE `mutasi_inventaris_asset` (
  `id` int(11) NOT NULL,
  `id_inventaris_asset` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double NOT NULL,
  `sumbangkan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mutasi_inventaris_gedung`
--

CREATE TABLE `mutasi_inventaris_gedung` (
  `id` int(11) NOT NULL,
  `id_inventaris_gedung` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double NOT NULL,
  `sumbangkan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mutasi_inventaris_jalan`
--

CREATE TABLE `mutasi_inventaris_jalan` (
  `id` int(11) NOT NULL,
  `id_inventaris_jalan` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double NOT NULL,
  `sumbangkan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mutasi_inventaris_peralatan`
--

CREATE TABLE `mutasi_inventaris_peralatan` (
  `id` int(11) NOT NULL,
  `id_inventaris_peralatan` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double NOT NULL,
  `sumbangkan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mutasi_inventaris_tanah`
--

CREATE TABLE `mutasi_inventaris_tanah` (
  `id` int(11) NOT NULL,
  `id_inventaris_tanah` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double NOT NULL,
  `sumbangkan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `outbox`
--

CREATE TABLE `outbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `InsertIntoDB` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `SendingDateTime` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `SendBefore` time NOT NULL DEFAULT '23:59:59',
  `SendAfter` time NOT NULL DEFAULT '00:00:00',
  `Text` text DEFAULT NULL,
  `DestinationNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text DEFAULT NULL,
  `Class` int(11) DEFAULT -1,
  `TextDecoded` text NOT NULL,
  `ID` int(10) UNSIGNED NOT NULL,
  `MultiPart` enum('false','true') DEFAULT 'false',
  `RelativeValidity` int(11) DEFAULT -1,
  `SenderID` varchar(255) DEFAULT NULL,
  `SendingTimeOut` timestamp NULL DEFAULT '2019-01-01 07:00:00',
  `DeliveryReport` enum('default','yes','no') DEFAULT 'default',
  `CreatorID` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `penduduk_hidup`
--

CREATE TABLE `penduduk_hidup` (
  `id` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nik` decimal(16,0) DEFAULT NULL,
  `id_kk` int(11) DEFAULT NULL,
  `kk_level` tinyint(2) DEFAULT NULL,
  `id_rtm` int(11) DEFAULT NULL,
  `rtm_level` int(11) DEFAULT NULL,
  `sex` tinyint(4) UNSIGNED DEFAULT NULL,
  `tempatlahir` varchar(100) DEFAULT NULL,
  `tanggallahir` date DEFAULT NULL,
  `agama_id` int(10) UNSIGNED DEFAULT NULL,
  `pendidikan_kk_id` int(10) UNSIGNED DEFAULT NULL,
  `pendidikan_sedang_id` int(10) UNSIGNED DEFAULT NULL,
  `pekerjaan_id` int(10) UNSIGNED DEFAULT NULL,
  `status_kawin` tinyint(4) UNSIGNED DEFAULT NULL,
  `warganegara_id` int(10) UNSIGNED DEFAULT NULL,
  `dokumen_pasport` varchar(45) DEFAULT NULL,
  `dokumen_kitas` int(10) DEFAULT NULL,
  `ayah_nik` varchar(16) DEFAULT NULL,
  `ibu_nik` varchar(16) DEFAULT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `golongan_darah_id` int(11) DEFAULT NULL,
  `id_cluster` int(11) DEFAULT NULL,
  `status` int(10) UNSIGNED DEFAULT NULL,
  `alamat_sebelumnya` varchar(200) DEFAULT NULL,
  `alamat_sekarang` varchar(200) DEFAULT NULL,
  `status_dasar` tinyint(4) DEFAULT NULL,
  `hamil` int(1) DEFAULT NULL,
  `cacat_id` int(11) DEFAULT NULL,
  `sakit_menahun_id` int(11) DEFAULT NULL,
  `akta_lahir` varchar(40) DEFAULT NULL,
  `akta_perkawinan` varchar(40) DEFAULT NULL,
  `tanggalperkawinan` date DEFAULT NULL,
  `akta_perceraian` varchar(40) DEFAULT NULL,
  `tanggalperceraian` date DEFAULT NULL,
  `cara_kb_id` tinyint(2) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `tanggal_akhir_paspor` date DEFAULT NULL,
  `no_kk_sebelumnya` varchar(30) DEFAULT NULL,
  `ktp_el` tinyint(4) DEFAULT NULL,
  `status_rekam` tinyint(4) DEFAULT NULL,
  `waktu_lahir` varchar(5) DEFAULT NULL,
  `tempat_dilahirkan` tinyint(2) DEFAULT NULL,
  `jenis_kelahiran` tinyint(2) DEFAULT NULL,
  `kelahiran_anak_ke` tinyint(2) DEFAULT NULL,
  `penolong_kelahiran` tinyint(2) DEFAULT NULL,
  `berat_lahir` smallint(6) DEFAULT NULL,
  `panjang_lahir` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pertanyaan`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `point`
--

CREATE TABLE `point` (
  `id` int(4) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `tipe` int(4) NOT NULL,
  `parrent` int(4) NOT NULL DEFAULT 1,
  `enabled` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `polygon`
--

CREATE TABLE `polygon` (
  `id` int(4) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT 'ff0000',
  `tipe` int(4) NOT NULL,
  `parrent` int(4) DEFAULT 1,
  `enabled` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `ndesc` varchar(200) DEFAULT NULL,
  `sdate` date NOT NULL,
  `edate` date NOT NULL,
  `userid` mediumint(9) NOT NULL,
  `status` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `program_peserta`
--

CREATE TABLE `program_peserta` (
  `id` int(11) NOT NULL,
  `peserta` decimal(16,0) NOT NULL,
  `program_id` int(11) NOT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `no_id_kartu` varchar(30) DEFAULT NULL,
  `kartu_nik` decimal(16,0) DEFAULT NULL,
  `kartu_nama` varchar(100) DEFAULT NULL,
  `kartu_tempat_lahir` varchar(100) DEFAULT NULL,
  `kartu_tanggal_lahir` date DEFAULT NULL,
  `kartu_alamat` varchar(200) DEFAULT NULL,
  `kartu_peserta` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `provinsi`
--

CREATE TABLE `provinsi` (
  `kode` tinyint(2) NOT NULL DEFAULT 0,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `provinsi`
--

INSERT INTO `provinsi` (`kode`, `nama`) VALUES
(11, 'Aceh'),
(12, 'Sumatera Utara'),
(13, 'Sumatera Barat'),
(14, 'Riau'),
(15, 'Jambi'),
(16, 'Sumatera Selatan'),
(17, 'Bengkulu'),
(18, 'Lampung'),
(19, 'Kepulauan Bangka Belitung'),
(21, 'Kepulauan Riau'),
(31, 'DKI Jakarta'),
(32, 'Jawa Barat'),
(33, 'Jawa Tengah'),
(34, 'DI Yogyakarta'),
(35, 'Jawa Timur'),
(36, 'Banten'),
(51, 'Bali'),
(52, 'Nusa Tenggara Barat'),
(53, 'Nusa Tenggara Timur'),
(61, 'Kalimantan Barat'),
(62, 'Kalimantan Tengah'),
(63, 'Kalimantan Selatan'),
(64, 'Kalimantan Timur'),
(65, 'Kalimantan Utara'),
(71, 'Sulawesi Utara'),
(72, 'Sulawesi Tengah'),
(73, 'Sulawesi Selatan'),
(74, 'Sulawesi Tenggara'),
(75, 'Gorontalo'),
(76, 'Sulawesi Barat'),
(81, 'Maluku'),
(82, 'Maluku Utara'),
(91, 'Papua'),
(92, 'Papua Barat');

-- --------------------------------------------------------

--
-- Table structure for table `ref_pindah`
--

CREATE TABLE `ref_pindah` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ref_pindah`
--

INSERT INTO `ref_pindah` (`id`, `nama`) VALUES
(1, 'Pindah keluar Desa/Kelurahan'),
(2, 'Pindah keluar Kecamatan'),
(3, 'Pindah keluar Kabupaten/Kota'),
(4, 'Pindah keluar Provinsi');

-- --------------------------------------------------------

--
-- Table structure for table `sentitems`
--

CREATE TABLE `sentitems` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `InsertIntoDB` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `SendingDateTime` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `DeliveryDateTime` timestamp NULL DEFAULT NULL,
  `Text` text NOT NULL,
  `DestinationNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text NOT NULL,
  `SMSCNumber` varchar(20) NOT NULL DEFAULT '',
  `Class` int(11) NOT NULL DEFAULT -1,
  `TextDecoded` text NOT NULL,
  `ID` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `SenderID` varchar(255) NOT NULL,
  `SequencePosition` int(11) NOT NULL DEFAULT 1,
  `Status` enum('SendingOK','SendingOKNoReport','SendingError','DeliveryOK','DeliveryFailed','DeliveryPending','DeliveryUnknown','Error') NOT NULL DEFAULT 'SendingOK',
  `StatusError` int(11) NOT NULL DEFAULT -1,
  `TPMR` int(11) NOT NULL DEFAULT -1,
  `RelativeValidity` int(11) NOT NULL DEFAULT -1,
  `CreatorID` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `setting_aplikasi`
--

CREATE TABLE `setting_aplikasi` (
  `id` int(11) NOT NULL,
  `key` varchar(50) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `jenis` varchar(30) DEFAULT NULL,
  `kategori` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting_aplikasi`
--

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
(10, 'admin_title', 'Sistem Informasi Desa', 'Judul tab browser modul administrasi', '', ''),
(11, 'web_theme', 'labs', 'Tema penampilan modul web', '', 'web'),
(12, 'offline_mode', '0', 'Apakah modul web akan ditampilkan atau tidak', '', ''),
(13, 'enable_track', '1', 'Apakah akan mengirimkan data statistik ke tracker', 'boolean', ''),
(14, 'dev_tracker', '', 'Host untuk tracker pada development', '', 'development'),
(16, 'google_key', '', 'Google API Key untuk Google Maps', '', 'web'),
(17, 'libreoffice_path', '', 'Path tempat instal libreoffice di server SID', '', ''),
(18, 'sumber_gambar_slider', '1', 'Sumber gambar slider besar', NULL, NULL),
(19, 'sebutan_singkatan_kadus', 'kawil', 'Sebutan singkatan jabatan kepala dusun', NULL, NULL),
(20, 'current_version', '19.06', 'Versi sekarang untuk migrasi', NULL, 'readonly'),
(21, 'timezone', 'Asia/Jakarta', 'Zona waktu perekaman waktu dan tanggal', NULL, NULL),
(22, 'tombol_cetak_surat', '0', 'Tampilkan tombol cetak langsung di form surat', 'boolean', NULL),
(23, 'web_artikel_per_page', '8', 'Jumlah artikel dalam satu halaman', 'int', 'web_theme'),
(24, 'penomoran_surat', '2', 'Penomoran surat mulai dari satu (1) setiap tahun', 'option', NULL),
(25, 'dashboard_program_bantuan', '1', 'ID program bantuan yang ditampilkan di dashboard', 'int', 'dashboard'),
(26, 'panjang_nomor_surat', '', 'Nomor akan diisi \'0\' di sebelah kiri, kalau perlu', 'int', 'surat'),
(27, 'warna_tema_admin', 'skin-purple', 'Warna dasar tema komponen Admin', 'option-value', NULL),
(28, 'format_nomor_surat', '[kode_surat]/[nomor_surat, 3]/PEM/[tahun]', 'Fomat penomoran surat', NULL, NULL),
(29, 'sebutan_pimpinan_desa', 'Kepala Desa', 'Sebutan pimpinan desa', NULL, 'pemerintahan');

-- --------------------------------------------------------

--
-- Table structure for table `setting_aplikasi_options`
--

CREATE TABLE `setting_aplikasi_options` (
  `id` int(11) NOT NULL,
  `id_setting` int(11) NOT NULL,
  `value` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting_aplikasi_options`
--

INSERT INTO `setting_aplikasi_options` (`id`, `id_setting`, `value`) VALUES
(1, 24, 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk semua surat layanan'),
(2, 24, 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk setiap surat layanan dengan jenis yang sama'),
(3, 24, 'Nomor berurutan untuk keseluruhan surat layanan, masuk dan keluar'),
(4, 27, 'skin-blue'),
(5, 27, 'skin-blue-light'),
(6, 27, 'skin-yellow'),
(7, 27, 'skin-yellow-light'),
(8, 27, 'skin-green'),
(9, 27, 'skin-green-light'),
(10, 27, 'skin-purple'),
(11, 27, 'skin-purple-light'),
(12, 27, 'skin-red'),
(13, 27, 'skin-red-light'),
(14, 27, 'skin-black'),
(15, 27, 'skin-black-light');

-- --------------------------------------------------------

--
-- Table structure for table `setting_modul`
--

CREATE TABLE `setting_modul` (
  `id` int(11) NOT NULL,
  `modul` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 0,
  `ikon` varchar(50) NOT NULL,
  `urut` tinyint(4) NOT NULL,
  `level` tinyint(1) NOT NULL DEFAULT 2,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `ikon_kecil` varchar(50) NOT NULL,
  `parent` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting_modul`
--

INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES
(1, 'Home', 'hom_sid', 1, 'fa-home', 1, 2, 1, 'fa fa-home', 0),
(2, 'Kependudukan', 'penduduk/clear', 1, 'fa-users', 3, 2, 0, 'fa fa-users', 0),
(3, 'Statistik', 'statistik', 1, 'fa-line-chart', 4, 2, 0, 'fa fa-line-chart', 0),
(4, 'Layanan Surat', 'surat', 1, 'fa-book', 5, 2, 0, 'fa fa-book', 0),
(5, 'Analisis', 'analisis_master/clear', 1, '   fa-check-square-o', 6, 2, 0, 'fa fa-check-square-o', 0),
(6, 'Bantuan', 'program_bantuan/clear', 1, 'fa-heart', 7, 2, 0, 'fa fa-heart', 0),
(7, 'Pertanahan', 'data_persil/clear', 1, 'fa-map-signs', 8, 2, 0, 'fa fa-map-signs', 0),
(8, 'Pengaturan Peta', 'plan', 1, 'fa-location-arrow', 9, 2, 0, 'fa fa-location-arrow', 9),
(9, 'Pemetaan', 'gis', 1, 'fa-globe', 10, 2, 0, 'fa fa-globe', 0),
(10, 'SMS', 'sms', 1, 'fa-envelope', 11, 2, 0, 'fa fa-envelope', 0),
(11, 'Pengaturan', 'man_user/clear', 1, 'fa-users', 12, 1, 1, 'fa-users', 0),
(13, 'Admin Web', 'web', 1, 'fa-desktop', 14, 4, 0, 'fa fa-desktop', 0),
(14, 'Layanan Mandiri', 'lapor', 1, 'fa-inbox', 15, 2, 0, 'fa fa-inbox', 0),
(15, 'Sekretariat', 'sekretariat', 1, 'fa-archive', 5, 2, 0, 'fa fa-archive', 0),
(17, 'Identitas [Desa]', 'hom_desa/konfigurasi', 1, 'fa-id-card', 2, 2, 0, '', 200),
(18, 'Pemerintahan [Desa]', 'pengurus/clear', 1, 'fa-sitemap', 3, 2, 0, '', 200),
(20, 'Wilayah Administratif', 'sid_core/clear', 1, 'fa-map', 2, 2, 0, '', 200),
(21, 'Penduduk', 'penduduk/clear', 1, 'fa-user', 2, 2, 0, '', 2),
(22, 'Keluarga', 'keluarga/clear', 1, 'fa-users', 3, 2, 0, '', 2),
(23, 'Rumah Tangga', 'rtm/clear', 1, 'fa-venus-mars', 4, 2, 0, '', 2),
(24, 'Kelompok', 'kelompok/clear', 1, 'fa-sitemap', 5, 2, 0, '', 2),
(25, 'Data Suplemen', 'suplemen', 1, 'fa-slideshare', 6, 2, 0, '', 2),
(26, 'Calon Pemilih', 'dpt/clear', 1, 'fa-podcast', 7, 2, 0, '', 2),
(27, 'Statistik Kependudukan', 'statistik', 1, 'fa-bar-chart', 1, 2, 0, '', 3),
(28, 'Laporan Bulanan', 'laporan/clear', 1, 'fa-file-text', 2, 2, 0, '', 3),
(29, 'Laporan Kelompok Rentan', 'laporan_rentan/clear', 1, 'fa-wheelchair', 3, 2, 0, '', 3),
(30, 'Pengaturan Surat', 'surat_master/clear', 1, 'fa-cog', 1, 2, 0, '', 4),
(31, 'Cetak Surat', 'surat', 1, 'fa-files-o', 2, 2, 0, '', 4),
(32, 'Arsip Layanan', 'keluar/clear', 1, 'fa-folder-open', 3, 2, 0, '', 4),
(33, 'Panduan', 'surat/panduan', 1, 'fa fa-book', 4, 2, 0, '', 4),
(39, 'SMS', 'sms', 1, 'fa-envelope-open-o', 1, 2, 0, '', 10),
(40, 'Daftar Kontak', 'sms/kontak', 1, 'fa-id-card-o', 2, 2, 0, '', 10),
(41, 'Pengaturan SMS', 'sms/setting', 1, 'fa-gear', 3, 2, 0, '', 10),
(42, 'Modul', 'modul/clear', 1, 'fa-tags', 1, 1, 0, '', 11),
(43, 'Aplikasi', 'setting', 1, 'fa-codepen', 2, 1, 0, '', 11),
(44, 'Pengguna', 'man_user', 1, 'fa-users', 3, 1, 0, '', 11),
(45, 'Database', 'database', 1, 'fa-database', 4, 1, 0, '', 11),
(46, 'Info Sistem', 'setting/info_sistem', 1, 'fa-server', 5, 1, 0, '', 11),
(47, 'Artikel', 'web/clear', 1, 'fa-file-movie-o', 1, 4, 0, '', 13),
(48, 'Widget', 'web_widget/clear', 1, 'fa-windows', 2, 4, 0, '', 13),
(49, 'Menu', 'menu/clear', 1, 'fa-bars', 3, 4, 0, '', 13),
(50, 'Komentar', 'komentar/clear', 1, 'fa-comments', 4, 4, 0, '', 13),
(51, 'Galeri', 'gallery', 1, 'fa-image', 5, 5, 0, '', 13),
(52, 'Dokumen', 'dokumen/clear', 1, 'fa-file-text', 6, 4, 0, '', 13),
(53, 'Media Sosial', 'sosmed', 1, 'fa-facebook', 7, 4, 0, '', 13),
(54, 'Slider', 'web/slider', 1, 'fa-film', 8, 4, 0, '', 13),
(55, 'Laporan Masuk', 'lapor', 1, 'fa-wechat', 1, 2, 0, '', 14),
(56, 'Pendaftar Layanan Mandiri', 'mandiri/clear', 1, 'fa-500px', 2, 2, 0, '', 14),
(57, 'Surat Masuk', 'surat_masuk/clear', 1, 'fa-sign-in', 1, 2, 0, '', 15),
(58, 'Surat Keluar', 'surat_keluar/clear', 1, 'fa-sign-out', 2, 2, 0, '', 15),
(59, 'SK Kades', 'dokumen_sekretariat/clear/2', 1, 'fa-legal', 3, 2, 0, '', 15),
(60, 'Perdes', 'dokumen_sekretariat/clear/3', 1, 'fa-newspaper-o', 4, 2, 0, '', 15),
(61, 'Inventaris', 'inventaris_tanah', 1, 'fa-cubes', 5, 2, 0, '', 15),
(62, 'Peta', 'gis/clear', 1, 'fa-globe', 1, 2, 0, 'fa fa-globe', 9),
(63, 'Klasfikasi Surat', 'klasifikasi/clear', 1, 'fa-code', 10, 2, 0, 'fa-code', 15),
(64, 'Teks Berjalan', 'teks_berjalan', 1, 'fa-ellipsis-h', 9, 2, 0, 'fa-ellipsis-h', 13),
(200, 'Info [Desa]', 'hom_desa', 1, 'fa-dashboard', 2, 2, 1, 'fa fa-home', 0);

-- --------------------------------------------------------

--
-- Table structure for table `setting_sms`
--

CREATE TABLE `setting_sms` (
  `autoreply_text` varchar(160) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suplemen`
--

CREATE TABLE `suplemen` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `keterangan` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suplemen_terdata`
--

CREATE TABLE `suplemen_terdata` (
  `id` int(11) NOT NULL,
  `id_suplemen` int(10) DEFAULT NULL,
  `id_terdata` varchar(20) DEFAULT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `surat_keluar`
--

CREATE TABLE `surat_keluar` (
  `id` int(11) NOT NULL,
  `nomor_urut` smallint(5) DEFAULT NULL,
  `nomor_surat` varchar(35) DEFAULT NULL,
  `kode_surat` varchar(10) DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `tanggal_catat` timestamp NOT NULL DEFAULT current_timestamp(),
  `tujuan` varchar(100) DEFAULT NULL,
  `isi_singkat` varchar(200) DEFAULT NULL,
  `berkas_scan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `surat_masuk`
--

CREATE TABLE `surat_masuk` (
  `id` int(11) NOT NULL,
  `nomor_urut` smallint(5) DEFAULT NULL,
  `tanggal_penerimaan` date NOT NULL,
  `nomor_surat` varchar(35) DEFAULT NULL,
  `kode_surat` varchar(10) DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `pengirim` varchar(100) DEFAULT NULL,
  `isi_singkat` varchar(200) DEFAULT NULL,
  `isi_disposisi` varchar(200) DEFAULT NULL,
  `berkas_scan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sys_traffic`
--

CREATE TABLE `sys_traffic` (
  `Tanggal` date NOT NULL,
  `ipAddress` text NOT NULL,
  `Jumlah` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_traffic`
--

INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES
('2019-09-02', '::1{}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teks_berjalan`
--

CREATE TABLE `teks_berjalan` (
  `id` int(11) NOT NULL,
  `teks` text DEFAULT NULL,
  `urut` int(5) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '2019-01-01 07:00:00',
  `updated_by` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `tautan` varchar(150) DEFAULT NULL,
  `judul_tautan` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_cacat`
--

CREATE TABLE `tweb_cacat` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Table structure for table `tweb_cara_kb`
--

CREATE TABLE `tweb_cara_kb` (
  `id` tinyint(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `sex` tinyint(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tweb_cara_kb`
--

INSERT INTO `tweb_cara_kb` (`id`, `nama`, `sex`) VALUES
(1, 'Pil', 2),
(2, 'IUD', 2),
(3, 'Suntik', 2),
(4, 'Kondom', 1),
(5, 'Susuk KB', 2),
(6, 'Sterilisasi Wanita', 2),
(7, 'Sterilisasi Pria', 1),
(99, 'Lainnya', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tweb_desa_pamong`
--

CREATE TABLE `tweb_desa_pamong` (
  `pamong_id` int(5) NOT NULL,
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
  `pamong_tglhenti` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_golongan_darah`
--

CREATE TABLE `tweb_golongan_darah` (
  `id` int(11) NOT NULL,
  `nama` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tweb_golongan_darah`
--

INSERT INTO `tweb_golongan_darah` (`id`, `nama`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'AB');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_keluarga`
--

CREATE TABLE `tweb_keluarga` (
  `id` int(10) NOT NULL,
  `no_kk` varchar(160) DEFAULT NULL,
  `nik_kepala` varchar(200) DEFAULT NULL,
  `tgl_daftar` timestamp NULL DEFAULT current_timestamp(),
  `kelas_sosial` int(4) DEFAULT NULL,
  `tgl_cetak_kk` datetime DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `id_cluster` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_keluarga_sejahtera`
--

CREATE TABLE `tweb_keluarga_sejahtera` (
  `id` int(10) NOT NULL DEFAULT 0,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tweb_keluarga_sejahtera`
--

INSERT INTO `tweb_keluarga_sejahtera` (`id`, `nama`) VALUES
(1, 'Keluarga Pra Sejahtera'),
(2, 'Keluarga Sejahtera I'),
(3, 'Keluarga Sejahtera II'),
(4, 'Keluarga Sejahtera III'),
(5, 'Keluarga Sejahtera III Plus');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk`
--

CREATE TABLE `tweb_penduduk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` decimal(16,0) NOT NULL,
  `id_kk` int(11) DEFAULT 0,
  `kk_level` tinyint(2) NOT NULL DEFAULT 0,
  `id_rtm` int(11) NOT NULL,
  `rtm_level` int(11) NOT NULL,
  `sex` tinyint(4) UNSIGNED DEFAULT NULL,
  `tempatlahir` varchar(100) NOT NULL,
  `tanggallahir` date DEFAULT NULL,
  `agama_id` int(10) UNSIGNED NOT NULL,
  `pendidikan_kk_id` int(10) UNSIGNED NOT NULL,
  `pendidikan_sedang_id` int(10) UNSIGNED NOT NULL,
  `pekerjaan_id` int(10) UNSIGNED NOT NULL,
  `status_kawin` tinyint(4) UNSIGNED NOT NULL,
  `warganegara_id` int(10) UNSIGNED NOT NULL,
  `dokumen_pasport` varchar(45) DEFAULT NULL,
  `dokumen_kitas` int(10) DEFAULT NULL,
  `ayah_nik` varchar(16) NOT NULL,
  `ibu_nik` varchar(16) NOT NULL,
  `nama_ayah` varchar(100) NOT NULL,
  `nama_ibu` varchar(100) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `golongan_darah_id` int(11) NOT NULL,
  `id_cluster` int(11) NOT NULL,
  `status` int(10) UNSIGNED DEFAULT NULL,
  `alamat_sebelumnya` varchar(200) NOT NULL,
  `alamat_sekarang` varchar(200) NOT NULL,
  `status_dasar` tinyint(4) NOT NULL DEFAULT 1,
  `hamil` int(1) DEFAULT NULL,
  `cacat_id` int(11) DEFAULT NULL,
  `sakit_menahun_id` int(11) DEFAULT NULL,
  `akta_lahir` varchar(40) NOT NULL,
  `akta_perkawinan` varchar(40) NOT NULL,
  `tanggalperkawinan` date DEFAULT NULL,
  `akta_perceraian` varchar(40) NOT NULL,
  `tanggalperceraian` date DEFAULT NULL,
  `cara_kb_id` tinyint(2) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `tanggal_akhir_paspor` date DEFAULT NULL,
  `no_kk_sebelumnya` varchar(30) DEFAULT NULL,
  `ktp_el` tinyint(4) DEFAULT NULL,
  `status_rekam` tinyint(4) DEFAULT NULL,
  `waktu_lahir` varchar(5) NOT NULL,
  `tempat_dilahirkan` tinyint(2) DEFAULT NULL,
  `jenis_kelahiran` tinyint(2) DEFAULT NULL,
  `kelahiran_anak_ke` tinyint(2) DEFAULT NULL,
  `penolong_kelahiran` tinyint(2) DEFAULT NULL,
  `berat_lahir` smallint(6) DEFAULT NULL,
  `panjang_lahir` varchar(10) DEFAULT NULL,
  `tag_id_card` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_agama`
--

CREATE TABLE `tweb_penduduk_agama` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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

CREATE TABLE `tweb_penduduk_hubungan` (
  `id` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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
(9, 'FAMILI'),
(10, 'PEMBANTU'),
(11, 'LAINNYA');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_kawin`
--

CREATE TABLE `tweb_penduduk_kawin` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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
-- Table structure for table `tweb_penduduk_mandiri`
--

CREATE TABLE `tweb_penduduk_mandiri` (
  `pin` char(32) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `tanggal_buat` datetime DEFAULT NULL,
  `id_pend` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tweb_penduduk_mandiri`
--

INSERT INTO `tweb_penduduk_mandiri` (`pin`, `last_login`, `tanggal_buat`, `id_pend`) VALUES
('3645e735f033e8482be0c7993fcba946', '2019-08-30 14:28:11', '2019-08-28 03:29:23', 1),
('3645e735f033e8482be0c7993fcba946', '2016-09-14 12:53:47', '2016-09-14 06:06:32', 2),
('3645e735f033e8482be0c7993fcba946', '2016-09-14 12:51:53', '2016-09-14 10:10:47', 20);

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_map`
--

CREATE TABLE `tweb_penduduk_map` (
  `id` int(11) NOT NULL,
  `lat` varchar(24) NOT NULL,
  `lng` varchar(24) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_pekerjaan`
--

CREATE TABLE `tweb_penduduk_pekerjaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

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
(9, 'PETANI/PEKEBUN'),
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
(88, 'WIRASWASTA'),
(89, 'LAINNYA');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_pendidikan`
--

CREATE TABLE `tweb_penduduk_pendidikan` (
  `id` tinyint(3) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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

CREATE TABLE `tweb_penduduk_pendidikan_kk` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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

CREATE TABLE `tweb_penduduk_sex` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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

CREATE TABLE `tweb_penduduk_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `tweb_penduduk_umur` (
  `id` int(11) NOT NULL,
  `nama` varchar(25) DEFAULT NULL,
  `dari` int(11) DEFAULT NULL,
  `sampai` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tweb_penduduk_umur`
--

INSERT INTO `tweb_penduduk_umur` (`id`, `nama`, `dari`, `sampai`, `status`) VALUES
(1, 'BALITA', 0, 5, 0),
(2, 'ANAK-ANAK', 6, 17, 0),
(3, 'DEWASA', 18, 30, 0),
(4, 'TUA', 31, 120, 0),
(6, 'Di bawah 1 Tahun', 0, 1, 1),
(9, '2 s/d 4 Tahun', 2, 4, 1),
(12, '5 s/d 9 Tahun', 5, 9, 1),
(13, '10 s/d 14 Tahun', 10, 14, 1),
(14, '15 s/d 19 Tahun', 15, 19, 1),
(15, '20 s/d 24 Tahun', 20, 24, 1),
(16, '25 s/d 29 Tahun', 25, 29, 1),
(17, '30 s/d 34 Tahun', 30, 34, 1),
(18, '35 s/d 39 Tahun ', 35, 39, 1),
(19, '40 s/d 44 Tahun', 40, 44, 1),
(20, '45 s/d 49 Tahun', 45, 49, 1),
(21, '50 s/d 54 Tahun', 50, 54, 1),
(22, '55 s/d 59 Tahun', 55, 59, 1),
(23, '60 s/d 64 Tahun', 60, 64, 1),
(24, '65 s/d 69 Tahun', 65, 69, 1),
(25, '70 s/d 74 Tahun', 70, 74, 1),
(26, 'Di atas 75 Tahun', 75, 99999, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tweb_penduduk_warganegara`
--

CREATE TABLE `tweb_penduduk_warganegara` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `tweb_rtm` (
  `id` int(11) NOT NULL,
  `nik_kepala` int(11) NOT NULL,
  `no_kk` varchar(20) NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kelas_sosial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_rtm_hubungan`
--

CREATE TABLE `tweb_rtm_hubungan` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `tweb_sakit_menahun` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `tweb_status_dasar` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tweb_status_dasar`
--

INSERT INTO `tweb_status_dasar` (`id`, `nama`) VALUES
(1, 'HIDUP'),
(2, 'MATI'),
(3, 'PINDAH'),
(4, 'HILANG'),
(9, 'TIDAK VALID');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_status_ktp`
--

CREATE TABLE `tweb_status_ktp` (
  `id` tinyint(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `ktp_el` tinyint(4) NOT NULL,
  `status_rekam` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tweb_status_ktp`
--

INSERT INTO `tweb_status_ktp` (`id`, `nama`, `ktp_el`, `status_rekam`) VALUES
(1, 'BELUM REKAM', 1, '2'),
(2, 'SUDAH REKAM', 2, '3'),
(3, 'CARD PRINTED', 2, '4'),
(4, 'PRINT READY RECORD', 2, '5'),
(5, 'CARD SHIPPED', 2, '6'),
(6, 'SENT FOR CARD PRINTING', 2, '7'),
(7, 'CARD ISSUED', 2, '8');

-- --------------------------------------------------------

--
-- Table structure for table `tweb_surat_atribut`
--

CREATE TABLE `tweb_surat_atribut` (
  `id` int(11) NOT NULL,
  `id_surat` int(11) NOT NULL,
  `id_tipe` tinyint(4) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `long` tinyint(4) NOT NULL,
  `kode` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tweb_surat_format`
--

CREATE TABLE `tweb_surat_format` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `url_surat` varchar(100) NOT NULL,
  `kode_surat` varchar(10) NOT NULL,
  `lampiran` varchar(100) DEFAULT NULL,
  `kunci` tinyint(1) NOT NULL DEFAULT 0,
  `favorit` tinyint(1) NOT NULL DEFAULT 0,
  `jenis` tinyint(2) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tweb_surat_format`
--

INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`) VALUES
(1, 'Keterangan Pengantar', 'surat_ket_pengantar', 'S-01', NULL, 0, 0, 1),
(2, 'Keterangan Penduduk', 'surat_ket_penduduk', 'S-02', NULL, 0, 0, 1),
(3, 'Biodata Penduduk', 'surat_bio_penduduk', 'S-03', 'f-1.01.php', 0, 0, 1),
(5, 'Keterangan Pindah Penduduk', 'surat_ket_pindah_penduduk', 'S-04', 'f-1.08.php,f-1.25.php', 0, 0, 1),
(6, 'Keterangan Jual Beli', 'surat_ket_jual_beli', 'S-05', NULL, 0, 0, 1),
(8, 'Pengantar Surat Keterangan Catatan Kepolisian', 'surat_ket_catatan_kriminal', 'S-07', NULL, 0, 0, 1),
(9, 'Keterangan KTP dalam Proses', 'surat_ket_ktp_dalam_proses', 'S-08', NULL, 0, 0, 1),
(10, 'Keterangan Beda Identitas', 'surat_ket_beda_nama', 'S-09', NULL, 0, 0, 1),
(11, 'Keterangan Bepergian / Jalan', 'surat_jalan', 'S-10', NULL, 0, 0, 1),
(12, 'Keterangan Kurang Mampu', 'surat_ket_kurang_mampu', 'S-11', NULL, 0, 0, 1),
(13, 'Pengantar Izin Keramaian', 'surat_izin_keramaian', 'S-12', NULL, 0, 0, 1),
(14, 'Pengantar Laporan Kehilangan', 'surat_ket_kehilangan', 'S-13', NULL, 0, 0, 1),
(15, 'Keterangan Usaha', 'surat_ket_usaha', 'S-14', NULL, 0, 0, 1),
(16, 'Keterangan JAMKESOS', 'surat_ket_jamkesos', 'S-15', NULL, 0, 0, 1),
(17, 'Keterangan Domisili Usaha', 'surat_ket_domisili_usaha', 'S-16', NULL, 0, 0, 1),
(18, 'Keterangan Kelahiran', 'surat_ket_kelahiran', 'S-17', 'f-2.01.php', 0, 0, 1),
(20, 'Permohonan Akta Lahir', 'surat_permohonan_akta', 'S-18', NULL, 0, 0, 1),
(21, 'Pernyataan Belum Memiliki Akta Lahir', 'surat_pernyataan_akta', 'S-19', NULL, 0, 0, 1),
(22, 'Permohonan Duplikat Kelahiran', 'surat_permohonan_duplikat_kelahiran', 'S-20', NULL, 0, 0, 1),
(24, 'Keterangan Kematian', 'surat_ket_kematian', 'S-21', 'f-2.29.php', 0, 0, 1),
(25, 'Keterangan Lahir Mati', 'surat_ket_lahir_mati', 'S-22', NULL, 0, 0, 1),
(26, 'Keterangan Untuk Nikah (N-1 s/d N-7)', 'surat_ket_nikah', 'S-23', NULL, 0, 0, 1),
(33, 'Keterangan Pergi Kawin', 'surat_ket_pergi_kawin', 'S-30', NULL, 0, 0, 1),
(35, 'Keterangan Wali Hakim', 'surat_ket_wali_hakim', 'S-32', NULL, 0, 0, 1),
(36, 'Permohonan Duplikat Surat Nikah', 'surat_permohonan_duplikat_surat_nikah', 'S-33', NULL, 0, 0, 1),
(37, 'Permohonan Cerai', 'surat_permohonan_cerai', 'S-34', NULL, 0, 0, 1),
(38, 'Keterangan Pengantar Rujuk/Cerai', 'surat_ket_rujuk_cerai', 'S-35', NULL, 0, 0, 1),
(45, 'Permohonan Kartu Keluarga', 'surat_permohonan_kartu_keluarga', 'S-36', 'f-1.15.php,f-1.01.php', 0, 0, 1),
(51, 'Domisili Usaha Non-Warga', 'surat_domisili_usaha_non_warga', 'S-37', NULL, 0, 0, 1),
(76, 'Keterangan Beda Identitas KIS', 'surat_ket_beda_identitas_kis', 'S-38', NULL, 0, 0, 1),
(85, 'Keterangan Izin Orang Tua/Suami/Istri', 'surat_izin_orangtua_suami_istri', 'S-39', NULL, 0, 0, 1),
(86, 'Pernyataan Penguasaan Fisik Bidang Tanah (SPORADIK)', 'surat_sporadik', 'S-40', NULL, 0, 0, 1),
(89, 'Permohonan Perubahan Kartu Keluarga', 'surat_permohonan_perubahan_kartu_keluarga', 'S-41', 'f-1.16.php,f-1.01.php', 0, 0, 1),
(110, 'Non Warga', 'surat_non_warga', '', NULL, 0, 0, 2),
(156, 'Keterangan Domisili', 'surat_ket_domisili', 'S-41', NULL, 0, 0, 1),
(160, 'Keterangan Penghasilan Orangtua', 'surat_ket_penghasilan_orangtua', 'S-42', NULL, 0, 0, 1),
(161, 'Raw', 'raw', '', NULL, 0, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tweb_wil_clusterdesa`
--

CREATE TABLE `tweb_wil_clusterdesa` (
  `id` int(11) NOT NULL,
  `rt` varchar(10) NOT NULL DEFAULT '0',
  `rw` varchar(10) NOT NULL DEFAULT '0',
  `dusun` varchar(50) NOT NULL DEFAULT '0',
  `id_kepala` int(11) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `lng` varchar(20) NOT NULL,
  `zoom` int(5) NOT NULL,
  `path` text NOT NULL,
  `map_tipe` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `id_grup` int(5) NOT NULL,
  `email` varchar(100) NOT NULL,
  `last_login` datetime NOT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT 0,
  `nama` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `foto` varchar(100) NOT NULL,
  `session` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `id_grup`, `email`, `last_login`, `active`, `nama`, `company`, `phone`, `foto`, `session`) VALUES
(1, 'admin', '$2y$10$T.C45Bhhq3D8bqtn70Tyzuu/sXW.NfO43zuNAy9bLjfQCyweQ.5Ca', 1, 'admin@combine.or.id', '2019-09-01 10:33:51', 1, 'Administrator', 'ADMIN', '321', 'favicon.png', 'a8d4080245664ed2049c1b2ded7cac30');

-- --------------------------------------------------------

--
-- Table structure for table `user_grup`
--

CREATE TABLE `user_grup` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_grup`
--

INSERT INTO `user_grup` (`id`, `nama`) VALUES
(1, 'Administrator'),
(2, 'Operator'),
(3, 'Redaksi'),
(4, 'Kontributor');

-- --------------------------------------------------------

--
-- Table structure for table `widget`
--

CREATE TABLE `widget` (
  `id` int(11) NOT NULL,
  `isi` text DEFAULT NULL,
  `enabled` int(2) DEFAULT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `jenis_widget` tinyint(2) NOT NULL DEFAULT 3,
  `urut` int(5) DEFAULT NULL,
  `form_admin` varchar(100) NOT NULL,
  `setting` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `widget`
--

INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES
(1, '<p><iframe src=\"https://www.google.co.id/maps?f=q&source=s_q&hl=en&geocode=&q=Logandu,+Karanggayam&aq=0&oq=logan&sll=-2.550221,118.015568&sspn=52.267573,80.332031&t=h&ie=UTF8&hq=&hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&ll=-7.55854,109.634173&spn=0.052497,0.078449&z=14&output=embed\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" width=\"100%\"></iframe></p> ', 2, 'Peta Desa', 3, 3, '', ''),
(2, 'layanan_mandiri.php', 1, 'Layanan Mandiri', 1, 1, 'mandiri', ''),
(3, 'agenda.php', 1, 'Agenda', 1, 7, 'web/index/1000', ''),
(4, 'galeri.php', 1, 'Galeri', 1, 8, 'gallery', ''),
(5, 'statistik.php', 1, 'Statistik', 1, 9, '', ''),
(6, 'komentar.php', 1, 'Komentar', 1, 10, 'komentar', ''),
(7, 'media_sosial.php', 1, 'Media Sosial', 1, 11, 'sosmed', ''),
(8, 'peta_lokasi_kantor.php', 1, 'Peta Lokasi Kantor', 1, 12, 'hom_desa', ''),
(9, 'statistik_pengunjung.php', 1, 'Statistik Pengunjung', 1, 13, '', ''),
(10, 'arsip_artikel.php', 1, 'Arsip Artikel', 1, 14, '', ''),
(11, 'aparatur_desa.php', 1, 'Aparatur Desa', 1, 5, 'web_widget/admin/aparatur_desa', ''),
(12, 'sinergi_program.php', 1, 'Sinergi Program', 1, 6, 'web_widget/admin/sinergi_program', '[{\"baris\":\"1\",\"kolom\":\"1\",\"judul\":\"HHHHHH\",\"old_gambar\":\"1566946172_1532855102665.jpg\",\"tautan\":\"asasasas\",\"gambar\":\"1566946172_1532855102665.jpg\"},{\"baris\":\"2\",\"kolom\":\"2\",\"judul\":\"sdsdsdsds\",\"old_gambar\":\"1567170837_pic_001-(1).jpg\",\"tautan\":\"asasasa\",\"gambar\":\"1567170837_pic_001-(1).jpg\"}]'),
(13, 'menu_kategori.php', 1, 'Menu Kategori', 1, 4, '', ''),
(14, 'peta_wilayah_desa.php', 1, 'Peta Wilayah Desa', 1, 2, 'hom_desa/konfigurasi', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
