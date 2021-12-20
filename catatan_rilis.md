#### [v21.12-pasca]

Di rilis ini, versi 21.06-premium, menyediakan Buku Inventaris dan Kekayaan Desa sesuai Permendagri 47/2016. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada afa28 yang terus berkontribusi.

Lengkapnya, isi rilis versi v21.12-pasca adalah sebagai berikut:


#### Penambahan Fitur
1. [#4137](https://github.com/OpenSID/OpenSID/issues/4137) Sekarang Status IDM Desa bisa ditampilkan untuk tahun pilihan.
2. [#4171](https://github.com/OpenSID/OpenSID/issues/4171) Di Pengaturan > QR Code scan tombol Kunjungi Website sekarang hanya tampil jika ada url valid di isi QR Code yg di-scan.
3. [#4183](https://github.com/OpenSID/OpenSID/issues/4183) Tampilkan / Sembunyikan PIN saat Ganti PIN Layanan Mandiri.
4. [#3918](https://github.com/OpenSID/OpenSID/issues/3918) Sekarang bisa kirim pesan belum lengkap pada waktu memeriksa permohonan surat layanan mandiri.
5. [#4124](https://github.com/OpenSID/OpenSID/issues/4124) Sekarang jabatan Kependudukan > Kelompok dapat diisi secara manual.
6. [#4055](https://github.com/OpenSID/OpenSID/issues/4055) Sekarang bisa ambil foto penduduk anggota kelompok dan Pemerintahan Desa menggunakan kamera HP/webcam.
7. [#4037](https://github.com/OpenSID/OpenSID/issues/4037) Pengguna Layanan Mandiri sekarang dapat memberi penilaian kepuasan pelayanan.
8. Tampilkan isi logs di Pengaturan > Info Sistem, untuk memudahkan pengguna melaporkan masalah.
9. [#4200](https://github.com/OpenSID/OpenSID/issues/4200) Sediakan tombol untuk memperbaharui data status IDM, mengganti yg tersimpan di cache.
10. [#3875](https://github.com/OpenSID/OpenSID/issues/3875) Sembunyikan Dokumen Persyaratan Surat pada permohonan surat Layanan Mandiri jika syarat surat tidak diperlukan.
11. [#4202](https://github.com/OpenSID/OpenSID/issues/4202) Sediakan tombol untuk menampilkan dokumen kelengkapan pada waktu memeriksa permohonan surat Layanan Mandiri.
12. [#4210](https://github.com/OpenSID/OpenSID/issues/4210) Di form ubah biodata penduduk, sediakan tombol kembali ke Daftar Anggota Keluarga jika dibuka dari situ.
13. [#2838](https://github.com/OpenSID/OpenSID/issues/2838) Sediakan Buku Inventaris dan Kekayaan Desa sesuai Permendagri 47/2016.


#### Perbaikan BUG
1. [#4162](https://github.com/OpenSID/OpenSID/issues/4162) Batasi tidak bisa ubah data Penduduk, Keluarga, Kelompok, Data Suplemen, Layanan Surat, Info Desa, Admin Web, Layanan Mandiri, Bantuan, Sekretariat, Pembangunan, Pertanahan dan Rumah Tangga kalau pengguna tidak mempunyai hak ubah.
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
14. Tampilkan foto perangkat desa di widget Aparatur Desa dan Bagan bagi perangkat dari penduduk desa.
15. [#4175](https://github.com/OpenSID/OpenSID/issues/4175) Perbaiki centang entri input manual data keuangan sesuai jenis anggaran.
16. Perbaiki migrasi kalau grup pengguna Satgas Covid dihapus.
17. [#4203](https://github.com/OpenSID/OpenSID/issues/4203) Sekarang blok tandatangan tampil benar di laporan cetak/unduh Buku Peraturan Desa.
18. [#4204](https://github.com/OpenSID/OpenSID/issues/4204) Sekarang blok tandatangan tampil benar di laporan cetak/unduh Buku Keputusan Kepala Desa.
19. [#4205](https://github.com/OpenSID/OpenSID/issues/4205) Sekarang blok tandatangan tampil benar di laporan cetak/unduh Buku Lembaran Desa Dan Berita Desa.
20. [#4206](https://github.com/OpenSID/OpenSID/issues/4206) Sekarang blok tandatangan tampil benar di laporan cetak/unduh Statistik Laporan Bulanan.
21. [#4201](https://github.com/OpenSID/OpenSID/issues/4201) Sekarang penduduk tidak tetap yang ditambahkan melalui Satgas Covid-19 > Pendataan tersimpan dengan benar.
22. [#4207](https://github.com/OpenSID/OpenSID/issues/4207) Sekarang kembalikan status dasar kepala keluarga mati/hilang/pindah terhitung benar di Statistik > Laporan Bulanan.


#### Perubahan Teknis
1. Perbaiki penulisan script Buku Tanah di Desa dan Buku Kas Tanah Desa.
2. Gunakan modal cetak global.
3. Sesuaikan migrasi dengan versi database.
4. Sederhanakan query modul Pembangunan.
5. Sesuaikan link status SDGs Desa supaya menggunakan format slug menu lainnya dan tidak perlu diawali 'first'.
6. Sederhanakan dan mengurangi duplikasi script pada Layanan Mandiri.
7. Hapus script tidak digunakan di modul Layanan Surat.
8. Sediakan opsi memanggil data eksternal tanpa menggunakan SSL. Ambil data SDGs tanpa menggunakan SSL, karena sertifikat portal Kemendesa yg bermasalah. Data SDGs tidak dirahasiakan, dan aman diambil tanpa SSL.
9. Kirim statistik tambahan ke TrackSID.
