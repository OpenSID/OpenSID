--
-- --------------------------------------------------------
--
-- Mengubah struktur database SID:
--   1. menambahkan kolom di tabel tweb_keluarga
--
-- --------------------------------------------------------
--

ALTER TABLE tweb_keluarga
  ADD COLUMN alamat varchar(200);
