Di rilis ini, versi 2312.0.0 berisi penambahan fitur tampilan anjungan yang baru dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada @ruririzal yang terus berkontribusi.


#### Penambahan Fitur

1. [#2203](https://github.com/OpenSID/premium/issues/2203) Penambahan select2 pamong infinite scroll.
2. [#2210](https://github.com/OpenSID/premium/issues/2210) Penambahan select2 penduduk cetak surat Infinite scroll.
3. [#6225](https://github.com/OpenSID/OpenSID/issues/6225) Penambahan surat TinyMCE permohonan cerai.
4. [#6540](https://github.com/OpenSID/OpenSID/issues/6540) Penambahan modul pilihan template sistem atau desa saat buat tempate surat TinyMCE.
5. [#6537](https://github.com/OpenSID/OpenSID/issues/6537) Penambahan pilihan otomatis kode isian sesuai dengan format penulisan pada surat TinyMCE.
6. [#6539](https://github.com/OpenSID/OpenSID/issues/6539) Penambahan untuk membedakan template isian dan header/footer pada surat TinyMCE.
7. [#2169](https://github.com/OpenSID/premium/issues/2169) Penambahan tampilan anjungan yang baru.
8. [#2261](https://github.com/OpenSID/premium/issues/2261) Penambahan tampilan buku tamu yang baru.


#### Perbaikan BUG

1. [#6536](https://github.com/OpenSID/OpenSID/issues/6536) Perbaikan buku tamu yang mengakibatkan gagal migrasi rilis v23.04.
2. [#6419](https://github.com/OpenSID/OpenSID/issues/6419) Perbaikan tidak bisa simpan identitas desa jika kolom operator belum tersedia.
3. [#6550](https://github.com/OpenSID/OpenSID/issues/6550) Perbaikan gagal import data penduduk menggunakan contoh data yang sudah tidak valid formatnya.
4. [#6545](https://github.com/OpenSID/OpenSID/issues/6545) Perbaikan karakter yang muncul pada notifikasi persetujuan.
5. [#6553](https://github.com/OpenSID/OpenSID/issues/6553) Perbaikan jika tidak ada versi terbaru dan tidak ada cache ijinkan null.
6. [#6557](https://github.com/OpenSID/OpenSID/issues/6557) Perbaikan gagal update identitas desa karena field kades yang diminta isi terdisable.
7. [#6528](https://github.com/OpenSID/OpenSID/issues/6528) Perbaikan query kependudukan pada mysql versi 8.
8. [#6554](https://github.com/OpenSID/OpenSID/issues/6554) Perbaikan gagal setting telegram maupun upload gambar profil.
9. [#6565](https://github.com/OpenSID/OpenSID/issues/6565) Perbaikan nama daerah kecamatan di surat keterangan nikah N1 sd N7.
10. [#6569](https://github.com/OpenSID/OpenSID/issues/6569) Perbaikan judul pratinjau surat tidak sesuai.
11. [#6570](https://github.com/OpenSID/OpenSID/issues/6570) Perbaikan tombol perbaiki arsip layanan yang membuat duplikasi tombol ubah.
12. [#6574](https://github.com/OpenSID/OpenSID/issues/6574) Perbaikan gagal buat pdf sementara saat tinjau pdf.
13. [#6580](https://github.com/OpenSID/OpenSID/issues/6580) Perbaikan Salah menampilkan pilihan tipe persentase saat ubah data dokumnetasi pembangunan.
14. [#6584](https://github.com/OpenSID/OpenSID/issues/6584) Perbaikan data apartur pada tampilan anjungan yang statis.
15. [#6583](https://github.com/OpenSID/OpenSID/issues/6583) Perbaikan error load datatables pada halaman home.
16. [#6582](https://github.com/OpenSID/OpenSID/issues/6582) Perbaikan filter user grup admin yang aktif atau grup lainnya secara bersamaan.
17. [#6579](https://github.com/OpenSID/OpenSID/issues/6579) Perbaikan paginasi tidak sesuai pada modul grup pengguna.
18. [#6607](https://github.com/OpenSID/OpenSID/issues/6607) Perbaikan pagging pada menu vaksin Laporan Penduduk.
19. [#6601](https://github.com/OpenSID/OpenSID/issues/6601) Perbaikan query data calon pemilih agar lebih cepat load halaman.
20. [#6602](https://github.com/OpenSID/OpenSID/issues/6602) Perbaikan tampilan anjungan dan buku tamu pada tampilan baru.
21. [#6636](https://github.com/OpenSID/OpenSID/issues/6636) Perbaikan timeout ketika cetak/unduh jika jumlah data cukup besar pada modul Penduduk.
22. [#6638](https://github.com/OpenSID/OpenSID/issues/6638) Perbaikan timeout ketika cetak/unduh jika jumlah data cukup besar pada modul Calon Pemilih.
23. [#6639](https://github.com/OpenSID/OpenSID/issues/6639) Perbaikan timeout ketika cetak/unduh jika jumlah data cukup besar pada modul Buku Induk Penduduk.
24. [#6640](https://github.com/OpenSID/OpenSID/issues/6640) Perbaikan timeout ketika cetak/unduh jika jumlah data cukup besar pada modul Buku KTP dan KK.
25. [#6637](https://github.com/OpenSID/OpenSID/issues/6637) Perbaikan timeout ketika cetak/unduh jika jumlah data cukup besar pada modul Keluarga.
26. [#6627](https://github.com/OpenSID/OpenSID/issues/6627) Perbaikan penamaan sesuai dengan peraturan.
27. [#6586](https://github.com/OpenSID/OpenSID/issues/6586) Perbaikan form input nama pada aparatur desa.
28. [#6650](https://github.com/OpenSID/OpenSID/issues/6650) Perbaikan muat halaman pengaturan aplikasi sangat lama ketika akses modul yang menggunakan based_64.
29. [#6654](https://github.com/OpenSID/OpenSID/issues/6654) Perbaikan penyeragaman header agar konsisten.
30. [#6658](https://github.com/OpenSID/OpenSID/issues/6658) Perbaikan session nama setelah login dan setelah perubahan profil.
31. [#6593](https://github.com/OpenSID/OpenSID/issues/6593) Perbaikan penanda tangan pada lampiran f2.01 tidak sesuai.
32. [#6665](https://github.com/OpenSID/OpenSID/issues/6665) Perbaikan validasi grup pengguna pada saat buat key opendk agar tidak membingungkan pengguna.
33. [#6635](https://github.com/OpenSID/OpenSID/issues/6635) Perbaikan timeout ketika import data penduduk jika jumlah penduduk cukup besar.
34. [#6655](https://github.com/OpenSID/OpenSID/issues/6655) Perbaikan data pada seeder pemasangan awal.
35. [#6656](https://github.com/OpenSID/OpenSID/issues/6656) Perbaikan validasi simpan nama pengguna pada halaman profil pengguna.
36. [#6585](https://github.com/OpenSID/OpenSID/issues/6585) Perbaikan data keperluan tidak tersimpan saat lakukan registrasi pada buku tamu.
37. [#6672](https://github.com/OpenSID/OpenSID/issues/6672) Perbaikan input tanggal pada form modul pengurus.
38. [#6671](https://github.com/OpenSID/OpenSID/issues/6671) Perbaikan kolom tahun pada lampiran F-2.01.
39. [#6662](https://github.com/OpenSID/OpenSID/issues/6662) Perbaikan ambil data IDM yang tersimpan pada cache.
40. [#6660](https://github.com/OpenSID/OpenSID/issues/6660) Perbaikan tampilan detail kotak masuk dan keluar pada kotak pesan layanan mandiri.
41. [#6641](https://github.com/OpenSID/OpenSID/issues/6641) Perbaikan nama dan tampilan tabel pada modul klasifikasi surat.
42. [#6661](https://github.com/OpenSID/OpenSID/issues/6661) Perbaikan hapus kategori yang memiliki sub kategori.
43. [#6685](https://github.com/OpenSID/OpenSID/issues/6685) Perbaikan cara menampilkan icon baterai langganan sesuai akses modul pelanggan.
44. [#6686](https://github.com/OpenSID/OpenSID/issues/6686) Perbaikan kesalahan penulisan atribut rquired pada surat jenis tinymce.
45. [#6682](https://github.com/OpenSID/OpenSID/issues/6682) Perbaikan navigasi kategori pada artikel [Tidak Berkategori].
46. [#6625](https://github.com/OpenSID/OpenSID/issues/6625) Perbaikan pencarian pada modul buku rekapitulasi jumlah penduduk.
47. [#6587](https://github.com/OpenSID/OpenSID/issues/6587) Perbaikan urutkan data pada modul buku administrasi pembangunan > buku kegiatan pembangunan.
48. [#6588](https://github.com/OpenSID/OpenSID/issues/6588) Perbaikan urutkan data pada modul buku administrasi pembangunan > buku rencana kerja pembangunan.
49. [#6589](https://github.com/OpenSID/OpenSID/issues/6589) Perbaikan urutkan data pada modul buku administrasi pembangunan > buku inventaris hasil-hasil pembangunan.
50. [#6645](https://github.com/OpenSID/OpenSID/issues/6645) Perbaikan performa pada data wilayah dusun.
51. [#6694](https://github.com/OpenSID/OpenSID/issues/6694) Perbaikan performa pada data wilayah rw.
52. [#6695](https://github.com/OpenSID/OpenSID/issues/6695) Perbaikan performa pada data wilayah rt.
53. [#6591](https://github.com/OpenSID/OpenSID/issues/6591) Perbaikan pencarian data pada modul satu data > dtks.
54. [#6689](https://github.com/OpenSID/OpenSID/issues/6689) Perbaikan impor data siskuedes.
55. [#6696](https://github.com/OpenSID/OpenSID/issues/6696) Perbaikan lihat dokumen pada menu informasi publik halaman website.
56. [#6693](https://github.com/OpenSID/OpenSID/issues/6693) Perbaikan tampilan widget arsip layanan.
57. [#6702](https://github.com/OpenSID/OpenSID/issues/6702) Perbaikan latar belakang halaman periksa.
58. [#6705](https://github.com/OpenSID/OpenSID/issues/6705) Perbaikan API hari libur.
59. [#6692](https://github.com/OpenSID/OpenSID/issues/6692) Perbaikan form tambah atau ubah data inventaris gedung dan bangunan.
60. [#6663](https://github.com/OpenSID/OpenSID/issues/6663) Perbaikan penyebutan sebutan desa, kepala desa dan pemerintah desa pada modul bumindes administrasi umum.
61. [#6701](https://github.com/OpenSID/OpenSID/issues/6701) Perbaikan notifikasi dan proses simpan artikel jika unggah gambar gagal.
62. [#6664](https://github.com/OpenSID/OpenSID/issues/6664) Perbaikan gagal rekamm kehadiran saat jam pulang.


#### Perubahan Teknis

1. [#2234](https://github.com/OpenSID/premium/issues/2234) Penamaan versi aplikasi.
2. [#2236](https://github.com/OpenSID/premium/issues/2236) Penyesuian instalasi awal database gabungan pada file general_helper.
3. [#2249](https://github.com/OpenSID/premium/issues/2249) Penyesuaian migrasi agar bisa digunakan pada OpenSID Database Gabungan.
4. [#6571](https://github.com/OpenSID/OpenSID/issues/6571) Load assets sweetalert2 secara global.
5. [#6605](https://github.com/OpenSID/OpenSID/issues/6605) Penyesuaian jumlah data tampailan pagging pada halaman Pendataan penerima vaksin covid-19.
6. [#6609](https://github.com/OpenSID/OpenSID/issues/6609) Penyesuaian jumlah data tampailan pagging pada halaman input data sensus/survei.
7. [#6456](https://github.com/OpenSID/OpenSID/issues/6456) Penyesuaian migrasi berulang pada pengubahan kolom id_telegram pada tabel user.
8. [#6670](https://github.com/OpenSID/OpenSID/issues/6670) Penyesuaian informasi pada notifikasi error.
9. [#6631](https://github.com/OpenSID/OpenSID/issues/6631) Penyesuaian backup dan restore database agar konsisten.
10. [#2350](https://github.com/OpenSID/premium/issues/2350) Penyesuaian notifikasi rilis dan versi yg disarankan jika masa berlangganan sudah berakhir.
11. [#6697](https://github.com/OpenSID/OpenSID/issues/6697) Penyesuaian cara menampilkan modul pada grup pengguna