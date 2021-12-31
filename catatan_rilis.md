Di rilis ini, versi 22.01-premium menyediakan Layanan lapor/keluhan/pengaduan bagi warga/umum. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada @andifahruddinakas, @agungsugiarto, @FirlianiF, @scqolbu24, @FaisyalRachman dan @apidong yang terus berkontribusi. Terima kasih pula pada @totoprayogo1916 dan @raamaj yang baru mulai berkontribusi.

#### Penambahan Fitur

1. [#4556](https://github.com/OpenSID/OpenSID/issues/4556) Tambahkan akun telegram ke data penduduk.
2. [#4518](https://github.com/OpenSID/OpenSID/issues/4518) Tambahkan lampiran surat keterangan pindah datang penduduk F-1.27, F-1.31, F-1.39.
3. [#4226](https://github.com/OpenSID/OpenSID/issues/4226) Perbaharui versi terbaru blanko pindah datang F.1.03.
4. [#4553](https://github.com/OpenSID/OpenSID/issues/4553) Tampilkan lapak di layanan mandiri/anjungan.
5. [#4506](https://github.com/OpenSID/OpenSID/issues/4506) Tambah tampilan slide gambar/video di anjungan pada saat tidak digunakan.
6. [#3947](https://github.com/OpenSID/OpenSID/issues/3947) Saat masuk layanan mandiri diarahkan ke halaman buat surat (standar) atau ke halaman daftar pesan masuk jika terdapat pesan masuk yg belum dibaca.
7. [#4507](https://github.com/OpenSID/OpenSID/issues/4507) Bisa atur jenis garis dan ketebalan tipe garis di pengaturan peta.
8. [#4558](https://github.com/OpenSID/OpenSID/issues/4558) Verifikasi akun Telegram di layanan mandiri.
9. [#4561](https://github.com/OpenSID/OpenSID/issues/4561) Notifikasi Sesuaikan pemberitahuan adanya rilis baru.
10. [#3876](https://github.com/OpenSID/OpenSID/issues/3876) Tambahkan "pintasan" pengaturan / informasi untuk surat layanan mandiri dan syarat surat.
11. [#4584](https://github.com/OpenSID/OpenSID/issues/4584) Laporkan jika exec tidak tersedia pada waktu mencetak PDF.
12. [#3663](https://github.com/OpenSID/OpenSID/issues/3663) Penambahan Fitur kirim PIN baru melalui telegram ke warga.
13. [#4411](https://github.com/OpenSID/OpenSID/issues/4411) Tambah aksi lihat dokumen pada Buku Administrasi Umum (Peraturan Desa, Keputusan Kepala Desa, Surat Masuk).
14. [#4622](https://github.com/OpenSID/OpenSID/issues/4622) Tidak boleh hapus data penduduk kalau sudah menggantikan data contoh awal atau data penduduk sudah dinyatakan lengkap.
15. [#512](https://github.com/OpenSID/OpenSID/issues/512) Layanan lapor/keluhan/pengaduan bagi warga/umum.
16. [#4180](https://github.com/OpenSID/OpenSID/issues/4180) Buku inventaris hasil-hasil pembangunan di Buku Administrasi Pembangunan.
17. [#4610](https://github.com/OpenSID/OpenSID/issues/4610) Pendataan warga penerima vaksin.
18. [#4497](https://github.com/OpenSID/OpenSID/issues/4497) Surat keterangan nikah & form F2.12 untuk warga bukan muslim.
19. [#4557](https://github.com/OpenSID/OpenSID/issues/4557) Lupa/reset PIN dengan telegram.

#### Perbaikan BUG

1. [#4560](https://github.com/OpenSID/OpenSID/issues/4560) Perbaiki migrasi untuk nik dan no_kk sementara kadang gagal untuk kasus tertentu.
2. [#463](https://github.com/OpenSID/premium/issues/463) Perbaiki devtoolbar agar tidak menggangu proses compress saat cetak surat.
3. [#4565](https://github.com/OpenSID/OpenSID/issues/4565) Perbaiki foto pembangunan terlihat besar.
4. [#4567](https://github.com/OpenSID/OpenSID/issues/4567) Perbaiki migrasi slug pembangunan tidak berjalan keseluruhan.
5. [#4566](https://github.com/OpenSID/OpenSID/issues/4566) Perbaiki login ektp dan persuratan di layanan mandiri.
6. [#4554](https://github.com/OpenSID/OpenSID/issues/4554) Sesuaikan view verifikasi surat berdasarkan tema yang digunakan.
7. [#4568](https://github.com/OpenSID/OpenSID/issues/4568) Perbaiki gagal saat mengakses modul rekapitulasi jumlah penduduk.
8. [#4549](https://github.com/OpenSID/OpenSID/issues/4549) Perbaiki gagal di penduduk penerima bantuan, tidak kompatibel.
9. [#4570](https://github.com/OpenSID/OpenSID/issues/4570) Perbaiki gagal tambah / ubah kader dengan menggunakan .htaccess.
10. [#4378](https://github.com/OpenSID/OpenSID/issues/4378) Penyesuaian daftar surat ubahan desa secara otomatis.
11. [#4392](https://github.com/OpenSID/OpenSID/issues/4392) Perbaiki status perkwinan modul penduduk dan keluarga.
12. [#4564](https://github.com/OpenSID/OpenSID/issues/4564) Perbaiki pengaturan peta yang tidak dapat menyimpan warna.
13. [#4585](https://github.com/OpenSID/OpenSID/issues/4585) Perbaiki tampilkan notifikasi gagal, jika widget gagal disimpan beserta penyebabnya.
14. [#4598](https://github.com/OpenSID/OpenSID/issues/4598) Perbaiki gagal impor SIAK.
15. [#4542](https://github.com/OpenSID/OpenSID/issues/4542) Perbaiki cetak surat kelahiran.
16. [#4576](https://github.com/OpenSID/OpenSID/issues/4576) Perbaiki grup pengguna.
17. [#2767](https://github.com/OpenSID/OpenSID/issues/2767) Perbaiki menu sidebar,collapse ketika kita pilih menu.
18. [#4596](https://github.com/OpenSID/OpenSID/issues/4596) Perbaiki suplemen tidak dapat impor data suplemen sasaran keluarga
19. [#4595](https://github.com/OpenSID/OpenSID/issues/4595) Perbaiki ubah panjang value pada kolom tag_id_card.
20. [#4593](https://github.com/OpenSID/OpenSID/issues/4593) Perbaiki gagal saat menyimpan peta, dengan data yg tidak lengkap.
21. [#4574](https://github.com/OpenSID/OpenSID/issues/4574) Tampilkan data artikel sesuai dengan pengaturan Grup dan Pengguna.
22. [#4600](https://github.com/OpenSID/OpenSID/issues/4600) Perbaiki import data penduduk berulang.
23. [#4602](https://github.com/OpenSID/OpenSID/issues/4602) Tampilkan status desa terdaftar pada layanan pelanggan.
24. [#4611](https://github.com/OpenSID/OpenSID/issues/4611) Perbaiki nilai satuan laporan hasil analisis.
25. [#4623](https://github.com/OpenSID/OpenSID/issues/4623) Perbaiki jumlah data suplemen yang tampil di halaman web tidak sesuai.

#### Perubahan Teknis

1. [#4583](https://github.com/OpenSID/OpenSID/issues/4583) Perbaiki .htaccess untuk file .zip dan .rar / Backup folder desa lewat hosting.
2. [#4588](https://github.com/OpenSID/OpenSID/issues/4588) Loloskan pemeriksaan token di setting demo.
3. Pindahkan style css kedalam HEAD di login Admin.
4. Pisahkan Konfigurasi DevToolBar.
5. [#4571](https://github.com/OpenSID/OpenSID/issues/4571) Pulihkan website demo menggunakan penjadwalan (mis. Cron Job, Cron Tab, atau lainnya).
