--
-- --------------------------------------------------------
--
-- Mengubah struktur database SID:
--   menambahkan kolom di tabel log_penduduk untuk menyimpan catatan peristiwa ubah status dasar

-- --------------------------------------------------------
--

ALTER TABLE `log_penduduk` ADD `catatan` text;
