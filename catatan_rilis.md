Di rilis ini, versi 22.02-premium-rev01 menyediakan [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [untuk diisi] yang terus berkontribusi. Terima kasih pula pada [untuk diisi] yang baru mulai berkontribusi.

#### Penambahan Fitur

#### Perbaikan BUG

1. [#4825](https://github.com/OpenSID/OpenSID/issues/4825) Perbaiki info media sosial tidak muncul di halaman utama.
2. [#4793](https://github.com/OpenSID/OpenSID/issues/4793) Perbaiki input data pembangunan alokasi anggaran 10 digit atau ada sebab lain.
3. [#4824](https://github.com/OpenSID/OpenSID/issues/4824) Perbaiki duplikat email ketika migrasi database.
4. [#4826](https://github.com/OpenSID/OpenSID/issues/4826) Perbaiki kolom asuransi dan no asuransi tidak terdowload di unduh export database xlsx dan import database.
5. [#4846](https://github.com/OpenSID/OpenSID/issues/4846) Perbaiki data pengguna dan penduduk.
6. [#4832](https://github.com/OpenSID/OpenSID/issues/4832) Perbaiki tambah grup pengguna baru.
7. [#4817](https://github.com/OpenSID/OpenSID/issues/4817) Perbaiki tidak dapat menyimpan data form master analisis.
8. [#4845](https://github.com/OpenSID/OpenSID/issues/4845) Perbaiki error ketika menambah anggota yang sudah terdaftar pada anggota kelompok.
9. [#4842](https://github.com/OpenSID/OpenSID/issues/4842) Perbaiki sukses input tanggal lahir mendatang.
10. [#4836](https://github.com/OpenSID/OpenSID/issues/4836) Perbaiki status perkawinan kawin tercatat semua.
11. [#4863](https://github.com/OpenSID/OpenSID/issues/4863) Perbaiki migrasi dari v20.12 umum ke v22.02-premium-beta01.
12. [#4799](https://github.com/OpenSID/OpenSID/issues/4799) Perbaiki agar tidak bisa ubah status hubungan dalam keluarga menjadi kepala keluarga jika kepala keluarga sudah meninggal.
13. [#4844](https://github.com/OpenSID/OpenSID/issues/4844) Perbaiki fields keterangan, nilai dan nilai pak pada database siskeudes.
14. [#4821](https://github.com/OpenSID/OpenSID/issues/4821) Perbaiki data penduduk/kepala keluarga yang berubah status dasar masih muncul di program bantuan.
15. [#4841](https://github.com/OpenSID/OpenSID/issues/4841) Perbaiki impor peta area persil dari KML/GPX dan buat area persil error 500.
16. [#4848](https://github.com/OpenSID/OpenSID/issues/4848) Perbaiki penambahan persil pada batasan digit nomor urut bidang.
17. [#4850](https://github.com/OpenSID/OpenSID/issues/4850) Perbaiki nama dan jenis kelamin pada surat keterangan lahir.
18. [#4828](https://github.com/OpenSID/OpenSID/issues/4828) Perbaiki layanan mandiri belum dapat masuk dengan e-ktp dan agak kesulitan pengoperasian di android.
19. [#4837](https://github.com/OpenSID/OpenSID/issues/4837) Perbaiki status kehamilan.
20. [#4869](https://github.com/OpenSID/OpenSID/issues/4869) Perbaiki upload dokumen laporan penduduk error.
21. [#90](https://github.com/OpenSID/tema-natra/issues/90) Perbaiki securimage tidak keluar di web demo berputar.
22. [#4865](https://github.com/OpenSID/OpenSID/issues/4865) Perbaiki error migrasi (ref_penduduk_hamil).
23. [#4870](https://github.com/OpenSID/OpenSID/issues/4870) Perbaiki error notifikasi login layanan mandiri, ketika pengguna belum aktif melakukan login di layanan mandiri.
24. [#4820](https://github.com/OpenSID/OpenSID/issues/4820) Perbaiki keluarga/kepala keluarga berubah status dasar pindah seluruh keluarga terdapat dalam laporan Perbaiki hasil klasifikasi analisis keluarga.
25. [#4862](https://github.com/OpenSID/OpenSID/issues/4862) Perbaiki mode production, tidak menampilkan pesan errornya.

#### Perubahan Teknis

1. Perbaiki return migrasi, tidak sesuai.
2. Perbaiki migrasi supaya tidak diulang-ulang dan laporkan yang gagal.
3. Hapus duplikasi menu_atas.
4. Perbaiki library paging.
