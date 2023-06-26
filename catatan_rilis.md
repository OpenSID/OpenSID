Di rilis ini, versi 2306.2.0 berisi penambahan [isi disini] dan perbaikan lain yang diminta Komunitas SID.
Terima kasih pada [isi disini] yang terus berkontribusi.

Berkaitan dengan perubahan teknis pada poin 13 disarankan bagi pengguna yang sudah memiliki .htaccess pada folder assets sebelum rilis ini untuk dapat menghapus .htaccess sebelumnya dan akan digenerate ulang oleh rilis ini.

#### Penambahan Fitur

1. [#6572](https://github.com/OpenSID/OpenSID/issues/6630) Penambahan tombol hapus pada template ubahan desa.
2. [#6010](https://github.com/OpenSID/OpenSID/issues/6010) Penambahan pengaturan agar bisa menyesuaikan/menambahkan ukuran huruf 9pt 11pt 16pt 20pt 22pt pada template surat TinyMCE.
3. [#6710](https://github.com/OpenSID/OpenSID/issues/6710) Penambahan pengaturan menampilkan data geospasial pada halaman peta wilayah.
4. [#6643](https://github.com/OpenSID/OpenSID/issues/6643) Penambahan tombol simpan sementara pada pembuatan template surat TinyMCE.
5. [#6730](https://github.com/OpenSID/OpenSID/issues/6730) Penambahan unggah gambar pada data widget.
6. [#6592](https://github.com/OpenSID/OpenSID/issues/6592) Penambahan kode isian data wilayah dusun pada surat TinyMCE.
7. [#6542](https://github.com/OpenSID/OpenSID/issues/6542) Penambahan pengaturan margin global.
8. [#6600](https://github.com/OpenSID/OpenSID/issues/6600) Penambahan pilihan manual menggunakan select2-tag agar memudahkan.
9. [#6212](https://github.com/OpenSID/OpenSID/issues/6212) Penambahan surat TinyMCE keterangan domisili.
10. [#5772](https://github.com/OpenSID/OpenSID/issues/5772) Penambahan surat TinyMCE keterangan lahir mati.
11. [#6214](https://github.com/OpenSID/OpenSID/issues/6214) Penambahan surat TinyMCE keterangan kepemilikan kendaraan.
12. [#6223](https://github.com/OpenSID/OpenSID/issues/6223) Penambahan surat TinyMCE pengantar permohonan penerbitan buku pas lintas.
13. [#6766](https://github.com/OpenSID/OpenSID/issues/6766) Penambahan kode isian orang tua berdasarkan biodata penduduk.
14. [#6219](https://github.com/OpenSID/OpenSID/issues/6219) Penambahan surat TinyMCE keterangan penghasilan orangtua.
15. [#6208](https://github.com/OpenSID/OpenSID/issues/6208) Penambahan surat TinyMCE biodata penduduk.
16. [#6224](https://github.com/OpenSID/OpenSID/issues/6224) Penambahan surat TinyMCE perintah perjalanan dinas.
17. [#6226](https://github.com/OpenSID/OpenSID/issues/6226) Penambahan surat TinyMCE permohonan duplikat surat nikah.
18. [#6217](https://github.com/OpenSID/OpenSID/issues/6217) Penambahan surat TinyMCE keterangan penghasilan ayah.
19. [#6215](https://github.com/OpenSID/OpenSID/issues/6215) Penambahan surat TinyMCE keterangan kepemilikan tanah.
20. [#6762](https://github.com/OpenSID/OpenSID/issues/6762) Penambahan pilih lampiran lebih dari 1 pada pengaturan TinyMCE.
21. [#6541](https://github.com/OpenSID/OpenSID/issues/6541) Penambahan atribut required dengan checkbox pada pengaturan form isian surat.
22. [#6293](https://github.com/OpenSID/OpenSID/issues/6293) Penambahan fungsi batasan memory limit yang memperbolehkan backup database.
23. [#6779](https://github.com/OpenSID/OpenSID/issues/6779) Penambahan unduh surat TTE di layanan mandiri.
24. [#6227](https://github.com/OpenSID/OpenSID/issues/6227) Penambahan surat TinyMCE permohonan kartu keluarga.
25. [#6216](https://github.com/OpenSID/OpenSID/issues/6216) Penambahan surat TinyMCE keterangan pengantar rujuk/cerai.
26. [#6781](https://github.com/OpenSID/OpenSID/issues/6781) Penyesuaian jumlah lampiran sesuai pengaturan pada surat TinyMCE.
27. [#5770](https://github.com/OpenSID/OpenSID/issues/5770) Penambahan surat TinyMCE permohonan duplikat kelahiran.
28. [#6092](https://github.com/OpenSID/OpenSID/issues/6092) Penambahan sumber data dinamis sebagai kode isian untuk TinyMCE.

#### Perbaikan BUG

1. [#6590](https://github.com/OpenSID/OpenSID/issues/6590) Perbaikan cara menampilkan area desa/dusun/rt/rw pada toolbox peta modul pertanahan.
2. [#6651](https://github.com/OpenSID/OpenSID/issues/6651) Perbaikan cara menampilkan path peta yang tidak valid pada halaman admin.
3. [#6733](https://github.com/OpenSID/OpenSID/issues/6733) Perbaikan cara menampilkan data permohonan surat TinyMCE dari layanan mandiri.
4. [#6720](https://github.com/OpenSID/OpenSID/issues/6720) Perbaikan data sumi gagal tampil pada surat keterangan kelahiran.
5. [#6714](https://github.com/OpenSID/OpenSID/issues/6714) Perbaikan cara menampilkan data pada laporan penduduk dan laporan rekap penerima vaksin covid-19.
6. [#6669](https://github.com/OpenSID/OpenSID/issues/6669) Perbaikan cara hapus penduduk dan log penduduk jika data dianggap sudah lengkap.
7. [#6441](https://github.com/OpenSID/OpenSID/issues/6441) Perbaikan jumlah dan detail arsip layanan.
8. [#6737](https://github.com/OpenSID/OpenSID/issues/6737) Perbaikan notifikasi gagal simpan widget jika extention tidy yang diperlukan tidak aktif.
9. [#6729](https://github.com/OpenSID/OpenSID/issues/6729) Perbaikan ambil data SDGS yang tersimpan pada cache.
10. [#6718](https://github.com/OpenSID/OpenSID/issues/6718) Perbaikan validasi proses permohonan surat melalui layanan mandiri jika memiliki syarat.
11. [#6740](https://github.com/OpenSID/OpenSID/issues/6740) Perjelas notifikasi hapus wilayah jika terdapat penduduk pada wilayah tersebut.
12. [#6741](https://github.com/OpenSID/OpenSID/issues/6741) Perbaikan pengecekan ganti password default kehalaman admin.
13. [#6717](https://github.com/OpenSID/OpenSID/issues/6717) Perbaikan menampilkan data anggota keluarga pada cetak surat TinyMCE.
14. [#6728](https://github.com/OpenSID/OpenSID/issues/6728) Perbaikan unggah dokumen surat masuk.
15. [#6739](https://github.com/OpenSID/OpenSID/issues/6739) Perbaikan gagal migrasi penambahan relasi antar tabel karena menggunakan engine yang berbeda.
16. [#6745](https://github.com/OpenSID/OpenSID/issues/6745) Sediakan tombol perbaikan log bulanan.
17. [#6746](https://github.com/OpenSID/OpenSID/issues/6746) Pebaikan perhitungan dan detail log penduduk dan keluarga pada laporan bulanan.
18. [#6749](https://github.com/OpenSID/OpenSID/issues/6749) Perbaikan nama bidang pada belanja laporan keuangan berdasarkan permendagri nomor 20 tahun 2018.
19. [#6751](https://github.com/OpenSID/OpenSID/issues/6751) Perbaikan hapus data penduduk pada modul suplemen.
20. [#6742](https://github.com/OpenSID/OpenSID/issues/6742) Perbaikan atribut required pada data awal surat TinyMCE.
21. [#6721](https://github.com/OpenSID/OpenSID/issues/6721) Perbaikan data kelompok/lembaga yang tidak valid.
22. [#6759](https://github.com/OpenSID/OpenSID/issues/6759) Perbaikan migrasi dtks.
23. [#6757](https://github.com/OpenSID/OpenSID/issues/6757) Perbaikan standar wajib isi data penduduk berdasarkan yang ada pada kartu kerluaga dengan menghapus isian wajib pada 'cara hubung warga'.
24. [#6760](https://github.com/OpenSID/OpenSID/issues/6760) Perbaikan tombol yang tampil saat cetak surat tinymce.
25. [#6756](https://github.com/OpenSID/OpenSID/issues/6756) Perbaikan menampilkan data keluarga tanpa kepala keluarga dengan no kk sementara.
26. [#6758](https://github.com/OpenSID/OpenSID/issues/6758) Perbaikan unggah/ambil gambar di form penduduk melalui menu penduduk dan keluarga berbeda.
27. [#6763](https://github.com/OpenSID/OpenSID/issues/6763) Perbaikan permission coookie browser.
28. [#6771](https://github.com/OpenSID/OpenSID/issues/6771) Perbaikan pilih headline artikel.
29. [#6784](https://github.com/OpenSID/OpenSID/issues/6784) Perbaikan ikon baca pesan yang tertukar pada kotak masuk layanan mandiri.
30. [#6775](https://github.com/OpenSID/OpenSID/issues/6775) Perbaikan pendaftara pada buku tamu tanpa kamera.

#### Perubahan Teknis

1. [#6676](https://github.com/OpenSID/OpenSID/issues/6676) Penambahan migrasi pengaturan nonaktifkan tema untuk OpenKab.
2. [#6736](https://github.com/OpenSID/OpenSID/issues/6736) Penyesuaian backup dan restore database mengikuti perubahan OpenSID database gabungan.
3. [#5870](https://github.com/OpenSID/OpenSID/issues/5870) Penambahan pilihan amankan token pengguna yang sedang digunakan sebelum restore database.
4. [#2383](https://github.com/OpenSID/premium/issues/2383) Penyesuaian modul pelanggan dan pendaftaran kerjasama untuk website demo OpenSID tidak ditampilkan.
5. [#6748](https://github.com/OpenSID/OpenSID/issues/6748) Penambahan periksa database untuk kasus tanggal tidak valid pada tabel covid19_vaksin.
6. [#6750](https://github.com/OpenSID/OpenSID/issues/6750) Penyesuaian query statistik dengan penambahan config_id pada tabel referensi yang dituju.
7. [#6738](https://github.com/OpenSID/OpenSID/issues/6738) Penambahan peringatan jika app_key pada folder desa dan database tidak sesuai.
8. [#6716](https://github.com/OpenSID/OpenSID/issues/6716) Penggunaan slug yang unik pada tabel user_grup.
9. [#6622](https://github.com/OpenSID/OpenSID/issues/6622) Pisahkan modul layanan pelanggan ke third_party agar mudah di pindahkan ke rilis umum.
10. [#6754](https://github.com/OpenSID/OpenSID/issues/6754) Penyesuaian semua engine tabel menjadi InnoDB yang membuat migrasi gagal.
11. [#6761](https://github.com/OpenSID/OpenSID/issues/6761) Penghapusan view tambah pengguna dengan untuk pilihan data posyandu yang tidak digunakan.
12. [#6755](https://github.com/OpenSID/OpenSID/issues/6755) Pisahkan modul layanan pendaftaran kerjasama ke third_party agar mudah di pindahkan ke rilis umum.
13. [#2464](https://github.com/OpenSID/premium/issues/2464) Pembatasan akses file .php pada folder assets.
14. [#6780](https://github.com/OpenSID/OpenSID/issues/6780) Penyesuaian URL slug untuk symbol "/" di surat TinyMCE.
