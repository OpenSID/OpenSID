# Panduan Menjalankan Test OpenSID

Dokumen ini menjelaskan cara menyiapkan lingkungan pengujian dan menjalankan test suite (Unit & Feature) pada proyek OpenSID yang menggunakan Laravel + PHPUnit.

---

## Prasyarat

Pastikan hal-hal berikut sudah terpasang di mesin Anda:

- PHP `^8.4`
- Composer
- MySQL (pastikan server sedang berjalan)
- Node.js & npm (untuk build aset front-end jika dibutuhkan)

---

## Langkah 1 — Buat File `.env.testing`

File `.env.testing` adalah konfigurasi environment untuk testing yang **tidak masuk ke version control** (di-gitignore). Buat file ini dari template yang sudah tersedia:

```bash
cp .env.testing.example .env.testing
```

Isi default dari `.env.testing.example` mengasumsikan MySQL berjalan di port **3306** dengan user/password standar. Jika konfigurasi MySQL Anda berbeda (misalnya menggunakan MAMP yang defaultnya port **8889**), edit file `.env.testing` dan sesuaikan nilai berikut:

```env
DB_CONNECTION=default
DB_HOST=127.0.0.1
DB_PORT=8889
DB_USERNAME=root
DB_PASSWORD=root
```

> **Sesuaikan** nilai `DB_PORT`, `DB_USERNAME`, dan `DB_PASSWORD` dengan konfigurasi MySQL Anda. Jika sudah sesuai dengan default (port 3306, user root, password kosong), gunakan nilai dari template.

---

## Langkah 2 — Buat Database Test

Test suite membutuhkan database terpisah agar tidak mencemari data development. Buat database kosong bernama `opensid-laravel-test` di MySQL:



> Database ini **tidak perlu diisi tabel secara manual**. Saat test berjalan, Laravel secara otomatis memuat skema dari `tests/playwright/storage/database/schema.sql` menggunakan trait `RefreshDatabase`.

---

## Langkah 3 — Jalankan Test

Setelah semua langkah di atas selesai, jalankan seluruh test suite:

```bash
php artisan test
```

Atau jika ingin menggunakan PHPUnit langsung:

```bash
vendor/bin/phpunit
```

---

## Perintah Test Lainnya

| Tujuan | Perintah |
|--------|----------|
| Jalankan semua test | `php artisan test` |
| Hanya test Unit | `vendor/bin/phpunit --testsuite=Unit` |
| Hanya test Feature | `vendor/bin/phpunit --testsuite=Feature` |
| Filter satu test class | `vendor/bin/phpunit --filter=NamaTestClass` |
| Satu file test tertentu | `vendor/bin/phpunit tests/Unit/NamaTest.php` |
| Tampilkan coverage | `vendor/bin/phpunit --coverage-text --filter=NamaClass` |

