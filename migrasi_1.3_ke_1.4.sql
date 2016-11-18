--
-- --------------------------------------------------------
--
-- Mengubah struktur database SID:
--   1. menambahkan jenis user di tabel user_grup
--
-- --------------------------------------------------------
--

INSERT INTO user_grup (id, nama) VALUES (4, 'Kontributor')
ON DUPLICATE KEY UPDATE
  id = VALUES(id),
  nama = VALUES(nama);
