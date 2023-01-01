Di rilis ini, versi 22.12.03 menyediakan Sinkronisasi data Program Bantuan dan Pembangunan ke OpenDK. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada balongbesuk yang terus berkontribusi.

#### Penambahan Fitur

1. [#34](https://github.com/OpenSID/wiki-layanan-opendesa/issues/34) Sesuaikan aturan mengaktifkan pemesanan layanan OpenSID.
2. [#4919](https://github.com/OpenSID/OpenSID/issues/4919) Munculkan isian KITAS hanya pada status WNA.
3. [#5132](https://github.com/OpenSID/OpenSID/issues/5132) Sediakan pengaturan untuk mengganti latar halaman rekam kehadiran.
4. [#5127](https://github.com/OpenSID/OpenSID/issues/5127) Wajibkan isian alamat sebelumnya pada penambahan penduduk datang / masuk.
5. [#5159](https://github.com/OpenSID/OpenSID/issues/5159) Sediakan demo akun untuk Layanan Mandiri.
6. [#5147](https://github.com/OpenSID/OpenSID/issues/5147) Sediakan metode pengenalan gawai rekam kehadiran yang lebih aman.
7. [#5004](https://github.com/OpenSID/OpenSID/issues/5004) Sederhanakan penggunaan qrcode agar tidak membebankan hosting.
8. [#4895](https://github.com/OpenSID/OpenSID/issues/4895) Sembunyikan harga jual jika status jenis mutasi "Disumbangkan" pada detail mutasi.
9. [#5014](https://github.com/OpenSID/OpenSID/issues/5014) Sediakan seeder untuk instalasi awal.
10. [#302](https://github.com/OpenSID/OpenDK/issues/302) Kirim peta desa ke OpenDK.
11. [#5085](https://github.com/OpenSID/OpenSID/issues/5085) Tambah captcha pada fitur lupa password siteman.
12. [#3909](https://github.com/OpenSID/OpenSID/issues/3909) Sinkronisasi data Pembangunan OpenSID ke OpenDK.
13. [#5208](https://github.com/OpenSID/OpenSID/issues/5208) Hapus folder desa-contoh dan buat folder desa menggunakan script.
14. [#5179](https://github.com/OpenSID/OpenSID/issues/5179) Sediakan Daftar Kontak Penduduk pada modul Hubung Warga.
15. [#5167](https://github.com/OpenSID/OpenSID/issues/5167) Tambah pengaturan logo burung garuda pada surat.
16. [#5205](https://github.com/OpenSID/OpenSID/issues/5205) Isi kode desa dari desa/config/config.php saat instalasi awal.
17. [#5209](https://github.com/OpenSID/OpenSID/issues/5209) Sediakan backup folder desa berukuran besar melalui aplikasi.
18. [#5245](https://github.com/OpenSID/OpenSID/issues/5245) Sinkronisasi data Program Bantuan OpenSID ke OpenDK.
19. [#5246](https://github.com/OpenSID/OpenSID/issues/5246) Sediakan ekspor data Program Bantuan untuk Impor di OpenDK.
20. [#5003](https://github.com/OpenSID/OpenSID/issues/5003) Sediakan tombol untuk hapus foto profil penduduk yang sudah di unggah.

#### Perbaikan BUG

1. [#5207](https://github.com/OpenSID/OpenSID/issues/5207) Perbaiki gagal impor siskuedes (Error : Data too long for column 'Keterangan' tabel keuangan_ta_spj_sisa).
2. [#5170](https://github.com/OpenSID/OpenSID/issues/5170) Perbaiki tampilan informasi staf untuk absensi pada modul pengguna.
3. [#5198](https://github.com/OpenSID/OpenSID/issues/5198) Perbaiki gagal simpan pemudik non domisili pada modul siaga covid-19.
4. [#5212](https://github.com/OpenSID/OpenSID/issues/5212) Perbaiki kirim pesan ke OpenDK jika token kosong.
5. [#4883](https://github.com/OpenSID/OpenSID/issues/4883) Perbaiki tombol kembali pada arsip desa untuk dokumen penduduk.
6. [#5217](https://github.com/OpenSID/OpenSID/issues/5217) Perbaiki pengaturan aktif/nonaktifkan halaman kehadiran yang tidak berfungsi.
7. [#5214](https://github.com/OpenSID/OpenSID/issues/5214) Perbaiki daftar dokumen surat keterangan kematian yang tampil tidak sesuai.
8. [#5176](https://github.com/OpenSID/OpenSID/issues/5176) Perbaiki akta perkawinan dan tanggal menikah ikut terhapus ketika status status kawin diubah menjadi cerai hidup / cerai mati.
9. [#5202](https://github.com/OpenSID/OpenSID/issues/5202) Sembunyikan icon pesan OpenDK pada header jika modul dimatikan.
10. [#5224](https://github.com/OpenSID/OpenSID/issues/5224) Perbaiki hari kelahiran pada lampiran Surat Keterangan Kelahiran yang tampil tidak benar.
11. [#5219](https://github.com/OpenSID/OpenSID/issues/5219) Perbaiki daftar perangkat desa di kehadiran yang tidak tampil dengan benar.
12. [#5243](https://github.com/OpenSID/OpenSID/issues/5243) Perbaiki sebutan kepala desa dan sebutan desa pada template surat yang tidak sesuai.
13. [#5249](https://github.com/OpenSID/OpenSID/issues/5249) Perbaiki impor data penduduk terbaca ganda.
14. [#5255](https://github.com/OpenSID/OpenSID/issues/5255) Perbaiki qr code surat yaang mengarah ke github opensid.
15. [#5261](https://github.com/OpenSID/OpenSID/issues/5261) Perbaiki data lampiran surat keterangan pindah penduduk tidak tampil.
16. [#5264](https://github.com/OpenSID/OpenSID/issues/5264) Perbaiki IDM Kemendes tidak dapat di perbaharui jika respon dari api tidak sesuai.
17. [#5263](https://github.com/OpenSID/OpenSID/issues/5263) Perbaiki statistik pada peta wilayah dusun tidak tampil.
18. [#5177](https://github.com/OpenSID/OpenSID/issues/5177) Perbaiki data jumlah keluarga tidak tampil pada Statistik Penduduk Laporan Bulanan.
19. [#5267](https://github.com/OpenSID/OpenSID/issues/5267) Perbaiki isian jumlah biaya pembangunan tidak bisa lebih dari 9 digit.
20. [#5265](https://github.com/OpenSID/OpenSID/issues/5265) Perbaiki duplikasi peserta program bantuan pada semua sasaran bantuan.
21. [#5269](https://github.com/OpenSID/OpenSID/issues/5269) Perbaiki error saat ubah artikel dengan grup pengguna bukan bawaan sistem.
22. [#5272](https://github.com/OpenSID/OpenSID/issues/5272) Perbaiki tidak bisa tampilkan feed.
23. [#5279](https://github.com/OpenSID/OpenSID/issues/5279) Perbaiki an/ub pada menu permohonan surat tidak muncul.
24. [#5277](https://github.com/OpenSID/OpenSID/issues/5277) Perbaiki masih bisa hapus pengguna yang sudah lakukan absensi kehadiran.
25. [#5285](https://github.com/OpenSID/OpenSID/issues/5285) Perbaiki notifikasi status langganan.
26. [#5286](https://github.com/OpenSID/OpenSID/issues/5286) Perbaiki error console [Uncaught TypeError: Cannot read properties of undefined (reading 'settings')]
27. [#5287](https://github.com/OpenSID/OpenSID/issues/5287) Perbaiki surat keterangan pindah penduduk.
28. [#5289](https://github.com/OpenSID/OpenSID/issues/5289) Perbaiki gagal install v22.06-premium di shared hosting.

#### Perubahan Teknis

1. Hapus menu login admin dan layanan mandiri pada contoh_data_awal.
2. Satukan helper underscore() dan ununderscore().
3. Perbaiki migrasi berulang tipe data kolom value dari varchar ke text.
4. Penyesuaian pengecekan rilis aplikasi OpenSID Premium dan Umum.
5. Penyesuaian repositori rilis premium dari berputar menjadi rilis-premium.
6. Lewati ganti passwod default untuk ENVIRONMENT Development.
7. Pindahkan versi kirim aplikasi ke layanan hanya jika melakukan migrasi.
8. Sesuaikan tampilan halaman home.
9. Sediakan tombol perbarui cache layanan pelanggan.
10. Sesuaikan sebutan desa pada modul identitas desa yang tidak sesuai.
11. [#5237](https://github.com/OpenSID/OpenSID/issues/5237) Sederhanakan pemanggilan tracksid dengan menghapus token_opensid, gunakan token_pantau.
12. Perpanjang layanan.
13. [#63](https://github.com/OpenSID/wiki-layanan-opendesa/issues/63) Batasi jalankan migrasi sesuai versi dan masa langganan.
14. [#1070](https://github.com/OpenSID/premium/pull/1070) Teknis perbaiki view database blank, rubah ke error 500.
15. [#1075](https://github.com/OpenSID/premium/pull/1075) Teknis perbaiki backup tidak jalan di VPS.
16. [#1080](https://github.com/OpenSID/premium/pull/1080) Teknis perbaiki hapus fungsi yang tidak diperlukan.
17. [#109](https://github.com/OpenSID/wiki-layanan-opendesa/issues/109) Teknis tidak bisa cetak nota pelanggan jika ada layanan yg belum lunas.