Di rilis ini, versi 22.01-premium menyediakan Layanan lapor/keluhan/pengaduan bagi warga/umum. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada @andifahruddinakas, @agungsugiarto, @FirlianiF, @scqolbu24, @FaisyalRachman dan @apidong yang terus berkontribusi. Terima kasih pula pada @totoprayogo1916 dan @raamaj yang baru mulai berkontribusi.

#### Penambahan Fitur

1. [#4556](https://github.com/OpenSID/OpenSID/issues/4556) Tambahkan akun telegram ke data penduduk.
2. [#4518](https://github.com/OpenSID/OpenSID/issues/4518) Tambahkan lampiran surat keterangan pindah datang penduduk F-1.27, F-1.31, F-1.39.
3. [#4226](https://github.com/OpenSID/OpenSID/issues/4226) Perbaharui versi terbaru blanko pindah datang F.1.03.
4. [#4553](https://github.com/OpenSID/OpenSID/issues/4553) Tampilkan lapak di layanan mandiri/anjungan.
5. [#4506](https://github.com/OpenSID/OpenSID/issues/4506) Tambah tampilan slide gambar/video di anjungan pada saat tidak digunakan.
6. [#3947](https://github.com/OpenSID/OpenSID/issues/3947) Saat masuk layanan mandiri diarahkan ke halaman buat surat (standar) atau ke halaman daftar pesan masuk jika terdapat pesan masuk yg belum dibaca.
7. [#4507](https://github.com/OpenSID/OpenSID/issues/4507) Bisa atur jenis garis dan ketebalan tipe garis di pengaturan peta.
8. [#4558](https://github.com/OpenSID/OpenSID/issues/4558) Verifikasi akun Telegram di layanan mandiri.
9. [#4561](https://github.com/OpenSID/OpenSID/issues/4561) Notifikasi Sesuaikan pemberitahuan adanya rilis baru.
10. [#3876](https://github.com/OpenSID/OpenSID/issues/3876) Tambahkan "pintasan" pengaturan / informasi untuk surat layanan mandiri dan syarat surat.
11. [#4584](https://github.com/OpenSID/OpenSID/issues/4584) Laporkan jika exec tidak tersedia pada waktu mencetak PDF.
12. [#3663](https://github.com/OpenSID/OpenSID/issues/3663) Penambahan Fitur kirim PIN baru melalui telegram ke warga.
13. [#4411](https://github.com/OpenSID/OpenSID/issues/4411) Tambah aksi lihat dokumen pada Buku Administrasi Umum (Peraturan Desa, Keputusan Kepala Desa, Surat Masuk).
14. [#4622](https://github.com/OpenSID/OpenSID/issues/4622) Tidak boleh hapus data penduduk kalau sudah menggantikan data contoh awal atau data penduduk sudah dinyatakan lengkap.
15. [#512](https://github.com/OpenSID/OpenSID/issues/512) Layanan lapor/keluhan/pengaduan bagi warga/umum.
16. [#4180](https://github.com/OpenSID/OpenSID/issues/4180) Buku inventaris hasil-hasil pembangunan di Buku Administrasi Pembangunan.
17. [#4610](https://github.com/OpenSID/OpenSID/issues/4610) Pendataan warga penerima vaksin.
18. [#4497](https://github.com/OpenSID/OpenSID/issues/4497) Surat keterangan nikah & form F2.12 untuk warga bukan muslim.
19. [#4557](https://github.com/OpenSID/OpenSID/issues/4557) Lupa/reset PIN dengan telegram.

#### Perbaikan BUG

1. [#4560](https://github.com/OpenSID/OpenSID/issues/4560) Perbaiki migrasi untuk nik dan no_kk sementara kadang gagal untuk kasus tertentu.
2. [#463](https://github.com/OpenSID/premium/issues/463) Perbaiki devtoolbar agar tidak menggangu proses compress saat cetak surat.
3. [#4565](https://github.com/OpenSID/OpenSID/issues/4565) Perbaiki foto pembangunan terlihat besar.
4. [#4567](https://github.com/OpenSID/OpenSID/issues/4567) Perbaiki migrasi slug pembangunan tidak berjalan keseluruhan.
5. [#4566](https://github.com/OpenSID/OpenSID/issues/4566) Perbaiki login ektp dan persuratan di layanan mandiri.
6. [#4554](https://github.com/OpenSID/OpenSID/issues/4554) Sesuaikan view verifikasi surat berdasarkan tema yang digunakan.
7. [#4568](https://github.com/OpenSID/OpenSID/issues/4568) Perbaiki gagal saat mengakses modul rekapitulasi jumlah penduduk.
8. [#4549](https://github.com/OpenSID/OpenSID/issues/4549) Perbaiki gagal di penduduk penerima bantuan, tidak kompatibel.
9. [#4570](https://github.com/OpenSID/OpenSID/issues/4570) Perbaiki gagal tambah / ubah kader dengan menggunakan .htaccess.
10. [#4378](https://github.com/OpenSID/OpenSID/issues/4378) Penyesuaian daftar surat ubahan desa secara otomatis.
11. [#4392](https://github.com/OpenSID/OpenSID/issues/4392) Perbaiki status perkwinan modul penduduk dan keluarga.
12. [#4564](https://github.com/OpenSID/OpenSID/issues/4564) Perbaiki pengaturan peta yang tidak dapat menyimpan warna.
13. [#4585](https://github.com/OpenSID/OpenSID/issues/4585) Perbaiki tampilkan notifikasi gagal, jika widget gagal disimpan beserta penyebabnya.
14. [#4598](https://github.com/OpenSID/OpenSID/issues/4598) Perbaiki gagal impor SIAK.
15. [#4542](https://github.com/OpenSID/OpenSID/issues/4542) Perbaiki cetak surat kelahiran.
16. [#4576](https://github.com/OpenSID/OpenSID/issues/4576) Perbaiki grup pengguna.
17. [#2767](https://github.com/OpenSID/OpenSID/issues/2767) Perbaiki menu sidebar,collapse ketika kita pilih menu.
18. [#4596](https://github.com/OpenSID/OpenSID/issues/4596) Perbaiki suplemen tidak dapat impor data suplemen sasaran keluarga
19. [#4595](https://github.com/OpenSID/OpenSID/issues/4595) Perbaiki ubah panjang value pada kolom tag_id_card.
20. [#4593](https://github.com/OpenSID/OpenSID/issues/4593) Perbaiki gagal saat menyimpan peta, dengan data yg tidak lengkap.
21. [#4574](https://github.com/OpenSID/OpenSID/issues/4574) Tampilkan data artikel sesuai dengan pengaturan Grup dan Pengguna.
22. [#4600](https://github.com/OpenSID/OpenSID/issues/4600) Perbaiki import data penduduk berulang.
23. [#4602](https://github.com/OpenSID/OpenSID/issues/4602) Tampilkan status desa terdaftar pada layanan pelanggan.
24. [#4611](https://github.com/OpenSID/OpenSID/issues/4611) Perbaiki nilai satuan laporan hasil analisis.
25. [#4623](https://github.com/OpenSID/OpenSID/issues/4623) Perbaiki jumlah data suplemen yang tampil di halaman web tidak sesuai.
26. [#4642](https://github.com/OpenSID/OpenSID/issues/4642) Perbaiki ketika edit data penduduk terjadi error.
27. [#4663](https://github.com/OpenSID/OpenSID/issues/4663) Perbaiki ketika edit data penduduk terjadi error telegram.
28. [#4673](https://github.com/OpenSID/OpenSID/issues/4673) Perbaiki sesi grup hanya beberapa menu yang tampil.
29. [#4645](https://github.com/OpenSID/OpenSID/issues/4645) Menampilkan Pembangunan tahun terbaru pada bagian atas.
30. [#4668](https://github.com/OpenSID/OpenSID/issues/4668) Perbaiki pada saat verifikasi telegram dengan akun yang sama (Duplicate entry).
31. [#4674](https://github.com/OpenSID/OpenSID/issues/4674) Perbaiki jangan tampilkan pesan NIK jika tidak ditemukan.
32. [#4641](https://github.com/OpenSID/OpenSID/issues/4641) Perbaikan modul vaksinasi.
33. [#4636](https://github.com/OpenSID/OpenSID/issues/4636) Perbaiki data tidak muncul di hasil unduhan surat keterangan nikah untuk warga non muslim (PDF & RTF).
34. [#4676](https://github.com/OpenSID/OpenSID/issues/4676) Perbaiki penduduk mati masih muncul pada data rumah tangga.
35. [#4690](https://github.com/OpenSID/OpenSID/issues/4690) Perbaiki izinkan penggunaan huruf dalam klasifikasi surat.
36. [#4700](https://github.com/OpenSID/OpenSID/issues/4700) Perbaiki pencarian dan pengurutan di modul buku kader tidak berjalan.
37. [#4675](https://github.com/OpenSID/OpenSID/issues/4675) Perbaiki list pamong tidak muncul saat cetak laporan hasil klasifikasi analisis.
38. [#4631](https://github.com/OpenSID/OpenSID/issues/4631) Perbaiki impor siskuedes kolom tidak ditemukan.
39. [#4704](https://github.com/OpenSID/OpenSID/issues/4704) Perbaiki error impor struktur database awal.
40. [#4646](https://github.com/OpenSID/OpenSID/issues/4646) Perbaiki duplikat pendataan tidak berfungsi.
41. [#4702](https://github.com/OpenSID/OpenSID/issues/4702) Perbaiki dan sediakan pengaturan suara video login mandiri.
42. [#4714](https://github.com/OpenSID/OpenSID/issues/4714) Perbaiki tidak bisa menghapus artikel.
43. [#4731](https://github.com/OpenSID/OpenSID/issues/4731) Perbaiki Latar Website pada tema tidak berganti.
44. [#4745](https://github.com/OpenSID/OpenSID/issues/4745) Perbaiki Salah penyebutan "Dusun" pada laporan rekap vaksin.
46. [#4746](https://github.com/OpenSID/OpenSID/issues/4746) Perbaiki Nomor dan Tanggal Akta Nikah aktif ketika status perkawinan belum kawin.
47. [#4744](https://github.com/OpenSID/OpenSID/issues/4744) Perbaiki Sesuaikan Sebutan Desa pada Notifikasi Layanan Mandiri.
48. [#4764](https://github.com/OpenSID/OpenSID/issues/4764) Perbaiki Paginasi pada menu Buku Rekapitulasi Jumlah Penduduk tidak bekerja normal.
49. [#4760](https://github.com/OpenSID/OpenSID/issues/4760) Perbaiki Link/Url "Home" pada breadcrumb salah.
50. [#4768](https://github.com/OpenSID/OpenSID/issues/4768) Perbaiki Nama desa tidak tampil dalam hasil cetak/unduh di bagian tanda tangan Wilayah Administratif.
51. [#4718](https://github.com/OpenSID/OpenSID/issues/4718) Perbaiki paginasi artikel kategori artikel.
52. [#4769](https://github.com/OpenSID/OpenSID/issues/4769) Perbaiki validasi form dan modal input agar tidak duplikasi.
53. [#4629](https://github.com/OpenSID/OpenSID/issues/4629) Perbaiki link sembarang bisa diinputkan di sosmed.
54. [#4725](https://github.com/OpenSID/OpenSID/issues/4725) Perbaiki form f1.08 (pindah pergi) surat keterangan pindah penduduk.
55. [#4765](https://github.com/OpenSID/OpenSID/issues/4765) Perbaiki pendidikan di buku kader pemberdayaan tidak sesuai dengan pendidikan di data penduduk.
56. [#4748](https://github.com/OpenSID/OpenSID/issues/4748) Perbaiki gagal simpan penduduk baru untuk usia 17+.
57. [#4735](https://github.com/OpenSID/OpenSID/issues/4735) Perbaiki rincian anggaran pembangunan melebihi total anggaran.
58. [#4771](https://github.com/OpenSID/OpenSID/issues/4771) Perbaiki paginasi halaman website pembangunan terjadi error/kosong apabila list view yang ditampilkan lebih dari 10 item.
59. [#4759](https://github.com/OpenSID/OpenSID/issues/4759) Perbaiki kolom "ditandatangani oleh" dalam hasil cetak dan unduh arsip surat kosong.
60. [#4751](https://github.com/OpenSID/OpenSID/issues/4751) Perbaiki lampiran F.2.01 memunculkan camat.
61. [#4723](https://github.com/OpenSID/OpenSID/issues/4723) Perbaiki kode warna bermasalah.
62. [#4754](https://github.com/OpenSID/OpenSID/issues/4754) Perbaiki tidak bisa menggunakan login ektp.
63. [#4772](https://github.com/OpenSID/OpenSID/issues/4772) Perbaiki status kepala rumah tangga jika sudah meninggal.
64. [#4775](https://github.com/OpenSID/OpenSID/issues/4775) Perbaiki pengelompokan rumah tangga tersimpan record data kosong yang ikut dihitung sebagai rekap jumlah data rumah tangga.
65. [#4774](https://github.com/OpenSID/OpenSID/issues/4774) Perbaiki nilai kosong pada saat kosongkan database (impor analisis).
66. [#4664](https://github.com/OpenSID/OpenSID/issues/4664) Perbaiki wajib identitas menyesuaikan umur dan status perkawinan.


#### Perubahan Teknis

1. [#4583](https://github.com/OpenSID/OpenSID/issues/4583) Perbaiki .htaccess untuk file .zip dan .rar / Backup folder desa lewat hosting.
2. [#4588](https://github.com/OpenSID/OpenSID/issues/4588) Loloskan pemeriksaan token di setting demo.
3. Pindahkan style css kedalam HEAD di login Admin.
4. Pisahkan Konfigurasi DevToolBar.
5. [#4571](https://github.com/OpenSID/OpenSID/issues/4571) Pulihkan website demo menggunakan penjadwalan (mis. Cron Job, Cron Tab, atau lainnya).
6. [#4661](https://github.com/OpenSID/OpenSID/issues/4661) Perbaiki jQuery di panggil 2 kali.
7. [#4639](https://github.com/OpenSID/OpenSID/pull/4639) Penambahan single quote ( ' ) pada opsi order_by.
8. Hapus folder/file logs setiap kali menjalankan job.
9. Perbaikan link assets menggunakan helper asset().
10. Tambahkan browser Edge yang berbasis Chromium.
11. Jangan kirim notifikasi jika token bot telegram kosong.