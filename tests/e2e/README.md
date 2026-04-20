# OpenSID E2E Tests

End-to-end testing untuk OpenSID menggunakan Playwright.

## Setup

1. Install dependencies:
```bash
npm install --save-dev @playwright/test dotenv
npx playwright install
```

2. Copy `.env.example` ke `.env` dan sesuaikan konfigurasi:
```bash
cp tests/e2e/.env.example tests/e2e/.env
```

3. **PENTING**: Update file `tests/e2e/.env` dengan credentials dan CAPTCHA yang valid:
```env
BASE_URL=http://localhost/premium
LOGIN_USERNAME=admin
LOGIN_PASSWORD=sid304
LOGIN_CAPTCHA=<CAPTCHA_CODE_YANG_VALID>  # ⚠️ Lihat CAPTCHA-SETUP.md untuk cara mendapatkan
HEADLESS=false
```

4. **Cara mendapatkan LOGIN_CAPTCHA:**
   - Buka `http://localhost/premium/siteman` di browser
   - Lihat kode CAPTCHA di form login
   - Copy dan paste ke `.env`
   - Lihat detail di [`CAPTCHA-SETUP.md`](./CAPTCHA-SETUP.md)

## Struktur File

```
tests/e2e/
├── .env                              # Konfigurasi environment (git ignored)
├── .env.example                      # Template konfigurasi
├── auth.js                           # Helper untuk login dan session management
├── example.spec.js                   # Contoh test
├── man-user.spec.js                  # Test spesifik halaman Man User
│
├── helpers/
│   ├── menu-config.js                # Konfigurasi semua menu admin (URL, tabel, tombol)
│   └── page-helpers.js               # Shared utilities (monitor, wait, assert)
│
├── admin-menu-structure.spec.js      # Test 1 & 2: Jumlah dan nama menu di sidebar
├── admin-menu-pages.spec.js          # Test 3 & 4: Halaman terbuka tanpa error 404/500/403/DT
├── admin-menu-buttons.spec.js        # Test 5: Semua tombol berfungsi dan bisa diinteraksi
│
└── storage/                          # Folder untuk menyimpan session dan screenshot
    ├── auth.json                     # Session storage (auto-generated)
    └── *.png                         # Screenshots (auto-generated)
```

## Test Suite Admin Menu

### Plan Testing

| Suite | File | Cakupan |
|-------|------|---------|
| **Struktur Sidebar** | `admin-menu-structure.spec.js` | Jumlah menu, nama menu, tidak duplikat, href valid |
| **Kesehatan Halaman** | `admin-menu-pages.spec.js` | Tidak ada 404/500/403, tidak ada DT console error |
| **Interaksi Tombol** | `admin-menu-buttons.spec.js` | Tombol tidak disabled, modal Tambah buka, filter trigger reload, hapus konfirmasi |

### Rincian Test Cases

#### 1. Struktur Sidebar (`admin-menu-structure.spec.js`)
- `1.1` Sidebar dapat dibaca setelah login
- `1.2` Jumlah menu utama tidak kurang dari minimum (5)
- `1.3` Tidak ada menu utama dengan teks kosong
- `1.4` Tidak ada menu utama yang duplikat
- `1.5` Menu konfigurasi sesuai dengan menu yang tampil di sidebar
- `1.6` Semua sub-menu dapat dilihat (expand parent)
- `1.7` Setiap link menu memiliki href yang valid
- `1.8` Laporan jumlah total menu (informatif)

#### 2. Kesehatan Halaman (`admin-menu-pages.spec.js`)
- `2.1` Setiap halaman terbuka tanpa HTTP error 404 / 500 (~80 halaman)
- `2.2` Setiap halaman tidak mengembalikan 403 Forbidden (~80 halaman)
- `2.3` Tidak ada console error terkait DataTables (~80 halaman)
- `2.4` DataTables berhasil dimuat pada halaman yang menggunakannya (~50 halaman)
- `2.5` AJAX request DataTables tidak menghasilkan error
- `2.6` Laporan ringkasan semua halaman (informatif)

#### 3. Interaksi Tombol (`admin-menu-buttons.spec.js`)
- `3.1` Semua tombol visible tidak dalam status disabled (~80 halaman)
- `3.2` Tombol Tambah berhasil membuka modal atau navigasi (~30 halaman)
- `3.3` Filter dropdown men-trigger DataTables reload (halaman dengan filter)
- `3.4` Tombol Edit baris pertama berfungsi (15 halaman sample)
- `3.5` Tombol Hapus memunculkan dialog konfirmasi, lalu **dibatalkan** (10 halaman)
- `3.6` Kolom pencarian DataTables berfungsi (10 halaman sample)
- `3.7` Laporan ringkasan interaksi tombol (informatif)

## Cara Menjalankan Test

### Option 1: Run dengan UI Mode (Recommended untuk Development) ⭐
```bash
npx playwright test tests/e2e --ui
```
✅ Menampilkan dashboard interaktif untuk melihat progress test real-time  
✅ Bisa lihat setiap step test, screenshot, console output

### Option 2: Run dengan Headed Browser (Lihat browser berjalan)
```bash
npx playwright test tests/e2e --headed
```
✅ Browser akan terbuka dan Anda bisa lihat proses testing  
✅ Cocok untuk debugging interaksi user

### Option 3: Run dengan CLI Reporter (tanpa UI)
```bash
npx playwright test tests/e2e --reporter=list
```
✅ Hasil test ditampilkan di terminal  
✅ Paling cepat untuk CI/CD

### Option 4: Jalankan test tertentu
```bash
# Test Suite 1 - Menu Structure
npx playwright test tests/e2e/admin-menu-structure.spec.js --ui

# Test Suite 2 - Menu Pages  
npx playwright test tests/e2e/admin-menu-pages.spec.js --ui

# Test Suite 3 - Menu Buttons
npx playwright test tests/e2e/admin-menu-buttons.spec.js --ui
```

### Option 5: Generate HTML Report
```bash
npx playwright test tests/e2e --reporter=html
npx playwright show-report
```
✅ Membuka HTML report di browser dengan hasil lengkap

### Debug mode
```bash
npx playwright test tests/e2e --debug
```
✅ Step-by-step debugging dengan Playwright Inspector

---

## Global Setup Flow (Otomatis sebelum test pertama)

**Saat menjalankan test, global-setup akan berjalan SEKALI untuk:**

1. ✅ **Check Existing Session**  
   Apakah sudah ada `auth.json` dengan session yang valid?
   - Jika YES → Gunakan session itu, skip login

2. ✅ **Check Login Status**  
   Akses `/siteman` dan cek auto-redirect ke `/beranda`
   - Jika YES (redirect) → Session masih aktif, simpan ke auth.json, skip login
   - Jika NO (tetap di /siteman) → Belum login, lanjut ke step 3

3. ✅ **Auto Login**  
   Jika belum login, jalankan login otomatis:
   - Isi username dari `LOGIN_USERNAME` di `.env`
   - Isi password dari `LOGIN_PASSWORD` di `.env`  
   - Isi CAPTCHA dari `LOGIN_CAPTCHA` di `.env` ⚠️
   - Klik tombol login
   - Tunggu redirect ke `/beranda`
   - Simpan session ke `auth.json`

4. ✅ **Run All Tests**  
   Setelah global setup selesai, semua test berjalan dengan session yang sudah tersimpan  
   Tidak perlu login lagi!

## Membuat Test Baru

Contoh test untuk mengecek DataTables:

```javascript
import { test, expect } from '@playwright/test';
import { STORAGE_STATE } from './auth.js';

test.describe('Man User DataTables', () => {
    test.use({ storageState: STORAGE_STATE });

    test('should load user management table', async ({ page }) => {
        // Buka halaman man_user
        await page.goto(process.env.BASE_URL + '/man_user');
        
        // Tunggu DataTables selesai load
        await page.waitForSelector('#tabeldata', { timeout: 10000 });
        
        // Cek tidak ada error alert
        const errorAlert = page.locator('.alert-danger');
        await expect(errorAlert).not.toBeVisible();
        
        // Cek DataTables ada data
        const tableRows = page.locator('#tabeldata tbody tr');
        await expect(tableRows.first()).toBeVisible();
        
        console.log('✅ DataTables loaded successfully');
    });
});
```

## Authentication

File `auth.js` menyediakan helper untuk:
- `setupAuth()` - Setup authentication sebelum test
- `login(username, password)` - Login manual
- `isSessionValid()` - Cek apakah session masih valid
- `checkLoginStatus(context)` - Cek status login

Session disimpan di `tests/e2e/storage/auth.json` dan akan di-reuse untuk semua test.

## Tips

1. **Session Management**: Session akan otomatis di-reuse jika masih valid. Jika expired, akan otomatis login ulang.

2. **Screenshots**: Semua screenshot error disimpan di `tests/e2e/storage/` untuk debugging.

3. **Console Errors**: Monitor console errors dengan:
```javascript
page.on('console', msg => {
    if (msg.type() === 'error') {
        console.log('Console error:', msg.text());
    }
});
```

4. **Network Monitoring**: Monitor network requests:
```javascript
page.on('requestfailed', request => {
    console.log('Request failed:', request.url());
});
```

## Troubleshooting

### Login gagal
- Cek credentials di `.env`
- Cek screenshot di `tests/e2e/storage/login-failed.png`
- Pastikan BASE_URL benar

### DataTables tidak load
- Cek console errors di browser
- Cek network tab untuk failed requests
- Pastikan route sudah POST (bukan GET)

### Session expired
- Hapus `tests/e2e/storage/auth.json`
- Jalankan test ulang untuk login fresh
