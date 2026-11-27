Rilis versi 2607.0.0 ini berisi teknis konversi opensid dari CI 3 ke Laravel secara incremental tahap pertama dan perbaikan lainnya yang diminta oleh komunitas SID.


### BUG

1. [#11262](https://github.com/OpenSID/OpenSID/issues/11262) Perbaikan form pengaduan warga menerima konten spam promosi.
2. [#11265](https://github.com/OpenSID/OpenSID/issues/11265) Perbaikan  kode daerah pada halaman formulir DTSEN tidak sesuai.
3. [#11267](https://github.com/OpenSID/OpenSID/issues/11267) Perbaikan cetak peta wilayah desa pada Identitas Desa tidak dapat digunakan.
4. [#11290](https://github.com/OpenSID/OpenSID/issues/11290) Perbaikan page tidak responsive setelah memasukkan kategori lembaga di form tambah lembaga.
5. [#11266](https://github.com/OpenSID/OpenSID/issues/11266) Perbaikan filter dusun dan tahun pada Statistik Akta Kematian tidak diteruskan ke Data Riwayat Mutasi Penduduk.
6. [#11296](https://github.com/OpenSID/OpenSID/issues/11296) Perbaikan error saat membuka menu DTSEN.
7. [#11264](https://github.com/OpenSID/OpenSID/issues/11264) Perbaikan beberapa masalah pada tema.
8. [#11297](https://github.com/OpenSID/OpenSID/issues/11297) Perbaikan logo pada halaman cetak PDF Sub Menu Rekap Catatan Harian yang tampil logo OpenSID.
9. [#11360](https://github.com/OpenSID/OpenSID/issues/11360) Perbaikan hasil unduh XLS Laporan Bulanan agar berisi border.
10. [#11345](https://github.com/OpenSID/OpenSID/issues/11345) Perbaikan pilihan opsi "bertemu" pada buku tamu tidak muncul sesuai yang diisi pada anjungan mandiri.
11. [#11329](https://github.com/OpenSID/OpenSID/issues/11329) Perbaikan template format_import_excel data penduduk untuk pekerjaan id nomor 5 yang masih PNS agar disesuaikan dengan aturan yang baru.
12. [#11311](https://github.com/OpenSID/OpenSID/issues/11311) Perbaikan kolom total saat cetak laporan kelompok rentan yang muncul di setiap halaman.
13. [#11310](https://github.com/OpenSID/OpenSID/issues/11310) Perbaikan tampilan print preview pada data suplemen yang amburadul dan tidak ada garis pada kolomnya.
14. [#11304](https://github.com/OpenSID/OpenSID/issues/11304) Perbaikan pilihan status kehadiran duplikat.
15. [#11303](https://github.com/OpenSID/OpenSID/issues/11303) Perbaikan tombol ekspor ke Excel di menu Stunting.
16. [#11302](https://github.com/OpenSID/OpenSID/issues/11302) Perbaikan kepemilikan Akta Kematian berdasarkan wilayah.
17. [#11372](https://github.com/OpenSID/OpenSID/issues/11372) Perbaikan tautan Facebook pada OpenSID yang masih mengarah ke grup yang lama.
18. [#11377](https://github.com/OpenSID/OpenSID/issues/11377) Perbaikan nama ketua umum pada form pendaftaran kerja sama desa di OpenSID agar sesuai yang terbaru.
19. [#6414](https://github.com/OpenSID/premium/issues/6414) Perbaikan impor template surat tidak bisa digunakan.
20. [#6412](https://github.com/OpenSID/premium/issues/6412) Perbaikan Modul Jabatan yang error 404.
21. [#6409](https://github.com/OpenSID/premium/issues/6409) Perbaikan tampilan halaman template surat yang jadi aneh dan berantakan setelah dikonversi.
22. [#6408](https://github.com/OpenSID/premium/issues/6408) Perbaikan tidak bisa membuat KK baru pada halaman Anggota Keluarga.
23. [#6407](https://github.com/OpenSID/premium/issues/6407) Perbaikan Modul Bagan Akses yang error 404.
24. [#6405](https://github.com/OpenSID/premium/issues/6405) Perbaikan impor template surat dinas tidak bisa digunakan.
25. [#6403](https://github.com/OpenSID/premium/issues/6403) Perbaikan error preview PDF cetak surat dinas.
26. [#6402](https://github.com/OpenSID/premium/issues/6402) Perbaikan gagal periksa permohonan surat.
27. [#6401](https://github.com/OpenSID/premium/issues/6401) Perbaikan gagal tambah pengguna baru layanan mandiri.
28. [#6400](https://github.com/OpenSID/premium/issues/6400) Perbaikan error ketika menjalankan atau mengakses script analisis.
29. [#6373](https://github.com/OpenSID/premium/pull/6373) Penyesuaian lampiran F2.01-a kelahiran anak.


### KEAMANAN

1. [#6354](https://github.com/OpenSID/premium/issues/6354) Perbaikan registrasi Buku Tamu yang menerima input spam dan percobaan SQL Injection.
2. [#6474](https://github.com/OpenSID/premium/issues/6474) Perbaikan kegagalan pemblokiran .htaccess yang mengakibatkan kebocoran kunci enkripsi (APP_KEY).

### TEKNIS

1. [#6353](https://github.com/OpenSID/premium/issues/6353) Penambahan status lisensi dan model distribusi pada repo OpenSID/rilis-premium.
2. [#6388](https://github.com/OpenSID/premium/issues/6388) Pengujian enkripsi OpenSID menggunakan IonCube.
3. [#6441](https://github.com/OpenSID/premium/issues/6441) Perbaikan error migrasi saat menjalankan semua migrasi.
4. [#6439](https://github.com/OpenSID/premium/issues/6439) Perbaikan PHPUnit crash akibat git ignore case-insensitive di Windows.
