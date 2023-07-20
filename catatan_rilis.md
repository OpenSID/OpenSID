Di rilis ini, versi 2307.0.3 berisi [isi disini] dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada [isi disini] yang terus berkontribusi.

#### Perbaikan BUG

1. [#6567](https://github.com/OpenSID/OpenSID/issues/6567) Perbaikan font bookman old style tidak bisa rata kiri-kanan.
2. [#6813](https://github.com/OpenSID/OpenSID/issues/6813) Perbaikan daftar pamong yang ditampilkan unik untuk pengaturan pengguna.
3. [#6817](https://github.com/OpenSID/OpenSID/issues/6817) Perbaikan menampilkan data anak pada saat input KIA.
4. [#6807](https://github.com/OpenSID/OpenSID/issues/6807) Perbaikan informasi kehadiran perangkat.
5. [#6850](https://github.com/OpenSID/OpenSID/issues/6850) Tidak bisa cetak lampiran yang menggunakan gambar.
6. [#6827](https://github.com/OpenSID/OpenSID/issues/6827) Perbaikan saat menambahkan kode/klasifikasi surat jika tidak tersedia pada daftar.
7. [#6838](https://github.com/OpenSID/OpenSID/issues/6838) Perbaikan ridirect stelah login dengan akses group home.
8. [#6849](https://github.com/OpenSID/OpenSID/issues/6849) Perbaikan template surat tinymce keterangan penghasilan ayah.
9. [#6815](https://github.com/OpenSID/OpenSID/issues/6815) Perbaikan sebutan pemerintah desa agar konsiten.
10. [#6840](https://github.com/OpenSID/OpenSID/issues/6840) Perbaikan pengaturan program DTKS.
11. [#6834](https://github.com/OpenSID/OpenSID/issues/6834) Perbaikan sebutan desa agar konsiten.
12. [#6835](https://github.com/OpenSID/OpenSID/issues/6835) Perbaikan sebutan dusun agar konsiten.
13. [#6833](https://github.com/OpenSID/OpenSID/issues/6833) Perbaikan sebutan kepala desa agar konsiten.
14. [#6862](https://github.com/OpenSID/OpenSID/issues/6862) Perbaikan pengaturan rentang waktu kehadiran keluar.
15. [#6873](https://github.com/OpenSID/OpenSID/issues/6873) Perbaikan pengecekan nama desa dengan karakter `/`, `(` dan `)`.
16. [#6842](https://github.com/OpenSID/OpenSID/issues/6842) Perbaikan ambil data surat dari aplikasi OpenDK jika token belum ada.
17. [#6863](https://github.com/OpenSID/OpenSID/issues/6863) Perbaikan validasi data keterangan pada data penduduk.
18. [#6839](https://github.com/OpenSID/OpenSID/issues/6839) Perbaikan hak akses grup pada modul.
19. [#6856](https://github.com/OpenSID/OpenSID/issues/6856) Perbaikan tidak bisa cetak surat TinyMCE yang menggunakan lampiran F-2.01.
20. [#6851](https://github.com/OpenSID/OpenSID/issues/6851) Perbaikan duplikasi url_id setiap kali cetak surat yang sama.
21. [#6870](https://github.com/OpenSID/OpenSID/issues/6870) Perbaikan input pada surat permohonan kk tidak muncul di lampiran F-1.15.
22. [#6871](https://github.com/OpenSID/OpenSID/issues/6871) Perbaikan input pada surat permohonan perubahan kk tidak muncul di lampiran F-1.16.
23. [#6876](https://github.com/OpenSID/OpenSID/issues/6876) Perbaikan data umur pada log penduduk.
24. [#6878](https://github.com/OpenSID/OpenSID/issues/6878) Perbaikan validasi ubah data modul.
25. [#6877](https://github.com/OpenSID/OpenSID/issues/6877) Perbaikan validasi proses simpan yang menggunakan tombol enter.
26. [#6889](https://github.com/OpenSID/OpenSID/issues/6889) Perbaikan tampilan datatables untuk jumlah kolom yang kurang.
27. [#6881](https://github.com/OpenSID/OpenSID/issues/6881) Perbaikan login dengan username terlalu panjang.
28. [#6888](https://github.com/OpenSID/OpenSID/issues/6888) Perbaikan detail data anggota keluarga yang tidak sesuai.
29. [#6886](https://github.com/OpenSID/OpenSID/issues/6886) Perbaikan tidak melakukan pembaruan urutan pengurus ketika ubah data.
30. [#6883](https://github.com/OpenSID/OpenSID/issues/6883) Perbaikan response gagal dari proses kirim ke OpenDK.
31. [#6890](https://github.com/OpenSID/OpenSID/issues/6890) Perbaikan notifikasi tabel jika data tidak ada.
32. [#6904](https://github.com/OpenSID/OpenSID/issues/6904) Perbaikan pencarian saat tambah/ubah data anggota peserta bantuan.
33. [#6885](https://github.com/OpenSID/OpenSID/issues/6885) Perbaikan hapus data penduduk ketika data sudah dinyatakan lengkap.
34. [#6900](https://github.com/OpenSID/OpenSID/issues/6900) Perbaikan penanda tangan lampiran F-1.15.

#### Perubahan Teknis

1. [#6744](https://github.com/OpenSID/OpenSID/issues/6744) Penambahan informasi query yang dihasilkan oleh eloquent pada develbar.
2. [#1918](https://github.com/OpenSID/premium/issues/1918) Refaktor query pada Analisis_kategori_model.php
3. [#1919](https://github.com/OpenSID/premium/issues/1919) Refaktor query pada Analisis_klasifikasi_model.php
4. [#1916](https://github.com/OpenSID/premium/issues/1916) Refaktor query pada Analisis_periode_model.php
5. [#6846](https://github.com/OpenSID/OpenSID/issues/6846) Penyesuian halaman periksa untuk OpenSID database gabungan.
6. [#2572](https://github.com/OpenSID/premium/issues/2572) Penyesuian sebutan kepala desa pada halaman maintenace.
7. [#6848](https://github.com/OpenSID/OpenSID/issues/6848) Penyesuian pasang baru melalui kode_desa melalui config.
8. [#6880](https://github.com/OpenSID/OpenSID/issues/6880) Penyesuaian form inputan email pada ganti email. 
9. [#2600](https://github.com/OpenSID/premium/issues/2600) Penyesuaian migrasi perbaikan collation agar jalan terus diakhir migrasi.
10. [#6901](https://github.com/OpenSID/OpenSID/issues/6901) Penyesuaian struktur kolom tgl_agenda pada tabel agenda.
11. [#2639](https://github.com/OpenSID/premium/issues/2639) Penyesuaian struktur kolom yang menggunakan default urrent_timestamp() ON UPDATE current_timestamp() namun tidak sesuai kegunaannya.