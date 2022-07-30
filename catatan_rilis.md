Di rilis ini, versi 22.07-premium-beta04 menyediakan [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.
Terima kasih pada [untuk diisi] yang terus berkontribusi.

#### Penambahan Fitur

1. [#5311](https://github.com/OpenSID/OpenSID/issues/5311) Bersihkan cache blade setiap selesai migrasi.
2. [#5307](https://github.com/OpenSID/OpenSID/issues/5307) Menambahkan masa berlaku surat agar bisa dipilih untuk dihilangkan atau ditampilkan pada form surat.
3. [#5306](https://github.com/OpenSID/OpenSID/issues/5306) Mengalihkan ke arsip atau daftar surat setelah cetak surat.
4. [#5313](https://github.com/OpenSID/OpenSID/issues/5313) Periksa database pengguna dan kata sandi bawaan.
5. [#5305](https://github.com/OpenSID/OpenSID/issues/5305) Menambahkan pengaturan jenis huruf bawaan.
6. [#5405](https://github.com/OpenSID/OpenSID/issues/5405) Menambahkan info email yg terkirim untuk verifikasi pendaftaran kerjasama.
7. [#5409](https://github.com/OpenSID/OpenSID/issues/5409) Surat yang sudah selesai dibuatkan pdf dan hasilnya tersimpan di dalam server.

#### Perbaikan BUG

1. [#5321](https://github.com/OpenSID/OpenSID/issues/5321) Merapikan informasi report ketika ada duplicate data tanggal.
2. [#5314](https://github.com/OpenSID/OpenSID/issues/5314) Membatasi Impor data penduduk dengan No KK < 16 digit akan gagal import.
3. [#5339](https://github.com/OpenSID/OpenSID/issues/5339) Mengatasi paging halaman tidak ditemukan pada album galeri yang lebih dari 10 gambar.
4. [#5341](https://github.com/OpenSID/OpenSID/issues/5341) Mengatasi tombol batal pada popup pengaturan pelanggan yang tidak berfungsi.
5. [#5322](https://github.com/OpenSID/OpenSID/issues/5322) Menambahkan keterangan & notifikasi tidak bisa input kode lembaga & kelompok.
6. [#5312](https://github.com/OpenSID/OpenSID/issues/5312) Mengatasi duplikat atas nama di pengurus & memperbarui penyesuaian a.n pamong ketika di update.
7. [#5320](https://github.com/OpenSID/OpenSID/issues/5320) Menghilangkan menu pertahanan didalam grup stunting pada pengguna.
8. [#5323](https://github.com/OpenSID/OpenSID/issues/5323) Mengatasi penambahan surat baru dengan cara menambahkannya di folder desa/template-surat.
9. [#5369](https://github.com/OpenSID/OpenSID/issues/5369) Manampilkan token dari layanan di opensid untuk dicopy oleh pengguna.
10. [#5352](https://github.com/OpenSID/OpenSID/issues/5352) Menampilkan QR Code yang tidak muncul saat surat dicetak padahal sudah diatur di pengaturan surat.
11. [#5310](https://github.com/OpenSID/OpenSID/issues/5310) Mengatasi Foto dan JK pada KK kosong yang tidak sesuai.
12. [#5370](https://github.com/OpenSID/OpenSID/issues/5370) Menambahkan validasi dan ketentuan-ketentuan yang berhubungan dengan pendaftaran kerjasama.
13. [#5377](https://github.com/OpenSID/OpenSID/issues/5377) Merapikan typo pada alert saat menambah hari libur dengan tanggal yang sama.
14. [#5379](https://github.com/OpenSID/OpenSID/issues/5379) Mengatasi error saat cetak PDF di menu cetak surat saat hanya ada a.n.
15. [#5380](https://github.com/OpenSID/OpenSID/issues/5380) Mengatasi error saat cetak surat menggunakan tinyMce.
16. [#5351](https://github.com/OpenSID/OpenSID/issues/5351) Mengatasi penginputan NIK lebih dari 16 digit saat mencetak surat domisili non warga.
17. [#5357](https://github.com/OpenSID/OpenSID/issues/5357) Mengatasi tidak bisa mengisi umur lebih dari 12 bulan pada data stunting pemantauan bulanan anak 0-2 tahun
18. [#5373](https://github.com/OpenSID/OpenSID/issues/5373) Mengatasi gambar gagal disimpan tapi alert nya berhasil disimpan.
19. [#5389](https://github.com/OpenSID/OpenSID/issues/5389) Perbaikan upload dokumen kerjasama menjadi tidak wajib.
20. [#5374](https://github.com/OpenSID/OpenSID/issues/5374) Perbaiki validasi saat input token pelanggan dan perbaiki notif yang tidak muncul.
21. [#5402](https://github.com/OpenSID/OpenSID/issues/5402) Perbaiki tampilan keterangan gambar di peta agar bisa enter ke bawah.
22. [#5390](https://github.com/OpenSID/OpenSID/issues/5390) Merubah cara backup inkremental yang memberatkan menjadi lebih ringan.
23. [#5394](https://github.com/OpenSID/OpenSID/issues/5394) Mengatasi menu aktif pada saat klik kategori lembaga.
24. [#5346](https://github.com/OpenSID/OpenSID/issues/5346) Menambahkan notifikasi gagal masuk jika akun staf / perangkat desa di non-aktifkan.
25. [#5416](https://github.com/OpenSID/OpenSID/issues/5416) Memperbaiki struktur perangkat tidak akan tampil/ tidak tersusun jika salah satu nama menggunakan tanda petik.
26. [#5390](https://github.com/OpenSID/OpenSID/issues/5390) Menambahkan daftar folder pada backup inkremental.
27. [#5180](https://github.com/OpenSID/OpenSID/issues/5180) Menampilkan icon waktu pada posting artikel & edit artikel.
28. [#5366](https://github.com/OpenSID/OpenSID/issues/5366) Memperbaiki tombol edit pada menu stunting bulanan ibu hamil.
29. [#5420](https://github.com/OpenSID/OpenSID/issues/5420) Merapikan tombol pada edit data penduduk.
30. [#5418](https://github.com/OpenSID/OpenSID/issues/5418) Memperbaiki tidak bisa hapus data hasil dari input stunting bulanan ibu hamil.
31. [#5427](https://github.com/OpenSID/OpenSID/issues/5427) Menampilkan data Logo, alamat, telepon pada halaman maintenace ketika mode offline yang sebelumnya tidak tampil.
32. [#5426](https://github.com/OpenSID/OpenSID/issues/5426) Memperbaiki tidak bisa menghapus surat tinymce yang dibuat baru di Pengaturan surat.
33. [#5419](https://github.com/OpenSID/OpenSID/issues/5419) Memperbaiki hasil expor DB yang tidak bisa di impor kembali krn adanya tgl 0000-000-000 00:00:00.
34. [#5325](https://github.com/OpenSID/OpenSID/issues/5325) Memperbaiki solusi croping gambar slider menjadi menyesuaikan panjang dan lebar slider.
35. [#1132](https://github.com/OpenSID/premium/issues/1132) Memperbaiki permohonan surat di layanan mandiri yang tidak bisa dibatalkan.
36. [#5268](https://github.com/OpenSID/OpenSID/issues/5268) Memperbaiki gagal buka halaman jika tidak bisa akses layanan.

#### Perubahan Teknis

1. [#5260](https://github.com/OpenSID/OpenSID/issues/5260) Menghilangkan fitur kosongkan database.
2. Update .gitignore.
