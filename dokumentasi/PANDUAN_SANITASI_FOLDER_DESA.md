# Panduan Fitur Bersih Folder Desa

Fitur **Bersih Folder Desa** memindai folder `desa/` dan menampilkan kandidat file yang tidak dibutuhkan atau tidak dirujuk oleh database — dikelompokkan per folder dengan deskripsi singkat — sehingga admin dapat meninjau dan menghapus secara selektif sebelum mendistribusikan paket demo atau membersihkan ruang disk.

> **Peringatan:** File yang dihapus melalui fitur ini **tidak dapat dipulihkan**. Selalu lakukan backup database dan folder desa sebelum melanjutkan.

---

## Cara Mengakses

### Melalui Web Admin

Masuk sebagai **super admin**, lalu buka menu **Database** → tab **Bersih Folder**.

URL langsung: `https://<domain-desa>/database/bersih-folder`

> Tab ini hanya muncul untuk super admin.

### Melalui Artisan (Command Line)

```bash
# Dry-run: tampilkan daftar kandidat tanpa menghapus
php artisan opensid:bersih-folder

# Hapus semua kandidat (dengan konfirmasi interaktif)
php artisan opensid:bersih-folder --hapus

# Batasi ke satu kategori saja (gunakan key rule dari tabel di bawah)
php artisan opensid:bersih-folder --rule=orphan_arsip --hapus
```

---

## Alur Kerja Melalui Web

1. **Scan** — klik tab Bersih Folder; sistem memindai seluruh folder `desa/` dan menampilkan kelompok kandidat.
2. **Tinjau** — setiap kelompok ditampilkan sebagai kartu dengan nama folder, deskripsi, jumlah file, dan ukuran total. Daftar nama file ditampilkan untuk semua kelompok — termasuk kelompok bulk seperti Prodeskel dan direktori stray. Kelompok dengan lebih dari 50 file dilipat secara default dan dapat dibuka dengan mengklik tautan "Tampilkan N file".
3. **Pilih** — semua kandidat tercentang secara default. Kelompok bulk diseleksi dengan satu checkbox "Hapus semua"; kelompok per-file memiliki checkbox individual per file. Hilangkan centang pada file atau kelompok yang ingin dipertahankan.
4. **Hapus** — klik tombol "Hapus X file terpilih". Konfirmasi ulang di dialog; proses tidak dapat dibatalkan.
5. **Hasil** — halaman menampilkan statistik: berapa file dihapus, berapa disk dibebaskan, dan apakah ada kesalahan.
6. **Scan ulang** — klik "Scan ulang" untuk memverifikasi folder sudah bersih.

---

## Kategori yang Dipindai

### 1. Konten Tidak Dikenal di Folder `desa/` (`stray_content`)

**Strategi:** whitelist — setiap direktori atau file di root `desa/` dan di dalam `desa/upload/` yang tidak ada di daftar yang dikenal OpenSID akan ditandai.

**Direktori yang dikenal di `desa/` root:**
`logo/`, `arsip/`, `cache/`, `config/`, `template-surat/`, `themes/`, `upload/`, `anjungan/`, `widgets/`, `pengaturan/`, `prodeskel/`, `backup/`, `modules/`, `mandiri/`, `mandiri_video/`

**File yang dikenal di `desa/` root:**
`app_key`, `.env`, `offline_mode.php`, `index.html`, `.htaccess`, `baca-ini.txt`

**Subdirektori yang dikenal di `desa/upload/`:**
`user_pict/`, `kelompok/`, `lembaga/`, `galeri/`, `artikel/`, `buku_tamu/`, `gis/`, `dokumen/`, `pengesahan/`, `widgets/`, `keuangan/`, `media/`, `produk/`, `pengaduan/`, `vaksin/`, `pendaftaran/`, `dtks/`, `dtsen/`, `fonts/`, `sosmed/`, `pengajuan_izin/`, `catatan_harian/`, `themes/`, `sinkronisasi/`, `thumbs/`

Setiap direktori stray menghasilkan kelompok tersendiri dengan seluruh isinya; direktori dihapus sebagai satu kesatuan (bukan per-file). Contoh: `desa/OSX/` (55MB, 14.077 file artefak macOS `._*` dan `.DS_Store`).

---

### 2. Arsip Surat Tidak Dirujuk (`orphan_arsip`)

File di `desa/arsip/` yang tidak lagi dirujuk oleh baris mana pun di tabel `log_surat`, `log_surat_dinas`, `surat_masuk`, atau `surat_keluar`.

**Penting:** Proses **Acak Data** secara otomatis menjalankan pembersihan ini sebagai bagian dari pipeline 3-fase — lihat [PANDUAN_ACAK_DATA.md](PANDUAN_ACAK_DATA.md). Jika Anda baru saja menjalankan Acak Data, `desa/arsip/` sudah berisi placeholder PDF yang bersih dan tidak perlu dibersihkan lagi dari sini.

Tanpa Acak Data, arsip surat lama (RTF/PDF dengan NIK asli di nama file) terakumulasi di folder ini. Gunakan fitur Bersih Folder untuk menghapusnya secara terpadu.

---

### 3. Thumbnail Surat Tidak Dirujuk (`orphan_thumbs`)

File PNG di `desa/upload/thumbs/` yang stem nama filenya tidak cocok dengan `nama_surat` di `log_surat` atau `log_surat_dinas`. Thumbnail lama berformat `surat_ket_*`, `surat_domisili_*`, `kk_*`, dll. yang tertinggal setelah surat dihapus.

---

### 4. Output Prodeskel (`prodeskel_output`)

Semua file di `desa/prodeskel/` adalah file RTF yang digenerate oleh aplikasi saat ekspor Prodeskel. Tidak ada di antaranya yang merupakan template atau aset yang dibutuhkan untuk menjalankan OpenSID.

---

### 5. Cache Langganan (`desa_cache`)

File `desa/cache/status_langganan` berisi objek PHP serialized dengan JWT token langganan, domain desa asli, kode desa, dan tanggal berlangganan — spesifik untuk instalasi asal. Harus dihapus sebelum mendistribusikan paket demo ke desa lain.

---

### 6. Dokumen Upload Tidak Dirujuk (`orphan_dokumen`)

File di `desa/upload/dokumen/` yang tidak ada entri di tabel `dokumen.satuan`. File ini **aktif ditampilkan** dalam portal dokumen publik dan fitur download. Hanya file tanpa baris DB yang menjadi kandidat; dokumen aktif tidak tersentuh.

---

### 7. Foto Pendaftaran Mandiri Tidak Dirujuk (`orphan_pendaftaran`)

File di `desa/upload/pendaftaran/` yang tidak dirujuk oleh kolom `scan_ktp`, `scan_kk`, atau `foto_selfie` di tabel `tweb_penduduk_mandiri`. Foto ini ditampilkan di halaman verifikasi permohonan pendaftaran. Hanya file tanpa baris DB yang menjadi kandidat.

---

### 8. Foto Buku Tamu Tidak Dirujuk (`orphan_buku_tamu`)

File di `desa/upload/buku_tamu/` yang tidak ada di kolom `buku_tamu.foto`. Foto ini ditampilkan di modul buku tamu admin. Hanya file tanpa baris DB yang menjadi kandidat.

---

### 9. Dokumen Vaksinasi Tidak Dirujuk (`orphan_vaksin`)

File di `desa/upload/vaksin/` yang tidak dirujuk oleh kolom `dokumen_vaksin_1`, `dokumen_vaksin_2`, `dokumen_vaksin_3`, atau `surat_dokter` di tabel `covid19_vaksin`. Ada route download aktif untuk file ini. Hanya file tanpa baris DB yang menjadi kandidat.

---

### 10. Foto Pengaduan Tidak Dirujuk (`orphan_pengaduan`)

File di `desa/upload/pengaduan/` yang tidak ada di kolom `pengaduan.foto`. Foto ini ditampilkan di halaman detail pengaduan warga. Model Pengaduan sudah menghapus file saat baris dihapus, sehingga tingkat orphan rendah — tetapi tidak nol jika proses penghapusan sebelumnya interrupted.

---

### 11. File Pengesahan Tidak Dirujuk (`orphan_pengesahan`)

File di `desa/upload/pengesahan/`. Tidak ada tabel database yang saat ini diketahui merujuk folder ini; semua file menjadi kandidat. Jika di kemudian hari sebuah tabel ditemukan, daftarkan di `OrphanPengesahanRule::REFERENCE_COLUMNS`.

---

## File yang Tidak Pernah Ditampilkan sebagai Kandidat

Sejumlah file dilindungi secara keras di lapisan dasar (`AbstractCleanupRule::ALWAYS_PROTECTED`) dan **tidak akan pernah muncul** sebagai kandidat hapus dari rule mana pun:

`index.html`, `index.php`, `.htaccess`, `.htpasswd`, `baca-ini.txt`, `web.config`, `.gitkeep`, `.gitignore`

Tambahan khusus `StrayContentRule`: `app_key`, `.env`, `offline_mode.php`.

---

## Apa yang Tidak Dipindai

Folder-folder berikut **tidak disentuh** oleh fitur ini karena aktif digunakan oleh aplikasi dan tidak akan menghasilkan kandidat kecuali sudah ada rule khusus untuk kategori orphan-nya:

| Folder | Alasan Tidak Dipindai |
|--------|----------------------|
| `desa/config/` | File konfigurasi wajib untuk bootstrap |
| `desa/template-surat/` | Template surat ODS/ODT — diperlukan untuk generate surat |
| `desa/themes/` | Tema frontend — diperlukan untuk tampilan website |
| `desa/pengaturan/`, `desa/anjungan/`, `desa/widgets/` | Aset UI per-instalasi |
| `desa/upload/user_pict/` | Foto warga dan pamong — diproses oleh Acak Data |
| `desa/upload/artikel/`, `desa/upload/galeri/` | Konten publik — diproses oleh Acak Data |
| `desa/upload/gis/` | Layer peta GIS |
| `desa/upload/media/` | Media konten website |
| `desa/upload/fonts/` | Font untuk generate PDF |

---

## Hubungan dengan Fitur Acak Data

Fitur Acak Data menjalankan **ruleset yang identik** dengan Bersih Folder sebagai fase 2 pipeline-nya — sehingga setelah Acak Data selesai, folder `desa/` sudah bersih dari semua kategori yang dipindai Bersih Folder. Bersih Folder berguna untuk tinjauan selektif manual atau pembersihan tanpa menjalankan acak penuh.

| | Acak Data | Bersih Folder |
|---|---|---|
| Arsip surat lama (orphan) | **Dihapus otomatis** (fase 2) | Tersedia sebagai `orphan_arsip` |
| Thumbnail surat lama | **Dihapus otomatis** (fase 2) | Tersedia sebagai `orphan_thumbs` |
| Cache langganan | **Dihapus otomatis** (fase 2) | Tersedia sebagai `desa_cache` |
| Output Prodeskel | **Dihapus otomatis** (fase 2) | Tersedia sebagai `prodeskel_output` |
| Konten stray (`desa/data_lama/` dll.) | **Dihapus otomatis** (fase 2) | Ditampilkan per direktori |
| Upload PII orphan (dokumen, vaksin, dll.) | **Dihapus otomatis** (fase 2) | Ditampilkan sebagai orphan per folder |
| Arsip placeholder baru | **Dibuat otomatis** (fase 3) | — |
| Foto warga / pamong | Diproses (null/avatar/blur) | Tidak dipindai |

**Urutan yang direkomendasikan untuk membuat paket demo:**

1. Restore database + folder desa dari sumber yang sama
2. Jalankan **Acak Data** — sanitasi database, bersih folder lengkap, dan buat placeholder bersih dalam satu proses
3. Export folder `desa/` yang sudah bersih

Jalankan **Bersih Folder** secara terpisah jika ingin meninjau kandidat file sebelum dihapus, atau membersihkan folder tanpa menjalankan acak data.

---

## Keamanan

- **Path traversal** — semua path yang dikirimkan dari form divalidasi menggunakan `realpath()` sebelum `unlink()`. Path yang keluar dari `desa/` ditolak dan dicatat di bagian errors.
- **File dilindungi** — `ALWAYS_PROTECTED` diterapkan di lapisan base class; tidak ada rule individual yang dapat melewatinya.
- **Direktori stray** — dihapus sebagai satu tree menggunakan `File::deleteDirectory()` setelah rule melakukan rescan pada saat delete (bukan hanya pada saat scan awal).
- **Bulk key** — key yang dikirimkan dari form di-match ke hasil rescan rule pada saat delete; file baru yang muncul di antara scan dan delete tidak dihapus kecuali benar-benar ada di hasil scan ulang.
