Di rilis ini, versi 22.03-premium-beta01 menyediakan [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [untuk diisi] yang terus berkontribusi.

#### Penambahan Fitur
1. [#4995](https://github.com/OpenSID/OpenSID/issues/4995) Penyesuaian lokasi impor dan ekspor data penduduk dari modul database ke modul penduduk.
2. [#4997](https://github.com/OpenSID/OpenSID/issues/4997) Penyesuaian aturan impor data BIP.
3. [#4998](https://github.com/OpenSID/OpenSID/issues/4998) Penyesuaian ekspor data penduduk.
4. [#4323](https://github.com/OpenSID/OpenSID/issues/4323) Penambahan fitur kehadiran perangkat desa.
5. [#4996](https://github.com/OpenSID/OpenSID/issues/4996) Penyesuaian aturan impor penduduk.
6. [#5068](https://github.com/OpenSID/OpenSID/issues/5068) Penyesuaian kelengkapan info sistem minimal dan maksimal versi php dan mysql/mariadb yang dibutuhkan.


#### Perbaikan BUG

1. [#4985](https://github.com/OpenSID/OpenSID/issues/4985) Perbaiki url sitemap.xml.
2. [#4988](https://github.com/OpenSID/OpenSID/issues/4988) Perbaiki validasi log error yg ditampilkan pada modul Pengaturan > Info Sistem > Log.
3. [#4991](https://github.com/OpenSID/OpenSID/issues/4991) Perbaiki unduh data rumah tangga yang tidak tampil.
4. [#4984](https://github.com/OpenSID/OpenSID/issues/4984) Periksa dan perbaiki kasus ada tabel yg hilang autoincrement.
5. [#4993](https://github.com/OpenSID/OpenSID/issues/4993) Periksa dan perbaiki kasus ada field id_cluster yang terisi null pada tabel tweb_keluarga.
6. [#4978](https://github.com/OpenSID/OpenSID/issues/4978) Perbaiki tidak dapat menghapus ataupun mengubah format surat desa tambahan.
7. [#4986](https://github.com/OpenSID/OpenSID/issues/4986) Periksa dan perbaiki kasus username ganda pada tabel user.
8. [#4999](https://github.com/OpenSID/OpenSID/issues/4999) Perbaiki tidak ada notifikasi yang muncul saat menonaktifkan/mengaktifkan modul.
9. [#5007](https://github.com/OpenSID/OpenSID/issues/5007) Perbaiki error 500 ketika berkas widget tidak ditemukan.
10. [#5005](https://github.com/OpenSID/OpenSID/issues/5005) Perbaiki error illegal mix of collations pada saat impor data vaksin.
11. [#5009](https://github.com/OpenSID/OpenSID/issues/5009) Perbaiki validasi tambah dan ubah data pada modul Pemetaan > Pengaturan Peta > Area.
12. [#5018](https://github.com/OpenSID/OpenSID/issues/5018) Perbaiki semua tabel dengan collation yang bukan utf8_general_ci.
13. [#5008](https://github.com/OpenSID/OpenSID/issues/5008) Perbaiki pop up rincian data wilayan (statistik, pengurus, dll) tidak tampil kosong pada tambah/ubah data peta.
14. [#5021](https://github.com/OpenSID/OpenSID/issues/5021) Perbaiki ekspor data suplemen tidak mengambil semua data yang ada.
15. [#5015](https://github.com/OpenSID/OpenSID/issues/5015) Perbaiki nama hari di surat keterangan kematian yang tidak sesuai.
16. [#5020](https://github.com/OpenSID/OpenSID/issues/5020) Periksa dan perbaiki kasus no_kk ganda pada tabel tweb_keluarga.
17. [#5029](https://github.com/OpenSID/OpenSID/issues/5029) Perbaiki nama pada tempat tanda tangan kepala desa pada form lampiran surat keterangan pindah penduduk bukan nama kepala desa yang dipilih.
18. Perbaiki error jika url yg diakses adalah error code 404.
19. [#4836](https://github.com/OpenSID/OpenSID/issues/4836) Perbaiki status perkawinan "KAWIN TERCATAT SEMUA".
20. [#5036](https://github.com/OpenSID/OpenSID/issues/5036) Perbaiki notifikasi kosongkan lokasi peta tidak berfungsi.
21. [#5030](https://github.com/OpenSID/OpenSID/issues/5030) Periksa dan perbaiki tabel referensi persil dan inventaris kosong.
22. [#5037](https://github.com/OpenSID/OpenSID/issues/5037) Perbaiki data lengenda pada peta agar tidak menutupi tombol ubah.
23. [#5044](https://github.com/OpenSID/OpenSID/issues/5044) Perbaiki ukuran tampilan struktur organisasi pemerintah desa.
24. [#5053](https://github.com/OpenSID/OpenSID/issues/5053) Perbaiki judul grafik keuangan pada peta tidak sesuai dengan yang ditampilkan.
25. [#5049](https://github.com/OpenSID/OpenSID/issues/5049) Perbaiki akses modul vaksinasi yang lambat.
26. [#5054](https://github.com/OpenSID/OpenSID/issues/5054) Perbaiki cetak peta pada web tidak memunculkan nama desa dan arah mata angin.
27. [#5055](https://github.com/OpenSID/OpenSID/issues/5055) Perbaiki pencarian nama non-warga di arsip layanan surat.
28. [#5065](https://github.com/OpenSID/OpenSID/issues/5065) Perbaiki hapus surat desa agar tidak menghapus keseluruhan surat desa yang ada.
29. [#5022](https://github.com/OpenSID/OpenSID/issues/5022) Perbaiki jenis peraturan desa setelah diubah selalu berubah jadi angka 1 pada Bumindes-> Buku Peraturan Desa.
30. [#5066](https://github.com/OpenSID/OpenSID/issues/5066) Perbaiki validasi pembuatan surat baru.
31. [#5084](https://github.com/OpenSID/OpenSID/issues/5084) Perbaiki error console pada daftar arsip / permohonan surat layanan mandiri.
32. [#5083](https://github.com/OpenSID/OpenSID/issues/5083) Perbaiki error console saat masuk layanan mandiri.
33. [#5086](https://github.com/OpenSID/OpenSID/issues/5086) Perbaiki mode pemeliharaan untuk pilihan web non-aktif sama sekali.
34. [#5039](https://github.com/OpenSID/OpenSID/issues/5039) Perbaiki collation ketika restore database.
35. [#5071](https://github.com/OpenSID/OpenSID/issues/5071) Perbaiki penamaan kategori umur di bawah 1 tahun menjadi 0 s/d 1 tahun.
36. [#5099](https://github.com/OpenSID/OpenSID/issues/5099) Perbaiki surat desa tidak tampil saat cetak surat setelah mengubah nama surat.
37. [#5100](https://github.com/OpenSID/OpenSID/issues/5100) Perbaiki data anggota kelompok pada web tidak tampil.


#### Perubahan Teknis

1. [#5017](https://github.com/OpenSID/OpenSID/issues/5017) Nonaktifkan menu Buku Administrasi Desa > Buku Keuangan.
2. Tambahkan docblock property pada MY_Controller.php dan MY_Model.php.
3. Hapus $this->json_output() dan ganti dengan helper json() mengurangi duplikasi.
4. Gunakan view blade untuk halaman periksa database.
5. Gunakan helper currentVersion() untuk pengecekan.
6. Penambahan informasi forum OpenDesa pada home.
