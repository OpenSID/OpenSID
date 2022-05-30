Di rilis ini, versi 22.05-premium-rev02 menyediakan [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [untuk diisi] yang terus berkontribusi. Terima kasih pula pada [untuk diisi] yang baru mulai berkontribusi.

#### Penambahan Fitur

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