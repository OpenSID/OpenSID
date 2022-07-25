Di rilis ini, versi 22.07-premium-beta03 menyediakan [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.
Terima kasih pada [untuk diisi] yang terus berkontribusi.

#### Penambahan Fitur
1. [#5311](https://github.com/OpenSID/OpenSID/issues/5311) [Cache Blade] Bersihkan Cache Blade Setiap Selesai Migrasi
2. [#5307](https://github.com/OpenSID/OpenSID/issues/5307) [Pengaturan Surat] Menambahkan Masa berlaku surat agar bisa dipilih untuk dihilangkan atau ditampilkan pada form surat
3. [#5306](https://github.com/OpenSID/OpenSID/issues/5306) [TinyMCE] Ridirect Ke Arsip / Daftar Surat Setelah Cetak Surat
4. [#5313](https://github.com/OpenSID/OpenSID/issues/5313) [Periksa Database] User dan Password Default (Statis)
5. [#5305](https://github.com/OpenSID/OpenSID/issues/5305) [TinyMCE] Menambahkan Pengaturan Default Font
6. [#5313](https://github.com/OpenSID/OpenSID/issues/5313) [Periksa Database] Menambahkan Fungsi Melewati pemeriksaan syarat sandi

#### Perbaikan BUG
1. [#5321](https://github.com/OpenSID/OpenSID/issues/5321) Merapikan informasi report ketika ada duplicate data tanggal
2. [#5314](https://github.com/OpenSID/OpenSID/issues/5314) Membatasi Impor data penduduk dengan No KK < 16 digit akan gagal import
3. [#5339](https://github.com/OpenSID/OpenSID/issues/5339) Mengatasi paging halaman tidak ditermukan pada album galeri yang lebih dari 10 gambar
4. [#5341](https://github.com/OpenSID/OpenSID/issues/5341) Mengatasi tombol batal pada popup pengaturan pelanggan yang tidak berfungsi
5. [#5322](https://github.com/OpenSID/OpenSID/issues/5322) Menambahkan keterangan & notifikasi tidak bisa input kode lembaga & kelompok
6. [#5312](https://github.com/OpenSID/OpenSID/issues/5312) Mengatasi duplikat atas nama di pengurus & memperbarui penyesuaian A.n pamong ketika di update
7. [#5320](https://github.com/OpenSID/OpenSID/issues/5320) Menghilangkan menu pertahanan didalam grup stunting pada pengguna
8. [#5323](https://github.com/OpenSID/OpenSID/issues/5323) Mengatasi penambahan surat baru dengan cara menambahkannya di folder desa/template-surat
9. [#5369](https://github.com/OpenSID/OpenSID/issues/5369) Manampilkan token dari layanan di opensid untuk dicopy oleh pengguna issue-5352
10. [#5352](https://github.com/OpenSID/OpenSID/issues/5352) Menampilkan QR Code yang tidak muncul saat surat dicetak padahal sudah diatur di pengaturan surat
11. [#5310](https://github.com/OpenSID/OpenSID/issues/5310) Mengatasi Foto dan JK pada KK kosong yang tidak sesuai  
12. [#5370](https://github.com/OpenSID/OpenSID/issues/5370) Menambahkan validasi dan ketentuan-ketentuan yang berhubungan dengan pendaftaran kerjasama
13. [#5377](https://github.com/OpenSID/OpenSID/issues/5377) Merapikan typo pada alert saat menambah hari libur dengan tanggal yang sama
14. [#5379](https://github.com/OpenSID/OpenSID/issues/5379) Mengatasi error saat cetak PDF di menu cetak surat saat hanya ada a.n
15. [#5380](https://github.com/OpenSID/OpenSID/issues/5380) Mengatasi error saat cetak surat menggunakan tinyMce
16. [#5351](https://github.com/OpenSID/OpenSID/issues/5351) Mengatasi penginputan NIK lebih dari 16 digit saat mencetak surat domisili non warga
17. [#5357](https://github.com/OpenSID/OpenSID/issues/5357) Mengatasi tidak bisa mengisi umur lebih dari 12 bulan pada data stunting pemantauan bulanan anak 0-2 tahun
18. [#5373](https://github.com/OpenSID/OpenSID/issues/5373) Mengatasi gambar gagal disimpan tapi alert nya berhasil disimpan
19. [#5389](https://github.com/OpenSID/OpenSID/issues/5389) Perbaikan upload dokumen kerjasama menjadi tidak wajib
20. [#5374](https://github.com/OpenSID/OpenSID/issues/5374) Perbaiki validasi saat input token pelanggan dan perbaiki notif yang tidak muncul
21. [#5402](https://github.com/OpenSID/OpenSID/issues/5402) Perbaiki tampilan keterangan gambar di peta agar bisa enter ke bawah
22. [#5390](https://github.com/OpenSID/OpenSID/issues/5390) Merubah cara backup inkremental yang memberatkan menjadi lebih ringan

#### Perubahan Teknis
1. [#5260](https://github.com/OpenSID/OpenSID/issues/5260) Menghilangkan Fitur Kosongkan Database
2. Update .gitignore
