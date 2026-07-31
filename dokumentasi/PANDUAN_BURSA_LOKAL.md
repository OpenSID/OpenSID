# Panduan Bursa Paket Lokal (Dev)

Bursa paket lokal adalah pengganti server Layanan yang berjalan sepenuhnya di mesin pengembang — tanpa koneksi ke server produksi. Alur pasang/lepas modul, simulasi langganan, dan emulator API Layanan semuanya dilayani dari lokal, sehingga pengembang dapat menguji seluruh siklus hidup modul — dari pendaftaran paket hingga pemasangan di admin — tanpa bergantung pada server eksternal.

Fitur ini **hanya tersedia pada `ENVIRONMENT=development`** dan tidak ikut rilis (`export-ignore`).

---

## Cara Kerja

```
Admin UI (tab Sumber)
  └─▶ setel "Bursa paket lokal"
       └─▶ tulis storage/app/dev-marketplace/.mode = 1

pre_controller hook (setiap request)
  └─▶ LocalMarketplace::aktif() baca .mode
       └─▶ PengalihLayananLokal: override config
            ├─▶ server_layanan   → https://<domain-lokal>/layanan-lokal
            └─▶ bursa.url_penyedia → https://<domain-lokal>/layanan-lokal

Modul memanggil API Layanan
  └─▶ Guzzle menembak Layanan_lokal controller (self-HTTP)
       └─▶ LocalLayanan::body() menyusun respons dari identitas desa lokal
```

Katalog, pengajuan GET/RELEASE, dan seluruh endpoint `/api/v1/pelanggan/*` dilayani oleh controller `Layanan_lokal` dan layanan `LocalLayanan` — bukan server `layanan.opendesa.id`.

---

## Prasyarat

| Kebutuhan | Keterangan |
|---|---|
| `ENVIRONMENT=development` | Wajib. Emulator menolak request di luar environment ini. Setel di `desa/config/config.php` atau via `.env` (`APP_ENV=development`). |
| `gh auth login` | Diperlukan untuk mengunduh ZIP repo **privat** (mis. `modul-anjungan`, `modul-pelanggan`). Untuk repo publik, `GITHUB_TOKEN` juga bisa sebagai alternatif. |
| Domain HTTPS lokal | `test-premium.test` (atau domain Herd setara) harus bisa diakses. Emulator melayani self-HTTP via TLS lokal. |

---

## Langkah 1 — Buka Tab Sumber

Masuk sebagai super admin → **Admin → Paket Tambahan → tab Sumber**.

Tab ini hanya muncul pada `ENVIRONMENT=development`. Di tab inilah semua pengelolaan bursa paket lokal dilakukan: pendaftaran paket, pemilihan sumber aktif, dan simulasi data langganan.

---

## Langkah 2 — Daftarkan Paket ke Gudang

Sebelum memasang modul, paket harus didaftarkan ke gudang ZIP lokal di `storage/app/dev-marketplace/`. Ada tiga cara:

### A — Dari URL Repo GitHub

Di bagian **Daftarkan paket**, isi kolom **URL repo** dengan alamat repo GitHub:

```
https://github.com/OpenSID/modul-pelanggan
https://github.com/OpenSID/modul-anjungan
https://github.com/OpenSID/modul-bukutamu
```

Bentuk ringkas `owner/repo` juga diterima:

```
OpenSID/modul-pelanggan
OpenSID/modul-anjungan
OpenSID/modul-bukutamu
```

Untuk mendaftarkan dari **cabang atau tag tertentu**, tambahkan `/tree/<nama-ref>` di akhir URL:

```
https://github.com/OpenSID/modul-anjungan/tree/teknis/fitur-baru
```

Sistem mengunduh ZIP arsip repo via `gh api` (untuk repo privat yang sudah di-auth via `gh auth login`) atau HTTP biasa (untuk repo publik atau dengan `GITHUB_TOKEN`). Setelah berhasil, dua berkas tersimpan di gudang:

- `storage/app/dev-marketplace/<Nama>.zip` — arsip paket
- `storage/app/dev-marketplace/<Nama>.json` — metadata sidecar (nama, versi, sumber, waktu)

### B — Dari Folder Lokal (Working-Tree)

Jika modul sedang dikerjakan secara lokal (mis. `Modules/Anjungan/` di checkout aktif), pilih opsi **Folder lokal** dan isi path lengkap:

```
/path/ke/project/Modules/Anjungan
```

Sistem membungkus working-tree menjadi ZIP sementara lalu menyimpannya ke gudang. Berguna untuk menguji perubahan lokal tanpa push ke GitHub terlebih dulu.

### C — Kandidat Otomatis

Tab Sumber mendeteksi modul yang sudah terpasang di `Modules/` tetapi belum ada di gudang, dan menampilkannya sebagai **kandidat**. Klik **Daftarkan dari folder** untuk snapshot cepat.

### Perbarui Paket

Jika repo sudah diperbarui (ada commit baru), daftarkan ulang URL yang sama — ZIP lama di gudang akan ditimpa.

---

## Langkah 3 — Aktifkan Mode Bursa Lokal

Di kotak **Sumber paket**, pilih radio **Bursa paket lokal**, lalu klik **Terapkan sumber**.

Setelah ini, setiap request mewarisi override URL — semua panggilan API modul ke server Layanan dialihkan ke emulator lokal.

Untuk kembali ke server produksi nyata: pilih **Layanan (server nyata)** → Terapkan.

---

## Langkah 4 — Pasang Modul dalam Urutan yang Benar

Ketiga modul utama memiliki ketergantungan struktural yang harus dipenuhi. Pemasangan di luar urutan ini akan ditolak dengan pesan error.

### Diagram Ketergantungan

```
Pelanggan
  │  provides: ["klien-langganan"]
  │
  └──▶ Anjungan
         │  requires_entitlement: true
         │  (butuh modul yang men-provide "klien-langganan")
         │
         └──▶ BukuTamu
                  membutuhkan: ["Anjungan"]
                  (butuh Anjungan terpasang)
```

### Urutan Pemasangan

| Urutan | Modul | Tipe | Prasyarat |
|---|---|---|---|
| **1** | **Pelanggan** | Gratis | Tidak ada — pasang pertama |
| **2** | **Anjungan** | Berbayar | Pelanggan harus terpasang (karena menyediakan `klien-langganan`) |
| **3** | **BukuTamu** | Gratis | Anjungan harus terpasang (dideklarasikan di `membutuhkan`) |

Pasang setiap modul di tab **Paket Tersedia** → klik **Pasang** sesuai urutan.

#### Mengapa Pelanggan harus pertama?

Modul berbayar (`requires_entitlement: true`) diverifikasi oleh `ModuleManager::cekPrasyaratEntitlement()` yang mencari modul aktif yang men-declare `provides: ["klien-langganan"]` di `module.json`-nya. Jika Pelanggan belum terpasang, kapabilitas ini tidak tersedia dan pemasangan Anjungan ditolak.

#### Mengapa BukuTamu harus setelah Anjungan?

`ModuleManager::cekPrasyaratStruktur()` membaca field `membutuhkan` dari `module.json` BukuTamu. Setiap modul yang tercantum di sana harus sudah terpasang di `Modules/`. Ini diterapkan tanpa pengecualian, bahkan di mode dev.

---

## Langkah 5 — Simulasi Data Langganan

Anjungan memeriksa keaktifan entitlement melalui `GerbangFitur` yang membaca token/status dari modul Pelanggan. Tanpa data langganan aktif, fitur Anjungan akan terkunci meski modul sudah terpasang.

Di tab Sumber, blok **"Data langganan pelanggan (simulasi Layanan)"**:

- Klik **Isi data langganan simulasi** — sistem mengisi cache `status_langganan` dengan data Premium + Hosting yang dibangun dari identitas desa lokal, seolah respons datang dari server Layanan nyata.
- Halaman **Info Desa → Pelanggan** akan menampilkan status langganan aktif.
- Klik **Kosongkan** untuk kembali ke kondisi tanpa langganan.

Simulasi ini bekerja karena emulator `Layanan_lokal` melayani endpoint `/api/v1/pelanggan/pemesanan` dengan data yang dibangun oleh `LocalLayanan::body()` — respons identik dengan yang dikirim server produksi.

---

## Verifikasi Setelah Pemasangan

| Modul | Cara Verifikasi |
|---|---|
| **Pelanggan** | Menu **Info Desa → Pelanggan** muncul di sidebar admin |
| **Anjungan** | Menu **Anjungan** muncul di sidebar; URL `/anjungan-mandiri` dapat diakses |
| **BukuTamu** | Muncul di halaman kios Anjungan; form buku tamu tersedia |

---

## Melepas Modul

Di tab **Riwayat Pemesanan** → klik **Lepas** pada modul yang ingin dihapus.

Urutan pelepasan harus **kebalikan** dari pemasangan:

| Urutan | Modul |
|---|---|
| 1 | BukuTamu |
| 2 | Anjungan |
| 3 | Pelanggan |

Melepas Anjungan saat BukuTamu masih terpasang akan ditolak — `ModuleManager::dependensiBalik()` mendeteksi bahwa BukuTamu bergantung pada Anjungan dan mencegah penghapusan.

---

## Membatalkan Pendaftaran Paket dari Gudang

Paket dapat dihapus dari gudang lokal (ZIP + sidecar) melalui tombol **Batalkan** di daftar paket tab Sumber. Ini tidak melepas modul dari `Modules/` — lepas dulu modul yang terpasang sebelum membatalkan pendaftaran.

---

## Referensi File dan Path

| Path | Keterangan |
|---|---|
| `storage/app/dev-marketplace/` | Gudang ZIP paket + sidecar JSON |
| `storage/app/dev-marketplace/.mode` | Berkas penanda mode: `1` = bursa lokal, `0` = Layanan |
| `storage/app/dev-marketplace-pesanan.json` | Log riwayat get/release |
| `donjo-app/controllers/Layanan_lokal.php` | Emulator endpoint API Layanan (dev-only) |
| `donjo-app/Routes/Web/dev.php` | Rute emulator — hanya dimuat di `ENVIRONMENT=development` |
| `app/Services/Module/LocalMarketplace.php` | Logika gudang, katalog, unduhan |
| `app/Services/Layanan/LocalLayanan.php` | Pembangkit isi respons simulasi |
| `app/Services/Layanan/PengalihLayananLokal.php` | Override URL Layanan ke emulator |
| `config/bursa.php` | `url_penyedia` — di-override saat mode lokal aktif |

---

## Pertanyaan Umum

### Tab Sumber tidak muncul di admin

Pastikan `ENVIRONMENT=development`. Rute emulator di `Routes/Web/dev.php` hanya dimuat ketika environment ini aktif; tab Sumber dikondisikan oleh pemeriksaan yang sama.

### Gagal mengunduh ZIP dari repo privat

Jalankan `gh auth login` terlebih dulu dan pastikan akun GitHub Anda memiliki akses ke repo tersebut. Jika `gh` tidak terinstal, set `GITHUB_TOKEN` sebagai variabel environment ke personal access token dengan scope `repo`.

### Anjungan terpasang tetapi fitur terkunci / halaman anjungan tidak muncul

Data langganan simulasi belum diisi. Buka tab Sumber → klik **Isi data langganan simulasi**. Jika sudah diisi tetapi masih terkunci, coba hapus cache: `php artisan cache:clear`.

### Paket di gudang sudah usang (commit lama)

Daftarkan ulang URL repo yang sama — sidecar + ZIP akan ditimpa dengan versi terbaru.

### Perubahan local folder tidak terdeteksi setelah daftar ulang

Batalkan pendaftaran paket lama lewat tombol **Batalkan**, lalu daftarkan ulang dari folder. Jika modul sudah terpasang, lepas dulu, daftarkan ulang, lalu pasang kembali.
