Di rilis ini, versi 22.08-premium-beta01 [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.
Terima kasih pada Irvan1609 yang terus berkontribusi.

#### Penambahan Fitur

1. [#5232](https://github.com/OpenSID/OpenSID/issues/5232) Penambahan pengaturan default jenis peta yang digunakan.
2. [#5308](https://github.com/OpenSID/OpenSID/issues/5308) Penambahan unduh template rtf bawaan sistem yang sudah di ubah.
3. [#5266](https://github.com/OpenSID/OpenSID/issues/5266) Penambahan tampilan dan pencarian tag id card pada data pemilih.
4. [#5304](https://github.com/OpenSID/OpenSID/issues/5304) Menambahkan format surat tinymce kepermohonan surat layanan mandiri.
5. [#5270](https://github.com/OpenSID/OpenSID/issues/5270) Sesuaikan alur pemeriksaan surat.
6. [#5273](https://github.com/OpenSID/OpenSID/issues/5273) Menambahkan notifikasi ketika ada surat permintaan surat untuk diperiksa atau ditandatangani.
7. [#5412](https://github.com/OpenSID/OpenSID/issues/5412) Menambahkan fitur untuk menentukan kepala desa dan sekdes hanya di pemerintah desa.
8. [#5233](https://github.com/OpenSID/OpenSID/issues/5233) Menambahkan menu tupoksi pada menu pemerintah desa.
9. [#4962](https://github.com/OpenSID/OpenSID/issues/4962) Menambahkan filter data pencarian pendudukan bukan peserta program bantuan.
10. [#5301](https://github.com/OpenSID/OpenSID/issues/5301) Menambahkan input ibu dengan status di kk selain istri tidak bisa ( menantu dll ) dan anak ( cucu dll) di menu kia.
11. [#4464](https://github.com/OpenSID/OpenSID/issues/4464) Menambahkan opsi sembunyikan luas area di map.
12. [#5271](https://github.com/OpenSID/OpenSID/issues/5271) Menambahkan penyesuaian alur pemeriksaan surat pada layanan mandiri.
13. [#1161](https://github.com/OpenSID/premium/issues/1161) Penyesuaian fungsi tombol a.n dan u.b pada pengaturan perangkat desa.
14. [#5480](https://github.com/OpenSID/OpenSID/issues/5480) Menyediakan fungsi untuk menghapus cache desa dan blade pada menu pengaturan info sistem diadmin.
15. [#4838](https://github.com/OpenSID/OpenSID/issues/4838) Menambahkan tidak menggunakan akseptor KB pada data penduduk.
16. [#5495](https://github.com/OpenSID/OpenSID/issues/5495) Penambahan default orientasi kertas dan ukuran kertas pada tinymce.
17. [#5295](https://github.com/OpenSID/OpenSID/issues/5295) Penambahan Form input hasil ukur berat & tinggi pada menu stunting.
18. [#5324](https://github.com/OpenSID/OpenSID/issues/5324) Menampilkan detail data alamat ibu saat menginput data kia.
19. [#5489](https://github.com/OpenSID/OpenSID/issues/5489) Menambahkan menu SDGS.
20. [#5492](https://github.com/OpenSID/OpenSID/issues/5492) Menambahkan alasan tolak ketika verifikasi surat.
21. [#5319](https://github.com/OpenSID/OpenSID/issues/5319) Menambahkan filter data anak berdasarkan nomor kk ibu.
22. [#5424](https://github.com/OpenSID/OpenSID/issues/5424) Menambahkan log keluarga pindah datang pada laporan bulanan.
23. [#4835](https://github.com/OpenSID/OpenSID/issues/4835) Menambahkan pengaturan untuk mengaktifkan / non aktifkan modul tte.
24. [#5410](https://github.com/OpenSID/OpenSID/issues/5410) Menambahkan pengisian passphrase yang hanya bisa dilakukan oleh kepala desa dan hanya saat tte aktif.
25. [#5407](https://github.com/OpenSID/OpenSID/issues/5407) Menambahkan template untuk penempatan kotak Info BSRE di surat TinyMCE.
26. [#4853](https://github.com/OpenSID/OpenSID/issues/4853) Menambahkan  penanganan exception dalam proses TTE.
27. [#5501](https://github.com/OpenSID/OpenSID/issues/5501) Menambahkan validasi anjungan dari layanan.

#### Perbaikan BUG

1.[#5452](https://github.com/OpenSID/OpenSID/issues/5452) Menghilangkan tampil tag ["desa"] pada halaman periksa.
2.[#5457](https://github.com/OpenSID/OpenSID/issues/5457) Menambahkan inputan manual untuk nama desa jika tidak ada respon dari pantau.
3.[#1188](https://github.com/OpenSID/premium/issues/1188) Menambahkan notifikasi beberapa modul gagal muat jika tidak terhubung ke internet.
4.[#5472](https://github.com/OpenSID/OpenSID/issues/5472) Mengatasi data entri NIK warga luar desa tidak bisa input lebih dari satu kali di menu buku tanah.
5.[#5471](https://github.com/OpenSID/OpenSID/issues/5471) Memperbaiki perintah group by pada Laporan rincian realisasi hasil dari impor siekudes.
6.[#5456](https://github.com/OpenSID/OpenSID/issues/5456) Memperbaiki buat surat tinymce yang tidak berhasil generate pdf.
7.[#1202](https://github.com/OpenSID/premium/issues/1202) Menghapus pengecekan url tidak valid di identitas desa.
8.[#5475](https://github.com/OpenSID/OpenSID/issues/5475) Perbaiki last login tidak diperbarui setelah login.
9.[#5478](https://github.com/OpenSID/OpenSID/issues/5478) Memperbaiki jJudul pada jumlah covid desa tidak tampil.
10.[#5477](https://github.com/OpenSID/OpenSID/issues/5477) Memperbaiki notifikasi saat mengembalikan status dasar penduduk.
11.[#5486](https://github.com/OpenSID/OpenSID/issues/5486) Memperbaiki pencarian nama ibu dari tambah anak ke 2 dari menu kia.
12.[#5487](https://github.com/OpenSID/OpenSID/issues/5487) Memperbaiki tampilan konsisten infrastruktur desa yang ditampilkan pada web dan admin.
13.[#5497](https://github.com/OpenSID/OpenSID/issues/5497) Memperbaiki tampilan inputan klasifikasi pindah pada form lampiran F-1.03.
14.[#5504](https://github.com/OpenSID/OpenSID/issues/5504) Memperbaiki tampilan data pada tabel status gizi anak menjadi singkatan huruf.
15.[#5513](https://github.com/OpenSID/OpenSID/issues/5513) Memperbaiki tampilan pengaturan margin.
16.[#5498](https://github.com/OpenSID/OpenSID/issues/5498) Memperbaiki laporan analisis saat di unduh datanya tidak tampil.
17.[#1187](https://github.com/OpenSID/premium/issues/1187) Cek koneksi saat memanggil asset external agar dapat opensid jalan tanpa internet.
18.[#5476](https://github.com/OpenSID/OpenSID/issues/5476) Memperbaiki luas tanah tidak bisa tersimpan di menu bumindes tanah kas desa.
19.[#5522](https://github.com/OpenSID/OpenSID/issues/5522) Memperbaiki footer surat mepet dengan isi surat.
20.[#5482](https://github.com/OpenSID/OpenSID/issues/5482) Merubah nama file foto aparatur desa yang menampilkan NIK.

#### Perubahan Teknis

1. Teknis rapikan penulisan code seeder data awal.
2. Pengecekan validasi premium dimulai dari identitas desa.
3. Merapikan select option pada pilih tahun id menu status desa.
4. Menambahkan required pada form edit album.
5. Mengembalikan fungsi pencarian cetak surat.
6. Menambahkan penjelasan tambahan di impor analisis.
