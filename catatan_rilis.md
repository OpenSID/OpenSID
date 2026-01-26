Rilis versi 2601.1.0 ini berisi penambahan notifikasi berbasis laravel dan perbaikan lainnya yang diminta oleh komunitas SID.

### FITUR
1. [#10663](https://github.com/OpenSID/OpenSID/issues/10663) Unduh semua data di menu rumah tangga.
2. [#10705](https://github.com/OpenSID/OpenSID/issues/10705) Perubahan penamaan gabung kk dan perjelas informasi pecah kk.
3. [#10706](https://github.com/OpenSID/OpenSID/issues/10706) Status shdk tidak berubah ketika pecah kk.
4. [#10664](https://github.com/OpenSID/OpenSID/issues/10664) Tambahkan KIA pada Format Import Penduduk.
5. [#10682](https://github.com/OpenSID/OpenSID/issues/10682) Fitur lepas kaitan kk lama ganda.
6. [#10607](https://github.com/OpenSID/OpenSID/issues/10607) Fitur copy IP address di layanan mandiri.
7. [#10500](https://github.com/OpenSID/OpenSID/issues/10500) Tambahkan nik ibu dan nik anak setelah nama ibu dan nama anak pada kesehatan>stunting.
8. [#10655](https://github.com/OpenSID/OpenSID/issues/10655) Fitur wajibkan setiap pengguna untuk verifikasi minimal salah satu dari alamat email atau akun Telegram, dilakukan pada saat pertama kali login, setelah akunnya dibuat oleh superadmin.
9. [#10642](https://github.com/OpenSID/OpenSID/issues/10642) Fitur agar hasil cetak F-1.06 potrait dan jadi satu halaman
10. [#10669](https://github.com/OpenSID/OpenSID/issues/10669) Fitur ketika cetak peta agar bisa full dan dibuat center terhadap peta wilayah desa.
11. [#10644](https://github.com/OpenSID/OpenSID/issues/10644) Fitur Isian data istri terdahulu di surat keterangan nikah bisa dari penduduk luar desa.
12. [#10643](https://github.com/OpenSID/OpenSID/issues/10643) Penambahan alamat lengkap lampiran surat keterangan nikah.
13. [#10728](https://github.com/OpenSID/OpenSID/issues/10728) Ganti Istilah Cacat menjadi Disabilitas.
14. [#10628](https://github.com/OpenSID/OpenSID/issues/10628) Penambahan fitur tambah validasi bayi baru lahir shdk anak pada keluarga.
15. [#10652](https://github.com/OpenSID/OpenSID/issues/10652) Penambahan fitur pengaturan pengguna secara global untuk seluruh sistem. 
16. [#10700](https://github.com/OpenSID/OpenSID/issues/10700) Penambahan fitur Data Program Bantuan tampil Di halaman WEB ketika Masa Berlaku Program Berakhir.
17. [#10627](https://github.com/OpenSID/OpenSID/issues/10627) Penambahan fitur penambahan data penduduk yang sudah meninggal di menu penduduk. 
18. [#10755](https://github.com/OpenSID/OpenSID/issues/10755) Penambahan fitur memberikan informasi detail apabila KK tanpa kepala keluarga ketika ingin menambahkan penduduk masuk/lahir/yang sudah ada.
19. [#10746](https://github.com/OpenSID/OpenSID/issues/10746) Penambahan fitur menambahkan no rumah tangga pada hasil export data penduduk.
20. [#10756](https://github.com/OpenSID/OpenSID/issues/10756) Penambahan fitur Lampiran Formulir F101 untuk Kabupaten Tabanan.
21. [#10752](https://github.com/OpenSID/OpenSID/issues/10752) Penambahan fitur unduh semua data pada buku mutasi penduduk dengan menggunakan component yg terpisah.
22. [#10762](https://github.com/OpenSID/OpenSID/issues/10762) Penambahan fitur filter tahun pada unduhan arsip surat dan standardisasi komponen dialog cetak.
23. [#10745](https://github.com/OpenSID/OpenSID/issues/10745) Penambahan fitur tombol sorting di kolom no rumah tangga di data penduduk.

### BUG
1. [#10678](https://github.com/OpenSID/OpenSID/issues/10678) Perbaikan typo pada pesan error hapus Pemerintah Desa.
2. [#10681](https://github.com/OpenSID/OpenSID/issues/10681) Perbaikan data anjungan sukses di delete, tetapi keterangannya data gagal.
3. [#10675](https://github.com/OpenSID/OpenSID/issues/10681) Perbaikan typo tulisan  anjungan.
4. [#10676](https://github.com/OpenSID/OpenSID/issues/10676) Perbaikan sebutan Desa tidak terbaca otomatis dari Pengaturan
5. [#10680](https://github.com/OpenSID/OpenSID/issues/10680) Perbaikan Error hapus massal Anggota RTM.
6. [#10685](https://github.com/OpenSID/OpenSID/issues/10685) Perbaiki preview cetak/unduh pada buku tanah di desa
7. [#10696](https://github.com/OpenSID/OpenSID/issues/10696) Perbaikan header tanggal sk pengangkatan yang duplikat pada modul lembaga desa.
8. [#10686](https://github.com/OpenSID/OpenSID/issues/10686) Perbaikan preview cetak/unduh dan tanggal pada buku tanah kas desa.
9. [#10693](https://github.com/OpenSID/OpenSID/issues/10693) Perbaikan daftar tahun yang duplikat pad amodul laporan penduduk.
10. [#10695](https://github.com/OpenSID/OpenSID/issues/10695) Perbaikan filter status pada modul Buku Lembaran Desa Dan Berita Desa.
11. [#10698](https://github.com/OpenSID/OpenSID/issues/10698) Perbaikan akses kamera pada halaman buku tamu.
12. [#10689](https://github.com/OpenSID/OpenSID/issues/10689) Perbaikan alamat tidak tampil pada rekam surat perseorangan.
13. [#10653](https://github.com/OpenSID/OpenSID/issues/10653) Perbaikan validasi tidak wajib isi input alamat pada modul keluarga.
14. [#10694](https://github.com/OpenSID/OpenSID/issues/10694) Perbaikan filter Buku Peraturan di Desa dan Buku Keputasan Kepala.
15. [#10692](https://github.com/OpenSID/OpenSID/issues/10692) Perbaikan tombol terima cookie pada layanan mandiri tidak bisa diklik.
16. [#10704](https://github.com/OpenSID/OpenSID/issues/10704) Perbaikan tombol export gpx di menu stunting.
17. [#10703](https://github.com/OpenSID/OpenSID/issues/10703) Perbaiki alert error di Pengaduan Kehadiran apabila si pelapor dihapus di Pendaftaran Layanan Mandiri.
18. [#10699](https://github.com/OpenSID/OpenSID/issues/10699) Perbaikan hak akses folder pengajuan_izin.
19. [#10702](https://github.com/OpenSID/OpenSID/issues/10702) Perbaikan bug login layanan mandiri error "Anjungan tidak ditemukan".
20. [#10708](https://github.com/OpenSID/OpenSID/issues/10708) Perbaikan pesan sukses edit data pada pengaturan surat.
21. [#10709](https://github.com/OpenSID/OpenSID/issues/10709) Perbaikan fungsi bug tombol batal pada pengaturan surat.
22. [#10710](https://github.com/OpenSID/OpenSID/issues/10710) Perbaikan hasil preview cetak buku pemerintah desa terpotong.
23. [#10712](https://github.com/OpenSID/OpenSID/issues/10712) Perbaikan hasil preview cetak buku ekspedisi tidak ada border.
24. [#10711](https://github.com/OpenSID/OpenSID/issues/10711) Perbaikan border dan rapihkan tampilan pada cetak agenda surat keluar.
25. [#10713](https://github.com/OpenSID/OpenSID/issues/10713) Perbaiki tampilan preview cetak di buku Administrasi penduduk.
26. [#10719](https://github.com/OpenSID/OpenSID/issues/10719) Perbaikan scan dari mamera di Menu qrcode yang tidak berfungsi.
27. [#10718](https://github.com/OpenSID/OpenSID/issues/10718) Perbaikan isian pekerjaan id di format import penduduk tidak menampilkan pilihan lainnya seperti di kode data.
28. [#10717](https://github.com/OpenSID/OpenSID/issues/10717) Perbaikan element data kependudukan tidak tampil di Kartu Rumah Tangga.
29. [#10715](https://github.com/OpenSID/OpenSID/issues/10715) Perbaikan tanggal Perkawinan/Perceraian pada hasil Unduhan Tombol Unduh dan Unduh F1.09 muncul di anggota yang status Perkawinan nya Cerai Mati.
30. [#10724](https://github.com/OpenSID/OpenSID/issues/10724) Perbaikan tombol ganti gambar di modals Pengajuan isi kurva.
31. [#10026](https://github.com/OpenSID/OpenSID/issues/10026) Perbaikan File Zip Backup Folder Desa Corrupt.
32. [#10723](https://github.com/OpenSID/OpenSID/issues/10723) Perbaikan tidak ada data yang di tampilkan saat cetak buku tamu.
33. [#10722](https://github.com/OpenSID/OpenSID/issues/10722) Perbaikan  cetak di Buku Administrasi Penduduk ketika filter tahun dan bulan tidak dipilih.
34. [#10726](https://github.com/OpenSID/OpenSID/issues/10726) Perbaikan Surat keluar di Arsip layanan surat tidak tampil di pengguna kades/sekdes
35. [#10731](https://github.com/OpenSID/OpenSID/issues/10731) Perbaikan dropdown pilihan penduduk kosong saat klik tombol Ubah pada Rekam Surat Perseorangan.
36. [#10732](https://github.com/OpenSID/OpenSID/issues/10732) Perbaikan DOM-Based XSS pada Fitur QR Scanner (HTML/ SVG Injection).
37. [#10736](https://github.com/OpenSID/OpenSID/issues/10736) Perbaikan Buat Qrcode tanpa logo tidak berfungsi.
38. [#10733](https://github.com/OpenSID/OpenSID/issues/10733) Perbaikan Catatan Perhitungan Tidak Ditampilkan pada Data Perolehan TKD yang Sudah Tersimpan.
39. [#10732](https://github.com/OpenSID/OpenSID/issues/10732) Perbaikan Form Warga Desa berubah setelah klik tombol Batal pada Buku Tanah di Desa.
40. [#10730](https://github.com/OpenSID/OpenSID/issues/10730) Perbaikan Data SK masih tetap muncul sudah dihapus.
41. [#10747](https://github.com/OpenSID/OpenSID/issues/10747) Perbaikan Gagal Reset pin di Layanan Mandiri.
42. [#10739](https://github.com/OpenSID/OpenSID/issues/10739) Perbaikan hasil scan qrcode upload file dan scan menggunakan kamera.
43. [#10738](https://github.com/OpenSID/OpenSID/issues/10738) Perbaikan gambar tidak tampil saat menggunakan url drive.
44. [#10724](https://github.com/OpenSID/OpenSID/issues/10724) Perbaikan tombol ganti tampilan captcha pada komentar artikel.
45. [#10751](https://github.com/OpenSID/OpenSID/issues/10751) Perbaikan Pemanggilan Logo default jika Kelompok tidak upload logonya.
46. [#10758](https://github.com/OpenSID/OpenSID/issues/10758) Perbaikan tombol Anjungan pada login Layanan Mandiri hilang saat IP Adress berubah.
47. [#10623](https://github.com/OpenSID/OpenSID/issues/10623) Perbaikan ubah alamat kk hanya dilakukan terpusat di edit data keluarga serta tampilkan alamat yang jelas pada detail penduduk.
48. [#10757](https://github.com/OpenSID/OpenSID/issues/10757) Perbaikan gambar/icon default menu anjungan tidak muncul.
49. [#10750](https://github.com/OpenSID/OpenSID/issues/10750) Perbaikan error setelah klik tombol bantuan pada data anggota rtm.
50. [#10759](https://github.com/OpenSID/OpenSID/issues/10759) Perbaikan pada fitur pencarian di tema ESENSI yang sebelumnya masih menerima payload input sangat panjang.
51. [#10760](https://github.com/OpenSID/OpenSID/issues/10760) Perbaikan hasil error report yang tampil jika gagal melakukan TTE .
52. [#10761](https://github.com/OpenSID/OpenSID/issues/10761) Perbaikan notifikasi/pesan gagal double di Layanan Mandiri.
53. [#10753](https://github.com/OpenSID/OpenSID/issues/10753) Perbaikan QR Code tidak muncul pada surat dinas.
54. [#10768](https://github.com/OpenSID/OpenSID/issues/10768) Perbaikan typo "Telelpon" pada Tabel Pelapak.
55. [#10769](https://github.com/OpenSID/OpenSID/issues/10769) Perbaikan nomor surat tidak masuk di lampiran F-1.34 .

### KEAMANAN
1. [#5771](https://github.com/OpenSID/premium/issues/5771) Perbaikan keamanan pada DataTables.
2. [#5694](https://github.com/OpenSID/OpenSID/issues/5694) Update package untuk mengatasi kerentanan keamanan.

### TEKNIS
1. [#10691](https://github.com/OpenSID/OpenSID/issues/10691) Perbaikan htaccess bawaan opensid yang error untuk server apache
2. [#10589](https://github.com/OpenSID/OpenSID/issues/10589) Melakukan refactor pemisahkan migrasi install baru untuk modul.
3. [#10743](https://github.com/OpenSID/OpenSID/issues/10743) Pengaturan sebutan wilayah desa dan jabatan di satu tempat.
4. [#10762](https://github.com/OpenSID/premium/issues/5851) Melakukan refactor menjadikan fitur scan folder desa menjadi paket module tambahan.

