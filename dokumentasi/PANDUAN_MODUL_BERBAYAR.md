# Panduan Modul Tambahan dan Layanan Berbayar

OpenSID mendukung pemasangan **modul tambahan** yang memperluas fitur inti — mulai dari kios layanan mandiri hingga buku tamu digital. Sebagian modul gratis, sebagian memerlukan langganan aktif dari Layanan OpenDesa.

---

## Modul yang Tersedia

| Modul | Tipe | Keterangan |
|---|---|---|
| **Pelanggan** | Gratis | Klien manajemen langganan. Mengelola token, memverifikasi status berlangganan, dan membuka akses modul berbayar. Harus dipasang pertama. |
| **Anjungan** | Berbayar | Kios layanan mandiri — warga dapat mengisi formulir, melihat informasi, dan mengakses layanan desa tanpa perlu dilayani petugas. Memerlukan langganan aktif dengan entitlement `anjungan`. |
| **Buku Tamu** | Gratis | Form kunjungan digital yang berjalan di dalam kios Anjungan. Petugas dapat melihat daftar tamu di panel admin. Memerlukan Anjungan terpasang. |

### Urutan Pemasangan

Tiga modul ini memiliki ketergantungan yang harus dipenuhi. Memasang di luar urutan ini akan menghasilkan pesan error.

```
1. Pelanggan   ──▶   2. Anjungan   ──▶   3. Buku Tamu
   (gratis)              (berbayar)           (gratis)
```

---

## Langkah 1 — Dapatkan Token Langganan

Token langganan adalah identitas digital desa Anda di sistem Layanan OpenDesa. Token ini membuktikan bahwa desa terdaftar dan memiliki hak akses atas fitur tertentu (mis. Anjungan).

1. Hubungi [OpenDesa](https://opendesa.id) untuk mendaftar atau memperbarui langganan.
2. Setelah pembayaran dikonfirmasi, Anda akan menerima token berupa string panjang — simpan dengan aman.
3. Token ini dimasukkan ke sistem satu kali; selanjutnya sistem memverifikasinya secara otomatis.

---

## Langkah 2 — Pasang Modul Pelanggan

Masuk sebagai super admin → **Admin → Paket Tambahan → tab Paket Tersedia**.

1. Temukan **Modul Pelanggan** (tipe: Gratis).
2. Klik **Pasang**.
3. Tunggu hingga proses selesai. Halaman akan muat ulang secara otomatis.

Setelah terpasang, menu **Info Desa → Pelanggan** muncul di sidebar.

> Modul Pelanggan tidak memerlukan token untuk dipasang — ia adalah klien yang nantinya menerima dan menyimpan token tersebut.

---

## Langkah 3 — Masukkan Token Langganan

1. Buka **Info Desa → Pelanggan**.
2. Di kolom **Token Layanan**, tempel token yang Anda terima dari OpenDesa.
3. Klik **Simpan**.

Sistem mengirim token ke server Layanan, memverifikasi kesesuaian dengan data desa (kode wilayah), lalu menyimpan token ke database dan file konfigurasi. Halaman Pelanggan selanjutnya menampilkan:

- Status langganan aktif (jenis layanan, masa berlaku)
- Daftar entitlement yang dimiliki desa (mis. `anjungan`)

> **Syarat koneksi:** Proses ini memerlukan akses internet dari server ke `layanan.opendesa.id`. Setelah token tersimpan, operasi harian tidak lagi memerlukan koneksi ke server Layanan.

---

## Langkah 4 — Pasang Modul Anjungan

**Prasyarat:** Modul Pelanggan terpasang dan token aktif.

1. **Admin → Paket Tambahan → tab Paket Tersedia**.
2. Temukan **Modul Anjungan** (tipe: Berbayar / Premium).
3. Klik **Pasang**. Sistem memverifikasi bahwa token desa memiliki entitlement `anjungan`. Jika hak ini tidak ada di token Anda, pemasangan ditolak — hubungi OpenDesa untuk menambahkan layanan Anjungan ke langganan.
4. Setelah terpasang, menu **Anjungan** muncul di sidebar admin.

---

## Langkah 5 — Konfigurasi Anjungan (Opsional)

Setelah terpasang, buka menu **Anjungan** di sidebar untuk mengatur tampilan dan konten kios:

- **Pengaturan umum** — nama kios, logo, video latar (opsional), warna tema.
- **Layanan yang ditampilkan** — pilih layanan mana saja yang dapat diakses warga dari kios.
- **Tampilan** — mode layar penuh atau jendela.

---

## Langkah 6 — Pasang Modul Buku Tamu (Opsional)

**Prasyarat:** Modul Anjungan terpasang.

1. **Admin → Paket Tambahan → tab Paket Tersedia**.
2. Temukan **Modul Buku Tamu** (tipe: Gratis).
3. Klik **Pasang**.

Form buku tamu akan otomatis tersedia di halaman kios Anjungan. Petugas dapat melihat daftar kunjungan di **Admin → Buku Tamu**.

---

## Mengakses Kios Anjungan

Setelah Anjungan terpasang, kios dapat diakses oleh siapa saja (tanpa login) di alamat:

```
https://<domain-desa>/index.php/anjungan-mandiri
```

Halaman ini dirancang untuk layar sentuh — tablet atau monitor kios. Warga dapat:
- Mengisi formulir layanan (surat, pengaduan, dll.) secara mandiri.
- Mengantri dan melihat informasi desa.
- Mengisi buku tamu (jika Modul Buku Tamu terpasang).

---

## Memantau dan Memperbarui Status Langganan

Halaman **Info Desa → Pelanggan** menampilkan kondisi langganan secara lengkap:

| Indikator | Keterangan |
|---|---|
| Hijau | Langganan aktif, masa berlaku > 30 hari |
| Oranye | Langganan aktif, masa berlaku 11–30 hari — segera perpanjang |
| Merah | Langganan aktif, masa berlaku ≤ 10 hari atau hampir berakhir |

Untuk memperpanjang:
1. Klik tautan **Perpanjang** di halaman Pelanggan, atau
2. Hubungi OpenDesa secara langsung melalui saluran yang tertera di [opendesa.id](https://opendesa.id).

Setelah perpanjangan dikonfirmasi, token baru akan dikirimkan. Masukkan token baru via **Info Desa → Pelanggan → Simpan** seperti pada Langkah 3.

---

## Melepas Modul

Modul dapat dilepas dari **Paket Tambahan → Riwayat Pemesanan → Lepas**.

Urutan pelepasan harus **kebalikan** dari pemasangan:

| Urutan | Modul |
|---|---|
| 1 | Buku Tamu |
| 2 | Anjungan |
| 3 | Pelanggan |

> Melepas modul **tidak menghapus data** yang sudah tersimpan di database. Laporan kunjungan buku tamu, log layanan, dan konfigurasi Anjungan tetap ada. Saat modul dipasang kembali, data tersebut dapat diakses lagi.

---

## Pertanyaan Umum

### Token saya valid tetapi Anjungan tidak dapat dipasang

Pastikan token memiliki entitlement `anjungan`. Buka **Info Desa → Pelanggan** dan periksa daftar layanan aktif. Jika Anjungan tidak tercantum, hubungi OpenDesa untuk menambahkan layanan tersebut ke langganan Anda.

### Halaman kios Anjungan tidak dapat dibuka

Periksa bahwa modul Anjungan terpasang dan token masih aktif. Jika token sudah kedaluwarsa, fitur Anjungan akan terkunci. Perbarui token dan pastikan masa berlaku masih berlaku di halaman Pelanggan.

### Buku Tamu tidak muncul di kios meski sudah dipasang

Pastikan Anjungan dikonfigurasi untuk menampilkan menu Buku Tamu. Buka **Admin → Anjungan → Pengaturan** dan periksa daftar layanan yang diaktifkan di kios.

### Apakah kios Anjungan memerlukan internet setiap saat?

Tidak. Setelah token tersimpan, kios beroperasi sepenuhnya secara lokal. Koneksi internet hanya diperlukan saat pertama kali menyimpan atau memperbarui token langganan.

### Apakah data warga hilang jika modul dilepas?

Tidak. Data (laporan buku tamu, log layanan, konfigurasi) tetap tersimpan di database dan dapat diakses kembali saat modul dipasang ulang.

### Token sudah disimpan tetapi status langganan tidak muncul di halaman Pelanggan

Coba hapus cache: buka terminal di server dan jalankan `php artisan cache:clear`. Jika masih tidak muncul, periksa koneksi server ke `layanan.opendesa.id` saat token pertama kali disimpan — token mungkin tidak berhasil diverifikasi.
