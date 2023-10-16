Di rilis ini, versi 2310.1.0 berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada @yonathanrizky yang terus berkontribusi.

#### Penambahan Fitur

1. [#6035](https://github.com/OpenSID/OpenSID/issues/6035) Penambahan pintasan ubah data penduduk pada modul rumah tangga.
2. [#5899](https://github.com/OpenSID/OpenSID/issues/5899) Penambahan fitur hapus lampiran artikel.
3. [#6076](https://github.com/OpenSID/OpenSID/issues/6076) Penyesuaian qrcode saat kondisi TTE aktif.
4. [#6406](https://github.com/OpenSID/OpenSID/issues/6406) Penambahan filter aktif dan tidak aktif di menu pengguna.

#### Perbaikan BUG

1. [#6391](https://github.com/OpenSID/OpenSID/issues/6391) Perbaikan menampilkan dan menyembunyikan password pada saat ganti profil.
2. [#6405](https://github.com/OpenSID/OpenSID/issues/6405) Perbaikan validasi token versi dan tanggal berakhir sama.
3. [#6344](https://github.com/OpenSID/OpenSID/issues/6344) Perbaikan validasi karakter pada surat masuk.
4. [#6407](https://github.com/OpenSID/OpenSID/issues/6407) Perbaikan tampilan berantakan pada saat inputan error menggunakan elemen input-grup.
5. [#6417](https://github.com/OpenSID/OpenSID/issues/6417) Perbaikan modul stunting tidak dapat menginput tinggi badan jika menggunakan tanda koma.
6. [#6422](https://github.com/OpenSID/OpenSID/issues/6422) Perbaikan notifikasi error dengan element input-group-addon.
7. [#6424](https://github.com/OpenSID/OpenSID/issues/6424) Perbaikan gagal migrasi pada jabatan sekdes.
8. [#6435](https://github.com/OpenSID/OpenSID/issues/6435) Perbaikan tampilan pilihan jenis presentasi pembagunan pada dokumentasi pembagunan tidak sesuai.
9. [#6438](https://github.com/OpenSID/OpenSID/issues/6438) Penyesuaian informasi harus aktifkan extensi exif di informasi kebutuhan sistem.
10. [#6432](https://github.com/OpenSID/OpenSID/issues/6432) Perbaikan nomor surat tidak kembali ke nomor 1 ketika pergantian tahun.
11. [#6447](https://github.com/OpenSID/OpenSID/issues/6447) Perbaikan maxlength input dengan maxlength pada table pangkat pamong.
12. [#6433](https://github.com/OpenSID/OpenSID/issues/6433) Perbaikan backup database berisi baris yg menyebabkan restore error.
13. [#6437](https://github.com/OpenSID/OpenSID/issues/6437) Perbaikan tambah data penduduk lahir dari modul keluarga.
14. [#6434](https://github.com/OpenSID/OpenSID/issues/6434) Perbaikan migrasi otomatis yang tidak berjalan sempurna.
15. [#6448](https://github.com/OpenSID/OpenSID/issues/6448) Perbaikan lampiran dokumen kelengkapan tidak muncul pada halaman verifikasi surat.
16. [#6341](https://github.com/OpenSID/OpenSID/issues/6341) Perbaikan font bookman old style yang tidak bisa cetak Tebal.
1. [#6536](https://github.com/OpenSID/OpenSID/issues/6536) Perbaikan buku tamu yang mengakibatkan gagal migrasi rilis v23.04.
2. [#6419](https://github.com/OpenSID/OpenSID/issues/6419) Perbaikan tidak bisa simpan identitas desa jika kolom operator belum tersedia.
3. [#6550](https://github.com/OpenSID/OpenSID/issues/6550) Perbaikan gagal import data penduduk menggunakan contoh data yang sudah tidak valid formatnya.
4. [#6545](https://github.com/OpenSID/OpenSID/issues/6545) Perbaikan karakter yang muncul pada notifikasi persetujuan.
5. [#6553](https://github.com/OpenSID/OpenSID/issues/6553) Perbaikan jika tidak ada versi terbaru dan tidak ada cache ijinkan null.
6. [#6557](https://github.com/OpenSID/OpenSID/issues/6557) Perbaikan gagal update identitas desa karena field kades yang diminta isi terdisable.
7. [#6528](https://github.com/OpenSID/OpenSID/issues/6528) Perbaikan query kependudukan pada mysql versi 8.
8. [#6554](https://github.com/OpenSID/OpenSID/issues/6554) Perbaikan gagal setting telegram maupun upload gambar profil.
9. [#6565](https://github.com/OpenSID/OpenSID/issues/6565) Perbaikan nama daerah kecamatan di surat keterangan nikah N1 sd N7.
10. [#6569](https://github.com/OpenSID/OpenSID/issues/6569) Perbaikan judul pratinjau surat tidak sesuai.
11. [#6570](https://github.com/OpenSID/OpenSID/issues/6570) Perbaikan tombol perbaiki arsip layanan yang membuat duplikasi tombol ubah.
12. [#6574](https://github.com/OpenSID/OpenSID/issues/6574) Perbaikan gagal buat pdf sementara saat tinjau pdf.

#### Perubahan Teknis

1. [#1889](https://github.com/OpenSID/premium/issues/1889) Penyesuaian created_at dan updated_at pada modul DTKS.
2. [#6383](https://github.com/OpenSID/OpenSID/issues/6383) Mengatasi HTML Injection pada form pencarian tema natra.
3. [#1976](https://github.com/OpenSID/premium/issues/1976) Sesuaikan modul jabatan agar bisa digunakan di OpenKAB.
4. [#6403](https://github.com/OpenSID/OpenSID/issues/6403) Penyesuaian penandatangan dokumen kerja sama dengan pengurus baru.
5. [#6421](https://github.com/OpenSID/OpenSID/issues/6421) Penambahan keterangan pada informasi cetak faktur.
6. [#5854](https://github.com/OpenSID/OpenSID/issues/5854) Ubah slug modul tanpa menggunakan id.
1. [#2234](https://github.com/OpenSID/premium/issues/2234) Penamaan versi aplikasi.
2. [#2236](https://github.com/OpenSID/premium/issues/2236) Penyesuian instalasi awal database gabungan pada file general_helper.
3. [#2249](https://github.com/OpenSID/premium/issues/2249) Penyesuaian migrasi agar bisa digunakan pada OpenSID Database Gabungan.
4. [#6571](https://github.com/OpenSID/OpenSID/issues/6571) Load assets sweetalert2 secara global.
