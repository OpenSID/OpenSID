# Panduan Bursa Paket Lokal (Pengembangan)

Bursa paket lokal adalah **alat pengembangan** yang menyimulasikan Layanan sebagai
**gudang berkas ZIP paket** di mesin lokal. Dengannya Anda dapat memasang, memperbarui,
melepas, dan mengajukan-ulang add-on (mis. Anjungan) **tanpa** server Layanan atau token —
untuk memverifikasi paket garapan sendiri maupun garapan orang lain.

> **Khusus pengembangan.** Fitur ini hanya aktif saat `ENVIRONMENT=development`. Controller,
> rute, view, dan servisnya di-`export-ignore` sehingga **tidak ikut** ke rilis. Pada rilis /
> di luar `development`, halaman Paket Tambahan berperilaku seperti biasa (memakai Layanan).

---

## 1. Konsep

- **Bursa paket lokal = gudang ZIP.** Persis seperti Layanan, ia hanyalah kumpulan berkas ZIP
  paket di `storage/app/dev-marketplace/<Nama>.zip` (plus sidecar `<Nama>.json` berisi metadata:
  nama, versi, sumber, ref, waktu).
- **Sumber aktif.** Halaman Paket Tambahan bisa dialihkan antara **Layanan** (server nyata) dan
  **bursa paket lokal**. Saat mode lokal aktif, seluruh tab (Paket Tersedia, Form Pendaftaran,
  Riwayat Pemesanan) beroperasi atas bursa paket lokal.
- **Terdaftar ≠ terpasang.** Mendaftarkan paket ke bursa hanya membuatnya *tersedia*; paket baru
  benar-benar *terpasang* setelah Anda memasangnya (Pasang) sehingga masuk ke `Modules/`.
- **Ambil/lepas (get/release).** Pasang = ambil (get); Hapus = lepas (release). Paket yang sudah
  dilepas dapat diajukan ulang dari bursa — melatih putaran lengkap seperti pada Layanan.

### Paket apa yang muncul di fitur Paket?

| Jenis | Contoh | Muncul di tab Paket? |
|---|---|---|
| Add-on marketplace | Anjungan, Buku Tamu, DTSEN | **Ya** (bila tersedia dari sumber / terpasang) |
| Modul inti OSS | Analisis, Kehadiran, Lapak | **Tidak** (`module.json` → `"marketplace": false`) |
| Infrastruktur | Pelanggan | **Tidak** (`"marketplace": false`) |

Add-on dikelola bursa **secara default**; modul inti OSS & infrastruktur menyatakan
`"marketplace": false` di `module.json` agar dikecualikan. Aturan ini membuat add-on yang
dipasang dari repo eksternal tetap tampil walau manifes-nya tak menyetel flag.

---

## 2. Prasyarat

- `ENVIRONMENT=development` (fitur di-gate ganda: konstruktor controller + guard `class_exists`).
- Untuk mendaftarkan paket **dari URL repo privat** (mis. `OpenSID/modul-anjungan`): **`gh`**
  (GitHub CLI) sudah login — `gh auth login`. Repo publik tak perlu; alternatif token lewat
  environment `GITHUB_TOKEN`.
- (Opsional) `git` di PATH bila mendaftarkan snapshot dari folder repo lokal.

---

## 3. Membuka panel

**Pengaturan → Paket Tambahan → tab "Sumber"** (tab paling kanan, hanya tampil di
`development`). Di sana ada: pemilih **Sumber**, form **Daftarkan paket dari URL repo**, form
**Daftarkan dari folder lokal**, dan tabel **Isi bursa paket lokal**.

---

## 4. Alur pemakaian

### 4.1 Mengisi bursa (daftarkan paket)

**Dari URL repo (utama):**
1. Buka tab **Sumber**.
2. Pada **Daftarkan paket dari URL repo**, isi `owner/repo` (mis. `OpenSID/modul-anjungan`) —
   awalan `https://github.com/` sudah tersedia. Boleh juga tempel URL lengkap atau bentuk
   `owner/repo/tree/<ref>`.
3. (Opsional) isi **Ref** (cabang/tag) untuk versi spesifik. Kosong = cabang utama repo.
4. Klik **Unduh & daftarkan**. Sistem mengunduh ZIP arsip repo (via `gh`) ke gudang.

**Dari folder lokal (opsional):** untuk paket yang sedang Anda garap sendiri — pilih paket
terpasang dari dropdown atau isi path folder (berisi `module.json`), lalu **Snapshot & daftarkan**.
Ini menyimpan *snapshot working-tree* (termasuk perubahan belum-commit) ke gudang.

### 4.2 Mengaktifkan sumber lokal

Pada bagian **Sumber**, pilih **Bursa paket lokal (gudang ZIP)** lalu **Terapkan sumber**.
Badge tab akan menunjukkan **Lokal**.

### 4.3 Memasang (ambil / get)

- **Paket Tersedia:** paket yang terdaftar di bursa muncul di sini. Klik **Pasang** (atau
  **Tingkatkan Versi** bila versi bursa lebih baru dari yang terpasang).
- **Form Pendaftaran:** pilih paket → **Ajukan & Pasang** (setara "get" dari bursa).

Paket yang baru dipasang muncul di **Paket Terpasang** dan tercatat di **Riwayat Pemesanan**.

### 4.4 Melepas (release) & ajukan ulang

- **Paket Terpasang → Hapus** melepas paket (folder `Modules/<Nama>` dihapus, tercatat di
  Riwayat Pemesanan). Paket kembali muncul sebagai *belum terpasang* di **Paket Tersedia**.
- Untuk memakainya lagi, **Pasang** ulang dari bursa (get). Inilah putaran get/release penuh.

### 4.5 Mengeluarkan dari bursa

Di tabel **Isi bursa paket lokal**, klik **Keluarkan** untuk menghapus ZIP + sidecar paket dari
gudang (tidak menghapus yang sudah terpasang; hanya mengeluarkannya dari daftar tersedia).

---

## 5. Status "Belum terverifikasi"

Pada **Paket Terpasang**, add-on yang terpasang tetapi **hak pakainya belum dapat diverifikasi**
diberi keterangan *"Belum terverifikasi"* (banner + per-kartu). Ini terjadi bila:

- **Mode lokal:** paket belum terdaftar di bursa paket lokal, atau
- **Mode Layanan:** tanpa token / Layanan tak terjangkau / langganan tak mengembalikan paket itu.

Paket tetap terdaftar sebagai terpasang, tetapi fiturnya dinonaktifkan hingga terverifikasi
(penegakan sebenarnya lewat gerbang entitlement). Untuk memverifikasi di mode lokal: daftarkan
paket itu ke bursa (langkah 4.1).

---

## 6. Konfigurasi

Kunci config (opsional) di `donjo-app/config/config.php` atau environment:

| Config | Environment | Guna |
|---|---|---|
| `module_dev_repo_base` | `MODULE_DEV_REPO_BASE` | Direktori induk berisi checkout repo paket (untuk pendaftaran folder lokal) |
| `module_dev_repo_strategy` | `MODULE_DEV_REPO_STRATEGY` | `working-tree` (default) |
| `module_dev_repo_ref` | `MODULE_DEV_REPO_REF` | ref default (mis. `HEAD`) |
| `module_dev_repo_fetch` | `MODULE_DEV_REPO_FETCH` | `git fetch` dulu sebelum arsip |

Pengunduhan dari URL repo tidak memerlukan config ini — cukup `gh` yang sudah login.

---

## 7. Catatan & lokasi berkas

- Gudang ZIP: `storage/app/dev-marketplace/` (per paket: `<Nama>.zip` + `<Nama>.json`).
- Log pemesanan get/release: `storage/app/dev-marketplace-pesanan.json`.
- Tidak ada perubahan skema/DB — nol jejak; semua state di berkas dan sesi.
- Add-on dikelola bursa secara default; kecualikan modul inti dengan `"marketplace": false`
  di `module.json`-nya.
- Berkas fitur ini (`Dev_modul`, `LocalMarketplace`, rute `Routes/Web/dev.php`, view
  `admin/dev_modul/*`) di-`export-ignore` → **tak ikut rilis**.
