#### [21.11-premium-beta02]

Di rilis ini, versi 21.11-premium-beta02, menyediakan [untuk_diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [untuk_diisi] yang terus berkontribusi.

#### Penambahan Fitur
1. [#4435](https://github.com/OpenSID/OpenSID/issues/4181) Tambahkan Buku Kader Pemberdayaan Masyarakat di Buku Administrasi Pembangunan.
2. [#4470](https://github.com/OpenSID/OpenSID/issues/4470) Menambahkan informasi penandatangan di halaman verifikasi surat.
3. [#4527](https://github.com/OpenSID/OpenSID/issues/4527) Gunakan slug untuk detail pembangunan dan tambahkan tombol share.
4. [#4495](https://github.com/OpenSID/OpenSID/issues/4495) Sesuaikan input data pembangunan.
5. [#4486](https://github.com/OpenSID/OpenSID/issues/4486) Penyesuaian impor data penduduk berupa huruf untuk pendidikan_sedang, cacat, dll.
6. [#4405](https://github.com/OpenSID/OpenSID/issues/4405) Kaitkan indikator/pertanyaan analisis dengan perhitungan berdasarkan data kependudukan.
7. [#4541](https://github.com/OpenSID/OpenSID/issues/4541) Perketat penghapusan keluarga. Sekarang hanya keluarga kosong yang dapat dihapus.
8. [#3811](https://github.com/OpenSID/OpenSID/issues/3811) Tampilkan pilihan link galeri pada saat membuat menu statis.


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
18. Seramkan gambar pengguna, buat lebih jelas.