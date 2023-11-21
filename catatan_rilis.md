Di rilis ini, versi 2311.1.0 berisi penambahan [untuk diisi] dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [untuk diisi] baru mulai berkontribusi.


#### Penambahan Fitur

1. [#2260](https://github.com/OpenSID/premium/issues/2260) Penambahan alur masuk kembali setelah ganti password.
2. [#7280](https://github.com/OpenSID/OpenSID/issues/7280) Penambahan pengaturan kode isian alias surat TinyMCE.
3. [#7348](https://github.com/OpenSID/OpenSID/issues/7348) Penambahan pengaturan penentuan pelapor dan pemohon pada pengaturan surat TinyMCE.
4. [#7347](https://github.com/OpenSID/OpenSID/issues/7347) Penambahan identitas terlapor dan pelapor pada arsip layanan.
5. [#7359](https://github.com/OpenSID/OpenSID/issues/7359) Penambahan laporan khusus dana desa pada laporan keuangan.


#### Perbaikan Bug

1. [#3029](https://github.com/OpenSID/premium/issues/3029) Perbaikan pengaturan surat untuk ditampilkan dilayanan mandiri hanya untuk penduduk individu saja tanpa menampilkan data lain.
2. [#2839](https://github.com/OpenSID/OpenSID/issues/2839) Perbaikan judul pada cetak/unduh embaga/kelompok anggota.
3. [#3051](https://github.com/OpenSID/OpenSID/issues/3051) Perbaikan seeder data awal.
4. [#7263](https://github.com/OpenSID/OpenSID/issues/7263) Perbaikan fungsi pada tombol batal pada input select2 dan select2tags.
5. [#7273](https://github.com/OpenSID/OpenSID/issues/7273) Perbaikan jenis dokumen penduduk.
6. [#7265](https://github.com/OpenSID/OpenSID/issues/7265) Perbaikan salin template keuangan manual untuk tahun yang sama.
7. [#7262](https://github.com/OpenSID/OpenSID/issues/7262) Perbaikan tombol hapus data keluarga mengikuti aturan data lengkap.
8. [#7303](https://github.com/OpenSID/OpenSID/issues/7303) Perbaikan halama periksa log keluarga.
9. [#7091](https://github.com/OpenSID/OpenSID/issues/7091) Perbaikan pencarian penduduk berdasarkan tag_id_card pada cetak surat.
10. [#7069](https://github.com/OpenSID/OpenSID/issues/7069) Perbaikan surat tinymce penghasilan orang tua menggunakan opertor hitung dan terbilang.
11. [#7310](https://github.com/OpenSID/OpenSID/issues/7310) Perbaikan surat bawaan tinymce.
12. [#7339](https://github.com/OpenSID/OpenSID/issues/7339) Perbaikan ejaan dari fungsi kembalikan foto bawaan pada tambah/ubah data penduduk.
13. [#7350](https://github.com/OpenSID/OpenSID/issues/7350) Perbaikan ketersediaan tombol tambah data penduduk dari penduduk yang sudah ada pada modul keluarga.
14. [#7363](https://github.com/OpenSID/OpenSID/issues/7363) Perbaikan alamat pada data penduduk.
15. [#7360](https://github.com/OpenSID/OpenSID/issues/7360) Perbaikan pratinjau surat TinyMCE.
16. [#7398](https://github.com/OpenSID/OpenSID/issues/7398) Perbaikan link api tte yang konflik dengan api SiapPakai.
17. [#7362](https://github.com/OpenSID/OpenSID/issues/7362) Perbaikan mengaktifkan atau menonaktifkan berita utama pada artikel.
18. [#7340](https://github.com/OpenSID/OpenSID/issues/7340) Perbaikan ubah status dasar penduduk dengan SHDK kepala keluarga namun belum terdaftar dalam keluarga.



#### Penyesuaian Teknis

1. [#6924](https://github.com/OpenSID/OpenSID/issues/6924) Penyesuaian cara menampilkan ubah data wilayah/lokasi pada identitas desa dan wilayah administratif.
2. [#3060](https://github.com/OpenSID/premium/issues/3060) Penyesuaian reset input group button secara global.
3. [#7274](https://github.com/OpenSID/OpenSID/issues/7274) Penyesuaian nama dan url modul hom_sid.
4. [#3061](https://github.com/OpenSID/premium/issues/3061) Penyesuaian data pada pada kolom id_kk tabel tweb_penduduk menggunakan null sebagai penanda kk kosong.
5. [#7230](https://github.com/OpenSID/OpenSID/issues/7230) Penyesuaian format ukuran font menggunakan pt.
6. [#7338](https://github.com/OpenSID/OpenSID/issues/7338) Penyesuaian pilihan shdk pada pengaturan form surat tinymce agar bisa memilih lebih dari satu pilihan.
7. [#1611](https://github.com/OpenSID/premium/issues/1611) Penyesuaian modul qrcode di controller tersendiri.
8. [#7345](https://github.com/OpenSID/OpenSID/issues/7345) Penyesuaian pilihan jenis peristiwa pada pengaturan form surat tinymce agar bisa memilih lebih dari satu pilihan.
9. [#7314](https://github.com/OpenSID/OpenSID/issues/7314) Penyesuaian tabel untuk menampung data token FCM dan log notifikasi.
10. [#6837](https://github.com/OpenSID/OpenSID/issues/6837) Penyesuaian isian tanggal berlaku surat hanya bisa di isi sesuai rentang pada pengaturan suratnya.
11. [#7361](https://github.com/OpenSID/OpenSID/issues/7361) Penyesuaian kode isian yang berupa angka, gambar dan jam.
12. [#6726](https://github.com/OpenSID/OpenSID/issues/6726) Penyesuaian modul pengaduan admin menggunakan ORM dan Blade.

#### Peningkatan Keamanan

1. [#7365](https://github.com/OpenSID/OpenSID/issues/7365) Penambahan notifikasi ke telegram jika terdapat gagal login sebanyak 3 kali.
2. [#7364](https://github.com/OpenSID/OpenSID/issues/7364) Pencatatan aktifitas pengguna yang berhasil masuk.
3. [#7366](https://github.com/OpenSID/OpenSID/issues/7366) Pendeteksi lokasi masuk dari lokasi yang tidak biasa.