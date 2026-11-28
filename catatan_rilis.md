Rilis versi 2602.1.0 ini berisi [untuk diisi] dan perbaikan lainnya yang diminta oleh komunitas SID.

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
12. [#10829](https://github.com/OpenSID/OpenSID/issues/10829) Penambahan fitur penduduk lepas agar dapat ditambah menjadi kepala keluarga dan shdk otomatis berubah jadi kepala keluarga.
13. [#10824](https://github.com/OpenSID/OpenSID/issues/10824) Penambahan fitur indikator required (*) pada form pendaftaran layanan mandiri.
14. [#10837](https://github.com/OpenSID/OpenSID/issues/10837) Penambahan fitur penjelasan pada checklist impor program bantuan.
15. [#10817](https://github.com/OpenSID/OpenSID/issues/10817) Penambahan fitur upload SK lembaga dan SK pengangkatan pengurus lembaga.
16. [#10748](https://github.com/OpenSID/OpenSID/issues/10748) Penambahan fitur pemberitahuan pemesanan hosting yang sudah expired.
17. [#8947](https://github.com/OpenSID/OpenSID/issues/8947) Penambahan fitur menampilkan foto pengguna.


### BUG

1. [#10855](https://github.com/OpenSID/OpenSID/issues/10855) Perbaikan Anjungan bisa diakses oleh siapapun walau belum didaftarkan.
2. [#10850](https://github.com/OpenSID/OpenSID/issues/10850) Perbaikan database pollution & broken logic pada Modul Dokumen Penduduk.
3. [#10859](https://github.com/OpenSID/OpenSID/issues/10859) Perbaikan sebutan dusun tidak tampil pada pilihan lokasi pembangunan.
4. [#10852](https://github.com/OpenSID/OpenSID/issues/10852) Perbaikan race condition & credential reuse pada Komentar Artikel.
5. [#10851](https://github.com/OpenSID/OpenSID/issues/10851) Perbaikan perhitungan peserta program bantuan.
6. [#10853](https://github.com/OpenSID/OpenSID/issues/10853) Perbaikan ubah tanggal periksa di pemantauan anak usia 0-2 tahun.
7. [#10863](https://github.com/OpenSID/OpenSID/issues/10863) Perbaikan penanganan notif gagal saat upload gambar di halaman pengaturan aplikasi.
8. [#5983](https://github.com/OpenSID/premium/issues/5983) Perbaikan stored XSS via SVG preview & logic error pada fungsi previewImage di Halaman Seting Aplikasi.
9. [#10866](https://github.com/OpenSID/OpenSID/issues/10866) Perbaikan gagal mengirim Pesan di Menu Layanan Mandiri.
10. [#10868](https://github.com/OpenSID/OpenSID/issues/10868) Perbaikan tombol detail dan ubah status pada kotak masuk (Mailbox).
11. [#10869](https://github.com/OpenSID/OpenSID/issues/10869) Perbaikan gagal impor grup pengguna.
12. [#10871](https://github.com/OpenSID/OpenSID/issues/10871) Perbaikan video youtube anjungan tidak muncul.
13. [#10867](https://github.com/OpenSID/OpenSID/issues/10867) Perbaikan error login Layanan mandiri setelah reset pin.
14. [#10854](https://github.com/OpenSID/OpenSID/issues/10854) Perbaikan gagal backup .sid pada database gabungan di Kabupaten Lima Puluh Kota.
15. [#10838](https://github.com/OpenSID/OpenSID/issues/10838) Perbaikan fitur impor bip di penduduk format SIAK.
 

### KEAMANAN
1. [#5974](https://github.com/OpenSID/premium/issues/5974) Perbaikan kerentanan kerentanan blind SSRF.
2. [#5982](https://github.com/OpenSID/premium/issues/5982) Perbaikan kerentanan pada TinyMCE Media Plugin.

### TEKNIS
1. [#10792](https://github.com/OpenSID/OpenSID/issues/10792) Migrasi versi opensid umum ke versi premium.
2. [#10860](https://github.com/OpenSID/OpenSID/issues/10860) Hapus order pada ceklist daftar anggota keluarga.
3. [#10858](https://github.com/OpenSID/OpenSID/issues/10858) Otomatis collapse (hide and show) pada statistik bantuan agar tidak terlalu panjang.
4. [#10857](https://github.com/OpenSID/OpenSID/issues/10857) Modul bawaan tidak perlu ditampilkan pada Paket Terpasang.
5. [#10827](https://github.com/OpenSID/OpenSID/issues/10827) Penambahan build version untuk membedakan versi Umum dan Premium pada proses migrasi.
6. [#10861](https://github.com/OpenSID/OpenSID/issues/10872) Optimasi modul info sistem.
7. [#10870](https://github.com/OpenSID/OpenSID/issues/10870) Penambahan constraint artikel dan periksa duplikat slug judul artikel.

