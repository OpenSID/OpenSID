#### [v21.05-premium-beta - 2021-05-20](https://github.com/OpenSID/premium/compare/v21.05-premium...rilis-dev)

Di rilis ini, versi 21.05-premium-beta, menyediakan [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada afa28 yang terus berkontribusi. Terima kasih pula pada [untuk diisi] yang baru mulai berkontribusi.

Lengkapnya, isi rilis versi v21.05-premium-beta - 2021-05-20 adalah sebagai berikut:


#### Penambahan Fitur
1. [#4137](https://github.com/OpenSID/OpenSID/issues/4137) Sekarang Status IDM Desa bisa ditampilkan untuk tahun pilihan.
2. [#4171](https://github.com/OpenSID/OpenSID/issues/4171) Di Pengaturan > QR Code scan tombol Kunjungi Website sekarang hanya tampil jika ada url valid di isi QR Code yg di-scan.
3. [#4183](https://github.com/OpenSID/OpenSID/issues/4183) Tampilkan / Sembunyikan PIN saat Ganti PIN Layanan Mandiri.
4. [#3918](https://github.com/OpenSID/OpenSID/issues/3918) Sekarang bisa kirim pesan belum lengkap pada waktu memeriksa permohonan surat layanan mandiri.
5. [#4124](https://github.com/OpenSID/OpenSID/issues/4124) Sekarang jabatan Kependudukan > Kelompok dapat diisi secara manual.
6. [#4055](https://github.com/OpenSID/OpenSID/issues/4055) Sekarang bisa ambil foto penduduk anggota kelompok dan Pemerintahan Desa menggunakan kamera HP/webcam.
7. [#4037](https://github.com/OpenSID/OpenSID/issues/4037) Pengguna Layanan Mandiri sekarang dapat memberi penilaian kepuasan pelayanan.
8. Tampilkan isi logs di Pengaturan > Info Sistem, untuk memudahkan pengguna melaporkan masalah.


#### Perbaikan BUG
1. [#4162](https://github.com/OpenSID/OpenSID/issues/4162) Batasi tidak bisa ubah data Penduduk, Keluarga, Kelompok, Data Suplemen, Layanan Surat, Info Desa, Admin Web dan Rumah Tangga kalau pengguna tidak mempunyai hak ubah.
2. [#4170](https://github.com/OpenSID/OpenSID/issues/4170) Sekarang data keluarga tersimpan normal pada impor data penduduk.
3. [#4165](https://github.com/OpenSID/OpenSID/issues/4165) Sekarang penandatangan tampil benar di cetak/unduh Buku Agenda - Surat Masuk dan Buku Ekspedisi.
4. [#4167](https://github.com/OpenSID/OpenSID/issues/4167) Sekarang laporan cetak/unduh Sekretariat > Informasi Publik ada blok penandatangan.
5. [#4172](https://github.com/OpenSID/OpenSID/issues/4172) Data rincian peserta bantuan sekarang tampil benar di Layanan Mandiri.
6. [#4168](https://github.com/OpenSID/OpenSID/issues/4168) Pindahkan Buku Tanah di Desa dan Buku Tanah Kas Desa ke Buku Administrasi Umum.
7. Perbaiki jumlah luas total dan pemeriksaan rincian luas di Buku Tanah di Desa.
8. [#4184](https://github.com/OpenSID/OpenSID/issues/4184) Nama desa sekarang tampil di cetak laporan Sekretariat > Informasi Publik.
9. [#4186](https://github.com/OpenSID/OpenSID/issues/4186) Munculkan keyboard virtual di anjungan bagi semua surat bawaan sistem yang tersedia di Layanan Mandiri.
10. [#4191](https://github.com/OpenSID/OpenSID/issues/4191) Sesuaikan keyboard virtual di anjungan supaya tidak menutup keseluruhan form.
11. [#4193](https://github.com/OpenSID/OpenSID/issues/4193) Sekarang Pamong di Pemerintahan Desa bisa diubah dari Database Penduduk menjadi Tidak Terdata.
12. Sekarang tidak menggantung jika gagal koneksi ke website eksternal, seperti ke https://pantau.opensid.or.id.
13. Sekarang pilihan pendaftar layanan mandiri tetap tampil di form tulis pesan jika setting database sql_mode termasuk only_full_group_by.


#### Perubahan Teknis
1. Perbaiki penulisan script Buku Tanah di Desa dan Buku Kas Tanah Desa.
2. Gunakan modal cetak global.
3. Sesuaikan migrasi dengan versi database.
4. Sederhanakan query modul Pembangunan.
5. Sesuaikan link status SDGs Desa supaya menggunakan format slug menu lainnya dan tidak perlu diawali 'first'.
6. Sederhanakan dan mengurangi duplikasi script pada Layanan Mandiri.
7. Hapus script tidak digunakan di modul Layanan Surat.
8. Sediakan opsi memanggil data eksternal tanpa menggunakan SSL. Ambil data SDGs tanpa menggunakan SSL, karena sertifikat portal Kemendesa yg bermasalah. Data SDGs tidak dirahasiakan, dan aman diambil tanpa SSL.
