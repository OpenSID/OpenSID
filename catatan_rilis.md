#### [v22.04]

Di rilis ini, versi 22.04, menyediakan Modul pendataan lembaga desa dan buku rencana kerja pembangunan. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada Agung Sugiarto dan afa28 yang terus berkontribusi. Terima kasih pula pada Cahyo Wicaksono yang baru mulai berkontribusi.

#### Penambahan Fitur

1. [#4330](https://github.com/OpenSID/OpenSID/issues/4330) Kenali gawai anjungan layanan mandiri menggunakan MAC Address.
2. [#4339](https://github.com/OpenSID/OpenSID/issues/4339) Tampilkan ketentuan layanan di Info Desa > Layanan Pelanggan.
3. [#369](https://github.com/OpenSID/premium/issues/369) Modul pendataan lembaga desa.
4. [#3613](https://github.com/OpenSID/OpenSID/issues/3613) Sediakan fitur cetak nomor registrasi/antrian untuk anjungan mandiri.
5. [#4178](https://github.com/OpenSID/OpenSID/issues/4178) Sediakan Buku Rencana Kerja Pembangunan di Buku Administrasi Pembangunan.

#### Perbaikan BUG

1. [#4346](https://github.com/OpenSID/OpenSID/issues/4346) Perbaiki program bantuan yang tidak dapat terhapus.
2. [#4289](https://github.com/OpenSID/OpenSID/issues/4289) Perbaiki session cari yang bentrok saat membuat C-Desa.
3. [#4348](https://github.com/OpenSID/OpenSID/issues/4348) Perbaiki batasi akses cetak kk untuk penduduk lepas di layanan mandiri.
4. [#4334](https://github.com/OpenSID/OpenSID/issues/4334) Perbaiki captcha ke matematik.
5. [#4269](https://github.com/OpenSID/OpenSID/issues/4269) Perbaiki permohonan surat berstatus Sedang Diperiksa.
6. [#3105](https://github.com/OpenSID/OpenSID/issues/3105) Perbaiki jangan tampilkan dusun jika - pada program bantuan.
7. [#4357](https://github.com/OpenSID/OpenSID/issues/4357) Perbaiki pengaturan khusus untuk modul pelanggan.
8. [#4360](https://github.com/OpenSID/OpenSID/issues/4360) Perbaiki status sdgs ketika tidak berhasil mendapatkan respon.
9. [#4363](https://github.com/OpenSID/OpenSID/issues/4363) Perbaiki impor peta type .gpx setelah impor menjadi blank.
10. [#4343](https://github.com/OpenSID/OpenSID/issues/4343) Perbaiki Data di ID BDT tidak dapat di filter antara sudah terisi dan belum mengisi.
11. [#4327](https://github.com/OpenSID/OpenSID/issues/4327) Perbaiki format Data Penduduk .xls Sinkronisasi Dari OpenSID ke OpenDK tidak sesuai.
12. [#4354](https://github.com/OpenSID/OpenSID/issues/4354) Perbaiki hak akses tambah/ubah dokumen, ekspedisi.
13. [#356](https://github.com/OpenSID/premium/issues/356) Perbaiki URL verifikasi surat tidak menampilkan data penduduk seperti NIK. [security]
14. [#4388](https://github.com/OpenSID/OpenSID/issues/4388) Perbaiki pencatatan log penduduk saat ubah data.
15. Tutup celah privasi data Arsip Layanan. [security]
16. Tutup celah privasi data Dokumen. [security]
17. [#3417](https://github.com/OpenSID/OpenSID/issues/3417) Ubah NIK Sementara dari 0 menjadi 16 digit diawali 0, dengan format 0[kode-desa-10-digit][nomor-urut-5-digit]
18. [#4356](https://github.com/OpenSID/OpenSID/issues/4356) Sekarang Title dan Author berkas RTF dan PDF hasil cetak surat berisi nama jenis surat dan nama desa.
19. [#360](https://github.com/OpenSID/premium/issues/360) Tambah validasi form isian data penduduk.
20. [#4408](https://github.com/OpenSID/OpenSID/issues/4408) Perbaiki tambah warga terdata di suplemen keluarga berdasarkan keluarga yang aktif.
21. [#4399](https://github.com/OpenSID/OpenSID/issues/4399) Perbaiki peta wilayah hasil impor file tipe .kml.
22. [#383](https://github.com/OpenSID/premium/issues/383) Perbaiki tidak bisa ubah password default pengguna baru.
23. [#4415](https://github.com/OpenSID/OpenSID/issues/4415) Perbaiki Cetak lembaga dan kelompok tidak tampil.
24. [#4425](https://github.com/OpenSID/OpenSID/issues/4425) Sekarang dokumen lampiran artikel bisa diunduh di tema klasik dan natra bawaan sistem.
25. [#4414](https://github.com/OpenSID/OpenSID/issues/4414) Perbaiki penanganan akses Responsive File Manager menggunakan session bawaan CI3.
26. [#389](https://github.com/OpenSID/premium/issues/389) Tambahkan validasi input no_kk tidak lagi menggunakan validasi NIK.
27. [#388](https://github.com/OpenSID/OpenSID/issues/388) Hapus required input kepala desa pada saat edit identitas desa.
28. [#4426](https://github.com/OpenSID/OpenSID/issues/4426) Sesuaikan Nama Menu/Modul pada Admin dan web OpenSID dengan sebutan_desa/sebutan_kepala_desa
29. [\$4419](https://github.com/OpenSID/OpenSID/issues/4419) Perbaiki impor data penduduk dengan NIK sementara.
30. [#4421](https://github.com/OpenSID/OpenSID/issues/4421) Perbaiki NIK sementara pada persuratan belum disesuaikan secara menyeluruh.

#### Perubahan Teknis

1. Perbaiki migrasi gagal pada penambahan indeks dengan nama kolom tertentu.
2. Sesuaikan contoh data awal modul menu.
3. Penanganan pelanggan premium. [security]
4. Perbaiki migrasi yang gagal.
5. Sesuaikan icon peta wilayah dan widget info pelanggan.
