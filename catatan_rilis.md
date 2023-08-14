Di rilis ini, versi 2308.0.1 berisi [isi disini] dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [isi disini] yang terus berkontribusi.

#### Perbaikan Bug
1. [#6882](https://github.com/OpenSID/OpenSID/issues/6882) Perbaikan luas peta tidak tampil mengikuti pengaturan pada modul pemetaan.
2. [#6957](https://github.com/OpenSID/OpenSID/issues/6957) Perbaikan daftar penduduk lepas saat menambah keluarga (kepala keluarga) baru dan anggota keluarga.
3. [#6793](https://github.com/OpenSID/OpenSID/issues/6793) Perbaikan gagal buat surat atau update surat.
4. [#6963](https://github.com/OpenSID/OpenSID/issues/6963) Perbaikan tambah data keluarga dari penduduk lepas.
5. [#6956](https://github.com/OpenSID/OpenSID/issues/6956) Perbaikan tanggal tidak valid pada tgl_cetak_ktp modul penduduk.
6. [#6955](https://github.com/OpenSID/OpenSID/issues/6955) Perbaikan form isian tinymce isian required.
7. [#6965](https://github.com/OpenSID/OpenSID/issues/6965) Perbaikan nama dusun pada pilih ketua dan anggota kelompok/lembaga.
8. [#6966](https://github.com/OpenSID/OpenSID/issues/6966) Perbaikan tautan teks berjalan.
9. [#6967](https://github.com/OpenSID/OpenSID/issues/6967) Perbaikan default data penduduk status lahir.
10. [#6962](https://github.com/OpenSID/OpenSID/issues/6962) Perbaikan pengisian lampiran F-1.01 menggunakan sesuai petunjuk pengisian.
11. [#6997](https://github.com/OpenSID/OpenSID/issues/6997) Perbaikan contoh data awal yang duplikasi dengan isian config_id null saat pemasangan baru.
12. [#6969](https://github.com/OpenSID/OpenSID/issues/6969) Perbaikan hubungan dalam keluarga penduduk jika ditambahkan melalui modul keluarga.
13. [#6999](https://github.com/OpenSID/OpenSID/issues/6999) Perbaikan pengecekan tanggal berakir dan versi aplikasi yang digunakan saat pemasangan awal.
14. [#6987](https://github.com/OpenSID/OpenSID/issues/6987) Perbaikan data keluarga yang masih menampilkan penduduk status selain hidup.
15. [#6979](https://github.com/OpenSID/OpenSID/issues/6979) Perbaikan kode isian orang tua pada surat tinymce.
16. [#6974](https://github.com/OpenSID/OpenSID/issues/6974) Perbaikan simpan data path kosong ([]) pada peta identitas desa.
17. [#7015](https://github.com/OpenSID/OpenSID/issues/7015) Perbaikan title dan popover tambah anggota kelompok/lembaga.
18. [#7014](https://github.com/OpenSID/OpenSID/issues/7014) Perbaikan tambah/ubah teks berjalan yang di tautkan ke artikel.
19. [#6995](https://github.com/OpenSID/OpenSID/issues/6995) Perbaikan halaman laporan aset yang dihapus.
20. [#6939](https://github.com/OpenSID/OpenSID/issues/6939) Perbaikan impor surat tinymce non-warga.
21. [#7012](https://github.com/OpenSID/OpenSID/issues/7012) Perbaikan penandatangan dengan nama dan jabatan yang panjang.
22. [#6968](https://github.com/OpenSID/OpenSID/issues/6968) Perbaikan tanggal pada lampiran F.2-01.

#### Perubahan Teknis

#### Peningkatan Keamanan
1. [#2661](https://github.com/OpenSID/premium/issues/2661) Penyesuaian konfigurasi security headers.
2. [#2677](https://github.com/OpenSID/premium/issues/2677) Pembatasan ubah permission file dan folder melalui RFM.
3. [#2634](https://github.com/OpenSID/premium/issues/2634) Pembatasan karakter pada input get halaman pengaduan dan lapak.
3. [#2681](https://github.com/OpenSID/premium/issues/2681) Pembatasan karakter pada input post halaman admin lapak.
4. [#2684](https://github.com/OpenSID/premium/issues/2684) Pembatasan karakter pada input post halaman admin inventaris tanah.
5. [#2686](https://github.com/OpenSID/premium/issues/2686) Pembatasan karakter pada input post halaman admin pembangunan.
6. [#2685](https://github.com/OpenSID/premium/issues/2685) Pembatasan karakter pada input post halaman admin keuangan manual.
7. [#2682](https://github.com/OpenSID/premium/issues/2682) Pembatasan karakter pada input post halaman kirim permohonan surat layanan mandiri web.
8. [#2687](https://github.com/OpenSID/premium/issues/2687) Pembatasan karakter pada input post halaman informasi publik admin.
9. [#2683](https://github.com/OpenSID/premium/issues/2683) Pembatasan karakter pada input post halaman artikel admin.
10. [#2688](https://github.com/OpenSID/premium/issues/2688) Pembatasan karakter pada input post halaman artikel widget.
11. [#2631](https://github.com/OpenSID/premium/issues/2631) Pembatasan hapus dokumen pada halaman dokumen layanan mandiri web.
12. [#2678](https://github.com/OpenSID/premium/issues/2678) Pembatasan tambah/ubah/hapus berkas/folder pada RFM sesuai hak akses modul yang diberikan.
13. [#2662](https://github.com/OpenSID/premium/issues/2662) Penyesuaian konfigurasi trusted host.
14. [#2663](https://github.com/OpenSID/premium/issues/2663) Pembatasan spesifik php info hanya untuk super admin.
15. [#2746](https://github.com/OpenSID/premium/issues/2746) Perbaikan uggah dokumen pada laporan penduduk.
16. [#2747](https://github.com/OpenSID/premium/issues/2747) Perbaikan uggah dokumen pada laporan apbdes.
17. [#2633](https://github.com/OpenSID/premium/issues/2633) Pembatasan pengiriman pesan pada layanan mandiri web dengan durasi 60 detik.
