Rilis versi 2604.0.1 ini berisi [untuk diisi]  dan perbaikan lainnya yang diminta oleh komunitas SID.

### BUG

1. [#10974](https://github.com/OpenSID/OpenSID/issues/10974) Perbaikan tanggal cetak yang dipilih tidak digunakan pada hasil cetak di Buku Administrasi Umum (selalu menampilkan tanggal hari ini).
2. [#10977](https://github.com/OpenSID/OpenSID/issues/10977) Perbaikan informasi masa berlaku OTP tidak sesuai antara aplikasi dan email.
3. [#10976](https://github.com/OpenSID/OpenSID/issues/10976) Perbaikan isian data perkawinan ketika merubah status menjadi cerai hidup dan mati, isian data pelengkapnya tidak secara otomatis terhiden kolomnya.
4. [#6129](https://github.com/OpenSID/premium/issues/6129) Perbaikan tidak ada validasi jenis file (Unrestricted File Upload) pada menu upload foto profil.
5. [#10987](https://github.com/OpenSID/OpenSID/issues/10987) Perbaikan data penduduk tidak tampil benar pada peta wilayah dusun.
6. [#10982](https://github.com/OpenSID/OpenSID/issues/10982) Perbaikan nama file hasil unduh xls pada menu laporan kelompok rentan tidak berisi nama hanya timestamp saja.
7. [#10966](https://github.com/OpenSID/OpenSID/issues/10966) Perbaikan pengaturan tahun apbdes.
8. [#10986](https://github.com/OpenSID/OpenSID/issues/10986) Perbaikan data akta kematian tidak tampil benar pada laporan statistik.
9. [#11018](https://github.com/OpenSID/OpenSID/issues/11018) Perbaikan data rincian lembaga tidak tampil di web.
10. [#11019](https://github.com/OpenSID/OpenSID/issues/11019) Perbaikan banyaknya foto setiap produk di menu lapk tidak bisa di isi angka lebih dari 5.
11. [#11020](https://github.com/OpenSID/OpenSID/issues/11020) Perbaikan peta desa tidak muncul di menu pemetaan.
12. [#11026](https://github.com/OpenSID/OpenSID/issues/11026) Perbaikan tombol enter pada keyboard pada saat isi "HAPUS" pada modal alert konfirmasi hapus tidak berfungsi.
13. [#11028](https://github.com/OpenSID/OpenSID/issues/11028) Perbaikan label tombol "Kembali ke Daftar Kelompok Di Desa" pada form tambah/ubah dokumen kelompok tidak sesuai dengan aksi.
14. [#11015](https://github.com/OpenSID/OpenSID/issues/11015) Perbaikan beberapa bug pada fitur dtsen yang harus disesuaikan.

### KEAMANAN
1. [#6160](https://github.com/OpenSID/premium/issues/6160) Perbaikan SQL injection (blind) pada parameter filter[tahun] di endpoint Bantuan Penduduk.

### TEKNIS
1. [#10908](https://github.com/OpenSID/OpenSID/issues/10908) Tema wira menjadi tema default pada instalasi baru aplikasi OpenSID Premium dan juga penambahan tema Lestari dan Seruit Lite selama berstatus pelanggan premium.