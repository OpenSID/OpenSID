#### [v21.11-pasca - 2021-11-19](https://github.com/OpenSID/premium/compare/v21.04-premium...v21.05-premium)

Di rilis ini, versi 21.11-pasca, menyediakan Impor Survei Google Form ke Master Analisis. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada Mochamad Arifin dan Ariandi Ryan Kahfi yang terus berkontribusi. Terima kasih pula pada Mokhamad Afila yang baru mulai berkontribusi.

Lengkapnya, isi rilis versi v21.11-pasca - 2021-11-19 adalah sebagai berikut:

#### Penambahan Fitur
1. [#4108](https://github.com/OpenSID/OpenSID/issues/4108) - Filter arsip surat menurut bulan.
2. [#3986](https://github.com/OpenSID/OpenSID/issues/3986) - Samarkan data penduduk di server yang khusus digunakan untuk data publik saja, tidak untuk pengelolaan data penduduk.
3. [#3986](https://github.com/OpenSID/OpenSID/issues/3986) - Sinkronkan data penduduk dan keluarga dari offline ke server data publik melalui impor file.
4. [#4121](https://github.com/OpenSID/OpenSID/issues/4121) - Tampilkan evaluasi SDGs Kemendesa di web.
5. [#1994](https://github.com/OpenSID/OpenSID/issues/1994) - Sediakan fitur untuk membuat grup pengguna tambahan yg bisa diatur hak aksesnya.
6. Tampilkan catatan rilis di Dashboard Admin.
7. [#4054](https://github.com/OpenSID/OpenSID/issues/4054) - Bisa impor survei Google Form ke master Analisis.
8. [#2836](https://github.com/OpenSID/OpenSID/issues/2836) - Sediakan Buku Tanah Desa sesuai Permendagri 47/2016.
9. [#2837](https://github.com/OpenSID/OpenSID/issues/2837) - Sediakan Buku Tanah Kas Desa sesuai Permendagri 47/2016.

#### Perbaikan BUG
1. Perketat pemeriksaan file pada Database > Restore.
2. Daftar penduduk tampil normal pada sql_mode = ONLY_FULL_GROUP_BY.
3. Modul Pertanahan sekarang menampilkan penjelasan kalau lokasi persil tidak ditemukan.
4. [#4091](https://github.com/OpenSID/OpenSID/issues/4091) - Sekarang menu navigasi tampil di atas tombol peta pada waktu hover mouse.
5. [#4123](https://github.com/OpenSID/OpenSID/issues/4123) - Sekarang penandatangan lampiran Surat Biodata Penduduk, Surat Permohonan Kartu Keluarga dan Surat Permohonan Perubahan Kartu Keluarga tampil benar.
6. [#4118](https://github.com/OpenSID/OpenSID/issues/4118) - Sekarang urut pertanyaan/indikator analisis berdasarkan kode lebih konsisten.
7. [#4113](https://github.com/OpenSID/OpenSID/issues/4113) - Perbaiki bisa cari nama penduduk atau nama lain yg berisi tanda kutip (').
8. [#4129](https://github.com/OpenSID/OpenSID/issues/4129) - Sekarang menambah anggota keluarga sudah normal kembali.
9. [#1486](https://github.com/OpenSID/OpenSID/issues/1486) - Hapus isian yg tidak digunakan di form Surat Pernyataan Belum Memiliki Akta Lahir.
10. [#3352](https://github.com/OpenSID/OpenSID/issues/3352) - Perbaiki pencatatan data pengunjung web.
11. [#4131](https://github.com/OpenSID/OpenSID/issues/4131) - Sekarang tidak tampil penduduk duplikat setelah pecah atau keluarkan anggota keluarga.
12. [#4130](https://github.com/OpenSID/OpenSID/issues/4130) - Sekarang pilihan huruf yg dapat dibaca penduduk tampil pada waktu menambah KK dan anggota keluarga.
13. Tampilkan status IDM untuk tahun 2021.
14. [#3593](https://github.com/OpenSID/OpenSID/issues/3593) - Tambahkan kolom status_dasar di file impor/ekspor data penduduk.
15. [#4135](https://github.com/OpenSID/OpenSID/issues/4135) - Sekarang halaman statis Daftar Calon Pemilih tampil normal kembali di web.
16. [#4133](https://github.com/OpenSID/OpenSID/issues/4133) - Sekarang peta tidak membatasi jumlah area yg dapat ditampilkan.
17. [#4136](https://github.com/OpenSID/OpenSID/issues/4136) - Sekarang penduduk dengan status dasar selain hidup tidak lagi tampil duplikat.
18. [#4140](https://github.com/OpenSID/OpenSID/issues/4140) - Sekarang jumlah rincian data suplemen yang tampil melalui menu statis web tidak dibatasi.
19. [#4143](https://github.com/OpenSID/OpenSID/issues/4143) - Kurangi jumlah link halaman pada paginasi Pengaturan Peta > Area supaya terlihat lebih rapi.
20. [#4148](https://github.com/OpenSID/OpenSID/issues/4148) - Sekarang bisa cari arsip layanan surat menggunakan nama penduduk berisi tanda petik (').
21. [#4150](https://github.com/OpenSID/OpenSID/issues/4150) - Sekarang NIK ganda tidak bisa dimasukkan lagi.
22. [#4152](https://github.com/OpenSID/OpenSID/issues/4152) - Sekarang penduduk mati/pindah/hilang yg belum kawin tidak lagi muncul di daftar penduduk lepas pada waktu menambah anggota keluarga dari penduduk yg ada.
23. Sekarang penduduk mati/pindah/hilang tidak lagi muncul di daftar pilihan terdata data suplemen.
24. Tulis ulang log_keluarga dan tulis ulang penghitungan statistik laporan kependudukan bulanan.
25. [#4154](https://github.com/OpenSID/OpenSID/issues/4154) - Sekarang urut pertanyaan/indikator di unduh data sensus/survei analisis lebih konsisten.
26. Ubah keterangan Google Key menjadi Mapbox Key di pengaturan aplikasi.
27. Sekarang bisa kembali tambah penduduk dengan NIK 0. [bug-fix]
28. Tampilkan penandatangan yg benar di Laporan Penduduk per Wilayah, Surat Keluar dan Buku Data Aparat Pemerintahan Desa. [bug-fix]
29. Sekarang penduduk tidak tampil ganda di Kependudukan > Penduduk jika menjadi peserta di lebih dari satu program bantuan. [bug-fix]


#### Perubahan Teknis
1. Pindahkan folder cache ambil status release dan status IDM ke folder 'cache'.
