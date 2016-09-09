--
-- --------------------------------------------------------
--
-- Mengubah struktur database SID:
--   1. menambahkan unique index di tabel tweb_surat_format
--   2. menambah dan mengubah baris surat di tabel tweb_surat_format untuk menyesuaikan
--     dengan perubahan yang dilakukan untuk membuat template surat Export Doc
--   3. menambah kolom tgl_cetak_kk di tabel tweb_keluarga
-- --------------------------------------------------------
--

CREATE UNIQUE INDEX migrasi_0_10_url_surat ON tweb_surat_format (url_surat);

INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`) VALUES
(1, 'Keterangan Pengantar', 'surat_ket_pengantar', 'S-01'),
(2, 'Keterangan Penduduk', 'surat_ket_penduduk', 'S-02'),
(3, 'Biodata Penduduk', 'surat_bio_penduduk', 'S-03'),
(5, 'Keterangan Pindah Penduduk', 'surat_ket_pindah_penduduk', 'S-04'),
(6, 'Keterangan Jual Beli', 'surat_ket_jual_beli', 'S-05'),
(7, 'Pengantar Pindah Antar Kabupaten/ Provinsi', 'surat_pindah_antar_kab_prov', 'S-06'),
(8, 'Pengantar Surat Keterangan Catatan Kepolisian', 'surat_ket_catatan_kriminal', 'S-07'),
(9, 'Keterangan KTP dalam Proses', 'surat_ket_ktp_dalam_proses', 'S-08'),
(10, 'Keterangan Beda Identitas', 'surat_ket_beda_nama', 'S-09'),
(11, 'Keterangan Bepergian / Jalan', 'surat_jalan', 'S-10'),
(12, 'Keterangan Kurang Mampu', 'surat_ket_kurang_mampu', 'S-11'),
(13, 'Pengantar Izin Keramaian', 'surat_izin_keramaian', 'S-12'),
(14, 'Pengantar Laporan Kehilangan', 'surat_ket_kehilangan', 'S-13'),
(15, 'Keterangan Usaha', 'surat_ket_usaha', 'S-14'),
(16, 'Keterangan JAMKESOS', 'surat_ket_jamkesos', 'S-15'),
(17, 'Keterangan Domisili Usaha', 'surat_ket_domisili_usaha', 'S-16'),
(18, 'Keterangan Kelahiran', 'surat_ket_kelahiran', 'S-17'),
(20, 'Permohonan Akta Lahir', 'surat_permohonan_akta', 'S-18'),
(21, 'Pernyataan Belum Memiliki Akta Lahir', 'surat_pernyataan_akta', 'S-19'),
(22, 'Permohonan Duplikat Kelahiran', 'surat_permohonan_duplikat_kelahiran', 'S-20'),
(24, 'Keterangan Kematian', 'surat_ket_kematian', 'S-21'),
(25, 'Keterangan Lahir Mati', 'surat_ket_lahir_mati', 'S-22'),
(26, 'Keterangan Untuk Nikah (N-1)', 'surat_ket_nikah', 'S-23'),
(27, 'Keterangan Asal Usul (N-2)', 'surat_ket_asalusul', 'S-24'),
(28, 'Persetujuan Mempelai (N-3)', 'surat_persetujuan_mempelai', 'S-25'),
(29, 'Keterangan Tentang Orang Tua (N-4)', 'surat_ket_orangtua', 'S-26'),
(30, 'Keterangan Izin Orang Tua(N-5)', 'surat_izin_orangtua', 'S-27'),
(31, 'Keterangan Kematian Suami/Istri(N-6)', 'surat_ket_kematian_suami_istri', 'S-28'),
(32, 'Pemberitahuan Kehendak Nikah (N-7)', 'surat_kehendak_nikah', 'S-29'),
(33, 'Keterangan Pergi Kawin', 'surat_ket_pergi_kawin', 'S-30'),
(34, 'Keterangan Wali', 'surat_ket_wali', 'S-31'),
(35, 'Keterangan Wali Hakim', 'surat_ket_wali_hakim', 'S-32'),
(36, 'Permohonan Duplikat Surat Nikah', 'surat_permohonan_duplikat_surat_nikah', 'S-33'),
(37, 'Permohonan Cerai', 'surat_permohonan_cerai', 'S-34'),
(38, 'Keterangan Pengantar Rujuk/Cerai', 'surat_ket_rujuk_cerai', 'S-35'),
(39, 'Ubah Sesuaikan', 'surat_ubah_sesuaikan', 'S-36')
ON DUPLICATE KEY UPDATE
  nama = VALUES(nama),
  url_surat = VALUES(url_surat),
  kode_surat = VALUES(kode_surat);

DROP INDEX migrasi_0_10_url_surat ON tweb_surat_format;

ALTER TABLE tweb_keluarga ADD tgl_cetak_kk datetime;


