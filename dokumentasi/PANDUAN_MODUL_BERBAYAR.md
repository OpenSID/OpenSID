# Panduan Modul Tambahan dan Layanan Berbayar

OpenSID mendukung pemasangan **modul tambahan** yang memperluas fitur inti — mulai dari kios layanan mandiri hingga buku tamu digital. Sebagian modul gratis, sebagian memerlukan langganan aktif dari Layanan OpenDesa.

---

## Modul yang Tersedia

| Modul | Tipe | Keterangan |
|---|---|---|
| **Pelanggan** | Gratis | Klien manajemen langganan. Dipasang otomatis saat token pertama kali disimpan. Mengelola token, memverifikasi status berlangganan, dan membuka akses modul berbayar. |
| **Anjungan** | Berbayar | Kios layanan mandiri — warga dapat mengisi formulir, melihat informasi, dan mengakses layanan desa tanpa perlu dilayani petugas. Memerlukan langganan aktif. |
| **Buku Tamu** | Gratis | Form kunjungan digital yang berjalan di dalam kios Anjungan. Memerlukan Anjungan terpasang. |

### Urutan Pemasangan

```
1. Masukkan token  ──▶  2. Pelanggan (otomatis)  ──▶  3. Anjungan  ──▶  4. Buku Tamu
                            (gratis)                      (berbayar)       (gratis)
```

---

## Langkah 1 — Dapatkan Token Langganan

Token langganan adalah identitas digital desa Anda di sistem Layanan OpenDesa.

1. Hubungi [OpenDesa](https://opendesa.id) untuk mendaftar atau memperbarui langganan.
2. Setelah pendaftaran dikonfirmasi, Anda menerima token berupa string panjang — simpan dengan aman.
3. Token dimasukkan ke sistem satu kali; selanjutnya sistem memverifikasinya secara otomatis.

---

## Langkah 2 — Masukkan Token Layanan

1. Masuk sebagai super admin → **Pengaturan Aplikasi** → cari kolom **Token Layanan**.
2. Tempel token yang diterima dari OpenDesa, lalu klik **Simpan**.

Setelah token disimpan, sistem secara otomatis:

- Menghubungi server Layanan OpenDesa untuk memverifikasi token.
- Mengunduh dan memasang **Modul Pelanggan** (klien manajemen langganan) jika belum ada.
- Setelah terpasang, menu **Info Desa → Pelanggan** muncul di sidebar.

> **Syarat koneksi:** Proses ini memerlukan akses internet dari server ke `layanan.opendesa.id`.
> Setelah modul terpasang dan token tersimpan, operasi harian tidak lagi memerlukan koneksi ke server Layanan.

---

## Jika Layanan Tidak Dapat Dihubungi saat Token Disimpan

Bila server tidak memiliki akses internet saat token pertama kali disimpan, Modul Pelanggan mungkin tidak terpasang otomatis. Gejala: token sudah tersimpan di pengaturan tetapi menu Info Desa → Pelanggan tidak muncul, dan halaman **Paket Tambahan** menampilkan pesan peringatan.

**Cara mengatasi:**

1. Pastikan server memiliki akses internet ke `layanan.opendesa.id`.
2. Buka **Admin → Paket Tambahan → tab Paket Tersedia**.
3. Klik tombol **Coba Pasang Sekarang** yang muncul di bagian atas halaman.

Tombol ini memanggil ulang endpoint bootstrap Layanan dan memasang modul yang diperlukan. Jika berhasil, halaman akan menampilkan notifikasi sukses dan modul Pelanggan akan tersedia.

Alternatif: simpan ulang token di **Pengaturan Aplikasi** (hapus dan isi kembali) — ini juga memicu ulang proses bootstrap secara otomatis.

---

## Langkah 3 — Pasang Modul Anjungan

**Prasyarat:** Modul Pelanggan terpasang dan token aktif.

1. Buka **Admin → Paket Tambahan → tab Paket Tersedia**.
2. Temukan **Modul Anjungan** (tipe: Berbayar / Premium).
3. Klik **Pasang**. Sistem memverifikasi bahwa token desa memiliki entitlement `anjungan`. Jika hak ini tidak ada, pemasangan ditolak — hubungi OpenDesa untuk menambahkan layanan Anjungan ke langganan.
4. Setelah terpasang, menu **Anjungan** muncul di sidebar admin.

---

## Langkah 4 — Konfigurasi Anjungan (Opsional)

Setelah terpasang, buka menu **Anjungan** di sidebar untuk mengatur tampilan dan konten kios:

- **Pengaturan umum** — nama kios, logo, video latar (opsional), warna tema.
- **Layanan yang ditampilkan** — pilih layanan mana saja yang dapat diakses warga dari kios.
- **Tampilan** — mode layar penuh atau jendela.

---

## Langkah 5 — Pasang Modul Buku Tamu (Opsional)

**Prasyarat:** Modul Anjungan terpasang.

1. **Admin → Paket Tambahan → tab Paket Tersedia**.
2. Temukan **Modul Buku Tamu** (tipe: Gratis).
3. Klik **Pasang**.

Form buku tamu otomatis tersedia di halaman kios Anjungan. Daftar kunjungan dapat dilihat di **Admin → Buku Tamu**.

---

## Mengakses Kios Anjungan

Setelah Anjungan terpasang, kios dapat diakses siapa saja (tanpa login) di:

```
https://<domain-desa>/index.php/anjungan-mandiri
```

Halaman ini dirancang untuk layar sentuh — tablet atau monitor kios. Warga dapat mengisi formulir layanan, melihat informasi desa, dan mengisi buku tamu (jika Modul Buku Tamu terpasang).

---

## Memantau dan Memperbarui Status Langganan

Halaman **Info Desa → Pelanggan** menampilkan kondisi langganan secara lengkap:

| Indikator | Keterangan |
|---|---|
| Hijau | Langganan aktif, masa berlaku > 30 hari |
| Oranye | Langganan aktif, masa berlaku 11–30 hari — segera perpanjang |
| Merah | Langganan aktif, masa berlaku ≤ 10 hari atau hampir berakhir |

Untuk memperpanjang, hubungi OpenDesa dan masukkan token baru di **Pengaturan Aplikasi** seperti pada Langkah 2.

---

## Melepas Modul

Modul dapat dilepas dari **Paket Tambahan → Paket Terpasang → Lepas**.

Urutan pelepasan harus **kebalikan** dari pemasangan:

| Urutan | Modul |
|---|---|
| 1 | Buku Tamu |
| 2 | Anjungan |
| 3 | Pelanggan |

> Melepas modul **tidak menghapus data** yang sudah tersimpan di database. Data dapat diakses kembali saat modul dipasang ulang.

---

## Pertanyaan Umum

### Setelah token disimpan, menu Pelanggan tidak muncul

Kemungkinan Layanan tidak dapat dihubungi saat token disimpan. Buka **Paket Tambahan → Paket Tersedia** dan klik **Coba Pasang Sekarang** jika tombol tersebut muncul. Pastikan server memiliki akses internet ke `layanan.opendesa.id`.

### Token saya valid tetapi Anjungan tidak dapat dipasang

Pastikan token memiliki entitlement `anjungan`. Buka **Info Desa → Pelanggan** dan periksa daftar layanan aktif. Jika Anjungan tidak tercantum, hubungi OpenDesa untuk menambahkan layanan tersebut ke langganan.

### Halaman kios Anjungan tidak dapat dibuka

Periksa bahwa Modul Anjungan terpasang dan token masih aktif. Jika token kedaluwarsa, fitur Anjungan akan terkunci. Perbarui token dan pastikan masa berlaku masih valid di halaman Pelanggan.

### Buku Tamu tidak muncul di kios meski sudah dipasang

Pastikan Anjungan dikonfigurasi untuk menampilkan menu Buku Tamu. Buka **Admin → Anjungan → Pengaturan** dan periksa daftar layanan yang diaktifkan.

### Apakah kios Anjungan memerlukan internet setiap saat?

Tidak. Setelah token tersimpan dan modul terpasang, kios beroperasi sepenuhnya secara lokal. Koneksi internet hanya diperlukan saat pertama kali menyimpan atau memperbarui token.

### Apakah data warga hilang jika modul dilepas?

Tidak. Data (laporan buku tamu, log layanan, konfigurasi) tetap tersimpan di database dan dapat diakses kembali saat modul dipasang ulang.
