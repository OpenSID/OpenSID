Rilis versi 2602.0.0 ini berisi perubahan penamaan gabung kk dan perjelas informasi pecah kk dan perbaikan lainnya yang diminta oleh komunitas SID.

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
24. [#10772](https://github.com/OpenSID/OpenSID/issues/10772) Penambahan fitur pengaturan captcha untuk bisa pilih case sensitive atau tidak.


### BUG
1. [#10723](https://github.com/OpenSID/OpenSID/issues/10723) Perbaikan tidak ada data yang di tampilkan saat cetak buku tamu.
2. [#10722](https://github.com/OpenSID/OpenSID/issues/10722) Perbaikan  cetak di Buku Administrasi Penduduk ketika filter tahun dan bulan tidak dipilih.
3. [#10726](https://github.com/OpenSID/OpenSID/issues/10726) Perbaikan Surat keluar di Arsip layanan surat tidak tampil di pengguna kades/sekdes
4. [#10731](https://github.com/OpenSID/OpenSID/issues/10731) Perbaikan dropdown pilihan penduduk kosong saat klik tombol Ubah pada Rekam Surat Perseorangan.
5. [#10732](https://github.com/OpenSID/OpenSID/issues/10732) Perbaikan DOM-Based XSS pada Fitur QR Scanner (HTML/ SVG Injection).
6. [#10736](https://github.com/OpenSID/OpenSID/issues/10736) Perbaikan Buat Qrcode tanpa logo tidak berfungsi.
7. [#10733](https://github.com/OpenSID/OpenSID/issues/10733) Perbaikan Catatan Perhitungan Tidak Ditampilkan pada Data Perolehan TKD yang Sudah Tersimpan.
8. [#10732](https://github.com/OpenSID/OpenSID/issues/10732) Perbaikan Form Warga Desa berubah setelah klik tombol Batal pada Buku Tanah di Desa.
9. [#10730](https://github.com/OpenSID/OpenSID/issues/10730) Perbaikan Data SK masih tetap muncul sudah dihapus.
10. [#10739](https://github.com/OpenSID/OpenSID/issues/10739) Perbaikan hasil scan qrcode upload file dan scan menggunakan kamera.
11. [#10738](https://github.com/OpenSID/OpenSID/issues/10738) Perbaikan gambar tidak tampil saat menggunakan url drive.
12. [#10724](https://github.com/OpenSID/OpenSID/issues/10724) Perbaikan tombol ganti tampilan captcha pada komentar artikel.
13. [#10751](https://github.com/OpenSID/OpenSID/issues/10751) Perbaikan Pemanggilan Logo default jika Kelompok tidak upload logonya.
14. [#10758](https://github.com/OpenSID/OpenSID/issues/10758) Perbaikan tombol Anjungan pada login Layanan Mandiri hilang saat IP Adress berubah.
15. [#10623](https://github.com/OpenSID/OpenSID/issues/10623) Perbaikan ubah alamat kk hanya dilakukan terpusat di edit data keluarga serta tampilkan alamat yang jelas pada detail penduduk.
16. [#10757](https://github.com/OpenSID/OpenSID/issues/10757) Perbaikan gambar/icon default menu anjungan tidak muncul.
17. [#10750](https://github.com/OpenSID/OpenSID/issues/10750) Perbaikan error setelah klik tombol bantuan pada data anggota rtm.
18. [#10759](https://github.com/OpenSID/OpenSID/issues/10759) Perbaikan pada fitur pencarian di tema ESENSI yang sebelumnya masih menerima payload input sangat panjang.
19. [#10760](https://github.com/OpenSID/OpenSID/issues/10760) Perbaikan hasil error report yang tampil jika gagal melakukan TTE .
20. [#10761](https://github.com/OpenSID/OpenSID/issues/10761) Perbaikan notifikasi/pesan gagal double di Layanan Mandiri.
21. [#10753](https://github.com/OpenSID/OpenSID/issues/10753) Perbaikan QR Code tidak muncul pada surat dinas.
22. [#10768](https://github.com/OpenSID/OpenSID/issues/10768) Perbaikan typo "Telelpon" pada Tabel Pelapak.
23. [#10769](https://github.com/OpenSID/OpenSID/issues/10769) Perbaikan nomor surat tidak masuk di lampiran F-1.34.
24. [#10771](https://github.com/OpenSID/OpenSID/issues/10771) Perbaikan tombol tinjaupdf tidak rapi di tampilan mobile.
25. [#10773](https://github.com/OpenSID/OpenSID/issues/10773) Perbaikan Pilihan pada pendaftaran anjungan belum optimal di tampilan Mobile.
26. [#10775](https://github.com/OpenSID/OpenSID/issues/10775) Perbaikan label pada form pengurus.
27. [#10774](https://github.com/OpenSID/OpenSID/issues/10774) Perbaikan typo pada kolom database mempengaruhi tampilan tulisan setelahnya di halaman website.
28. [#10671](https://github.com/OpenSID/OpenSID/issues/10671) Perbaikan data kesukuan (adat dan marga) tidak tersimpan ketika menambah keluarga dari menu keluarga.
29. [#10770](https://github.com/OpenSID/OpenSID/issues/10770) Perbaikan validasi format nomor surat dinas maksimal 35 karakter agar pengguna tidak menginputkan nomor surat yang terlalu panjang.
30. [#10785](https://github.com/OpenSID/OpenSID/issues/10785) Perbaikan tidak bisa simpan data isian ketenagakerjaan di bagian keterangan sosial DTKS/DTSEN.
31. [#10790](https://github.com/OpenSID/OpenSID/issues/10790) Perbaikan tampilan hasil cetak kolom "Pendidikan dan Pekerjaan" lampiran F-1.06.
32. [#10776](https://github.com/OpenSID/OpenSID/issues/10776) Perbaikan stunting web filter tidak berfungsi dan data pada widget masih static global.
33. [#10778](https://github.com/OpenSID/OpenSID/issues/10778) Perbaikan typo Pekerjaan pada saat Tambah Data Penduduk.

### KEAMANAN
1. [#5694](https://github.com/OpenSID/OpenSID/issues/5694) Update package untuk mengatasi kerentanan keamanan.
2. [#5724](https://github.com/OpenSID/premium/issues/5724) perbaikan vulnerability Blind Server-Side Request Forgery pada TinyMCE Image Plugin.

### TEKNIS
1. [#10691](https://github.com/OpenSID/OpenSID/issues/10691) Perbaikan htaccess bawaan opensid yang error untuk server apache
2. [#10589](https://github.com/OpenSID/OpenSID/issues/10589) Melakukan refactor pemisahkan migrasi install baru untuk modul.
3. [#10743](https://github.com/OpenSID/OpenSID/issues/10743) Pengaturan sebutan wilayah desa dan jabatan di satu tempat.
4. [#10762](https://github.com/OpenSID/premium/issues/5851) Melakukan refactor menjadikan fitur scan folder desa menjadi paket module tambahan.

