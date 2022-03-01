#### [v22.03]

Di rilis ini, versi 22.03, menyediakan Tampilkan Data ID BDT Rumah Tangga dan Kirim laporan Siskeudes ke OpenDK melalu API. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada @afila yang terus berkontribusi.

#### Penambahan Fitur

1. [#4224](https://github.com/OpenSID/OpenSID/issues/4224) Tambahkan kolom BPJS Ketenagakerjaan di biodata penduduk, beserta statistiknya.
2. Perjelas judul Asuransi menjadi Asuransi Kesehatan di biodata penduduk.
3. [#3598](https://github.com/OpenSID/OpenSID/issues/3598) Sekarang Buku Peraturan Desa dan Buku Keputusan Kepala Desa dapat dipilah berdasarkan tahun.
4. [#4299](https://github.com/OpenSID/OpenSID/issues/4299) Tambahkan ID BDT pada data Kependudukan > Rumah Tangga dan Statistik > Kependudukan > Rumah Tangga.
5. [#4300](https://github.com/OpenSID/OpenSID/issues/4300) Tampilkan ID BDT di Surat Keterangan Kurang Mampu.
6. [#2321](https://github.com/OpenSID/OpenSID/issues/2321) Kirim laporan Siskeudes ke OpenDK melalu API.

#### Perbaikan BUG

1. [#4301](https://github.com/OpenSID/OpenSID/issues/4301) Perbaiki id validasi yang duplikat di form pembuatan surat dan global modal setting.
2. [#4305](https://github.com/OpenSID/OpenSID/issues/4305) Perbaiki peta dusun yang tidak tampil semua.
3. [#4271](https://github.com/OpenSID/OpenSID/issues/4271) Perbaiki input nama dan deskripsi produk pada Modul Lapak.
4. Sekarang impor peta data persil tersimpan dan tampil benar.
5. [#4311](https://github.com/OpenSID/OpenSID/issues/4311) Sekarang blok tanda tangan di Lembar Disposisi sesuai dengan pilihan petugas.
6. [#4287](https://github.com/OpenSID/OpenSID/issues/4287) Sekarang dari statistik kependudukan, bisa tampilkan data penduduk yang belum mengisi Kepemilikan KTP.
7. [#4309](https://github.com/OpenSID/OpenSID/issues/4309) Perbaiki paginasi pada modul Menu Statis.
8. [#4310](https://github.com/OpenSID/OpenSID/issues/4310) Perbaiki penyimpanan mutasi C-Desa dan penghapusan pemilik awal.
9. [#4313](https://github.com/OpenSID/OpenSID/issues/4313) Perbaiki form input data penduduk pada kolom data yg harus diketik, enter menyebabkan mengarah ke peta.
10. [#4346](https://github.com/OpenSID/OpenSID/issues/4346) Perbaiki program bantuan yang tidak dapat terhapus.
11. [#4289](https://github.com/OpenSID/OpenSID/issues/4289) Perbaiki session cari yang bentrok saat membuat C-Desa.
12. [#4348](https://github.com/OpenSID/OpenSID/issues/4348) Perbaiki batasi akses cetak kk untuk penduduk lepas di layanan mandiri.
13. [#4334](https://github.com/OpenSID/OpenSID/issues/4334) Perbaiki captcha ke matematik.
14. [#4269](https://github.com/OpenSID/OpenSID/issues/4269) Perbaiki permohonan surat berstatus Sedang Diperiksa.
15. [#3105](https://github.com/OpenSID/OpenSID/issues/3105) Perbaiki jangan tampilkan dusun jika - pada program bantuan.
16. [#4357](https://github.com/OpenSID/OpenSID/issues/4357) Perbaiki pengaturan khusus untuk modul pelanggan.
17. [#4360](https://github.com/OpenSID/OpenSID/issues/4360) Perbaiki status sdgs ketika tidak berhasil mendapatkan respon.
18. [#4363](https://github.com/OpenSID/OpenSID/issues/4363) Perbaiki impor peta type .gpx setelah impor menjadi blank.
19. [#4343](https://github.com/OpenSID/OpenSID/issues/4343) Perbaiki Data di ID BDT tidak dapat di filter antara sudah terisi dan belum mengisi.
20. [#4327](https://github.com/OpenSID/OpenSID/issues/4327) Perbaiki format Data Penduduk .xls Sinkronisasi Dari OpenSID ke OpenDK tidak sesuai.
21. [#4354](https://github.com/OpenSID/OpenSID/issues/4354) Perbaiki hak akses tambah/ubah dokumen, ekspedisi.
22. [#356](https://github.com/OpenSID/premium/issues/356) Perbaiki URL verifikasi surat tidak menampilkan data penduduk seperti NIK. [security]
23. [#4388](https://github.com/OpenSID/OpenSID/issues/4388) Perbaiki pencatatan log penduduk saat ubah data.

#### Perubahan Teknis

1. Kembalikan commit 6d7d4f776c6a39871a2310dc6d6b0b973aa9e572 yang tertimpa.
2. Rapikan script di form setting untuk kategori readonly demo_mode.
3. Otomatis simpan token pelanggan terbaru jika pelanggan melalakukan pemesanan.
4. Default server tracker/pantau dan layanan.
5. Sederhanakan modul menu
6. Perbaiki migrasi gagal pada penambahan indeks dengan nama kolom tertentu.
7. Sesuaikan contoh data awal modul menu.