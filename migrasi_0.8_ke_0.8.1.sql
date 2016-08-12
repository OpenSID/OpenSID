--
-- --------------------------------------------------------
--
-- Mengubah struktur database SID:
--   menambahkan kolom di tabel log_surat untuk menyimpan nama surat yang dikeluarkan

-- --------------------------------------------------------
--
-- Struktur untuk tabel `tweb_penduduk_mandiri`
--

ALTER TABLE `log_surat` ADD `nama_surat` varchar(100);

