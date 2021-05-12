#### [v21.05-premium-pasca - 2021-05-12](https://github.com/OpenSID/premium/compare/v21.05-premium...rilis-dev)

Di rilis ini, versi 21.05-premium-pasca, menyediakan [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada afa28 yang terus berkontribusi. Terima kasih pula pada [untuk diisi] yang baru mulai berkontribusi.

Lengkapnya, isi rilis versi v21.05-premium - 2021-05-12 adalah sebagai berikut:

#### Penambahan Fitur
1. [#4137](https://github.com/OpenSID/OpenSID/issues/4137) Sekarang Status IDM Desa bisa ditampilkan untuk tahun pilihan.
2. [#4171](https://github.com/OpenSID/OpenSID/issues/4171) Di Pengaturan > QR Code scan tombol Kunjungi Website sekarang hanya tampil jika ada url valid di isi QR Code yg di-scan.
3. [#4183](https://github.com/OpenSID/OpenSID/issues/4183) Tampilkan / Sembunyikan PIN saat Ganti PIN Layanan Mandiri.
4. [#3918](https://github.com/OpenSID/OpenSID/issues/3918) Sekarang bisa kirim pesan belum lengkap pada waktu memeriksa permohonan surat layanan mandiri.
5. [#4124](https://github.com/OpenSID/OpenSID/issues/4124) Sekarang jabatan Kependudukan > Kelompok dapat diisi secara manual.
6. [#4055](https://github.com/OpenSID/OpenSID/issues/4055) Sekarang bisa ambil foto penduduk anggota kelompok menggunakan kamera HP/webcam.


#### Perbaikan BUG
1. [#4162](https://github.com/OpenSID/OpenSID/issues/4162) Batasi tidak bisa ubah data Penduduk, Keluarga, Kelompok, Data Suplemen, Layanan Surat dan Rumah Tangga kalau pengguna tidak mempunyai hak ubah.
2. [#4170](https://github.com/OpenSID/OpenSID/issues/4170) Sekarang data keluarga tersimpan normal pada impor data penduduk.
3. [#4165](https://github.com/OpenSID/OpenSID/issues/4165) Sekarang penandatangan tampil benar di cetak/unduh Buku Agenda - Surat Masuk dan Buku Ekspedisi.
4. [#4167](https://github.com/OpenSID/OpenSID/issues/4167) Sekarang laporan cetak/unduh Sekretariat > Informasi Publik ada blok penandatangan.
5. [#4172](https://github.com/OpenSID/OpenSID/issues/4172) Data rincian peserta bantuan sekarang tampil benar di Layanan Mandiri.
6. [#4168](https://github.com/OpenSID/OpenSID/issues/4168) Pindahkan Buku Tanah di Desa dan Buku Tanah Kas Desa ke Buku Administrasi Umum.
7. Perbaiki jumlah luas total dan pemeriksaan rincian luas di Buku Tanah di Desa.
8. [#4184](https://github.com/OpenSID/OpenSID/issues/4184) Nama desa sekarang tampil di cetak laporan Sekretariat > Informasi Publik.
9. [#4186](https://github.com/OpenSID/OpenSID/issues/4186) Munculkan keyboard virtual di anjungan bagi semua surat bawaan sistem yang tersedia di Layanan Mandiri.


#### Perubahan Teknis
1. Perbaiki penulisan script Buku Tanah di Desa dan Buku Kas Tanah Desa.
2. Gunakan modal cetak global.
3. Sesuaikan migrasi dengan versi database.
4. Sederhanakan query modul Pembangunan.
5. Sesuaikan link status SDGs Desa supaya menggunakan format slug menu lainnya dan tidak perlu diawali 'first'.
6. Sederhanakan dan mengurangi duplikasi script pada Layanan Mandiri.
7. Hapus script tidak digunakan di modul Layanan Surat.
