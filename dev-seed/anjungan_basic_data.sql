-- =============================================================================
-- Data dasar DEV untuk menguji modul Anjungan (kios Layanan Mandiri).
--
-- BUKAN untuk rilis produksi: folder `dev-seed/` di-`export-ignore` di
-- `.gitattributes`, jadi ikut ter-commit ke branch tetapi TIDAK masuk arsip
-- rilis (`git archive`). Untuk dipakai developer lain yang butuh data awal.
--
-- Menyisipkan: 1 perangkat Anjungan (kios), 1 wilayah + 1 keluarga + 3 warga,
-- 3 artikel terbit, 2 gambar galeri slider, dan mengeset dua setting Anjungan.
--
-- Idempoten: memakai ID tetap (rentang tinggi) + hapus-lalu-sisip, jadi aman
-- dijalankan berulang. Portabel lintas install: `config_id` diambil dinamis.
--
-- Jalankan manual (ganti nama DB sesuai install):
--   mysql -h127.0.0.1 -uroot opensid_umum_verify < dev-seed/anjungan_basic_data.sql
--   mysql -h127.0.0.1 -uroot opensid             < dev-seed/anjungan_basic_data.sql   # Premium
--
-- Lalu di browser aktifkan kios: buka DevTools di halaman .../layanan-mandiri/masuk →
--   localStorage.setItem('anjungan_uuid','11111111-1111-1111-1111-111111111111')
-- muat ulang → tombol ANJUNGAN muncul → buka .../anjungan-mandiri.
-- (Butuh entitlement aktif: situs mode development ATAU langganan Anjungan aktif.)
-- =============================================================================

SET NAMES utf8mb4 COLLATE utf8mb4_general_ci;   -- cocokkan collation kolom (hindari "illegal mix")

-- config_id desa aktif (diambil dinamis; di verify-Umum = 2, di banyak install = 1)
SET @cfg := (SELECT id FROM config ORDER BY id LIMIT 1);

-- ID tetap untuk baris seed (rentang tinggi agar tak bentrok data asli / auto_increment)
SET @dev_uuid := '11111111-1111-1111-1111-111111111111';
SET @anj_id   := 990001;
SET @wil_id   := 990001;
SET @kk_id    := 990001;
SET @p1 := 990001;
SET @p2 := 990002;
SET @p3 := 990003;
SET @art1 := 990001;
SET @art2 := 990002;
SET @art3 := 990003;
SET @gal1 := 990001;
SET @gal2 := 990002;
SET @album_id := 990001;   -- "parrent" galeri slider = nilai setting anjungan_slide

-- --------------------------------------------------------------------------
-- Bersihkan baris seed lama (idempoten). Urut: anak dulu, induk belakangan.
-- --------------------------------------------------------------------------
UPDATE tweb_keluarga SET nik_kepala = NULL WHERE id = @kk_id;
DELETE FROM anjungan             WHERE id = @anj_id OR uuid = @dev_uuid;
DELETE FROM tweb_penduduk        WHERE id IN (@p1, @p2, @p3);
DELETE FROM tweb_keluarga        WHERE id = @kk_id;
DELETE FROM tweb_wil_clusterdesa WHERE id = @wil_id;
DELETE FROM artikel              WHERE id IN (@art1, @art2, @art3);
DELETE FROM gambar_gallery       WHERE id IN (@gal1, @gal2);

-- --------------------------------------------------------------------------
-- 1) Perangkat Anjungan (kios). tipe '[1]' = ANJUNGAN, status 1 = aktif.
--    uuid ini yang dicocokkan resolver kios (cek-anjungan-ajax / cookie).
-- --------------------------------------------------------------------------
INSERT INTO anjungan
  (id, uuid, ip_address, config_id, status, tipe, keyboard, orientasi_layar,
   permohonan_surat_tanpa_akun, keterangan, created_at, updated_at)
VALUES
  (@anj_id, @dev_uuid, '0.0.0.0', @cfg, 1, '[1]', 1, 1, 1,
   'Anjungan Dev (seed)', NOW(), NOW());

-- --------------------------------------------------------------------------
-- 2) Wilayah (1 dusun/RW/RT)
-- --------------------------------------------------------------------------
INSERT INTO tweb_wil_clusterdesa (id, config_id, dusun, rw, rt)
VALUES (@wil_id, @cfg, 'Dusun Contoh', '01', '01');

-- --------------------------------------------------------------------------
-- 3) Keluarga (kepala diisi setelah penduduk dibuat)
-- --------------------------------------------------------------------------
INSERT INTO tweb_keluarga (id, config_id, no_kk, id_cluster, alamat, nik_kepala)
VALUES (@kk_id, @cfg, '3200000000000001', @wil_id, 'Jl. Contoh No. 1', NULL);

-- --------------------------------------------------------------------------
-- 4) Penduduk (3 warga). agama/pekerjaan/pendidikan pakai id referensi awal (1).
--    golongan_darah_id '13' = Tidak Tahu. status_dasar 1 = hidup, status 1 = tetap.
-- --------------------------------------------------------------------------
INSERT INTO tweb_penduduk
  (id, config_id, nama, nik, sex, tempatlahir, tanggallahir, agama_id,
   pekerjaan_id, pendidikan_kk_id, golongan_darah_id, kk_level, status_kawin,
   id_kk, id_cluster, status_dasar, status, alamat_sekarang)
VALUES
  (@p1, @cfg, 'Budi Santoso', '3200000000000001', 1, 'Bandung', '1980-01-15',
   1, 1, 1, '13', 1, 2, @kk_id, @wil_id, 1, 1, 'Jl. Contoh No. 1'),
  (@p2, @cfg, 'Siti Aminah',  '3200000000000002', 2, 'Bandung', '1985-06-20',
   1, 1, 1, '13', 2, 2, @kk_id, @wil_id, 1, 1, 'Jl. Contoh No. 1'),
  (@p3, @cfg, 'Andi Wijaya',  '3200000000000003', 1, 'Bandung', '2005-03-10',
   1, 1, 1, '13', 3, 1, @kk_id, @wil_id, 1, 1, 'Jl. Contoh No. 1');

-- Tetapkan kepala keluarga (kk_level 1)
UPDATE tweb_keluarga SET nik_kepala = @p1 WHERE id = @kk_id;

-- --------------------------------------------------------------------------
-- 5) Artikel (3 terbit). Arsip kios menampilkan enabled=1 & tgl_upload < now.
-- --------------------------------------------------------------------------
SET @kat := (SELECT id FROM kategori WHERE tipe = 1 ORDER BY id LIMIT 1);
INSERT INTO artikel
  (id, config_id, judul, slug, isi, enabled, headline, slider, tgl_upload,
   id_kategori, tipe, jenis_widget, boleh_komentar)
VALUES
  (@art1, @cfg, 'Selamat Datang di Anjungan Desa', 'selamat-datang-anjungan-desa',
   '<p>Contoh artikel untuk kios Anjungan.</p>', 1, 1, 0, NOW() - INTERVAL 3 DAY,
   @kat, 'artikel', 3, 1),
  (@art2, @cfg, 'Layanan Mandiri Desa', 'layanan-mandiri-desa',
   '<p>Ajukan surat lewat Anjungan Mandiri.</p>', 1, 0, 0, NOW() - INTERVAL 2 DAY,
   @kat, 'artikel', 3, 1),
  (@art3, @cfg, 'Jam Pelayanan Kantor Desa', 'jam-pelayanan-kantor-desa',
   '<p>Senin - Jumat, 08.00 - 15.00.</p>', 1, 0, 0, NOW() - INTERVAL 1 DAY,
   @kat, 'artikel', 3, 1);

-- --------------------------------------------------------------------------
-- 6) Galeri slider + setting Anjungan.
--    gambar 'kosong.jpg' = placeholder (gambar mungkin tak ada → hanya bingkai).
-- --------------------------------------------------------------------------
INSERT INTO gambar_gallery
  (id, config_id, nama, gambar, parrent, jenis, enabled, tgl_upload, slider)
VALUES
  (@gal1, @cfg, 'Slide 1', 'kosong.jpg', @album_id, 1, 1, NOW(), 1),
  (@gal2, @cfg, 'Slide 2', 'kosong.jpg', @album_id, 1, 1, NOW(), 1);

UPDATE setting_aplikasi SET value = @album_id
  WHERE `key` = 'anjungan_slide';
UPDATE setting_aplikasi SET value = 'Selamat datang di Anjungan Mandiri Desa — silakan gunakan layanan mandiri.'
  WHERE `key` = 'anjungan_teks_berjalan';

-- Selesai. Verifikasi cepat: SELECT dari anjungan / tweb_penduduk / artikel di bawah.
SELECT
  (SELECT COUNT(*) FROM anjungan      WHERE uuid = @dev_uuid) AS kios,
  (SELECT COUNT(*) FROM tweb_penduduk WHERE id IN (@p1,@p2,@p3)) AS warga,
  (SELECT COUNT(*) FROM artikel       WHERE id IN (@art1,@art2,@art3)) AS artikel,
  (SELECT COUNT(*) FROM gambar_gallery WHERE id IN (@gal1,@gal2)) AS galeri;
