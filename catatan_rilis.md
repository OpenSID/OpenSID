Rilis versi 2609.0.1 ini berisi perbaikan lampiran F-2.01 dan F-2.01-kelahiran tidak menampilkan data dan perbaikan lainnya yang diminta oleh komunitas SID.

### Bug

1. [#11918](https://github.com/OpenSID/OpenSID/issues/11918) Perbaikan Usia anak dan tanggal pemeriksaan ketika tambah data pemantauan anak 0-2 tahun tidak sesuai.
2. [#11926](https://github.com/OpenSID/OpenSID/issues/11926) Perbaikan kalkulasi akta kematian pada menu Statistik Kependudukan.
3. [#11920](https://github.com/OpenSID/OpenSID/issues/11920) Perbaikan validasi template surat ke reset ke status belum validasi jika klik simpan / simpan keluar.
4. [#11924](https://github.com/OpenSID/OpenSID/issues/11924) Perbaikan validasi template gagal ketika template mengandung halaman baru (pagebreak).
5. [#11916](https://github.com/OpenSID/OpenSID/issues/11916) Perbaikan rekapitulasi penduduk yang tidak sinkron dengan dashboard.
6. [#11944](https://github.com/OpenSID/OpenSID/issues/11944) Perbaikan artikel yang terdeteksi spam.
7. [#11836](https://github.com/OpenSID/OpenSID/issues/11836) Perbaikan tidak bisa hapus Inventaris Aset Lainnya yang memiliki riwayat mutasi.
8. [#11928](https://github.com/OpenSID/OpenSID/issues/11928) Perbaikan lampiran F-2.01 dan F-2.01-kelahiran tidak menampilkan data.
9. [#11942](https://github.com/OpenSID/OpenSID/issues/11942) Perbaikan Jabatan kades dan sekdes tidak berubah saat di setting kelurahan dan tidak bisa di edit.
10. [#11946](https://github.com/OpenSID/OpenSID/issues/11946) Perbaikan total kk pada wilayah administratif tidak sama dengan total kk di menu keluarga.
11. [#11949](https://github.com/OpenSID/OpenSID/issues/11949) Perbaikan tidak muncul tombol untuk passphrase tte di akun kades.
12. [#11950](https://github.com/OpenSID/OpenSID/issues/11950) Perbaikan informasi jika akun di nonaktifkan karna telah tidak login selama 30 hari.
13. [#11952](https://github.com/OpenSID/OpenSID/issues/11952) Perbaikan validasi templat gagal setelah salin surat bawaan sistem.



### Teknis

1. [#11921](https://github.com/OpenSID/OpenSID/issues/11921) Membuat progress latih model spam lebih jelas dan tambahkan workflow input data spam massal.
2. [#6816](https://github.com/OpenSID/premium/issues/6816) Pemindahan file index.php ke folder public/ mengikuti struktur standar Laravel
3. [#7011](https://github.com/OpenSID/premium/issues/7011) Data pemesanan  otomatis terupdate setelah perpanjangan layanan.
4. [#11934](https://github.com/OpenSID/OpenSID/issues/11934) Perbaikan isian data pengaduan tidak masuk ke admin.
5. [#6937](https://github.com/OpenSID/premium/issues/6937) Modernisasi pengelolaan berkas TinyMCE: migrasi RFM ke ekosistem Laravel.



### Optimasi

1. [#11921](https://github.com/OpenSID/OpenSID/issues/11921) Optimasi Test Suite - Pengurangan Waktu Eksekusi.
2. [#11917](https://github.com/OpenSID/OpenSID/issues/11917) Optimasi performa halaman Laporan dan Impor Desil DTSEN.


### Keamanan

1. [#7008](https://github.com/OpenSID/premium/issues/7008) Perbaikan keamanan proteksi eksekusi PHP di folder unggah lemah — .htaccess FilesMatch bisa dilewati & diabaikan Nginx.
2. [#6999](https://github.com/OpenSID/premium/issues/6999) Perbaikan keamanan data tempat lahir & alamat penduduk (menu Suplemen) tampil di halaman/API publik tanpa login.
