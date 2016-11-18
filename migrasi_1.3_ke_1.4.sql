--
-- --------------------------------------------------------
--
-- Mengubah struktur database SID
--
-- --------------------------------------------------------
--

--  Menambahkan jenis user di tabel user_grup
INSERT INTO user_grup (id, nama) VALUES (4, 'Kontributor')
ON DUPLICATE KEY UPDATE
  id = VALUES(id),
  nama = VALUES(nama);

-- Buat tanggalperkawinan dan tanggalperceraian boleh NULL
ALTER TABLE tweb_penduduk CHANGE tanggalperkawinan tanggalperkawinan DATE NULL DEFAULT NULL;
ALTER TABLE tweb_penduduk CHANGE tanggalperceraian tanggalperceraian DATE NULL DEFAULT NULL;

-- Ubah tanggal menjadi NULL apabila 0000-00-00
UPDATE tweb_penduduk SET tanggalperkawinan=NULL WHERE tanggalperkawinan='0000-00-00';
UPDATE tweb_penduduk SET tanggalperceraian=NULL WHERE tanggalperceraian='0000-00-00';
