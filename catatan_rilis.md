#### [v22.05-pasca]

Di rilis ini, versi 22.05-pasca, menyediakan Kaitkan indikator/pertanyaan analisis dengan data saran penduduk dan keluarga, Tampilkan data Pembangunan di halaman WEB. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada andifahruddinakas dan agungsugiarto yang terus berkontribusi.

#### Penambahan Fitur

1. [#4366](https://github.com/OpenSID/OpenSID/issues/4366) RSS Feeds artikel sekarang berisi tanggal unggah artikel.
2. [#4440](https://github.com/OpenSID/OpenSID/issues/4440) Perbaiki tampilan hasil analisis di web, supaya indikator ditampilkan per master analisis.
3. [#4446](https://github.com/OpenSID/OpenSID/issues/4446) Perbaiki surat keterangan nikah sesuai dg KEPDIRJEN BIMAS ISLAM NO. 473 TAHUN 2020.
4. [#4322](https://github.com/OpenSID/OpenSID/issues/4322) Sediakan notifikasi telegram ke pengguna admin / operator.
5. [#4376](https://github.com/OpenSID/OpenSID/issues/4376) Pencarian dan kategori produk pada web lapak.
6. [#3844](https://github.com/OpenSID/OpenSID/issues/3844) Perjelas keterangan data kependudukan wilayah pada halaman website desa.
7. [#3404](https://github.com/OpenSID/OpenSID/issues/3404) Penyesuaian Istilah Baru tentng COVID-19.
8. [#4402](https://github.com/OpenSID/OpenSID/issues/4402) Tampilkan data Pembangunan di halaman WEB.
9. [#4404](https://github.com/OpenSID/OpenSID/issues/4404) Kaitkan indikator/pertanyaan analisis dengan data saran penduduk dan keluarga.
10. [#4352](https://github.com/OpenSID/OpenSID/issues/4352) Sediakan kolom input ID BDT di menu Tambah Rumah Tangga Baru.
11. Ubah No. KK Sementara dari 0 menjadi 16 digit diawali 0, dengan format 0[kode-desa-10-digit][nomor-urut-5-digit]
12. Sediakan bisa pecah seluruh anggota keluarga status mati/hilang/pindah dan masukkan ke keluarga baru.


#### Perbaikan BUG

1. [#4450](https://github.com/OpenSID/OpenSID/issues/4450) Perbaiki bisa unduh salinan kartu keluarga.
2. [#4451](https://github.com/OpenSID/OpenSID/issues/4451) Daftar Pembangunan sekarang menampilkan semua pembangunan.
3. Tambahkan penjelasan dan validasi pengisian waktu di form Pembangunan.
4. [#4455](https://github.com/OpenSID/OpenSID/issues/4455) Data keluarga dengan KK yang mati/pindah/hilang sekarang tidak dapat diubah.
5. [#4458](https://github.com/OpenSID/OpenSID/issues/4458) Perbaiki tombol simpan tidak berfungsi pada input menu Buku Tanah di Desa.
6. [#4459](https://github.com/OpenSID/OpenSID/issues/4459) Perbaiki error 500 ketika melakukan pengosongan Basisdata.
7. Sekarang pilihan 'Bawa bukti fisik ke Kantor Desa' untuk syarat dokumen di layanan surat anjungan kembali tersedia.
8. [#4456](https://github.com/OpenSID/OpenSID/issues/4456) Perbaiki menampilkan penerima bantuan dari statistik program bantuan.
9. [#4461](https://github.com/OpenSID/OpenSID/issues/4461) Izinkan data penduduk sama di modul buku tanah di desa.
10. [#4467](https://github.com/OpenSID/OpenSID/issues/4467) Perbaiki tidak bisa simpan data produk dengan tipe potongan persen (%).
11. [#4462](https://github.com/OpenSID/OpenSID/issues/4462) Perbaiki urut permohonan surat.
12. [#4471](https://github.com/OpenSID/OpenSID/issues/4471) Perbaiki buka halaman verifikasi surat.
13. Perbaiki validasi input warna di Identitas Desa Peta Wilayah
14. [#4484](https://github.com/OpenSID/OpenSID/issues/4484) Perbaiki impor data penduduk yg data keluarga tdk tampil.
15. [#4485](https://github.com/OpenSID/OpenSID/issues/4485) Batasi input data Nomor Rumah Tangga maksimal 30 karakter.
16. [#4492](https://github.com/OpenSID/OpenSID/issues/4492) Perbaiki gagal akses modul Inventaris dan Kekayaan Desa jika data masih kosong/tidak ada.
17. [#4483](https://github.com/OpenSID/OpenSID/issues/4483) Perbaiki gagal menampilkan file/gambar dengan nama yang berkarakter kshsus (misalnya persen (%)).
18. [#4494](https://github.com/OpenSID/OpenSID/issues/4494) Perbaiki ubah menu untuk kelompok yang salah.
19. [#4510](https://github.com/OpenSID/OpenSID/issues/4510) Perbaiki cetak dokumen kesepakatan kerjasama desa.
20. [#4466](https://github.com/OpenSID/OpenSID/issues/4466) Perbaiki pencarian produk beserta halaman pada web lapak.
21. [#4435](https://github.com/OpenSID/OpenSID/issues/4435) Perbaiki daftar permohonan surat.
22. [#4531](https://github.com/OpenSID/OpenSID/issues/4531) Perbaiki tampilkan daftar rumah tangga sesuai statistik RTM.
23. Perbaiki statistik kependudukan berbasis rentang umur, yaitu Umur (Rentang), Umur (Kategori) dan Akta Kelahiran.
24. Perbaiki statistik kependudukan hubungan keluarga, untuk menangani kasus data yang belum diisi.
25. [#4530](https://github.com/OpenSID/OpenSID/issues/4530) Ubah data Akseptor KB yang tidak valid supaya statistik Akseptor KB tampil benar.
26. [#4530](https://github.com/OpenSID/OpenSID/issues/4532) Perbaiki link detail pembangunan pada halaman peta.
27. [#4502](https://github.com/OpenSID/OpenSID/issues/4502) Perbaiki status hubungan dalam keluarga untuk famili menjadi famili lain.
28. [#4514](https://github.com/OpenSID/OpenSID/issues/4514) Perbaiki dan sederhanakan tombol kemabli pada modul analisis.
29. [#4513](https://github.com/OpenSID/OpenSID/issues/4513) Jangan tampilkan aksi pecah kk untuk keluarga tidak aktif dengan anggota keluarga 0.
30. [#4498](https://github.com/OpenSID/OpenSID/issues/4498) Hapus data analisis_parameter dengan responden 0 untuk tipe pertanyaan 3 dan 4.
31. [#4499](https://github.com/OpenSID/OpenSID/issues/4499) Perbaiki lampiran surat_ket_kelahiran (F-21.01).
32. Perbaiki format waktu (jam) pesan yang dikirimkan lewat telegram.
33. Perbaiki tampilan kelengkapan dokumen di layanan mandiri.
34. [#4538](https://github.com/OpenSID/OpenSID/issues/4538) Perbaiki error impor program bantuan untuk kasus template menggunakan format date untuk tanggal.
35. [#4535](https://github.com/OpenSID/OpenSID/issues/4535) Perbaiki NIK dan No KK Sementara pada Surat Permohonan Kartu Keluarga Baru.


#### Perubahan Teknis

1. Pindahkan tampilan lapak ke masing-masing tema.
2. Pindahkan Info System ke controller tersendiri.
3. Pindahkan folder cache ke desa > cache.
4. Gunakan devtool debugbar untuk development.
5. Sederhanakan paging pada tema klasik.
6. Tambahkan define DESAPATH pada filemanager.
7. Perbaiki template dokumen kerjasama.
8. Perbaiki mengurut view yang bergantungan di backup database.
9. Sesuaikan impor data untuk mode demo.
