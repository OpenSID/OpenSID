Di rilis ini, versi 22.01-premium-rev01 [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [untuk diisi] yang terus berkontribusi. Terima kasih pula pada @Irvan1609 yang baru mulai berkontribusi.

#### Penambahan Fitur

1. [#1759](https://github.com/OpenSID/OpenSID/issues/1759) Laporan statistik kehamilan.
2. [#4635](https://github.com/OpenSID/OpenSID/issues/4635) Permintaan Penyediaan Tombol Hapus Dan Tanda Conteng Pilihan Hapus Data Pada Fitur Suplemen [Nama Modul].
3. [#1760](https://github.com/OpenSID/OpenSID/issues/1760) Pendaftaran layanan mandiri.
4. [#4521](https://github.com/OpenSID/OpenSID/issues/4521) Penulisan kalimat NIP/NIPD pada tangan tangan surat secara dinamis.
5. [#4353](https://github.com/OpenSID/OpenSID/issues/4353) Tambah fitur tag ktp di format impor data penduduk excel.
6. [#4350](https://github.com/OpenSID/OpenSID/issues/4350) Pencarian spesifik pada data penduduk.
7. [#4612](https://github.com/OpenSID/OpenSID/issues/4612) Tambah fitur verifikasi akun email.
8. [#4430](https://github.com/OpenSID/OpenSID/issues/4430) Penambahan Fitur Import Asuransi Kesehatan pada file FormatImporExcel.
9. [#4665](https://github.com/OpenSID/OpenSID/issues/4665) Sediakan Filter Bantuan aktif dan tidak aktif Pada Statistik Bantuan Keluarga.
10. [#4606](https://github.com/OpenSID/OpenSID/issues/4606) Tambah saring data rincian suplemen.
11. [#4710](https://github.com/OpenSID/OpenSID/issues/4710) Notifikasi kirim pin baru melalui email.
12. [#4670](https://github.com/OpenSID/OpenSID/issues/4670) Reset password untuk pengguna administrator.
13. [#4669](https://github.com/OpenSID/OpenSID/issues/4669) Verifikasi akun email untuk pengguna administrator.
14. [#4711](https://github.com/OpenSID/OpenSID/issues/4711) Kirim verifikasi melalui email pada pendaftaran layanan mandiri.

#### Perbaikan BUG

1. [#4642](https://github.com/OpenSID/OpenSID/issues/4642) Perbaiki ketika edit data penduduk terjadi error.
2. [#4663](https://github.com/OpenSID/OpenSID/issues/4663) Perbaiki ketika edit data penduduk terjadi error telegram.
3. [#4673](https://github.com/OpenSID/OpenSID/issues/4673) Perbaiki sesi grup hanya beberapa menu yang tampil.
4. [#4645](https://github.com/OpenSID/OpenSID/issues/4645) Menampilkan Pembangunan tahun terbaru pada bagian atas.
5. [#4668](https://github.com/OpenSID/OpenSID/issues/4668) Perbaiki pada saat verifikasi telegram dengan akun yang sama (Duplicate entry).
6. [#4674](https://github.com/OpenSID/OpenSID/issues/4674) Perbaiki jangan tampilkan pesan NIK jika tidak ditemukan.
7. [#4641](https://github.com/OpenSID/OpenSID/issues/4641) Perbaikan modul vaksinasi.
8. [#4636](https://github.com/OpenSID/OpenSID/issues/4636) Perbaiki data tidak muncul di hasil unduhan surat keterangan nikah untuk warga non muslim (PDF & RTF).
9. [#4676](https://github.com/OpenSID/OpenSID/issues/4676) Perbaiki penduduk mati masih muncul pada data rumah tangga.
10. [#4690](https://github.com/OpenSID/OpenSID/issues/4690) Perbaiki izinkan penggunaan huruf dalam klasifikasi surat.
11. [#4700](https://github.com/OpenSID/OpenSID/issues/4700) Perbaiki pencarian dan pengurutan di modul buku kader tidak berjalan.
12. [#4675](https://github.com/OpenSID/OpenSID/issues/4675) Perbaiki list pamong tidak muncul saat cetak laporan hasil klasifikasi analisis.
13. [#4631](https://github.com/OpenSID/OpenSID/issues/4631) Perbaiki impor siskuedes kolom tidak ditemukan.
14. [#4704](https://github.com/OpenSID/OpenSID/issues/4704) Perbaiki error impor struktur database awal.
15. [#4646](https://github.com/OpenSID/OpenSID/issues/4646) Perbaiki duplikat pendataan tidak berfungsi.
16. [#4664](https://github.com/OpenSID/OpenSID/issues/4664) Perbaiki wajib identitas menyesuaikan umur dan status perkawinan.
17. [#4702](https://github.com/OpenSID/OpenSID/issues/4702) Perbaiki dan sediakan pengaturan suara video login mandiri.
18. [#4714](https://github.com/OpenSID/OpenSID/issues/4714) Perbaiki tidak bisa menghapus artikel.
19. [#4731](https://github.com/OpenSID/OpenSID/issues/4731) Perbaiki Latar Website pada tema tidak berganti.
20. [#4745](https://github.com/OpenSID/OpenSID/issues/4745) Perbaiki Salah penyebutan "Dusun" pada laporan rekap vaksin.
21. [#4746](https://github.com/OpenSID/OpenSID/issues/4746) Perbaiki Nomor dan Tanggal Akta Nikah aktif ketika status perkawinan belum kawin.
22. [#4744](https://github.com/OpenSID/OpenSID/issues/4744) Perbaiki Sesuaikan Sebutan Desa pada Notifikasi Layanan Mandiri.
23. [#4764](https://github.com/OpenSID/OpenSID/issues/4764) Perbaiki Paginasi pada menu Buku Rekapitulasi Jumlah Penduduk tidak bekerja normal.
24. [#4760](https://github.com/OpenSID/OpenSID/issues/4760) Perbaiki Link/Url "Home" pada breadcrumb salah.

#### Perubahan Teknis

1. [#4661](https://github.com/OpenSID/OpenSID/issues/4661) Perbaiki jQuery di panggil 2 kali.
2. [#4639](https://github.com/OpenSID/OpenSID/pull/4639) Penambahan single quote ( ' ) pada opsi order_by.
3. Hapus folder/file logs setiap kali menjalankan job.
4. Perbaikan link assets menggunakan helper asset().
5. Tambahkan browser Edge yang berbasis Chromium.
6. Jangan kirim notifikasi jika token bot telegram kosong.
7. Penulisan hallo menjadi halo