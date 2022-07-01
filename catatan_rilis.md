Di rilis ini, versi 22.07-premium menyediakan integrasi aplikasi stunting ke OpenSID dan fitur baru surat TinyMCE. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

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
#### Perubahan Teknis

1. [#1070](https://github.com/OpenSID/premium/pull/1070) Teknis perbaiki view database blank, rubah ke error 500.
2. [#1075](https://github.com/OpenSID/premium/pull/1075) Teknis perbaiki backup tidak jalan di VPS.
3. [#1080](https://github.com/OpenSID/premium/pull/1080) Teknis perbaiki hapus fungsi yang tidak diperlukan.
4. [#109](https://github.com/OpenSID/wiki-layanan-opendesa/issues/109) Teknis tidak bisa cetak nota pelanggan jika ada layanan yg belum lunas.
