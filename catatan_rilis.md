Di rilis ini, versi 22.05-premium-rev01 menyediakan [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [untuk diisi] yang terus berkontribusi. Terima kasih pula pada [untuk diisi] yang baru mulai berkontribusi.

#### Penambahan Fitur

1. [#34](https://github.com/OpenSID/wiki-layanan-opendesa/issues/34) Sesuaikan aturan mengaktifkan pemesanan layanan OpenSID.
2. [#4919](https://github.com/OpenSID/OpenSID/issues/4919) Munculkan isian KITAS hanya pada status WNA.
3. [#5132](https://github.com/OpenSID/OpenSID/issues/5132) Sediakan pengaturan untuk mengganti latar halaman rekam kehadiran.
4. [#5127](https://github.com/OpenSID/OpenSID/issues/5127) Wajibkan isian alamat sebelumnya pada penambahan penduduk datang / masuk.
5. [#5159](https://github.com/OpenSID/OpenSID/issues/5159) Sediakan demo akun untuk Layanan Mandiri.
6. [#5147](https://github.com/OpenSID/OpenSID/issues/5147) Sediakan metode pengenalan gawai rekam kehadiran yang lebih aman.


#### Perbaikan BUG
1. [#5207](https://github.com/OpenSID/OpenSID/issues/5207) Perbaiki gagal impor siskuedes (Error : Data too long for column 'Keterangan' tabel keuangan_ta_spj_sisa).
2. [#5170](https://github.com/OpenSID/OpenSID/issues/5170) Perbaiki tampilan informasi staf untuk absensi pada modul pengguna.
3. [#5198](https://github.com/OpenSID/OpenSID/issues/5198) Perbaiki gagal simpan pemudik non domisili pada modul siaga covid-19.
4. [#5212](https://github.com/OpenSID/OpenSID/issues/5212) Perbaiki kirim pesan ke OpenDK jika token kosong.
5. [#4883](https://github.com/OpenSID/OpenSID/issues/4883) Perbaiki tombol kembali pada arsip desa untuk dokumen penduduk.
6. [#5217](https://github.com/OpenSID/OpenSID/issues/5217) Perbaiki pengaturan aktif/nonaktifkan halaman kehadiran yang tidak berfungsi.
7. [#5214](https://github.com/OpenSID/OpenSID/issues/5214) Perbaiki daftar dokumen surat keterangan kematian yang tampil tidak sesuai.

#### Perubahan Teknis

1. Hapus menu login admin dan layanan mandiri pada contoh_data_awal.
2. Satukan helper underscore() dan ununderscore().
3. Perbaiki migrasi berulang tipe data kolom value dari varchar ke text.
4. Penyesuaian pengecekan rilis aplikasi OpenSID Premium dan Umum.
5. Penyesuaian repositori rilis premium dari berputar menjadi rilis-premium.
6. Lewati ganti passwod default untuk ENVIRONMENT Development.
