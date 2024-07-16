Di rilis ini, versi 2407.0.0 berisi penambahan fungsi pindah keluarga secara kolektif atar wilayah dusun/rw/rt dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada @ariandi dan @arifpriadi telah ikut berkontribusi.

#### FITUR
1. [#4333](https://github.com/OpenSID/OpenSID/issues/4333) Penambahan data rumah tanggal pada modul pementaan.
2. [#5949](https://github.com/OpenSID/OpenSID/issues/5949) Penambahan status hari libur.
3. [#3604](https://github.com/OpenSID/OpenSID/issues/3604) Penambahan fungsi pindah keluarga secara kolektif atar wilayah dusun/rw/rt.
4. [#5141](https://github.com/OpenSID/OpenSID/issues/5141) Penambahan pengaturan jumlah slider yang ditampilkan pada halaman web.
5. [#1772](https://github.com/OpenSID/OpenSID/issues/1772) Penambahan jadwal pemilihan pada modul dpt.
6. [#4630](https://github.com/OpenSID/OpenSID/issues/4630) Penambahan jenis link/embed file pada modul galeri.
7. [#5223](https://github.com/OpenSID/OpenSID/issues/5223) Penambahan jenis ekspor data penduduk berupa data isian yang dikonversi jadi huruf.
8. [#3315](https://github.com/OpenSID/OpenSID/issues/3315) Penambahan fitur backup / restore OpenSID Database Gabungan.
9. [#2599](https://github.com/OpenSID/OpenSID/issues/2599) Penambahan pengaturan tagline / motto desa.


#### BUG

1. [#7474](https://github.com/OpenSID/OpenSID/issues/7474) Perbaikan detail data pada url statistik buku nikah.
2. [#7462](https://github.com/OpenSID/OpenSID/issues/7462) Perbaikan ekspor klasifikasi surat.
3. [#7493](https://github.com/OpenSID/OpenSID/issues/7493) Perbaikan ejaan.
4. [#7494](https://github.com/OpenSID/OpenSID/issues/7494) Perbaikan notifikasi buat token sinkronisasi OpenDK.
5. [#7454](https://github.com/OpenSID/OpenSID/issues/7454) Perbaikan halaman mode offline dan pindahkan ke masing-masing tema.
6. [#7465](https://github.com/OpenSID/OpenSID/issues/7465) Perbaikan akses awal modul arsip layanan.
7. [#7499](https://github.com/OpenSID/OpenSID/issues/7499) Perbaikan notifikasi reset pin layanan mandiri dan bisa disesuaikan oleh pengguna.
8. [#7502](https://github.com/OpenSID/OpenSID/issues/7502) Perbaikan pecah keluarga.
9. [#7491](https://github.com/OpenSID/OpenSID/issues/7491) Perbaikan pratinjau pdf saat melalukan passphrase.
10. [#7500](https://github.com/OpenSID/OpenSID/issues/7500) Perbaikan notifikasi gagal impor peta tipe gpx/kml dan shp.
11. [#7487](https://github.com/OpenSID/OpenSID/issues/7487) Perbaikan saring data stunting > hasil scorecard konvergensi berdasarkan posyandu.
12. [#7514](https://github.com/OpenSID/OpenSID/issues/7514) Perbaikan kode isian jika hanya ada penduduk luar saja.
13. [#6136](https://github.com/OpenSID/OpenSID/issues/6136) Perbaikan notifikasi hapus dusun/rw/rt yang sudah 0 di modul Wilayah.
14. [#7526](https://github.com/OpenSID/OpenSID/issues/7526) Perbaikan menu suplemen yang sudah terhapus.


#### TEKNIS

1. [#3197](https://github.com/OpenSID/premium/issues/3197) Penyesuaian source menggunakan rector.
2. [#7498](https://github.com/OpenSID/OpenSID/issues/7498) Penyesuaian beberapa fungsi agar jalan normal di php 8.x.
3. [#7439](https://github.com/OpenSID/OpenSID/issues/7439) Penyesuaian modul penduduk > dokumen menggunakan ORM dan Blade.
4. [#7442](https://github.com/OpenSID/OpenSID/issues/7442) Penyesuaian modul pengaturan peta > tipe garis menggunakan ORM dan Blade.
5. [#7490](https://github.com/OpenSID/OpenSID/issues/7490) Penyesuaian modul pengaturan > pengguna > grup menggunakan ORM dan Blade.
6. [#7489](https://github.com/OpenSID/OpenSID/issues/7489) Penyesuaian modul pendapat menggunakan ORM dan Blade.
7. [#6722](https://github.com/OpenSID/OpenSID/issues/6722) Penyesuaian modul wilayah administratif menggunakan ORM dan Blade.
8. [#6725](https://github.com/OpenSID/OpenSID/issues/6725) Penyesuaian modul suplemen menggunakan ORM dan Blade.
9. [#7508](https://github.com/OpenSID/OpenSID/issues/7508) Penyesuaian modul pengunjung web menggunakan ORM dan Blade.
10. [#7423](https://github.com/OpenSID/OpenSID/issues/7423) Penyesuaian modul widget web menggunakan ORM dan Blade.
11. [#7512](https://github.com/OpenSID/OpenSID/issues/7512) Penyesuaian modul pengaturan peta > tipe lokasi menggunakan ORM dan Blade.
12. [#7509](https://github.com/OpenSID/OpenSID/issues/7509) Penyesuaian pengecekan hak akses.
13. [#7512](https://github.com/OpenSID/OpenSID/issues/7512) Penyesuaian modul pengaturan peta > simbol lokasi menggunakan ORM dan Blade.
14. [#7511](https://github.com/OpenSID/OpenSID/issues/7511) Penyesuaian modul pengaturan peta > lokasi menggunakan ORM dan Blade.
15. [#7528](https://github.com/OpenSID/OpenSID/issues/7528) Penyesuaian pengaturan mapbox_key dan google recaptcha ambil dari config jika belum tersedia.
16. [#7529](https://github.com/OpenSID/OpenSID/issues/7529) Penyesuaian modul pengaturan modul menggunakan ORM dan Blade.
17. [#7518](https://github.com/OpenSID/OpenSID/issues/7518) Penyesuaian modul arsip layanan menggunakan ORM dan Blade.
18. [#7507](https://github.com/OpenSID/OpenSID/issues/7507) Penyesuaian modul daftar pemilih tetap menggunakan ORM dan Blade.
19. [#3084](https://github.com/OpenSID/premium/issues/3084) Penyesuaian ulang migrasi.
20. [#237](https://github.com/OpenSID/opensid-api/issues/237) Penyesuian teknis unit testing sisipkan id arsip saat cetak pdf.


#### KEAMANAN

1. [#3200](https://github.com/OpenSID/premium/issues/3200) Peningkatan keamanan pada form unggah file pada modul menu anjungan.
2. [#3243](https://github.com/OpenSID/premium/issues/3243) Peningkatan keamanan password database.


#### INFO PENTING
- Pada rilis ini, penggunaan surat jenis RTF tidak didukung dan tidak dikembangkan lagi. Silahkan beralih dan gunakan surat jenis TinyMCE yang sudah dikembangkan.