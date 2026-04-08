Rilis versi 2604.0.0 ini berisi [untuk diisi]  dan perbaikan lainnya yang diminta oleh komunitas SID.

### BUG

1. [#10974](https://github.com/OpenSID/OpenSID/issues/10974) Perbaikan tanggal cetak yang dipilih tidak digunakan pada hasil cetak di Buku Administrasi Umum (selalu menampilkan tanggal hari ini).
2. [#10977](https://github.com/OpenSID/OpenSID/issues/10977) Perbaikan informasi masa berlaku OTP tidak sesuai antara aplikasi dan email.
3. [#10976](https://github.com/OpenSID/OpenSID/issues/10976) Perbaikan isian data perkawinan ketika merubah status menjadi cerai hidup dan mati, isian data pelengkapnya tidak secara otomatis terhiden kolomnya.
4. [#6129](https://github.com/OpenSID/premium/issues/6129) Perbaikan tidak ada validasi jenis file (Unrestricted File Upload) pada menu upload foto profil.
5. [#10987](https://github.com/OpenSID/OpenSID/issues/10987) Perbaikan data penduduk tidak tampil benar pada peta wilayah dusun.
6. [#10982](https://github.com/OpenSID/OpenSID/issues/10982) Perbaikan nama file hasil unduh xls pada menu laporan kelompok rentan tidak berisi nama hanya timestamp saja.
7. [#10966](https://github.com/OpenSID/OpenSID/issues/10966) Perbaikan pengaturan tahun apbdes.

### KEAMANAN
1. [#6160](https://github.com/OpenSID/premium/issues/6160) Perbaikan SQL injection (blind) pada parameter filter[tahun] di endpoint Bantuan Penduduk.
