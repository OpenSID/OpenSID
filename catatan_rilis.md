Di rilis ini, versi 22.12-premium-rev05 ditambahkan [isi disini]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [isi disini] yang terus berkontribusi.

#### Penambahan Fitur

1. [#5994](https://github.com/OpenSID/OpenSID/issues/5994) Penambahan pengaturan jenis surat yang dapat dikirim ke opendk pada pengaturan surat.
2. [#6125](https://github.com/OpenSID/OpenSID/issues/6125) Sediakan informasi tag qrcode ketika pengaturan TTE camat diaktifkan.
3. [#5340](https://github.com/OpenSID/OpenSID/issues/5340) Penambahan buku tamu.

#### Perbaikan BUG

1. [#1698](https://github.com/OpenSID/premium/issues/1698) Perbaikan validasi form unggah gambar pada modul pengaturan.
2. [#6074](https://github.com/OpenSID/OpenSID/issues/6074) Perbaikan terkait refaktor api tracksid yang tidak respon.
3. [#1682](https://github.com/OpenSID/premium/issues/1682) Perbaikan validasi form unggah gambar pada modul RFM TinyMCE.
4. [#6073](https://github.com/OpenSID/OpenSID/issues/6073) Perbaikan validasi form unggah gambar pada dokumen penduduk.
5. [#1724](https://github.com/OpenSID/premium/issues/1724) Perbaikan cek extension gambar & error pada saat restore database.
6. [#1692](https://github.com/OpenSID/premium/issues/1692) Perbaikan validasi form unggah file gambar di produk lapak.
7. [#6080](https://github.com/OpenSID/OpenSID/issues/6080) Perbaikan tulisan yang tidak rapi.
8. [#6082](https://github.com/OpenSID/OpenSID/issues/6082) Sesuaikan opsi dan tombol pada buku peraturan di desa.
9. [#6106](https://github.com/OpenSID/OpenSID/issues/6106) Sesuaikan hasil cetak buku aparat pemerintah desa.
10. [#6087](https://github.com/OpenSID/OpenSID/issues/6087) Perbaikan disable menu yang tidak berfungsi.
11. [#6093](https://github.com/OpenSID/OpenSID/issues/6093) Perbaikan tidak dapat mengembalikan status dasar pada log penduduk.
12. [#6104](https://github.com/OpenSID/OpenSID/issues/6104) Sesuaikan jumlah karakter maksimal 200 karakter pada judul artikel.
13. [#1613](https://github.com/OpenSID/premium/issues/1613) Sesuaikan button batal pada tambah/ubah data dusun/rt/rw.
14. [#6094](https://github.com/OpenSID/OpenSID/issues/6094) Perbaikan halaman berlangganan selalu reload.
15. [#6078](https://github.com/OpenSID/OpenSID/issues/6078) Perbaikan penjumlahan pada kolom JLH ART dan kolom jumlah keluarga.
16. [#1693](https://github.com/OpenSID/premium/issues/1693) Perbaikan validasi unggah file gambar di pembangunan.
17. [#6118](https://github.com/OpenSID/OpenSID/issues/6118) Perbaikan tombol kembalikan status terpilih pada form log penduduk tidak berfungsi.
18. [#6119](https://github.com/OpenSID/OpenSID/issues/6119) Perbaikan pilihan cetak buku administrasi desa.
19. [#6111](https://github.com/OpenSID/OpenSID/issues/6111) Perbaikan halaman layanan mandiri selalu reload di browser firefox.
20. [#6115](https://github.com/OpenSID/OpenSID/issues/6115) Perbaikan gawai anjungan terkunci otomatis dalam rentang waktu yg tidak menentu.
21. [#6120](https://github.com/OpenSID/OpenSID/issues/6120) Perbaikan update profile ketika id telegram kosong.
22. [#6102](https://github.com/OpenSID/OpenSID/issues/6102) Perbaikan jarak samping bagian artikel pada tema natra.
23. [#1756](https://github.com/OpenSID/premium/issues/1756) Perbaikan perbarui dan hapus data area tidak menghapus file unggahan.
24. [#6103](https://github.com/OpenSID/OpenSID/issues/6103) Perbaikan tema esensi artikel home posisi tidak pada tempatnya.
25. [#1816](https://github.com/OpenSID/premium/issues/1816) Perbaikan perbarui dan hapus data garis tidak menghapus file unggahan.
26. [#1834](https://github.com/OpenSID/premium/issues/1834) Perbaikan mengilangkan file yang telah di hapus dari folder desa untuk inputan lokasi.
27. [#1849](https://github.com/OpenSID/premium/issues/1849) Perbaikan gagal migrasi pada rev04.
28. [#1850](https://github.com/OpenSID/premium/issues/1850) Perbaikan jalankan migrasi Loading terus karena gagal komunikasi dengan api layanan.

#### Perubahan Teknis

1. Menambahkan accept file pada inputan file sesuai kebutuhan.
2. Hapus file yang tidak di gunakan.
3. RFM url_upload false.
4. [#1731](https://github.com/OpenSID/premium/issues/1731) Menambahkan timestamp unggah file di form pengaturan garis.
5. [#1732](https://github.com/OpenSID/premium/issues/1732) Menambahkan timestamp unggah file di form pengaturan area.
6. [#1754](https://github.com/OpenSID/premium/issues/1754) Menambahkan timestamp pada upload photo di menu lapak.
7. [#6108](https://github.com/OpenSID/OpenSID/issues/6108) Tampilkan keterangan singkat fungsi tombol perbaiki.
8. [#1737](https://github.com/OpenSID/premium/issues/1737) Penyesuaian proses restore folder desa .zip terkait keamaan.
9. [#1741](https://github.com/OpenSID/premium/issues/1741) Penyesuaian proses import pada menu suplemen.
10. [#1757](https://github.com/OpenSID/premium/issues/1757) Defined file folder app dan tambahkan index.php
11. [#1761](https://github.com/OpenSID/premium/issues/1761) Jangan sertakan file dan folder pada rilis.
12. [#1763](https://github.com/OpenSID/premium/issues/1763) Penyesuaian htaccess untuk batasi akses file.
13. [#1779](https://github.com/OpenSID/premium/issues/1779) Audit Keamanan - Modul Identitas Desa.
14. [#1786](https://github.com/OpenSID/premium/issues/1786) Audit Keamaan - merubah url untuk melihat atau mengunduh pada gambar pengaduan.
15. [#1781](https://github.com/OpenSID/premium/issues/1781) Audit Keamanan - modul pengurus.
16. [#1782](https://github.com/OpenSID/premium/issues/1783) Audit Keamanan - form tambah penduduk.
17. [#1788](https://github.com/OpenSID/premium/issues/1788) Audit Keamanan - enkrip link storage admin layanan mandiri.
18. [#1738](https://github.com/OpenSID/premium/issues/1738) Audit Keamanan - penyesuaian proses_sinkronkan() upload file zip dengan penyimpanan sementara.
19. [#1740](https://github.com/OpenSID/premium/issues/1740) Audit Keamanan - proses import program bantuan.
20. [#1770](https://github.com/OpenSID/premium/issues/1770) Audit Keamanan - form produk lapak.
21. [#1819](https://github.com/OpenSID/premium/issues/1819) Audit Keamanan - sembunyikan javascript.
22. [#1733](https://github.com/OpenSID/premium/issues/1733) Audit keamanan - form pengaturan latar belakang.
23. [#1771](https://github.com/OpenSID/premium/issues/1771) Audit keamanan - form pembangunan.
24. [#1765](https://github.com/OpenSID/premium/issues/1765) Audit keamanan - form galeri.
25. [#1808](https://github.com/OpenSID/premium/issues/1808) Audit keamanan - form artikel.
