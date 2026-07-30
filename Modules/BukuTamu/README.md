# Modul Buku Tamu

Modul **Buku Tamu** untuk OpenSID — registrasi & manajemen tamu yang berkunjung ke kantor desa, terintegrasi dengan kiosk Anjungan Mandiri.

Dikemas sebagai **add-on OpenSID** (kontrak `module.json` + `Providers/BukuTamuServiceProvider`), didistribusikan & dipasang dinamis via Layanan. Membutuhkan modul **Anjungan** (`"membutuhkan": ["Anjungan"]`).

## Struktur

```
Acak/              sanitizer PII tabel buku_tamu (Premium: daftarkan ke RegistriPembersih)
Config/            konfigurasi modul
Database/
  Migrations/      migrasi tabel buku_tamu, keperluan, pertanyaan, kepuasan
  Seeders/         seeder keperluan, pengaturan, akses modul
Events/            TamuSubmitted (dipicu saat tamu baru registrasi)
FolderDesa/        aturan pembersih foto tamu yatim (Premium: daftarkan ke RegistriModul)
Http/Controllers/
  BackEnd/         admin: daftar tamu, keperluan, pertanyaan kepuasan
  FrontEnd/        kiosk: registrasi tamu & indeks kepuasan (via Anjungan)
Http/Requests/     validasi form keperluan, pertanyaan, tamu
Listeners/         SendTamuNotification (notifikasi admin saat tamu baru)
Models/            TamuModel, KeperluanModel, PertanyaanModel, KepuasanModel
Notifications/     TamuBaru (notifikasi database Laravel)
Providers/         BukuTamuServiceProvider (registrasi modul + seam ke core)
Routes/            web.php
Views/             tampilan backend & frontend + aset
module.json        metadata add-on (nama, versi, dependensi)
```

## Dependensi

- **Anjungan** — modul ini menggunakan kiosk FrontEnd Anjungan sebagai titik masuk registrasi tamu. Installer menegakkan urutan pasang: Anjungan harus terpasang sebelum BukuTamu.

## Kompatibilitas core

- **Premium (PHP 8.4):** `BukuTamuServiceProvider` mendaftarkan sanitizer PII ke `RegistriPembersih` dan aturan folder ke `RegistriModul` (seam Premium-only, dijaga `$this->app->bound()`).
- **Umum (PHP 8.2):** seam Premium tidak terikat → registrasi tersebut di-skip otomatis; event + kategori notifikasi tetap aktif.

## Status: ekstraksi (PoC)

Repo ini dibuat sebagai bagian PoC pemisahan fitur premium dari core OpenSID Umum menjadi add-on.
Lihat issue perencanaan: `OpenSID/premium#6682`.
