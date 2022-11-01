Di rilis ini, versi 22.11 menyediakan Penambahan fitur kehadiran perangkat desa. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada @totoprayogo1916 yang terus berkontribusi.

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
38. [#5110](https://github.com/OpenSID/OpenSID/issues/5110) Perbaiki gagal tambah dan ubah pengguna yang tidak diperuntukkan untuk rekam kehadiran serta validasi kolom unik untuk email, username, dan pamong_id.
39. [#5109](https://github.com/OpenSID/OpenSID/issues/5109) Perbaiki gagal ekspor data rekapitulasi kehadiran.
40. [#5119](https://github.com/OpenSID/OpenSID/issues/5119) Perbaiki rekam kehadiran perangkat desa menggunakan ektp yang terdaftar pada data penduduk serta validasi kolom unik untuk tag_id_card_pamong (tweb_desa_pamong) dengan tag_id_card (tweb_penduduk).
41. [#5112](https://github.com/OpenSID/OpenSID/issues/5112) Sesuaikan notifikasi rekam kehadiran untuk kehadiran masuk dan keluar yang tidak sesuai.
42. [#5123](https://github.com/OpenSID/OpenSID/issues/5123) Perbaiki gagal ekspor excel data penduduk.
43. [#5128](https://github.com/OpenSID/OpenSID/issues/5128) Perbaiki statistik bpjs ketenagakerjaan.
44. [#4950](https://github.com/OpenSID/OpenSID/issues/4950) Perbaiki tampilan tombol impor pada menu rumah tangga di tampilan handphone.
45. [#4163](https://github.com/OpenSID/OpenSID/issues/4163) Perbaiki hasil ekspor lampiran f.101, f.15, f16 di tanggal pembuatan surat.
46. [#5050](https://github.com/OpenSID/OpenSID/issues/5050) Perbaiki tampilan tabel statistik pada halaman peta yang bertumpuk.
47. [#5118](https://github.com/OpenSID/OpenSID/issues/5118) Perbaiki perhitungan dan grafik APBDes pada Pelaksanaan Pembiayaan.
48. [#5042](https://github.com/OpenSID/OpenSID/issues/5042) Perbaiki laporan ulang meengenai info layanan.
49. [#5137](https://github.com/OpenSID/OpenSID/issues/5137) Perbaiki notifikasi baris gagal ketika impor penduduk tidak muncul.
50. [#5140](https://github.com/OpenSID/OpenSID/issues/5140) Perbaiki latar login mandiri gambar tidak berubah.
51. [#5122](https://github.com/OpenSID/OpenSID/issues/5122) Perbaiki unduh excel pembangunan berantakan.
52. [#5145](https://github.com/OpenSID/OpenSID/issues/5145) Perbaiki artikel yang di awali dengan angka akan di arahkan ke artikel lain.
53. [#5113](https://github.com/OpenSID/OpenSID/issues/5113) Perbaiki konfirmasi jika kehadiran kurang 1 menit.
54. [#5135](https://github.com/OpenSID/OpenSID/issues/5135) Perbaiki menu tidak terpilih saat melakukan ubah data.
55. [#5158](https://github.com/OpenSID/OpenSID/issues/5158) Perbaiki impor data program bantuan.
56. [#5164](https://github.com/OpenSID/OpenSID/issues/5164) Perbaiki form penduduk jika tanggal pindah masuk dan lapor melampaui hari penginputan.
57. [#5165](https://github.com/OpenSID/OpenSID/issues/5165) Perbaiki urutan tanggal pada info sistem logs.
58. [#5166](https://github.com/OpenSID/OpenSID/issues/5166) Perbaiki ejaan sinkronisasi.
59. [#5153](https://github.com/OpenSID/OpenSID/issues/5153) Perbaiki pengaturan hapus spasi pada input ip.
60. [#5163](https://github.com/OpenSID/OpenSID/issues/5163) Perbaiki database contoh data awal mengikuti standar.
61. [#5173](https://github.com/OpenSID/OpenSID/issues/5173) Perbaiki perhitungan laporan keuangan impor dan manual tidak konsisten.
62. [#5187](https://github.com/OpenSID/OpenSID/issues/5187) Perbaiki kosongkan DB, jam kerja pada modul kehadiran juga terhapus.
63. [#5189](https://github.com/OpenSID/OpenSID/issues/5189) Perbaiki nilai setting tidak tampil saat periksa database.
64. [#5174](https://github.com/OpenSID/OpenSID/issues/5174) Perbaiki hapus fitur sebaran data covid di peta web.
65. [#5175](https://github.com/OpenSID/OpenSID/issues/5175) Perbaiki hapus fitur covid provinsi di web.
66. [#5192](https://github.com/OpenSID/OpenSID/issues/5192) Perbaiki notifikasi saat kembalikan ke pengaturan default server selalu error.
67. [#5181](https://github.com/OpenSID/OpenSID/issues/5181) Perbaiki sesi login gagal mengulangi waktu hitung tunggu untuk login kembali.
68. [#5183](https://github.com/OpenSID/OpenSID/issues/5183) Perbaiki notifikasi persetujuan penggunaan aplikasi OpenSID.
69. [#5194](https://github.com/OpenSID/OpenSID/issues/5194) Perbaiki error ketika klik bantuan pada layanan mandiri.
70. [#5106](https://github.com/OpenSID/OpenSID/issues/5106) Perbaiki deteksi MAC Address Gawai Kehadiran.
71. [#5197](https://github.com/OpenSID/OpenSID/issues/5197) Perbaiki tampilan produk lapak mandiri berantakan.


#### Perubahan Teknis

1. [#5017](https://github.com/OpenSID/OpenSID/issues/5017) Nonaktifkan menu Buku Administrasi Desa > Buku Keuangan.
2. Tambahkan docblock property pada MY_Controller.php dan MY_Model.php.
3. Hapus $this->json_output() dan ganti dengan helper json() mengurangi duplikasi.
4. Gunakan view blade untuk halaman periksa database.
5. Gunakan helper currentVersion() untuk pengecekan.
6. Penambahan informasi forum OpenDesa pada home.
7. Sesuaikan notifikasi rilis untuk rilis resmi.
8. Perbaiki kesalahan penulisan domain berputar.
9. Perbaiki helper setting.
10. [#5010](https://github.com/OpenSID/OpenSID/pull/5010), [#5011](https://github.com/OpenSID/OpenSID/pull/5011) Update htaccess.txt
11. Perbaiki penulisan GitHub.
12. Hapus tabel tidak digunakan.
13. Perbaiki Local arbitrary file code execution.
14. Helper sebutan desa.
15. Perbaiki job dan kosongkan DB.
16. Perbaiki duplikasi penamaan fuction grup akses.
17. Update index.php
