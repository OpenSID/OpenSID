Di rilis ini, versi 2305.0.0 berisi penambahan fitur tampilan anjungan yang baru dan perbaikan lain yang diminta Komunitas SID.

Terima kasih pada @ruririzal yang terus berkontribusi.

#### Penambahan Fitur

1. [#2203](https://github.com/OpenSID/premium/issues/2203) Penambahan select2 pamong infinite scroll.
2. [#2210](https://github.com/OpenSID/premium/issues/2210) Penambahan select2 penduduk cetak surat Infinite scroll.
3. [#6225](https://github.com/OpenSID/OpenSID/issues/6225) Penambahan surat TinyMCE permohonan cerai.
4. [#6540](https://github.com/OpenSID/OpenSID/issues/6540) Penambahan modul pilihan template sistem atau desa saat buat tempate surat TinyMCE.
5. [#6537](https://github.com/OpenSID/OpenSID/issues/6537) Penambahan pilihan otomatis kode isian sesuai dengan format penulisan pada surat TinyMCE.
6. [#6539](https://github.com/OpenSID/OpenSID/issues/6539) Penambahan untuk membedakan template isian dan header/footer pada surat TinyMCE.
7. [#2169](https://github.com/OpenSID/premium/issues/2169) Penambahan tampilan anjungan yang baru.
8. [#2261](https://github.com/OpenSID/premium/issues/2261) Penambahan tampilan buku tamu yang baru.

#### Perbaikan BUG

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

1. [#2234](https://github.com/OpenSID/premium/issues/2234) Penamaan versi aplikasi.
2. [#2236](https://github.com/OpenSID/premium/issues/2236) Penyesuian instalasi awal database gabungan pada file general_helper.
3. [#2249](https://github.com/OpenSID/premium/issues/2249) Penyesuaian migrasi agar bisa digunakan pada OpenSID Database Gabungan.
4. [#6571](https://github.com/OpenSID/OpenSID/issues/6571) Load assets sweetalert2 secara global.
