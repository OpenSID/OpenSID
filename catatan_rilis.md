#### [v22.05]

Di rilis ini, versi 22.05, menyediakan  Buku Kegiatan Pembangunan di Buku Administrasi Pembangunan. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pula pada Cahyo Wicaksono yang baru mulai berkontribusi.

#### Penambahan Fitur
1. [#4179](https://github.com/OpenSID/OpenSID/issues/4179) Sediakan Buku Kegiatan Pembangunan di Buku Administrasi Pembangunan.
2. [#4410](https://github.com/OpenSID/OpenSID/issues/4410) Sekarang dokumen kelengkapan penduduk bisa diunduh atau ditampilkan (jika setting browser mendukung).
3. [#4371](https://github.com/OpenSID/OpenSID/issues/4371) Sekarang bisa menghapus lebih dari satu log file sekaligus.
4. [#4355](https://github.com/OpenSID/OpenSID/issues/4355) Sediakan subjek tingkat desa, dusun, rukun warga (RW) dan rukun tetangga (RT) untuk master analisis.
5. [#4122](https://github.com/OpenSID/OpenSID/issues/4122) Mengirim Buku Rekap Jumlah Penduduk ke OpenDK melalui API.
6. Sediakan fitur ekspor master analisis yang bisa langsung diimpor.
7. Sekarang impor master analisis membaca file xlsx, tidak lagi xls.

#### Perbaikan BUG
1. [#4408](https://github.com/OpenSID/OpenSID/issues/4408) Perbaiki tambah warga terdata di suplemen keluarga berdasarkan keluarga yang aktif.
2. [#4399](https://github.com/OpenSID/OpenSID/issues/4399) Perbaiki peta wilayah hasil impor file tipe .kml.
3. [#383](https://github.com/OpenSID/premium/issues/383) Perbaiki tidak bisa ubah password default pengguna baru.
4. [#4415](https://github.com/OpenSID/OpenSID/issues/4415) Perbaiki Cetak lembaga dan kelompok tidak tampil.
5. [#4425](https://github.com/OpenSID/OpenSID/issues/4425) Sekarang dokumen lampiran artikel bisa diunduh di tema klasik dan natra bawaan sistem.
6. [#4414](https://github.com/OpenSID/OpenSID/issues/4414) Perbaiki penanganan akses Responsive File Manager menggunakan session bawaan CI3.
7. [#389](https://github.com/OpenSID/premium/issues/389) Tambahkan validasi input nomor kartu keluarga terpisah dari validasi NIK.
8. [#388](https://github.com/OpenSID/OpenSID/issues/388) Sekarang kepala desa tidak wajib diisi pada saat mengubah identitas desa.
9. [#4426](https://github.com/OpenSID/OpenSID/issues/4426) Sesuaikan Nama Menu/Modul pada Admin dan web OpenSID dengan sebutan_desa/sebutan_kepala_desa
10. [$4419](https://github.com/OpenSID/OpenSID/issues/4419) Perbaiki impor data penduduk dengan NIK sementara.
11. [#4421](https://github.com/OpenSID/OpenSID/issues/4421) Perbaiki NIK sementara pada persuratan belum disesuaikan secara menyeluruh.
12. [#4439](https://github.com/OpenSID/OpenSID/issues/4439) Perbaiki error ketika kolom hubungan dalam keluarga tidak di isi saat menambah penduduk masuk.
13. [#4432](https://github.com/OpenSID/OpenSID/issues/4439) Sesuaikan hak akses level operator pada modul Pengguna
14. [#4450](https://github.com/OpenSID/OpenSID/issues/4450) Perbaiki bisa unduh salinan kartu keluarga.
15. [#4451](https://github.com/OpenSID/OpenSID/issues/4451) Daftar Pembangunan sekarang menampilkan semua pembangunan.
16. Tambahkan penjelasan dan validasi pengisian waktu di form Pembangunan.
17. [#4455](https://github.com/OpenSID/OpenSID/issues/4455) Data keluarga dengan KK yang mati/pindah/hilang sekarang tidak dapat diubah.
18. [#4458](https://github.com/OpenSID/OpenSID/issues/4458) Perbaiki tombol simpan tidak berfungsi pada input menu Buku Tanah di Desa.
19. [#4459](https://github.com/OpenSID/OpenSID/issues/4459) Perbaiki error 500 ketika melakukan pengosongan Basisdata.
20. Sekarang pilihan 'Bawa bukti fisik ke Kantor Desa' untuk syarat dokumen di layanan surat anjungan kembali tersedia.
21. [#4456](https://github.com/OpenSID/OpenSID/issues/4456) Perbaiki menampilkan penerima bantuan dari statistik program bantuan.
22. [#4461](https://github.com/OpenSID/OpenSID/issues/4461) Izinkan data penduduk sama di modul buku tanah di desa.
23. [#4467](https://github.com/OpenSID/OpenSID/issues/4467) Perbaiki tidak bisa simpan data produk dengan tipe potongan persen (%).
24. [#4462](https://github.com/OpenSID/OpenSID/issues/4462) Perbaiki urut permohonan surat.
25. [#4471](https://github.com/OpenSID/OpenSID/issues/4471) Perbaiki buka halaman verifikasi surat.
26. Perbaiki validasi input warna di Identitas Desa Peta Wilayah
27. [#4484](https://github.com/OpenSID/OpenSID/issues/4484) Perbaiki impor data penduduk yg data keluarga tdk tampil.
28. [#4485](https://github.com/OpenSID/OpenSID/issues/4485) Batasi input data Nomor Rumah Tangga maksimal 30 karakter.
29. [#4492](https://github.com/OpenSID/OpenSID/issues/4492) Perbaiki gagal akses modul Inventaris dan Kekayaan Desa jika data masih kosong/tidak ada.
30. [#4483](https://github.com/OpenSID/OpenSID/issues/4483) Perbaiki gagal menampilkan file/gambar dengan nama yang berkarakter kshsus (misalnya persen (%)).

#### Perubahan Teknis
1. Perbaiki migrasi yang gagal.
2. Sesuaikan icon peta wilayah dan widget info pelanggan.
3. Penyesuaian pengaturan basis data untuk pengguna/desa
