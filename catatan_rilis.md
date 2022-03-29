Di rilis ini, versi 22.03-premium-beta01 menyediakan [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [untuk diisi] yang terus berkontribusi.

#### Penambahan Fitur
1. [#4995](https://github.com/OpenSID/OpenSID/issues/4995) Pindahkan impor dan expor data penduduk ke modul penduduk.
2. [#4997](https://github.com/OpenSID/OpenSID/issues/4997) Penyesuaian aturan impor data BIP.
3. [#4998](https://github.com/OpenSID/OpenSID/issues/4998) Penyesuaian expor data penduduk.
4. [#4323](https://github.com/OpenSID/OpenSID/issues/4323) Penambahan fitur absensi perangkat desa.
5. [#4996](https://github.com/OpenSID/OpenSID/issues/4996) Penyesuaian aturan impor penduduk.
6. [#5068](https://github.com/OpenSID/OpenSID/issues/5068) Lengkapi info sistem minimal dan maksimal versi php dan mysql/mariadb yang dibutuhkan.


#### Perbaikan BUG

1. [#4985](https://github.com/OpenSID/OpenSID/issues/4985) Perbaiki url sitemap.xml.
2. [#4988](https://github.com/OpenSID/OpenSID/issues/4988) Perbaiki validasi log error yg ditampilkan pada modul Pengaturan > Info Sistem > Log.
3. [#4991](https://github.com/OpenSID/OpenSID/issues/4991) Perbaiki unduh data rumah tangga yang tidak tampil.
4. [#4984](https://github.com/OpenSID/OpenSID/issues/4984) Periksa dan perbaiki kasus ada tabel yg hilang autoincrement.
5. [#4993](https://github.com/OpenSID/OpenSID/issues/4993) Periksa dan perbaiki kasus ada field id_cluster yang terisi null pada tabel tweb_keluarga.
6. [#4978](https://github.com/OpenSID/OpenSID/issues/4978) Perbaiki tidak dapat menghapus ataupun mengubah format surat desa tambahan.
7. [#4986](https://github.com/OpenSID/OpenSID/issues/4986) Periksa dan perbaiki kasus username ganda pada tabel user.
8. [#4999](https://github.com/OpenSID/OpenSID/issues/4999) Perbaiki notifikasi menonaktifkan/mengaktifkan dan kembali ke default penggunaa server pada modul.
9. [#5007](https://github.com/OpenSID/OpenSID/issues/5007) Nonaktifkan widget jika file widget tidak ditemukan.
10. [#5005](https://github.com/OpenSID/OpenSID/issues/5005) Perbaiki impor data vaksin (Illegal mix of collations).
11. [#5009](https://github.com/OpenSID/OpenSID/issues/5009) Perbaiki validasi tamabah dan ubah data pada modul Pemetaan > Pengaturan Peta > Area.
12. [#5018](https://github.com/OpenSID/OpenSID/issues/5018) Periksa dan perbaiki semua tabel dengan collation bukan utf8_general_ci.
13. [#5008](https://github.com/OpenSID/OpenSID/issues/5008) Perbaiki popup rincian data wilayan(statistik, pengurus, dll) tidak tampil kosong pada tambah/ubah data peta.
14. [#5021](https://github.com/OpenSID/OpenSID/issues/5021) Perbaiki export data suplemen tidak mengambil semua data yang ada.
15. [#5015](https://github.com/OpenSID/OpenSID/issues/5015) Perbaiki nama hari di surat keterangan kematian yang tidak sesuai.
16. [#5020](https://github.com/OpenSID/OpenSID/issues/5020) Periksa dan perbaiki kasus no_kk ganda pada tabel tweb_keluarga.
17. [#5029](https://github.com/OpenSID/OpenSID/issues/5029) Perbaiki Nama Pada Tempat Tanda Tangan Kepala Desa Pada Form Lampiran Surat Keterangan Pindah Penduduk Bukan Nama Kepala Desa Yang Dipilih.
18. Perbaiki error jika url yg diakses adalah error code 404.
19. [#4836](https://github.com/OpenSID/OpenSID/issues/4836) Perbaiki Status Perkawinan "KAWIN TERCATAT SEMUA".
20. [#5036](https://github.com/OpenSID/OpenSID/issues/5036) Perbaiki notifikasi kosongkan lokasi peta tidak berfungsi.
21. [#5030](https://github.com/OpenSID/OpenSID/issues/5030) Periksa dan perbaiki tabel referensi persil dan inventaris kosong.
22. [#5037](https://github.com/OpenSID/OpenSID/issues/5037) Perbaiki data lengenda pada peta agar tidak menutupi toolbar peta dengan menambahkan scroollbar.
23. [#5044](https://github.com/OpenSID/OpenSID/issues/5044) Perbaiki struktur organisasi pemerintah desa.
24. [#5053](https://github.com/OpenSID/OpenSID/issues/5053) Perbaiki judul grafik keuangan pada peta tidak sesuai dengan yang ditampilkan.
25. [#5049](https://github.com/OpenSID/OpenSID/issues/5049) Perbaiki akses modul vaksinasi yang lambat.
26. [#5054](https://github.com/OpenSID/OpenSID/issues/5054) Perbaiki cetak peta pada web tidak memunculkan nama desa dan arah mata angin.
27. [#5055](https://github.com/OpenSID/OpenSID/issues/5055) Perbaiki pencarian nama non warga di arsip layanan surat.
28. [#5065](https://github.com/OpenSID/OpenSID/issues/5065) Perbaiki hapus surat desa agar tidak menghapus keseluruhan surat desa yang ada.
29. [#5022](https://github.com/OpenSID/OpenSID/issues/5022) Perbaiki jenis peraturan desa pada Bumindes-> Buku Peraturan Desa.
30. [#5066](https://github.com/OpenSID/OpenSID/issues/5066) Perbaiki validasi pembuatan surat baru.
31. [#5084](https://github.com/OpenSID/OpenSID/issues/5084) Perbaiki error console pada daftar arsip / permohonan surat layanan mandiri.
32. [#5083](https://github.com/OpenSID/OpenSID/issues/5083) Perbaiki error console saat masuk layanan mandiri.
33. [#5086](https://github.com/OpenSID/OpenSID/issues/5086) Perbaiki maintenance mode untuk pilihan web non-aktif sama sekali.


#### Perubahan Teknis

1. [#5017](https://github.com/OpenSID/OpenSID/issues/5017) Nonaktifkan menu Buku Administrasi Desa > Buku Keuangan.
2. Tambahkan docblock property pada MY_Controller.php dan MY_Model.php.
3. Hapus $this->json_output() dan ganti dengan helper json() mengurangi duplikasi.
4. Gunakan view blade untuk halaman periksa database.
