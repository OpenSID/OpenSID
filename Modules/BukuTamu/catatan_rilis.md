# Catatan Rilis — Modul Buku Tamu

## 2607.0.0 (ekstraksi dari core)
- Ekstraksi modul BukuTamu dari `OpenSID/premium` (`Modules/BukuTamu`) ke repo add-on tersendiri.
- Struktur & boilerplate mengikuti template modul `OpenSID/modul-anjungan`.
- Tambahkan dependensi `"membutuhkan": ["Anjungan"]` — installer menegakkan urutan pasang.
- `BukuTamuServiceProvider` menyatukan Premium (Acak + FolderDesa seam) dan Umum (tanpa seam) lewat guard `$this->app->bound()`.
- Konteks & rencana: `OpenSID/premium#6682`.
