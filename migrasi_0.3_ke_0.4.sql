--
-- Database : `sid304`
--
-- --------------------------------------------------------
--
-- Mengubah struktur database SID:
--   dari struktur yang dipergunakan sid304-jms v0.3x (unduhan SID 21 Mei 2016)
--   ke struktur yang dipergunakan sid304-jms v0.4 (unduhan SID 30 Mei 2016)
--
-- PERHATIAN: Struktur tabel dalam script ini disusun berdasarkan pemeriksaan source code SID 30 Mei 2016
-- dan belum tentu sesuai dengan yang di-release oleh CRI. Script migrasi ini akan diperbaiki apabila kami
-- mendapat informasi lanjut.

-- --------------------------------------------------------
--
-- Struktur untuk tabel `tweb_penduduk_mandiri`
--

CREATE TABLE IF NOT EXISTS `tweb_penduduk_mandiri` (
  `nik` decimal(16,0) NOT NULL,
  `pin` char(32) NOT NULL,
  `last_login` datetime,
  `tanggal_buat` date NOT NULL,
  PRIMARY KEY  (`nik`)
);

--
-- Struktur untuk tabel `program`
--
CREATE TABLE IF NOT EXISTS `program` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `sasaran` tinyint,
  `ndesc` varchar(200),
  `sdate` date NOT NULL,
  `edate` date NOT NULL,
  `userid` mediumint NOT NULL,
  `status` int(10),
  PRIMARY KEY  (`id`)
);

--
-- Struktur untuk tabel `program_peserta`
--
CREATE TABLE IF NOT EXISTS `program_peserta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `peserta` decimal(16,0) NOT NULL,
  `program_id` int NOT NULL,
  `sasaran` tinyint,
  PRIMARY KEY  (`id`)
);

--
-- Struktur untuk tabel `data_persil`
--

CREATE TABLE IF NOT EXISTS `data_persil` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nik` decimal(16,0) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `persil_jenis_id` int NOT NULL,
  `id_clusterdesa` int NOT NULL,
  `luas` int,
  `no_sppt_pbb` int,
  `kelas` varchar(50),
  `persil_peruntukan_id` int NOT NULL,
  `alamat_ext` varchar(100),
  `userID` mediumint,
  PRIMARY KEY  (`id`)
);

--
-- Struktur untuk tabel `data_persil_peruntukan`
--

CREATE TABLE IF NOT EXISTS `data_persil_peruntukan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `ndesc` varchar(200),
  PRIMARY KEY  (`id`)
);

--
-- Struktur untuk tabel `data_persil_jenis`
--

CREATE TABLE IF NOT EXISTS `data_persil_jenis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `ndesc` varchar(200),
  PRIMARY KEY  (`id`)
);
