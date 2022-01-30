Di rilis ini, versi v22.02, menyediakan Lapak Desa dan Penambahan Peta Area Pada Pertanahan. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada Mokhamad Afila yang terus berkontribusi.

Lengkapnya, isi rilis versi v22.02 adalah sebagai berikut:


#### Penambahan Fitur
1. [#4099](https://github.com/OpenSID/OpenSID/issues/4099) Tampilkan lokasi persil dalam peta desa.
2. [#4235](https://github.com/OpenSID/OpenSID/issues/4235) Tambahkan tombol ubah persil di rincian C-Desa.
3. [#3541](https://github.com/OpenSID/OpenSID/issues/3541) Sediakan lapak desa untuk ditampilkan di web.
4. [#4266](https://github.com/OpenSID/OpenSID/issues/4266) Sekarang setelah logout layanan mandiri kembali ke form sesuai login awal. Juga untuk login eKTP, sekarang scan dilakukan sebelum mengisi PIN.
5. [#3464](https://github.com/OpenSID/OpenSID/issues/3464) Sekarang peta wilayah desa dan dusun dapat terdiri dari lebih dari satu bagian terpisah.
6. [#4262](https://github.com/OpenSID/OpenSID/issues/4262) Sekarang bisa kosongkan peta wilayah dan area.
7. [#4223](https://github.com/OpenSID/OpenSID/issues/4223) Tambahkan data suku/etnis pada biodata penduduk.
8. Sekarang Info Desa > Layanan Pelanggan menyediakan status semua pemesanan layanan dari OpenDesa, termasuk faktur pemesanan dan bukti pembayaran.
9. Sediakan pengaturan Layanan Pelanggan terpisah diakses dari header dan pesan peringatan.


#### Perbaikan BUG
1. [#4162](https://github.com/OpenSID/OpenSID/issues/4162) Batasi tidak bisa ubah data Pemetaaan, Buku Administrasi Umum, Keuangan, Siaga Covid-19, Pengaturan, SMS dan Analisis kalau pengguna tidak mempunyai hak ubah.
2. Sekarang keluarga yang kepala keluarganya meninggal, hilang atau pindah dilaporkan di Laporan Bulanan pada bulan sesuai dengan Tanggal Lapor peristiwa yang bersangkutan.
3. Sekarang filter tahun berjalan normal di Buku Inventaris dan Kekayaan Desa.
4. [#4234](https://github.com/OpenSID/OpenSID/issues/4234) Sekarang foto default pengguna yg login ditampilkan kembali.
5. [#3293](https://github.com/OpenSID/OpenSID/issues/3293) Sekarang modal untuk membuat folder baru di Responsive File Manager tampil ditengah pada layar HP dan dapat diisi.
6. Sekarang menambah pemudik Covid-19 tersimpan dengan benar.
7. [#3419](https://github.com/OpenSID/OpenSID/issues/3419) Sekarang tidak error kalau tidak berhasil memperoleh informasi rilis dari Github.
8. [#4247](https://github.com/OpenSID/OpenSID/issues/4247) Perkecil judul Status IDM Desa supaya muat status panjang seperti 'BERKEMBANG'.
9. [#4101](https://github.com/OpenSID/OpenSID/issues/4101) Sekarang penjumlahan luas tanah basah dan kering di laporan Data C-Desa sudah benar.
10. Foto pengguna sekarang diisi foto default pada waktu membuat pengguna baru.
11. [#4267](https://github.com/OpenSID/OpenSID/issues/4267) Sekarang keluarga tidak bisa tersimpan dengan lokasi wilayah kosong.
12. [#4263](https://github.com/OpenSID/OpenSID/issues/4263) Di Buku Induk Penduduk, sekarang anggota keluarga dengan Nomor KK 0 terurut dengan benar.
13. [#4294](https://github.com/OpenSID/OpenSID/issues/4294) Sekarang bisa cari C-Desa menggunakan nama pemilik luar desa.
14. Perbaiki impor Excel data penduduk supaya data keluarga tersimpan.


#### Perubahan Teknis
1. Hapus tabel dan script Provinsi yg tidak digunakan lagi.
2. Gunakan dialog konfirmasi global di aksi keluarga, penduduk dan log penduduk.
3. Hapus script popup modal yang tidak digunakan.
