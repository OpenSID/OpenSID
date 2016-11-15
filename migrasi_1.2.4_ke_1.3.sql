--
-- --------------------------------------------------------
--
-- Mengubah struktur database SID:
--   1. menambahkan kolom di tabel menu
--
-- --------------------------------------------------------
--

ALTER TABLE menu
  ADD COLUMN urut int(5);

