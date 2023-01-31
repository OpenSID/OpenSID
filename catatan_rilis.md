Di rilis ini, versi 23.02 menyediakan integrasi aplikasi stunting ke OpenSID dan fitur baru surat TinyMCE. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada balongbesuk yang terus berkontribusi.

#### Penambahan Fitur
1. [#5206](https://github.com/OpenSID/OpenSID/issues/5206) Tampilkan peringatan langganan SaaS akan berakhir.
2. [#5210](https://github.com/OpenSID/OpenSID/issues/5210) Backup folder desa secara inkremental.
3. [#4680](https://github.com/OpenSID/OpenSID/issues/4680) Integrasikan aplikasi stunting oleh komunitas ke OpenSID.
4. [#5108](https://github.com/OpenSID/OpenSID/issues/5108) Buat template surat menggunakan TinyMCE.

#### Perbaikan BUG
1. [#5249](https://github.com/OpenSID/OpenSID/issues/5249) Perbaiki impor data penduduk terbaca ganda.
2. [#5255](https://github.com/OpenSID/OpenSID/issues/5255) Perbaiki qr code surat yaang mengarah ke github opensid.
3. [#5261](https://github.com/OpenSID/OpenSID/issues/5261) Perbaiki data lampiran surat keterangan pindah penduduk tidak tampil.
4. [#5264](https://github.com/OpenSID/OpenSID/issues/5264) Perbaiki IDM Kemendes tidak dapat di perbaharui jika respon dari api tidak sesuai.
5. [#5263](https://github.com/OpenSID/OpenSID/issues/5263) Perbaiki statistik pada peta wilayah dusun tidak tampil.
6. [#5177](https://github.com/OpenSID/OpenSID/issues/5177) Perbaiki data jumlah keluarga tidak tampil pada Statistik Penduduk Laporan Bulanan.
7. [#5267](https://github.com/OpenSID/OpenSID/issues/5267) Perbaiki isian jumlah biaya pembangunan tidak bisa lebih dari 9 digit.
8. [#5265](https://github.com/OpenSID/OpenSID/issues/5265) Perbaiki duplikasi peserta program bantuan pada semua sasaran bantuan.
9. [#5269](https://github.com/OpenSID/OpenSID/issues/5269) Perbaiki error saat ubah artikel dengan grup pengguna bukan bawaan sistem.
10. [#5272](https://github.com/OpenSID/OpenSID/issues/5272) Perbaiki tidak bisa tampilkan feed.
11. [#5279](https://github.com/OpenSID/OpenSID/issues/5279) Perbaiki an/ub pada menu permohonan surat tidak muncul.
12. [#5277](https://github.com/OpenSID/OpenSID/issues/5277) Perbaiki masih bisa hapus pengguna yang sudah lakukan absensi kehadiran.
13. [#5285](https://github.com/OpenSID/OpenSID/issues/5285) Perbaiki notifikasi status langganan.
14. [#5286](https://github.com/OpenSID/OpenSID/issues/5286) Perbaiki error console [Uncaught TypeError: Cannot read properties of undefined (reading 'settings')]
15. [#5287](https://github.com/OpenSID/OpenSID/issues/5287) Perbaiki surat keterangan pindah penduduk.
16. [#5289](https://github.com/OpenSID/OpenSID/issues/5289) Perbaiki gagal install v22.06-premium di shared hosting.
17. [#5321](https://github.com/OpenSID/OpenSID/issues/5321) Merapikan informasi report ketika ada duplicate data tanggal.
18. [#5314](https://github.com/OpenSID/OpenSID/issues/5314) Membatasi Impor data penduduk dengan No KK < 16 digit akan gagal import.
19. [#5339](https://github.com/OpenSID/OpenSID/issues/5339) Mengatasi paging halaman tidak ditemukan pada album galeri yang lebih dari 10 gambar.
20. [#5341](https://github.com/OpenSID/OpenSID/issues/5341) Mengatasi tombol batal pada popup pengaturan pelanggan yang tidak berfungsi.
21. [#5322](https://github.com/OpenSID/OpenSID/issues/5322) Menambahkan keterangan & notifikasi tidak bisa input kode lembaga & kelompok.
22. [#5312](https://github.com/OpenSID/OpenSID/issues/5312) Mengatasi duplikat atas nama di pengurus & memperbarui penyesuaian a.n pamong ketika di update.
23. [#5320](https://github.com/OpenSID/OpenSID/issues/5320) Menghilangkan menu pertahanan didalam grup stunting pada pengguna.
24. [#5323](https://github.com/OpenSID/OpenSID/issues/5323) Mengatasi penambahan surat baru dengan cara menambahkannya di folder desa/template-surat.
25. [#5369](https://github.com/OpenSID/OpenSID/issues/5369) Manampilkan token dari layanan di opensid untuk dicopy oleh pengguna.
26. [#5352](https://github.com/OpenSID/OpenSID/issues/5352) Menampilkan QR Code yang tidak muncul saat surat dicetak padahal sudah diatur di pengaturan surat.
27. [#5310](https://github.com/OpenSID/OpenSID/issues/5310) Mengatasi Foto dan JK pada KK kosong yang tidak sesuai.
28. [#5370](https://github.com/OpenSID/OpenSID/issues/5370) Menambahkan validasi dan ketentuan-ketentuan yang berhubungan dengan pendaftaran kerjasama.
29. [#5377](https://github.com/OpenSID/OpenSID/issues/5377) Merapikan typo pada alert saat menambah hari libur dengan tanggal yang sama.
30. [#5379](https://github.com/OpenSID/OpenSID/issues/5379) Mengatasi error saat cetak PDF di menu cetak surat saat hanya ada a.n.
31. [#5380](https://github.com/OpenSID/OpenSID/issues/5380) Mengatasi error saat cetak surat menggunakan tinyMce.
32. [#5351](https://github.com/OpenSID/OpenSID/issues/5351) Mengatasi penginputan NIK lebih dari 16 digit saat mencetak surat domisili non warga.
33. [#5357](https://github.com/OpenSID/OpenSID/issues/5357) Mengatasi tidak bisa mengisi umur lebih dari 12 bulan pada data stunting pemantauan bulanan anak 0-2 tahun
34. [#5373](https://github.com/OpenSID/OpenSID/issues/5373) Mengatasi gambar gagal disimpan tapi alert nya berhasil disimpan.
35. [#5389](https://github.com/OpenSID/OpenSID/issues/5389) Perbaikan upload dokumen kerjasama menjadi tidak wajib.
36. [#5374](https://github.com/OpenSID/OpenSID/issues/5374) Perbaiki validasi saat input token pelanggan dan perbaiki notif yang tidak muncul.
37. [#5402](https://github.com/OpenSID/OpenSID/issues/5402) Perbaiki tampilan keterangan gambar di peta agar bisa enter ke bawah.
38. [#5390](https://github.com/OpenSID/OpenSID/issues/5390) Merubah cara backup inkremental yang memberatkan menjadi lebih ringan.
39. [#5394](https://github.com/OpenSID/OpenSID/issues/5394) Mengatasi menu aktif pada saat klik kategori lembaga.
40. [#5346](https://github.com/OpenSID/OpenSID/issues/5346) Menambahkan notifikasi gagal masuk jika akun staf / perangkat desa di non-aktifkan.
41. [#5416](https://github.com/OpenSID/OpenSID/issues/5416) Memperbaiki struktur perangkat tidak akan tampil/ tidak tersusun jika salah satu nama menggunakan tanda petik.
42. [#5390](https://github.com/OpenSID/OpenSID/issues/5390) Menambahkan daftar folder pada backup inkremental.
43. [#5180](https://github.com/OpenSID/OpenSID/issues/5180) Menampilkan icon waktu pada posting artikel & edit artikel.
44. [#5366](https://github.com/OpenSID/OpenSID/issues/5366) Memperbaiki tombol edit pada menu stunting bulanan ibu hamil.
45. [#5420](https://github.com/OpenSID/OpenSID/issues/5420) Merapikan tombol pada edit data penduduk.
46. [#5418](https://github.com/OpenSID/OpenSID/issues/5418) Memperbaiki tidak bisa hapus data hasil dari input stunting bulanan ibu hamil.
47. [#5427](https://github.com/OpenSID/OpenSID/issues/5427) Menampilkan data Logo, alamat, telepon pada 48.
48. [#5426](https://github.com/OpenSID/OpenSID/issues/5426) Memperbaiki tidak bisa menghapus surat tinymce yang dibuat baru di Pengaturan surat.
49. [#5419](https://github.com/OpenSID/OpenSID/issues/5419) Memperbaiki hasil expor DB yang tidak bisa di impor kembali krn adanya tgl 0000-000-000 00:00:00.
50. [#5325](https://github.com/OpenSID/OpenSID/issues/5325) Memperbaiki solusi croping gambar slider menjadi menyesuaikan panjang dan lebar slider.
51. [#1132](https://github.com/OpenSID/premium/issues/1132) Memperbaiki permohonan surat di layanan mandiri yang tidak bisa dibatalkan.
52. [#5268](https://github.com/OpenSID/OpenSID/issues/5268) Memperbaiki gagal buka halaman jika tidak bisa akses layanan.
53. [#5359](https://github.com/OpenSID/OpenSID/issues/5359) Memperbaiki realisasi APBDes tidak sama antara OpenSID dan Siskeudes.
54. [#5431](https://github.com/OpenSID/OpenSID/issues/5431) Menambahkan kategori di feed.
55. [#5421](https://github.com/OpenSID/OpenSID/issues/5421) Memperbaiki laporan jumlah awal bulan tidak sesuai denga akhir bulan sebelumnya.

#### Perubahan Teknis

1. [#5260](https://github.com/OpenSID/OpenSID/issues/5260) Menghilangkan Fitur Kosongkan Database.
2. Update .gitignore.
3. [#1070](https://github.com/OpenSID/premium/pull/1070) Teknis perbaiki view database blank, rubah ke error 500.
4. [#1075](https://github.com/OpenSID/premium/pull/1075) Teknis perbaiki backup tidak jalan di VPS.
5. [#1080](https://github.com/OpenSID/premium/pull/1080) Teknis perbaiki hapus fungsi yang tidak diperlukan.
6. [#109](https://github.com/OpenSID/wiki-layanan-opendesa/issues/109) Teknis tidak bisa cetak nota pelanggan jika ada layanan yg belum lunas.