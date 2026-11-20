# Panduan Penggunaan Fitur Acak Data

Fitur **Acak Data** mengganti seluruh data identitas warga di database — termasuk NIK, nama, tanggal lahir, nomor KK, telepon, dan e-mail — dengan nilai sintetis yang realistis, sekaligus mengolah foto-foto dan arsip surat di folder `desa/`, dan membersihkan semua file yang tidak diperlukan untuk data demo (cache langganan, output Prodeskel, konten stray, dan upload PII yang tidak lagi dirujuk database). Database dan folder desa yang dihasilkan dapat digunakan untuk keperluan demonstrasi, pengembangan, atau analisis tanpa membocorkan data warga yang sesungguhnya.

Selain itu, pada jalur **web admin** Acak Data menjalankan resolusi otomatis terhadap masalah yang dilaporkan halaman **Periksa Data** (`/periksa`) — duplikat kepala keluarga, log keluarga ganda, klasifikasi surat ganda, artikel yatim, dan lainnya — sehingga database hasil acak lolos seluruh pemeriksaan tanpa memerlukan intervensi manual admin. Lihat bagian [Resolusi Pemeriksaan Database (/periksa)](#resolusi-pemeriksaan-database-periksa).

Setelah proses selesai, sebuah callout **"Data Dianonimkan"** muncul di panel info admin dan sebuah bilah tipis di pojok kanan atas konten menampilkan tanggal anonimasi — penanda visual bahwa instalasi ini berisi data sintetis, bukan data warga sebenarnya. Penanda ini aktif selama setelan `data_diacak` terisi; memulihkan database asli akan menghilangkannya.

Fitur **Bersih Folder Desa** (lihat [PANDUAN_SANITASI_FOLDER_DESA.md](PANDUAN_SANITASI_FOLDER_DESA.md)) menjalankan ruleset pembersihan folder yang sama tetapi memberi kontrol selektif per-file. Daftar file yang Anda pertahankan (deselect) di halaman tersebut **tersimpan** dan otomatis dihormati saat Acak Data berjalan.

> **Peringatan:** Proses ini **tidak dapat dibatalkan**. Data yang telah diacak tidak bisa dikembalikan ke nilai aslinya. Selalu lakukan backup sebelum menjalankan fitur ini.

---

## Penting: Database dan Folder Desa Harus Sinkron

Fitur Acak Data beroperasi pada **dua komponen sekaligus**:

1. **Database** — semua data identitas warga di tabel-tabel MySQL.
2. **Folder `desa/`** — file foto warga, foto pamong, foto artikel, foto galeri, media, dan arsip surat di `desa/upload/` dan `desa/arsip/`.

Kedua komponen ini **harus berasal dari instalasi yang sama** sebelum acak dijalankan. Jika database berasal dari instalasi A dan folder `desa/` masih dari instalasi B — misalnya setelah restore database dari backup tetapi folder desa belum di-restore — acak akan memproses file foto yang tidak berkorespondensi dengan data di database. Hasilnya tidak terdefinisi dan kemungkinan besar tidak berguna.

**Skenario yang paling berisiko:** restore database dari instalasi lain → langsung jalankan acak tanpa merestore folder desa yang sesuai.

Formulir acak web meminta konfirmasi eksplisit bahwa database dan folder desa sudah sinkron sebelum tombol Jalankan dapat digunakan.

---

## Hanya untuk Database Satu Desa (bukan Multi-Desa)

Acak Data adalah alat de-identifikasi untuk **satu desa**. Pada database **multi-desa (multitenant)**, proses ini akan mengubah data seluruh desa sekaligus, sehingga sengaja dinonaktifkan:

- **Web admin** — tab Acak menampilkan pemberitahuan bahwa fitur tidak tersedia untuk database multi-desa, tanpa formulir.
- **Artisan** — perintah berhenti dengan pesan galat dan kode keluar gagal sebelum memproses apa pun.

Deteksi multitenant mengikuti konvensi OpenSID (lebih dari satu baris pada tabel `config`).

---

## Siapa yang dapat menggunakan fitur ini?

Fitur Acak Data hanya dapat diakses oleh **super admin** (grup Administrator). Pengecualian: pada mode **server demo** (`penggunaan_server == 6`) akses juga diizinkan. Pengguna dengan peran lain tidak akan melihat tab Acak dan akan mendapat respons 403 jika mencoba mengakses URL-nya secara langsung.

---

## Cara 1 — Melalui Web Admin

### Langkah 1: Buka halaman Database

Masuk sebagai super admin, lalu buka menu **Database** di navigasi admin. URL-nya adalah:

```
https://<domain-desa>/database/
```

### Langkah 2: Klik tab Acak

Di bagian atas halaman terdapat beberapa tab: **Backup/Restore**, **Migrasi DB**, **Acak**, dan halaman terkait **Bersih Folder Desa**. Klik tab **Acak**.

> Tab Acak hanya berfungsi jika Anda login sebagai super admin (atau pada server demo).

### Langkah 3: Backup database dan folder desa terlebih dahulu

Sebelum melanjutkan, pastikan **keduanya** sudah dibackup:

- **Database** — gunakan tab **Backup/Restore** di halaman yang sama untuk mengunduh backup `.sql` atau `.sql.gz`.
- **Folder `desa/`** — gunakan fitur **Backup/Restore Folder Desa** untuk membuat arsip ZIP folder desa.

Simpan kedua file backup di tempat yang aman di luar server.

### Langkah 4: Isi seed dan pilih mode pemrosesan

Formulir memiliki beberapa bagian:

**Seed** adalah kata kunci yang menentukan nilai-nilai sintetis yang akan dihasilkan:
- Seed yang **sama** pada database yang **sama** selalu menghasilkan nilai yang **identik** (algoritma HMAC-SHA256).
- Seed berbeda menghasilkan nilai berbeda.
- Default pada formulir adalah **seed terakhir yang dipakai** (tersimpan di `setting_aplikasi`), atau `opensid-demo` bila belum pernah dijalankan.

**Foto Warga & Perangkat Desa** — pilih salah satu:

| Pilihan | Perilaku |
|---|---|
| **Kaburkan wajah** (default) | Foto warga dan pamong yang ada di folder diproses oleh skrip Python: wajah dan lencana nama di seragam dikaburkan secara otomatis. Kolom `foto` di database tidak diubah — file dimodifikasi di tempat. Memerlukan Python 3 dan pustaka `face-recognition` terinstal di server. |
| **Ganti dengan avatar kartun** | File foto asli dihapus dan diganti avatar kartun deterministik dari DiceBear API (memerlukan internet). Latar biru untuk pria, pink/peach untuk wanita. Fallback otomatis ke avatar inisial berbasis GD jika tidak ada internet. |
| **Kosongkan foto** | Kolom `foto` di-set `NULL`; aplikasi menampilkan avatar default biru (pria) atau merah (wanita) secara otomatis. File fisik di `desa/upload/user_pict/` tidak disentuh. |

**Foto Artikel, Galeri & Media** — pilih salah satu:

| Pilihan | Perilaku |
|---|---|
| **Tempel watermark "FOTO DEMO"** (default) | Foto asli tetap ada; tanda air diagonal semi-transparan bertuliskan "FOTO DEMO" ditambahkan di atas setiap foto. Untuk artikel/galeri: termasuk varian `kecil_` dan `sedang_`. Untuk media: hanya file asli (tidak ada thumbnail). |
| **Ganti dengan foto acak** | Foto diambil dari Picsum Photos (memerlukan internet); hasil deterministik per file. Jika tidak ada internet, diisi warna solid berteks "FOTO DEMO". Untuk artikel/galeri, varian `kecil_` dan `sedang_` juga dibuat; untuk media, hanya file asli yang diganti (tidak ada thumbnail). |
| **Biarkan apa adanya** | Foto artikel, galeri, dan media di `desa/upload/artikel/`, `desa/upload/galeri/`, dan `desa/upload/media/` (termasuk sub-folder) tidak disentuh. |

**Arsip Surat** — pilih salah satu:

| Pilihan | Perilaku |
|---|---|
| **Render ulang surat dari template** (default) | Setiap arsip surat yang masih menyimpan entri form (kolom `input`) di-render ulang dari template surat aslinya, tetapi memakai data yang sudah diacak — sehingga arsip demo terasa seperti surat sungguhan (kop, nomor, identitas pemohon, tanda tangan). Bila render gagal untuk suatu surat, baris itu otomatis jatuh ke placeholder. Hanya efektif di **web admin** (memerlukan stack lengkap untuk me-render PDF). |
| **PDF placeholder "[ISI SURAT DIREDAKSI]"** | Setiap arsip surat diganti PDF kosong berisi teks `[ISI SURAT DIREDAKSI]`. |

**Konfirmasi (dua centang wajib).** Tombol **Jalankan Acak Data** baru aktif setelah **kedua** kotak berikut dicentang:

1. **Sinkronisasi** — bahwa database dan folder desa yang aktif saat ini berasal dari instalasi yang sama.
2. **Tinjau arsip** — bahwa Anda telah membuka halaman **Bersih Folder Desa** dan meninjau file di `desa/arsip/` dan `desa/upload/thumbs/` yang akan dihapus permanen saat acak berjalan.

> **Catatan file yang dipertahankan:** pemilihan file per-item **tidak** dilakukan di formulir acak. Buka halaman **Bersih Folder Desa** untuk meninjau dan menghilangkan centang file yang ingin dipertahankan; daftar tersebut tersimpan dan otomatis dihormati saat Acak Data berjalan.

### Langkah 5: Konfirmasi di modal

Sebuah jendela konfirmasi akan muncul berisi ringkasan tindakan. Baca peringatannya dengan seksama, lalu klik **Sudah backup, lanjutkan**. Untuk membatalkan, klik **Belum, kembali**.

### Langkah 6: Tunggu proses selesai

Setelah dikonfirmasi, modal berubah menjadi tampilan progres yang **mengunci layar**. Proses acak dijalankan sebagai **job latar belakang** (proses terpisah di server, terlepas dari request web); halaman lalu **mem-poll status** secara berkala dan menampilkan langkah yang sedang berjalan. Karena tiap poll adalah request pendek, pendekatan ini **kebal terhadap batas waktu gateway** (`fastcgi_read_timeout` nginx/FPM) yang pada aliran sebelumnya dapat memutus koneksi ("Koneksi terputus") saat database besar atau mode berat dipilih.

Lama waktu tergantung jumlah data — untuk database dengan ribuan warga biasanya beberapa puluh detik, dan bisa beberapa menit jika mode avatar, blur, atau surat sintetis dipilih (karena ada proses generasi/manipulasi gambar atau render PDF). **Jangan tutup atau tinggalkan halaman** selama proses berlangsung agar Anda diarahkan otomatis ke halaman hasil saat selesai. (Proses tetap berjalan di server meski halaman ditutup, hanya saja ringkasan hasil tidak akan tampil.)

### Langkah 7: Periksa hasil

Setelah selesai, browser diarahkan ke halaman hasil yang menampilkan laporan mencakup:

| Informasi | Keterangan |
|---|---|
| Waktu proses | Durasi dalam menit/detik |
| Baris diproses | Total baris yang diperbarui di semua tabel |
| Tabel dimodifikasi | Jumlah tabel yang diubah |
| NIK diganti | Jumlah NIK yang diganti |
| No. KK diganti | Jumlah nomor KK yang diganti |
| Nama diganti | Jumlah nama warga yang diganti |
| No. telepon diganti | Jumlah nomor telepon yang diganti |
| Avatar dibuat | Jumlah file avatar yang dibuat (hanya muncul jika mode avatar dipilih) |
| Foto dikabur | Jumlah wajah yang dikaburkan (hanya muncul jika mode blur dipilih) |
| Foto artikel / galeri / media diproses | Jumlah file per direktori (hanya muncul jika nilainya > 0) |
| Arsip surat diganti | Jumlah berkas arsip surat yang dibuat ulang |
| Surat sintetis dibuat | Jumlah surat yang berhasil di-render ulang dari template (mode sintetis) |
| File usang dihapus | Jumlah file dihapus oleh pembersihan folder — orphan arsip, thumbs, cache, prodeskel, PII uploads, dan stray content |
| Masalah periksa diselesaikan | Total masalah `/periksa` yang diselesaikan otomatis |

Di bawah tabel statistik ditampilkan:

- **Contoh 5 baris pertama** (NIK lama → NIK baru, Nama lama → Nama baru) untuk memverifikasi proses berjalan benar.
- **Rincian penyelesaian /periksa** — tabel setiap jenis masalah beserta jumlahnya.
- Jika masih ada masalah tersisa, sebuah peringatan **"/periksa masih melaporkan:"** menampilkan daftarnya.
- Peringatan lain (mis. tabel tertentu tidak ditemukan, atau pustaka Python untuk blur tidak tersedia).

Setelah halaman hasil tampil, browser secara otomatis mengunduh ulang setiap foto yang diproses di latar belakang (`fetch` dengan `cache: 'reload'`). Indikator "Memperbarui cache browser…" akan berubah menjadi hijau saat selesai — setelah itu foto yang diperbarui tampil langsung di halaman lain tanpa perlu hard-refresh manual.

---

## Cara 2 — Melalui Artisan (Command Line)

Gunakan cara ini jika Anda memiliki akses ke terminal server dan ingin mengotomatiskan proses atau menjalankannya tanpa membuka browser.

### Perintah dasar

```bash
php artisan opensid:db-acak
```

Perintah tanpa opsi memakai **default**: seed `opensid-demo`, foto `blur`, foto konten `watermark`, dan surat `sintetis` (yang pada konteks console otomatis jatuh ke placeholder — lihat catatan di bawah).

### Opsi yang tersedia

```bash
php artisan opensid:db-acak --seed=nama-desa-demo --foto=avatar --foto-konten=watermark --surat=placeholder
```

| Opsi | Default | Keterangan |
|---|---|---|
| `--seed=<nilai>` | `opensid-demo` | Seed deterministik; seed yang sama selalu menghasilkan output identik |
| `--foto=<mode>` | `blur` | `none` — kosongkan kolom foto; `avatar` — ganti dengan avatar kartun; `blur` — kaburkan wajah & nama (butuh Python) |
| `--foto-konten=<mode>` | `watermark` | `none` — biarkan; `watermark` — tempel tanda air DEMO; `picsum` — ganti dengan foto acak. Berlaku untuk artikel, galeri, dan media sekaligus. |
| `--surat=<mode>` | `sintetis` | `placeholder` — PDF kosong `[ISI SURAT DIREDAKSI]`; `sintetis` — render ulang dari template (**hanya efektif di web**; di console selalu jatuh ke placeholder) |

> **Catatan sinkronisasi:** Artisan tidak menampilkan konfirmasi sinkronisasi. Pastikan database dan folder desa sudah sinkron secara manual sebelum menjalankan perintah ini.

> **Catatan surat sintetis:** Render surat sintetis memerlukan stack web lengkap (untuk `setting()`, identitas desa, dan mesin PDF) yang tidak tersedia di konteks console — jadi jalur artisan selalu menghasilkan **placeholder**. Gunakan web admin untuk surat sintetis.

> **Catatan resolusi periksa:** Jalur artisan **tidak** menjalankan resolusi `/periksa` otomatis. Resolusi memerlukan stack CI3 lengkap (untuk `identitas()`, `ci_auth()`, dan pustaka `Periksa`) yang tidak tersedia di konteks console. Untuk database hasil yang lolos seluruh pemeriksaan `/periksa`, gunakan jalur **web admin**, atau buka `/periksa` di web dan selesaikan manual setelah acak via artisan.

> **Catatan multi-desa:** Pada database multi-desa, perintah berhenti dengan galat sebelum memproses apa pun.

### Contoh output

```
>_ Mulai proses acak data (seed: opensid-demo, foto: blur, foto konten: watermark, surat: sintetis)…
>_ Selesai dalam 28.4 detik
>_ Baris diproses     : 29.426
>_ Tabel dimodifikasi : 21
>_ File usang dihapus  : 512
>_ Wajah dikaburkan    : 142
>_ Foto konten diproses: 79 (artikel 38, galeri 24, media 17)
>_ Arsip surat dibuat  : 63
```

> Baris NIK/KK/Nama/Telepon, Avatar, Masalah periksa, dan Surat sintetis hanya muncul bila nilainya relevan (> 0). Resolusi periksa dan surat sintetis tidak berjalan di jalur console.

### Backup sebelum menjalankan

```bash
# Contoh menggunakan mysqldump
mysqldump -u root -p nama_database > backup-sebelum-acak-$(date +%Y%m%d).sql
```

---

## Apa yang diubah oleh fitur Acak Data?

### Data yang **diganti**

| Tabel / Field | Yang diganti |
|---|---|
| NIK warga | 16 digit baru dengan format valid; prefix wilayah asli dipertahankan |
| Nama warga | Nama Indonesia realistis, sesuai jenis kelamin |
| Tanggal lahir | **Tahun dipertahankan**; bulan dan tanggal diacak |
| Nomor KK | 16 digit baru dengan format valid |
| Nama ayah / ibu | Nama sintetis, konsisten dengan data orang tua jika masih terdaftar |
| NIK ayah / ibu | Dipetakan ke NIK sintetis orang tua yang bersangkutan |
| Nomor telepon | Nomor HP Indonesia yang valid (`08xx-xxxxxxxx`) |
| E-mail | Alamat e-mail sintetis |
| Nama pamong desa | Nama sintetis |
| NIK pamong desa | Dipetakan ke NIK sintetis jika pamong juga terdaftar sebagai warga |
| Foto warga / pamong | Tergantung mode: kolom `NULL`, avatar baru, atau file dikaburkan di tempat |
| Foto artikel & galeri | Tergantung mode: tidak disentuh, tanda air ditambahkan, atau diganti foto acak; termasuk varian `kecil_` dan `sedang_` |
| Foto media (`upload/media/` dan sub-folder) | Tergantung mode: tidak disentuh, tanda air ditambahkan, atau diganti foto acak; tidak ada thumbnail |
| Data penerima program | NIK, nama, dan alamat diganti |
| Isi surat yang diterbitkan | Kolom `isi_surat` di DB diganti `[ISI SURAT DIREDAKSI]`; file arsip RTF/PDF lama **dihapus**. Untuk arsip baru di `desa/arsip/` (nama standar `surat_{id}.pdf`): mode **sintetis** me-render ulang surat dari template + entri form yang sudah disanitasi (bila masih ada), selain itu PDF placeholder kosong |
| Entri form surat (`log_surat.input`) | Mode **placeholder**: dikosongkan. Mode **sintetis**: nilai PII di dalamnya disanitasi (NIK non-warga, nama orang, nomor telepon) lalu dipertahankan agar surat dapat di-render ulang |
| Berkas scan surat masuk/keluar | Kolom `berkas_scan` di `surat_masuk` dan `surat_keluar` dikosongkan |
| Thumbnail surat lama | File PNG di `desa/upload/thumbs/` yang tidak dirujuk DB dihapus; thumbnail baru dibuat untuk setiap baris log_surat |
| PIN layanan mandiri | Diseragamkan ke PIN demo |
| Log aktivitas (audit) | Baris terkait warga dan pengguna dihapus |
| Identitas kontak desa | Nama, telepon, dan e-mail kontak diganti dengan nilai demo |
| Video anjungan | Kolom `anjungan_video` dan `tampilan_anjungan_video` dikosongkan |

### Data yang **dipertahankan**

| Data | Alasan dipertahankan |
|---|---|
| Tahun lahir semua warga | Menjaga distribusi usia agar statistik tetap bermakna |
| Prefix wilayah NIK (6 digit awal) | Menjaga konteks geografis; prefix bukan data pribadi |
| Jenis kelamin, agama, pendidikan, pekerjaan | Data demografis untuk statistik |
| Hubungan keluarga (kepala, istri, anak, dst.) | Struktur rumah tangga dipertahankan |
| RT/RW, dusun, wilayah administratif | Data geografis bukan PII |
| Nama desa, kecamatan, kabupaten, provinsi | Data institusi, bukan perorangan |
| Seluruh kunci utama dan relasi antar-tabel | Integritas referensial database harus terjaga |

---

## Mode Foto Warga & Perangkat Desa: Detail

### Kaburkan wajah (blur) — default

Foto warga dan pamong yang ada di folder diproses oleh skrip Python (`bin/blur_user_pict.py`):
- Wajah dan lencana nama di seragam dikaburkan secara otomatis menggunakan face detection.
- File foto dimodifikasi **di tempat** — nama file dan kolom `foto` di database tidak berubah.
- Memerlukan Python 3 dan pustaka `face-recognition` terinstal di server.

Jika pustaka Python tidak tersedia atau skrip gagal, proses acak tetap berlanjut dan peringatan ditampilkan di halaman hasil.

### Ganti dengan avatar kartun (avatar)

Hanya berlaku untuk warga dan perangkat desa yang **sebelumnya memiliki foto** di database. Yang tidak memiliki foto tetap `NULL`.

1. File foto asli di `desa/upload/user_pict/` **dihapus**.
2. Avatar ilustrasi diambil dari [DiceBear API](https://www.dicebear.com/). Memerlukan akses internet dari server saat proses berjalan.
3. File avatar baru (gambar penuh + thumbnail) ditulis dan kolom `foto` diperbarui.

Warna latar ditentukan secara deterministik: pria → nuansa biru, wanita → nuansa pink/peach. Jika server tidak dapat menjangkau DiceBear API, sistem jatuh ke avatar inisial berbasis GD secara otomatis.

### Kosongkan foto (none)

Kolom `foto` di tabel `tweb_penduduk` dan `tweb_desa_pamong` di-set menjadi `NULL`. Aplikasi secara otomatis menampilkan avatar bawaan OpenSID (pria → ikon biru, wanita → ikon merah). File foto fisik di `desa/upload/user_pict/` **tidak dihapus** — jika ingin mendistribusikan folder `desa/`, hapus secara manual atau gunakan fitur Bersih Folder Desa.

---

## Mode Foto Artikel, Galeri & Media: Detail

Mode ini berlaku untuk tiga direktori sekaligus:
- `desa/upload/artikel/` — foto artikel berita
- `desa/upload/galeri/` — foto galeri
- `desa/upload/media/` dan semua sub-foldernya — gambar yang diunggah melalui TinyMCE (sisipan artikel, banner, dll.)

Perbedaan penting: foto artikel dan galeri memiliki varian thumbnail (`kecil_` dan `sedang_`) yang juga diproses. Foto media adalah file penuh tanpa thumbnail — hanya file asli yang diproses.

### Tempel watermark "FOTO DEMO" (watermark) — default

Tanda air diagonal semi-transparan bertuliskan "FOTO DEMO" ditambahkan di atas setiap file foto yang ada. Untuk artikel dan galeri: tanda air juga diterapkan ke varian `kecil_` dan `sedang_`. Untuk media: hanya file asli. File dimodifikasi di tempat — nama file dan referensi di database tidak berubah.

### Ganti dengan foto acak (picsum)

Foto diganti dengan gambar acak dari [Picsum Photos](https://picsum.photos/). Seed diambil dari HMAC nama file, sehingga file yang sama selalu mendapat gambar yang sama di setiap run. Untuk artikel dan galeri: varian `kecil_` (300×200) selalu dibuat; `sedang_` (480×320) dibuat jika sudah ada sebelumnya. Untuk media: hanya file asli yang diganti (tidak ada thumbnail). Jika server tidak dapat menjangkau Picsum, otomatis menggunakan gambar placeholder warna solid berteks "FOTO DEMO".

### Biarkan apa adanya (none)

Semua foto di ketiga direktori di atas tidak disentuh sama sekali.

---

## Resolusi Pemeriksaan Database (/periksa)

Halaman **Periksa Data** (`/periksa`) mendeteksi inkonsistensi data — duplikat, referensi yatim, kolom kosong, dan sejenisnya — yang biasanya harus diselesaikan satu per satu oleh admin desa. Karena database hasil acak hanya untuk demo, Acak Data **menyelesaikan masalah-masalah ini secara otomatis** dengan keputusan deterministik, sehingga halaman `/periksa` bersih tanpa intervensi manual.

Resolusi ini berjalan sebagai **salah satu tahap menjelang akhir proses** (`PeriksaResolverSanitizer`) dan **hanya aktif pada jalur web admin**, karena bergantung pada stack CI3 (`identitas()`, `ci_auth()`, dan pustaka `Periksa`). Pada jalur artisan/headless tahap ini otomatis dilewati.

### Pembagian tanggung jawab

`PeriksaResolver` bekerja dalam dua kelompok:

1. **Masalah "penghakiman"** yang tidak di-auto-fix pustaka `Periksa` — diselesaikan deterministik di resolver: `data_null`, `kepala_keluarga_ganda`, `klasifikasi_surat_ganda`, `kepala_rtm_ganda`, `log_keluarga_ganda`, `data_duplikatartikel`, `artikel_orphan`, `data_cluster`, `menu_tanpa_parent`, dan `suplemen_terdata_kosong`.
2. **Perbaikan aman/generik** yang sudah menjadi tanggung jawab `App\Libraries\Periksa::perbaiki()` (collation, view, `log_penduduk`, relasi artikel, dll.) — cukup dipicu.

Karena `perbaiki()` dapat memunculkan kembali dua masalah kelompok 1 (`kepala_keluarga_ganda` dan `log_keluarga_ganda`), keduanya dijalankan ulang setelahnya (**pass konvergensi**), lalu resolver memverifikasi tidak ada sisa masalah.

### Masalah yang diselesaikan otomatis dan keputusannya

| Masalah `/periksa` | Keputusan deterministik |
|---|---|
| **Kepala keluarga ganda** (lebih dari satu anggota ber-`kk_level` kepala dalam satu KK) | Pilih anggota **laki-laki tertua** sebagai kepala keluarga. Anggota lain diturunkan: jadi **istri** bila perempuan dan belum ada istri di KK itu; selain itu jadi **famili lain**. Kolom `tweb_keluarga.nik_kepala` diarahkan ke kepala terpilih. |
| **`kk_level` kosong / null** (bagian `data_null`) | Backfill dengan logika serupa: jadi **istri** bila perempuan, belum ada istri, dan kepala keluarga laki-laki; selain itu **famili lain**. |
| **Data null lain** (kolom referensi & teks penduduk) | Kolom referensi diisi nilai **modus** (paling sering muncul) atau fallback wajar; kolom teks diisi placeholder. |
| **Klasifikasi surat ganda** (kode duplikat) | Pertahankan satu baris; **referensi FK diarahkan ulang** ke baris yang dipertahankan sebelum duplikat dihapus. |
| **Kepala RTM ganda** | Serupa kepala keluarga ganda — satu kepala dipertahankan, sisanya diturunkan, referensi diarahkan ulang. |
| **Log keluarga ganda** (baris `log_keluarga` ganda untuk `id_kk` + `tgl_peristiwa` yang sama) | Pertahankan **satu** baris, prioritaskan baris peristiwa **daftar** (`id_peristiwa = 1`) agar tidak di-regenerasi oleh `perbaiki()`; hapus sisanya. Dijalankan ulang pada pass konvergensi. |
| **Artikel duplikat** | Bila `judul` + `isi` **identik**, hapus salah satu. Bila berbeda, pertahankan keduanya dan beri **slug unik** baru. |
| **Artikel yatim** (kategori / penulis menggantung) | `id_kategori` yang menggantung di-null-kan (tipe diset `dinamis`); `id_user` diarahkan ke admin aktif pertama atau di-null-kan. |
| **Cluster/dusun tidak konsisten** | Variasi penulisan nama dusun dikanonikalisasi ke varian yang **paling sering** muncul. |
| **Menu tanpa parent** | `parrent` yang menggantung diset `0` (menu tingkat atas). |
| **Suplemen terdata kosong/yatim** | Baris `suplemen_terdata` yatim (tanpa sasaran penduduk/keluarga) dihapus. |

### Pelaporan di halaman hasil

Halaman hasil acak menampilkan tabel **"Rincian penyelesaian /periksa"** berisi setiap masalah yang diselesaikan beserta jumlahnya. Jika setelah pass konvergensi + verifikasi masih ada masalah tersisa, sebuah peringatan **"/periksa masih melaporkan:"** ditampilkan dengan rinciannya, sehingga admin tahu untuk membukanya manual di `/periksa`.

### Verifikasi manual

Setelah acak via web, buka `https://<domain-desa>/periksa` — seluruh pemeriksaan seharusnya bersih. Jika ada sisa, periksa peringatan di halaman hasil acak untuk mengetahui masalah mana yang perlu intervensi.

---

## Pertanyaan Umum

### Bisakah saya menjalankan acak data dua kali?

Untuk **sanitasi database** dan mode **Ganti dengan foto acak (picsum)**: ya, hasil identik setiap kali — seed yang sama menghasilkan nilai dan gambar yang sama.

Untuk mode **watermark**: **tidak idempoten**. Setiap run menambah lapisan tanda air baru di atas file yang sudah ada, karena foto dimodifikasi di tempat tanpa menyimpan salinan asli. Menjalankan dua kali akan menghasilkan dua tanda air berlapis.

**Solusi:** selalu restore folder `desa/` dari backup sebelum menjalankan ulang Acak Data dalam mode watermark.

### Mengapa setelah acak halaman /periksa bersih, padahal sebelumnya banyak masalah?

Karena Acak Data menjalankan resolusi `/periksa` otomatis pada jalur web. Masalah seperti kepala keluarga ganda, log keluarga ganda, klasifikasi surat ganda, dan artikel yatim diselesaikan dengan keputusan deterministik tanpa intervensi admin. Detail keputusan ada di bagian [Resolusi Pemeriksaan Database (/periksa)](#resolusi-pemeriksaan-database-periksa). Jika Anda menjalankan acak via **artisan**, resolusi ini dilewati — buka `/periksa` di web untuk menyelesaikannya.

### Halaman hasil menampilkan "/periksa masih melaporkan". Apa artinya?

Resolver tidak berhasil menyelesaikan semua masalah dalam pass konvergensi (mis. kategori masalah yang belum dicakup resolver, atau kondisi data tak terduga). Buka `https://<domain-desa>/periksa` dan selesaikan masalah yang tersisa secara manual. Ini jarang terjadi pada database desa normal.

### Apakah aplikasi OpenSID tetap berfungsi setelah data diacak?

Ya. Semua relasi antar-tabel dipertahankan, termasuk hubungan keluarga, data pamong, dan referensi silang NIK orang tua–anak. OpenSID akan berjalan normal dan menghasilkan statistik yang realistis karena distribusi demografis tidak diubah.

### Apakah data buku tamu, pengaduan, dan pelapak juga diacak?

Ya. Fitur ini mencakup banyak tabel pendukung ber-PII — termasuk `buku_tamu`, `pengaduan`, `pelapak`, `kontak`, `sent_items`, layanan mandiri, KIA, PPID, DTKS, dan lainnya. Lihat tabel "Data yang diganti" di atas.

### Foto sudah diproses di server, tetapi browser masih menampilkan foto lama. Apa yang terjadi?

Mode blur dan watermark memodifikasi file foto di tempat tanpa mengubah nama file. Karena URL foto tidak berubah, browser mungkin masih menampilkan versi lama dari cache-nya.

Halaman hasil acak secara otomatis mengatasi ini dengan mengunduh ulang semua foto yang diproses di latar belakang menggunakan `fetch` dengan `cache: 'reload'`. Tunggu indikator "Memperbarui cache browser…" berubah menjadi hijau sebelum membuka halaman lain.

### Apa yang terjadi jika saya jalankan acak saat database dan folder desa tidak sinkron?

Acak akan tetap berjalan, tetapi hasilnya mungkin tidak konsisten:
- Mode blur akan memproses file foto yang ada di folder — jika foto-foto tersebut bukan milik warga di database saat ini, hasilnya tidak ada hubungannya dengan data yang diacak.
- Mode watermark akan menambah tanda air ke semua foto yang ditemukan di folder, terlepas dari korespondensi dengan data database.
- Mode avatar akan menghapus foto di folder lalu membuat avatar berdasarkan ID warga di database — yang mungkin tidak cocok dengan warga yang fotonya ada di folder.

Pastikan selalu merestore database **dan** folder desa dari sumber yang sama sebelum menjalankan acak.

### Bagaimana cara memulihkan data asli setelah acak?

Restore dari file backup yang dibuat sebelum acak dijalankan — **dua backup**: database (`.sql`) dan folder desa (`.zip`). Fitur Acak Data tidak menyimpan catatan nilai asli di manapun. Memulihkan database asli juga menghilangkan penanda "Data Dianonimkan".

### Apakah log surat dihapus?

Tidak dihapus, tetapi diproses dalam tiga fase:

1. **Fase 1 — Sanitasi database:** isi surat diganti `[ISI SURAT DIREDAKSI]`, kolom `nama_surat` diubah ke format `surat_{id}.pdf` (atau `surat_dinas_{id}.pdf`), nama pamong dianonimkan, NIK non-warga diremapping. Kolom `berkas_scan` di `surat_masuk` dan `surat_keluar` dikosongkan. Kolom `input` mengikuti mode surat (dikosongkan pada placeholder; disanitasi & disimpan pada sintetis).
2. **Fase 2 — Bersih folder (ruleset lengkap):** menjalankan ruleset yang sama dengan fitur Bersih Folder Desa — semua file yang tidak diperlukan untuk data demo dihapus sekaligus:
   - `desa/arsip/` — file RTF/PDF lama dengan NIK di nama file, dan file scan yang di-null dari fase 1
   - `desa/upload/thumbs/` — thumbnail PNG yang tidak lagi cocok dengan `log_surat`
   - `desa/cache/status_langganan` — JWT token dan domain instalasi asal
   - `desa/prodeskel/` — semua file output Prodeskel (dapat di-generate ulang)
   - Direktori stray di `desa/` root dan file loose yang tidak dikenal
   - Upload PII tanpa baris DB: orphan di `dokumen/`, `pendaftaran/`, `buku_tamu/`, `vaksin/`, `pengaduan/`, `pengesahan/`

   Total file yang dihapus ditampilkan di halaman hasil sebagai "File usang dihapus". File yang Anda pertahankan lewat daftar tersimpan Bersih Folder Desa dikecualikan dari penghapusan.
3. **Fase 3 — Buat arsip baru:**
   - Mode **sintetis** (default web): untuk setiap baris `log_surat` yang masih menyimpan entri form (`input`), surat di-render ulang dari template aslinya memakai data yang sudah diacak, lalu ditulis ke `desa/arsip/surat_{id}.pdf`. Bila render gagal, baris itu jatuh ke placeholder dan dicatat sebagai peringatan.
   - Baris tanpa entri form, surat dinas, dan mode **placeholder**: file PDF kosong berjudul `[ISI SURAT DIREDAKSI]` dibuat untuk setiap baris.
   - Thumbnail PNG baru dibuat di `desa/upload/thumbs/`.

Setelah proses selesai, `desa/arsip/` berisi surat sintetis dan/atau placeholder PDF anonim — bukan arsip RTF NIK-bearing yang asli. Metadata surat (tanggal, jenis, nama pamong) tetap ada namun sudah dianonimkan.

> **Catatan jalur artisan:** `php artisan opensid:db-acak` selalu memakai mode **placeholder** untuk arsip surat. Render surat sintetis memerlukan stack web lengkap yang tidak tersedia di konteks console.

---

## Membuat Paket Demo yang Lengkap

Acak Data menangani **semua** pembersihan yang diperlukan dalam satu jalankan:

1. Sanitasi database (fase 1)
2. Pembersihan folder — ruleset identik dengan Bersih Folder Desa (fase 2)
3. Pembuatan placeholder/surat sintetis arsip surat (fase 3)
4. Pemrosesan foto warga dan konten artikel/galeri/media (sesuai mode yang dipilih)
5. Resolusi otomatis masalah `/periksa` (hanya jalur web — lihat bagian Resolusi Pemeriksaan Database)

Setelah Acak Data selesai, folder `desa/` sudah siap untuk didistribusikan: tidak ada lagi file NIK-bearing, cache instalasi, output Prodeskel, konten stray, atau upload PII tanpa baris DB. Sebuah file penanda `desa/acak_info.json` mencatat tanggal anonimasi.

Fitur **Bersih Folder Desa** (halaman terpisah di menu Database, atau `php artisan opensid:bersih-folder`) tetap tersedia jika ingin meninjau dan memilih file secara selektif. Lihat [PANDUAN_SANITASI_FOLDER_DESA.md](PANDUAN_SANITASI_FOLDER_DESA.md) untuk dokumentasi lengkap.

---

## Tips

- **Simpan seed Anda.** Catat seed yang digunakan agar tim lain dapat menghasilkan database demo yang identik kapan saja. Seed terakhir juga tersimpan otomatis sebagai default berikutnya.
- **Gunakan seed unik per desa.** Jika mengelola beberapa desa, gunakan seed berbeda untuk setiap desa agar database demo tidak saling tertukar.
- **Restore folder desa dulu, baru jalankan acak.** Jika baru saja restore dari backup, pastikan folder desa yang sesuai juga sudah di-restore sebelum menjalankan acak.
- **Gunakan mode blur untuk demo dengan foto nyata.** Mode blur mempertahankan keaslian foto (pencahayaan, komposisi) tanpa mengekspos identitas, sehingga tampilan demo lebih meyakinkan dibanding avatar atau placeholder.
- **Gunakan mode watermark untuk foto artikel/galeri.** Tanda air DEMO mencegah foto konten desa disalahartikan sebagai foto resmi dalam konteks demo.
- **Backup dulu, selalu.** Meskipun tampak sepele, kebiasaan ini menyelamatkan data jika ada kesalahan konfigurasi atau kebutuhan mendadak untuk mengembalikan data asli.
