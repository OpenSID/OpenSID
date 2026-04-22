Rilis versi 2604.0.1 ini berisi [untuk diisi]  dan perbaikan lainnya yang diminta oleh komunitas SID.

### BUG

1. [#10974](https://github.com/OpenSID/OpenSID/issues/10974) Perbaikan tanggal cetak yang dipilih tidak digunakan pada hasil cetak di Buku Administrasi Umum (selalu menampilkan tanggal hari ini).
2. [#10977](https://github.com/OpenSID/OpenSID/issues/10977) Perbaikan informasi masa berlaku OTP tidak sesuai antara aplikasi dan email.
3. [#10976](https://github.com/OpenSID/OpenSID/issues/10976) Perbaikan isian data perkawinan ketika merubah status menjadi cerai hidup dan mati, isian data pelengkapnya tidak secara otomatis terhiden kolomnya.
4. [#6129](https://github.com/OpenSID/premium/issues/6129) Perbaikan tidak ada validasi jenis file (Unrestricted File Upload) pada menu upload foto profil.
5. [#10987](https://github.com/OpenSID/OpenSID/issues/10987) Perbaikan data penduduk tidak tampil benar pada peta wilayah dusun.
6. [#10982](https://github.com/OpenSID/OpenSID/issues/10982) Perbaikan nama file hasil unduh xls pada menu laporan kelompok rentan tidak berisi nama hanya timestamp saja.
7. [#10966](https://github.com/OpenSID/OpenSID/issues/10966) Perbaikan pengaturan tahun apbdes.
8. [#10986](https://github.com/OpenSID/OpenSID/issues/10986) Perbaikan data akta kematian tidak tampil benar pada laporan statistik.
9. [#11018](https://github.com/OpenSID/OpenSID/issues/11018) Perbaikan data rincian lembaga tidak tampil di web.
10. [#11019](https://github.com/OpenSID/OpenSID/issues/11019) Perbaikan banyaknya foto setiap produk di menu lapk tidak bisa di isi angka lebih dari 5.
11. [#11020](https://github.com/OpenSID/OpenSID/issues/11020) Perbaikan peta desa tidak muncul di menu pemetaan.
12. [#11026](https://github.com/OpenSID/OpenSID/issues/11026) Perbaikan tombol enter pada keyboard pada saat isi "HAPUS" pada modal alert konfirmasi hapus tidak berfungsi.
13. [#11028](https://github.com/OpenSID/OpenSID/issues/11028) Perbaikan label tombol "Kembali ke Daftar Kelompok Di Desa" pada form tambah/ubah dokumen kelompok tidak sesuai dengan aksi.
14. [#11015](https://github.com/OpenSID/OpenSID/issues/11015) Perbaikan beberapa bug pada fitur dtsen yang harus disesuaikan.
15. [#11021](https://github.com/OpenSID/OpenSID/issues/11021) Perbaikan filter pada menu statistik kependudukan tidak sesuai.
16. [#11036](https://github.com/OpenSID/OpenSID/issues/11036) Perbaikan peta desa ketika dicetak tampil kecil.
17. [#11038](https://github.com/OpenSID/OpenSID/issues/11038) Perbaikan tombol kembalikan penduduk tidak tampil di riwayat mutasi penduduk.
18. [#11052](https://github.com/OpenSID/OpenSID/issues/11052) Perbaikan info mohon tunggu pada tombol simpan terus muncul setelah data berhasil disimpan.
19. [#11057](https://github.com/OpenSID/OpenSID/issues/11057) Perbaikan struktur tabel artikel agar saat kategori artikel dihapus maka artikel tidak ikut terhapus.
20. [#11058](https://github.com/OpenSID/OpenSID/issues/110580) Perbaikan dataTables gagal load (net::ERR_EMPTY_RESPONSE) karena URL GET terlalu panjang di-drop WAF/edge di modul penduduk dan keluarga.
21. [#11053](https://github.com/OpenSID/OpenSID/issues/11053) Perbaikan tidak ada keterangan/validasi maksimal karakter saat input di pengaturan peta.
22. [#11042](https://github.com/OpenSID/OpenSID/issues/11042) Perbaikan menampilkan '0' untuk nomor KK dan NIK sementara di halaman hasil unduh F109.
23. [#11045](https://github.com/OpenSID/OpenSID/issues/11045) Perbaikan menampilkan data berelasi pada layanan mandiri.
24. [#11047](https://github.com/OpenSID/OpenSID/issues/11047) Perbaikan lihat password pada halaman pengaturan aplikasi tidak berfungsi.
25. [#11046](https://github.com/OpenSID/OpenSID/issues/11046) Perbaikan saat aplikasi patau down, website desa dengan data lengkap atau stabil ikut menjadi lambat diakses.
26. [#11063](https://github.com/OpenSID/OpenSID/issues/11063) Perbaikan sebutan nama di soal DTSEN pada tab anggota keluarga tidak mencantumkan nama sesuai data yang dipilih.
27. [#11074](https://github.com/OpenSID/OpenSID/issues/11074) Perbaikan SHDK pada isian 409 DTSEN tidak otomatis terisi berdasarkan data penduduk.
28. [#11071](https://github.com/OpenSID/OpenSID/issues/11071) Perbaikan pada hasil pemantauan dtsen, nama petugas tidak tampil, padahal sudah di isi manual.
29. [#11073](https://github.com/OpenSID/OpenSID/issues/11073) Perbaikan isian memiliki kartu Identitas pada isian 411 di DTSEN tidak sesuai.

### KEAMANAN
1. [#6160](https://github.com/OpenSID/premium/issues/6160) Perbaikan SQL injection (blind) pada parameter filter[tahun] di endpoint Bantuan Penduduk.

### TEKNIS
1. [#10908](https://github.com/OpenSID/OpenSID/issues/10908) Tema wira menjadi tema default pada instalasi baru aplikasi OpenSID Premium dan juga penambahan tema Lestari dan Seruit Lite selama berstatus pelanggan premium.
2. [#11076](https://github.com/OpenSID/OpenSID/issues/11076) Penambahan halaman peringatan ketika ionCube loader belum terpasang.
3. [#10953](https://github.com/OpenSID/OpenSID/issues/10953) Optimasi browser cache untuk static asset tema & module.