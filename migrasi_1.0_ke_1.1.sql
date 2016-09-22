--
-- --------------------------------------------------------
--
-- Mengubah struktur database SID:
--   1. menambahkan kolom di tabel log_bulanan
--   2. menambah tabel log_keluarga untuk mencatat perubahan keluarga
--
-- --------------------------------------------------------
--

ALTER TABLE log_bulanan
  ADD COLUMN kk_lk int(11),
  ADD COLUMN kk_pr int(11);

CREATE TABLE `log_keluarga` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_kk` int(11) NOT NULL,
  `kk_sex` tinyint(2) NOT NULL,
  `id_peristiwa` int(4) NOT NULL,
  `tgl_peristiwa` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_kk` (`id_kk`,`id_peristiwa`,`tgl_peristiwa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
