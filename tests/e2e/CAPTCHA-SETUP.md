# Panduan Mendapatkan CAPTCHA Code untuk Testing

## Cara Mendapatkan CAPTCHA Code yang Valid

CAPTCHA di halaman login OpenSID adalah **dinamis** — berubah setiap kali halaman di-reload. Anda perlu mendapatkan kode CAPTCHA yang benar-benar ditampilkan saat login.

### Opsi 1: Manual (Recommended untuk Lokal Testing)

1. **Buka halaman login di browser:**
   ```
   http://localhost/premium/siteman
   ```

2. **Lihat kode CAPTCHA** yang ditampilkan di form login (biasanya di bawah form password)

3. **Copy kode CAPTCHA** (contoh: `QB.v4pRFwK` atau format lainnya)

4. **Update file `tests/e2e/.env`:**
   ```env
   LOGIN_CAPTCHA=QB.v4pRFwK
   ```

5. **Jalankan test:**
   ```bash
   npx playwright test tests/e2e/admin-menu-structure.spec.js
   ```

### Opsi 2: Disable CAPTCHA di Development Mode

Jika Anda memiliki akses ke file konfigurasi OpenSID:

1. Cari file konfigurasi yang mengatur CAPTCHA (biasanya di `config/` atau `app/Config/`)

2. Disable CAPTCHA untuk environment lokal:
   ```php
   'use_captcha' => env('USE_CAPTCHA', false), // Set false untuk dev
   ```

3. Jalankan test tanpa perlu CAPTCHA code

### Opsi 3: Menggunakan File Session Tersimpan

Jika sudah pernah login sebelumnya:

1. Browser akan otomatis redirect dari `/siteman` ke `/beranda` jika session masih valid

2. File `tests/e2e/storage/auth.json` akan otomatis tersimpan dengan session tersebut

3. Test berikutnya akan menggunakan session yang sudah tersimpan

---

## Troubleshooting

### Error: "Captcha yang dimasukkan tidak valid"

- ❌ CAPTCHA code di `.env` **sudah expired** atau tidak sesuai
- ✅ Solusi: Dapatkan CAPTCHA code baru dari browser sesuai Opsi 1

### Error: "LOGIN_CAPTCHA tidak dikonfigurasi"

- ❌ CAPTCHA field ada di form tapi `.env` tidak punya nilai `LOGIN_CAPTCHA`
- ✅ Solusi: Tambah `LOGIN_CAPTCHA=<kode>` ke `.env` sesuai Opsi 1

### Jika server menggunakan CAPTCHA gambar visual

Alternatif lain:
- Gunakan pytest + selenium dengan OCR library untuk read CAPTCHA
- Atau setup webhook untuk bypass CAPTCHA di testing environment
- Atau gunakan headless browser dengan eksternal CAPTCHA solver API

---

## Catatan

Untuk integration test atau CI/CD pipeline yang fully automated, **sebaiknya disable CAPTCHA di environment testing** atau gunakan token/API key alternatif untuk login yang tidak memerlukan CAPTCHA.
