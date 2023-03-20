Di rilis ini, versi 23.03-pasca menyediakan fitur TTE dan penyesuaian alur pemeriksaan surat. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada Irvan1609 yang terus berkontribusi.

#### Penambahan Fitur

1. [#5232](https://github.com/OpenSID/OpenSID/issues/5232) Penambahan pengaturan default jenis peta yang digunakan.
2. [#5308](https://github.com/OpenSID/OpenSID/issues/5308) Penambahan unduh template rtf bawaan sistem yang sudah di ubah.
3. [#5266](https://github.com/OpenSID/OpenSID/issues/5266) Penambahan tampilan dan pencarian tag id card pada data pemilih.
4. [#5304](https://github.com/OpenSID/OpenSID/issues/5304) Penambahan format surat TinyMCE kepermohonan surat layanan mandiri.
5. [#5270](https://github.com/OpenSID/OpenSID/issues/5270) Sesuaikan alur pemeriksaan surat.
6. [#5273](https://github.com/OpenSID/OpenSID/issues/5273) Penambahan notifikasi ketika ada surat permintaan surat untuk diperiksa atau ditandatangani.
7. [#5412](https://github.com/OpenSID/OpenSID/issues/5412) Penambahan fitur untuk menentukan kepala desa dan sekdes hanya di pemerintah desa.
8. [#5233](https://github.com/OpenSID/OpenSID/issues/5233) Penambahan menu tupoksi pada menu pemerintah desa.
9. [#4962](https://github.com/OpenSID/OpenSID/issues/4962) Penambahan filter data pencarian pendudukan bukan peserta program bantuan.
10. [#5301](https://github.com/OpenSID/OpenSID/issues/5301) Penambahan input ibu dengan status di kk selain istri tidak bisa ( menantu dll ) dan anak ( cucu dll) di menu kia.
11. [#4464](https://github.com/OpenSID/OpenSID/issues/4464) Penambahan opsi sembunyikan luas area di map.
12. [#5271](https://github.com/OpenSID/OpenSID/issues/5271) Penambahan penyesuaian alur pemeriksaan surat pada layanan mandiri.
13. [#1161](https://github.com/OpenSID/premium/issues/1161) Penyesuaian fungsi tombol a.n dan u.b pada pengaturan perangkat desa.
14. [#5480](https://github.com/OpenSID/OpenSID/issues/5480) Menyediakan fungsi untuk menghapus cache desa dan blade pada menu pengaturan info sistem diadmin.
15. [#4838](https://github.com/OpenSID/OpenSID/issues/4838) Penambahan tidak menggunakan akseptor KB pada data penduduk.
16. [#5495](https://github.com/OpenSID/OpenSID/issues/5495) Penambahan default orientasi kertas dan ukuran kertas pada tinymce.
17. [#5295](https://github.com/OpenSID/OpenSID/issues/5295) Penambahan Form input hasil ukur berat & tinggi pada menu stunting.
18. [#5324](https://github.com/OpenSID/OpenSID/issues/5324) Penambahan detail data alamat ibu saat menginput data kia.
19. [#5489](https://github.com/OpenSID/OpenSID/issues/5489) Penambahan menu SDGS.
20. [#5492](https://github.com/OpenSID/OpenSID/issues/5492) Penambahan alasan tolak ketika verifikasi surat.
21. [#5319](https://github.com/OpenSID/OpenSID/issues/5319) Penambahan filter data anak berdasarkan nomor kk ibu.
22. [#5424](https://github.com/OpenSID/OpenSID/issues/5424) Penambahan log keluarga pindah datang pada laporan bulanan.
23. [#4835](https://github.com/OpenSID/OpenSID/issues/4835) Penambahan pengaturan untuk mengaktifkan / non aktifkan modul tte.
24. [#5410](https://github.com/OpenSID/OpenSID/issues/5410) Penambahan pengisian passphrase yang hanya bisa dilakukan oleh kepala desa dan hanya saat tte aktif.
25. [#5407](https://github.com/OpenSID/OpenSID/issues/5407) Penambahan template untuk penempatan kotak Info BSRE di surat TinyMCE.
26. [#4853](https://github.com/OpenSID/OpenSID/issues/4853) Penambahan penanganan exception dalam proses TTE.
27. [#5501](https://github.com/OpenSID/OpenSID/issues/5501) Penambahan validasi anjungan dari layanan.
28. [#5448](https://github.com/OpenSID/OpenSID/issues/5448) Penambahan tombol memberhentikan proses backup inkremental.
29. [#5503](https://github.com/OpenSID/OpenSID/issues/5503) Penambahan inputan waktu pemeriksaan dimenu stunting.
30. [#5315](https://github.com/OpenSID/OpenSID/issues/5315) Penambahan pengaturan surat untuk menampilkan header dan footer surat TinyMCE.
31. [#5302](https://github.com/OpenSID/OpenSID/issues/5302) Penambahan pilih data penduduk dinamis pada surat TinyMCE.
32. [#4835](https://github.com/OpenSID/OpenSID/issues/4835) Penambahan fitur TTE versi demo.

#### Perbaikan BUG

1. [#5452](https://github.com/OpenSID/OpenSID/issues/5452) Menghilangkan tampil tag ["desa"] pada halaman periksa.
2. [#5457](https://github.com/OpenSID/OpenSID/issues/5457) Menambahkan inputan manual untuk nama desa jika tidak ada respon dari pantau.
3. [#1188](https://github.com/OpenSID/premium/issues/1188) Menambahkan notifikasi beberapa modul gagal muat jika tidak terhubung ke internet.
4. [#5472](https://github.com/OpenSID/OpenSID/issues/5472) Mengatasi data entri NIK warga luar desa tidak bisa input lebih dari satu kali di menu buku tanah.
5. [#5471](https://github.com/OpenSID/OpenSID/issues/5471) Memperbaiki perintah group by pada Laporan rincian realisasi hasil dari impor siekudes.
6. [#5456](https://github.com/OpenSID/OpenSID/issues/5456) Memperbaiki buat surat tinymce yang tidak berhasil generate pdf.
7. [#1202](https://github.com/OpenSID/premium/issues/1202) Menghapus pengecekan url tidak valid di identitas desa.
8. [#5475](https://github.com/OpenSID/OpenSID/issues/5475) Perbaiki last login tidak diperbarui setelah login.
9. [#5478](https://github.com/OpenSID/OpenSID/issues/5478) Memperbaiki jJudul pada jumlah covid desa tidak tampil.
10. [#5477](https://github.com/OpenSID/OpenSID/issues/5477) Memperbaiki notifikasi saat mengembalikan status dasar penduduk.
11. [#5486](https://github.com/OpenSID/OpenSID/issues/5486) Memperbaiki pencarian nama ibu dari tambah anak ke 2 dari menu kia.
12. [#5487](https://github.com/OpenSID/OpenSID/issues/5487) Memperbaiki tampilan konsisten infrastruktur desa yang ditampilkan pada web dan admin.
13. [#5497](https://github.com/OpenSID/OpenSID/issues/5497) Memperbaiki tampilan inputan klasifikasi pindah pada form lampiran F-1.03.
14. [#5504](https://github.com/OpenSID/OpenSID/issues/5504) Memperbaiki tampilan data pada tabel status gizi anak menjadi singkatan huruf.
15. [#5513](https://github.com/OpenSID/OpenSID/issues/5513) Memperbaiki tampilan pengaturan margin.
16. [#5498](https://github.com/OpenSID/OpenSID/issues/5498) Memperbaiki laporan analisis saat di unduh datanya tidak tampil.
17. [#1187](https://github.com/OpenSID/premium/issues/1187) Cek koneksi saat memanggil asset external agar dapat opensid jalan tanpa internet.
18. [#5476](https://github.com/OpenSID/OpenSID/issues/5476) Memperbaiki luas tanah tidak bisa tersimpan di menu bumindes tanah kas desa.
19. [#5522](https://github.com/OpenSID/OpenSID/issues/5522) Memperbaiki footer surat mepet dengan isi surat.
20. [#5482](https://github.com/OpenSID/OpenSID/issues/5482) Merubah nama file foto aparatur desa yang menampilkan NIK.
21. [#5528](https://github.com/OpenSID/OpenSID/issues/5528) Memperbaiki pengaturan masa berlaku tidak berfungsi pada surat pengantar.
22. [#5527](https://github.com/OpenSID/OpenSID/issues/5527) Memperbaiki edit sebelum cetak PDF tidak berfungsi.
23. [#5505](https://github.com/OpenSID/OpenSID/issues/5505) Memperbaiki scorecard konvergensi pada tabel jumlah sasaran dan tabel hasil pengukuran tidak berfungsi dengan benar.
24. [#5545](https://github.com/OpenSID/OpenSID/issues/5545) Memperbaiki judul peraturan desa tidak tersimpan / kosong.
25. [#5541](https://github.com/OpenSID/OpenSID/issues/5541) Memperbaiki surat rtf mengganti masa berlaku menjadi "-" ketika pengaturan masa berlaku 0.
26. [#1277](https://github.com/OpenSID/OpenSID/issues/1277) Perbaiki sinkronisasi program bantuan yang terhapus di opendk.

#### Perubahan Teknis

1. Teknis rapikan penulisan code seeder data awal.
2. Pengecekan validasi premium dimulai dari identitas desa.
3. Merapikan select option pada pilih tahun id menu status desa.
4. Menambahkan required pada form edit album.
5. Mengembalikan fungsi pencarian cetak surat.
6. Menambahkan penjelasan tambahan di impor analisis.
7. Memperbarui .gitignore.
8. Deploy Website Berputar.