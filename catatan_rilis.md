Di rilis ini, versi 2410.0.0 berisi kode isian dan beberepa lampiran serta perbaikan lain yang diminta Komunitas SID.

#### FITUR

1. [#7689](https://github.com/OpenSID/OpenSID/issues/7689) Penambahan kode isian data penduduk pada tinymce.
2. [#7635](https://github.com/OpenSID/OpenSID/issues/7635) Penambahan kaitkan kategori isian dengan kondisi pilihan referensi kode isian.
3. [#7710](https://github.com/OpenSID/OpenSID/issues/7710) Penambahan pilihan lampiran yang akan dicetak pada pengisian cetak surat.
4. [#7284](https://github.com/OpenSID/OpenSID/issues/7284) Penambahan kelengkapan data lampiran F-2.01 saat cetak.
5. [#7043](https://github.com/OpenSID/OpenSID/issues/7043) Penambahan lampiran F-2.30.
6. [#6116](https://github.com/OpenSID/OpenSID/issues/6116) Penerapan Kode/ klasifikasi Surat/arsip Sesuai Peraturan Menteri Dalam Negeri Nomor 83 Tahun 2022.
6. [#7712](https://github.com/OpenSID/OpenSID/issues/7712) Penambahan otomatis isi data dari pengaturan hubungkan data antar form kategori surat.


#### BUG

1. [#7679](https://github.com/OpenSID/OpenSID/issues/7679) Perbaikan validasi luas tanah pada tambah data persil modul pertanahan.
2. [#7694](https://github.com/OpenSID/OpenSID/issues/7694) Perbaikan router tidak ditemukan.
3. [#7690](https://github.com/OpenSID/OpenSID/issues/7690) Perbaikan menampilkan daftar menu bertingkat.
4. [#7634](https://github.com/OpenSID/OpenSID/issues/7634) Perbaikan penanda tangan pada lampiran surat keterangan nikah.
5. [#7695](https://github.com/OpenSID/OpenSID/issues/7695) Perbaikan data keluarga yang ditampilkan pada surat keterangan pindah.
6. [#7692](https://github.com/OpenSID/OpenSID/issues/7692) Perbaikan data lampiran f-1.08.
7. [#7686](https://github.com/OpenSID/OpenSID/issues/7686) Perbaikan data awal modul widget.
8. [#7688](https://github.com/OpenSID/OpenSID/issues/7688) Perbaikan akses hapus kategori pada artikel statis.
9. [#7698](https://github.com/OpenSID/OpenSID/issues/7698) Perbaikan menampilkan bagan pengurus / pemerintah desa.
10. [#7685](https://github.com/OpenSID/OpenSID/issues/7685) Perbaikan besar kecil huruf pada gelar nama camat data kode isian surat.
11. [#7701](https://github.com/OpenSID/OpenSID/issues/7701) Perbaikan hak akses beberapa modul pada view.
12. [#7714](https://github.com/OpenSID/OpenSID/issues/7714) Perbaikan url/navigasi submodul kategori lembaga/kelompok.
13. [#7709](https://github.com/OpenSID/OpenSID/issues/7709) Perbaikan data kode isian nama desa yang menggunakan romawi.
14. [#7702](https://github.com/OpenSID/OpenSID/issues/7702) Perbaikan menampilkan foto pada modul pengurus.
15. [#7716](https://github.com/OpenSID/OpenSID/issues/7716) Perbaikan pratinjau surat dengan status konsep.
16. [#7719](https://github.com/OpenSID/OpenSID/issues/7719) Perbaikan pengecekan perangkat kehadiran yang terdaftar.
17. [#7664](https://github.com/OpenSID/OpenSID/issues/7664) Perbaikan fungsi dragable pada versi mobile.
18. [#7728](https://github.com/OpenSID/OpenSID/issues/7728) Perbaikan ejaan provinsi pada lampiran surat.
19. [#7727](https://github.com/OpenSID/OpenSID/issues/7727) Perbaikan menampilkan detail data surat perorangan dari modul arsip desa.
20. [#7720](https://github.com/OpenSID/OpenSID/issues/7720) Perbaikan verifikasi google captha jika tidak valid.
21. [#7730](https://github.com/OpenSID/OpenSID/issues/7730) Perbaikan menampilkan data galeri pada halaman web.
22. [#7722](https://github.com/OpenSID/OpenSID/issues/7722) Perbaikan strict count array php 8.x pada lampiran dengan data pengikut tidak ada.
22. [#7735](https://github.com/OpenSID/OpenSID/issues/7735) Perbaikan pencarian kumpulan KK pada modul keluarga.
23. [#7740](https://github.com/OpenSID/OpenSID/issues/7740) Perbaikan impor data program bantuan.


#### TEKNIS

1. [#7588](https://github.com/OpenSID/OpenSID/issues/7588) Penyesuaian modul Administrasi Pembangunan (Buku Rencana kerja Pembangunan, Buku Kegiatan Pembangunan dan Buku Inventaris hasil-Hasil Pembangunan) menggunakan ORM dan Blade Laravel.
2. [#7713](https://github.com/OpenSID/OpenSID/issues/7713) Penyesuaian pengaturan ukuran bagan serangam dengan yang lain, berada di pojok kanan atas.
3. [#7683](https://github.com/OpenSID/OpenSID/issues/7683) Penyesuaian modul Info Sistem menggunakan ORM dan Blade Laravel.
4. [#7684](https://github.com/OpenSID/OpenSID/issues/7684) Penyesuaian modul Buku Ekspedisi menggunakan ORM dan Blade Laravel.
5. [#7706](https://github.com/OpenSID/OpenSID/issues/7706) Penyesuaian modul Buku Induk Penduduk, Buku Mutasi Penduduk Desa dan Buku Penduduk Sementara menggunakan ORM dan Blade Laravel.
6. [#76827682](https://github.com/OpenSID/OpenSID/issues/76827682) Penyesuaian modul QRCode menggunakan ORM dan Blade Laravel.


#### KEAMANAN

1. [#3506](https://github.com/OpenSID/premium/issues/3506) Peningkatan keamanan unggah foto widget.


#### INFO PENTING
- Pada rilis ini, penggunaan surat jenis RTF tidak didukung dan tidak dikembangkan lagi. Silahkan beralih dan gunakan surat jenis TinyMCE yang sudah dikembangkan