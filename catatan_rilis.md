#### [v21.10-premium]

Di rilis ini, versi 21.10-premium, menyediakan **Buku Kegiatan Pembangunan di Buku Administrasi Pembangunan**. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada Agung Sugiarto, afa28 dan Cahyo Wicaksono yang terus berkontribusi. Terima kasih pula pada Cahyo Wicaksono yang baru mulai berkontribusi.

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
13. [#4432](https://github.com/OpenSID/OpenSID/issues/4439) Sesuaikan hak akses level operator pada modul Pengguna.

#### Perubahan Teknis
1. Perbaiki migrasi yang gagal.
2. Sesuaikan icon peta wilayah dan widget info pelanggan.
3. Penyesuaian pengaturan basis data untuk pengguna/desa
