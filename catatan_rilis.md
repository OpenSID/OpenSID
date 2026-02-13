Rilis versi 2602.0.0 ini berisi perubahan penamaan gabung kk dan perjelas informasi pecah kk dan perbaikan lainnya yang diminta oleh komunitas SID.

### FITUR
1. [#10749](https://github.com/OpenSID/OpenSID/issues/10749) Permintaan fitur agar bisa menambahkan nomor rumah tangga yang diakhiri huruf.
2. [#5854](https://github.com/OpenSID/premium/issues/5854) Penyederhanaan fitur scan folder desa.
3. [#10813](https://github.com/OpenSID/OpenSID/issues/10813) Penambahan fitur untuk setting format nomor rumah tangga.
4. [#9874](https://github.com/OpenSID/OpenSID/issues/9874) Penambahan fitur pengaturan data buku tamu.
5. [#10797](https://github.com/OpenSID/OpenSID/issues/10797) Penambahan fitur pemberian satu langkah sadar perihal penghapusan data.
6. [#10592](https://github.com/OpenSID/OpenSID/issues/10592) Penambahan fitur surat pengantar kia.
7. [#10805](https://github.com/OpenSID/OpenSID/issues/10805) Penambahan fitur diagram lingkaran stunting.
8. [#10810](https://github.com/OpenSID/OpenSID/issues/10810) Penambahan fitur samakan tampilan view kamera dengan hasil capture di buku tamu.
9. [#10783](https://github.com/OpenSID/OpenSID/issues/10783) Penambahan fitur tampilkan status dasar dan tambahkan filter status dasar pada rincian/anggota kelompok.
10. [#10798](https://github.com/OpenSID/OpenSID/issues/10798) Penambahan fitur penerapan reCaptcha Score based (v3).
11. [#10807](https://github.com/OpenSID/OpenSID/issues/10807) Penambahan fitur unduh semua data pada buku administrasi penduduk.

### BUG
1. [#10789](https://github.com/OpenSID/OpenSID/issues/10789) Perbaikan umur pada edit data pemantauan anak.
2. [#10787](https://github.com/OpenSID/OpenSID/issues/10787) Perbaikan tujuan pindah kembali kosong ketika edit data di riwayat mutasi penduduk.
3. [#10796](https://github.com/OpenSID/OpenSID/issues/10796) Perbaikan data statistik laporan bulanan penduduk antarbulan tidak sinkron.
4. [#5898](https://github.com/OpenSID/premium/issues/5898) Perbaikan tidak bisa melakukan backup .sid.
5. [#10801](https://github.com/OpenSID/OpenSID/issues/10801) Perbaikan Google reCAPTCHA Error Missing required parameters sitekey.
6. [#10804](https://github.com/OpenSID/OpenSID/issues/10804) Perbaikan filter nomor rumah tangga pada penduduk.
7. [#10808](https://github.com/OpenSID/OpenSID/issues/10808) Perbaikan Icon tambah tidak muncul pada tombol tambah anggota di modul Kelompok/Lembaga.
8. [#10806](https://github.com/OpenSID/OpenSID/issues/10806) Perbaikan tombol tinjau surat masih muncul saat selesai cetak dan diklik error.
9. [#10811](https://github.com/OpenSID/OpenSID/issues/10811) Perbaikan gagal ubah data ketua lembaga dan jabatan berubah menjadi anggota.
10. [#10802](https://github.com/OpenSID/OpenSID/issues/10802) Perbaikan error saat import data analisis.
11. [#10812](https://github.com/OpenSID/OpenSID/issues/10812) Perbaikan fungsi pencarian marga agar yang disorot sesuai dengan marga yang dicari. 
12. [#10825](https://github.com/OpenSID/OpenSID/issues/10825) Perbaikan proses pendaftaran layanan mandiri gagal dan  muncul blank. eror yang tampil tidak sesuai dengan yang ada di log.
13. [#10820](https://github.com/OpenSID/OpenSID/issues/10820) Perbaikan error registrasi buku tamu.
14. [#10821](https://github.com/OpenSID/OpenSID/issues/10821) Perbaikan pengguna menjadi tidak aktif setelah di aktifkan.
15. [#10832](https://github.com/OpenSID/OpenSID/issues/10832) Perbaikan tidak bisa lakukan ubah bantuan apabila belum ada peserta.
16. [#10836](https://github.com/OpenSID/OpenSID/issues/10836) Perbaikan asal dana dan random no peserta jika tidak kosong pada import data bantuan.
17. [#10833](https://github.com/OpenSID/OpenSID/issues/10833) Perbaikan error 500 saat buat surat di Layanan Mandiri.
18. [#10835](https://github.com/OpenSID/OpenSID/issues/10835) Perbaikan eror datatables pada fitur pencarian secara global.
19. [#10830](https://github.com/OpenSID/OpenSID/issues/10830) Perbaikan validasi nomor sertifikat pada modul Inventaris Tanah.
20. [#10809](https://github.com/OpenSID/OpenSID/issues/10809) Perbaikan gagal saat restore database ke localhost.
21. [#10845](https://github.com/OpenSID/OpenSID/issues/10845) Perbaikan gagal import analisis.
22. [#10841](https://github.com/OpenSID/OpenSID/issues/10841) Perbaikan form submit berulang pada pengajuan surat di layanan mandiri.
23. [#10839](https://github.com/OpenSID/OpenSID/issues/10839) Perbaikan input data ayah/ibu pada modul penduduk.
24. [#10846](https://github.com/OpenSID/OpenSID/issues/10846) Perbaikan error saat import data analisis.
25. [#10840](https://github.com/OpenSID/OpenSID/issues/10840) Perbaikan menu keuangan pada anjungan tidak bisa di buka.

### KEAMANAN
1. [#5921](https://github.com/OpenSID/premium/issues/5921) Pembaruan package untuk mengatasi kerentanan yang terdeteksi pada dependensi npm.
2. [#5892](https://github.com/OpenSID/premium/issues/5892) Perkuat security headers berdasarkan audit BSSN.
3. [#5932](https://github.com/OpenSID/premium/issues/5932) Pembaharuan library moment js.
4. [#5953](https://github.com/OpenSID/premium/issues/5953) Pembaruan library axios.

### TEKNIS
1. [#10792](https://github.com/OpenSID/OpenSID/issues/10792) Migrasi versi opensid umum ke versi premium.