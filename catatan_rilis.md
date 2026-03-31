Rilis versi 2604.0.0 ini berisi penambahan fitur pindah 1 keluarga sekaligus  dan perbaikan lainnya yang diminta oleh komunitas SID.

### FITUR
1. [#10877](https://github.com/OpenSID/OpenSID/issues/10877) Penambahan Deaktivasi akun tidak aktif dengan lazy check saat login.
2. [#10888](https://github.com/OpenSID/OpenSID/issues/10888) Penambahan filter status hubungan dalam keluarga dalam pencarian spesifik di menu penduduk.
3. [#10428](https://github.com/OpenSID/OpenSID/issues/10428) Penambahan fitur pada menu Pembangunan untuk SILPA bisa ditambahkan rumus & auto isi dan pelaksanan diambil dari perangkat desa.
4. [#9850](https://github.com/OpenSID/OpenSID/issues/9850) Penambahan fitur bolehkan inputan selain angka pada kuisioner "Nomor Sertifikat Buku Letter C/Persil" di Buku Kas Desa.
5. [#10818](https://github.com/OpenSID/OpenSID/issues/10818) Penambahan fitur tambahkan opsi penduduk luar desa ketika menambahkan anggota pada lembaga.
6. [#10816](https://github.com/OpenSID/OpenSID/issues/10816) Penambahan fitur tambahkan opsi penduduk luar desa ketika menambahkan anggota pada pada kelompok.
7. [#10913](https://github.com/OpenSID/OpenSID/issues/10913) Penambahan fitur alur hapus pengguna: implementasi soft delete dan validasi relasi.
8. [#10843](https://github.com/OpenSID/OpenSID/issues/10843) Penambahan fitur peta batas desa dibuat otomatis disediakan oleh opensid.
9. [#10921](https://github.com/OpenSID/OpenSID/issues/10921) Penambahan fitur lampiran f1.02 untuk surat keterangan kematian.
10. [#10429](https://github.com/OpenSID/OpenSID/issues/10429) Penambahan fitur setiap pengajuan izin dari kehadiran ditambahkan notifikasi.
11. [#8642](https://github.com/OpenSID/OpenSID/issues/8642) Penambahan fitur notifikasi adanya Pengaduan baru pada halaman siteman.
12. [#10950](https://github.com/OpenSID/OpenSID/issues/10950) Penambahan fitur ubah aksi lihat dokumen kematian ke preview.
13. [#10527](https://github.com/OpenSID/OpenSID/issues/10527) Penambahan fitur upload peta wilayah desa dengan banyak area.
14. [#10903](https://github.com/OpenSID/OpenSID/issues/10903) Penambahan fitur pencarian spesifik berdasarkan nomor KK sebelumnya di menu Penduduk.
15. [#10905](https://github.com/OpenSID/OpenSID/issues/10905) Penambahan fitur pindah 1 keluarga sekaligus.
16. [#10931](https://github.com/OpenSID/OpenSID/issues/10931) Penambahan fitur filter tahun pada menu statistik.
17. [#10721](https://github.com/OpenSID/OpenSID/issues/10721) Penambahan modul DTSEN.


### BUG
1. [#10925](https://github.com/OpenSID/OpenSID/issues/10925) Perbaikan gagal upload file pada artikel.
2. [#10944](https://github.com/OpenSID/OpenSID/issues/10944) Perbaikan tombol keterangan tidak merespon saat diklik pada halaman permohonan surat layanan mandiri.
3. [#10945](https://github.com/OpenSID/OpenSID/issues/10945) Perbaikan tampilan text berjalan tidak berfungsi di browser versi terbaru.
4. [#10940](https://github.com/OpenSID/OpenSID/issues/10940) Perbaikan nomor akta perceraian pada lampiran F101-Tabanan tidak muncul pada previews dan unduh pdf.
5. [#10930](https://github.com/OpenSID/OpenSID/issues/10930) Perbaikan gagal import data penduduk jika nama ayah/ ibu berisi karakter "-".
6. [#10947](https://github.com/OpenSID/OpenSID/issues/10947) Perbaikan error 500 impor data suplement ketika ada data yang tidak valid.
7. [#10946](https://github.com/OpenSID/OpenSID/issues/10946) Perbaikan pesan response sinkronisasi data pada menu OpenDK.
8. [#10924](https://github.com/OpenSID/OpenSID/issues/10924) Perbaikan hasil scan QR code surat dinas yang tidak sesuai.
9. [#10948](https://github.com/OpenSID/OpenSID/issues/10948) Perbaikan NIK tidak tampil dengan benar saat cetak/unduh bantuan.
10. [#10949](https://github.com/OpenSID/OpenSID/issues/10949) Perbaikan tampilan Penomoran Surat di Menu Pengaturan Surat/Lainnya.
11. [#10939](https://github.com/OpenSID/OpenSID/issues/10939) Perbaikan sinkronisasi data program bantuan ke OpenDK.
12. [#10938](https://github.com/OpenSID/OpenSID/issues/10938) Perbaikan dokumen yang diupload warga melalui Layanan Mandiri otomatis dapat diubah.
13. [#10927](https://github.com/OpenSID/OpenSID/issues/10927) Perbaikan inkonsisten status pada rekap kehadiran jika keluar dari presensi PC dan aplikasi mobile Kelola Desa.
14. [#10937](https://github.com/OpenSID/OpenSID/issues/10937) Perbaikan export peta wilayah dengan banyak area ke format gpx.
15. [#10958](https://github.com/OpenSID/OpenSID/issues/10958) Perbaikan pesan sukses hapus data dokumen penduduk.
16. [#10961](https://github.com/OpenSID/OpenSID/issues/10961) Perbaikan unduh pada input data sensus / survei excel isi data dan kode data.
17. [#10878](https://github.com/OpenSID/OpenSID/issues/10878) Perbaikan inputan lat/long pada pengaturan peta lokasi agar tidak bisa menerima inputan huruf.
18. [#10963](https://github.com/OpenSID/OpenSID/issues/10963) Perbaikan tidak bisa upload file galeri dan gambar tidak tampil di slider.
19. [#6093](https://github.com/OpenSID/premium/issues/6093) Perbaikan Stored XSS via Attribute Injection pada Modul Pembangunan (Halaman Publik).
20. [#10971](https://github.com/OpenSID/OpenSID/issues/10971) Perbaikan bagan struktur organisasi pemerintah desa tidak tampil.
21. [#10974](https://github.com/OpenSID/OpenSID/issues/10974) Perbaikan tanggal cetak yang dipilih tidak digunakan pada hasil cetak di Buku Administrasi Umum (selalu menampilkan tanggal hari ini).

### KEAMANAN
1. [#6081](https://github.com/OpenSID/premium/issues/6081) Peningkatan keamanan pada ekspor excel data terdata.
2. [#6016](https://github.com/OpenSID/premium/issues/6016) Peningkatan keamanan backup .sid dan pengurus.
3. [#6117](https://github.com/OpenSID/premium/issues/6117) Peningkatan keamanan data pribadi pelapak (NIK, TTL, Status, dll) yang terbuka.

### TEKNIS
1. [#10898](https://github.com/OpenSID/OpenSID/issues/10898) Pengelompokan surat sistem dan surat desa pada pengaturan catatan peristiwa di menu Riwayat Mutasi Penduduk.
2. [#10929](https://github.com/OpenSID/OpenSID/issues/10929) Penyederhanaan logika pemesanan tema premium.

