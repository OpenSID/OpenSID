Rilis versi 2605.1.0 ini berisi penambahan fitur lampiran surat f201 berdasarkan permendagri nomor 6 tahun 2026 dan perbaikan lainnya yang diminta oleh komunitas SID.

### FITUR

1. [#10935](https://github.com/OpenSID/OpenSID/issues/10935) Penambahan fitur desil otomatis pada formulir DTSEN.
2. [#10970](https://github.com/OpenSID/OpenSID/issues/10970) Penambahan fitur laporan kegiatan harian untuk perangkat desa.
3. [#10979](https://github.com/OpenSID/OpenSID/issues/10979) Penambahan fitur isian penduduk luar desa pada cetak surat diberi pilihan hidup/mati.
4. [#11032](https://github.com/OpenSID/OpenSID/issues/11032) Penambahan fitur lampiran surat f201 berdasarkan permendagri nomor 6 tahun 2026.
5. [#11010](https://github.com/OpenSID/OpenSID/issues/11010) Penambahan fitur impor data DTSEN berdasarkan desil SIKNg.
6. [#11054](https://github.com/OpenSID/OpenSID/issues/11054) Penambahan fitur rekapitulasi kehadiran bulanan.
7. [#11235](https://github.com/OpenSID/OpenSID/issues/11235) Penambahan fitur sinkronisasi artikel & kategori dari OpenKab ke OpenSID dengan filter per-wilayah.
8. [#10968](https://github.com/OpenSID/OpenSID/issues/10968) Penambahan fitur pengaturan jarak yang diizinkan melakukan absensi kehadiran menggunakan aplikasi kelolaDesa.
9. [#11165](https://github.com/OpenSID/OpenSID/issues/11165) Penambahan fitur penulisan DTSEN menggunakan huruf kapital.
10. [#11016](https://github.com/OpenSID/OpenSID/issues/11016) Penambahan fitur statistik akta kematian berdasarkan wilayah.
11. [#11056](https://github.com/OpenSID/OpenSID/issues/11056) Penambahan fitur edit surat dinas di arsip layanan.

### BUG

1. [#11232](https://github.com/OpenSID/OpenSID/issues/11232) Perbaikan tooltip tombol icon “Eye” menampilkan teks “Unduh” pada halaman dokumen layanan mandiri.
2. [#11215](https://github.com/OpenSID/OpenSID/issues/11215) Perbaikan filter tidak berfungsi di menu data lembaga desa.
3. [#11239](https://github.com/OpenSID/OpenSID/issues/11239) Perbaikan Login Layanan Mandiri terblokir akibat anjungan_uuid basi di cookie/local storage.
4. [#11233](https://github.com/OpenSID/OpenSID/issues/11233) Perbaikan layanan mandiri masih bisa diakses tanpa login ulang setelah reset PIN.
5. [#11236](https://github.com/OpenSID/OpenSID/issues/11236) Perbaikan gagal memuat anggota keluarga saat ubah status dasar pada data tertentu.
6. [#11247](https://github.com/OpenSID/OpenSID/issues/11247) Perbaikan data rincian penduduk pada semua statistik tidak terfilter berdasarkan tahun yang dipilih.
7. [#11237](https://github.com/OpenSID/OpenSID/issues/11237) Perbaikan upload gambar pada artikel dianggap jenis berkas berbahaya.
8. [#11240](https://github.com/OpenSID/OpenSID/issues/11240) Perbaikan tombol hapus pada halaman pengguna tidak berfungsi.
9. [#11243](https://github.com/OpenSID/OpenSID/issues/11243) Perbaikan ketidaksesuaian jumlah rekapitulasi KK antara Laporan Bulanan dan Buku Administrasi Penduduk.
10. [#11238](https://github.com/OpenSID/OpenSID/issues/11238) Perbaikan URL notifikasi menyimpan localhost saat migrasi dijalankan di local.
11. [#11249](https://github.com/OpenSID/OpenSID/issues/11249) Perbaikan tombol simpan Identitas Desa tidak kembali normal setelah upload gambar gagal.
12. [#11251](https://github.com/OpenSID/OpenSID/issues/11251) Perbaikan sesi perangkat Anjungan hilang setiap pagi — perangkat ter-redirect ke halaman login.
13. [#11253](https://github.com/OpenSID/OpenSID/issues/11253) Perbaikan tombol "ke Permohonan Surat" pada saat cetak surat mengarah ke arsip layanan.

### KEAMANAN

1. [#6323](https://github.com/OpenSID/premium/issues/6323) Perbaikan kerentanan keamanan pada dependencies NPM dan Composer.

### TEKNIS

1. [#11241](https://github.com/OpenSID/OpenSID/issues/11241) buat status covid rss menjadi tidak aktif dan nonaktifkan menu/modulnya karena sudah tidak terpakai.
2. [#11250](https://github.com/OpenSID/OpenSID/issues/11250) Menon-aktifkan PlaywrightController Endpoints Accessible pada mode demo.
3. [#6199](https://github.com/OpenSID/premium/issues/6199) Tambahkan panduan teknis pembuatan modul bawaan dan modul premium.
4. [#11198](https://github.com/OpenSID/premium/issues/11198) Perintah scan tema melalui CLI (php index.php artisan opensid:pindai-tema).