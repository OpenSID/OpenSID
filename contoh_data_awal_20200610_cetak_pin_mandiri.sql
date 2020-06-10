-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Jun 2020 pada 18.28
-- Versi server: 10.4.8-MariaDB
-- Versi PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opensid`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `id_artikel` int(11) NOT NULL,
  `tgl_agenda` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `koordinator_kegiatan` varchar(50) NOT NULL,
  `lokasi_kegiatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `agenda`
--

INSERT INTO `agenda` (`id`, `id_artikel`, `tgl_agenda`, `koordinator_kegiatan`, `lokasi_kegiatan`) VALUES
(1, 83, '2020-01-07 22:58:42', '', 'Aula Desa'),
(2, 41, '2020-04-08 22:59:18', '', 'Ruang rapat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_indikator`
--

CREATE TABLE `analisis_indikator` (
  `id` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `nomor` int(3) NOT NULL,
  `pertanyaan` varchar(400) NOT NULL,
  `id_tipe` tinyint(4) NOT NULL DEFAULT 1,
  `bobot` tinyint(4) NOT NULL DEFAULT 0,
  `act_analisis` tinyint(1) NOT NULL DEFAULT 2,
  `id_kategori` int(4) NOT NULL,
  `is_publik` tinyint(1) NOT NULL DEFAULT 0,
  `is_teks` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `analisis_indikator`
--

INSERT INTO `analisis_indikator` (`id`, `id_master`, `nomor`, `pertanyaan`, `id_tipe`, `bobot`, `act_analisis`, `id_kategori`, `is_publik`, `is_teks`) VALUES
(1, 2, 1, 'kepemilikan rumah', 1, 1, 1, 1, 0, 0),
(2, 2, 2, 'penghasilan perbulan', 1, 4, 1, 2, 0, 0),
(3, 3, 1, 'Jumlah Penghasilan Perbulan', 3, 0, 0, 3, 0, 0),
(4, 3, 2, 'Jumlah Pengeluaran Perbulan', 3, 0, 0, 3, 0, 0),
(5, 3, 3, 'Status Kepemilikan Rumah?*', 1, 0, 0, 3, 0, 0),
(6, 3, 4, 'Kategori KK', 1, 0, 0, 3, 0, 0),
(7, 3, 5, 'Penerima Raskin', 1, 0, 0, 3, 0, 0),
(8, 3, 6, 'Penerima BLT/BLSM', 1, 0, 0, 3, 0, 0),
(9, 3, 7, 'Peserta BPJS/Jamkesmas/Jamkesda', 1, 0, 0, 3, 0, 0),
(10, 3, 8, 'Sumber Air Minum?*', 1, 0, 0, 4, 0, 0),
(11, 3, 9, 'Keterangan', 2, 0, 0, 4, 0, 0),
(12, 3, 10, 'Jenis Lahan', 1, 0, 0, 5, 0, 0),
(13, 3, 11, 'Luas Lahan', 1, 0, 0, 5, 0, 0),
(14, 3, 12, 'Jenis Komoditas', 1, 0, 0, 6, 0, 0),
(15, 3, 13, 'Produksi', 3, 0, 0, 6, 0, 0),
(16, 3, 14, 'Satuan', 1, 0, 0, 6, 0, 0),
(17, 3, 15, 'Nilai (Rp)', 3, 0, 0, 6, 0, 0),
(18, 3, 16, 'Pemasaran Hasil', 1, 0, 0, 6, 0, 0),
(19, 3, 17, 'Jenis Komoditas', 1, 0, 0, 7, 0, 0),
(20, 3, 18, 'Jumlah Pohon', 3, 0, 0, 7, 0, 0),
(21, 3, 19, 'Produksi', 3, 0, 0, 7, 0, 0),
(22, 3, 20, 'Satuan', 1, 0, 0, 7, 0, 0),
(23, 3, 21, 'Nilai (Rp)', 3, 0, 0, 7, 0, 0),
(24, 3, 22, 'Pemasaran Hasil', 1, 0, 0, 7, 0, 0),
(25, 3, 23, 'Jenis Komoditas', 1, 0, 0, 8, 0, 0),
(26, 3, 24, 'Produksi', 3, 0, 0, 8, 0, 0),
(27, 3, 25, 'Satuan', 1, 0, 0, 8, 0, 0),
(28, 3, 26, 'Nilai (Rp)', 3, 0, 0, 8, 0, 0),
(29, 3, 27, 'Pemasaran Hasil', 1, 0, 0, 8, 0, 0),
(30, 3, 28, 'Jenis Komoditas', 1, 0, 0, 9, 0, 0),
(31, 3, 29, 'Produksi', 3, 0, 0, 9, 0, 0),
(32, 3, 30, 'Satuan', 1, 0, 0, 9, 0, 0),
(33, 3, 31, 'Nilai (Rp)', 3, 0, 0, 9, 0, 0),
(34, 3, 32, 'Pemasaran Hasil', 1, 0, 0, 9, 0, 0),
(35, 3, 33, 'Jenis Komoditas', 1, 0, 0, 10, 0, 0),
(36, 3, 34, 'Produksi', 3, 0, 0, 10, 0, 0),
(37, 3, 35, 'Satuan', 1, 0, 0, 10, 0, 0),
(38, 3, 36, 'Nilai (Rp)', 3, 0, 0, 10, 0, 0),
(39, 3, 37, 'Pemasaran Hasil', 1, 0, 0, 10, 0, 0),
(40, 3, 38, 'Jenis Komoditas', 1, 0, 0, 11, 0, 0),
(41, 3, 39, 'Produksi', 3, 0, 0, 11, 0, 0),
(42, 3, 40, 'Satuan', 1, 0, 0, 11, 0, 0),
(43, 3, 41, 'Nilai (Rp)', 3, 0, 0, 11, 0, 0),
(44, 3, 42, 'Pemasaran Hasil', 1, 0, 0, 11, 0, 0),
(45, 3, 43, 'Jenis Komoditas', 1, 0, 0, 12, 0, 0),
(46, 3, 44, 'Produksi', 3, 0, 0, 12, 0, 0),
(47, 3, 45, 'Satuan', 1, 0, 0, 12, 0, 0),
(48, 3, 46, 'Nilai (Rp)', 3, 0, 0, 12, 0, 0),
(49, 3, 47, 'Pemasaran Hasil', 1, 0, 0, 12, 0, 0),
(50, 3, 48, 'Jenis Bahan Galian', 1, 0, 0, 13, 0, 0),
(51, 3, 49, 'Milik Perorangan (Ha)', 3, 0, 0, 13, 0, 0),
(52, 3, 50, 'Milik Adat (Ha)', 3, 0, 0, 13, 0, 0),
(53, 3, 51, 'Satuan', 1, 0, 0, 13, 0, 0),
(54, 3, 52, 'Pemasaran', 1, 0, 0, 13, 0, 0),
(55, 3, 53, 'Jenis Komoditas', 1, 0, 0, 14, 0, 0),
(56, 3, 54, 'Produksi', 3, 0, 0, 14, 0, 0),
(57, 3, 55, 'Satuan', 1, 0, 0, 14, 0, 0),
(58, 3, 56, 'Nilai (Rp)', 3, 0, 0, 14, 0, 0),
(59, 3, 57, 'Pemasaran Hasil', 1, 0, 0, 14, 0, 0),
(60, 3, 58, 'Nama Alat', 1, 0, 0, 15, 0, 0),
(61, 3, 59, 'Jumlah', 3, 0, 0, 15, 0, 0),
(62, 3, 60, 'Pemanfaatan Sungai/Waduk DLL', 2, 0, 0, 16, 0, 0),
(63, 3, 61, 'Lembaga Pendidikan', 2, 0, 0, 16, 0, 0),
(64, 3, 62, 'Penguasaan Aset Tanah', 1, 0, 0, 16, 0, 0),
(65, 3, 63, 'Aset Sarana Transportasi Umum', 2, 0, 0, 16, 0, 0),
(66, 3, 64, 'Aset Sarana Produksi', 2, 0, 0, 16, 0, 0),
(67, 3, 65, 'Aset Rumah (Dinding)', 1, 0, 0, 16, 0, 0),
(68, 3, 66, 'Aset Rumah (Lantai)', 1, 0, 0, 16, 0, 0),
(69, 3, 67, 'Aset Rumah (Atap)', 1, 0, 0, 16, 0, 0),
(70, 3, 68, 'Aset Lainnya', 2, 0, 0, 16, 0, 0),
(71, 3, 69, 'Kualitas Ibu Hamil', 2, 0, 0, 16, 0, 0),
(72, 3, 70, 'Kualitas Bayi', 2, 0, 0, 16, 0, 0),
(73, 3, 71, 'Tempat Persalinan', 2, 0, 0, 16, 0, 0),
(74, 3, 72, 'Pertolongan Persalinan', 2, 0, 0, 16, 0, 0),
(75, 3, 73, 'Cakupan Imunisasi', 2, 0, 0, 16, 0, 0),
(76, 3, 74, 'Penderita Sakit Kelainan', 2, 0, 0, 16, 0, 0),
(77, 3, 75, 'Perilaku Hidup Bersih', 1, 0, 0, 16, 0, 0),
(78, 3, 76, 'Pola Makan', 1, 0, 0, 16, 0, 0),
(79, 3, 77, 'Kebiasaan Berobat', 1, 0, 0, 16, 0, 0),
(80, 3, 78, 'Status Gizi Balita', 1, 0, 0, 16, 0, 0),
(81, 3, 79, 'Jenis Penyakit', 2, 0, 0, 16, 0, 0),
(82, 3, 80, 'Kerukunan', 2, 0, 0, 16, 0, 0),
(83, 3, 81, 'Perkelahian', 2, 0, 0, 16, 0, 0),
(84, 3, 82, 'Pencurian', 2, 0, 0, 16, 0, 0),
(85, 3, 83, 'Penjarahan', 2, 0, 0, 16, 0, 0),
(86, 3, 84, 'Perjudian', 2, 0, 0, 16, 0, 0),
(87, 3, 85, 'Pemakaian Miras dan Narkoba', 2, 0, 0, 16, 0, 0),
(88, 3, 86, 'Pembunuhan', 2, 0, 0, 16, 0, 0),
(89, 3, 87, 'Penculikan', 2, 0, 0, 16, 0, 0),
(90, 3, 88, 'Kejahatan Seksual', 2, 0, 0, 16, 0, 0),
(91, 3, 89, 'Kekerasan Dalam Rumah Tangga', 2, 0, 0, 16, 0, 0),
(92, 3, 90, 'Masalah Kesejahteraan Keluarga', 2, 0, 0, 16, 0, 0),
(93, 4, 1, 'Nomor Akte Kelahiran', 4, 0, 0, 17, 0, 0),
(94, 4, 2, 'Hubungan dengan Kepala Keluarga', 1, 0, 0, 17, 0, 0),
(95, 4, 3, 'Status Perkawinan', 1, 0, 0, 17, 0, 0),
(96, 4, 4, 'Agama dan Aliran Kepercayaan', 1, 0, 0, 17, 0, 0),
(97, 4, 5, 'Golongan Darah', 1, 0, 0, 17, 0, 0),
(98, 4, 6, 'Kewarganegaraan', 1, 0, 0, 17, 0, 0),
(99, 4, 7, 'Etnis/Suku', 4, 0, 0, 17, 0, 0),
(100, 4, 8, 'Pendidikan Umum Terakhir', 1, 0, 0, 17, 0, 0),
(101, 4, 9, 'Mata Pencaharian Pokok/Pekerjaan', 1, 0, 0, 17, 0, 0),
(102, 4, 10, 'Nama Bapak Kandung', 4, 0, 0, 17, 0, 0),
(103, 4, 11, 'Nama Ibu Kandung', 4, 0, 0, 17, 0, 0),
(104, 4, 12, 'Akseptor KB', 1, 0, 0, 17, 0, 0),
(105, 4, 13, 'Cacat Fisik', 2, 0, 0, 17, 0, 1),
(106, 4, 14, 'Cacat Mental', 2, 0, 0, 17, 0, 1),
(107, 4, 15, 'Kedudukan Anggota Keluarga sebagai Wajib Pajak dan Retribusi', 2, 0, 0, 17, 0, 1),
(108, 4, 16, 'Lembaga Pemerintahan Yang Diikuti Anggota Keluarga', 2, 0, 0, 17, 0, 1),
(109, 4, 17, 'Lembaga Kemasyarakatan Yang Diikuti Anggota Keluarga', 2, 0, 0, 17, 0, 1),
(110, 4, 18, 'Lembaga Ekonomi Yang Dimiliki Anggota Keluarga', 2, 0, 0, 17, 0, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_kategori_indikator`
--

CREATE TABLE `analisis_kategori_indikator` (
  `id` int(11) NOT NULL,
  `id_master` tinyint(4) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `kategori_kode` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `analisis_kategori_indikator`
--

INSERT INTO `analisis_kategori_indikator` (`id`, `id_master`, `kategori`, `kategori_kode`) VALUES
(1, 2, 'Aset', ''),
(2, 2, 'Penghasilan', ''),
(3, 3, 'PENGHASILAN DAN PENGELUARAN KELUARGA', ''),
(4, 3, 'SUMBER AIR MINUM KELUARGA', ''),
(5, 3, 'KEPEMILIKAN LAHAN KELUARGA', ''),
(6, 3, 'PRODUKSI TANAMAN PANGAN', ''),
(7, 3, 'PRODUKSI BUAH-BUAHAN', ''),
(8, 3, 'PRODUKSI TANAMAN OBAT', ''),
(9, 3, 'PRODUKSI PERKEBUNAN', ''),
(10, 3, 'PRODUKSI HASIL HUTAN', ''),
(11, 3, 'JENIS TERNAK', ''),
(12, 3, 'PRODUKSI PERIKANAN', ''),
(13, 3, 'PRODUKSI BAHAN GALIAN', ''),
(14, 3, 'PENGOLAHAN HASIL TERNAK', ''),
(15, 3, 'ALAT PRODUKSI PERIKANAN', ''),
(16, 3, 'PEMANFAATAN AIR, ASET RUMAH DLL', ''),
(17, 4, 'Data Anggota Keluarga', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_klasifikasi`
--

CREATE TABLE `analisis_klasifikasi` (
  `id` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `minval` double(7,2) NOT NULL,
  `maxval` double(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `analisis_klasifikasi`
--

INSERT INTO `analisis_klasifikasi` (`id`, `id_master`, `nama`, `minval`, `maxval`) VALUES
(1, 2, 'Miskin', 5.00, 10.00),
(2, 2, 'Sedang', 11.00, 20.00),
(3, 2, 'Kaya', 21.00, 25.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_master`
--

CREATE TABLE `analisis_master` (
  `id` int(11) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `subjek_tipe` tinyint(4) NOT NULL,
  `lock` tinyint(1) NOT NULL DEFAULT 1,
  `deskripsi` text NOT NULL,
  `kode_analisis` varchar(5) NOT NULL DEFAULT '00000',
  `id_kelompok` int(11) DEFAULT NULL,
  `pembagi` varchar(10) NOT NULL DEFAULT '100',
  `id_child` smallint(4) DEFAULT NULL,
  `format_impor` tinyint(2) DEFAULT NULL,
  `jenis` tinyint(2) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `analisis_master`
--

INSERT INTO `analisis_master` (`id`, `nama`, `subjek_tipe`, `lock`, `deskripsi`, `kode_analisis`, `id_kelompok`, `pembagi`, `id_child`, `format_impor`, `jenis`) VALUES
(1, 'Analisis Keahlian Individu', 1, 1, '<p>survey</p>', '00000', 0, '1', 0, 0, 2),
(2, 'AKP Lombok Tengah', 2, 1, '<p>keterangan</p>', '00000', 0, '1', 0, 0, 2),
(3, 'Data Dasar Keluarga (Prodeskel)', 2, 1, 'Pendataan Profil Desa', 'DDK02', 0, '', 0, 0, 1),
(4, 'Data Anggota Keluarga (Prodeskel)', 1, 1, 'Pendataan Profil Desa', 'DAK02', 0, '', 0, 0, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_parameter`
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
-- Dumping data untuk tabel `analisis_parameter`
--

INSERT INTO `analisis_parameter` (`id`, `id_indikator`, `jawaban`, `nilai`, `kode_jawaban`, `asign`) VALUES
(1, 1, 'milik sendiri', 5, 1, 0),
(2, 1, 'milik orang tua', 4, 2, 0),
(3, 1, 'kontrak', 1, 3, 0),
(4, 2, '< Rp.500.000,-', 1, 1, 0),
(5, 2, 'Rp 500.000,- sampa Rp 1.000.000,-', 3, 2, 0),
(6, 2, 'diatas Rp 2.000.000,-', 5, 3, 0),
(7, 5, 'Milik Sendiri', 0, 169, 1),
(8, 5, 'Milik Orang Tua', 0, 170, 1),
(9, 5, 'Milik Keluarga', 0, 171, 1),
(10, 5, 'Sewa/Kontrak', 0, 172, 1),
(11, 5, 'Pinjam Pakai', 0, 173, 1),
(12, 6, 'Pra Sejahtera', 0, 0, 1),
(13, 6, 'Sejahtera 1', 0, 1, 1),
(14, 6, 'Sejahtera 2', 0, 2, 1),
(15, 6, 'Sejahtera 3+', 0, 3, 1),
(16, 7, 'Ya', 0, 1, 1),
(17, 7, 'Tidak', 0, 0, 1),
(18, 8, 'Ya', 0, 1, 1),
(19, 8, 'Tidak', 0, 0, 1),
(20, 9, 'Ya', 0, 1, 1),
(21, 9, 'Tidak', 0, 0, 1),
(22, 10, 'Bak penampung air hujan', 0, 503, 1),
(23, 10, 'Beli dari tangki swasta', 0, 504, 1),
(24, 10, 'Depot isi ulang', 0, 505, 1),
(25, 10, 'Embung', 0, 502, 1),
(26, 10, 'Hidran umum', 0, 498, 1),
(27, 10, 'Mata air', 0, 495, 1),
(28, 10, 'PAM', 0, 499, 1),
(29, 10, 'Pipa', 0, 500, 1),
(30, 10, 'Sumber Air Resapan Umum', 0, 1741, 1),
(31, 10, 'Sumur gali', 0, 496, 1),
(32, 10, 'Sumur pompa', 0, 497, 1),
(33, 10, 'Sungai', 0, 501, 1),
(34, 11, 'Baik', 0, 1, 1),
(35, 11, 'Berasa', 0, 2, 1),
(36, 11, 'Berwarna', 0, 3, 1),
(37, 11, 'Berbau', 0, 4, 1),
(38, 12, 'Hutan', 0, 952, 1),
(39, 12, 'Perkebunan', 0, 951, 1),
(40, 12, 'Tanaman Pangan', 0, 950, 1),
(41, 13, 'Memiliki kurang 0,5 ha', 0, 1732, 1),
(42, 13, 'Memiliki 0,5 - 1,0 ha', 0, 1733, 1),
(43, 13, 'Memiliki lebih dari 1,0 ha', 0, 1734, 1),
(44, 13, 'Tidak memiliki', 0, 1735, 1),
(45, 14, 'Bawah Merah', 0, 12, 1),
(46, 14, 'Bawang Putih', 0, 13, 1),
(47, 14, 'Bayam', 0, 22, 1),
(48, 14, 'Brocoli', 0, 20, 1),
(49, 14, 'Buncis', 0, 19, 1),
(50, 14, 'Cabe', 0, 11, 1),
(51, 14, 'Jagung', 0, 1, 1),
(52, 14, 'Jamur', 0, 78, 1),
(53, 14, 'Jeruk Nipis', 0, 48, 1),
(54, 14, 'Kacang Hijau', 0, 253, 1),
(55, 14, 'Kacang Kedelai', 0, 2, 1),
(56, 14, 'Kacang Merah', 0, 6, 1),
(57, 14, 'Kacang Panjang', 0, 4, 1),
(58, 14, 'Kacang Tanah', 0, 3, 1),
(59, 14, 'Kacang Turis', 0, 24, 1),
(60, 14, 'Kangkung', 0, 23, 1),
(61, 14, 'Kemiri', 0, 96, 1),
(62, 14, 'Kentang', 0, 16, 1),
(63, 14, 'Kubis', 0, 17, 1),
(64, 14, 'Mentimun', 0, 18, 1),
(65, 14, 'Padi Ladang', 0, 8, 1),
(66, 14, 'Padi Sawah', 0, 7, 1),
(67, 14, 'Sawi', 0, 15, 1),
(68, 14, 'Selada', 0, 26, 1),
(69, 14, 'Terong', 0, 21, 1),
(70, 14, 'Tomat', 0, 14, 1),
(71, 14, 'Tumpang Sari', 0, 29, 1),
(72, 14, 'Ubi Jalar', 0, 10, 1),
(73, 14, 'Ubi Kayu', 0, 9, 1),
(74, 14, 'Umbi-Umbian Lain', 0, 25, 1),
(75, 14, 'Wortel', 0, 28, 1),
(76, 16, 'BATANG/TH', 0, 1746, 1),
(77, 16, 'BUAH/TH ', 0, 1013, 1),
(78, 16, 'EKOR/TH ', 0, 1745, 1),
(79, 16, 'JENIS/TH', 0, 965, 1),
(80, 16, 'KG/TH', 0, 960, 1),
(81, 16, 'LITER/TH', 0, 962, 1),
(82, 16, 'M/TH', 0, 963, 1),
(83, 16, 'M3/TH', 0, 961, 1),
(84, 16, 'TON/TH', 0, 966, 1),
(85, 16, 'UNIT/TH', 0, 964, 1),
(86, 18, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(87, 18, 'Dijual ke pasar', 0, 489, 1),
(88, 18, 'Dijual langsung ke konsumen', 0, 488, 1),
(89, 18, 'Dijual melalui KUD', 0, 490, 1),
(90, 18, 'Dijual melalui Pengecer', 0, 492, 1),
(91, 18, 'Dijual melalui Tengkulak', 0, 491, 1),
(92, 18, 'Tidak dijual', 0, 494, 1),
(93, 19, 'Alpokat', 0, 31, 1),
(94, 19, 'Anggur', 0, 54, 1),
(95, 19, 'Apel', 0, 36, 1),
(96, 19, 'Belimbing', 0, 38, 1),
(97, 19, 'Duku', 0, 41, 1),
(98, 19, 'Durian', 0, 39, 1),
(99, 19, 'Gandaria', 0, 258, 1),
(100, 19, 'Jambu air', 0, 50, 1),
(101, 19, 'Jambu klutuk', 0, 57, 1),
(102, 19, 'Jambu Mete', 0, 88, 1),
(103, 19, 'Jeruk', 0, 30, 1),
(104, 19, 'Kedondong', 0, 53, 1),
(105, 19, 'Kesemek', 0, 257, 1),
(106, 19, 'Kokosan', 0, 42, 1),
(107, 19, 'Lengkeng', 0, 45, 1),
(108, 19, 'Limau', 0, 47, 1),
(109, 19, 'Mangga', 0, 32, 1),
(110, 19, 'Manggis', 0, 34, 1),
(111, 19, 'Markisa', 0, 44, 1),
(112, 19, 'Matoa', 0, 249, 1),
(113, 19, 'Melinjo', 0, 55, 1),
(114, 19, 'Melon', 0, 49, 1),
(115, 19, 'Murbei', 0, 58, 1),
(116, 19, 'Nangka', 0, 51, 1),
(117, 19, 'Nenas', 0, 56, 1),
(118, 19, 'Pepaya', 0, 37, 1),
(119, 19, 'Pisang', 0, 43, 1),
(120, 19, 'Rambutan', 0, 33, 1),
(121, 19, 'Salak', 0, 35, 1),
(122, 19, 'Sawo', 0, 40, 1),
(123, 19, 'Semangka', 0, 46, 1),
(124, 19, 'Sirsak', 0, 52, 1),
(125, 19, 'Stroberi', 0, 255, 1),
(126, 19, 'Talas', 0, 27, 1),
(127, 22, 'BATANG/TH', 0, 1746, 1),
(128, 22, 'BUAH/TH ', 0, 1013, 1),
(129, 22, 'EKOR/TH ', 0, 1745, 1),
(130, 22, 'JENIS/TH', 0, 965, 1),
(131, 22, 'KG/TH', 0, 960, 1),
(132, 22, 'LITER/TH', 0, 962, 1),
(133, 22, 'M/TH', 0, 963, 1),
(134, 22, 'M3/TH', 0, 961, 1),
(135, 22, 'TON/TH', 0, 966, 1),
(136, 22, 'UNIT/TH', 0, 964, 1),
(137, 24, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(138, 24, 'Dijual ke pasar', 0, 489, 1),
(139, 24, 'Dijual langsung ke konsumen', 0, 488, 1),
(140, 24, 'Dijual melalui KUD', 0, 490, 1),
(141, 24, 'Dijual melalui Pengecer', 0, 492, 1),
(142, 24, 'Dijual melalui Tengkulak', 0, 491, 1),
(143, 24, 'Tidak dijual', 0, 494, 1),
(144, 25, 'Akar Wangi', 0, 76, 1),
(145, 25, 'Buah Merah', 0, 65, 1),
(146, 25, 'Daun Dewa', 0, 63, 1),
(147, 25, 'Daun Sereh', 0, 74, 1),
(148, 25, 'Daun Sirih', 0, 72, 1),
(149, 25, 'Dewi-Dewi', 0, 79, 1),
(150, 25, 'Jahe', 0, 59, 1),
(151, 25, 'Jamur', 0, 252, 1),
(152, 25, 'Kayu Manis', 0, 73, 1),
(153, 25, 'Kencur', 0, 77, 1),
(154, 25, 'Kumis Kucing', 0, 64, 1),
(155, 25, 'Kunyit', 0, 60, 1),
(156, 25, 'Lengkuas', 0, 61, 1),
(157, 25, 'Mahkota Dewa', 0, 75, 1),
(158, 25, 'Mengkudu', 0, 62, 1),
(159, 25, 'Sambiloto', 0, 66, 1),
(160, 25, 'Temu Hitam', 0, 68, 1),
(161, 25, 'Temu Kunci', 0, 71, 1),
(162, 25, 'Temu Putih', 0, 69, 1),
(163, 25, 'Temu Putri', 0, 70, 1),
(164, 25, 'Temulawak', 0, 67, 1),
(165, 27, 'BATANG/TH', 0, 1746, 1),
(166, 27, 'BUAH/TH ', 0, 1013, 1),
(167, 27, 'EKOR/TH ', 0, 1745, 1),
(168, 27, 'JENIS/TH', 0, 965, 1),
(169, 27, 'KG/TH', 0, 960, 1),
(170, 27, 'LITER/TH', 0, 962, 1),
(171, 27, 'M/TH', 0, 963, 1),
(172, 27, 'M3/TH', 0, 961, 1),
(173, 27, 'TON/TH', 0, 966, 1),
(174, 27, 'UNIT/TH', 0, 964, 1),
(175, 29, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(176, 29, 'Dijual ke pasar', 0, 489, 1),
(177, 29, 'Dijual langsung ke konsumen', 0, 488, 1),
(178, 29, 'Dijual melalui KUD', 0, 490, 1),
(179, 29, 'Dijual melalui Pengecer', 0, 492, 1),
(180, 29, 'Dijual melalui Tengkulak', 0, 491, 1),
(181, 29, 'Tidak dijual', 0, 494, 1),
(182, 30, 'Cengkeh', 0, 83, 1),
(183, 30, 'Coklat', 0, 84, 1),
(184, 30, 'Jarak kepyar', 0, 93, 1),
(185, 30, 'Jarak pagar', 0, 92, 1),
(186, 30, 'Kacang mede', 0, 5, 1),
(187, 30, 'Kapuk', 0, 95, 1),
(188, 30, 'Karet', 0, 87, 1),
(189, 30, 'Kelapa', 0, 80, 1),
(190, 30, 'Kelapa sawit', 0, 81, 1),
(191, 30, 'Kemiri', 0, 256, 1),
(192, 30, 'Kopi', 0, 82, 1),
(193, 30, 'Lada', 0, 86, 1),
(194, 30, 'Pala', 0, 90, 1),
(195, 30, 'Pinang', 0, 85, 1),
(196, 30, 'Tebu', 0, 94, 1),
(197, 30, 'Teh', 0, 97, 1),
(198, 30, 'Tembakau', 0, 89, 1),
(199, 30, 'Vanili', 0, 91, 1),
(200, 32, 'BATANG/TH', 0, 1746, 1),
(201, 32, 'BUAH/TH ', 0, 1013, 1),
(202, 32, 'EKOR/TH ', 0, 1745, 1),
(203, 32, 'JENIS/TH', 0, 965, 1),
(204, 32, 'KG/TH', 0, 960, 1),
(205, 32, 'LITER/TH', 0, 962, 1),
(206, 32, 'M/TH', 0, 963, 1),
(207, 32, 'M3/TH', 0, 961, 1),
(208, 32, 'TON/TH', 0, 966, 1),
(209, 32, 'UNIT/TH', 0, 964, 1),
(210, 34, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(211, 34, 'Dijual ke pasar', 0, 489, 1),
(212, 34, 'Dijual langsung ke konsumen', 0, 488, 1),
(213, 34, 'Dijual melalui KUD', 0, 490, 1),
(214, 34, 'Dijual melalui Pengecer', 0, 492, 1),
(215, 34, 'Dijual melalui Tengkulak', 0, 491, 1),
(216, 34, 'Tidak dijual', 0, 494, 1),
(217, 35, 'Arang', 0, 121, 1),
(218, 35, 'Bambu', 0, 102, 1),
(219, 35, 'Cemara', 0, 109, 1),
(220, 35, 'Damar', 0, 101, 1),
(221, 35, 'Enau', 0, 107, 1),
(222, 35, 'Gambir', 0, 117, 1),
(223, 35, 'Gula enau', 0, 119, 1),
(224, 35, 'Gula lontar', 0, 120, 1),
(225, 35, 'Ijuk Enau', 0, 245, 1),
(226, 35, 'Jati', 0, 103, 1),
(227, 35, 'Kayu', 0, 98, 1),
(228, 35, 'Kayu Bakar', 0, 247, 1),
(229, 35, 'Kayu besi', 0, 114, 1),
(230, 35, 'Kayu cendana', 0, 110, 1),
(231, 35, 'Kayu gaharu', 0, 111, 1),
(232, 35, 'Kayu Sengon', 0, 246, 1),
(233, 35, 'Kayu ulin', 0, 115, 1),
(234, 35, 'Kemenyan', 0, 116, 1),
(235, 35, 'Lontar', 0, 105, 1),
(236, 35, 'Madu lebah', 0, 99, 1),
(237, 35, 'Mahoni', 0, 108, 1),
(238, 35, 'Meranti', 0, 113, 1),
(239, 35, 'Minyak kayu putih', 0, 118, 1),
(240, 35, 'Nilam', 0, 104, 1),
(241, 35, 'Rotan', 0, 100, 1),
(242, 35, 'Rumbia', 0, 259, 1),
(243, 35, 'Sagu', 0, 106, 1),
(244, 35, 'Sarang burung', 0, 112, 1),
(245, 37, 'BATANG/TH', 0, 1746, 1),
(246, 37, 'BUAH/TH ', 0, 1013, 1),
(247, 37, 'EKOR/TH ', 0, 1745, 1),
(248, 37, 'JENIS/TH', 0, 965, 1),
(249, 37, 'KG/TH', 0, 960, 1),
(250, 37, 'LITER/TH', 0, 962, 1),
(251, 37, 'M/TH', 0, 963, 1),
(252, 37, 'M3/TH', 0, 961, 1),
(253, 37, 'TON/TH', 0, 966, 1),
(254, 37, 'UNIT/TH', 0, 964, 1),
(255, 39, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(256, 39, 'Dijual ke pasar', 0, 489, 1),
(257, 39, 'Dijual langsung ke konsumen', 0, 488, 1),
(258, 39, 'Dijual melalui KUD', 0, 490, 1),
(259, 39, 'Dijual melalui Pengecer', 0, 492, 1),
(260, 39, 'Dijual melalui Tengkulak', 0, 491, 1),
(261, 39, 'Tidak dijual', 0, 494, 1),
(262, 40, 'Angsa', 0, 131, 1),
(263, 40, 'Anjing', 0, 135, 1),
(264, 40, 'Ayam kampung', 0, 125, 1),
(265, 40, 'Babi', 0, 124, 1),
(266, 40, 'Bebek', 0, 127, 1),
(267, 40, 'Buaya', 0, 145, 1),
(268, 40, 'Burung beo', 0, 142, 1),
(269, 40, 'Burung cendrawasih', 0, 140, 1),
(270, 40, 'Burung kakatua', 0, 141, 1),
(271, 40, 'Burung langka lainnya', 0, 144, 1),
(272, 40, 'Burung merak', 0, 143, 1),
(273, 40, 'Burung Merpati', 0, 244, 1),
(274, 40, 'Burung onta', 0, 138, 1),
(275, 40, 'Burung puyuh', 0, 132, 1),
(276, 40, 'Domba', 0, 130, 1),
(277, 40, 'Jenis ayam broiler', 0, 126, 1),
(278, 40, 'Kambing', 0, 129, 1),
(279, 40, 'Kelinci', 0, 133, 1),
(280, 40, 'Kerbau', 0, 123, 1),
(281, 40, 'Kucing', 0, 136, 1),
(282, 40, 'Kuda', 0, 128, 1),
(283, 40, 'Sapi', 0, 122, 1),
(284, 40, 'Tuna', 0, 146, 1),
(285, 40, 'Ular cobra', 0, 137, 1),
(286, 40, 'Ular pithon', 0, 139, 1),
(287, 42, 'BATANG/TH', 0, 1746, 1),
(288, 42, 'BUAH/TH ', 0, 1013, 1),
(289, 42, 'EKOR/TH ', 0, 1745, 1),
(290, 42, 'JENIS/TH', 0, 965, 1),
(291, 42, 'KG/TH', 0, 960, 1),
(292, 42, 'LITER/TH', 0, 962, 1),
(293, 42, 'M/TH', 0, 963, 1),
(294, 42, 'M3/TH', 0, 961, 1),
(295, 42, 'TON/TH', 0, 966, 1),
(296, 42, 'UNIT/TH', 0, 964, 1),
(297, 44, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(298, 44, 'Dijual ke pasar', 0, 489, 1),
(299, 44, 'Dijual langsung ke konsumen', 0, 488, 1),
(300, 44, 'Dijual melalui KUD', 0, 490, 1),
(301, 44, 'Dijual melalui Pengecer', 0, 492, 1),
(302, 44, 'Dijual melalui Tengkulak', 0, 491, 1),
(303, 44, 'Tidak dijual', 0, 494, 1),
(304, 45, 'Ayam-ayam', 0, 168, 1),
(305, 45, 'Bandeng', 0, 171, 1),
(306, 45, 'Barabara', 0, 165, 1),
(307, 45, 'Baronang', 0, 160, 1),
(308, 45, 'Bawal', 0, 159, 1),
(309, 45, 'Belanak', 0, 155, 1),
(310, 45, 'Belut', 0, 184, 1),
(311, 45, 'Cucut', 0, 166, 1),
(312, 45, 'Cumi', 0, 156, 1),
(313, 45, 'Gabus', 0, 179, 1),
(314, 45, 'Gurame', 0, 183, 1),
(315, 45, 'Gurita', 0, 157, 1),
(316, 45, 'Hiu', 0, 149, 1),
(317, 45, 'Ikan ekor kuning', 0, 162, 1),
(318, 45, 'Jambal', 0, 152, 1),
(319, 45, 'Kakap', 0, 150, 1),
(320, 45, 'Katak', 0, 188, 1),
(321, 45, 'Kembung', 0, 161, 1),
(322, 45, 'Kepiting', 0, 174, 1),
(323, 45, 'Kerang', 0, 173, 1),
(324, 45, 'Sunuk', 0, 163, 1),
(325, 45, 'Kodok', 0, 187, 1),
(326, 45, 'Kulit kerang', 0, 209, 1),
(327, 45, 'Kuwe', 0, 154, 1),
(328, 45, 'Layur', 0, 167, 1),
(329, 45, 'Lele', 0, 178, 1),
(330, 45, 'Mas', 0, 175, 1),
(331, 45, 'Mujair', 0, 177, 1),
(332, 45, 'Nener', 0, 172, 1),
(333, 45, 'Nila', 0, 181, 1),
(334, 45, 'Pari', 0, 153, 1),
(335, 45, 'Patin', 0, 180, 1),
(336, 45, 'Penyu', 0, 185, 1),
(337, 45, 'Rajungan', 0, 176, 1),
(338, 45, 'Rumput laut', 0, 186, 1),
(339, 45, 'Salmon', 0, 147, 1),
(340, 45, 'Sarden', 0, 158, 1),
(341, 45, 'Sepat', 0, 182, 1),
(342, 45, 'Tembang', 0, 170, 1),
(343, 45, 'Tenggiri', 0, 151, 1),
(344, 45, 'Teri', 0, 254, 1),
(345, 45, 'Teripang', 0, 164, 1),
(346, 45, 'Tongkol/cakalang', 0, 148, 1),
(347, 45, 'Tuna', 0, 251, 1),
(348, 45, 'Udang/lobster', 0, 169, 1),
(349, 47, 'BATANG/TH', 0, 1746, 1),
(350, 47, 'BUAH/TH ', 0, 1013, 1),
(351, 47, 'EKOR/TH ', 0, 1745, 1),
(352, 47, 'JENIS/TH', 0, 965, 1),
(353, 47, 'KG/TH', 0, 960, 1),
(354, 47, 'LITER/TH', 0, 962, 1),
(355, 47, 'M/TH', 0, 963, 1),
(356, 47, 'M3/TH', 0, 961, 1),
(357, 47, 'TON/TH', 0, 966, 1),
(358, 47, 'UNIT/TH', 0, 964, 1),
(359, 49, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(360, 49, 'Dijual ke pasar', 0, 489, 1),
(361, 49, 'Dijual langsung ke konsumen', 0, 488, 1),
(362, 49, 'Dijual melalui KUD', 0, 490, 1),
(363, 49, 'Dijual melalui Pengecer', 0, 492, 1),
(364, 49, 'Dijual melalui Tengkulak', 0, 491, 1),
(365, 49, 'Tidak dijual', 0, 494, 1),
(366, 50, 'Aluminium', 0, 189, 1),
(367, 50, 'Batu apung', 0, 190, 1),
(368, 50, 'Batu cadas', 0, 191, 1),
(369, 50, 'Batu Gamping', 0, 192, 1),
(370, 50, 'Batu Gips', 0, 193, 1),
(371, 50, 'Batu Granit', 0, 194, 1),
(372, 50, 'Batu gunung', 0, 195, 1),
(373, 50, 'Batu kali', 0, 196, 1),
(374, 50, 'Batu kapur', 0, 197, 1),
(375, 50, 'Batu marmer', 0, 198, 1),
(376, 50, 'Batu Putih', 0, 199, 1),
(377, 50, 'Batu Trass', 0, 200, 1),
(378, 50, 'Batubara', 0, 201, 1),
(379, 50, 'Belerang', 0, 202, 1),
(380, 50, 'Biji Besi', 0, 203, 1),
(381, 50, 'Bouxit', 0, 204, 1),
(382, 50, 'Emas', 0, 205, 1),
(383, 50, 'Garam', 0, 206, 1),
(384, 50, 'Gas Alam', 0, 207, 1),
(385, 50, 'Gips', 0, 208, 1),
(386, 50, 'Kuningan', 0, 210, 1),
(387, 50, 'Mangan', 0, 212, 1),
(388, 50, 'Minyak', 0, 233, 1),
(389, 50, 'Minyak Bumi', 0, 213, 1),
(390, 50, 'Nikel', 0, 214, 1),
(391, 50, 'Pasir', 0, 215, 1),
(392, 50, 'Pasir Batu', 0, 216, 1),
(393, 50, 'Pasir Besi', 0, 217, 1),
(394, 50, 'Pasir kwarsa', 0, 218, 1),
(395, 50, 'Perak', 0, 219, 1),
(396, 50, 'Perunggu', 0, 220, 1),
(397, 50, 'Tanah Garam', 0, 221, 1),
(398, 50, 'Tanah liat', 0, 222, 1),
(399, 50, 'Tembaga', 0, 223, 1),
(400, 50, 'Timah', 0, 224, 1),
(401, 50, 'Uranium', 0, 225, 1),
(402, 53, 'BATANG/TH', 0, 1746, 1),
(403, 53, 'BUAH/TH ', 0, 1013, 1),
(404, 53, 'EKOR/TH ', 0, 1745, 1),
(405, 53, 'JENIS/TH', 0, 965, 1),
(406, 53, 'KG/TH', 0, 960, 1),
(407, 53, 'LITER/TH', 0, 962, 1),
(408, 53, 'M/TH', 0, 963, 1),
(409, 53, 'M3/TH', 0, 961, 1),
(410, 53, 'TON/TH', 0, 966, 1),
(411, 53, 'UNIT/TH', 0, 964, 1),
(412, 54, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(413, 54, 'Dijual ke pasar', 0, 489, 1),
(414, 54, 'Dijual langsung ke konsumen', 0, 488, 1),
(415, 54, 'Dijual melalui KUD', 0, 490, 1),
(416, 54, 'Dijual melalui Pengecer', 0, 492, 1),
(417, 54, 'Dijual melalui Tengkulak', 0, 491, 1),
(418, 54, 'Tidak dijual', 0, 494, 1),
(419, 55, 'Air liur burung walet', 0, 232, 1),
(420, 55, 'Bulu', 0, 231, 1),
(421, 55, 'Burung walet', 0, 134, 1),
(422, 55, 'Cinderamata', 0, 235, 1),
(423, 55, 'Daging', 0, 229, 1),
(424, 55, 'Hiasan/lukisan', 0, 234, 1),
(425, 55, 'Kerupuk Kulit', 0, 248, 1),
(426, 55, 'Kulit', 0, 227, 1),
(427, 55, 'Madu', 0, 230, 1),
(428, 55, 'Susu', 0, 226, 1),
(429, 55, 'Telur', 0, 228, 1),
(430, 57, 'BATANG/TH', 0, 1746, 1),
(431, 57, 'BUAH/TH ', 0, 1013, 1),
(432, 57, 'EKOR/TH ', 0, 1745, 1),
(433, 57, 'JENIS/TH', 0, 965, 1),
(434, 57, 'KG/TH', 0, 960, 1),
(435, 57, 'LITER/TH', 0, 962, 1),
(436, 57, 'M/TH', 0, 963, 1),
(437, 57, 'M3/TH', 0, 961, 1),
(438, 57, 'TON/TH', 0, 966, 1),
(439, 57, 'UNIT/TH', 0, 964, 1),
(440, 59, 'Dijual ke Lumbung Pangan Desa/kel', 0, 493, 1),
(441, 59, 'Dijual ke pasar', 0, 489, 1),
(442, 59, 'Dijual langsung ke konsumen', 0, 488, 1),
(443, 59, 'Dijual melalui KUD', 0, 490, 1),
(444, 59, 'Dijual melalui Pengecer', 0, 492, 1),
(445, 59, 'Dijual melalui Tengkulak', 0, 491, 1),
(446, 59, 'Tidak dijual', 0, 494, 1),
(447, 60, 'Jala', 0, 405, 1),
(448, 60, 'Jermal', 0, 402, 1),
(449, 60, 'Karamba', 0, 400, 1),
(450, 60, 'Pancing', 0, 403, 1),
(451, 60, 'Pukat', 0, 404, 1),
(452, 60, 'Tambak', 0, 401, 1),
(453, 62, 'Air minum/air baku', 0, 511, 1),
(454, 62, 'Buang air besar', 0, 514, 1),
(455, 62, 'Cuci dan mandi', 0, 512, 1),
(456, 62, 'Irigasi', 0, 513, 1),
(457, 62, 'Pembangkit listrik', 0, 515, 1),
(458, 62, 'Prasarana transportasi', 0, 516, 1),
(459, 62, 'Sumber air panas', 0, 517, 1),
(460, 62, 'Usaha Perikanan', 0, 510, 1),
(461, 63, 'Biara', 0, 687, 1),
(462, 63, 'Kursus Bahasa', 0, 697, 1),
(463, 63, 'Kursus Bela Diri', 0, 703, 1),
(464, 63, 'Kursus Komputer', 0, 700, 1),
(465, 63, 'Kursus Mengemudi', 0, 701, 1),
(466, 63, 'Kursus Menjahit', 0, 698, 1),
(467, 63, 'Kursus Montir', 0, 699, 1),
(468, 63, 'Kursus Satpam', 0, 702, 1),
(469, 63, 'Lembaga Kursus Keterampilan Swasta Katolik', 0, 692, 1),
(470, 63, 'Lembaga Pendidikan Swasta Budha', 0, 695, 1),
(471, 63, 'Lembaga Pendidikan Swasta Hindu', 0, 694, 1),
(472, 63, 'Lembaga Pendidikan Swasta Konghucu', 0, 696, 1),
(473, 63, 'Lembaga Pendidikan Swasta Kristen Protestan', 0, 693, 1),
(474, 63, 'Madrasah Aliyah', 0, 682, 1),
(475, 63, 'Madrasah Ibtidaiyah', 0, 680, 1),
(476, 63, 'Madrasah Tsanawiyah', 0, 681, 1),
(477, 63, 'Perguruan Tinggi', 0, 676, 1),
(478, 63, 'Perguruan Tinggi Swasta Katolik', 0, 688, 1),
(479, 63, 'Pondok Pesantren', 0, 677, 1),
(480, 63, 'Rhaudatul Athfal (Tk)', 0, 679, 1),
(481, 63, 'SD/Sederajat', 0, 673, 1),
(482, 63, 'Sekolah Dasar Swasta Katolik', 0, 689, 1),
(483, 63, 'Sekolah Tinggi Agama Islam', 0, 683, 1),
(484, 63, 'Seminari Menengah', 0, 685, 1),
(485, 63, 'Seminari Tinggi', 0, 686, 1),
(486, 63, 'SLTA Swasta Katolik', 0, 691, 1),
(487, 63, 'SLTP Swasta Katolik', 0, 690, 1),
(488, 63, 'SMA/Sederajat', 0, 675, 1),
(489, 63, 'SMP/Sederajat', 0, 674, 1),
(490, 63, 'Taman Pendidikan Alqur?an', 0, 678, 1),
(491, 63, 'TK/Preschool/Play Group', 0, 672, 1),
(492, 63, 'Universitas Swasta Islam', 0, 684, 1),
(493, 64, 'Tidak memiliki tanah', 0, 704, 1),
(494, 64, 'Memiliki tanah kurang dari 0,1 ha', 0, 1744, 1),
(495, 64, 'Memiliki tanah antara 0,1 - 0,2 ha', 0, 705, 1),
(496, 64, 'Memiliki tanah antara 0,2 - 0,3 ha', 0, 706, 1),
(497, 64, 'Memiliki tanah antara 0,3 - 0,4 ha', 0, 707, 1),
(498, 64, 'Memiliki tanah antara 0,4 - 0,5 ha', 0, 708, 1),
(499, 64, 'Memiliki tanah antara 0,5 - 0,6 ha', 0, 709, 1),
(500, 64, 'Memiliki tanah antara 0,6 - 0,7 ha', 0, 710, 1),
(501, 64, 'Memiliki tanah antara 0,7 - 0,8 ha', 0, 711, 1),
(502, 64, 'Memiliki tanah antara 0,8 - 0,9 ha', 0, 712, 1),
(503, 64, 'Memiliki tanah antara 0,9 - 1,0 ha', 0, 713, 1),
(504, 64, 'Memiliki tanah antara 1,0 - 5,0 ha', 0, 714, 1),
(505, 64, 'Memiliki tanah lebih dari 5,0 ha', 0, 715, 1),
(506, 65, 'Memiiki cidemo/andong/dokar  ', 0, 718, 1),
(507, 65, 'Memiliki bajaj/kancil', 0, 723, 1),
(508, 65, 'Memiliki becak', 0, 717, 1),
(509, 65, 'Memiliki bus penumpang/angkutan orang/barang', 0, 721, 1),
(510, 65, 'Memiliki ojek motor/sepeda motor/bentor', 0, 716, 1),
(511, 65, 'Memiliki perahu tidak bermotor', 0, 719, 1),
(512, 65, 'Memiliki sepeda dayung', 0, 722, 1),
(513, 65, 'Memiliki tongkang', 0, 720, 1),
(514, 66, 'Memiliki alat pengolahan hasil hutan  ', 0, 731, 1),
(515, 66, 'Memiliki alat pengolahan hasil perikanan  ', 0, 728, 1),
(516, 66, 'Memiliki alat pengolahan hasil perkebunan', 0, 730, 1),
(517, 66, 'Memiliki alat pengolahan hasil peternakan  ', 0, 729, 1),
(518, 66, 'Memiliki alat produksi dan pengolah hasil Industri kerajinan keluarga skala kecil dan menengah  ', 0, 733, 1),
(519, 66, 'Memiliki alat produksi dan pengolah hasil pertambangan  ', 0, 732, 1),
(520, 66, 'Memiliki alat produksi dan pengolahan hasil industri bahan bakar dan gas skala keluarga/rumah tangga  ', 0, 734, 1),
(521, 66, 'Memiliki kapal penangkap ikan  ', 0, 727, 1),
(522, 66, 'Memiliki pabrik pengolahan hasil pertanian  ', 0, 726, 1),
(523, 66, 'Memiliki penggilingan padi  ', 0, 724, 1),
(524, 66, 'Memiliki traktor', 0, 725, 1),
(525, 67, 'Bambu', 0, 737, 1),
(526, 67, 'Dedaunan', 0, 740, 1),
(527, 67, 'Kayu', 0, 736, 1),
(528, 67, 'Pelepah kelapa/lontar/gebang  ', 0, 739, 1),
(529, 67, 'Tanah Liat', 0, 738, 1),
(530, 67, 'Tembok', 0, 735, 1),
(531, 68, 'Kayu', 0, 743, 1),
(532, 68, 'Keramik', 0, 741, 1),
(533, 68, 'Semen', 0, 742, 1),
(534, 68, 'Tanah', 0, 744, 1),
(535, 69, 'Asbes', 0, 747, 1),
(536, 69, 'Bambu', 0, 749, 1),
(537, 69, 'Daun ilalang ', 0, 7752, 1),
(538, 69, 'Daun lontar/gebang/enau  ', 0, 751, 1),
(539, 69, 'Genteng', 0, 745, 1),
(540, 69, 'Kayu', 0, 750, 1),
(541, 69, 'Seng', 0, 746, 1),
(542, 70, 'Berlangganan koran/majalah', 0, 787, 1),
(543, 70, 'Memiliki buku surat berharga', 0, 766, 1),
(544, 70, 'Memiliki buku tabungan bank', 0, 765, 1),
(545, 70, 'Memiliki hiasan emas/berlian', 0, 764, 1),
(546, 70, 'Memiliki HP CDMA', 0, 784, 1),
(547, 70, 'Memiliki HP GSM', 0, 783, 1),
(548, 70, 'Memiliki kapal barang', 0, 757, 1),
(549, 70, 'Memiliki kapal penumpang', 0, 758, 1),
(550, 70, 'Memiliki kapal pesiar', 0, 759, 1),
(551, 70, 'Memiliki mobil pribadi dan sejenisnya', 0, 755, 1),
(552, 70, 'Memiliki parabola', 0, 786, 1),
(553, 70, 'Memiliki perahu bermotor', 0, 756, 1),
(554, 70, 'Memiliki perusahaan industri besar', 0, 770, 1),
(555, 70, 'Memiliki perusahaan industri kecil', 0, 772, 1),
(556, 70, 'Memiliki perusahaan industri menengah', 0, 771, 1),
(557, 70, 'Memiliki saham di perusahaan', 0, 781, 1),
(558, 70, 'Memiliki sepeda motor pribadi', 0, 754, 1),
(559, 70, 'Memiliki sertifikat bangunan', 0, 769, 1),
(560, 70, 'Memiliki sertifikat deposito', 0, 767, 1),
(561, 70, 'Memiliki sertifikat tanah', 0, 768, 1),
(562, 70, 'Memiliki ternak besar', 0, 762, 1),
(563, 70, 'Memiliki ternak kecil', 0, 763, 1),
(564, 70, 'Memiliki TV dan elektronik sejenis lainnya', 0, 753, 1),
(565, 70, 'Memiliki usaha di pasar desa', 0, 779, 1),
(566, 70, 'Memiliki usaha di pasar swalayan', 0, 777, 1),
(567, 70, 'Memiliki usaha di pasar tradisional', 0, 778, 1),
(568, 70, 'Memiliki usaha pasar swalayan', 0, 776, 1),
(569, 70, 'Memiliki usaha perikanan', 0, 773, 1),
(570, 70, 'Memiliki usaha perkebunan', 0, 775, 1),
(571, 70, 'Memiliki usaha peternakan', 0, 774, 1),
(572, 70, 'Memiliki usaha transportasi/pengangkutan', 0, 780, 1),
(573, 70, 'Memiliki Usaha Wartel', 0, 785, 1),
(574, 70, 'Memiliki/menyewa helikopter pribadi', 0, 760, 1),
(575, 70, 'Memiliki/menyewa pesawat terbang pribadi', 0, 761, 1),
(576, 70, 'Pelanggan Telkom', 0, 782, 1),
(577, 71, 'Ibu hamil melahirkan', 0, 796, 1),
(578, 71, 'Ibu hamil periksa di Bidan Praktek', 0, 792, 1),
(579, 71, 'Ibu hamil periksa di Dokter Praktek', 0, 791, 1),
(580, 71, 'Ibu hamil periksa di Dukun Terlatih', 0, 793, 1),
(581, 71, 'Ibu hamil periksa di Posyandu', 0, 788, 1),
(582, 71, 'Ibu hamil periksa di Puskesmas', 0, 789, 1),
(583, 71, 'Ibu hamil periksa di Rumah Sakit', 0, 790, 1),
(584, 71, 'Ibu hamil tidak periksa kesehatan', 0, 794, 1),
(585, 71, 'Ibu hamil yang meninggal', 0, 795, 1),
(586, 71, 'Ibu nifas sakit', 0, 797, 1),
(587, 71, 'Ibu nifas sehat', 0, 799, 1),
(588, 71, 'Kematian ibu nifas', 0, 798, 1),
(589, 71, 'Kematian ibu saat melahirkan', 0, 800, 1),
(590, 72, 'Bayi 0-5 tahun hidup yang menderita kelainan organ tubuh, fisik dan mental  ', 0, 807, 1),
(591, 72, 'Bayi lahir berat kurang dari 2,5 kg', 0, 805, 1),
(592, 72, 'Bayi lahir berat lebih dari 4 kg', 0, 806, 1),
(593, 72, 'Bayi lahir hidup cacat', 0, 803, 1),
(594, 72, 'Bayi lahir hidup normal', 0, 802, 1),
(595, 72, 'Bayi lahir mati', 0, 804, 1),
(596, 72, 'Keguguran kandungan', 0, 801, 1),
(597, 73, 'Rumah dukun', 0, 815, 1),
(598, 73, 'Rumah sendiri', 0, 816, 1),
(599, 73, 'Tempat persalinan Balai Kesehatan Ibu Anak', 0, 812, 1),
(600, 73, 'Tempat persalinan Polindes', 0, 811, 1),
(601, 73, 'Tempat persalinan Puskesmas', 0, 810, 1),
(602, 73, 'Tempat persalinan Rumah Bersalin', 0, 809, 1),
(603, 73, 'Tempat persalinan rumah praktek bidan', 0, 813, 1),
(604, 73, 'Tempat persalinan Rumah Sakit Umum', 0, 808, 1),
(605, 73, 'Tempat praktek dokter', 0, 814, 1),
(606, 74, 'Persalinan ditolong bidan', 0, 818, 1),
(607, 74, 'Persalinan ditolong Dokter', 0, 817, 1),
(608, 74, 'Persalinan ditolong dukun bersalin', 0, 820, 1),
(609, 74, 'Persalinan ditolong keluarga', 0, 821, 1),
(610, 74, 'Persalinan ditolong perawat', 0, 819, 1),
(611, 75, 'BCG', 0, 823, 1),
(612, 75, 'Cacar', 0, 830, 1),
(613, 75, 'Campak', 0, 829, 1),
(614, 75, 'DPT-1', 0, 822, 1),
(615, 75, 'DPT-2', 0, 825, 1),
(616, 75, 'DPT-3', 0, 828, 1),
(617, 75, 'Polio -1', 0, 824, 1),
(618, 75, 'Polio-2', 0, 826, 1),
(619, 75, 'Polio-3', 0, 827, 1),
(620, 75, 'Sudah Semua', 0, 831, 1),
(621, 76, 'Busung Lapar', 0, 838, 1),
(622, 76, 'Cikungunya', 0, 836, 1),
(623, 76, 'Demam Berdarah', 0, 833, 1),
(624, 76, 'Flu Burung', 0, 837, 1),
(625, 76, 'Kelainan fisik', 0, 841, 1),
(626, 76, 'Kelainan mental', 0, 842, 1),
(627, 76, 'Kelaparan', 0, 839, 1),
(628, 76, 'Kolera', 0, 834, 1),
(629, 76, 'Kulit Bersisik', 0, 840, 1),
(630, 76, 'Muntaber', 0, 832, 1),
(631, 76, 'Polio', 0, 835, 1),
(632, 77, 'Biasa buang air besar di sungai/parit/kebun/hutan  ', 0, 845, 1),
(633, 77, 'Memiliki WC yang darurat/kurang memenuhi standar kesehatan  ', 0, 844, 1),
(634, 77, 'Memiliki WC yang permanen/semipermanen  ', 0, 843, 1),
(635, 77, 'Menggunakan fasilitas MCK umum  ', 0, 846, 1),
(636, 78, 'Belum tentu sehari makan 1 kali  ', 0, 851, 1),
(637, 78, 'Kebiasaan makan dalam sehari 1 kali  ', 0, 847, 1),
(638, 78, 'Kebiasaan makan sehari 2 kali  ', 0, 848, 1),
(639, 78, 'Kebiasaan makan sehari 3 kali  ', 0, 849, 1),
(640, 78, 'Kebiasaan makan sehari lebih dari 3 kali  ', 0, 850, 1),
(641, 79, 'Dokter/puskesmas/mantri kesehatan/perawat/ bidan/ posyandu  ', 0, 853, 1),
(642, 79, 'Dukun Terlatih  ', 0, 852, 1),
(643, 79, 'Obat tradisional dari dukun pengobatan alternatif  ', 0, 854, 1),
(644, 79, 'Obat tradisional dari keluarga sendiri  ', 0, 856, 1),
(645, 79, 'Paranormal  ', 0, 855, 1),
(646, 79, 'Tidak diobati  ', 0, 857, 1),
(647, 80, 'Balita bergizi baik  ', 0, 859, 1),
(648, 80, 'Balita bergizi buruk  ', 0, 858, 1),
(649, 80, 'Balita bergizi kurang  ', 0, 860, 1),
(650, 80, 'Balita bergizi lebih', 0, 861, 1),
(651, 81, 'Asma', 0, 874, 1),
(652, 81, 'Diabetes Melitus', 0, 867, 1),
(653, 81, 'Gila/stress', 0, 872, 1),
(654, 81, 'Ginjal', 0, 868, 1),
(655, 81, 'HIV/AIDS', 0, 871, 1),
(656, 81, 'Jantung', 0, 862, 1),
(657, 81, 'Kanker', 0, 865, 1),
(658, 81, 'Lepra/Kusta', 0, 870, 1),
(659, 81, 'Lever', 0, 863, 1),
(660, 81, 'Malaria', 0, 869, 1),
(661, 81, 'Paru-paru', 0, 864, 1),
(662, 81, 'Stroke', 0, 866, 1),
(663, 81, 'TBC', 0, 873, 1),
(664, 82, 'Anak yatim/piatu dalam keluarga akibat konflik Sara  ', 0, 878, 1),
(665, 82, 'Janda/duda dalam keluarga akibat konflik Sara  ', 0, 877, 1),
(666, 82, 'Korban luka dalam keluarga akibat konflik Sara  ', 0, 875, 1),
(667, 82, 'Korban meninggal dalam keluarga akibat konflik Sara ', 0, 876, 1),
(668, 83, 'Korban jiwa akibat perkelahian dalam keluarga  ', 0, 879, 1),
(669, 83, 'Korban luka parah akibat perkelahian dalam keluarga ', 0, 880, 1),
(670, 84, 'Korban pencurian, perampokan dalam keluarga  ', 0, 881, 1),
(671, 85, 'Korban penjarahan yang pelakunya anggota keluarga  ', 0, 882, 1),
(672, 85, 'Korban penjarahan yang pelakunya bukan anggota keluarga  ', 0, 883, 1),
(673, 86, 'Anggota keluarga yang memiliki kebiasaan berjudi', 0, 884, 1),
(674, 87, 'Anggota keluarga mengkonsumsi Miras yang dilarang  ', 0, 885, 1),
(675, 87, 'Anggota keluarga yang mengkonsumsi Narkoba ', 0, 886, 1),
(676, 88, 'Korban pembunuhan dalam keluarga yang pelakunya anggota keluarga  ', 0, 887, 1),
(677, 88, 'Korban pembunuhan dalam keluarga yang pelakunya bukan anggota keluarga', 0, 888, 1),
(678, 89, 'Korban penculikan yang pelakunya anggota keluarga  ', 0, 889, 1),
(679, 89, 'Korban penculikan yang pelakunya bukan anggota keluarga  ', 0, 890, 1),
(680, 90, 'Korban kehamilan di luar nikah yang sah menurut hukum adat  ', 0, 893, 1),
(681, 90, 'Korban kehamilan yang tidak dinikahi pelakunya  ', 0, 894, 1),
(682, 90, 'Korban kehamilan yang tidak/belum disahkan secara hukum agama dan hukum negara  ', 0, 895, 1),
(683, 90, 'Korban perkosaan/pelecehan seksual yang pelakunya anggota keluarga  ', 0, 891, 1),
(684, 90, 'Korban perkosaan/pelecehan seksual yang pelakunya bukan anggota keluarga  ', 0, 892, 1),
(685, 91, 'Adanya pemukulan/tindakan fisik antara anak dengan anggota keluarga lain  ', 0, 903, 1),
(686, 91, 'Adanya pemukulan/tindakan fisik antara anak dengan orang tua  ', 0, 901, 1),
(687, 91, 'Adanya pemukulan/tindakan fisik antara anak dengan pembantu  ', 0, 905, 1),
(688, 91, 'Adanya pemukulan/tindakan fisik antara orang tua dengan anak  ', 0, 902, 1),
(689, 91, 'Adanya pemukulan/tindakan fisik antara orang tua dengan orang tua  ', 0, 904, 1),
(690, 91, 'Adanya pemukulan/tindakan fisik antara orang tua dengan pembantu  ', 0, 906, 1),
(691, 91, 'Adanya pertengkaran dalam keluarga antara anak dan anak  ', 0, 897, 1),
(692, 91, 'Adanya pertengkaran dalam keluarga antara anak dan anggota keluarga lain  ', 0, 900, 1),
(693, 91, 'Adanya pertengkaran dalam keluarga antara anak dan orang tua  ', 0, 896, 1),
(694, 91, 'Adanya pertengkaran dalam keluarga antara anak dan pembantu  ', 0, 899, 1),
(695, 91, 'Adanya pertengkaran dalam keluarga antara ayah dan ibu/orang tua ', 0, 898, 1),
(696, 92, 'Ada anak anggota keluarga yang mengemis', 0, 918, 1),
(697, 92, 'Ada anak dan anggota keluarga yang menjadi pengamen', 0, 919, 1),
(698, 92, 'Ada anak yang membantu orang tua mendapatkan penghasilan', 0, 934, 1),
(699, 92, 'Ada anggota keluarga eks narapidana', 0, 936, 1),
(700, 92, 'Ada anggota keluarga yang bermalam/tidur di jalanan/emperan toko/pusat keramaian/kolong jembatan', 0, 916, 1),
(701, 92, 'Ada anggota keluarga yang cacat fisik', 0, 921, 1),
(702, 92, 'Ada anggota keluarga yang cacat mental', 0, 922, 1),
(703, 92, 'Ada anggota keluarga yang gila/stres', 0, 920, 1),
(704, 92, 'Ada anggota keluarga yang kelainan kulit', 0, 923, 1),
(705, 92, 'Ada anggota keluarga yang menganggur', 0, 933, 1),
(706, 92, 'Ada anggota keluarga yang mengemis', 0, 915, 1),
(707, 92, 'Ada anggota keluarga yang menjadi pengamen', 0, 924, 1),
(708, 92, 'Ada anggota keluarga yang termasuk manusia lanjut usia (di atas 60 thn)', 0, 917, 1),
(709, 92, 'Anggota keluarga yatim/piatu', 0, 925, 1),
(710, 92, 'Keluarga duda', 0, 927, 1),
(711, 92, 'Keluarga janda', 0, 926, 1),
(712, 92, 'Kepala keluarga perempuan', 0, 935, 1),
(713, 92, 'Tinggal di bantaran sungai', 0, 928, 1),
(714, 92, 'Tinggal di daerah kawasan kering, tandus dan kritis', 0, 947, 1),
(715, 92, 'Tinggal di daerah rawan bencana tsunami', 0, 938, 1),
(716, 92, 'Tinggal di desa/kelurahan rawan air bersih', 0, 944, 1),
(717, 92, 'Tinggal di desa/kelurahan rawan banjir', 0, 937, 1),
(718, 92, 'Tinggal di desa/kelurahan rawan bencana kekeringan', 0, 945, 1),
(719, 92, 'Tinggal di desa/kelurahan rawan gagal tanam/panen', 0, 946, 1),
(720, 92, 'Tinggal di desa/kelurahan rawan gunung meletus', 0, 939, 1),
(721, 92, 'Tinggal di desa/kelurahan rawan kelaparan', 0, 943, 1),
(722, 92, 'Tinggal di jalur hijau', 0, 929, 1),
(723, 92, 'Tinggal di jalur rawan gempa bumi', 0, 940, 1),
(724, 92, 'Tinggal di kawasan jalur rel kereta api', 0, 930, 1),
(725, 92, 'Tinggal di kawasan jalur sutet', 0, 931, 1),
(726, 92, 'Tinggal di kawasan kumuh dan padat pemukiman', 0, 932, 1),
(727, 92, 'Tinggal di kawasan rawan kebakaran', 0, 942, 1),
(728, 92, 'Tinggal di kawasan rawan tanah longsor', 0, 941, 1),
(729, 94, 'Kepala Keluarga', 0, 1, 1),
(730, 94, 'Suami', 0, 2, 1),
(731, 94, 'Istri', 0, 3, 1),
(732, 94, 'Anak Kandung', 0, 4, 1),
(733, 94, 'Anak Angkat', 0, 5, 1),
(734, 94, 'Ayah', 0, 6, 1),
(735, 94, 'Ibu', 0, 7, 1),
(736, 94, 'Paman', 0, 8, 1),
(737, 94, 'Tante', 0, 9, 1),
(738, 94, 'Kakak', 0, 10, 1),
(739, 94, 'Adik', 0, 11, 1),
(740, 94, 'Kakek', 0, 12, 1),
(741, 94, 'Nenek', 0, 13, 1),
(742, 94, 'Sepupu', 0, 14, 1),
(743, 94, 'Keponakan', 0, 15, 1),
(744, 94, 'Teman', 0, 16, 1),
(745, 94, 'Mertua', 0, 17, 1),
(746, 94, 'Cucu', 0, 18, 1),
(747, 94, 'Famili lain', 0, 19, 1),
(748, 94, 'Menantu', 0, 21, 1),
(749, 94, 'Lainnya', 0, 22, 1),
(750, 94, 'Anak Tiri', 0, 23, 1),
(751, 95, 'Kawin', 0, 1, 1),
(752, 95, 'Belum Kawin', 0, 2, 1),
(753, 95, 'Janda/Duda', 0, 3, 1),
(754, 96, 'Islam', 0, 1, 1),
(755, 96, 'Kristen Protestan', 0, 2, 1),
(756, 96, 'Kristen Katolik', 0, 3, 1),
(757, 96, 'Hindu', 0, 4, 1),
(758, 96, 'Budha', 0, 5, 1),
(759, 96, 'Konghucu', 0, 6, 1),
(760, 96, 'Aliran Kepercayaan Kepada Tuhan YME', 0, 7, 1),
(761, 97, 'O', 0, 0, 1),
(762, 97, 'A', 0, 1, 1),
(763, 97, 'B', 0, 2, 1),
(764, 97, 'AB', 0, 3, 1),
(765, 97, 'Tidak Tahu', 0, 4, 1),
(766, 98, 'Warga Negara Indonesia', 0, 1, 1),
(767, 98, 'Warga Negara Asing', 0, 2, 1),
(768, 98, 'Dwi Kewarganegaraan', 0, 3, 1),
(769, 100, 'Belum masuk TK/Kelompok Bermain', 0, 1, 1),
(770, 100, 'Sedang TK/Kelompok Bermain', 0, 2, 1),
(771, 100, 'Tidak pernah sekolah', 0, 3, 1),
(772, 100, 'Sedang SD/sederajat', 0, 4, 1),
(773, 100, 'Tamat SD/sederajat', 0, 5, 1),
(774, 100, 'Tidak tamat SD/sederajat', 0, 6, 1),
(775, 100, 'Sedang SLTP/Sederajat', 0, 7, 1),
(776, 100, 'Tamat SLTP/sederajat', 0, 8, 1),
(777, 100, 'Sedang SLTA/sederajat', 0, 9, 1),
(778, 100, 'Tamat SLTA/sederajat', 0, 10, 1),
(779, 100, 'Sedang D-1/sederajat', 0, 11, 1),
(780, 100, 'Tamat D-1/sederajat', 0, 12, 1),
(781, 100, 'Sedang D-2/sederajat', 0, 13, 1),
(782, 100, 'Tamat D-2/sederajat', 0, 14, 1),
(783, 100, 'Sedang D-3/sederajat', 0, 15, 1),
(784, 100, 'Tamat D-4/sederajat', 0, 16, 1),
(785, 100, 'Sedang S-1/sederajat', 0, 17, 1),
(786, 100, 'Tamat S-1/sederajat', 0, 18, 1),
(787, 100, 'Sedang S-2/sederajat', 0, 19, 1),
(788, 100, 'Tamat S-2/sederajat', 0, 20, 1),
(789, 100, 'Sedang S-3/sederajat', 0, 21, 1),
(790, 100, 'Tamat S-3/sederajat', 0, 22, 1),
(791, 100, 'Sedang SLB A/sederajat', 0, 23, 1),
(792, 100, 'Tamat SLB A/sederajat', 0, 24, 1),
(793, 100, 'Sedang SLB B/sederajat', 0, 25, 1),
(794, 100, 'Tamat SLB B/sederajat', 0, 26, 1),
(795, 100, 'Sedang SLB C/sederajat', 0, 27, 1),
(796, 100, 'Tamat SLB C/sederajat', 0, 28, 1),
(797, 100, 'Tidak dapat membaca dan menulis huruf Latin/Arab', 0, 29, 1),
(798, 100, 'Tamat D-3/sederajat', 0, 30, 1),
(799, 101, 'Petani', 0, 1, 1),
(800, 101, 'Buruh Tani', 0, 2, 1),
(801, 101, 'Buruh Migran Perempuan', 0, 3, 1),
(802, 101, 'Buruh Migran laki-laki', 0, 4, 1),
(803, 101, 'Pegawai Negeri Sipil', 0, 5, 1),
(804, 101, 'Karyawan Swasta', 0, 6, 1),
(805, 101, 'Pengrajin', 0, 7, 1),
(806, 101, 'Pedagang barang kelontong', 0, 8, 1),
(807, 101, 'Peternak', 0, 9, 1),
(808, 101, 'Nelayan', 0, 10, 1),
(809, 101, 'Montir', 0, 11, 1),
(810, 101, 'Dokter swasta', 0, 12, 1),
(811, 101, 'Perawat swasta', 0, 13, 1),
(812, 101, 'Bidan swasta', 0, 14, 1),
(813, 101, 'Ahli Pengobatan Alternatif', 0, 15, 1),
(814, 101, 'TNI', 0, 16, 1),
(815, 101, 'POLRI', 0, 17, 1),
(816, 101, 'Pengusaha kecil, menengah dan besar', 0, 18, 1),
(817, 101, 'Guru swasta', 0, 19, 1),
(818, 101, 'Dosen swasta', 0, 20, 1),
(819, 101, 'Seniman/artis', 0, 21, 1),
(820, 101, 'Pedagang Keliling', 0, 22, 1),
(821, 101, 'Penambang', 0, 23, 1),
(822, 101, 'Tukang Kayu', 0, 24, 1),
(823, 101, 'Tukang Batu', 0, 25, 1),
(824, 101, 'Tukang cuci', 0, 26, 1),
(825, 101, 'Pembantu rumah tangga', 0, 27, 1),
(826, 101, 'Pengacara', 0, 28, 1),
(827, 101, 'Notaris', 0, 29, 1),
(828, 101, 'Dukun Tradisional', 0, 30, 1),
(829, 101, 'Arsitektur/Desainer', 0, 31, 1),
(830, 101, 'Karyawan Perusahaan Swasta', 0, 32, 1),
(831, 101, 'Karyawan Perusahaan Pemerintah', 0, 33, 1),
(832, 101, 'Wiraswasta', 0, 34, 1),
(833, 101, 'Konsultan Manajemen dan Teknis', 0, 35, 1),
(834, 101, 'Tidak Mempunyai Pekerjaan Tetap', 0, 36, 1),
(835, 101, 'Belum Bekerja', 0, 37, 1),
(836, 101, 'Pelajar', 0, 38, 1),
(837, 101, 'Ibu Rumah Tangga', 0, 39, 1),
(838, 101, 'Purnawirawan/Pensiunan', 0, 40, 1),
(839, 101, 'Perangkat Desa', 0, 41, 1),
(840, 101, 'Buruh Harian Lepas', 0, 42, 1),
(841, 101, 'Pemilik perusahaan', 0, 55, 1),
(842, 101, 'Pengusaha perdagangan hasil bumi', 0, 56, 1),
(843, 101, 'Buruh jasa perdagangan hasil bumi', 0, 57, 1),
(844, 101, 'Pemilik usaha jasa transportasi dan perhubungan', 0, 58, 1),
(845, 101, 'Buruh usaha jasa transportasi dan perhubungan', 0, 59, 1),
(846, 101, 'Pemilik usaha informasi dan komunikasi', 0, 60, 1),
(847, 101, 'Buruh usaha jasa informasi dan komunikasi', 0, 61, 1),
(848, 101, 'Kontraktor', 0, 62, 1),
(849, 101, 'Pemilik usaha jasa hiburan dan pariwisata', 0, 63, 1),
(850, 101, 'Buruh usaha jasa hiburan dan pariwisata', 0, 64, 1),
(851, 101, 'Pemilik usaha hotel dan penginapan lainnya ', 0, 65, 1),
(852, 101, 'Buruh usaha hotel dan penginapan lainnya', 0, 66, 1),
(853, 101, 'Pemilik usaha warung, rumah makan dan restoran', 0, 67, 1),
(854, 101, 'Dukun/paranormal/supranatural', 0, 68, 1),
(855, 101, 'Jasa pengobatan alternatif', 0, 69, 1),
(856, 101, 'Sopir', 0, 70, 1),
(857, 101, 'Usaha jasa pengerah tenaga kerja', 0, 71, 1),
(858, 101, 'Jasa penyewaan peralatan pesta', 0, 74, 1),
(859, 101, 'Pemulung', 0, 75, 1),
(860, 101, 'Pengrajin industri rumah tangga lainnya', 0, 76, 1),
(861, 101, 'Tukang Anyaman', 0, 77, 1),
(862, 101, 'Tukang Jahit', 0, 78, 1),
(863, 101, 'Tukang Kue', 0, 79, 1),
(864, 101, 'Tukang Rias', 0, 80, 1),
(865, 101, 'Tukang Sumur', 0, 81, 1),
(866, 101, 'Jasa Konsultansi Manajemen dan Teknis ', 0, 82, 1),
(867, 101, 'Juru Masak', 0, 83, 1),
(868, 101, 'Karyawan Honorer', 0, 84, 1),
(869, 101, 'Pialang', 0, 85, 1),
(870, 101, 'Pskiater/Psikolog', 0, 86, 1),
(871, 101, 'Wartawan', 0, 87, 1),
(872, 101, 'Tukang Cukur', 0, 88, 1),
(873, 101, 'Tukang Las', 0, 89, 1),
(874, 101, 'Tukang Gigi', 0, 90, 1),
(875, 101, 'Tukang Listrik', 0, 91, 1),
(876, 101, 'Pemuka Agama', 0, 92, 1),
(877, 101, 'Anggota Legislatif', 0, 93, 1),
(878, 101, 'Kepala Daerah', 0, 94, 1),
(879, 101, 'Apoteker', 0, 96, 1),
(880, 101, 'Presiden', 0, 97, 1),
(881, 101, 'Wakil presiden', 0, 98, 1),
(882, 101, 'Anggota Mahkamah Konstitusi', 0, 99, 1),
(883, 101, 'Anggota Kabinet Kementrian', 0, 100, 1),
(884, 101, 'Duta besar', 0, 101, 1),
(885, 101, 'Gubernur', 0, 102, 1),
(886, 101, 'Wakil bupati', 0, 103, 1),
(887, 101, 'Pilot', 0, 104, 1),
(888, 101, 'Penyiar radio', 0, 105, 1),
(889, 101, 'Pelaut', 0, 106, 1),
(890, 101, 'Peneliti', 0, 107, 1),
(891, 101, 'Satpam/Security', 0, 108, 1),
(892, 101, 'Wakil Gubernur', 0, 109, 1),
(893, 101, 'Bupati/Walikota', 0, 110, 1),
(894, 101, 'Akuntan', 0, 112, 1),
(895, 104, 'Menggunakan alat kontrasepsi Suntik', 0, 1, 1),
(896, 104, 'Menggunakan alat kontrasepsi Spiral', 0, 2, 1),
(897, 104, 'Menggunakan alat kontrasepsi Kondom', 0, 3, 1),
(898, 104, 'Menggunakan alat kontrasepsi vasektomi', 0, 4, 1),
(899, 104, 'Menggunakan alat kontrasepsi Tubektomi', 0, 5, 1),
(900, 104, 'Menggunakan alat kontrasepsi Pil', 0, 6, 1),
(901, 104, 'Menggunakan metode KB Alamiah/Kalender', 0, 7, 1),
(902, 104, 'Menggunakan obat tradisional', 0, 8, 1),
(903, 104, 'Tidak Menggunakan alat kontrasepsi /metode KBA', 0, 9, 1),
(904, 104, 'Susuk KB (Implant)', 0, 10, 1),
(905, 104, 'Tidak Menjawab', 0, 11, 1),
(906, 105, 'Tuna Rungu', 0, 1, 1),
(907, 105, 'Tuna Wicara', 0, 2, 1),
(908, 105, 'Tuna Netra', 0, 3, 1),
(909, 105, 'Lumpuh', 0, 4, 1),
(910, 105, 'Sumbing', 0, 5, 1),
(911, 106, 'Idiot', 0, 1, 1),
(912, 106, 'Gila', 0, 2, 1),
(913, 106, 'Stress', 0, 3, 1),
(914, 107, 'Wajib Pajak Bumi dan Bangunan', 0, 1, 1),
(915, 107, 'Wajib Pajak Penghasilan Perorangan', 0, 2, 1),
(916, 107, 'Wajib Pajak Badan/Perusahaan', 0, 3, 1),
(917, 107, 'Wajib Pajak Kendaraan Bermotor', 0, 4, 1),
(918, 107, 'Wajib Retribusi Kebersihan', 0, 5, 1),
(919, 107, 'Wajib Retribusi Keamanan', 0, 6, 1),
(920, 108, 'Kepala Desa/Lurah', 0, 1, 1),
(921, 108, 'Sekretaris Desa/Kelurahan', 0, 2, 1),
(922, 108, 'Kepala Urusan', 0, 3, 1),
(923, 108, 'Kepala Dusun/Lingkungan', 0, 4, 1),
(924, 108, 'Staf Desa/Kelurahan', 0, 5, 1),
(925, 108, 'Ketua BPD', 0, 6, 1),
(926, 108, 'Wakil Ketua BPD', 0, 7, 1),
(927, 108, 'Sekretaris BPD', 0, 8, 1),
(928, 108, 'Anggota BPD', 0, 9, 1),
(929, 109, 'Pengurus RT', 0, 1, 1),
(930, 109, 'Anggota Pengurus RT', 0, 2, 1),
(931, 109, 'Pengurus RW', 0, 3, 1),
(932, 109, 'Anggota Pengurus RW', 0, 4, 1),
(933, 109, 'Pengurus LKMD/K/LPM', 0, 5, 1),
(934, 109, 'Anggota LKMD/K/LPM', 0, 6, 1),
(935, 109, 'Pengurus PKK', 0, 7, 1),
(936, 109, 'Anggota PKK', 0, 8, 1),
(937, 109, 'Pengurus Lembaga Adat', 0, 9, 1),
(938, 109, 'Pengurus Karang Taruna', 0, 10, 1),
(939, 109, 'Anggota Karang Taruna', 0, 11, 1),
(940, 109, 'Pengurus Hansip/Linmas', 0, 12, 1),
(941, 109, 'Pengurus Poskamling', 0, 13, 1),
(942, 109, 'Pengurus Organisasi Perempuan', 0, 14, 1),
(943, 109, 'Anggota Organisasi Perempuan', 0, 15, 1),
(944, 109, 'Pengurus Organisasi Bapak-bapak', 0, 16, 1),
(945, 109, 'Anggota Organisasi Bapak-bapak', 0, 17, 1),
(946, 109, 'Pengurus Organisasi keagamaan', 0, 18, 1),
(947, 109, 'Anggota Organisasi keagamaan', 0, 19, 1),
(948, 109, 'Pengurus Organisasi profesi wartawan', 0, 20, 1),
(949, 109, 'Anggota Organisasi profesi wartawan', 0, 21, 1),
(950, 109, 'Pengurus Posyandu', 0, 22, 1),
(951, 109, 'Pengurus Posyantekdes', 0, 23, 1),
(952, 109, 'Pengurus Organisasi Kelompok Tani/Nelayan', 0, 24, 1),
(953, 109, 'Anggota Organisasi Kelompok Tani/Nelayan', 0, 25, 1),
(954, 109, 'Pengurus Lembaga Gotong royong', 0, 26, 1),
(955, 109, 'Anggota Lembaga Gotong royong', 0, 27, 1),
(956, 109, 'Pengurus Organisasi Profesi guru', 0, 28, 1),
(957, 109, 'Anggota Organisasi Profesi guru', 0, 29, 1),
(958, 109, 'Pengurus Organisasi profesi dokter/tenaga medis', 0, 30, 1),
(959, 109, 'Anggota Organisasi profesi/tenaga medis', 0, 31, 1),
(960, 109, 'Pengurus organisasi pensiunan', 0, 32, 1),
(961, 109, 'Anggota organisasi pensiunan', 0, 33, 1),
(962, 109, 'Pengurus organisasi pemirsa/pendengar', 0, 34, 1),
(963, 109, 'Anggota organisasi pemirsa/pendengar', 0, 35, 1),
(964, 109, 'Pengurus lembaga pencinta alam', 0, 36, 1),
(965, 109, 'Anggota organisasi pencinta alam', 0, 37, 1),
(966, 109, 'Pengurus organisasi pengembangan ilmu pengetahuan', 0, 38, 1),
(967, 109, 'Anggota organisasi pengembangan ilmu pengetaahuan', 0, 39, 1),
(968, 109, 'Pemilik yayasan', 0, 40, 1),
(969, 109, 'Pengurus yayasan', 0, 41, 1),
(970, 109, 'Anggota yayasan', 0, 42, 1),
(971, 109, 'Pengurus Satgas Kebersihan', 0, 43, 1),
(972, 109, 'Anggota Satgas Kebersihan', 0, 44, 1),
(973, 109, 'Pengurus Satgas Kebakaran', 0, 45, 1),
(974, 109, 'Anggota Satgas Kebakaran', 0, 46, 1),
(975, 109, 'Pengurus Posko Penanggulangan Bencana', 0, 47, 1),
(976, 109, 'Anggota Tim Penanggulangan Bencana', 0, 48, 1),
(977, 110, 'Koperasi', 0, 1, 1),
(978, 110, 'Unit Usaha Simpan Pinjam', 0, 2, 1),
(979, 110, 'Industri Kerajinan Tangan', 0, 3, 1),
(980, 110, 'Industri Pakaian', 0, 4, 1),
(981, 110, 'Industri Usaha Makanan', 0, 5, 1),
(982, 110, 'Industri Alat Rumah Tangga', 0, 6, 1),
(983, 110, 'Industri Usaha Bahan Bangunan', 0, 7, 1),
(984, 110, 'Industri Alat Pertanian', 0, 8, 1),
(985, 110, 'Restoran', 0, 9, 1),
(986, 110, 'Toko/ Swalayan', 0, 10, 1),
(987, 110, 'Warung Kelontongan/Kios', 0, 11, 1),
(988, 110, 'Angkutan Darat', 0, 12, 1),
(989, 110, 'Angkutan Sungai', 0, 13, 1),
(990, 110, 'Angkutan Laut', 0, 14, 1),
(991, 110, 'Angkutan Udara', 0, 15, 1),
(992, 110, 'Jasa Ekspedisi/Pengiriman Barang', 0, 16, 1),
(993, 110, 'Tukang Sumur', 0, 17, 1),
(994, 110, 'Usaha Pasar Harian', 0, 18, 1),
(995, 110, 'Usaha Pasar Mingguan', 0, 19, 1),
(996, 110, 'Usaha Pasar Ternak', 0, 20, 1),
(997, 110, 'Usaha Pasar Hasil Bumi Dan Tambang', 0, 21, 1),
(998, 110, 'Usaha Perdagangan Antar Pulau', 0, 22, 1),
(999, 110, 'Pengijon', 0, 23, 1),
(1000, 110, 'Pedagang Pengumpul/Tengkulak', 0, 24, 1),
(1001, 110, 'Usaha Peternakan', 0, 25, 1),
(1002, 110, 'Usaha Perikanan', 0, 26, 1),
(1003, 110, 'Usaha Perkebunan', 0, 27, 1),
(1004, 110, 'Kelompok Simpan Pinjam', 0, 28, 1),
(1005, 110, 'Usaha Minuman', 0, 29, 1),
(1006, 110, 'Industri Farmasi', 0, 30, 1),
(1007, 110, 'Industri Karoseri', 0, 31, 1),
(1008, 110, 'Penitipan Kendaraan Bermotor', 0, 32, 1),
(1009, 110, 'Industri Perakitan Elektronik', 0, 33, 1),
(1010, 110, 'Pengolahan Kayu', 0, 34, 1),
(1011, 110, 'Bioskop', 0, 35, 1),
(1012, 110, 'Film Keliling', 0, 36, 1),
(1013, 110, 'Sandiwara/Drama', 0, 37, 1),
(1014, 110, 'Group Lawak', 0, 38, 1),
(1015, 110, 'Jaipongan', 0, 39, 1),
(1016, 110, 'Wayang Orang/Golek', 0, 40, 1),
(1017, 110, 'Group Musik/Band', 0, 41, 1),
(1018, 110, 'Group Vokal/Paduan Suara', 0, 42, 1),
(1019, 110, 'Usaha Persewaan Tenaga Listrik', 0, 43, 1),
(1020, 110, 'Usaha Pengecer Gas Dan Bahan Bakar Minyak', 0, 44, 1),
(1021, 110, 'Usaha Air Minum Dalam Kemasan', 0, 45, 1),
(1022, 110, 'Tukang Kayu', 0, 46, 1),
(1023, 110, 'Tukang Batu', 0, 47, 1),
(1024, 110, 'Tukang Jahit/Bordir', 0, 48, 1),
(1025, 110, 'Tukang Cukur', 0, 49, 1),
(1026, 110, 'Tukang Service Elektronik', 0, 50, 1),
(1027, 110, 'Tukang Besi', 0, 51, 1),
(1028, 110, 'Tukang Pijat/Urut', 0, 52, 1),
(1029, 110, 'Tukang Sumur', 0, 53, 1),
(1030, 110, 'Notaris', 0, 54, 1),
(1031, 110, 'Pengacara/Advokat', 0, 55, 1),
(1032, 110, 'Konsultan Manajemen', 0, 56, 1),
(1033, 110, 'Konsultan Teknis', 0, 57, 1),
(1034, 110, 'Pejabat Pembuat Akta Tanah', 0, 58, 1),
(1035, 110, 'Losmen', 0, 59, 1),
(1036, 110, 'Wisma', 0, 60, 1),
(1037, 110, 'Asrama', 0, 61, 1),
(1038, 110, 'Persewaan Kamar', 0, 62, 1),
(1039, 110, 'Kontrakan Rumah', 0, 63, 1),
(1040, 110, 'Mess', 0, 64, 1),
(1041, 110, 'Hotel', 0, 65, 1),
(1042, 110, 'Home Stay', 0, 66, 1),
(1043, 110, 'Villa', 0, 67, 1),
(1044, 110, 'Town House', 0, 68, 1),
(1045, 110, 'Usaha Asuransi', 0, 69, 1),
(1046, 110, 'Lembaga Keuangan Bukan Bank', 0, 70, 1),
(1047, 110, 'Lembaga Perkreditan Rakyat', 0, 71, 1),
(1048, 110, 'Pegadaian', 0, 72, 1),
(1049, 110, 'Bank Perkreditan Rakyat', 0, 73, 1),
(1050, 110, 'Usaha Penyewaan Alat Pesta', 0, 74, 1),
(1051, 110, 'Usaha Pengolahan dan Penjualan Hasil Hutan', 0, 75, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_partisipasi`
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
-- Struktur dari tabel `analisis_periode`
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
-- Dumping data untuk tabel `analisis_periode`
--

INSERT INTO `analisis_periode` (`id`, `id_master`, `nama`, `id_state`, `aktif`, `keterangan`, `tahun_pelaksanaan`) VALUES
(1, 2, 'Pendataan 2014', 2, 2, 'ket', 2014),
(2, 2, 'Pendataan 2015', 1, 1, 'nnn', 2015),
(3, 3, 'Data Dasar Keluarga ', 1, 1, 'Pendataan Profil Desa', 2018),
(4, 4, 'Data Anggota Keluarga', 1, 1, 'Pendataan Profil Desa', 2018);

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_ref_state`
--

CREATE TABLE `analisis_ref_state` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `analisis_ref_state`
--

INSERT INTO `analisis_ref_state` (`id`, `nama`) VALUES
(1, 'Belum Entri / Pendataan'),
(2, 'Sedang Dalam Pendataan'),
(3, 'Selesai Entri / Pendataan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_ref_subjek`
--

CREATE TABLE `analisis_ref_subjek` (
  `id` tinyint(4) NOT NULL,
  `subjek` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `analisis_ref_subjek`
--

INSERT INTO `analisis_ref_subjek` (`id`, `subjek`) VALUES
(1, 'Penduduk'),
(2, 'Keluarga / KK'),
(3, 'Rumah Tangga'),
(4, 'Kelompok');

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_respon`
--

CREATE TABLE `analisis_respon` (
  `id_indikator` int(11) NOT NULL,
  `id_parameter` int(11) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `analisis_respon`
--

INSERT INTO `analisis_respon` (`id_indikator`, `id_parameter`, `id_subjek`, `id_periode`) VALUES
(1, 1, 1, 2),
(2, 6, 1, 2),
(1, 3, 2, 2),
(2, 5, 2, 2),
(1, 2, 3, 2),
(2, 4, 3, 2),
(1, 2, 4, 2),
(2, 4, 4, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_respon_bukti`
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
-- Struktur dari tabel `analisis_respon_hasil`
--

CREATE TABLE `analisis_respon_hasil` (
  `id_master` tinyint(4) NOT NULL,
  `id_periode` tinyint(4) NOT NULL,
  `id_subjek` int(11) NOT NULL,
  `akumulasi` double(8,3) NOT NULL,
  `tgl_update` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `analisis_respon_hasil`
--

INSERT INTO `analisis_respon_hasil` (`id_master`, `id_periode`, `id_subjek`, `akumulasi`, `tgl_update`) VALUES
(2, 1, 129, 25.000, '2018-08-19 22:13:33'),
(2, 1, 254, 5.000, '2018-08-19 22:13:33'),
(2, 1, 298, 17.000, '2018-08-19 22:13:33'),
(2, 1, 304, 17.000, '2018-08-19 22:13:33'),
(2, 1, 308, 21.000, '2018-08-19 22:13:33'),
(2, 1, 309, 9.000, '2018-08-19 22:13:33'),
(2, 2, 1, 25.000, '2018-08-19 22:15:33'),
(2, 2, 2, 13.000, '2018-08-19 22:15:50'),
(2, 2, 3, 8.000, '2018-08-19 22:16:04'),
(2, 2, 4, 8.000, '2018-08-19 22:16:23'),
(2, 2, 129, 5.000, '2018-08-19 22:14:24'),
(2, 2, 254, 25.000, '2018-08-19 22:14:24'),
(2, 2, 298, 24.000, '2018-08-19 22:14:24'),
(2, 2, 304, 21.000, '2018-08-19 22:14:24'),
(2, 2, 308, 24.000, '2018-08-19 22:14:24'),
(2, 2, 309, 21.000, '2018-08-19 22:14:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisis_tipe_indikator`
--

CREATE TABLE `analisis_tipe_indikator` (
  `id` tinyint(4) NOT NULL,
  `tipe` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `analisis_tipe_indikator`
--

INSERT INTO `analisis_tipe_indikator` (`id`, `tipe`) VALUES
(1, 'Pilihan (Tunggal)'),
(2, 'Pilihan (Multivalue)'),
(3, 'Isian Angka'),
(4, 'Isian Tulisan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota_grup_kontak`
--

CREATE TABLE `anggota_grup_kontak` (
  `id_grup_kontak` int(11) NOT NULL,
  `id_grup` int(11) NOT NULL,
  `id_kontak` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `area`
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

--
-- Dumping data untuk tabel `area`
--

INSERT INTO `area` (`id`, `nama`, `path`, `enabled`, `ref_polygon`, `foto`, `id_cluster`, `desk`) VALUES
(1, 'Area 1', '[[[-8.478525723657054,116.05240345001222],[-8.477591903247376,116.04287624359132],[-8.481412063305804,116.04055881500244],[-8.484553055345845,116.04768276214601]]]', 1, 3, '', 0, 'Area 1'),
(2, 'Area 2', '[[[-8.494865867509324,116.05296134948732],[-8.501487264597221,116.0522747039795],[-8.501147708551757,116.04626655578615],[-8.491130670045568,116.0412883758545]]]', 1, 0, '', 0, 'Area 2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `artikel`
--

CREATE TABLE `artikel` (
  `id` int(11) NOT NULL,
  `gambar` varchar(200) DEFAULT NULL,
  `isi` text NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT 1,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_kategori` int(4) NOT NULL,
  `id_user` int(4) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `headline` int(1) NOT NULL DEFAULT 0,
  `gambar1` varchar(200) DEFAULT NULL,
  `gambar2` varchar(200) DEFAULT NULL,
  `gambar3` varchar(200) DEFAULT NULL,
  `dokumen` varchar(400) DEFAULT NULL,
  `link_dokumen` varchar(200) DEFAULT NULL,
  `boleh_komentar` tinyint(1) NOT NULL DEFAULT 1,
  `slug` varchar(200) DEFAULT NULL,
  `hit` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `artikel`
--

INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `boleh_komentar`, `slug`, `hit`) VALUES
(7, '', '<p><strong>Awal mula SID</strong><br /> \"Awalnya ada keinginan dari pemerintah Desa Balerante yang berharap pelayanan pemerintah desa bisa seperti pengunjung rumah sakit yang ingin mencari data pasien rawat inap, tinggal ketik nama di komputer, maka data tersebut akan keluar\"<br /> (Mart Widarto, pengelola Program Lumbung Komunitas)<br /> Program ini mulai dibuat dari awal 2006: <br /> 1. (2006) komunitas melakukan komunikasi dan diskusi lepas tentang sebuah sistem yang bisa digunakan untuk menyimpan data.<br /> 2. (2008) Rangkaian FDG dengan pemerintah desa membahas tentang tata kelola pendokumentasian di desa<br /> 3. (2009) Ujicoba SID yang sudah dikembangkan di balerante<br /> 4. (2009-2010) Membangun SID (aplikasi) dibeberapa desa yang lain: terong (bantul), Nglegi (Gunungkidul) <br /> 5. (2011) Kandangan (Temanggung) Gilangharjo (bantul) Girikarto (gunungkidul) Talun (klaten) Pager Gunung (magelang) <br /> 6. hingga saat ini 2013 sudah banyak desa pengguna SID.<br /> <br /> <strong>SID sebagai tanggapan atas kebutuhan:</strong><br /> Kalau dulu untuk mencari data penduduk menurut kelompok umur saja kesulitan karena tidak mempunyai databasenya. Dengan adanya SID menjadi lebih mudah.<br /> (Nuryanto, Kabag Pelayanan Pemdes Terong)<br /> <br /> Membangun sebuah sistem bukan hanya membuatkan software dan meninggalkan begitu saja, namun ada upaya untuk memadukan sistem dengan kebutuhan yang ada pada desa. sehingga software dapat memenuhi kebutuhan yang telah ada bukan memaksakan desa untuk mengikuti dan berpindah sistem. inilah yang melatari combine melaksanakan alur pengaplikasian software.<br /> 1. Bentuk tim kerja bersama pemerintah desa<br /> 2. Diskusikan basis data apa saja yang diperlukan untuk warga<br /> 3. Himpun data kependudukan warga dari Kartu Keluarga (KK)<br /> 4. Daftarkan proyek SID dan dapatkan aplikasi softwarenya di http://abcd.lumbungkomunitas.net<br /> 5. Install aplikasi software SID di komputer desa<br /> 6. Entry data penduduk ke SID<br /> 7. Basis data kependudukan sudah bisa dimanfaatkan<br /> 8. Diskusikan rencana pengembangan SID sesuai kebutuhan desa<br /> 9. Sebarluaskan informasi desa melalui beragam media untuk warga<br /> <br /> Pemberdayaan data desa yang dibangun diharapkan dapat menjunjung kesejahteraan masyarakat desa, data-data tersebut dapat diperuntukkan untuk riset lebih lanjut tentang kemiskinan, tanggap bencana, sumberdaya desa yang bisa diekspose keluar dan dengan menghubungkan dari desa ke desa dapat mencontohkan banyak hal dalam keberhasilan pemberdayaannya.<br /> (sumber: Buku Sistem Informasi Desa) <br /> <strong><br /></strong></p>', 1, '2013-03-31 13:31:04', 999, 1, 'Awal mula SID', 0, '', '', '', '', '', 1, 'awal-mula-sid', 0),
(32, '', '<div class=\"contentText\">\r\n<div align=\"justify\">Bagian ini berisi informasi dasar mengenai desa kami. Sila klik pada tautan berikut untuk mendapatkan informasi yang lebih rinci.</div>\r\n<div align=\"justify\">&nbsp;</div>\r\n<div align=\"justify\"><ol>\r\n<li>Sejarah Desa</li>\r\n<li>Profil Wilayah Desa</li>\r\n<li>Profil Masyarakat Desa</li>\r\n<li>Profil Potensi Desa</li>\r\n</ol></div>\r\n</div>', 1, '2013-07-29 10:46:44', 999, 1, 'Profil Desa', 0, '', '', '', '', '', 1, 'profil-desa', 3),
(34, '', '<p style=\"text-align: justify;\"><span style=\"color: #ff0000;\"><strong>Contoh (Sila edit halaman ini sesuai dengan deskripsi desa ini)!</strong></span></p>\r\n<p style=\"text-align: justify;\">Berdasarkan data desa pada bulan Februari 2010, jumlah penduduk Desa Terong sebanyak 6484 orang. Jumlah Kepala Keluarga (KK) sebanyak 1605 KK.</p>\r\n<p style=\"text-align: justify;\">Jumlah penduduk Desa Terong usia produktif pada tahun 2009 adalah 4746 orang. Jumlah angkatan kerja tersebut jika dilihat berdasarkan tingkat pendidikannya adalah sebagai berikut:</p>\r\n<table style=\"width: 100%;\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">\r\n<tbody>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\"><strong>No.</strong></p>\r\n</td>\r\n<td style=\"width: 42%;\">\r\n<p style=\"text-align: center;\"><strong>Angkatan Kerja</strong></p>\r\n</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\"><strong>L</strong></p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\"><strong>P</strong></p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\"><strong>Jumlah</strong></p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">1</p>\r\n</td>\r\n<td style=\"width: 42%;\">Tidak Tamat SD</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">59</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">56</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">115</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">2</p>\r\n</td>\r\n<td style=\"width: 42%;\">SD</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">880</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">792</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1672</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">3</p>\r\n</td>\r\n<td style=\"width: 42%;\">SLTP</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">813</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">683</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1496</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">4</p>\r\n</td>\r\n<td style=\"width: 42%;\">SLTA</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">725</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">673</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">1398</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">5</p>\r\n</td>\r\n<td style=\"width: 42%;\">Akademi</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">13</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">11</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">24</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 8%;\">\r\n<p style=\"text-align: center;\">6</p>\r\n</td>\r\n<td style=\"width: 42%;\">Perguruan Tinggi</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">23</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">18</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">41</p>\r\n</td>\r\n</tr>\r\n<tr valign=\"top\">\r\n<td style=\"width: 50%;\" colspan=\"2\">\r\n<p style=\"text-align: center;\">Jumlah Total</p>\r\n</td>\r\n<td style=\"width: 17%;\">\r\n<p style=\"text-align: center;\">2513</p>\r\n</td>\r\n<td style=\"width: 18%;\">\r\n<p style=\"text-align: center;\">2233</p>\r\n</td>\r\n<td style=\"width: 15%;\">\r\n<p style=\"text-align: center;\">4746</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Profil sosial masyarakat</strong></p>\r\n<p style=\"text-align: justify;\">Dalam aktivitas keseharian, masyarakat Desa Terong sangat taat dalam menjalankan ibadah keagamaan. Setiap Rukung Tetangga (RT) dan pedukuhan memiliki kelompok-kelompok pengajian. Pada peringatan hari besar Islam, penduduk Desa Terong kerap menggelar acara peringatan dan karnaval budaya dengan tema yang disesuaikan dengan hari besar keagamaan. Sebagian besar warga Desa Terong terafiliasi pada organisasi kemasyarakat Islam Muhammadiyah.</p>\r\n<p style=\"text-align: justify;\">Gelaran perayaan lain selalu dilakukan dalam rangka memperingati Hari Kemerdekaan Republik Indonesia. Setiap pedukuhan akan turut serta dan semangat menampilkan ciri khasnya dalam acara peringatan dan karnaval budaya.</p>\r\n<p style=\"text-align: justify;\">Kelompok pemuda di Desa Terong yang tergabung dalam kelompok pegiat Karang Taruna menjadi aktor utama dalam banyak kegiatan desa. Kelompok ini aktif menggelar program kegiatan untuk isu demokrasi kepada warga, penguatan ekonomi produktif, pelatihan penanggulangan bencana, dan kampanye Gerakan Remaja Sayang Ibu (GEMAS).</p>\r\n<p style=\"text-align: justify;\">Sejumlah penduduk Desa Terong bekerja merantau di daerah di luar Yogyakarta. Namun, ikatan sosial mereka terhadap tanah kelahiran tetap tinggi. Penduduk asli Desa Terong yang tinggal di Jakarta dan sekitarnya misalnya, mereka membentuk paguyuban untuk memelihara silaturahmi antar sesama warga perantauan. Setiap bulan diadakan kegiatan arisan keliling secara bergilir di setiap tempat anggotanya. Setiap dua tahun sekali diadakan pula kegiatan mudik bersama ke kampung halaman di Desa Terong</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Profil politik masyarakat</strong></p>\r\n<p style=\"text-align: justify;\">Warga Desa Terong dikenal sebagai kelompok masyarakat yang paling aktif dan memiliki potensi tertinggi untuk berpartisipasi dalam pemberian suara untuk Pemilihan Umum dan Pemilihan Kepala Daerah Langsung. Tingkat partisipasi warga di desa ini terbanyak jika dibandingkan dengan desa lain di Kecamatan Dlingo, Bantul.</p>\r\n<p style=\"text-align: justify;\">Warga Desa Terong sangat aktif dalam mengawal proses penyusunan Rancangan Undang-Undang (RUU) Keistimewaan Yogyakarta. Banyak warga Desa Terong yang tergabung dalam Gerakan Rakyat Yogyakarta (GRY) dan aktif dalam beragam kegiatan serta demontrasi mendukung penetapan keistimewaan Yogyakarta. Kepala Desa Terong Sudirman Alfian merupakan Ketua Paguyuban Lurah dan Pamong Desa Ing Sedya Memetri Asrining Yogyakarta (ISMAYA) se Daerah Istimewa Yogyakarta (DIY). Beliau ditunjuk pula sebagai anggota tim perumus RUU Keistimewaan Yogyakarta bersi masyarakat Yogyakarta. Salah satu hal yang diperjuangkan dalam RUU tersebut adalah tidak adanya pelaksanaan pemilihan kepala daerah langsung dalam pemilihan Gubernur DIY; dengan mempertahankan konsep dwi tunggal Sri Sultan Hamengku Buwono dan Paku Alam sebagai Gubernur dan Wakil Bubernur DIY.</p>\r\n<p style=\"text-align: justify;\">Permasalahan mendasar yang ada di Desa Terong adalah tidak imbangnya jumlah pencari kerja dengan jumlah lapangan kerja yang tersedia. Sekalipun jumlah pengangguran di Desa Terong pada Tahun 2009 hanya orang tetapi kebanyakan mereka bekerja di luar Desa. Jadi, perlu gerakan kembali ke Desa serta menarik sumber-sumber ekonomi ke desa agar pencari kerja tidak banyak tersedot ke luar Desa.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Sumber:<br />Laporan Pertanggung Jawaban Lurah Desa Terong, Kecamatan Dlingo, Kabupaten Bantul tahun 2009.</p>', 1, '2013-07-29 11:13:36', 999, 1, 'Profil Masyarakat Desa', 0, '', '', '', '', '', 1, 'profil-masyarakat-desa', 0),
(36, '', '<p>Kontak kami berisi cara menghubungi desa, seperti contoh berikut :</p>\r\n<p>Alamat : Jl desa no 01</p>\r\n<p>No Telepon : 081xxxxxxxxx</p>\r\n<p>Email : xx@desa.com</p>', 1, '2013-07-29 11:28:31', 999, 1, 'Kontak Kami', 0, '', '', '', '', '', 1, 'kontak-kami', 1),
(37, '', '<p><span style=\"color: #ff0000;\"><strong>Contoh (Sila edit halaman ini dan sesuaikan dengan deskripsi untuk desa ini)!</strong></span><br /><br />Susunan Organisasi Badan Permusyawaratan Desa:</p>\r\n<p>Ketua</p>\r\n<p>Sekretaris</p>\r\n<p>Anggota</p>', 1, '2013-07-29 11:33:33', 999, 1, 'Badan Permusyawaratan Desa', 0, '', '', '', '', '', 1, 'badan-permusyawaratan-desa', 0),
(38, '', '<p>Berisi data lembaga yang ada di desa beserta deskripsi dan susunan pengurusnya</p>', 1, '2013-07-29 11:38:33', 999, 1, 'Lembaga Kemasyarakatan', 0, '', '', '', '', '', 1, 'lembaga-kemasyarakatan', 1),
(41, '', '<p>Agenda Bulan Agustus :</p>\r\n<p>01/08/2013 : Rapat rutin</p>\r\n<p>04/08/2013 : Pertemuan Pengurus</p>\r\n<p>05/08/2013 : Seminar</p>\r\n<p>&nbsp;</p>', 1, '2013-07-30 07:08:52', 1000, 1, 'Rapat bulanan', 0, '', '', '', '', '', 1, 'agenda', 1),
(42, '', '<p>Daftar Undang Undang Desa</p>\n<p><a href=\"../../../../../../assets/front/dokumen/Profil_SSN_SMP1Kepil.pdf\">1. UU No desa</a></p>\n<p>berisi asf basdaf.</p>\n<p>&nbsp;</p>\n<p><a href=\"kebumenkab.go.id/uu.pdf\">2.UU Perdangangan</a></p>', 1, '2014-04-20 11:21:56', 999, 1, 'Undang Undang', 0, '', '', '', '', '', 1, 'undang-undang', 0),
(43, '', '<p>Isi Peraturan Pemerintah</p>', 1, '2014-04-20 11:24:01', 999, 1, 'Peraturan Pemerintah', 0, '', '', '', '', '', 1, 'peraturan-pemerintah', 0),
(44, '', '<p>Isi Peraturan Desa</p>', 1, '2014-04-20 11:24:35', 999, 1, 'Peraturan Desa', 0, '', '', '', '', '', 1, 'peraturan-desa', 0),
(45, '', '<p>Isi Peraturan Kepala Desa</p>', 1, '2014-04-20 11:25:04', 999, 1, 'Peraturan Kepala Desa', 0, '', '', '', '', '', 1, 'peraturan-kepala-desa', 0),
(46, '', '<p>Isi Keputusan kepala desa</p>', 1, '2014-04-20 11:25:36', 999, 1, 'Keputusan Kepala Desa', 0, '', '', '', '', '', 1, 'keputusan-kepala-desa', 0),
(47, '', '<p>Isi Panduan</p>', 1, '2014-04-20 11:38:10', 999, 1, 'Panduan', 0, '', '', '', '', '', 1, 'panduan', 0),
(51, '', '<p>Wahai masyarakat yang ber,,,,,,,,,,,,,,,,,,,,</p>\n<p>no hp : 097867575</p>\n<p>email: jkgkgkg</p>\n<p>ato komentar di bawah ini :</p>', 1, '2014-04-22 03:11:20', 999, 1, 'Pengaduan', 0, '', '', '', '', '', 1, 'pengaduan', 0),
(59, '', '<ol>\r\n<li><strong>a.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Jumlah Penduduk</strong></li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<table style=\"width: 372px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah jiwa</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah laki-laki</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah perempuan</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Jumlah Kepala Keluarga</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">KK</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li><strong>b.&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Tingkat Pendidikan</strong></li>\r\n</ol>\r\n<table style=\"width: 373px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Belum sekolah</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Usia 7-45 tahun tidak pernah sekolah</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pernah sekolah SD tetapi tidak tamat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pendidikan terakhir</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Tamat SD/sederajat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">SLTP/sederajat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">SLTA/sederajat</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">D-1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">D-2</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">D-3</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">S-1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">S-2</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">S-3</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"121\">&nbsp;</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li><strong>c.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>Mata Pencaharian</strong></li>\r\n</ol>\r\n<p><strong>&nbsp;</strong></p>\r\n<table style=\"width: 372px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Petani</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">246</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Buruh tani</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">125</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Buruh/swasta</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">136</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pegawai Negeri</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">35</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pengrajin</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">&nbsp;</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pedagang</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">9</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Peternak</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">-</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Nelayan</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">-</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Montir</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">8</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Dokter</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">-</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">POLRI/ABRI</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pensiunan</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">36</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Perangkat Desa</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">15</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Pembuat Bata</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">3</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><strong>&nbsp;</strong></p>\r\n<ol>\r\n<li><strong>d.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong>AGAMA</strong></li>\r\n</ol>\r\n<table style=\"width: 372px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Islam</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">2215</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Kristen</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">5</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Katholik</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">&nbsp;</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Hindu</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">&nbsp;</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"168\">\r\n<p align=\"right\">Budha</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"120\">\r\n<p align=\"right\">1</p>\r\n</td>\r\n<td valign=\"bottom\" nowrap=\"nowrap\" width=\"84\">\r\n<p align=\"right\">orang</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><strong>&nbsp;</strong></p>\r\n<p><strong>&nbsp;</strong></p>', 1, '2014-04-30 11:23:24', 999, 1, 'Profil Potensi Desa', 0, '', '', '', '', '', 1, 'profil-potensi-desa', 0),
(62, '', '<p>Lembaga Kemasyarakatan Desa (LKMD) adalah salah satu lembaga kemasyaratan yang berada di desa.</p>\n<p>TUGAS LKMD</p>\n<ol>\n<li>menyusun rencana pembangunan secara partisipatif,</li>\n<li>menggerakkan swadaya gotong royong masyarakat,</li>\n<li>melaksanakan dan</li>\n<li>mengendalikan pembangunan.</li>\n</ol>\n<p align=\"left\">FUNGSI LKMD</p>\n<ol>\n<li>penampungan dan penyaluran aspirasi masyarakat dalam pembangunan;</li>\n<li>penanaman dan pemupukan rasa persatuan dan kesatuan masyarakat dalam kerangka memperkokoh Negara Kesatuan Republik Indonesia;</li>\n<li>peningkatan kualitas dan percepatan pelayanan pemerintah kepada masyarakat;</li>\n<li>penyusunan rencana, pelaksanaan, pelestarian dan pengembangan hasil-hasil pembangunan secara partisipatif;</li>\n<li>penumbuhkembangan dan penggerak prakarsa, partisipasi, serta swadaya gotong royong masyarakat; dan</li>\n<li>penggali, pendayagunaan dan pengembangan potensi sumber daya alam serta keserasian lingkungan hidup.</li>\n</ol>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 11:39:07', 999, 1, 'LKMD', 0, '', '', '', '', '', 1, 'lkmd', 0),
(63, '', '<p>TUGAS PKK</p>\n<ol>\n<li>menyusun rencana kerja PKK Desa/Kelurahan, sesuai dengan basil Rakerda Kabupaten/Kota;</li>\n<li>melaksanakan kegiatan sesuai jadwal yang disepakati;</li>\n<li>menyuluh dan menggerakkan kelompok-kelompok PKK Dusun/Lingkungan, RW, RT dan dasa wisma agar dapat mewujudkan kegiatan-kegiatan yang telah disusun dan disepakati;</li>\n<li>menggali, menggerakan dan mengembangkan potensi masyarakat, khususnya keluarga untuk meningkatkan kesejahteraan keluarga sesuai dengan kebijaksanaan yang telah ditetapkan;</li>\n<li>melaksanakan kegiatan penyuluhan kepada keluarga-keluarga yang mencakup kegiatan bimbingan dan motivasi dalam upaya mencapai keluarga sejahtera;.</li>\n<li>mengadakan pembinaan dan bimbingan mengenai pelaksanaan program kerja;</li>\n<li>berpartisipasi dalam pelaksanaan program instansi yang berkaitan dengan kesejahteraan keluarga di desa/kelurahan;</li>\n<li>membuat laporan basil kegiatan kepada Tim Penggerak PKK Kecamatan dengan tembusan kepada Ketua Dewan Penyantun Tim Penggerak PKK setempat;</li>\n<li>melaksanakan tertib administrasi; dan</li>\n<li>mengadakan konsultasi dengan Ketua Dewan Penyantun Tim Penggerak PKK setempat.</li>\n</ol>\n<p>&nbsp;</p>\n<p>FUNGSI PKK</p>\n<ol>\n<li>penyuluh, motivator dan penggerak masyarakat agar mau dan mampu melaksanakan program PKK; dan</li>\n<li>fasilitator, perencana, pelaksana, pengendali, pembina dan pembimbing Gerakan PKK.</li>\n</ol>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"center\"><strong>Alamatn</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"center\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"180\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"241\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 2, '2014-04-30 11:41:13', 999, 1, 'PKK', 0, '', '', '', '', '', 1, 'pkk', 0),
(64, '', '<p align=\"left\">TUGAS &nbsp;KARANGTARUNA</p>\n<p align=\"left\">menanggulangi berbagai masalah kesejahteraan sosial terutama yang dihadapi generasi muda, baik yang bersifat preventif, rehabilitatif, maupun pengembangan potensi generasi muda di lingkungannya</p>\n<p align=\"left\">FUNGSI KARANGTAURNA</p>\n<ol>\n<li>penyelenggara usaha kesejahteraan sosial;</li>\n<li>penyelenggara pendidikan dan pelatihan bagi masyarakat;</li>\n<li>penyelenggara pemberdayaan masyarakat terutama generasi muda di lingkungannya secara komprehensif, terpadu dan terarah serta berkesinambungan;</li>\n<li>penyelenggara kegiatan pengembangan jiwa kewirausahaan bagi generasi muda di lingkungannya;</li>\n<li>penanaman pengertian, memupuk dan meningkatkan kesadaran tanggung jawab sosial generasi muda;</li>\n<li>penumbuhan dan pengembangan semangat kebersamaan, jiwa kekeluargaan, kesetiakawanan sosial dan memperkuat nilai-nilai kearifan dalam bingkai Negara Kesatuan Republik Indonesia;</li>\n</ol>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 11:44:28', 999, 1, 'Karang Taruna', 0, '', '', '', '', '', 1, 'karang-taruna', 0);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `boleh_komentar`, `slug`, `hit`) VALUES
(65, '', '<p align=\"left\">TUGAS RT/RW</p>\n<p align=\"left\">membantu Pemerintah Desa dan Lurah dalam penyelenggaraan urusan pemerintahan</p>\n<p align=\"left\">FUNGSI PKK</p>\n<ol>\n<li>pendataan kependudukan dan pelayanan administrasi pemerintahan lainnya;</li>\n<li>pemeliharaan keamanan, ketertiban dan kerukunan hidup antar warga;</li>\n<li>pembuatan gagasan dalam pelaksanaan pembangunan dengan mengembangkan aspirasi dan swadaya murni masyarakat; dan</li>\n<li>penggerak swadaya gotong royong dan partisipasi masyarakat di wilayahnya.</li>\n</ol>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\"><strong>Nama Pejabat</strong></p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">Ketua RW 1</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">Ketua RW 1 Rt 01</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">Ketua RW 1 Rt 02</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"186\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"204\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"193\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 1, '2014-04-30 11:45:19', 999, 1, 'RT RW', 0, '', '', '', '', '', 1, 'rt-rw', 0),
(66, '', '<p class=\"Default\">&nbsp;</p>\n<p class=\"Default\">Tim Koordinasi Percepatan Penanggulangan Kemiskinan Desa yang selanjutnya disingkat TKP2KDes adalah wadah koordinasi lintas sektor dan lintas pemangku kepentingan untuk percepatan penanggulangan kemiskinan di desa.</p>\n<p class=\"Default\">TKP2KDes bertugas mengkoordinasikan perencanaan, pengorganisasian, pelaksanaan dan pengendalian program penanggulangan kemiskinan di tingkat Desa.</p>\n<p>( Perda Kabupaten Kebumen Nomor 20 Tahun 2012 Tentang Percepatan Penanggulangan Kemiskinan )</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip; Nomor : &hellip;&hellip;tanggal &hellip;&hellip;.. bulan&hellip;.. tentang &hellip;..</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 2, '2014-04-30 11:46:01', 999, 1, 'TKP2KDes', 0, '', '', '', '', '', 1, 'tkp2kdes', 0),
(67, '', '<p class=\"Default\">&nbsp;</p>\n<p class=\"Default\">Kelompok Perlindungan Anak Desa atau Kelurahan yang selanjutnya disingkat KPAD atau KPAK adalah lembaga perlindungan anak berbasis masyarakat yang berkedudukan dan melakukan kerja&ndash;kerja perlindungan anak di wilayah desa atau kelurahan tempat anak bertempat tinggal&nbsp;&nbsp;&nbsp;&nbsp; ( Perda Kaupaten Kebumen No 3 Tahun 2013 Tentang Penyelenggaraan Perlindungan Anak&nbsp; )</p>\n<p>TUGAS-TUGAS KPAD</p>\n<p>1.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sosialisasi</p>\n<ol>\n<li>Mensosialisasikan kepada masyarakat tentang hak-hak anak</li>\n<li>Mempromosikan CHILD RIGHTS dan CHILD PROTECTION</li>\n<li>Melakukan upaya pencegahan, respon dan penanganan kasus kasus kekerasan terhadap anak dan masalah anak.</li>\n</ol>\n<p>1.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mediasi</p>\n<ol>\n<li>Mengedepankan upaya musyawarah dan mufakat (Rembug Desa)&nbsp; dalam menyelesaikan masalah &ndash; (Restorative Justive)</li>\n<li>Melakukan koordinasi dengan pihak terkait di level desa, kecamatan dan kabupaten dalam upaya perlindungan anak.</li>\n<li>Melakukan pendampingan kasus (dari pelaporan &ndash; medis &ndash; psikologi - reintegrasi)</li>\n</ol>\n<p>1.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fasilitasi</p>\n<ol>\n<li>Memfasilitasi terbentuknya kelompok anak di desa sebagai media partisipasi anak</li>\n<li>Memfasilitasi partisipasi anak untuk terlibat dalam penyusunan perencanaan pembangunan yang berbasis hak anak (penyusunan RPJMDesa)</li>\n</ol>\n<p>1.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Dokumentasi</p>\n<ol>\n<li>Mendokumentasikan semua proses yang dilakukan (Kegiatan Promosi; Penanganan Kasus dan mencatat kasus yang dilaporkan; Perkembangan Kasus, Pertemuan,dll)</li>\n</ol>\n<p>1.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Advokasi</p>\n<ol>\n<li>Mendorong adanya kebijakan dan penganggaran untuk perlindungan anak di level desa</li>\n<li>Menerima pengaduan kasus dan konsultasi tentang perlindungan anak</li>\n<li>Berhubungan dengan P2TP2A dan LPA untuk pendampingan hukum kasus anak (korban dan atau pelaku)</li>\n</ol>\n<p class=\"Default\">&nbsp;</p>\n<p class=\"Default\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS &hellip;&hellip;&hellip;&hellip;</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang Penetapan Pengurus Lembaga Kemasyarakatan Desa &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p align=\"left\">&nbsp;</p>\n<p>&nbsp;</p>', 2, '2014-04-30 11:47:21', 999, 1, 'KPAD', 0, '', '', '', '', '', 1, 'kpad', 0),
(68, '', '<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK TERNAK &hellip;..</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang</p>\n<p align=\"center\">&hellip;&hellip;&hellip;&hellip;&hellip;.. &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK WANITA TANI TERNAK&nbsp; &hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align=\"center\">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p>&nbsp;</p>', 2, '2014-04-30 11:47:58', 999, 1, 'Kelompok Ternak', 0, '', '', '', '', '', 1, 'kelompok-ternak', 0),
(69, '', '<p align=\"center\"><strong>DAFTAR NAMA PENGURUS GAPOKTAN</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. Juni &hellip;.. tentang</p>\n<p align=\"center\">&hellip;&hellip;&hellip;&hellip;&hellip;.. &hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"left\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK TANI &hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align=\"center\">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\">&nbsp;</p>\n<p align=\"center\"><strong>DAFTAR NAMA PENGURUS KELOMPOK TANI &hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>MASA JABATAN &hellip;&hellip;s/d&hellip;&hellip;.</strong></p>\n<p align=\"center\"><strong>DESA &hellip;&hellip;&hellip;&hellip;.. KECAMATAN &hellip;&hellip;&hellip; KABUPATEN &hellip;&hellip;&hellip;..</strong></p>\n<p align=\"center\">Surat Keputusan Kepala Desa &hellip;&hellip;&hellip;&hellip;. Nomor : &hellip;&hellip;&hellip;&hellip;&hellip; tanggal &hellip;&hellip;.. bulan&hellip;..</p>\n<p align=\"center\">tentang &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.</p>\n<p align=\"center\">&nbsp;</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\"><strong>No</strong></p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"center\"><strong>Jabatan</strong></p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"center\"><strong>Nama Pengurus</strong></p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"center\"><strong>Alamat</strong></p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">1</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">2</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"center\">3</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n<tr>\n<td valign=\"top\" width=\"55\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"162\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"192\">\n<p align=\"left\">&nbsp;</p>\n</td>\n<td valign=\"top\" width=\"229\">\n<p align=\"left\">&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>', 1, '2014-04-30 11:48:39', 999, 1, 'Kelompok Tani', 0, '', '', '', '', '', 1, 'kelompok-tani', 0),
(70, '', '<p>Linmas</p>', 1, '2014-04-30 11:53:18', 999, 1, 'LinMas', 0, '', '', '', '', '', 1, 'linmas', 0),
(71, '', '<p>Kelompok Ekonomi Lainya</p>', 2, '2014-04-30 11:54:20', 999, 1, 'Kelompok Ekonomi Lainya', 0, '', '', '', '', '', 1, 'kelompok-ekonomi-lainya', 0),
(83, '', '<p>Tiap hari rapat sampai kumat</p>', 1, '2014-11-06 11:17:52', 1000, 1, 'Rapat Lagi', 0, '', '', '', '', '', 1, 'rapat-lagi', 0);
INSERT INTO `artikel` (`id`, `gambar`, `isi`, `enabled`, `tgl_upload`, `id_kategori`, `id_user`, `judul`, `headline`, `gambar1`, `gambar2`, `gambar3`, `dokumen`, `link_dokumen`, `boleh_komentar`, `slug`, `hit`) VALUES
(85, '1471927406download (1).jpg', '<div class=\"contentText\">\r\n<div align=\"justify\">Bagian ini berisi informasi mengenai PemerintahanDesa. Sila klik pada tautan berikut untuk mendapatkan informasi yang lebih rinci.</div>\r\n<div align=\"justify\">&nbsp;</div>\r\n<ol>\r\n<li>Visi dan Misi</li>\r\n<li>Pemerintah Desa</li>\r\n<li>Badan Permusyawaratan Desa</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI dan MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Senggigi Berseri\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>(Bersih, Relegius, Sejahtera, Rapi, dan Indah)</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Terwujudnya masyarakat Desa Senggigi yang Bersih, Relegius, Sejahtra, Rapi dan Indah melalui Akselerasi Pembangunan yang berbasis Keagamaan, Budaya Hukum dan Berwawasan Lingkungan dengan berorentasi pada peningkatan Kinerja Aparatur dan Pemberdayaan Masyarakat\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>Misi dan Program Desa Senggigi</strong></p>\r\n<p style=\"text-align: justify;\">Dan untuk melaksanakan visi Desa Senggigi dilaksanakan misi dan program sebagai berikut:</p>\r\n<p style=\"text-align: justify;\">1. Pembangunan Jangka Panjang</p>\r\n<ul>\r\n<li style=\"text-align: justify;\">Melanjutkan Pembangunan Desa yang belum terlaksana</li>\r\n<li style=\"text-align: justify;\">Meningkatkan Kerjasama antara pemerintah Desa dengan Lembaga desa yang ada</li>\r\n<li style=\"text-align: justify;\">Meningkatkan Kesejahtraan Masyarakat desa dengan meningkatkan sarana dan prasarana ekonomi warga.&nbsp;</li>\r\n</ul>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">2. Pembangunan Jangka Pendek &nbsp;</p>\r\n<ul>\r\n<li style=\"text-align: justify;\">Mengembangkan dan Menjaga serta melestarikan ada istiadat desa terutama yang telah mengakar di desa senggigi.&nbsp;</li>\r\n<li style=\"text-align: justify;\">Meningkatkan pelayanan dalam bidang pemerintahan kepada warga masyarakat&nbsp;</li>\r\n<li style=\"text-align: justify;\">Meningkatkan sarana dan prasarana ekonomi warga desa dengan perbaikan prasarana dan sarana ekonomi.</li>\r\n<li style=\"text-align: justify;\">Meningkatkan sarana dan prasarana pendidikan guna peningkatan sumber daya manusia desa senggigi.&nbsp;</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>Kepala Desa Senggigi</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>Muhammad Ilham</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n</div>', 1, '2014-11-07 03:53:54', 999, 1, 'Pemerintahan Desa', 0, '', '', '', '', '', 1, 'pemerintahan-desa', 3),
(90, '1471968200IMG-20160823-WA0007.jpg', '', 1, '2016-08-23 17:03:21', 5, 1, 'PERDES PHBS ', 0, '1471968200IMG-20160823-WA0012.jpg', '1471968200', '1471968200', 'PERDES PHBS.docx', 'PERDES PHBS ', 1, 'perdes-phbs', 0),
(92, '1472006396', '<p><strong>Susunan Organisasi Pemerintah Desa Senggigi</strong><br /><br />Kepala Desa: MUHAMMAD ILHAM<br />Sekretaris Desa:&nbsp;<span>MUSTAHIQ, S.Adm</span><br />Kepala Urusan Pemerintahan: SYAFRUDIN<br />Kepala Urusan&nbsp;Pembangunan: SYAFI\'I, SE<br />Kepala Urusan&nbsp;Kesra: HAMIDIAH<br />Kepala Urusan&nbsp;Keuangan: MARDIANA<br />Kepala Urusan&nbsp;Trantib: SUPARDI RUSTAM<br />Kepala Urusan&nbsp;Umum: MAHRUP<br /><br /></p>', 1, '2016-08-24 03:39:56', 999, 1, 'Pemerintah Desa', 0, '1472006396', '1472006396', '1472006396', '', '', 1, 'pemerintah-desa', 3),
(93, '1549419057_visi-850x442.jpg', '<p style=\"text-align: center;\"><strong>VISI dan MISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>VISI</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Senggigi Berseri\"</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>(Bersih, Relegius, Sejahtera, Rapi, dan Indah)</strong></p>\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<p style=\"text-align: center;\"><strong>\"Terwujudnya masyarakat Desa Senggigi yang Bersih, Relegius, Sejahtera, Rapi dan Indah melalui Akselerasi Pembangunan yang berbasis Keagamaan, Budaya Hukum dan Berwawasan Lingkungan dengan berorentasi pada peningkatan Kinerja Aparatur dan Pemberdayaan Masyarakat\"</strong></p>\r\n<p>&nbsp;&nbsp;</p>\r\n<p><strong>MISI</strong></p>\r\n<p><strong>Misi dan Program Desa Senggigi</strong></p>\r\n<p>Dan untuk melaksanakan visi Desa Senggigi dilaksanakan misi dan program sebagai berikut:</p>\r\n<p>1. Pembangunan Jangka Panjang</p>\r\n<p>&nbsp; &nbsp; - Melanjutkan pembangunan desa yang belum terlaksana.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan kerjasama antara pemerintah desa dengan lembaga desa yang ada.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan kesejahtraan masyarakat desa dengan meningkatkan sarana dan prasarana ekonomi warga.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>2. Pembangunan Jangka Pendek &nbsp;</p>\r\n<p>&nbsp; &nbsp; - Mengembangkan dan Menjaga serta melestarikan ada istiadat desa terutama yang telah mengakar di desa senggigi.&nbsp;</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan pelayanan dalam bidang pemerintahan kepada warga masyarakat&nbsp;</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan sarana dan prasarana ekonomi warga desa dengan perbaikan prasarana dan sarana ekonomi.</p>\r\n<p>&nbsp; &nbsp; - Meningkatkan sarana dan prasarana pendidikan guna peningkatan sumber daya manusia Desa Senggigi.&nbsp;</p>', 1, '2016-08-24 03:48:28', 999, 1, 'Visi dan Misi', 0, '1472006908', '1472006908', '1472006908', '', '', 1, 'visi-dan-misi', 3),
(94, '1527544437_gotong-royong-pantai.jpeg', '<p style=\"text-align: justify;\">Gotong royong yang dulu digagas pertama kali oleh pendiri bangsa, Ir. Soekarno kian hari semakin terkikis dengan budaya individual ditengah persaingan yang begitu ketat dalam mencapai tujuan tertentu, kenyataan inilah yang membuat nilai-nilai sosial ditatanan masyarakat yang sejak dulu sudah ditanamkan oleh nenek moyang kita luntur seiring dengan perkembangan jaman. padahal untuk mencapai suatu tujuan dan cita-cita bersama seharusnya dikerjakan secara bersama-sama.&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Maka dengan kenyataan inilah, pemerintah desa senggigi kembali melakukan sebuah inovasi baru dalam merangkul masyarakat agar terus menanamkan kebudayaan \"Gotong Raoyong\". kegitan gotong royong dalam pembangunan jalan desa sedikitnya melibatkan hampir 95% masyarakat senggigi, kebersamaan dan ikatan persaudaraan atas kepentingan bersama menjadi satu ketika semua masyarakat desa terlibat aktif, tanpa harus melihat tatanan dan golongan yang ada. masyarakat saling bahu membahu seiring kegitan gotong royong.&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', 1, '2016-08-24 04:02:44', 1, 1, 'Membangun Desa Lewat Gotong Royong', 3, '1472782825artikel-2-2.jpeg', '1472007764', '1472007764', '', '', 1, 'membangun-desa-lewat-gotong-royong', 0),
(95, '1527540313_kemerdekaan-pantai.jpg', '<p>Desa Senggigi ikut memeriahkan perayaan 17 Agustus 2016 sebagai hari jadi Indonesia yang ke 71 melalui kegiatan Karnaval yang diselenggarakan oleh Camat Batulayar Kabupaten Lombok Barat NTB. Acara karnaval dilaksanakan pada hari Rabu, 17 Agustus 2016 dimulai pukul 15.30 s/d 17.00 wita. Masing-masing desa berkumpul disekitaran kantor Camat Batulayar, dan berjalan menuju Taman Bale Pelangi Desa Sandik sebagai pusat titik kumpul seluruh peserta karnaval.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Dalam karnaval ini, Desa Senggigi melibatkan berbagai unsur masyarakat seperti tokoh masyarakat, perempuan, pemuda dan anak-anak dengan menggunakan baju adat dan berbagai macam asesoris hari kemerdekaan, kegitan tersebut adalah salah satu cara bagaimana memupuk semangat bagi setiap warga negara, khususnya kaum muda sebagai harapan bangsa, yang kian hari semakin terkikis dengan pengaruh global saat ini.</p>\r\n<p>&nbsp;</p>\r\n<p>Lewat karang taruna desa senggigi, pemupukan pemberian semangat dalam berpacu memajukan desa dan bangsa terus dilakukan, berbagai macam kegiatan tahapan dalam pelaksanaan hari kemerdekaan terus di lakukan.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', 1, '2016-08-24 06:05:21', 1, 1, 'Perayaan Hari Kemerdekaan 2016', 3, '1472782634galeri-1-2.jpeg', '1472015120', '1472015120', '', '', 1, 'perayaan-hari-kemerdekaan-2016', 0),
(96, '1472782915artikel-3-1.jpeg', '<p>Dalam rapat pembahasan komitmen perekrutan karyawan hotel pada tanggal 24 Agustus 2016 di kantor desa sengigi telah menyepakati beberapa komitmen bersama diantaranya sebagai berikut:</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>1. Dalam perekrutan karyawan, pihak hotel harus memprioritaskan masyarakat senggigi minimal 35%</p>\n<p>2. Pihak Hotel harus mengikuti program perencanaan tenaga kerja desa senggigi sesua dengan VISI dan MISI desa</p>\n<p>3. Pihak hotel harus melakukan kordinasi dengan pemerintah desa ketika perekrutan karyawan&nbsp;</p>\n<p>4. Pihak Hotel harus melakukan pelatihan bagi calon karyawan, khususnya karyawan yang berasal dari desa sengggigi</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>Bagi rekan-rekan pemuda dan masyarakat harap melakukan kordinasi dengan pemerintah desa terkait dengan beberapa hasil pertemuan dalam membangun komitme dengan pihak hotel, jika ada hal mendesak terkait beberapa syarat ketentuan perekrutan, rekan-rekan pemuda dan masyarakat bisa menghubungi kami di kantor desa..</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>', 1, '2016-08-24 06:55:10', 4, 1, 'Rapat membangun Komitmen antara Karang Taruna Desa Senggigi dengan Taruna Hotel', 1, '1472018109IMG-20160824-WA0000.jpg', '1472018109', '1472018109', '', '', 1, 'rapat-membangun-komitmen-antara-karang-taruna-desa-senggigi-dengan-taruna-hotel', 0),
(97, '1472019299', '<p>Halaman ini berisi tautan menuju informasi mengenai Basis Data Desa. Ada dua jenis data yang dimuat dalam sistem ini, yakni basis data kependudukan dan basis data sumber daya desa. Sila klik pada tautan berikut untuk mendapatkan tampilan data statistik per kategori.</p>\r\n<ol>\r\n<li>Data Wilayah Administratif&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Pendidikan&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Pekerjaan&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Golongan Darah&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Agama&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Jenis Kelamin&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Kelompok Umur&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Penerima Raskin&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</li>\r\n<li>Data Penerima BPJS &nbsp; &nbsp; &nbsp; &nbsp;</li>\r\n<li>Data Warga Negara &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;</li>\r\n</ol>\r\n<p>Data yang tampil adalah statistik yang didapatkan dari proses olah data dasar yang dilakukan secara&nbsp;<em>offline</em>&nbsp;di kantor desa secara rutin/harian. Data dasar di kantor desa diunggah ke dalam sistem&nbsp;<em>online</em>&nbsp;di website ini secara berkala. Sila hubungi kontak pemerintah desa untuk mendapatkan data dan informasi desa termutakhir.</p>', 1, '2016-08-24 07:14:59', 999, 1, 'Data Desa', 0, '1472019299', '1472019299', '1472019299', '', '', 1, 'data-desa', 3),
(98, '1549419108_desa.jpg', '<p>Wilayah desa berisi tentang penjelasan dan deskripsi letak wilayah desa. contohnya sebagai berikut :<br />Batas-batas :<br />Utara&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kelurahan a<br />Timur &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Desa b<br />Selatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Desa c<br />Barat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kelurahan d dan Desa e<br />Luas Wilayah Desa Penglatan&nbsp;&nbsp; : 186,193 Ha<br />Letak Dan Batas Desa x<br />Desa Penglatan terletak pada posisi 115. 7.20 LS 8. 7.10 BT, dengan ketinggian kurang lebih 250 M diatas permukaan laut.</p>\r\n<p>Peta Desa:</p>\r\n<p><iframe src=\"https://www.google.co.id/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Logandu,+Karanggayam&amp;aq=0&amp;oq=logandu&amp;sll=-2.550221,118.015568&amp;sspn=52.267573,80.332031&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&amp;z=14Ôë¬=-7.55854,109.634173&amp;output=embed\" width=\"600\" height=\"450\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\"></iframe></p>', 1, '2016-08-26 07:28:14', 999, 1, 'Wilayah Desa', 0, '1472192894', '1472192894', '1472192894', '', '', 1, 'wilayah-desa', 0),
(99, '1472228892Raja Lombok 1902.jpg', '<p style=\"text-align: justify;\" align=\"center\">Sejarah telah mencatat bahwa Pulau Lombok pernah menjadi wilayah kekuasaan Kerajaan Karang Asem Bali yang berkedudukan di Cakranegara dengan seorang raja bernama Anak Agung Gde Jelantik. Berakhirnya <strong>kekuasaan</strong> Kerajaan Karang Asem Bali di Pulau Lombok setelah datangnya Belanda pada Tahun 1891, dimana Belanda pada waktu itu ingin menguasai Pulau Lombok dengan dalih pura-pura membantu rakyat Lombok yang dianggap tertindas oleh Pemerintahan Raja Lombok yaitu Anak Agung Gede Jelantik.</p>\r\n<p style=\"text-align: justify;\">Pada masa kekuasaan Raja Lombok yaitu Anak Agung Gde Jelantik, wilayah Desa Senggigi ( Dusun Mangsit, Kerandangan, Senggigi dan Dusun Loco) masih bergabung dengan Desa Senteluk yang sekarang menjadi Desa Meninting . Sedangkan pada tahun 1962 Desa Senteluk pecah menjadi 2 (Dua) desa yaitu Desa Meninting dan Desa Batulayar dan Dusun Mangsit,Kerandangan,Senggigi dan Dusun Loco bergabung ke Desa Batulayar.&nbsp;</p>\r\n<p style=\"text-align: justify;\">Pemberian nama Desa Batulayar pada waktu itu yang lazim disebut dengan Pemusungan/Kepala Dea Batulayar berdasarkan hasil musyawarah nama Batulayar diambil dari nama tempat yang amat terkenal yaitu Makam Batulayar yang sampai saat ini banyak dikunjungi oleh masyarakat Pulau Lombok pada khususnya dan Masyarakat Nusa Tenggara Barat pada umumnya.</p>\r\n<p style=\"text-align: justify;\">Pada tahun 2001 Desa Batulayar dimekarkan menjadi 2 (dua) yaitu Desa Batulayar (sebagai Desa Induk) dan Desa Senggigi (sebagai Desa Persiapan) dengan SK.Bupati No : 30 Tahun 2001 tanggal 17 Mei 2001, yang pada waktu itu yang menjadi pejabat Kepala Desa Senggigi ialah <strong>H. ARIF RAHMAN, S.IP</strong>., dengan jumlah dusun sebanyak 3 dusun, yaitu :</p>\r\n<p>1. Dusun Senggigi</p>\r\n<p>2. Dusun Kerandangan</p>\r\n<p>3. Dusun Mangsit</p>\r\n<p>Selanjutnya pada tanggal 30 Juli 2003 Pejabat Kepala Desa Senggigi dari <strong>H. ARIF RAHMAN, S.IP</strong> diganti oleh Saudara<strong> ARIFIN</strong> dengan SK. Bupati Lombok Barat No : 409/66/pem/2003. Berhubung Desa Senggigi masih bersifat Desa Persiapan, maka berdasarkan hasil musyawarah desa, tertanggal 15 Desember 2003 , maka pada tanggal 22 Desember 2003 Desa Senggigi mengadakan Pemilihan Kepala Desa devinitif yang pertama kali dipimpin oleh&nbsp;<strong>HAJI JUNAIDI</strong>&nbsp;terpilih&nbsp;dengan SK. Bupati Lombok Barat No :01/01/Pem/2004 tertanggal 2 Januari 2004&nbsp;sampai pada tahun 2008.&nbsp;</p>\r\n<p style=\"text-align: justify;\">Selanjutnya pada tahun 2008, Desa Senggigi mengadakan pemilihan Kepala Desa Senggigi yang kedua dan dimenangkan oleh Bapak <strong>H. MUTAKIR AHMAD</strong>&nbsp;dengan&nbsp;SK. Bupati Lombok Barat No :1320/48/Pem./2008 tertanggal 23 Desember 2008, Periode 2008-2014. &nbsp;Kemudian Kepala desa terpilih Periode 2015 s/d 2021&nbsp;adalah <strong>MUHAMMAD ILHAM</strong>&nbsp;dengan SK. Bupati Lombok Barat No : 160/04/BPMPD/15 tanggal 27 Januari 2015 kini baru menjabat 2 (dua) bulan.</p>\r\n<p style=\"text-align: justify;\">Demikian selanyang pandang atau sejarah singkat Desa Senggigi yang dapat kami sampaikan kepada para pegiat Medsos, semoga dapat bermanfaat untuk kita semua, terima kasih.</p>\r\n<p style=\"text-align: justify;\" align=\"center\">&nbsp;</p>\r\n<p style=\"text-align: justify;\" align=\"center\">&nbsp;</p>', 1, '2016-08-26 08:38:09', 999, 1, 'Sejarah Desa', 3, '1472229325490125_20121123041539.jpg', '1472197089', '1472197089', '', '', 1, 'sejarah-desa', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `captcha_codes`
--

CREATE TABLE `captcha_codes` (
  `id` varchar(40) NOT NULL,
  `namespace` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL,
  `code_display` varchar(32) NOT NULL,
  `created` int(11) NOT NULL,
  `audio_data` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `captcha_codes`
--

INSERT INTO `captcha_codes` (`id`, `namespace`, `code`, `code_display`, `created`, `audio_data`) VALUES
('10.0.2.2', 'default', '2ypo6p', '2yPo6P', 1527544062, NULL),
('192.168.33.1', 'default', 'uzn728', 'uzN728', 1583017284, NULL),
('::1', 'default', 'msjxmh', 'msJXMh', 1591453501, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `config`
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
  `logo` varchar(100) DEFAULT NULL,
  `lat` varchar(20) DEFAULT NULL,
  `lng` varchar(20) DEFAULT NULL,
  `zoom` tinyint(4) DEFAULT NULL,
  `map_tipe` varchar(20) DEFAULT NULL,
  `path` text DEFAULT NULL,
  `alamat_kantor` varchar(200) DEFAULT NULL,
  `email_desa` varchar(50) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `kantor_desa` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `config`
--

INSERT INTO `config` (`id`, `nama_desa`, `kode_desa`, `nama_kepala_desa`, `nip_kepala_desa`, `kode_pos`, `nama_kecamatan`, `kode_kecamatan`, `nama_kepala_camat`, `nip_kepala_camat`, `nama_kabupaten`, `kode_kabupaten`, `nama_propinsi`, `kode_propinsi`, `logo`, `lat`, `lng`, `zoom`, `map_tipe`, `path`, `alamat_kantor`, `email_desa`, `telepon`, `website`, `kantor_desa`) VALUES
(1, 'Senggig1 ', '05', 'Muhammad Ilham ', '--', '83355', 'Batulay4r ', '14', 'Bambang Budi Sanyoto, S. H', '-', 'Lombok Bar4t ', '01', 'Nusa Tenggara Barat', '52', 'opensid_logo__sid__bXziTU1.png', '-8.483832804795249', '116.08523368835449', 14, 'HYBRID', '[[[-8.470247273601585,116.03699684143068],[-8.470993084228864,116.03974342346193],[-8.471775371367853,116.04249000549318],[-8.474831548688417,116.04557991027833],[-8.47754813036,116.04334831237793],[-8.478736628804842,116.0522747039795],[-8.48688623339785,116.04712486267091],[-8.492319207044495,116.04626655578615],[-8.492319207044495,116.04866981506349],[-8.490281850938663,116.05433464050294],[-8.499110315926593,116.06446266174318],[-8.507429260374638,116.06068611145021],[-8.509466525358253,116.05605125427248],[-8.501656950751967,116.04969978332521],[-8.501656950751967,116.046781539917],[-8.503694246430312,116.04454994201662],[-8.496820982890759,116.0453224182129],[-8.494953428786745,116.03931427001955],[-8.48986005320605,116.0365676879883],[-8.48493639256516,116.03364944458009],[-8.47975533883251,116.03768348693849]]]', 'Jl. Raya Senggigi Km 10 Kerandangan ', '', '', '', 'opensid_kantor__sid__w4i7MpO.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `covid19_pantau`
--

CREATE TABLE `covid19_pantau` (
  `id` int(11) NOT NULL,
  `id_pemudik` int(11) DEFAULT NULL,
  `tanggal_jam` datetime DEFAULT NULL,
  `suhu_tubuh` decimal(4,2) DEFAULT NULL,
  `batuk` varchar(20) DEFAULT NULL,
  `flu` varchar(20) DEFAULT NULL,
  `sesak_nafas` varchar(20) DEFAULT NULL,
  `keluhan_lain` varchar(255) DEFAULT NULL,
  `status_covid` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `covid19_pemudik`
--

CREATE TABLE `covid19_pemudik` (
  `id` int(11) NOT NULL,
  `id_terdata` int(11) DEFAULT NULL,
  `tanggal_datang` date DEFAULT NULL,
  `asal_mudik` varchar(255) DEFAULT NULL,
  `durasi_mudik` varchar(50) DEFAULT NULL,
  `tujuan_mudik` varchar(255) DEFAULT NULL,
  `keluhan_kesehatan` varchar(255) DEFAULT NULL,
  `status_covid` varchar(50) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `is_wajib_pantau` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `daftar_anggota_grup`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `daftar_anggota_grup` (
`id_grup_kontak` int(11)
,`id_grup` int(11)
,`nama_grup` varchar(30)
,`id_kontak` int(11)
,`nama` varchar(100)
,`no_hp` varchar(15)
,`sex` varchar(9)
,`alamat_sekarang` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `daftar_grup`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `daftar_grup` (
`id_grup` int(11)
,`nama_grup` varchar(30)
,`jumlah_anggota` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `daftar_kontak`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `daftar_kontak` (
`id_kontak` int(11)
,`id_pend` int(11)
,`nama` varchar(100)
,`no_hp` varchar(15)
,`sex` varchar(9)
,`alamat_sekarang` varchar(200)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_persil`
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
-- Struktur dari tabel `data_persil_jenis`
--

CREATE TABLE `data_persil_jenis` (
  `id` int(11) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `ndesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_persil_peruntukan`
--

CREATE TABLE `data_persil_peruntukan` (
  `id` int(11) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `ndesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_log_penduduk`
--

CREATE TABLE `detail_log_penduduk` (
  `id` int(10) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `disposisi_surat_masuk`
--

CREATE TABLE `disposisi_surat_masuk` (
  `id_disposisi` int(11) NOT NULL,
  `id_surat_masuk` int(11) NOT NULL,
  `id_desa_pamong` int(11) DEFAULT NULL,
  `disposisi_ke` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen`
--

CREATE TABLE `dokumen` (
  `id` int(11) NOT NULL,
  `satuan` varchar(200) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT 1,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_pend` int(11) NOT NULL DEFAULT 0,
  `kategori` tinyint(3) NOT NULL DEFAULT 1,
  `attr` text NOT NULL,
  `tahun` int(4) DEFAULT NULL,
  `kategori_info_publik` tinyint(4) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `id_syarat` int(11) DEFAULT NULL,
  `id_parent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `dokumen`
--

INSERT INTO `dokumen` (`id`, `satuan`, `nama`, `enabled`, `tgl_upload`, `id_pend`, `kategori`, `attr`, `tahun`, `kategori_info_publik`, `updated_at`, `deleted`, `id_syarat`, `id_parent`) VALUES
(2, 'SK+TIM+Penyusun+RPJMDes+Tahun+2017_uwdc6N_grafik-statistik-ada-jumlah.png', 'SK TIM Penyusun RPJMDes Tahun 2017', 1, '2018-05-27 23:49:28', 0, 2, '{\"uraian\":\"SK TIM Penyusun RPJMDes Tahun 2017\",\"no_kep_kades\":\"1\",\"tgl_kep_kades\":\"13-01-2017\",\"no_lapor\":\"1\",\"tgl_lapor\":\"13-01-2017\",\"keterangan\":\"Sudah Terbit\"}', 2017, 3, '2019-11-30 15:01:57', 0, NULL, NULL),
(3, 'SK+Pengangkatan+RT+dan+Pemberentian+RT+Baru_OzjhwE_surat-kk-peraturan.jpg', 'SK Pengangkatan RT dan Pemberentian RT Baru', 1, '2018-05-27 23:51:53', 0, 2, '{\"uraian\":\"SK Pengangkatan RT dan Pemberentian RT Baru\",\"no_kep_kades\":\"2\",\"tgl_kep_kades\":\"14-01-2017\",\"no_lapor\":\"2\",\"tgl_lapor\":\"14-01-2017\",\"keterangan\":\"Sudah Terbit\"}', 2017, 3, '2019-11-30 15:01:57', 0, NULL, NULL),
(4, 'Perdes+SPJ+Tentang+Keuang+Desa+Tahun+2016_cXJUfP_user-setting-pengaturan.png', 'Perdes SPJ Tentang Keuang Desa Tahun 2016', 1, '2018-05-27 23:57:37', 0, 3, '{\"uraian\":\"Perdes SPJ Tentang Keuang Desa Tahun 2016\",\"jenis_peraturan\":\"Perdes SPJ Tahun 2016\",\"no_ditetapkan\":\"1\",\"tgl_ditetapkan\":\"09-01-2016\",\"tgl_kesepakatan\":\"05-01-2016\",\"no_lapor\":\"1\",\"tgl_lapor\":\"05-01-2016\",\"no_lembaran_desa\":\"1\",\"tgl_lembaran_desa\":\"05-01-2017\",\"no_berita_desa\":\"1\",\"tgl_berita_desa\":\"05-01-2017\",\"keterangan\":\"Sudah Terbit\"}', 2016, 3, '2019-11-30 15:01:57', 0, NULL, NULL),
(5, 'RPJMDes+Miau+Merah+Tahun+2016+s%2Fd+2022_fMaZGt_cetak-log-penduduk.png', 'RPJMDes Miau Merah Tahun 2016 s/d 2022', 1, '2018-05-28 00:09:56', 0, 3, '{\"uraian\":\"Rencana Pembangunan Jangka Menengah Desa\",\"jenis_peraturan\":\"RPJMDes\",\"no_ditetapkan\":\"2\",\"tgl_ditetapkan\":\"13-01-2017\",\"tgl_kesepakatan\":\"13-01-2017\",\"no_lapor\":\"2\",\"tgl_lapor\":\"13-01-2017\",\"no_lembaran_desa\":\"2\",\"tgl_lembaran_desa\":\"14-01-2017\",\"no_berita_desa\":\"2\",\"tgl_berita_desa\":\"14-01-2017\",\"keterangan\":\"Sudah Terbit\"}', 2017, 3, '2019-11-30 15:01:57', 0, NULL, NULL),
(6, 'formulir-pengajuan-keberatan-informasi-od5ryq-ammar-alifian-fahdan-1-jpg.jpg', 'Formulir Pengajuan Keberatan Informasi', 1, '2019-10-31 15:20:48', 0, 1, '[]', 2019, 1, '2019-11-30 15:01:57', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `dokumen_hidup`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `dokumen_hidup` (
`id` int(11)
,`satuan` varchar(200)
,`nama` varchar(50)
,`enabled` int(2)
,`tgl_upload` timestamp
,`id_pend` int(11)
,`kategori` tinyint(3)
,`attr` text
,`tahun` int(4)
,`kategori_info_publik` tinyint(4)
,`updated_at` timestamp
,`deleted` tinyint(1)
,`id_syarat` int(11)
,`id_parent` int(11)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `gambar_gallery`
--

CREATE TABLE `gambar_gallery` (
  `id` int(11) NOT NULL,
  `parrent` int(4) DEFAULT 0,
  `gambar` varchar(200) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT 1,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipe` int(4) DEFAULT 0,
  `slider` tinyint(1) DEFAULT NULL,
  `urut` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `gambar_gallery`
--

INSERT INTO `gambar_gallery` (`id`, `parrent`, `gambar`, `nama`, `enabled`, `tgl_upload`, `tipe`, `slider`, `urut`) VALUES
(28, 0, 'galeri-1-1.jpg', 'Karnaval Hari Kemerdekaan ', 1, '2016-08-26 07:53:51', 0, NULL, NULL),
(29, 0, '', 'Panorama Wisata ', 1, '2016-08-26 07:55:31', 0, NULL, NULL),
(30, 28, 'IMG-20160823-WA0116.jpg', 'Karnaval baju adat', 1, '2016-08-26 07:57:10', 2, NULL, NULL),
(31, 28, 'galeri-1-2.jpeg', 'Kemeriahan Karnaval', 2, '2016-08-26 07:58:16', 2, NULL, NULL),
(32, 29, 'galeri-2-2.jpeg', 'Pantai indah', 1, '2016-09-01 19:14:06', 2, NULL, NULL),
(33, 29, 'galeri-2-3.jpeg', 'Kolam renang impian', 1, '2016-09-01 19:14:28', 2, NULL, NULL),
(34, 0, '', 'Kegiatan Kantor Desa', 2, '2016-09-01 23:24:59', 0, NULL, NULL),
(35, 28, '', 'Tarian adat', 1, '2016-09-02 00:32:55', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `garis`
--

CREATE TABLE `garis` (
  `id` int(4) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `path` text DEFAULT NULL,
  `enabled` int(11) NOT NULL DEFAULT 1,
  `ref_line` int(9) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `desk` text DEFAULT NULL,
  `id_cluster` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `garis`
--

INSERT INTO `garis` (`id`, `nama`, `path`, `enabled`, `ref_line`, `foto`, `desk`, `id_cluster`) VALUES
(1, 'Jalan 1', '', 1, 1, '', '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `gis_simbol`
--

CREATE TABLE `gis_simbol` (
  `simbol` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `gis_simbol`
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
-- Struktur dari tabel `inbox`
--

CREATE TABLE `inbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ReceivingDateTime` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Struktur dari tabel `inventaris_asset`
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventaris_gedung`
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventaris_jalan`
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventaris_kontruksi`
--

CREATE TABLE `inventaris_kontruksi` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kondisi_bangunan` varchar(255) NOT NULL,
  `kontruksi_bertingkat` varchar(255) NOT NULL,
  `kontruksi_beton` tinyint(1) DEFAULT 0,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventaris_peralatan`
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventaris_tanah`
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(5) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `tipe` int(4) NOT NULL DEFAULT 1,
  `urut` tinyint(4) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `parrent` tinyint(4) NOT NULL DEFAULT 0,
  `slug` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `kategori`, `tipe`, `urut`, `enabled`, `parrent`, `slug`) VALUES
(1, 'Berita Desa', 1, 1, 1, 0, 'berita-desa'),
(2, 'Produk Desa', 1, 3, 2, 0, 'produk-desa'),
(4, 'Program Kerja', 2, 2, 1, 0, 'program-kerja'),
(5, 'Peraturan Desa', 2, 5, 1, 0, 'peraturan-desa'),
(6, 'Laporan Desa', 2, 6, 2, 0, 'laporan-desa'),
(8, 'Panduan Layanan Desa', 2, 3, 2, 0, 'panduan-layanan-desa'),
(17, 'Peraturan Kebersihan Desa', 1, 0, 2, 5, 'peraturan-kebersihan-desa'),
(20, 'Berita Lokal', 1, 0, 2, 1, 'berita-lokal'),
(21, 'Berita Kriminal', 1, 0, 2, 1, 'berita-kriminal');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelompok`
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
-- Struktur dari tabel `kelompok_anggota`
--

CREATE TABLE `kelompok_anggota` (
  `id` int(11) NOT NULL,
  `id_kelompok` int(11) NOT NULL,
  `id_penduduk` int(11) NOT NULL,
  `no_anggota` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelompok_master`
--

CREATE TABLE `kelompok_master` (
  `id` int(11) NOT NULL,
  `kelompok` varchar(50) NOT NULL,
  `deskripsi` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `kelompok_master`
--

INSERT INTO `kelompok_master` (`id`, `kelompok`, `deskripsi`) VALUES
(1, 'Kelompok Ternak', '<p>Kelompok yang memelihara ternak</p>');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `keluarga_aktif`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `keluarga_aktif` (
`id` int(10)
,`no_kk` varchar(160)
,`nik_kepala` varchar(200)
,`tgl_daftar` timestamp
,`kelas_sosial` int(4)
,`tgl_cetak_kk` datetime
,`alamat` varchar(200)
,`id_cluster` int(11)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_master`
--

CREATE TABLE `keuangan_master` (
  `id` int(11) NOT NULL,
  `versi_database` varchar(50) NOT NULL,
  `tahun_anggaran` varchar(250) NOT NULL,
  `aktif` int(2) NOT NULL DEFAULT 1,
  `tanggal_impor` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_bank_desa`
--

CREATE TABLE `keuangan_ref_bank_desa` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(50) NOT NULL,
  `Kd_Desa` varchar(50) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `NoRek_Bank` varchar(100) NOT NULL,
  `Nama_Bank` varchar(250) NOT NULL,
  `Kantor_Cabang` varchar(100) DEFAULT NULL,
  `Nama_Pemilik` varchar(100) DEFAULT NULL,
  `Alamat_Pemilik` varchar(100) DEFAULT NULL,
  `No_Identitas` varchar(20) DEFAULT NULL,
  `No_Telepon` varchar(20) DEFAULT NULL,
  `ID_Bank` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_bel_operasional`
--

CREATE TABLE `keuangan_ref_bel_operasional` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `ID_Keg` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_bidang`
--

CREATE TABLE `keuangan_ref_bidang` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Bid` varchar(50) NOT NULL,
  `Nama_Bidang` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_bunga`
--

CREATE TABLE `keuangan_ref_bunga` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Bunga` varchar(50) NOT NULL,
  `Kd_Admin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_desa`
--

CREATE TABLE `keuangan_ref_desa` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Kec` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Nama_Desa` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_kecamatan`
--

CREATE TABLE `keuangan_ref_kecamatan` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Kec` varchar(100) NOT NULL,
  `Nama_Kecamatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_kegiatan`
--

CREATE TABLE `keuangan_ref_kegiatan` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Bid` varchar(100) DEFAULT NULL,
  `ID_Keg` varchar(100) NOT NULL,
  `Nama_Kegiatan` varchar(250) NOT NULL,
  `Jns_Kegiatan` tinyint(5) DEFAULT NULL,
  `Kd_Sub` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_korolari`
--

CREATE TABLE `keuangan_ref_korolari` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Kd_RekDB` varchar(100) NOT NULL,
  `Kd_RekKD` varchar(250) NOT NULL,
  `Jenis` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_neraca_close`
--

CREATE TABLE `keuangan_ref_neraca_close` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Kelompok` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_perangkat`
--

CREATE TABLE `keuangan_ref_perangkat` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kode` varchar(100) NOT NULL,
  `Nama_Perangkat` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_potongan`
--

CREATE TABLE `keuangan_ref_potongan` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Kd_Potongan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_rek1`
--

CREATE TABLE `keuangan_ref_rek1` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Akun` varchar(100) NOT NULL,
  `Nama_Akun` varchar(100) NOT NULL,
  `NoLap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_rek2`
--

CREATE TABLE `keuangan_ref_rek2` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Akun` varchar(100) NOT NULL,
  `Kelompok` varchar(100) NOT NULL,
  `Nama_Kelompok` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_rek3`
--

CREATE TABLE `keuangan_ref_rek3` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kelompok` varchar(100) NOT NULL,
  `Jenis` varchar(100) NOT NULL,
  `Nama_Jenis` varchar(100) NOT NULL,
  `Formula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_rek4`
--

CREATE TABLE `keuangan_ref_rek4` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Jenis` varchar(100) NOT NULL,
  `Obyek` varchar(100) NOT NULL,
  `Nama_Obyek` varchar(100) NOT NULL,
  `Peraturan` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_sbu`
--

CREATE TABLE `keuangan_ref_sbu` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Kode_SBU` varchar(100) NOT NULL,
  `NoUrut_SBU` varchar(100) NOT NULL,
  `Nama_SBU` varchar(100) NOT NULL,
  `Nilai` varchar(100) NOT NULL,
  `Satuan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ref_sumber`
--

CREATE TABLE `keuangan_ref_sumber` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kode` varchar(100) NOT NULL,
  `Nama_Sumber` varchar(100) NOT NULL,
  `Urut` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_anggaran`
--

CREATE TABLE `keuangan_ta_anggaran` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `KdPosting` varchar(100) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `KURincianSD` varchar(100) NOT NULL,
  `KD_Rincian` varchar(100) NOT NULL,
  `RincianSD` varchar(100) NOT NULL,
  `Anggaran` varchar(100) NOT NULL,
  `AnggaranPAK` varchar(100) NOT NULL,
  `AnggaranStlhPAK` varchar(100) NOT NULL,
  `Belanja` varchar(100) NOT NULL,
  `Kd_keg` varchar(100) NOT NULL,
  `SumberDana` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `TglPosting` varchar(100) NOT NULL,
  `Kd_SubRinci` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_anggaran_log`
--

CREATE TABLE `keuangan_ta_anggaran_log` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `KdPosting` varchar(100) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_Perdes` varchar(100) NOT NULL,
  `TglPosting` varchar(100) NOT NULL,
  `UserID` varchar(50) NOT NULL,
  `Kunci` varchar(100) NOT NULL,
  `No_Perkades` varchar(100) DEFAULT NULL,
  `Petugas` varchar(80) DEFAULT NULL,
  `Tanggal` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_anggaran_rinci`
--

CREATE TABLE `keuangan_ta_anggaran_rinci` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `KdPosting` varchar(100) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Kd_SubRinci` varchar(100) NOT NULL,
  `No_Urut` varchar(100) NOT NULL,
  `Uraian` varchar(100) NOT NULL,
  `SumberDana` varchar(100) NOT NULL,
  `JmlSatuan` varchar(100) NOT NULL,
  `HrgSatuan` varchar(100) NOT NULL,
  `Satuan` varchar(100) NOT NULL,
  `Anggaran` varchar(100) NOT NULL,
  `JmlSatuanPAK` varchar(100) NOT NULL,
  `HrgSatuanPAK` varchar(100) NOT NULL,
  `AnggaranStlhPAK` varchar(100) NOT NULL,
  `AnggaranPAK` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_bidang`
--

CREATE TABLE `keuangan_ta_bidang` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Bid` varchar(100) NOT NULL,
  `Nama_Bidang` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_desa`
--

CREATE TABLE `keuangan_ta_desa` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Nm_Kades` varchar(100) NOT NULL,
  `Jbt_Kades` varchar(100) NOT NULL,
  `Nm_Sekdes` varchar(100) NOT NULL,
  `NIP_Sekdes` varchar(100) NOT NULL,
  `Jbt_Sekdes` varchar(100) NOT NULL,
  `Nm_Kaur_Keu` varchar(100) NOT NULL,
  `Jbt_Kaur_Keu` varchar(100) NOT NULL,
  `Nm_Bendahara` varchar(100) NOT NULL,
  `Jbt_Bendahara` varchar(100) NOT NULL,
  `No_Perdes` varchar(100) NOT NULL,
  `Tgl_Perdes` varchar(100) NOT NULL,
  `No_Perdes_PB` varchar(100) NOT NULL,
  `Tgl_Perdes_PB` varchar(100) NOT NULL,
  `No_Perdes_PJ` varchar(100) NOT NULL,
  `Tgl_Perdes_PJ` varchar(100) NOT NULL,
  `Alamat` varchar(250) NOT NULL,
  `Ibukota` varchar(100) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `NPWP` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_jurnal_umum`
--

CREATE TABLE `keuangan_ta_jurnal_umum` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `KdBuku` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Tanggal` varchar(100) NOT NULL,
  `JnsBukti` varchar(100) NOT NULL,
  `NoBukti` varchar(100) NOT NULL,
  `Keterangan` varchar(100) NOT NULL,
  `DK` varchar(100) NOT NULL,
  `Debet` varchar(100) NOT NULL,
  `Kredit` varchar(100) NOT NULL,
  `Jenis` varchar(100) NOT NULL,
  `Posted` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_jurnal_umum_rinci`
--

CREATE TABLE `keuangan_ta_jurnal_umum_rinci` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `NoBukti` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `RincianSD` varchar(100) NOT NULL,
  `NoID` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Akun` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Sumberdana` varchar(100) NOT NULL,
  `DK` varchar(100) NOT NULL,
  `Debet` varchar(100) NOT NULL,
  `Kredit` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_kegiatan`
--

CREATE TABLE `keuangan_ta_kegiatan` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Bid` varchar(100) DEFAULT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `ID_Keg` varchar(100) NOT NULL,
  `Nama_Kegiatan` varchar(100) NOT NULL,
  `Pagu` varchar(100) NOT NULL,
  `Pagu_PAK` varchar(100) NOT NULL,
  `Nm_PPTKD` varchar(100) NOT NULL,
  `NIP_PPTKD` varchar(100) NOT NULL,
  `Lokasi` varchar(100) NOT NULL,
  `Waktu` varchar(100) NOT NULL,
  `Keluaran` varchar(100) NOT NULL,
  `Sumberdana` varchar(100) NOT NULL,
  `Jbt_PPTKD` varchar(100) DEFAULT NULL,
  `Kd_Sub` varchar(30) DEFAULT NULL,
  `Nilai` bigint(20) UNSIGNED DEFAULT NULL,
  `NilaiPAK` bigint(20) UNSIGNED DEFAULT NULL,
  `Satuan` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_mutasi`
--

CREATE TABLE `keuangan_ta_mutasi` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `Tgl_Bukti` varchar(100) NOT NULL,
  `Keterangan` varchar(200) DEFAULT NULL,
  `Kd_Bank` varchar(100) DEFAULT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Sumberdana` varchar(100) NOT NULL,
  `Kd_Mutasi` varchar(100) NOT NULL,
  `Nilai` varchar(100) NOT NULL,
  `ID_Bank` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_pajak`
--

CREATE TABLE `keuangan_ta_pajak` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_SSP` varchar(100) NOT NULL,
  `Tgl_SSP` varchar(100) NOT NULL,
  `Keterangan` varchar(250) DEFAULT NULL,
  `Nama_WP` varchar(100) NOT NULL,
  `Alamat_WP` varchar(100) NOT NULL,
  `NPWP` varchar(100) NOT NULL,
  `Kd_MAP` varchar(100) NOT NULL,
  `Nm_Penyetor` varchar(100) NOT NULL,
  `Jn_Transaksi` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Jumlah` varchar(100) NOT NULL,
  `KdBayar` varchar(100) NOT NULL,
  `ID_Bank` varchar(10) DEFAULT NULL,
  `NTPN` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_pajak_rinci`
--

CREATE TABLE `keuangan_ta_pajak_rinci` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_SSP` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Nilai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_pemda`
--

CREATE TABLE `keuangan_ta_pemda` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Prov` varchar(100) NOT NULL,
  `Kd_Kab` varchar(100) NOT NULL,
  `Nama_Pemda` varchar(100) NOT NULL,
  `Nama_Provinsi` varchar(100) NOT NULL,
  `Ibukota` varchar(100) NOT NULL,
  `Alamat` varchar(100) NOT NULL,
  `Nm_Bupati` varchar(100) NOT NULL,
  `Jbt_Bupati` varchar(100) NOT NULL,
  `Logo` mediumblob DEFAULT NULL,
  `C_Kode` varchar(100) NOT NULL,
  `C_Pemda` varchar(100) NOT NULL,
  `C_Data` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_pencairan`
--

CREATE TABLE `keuangan_ta_pencairan` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `No_Cek` varchar(100) NOT NULL,
  `No_SPP` varchar(100) NOT NULL,
  `Tgl_Cek` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Keterangan` varchar(250) DEFAULT NULL,
  `Jumlah` varchar(100) NOT NULL,
  `Potongan` varchar(100) NOT NULL,
  `KdBayar` varchar(100) NOT NULL,
  `ID_Bank` varchar(10) DEFAULT NULL,
  `Kunci` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_perangkat`
--

CREATE TABLE `keuangan_ta_perangkat` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Jabatan` varchar(100) NOT NULL,
  `No_ID` varchar(100) NOT NULL,
  `Nama_Perangkat` varchar(100) NOT NULL,
  `Alamat_Perangkat` varchar(100) NOT NULL,
  `Nomor_HP` varchar(100) NOT NULL,
  `Rek_Bank` varchar(100) NOT NULL,
  `Nama_Bank` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rab`
--

CREATE TABLE `keuangan_ta_rab` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Anggaran` varchar(100) NOT NULL,
  `AnggaranPAK` varchar(100) NOT NULL,
  `AnggaranStlhPAK` varchar(100) NOT NULL,
  `Kd_SubRinci` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rab_rinci`
--

CREATE TABLE `keuangan_ta_rab_rinci` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Kd_SubRinci` varchar(100) NOT NULL,
  `No_Urut` varchar(100) NOT NULL,
  `SumberDana` varchar(100) NOT NULL,
  `Uraian` varchar(100) NOT NULL,
  `Satuan` varchar(100) NOT NULL,
  `JmlSatuan` varchar(100) NOT NULL,
  `HrgSatuan` varchar(100) NOT NULL,
  `Anggaran` varchar(100) NOT NULL,
  `JmlSatuanPAK` varchar(100) NOT NULL,
  `HrgSatuanPAK` varchar(100) NOT NULL,
  `AnggaranStlhPAK` varchar(100) NOT NULL,
  `AnggaranPAK` varchar(100) NOT NULL,
  `Kode_SBU` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rab_sub`
--

CREATE TABLE `keuangan_ta_rab_sub` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Kd_SubRinci` varchar(100) NOT NULL,
  `Nama_SubRinci` varchar(100) NOT NULL,
  `Anggaran` varchar(100) NOT NULL,
  `AnggaranPAK` varchar(100) NOT NULL,
  `AnggaranStlhPAK` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rpjm_bidang`
--

CREATE TABLE `keuangan_ta_rpjm_bidang` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Bid` varchar(100) NOT NULL,
  `Nama_Bidang` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rpjm_kegiatan`
--

CREATE TABLE `keuangan_ta_rpjm_kegiatan` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Bid` varchar(100) DEFAULT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `ID_Keg` varchar(100) NOT NULL,
  `Nama_Kegiatan` varchar(100) NOT NULL,
  `Lokasi` varchar(100) NOT NULL,
  `Keluaran` varchar(100) NOT NULL,
  `Kd_Sas` varchar(100) NOT NULL,
  `Sasaran` varchar(100) NOT NULL,
  `Tahun1` varchar(100) NOT NULL,
  `Tahun2` varchar(100) NOT NULL,
  `Tahun3` varchar(100) NOT NULL,
  `Tahun4` varchar(100) NOT NULL,
  `Tahun5` varchar(100) NOT NULL,
  `Tahun6` varchar(100) NOT NULL,
  `Swakelola` varchar(100) NOT NULL,
  `Kerjasama` varchar(100) NOT NULL,
  `Pihak_Ketiga` varchar(100) NOT NULL,
  `Sumberdana` varchar(100) NOT NULL,
  `Kd_Sub` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rpjm_misi`
--

CREATE TABLE `keuangan_ta_rpjm_misi` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `ID_Misi` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `ID_Visi` varchar(100) NOT NULL,
  `No_Misi` varchar(100) NOT NULL,
  `Uraian_Misi` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rpjm_pagu_indikatif`
--

CREATE TABLE `keuangan_ta_rpjm_pagu_indikatif` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Sumber` varchar(100) NOT NULL,
  `Tahun1` varchar(100) NOT NULL,
  `Tahun2` varchar(100) NOT NULL,
  `Tahun3` varchar(100) NOT NULL,
  `Tahun4` varchar(100) NOT NULL,
  `Tahun5` varchar(100) NOT NULL,
  `Tahun6` varchar(100) NOT NULL,
  `Pola` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rpjm_pagu_tahunan`
--

CREATE TABLE `keuangan_ta_rpjm_pagu_tahunan` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Tahun` varchar(100) NOT NULL,
  `Kd_Sumber` varchar(100) NOT NULL,
  `Biaya` varchar(100) NOT NULL,
  `Volume` varchar(100) NOT NULL,
  `Satuan` varchar(100) NOT NULL,
  `Lokasi_Spesifik` varchar(100) NOT NULL,
  `Jml_Sas_Pria` varchar(100) NOT NULL,
  `Jml_Sas_Wanita` varchar(100) NOT NULL,
  `Jml_Sas_ARTM` varchar(100) NOT NULL,
  `Waktu` varchar(100) NOT NULL,
  `Mulai` varchar(100) NOT NULL,
  `Selesai` varchar(100) NOT NULL,
  `Pola_Kegiatan` varchar(100) NOT NULL,
  `Pelaksana` varchar(100) NOT NULL,
  `No_ID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rpjm_sasaran`
--

CREATE TABLE `keuangan_ta_rpjm_sasaran` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `ID_Sasaran` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `ID_Tujuan` varchar(100) NOT NULL,
  `No_Sasaran` varchar(100) NOT NULL,
  `Uraian_Sasaran` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rpjm_tujuan`
--

CREATE TABLE `keuangan_ta_rpjm_tujuan` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `ID_Tujuan` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `ID_Misi` varchar(100) NOT NULL,
  `No_Tujuan` varchar(100) NOT NULL,
  `Uraian_Tujuan` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_rpjm_visi`
--

CREATE TABLE `keuangan_ta_rpjm_visi` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `ID_Visi` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_Visi` varchar(100) NOT NULL,
  `Uraian_Visi` varchar(250) DEFAULT NULL,
  `TahunA` varchar(100) NOT NULL,
  `TahunN` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_saldo_awal`
--

CREATE TABLE `keuangan_ta_saldo_awal` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Jenis` varchar(100) NOT NULL,
  `Anggaran` varchar(100) NOT NULL,
  `Debet` varchar(100) NOT NULL,
  `Kredit` varchar(100) NOT NULL,
  `Tgl_Bukti` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_spj`
--

CREATE TABLE `keuangan_ta_spj` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `No_SPJ` varchar(100) NOT NULL,
  `Tgl_SPJ` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_SPP` varchar(100) NOT NULL,
  `Keterangan` varchar(100) NOT NULL,
  `Jumlah` varchar(100) NOT NULL,
  `Potongan` varchar(100) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Kunci` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_spjpot`
--

CREATE TABLE `keuangan_ta_spjpot` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_SPJ` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Nilai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_spj_bukti`
--

CREATE TABLE `keuangan_ta_spj_bukti` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `No_SPJ` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `Tgl_Bukti` varchar(100) NOT NULL,
  `Sumberdana` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Nm_Penerima` varchar(100) NOT NULL,
  `Alamat` varchar(100) NOT NULL,
  `Rek_Bank` varchar(100) NOT NULL,
  `Nm_Bank` varchar(100) NOT NULL,
  `NPWP` varchar(100) NOT NULL,
  `Keterangan` varchar(250) DEFAULT NULL,
  `Nilai` varchar(100) NOT NULL,
  `Kd_SubRinci` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_spj_rinci`
--

CREATE TABLE `keuangan_ta_spj_rinci` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `No_SPJ` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Sumberdana` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_SPP` varchar(100) NOT NULL,
  `JmlCair` varchar(100) NOT NULL,
  `Nilai` varchar(100) NOT NULL,
  `Alamat` varchar(100) DEFAULT NULL,
  `Sisa` varchar(100) NOT NULL,
  `Kd_SubRinci` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_spj_sisa`
--

CREATE TABLE `keuangan_ta_spj_sisa` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `Tgl_Bukti` varchar(100) NOT NULL,
  `No_SPJ` varchar(100) NOT NULL,
  `Tgl_SPJ` varchar(100) NOT NULL,
  `No_SPP` varchar(100) NOT NULL,
  `Tgl_SPP` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Keterangan` varchar(100) NOT NULL,
  `Nilai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_spp`
--

CREATE TABLE `keuangan_ta_spp` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `No_SPP` varchar(100) NOT NULL,
  `Tgl_SPP` varchar(100) NOT NULL,
  `Jn_SPP` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Keterangan` varchar(250) DEFAULT NULL,
  `Jumlah` varchar(100) NOT NULL,
  `Potongan` varchar(100) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `F10` varchar(10) DEFAULT NULL,
  `F11` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_sppbukti`
--

CREATE TABLE `keuangan_ta_sppbukti` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_SPP` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Sumberdana` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `Tgl_Bukti` varchar(100) NOT NULL,
  `Nm_Penerima` varchar(100) NOT NULL,
  `Alamat` varchar(100) NOT NULL,
  `Rek_Bank` varchar(100) NOT NULL,
  `Nm_Bank` varchar(100) NOT NULL,
  `NPWP` varchar(100) NOT NULL,
  `Keterangan` varchar(200) DEFAULT NULL,
  `Nilai` varchar(100) NOT NULL,
  `Kd_SubRinci` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_spppot`
--

CREATE TABLE `keuangan_ta_spppot` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_SPP` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Nilai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_spp_rinci`
--

CREATE TABLE `keuangan_ta_spp_rinci` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_SPP` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Sumberdana` varchar(100) NOT NULL,
  `Nilai` varchar(100) NOT NULL,
  `Kd_SubRinci` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_sts`
--

CREATE TABLE `keuangan_ta_sts` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `Tgl_Bukti` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Uraian` varchar(100) NOT NULL,
  `NoRek_Bank` varchar(100) NOT NULL,
  `Nama_Bank` varchar(100) NOT NULL,
  `Jumlah` varchar(100) NOT NULL,
  `Nm_Bendahara` varchar(100) NOT NULL,
  `Jbt_Bendahara` varchar(100) NOT NULL,
  `ID_Bank` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_sts_rinci`
--

CREATE TABLE `keuangan_ta_sts_rinci` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `No_TBP` varchar(100) NOT NULL,
  `Uraian` varchar(100) NOT NULL,
  `Nilai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_tbp`
--

CREATE TABLE `keuangan_ta_tbp` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `Tgl_Bukti` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Uraian` varchar(250) DEFAULT NULL,
  `Nm_Penyetor` varchar(100) NOT NULL,
  `Alamat_Penyetor` varchar(100) NOT NULL,
  `TTD_Penyetor` varchar(100) NOT NULL,
  `NoRek_Bank` varchar(100) NOT NULL,
  `Nama_Bank` varchar(100) NOT NULL,
  `Jumlah` varchar(100) NOT NULL,
  `Nm_Bendahara` varchar(100) NOT NULL,
  `Jbt_Bendahara` varchar(100) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `KdBayar` varchar(100) NOT NULL,
  `Ref_Bayar` varchar(100) NOT NULL,
  `ID_Bank` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_tbp_rinci`
--

CREATE TABLE `keuangan_ta_tbp_rinci` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `No_Bukti` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `RincianSD` varchar(100) NOT NULL,
  `SumberDana` varchar(100) NOT NULL,
  `nilai` varchar(100) NOT NULL,
  `Kd_SubRinci` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_triwulan`
--

CREATE TABLE `keuangan_ta_triwulan` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `KURincianSD` varchar(100) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Sifat` varchar(100) NOT NULL,
  `SumberDana` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Anggaran` varchar(100) NOT NULL,
  `AnggaranPAK` varchar(100) NOT NULL,
  `Tw1Rinci` varchar(100) DEFAULT NULL,
  `Tw2Rinci` varchar(100) DEFAULT NULL,
  `Tw3Rinci` varchar(100) DEFAULT NULL,
  `Tw4Rinci` varchar(100) DEFAULT NULL,
  `KunciData` varchar(100) NOT NULL,
  `Jan` varchar(100) DEFAULT NULL,
  `Peb` varchar(100) DEFAULT NULL,
  `Mar` varchar(100) DEFAULT NULL,
  `Apr` varchar(100) DEFAULT NULL,
  `Mei` varchar(100) DEFAULT NULL,
  `Jun` varchar(100) DEFAULT NULL,
  `Jul` varchar(100) DEFAULT NULL,
  `Agt` varchar(100) DEFAULT NULL,
  `Sep` varchar(100) DEFAULT NULL,
  `Okt` varchar(100) DEFAULT NULL,
  `Nop` varchar(100) DEFAULT NULL,
  `Des` varchar(100) DEFAULT NULL,
  `Kd_SubRinci` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_ta_triwulan_rinci`
--

CREATE TABLE `keuangan_ta_triwulan_rinci` (
  `id` int(11) NOT NULL,
  `id_keuangan_master` int(11) NOT NULL,
  `KdPosting` varchar(100) NOT NULL,
  `KURincianSD` varchar(100) NOT NULL,
  `Tahun` varchar(100) NOT NULL,
  `Sifat` varchar(100) NOT NULL,
  `SumberDana` varchar(100) NOT NULL,
  `Kd_Desa` varchar(100) NOT NULL,
  `Kd_Keg` varchar(100) NOT NULL,
  `Kd_Rincian` varchar(100) NOT NULL,
  `Anggaran` varchar(100) NOT NULL,
  `AnggaranPAK` varchar(100) NOT NULL,
  `Tw1Rinci` varchar(100) DEFAULT NULL,
  `Tw2Rinci` varchar(100) DEFAULT NULL,
  `Tw3Rinci` varchar(100) DEFAULT NULL,
  `Tw4Rinci` varchar(100) DEFAULT NULL,
  `KunciData` varchar(100) NOT NULL,
  `Jan` varchar(100) DEFAULT NULL,
  `Peb` varchar(100) DEFAULT NULL,
  `Mar` varchar(100) DEFAULT NULL,
  `Apr` varchar(100) DEFAULT NULL,
  `Mei` varchar(100) DEFAULT NULL,
  `Jun` varchar(100) DEFAULT NULL,
  `Jul` varchar(100) DEFAULT NULL,
  `Agt` varchar(100) DEFAULT NULL,
  `Sep` varchar(100) DEFAULT NULL,
  `Okt` varchar(100) DEFAULT NULL,
  `Nop` varchar(100) DEFAULT NULL,
  `Des` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `klasifikasi_analisis_keluarga`
--

CREATE TABLE `klasifikasi_analisis_keluarga` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `jenis` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `klasifikasi_surat`
--

CREATE TABLE `klasifikasi_surat` (
  `id` int(4) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `uraian` mediumtext NOT NULL,
  `enabled` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `klasifikasi_surat`
--

INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES
(1, '000', 'UMUM', '-', 1),
(2, '001', 'Lambang', '-', 1),
(3, '001.1', 'Garuda', '-', 1),
(4, '001.2', 'Bendera Kebangsaan', '-', 1),
(5, '001.3', 'Lagu Kebangsaan', '-', 1),
(6, '001.4', 'Daerah', '-', 1),
(7, '001.31', 'Provinsi', '-', 1),
(8, '001.32', 'Kabupaten/Kota', '-', 1),
(9, '002', 'Tanda Kehormatan/Penghargaan untuk pegawai ', 'lihat 861.1', 1),
(10, '002.1', 'Bintang', '-', 1),
(11, '002.2', 'Satyalencana', '-', 1),
(12, '002.3', 'Samkarya Nugraha', '-', 1),
(13, '002.4', 'Monumen', '-', 1),
(14, '002.5', 'Penghargaan Secara Adat', '-', 1),
(15, '002.6', 'Penghargaan lainnya', '-', 1),
(16, '003', 'Hari Raya/Besar', '-', 1),
(17, '003.1', 'Nasional 17 Agustus, Hari Pahlawan, dan sebagainya', '-', 1),
(18, '003.2', 'Hari Raya Keagamaan', '-', 1),
(19, '003.3', 'Hari Ulang Tahun', '-', 1),
(20, '003.4', 'Hari-hari Besar Internasional', '-', 1),
(21, '004', 'Ucapan', '-', 1),
(22, '004.1', 'Ucapan Terima Kasih', '-', 1),
(23, '004.2', 'Ucapan Selamat', '-', 1),
(24, '004.3', 'Ucapan Belasungkawa', '-', 1),
(25, '004.4', 'Ucapan Lainnya', '-', 1),
(26, '005', 'Undangan', '-', 1),
(27, '006', 'Tanda Jabatan', '-', 1),
(28, '006.1', 'Pamong Praja', '-', 1),
(29, '006.2', 'Tanda Pengenal', '-', 1),
(30, '006.3', 'Pejabat lainnya', '-', 1),
(31, '007', '-', '-', 1),
(32, '008', '-', '-', 1),
(33, '009', '-', '-', 1),
(34, '010', 'URUSAN DALAM ', 'Gedung Kantor/Termasuk Instalasi Prasarana Fisik Pamong', 1),
(35, '011', 'Kantor Dinas', '-', 1),
(36, '012', 'Rumah Dinas', '-', 1),
(37, '012.1', 'Tanah Untuk Rumah Dinas', '-', 1),
(38, '012.2', 'Perabot Rumah Dinas', '-', 1),
(39, '012.3', 'Rumah Dinas Golongan 1', '-', 1),
(40, '012.4', 'Rumah Dinas Golongan 2', '-', 1),
(41, '012.5', 'Rumah Dinas Golongan 3', '-', 1),
(42, '012.6', 'Rumah/Bangunan Lainnya', '-', 1),
(43, '012.7', 'Rumah Pejabat Negara', '-', 1),
(44, '013', 'Mess/Guest House', '-', 1),
(45, '014', 'Rumah Susun/Apartemen', '-', 1),
(46, '015', 'Penerangan Listrik/Jasa Listrik', '-', 1),
(47, '016', 'Telepon/Faximile/Internet', '-', 1),
(48, '017', 'Keamanan/Ketertiban Kantor', '-', 1),
(49, '018', 'Kebersihan Kantor', '-', 1),
(50, '019', 'Protokol', '-', 1),
(51, '019.1', 'Upacara Bendera', '-', 1),
(52, '019.2', 'Tata Tempat', '-', 1),
(53, '019.21', 'Pemasangan Gambar Presiden/Wakil Presiden', '-', 1),
(54, '019.3', 'Audiensi / Menghadap Pimpinan', '-', 1),
(55, '019.4', 'Alamat-Alamat Kantor Pejabat', '-', 1),
(56, '019.5', 'Bandir/Umbul-Umbul/Spanduk', '-', 1),
(57, '020', 'PERALATAN', '-', 1),
(58, '020.1', 'Penawaran', '-', 1),
(59, '021', 'Alat Tulis', '-', 1),
(60, '022', 'Mesin Kantor', '-', 1),
(61, '023', 'Perabot Kantor', '-', 1),
(62, '024', 'Alat Angkutan', '-', 1),
(63, '025', 'Pakaian Dinas', '-', 1),
(64, '026', 'Senjata', '-', 1),
(65, '027', 'Pengadaan', '-', 1),
(66, '028', 'Inventaris', '-', 1),
(67, '029', '-', '-', 1),
(68, '030', 'KEKAYAAN DAERAH', '-', 1),
(69, '031', 'Sumber Daya Alam', '-', 1),
(70, '032', 'Asset Daerah', '-', 1),
(71, '033', '-', '-', 1),
(72, '034', '-', '-', 1),
(73, '035', '-', '-', 1),
(74, '036', '-', '-', 1),
(75, '040', 'PERPUSTAKAAN DOKUMENTASI / KEARSIPAN / SANDI', '-', 1),
(76, '041', 'Perpustakaan', '-', 1),
(77, '041.1', 'Umum', '-', 1),
(78, '041.2', 'Khusus', '-', 1),
(79, '041.3', 'Perguruan Tinggi', '-', 1),
(80, '041.4', 'Sekolah', '-', 1),
(81, '041.5', 'Keliling', '-', 1),
(82, '042', 'Dokumentasi', '-', 1),
(83, '043', '-', '-', 1),
(84, '044', '-', '-', 1),
(85, '045', 'Kearsipan', '-', 1),
(86, '045.1', 'Pola Klasifikasi', '-', 1),
(87, '045.2', 'Penataan Berkas', '-', 1),
(88, '045.3', 'Penyusutan Arsip', '-', 1),
(89, '045.31', 'Jadwal Retensi Arsip', '-', 1),
(90, '045.32', 'Pemindahan Arsip', '-', 1),
(91, '045.33', 'Penilaian Arsip', '-', 1),
(92, '045.34', 'Pemusnahan Arsip', '-', 1),
(93, '045.35', 'Penyerahan Arsip', '-', 1),
(94, '045.36', 'Berita Acara Penyusutan Arsip', '-', 1),
(95, '045.37', 'Daftar Pencarian Arsip', '-', 1),
(96, '045.4', 'Pembinaan Kearsipan', '-', 1),
(97, '045.41', 'Bimbingan Teknis', '-', 1),
(98, '045.5', 'Pemeliharaan /Perawatan Arsip', '-', 1),
(99, '045.6', 'Pengawetan/Fumigasi', '-', 1),
(100, '046', 'Sandi', '-', 1),
(101, '047', 'Website', '-', 1),
(102, '048', 'Pengelolaan Data', '-', 1),
(103, '049', 'Jaringan Komunikasi Data', '-', 1),
(104, '050', 'PERENCANAAN', '-', 1),
(105, '050.1', 'Repelita/8 Sukses', '-', 1),
(106, '050.11', 'Pelita Daerah', '-', 1),
(107, '050.12', 'Bantuan Pembangunan Daerah', '-', 1),
(108, '050.13', 'Bappeda', '-', 1),
(109, '051', 'Proyek Bidang Pemerintahan, ', 'Klasifikasikan Disini : Proyek Prasaran Fisik Pemerintahan, Tambahkan Perincian 100 Pada 051 Contoh: Proyek Kepenjaraan 051.86', 1),
(110, '052', 'Bidang Politik', '-', 1),
(111, '053', 'Bidang Keamanan Dan Ketertiban', 'Tambahkan Perincian 300 Pada 053 Contoh: Proyek Ketataprajaan 053.311 ', 1),
(112, '054', 'Bidang Kesejahteraan Rakyat ', 'Tambahkan Peincian 400 pada 054 Contoh: Proyek Resettlement Desa 054.671', 1),
(113, '055', 'Bidang Perekonomian ', 'Tambahkan Perincian 500 Pada 055 Contoh: Proyek Pasar 055.112', 1),
(114, '056', 'Bidang Pekerjaan Umum ', 'Tambahkan Perincian 600 pada 056 Contoh: Proyek Jembatan 056.3', 1),
(115, '057', 'Bidang Pengawasan', '-', 1),
(116, '058', 'Bidang Kepegawaian', '-', 1),
(117, '059', 'Bidang Keuangan', '-', 1),
(118, '060', 'ORGANISASI / KETATALAKSANAAN', '-', 1),
(119, '060.1', 'Program Kerja', '-', 1),
(120, '061', 'Organisasi Instansi Pemerintah (struktur organisasi)', '-', 1),
(121, '061.1', 'Susunan dan Tata Kerja', '-', 1),
(122, '061.2', 'Tata Tertib Kantor, Jam Kerja di Bulan Puasa', '-', 1),
(123, '062', 'Organisasi Badan Non Pemerintah', '-', 1),
(124, '063', 'Organisasi Badan Internasional', '-', 1),
(125, '064', 'Organisasi Semi Pemerintah, BKS-AKSI', '-', 1),
(126, '065', 'Ketatalaksanaan / Tata Naskah / Sistem', '-', 1),
(127, '066', 'Stempel Dinas', '-', 1),
(128, '067', 'Pelayanan Umum / Pelayanan Publik / Analisis', '-', 1),
(129, '068', 'Komputerisasi / Siskomdagri', '-', 1),
(130, '069', 'Standar Pelayanan Minimal', '-', 1),
(131, '070', 'PENELITIAN', '-', 1),
(132, '071', 'Riset', '-', 1),
(133, '072', 'Survey', '-', 1),
(134, '073', 'Kajian', '-', 1),
(135, '074', 'Kerjasama Penelitian Dengan Perguruan Tinggi', '-', 1),
(136, '075', 'Kementerian Lainnya', '-', 1),
(137, '076', 'Non Kementerian', '-', 1),
(138, '077', 'Provinsi', '-', 1),
(139, '078', 'Kabupaten/Kota', '-', 1),
(140, '079', 'Kecamatan /Desa', '-', 1),
(141, '080', 'KONFERENSI / RAPAT / SEMINAR', '-', 1),
(142, '081', 'Gubernur', '-', 1),
(143, '082', 'Bupati / Walikota', '-', 1),
(144, '083', 'Komponen, Eselon Lainnya', '-', 1),
(145, '084', 'Instansi Lainnya', '-', 1),
(146, '085', 'Internasional Di Dalam Negeri', '-', 1),
(147, '086', 'Internasional Di Luar Negeri', '-', 1),
(148, '087', '-', '-', 1),
(149, '088', '-', '-', 1),
(150, '089', '-', '-', 1),
(151, '090', 'PERJALANAN DINAS', '-', 1),
(152, '091', 'Perjalanan Presiden/Wakil Presiden Ke Daerah', '-', 1),
(153, '092', 'Perjalanan Menteri Ke Daerah', '-', 1),
(154, '093', 'Perjalanan Pejabat Tinggi (Pejabat Eselon 1)', '-', 1),
(155, '094', 'Perjalanan Pegawai Termasuk Pemanggilan Pegawai', '-', 1),
(156, '095', 'Perjalanan Tamu Asing Ke Daerah', '-', 1),
(157, '096', 'Perjalanan Presiden/Wakil Presiden Ke Luar Negeri', '-', 1),
(158, '097', 'Perjalanan Menteri Ke Luar Negeri', '-', 1),
(159, '098', 'Perjalanan Pejabat Tinggi Ke Luar Negeri', '-', 1),
(160, '099', 'Perjalanan Pegawai ke Luar Negeri', '-', 1),
(161, '100', 'PEMERINTAHAN', 'Meliputi: Tata Praja, Legislatif, Yudikatif, Hubungan luar', 1),
(162, '101', 'negeri', '-', 1),
(163, '102', 'GDN', '-', 1),
(164, '103', '-', '-', 1),
(165, '104', '-', '-', 1),
(166, '105', '-', '-', 1),
(167, '110', 'PEMERINTAHAN PUSAT', '-', 1),
(168, '111', 'Presiden', 'Meliputi: pencalonan, pengangkatan, pelantikan, sumpah, dan serah jabatan', 1),
(169, '111.1', 'Pertanggung jawaban presiden kpd MPR', '-', 1),
(170, '111.2', 'Amanat Presiden/Amanat Kenegaraan/Pidato', '-', 1),
(171, '112', 'Wakil Presiden', 'Meliputi: pencalonan, pengangkatan, pelantikan, sumpah, dan serah jabatan', 1),
(172, '112.1', 'Pertanggung jawaban wakil presiden kepada MPR', '-', 1),
(173, '112.2', 'Amanat Wakil Presiden/Amanat Kenegaraan/Pidato', '-', 1),
(174, '113', 'Susunan Kabinet', '-', 1),
(175, '113.1', 'Reshuffle', '-', 1),
(176, '113.2', 'Penunjukan Menteri ad interim', '-', 1),
(177, '113.3', 'Sidang Kabinet', '-', 1),
(178, '114', 'Kementerian Dalam Negeri', '-', 1),
(179, '114.1', 'Amanat Menteri Dalam Negeri/Sambutan', '-', 1),
(180, '115', 'Kementerian lainnya', '-', 1),
(181, '116', 'Lembaga Tinggi Negara', '-', 1),
(182, '117', 'Lembaga Non Kementerian', '-', 1),
(183, '118', 'Otonomi/Desentralisasi/Dekonsentrasi', '-', 1),
(184, '119', 'Kerjasama Antar Kementerian', '-', 1),
(185, '120', 'PEMERINTAH PROVINSI', '-', 1),
(186, '120.04', 'Laporan daerah', '-', 1),
(187, '120.42', 'Monografi tambahkan kode wilayah', '-', 1),
(188, '120.1', 'Koordinasi', '-', 1),
(189, '120.2', 'Instansi Tingkat Provinsi', '-', 1),
(190, '120.21', 'Dinas Otonomi', '-', 1),
(191, '120.22', 'Instansi Vertikal', '-', 1),
(192, '120.23', 'Kerjasama antar Provinsi/Daerah', '-', 1),
(193, '121', 'Gubernur tambahkan kode wilayah, ', 'Meliputi: Pencalonan, Pengangkatan, Meninggal, Pelantikan, Pemberhentian, Serah Terima Jabatan dan sebagainya.', 1),
(194, '122', 'Wakil Gubernur tambahkan kode wilayah, ', 'Meliputi: Pencalonan, Pengangkatan, Meninggal, Pelantikan, Pemberhentian, Serah Terima Jabatan  dan sebagainya.', 1),
(195, '123', 'Sekretaris Wilayah tambahkan kode wilayah, ', 'Meliputi: Pencalonan, Pengangkatan, Meninggal, Pelantikan, Pemberhentian, Serah Terima Jabatan dan sebagainya.', 1),
(196, '124', 'Pembentukan/Pemekaran Wilayah', '-', 1),
(197, '124.1', 'Pembinaan/Perubahan Nama kepada: Daerah, Kota,Benda, Geografis, Gunung, Sungai, Pulau, Selat, Batas laut, dan sebagainya', '-', 1),
(198, '124.2', 'Pemekaran  Wilayah', '-', 1),
(199, '124.3', 'Forum Koordinasi lainnya', '-', 1),
(200, '125', 'Pembentukan Pemekaran Wilayah', '-', 1),
(201, '125.1', 'Pembinaan/Perubahan Nama Kepada: Daerah, Kota, Benda, Geografis, Gunung, Sungai, Pulau, Selat, Batas Laut, dan sebagainya.', '-', 1),
(202, '125.2', 'Pembentukan Wialayah', '-', 1),
(203, '125.3', 'Pemindahan Ibukota', '-', 1),
(204, '125.4', 'Perubahan batas Wilayah', '-', 1),
(205, '125.5', 'Pemekaran Wialayah', '-', 1),
(206, '126', 'Pembagian Wilayah', '-', 1),
(207, '127', 'Penyerahan Urusan', '-', 1),
(208, '128', 'Swaparaja/Penataan Wilayah/Daerah', '-', 1),
(209, '129', '-', '-', 1),
(210, '130', 'PEMERINTAH KABUPATEN / KOTA', 'Bupati /Walikota, Tambahkan Kode Wilayah, Meliputi: Pencalonan,Pengangkatan, Meninggal, Pelantikan,', 1),
(211, '131', 'Pemberhentian, Serah Terima Jabatan, dsb', 'Sambutan / Pengarahan / Amanat Wakil Bupati /Walikota, Tambahkan Kode Wilayah, Meliputi: Pencalonan, Pengangkatan, Meninggal, Pelantikan,', 1),
(212, '132', 'Pemberhentian, Serah Terima Jabatan, Sekretaris Daerah Kabupaten/Kota, Tambahkan Kode Wilayah, ', 'Meliputi: Pencalonan, Pengangkatan, Meninggal,', 1),
(213, '133', 'Pelantikan, Pemberhentian, Serah Terima Jabatan,.', '-', 1),
(214, '134', 'Forum Koordinasi Pemerintah Di Daerah', '-', 1),
(215, '134.1', 'Muspida', '-', 1),
(216, '134.2', 'Forum PAN (Panitian Anggaran Nasional)', '-', 1),
(217, '134.3', 'Forum Koordinasi Lainnya', '-', 1),
(218, '134.4', 'Kerjasama antar Kabupaten/Kota', '-', 1),
(219, '135', 'Pembentukan / Pemekaran Wilayah', '-', 1),
(220, '135.1', 'Pemindahan Ibukota', '-', 1),
(221, '135.2', 'Pembentukan Wilayah Pembantu Bupati/Walikota', '-', 1),
(222, '135.3', 'Pemabagian Wilayah Kabupaten/Kota', '-', 1),
(223, '135.4', 'Perubahan Batas Wilayah', '-', 1),
(224, '135.5', 'Pemekaran Wilayah', '-', 1),
(225, '135.6', 'Permasalahan Batas Wilayah', '-', 1),
(226, '135.7', 'Pembentukan Ibukota Kabupaten/Kota Pemberian dan Penggantian Nama Kabupaten/Kota, Daerah,', '-', 1),
(227, '135.8', 'Jalan', '-', 1),
(228, '136', 'Pembagian Wilayah', '-', 1),
(229, '137', 'Penyerahan Urusan', '-', 1),
(230, '138', 'Pemerintah Wilayah Kecamatan', '-', 1),
(231, '138.1', 'Sambutan / Pengarahan / Amanat', '-', 1),
(232, '138.2', 'Pembentukan Kecamatan', '-', 1),
(233, '138.3', 'Pemekaran Kecamatan', '-', 1),
(234, '138.4', 'Perluasan/Perubahan Batas Wilayah Kecamatan', '-', 1),
(235, '138.5', 'Pembentukan Perwakilan Kecamatan/Kemantren', '-', 1),
(236, '138.6', '-', '-', 1),
(237, '138.7', '-', '-', 1),
(238, '139', '-', '-', 1),
(239, '140', 'PEMERINTAHAN DESA / KELURAHAN', 'Pamong Desa, Meliputi: Pencalonan, Pemilihan, Meninggal,', 1),
(240, '141', 'Pengangkatan, Pemberhenian, dan sebagainya', '-', 1),
(241, '142', 'Penghasilan Pamong Desa', '-', 1),
(242, '143', 'Kekayaan Desa', '-', 1),
(243, '144', 'Dewan Tingkat Desa, Dewan Marga, Rembug Desa', '-', 1),
(244, '145', 'Administrasi Desa', '-', 1),
(245, '146', 'Kewilayahan', '-', 1),
(246, '146.1', 'Pembentukan Desa/Kelurahan', '-', 1),
(247, '146.2', 'Pemekaran Desa/Kelurahan', '-', 1),
(248, '146.3', 'Perubahan Batas Wilayah / Perluasan Desa / Kelurahan', '-', 1),
(249, '146.4', 'Perubahan Nama Desa / Kelurahan', '-', 1),
(250, '146.5', 'Kerjasama Antar Desa / Kelurahan', '-', 1),
(251, '147', 'Lembaga-lembaga Tingkat Desa', 'Jangan Klasifikasikan Disini, Lihat 410 Dengan Perinciannya', 1),
(252, '148', 'Perangkat Kelurahan', '-', 1),
(253, '148.1', 'Kepala Kelurahan', '-', 1),
(254, '148.2', 'Sekretaris Kelurahan', '-', 1),
(255, '148.3', 'Staf Kelurahan', '-', 1),
(256, '149.1', 'Dewan Kelurahan', '-', 1),
(257, '149.2', 'Rukun Tetangga', '-', 1),
(258, '149.3', 'Rukun Warga', '-', 1),
(259, '149.4', 'Rukun Kampung', '-', 1),
(260, '150', 'LEGISLATIF MPR / DPR / DPD', '-', 1),
(261, '151', 'Keanggotaan MPR', '-', 1),
(262, '151.1', 'Pencalonan', '-', 1),
(263, '151.2', 'Pemberhentian', '-', 1),
(264, '151.3', 'Recall', '-', 1),
(265, '151.4', 'Pelanggaran', '-', 1),
(266, '152', 'Persidangan', '-', 1),
(267, '153', 'Kesejahteraan', '-', 1),
(268, '153.1', 'Keuangan', '-', 1),
(269, '153.2', 'Penghargaan', '-', 1),
(270, '154', 'Hak', '-', 1),
(271, '155', 'Keanggotaan DPR ', 'Pencalonan Pengangkatan Persidangan Sidang Pleno Dengar Pendapat/Rapat Komisi', 1),
(272, '156', 'Reses', '-', 1),
(273, '157', 'Kesejahteraan', '-', 1),
(274, '157.1', 'Keuangan', '-', 1),
(275, '157.2', 'Penghargaan', '-', 1),
(276, '158', 'Jawaban Pemerintah', '-', 1),
(277, '159', 'Hak', '-', 1),
(278, '160', 'DPRD PROVINSI TAMBAHKAN KODE WILAYAH', '-', 1),
(279, '161', 'Keanggotaan', '-', 1),
(280, '161.1', 'Pencalonan', '-', 1),
(281, '161.2', 'Pengangkatan', '-', 1),
(282, '161.3', 'Pemberhentian', '-', 1),
(283, '161.4', 'Recall', '-', 1),
(284, '161.5', 'Meninggal', '-', 1),
(285, '161.6', 'Pelanggaran', '-', 1),
(286, '162', 'Persidangan', '-', 1),
(287, '162.1', 'Reses', '-', 1),
(288, '163', 'Kesejahteraan', '-', 1),
(289, '163.1', 'Keuangan', '-', 1),
(290, '163.2', 'Penghargaan', '-', 1),
(291, '164', 'Hak', '-', 1),
(292, '165', 'Sekretaris DPRD Provinsi', '-', 1),
(293, '166', '-', '-', 1),
(294, '167', '-', '-', 1),
(295, '168', '-', '-', 1),
(296, '169', '-', '-', 1),
(297, '170', 'DPRD KABUPATEN TAMBAHKAN KODE WILAYAH', '-', 1),
(298, '171', 'Keanggotaan', '-', 1),
(299, '171.1', 'Pencalonan', '-', 1),
(300, '171.2', 'Pengangkatan', '-', 1),
(301, '171.3', 'Pemberhentian', '-', 1),
(302, '171.4', 'Recall', '-', 1),
(303, '171.5', 'Pelanggaran', '-', 1),
(304, '172', 'Persidangan', '-', 1),
(305, '173', 'Kesejahteraan', '-', 1),
(306, '173.1', 'Keuangan', '-', 1),
(307, '173.2', 'Penghargaan', '-', 1),
(308, '174', 'Hak', '-', 1),
(309, '175', 'Sekretaris DPRD Kabupaten/Kota', '-', 1),
(310, '176', '-', '-', 1),
(311, '177', '-', '-', 1),
(312, '178', '-', '-', 1),
(313, '180', 'HUKUM', '-', 1),
(314, '180.1', 'Kontitusi', '-', 1),
(315, '180.11', 'Dasar Hukum', '-', 1),
(316, '180.12', 'Undang-Undang Dasar', '-', 1),
(317, '180.2', 'GBHN', '-', 1),
(318, '180.3', 'Amnesti, Abolisi dan Grasi', '-', 1),
(319, '181', 'Perdata', '-', 1),
(320, '181.1', 'Tanah', '-', 1),
(321, '181.2', 'Rumah', '-', 1),
(322, '181.3', 'Utang/Piutang', '-', 1),
(323, '181.31', 'Gadai', '-', 1),
(324, '181.32', 'Hipotik', '-', 1),
(325, '181.4', 'Notariat', '-', 1),
(326, '182', 'Pidana', '-', 1),
(327, '182.1', 'Penyidik Pegawai Negeri Sipil (PPNS)', '-', 1),
(328, '183', 'Peradilan', 'Peradilan Agama Islam 451.6, Peradilan Perkara Tanah 593.71', 1),
(329, '183.1', 'Bantuan Hukum', '-', 1),
(330, '184', 'Hukum Internasional', '-', 1),
(331, '185', 'Imigrasi', '-', 1),
(332, '185.1', 'Visa', '-', 1),
(333, '185.2', 'Pasport', '-', 1),
(334, '185.3', 'Exit', '-', 1),
(335, '185.4', 'Reentry', '-', 1),
(336, '185.5', 'Lintas Batas/Batas Antar Negara', '-', 1),
(337, '186', 'Kepenjaraan', '-', 1),
(338, '187', 'Kejaksaan', '-', 1),
(339, '188', 'Peraturan Perundang-Undangan', '-', 1),
(340, '188.1', 'TAP MPR', '-', 1),
(341, '188.2', 'Undang-Undang Dasar', '-', 1),
(342, '188.3', 'Peraturan', '-', 1),
(343, '188.31', 'Peraturan Pemerintah', '-', 1),
(344, '188.32', 'Peraturan Menteri', '-', 1),
(345, '188.33', 'Peraturan Lembaga Non Departemen', '-', 1),
(346, '188.34', 'Peraturan Daerah', '-', 1),
(347, '188.341', 'Peraturan Provinsi', '-', 1),
(348, '188.342', 'Peraturan Kabupaten/Kota', '-', 1),
(349, '188.4', 'Keputusan', '-', 1),
(350, '188.41', 'Presiden', '-', 1),
(351, '188.42', 'Menteri', '-', 1),
(352, '188.43', 'Lembaga Non Departemen', '-', 1),
(353, '188.44', 'Gubernur', '-', 1),
(354, '188.45', 'Bupati/Walikota', '-', 1),
(355, '188.5', 'Instruksi', '-', 1),
(356, '188.51', 'Presiden', '-', 1),
(357, '188.52', 'Menteri', '-', 1),
(358, '188.53', 'Lembaga Non Departemen', '-', 1),
(359, '188.54', 'Gubernur', '-', 1),
(360, '188.55', 'Bupati/Walikota', '-', 1),
(361, '189', 'Hukum Adat', '-', 1),
(362, '189.1', 'Tokoh Adat/Masyarakat', '-', 1),
(363, '190', 'HUBUNGAN LUAR NEGERI', '-', 1),
(364, '191', 'Perwakilan Asing', '-', 1),
(365, '192', 'Tamu Negara', '-', 1),
(366, '193', 'Kerjasama Dengan Negara Asing', '-', 1),
(367, '193.1', 'Asean', '-', 1),
(368, '193.2', 'Bantuan Luar Negeri/Hibah', '-', 1),
(369, '194', 'Perwakilan RI Di Luar Negeri/Hibah', '-', 1),
(370, '195', 'PBB', '-', 1),
(371, '196', 'Laporan Luar Negeri', '-', 1),
(372, '197', 'Hutang Luar Negeri PHLN/LOAN', '-', 1),
(373, '198', '-', '-', 1),
(374, '199', '-', '-', 1),
(375, '200', 'POLITIK', '-', 1),
(376, '201', 'Kebijaksanaan umum', '-', 1),
(377, '202', 'Orde baru', '-', 1),
(378, '203', 'Reformasi', '-', 1),
(379, '204', '-', '-', 1),
(380, '205', '-', '-', 1),
(381, '206', '-', '-', 1),
(382, '210', 'KEPARTAIAN', '-', 1),
(383, '211', 'Lambang partai', '-', 1),
(384, '212', 'Kartu tanda anggota', '-', 1),
(385, '213', 'Bantuan keuangan parpol', '-', 1),
(386, '214', '-', '-', 1),
(387, '215', '-', '-', 1),
(388, '216', '-', '-', 1),
(389, '220', 'ORGANISASI KEMASYARAKATAN', '-', 1),
(390, '221', 'Berdasarkan perjuangan', '-', 1),
(391, '221.1', 'Perintis kemerdekaan', '-', 1),
(392, '221.2', 'angkatan 45', '-', 1),
(393, '221.3', 'Veteran', '-', 1),
(394, '222', 'Berdasarkan Kekaryaan', '-', 1),
(395, '222.1', 'PEPABRI', '-', 1),
(396, '222.2', 'Wredatama', '-', 1),
(397, '223', 'Berdasarkan kerohanian', '-', 1),
(398, '224', 'Lembaga adat', '-', 1),
(399, '225', 'Lembaga Swadaya Masyarakat', '-', 1),
(400, '226', '-', '-', 1),
(401, '230', 'ORGANISASI PROFESI DAN FUNGSIONAL', '-', 1),
(402, '231', 'Ikatan Dokter Indonesia', '-', 1),
(403, '232', 'Persatuan Guru Republik Indonesia', '-', 1),
(404, '233', 'PERSATUAN SARJANA HUKUM INDONESIA', '-', 1),
(405, '234', 'Persatuan Advokat Indonesia', '-', 1),
(406, '235', 'Lembaga Bantuan Hukum Indonesia', '-', 1),
(407, '236', 'Korps Pegawai Republik Indonesia', '-', 1),
(408, '237', 'Persatuan Wartawan Indonesia', '-', 1),
(409, '238', 'Ikatan Cendikiawan Muslim Indonesia', '-', 1),
(410, '239', 'Organisasi Profesi Dan Fungsional Lainnya', '-', 1),
(411, '240', 'ORGANISASI PEMUDA', '-', 1),
(412, '241', 'Komite Nasional Pemuda Indonesia', '-', 1),
(413, '242', 'Organisasi Mahasiswa', '-', 1),
(414, '243', 'Organisasi Pelajar', '-', 1),
(415, '244', 'Gerakan Pemuda Ansor', '-', 1),
(416, '245', 'Gerakan Pemuda Islam Indonesia', '-', 1),
(417, '246', 'Gerakan Pemuda Marhaenis', '-', 1),
(418, '247', '-', '-', 1),
(419, '248', '-', '-', 1),
(420, '250', 'ORGANISASI BURUH, TANI, NELAYAN DAN ANGKUTAN', '-', 1),
(421, '251', 'Federasi Buruh Seluruh Indonesia', '-', 1),
(422, '252', 'Organisasi Buruh Internasional', '-', 1),
(423, '253', 'Himpunan Kerukunan Tani', '-', 1),
(424, '254', 'Himpunan Nelayan Seluruh Indonesia', '-', 1),
(425, '255', 'Keluarga Sopir Proporsional Indonesia', '-', 1),
(426, '256', '-', '-', 1),
(427, '257', '-', '-', 1),
(428, '258', '-', '-', 1),
(429, '260', 'ORGANISASI WANITA', '-', 1),
(430, '261', 'Dharma Wanita', '-', 1),
(431, '262', 'Persatuan Wanita Indonesia', '-', 1),
(432, '263', 'Pemberdayaan Perempuan (wanita)', '-', 1),
(433, '264', 'Kongres Wanita', '-', 1),
(434, '265', '-', '-', 1),
(435, '266', '-', '-', 1),
(436, '267', '-', '-', 1),
(437, '268', '-', '-', 1),
(438, '269', '-', '-', 1),
(439, '270', 'PEMILIHAN UMUM', '-', 1),
(440, '271', 'Pencalonan', '-', 1),
(441, '272', 'Nomor Urut Partai / Tanda Gambar', '-', 1),
(442, '273', 'Kampanye', '-', 1),
(443, '274', 'Petugas Pemilu', '-', 1),
(444, '275', 'Pemilih / Daftar Pemilih', '-', 1),
(445, '276', 'Sarana', '-', 1),
(446, '276.1', 'TPS', '-', 1),
(447, '276.2', 'Kendaraan', '-', 1),
(448, '276.3', 'Surat Suara', '-', 1),
(449, '276.4', 'Kotak Suara', '-', 1),
(450, '276.5', 'Dana', '-', 1),
(451, '277', 'Pemungutan Suara / Perhitungan Suara', '-', 1),
(452, '278', 'Penetapan Hasil Pemilu', '-', 1),
(453, '279', 'Penetapan Perolehan Jumlah Kursi Dan Calon Terpilih', '-', 1),
(454, '280', 'Pengucapan Sumpah Janji MPR,DPR,DPD', '-', 1),
(455, '281', '', '-', 1),
(456, '282', '-', '-', 1),
(457, '283', '-', '-', 1),
(458, '284', '-', '-', 1),
(459, '300', 'KEAMANAN / KETERTIBAN', '-', 1),
(460, '301', 'Keamanan', '-', 1),
(461, '302', 'Ketertiban', '-', 1),
(462, '303', '-', '-', 1),
(463, '310', 'PERTAHANAN', '-', 1),
(464, '311', 'Darat', '-', 1),
(465, '312', 'Laut', '-', 1),
(466, '313', 'Udara', '-', 1),
(467, '314', 'Perbatasan', '-', 1),
(468, '315', '-', '-', 1),
(469, '316', '-', '-', 1),
(470, '317', '-', '-', 1),
(471, '320', 'KEMILITERAN', '-', 1),
(472, '321', 'Latihan Militer', '-', 1),
(473, '322', 'Wajib Militer', '-', 1),
(474, '323', 'Operasi Militer', '-', 1),
(475, '324', 'Kekaryaan TNI Pejabat Sipil dari TNI', '-', 1),
(476, '324.1', 'TMD', '-', 1),
(477, '325', '-', '-', 1),
(478, '326', '-', '-', 1),
(479, '327', '-', '-', 1),
(480, '328', '-', '-', 1),
(481, '330', 'KEAMANAN', '-', 1),
(482, '331', 'Kepolisian', '-', 1),
(483, '331.1', 'Polisi Pamong Praja', '-', 1),
(484, '331.2', 'Kamra', '-', 1),
(485, '331.3', 'Kamling', '-', 1),
(486, '331.4', 'Jaga Wana', '-', 1),
(487, '332', 'Huru-Hara / Demonstrasi', '-', 1),
(488, '333', 'Senjata Api Tajam', '-', 1),
(489, '334', 'Bahan Peledak', '-', 1),
(490, '335', 'Perjudian', '-', 1),
(491, '336', 'Surat-Surat Kaleng', '-', 1),
(492, '337', 'Pengaduan', '-', 1),
(493, '338', 'Himbauan / Larangan', '-', 1),
(494, '339', 'Teroris', '-', 1),
(495, '340', 'PERTAHANAN SIPIL', '-', 1),
(496, '341', 'Perlindungan Sipil', '-', 1),
(497, '342', '-', '-', 1),
(498, '343', '-', '-', 1),
(499, '344', '-', '-', 1),
(500, '350', 'KEJAHATAN', '-', 1),
(501, '351', 'Makar / Pemberontak', '-', 1),
(502, '352', 'Pembunuhan', '-', 1),
(503, '353', 'Penganiayaan, Pencurian', '-', 1),
(504, '354', 'Subversi / Penyelundupan / Narkotika', '-', 1),
(505, '355', 'Pemalsuan', '-', 1),
(506, '356', 'Korupsi / Penyelewengan / Penyalahgunaan Jabatan / KKN', '-', 1),
(507, '357', 'Pemerkosaan / Perbuatan Cabul', '-', 1),
(508, '358', 'Kenakalan', '-', 1),
(509, '359', 'Kejahatan Lainnya', '-', 1),
(510, '360', 'BENCANA', '-', 1),
(511, '361', 'Gunung Berapi / Gempa', '-', 1),
(512, '362', 'Banjir / Tanah Longsor', '-', 1),
(513, '363', 'Angin Topan', '-', 1),
(514, '364', 'Kebakaran', '-', 1),
(515, '364.1', 'Pemadam Kebakaran', '-', 1),
(516, '365', 'Kekeringan', '-', 1),
(517, '366', 'Tsunami', '-', 1),
(518, '367', '-', '-', 1),
(519, '368', '-', '-', 1),
(520, '370', 'KECELAKAAN / SAR', '-', 1),
(521, '371', 'Darat', '-', 1),
(522, '372', 'Udara', '-', 1),
(523, '373', 'Laut', '-', 1),
(524, '374', 'Sungai / Danau', '-', 1),
(525, '375', '-', '-', 1),
(526, '376', '-', '-', 1),
(527, '377', '-', '-', 1),
(528, '380', '-', '-', 1),
(529, '381', '-', '-', 1),
(530, '382', '-', '-', 1),
(531, '383', '-', '-', 1),
(532, '390', '-', '-', 1),
(533, '391', '-', '-', 1),
(534, '392', '-', '-', 1),
(535, '393', '-', '-', 1),
(536, '400', 'KESEJAHTERAAN RAKYAT', '-', 1),
(537, '401', 'Keluarga Miskin', '-', 1),
(538, '402', 'PNPM Mandiri Pedesaan', '-', 1),
(539, '403', '-', '-', 1),
(540, '404', '-', '-', 1),
(541, '410', 'PEMBANGUNAN DESA', '-', 1),
(542, '411', 'Pembinaan Usaha Gotong Royong', '-', 1),
(543, '411.1', 'Swadaya Gotong Royong', '-', 1),
(544, '411.11', 'Penataan Gotong Royong', '-', 1),
(545, '411.12', 'Gotong Royong Dinamis', '-', 1),
(546, '411.13', 'Gotong Royong Statis', '-', 1),
(547, '411.14', 'Pungutan', '-', 1),
(548, '411.2', 'Lembaga Sosial Desa (LSD)', '-', 1),
(549, '411.21', 'Pembinaan', '-', 1),
(550, '411.22', 'Klasifikasi', '-', 1),
(551, '411.23', 'Proyek', '-', 1),
(552, '411.24', 'Musyawarah', '-', 1),
(553, '411.3', 'Latihan Kerja Masyarakat', '-', 1),
(554, '411.31', 'Kader Masyarakat', '-', 1),
(555, '411.32', 'Kuliah Kerja Nyata (KKN)', '-', 1),
(556, '411.33', 'Pusat Latihan', '-', 1),
(557, '411.34', 'Kursus-Kursus', '-', 1),
(558, '411.35', 'Kurikulum / Sylabus', '-', 1),
(559, '411.36', 'Ketrampilan', '-', 1),
(560, '411.37', 'Pramuka', '-', 1),
(561, '411.4', 'Pembinaan Kesejahteraan Keluarga (PKK)', '-', 1),
(562, '411.41', 'Program', '-', 1),
(563, '411.42', 'Pembinaan Organisasi', '-', 1),
(564, '411.43', 'Kegiatan', '-', 1),
(565, '411.5', 'Penyuluhan', '-', 1),
(566, '411.51', 'Publikasi', '-', 1),
(567, '411.52', 'Peragaan', '-', 1),
(568, '411.53', 'Sosio Drama', '-', 1),
(569, '411.54', 'Siaran Pedesaan', '-', 1),
(570, '411.55', 'Penyuluhan Lapangan', '-', 1),
(571, '411.6', 'Kelembagaan Desa', '-', 1),
(572, '411.61', 'Kelompok Tani', '-', 1),
(573, '411.62', 'Rukun Tani', '-', 1),
(574, '411.63', 'Subak', '-', 1),
(575, '411.64', 'Dharma Tirta', '-', 1),
(576, '412', 'Perekonomian Desa', '-', 1),
(577, '412.1', 'Produksi Desa', '-', 1),
(578, '412.11', 'Pengolahan', '-', 1),
(579, '412.12', 'Pemasaran', '-', 1),
(580, '412.2', 'Keuangan Desa', '-', 1),
(581, '412.21', 'Perkreditan Desa', '-', 1),
(582, '412.22', 'Inventarisasi Data', '-', 1),
(583, '412.23', 'Perkembangan / Pelaksanaan', '-', 1),
(584, '412.24', 'Bantuan / Stimulans', '-', 1),
(585, '412.25', 'Petunjuk / Pembinaan Pelaksanaan', '-', 1),
(586, '412.3', 'Koperasi Desa', '-', 1),
(587, '412.31', 'Badan Usaha Unit Desa (BUUD)', '-', 1),
(588, '412.32', 'Koperasi Usaha Desa', '-', 1),
(589, '412.4', 'Penataan Bantuan Pembangunan Desa', '-', 1),
(590, '412.41', 'Jumlah Desa Yang Diberi Bantuan', '-', 1),
(591, '412.42', 'Pengarahan', '-', 1),
(592, '412.43', 'Pusat', '-', 1),
(593, '412.44', 'Daerah', '-', 1),
(594, '412.5', 'Alokasi Bantuan Pembangunan Desa', '-', 1),
(595, '412.51', 'Pusat', '-', 1),
(596, '412.52', 'Daerah', '-', 1),
(597, '412.6', 'Pelaksanaan Bantuan Pembangunan Desa', '-', 1),
(598, '412.61', 'Bantuan Langsung', '-', 1),
(599, '412.62', 'Bantuan Keserasian', '-', 1),
(600, '412.63', 'Bantuan Juara Lomba Desa', '-', 1),
(601, '413', 'Prasarana Desa', '-', 1),
(602, '413.1', 'Prasarana Desa', '-', 1),
(603, '413.11', 'Pembinaan', '-', 1),
(604, '413.12', 'Bimbingan Teknis', '-', 1),
(605, '413.2', 'Pemukiman Kembali Penduduk', '-', 1),
(606, '413.21', 'Lokasi', '-', 1),
(607, '413.22', 'Diskusi', '-', 1),
(608, '413.23', 'Pelaksanaan', '-', 1),
(609, '413.3', 'Masyarakat Pradesa', '-', 1),
(610, '413.31', 'Pembinaan', '-', 1),
(611, '413.32', 'Penyuluhan', '-', 1),
(612, '413.4', 'Pemugaran Perumahan Dan Lingkungan Desa', '-', 1),
(613, '413.41', 'Rumah Sehat', '-', 1),
(614, '413.42', 'Proyek Perintis', '-', 1),
(615, '413.43', 'Pelaksanaan', '-', 1),
(616, '413.44', 'Pengembangan', '-', 1),
(617, '413.45', 'Perbaikan Kampung', '-', 1),
(618, '414', 'Pengembangan Desa', '-', 1),
(619, '414.1', 'Tingkat Perkembangan Desa', '-', 1),
(620, '414.11', 'Jumlah Desa', '-', 1),
(621, '414.12', 'Pemekaran Desa', '-', 1),
(622, '414.13', 'Pembentukan Desa Baru', '-', 1),
(623, '414.14', 'Evaluasi', '-', 1),
(624, '414.15', 'Bagan', '-', 1),
(625, '414.2', 'Unit Desa Kerja Pembangunan (UDKP)', '-', 1),
(626, '414.21', 'Penyuluhan Program', '-', 1),
(627, '414.22', 'Lokasi UDKP', '-', 1),
(628, '414.23', 'Pelaksanaan', '-', 1),
(629, '414.24', 'Bimbingan/Pembinaan', '-', 1),
(630, '414.25', 'Evaluasi', '-', 1),
(631, '414.3', 'Tata Desa', '-', 1),
(632, '414.31', 'Inventarisasi', '-', 1),
(633, '414.32', 'Penyusunan Pola Tata Desa', '-', 1),
(634, '414.33', 'Aplikasi Tata Desa', '-', 1),
(635, '414.34', 'Pemetaan', '-', 1),
(636, '414.35', 'Pedoman Pelaksanaan', '-', 1),
(637, '414.36', 'Evaluasi', '-', 1),
(638, '414.4', 'Perlombaan Desa', '-', 1),
(639, '414.41', 'Pedoman', '-', 1),
(640, '414.42', 'Penilaian', '-', 1),
(641, '414.43', 'Kejuaraan', '-', 1),
(642, '414.44', 'Piagam', '-', 1),
(643, '415', 'Koordinasi', '-', 1),
(644, '415.1', 'Sektor Khusus', '-', 1),
(645, '415.2', 'Rapat Koordinasi Horizontal (RKH)', '-', 1),
(646, '415.3', 'Tim Koordinasi Pusat (TKP)', '-', 1),
(647, '415.4', 'Kerjasama', '-', 1),
(648, '415.41', 'Luar Negeri (UNICEF)', '-', 1),
(649, '415.42', 'Perguruan Tinggi', '-', 1),
(650, '415.43', 'Kementerian / Lembaga Non Kementerian', '-', 1),
(651, '416', '-', '-', 1),
(652, '417', '-', '-', 1),
(653, '418', '-', '-', 1),
(654, '420', 'PENDIDIKAN', '-', 1),
(655, '420.1', 'Pendidikan Khusus Klasifikasi Disini Pendidikan Putra/I Irja', '-', 1),
(656, '421', 'Sekolah', '-', 1),
(657, '421.1', 'Pra Sekolah', '-', 1),
(658, '421.2', 'Sekolah Dasar', '-', 1),
(659, '421.3', 'Sekolah Menengah', '-', 1),
(660, '421.4', 'Sekolah Tinggi', '-', 1),
(661, '421.5', 'Sekolah Kejuruan', '-', 1),
(662, '421.6', 'Kegiatan Sekolah, Dies Natalis Lustrum', '-', 1),
(663, '421.7', 'Kegiatan Pelajar', '-', 1),
(664, '421.71', 'Reuni Darmawisata', '-', 1),
(665, '421.72', 'Pelajar Teladan', '-', 1),
(666, '421.73', 'Resimen Mahasiswa', '-', 1),
(667, '421.8', 'Sekolah Pendidikan Luar Biasa', '-', 1),
(668, '421.9', 'Pendidikan Luar Sekolah / Pemberantasan Buta Huruf', '-', 1),
(669, '422', 'Administrasi Sekolah', 'Persyaratan Masuk Sekolah, Testing, Ujian, Pendaftaran,', 1),
(670, '422.1', 'Mapras, Perpeloncoan', '-', 1),
(671, '422.2', 'Tahun Pelajaran', '-', 1),
(672, '422.3', 'Hari Libur', '-', 1),
(673, '422.4', 'Uang Sekolah, Klasifikasi Disini SPP', '-', 1),
(674, '422.5', 'Beasiswa', '-', 1),
(675, '423', 'Metode Belajar', '-', 1),
(676, '423.1', 'Kuliah', '-', 1),
(677, '423.2', 'Ceramah, Simposium', '-', 1),
(678, '423.3', 'Diskusi', '-', 1),
(679, '423.4', 'Kuliah Lapangan, Widyawisata, KKN, Studi Tur', '-', 1),
(680, '423.5', 'Kurikulum', '-', 1),
(681, '423.6', 'Karya Tulis', '-', 1),
(682, '423.7', 'Ujian', '-', 1),
(683, '424', 'Tenaga Pengajar, Guru, Dosen, Dekan, Rektor', 'Klasifikasi Disini: Guru Teladan', 1),
(684, '425', 'Sarana Pendidikan', '-', 1),
(685, '425.1', 'Gedung', '-', 1),
(686, '425.11', 'Gedung Sekolah', '-', 1),
(687, '425.12', 'Kampus', '-', 1),
(688, '425.13', 'Pusat Kegiatan Mahasiswa', '-', 1),
(689, '425.2', 'Buku', '-', 1),
(690, '425.3', 'Perlengkapan Sekolah', '-', 1),
(691, '426', 'Keolahragaan', '-', 1),
(692, '426.1', 'Cabang Olah Raga', '-', 1),
(693, '426.2', 'Sarana', '-', 1),
(694, '426.21', 'Gedung Olah Raga', '-', 1),
(695, '426.22', 'Stadion', '-', 1),
(696, '426.23', 'Lapangan', '-', 1),
(697, '426.24', 'Kolam renang', '-', 1),
(698, '426.3', 'Pesta Olah Raga, ', 'Klasifikasi Disini: PON, Porsade, Olimpiade, dsb', 1),
(699, '426.4', 'KONI', '-', 1),
(700, '427', 'Kepramukaan Meliputi: Organisasi Dan Kegiatan Remaja', 'Klasifikasi Disini: Gelanggang Remaja', 1),
(701, '428', 'Kepramukaan', '-', 1),
(702, '429', 'Pendidikan  Kedinasan Untuk Depdagri, Lihat 890', '-', 1),
(703, '430', 'KEBUDAYAAN', '-', 1),
(704, '431', 'Kesenian', '-', 1),
(705, '431.1', 'Cabang Kesenian', '-', 1),
(706, '431.2', 'Sarana', '-', 1),
(707, '431.21', 'Gedung Kesenian', '-', 1),
(708, '432', 'Kepurbakalaan', '-', 1),
(709, '432.1', 'Museum', '-', 1),
(710, '432.2', 'Peninggalan Kuno', '-', 1),
(711, '432.21', 'Candi Termasuk Pemugaran', '-', 1),
(712, '432.22', 'Benda', '-', 1),
(713, '433', 'Sejarah', '-', 1),
(714, '434', 'Bahasa', '-', 1),
(715, '435', 'Usaha Pertunjukan, Hiburan, Kesenangan', '-', 1),
(716, '436', 'Kepercayaan', '-', 1),
(717, '437', '-', '-', 1),
(718, '438', '-', '-', 1),
(719, '439', '-', '-', 1),
(720, '440', 'KESEHATAN', '-', 1),
(721, '441', 'Pembinaan Kesehatan', '-', 1),
(722, '441.1', 'Gizi', '-', 1),
(723, '441.2', 'Mata', '-', 1),
(724, '441.3', 'Jiwa', '-', 1),
(725, '441.4', 'Kanker', '-', 1),
(726, '441.5', 'Usaha Kegiatan Sekolah (UKS)', '-', 1),
(727, '441.6', 'Perawatan', '-', 1),
(728, '441.7', 'Penyuluhan Kesehatan Masyarakat (PKM)', '-', 1),
(729, '441.8', 'Pekan Imunisasi Nasional', '-', 1),
(730, '442', 'Obat-obatan', '-', 1),
(731, '442.1', 'Pengadaan', '-', 1),
(732, '442.2', 'Penyimpanan', '-', 1),
(733, '443', 'Penyakit Menular', '-', 1),
(734, '443.1', 'Pencegahan', '-', 1),
(735, '443.2', 'Pemberantasan dan Pencegahan Penyakit Menular Langsung (P2ML)', '-', 1),
(736, '443.21', 'Kusta', '-', 1),
(737, '443.22', 'Kelamin', '-', 1),
(738, '443.23', 'Frambosia', '-', 1),
(739, '443.24', 'TBC / AIDS / HIV', '-', 1),
(740, '443.3', 'Epidemiologi dan Karantina (Epidka)', '-', 1),
(741, '443.31', 'Kholera', '-', 1),
(742, '443.32', 'Imunisasi', '-', 1),
(743, '443.33', 'Survailense', '-', 1),
(744, '443.34', 'Rabies (Anjing Gila) Antraks', '-', 1),
(745, '443.4', 'Pemberantasan & Pencegahan Penyakit Menular Sumber Binatang (P2B)', '-', 1),
(746, '443.41', 'Malaria', '-', 1),
(747, '443.42', 'Dengue Faemorrhagic Fever (Demam Berdarah HDF)', '-', 1),
(748, '443.43', 'Filaria', '-', 1),
(749, '443.44', 'Serangga', '-', 1),
(750, '443.5', 'Hygiene Sanitasi', '-', 1),
(751, '443.51', 'Tempat-tempat Pembuatan Dan Penjualan Makanan dan Minuman (TPPMM)', '-', 1),
(752, '443.52', 'Sarana Air Minum Dan Jamban Keluarga (Samijaga)', '-', 1),
(753, '443.53', 'Pestisida', '-', 1),
(754, '444', 'Gizi', '-', 1),
(755, '444.1', ' Kekurangan Makanan Bahaya Kelaparan, Busung Lapar', '-', 1),
(756, '444.2', 'Keracunan Makanan', '-', 1),
(757, '444.3', 'Menu Makanan Rakyat', '-', 1),
(758, '444.4', 'Badan Perbaikan Gizi Daerah (BPGD)', '-', 1),
(759, '444.5', 'Program Makanan Tambahn Anak Sekolah (PMT-AS)', '-', 1),
(760, '445', 'Rumah Sakit, Balai Kesehatan, PUSKESMAS, PUSKESMAS, Keliling, Poliklinik', '-', 1),
(761, '446', 'Tenaga Medis', '-', 1),
(762, '448', 'Pengobatan Tadisional', '-', 1),
(763, '448.1', 'Pijat', '-', 1),
(764, '448.2', 'Tusuk Jarum', '-', 1),
(765, '448.3', 'Jamu Tradisional', '-', 1),
(766, '448.4', 'Dukun / Paranormal', '-', 1),
(767, '450', 'AGAMA', '-', 1),
(768, '451', 'Islam', '-', 1),
(769, '451.1', 'Peribadatan', '-', 1),
(770, '451.11', 'Sholat', '-', 1),
(771, '451.12', 'Zakat Fitrah', '-', 1),
(772, '451.13', 'Puasa', '-', 1),
(773, '451.14', 'MTQ', '-', 1),
(774, '451.2', 'Rumah Ibadah', '-', 1),
(775, '451.3', 'Tokoh Agama', '-', 1),
(776, '451.4', 'Pendidikan', '-', 1),
(777, '451.41', 'Tinggi', '-', 1),
(778, '451.42', 'Menengah', '-', 1),
(779, '451.43', 'Dasar', '-', 1),
(780, '451.44', 'Pondok Pesantren', '-', 1),
(781, '451.45', 'Gedung Sekolah', '-', 1),
(782, '451.46', 'Tenaga Pengajar', '-', 1),
(783, '451.47', 'Buku', '-', 1),
(784, '451.48', 'Dakwah', '-', 1),
(785, '451.49', 'Organisasi / Lembaga Pendidikan', '-', 1),
(786, '451.5', 'Harta Agama Wakaf, Baitulmal, dsb', '-', 1),
(787, '451.6', 'Peradilan', '-', 1),
(788, '451.7', 'Organisasi Keagamaan Bukan Politik Majelis Ulama', '-', 1),
(789, '451.8', 'Mazhab', '-', 1),
(790, '452', 'Protestan', '-', 1),
(791, '452.1', 'Peribadatan', '-', 1),
(792, '452.2', 'Rumah Ibadah', '-', 1),
(793, '452.3', 'Tokoh Agama, Rohaniawan, Pendeta, Domine', '-', 1),
(794, '452.4', 'Mazhab', '-', 1),
(795, '452.5', 'Organisasi Gerejani', '-', 1),
(796, '453', 'Katolik', '-', 1),
(797, '453.1', 'Peribadatan', '-', 1),
(798, '453.2', 'Rumah Ibadah', '-', 1),
(799, '453.3', 'Tokoh Agama, Rohaniawan, Pendeta, Pastor', '-', 1),
(800, '453.4', 'Mazhab', '-', 1),
(801, '453.5', 'Organisasi Gerejani', '-', 1),
(802, '454', 'Hindu', '-', 1),
(803, '454.1', 'Peribadatan', '-', 1),
(804, '454.2', 'Rumah Ibadah', '-', 1),
(805, '454.3', 'Tokoh Agama, Rohaniawan', '-', 1),
(806, '454.4', 'Mazhab', '-', 1),
(807, '454.5', 'Organisasi Keagamaan', '-', 1),
(808, '455', 'Budha', '-', 1),
(809, '455.1', 'Peribadatan', '-', 1),
(810, '455.2', 'Rumah Ibadah', '-', 1),
(811, '455.3', 'Tokoh Agama, Rohaniawan', '-', 1),
(812, '455.4', 'Mazhab', '-', 1),
(813, '455.5', 'Organisasi Keagamaan', '-', 1),
(814, '456', 'Urusan Haji', '-', 1),
(815, '456.1', 'ONH', '-', 1),
(816, '456.2', 'Manasik', '-', 1),
(817, '457', '-', '-', 1),
(818, '458', '-', '-', 1),
(819, '458', '-', '-', 1),
(820, '460', 'SOSIAL', '-', 1),
(821, '461', 'Rehabilitasi Penderita Cacat', '-', 1),
(822, '461.1', 'Cacat Maat', '-', 1),
(823, '461.2', 'Cacat Tubuh', '-', 1),
(824, '461.3', 'Cacat Mental', '-', 1),
(825, '461.4', 'Bisul/Tuli', '-', 1),
(826, '462', 'Tuna Sosial', '-', 1),
(827, '462.1', 'Gelandangan', '-', 1),
(828, '462.2', 'Pengemis', '-', 1),
(829, '462.3', 'Tuna Susila', '-', 1),
(830, '462.4', 'Anak Nakal', '-', 1),
(831, '463', 'Kesejahteraan Anak / Keluarga', '-', 1),
(832, '463.1', 'Anak Putus Sekolah', '-', 1),
(833, '463.2', 'Ibu Teladan', '-', 1),
(834, '463.3', 'Anak Asuh', '-', 1),
(835, '464', 'Pembinaan Pahlawan', 'Pahlawan Meliputi: Penghargaan Kepada Pahlawan,', 1),
(836, '464.1', 'Tunjangan Kepada Pahlawan Dan Jandanya', 'Perintis Kemerdekaan Meliputi: Pembinaan, Penghargaan', 1),
(837, '464.2', 'Dan Tunjangan Kepada Perintis', '-', 1),
(838, '464.3', 'Cacat Veteran', '-', 1),
(839, '465', 'Kesejahteraan Sosial', '-', 1),
(840, '465.1', 'Lanjut Usia', '-', 1),
(841, '465.2', 'Korban Kekacauan, Pengungsi, Repatriasi', '-', 1),
(842, '466', 'Sumbangan Sosial', '-', 1),
(843, '466.1', 'Korban Bencana', '-', 1),
(844, '466.2', 'Pencarian Dana Untuk Sumbangan', '-', 1),
(845, '466.3', 'Meliputi: Penyelenggaraan Undian, Ketangkasan, Bazar, dsb', '-', 1),
(846, '466.4', 'Panti Asuhan', '-', 1),
(847, '466.5', 'Panti Jompo', '-', 1),
(848, '467', ' Bimbingan Sosial', '-', 1),
(849, '467.1', 'Masyarakat Suku Terasing Meliputi: Bimbingan, Pendidikan, Kesehatan, Pemukiman', '-', 1),
(850, '468', 'PMI', '-', 1),
(851, '469', 'Makam', '-', 1),
(852, '469.1', 'Umum', '-', 1),
(853, '469.2', 'Pahlawan Meliputi: Penghargaan Kepada Pahlawan, Tunjangan Kpd Pahlawan Dan Jandanya', '-', 1),
(854, '469.3', 'Khusus Keluarga Raja', '-', 1),
(855, '469.4', 'Krematorium', '-', 1),
(856, '470', 'KEPENDUDUKAN', '-', 1),
(857, '471', 'Pendaftaran Penduduk', '-', 1),
(858, '471.1', 'Identitas Penduduk', '-', 1),
(859, '471.11', 'Biodata', '-', 1),
(860, '471.12', 'Nomor Induk Kependudukan', '-', 1),
(861, '471.13', 'Kartu Tanda Penduduk', '-', 1),
(862, '471.14', 'Kartu Keluarga', '-', 1),
(863, '471.15', 'Advokasi Indentitas Penduduk', '-', 1),
(864, '471.2', 'Perpindahan Penduduk Dalam Wilayah Indonesia', '-', 1),
(865, '471.21', 'Perpindahan Penduduk WNI', '-', 1),
(866, '471.22', 'Perpindahan Penduduk WNA Dalam Wilayah Indonesia', '-', 1),
(867, '471.23', 'Perpindahan Penduduk WNA dan WNI Tinggal Sementara', '-', 1),
(868, '471.24', 'Daerah Terbelakan', '-', 1),
(869, '471.25', 'Bedol Desa', '-', 1),
(870, '471.3', 'Perpindahan Penduduk Antar Negara', '-', 1),
(871, '471.31', 'Penduduk Indonesia Ke Luar Negeri', '-', 1),
(872, '471.32', 'Orang Asing Tinggal Sementara', '-', 1),
(873, '471.33', 'Orang Asing Tinggal Tetap', '-', 1),
(874, '471.34', 'Perpindahan Penduduk Antar Negara Di Wilayah Pembatasan Antar Negara (Pelintas Batas Tradisional)', '-', 1),
(875, '471.4', 'Pendaftaran Pengungsi Dan Penduduk Rentan', '-', 1),
(876, '471.41', 'Akibat Bencana Alam', '-', 1),
(877, '471.42', 'Akibat Kerusuhan Sosial', '-', 1),
(878, '471.43', 'Pendaftaran Penduduk Daerah Terbelakang', '-', 1),
(879, '471.44', 'Pendaftaran Penduduk Rentan', '-', 1),
(880, '472', 'Pencatatan Sipil', '-', 1),
(881, '472.1', 'Kelahiran, Kematian Dan Advokasi', '-', 1),
(882, '472.11', 'Kelahiran', '-', 1),
(883, '472.12', 'Kematian', '-', 1),
(884, '472.13', 'Advokasi Kelahiran Dan Kematian', '-', 1),
(885, '472.2', 'Perkawinan, Peceraian Dan Advokasi', '-', 1),
(886, '472.3', 'Perkawinan Agama Islam', '-', 1),
(887, '472.4', 'Perkawinan Agama Non Islam', '-', 1),
(888, '472.5', 'Perceraian Agama Islam', '-', 1),
(889, '472.6', 'Perceraian Agama Non Islam', '-', 1),
(890, '472.7', 'Advokasi Perkawinan Dan Perceraian', '-', 1),
(891, '472.3', 'Pengangkatan, Pengakuan, Dan Pengesahan Anak Serta Perubahan Dan Pembatalan Akta Dan Advokasi Pengangkatan Anak', '-', 1),
(892, '472.31', 'Pengangkatan Anak', '-', 1),
(893, '472.32', 'Pengakuan Anak', '-', 1),
(894, '472.33', 'Pengesahan Anak', '-', 1),
(895, '472.34', 'Perubahan Anak', '-', 1),
(896, '472.35', 'Pembatalan Anak', '-', 1),
(897, '472.36', 'Advokasi Pengurusan Pengangkatan, Pengakuan Dan Pengesahan Anak Serta Perubahan Dan Pembatalan Akta', '-', 1),
(898, '472.4', 'Pencatatan Kewarganegaraan', '-', 1),
(899, '472.41', 'Akibat Perkawinan', '-', 1),
(900, '472.42', 'Akibat Kelahiran', '-', 1),
(901, '472.43', 'Non Perkawinan', '-', 1),
(902, '472.44', 'Non Kelahiran', '-', 1),
(903, '472.45', 'Perubahan WNI ke WNA', '-', 1),
(904, '473', 'Informasi Kependudukan', '-', 1),
(905, '473.1', 'Teknologi Informasi', '-', 1),
(906, '473.11', 'Perangkat Keras', '-', 1),
(907, '473.12', 'Perangkat Lunak', '-', 1),
(908, '473.13', 'Jaringan Komunikasi Data', '-', 1),
(909, '473.2', 'Kelembagaan Dan Sumber Daya Informasi', '-', 1),
(910, '473.21', 'Daerah Maju', '-', 1),
(911, '473.22', 'Daerah Berkembang', '-', 1),
(912, '473.23', 'Daerah Terbelakang', '-', 1),
(913, '473.3', 'Pengolahan Data Kependudukan', '-', 1),
(914, '473.31', 'Pendaftaran Penduduk', '-', 1),
(915, '473.32', 'Kejadian Vital Penduduk', '-', 1),
(916, '473.33', 'Penduduk Non Registrasi', '-', 1),
(917, '473.4', 'Pelayanan Informasi Kependudukan', '-', 1),
(918, '473.41', 'Media Elektronik', '-', 1),
(919, '473.42', 'Media Cetak', '-', 1),
(920, '473.43', 'Outlet', '-', 1),
(921, '474', 'Perkembangan Penduduk', '-', 1),
(922, '474.1', 'Pengarahan Kuantitas Penduduk', '-', 1),
(923, '474.11', 'Struktur Jumlah', '-', 1),
(924, '474.12', 'Komposisi', '-', 1),
(925, '474.13', 'Fertilitas', '-', 1),
(926, '474.14', 'Kesehatan Reproduksi', '-', 1),
(927, '474.15', 'Morbiditas Penduduk', '-', 1),
(928, '474.16', 'Mortalitas Penduduk', '-', 1),
(929, '474.2', 'Pengembangan Kuantitas Penduduk', '-', 1),
(930, '474.21', 'Anak dan Remaja', '-', 1),
(931, '474.22', 'Penduduk Usia Produktif', '-', 1),
(932, '474.23', 'Penduduk Lanjut Usia', '-', 1),
(933, '474.24', 'Gender', '-', 1),
(934, '474.3', 'Penataan Persebaran Penduduk', '-', 1),
(935, '474.31', 'Migrasi Antar Wilayah', '-', 1),
(936, '474.32', 'Migrasi Internasional', '-', 1),
(937, '474.33', 'Urbanisasi', '-', 1),
(938, '474.34', 'Sementara', '-', 1),
(939, '474.35', 'Migrasi Non Permanen', '-', 1),
(940, '474.4', 'Perlindungan Pemberdayaan Penduduk', '-', 1),
(941, '474.41', 'Pengembangan Sistem Pelindungan Penduduk', '-', 1),
(942, '474.42', 'Pelayanan Kelembagaan Ekonomi', '-', 1),
(943, '474.43', 'Pelayanan Kelembagaan Sosial Budaya', '-', 1),
(944, '474.44', 'Partisipasi Masyarakat', '-', 1),
(945, '474.5', 'Pengembangan Wawasan Kependudukan', '-', 1),
(946, '474.51', 'Pendidikan Jalur Sekolah', '-', 1),
(947, '474.52', 'Pendidikan Jalur Luar Sekolah', '-', 1),
(948, '474.53', 'Pendidikan Jalur Masyarakat', '-', 1),
(949, '474.54', 'Pembangunan Berwawasan Kependudukan', '-', 1),
(950, '475', 'Proyeksi Dan Penyerasian Kebijakan Kependudukan', '-', 1),
(951, '475.1', 'Indikator Kependudukan', '-', 1),
(952, '475.11', 'Perumusan Penetapan Dan Pengembangan Indikator Kependudukan', '-', 1),
(953, '475.12', 'Pemanfaatan Indikator Kependudukan', '-', 1),
(954, '475.13', 'Sosialisasi Indikator Kependudukan', '-', 1),
(955, '475.2', 'Proyeksi Kependudukan', '-', 1),
(956, '475.21', 'Penyusunan Dan Pengembangan Proyeksi Kependudukan', '-', 1),
(957, '475.22', 'Pemanfaatan Proyeksi Kependudukan', '-', 1),
(958, '475.3', 'Analisis Dampak Kependudukan', '-', 1),
(959, '475.31', 'Penyusunan Dan Pengembangan', '-', 1),
(960, '475.32', 'Pemanfaatan Analisis Dampak Kependudukan', '-', 1),
(961, '475.4', 'Penyerasian Kebijakan Lembaga Non Pemerintah', '-', 1),
(962, '475.41', 'Lembaga Internasioanal', '-', 1),
(963, '475.42', 'Lembaga Masyarakat Dan Nirlaba', '-', 1),
(964, '475.43', 'Lembaga Usaha Swasta', '-', 1),
(965, '475.5', 'Penyerasian Kebijakan Lembaga Pemerintah', '-', 1),
(966, '475.51', 'Lembaga Pemerintah', '-', 1),
(967, '475.52', 'Pemerintah Provinsidan Kota', '-', 1),
(968, '475.53', 'Pemerintah Kabupaten', '-', 1),
(969, '475.6', 'Analisis', '-', 1),
(970, '476', 'Monitoring', '-', 1),
(971, '477', 'Evaluasi', '-', 1),
(972, '478', 'Dokumentasi', '-', 1),
(973, '479', '-', '-', 1),
(974, '480', 'MEDIA MASSA', '-', 1),
(975, '481', 'Penerbitan', '-', 1),
(976, '481.1', 'Surat Kabar', '-', 1),
(977, '481.2', 'Majalah', '-', 1),
(978, '481.3', 'Buku', '-', 1),
(979, '481.4', 'Penerjemahan', '-', 1),
(980, '482', 'Radio', '-', 1),
(981, '482.1', 'RRI', '-', 1),
(982, '482.11', 'Siaran Pedesaan Jgn Diklasifikasikan Disini', '-', 1),
(983, '482.2', 'Non RRI', '-', 1),
(984, '482.3', 'Luar Negeri', '-', 1),
(985, '483', 'Televisi', '-', 1),
(986, '484', 'Film', '-', 1),
(987, '485', 'Pers', '-', 1),
(988, '485.1', 'Kewartawanan', '-', 1),
(989, '485.2', 'Wawancara', '-', 1),
(990, '485.3', 'Informasi Nasional', '-', 1),
(991, '486', 'Grafika', '-', 1),
(992, '487', 'Penerangan', '-', 1),
(993, '487.1', 'Pameran Non Komersil', '-', 1),
(994, '488', 'Operation Room', '-', 1),
(995, '489', 'Hubungan Masyarakat', '-', 1),
(996, '490', 'Pengaduan Masyarakat', '-', 1),
(997, '491', '-', '-', 1),
(998, '492', '-', '-', 1),
(999, '500', 'PEREKONOMIAN', '-', 1),
(1000, '500.1', 'Dewan Stabilisasi', '-', 1),
(1001, '501', 'Pengadaan Pangan', '-', 1),
(1002, '502', 'Pengadaan Sandang Perizinan Pada Umumnya Untuk Perizinan Suatu Bidang,', '-', 1),
(1003, '503', 'Kalsifikasikan Masalahnya', '-', 1),
(1004, '504', '-', '-', 1),
(1005, '505', '-', '-', 1),
(1006, '506', '-', '-', 1),
(1007, '510', 'PERDAGANGAN', 'Klasifikasikan Disini: Tata Niaga', 1),
(1008, '510.1', 'Promosi Perdagangan', '-', 1),
(1009, '510.11', 'Pekan Raya', '-', 1),
(1010, '510.12', 'Iklan', '-', 1),
(1011, '510.13', 'Pameran Non Komersil', '-', 1),
(1012, '510.2', 'Pelelangan', '-', 1),
(1013, '510.3', 'Tera', '-', 1),
(1014, '511', 'Pemasaran', '-', 1),
(1015, '511.1', 'Sembilan Bahan Pokok, Tambahkan Kode Wilayah : Beras, Garam, Tanah, Minyak Goreng', '-', 1),
(1016, '511.2', 'Pasar', '-', 1),
(1017, '511.3', 'Pertokoan, Kaki Lima, Kios', '-', 1),
(1018, '512', 'Ekspor', '-', 1),
(1019, '513', 'Impor', '-', 1),
(1020, '514', 'Perdagangan Antar Pulau', '-', 1),
(1021, '515', 'Perdagangan Luar Negeri', '-', 1),
(1022, '516', 'Pergudangan', '-', 1),
(1023, '517', 'Aneka Usaha Perdagangan', '-', 1),
(1024, '518', 'Koperasi untuk BUUD, KUD lihat ( 412.31-412.32)', '-', 1),
(1025, '519', '-', '-', 1),
(1026, '520', 'PERTANIAN', '-', 1),
(1027, '521', 'Tanaman Pangan', '-', 1),
(1028, '521.1', 'Program', '-', 1),
(1029, '521.11', 'Bimas / Inmas Termasuk Kredit', '-', 1),
(1030, '521.12', 'Penyuluhan', '-', 1),
(1031, '521.2', 'Produksi', '-', 1),
(1032, '521.21', 'Padi / Panen', '-', 1),
(1033, '521.22', 'Palawija', '-', 1),
(1034, '521.23', 'Jagung', '-', 1),
(1035, '521.24', 'Ketela Pohon / Ubi-Ubian', '-', 1),
(1036, '521.25', 'Hortikultura', '-', 1),
(1037, '521.26', 'Sayuran / Buah-Buahan', '-', 1),
(1038, '521.27', 'Tanaman Hias', '-', 1),
(1039, '521.28', 'Pembudidayaan Rumput Laut', '-', 1),
(1040, '521.3', 'Saran Usaha Pertanian', '-', 1),
(1041, '521.31', 'Peralatan Meliputi: Traktor Dan Peralatan Lainya', '-', 1),
(1042, '521.32', 'Pembibitan', '-', 1),
(1043, '521.33', 'Pupuk', '-', 1),
(1044, '521.4', 'Perlindungan Tanaman', '-', 1),
(1045, '521.41', 'Penyakit, Penyakit Daun, Penyakit Batang Hama, Serangga, Wereng, Walang Sangit, Tungru, Tikus Dan Sejenisnya', '-', 1),
(1046, '521.42', 'Pemberantasan Hama Meliputi: Penyemprotan, Penyiangan, Geropyokan, Sparayer,', '-', 1),
(1047, '521.43', 'Pemberantasan Melalui Udara', '-', 1),
(1048, '521.44', 'Pestisida', '-', 1),
(1049, '521.5', 'Tanah Pertanian Pangan', '-', 1),
(1050, '521.51', 'Persawahan', '-', 1),
(1051, '521.52', 'Perladangan', '-', 1),
(1052, '521.53', 'Kebun', '-', 1),
(1053, '521.54', 'Rumpun Ikan Laut', '-', 1),
(1054, '521.55', 'KTA/Lahan Kritis', '-', 1),
(1055, '521.6', 'Pengusaha Petani', '-', 1),
(1056, '521.7', 'Bina Usaha', '-', 1),
(1057, '521.71', 'Pasca Panen', '-', 1),
(1058, '521.72', 'Pemasaran Hasil', '-', 1),
(1059, '522', 'Kehutanan', '-', 1),
(1060, '522.1', 'Program', '-', 1),
(1061, '522.11', 'Hak Pengusahaan Hutan', '-', 1),
(1062, '522.12', 'Tata Guna Hutan', '-', 1),
(1063, '522.13', 'Perpetaan Hutan', '-', 1),
(1064, '522.14', 'Tumpangsari', '-', 1),
(1065, '522.2', 'Produksi', '-', 1),
(1066, '522.21', 'Kayu', '-', 1),
(1067, '522.22', 'Non Kayu', '-', 1),
(1068, '522.3', 'Sarana  Usaha  Kehutanan', '-', 1),
(1069, '522.4', 'Penghijauan, Reboisasi', '-', 1),
(1070, '522.5', 'Kelestarian', '-', 1),
(1071, '522.51', 'Cagar Alam, Marga Satwa, Suaka Marga Satwa', '-', 1),
(1072, '522.52', 'Berburu Meliputi Larangan Dan Ijin Berburu', '-', 1),
(1073, '522.53', 'Kebun Binatang', '-', 1),
(1074, '522.54', 'Konservasi Lahan', '-', 1),
(1075, '522.6', 'Penyakit/Hama', '-', 1),
(1076, '522.7', 'Jenis-jenis Hutan', '-', 1),
(1077, '522.71', 'Hutan Hidup', '-', 1),
(1078, '522.72', 'Hutan Wisata', '-', 1),
(1079, '522.73', 'Hutan Produksi', '-', 1),
(1080, '522.74', 'Hutan Lindung', '-', 1),
(1081, '523', 'Perikanan', '-', 1),
(1082, '523.1', 'Program', '-', 1),
(1083, '523.11', 'Penyuluhan', '-', 1),
(1084, '523.12', 'Teknologi', '-', 1),
(1085, '523.2', 'Produksi', '-', 1),
(1086, '523.21', 'Pelelangan', '-', 1),
(1087, '523.3', 'Usaha Perikanan', '-', 1),
(1088, '523.31', 'Pembibitan', '-', 1),
(1089, '523.32', 'Daerah Penagkapan', '-', 1),
(1090, '523.33', 'Pertambakan Meliputi: ( Tambak Ikan Air Deras, Tambak Udang dll )', '-', 1),
(1091, '523.34', 'Jaring Terapung', '-', 1),
(1092, '523.4', 'Sarana', '-', 1),
(1093, '523.41', 'Peralatan', '-', 1),
(1094, '523.42', 'Kapal', '-', 1),
(1095, '523.43', 'Pelabuhan', '-', 1),
(1096, '523.5', 'Pengusaha', '-', 1),
(1097, '523.6', 'Nelayan', '-', 1),
(1098, '524', 'Peternakan', '-', 1),
(1099, '524.1', 'Produksi', '-', 1),
(1100, '524.11', 'Susu Ternak Rakyat', '-', 1),
(1101, '524.12', 'Telur', '-', 1),
(1102, '524.13', 'Daging', '-', 1),
(1103, '524.14', 'Kulit', '-', 1),
(1104, '524.2', 'Sarana Usaha Ternak', '-', 1),
(1105, '524.21', 'Pembibitan', '-', 1),
(1106, '524.22', 'Kandang Ternak', '-', 1),
(1107, '524.3', 'Kesehatan Hewan', '-', 1),
(1108, '524.31', 'Penyakit Hewan', '-', 1),
(1109, '524.32', 'Pos Kesehatan Hewan', '-', 1),
(1110, '524.33', 'Tesi Pullorum', '-', 1),
(1111, '524.34', 'Karantina', '-', 1),
(1112, '524.35', 'Pemberantasan Penyakit Hewan Termasuk Usaha Pencegahannya', '-', 1),
(1113, '524.4', 'Perunggasan', '-', 1),
(1114, '524.5', 'Pengembangan Ternak', '-', 1),
(1115, '524.51', 'Inseminasi Buatan', '-', 1),
(1116, '524.52', 'Pembibitan / Bibit Unggul', '-', 1),
(1117, '524.53', 'Penyebaran Ternak', '-', 1),
(1118, '524.6', 'Makanan Ternak', '-', 1),
(1119, '524.7', 'Tempat Pemotongan Hewan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES
(1120, '524.8', 'Data Peternakan', '-', 1),
(1121, '525', 'Perkebunan', '-', 1),
(1122, '525.1', 'Program', '-', 1),
(1123, '525.2', 'Produksi', '-', 1),
(1124, '525.21', 'Karet', '-', 1),
(1125, '525.22', 'The', '-', 1),
(1126, '525.23', 'Tembakau', '-', 1),
(1127, '525.24', 'Tebu', '-', 1),
(1128, '525.25', 'Cengkeh', '-', 1),
(1129, '525.26', 'Kopra', '-', 1),
(1130, '525.27', 'Kopi', '-', 1),
(1131, '525.28', 'Coklat', '-', 1),
(1132, '525.29', 'Aneka Tanaman', '-', 1),
(1133, '526', '-', '-', 1),
(1134, '527', '-', '-', 1),
(1135, '528', '-', '-', 1),
(1136, '530', 'PERINDUSTRIAN', '-', 1),
(1137, '530.08', 'Undang-Undang Gangguan', '-', 1),
(1138, '531', 'Industri Logam', '-', 1),
(1139, '532', 'Industri Mesin/Elektronik', '-', 1),
(1140, '533', 'Industri Kimia/Farmasi', '-', 1),
(1141, '534', 'Industri Tekstil', '-', 1),
(1142, '535', 'Industri Makanan / Minuman', '-', 1),
(1143, '536', 'Aneka Industri / Perusahaan', '-', 1),
(1144, '537', 'Aneka Kerajinan', '-', 1),
(1145, '538', 'Usaha Negara / BUMN', '-', 1),
(1146, '538.1', 'Perjan', '-', 1),
(1147, '538.2', 'Perum', '-', 1),
(1148, '538.3', 'Persero / PT, CV', '-', 1),
(1149, '539', 'Perusahaan Daerah / BUMD/BULD', '-', 1),
(1150, '540', 'PERTAMBANGAN / KESAMUDRAAN', '-', 1),
(1151, '541', 'Minyak Bumi / Bensin', '-', 1),
(1152, '541.1', 'Pengusahaan', '-', 1),
(1153, '542', 'Gas bumi', '-', 1),
(1154, '542.1', 'Eksploitasi / Pengeboran', '-', 1),
(1155, '542.11', 'Kontrak Kerja', '-', 1),
(1156, '542.2', 'Penogolahan,', 'Meliputi :Tangki, Pompa, Tanker', 1),
(1157, '543', 'Aneka Tambang', '-', 1),
(1158, '543.1', 'Timah', '-', 1),
(1159, '543.2', 'Alumunium, Boxit', '-', 1),
(1160, '543.3', 'Besi Termasuk Besi Tua', '-', 1),
(1161, '543.4', 'Tembaga', '-', 1),
(1162, '543.5', 'Batu Bara', '-', 1),
(1163, '544', 'Logam Mulia,Emas,Intan,Perak', '-', 1),
(1164, '545', 'Logam', '-', 1),
(1165, '546', 'Geologi', '-', 1),
(1166, '546.1', 'Vulkanologi', '-', 1),
(1167, '546.11', 'Pengawasan Gunung Berapi', '-', 1),
(1168, '546.2', 'Sumur Artesis, Air Bawah Tanah', '-', 1),
(1169, '547', 'Hidrologi', '-', 1),
(1170, '548', 'Kesamudraan', '-', 1),
(1171, '549', 'Pesisir Pantai', '-', 1),
(1172, '550', 'PERHUBUNGAN', '-', 1),
(1173, '551', 'Perhubungan Darat', '-', 1),
(1174, '551.1', 'Lalu Lintas Jalan Raya, Sungai, Danau', '-', 1),
(1175, '551.11', 'Keamanan Lalu Lintas, Rambu-Rambu', '-', 1),
(1176, '551.2', 'Angkutan Jalan Raya', '-', 1),
(1177, '551.21', 'Perizinan', '-', 1),
(1178, '551.22', 'Terminal', '-', 1),
(1179, '551.23', 'Alat Angkutan', '-', 1),
(1180, '551.3', 'Angkutan Sungai', '-', 1),
(1181, '551.31', 'Perizinan', '-', 1),
(1182, '551.32', 'Terminal', '-', 1),
(1183, '551.33', 'Pelabuhan', '-', 1),
(1184, '551.4', 'Angkutan Danau', '-', 1),
(1185, '551.41', 'Perizinan', '-', 1),
(1186, '551.42', 'Terminal', '-', 1),
(1187, '551.43', 'Pelabuhan', '-', 1),
(1188, '551.5', 'Feri', '-', 1),
(1189, '551.51', 'Perizinan', '-', 1),
(1190, '551.52', 'Terminal', '-', 1),
(1191, '551.53', 'Pelabuhan', '-', 1),
(1192, '551.6', 'Perkereta-Apian', '-', 1),
(1193, '552', 'Perhubungan Laut', '-', 1),
(1194, '552.1', 'Lalu Lintas Angkutan Laut, Pelayanan Umum', '-', 1),
(1195, '552.11', 'Keamanan Lalu Lintas, Rambu-Rambu', '-', 1),
(1196, '552.12', 'Pelayaran Dalam Negeri', '-', 1),
(1197, '552.13', 'Pelayaran Luar Negeri', '-', 1),
(1198, '552.2', 'Perkapalan Alat Angkutan', '-', 1),
(1199, '552.3', 'Pelabuhan', '-', 1),
(1200, '552.4', 'Pengerukan', '-', 1),
(1201, '552.5', 'Penjagaan Pantai', '-', 1),
(1202, '553', 'Perhubungan Udara', '-', 1),
(1203, '553.1', 'Lalu Lintas Udara / Keamanan Lalu Lintas Udara', '-', 1),
(1204, '553.2', 'Pelabuhan Udara', '-', 1),
(1205, '553.3', 'Alat Angkutan', '-', 1),
(1206, '554', 'Pos', '-', 1),
(1207, '555', 'Telekomunikasi', '-', 1),
(1208, '555.1', 'Telepon', '-', 1),
(1209, '555.2', 'Telegram', '-', 1),
(1210, '555.3', 'Telex / SSB, Faximile', '-', 1),
(1211, '555.4', 'Satelit, Internet', '-', 1),
(1212, '555.5', 'Stasiun Bumi, Parabola', '-', 1),
(1213, '556', 'Pariwisata dan Rekreasi', '-', 1),
(1214, '556.1', 'Obyek Kepariwisataan Taman Mini Indonesia Indah', '-', 1),
(1215, '556.2', 'Perhotelan', '-', 1),
(1216, '556.3', 'Travel service', '-', 1),
(1217, '556.4', 'Tempat Rekreasi', '-', 1),
(1218, '557', 'Meteorologi', '-', 1),
(1219, '557.1', 'Ramalan Cuaca', '-', 1),
(1220, '557.2', 'Curah Hujan', '-', 1),
(1221, '557.3', 'Kemarau Panjang', '-', 1),
(1222, '558', '-', '-', 1),
(1223, '559', '-', '-', 1),
(1224, '560', 'TENAGA KERJA', '-', 1),
(1225, '560.1', 'Pengangguran', '-', 1),
(1226, '561', 'Upah', '-', 1),
(1227, '562', 'Penempatan Tenaga Kerja, TKI', '-', 1),
(1228, '563', 'Latihan Kerja', '-', 1),
(1229, '564', 'Tenaga Kerja', '-', 1),
(1230, '564.1', 'Butsi', '-', 1),
(1231, '564.2', 'Padat Karya', '-', 1),
(1232, '565', 'Perselisihan Perburuhan', '-', 1),
(1233, '566', 'Keselamatan Kerja', '-', 1),
(1234, '567', 'Pemutusan Hubungan Kerja', '-', 1),
(1235, '568', 'kesejahteraan Buruh', '-', 1),
(1236, '569', 'Tenaga Orang Asing', '-', 1),
(1237, '570', 'PERMODALAN', '-', 1),
(1238, '571', 'Modal Domestik', '-', 1),
(1239, '572', 'Modal Asing', '-', 1),
(1240, '573', 'Modal Patungan (Joint Venture) / Penyertaan Modal', '-', 1),
(1241, '574', 'Pasar Uang Dan Modal', '-', 1),
(1242, '575', 'Saham', '-', 1),
(1243, '576', 'Belanja Modal', '-', 1),
(1244, '577', 'Modal Daerah', '-', 1),
(1245, '580', 'PERBANKAN / MONETER', '-', 1),
(1246, '581', 'Kredit', '-', 1),
(1247, '582', 'Investasi', '-', 1),
(1248, '583', 'Pembukaan ,Perubahan,Penutupan Rekening, Deposito', '-', 1),
(1249, '584', 'Bank Pembangunan Daerah', '-', 1),
(1250, '585', 'Asuransi Dana Kecelakaan Lalu Lintas', '-', 1),
(1251, '586', 'Alat Pembayaran, Cek, Giro, Wesel, Transfer', '-', 1),
(1252, '587', 'Fiskal', '-', 1),
(1253, '588', 'Hutang Negara', '-', 1),
(1254, '589', 'Moneter', '-', 1),
(1255, '590', 'AGRARIA', '-', 1),
(1256, '591', 'Tataguna Tanah', '-', 1),
(1257, '591.1', 'Pemetaan dan Pengukuran', '-', 1),
(1258, '591.2', 'Perpetaan', '-', 1),
(1259, '591.3', 'penyediaan Data', '-', 1),
(1260, '591.4', 'Fatwa Tata Guna Tanah', '-', 1),
(1261, '591.5', 'Tanah Kritis', '-', 1),
(1262, '592', 'Landreform', '-', 1),
(1263, '592.1', 'Redistribusi', '-', 1),
(1264, '592.11', 'Pendaftaran Pemilikan Dan Pengurusan', '-', 1),
(1265, '592.12', 'Penentuan Tanah Obyek Landreform', '-', 1),
(1266, '592.13', 'Pembagian Tanah Obyek Landreform', '-', 1),
(1267, '592.14', 'Sengketa Redistribusi Tanah Obyek Landreform', '-', 1),
(1268, '592.2', 'Ganti Rugi', '-', 1),
(1269, '592.21', 'Ganti Rugi Tanah Kelebihan', 'Meliputi : Sengketa Ganti Rugi Tanah Kelebihan Tanah', 1),
(1270, '592.22', 'Ganti Rugi Tanah Absentee', 'Meliputi : Sengketa Ganti Rugi Tanah Absentee', 1),
(1271, '592.23', 'Ganti Rugi Tanah Partikelir', 'Meliputi : Sengketa Ganti Rugi Tanah Partikelir', 1),
(1272, '592.3', 'Bagi Hasil', '-', 1),
(1273, '592.31', 'Penetapan Imbangan Bagi Hasil', '-', 1),
(1274, '592.32', 'Pelaksanaan Perjanjian Bagi Hasil', '-', 1),
(1275, '592.33', 'Sengketa Perjanjian Bagi Hasil', '-', 1),
(1276, '592.4', 'Gadai Tanah', '-', 1),
(1277, '592.41', 'Pendaftaran Pemilikan Dan Pengurusan', '-', 1),
(1278, '592.42', 'Pelaksanaan Gadai Tanah', '-', 1),
(1279, '592.43', 'Sengketa Gadai Tanah', '-', 1),
(1280, '592.5', 'Bimbingan dan Penyuluhan', '-', 1),
(1281, '592.6', 'Pengembangan', '-', 1),
(1282, '592.7', 'Yayasan Dana Landreform', '-', 1),
(1283, '593', 'Pengurusan Hak-Hak Tanah', '-', 1),
(1284, '593.01', 'Penyusunan Program Dan Bimbingan Teknis', '-', 1),
(1285, '593.1', 'Sewa Tanah', '-', 1),
(1286, '593.11', 'Sewa Tanah Untuk Tanaman Tertentu, Tebu, Tembakau, Rosela, Chorcorus', '-', 1),
(1287, '593.2', 'Hak Milik', '-', 1),
(1288, '593.21', 'Perorangan', '-', 1),
(1289, '593.22', 'Badan Hukum', '-', 1),
(1290, '593.3', 'Hak Pakai', '-', 1),
(1291, '593.31', 'Perorangan', '-', 1),
(1292, '593.311', 'Warga Negara Indonesia', '-', 1),
(1293, '593.312', 'Warga Negara Asing', '-', 1),
(1294, '593.32', 'Badan Hukum', '-', 1),
(1295, '593.321', 'Badan Hukum Indonesia', '-', 1),
(1296, '593.322', 'Badan Hukum Asing, Kedutaan, Konsulat Kantor Dagang Asing', '-', 1),
(1297, '593.33', 'Tanah Gedung-Gedung Negara', '-', 1),
(1298, '593.4', 'Guna Usaha', '-', 1),
(1299, '593.41', 'Perkebunan Besar', '-', 1),
(1300, '593.42', 'Perkebunan Rakyat', '-', 1),
(1301, '593.43', 'Peternakan', '-', 1),
(1302, '593.44', 'Perikanan', '-', 1),
(1303, '593.45', 'Kehutanan', '-', 1),
(1304, '593.5', 'Hak Guna Bangunan', '-', 1),
(1305, '593.51', 'Perorangan', '-', 1),
(1306, '593.52', 'Badan Hukum', '-', 1),
(1307, '593.53', 'P3MB (Panitia Pelaksana Penguasaan Milik Belanda)', '-', 1),
(1308, '593.54', 'Badan Hukum Asing Belanda-Prrk No 5165', '-', 1),
(1309, '593.55', 'Pemulihan Hak (Pen Pres 4/1960)', '-', 1),
(1310, '593.6', 'Hak Pengelolaan', '-', 1),
(1311, '593.61', 'PN Perumnas, Bonded Ware House, Industrial Estate, Real Estate', '-', 1),
(1312, '593.62', 'Perusahaan Daerah Pembangunan Perumahan', '-', 1),
(1313, '593.7', 'Sengketa Tanah', '-', 1),
(1314, '593.71', 'Peradilan Perkara Tanah', '-', 1),
(1315, '593.8', 'Pencabutan dan Pembebasan Tanah', '-', 1),
(1316, '593.81', 'Pencabutan Hak', '-', 1),
(1317, '593.82', 'Pembebasan Tanah', '-', 1),
(1318, '593.83', 'Ganti Rugi Tanah', '-', 1),
(1319, '594', 'Pendaftaran Tanah', '-', 1),
(1320, '594.1', 'Pengukuran / Pemetaan', '-', 1),
(1321, '594.11', 'Fotogrametri', '-', 1),
(1322, '594.12', 'Terristris', '-', 1),
(1323, '594.13', 'Triangulasi', '-', 1),
(1324, '594.14', 'Peralatan', '-', 1),
(1325, '594.2', 'Dana Pengukuran (Permen Agraria No. 61/1965)', '-', 1),
(1326, '594.3', 'Sertifikat', '-', 1),
(1327, '594.4', 'Pejabat Pembuat Akta Tanah (PPAT)', '-', 1),
(1328, '595', 'Lahan Transmigrasi', '-', 1),
(1329, '595.1', 'Tataguna Tanah', '-', 1),
(1330, '595.2', 'Landreform', '-', 1),
(1331, '595.3', 'Pengurusan Hak-Hak Tanah', '-', 1),
(1332, '595.4', 'Pendaftaran Tanah', '-', 1),
(1333, '596', '-', '-', 1),
(1334, '597', '-', '-', 1),
(1335, '598', '-', '-', 1),
(1336, '599', '-', '-', 1),
(1337, '600', 'PEKERJAAN UMUM DAN KETENAGAKERJAAN', '-', 1),
(1338, '601', 'Tata Bangunan Konstruksi Dan Industri Konstruksi', '-', 1),
(1339, '602', 'Kontraktor Pemborong', '-', 1),
(1340, '602.1', 'Tender', '-', 1),
(1341, '602.2', 'Pennunjukan', '-', 1),
(1342, '602.3', 'Prakualifikasi', '-', 1),
(1343, '602.31', 'Daftar Rekanan Mampu (DRM)', '-', 1),
(1344, '602.32', 'Tanda Daftar Rekanan', '-', 1),
(1345, '603', 'Arsitektur', '-', 1),
(1346, '604', 'Bahan Bangunan', '-', 1),
(1347, '604.1', 'Tanah Dan Batu Seperti: Batu Belah, Steen Slaag, Split dsb', '-', 1),
(1348, '604.2', 'Aspal, Aspal Buatan, Aspal Alam (butas)', '-', 1),
(1349, '604.3', 'Besi Dan Logam Lainnya', '-', 1),
(1350, '604.31', 'Besi Beton', '-', 1),
(1351, '604.32', 'Besi Profil', '-', 1),
(1352, '604.33', 'Paku', '-', 1),
(1353, '604.34', 'Alumunium, Profil', '-', 1),
(1354, '604.4', 'Bahan-Bahan Pelindung Dan Pengawet ', '(Cat, Tech Til, Pengawet Kayu)', 1),
(1355, '604.5', 'Semen', '-', 1),
(1356, '604.6', 'Kayu', '-', 1),
(1357, '604.7', 'Bahan Penutup Atap ', '(Genting, Asbes Gelombang, Seng Dan Sebagainya)', 1),
(1358, '604.8', 'Alat-Alat Penggantung Dan Pengunci', '-', 1),
(1359, '604.9', 'Bahan-Bahan Bangunan Lainnya', '-', 1),
(1360, '605', 'Instalasi', '-', 1),
(1361, '605.1', 'Instalasi Bangunan', '-', 1),
(1362, '605.2', 'Instalasi Listrik', '-', 1),
(1363, '605.3', 'Instalasi Air Sanitasi', '-', 1),
(1364, '605.4', 'Instalasi Pengatur Udara', '-', 1),
(1365, '605.5', 'Instalasi Akustik', '-', 1),
(1366, '605.6', 'Instalasi Cahaya / Penerangan', '-', 1),
(1367, '606', 'Konstruksi Pencegahan', '-', 1),
(1368, '606.1', 'Konstruksi Pencegahan Terhadap Kebakaran', '-', 1),
(1369, '606.2', 'Konstruksi Pencegahan Terhadap Gempa', '-', 1),
(1370, '606.3', 'Konstruksi Penegahan Terhadap Angin Udara/Panas', '-', 1),
(1371, '606.4', 'Konstruksi Pencegahan Terhadap Kegaduhan', '-', 1),
(1372, '606.5', 'Konstruksi Pencegahan Terhadap Gas/Explosive', '-', 1),
(1373, '606.6', 'Konstruksi Pencegahan Terhadap Serangga', '-', 1),
(1374, '606.7', 'Konstruksi Pencegahan Terhadap Radiasi Atom', '-', 1),
(1375, '607', '-', '-', 1),
(1376, '608', '-', '-', 1),
(1377, '609', '-', '-', 1),
(1378, '610', 'PENGAIRAN', '-', 1),
(1379, '611', 'Irigasi', '-', 1),
(1380, '611.1', 'Bangunan Waduk', '-', 1),
(1381, '611.11', 'Bendungan', '-', 1),
(1382, '611.12', 'Tanggul', '-', 1),
(1383, '611.13', 'Pelimpahan Banjir', '-', 1),
(1384, '611.14', 'Menara Pengambilan', '-', 1),
(1385, '611.2', 'Bangunan Pengambilan', '-', 1),
(1386, '611.21', 'Bendungan', '-', 1),
(1387, '611.22', 'Bendungan Dengan Pintu Bilas', '-', 1),
(1388, '611.23', 'Bendungan Dengan Pompa', '-', 1),
(1389, '611.24', 'Pengambilan Bebas', '-', 1),
(1390, '611.25', 'Pengambilan Bebas Dengan Pompa', '-', 1),
(1391, '611.26', 'Sumur Dengan Pompa', '-', 1),
(1392, '611.27', 'Kantung Lumpur', '-', 1),
(1393, '611.28', 'Slit Ekstrator', '-', 1),
(1394, '611.29', 'Escope Channel', '-', 1),
(1395, '611.3', 'Bangunan Pembawa', '-', 1),
(1396, '611.31', 'Saluran', '-', 1),
(1397, '611.311', 'Saluran Induk', '-', 1),
(1398, '611.312', 'Saluran Sekunder', '-', 1),
(1399, '611.313', 'Suplesi', '-', 1),
(1400, '611.314', 'Tersier', '-', 1),
(1401, '611.315', 'Saluran Kwarter', '-', 1),
(1402, '611.316', 'Saluran Pasangan', '-', 1),
(1403, '611.317', 'Saluran Tertutup / Terowongan', '-', 1),
(1404, '611.32', 'Bangunan', '-', 1),
(1405, '611.321', 'Bangunan Bagi', '-', 1),
(1406, '611.322', 'Bangunan Bagi Dan Sadap', '-', 1),
(1407, '611.323', 'Bangunan Sadap', '-', 1),
(1408, '611.324', 'Bangunan Check', '-', 1),
(1409, '611.325', 'Bangunan Terjun', '-', 1),
(1410, '611.33', 'Box Tersier', '-', 1),
(1411, '611.34', 'Got Miring', '-', 1),
(1412, '611.35', 'Talang', '-', 1),
(1413, '611.36', 'Syphon', '-', 1),
(1414, '611.37', 'Gorong-Gorong', '-', 1),
(1415, '611.38', 'Pelimpah Samping', '-', 1),
(1416, '611.4', 'Bangunan Pembuang', '-', 1),
(1417, '611.41', 'Saluran', '-', 1),
(1418, '611.411', 'Saluran Pembuang Induk', '-', 1),
(1419, '611.412', 'Saluran Pembuang Sekunder', '-', 1),
(1420, '611.413', 'Saluran Tersier', '-', 1),
(1421, '611.42', 'Bangunan', '-', 1),
(1422, '611.421', 'Bangunan Outlet', '-', 1),
(1423, '611.422', 'Bangunan Terjun', '-', 1),
(1424, '611.423', 'Bangunan Penahan Banjir', '-', 1),
(1425, '611.43', 'Gorong-Gorong Pembuang', '-', 1),
(1426, '611.44', 'Talang Pembuang', '-', 1),
(1427, '611.45', 'Syphon Pembuang', '-', 1),
(1428, '611.5', 'Bangunan Lainnya', '-', 1),
(1429, '611.51', 'Jalan', '-', 1),
(1430, '611.511', 'Jalan Inspeksi', '-', 1),
(1431, '611.512', 'Jalan Logistik Waduk Lapangan', '-', 1),
(1432, '611.52', 'Jembatan', '-', 1),
(1433, '611.521', 'Jembatan Inspeksi', '-', 1),
(1434, '611.522', 'Jembatan Hewan', '-', 1),
(1435, '611.53', 'Tangga Cuci', '-', 1),
(1436, '611.54', 'Kubangan Kerbau', '-', 1),
(1437, '611.55', 'Waduk Lapangan', '-', 1),
(1438, '611.56', 'Bangunan Penunjang', '-', 1),
(1439, '611.57', 'Jaringan Telepon', '-', 1),
(1440, '611.58', 'Stasiun Agro', '-', 1),
(1441, '612', 'Folder', '-', 1),
(1442, '612.1', 'Tanggul Keliling', '-', 1),
(1443, '612.11', 'Tanggul', '-', 1),
(1444, '612.12', 'Bangunan Penutup Sungai', '-', 1),
(1445, '612.13', 'Jembatan', '-', 1),
(1446, '612.2', 'Bangunan Pembawa', '-', 1),
(1447, '612.21', 'Saluran', '-', 1),
(1448, '612.211', 'Saluran Muka', '-', 1),
(1449, '612.212', 'Saluran Pembawa Waduk', '-', 1),
(1450, '612.213', 'Saluran Pembawa Sekunder', '-', 1),
(1451, '612.22', 'Stasiun Pompa Pemasukan', '-', 1),
(1452, '612.23', 'Bangunan Bagi', '-', 1),
(1453, '612.24', 'Gorong-Gorong', '-', 1),
(1454, '612.25', 'Syphon', '-', 1),
(1455, '612.3', 'Bangunan Pembuang', '-', 1),
(1456, '612.31', 'Stasiun Pompa Pembuang', '-', 1),
(1457, '612.32', 'Saluran', '-', 1),
(1458, '612.321', 'Saluran Pembuang Induk', '-', 1),
(1459, '612.322', 'Saluran Pembuang Sekunder', '-', 1),
(1460, '612.33', 'Pintu Air Pembuangan', '-', 1),
(1461, '612.34', 'Gorong-Gorong Pembuangan', '-', 1),
(1462, '612.35', 'Syphon Pembuangan', '-', 1),
(1463, '612.4', 'Bangunan Lainnya', '-', 1),
(1464, '612.41', 'Bangunan', '-', 1),
(1465, '612.411', 'Bangunan Pengukur Air', '-', 1),
(1466, '612.412', 'Bangunan Pengukur Curah Hujan', '-', 1),
(1467, '612.413', 'Bangunan Gudang Stasiun Pompa', '-', 1),
(1468, '612.414', 'Bangunan Listrik Stasiun Pompa', '-', 1),
(1469, '612.42', 'Rumah Petugas Aksploitasi', '-', 1),
(1470, '613', 'Pasang Surut', '-', 1),
(1471, '613.1', 'Bangunan Pembawa', '-', 1),
(1472, '613.11', 'Saluran', '-', 1),
(1473, '613.111', 'Saluran Pembawa Induk', '-', 1),
(1474, '613.112', 'Saluran Pembawa Sekunder', '-', 1),
(1475, '613.113', 'Saluran Pembawa Tersier', '-', 1),
(1476, '613.114', 'Saluran penyimpanan air', '-', 1),
(1477, '613.12', 'Bangunan Pintu Pemasukan', '-', 1),
(1478, '613.2', 'Bangunan Pembuang', '-', 1),
(1479, '613.21', 'Saluran', '-', 1),
(1480, '613.211', 'Saluran Pembuang Induk', '-', 1),
(1481, '613.212', 'Saluran Pembuang Sekunder', '-', 1),
(1482, '613.213', 'Saluran Pembuang Tersier', '-', 1),
(1483, '613.214', 'Saluran Pengumpul Air', '-', 1),
(1484, '613.22', 'Bangunan Pintu Pembuang', '-', 1),
(1485, '613.3', 'Bangunan Lainnya', '-', 1),
(1486, '613.31', 'Kolam Pasang', '-', 1),
(1487, '613.32', 'Saluran', '-', 1),
(1488, '613.321', 'Saluran Lalu Lintas', '-', 1),
(1489, '613.322', 'Saluran Muka', '-', 1),
(1490, '613.33', 'Bangunan', '-', 1),
(1491, '613.331', 'Bangunan Penangkis Kotoran', '-', 1),
(1492, '613.332', 'Bangunan Pengukur Muka Air', '-', 1),
(1493, '613.333', 'Bangunan Pengukur Curah Hujan', '-', 1),
(1494, '613.34', 'Jalan', '-', 1),
(1495, '613.35', 'Jembatan', '-', 1),
(1496, '614', 'Pengendalian Sungai', '-', 1),
(1497, '614.1', 'Bangunan Pengaman', '-', 1),
(1498, '614.11', 'Tanggul Banjir', '-', 1),
(1499, '614.12', 'Pintu Pengatur Banjir', '-', 1),
(1500, '614.13', 'Klep Pengatur Banjir', '-', 1),
(1501, '614.14', 'Tembok Pengaman Talud', '-', 1),
(1502, '614.15', 'Krib', '-', 1),
(1503, '614.16', 'Kantung Lumpur', '-', 1),
(1504, '614.17', 'Check-Dam', '-', 1),
(1505, '614.18', 'Syphon', '-', 1),
(1506, '614.2', 'Saluran Pengaman', '-', 1),
(1507, '614.21', 'Saluran Banjir', '-', 1),
(1508, '614.22', 'Saluran Drainage', '-', 1),
(1509, '614.23', 'Corepure', '-', 1),
(1510, '614.3', 'Bangunan Lainnya', '-', 1),
(1511, '614.31', 'Warning System', '-', 1),
(1512, '614.32', 'Stasiun', '-', 1),
(1513, '614.321', 'Stasiun Pengukur Curah Hujan', '-', 1),
(1514, '614.322', 'Stasiun Pengukur Air', '-', 1),
(1515, '614.323', 'Stasiun Pengukur Cuaca', '-', 1),
(1516, '614.324', 'Stasiun Pos Penjagaan', '-', 1),
(1517, '615', 'Pengamanan Pantai', '-', 1),
(1518, '615.1', 'Tanggul', '-', 1),
(1519, '615.2', 'Krib', '-', 1),
(1520, '615.3', 'Bangunan Lainnya', '-', 1),
(1521, '616', 'Air Tanah', '-', 1),
(1522, '616.1', 'Stasiun Pompa', '-', 1),
(1523, '616.2', 'Bangunan Pembawa', '-', 1),
(1524, '616.3', 'Bangunan Pembuang', '-', 1),
(1525, '616.4', 'Bangunan Lainnya', '-', 1),
(1526, '617', '-', '-', 1),
(1527, '618', '-', '-', 1),
(1528, '619', '-', '-', 1),
(1529, '620', 'JALAN', '-', 1),
(1530, '621', 'Jalan Kota', '-', 1),
(1531, '621.1', 'Daerah Penguasaan', '-', 1),
(1532, '621.11', 'Tanah', '-', 1),
(1533, '621.12', 'Tanaman', '-', 1),
(1534, '621.13', 'Bangunan', '-', 1),
(1535, '621.2', 'Bangunan Sementara', '-', 1),
(1536, '621.21', 'Jalan Sementara', '-', 1),
(1537, '621.22', 'Jembatan Sementara', '-', 1),
(1538, '621.23', 'Kantor Proyek', '-', 1),
(1539, '621.24', 'Gedung Proyek', '-', 1),
(1540, '621.25', 'Barak Kerja', '-', 1),
(1541, '621.26', 'Laboratorium Lapangan', '-', 1),
(1542, '621.27', 'Rumah', '-', 1),
(1543, '621.3', 'Badan Jalan', '-', 1),
(1544, '621.31', 'Pekerjaan Tanah (Earth Work)', '-', 1),
(1545, '621.32', 'Stabilisasi', '-', 1),
(1546, '621.4', 'Perkerasan', '-', 1),
(1547, '621.41', 'Lapis Pondasi Bawah', '-', 1),
(1548, '621.42', 'Lapis Pondasi', '-', 1),
(1549, '621.43', 'Lapis Permukaan', '-', 1),
(1550, '621.5', 'Drainage', '-', 1),
(1551, '621.51', 'Parit Tanah', '-', 1),
(1552, '621.52', 'Gorong-Gorong (Culvert)', '-', 1),
(1553, '621.6', 'Buku Trotuir', '-', 1),
(1554, '621.61', 'Tanah', '-', 1),
(1555, '621.62', 'Perkerasan', '-', 1),
(1556, '621.63', 'Pasangan', '-', 1),
(1557, '621.7', 'Median', '-', 1),
(1558, '621.71', 'Tanah', '-', 1),
(1559, '621.72', 'Tanaman', '-', 1),
(1560, '621.73', 'Perkerasan', '-', 1),
(1561, '621.74', 'Pasangan', '-', 1),
(1562, '621.8', 'Daerah Samping', '-', 1),
(1563, '621.81', 'Tanaman', '-', 1),
(1564, '621.82', 'Pagar', '-', 1),
(1565, '621.9', 'Bangunan Pelengkap Dan Pengamanan', '-', 1),
(1566, '621.91', 'Rambu-Rambu/Tanda-Tanda Lalu Lintas', '-', 1),
(1567, '621.92', 'Lampu Penerangan', '-', 1),
(1568, '621.93', 'Lampu Pengatur Lalu Lintas', '-', 1),
(1569, '621.94', 'Patok-Patok KM', '-', 1),
(1570, '621.95', 'Patok-Patok ROW (Sempadan)', '-', 1),
(1571, '621.96', 'Rel Pengamanan', '-', 1),
(1572, '621.97', 'Pagar', '-', 1),
(1573, '621.98', 'Turap Penahan', '-', 1),
(1574, '621.99', 'Bronjong', '-', 1),
(1575, '622', 'Jalan Luar Kota', '-', 1),
(1576, '622.1', 'Daerah Penguasaan', '-', 1),
(1577, '622.11', 'Tanah', '-', 1),
(1578, '622.12', 'Tanaman', '-', 1),
(1579, '622.13', 'Bangunan', '-', 1),
(1580, '622.2', 'Bangunan Sementara', '-', 1),
(1581, '622.21', 'Jalan Sementara', '-', 1),
(1582, '622.22', 'Jembatan Sementara', '-', 1),
(1583, '622.23', 'Kantor Proyek', '-', 1),
(1584, '622.24', 'Gudang Proyek', '-', 1),
(1585, '622.25', 'Barak Kerja', '-', 1),
(1586, '622.26', 'Laboratorium Lapangan', '-', 1),
(1587, '622.27', 'Rumah', '-', 1),
(1588, '622.3', 'Badan Jalan', '-', 1),
(1589, '622.31', 'Pekerjaan Tanah (Earth Work)', '-', 1),
(1590, '622.32', 'Stabilisasi', '-', 1),
(1591, '622.4', 'Perkerasan', '-', 1),
(1592, '622.41', 'Lapis Pondasi Bawah', '-', 1),
(1593, '622.42', 'Lapis Pondasi', '-', 1),
(1594, '622.43', 'Lapis Permukaan', '-', 1),
(1595, '622.5', 'Drainage', '-', 1),
(1596, '622.51', 'Parit', '-', 1),
(1597, '622.52', 'Gorong-Gorong (Culvert)', '-', 1),
(1598, '622.53', 'Sub Drainage', '-', 1),
(1599, '622.6', 'Trotoar', '-', 1),
(1600, '622.61', 'Tanah', '-', 1),
(1601, '622.62', 'Perkerasan', '-', 1),
(1602, '622.7', 'Median', '-', 1),
(1603, '622.71', 'Tanah', '-', 1),
(1604, '622.72', 'Tanaman', '-', 1),
(1605, '622.73', 'Perkerasan', '-', 1),
(1606, '622.74', 'Pasangan', '-', 1),
(1607, '622.8', 'Daerah Samping', '-', 1),
(1608, '622.81', 'Tanaman', '-', 1),
(1609, '622.82', 'Pagar', '-', 1),
(1610, '622.9', 'Bangunan Pelengkap Dan Pengamanan', '-', 1),
(1611, '622.91', 'Rambu-Rambu/Tanda-Tanda Lalu Lintas', '-', 1),
(1612, '622.92', 'Lampu Penerangan', '-', 1),
(1613, '622.93', 'Lampu Pengatur Lalu Lintas', '-', 1),
(1614, '622.94', 'Patok-Patok KM', '-', 1),
(1615, '622.95', 'Patok-Patok ROW (Sempadan)', '-', 1),
(1616, '622.96', 'Rel Pengamanan', '-', 1),
(1617, '622.97', 'Pagar', '-', 1),
(1618, '622.98', 'Turap Penahan', '-', 1),
(1619, '622.99', 'Bronjong', '-', 1),
(1620, '623', '-', '-', 1),
(1621, '623', '-', '-', 1),
(1622, '623', '-', '-', 1),
(1623, '630', 'JEMBATAN', '-', 1),
(1624, '631', 'Jembatan Pada Jalan Kota', '-', 1),
(1625, '631.1', 'Daerah Penguasaan', '-', 1),
(1626, '631.11', 'Tanah', '-', 1),
(1627, '631.12', 'Tanaman', '-', 1),
(1628, '631.13', 'Bangunan', '-', 1),
(1629, '631.2', 'Bangunan Sementara', '-', 1),
(1630, '631.21', 'Jalan Sementara', '-', 1),
(1631, '631.22', 'Jembatan Sementara', '-', 1),
(1632, '631.23', 'Kantor Proyek', '-', 1),
(1633, '631.24', 'Gudang Proyek', '-', 1),
(1634, '631.25', 'Barak Kerja', '-', 1),
(1635, '631.26', 'Laboratorium Lapangan', '-', 1),
(1636, '631.27', 'Rumah', '-', 1),
(1637, '631.3', 'Pekerjaan Tanah (Earth Work)', '-', 1),
(1638, '631.31', 'Galian Tanah', '-', 1),
(1639, '631.32', 'Timbunan Tanah', '-', 1),
(1640, '631.4', 'Pondasi', '-', 1),
(1641, '631.41', 'Pondasi Kepala Jalan', '-', 1),
(1642, '631.42', 'Pondasi Pilar', '-', 1),
(1643, '631.43', 'Angker', '-', 1),
(1644, '631.5', 'Bangunan Bawah', '-', 1),
(1645, '631.51', 'Kepala Jembatan', '-', 1),
(1646, '631.52', 'Pilar', '-', 1),
(1647, '631.53', 'Piloon', '-', 1),
(1648, '631.54', 'Landasan', '-', 1),
(1649, '631.6', 'Bangunan', '-', 1),
(1650, '631.61', 'Gelagar', '-', 1),
(1651, '631.62', 'Lantai', '-', 1),
(1652, '631.63', 'Perkerasan', '-', 1),
(1653, '631.64', 'Jalan Orang / Trotoar', '-', 1),
(1654, '631.65', 'Sandaran', '-', 1),
(1655, '631.66', 'Talang air', '-', 1),
(1656, '631.7', 'Bangunan / Pengaman', '-', 1),
(1657, '631.71', 'Turap Penahan', '-', 1),
(1658, '631.72', 'Bronjong', '-', 1),
(1659, '631.73', '', '-', 1),
(1660, '631.74', 'Kist Dam', '-', 1),
(1661, '631.75', 'Corepure', '-', 1),
(1662, '631.76', 'Krib', '-', 1),
(1663, '631.8', 'Bangunan Pelengkap', '-', 1),
(1664, '631.81', 'Rambu-Rambu/Tanda-Tanda Lalu Lintas', '-', 1),
(1665, '631.82', 'Lampu Penerangan', '-', 1),
(1666, '631.83', 'Lampu Pengatur Lalu Lintas', '-', 1),
(1667, '631.84', 'Patok Pengaman', '-', 1),
(1668, '631.85', 'Patok ROW (Sempadan)', '-', 1),
(1669, '631.86', 'Pagar', '-', 1),
(1670, '631.9', 'Oprit', '-', 1),
(1671, '631.91', 'Badan', '-', 1),
(1672, '631.92', 'Perkerasan', '-', 1),
(1673, '631.93', 'Drainage', '-', 1),
(1674, '631.94', 'Baku', '-', 1),
(1675, '631.95', 'Median', '-', 1),
(1676, '632', 'Jembatan Pada Jalan Luar Kota', '-', 1),
(1677, '632.1', 'Daerah Penguasaan', '-', 1),
(1678, '632.11', 'Tanah', '-', 1),
(1679, '632.12', 'Tanaman', '-', 1),
(1680, '632.13', 'Bangunan', '-', 1),
(1681, '632.2', 'Bangunan Sementara', '-', 1),
(1682, '632.21', 'Jalan Sementara', '-', 1),
(1683, '632.22', 'Jembatan Sementara', '-', 1),
(1684, '632.23', 'Kantor Proyek', '-', 1),
(1685, '632.24', 'Gudang Proyek', '-', 1),
(1686, '632.25', 'Barak Kerja', '-', 1),
(1687, '632.26', 'Laboratorium Lapangan', '-', 1),
(1688, '632.27', 'Rumah', '-', 1),
(1689, '632.3', 'Pekerjaan Tanah (Earth Work)', '-', 1),
(1690, '632.31', 'Galian Tanah', '-', 1),
(1691, '632.32', 'Timnunan Tanah', '-', 1),
(1692, '632.4', 'Pondasi', '-', 1),
(1693, '632.41', 'Pondasi Kepala Jembatan', '-', 1),
(1694, '632.42', 'Pondasi Pilar', '-', 1),
(1695, '632.43', 'Pondasi Angker', '-', 1),
(1696, '632.5', 'Bangunan Bawah', '-', 1),
(1697, '632.51', 'Kepala Jembatan', '-', 1),
(1698, '632.52', 'Pilar', '-', 1),
(1699, '632.53', 'Piloon', '-', 1),
(1700, '632.54', 'Landasan', '-', 1),
(1701, '632.6', 'Bangunan Atas', '-', 1),
(1702, '632.61', 'Gelagar', '-', 1),
(1703, '632.62', 'Lantai', '-', 1),
(1704, '632.63', 'Perkerasan', '-', 1),
(1705, '632.64', 'Jalan Orang / Trotoar', '-', 1),
(1706, '632.65', 'Sandaran', '-', 1),
(1707, '632.66', 'Talang Air', '-', 1),
(1708, '632.7', 'Bangunan Pengaman', '-', 1),
(1709, '632.71', 'Turap / Penahan', '-', 1),
(1710, '632.72', 'Bronjong', '-', 1),
(1711, '632.73', 'Stek Dam', '-', 1),
(1712, '632.74', 'Kist Dam', '-', 1),
(1713, '632.75', 'Corepure', '-', 1),
(1714, '632.76', 'Krib', '-', 1),
(1715, '632.8', 'Bangunan Pelengkap', '-', 1),
(1716, '632.81', 'Rambu-Rambu/Tanda-Tanda Lalu Lintas', '-', 1),
(1717, '632.82', 'Lampu Penerangan', '-', 1),
(1718, '632.83', 'Lampu Pengatur Lalu Lintas', '-', 1),
(1719, '632.84', 'Patok Pengaman', '-', 1),
(1720, '632.85', 'Patok ROW (Sempadan)', '-', 1),
(1721, '632.86', 'Pagar', '-', 1),
(1722, '632.9', 'Oprit', '-', 1),
(1723, '632.91', 'Badan', '-', 1),
(1724, '632.92', 'Perkerasan', '-', 1),
(1725, '632.93', 'Drainage', '-', 1),
(1726, '632.94', 'Baku', '-', 1),
(1727, '632.95', 'Median', '-', 1),
(1728, '633', '-', '-', 1),
(1729, '634', '-', '-', 1),
(1730, '635', '-', '-', 1),
(1731, '640', 'BANGUNAN', '-', 1),
(1732, '640.1', 'Gedung Pengadilan', '-', 1),
(1733, '640.2', 'Rumah Pejabat Negara', '-', 1),
(1734, '640.3', 'Gedung DPR', '-', 1),
(1735, '640.4', 'Gedung Balai Kota', '-', 1),
(1736, '640.5', 'Penjara', '-', 1),
(1737, '640.6', 'Perkantoran', '-', 1),
(1738, '642', 'Bangunan Pendidikan', '-', 1),
(1739, '642.1', 'Taman Kanak-Kanak', '-', 1),
(1740, '642.2', 'SD & SEKOLAH MENENGAH', '-', 1),
(1741, '642.3', 'Perguruan Tinggi', '-', 1),
(1742, '643', 'Bangunan Rekreasi', '-', 1),
(1743, '643.1', 'BANGUNAN OLAH RAGA', '-', 1),
(1744, '643.2', 'Gedung Kesenian', '-', 1),
(1745, '643.3', 'Gedung Pemancar', '-', 1),
(1746, '644', 'Bangunan Perdagangan', '-', 1),
(1747, '644.1', 'Pusat Perbelanjaan', '-', 1),
(1748, '644.2', 'Gedung Perdagangan', '-', 1),
(1749, '644.3', 'Bank', '-', 1),
(1750, '644.4', 'Pekantoran', '-', 1),
(1751, '645', 'Bangunan Pelayanan Umum', '-', 1),
(1752, '645.1', 'MCK', '-', 1),
(1753, '645.2', 'Gedung Parkir', '-', 1),
(1754, '645.3', 'Rumah Sakit', '-', 1),
(1755, '645.4', 'Gedung Telkom', '-', 1),
(1756, '645.5', 'Terminal Angkutan udara', '-', 1),
(1757, '645.6', 'Terminal Angkutan udara', '-', 1),
(1758, '645.7', 'Terminal Angkutan Darat', '-', 1),
(1759, '645.8', 'Bangunan Keagamaan', '-', 1),
(1760, '646', 'Bangunan Peninggalan Sejarah', '-', 1),
(1761, '646.1', 'Monumen', '-', 1),
(1762, '646.2', 'Candi', '-', 1),
(1763, '646.3', 'Keraton', '-', 1),
(1764, '646.4', 'Rumah Tradisional', '-', 1),
(1765, '647', 'Bangunan Industri', '-', 1),
(1766, '648', 'Bangunan Tempat Tinggal', '-', 1),
(1767, '648.1', 'Rumah Perkotaan', '-', 1),
(1768, '648.11', 'Inti / Sederhana', '-', 1),
(1769, '648.12', 'Sedang / Mewah', '-', 1),
(1770, '648.2', 'Rumah Pedesaan', '-', 1),
(1771, '648.21', 'Rumah Contoh', '-', 1),
(1772, '648.3', 'Real Estate', '-', 1),
(1773, '648.4', 'Bapetarum', '-', 1),
(1774, '649', 'Elemen Bangunan', '-', 1),
(1775, '649.1', 'Pondasi', '-', 1),
(1776, '649.11', 'Di Atas Tiang', '-', 1),
(1777, '649.2', 'Dinding', '-', 1),
(1778, '649.21', 'Penahan Beban', '-', 1),
(1779, '649.22', 'Tidak Menahan Beban', '-', 1),
(1780, '649.3', 'Atap', '-', 1),
(1781, '649.4', 'Lantai / Langit-Langit', '-', 1),
(1782, '649.41', 'Supended', '-', 1),
(1783, '649.42', 'Solit', '-', 1),
(1784, '649.5', 'Pintu / Jendela', '-', 1),
(1785, '649.51', 'Pintu Harmonik', '-', 1),
(1786, '649.52', 'Pintu Biasa', '-', 1),
(1787, '649.53', 'Pintu Sorong', '-', 1),
(1788, '649.54', 'Pintu Kayu', '-', 1),
(1789, '649.55', 'Jendela Sorong', '-', 1),
(1790, '649.56', 'Jendela Vertikal', '-', 1),
(1791, '650', 'TATA KOTA', '-', 1),
(1792, '651', 'Daerah Perdagangan / Pelabuhan', '-', 1),
(1793, '651.1', 'Daerah Pusat Perbelanjaan', '-', 1),
(1794, '651.2', 'Daerah Perkotaan', '-', 1),
(1795, '652', 'Daerah Pemerintah', '-', 1),
(1796, '653', 'Daerah Perumahan', '-', 1),
(1797, '653.1', 'Kepadatan Rendah', '-', 1),
(1798, '653.2', 'Kepadatan Tinggi', '-', 1),
(1799, '654', 'Daerah Industri', '-', 1),
(1800, '654.1', 'Industri Berat', '-', 1),
(1801, '654.2', 'Industri Ringan', '-', 1),
(1802, '654.3', 'Industri Ringan (Home Industry)', '-', 1),
(1803, '655', 'Daerah Rekreasi', '-', 1),
(1804, '655.1', 'Public Garden', '-', 1),
(1805, '655.2', 'Sport & Playing Fields', '-', 1),
(1806, '655.3', 'Open Space', '-', 1),
(1807, '656', 'Transportasi (Tata Letak)', '-', 1),
(1808, '656.1', 'Jaringan Jalan', '-', 1),
(1809, '656.11', 'Penerangan Jalan', '-', 1),
(1810, '656.2', 'Jaringan Kereta Api', '-', 1),
(1811, '656.3', 'Jaringan Sungai', '-', 1),
(1812, '657', 'Assaineering', '-', 1),
(1813, '657.1', 'Saluran Pengumpulan', '-', 1),
(1814, '657.2', 'Instalasi Pengolahan', '-', 1),
(1815, '657.21', 'Bangunan', '-', 1),
(1816, '657.211', 'Bangunan Penyaringan', '-', 1),
(1817, '657.212', 'Bangunan Penghancur Kotoran / Sampah', '-', 1),
(1818, '657.213', 'Bangunan Pengendap', '-', 1),
(1819, '657.214', 'Bangunan Pengering Lumpur', '-', 1),
(1820, '657.22', 'Unit Densifektan', '-', 1),
(1821, '657.23', 'Unit Perpompaan', '-', 1),
(1822, '658', 'Kesehatan Lingkungan', '-', 1),
(1823, '658.1', 'Persampahan', '-', 1),
(1824, '658.11', 'Bangunan Pengumpul', '-', 1),
(1825, '658.12', 'Bangunan Pemusnahan', '-', 1),
(1826, '658.2', 'Pengotoran Udara', '-', 1),
(1827, '658.3', 'pengotoran Air', '-', 1),
(1828, '658.31', 'Air Buangan Industri Limbah', '-', 1),
(1829, '658.4', 'Kegaduhan', '-', 1),
(1830, '658.5', 'Kebersihan Kota', '-', 1),
(1831, '659', '-', '-', 1),
(1832, '660', 'TATA LINGKUNGAN', '-', 1),
(1833, '660.1', 'Persampahan', '-', 1),
(1834, '660.2', 'Kebersihan Lingkungan', '-', 1),
(1835, '660.3', 'Pencemaran', '-', 1),
(1836, '660.31', 'Pecemaran Air', '-', 1),
(1837, '660.32', 'Pencemaran Udara', '-', 1),
(1838, '661', 'Daerah Hutan', '-', 1),
(1839, '662', 'Daerah Pertanian', '-', 1),
(1840, '663', 'Daerah Pemikiman', '-', 1),
(1841, '664', 'Pusat Pertumbuhan', '-', 1),
(1842, '665', 'Transportasi', '-', 1),
(1843, '665.1', 'Jaringan Jalan', '-', 1),
(1844, '665.2', 'Jaringan Kereta Api', '-', 1),
(1845, '665.3', 'Jaringan Sungai', '-', 1),
(1846, '666', '-', '-', 1),
(1847, '667', '-', '-', 1),
(1848, '668', '-', '-', 1),
(1849, '670', 'KETENAGAAN', '-', 1),
(1850, '671', 'Listrik', '-', 1),
(1851, '671.1', 'Kelistrikan', '-', 1),
(1852, '671.11', 'Kelisrikan PLN', '-', 1),
(1853, '671.12', 'Kelistrikan Non PLN', '-', 1),
(1854, '671.2', 'Pembangkit Tenaga Listrik', '-', 1),
(1855, '671.21', 'PLTA  ( Pembangkit  Listrik Tenaga Air )', '-', 1),
(1856, '671.22', 'PLTD  ( Pembangkit Listrik Tenaga Diesel )', '-', 1),
(1857, '671.23', 'PLTG P ( Pembangkit Listrik Tenaga Gas )', '-', 1),
(1858, '671.24', 'PLTM ( Pembangkit  Listrik Tenaga Matahari )', '-', 1),
(1859, '671.25', 'PLTN ( Pembangkit Listrik Tenaga Nuklir )', '-', 1),
(1860, '671.26', 'PLTPB ( Pembangkit Listrik Tenaga Uap )', '-', 1),
(1861, '671.3', 'Transmisi Tenaga Listrik', '-', 1),
(1862, '671.31', 'Gardu Induk/Gardu Penghubung/Gardu Trafo', '-', 1),
(1863, '671.32', 'Saluran Udara Tegangan Tinggi', '-', 1),
(1864, '671.33', 'Kabel Bawah Tanah', '-', 1),
(1865, '671.4', 'Distribusi Tenaga Listrik', '-', 1),
(1866, '671.41', 'Gardu Distribusi', '-', 1),
(1867, '671.42', 'Tegangan Rendah', '-', 1),
(1868, '671.43', 'Tegangan Menengah', '-', 1),
(1869, '671.44', 'Jaringan Bawah Tanah', '-', 1),
(1870, '671.5', 'Pengusahaan Listrik', '-', 1),
(1871, '671.51', 'Sambungan Listrik', '-', 1),
(1872, '671.52', 'Penjualan Tenaga Listrik', '-', 1),
(1873, '671.53', 'Tarif Listrik', '-', 1),
(1874, '672', 'Tenaga Air', '-', 1),
(1875, '673', 'Tenaga Minyak', '-', 1),
(1876, '674', 'Tenaga Gas', '-', 1),
(1877, '675', 'Tenaga Matahari', '-', 1),
(1878, '676', 'Tenaga Nuklir', '-', 1),
(1879, '677', 'Tenaga Panas Bumi', '-', 1),
(1880, '678', 'Tenaga Uap', '-', 1),
(1881, '679', 'Tenaga Lainya', '-', 1),
(1882, '680', 'PERALATAN', '-', 1),
(1883, '681', '-', '-', 1),
(1884, '682', '-', '-', 1),
(1885, '683', '-', '-', 1),
(1886, '690', 'AIR MINUM', '-', 1),
(1887, '691', 'Intake', '-', 1),
(1888, '691.1', 'Broncaptering', '-', 1),
(1889, '691.2', 'Sumur', '-', 1),
(1890, '691.3', 'Bendungan', '-', 1),
(1891, '691.4', 'Saringan (screen)', '-', 1),
(1892, '691.5', 'Pintu air', '-', 1),
(1893, '691.6', 'Saluran Pembawa', '-', 1),
(1894, '691.7', 'Alat Ukur', '-', 1),
(1895, '691.8', 'Perpompaan', '-', 1),
(1896, '692', 'Transmisi Air Baku', '-', 1),
(1897, '692.1', 'Perpipaan', '-', 1),
(1898, '692.2', 'Katup Udara (Air Relief)', '-', 1),
(1899, '692.3', 'Katup Penguras (Blow Off)', '-', 1),
(1900, '692.4', 'Bak Pelepas Tekanan', '-', 1),
(1901, '692.5', 'Jembatan Pipa', '-', 1),
(1902, '692.6', 'Syphon', '-', 1),
(1903, '693', 'Instalasi Pengelolaan', '-', 1),
(1904, '693.1', 'Bangunan Ukur', '-', 1),
(1905, '693.2', 'Bangunan Aerasi', '-', 1),
(1906, '693.3', 'Bangunan Pengendapan', '-', 1),
(1907, '693.4', 'Bangunan Pembubuh Bahan Kimia', '-', 1),
(1908, '693.5', 'Bangunan Pengaduk', '-', 1),
(1909, '693.6', 'Bangunan Saringan', '-', 1),
(1910, '693.7', 'Perpompaan', '-', 1),
(1911, '693.8', 'Clear Hell', '-', 1),
(1912, '694', 'Distribusi', '-', 1),
(1913, '694.1', 'Reservoir Menara Bawah Tanah', '-', 1),
(1914, '694.11', 'Menara', '-', 1),
(1915, '694.12', 'reservoir di Bawah Tanah', '-', 1),
(1916, '694.2', 'Perpipaan', '-', 1),
(1917, '694.3', 'Perpompaan', '-', 1),
(1918, '694.4', 'Jembatan Pipa', '-', 1),
(1919, '694.5', 'Syphon', '-', 1),
(1920, '694.6', 'Hydran', '-', 1),
(1921, '694.61', 'Hydran Umum', '-', 1),
(1922, '694.62', 'Hydran Kebakaran', '-', 1),
(1923, '694.7', 'Katup', '-', 1),
(1924, '694.71', 'Katup Udara (Air Relief)', '-', 1),
(1925, '694.72', 'Katup Pelepas (Blow Off)', '-', 1),
(1926, '694.8', 'Bak Pelepas Tekanan', '-', 1),
(1927, '695', '-', '-', 1),
(1928, '696', '-', '-', 1),
(1929, '697', '-', '-', 1),
(1930, '698', '-', '-', 1),
(1931, '699', '-', '-', 1),
(1932, '700', 'PENGAWASAN', '-', 1),
(1933, '701', 'Bidang Urusan Dalam', '-', 1),
(1934, '702', 'Bidang Peralatan', '-', 1),
(1935, '703', 'Bidang Kekayaan Daerah', '-', 1),
(1936, '704', 'Bidang Perpustakaan / Dokumentasi / Kearsipan Sandi', '-', 1),
(1937, '705', 'Bidang Perencanaan', '-', 1),
(1938, '706', 'Bidang Organisasi / Ketatalaksanaan', '-', 1),
(1939, '707', 'Bidang Penelitian', '-', 1),
(1940, '708', 'Bidang Konferensi', '-', 1),
(1941, '709', 'Bidang Perjalanan Dinas', '-', 1),
(1942, '710', 'BIDANG PEMERINTAHAN', '-', 1),
(1943, '711', 'Bidang Pemerintahan Pusat', '-', 1),
(1944, '712', 'Bidang Pemerintahan Provinsi', '-', 1),
(1945, '713', 'Bidang Pemerintahan Kabupaten / Kota', '-', 1),
(1946, '714', 'Bidang Pemerintahan Desa', '-', 1),
(1947, '715', 'Bidang MPR / DPR', '-', 1),
(1948, '716', 'Bidang DPRD Provinsi', '-', 1),
(1949, '717', 'Bidang DPRD Kabupaten / Kota', '-', 1),
(1950, '718', 'Bidang Hukum', '-', 1),
(1951, '719', 'Bidang Hubungan Luar Negeri', '-', 1),
(1952, '720', 'BIDANG POLITIK', '-', 1),
(1953, '721', 'Bidang Kepartaian', '-', 1),
(1954, '722', 'Bidang Organisasi Kemasyarakatan', '-', 1),
(1955, '723', 'Bidang Organisasi Profesi Dan Fungsional', '-', 1),
(1956, '724', 'Bidang Organisasi Pemuda', '-', 1),
(1957, '725', 'Bidang Organisasi Buruh, Tani, Dan Nelayan', '-', 1),
(1958, '726', 'Bidang Organisasi Wanita', '-', 1),
(1959, '727', 'Bidang Pemilihan Umum', '-', 1),
(1960, '730', 'BIDANG KEAMANAN/KETERTIBAN', '-', 1),
(1961, '731', 'Bidang Pertahanan', '-', 1),
(1962, '732', 'Bidang Kemiliteran', '-', 1),
(1963, '733', 'Bidang Perlindungan Masyarakat', '-', 1),
(1964, '734', 'Bidang Kemanan', '-', 1),
(1965, '735', 'bidang Kejahatan', '-', 1),
(1966, '736', 'Bidang Bencana', '-', 1),
(1967, '737', 'Bidang Kecelakaan', '-', 1),
(1968, '738', '-', '-', 1),
(1969, '739', '-', '-', 1),
(1970, '740', 'BIDANG KESEJAHTERAAN RAKYAT', '-', 1),
(1971, '741', 'Bidang Pembagunan Desa', '-', 1),
(1972, '742', 'Bidang Pendidikan', '-', 1),
(1973, '743', 'Bidang Kebudayaan', '-', 1),
(1974, '744', 'Bidang Kesehatan', '-', 1),
(1975, '745', 'Bidang Agama', '-', 1),
(1976, '746', 'Bidang Sosial', '-', 1),
(1977, '747', 'Bidang Kependudukan', '-', 1),
(1978, '748', 'Bidang Media Massa', '-', 1),
(1979, '749', '-', '-', 1),
(1980, '750', 'BIDANG PEREKONOMIAN', '-', 1),
(1981, '751', 'Bidang Perdagangan', '-', 1),
(1982, '752', 'Bidang Pertanian', '-', 1),
(1983, '753', 'Bidang Perindustrian', '-', 1),
(1984, '754', 'Bidang Pertambangan / Kesamudraan', '-', 1),
(1985, '755', 'Bidang Perhubungan', '-', 1),
(1986, '756', 'Bidang Tenaga Kerja', '-', 1),
(1987, '757', 'Bidang Permodalan', '-', 1),
(1988, '758', 'Bidang Perbankan / Moneter', '-', 1),
(1989, '759', 'Bidang Agraria', '-', 1),
(1990, '760', 'BIDANG PEKERJAAN UMUM', '-', 1),
(1991, '761', 'Bidang Pengairan', '-', 1),
(1992, '762', 'Bidang Jalan', '-', 1),
(1993, '763', 'Bidang Jembatan', '-', 1),
(1994, '764', 'Bidang Bangunan', '-', 1),
(1995, '765', 'Bidang Tata Kota', '-', 1),
(1996, '766', 'Bidang Lingkungan', '-', 1),
(1997, '767', 'Bidang Ketenagaan', '-', 1),
(1998, '768', 'Bidang Peralatan', '-', 1),
(1999, '769', 'Bidang Air Minum', '-', 1),
(2000, '770', '-', '-', 1),
(2001, '771', '-', '-', 1),
(2002, '772', '-', '-', 1),
(2003, '780', 'BIDANG KEPEGAWAIAN', '-', 1),
(2004, '781', 'Bidang Pengadaan Pegawai', '-', 1),
(2005, '782', 'Bidang Mutasi Pegawai', '-', 1),
(2006, '783', 'Bidang Kedudukan Pegawai', '-', 1),
(2007, '784', 'Bidang Kesejahteran Pegawai', '-', 1),
(2008, '785', 'Bidang Cuti', '-', 1),
(2009, '786', 'Bidang Penilaian', '-', 1),
(2010, '787', 'Bidang Tata Usaha Kepegawaian', '-', 1),
(2011, '788', 'Bidang Pemberhentian Pegawai', '-', 1),
(2012, '789', 'Bidang Pendidikan Pegawai', '-', 1),
(2013, '790', 'BIDANG KEUANGAN', '-', 1),
(2014, '791', 'Bidang Anggaran', '-', 1),
(2015, '792', 'Bidang Otorisasi', '-', 1),
(2016, '793', 'Bidang Verifikasi', '-', 1),
(2017, '794', 'Bidang Pembukuan', '-', 1),
(2018, '795', 'Bidang Perbendaharaan', '-', 1),
(2019, '796', 'Bidang Pembina Kebendaharaan', '-', 1),
(2020, '797', 'Bidang Pendapatan', '-', 1),
(2021, '798', '-', '-', 1),
(2022, '799', 'Bidang Bendaharaan', '-', 1),
(2023, '800', 'KEPEGAWAIAN', 'Klasifikasi Disini: Kebijaksanaan Kepegawaian', 1),
(2024, '800.1', 'Perencanaan', '-', 1),
(2025, '800.2', 'Penelitian', '-', 1),
(2026, '800.043', 'Pengaduan', '-', 1),
(2027, '800.05', 'Tim', '-', 1),
(2028, '800.07', 'Statistik', '-', 1),
(2029, '800.08', 'Peraturan Perundang-Undangan', '-', 1),
(2030, '810', 'PENGADAAN', 'Meliputi: Lamaran, Pengujian Kesehatan, Dan Pengangkatan Calon Pegawai', 1),
(2031, '811', 'Lamaran', '-', 1),
(2032, '811.1', 'Testing', '-', 1),
(2033, '811.2', 'Screening', '-', 1),
(2034, '811.3', 'Panggilan', '-', 1),
(2035, '812', 'Pengujian Kesehatan', '-', 1),
(2036, '813', 'Pengangkatan Calon Pegawai', '-', 1),
(2037, '813.1', 'Pengangkatan Calon Pegawai Golongan 1', '-', 1),
(2038, '813.2', 'Pengangkatan Calon Pegawai Golongan II', '-', 1),
(2039, '813.3', 'Pengangkatan Calon Pegawai Golongan III', '-', 1),
(2040, '813.4', 'Pengangkatan Calon Pegawai Golongan IV', '-', 1),
(2041, '813.5', 'Pengangkatan Calon Guru Inpres', '-', 1),
(2042, '814', 'Pengangkatan Tenaga Lepas', '-', 1),
(2043, '814.1', 'Pengangkatan Tenaga Bulanan / Tenaga Kontrak', '-', 1),
(2044, '814.2', 'Pengangkatan Tenaga Harian', '-', 1),
(2045, '814.3', 'Pengangkatan Tenaga Pensiunan', '-', 1),
(2046, '815', '-', '-', 1),
(2047, '816', '-', '-', 1),
(2048, '817', '-', '-', 1),
(2049, '820', 'MUTASI', 'Meliputi: Pengangkatan, Kenaikan Gaji Berkala, Kenaikan Pangkat, Pemindahan, Pelimpahan Datasering, Tugas Belajar Dan Wajib Militer', 1),
(2050, '821', 'Pengangkatan', '-', 1),
(2051, '821.1', 'Pengangkatan Menjadi Pegawai Negeri Tetap', '-', 1),
(2052, '821.11', 'Pengangkatan Menjadi Pegawai Negeri Golongan 1', '-', 1),
(2053, '821.12', 'Pengangkatan Menjadi Pegawai Negeri Golongan 2', '-', 1),
(2054, '821.13', 'Pengangkatan Menjadi Pegawai Negeri Golongan 3', '-', 1),
(2055, '821.14', 'Pengangkatan Menjadi Pegawai Negeri Golongan 4', '-', 1),
(2056, '821.15', 'Pengangkatan Menjadi Pegawai Negeri Sipil Yang Cuti Di Luar Tanggungan Negara', '-', 1),
(2057, '821.2', 'Pengangkatan Dalam Jabatan, Pembebasan Dari Jabatan, Berita Acara Serah Terima Jabatan', '-', 1),
(2058, '821.21', 'Sekjen/Dirjen/Irjen/Kabag', '-', 1),
(2059, '821.22', 'Kepala Biro/Direktur/Inspektur/Kepala Pusat/Sekretaris/Kepala Dinas/Asisten Sekwilda', '-', 1),
(2060, '821.23', 'Kepala Bagian/Kepala Sub Direktorat/Kepala Bidang/Inspektur Pembantu', '-', 1),
(2061, '821.24', 'Kepala Subbagian/Kepala Seksi/Kepala Sub Bidang/Pemeriksa', '-', 1),
(2062, '821.25', 'Residen/Pembantu Gubernur', '-', 1),
(2063, '821.26', 'Wedana/Pembantu Bupati', '-', 1),
(2064, '821.27', 'Camat', '-', 1),
(2065, '821.28', 'Lurah Administratif (Lurah Desa)', '-', 1),
(2066, '821.29', 'Jabatan Lainnya', '-', 1),
(2067, '822', 'Kenaikan Gaji Berkala', '-', 1),
(2068, '822.1', 'Pegawai Golongan 1', '-', 1),
(2069, '822.2', 'Pegawai Golongan 2', '-', 1),
(2070, '822.3', 'Pegawai Golongan 3', '-', 1),
(2071, '822.4', 'Pegawai Golongan 4', '-', 1),
(2072, '823', 'Kenaikan Pangkat / Pengangkatan', '-', 1),
(2073, '823.1', 'Pegawai Golongan 1', '-', 1),
(2074, '823.2', 'Pegawai Golongan 2', '-', 1),
(2075, '823.3', 'Pegawai Golongan 3', '-', 1),
(2076, '823.4', 'Pegawai Golongan 4', '-', 1),
(2077, '824', 'Pemindahan / Pelimpahan / Perbantuan', '-', 1),
(2078, '824.1', 'Pegawai Golongan 1', '-', 1),
(2079, '824.2', 'Pegawai Golongan 2', '-', 1),
(2080, '824.3', 'Pegawai Golongan 3', '-', 1),
(2081, '824.4', 'Pegawai Golongan 4', '-', 1),
(2082, '824.5', 'Lolos Butuh', '-', 1),
(2083, '824.6', 'Kurikulum dan Silabi', '-', 1),
(2084, '824.7', 'Proposal (TOR)', '-', 1),
(2085, '825', 'Datasering dan Penempatan Kembali', '-', 1),
(2086, '826', 'Penunjukan Tugas Belajar', '-', 1),
(2087, '826.1', 'Dalam Negeri', '-', 1),
(2088, '826.2', 'Luar Negeri', '-', 1),
(2089, '826.3', 'Tunjangan Belajar', '-', 1),
(2090, '826.4', 'Penempatan Kembali', '-', 1),
(2091, '827', 'Wajib Militer', '-', 1),
(2092, '828', 'Mutasi Dengan Instansi Lain', '-', 1),
(2093, '829', '-', '-', 1),
(2094, '830', 'KEDUDUKAN', 'Meliputi: Perhitungan Masa Kerja, Penyesuaian Pangkat/Gaji, Penghargaan Ijasah, Dan Jenjang Pangkat', 1),
(2095, '831', 'Perhitungan Masa Kerja', '-', 1),
(2096, '832', 'Penyesuaian Pangkat / Gaji', '-', 1),
(2097, '832.1', 'Pegawai Golongan 1', '-', 1),
(2098, '832.2', 'Pegawai Golongan 2', '-', 1),
(2099, '832.3', 'Pegawai Golongan 3', '-', 1),
(2100, '832.4', 'Pegawai Golongan 4', '-', 1),
(2101, '833', 'Penghargaan Ijazah / Penyesuaian', '-', 1),
(2102, '834', 'Jenjang Pangkat / Eselonering', '-', 1),
(2103, '835', '-', '-', 1),
(2104, '836', '-', '-', 1),
(2105, '837', '-', '-', 1),
(2106, '840', 'KESEJAHTERAAN PEGAWAI', 'Meliputi: Tunjangan, Dana, Perawatan Kesehatan, Koperasi, Distribusi, Permahan/Tanah, Bantuan Sosial, Rekreasi Dan Dispensasi.', 1),
(2107, '841', 'Tunjangan', '-', 1),
(2108, '841.1', 'Jabatan', '-', 1),
(2109, '841.2', 'Kehormatan', '-', 1),
(2110, '841.3', 'Kematian/Uang Duka', '-', 1),
(2111, '841.4', 'Tunjangan Hari Raya', '-', 1),
(2112, '841.5', 'Perjalanan Dinas Tetap/Cuti/Pindah', '-', 1),
(2113, '841.6', 'Keluarga', '-', 1),
(2114, '841.7', 'Sandang, Pangan, Papan (Bapertarum)', '-', 1),
(2115, '842', 'Dana', '-', 1),
(2116, '842.1', 'Taspen', '-', 1),
(2117, '842.2', 'Kesehatan', '-', 1),
(2118, '842.3', 'Asuransi', '-', 1),
(2119, '843', 'Perawatan Kesehatan', '-', 1),
(2120, '843.1', 'Poliklinik', '-', 1),
(2121, '843.2', 'Perawatan Dokter', '-', 1),
(2122, '843.3', 'Obat-Obatan', '-', 1),
(2123, '843.4', 'Keluarga Berencana', '-', 1),
(2124, '844', 'Koperasi / Distribusi', '-', 1),
(2125, '844.1', 'Distribusi Pangan', '-', 1),
(2126, '844.2', 'Distribusi Sandang', '-', 1),
(2127, '844.3', 'Distribusi Papan', '-', 1),
(2128, '845', 'Perumahan/Tanah', '-', 1),
(2129, '845.1', 'Perumahan Pegawai', '-', 1),
(2130, '845.2', 'Tanah Kapling', '-', 1),
(2131, '845.3', 'Losmen/Hotel', '-', 1),
(2132, '846', 'Bantuan Sosial', '-', 1),
(2133, '846.1', 'Bantuan Kebakaran', '-', 1),
(2134, '846.2', 'Bantuan Kebanjiran', '-', 1),
(2135, '847', '-', '-', 1),
(2136, '848', '-', '-', 1),
(2137, '849', '-', '-', 1),
(2138, '850', 'CUTI ', 'Meliputi Cuti Tahunan, Cuti Besar, Cuti Sakit, Cuti Hamil, Cuti Naik Haji, Cuti Diluar Tanggungan Negara Dan Cuti Alasan Lain', 1),
(2139, '851', 'Cuti Tahunan', '-', 1),
(2140, '852', 'Cuti Besar', '-', 1),
(2141, '853', 'Cuti Sakit', '-', 1),
(2142, '854', 'Cuti Hamil', '-', 1),
(2143, '855', 'Cuti Naik Haji/Umroh', '-', 1),
(2144, '856', 'Cuti Di Luar Tangungan Neagara', '-', 1),
(2145, '857', 'Cuti Alasan Lain/Alasan Penting', '-', 1),
(2146, '858', '-', '-', 1),
(2147, '859', '-', '-', 1),
(2148, '860', 'PENILAIAN', 'Meliputi: Penghargaan, Hukuman, Konduite, Ujian Dinas,Penilaian Kakayaan Pribadi Dan Rehabilitasi', 1),
(2149, '861', 'Penghargaan', '-', 1),
(2150, '861.1', 'Bintang/Satyalencana', '-', 1),
(2151, '861.2', 'Kenaikan Pangkat Anumerta', '-', 1),
(2152, '861.3', 'Kenaikan Gaji Istimewa', '-', 1),
(2153, '861.4', 'Hadiah Berupa Uang', '-', 1),
(2154, '861.5', 'Pegawai Teladan', '-', 1),
(2155, '862', 'Hukuman', '-', 1),
(2156, '862.1', 'Teguran Peringatan', '-', 1),
(2157, '862.2', 'Penundaan Kenaikan Gaji', '-', 1),
(2158, '862.3', 'Penurunan Pangkat', '-', 1),
(2159, '862.4', 'Pemindahan', 'Catatan: Pemberhentian Untuk Sementara Waktu Dan Pemberhentian Tidak Dengan Hormat Lihat 887 Dan 888', 1),
(2160, '863', 'Konduite, DP3, Disiplin Pegawai', '-', 1),
(2161, '864', 'Ujian Dinas', '-', 1),
(2162, '864.1', 'Tingkat 1', '-', 1),
(2163, '864.2', 'Tingkat 2', '-', 1),
(2164, '864.3', 'Tingkat 3', '-', 1),
(2165, '865', 'Penilaian Kehidupan Pegawai Negeri', 'Meliputi: Petunjuk Pelaksanaan Hidup Sederhana, Penilaian Kekayaan Pribadi ( LP2P )', 1),
(2166, '866', 'Rehabilitasi / Pengaktifan Kembali', '-', 1),
(2167, '867', '-', '-', 1),
(2168, '868', '-', '-', 1),
(2169, '869', '-', '-', 1),
(2170, '870', 'TATA USAHA KEPEGAWAIAN', 'Meliputi: Formasi, Bezetting, Registrasi,Daftar, Riwayat Hidup, Hak, Penggajian, Sumpah,/Janji Dan Korps Pegawai', 1),
(2171, '871', 'Formasi', '-', 1),
(2172, '872', 'Bezetting/Daftar Urut Kepegawaian', '-', 1),
(2173, '873', 'Registrasi', '-', 1),
(2174, '873.1', 'NIP', '-', 1),
(2175, '873.2', 'KARPEG', '-', 1),
(2176, '873.3', 'Legitiminasi/Tanda Pengenal', '-', 1),
(2177, '873.4', 'Daftar Keluarga, Perkawinan, Perceraian, Karis, Karsu', '-', 1),
(2178, '874', 'Daftar Riwayat Pekerjaan', '-', 1),
(2179, '874.1', 'Tanggal Lahir', '-', 1),
(2180, '874.2', 'Penggantian Nama', '-', 1),
(2181, '874.3', 'Izin kepartaian Organisasi', '-', 1),
(2182, '875', 'Kewenangan Mutasi Pegawai', '-', 1),
(2183, '875.1', 'Pelimpahan Wewenang', '-', 1),
(2184, '875.2', 'Specimen Tanda Tangan', '-', 1),
(2185, '876', 'Penggajian', '-', 1),
(2186, '876.1', 'SKPP', '-', 1),
(2187, '877', 'Sumpah/Janji', '-', 1),
(2188, '878', 'Korps Pegawai', '-', 1),
(2189, '879', '-', '-', 1),
(2190, '880', 'PEMBERHENTIAN PEGAWAI', 'Meliputi Atas  Pemberhentian,Permintaan Sendiri, Dengan Hak Pensiun, Karena Meninggal Dunia, Alasan Lain, Dengan Diberi Uang Pesangon, Uang Tnggu Untuk Sementara Waktu Dan Pemberhentian Tidak Dengan  Hormat', 1),
(2191, '881', 'Permintaan Sendiri', '-', 1),
(2192, '882', 'Dengan Hak Pensiun', '-', 1),
(2193, '882.1', 'Pemberhentian Dengan Hak Pensiun Pegawai Negeri Golongan 1', '-', 1),
(2194, '882.2', 'Pemberhentian Dengan Hak Pensiun Pegawai Negeri Golongan 2', '-', 1),
(2195, '882.3', 'Pemberhentian Dengan Hak Pensiun Pegawai Negeri Golongan 3', '-', 1),
(2196, '882.4', 'Pemberhentian Dengan Hak Pensiun Pegawai Negeri Golongan 4', '-', 1),
(2197, '882.5', 'Pensiun Janda / Duda', '-', 1),
(2198, '882.6', 'Pensiun Yatim Piatu', '-', 1),
(2199, '882.7', 'Uang Muka Pensiun', '-', 1),
(2200, '883', 'Karena Meninggal', '-', 1),
(2201, '883.1', 'Karena Meninggal Dalam Tugas', '-', 1),
(2202, '884', 'Alasan Lain', '-', 1),
(2203, '885', 'Uang Pesangon', '-', 1),
(2204, '886', 'Uang Tunggu', '-', 1),
(2205, '887', 'Untuk Sementara Waktu', '-', 1),
(2206, '888', 'Tidak Dengan Hormat', '-', 1),
(2207, '889', '-', '-', 1),
(2208, '890', 'PENDIDIKAN PEGAWAI', 'Meliputi: Perencanaan, Pendidikan Reguler, Pendidikan Non-Reguler, Pendidikan Ke Luar Negeri, Metode, Tenaga Pengajar, Administrasi Pendidikan, Fasilitas Sarana Pendidikan', 1),
(2209, '891', 'Perencanaan', '-', 1),
(2210, '891.1', 'Program', '-', 1),
(2211, '891.2', 'Kurikulum dan Silabi', '-', 1),
(2212, '891.3', 'Proposal ( TOR )', '-', 1),
(2213, '892', 'Pendidikan _Egular / Kader', '-', 1),
(2214, '892.1', 'IPDN / APDN', '-', 1),
(2215, '892.2', 'Kursus-Kursus Reguler', '-', 1),
(2216, '893', 'Pendidikan dan Pelatihan / Non Reguler', '-', 1),
(2217, '893.1', 'LEMHANAS', '-', 1),
(2218, '893.2', 'Pendidikan dan Pelatihan Struktural, SPATI, SPAMEN, SPAMA, ADUMLA, ADUM', '-', 1),
(2219, '893.3', 'Kursus-Kursus / Penataran', '-', 1),
(2220, '893.4', 'Diklat Tehnik, Fungsional Dan Manajemen Pemerintahan', '-', 1),
(2221, '893.5', 'Diklat Lainnya', '-', 1),
(2222, '894', 'Pendidikan Luar Negeri', '-', 1),
(2223, '894.1', 'Berkesinambungan / Berkala / Bergelar', '-', 1),
(2224, '894.2', 'Non Gelar / Diploma', '-', 1),
(2225, '895', 'Metode', '-', 1),
(2226, '895.1', 'Kuliah', '-', 1),
(2227, '895.2', 'Ceramah, Simposium', '-', 1),
(2228, '895.3', 'Diskusi, Raker, Seminar, Lokakarya, Orientasi', '-', 1),
(2229, '895.4', 'Studi Lapangan, Kkn, Widyawisata', '-', 1),
(2230, '895.5', 'Tanya Jawab / Sylabi / Modul / Kursil', '-', 1),
(2231, '895.7', 'Penugasan', '-', 1);
INSERT INTO `klasifikasi_surat` (`id`, `kode`, `nama`, `uraian`, `enabled`) VALUES
(2232, '895.8', 'Gladi', '-', 1),
(2233, '896', 'Tenaga Pengajar / Widyaiswara/Narasumber', '-', 1),
(2234, '896.1', 'Moderator', '-', 1),
(2235, '897', 'Administrasi Pendidikan', '-', 1),
(2236, '897.1', 'Tahun Pelajaran', '-', 1),
(2237, '897.2', 'Persyaratan, Pendaftaran, Testing, Ujian', '-', 1),
(2238, '897.3', 'STTP', '-', 1),
(2239, '897.4', 'Penilaian Angka Kredit', '-', 1),
(2240, '897.5', 'Laporan Pendidikan Dan Pelatihan', '-', 1),
(2241, '898', 'Fasilitas Belajar', '-', 1),
(2242, '898.1', 'Tunjangan Belajar', '-', 1),
(2243, '898.2', 'Asrama', '-', 1),
(2244, '898.3', 'Uang Makan', '-', 1),
(2245, '898.4', 'Uang Transport', '-', 1),
(2246, '898.5', 'Uang Buku', '-', 1),
(2247, '898.6', 'Uang Ujian', '-', 1),
(2248, '898.7', 'Uang Semester / Uang Kuliah', '-', 1),
(2249, '898.8', 'Uang Saku', '-', 1),
(2250, '899', 'Sarana', '-', 1),
(2251, '899.1', 'Bantuan Sarana Belajar', '-', 1),
(2252, '899.2', 'Bantuan Alat-Alat Tulis', '-', 1),
(2253, '899.3', 'Bantuan Sarana Belajar Lainnya', '-', 1),
(2254, '900', 'KEUANGAN', '-', 1),
(2255, '901', 'Nota Keuangan', '-', 1),
(2256, '902', 'APBN', '-', 1),
(2257, '903', 'APBD', '-', 1),
(2258, '904', 'APBN-P', '-', 1),
(2259, '905', 'Dana Alokasi Umum', '-', 1),
(2260, '906', 'Dana Alokasi Khusus', '-', 1),
(2261, '907', 'Dekonsentrasi (Pelimpahan Dana Dari Pusat Ke Daerah)', '-', 1),
(2262, '907', '-', '-', 1),
(2263, '908', '-', '-', 1),
(2264, '910', 'ANGGARAN', '-', 1),
(2265, '911', 'Rutin', '-', 1),
(2266, '912', 'Pembangunan', '-', 1),
(2267, '913', 'Anggaran Belanja Tambahan', '-', 1),
(2268, '914', 'Daftar Isian Kegiatan (DIK)', '-', 1),
(2269, '914.1', 'Daftar Usulan Kegiatan (DUK)', '-', 1),
(2270, '915', 'Daftar Isian Poyek (DIP)', '-', 1),
(2271, '915.1', 'Daftar Usulan Proyek (DUP)', '-', 1),
(2272, '915.2', 'Daftar Isian Pengguna Anggaran (DIPA)', '-', 1),
(2273, '916', 'Revisi Anggaran', '-', 1),
(2274, '917', '-', '-', 1),
(2275, '918', '-', '-', 1),
(2276, '920', 'OTORISASI / SKO', '-', 1),
(2277, '921', 'Rutin', '-', 1),
(2278, '922', 'Pembangunan', '-', 1),
(2279, '923', 'SIAP', '-', 1),
(2280, '924', 'Ralat SKO', '-', 1),
(2281, '925', '-', '-', 1),
(2282, '926', '-', '-', 1),
(2283, '927', '-', '-', 1),
(2284, '930', 'VERIFIKASI', '-', 1),
(2285, '931', 'SPM Rutin (daftar p8)', '-', 1),
(2286, '932', 'SPM Pembangunan (daftar p8)', '-', 1),
(2287, '933', 'Penerimaan (daftar p6. p7)', '-', 1),
(2288, '934', 'SPJ Rutin', '-', 1),
(2289, '935', 'SPJ Pembangunan', '-', 1),
(2290, '936', 'Nota Pemeriksaan', '-', 1),
(2291, '937', 'SP Pemindahan Pembukuan', '-', 1),
(2292, '938', '-', '-', 1),
(2293, '939', '-', '-', 1),
(2294, '940', 'PEMBUKUAN', '-', 1),
(2295, '941', 'Penyusunan Perhitungan Anggaran', '-', 1),
(2296, '942', 'Permintaan  Data Anggaran Laporan Fisik Pembangunan', '-', 1),
(2297, '943', 'Laporan Fisik Pembangunan', '-', 1),
(2298, '944', '-', '-', 1),
(2299, '945', '-', '-', 1),
(2300, '950', 'PERBENDAHARAAN', '-', 1),
(2301, '951', 'Tuntutan Ganti Rugi (ICW Pasal 74)', '-', 1),
(2302, '952', 'Tuntutan Bendaharawan', '-', 1),
(2303, '953', 'Penghapusan Kekayaan Negara', '-', 1),
(2304, '954', 'Pengangkatan/Penggantian Pemimpin Proyak Dan Pengangkatan/Pemberhentian Bendaharawan', '-', 1),
(2305, '955', 'Spesimen Tanda Tangan', '-', 1),
(2306, '956', 'Surat Tagihan Piutang, Ikhtisar Bulanan', '-', 1),
(2307, '957', '-', '-', 1),
(2308, '958', '-', '-', 1),
(2309, '959', '-', '-', 1),
(2310, '960', 'PEMBINAAN KEBENDAHARAAN', '-', 1),
(2311, '961', 'Pemeriksaan Kas Dan Hasil Pemeriksaan Kas', '-', 1),
(2312, '962', 'Pemeriksaan Administrasi Bendaharawan', '-', 1),
(2313, '963', 'Laporan Keuangan Bendaharawan', '-', 1),
(2314, '964', '-', '-', 1),
(2315, '965', '-', '-', 1),
(2316, '966', '-', '-', 1),
(2317, '970', 'PENDAPATAN', '-', 1),
(2318, '971', 'Perimbangan Keuangan', '-', 1),
(2319, '972', 'Subsidi', '-', 1),
(2320, '973', 'Pajak,Ipeda, IHH,IHPH', '-', 1),
(2321, '974', 'Retribusi', '-', 1),
(2322, '975', 'Bea', '-', 1),
(2323, '976', 'Cukai', '-', 1),
(2324, '977', 'Pungutan / PNBP', '-', 1),
(2325, '978', 'Bantuan Presiden, Menteri Dan Bantuan Lainnya', '-', 1),
(2326, '979', '-', '-', 1),
(2327, '980', '-', '-', 1),
(2328, '981', '-', '-', 1),
(2329, '990', 'BENDAHARAWAN', '-', 1),
(2330, '991', 'SKPP / SPP', '-', 1),
(2331, '992', 'Teguran SPJ', '-', 1),
(2332, '993', '-', '-', 1),
(2333, '994', '-', '-', 1),
(2334, '995', '-', '-', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `id` int(5) NOT NULL,
  `id_artikel` int(7) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `subjek` tinytext DEFAULT NULL,
  `komentar` text NOT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT NULL,
  `tipe` tinyint(1) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `komentar`
--

INSERT INTO `komentar` (`id`, `id_artikel`, `owner`, `email`, `subjek`, `komentar`, `tgl_upload`, `status`, `tipe`, `no_hp`, `updated_at`, `is_archived`) VALUES
(8, 95, 'Penduduk Biasa', 'penduduk@desaku.desa.id', NULL, 'Selamat atas keberhasilan Senggigi merayakan Hari Kemerdeakaan 2016!', '2016-09-13 23:09:16', 1, NULL, NULL, '2019-09-30 14:47:42', 0),
(9, 775, 'AHMAD ALLIF RIZKI', '5201140706966997', 'Tidak ada subjek pesan', 'Harap alamat keluarga kami diperbaik menjadi RT 002 Dusun Mangsit. \n\nTerima kasih.', '2016-09-14 00:44:59', 1, 1, NULL, '2019-09-30 14:47:42', 0),
(10, 775, 'DENATUL SUARTINI', '3275014601977005', 'Tidak ada subjek pesan', 'Saya ke kantor desa kemarin jam 12:30 siang, tetapi tidak ada orang. Anak kami akan pergi ke Yogyakarta untuk kuliah selama 4 tahun. Apakah perlu kami laporkan?', '2016-09-14 03:49:34', 2, 1, NULL, '2019-09-30 14:47:42', 0),
(11, 775, 'DENATUL SUARTINI', '3275014601977005', 'Tidak ada subjek pesan', 'Laporan ini tidak relevan. Hanya berisi komentar saja.', '2016-09-14 04:05:02', 2, 1, NULL, '2019-09-30 14:47:42', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontak`
--

CREATE TABLE `kontak` (
  `id_kontak` int(11) NOT NULL,
  `id_pend` int(11) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontak_grup`
--

CREATE TABLE `kontak_grup` (
  `id_grup` int(11) NOT NULL,
  `nama_grup` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `line`
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

--
-- Dumping data untuk tabel `line`
--

INSERT INTO `line` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES
(1, 'Jalan', '', '#FFCD42', 0, 1, 1),
(2, 'Jalan Raya', '', '#FFCD42', 2, 1, 1),
(3, 'Jalan Kampung', '', '', 2, 1, 1),
(4, 'Ring Road', '', '', 2, 1, 1),
(5, 'Sungai', '', '#FFFFFF', 0, 1, 1),
(6, 'Selokan', '', '', 2, 5, 1),
(7, 'Jalan setapak', '', '#d45dd6', 0, 1, 1),
(8, 'Kali', '', '#16d958', 2, 5, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_bulanan`
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
-- Dumping data untuk tabel `log_bulanan`
--

INSERT INTO `log_bulanan` (`id`, `pend`, `wni_lk`, `wni_pr`, `kk`, `tgl`, `kk_lk`, `kk_pr`, `wna_lk`, `wna_pr`) VALUES
(1, 97, 46, 51, 37, '2019-11-30 15:04:42', 28, 9, 0, 0),
(983, 97, 46, 51, 37, '2019-12-31 13:11:58', 28, 9, 0, 0),
(984, 97, 46, 51, 37, '2019-12-31 13:11:58', 28, 9, 0, 0),
(985, 97, 46, 51, 37, '2019-12-31 13:11:58', 28, 9, 0, 0),
(986, 97, 46, 51, 37, '2019-12-31 13:11:58', 28, 9, 0, 0),
(987, 97, 46, 51, 37, '2019-12-31 13:11:58', 28, 9, 0, 0),
(988, 97, 46, 51, 37, '2019-12-31 13:11:58', 28, 9, 0, 0),
(989, 97, 46, 51, 37, '2019-12-31 13:11:58', 28, 9, 0, 0),
(990, 97, 46, 51, 37, '2019-12-31 13:11:59', 28, 9, 0, 0),
(991, 97, 46, 51, 37, '2019-12-31 13:11:59', 28, 9, 0, 0),
(992, 97, 46, 51, 37, '2019-12-31 13:12:00', 28, 9, 0, 0),
(993, 97, 46, 51, 37, '2019-12-31 13:12:01', 28, 9, 0, 0),
(994, 97, 46, 51, 37, '2019-12-31 13:12:01', 28, 9, 0, 0),
(995, 97, 46, 51, 37, '2019-12-31 13:12:01', 28, 9, 0, 0),
(996, 97, 46, 51, 37, '2019-12-31 13:12:01', 28, 9, 0, 0),
(997, 97, 46, 51, 37, '2019-12-31 13:12:02', 28, 9, 0, 0),
(998, 97, 46, 51, 37, '2019-12-31 13:12:03', 28, 9, 0, 0),
(999, 97, 46, 51, 37, '2019-12-31 13:12:03', 28, 9, 0, 0),
(1000, 97, 46, 51, 37, '2019-12-31 13:12:03', 28, 9, 0, 0),
(1001, 97, 46, 51, 37, '2019-12-31 13:12:04', 28, 9, 0, 0),
(1002, 97, 46, 51, 37, '2019-12-31 13:12:05', 28, 9, 0, 0),
(1003, 97, 46, 51, 37, '2019-12-31 13:12:05', 28, 9, 0, 0),
(1004, 97, 46, 51, 37, '2019-12-31 13:12:05', 28, 9, 0, 0),
(1005, 97, 46, 51, 37, '2019-12-31 13:12:05', 28, 9, 0, 0),
(1006, 97, 46, 51, 37, '2019-12-31 13:12:06', 28, 9, 0, 0),
(1007, 97, 46, 51, 37, '2020-01-31 03:33:46', 28, 9, 0, 0),
(1008, 97, 46, 51, 37, '2020-02-29 02:27:27', 28, 9, 0, 0),
(1009, 97, 46, 51, 37, '2020-02-29 15:55:22', 28, 9, 0, 0),
(1010, 97, 46, 51, 37, '2020-02-29 15:55:22', 28, 9, 0, 0),
(1011, 97, 46, 51, 37, '2020-02-29 15:55:22', 28, 9, 0, 0),
(1012, 97, 46, 51, 37, '2020-02-29 15:55:23', 28, 9, 0, 0),
(1013, 97, 46, 51, 37, '2020-02-29 15:55:23', 28, 9, 0, 0),
(1014, 97, 46, 51, 37, '2020-02-29 15:55:26', 28, 9, 0, 0),
(1015, 97, 46, 51, 37, '2020-02-29 15:55:26', 28, 9, 0, 0),
(1016, 97, 46, 51, 37, '2020-02-29 15:55:29', 28, 9, 0, 0),
(1017, 97, 46, 51, 37, '2020-02-29 15:55:29', 28, 9, 0, 0),
(1018, 97, 46, 51, 37, '2020-02-29 15:55:32', 28, 9, 0, 0),
(1019, 97, 46, 51, 37, '2020-02-29 15:55:32', 28, 9, 0, 0),
(1020, 97, 46, 51, 37, '2020-02-29 15:55:35', 28, 9, 0, 0),
(1021, 97, 46, 51, 37, '2020-02-29 15:55:35', 28, 9, 0, 0),
(1022, 97, 46, 51, 37, '2020-02-29 15:55:38', 28, 9, 0, 0),
(1023, 97, 46, 51, 37, '2020-02-29 15:55:38', 28, 9, 0, 0),
(1024, 97, 46, 51, 37, '2020-02-29 15:55:41', 28, 9, 0, 0),
(1025, 97, 46, 51, 37, '2020-02-29 15:55:41', 28, 9, 0, 0),
(1026, 97, 46, 51, 37, '2020-02-29 15:55:44', 28, 9, 0, 0),
(1027, 97, 46, 51, 37, '2020-02-29 15:55:44', 28, 9, 0, 0),
(1028, 97, 46, 51, 37, '2020-02-29 15:55:47', 28, 9, 0, 0),
(1029, 97, 46, 51, 37, '2020-02-29 15:55:47', 28, 9, 0, 0),
(1030, 97, 46, 51, 37, '2020-02-29 15:55:50', 28, 9, 0, 0),
(1031, 97, 46, 51, 37, '2020-02-29 15:55:50', 28, 9, 0, 0),
(1032, 97, 46, 51, 37, '2020-02-29 15:55:53', 28, 9, 0, 0),
(1033, 97, 46, 51, 37, '2020-02-29 15:55:53', 28, 9, 0, 0),
(1034, 97, 46, 51, 37, '2020-02-29 15:55:56', 28, 9, 0, 0),
(1035, 97, 46, 51, 37, '2020-02-29 15:55:56', 28, 9, 0, 0),
(1036, 97, 46, 51, 37, '2020-02-29 15:55:59', 28, 9, 0, 0),
(1037, 97, 46, 51, 37, '2020-02-29 15:55:59', 28, 9, 0, 0),
(1038, 97, 46, 51, 37, '2020-02-29 15:56:02', 28, 9, 0, 0),
(1039, 97, 46, 51, 37, '2020-02-29 15:56:02', 28, 9, 0, 0),
(1040, 97, 46, 51, 37, '2020-02-29 15:56:05', 28, 9, 0, 0),
(1041, 97, 46, 51, 37, '2020-02-29 15:56:05', 28, 9, 0, 0),
(1042, 97, 46, 51, 37, '2020-02-29 15:56:08', 28, 9, 0, 0),
(1043, 97, 46, 51, 37, '2020-02-29 15:56:08', 28, 9, 0, 0),
(1044, 97, 46, 51, 37, '2020-02-29 15:56:11', 28, 9, 0, 0),
(1045, 97, 46, 51, 37, '2020-02-29 15:56:11', 28, 9, 0, 0),
(1046, 97, 46, 51, 37, '2020-02-29 15:56:14', 28, 9, 0, 0),
(1047, 97, 46, 51, 37, '2020-02-29 15:56:14', 28, 9, 0, 0),
(1048, 97, 46, 51, 37, '2020-02-29 15:56:17', 28, 9, 0, 0),
(1049, 97, 46, 51, 37, '2020-02-29 15:56:17', 28, 9, 0, 0),
(1050, 97, 46, 51, 37, '2020-02-29 15:56:20', 28, 9, 0, 0),
(1051, 97, 46, 51, 37, '2020-02-29 15:56:20', 28, 9, 0, 0),
(1052, 97, 46, 51, 37, '2020-02-29 15:56:23', 28, 9, 0, 0),
(1053, 97, 46, 51, 37, '2020-02-29 15:56:23', 28, 9, 0, 0),
(1054, 97, 46, 51, 37, '2020-02-29 15:56:26', 28, 9, 0, 0),
(1055, 97, 46, 51, 37, '2020-02-29 15:56:26', 28, 9, 0, 0),
(1056, 97, 46, 51, 37, '2020-02-29 15:56:29', 28, 9, 0, 0),
(1057, 97, 46, 51, 37, '2020-02-29 15:56:29', 28, 9, 0, 0),
(1058, 97, 46, 51, 37, '2020-02-29 15:56:32', 28, 9, 0, 0),
(1059, 97, 46, 51, 37, '2020-02-29 15:56:32', 28, 9, 0, 0),
(1060, 97, 46, 51, 37, '2020-02-29 15:56:35', 28, 9, 0, 0),
(1061, 97, 46, 51, 37, '2020-02-29 15:56:35', 28, 9, 0, 0),
(1062, 97, 46, 51, 37, '2020-02-29 15:56:38', 28, 9, 0, 0),
(1063, 97, 46, 51, 37, '2020-02-29 15:56:38', 28, 9, 0, 0),
(1064, 97, 46, 51, 37, '2020-02-29 15:56:41', 28, 9, 0, 0),
(1065, 97, 46, 51, 37, '2020-02-29 15:56:41', 28, 9, 0, 0),
(1066, 97, 46, 51, 37, '2020-02-29 15:56:41', 28, 9, 0, 0),
(1067, 97, 46, 51, 37, '2020-02-29 15:56:41', 28, 9, 0, 0),
(1068, 97, 46, 51, 37, '2020-02-29 15:56:42', 28, 9, 0, 0),
(1069, 97, 46, 51, 37, '2020-02-29 15:56:42', 28, 9, 0, 0),
(1070, 97, 46, 51, 37, '2020-02-29 15:56:45', 28, 9, 0, 0),
(1071, 97, 46, 51, 37, '2020-02-29 15:56:45', 28, 9, 0, 0),
(1072, 97, 46, 51, 37, '2020-02-29 15:56:48', 28, 9, 0, 0),
(1073, 97, 46, 51, 37, '2020-02-29 15:56:48', 28, 9, 0, 0),
(1074, 97, 46, 51, 37, '2020-02-29 15:56:51', 28, 9, 0, 0),
(1075, 97, 46, 51, 37, '2020-02-29 15:56:51', 28, 9, 0, 0),
(1076, 97, 46, 51, 37, '2020-02-29 15:56:52', 28, 9, 0, 0),
(1077, 97, 46, 51, 37, '2020-02-29 15:56:53', 28, 9, 0, 0),
(1078, 97, 46, 51, 37, '2020-02-29 15:56:53', 28, 9, 0, 0),
(1079, 97, 46, 51, 37, '2020-02-29 15:56:53', 28, 9, 0, 0),
(1080, 97, 46, 51, 37, '2020-02-29 15:56:56', 28, 9, 0, 0),
(1081, 97, 46, 51, 37, '2020-02-29 15:56:56', 28, 9, 0, 0),
(1082, 97, 46, 51, 37, '2020-02-29 15:56:57', 28, 9, 0, 0),
(1083, 97, 46, 51, 37, '2020-02-29 15:56:57', 28, 9, 0, 0),
(1084, 97, 46, 51, 37, '2020-02-29 15:56:57', 28, 9, 0, 0),
(1085, 97, 46, 51, 37, '2020-02-29 15:56:57', 28, 9, 0, 0),
(1086, 97, 46, 51, 37, '2020-02-29 15:57:00', 28, 9, 0, 0),
(1087, 97, 46, 51, 37, '2020-02-29 15:57:00', 28, 9, 0, 0),
(1088, 97, 46, 51, 37, '2020-02-29 15:57:02', 28, 9, 0, 0),
(1089, 97, 46, 51, 37, '2020-02-29 15:57:02', 28, 9, 0, 0),
(1090, 97, 46, 51, 37, '2020-02-29 15:57:02', 28, 9, 0, 0),
(1091, 97, 46, 51, 37, '2020-02-29 15:57:02', 28, 9, 0, 0),
(1092, 97, 46, 51, 37, '2020-02-29 15:57:05', 28, 9, 0, 0),
(1093, 97, 46, 51, 37, '2020-02-29 15:57:05', 28, 9, 0, 0),
(1094, 97, 46, 51, 37, '2020-02-29 15:57:08', 28, 9, 0, 0),
(1095, 97, 46, 51, 37, '2020-02-29 15:57:08', 28, 9, 0, 0),
(1096, 97, 46, 51, 37, '2020-02-29 15:57:08', 28, 9, 0, 0),
(1097, 97, 46, 51, 37, '2020-02-29 15:57:09', 28, 9, 0, 0),
(1098, 97, 46, 51, 37, '2020-02-29 15:57:09', 28, 9, 0, 0),
(1099, 97, 46, 51, 37, '2020-02-29 15:57:09', 28, 9, 0, 0),
(1100, 97, 46, 51, 37, '2020-02-29 15:57:12', 28, 9, 0, 0),
(1101, 97, 46, 51, 37, '2020-02-29 15:57:12', 28, 9, 0, 0),
(1102, 97, 46, 51, 37, '2020-02-29 15:57:15', 28, 9, 0, 0),
(1103, 97, 46, 51, 37, '2020-02-29 15:57:15', 28, 9, 0, 0),
(1104, 97, 46, 51, 37, '2020-02-29 15:57:18', 28, 9, 0, 0),
(1105, 97, 46, 51, 37, '2020-02-29 15:57:18', 28, 9, 0, 0),
(1106, 97, 46, 51, 37, '2020-02-29 15:57:21', 28, 9, 0, 0),
(1107, 97, 46, 51, 37, '2020-02-29 15:57:21', 28, 9, 0, 0),
(1108, 97, 46, 51, 37, '2020-02-29 15:57:24', 28, 9, 0, 0),
(1109, 97, 46, 51, 37, '2020-02-29 15:57:24', 28, 9, 0, 0),
(1110, 97, 46, 51, 37, '2020-02-29 15:57:27', 28, 9, 0, 0),
(1111, 97, 46, 51, 37, '2020-02-29 15:57:27', 28, 9, 0, 0),
(1112, 97, 46, 51, 37, '2020-02-29 15:57:30', 28, 9, 0, 0),
(1113, 97, 46, 51, 37, '2020-02-29 15:57:30', 28, 9, 0, 0),
(1114, 97, 46, 51, 37, '2020-02-29 15:57:33', 28, 9, 0, 0),
(1115, 97, 46, 51, 37, '2020-02-29 15:57:33', 28, 9, 0, 0),
(1116, 97, 46, 51, 37, '2020-02-29 15:57:36', 28, 9, 0, 0),
(1117, 97, 46, 51, 37, '2020-02-29 15:57:36', 28, 9, 0, 0),
(1118, 97, 46, 51, 37, '2020-02-29 15:57:39', 28, 9, 0, 0),
(1119, 97, 46, 51, 37, '2020-02-29 15:57:39', 28, 9, 0, 0),
(1120, 97, 46, 51, 37, '2020-02-29 15:57:42', 28, 9, 0, 0),
(1121, 97, 46, 51, 37, '2020-02-29 15:57:42', 28, 9, 0, 0),
(1122, 97, 46, 51, 37, '2020-02-29 15:57:45', 28, 9, 0, 0),
(1123, 97, 46, 51, 37, '2020-02-29 15:57:45', 28, 9, 0, 0),
(1124, 97, 46, 51, 37, '2020-02-29 15:57:48', 28, 9, 0, 0),
(1125, 97, 46, 51, 37, '2020-02-29 15:57:48', 28, 9, 0, 0),
(1126, 97, 46, 51, 37, '2020-02-29 15:57:49', 28, 9, 0, 0),
(1127, 97, 46, 51, 37, '2020-02-29 15:57:49', 28, 9, 0, 0),
(1128, 97, 46, 51, 37, '2020-02-29 15:57:50', 28, 9, 0, 0),
(1129, 97, 46, 51, 37, '2020-02-29 15:57:50', 28, 9, 0, 0),
(1130, 97, 46, 51, 37, '2020-02-29 15:57:53', 28, 9, 0, 0),
(1131, 97, 46, 51, 37, '2020-02-29 15:57:53', 28, 9, 0, 0),
(1132, 97, 46, 51, 37, '2020-02-29 15:57:54', 28, 9, 0, 0),
(1133, 97, 46, 51, 37, '2020-02-29 15:57:54', 28, 9, 0, 0),
(1134, 97, 46, 51, 37, '2020-02-29 15:57:55', 28, 9, 0, 0),
(1135, 97, 46, 51, 37, '2020-02-29 15:57:55', 28, 9, 0, 0),
(1136, 97, 46, 51, 37, '2020-02-29 15:57:57', 28, 9, 0, 0),
(1137, 97, 46, 51, 37, '2020-02-29 15:57:58', 28, 9, 0, 0),
(1138, 97, 46, 51, 37, '2020-02-29 15:57:58', 28, 9, 0, 0),
(1139, 97, 46, 51, 37, '2020-02-29 15:58:01', 28, 9, 0, 0),
(1140, 97, 46, 51, 37, '2020-02-29 15:58:01', 28, 9, 0, 0),
(1141, 97, 46, 51, 37, '2020-02-29 15:58:04', 28, 9, 0, 0),
(1142, 97, 46, 51, 37, '2020-02-29 15:58:04', 28, 9, 0, 0),
(1143, 97, 46, 51, 37, '2020-02-29 15:58:06', 28, 9, 0, 0),
(1144, 97, 46, 51, 37, '2020-02-29 15:58:06', 28, 9, 0, 0),
(1145, 97, 46, 51, 37, '2020-02-29 15:58:07', 28, 9, 0, 0),
(1146, 97, 46, 51, 37, '2020-02-29 15:58:07', 28, 9, 0, 0),
(1147, 97, 46, 51, 37, '2020-02-29 15:58:10', 28, 9, 0, 0),
(1148, 97, 46, 51, 37, '2020-02-29 15:58:10', 28, 9, 0, 0),
(1149, 97, 46, 51, 37, '2020-02-29 15:58:13', 28, 9, 0, 0),
(1150, 97, 46, 51, 37, '2020-02-29 15:58:13', 28, 9, 0, 0),
(1151, 97, 46, 51, 37, '2020-02-29 15:58:16', 28, 9, 0, 0),
(1152, 97, 46, 51, 37, '2020-02-29 15:58:16', 28, 9, 0, 0),
(1153, 97, 46, 51, 37, '2020-02-29 15:58:19', 28, 9, 0, 0),
(1154, 97, 46, 51, 37, '2020-02-29 15:58:19', 28, 9, 0, 0),
(1155, 97, 46, 51, 37, '2020-02-29 15:58:22', 28, 9, 0, 0),
(1156, 97, 46, 51, 37, '2020-02-29 15:58:22', 28, 9, 0, 0),
(1157, 97, 46, 51, 37, '2020-02-29 15:58:25', 28, 9, 0, 0),
(1158, 97, 46, 51, 37, '2020-02-29 15:58:25', 28, 9, 0, 0),
(1159, 97, 46, 51, 37, '2020-02-29 15:58:28', 28, 9, 0, 0),
(1160, 97, 46, 51, 37, '2020-02-29 15:58:28', 28, 9, 0, 0),
(1161, 97, 46, 51, 37, '2020-02-29 15:58:31', 28, 9, 0, 0),
(1162, 97, 46, 51, 37, '2020-02-29 15:58:31', 28, 9, 0, 0),
(1163, 97, 46, 51, 37, '2020-02-29 15:58:34', 28, 9, 0, 0),
(1164, 97, 46, 51, 37, '2020-02-29 15:58:34', 28, 9, 0, 0),
(1165, 97, 46, 51, 37, '2020-02-29 15:58:37', 28, 9, 0, 0),
(1166, 97, 46, 51, 37, '2020-02-29 15:58:37', 28, 9, 0, 0),
(1167, 97, 46, 51, 37, '2020-02-29 15:58:37', 28, 9, 0, 0),
(1168, 97, 46, 51, 37, '2020-02-29 15:58:37', 28, 9, 0, 0),
(1169, 97, 46, 51, 37, '2020-02-29 15:58:40', 28, 9, 0, 0),
(1170, 97, 46, 51, 37, '2020-02-29 15:58:40', 28, 9, 0, 0),
(1171, 97, 46, 51, 37, '2020-02-29 15:58:40', 28, 9, 0, 0),
(1172, 97, 46, 51, 37, '2020-02-29 15:58:42', 28, 9, 0, 0),
(1173, 97, 46, 51, 37, '2020-02-29 15:58:43', 28, 9, 0, 0),
(1174, 97, 46, 51, 37, '2020-02-29 15:58:43', 28, 9, 0, 0),
(1175, 97, 46, 51, 37, '2020-02-29 15:58:46', 28, 9, 0, 0),
(1176, 97, 46, 51, 37, '2020-02-29 15:58:46', 28, 9, 0, 0),
(1177, 97, 46, 51, 37, '2020-02-29 15:58:49', 28, 9, 0, 0),
(1178, 97, 46, 51, 37, '2020-02-29 15:58:49', 28, 9, 0, 0),
(1179, 97, 46, 51, 37, '2020-02-29 15:58:52', 28, 9, 0, 0),
(1180, 97, 46, 51, 37, '2020-02-29 15:58:52', 28, 9, 0, 0),
(1181, 97, 46, 51, 37, '2020-02-29 15:58:55', 28, 9, 0, 0),
(1182, 97, 46, 51, 37, '2020-02-29 15:58:55', 28, 9, 0, 0),
(1183, 97, 46, 51, 37, '2020-02-29 15:58:58', 28, 9, 0, 0),
(1184, 97, 46, 51, 37, '2020-02-29 15:58:58', 28, 9, 0, 0),
(1185, 97, 46, 51, 37, '2020-02-29 15:59:01', 28, 9, 0, 0),
(1186, 97, 46, 51, 37, '2020-02-29 15:59:01', 28, 9, 0, 0),
(1187, 97, 46, 51, 37, '2020-02-29 15:59:04', 28, 9, 0, 0),
(1188, 97, 46, 51, 37, '2020-02-29 15:59:04', 28, 9, 0, 0),
(1189, 97, 46, 51, 37, '2020-02-29 15:59:07', 28, 9, 0, 0),
(1190, 97, 46, 51, 37, '2020-02-29 15:59:07', 28, 9, 0, 0),
(1191, 97, 46, 51, 37, '2020-02-29 15:59:09', 28, 9, 0, 0),
(1192, 97, 46, 51, 37, '2020-02-29 15:59:10', 28, 9, 0, 0),
(1193, 97, 46, 51, 37, '2020-02-29 15:59:10', 28, 9, 0, 0),
(1194, 97, 46, 51, 37, '2020-02-29 15:59:10', 28, 9, 0, 0),
(1195, 97, 46, 51, 37, '2020-02-29 15:59:13', 28, 9, 0, 0),
(1196, 97, 46, 51, 37, '2020-02-29 15:59:13', 28, 9, 0, 0),
(1197, 97, 46, 51, 37, '2020-02-29 15:59:14', 28, 9, 0, 0),
(1198, 97, 46, 51, 37, '2020-02-29 15:59:14', 28, 9, 0, 0),
(1199, 97, 46, 51, 37, '2020-02-29 15:59:14', 28, 9, 0, 0),
(1200, 97, 46, 51, 37, '2020-02-29 15:59:17', 28, 9, 0, 0),
(1201, 97, 46, 51, 37, '2020-02-29 15:59:17', 28, 9, 0, 0),
(1202, 97, 46, 51, 37, '2020-02-29 15:59:18', 28, 9, 0, 0),
(1203, 97, 46, 51, 37, '2020-02-29 15:59:19', 28, 9, 0, 0),
(1204, 97, 46, 51, 37, '2020-02-29 15:59:19', 28, 9, 0, 0),
(1205, 97, 46, 51, 37, '2020-02-29 15:59:22', 28, 9, 0, 0),
(1206, 97, 46, 51, 37, '2020-02-29 15:59:22', 28, 9, 0, 0),
(1207, 97, 46, 51, 37, '2020-02-29 15:59:25', 28, 9, 0, 0),
(1208, 97, 46, 51, 37, '2020-02-29 15:59:25', 28, 9, 0, 0),
(1209, 97, 46, 51, 37, '2020-02-29 15:59:28', 28, 9, 0, 0),
(1210, 97, 46, 51, 37, '2020-02-29 15:59:28', 28, 9, 0, 0),
(1211, 97, 46, 51, 37, '2020-02-29 15:59:31', 28, 9, 0, 0),
(1212, 97, 46, 51, 37, '2020-02-29 15:59:31', 28, 9, 0, 0),
(1213, 97, 46, 51, 37, '2020-02-29 15:59:34', 28, 9, 0, 0),
(1214, 97, 46, 51, 37, '2020-02-29 15:59:34', 28, 9, 0, 0),
(1215, 97, 46, 51, 37, '2020-02-29 15:59:37', 28, 9, 0, 0),
(1216, 97, 46, 51, 37, '2020-02-29 15:59:37', 28, 9, 0, 0),
(1217, 97, 46, 51, 37, '2020-02-29 15:59:40', 28, 9, 0, 0),
(1218, 97, 46, 51, 37, '2020-02-29 15:59:40', 28, 9, 0, 0),
(1219, 97, 46, 51, 37, '2020-02-29 15:59:43', 28, 9, 0, 0),
(1220, 97, 46, 51, 37, '2020-02-29 15:59:43', 28, 9, 0, 0),
(1221, 97, 46, 51, 37, '2020-02-29 15:59:46', 28, 9, 0, 0),
(1222, 97, 46, 51, 37, '2020-02-29 15:59:46', 28, 9, 0, 0),
(1223, 97, 46, 51, 37, '2020-02-29 15:59:49', 28, 9, 0, 0),
(1224, 97, 46, 51, 37, '2020-02-29 15:59:49', 28, 9, 0, 0),
(1225, 97, 46, 51, 37, '2020-02-29 15:59:52', 28, 9, 0, 0),
(1226, 97, 46, 51, 37, '2020-02-29 15:59:52', 28, 9, 0, 0),
(1227, 97, 46, 51, 37, '2020-02-29 15:59:55', 28, 9, 0, 0),
(1228, 97, 46, 51, 37, '2020-02-29 15:59:55', 28, 9, 0, 0),
(1229, 97, 46, 51, 37, '2020-02-29 15:59:58', 28, 9, 0, 0),
(1230, 97, 46, 51, 37, '2020-02-29 15:59:58', 28, 9, 0, 0),
(1231, 97, 46, 51, 37, '2020-02-29 16:00:01', 28, 9, 0, 0),
(1232, 97, 46, 51, 37, '2020-02-29 16:00:01', 28, 9, 0, 0),
(1233, 97, 46, 51, 37, '2020-02-29 16:00:04', 28, 9, 0, 0),
(1234, 97, 46, 51, 37, '2020-02-29 16:00:04', 28, 9, 0, 0),
(1235, 97, 46, 51, 37, '2020-02-29 16:00:07', 28, 9, 0, 0),
(1236, 97, 46, 51, 37, '2020-02-29 16:00:07', 28, 9, 0, 0),
(1237, 97, 46, 51, 37, '2020-02-29 16:00:07', 28, 9, 0, 0),
(1238, 97, 46, 51, 37, '2020-02-29 16:00:07', 28, 9, 0, 0),
(1239, 97, 46, 51, 37, '2020-02-29 16:00:08', 28, 9, 0, 0),
(1240, 97, 46, 51, 37, '2020-02-29 16:00:08', 28, 9, 0, 0),
(1241, 97, 46, 51, 37, '2020-02-29 16:00:11', 28, 9, 0, 0),
(1242, 97, 46, 51, 37, '2020-02-29 16:00:11', 28, 9, 0, 0),
(1243, 97, 46, 51, 37, '2020-02-29 16:00:14', 28, 9, 0, 0),
(1244, 97, 46, 51, 37, '2020-02-29 16:00:14', 28, 9, 0, 0),
(1245, 97, 46, 51, 37, '2020-02-29 16:00:17', 28, 9, 0, 0),
(1246, 97, 46, 51, 37, '2020-02-29 16:00:17', 28, 9, 0, 0),
(1247, 97, 46, 51, 37, '2020-02-29 16:00:20', 28, 9, 0, 0),
(1248, 97, 46, 51, 37, '2020-02-29 16:00:20', 28, 9, 0, 0),
(1249, 97, 46, 51, 37, '2020-02-29 16:00:23', 28, 9, 0, 0),
(1250, 97, 46, 51, 37, '2020-02-29 16:00:23', 28, 9, 0, 0),
(1251, 97, 46, 51, 37, '2020-02-29 16:00:26', 28, 9, 0, 0),
(1252, 97, 46, 51, 37, '2020-02-29 16:00:26', 28, 9, 0, 0),
(1253, 97, 46, 51, 37, '2020-02-29 16:00:29', 28, 9, 0, 0),
(1254, 97, 46, 51, 37, '2020-02-29 16:00:29', 28, 9, 0, 0),
(1255, 97, 46, 51, 37, '2020-02-29 16:00:32', 28, 9, 0, 0),
(1256, 97, 46, 51, 37, '2020-02-29 16:00:32', 28, 9, 0, 0),
(1257, 97, 46, 51, 37, '2020-02-29 16:00:35', 28, 9, 0, 0),
(1258, 97, 46, 51, 37, '2020-02-29 16:00:35', 28, 9, 0, 0),
(1259, 97, 46, 51, 37, '2020-02-29 16:00:38', 28, 9, 0, 0),
(1260, 97, 46, 51, 37, '2020-02-29 16:00:38', 28, 9, 0, 0),
(1261, 97, 46, 51, 37, '2020-02-29 16:00:41', 28, 9, 0, 0),
(1262, 97, 46, 51, 37, '2020-02-29 16:00:41', 28, 9, 0, 0),
(1263, 97, 46, 51, 37, '2020-02-29 16:00:44', 28, 9, 0, 0),
(1264, 97, 46, 51, 37, '2020-02-29 16:00:44', 28, 9, 0, 0),
(1265, 97, 46, 51, 37, '2020-02-29 16:00:47', 28, 9, 0, 0),
(1266, 97, 46, 51, 37, '2020-02-29 16:00:47', 28, 9, 0, 0),
(1267, 97, 46, 51, 37, '2020-02-29 16:00:50', 28, 9, 0, 0),
(1268, 97, 46, 51, 37, '2020-02-29 16:00:50', 28, 9, 0, 0),
(1269, 97, 46, 51, 37, '2020-02-29 16:00:53', 28, 9, 0, 0),
(1270, 97, 46, 51, 37, '2020-02-29 16:00:53', 28, 9, 0, 0),
(1271, 97, 46, 51, 37, '2020-02-29 16:00:56', 28, 9, 0, 0),
(1272, 97, 46, 51, 37, '2020-02-29 16:00:56', 28, 9, 0, 0),
(1273, 97, 46, 51, 37, '2020-02-29 16:00:59', 28, 9, 0, 0),
(1274, 97, 46, 51, 37, '2020-02-29 16:00:59', 28, 9, 0, 0),
(1275, 97, 46, 51, 37, '2020-02-29 16:01:02', 28, 9, 0, 0),
(1276, 97, 46, 51, 37, '2020-02-29 16:01:02', 28, 9, 0, 0),
(1277, 97, 46, 51, 37, '2020-02-29 16:01:05', 28, 9, 0, 0),
(1278, 97, 46, 51, 37, '2020-02-29 16:01:05', 28, 9, 0, 0),
(1279, 97, 46, 51, 37, '2020-02-29 16:01:08', 28, 9, 0, 0),
(1280, 97, 46, 51, 37, '2020-02-29 16:01:08', 28, 9, 0, 0),
(1281, 97, 46, 51, 37, '2020-02-29 16:01:11', 28, 9, 0, 0),
(1282, 97, 46, 51, 37, '2020-02-29 16:01:11', 28, 9, 0, 0),
(1283, 97, 46, 51, 37, '2020-02-29 16:01:14', 28, 9, 0, 0),
(1284, 97, 46, 51, 37, '2020-02-29 16:01:14', 28, 9, 0, 0),
(1285, 97, 46, 51, 37, '2020-02-29 16:01:17', 28, 9, 0, 0),
(1286, 97, 46, 51, 37, '2020-02-29 16:01:17', 28, 9, 0, 0),
(1287, 97, 46, 51, 37, '2020-02-29 16:01:20', 28, 9, 0, 0),
(1288, 97, 46, 51, 37, '2020-02-29 16:01:20', 28, 9, 0, 0),
(1289, 97, 46, 51, 37, '2020-02-29 16:01:23', 28, 9, 0, 0),
(1290, 97, 46, 51, 37, '2020-02-29 16:01:23', 28, 9, 0, 0),
(1291, 97, 46, 51, 37, '2020-02-29 16:01:26', 28, 9, 0, 0),
(1292, 97, 46, 51, 37, '2020-02-29 16:01:26', 28, 9, 0, 0),
(1293, 97, 46, 51, 37, '2020-02-29 16:01:29', 28, 9, 0, 0),
(1294, 97, 46, 51, 37, '2020-02-29 16:01:29', 28, 9, 0, 0),
(1295, 97, 46, 51, 37, '2020-02-29 16:01:32', 28, 9, 0, 0),
(1296, 97, 46, 51, 37, '2020-02-29 16:01:32', 28, 9, 0, 0),
(1297, 97, 46, 51, 37, '2020-02-29 16:01:35', 28, 9, 0, 0),
(1298, 97, 46, 51, 37, '2020-02-29 16:01:35', 28, 9, 0, 0),
(1299, 97, 46, 51, 37, '2020-02-29 16:01:38', 28, 9, 0, 0),
(1300, 97, 46, 51, 37, '2020-02-29 16:01:38', 28, 9, 0, 0),
(1301, 97, 46, 51, 37, '2020-02-29 16:01:41', 28, 9, 0, 0),
(1302, 97, 46, 51, 37, '2020-02-29 16:01:41', 28, 9, 0, 0),
(1303, 97, 46, 51, 37, '2020-02-29 16:01:44', 28, 9, 0, 0),
(1304, 97, 46, 51, 37, '2020-02-29 16:01:44', 28, 9, 0, 0),
(1305, 97, 46, 51, 37, '2020-02-29 16:01:47', 28, 9, 0, 0),
(1306, 97, 46, 51, 37, '2020-02-29 16:01:47', 28, 9, 0, 0),
(1307, 97, 46, 51, 37, '2020-02-29 16:01:50', 28, 9, 0, 0),
(1308, 97, 46, 51, 37, '2020-02-29 16:01:50', 28, 9, 0, 0),
(1309, 97, 46, 51, 37, '2020-02-29 16:01:53', 28, 9, 0, 0),
(1310, 97, 46, 51, 37, '2020-02-29 16:01:53', 28, 9, 0, 0),
(1311, 97, 46, 51, 37, '2020-02-29 16:01:56', 28, 9, 0, 0),
(1312, 97, 46, 51, 37, '2020-02-29 16:01:56', 28, 9, 0, 0),
(1313, 97, 46, 51, 37, '2020-02-29 16:01:59', 28, 9, 0, 0),
(1314, 97, 46, 51, 37, '2020-02-29 16:01:59', 28, 9, 0, 0),
(1315, 97, 46, 51, 37, '2020-02-29 16:02:02', 28, 9, 0, 0),
(1316, 97, 46, 51, 37, '2020-02-29 16:02:02', 28, 9, 0, 0),
(1317, 97, 46, 51, 37, '2020-02-29 16:02:05', 28, 9, 0, 0),
(1318, 97, 46, 51, 37, '2020-02-29 16:02:05', 28, 9, 0, 0),
(1319, 97, 46, 51, 37, '2020-02-29 16:02:09', 28, 9, 0, 0),
(1320, 97, 46, 51, 37, '2020-02-29 16:02:09', 28, 9, 0, 0),
(1321, 97, 46, 51, 37, '2020-02-29 16:02:12', 28, 9, 0, 0),
(1322, 97, 46, 51, 37, '2020-02-29 16:02:12', 28, 9, 0, 0),
(1323, 97, 46, 51, 37, '2020-02-29 16:02:15', 28, 9, 0, 0),
(1324, 97, 46, 51, 37, '2020-02-29 16:02:15', 28, 9, 0, 0),
(1325, 97, 46, 51, 37, '2020-02-29 16:02:17', 28, 9, 0, 0),
(1326, 97, 46, 51, 37, '2020-02-29 16:02:17', 28, 9, 0, 0),
(1327, 97, 46, 51, 37, '2020-02-29 16:02:21', 28, 9, 0, 0),
(1328, 97, 46, 51, 37, '2020-02-29 16:02:21', 28, 9, 0, 0),
(1329, 97, 46, 51, 37, '2020-02-29 16:02:23', 28, 9, 0, 0),
(1330, 97, 46, 51, 37, '2020-02-29 16:02:24', 28, 9, 0, 0),
(1331, 97, 46, 51, 37, '2020-02-29 16:02:27', 28, 9, 0, 0),
(1332, 97, 46, 51, 37, '2020-02-29 16:02:27', 28, 9, 0, 0),
(1333, 97, 46, 51, 37, '2020-02-29 16:02:29', 28, 9, 0, 0),
(1334, 97, 46, 51, 37, '2020-02-29 16:02:30', 28, 9, 0, 0),
(1335, 97, 46, 51, 37, '2020-02-29 16:02:33', 28, 9, 0, 0),
(1336, 97, 46, 51, 37, '2020-02-29 16:02:33', 28, 9, 0, 0),
(1337, 97, 46, 51, 37, '2020-02-29 16:02:36', 28, 9, 0, 0),
(1338, 97, 46, 51, 37, '2020-02-29 16:02:36', 28, 9, 0, 0),
(1339, 97, 46, 51, 37, '2020-02-29 16:02:39', 28, 9, 0, 0),
(1340, 97, 46, 51, 37, '2020-02-29 16:02:39', 28, 9, 0, 0),
(1341, 97, 46, 51, 37, '2020-02-29 16:02:42', 28, 9, 0, 0),
(1342, 97, 46, 51, 37, '2020-02-29 16:02:42', 28, 9, 0, 0),
(1343, 97, 46, 51, 37, '2020-02-29 16:02:44', 28, 9, 0, 0),
(1344, 97, 46, 51, 37, '2020-02-29 16:02:45', 28, 9, 0, 0),
(1345, 97, 46, 51, 37, '2020-02-29 16:02:48', 28, 9, 0, 0),
(1346, 97, 46, 51, 37, '2020-02-29 16:02:48', 28, 9, 0, 0),
(1347, 97, 46, 51, 37, '2020-02-29 16:02:51', 28, 9, 0, 0),
(1348, 97, 46, 51, 37, '2020-02-29 16:02:51', 28, 9, 0, 0),
(1349, 97, 46, 51, 37, '2020-02-29 16:02:53', 28, 9, 0, 0),
(1350, 97, 46, 51, 37, '2020-02-29 16:02:53', 28, 9, 0, 0),
(1351, 97, 46, 51, 37, '2020-02-29 16:02:57', 28, 9, 0, 0),
(1352, 97, 46, 51, 37, '2020-02-29 16:02:57', 28, 9, 0, 0),
(1353, 97, 46, 51, 37, '2020-02-29 16:02:59', 28, 9, 0, 0),
(1354, 97, 46, 51, 37, '2020-02-29 16:03:00', 28, 9, 0, 0),
(1355, 97, 46, 51, 37, '2020-02-29 16:03:03', 28, 9, 0, 0),
(1356, 97, 46, 51, 37, '2020-02-29 16:03:03', 28, 9, 0, 0),
(1357, 97, 46, 51, 37, '2020-02-29 16:03:05', 28, 9, 0, 0),
(1358, 97, 46, 51, 37, '2020-02-29 16:03:06', 28, 9, 0, 0),
(1359, 97, 46, 51, 37, '2020-02-29 16:03:08', 28, 9, 0, 0),
(1360, 97, 46, 51, 37, '2020-02-29 16:03:09', 28, 9, 0, 0),
(1361, 97, 46, 51, 37, '2020-02-29 16:03:12', 28, 9, 0, 0),
(1362, 97, 46, 51, 37, '2020-02-29 16:03:12', 28, 9, 0, 0),
(1363, 97, 46, 51, 37, '2020-02-29 16:03:15', 28, 9, 0, 0),
(1364, 97, 46, 51, 37, '2020-02-29 16:03:15', 28, 9, 0, 0),
(1365, 97, 46, 51, 37, '2020-02-29 16:03:18', 28, 9, 0, 0),
(1366, 97, 46, 51, 37, '2020-02-29 16:03:18', 28, 9, 0, 0),
(1367, 97, 46, 51, 37, '2020-02-29 16:03:21', 28, 9, 0, 0),
(1368, 97, 46, 51, 37, '2020-02-29 16:03:21', 28, 9, 0, 0),
(1369, 97, 46, 51, 37, '2020-02-29 16:03:24', 28, 9, 0, 0),
(1370, 97, 46, 51, 37, '2020-02-29 16:03:24', 28, 9, 0, 0),
(1371, 97, 46, 51, 37, '2020-02-29 16:03:27', 28, 9, 0, 0),
(1372, 97, 46, 51, 37, '2020-02-29 16:03:27', 28, 9, 0, 0),
(1373, 97, 46, 51, 37, '2020-02-29 16:03:30', 28, 9, 0, 0),
(1374, 97, 46, 51, 37, '2020-02-29 16:03:30', 28, 9, 0, 0),
(1375, 97, 46, 51, 37, '2020-02-29 16:03:33', 28, 9, 0, 0),
(1376, 97, 46, 51, 37, '2020-02-29 16:03:33', 28, 9, 0, 0),
(1377, 97, 46, 51, 37, '2020-02-29 16:03:36', 28, 9, 0, 0),
(1378, 97, 46, 51, 37, '2020-02-29 16:03:36', 28, 9, 0, 0),
(1379, 97, 46, 51, 37, '2020-02-29 16:03:38', 28, 9, 0, 0),
(1380, 97, 46, 51, 37, '2020-02-29 16:03:38', 28, 9, 0, 0),
(1381, 97, 46, 51, 37, '2020-02-29 16:03:42', 28, 9, 0, 0),
(1382, 97, 46, 51, 37, '2020-02-29 16:03:42', 28, 9, 0, 0),
(1383, 97, 46, 51, 37, '2020-02-29 16:03:45', 28, 9, 0, 0),
(1384, 97, 46, 51, 37, '2020-02-29 16:03:45', 28, 9, 0, 0),
(1385, 97, 46, 51, 37, '2020-02-29 16:03:47', 28, 9, 0, 0),
(1386, 97, 46, 51, 37, '2020-02-29 16:03:47', 28, 9, 0, 0),
(1387, 97, 46, 51, 37, '2020-02-29 16:03:51', 28, 9, 0, 0),
(1388, 97, 46, 51, 37, '2020-02-29 16:03:51', 28, 9, 0, 0),
(1389, 97, 46, 51, 37, '2020-02-29 16:03:53', 28, 9, 0, 0),
(1390, 97, 46, 51, 37, '2020-02-29 16:03:53', 28, 9, 0, 0),
(1391, 97, 46, 51, 37, '2020-02-29 16:03:57', 28, 9, 0, 0),
(1392, 97, 46, 51, 37, '2020-02-29 16:03:57', 28, 9, 0, 0),
(1393, 97, 46, 51, 37, '2020-02-29 16:03:59', 28, 9, 0, 0),
(1394, 97, 46, 51, 37, '2020-02-29 16:03:59', 28, 9, 0, 0),
(1395, 97, 46, 51, 37, '2020-02-29 16:04:02', 28, 9, 0, 0),
(1396, 97, 46, 51, 37, '2020-02-29 16:04:03', 28, 9, 0, 0),
(1397, 97, 46, 51, 37, '2020-02-29 16:04:06', 28, 9, 0, 0),
(1398, 97, 46, 51, 37, '2020-02-29 16:04:06', 28, 9, 0, 0),
(1399, 97, 46, 51, 37, '2020-02-29 16:04:09', 28, 9, 0, 0),
(1400, 97, 46, 51, 37, '2020-02-29 16:04:09', 28, 9, 0, 0),
(1401, 97, 46, 51, 37, '2020-02-29 16:04:11', 28, 9, 0, 0),
(1402, 97, 46, 51, 37, '2020-02-29 16:04:11', 28, 9, 0, 0),
(1403, 97, 46, 51, 37, '2020-02-29 16:04:15', 28, 9, 0, 0),
(1404, 97, 46, 51, 37, '2020-02-29 16:04:15', 28, 9, 0, 0),
(1405, 97, 46, 51, 37, '2020-02-29 16:04:17', 28, 9, 0, 0),
(1406, 97, 46, 51, 37, '2020-02-29 16:04:17', 28, 9, 0, 0),
(1407, 97, 46, 51, 37, '2020-02-29 16:04:21', 28, 9, 0, 0),
(1408, 97, 46, 51, 37, '2020-02-29 16:04:21', 28, 9, 0, 0),
(1409, 97, 46, 51, 37, '2020-02-29 16:04:23', 28, 9, 0, 0),
(1410, 97, 46, 51, 37, '2020-02-29 16:04:23', 28, 9, 0, 0),
(1411, 97, 46, 51, 37, '2020-02-29 16:04:26', 28, 9, 0, 0),
(1412, 97, 46, 51, 37, '2020-02-29 16:04:26', 28, 9, 0, 0),
(1413, 97, 46, 51, 37, '2020-02-29 16:04:29', 28, 9, 0, 0),
(1414, 97, 46, 51, 37, '2020-02-29 16:04:29', 28, 9, 0, 0),
(1415, 97, 46, 51, 37, '2020-02-29 16:04:32', 28, 9, 0, 0),
(1416, 97, 46, 51, 37, '2020-02-29 16:04:32', 28, 9, 0, 0),
(1417, 97, 46, 51, 37, '2020-02-29 16:04:33', 28, 9, 0, 0),
(1418, 97, 46, 51, 37, '2020-02-29 16:04:34', 28, 9, 0, 0),
(1419, 97, 46, 51, 37, '2020-02-29 16:04:34', 28, 9, 0, 0),
(1420, 97, 46, 51, 37, '2020-02-29 16:04:37', 28, 9, 0, 0),
(1421, 97, 46, 51, 37, '2020-02-29 16:04:37', 28, 9, 0, 0),
(1422, 97, 46, 51, 37, '2020-02-29 16:04:40', 28, 9, 0, 0),
(1423, 97, 46, 51, 37, '2020-02-29 16:04:40', 28, 9, 0, 0),
(1424, 97, 46, 51, 37, '2020-02-29 16:04:43', 28, 9, 0, 0),
(1425, 97, 46, 51, 37, '2020-02-29 16:04:43', 28, 9, 0, 0),
(1426, 97, 46, 51, 37, '2020-02-29 16:04:45', 28, 9, 0, 0),
(1427, 97, 46, 51, 37, '2020-02-29 16:04:45', 28, 9, 0, 0),
(1428, 97, 46, 51, 37, '2020-02-29 16:04:45', 28, 9, 0, 0),
(1429, 97, 46, 51, 37, '2020-02-29 16:04:48', 28, 9, 0, 0),
(1430, 97, 46, 51, 37, '2020-02-29 16:04:48', 28, 9, 0, 0),
(1431, 97, 46, 51, 37, '2020-02-29 16:04:48', 28, 9, 0, 0),
(1432, 97, 46, 51, 37, '2020-03-01 04:22:18', 28, 9, 0, 0),
(1433, 97, 46, 51, 37, '2020-03-31 16:13:31', 28, 9, 0, 0),
(1434, 97, 46, 51, 37, '2020-03-31 16:13:31', 28, 9, 0, 0),
(1435, 97, 46, 51, 37, '2020-03-31 16:13:31', 28, 9, 0, 0),
(1436, 97, 46, 51, 37, '2020-03-31 16:13:31', 28, 9, 0, 0),
(1437, 97, 46, 51, 37, '2020-03-31 16:13:31', 28, 9, 0, 0),
(1438, 97, 46, 51, 37, '2020-03-31 16:13:31', 28, 9, 0, 0),
(1439, 97, 46, 51, 37, '2020-03-31 16:13:32', 28, 9, 0, 0),
(1440, 97, 46, 51, 37, '2020-03-31 16:13:32', 28, 9, 0, 0),
(1441, 97, 46, 51, 37, '2020-03-31 16:13:32', 28, 9, 0, 0),
(1442, 97, 46, 51, 37, '2020-03-31 16:13:35', 28, 9, 0, 0),
(1443, 97, 46, 51, 37, '2020-03-31 16:13:35', 28, 9, 0, 0),
(1444, 97, 46, 51, 37, '2020-03-31 16:13:35', 28, 9, 0, 0),
(1445, 97, 46, 51, 37, '2020-03-31 16:13:38', 28, 9, 0, 0),
(1446, 97, 46, 51, 37, '2020-03-31 16:13:38', 28, 9, 0, 0),
(1447, 97, 46, 51, 37, '2020-03-31 16:13:38', 28, 9, 0, 0),
(1448, 97, 46, 51, 37, '2020-03-31 16:13:41', 28, 9, 0, 0),
(1449, 97, 46, 51, 37, '2020-03-31 16:13:41', 28, 9, 0, 0),
(1450, 97, 46, 51, 37, '2020-03-31 16:13:41', 28, 9, 0, 0),
(1451, 97, 46, 51, 37, '2020-03-31 16:13:42', 28, 9, 0, 0),
(1452, 97, 46, 51, 37, '2020-03-31 16:13:42', 28, 9, 0, 0),
(1453, 97, 46, 51, 37, '2020-03-31 16:13:43', 28, 9, 0, 0),
(1454, 97, 46, 51, 37, '2020-03-31 16:13:43', 28, 9, 0, 0),
(1455, 97, 46, 51, 37, '2020-03-31 16:13:43', 28, 9, 0, 0),
(1456, 97, 46, 51, 37, '2020-03-31 16:13:46', 28, 9, 0, 0),
(1457, 97, 46, 51, 37, '2020-03-31 16:13:46', 28, 9, 0, 0),
(1458, 97, 46, 51, 37, '2020-03-31 16:13:46', 28, 9, 0, 0),
(1459, 97, 46, 51, 37, '2020-03-31 16:13:49', 28, 9, 0, 0),
(1460, 97, 46, 51, 37, '2020-03-31 16:13:49', 28, 9, 0, 0),
(1461, 97, 46, 51, 37, '2020-03-31 16:13:49', 28, 9, 0, 0),
(1462, 97, 46, 51, 37, '2020-03-31 16:13:52', 28, 9, 0, 0),
(1463, 97, 46, 51, 37, '2020-03-31 16:13:52', 28, 9, 0, 0),
(1464, 97, 46, 51, 37, '2020-03-31 16:13:52', 28, 9, 0, 0),
(1465, 97, 46, 51, 37, '2020-03-31 16:13:55', 28, 9, 0, 0),
(1466, 97, 46, 51, 37, '2020-03-31 16:13:55', 28, 9, 0, 0),
(1467, 97, 46, 51, 37, '2020-03-31 16:13:55', 28, 9, 0, 0),
(1468, 97, 46, 51, 37, '2020-03-31 16:13:58', 28, 9, 0, 0),
(1469, 97, 46, 51, 37, '2020-03-31 16:13:58', 28, 9, 0, 0),
(1470, 97, 46, 51, 37, '2020-03-31 16:13:58', 28, 9, 0, 0),
(1471, 97, 46, 51, 37, '2020-03-31 16:14:01', 28, 9, 0, 0),
(1472, 97, 46, 51, 37, '2020-03-31 16:14:01', 28, 9, 0, 0),
(1473, 97, 46, 51, 37, '2020-03-31 16:14:01', 28, 9, 0, 0),
(1474, 97, 46, 51, 37, '2020-03-31 16:14:04', 28, 9, 0, 0),
(1475, 97, 46, 51, 37, '2020-03-31 16:14:04', 28, 9, 0, 0),
(1476, 97, 46, 51, 37, '2020-03-31 16:14:04', 28, 9, 0, 0),
(1477, 97, 46, 51, 37, '2020-03-31 16:14:07', 28, 9, 0, 0),
(1478, 97, 46, 51, 37, '2020-03-31 16:14:07', 28, 9, 0, 0),
(1479, 97, 46, 51, 37, '2020-03-31 16:14:07', 28, 9, 0, 0),
(1480, 97, 46, 51, 37, '2020-03-31 16:14:10', 28, 9, 0, 0),
(1481, 97, 46, 51, 37, '2020-03-31 16:14:10', 28, 9, 0, 0),
(1482, 97, 46, 51, 37, '2020-03-31 16:14:10', 28, 9, 0, 0),
(1483, 97, 46, 51, 37, '2020-03-31 16:14:13', 28, 9, 0, 0),
(1484, 97, 46, 51, 37, '2020-03-31 16:14:13', 28, 9, 0, 0),
(1485, 97, 46, 51, 37, '2020-03-31 16:14:13', 28, 9, 0, 0),
(1486, 97, 46, 51, 37, '2020-03-31 16:14:16', 28, 9, 0, 0),
(1487, 97, 46, 51, 37, '2020-03-31 16:14:16', 28, 9, 0, 0),
(1488, 97, 46, 51, 37, '2020-03-31 16:14:16', 28, 9, 0, 0),
(1489, 97, 46, 51, 37, '2020-03-31 16:14:19', 28, 9, 0, 0),
(1490, 97, 46, 51, 37, '2020-03-31 16:14:19', 28, 9, 0, 0),
(1491, 97, 46, 51, 37, '2020-03-31 16:14:19', 28, 9, 0, 0),
(1492, 97, 46, 51, 37, '2020-03-31 16:14:20', 28, 9, 0, 0),
(1493, 97, 46, 51, 37, '2020-03-31 16:14:21', 28, 9, 0, 0),
(1494, 97, 46, 51, 37, '2020-03-31 16:14:21', 28, 9, 0, 0),
(1495, 97, 46, 51, 37, '2020-03-31 16:14:21', 28, 9, 0, 0),
(1496, 97, 46, 51, 37, '2020-03-31 16:14:24', 28, 9, 0, 0),
(1497, 97, 46, 51, 37, '2020-03-31 16:14:24', 28, 9, 0, 0),
(1498, 97, 46, 51, 37, '2020-03-31 16:14:24', 28, 9, 0, 0),
(1499, 97, 46, 51, 37, '2020-03-31 16:14:27', 28, 9, 0, 0),
(1500, 97, 46, 51, 37, '2020-03-31 16:14:27', 28, 9, 0, 0),
(1501, 97, 46, 51, 37, '2020-03-31 16:14:27', 28, 9, 0, 0),
(1502, 97, 46, 51, 37, '2020-03-31 16:14:30', 28, 9, 0, 0),
(1503, 97, 46, 51, 37, '2020-03-31 16:14:30', 28, 9, 0, 0),
(1504, 97, 46, 51, 37, '2020-03-31 16:14:30', 28, 9, 0, 0),
(1505, 97, 46, 51, 37, '2020-03-31 16:14:33', 28, 9, 0, 0),
(1506, 97, 46, 51, 37, '2020-03-31 16:14:33', 28, 9, 0, 0),
(1507, 97, 46, 51, 37, '2020-03-31 16:14:33', 28, 9, 0, 0),
(1508, 97, 46, 51, 37, '2020-03-31 16:14:36', 28, 9, 0, 0),
(1509, 97, 46, 51, 37, '2020-03-31 16:14:36', 28, 9, 0, 0),
(1510, 97, 46, 51, 37, '2020-03-31 16:14:36', 28, 9, 0, 0),
(1511, 97, 46, 51, 37, '2020-03-31 16:14:39', 28, 9, 0, 0),
(1512, 97, 46, 51, 37, '2020-03-31 16:14:39', 28, 9, 0, 0),
(1513, 97, 46, 51, 37, '2020-03-31 16:14:39', 28, 9, 0, 0),
(1514, 97, 46, 51, 37, '2020-03-31 16:14:42', 28, 9, 0, 0),
(1515, 97, 46, 51, 37, '2020-03-31 16:14:42', 28, 9, 0, 0),
(1516, 97, 46, 51, 37, '2020-03-31 16:14:42', 28, 9, 0, 0),
(1517, 97, 46, 51, 37, '2020-03-31 16:14:45', 28, 9, 0, 0),
(1518, 97, 46, 51, 37, '2020-03-31 16:14:45', 28, 9, 0, 0),
(1519, 97, 46, 51, 37, '2020-03-31 16:14:45', 28, 9, 0, 0),
(1520, 97, 46, 51, 37, '2020-03-31 16:14:48', 28, 9, 0, 0),
(1521, 97, 46, 51, 37, '2020-03-31 16:14:48', 28, 9, 0, 0),
(1522, 97, 46, 51, 37, '2020-03-31 16:14:48', 28, 9, 0, 0),
(1523, 97, 46, 51, 37, '2020-03-31 16:14:51', 28, 9, 0, 0),
(1524, 97, 46, 51, 37, '2020-03-31 16:14:51', 28, 9, 0, 0),
(1525, 97, 46, 51, 37, '2020-03-31 16:14:51', 28, 9, 0, 0),
(1526, 97, 46, 51, 37, '2020-03-31 16:14:54', 28, 9, 0, 0),
(1527, 97, 46, 51, 37, '2020-03-31 16:14:54', 28, 9, 0, 0),
(1528, 97, 46, 51, 37, '2020-03-31 16:14:54', 28, 9, 0, 0),
(1529, 97, 46, 51, 37, '2020-03-31 16:14:57', 28, 9, 0, 0),
(1530, 97, 46, 51, 37, '2020-03-31 16:14:57', 28, 9, 0, 0),
(1531, 97, 46, 51, 37, '2020-03-31 16:14:57', 28, 9, 0, 0),
(1532, 97, 46, 51, 37, '2020-03-31 16:15:00', 28, 9, 0, 0),
(1533, 97, 46, 51, 37, '2020-03-31 16:15:00', 28, 9, 0, 0),
(1534, 97, 46, 51, 37, '2020-03-31 16:15:00', 28, 9, 0, 0),
(1535, 97, 46, 51, 37, '2020-03-31 16:15:03', 28, 9, 0, 0),
(1536, 97, 46, 51, 37, '2020-03-31 16:15:03', 28, 9, 0, 0),
(1537, 97, 46, 51, 37, '2020-03-31 16:15:03', 28, 9, 0, 0),
(1538, 97, 46, 51, 37, '2020-03-31 16:15:06', 28, 9, 0, 0),
(1539, 97, 46, 51, 37, '2020-03-31 16:15:06', 28, 9, 0, 0),
(1540, 97, 46, 51, 37, '2020-03-31 16:15:06', 28, 9, 0, 0),
(1541, 97, 46, 51, 37, '2020-03-31 16:15:09', 28, 9, 0, 0),
(1542, 97, 46, 51, 37, '2020-03-31 16:15:09', 28, 9, 0, 0),
(1543, 97, 46, 51, 37, '2020-03-31 16:15:09', 28, 9, 0, 0),
(1544, 97, 46, 51, 37, '2020-03-31 16:15:12', 28, 9, 0, 0),
(1545, 97, 46, 51, 37, '2020-03-31 16:15:12', 28, 9, 0, 0),
(1546, 97, 46, 51, 37, '2020-03-31 16:15:12', 28, 9, 0, 0),
(1547, 97, 46, 51, 37, '2020-03-31 16:15:15', 28, 9, 0, 0),
(1548, 97, 46, 51, 37, '2020-03-31 16:15:15', 28, 9, 0, 0),
(1549, 97, 46, 51, 37, '2020-03-31 16:15:15', 28, 9, 0, 0),
(1550, 97, 46, 51, 37, '2020-03-31 16:15:18', 28, 9, 0, 0),
(1551, 97, 46, 51, 37, '2020-03-31 16:15:18', 28, 9, 0, 0),
(1552, 97, 46, 51, 37, '2020-03-31 16:15:18', 28, 9, 0, 0),
(1553, 97, 46, 51, 37, '2020-03-31 16:15:21', 28, 9, 0, 0),
(1554, 97, 46, 51, 37, '2020-03-31 16:15:21', 28, 9, 0, 0),
(1555, 97, 46, 51, 37, '2020-03-31 16:15:21', 28, 9, 0, 0),
(1556, 97, 46, 51, 37, '2020-03-31 16:15:24', 28, 9, 0, 0),
(1557, 97, 46, 51, 37, '2020-03-31 16:15:24', 28, 9, 0, 0),
(1558, 97, 46, 51, 37, '2020-03-31 16:15:24', 28, 9, 0, 0),
(1559, 97, 46, 51, 37, '2020-03-31 16:15:27', 28, 9, 0, 0),
(1560, 97, 46, 51, 37, '2020-03-31 16:15:27', 28, 9, 0, 0),
(1561, 97, 46, 51, 37, '2020-03-31 16:15:27', 28, 9, 0, 0),
(1562, 97, 46, 51, 37, '2020-03-31 16:15:30', 28, 9, 0, 0),
(1563, 97, 46, 51, 37, '2020-03-31 16:15:30', 28, 9, 0, 0),
(1564, 97, 46, 51, 37, '2020-03-31 16:15:30', 28, 9, 0, 0),
(1565, 97, 46, 51, 37, '2020-03-31 16:15:33', 28, 9, 0, 0),
(1566, 97, 46, 51, 37, '2020-03-31 16:15:33', 28, 9, 0, 0),
(1567, 97, 46, 51, 37, '2020-03-31 16:15:33', 28, 9, 0, 0),
(1568, 97, 46, 51, 37, '2020-03-31 16:15:36', 28, 9, 0, 0),
(1569, 97, 46, 51, 37, '2020-03-31 16:15:36', 28, 9, 0, 0),
(1570, 97, 46, 51, 37, '2020-03-31 16:15:36', 28, 9, 0, 0),
(1571, 97, 46, 51, 37, '2020-03-31 16:15:39', 28, 9, 0, 0),
(1572, 97, 46, 51, 37, '2020-03-31 16:15:39', 28, 9, 0, 0),
(1573, 97, 46, 51, 37, '2020-03-31 16:15:39', 28, 9, 0, 0),
(1574, 97, 46, 51, 37, '2020-03-31 16:15:42', 28, 9, 0, 0),
(1575, 97, 46, 51, 37, '2020-03-31 16:15:42', 28, 9, 0, 0),
(1576, 97, 46, 51, 37, '2020-03-31 16:15:42', 28, 9, 0, 0),
(1577, 97, 46, 51, 37, '2020-03-31 16:15:45', 28, 9, 0, 0),
(1578, 97, 46, 51, 37, '2020-03-31 16:15:45', 28, 9, 0, 0),
(1579, 97, 46, 51, 37, '2020-03-31 16:15:45', 28, 9, 0, 0),
(1580, 97, 46, 51, 37, '2020-03-31 16:15:48', 28, 9, 0, 0),
(1581, 97, 46, 51, 37, '2020-03-31 16:15:48', 28, 9, 0, 0),
(1582, 97, 46, 51, 37, '2020-03-31 16:15:48', 28, 9, 0, 0),
(1583, 97, 46, 51, 37, '2020-03-31 16:15:51', 28, 9, 0, 0),
(1584, 97, 46, 51, 37, '2020-03-31 16:15:51', 28, 9, 0, 0),
(1585, 97, 46, 51, 37, '2020-03-31 16:15:51', 28, 9, 0, 0),
(1586, 97, 46, 51, 37, '2020-03-31 16:15:54', 28, 9, 0, 0),
(1587, 97, 46, 51, 37, '2020-03-31 16:15:54', 28, 9, 0, 0),
(1588, 97, 46, 51, 37, '2020-03-31 16:15:54', 28, 9, 0, 0),
(1589, 97, 46, 51, 37, '2020-03-31 16:15:57', 28, 9, 0, 0),
(1590, 97, 46, 51, 37, '2020-03-31 16:15:57', 28, 9, 0, 0),
(1591, 97, 46, 51, 37, '2020-03-31 16:15:57', 28, 9, 0, 0),
(1592, 97, 46, 51, 37, '2020-03-31 16:15:57', 28, 9, 0, 0),
(1593, 97, 46, 51, 37, '2020-03-31 16:15:58', 28, 9, 0, 0),
(1594, 97, 46, 51, 37, '2020-03-31 16:15:58', 28, 9, 0, 0),
(1595, 97, 46, 51, 37, '2020-03-31 16:15:58', 28, 9, 0, 0),
(1596, 97, 46, 51, 37, '2020-03-31 16:16:01', 28, 9, 0, 0),
(1597, 97, 46, 51, 37, '2020-04-30 15:07:25', 28, 9, 0, 0),
(1598, 97, 46, 51, 37, '2020-04-30 15:07:25', 28, 9, 0, 0),
(1599, 97, 46, 51, 37, '2020-04-30 15:07:25', 28, 9, 0, 0),
(1600, 97, 46, 51, 37, '2020-04-30 15:07:25', 28, 9, 0, 0),
(1601, 97, 46, 51, 37, '2020-04-30 15:07:26', 28, 9, 0, 0),
(1602, 97, 46, 51, 37, '2020-04-30 15:07:26', 28, 9, 0, 0),
(1603, 97, 46, 51, 37, '2020-04-30 15:07:26', 28, 9, 0, 0),
(1604, 97, 46, 51, 37, '2020-04-30 15:07:32', 28, 9, 0, 0),
(1605, 97, 46, 51, 37, '2020-05-31 17:14:43', 28, 9, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_ekspor`
--

CREATE TABLE `log_ekspor` (
  `id` int(11) NOT NULL,
  `tgl_ekspor` timestamp NOT NULL DEFAULT current_timestamp(),
  `kode_ekspor` varchar(100) NOT NULL,
  `semua` int(1) NOT NULL DEFAULT 1,
  `dari_tgl` date DEFAULT NULL,
  `total` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_keluarga`
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
-- Struktur dari tabel `log_penduduk`
--

CREATE TABLE `log_penduduk` (
  `id` int(10) NOT NULL,
  `id_pend` int(11) NOT NULL,
  `id_detail` int(4) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `bulan` varchar(2) NOT NULL,
  `tahun` varchar(4) NOT NULL,
  `tgl_peristiwa` datetime DEFAULT current_timestamp(),
  `catatan` text DEFAULT NULL,
  `no_kk` decimal(16,0) DEFAULT NULL,
  `nama_kk` varchar(100) DEFAULT NULL,
  `ref_pindah` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_perubahan_penduduk`
--

CREATE TABLE `log_perubahan_penduduk` (
  `id` int(11) NOT NULL,
  `id_pend` int(11) NOT NULL,
  `id_cluster` varchar(200) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_surat`
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
  `nama_non_warga` varchar(100) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `log_surat`
--

INSERT INTO `log_surat` (`id`, `id_format_surat`, `id_pend`, `id_pamong`, `id_user`, `tanggal`, `bulan`, `tahun`, `no_surat`, `nama_surat`, `lampiran`, `nik_non_warga`, `nama_non_warga`, `keterangan`) VALUES
(7, 2, 1, 14, 1, '2019-03-30 03:36:04', '03', '2019', '1', 'surat_ket_penduduk_5201142005716996_2019-03-30_1.rtf', '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi`
--

CREATE TABLE `lokasi` (
  `id` int(4) NOT NULL,
  `desk` text NOT NULL,
  `nama` varchar(50) NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT 1,
  `lat` varchar(30) DEFAULT NULL,
  `lng` varchar(30) DEFAULT NULL,
  `ref_point` int(9) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `id_cluster` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `lokasi`
--

INSERT INTO `lokasi` (`id`, `desk`, `nama`, `enabled`, `lat`, `lng`, `ref_point`, `foto`, `id_cluster`) VALUES
(1, 'Sekolah Menengah Pertama', 'SMP', 1, '-8.49563254042209', '116.04755401611328', 5, '', 0),
(2, 'Sekolah Menengah Atas', 'SMA', 1, '-8.485106175017545', '116.04549407958986', 4, '', 0),
(3, 'Sarana Pendidikan', 'Puskesmas Husada', 1, '-8.478145032940077', '116.0394859313965', 9, '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `media_sosial`
--

CREATE TABLE `media_sosial` (
  `id` int(11) NOT NULL,
  `gambar` text NOT NULL,
  `link` text DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `tipe` tinyint(1) DEFAULT 1,
  `enabled` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `media_sosial`
--

INSERT INTO `media_sosial` (`id`, `gambar`, `link`, `nama`, `tipe`, `enabled`) VALUES
(1, 'fb.png', 'https://www.facebook.com/groups/OpenSID/', 'Facebook', 1, 1),
(2, 'twt.png', '', 'Twitter', 1, 1),
(3, 'goo.png', '', 'Google Plus', 1, 1),
(4, 'yb.png', '', 'YouTube', 1, 1),
(5, 'ins.png', '', 'Instagram', 1, 1),
(6, 'wa.png', '', 'WhatsApp', 1, 1),
(7, 'tg.png', NULL, 'Telegram', 1, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
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

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `nama`, `link`, `tipe`, `parrent`, `link_tipe`, `enabled`, `urut`) VALUES
(16, 'Profil Desa', 'artikel/32', 1, 1, 1, 1, NULL),
(17, 'Pemerintahan Desa', 'artikel/85', 1, 1, 1, 1, NULL),
(19, 'Lembaga Masyarakat', 'artikel/38', 1, 1, 1, 2, NULL),
(23, 'Teras Desa', '', 2, 1, 1, 1, NULL),
(24, 'Data Desa', 'artikel/97', 1, 1, 0, 1, NULL),
(31, 'Data Wilayah Administratif', 'wilayah', 3, 24, 1, 1, NULL),
(32, 'Data Pendidikan dalam KK', 'statistik/0', 3, 24, 1, 1, NULL),
(33, 'Data Pendidikan Ditempuh', 'statistik/14', 3, 24, 0, 1, NULL),
(34, 'Data Pekerjaan', 'statistik/1', 3, 24, 1, 1, NULL),
(35, 'Data Agama', 'statistik/3', 3, 24, 1, 1, NULL),
(36, 'Data Jenis Kelamin', 'statistik/4', 3, 24, 1, 1, NULL),
(40, 'Data Golongan Darah', 'statistik/7', 3, 24, 1, 2, NULL),
(51, 'Data Kelompok Umur', 'statistik/12', 3, 24, 1, 2, NULL),
(52, 'Data Penerima Raskin', 'statistik_k/2', 3, 24, 1, 2, NULL),
(53, 'Data Penerima Jamkesmas', 'statistik_k/3', 3, 24, 1, 2, NULL),
(55, 'Profil Wilayah Desa', 'artikel/33', 3, 16, 1, 1, NULL),
(56, 'Profil Masyarakat Desa', 'artikel/34', 3, 16, 1, 2, NULL),
(57, 'Visi dan Misi', 'artikel/93', 3, 17, 0, 1, NULL),
(58, 'Pemerintah Desa', 'artikel/92', 3, 17, 0, 1, NULL),
(59, 'Badan Permusyawaratan Desa', 'artikel/37', 3, 17, 0, 2, NULL),
(62, 'Berita Desa', '', 2, 1, 1, 1, NULL),
(63, 'Agenda Desa', 'artikel/41', 2, 1, 1, 2, NULL),
(64, 'Peraturan Desa', 'peraturan', 2, 1, 1, 2, NULL),
(65, 'Panduan Layanan Desa', '#', 2, 1, 1, 1, NULL),
(66, 'Produk Desa', 'produk', 2, 1, 1, 1, NULL),
(68, 'Undang undang', 'artikel/42', 3, 64, 1, 2, NULL),
(69, 'Peraturan Pemerintah', 'artikel/43', 3, 64, 1, 2, NULL),
(70, 'Peraturan Daerah', '', 3, 64, 1, 2, NULL),
(71, 'Peraturan Bupati', '', 3, 64, 1, 2, NULL),
(72, 'Peraturan Bersama KaDes', '', 3, 64, 1, 2, NULL),
(73, 'Informasi Publik', '#', 2, 1, 1, 1, NULL),
(75, 'Rencana Kerja Anggaran', '', 3, 73, 1, 1, NULL),
(76, 'RAPB Desa', '', 3, 73, 1, 1, NULL),
(77, 'APB Desa', '', 3, 73, 1, 1, NULL),
(78, 'DPA', '', 3, 73, 1, 1, NULL),
(80, 'Profil Potensi Desa', 'artikel/59', 3, 16, 1, 2, NULL),
(84, 'LKMD', 'artikel/62', 3, 18, 1, 1, NULL),
(85, 'PKK', 'artikel/63', 3, 18, 1, 1, NULL),
(86, 'Karang Taruna', 'artikel/64', 3, 18, 1, 1, NULL),
(87, 'RT RW', 'artikel/65', 3, 18, 1, 1, NULL),
(88, 'Linmas', 'artikel/70', 3, 18, 1, 1, NULL),
(89, 'TKP2KDes', 'artikel/66', 3, 18, 1, 1, NULL),
(90, 'KPAD', 'artikel/67', 3, 18, 1, 1, NULL),
(91, 'Kelompok Ternak', 'artikel/68', 3, 18, 1, 1, NULL),
(92, 'Kelompok Tani', 'artikel/69', 3, 18, 1, 1, NULL),
(93, 'Kelompok Ekonomi Lainya', 'artikel/71', 3, 18, 1, 1, NULL),
(98, 'LKPJ', '', 3, 73, 1, 1, NULL),
(99, 'LPPD', '', 3, 73, 1, 1, NULL),
(100, 'ILPPD', '', 3, 73, 1, 1, NULL),
(101, 'Peraturan Desa', 'artikel/44', 3, 64, 1, 2, NULL),
(102, 'Peraturan Kepala Desa', 'artikel/45', 3, 64, 1, 2, NULL),
(103, 'Keputusan Kepala Desa', 'artikel/46', 3, 64, 1, 2, NULL),
(104, 'PBB', '', 3, 73, 1, 1, NULL),
(106, 'Data Warga Negara', 'statistik/13', 3, 24, 1, 1, NULL),
(108, 'Data Kelas Sosial', 'statistik_k/1', 3, 24, 1, 2, NULL),
(109, 'Kontak', 'artikel/36', 1, 1, 1, 2, NULL),
(110, 'Peraturan Desa', 'peraturan', 3, 66, 1, 1, NULL),
(112, 'Coba', 'coba', 2, 1, 1, 1, NULL),
(113, 'Peta', 'peta', 3, 109, 5, 1, NULL),
(114, 'Sejarah Desa', 'artikel/99', 3, 16, 0, 1, NULL),
(115, 'Produk Hukum', 'peraturan_desa', 1, 1, 5, 1, 1),
(116, 'Informasi Publik', 'informasi_publik', 1, 1, 5, 1, 2),
(117, 'Bantuan Penduduk', 'statistik/bantuan_penduduk', 3, 109, 2, 1, 1),
(118, 'Bantuan Keluarga', 'statistik/bantuan_keluarga', 3, 109, 3, 1, 2),
(119, 'DPT', 'dpt', 3, 109, 2, 1, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrasi`
--

CREATE TABLE `migrasi` (
  `id` int(11) NOT NULL,
  `versi_database` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `migrasi`
--

INSERT INTO `migrasi` (`id`, `versi_database`) VALUES
(1, '2020040102'),
(2, '2020050101'),
(3, '2020060101');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mutasi_inventaris_asset`
--

CREATE TABLE `mutasi_inventaris_asset` (
  `id` int(11) NOT NULL,
  `id_inventaris_asset` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `sumbangkan` varchar(255) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mutasi_inventaris_gedung`
--

CREATE TABLE `mutasi_inventaris_gedung` (
  `id` int(11) NOT NULL,
  `id_inventaris_gedung` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `sumbangkan` varchar(255) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mutasi_inventaris_jalan`
--

CREATE TABLE `mutasi_inventaris_jalan` (
  `id` int(11) NOT NULL,
  `id_inventaris_jalan` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `sumbangkan` varchar(255) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mutasi_inventaris_peralatan`
--

CREATE TABLE `mutasi_inventaris_peralatan` (
  `id` int(11) NOT NULL,
  `id_inventaris_peralatan` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `sumbangkan` varchar(255) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mutasi_inventaris_tanah`
--

CREATE TABLE `mutasi_inventaris_tanah` (
  `id` int(11) NOT NULL,
  `id_inventaris_tanah` int(11) DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `sumbangkan` varchar(255) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `outbox`
--

CREATE TABLE `outbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `InsertIntoDB` timestamp NOT NULL DEFAULT current_timestamp(),
  `SendingDateTime` timestamp NOT NULL DEFAULT current_timestamp(),
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
  `SendingTimeOut` timestamp NULL DEFAULT NULL,
  `DeliveryReport` enum('default','yes','no') DEFAULT 'default',
  `CreatorID` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `penduduk_hidup`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `penduduk_hidup` (
`id` int(11)
,`nama` varchar(100)
,`nik` decimal(16,0)
,`id_kk` int(11)
,`kk_level` tinyint(2)
,`id_rtm` varchar(30)
,`rtm_level` int(11)
,`sex` tinyint(4) unsigned
,`tempatlahir` varchar(100)
,`tanggallahir` date
,`agama_id` int(1)
,`pendidikan_kk_id` int(1)
,`pendidikan_sedang_id` int(1)
,`pekerjaan_id` int(1)
,`status_kawin` tinyint(4)
,`warganegara_id` tinyint(4)
,`dokumen_pasport` varchar(45)
,`dokumen_kitas` int(10)
,`ayah_nik` varchar(16)
,`ibu_nik` varchar(16)
,`nama_ayah` varchar(100)
,`nama_ibu` varchar(100)
,`foto` varchar(100)
,`golongan_darah_id` int(11)
,`id_cluster` int(11)
,`status` int(10) unsigned
,`alamat_sebelumnya` varchar(200)
,`alamat_sekarang` varchar(200)
,`status_dasar` tinyint(4)
,`hamil` int(1)
,`cacat_id` int(11)
,`sakit_menahun_id` int(11)
,`akta_lahir` varchar(40)
,`akta_perkawinan` varchar(40)
,`tanggalperkawinan` date
,`akta_perceraian` varchar(40)
,`tanggalperceraian` date
,`cara_kb_id` tinyint(2)
,`telepon` varchar(20)
,`tanggal_akhir_paspor` date
,`no_kk_sebelumnya` varchar(30)
,`ktp_el` tinyint(4)
,`status_rekam` tinyint(4)
,`waktu_lahir` varchar(5)
,`tempat_dilahirkan` tinyint(2)
,`jenis_kelahiran` tinyint(2)
,`kelahiran_anak_ke` tinyint(2)
,`penolong_kelahiran` tinyint(2)
,`berat_lahir` smallint(6)
,`panjang_lahir` varchar(10)
,`tag_id_card` varchar(15)
,`created_at` timestamp
,`created_by` int(11)
,`updated_at` timestamp
,`updated_by` int(11)
,`id_asuransi` tinyint(5)
,`no_asuransi` char(100)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_surat`
--

CREATE TABLE `permohonan_surat` (
  `id` int(11) NOT NULL,
  `id_pemohon` int(11) NOT NULL,
  `id_surat` int(11) NOT NULL,
  `isian_form` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `no_hp_aktif` varchar(50) NOT NULL,
  `syarat` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanyaan`
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
-- Struktur dari tabel `point`
--

CREATE TABLE `point` (
  `id` int(4) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) NOT NULL,
  `tipe` int(4) NOT NULL,
  `parrent` int(4) NOT NULL DEFAULT 1,
  `enabled` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `point`
--

INSERT INTO `point` (`id`, `nama`, `simbol`, `tipe`, `parrent`, `enabled`) VALUES
(1, 'Sarana Pendidikan', 'face-embarrassed.png', 0, 1, 1),
(2, 'Sarana Transportasi', 'face-devilish.png', 0, 1, 1),
(3, 'Sarana Kesehatan', 'emblem-photos.png', 0, 1, 1),
(4, 'SMA', 'gateswalls.png', 2, 1, 1),
(5, 'SMP (Sekolah Menengah Pertama)', 'arch.png', 2, 1, 1),
(6, 'Masjid', 'mosque.png', 2, 7, 1),
(7, 'Tempat Ibadah', 'emblem-art.png', 0, 1, 1),
(8, 'Kuil', 'moderntower.png', 2, 7, 1),
(9, 'RS', 'accerciser.png', 2, 3, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `polygon`
--

CREATE TABLE `polygon` (
  `id` int(4) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `simbol` varchar(50) DEFAULT NULL,
  `color` varchar(10) NOT NULL DEFAULT 'ff0000',
  `tipe` int(4) DEFAULT 0,
  `parrent` int(4) DEFAULT 1,
  `enabled` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `polygon`
--

INSERT INTO `polygon` (`id`, `nama`, `simbol`, `color`, `tipe`, `parrent`, `enabled`) VALUES
(1, 'rawan topan', '', '#7C78FF', 0, 1, 1),
(2, 'jalur selokan', '', '#F4FF59', 0, 1, 1),
(3, 'Pemukiman rawan topan', '', '#db2121', 2, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `ndesc` varchar(200) DEFAULT NULL,
  `sdate` date NOT NULL,
  `edate` date NOT NULL,
  `userid` mediumint(9) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `asaldana` char(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `program`
--

INSERT INTO `program` (`id`, `nama`, `sasaran`, `ndesc`, `sdate`, `edate`, `userid`, `status`, `asaldana`) VALUES
(1, 'BPNT', 2, 'Bantuan Pemerintah Non Tunai', '2015-12-13', '2020-12-31', 0, 1, 'Pusat'),
(2, 'BLSM', 2, '', '2015-12-13', '2017-12-13', 0, 0, NULL),
(3, 'PKH', 2, '', '2015-12-13', '2017-12-13', 0, 0, NULL),
(4, 'Bedah Rumah', 2, '', '2015-12-13', '2017-12-13', 0, 0, NULL),
(5, 'JAMKESMAS', 1, 'Jamkesmas', '2015-12-13', '2020-12-31', 0, 1, 'Kab/Kota');

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_peserta`
--

CREATE TABLE `program_peserta` (
  `id` int(11) NOT NULL,
  `peserta` varchar(30) NOT NULL,
  `program_id` int(11) NOT NULL,
  `no_id_kartu` varchar(30) DEFAULT NULL,
  `kartu_nik` varchar(30) DEFAULT NULL,
  `kartu_nama` varchar(100) DEFAULT NULL,
  `kartu_tempat_lahir` varchar(100) DEFAULT NULL,
  `kartu_tanggal_lahir` date DEFAULT NULL,
  `kartu_alamat` varchar(200) DEFAULT NULL,
  `kartu_peserta` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `program_peserta`
--

INSERT INTO `program_peserta` (`id`, `peserta`, `program_id`, `no_id_kartu`, `kartu_nik`, `kartu_nama`, `kartu_tempat_lahir`, `kartu_tanggal_lahir`, `kartu_alamat`, `kartu_peserta`) VALUES
(2, '5201140104126994', 1, NULL, '', NULL, NULL, NULL, NULL, ''),
(3, '5201140105136997', 1, NULL, '', NULL, NULL, NULL, NULL, ''),
(4, '5201140104126995', 2, NULL, '', NULL, NULL, NULL, NULL, ''),
(5, '5201140105136997', 2, NULL, '', NULL, NULL, NULL, NULL, ''),
(6, '5201140104126995', 3, NULL, '', NULL, NULL, NULL, NULL, ''),
(7, '5201140105136997', 3, NULL, '', NULL, NULL, NULL, NULL, ''),
(8, '5201140104166999', 4, NULL, '', NULL, NULL, NULL, NULL, ''),
(9, '5201140105136997', 4, NULL, '', NULL, NULL, NULL, NULL, ''),
(10, '5201142005716996', 5, NULL, '', NULL, NULL, NULL, NULL, ''),
(11, '5201140706966997', 5, NULL, '', NULL, NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `provinsi`
--

CREATE TABLE `provinsi` (
  `kode` tinyint(2) NOT NULL DEFAULT 0,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `provinsi`
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
-- Struktur dari tabel `ref_dokumen`
--

CREATE TABLE `ref_dokumen` (
  `id` int(5) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_dokumen`
--

INSERT INTO `ref_dokumen` (`id`, `nama`) VALUES
(1, 'Informasi Publik'),
(2, 'SK Kades'),
(3, 'Perdes');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_pindah`
--

CREATE TABLE `ref_pindah` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_pindah`
--

INSERT INTO `ref_pindah` (`id`, `nama`) VALUES
(1, 'Pindah keluar Desa/Kelurahan'),
(2, 'Pindah keluar Kecamatan'),
(3, 'Pindah keluar Kabupaten/Kota'),
(4, 'Pindah keluar Provinsi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_status_covid`
--

CREATE TABLE `ref_status_covid` (
  `id` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_status_covid`
--

INSERT INTO `ref_status_covid` (`id`, `nama`) VALUES
(1, 'ODP'),
(2, 'PDP'),
(3, 'ODR'),
(4, 'OTG'),
(5, 'POSITIF'),
(6, 'DLL');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_syarat_surat`
--

CREATE TABLE `ref_syarat_surat` (
  `ref_syarat_id` int(1) UNSIGNED NOT NULL,
  `ref_syarat_nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_syarat_surat`
--

INSERT INTO `ref_syarat_surat` (`ref_syarat_id`, `ref_syarat_nama`) VALUES
(1, 'Surat Pengantar RT/RW'),
(2, 'Fotokopi KK'),
(3, 'Fotokopi KTP'),
(4, 'Fotokopi Surat Nikah/Akta Nikah/Kutipan Akta Perkawinan'),
(5, 'Fotokopi Akta Kelahiran/Surat Kelahiran bagi keluarga yang mempunyai anak'),
(6, 'Surat Pindah Datang dari tempat asal'),
(7, 'Surat Keterangan Kematian dari Rumah Sakit, Rumah Bersalin Puskesmas, atau visum Dokter'),
(8, 'Surat Keterangan Cerai'),
(9, 'Fotokopi Ijasah Terakhir'),
(10, 'SK. PNS/KARIP/SK. TNI ÔÇô POLRI'),
(11, 'Surat Keterangan Kematian dari Kepala Desa/Kelurahan'),
(12, 'Surat imigrasi / STMD (Surat Tanda Melapor Diri)');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sentitems`
--

CREATE TABLE `sentitems` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `InsertIntoDB` timestamp NOT NULL DEFAULT current_timestamp(),
  `SendingDateTime` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Struktur dari tabel `setting_aplikasi`
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
-- Dumping data untuk tabel `setting_aplikasi`
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
(11, 'web_theme', 'desa/tema-natra', 'Tema penampilan modul web', '', 'web'),
(12, 'offline_mode', '0', 'Apakah modul web akan ditampilkan atau tidak', 'option-kode', ''),
(13, 'enable_track', '1', 'Apakah akan mengirimkan data statistik ke tracker', 'boolean', ''),
(14, 'dev_tracker', '', 'Host untuk tracker pada development', '', 'development'),
(16, 'google_key', '', 'Google API Key untuk Google Maps', '', 'web'),
(17, 'libreoffice_path', '', 'Path tempat instal libreoffice di server SID', '', ''),
(18, 'sumber_gambar_slider', '2', 'Sumber gambar slider besar', NULL, NULL),
(19, 'sebutan_singkatan_kadus', 'kawil', 'Sebutan singkatan jabatan kepala dusun', NULL, NULL),
(20, 'current_version', '20.06', 'Versi sekarang untuk migrasi', NULL, 'readonly'),
(21, 'timezone', 'Asia/Jakarta', 'Zona waktu perekaman waktu dan tanggal', NULL, NULL),
(23, 'web_artikel_per_page', '8', 'Jumlah artikel dalam satu halaman', 'int', 'web_theme'),
(24, 'penomoran_surat', '2', 'Penomoran surat mulai dari satu (1) setiap tahun', 'option', NULL),
(25, 'dashboard_program_bantuan', '1', 'ID program bantuan yang ditampilkan di dashboard', 'int', 'dashboard'),
(26, 'panjang_nomor_surat', '', 'Nomor akan diisi \'0\' di sebelah kiri, kalau perlu', 'int', 'surat'),
(27, 'warna_tema_admin', 'skin-purple', 'Warna dasar tema komponen Admin', 'option-value', NULL),
(28, 'format_nomor_surat', '[kode_surat]/[nomor_surat, 3]/PEM/[tahun]', 'Fomat penomoran surat', NULL, NULL),
(30, 'penggunaan_server', '1	', 'Setting penggunaan server', 'int', 'sistem'),
(31, 'daftar_penerima_bantuan', '1', 'Apakah akan tampilkan daftar penerima bantuan di statistik halaman muka', 'boolean', 'conf_web'),
(32, 'apbdes_footer', '1', 'Apakah akan tampilkan grafik APBDes di halaman muka', 'boolean', 'conf_web'),
(33, 'apbdes_footer_all', '1', 'Apakah akan tampilkan grafik APBDes di semua halaman', 'boolean', 'conf_web'),
(34, 'apbdes_manual_input', '1', 'Apakah akan tampilkan grafik APBDes yang diinput secara manual', 'boolean', 'conf_web'),
(35, 'covid_data', '1', 'Apakah akan tampilkan status Covid-19 Provinsi di halaman muka', 'boolean', 'conf_web'),
(36, 'covid_desa', '1', 'Apakah akan tampilkan status Covid-19 Desa di halaman muka', 'boolean', 'conf_web'),
(37, 'covid_rss', '0', 'Apakah akan tampilkan RSS Covid-19 di halaman muka', 'boolean', 'conf_web'),
(38, 'provinsi_covid', '62', 'Kode provinsi status Covid-19 ', '', 'conf_web'),
(39, 'statistik_chart_3d', '1', 'Apakah akan tampilkan Statistik Chart 3D', 'boolean', 'conf_web');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_aplikasi_options`
--

CREATE TABLE `setting_aplikasi_options` (
  `id` int(11) NOT NULL,
  `id_setting` int(11) NOT NULL,
  `value` varchar(512) NOT NULL,
  `kode` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `setting_aplikasi_options`
--

INSERT INTO `setting_aplikasi_options` (`id`, `id_setting`, `value`, `kode`) VALUES
(1, 24, 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk semua surat layanan', NULL),
(2, 24, 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk setiap surat layanan dengan jenis yang sama', NULL),
(3, 24, 'Nomor berurutan untuk keseluruhan surat layanan, masuk dan keluar', NULL),
(4, 27, 'skin-blue', NULL),
(5, 27, 'skin-blue-light', NULL),
(6, 27, 'skin-yellow', NULL),
(7, 27, 'skin-yellow-light', NULL),
(8, 27, 'skin-green', NULL),
(9, 27, 'skin-green-light', NULL),
(10, 27, 'skin-purple', NULL),
(11, 27, 'skin-purple-light', NULL),
(12, 27, 'skin-red', NULL),
(13, 27, 'skin-red-light', NULL),
(14, 27, 'skin-black', NULL),
(15, 27, 'skin-black-light', NULL),
(16, 12, 'Web bisa diakses publik', 0),
(17, 12, 'Web hanya bisa diakses petugas web', 1),
(18, 12, 'Web non-aktif sama sekali', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_modul`
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
-- Dumping data untuk tabel `setting_modul`
--

INSERT INTO `setting_modul` (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES
(1, 'Home', 'hom_sid', 1, 'fa-home', 1, 2, 1, 'fa fa-home', 0),
(2, 'Kependudukan', '', 1, 'fa-users', 3, 2, 0, 'fa fa-users', 0),
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
(15, 'Sekretariat', 'surat_keluar/clear', 1, 'fa-archive', 5, 2, 0, 'fa fa-archive', 0),
(17, 'Identitas [Desa]', 'hom_desa/konfigurasi', 1, 'fa-id-card', 2, 2, 0, '', 200),
(18, 'Pemerintahan [Desa]', 'pengurus/clear', 1, 'fa-sitemap', 3, 2, 0, '', 200),
(20, 'Wilayah Administratif', 'sid_core/clear', 1, 'fa-map', 2, 2, 0, '', 200),
(21, 'Penduduk', 'penduduk/clear', 1, 'fa-user', 2, 2, 0, '', 2),
(22, 'Keluarga', 'keluarga/clear', 1, 'fa-users', 3, 2, 0, '', 2),
(23, 'Rumah Tangga', 'rtm/clear', 1, 'fa-venus-mars', 4, 2, 0, '', 2),
(24, 'Kelompok', 'kelompok/clear', 1, 'fa-sitemap', 5, 2, 0, '', 2),
(25, 'Data Suplemen', 'suplemen', 1, 'fa-slideshare', 6, 2, 0, '', 2),
(26, 'Calon Pemilih', 'dpt/clear', 1, 'fa-podcast', 7, 2, 0, '', 2),
(27, 'Statistik Kependudukan', 'statistik/clear', 1, 'fa-bar-chart', 1, 2, 0, '', 3),
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
(51, 'Galeri', 'gallery/clear', 1, 'fa-image', 5, 5, 0, '', 13),
(52, 'Informasi Publik', 'dokumen/clear', 1, 'fa-file-text', 4, 4, 0, '', 15),
(53, 'Media Sosial', 'sosmed', 1, 'fa-facebook', 7, 4, 0, '', 13),
(54, 'Slider', 'web/slider', 1, 'fa-film', 8, 4, 0, '', 13),
(55, 'Kotak Pesan', 'mailbox/clear', 1, 'fa-wechat', 1, 2, 0, '', 14),
(56, 'Pendaftar Layanan Mandiri', 'mandiri/clear', 1, 'fa-500px', 2, 2, 0, '', 14),
(57, 'Surat Masuk', 'surat_masuk/clear', 1, 'fa-sign-in', 1, 2, 0, '', 15),
(58, 'Surat Keluar', 'surat_keluar/clear', 1, 'fa-sign-out', 2, 2, 0, '', 15),
(61, 'Inventaris', 'inventaris_tanah', 1, 'fa-cubes', 5, 2, 0, '', 15),
(62, 'Peta', 'gis/clear', 1, 'fa-globe', 1, 2, 0, 'fa fa-globe', 9),
(63, 'Klasfikasi Surat', 'klasifikasi/clear', 1, 'fa-code', 10, 2, 0, 'fa-code', 15),
(64, 'Teks Berjalan', 'teks_berjalan', 1, 'fa-ellipsis-h', 9, 2, 0, 'fa-ellipsis-h', 13),
(65, 'Kategori', 'kategori', 1, '', 0, 0, 2, '', 49),
(66, 'Log Penduduk', 'penduduk_log', 1, '', 0, 0, 2, '', 21),
(67, 'analisis_kategori', 'analisis_kategori', 1, '', 0, 0, 2, '', 5),
(68, 'analisis_indikator', 'analisis_indikator', 1, '', 0, 0, 2, '', 5),
(69, 'analisis_klasifikasi', 'analisis_klasifikasi', 1, '', 0, 0, 2, '', 5),
(70, 'analisis_periode', 'analisis_periode', 1, '', 0, 0, 2, '', 5),
(71, 'analisis_respon', 'analisis_respon', 1, '', 0, 0, 2, '', 5),
(72, 'analisis_laporan', 'analisis_laporan', 1, '', 0, 0, 2, '', 5),
(73, 'analisis_statistik_jawaban', 'analisis_statistik_jawaban', 1, '', 0, 0, 2, '', 5),
(74, 'Wilayah', 'wilayah', 1, '', 0, 0, 2, '', 21),
(75, 'api_inventaris_asset', 'api_inventaris_asset', 1, '', 0, 0, 2, '', 61),
(76, 'api_inventaris_gedung', 'api_inventaris_gedung', 1, '', 0, 0, 2, '', 61),
(77, 'api_inventaris_gedung', 'api_inventaris_gedung', 1, '', 0, 0, 2, '', 61),
(78, 'api_inventaris_jalan', 'api_inventaris_jalan', 1, '', 0, 0, 2, '', 61),
(79, 'api_inventaris_konstruksi', 'api_inventaris_kontruksi', 1, '', 0, 0, 2, '', 61),
(80, 'api_inventaris_peralatan', 'api_inventaris_peralatan', 1, '', 0, 0, 2, '', 61),
(81, 'api_inventaris_tanah', 'api_inventaris_tanah', 1, '', 0, 0, 2, '', 61),
(82, 'inventaris_asset', 'inventaris_asset', 1, '', 0, 0, 2, '', 61),
(83, 'inventaris_gedung', 'inventaris_gedung', 1, '', 0, 0, 2, '', 61),
(84, 'inventaris_jalan', 'inventaris_jalan', 1, '', 0, 0, 2, '', 61),
(85, 'inventaris_kontruksi', 'inventaris_kontruksi', 1, '', 0, 0, 2, '', 61),
(86, 'inventaris_peralatan', 'inventaris_peralatan', 1, '', 0, 0, 2, '', 61),
(87, 'laporan_inventaris', 'laporan_inventaris', 1, '', 0, 0, 2, '', 61),
(88, 'plan', 'plan', 1, '', 0, 0, 2, '', 8),
(89, 'point', 'point', 1, '', 0, 0, 2, '', 8),
(90, 'garis', 'garis', 1, '', 0, 0, 2, '', 8),
(91, 'line', 'line', 1, '', 0, 0, 2, '', 8),
(92, 'area', 'area', 1, '', 0, 0, 2, '', 8),
(93, 'polygon', 'polygon', 1, '', 0, 0, 2, '', 8),
(94, 'Kategori Kelompok', 'kelompok_master', 1, '', 0, 0, 2, '', 24),
(95, 'Produk Hukum', 'dokumen_sekretariat/peraturan_desa', 1, 'fa-book', 3, 2, 0, '', 15),
(96, 'Informasi Publik', 'informasi_publik', 1, '', 0, 0, 2, '', 52),
(97, 'Daftar Persyaratan', 'surat_mohon', 1, 'fa fa-book', 5, 2, 0, '', 4),
(98, 'Permohonan Surat', 'permohonan_surat_admin/clear', 1, 'fa-files-o', 0, 0, 0, '', 14),
(200, 'Info [Desa]', 'hom_desa', 1, 'fa-dashboard', 2, 2, 1, 'fa fa-home', 0),
(201, 'Keuangan', 'keuangan', 1, 'fa-balance-scale', 6, 2, 0, 'fa-balance-scale', 0),
(202, 'Impor Data', 'keuangan/impor_data', 1, 'fa-cloud-upload', 6, 2, 0, 'fa-cloud-upload', 201),
(203, 'Laporan', 'keuangan/laporan', 1, 'fa-bar-chart', 6, 2, 0, 'fa-bar-chart', 201),
(205, 'Pengunjung', 'pengunjung/clear', 1, 'fa-bar-chart', 10, 4, 0, '', 13),
(206, 'Siaga Covid-19', '', 1, 'fa-heartbeat', 0, 2, 0, 'fa fa-heartbeat', 0),
(207, 'Pendataan', 'covid19', 1, 'fa-list', 1, 2, 0, 'fa fa-list', 206),
(208, 'Pemantauan', 'covid19/pantau', 1, 'fa-check', 2, 2, 0, 'fa fa-check', 206),
(211, 'Pengaturan', 'setting/web', 1, 'fa-gear', 11, 4, 0, 'fa-gear', 13);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_sms`
--

CREATE TABLE `setting_sms` (
  `autoreply_text` varchar(160) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `setting_sms`
--

INSERT INTO `setting_sms` (`autoreply_text`) VALUES
('Terima kasih pesan Anda telah kami terima.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `suplemen`
--

CREATE TABLE `suplemen` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `sasaran` tinyint(4) DEFAULT NULL,
  `keterangan` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `suplemen_terdata`
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
-- Struktur dari tabel `surat_keluar`
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
-- Struktur dari tabel `surat_masuk`
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
-- Struktur dari tabel `syarat_surat`
--

CREATE TABLE `syarat_surat` (
  `id` int(10) NOT NULL,
  `surat_format_id` int(10) NOT NULL,
  `ref_syarat_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `syarat_surat`
--

INSERT INTO `syarat_surat` (`id`, `surat_format_id`, `ref_syarat_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 2, 2),
(5, 2, 3),
(6, 3, 2),
(7, 3, 3),
(8, 5, 1),
(9, 5, 2),
(10, 5, 3),
(11, 6, 1),
(12, 6, 2),
(13, 6, 3),
(14, 8, 1),
(15, 8, 2),
(16, 8, 3),
(17, 9, 2),
(18, 9, 3),
(19, 10, 1),
(20, 10, 2),
(21, 10, 3),
(22, 11, 1),
(23, 11, 2),
(24, 11, 3),
(25, 12, 1),
(26, 12, 2),
(27, 12, 3),
(28, 13, 1),
(29, 13, 2),
(30, 13, 3),
(31, 14, 1),
(32, 14, 2),
(33, 14, 3),
(34, 15, 1),
(35, 15, 2),
(36, 15, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sys_traffic`
--

CREATE TABLE `sys_traffic` (
  `Tanggal` date NOT NULL,
  `ipAddress` text NOT NULL,
  `Jumlah` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `sys_traffic`
--

INSERT INTO `sys_traffic` (`Tanggal`, `ipAddress`, `Jumlah`) VALUES
('2014-11-15', '::1{}', 1),
('2014-11-16', '::1{}', 1),
('2014-11-18', '', 3),
('2014-11-21', '', 3),
('2014-11-26', '::1{}', 1),
('2014-12-03', '127.0.0.1{}', 1),
('2014-12-04', '', 3),
('2014-12-05', '', 5),
('2014-12-06', '127.0.0.1{}', 1),
('2014-12-08', '127.0.0.1{}', 1),
('2014-12-09', '127.0.0.1{}', 1),
('2014-12-10', '', 3),
('2016-05-25', '', 2),
('2016-05-26', '', 4),
('2016-05-27', '', 2),
('2016-05-28', '10.0.2.2{}', 1),
('2016-05-29', '', 2),
('2016-05-30', '10.0.2.2{}', 1),
('2016-05-31', '', 3),
('2016-06-01', '10.0.2.2{}', 1),
('2016-08-23', '', 6),
('2016-08-24', '', 7),
('2016-08-26', '', 8),
('2016-08-27', '192.168.1.66{}', 1),
('2016-08-28', '', 3),
('2016-08-29', '', 2),
('2016-08-30', '', 3),
('2016-08-31', '127.0.0.1{}', 1),
('2016-09-02', '', 4),
('2016-09-03', '', 2),
('2016-09-04', '10.0.2.2{}', 1),
('2016-09-05', '', 2),
('2016-09-07', '', 2),
('2016-09-08', '10.0.2.2{}', 1),
('2016-09-09', '', 4),
('2016-09-10', '', 4),
('2016-09-11', '', 2),
('2016-09-14', '', 4),
('2016-12-02', '::1{}', 55),
('2017-07-16', '10.0.2.2{}', 1),
('2017-12-02', '::1{}', 61),
('2018-05-28', '', 3),
('2018-05-29', '10.0.2.2{}', 1),
('2018-11-30', '192.168.33.1{}', 1),
('2018-12-02', '::1{}', 79),
('2019-01-02', '::1{}', 88),
('2019-06-01', '::1{}', 76),
('2019-12-01', '::1{}', 71),
('2020-01-01', '::1{}', 102),
('2020-01-08', '::1{}', 177),
('2020-01-15', '::1{}', 154),
('2020-01-31', '::1{}', 120),
('2020-02-02', '::1{}', 67),
('2020-02-03', '::1{}', 98),
('2020-02-04', '::1{}', 201),
('2020-02-05', '::1{}', 187),
('2020-02-06', '::1{}', 177),
('2020-02-07', '::1{}', 156),
('2020-02-08', '::1{}', 145),
('2020-02-09', '::1{}', 176),
('2020-02-10', '::1{}', 101),
('2020-02-11', '::1{}', 120),
('2020-02-12', '::1{}', 123),
('2020-02-13', '::1{}', 112),
('2020-02-14', '::1{}', 113),
('2020-02-15', '::1{}', 145),
('2020-02-16', '::1{}', 122),
('2020-02-17', '::1{}', 100),
('2020-02-18', '::1{}', 237),
('2020-02-19', '::1{}', 242),
('2020-02-20', '::1{}', 210),
('2020-02-21', '::1{}', 209),
('2020-02-22', '::1{}', 201),
('2020-02-23', '::1{}', 206),
('2020-02-24', '::1{}', 190),
('2020-02-25', '::1{}', 191),
('2020-02-26', '::1{}', 154),
('2020-02-27', '::1{}', 131),
('2020-02-28', '::1{}', 98),
('2020-02-29', '::1{}', 91),
('2020-06-06', '::1{}', 1),
('2020-06-10', '', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `teks_berjalan`
--

CREATE TABLE `teks_berjalan` (
  `id` int(11) NOT NULL,
  `teks` text DEFAULT NULL,
  `urut` int(5) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `tautan` varchar(150) DEFAULT NULL,
  `judul_tautan` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `teks_berjalan`
--

INSERT INTO `teks_berjalan` (`id`, `teks`, `urut`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `tautan`, `judul_tautan`) VALUES
(1, 'Ini contoh teks berjalan. Isi dengan tulisan yang menampilkan suatu ciri atau kegiatan penting di desa anda.', NULL, '2019-05-28 15:45:28', 1, '2019-05-28 15:45:28', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_aset`
--

CREATE TABLE `tweb_aset` (
  `id_aset` int(11) NOT NULL,
  `golongan` varchar(11) NOT NULL,
  `bidang` varchar(11) NOT NULL,
  `kelompok` varchar(11) NOT NULL,
  `sub_kelompok` varchar(11) NOT NULL,
  `sub_sub_kelompok` varchar(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tweb_aset`
--

INSERT INTO `tweb_aset` (`id_aset`, `golongan`, `bidang`, `kelompok`, `sub_kelompok`, `sub_sub_kelompok`, `nama`) VALUES
(1, '1', '00', '00', '00', '000', 'TANAH'),
(2, '1', '01', '00', '00', '000', 'TANAH DESA'),
(3, '1', '01', '01', '00', '000', 'TANAH KAS DESA'),
(4, '1', '01', '01', '01', '000', 'TANAH BENGKOK'),
(5, '1', '01', '01', '01', '001', 'TANAH BENGKOK KEPALA DESA'),
(6, '1', '01', '01', '01', '999', 'TANAH BENGKOK LAINNYA'),
(7, '1', '01', '01', '02', '000', 'TANAH BONDO'),
(8, '1', '01', '01', '03', '000', 'TANAH KALAKERAN NEGERI'),
(9, '1', '01', '01', '04', '000', 'TANAH PECATU'),
(10, '1', '01', '01', '05', '000', 'TANAH PENGAREM-AREM'),
(11, '1', '01', '01', '06', '000', 'TANAH TITISARA'),
(12, '1', '01', '02', '00', '000', 'TANAH PERKAMPUNGAN'),
(13, '1', '01', '02', '01', '000', 'TANAH PERKAMPUNGAN'),
(14, '1', '01', '02', '01', '001', 'TANAH PERKAMPUNGAN'),
(15, '1', '01', '02', '01', '999', 'TANAH PERKAMPUNGAN LAINNYA'),
(16, '1', '01', '02', '02', '000', 'EMPLASMEN'),
(17, '1', '01', '02', '02', '001', 'EMPLASMEN'),
(18, '1', '01', '02', '02', '999', 'EMPLASMEN LAINNYA'),
(19, '1', '01', '02', '03', '000', 'TANAH KUBURAN'),
(20, '1', '01', '02', '03', '001', 'TANAH KUBURAN ISLAM'),
(21, '1', '01', '02', '03', '002', 'TANAH KUBURAN KRISTEN'),
(22, '1', '01', '02', '03', '003', 'TANAH KUBURAN CINA'),
(23, '1', '01', '02', '03', '004', 'TANAH KUBURAN HINDU'),
(24, '1', '01', '02', '03', '005', 'TANAH KUBURAN BUDHA'),
(25, '1', '01', '02', '03', '006', 'TANAH MAKAM PAHLAWAN'),
(26, '1', '01', '02', '03', '007', 'TANAH KUBURAN TEMPAT BENDA BERSEJARAH'),
(27, '1', '01', '02', '03', '008', 'TANAH MAKAM UMUM/KUBURAN UMUM'),
(28, '1', '01', '02', '03', '999', 'TANAH KUBURAN LAINNYA'),
(29, '1', '01', '03', '00', '000', 'TANAH PERTANIAN'),
(30, '1', '01', '03', '01', '000', 'SAWAH SATU TAHUN DITANAMI'),
(31, '1', '01', '03', '01', '001', 'SAWAH DITANAMI PADI'),
(32, '1', '01', '03', '01', '002', 'SAWAH DITANAMI PALAWIJA'),
(33, '1', '01', '03', '01', '003', 'SAWAH DITANAMI TEBU'),
(34, '1', '01', '03', '01', '004', 'SAWAH DITANAMI SAYURAN'),
(35, '1', '01', '03', '01', '005', 'SAWAH DITANAMI TEMBAKAU'),
(36, '1', '01', '03', '01', '006', 'SAWAH DITANAMI ROSELLA'),
(37, '1', '01', '03', '01', '999', 'SAWAH DITANAMI LAINNYA'),
(38, '1', '01', '03', '02', '000', 'TANAH KERING/TEGALAN'),
(39, '1', '01', '03', '02', '001', 'TANAH KERING DITANAMI BUAH-BUAHAN'),
(40, '1', '01', '03', '02', '002', 'TANAH KERING DITANAMI TEMBAKAU'),
(41, '1', '01', '03', '02', '003', 'TANAH KERING DITANAMI JAGUNG'),
(42, '1', '01', '03', '02', '004', 'TANAH KERING DITANAMI KETELA POHON'),
(43, '1', '01', '03', '02', '005', 'TANAH KERING DITANAMI KACANG TANAH'),
(44, '1', '01', '03', '02', '006', 'TANAH KERING DITANAMI KACANG HIJAU'),
(45, '1', '01', '03', '02', '007', 'TANAH KERING DITANAMI KEDELAI'),
(46, '1', '01', '03', '02', '008', 'TANAH KERING DITANAMI UBI JALAR'),
(47, '1', '01', '03', '02', '009', 'TANAH KERING DITANAMI KELADI'),
(48, '1', '01', '03', '02', '999', 'TANAH KERING DITANAMI LAINNYA'),
(49, '1', '01', '03', '03', '000', 'LADANG'),
(50, '1', '01', '03', '03', '001', 'LADANG PADI'),
(51, '1', '01', '03', '03', '002', 'LADANG JAGUNG'),
(52, '1', '01', '03', '03', '003', 'LADANG KETELA POHON'),
(53, '1', '01', '03', '03', '004', 'LADANG KACANG TANAH'),
(54, '1', '01', '03', '03', '005', 'LADANG KACANG HIJAU'),
(55, '1', '01', '03', '03', '006', 'LADANG KEDELAI'),
(56, '1', '01', '03', '03', '007', 'LADANG UBI JALAR'),
(57, '1', '01', '03', '03', '008', 'LADANG KELADI'),
(58, '1', '01', '03', '03', '009', 'LADANG BENGKUANG'),
(59, '1', '01', '03', '03', '010', 'LADANG APEL'),
(60, '1', '01', '03', '03', '011', 'LADANG KENTANG'),
(61, '1', '01', '03', '03', '012', 'LADANG JERUK'),
(62, '1', '01', '03', '03', '999', 'LADANG LAINNYA'),
(63, '1', '01', '04', '00', '000', 'TANAH PERKEBUNAN'),
(64, '1', '01', '04', '01', '000', 'TANAH PERKEBUNAN'),
(65, '1', '01', '04', '01', '001', 'TANAH PERKEBUNAN KARET'),
(66, '1', '01', '04', '01', '002', 'TANAH PERKEBUNAN KOPI'),
(67, '1', '01', '04', '01', '003', 'TANAH PERKEBUNAN KELAPA'),
(68, '1', '01', '04', '01', '004', 'TANAH PERKEBUNAN RANDU'),
(69, '1', '01', '04', '01', '005', 'TANAH PERKEBUNAN LADA'),
(70, '1', '01', '04', '01', '006', 'TANAH PERKEBUNAN TEH'),
(71, '1', '01', '04', '01', '007', 'TANAH PERKEBUNAN KINA'),
(72, '1', '01', '04', '01', '008', 'TANAH PERKEBUNAN COKLAT'),
(73, '1', '01', '04', '01', '009', 'TANAH PERKEBUNAN KELAPA SAWIT'),
(74, '1', '01', '04', '01', '010', 'TANAH PERKEBUNAN SEREH'),
(75, '1', '01', '04', '01', '011', 'TANAH PERKEBUNAN CENGKEH'),
(76, '1', '01', '04', '01', '012', 'TANAH PERKEBUNAN PALA'),
(77, '1', '01', '04', '01', '013', 'TANAH PERKEBUNAN SAGU'),
(78, '1', '01', '04', '01', '014', 'TANAH PERKEBUNAN JAMBU MENTE'),
(79, '1', '01', '04', '01', '015', 'TANAH PERKEBUNAN TENGKAWANG'),
(80, '1', '01', '04', '01', '016', 'TANAH PERKEBUNAN MINYAK KAYU PUTIH'),
(81, '1', '01', '04', '01', '017', 'TANAH PERKEBUNAN KAYU MANIS'),
(82, '1', '01', '04', '01', '018', 'TANAH PERKEBUNAN PETAI'),
(83, '1', '01', '04', '01', '999', 'TANAH PERKEBUNAN LAINNYA'),
(84, '1', '01', '05', '00', '000', 'TANAH HUTAN'),
(85, '1', '01', '05', '01', '000', 'TANAH HUTAN LEBAT (DITANAMI JENIS KAYU UTAMA)'),
(86, '1', '01', '05', '01', '001', 'TANAH HUTAN MERANTI'),
(87, '1', '01', '05', '01', '002', 'TANAH HUTAN RASAMALA'),
(88, '1', '01', '05', '01', '003', 'TANAH HUTAN BULIAN'),
(89, '1', '01', '05', '01', '004', 'TANAH HUTAN MEDANG'),
(90, '1', '01', '05', '01', '005', 'TANAH HUTAN JELUTUNG'),
(91, '1', '01', '05', '01', '006', 'TANAH HUTAN RAMIN'),
(92, '1', '01', '05', '01', '007', 'TANAH HUTAN PUSPA'),
(93, '1', '01', '05', '01', '008', 'TANAH HUTAN SUNINTEM'),
(94, '1', '01', '05', '01', '009', 'TANAH HUTAN ALBENIA'),
(95, '1', '01', '05', '01', '010', 'TANAH HUTAN KAYU BESI/ULIN'),
(96, '1', '01', '05', '01', '999', 'HUTAN LEBAT LAINNYA'),
(97, '1', '01', '05', '02', '000', 'TANAH HUTAN BELUKAR'),
(98, '1', '01', '05', '02', '001', 'TANAH HUTAN SEMAK-SEMAK'),
(99, '1', '01', '05', '02', '002', 'HUTAN BELUKAR'),
(100, '1', '01', '05', '02', '003', 'HUTAN BELUKAR LAINNYA'),
(101, '1', '01', '05', '03', '000', 'HUTAN TANAMAN JENIS'),
(102, '1', '01', '05', '03', '001', 'HUTAN TANAMAN JATI'),
(103, '1', '01', '05', '03', '002', 'HUTAN TANAMAN PINUS'),
(104, '1', '01', '05', '03', '003', 'HUTAN TANAMAN ROTAN'),
(105, '1', '01', '05', '03', '999', 'HUTAN TANAMAN JENIS LAINNYA'),
(106, '1', '01', '05', '04', '000', 'HUTAN ALAM SEJENIS/HUTAN RAWA'),
(107, '1', '01', '05', '04', '001', 'HUTAN BAKAU'),
(108, '1', '01', '05', '04', '002', 'HUTAN CEMARA (YANG TIDAK DITANAMAN)'),
(109, '1', '01', '05', '04', '003', 'HUTAN GALAM'),
(110, '1', '01', '05', '04', '004', 'HUTAN NIPAH'),
(111, '1', '01', '05', '04', '005', 'HUTAN BAMBU'),
(112, '1', '01', '05', '04', '006', 'HUTAN ROTAN'),
(113, '1', '01', '05', '04', '999', 'HUTAN ALAM SEJENIS LAINNYA'),
(114, '1', '01', '05', '05', '000', 'HUTAN UNTUK PENGGUNAAN KHUSUS'),
(115, '1', '01', '05', '05', '001', 'HUTAN CADANGAN'),
(116, '1', '01', '05', '05', '002', 'HUTAN LINDUNG'),
(117, '1', '01', '05', '05', '003', 'HUTAN CAGAR ALAM'),
(118, '1', '01', '05', '05', '004', 'HUTAN TAMAN WISATA'),
(119, '1', '01', '05', '05', '005', 'HUTAN TAMAN BURUNG'),
(120, '1', '01', '05', '05', '006', 'HUTAN SUAKA MARGA SATWA'),
(121, '1', '01', '05', '05', '007', 'HUTAN TAMAN NASIONAL'),
(122, '1', '01', '05', '05', '008', 'HUTAN PRODUKSI'),
(123, '1', '01', '05', '05', '999', 'HUTAN UNTUK PENGGUNAAN KHUSUS LAINNYA'),
(124, '1', '01', '06', '00', '000', 'TANAH KEBUN CAMPURAN'),
(125, '1', '01', '06', '01', '000', 'TANAH YANG TIDAK ADA JARINGAN PENGAIRAN'),
(126, '1', '01', '06', '01', '001', 'TANAMAN RUPA-RUPA'),
(127, '1', '01', '06', '01', '999', 'TANAH KEBUN CAMPURAN LAINNYA'),
(128, '1', '01', '06', '02', '000', 'TUMBUH LIAR BERCAMPUR JENIS LAIN'),
(129, '1', '01', '06', '02', '001', 'JENIS TANAMAN RUPA-RUPA & TIDAK JELAS MANA YANG MENONJOL'),
(130, '1', '01', '06', '02', '002', 'TANAMAN LUAR PERKARANGAN'),
(131, '1', '01', '06', '02', '999', 'TUMBUH LIAR BERCAMPUR JENIS LAINNYA'),
(132, '1', '01', '07', '00', '000', 'TANAH KOLAM IKAN'),
(133, '1', '01', '07', '01', '000', 'TAMBAK'),
(134, '1', '01', '07', '01', '001', 'TAMBAK'),
(135, '1', '01', '07', '01', '999', 'TAMBAK LAINNYA'),
(136, '1', '01', '07', '02', '000', 'AIR TAWAR'),
(137, '1', '01', '07', '02', '001', 'KOLAM AIR TAWAR'),
(138, '1', '01', '07', '02', '999', 'AIR TAWAR LAINNYA'),
(139, '1', '01', '08', '00', '000', 'TANAH DANAU / RAWA'),
(140, '1', '01', '08', '01', '000', 'RAWA'),
(141, '1', '01', '08', '01', '001', 'RAWA'),
(142, '1', '01', '08', '01', '999', 'RAWA LAINNYA'),
(143, '1', '01', '08', '02', '000', 'DANAU'),
(144, '1', '01', '08', '02', '001', 'SANAU/SITU'),
(145, '1', '01', '08', '02', '002', 'WADUK'),
(146, '1', '01', '08', '02', '999', 'DANAU LAINNYA'),
(147, '1', '01', '09', '00', '000', 'TANAH TANDUS / RUSAK'),
(148, '1', '01', '09', '01', '000', 'TANAH TANDUS'),
(149, '1', '01', '09', '01', '001', 'BERBATU-BATU'),
(150, '1', '01', '09', '01', '002', 'LONGSOR'),
(151, '1', '01', '09', '01', '003', 'TANAH LAHAR'),
(152, '1', '01', '09', '01', '004', 'TANAH BERPASIR/PASIR'),
(153, '1', '01', '09', '01', '005', 'TANAH PENGAMBILAN/KUASI'),
(154, '1', '01', '09', '01', '999', 'TANAH TANDUS LAINNYA'),
(155, '1', '01', '09', '02', '000', 'TANAH RUSAK'),
(156, '1', '01', '09', '02', '001', 'TANAH YANG TEREROSI/LONGSOR'),
(157, '1', '01', '09', '02', '002', 'BEKAS TAMBANG/GALIAN'),
(158, '1', '01', '09', '02', '003', 'BEKAS SAWAH/RAWA'),
(159, '1', '01', '09', '02', '999', 'TANAH RUSAK LAINNYA'),
(160, '1', '01', '10', '00', '000', 'TANAH ALANG-ALANG DAN PADANG RUMPUT'),
(161, '1', '01', '10', '01', '000', 'ALANG-ALANG'),
(162, '1', '01', '10', '01', '001', 'ALANG-ALANG'),
(163, '1', '01', '10', '01', '999', 'ALANG-ALANG LAINNYA'),
(164, '1', '01', '10', '02', '000', 'PADANG RUMPUT'),
(165, '1', '01', '10', '02', '001', 'SEMAK BELUKAR'),
(166, '1', '01', '10', '02', '002', 'PADANG RUMPUT'),
(167, '1', '01', '10', '02', '999', 'PADANG RUMPUT LAINNYA'),
(168, '1', '01', '11', '00', '000', 'TANAH PERTAMBANGAN'),
(169, '1', '01', '11', '01', '000', 'TANAH PERTAMBANGAN'),
(170, '1', '01', '11', '01', '001', 'TANAH PERTAMBANGAN INTAN'),
(171, '1', '01', '11', '01', '002', 'TANAH PERTAMBANGAN EMAS'),
(172, '1', '01', '11', '01', '003', 'TANAH PERTAMBANGAN PERAK'),
(173, '1', '01', '11', '01', '004', 'TANAH PERTAMBANGAN NEKEL'),
(174, '1', '01', '11', '01', '005', 'TANAH PERTAMBANGAN TIMAH'),
(175, '1', '01', '11', '01', '006', 'TANAH PERTAMBANGAN URANIUM'),
(176, '1', '01', '11', '01', '007', 'TANAH PERTAMBANGAN TEMBAGA'),
(177, '1', '01', '11', '01', '008', 'TANAH PERTAMBANGAN MINYAK BUMI'),
(178, '1', '01', '11', '01', '009', 'TANAH PERTAMBANGAN BATU BARA'),
(179, '1', '01', '11', '01', '010', 'TANAH PERTAMBANGAN KOSLIN'),
(180, '1', '01', '11', '01', '011', 'TANAH PERTAMBANGAN BATU BARA BERHARGA'),
(181, '1', '01', '11', '01', '012', 'TANAH PERTAMBANGAN PASIR BERHARGA'),
(182, '1', '01', '11', '01', '999', 'TANAH PERTAMBANGAN LAINNYA'),
(183, '1', '01', '12', '00', '000', 'TANAH UNTUK BANGUNAN GEDUNG'),
(184, '1', '01', '12', '01', '000', 'TANAH BANGUNAN PERUMAHAN/GDG. TEMPAT TINGGAL'),
(185, '1', '01', '12', '01', '001', 'TANAH BANGUNAN MESS'),
(186, '1', '01', '12', '01', '002', 'TANAH BANGUNAN WISMA'),
(187, '1', '01', '12', '01', '003', 'TANAH BANGUNAN ASRAMA'),
(188, '1', '01', '12', '01', '004', 'TANAH BANGUNAN PERISTIRAHATAN'),
(189, '1', '01', '12', '01', '005', 'TANAH BANGUNAN BUNGALAOW'),
(190, '1', '01', '12', '01', '006', 'TANAH BANGUNAN COTTAGE'),
(191, '1', '01', '12', '01', '999', 'TANAH BANGUNAN RUMAH TEMPAT TINGGAL LAINNYA'),
(192, '1', '01', '12', '02', '000', 'TANAH UNTUK BANGUNAN GEDUNG PERDAGANGAN'),
(193, '1', '01', '12', '02', '001', 'TANAH BANGUNAN PASAR'),
(194, '1', '01', '12', '02', '002', 'TANAH BANGUNAN PERTOKOAN/RUMAH TOKO'),
(195, '1', '01', '12', '02', '003', 'TANAH BANGUNAN GUDANG'),
(196, '1', '01', '12', '02', '004', 'TANAH BANGUNAN BIOSKOP'),
(197, '1', '01', '12', '02', '005', 'TANAH BANGUNAN HOTEL/PENGINAPAN'),
(198, '1', '01', '12', '02', '006', 'TANAH BANGUNAN TERMINAL DARAT'),
(199, '1', '01', '12', '02', '007', 'TANAH BANGUNAN TERMINAL LAUT'),
(200, '1', '01', '12', '02', '008', 'TANAH BANGUNAN GEDUNG KESENIAN'),
(201, '1', '01', '12', '02', '009', 'TANAH BANGUNAN GEDUNG PAMERAN'),
(202, '1', '01', '12', '02', '010', 'TANAH BANGUNAN GEDUNG PUSAT PERBELANJAAN'),
(203, '1', '01', '12', '02', '011', 'TANAH BANGUNAN APOTIK'),
(204, '1', '01', '12', '02', '999', 'TANAH BANGUNAN GEDUNG PERDAGANGAN LAINNYA'),
(205, '1', '01', '12', '03', '000', 'TANAH UNTUK BANGUNAN INDUSTRI'),
(206, '1', '01', '12', '03', '001', 'TANAH BANGUNAN INDUSTRI MAKANAN'),
(207, '1', '01', '12', '03', '002', 'TANAH BANGUNAN INDUSTRI MINUMAN'),
(208, '1', '01', '12', '03', '003', 'TANAH BANGUNAN INDUSTRI/ALAT RT.'),
(209, '1', '01', '12', '03', '004', 'TANAH BANGUNAN INDUSTRI PAKAIAN/GARMENT'),
(210, '1', '01', '12', '03', '005', 'TANAH BANGUNAN INDUSTRI BESI/LOGAM'),
(211, '1', '01', '12', '03', '006', 'TANAH BANGUNAN INDUSTRI BAJA'),
(212, '1', '01', '12', '03', '007', 'TANAH BANGUNAN INDUSTRI PENGALENGAN'),
(213, '1', '01', '12', '03', '008', 'TANAH BANGUNAN INDUSTRI BENGKEL'),
(214, '1', '01', '12', '03', '009', 'TANAH BANGUNAN INDUSTRI PENYULINGAN  MINYAK'),
(215, '1', '01', '12', '03', '010', 'TANAH BANGUNAN INDUSTRI SEMEN'),
(216, '1', '01', '12', '03', '011', 'TANAH BANGUNAN INDUSTRI BATU BATA/BATAKO'),
(217, '1', '01', '12', '03', '012', 'TANAH BANGUNAN INDUSTRI GENTENG'),
(218, '1', '01', '12', '03', '013', 'TANAH BANGUNAN INDUSTRI PERCETAKAN'),
(219, '1', '01', '12', '03', '014', 'TANAH BANGUNAN INDUSTRI TESKTIL'),
(220, '1', '01', '12', '03', '015', 'TANAH BANGUNAN INDUSTRI OBAT-OBATAN'),
(221, '1', '01', '12', '03', '016', 'TANAH BANGUNAN INDUSTRI ALAT OLAH RAGA'),
(222, '1', '01', '12', '03', '017', 'TANAH BANGUNAN INDUSTRI KENDARAAN/ OTOMOTIF'),
(223, '1', '01', '12', '03', '019', 'TANAH BANGUNAN INDUSTRI PERSENJATAAN'),
(224, '1', '01', '12', '03', '020', 'TANAH BANGUNAN INDUSTRI KAPAL UDARA'),
(225, '1', '01', '12', '03', '021', 'TANAH BANGUNAN INDUSTRI KAPAL LAUT'),
(226, '1', '01', '12', '03', '022', 'TANAH BANGUNAN INDUSTRI KAPAL API'),
(227, '1', '01', '12', '03', '023', 'TANAH BANGUNAN INDUSTRI KERAMIK/MARMER'),
(228, '1', '01', '12', '03', '999', 'TANAH BANGUNAN INDUSTRI LAINNYA'),
(229, '1', '01', '12', '04', '000', 'TANAH UNTUK BANGUNAN TEMPAT KERJA/JASA'),
(230, '1', '01', '12', '04', '001', 'TANAH BANGUNAN KANTOR PEMERINTAH'),
(231, '1', '01', '12', '04', '002', 'TANAH BANGUNAN SEKOLAH'),
(232, '1', '01', '12', '04', '003', 'TANAH BANGUNAN RUMAH SAKIT'),
(233, '1', '01', '12', '04', '004', 'TANAH BANGUNAN APOTIK'),
(234, '1', '01', '12', '04', '005', 'TANAH BANGUNAN TEMPAT IBADAH'),
(235, '1', '01', '12', '04', '006', 'TANAH BANGUNAN DERMAGA'),
(236, '1', '01', '12', '04', '007', 'TANAH BANGUNAN PELABUHAN UDARA'),
(237, '1', '01', '12', '04', '008', 'TANAH BANGUNAN OLAH RAGA'),
(238, '1', '01', '12', '04', '009', 'TANAH BANGUNAN TAMAN/WISATA/REKREASI'),
(239, '1', '01', '12', '04', '010', 'TANAH BANGUNAN BALAI SIDANG/PERTEMUAN'),
(240, '1', '01', '12', '04', '011', 'TANAH BANGUNAN BALAI NIKAH'),
(241, '1', '01', '12', '04', '012', 'TANAH BANGUNAN PUSKESMAS/POSYANDU'),
(242, '1', '01', '12', '04', '013', 'TANAH BANGUNAN POLIKLINIK'),
(243, '1', '01', '12', '04', '014', 'TANAH BANGUNAN LABORATURIUM'),
(244, '1', '01', '12', '04', '015', 'TANAH BANGUNAN FUMIGASI/STERLISASI'),
(245, '1', '01', '12', '04', '016', 'TANAH BANGUNAN KARANTINA'),
(246, '1', '01', '12', '04', '017', 'TANAH BANGUNAN BANGSAL PENGOLAHAN  PONDON KERJA'),
(247, '1', '01', '12', '04', '018', 'TANAH BANGUNAN KANDANG HEWAN'),
(248, '1', '01', '12', '04', '019', 'TANAH BANGUNAN-BANGUNAN PEMBIBITAN'),
(249, '1', '01', '12', '04', '020', 'TANAH BANGUNAN RUMAH PENDINGIN'),
(250, '1', '01', '12', '04', '021', 'TANAH BANGUNAN RUMAH PENGERING'),
(251, '1', '01', '12', '04', '022', 'TANAH BANGUNAN STASIUN PENELITIAN'),
(252, '1', '01', '12', '04', '023', 'TANAH BANGUNAN GEDUNG PELELANGAN IKAN'),
(253, '1', '01', '12', '04', '024', 'TANAH BANGUNAN POS JAGA/MENARA JAGA'),
(254, '1', '01', '12', '04', '999', 'TANAH BANGUNAN TEMPAT KERJA LAINNYA'),
(255, '1', '01', '12', '05', '000', 'TANAH KOSONG'),
(256, '1', '01', '12', '05', '001', 'TANAH SAWAH'),
(257, '1', '01', '12', '05', '002', 'TANAH TEGALAN'),
(258, '1', '01', '12', '05', '003', 'TANAH KEBUN'),
(259, '1', '01', '12', '05', '004', 'KEBUN PEMBIBITAN'),
(260, '1', '01', '12', '05', '999', 'TANAH KOSONG YANG TIDAK DIUSAHAKAN'),
(261, '1', '01', '12', '06', '000', 'TANAH PETERNAKAN'),
(262, '1', '01', '12', '06', '001', 'TANAH PETERNAKAN'),
(263, '1', '01', '12', '06', '999', 'TANAH PETERNAKAN LAINNYA'),
(264, '1', '01', '12', '07', '000', 'TANAH BANGUNAN PENGAIRAN'),
(265, '1', '01', '12', '07', '001', 'TANAH WADUK'),
(266, '1', '01', '12', '07', '002', 'TANAH KOMPLEK BENDUNGAN'),
(267, '1', '01', '12', '07', '003', 'TANAH JARINGAN/SALURAN'),
(268, '1', '01', '12', '07', '999', 'TANAH BANGUNAN PENGAIRAN LAINNYA'),
(269, '1', '01', '12', '08', '000', 'TANAH BANGUNAN JALAN DAN JEMBATAN'),
(270, '1', '01', '12', '08', '001', 'TANAH JALAN'),
(271, '1', '01', '12', '08', '002', 'TANAH JEMBATAN'),
(272, '1', '01', '12', '08', '999', 'TANAH BANGUNAN JALAN DAN JEMBATAN LAINNYA'),
(273, '1', '01', '12', '09', '000', 'TANAH LEMBIRAN/BANTARAN/LEPE-LEPE/SETREN DST'),
(274, '1', '01', '12', '09', '001', 'TANAH LEMBIRAN PENGAIRAN'),
(275, '1', '01', '12', '09', '002', 'TANAH LEMBIRAN JALAN DAN JEMBATAN'),
(276, '1', '01', '12', '09', '999', 'TANAH LEMBIRAN LAINNYA'),
(277, '1', '01', '13', '00', '000', 'TANAH UNTUK BANGUNAN BUKAN GEDUNG'),
(278, '1', '01', '13', '01', '000', 'TANAH LAPANGAN OLAH RAGA'),
(279, '1', '01', '13', '01', '001', 'TANAH LAPANGAN TENIS'),
(280, '1', '01', '13', '01', '002', 'TANAH LAPANGAN BASKET'),
(281, '1', '01', '13', '01', '003', 'TANAH LAPANGAN BADMINTON/BULUTANGKIS'),
(282, '1', '01', '13', '01', '004', 'TANAH LAPANGAN GOLF'),
(283, '1', '01', '13', '01', '005', 'TANAH LAPANGAN SEPAK BOLA'),
(284, '1', '01', '13', '01', '006', 'TANAH LAPANGAN BOLA VOLLY'),
(285, '1', '01', '13', '01', '007', 'TANAH LAPANGAN SEPAK TAKRAW'),
(286, '1', '01', '13', '01', '008', 'TAANH LAPANGAN PACUAN KUDA'),
(287, '1', '01', '13', '01', '009', 'TANAH LAPANGAN BALAP SEPEDA'),
(288, '1', '01', '13', '01', '010', 'TANAH LAPANGAN ATLETIK'),
(289, '1', '01', '13', '01', '011', 'TANAH LAPANGAN SOFTBALL'),
(290, '1', '01', '13', '01', '999', 'TANAH LAPANGAN OLAHRAGA LAINNYA'),
(291, '1', '01', '13', '02', '000', 'TANAH LAPANGAN PARKIR'),
(292, '1', '01', '13', '02', '001', 'TANAH LAPANGAN PARKIR KONTRUKSI BETON'),
(293, '1', '01', '13', '02', '002', 'TANAH LAPANGAN PARKIR KONTRUKSI ASPAL'),
(294, '1', '01', '13', '02', '003', 'TANAH LAPANGAN PARKIR SIRTU (PASIR BATU)'),
(295, '1', '01', '13', '02', '004', 'TANAH LAPANGAN PARKIR KONBLOK'),
(296, '1', '01', '13', '02', '005', 'TANAH LAPANGAN PARKIR TANAH KERAS'),
(297, '1', '01', '13', '02', '999', 'TANAH LAPANGAN PARKIR LAINNYA'),
(298, '1', '01', '13', '03', '000', 'TANAH LAPANGAN PENIMBUN BARANG'),
(299, '1', '01', '13', '03', '001', 'TANAH LAPANGAN PENIMBUN BARANG BELUM DIOLAH'),
(300, '1', '01', '13', '03', '002', 'TANAH LAPANGAN PENIMBUN BARANG JADI'),
(301, '1', '01', '13', '03', '003', 'TANAH LAPANGAN PENIMBUN PEMBUANGAN SAMPAH'),
(302, '1', '01', '13', '03', '004', 'TANAH LAPANGAN PENIMBUN BAHAN BANGUNAN'),
(303, '1', '01', '13', '03', '005', 'TANAH LAPANGAN PENIMBUN BARANG BUKTI'),
(304, '1', '01', '13', '03', '999', 'TANAH LAPANGAN PENIMBUN BARANG LAINNYA'),
(305, '1', '01', '13', '04', '000', 'TANAH LAPANGAN PEMANCAR DAN STUDIO ALAM'),
(306, '1', '01', '13', '04', '001', 'TANAH LAPANGAN PEMANCAR TV/RADIO/RADAR'),
(307, '1', '01', '13', '04', '002', 'TANAH LAPANGAN STUDIO ALAM'),
(308, '1', '01', '13', '04', '003', 'TANAH LAPANGAN PEMANCAR LAINNYA'),
(309, '1', '01', '13', '04', '999', 'TANAH LAPANGAN PEMANCAR DAN STUDIO ALAM LAINNYA'),
(310, '1', '01', '13', '05', '000', 'TANAH LAPANGAN PENGUJIAN/PENGOLAHAN'),
(311, '1', '01', '13', '05', '001', 'TANAH LAPANGAN PENGUJIAN KENDARAAN  BERMOTOR'),
(312, '1', '01', '13', '05', '002', 'TANAH LAPANGAN PENGELOLAAN BAHAN BANGUNAN'),
(313, '1', '01', '13', '05', '999', 'TANAH LAPANGAN PENGUJIAN/PENGOLAHAN LAINNYA'),
(314, '1', '01', '13', '06', '000', 'TANAH LAPANGAN TERBANG'),
(315, '1', '01', '13', '06', '001', 'TANAH LAPANGAN TERBANG PERINTIS'),
(316, '1', '01', '13', '06', '002', 'TANAH LAPNGAN KOMERSIAL'),
(317, '1', '01', '13', '06', '003', 'TANAH LAPANGAN TERBANG KHUSUS/MILITER'),
(318, '1', '01', '13', '06', '004', 'TANAH LAOPANGAN TERBANG OLAH RAGA'),
(319, '1', '01', '13', '06', '005', 'TANAH LAPANGAN TERBANG PENDIDIKAN'),
(320, '1', '01', '13', '06', '999', 'TANAH LAPANGAN TERBANG LAINNYA'),
(321, '1', '01', '13', '07', '000', 'TANAH UNTUK BANGUNAN JALAN'),
(322, '1', '01', '13', '07', '001', 'TANAH UNTUK JALAN NASIONAL'),
(323, '1', '01', '13', '07', '002', 'TANAH UNTUK JALAN PROPINSI'),
(324, '1', '01', '13', '07', '003', 'TANAH UNTUK JALAN KABUPATEN'),
(325, '1', '01', '13', '07', '004', 'TANAH UNTUK JALAN KOTAMADYA'),
(326, '1', '01', '13', '07', '005', 'TANAH UNTUK JALAN DESA'),
(327, '1', '01', '13', '07', '006', 'TANAH UNTUK JALAN TOL'),
(328, '1', '01', '13', '07', '007', 'TANAH UNTUK JALAN KERETA API/LORI'),
(329, '1', '01', '13', '07', '008', 'TANAH UNTUK JALAN LANDASAN PACU PESAWAT TERBANG'),
(330, '1', '01', '13', '07', '009', 'TANAH UNTUK JALAN KHUSUS/KOMPLEK'),
(331, '1', '01', '13', '07', '999', 'TANAH UNTUK BANGUNAN JALAN LAINNYA'),
(332, '1', '01', '13', '08', '000', 'TANAH UNTUK BANGUNAN AIR'),
(333, '1', '01', '13', '08', '001', 'TANAH UNTUK BANGUNAN AIR IRIGASI'),
(334, '1', '01', '13', '08', '002', 'TANAH UNTUK BANGUNAN PENGAIRAN PASANG SURUT'),
(335, '1', '01', '13', '08', '003', 'TANAH UNTUK BANGUNAN PENGEMBANGAN RAWA DAN POLDER'),
(336, '1', '01', '13', '08', '004', 'TANAH UNTUK BANGUNAN PENGAMAN SUNGAI DAN PENANGGULANGAN BENCANA ALAM'),
(337, '1', '01', '13', '08', '005', 'TANAH UNTUK BANGUNAN PENGEMBANGAN SUMBER AIR DAN AIR TNH'),
(338, '1', '01', '13', '08', '006', 'TANAH UNTUK BANGUNAN AIR BERSIH/AIR BAKU'),
(339, '1', '01', '13', '08', '007', 'TANAH UNTUK BANGUNAN AIR KOTOR'),
(340, '1', '01', '13', '08', '999', 'TANAH UNTUK BANGUNAN AIR LAINNYA'),
(341, '1', '01', '13', '09', '000', 'TANAH UNTUK BANGUNAN INSTALASI'),
(342, '1', '01', '13', '09', '001', 'TANAH UNTUK BANGUNAN INSTALASI AIR BERSIH/AIR BAKU'),
(343, '1', '01', '13', '09', '002', 'TANAH UNTUK BANGUNAN INSTALASI AIR KOTOR/AIR LIMBAH'),
(344, '1', '01', '13', '09', '003', 'TANAH UNTUK BANGUNAN INSTALASI PENGELOHAN SAMPAH'),
(345, '1', '01', '13', '09', '004', 'TANAH UNTUK BANGUNAN INSTALASI PENGOLAHAN BAHAN BANGUNAN'),
(346, '1', '01', '13', '09', '005', 'TANAH UNTUK BANGUNAN INSTALASI LISTRIK'),
(347, '1', '01', '13', '09', '006', 'TANAH UNTUK BANGUNAN INSTALASI GARDU LISTRIK'),
(348, '1', '01', '13', '09', '007', 'TANAH UNTUK BANGUNAN PANGOLAHAN LIMBAH'),
(349, '1', '01', '13', '09', '999', 'TANAH UNTUK BANGUNAN INSTALASI LAINNYA'),
(350, '1', '01', '13', '10', '000', 'TANAH UNTUK BANGUNAN JARINGAN'),
(351, '1', '01', '13', '10', '001', 'TANAH UNTUK BANGUNAN JARINGAN AIR BERSIH/AIR BAKU'),
(352, '1', '01', '13', '10', '002', 'TANAH UNTUK BANGUNAN JARINGAN KOMUNIKASI'),
(353, '1', '01', '13', '10', '003', 'TANAH UNTUK BANGUNAN JARINGAN LISTRIK'),
(354, '1', '01', '13', '10', '004', 'TANAH UNTUK BANGUNAN JARINGAN GAS/BBM'),
(355, '1', '01', '13', '10', '999', 'TANAH UNTUK BANGUNAN JARINGAN LAINNYA'),
(356, '1', '01', '13', '11', '000', 'TANAH UNTUK BANGUNAN BERSEJARAH'),
(357, '1', '01', '13', '11', '001', 'TANAH UNTUK MONUMEN'),
(358, '1', '01', '13', '11', '002', 'TANAH UNTUK TUGU PERINGATAN'),
(359, '1', '01', '13', '11', '003', 'TANAH UNTUK TUGU BATAS WILAYAH'),
(360, '1', '01', '13', '11', '004', 'TANAH UNTUK CANDI'),
(361, '1', '01', '13', '11', '005', 'TANAH UNTUK BANGUNAN MOSEUM'),
(362, '1', '01', '13', '11', '006', 'TANAH UNTUK BANGUNAN BERSEJARAH'),
(363, '1', '01', '13', '11', '999', 'TANAH UNTUK BANGUNAN BERSEJARAH LAINNYA'),
(364, '1', '01', '13', '12', '000', 'TANAH UNTUK BANGUNAN GEDUNG OLAH RAGA'),
(365, '1', '01', '13', '12', '001', 'TANAH BANGUNAN SARANA OLAOH RAGA TERBATAS'),
(366, '1', '01', '13', '12', '002', 'TANAH BANGUNAN SARANA OLAH RAGA TERBUKA'),
(367, '1', '01', '13', '12', '999', 'TANAH BANGUNAN SARANA OLAH RAGA LAINNYA'),
(368, '1', '01', '13', '13', '000', 'TANAH UNTUK BANGUNAN TEMPAT IBADAH'),
(369, '1', '01', '13', '13', '001', 'TANAH UNTUK BANGUNAN MESJID'),
(370, '1', '01', '13', '13', '002', 'TANAH UNTUK BANGUNAN GEREJA'),
(371, '1', '01', '13', '13', '003', 'TANAH UNTUK BANGUNAN PURA'),
(372, '1', '01', '13', '13', '004', 'TANAH UNTUK BANGUNAN VIHARA'),
(373, '1', '01', '13', '13', '005', 'TANAH UNTUK BANGUNAN KLENTENG/KUIL'),
(374, '1', '01', '13', '13', '006', 'TANAH UNTUK BANGUNAN KREMATORIUM'),
(375, '1', '01', '13', '13', '999', 'TANAH UNTUK BANGUNAN TAMPAT IBADAH LAINNYA'),
(376, '1', '01', '14', '00', '000', 'TANAH PENGGUNAAN LAINNYA'),
(377, '1', '01', '14', '01', '000', 'PENGGALIAN'),
(378, '1', '01', '14', '01', '001', 'PENGGALIAN'),
(379, '1', '01', '14', '01', '002', 'TEMPAT AIR HANGAT'),
(380, '1', '01', '14', '01', '999', 'TANAH PENGGUNAAN LAINNYA'),
(381, '2', '00', '00', '00', '000', 'PERALATAN DAN MESIN'),
(382, '2', '01', '00', '00', '000', 'ALAT BESAR'),
(383, '2', '01', '01', '00', '000', 'ALAT BESAR DARAT'),
(384, '2', '01', '01', '01', '000', 'TRACTOR'),
(385, '2', '01', '01', '01', '001', 'CRAWLER TRACTOR + ATTACHMENT'),
(386, '2', '01', '01', '01', '002', 'WHEEL TRACTOR + ATTACHMENT'),
(387, '2', '01', '01', '01', '003', 'SWAMP TRACTOR + ATTACHMENT'),
(388, '2', '01', '01', '01', '004', 'PRIME MOWER'),
(389, '2', '01', '01', '01', '005', 'AIRCRAFT TOWING TRACTOR'),
(390, '2', '01', '01', '01', '006', 'TOWING BAR'),
(391, '2', '01', '01', '01', '007', 'BULLDOZER'),
(392, '2', '01', '01', '01', '008', 'WHEEL DOZER'),
(393, '2', '01', '01', '01', '999', 'TRACTOR LAINNYA'),
(394, '2', '01', '01', '02', '000', 'GRADER'),
(395, '2', '01', '01', '02', '001', 'GRADER + ATTACHMENT'),
(396, '2', '01', '01', '02', '002', 'GRADER TOWED TYPE'),
(397, '2', '01', '01', '02', '999', 'GRADER LAINNYA'),
(398, '2', '01', '01', '03', '000', 'EXCAVATOR'),
(399, '2', '01', '01', '03', '001', 'CRAWLER EXCAVATOR + ATTACHMENT'),
(400, '2', '01', '01', '03', '002', 'WHEEL EXCAVATOR + ATTACHMENT'),
(401, '2', '01', '01', '03', '999', 'EXCAVATOR LAINNYA'),
(402, '2', '01', '01', '04', '000', 'PILE DRIVER'),
(403, '2', '01', '01', '04', '001', 'DIESEL PILE DRIVER'),
(404, '2', '01', '01', '04', '002', 'PNEUMATIC PILE DRIVER'),
(405, '2', '01', '01', '04', '003', 'VIBRATION PILE DRIVER'),
(406, '2', '01', '01', '04', '999', 'PILE DRIVER LAINNYA'),
(407, '2', '01', '01', '05', '000', 'HAULER'),
(408, '2', '01', '01', '05', '001', 'SELF PROPELLED SCRAPER'),
(409, '2', '01', '01', '05', '002', 'TOWED SCRAPER'),
(410, '2', '01', '01', '05', '003', 'DUMP TRUCK'),
(411, '2', '01', '01', '05', '004', 'DUMP WAGON'),
(412, '2', '01', '01', '05', '005', 'LORI'),
(413, '2', '01', '01', '05', '999', 'HAULER LAINNYA'),
(414, '2', '01', '01', '06', '000', 'ASPHALT EQUIPMENT'),
(415, '2', '01', '01', '06', '001', 'ASPHALT MIXING PLANT'),
(416, '2', '01', '01', '06', '002', 'ASPHALT FINISHER'),
(417, '2', '01', '01', '06', '003', 'ASPHALT DISTRIBUTOR'),
(418, '2', '01', '01', '06', '004', 'ASPHALT HEATER'),
(419, '2', '01', '01', '06', '005', 'ASPHALT TANKER'),
(420, '2', '01', '01', '06', '006', 'ASPHALT SPRAYER'),
(421, '2', '01', '01', '06', '007', 'ASBUTON DRYER'),
(422, '2', '01', '01', '06', '008', 'ASPHALT RECYCLE'),
(423, '2', '01', '01', '06', '009', 'COLD MILLING MACHINE'),
(424, '2', '01', '01', '06', '010', 'ASPHALT MIXER'),
(425, '2', '01', '01', '06', '011', 'BITUMEN / ASPHALT TEST'),
(426, '2', '01', '01', '06', '999', 'ASPHALT EQUIPMENT LAINNYA'),
(427, '2', '01', '01', '07', '000', 'COMPACTING EQUIPMENT'),
(428, '2', '01', '01', '07', '001', 'MACADAM ROLLER/THREE WHEEL ROLLER'),
(429, '2', '01', '01', '07', '002', 'TANDEM ROLLER'),
(430, '2', '01', '01', '07', '003', 'MESH ROLLER'),
(431, '2', '01', '01', '07', '004', 'VIBRATION ROLLER'),
(432, '2', '01', '01', '07', '005', 'TYRE ROLLER'),
(433, '2', '01', '01', '07', '006', 'SOIL STABILIZER'),
(434, '2', '01', '01', '07', '007', 'SHEEPFOOT/TAMPING ROLLER'),
(435, '2', '01', '01', '07', '008', 'STAMPER'),
(436, '2', '01', '01', '07', '009', 'VIBRATION PLATE'),
(437, '2', '01', '01', '07', '010', 'PEMADAT SAMPAH'),
(438, '2', '01', '01', '07', '011', 'TRUCK & BUSH TYRE'),
(439, '2', '01', '01', '07', '999', 'COMPACTING EQUIPMENT LAINNYA'),
(440, '2', '01', '01', '08', '000', 'AGGREGATE & CONCRETE EQUIPMENT'),
(441, '2', '01', '01', '08', '001', 'STONE CRUSHING PLANT'),
(442, '2', '01', '01', '08', '002', 'SCREENING CLASSIFER'),
(443, '2', '01', '01', '08', '003', 'STONE CHUSER'),
(444, '2', '01', '01', '08', '004', 'AGGREGATE WASHER'),
(445, '2', '01', '01', '08', '005', 'BATCHING PLANT'),
(446, '2', '01', '01', '08', '006', 'CONCRETE FINISHER'),
(447, '2', '01', '01', '08', '007', 'CONCRETE PUMP'),
(448, '2', '01', '01', '08', '008', 'CONCRETE LIFT'),
(449, '2', '01', '01', '08', '009', 'CONCRETE PRESTRES'),
(450, '2', '01', '01', '08', '010', 'CONCRETE CUTTER'),
(451, '2', '01', '01', '08', '011', 'CONCRETE MIXER'),
(452, '2', '01', '01', '08', '012', 'CONCRETE VIBRATOR'),
(453, '2', '01', '01', '08', '013', 'CONCRETE BREAKER'),
(454, '2', '01', '01', '08', '014', 'AGGREGATE/CHIP SPREADER'),
(455, '2', '01', '01', '08', '015', 'GRAUTING MACHINE'),
(456, '2', '01', '01', '08', '016', 'CONCRETE MOULD'),
(457, '2', '01', '01', '08', '017', 'PIPE PLANT EQUIPMENT'),
(458, '2', '01', '01', '08', '018', 'CONCRETE MIXER TANDEM'),
(459, '2', '01', '01', '08', '019', 'ONION HEAD MACHINE'),
(460, '2', '01', '01', '08', '020', 'PAN MIXER'),
(461, '2', '01', '01', '08', '021', 'ASBUTON MIXER'),
(462, '2', '01', '01', '08', '022', 'PADDLE MIXER'),
(463, '2', '01', '01', '08', '023', 'ASPHALT BUTON CRUSHER'),
(464, '2', '01', '01', '08', '024', 'ROCK DRILL'),
(465, '2', '01', '01', '08', '999', 'AGGREGATE & CONCRETE EQUIPMENT LAINNYA'),
(466, '2', '01', '01', '09', '000', 'LOADER'),
(467, '2', '01', '01', '09', '001', 'TRACK LOADER + ATTACHMENT'),
(468, '2', '01', '01', '09', '002', 'WHEEL LOADER + ATTACHMENT'),
(469, '2', '01', '01', '09', '003', 'MAIN DECK LOADER'),
(470, '2', '01', '01', '09', '004', 'CONVEYOR BELT TRUCK'),
(471, '2', '01', '01', '09', '005', 'HIGH LIFT LOADER'),
(472, '2', '01', '01', '09', '006', 'BACKHOE LOADER'),
(473, '2', '01', '01', '09', '999', 'LOADER LAINNYA'),
(474, '2', '01', '01', '10', '000', 'ALAT PENGANGKAT'),
(475, '2', '01', '01', '10', '001', 'TOWER CRANE'),
(476, '2', '01', '01', '10', '002', 'TRUCK MOUNTED CRANE'),
(477, '2', '01', '01', '10', '003', 'TRUCK CRANE'),
(478, '2', '01', '01', '10', '004', 'WHEEL CRANE'),
(479, '2', '01', '01', '10', '005', 'FORKLIFT'),
(480, '2', '01', '01', '10', '006', 'FORTAL CRANE'),
(481, '2', '01', '01', '10', '007', 'CRAWLER CRANE'),
(482, '2', '01', '01', '10', '008', 'CONTAINER CRANE'),
(483, '2', '01', '01', '10', '009', 'TRANSTAINER'),
(484, '2', '01', '01', '10', '010', 'TRAVELT CONTAINER STACKER'),
(485, '2', '01', '01', '10', '011', 'TOP LOADER'),
(486, '2', '01', '01', '10', '012', 'RAIL LIFTER'),
(487, '2', '01', '01', '10', '013', 'TRACK MOTOR CAR'),
(488, '2', '01', '01', '10', '014', 'SALVAGE PESAWAT UDARA'),
(489, '2', '01', '01', '10', '015', 'HAND PALET TRUCK'),
(490, '2', '01', '01', '10', '016', 'CRANE SHOVEL 20 T'),
(491, '2', '01', '01', '10', '017', 'SHOP WOOD WORKING CRANE SHOVEL 20 T'),
(492, '2', '01', '01', '10', '999', 'ALAT PENGANGKAT LAINNYA'),
(493, '2', '01', '01', '11', '000', 'MESIN PROSES'),
(494, '2', '01', '01', '11', '001', 'MESIN PEMBUAT PELLET'),
(495, '2', '01', '01', '11', '002', 'MESIN PEMBUAT ES'),
(496, '2', '01', '01', '11', '003', 'MESIN PENGHANCUR ES'),
(497, '2', '01', '01', '11', '004', 'WATER TREATMENT (MESIN PROSES)'),
(498, '2', '01', '01', '11', '005', 'SEA WATER TREATMENT'),
(499, '2', '01', '01', '11', '006', 'MESIN PENGOLAH DODOL'),
(500, '2', '01', '01', '11', '999', 'MESIN PROSES LAINNYA'),
(501, '2', '01', '01', '99', '000', 'ALAT BESAR DARAT LAINNYA'),
(502, '2', '01', '01', '99', '999', 'ALAT BESAR DARAT LAINNYA'),
(503, '2', '01', '02', '00', '000', 'ALAT BESAR APUNG'),
(504, '2', '01', '02', '01', '000', 'DREDGER'),
(505, '2', '01', '02', '01', '001', 'SUCTION DREDGER'),
(506, '2', '01', '02', '01', '002', 'BUCKET DREDGER'),
(507, '2', '01', '02', '01', '003', 'CUTTER SUCTION DREDGER'),
(508, '2', '01', '02', '01', '999', 'DREDGER LAINNYA'),
(509, '2', '01', '02', '02', '000', 'FLOATING EXCAVATOR'),
(510, '2', '01', '02', '02', '001', 'FLOATING EXCAVATOR + ATTACHMENT'),
(511, '2', '01', '02', '02', '002', 'FLOATING CRANE'),
(512, '2', '01', '02', '02', '003', 'FLOATING PUMP'),
(513, '2', '01', '02', '02', '999', 'FLOATING EXCAVATOR LAINNYA'),
(514, '2', '01', '02', '03', '000', 'AMPHIBI DREDGER'),
(515, '2', '01', '02', '03', '001', 'PLAIN SUCTION'),
(516, '2', '01', '02', '03', '002', 'CUTTER (AMPHIBI DREDGER)'),
(517, '2', '01', '02', '03', '003', 'CLAMSHELL / DRAGLINE'),
(518, '2', '01', '02', '03', '999', 'AMPHIBI DREDGER LAINNYA'),
(519, '2', '01', '02', '04', '000', 'KAPAL TARIK'),
(520, '2', '01', '02', '04', '001', 'KAPAL TARIK'),
(521, '2', '01', '02', '04', '999', 'KAPAL TARIK LAINNYA'),
(522, '2', '01', '02', '05', '000', 'MESIN PROSES APUNG'),
(523, '2', '01', '02', '05', '001', 'WATER TREATMENT (MESIN PROSES APUNG)'),
(524, '2', '01', '02', '05', '999', 'MESIN PROSES APUNG LAINNYA'),
(525, '2', '01', '02', '99', '000', 'ALAT BESAR APUNG LAINNYA'),
(526, '2', '01', '02', '99', '999', 'ALAT BESAR APUNG LAINNYA'),
(527, '2', '01', '03', '00', '000', 'ALAT BANTU'),
(528, '2', '01', '03', '01', '000', 'ALAT PENARIK'),
(529, '2', '01', '03', '01', '001', 'ALAT PENARIK KAPAL'),
(530, '2', '01', '03', '01', '002', 'ALAT PENARIK JARING'),
(531, '2', '01', '03', '01', '999', 'ALAT PENARIK LAINNYA'),
(532, '2', '01', '03', '02', '000', 'FEEDER'),
(533, '2', '01', '03', '02', '001', 'ELEVATOR /LIFT'),
(534, '2', '01', '03', '02', '002', 'BELT CONVEYOR (FEEDER)'),
(535, '2', '01', '03', '02', '003', 'SCREW CONVEYOR (FEEDER)'),
(536, '2', '01', '03', '02', '004', 'ESCALATOR'),
(537, '2', '01', '03', '02', '005', 'GANDOLA'),
(538, '2', '01', '03', '02', '006', 'ELEVATOR (FEEDER)'),
(539, '2', '01', '03', '02', '007', 'GANGWAY'),
(540, '2', '01', '03', '02', '999', 'FEEDER LAINNYA (ALAT BESAR)'),
(541, '2', '01', '03', '03', '000', 'COMPRESSOR'),
(542, '2', '01', '03', '03', '001', 'TRANSPORTABLE COMPRESSOR'),
(543, '2', '01', '03', '03', '002', 'PORTABLE COMPRESSOR'),
(544, '2', '01', '03', '03', '003', 'STATIONARY COMPRESSOR'),
(545, '2', '01', '03', '03', '004', 'AIR COMPRESOR'),
(546, '2', '01', '03', '03', '005', 'COMPRESSOR PNEUMATIC TOOL 25 GMP'),
(547, '2', '01', '03', '03', '999', 'COMPRESSOR LAINNYA'),
(548, '2', '01', '03', '04', '000', 'ELECTRIC GENERATING SET'),
(549, '2', '01', '03', '04', '001', 'TRANSPORTABLE GENERATING SET'),
(550, '2', '01', '03', '04', '002', 'PORTABLE GENERATING SET'),
(551, '2', '01', '03', '04', '003', 'STATIONARY GENERATING SET'),
(552, '2', '01', '03', '04', '004', 'DYNAMO ELECTRIC'),
(553, '2', '01', '03', '04', '999', 'ELECTRIC GENERATING SET LAINNYA'),
(554, '2', '01', '03', '05', '000', 'POMPA'),
(555, '2', '01', '03', '05', '001', 'TRANSPORTABLE WATER PUMP'),
(556, '2', '01', '03', '05', '002', 'PORTABLE WATER PUMP'),
(557, '2', '01', '03', '05', '003', 'STATIONARY WATER PUMP'),
(558, '2', '01', '03', '05', '004', 'POMPA LUMPUR'),
(559, '2', '01', '03', '05', '005', 'SUMERSIBLE PUMP'),
(560, '2', '01', '03', '05', '006', 'POMPA TANGAN'),
(561, '2', '01', '03', '05', '007', 'POMPA ANGIN'),
(562, '2', '01', '03', '05', '008', 'POMPA BENSIN/MINYAK STATIONERY'),
(563, '2', '01', '03', '05', '009', 'POMPA BENSIN/MINYAK TRANSPORTABLE'),
(564, '2', '01', '03', '05', '010', 'POMPA AIR'),
(565, '2', '01', '03', '05', '011', 'WATER DISTRIBUTOR'),
(566, '2', '01', '03', '05', '012', 'WATER PURIFICATION'),
(567, '2', '01', '03', '05', '999', 'POMPA LAINNYA'),
(568, '2', '01', '03', '06', '000', 'MESIN BOR'),
(569, '2', '01', '03', '06', '001', 'MESIN BOR BATU'),
(570, '2', '01', '03', '06', '002', 'MESIN BOR TANAH'),
(571, '2', '01', '03', '06', '003', 'MESIN BOR BETON'),
(572, '2', '01', '03', '06', '999', 'MESIN BOR LAINNYA'),
(573, '2', '01', '03', '07', '000', 'UNIT PEMELIHARAAN LAPANGAN'),
(574, '2', '01', '03', '07', '001', 'MOBIL WORKSHOP'),
(575, '2', '01', '03', '07', '002', 'SERVICE CAR'),
(576, '2', '01', '03', '07', '003', 'FLOATING WORKSHOP'),
(577, '2', '01', '03', '07', '004', 'ROAD MAINTENANCE TRUCK'),
(578, '2', '01', '03', '07', '005', 'SWEEPER TRUCK'),
(579, '2', '01', '03', '07', '006', 'WRECK CAR'),
(580, '2', '01', '03', '07', '007', 'LEAK DETECTOR (UNIT PEMELIHARAAN LAPANGAN)'),
(581, '2', '01', '03', '07', '008', 'PIPE LOCATOR'),
(582, '2', '01', '03', '07', '009', 'METAL LOCATOR'),
(583, '2', '01', '03', '07', '010', 'MESIN DIESEL'),
(584, '2', '01', '03', '07', '011', 'KETLE HEATING'),
(585, '2', '01', '03', '07', '012', 'SWEEPER PENGHISAP OLI'),
(586, '2', '01', '03', '07', '013', 'FUEL TANK'),
(587, '2', '01', '03', '07', '014', 'GRASS COLECTOR'),
(588, '2', '01', '03', '07', '015', 'MESIN PEMOTONG ASPAL (DRAGING)'),
(589, '2', '01', '03', '07', '016', 'SWEEPER ROTARY'),
(590, '2', '01', '03', '07', '017', 'EARTH VAGER TRUCK'),
(591, '2', '01', '03', '07', '018', 'SCRAPPER'),
(592, '2', '01', '03', '07', '019', 'ROSTER'),
(593, '2', '01', '03', '07', '020', 'SHOP TRUCK EQUIPMENT'),
(594, '2', '01', '03', '07', '999', 'UNIT PEMELIHARAAN LAPANGAN LAINNYA'),
(595, '2', '01', '03', '08', '000', 'ALAT PENGOLAHAN AIR KOTOR'),
(596, '2', '01', '03', '08', '001', 'UNIT PENGOLAHAN AIR KOTOR'),
(597, '2', '01', '03', '08', '999', 'ALAT PENGOLAHAN AIR KOTOR LAINNYA'),
(598, '2', '01', '03', '09', '000', 'PEMBANGKIT UAP AIR PANAS/STEAM GENERATOR'),
(599, '2', '01', '03', '09', '001', 'UNIT PEMBANGKIT UAP AIR PANAS'),
(600, '2', '01', '03', '09', '999', 'PEMBANGKIT UAP AIR PANAS/STEAM GENERATOR LAINNYA'),
(601, '2', '01', '03', '12', '000', 'PERALATAN KEBAKARAN HUTAN'),
(602, '2', '01', '03', '12', '001', 'BACKPACK PUMP (POMPA PUNGGUNG BESAR)'),
(603, '2', '01', '03', '12', '002', 'FLOATING FIRE PUMP (POMPA PUNGGUNG KECIL)'),
(604, '2', '01', '03', '12', '003', 'POMPA PORTABLE'),
(605, '2', '01', '03', '12', '004', 'JET SHOOTER'),
(606, '2', '01', '03', '12', '005', 'GOLOK PEMADAM'),
(607, '2', '01', '03', '12', '006', 'BLADE SHOVEL (SEKOP PEMADAM)'),
(608, '2', '01', '03', '12', '007', 'SUMBUT'),
(609, '2', '01', '03', '12', '008', 'VELD BED'),
(610, '2', '01', '03', '12', '009', 'RANSEL PEMADAM'),
(611, '2', '01', '03', '12', '010', 'FULL BODY HARNESS'),
(612, '2', '01', '03', '12', '011', 'SIT HARNESS'),
(613, '2', '01', '03', '12', '012', 'FIGURE'),
(614, '2', '01', '03', '12', '013', 'ASCENDER'),
(615, '2', '01', '03', '12', '014', 'SCROLL LOCK'),
(616, '2', '01', '03', '12', '015', 'PERLENGKAPAN RESCUE'),
(617, '2', '01', '03', '12', '016', 'AUTOMATIC SNAP HOOK'),
(618, '2', '01', '03', '12', '017', 'TANGGA TALI'),
(619, '2', '01', '03', '12', '018', 'NOZEL TABIR ALUMUNIUM'),
(620, '2', '01', '03', '12', '019', 'NOZEL KUNINGAN PERNEKEL'),
(621, '2', '01', '03', '12', '020', 'SELANG AIR'),
(622, '2', '01', '03', '12', '021', 'BREATHING APARATUS (TABUNG 10 KG)'),
(623, '2', '01', '03', '12', '022', 'GEPYOK PEMADAM'),
(624, '2', '01', '03', '12', '023', 'FIRE RAKE (GARU TAJAM)'),
(625, '2', '01', '03', '12', '024', 'PULASKI AXE (KAPAK DUA FUNGSI)'),
(626, '2', '01', '03', '12', '025', 'FIRE TOOL (GARU PACUL/ CANGKUL)'),
(627, '2', '01', '03', '12', '026', 'SABIT SEMAK'),
(628, '2', '01', '03', '12', '027', 'FLAPPER (PEMUKUL API)'),
(629, '2', '01', '03', '12', '028', 'DRIP TORCH (OBOR SULUT TETES)'),
(630, '2', '01', '03', '12', '029', 'FILES (KIKIR BAJA)'),
(631, '2', '01', '03', '12', '030', 'KACA MATA (LENSA TAHAN PANAS)'),
(632, '2', '01', '03', '12', '031', 'KOPEL REM'),
(633, '2', '01', '03', '12', '032', 'FELPES'),
(634, '2', '01', '03', '12', '033', 'KANTONG AIR'),
(635, '2', '01', '03', '12', '034', 'BATANG POMPA'),
(636, '2', '01', '03', '12', '999', 'PERALATAN KEBAKARAN HUTAN LAINNYA'),
(637, '2', '01', '03', '13', '000', 'PERALATAN SELAM'),
(638, '2', '01', '03', '13', '001', 'TANKS (TABUNG SELAM)'),
(639, '2', '01', '03', '13', '002', 'SEPATU KARANG'),
(640, '2', '01', '03', '13', '003', 'KNIVES (PISAU SELAM)'),
(641, '2', '01', '03', '13', '004', 'DIVE LIGHTS (SENTER SELAM)'),
(642, '2', '01', '03', '13', '005', 'REGULATOR INSTRUMENTS'),
(643, '2', '01', '03', '13', '006', 'BOUYANCY COMPENSATOR DEVICE (BCD)'),
(644, '2', '01', '03', '13', '007', 'BELT (SABUK PEMBERAT)'),
(645, '2', '01', '03', '13', '008', 'WEIGHT (PEMBERAT)'),
(646, '2', '01', '03', '13', '009', 'DIVING GLOVES (SARUNG TANGAN SELAM)'),
(647, '2', '01', '03', '13', '010', 'KOMPRESOR SELAM'),
(648, '2', '01', '03', '13', '011', 'PELAMPUNG LIFE JACKET'),
(649, '2', '01', '03', '13', '999', 'PERALATAN SELAM LAINNYA'),
(650, '2', '01', '03', '14', '000', 'PERALATAN SAR MOUNTENERING'),
(651, '2', '01', '03', '14', '001', 'TALI KAMANTEL STATIC'),
(652, '2', '01', '03', '14', '002', 'TALI KAMANTEL DINAMIC'),
(653, '2', '01', '03', '14', '003', 'RAINCOAT (PONCO)'),
(654, '2', '01', '03', '14', '004', 'SEAT HARNESS'),
(655, '2', '01', '03', '14', '005', 'PRUSIK'),
(656, '2', '01', '03', '14', '006', 'JUMMAR'),
(657, '2', '01', '03', '14', '007', 'PULLEY'),
(658, '2', '01', '03', '14', '008', 'DESCENDER FIGURE OG EIGHT'),
(659, '2', '01', '03', '14', '009', 'CARABINER NON SCREW'),
(660, '2', '01', '03', '14', '010', 'WEBBING'),
(661, '2', '01', '03', '14', '011', 'TANDU LIPAT'),
(662, '2', '01', '03', '14', '999', 'PERALATAN SAR MOUNTENERING LAINNYA'),
(663, '2', '01', '03', '99', '000', 'ALAT BANTU LAINNYA'),
(664, '2', '01', '03', '99', '999', 'ALAT BANTU LAINNYA'),
(665, '2', '02', '00', '00', '000', 'ALAT ANGKUTAN'),
(666, '2', '02', '01', '00', '000', 'ALAT ANGKUTAN DARAT BERMOTOR'),
(667, '2', '02', '01', '01', '000', 'KENDARAAN DINAS BERMOTOR PERORANGAN'),
(668, '2', '02', '01', '01', '001', 'SEDAN'),
(669, '2', '02', '01', '01', '002', 'JEEP'),
(670, '2', '02', '01', '01', '003', 'STATION WAGON'),
(671, '2', '02', '01', '01', '999', 'KENDARAAN DINAS BERMOTOR PERORANGAN LAINNYA'),
(672, '2', '02', '01', '02', '000', 'KENDARAAN BERMOTOR PENUMPANG'),
(673, '2', '02', '01', '02', '001', 'BUS ( PENUMPANG 30 ORANG KEATAS )'),
(674, '2', '02', '01', '02', '002', 'MICRO BUS ( PENUMPANG 15 S/D 29 ORANG )'),
(675, '2', '02', '01', '02', '003', 'MINI BUS ( PENUMPANG 14 ORANG KEBAWAH )'),
(676, '2', '02', '01', '02', '004', 'KENDARAAN LAPIS BAJA'),
(677, '2', '02', '01', '02', '999', 'KENDARAAN BERMOTOR PENUMPANG LAINNYA'),
(678, '2', '02', '01', '03', '000', 'KENDARAAN BERMOTOR ANGKUTAN BARANG'),
(679, '2', '02', '01', '03', '001', 'TRUCK + ATTACHMENT'),
(680, '2', '02', '01', '03', '002', 'PICK UP'),
(681, '2', '02', '01', '03', '003', 'YEENGLER/TRAILER'),
(682, '2', '02', '01', '03', '004', 'SEMI TRAILER'),
(683, '2', '02', '01', '03', '005', 'TRUCK PONTON DENGAN TRAILLER'),
(684, '2', '02', '01', '03', '006', 'DALHURA'),
(685, '2', '02', '01', '03', '999', 'KENDARAAN BERMOTOR ANGKUTAN BARANG LAINNYA'),
(686, '2', '02', '01', '04', '000', 'KENDARAAN BERMOTOR BERODA DUA'),
(687, '2', '02', '01', '04', '001', 'SEPEDA MOTOR'),
(688, '2', '02', '01', '04', '002', 'SCOOTER'),
(689, '2', '02', '01', '04', '003', 'SEPEDA MOTOR PERPUSTAKAAN KELILING'),
(690, '2', '02', '01', '04', '004', 'SEPEDA MOTOR PATROLI'),
(691, '2', '02', '01', '04', '005', 'SEPEDA MOTOR PENGAWALAN'),
(692, '2', '02', '01', '04', '999', 'KENDARAAN BERMOTOR BERODA DUA LAINNYA'),
(693, '2', '02', '01', '05', '000', 'KENDARAAN BERMOTOR KHUSUS'),
(694, '2', '02', '01', '05', '001', 'MOBIL AMBULANCE'),
(695, '2', '02', '01', '05', '002', 'MOBIL JENAZAH'),
(696, '2', '02', '01', '05', '003', 'MOBIL UNIT PENERANGAN DARAT'),
(697, '2', '02', '01', '05', '004', 'MOBIL PEMADAM KEBAKARAN'),
(698, '2', '02', '01', '05', '005', 'MOBIL TINJA'),
(699, '2', '02', '01', '05', '006', 'MOBIL TANGKI AIR'),
(700, '2', '02', '01', '05', '007', 'MOBIL UNIT MONITORING FREKWENSI'),
(701, '2', '02', '01', '05', '008', 'MOBIL UNIT PERPUSTAKAAN KELILING'),
(702, '2', '02', '01', '05', '009', 'MOBIL UNIT VISUAL MINI (MUVIANI)'),
(703, '2', '02', '01', '05', '010', 'MOBIL UNIT SATELITE LINK VAN'),
(704, '2', '02', '01', '05', '011', 'MOBIL UNIT PANGGUNG'),
(705, '2', '02', '01', '05', '012', 'MOBIL UNIT PAMERAN'),
(706, '2', '02', '01', '05', '013', 'OUT SIDE BROAD CAST VAN RADIO'),
(707, '2', '02', '01', '05', '014', 'OUT SIDE BROAD CAST VAN TELEVISI'),
(708, '2', '02', '01', '05', '015', 'MOBIL UNIT PRODUKSI FILM'),
(709, '2', '02', '01', '05', '016', 'MOBIL UNIT PRODUKSI TELEVISI'),
(710, '2', '02', '01', '05', '017', 'MOBIL UNIT PRODUKSI CINERAMA'),
(711, '2', '02', '01', '05', '018', 'MOBIL UNIT KESEHATAN MASYARAKAT'),
(712, '2', '02', '01', '05', '019', 'MOBIL UNIT KESEHATAN HEWAN'),
(713, '2', '02', '01', '05', '020', 'MOBIL UNIT TAHANAN'),
(714, '2', '02', '01', '05', '021', 'MOBIL UNIT PENGANGKUT UANG'),
(715, '2', '02', '01', '05', '022', 'TRUCK SAMPAH'),
(716, '2', '02', '01', '05', '023', 'MOBIL TANGKI BAHAN BAKAR'),
(717, '2', '02', '01', '05', '024', 'MOBIL UNIT RONTGEN'),
(718, '2', '02', '01', '05', '025', 'MOBIL UNIT REHABILITASI SOSIAL KELILING'),
(719, '2', '02', '01', '05', '026', 'BOMP TRAILER'),
(720, '2', '02', '01', '05', '027', 'KENDARAAN KLINIK'),
(721, '2', '02', '01', '05', '028', 'MOBIL UNIT PENGANGKUT LIMBAH RADIO AKTIF'),
(722, '2', '02', '01', '05', '029', 'MOBIL TRANFUSI DARAH'),
(723, '2', '02', '01', '05', '030', 'KENDARAAN TIM PEMELIHARAAN'),
(724, '2', '02', '01', '05', '031', 'MOBIL PENARIK (UNIMOG)'),
(725, '2', '02', '01', '05', '032', 'KENDARAAN SATMOBEK/SATMOBENG/SATMOMAS'),
(726, '2', '02', '01', '05', '033', 'MOBIL WORK SHOP/SERVICES'),
(727, '2', '02', '01', '05', '034', 'KENDARAAN DEREK'),
(728, '2', '02', '01', '05', '035', 'MOBIL UNIT KHUSUS ALJIHANDAK'),
(729, '2', '02', '01', '05', '036', 'AIRCRAFT AIR CONDITIONING'),
(730, '2', '02', '01', '05', '037', 'KENDARAAN GIRAFLE RADAR'),
(731, '2', '02', '01', '05', '038', 'MOBIL PERS VAN'),
(732, '2', '02', '01', '05', '039', 'KENDARAAN UNIT BEDAH'),
(733, '2', '02', '01', '05', '040', 'MOBILE FLOODLIGHT'),
(734, '2', '02', '01', '05', '041', 'KENDARAAN PENGANGKUT TANK'),
(735, '2', '02', '01', '05', '042', 'CRASH CAR'),
(736, '2', '02', '01', '05', '043', 'KENDARAAN WATER CANON'),
(737, '2', '02', '01', '05', '044', 'FOAM VEHICLE'),
(738, '2', '02', '01', '05', '045', 'KENDARAAN TOILET'),
(739, '2', '02', '01', '05', '046', 'RAPID INVENTION VEHICLE'),
(740, '2', '02', '01', '05', '047', 'KENDARAAN GAS AIRMATA'),
(741, '2', '02', '01', '05', '048', 'KENDARAAN TAKTIS'),
(742, '2', '02', '01', '05', '049', 'KENDARAAN VIP (ANTI PELURU)'),
(743, '2', '02', '01', '05', '050', 'KENDARAAN TANGGA PESAWAT'),
(744, '2', '02', '01', '05', '051', 'KENDARAAN METEO'),
(745, '2', '02', '01', '05', '052', 'KENDARAAN SWEEPER'),
(746, '2', '02', '01', '05', '053', 'KENDARAAN KAMAR SANDI'),
(747, '2', '02', '01', '05', '054', 'KENDARAAN JAMMING FREKUENSI'),
(748, '2', '02', '01', '05', '055', 'KENDARAAN MONITORING SINYAL'),
(749, '2', '02', '01', '05', '056', 'MOBIL DAPUR LAPANGAN'),
(750, '2', '02', '01', '05', '057', 'MOBIL PENARIK BARRIER'),
(751, '2', '02', '01', '05', '058', 'MOBIL OPERASIONAL PJR'),
(752, '2', '02', '01', '05', '059', 'AUTOMATIC UNGUIDED VEHICLE (AUGV)'),
(753, '2', '02', '01', '05', '060', 'RESCUE CAR'),
(754, '2', '02', '01', '05', '061', 'RAPID DEPLOYMENT LAND SAR'),
(755, '2', '02', '01', '05', '062', 'RESCUE TRUCK'),
(756, '2', '02', '01', '05', '063', 'MONILOG (MOBIL LOGISTIK/ PERSONIL)'),
(757, '2', '02', '01', '05', '064', 'MOBIL LATIH'),
(758, '2', '02', '01', '05', '065', 'RAN SWITCH WAGON'),
(759, '2', '02', '01', '05', '066', 'RAN CACDRI WAGON'),
(760, '2', '02', '01', '05', '067', 'RAN TRAKTOR'),
(761, '2', '02', '01', '05', '068', 'RAN TANGKI'),
(762, '2', '02', '01', '05', '069', 'RAN ZAT ASAM'),
(763, '2', '02', '01', '05', '070', 'RAN PENYAPU LANDASAN'),
(764, '2', '02', '01', '05', '071', 'RAN PANDU PESAWAT'),
(765, '2', '02', '01', '05', '072', 'RAN PENARIK PESAWAT'),
(766, '2', '02', '01', '05', '073', 'RAN PENYAPU HANGGAR'),
(767, '2', '02', '01', '05', '074', 'RAN DRUG CHUTE'),
(768, '2', '02', '01', '05', '075', 'RAN PEMBANGKIT TENAGA'),
(769, '2', '02', '01', '05', '076', 'RAN CRIME SQUID'),
(770, '2', '02', '01', '05', '077', 'RAN WEAPON CARRIER'),
(771, '2', '02', '01', '05', '078', 'RAN LABORATORIUM / UJI COBA'),
(772, '2', '02', '01', '05', '079', 'RAN KANTIN'),
(773, '2', '02', '01', '05', '080', 'RAN PATROLI'),
(774, '2', '02', '01', '05', '081', 'RAN JEEP KOMMAB'),
(775, '2', '02', '01', '05', '082', 'RAN RECOVERY'),
(776, '2', '02', '01', '05', '083', 'RAN PENGISI BB PESAWAT'),
(777, '2', '02', '01', '05', '084', 'RAN WRECKER'),
(778, '2', '02', '01', '05', '085', 'RAN FORKLIP'),
(779, '2', '02', '01', '05', '086', 'MOBIL PATROLI'),
(780, '2', '02', '01', '05', '087', 'KENDARAAN APC'),
(781, '2', '02', '01', '05', '088', 'KENDARAAN DARE V'),
(782, '2', '02', '01', '05', '089', 'KENDARAAN/MOBIL PENGAWALAN'),
(783, '2', '02', '01', '05', '090', 'MOBIL IRUP'),
(784, '2', '02', '01', '05', '091', 'MOBIL KOMLEK POLRI'),
(785, '2', '02', '01', '05', '092', 'MOBIL UNIT TKP'),
(786, '2', '02', '01', '05', '093', 'MOBIL UNIT LAKA LANTAS'),
(787, '2', '02', '01', '05', '094', 'MOBIL UNIT IDENTIFIKASI'),
(788, '2', '02', '01', '05', '095', 'MOBIL UNIT LABFOR'),
(789, '2', '02', '01', '05', '096', 'MOBIL UNIT PENERANGAN POLRI'),
(790, '2', '02', '01', '05', '097', 'MOBIL UNIT DEREK'),
(791, '2', '02', '01', '05', '098', 'MOBIL UNIT SATWA'),
(792, '2', '02', '01', '05', '099', 'RANTIS PHH'),
(793, '2', '02', '01', '05', '100', 'KENDARAAN POS POLISI MOBILE'),
(794, '2', '02', '01', '05', '101', 'MOBIL UNIT ALSUS JIHANDAK'),
(795, '2', '02', '01', '05', '102', 'MOBIL GOLFCAR'),
(796, '2', '02', '01', '05', '103', 'RANTIS RESCUE SAMAPTA'),
(797, '2', '02', '01', '05', '104', 'RANSUS SATWA ANJING TYPE KECIL'),
(798, '2', '02', '01', '05', '105', 'RANSUS SATWA ANJING TYPE SEDANG'),
(799, '2', '02', '01', '05', '106', 'RANSUS SATWA ANJING TYPE BESAR'),
(800, '2', '02', '01', '05', '107', 'RANSUS SATWA KUDA TYPE SEDANG'),
(801, '2', '02', '01', '05', '108', 'RANSUS SATWA KUDA TYPE BESAR'),
(802, '2', '02', '01', '05', '109', 'TRAILER KUDA'),
(803, '2', '02', '01', '05', '999', 'KENDARAAN BERMOTOR KHUSUS LAINNYA'),
(804, '2', '02', '01', '99', '000', 'ALAT ANGKUTAN DARAT BERMOTOR LAINNYA'),
(805, '2', '02', '01', '99', '999', 'ALAT ANGKUTAN DARAT BERMOTOR LAINNYA'),
(806, '2', '02', '02', '00', '000', 'ALAT ANGKUTAN DARAT TAK BERMOTOR'),
(807, '2', '02', '02', '01', '000', 'KENDARAAN TAK BERMOTOR ANGKUTAN BARANG'),
(808, '2', '02', '02', '01', '001', 'GEROBAK TARIK'),
(809, '2', '02', '02', '01', '002', 'GEROBAK DORONG'),
(810, '2', '02', '02', '01', '003', 'CARAVAN'),
(811, '2', '02', '02', '01', '004', 'LORI DORONG'),
(812, '2', '02', '02', '01', '005', 'TRAILER'),
(813, '2', '02', '02', '01', '006', 'CONTAINER DOLLY'),
(814, '2', '02', '02', '01', '007', 'PALLET DOLLY'),
(815, '2', '02', '02', '01', '008', 'BAGGAGE AND MAIL CART'),
(816, '2', '02', '02', '01', '009', 'BAGGAGE TROLLY'),
(817, '2', '02', '02', '01', '010', 'MEJA DORONG SAJI/TROLLEY SAJI');
INSERT INTO `tweb_aset` (`id_aset`, `golongan`, `bidang`, `kelompok`, `sub_kelompok`, `sub_sub_kelompok`, `nama`) VALUES
(818, '2', '02', '02', '01', '011', 'RODA DUA BERINSULASI'),
(819, '2', '02', '02', '01', '012', 'RODA TIGA/ GEROBAK KAYUH BERINSULASI'),
(820, '2', '02', '02', '01', '999', 'KENDARAAN TAK BERMOTOR ANGKUTAN BARANG LAINNYA'),
(821, '2', '02', '02', '02', '000', 'KENDARAAN TAK BERMOTOR PENUMPANG'),
(822, '2', '02', '02', '02', '001', 'SEPEDA'),
(823, '2', '02', '02', '02', '002', 'KUDA (KENDARAAN TAK BERMOTOR PENUMPANG)'),
(824, '2', '02', '02', '02', '999', 'KENDARAAN TAK BERMOTOR PENUMPANG LAINNYA'),
(825, '2', '02', '02', '03', '000', 'ALAT ANGKUTAN KERETA REL TAK BERMOTOR'),
(826, '2', '02', '02', '03', '001', 'KERETA PENUMPANG'),
(827, '2', '02', '02', '03', '002', 'KERETA MAKAN'),
(828, '2', '02', '02', '03', '003', 'POWER CAR'),
(829, '2', '02', '02', '03', '004', 'GERBONG BARANG TERTUTUP'),
(830, '2', '02', '02', '03', '005', 'GERBONG BARANG TERBUKA'),
(831, '2', '02', '02', '03', '999', 'ALAT ANGKUTAN KERETA REL TAK BERMOTOR LAINNYA'),
(832, '2', '02', '02', '99', '000', 'ALAT ANGKUTAN DARAT TAK BERMOTOR LAINNYA'),
(833, '2', '02', '02', '99', '999', 'ALAT ANGKUTAN DARAT TAK BERMOTOR LAINNYA'),
(834, '2', '02', '03', '00', '000', 'ALAT ANGKUTAN APUNG BERMOTOR'),
(835, '2', '02', '03', '01', '000', 'ALAT ANGKUTAN APUNG BERMOTOR UNTUK BARANG'),
(836, '2', '02', '03', '01', '001', 'KAPAL MINYAK (TANKER)'),
(837, '2', '02', '03', '01', '002', 'TONGKANG BERMOTOR'),
(838, '2', '02', '03', '01', '003', 'TUG BOAT + ATTACHMENT'),
(839, '2', '02', '03', '01', '004', 'LANDING SHIP TRANSPORTATION( L.S.T )'),
(840, '2', '02', '03', '01', '005', 'KAPAL CARGO (KAPAL BARANG)'),
(841, '2', '02', '03', '01', '006', 'TRUCK AIR'),
(842, '2', '02', '03', '01', '999', 'ALAT ANGKUTAN APUNG BERMOTOR UNTUK BARANG LAINNYA'),
(843, '2', '02', '03', '02', '000', 'ALAT ANGKUTAN APUNG BERMOTOR UNTUK PENUMPANG'),
(844, '2', '02', '03', '02', '001', 'SPEED BOAT / MOTOR TEMPEL'),
(845, '2', '02', '03', '02', '002', 'MOTOR BOAT'),
(846, '2', '02', '03', '02', '003', 'KLOTOK'),
(847, '2', '02', '03', '02', '004', 'FERRY'),
(848, '2', '02', '03', '02', '005', 'HIDROFOIL'),
(849, '2', '02', '03', '02', '006', 'JETFOIL'),
(850, '2', '02', '03', '02', '007', 'LONG BOAT'),
(851, '2', '02', '03', '02', '008', 'KAPAL PASSANGER (KAPAL PENUMPANG)'),
(852, '2', '02', '03', '02', '009', 'PERAHU KAYU'),
(853, '2', '02', '03', '02', '999', 'ALAT ANGKUTAN APUNG BERMOTOR UNTUK PENUMPANG LAINNYA'),
(854, '2', '02', '03', '03', '000', 'ALAT ANGKUTAN APUNG BERMOTOR KHUSUS'),
(855, '2', '02', '03', '03', '001', 'SURVEY BOAT'),
(856, '2', '02', '03', '03', '002', 'KAPAL ANTI POLUSI'),
(857, '2', '02', '03', '03', '003', 'KAPAL PERAMBUAN'),
(858, '2', '02', '03', '03', '004', 'OUT BOAT MOTOR'),
(859, '2', '02', '03', '03', '005', 'KAPAL HYDROGRAFI'),
(860, '2', '02', '03', '03', '006', 'KAPAL UNIT PENERANGAN AIR'),
(861, '2', '02', '03', '03', '007', 'KAPAL VISUAL MINI'),
(862, '2', '02', '03', '03', '008', 'KAPAL PENANGKAP IKAN'),
(863, '2', '02', '03', '03', '009', 'KAPAL PENGANGKUT HEWAN'),
(864, '2', '02', '03', '03', '010', 'KAPAL PATROLI PANTAI'),
(865, '2', '02', '03', '03', '011', 'KAPAL MOTOR PERPUSTAKAAN KELILING'),
(866, '2', '02', '03', '03', '012', 'FLOATING WORK SHOP/DOCK'),
(867, '2', '02', '03', '03', '013', 'MORING BOAT/KEPIL'),
(868, '2', '02', '03', '03', '014', 'SUCTION DREDGER/KERUK HISAP'),
(869, '2', '02', '03', '03', '015', 'QUTTER DREDGER/KERUK BOR'),
(870, '2', '02', '03', '03', '016', 'BUCKET DREDGER/KERUK TIMBA'),
(871, '2', '02', '03', '03', '017', 'CLAMPSHEL DREDGER/KERUK CAKRAM'),
(872, '2', '02', '03', '03', '018', 'ALAT ANGKUTAN APUNG UNTUK MANCING'),
(873, '2', '02', '03', '03', '019', 'FLOATING PILE + ATTACHMENT (ALAT ANGKUTAN APUNG BERMOTOR KHUSUS)'),
(874, '2', '02', '03', '03', '020', 'SEKOCI MOTOR TEMPEL'),
(875, '2', '02', '03', '03', '021', 'PERAHU MOTOR TEMPEL'),
(876, '2', '02', '03', '03', '022', 'KAPAL OSEANOGRAFI'),
(877, '2', '02', '03', '03', '023', 'PERAHU TRADISIONAL'),
(878, '2', '02', '03', '03', '024', 'SEA RIDER'),
(879, '2', '02', '03', '03', '025', 'HOVER CRAFT'),
(880, '2', '02', '03', '03', '026', 'KAPAL PENGANGKUT IKAN'),
(881, '2', '02', '03', '03', '027', 'KAPAL PENGOLAH IKAN'),
(882, '2', '02', '03', '03', '028', 'KAPAL PENELITIAN/ EKSPLORASI PERIKANAN'),
(883, '2', '02', '03', '03', '029', 'KAPAL PENDUKUNG OPERASI PENANGKAPAN IKAN'),
(884, '2', '02', '03', '03', '030', 'KAPAL PENDUKUNG OPERASI PEMBUDIDAYAAN IKAN'),
(885, '2', '02', '03', '03', '031', 'KAPAL PENGAWAS PERIKANAN'),
(886, '2', '02', '03', '03', '032', 'PERAHU INTAI 3 ORANG'),
(887, '2', '02', '03', '03', '033', 'PERAHU SERBU 15 ORANG'),
(888, '2', '02', '03', '03', '034', 'KAPAL PATROLI POLISI'),
(889, '2', '02', '03', '03', '035', 'JET SKY'),
(890, '2', '02', '03', '03', '999', 'ALAT ANGKUTAN APUNG BERMOTOR KHUSUS LAINNYA'),
(891, '2', '02', '03', '99', '000', 'ALAT ANGKUTAN APUNG BERMOTOR LAINNYA'),
(892, '2', '02', '03', '99', '999', 'ALAT ANGKUTAN APUNG BERMOTOR LAINNYA'),
(893, '2', '02', '04', '00', '000', 'ALAT ANGKUTAN APUNG TAK BERMOTOR'),
(894, '2', '02', '04', '01', '000', 'ALAT ANGKUTAN APUNG TAK BERMOTOR UNTUK BARANG'),
(895, '2', '02', '04', '01', '001', 'TONGKANG'),
(896, '2', '02', '04', '01', '002', 'PERAHU BARANG'),
(897, '2', '02', '04', '01', '999', 'ALAT ANGKUTAN APUNG TAK BERMOTOR UNTUK BARANG LAINNYA'),
(898, '2', '02', '04', '02', '000', 'ALAT ANGKUTAN APUNG TAK BERMOTOR UNTUK PENUMPANG'),
(899, '2', '02', '04', '02', '001', 'PERAHU PENUMPANG'),
(900, '2', '02', '04', '02', '002', 'PERAHU PENYEBERANGAN'),
(901, '2', '02', '04', '02', '999', 'ALAT ANGKUTAN APUNG TAK BERMOTOR UNTUK PENUMPANG LAINNYA'),
(902, '2', '02', '04', '03', '000', 'ALAT ANGKUTAN APUNG TAK BERMOTOR KHUSUS'),
(903, '2', '02', '04', '03', '001', 'PONTON'),
(904, '2', '02', '04', '03', '002', 'PERAHU KARET (ALAT ANGKUTAN APUNG TAK BERMOTOR KHUSUS)'),
(905, '2', '02', '04', '03', '003', 'PONTON RUMAH'),
(906, '2', '02', '04', '03', '004', 'FLOATING PLATFORM/RAKIT'),
(907, '2', '02', '04', '03', '999', 'ALAT ANGKUTAN APUNG TAK BERMOTOR KHUSUS LAINNYA'),
(908, '2', '02', '04', '99', '000', 'ALAT ANGKUTAN APUNG TAK BERMOTOR LAINNYA'),
(909, '2', '02', '04', '99', '999', 'ALAT ANGKUTAN APUNG TAK BERMOTOR LAINNYA'),
(910, '2', '03', '00', '00', '000', 'ALAT BENGKEL DAN ALAT UKUR'),
(911, '2', '03', '01', '00', '000', 'ALAT BENGKEL BERMESIN'),
(912, '2', '03', '01', '01', '000', 'PERKAKAS KONSTRUKSI LOGAM TERPASANG PADA PONDASI'),
(913, '2', '03', '01', '01', '001', 'MESIN BUBUT'),
(914, '2', '03', '01', '01', '002', 'MESIN FRAIS'),
(915, '2', '03', '01', '01', '003', 'MESIN KETAM (PERKAKAS KONSTRUKSI LOGAM TERPASANG PADA PONDASI)'),
(916, '2', '03', '01', '01', '004', 'MESIN PRESS HIDROLIK & PUNCH'),
(917, '2', '03', '01', '01', '005', 'MESIN BOR'),
(918, '2', '03', '01', '01', '006', 'MESIN GERGAJI LOGAM'),
(919, '2', '03', '01', '01', '007', 'MESIN GERINDA'),
(920, '2', '03', '01', '01', '008', 'MESIN ROL'),
(921, '2', '03', '01', '01', '009', 'MESIN BOR CYLINDER'),
(922, '2', '03', '01', '01', '010', 'MESIN SKRUP'),
(923, '2', '03', '01', '01', '011', 'MESIN MEILING'),
(924, '2', '03', '01', '01', '012', 'MESIN PUREL'),
(925, '2', '03', '01', '01', '013', 'MESIN PERAPEN'),
(926, '2', '03', '01', '01', '014', 'MESIN SIKAT KULIT'),
(927, '2', '03', '01', '01', '015', 'MESIN PEMOTONG KULIT'),
(928, '2', '03', '01', '01', '016', 'MESIN JAHIT KULIT'),
(929, '2', '03', '01', '01', '017', 'MESIN PENGEPRES KULIT'),
(930, '2', '03', '01', '01', '018', 'MESIN KOMPRESOR'),
(931, '2', '03', '01', '01', '019', 'MESIN LAS LISTRIK'),
(932, '2', '03', '01', '01', '020', 'MESIN DYNAMO KRON'),
(933, '2', '03', '01', '01', '021', 'MESIN SIKAT BESI KRON'),
(934, '2', '03', '01', '01', '022', 'MESIN PEMOTONG FIBERGLAS/POLIYSTER'),
(935, '2', '03', '01', '01', '023', 'MESIN GULUNG LISTRIK'),
(936, '2', '03', '01', '01', '024', 'MESIN PELUBANG (PERKAKAS KONSTRUKSI LOGAM TERPASANG PADA PONDASI)'),
(937, '2', '03', '01', '01', '025', 'MESIN PENEKUK/LIPAT PLAT'),
(938, '2', '03', '01', '01', '026', 'MESIN GUNTING PLAT'),
(939, '2', '03', '01', '01', '027', 'MESIN PEMBENGKOK UNI'),
(940, '2', '03', '01', '01', '028', 'MESIN AMPLAS PLAT'),
(941, '2', '03', '01', '01', '029', 'MESIN PEMOTONG PLAT'),
(942, '2', '03', '01', '01', '030', 'MESIN TRANSMISSION AUTOMOTIVE'),
(943, '2', '03', '01', '01', '031', 'MESIN PEMBENGKOK LOGAM'),
(944, '2', '03', '01', '01', '032', 'MESIN CRYSTAL GROWING'),
(945, '2', '03', '01', '01', '033', 'MESIN LASER CUTTING'),
(946, '2', '03', '01', '01', '034', 'MESIN LASER WELDING'),
(947, '2', '03', '01', '01', '035', 'MESIN LIPAT PLAT'),
(948, '2', '03', '01', '01', '036', 'MESIN BRIKET'),
(949, '2', '03', '01', '01', '037', 'UNIV. GRINDER SETING VALVE'),
(950, '2', '03', '01', '01', '038', 'UNIV. GRINDER VALVE REPAIR'),
(951, '2', '03', '01', '01', '039', 'MESIN SERUT'),
(952, '2', '03', '01', '01', '040', 'MESIN PROFILE KAYU'),
(953, '2', '03', '01', '01', '999', 'PERKAKAS KONSTRUKSI LOGAM TERPASANG PADA PONDASI LAINNYA'),
(954, '2', '03', '01', '02', '000', 'PERKAKAS KONSTRUKSI LOGAM YANG TRANSPORTABLE (BERPINDAH)'),
(955, '2', '03', '01', '02', '001', 'MESIN GERINDA TANGAN'),
(956, '2', '03', '01', '02', '002', 'MESIN BOR TANGAN'),
(957, '2', '03', '01', '02', '003', 'MESIN CYLINDER'),
(958, '2', '03', '01', '02', '004', 'RIVETING MACHINE'),
(959, '2', '03', '01', '02', '005', 'MESIN GULUNG MANUAL'),
(960, '2', '03', '01', '02', '006', 'MESIN AMPELAS TANGAN'),
(961, '2', '03', '01', '02', '007', 'MESIN AMPELAS ROL KECIL'),
(962, '2', '03', '01', '02', '008', 'MESIN GERGAJI BESI'),
(963, '2', '03', '01', '02', '999', 'PERKAKAS KONSTRUKSI LOGAM YANG TRANSPORTABLE (BERPINDAH) LAINNYA'),
(964, '2', '03', '01', '03', '000', 'PERKAKAS BENGKEL LISTRIK'),
(965, '2', '03', '01', '03', '001', 'BATTERY CHARGE'),
(966, '2', '03', '01', '03', '002', 'WINDER'),
(967, '2', '03', '01', '03', '003', 'TRANSFORMATOR'),
(968, '2', '03', '01', '03', '004', 'SOLDER LISTRIK'),
(969, '2', '03', '01', '03', '005', 'SEDOTAN TIMAH LISTRIK'),
(970, '2', '03', '01', '03', '006', 'ELECTRICAL DISCHARGE'),
(971, '2', '03', '01', '03', '007', 'VERTICAL MACHINING CENTRE'),
(972, '2', '03', '01', '03', '008', 'COPY MILLING'),
(973, '2', '03', '01', '03', '009', 'SURFACE GRINDING PROTH'),
(974, '2', '03', '01', '03', '010', 'CYDRICAL GRINDER YAM'),
(975, '2', '03', '01', '03', '011', 'CAPACITY DIE CASTING'),
(976, '2', '03', '01', '03', '012', 'HMC CINTINATI MILACRON'),
(977, '2', '03', '01', '03', '013', 'ENGINE CYLINDER RESEARCH ENGINE'),
(978, '2', '03', '01', '03', '014', 'VALVE SENSOR'),
(979, '2', '03', '01', '03', '015', 'COORDINATE MEASURING MACHINES'),
(980, '2', '03', '01', '03', '016', 'ENGINE COOLING SYSTEM'),
(981, '2', '03', '01', '03', '017', 'OUTLET MANIFODLD PRESSURE'),
(982, '2', '03', '01', '03', '018', 'IMPULSE ORBITAL WELDER'),
(983, '2', '03', '01', '03', '019', 'AVL DIGAS'),
(984, '2', '03', '01', '03', '020', 'ELECTRIC WIRE ROPE'),
(985, '2', '03', '01', '03', '021', 'STEAM PRESSURE GAUGE'),
(986, '2', '03', '01', '03', '022', 'SAVETUY VALVE'),
(987, '2', '03', '01', '03', '023', 'TRESHER STATIS'),
(988, '2', '03', '01', '03', '024', 'VARIAC'),
(989, '2', '03', '01', '03', '025', 'MIXER (PERKAKAS BENGKEL LISTRIK)'),
(990, '2', '03', '01', '03', '026', 'STEPPING MOTOR'),
(991, '2', '03', '01', '03', '027', 'CYLINDER PRESSURE TRANDUCER'),
(992, '2', '03', '01', '03', '028', 'ENGINE SIMULATION SOFTWARE PACKAGE'),
(993, '2', '03', '01', '03', '029', 'AXHAUST GAS ANALIZER'),
(994, '2', '03', '01', '03', '030', 'CIRCULAR SAW'),
(995, '2', '03', '01', '03', '031', 'TESTER LISTRIK/TELEPON/INTERNET'),
(996, '2', '03', '01', '03', '999', 'PERKAKAS BENGKEL LISTRIK LAINNYA (ALAT BENGKEL BERMESIN)'),
(997, '2', '03', '01', '04', '000', 'PERKAKAS BENGKEL SERVICE'),
(998, '2', '03', '01', '04', '001', 'AUTO LIFT'),
(999, '2', '03', '01', '04', '002', 'CAR WASHER'),
(1000, '2', '03', '01', '04', '003', 'STEAM CLEANER'),
(1001, '2', '03', '01', '04', '004', 'LUBRIACATING EQUIPMENT'),
(1002, '2', '03', '01', '04', '005', 'MESIN SPOORING'),
(1003, '2', '03', '01', '04', '006', 'MESIN BALANCER'),
(1004, '2', '03', '01', '04', '007', 'BRAKE DRUM LATHE/MESIN PERATA TROMOL'),
(1005, '2', '03', '01', '04', '008', 'PENGASAH LUBANG STANG PISTON'),
(1006, '2', '03', '01', '04', '009', 'LUBRICATING SET (PERKAKAS BENGKEL SERVICE)'),
(1007, '2', '03', '01', '04', '010', 'AIR FILTER REGULATOR'),
(1008, '2', '03', '01', '04', '011', 'DIAMOND CARE DRILL CARE'),
(1009, '2', '03', '01', '04', '012', 'AC MOTOR CONTROL'),
(1010, '2', '03', '01', '04', '999', 'PERKAKAS BENGKEL SERVICE LAINNYA (ALAT BENGKEL BERMESIN)'),
(1011, '2', '03', '01', '05', '000', 'PERKAKAS PENGANGKAT BERMESIN'),
(1012, '2', '03', '01', '05', '001', 'OVERHEAD CRANE'),
(1013, '2', '03', '01', '05', '002', 'HOIST'),
(1014, '2', '03', '01', '05', '003', 'WINCH/LIR'),
(1015, '2', '03', '01', '05', '999', 'PERKAKAS PENGANGKAT BERMESIN LAINNYA'),
(1016, '2', '03', '01', '06', '000', 'PERKAKAS BENGKEL KAYU'),
(1017, '2', '03', '01', '06', '001', 'MESIN GERGAJI'),
(1018, '2', '03', '01', '06', '002', 'MESIN KETAM (PERKAKAS BENGKEL KAYU)'),
(1019, '2', '03', '01', '06', '003', 'MESIN BOR KAYU'),
(1020, '2', '03', '01', '06', '004', 'MESIN PENGHALUS'),
(1021, '2', '03', '01', '06', '005', 'TATAH LISTRIK OSCAR MK 361'),
(1022, '2', '03', '01', '06', '006', 'PASAH LISTRIK MKC'),
(1023, '2', '03', '01', '06', '007', 'PROFILE LISTRIK MKC'),
(1024, '2', '03', '01', '06', '008', 'GRENDO DUDUK'),
(1025, '2', '03', '01', '06', '009', 'GERGAJI BENGKOK ATS'),
(1026, '2', '03', '01', '06', '010', 'AMPLAS LISTRIK GMT'),
(1027, '2', '03', '01', '06', '011', 'GERGAJI CHAIN SAW'),
(1028, '2', '03', '01', '06', '012', 'TABLE SAW 10 EASTCO'),
(1029, '2', '03', '01', '06', '999', 'PERKAKAS BENGKEL KAYU LAINNYA'),
(1030, '2', '03', '01', '07', '000', 'PERKAKAS BENGKEL KHUSUS'),
(1031, '2', '03', '01', '07', '001', 'MESIN JAHIT TERPAL'),
(1032, '2', '03', '01', '07', '002', 'PERKAKAS VULKANISIR BAN'),
(1033, '2', '03', '01', '07', '003', 'PERKAKAS BONGKAR/PASANG BAN'),
(1034, '2', '03', '01', '07', '004', 'MESIN TENUN TEKSTIL'),
(1035, '2', '03', '01', '07', '005', 'MESIN CELUP (PERKAKAS BENGKEL KHUSUS)'),
(1036, '2', '03', '01', '07', '006', 'PEMASANG BARU'),
(1037, '2', '03', '01', '07', '007', 'MESIN TENUN JAHIT'),
(1038, '2', '03', '01', '07', '999', 'PERKAKAS BENGKEL KHUSUS LAINNYA'),
(1039, '2', '03', '01', '08', '000', 'PERALATAN LAS'),
(1040, '2', '03', '01', '08', '001', 'PERALATAN LAS LISTRIK'),
(1041, '2', '03', '01', '08', '002', 'PERALATAN LAS KARBIT'),
(1042, '2', '03', '01', '08', '003', 'PERALATAN LAS GAS'),
(1043, '2', '03', '01', '08', '999', 'PERALATAN LAS LAINNYA'),
(1044, '2', '03', '01', '99', '000', 'ALAT BENGKEL BERMESIN LAINNYA'),
(1045, '2', '03', '01', '99', '999', 'ALAT BENGKEL BERMESIN LAINNYA'),
(1046, '2', '03', '02', '00', '000', 'ALAT BENGKEL TAK BERMESIN'),
(1047, '2', '03', '02', '01', '000', 'PERKAKAS BENGKEL KONSTRUKSI LOGAM'),
(1048, '2', '03', '02', '01', '001', 'PERKAKAS DAPUR TEMPA'),
(1049, '2', '03', '02', '01', '002', 'PERKAKAS BANGKU KERJA'),
(1050, '2', '03', '02', '01', '003', 'PERKAKAS PENGUKUR'),
(1051, '2', '03', '02', '01', '004', 'PERKAKAS PENGECORAN LOGAM'),
(1052, '2', '03', '02', '01', '005', 'R O L'),
(1053, '2', '03', '02', '01', '006', 'PERKAKAS PEMOTONG PLAT'),
(1054, '2', '03', '02', '01', '007', 'PERKAKAS PRESS HIDROLIK'),
(1055, '2', '03', '02', '01', '008', 'PERKAKAS PEMOTONG KABEL SLING'),
(1056, '2', '03', '02', '01', '009', 'PERKAKAS PENGECATAN KENDARAAN'),
(1057, '2', '03', '02', '01', '999', 'PERKAKAS BENGKEL KONSTRUKSI LOGAM LAINNYA'),
(1058, '2', '03', '02', '02', '000', 'PERKAKAS BENGKEL LISTRIK'),
(1059, '2', '03', '02', '02', '001', 'ARMATURE DRYING OVEN'),
(1060, '2', '03', '02', '02', '002', 'MICA UNDERCUTTER'),
(1061, '2', '03', '02', '02', '003', 'COMMUTATOR TURNING TOOL'),
(1062, '2', '03', '02', '02', '004', 'ARMATURE CROWLER'),
(1063, '2', '03', '02', '02', '005', 'SOLID STATE SOLDERING GUN'),
(1064, '2', '03', '02', '02', '999', 'PERKAKAS BENGKEL LISTRIK LAINNYA (ALAT BENGKEL TAK BERMESIN)'),
(1065, '2', '03', '02', '03', '000', 'PERKAKAS BENGKEL SERVICE'),
(1066, '2', '03', '02', '03', '001', 'PERKAKAS BENGKEL SERVICE'),
(1067, '2', '03', '02', '03', '002', 'LUBRICATING SET (PERKAKAS BENGKEL SERVICE)'),
(1068, '2', '03', '02', '03', '003', 'PERLENGKAPAN BENGKEL MEKANIK'),
(1069, '2', '03', '02', '03', '004', 'JEMBATAN SERVICE HIDROLIK'),
(1070, '2', '03', '02', '03', '999', 'PERKAKAS BENGKEL SERVICE LAINNYA (ALAT BENGKEL TAK BERMESIN)'),
(1071, '2', '03', '02', '04', '000', 'PERKAKAS PENGANGKAT'),
(1072, '2', '03', '02', '04', '001', 'DONGKRAK MEKANIK'),
(1073, '2', '03', '02', '04', '002', 'DONGKRAK HIDROLIK'),
(1074, '2', '03', '02', '04', '003', 'T A K E L'),
(1075, '2', '03', '02', '04', '004', 'G A N T R Y'),
(1076, '2', '03', '02', '04', '005', 'T R I P O D'),
(1077, '2', '03', '02', '04', '006', 'FLOOR CRANE'),
(1078, '2', '03', '02', '04', '999', 'PERKAKAS PENGANGKAT LAINNYA'),
(1079, '2', '03', '02', '05', '000', 'PERKAKAS STANDARD (STANDARD TOOLS)'),
(1080, '2', '03', '02', '05', '001', 'TOOL KIT SET'),
(1081, '2', '03', '02', '05', '002', 'TOOL KIT BOX'),
(1082, '2', '03', '02', '05', '003', 'TOOL CABINET SET'),
(1083, '2', '03', '02', '05', '004', 'KUNCI PIPA'),
(1084, '2', '03', '02', '05', '005', 'PULLER SET'),
(1085, '2', '03', '02', '05', '006', 'TAP DIES'),
(1086, '2', '03', '02', '05', '007', 'GREEPER'),
(1087, '2', '03', '02', '05', '008', 'ENGINE STAND'),
(1088, '2', '03', '02', '05', '009', 'KUNCI MOMENT'),
(1089, '2', '03', '02', '05', '010', 'PEMBUAT FISIK (DIESS)'),
(1090, '2', '03', '02', '05', '011', 'TUNGKU NON FERROUS'),
(1091, '2', '03', '02', '05', '012', 'WHEEL CHOCK (PERKAKAS STANDARD (STANDARD TOOLS))'),
(1092, '2', '03', '02', '05', '013', 'MAINTENANCE STEP'),
(1093, '2', '03', '02', '05', '014', 'CRIMPING TOLLS'),
(1094, '2', '03', '02', '05', '015', 'TOOLKIT TUKANG KAYU TON'),
(1095, '2', '03', '02', '05', '016', 'TOOLKIT TUKANG BATU TON'),
(1096, '2', '03', '02', '05', '017', 'TOOLKIT TUKANG LISTRIK'),
(1097, '2', '03', '02', '05', '018', 'TOOLKIT PEMELIHARAAN'),
(1098, '2', '03', '02', '05', '019', 'TOOLKIT PERBENGKELAN'),
(1099, '2', '03', '02', '05', '020', 'TOOLKIT PERPIPAAN'),
(1100, '2', '03', '02', '05', '021', 'TOOL OUTFIT PIONER ELECTRIC'),
(1101, '2', '03', '02', '05', '022', 'TOOL GENERAL MECHANIC SET'),
(1102, '2', '03', '02', '05', '023', 'TOOLKIT TUKANG BESI'),
(1103, '2', '03', '02', '05', '024', 'TOOL ELECTRICAL SET'),
(1104, '2', '03', '02', '05', '025', 'SAWMIL'),
(1105, '2', '03', '02', '05', '026', 'UNIT PELUMAS PORTABLE'),
(1106, '2', '03', '02', '05', '027', 'SCAFOLDING SET & TOOL'),
(1107, '2', '03', '02', '05', '028', 'HAND FALLET'),
(1108, '2', '03', '02', '05', '029', 'PARON'),
(1109, '2', '03', '02', '05', '030', 'CYLINDER BEARING'),
(1110, '2', '03', '02', '05', '031', 'PERLENGKAPAN BENGKEL PENGECATAN'),
(1111, '2', '03', '02', '05', '999', 'PERKAKAS STANDARD (STANDARD TOOLS) LAINNYA'),
(1112, '2', '03', '02', '06', '000', 'PERKAKAS KHUSUS (SPECIAL TOOLS)'),
(1113, '2', '03', '02', '06', '001', 'KUNCI KHUSUS UNTUK ENGINE'),
(1114, '2', '03', '02', '06', '002', 'KUNCI KHUSUS ALAT BESAR DARAT'),
(1115, '2', '03', '02', '06', '003', 'KUNCI KHUSUS ALAT BESAR APUNG'),
(1116, '2', '03', '02', '06', '004', 'KUNCI KHUSUS CASIS ALAT ANGKUT DARAT'),
(1117, '2', '03', '02', '06', '005', 'KUNCI KHUSUS CASIS'),
(1118, '2', '03', '02', '06', '006', 'KUNCI KHUSUS ALAT ANGKUT APUNG'),
(1119, '2', '03', '02', '06', '007', 'KUNCI KHUSUS PEMBUKA MUR/BAUT'),
(1120, '2', '03', '02', '06', '008', 'KUNCI KHUSUS MOMENT'),
(1121, '2', '03', '02', '06', '009', 'KUNCI KHUSUS ALAT BESAR UDARA'),
(1122, '2', '03', '02', '06', '010', 'KUNCI KHUSUS CASIS ALAT BESAR UDARA'),
(1123, '2', '03', '02', '06', '011', 'DIGITAL TANG AMPERE'),
(1124, '2', '03', '02', '06', '012', 'DIGITAL TACHOMETER'),
(1125, '2', '03', '02', '06', '013', 'FOOT KLEP'),
(1126, '2', '03', '02', '06', '014', 'CINCIN/KOPLING SLANG HYDRANT'),
(1127, '2', '03', '02', '06', '015', 'KUNCI L'),
(1128, '2', '03', '02', '06', '016', 'TBA'),
(1129, '2', '03', '02', '06', '999', 'PERKAKAS KHUSUS (SPECIAL TOOLS) LAINNYA'),
(1130, '2', '03', '02', '07', '000', 'PERKAKAS BENGKEL KERJA'),
(1131, '2', '03', '02', '07', '001', 'GERGAJI'),
(1132, '2', '03', '02', '07', '002', 'KETAM'),
(1133, '2', '03', '02', '07', '003', 'BOR'),
(1134, '2', '03', '02', '07', '004', 'PAHAT'),
(1135, '2', '03', '02', '07', '005', 'KAKAK TUA'),
(1136, '2', '03', '02', '07', '006', 'WATER PAS'),
(1137, '2', '03', '02', '07', '007', 'SIKU'),
(1138, '2', '03', '02', '07', '008', 'PALU'),
(1139, '2', '03', '02', '07', '999', 'PERKAKAS BENGKEL KERJA LAINNYA'),
(1140, '2', '03', '02', '08', '000', 'PERALATAN TUKANG BESI'),
(1141, '2', '03', '02', '08', '001', 'TANGGEM'),
(1142, '2', '03', '02', '08', '002', 'GUNTING PLAT'),
(1143, '2', '03', '02', '08', '003', 'LANDASAN KENTENG'),
(1144, '2', '03', '02', '08', '004', 'KUNCI KAUL'),
(1145, '2', '03', '02', '08', '005', 'GUNTING PLAT TANGAN'),
(1146, '2', '03', '02', '08', '006', 'TANG KOMBINASI'),
(1147, '2', '03', '02', '08', '007', 'TANG POTONG'),
(1148, '2', '03', '02', '08', '008', '\"BETEL'),
(1149, '2', '03', '02', '08', '009', 'PUKUL KONDE'),
(1150, '2', '03', '02', '08', '010', 'PUKUL LENGKUNG'),
(1151, '2', '03', '02', '08', '011', 'PUKUL SABIT'),
(1152, '2', '03', '02', '08', '012', 'KIKIR'),
(1153, '2', '03', '02', '08', '013', 'KUNCI PAS'),
(1154, '2', '03', '02', '08', '014', 'TANG SENAI & TAP'),
(1155, '2', '03', '02', '08', '015', 'DREI BIASA (OBENG)'),
(1156, '2', '03', '02', '08', '016', 'DREI KEMBANG (OBENG)'),
(1157, '2', '03', '02', '08', '017', 'DREI KETOK (OBENG)'),
(1158, '2', '03', '02', '08', '018', 'SEKET MAT'),
(1159, '2', '03', '02', '08', '019', 'JANGKA BESI'),
(1160, '2', '03', '02', '08', '020', 'KUNCI STANG'),
(1161, '2', '03', '02', '08', '999', 'PERALATAN TUKANG BESI LAINNYA'),
(1162, '2', '03', '02', '09', '000', 'PERALATAN TUKANG KAYU'),
(1163, '2', '03', '02', '09', '001', 'TATAH BIASA'),
(1164, '2', '03', '02', '09', '002', 'TATAH LENGKUNG'),
(1165, '2', '03', '02', '09', '003', 'KAOTA'),
(1166, '2', '03', '02', '09', '004', 'PETEL'),
(1167, '2', '03', '02', '09', '005', 'PATAR'),
(1168, '2', '03', '02', '09', '006', 'BOR ENGKOL'),
(1169, '2', '03', '02', '09', '007', 'PERLENGKAPAN BENGKEL KAYU'),
(1170, '2', '03', '02', '09', '999', 'PERALATAN TUKANG KAYU LAINNYA'),
(1171, '2', '03', '02', '10', '000', 'PERALATAN TUKANG KULIT'),
(1172, '2', '03', '02', '10', '001', 'PISAU KULIT'),
(1173, '2', '03', '02', '10', '002', 'PANDOKAN SEPATU'),
(1174, '2', '03', '02', '10', '003', 'LIS SEPATU'),
(1175, '2', '03', '02', '10', '004', 'COKRO'),
(1176, '2', '03', '02', '10', '005', 'PLONG KULIT'),
(1177, '2', '03', '02', '10', '006', 'CATUT'),
(1178, '2', '03', '02', '10', '007', 'PUKUL SEPATU'),
(1179, '2', '03', '02', '10', '008', 'GUNTING KULIT'),
(1180, '2', '03', '02', '10', '009', 'GUNTING KAIN'),
(1181, '2', '03', '02', '10', '010', 'DREK MATA AYAM'),
(1182, '2', '03', '02', '10', '012', 'UNCEK'),
(1183, '2', '03', '02', '10', '999', 'PERALATAN TUKANG KULIT LAINNYA'),
(1184, '2', '03', '02', '11', '000', '\"PERALATAN UKUR'),
(1185, '2', '03', '02', '11', '001', 'DIPAN UKUR'),
(1186, '2', '03', '02', '11', '002', 'METERAN KAIN'),
(1187, '2', '03', '02', '11', '003', 'ROL METER'),
(1188, '2', '03', '02', '11', '004', 'JANGKA BERKAKI'),
(1189, '2', '03', '02', '11', '005', 'PATAR GIP'),
(1190, '2', '03', '02', '11', '006', 'PISAU GIP'),
(1191, '2', '03', '02', '11', '007', 'PARAREL BAR'),
(1192, '2', '03', '02', '11', '008', 'CERMIN BESAR'),
(1193, '2', '03', '02', '11', '009', 'TANGGA LATIHAN'),
(1194, '2', '03', '02', '11', '010', 'TRAP LATIHAN'),
(1195, '2', '03', '02', '11', '999', '\"PERALATAN UKUR'),
(1196, '2', '03', '02', '12', '000', 'PERALATAN BENGKEL KHUSUS PELADAM'),
(1197, '2', '03', '02', '12', '001', 'MESIN CNC'),
(1198, '2', '03', '02', '12', '002', 'DYNAMO TUNGKU'),
(1199, '2', '03', '02', '12', '003', 'MESIN FRAIS'),
(1200, '2', '03', '02', '12', '004', 'MESIN SKRAF'),
(1201, '2', '03', '02', '12', '005', 'MESIN BOR MEJA / KAKI LISTRIK'),
(1202, '2', '03', '02', '12', '006', 'PALU BESAR'),
(1203, '2', '03', '02', '12', '007', 'MESIN KORTER'),
(1204, '2', '03', '02', '12', '008', 'PALU KECIL'),
(1205, '2', '03', '02', '12', '009', 'MESIN GERINDA DUDUK (BENCH GERINDA)'),
(1206, '2', '03', '02', '12', '010', 'GEGEP PEMOTONG KUKU'),
(1207, '2', '03', '02', '12', '011', 'GEGEP PEMOTONG PAKU'),
(1208, '2', '03', '02', '12', '012', 'PISAU RENET'),
(1209, '2', '03', '02', '12', '013', 'MESIN JAHIT TERPAL'),
(1210, '2', '03', '02', '12', '014', 'PELOBANG TAPEL'),
(1211, '2', '03', '02', '12', '015', 'TANG BUAYA'),
(1212, '2', '03', '02', '12', '016', 'MESIN BATTERY SET / PENGISI ACCU'),
(1213, '2', '03', '02', '12', '017', 'PERALATAN BENGKEL LAINNYA'),
(1214, '2', '03', '02', '12', '018', 'MESIN BLOWER LISTRIK / MEKANIK'),
(1215, '2', '03', '02', '12', '019', 'MESIN SIKAT / BRUSH MACHINE'),
(1216, '2', '03', '02', '12', '020', 'MESIN PEMBUKA BAN'),
(1217, '2', '03', '02', '12', '021', 'MESIN SLEP KRUK AS'),
(1218, '2', '03', '02', '12', '022', 'MESIN ASAH SILIDER COP'),
(1219, '2', '03', '02', '12', '023', 'MESIN GULUNG SPOOL'),
(1220, '2', '03', '02', '12', '024', 'MESIN GULUNG PLAT'),
(1221, '2', '03', '02', '12', '025', 'MESIN POMPA AIR PMK'),
(1222, '2', '03', '02', '12', '026', 'MESIN ASAH KLEP'),
(1223, '2', '03', '02', '12', '027', 'MESIN TUSUK / STIK'),
(1224, '2', '03', '02', '12', '028', 'MESIN BOR LISTRIK TANGAN'),
(1225, '2', '03', '02', '12', '029', 'MESIN NIMBLING'),
(1226, '2', '03', '02', '12', '030', 'MESIN GERINDA TANGAN LISTRIK'),
(1227, '2', '03', '02', '12', '031', 'MESIN POTONG PLAT BENTUK / HAND NIMBLER'),
(1228, '2', '03', '02', '12', '032', 'UNIT CAT'),
(1229, '2', '03', '02', '12', '033', 'CUT OFF SAW'),
(1230, '2', '03', '02', '12', '034', 'MESIN ANALISA SYSTEM'),
(1231, '2', '03', '02', '12', '035', 'BLENDER LAS POTONG'),
(1232, '2', '03', '02', '12', '036', 'MESIN CUCI KENDARAAN/ CAR WASHER'),
(1233, '2', '03', '02', '12', '037', 'PERKAKAS AC'),
(1234, '2', '03', '02', '12', '999', 'PERALATAN BENGKEL KHUSUS PELADAM LAINNYA'),
(1235, '2', '03', '02', '99', '000', 'ALAT BENGKEL TAK BERMESIN LAINNYA'),
(1236, '2', '03', '02', '99', '999', 'ALAT BENGKEL TAK BERMESIN LAINNYA'),
(1237, '2', '03', '03', '00', '000', 'ALAT UKUR'),
(1238, '2', '03', '03', '01', '000', 'ALAT UKUR UNIVERSAL'),
(1239, '2', '03', '03', '01', '001', 'AF GENERATOR TONE GENERATOR'),
(1240, '2', '03', '03', '01', '002', 'AUDIO SIGNAL SOURCE'),
(1241, '2', '03', '03', '01', '003', 'AUDIO TEST SET'),
(1242, '2', '03', '03', '01', '004', 'AUDIO MORSE & DISTRIBUTOR METER'),
(1243, '2', '03', '03', '01', '005', 'AUDIO SWEEP OSILATOR'),
(1244, '2', '03', '03', '01', '006', 'VTVM VOLT'),
(1245, '2', '03', '03', '01', '007', 'INDEPENDENCE METER'),
(1246, '2', '03', '03', '01', '008', 'DECIBLE METER'),
(1247, '2', '03', '03', '01', '009', 'CRT TESTER'),
(1248, '2', '03', '03', '01', '010', 'CIRCUIT TESTER (ALAT UKUR UNIVERSAL)'),
(1249, '2', '03', '03', '01', '011', 'ELECTRONIC CAPASITOR TESTER'),
(1250, '2', '03', '03', '01', '012', 'ILLUMINO METER'),
(1251, '2', '03', '03', '01', '013', 'IC TESTER SEMI TEST IV'),
(1252, '2', '03', '03', '01', '014', 'IC METER'),
(1253, '2', '03', '03', '01', '015', 'MIHVOLT METER'),
(1254, '2', '03', '03', '01', '016', 'MULTITESTER & ACCESSORIE'),
(1255, '2', '03', '03', '01', '017', 'MULTISESTER DIGITAL'),
(1256, '2', '03', '03', '01', '018', 'PHOTO ILLUMINATION METER'),
(1257, '2', '03', '03', '01', '019', 'TRANSISTOR TESTER SEMITEST I'),
(1258, '2', '03', '03', '01', '020', 'TRANSISTOR TESTER SEMITEST II'),
(1259, '2', '03', '03', '01', '021', 'TRANSISTOR TESTER SEMITEST V'),
(1260, '2', '03', '03', '01', '022', 'TRANSISTOR TESTER AVO'),
(1261, '2', '03', '03', '01', '023', 'VOLT METER ELEKTRONIK'),
(1262, '2', '03', '03', '01', '024', 'VOLT METER DIGITAL'),
(1263, '2', '03', '03', '01', '025', 'VOLT METER HIGT TENSION'),
(1264, '2', '03', '03', '01', '026', 'WIDW BAND LEVEL METER'),
(1265, '2', '03', '03', '01', '027', 'AUTOMATIC DISTROTION METER'),
(1266, '2', '03', '03', '01', '028', 'POWER METER AND ACCESSORIES'),
(1267, '2', '03', '03', '01', '029', 'PH METER (ALAT UKUR UNIVERSAL)'),
(1268, '2', '03', '03', '01', '030', 'QUASI PEAK METER'),
(1269, '2', '03', '03', '01', '031', 'THRULINE WATT METER'),
(1270, '2', '03', '03', '01', '032', 'DIGITAL MULTIMETER (ALAT UKUR UNIVERSAL)'),
(1271, '2', '03', '03', '01', '033', 'MULTI METER'),
(1272, '2', '03', '03', '01', '034', 'METER CALIBRATOR'),
(1273, '2', '03', '03', '01', '035', 'MOISE FIGURE METER'),
(1274, '2', '03', '03', '01', '036', 'DISTORTION ANALYZER'),
(1275, '2', '03', '03', '01', '037', 'VECTOR VOLT METER (ALAT UKUR UNIVERSAL)'),
(1276, '2', '03', '03', '01', '038', 'PULSE GENERATOR (ALAT UKUR UNIVERSAL)'),
(1277, '2', '03', '03', '01', '039', 'DME GROUND STATION TEST SET (ALAT UKUR UNIVERSAL)'),
(1278, '2', '03', '03', '01', '040', 'UHF SIGNAL GENERATOR'),
(1279, '2', '03', '03', '01', '041', 'SWEEP OSCILLATOR (ALAT UKUR UNIVERSAL)'),
(1280, '2', '03', '03', '01', '042', 'VHF SIGNAL GENERATOR'),
(1281, '2', '03', '03', '01', '043', 'SPEKTRUM ANALYZER'),
(1282, '2', '03', '03', '01', '044', 'TUBE TESTER (ALAT UKUR UNIVERSAL)'),
(1283, '2', '03', '03', '01', '045', 'DOSIMETER & ACCESORIES'),
(1284, '2', '03', '03', '01', '046', 'SURVEY METER (ALAT UKUR UNIVERSAL)'),
(1285, '2', '03', '03', '01', '047', 'SOUND DETECTOR'),
(1286, '2', '03', '03', '01', '048', 'VIDICON QUICK TESTER'),
(1287, '2', '03', '03', '01', '049', 'PATTERN FOR TV ADJUSTMENT'),
(1288, '2', '03', '03', '01', '050', 'POWER METER CILLIBRATOR'),
(1289, '2', '03', '03', '01', '051', 'THERMISTOR'),
(1290, '2', '03', '03', '01', '052', '\"SIGNAL GENERATOR AUDIO VHF'),
(1291, '2', '03', '03', '01', '053', 'X - TAL DETECTOR'),
(1292, '2', '03', '03', '01', '054', 'CO - AXIAL SLOT LINE'),
(1293, '2', '03', '03', '01', '055', 'RF VOLT METER'),
(1294, '2', '03', '03', '01', '056', 'FREKQUENCY WAVE METER'),
(1295, '2', '03', '03', '01', '057', 'MEGGER'),
(1296, '2', '03', '03', '01', '058', 'CO AXIAL ATTENUATOR'),
(1297, '2', '03', '03', '01', '059', 'VARIABEL CO AXIAL ATTENUATOR'),
(1298, '2', '03', '03', '01', '060', 'DIRECTIONAL COUPLER (ALAT UKUR UNIVERSAL)'),
(1299, '2', '03', '03', '01', '061', 'PIN MODULATOR'),
(1300, '2', '03', '03', '01', '062', 'LOGIG TROUBLE SHOTING KIT'),
(1301, '2', '03', '03', '01', '063', 'SWR METER'),
(1302, '2', '03', '03', '01', '064', 'MEMORI PROGRAMMER'),
(1303, '2', '03', '03', '01', '065', 'LOGIG STATC ANALYZER'),
(1304, '2', '03', '03', '01', '066', 'FREQUENCY CUONTER'),
(1305, '2', '03', '03', '01', '067', 'UNIVERSAL BRIDGE'),
(1306, '2', '03', '03', '01', '068', 'FB METER'),
(1307, '2', '03', '03', '01', '069', 'NOISE'),
(1308, '2', '03', '03', '01', '070', 'RADIATION MONITOR ISOTROPIC'),
(1309, '2', '03', '03', '01', '071', 'PHASE METER'),
(1310, '2', '03', '03', '01', '072', 'GLOBAL POSITIONING SYSTEM'),
(1311, '2', '03', '03', '01', '073', 'ILS. CALIBRATION RX.'),
(1312, '2', '03', '03', '01', '074', 'DCP ( ALAT CONTROL ) SENSOR'),
(1313, '2', '03', '03', '01', '075', 'MOISTEUR METER'),
(1314, '2', '03', '03', '01', '076', 'ROTA METER'),
(1315, '2', '03', '03', '01', '077', 'MINI PHASEC VIEW'),
(1316, '2', '03', '03', '01', '078', 'FREQUENCY INVERTER'),
(1317, '2', '03', '03', '01', '079', 'ACCUMETER'),
(1318, '2', '03', '03', '01', '080', 'TEMPERATUR DIGITAL'),
(1319, '2', '03', '03', '01', '081', 'ARGOMETER'),
(1320, '2', '03', '03', '01', '082', 'DIAL TEST INDICATOR'),
(1321, '2', '03', '03', '01', '083', 'SPEED METER'),
(1322, '2', '03', '03', '01', '084', '\"OIL BATH'),
(1323, '2', '03', '03', '01', '085', 'SPEED DETECTOR'),
(1324, '2', '03', '03', '01', '086', 'THERMOHYGROMETER (ALAT UKUR UNIVERSAL)'),
(1325, '2', '03', '03', '01', '087', 'TRAFFIC COUNTER'),
(1326, '2', '03', '03', '01', '088', 'STANDAR TEST GAUGE'),
(1327, '2', '03', '03', '01', '090', 'SIGMA METER'),
(1328, '2', '03', '03', '01', '091', 'IONISASI METER'),
(1329, '2', '03', '03', '01', '092', 'ROTAN SAMPLER SPLITER'),
(1330, '2', '03', '03', '01', '093', 'HENRY METER'),
(1331, '2', '03', '03', '01', '094', 'MESIN KOCOK HORISONTAL'),
(1332, '2', '03', '03', '01', '095', 'CAPASITOR METER'),
(1333, '2', '03', '03', '01', '096', 'MICROPROCESSOR CONDUCTIVITY'),
(1334, '2', '03', '03', '01', '097', 'UHF OUT PUSTTESSTING EQUIPMENT'),
(1335, '2', '03', '03', '01', '098', 'SHRANGKAGE LIMIT APPARATUS'),
(1336, '2', '03', '03', '01', '099', 'R.F. SIGNAL GENERATOR'),
(1337, '2', '03', '03', '01', '100', 'DEWMETER PRINT'),
(1338, '2', '03', '03', '01', '102', 'ORBITAL SHAKER'),
(1339, '2', '03', '03', '01', '103', 'VHF/UHF DUMMY LOAD'),
(1340, '2', '03', '03', '01', '104', 'OZONIZER'),
(1341, '2', '03', '03', '01', '105', 'PSOPHOMETRIC WEIGHTING NETWORK'),
(1342, '2', '03', '03', '01', '106', 'PERSONAL CDT'),
(1343, '2', '03', '03', '01', '107', 'PORTABLE TEST RECK'),
(1344, '2', '03', '03', '01', '108', 'RADIO METER (ALAT UKUR UNIVERSAL)'),
(1345, '2', '03', '03', '01', '109', 'NMOTOR DRIVE WIRE WROPPER'),
(1346, '2', '03', '03', '01', '110', 'SALINITY TEMP DEPTH ANALIZER'),
(1347, '2', '03', '03', '01', '111', 'DIGITAL CIRCUIT TESTER'),
(1348, '2', '03', '03', '01', '112', 'SALINOMETER'),
(1349, '2', '03', '03', '01', '113', 'FIELD STRENGTH METER'),
(1350, '2', '03', '03', '01', '114', 'ACIENTIFIC SOUNDEER SYSTEM'),
(1351, '2', '03', '03', '01', '115', 'ALTERNEATUR'),
(1352, '2', '03', '03', '01', '116', 'SENTER BAWAH AIR'),
(1353, '2', '03', '03', '01', '117', 'MEGA OHM TESTER'),
(1354, '2', '03', '03', '01', '118', 'SIX PLACE HIDROMANIFOLD'),
(1355, '2', '03', '03', '01', '119', 'INSULATION TESTER (ALAT UKUR UNIVERSAL)'),
(1356, '2', '03', '03', '01', '120', 'SONICATOR VIRSOIC CALL DISLUPTOR'),
(1357, '2', '03', '03', '01', '121', 'ELECTRIC BENCH'),
(1358, '2', '03', '03', '01', '122', 'SWEEP FUNCTION GENERATOR'),
(1359, '2', '03', '03', '01', '123', 'LOADMETER'),
(1360, '2', '03', '03', '01', '124', 'SYSTEM UV STERELISASI DAN SIRKULASI AI'),
(1361, '2', '03', '03', '01', '125', 'COUNTER TESTER'),
(1362, '2', '03', '03', '01', '126', 'SYSTEM FOR CHEMICAL OXYGEN DEMOND'),
(1363, '2', '03', '03', '01', '127', 'THE DACOR SEASPRINT UNDER WATER VEHICLE'),
(1364, '2', '03', '03', '01', '128', 'TITRATION UNIT'),
(1365, '2', '03', '03', '01', '129', 'ULTRASONIC CLEANER (ALAT UKUR UNIVERSAL)'),
(1366, '2', '03', '03', '01', '130', 'WATER ANALYSIS KIT'),
(1367, '2', '03', '03', '01', '131', 'WHEEL METER'),
(1368, '2', '03', '03', '01', '132', 'PROYECTION POLARISCOPE'),
(1369, '2', '03', '03', '01', '133', 'CDMA/GSM TEST'),
(1370, '2', '03', '03', '01', '134', 'ANTENNA SELECTOR'),
(1371, '2', '03', '03', '01', '135', 'LOG PERIODIC ANTENNA'),
(1372, '2', '03', '03', '01', '136', 'ALAT UKUR SIGMAT'),
(1373, '2', '03', '03', '01', '999', 'ALAT UKUR UNIVERSAL LAINNYA'),
(1374, '2', '03', '03', '02', '000', 'UNIVERSAL TESTER'),
(1375, '2', '03', '03', '02', '001', 'FREQUENCY COUNTER (UNIVERSAL TESTER)'),
(1376, '2', '03', '03', '02', '002', 'INSULATION RES METER MOD'),
(1377, '2', '03', '03', '02', '003', 'NOISE & DISTORTION METER'),
(1378, '2', '03', '03', '02', '004', 'OSCILATOR DISTORTION METER'),
(1379, '2', '03', '03', '02', '005', 'OSCILATOR TEST SIGNAL'),
(1380, '2', '03', '03', '02', '006', 'OSCILATOR WIDW BAND'),
(1381, '2', '03', '03', '02', '007', 'OSCILATOR SWEEP'),
(1382, '2', '03', '03', '02', '008', 'PRECISION ENCODER MONITOR'),
(1383, '2', '03', '03', '02', '009', 'PLAMBICON TEST UNIT'),
(1384, '2', '03', '03', '02', '010', 'SCANNER (UNIVERSAL TESTER)'),
(1385, '2', '03', '03', '02', '011', 'TIME INTERVAL UNIT'),
(1386, '2', '03', '03', '02', '012', 'UNIVERSAL COUNTER (UNIVERSAL TESTER)'),
(1387, '2', '03', '03', '02', '013', 'VIDEO NOISE METER'),
(1388, '2', '03', '03', '02', '014', 'ADMINTANCE METER'),
(1389, '2', '03', '03', '02', '015', 'ADMINTANCE BRIDE'),
(1390, '2', '03', '03', '02', '016', 'FIELDSTRENGTH METER'),
(1391, '2', '03', '03', '02', '017', 'RF BRIDGE'),
(1392, '2', '03', '03', '02', '018', 'RF PUSH BUTTON ATTENUATOR'),
(1393, '2', '03', '03', '02', '019', 'VISION AND SOUND NYQUIST DEMODULATOR AMF'),
(1394, '2', '03', '03', '02', '020', 'V.S.W.R STANDING REVIEW'),
(1395, '2', '03', '03', '02', '022', 'DIGITAL FREQUENCE METER'),
(1396, '2', '03', '03', '02', '023', 'VINDICAM QUICK TESTER'),
(1397, '2', '03', '03', '02', '024', 'COAXIAL ATT'),
(1398, '2', '03', '03', '02', '025', 'VARIABLE COAXIAL ATT'),
(1399, '2', '03', '03', '02', '026', 'LOGIC PROBE (UNIVERSAL TESTER)'),
(1400, '2', '03', '03', '02', '027', 'SURVEY METER (UNIVERSAL TESTER)'),
(1401, '2', '03', '03', '02', '028', 'LOGIC COMPARATOR'),
(1402, '2', '03', '03', '02', '999', 'UNIVERSAL TESTER LAINNYA'),
(1403, '2', '03', '03', '03', '000', 'ALAT UKUR/PEMBANDING'),
(1404, '2', '03', '03', '03', '001', 'UKURAN JOHANSON (ALAT PEMBANDING STANDAR UKURAN PANJANG)'),
(1405, '2', '03', '03', '03', '002', 'MICRO INDICATOR (DENGAN PERLENGKAPAN SUPARTO POINTERS DAN REVOLV'),
(1406, '2', '03', '03', '03', '003', 'PERLENGKAPAN MICRO INDICATOR'),
(1407, '2', '03', '03', '03', '004', 'PSYCOMETER VANLAMBRECHT'),
(1408, '2', '03', '03', '03', '005', 'PSYCOMETER'),
(1409, '2', '03', '03', '03', '006', 'BAROMETER LOGAM'),
(1410, '2', '03', '03', '03', '007', 'BAROMETER MERCURY'),
(1411, '2', '03', '03', '03', '008', 'MANOMETER UNTUK MESIN'),
(1412, '2', '03', '03', '03', '009', 'MONOTOR PRECISI'),
(1413, '2', '03', '03', '03', '010', 'ALAT PEMERIKSA MANOMETER ( DENGAN PERLENGKAPAN )'),
(1414, '2', '03', '03', '03', '011', 'ALAT PEMERIKSAAN ZAT CAIR'),
(1415, '2', '03', '03', '03', '012', 'TERMOMETER STANDAR'),
(1416, '2', '03', '03', '03', '013', 'TERMOMETER GOVERMEN TESTER 0 DERAJAT SAMPAI DENGAN 100 DERAJAT C'),
(1417, '2', '03', '03', '03', '014', 'THERMOSTAT ( PENGUJI PEMERIKSAAN TERMOMETER )'),
(1418, '2', '03', '03', '03', '015', 'JAM UKUR ( MEET LOCK )'),
(1419, '2', '03', '03', '03', '016', 'HARDNES TESTER'),
(1420, '2', '03', '03', '03', '017', 'STOPWATCH'),
(1421, '2', '03', '03', '03', '018', 'LOUP'),
(1422, '2', '03', '03', '03', '019', 'PLANIMETER (ALAT UKUR/PEMBANDING)'),
(1423, '2', '03', '03', '03', '020', 'METRA BLOCK'),
(1424, '2', '03', '03', '03', '021', 'LEMARI BAJA PENGERING'),
(1425, '2', '03', '03', '03', '022', 'SANBLAS UNIT'),
(1426, '2', '03', '03', '03', '023', 'ALAT PEMERIKSAAN TIMBANGAN TEKANAN BERODA'),
(1427, '2', '03', '03', '03', '024', 'STELAN INSTRUMEN BOURJE'),
(1428, '2', '03', '03', '03', '025', 'LAMPU UNTUK MENERANGI SKALA NERACA PAKAI STANDAR'),
(1429, '2', '03', '03', '03', '026', 'AVOMETER SU 20 - 20 K'),
(1430, '2', '03', '03', '03', '027', 'TRAPPO 1.000 WATT'),
(1431, '2', '03', '03', '03', '028', 'TOOL SET'),
(1432, '2', '03', '03', '03', '029', 'LANDASAN CAP LENGKAP'),
(1433, '2', '03', '03', '03', '030', 'KAKI TIGA GANTUNGAN DACIN'),
(1434, '2', '03', '03', '03', '031', 'ALAT PENDATAR TAKARAN BENSIN'),
(1435, '2', '03', '03', '03', '032', 'TANG PLOMBIR / SEGEL'),
(1436, '2', '03', '03', '03', '033', 'EXICATOR BESAR'),
(1437, '2', '03', '03', '03', '034', 'EXICATOR KECIL'),
(1438, '2', '03', '03', '03', '035', 'DESICATOR ( SIZE ) 3'),
(1439, '2', '03', '03', '03', '036', 'DESICATOR ( SIZE ) 4'),
(1440, '2', '03', '03', '03', '037', 'BOTOL AIR SALING DARI 25 LITER'),
(1441, '2', '03', '03', '03', '038', 'PICNOMETER'),
(1442, '2', '03', '03', '03', '039', 'DESIMETER ( HIDROMETER )'),
(1443, '2', '03', '03', '03', '040', 'TELESCOPE TILE VARIEBLE'),
(1444, '2', '03', '03', '03', '041', 'OPTICAL STREAN ( UNTUK PEMERIKSAAN KACA )'),
(1445, '2', '03', '03', '03', '042', 'OPTOCAL TEKNIS GANGE ( PENGUKUR TEBAL DINDING )'),
(1446, '2', '03', '03', '03', '043', 'LIFTER CAPASITAS 500 KG'),
(1447, '2', '03', '03', '03', '044', 'TAXIMETER TESTER'),
(1448, '2', '03', '03', '03', '045', 'SPEDOMETER TESTER'),
(1449, '2', '03', '03', '03', '046', 'STANDARD GUAGE BLOCKS'),
(1450, '2', '03', '03', '03', '047', 'FINEST DIRECT READING INTERN MICROMETER OF VARIOS RANGE UP TO 10'),
(1451, '2', '03', '03', '03', '048', 'CONSTANT TEMPERATURE COMBINED BRIDGE THERMOSTAT'),
(1452, '2', '03', '03', '03', '049', 'TRANSPARAN PLASTIC RACK INSERT FOR 20 TEST TEST TUBES 75 X 17'),
(1453, '2', '03', '03', '03', '050', 'WATER BATH PLEXIGLASS CAPASITY 71'),
(1454, '2', '03', '03', '03', '051', 'TEST TUBE RACK STAINLESSTEL WITH 10 HOLES 18 MM DIA'),
(1455, '2', '03', '03', '03', '052', 'CALORIMETER THERMOMETER ACETO BESTMEN CERTIFICATE'),
(1456, '2', '03', '03', '03', '053', 'SIT OF GAUGE PRETITION LANDS BERGER THERMOMETER'),
(1457, '2', '03', '03', '03', '054', 'SET OF 14 HIGHT PRECISION AMERAL THERMOMETER'),
(1458, '2', '03', '03', '03', '055', 'ADDITION TUNER STOP WATCH'),
(1459, '2', '03', '03', '03', '056', '\"UNIVERSAL CLAMP'),
(1460, '2', '03', '03', '03', '057', '\"UNIVERSAL CLAMP'),
(1461, '2', '03', '03', '03', '058', 'VENIER CALIVER'),
(1462, '2', '03', '03', '03', '059', 'PROPILE PROYEKTOR TOYO SERIE'),
(1463, '2', '03', '03', '03', '060', 'TOOL MAKER MICROSCOPE MAGNIFICATION 30 X'),
(1464, '2', '03', '03', '03', '061', 'MICROSCOPE MULTIVIEW'),
(1465, '2', '03', '03', '03', '999', 'ALAT UKUR/PEMBANDING LAINNYA'),
(1466, '2', '03', '03', '04', '000', 'ALAT UKUR LAINNYA'),
(1467, '2', '03', '03', '04', '001', 'METER X - 27 DARI PLATINA TRIDIUM'),
(1468, '2', '03', '03', '04', '002', 'H - METER DARI BAJA NIKEL'),
(1469, '2', '03', '03', '04', '003', 'KOMPARATOR'),
(1470, '2', '03', '03', '04', '004', 'ALAT PENGUKUR GARIS TENGAH'),
(1471, '2', '03', '03', '04', '005', 'BAN UKUR'),
(1472, '2', '03', '03', '04', '006', 'DIAMETER TAPE'),
(1473, '2', '03', '03', '04', '007', 'UKURAN TINGGI ORANG'),
(1474, '2', '03', '03', '04', '008', 'SCHUIFMAAT ( UKURAN INGSUT )'),
(1475, '2', '03', '03', '04', '009', 'LIFTER STANDARD ( 1 LITER )'),
(1476, '2', '03', '03', '04', '010', 'BEJANA UKUR'),
(1477, '2', '03', '03', '04', '011', 'ALAT UKUR KADAR AIR (ALAT UKUR LAINNYA)'),
(1478, '2', '03', '03', '04', '012', 'ALAT UKUR PEMECAH KULIT GABAH'),
(1479, '2', '03', '03', '04', '013', 'RAIN GAUGE'),
(1480, '2', '03', '03', '04', '014', 'NEEDLE LIFT SENSOR'),
(1481, '2', '03', '03', '04', '999', 'ALAT UKUR LAINNYA'),
(1482, '2', '03', '03', '05', '000', 'ALAT TIMBANGAN/BIARA'),
(1483, '2', '03', '03', '05', '001', 'TIMBANGAN JEMBATAN CAPASITAS 10 TON'),
(1484, '2', '03', '03', '05', '002', 'TIMBANGAN MEJA CAPASITAS 10 KG'),
(1485, '2', '03', '03', '05', '003', 'TIMBANGAN MEJA CAPASITAS 5 KG'),
(1486, '2', '03', '03', '05', '004', 'TIMBANGAN BBI CAPASITAS 100 KG'),
(1487, '2', '03', '03', '05', '005', 'TIMBANGAN BBI CAPASITAS 25 KG'),
(1488, '2', '03', '03', '05', '006', 'TIMBANGAN BBI CAPASITAS 15 KG ( TIMBANGAN BAYI )'),
(1489, '2', '03', '03', '05', '007', 'TIMBANGAN BBI CAPASITAS 10 KG'),
(1490, '2', '03', '03', '05', '008', 'TIMBANGAN CEPAT CAPASITAS 10 KG'),
(1491, '2', '03', '03', '05', '009', 'TIMBANGAN CEPAT CAPASITAS 25 KG'),
(1492, '2', '03', '03', '05', '010', 'TIMBANGAN CEPAT CAPASITAS 200 KG'),
(1493, '2', '03', '03', '05', '011', 'TIMBANGAN PEGAS CAPASITAS 10 KG'),
(1494, '2', '03', '03', '05', '012', 'TIMBANGAN PEGAS CAPASITAS 50 KG (ALAT TIMBANGAN/BIARA)'),
(1495, '2', '03', '03', '05', '014', 'TIMBANGAN SURAT CAPASITAS 100 KG'),
(1496, '2', '03', '03', '05', '015', 'TIMBANGAN KWADRAN CAPASITAS 100 KG'),
(1497, '2', '03', '03', '05', '016', 'TIMBANGAN SENTISIMAL DACIN KUNINGAN'),
(1498, '2', '03', '03', '05', '017', 'TIMBANGAN GULA GAVEKA'),
(1499, '2', '03', '03', '05', '018', 'TIMBANGAN GANTUNG CAPASITAS 50 GRAM'),
(1500, '2', '03', '03', '05', '019', 'NERACA HALUS + LEMARI CAPASITAS 500 GRAM'),
(1501, '2', '03', '03', '05', '020', 'NERACA PARAMA E'),
(1502, '2', '03', '03', '05', '021', 'NERACA PARAMA D CAPASITAS 5 GRAM'),
(1503, '2', '03', '03', '05', '022', 'NERACA PERCISI ELEKTRONIK CAPASITAS 1 KG.'),
(1504, '2', '03', '03', '05', '023', 'NERACA PERCISI ( SINGLE PAN ) CAPASITAS 20 KG.'),
(1505, '2', '03', '03', '05', '024', 'NERACA PERCISI ( ELEKTRONIK VACUM ME )'),
(1506, '2', '03', '03', '05', '025', 'NERACA PERCISI 30 KG ( MICRO BALANCE )'),
(1507, '2', '03', '03', '05', '026', 'NERACA PERCISI CAPASITAS 50 GRAM'),
(1508, '2', '03', '03', '05', '027', 'NERACA PERCISI CAPASITAS 1 KG.'),
(1509, '2', '03', '03', '05', '028', 'NERACA TERA E'),
(1510, '2', '03', '03', '05', '029', 'NERACA TERA A CAPASITAS 75 KG.'),
(1511, '2', '03', '03', '05', '030', 'NERACA TERA B CAPASITAS 10 KG.'),
(1512, '2', '03', '03', '05', '031', 'NERACA TORSION BALANCE CAPASITAS 500 GRAM'),
(1513, '2', '03', '03', '05', '032', 'NERACA ANALISA CAPASITAS 1000 GRAM'),
(1514, '2', '03', '03', '05', '033', 'NERACA ANALISA CAPASITAS 20 KG'),
(1515, '2', '03', '03', '05', '034', 'NERACA CAPASITAS 1 KG.'),
(1516, '2', '03', '03', '05', '035', 'NERACA CAPASITAS 20 KG.'),
(1517, '2', '03', '03', '05', '036', 'MOISTER METER'),
(1518, '2', '03', '03', '05', '037', 'NERACA DENGAN DIGITAL DISPLAY'),
(1519, '2', '03', '03', '05', '999', 'ALAT TIMBANGAN/BIARA LAINNYA'),
(1520, '2', '03', '03', '06', '000', 'ANAK TIMBANGAN / BIARA'),
(1521, '2', '03', '03', '06', '001', 'KILOGRAM TEMBAGA NASIONAL PLATINA'),
(1522, '2', '03', '03', '06', '002', 'KILOGRAM TEMBAGA BENTUK TONG BERSADUR MAS MURNI 1 KG.'),
(1523, '2', '03', '03', '06', '003', 'KILOGRAM SEPUH MAS 1 KG. PAKAI TOMBOL'),
(1524, '2', '03', '03', '06', '004', 'KILOGRAM BAJA BERBENTUK TONG BERSADUR CROOM'),
(1525, '2', '03', '03', '06', '005', 'KILOGRAM DARI BAJA BERBENTUK SLINDER'),
(1526, '2', '03', '03', '06', '006', 'KILOGRAM KERJA STANDAR TK.II'),
(1527, '2', '03', '03', '06', '007', 'KILOGRAM STANDAR'),
(1528, '2', '03', '03', '06', '008', 'ANAK TIMBANGAN TEMBAGA KANTOR TK.III'),
(1529, '2', '03', '03', '06', '009', 'ANAK TIMBANGAN MILIGRAM'),
(1530, '2', '03', '03', '06', '010', 'ANAK TIMBANGAN MILIGRAM PLATINA'),
(1531, '2', '03', '03', '06', '011', 'ANAK TIMBANGAN MILIGRAM ALUMINIUM'),
(1532, '2', '03', '03', '06', '012', 'ANAK TIMBANGAN GRAM STANDAR 1 GRAM'),
(1533, '2', '03', '03', '06', '013', 'ANAK TIMBANGAN HALUS DARI 1.000 - 1 GRAM'),
(1534, '2', '03', '03', '06', '014', 'ANAK TIMBANGAN BIASA DARI 1.000 - 1 GRAM'),
(1535, '2', '03', '03', '06', '015', 'ANAK TIMBANGAN BIDUR'),
(1536, '2', '03', '03', '06', '016', 'ANAK TIMBANGAN DARI BESI'),
(1537, '2', '03', '03', '06', '017', 'ANAK TIMBANGAN KEPING ( MULUT KECIL )'),
(1538, '2', '03', '03', '06', '018', 'ANAK TIMBANGAN KEPING ( MULUT BESAR )'),
(1539, '2', '03', '03', '06', '999', 'ANAK TIMBANGAN / BIARA LAINNYA'),
(1540, '2', '03', '03', '07', '000', 'TAKARAN KERING'),
(1541, '2', '03', '03', '07', '001', 'TAKARAN KERING DARI 100 - 50 - 20 LITER'),
(1542, '2', '03', '03', '07', '002', '\"TAKARAN KERING DARI 10 S/D 0'),
(1543, '2', '03', '03', '07', '999', 'TAKARAN KERING LAINNYA'),
(1544, '2', '03', '03', '08', '000', 'TAKARAN BAHAN BANGUNAN'),
(1545, '2', '03', '03', '08', '001', 'TAKARAN BAHAN BANGUNAN 2 HL BERBENTUK TONG'),
(1546, '2', '03', '03', '08', '999', 'TAKARAN BAHAN BANGUNAN LAINNYA'),
(1547, '2', '03', '03', '09', '000', 'TAKARAN LAINNYA'),
(1548, '2', '03', '03', '09', '001', 'TAKARAN LATEX/GETAH SUSU'),
(1549, '2', '03', '03', '09', '002', '\"TAKARAN BUAH KOPI DARI 0'),
(1550, '2', '03', '03', '09', '003', 'TAKARAN KAPUK DARI KAYU 2 DAN 1 HL'),
(1551, '2', '03', '03', '09', '004', '\"TAKARAN MINYAK DARI BESI 0'),
(1552, '2', '03', '03', '09', '005', '\"TAKARAN GANDUM 0'),
(1553, '2', '03', '03', '09', '999', 'TAKARAN LAINNYA'),
(1554, '2', '03', '03', '99', '999', 'ALAT UKUR LAINNYA'),
(1555, '2', '04', '00', '00', '000', 'ALAT PERTANIAN'),
(1556, '2', '04', '01', '00', '000', 'ALAT PENGOLAHAN'),
(1557, '2', '04', '01', '01', '000', 'ALAT PENGOLAHAN TANAH DAN TANAMAN'),
(1558, '2', '04', '01', '01', '001', 'BAJAK KAYU'),
(1559, '2', '04', '01', '01', '002', 'BAJAK MUARA'),
(1560, '2', '04', '01', '01', '003', 'PACUL'),
(1561, '2', '04', '01', '01', '004', 'LINGGIS'),
(1562, '2', '04', '01', '01', '005', 'GARPU PACUL'),
(1563, '2', '04', '01', '01', '006', 'GARPU KAYU'),
(1564, '2', '04', '01', '01', '007', 'GARPU BESI'),
(1565, '2', '04', '01', '01', '008', 'TRACTOR FOUR WHEEL (DENGAN KELENGKAPANNYA)'),
(1566, '2', '04', '01', '01', '009', 'TRACTOR TANGAN DENGAN PERLENGKAPANNYA'),
(1567, '2', '04', '01', '01', '999', 'ALAT PENGOLAHAN TANAH DAN TANAMAN LAINNYA'),
(1568, '2', '04', '01', '02', '000', 'ALAT PEMELIHARAAN TANAMAN/IKAN/TERNAK'),
(1569, '2', '04', '01', '02', '001', 'KORED'),
(1570, '2', '04', '01', '02', '002', 'ARIT'),
(1571, '2', '04', '01', '02', '003', 'BABATAN'),
(1572, '2', '04', '01', '02', '004', 'PACUL DANGIR'),
(1573, '2', '04', '01', '02', '005', 'PENYEMPROT OTOMATIS (AUTOMATIC SPRAYER)'),
(1574, '2', '04', '01', '02', '006', 'PENYEMPROT MESIN (POWER SPRAYER)'),
(1575, '2', '04', '01', '02', '007', 'PENYEMPROT TANGAN (HAND SPRAYER)'),
(1576, '2', '04', '01', '02', '008', 'ALAT PENYIANG TANAMAN'),
(1577, '2', '04', '01', '02', '999', 'ALAT PEMELIHARAAN TANAMAN/IKAN/TERNAK LAINNYA'),
(1578, '2', '04', '01', '03', '000', 'ALAT PANEN'),
(1579, '2', '04', '01', '03', '001', 'ANI-ANI'),
(1580, '2', '04', '01', '03', '002', 'ALAT PERONTOKAN (THRESSER PEDAL)'),
(1581, '2', '04', '01', '03', '003', 'ALAT PERONTOKAN MESIN (POWER THRESSER)'),
(1582, '2', '04', '01', '03', '004', 'ALAT PEMIPIL JAGUNG'),
(1583, '2', '04', '01', '03', '005', 'ALAT PENGERING (DRYER)'),
(1584, '2', '04', '01', '03', '006', 'ALAT PENGUKUR KADAR AIR (MOISTURE TESTER)'),
(1585, '2', '04', '01', '03', '007', 'ALAT PENGGILING KOPI'),
(1586, '2', '04', '01', '03', '008', 'ALAT PENGOLAH TEPUNG'),
(1587, '2', '04', '01', '03', '009', 'ALAT BANTU UJI TUMBUH'),
(1588, '2', '04', '01', '03', '010', 'ALAT PENAMPI'),
(1589, '2', '04', '01', '03', '999', 'ALAT PANEN LAINNYA'),
(1590, '2', '04', '01', '04', '000', 'ALAT PENYIMPAN HASIL PERCOBAAN PERTANIAN'),
(1591, '2', '04', '01', '04', '001', 'COLD STORAGE (KAMAR PENDINGIN)'),
(1592, '2', '04', '01', '04', '002', 'SELO (KOTAK PENYIMPANAN) DENGAN PENGATUR TEMPERATUR'),
(1593, '2', '04', '01', '04', '003', 'RAK-RAK PENYIMPAN'),
(1594, '2', '04', '01', '04', '004', 'LEMARI PENYIMPAN'),
(1595, '2', '04', '01', '04', '999', 'ALAT PENYIMPAN HASIL PERCOBAAN PERTANIAN LAINNYA'),
(1596, '2', '04', '01', '05', '000', 'ALAT LABORATORIUM PERTANIAN'),
(1597, '2', '04', '01', '05', '001', 'ALAT PENGUKUR CURAH HUJAN'),
(1598, '2', '04', '01', '05', '002', 'ALAT PENGUKUR CAHAYA'),
(1599, '2', '04', '01', '05', '003', 'ALAT PENGUKUR INTENSITAS CAHAYA'),
(1600, '2', '04', '01', '05', '004', 'ALAT PENGUKUR TEMPERATUR'),
(1601, '2', '04', '01', '05', '005', 'ALAT PENGUKUR P.H. TANAH (SOIL TESTER)'),
(1602, '2', '04', '01', '05', '006', 'ALAT PENGAMBIL SAMPLE TANAH'),
(1603, '2', '04', '01', '05', '007', 'RICE'),
(1604, '2', '04', '01', '05', '008', 'GRINDDING MILL'),
(1605, '2', '04', '01', '05', '009', 'VOLUME TEST'),
(1606, '2', '04', '01', '05', '010', 'WEIGHT'),
(1607, '2', '04', '01', '05', '011', 'STRAW FACTURE'),
(1608, '2', '04', '01', '05', '012', 'FALLING NUMBER'),
(1609, '2', '04', '01', '05', '013', 'ELECTRODE PH METER'),
(1610, '2', '04', '01', '05', '014', 'ALAT PENURUN KADAR AIR MADU'),
(1611, '2', '04', '01', '05', '999', 'ALAT LABORATORIUM PERTANIAN LAINNYA (ALAT PENGOLAHAN PERTANIAN)'),
(1612, '2', '04', '01', '06', '000', 'ALAT PROSESING'),
(1613, '2', '04', '01', '06', '001', 'UNIT PENGADUK'),
(1614, '2', '04', '01', '06', '002', 'ALAT PENCABUT BULU AYAM'),
(1615, '2', '04', '01', '06', '003', 'ALAT PEMBUAT PELET/MAKANAN TERNAK'),
(1616, '2', '04', '01', '06', '004', 'ALAT PEMBUAT MOLASE BLOK');
INSERT INTO `tweb_aset` (`id_aset`, `golongan`, `bidang`, `kelompok`, `sub_kelompok`, `sub_sub_kelompok`, `nama`) VALUES
(1617, '2', '04', '01', '06', '005', 'MESIN TETAS'),
(1618, '2', '04', '01', '06', '006', 'MESIN PERAH SUSU'),
(1619, '2', '04', '01', '06', '007', 'MILK CAN'),
(1620, '2', '04', '01', '06', '008', 'PENGUPAS KULIT ARI KEDELAI'),
(1621, '2', '04', '01', '06', '009', 'PEMARUT SERAT SERBA GUNA'),
(1622, '2', '04', '01', '06', '010', 'PENYAWUT SINGKONG'),
(1623, '2', '04', '01', '06', '011', 'GILINGAN BERAS'),
(1624, '2', '04', '01', '06', '012', 'SALINA INJECTOR'),
(1625, '2', '04', '01', '06', '013', 'SCALLER MOTOR'),
(1626, '2', '04', '01', '06', '014', 'ULV CABINET'),
(1627, '2', '04', '01', '06', '015', 'TLC DRAYER'),
(1628, '2', '04', '01', '06', '016', 'MESIN PENCUCI ALAT (MIELE)'),
(1629, '2', '04', '01', '06', '017', 'HYDROLIC PIECES'),
(1630, '2', '04', '01', '06', '018', 'REAPER'),
(1631, '2', '04', '01', '06', '019', 'ELECTRIC DISK CUTTER'),
(1632, '2', '04', '01', '06', '020', 'RAGUM /CATOK'),
(1633, '2', '04', '01', '06', '021', 'DIESEL EGGANE'),
(1634, '2', '04', '01', '06', '022', 'ALAT PROSESING DAGING'),
(1635, '2', '04', '01', '06', '023', 'ALAT PROSESING TELUR'),
(1636, '2', '04', '01', '06', '024', 'ICE CREAM MAKER'),
(1637, '2', '04', '01', '06', '025', 'HAND SEPARATOR'),
(1638, '2', '04', '01', '06', '026', 'MESIN PENEPUNG BERAS'),
(1639, '2', '04', '01', '06', '027', 'ALAT PENGGILING JAGUNG'),
(1640, '2', '04', '01', '06', '028', 'MESIN PENGAYAK TEPUNG'),
(1641, '2', '04', '01', '06', '029', 'PENGOLAHAN PRODUK KERING'),
(1642, '2', '04', '01', '06', '030', 'PENYAWUT BESAR DAN KECIL'),
(1643, '2', '04', '01', '06', '031', 'PROCESSING MULTIGUNA'),
(1644, '2', '04', '01', '06', '032', 'PUMP FOR HPLC AND ACCESSORIES'),
(1645, '2', '04', '01', '06', '033', 'SAUSAGE FEELER MACHINE'),
(1646, '2', '04', '01', '06', '034', 'TWIN PAPER ROLLER BEARING'),
(1647, '2', '04', '01', '06', '035', 'SKINNING CRADLE'),
(1648, '2', '04', '01', '06', '036', 'HEAD RESTRAINER'),
(1649, '2', '04', '01', '06', '037', 'STUNING DEVICE'),
(1650, '2', '04', '01', '06', '038', 'PENYODOK KOTORAN'),
(1651, '2', '04', '01', '06', '039', 'PENGARAH KEPALA'),
(1652, '2', '04', '01', '06', '040', 'OFFAL WASH'),
(1653, '2', '04', '01', '06', '041', 'BEEF SPLITTER'),
(1654, '2', '04', '01', '06', '999', 'ALAT PROSESING LAINNYA'),
(1655, '2', '04', '01', '07', '000', 'ALAT PASCA PANEN'),
(1656, '2', '04', '01', '07', '001', 'ALAT PENGASAPAN'),
(1657, '2', '04', '01', '07', '002', 'ALAT PEMBEKUAN'),
(1658, '2', '04', '01', '07', '003', 'ALAT PENGGILING PADI'),
(1659, '2', '04', '01', '07', '004', 'ALAT PENCACAH HIJAUAN'),
(1660, '2', '04', '01', '07', '005', 'ALAT PEMECAH TAPIOKA'),
(1661, '2', '04', '01', '07', '999', 'ALAT PASCA PANEN LAINNYA'),
(1662, '2', '04', '01', '08', '000', 'ALAT PRODUKSI PERIKANAN'),
(1663, '2', '04', '01', '08', '001', 'PUKAT'),
(1664, '2', '04', '01', '08', '002', 'DOUBLE RIG SHRIMP TRAWL/PUKAT UDANG GANDA'),
(1665, '2', '04', '01', '08', '003', 'PAYANG ( TERMASUK LAMPARA )'),
(1666, '2', '04', '01', '08', '004', 'DANISH SEINE ( DOGOL )'),
(1667, '2', '04', '01', '08', '005', 'BEACH SEINE ( PUKAT PANTAI )'),
(1668, '2', '04', '01', '08', '006', 'DRIFT GILL NET ( JARING INSANG HANYUT )'),
(1669, '2', '04', '01', '08', '007', 'ENCIRCLING GILL NET ( JARING INSANG LINGKAR )'),
(1670, '2', '04', '01', '08', '008', 'SHRIMP GILL NET ( JARING KLITIK )'),
(1671, '2', '04', '01', '08', '009', 'SET GILL NET ( JARING INSANG TETAP )'),
(1672, '2', '04', '01', '08', '010', 'BOAT RAFT LIFT NET ( BAGAN PERAHU/RAKIT )'),
(1673, '2', '04', '01', '08', '011', 'BAGAN TANCAP BERIKUT KELONG'),
(1674, '2', '04', '01', '08', '012', 'SCOOP NET ( SEROK )'),
(1675, '2', '04', '01', '08', '013', 'JARING ANGKAT LAINNYA'),
(1676, '2', '04', '01', '08', '014', 'GUIDING BARRIER ( SEROK )'),
(1677, '2', '04', '01', '08', '015', 'STOW NET ( JERMAL TERMASUK TOGO )'),
(1678, '2', '04', '01', '08', '016', 'PORTABLE TRAPS ( BUBU )'),
(1679, '2', '04', '01', '08', '017', 'PERANGKAP LAINNYA'),
(1680, '2', '04', '01', '08', '018', 'TUNA LONG LINE ( RAWAI TUNA )'),
(1681, '2', '04', '01', '08', '019', 'SET LONG LINE ( RAWAI TETAP )'),
(1682, '2', '04', '01', '08', '020', 'SKIPJACK POLE AND LINES ( HUHATE )'),
(1683, '2', '04', '01', '08', '021', 'TROOL LINE ( PANCING TONDA )'),
(1684, '2', '04', '01', '08', '022', 'PANCING LAINNYA'),
(1685, '2', '04', '01', '08', '023', 'MUROAMI INC. MALLALUGIS'),
(1686, '2', '04', '01', '08', '024', 'JALA'),
(1687, '2', '04', '01', '08', '025', 'GARPU'),
(1688, '2', '04', '01', '08', '026', 'TOMBAK'),
(1689, '2', '04', '01', '08', '027', 'SEA WATER RESERVOIR'),
(1690, '2', '04', '01', '08', '028', 'BAK PEMELIHARAAN SEMENTARA'),
(1691, '2', '04', '01', '08', '029', 'BAK PENGENDAPAN'),
(1692, '2', '04', '01', '08', '030', 'KERAMBA ( JARING APUNG )'),
(1693, '2', '04', '01', '08', '031', 'JARING LINGKAR'),
(1694, '2', '04', '01', '08', '032', 'PUKAT TARIK BERKAPAL'),
(1695, '2', '04', '01', '08', '033', 'PUKAT HELA'),
(1696, '2', '04', '01', '08', '034', 'PUKAT DORONG'),
(1697, '2', '04', '01', '08', '035', 'PENGGARUK'),
(1698, '2', '04', '01', '08', '036', 'JARING ANGKAT MENETAP'),
(1699, '2', '04', '01', '08', '037', 'JARING ANGKAT TIDAK MENETAP'),
(1700, '2', '04', '01', '08', '038', 'ALAT YANG DIJATUHKAN'),
(1701, '2', '04', '01', '08', '039', 'ALAT PENJEPIT DAN MELUKAI'),
(1702, '2', '04', '01', '08', '999', 'ALAT PRODUKSI PERIKANAN LAINNYA'),
(1703, '2', '04', '01', '99', '000', 'ALAT PENGOLAHAN LAINNYA'),
(1704, '2', '04', '01', '99', '999', 'ALAT PENGOLAHAN LAINNYA'),
(1705, '2', '05', '00', '00', '000', 'ALAT KANTOR & RUMAH TANGGA'),
(1706, '2', '05', '01', '00', '000', 'ALAT KANTOR'),
(1707, '2', '05', '01', '01', '000', 'MESIN KETIK'),
(1708, '2', '05', '01', '01', '001', 'MESIN KETIK MANUAL PORTABLE (11-13 INCI)'),
(1709, '2', '05', '01', '01', '002', 'MESIN KETIK MANUAL STANDARD (14-16 INCI)'),
(1710, '2', '05', '01', '01', '003', 'MESIN KETIK MANUAL LANGEWAGON (18-27 INCI)'),
(1711, '2', '05', '01', '01', '004', 'MESIN KETIK LISTRIK'),
(1712, '2', '05', '01', '01', '005', 'MESIN KETIK LISTRIK POTABLE (11-13 INCI)'),
(1713, '2', '05', '01', '01', '006', 'MESIN KETIK LISTRIK STANDARD (14-16 INCI)'),
(1714, '2', '05', '01', '01', '007', 'MESIN KETIK LISTRIK LANGEWAGON (18-27 INCI)'),
(1715, '2', '05', '01', '01', '008', 'MESIN KETIK ELEKTRONIK/SELEKTRIK'),
(1716, '2', '05', '01', '01', '009', 'MESIN KETIK BRAILLE'),
(1717, '2', '05', '01', '01', '010', 'MESIN PHROMOSONS'),
(1718, '2', '05', '01', '01', '011', 'MESIN CETAK STEREO PIPER (BRAILLE)'),
(1719, '2', '05', '01', '01', '999', 'MESIN KETIK LAINNYA'),
(1720, '2', '05', '01', '02', '000', 'MESIN HITUNG/MESIN JUMLAH'),
(1721, '2', '05', '01', '02', '001', 'MESIN HITUNG MANUAL'),
(1722, '2', '05', '01', '02', '002', 'MESIN HITUNG LISTRIK'),
(1723, '2', '05', '01', '02', '003', 'MESIN HITUNG ELEKTRONIK/CALCULATOR'),
(1724, '2', '05', '01', '02', '004', 'MESIN KAS REGISTER'),
(1725, '2', '05', '01', '02', '005', 'ABAKUS (ALAT HITUNG)'),
(1726, '2', '05', '01', '02', '006', 'BLOKYCS (MESIN HITUNG BRAILLE)'),
(1727, '2', '05', '01', '02', '007', 'MESIN PENGHITUNG UANG'),
(1728, '2', '05', '01', '02', '008', 'MESIN PEMBUKUAN'),
(1729, '2', '05', '01', '02', '009', 'MESIN PENGHITUNG KERTAS/PITA CUKAI'),
(1730, '2', '05', '01', '02', '999', 'MESIN HITUNG/MESIN JUMLAH LAINNYA'),
(1731, '2', '05', '01', '03', '000', 'ALAT REPRODUKSI (PENGGANDAAN)'),
(1732, '2', '05', '01', '03', '001', 'MESIN STENSIL MANUAL FOLIO'),
(1733, '2', '05', '01', '03', '002', 'MESIN STENSIL MANUAL DOUBLE FOLIO'),
(1734, '2', '05', '01', '03', '003', 'MESIN STENSIL LISTRIK FOLIO'),
(1735, '2', '05', '01', '03', '004', 'MESIN STENSIL LISTRIK DOUBLE FOLIO'),
(1736, '2', '05', '01', '03', '005', 'MESIN STENSIL SPIRITUS MANUAL'),
(1737, '2', '05', '01', '03', '006', 'MESIN STENSIL SPIRITUS LISTRIK'),
(1738, '2', '05', '01', '03', '007', 'MESIN FOTOCOPY FOLIO'),
(1739, '2', '05', '01', '03', '008', 'MESIN FOTOCOPY DOUBLE FOLIO'),
(1740, '2', '05', '01', '03', '009', 'MESIN FOTOCOPY ELECTRONIC'),
(1741, '2', '05', '01', '03', '010', 'MESIN THERMOFORN'),
(1742, '2', '05', '01', '03', '011', 'MESIN FOTOCOPY LAINNYA'),
(1743, '2', '05', '01', '03', '012', 'RISOGRAF'),
(1744, '2', '05', '01', '03', '999', 'ALAT REPRODUKSI (PENGGANDAAN) LAINNYA'),
(1745, '2', '05', '01', '04', '000', 'ALAT PENYIMPAN PERLENGKAPAN KANTOR'),
(1746, '2', '05', '01', '04', '001', 'LEMARI BESI/METAL'),
(1747, '2', '05', '01', '04', '002', 'LEMARI KAYU'),
(1748, '2', '05', '01', '04', '003', 'RAK BESI'),
(1749, '2', '05', '01', '04', '004', 'RAK KAYU'),
(1750, '2', '05', '01', '04', '005', 'FILING CABINET BESI'),
(1751, '2', '05', '01', '04', '006', 'FILING CABINET KAYU'),
(1752, '2', '05', '01', '04', '007', 'BRANDKAS'),
(1753, '2', '05', '01', '04', '008', 'PETI UANG/CASH BOX/COIN BOX'),
(1754, '2', '05', '01', '04', '009', 'KARDEX BESI'),
(1755, '2', '05', '01', '04', '010', 'KARDEX KAYU'),
(1756, '2', '05', '01', '04', '011', 'ROTARY FILLING'),
(1757, '2', '05', '01', '04', '012', 'COMPACT ROLLING'),
(1758, '2', '05', '01', '04', '013', 'BUFFET'),
(1759, '2', '05', '01', '04', '014', 'MOBILE FILE'),
(1760, '2', '05', '01', '04', '015', 'LOCKER'),
(1761, '2', '05', '01', '04', '016', 'ROLL OPEK'),
(1762, '2', '05', '01', '04', '017', 'TEMPAT MENYIMPAN GAMBAR'),
(1763, '2', '05', '01', '04', '018', 'KONTAINER'),
(1764, '2', '05', '01', '04', '019', 'COIN BOX'),
(1765, '2', '05', '01', '04', '020', 'LEMARI DISPLAY'),
(1766, '2', '05', '01', '04', '021', 'WATER PROOF BOX'),
(1767, '2', '05', '01', '04', '022', 'FOLDING CONTAINER BOX'),
(1768, '2', '05', '01', '04', '023', 'BOX TRUCK'),
(1769, '2', '05', '01', '04', '024', 'LACI BOX'),
(1770, '2', '05', '01', '04', '025', 'LEMARI KATALOG'),
(1771, '2', '05', '01', '04', '999', 'ALAT PENYIMPAN PERLENGKAPAN KANTOR LAINNYA'),
(1772, '2', '05', '01', '05', '000', 'ALAT KANTOR LAINNYA'),
(1773, '2', '05', '01', '05', '001', 'TABUNG PEMADAM API'),
(1774, '2', '05', '01', '05', '002', 'HYDRANT'),
(1775, '2', '05', '01', '05', '003', 'SPRINKLER'),
(1776, '2', '05', '01', '05', '004', 'FIRE ALARM'),
(1777, '2', '05', '01', '05', '005', 'RAMBU-RAMBU'),
(1778, '2', '05', '01', '05', '006', 'NARKOTIK TEST'),
(1779, '2', '05', '01', '05', '007', 'CCTV - CAMERA CONTROL TELEVISION SYSTEM'),
(1780, '2', '05', '01', '05', '008', 'PAPAN VISUAL/PAPAN NAMA'),
(1781, '2', '05', '01', '05', '009', 'MOVITEX BOARD'),
(1782, '2', '05', '01', '05', '010', 'WHITE BOARD'),
(1783, '2', '05', '01', '05', '011', 'ALAT DETEKTOR UANG PALSU'),
(1784, '2', '05', '01', '05', '012', 'ALAT DETEKTOR BARANG TERLARANG/X RAY'),
(1785, '2', '05', '01', '05', '013', 'COPY BOARD/ELEKTRIC WHITE BOARD'),
(1786, '2', '05', '01', '05', '014', 'PETA'),
(1787, '2', '05', '01', '05', '015', 'ALAT PENGHANCUR KERTAS'),
(1788, '2', '05', '01', '05', '016', 'GLOBE'),
(1789, '2', '05', '01', '05', '017', 'MESIN ABSENSI'),
(1790, '2', '05', '01', '05', '018', 'DRY SEAL'),
(1791, '2', '05', '01', '05', '019', 'FERGULATOR'),
(1792, '2', '05', '01', '05', '020', 'CREAM POLISHER'),
(1793, '2', '05', '01', '05', '021', 'MESIN PERANGKO'),
(1794, '2', '05', '01', '05', '022', 'CHECK WRITER'),
(1795, '2', '05', '01', '05', '023', 'NUMERATOR'),
(1796, '2', '05', '01', '05', '024', 'ALAT PEMOTONG KERTAS'),
(1797, '2', '05', '01', '05', '025', 'HEADMACHINE BESAR'),
(1798, '2', '05', '01', '05', '026', 'PERFORATOR BESAR'),
(1799, '2', '05', '01', '05', '027', 'ALAT PENCETAK LABEL'),
(1800, '2', '05', '01', '05', '028', 'OVERHEAD PROJECTOR'),
(1801, '2', '05', '01', '05', '029', 'HAND METAL DETECTOR'),
(1802, '2', '05', '01', '05', '030', 'WALKMAN DETECTOR'),
(1803, '2', '05', '01', '05', '031', 'PANEL PAMERAN'),
(1804, '2', '05', '01', '05', '032', 'ALAT PENGAMAN / SINYAL'),
(1805, '2', '05', '01', '05', '033', 'BOARD MODULUX'),
(1806, '2', '05', '01', '05', '034', 'PORTO SAFE TRAVEL COSE'),
(1807, '2', '05', '01', '05', '035', 'DISK PRIME'),
(1808, '2', '05', '01', '05', '036', 'MEGASHOW'),
(1809, '2', '05', '01', '05', '037', 'WHITE BOARD ELECTRONIC'),
(1810, '2', '05', '01', '05', '038', 'LASER POINTER'),
(1811, '2', '05', '01', '05', '039', 'DISPLAY'),
(1812, '2', '05', '01', '05', '040', 'EXHAUSTER FORM'),
(1813, '2', '05', '01', '05', '041', 'RUBU MUJAYYAB'),
(1814, '2', '05', '01', '05', '042', 'ELECTRIC DUMPER'),
(1815, '2', '05', '01', '05', '043', 'MESIN TERAAN'),
(1816, '2', '05', '01', '05', '044', 'MESIN LAMINATING'),
(1817, '2', '05', '01', '05', '045', 'PENANGKAL PETIR'),
(1818, '2', '05', '01', '05', '046', 'STEMPEL TIMBUL/BULAT'),
(1819, '2', '05', '01', '05', '047', 'LAMPU-LAMPU KRISTAL'),
(1820, '2', '05', '01', '05', '048', 'LCD PROJECTOR/INFOCUS'),
(1821, '2', '05', '01', '05', '049', 'FLIP CHART'),
(1822, '2', '05', '01', '05', '050', 'BINDING MACHINE'),
(1823, '2', '05', '01', '05', '051', 'SOFTBOARD'),
(1824, '2', '05', '01', '05', '052', 'ALAT PEREKAM SUARA (VOICE PEN)'),
(1825, '2', '05', '01', '05', '053', 'ACCES CONTROL SYSTEM'),
(1826, '2', '05', '01', '05', '054', 'INTRUCTION DETECTOR'),
(1827, '2', '05', '01', '05', '055', 'MONITOR PANEL WITH MIMIC BOARD'),
(1828, '2', '05', '01', '05', '056', '\"PANIC BUTTON SYSTEM'),
(1829, '2', '05', '01', '05', '057', 'PINTU ELEKTRIK (YANG MEMAKAI AKSES)'),
(1830, '2', '05', '01', '05', '058', 'FOCUSING SCREEN/LAYAR LCD PROJECTOR'),
(1831, '2', '05', '01', '05', '059', 'ALAT DETEKTOR BARANG TERLARANG'),
(1832, '2', '05', '01', '05', '060', 'PROYECTOR SPIDER BRACKET'),
(1833, '2', '05', '01', '05', '061', 'PAPAN GAMBAR'),
(1834, '2', '05', '01', '05', '062', 'BEL'),
(1835, '2', '05', '01', '05', '063', 'ELECTRIC PRESSING MACHINE'),
(1836, '2', '05', '01', '05', '064', 'ENCAPSULATOR (JARASONIC WELDER)'),
(1837, '2', '05', '01', '05', '065', 'DEACIDIFICATOR UNIT (NON AQUAS)'),
(1838, '2', '05', '01', '05', '066', 'FULL AUTOMATIC LEAF CASTER'),
(1839, '2', '05', '01', '05', '067', 'CONSERVATION TOOLS'),
(1840, '2', '05', '01', '05', '068', 'BOARD STAN'),
(1841, '2', '05', '01', '05', '069', 'VACUM FREEZE DRY CHAMBER'),
(1842, '2', '05', '01', '05', '070', 'KOTAK SURAT'),
(1843, '2', '05', '01', '05', '071', 'GEMBOK'),
(1844, '2', '05', '01', '05', '072', 'COMPACT HAND PROJECTOR'),
(1845, '2', '05', '01', '05', '073', 'ALAT SIDIK JARI'),
(1846, '2', '05', '01', '05', '074', 'ALAT PENGHANCUR JARUM'),
(1847, '2', '05', '01', '05', '075', 'WALKTHROUGH/ PORTAL METAL DETECTOR'),
(1848, '2', '05', '01', '05', '076', 'HANDHELD TRACE DETECTOR'),
(1849, '2', '05', '01', '05', '077', 'ALAT DETEKSI PITA CUKAI PALSU/ VIDEO SPECTRAL COMPARATOR'),
(1850, '2', '05', '01', '05', '078', 'MESIN PACKING/ STARPPING MACHINE'),
(1851, '2', '05', '01', '05', '079', 'TELEVISION CONTROL OPERASIONAL LIFT'),
(1852, '2', '05', '01', '05', '080', 'MESIN ANTRIAN'),
(1853, '2', '05', '01', '05', '081', 'PAPAN PENGUMUMAN'),
(1854, '2', '05', '01', '05', '082', 'MESIN FOGGING'),
(1855, '2', '05', '01', '05', '083', 'TERALIS'),
(1856, '2', '05', '01', '05', '999', 'PERKAKAS KANTOR LAINNYA'),
(1857, '2', '05', '01', '99', '000', 'ALAT KANTOR LAINNYA'),
(1858, '2', '05', '01', '99', '999', 'ALAT KANTOR LAINNYA'),
(1859, '2', '05', '02', '00', '000', 'ALAT RUMAH TANGGA'),
(1860, '2', '05', '02', '01', '000', 'MEUBELAIR'),
(1861, '2', '05', '02', '01', '001', 'MEJA KERJA BESI/METAL'),
(1862, '2', '05', '02', '01', '002', 'MEJA KERJA KAYU'),
(1863, '2', '05', '02', '01', '003', 'KURSI BESI/METAL'),
(1864, '2', '05', '02', '01', '004', 'KURSI KAYU'),
(1865, '2', '05', '02', '01', '005', 'SICE'),
(1866, '2', '05', '02', '01', '006', 'BANGKU PANJANG BESI/METAL'),
(1867, '2', '05', '02', '01', '007', 'BANGKU PANJANG KAYU'),
(1868, '2', '05', '02', '01', '008', 'MEJA RAPAT'),
(1869, '2', '05', '02', '01', '009', 'MEJA KOMPUTER'),
(1870, '2', '05', '02', '01', '010', 'TEMPAT TIDUR BESI'),
(1871, '2', '05', '02', '01', '011', 'TEMPAT TIDUR KAYU'),
(1872, '2', '05', '02', '01', '012', 'MEJA KETIK'),
(1873, '2', '05', '02', '01', '013', 'MEJA TELEPON'),
(1874, '2', '05', '02', '01', '014', 'MEJA RESEPSIONIS'),
(1875, '2', '05', '02', '01', '015', 'MEJA MARMER'),
(1876, '2', '05', '02', '01', '016', 'KASUR/SPRING BED'),
(1877, '2', '05', '02', '01', '017', 'SKETSEL'),
(1878, '2', '05', '02', '01', '018', 'MEJA MAKAN BESI'),
(1879, '2', '05', '02', '01', '019', 'MEJA MAKAN KAYU'),
(1880, '2', '05', '02', '01', '020', 'KURSI FIBER GLAS/PLASTIK'),
(1881, '2', '05', '02', '01', '021', 'POT BUNGA'),
(1882, '2', '05', '02', '01', '022', 'PARTISI'),
(1883, '2', '05', '02', '01', '023', 'PUBLIK ASTARI (PEMBATAS ANTRIAN)'),
(1884, '2', '05', '02', '01', '024', 'RAK SEPATU ( ALMUNIUM )'),
(1885, '2', '05', '02', '01', '025', 'GANTUNGAN JAS'),
(1886, '2', '05', '02', '01', '026', 'NAKAS'),
(1887, '2', '05', '02', '01', '027', 'CUBIKAL'),
(1888, '2', '05', '02', '01', '028', 'WORKSTATION'),
(1889, '2', '05', '02', '01', '999', 'MEUBELAIR LAINNYA'),
(1890, '2', '05', '02', '02', '000', 'ALAT PENGUKUR WAKTU'),
(1891, '2', '05', '02', '02', '001', 'JAM MEKANIS'),
(1892, '2', '05', '02', '02', '002', 'JAM LISTRIK'),
(1893, '2', '05', '02', '02', '003', 'JAM ELEKTRONIK'),
(1894, '2', '05', '02', '02', '004', 'CONTROL CLOCK'),
(1895, '2', '05', '02', '02', '999', 'ALAT PENGUKUR WAKTU LAINNYA'),
(1896, '2', '05', '02', '03', '000', 'ALAT PEMBERSIH'),
(1897, '2', '05', '02', '03', '001', 'MESIN PENGHISAP DEBU/VACUUM CLEANER'),
(1898, '2', '05', '02', '03', '002', 'MESIN PEL/POLES'),
(1899, '2', '05', '02', '03', '003', 'MESIN PEMOTONG RUMPUT'),
(1900, '2', '05', '02', '03', '004', 'MESIN CUCI'),
(1901, '2', '05', '02', '03', '005', 'AIR CLEANER'),
(1902, '2', '05', '02', '03', '006', 'ALAT PEMBERSIH SALJU'),
(1903, '2', '05', '02', '03', '999', 'ALAT PEMBERSIH LAINNYA'),
(1904, '2', '05', '02', '04', '000', 'ALAT PENDINGIN'),
(1905, '2', '05', '02', '04', '001', 'LEMARI ES'),
(1906, '2', '05', '02', '04', '002', 'A.C. SENTRAL'),
(1907, '2', '05', '02', '04', '003', 'A.C. WINDOW'),
(1908, '2', '05', '02', '04', '004', 'A.C. SPLIT'),
(1909, '2', '05', '02', '04', '005', 'PORTABLE AIR CONDITIONER (ALAT PENDINGIN)'),
(1910, '2', '05', '02', '04', '006', 'KIPAS ANGIN'),
(1911, '2', '05', '02', '04', '007', 'EXHAUSE FAN'),
(1912, '2', '05', '02', '04', '008', 'COLD STORAGE (ALAT PENDINGIN)'),
(1913, '2', '05', '02', '04', '009', 'REACH IN FREZZER'),
(1914, '2', '05', '02', '04', '010', 'REACH IN CHILLER'),
(1915, '2', '05', '02', '04', '011', 'UP RIGHT CHILLER/FREZZER'),
(1916, '2', '05', '02', '04', '012', 'COLD ROOM FREZZER'),
(1917, '2', '05', '02', '04', '013', 'AIR CURTAIN'),
(1918, '2', '05', '02', '04', '014', 'AIR HANDLING UNIT'),
(1919, '2', '05', '02', '04', '999', 'ALAT PENDINGIN LAINNYA'),
(1920, '2', '05', '02', '05', '000', 'ALAT DAPUR'),
(1921, '2', '05', '02', '05', '001', 'KOMPOR LISTRIK (ALAT DAPUR)'),
(1922, '2', '05', '02', '05', '002', 'KOMPOR GAS (ALAT DAPUR)'),
(1923, '2', '05', '02', '05', '003', 'KOMPOR MINYAK'),
(1924, '2', '05', '02', '05', '004', 'TEKO LISTRIK'),
(1925, '2', '05', '02', '05', '005', 'RICE COOKER (ALAT DAPUR)'),
(1926, '2', '05', '02', '05', '006', 'OVEN LISTRIK'),
(1927, '2', '05', '02', '05', '007', 'RICE WARMER'),
(1928, '2', '05', '02', '05', '008', 'KITCHEN SET'),
(1929, '2', '05', '02', '05', '009', 'TABUNG GAS'),
(1930, '2', '05', '02', '05', '010', 'MESIN GILING BUMBU'),
(1931, '2', '05', '02', '05', '011', 'TRENG AIR/TANDON AIR'),
(1932, '2', '05', '02', '05', '012', 'MESIN PARUTAN KELAPA'),
(1933, '2', '05', '02', '05', '013', 'KOMPOR KOMPRESOR'),
(1934, '2', '05', '02', '05', '014', 'ALAT PEMANGGANG ROTI/SATE'),
(1935, '2', '05', '02', '05', '015', 'RAK PIRING ALUMUNIUM'),
(1936, '2', '05', '02', '05', '016', 'ALAT PENYIMPAN BERAS'),
(1937, '2', '05', '02', '05', '017', 'PANCI'),
(1938, '2', '05', '02', '05', '018', 'BLENDER'),
(1939, '2', '05', '02', '05', '019', 'MIXER'),
(1940, '2', '05', '02', '05', '020', 'OVEN GAS'),
(1941, '2', '05', '02', '05', '021', 'PRESTO COOKER'),
(1942, '2', '05', '02', '05', '022', 'WONDER PAN'),
(1943, '2', '05', '02', '05', '023', 'MESIN GILING DAGING'),
(1944, '2', '05', '02', '05', '024', 'HEATING SET'),
(1945, '2', '05', '02', '05', '025', 'THERMOS AIR'),
(1946, '2', '05', '02', '05', '999', 'ALAT DAPUR LAINNYA'),
(1947, '2', '05', '02', '06', '000', 'ALAT RUMAH TANGGA LAINNYA ( HOME USE )'),
(1948, '2', '05', '02', '06', '001', 'RADIO'),
(1949, '2', '05', '02', '06', '002', 'TELEVISI'),
(1950, '2', '05', '02', '06', '003', 'VIDEO CASSETTE'),
(1951, '2', '05', '02', '06', '004', 'TAPE RECORDER (ALAT RUMAH TANGGA LAINNYA ( HOME USE ))'),
(1952, '2', '05', '02', '06', '005', 'AMPLIFIER'),
(1953, '2', '05', '02', '06', '006', 'EQUALIZER'),
(1954, '2', '05', '02', '06', '007', 'LOUDSPEAKER'),
(1955, '2', '05', '02', '06', '008', 'SOUND SYSTEM'),
(1956, '2', '05', '02', '06', '009', 'COMPACT DISC'),
(1957, '2', '05', '02', '06', '010', 'LASER DISC'),
(1958, '2', '05', '02', '06', '011', 'KARAOKE'),
(1959, '2', '05', '02', '06', '012', 'WIRELESS'),
(1960, '2', '05', '02', '06', '013', 'MEGAPHONE'),
(1961, '2', '05', '02', '06', '014', 'MICROPHONE'),
(1962, '2', '05', '02', '06', '015', 'MICROPHONE TABLE STAND'),
(1963, '2', '05', '02', '06', '016', 'MIC CONFERENCE'),
(1964, '2', '05', '02', '06', '017', 'UNIT POWER SUPPLY'),
(1965, '2', '05', '02', '06', '018', 'STEP UP/DOWN (ALAT RUMAH TANGGA LAINNYA ( HOME USE ))'),
(1966, '2', '05', '02', '06', '019', 'STABILISATOR'),
(1967, '2', '05', '02', '06', '020', 'CAMERA VIDEO'),
(1968, '2', '05', '02', '06', '021', 'TUSTEL'),
(1969, '2', '05', '02', '06', '022', 'MESIN JAHIT'),
(1970, '2', '05', '02', '06', '023', 'TIMBANGAN ORANG'),
(1971, '2', '05', '02', '06', '024', 'TIMBANGAN BARANG'),
(1972, '2', '05', '02', '06', '025', 'ALAT HIASAN'),
(1973, '2', '05', '02', '06', '026', 'LAMBANG GARUDA PANCASILA'),
(1974, '2', '05', '02', '06', '027', 'GAMBAR PRESIDEN/WAKIL PRESIDEN'),
(1975, '2', '05', '02', '06', '028', 'LAMBANG KORPRI/DHARMA WANITA'),
(1976, '2', '05', '02', '06', '029', 'AQUARIUM (ALAT RUMAH TANGGA LAINNYA ( HOME USE ))'),
(1977, '2', '05', '02', '06', '030', 'TIANG BENDERA'),
(1978, '2', '05', '02', '06', '031', 'PATAKA'),
(1979, '2', '05', '02', '06', '032', 'SETERIKA'),
(1980, '2', '05', '02', '06', '033', 'WATER FILTER'),
(1981, '2', '05', '02', '06', '034', 'TANGGA ALUMINIUM'),
(1982, '2', '05', '02', '06', '035', 'KACA HIAS'),
(1983, '2', '05', '02', '06', '036', 'DISPENSER'),
(1984, '2', '05', '02', '06', '037', 'MIMBAR/PODIUM'),
(1985, '2', '05', '02', '06', '038', 'GUCCI'),
(1986, '2', '05', '02', '06', '039', 'TANGGA HIDROLIK'),
(1987, '2', '05', '02', '06', '040', 'PALU SIDANG'),
(1988, '2', '05', '02', '06', '041', 'MESIN PENGERING PAKAIAN'),
(1989, '2', '05', '02', '06', '042', 'LAMBANG INSTANSI'),
(1990, '2', '05', '02', '06', '043', 'LONCENG/GENTA'),
(1991, '2', '05', '02', '06', '044', 'MESIN PEMOTONG KERAMIK'),
(1992, '2', '05', '02', '06', '045', 'COFFEE MAKER'),
(1993, '2', '05', '02', '06', '046', 'HANDY CAM'),
(1994, '2', '05', '02', '06', '047', 'MESIN OBRAS'),
(1995, '2', '05', '02', '06', '048', 'MESIN POTONG KAIN'),
(1996, '2', '05', '02', '06', '049', 'MESIN PELUBANG KANCING'),
(1997, '2', '05', '02', '06', '050', 'MEJA POTONG'),
(1998, '2', '05', '02', '06', '051', 'RADER'),
(1999, '2', '05', '02', '06', '052', 'MANEQUIN (BONEKA)'),
(2000, '2', '05', '02', '06', '053', 'PINSET (PISAU LOBANG KANCING)'),
(2001, '2', '05', '02', '06', '054', 'MINI COMPO'),
(2002, '2', '05', '02', '06', '055', 'HEATER (ALAT RUMAH TANGGA LAINNYA ( HOME USE ))'),
(2003, '2', '05', '02', '06', '056', 'KARPET'),
(2004, '2', '05', '02', '06', '057', 'VERTIKAL BLIND'),
(2005, '2', '05', '02', '06', '058', 'GORDYIN/KRAY'),
(2006, '2', '05', '02', '06', '059', 'KABEL ROLL'),
(2007, '2', '05', '02', '06', '060', 'ASBAK TINGGI'),
(2008, '2', '05', '02', '06', '061', 'KESET KAKI'),
(2009, '2', '05', '02', '06', '062', 'SUN SCREEN'),
(2010, '2', '05', '02', '06', '063', 'ALAT PEMANAS RUANGAN'),
(2011, '2', '05', '02', '06', '064', 'LEMARI PLASTIK'),
(2012, '2', '05', '02', '06', '065', 'MESIN PENGERING TANGAN'),
(2013, '2', '05', '02', '06', '066', 'PANGGUNG'),
(2014, '2', '05', '02', '06', '067', 'MESIN PEDDING'),
(2015, '2', '05', '02', '06', '068', 'DVD PLAYER'),
(2016, '2', '05', '02', '06', '069', 'LAMPU BELAJAR'),
(2017, '2', '05', '02', '06', '070', 'TANGGA'),
(2018, '2', '05', '02', '06', '071', 'KABEL'),
(2019, '2', '05', '02', '06', '072', 'LAMPU'),
(2020, '2', '05', '02', '06', '073', 'JEMURAN'),
(2021, '2', '05', '02', '06', '074', 'PATUNG PERAGA PAKAIAN'),
(2022, '2', '05', '02', '06', '075', 'GENDOLA'),
(2023, '2', '05', '02', '06', '076', 'GUNTING RUMPUT NON MESIN'),
(2024, '2', '05', '02', '06', '077', 'BENDERA NEGARA'),
(2025, '2', '05', '02', '06', '078', 'BINGKAI FOTO'),
(2026, '2', '05', '02', '06', '079', 'ALAT PANGKAS RAMBUT LISTRIK'),
(2027, '2', '05', '02', '06', '080', 'BRACKET STANDING PERALATAN'),
(2028, '2', '05', '02', '06', '081', 'TANGKI AIR'),
(2029, '2', '05', '02', '06', '082', 'HOME THEATER'),
(2030, '2', '05', '02', '06', '999', 'ALAT RUMAH TANGGA LAINNYA ( HOME USE )'),
(2031, '2', '05', '02', '99', '000', 'ALAT RUMAH TANGGA LAINNYA'),
(2032, '2', '05', '02', '99', '999', 'ALAT RUMAH TANGGA LAINNYA'),
(2033, '2', '06', '00', '00', '000', '\"ALAT STUDIO'),
(2034, '2', '06', '01', '00', '000', 'ALAT STUDIO'),
(2035, '2', '06', '01', '01', '000', 'PERALATAN STUDIO AUDIO'),
(2036, '2', '06', '01', '01', '001', 'AUDIO MIXING CONSOLE'),
(2037, '2', '06', '01', '01', '002', 'AUDIO MIXING PORTABLE'),
(2038, '2', '06', '01', '01', '003', 'AUDIO MIXING STATIONER'),
(2039, '2', '06', '01', '01', '004', 'AUDIO ATTENUATOR'),
(2040, '2', '06', '01', '01', '005', 'AUDIO AMPLIFIER'),
(2041, '2', '06', '01', '01', '006', 'AUDIO ERASE UNIT'),
(2042, '2', '06', '01', '01', '007', 'AUDIO VIDEO SELECTOR (PERALATAN STUDIO AUDIO)'),
(2043, '2', '06', '01', '01', '008', 'AUDIO MONITOR ACTIVE'),
(2044, '2', '06', '01', '01', '009', 'AUDIO MONITOR PASSIVE'),
(2045, '2', '06', '01', '01', '010', 'AUDIO REVERBERATION'),
(2046, '2', '06', '01', '01', '011', 'AUDIO PATCH PANEL'),
(2047, '2', '06', '01', '01', '012', 'AUDIO DISTRIBUTION'),
(2048, '2', '06', '01', '01', '013', 'AUDIO TONE GENERATOR'),
(2049, '2', '06', '01', '01', '014', 'AUDIO CATRIDGE RECORDER'),
(2050, '2', '06', '01', '01', '015', 'AUDIO LOGGING RECORDER'),
(2051, '2', '06', '01', '01', '016', 'COMPACT DISC PLAYER'),
(2052, '2', '06', '01', '01', '017', 'CASSETTE DUPLICATOR'),
(2053, '2', '06', '01', '01', '018', 'DISC RECORD PLAYER'),
(2054, '2', '06', '01', '01', '019', 'MULTITRACK RECORDER'),
(2055, '2', '06', '01', '01', '020', 'REEL TAPE DUPLICATOR'),
(2056, '2', '06', '01', '01', '021', 'COMPACT DISC JUKE BOX SYSTEM'),
(2057, '2', '06', '01', '01', '022', 'TELEPHONE HYBRID'),
(2058, '2', '06', '01', '01', '023', 'AUDIO PHONE IN'),
(2059, '2', '06', '01', '01', '024', 'PROFANITY DELAY SYSTEM'),
(2060, '2', '06', '01', '01', '025', 'AUDIO VISUAL'),
(2061, '2', '06', '01', '01', '026', 'AUDIO FILTER'),
(2062, '2', '06', '01', '01', '027', 'AUDIO LIMITER'),
(2063, '2', '06', '01', '01', '028', 'AUDIO COMPRESSOR'),
(2064, '2', '06', '01', '01', '029', 'TURN TABLE'),
(2065, '2', '06', '01', '01', '030', 'TALK BACK UNIT'),
(2066, '2', '06', '01', '01', '031', 'INTERCOM UNIT'),
(2067, '2', '06', '01', '01', '032', 'BUZZER'),
(2068, '2', '06', '01', '01', '033', 'SET STUDIO LIGHT SIGNAL'),
(2069, '2', '06', '01', '01', '034', 'DOLBY NOISE REDUCTION'),
(2070, '2', '06', '01', '01', '035', 'MODULATION MONITOR SPEAKER KABARET'),
(2071, '2', '06', '01', '01', '036', 'MICROPHONE/WIRELESS MIC'),
(2072, '2', '06', '01', '01', '037', 'MICROPHONE/BOOM STAND'),
(2073, '2', '06', '01', '01', '038', 'MICROPHONE CONNECTOR BOX'),
(2074, '2', '06', '01', '01', '039', 'LIGHT SIGNAL'),
(2075, '2', '06', '01', '01', '040', 'POWER SUPPLY MICROPHONE'),
(2076, '2', '06', '01', '01', '041', 'PROFESSIONAL SOUND SYSTEM'),
(2077, '2', '06', '01', '01', '042', 'AUDIO MASTER CONTROL UNIT'),
(2078, '2', '06', '01', '01', '043', 'TIME INDETIFICATION UNIT'),
(2079, '2', '06', '01', '01', '044', 'AUDIO ANNOUNCER DESK'),
(2080, '2', '06', '01', '01', '045', 'MASTER CLOCK (PERALATAN STUDIO AUDIO)'),
(2081, '2', '06', '01', '01', '046', 'SLAVE CLOCK (PERALATAN STUDIO AUDIO)'),
(2082, '2', '06', '01', '01', '047', 'AUDIO COMMAND DESK'),
(2083, '2', '06', '01', '01', '048', 'UNINTERRUPTIBLE POWER SUPPLY (UPS)'),
(2084, '2', '06', '01', '01', '049', 'MASTER CONTROL DESK'),
(2085, '2', '06', '01', '01', '050', 'HEAD COMPENSATOR'),
(2086, '2', '06', '01', '01', '051', 'AUTOMATIC VOLTAGE REGULATOR (AVR)'),
(2087, '2', '06', '01', '01', '053', 'HUM/CABLE CONPENSATOR'),
(2088, '2', '06', '01', '01', '054', 'EDITING & DUBBING SYSTEM'),
(2089, '2', '06', '01', '01', '055', 'ANALOG DELAY (PERALATAN STUDIO AUDIO)'),
(2090, '2', '06', '01', '01', '056', 'BATTERY CHARGER (PERALATAN STUDIO AUDIO)'),
(2091, '2', '06', '01', '01', '057', 'BLANK PANEL'),
(2092, '2', '06', '01', '01', '058', 'CONTROL UNIT HF'),
(2093, '2', '06', '01', '01', '059', 'DELAY UNIT'),
(2094, '2', '06', '01', '01', '060', 'POWER AMPLIFIER'),
(2095, '2', '06', '01', '01', '061', 'PAGING MIC'),
(2096, '2', '06', '01', '01', '062', 'COMPACT MONITOR PANEL FOR STEREO'),
(2097, '2', '06', '01', '01', '063', 'PISTOL GRIP'),
(2098, '2', '06', '01', '01', '064', 'MOUNTING BREAKEN'),
(2099, '2', '06', '01', '01', '065', 'CHAIRMAN/AUDIO CONFERENCE'),
(2100, '2', '06', '01', '01', '066', 'TIME SWITCHING'),
(2101, '2', '06', '01', '01', '067', 'TERMINAL BOARD'),
(2102, '2', '06', '01', '01', '068', 'ENCODER/DECODER'),
(2103, '2', '06', '01', '01', '069', 'WIND SHIELD'),
(2104, '2', '06', '01', '01', '070', 'RECEIVER HF/LF'),
(2105, '2', '06', '01', '01', '071', 'RECEIVER VHF/FM'),
(2106, '2', '06', '01', '01', '072', 'AUDIO TAPE REEL RECORDER'),
(2107, '2', '06', '01', '01', '073', 'AUDIO CASSETTE RECORDER'),
(2108, '2', '06', '01', '01', '074', 'COMPACT DISC RECORDER'),
(2109, '2', '06', '01', '01', '075', 'DIGITAL AUDIO STORAGE SYSTEM'),
(2110, '2', '06', '01', '01', '076', 'DIGITAL AUDIO TAPERECORDER'),
(2111, '2', '06', '01', '01', '077', 'BLITZZER'),
(2112, '2', '06', '01', '01', '078', 'AUDIO MAXIMIZER'),
(2113, '2', '06', '01', '01', '079', 'MICROPHONE CABLE'),
(2114, '2', '06', '01', '01', '080', 'SIGNAL INSTRUMENT SWITCER'),
(2115, '2', '06', '01', '01', '081', 'CELLING MOUNT BRACKET'),
(2116, '2', '06', '01', '01', '082', 'INTERFACEBOARD'),
(2117, '2', '06', '01', '01', '083', 'VIDEO PRESENTER'),
(2118, '2', '06', '01', '01', '084', 'MULTISCAN PROYECTOR'),
(2119, '2', '06', '01', '01', '085', 'CABLE'),
(2120, '2', '06', '01', '01', '086', '\"SCANNER COIR'),
(2121, '2', '06', '01', '01', '087', 'KOMP. INTERFACE BOAR'),
(2122, '2', '06', '01', '01', '088', 'VOICE RECORDER'),
(2123, '2', '06', '01', '01', '089', 'AM/FM MEASUREMENT'),
(2124, '2', '06', '01', '01', '090', 'SIGNAL ON AIR'),
(2125, '2', '06', '01', '01', '091', 'DIGITAL LED RUNNING TEXT'),
(2126, '2', '06', '01', '01', '092', 'ANALOG/DIGITAL RECEIVER'),
(2127, '2', '06', '01', '01', '093', 'DIGITAL KEYBOARD TECHNICS'),
(2128, '2', '06', '01', '01', '094', 'EXPLORIST 600'),
(2129, '2', '06', '01', '01', '999', 'PERALATAN STUDIO AUDIO LAINNYA'),
(2130, '2', '06', '01', '02', '000', 'PERALATAN STUDIO VIDEO DAN FILM'),
(2131, '2', '06', '01', '02', '001', 'ASSIGNMENT SWITCHER'),
(2132, '2', '06', '01', '02', '002', 'OFF AIR TV MONITOR'),
(2133, '2', '06', '01', '02', '003', 'CAMERA ELECTRONIC'),
(2134, '2', '06', '01', '02', '004', 'PULSE GENERATOR (PERALATAN STUDIO VIDEO DAN FILM)'),
(2135, '2', '06', '01', '02', '005', 'PULSE DISTRIBUTION AMPLIFIER'),
(2136, '2', '06', '01', '02', '006', 'PULSE SWITCHER'),
(2137, '2', '06', '01', '02', '007', 'PULSE DELAY LINE'),
(2138, '2', '06', '01', '02', '008', 'CHARACTER GENERATOR (PERALATAN STUDIO VIDEO DAN FILM)'),
(2139, '2', '06', '01', '02', '009', 'CAPTION GENERATOR'),
(2140, '2', '06', '01', '02', '010', 'TELECINE'),
(2141, '2', '06', '01', '02', '011', 'VIDEO DISTRIBUTION AMPLIFIER'),
(2142, '2', '06', '01', '02', '012', 'VIDEO MONITOR'),
(2143, '2', '06', '01', '02', '013', 'VIDEO TAPE RECORDER PORTABLE'),
(2144, '2', '06', '01', '02', '014', 'VIDEO TAPE RECORDER STATIONER'),
(2145, '2', '06', '01', '02', '015', 'VIDEO MIXER'),
(2146, '2', '06', '01', '02', '016', 'VIDEO SWITCHER'),
(2147, '2', '06', '01', '02', '017', 'VIDEO EQUALIZER AMPLIFIER'),
(2148, '2', '06', '01', '02', '018', 'VIDEO COLOR BAR GENERATOR'),
(2149, '2', '06', '01', '02', '019', 'VIDEO CROSS BAR SWITCH'),
(2150, '2', '06', '01', '02', '020', 'VIDEO TEST SIGNAL GENERATOR'),
(2151, '2', '06', '01', '02', '021', 'VIDEO CORRECTOR'),
(2152, '2', '06', '01', '02', '022', 'VIDEO CAPTION ADDER'),
(2153, '2', '06', '01', '02', '023', 'VIDEO HUM COMPENSATOR'),
(2154, '2', '06', '01', '02', '024', 'VIDEO PROCESSOR'),
(2155, '2', '06', '01', '02', '025', 'VIDEO STATION ID GENERATOR'),
(2156, '2', '06', '01', '02', '026', 'VIDEO PATCH PANEL'),
(2157, '2', '06', '01', '02', '027', 'VIDEO DELAY UNIT'),
(2158, '2', '06', '01', '02', '028', 'VIDEO PROCESSING AMPLIFIER'),
(2159, '2', '06', '01', '02', '029', 'VIDEO EQUALIZER'),
(2160, '2', '06', '01', '02', '030', 'VIDEO TAPE EVALUATOR'),
(2161, '2', '06', '01', '02', '031', 'VIDEO EFFECT GENERATOR'),
(2162, '2', '06', '01', '02', '032', 'VITS INSERTER GENERATOR'),
(2163, '2', '06', '01', '02', '033', 'CAMERA WALL BOX'),
(2164, '2', '06', '01', '02', '034', 'TELEPROMPTER'),
(2165, '2', '06', '01', '02', '035', 'TIME BASE CORRECTOR'),
(2166, '2', '06', '01', '02', '036', 'GUN SMOKE'),
(2167, '2', '06', '01', '02', '037', 'AUTOMATIC EDITING CONTROL (PERALATAN STUDIO VIDEO DAN FILM)'),
(2168, '2', '06', '01', '02', '038', 'POWER SUPPLY (PERALATAN STUDIO VIDEO DAN FILM)'),
(2169, '2', '06', '01', '02', '039', 'EDITING ELECTRONIC'),
(2170, '2', '06', '01', '02', '040', 'RECTIFIER UNIT'),
(2171, '2', '06', '01', '02', '041', 'REMOTE CONTROL UNIT'),
(2172, '2', '06', '01', '02', '042', 'RAK PERALATAN'),
(2173, '2', '06', '01', '02', '043', 'STABILIZING AMPLIFIER'),
(2174, '2', '06', '01', '02', '044', 'DIGITAL VIDEO EFFECT'),
(2175, '2', '06', '01', '02', '045', 'TRIPOD CAMERA'),
(2176, '2', '06', '01', '02', '046', 'DIMMER'),
(2177, '2', '06', '01', '02', '047', 'CHILLER'),
(2178, '2', '06', '01', '02', '048', 'SLAVE CLOCK (PERALATAN STUDIO VIDEO DAN FILM)'),
(2179, '2', '06', '01', '02', '049', 'MASTER CLOCK (PERALATAN STUDIO VIDEO DAN FILM)'),
(2180, '2', '06', '01', '02', '050', 'TELEDYNE'),
(2181, '2', '06', '01', '02', '051', 'FLYING SPOT SCANNER'),
(2182, '2', '06', '01', '02', '052', 'SYNCHRONIZING PULSE GENERATOR'),
(2183, '2', '06', '01', '02', '053', 'DC CONVERTER'),
(2184, '2', '06', '01', '02', '054', 'BLACK BURST GENERATOR'),
(2185, '2', '06', '01', '02', '055', 'LIGHTING STAND TRIPOD'),
(2186, '2', '06', '01', '02', '056', 'FILM PROJECTOR'),
(2187, '2', '06', '01', '02', '057', 'SLIDE PROJECTOR'),
(2188, '2', '06', '01', '02', '058', 'COMMAND DESK'),
(2189, '2', '06', '01', '02', '059', 'ANNOUNCER DESK'),
(2190, '2', '06', '01', '02', '060', 'CAMERA FILM'),
(2191, '2', '06', '01', '02', '061', 'LENSA KAMERA'),
(2192, '2', '06', '01', '02', '062', 'FILM MAGAZINE'),
(2193, '2', '06', '01', '02', '063', 'CLAPER'),
(2194, '2', '06', '01', '02', '064', 'CHANGING BAG'),
(2195, '2', '06', '01', '02', '065', 'CONDITIONER'),
(2196, '2', '06', '01', '02', '066', 'COLOUR FILM ANALYZER'),
(2197, '2', '06', '01', '02', '068', 'FILM SOUND RECORDER'),
(2198, '2', '06', '01', '02', '069', 'TELE RECORDER'),
(2199, '2', '06', '01', '02', '070', 'CAMERA VIEW FINDER'),
(2200, '2', '06', '01', '02', '071', 'SERVO ZOOM LENS'),
(2201, '2', '06', '01', '02', '072', 'CAMERA ADAPTOR'),
(2202, '2', '06', '01', '02', '073', 'PHOTO PROCESSING SET'),
(2203, '2', '06', '01', '02', '074', 'MICRO FILM'),
(2204, '2', '06', '01', '02', '075', 'MIXER PVC'),
(2205, '2', '06', '01', '02', '076', 'UNIT REPLENIESER TANK'),
(2206, '2', '06', '01', '02', '077', 'HORIZONTAL MOTORIZED FILM REWINDER'),
(2207, '2', '06', '01', '02', '078', 'VERTICAL MOTORIZED FILM REWINDER'),
(2208, '2', '06', '01', '02', '079', 'MANUAL FILM REWINDER'),
(2209, '2', '06', '01', '02', '080', 'MESIN PROSESING FILM NEGATIF'),
(2210, '2', '06', '01', '02', '081', 'MESIN PROSESING FILM POSITIF'),
(2211, '2', '06', '01', '02', '082', 'MESIN PROSESING FILM WARNA NEGATIF (ECN)'),
(2212, '2', '06', '01', '02', '083', 'MESIN PROSESING FILM WARNA POSITIF (ECP)'),
(2213, '2', '06', '01', '02', '084', 'MESIN FILM COLOR ANALYZER'),
(2214, '2', '06', '01', '02', '085', 'ANALITICAL BALANCE (PERALATAN STUDIO VIDEO DAN FILM)'),
(2215, '2', '06', '01', '02', '086', 'ALAT PEMANAS PROSESING ( WATER HEATER )'),
(2216, '2', '06', '01', '02', '087', 'STAPLER FILM'),
(2217, '2', '06', '01', '02', '088', 'MAGNETIC STIP'),
(2218, '2', '06', '01', '02', '089', 'SPLITZER TAPE'),
(2219, '2', '06', '01', '02', '090', 'MEJA EDITING FILM'),
(2220, '2', '06', '01', '02', '091', 'DIGITAL TBC'),
(2221, '2', '06', '01', '02', '092', 'TITANIUM TANK SINGLE SHAFT'),
(2222, '2', '06', '01', '02', '093', 'TEMPERATUR CONTROL C/W'),
(2223, '2', '06', '01', '02', '094', 'GEAR BOX SUN ASSY'),
(2224, '2', '06', '01', '02', '095', 'TACHO GENERATOR FOR DRIVE MOTOR RACHING'),
(2225, '2', '06', '01', '02', '096', 'CIRCULATION SYSTEM COMPLET'),
(2226, '2', '06', '01', '02', '097', 'CHILLER WATER COMPLET'),
(2227, '2', '06', '01', '02', '098', 'VIDEO AUDIO JACK PANEL'),
(2228, '2', '06', '01', '02', '099', 'AUTOMATIC EMERGENCY LIGHT'),
(2229, '2', '06', '01', '02', '100', 'FILM CHAIN MULTIPLIER'),
(2230, '2', '06', '01', '02', '101', 'PHOTO TUSTEL'),
(2231, '2', '06', '01', '02', '102', 'PHOTO TUSTEL POLAROID'),
(2232, '2', '06', '01', '02', '103', 'BETACAM RECORDER/PLAYER'),
(2233, '2', '06', '01', '02', '104', 'SLIDE RAIL'),
(2234, '2', '06', '01', '02', '105', 'WEAPON & METAL DETECTOR ( CHECK GATE )'),
(2235, '2', '06', '01', '02', '107', 'LAYAR FILM/PROJECTOR'),
(2236, '2', '06', '01', '02', '108', 'CAMERA TUNE SIMULATOR'),
(2237, '2', '06', '01', '02', '109', 'DRY SPLITZER FILM'),
(2238, '2', '06', '01', '02', '110', 'VIDEO TONE CLEANER'),
(2239, '2', '06', '01', '02', '111', 'MINI VIEWER'),
(2240, '2', '06', '01', '02', '112', 'PUSH BUTTON CONTROL PANEL'),
(2241, '2', '06', '01', '02', '113', 'RAK TERMINAL VENCING'),
(2242, '2', '06', '01', '02', '114', 'STANDARD TRUE SIGNAL/MASTER RACK'),
(2243, '2', '06', '01', '02', '115', 'MOTOR DRIVER'),
(2244, '2', '06', '01', '02', '116', 'ANALOG DELAY (PERALATAN STUDIO VIDEO DAN FILM)'),
(2245, '2', '06', '01', '02', '117', 'STANDARD POINT ANIMATION'),
(2246, '2', '06', '01', '02', '118', 'HEAD SET'),
(2247, '2', '06', '01', '02', '119', 'CHARACTER EFFECT INTERFACE'),
(2248, '2', '06', '01', '02', '120', 'LIGHTING HEAD BODY'),
(2249, '2', '06', '01', '02', '121', 'LIGHTING MECHANIC'),
(2250, '2', '06', '01', '02', '122', 'ALOS 321 FICHE READER'),
(2251, '2', '06', '01', '02', '123', 'ALOS 321 ALOS READER'),
(2252, '2', '06', '01', '02', '124', 'INSERTER JACKET FILMNES MODEL FRF-160 & 3500'),
(2253, '2', '06', '01', '02', '125', '\"DIASO PRINTER'),
(2254, '2', '06', '01', '02', '126', 'DIASO PROCESSOR 404 DAN 404 D'),
(2255, '2', '06', '01', '02', '127', 'CAMERA UNDER WATER'),
(2256, '2', '06', '01', '02', '128', 'CAMERA DIGITAL'),
(2257, '2', '06', '01', '02', '129', 'TAS KAMERA'),
(2258, '2', '06', '01', '02', '130', 'LAMPU BLITZ KAMERA'),
(2259, '2', '06', '01', '02', '131', 'LENSA FILTER'),
(2260, '2', '06', '01', '02', '132', 'VIDEO CONFERENCE'),
(2261, '2', '06', '01', '02', '133', 'TURBO IDDR (INTELLIGENT DIGITAL DISK RECORDER)'),
(2262, '2', '06', '01', '02', '134', 'VIDEO ROUTER'),
(2263, '2', '06', '01', '02', '135', 'LCD MONITOR'),
(2264, '2', '06', '01', '02', '136', 'SDI RASTERISER'),
(2265, '2', '06', '01', '02', '137', 'AUDIO MONITORING UNIT'),
(2266, '2', '06', '01', '02', '138', 'FRAME SYNCHRONIZER'),
(2267, '2', '06', '01', '02', '139', 'AUDIO TRANSCODER'),
(2268, '2', '06', '01', '02', '140', 'AUDIO CONVERTER'),
(2269, '2', '06', '01', '02', '141', 'AUTOMATION MAIN'),
(2270, '2', '06', '01', '02', '142', 'RECORDING WORKSTATION'),
(2271, '2', '06', '01', '02', '143', 'EDITOR WORKSTATION'),
(2272, '2', '06', '01', '02', '144', 'ON AIR RECORDING'),
(2273, '2', '06', '01', '02', '145', 'CONNECTORS'),
(2274, '2', '06', '01', '02', '146', 'PATCH CORD'),
(2275, '2', '06', '01', '02', '147', 'AUDIO EMBEDDER'),
(2276, '2', '06', '01', '02', '148', 'VTR RECORDER'),
(2277, '2', '06', '01', '02', '149', 'ANALOG VIDEO ROUTER'),
(2278, '2', '06', '01', '02', '150', 'BROADBAND AMLIFIER'),
(2279, '2', '06', '01', '02', '151', 'SPLITTER'),
(2280, '2', '06', '01', '02', '152', 'RF CABLE'),
(2281, '2', '06', '01', '02', '153', 'F CONNECTOR'),
(2282, '2', '06', '01', '02', '154', 'TV CONNECTOR'),
(2283, '2', '06', '01', '02', '155', 'THERMO BIND MACHINE'),
(2284, '2', '06', '01', '02', '156', 'KAMERA STILE'),
(2285, '2', '06', '01', '02', '157', 'MINI DV'),
(2286, '2', '06', '01', '02', '158', 'MONOPOD'),
(2287, '2', '06', '01', '02', '159', 'CLIPP ON'),
(2288, '2', '06', '01', '02', '160', 'COMPUTER EDITING'),
(2289, '2', '06', '01', '02', '161', 'CUT EDITING'),
(2290, '2', '06', '01', '02', '162', 'DUPLICATOR VCD'),
(2291, '2', '06', '01', '02', '163', 'DUPLICATOR DVD'),
(2292, '2', '06', '01', '02', '164', 'VIDEO SPLITTER'),
(2293, '2', '06', '01', '02', '165', 'CAMERA CONFERENCE'),
(2294, '2', '06', '01', '02', '999', 'PERALATAN STUDIO VIDEO DAN FILM LAINNYA'),
(2295, '2', '06', '01', '03', '000', 'PERALATAN STUDIO GAMBAR'),
(2296, '2', '06', '01', '03', '001', 'MEJA GAMBAR'),
(2297, '2', '06', '01', '03', '002', 'LICHDRUCK APPARAAT'),
(2298, '2', '06', '01', '03', '003', 'SABLON SET'),
(2299, '2', '06', '01', '03', '004', 'ALAT TULIS GAMBAR'),
(2300, '2', '06', '01', '03', '005', 'BUSUR GAMBAR'),
(2301, '2', '06', '01', '03', '006', 'JANGKA GAMBAR'),
(2302, '2', '06', '01', '03', '999', 'PERALATAN STUDIO GAMBAR LAINNYA'),
(2303, '2', '06', '01', '04', '000', 'PERALATAN CETAK'),
(2304, '2', '06', '01', '04', '001', 'MEJA MEMBUAT KLISE'),
(2305, '2', '06', '01', '04', '002', 'MEJA CETAK TANGAN'),
(2306, '2', '06', '01', '04', '003', 'MESIN CETAK LISTRIK SHEET'),
(2307, '2', '06', '01', '04', '004', 'MESIN CETAK LISTRIK ROLL'),
(2308, '2', '06', '01', '04', '005', 'MESIN CETAK ELEKTRONIK'),
(2309, '2', '06', '01', '04', '006', 'MESIN CETAK'),
(2310, '2', '06', '01', '04', '007', 'MESIN CETAK OFFSET SHEET'),
(2311, '2', '06', '01', '04', '008', 'MESIN CETAK OFFSET ROLL'),
(2312, '2', '06', '01', '04', '009', 'MESIN CETAK OFFSET MINI'),
(2313, '2', '06', '01', '04', '010', 'MESIN PEMOTONG BIASA'),
(2314, '2', '06', '01', '04', '011', 'MESIN PEMOTONG BIASA TIGA PISAU'),
(2315, '2', '06', '01', '04', '012', 'MESIN JILID BUNDAR'),
(2316, '2', '06', '01', '04', '013', 'MESIN JILID BESAR'),
(2317, '2', '06', '01', '04', '014', 'MESIN JILID'),
(2318, '2', '06', '01', '04', '015', 'MESIN LIPAT'),
(2319, '2', '06', '01', '04', '016', 'MESIN PEMBUAT HURUF'),
(2320, '2', '06', '01', '04', '017', 'MESIN PENYUSUN HURUF BIASA'),
(2321, '2', '06', '01', '04', '018', 'MESIN PENYUSUN HURUF FOTO (FOTO TYPE SETTING)'),
(2322, '2', '06', '01', '04', '019', 'MESIN PELUBANG (PERALATAN CETAK)'),
(2323, '2', '06', '01', '04', '020', 'MESIN PROOF'),
(2324, '2', '06', '01', '04', '021', 'CAMERA VERTICAL'),
(2325, '2', '06', '01', '04', '022', 'MESIN PRES'),
(2326, '2', '06', '01', '04', '023', 'MESIN JAHIT KAWAT'),
(2327, '2', '06', '01', '04', '024', 'MESIN JAHIT BENANG'),
(2328, '2', '06', '01', '04', '025', 'MESIN PILUNG'),
(2329, '2', '06', '01', '04', '026', 'MESIN GARIS'),
(2330, '2', '06', '01', '04', '027', 'MESIN PEREKAM STENSIL FOLIO'),
(2331, '2', '06', '01', '04', '028', 'MESIN PEREKAM STENSIL DOUBLE FOLIO'),
(2332, '2', '06', '01', '04', '029', 'MESIN PLATE MAKER FOLIO'),
(2333, '2', '06', '01', '04', '030', 'MESIN PLATE MAKER DOUBLE FOLIO'),
(2334, '2', '06', '01', '04', '031', 'MESIN POTONG'),
(2335, '2', '06', '01', '04', '032', 'MESIN HANDPRESS'),
(2336, '2', '06', '01', '04', '033', 'MESIN STAHD'),
(2337, '2', '06', '01', '04', '034', 'MESIN KERTAS'),
(2338, '2', '06', '01', '04', '035', 'KACIP POTONG SUDUT'),
(2339, '2', '06', '01', '04', '036', 'ALAT PEMBUAT VORMSTAND'),
(2340, '2', '06', '01', '04', '037', 'MESIN PASET'),
(2341, '2', '06', '01', '04', '038', 'MESIN PRASISE KLISE'),
(2342, '2', '06', '01', '04', '039', 'MESIN PEMBOLONG FILM SETENGAH PLANO'),
(2343, '2', '06', '01', '04', '040', 'MESIN CETAK MAS'),
(2344, '2', '06', '01', '04', '041', 'MESIN CETAK STEREO TYPER'),
(2345, '2', '06', '01', '04', '042', 'MESIN CETAK BRAILLE'),
(2346, '2', '06', '01', '04', '043', 'MESIN FONDS'),
(2347, '2', '06', '01', '04', '044', 'MESIN FOLDING'),
(2348, '2', '06', '01', '04', '045', 'MESIN BARCODE'),
(2349, '2', '06', '01', '04', '046', 'MESIN PROFESIONAL VELOBINDER'),
(2350, '2', '06', '01', '04', '047', 'MESIN CACAH'),
(2351, '2', '06', '01', '04', '048', 'IMAGE SETTER'),
(2352, '2', '06', '01', '04', '049', 'MESIN SPARASI'),
(2353, '2', '06', '01', '04', '050', 'CAMERA HORIZONTAL'),
(2354, '2', '06', '01', '04', '051', 'ALAT COVER CREASING'),
(2355, '2', '06', '01', '04', '052', 'MESIN PEMBUAT ID CARD'),
(2356, '2', '06', '01', '04', '999', 'PERALATAN CETAK LAINNYA'),
(2357, '2', '06', '01', '05', '000', 'PERALATAN STUDIO PEMETAAN/PERALATAN UKUR TANAH'),
(2358, '2', '06', '01', '05', '001', 'AUTOGRAPH UNIT'),
(2359, '2', '06', '01', '05', '002', 'AVIOGRAPH PLUS PLOTING TABLE'),
(2360, '2', '06', '01', '05', '003', 'PLANITOP'),
(2361, '2', '06', '01', '05', '004', 'POINT TRANTER DEVICE'),
(2362, '2', '06', '01', '05', '005', 'TRESTIRIAL CAMERA'),
(2363, '2', '06', '01', '05', '006', 'SLOHED TEMLET'),
(2364, '2', '06', '01', '05', '007', 'SKETCH MASTER'),
(2365, '2', '06', '01', '05', '008', 'RECTIFIER (PERALATAN STUDIO PEMETAAN/PERALATAN UKUR TANAH)'),
(2366, '2', '06', '01', '05', '009', 'OPTICAL PANTOGRAPH'),
(2367, '2', '06', '01', '05', '010', 'CONTACT PRINTER'),
(2368, '2', '06', '01', '05', '011', 'PENGERING PHOTO'),
(2369, '2', '06', '01', '05', '012', 'VACUM FRAME'),
(2370, '2', '06', '01', '05', '013', 'COORDINATOGRAPH'),
(2371, '2', '06', '01', '05', '014', 'PEMOTONG FILM'),
(2372, '2', '06', '01', '05', '015', 'STREOSCOPE TANAH'),
(2373, '2', '06', '01', '05', '016', 'WATERPAS'),
(2374, '2', '06', '01', '05', '017', 'THEODOLITE (PERALATAN STUDIO PEMETAAN/PERALATAN UKUR TANAH)'),
(2375, '2', '06', '01', '05', '018', 'DISTOMAT'),
(2376, '2', '06', '01', '05', '019', 'B.T.M'),
(2377, '2', '06', '01', '05', '020', 'LEVEL'),
(2378, '2', '06', '01', '05', '021', 'JALON'),
(2379, '2', '06', '01', '05', '022', 'RAMBU/BAK UKUR'),
(2380, '2', '06', '01', '05', '023', 'KOMPAS GEOLOGI'),
(2381, '2', '06', '01', '05', '024', 'CLINOMETER'),
(2382, '2', '06', '01', '05', '025', 'ALTIMETER (PERALATAN STUDIO PEMETAAN/PERALATAN UKUR TANAH)'),
(2383, '2', '06', '01', '05', '026', 'HOLIOMETER'),
(2384, '2', '06', '01', '05', '027', 'TELESCOPE (PERALATAN STUDIO PEMETAAN/PERALATAN UKUR TANAH)'),
(2385, '2', '06', '01', '05', '028', 'PASSER DOSS'),
(2386, '2', '06', '01', '05', '029', 'CURVERMETER'),
(2387, '2', '06', '01', '05', '030', 'ROLLMETER'),
(2388, '2', '06', '01', '05', '031', 'MEET BAND'),
(2389, '2', '06', '01', '05', '032', 'BUSUR DERAJAT'),
(2390, '2', '06', '01', '05', '033', 'CHRONOMETER (PERALATAN STUDIO PEMETAAN/PERALATAN UKUR TANAH)'),
(2391, '2', '06', '01', '05', '034', 'GAWANG LOKASI'),
(2392, '2', '06', '01', '05', '035', 'KOMPAS (PERALATAN STUDIO PEMETAAN/PERALATAN UKUR TANAH)'),
(2393, '2', '06', '01', '05', '036', 'SEXTANT'),
(2394, '2', '06', '01', '05', '037', 'TEROPONG/KEKER'),
(2395, '2', '06', '01', '05', '038', 'GPS RECEIVER'),
(2396, '2', '06', '01', '05', '039', 'GROUND PARETRATING RADAR'),
(2397, '2', '06', '01', '05', '040', 'TEKEN SCHAAL/JANGKA TUSUK'),
(2398, '2', '06', '01', '05', '041', 'PANTOGRAPH'),
(2399, '2', '06', '01', '05', '042', 'PLANI METER'),
(2400, '2', '06', '01', '05', '043', 'PRISMA ROELAK'),
(2401, '2', '06', '01', '05', '044', 'PRISMA METER'),
(2402, '2', '06', '01', '05', '045', 'PRISMA UKUR'),
(2403, '2', '06', '01', '05', '046', 'RUITER PLAAT'),
(2404, '2', '06', '01', '05', '047', 'KAMERA UDARA'),
(2405, '2', '06', '01', '05', '048', 'STEREOPLOTTER'),
(2406, '2', '06', '01', '05', '049', 'PLANICOMP'),
(2407, '2', '06', '01', '05', '050', 'MEJA SINAR'),
(2408, '2', '06', '01', '05', '051', 'GRAVER'),
(2409, '2', '06', '01', '05', '052', 'PEN HOLDER'),
(2410, '2', '06', '01', '05', '999', 'PERALATAN STUDIO PEMETAAN/PERALATAN UKUR TANAH LAINNYA'),
(2411, '2', '06', '01', '99', '000', 'ALAT STUDIO LAINNYA'),
(2412, '2', '06', '01', '99', '999', 'ALAT STUDIO LAINNYA'),
(2413, '2', '06', '02', '00', '000', 'ALAT KOMUNIKASI'),
(2414, '2', '06', '02', '01', '000', 'ALAT KOMUNIKASI TELEPHONE'),
(2415, '2', '06', '02', '01', '001', 'TELEPHONE (PABX)'),
(2416, '2', '06', '02', '01', '002', 'INTERMEDIATE TELEPHONE/KEY TELEPHONE'),
(2417, '2', '06', '02', '01', '003', 'PESAWAT TELEPHONE'),
(2418, '2', '06', '02', '01', '004', 'TELEPHONE MOBILE'),
(2419, '2', '06', '02', '01', '005', 'PAGER'),
(2420, '2', '06', '02', '01', '006', 'HANDY TALKY (HT)'),
(2421, '2', '06', '02', '01', '007', 'TELEX'),
(2422, '2', '06', '02', '01', '008', 'SELECTIVE COLLING'),
(2423, '2', '06', '02', '01', '009', 'PERALATAN SPECH PLAS'),
(2424, '2', '06', '02', '01', '010', 'FACSIMILE'),
(2425, '2', '06', '02', '01', '011', 'BIDDING PIT'),
(2426, '2', '06', '02', '01', '012', 'LOCAL BATTERY TELEPHONE'),
(2427, '2', '06', '02', '01', '013', 'SENHUB FIXED'),
(2428, '2', '06', '02', '01', '014', 'SENHUB MOBILE'),
(2429, '2', '06', '02', '01', '015', 'TELEPON LAPANGAN'),
(2430, '2', '06', '02', '01', '016', 'SENTRAL TELEPON LAPANGAN'),
(2431, '2', '06', '02', '01', '017', 'TELEPON SATELIT'),
(2432, '2', '06', '02', '01', '018', 'KOM DATA'),
(2433, '2', '06', '02', '01', '019', 'PDA'),
(2434, '2', '06', '02', '01', '020', 'TELEPON DIGITAL'),
(2435, '2', '06', '02', '01', '021', 'TELEPON ANALOG'),
(2436, '2', '06', '02', '01', '999', 'ALAT KOMUNIKASI TELEPHONE LAINNYA'),
(2437, '2', '06', '02', '02', '000', 'ALAT KOMUNIKASI RADIO SSB'),
(2438, '2', '06', '02', '02', '001', 'UNIT TRANCEIVER SSB PORTABLE'),
(2439, '2', '06', '02', '02', '002', 'UNIT TRANCEIVER SSB TRANSPORTABLE'),
(2440, '2', '06', '02', '02', '003', 'UNIT TRANCEIVER SSB STATIONERY'),
(2441, '2', '06', '02', '02', '999', 'ALAT KOMUNIKASI RADIO SSB LAINNYA'),
(2442, '2', '06', '02', '03', '000', 'ALAT KOMUNIKASI RADIO HF/FM'),
(2443, '2', '06', '02', '03', '001', 'UNIT TRANCEIVER HF PORTABLE'),
(2444, '2', '06', '02', '03', '002', 'UNIT TRANCEIVER HF TRANSPORTABLE'),
(2445, '2', '06', '02', '03', '003', 'UNIT TRANCEIVER HF STATIONERY'),
(2446, '2', '06', '02', '03', '004', 'UNIT TRANCEIVER FM'),
(2447, '2', '06', '02', '03', '999', 'ALAT KOMUNIKASI RADIO HF/FM LAINNYA'),
(2448, '2', '06', '02', '04', '000', 'ALAT KOMUNIKASI RADIO VHF'),
(2449, '2', '06', '02', '04', '001', 'UNIT TRANCEIVER VHF PORTABLE'),
(2450, '2', '06', '02', '04', '002', 'UNIT TRANCEIVER VHF TRANSPORTABLE'),
(2451, '2', '06', '02', '04', '003', 'UNIT TRANCEIVER VHF STATIONARY'),
(2452, '2', '06', '02', '04', '999', 'ALAT KOMUNIKASI RADIO VHF LAINNYA'),
(2453, '2', '06', '02', '05', '000', 'ALAT KOMUNIKASI RADIO UHF'),
(2454, '2', '06', '02', '05', '001', 'UNIT TRANCEIVER UHF PORTABLE'),
(2455, '2', '06', '02', '05', '002', 'UNIT TRANCEIVER UHF TRANSPORTABLE'),
(2456, '2', '06', '02', '05', '003', 'UNIT TRANCEIVER UHF STATIONARY'),
(2457, '2', '06', '02', '05', '999', 'ALAT KOMUNIKASI RADIO UHF LAINNYA'),
(2458, '2', '06', '02', '06', '000', 'ALAT KOMUNIKASI SOSIAL'),
(2459, '2', '06', '02', '06', '001', 'PUBLIK ADDRESS (LAPANGAN)'),
(2460, '2', '06', '02', '06', '002', 'WIRELESS AMPLIFIER'),
(2461, '2', '06', '02', '06', '003', 'SLIDE PROJECTOR (LAPANGAN)');
INSERT INTO `tweb_aset` (`id_aset`, `golongan`, `bidang`, `kelompok`, `sub_kelompok`, `sub_sub_kelompok`, `nama`) VALUES
(2462, '2', '06', '02', '06', '004', 'MULTIPLEX SYSTEM'),
(2463, '2', '06', '02', '06', '005', 'FREQUENCY SYSTHESIZER UNIT'),
(2464, '2', '06', '02', '06', '006', 'PATCHING BOARD'),
(2465, '2', '06', '02', '06', '999', 'ALAT KOMUNIKASI SOSIAL LAINNYA'),
(2466, '2', '06', '02', '07', '000', 'ALAT-ALAT SANDI'),
(2467, '2', '06', '02', '07', '001', 'MORSE KEYER'),
(2468, '2', '06', '02', '07', '002', 'AUTOMATIC DEORSE KEYER'),
(2469, '2', '06', '02', '07', '003', 'ALAT SEMBOYAN'),
(2470, '2', '06', '02', '07', '004', 'MESIN SANDI DAN KELENGKAPANNYA'),
(2471, '2', '06', '02', '07', '005', 'FINGER PRINTER TIME AND ATTANDANCE ACCES CONTROL SYSTEM'),
(2472, '2', '06', '02', '07', '006', 'MESIN SANDI TEKS'),
(2473, '2', '06', '02', '07', '007', 'MESIN SANDI SUARA'),
(2474, '2', '06', '02', '07', '008', 'MESIN SANDI DATA'),
(2475, '2', '06', '02', '07', '009', 'MESIN SANDI BERBASIS SOFTWARE'),
(2476, '2', '06', '02', '07', '010', 'MESIN SANDI BERBASIS HARDWARE'),
(2477, '2', '06', '02', '07', '011', 'MESIN SANDI BERBASIS SOFTWARE DAN HARDWARE'),
(2478, '2', '06', '02', '07', '012', 'ALAT PEMBANGKIT KUNCI'),
(2479, '2', '06', '02', '07', '013', 'ALAT PENDISTRIBUSI KUNCI'),
(2480, '2', '06', '02', '07', '014', 'CRYPTHOPONE'),
(2481, '2', '06', '02', '07', '015', 'CRYTOFAX'),
(2482, '2', '06', '02', '07', '016', 'SERVER ENCRIPTION'),
(2483, '2', '06', '02', '07', '017', 'HANDPHONE ENCRIPTION'),
(2484, '2', '06', '02', '07', '018', 'GSM JAMMER'),
(2485, '2', '06', '02', '07', '019', 'CDMA JAMMER'),
(2486, '2', '06', '02', '07', '999', 'ALAT-ALAT SANDI LAINNYA'),
(2487, '2', '06', '02', '08', '000', 'ALAT KOMUNIKASI KHUSUS'),
(2488, '2', '06', '02', '08', '001', 'ALAT DF RADIO SSB'),
(2489, '2', '06', '02', '08', '002', 'SUPER BROOM'),
(2490, '2', '06', '02', '08', '003', 'ALAT DF RADIO HF/FM'),
(2491, '2', '06', '02', '08', '004', 'SCANLOCK PLUS CEBERUS'),
(2492, '2', '06', '02', '08', '005', 'ALAT DF RADIO VHF'),
(2493, '2', '06', '02', '08', '006', 'STELATH DIGITAL REPEATER'),
(2494, '2', '06', '02', '08', '007', 'ALAT DF RADIO UHF'),
(2495, '2', '06', '02', '08', '008', 'TRANKING'),
(2496, '2', '06', '02', '08', '009', 'TELEPON TAPING'),
(2497, '2', '06', '02', '08', '010', 'STELATH'),
(2498, '2', '06', '02', '08', '011', 'VISATELIT'),
(2499, '2', '06', '02', '08', '012', 'MAINFRAME (ALAT KOMUNIKASI KHUSUS)'),
(2500, '2', '06', '02', '08', '013', 'SAFE LIGHT FILTER'),
(2501, '2', '06', '02', '08', '014', 'ANTI SADAP TELEPON (SCANBLER)'),
(2502, '2', '06', '02', '08', '015', 'BILLINF SYSTEM'),
(2503, '2', '06', '02', '08', '016', 'ROOM MONITORING MC06'),
(2504, '2', '06', '02', '08', '017', 'WATCH TRANSMITER'),
(2505, '2', '06', '02', '08', '018', 'ASHTRAY'),
(2506, '2', '06', '02', '08', '019', 'NON DIRECTION BEACON (NDB)'),
(2507, '2', '06', '02', '08', '020', 'RADIO LINK'),
(2508, '2', '06', '02', '08', '021', 'LOCALIZER'),
(2509, '2', '06', '02', '08', '022', 'GLADE PATH'),
(2510, '2', '06', '02', '08', '023', 'MIDLE MARKER'),
(2511, '2', '06', '02', '08', '024', 'RADIO COMMUNICATION MATCHING SWITCH (RCMS)'),
(2512, '2', '06', '02', '08', '025', 'DIRECTION VERY OMNI RANGE (DVOR)'),
(2513, '2', '06', '02', '08', '026', 'INTEGRATED GROUND CAOMMUNICATION SYSTEM (SGRS)'),
(2514, '2', '06', '02', '08', '027', 'SWITCHING GROUND RECEIVER SYSTEM (SGRS)'),
(2515, '2', '06', '02', '08', '028', 'ALAT RX RADIO SSB'),
(2516, '2', '06', '02', '08', '029', 'ALAT RX RADIO HF/FM'),
(2517, '2', '06', '02', '08', '030', 'ALAT RX RADIO VHF'),
(2518, '2', '06', '02', '08', '031', 'ALAT RX RADIO UHF'),
(2519, '2', '06', '02', '08', '032', 'ALAT JAMMING RADIO SSB'),
(2520, '2', '06', '02', '08', '033', 'ALAT JAMMING RADIO HF/FM'),
(2521, '2', '06', '02', '08', '034', 'ALAT JAMMING RADIO VHF'),
(2522, '2', '06', '02', '08', '035', 'ALAT JAMMING RADIO UHF'),
(2523, '2', '06', '02', '08', '036', 'ALAT SPEKTRUM FREK MONITOR SSB'),
(2524, '2', '06', '02', '08', '037', 'ALAT SPEKTRUM FREK MONITOR HF/FM'),
(2525, '2', '06', '02', '08', '038', 'ALAT SPEKTRUM FREK MONITOR VHF'),
(2526, '2', '06', '02', '08', '039', 'ALAT SPEKTRUM FREK MONITOR UHF'),
(2527, '2', '06', '02', '08', '040', 'ALAT TRAFFIC ANALYSIS'),
(2528, '2', '06', '02', '08', '041', 'ALAT COUNTERSURVEILLANCE'),
(2529, '2', '06', '02', '08', '042', 'ALAT SURVEILLANCE'),
(2530, '2', '06', '02', '08', '043', 'ALAT JAMMING FREKUENSI'),
(2531, '2', '06', '02', '08', '044', 'ALAT PENGENDALI PANCARAN GELOMBANG ELEKTROMAGNETIK (TEMPEST)'),
(2532, '2', '06', '02', '08', '999', 'ALAT KOMUNIKASI KHUSUS LAINNYA'),
(2533, '2', '06', '02', '09', '000', 'ALAT KOMUNIKASI DIGITAL DAN KONVENSIONAL'),
(2534, '2', '06', '02', '09', '001', 'SYSTEM CONTROL NODE MULTI SITE SYSTEM'),
(2535, '2', '06', '02', '09', '002', 'SITE BASE STATAION MULTI SITE SYSTEM'),
(2536, '2', '06', '02', '09', '003', 'CONTROLL CENTER'),
(2537, '2', '06', '02', '09', '004', 'E2EENCRYPTION MANAGEMENT TOOLS'),
(2538, '2', '06', '02', '09', '005', 'NETWORK MONITORING SYSTEM'),
(2539, '2', '06', '02', '09', '006', 'SWITCHING MATRIX AND SERVER'),
(2540, '2', '06', '02', '09', '007', 'DIGITAL RECORDING SYSTEM'),
(2541, '2', '06', '02', '09', '008', 'OFFICIAL PHERIPHERAL'),
(2542, '2', '06', '02', '09', '009', 'MOBILE UNIT'),
(2543, '2', '06', '02', '09', '010', 'MOBILE GATEWAY'),
(2544, '2', '06', '02', '09', '011', 'CONVERT BODY'),
(2545, '2', '06', '02', '09', '012', 'REPEATER RX/TX'),
(2546, '2', '06', '02', '09', '013', 'REPEATER MULTIBAND COMBINER 4 IN 4OUT'),
(2547, '2', '06', '02', '09', '014', 'REPEATER MULTIBAND COMBINER 4 IN 2OUT'),
(2548, '2', '06', '02', '09', '015', 'REPEATER MULTIBAND COMBINER 2 IN 2OUT'),
(2549, '2', '06', '02', '09', '016', 'REPEATER CDMA 80PO MHZ'),
(2550, '2', '06', '02', '09', '017', 'REPEATER CDS 1800 MHZ'),
(2551, '2', '06', '02', '09', '999', 'ALAT KOMUNIKASI DIGITAL DAN KONVENSIONAL LAINNYA'),
(2552, '2', '06', '02', '10', '000', 'ALAT KOMUNIKASI SATELIT'),
(2553, '2', '06', '02', '10', '001', 'FULLY SYSTEM HUB'),
(2554, '2', '06', '02', '10', '002', 'VSAT SYSTEM FOR REMOTE TERMINAL'),
(2555, '2', '06', '02', '10', '003', 'COMMOB (COMMUNICATION MOBILE) VSAT'),
(2556, '2', '06', '02', '10', '004', 'WIRELESS BASE STATION + SURVEILLANCE MANPACK KIT'),
(2557, '2', '06', '02', '10', '005', 'FLYAWAY'),
(2558, '2', '06', '02', '10', '006', 'ENCRYPTION'),
(2559, '2', '06', '02', '10', '007', 'REMOTE DATA CONNECTION DISTRIBUTION'),
(2560, '2', '06', '02', '10', '008', 'REMOTE VOIP GATEWAY E1 CARD INTERFACE'),
(2561, '2', '06', '02', '10', '009', 'SPECTRUM ANALYZER FOR HUB STATION'),
(2562, '2', '06', '02', '10', '010', 'SPECTRUM ANALYZER PORTABLE FOR FIELD USE'),
(2563, '2', '06', '02', '10', '011', 'UPS 15 KVA FOR HUB STATION'),
(2564, '2', '06', '02', '10', '012', 'UPS 1 KVA FOR REMOTE STATION'),
(2565, '2', '06', '02', '10', '999', 'ALAT KOMUNIKASI SATELIT LAINNYA'),
(2566, '2', '06', '02', '99', '000', 'ALAT KOMUNIKASI LAINNYA'),
(2567, '2', '06', '02', '99', '999', 'ALAT KOMUNIKASI LAINNYA'),
(2568, '2', '06', '03', '00', '000', 'PERALATAN PEMANCAR'),
(2569, '2', '06', '03', '01', '000', 'PERALATAN PEMANCAR MF/MW'),
(2570, '2', '06', '03', '01', '001', 'UNIT PEMANCAR MF/MW PORTABLE'),
(2571, '2', '06', '03', '01', '002', 'UNIT PEMANCAR MF/MW TRANSPORTABLE'),
(2572, '2', '06', '03', '01', '003', 'UNIT PEMANCAR MF/MW STATIONARY'),
(2573, '2', '06', '03', '01', '999', 'PERALATAN PEMANCAR MF/MW LAINNYA'),
(2574, '2', '06', '03', '02', '000', 'PERALATAN PEMANCAR HF/SW'),
(2575, '2', '06', '03', '02', '001', 'UNIT PEMANCAR HF/SW PORTABLE'),
(2576, '2', '06', '03', '02', '002', 'UNIT PEMANCAR HF/SW TRANSPORTABLE'),
(2577, '2', '06', '03', '02', '003', 'UNIT PEMANCAR HF/SW STATIONARY'),
(2578, '2', '06', '03', '02', '999', 'PERALATAN PEMANCAR HF/SW LAINNYA'),
(2579, '2', '06', '03', '03', '000', 'PERALATAN PEMANCAR VHF/FM'),
(2580, '2', '06', '03', '03', '001', 'UNIT PEMANCAR VHF/FM PORTABLE'),
(2581, '2', '06', '03', '03', '002', 'UNIT PEMANCAR VHF/FM TRANSPORTABLE'),
(2582, '2', '06', '03', '03', '003', 'UNIT PEMANCAR VHF/FM STATIONARY'),
(2583, '2', '06', '03', '03', '999', 'PERALATAN PEMANCAR VHF/FM LAINNYA'),
(2584, '2', '06', '03', '04', '000', 'PERALATAN PEMANCAR UHF'),
(2585, '2', '06', '03', '04', '001', 'UNIT PEMANCAR UHF PORTABLE'),
(2586, '2', '06', '03', '04', '002', 'UNIT PEMANCAR UHF TRANSPORTABLE'),
(2587, '2', '06', '03', '04', '003', 'UNIT PEMANCAR UHF STATIONARY'),
(2588, '2', '06', '03', '04', '004', 'PORTABLE REPORTER LINK'),
(2589, '2', '06', '03', '04', '999', 'PERALATAN PEMANCAR UHF LAINNYA'),
(2590, '2', '06', '03', '05', '000', 'PERALATAN PEMANCAR SHF'),
(2591, '2', '06', '03', '05', '001', 'UNIT PEMANCAR SHF PORTABLE'),
(2592, '2', '06', '03', '05', '002', 'UNIT PEMANCAR SHF TRANSPORTABLE'),
(2593, '2', '06', '03', '05', '003', 'UNIT PEMANCAR SHF STATIONARY'),
(2594, '2', '06', '03', '05', '004', 'SATELLITE LINK ( UP/DOWN LINK )'),
(2595, '2', '06', '03', '05', '999', 'PERALATAN PEMANCAR SHF LAINNYA'),
(2596, '2', '06', '03', '06', '000', 'PERALATAN ANTENA MF/MW'),
(2597, '2', '06', '03', '06', '001', 'ANTENE MF/MW PORTABLE'),
(2598, '2', '06', '03', '06', '002', 'ANTENE MF/MW TRANSPORTABLE'),
(2599, '2', '06', '03', '06', '003', 'ANTENE MF/MW STATIONARY'),
(2600, '2', '06', '03', '06', '999', 'PERALATAN ANTENA MF/MW LAINNYA'),
(2601, '2', '06', '03', '07', '000', 'PERALATAN ANTENA HF/SW'),
(2602, '2', '06', '03', '07', '001', 'ANTENE HF/SW PORTABLE'),
(2603, '2', '06', '03', '07', '002', 'ANTENE HF/SW TRANSPORTABLE'),
(2604, '2', '06', '03', '07', '003', 'ANTENE HF/SW STATIONARY'),
(2605, '2', '06', '03', '07', '999', 'PERALATAN ANTENA HF/SW LAINNYA'),
(2606, '2', '06', '03', '08', '000', 'PERALATAN ANTENA VHF/FM'),
(2607, '2', '06', '03', '08', '001', 'ANTENE VHF/FM PORTABLE'),
(2608, '2', '06', '03', '08', '002', 'ANTENE VHF/FM TRANSPORTABLE'),
(2609, '2', '06', '03', '08', '003', 'ANTENE VHF/FM STATIONARY'),
(2610, '2', '06', '03', '08', '999', 'PERALATAN ANTENA VHF/FM LAINNYA'),
(2611, '2', '06', '03', '09', '000', 'PERALATAN ANTENA UHF'),
(2612, '2', '06', '03', '09', '001', 'ANTENE UHF PORTABLE'),
(2613, '2', '06', '03', '09', '002', 'ANTENE UHF TRANSPORTABLE'),
(2614, '2', '06', '03', '09', '003', 'ANTENE UHF STATIONARY'),
(2615, '2', '06', '03', '09', '999', 'PERALATAN ANTENA UHF LAINNYA'),
(2616, '2', '06', '03', '10', '000', 'PERALATAN ANTENA SHF/PARABOLA'),
(2617, '2', '06', '03', '10', '001', 'ANTENE SHF PORTABLE'),
(2618, '2', '06', '03', '10', '002', 'ANTENE SHF TRANSPORTABLE'),
(2619, '2', '06', '03', '10', '003', 'ANTENE SHF STATIONARY'),
(2620, '2', '06', '03', '10', '004', 'ANTENA ALL BAND'),
(2621, '2', '06', '03', '10', '005', 'ANTENA SSB'),
(2622, '2', '06', '03', '10', '999', 'PERALATAN ANTENA SHF/PARABOLA LAINNYA'),
(2623, '2', '06', '03', '11', '000', 'PERALATAN TRANSLATOR VHF/VHF'),
(2624, '2', '06', '03', '11', '001', 'TRANSLATOR VHF/VHF PORTABLE'),
(2625, '2', '06', '03', '11', '002', 'TRANSLATOR VHF/VHF TRANSPORTABLE'),
(2626, '2', '06', '03', '11', '003', 'TRANSLATOR VHF/VHF STATIONARY'),
(2627, '2', '06', '03', '11', '999', 'PERALATAN TRANSLATOR VHF/VHF LAINNYA'),
(2628, '2', '06', '03', '12', '000', 'PERALATAN TRANSLATOR UHF/UHF'),
(2629, '2', '06', '03', '12', '001', 'TRANSLATOR UHF/UHF PORTABLE'),
(2630, '2', '06', '03', '12', '002', 'TRANSLATOR UHF/UHF TRANSPORTABLE'),
(2631, '2', '06', '03', '12', '003', 'TRANSLATOR UHF/UHF STATIONARY'),
(2632, '2', '06', '03', '12', '999', 'PERALATAN TRANSLATOR UHF/UHF LAINNYA'),
(2633, '2', '06', '03', '13', '000', 'PERALATAN TRANSLATOR VHF/UHF'),
(2634, '2', '06', '03', '13', '001', 'TRANSLATOR VHF/UHF PORTABLE'),
(2635, '2', '06', '03', '13', '002', 'TRANSLATOR VHF/UHF TRANSPORTABLE'),
(2636, '2', '06', '03', '13', '003', 'TRANSLATOR VHF/UHF STATIONARY'),
(2637, '2', '06', '03', '13', '999', 'PERALATAN TRANSLATOR VHF/UHF LAINNYA'),
(2638, '2', '06', '03', '14', '000', 'PERALATAN TRANSLATOR UHF/VHF'),
(2639, '2', '06', '03', '14', '001', 'TRANSLATOR UHF/VHF PORTABLE'),
(2640, '2', '06', '03', '14', '002', 'TRANSLATOR UHF/VHF TRANSPORTABLE'),
(2641, '2', '06', '03', '14', '003', 'TRANSLATOR UHF/VHF STATIONARY'),
(2642, '2', '06', '03', '14', '999', 'PERALATAN TRANSLATOR UHF/VHF LAINNYA'),
(2643, '2', '06', '03', '15', '000', 'PERALATAN MICROWAVE F P U'),
(2644, '2', '06', '03', '15', '001', 'MICROWAVE F P U PORTABLE'),
(2645, '2', '06', '03', '15', '002', 'MICROWAVE F P U TRANSPORTABLE'),
(2646, '2', '06', '03', '15', '003', 'MICROWAVE F P U STATIONARY'),
(2647, '2', '06', '03', '15', '999', 'PERALATAN MICROWAVE F P U LAINNYA'),
(2648, '2', '06', '03', '16', '000', 'PERALATAN MICROWAVE TERESTRIAL'),
(2649, '2', '06', '03', '16', '001', 'MICROWAVE TERESTRIAL PORTABLE'),
(2650, '2', '06', '03', '16', '002', 'MICROWAVE TERESTRIAL TRANSPORTABLE'),
(2651, '2', '06', '03', '16', '003', 'MICROWAVE TERESTRIAL STATIONARY'),
(2652, '2', '06', '03', '16', '999', 'PERALATAN MICROWAVE TERESTRIAL LAINNYA'),
(2653, '2', '06', '03', '17', '000', 'PERALATAN MICROWAVE TVRO'),
(2654, '2', '06', '03', '17', '001', 'MICROWAVE TVRO PORTABLE'),
(2655, '2', '06', '03', '17', '002', 'MICROWAVE TVRO TRANSPORTABLE'),
(2656, '2', '06', '03', '17', '003', 'MICROWAVE TVRO STATIONARY'),
(2657, '2', '06', '03', '17', '999', 'PERALATAN MICROWAVE TVRO LAINNYA'),
(2658, '2', '06', '03', '18', '000', 'PERALATAN DUMMY LOAD'),
(2659, '2', '06', '03', '18', '001', 'DUMMY LOAD PENDINGIN UDARA'),
(2660, '2', '06', '03', '18', '002', 'DUMMY LOAD PENDINGIN AIR'),
(2661, '2', '06', '03', '18', '003', 'DUMMY LOAD PENDINGIN MINYAK'),
(2662, '2', '06', '03', '18', '004', 'DUMMY LOAD PENDINGIN GAS'),
(2663, '2', '06', '03', '18', '999', 'PERALATAN DUMMY LOAD LAINNYA'),
(2664, '2', '06', '03', '19', '000', 'SWITCHER ANTENA'),
(2665, '2', '06', '03', '19', '001', 'SWITCHER COMBINATION'),
(2666, '2', '06', '03', '19', '002', 'SWITCHER MANUAL'),
(2667, '2', '06', '03', '19', '003', 'SWITCHER AUTOMATIC MOTOR'),
(2668, '2', '06', '03', '19', '999', 'SWITCHER ANTENA LAINNYA'),
(2669, '2', '06', '03', '20', '000', 'SWITCHER/MENARA ANTENA'),
(2670, '2', '06', '03', '20', '001', 'SELF SUPPORTING TOWER'),
(2671, '2', '06', '03', '20', '002', 'GUY TOWER'),
(2672, '2', '06', '03', '20', '003', 'MAST TOWER'),
(2673, '2', '06', '03', '20', '004', 'CONCRETE TOWER'),
(2674, '2', '06', '03', '20', '999', 'SWITCHER/MENARA ANTENA LAINNYA'),
(2675, '2', '06', '03', '21', '000', 'FEEDER'),
(2676, '2', '06', '03', '21', '001', 'OPEN WIRE'),
(2677, '2', '06', '03', '21', '002', 'COAXIAL FEEDER'),
(2678, '2', '06', '03', '21', '003', 'ANTENNA TUNING UNIT'),
(2679, '2', '06', '03', '21', '004', 'DEHYDRATOR'),
(2680, '2', '06', '03', '21', '999', '\"FEEDER LAINNYA (ALAT STUDIO'),
(2681, '2', '06', '03', '22', '000', 'HUMIDITY CONTROL'),
(2682, '2', '06', '03', '22', '001', 'DEHUMIDIFIER (HUMIDITY CONTROL)'),
(2683, '2', '06', '03', '22', '999', 'HUMIDITY CONTROL LAINNYA'),
(2684, '2', '06', '03', '23', '000', 'PROGRAM INPUT EQUIPMENT'),
(2685, '2', '06', '03', '23', '001', 'RECEIVER STL/VHF ( FM)'),
(2686, '2', '06', '03', '23', '002', 'RECEIVER STL/UHF'),
(2687, '2', '06', '03', '23', '003', 'RECEIVER STL/SHF'),
(2688, '2', '06', '03', '23', '004', 'TVRO'),
(2689, '2', '06', '03', '23', '005', 'LINE AMPLIFIER'),
(2690, '2', '06', '03', '23', '006', 'S R O'),
(2691, '2', '06', '03', '23', '007', 'LINE EQUALIZER'),
(2692, '2', '06', '03', '23', '008', 'AUTOMATIC GAIN CONTROL'),
(2693, '2', '06', '03', '23', '009', 'COMPRESSOR AMPLIFIER'),
(2694, '2', '06', '03', '23', '010', 'EXPANDER AMPLIFIER'),
(2695, '2', '06', '03', '23', '011', 'ATTENUATOR'),
(2696, '2', '06', '03', '23', '012', 'AUDIO PROCESSOR AM'),
(2697, '2', '06', '03', '23', '013', 'STEREO GENERATOR FM'),
(2698, '2', '06', '03', '23', '014', 'DISTRIBUTOR AMPLIFIER'),
(2699, '2', '06', '03', '23', '015', 'SWITCHER/PATCH PANEL'),
(2700, '2', '06', '03', '23', '016', 'AUDIO MONITOR'),
(2701, '2', '06', '03', '23', '017', 'AM MONITOR'),
(2702, '2', '06', '03', '23', '018', 'FM MONITOR'),
(2703, '2', '06', '03', '23', '019', 'POWER DISTRIBUTION BOARD'),
(2704, '2', '06', '03', '23', '020', 'LIGHTNING PROTECTOR'),
(2705, '2', '06', '03', '23', '021', 'ALL BAND RECEIVER'),
(2706, '2', '06', '03', '23', '022', 'CHANGE OVER SWITCH'),
(2707, '2', '06', '03', '23', '999', 'PROGRAM INPUT EQUIPMENT LAINNYA'),
(2708, '2', '06', '03', '24', '000', 'PERALATAN ANTENE PENERIMA VHF'),
(2709, '2', '06', '03', '24', '001', 'ANTENE PENERIMA VHF'),
(2710, '2', '06', '03', '24', '002', 'PERALATAN ANTENA PENERIMA LF'),
(2711, '2', '06', '03', '24', '003', 'PERALATAN ANTENA PENERIMA MF'),
(2712, '2', '06', '03', '24', '004', 'PERALATAN ANTENA PENERIMA HF'),
(2713, '2', '06', '03', '24', '005', 'PERALATAN ANTENA PENERIMA MF+HF'),
(2714, '2', '06', '03', '24', '006', 'PERALATAN ANTENA PENERIMA VHF'),
(2715, '2', '06', '03', '24', '007', 'PERALATAN ANTENA PENERIMA UHF'),
(2716, '2', '06', '03', '24', '008', 'PERALATAN ANTENA PENERIMA SSHF'),
(2717, '2', '06', '03', '24', '999', 'PERALATAN ANTENE PENERIMA VHF LAINNYA'),
(2718, '2', '06', '03', '25', '000', 'PERALATAN PEMANCAR LF'),
(2719, '2', '06', '03', '25', '001', 'PERALATAN PEMANCAR LF TRANSPORTABLE'),
(2720, '2', '06', '03', '25', '002', 'PERALATAN PEMANCAR LF PORTABLE'),
(2721, '2', '06', '03', '25', '003', 'PERALATAN PEMANCAR LF STATIONARY'),
(2722, '2', '06', '03', '25', '999', 'PERALATAN PEMANCAR LF LAINNYA'),
(2723, '2', '06', '03', '26', '000', 'UNIT PEMANCAR MF+HF'),
(2724, '2', '06', '03', '26', '001', 'UNIT PEMANCAR MF+HF TRANSPORTABLE'),
(2725, '2', '06', '03', '26', '002', 'UNIT PEMANCAR MF+HF PORTABLE'),
(2726, '2', '06', '03', '26', '003', 'UNIT PEMANCAR MF+HF STATIONARY'),
(2727, '2', '06', '03', '26', '999', 'UNIT PEMANCAR MF+HF LAINNYA'),
(2728, '2', '06', '03', '27', '000', 'PERALATAN ANTENA PEMANCAR MF+HF'),
(2729, '2', '06', '03', '27', '001', 'PERALATAN ANTENA PEMANCAR MF+HF TRANSPORTABLE'),
(2730, '2', '06', '03', '27', '002', 'PERALATAN ANTENA PEMANCAR MF+HF PORTABLE'),
(2731, '2', '06', '03', '27', '003', 'PERALATAN ANTENA PEMANCAR MF+HF STATIONARY'),
(2732, '2', '06', '03', '27', '999', 'PERALATAN ANTENA PEMANCAR MF+HF LAINNYA'),
(2733, '2', '06', '03', '28', '000', 'PERALATAN PENERIMA'),
(2734, '2', '06', '03', '28', '001', 'PERALATAN PENERIMA LF'),
(2735, '2', '06', '03', '28', '002', 'PERALATAN PENERIMA MF'),
(2736, '2', '06', '03', '28', '003', 'PERALATAN PENERIMA HF'),
(2737, '2', '06', '03', '28', '004', 'PERALATAN PENERIMA MF+HF'),
(2738, '2', '06', '03', '28', '005', 'PERALATAN PENERIMA UHF'),
(2739, '2', '06', '03', '28', '006', 'PERALATAN PENERIMA SHF'),
(2740, '2', '06', '03', '28', '999', 'PERALATAN PENERIMA LAINNYA'),
(2741, '2', '06', '03', '29', '000', 'PERALATAN PEMANCAR DAN PENERIMA LF'),
(2742, '2', '06', '03', '29', '001', 'UNIT TRANSCEIVER LF TRANSPORTABLE'),
(2743, '2', '06', '03', '29', '002', 'UNIT TRANSCEIVER LF PORTABLE'),
(2744, '2', '06', '03', '29', '003', 'UNIT TRANSCEIVER LF STATIONARY'),
(2745, '2', '06', '03', '29', '999', 'PERALATAN PEMANCAR DAN PENERIMA LF LAINNYA'),
(2746, '2', '06', '03', '30', '000', 'PERALATAN PEMANCAR DAN PENERIMA MF'),
(2747, '2', '06', '03', '30', '001', 'UNIT TRANSCEIVER MF TRANSPORTABLE'),
(2748, '2', '06', '03', '30', '002', 'UNIT TRANSCEIVER MF PORTABLE'),
(2749, '2', '06', '03', '30', '003', 'UNIT TRANSCEIVER MF STATIONARY'),
(2750, '2', '06', '03', '30', '999', 'PERALATAN PEMANCAR DAN PENERIMA MF LAINNYA'),
(2751, '2', '06', '03', '31', '000', 'PERALATAN PEMANCAR DAN PENERIMA HF'),
(2752, '2', '06', '03', '31', '001', 'UNIT TRANSCEIVER HF TRANSPORTABLE'),
(2753, '2', '06', '03', '31', '002', 'UNIT TRANSCEIVER HF PORTABLE'),
(2754, '2', '06', '03', '31', '003', 'UNIT TRANSCEIVER HF STATIONARY'),
(2755, '2', '06', '03', '31', '004', 'RS SSB TRANCIEVER'),
(2756, '2', '06', '03', '31', '005', 'MINI RANGER'),
(2757, '2', '06', '03', '31', '006', 'ARTEMIS'),
(2758, '2', '06', '03', '31', '007', 'TELEROMETER'),
(2759, '2', '06', '03', '31', '999', 'PERALATAN PEMANCAR DAN PENERIMA HF LAINNYA'),
(2760, '2', '06', '03', '32', '000', 'PERALATAN PEMANCAR DAN PENERIMA MF+HF'),
(2761, '2', '06', '03', '32', '001', 'UNIT TRANSCEIVER MF+HF TRANSPORTABLE'),
(2762, '2', '06', '03', '32', '002', 'UNIT TRANSCEIVER MF+HF PORTABLE'),
(2763, '2', '06', '03', '32', '003', 'UNIT TRANSCEIVER MF+HF STATIONARY'),
(2764, '2', '06', '03', '32', '004', 'DIFFERENTIAL OMEGA (PERALATAN PEMANCAR DAN PENERIMA MF+HF)'),
(2765, '2', '06', '03', '32', '999', 'PERALATAN PEMANCAR DAN PENERIMA MF+HF LAINNYA'),
(2766, '2', '06', '03', '33', '000', 'PERALATAN PEMANCAR DAN PENERIMA VHF'),
(2767, '2', '06', '03', '33', '001', 'UNIT TRANSCEIVER VHF TRANSPORTABLE'),
(2768, '2', '06', '03', '33', '002', 'UNIT TRANSCEIVER VHF PORTABLE'),
(2769, '2', '06', '03', '33', '003', 'UNIT TRANSCEIVER VHF STATIONARY'),
(2770, '2', '06', '03', '33', '999', 'PERALATAN PEMANCAR DAN PENERIMA VHF LAINNYA'),
(2771, '2', '06', '03', '34', '000', 'PERALATAN PEMANCAR DAN PENERIMA UHF'),
(2772, '2', '06', '03', '34', '001', 'UNIT TRANSCEIVER UHF TRANSPORTABLE'),
(2773, '2', '06', '03', '34', '002', 'UNIT TRANSCEIVER UHF PORTABLE'),
(2774, '2', '06', '03', '34', '003', 'UNIT TRANSCEIVER UHF STATIONARY'),
(2775, '2', '06', '03', '34', '004', 'ULTRA HIGHT FREQUENCE LINK'),
(2776, '2', '06', '03', '34', '005', 'AUTO ALARM TUSTEL (AAT)'),
(2777, '2', '06', '03', '34', '006', 'DISTRIBUTION BOARD AND SIGNAL UNIT'),
(2778, '2', '06', '03', '34', '007', 'REMOTE TERMINAL UNIT'),
(2779, '2', '06', '03', '34', '008', 'MULTIPLEX TERMINAL EQUIPMENT'),
(2780, '2', '06', '03', '34', '009', 'SIGNAL VELVOGER GROUNDING'),
(2781, '2', '06', '03', '34', '010', 'BRIDGE MERGER TESTING'),
(2782, '2', '06', '03', '34', '011', 'MESSAGE REPEATER'),
(2783, '2', '06', '03', '34', '012', 'ELECTRIC CLEANER'),
(2784, '2', '06', '03', '34', '013', 'AOTOMATIC AERLALE'),
(2785, '2', '06', '03', '34', '014', 'POWER AND AWR METER ROUND'),
(2786, '2', '06', '03', '34', '015', 'VOLTAGE REGULATOR'),
(2787, '2', '06', '03', '34', '016', 'GYRO COMPASS'),
(2788, '2', '06', '03', '34', '017', 'FREQUENCE SYNTHESIZER UNIT'),
(2789, '2', '06', '03', '34', '018', 'VODAS (VOICE DEVISE ANTI SINGING)'),
(2790, '2', '06', '03', '34', '019', 'ANEMOMETER (PERALATAN PEMANCAR DAN PENERIMA UHF)'),
(2791, '2', '06', '03', '34', '020', 'CLEAR VIEW SCREEN'),
(2792, '2', '06', '03', '34', '021', 'ARQ UNIT'),
(2793, '2', '06', '03', '34', '022', 'RADIO DIRECTION FINDER'),
(2794, '2', '06', '03', '34', '023', 'POWER TRANSMITTER'),
(2795, '2', '06', '03', '34', '024', 'TELE CONTROLLER'),
(2796, '2', '06', '03', '34', '025', 'LOCAL TERMINAL'),
(2797, '2', '06', '03', '34', '026', 'DIGITAL SELECTIVE CALLING (DSC)'),
(2798, '2', '06', '03', '34', '999', 'PERALATAN PEMANCAR DAN PENERIMA UHF LAINNYA'),
(2799, '2', '06', '03', '35', '000', 'PERALATAN PEMANCAR DAN PENERIMA SHF'),
(2800, '2', '06', '03', '35', '001', 'UNIT TRANSCEIVER SHF TRANSPORTABLE'),
(2801, '2', '06', '03', '35', '002', 'UNIT TRANSCEIVER SHF PORTABLE'),
(2802, '2', '06', '03', '35', '003', 'UNIT TRANSCEIVER SHF STATIONARY'),
(2803, '2', '06', '03', '35', '999', 'PERALATAN PEMANCAR DAN PENERIMA SHF LAINNYA'),
(2804, '2', '06', '03', '36', '000', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA LF'),
(2805, '2', '06', '03', '36', '001', 'UNIT ANTENA TRANSCEIVER LF TRANSPORTABLE'),
(2806, '2', '06', '03', '36', '002', 'UNIT ANTENA TRANSCEIVER LF PORTABLE'),
(2807, '2', '06', '03', '36', '003', 'UNIT ANTENA TRANSCEIVER LF STATIONARY'),
(2808, '2', '06', '03', '36', '999', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA LF LAINNYA'),
(2809, '2', '06', '03', '37', '000', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA MF'),
(2810, '2', '06', '03', '37', '001', 'UNIT ANTENA TRANSCEIVER MF TRANSPORTABLE'),
(2811, '2', '06', '03', '37', '002', 'UNIT ANTENA TRANSCEIVER MF PORTABLE'),
(2812, '2', '06', '03', '37', '003', 'UNIT ANTENA TRANSCEIVER MF STATIONARY'),
(2813, '2', '06', '03', '37', '999', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA MF LAINNYA'),
(2814, '2', '06', '03', '38', '000', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA HF'),
(2815, '2', '06', '03', '38', '001', 'UNIT ANTENA TRANSCEIVER HF TRANSPORTABLE'),
(2816, '2', '06', '03', '38', '002', 'UNIT ANTENA TRANSCEIVER HF PORTABLE'),
(2817, '2', '06', '03', '38', '003', 'UNIT ANTENA TRANSCEIVER HF STATIONARY'),
(2818, '2', '06', '03', '38', '999', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA HF LAINNYA'),
(2819, '2', '06', '03', '39', '000', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA MF+HF'),
(2820, '2', '06', '03', '39', '001', 'UNIT ANTENA TRANSCEIVER MF+ HF TRANSPORTABLE'),
(2821, '2', '06', '03', '39', '002', 'UNIT ANTENA TRANSCEIVER MF+HF PORTABLE'),
(2822, '2', '06', '03', '39', '003', 'UNIT ANTENA TRANSCEIVER MF+HF STATIONARY'),
(2823, '2', '06', '03', '39', '999', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA MF+HF LAINNYA'),
(2824, '2', '06', '03', '40', '000', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA VHF'),
(2825, '2', '06', '03', '40', '001', 'UNIT ANTENA TRANSCEIVER VHF TRANSPORTABLE'),
(2826, '2', '06', '03', '40', '002', 'UNIT ANTENA TRANSCEIVER VHF PORTABLE'),
(2827, '2', '06', '03', '40', '003', 'UNIT ANTENA TRANSCEIVER VHF STATIONARY'),
(2828, '2', '06', '03', '40', '999', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA VHF LAINNYA'),
(2829, '2', '06', '03', '41', '000', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA UHF'),
(2830, '2', '06', '03', '41', '001', 'UNIT ANTENA TRANSCEIVER UHF TRANSPORTABLE'),
(2831, '2', '06', '03', '41', '002', 'UNIT ANTENA TRANSCEIVER UHF PORTABLE'),
(2832, '2', '06', '03', '41', '003', 'UNIT ANTENA TRANSCEIVER UHF STATIONARY'),
(2833, '2', '06', '03', '41', '999', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA UHF LAINNYA'),
(2834, '2', '06', '03', '42', '000', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA SHF'),
(2835, '2', '06', '03', '42', '001', 'UNIT ANTENA TRANSCEIVER SHF TRANSPORTABLE'),
(2836, '2', '06', '03', '42', '002', 'UNIT ANTENA TRANSCEIVER SHF PORTABLE'),
(2837, '2', '06', '03', '42', '003', 'UNIT ANTENA TRANSCEIVER SHF STATIONARY'),
(2838, '2', '06', '03', '42', '999', 'PERALATAN ANTENA PEMANCAR DAN PENERIMA SHF LAINNYA'),
(2839, '2', '06', '03', '43', '000', 'PERALATAN PENERIMA CUACA CITRA SATELITE RESOLUSI RENDAH'),
(2840, '2', '06', '03', '43', '001', 'ALAT PENERIMA SATELITE CUACA'),
(2841, '2', '06', '03', '43', '999', 'PERALATAN PENERIMA CUACA CITRA SATELITE RESOLUSI RENDAH LAINNYA'),
(2842, '2', '06', '03', '44', '000', 'PERALATAN PENERIMA CUACA CITRA SATELITE RESOLUSI TINGGI'),
(2843, '2', '06', '03', '44', '001', 'ALAT PENERIMA SATELITE CUACA GEO STASIMETER'),
(2844, '2', '06', '03', '44', '002', 'ALAT PENERIMA SATELITE CUACA ORBIT POLAR'),
(2845, '2', '06', '03', '44', '999', 'PERALATAN PENERIMA CUACA CITRA SATELITE RESOLUSI TINGGI LAINNYA'),
(2846, '2', '06', '03', '45', '000', 'PERALATAN PENERIMA DAN PENGIRIM GAMBAR KE PERMUKAAN'),
(2847, '2', '06', '03', '45', '001', 'SCANNER FACSIMILE'),
(2848, '2', '06', '03', '45', '002', 'ALDEN MINIFAX RECORDER'),
(2849, '2', '06', '03', '45', '003', 'UNIVERSAL GRAPHIC RECORDER'),
(2850, '2', '06', '03', '45', '004', 'WEATHER CHART RECORDER'),
(2851, '2', '06', '03', '45', '999', 'PERALATAN PENERIMA DAN PENGIRIM GAMBAR KE PERMUKAAN LAINNYA'),
(2852, '2', '06', '03', '46', '000', 'PERALATAN PERLENGKAPAN RADIO'),
(2853, '2', '06', '03', '46', '001', 'BOX BATTERY'),
(2854, '2', '06', '03', '46', '002', 'CUTTON DUCK'),
(2855, '2', '06', '03', '46', '003', 'CARRING CASE'),
(2856, '2', '06', '03', '46', '004', 'HAND SET'),
(2857, '2', '06', '03', '46', '005', 'CONECCTOR'),
(2858, '2', '06', '03', '46', '999', 'PERALATAN PERLENGKAPAN RADIO LAINNYA'),
(2859, '2', '06', '03', '47', '000', 'SUMBER TENAGA'),
(2860, '2', '06', '03', '47', '001', 'BA-30'),
(2861, '2', '06', '03', '47', '002', 'GENSET'),
(2862, '2', '06', '03', '47', '003', 'SOLAR CELL'),
(2863, '2', '06', '03', '47', '004', 'CHARGER'),
(2864, '2', '06', '03', '47', '999', 'SUMBER TENAGA LAINNYA'),
(2865, '2', '06', '03', '99', '000', 'PERALATAN PEMANCAR LAINNYA'),
(2866, '2', '06', '03', '99', '999', 'PERALATAN PEMANCAR LAINNYA'),
(2867, '2', '06', '04', '00', '000', 'PERALATAN KOMUNIKASI NAVIGASI'),
(2868, '2', '06', '04', '01', '000', 'PERALATAN KOMUNIKASI NAVIGASI INSTRUMEN LANDING SYSTEM'),
(2869, '2', '06', '04', '01', '001', 'STANDARD INSTRUMEN LANDING SYSTEM'),
(2870, '2', '06', '04', '01', '002', 'MICROWAVE LANDING SYSTEM'),
(2871, '2', '06', '04', '01', '999', 'PERALATAN KOMUNIKASI NAVIGASI INSTRUMEN LANDING SYSTEM LAINNYA'),
(2872, '2', '06', '04', '02', '000', 'VERY HIGHT FREQUENCE OMNI RANGE (VOR)'),
(2873, '2', '06', '04', '02', '001', 'CONVENTIONAL VOR (CVOR)'),
(2874, '2', '06', '04', '02', '002', 'DOOPLE VOR (DVOR)'),
(2875, '2', '06', '04', '02', '999', 'VERY HIGHT FREQUENCE OMNI RANGE (VOR) LAINNYA'),
(2876, '2', '06', '04', '03', '000', 'DISTANCE MEASURING EQUIPMENT (DME)'),
(2877, '2', '06', '04', '03', '001', 'TRANSPONDER DME'),
(2878, '2', '06', '04', '03', '002', 'ANTENA DME'),
(2879, '2', '06', '04', '03', '003', 'BEACON'),
(2880, '2', '06', '04', '03', '004', 'NDB'),
(2881, '2', '06', '04', '03', '005', 'DB'),
(2882, '2', '06', '04', '03', '006', 'RADAR BEACON'),
(2883, '2', '06', '04', '03', '007', 'DIFFERENTIAL OMEGA (DISTANCE MEASURING EQUIPMENT (DME))'),
(2884, '2', '06', '04', '03', '008', 'DIFFERENTIAL GPS'),
(2885, '2', '06', '04', '03', '999', 'DISTANCE MEASURING EQUIPMENT (DME) LAINNYA'),
(2886, '2', '06', '04', '04', '000', 'RADAR'),
(2887, '2', '06', '04', '04', '001', 'PRIMARY SURVEILLANCE RADAR'),
(2888, '2', '06', '04', '04', '002', 'SECONDARY SURVEILLANCE RADAR'),
(2889, '2', '06', '04', '04', '999', 'RADAR LAINNYA'),
(2890, '2', '06', '04', '05', '000', 'ALAT PENGATUR TELEKOMUNIKASI'),
(2891, '2', '06', '04', '05', '001', 'MESSAGE SWITCHING CENTER (MSC)'),
(2892, '2', '06', '04', '05', '002', 'AUTOMATIC MESSAGE SWITCHING CENTER (AMSC)'),
(2893, '2', '06', '04', '05', '003', 'CURRENT CONSOLE REGULATOR'),
(2894, '2', '06', '04', '05', '004', 'CONTROLLER CONSOLE PVC'),
(2895, '2', '06', '04', '05', '006', 'NO BREAK CASINET'),
(2896, '2', '06', '04', '05', '007', 'TELEGRAPHIC FRAME'),
(2897, '2', '06', '04', '05', '008', 'MORDEN'),
(2898, '2', '06', '04', '05', '009', 'RADIO CONSOLE'),
(2899, '2', '06', '04', '05', '010', 'SUPERVISORI CONSOLE'),
(2900, '2', '06', '04', '05', '999', 'ALAT PENGATUR TELEKOMUNIKASI LAINNYA'),
(2901, '2', '06', '04', '06', '000', 'PERALATAN KOMUNIKASI UNTUK DOKUMENTASI'),
(2902, '2', '06', '04', '06', '001', 'UNIT TAPE RECORDER'),
(2903, '2', '06', '04', '06', '002', 'UNIT TIME ANNOUNCING'),
(2904, '2', '06', '04', '06', '003', 'UNIT MASTER CLOCK'),
(2905, '2', '06', '04', '06', '004', 'UNIT REPRODUCER'),
(2906, '2', '06', '04', '06', '005', 'UNIT REMOTE CONTROL'),
(2907, '2', '06', '04', '06', '999', 'PERALATAN KOMUNIKASI UNTUK DOKUMENTASI LAINNYA'),
(2908, '2', '06', '04', '99', '000', 'PERALATAN KOMUNIKASI NAVIGASI LAINNYA'),
(2909, '2', '06', '04', '99', '999', 'PERALATAN KOMUNIKASI NAVIGASI LAINNYA'),
(2910, '2', '07', '00', '00', '000', 'KOMPUTER'),
(2911, '2', '07', '01', '00', '000', 'KOMPUTER UNIT'),
(2912, '2', '07', '01', '01', '000', 'KOMPUTER JARINGAN'),
(2913, '2', '07', '01', '01', '001', 'MAINFRAME (KOMPUTER JARINGAN)'),
(2914, '2', '07', '01', '01', '002', 'MINI KOMPUTER'),
(2915, '2', '07', '01', '01', '003', 'LOCAL AREA NETWORK (LAN)'),
(2916, '2', '07', '01', '01', '004', 'INTERNET'),
(2917, '2', '07', '01', '01', '005', 'KOMPUTER WEDIS'),
(2918, '2', '07', '01', '01', '006', 'KOMPUTER SYNERGIE'),
(2919, '2', '07', '01', '01', '007', 'PC WORKSTATION'),
(2920, '2', '07', '01', '01', '999', 'KOMPUTER JARINGAN LAINNYA'),
(2921, '2', '07', '01', '02', '000', 'PERSONAL KOMPUTER'),
(2922, '2', '07', '01', '02', '001', 'P.C UNIT'),
(2923, '2', '07', '01', '02', '002', 'LAP TOP'),
(2924, '2', '07', '01', '02', '003', 'NOTE BOOK'),
(2925, '2', '07', '01', '02', '004', 'PALM TOP'),
(2926, '2', '07', '01', '02', '005', 'CODE BREAKER SUPER KOMPUTER'),
(2927, '2', '07', '01', '02', '006', 'THINCLIENT'),
(2928, '2', '07', '01', '02', '007', 'NET BOOK'),
(2929, '2', '07', '01', '02', '008', 'ULTRA MOBILE P.C.'),
(2930, '2', '07', '01', '02', '999', 'PERSONAL KOMPUTER LAINNYA'),
(2931, '2', '07', '01', '99', '000', 'KOMPUTER UNIT LAINNYA'),
(2932, '2', '07', '01', '99', '999', 'KOMPUTER UNIT LAINNYA'),
(2933, '2', '07', '02', '00', '000', 'PERALATAN KOMPUTER'),
(2934, '2', '07', '02', '01', '000', 'PERALATAN MAINFRAME'),
(2935, '2', '07', '02', '01', '001', 'CARD READER (PERALATAN MAINFRAME)'),
(2936, '2', '07', '02', '01', '002', 'MAGNETIC TAPE UNIT (PERALATAN MAINFRAME)'),
(2937, '2', '07', '02', '01', '003', 'FLOPPY DISK UNIT (PERALATAN MAINFRAME)'),
(2938, '2', '07', '02', '01', '004', 'STORAGE MODUL DISK (PERALATAN MAINFRAME)'),
(2939, '2', '07', '02', '01', '005', 'CONSOLE UNIT (PERALATAN MAINFRAME)'),
(2940, '2', '07', '02', '01', '006', 'CPU (PERALATAN MAINFRAME)'),
(2941, '2', '07', '02', '01', '007', 'DISK PACK (PERALATAN MAINFRAME)'),
(2942, '2', '07', '02', '01', '008', 'HARD COPY CONSOLE'),
(2943, '2', '07', '02', '01', '009', 'SERIAL PRINTER'),
(2944, '2', '07', '02', '01', '010', 'LINE PRINTER'),
(2945, '2', '07', '02', '01', '011', 'PLOTTER (PERALATAN MAINFRAME)'),
(2946, '2', '07', '02', '01', '012', 'HARD DISK'),
(2947, '2', '07', '02', '01', '013', 'KEYBOARD (PERALATAN MAINFRAME)'),
(2948, '2', '07', '02', '01', '014', 'STEAMER'),
(2949, '2', '07', '02', '01', '015', 'DATA PATCH PANEL'),
(2950, '2', '07', '02', '01', '016', 'PAPER TAPE READER'),
(2951, '2', '07', '02', '01', '017', 'PANABOARD'),
(2952, '2', '07', '02', '01', '999', 'PERALATAN MAINFRAME LAINNYA'),
(2953, '2', '07', '02', '02', '000', 'PERALATAN MINI KOMPUTER'),
(2954, '2', '07', '02', '02', '001', 'CARD READER (PERALATAN MINI KOMPUTER)'),
(2955, '2', '07', '02', '02', '002', 'MAGNETIC TAPE UNIT (PERALATAN MINI KOMPUTER)'),
(2956, '2', '07', '02', '02', '003', 'FLOPPY DISK UNIT (PERALATAN MINI KOMPUTER)'),
(2957, '2', '07', '02', '02', '004', 'STORAGE MODUL DISK (PERALATAN MINI KOMPUTER)'),
(2958, '2', '07', '02', '02', '005', 'CONSOLE UNIT (PERALATAN MINI KOMPUTER)'),
(2959, '2', '07', '02', '02', '006', 'CPU (PERALATAN MINI KOMPUTER)'),
(2960, '2', '07', '02', '02', '007', 'DISK PACK (PERALATAN MINI KOMPUTER)'),
(2961, '2', '07', '02', '02', '009', 'PLOTTER (PERALATAN MINI KOMPUTER)'),
(2962, '2', '07', '02', '02', '010', 'SCANNER (PERALATAN MINI KOMPUTER)'),
(2963, '2', '07', '02', '02', '011', 'COMPUTER COMPATIBLE'),
(2964, '2', '07', '02', '02', '012', 'VIEWER (PERALATAN MINI KOMPUTER)'),
(2965, '2', '07', '02', '02', '013', 'DIGITIZER (PERALATAN MINI KOMPUTER)'),
(2966, '2', '07', '02', '02', '014', 'KEYBOARD (PERALATAN MINI KOMPUTER)'),
(2967, '2', '07', '02', '02', '015', 'AUTO SWITCH/DATA SWITCH'),
(2968, '2', '07', '02', '02', '016', 'CUT SHEET FEEDER'),
(2969, '2', '07', '02', '02', '017', 'SPEAKER KOMPUTER'),
(2970, '2', '07', '02', '02', '999', 'PERALATAN MINI KOMPUTER LAINNYA'),
(2971, '2', '07', '02', '03', '000', 'PERALATAN PERSONAL KOMPUTER'),
(2972, '2', '07', '02', '03', '001', 'CPU (PERALATAN PERSONAL KOMPUTER)'),
(2973, '2', '07', '02', '03', '002', 'MONITOR'),
(2974, '2', '07', '02', '03', '003', 'PRINTER (PERALATAN PERSONAL KOMPUTER)'),
(2975, '2', '07', '02', '03', '004', 'SCANNER (PERALATAN PERSONAL KOMPUTER)'),
(2976, '2', '07', '02', '03', '005', 'PLOTTER (PERALATAN PERSONAL KOMPUTER)'),
(2977, '2', '07', '02', '03', '006', 'VIEWER (PERALATAN PERSONAL KOMPUTER)'),
(2978, '2', '07', '02', '03', '007', 'EXTERNAL'),
(2979, '2', '07', '02', '03', '008', 'DIGITIZER (PERALATAN PERSONAL KOMPUTER)'),
(2980, '2', '07', '02', '03', '009', 'KEYBOARD (PERALATAN PERSONAL KOMPUTER)'),
(2981, '2', '07', '02', '03', '010', 'CD WRITTER'),
(2982, '2', '07', '02', '03', '011', 'DVD WRITER'),
(2983, '2', '07', '02', '03', '012', 'FIREWIRE CARD'),
(2984, '2', '07', '02', '03', '013', 'CAPTURE CARD'),
(2985, '2', '07', '02', '03', '014', 'LAN CARD'),
(2986, '2', '07', '02', '03', '015', 'EXTERNAL CD/ DVD DRIVE (ROM)'),
(2987, '2', '07', '02', '03', '016', 'EXTERNAL FLOPPY DISK DRIVE'),
(2988, '2', '07', '02', '03', '017', 'EXTERNAL/ PORTABLE HARDISK'),
(2989, '2', '07', '02', '03', '999', 'PERALATAN PERSONAL KOMPUTER LAINNYA'),
(2990, '2', '07', '02', '04', '000', 'PERALATAN JARINGAN'),
(2991, '2', '07', '02', '04', '001', 'SERVER'),
(2992, '2', '07', '02', '04', '002', 'ROUTER'),
(2993, '2', '07', '02', '04', '003', 'HUB'),
(2994, '2', '07', '02', '04', '004', 'MODEM'),
(2995, '2', '07', '02', '04', '005', 'NETWARE INTERFACE EXTERNAL'),
(2996, '2', '07', '02', '04', '006', 'REPEATER AND TRANSCIEVER'),
(2997, '2', '07', '02', '04', '007', 'HEAD COPY TERMINAL'),
(2998, '2', '07', '02', '04', '008', 'RACK MODEM'),
(2999, '2', '07', '02', '04', '009', 'CARD PUNCH'),
(3000, '2', '07', '02', '04', '010', 'HEAD COPY PRINTER'),
(3001, '2', '07', '02', '04', '011', 'CHARACTER TERMINAL'),
(3002, '2', '07', '02', '04', '012', 'GRAPHIC TERMINAL'),
(3003, '2', '07', '02', '04', '013', 'TERMINAL'),
(3004, '2', '07', '02', '04', '014', 'RAK SERVER'),
(3005, '2', '07', '02', '04', '015', 'FIREWALL'),
(3006, '2', '07', '02', '04', '016', 'SWITCH RAK'),
(3007, '2', '07', '02', '04', '017', 'WANSCALLER'),
(3008, '2', '07', '02', '04', '018', 'E-MAIL SECURITY'),
(3009, '2', '07', '02', '04', '019', 'CLIENT CLEARING HOUSE'),
(3010, '2', '07', '02', '04', '020', 'CAT 6 CABLE'),
(3011, '2', '07', '02', '04', '021', 'KABEL UTP'),
(3012, '2', '07', '02', '04', '022', 'WIRELESS PCI CARD'),
(3013, '2', '07', '02', '04', '023', 'WIRELESS ACCESS POINT'),
(3014, '2', '07', '02', '04', '024', 'SWITCH'),
(3015, '2', '07', '02', '04', '025', 'HUBBEL UTP'),
(3016, '2', '07', '02', '04', '026', 'ACCES POINT'),
(3017, '2', '07', '02', '04', '027', 'RACKMOUNT'),
(3018, '2', '07', '02', '04', '028', 'KVM KEYBOARD VIDEO MONITOR'),
(3019, '2', '07', '02', '04', '029', 'MOBILE MODEM GSM/ CDMA'),
(3020, '2', '07', '02', '04', '030', 'NETWORK CABLE TESTER'),
(3021, '2', '07', '02', '04', '031', 'JARINGAN SATPAS'),
(3022, '2', '07', '02', '04', '999', 'PERALATAN JARINGAN LAINNYA'),
(3023, '2', '07', '02', '99', '000', 'PERALATAN KOMPUTER LAINNYA'),
(3024, '2', '07', '02', '99', '999', 'PERALATAN KOMPUTER LAINNYA'),
(3025, '2', '08', '00', '00', '000', 'ALAT PENGEBORAN'),
(3026, '2', '08', '01', '00', '000', 'ALAT PENGEBORAN MESIN'),
(3027, '2', '08', '01', '01', '000', 'BOR MESIN TUMBUK'),
(3028, '2', '08', '01', '01', '001', 'BOR MESIN TUMBUK PAKAI KABEL'),
(3029, '2', '08', '01', '01', '002', 'BOR MESIN TUMBUK PAKAI SETANG BOR'),
(3030, '2', '08', '01', '01', '003', 'BOR MESIN TUMBUK KOMBINASI 01 & 02'),
(3031, '2', '08', '01', '01', '999', 'BOR MESIN TUMBUK LAINNYA'),
(3032, '2', '08', '01', '02', '000', 'BOR MESIN PUTAR'),
(3033, '2', '08', '01', '02', '001', 'ROTARY TABLE (BOR MESIN PUTAR)'),
(3034, '2', '08', '01', '02', '002', 'SPINDLE'),
(3035, '2', '08', '01', '02', '003', 'KOMBINASI 01 & 02'),
(3036, '2', '08', '01', '02', '004', 'TOP DRIVE'),
(3037, '2', '08', '01', '02', '005', 'WIKIE DRILL'),
(3038, '2', '08', '01', '02', '999', 'BOR MESIN PUTAR LAINNYA'),
(3039, '2', '08', '01', '99', '000', 'ALAT PENGEBORAN MESIN LAINNYA'),
(3040, '2', '08', '01', '99', '999', 'ALAT PENGEBORAN MESIN LAINNYA'),
(3041, '2', '08', '02', '00', '000', 'ALAT PENGEBORAN NON MESIN'),
(3042, '2', '08', '02', '01', '000', 'BANGKA'),
(3043, '2', '08', '02', '01', '001', 'BANGKA'),
(3044, '2', '08', '02', '01', '999', 'BANGKA LAINNYA'),
(3045, '2', '08', '02', '02', '000', 'PANTEK'),
(3046, '2', '08', '02', '02', '001', 'PANTEK'),
(3047, '2', '08', '02', '02', '002', 'SONDIR'),
(3048, '2', '08', '02', '02', '999', 'PANTEK LAINNYA'),
(3049, '2', '08', '02', '03', '000', 'PUTAR'),
(3050, '2', '08', '02', '03', '001', 'PUTAR'),
(3051, '2', '08', '02', '03', '002', 'BAND HIDROLIK'),
(3052, '2', '08', '02', '03', '999', 'PUTAR LAINNYA'),
(3053, '2', '08', '02', '04', '000', 'PERALATAN BANTU'),
(3054, '2', '08', '02', '04', '001', 'DRAWWORK'),
(3055, '2', '08', '02', '04', '002', 'DRILL PIPE'),
(3056, '2', '08', '02', '04', '003', 'DRILL CILLAR'),
(3057, '2', '08', '02', '04', '004', 'KELLY'),
(3058, '2', '08', '02', '04', '005', 'CEMETING UNIT'),
(3059, '2', '08', '02', '04', '006', 'ROTARY TABLE (PERALATAN BANTU)'),
(3060, '2', '08', '02', '04', '007', 'TUBING SLIP'),
(3061, '2', '08', '02', '04', '008', 'TUBING SPINDER'),
(3062, '2', '08', '02', '04', '009', 'ALAT PANCING'),
(3063, '2', '08', '02', '04', '010', 'SWIVEL'),
(3064, '2', '08', '02', '04', '011', 'MUD TANK'),
(3065, '2', '08', '02', '04', '999', 'PERALATAN BANTU LAINNYA'),
(3066, '2', '08', '02', '99', '000', 'ALAT PENGEBORAN NON MESIN LAINNYA'),
(3067, '2', '08', '02', '99', '999', 'ALAT PENGEBORAN NON MESIN LAINNYA'),
(3068, '2', '09', '00', '00', '000', '\"ALAT PRODUKSI'),
(3069, '2', '09', '01', '00', '000', 'SUMUR'),
(3070, '2', '09', '01', '01', '000', 'PERALATAN SUMUR MINYAK'),
(3071, '2', '09', '01', '01', '001', 'ALAT PERAWAT SUMUR'),
(3072, '2', '09', '01', '01', '002', 'AMERADA TEST'),
(3073, '2', '09', '01', '01', '003', 'SONOLOG'),
(3074, '2', '09', '01', '01', '004', 'PERFORMING UNIT'),
(3075, '2', '09', '01', '01', '005', 'LOGGING UNIT'),
(3076, '2', '09', '01', '01', '006', 'SAND PUMP'),
(3077, '2', '09', '01', '01', '999', 'PERALATAN SUMUR MINYAK LAINNYA'),
(3078, '2', '09', '01', '02', '000', 'SUMUR PEMBORAN'),
(3079, '2', '09', '01', '02', '001', 'SUMUR PEMBORAN PANAS BUMI'),
(3080, '2', '09', '01', '02', '002', 'SUMUR PEMBORAN GAS'),
(3081, '2', '09', '01', '02', '003', 'SUMUR PEMBORAN AIR'),
(3082, '2', '09', '01', '02', '999', 'SUMUR PEMBORAN LAINNYA'),
(3083, '2', '09', '01', '99', '000', 'SUMUR LAINNYA'),
(3084, '2', '09', '01', '99', '999', 'SUMUR LAINNYA'),
(3085, '2', '09', '02', '00', '000', 'PRODUKSI'),
(3086, '2', '09', '02', '01', '000', 'R I G'),
(3087, '2', '09', '02', '01', '001', 'STANG BOR'),
(3088, '2', '09', '02', '01', '999', 'R I G LAINNYA'),
(3089, '2', '09', '02', '99', '000', 'PRODUKSI LAINNYA'),
(3090, '2', '09', '02', '99', '999', 'PRODUKSI LAINNYA'),
(3091, '2', '09', '03', '00', '000', 'PENGOLAHAN DAN PEMURNIAN'),
(3092, '2', '09', '03', '01', '000', 'ALAT PENGOLAHAN MINYAK'),
(3093, '2', '09', '03', '01', '001', 'KAPASITAS KECIL (ALAT PENGOLAHAN MINYAK)'),
(3094, '2', '09', '03', '01', '002', 'KAPASITAS SEDANG (ALAT PENGOLAHAN MINYAK)'),
(3095, '2', '09', '03', '01', '003', 'KAPASITAS BESAR (ALAT PENGOLAHAN MINYAK)'),
(3096, '2', '09', '03', '01', '004', 'CALON EVAPORATOR'),
(3097, '2', '09', '03', '01', '005', 'CONDENSOR (ALAT PENGOLAHAN MINYAK)'),
(3098, '2', '09', '03', '01', '006', 'COOLER (ALAT PENGOLAHAN MINYAK)'),
(3099, '2', '09', '03', '01', '007', 'POMPA PROSO'),
(3100, '2', '09', '03', '01', '008', 'TURBINE'),
(3101, '2', '09', '03', '01', '009', 'AIR DRYER'),
(3102, '2', '09', '03', '01', '010', 'BOILER'),
(3103, '2', '09', '03', '01', '999', 'ALAT PENGOLAHAN MINYAK LAINNYA'),
(3104, '2', '09', '03', '02', '000', 'ALAT PENGOLAHAN AIR'),
(3105, '2', '09', '03', '02', '001', 'KAPASITAS KECIL (ALAT PENGOLAHAN AIR)'),
(3106, '2', '09', '03', '02', '002', 'KAPASITAS SEDANG (ALAT PENGOLAHAN AIR)'),
(3107, '2', '09', '03', '02', '003', 'KAPASITAS BESAR (ALAT PENGOLAHAN AIR)'),
(3108, '2', '09', '03', '02', '999', 'ALAT PENGOLAHAN AIR LAINNYA'),
(3109, '2', '09', '03', '03', '000', 'ALAT PENGOLAHAN STEAM'),
(3110, '2', '09', '03', '03', '001', 'KAPASITAS KECIL (ALAT PENGOLAHAN STEAM)'),
(3111, '2', '09', '03', '03', '002', 'KAPASITAS SEDANG (ALAT PENGOLAHAN STEAM)'),
(3112, '2', '09', '03', '03', '003', 'KAPASITAS BESAR (ALAT PENGOLAHAN STEAM)'),
(3113, '2', '09', '03', '03', '999', 'ALAT PENGOLAHAN STEAM LAINNYA'),
(3114, '2', '09', '03', '04', '000', 'ALAT PENGOLAHAN WAX'),
(3115, '2', '09', '03', '04', '001', 'KAPASITAS KECIL (ALAT PENGOLAHAN WAX)'),
(3116, '2', '09', '03', '04', '002', 'KAPASITAS SEDANG (ALAT PENGOLAHAN WAX)'),
(3117, '2', '09', '03', '04', '003', 'KAPASITAS BESAR (ALAT PENGOLAHAN WAX)'),
(3118, '2', '09', '03', '04', '999', 'ALAT PENGOLAHAN WAX LAINNYA'),
(3119, '2', '09', '03', '99', '000', 'PENGOLAHAN DAN PEMURNIAN LAINNYA'),
(3120, '2', '09', '03', '99', '999', 'PENGOLAHAN DAN PEMURNIAN LAINNYA'),
(3121, '2', '10', '00', '00', '000', 'PERALATAN OLAH RAGA'),
(3122, '2', '10', '01', '00', '000', 'PERALATAN OLAH RAGA'),
(3123, '2', '10', '01', '01', '000', 'PERALATAN OLAH RAGA ATLETIK'),
(3124, '2', '10', '01', '01', '001', 'LEMPAR CAKRAM'),
(3125, '2', '10', '01', '01', '002', 'LEMPAR LEMBING'),
(3126, '2', '10', '01', '01', '003', 'TOLAK PELURU'),
(3127, '2', '10', '01', '01', '004', 'ALAT LARI GAWANG'),
(3128, '2', '10', '01', '01', '005', 'GALAH'),
(3129, '2', '10', '01', '01', '006', 'MARTIL'),
(3130, '2', '10', '01', '01', '007', 'MISTAR LOMPAT TINGGI'),
(3131, '2', '10', '01', '01', '008', 'MATRAS LARI'),
(3132, '2', '10', '01', '01', '009', 'START BLOCK'),
(3133, '2', '10', '01', '01', '010', 'METER LINE'),
(3134, '2', '10', '01', '01', '011', 'BENDERA START'),
(3135, '2', '10', '01', '01', '012', 'PULL MASTER'),
(3136, '2', '10', '01', '01', '013', 'KOSTUM'),
(3137, '2', '10', '01', '01', '014', 'BAK LOMPAT TINGGI'),
(3138, '2', '10', '01', '01', '015', 'BAK LOMPAT JAUH'),
(3139, '2', '10', '01', '01', '999', 'PERALATAN ATLETIK LAINNYA'),
(3140, '2', '10', '01', '02', '000', 'PERALATAN PERMAINAN'),
(3141, '2', '10', '01', '02', '001', 'ALAT TENIS MEJA'),
(3142, '2', '10', '01', '02', '002', 'ALAT VOLLEY'),
(3143, '2', '10', '01', '02', '003', 'ALAT BILYARD'),
(3144, '2', '10', '01', '02', '004', 'ALAT BADMINTON'),
(3145, '2', '10', '01', '02', '005', 'SEPATU RODA'),
(3146, '2', '10', '01', '02', '006', 'BOLA KAKI'),
(3147, '2', '10', '01', '02', '007', 'ALAT BASKET'),
(3148, '2', '10', '01', '02', '008', 'BOLA BASKET'),
(3149, '2', '10', '01', '02', '009', 'KERANJANG BOLA/RING'),
(3150, '2', '10', '01', '02', '010', 'KOSTUM BASKET'),
(3151, '2', '10', '01', '02', '011', 'KOSTUM SEPAK BOLA'),
(3152, '2', '10', '01', '02', '012', 'SEPATU BOLA + KAOS KAKI'),
(3153, '2', '10', '01', '02', '013', 'RAKET TENIS'),
(3154, '2', '10', '01', '02', '014', 'NET TENIS'),
(3155, '2', '10', '01', '02', '015', 'BOLA TENIS'),
(3156, '2', '10', '01', '02', '016', 'ROOT'),
(3157, '2', '10', '01', '02', '999', 'PERALATAN PERMAINAN LAINNYA'),
(3158, '2', '10', '01', '03', '000', 'PERALATAN SENAM'),
(3159, '2', '10', '01', '03', '001', 'PALANG SEJAJAR'),
(3160, '2', '10', '01', '03', '002', 'PALANG KUDA'),
(3161, '2', '10', '01', '03', '003', 'MATRAS'),
(3162, '2', '10', '01', '03', '004', 'GELANG-GELANG'),
(3163, '2', '10', '01', '03', '005', 'PERALATAN FITNES'),
(3164, '2', '10', '01', '03', '006', 'KUDA PELANA'),
(3165, '2', '10', '01', '03', '007', 'BALANCE BEEM'),
(3166, '2', '10', '01', '03', '008', 'MULTI STATION'),
(3167, '2', '10', '01', '03', '009', 'ARGOCYCLE'),
(3168, '2', '10', '01', '03', '010', 'TREADMILL'),
(3169, '2', '10', '01', '03', '011', 'ORBITREK'),
(3170, '2', '10', '01', '03', '012', 'HENG UP BOARD'),
(3171, '2', '10', '01', '03', '013', 'SIT UP BOARD'),
(3172, '2', '10', '01', '03', '014', 'BECK UP BOARD'),
(3173, '2', '10', '01', '03', '015', 'DAMBLE SET'),
(3174, '2', '10', '01', '03', '016', 'BARBLE SET'),
(3175, '2', '10', '01', '03', '017', 'RAK DAMBLE SET'),
(3176, '2', '10', '01', '03', '018', 'RAK DARBLE SET'),
(3177, '2', '10', '01', '03', '019', 'MASSAGE CHAIR'),
(3178, '2', '10', '01', '03', '020', 'MASSAGE FOOT'),
(3179, '2', '10', '01', '03', '999', 'PERALATAN SENAM LAINNYA'),
(3180, '2', '10', '01', '04', '000', 'PARALATAN OLAH RAGA AIR'),
(3181, '2', '10', '01', '04', '001', 'SKI AIR'),
(3182, '2', '10', '01', '04', '002', 'SKI DIVING'),
(3183, '2', '10', '01', '04', '003', 'SELANCAR'),
(3184, '2', '10', '01', '04', '004', 'PERAHU KARET (PARALATAN OLAH RAGA AIR)'),
(3185, '2', '10', '01', '04', '005', 'PERAHU LAYAR'),
(3186, '2', '10', '01', '04', '006', 'ALAT ARUNG JERAM'),
(3187, '2', '10', '01', '04', '007', 'ALAT DAYUNG'),
(3188, '2', '10', '01', '04', '008', 'KACA MATA AIR'),
(3189, '2', '10', '01', '04', '009', 'FULL FOOT FIN'),
(3190, '2', '10', '01', '04', '010', 'ALAT UKUR KEDALAMAN'),
(3191, '2', '10', '01', '04', '011', 'BOUYANCE KOMPENSATOR'),
(3192, '2', '10', '01', '04', '012', 'HP KOMPRESSOR'),
(3193, '2', '10', '01', '04', '013', 'KOMPAS SELAM'),
(3194, '2', '10', '01', '04', '014', 'PISAU SELAM'),
(3195, '2', '10', '01', '04', '015', 'PERAYU KAYAK 1'),
(3196, '2', '10', '01', '04', '016', 'PERAYU KAYAK 2'),
(3197, '2', '10', '01', '04', '017', 'PERAHU CANO CANADIAN 1'),
(3198, '2', '10', '01', '04', '018', 'PERAHU CANO CANADIAN 2'),
(3199, '2', '10', '01', '04', '019', 'PERAHU TRADISIONAL/PERAHU NAGA'),
(3200, '2', '10', '01', '04', '020', 'ROOWING/SINGLE SCOOL'),
(3201, '2', '10', '01', '04', '021', 'ROOWING/DOUBLE SCOOL'),
(3202, '2', '10', '01', '04', '022', 'PAPAN JUMPING + TALI + HELM'),
(3203, '2', '10', '01', '04', '023', 'PAPAN SLALOM + TALI + HELM'),
(3204, '2', '10', '01', '04', '024', 'PAPAN TRICK + TALI + HELM'),
(3205, '2', '10', '01', '04', '025', 'JAMPING TRACK'),
(3206, '2', '10', '01', '04', '026', 'MOTOR PENARIK/SPEED BOAT'),
(3207, '2', '10', '01', '04', '027', 'PELAMPUNG LINTASAN'),
(3208, '2', '10', '01', '04', '028', 'OPTIMIST'),
(3209, '2', '10', '01', '04', '029', 'ENTERPRISE'),
(3210, '2', '10', '01', '04', '030', 'KELAS 420'),
(3211, '2', '10', '01', '04', '031', 'KELAS 470'),
(3212, '2', '10', '01', '04', '032', 'FIREBALL'),
(3213, '2', '10', '01', '04', '033', 'SELANCAR ANGIN'),
(3214, '2', '10', '01', '04', '034', 'HOBBY CAT'),
(3215, '2', '10', '01', '04', '035', 'KIIL BOAT'),
(3216, '2', '10', '01', '04', '999', 'PARALATAN OLAH RAGA AIR LAINNYA'),
(3217, '2', '10', '01', '05', '000', 'PERALATAN OLAH RAGA UDARA'),
(3218, '2', '10', '01', '05', '001', 'GANTOLE'),
(3219, '2', '10', '01', '05', '002', 'BALON UDARA'),
(3220, '2', '10', '01', '05', '003', 'PAYUNG UDARA (PARASUT)'),
(3221, '2', '10', '01', '05', '004', 'ALAT TERBANG LAYANG'),
(3222, '2', '10', '01', '05', '999', 'PERALATAN OLAH RAGA UDARA LAINNYA'),
(3223, '2', '10', '01', '06', '000', 'PERALATAN OLAH RAGA LAINNYA'),
(3224, '2', '10', '01', '06', '001', 'CATUR'),
(3225, '2', '10', '01', '06', '002', 'SARUNG TINJU'),
(3226, '2', '10', '01', '06', '003', 'SEPEDA OLAH RAGA'),
(3227, '2', '10', '01', '06', '999', 'PERALATAN OLAH RAGA LAINNYA'),
(3228, '2', '10', '01', '99', '999', 'PERALATAN OLAH RAGA LAINNYA'),
(3229, '3', '00', '00', '00', '000', 'GEDUNG DAN BANGUNAN'),
(3230, '3', '01', '00', '00', '000', 'BANGUNAN GEDUNG'),
(3231, '3', '01', '01', '00', '000', 'BANGUNAN GEDUNG TEMPAT KERJA'),
(3232, '3', '01', '01', '01', '000', 'BANGUNAN GEDUNG KANTOR'),
(3233, '3', '01', '01', '01', '001', 'BANGUNAN GEDUNG KANTOR PERMANEN'),
(3234, '3', '01', '01', '01', '002', 'BANGUNAN GEDUNG KANTOR SEMI PERMANEN'),
(3235, '3', '01', '01', '01', '003', 'BANGUNAN GEDUNG KANTOR DARURAT'),
(3236, '3', '01', '01', '01', '004', 'RUMAH PANEL'),
(3237, '3', '01', '01', '01', '999', 'BANGUNAN GEDUNG KANTOR LAINNYA'),
(3238, '3', '01', '01', '02', '000', 'BANGUNAN GUDANG'),
(3239, '3', '01', '01', '02', '001', 'BANGUNAN GUDANG TERTUTUP PERMANEN'),
(3240, '3', '01', '01', '02', '002', 'BANGUNAN GUDANG TERTUTUP SEMI PERMANEN'),
(3241, '3', '01', '01', '02', '003', 'BANGUNAN GUDANG TERTUTUP DARURAT'),
(3242, '3', '01', '01', '02', '004', 'BANGUNAN GUDANG TERBUKA PERMANEN'),
(3243, '3', '01', '01', '02', '005', 'BANGUNAN GUDANG TERBUKA SEMI PERMANEN'),
(3244, '3', '01', '01', '02', '006', 'BANGUNAN GUDANG TERBUKA DARURAT'),
(3245, '3', '01', '01', '02', '999', 'BANGUNAN GUDANG LAINNYA'),
(3246, '3', '01', '01', '03', '000', 'BANGUNAN GEDUNG UNTUK BENGKEL'),
(3247, '3', '01', '01', '03', '001', 'BANGUNAN BENGKEL PERMANEN'),
(3248, '3', '01', '01', '03', '002', 'BANGUNAN BENGKEL  SEMI PERMANEN');
INSERT INTO `tweb_aset` (`id_aset`, `golongan`, `bidang`, `kelompok`, `sub_kelompok`, `sub_sub_kelompok`, `nama`) VALUES
(3249, '3', '01', '01', '03', '003', 'BANGUNAN BENGKEL  DARURAT'),
(3250, '3', '01', '01', '03', '999', 'BANGUNAN GEDUNG UNTUK BENGKEL LAINNYA'),
(3251, '3', '01', '01', '04', '000', 'BANGUNAN GEDUNG INSTALASI'),
(3252, '3', '01', '01', '04', '001', 'GEDUNG INSTALASI STUDIO'),
(3253, '3', '01', '01', '04', '002', 'GEDUNG INSTALASI PEMANCAR'),
(3254, '3', '01', '01', '04', '999', 'BANGUNAN GEDUNG INSTALASI LAINNYA'),
(3255, '3', '01', '01', '05', '000', 'BANGUNAN GEDUNG LABORATORIUM'),
(3256, '3', '01', '01', '05', '001', 'BANGUNAN GEDUNG LABORATORIUM PERMANEN'),
(3257, '3', '01', '01', '05', '002', 'BANGUNAN GEDUNG LABORATORIUM SEMI PERMANEN'),
(3258, '3', '01', '01', '05', '003', 'BANGUNAN GEDUNG LABORATORIUM DARURAT'),
(3259, '3', '01', '01', '05', '999', 'BANGUNAN GEDUNG LABORATORIUM LAINNYA'),
(3260, '3', '01', '01', '06', '000', 'BANGUNAN KESEHATAN'),
(3261, '3', '01', '01', '06', '001', 'BANGUNAN POSYANDU'),
(3262, '3', '01', '01', '06', '002', 'BANGUNAN POLINDES (PONDOK BERSALIN DESA)'),
(3263, '3', '01', '01', '06', '003', 'BANGUNAN APOTIK'),
(3264, '3', '01', '01', '06', '004', 'BANGUNAN TOKO KHUSUS OBAT/JAMU'),
(3265, '3', '01', '01', '06', '999', 'BANGUNAN KESEHATAN LAINNYA'),
(3266, '3', '01', '01', '07', '000', 'BANGUNAN GEDUNG TEMPAT IBADAH'),
(3267, '3', '01', '01', '07', '001', 'BANGUNAN GEDUNG TEMPAT IBADAH PERMANEN'),
(3268, '3', '01', '01', '07', '002', 'BANGUNAN GEDUNG TEMPAT IBADAH SEMI PERMANEN'),
(3269, '3', '01', '01', '07', '003', 'BANGUNAN GEDUNG TEMPAT IBADAH DARURAT'),
(3270, '3', '01', '01', '07', '999', 'BANGUNAN GEDUNG TEMPAT IBADAH LAINNYA'),
(3271, '3', '01', '01', '08', '000', 'BANGUNAN GEDUNG TEMPAT PERTEMUAN'),
(3272, '3', '01', '01', '08', '001', 'BANGUNAN GEDUNG PERTEMUAN PERMANEN'),
(3273, '3', '01', '01', '08', '002', 'BANGUNAN GEDUNG PERTEMUAN SEMI PERMANEN'),
(3274, '3', '01', '01', '08', '003', 'BANGUNAN GEDUNG PERTEMUAN DARURAT'),
(3275, '3', '01', '01', '08', '999', 'BANGUNAN GEDUNG TEMPAT PERTEMUAN LAINNYA'),
(3276, '3', '01', '01', '09', '000', 'BANGUNAN GEDUNG TEMPAT PENDIDIKAN'),
(3277, '3', '01', '01', '09', '001', 'BANGUNAN GEDUNG PENDIDIKAN PERMANEN'),
(3278, '3', '01', '01', '09', '002', 'BANGUNAN GEDUNG PENDIDIKAN SEMI PERMANEN'),
(3279, '3', '01', '01', '09', '003', 'BANGUNAN GEDUNG PENDIDIKAN DARURAT'),
(3280, '3', '01', '01', '09', '004', 'BANGUNAN GEDUNG PENDIDIKAN DAN LATIHAN'),
(3281, '3', '01', '01', '09', '999', 'BANGUNAN GEDUNG TEMPAT PENDIDIKAN LAINNYA'),
(3282, '3', '01', '01', '10', '000', 'BANGUNAN GEDUNG TEMPAT OLAH RAGA'),
(3283, '3', '01', '01', '10', '001', 'GEDUNG OLAH RAGA TETUTUP PERMANEN'),
(3284, '3', '01', '01', '10', '002', 'GEDUNG OLAH RAGA TERTUTUP SEMI PERMANEN'),
(3285, '3', '01', '01', '10', '003', 'GEDUNG OLAH RAGA TERTUTUP DARURAT'),
(3286, '3', '01', '01', '10', '004', 'BANGUNAN OLAH RAGA TERBUKA PERMANEN'),
(3287, '3', '01', '01', '10', '005', 'BANGUNAN OLAH RAGA TERBUKA SEMI PERMANEN'),
(3288, '3', '01', '01', '10', '006', 'BANGUNAN OLAH RAGA TERBUKA DARURAT'),
(3289, '3', '01', '01', '10', '007', 'BANGUNAN GEDUNG OLAH RAGA KOLAM RENANG'),
(3290, '3', '01', '01', '10', '999', 'BANGUNAN GEDUNG TEMPAT OLAH RAGA LAINNYA'),
(3291, '3', '01', '01', '11', '000', 'BANGUNAN GEDUNG PERTOKOAN/KOPERASI/PASAR'),
(3292, '3', '01', '01', '11', '001', 'GEDUNG PERTOKOAN/KOPERASI/PASAR PERMANEN'),
(3293, '3', '01', '01', '11', '002', 'GEDUNG PERTOKOAN/KOPERASI/PASAR SEMI PERMANEN'),
(3294, '3', '01', '01', '11', '003', 'GEDUNG PERTOKOAN/KOPERASI/PASAR DARURAT'),
(3295, '3', '01', '01', '11', '999', 'BANGUNAN GEDUNG PERTOKOAN/KOPERASI/PASAR LAINNYA'),
(3296, '3', '01', '01', '12', '000', 'BANGUNAN GEDUNG GARASI/POOL'),
(3297, '3', '01', '01', '12', '001', 'GEDUNG GARASI/POOL PERMANEN'),
(3298, '3', '01', '01', '12', '002', 'GEDUNG GARASI/POOL SEMI PERMANEN'),
(3299, '3', '01', '01', '12', '003', 'GEDUNG GARASI/POOL DARURAT'),
(3300, '3', '01', '01', '12', '999', 'BANGUNAN GEDUNG GARASI/POOL LAINNYA'),
(3301, '3', '01', '01', '13', '000', 'BANGUNAN GEDUNG PEMOTONG HEWAN'),
(3302, '3', '01', '01', '13', '001', 'GEDUNG PEMOTONG HEWAN PERMANEN'),
(3303, '3', '01', '01', '13', '002', 'GEDUNG PEMOTONG HEWAN SEMI PERMANEN'),
(3304, '3', '01', '01', '13', '003', 'GEDUNG PEMOTONG HEWAN DARURAT'),
(3305, '3', '01', '01', '13', '999', 'BANGUNAN GEDUNG PEMOTONG HEWAN LAINNYA'),
(3306, '3', '01', '01', '14', '000', 'BANGUNAN GEDUNG PERPUSTAKAAN'),
(3307, '3', '01', '01', '14', '001', 'BANGUNAN GEDUNG PERPUSTAKAAN PERMANEN'),
(3308, '3', '01', '01', '14', '002', 'BANGUNAN GEDUNG PERPUSTAKAAN SEMI PERMANEN'),
(3309, '3', '01', '01', '14', '003', 'BANGUNAN GEDUNG PERPUSTAKAAN DARURAT'),
(3310, '3', '01', '01', '14', '999', 'BANGUNAN GEDUNG PERPUSTAKAAN LAINNYA'),
(3311, '3', '01', '01', '15', '000', 'BANGUNAN GEDUNG MUSIUM'),
(3312, '3', '01', '01', '15', '001', 'BANGUNAN GEDUNG MUSIUM PERMANEN'),
(3313, '3', '01', '01', '15', '002', 'BANGUNAN GEDUNG MUSIUM SEMI PERMANEN'),
(3314, '3', '01', '01', '15', '003', 'BANGUNAN GEDUNG MUSIUM DARURAT'),
(3315, '3', '01', '01', '15', '999', 'BANGUNAN GEDUNG MUSIUM LAINNYA'),
(3316, '3', '01', '01', '16', '000', 'BANGUNAN GEDUNG TERMINAL/PELABUHAN'),
(3317, '3', '01', '01', '16', '001', 'BANGUNAN GEDUNG TERMINAL/PELABUHAN PERMANEN'),
(3318, '3', '01', '01', '16', '002', 'BANGUNAN GEDUNG TERMINAL/PELABUHAN SEMI PERMANEN'),
(3319, '3', '01', '01', '16', '003', 'BANGUNAN GEDUNG TERMINAL/PELABUHAN DARURAT'),
(3320, '3', '01', '01', '16', '004', 'BANGUNAN HALTE/SHELTER'),
(3321, '3', '01', '01', '16', '999', 'BANGUNAN GEDUNG TERMINAL/PELABUHAN LAINNYA'),
(3322, '3', '01', '01', '17', '000', 'BANGUNAN TERBUKA'),
(3323, '3', '01', '01', '17', '001', 'BANGUNAN LANTAI JEMUR PERMANEN'),
(3324, '3', '01', '01', '17', '002', 'BANGUNAN LANTAI JEMUR SEMI PERMANEN'),
(3325, '3', '01', '01', '17', '003', 'BANGUNAN LANTAI JEMUR DARURAT'),
(3326, '3', '01', '01', '17', '999', 'BANGUNAN TERBUKA LAINNYA'),
(3327, '3', '01', '01', '18', '000', 'BANGUNAN PENAMPUNG SEKAM'),
(3328, '3', '01', '01', '18', '001', 'BANGUNAN PENAMPUNG SEKAM PERMANEN'),
(3329, '3', '01', '01', '18', '002', 'BANGUNAN PENAMPUNG SEKAM SEMI PERMANEN'),
(3330, '3', '01', '01', '18', '003', 'BANGUNAN PENAMPUNG SEKAM DARURAT'),
(3331, '3', '01', '01', '18', '999', 'BANGUNAN PENAMPUNG SEKAM LAINNYA'),
(3332, '3', '01', '01', '19', '000', 'BANGUNAN TEMPAT PELELANGAN IKAN (TPI)'),
(3333, '3', '01', '01', '19', '001', 'BANGUNAN TPI PERMANEN'),
(3334, '3', '01', '01', '19', '002', 'BANGUNAN TPI SEMI PERMANEN'),
(3335, '3', '01', '01', '19', '003', 'BANGUNAN TPI DARURAT'),
(3336, '3', '01', '01', '19', '999', 'BANGUNAN TEMPAT PELELANGAN IKAN (TPI) LAINNYA'),
(3337, '3', '01', '01', '20', '000', 'BANGUNAN INDUSTRI'),
(3338, '3', '01', '01', '20', '001', 'BANGUNAN INDUSTRI MAKANAN'),
(3339, '3', '01', '01', '20', '002', 'BANGUNAN INDUSTRI MINUMAN'),
(3340, '3', '01', '01', '20', '003', 'BANGUNAN INDUSTRI ALAT RT'),
(3341, '3', '01', '01', '20', '004', 'BANGUNAN INDUSTRI PAKAIAN/GARMENT'),
(3342, '3', '01', '01', '20', '005', 'BANGUNAN INDUSTRI BAJA/BESI/LOGAM'),
(3343, '3', '01', '01', '20', '006', 'BANGUNAN INDUSTRI PENGEMASAN'),
(3344, '3', '01', '01', '20', '007', 'BANGUNAN INDUSTRI BENGKEL'),
(3345, '3', '01', '01', '20', '008', 'BANGUNAN INDUSTRI PENYULINGAN MINYAK'),
(3346, '3', '01', '01', '20', '009', 'BANGUNAN INDUSTRI KIMIA DAN PUPUK'),
(3347, '3', '01', '01', '20', '010', 'BANGUNAN INDUSTRI OBAT-OBATAN'),
(3348, '3', '01', '01', '20', '011', 'BANGUNAN INDUSTRI SEMEN'),
(3349, '3', '01', '01', '20', '012', 'BANGUNAN INDUSTRI BATU-BATA/BATAKO'),
(3350, '3', '01', '01', '20', '013', 'BANGUNAN INDUSTRI GENTENG'),
(3351, '3', '01', '01', '20', '014', 'BANGUNAN INDUSTRI PERCETAKAN'),
(3352, '3', '01', '01', '20', '015', 'BANGUNAN INDUSTRI TEKSTIL'),
(3353, '3', '01', '01', '20', '016', 'BANGUNAN INDUSTRI ALAT OLAH RAGA'),
(3354, '3', '01', '01', '20', '017', 'BANGUNAN INDUSTRI KENDARAAN/OTOMOTIF'),
(3355, '3', '01', '01', '20', '018', 'BANGUNAN INDUSTRI KERAMIK/MARMER'),
(3356, '3', '01', '01', '20', '019', 'BANGUNAN PABRIK ES'),
(3357, '3', '01', '01', '20', '020', 'BANGUNAN PASAR IKAN HIGIENIS/ PIH'),
(3358, '3', '01', '01', '20', '021', 'BANGUNAN DEPO PASAR IKAN'),
(3359, '3', '01', '01', '20', '022', 'BANGUNAN PASAR/ RAISER IKAN HIAS'),
(3360, '3', '01', '01', '20', '999', 'BANGUNAN INDUSTRI LAINNYA'),
(3361, '3', '01', '01', '21', '000', 'BANGUNAN PETERNAKAN/PERIKANAN'),
(3362, '3', '01', '01', '21', '001', 'BANGUNAN UNTUK KANDANG'),
(3363, '3', '01', '01', '21', '002', 'BANGUNAN KOLAM/BAK IKAN'),
(3364, '3', '01', '01', '21', '003', 'BANGUNAN PEMBESAR IKAN'),
(3365, '3', '01', '01', '21', '999', 'BANGUNAN PETERNAKAN/PERIKANAN LAINNYA'),
(3366, '3', '01', '01', '22', '000', 'BANGUNAN GEDUNG TEMPAT KERJA LAINNYA'),
(3367, '3', '01', '01', '22', '001', 'BANGUNAN GEDUNG TEMPAT KERJA LAINNYA PERMANEN'),
(3368, '3', '01', '01', '22', '002', 'BANGUNAN GEDUNG TEMPAT KERJA LAINNYA SEMI PERMANEN'),
(3369, '3', '01', '01', '22', '003', 'BANGUNAN GEDUNG TEMPAT KERJA LAINNYA DARURAT'),
(3370, '3', '01', '01', '22', '004', 'GEDUNG PENGUJIAN KENDARAAN LAINNYA'),
(3371, '3', '01', '01', '22', '999', 'BANGUNAN LAINNYA'),
(3372, '3', '01', '01', '23', '000', 'BANGUNAN FASILITAS UMUM'),
(3373, '3', '01', '01', '23', '001', 'BANGUNAN TEMPAT PARKIR'),
(3374, '3', '01', '01', '23', '002', 'BANGUNAN TEMPAT BERMAIN ANAK'),
(3375, '3', '01', '01', '23', '003', 'BANGUNAN PENERANGAN JALAN'),
(3376, '3', '01', '01', '23', '004', 'BANGUNAN PENERANGAN TAMAN'),
(3377, '3', '01', '01', '23', '999', 'BANGUNAN FASILITAS UMUM LAINNYA'),
(3378, '3', '01', '01', '24', '000', 'BANGUNAN PARKIR'),
(3379, '3', '01', '01', '24', '001', 'BANGUNAN PARKIR TERBUKA PERMANEN'),
(3380, '3', '01', '01', '24', '002', 'BANGUNAN PARKIR TERBUKA SEMI PERMANEN'),
(3381, '3', '01', '01', '24', '003', 'BANGUNAN PARKIR TERBUKA DARURAT'),
(3382, '3', '01', '01', '24', '004', 'BANGUNAN PARKIR TERTUTUP PERMANEN'),
(3383, '3', '01', '01', '24', '005', 'BANGUNAN PARKIR TERTUTUP SEMI PERMANEN'),
(3384, '3', '01', '01', '24', '006', 'BANGUNAN PARKIR TERTUTUP DARURAT'),
(3385, '3', '01', '01', '24', '999', 'BANGUNAN PARKIR LAINNYA'),
(3386, '3', '01', '01', '25', '000', 'TAMAN'),
(3387, '3', '01', '01', '25', '001', 'TAMAN PERMANEN'),
(3388, '3', '01', '01', '25', '002', 'TAMAN SEMI PERMANEN'),
(3389, '3', '01', '01', '25', '999', 'TAMAN LAINNYA'),
(3390, '3', '01', '01', '99', '000', 'BANGUNAN GEDUNG TEMPAT KERJA LAINNYA'),
(3391, '3', '01', '01', '99', '999', 'BANGUNAN GEDUNG TEMPAT KERJA LAINNYA'),
(3392, '3', '01', '02', '01', '000', 'HOTEL'),
(3393, '3', '01', '02', '01', '001', 'HOTEL PERMANEN'),
(3394, '3', '01', '02', '01', '002', 'HOTEL SEMI PERMANEN'),
(3395, '3', '01', '02', '01', '999', 'HOTEL LAINNYA'),
(3396, '3', '01', '02', '02', '000', 'MOTEL'),
(3397, '3', '01', '02', '02', '001', 'MOTEL PERMANEN'),
(3398, '3', '01', '02', '02', '002', 'MOTEL SEMI PERMANEN'),
(3399, '3', '01', '02', '02', '999', 'MOTEL LAINNYA'),
(3400, '3', '01', '02', '03', '000', 'PANTI ASUHAN'),
(3401, '3', '01', '02', '03', '001', 'PANTI ASUHAN'),
(3402, '3', '01', '02', '03', '999', 'PANTI ASUHAN LAINNYA'),
(3403, '3', '01', '02', '99', '000', 'BANGUNAN GEDUNG TEMPAT TINGGAL LAINNYA'),
(3404, '3', '01', '02', '99', '999', 'BANGUNAN GEDUNG TEMPAT TINGGAL LAINNYA'),
(3405, '3', '02', '00', '00', '000', 'MONUMEN'),
(3406, '3', '02', '01', '00', '000', 'CANDI/TUGU PERINGATAN/PRASASTI'),
(3407, '3', '02', '01', '01', '000', 'CANDI'),
(3408, '3', '02', '01', '01', '001', 'CANDI'),
(3409, '3', '02', '01', '01', '999', 'CANDI LAINNYA'),
(3410, '3', '02', '01', '02', '000', 'TUGU'),
(3411, '3', '02', '01', '02', '001', 'TUGU KEMERDEKAAN'),
(3412, '3', '02', '01', '02', '002', 'TUGU PEMBANGUNAN'),
(3413, '3', '02', '01', '02', '999', 'TUGU PERINGATAN LAINNYA'),
(3414, '3', '02', '01', '03', '000', 'BANGUNAN PENINGGALAN'),
(3415, '3', '02', '01', '03', '001', 'ISTANA PENINGGALAN'),
(3416, '3', '02', '01', '03', '002', 'RUMAH ADAT'),
(3417, '3', '02', '01', '03', '003', 'RUMAH PENINGGALAN SEJARAH'),
(3418, '3', '02', '01', '03', '004', 'MAKAM BERSEJARAH'),
(3419, '3', '02', '01', '03', '999', 'BANGUNAN PENINGGALAN LAINNYA'),
(3420, '3', '02', '01', '99', '000', 'CANDI/TUGU PERINGATAN/PRASASTI LAINNYA'),
(3421, '3', '02', '01', '99', '999', 'BANGUNAN PENINGGALAN LAINNYA'),
(3422, '3', '02', '01', '99', '999', 'CANDI/TUGU PERINGATAN/PRASASTI LAINNYA'),
(3423, '4', '00', '00', '00', '000', 'JALAN'),
(3424, '4', '01', '00', '00', '000', 'JALAN DAN JEMBATAN'),
(3425, '4', '01', '01', '00', '000', 'JALAN'),
(3426, '4', '01', '01', '01', '000', 'JALAN DESA'),
(3427, '4', '01', '01', '01', '001', 'JALAN DESA'),
(3428, '4', '01', '01', '01', '999', 'JALAN DESA LAINNYA'),
(3429, '4', '01', '01', '02', '000', 'JALAN KHUSUS'),
(3430, '4', '01', '01', '02', '001', 'JALAN KHUSUS INSPEKSI'),
(3431, '4', '01', '01', '02', '002', 'JALAN KHUSUS KOMPLEKS'),
(3432, '4', '01', '01', '02', '003', 'JALAN KHUSUS PROYEK'),
(3433, '4', '01', '01', '02', '004', 'JALAN KHUSUS QUARRY'),
(3434, '4', '01', '01', '02', '005', 'JALAN KHUSUS LORI'),
(3435, '4', '01', '01', '02', '006', 'JALAN KHUSUS BADAN HUKUM'),
(3436, '4', '01', '01', '02', '007', 'JALAN KHUSUS PERORANGAN'),
(3437, '4', '01', '01', '02', '008', 'JALAN KHUSUS LAINNYA'),
(3438, '4', '01', '01', '02', '009', 'JALAN KHUSUS PEJALAN KAKI (TROTOAR)'),
(3439, '4', '01', '01', '02', '999', 'LAINNYA (JALAN KHUSUS)'),
(3440, '4', '01', '01', '99', '000', 'JALAN LAINNYA'),
(3441, '4', '01', '01', '99', '999', 'JALAN LAINNYA'),
(3442, '4', '01', '02', '00', '000', 'JEMBATAN'),
(3443, '4', '01', '02', '01', '000', 'JEMBATAN PADA JALAN DESA'),
(3444, '4', '01', '02', '01', '001', 'JEMBATAN PADA JALAN DESA'),
(3445, '4', '01', '02', '01', '999', 'JEMBATAN PADA JALAN DESA LAINNYA'),
(3446, '4', '01', '02', '02', '000', 'JEMBATAN PADA JALAN KHUSUS'),
(3447, '4', '01', '02', '02', '001', 'JEMBATAN PADA JALAN KHUSUS INSPEKSI'),
(3448, '4', '01', '02', '02', '002', 'JEMBATAN PADA JALAN KHUSUS KOMPLEKS'),
(3449, '4', '01', '02', '02', '003', 'JEMBATAN PADA JALAN KHUSUS PROYEK'),
(3450, '4', '01', '02', '02', '004', 'JEMBATAN PADA JALAN KHUSUS QUARRY'),
(3451, '4', '01', '02', '02', '005', 'JEMBATAN PADA JALAN KHUSUS LORI'),
(3452, '4', '01', '02', '02', '006', 'JEMBATAN PADA JALAN KHUSUS BADAN HUKUM'),
(3453, '4', '01', '02', '02', '007', 'JEMBATAN PADA JALAN KHUSUS PERORANGAN'),
(3454, '4', '01', '02', '02', '999', 'JEMBATAN PADA JALAN KHUSUS LAINNYA'),
(3455, '4', '01', '02', '03', '000', 'JEMBATAN PENYEBERANGAN'),
(3456, '4', '01', '02', '03', '001', 'JEMBATAN PENYEBERANGAN ORANG'),
(3457, '4', '01', '02', '03', '002', 'JEMBATAN PENYEBERANGAN KENDARAAN'),
(3458, '4', '01', '02', '03', '005', 'JEMBATAN GANTUNG'),
(3459, '4', '01', '02', '03', '999', 'JEMBATAN PENYEBERANGAN LAINNYA'),
(3460, '4', '01', '02', '04', '000', 'JEMBATAN LABUH/SANDAR PADA TERMINAL'),
(3461, '4', '01', '02', '04', '001', 'DERMAGA'),
(3462, '4', '01', '02', '04', '002', 'KADE'),
(3463, '4', '01', '02', '04', '003', 'EMBARKASI/DEBARKASI'),
(3464, '4', '01', '02', '04', '004', 'JEMBATAN PANTAI'),
(3465, '4', '01', '02', '04', '999', 'JEMBATAN LABUH/SANDAR PADA TERMINAL LAINNYA'),
(3466, '4', '01', '02', '05', '000', 'JEMBATAN PENGUKUR'),
(3467, '4', '01', '02', '05', '001', 'JEMBATAN TIMBANG'),
(3468, '4', '01', '02', '05', '002', 'JEMBATAN KIR/PENGUJIAN'),
(3469, '4', '01', '02', '05', '999', 'JEMBATAN PENGUKUR LAINNYA'),
(3470, '4', '01', '02', '99', '000', 'JEMBATAN LAINNYA'),
(3471, '4', '01', '02', '99', '999', 'JEMBATAN LAINNYA'),
(3472, '4', '02', '00', '00', '000', 'BANGUNAN AIR'),
(3473, '4', '02', '01', '00', '000', 'BANGUNAN AIR IRIGASI'),
(3474, '4', '02', '01', '01', '000', 'BANGUNAN WADUK IRIGASI'),
(3475, '4', '02', '01', '01', '001', '\"WADUK DENGAN BENDUNGAN'),
(3476, '4', '02', '01', '01', '002', '\"WADUK DENGAN BENDUNGAN'),
(3477, '4', '02', '01', '01', '003', 'WADUK DENGAN MENARA PENGAMBILAN'),
(3478, '4', '02', '01', '01', '004', '\"WADUK DENGAN TANGGUL'),
(3479, '4', '02', '01', '01', '005', 'WADUK DENGAN TANGGUL DAN PINTU PENGUKUR WADUK LAPANGAN'),
(3480, '4', '02', '01', '01', '999', 'BANGUNAN WADUK IRIGASI LAINNYA'),
(3481, '4', '02', '01', '02', '000', 'BANGUNAN PENGAMBILAN IRIGASI'),
(3482, '4', '02', '01', '02', '001', 'BENDUNG'),
(3483, '4', '02', '01', '02', '002', 'BENDUNG DENGAN PINTU BILAS'),
(3484, '4', '02', '01', '02', '003', 'BENDUNG DENGAN POMPA'),
(3485, '4', '02', '01', '02', '004', 'BANGUNAN PENGAMBILAN BEBAS'),
(3486, '4', '02', '01', '02', '005', 'BANGUNAN PENGAMBILAN BEBAS DGN POMPA (BGNAN PENGAMBILAN IRIGASI)'),
(3487, '4', '02', '01', '02', '006', 'SUMUR DENGAN POMPA (BANGUNAN PENGAMBILAN IRIGASI)'),
(3488, '4', '02', '01', '02', '999', 'BANGUNAN PENGAMBILAN IRIGASI LAINNYA'),
(3489, '4', '02', '01', '03', '000', 'BANGUNAN PEMBAWA IRIGASI'),
(3490, '4', '02', '01', '03', '001', 'SALURAN MUKA (BANGUNAN PEMBAWA IRIGASI)'),
(3491, '4', '02', '01', '03', '002', 'SALURAN INDUK (BANGUNAN PEMBAWA IRIGASI)'),
(3492, '4', '02', '01', '03', '003', 'SALURAN SEKUNDER (BANGUNAN PEMBAWA IRIGASI)'),
(3493, '4', '02', '01', '03', '004', 'SALURAN TERSIER (BANGUNAN PEMBAWA IRIGASI)'),
(3494, '4', '02', '01', '03', '005', 'SALURAN KWARTER'),
(3495, '4', '02', '01', '03', '006', 'SALURAN PASANG TERTUTUP/TEROWONGAN'),
(3496, '4', '02', '01', '03', '007', 'SALURAN SUPLESI'),
(3497, '4', '02', '01', '03', '999', 'BANGUNAN PEMBAWA IRIGASI LAINNYA'),
(3498, '4', '02', '01', '04', '000', 'BANGUNAN PEMBUANG IRIGASI'),
(3499, '4', '02', '01', '04', '001', 'SALURAN INDUK PEMBUANG (BANGUNAN PEMBUANG IRIGASI)'),
(3500, '4', '02', '01', '04', '002', 'SALURAN SEKUNDER PEMBUANG (BANGUNAN PEMBUANG IRIGASI)'),
(3501, '4', '02', '01', '04', '003', 'SALURAN TERSIER PEMBUANG (BANGUNAN PEMBUANG IRIGASI)'),
(3502, '4', '02', '01', '04', '999', 'BANGUNAN PEMBUANG IRIGASI LAINNYA'),
(3503, '4', '02', '01', '05', '000', 'BANGUNAN PENGAMAN IRIGASI'),
(3504, '4', '02', '01', '05', '001', 'TANGGUL BANJIR (BANGUNAN PENGAMAN IRIGASI)'),
(3505, '4', '02', '01', '05', '002', 'BANGUNAN PINTU AIR/KLEP (BANGUNAN PENGAMAN IRIGASI)'),
(3506, '4', '02', '01', '05', '999', 'BANGUNAN PENGAMAN IRIGASI LAINNYA'),
(3507, '4', '02', '01', '06', '000', 'BANGUNAN PELENGKAP IRIGASI'),
(3508, '4', '02', '01', '06', '001', 'BANGUNAN BAGI'),
(3509, '4', '02', '01', '06', '002', 'BANGUNAN BAGI DAN SADAP (BANGUNAN PELENGKAP IRIGASI)'),
(3510, '4', '02', '01', '06', '003', 'BANGUNAN SADAP (BANGUNAN PELENGKAP IRIGASI)'),
(3511, '4', '02', '01', '06', '004', 'BANGUNAN GOT MIRING'),
(3512, '4', '02', '01', '06', '005', 'BANGUNAN TERJUN (BANGUNAN PELENGKAP IRIGASI)'),
(3513, '4', '02', '01', '06', '006', 'BANGUNAN TALANG (BANGUNAN PELENGKAP IRIGASI)'),
(3514, '4', '02', '01', '06', '007', 'BANGUNAN SYPHON (BANGUNAN PELENGKAP IRIGASI)'),
(3515, '4', '02', '01', '06', '008', 'BANGUNAN GORONG-GORONG (BANGUNAN PELENGKAP IRIGASI)'),
(3516, '4', '02', '01', '06', '009', 'BANGUNAN PELIMPAH SAMPAH'),
(3517, '4', '02', '01', '06', '010', 'BANGUNAN PENGELUARAN/PINTU'),
(3518, '4', '02', '01', '06', '011', 'BANGUNAN BOX TERSIER (BANGUNAN PELENGKAP IRIGASI)'),
(3519, '4', '02', '01', '06', '012', 'BANGUNAN PENGUKUR'),
(3520, '4', '02', '01', '06', '013', 'BANGUNAN MANDI HEWAN'),
(3521, '4', '02', '01', '06', '014', 'BANGUNAN PERTEMUAN SALURAN'),
(3522, '4', '02', '01', '06', '015', 'BANGUNAN PELENGKAP DALAM PETAK TERSIER'),
(3523, '4', '02', '01', '06', '016', 'BANGUNAN JEMBATAN (BANGUNAN PELENGKAP IRIGASI)'),
(3524, '4', '02', '01', '06', '999', 'BANGUNAN PELENGKAP IRIGASI LAINNYA'),
(3525, '4', '02', '01', '07', '000', 'BANGUNAN SAWAH IRIGASI'),
(3526, '4', '02', '01', '07', '001', 'BANGUNAN SAWAH IRIGASI TEHNIS'),
(3527, '4', '02', '01', '07', '002', 'BANGUNAN SAWAH IRIGASI SEMI TEHNIS'),
(3528, '4', '02', '01', '07', '003', 'BANGUNAN SAWAH IRIGASI NON TEHNIS'),
(3529, '4', '02', '01', '07', '999', 'BANGUNAN SAWAH IRIGASI LAINNYA'),
(3530, '4', '02', '01', '99', '000', 'BANGUNAN AIR IRIGASI LAINNYA'),
(3531, '4', '02', '01', '99', '999', 'BANGUNAN AIR IRIGASI LAINNYA'),
(3532, '4', '02', '02', '00', '000', 'BANGUNAN PENGAIRAN PASANG SURUT'),
(3533, '4', '02', '02', '01', '000', 'BANGUNAN WADUK PASANG SURUT'),
(3534, '4', '02', '02', '01', '001', 'WADUK PASANG SURUT'),
(3535, '4', '02', '02', '01', '999', 'BANGUNAN WADUK PASANG SURUT LAINNYA'),
(3536, '4', '02', '02', '02', '000', 'BANGUNAN PENGAMBILAN PASANG SURUT'),
(3537, '4', '02', '02', '02', '001', 'BANGUNAN BENDUNG DENGAN POMPA'),
(3538, '4', '02', '02', '02', '002', 'BANGUNAN PENGAMBILAN BEBAS DGN POMPA (BGNAN PENGAMBILAN PSG SURUT'),
(3539, '4', '02', '02', '02', '999', 'BANGUNAN PENGAMBILAN PASANG SURUT LAINNYA'),
(3540, '4', '02', '02', '03', '000', 'BANGUNAN PEMBAWA PASANG SURUT'),
(3541, '4', '02', '02', '03', '001', 'SALURAN MUKA (BANGUNAN PEMBAWA PASANG SURUT)'),
(3542, '4', '02', '02', '03', '002', 'SALURAN INDUK (BANGUNAN PEMBAWA PASANG SURUT)'),
(3543, '4', '02', '02', '03', '003', 'SALURAN SEKUNDER (BANGUNAN PEMBAWA PASANG SURUT)'),
(3544, '4', '02', '02', '03', '004', 'SALURAN TERSIER (BANGUNAN PEMBAWA PASANG SURUT)'),
(3545, '4', '02', '02', '03', '005', 'SALURAN PENYIMPAN AIR'),
(3546, '4', '02', '02', '03', '006', 'SALURAN LALU LINTAS AIR'),
(3547, '4', '02', '02', '03', '999', 'BANGUNAN PEMBAWA PASANG SURUT LAINNYA'),
(3548, '4', '02', '02', '04', '000', 'SALURAN PEMBUANG PASANG SURUT'),
(3549, '4', '02', '02', '04', '001', 'SALURAN INDUK PEMBUANG (SALURAN PEMBUANG PASANG SURUT)'),
(3550, '4', '02', '02', '04', '002', 'SALURAN SEKUNDER PEMBUANG (SALURAN PEMBUANG PASANG SURUT)'),
(3551, '4', '02', '02', '04', '003', 'SALURAN TERSIER PEMBUANG (SALURAN PEMBUANG PASANG SURUT)'),
(3552, '4', '02', '02', '04', '004', 'SALURAN PENGUMPUL AIR'),
(3553, '4', '02', '02', '04', '999', 'SALURAN PEMBUANG PASANG SURUT LAINNYA'),
(3554, '4', '02', '02', '05', '000', 'BANGUNAN PENGAMAN PASANG SURUT'),
(3555, '4', '02', '02', '05', '001', 'BANGUNAN PINTU AIR/KLEP (BANGUNAN PENGAMAN PASANG SURUT)'),
(3556, '4', '02', '02', '05', '002', 'BANGUNAN PEMASUKAN/PEMBUANG'),
(3557, '4', '02', '02', '05', '003', 'KOLAM PASANG'),
(3558, '4', '02', '02', '05', '999', 'BANGUNAN PENGAMAN PASANG SURUT LAINNYA'),
(3559, '4', '02', '02', '06', '000', 'BANGUNAN PELENGKAP PASANG SURUT'),
(3560, '4', '02', '02', '06', '001', 'BANGUNAN JEMBATAN (BANGUNAN PELENGKAP PASANG SURUT)'),
(3561, '4', '02', '02', '06', '002', 'BANGUNAN JEMBATAN PENGHALANG (BANGUNAN PELENGKAP PASANG SURUT)'),
(3562, '4', '02', '02', '06', '003', 'BANGUNAN PENUTUP PENANGKIS KOTORAN'),
(3563, '4', '02', '02', '06', '004', 'BANGUNAN PENGUKUR MUKA AIR (BANGUNAN PELENGKAP PASANG SURUT)'),
(3564, '4', '02', '02', '06', '005', 'BANGUNAN PENGUKUR CURAH HUJAN (BANGUNAN PELENGKAP PASANG SURUT)'),
(3565, '4', '02', '02', '06', '999', 'BANGUNAN PELENGKAP PASANG SURUT LAINNYA'),
(3566, '4', '02', '02', '07', '000', 'BANGUNAN SAWAH PASANG SURUT'),
(3567, '4', '02', '02', '07', '001', 'BANGUNAN SAWAH PASANG SURUT TEKNIS'),
(3568, '4', '02', '02', '07', '002', 'BANGUNAN SAWAH PASANG SURUT SEMI TEKNIS'),
(3569, '4', '02', '02', '07', '003', 'BANGUNAN SAWAH PASANG SURUT NON TEKNIS'),
(3570, '4', '02', '02', '07', '999', 'BANGUNAN SAWAH PASANG SURUT LAINNYA'),
(3571, '4', '02', '02', '99', '000', 'BANGUNAN PENGAIRAN PASANG SURUT LAINNYA'),
(3572, '4', '02', '02', '99', '999', 'BANGUNAN PENGAIRAN PASANG SURUT LAINNYA'),
(3573, '4', '02', '03', '00', '000', 'BANGUNAN PENGEMBANGAN RAWA DAN POLDER'),
(3574, '4', '02', '03', '01', '000', 'BANGUNAN WADUK PENGEMBANGAN RAWA'),
(3575, '4', '02', '03', '01', '001', 'BANGUNAN WADUK'),
(3576, '4', '02', '03', '01', '999', 'BANGUNAN WADUK PENGEMBANGAN RAWA LAINNYA'),
(3577, '4', '02', '03', '02', '000', 'BANGUNAN PENGAMBILAN PENGEMBANGAN RAWA'),
(3578, '4', '02', '03', '02', '001', 'WADUK PENGAMBILAN RAWA'),
(3579, '4', '02', '03', '02', '999', 'BANGUNAN PENGAMBILAN PENGEMBANGAN RAWA LAINNYA'),
(3580, '4', '02', '03', '03', '000', 'BANGUNAN PEMBAWA PENGEMBANGAN RAWA'),
(3581, '4', '02', '03', '03', '001', 'SALURAN MUKA (BANGUNAN PEMBAWA PENGEMBANGAN RAWA)'),
(3582, '4', '02', '03', '03', '002', 'SALURAN INDUK (BANGUNAN PEMBAWA PENGEMBANGAN RAWA)'),
(3583, '4', '02', '03', '03', '003', 'SALURAN SEKUNDER (BANGUNAN PEMBAWA PENGEMBANGAN RAWA)'),
(3584, '4', '02', '03', '03', '004', 'SALURAN TERSIER (BANGUNAN PEMBAWA PENGEMBANGAN RAWA)'),
(3585, '4', '02', '03', '03', '999', 'BANGUNAN PEMBAWA PENGEMBANGAN RAWA LAINNYA'),
(3586, '4', '02', '03', '04', '000', 'BANGUNAN PEMBUANG PENGEMBANGAN RAWA'),
(3587, '4', '02', '03', '04', '001', 'SALURAN INDUK PEMBUANG (BANGUNAN PEMBUANG PENGEMBANGAN RAWA)'),
(3588, '4', '02', '03', '04', '002', 'SALURAN SEKUNDER PEMBUANG (BANGUNAN PEMBUANG PENGEMBANGAN RAWA)'),
(3589, '4', '02', '03', '04', '003', 'SALURAN TERSIER PEMBUANG (BANGUNAN PEMBUANG PENGEMBANGAN RAWA)'),
(3590, '4', '02', '03', '04', '999', 'BANGUNAN PEMBUANG PENGEMBANGAN RAWA LAINNYA'),
(3591, '4', '02', '03', '05', '000', 'BANGUNAN PENGAMAN PENGEMBANGAN RAWA'),
(3592, '4', '02', '03', '05', '001', 'TANGGUL KELILING'),
(3593, '4', '02', '03', '05', '002', 'BANGUNAN PINTU AIR/KLEP (BANGUNAN PENGAMAN PENGEMBANGAN RAWA)'),
(3594, '4', '02', '03', '05', '999', 'BANGUNAN PENGAMAN PENGEMBANGAN RAWA LAINNYA'),
(3595, '4', '02', '03', '06', '000', 'BANGUNAN PELENGKAP PENGEMBANGAN RAWA'),
(3596, '4', '02', '03', '06', '001', 'BANGUNAN BAGI DAN SADAP (BANGUNAN PELENGKAP PENGEMBANGAN RAWA)'),
(3597, '4', '02', '03', '06', '002', 'BANGUNAN SADAP (BANGUNAN PELENGKAP PENGEMBANGAN RAWA)'),
(3598, '4', '02', '03', '06', '003', 'BANGUNAN TERJUN (BANGUNAN PELENGKAP PENGEMBANGAN RAWA)'),
(3599, '4', '02', '03', '06', '004', 'BANGUNAN SYPHON (BANGUNAN PELENGKAP PENGEMBANGAN RAWA)'),
(3600, '4', '02', '03', '06', '005', 'BANGUNAN GORONG-GORONG (BANGUNAN PELENGKAP PENGEMBANGAN RAWA)'),
(3601, '4', '02', '03', '06', '006', 'BANGUNAN JEMBATAN (BANGUNAN PELENGKAP PENGEMBANGAN RAWA)'),
(3602, '4', '02', '03', '06', '007', 'BANGUNAN JEMBATAN PENGHALANG (BGNAN PELENGKAP PENGEMBANGAN RAWA)'),
(3603, '4', '02', '03', '06', '008', 'BANGUNAN PENGUKUR MUKA AIR (BANGUNAN PELENGKAP PENGEMBANGAN RAWA)'),
(3604, '4', '02', '03', '06', '009', 'BANGUNAN PENGUKUR CURAH HUJAN (BGNAN PELENGKAP PENGEMBANGAN RAWA)'),
(3605, '4', '02', '03', '06', '010', 'BANGUNAN PENUTUP SUNGAI'),
(3606, '4', '02', '03', '06', '011', 'BANGUNAN STASIUN POMPA PEMASUKAN/PEMBUANG'),
(3607, '4', '02', '03', '06', '999', 'BANGUNAN PELENGKAP PENGEMBANGAN RAWA LAINNYA'),
(3608, '4', '02', '03', '07', '000', 'BANGUNAN SAWAH PENGEMBANGAN RAWA'),
(3609, '4', '02', '03', '07', '001', 'BANGUNAN SAWAH RAWA TEKNIS'),
(3610, '4', '02', '03', '07', '002', 'BANGUNAN SAWAH RAWA SEMI TEKNIS'),
(3611, '4', '02', '03', '07', '003', 'BANGUNAN SAWAH RAWA NON TEKNIS'),
(3612, '4', '02', '03', '07', '999', 'BANGUNAN SAWAH PENGEMBANGAN RAWA LAINNYA'),
(3613, '4', '02', '03', '99', '000', 'BANGUNAN PENGEMBANGAN RAWA DAN POLDER LAINNYA'),
(3614, '4', '02', '03', '99', '999', 'BANGUNAN PENGEMBANGAN RAWA DAN POLDER LAINNYA'),
(3615, '4', '02', '04', '00', '000', 'BANGUNAN PENGAMAN SUNGAI/PANTAI & PENANGGULANGAN BENCANA ALAM'),
(3616, '4', '02', '04', '01', '000', 'BANGUNAN PENGAMAN SUNGAI/PANTAI & PENANGGULANGAN BENCANA ALAM'),
(3617, '4', '02', '04', '01', '001', 'BANGUNA WASUK PENGAMAN SUNGAI/PANTAI'),
(3618, '4', '02', '04', '01', '002', '\"WADUK DENGAN TANGGUL'),
(3619, '4', '02', '04', '01', '999', 'BANGUNAN PENGAMAN SUNGAI/PANTAI & PENANGGULANGAN BENCANA ALAM LAINNYA'),
(3620, '4', '02', '04', '02', '000', 'BANGUNAN PENGAMBILAN PENGAMAN SUNGAI/PANTAI'),
(3621, '4', '02', '04', '02', '001', 'BANGUNAN PENGAMBILAN PENGAMANAN SUNGAI'),
(3622, '4', '02', '04', '02', '002', 'BANGUNAN PENGAMBILAN PENGAMANAN PANTAI'),
(3623, '4', '02', '04', '02', '999', 'BANGUNAN PENGAMBILAN PENGAMAN SUNGAI/PANTAI LAINNYA'),
(3624, '4', '02', '04', '03', '000', 'BANGUNAN PEMBAWA PENGAMAN SUNGAI/PANTAI'),
(3625, '4', '02', '04', '03', '001', 'BANGUNAN PEMBAWA PENGAMAN SUNGAI'),
(3626, '4', '02', '04', '03', '002', 'BANGUNAN PEMBAWA PENGAMAN PANTAI'),
(3627, '4', '02', '04', '03', '999', 'BANGUNAN PEMBAWA PENGAMAN SUNGAI/PANTAI LAINNYA'),
(3628, '4', '02', '04', '04', '000', 'BANGUNAN PEMBUANG PENGAMAN SUNGAI'),
(3629, '4', '02', '04', '04', '001', 'SALURAN BANJIR'),
(3630, '4', '02', '04', '04', '002', 'SALURAN DRAINAGE'),
(3631, '4', '02', '04', '04', '999', 'BANGUNAN PEMBUANG PENGAMAN SUNGAI LAINNYA'),
(3632, '4', '02', '04', '05', '000', 'BANGUNAN PENGAMAN PENGAMANAN SUNGAI/PANTAI'),
(3633, '4', '02', '04', '05', '001', 'TANGGUL BANJIR (BANGUNAN PENGAMAN PENGAMANAN SUNGAI/PANTAI)'),
(3634, '4', '02', '04', '05', '002', 'PINTU PENGATUR BANJIR'),
(3635, '4', '02', '04', '05', '003', 'COUPURE/SODETAN'),
(3636, '4', '02', '04', '05', '004', 'KANTONG PASIR/LAHAR/LUMPUR'),
(3637, '4', '02', '04', '05', '005', 'CHEKDAM/PENAHAN SEDIMEN'),
(3638, '4', '02', '04', '05', '006', 'KRIB PENGAMAN SUNGAI/PANTAI'),
(3639, '4', '02', '04', '05', '007', 'BANGUNAN PENGUAT TEBING/PANTAI'),
(3640, '4', '02', '04', '05', '008', 'BANGUNAN PELIMPAH BANJIR'),
(3641, '4', '02', '04', '05', '009', 'DAM KONSOLIDASI'),
(3642, '4', '02', '04', '05', '010', 'PERALATAN SARINGAN SAMPAH ( POND SCREEN )'),
(3643, '4', '02', '04', '05', '011', 'KLEP PENGATUR BANJIR'),
(3644, '4', '02', '04', '05', '012', 'BANGUNAN PEMECAH GELOMBANG'),
(3645, '4', '02', '04', '05', '013', 'BANGUNAN PELANTARAN PANTAI'),
(3646, '4', '02', '04', '05', '999', 'BANGUNAN PENGAMAN PENGAMANAN SUNGAI/PANTAI LAINNYA'),
(3647, '4', '02', '04', '06', '000', 'BANGUNAN PELENGKAP PENGAMAN SUNGAI'),
(3648, '4', '02', '04', '06', '001', 'BANGUNAN SYPHON (BANGUNAN PELENGKAP PENGAMAN SUNGAI)'),
(3649, '4', '02', '04', '06', '002', 'BANGUNAN GORONG-GORONG (BANGUNAN PELENGKAP PENGAMAN SUNGAI)'),
(3650, '4', '02', '04', '06', '003', 'BANGUNAN JEMBATAN (BANGUNAN PELENGKAP PENGAMAN SUNGAI)'),
(3651, '4', '02', '04', '06', '004', 'BANGUNAN PENGUKUR MUKA AIR (BANGUNAN PELENGKAP PENGAMAN SUNGAI)'),
(3652, '4', '02', '04', '06', '005', 'BANGUNAN PENGUKUR CURAH HUJAN (BGNAN PELENGKAP PENGAMAN SUNGAI)'),
(3653, '4', '02', '04', '06', '006', 'STASIUN POS PENJAGA/PENGAMAT'),
(3654, '4', '02', '04', '06', '007', 'BANGUNAN DERMAGA'),
(3655, '4', '02', '04', '06', '008', 'BANGUNAN STASIUN POMPA PEMBUANG'),
(3656, '4', '02', '04', '06', '009', 'WARNING SYSTEM'),
(3657, '4', '02', '04', '06', '999', 'BANGUNAN PELENGKAP PENGAMAN SUNGAI LAINNYA'),
(3658, '4', '02', '04', '99', '000', 'BANGUNAN PENGAMAN SUNGAI/PANTAI & PENANGGULANGAN BENCANA ALAM LAINNYA'),
(3659, '4', '02', '04', '99', '999', 'BANGUNAN PENGAMAN SUNGAI/PANTAI & PENANGGULANGAN BENCANA ALAM LAINNYA'),
(3660, '4', '02', '05', '00', '000', 'BANGUNAN PENGEMBANGAN SUMBER AIR DAN AIR TANAH'),
(3661, '4', '02', '05', '01', '000', 'BANGUNAN WADUK PENGEMBANGAN SUMBER AIR'),
(3662, '4', '02', '05', '01', '001', 'EMBUNG/WADUK LAPANGAN'),
(3663, '4', '02', '05', '01', '999', 'BANGUNAN WADUK PENGEMBANGAN SUMBER AIR LAINNYA'),
(3664, '4', '02', '05', '02', '000', 'BANGUNAN PENGAMBILAN PENGEMBANGAN SUMBER AIR'),
(3665, '4', '02', '05', '02', '001', 'SUMUR DENGAN POMPA (BANGUNAN PENGAMBILAN PENGEMBANGAN SUMBER AIR)'),
(3666, '4', '02', '05', '02', '002', 'SUMUR ARTETIS'),
(3667, '4', '02', '05', '02', '999', 'BANGUNAN PENGAMBILAN PENGEMBANGAN SUMBER AIR LAINNYA'),
(3668, '4', '02', '05', '03', '000', 'BANGUNAN PEMBAWA PENGEMBANGAN SUMBER AIR'),
(3669, '4', '02', '05', '03', '001', 'SALURAN TERSIER (BANGUNAN PEMBAWA PENGEMBANGAN SUMBER AIR)'),
(3670, '4', '02', '05', '03', '002', 'SALURAN KUARTIER'),
(3671, '4', '02', '05', '03', '999', 'BANGUNAN PEMBAWA PENGEMBANGAN SUMBER AIR LAINNYA'),
(3672, '4', '02', '05', '04', '000', 'BANGUNAN PEMBUANG PENGEMBANGAN SUMBER AIR'),
(3673, '4', '02', '05', '04', '001', 'SALURAN PEMBUANG'),
(3674, '4', '02', '05', '04', '999', 'BANGUNAN PEMBUANG PENGEMBANGAN SUMBER AIR LAINNYA'),
(3675, '4', '02', '05', '05', '000', 'BANGUNAN PENGAMAN PENGEMBANGAN SUMBER AIR'),
(3676, '4', '02', '05', '05', '001', 'BAK PENAMPUNG/KOLAM/ MENARA PENAMPUNGAN'),
(3677, '4', '02', '05', '05', '002', 'BANGUNAN KLIMATOLOGI'),
(3678, '4', '02', '05', '05', '003', 'BANGUNAN HIDROMETRI'),
(3679, '4', '02', '05', '05', '004', 'SUMUR PENGAMATAN'),
(3680, '4', '02', '05', '05', '999', 'BANGUNAN PENGAMAN PENGEMBANGAN SUMBER AIR LAINNYA'),
(3681, '4', '02', '05', '06', '000', 'BANGUNAN PELENGKAP PENGEMBANGAN SUMBER AIR'),
(3682, '4', '02', '05', '06', '001', 'BANGUNAN TERJUN (BANGUNAN PELENGKAP PENGEMBANGAN SUMBER AIR)'),
(3683, '4', '02', '05', '06', '002', 'BANGUNAN TALANG (BANGUNAN PELENGKAP PENGEMBANGAN SUMBER AIR)'),
(3684, '4', '02', '05', '06', '003', 'BANGUNAN SYPHON (BANGUNAN PELENGKAP PENGEMBANGAN SUMBER AIR)'),
(3685, '4', '02', '05', '06', '004', 'BANGUNAN GORONG-GORONG (BGNAN PELENGKAP PENGEMBANGAN SUMBER AIR)'),
(3686, '4', '02', '05', '06', '005', 'BANGUNAN BOX TERSIER (BANGUNAN PELENGKAP PENGEMBANGAN SUMBER AIR)'),
(3687, '4', '02', '05', '06', '006', 'BANGUNAN JEMBATAN (BANGUNAN PELENGKAP PENGEMBANGAN SUMBER AIR)'),
(3688, '4', '02', '05', '06', '999', 'BANGUNAN PELENGKAP PENGEMBANGAN SUMBER AIR LAINNYA'),
(3689, '4', '02', '05', '07', '000', 'BANGUNAN SAWAH IRIGASI AIR TANAH'),
(3690, '4', '02', '05', '07', '001', 'BANGUNAN SAWAH IRIGASI AIR TANAH TEKNIS'),
(3691, '4', '02', '05', '07', '002', 'BANGUNAN SAWAH IRIGASI AIR TANAH SEMI TEKNIS'),
(3692, '4', '02', '05', '07', '003', 'BANGUNAN SAWAH IRIGASI AIR TANAH NON TEKNIS'),
(3693, '4', '02', '05', '07', '999', 'BANGUNAN SAWAH IRIGASI AIR TANAH LAINNYA'),
(3694, '4', '02', '05', '99', '000', 'BANGUNAN PENGEMBANGAN SUMBER AIR DAN AIR TANAH LAINNYA'),
(3695, '4', '02', '05', '99', '999', 'BANGUNAN PENGEMBANGAN SUMBER AIR DAN AIR TANAH LAINNYA'),
(3696, '4', '02', '06', '00', '000', 'BANGUNAN AIR BERSIH/AIR BAKU'),
(3697, '4', '02', '06', '01', '000', 'BANGUNAN WADUK AIR BERSIH/AIR BAKU'),
(3698, '4', '02', '06', '01', '001', 'WADUK PENYIMPANAN AIR BAKU'),
(3699, '4', '02', '06', '01', '002', 'WADUK PENYIMPANAN AIR HUJAN'),
(3700, '4', '02', '06', '01', '003', 'BAK PENYIMPANAN/TOWER AIR BAKU'),
(3701, '4', '02', '06', '01', '999', 'BANGUNAN WADUK AIR BERSIH/AIR BAKU LAINNYA'),
(3702, '4', '02', '06', '02', '000', 'BANGUNAN PENGAMBILAN AIR BERSIH/AIR BAKU'),
(3703, '4', '02', '06', '02', '001', 'BANGUNAN PENGAMBILAN DARI WADUK'),
(3704, '4', '02', '06', '02', '002', 'BANGUNAN PENGAMBILAN DARI SUNGAI'),
(3705, '4', '02', '06', '02', '003', 'BANGUNAN PENGAMBILAN DARI DANAU'),
(3706, '4', '02', '06', '02', '004', 'BANGUNAN PENGAMBILAN DARI RAWA'),
(3707, '4', '02', '06', '02', '005', 'BANGUNAN PENGAMBILAN DARI AIR LAUT'),
(3708, '4', '02', '06', '02', '006', 'BANGUNAN PENGAMBILAN DARI SUMBER AIR'),
(3709, '4', '02', '06', '02', '007', 'BANGUNAN PENGAMBILAN DARI SUMUR ARTETIS'),
(3710, '4', '02', '06', '02', '999', 'BANGUNAN PENGAMBILAN AIR BERSIH/AIR BAKU LAINNYA'),
(3711, '4', '02', '06', '03', '000', 'BANGUNAN PEMBAWA AIR BERSIH/AIR BAKU'),
(3712, '4', '02', '06', '03', '001', 'SALURAN PEMBAWA AIR BAKU TERBUKA'),
(3713, '4', '02', '06', '03', '002', 'SALURAN PEMBAWA AIR BAKU TERTUTUP'),
(3714, '4', '02', '06', '03', '999', 'BANGUNAN PEMBAWA AIR BERSIH/AIR BAKU LAINNYA'),
(3715, '4', '02', '06', '04', '000', 'BANGUNAN PEMBUANG AIR BERSIH/AIR BAKU'),
(3716, '4', '02', '06', '04', '001', 'SALURAN PEMBUANG AIR CUCIAN AIR BAKU'),
(3717, '4', '02', '06', '04', '002', 'SALURAN PEMBUANG AIR CUCIAN INSTALASI'),
(3718, '4', '02', '06', '04', '999', 'BANGUNAN PEMBUANG AIR BERSIH/AIR BAKU LAINNYA'),
(3719, '4', '02', '06', '05', '000', 'BANGUNAN PELENGKAP AIR BERSIH/AIR BAKU'),
(3720, '4', '02', '06', '05', '001', 'BANGUNAN TALANG (BANGUNAN PELENGKAP AIR BERSIH/AIR BAKU)'),
(3721, '4', '02', '06', '05', '002', 'BANGUNAN SYPHON (BANGUNAN PELENGKAP AIR BERSIH/AIR BAKU)'),
(3722, '4', '02', '06', '05', '003', 'BANGUNAN GORONG-GORONG (BANGUNAN PELENGKAP AIR BERSIH/AIR BAKU)'),
(3723, '4', '02', '06', '05', '004', 'BANGUNAN JEMBATAN (BANGUNAN PELENGKAP AIR BERSIH/AIR BAKU)'),
(3724, '4', '02', '06', '05', '005', 'BANGUNAN PENAMPUNG AIR BAKU'),
(3725, '4', '02', '06', '05', '006', 'BANGUNAN HIDRAN UMUM'),
(3726, '4', '02', '06', '05', '007', 'BANGUNAN MANDI CUCI KAKUS (MCK)'),
(3727, '4', '02', '06', '05', '008', 'BANGUNAN MENARA/BAK PENAMPUNG/RESERVOIR AIR MINUM'),
(3728, '4', '02', '06', '05', '009', 'BANGUNAN BUSTER PUMP'),
(3729, '4', '02', '06', '05', '999', 'BANGUNAN PELENGKAP AIR BERSIH/AIR BAKU LAINNYA'),
(3730, '4', '02', '06', '99', '000', 'BANGUNAN AIR BERSIH/AIR BAKU LAINNYA'),
(3731, '4', '02', '06', '99', '999', 'BANGUNAN AIR BERSIH/AIR BAKU LAINNYA'),
(3732, '4', '02', '07', '00', '000', 'BANGUNAN AIR KOTOR'),
(3733, '4', '02', '07', '01', '000', 'BANGUNAN PEMBAWA AIR KOTOR'),
(3734, '4', '02', '07', '01', '001', 'SALURAN PENGUMPUL AIR HUJAN'),
(3735, '4', '02', '07', '01', '002', 'SALURAN PENGUMPUL AIR BUANGAN DOMESTIK'),
(3736, '4', '02', '07', '01', '003', 'SALURAN PENGUMPUL AIR BUANGAN INDUSTRI'),
(3737, '4', '02', '07', '01', '004', 'SALURAN PENGUMPUL AIR BUANGAN PERTANIAN'),
(3738, '4', '02', '07', '01', '999', 'BANGUNAN PEMBAWA AIR KOTOR LAINNYA'),
(3739, '4', '02', '07', '02', '000', 'BANGUNAN WADUK AIR KOTOR'),
(3740, '4', '02', '07', '02', '001', 'WADUK AIR HUJAN'),
(3741, '4', '02', '07', '02', '002', 'WADUK AIR BUANGAN DOMESTIK'),
(3742, '4', '02', '07', '02', '003', 'WADUK AIR BUANGAN INDUSTRI'),
(3743, '4', '02', '07', '02', '004', 'WADUK AIR BUANGAN PERTANIAN'),
(3744, '4', '02', '07', '02', '999', 'BANGUNAN WADUK AIR KOTOR LAINNYA'),
(3745, '4', '02', '07', '03', '000', 'BANGUNAN PEMBUANG AIR KOTOR'),
(3746, '4', '02', '07', '03', '001', 'SALURAN PEMBUANG AIR BUANGAN AIR HUJAN'),
(3747, '4', '02', '07', '03', '002', 'SALURAN PEMBUANG AIR BUANGAN DOMESTIK'),
(3748, '4', '02', '07', '03', '003', 'SALURAN PEMBUANG AIR BUANGAN AIR INDUSTRI'),
(3749, '4', '02', '07', '03', '004', 'SALURAN PEMBUANG AIR BUANGAN AIR PERTANIAN'),
(3750, '4', '02', '07', '03', '999', 'BANGUNAN PEMBUANG AIR KOTOR LAINNYA'),
(3751, '4', '02', '07', '04', '000', 'BANGUNAN PENGAMAN AIR KOTOR'),
(3752, '4', '02', '07', '04', '001', 'BANGUNAN POMPA AIR HUJAN'),
(3753, '4', '02', '07', '04', '002', 'BANGUNAN POMPA AIR BUANGAN DOMESTIK'),
(3754, '4', '02', '07', '04', '003', 'BANGUNAN POMPA AIR BUANGAN INDUSTRI'),
(3755, '4', '02', '07', '04', '004', 'BANGUNAN POMPA AIR BUANGAN PERTANIAN'),
(3756, '4', '02', '07', '04', '999', 'BANGUNAN PENGAMAN AIR KOTOR LAINNYA'),
(3757, '4', '02', '07', '05', '000', 'BANGUNAN PELENGKAP AIR KOTOR'),
(3758, '4', '02', '07', '05', '001', 'BANGUNAN TALANG (BANGUNAN PELENGKAP AIR KOTOR)'),
(3759, '4', '02', '07', '05', '002', 'BANGUNAN SYPHON (BANGUNAN PELENGKAP AIR KOTOR)'),
(3760, '4', '02', '07', '05', '003', 'BANGUNAN GORONG-GORONG (BANGUNAN PELENGKAP AIR KOTOR)'),
(3761, '4', '02', '07', '05', '004', 'BANGUNAN JEMBATAN (BANGUNAN PELENGKAP AIR KOTOR)'),
(3762, '4', '02', '07', '05', '005', 'BANGUNAN BAK KONTROL/MAN HOLE'),
(3763, '4', '02', '07', '05', '006', 'SALURAN AIR KOTOR SAMBUNGAN DARI RUMAH'),
(3764, '4', '02', '07', '05', '007', 'BANGUNAN (BOX) CULVERT'),
(3765, '4', '02', '07', '05', '008', 'MULTIPLE PIPA ARCHES'),
(3766, '4', '02', '07', '05', '009', 'BANGUNAN PLAT DEKER'),
(3767, '4', '02', '07', '05', '999', 'BANGUNAN PELENGKAP AIR KOTOR LAINNYA'),
(3768, '4', '02', '07', '99', '000', 'BANGUNAN AIR KOTOR LAINNYA'),
(3769, '4', '02', '07', '99', '999', 'BANGUNAN AIR KOTOR LAINNYA'),
(3770, '4', '03', '00', '00', '000', 'INSTALASI'),
(3771, '4', '03', '01', '00', '000', 'INSTALASI AIR BERSIH / AIR BAKU'),
(3772, '4', '03', '01', '01', '000', 'INSTALASI AIR PERMUKAAN'),
(3773, '4', '03', '01', '01', '001', 'INSTALASI AIR PERMUKAAN KAPASITAS KECIL'),
(3774, '4', '03', '01', '01', '002', 'INSTALASI AIR PERMUKAAN KAPASITAS SEDANG'),
(3775, '4', '03', '01', '01', '003', 'INSTALASI AIR PERMUKAAN KAPASITAS BESAR'),
(3776, '4', '03', '01', '01', '999', 'INSTALASI AIR PERMUKAAN LAINNYA'),
(3777, '4', '03', '01', '02', '000', 'INSTALASI AIR SUMBER / MATA AIR'),
(3778, '4', '03', '01', '02', '001', 'INSTALASI AIR SUMBER / MATA AIR KAPASITAS KECIL'),
(3779, '4', '03', '01', '02', '002', 'INSTALASI AIR SUMBER / MATA AIR KAPASITAS SEDANG'),
(3780, '4', '03', '01', '02', '003', 'INSTALASI AIR SUMBER / MATA AIR KAPASITAS BESAR'),
(3781, '4', '03', '01', '02', '999', 'INSTALASI AIR SUMBER / MATA AIR LAINNYA'),
(3782, '4', '03', '01', '03', '000', 'INSTALASI AIR TANAH DALAM'),
(3783, '4', '03', '01', '03', '001', 'INSTALASI AIR TANAH DALAM KAPASITAS KECIL'),
(3784, '4', '03', '01', '03', '002', 'INSTALASI AIR TANAH DALAM KAPASITAS SEDANG'),
(3785, '4', '03', '01', '03', '003', 'INSTALASI AIR TANAH DALAM KAPASITAS BESAR'),
(3786, '4', '03', '01', '03', '999', 'INSTALASI AIR TANAH DALAM LAINNYA'),
(3787, '4', '03', '01', '04', '000', 'INSTALASI AIR TANAH DANGKAL'),
(3788, '4', '03', '01', '04', '001', 'INSTALASI AIR TANAH DANGKAL KAPASITAS KECIL'),
(3789, '4', '03', '01', '04', '002', 'INSTALASI AIR TANAH DANGKAL KAPASITAS SEDANG'),
(3790, '4', '03', '01', '04', '003', 'INSTALASI AIR TANAH DANGKAL KAPASITAS BESAR'),
(3791, '4', '03', '01', '04', '999', 'INSTALASI AIR TANAH DANGKAL LAINNYA'),
(3792, '4', '03', '01', '05', '000', 'INSTALASI AIR BERSIH / AIR BAKU LAINNYA'),
(3793, '4', '03', '01', '05', '001', 'SISTEM PENGOLAHAN AIR SEDERHANA (SIPAS)'),
(3794, '4', '03', '01', '05', '002', 'JARINGAN RUMAH TANGGA (JARUT)'),
(3795, '4', '03', '01', '05', '003', 'PENAMPUNGAN AIR HUJAN (PAH)'),
(3796, '4', '03', '01', '05', '004', 'SUMUR GALI (SGL)'),
(3797, '4', '03', '01', '05', '005', 'SUMUR RESAPAN'),
(3798, '4', '03', '01', '05', '999', 'INSTALASI AIR BERSIH / AIR BAKU LAINNYA LAINNYA'),
(3799, '4', '03', '01', '99', '000', 'INSTALASI AIR BERSIH / AIR BAKU LAINNYA'),
(3800, '4', '03', '01', '99', '999', 'INSTALASI AIR BERSIH / AIR BAKU LAINNYA'),
(3801, '4', '03', '02', '00', '000', 'INSTALASI AIR KOTOR'),
(3802, '4', '03', '02', '01', '000', 'INSTALASI AIR BUANGAN DOMESTIK'),
(3803, '4', '03', '02', '01', '001', 'INSTALASI AIR BUANGAN DOMESTIK KAPASITAS KECIL'),
(3804, '4', '03', '02', '01', '002', 'INSTALASI AIR BUANGAN DOMESTIK KAPASITAS SEDANG'),
(3805, '4', '03', '02', '01', '003', 'INSTALASI AIR BUANGAN DOMESTIK KAPASITAS BESAR'),
(3806, '4', '03', '02', '01', '999', 'INSTALASI AIR BUANGAN DOMESTIK LAINNYA'),
(3807, '4', '03', '02', '02', '000', 'INSTALASI AIR BUANGAN INDUSTRI'),
(3808, '4', '03', '02', '02', '001', 'INSTALASI AIR BUANGAN INDUSTRI KAPASITAS KECIL'),
(3809, '4', '03', '02', '02', '002', 'INSTALASI AIR BUANGAN INDUSTRI KAPASITAS SEDANG'),
(3810, '4', '03', '02', '02', '003', 'INSTALASI AIR BUANGAN INDUSTRI KAPASITAS BESAR'),
(3811, '4', '03', '02', '02', '999', 'INSTALASI AIR BUANGAN INDUSTRI LAINNYA'),
(3812, '4', '03', '02', '03', '000', 'INSTALASI AIR BUANGAN PERTANIAN'),
(3813, '4', '03', '02', '03', '001', 'INSTALASI AIR BUANGAN PERTANIAN KAPASITAS KECIL'),
(3814, '4', '03', '02', '03', '002', 'INSTALASI AIR BUANGAN PERTANIAN KAPASITAS SEDANG'),
(3815, '4', '03', '02', '03', '003', 'INSTALASI AIR BUANGAN PERTANIAN KAPASITAS BESAR'),
(3816, '4', '03', '02', '03', '999', 'INSTALASI AIR BUANGAN PERTANIAN LAINNYA'),
(3817, '4', '03', '02', '99', '000', 'INSTALASI AIR KOTOR LAINNYA'),
(3818, '4', '03', '02', '99', '999', 'INSTALASI AIR KOTOR LAINNYA'),
(3819, '4', '03', '03', '00', '000', 'INSTALASI PENGOLAHAN SAMPAH'),
(3820, '4', '03', '03', '01', '000', 'INSTALASI PENGOLAHAN SAMPAH ORGANIK'),
(3821, '4', '03', '03', '01', '001', 'INSTALASI PENGOLAHAN SAMPAH ORGANIK SISTEM PEMBAKARAN'),
(3822, '4', '03', '03', '01', '002', 'INSTALASI PENGOLAHAN SAMPAH ORGANIK SISTEM KOMPOS'),
(3823, '4', '03', '03', '01', '003', 'INSTALASI PENGOLAHAN SAMPAH ORGANIK SISTEM PENIMBUNAN'),
(3824, '4', '03', '03', '01', '999', 'INSTALASI PENGOLAHAN SAMPAH ORGANIK LAINNYA'),
(3825, '4', '03', '03', '02', '000', 'INSTALASI PENGOLAHAN SAMPAH NON ORGANIK'),
(3826, '4', '03', '03', '02', '001', 'INSTALASI PENGOLAHAN SAMPAH NON ORGANIK DAUR ULANG LOGAM'),
(3827, '4', '03', '03', '02', '002', 'INSTALASI PENGOLAHAN SAMPAH NON ORGANIK DAUR ULANG NON LOGAM'),
(3828, '4', '03', '03', '02', '999', 'INSTALASI PENGOLAHAN SAMPAH NON ORGANIK LAINNYA'),
(3829, '4', '03', '03', '03', '000', 'BANGUNAN PENAMPUNG SAMPAH'),
(3830, '4', '03', '03', '03', '001', 'BANGUNAN TEMPAT PENAMPUNG SAMPAH RUMAH TANGGA'),
(3831, '4', '03', '03', '03', '002', 'BANGUNAN TEMPAT MENAMPUNG SAMPAH LINGKUNGAN'),
(3832, '4', '03', '03', '03', '999', 'BANGUNAN PENAMPUNG SAMPAH LAINNYA'),
(3833, '4', '03', '03', '99', '000', 'INSTALASI PENGOLAHAN SAMPAH LAINNYA'),
(3834, '4', '03', '03', '99', '999', 'INSTALASI PENGOLAHAN SAMPAH LAINNYA'),
(3835, '4', '03', '04', '00', '000', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN'),
(3836, '4', '03', '04', '01', '000', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERCONTOHAN'),
(3837, '4', '03', '04', '01', '001', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERCONTOHAN PENGAWETAN KAYU'),
(3838, '4', '03', '04', '01', '002', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERCONTOHAN PENGERINGAN KAYU'),
(3839, '4', '03', '04', '01', '003', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERCONTOHAN PENGERJAAN KAYU'),
(3840, '4', '03', '04', '01', '004', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERCONTOHAN PERKAPURAN'),
(3841, '4', '03', '04', '01', '005', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERCONTOHAN PEMBUATAN BATU'),
(3842, '4', '03', '04', '01', '006', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERCONTOHAN PEMBUATAN AGREGA'),
(3843, '4', '03', '04', '01', '999', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERCONTOHAN LAINNYA'),
(3844, '4', '03', '04', '02', '000', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERINTIS'),
(3845, '4', '03', '04', '02', '001', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERINTIS PENGAWETAN KAYU'),
(3846, '4', '03', '04', '02', '002', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERINTIS PENGERINGAN KAYU'),
(3847, '4', '03', '04', '02', '003', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERINTIS PENGERJAAN KAYU'),
(3848, '4', '03', '04', '02', '004', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERINTIS PERKAPURAN'),
(3849, '4', '03', '04', '02', '005', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERINTIS PEMBUATAN BATU CETA'),
(3850, '4', '03', '04', '02', '006', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERINTIS PEMBUATAN AGREGAT'),
(3851, '4', '03', '04', '02', '999', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN PERINTIS LAINNYA'),
(3852, '4', '03', '04', '03', '000', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN TERAPAN'),
(3853, '4', '03', '04', '03', '001', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN TERAPAN'),
(3854, '4', '03', '04', '03', '999', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN TERAPAN LAINNYA'),
(3855, '4', '03', '04', '99', '000', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN LAINNYA'),
(3856, '4', '03', '04', '99', '999', 'INSTALASI PENGOLAHAN BAHAN BANGUNAN LAINNYA'),
(3857, '4', '03', '05', '00', '000', 'INSTALASI PEMBANGKIT LISTRIK'),
(3858, '4', '03', '05', '01', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA AIR (PLTA)'),
(3859, '4', '03', '05', '01', '001', 'INSTALASI PLTA KAPASITAS KECIL'),
(3860, '4', '03', '05', '01', '002', 'INSTALASI PLTA KAPASITAS SEDANG'),
(3861, '4', '03', '05', '01', '003', 'INSTALASI PLTA KAPASITAS BESAR'),
(3862, '4', '03', '05', '01', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA AIR (PLTA) LAINNYA'),
(3863, '4', '03', '05', '02', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA DIESEL (PLTD)'),
(3864, '4', '03', '05', '02', '001', 'INSTALASI PLTD KAPASITAS KECIL'),
(3865, '4', '03', '05', '02', '002', 'INSTALASI PLTD KAPASITAS SEDANG'),
(3866, '4', '03', '05', '02', '003', 'INSTALASI PLTD KAPASITAS BESAR'),
(3867, '4', '03', '05', '02', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA DIESEL (PLTD) LAINNYA'),
(3868, '4', '03', '05', '03', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA MIKRO HIDRO (PLTM)'),
(3869, '4', '03', '05', '03', '001', 'INSTALASI PLTM KAPASITAS KECIL'),
(3870, '4', '03', '05', '03', '002', 'INSTALASI PLTM KAPASITAS SEDANG'),
(3871, '4', '03', '05', '03', '003', 'INSTALASI PLTM KAPASITAS BESAR'),
(3872, '4', '03', '05', '03', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA MIKRO HIDRO (PLTM) LAINNYA'),
(3873, '4', '03', '05', '04', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA ANGIN (PLTAN)'),
(3874, '4', '03', '05', '04', '001', 'INSTALASI PLTAN KAPASITAS KECIL'),
(3875, '4', '03', '05', '04', '002', 'INSTALASI PLTAN KAPASITAS SEDANG'),
(3876, '4', '03', '05', '04', '003', 'INSTALASI PLTAN KAPASITAS BESAR'),
(3877, '4', '03', '05', '04', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA ANGIN (PLTAN) LAINNYA'),
(3878, '4', '03', '05', '05', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA UAP (PLTU)'),
(3879, '4', '03', '05', '05', '001', 'INSTALASI PLTU KAPASITAS KECIL'),
(3880, '4', '03', '05', '05', '002', 'INSTALASI PLTU KAPASITAS SEDANG'),
(3881, '4', '03', '05', '05', '003', 'INSTALASI PLTU KAPASITAS BESAR'),
(3882, '4', '03', '05', '05', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA UAP (PLTU) LAINNYA'),
(3883, '4', '03', '05', '06', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA NUKLIR (PLTN)'),
(3884, '4', '03', '05', '06', '001', 'INSTALASI PLTN KAPASITAS KECIL'),
(3885, '4', '03', '05', '06', '002', 'INSTALASI PLTN KAPASITAS SEDANG'),
(3886, '4', '03', '05', '06', '003', 'INSTALASI PLTN KAPASITAS BESAR'),
(3887, '4', '03', '05', '06', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA NUKLIR (PLTN) LAINNYA'),
(3888, '4', '03', '05', '07', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA GAS (PLTG)'),
(3889, '4', '03', '05', '07', '001', 'INSTALASI PLTG KAPASITAS KECIL'),
(3890, '4', '03', '05', '07', '002', 'INSTALASI PLTG KAPASITAS SEDANG'),
(3891, '4', '03', '05', '07', '003', 'INSTALASI PLTG KAPASITAS BESAR'),
(3892, '4', '03', '05', '07', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA GAS (PLTG) LAINNYA'),
(3893, '4', '03', '05', '08', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA PANAS BUMI (PLTP)'),
(3894, '4', '03', '05', '08', '001', 'INSTALASI PLTP KAPASITAS KECIL'),
(3895, '4', '03', '05', '08', '002', 'INSTALASI PLTP KAPASITAS SEDANG'),
(3896, '4', '03', '05', '08', '003', 'INSTALASI PLTP KAPASITAS BESAR'),
(3897, '4', '03', '05', '08', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA PANAS BUMI (PLTP) LAINNYA'),
(3898, '4', '03', '05', '09', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA SURYA (PLTS)'),
(3899, '4', '03', '05', '09', '001', 'INSTALASI PLTS KAPASITAS KECIL'),
(3900, '4', '03', '05', '09', '002', 'INSTALASI PLTS KAPASITAS SEDANG'),
(3901, '4', '03', '05', '09', '003', 'INSTALASI PLTS KAPASITAS BESAR'),
(3902, '4', '03', '05', '09', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA SURYA (PLTS) LAIINNYA'),
(3903, '4', '03', '05', '10', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA BIOGAS (PLTB)'),
(3904, '4', '03', '05', '10', '001', 'INSTALASI PLTB KAPASITAS KECIL'),
(3905, '4', '03', '05', '10', '002', 'INSTALASI PLTB KAPASITAS SEDANG'),
(3906, '4', '03', '05', '10', '003', 'INSTALASI PLTB KAPASITAS BESAR'),
(3907, '4', '03', '05', '10', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA BIOGAS (PLTB) LAINNYA'),
(3908, '4', '03', '05', '11', '000', 'INSTALASI PEMBANGKIT LISTRIK TENAGA SAMUDERA / GELOMBANG SAMUDERA'),
(3909, '4', '03', '05', '11', '001', 'INSTALASI PLTSM KAPASITAS KECIL'),
(3910, '4', '03', '05', '11', '002', 'INSTALASI PLTSM KAPASITAS SEDANG'),
(3911, '4', '03', '05', '11', '003', 'INSTALASI PLTSM KAPASITAS BESAR'),
(3912, '4', '03', '05', '11', '999', 'INSTALASI PEMBANGKIT LISTRIK TENAGA SAMUDERA / GELOMBANG SAMUDERA LAINNYA'),
(3913, '4', '03', '05', '99', '000', 'INSTALASI PEMBANGKIT LISTRIK LAINNYA');
INSERT INTO `tweb_aset` (`id_aset`, `golongan`, `bidang`, `kelompok`, `sub_kelompok`, `sub_sub_kelompok`, `nama`) VALUES
(3914, '4', '03', '05', '99', '999', 'INSTALASI PEMBANGKIT LISTRIK LAINNYA'),
(3915, '4', '03', '06', '00', '000', 'INSTALASI GARDU LISTRIK'),
(3916, '4', '03', '06', '01', '000', 'INSTALASI GARDU LISTRIK INDUK'),
(3917, '4', '03', '06', '01', '001', 'INSTALASI GARDU LISTRIK INDUK KAPASITAS KECIL'),
(3918, '4', '03', '06', '01', '002', 'INSTALASI GARDU LISTRIK INDUK KAPASITAS SEDANG'),
(3919, '4', '03', '06', '01', '003', 'INSTALASI GARDU LISTRIK INDUK KAPASITAS BESAR'),
(3920, '4', '03', '06', '01', '999', 'INSTALASI GARDU LISTRIK INDUK LAINNYA'),
(3921, '4', '03', '06', '02', '000', 'INSTALASI GARDU LISTRIK DISTRIBUSI'),
(3922, '4', '03', '06', '02', '001', 'INSTALASI GARDU LISTRIK DISTRIBUSI KAPASITAS KECIL'),
(3923, '4', '03', '06', '02', '002', 'INSTALASI GARDU LISTRIK DISTRIBUSI KAPASITAS SEDANG'),
(3924, '4', '03', '06', '02', '003', 'INSTALASI GARDU LISTRIK DISTRIBUSI KAPASITAS BESAR'),
(3925, '4', '03', '06', '02', '999', 'INSTALASI GARDU LISTRIK DISTRIBUSI LAINNYA'),
(3926, '4', '03', '06', '03', '000', 'INSTALASI PUSAT PENGATUR LISTRIK'),
(3927, '4', '03', '06', '03', '001', 'INSTALASI PUSAT PENGATUR LISTRIK KAPASITAS KECIL'),
(3928, '4', '03', '06', '03', '002', 'INSTALASI PUSAT PENGATUR LISTRIK KAPASITAS SEDANG'),
(3929, '4', '03', '06', '03', '003', 'INSTALASI PUSAT PENGATUR LISTRIK KAPASITAS BESAR'),
(3930, '4', '03', '06', '03', '999', 'INSTALASI PUSAT PENGATUR LISTRIK LAINNYA'),
(3931, '4', '03', '06', '99', '000', 'INSTALASI GARDU LISTRIK LAINNYA'),
(3932, '4', '03', '06', '99', '999', 'INSTALASI GARDU LISTRIK LAINNYA'),
(3933, '4', '03', '07', '00', '000', 'INSTALASI LAIN'),
(3934, '4', '03', '07', '01', '000', 'INSTALASI LAIN'),
(3935, '4', '03', '07', '01', '001', 'INSTALASI GENERATING SET'),
(3936, '4', '03', '07', '01', '002', 'INSTALASI AC'),
(3937, '4', '03', '07', '01', '003', 'INSTALASI BUILDING AUTOMATION SYSTEM (BAS)'),
(3938, '4', '03', '07', '01', '004', 'INSTALASI KOMPUTER'),
(3939, '4', '03', '07', '01', '999', 'INSTALASI LAIN-LAIN'),
(3940, '4', '04', '00', '00', '000', 'JARINGAN'),
(3941, '4', '04', '01', '00', '000', 'JARINGAN AIR MINUM'),
(3942, '4', '04', '01', '01', '000', 'JARINGAN PEMBAWA'),
(3943, '4', '04', '01', '01', '001', 'JARINGAN PEMBAWA KAPASITAS KECIL'),
(3944, '4', '04', '01', '01', '002', 'JARINGAN PEMBAWA KAPASITAS SEDANG'),
(3945, '4', '04', '01', '01', '003', 'JARINGAN PEMBAWA KAPASITAS BESAR'),
(3946, '4', '04', '01', '01', '999', 'JARINGAN PEMBAWA LAINNYA'),
(3947, '4', '04', '01', '02', '000', 'JARINGAN INDUK DISTRIBUSI'),
(3948, '4', '04', '01', '02', '001', 'JARINGAN INDUK DISTRIBUSI KAPASITAS KECIL'),
(3949, '4', '04', '01', '02', '002', 'JARINGAN INDUK DISTRIBUSI KAPASITAS SEDANG'),
(3950, '4', '04', '01', '02', '003', 'JARINGAN INDUK DISTRIBUSI KAPASITAS BESAR'),
(3951, '4', '04', '01', '02', '999', 'JARINGAN INDUK DISTRIBUSI LAINNYA'),
(3952, '4', '04', '01', '03', '000', 'JARINGAN CABANG DISTRIBUSI'),
(3953, '4', '04', '01', '03', '001', 'JARINGAN CABANG DISTRIBUSI KAPASITAS KECIL'),
(3954, '4', '04', '01', '03', '002', 'JARINGAN CABANG DISTRIBUSI KAPASITAS SEDANG'),
(3955, '4', '04', '01', '03', '003', 'JARINGAN CABANG DISTRIBUSI KAPASITAS BESAR'),
(3956, '4', '04', '01', '03', '999', 'JARINGAN CABANG DISTRIBUSI LAINNYA'),
(3957, '4', '04', '01', '04', '000', 'JARINGAN SAMBUNGAN KE RUMAH'),
(3958, '4', '04', '01', '04', '001', 'JARINGAN SAMBUNGAN KE RUMAH KAPASITAS KECIL'),
(3959, '4', '04', '01', '04', '002', 'JARINGAN SAMBUNGAN KE RUMAH KAPASITAS SEDANG'),
(3960, '4', '04', '01', '04', '003', 'JARINGAN SAMBUNGAN KE RUMAH KAPASITAS BESAR'),
(3961, '4', '04', '01', '04', '999', 'JARINGAN SAMBUNGAN KE RUMAH LAINNYA'),
(3962, '4', '04', '01', '99', '000', 'JARINGAN AIR MINUM LAINNYA'),
(3963, '4', '04', '01', '99', '999', 'JARINGAN AIR MINUM LAINNYA'),
(3964, '4', '04', '02', '00', '000', 'JARINGAN LISTRIK'),
(3965, '4', '04', '02', '01', '000', 'JARINGAN TRANSMISI'),
(3966, '4', '04', '02', '01', '001', 'JARINGAN TRANSMISI TEGANGAN DIATAS 300 KVA'),
(3967, '4', '04', '02', '01', '002', 'JARINGAN TRANSMISI TEGANGAN 100 S/D 300 KVA'),
(3968, '4', '04', '02', '01', '003', 'JARINGAN TRANSMISI TEGANGAN DIBAWAH 100 KVA'),
(3969, '4', '04', '02', '01', '999', 'JARINGAN TRANSMISI LAINNYA'),
(3970, '4', '04', '02', '02', '000', 'JARINGAN DISTRIBUSI'),
(3971, '4', '04', '02', '02', '001', 'JARINGAN DISTRIBUSI TEGANGAN DIATAS 20 KVA'),
(3972, '4', '04', '02', '02', '002', 'JARINGAN DISTRIBUSI TEGANGAN 1 S/D 20 KVA'),
(3973, '4', '04', '02', '02', '003', 'JARINGAN DISTRIBUSI TEGANGAN DIBAWAH 1 KVA'),
(3974, '4', '04', '02', '02', '999', 'JARINGAN DISTRIBUSI LAINNYA'),
(3975, '4', '04', '02', '99', '000', 'JARINGAN LISTRIK LAINNYA'),
(3976, '4', '04', '02', '99', '999', 'JARINGAN LISTRIK LAINNYA'),
(3977, '4', '04', '03', '00', '000', 'JARINGAN TELEPON'),
(3978, '4', '04', '03', '01', '000', 'JARINGAN TELEPON DIATAS TANAH'),
(3979, '4', '04', '03', '01', '001', 'JARINGAN TELEPON DIATAS TANAH KAPASITAS KECIL'),
(3980, '4', '04', '03', '01', '002', 'JARINGAN TELEPON DIATAS TANAH KAPASITAS SEDANG'),
(3981, '4', '04', '03', '01', '003', 'JARINGAN TELEPON DIATAS TANAH KAPASITAS BESAR'),
(3982, '4', '04', '03', '01', '999', 'JARINGAN TELEPON DIATAS TANAH LAINNYA'),
(3983, '4', '04', '03', '02', '000', 'JARINGAN TELEPON DIBAWAH TANAH'),
(3984, '4', '04', '03', '02', '001', 'JARINGAN TELEPON DIBAWAH TANAH KAPASITAS KECIL'),
(3985, '4', '04', '03', '02', '002', 'JARINGAN TELEPON DIBAWAH TANAH KAPASITAS SEDANG'),
(3986, '4', '04', '03', '02', '003', 'JARINGAN TELEPON DIBAWAH TANAH KAPASITAS BESAR'),
(3987, '4', '04', '03', '02', '999', 'JARINGAN TELEPON DIBAWAH TANAH LAINNYA'),
(3988, '4', '04', '03', '03', '000', 'JARINGAN TELEPON DIDALAM AIR'),
(3989, '4', '04', '03', '03', '001', 'JARINGAN TELEPON DIDALAM AIR KAPASITAS KECIL'),
(3990, '4', '04', '03', '03', '002', 'JARINGAN TELEPON DIDALAM AIR KAPASITAS SEDANG'),
(3991, '4', '04', '03', '03', '003', 'JARINGAN TELEPON DIDALAM AIR KAPASITAS BESAR'),
(3992, '4', '04', '03', '03', '999', 'JARINGAN TELEPON DIDALAM AIR LAINNYA'),
(3993, '4', '04', '03', '04', '000', 'JARINGAN DENGAN MEDIA UDARA'),
(3994, '4', '04', '03', '04', '001', 'JARINGAN SATELIT'),
(3995, '4', '04', '03', '04', '002', 'JARINGAN RADIO'),
(3996, '4', '04', '03', '04', '999', 'JARINGAN DENGAN MEDIA UDARA LAINNYA'),
(3997, '4', '04', '03', '99', '000', 'JARINGAN TELEPON LAINNYA'),
(3998, '4', '04', '03', '99', '999', 'JARINGAN TELEPON LAINNYA'),
(3999, '4', '04', '04', '00', '000', 'JARINGAN GAS'),
(4000, '4', '04', '04', '01', '000', 'JARINGAN PIPA GAS TRANSMISI'),
(4001, '4', '04', '04', '01', '001', 'JARINGAN PIPA BAJA'),
(4002, '4', '04', '04', '01', '999', 'JARINGAN PIPA GAS TRANSMISI LAINNYA'),
(4003, '4', '04', '04', '02', '000', 'JARINGAN PIPA DISTRIBUSI'),
(4004, '4', '04', '04', '02', '001', 'JARINGAN PIPA DISTRIBUSI TEKANAN TINGGI'),
(4005, '4', '04', '04', '02', '002', 'JARINGAN PIPA DISTRIBUSI TEKANAN MENENGAH PIPA BAJA'),
(4006, '4', '04', '04', '02', '003', 'JARINGAN PIPA DISTRIBUSI TEKANAN MENENGAH PIPA PE'),
(4007, '4', '04', '04', '02', '004', 'JARINGAN PIPA DISTRIBUSI TEKANAN RENDAH PIPA BAJA'),
(4008, '4', '04', '04', '02', '005', 'JARINGAN PIPA DISTRIBUSI TEKANAN RENDAH PIPA PC'),
(4009, '4', '04', '04', '02', '999', 'JARINGAN PIPA DISTRIBUSI LAINNYA'),
(4010, '4', '04', '04', '03', '000', 'JARINGAN PIPA DINAS'),
(4011, '4', '04', '04', '03', '001', 'JARINGAN PIPA DINAS PIPA BAJA'),
(4012, '4', '04', '04', '03', '002', 'JARINGAN PIPA DINAS PIPA PE'),
(4013, '4', '04', '04', '03', '999', 'JARINGAN PIPA DINAS LAINNYA'),
(4014, '4', '04', '04', '04', '000', 'JARINGAN BBM'),
(4015, '4', '04', '04', '04', '001', 'JARINGAN BBM BENSIN'),
(4016, '4', '04', '04', '04', '002', 'JARINGAN BBM SOLAR'),
(4017, '4', '04', '04', '04', '003', 'JARINGAN BBM MINYAK TANAH'),
(4018, '4', '04', '04', '04', '999', 'JARINGAN BBM LAINNYA'),
(4019, '4', '04', '04', '99', '000', 'JARINGAN GAS LAINNYA'),
(4020, '4', '04', '04', '99', '999', 'JARINGAN GAS LAINNYA'),
(4021, '5', '00', '00', '00', '000', 'ASET TETAP LAINNYA'),
(4022, '5', '01', '00', '00', '000', 'BAHAN PERPUSTAKAAN'),
(4023, '5', '01', '01', '00', '000', 'BAHAN PERPUSTAKAAN TERCETAK'),
(4024, '5', '01', '01', '01', '000', 'BUKU'),
(4025, '5', '01', '01', '01', '001', 'MONOGRAF'),
(4026, '5', '01', '01', '01', '002', 'REFERENSI'),
(4027, '5', '01', '01', '01', '999', 'BUKU LAINNYA'),
(4028, '5', '01', '01', '02', '000', 'SERIAL'),
(4029, '5', '01', '01', '02', '001', 'SURAT KABAR'),
(4030, '5', '01', '01', '02', '002', 'MAJALAH'),
(4031, '5', '01', '01', '02', '003', 'BULETIN'),
(4032, '5', '01', '01', '02', '004', 'LAPORAN'),
(4033, '5', '01', '01', '02', '999', 'SERIAL LAINNYA'),
(4034, '5', '01', '01', '99', '000', 'TERCETAK LAINNYA'),
(4035, '5', '01', '01', '99', '999', 'BAHAN PERPUSTAKAAN TERCETAK LAINNYA'),
(4036, '5', '01', '02', '00', '000', 'BAHAN PERPUSTAKAAN TEREKAM DAN BENTUK MIKRO'),
(4037, '5', '01', '02', '01', '000', 'AUDIO VISUAL'),
(4038, '5', '01', '02', '01', '001', 'KASET'),
(4039, '5', '01', '02', '01', '002', 'VIDEO'),
(4040, '5', '01', '02', '01', '003', 'CD/VCD/DVD/LD'),
(4041, '5', '01', '02', '01', '004', 'PITA FILM'),
(4042, '5', '01', '02', '01', '005', 'PITA SUARA'),
(4043, '5', '01', '02', '01', '006', 'PIRINGAN HITAM'),
(4044, '5', '01', '02', '01', '028', 'PETA DIGITAL'),
(4045, '5', '01', '02', '01', '999', 'AUDIO VISUAL LAINNYA'),
(4046, '5', '01', '02', '02', '000', 'BENTUK MIKRO (MICROFORM)'),
(4047, '5', '01', '02', '02', '001', 'MIKROFILM'),
(4048, '5', '01', '02', '02', '002', 'MIKROFISCH'),
(4049, '5', '01', '02', '02', '003', 'SLIDE'),
(4050, '5', '01', '02', '02', '999', 'BENTUK MIKRO/MIKROFORM LAINNYA'),
(4051, '5', '01', '02', '99', '000', 'TEREKAM DAN BENTUK MIKRO LAINNYA'),
(4052, '5', '01', '02', '99', '999', 'BAHAN PERPUSTAKAAN TEREKAM DAN BENTUK MIKRO LAINNYA'),
(4053, '5', '01', '03', '00', '000', '\"KARTOGRAFI'),
(4054, '5', '01', '03', '01', '000', 'BAHAN KARTOGRAFI'),
(4055, '5', '01', '03', '01', '001', 'PETA (MAP)'),
(4056, '5', '01', '03', '01', '002', 'ATLAS'),
(4057, '5', '01', '03', '01', '003', 'BLUE PRINT'),
(4058, '5', '01', '03', '01', '004', 'BOLA DUNIA (GLOBE)'),
(4059, '5', '01', '03', '01', '999', 'BAHAN KARTOGRAFI LAINNYA'),
(4060, '5', '01', '03', '02', '000', 'NASKAH (MANUSKRIP) / ASLI'),
(4061, '5', '01', '03', '02', '001', 'NASKAH/MANUSKRIP BERBAHAN KERTAS'),
(4062, '5', '01', '03', '02', '002', 'NASKAH/MANUSKRIP BERBAHAN DAUN'),
(4063, '5', '01', '03', '02', '003', 'NASKAH/MANUSKRIP BERBAHAN KAYU'),
(4064, '5', '01', '03', '02', '004', 'NASKAH/MANUSKRIP BERBAHAN BAMBU'),
(4065, '5', '01', '03', '02', '005', 'NASKAH/MANUSKRIP BERBAHAN KULIT KAYU'),
(4066, '5', '01', '03', '02', '006', 'NASKAH/MANUSKRIP BERBAHAN KULIT BINATANG'),
(4067, '5', '01', '03', '02', '007', 'NASKAH/MANUSKRIP BERBAHAN TULANG/TANDUK'),
(4068, '5', '01', '03', '02', '008', 'NASKAH/MANUSKRIP BERBAHAN TEMPURUNG'),
(4069, '5', '01', '03', '02', '999', 'NASKAH/MANUSKRIP BERBAHAN LAINNYA'),
(4070, '5', '01', '03', '03', '000', 'LUKISAN DAN UKIRAN'),
(4071, '5', '01', '03', '03', '001', 'LUKISAN KANVAS'),
(4072, '5', '01', '03', '03', '002', '\"LUKISAN BATU'),
(4073, '5', '01', '03', '03', '003', 'UKIRAN KAYU DAN SEJENISNYA'),
(4074, '5', '01', '03', '03', '004', 'UKIRAN LOGAM DAN SEJENISNYA'),
(4075, '5', '01', '03', '03', '005', 'UKIRAN BATU DAN SEJENISNYA'),
(4076, '5', '01', '03', '03', '999', 'UKIRAN DAN LUKISAN LAINNYA'),
(4077, '5', '01', '03', '99', '000', '\"KARTOGRAFI'),
(4078, '5', '01', '03', '99', '999', '\"KARTOGRAFI'),
(4079, '5', '02', '00', '00', '000', 'BARANG BERCORAK KESENIAN/KEBUDAYAAN/OLAHRAGA'),
(4080, '5', '02', '01', '00', '000', 'BARANG BERCORAK KESENIAN'),
(4081, '5', '02', '01', '01', '000', 'ALAT MUSIK'),
(4082, '5', '02', '01', '01', '001', 'ALAT MUSIK TRADISIONAL/DAERAH'),
(4083, '5', '02', '01', '01', '002', 'ALAT MUSIK MODERN/BAND'),
(4084, '5', '02', '01', '01', '999', 'ALAT MUSIK LAINNYA'),
(4085, '5', '02', '01', '02', '000', 'LUKISAN'),
(4086, '5', '02', '01', '02', '001', 'LUKISAN CAT AIR'),
(4087, '5', '02', '01', '02', '002', 'SULAMAN / TEMPELAN'),
(4088, '5', '02', '01', '02', '003', 'LUKISAN CAT MINYAK'),
(4089, '5', '02', '01', '02', '004', 'LUKISAN BULU'),
(4090, '5', '02', '01', '02', '005', 'SENI RELIEF'),
(4091, '5', '02', '01', '02', '999', 'LUKISAN LAINNYA'),
(4092, '5', '02', '01', '03', '000', 'ALAT PERAGA KESENIAN'),
(4093, '5', '02', '01', '03', '001', 'WAYANG GOLEK'),
(4094, '5', '02', '01', '03', '002', 'WAYANG KULIT'),
(4095, '5', '02', '01', '03', '999', 'ALAT PERAGA KESENIAN LAINNYA'),
(4096, '5', '02', '01', '99', '000', 'BARANG BERCORAK KESENIAN LAINNYA'),
(4097, '5', '02', '01', '99', '999', 'BARANG BERCORAK KESENIAN LAINNYA'),
(4098, '5', '02', '02', '00', '000', 'ALAT BERCORAK KEBUDAYAAN'),
(4099, '5', '02', '02', '01', '000', 'PAHATAN'),
(4100, '5', '02', '02', '01', '001', 'PAHATAN BATU'),
(4101, '5', '02', '02', '01', '002', 'PAHATAN KAYU'),
(4102, '5', '02', '02', '01', '003', 'PAHATAN LOGAM'),
(4103, '5', '02', '02', '01', '999', 'PAHATAN LAINNYA'),
(4104, '5', '02', '02', '02', '000', '\"MAKET'),
(4105, '5', '02', '02', '02', '001', 'MAKET/MINIATUR/REPLIKA'),
(4106, '5', '02', '02', '02', '002', 'FOTO DOKUMEN'),
(4107, '5', '02', '02', '02', '003', 'NASKAH KUNO'),
(4108, '5', '02', '02', '02', '004', 'MATA UANG/ NUMISMATIK'),
(4109, '5', '02', '02', '02', '005', 'PERHIASAN'),
(4110, '5', '02', '02', '02', '006', 'BARANG KERAMIK/ GERABAH'),
(4111, '5', '02', '02', '02', '007', 'ARCA/ PATUNG'),
(4112, '5', '02', '02', '02', '008', 'BENDA KUNO/ UNIK'),
(4113, '5', '02', '02', '02', '009', 'FOSIL'),
(4114, '5', '02', '02', '02', '010', 'MUMY'),
(4115, '5', '02', '02', '02', '999', 'MAKET DAN FOTO DOKUMEN LAINNYA'),
(4116, '5', '02', '02', '99', '000', 'ALAT BERCORAK KEBUDAYAAN LAINNYA'),
(4117, '5', '02', '02', '99', '999', 'ALAT BERCORAK KEBUDAYAAN LAINNYA'),
(4118, '5', '02', '03', '00', '000', 'TANDA PENGHARGAAN BIDANG OLAH RAGA'),
(4119, '5', '02', '03', '01', '000', 'TANDA PENGHARGAAN'),
(4120, '5', '02', '03', '01', '001', 'PIALA'),
(4121, '5', '02', '03', '01', '002', 'MEDALI'),
(4122, '5', '02', '03', '01', '003', 'PIAGAM'),
(4123, '5', '02', '03', '01', '999', 'TANDA PENGHARGAAN LAINNYA'),
(4124, '5', '02', '03', '99', '000', 'TANDA PENGHARGAAN BIDANG OLAH RAGA LAINNYA'),
(4125, '5', '02', '03', '99', '999', 'TANDA PENGHARGAAN BIDANG OLAH RAGA LAINNYA'),
(4126, '5', '03', '00', '00', '000', 'HEWAN'),
(4127, '5', '03', '01', '00', '000', 'HEWAN PIARAAN'),
(4128, '5', '03', '01', '01', '000', 'HEWAN PENGAMAN'),
(4129, '5', '03', '01', '01', '001', 'ANJING PELACAK'),
(4130, '5', '03', '01', '01', '002', 'ANJING PENJAGA'),
(4131, '5', '03', '01', '01', '999', 'HEWAN PENGAMAN LAINNYA'),
(4132, '5', '03', '01', '02', '000', 'HEWAN PENGANGKUT'),
(4133, '5', '03', '01', '02', '001', 'GAJAH'),
(4134, '5', '03', '01', '02', '002', 'KUDA (HEWAN PENGANGKUT)'),
(4135, '5', '03', '01', '02', '999', 'HEWAN PENGANGKUT LAINNYA'),
(4136, '5', '03', '01', '99', '000', 'HEWAN PIARAAN LAINNYA'),
(4137, '5', '03', '01', '99', '999', 'HEWAN PIARAAN LAINNYA'),
(4138, '5', '03', '02', '00', '000', 'TERNAK'),
(4139, '5', '03', '02', '01', '000', 'TERNAK POTONG'),
(4140, '5', '03', '02', '01', '001', 'BABI'),
(4141, '5', '03', '02', '01', '002', 'DOMBA'),
(4142, '5', '03', '02', '01', '003', 'KAMBING'),
(4143, '5', '03', '02', '01', '004', 'KERBAU'),
(4144, '5', '03', '02', '01', '005', 'SAPI POTONG'),
(4145, '5', '03', '02', '01', '999', 'TERNAK POTONG LAINNYA'),
(4146, '5', '03', '02', '02', '000', 'TERNAK PERAH'),
(4147, '5', '03', '02', '02', '001', 'SAPI PERAH'),
(4148, '5', '03', '02', '02', '002', 'DOMBA PERAH'),
(4149, '5', '03', '02', '02', '003', 'KAMBING PERAH'),
(4150, '5', '03', '02', '02', '999', 'TERNAK PERAH LAINNYA'),
(4151, '5', '03', '02', '03', '000', 'TERNAK UNGGAS'),
(4152, '5', '03', '02', '03', '001', 'AYAM'),
(4153, '5', '03', '02', '03', '002', 'BURUNG'),
(4154, '5', '03', '02', '03', '003', 'ITIK'),
(4155, '5', '03', '02', '03', '999', 'TERNAK UNGGAS LAINNYA'),
(4156, '5', '03', '02', '99', '000', 'TERNAK LAINNYA'),
(4157, '5', '03', '02', '99', '999', 'TERNAK LAINNYA'),
(4158, '5', '03', '03', '00', '000', 'HEWAN LAINNYA'),
(4159, '5', '03', '03', '01', '000', 'HEWAN LAINNYA'),
(4160, '5', '03', '03', '01', '001', 'HEWAN LAINNYA'),
(4161, '5', '04', '00', '00', '000', 'IKAN'),
(4162, '5', '04', '01', '00', '000', 'IKAN BERSIRIP (PISCES/IKAN BERSIRIP)'),
(4163, '5', '04', '01', '01', '000', 'IKAN BUDIDAYA'),
(4164, '5', '04', '01', '01', '001', 'IKAN AIR TAWAR BUDIDAYA'),
(4165, '5', '04', '01', '01', '002', 'IKAN AIR LAUT BUDIDAYA'),
(4166, '5', '04', '01', '01', '003', 'IKAN AIR PAYAU BUDIDAYA'),
(4167, '5', '04', '01', '01', '004', 'IKAN HIAS AIR TAWAR BUDIDAYA'),
(4168, '5', '04', '01', '01', '005', 'IKAN HIAS AIR PAYAU/LAUT BUDIDAYA'),
(4169, '5', '04', '01', '01', '999', 'IKAN BUDIDAYA LAINNYA'),
(4170, '5', '04', '02', '00', '000', '\"CRUSTEA (UDANG'),
(4171, '5', '04', '02', '01', '000', '\"CRUSTEA BUDIDAYA (UDANG'),
(4172, '5', '04', '02', '01', '001', 'UDANG'),
(4173, '5', '04', '02', '01', '002', 'RAJUNGAN'),
(4174, '5', '04', '02', '01', '003', 'KEPITING'),
(4175, '5', '04', '02', '01', '999', '\"CRUSTEA (UDANG'),
(4176, '5', '04', '03', '00', '000', '\"MOLLUSCA (KERANG'),
(4177, '5', '04', '03', '01', '000', '\"MOLLUSCA BUDIDAYA (KERANG'),
(4178, '5', '04', '03', '01', '001', 'KERANG'),
(4179, '5', '04', '03', '01', '002', 'TIRAM'),
(4180, '5', '04', '03', '01', '003', 'CUMI-CUMI'),
(4181, '5', '04', '03', '01', '004', 'GURITA'),
(4182, '5', '04', '03', '01', '005', 'SIPUT'),
(4183, '5', '04', '03', '01', '999', '\"MOLLUSCA (KERANG'),
(4184, '5', '04', '04', '00', '000', 'COELENTERATA (UBUR-UBUR DAN SEBANGSANYA)'),
(4185, '5', '04', '04', '01', '000', 'COELENTERATA BUDIDAYA (UBUR-UBUR DAN SEBANGSANYA)'),
(4186, '5', '04', '04', '01', '001', 'UBUR-UBUR BUDIDAYA'),
(4187, '5', '04', '04', '01', '999', 'COELENTERATA (UBUR-UBUR DAN SEBANGSANYA) LAINNYA'),
(4188, '5', '04', '05', '00', '000', '\"ECHINODERMATA (TRIPANG'),
(4189, '5', '04', '05', '01', '000', '\"ECHINODERMATA BUDIDAYA (TRIPANG'),
(4190, '5', '04', '05', '01', '001', 'TERIPANG'),
(4191, '5', '04', '05', '01', '002', 'BULU BABI'),
(4192, '5', '04', '05', '01', '999', '\"ECHINODERMATA (TRIPANG'),
(4193, '5', '04', '06', '00', '000', 'AMPHIBIA (KODOK DAN SEBANGSANYA)'),
(4194, '5', '04', '06', '01', '000', 'AMPHIBIA BUDIDAYA (KODOK DAN SEBANGSANYA)'),
(4195, '5', '04', '06', '01', '001', 'KODOK'),
(4196, '5', '04', '06', '01', '002', 'SEBANGSA KODOK'),
(4197, '5', '04', '06', '01', '999', 'AMPHIBIA (KODOK DAN SEBANGSANYA) LAINNYA'),
(4198, '5', '04', '07', '00', '000', '\"REPTILIA (BUAYA'),
(4199, '5', '04', '07', '01', '000', '\"REPTILIA BUDIDAYA (BUAYA'),
(4200, '5', '04', '07', '01', '001', 'PENYU'),
(4201, '5', '04', '07', '01', '002', 'KURA-KURA'),
(4202, '5', '04', '07', '01', '003', 'BIAWAK'),
(4203, '5', '04', '07', '01', '004', 'ULAR AIR'),
(4204, '5', '04', '07', '01', '999', '\"REPTILIA (BUAYA'),
(4205, '5', '04', '08', '00', '000', '\"MAMMALIA (PAUS'),
(4206, '5', '04', '08', '01', '000', '\"MAMMALIA BUDIDAYA (PAUS'),
(4207, '5', '04', '08', '01', '001', 'PAUS'),
(4208, '5', '04', '08', '01', '002', 'LUMBA-LUMBA'),
(4209, '5', '04', '08', '01', '003', 'PESUT'),
(4210, '5', '04', '08', '01', '004', 'DUYUNG'),
(4211, '5', '04', '08', '01', '999', '\"MAMMALIA (PAUS'),
(4212, '5', '04', '09', '00', '000', 'ALGAE (RUMPUT LAUT DAN TUMBUH-TUMBUHAN LAIN YANG HIDUP DI DALAM AIR)'),
(4213, '5', '04', '09', '01', '000', 'ALGAE BUDIDAYA (RUMPUT LAUT DAN TUMBUH-TUMBUHAN LAIN YANG HIDUP DI DALAM AIR)'),
(4214, '5', '04', '09', '01', '001', 'RUMPUT LAUT'),
(4215, '5', '04', '09', '01', '002', 'TUMBUH-TUMBUHAN LAIN YANG HIDUP DI DALAM AIR'),
(4216, '5', '04', '09', '01', '999', 'ALGAE (RUMPUT LAUT DAN TUMBUH-TUMBUHAN LAIN YANG HIDUP DI DALAM AIR) LAINNYA'),
(4217, '5', '04', '10', '00', '000', 'BIOTA PERAIRAN LAINNYA'),
(4218, '5', '04', '10', '01', '000', 'BUDIDAYA BIOTA PERAIRAN LAINNYA'),
(4219, '5', '04', '10', '01', '001', 'BIOTA PERAIRAN LAINNYA'),
(4220, '5', '05', '00', '00', '000', 'TANAMAN'),
(4221, '5', '05', '01', '00', '000', 'TANAMAN'),
(4222, '5', '05', '01', '01', '000', 'TANAMAN'),
(4223, '5', '05', '01', '01', '001', 'TANAMAN KERAS'),
(4224, '5', '05', '01', '01', '002', 'TANAMAN INDUSTRI'),
(4225, '5', '05', '01', '01', '003', 'TANAMAN PERKEBUNAN'),
(4226, '5', '05', '01', '01', '004', 'TANAMAN HORTIKULTURA'),
(4227, '5', '05', '01', '01', '005', 'TANAMAN PANGAN'),
(4228, '5', '05', '01', '01', '006', 'TANAMAN HIAS'),
(4229, '5', '05', '01', '01', '007', 'TANAMAN OBAT'),
(4230, '5', '05', '01', '01', '008', 'TANAMAN PLASMA'),
(4231, '5', '05', '01', '01', '999', 'TANAMAN LAINNYA'),
(4232, '5', '06', '00', '00', '000', 'ASET TETAP DALAM RENOVASI'),
(4233, '5', '06', '01', '00', '000', 'ASET TETAP DALAM RENOVASI'),
(4234, '5', '06', '01', '01', '000', 'ASET TETAP DALAM RENOVASI'),
(4235, '5', '06', '01', '01', '001', 'TANAH DALAM RENOVASI'),
(4236, '5', '06', '01', '01', '002', 'PERALATAN DAN MESIN DALAM RENOVASI'),
(4237, '5', '06', '01', '01', '003', 'GEDUNG DAN BANGUNAN DALAM RENOVASI'),
(4238, '5', '06', '01', '01', '004', '\"JALAN'),
(4239, '5', '06', '01', '01', '005', 'ASET TETAP LAINNYA DALAM RENOVASI'),
(4240, '5', '06', '01', '01', '999', 'ASET TETAP DALAM RENOVASI LAINNYA'),
(4241, '6', '00', '00', '00', '000', 'KONSTRUKSI DALAM PENGERJAAN'),
(4242, '6', '01', '00', '00', '000', 'KONSTRUKSI DALAM PENGERJAAN'),
(4243, '6', '01', '01', '00', '000', 'KONSTRUKSI DALAM PENGERJAAN'),
(4244, '6', '01', '01', '01', '000', 'KONSTRUKSI DALAM PENGERJAAN'),
(4245, '6', '01', '01', '01', '001', 'TANAH DALAM PENGERJAAN'),
(4246, '6', '01', '01', '01', '002', 'PERALATAN DAN MESIN DALAM PENGERJAAN'),
(4247, '6', '01', '01', '01', '003', 'GEDUNG DAN BANGUNAN DALAM PENGERJAAN'),
(4248, '6', '01', '01', '01', '004', '\"JALAN'),
(4249, '6', '01', '01', '01', '005', 'ASET TETAP LAINNYA DALAM PENGERJAAN'),
(4250, '6', '01', '01', '01', '999', 'KONSTRUKSI DALAM PENGERJAAN LAINNYA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_cacat`
--

CREATE TABLE `tweb_cacat` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tweb_cacat`
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
-- Struktur dari tabel `tweb_cara_kb`
--

CREATE TABLE `tweb_cara_kb` (
  `id` tinyint(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `sex` tinyint(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `tweb_cara_kb`
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
-- Struktur dari tabel `tweb_desa_pamong`
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
  `pamong_tglhenti` date DEFAULT NULL,
  `pamong_ub` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data untuk tabel `tweb_desa_pamong`
--

INSERT INTO `tweb_desa_pamong` (`pamong_id`, `pamong_nama`, `pamong_nip`, `pamong_nik`, `jabatan`, `pamong_status`, `pamong_tgl_terdaftar`, `pamong_ttd`, `foto`, `id_pend`, `pamong_tempatlahir`, `pamong_tanggallahir`, `pamong_sex`, `pamong_pendidikan`, `pamong_agama`, `pamong_nosk`, `pamong_tglsk`, `pamong_masajab`, `urut`, `pamong_niap`, `pamong_pangkat`, `pamong_nohenti`, `pamong_tglhenti`, `pamong_ub`) VALUES
(14, 'Muhammad Ilham ', '', '', 'Kepala Desa', '1', '2014-04-20', 1, 'CjR9Xl_kades.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(20, 'Mustahiq S.Adm', '197905062010011007', '5201140506790001', 'Sekretaris Desa', '1', '2016-08-23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(21, 'Syafruddin ', '-', '5201140911720004', 'Kaur Pemerintahan ', '1', '2016-08-23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(22, 'Supardi Rustam ', '-', '5201140101710003', 'Kaur Umum ', '1', '2016-08-23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(23, 'Mardiana ', '-', '5201145203810001', 'Kaur Keuangan', '1', '2016-08-23', NULL, 'cNzva0_bendahara.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(24, 'Syafi-i. SE ', '-', '5201140506730002', 'Kaur Pembangunan ', '1', '2016-08-23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(25, 'Mahrup ', '', '', 'Kaur Keamanan dan Ketertiban', '1', '2016-08-23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_golongan_darah`
--

CREATE TABLE `tweb_golongan_darah` (
  `id` int(11) NOT NULL,
  `nama` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tweb_golongan_darah`
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
-- Struktur dari tabel `tweb_keluarga`
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

--
-- Dumping data untuk tabel `tweb_keluarga`
--

INSERT INTO `tweb_keluarga` (`id`, `no_kk`, `nik_kepala`, `tgl_daftar`, `kelas_sosial`, `tgl_cetak_kk`, `alamat`, `id_cluster`) VALUES
(1, '5201140104126994', '1', '2016-09-14 06:28:03', NULL, NULL, NULL, 4),
(2, '5201140104126995', '5', '2016-09-14 06:28:03', NULL, NULL, NULL, 8),
(3, '5201140104166999', '9', '2016-09-14 06:28:03', NULL, NULL, NULL, 12),
(4, '5201140105136997', '12', '2016-09-14 06:28:03', NULL, NULL, NULL, 16),
(5, '5201140106166996', '16', '2016-09-14 06:28:03', NULL, NULL, NULL, 8),
(6, '5201140106167002', '17', '2016-09-14 06:28:03', NULL, NULL, NULL, 17),
(7, '5201140106167003', '19', '2016-09-14 06:28:03', NULL, NULL, NULL, 16),
(8, '5201140107126996', '21', '2016-09-14 06:28:03', NULL, NULL, NULL, 18),
(9, '5201140108146995', '25', '2016-09-14 06:28:03', NULL, NULL, NULL, 18),
(10, '5201140109126996', '26', '2016-09-14 06:28:03', NULL, NULL, NULL, 19),
(11, '5201140109156994', '30', '2016-09-14 06:28:03', NULL, NULL, NULL, 19),
(12, '5201140110137011', '32', '2016-09-14 06:28:03', NULL, NULL, NULL, 20),
(13, '5201140110137038', '35', '2016-09-14 06:28:03', NULL, NULL, NULL, 18),
(14, '5201140110156997', '37', '2016-09-14 06:28:03', NULL, NULL, NULL, 18),
(15, '5201140111126997', '38', '2016-09-14 06:28:03', NULL, NULL, NULL, 17),
(16, '5201140111126999', '39', '2016-09-14 06:28:03', NULL, NULL, NULL, 21),
(17, '5201140112107003', '42', '2016-09-14 06:28:03', NULL, NULL, NULL, 12),
(18, '5201140112126998', '45', '2016-09-14 06:28:03', NULL, NULL, NULL, 22),
(19, '5201140202167000', '51', '2016-09-14 06:28:03', NULL, NULL, NULL, 23),
(20, '5201140202167002', '52', '2016-09-14 06:28:03', NULL, NULL, NULL, 24),
(21, '5201140203136994', '55', '2016-09-14 06:28:03', NULL, NULL, NULL, 8),
(22, '5201140203136995', '56', '2016-09-14 06:28:03', NULL, NULL, NULL, 16),
(23, '5201140203167003', '59', '2016-09-14 06:28:03', NULL, NULL, NULL, 23),
(24, '5201140204166994', '61', '2016-09-14 06:28:03', NULL, NULL, NULL, 25),
(25, '5201140205156994', '62', '2016-09-14 06:28:03', NULL, NULL, NULL, 26),
(26, '5201140205156995', '65', '2016-09-14 06:28:03', NULL, NULL, NULL, 26),
(27, '5201140205156996', '68', '2016-09-14 06:28:03', NULL, NULL, NULL, 25),
(28, '5201140205156997', '71', '2016-09-14 06:28:03', NULL, NULL, NULL, 25),
(29, '5201140206157000', '74', '2016-09-14 06:28:03', NULL, NULL, NULL, 17),
(30, '5201140206157004', '76', '2016-09-14 06:28:03', NULL, NULL, NULL, 27),
(31, '5201140207156998', '77', '2016-09-14 06:28:03', NULL, NULL, NULL, 28),
(32, '5201140207157000', '80', '2016-09-14 06:28:03', NULL, NULL, NULL, 29),
(33, '5201140209156996', '83', '2016-09-14 06:28:03', NULL, NULL, NULL, 30),
(34, '5201140210137022', '84', '2016-09-14 06:28:03', NULL, NULL, NULL, 29),
(35, '5201140211117001', '88', '2016-09-14 06:28:03', NULL, NULL, NULL, 31),
(36, '5201140211117002', '91', '2016-09-14 06:28:03', NULL, NULL, NULL, 31),
(37, '5201140211117003', '95', '2016-09-14 06:28:03', NULL, NULL, NULL, 31);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_keluarga_sejahtera`
--

CREATE TABLE `tweb_keluarga_sejahtera` (
  `id` int(10) NOT NULL DEFAULT 0,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tweb_keluarga_sejahtera`
--

INSERT INTO `tweb_keluarga_sejahtera` (`id`, `nama`) VALUES
(1, 'Keluarga Pra Sejahtera'),
(2, 'Keluarga Sejahtera I'),
(3, 'Keluarga Sejahtera II'),
(4, 'Keluarga Sejahtera III'),
(5, 'Keluarga Sejahtera III Plus');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_penduduk`
--

CREATE TABLE `tweb_penduduk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` decimal(16,0) NOT NULL,
  `id_kk` int(11) DEFAULT 0,
  `kk_level` tinyint(2) NOT NULL DEFAULT 0,
  `id_rtm` varchar(30) DEFAULT NULL,
  `rtm_level` int(11) DEFAULT NULL,
  `sex` tinyint(4) UNSIGNED DEFAULT NULL,
  `tempatlahir` varchar(100) DEFAULT NULL,
  `tanggallahir` date DEFAULT NULL,
  `agama_id` int(1) DEFAULT NULL,
  `pendidikan_kk_id` int(1) DEFAULT NULL,
  `pendidikan_sedang_id` int(1) DEFAULT NULL,
  `pekerjaan_id` int(1) DEFAULT NULL,
  `status_kawin` tinyint(4) DEFAULT NULL,
  `warganegara_id` tinyint(4) NOT NULL DEFAULT 1,
  `dokumen_pasport` varchar(45) DEFAULT NULL,
  `dokumen_kitas` int(10) DEFAULT NULL,
  `ayah_nik` varchar(16) DEFAULT NULL,
  `ibu_nik` varchar(16) DEFAULT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `golongan_darah_id` int(11) DEFAULT NULL,
  `id_cluster` int(11) NOT NULL,
  `status` int(10) UNSIGNED DEFAULT NULL,
  `alamat_sebelumnya` varchar(200) DEFAULT NULL,
  `alamat_sekarang` varchar(200) DEFAULT NULL,
  `status_dasar` tinyint(4) NOT NULL DEFAULT 1,
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
  `panjang_lahir` varchar(10) DEFAULT NULL,
  `tag_id_card` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `id_asuransi` tinyint(5) DEFAULT NULL,
  `no_asuransi` char(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `tweb_penduduk`
--

INSERT INTO `tweb_penduduk` (`id`, `nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, `sex`, `tempatlahir`, `tanggallahir`, `agama_id`, `pendidikan_kk_id`, `pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, `warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, `ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, `foto`, `golongan_darah_id`, `id_cluster`, `status`, `alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`, `hamil`, `cacat_id`, `sakit_menahun_id`, `akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, `akta_perceraian`, `tanggalperceraian`, `cara_kb_id`, `telepon`, `tanggal_akhir_paspor`, `no_kk_sebelumnya`, `ktp_el`, `status_rekam`, `waktu_lahir`, `tempat_dilahirkan`, `jenis_kelahiran`, `kelahiran_anak_ke`, `penolong_kelahiran`, `berat_lahir`, `panjang_lahir`, `tag_id_card`, `created_at`, `created_by`, `updated_at`, `updated_by`, `id_asuransi`, `no_asuransi`, `email`) VALUES
(1, 'AHLUL', '5201142005716996', 1, 1, '0', 0, 1, 'MANGSIT', '1970-05-20', 1, 3, 18, 26, 2, 1, '', 0, '', '', 'ARFAH', 'RAISAH', '', 13, 4, 1, '', '', 1, 0, 0, 0, '', '', NULL, '', NULL, 0, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(2, 'AHMAD ALLIF RIZKI', '5201140706966997', 1, 4, '0', 0, 1, 'MANGSIT', '1995-06-07', 1, 1, 18, 1, 1, 1, '', 0, '', '', 'AHLUL', 'RUSDAH', '', 13, 4, 1, '', '', 1, 0, 0, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(3, 'AHMAD HABIB', '5201140301916995', 1, 4, '0', 0, 1, 'MANGSIT', '1990-01-03', 1, 3, 18, 1, 1, 1, NULL, NULL, '', '', 'AHLUL', 'RUSDAH', '', 13, 4, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(4, 'ADINI SEPTIA LISTA', '5201145003976995', 1, 4, '0', 0, 2, 'MANGSIT', '1996-03-10', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'AHLUL', 'RUSDAH', '', 13, 4, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(5, 'AHYAR', '5201141003666996', 2, 1, '0', 0, 1, 'JAKARTA', '1965-03-10', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'PAIMUN', 'SUPINAH', '', 13, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(6, 'APTA MADA RIZKY ALAMSYAH', '5201141412121724', 2, 4, '0', 0, 1, 'DEPOK', '2002-12-14', 1, 2, 18, 3, 1, 1, NULL, NULL, '', '', 'AHYAR', 'ALIYAH', '', 13, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(7, 'ALIYAH', '5201144609786995', 2, 3, '0', 0, 2, 'BEKASI', '1977-09-06', 1, 5, 18, 2, 2, 1, NULL, NULL, '', '', 'TAGOR SIPAHUTAR', 'AMAHWATI', '', 13, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(8, 'ALPIANI', '5201144301171725', 2, 4, '0', 0, 2, 'BOGOR', '2007-01-03', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'AHYAR', 'ALIYAH', '', 13, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(9, 'ASHARI', '5201140107867064', 3, 1, '0', 0, 1, 'KERANDANGAN', '1985-12-30', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'H. ABDUL KARIM', 'RADIAH', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(10, 'BACHTIAR HADI', '5201142210181724', 3, 4, '0', 0, 1, 'MATARAM', '2008-10-22', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'ASHARI', 'ANGGUN LESTARI PRATAMA', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(11, 'ANGGUN LESTARI PRATAMA', '5201146510916995', 3, 3, '0', 0, 2, 'SENGGIGI', '1990-10-25', 1, 4, 18, 88, 2, 1, NULL, NULL, '', '', 'SADIRAH', 'HJ. ROHANI', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(12, 'DAHRI', '5201143112797117', 4, 1, '0', 0, 1, 'MASBAGIK', '1978-12-31', 1, 3, 18, 88, 2, 1, NULL, NULL, '', '', 'AMAQ SAHMINI', 'INAQ SAHMINI', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(13, 'ERLANGGA DWIKO SAPUTRO', '5201140705156994', 4, 4, '0', 0, 1, 'MENINTING', '2014-05-07', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'DAHRI', 'ASMIATUN', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(14, 'FARIDAH', '5201141107101724', 4, 4, '0', 0, 1, 'MASBAGIK', '2000-07-11', 1, 3, 18, 3, 1, 1, NULL, NULL, '', '', 'DAHRI', 'ASMIATUN', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(15, 'ASMIATUN', '5201147112817188', 4, 3, '0', 0, 2, 'MASBAGIK', '1980-12-31', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'AMAQ MUJAENI', 'INAQ SAHMINI', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(16, 'BAIQ OLIVIA APRILLIANI', '5201145211486994', 5, 1, '0', 0, 2, 'SENGGIGI', '1947-11-12', 1, 1, 18, 1, 4, 1, NULL, NULL, '', '', 'AMAQ SINAREP', 'INAQ SINAREP', '', 13, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(17, 'FAUZI', '5201141210906998', 6, 1, '0', 0, 1, 'KERANDANGAN', '1989-10-12', 1, 5, 18, 3, 1, 1, NULL, NULL, '', '', 'SABLI', 'RAOHUN', '', 13, 17, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(18, 'DELLA MAHARANI NINGSIH', '5201147112947048', 6, 9, '0', 0, 2, 'KERANDANGAN', '1993-12-31', 1, 4, 18, 1, 1, 1, NULL, NULL, '', '', 'SABLI', 'RAOHUN', '', 13, 17, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(19, 'HAERUL FATONI', '5201142911936995', 7, 1, '0', 0, 1, 'SENGGIGI', '1992-11-29', 1, 5, 18, 15, 2, 1, NULL, NULL, '', '', 'ANGKASAH', 'MARJANAH', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(20, 'DENATUL SUARTINI', '3275014601977005', 7, 3, '0', 0, 2, 'JAKARTA', '1996-01-06', 1, 5, 18, 2, 2, 1, NULL, NULL, '', '', 'G. AMIN. P', 'NGATI', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(21, 'HERI IRAWAN', '5201140607636994', 8, 1, '0', 0, 1, 'TELOKE', '1962-07-06', 1, 3, 18, 9, 2, 1, NULL, NULL, '', '', 'AMAK MAJUMI', 'INAK MIDAH', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(22, 'HERMAN', '5201140305936994', 8, 4, '0', 0, 1, 'SENGGIGI', '1992-05-03', 1, 4, 18, 1, 1, 1, NULL, NULL, '', '', 'HERI IRAWAN', 'DEWI SAULINA', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(23, 'DEWI SAULINA', '5201144808686994', 8, 3, '0', 0, 2, 'KEKERAN', '1967-08-08', 1, 1, 18, 2, 2, 1, NULL, NULL, '', '', 'H. ZAENUDIN', 'INAK NAH', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(24, 'ELOK KHALISA SABRINA', '5201144408886994', 8, 4, '0', 0, 2, 'SENGGIGI', '1987-08-04', 1, 4, 18, 88, 1, 1, NULL, NULL, '', '', 'SERIMAN', 'DEWI SAULINA', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(25, 'I KETUT PAHING', '5201142210806997', 9, 1, '0', 0, 1, 'MATARAM', '1979-10-22', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', '-', '-', '', 2, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(26, 'IDA BAGUS MAS BUJANA', '5201143112707040', 10, 1, '0', 0, 1, 'APIT AIK', '1969-12-31', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'SAHMIN', 'MAOSIN', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(27, 'JOKO PATMOTO', '5201141009146994', 10, 4, '0', 0, 1, 'MANGSIT', '2013-09-10', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'IDA BAGUS MAS BUJANA', 'FITRIANI', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(28, 'KOMANG SALUN', '5201143105121724', 10, 4, '0', 0, 1, 'KAYANGAN', '2002-05-31', 1, 2, 18, 3, 1, 1, NULL, NULL, '', '', 'AMILUDIN', 'FITRIANI', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(29, 'FITRIANI', '5201145107836994', 10, 3, '0', 0, 2, 'KAYANGAN', '1982-07-11', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'REMBUK', 'SITIAH', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(30, 'LALU WAWAN DININGRAT', '5201141206886994', 11, 1, '0', 0, 1, 'MANGSIT', '1987-06-12', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'MAHSUN SUBUH', 'SARDIAH', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(31, 'FITRIANI', '5271016801926995', 11, 3, '0', 0, 2, 'MATARAM', '1991-01-28', 1, 5, 18, 15, 2, 1, NULL, NULL, '', '', 'UMAR', 'RUMINSIH', '', 13, 19, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(32, 'M. FA\'IZ AZAMI', '5201143112897123', 12, 1, '0', 0, 1, 'GEGELANG', '1988-12-31', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'SAREH', 'SUTIMAH', '', 13, 20, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(33, 'HILMIATI', '5201146402906994', 12, 3, '0', 0, 2, 'LOCO', '1989-02-24', 1, 4, 18, 88, 2, 1, NULL, NULL, '', '', 'H. MANSYUR', 'HJ. SA\'ADAH', '', 13, 20, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(34, 'HJ. PARIDAH', '5201144912146994', 12, 4, '0', 0, 2, 'MENINTING', '2013-12-09', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'M. FA\'IZ AZAMI', 'HILMIATI', '', 13, 20, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(35, 'HJ. SAMIRAH', '5201147112767266', 13, 1, '0', 0, 2, 'SENGGIGI', '1975-12-31', 1, 3, 18, 15, 3, 1, NULL, NULL, '', '', 'DAMSYAH', 'MARWIYAH', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(36, 'HUR MINAH', '5201144504131726', 13, 4, '0', 0, 2, 'SENGGIGI', '2003-04-05', 1, 3, 18, 3, 1, 1, NULL, NULL, '', '', 'MARSINI', 'KHODIJAH', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(37, 'HUSNAH', '5201145905936994', 14, 1, '0', 0, 2, 'LOTIM', '1992-05-19', 1, 4, 18, 88, 1, 1, NULL, NULL, '', '', '-', '-', '', 13, 18, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(38, 'IDA AYU OKA SUKERTI', '5201147112587053', 15, 1, '0', 0, 2, 'KERANDANGAN', '1957-12-31', 1, 3, 18, 88, 4, 1, NULL, NULL, '', '', 'ANGGRAH', 'HABIBAH', '', 13, 17, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(39, 'M. JAYADI', '5201143112837098', 16, 1, '0', 0, 1, 'SENGGIGI', '1982-12-31', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'IKHSAN', 'SAIDAH', '', 13, 21, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(40, 'JARIYAH', '5201145406916994', 16, 3, '0', 0, 2, 'SENGGIGI', '1990-06-14', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'SEGEP', 'HURNIWATI', '', 13, 21, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(41, 'LIHAM SATUN', '5201147112116995', 16, 4, '0', 0, 2, 'MATARAM', '2010-12-31', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'M. JAYADI', 'JARIYAH', '', 13, 21, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(42, 'M. NUR SAHID', '5201140507916996', 17, 1, '0', 0, 1, 'KERANDANGAN', '1990-07-05', 1, 4, 18, 88, 2, 1, NULL, NULL, '', '', '-', '-', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(43, 'MADE ASTAWE', '5201142503181724', 17, 4, '0', 0, 1, 'KERANDANGAN', '2008-03-25', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'M. NUR SAHID', 'MAISAH', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(44, 'MAISAH', '5201144605936994', 17, 3, '0', 0, 2, 'KERANDANGAN', '1992-05-06', 4, 1, 18, 88, 2, 1, NULL, NULL, '', '', '-', '-', '', 13, 12, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(45, 'MARSUNIN YOGA PRATAMA', '5201143112677056', 18, 1, '0', 0, 1, 'PEJARAKAN', '1966-12-31', 1, 3, 18, 9, 2, 1, NULL, NULL, '', '', 'MISRAH', 'INAQ MISDAH', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(46, 'MARZUKI', '5201141003966996', 18, 4, '0', 0, 1, 'LOCO', '1995-03-10', 1, 5, 18, 3, 1, 1, NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(47, 'MUHAMAD HAMDI', '5201141706986996', 18, 4, '0', 0, 1, 'LOCO', '1997-06-17', 1, 4, 18, 3, 1, 1, NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(48, 'MARLIA SAJIDA', '5201147112707088', 18, 3, '0', 0, 2, 'PEJARAKAN', '1969-12-31', 1, 3, 18, 2, 2, 1, NULL, NULL, '', '', 'H. ZAINUDIN', 'INAQ NAH', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(49, 'MIRA FANDA', '5201146704906995', 18, 4, '0', 0, 2, 'LOCO', '1989-04-27', 1, 5, 18, 88, 4, 1, NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(50, 'MUNAAH', '5201146304171724', 18, 4, '0', 0, 2, 'LOCO', '2007-04-23', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'MARSUNIN YOGA PRATAMA', 'MARLIA SAJIDA', '', 13, 22, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(51, 'MUHAMAD KABIR', '5201140107917031', 19, 1, '0', 0, 1, 'SENGGIGI', '1985-12-31', 1, 3, 18, 88, 2, 1, NULL, NULL, '', '', 'MUNIAH', 'SALIKIN', '', 13, 23, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(52, 'MUHAMAD SUHAD', '5201141704876995', 20, 1, '0', 0, 1, 'SENGGIGI', '1982-12-10', 1, 5, 18, 15, 2, 1, NULL, NULL, '', '', 'MUNIAH', 'HAJRIAH', '', 13, 24, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(53, 'MUHAMMAD HAIKAL FIRMANSYAH', '5201140308151724', 20, 4, '0', 0, 1, 'LOCO', '2005-08-03', 1, 2, 18, 1, 1, 1, NULL, NULL, '', '', 'MUHAMAD SUHAD', 'KHADIJAH', '', 13, 24, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(54, 'MURNIAH', '5201145904846994', 20, 3, '0', 0, 2, 'SETANGI', '1991-03-04', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'SAHABUDIN', 'SAKMAH', '', 13, 24, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(55, 'MURNIATI SAGITA', '5201144112726996', 21, 1, '0', 0, 2, 'YOGYAKARTA', '1971-12-01', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'UMAR SANTOSA', 'MIRANTI', '', 1, 8, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(56, 'MUHAMMAD RIFAI', '5201143105926995', 22, 1, '0', 0, 1, 'LOCO', '1991-05-31', 4, 4, 18, 88, 2, 1, NULL, NULL, '', '', 'I WAYAN MERTI', 'NI NYOMAN RENI', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(57, 'NADIA ROSDIANA', '5201144305936996', 22, 3, '0', 0, 2, 'MATARAM', '1992-05-03', 4, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'I WAYAN PARTA', 'NI NENGAH SUDINI', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(58, 'NI KOMANG PONIASIH', '5201146206126994', 22, 4, '0', 0, 2, 'MATARAM', '2011-06-22', 4, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'MURNIATI SAGITA', 'NADIA ROSDIANA', '', 13, 16, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(59, 'MUHAMMAD WIRDA MAULANA IBRAHIM', '5201143112417056', 23, 1, '0', 0, 1, 'SENGGIGI', '1940-12-31', 1, 1, 18, 9, 2, 1, NULL, NULL, '', '', 'AMAQ SUN -ALM-', 'INAQ SUN -ALM-', '', 13, 23, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(60, 'NI LUH NITA SARI', '5201147112466997', 23, 3, '0', 0, 2, 'SENTELUK', '1945-12-31', 1, 1, 18, 2, 2, 1, NULL, NULL, '', '', 'AMAQ IRAH', 'INAQ IRAH', '', 13, 23, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(61, 'NI NENGAH AYU KARSINI', '5201145505946996', 24, 1, '0', 0, 2, 'SENGGIGI', '1993-05-15', 1, 2, 18, 15, 1, 1, NULL, NULL, '', '', 'H HAMDANI', 'SANERIAH', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(62, 'MUKSAN', '5201143112957094', 25, 1, '0', 0, 1, 'MANGSIT', '1994-12-31', 1, 4, 18, 88, 2, 1, NULL, NULL, '', '', 'MISDAH', 'RABIAH', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(63, 'NURHAYATI', '5201145509146994', 25, 4, '0', 0, 2, 'MENINTING', '2013-09-15', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'MUKSAN', 'NUR\'AINI', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(64, 'MURSIDIN', '5201142204966994', 26, 4, '0', 0, 1, 'MANGSIT', '1995-04-22', 1, 3, 18, 11, 1, 1, NULL, NULL, '', '', 'RUSNAH (ALM)', 'NURHIDAYAH', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(65, 'NURHIDAYAH', '5201144209766995', 26, 1, '0', 0, 2, 'MANGSIT', '1975-09-02', 1, 3, 18, 2, 4, 1, NULL, NULL, '', '', 'ISMAIL', 'JUMINAH', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(66, 'NURJANAH', '5201145005101724', 26, 4, '0', 0, 2, 'MONTONG', '2000-05-10', 1, 4, 18, 3, 1, 1, NULL, NULL, '', '', 'RUSNAH (ALM)', 'NURHIDAYAH', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(67, 'NURUL AINUN', '5201144108121724', 26, 4, '0', 0, 2, 'MANGSIT', '2002-08-01', 1, 2, 18, 3, 1, 1, NULL, NULL, '', '', 'RUSNAH', 'NURHIDAYAH', '', 13, 26, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(68, 'MUSAHAB', '5201141607936996', 27, 1, '0', 0, 1, 'LOTENG', '1992-07-16', 1, 6, 18, 88, 2, 1, NULL, NULL, '', '', 'LALU ROSIDI', 'BQ. ALISA', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(69, 'NURUL FAIZAH', '5201145003936994', 27, 3, '0', 0, 2, 'SENGGIGI', '1992-03-10', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'SAHUR', 'SARE\'AH', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(70, 'NURUL HIDAYATI', '5201147004136996', 27, 4, '0', 0, 2, 'MATARAM', '2012-04-30', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'MUSAHAB', 'NURUL FAIZAH', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(71, 'NAPIAH', '5201141303906995', 28, 1, '0', 0, 1, 'SENGGIGI', '1989-03-13', 1, 4, 18, 11, 2, 1, NULL, NULL, '', '', 'MUNIAH', 'HAJARIAH', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(72, 'RACHEL YULIANTI', '5201146507966995', 28, 3, '0', 0, 2, 'MELASE', '1995-07-25', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'LUKMAN', 'MUSNAH', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(73, 'RAISHA MAULIDYA', '5201144701156995', 28, 4, '0', 0, 2, 'MENINTING', '2014-01-07', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'NAPIAH', 'RACHEL YULIANTI', '', 13, 25, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(74, 'PATANUL HUSNUL', '5201143112667000', 29, 1, '0', 0, 1, 'JAWA TIMUR', '1965-12-31', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'AHMAD', 'ASIH', '', 13, 17, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(75, 'RATNAWATY', '5201145512796995', 29, 3, '0', 0, 2, 'KERANDANGAN', '1978-12-15', 1, 5, 18, 84, 2, 1, NULL, NULL, '', '', 'JUM', 'REMAH', '', 13, 17, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(76, 'RABITAH', '5201140312896994', 30, 1, '0', 0, 1, 'KERANDANGAN', '1988-12-03', 4, 4, 18, 88, 1, 1, NULL, NULL, '', '', '-', '-', '', 13, 27, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(77, 'ROMI FAISAL', '5201141506856997', 31, 1, '0', 0, 1, 'MANGSIT', '1984-06-15', 1, 3, 18, 15, 2, 1, NULL, NULL, '', '', 'MUNTAHAR', 'MAKNAH', '', 13, 28, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(78, 'RAUDATUL ILMI', '5201145808816994', 31, 3, '0', 0, 2, 'IRENG DAYE', '1980-08-18', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'MUDAHIR', 'RUMISAH', '', 13, 28, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(79, 'ROHANI', '5201144306116994', 31, 4, '0', 0, 2, 'MANGSIT', '2010-06-03', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'ROMI FAISAL', 'RAUDATUL ILMI', '', 13, 28, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(80, 'RUKIAH', '5201145909946994', 32, 1, '0', 0, 2, 'SERANG', '1993-09-19', 1, 4, 18, 88, 3, 1, NULL, NULL, '', '', '-', '-', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(81, 'RUMALI', '5201144507886994', 32, 9, '0', 0, 2, 'JAKARTA', '1987-07-05', 1, 4, 18, 88, 1, 1, NULL, NULL, '', '', '-', '-', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(82, 'RONI', '5201140301836997', 33, 4, '0', 0, 1, 'DENPASAR', '1982-01-03', 4, 5, 18, 15, 1, 1, NULL, NULL, '', '', 'IDA BAGUS PUTU WIADNYA', 'RUSMAYANTI', '', 13, 30, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(83, 'RUSMAYANTI', '5201145003546994', 33, 1, '0', 0, 2, 'DENPASAR', '1953-03-10', 4, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'IDA BAGUS MAS', 'IDA AYU RAKA', '', 13, 30, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(84, 'RUSNI', '5201143112707180', 34, 1, '0', 0, 1, 'KEKERAN', '1969-12-31', 1, 3, 18, 9, 2, 1, NULL, NULL, '', '', '-', '-', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(85, 'SAPIAH', '5201147011726994', 34, 3, '0', 0, 2, 'KEKERAN', '1971-11-30', 1, 3, 18, 2, 2, 1, NULL, NULL, '', '', '-', '-', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(86, 'SAPINAH', '5201145701966994', 34, 4, '0', 0, 2, 'SENGGIGI', '1995-01-17', 1, 5, 18, 3, 1, 1, NULL, NULL, '', '', 'RUSNI', 'SAPIAH', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(87, 'SARRA LANGELAND', '5201145111946996', 34, 4, '0', 0, 2, 'SENGGIGI', '1993-11-11', 1, 5, 18, 3, 1, 1, NULL, NULL, '', '', 'RUSNI', 'SAPIAH', '', 13, 29, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(88, 'SAHRONI', '5201143112617096', 35, 1, '0', 0, 1, 'MEDAS', '1960-12-31', 1, 4, 18, 88, 2, 1, NULL, NULL, '', '', 'SADIYAH', 'INAQ SADIAH', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(89, 'SERIMAN', '5201141012846995', 35, 4, '0', 0, 1, 'SENGGIGI', '1983-12-10', 1, 5, 18, 15, 1, 1, NULL, NULL, '', '', 'SAHRONI', 'NURLAELA', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(90, 'SUNYOTOH', '5201143112817139', 35, 4, '0', 0, 1, 'MEDAS', '1980-12-31', 1, 5, 18, 15, 1, 1, NULL, NULL, '', '', 'SAHRONI', 'NURLAELA', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(91, 'SYARIFUL KALAM', '5201141707776994', 36, 1, '0', 0, 1, 'SENGGIGI', '1976-07-17', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'H. ABDURAHMAN', 'NAFISAH', '', 1, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(92, 'SITI AISYAH', '5201146210776994', 36, 3, '0', 0, 2, 'SUKARAJA', '1976-10-22', 1, 4, 18, 2, 2, 1, NULL, NULL, '', '', 'AMINALLOH', 'RAEHAN', '', 2, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(93, 'SITI PAOZIAH', '5201146312161724', 36, 4, '0', 0, 2, 'SENGGIGI', '2006-12-23', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'SYARIFUL KALAM', 'SITI AISYAH', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(94, 'SUKMA UTAMI', '5201144607996998', 36, 4, '0', 0, 2, 'SENGGIGI', '1998-07-06', 1, 4, 18, 3, 1, 1, NULL, NULL, '', '', 'SYARIFUL KALAM', 'SITI AISYAH', '', 5, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(95, 'WAHID ALIAS H. MAHSUN', '5201141212816996', 37, 1, '0', 0, 1, 'SENGGIGI', '1980-12-12', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'H. ABDURRAHMAN', 'NAFISAH', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(96, 'WAYAN EKA PRAWATA', '5201142003136994', 37, 4, '0', 0, 1, 'GUNUNG SARI', '2012-03-20', 1, 1, 18, 1, 1, 1, NULL, NULL, '', '', 'WAHID ALIAS H. MAHSUN', 'ULFA WIDIAWATI', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL),
(97, 'ULFA WIDIAWATI', '5201145203896994', 37, 3, '0', 0, 2, 'JOHAR PELITA', '1988-03-12', 1, 5, 18, 88, 2, 1, NULL, NULL, '', '', 'ZAMHARIN', 'SITIYAH', '', 13, 31, 1, '', '', 1, NULL, NULL, 0, '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, 0, 0, '', 0, 0, 0, 0, 0, '', NULL, '2019-05-28 15:45:28', 0, '2019-05-28 15:45:28', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_penduduk_agama`
--

CREATE TABLE `tweb_penduduk_agama` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `tweb_penduduk_agama`
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
-- Struktur dari tabel `tweb_penduduk_asuransi`
--

CREATE TABLE `tweb_penduduk_asuransi` (
  `id` tinyint(5) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tweb_penduduk_asuransi`
--

INSERT INTO `tweb_penduduk_asuransi` (`id`, `nama`) VALUES
(1, 'Tidak/Belum Punya'),
(2, 'BPJS Penerima Bantuan Iuran'),
(3, 'BPJS Non Penerima Bantuan Iuran'),
(99, 'Asuransi Lainnya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_penduduk_hubungan`
--

CREATE TABLE `tweb_penduduk_hubungan` (
  `id` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `tweb_penduduk_hubungan`
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
-- Struktur dari tabel `tweb_penduduk_kawin`
--

CREATE TABLE `tweb_penduduk_kawin` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `tweb_penduduk_kawin`
--

INSERT INTO `tweb_penduduk_kawin` (`id`, `nama`) VALUES
(1, 'BELUM KAWIN'),
(2, 'KAWIN'),
(3, 'CERAI HIDUP'),
(4, 'CERAI MATI');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_penduduk_mandiri`
--

CREATE TABLE `tweb_penduduk_mandiri` (
  `pin` char(32) NOT NULL,
  `plain` char(6) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `tanggal_buat` datetime DEFAULT NULL,
  `id_pend` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tweb_penduduk_mandiri`
--

INSERT INTO `tweb_penduduk_mandiri` (`pin`, `plain`, `last_login`, `tanggal_buat`, `id_pend`) VALUES
('285f6ecefab78d5efaee3a2d4549adf5', '087031', NULL, '2020-06-10 23:26:07', 2),
('85e7d3fd9c1a1c04d2b900e0e418ab50', '095461', NULL, '2020-06-10 23:25:45', 20);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_penduduk_map`
--

CREATE TABLE `tweb_penduduk_map` (
  `id` int(11) NOT NULL,
  `lat` varchar(24) DEFAULT NULL,
  `lng` varchar(24) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tweb_penduduk_map`
--

INSERT INTO `tweb_penduduk_map` (`id`, `lat`, `lng`) VALUES
(7, '-8.495339739996284', '116.05516478419307'),
(3, '-8.496679059709217', '116.05342939496042');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_penduduk_pekerjaan`
--

CREATE TABLE `tweb_penduduk_pekerjaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `tweb_penduduk_pekerjaan`
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
-- Struktur dari tabel `tweb_penduduk_pendidikan`
--

CREATE TABLE `tweb_penduduk_pendidikan` (
  `id` tinyint(3) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `tweb_penduduk_pendidikan`
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
-- Struktur dari tabel `tweb_penduduk_pendidikan_kk`
--

CREATE TABLE `tweb_penduduk_pendidikan_kk` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `tweb_penduduk_pendidikan_kk`
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
-- Struktur dari tabel `tweb_penduduk_sex`
--

CREATE TABLE `tweb_penduduk_sex` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `tweb_penduduk_sex`
--

INSERT INTO `tweb_penduduk_sex` (`id`, `nama`) VALUES
(1, 'LAKI-LAKI'),
(2, 'PEREMPUAN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_penduduk_status`
--

CREATE TABLE `tweb_penduduk_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tweb_penduduk_status`
--

INSERT INTO `tweb_penduduk_status` (`id`, `nama`) VALUES
(1, 'TETAP'),
(2, 'TIDAK TETAP'),
(3, 'PENDATANG');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_penduduk_umur`
--

CREATE TABLE `tweb_penduduk_umur` (
  `id` int(11) NOT NULL,
  `nama` varchar(25) DEFAULT NULL,
  `dari` int(11) DEFAULT NULL,
  `sampai` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tweb_penduduk_umur`
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
-- Struktur dari tabel `tweb_penduduk_warganegara`
--

CREATE TABLE `tweb_penduduk_warganegara` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tweb_penduduk_warganegara`
--

INSERT INTO `tweb_penduduk_warganegara` (`id`, `nama`) VALUES
(1, 'WNI'),
(2, 'WNA'),
(3, 'DUA KEWARGANEGARAAN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_rtm`
--

CREATE TABLE `tweb_rtm` (
  `id` int(11) NOT NULL,
  `nik_kepala` int(11) NOT NULL,
  `no_kk` varchar(30) NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kelas_sosial` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_rtm_hubungan`
--

CREATE TABLE `tweb_rtm_hubungan` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tweb_rtm_hubungan`
--

INSERT INTO `tweb_rtm_hubungan` (`id`, `nama`) VALUES
(1, 'Kepala Rumah Tangga'),
(2, 'Anggota');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_sakit_menahun`
--

CREATE TABLE `tweb_sakit_menahun` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tweb_sakit_menahun`
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
-- Struktur dari tabel `tweb_status_dasar`
--

CREATE TABLE `tweb_status_dasar` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tweb_status_dasar`
--

INSERT INTO `tweb_status_dasar` (`id`, `nama`) VALUES
(1, 'HIDUP'),
(2, 'MATI'),
(3, 'PINDAH'),
(4, 'HILANG'),
(9, 'TIDAK VALID');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_status_ktp`
--

CREATE TABLE `tweb_status_ktp` (
  `id` tinyint(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `ktp_el` tinyint(4) NOT NULL,
  `status_rekam` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `tweb_status_ktp`
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
-- Struktur dari tabel `tweb_surat_atribut`
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
-- Struktur dari tabel `tweb_surat_format`
--

CREATE TABLE `tweb_surat_format` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `url_surat` varchar(100) NOT NULL,
  `kode_surat` varchar(10) DEFAULT NULL,
  `lampiran` varchar(100) DEFAULT NULL,
  `kunci` tinyint(1) NOT NULL DEFAULT 0,
  `favorit` tinyint(1) NOT NULL DEFAULT 0,
  `jenis` tinyint(2) NOT NULL DEFAULT 2,
  `mandiri` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tweb_surat_format`
--

INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`, `lampiran`, `kunci`, `favorit`, `jenis`, `mandiri`) VALUES
(1, 'Keterangan Pengantar', 'surat_ket_pengantar', 'S-01', NULL, 0, 0, 1, 1),
(2, 'Keterangan Penduduk', 'surat_ket_penduduk', 'S-02', NULL, 0, 0, 1, 1),
(3, 'Biodata Penduduk', 'surat_bio_penduduk', 'S-03', 'f-1.01.php', 0, 0, 1, 1),
(5, 'Keterangan Pindah Penduduk', 'surat_ket_pindah_penduduk', 'S-04', 'f-1.08.php,f-1.25.php', 0, 0, 1, 1),
(6, 'Keterangan Jual Beli', 'surat_ket_jual_beli', 'S-05', NULL, 0, 0, 1, 1),
(8, 'Pengantar Surat Keterangan Catatan Kepolisian', 'surat_ket_catatan_kriminal', 'S-07', NULL, 0, 0, 1, 1),
(9, 'Keterangan KTP dalam Proses', 'surat_ket_ktp_dalam_proses', 'S-08', NULL, 0, 0, 1, 1),
(10, 'Keterangan Beda Identitas', 'surat_ket_beda_nama', 'S-09', NULL, 0, 0, 1, 1),
(11, 'Keterangan Bepergian / Jalan', 'surat_jalan', 'S-10', NULL, 0, 0, 1, 1),
(12, 'Keterangan Kurang Mampu', 'surat_ket_kurang_mampu', 'S-11', NULL, 0, 0, 1, 1),
(13, 'Pengantar Izin Keramaian', 'surat_izin_keramaian', 'S-12', NULL, 0, 0, 1, 1),
(14, 'Pengantar Laporan Kehilangan', 'surat_ket_kehilangan', 'S-13', NULL, 0, 0, 1, 1),
(15, 'Keterangan Usaha', 'surat_ket_usaha', 'S-14', NULL, 0, 0, 1, 1),
(16, 'Keterangan JAMKESOS', 'surat_ket_jamkesos', 'S-15', NULL, 0, 0, 1, 0),
(17, 'Keterangan Domisili Usaha', 'surat_ket_domisili_usaha', 'S-16', NULL, 0, 0, 1, 0),
(18, 'Keterangan Kelahiran', 'surat_ket_kelahiran', 'S-17', 'f-2.01.php', 0, 0, 1, 0),
(20, 'Permohonan Akta Lahir', 'surat_permohonan_akta', 'S-18', NULL, 0, 0, 1, 0),
(21, 'Pernyataan Belum Memiliki Akta Lahir', 'surat_pernyataan_akta', 'S-19', NULL, 0, 0, 1, 0),
(22, 'Permohonan Duplikat Kelahiran', 'surat_permohonan_duplikat_kelahiran', 'S-20', NULL, 0, 0, 1, 0),
(24, 'Keterangan Kematian', 'surat_ket_kematian', 'S-21', 'f-2.29.php', 0, 0, 1, 0),
(25, 'Keterangan Lahir Mati', 'surat_ket_lahir_mati', 'S-22', NULL, 0, 0, 1, 0),
(26, 'Keterangan Untuk Nikah (N-1 s/d N-7)', 'surat_ket_nikah', 'S-23', NULL, 0, 0, 1, 0),
(33, 'Keterangan Pergi Kawin', 'surat_ket_pergi_kawin', 'S-30', NULL, 0, 0, 1, 0),
(35, 'Keterangan Wali Hakim', 'surat_ket_wali_hakim', 'S-32', NULL, 0, 0, 1, 0),
(36, 'Permohonan Duplikat Surat Nikah', 'surat_permohonan_duplikat_surat_nikah', 'S-33', NULL, 0, 0, 1, 0),
(37, 'Permohonan Cerai', 'surat_permohonan_cerai', 'S-34', NULL, 0, 0, 1, 0),
(38, 'Keterangan Pengantar Rujuk/Cerai', 'surat_ket_rujuk_cerai', 'S-35', NULL, 0, 0, 1, 0),
(45, 'Permohonan Kartu Keluarga', 'surat_permohonan_kartu_keluarga', 'S-36', 'f-1.15.php,f-1.01.php', 0, 0, 1, 0),
(51, 'Domisili Usaha Non-Warga', 'surat_domisili_usaha_non_warga', 'S-37', NULL, 0, 0, 1, 0),
(76, 'Keterangan Beda Identitas KIS', 'surat_ket_beda_identitas_kis', 'S-38', NULL, 0, 0, 1, 0),
(85, 'Keterangan Izin Orang Tua/Suami/Istri', 'surat_izin_orangtua_suami_istri', 'S-39', NULL, 0, 0, 1, 0),
(86, 'Pernyataan Penguasaan Fisik Bidang Tanah (SPORADIK)', 'surat_sporadik', 'S-40', NULL, 0, 0, 1, 0),
(89, 'Permohonan Perubahan Kartu Keluarga', 'surat_permohonan_perubahan_kartu_keluarga', 'S-41', 'f-1.16.php,f-1.01.php', 0, 0, 1, 0),
(110, 'Non Warga', 'surat_non_warga', '', NULL, 0, 0, 2, 0),
(156, 'Keterangan Domisili', 'surat_ket_domisili', 'S-41', NULL, 0, 0, 1, 0),
(160, 'Keterangan Penghasilan Orangtua', 'surat_ket_penghasilan_orangtua', 'S-42', NULL, 0, 0, 1, 0),
(161, 'Pengantar Permohonan Penerbitan Buku Pas Lintas', 'surat_permohonan_penerbitan_buku_pas_lintas', 'S-43', NULL, 0, 0, 1, 0),
(162, 'Keterangan Penghasilan Ayah', 'surat_ket_penghasilan_ayah', 'S-44', NULL, 0, 0, 1, 0),
(163, 'Keterangan Penghasilan Ibu', 'surat_ket_penghasilan_ibu', 'S-45', NULL, 0, 0, 1, 0),
(164, 'Raw', 'raw', '', NULL, 0, 0, 2, 0),
(167, 'Perintah Perjalanan Dinas', 'surat_perintah_perjalanan_dinas', 'S-46', NULL, 0, 0, 1, 0),
(168, 'Kuasa', 'surat_kuasa', 'S-47', NULL, 0, 0, 1, 0),
(172, 'Keterangan Kepemilikan Kendaraan', 'surat_ket_kepemilikan_kendaraan', 'S-48', NULL, 0, 0, 1, 0),
(173, 'Keterangan Kepemilikan Tanah', 'surat_ket_kepemilikan_tanah', 'S-49', NULL, 0, 0, 1, 0),
(177, 'Baru', 'surat_baru', NULL, NULL, 0, 0, 2, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tweb_wil_clusterdesa`
--

CREATE TABLE `tweb_wil_clusterdesa` (
  `id` int(11) NOT NULL,
  `rt` varchar(10) NOT NULL DEFAULT '0',
  `rw` varchar(10) NOT NULL DEFAULT '0',
  `dusun` varchar(50) NOT NULL DEFAULT '0',
  `id_kepala` int(11) DEFAULT NULL,
  `lat` varchar(20) DEFAULT NULL,
  `lng` varchar(20) DEFAULT NULL,
  `zoom` int(11) DEFAULT NULL,
  `path` text DEFAULT NULL,
  `map_tipe` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tweb_wil_clusterdesa`
--

INSERT INTO `tweb_wil_clusterdesa` (`id`, `rt`, `rw`, `dusun`, `id_kepala`, `lat`, `lng`, `zoom`, `path`, `map_tipe`) VALUES
(1, '0', '0', 'MANGSIT', 0, '', '', 0, '', ''),
(2, '0', '-', 'MANGSIT', 0, '', '', 0, '', ''),
(3, '-', '-', 'MANGSIT', 0, '', '', 0, '', ''),
(4, '004', '-', 'MANGSIT', 0, '', '', 0, '', ''),
(5, '0', '0', 'SENGGIGI', 0, '', '', 0, '', ''),
(6, '0', '-', 'SENGGIGI', 0, '', '', 0, '', ''),
(7, '-', '-', 'SENGGIGI', 0, '', '', 0, '', ''),
(8, '001', '-', 'SENGGIGI', 0, '', '', 0, '', ''),
(9, '0', '0', 'KERANDANGAN', 0, '-8.487384220496068', '116.04262340348217', 17, '[[[-8.485902029262565,116.04141712188722],[-8.485689801625153,116.04226469993593],[-8.485626133311067,116.04422807693483],[-8.485901656206273,116.04463371913882],[-8.486320971248613,116.04483272647488],[-8.486835829473774,116.0447645187378],[-8.487504344139245,116.04471087455751],[-8.48779085006813,116.04368090629579],[-8.488194080272509,116.04272603988649],[-8.488024299185433,116.04154586791994],[-8.487186003967512,116.04076266288759],[-8.486676659143768,116.04054808616638]]]', 'HYBRID'),
(10, '0', '-', 'KERANDANGAN', 0, '', '', 0, '', ''),
(11, '-', '-', 'KERANDANGAN', 0, '', '', 0, '', ''),
(12, '002', '-', 'KERANDANGAN', 0, '', '', 0, '', ''),
(13, '0', '0', 'LOCO', 0, '', '', 0, '', ''),
(14, '0', '-', 'LOCO', 0, '', '', 0, '', ''),
(15, '-', '-', 'LOCO', 0, '', '', 0, '', ''),
(16, '003', '-', 'LOCO', 0, '', '', 0, '', ''),
(17, '001', '-', 'KERANDANGAN', 0, '', '', 16, '[[[-8.488024299185433,116.04154586791994],[-8.488194080272509,116.04272603988649],[-8.487966087370822,116.04326593697435],[-8.487208770214899,116.04254785480175],[-8.48739952668305,116.04203277514988],[-8.487473709728375,116.04192546688903],[-8.48763267334875,116.04150696467187],[-8.487805557708919,116.04134150129615]]]', ''),
(18, '005', '-', 'SENGGIGI', 0, '', '', 0, '', ''),
(19, '005', '-', 'MANGSIT', 0, '', '', 0, '', ''),
(20, '005', '-', 'LOCO', 0, '', '', 0, '', ''),
(21, '003', '-', 'SENGGIGI', 0, '', '', 0, '', ''),
(22, '002', '-', 'LOCO', 0, '', '', 0, '', ''),
(23, '004', '-', 'SENGGIGI', 0, '', '', 0, '', ''),
(24, '004', '-', 'LOCO', 0, '', '', 0, '', ''),
(25, '001', '-', 'LOCO', 0, '', '', 0, '', ''),
(26, '002', '-', 'MANGSIT', 0, '', '', 0, '', ''),
(27, '004', '-', 'KERANDANGAN', 0, '', '', 0, '', ''),
(28, '003', '-', 'MANGSIT', 0, '', '', 0, '', ''),
(29, '006', '-', 'SENGGIGI', 0, '', '', 0, '', ''),
(30, '006', '-', 'KERANDANGAN', 0, '', '', 0, '', ''),
(31, '002', '-', 'SENGGIGI', 0, '', '', 0, '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `id_grup` int(5) NOT NULL,
  `email` varchar(100) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT 0,
  `nama` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `foto` varchar(100) NOT NULL,
  `session` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `id_grup`, `email`, `last_login`, `active`, `nama`, `company`, `phone`, `foto`, `session`) VALUES
(1, 'admin', '$2y$10$CfFhuvLXa3RNotqOPYyW2.JujLbAbZ4YO0PtxIRBz4QDLP0/pfH6.', 1, 'info@opendesa.id', '2020-06-06 23:47:37', 1, 'Administrator', 'ADMIN', '321', 'favicon.png', 'a8d4080245664ed2049c1b2ded7cac30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_grup`
--

CREATE TABLE `user_grup` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `user_grup`
--

INSERT INTO `user_grup` (`id`, `nama`) VALUES
(1, 'Administrator'),
(2, 'Operator'),
(3, 'Redaksi'),
(4, 'Kontributor'),
(5, 'Satgas Covid-19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `widget`
--

CREATE TABLE `widget` (
  `id` int(11) NOT NULL,
  `isi` text DEFAULT NULL,
  `enabled` int(2) DEFAULT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `jenis_widget` tinyint(2) NOT NULL DEFAULT 3,
  `urut` int(5) DEFAULT NULL,
  `form_admin` varchar(100) DEFAULT NULL,
  `setting` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `widget`
--

INSERT INTO `widget` (`id`, `isi`, `enabled`, `judul`, `jenis_widget`, `urut`, `form_admin`, `setting`) VALUES
(1, '<p><iframe src=\"https://www.google.co.id/maps?f=q&source=s_q&hl=en&geocode=&q=Logandu,+Karanggayam&aq=0&oq=logan&sll=-2.550221,118.015568&sspn=52.267573,80.332031&t=h&ie=UTF8&hq=&hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&ll=-7.55854,109.634173&spn=0.052497,0.078449&z=14&output=embed\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" width=\"100%\"></iframe></p> ', 2, 'Peta Desa', 3, 3, '', ''),
(2, 'layanan_mandiri.php', 1, 'Layanan Mandiri', 1, 1, 'mandiri', ''),
(3, 'agenda.php', 2, 'Agenda', 1, 8, 'web/index/1000', ''),
(4, 'galeri.php', 2, 'Galeri', 1, 9, 'gallery', ''),
(5, 'statistik.php', 1, 'Statistik', 1, 10, '', ''),
(6, 'komentar.php', 1, 'Komentar', 1, 11, 'komentar', ''),
(7, 'media_sosial.php', 1, 'Media Sosial', 1, 12, 'sosmed', ''),
(8, 'peta_lokasi_kantor.php', 1, 'Peta Lokasi Kantor', 1, 13, 'hom_desa', ''),
(9, 'statistik_pengunjung.php', 1, 'Statistik Pengunjung', 1, 14, '', ''),
(10, 'arsip_artikel.php', 1, 'Arsip Artikel', 1, 4, '', ''),
(11, 'aparatur_desa.php', 2, 'Aparatur Desa', 1, 6, 'web_widget/admin/aparatur_desa', ''),
(12, 'sinergi_program.php', 2, 'Sinergi Program', 1, 7, 'web_widget/admin/sinergi_program', '[]'),
(13, 'menu_kategori.php', 2, 'Menu Kategori', 1, 5, '', ''),
(14, 'peta_wilayah_desa.php', 1, 'Peta Wilayah Desa', 1, 2, 'hom_desa/konfigurasi', ''),
(15, 'keuangan.php', 1, 'Keuangan', 1, 15, 'keuangan/impor_data', '');

-- --------------------------------------------------------

--
-- Struktur untuk view `daftar_anggota_grup`
--
DROP TABLE IF EXISTS `daftar_anggota_grup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `daftar_anggota_grup`  AS  select `a`.`id_grup_kontak` AS `id_grup_kontak`,`a`.`id_grup` AS `id_grup`,`c`.`nama_grup` AS `nama_grup`,`b`.`id_kontak` AS `id_kontak`,`b`.`nama` AS `nama`,`b`.`no_hp` AS `no_hp`,`b`.`sex` AS `sex`,`b`.`alamat_sekarang` AS `alamat_sekarang` from ((`anggota_grup_kontak` `a` left join `daftar_kontak` `b` on(`a`.`id_kontak` = `b`.`id_kontak`)) left join `kontak_grup` `c` on(`a`.`id_grup` = `c`.`id_grup`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `daftar_grup`
--
DROP TABLE IF EXISTS `daftar_grup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `daftar_grup`  AS  select `a`.`id_grup` AS `id_grup`,`a`.`nama_grup` AS `nama_grup`,(select count(`anggota_grup_kontak`.`id_kontak`) from `anggota_grup_kontak` where `a`.`id_grup` = `anggota_grup_kontak`.`id_grup`) AS `jumlah_anggota` from `kontak_grup` `a` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `daftar_kontak`
--
DROP TABLE IF EXISTS `daftar_kontak`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `daftar_kontak`  AS  select `a`.`id_kontak` AS `id_kontak`,`a`.`id_pend` AS `id_pend`,`b`.`nama` AS `nama`,`a`.`no_hp` AS `no_hp`,case when `b`.`sex` = '1' then 'Laki-laki' else 'Perempuan' end AS `sex`,`b`.`alamat_sekarang` AS `alamat_sekarang` from (`kontak` `a` left join `tweb_penduduk` `b` on(`a`.`id_pend` = `b`.`id`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `dokumen_hidup`
--
DROP TABLE IF EXISTS `dokumen_hidup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dokumen_hidup`  AS  select `dokumen`.`id` AS `id`,`dokumen`.`satuan` AS `satuan`,`dokumen`.`nama` AS `nama`,`dokumen`.`enabled` AS `enabled`,`dokumen`.`tgl_upload` AS `tgl_upload`,`dokumen`.`id_pend` AS `id_pend`,`dokumen`.`kategori` AS `kategori`,`dokumen`.`attr` AS `attr`,`dokumen`.`tahun` AS `tahun`,`dokumen`.`kategori_info_publik` AS `kategori_info_publik`,`dokumen`.`updated_at` AS `updated_at`,`dokumen`.`deleted` AS `deleted`,`dokumen`.`id_syarat` AS `id_syarat`,`dokumen`.`id_parent` AS `id_parent` from `dokumen` where `dokumen`.`deleted` <> 1 ;

-- --------------------------------------------------------

--
-- Struktur untuk view `keluarga_aktif`
--
DROP TABLE IF EXISTS `keluarga_aktif`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `keluarga_aktif`  AS  select `k`.`id` AS `id`,`k`.`no_kk` AS `no_kk`,`k`.`nik_kepala` AS `nik_kepala`,`k`.`tgl_daftar` AS `tgl_daftar`,`k`.`kelas_sosial` AS `kelas_sosial`,`k`.`tgl_cetak_kk` AS `tgl_cetak_kk`,`k`.`alamat` AS `alamat`,`k`.`id_cluster` AS `id_cluster` from (`tweb_keluarga` `k` left join `tweb_penduduk` `p` on(`k`.`nik_kepala` = `p`.`id`)) where `p`.`status_dasar` = 1 ;

-- --------------------------------------------------------

--
-- Struktur untuk view `penduduk_hidup`
--
DROP TABLE IF EXISTS `penduduk_hidup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `penduduk_hidup`  AS  select `tweb_penduduk`.`id` AS `id`,`tweb_penduduk`.`nama` AS `nama`,`tweb_penduduk`.`nik` AS `nik`,`tweb_penduduk`.`id_kk` AS `id_kk`,`tweb_penduduk`.`kk_level` AS `kk_level`,`tweb_penduduk`.`id_rtm` AS `id_rtm`,`tweb_penduduk`.`rtm_level` AS `rtm_level`,`tweb_penduduk`.`sex` AS `sex`,`tweb_penduduk`.`tempatlahir` AS `tempatlahir`,`tweb_penduduk`.`tanggallahir` AS `tanggallahir`,`tweb_penduduk`.`agama_id` AS `agama_id`,`tweb_penduduk`.`pendidikan_kk_id` AS `pendidikan_kk_id`,`tweb_penduduk`.`pendidikan_sedang_id` AS `pendidikan_sedang_id`,`tweb_penduduk`.`pekerjaan_id` AS `pekerjaan_id`,`tweb_penduduk`.`status_kawin` AS `status_kawin`,`tweb_penduduk`.`warganegara_id` AS `warganegara_id`,`tweb_penduduk`.`dokumen_pasport` AS `dokumen_pasport`,`tweb_penduduk`.`dokumen_kitas` AS `dokumen_kitas`,`tweb_penduduk`.`ayah_nik` AS `ayah_nik`,`tweb_penduduk`.`ibu_nik` AS `ibu_nik`,`tweb_penduduk`.`nama_ayah` AS `nama_ayah`,`tweb_penduduk`.`nama_ibu` AS `nama_ibu`,`tweb_penduduk`.`foto` AS `foto`,`tweb_penduduk`.`golongan_darah_id` AS `golongan_darah_id`,`tweb_penduduk`.`id_cluster` AS `id_cluster`,`tweb_penduduk`.`status` AS `status`,`tweb_penduduk`.`alamat_sebelumnya` AS `alamat_sebelumnya`,`tweb_penduduk`.`alamat_sekarang` AS `alamat_sekarang`,`tweb_penduduk`.`status_dasar` AS `status_dasar`,`tweb_penduduk`.`hamil` AS `hamil`,`tweb_penduduk`.`cacat_id` AS `cacat_id`,`tweb_penduduk`.`sakit_menahun_id` AS `sakit_menahun_id`,`tweb_penduduk`.`akta_lahir` AS `akta_lahir`,`tweb_penduduk`.`akta_perkawinan` AS `akta_perkawinan`,`tweb_penduduk`.`tanggalperkawinan` AS `tanggalperkawinan`,`tweb_penduduk`.`akta_perceraian` AS `akta_perceraian`,`tweb_penduduk`.`tanggalperceraian` AS `tanggalperceraian`,`tweb_penduduk`.`cara_kb_id` AS `cara_kb_id`,`tweb_penduduk`.`telepon` AS `telepon`,`tweb_penduduk`.`tanggal_akhir_paspor` AS `tanggal_akhir_paspor`,`tweb_penduduk`.`no_kk_sebelumnya` AS `no_kk_sebelumnya`,`tweb_penduduk`.`ktp_el` AS `ktp_el`,`tweb_penduduk`.`status_rekam` AS `status_rekam`,`tweb_penduduk`.`waktu_lahir` AS `waktu_lahir`,`tweb_penduduk`.`tempat_dilahirkan` AS `tempat_dilahirkan`,`tweb_penduduk`.`jenis_kelahiran` AS `jenis_kelahiran`,`tweb_penduduk`.`kelahiran_anak_ke` AS `kelahiran_anak_ke`,`tweb_penduduk`.`penolong_kelahiran` AS `penolong_kelahiran`,`tweb_penduduk`.`berat_lahir` AS `berat_lahir`,`tweb_penduduk`.`panjang_lahir` AS `panjang_lahir`,`tweb_penduduk`.`tag_id_card` AS `tag_id_card`,`tweb_penduduk`.`created_at` AS `created_at`,`tweb_penduduk`.`created_by` AS `created_by`,`tweb_penduduk`.`updated_at` AS `updated_at`,`tweb_penduduk`.`updated_by` AS `updated_by`,`tweb_penduduk`.`id_asuransi` AS `id_asuransi`,`tweb_penduduk`.`no_asuransi` AS `no_asuransi` from `tweb_penduduk` where `tweb_penduduk`.`status_dasar` = 1 ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_artikel_fk` (`id_artikel`);

--
-- Indeks untuk tabel `analisis_indikator`
--
ALTER TABLE `analisis_indikator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_master` (`id_master`,`id_tipe`),
  ADD KEY `id_tipe` (`id_tipe`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `analisis_kategori_indikator`
--
ALTER TABLE `analisis_kategori_indikator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_master` (`id_master`);

--
-- Indeks untuk tabel `analisis_klasifikasi`
--
ALTER TABLE `analisis_klasifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_master` (`id_master`);

--
-- Indeks untuk tabel `analisis_master`
--
ALTER TABLE `analisis_master`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `analisis_parameter`
--
ALTER TABLE `analisis_parameter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_indikator` (`id_indikator`);

--
-- Indeks untuk tabel `analisis_partisipasi`
--
ALTER TABLE `analisis_partisipasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_subjek` (`id_subjek`,`id_master`,`id_periode`,`id_klassifikasi`),
  ADD KEY `id_master` (`id_master`),
  ADD KEY `id_periode` (`id_periode`),
  ADD KEY `id_klassifikasi` (`id_klassifikasi`);

--
-- Indeks untuk tabel `analisis_periode`
--
ALTER TABLE `analisis_periode`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_master` (`id_master`),
  ADD KEY `id_state` (`id_state`);

--
-- Indeks untuk tabel `analisis_ref_state`
--
ALTER TABLE `analisis_ref_state`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `analisis_ref_subjek`
--
ALTER TABLE `analisis_ref_subjek`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `analisis_respon`
--
ALTER TABLE `analisis_respon`
  ADD KEY `id_parameter` (`id_parameter`,`id_subjek`),
  ADD KEY `id_periode` (`id_periode`),
  ADD KEY `id_indikator` (`id_indikator`);

--
-- Indeks untuk tabel `analisis_respon_hasil`
--
ALTER TABLE `analisis_respon_hasil`
  ADD UNIQUE KEY `id_master` (`id_master`,`id_periode`,`id_subjek`);

--
-- Indeks untuk tabel `analisis_tipe_indikator`
--
ALTER TABLE `analisis_tipe_indikator`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `anggota_grup_kontak`
--
ALTER TABLE `anggota_grup_kontak`
  ADD PRIMARY KEY (`id_grup_kontak`),
  ADD KEY `anggota_grup_kontak_ke_kontak` (`id_kontak`),
  ADD KEY `anggota_grup_kontak_ke_kontak_grup` (`id_grup`);

--
-- Indeks untuk tabel `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `captcha_codes`
--
ALTER TABLE `captcha_codes`
  ADD PRIMARY KEY (`id`,`namespace`),
  ADD KEY `created` (`created`);

--
-- Indeks untuk tabel `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `covid19_pantau`
--
ALTER TABLE `covid19_pantau`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pantau_pemudik` (`id_pemudik`);

--
-- Indeks untuk tabel `covid19_pemudik`
--
ALTER TABLE `covid19_pemudik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pemudik_penduduk` (`id_terdata`);

--
-- Indeks untuk tabel `data_persil`
--
ALTER TABLE `data_persil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pend` (`id_pend`);

--
-- Indeks untuk tabel `data_persil_jenis`
--
ALTER TABLE `data_persil_jenis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `data_persil_peruntukan`
--
ALTER TABLE `data_persil_peruntukan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `disposisi_surat_masuk`
--
ALTER TABLE `disposisi_surat_masuk`
  ADD PRIMARY KEY (`id_disposisi`),
  ADD KEY `id_surat_fk` (`id_surat_masuk`),
  ADD KEY `desa_pamong_fk` (`id_desa_pamong`);

--
-- Indeks untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gambar_gallery`
--
ALTER TABLE `gambar_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parrent` (`parrent`);

--
-- Indeks untuk tabel `garis`
--
ALTER TABLE `garis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `inbox`
--
ALTER TABLE `inbox`
  ADD PRIMARY KEY (`ID`);

--
-- Indeks untuk tabel `inventaris_asset`
--
ALTER TABLE `inventaris_asset`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `inventaris_gedung`
--
ALTER TABLE `inventaris_gedung`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `inventaris_jalan`
--
ALTER TABLE `inventaris_jalan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `inventaris_kontruksi`
--
ALTER TABLE `inventaris_kontruksi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `inventaris_peralatan`
--
ALTER TABLE `inventaris_peralatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `inventaris_tanah`
--
ALTER TABLE `inventaris_tanah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kelompok`
--
ALTER TABLE `kelompok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ketua` (`id_ketua`),
  ADD KEY `id_master` (`id_master`);

--
-- Indeks untuk tabel `kelompok_anggota`
--
ALTER TABLE `kelompok_anggota`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_kelompok` (`id_kelompok`,`id_penduduk`);

--
-- Indeks untuk tabel `kelompok_master`
--
ALTER TABLE `kelompok_master`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keuangan_master`
--
ALTER TABLE `keuangan_master`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keuangan_ref_bank_desa`
--
ALTER TABLE `keuangan_ref_bank_desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_bank_desa_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_bel_operasional`
--
ALTER TABLE `keuangan_ref_bel_operasional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_bel_operasional_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_bidang`
--
ALTER TABLE `keuangan_ref_bidang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_bidang_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_bunga`
--
ALTER TABLE `keuangan_ref_bunga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_bunga_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_desa`
--
ALTER TABLE `keuangan_ref_desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_desa_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_kecamatan`
--
ALTER TABLE `keuangan_ref_kecamatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_kecamatan_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_kegiatan`
--
ALTER TABLE `keuangan_ref_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_kegiatan_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_korolari`
--
ALTER TABLE `keuangan_ref_korolari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_korolari_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_neraca_close`
--
ALTER TABLE `keuangan_ref_neraca_close`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_neraca_close_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_perangkat`
--
ALTER TABLE `keuangan_ref_perangkat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_perangkat_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_potongan`
--
ALTER TABLE `keuangan_ref_potongan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_potongan_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_rek1`
--
ALTER TABLE `keuangan_ref_rek1`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_rek1_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_rek2`
--
ALTER TABLE `keuangan_ref_rek2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_rek2_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_rek3`
--
ALTER TABLE `keuangan_ref_rek3`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_rek3_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_rek4`
--
ALTER TABLE `keuangan_ref_rek4`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_rek4_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_sbu`
--
ALTER TABLE `keuangan_ref_sbu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_sbu_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ref_sumber`
--
ALTER TABLE `keuangan_ref_sumber`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ref_sumber_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_anggaran`
--
ALTER TABLE `keuangan_ta_anggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_anggaran_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_anggaran_log`
--
ALTER TABLE `keuangan_ta_anggaran_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_anggaran_log_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_anggaran_rinci`
--
ALTER TABLE `keuangan_ta_anggaran_rinci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_anggaran_rinci_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_bidang`
--
ALTER TABLE `keuangan_ta_bidang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_bidang_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_desa`
--
ALTER TABLE `keuangan_ta_desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_desa_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_jurnal_umum`
--
ALTER TABLE `keuangan_ta_jurnal_umum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_jurnal_umum_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_jurnal_umum_rinci`
--
ALTER TABLE `keuangan_ta_jurnal_umum_rinci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_jurnal_umum_rinci_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_kegiatan`
--
ALTER TABLE `keuangan_ta_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_kegiatan_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_mutasi`
--
ALTER TABLE `keuangan_ta_mutasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_mutasi_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_pajak`
--
ALTER TABLE `keuangan_ta_pajak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_pajak_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_pajak_rinci`
--
ALTER TABLE `keuangan_ta_pajak_rinci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_pajak_rinci_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_pemda`
--
ALTER TABLE `keuangan_ta_pemda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_pemda_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_pencairan`
--
ALTER TABLE `keuangan_ta_pencairan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_pencairan_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_perangkat`
--
ALTER TABLE `keuangan_ta_perangkat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_perangkat_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rab`
--
ALTER TABLE `keuangan_ta_rab`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rab_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rab_rinci`
--
ALTER TABLE `keuangan_ta_rab_rinci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rab_rinci_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rab_sub`
--
ALTER TABLE `keuangan_ta_rab_sub`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rab_sub_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rpjm_bidang`
--
ALTER TABLE `keuangan_ta_rpjm_bidang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rpjm_bidang_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rpjm_kegiatan`
--
ALTER TABLE `keuangan_ta_rpjm_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rpjm_kegiatan_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rpjm_misi`
--
ALTER TABLE `keuangan_ta_rpjm_misi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rpjm_misi_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rpjm_pagu_indikatif`
--
ALTER TABLE `keuangan_ta_rpjm_pagu_indikatif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rpjm_pagu_indikatif_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rpjm_pagu_tahunan`
--
ALTER TABLE `keuangan_ta_rpjm_pagu_tahunan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rpjm_pagu_tahunan_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rpjm_sasaran`
--
ALTER TABLE `keuangan_ta_rpjm_sasaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rpjm_sasaran_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rpjm_tujuan`
--
ALTER TABLE `keuangan_ta_rpjm_tujuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rpjm_tujuan_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_rpjm_visi`
--
ALTER TABLE `keuangan_ta_rpjm_visi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_rpjm_visi_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_saldo_awal`
--
ALTER TABLE `keuangan_ta_saldo_awal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_saldo_awal_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_spj`
--
ALTER TABLE `keuangan_ta_spj`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_spj_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_spjpot`
--
ALTER TABLE `keuangan_ta_spjpot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_spjpot_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_spj_bukti`
--
ALTER TABLE `keuangan_ta_spj_bukti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_spj_bukti_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_spj_rinci`
--
ALTER TABLE `keuangan_ta_spj_rinci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_spj_rinci_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_spj_sisa`
--
ALTER TABLE `keuangan_ta_spj_sisa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_spj_sisa_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_spp`
--
ALTER TABLE `keuangan_ta_spp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_spp_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_sppbukti`
--
ALTER TABLE `keuangan_ta_sppbukti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_sppbukti_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_spppot`
--
ALTER TABLE `keuangan_ta_spppot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_spppot_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_spp_rinci`
--
ALTER TABLE `keuangan_ta_spp_rinci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_spp_rinci_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_sts`
--
ALTER TABLE `keuangan_ta_sts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_sts_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_sts_rinci`
--
ALTER TABLE `keuangan_ta_sts_rinci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_sts_rinci_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_tbp`
--
ALTER TABLE `keuangan_ta_tbp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_tbp_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_tbp_rinci`
--
ALTER TABLE `keuangan_ta_tbp_rinci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_tbp_rinci_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_triwulan`
--
ALTER TABLE `keuangan_ta_triwulan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_triwulan_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `keuangan_ta_triwulan_rinci`
--
ALTER TABLE `keuangan_ta_triwulan_rinci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_keuangan_ta_triwulan_rinci_master_fk` (`id_keuangan_master`);

--
-- Indeks untuk tabel `klasifikasi_analisis_keluarga`
--
ALTER TABLE `klasifikasi_analisis_keluarga`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `klasifikasi_surat`
--
ALTER TABLE `klasifikasi_surat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id_kontak`),
  ADD KEY `kontak_ke_tweb_penduduk` (`id_pend`);

--
-- Indeks untuk tabel `kontak_grup`
--
ALTER TABLE `kontak_grup`
  ADD PRIMARY KEY (`id_grup`);

--
-- Indeks untuk tabel `line`
--
ALTER TABLE `line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parrent` (`parrent`);

--
-- Indeks untuk tabel `log_bulanan`
--
ALTER TABLE `log_bulanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_ekspor`
--
ALTER TABLE `log_ekspor`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_keluarga`
--
ALTER TABLE `log_keluarga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_kk` (`id_kk`,`id_peristiwa`,`tgl_peristiwa`);

--
-- Indeks untuk tabel `log_penduduk`
--
ALTER TABLE `log_penduduk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_pend` (`id_pend`,`id_detail`,`tgl_peristiwa`),
  ADD KEY `id_ref_pindah` (`ref_pindah`);

--
-- Indeks untuk tabel `log_perubahan_penduduk`
--
ALTER TABLE `log_perubahan_penduduk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_surat`
--
ALTER TABLE `log_surat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_point` (`ref_point`);

--
-- Indeks untuk tabel `media_sosial`
--
ALTER TABLE `media_sosial`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrasi`
--
ALTER TABLE `migrasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mutasi_inventaris_asset`
--
ALTER TABLE `mutasi_inventaris_asset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_mutasi_inventaris_asset` (`id_inventaris_asset`);

--
-- Indeks untuk tabel `mutasi_inventaris_gedung`
--
ALTER TABLE `mutasi_inventaris_gedung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_mutasi_inventaris_gedung` (`id_inventaris_gedung`);

--
-- Indeks untuk tabel `mutasi_inventaris_jalan`
--
ALTER TABLE `mutasi_inventaris_jalan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_mutasi_inventaris_jalan` (`id_inventaris_jalan`);

--
-- Indeks untuk tabel `mutasi_inventaris_peralatan`
--
ALTER TABLE `mutasi_inventaris_peralatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_mutasi_inventaris_peralatan` (`id_inventaris_peralatan`);

--
-- Indeks untuk tabel `mutasi_inventaris_tanah`
--
ALTER TABLE `mutasi_inventaris_tanah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_mutasi_inventaris_tanah` (`id_inventaris_tanah`);

--
-- Indeks untuk tabel `outbox`
--
ALTER TABLE `outbox`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `outbox_date` (`SendingDateTime`,`SendingTimeOut`),
  ADD KEY `outbox_sender` (`SenderID`);

--
-- Indeks untuk tabel `permohonan_surat`
--
ALTER TABLE `permohonan_surat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `point`
--
ALTER TABLE `point`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parrent` (`parrent`);

--
-- Indeks untuk tabel `polygon`
--
ALTER TABLE `polygon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parrent` (`parrent`);

--
-- Indeks untuk tabel `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `program_peserta`
--
ALTER TABLE `program_peserta`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `provinsi`
--
ALTER TABLE `provinsi`
  ADD PRIMARY KEY (`kode`);

--
-- Indeks untuk tabel `ref_dokumen`
--
ALTER TABLE `ref_dokumen`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_pindah`
--
ALTER TABLE `ref_pindah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_status_covid`
--
ALTER TABLE `ref_status_covid`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_syarat_surat`
--
ALTER TABLE `ref_syarat_surat`
  ADD PRIMARY KEY (`ref_syarat_id`);

--
-- Indeks untuk tabel `sentitems`
--
ALTER TABLE `sentitems`
  ADD PRIMARY KEY (`ID`,`SequencePosition`),
  ADD KEY `sentitems_date` (`DeliveryDateTime`),
  ADD KEY `sentitems_tpmr` (`TPMR`),
  ADD KEY `sentitems_dest` (`DestinationNumber`),
  ADD KEY `sentitems_sender` (`SenderID`);

--
-- Indeks untuk tabel `setting_aplikasi`
--
ALTER TABLE `setting_aplikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `setting_aplikasi_options`
--
ALTER TABLE `setting_aplikasi_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_setting_fk` (`id_setting`);

--
-- Indeks untuk tabel `setting_modul`
--
ALTER TABLE `setting_modul`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `suplemen`
--
ALTER TABLE `suplemen`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `suplemen_terdata`
--
ALTER TABLE `suplemen_terdata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_suplemen` (`id_suplemen`);

--
-- Indeks untuk tabel `surat_keluar`
--
ALTER TABLE `surat_keluar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `surat_masuk`
--
ALTER TABLE `surat_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `syarat_surat`
--
ALTER TABLE `syarat_surat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_surat_format` (`surat_format_id`);

--
-- Indeks untuk tabel `sys_traffic`
--
ALTER TABLE `sys_traffic`
  ADD PRIMARY KEY (`Tanggal`);

--
-- Indeks untuk tabel `teks_berjalan`
--
ALTER TABLE `teks_berjalan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_aset`
--
ALTER TABLE `tweb_aset`
  ADD PRIMARY KEY (`id_aset`);

--
-- Indeks untuk tabel `tweb_cacat`
--
ALTER TABLE `tweb_cacat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_cara_kb`
--
ALTER TABLE `tweb_cara_kb`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_desa_pamong`
--
ALTER TABLE `tweb_desa_pamong`
  ADD PRIMARY KEY (`pamong_id`);

--
-- Indeks untuk tabel `tweb_golongan_darah`
--
ALTER TABLE `tweb_golongan_darah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_keluarga`
--
ALTER TABLE `tweb_keluarga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nik_kepala` (`nik_kepala`);

--
-- Indeks untuk tabel `tweb_keluarga_sejahtera`
--
ALTER TABLE `tweb_keluarga_sejahtera`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk`
--
ALTER TABLE `tweb_penduduk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_agama`
--
ALTER TABLE `tweb_penduduk_agama`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_asuransi`
--
ALTER TABLE `tweb_penduduk_asuransi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_hubungan`
--
ALTER TABLE `tweb_penduduk_hubungan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_kawin`
--
ALTER TABLE `tweb_penduduk_kawin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_mandiri`
--
ALTER TABLE `tweb_penduduk_mandiri`
  ADD PRIMARY KEY (`id_pend`);

--
-- Indeks untuk tabel `tweb_penduduk_pekerjaan`
--
ALTER TABLE `tweb_penduduk_pekerjaan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_pendidikan`
--
ALTER TABLE `tweb_penduduk_pendidikan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_pendidikan_kk`
--
ALTER TABLE `tweb_penduduk_pendidikan_kk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_sex`
--
ALTER TABLE `tweb_penduduk_sex`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_status`
--
ALTER TABLE `tweb_penduduk_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_umur`
--
ALTER TABLE `tweb_penduduk_umur`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_penduduk_warganegara`
--
ALTER TABLE `tweb_penduduk_warganegara`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_rtm`
--
ALTER TABLE `tweb_rtm`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_kk` (`no_kk`),
  ADD UNIQUE KEY `no_kk_2` (`no_kk`);

--
-- Indeks untuk tabel `tweb_rtm_hubungan`
--
ALTER TABLE `tweb_rtm_hubungan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_sakit_menahun`
--
ALTER TABLE `tweb_sakit_menahun`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_status_dasar`
--
ALTER TABLE `tweb_status_dasar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_status_ktp`
--
ALTER TABLE `tweb_status_ktp`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_surat_atribut`
--
ALTER TABLE `tweb_surat_atribut`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tweb_surat_format`
--
ALTER TABLE `tweb_surat_format`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url_surat` (`url_surat`);

--
-- Indeks untuk tabel `tweb_wil_clusterdesa`
--
ALTER TABLE `tweb_wil_clusterdesa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rt` (`rt`,`rw`,`dusun`),
  ADD KEY `id_kepala` (`id_kepala`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_grup`
--
ALTER TABLE `user_grup`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `widget`
--
ALTER TABLE `widget`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `analisis_indikator`
--
ALTER TABLE `analisis_indikator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT untuk tabel `analisis_kategori_indikator`
--
ALTER TABLE `analisis_kategori_indikator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `analisis_klasifikasi`
--
ALTER TABLE `analisis_klasifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `analisis_master`
--
ALTER TABLE `analisis_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `analisis_parameter`
--
ALTER TABLE `analisis_parameter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1052;

--
-- AUTO_INCREMENT untuk tabel `analisis_partisipasi`
--
ALTER TABLE `analisis_partisipasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `analisis_periode`
--
ALTER TABLE `analisis_periode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `analisis_ref_state`
--
ALTER TABLE `analisis_ref_state`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `analisis_ref_subjek`
--
ALTER TABLE `analisis_ref_subjek`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `analisis_tipe_indikator`
--
ALTER TABLE `analisis_tipe_indikator`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `anggota_grup_kontak`
--
ALTER TABLE `anggota_grup_kontak`
  MODIFY `id_grup_kontak` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `area`
--
ALTER TABLE `area`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `config`
--
ALTER TABLE `config`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `covid19_pantau`
--
ALTER TABLE `covid19_pantau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `covid19_pemudik`
--
ALTER TABLE `covid19_pemudik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_persil`
--
ALTER TABLE `data_persil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_persil_jenis`
--
ALTER TABLE `data_persil_jenis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_persil_peruntukan`
--
ALTER TABLE `data_persil_peruntukan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `disposisi_surat_masuk`
--
ALTER TABLE `disposisi_surat_masuk`
  MODIFY `id_disposisi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `gambar_gallery`
--
ALTER TABLE `gambar_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `garis`
--
ALTER TABLE `garis`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `inbox`
--
ALTER TABLE `inbox`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `inventaris_asset`
--
ALTER TABLE `inventaris_asset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `inventaris_gedung`
--
ALTER TABLE `inventaris_gedung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `inventaris_jalan`
--
ALTER TABLE `inventaris_jalan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `inventaris_kontruksi`
--
ALTER TABLE `inventaris_kontruksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `inventaris_peralatan`
--
ALTER TABLE `inventaris_peralatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `inventaris_tanah`
--
ALTER TABLE `inventaris_tanah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `kelompok`
--
ALTER TABLE `kelompok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kelompok_anggota`
--
ALTER TABLE `kelompok_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kelompok_master`
--
ALTER TABLE `kelompok_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `keuangan_master`
--
ALTER TABLE `keuangan_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_bank_desa`
--
ALTER TABLE `keuangan_ref_bank_desa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_bel_operasional`
--
ALTER TABLE `keuangan_ref_bel_operasional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_bidang`
--
ALTER TABLE `keuangan_ref_bidang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_bunga`
--
ALTER TABLE `keuangan_ref_bunga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_desa`
--
ALTER TABLE `keuangan_ref_desa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_kecamatan`
--
ALTER TABLE `keuangan_ref_kecamatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_kegiatan`
--
ALTER TABLE `keuangan_ref_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_korolari`
--
ALTER TABLE `keuangan_ref_korolari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_neraca_close`
--
ALTER TABLE `keuangan_ref_neraca_close`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_perangkat`
--
ALTER TABLE `keuangan_ref_perangkat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_potongan`
--
ALTER TABLE `keuangan_ref_potongan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_rek1`
--
ALTER TABLE `keuangan_ref_rek1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_rek2`
--
ALTER TABLE `keuangan_ref_rek2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_rek3`
--
ALTER TABLE `keuangan_ref_rek3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_rek4`
--
ALTER TABLE `keuangan_ref_rek4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_sbu`
--
ALTER TABLE `keuangan_ref_sbu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ref_sumber`
--
ALTER TABLE `keuangan_ref_sumber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_anggaran`
--
ALTER TABLE `keuangan_ta_anggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_anggaran_log`
--
ALTER TABLE `keuangan_ta_anggaran_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_anggaran_rinci`
--
ALTER TABLE `keuangan_ta_anggaran_rinci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_bidang`
--
ALTER TABLE `keuangan_ta_bidang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_desa`
--
ALTER TABLE `keuangan_ta_desa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_jurnal_umum`
--
ALTER TABLE `keuangan_ta_jurnal_umum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_jurnal_umum_rinci`
--
ALTER TABLE `keuangan_ta_jurnal_umum_rinci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_kegiatan`
--
ALTER TABLE `keuangan_ta_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_mutasi`
--
ALTER TABLE `keuangan_ta_mutasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_pajak`
--
ALTER TABLE `keuangan_ta_pajak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_pajak_rinci`
--
ALTER TABLE `keuangan_ta_pajak_rinci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_pemda`
--
ALTER TABLE `keuangan_ta_pemda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_pencairan`
--
ALTER TABLE `keuangan_ta_pencairan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_perangkat`
--
ALTER TABLE `keuangan_ta_perangkat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rab`
--
ALTER TABLE `keuangan_ta_rab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rab_rinci`
--
ALTER TABLE `keuangan_ta_rab_rinci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rab_sub`
--
ALTER TABLE `keuangan_ta_rab_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rpjm_bidang`
--
ALTER TABLE `keuangan_ta_rpjm_bidang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rpjm_kegiatan`
--
ALTER TABLE `keuangan_ta_rpjm_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rpjm_misi`
--
ALTER TABLE `keuangan_ta_rpjm_misi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rpjm_pagu_indikatif`
--
ALTER TABLE `keuangan_ta_rpjm_pagu_indikatif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rpjm_pagu_tahunan`
--
ALTER TABLE `keuangan_ta_rpjm_pagu_tahunan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rpjm_sasaran`
--
ALTER TABLE `keuangan_ta_rpjm_sasaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rpjm_tujuan`
--
ALTER TABLE `keuangan_ta_rpjm_tujuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_rpjm_visi`
--
ALTER TABLE `keuangan_ta_rpjm_visi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_saldo_awal`
--
ALTER TABLE `keuangan_ta_saldo_awal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_spj`
--
ALTER TABLE `keuangan_ta_spj`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_spjpot`
--
ALTER TABLE `keuangan_ta_spjpot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_spj_bukti`
--
ALTER TABLE `keuangan_ta_spj_bukti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_spj_rinci`
--
ALTER TABLE `keuangan_ta_spj_rinci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_spj_sisa`
--
ALTER TABLE `keuangan_ta_spj_sisa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_spp`
--
ALTER TABLE `keuangan_ta_spp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_sppbukti`
--
ALTER TABLE `keuangan_ta_sppbukti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_spppot`
--
ALTER TABLE `keuangan_ta_spppot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_spp_rinci`
--
ALTER TABLE `keuangan_ta_spp_rinci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_sts`
--
ALTER TABLE `keuangan_ta_sts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_sts_rinci`
--
ALTER TABLE `keuangan_ta_sts_rinci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_tbp`
--
ALTER TABLE `keuangan_ta_tbp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_tbp_rinci`
--
ALTER TABLE `keuangan_ta_tbp_rinci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_triwulan`
--
ALTER TABLE `keuangan_ta_triwulan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_ta_triwulan_rinci`
--
ALTER TABLE `keuangan_ta_triwulan_rinci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `klasifikasi_analisis_keluarga`
--
ALTER TABLE `klasifikasi_analisis_keluarga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `klasifikasi_surat`
--
ALTER TABLE `klasifikasi_surat`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2335;

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id_kontak` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kontak_grup`
--
ALTER TABLE `kontak_grup`
  MODIFY `id_grup` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `line`
--
ALTER TABLE `line`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `log_bulanan`
--
ALTER TABLE `log_bulanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1606;

--
-- AUTO_INCREMENT untuk tabel `log_ekspor`
--
ALTER TABLE `log_ekspor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_keluarga`
--
ALTER TABLE `log_keluarga`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_penduduk`
--
ALTER TABLE `log_penduduk`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_perubahan_penduduk`
--
ALTER TABLE `log_perubahan_penduduk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_surat`
--
ALTER TABLE `log_surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `media_sosial`
--
ALTER TABLE `media_sosial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT untuk tabel `migrasi`
--
ALTER TABLE `migrasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `mutasi_inventaris_asset`
--
ALTER TABLE `mutasi_inventaris_asset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mutasi_inventaris_gedung`
--
ALTER TABLE `mutasi_inventaris_gedung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mutasi_inventaris_jalan`
--
ALTER TABLE `mutasi_inventaris_jalan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mutasi_inventaris_peralatan`
--
ALTER TABLE `mutasi_inventaris_peralatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mutasi_inventaris_tanah`
--
ALTER TABLE `mutasi_inventaris_tanah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `outbox`
--
ALTER TABLE `outbox`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `permohonan_surat`
--
ALTER TABLE `permohonan_surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `point`
--
ALTER TABLE `point`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `polygon`
--
ALTER TABLE `polygon`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `program_peserta`
--
ALTER TABLE `program_peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `ref_dokumen`
--
ALTER TABLE `ref_dokumen`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `ref_status_covid`
--
ALTER TABLE `ref_status_covid`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `ref_syarat_surat`
--
ALTER TABLE `ref_syarat_surat`
  MODIFY `ref_syarat_id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `setting_aplikasi`
--
ALTER TABLE `setting_aplikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `setting_aplikasi_options`
--
ALTER TABLE `setting_aplikasi_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `setting_modul`
--
ALTER TABLE `setting_modul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT untuk tabel `suplemen`
--
ALTER TABLE `suplemen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `suplemen_terdata`
--
ALTER TABLE `suplemen_terdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `surat_keluar`
--
ALTER TABLE `surat_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `surat_masuk`
--
ALTER TABLE `surat_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `syarat_surat`
--
ALTER TABLE `syarat_surat`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `teks_berjalan`
--
ALTER TABLE `teks_berjalan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tweb_cacat`
--
ALTER TABLE `tweb_cacat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tweb_cara_kb`
--
ALTER TABLE `tweb_cara_kb`
  MODIFY `id` tinyint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT untuk tabel `tweb_desa_pamong`
--
ALTER TABLE `tweb_desa_pamong`
  MODIFY `pamong_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `tweb_golongan_darah`
--
ALTER TABLE `tweb_golongan_darah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `tweb_keluarga`
--
ALTER TABLE `tweb_keluarga`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk`
--
ALTER TABLE `tweb_penduduk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_agama`
--
ALTER TABLE `tweb_penduduk_agama`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_asuransi`
--
ALTER TABLE `tweb_penduduk_asuransi`
  MODIFY `id` tinyint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_hubungan`
--
ALTER TABLE `tweb_penduduk_hubungan`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_kawin`
--
ALTER TABLE `tweb_penduduk_kawin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_pekerjaan`
--
ALTER TABLE `tweb_penduduk_pekerjaan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_pendidikan`
--
ALTER TABLE `tweb_penduduk_pendidikan`
  MODIFY `id` tinyint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_pendidikan_kk`
--
ALTER TABLE `tweb_penduduk_pendidikan_kk`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_sex`
--
ALTER TABLE `tweb_penduduk_sex`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_status`
--
ALTER TABLE `tweb_penduduk_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_umur`
--
ALTER TABLE `tweb_penduduk_umur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `tweb_penduduk_warganegara`
--
ALTER TABLE `tweb_penduduk_warganegara`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tweb_rtm`
--
ALTER TABLE `tweb_rtm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tweb_rtm_hubungan`
--
ALTER TABLE `tweb_rtm_hubungan`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tweb_sakit_menahun`
--
ALTER TABLE `tweb_sakit_menahun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `tweb_status_dasar`
--
ALTER TABLE `tweb_status_dasar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `tweb_status_ktp`
--
ALTER TABLE `tweb_status_ktp`
  MODIFY `id` tinyint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tweb_surat_atribut`
--
ALTER TABLE `tweb_surat_atribut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tweb_surat_format`
--
ALTER TABLE `tweb_surat_format`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT untuk tabel `tweb_wil_clusterdesa`
--
ALTER TABLE `tweb_wil_clusterdesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `widget`
--
ALTER TABLE `widget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `id_artikel_fk` FOREIGN KEY (`id_artikel`) REFERENCES `artikel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `anggota_grup_kontak`
--
ALTER TABLE `anggota_grup_kontak`
  ADD CONSTRAINT `anggota_grup_kontak_ke_kontak` FOREIGN KEY (`id_kontak`) REFERENCES `kontak` (`id_kontak`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `anggota_grup_kontak_ke_kontak_grup` FOREIGN KEY (`id_grup`) REFERENCES `kontak_grup` (`id_grup`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `covid19_pantau`
--
ALTER TABLE `covid19_pantau`
  ADD CONSTRAINT `fk_pantau_pemudik` FOREIGN KEY (`id_pemudik`) REFERENCES `covid19_pemudik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `covid19_pemudik`
--
ALTER TABLE `covid19_pemudik`
  ADD CONSTRAINT `fk_pemudik_penduduk` FOREIGN KEY (`id_terdata`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `data_persil`
--
ALTER TABLE `data_persil`
  ADD CONSTRAINT `persil_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `disposisi_surat_masuk`
--
ALTER TABLE `disposisi_surat_masuk`
  ADD CONSTRAINT `desa_pamong_fk` FOREIGN KEY (`id_desa_pamong`) REFERENCES `tweb_desa_pamong` (`pamong_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_surat_fk` FOREIGN KEY (`id_surat_masuk`) REFERENCES `surat_masuk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_bank_desa`
--
ALTER TABLE `keuangan_ref_bank_desa`
  ADD CONSTRAINT `id_keuangan_ref_bank_desa_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_bel_operasional`
--
ALTER TABLE `keuangan_ref_bel_operasional`
  ADD CONSTRAINT `id_keuangan_ref_bel_operasional_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_bidang`
--
ALTER TABLE `keuangan_ref_bidang`
  ADD CONSTRAINT `id_keuangan_ref_bidang_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_bunga`
--
ALTER TABLE `keuangan_ref_bunga`
  ADD CONSTRAINT `id_keuangan_ref_bunga_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_desa`
--
ALTER TABLE `keuangan_ref_desa`
  ADD CONSTRAINT `id_keuangan_ref_desa_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_kecamatan`
--
ALTER TABLE `keuangan_ref_kecamatan`
  ADD CONSTRAINT `id_keuangan_ref_kecamatan_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_kegiatan`
--
ALTER TABLE `keuangan_ref_kegiatan`
  ADD CONSTRAINT `id_keuangan_ref_kegiatan_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_korolari`
--
ALTER TABLE `keuangan_ref_korolari`
  ADD CONSTRAINT `id_keuangan_ref_korolari_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_neraca_close`
--
ALTER TABLE `keuangan_ref_neraca_close`
  ADD CONSTRAINT `id_keuangan_ref_neraca_close_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_perangkat`
--
ALTER TABLE `keuangan_ref_perangkat`
  ADD CONSTRAINT `id_keuangan_ref_perangkat_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_potongan`
--
ALTER TABLE `keuangan_ref_potongan`
  ADD CONSTRAINT `id_keuangan_ref_potongan_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_rek1`
--
ALTER TABLE `keuangan_ref_rek1`
  ADD CONSTRAINT `id_keuangan_ref_rek1_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_rek2`
--
ALTER TABLE `keuangan_ref_rek2`
  ADD CONSTRAINT `id_keuangan_ref_rek2_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_rek3`
--
ALTER TABLE `keuangan_ref_rek3`
  ADD CONSTRAINT `id_keuangan_ref_rek3_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_rek4`
--
ALTER TABLE `keuangan_ref_rek4`
  ADD CONSTRAINT `id_keuangan_ref_rek4_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_sbu`
--
ALTER TABLE `keuangan_ref_sbu`
  ADD CONSTRAINT `id_keuangan_ref_sbu_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ref_sumber`
--
ALTER TABLE `keuangan_ref_sumber`
  ADD CONSTRAINT `id_keuangan_ref_sumber_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_anggaran`
--
ALTER TABLE `keuangan_ta_anggaran`
  ADD CONSTRAINT `id_keuangan_ta_anggaran_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_anggaran_log`
--
ALTER TABLE `keuangan_ta_anggaran_log`
  ADD CONSTRAINT `id_keuangan_ta_anggaran_log_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_anggaran_rinci`
--
ALTER TABLE `keuangan_ta_anggaran_rinci`
  ADD CONSTRAINT `id_keuangan_ta_anggaran_rinci_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_bidang`
--
ALTER TABLE `keuangan_ta_bidang`
  ADD CONSTRAINT `id_keuangan_ta_bidang_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_desa`
--
ALTER TABLE `keuangan_ta_desa`
  ADD CONSTRAINT `id_keuangan_ta_desa_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_jurnal_umum`
--
ALTER TABLE `keuangan_ta_jurnal_umum`
  ADD CONSTRAINT `id_keuangan_ta_jurnal_umum_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_jurnal_umum_rinci`
--
ALTER TABLE `keuangan_ta_jurnal_umum_rinci`
  ADD CONSTRAINT `id_keuangan_ta_jurnal_umum_rinci_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_kegiatan`
--
ALTER TABLE `keuangan_ta_kegiatan`
  ADD CONSTRAINT `id_keuangan_ta_kegiatan_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_mutasi`
--
ALTER TABLE `keuangan_ta_mutasi`
  ADD CONSTRAINT `id_keuangan_ta_mutasi_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_pajak`
--
ALTER TABLE `keuangan_ta_pajak`
  ADD CONSTRAINT `id_keuangan_ta_pajak_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_pajak_rinci`
--
ALTER TABLE `keuangan_ta_pajak_rinci`
  ADD CONSTRAINT `id_keuangan_ta_pajak_rinci_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_pemda`
--
ALTER TABLE `keuangan_ta_pemda`
  ADD CONSTRAINT `id_keuangan_ta_pemda_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_pencairan`
--
ALTER TABLE `keuangan_ta_pencairan`
  ADD CONSTRAINT `id_keuangan_ta_pencairan_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_perangkat`
--
ALTER TABLE `keuangan_ta_perangkat`
  ADD CONSTRAINT `id_keuangan_ta_perangkat_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rab`
--
ALTER TABLE `keuangan_ta_rab`
  ADD CONSTRAINT `id_keuangan_ta_rab_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rab_rinci`
--
ALTER TABLE `keuangan_ta_rab_rinci`
  ADD CONSTRAINT `id_keuangan_ta_rab_rinci_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rab_sub`
--
ALTER TABLE `keuangan_ta_rab_sub`
  ADD CONSTRAINT `id_keuangan_ta_rab_sub_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rpjm_bidang`
--
ALTER TABLE `keuangan_ta_rpjm_bidang`
  ADD CONSTRAINT `id_keuangan_ta_rpjm_bidang_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rpjm_kegiatan`
--
ALTER TABLE `keuangan_ta_rpjm_kegiatan`
  ADD CONSTRAINT `id_keuangan_ta_rpjm_kegiatan_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rpjm_misi`
--
ALTER TABLE `keuangan_ta_rpjm_misi`
  ADD CONSTRAINT `id_keuangan_ta_rpjm_misi_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rpjm_pagu_indikatif`
--
ALTER TABLE `keuangan_ta_rpjm_pagu_indikatif`
  ADD CONSTRAINT `id_keuangan_ta_rpjm_pagu_indikatif_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rpjm_pagu_tahunan`
--
ALTER TABLE `keuangan_ta_rpjm_pagu_tahunan`
  ADD CONSTRAINT `id_keuangan_ta_rpjm_pagu_tahunan_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rpjm_sasaran`
--
ALTER TABLE `keuangan_ta_rpjm_sasaran`
  ADD CONSTRAINT `id_keuangan_ta_rpjm_sasaran_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rpjm_tujuan`
--
ALTER TABLE `keuangan_ta_rpjm_tujuan`
  ADD CONSTRAINT `id_keuangan_ta_rpjm_tujuan_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_rpjm_visi`
--
ALTER TABLE `keuangan_ta_rpjm_visi`
  ADD CONSTRAINT `id_keuangan_ta_rpjm_visi_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_saldo_awal`
--
ALTER TABLE `keuangan_ta_saldo_awal`
  ADD CONSTRAINT `id_keuangan_ta_saldo_awal_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_spj`
--
ALTER TABLE `keuangan_ta_spj`
  ADD CONSTRAINT `id_keuangan_ta_spj_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_spjpot`
--
ALTER TABLE `keuangan_ta_spjpot`
  ADD CONSTRAINT `id_keuangan_ta_spjpot_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_spj_bukti`
--
ALTER TABLE `keuangan_ta_spj_bukti`
  ADD CONSTRAINT `id_keuangan_ta_spj_bukti_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_spj_rinci`
--
ALTER TABLE `keuangan_ta_spj_rinci`
  ADD CONSTRAINT `id_keuangan_ta_spj_rinci_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_spj_sisa`
--
ALTER TABLE `keuangan_ta_spj_sisa`
  ADD CONSTRAINT `id_keuangan_ta_spj_sisa_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_spp`
--
ALTER TABLE `keuangan_ta_spp`
  ADD CONSTRAINT `id_keuangan_ta_spp_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_sppbukti`
--
ALTER TABLE `keuangan_ta_sppbukti`
  ADD CONSTRAINT `id_keuangan_ta_sppbukti_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_spppot`
--
ALTER TABLE `keuangan_ta_spppot`
  ADD CONSTRAINT `id_keuangan_ta_spppot_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_spp_rinci`
--
ALTER TABLE `keuangan_ta_spp_rinci`
  ADD CONSTRAINT `id_keuangan_ta_spp_rinci_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_sts`
--
ALTER TABLE `keuangan_ta_sts`
  ADD CONSTRAINT `id_keuangan_ta_sts_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_sts_rinci`
--
ALTER TABLE `keuangan_ta_sts_rinci`
  ADD CONSTRAINT `id_keuangan_ta_sts_rinci_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_tbp`
--
ALTER TABLE `keuangan_ta_tbp`
  ADD CONSTRAINT `id_keuangan_ta_tbp_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_tbp_rinci`
--
ALTER TABLE `keuangan_ta_tbp_rinci`
  ADD CONSTRAINT `id_keuangan_ta_tbp_rinci_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_triwulan`
--
ALTER TABLE `keuangan_ta_triwulan`
  ADD CONSTRAINT `id_keuangan_ta_triwulan_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keuangan_ta_triwulan_rinci`
--
ALTER TABLE `keuangan_ta_triwulan_rinci`
  ADD CONSTRAINT `id_keuangan_ta_triwulan_rinci_master_fk` FOREIGN KEY (`id_keuangan_master`) REFERENCES `keuangan_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kontak`
--
ALTER TABLE `kontak`
  ADD CONSTRAINT `kontak_ke_tweb_penduduk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_penduduk`
--
ALTER TABLE `log_penduduk`
  ADD CONSTRAINT `id_ref_pindah` FOREIGN KEY (`ref_pindah`) REFERENCES `ref_pindah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mutasi_inventaris_asset`
--
ALTER TABLE `mutasi_inventaris_asset`
  ADD CONSTRAINT `FK_mutasi_inventaris_asset` FOREIGN KEY (`id_inventaris_asset`) REFERENCES `inventaris_asset` (`id`);

--
-- Ketidakleluasaan untuk tabel `mutasi_inventaris_gedung`
--
ALTER TABLE `mutasi_inventaris_gedung`
  ADD CONSTRAINT `FK_mutasi_inventaris_gedung` FOREIGN KEY (`id_inventaris_gedung`) REFERENCES `inventaris_gedung` (`id`);

--
-- Ketidakleluasaan untuk tabel `mutasi_inventaris_jalan`
--
ALTER TABLE `mutasi_inventaris_jalan`
  ADD CONSTRAINT `FK_mutasi_inventaris_jalan` FOREIGN KEY (`id_inventaris_jalan`) REFERENCES `inventaris_jalan` (`id`);

--
-- Ketidakleluasaan untuk tabel `mutasi_inventaris_peralatan`
--
ALTER TABLE `mutasi_inventaris_peralatan`
  ADD CONSTRAINT `FK_mutasi_inventaris_peralatan` FOREIGN KEY (`id_inventaris_peralatan`) REFERENCES `inventaris_peralatan` (`id`);

--
-- Ketidakleluasaan untuk tabel `mutasi_inventaris_tanah`
--
ALTER TABLE `mutasi_inventaris_tanah`
  ADD CONSTRAINT `FK_mutasi_inventaris_tanah` FOREIGN KEY (`id_inventaris_tanah`) REFERENCES `inventaris_tanah` (`id`);

--
-- Ketidakleluasaan untuk tabel `setting_aplikasi_options`
--
ALTER TABLE `setting_aplikasi_options`
  ADD CONSTRAINT `id_setting_fk` FOREIGN KEY (`id_setting`) REFERENCES `setting_aplikasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `suplemen_terdata`
--
ALTER TABLE `suplemen_terdata`
  ADD CONSTRAINT `suplemen_terdata_ibfk_1` FOREIGN KEY (`id_suplemen`) REFERENCES `suplemen` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `syarat_surat`
--
ALTER TABLE `syarat_surat`
  ADD CONSTRAINT `id_surat_format` FOREIGN KEY (`surat_format_id`) REFERENCES `tweb_surat_format` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tweb_penduduk_mandiri`
--
ALTER TABLE `tweb_penduduk_mandiri`
  ADD CONSTRAINT `id_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
