--
-- --------------------------------------------------------
--
-- Mengubah struktur database SID:
--   1. menambahkan kolom di tabel log_bulanan
--   2. menambah tabel log_keluarga untuk mencatat perubahan keluarga
--   3. kembalikan view data_surats
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


DROP VIEW IF EXISTS data_surat;
DROP TABLE IF EXISTS data_surat;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `data_surat` AS select `u`.`id` AS `id`,`u`.`nama` AS `nama`,`x`.`nama` AS `sex`,`u`.`tempatlahir` AS `tempatlahir`,`u`.`tanggallahir` AS `tanggallahir`,(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) from `tweb_penduduk` where (`tweb_penduduk`.`id` = `u`.`id`)) AS `umur`,`w`.`nama` AS `status_kawin`,`f`.`nama` AS `warganegara`,`a`.`nama` AS `agama`,`d`.`nama` AS `pendidikan`,`j`.`nama` AS `pekerjaan`,`u`.`nik` AS `nik`,`c`.`rt` AS `rt`,`c`.`rw` AS `rw`,`c`.`dusun` AS `dusun`,`k`.`no_kk` AS `no_kk`,(select `tweb_penduduk`.`nama` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `k`.`nik_kepala`)) AS `kepala_kk` from ((((((((`tweb_penduduk` `u` left join `tweb_penduduk_sex` `x` on((`u`.`sex` = `x`.`id`))) left join `tweb_penduduk_kawin` `w` on((`u`.`status_kawin` = `w`.`id`))) left join `tweb_penduduk_agama` `a` on((`u`.`agama_id` = `a`.`id`))) left join `tweb_penduduk_pendidikan_kk` `d` on((`u`.`pendidikan_kk_id` = `d`.`id`))) left join `tweb_penduduk_pekerjaan` `j` on((`u`.`pekerjaan_id` = `j`.`id`))) left join `tweb_wil_clusterdesa` `c` on((`u`.`id_cluster` = `c`.`id`))) left join `tweb_keluarga` `k` on((`u`.`id_kk` = `k`.`id`))) left join `tweb_penduduk_warganegara` `f` on((`u`.`warganegara_id` = `f`.`id`)));

