#### [22.07]

Di rilis ini, versi 22.07, menyediakan Buku Kader Pemberdayaan Masyarakat di Buku Administrasi Pembangunan. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada andifahruddinakas dan agungsugiarto yang terus berkontribusi. Terima kasih pula pada Faisyal Rachman dan Firliani Fauziah yang baru mulai berkontribusi.

#### Penambahan Fitur

1. [#4435](https://github.com/OpenSID/OpenSID/issues/4181) Tambahkan Buku Kader Pemberdayaan Masyarakat di Buku Administrasi Pembangunan.
2. [#4470](https://github.com/OpenSID/OpenSID/issues/4470) Menambahkan informasi penandatangan di halaman verifikasi surat.
3. [#4527](https://github.com/OpenSID/OpenSID/issues/4527) Gunakan slug untuk detail pembangunan dan tambahkan tombol share.
4. [#4495](https://github.com/OpenSID/OpenSID/issues/4495) Sesuaikan input data pembangunan.
5. [#4486](https://github.com/OpenSID/OpenSID/issues/4486) Penyesuaian impor data penduduk berupa huruf untuk pendidikan_sedang, cacat, dll.
6. [#4405](https://github.com/OpenSID/OpenSID/issues/4405) Kaitkan indikator/pertanyaan analisis dengan perhitungan berdasarkan data kependudukan.
7. [#4541](https://github.com/OpenSID/OpenSID/issues/4541) Perketat penghapusan keluarga. Sekarang hanya keluarga kosong yang dapat dihapus.
8. [#3811](https://github.com/OpenSID/OpenSID/issues/3811) Tampilkan pilihan link galeri pada saat membuat menu statis.
9. [#4351](https://github.com/OpenSID/OpenSID/issues/4351) Buat slug untuk data suplemen.
10. [#4369](https://github.com/OpenSID/OpenSID/issues/4369) Sesuaikan format impor dan ekspor penduduk untuk data suku.
11. [#4544](https://github.com/OpenSID/OpenSID/issues/4544) Buat slug untuk data kelompok.

#### Perbaikan BUG

1. [#4510](https://github.com/OpenSID/OpenSID/issues/4510) Perbaiki cetak dokumen kesepakatan kerjasama desa.
2. [#4466](https://github.com/OpenSID/OpenSID/issues/4466) Perbaiki pencarian produk beserta halaman pada web lapak.
3. [#4435](https://github.com/OpenSID/OpenSID/issues/4435) Perbaiki daftar permohonan surat.
4. [#4531](https://github.com/OpenSID/OpenSID/issues/4531) Perbaiki tampilkan daftar rumah tangga sesuai statistik RTM.
5. Perbaiki statistik kependudukan berbasis rentang umur, yaitu Umur (Rentang), Umur (Kategori) dan Akta Kelahiran.
6. Perbaiki statistik kependudukan hubungan keluarga, untuk menangani kasus data yang belum diisi.
7. [#4530](https://github.com/OpenSID/OpenSID/issues/4530) Ubah data Akseptor KB yang tidak valid supaya statistik Akseptor KB tampil benar.
8. [#4530](https://github.com/OpenSID/OpenSID/issues/4532) Perbaiki link detail pembangunan pada halaman peta.
9. [#4502](https://github.com/OpenSID/OpenSID/issues/4502) Perbaiki status hubungan dalam keluarga untuk famili menjadi famili lain.
10. [#4514](https://github.com/OpenSID/OpenSID/issues/4514) Perbaiki dan sederhanakan tombol kemabli pada modul analisis.
11. [#4513](https://github.com/OpenSID/OpenSID/issues/4513) Jangan tampilkan aksi pecah kk untuk keluarga tidak aktif dengan anggota keluarga 0.
12. [#4498](https://github.com/OpenSID/OpenSID/issues/4498) Hapus data analisis_parameter dengan responden 0 untuk tipe pertanyaan 3 dan 4.
13. [#4499](https://github.com/OpenSID/OpenSID/issues/4499) Perbaiki lampiran surat_ket_kelahiran (F-21.01).
14. Perbaiki format waktu (jam) pesan yang dikirimkan lewat telegram.
15. Perbaiki tampilan kelengkapan dokumen di layanan mandiri.
16. [#4538](https://github.com/OpenSID/OpenSID/issues/4538) Perbaiki error impor program bantuan untuk kasus template menggunakan format date untuk tanggal.
17. [#4535](https://github.com/OpenSID/OpenSID/issues/4535) Perbaiki NIK dan No KK Sementara pada Surat Permohonan Kartu Keluarga Baru.
18. Sekarang pembuatan keluarga baru dari penduduk masuk tercatat secara benar di laporan bulanan.
19. [#4560](https://github.com/OpenSID/OpenSID/issues/4560) Migrasi untuk nik dan no_kk sementara kadang error untuk kasus tertentu.
20. [#463](https://github.com/OpenSID/premium/issues/463) Perbaiki develbar agar tidak menggangu proses compress saat cetak surat.
21. [#4565](https://github.com/OpenSID/OpenSID/issues/4565) Perbaiki foto pembangunan terlihat besar.
22. [#4567](https://github.com/OpenSID/OpenSID/issues/4567) Perbaiki migrasi slug pembangunan tidak berjalan keseluruhan.
23. [#4566](https://github.com/OpenSID/OpenSID/issues/4566) Perbaiki login ektp dan persuratan di layanan mandiri.
24. [#4554](https://github.com/OpenSID/OpenSID/issues/4554) Sesuaikan view verifikasi surat berdasarkan tema yang digunakan.
25. [#4568](https://github.com/OpenSID/OpenSID/issues/4568) Perbaiki error saat mengakses modul rekapitulasi jumlah penduduk.
26. [#4549](https://github.com/OpenSID/OpenSID/issues/4549) Perbaiki error di penduduk penerima bantuan, tidak kompatibel.
27. [#4570](https://github.com/OpenSID/OpenSID/issues/4570) Perbaiki error tambah / ubah kader dengan menggunakan .htaccess.
28. [#4378](https://github.com/OpenSID/OpenSID/issues/4378) Penyesuaian daftar surat ubahan desa secara otomatis.
29. [#4392](https://github.com/OpenSID/OpenSID/issues/4392) Perbaiki status perkwinan modul penduduk dan keluarga.
30. [#4564](https://github.com/OpenSID/OpenSID/issues/4564) Perbaiki pengaturan peta yang tidak dapat menyimpan warna.
31. [#4585](https://github.com/OpenSID/OpenSID/issues/4585) Perbaiki tampilkan notifikasi error, jika widget gagal disimpan beserta penyebabnya.
32. [#4598](https://github.com/OpenSID/OpenSID/issues/4598) Perbaiki Error impor SIAK.
33. [#4542](https://github.com/OpenSID/OpenSID/issues/4542) Perbaiki Cetak Surat Kelahiran
34. [#4576](https://github.com/OpenSID/OpenSID/issues/4576) Perbaiki Grup Pengguna
35. [#2767](https://github.com/OpenSID/OpenSID/issues/2767) Perbaiki Menu Sidebar,collapse ketika kita pilih menu.
36. [#4596](https://github.com/OpenSID/OpenSID/issues/4596) Perbaiki Suplemen Tidak dapat impor data suplemen sasaran keluarga
37. [#4595](https://github.com/OpenSID/OpenSID/issues/4595) Perbaiki Ubah panjang value pada kolom tag_id_card.
38. [#4593](https://github.com/OpenSID/OpenSID/issues/4593) Error saat menyimpan peta, dengan data yg tidak lengkap.
39. [#4574](https://github.com/OpenSID/OpenSID/issues/4574) Tampilkan data artikel sesuai dengan pengaturan Grup dan Pengguna.
40. [#4600](https://github.com/OpenSID/OpenSID/issues/4600) Perbaiki import data penduduk berulang.
41. [#4602](https://github.com/OpenSID/OpenSID/issues/4602) Tampilkan Status Desa Terdaftar pada Layanan Pelanggan.

#### Perubahan Teknis

1. Tambahkan define DESAPATH pada filemanager.
2. Pindahkan config pantau ke sistem.
3. Sembunyikan token_opensid pada pengaturan.
4. Jangan kirim data ke pantau jika versi demo / development.
5. Sediakan pengaturan config untuk mempermudah proses rilis.
6. Perbaiki template dokumen kerjasama.
7. Perbaiki mengurut view yang bergantungan di backup database.
8. Sesuaikan impor data untuk mode demo.
9. Kelompokkan bagian layanan mandiri agar mudah diubah.
10. Sederhanakan kondisi menyisipkan gambar pada surat.
11. Gunakan composer untuk mengelola package library.
12. Implementasi Coding Style Standard (php-cs-fixer) meggunakan syle Codeigniter 4.
13. Hapus laporan bulanan lama yang tidak digunakan.
14. Penyerdahanaan proses migrasi.
15. Pindahkan migrasi opensid-api ke opensid.
16. Load model ketika dibutuhkan saja untuk mempercepat.
17. Hapus duplikasi assets print preview.
18. Seragamkan gambar pengguna, supaya lebih jelas.