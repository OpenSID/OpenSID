# ADR-013: Laravel-Native Admin Controller, Middleware Chain & Premium Services

## Status
**Accepted** — Implemented in `improve-konversi` branch (Agustus 2026)

## Context

OpenSID menjalankan dua framework (Laravel + CodeIgniter 3) secara hibrid. Controller admin yang sudah dikonversi ke Laravel-native masih bergantung pada CI bootstrap untuk helper, session, dan validasi premium. Sementara itu, controller CI yang belum dikonversi (`Admin_Controller`) menjalankan setup berulang di `__construct()` (auth, premium, langganan, view sharing) yang tidak portabel ke Laravel.

Permasalahan utama:

1. **Surat_mohon (CI) sudah dikonversi ke SyaratSuratController (Laravel-native)** — CI controller `donjo-app/controllers/Surat_mohon.php` dihapus, diganti `app/Http/Controllers/Admin/SyaratSuratController.php`
2. **CI route `surat_mohon` dihapus** — pindah ke Laravel-native `routes/admin.php`
3. **Duplikasi setup** — `Admin_Controller::__construct()` menjalankan auth check, `cek_identitas_desa()`, `PelangganService::perbaruiLangganan()`, View::share — semua harus direplikasi di Laravel-native middleware
4. **Premium validation dependen pada CI router** — `CekService::isExceptController()` membaca `$this->ci->router->class` yang tidak tersedia di konteks Laravel
5. **Redirect tidak controlled** — `CekService::validasi()` memanggil `redirect('peringatan')` tanpa return; di Laravel, function `redirect()` mengembalikan object (bukan exit)
6. **Config tersebar** — `config_item('server_layanan')`, `config_item('token_layanan')`, `config_item('demo_mode')` dari CI config tidak tersedia di konteks Laravel
7. **Session/Cache CI** — `$this->ci->session->set_userdata()`, `app('ci')->cache->file->get()` tidak tersedia di konteks Laravel
8. **Constants duplikasi** — PREMIUM, VERSION_BUILD, WEBSITE_DEMO, MODUL_BAWAAN, TEMA_PREMIUM_FREE didefinisikan di `donjo-app/helpers/core_helper.php` hanya untuk CI path
9. **CI helpers tidak tersedia** — Blade view menggunakan `site_url()`, `form_open()` dari CI helper yang tidak di-load untuk Laravel-native route
10. **MY_Input CSRF** — CI CSRF tetap diverifikasi untuk rute Laravel-native karena `MY_Input::__construct()` tidak mengecek route
11. **Null controller di middleware** — `view()->shared('controller')` mengembalikan null karena timing pipeline middleware (view share belum di-set saat middleware jalan sebelum controller)
12. **Bug operator precedence** — `$flagPerbaharui ?? false && ...` menyebabkan skip-by-controller tidak pernah bekerja

## Decision

### 1. Arsitektur Controller & Inheritance

```
SyaratSuratController extends Controller extends \Illuminate\Routing\Controller
```

- `Controller.php` — `abstract class Controller extends \Illuminate\Routing\Controller` agar bisa menggunakan `$this->middleware()`, `$this->authorize()`, dll.
- **Tidak ada lagi `BaseController` / `BaseAdminController`** — keduanya dihapus. Setup yang dulu dijalankan di constructor base class (setting, desa, cek_anjungan, migration check, tracker, view share) dipindah ke middleware chain (lihat section 2).

**Konteks modul dideklarasikan via attribute `#[AdminModul]`** (`app/Attributes/AdminModul.php`):

```php
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class AdminModul
{
    public function __construct(
        public readonly string $modul,                  // wajib — slug lowercase
        public readonly ?string $subModul = null,
        public readonly ?string $kategoriPengaturan = null,
        public readonly ?string $controller = null,
    ) {}
}
```

- Attribute **tidak repeatable** — maksimal satu per class dan satu per method (dijamin PHPStan rule, lihat section 15)
- Bisa di level class (berlaku untuk semua method) atau level method (override untuk method spesifik)
- `modul` harus literal slug `[a-z0-9-]+` lowercase (dijamin PHPStan rule)
- Contoh pemakaian di codebase:
  - `#[AdminModul(modul: 'layanan-surat', subModul: 'daftar-persyaratan')]` — `SyaratSuratController`
  - `#[AdminModul(modul: 'statistik', subModul: 'laporan-perkembangan')]` — `LaporanPerkembanganController`
  - `#[AdminModul(modul: 'pengaturan', subModul: 'acak-data')]` — `AcakController`

**SyaratSuratController** (`app/Http/Controllers/Admin/SyaratSuratController.php`):
- Menggantikan `donjo-app/controllers/Surat_mohon.php` (CI — dihapus)
- Extends `App\Http\Controllers\Controller` polos + attribute `#[AdminModul]` — tanpa constructor setup
- CRUD standar Laravel: index, datatables, form, store, update, destroy, destroyAll
- Menggunakan `SyaratSuratRequest` untuk validasi
- Menggunakan `DataTables` untuk server-side
- View: `admin.syaratan_surat.index` + `admin.syaratan_surat.form`

Logic spesifik controller tetap boleh diletakkan di constructor via `$this->middleware(fn($r, $n) {...})` — contoh `AcakController::__construct()` yang memeriksa super admin dan modul aktif.

### 2. Middleware Chain (Laravel Route Group)

Group middleware `admin` didefinisikan di `bootstrap/app.php`:

```
bootstrap/app.php:
  $middleware->group('admin', [
      'auth:admin',
      \App\Http\Middleware\EnsureDesaInstalled::class,
      \App\Http\Middleware\CheckDesaSetupCompletion::class,
      \App\Http\Middleware\ShareAnjungan::class,
      \App\Http\Middleware\ShareAdminModul::class,
      \App\Http\Middleware\ShareDesaData::class,
      \App\Http\Middleware\CheckDatabaseMigrations::class,
      \App\Http\Middleware\TrackDesaActivity::class,
  ]);
```

```
routes/web.php:
  Route::middleware('admin')->group(base_path('routes/admin.php'));
```

| Urutan | Middleware | Fungsi |
|--------|-----------|--------|
| 1 | `auth:admin` | Redirect ke `siteman` jika belum login |
| 2 | `EnsureDesaInstalled` | Cek instalasi desa + app key + reconnect DB |
| 3 | `CheckDesaSetupCompletion` | **Validasi premium**, identitas desa, force password, setup warnings |
| 4 | `ShareAnjungan` | View::share `cek_anjungan` via `ResolveCurrentAction` |
| 5 | `ShareAdminModul` | View::share konteks modul (`modul_ini`, `sub_modul_ini`, `controller`, `akses_modul`, `kategori_pengaturan`) dari `#[AdminModul]` |
| 6 | `ShareDesaData` | View::share setting, desa, auth, session, langganan, notifikasi; panggil `PelangganService::perbaruiLangganan()` |
| 7 | `CheckDatabaseMigrations` | `MigrationCheckAction::execute()` (skip di env testing) |
| 8 | `TrackDesaActivity` | `TrackDesaAction::execute()` → `Tracker::trackDesa()` (skip di env testing) |

**Authenticate middleware** (`app/Http/Middleware/Authenticate.php`):
- Sebelum: redirect ke `route('login')`
- Sesudah: redirect ke `'siteman'` untuk admin guard, `route('login')` untuk guard lain

### 3. Middleware Configuration Migration: Kernel.php → bootstrap/app.php

Middleware groups dan aliases dipindah dari `app/Http/Kernel.php` ke `bootstrap/app.php` (Laravel 11+ pattern).

**bootstrap/app.php**:
```php
$middleware->web(
    append: [\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class],
    replace: [
        \Illuminate\Cookie\Middleware\EncryptCookies::class => \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class => \App\Http\Middleware\VerifyCsrfToken::class,
    ],
);
$middleware->group('codeigniter', [
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \App\Http\Middleware\AuthenticateSession::class,
    \App\Http\Middleware\CodeIgniterResponseMiddleware::class,
]);
$middleware->alias([
    'auth'   => \App\Http\Middleware\Authenticate::class,
    'guest'  => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'signed' => \App\Http\Middleware\ValidateSignature::class,
    'can'    => \App\Http\Middleware\Authorize::class,
]);

$middleware->group('admin', [
    'auth:admin',
    \App\Http\Middleware\EnsureDesaInstalled::class,
    \App\Http\Middleware\CheckDesaSetupCompletion::class,
    \App\Http\Middleware\ShareAnjungan::class,
    \App\Http\Middleware\ShareAdminModul::class,
    \App\Http\Middleware\ShareDesaData::class,
    \App\Http\Middleware\CheckDatabaseMigrations::class,
    \App\Http\Middleware\TrackDesaActivity::class,
]);
```

### 4. CekService Refactor (Laravel-Native)

**Hapus dependensi CI**:
- `public \CI_Controller $ci;` — dihapus dari class
- `$this->ci = app()->make(\CI_Controller::class)` — dihapus dari constructor
- Constructor hanya set `$this->token = setting('layanan_opendesa_token')`
- `$this->ci->session->set_userdata()` → `session()->put()`
- `$this->ci->session->unset_userdata()` → `session()->forget()`
- `$this->ci->header['desa']` → `identitas()` (object, bukan array)
- `config_item('server_layanan')` → `config('services.opendesa.server_layanan')`
- `config_item('token_layanan')` → `config('services.opendesa.token_layanan')`
- `config_item('demo_mode')` → `config('opensid.demo_mode')`
- `$this->ci->agent->browser()` → `request()->userAgent()`
- `$this->ci->agent->platform()` → `PHP_OS` (sederhanakan)
- `$this->ci->input->ip_address()` → `request()->ip()`
- `log_message()` → `logger()->error()`
- `$this->ci->load->library('user_agent')` → dihapus

**Perubahan signature**:
```php
// Sebelum
public function validasi(): bool
public function validasiVersi($install = false): bool

// Sesudah
public function validasi(?string $currentController = null, bool $redirectOnFail = true)
public function validasiVersi(?string $currentController = null, bool $install = false)
```

**isExceptController** — tidak lagi membaca CI router:
```php
private function isExceptController(?string $currentController): bool
{
    if ($currentController === null) {
        return false;
    }
    return in_array(strtolower($currentController), $this->kecuali);
}
```

Array `$kecuali` ditambah `'pelanggan'` dan `'pendaftaran_kerjasama'` — slug/camelCase dari route name Laravel.

**Redirect controlled via parameter**:
```php
if (! $this->validasiAkses()) {
    if ($redirectOnFail) {
        return redirect('peringatan');
    }
    return false;
}
```

**Method baru** `versiSetara()` — sebelumnya disimpan ke `$this->ci->versi_setara`:
```php
private function versiSetara(string $berakhir): string
{
    $date = date('Y-m-d', strtotime('+7 month', strtotime($berakhir)));
    return str_replace('-', '', substr($date, 2, 5)) . '.0.0';
}
```

**Method `daftarHitam()`** — parameter ditambah `object $desa`:
```php
private function daftarHitam(object $desa): void
{
    // Browser: request()->userAgent() ?: 'Unidentified User Agent'
    // OS: PHP_OS
    // IP: request()->ip()
    // POST ke server_layanan/api/v1/pelanggan/daftarhitam
}
```

### 5. PelangganService Refactor (Laravel-Native)

- Hapus `use CI_Controller`
- Hapus `$ci = get_instance()` dari `apiPelangganPemesanan()` dan `perbaruiLangganan()`
- `app('ci')->cache->file->get()` → `cache()`
- `app('ci')->session->set_userdata()` → `session()->put()`
- `config_item('server_layanan')` → `config('services.opendesa.server_layanan')`
- `$ci->list_setting->firstWhere('key', 'layanan_opendesa_token')?->value` → `setting('layanan_opendesa_token')`
- `app()->make('ci')` → parameter `$currentController` + `$flagPerbaharui`
- `config_item('demo_mode')` → `config('opensid.demo_mode')`
- `$ci->cache->pakai_cache()` → `cache()->rememberForever()`
- Hapus `$ci->load->helper('file')`
- `site_url()` → `url()`

**Method `perbaruiLangganan()`** — fix operator precedence:
```php
// SEBELUM (salah): $flagPerbaharui ?? false && ...
// SESUDAH (benar): ($flagPerbaharui ?? false) && ...
```

**Method `pemesanan()`** — tidak lagi menerima `CI_Controller $ci`:
```php
// Sebelum
private static function pemesanan(CI_Controller $ci, object $data)

// Sesudah
private static function pemesanan(object $data): void
```
Data desa diambil via `identitas()` langsung.

### 6. Constants Migration: CI → Laravel

PREMIUM, VERSION_BUILD, RANGE_PREMIUM_MASUK_UMUM, MINIMUM_VERSION_BUILD, WEBSITE_DEMO, MODUL_BAWAAN, TEMA_PREMIUM_FREE dipindah dari `donjo-app/helpers/core_helper.php` ke `app/Helpers/core_helper.php`.

Constants ini didefinisikan di global scope sehingga tersedia baik untuk CI maupun Laravel context.

### 7. Helper Functions Migration: CI → Laravel

| Function | Old Location | New Location | Status |
|----------|-------------|-------------|--------|
| `can()` | `donjo-app/helpers/general_helper.php` | `app/Helpers/general_helper.php` | ✅ Dipindahkan |
| `isCan()` | `donjo-app/helpers/general_helper.php` | `app/Helpers/general_helper.php` | ✅ Dipindahkan |
| `folder()` | `donjo-app/helpers/general_helper.php` | `app/Helpers/general_helper.php` | ✅ Dipindahkan |
| `folder_desa()` | `donjo-app/helpers/general_helper.php` | `app/Helpers/general_helper.php` | ✅ Dipindahkan |
| `cek_anjungan()` | `donjo-app/helpers/core_helper.php` | `app/Helpers/core_helper.php` | ✅ Dipindahkan |
| `calculate_days()` | `donjo-app/helpers/general_helper.php` | `app/Helpers/general_helper.php` | ✅ Dipindahkan |
| `calculate_date_intervals()` | `donjo-app/helpers/general_helper.php` | `app/Helpers/general_helper.php` | ✅ Dipindahkan |
| `AmbilVersi()` | `donjo-app/helpers/opensid_helper.php` | `app/Helpers/opensid_helper.php` | ✅ Dipindahkan |
| `currentVersion()` | `donjo-app/helpers/opensid_helper.php` | `app/Helpers/opensid_helper.php` | ✅ Dipindahkan |
| `favico_desa()`, `gambar_desa()` | `donjo-app/helpers/opensid_helper.php` | `app/Helpers/opensid_helper.php` | ✅ Dipindahkan |
| `isLocalIPAddress()` | `donjo-app/helpers/opensid_helper.php` | `app/Helpers/opensid_helper.php` | ✅ Dipindahkan |
| `AmbilFoto()`, `Foto_Default()`, constants MIME/EXT | `donjo-app/helpers/pict_helper.php` | `app/Helpers/pict_helper.php` | ✅ Dipindahkan |
| `super_admin()` | `donjo-app/helpers/opensid_helper.php` | — | ❌ Dead code — tidak dipindahkan, `User::superAdmin()` sudah handle |
| `is_super_admin()` | `donjo-app/helpers/opensid_helper.php` | — | ❌ Dead code — tidak dipindahkan |
| `is_group_administrator()` | `donjo-app/helpers/opensid_helper.php` | — | ❌ Dead code — tidak dipindahkan |
| `parsedown()` | `donjo-app/helpers/general_helper.php` | — | ❌ Dead code — tidak dipindahkan |
| `admin_menu()` | `donjo-app/helpers/general_helper.php` | — | ❌ Dead code — Modul tree sudah handle |
| `get_domain()` | `donjo-app/helpers/opensid_helper.php` | — | ❌ Dead code — hanya dipakai `is_demo_mode()` yang sudah di-refaktor |
| `pantau_is_down()`, `httpPost()`, `get_pantau_attempt_count()`, `reset_pantau_status()`, `mark_pantau_down()` | `donjo-app/helpers/opensid_helper.php` | — | ❌ Dipindahkan ke Tracker service |

Semua fungsi di `app/Helpers/` menggunakan guard `if (! function_exists(...))` untuk mencegah konflik definisi ganda.

### 8. Admin_Controller: Bridge Code ke Legacy CI3

`donjo-app/core/Admin_Controller.php` — perubahan minimal (bridge code):

```php
// Line 78: Parameter controller untuk skip-by-controller logic
PelangganService::perbaruiLangganan($this->controller);

// Line 229: Parameter controller untuk isExceptController()
$validasi = (new CekService())->validasi($this->controller);

// Line 249-252: CI3 cache metadata → Laravel cache()
// Sebelum: $this->cache->file->get_metadata('status_langganan')
// Sesudah: empty(cache('status_langganan'))

// Line 262: CI3 environment check → helper is_demo_mode()
// Sebelum: app()->environment(['development', 'testing']) || (config_item('demo_mode') && ...)
// Sesudah: is_demo_mode()
```

### 9. Web_Controller: Bridge Code

`donjo-app/core/Web_Controller.php` — method `pemesanan()`:

```php
// Closure tidak static — perlu akses $controller dari scope parent
$pemesanan = cache()->remember('tema_premium', 60 * 60 * 24 * 7, function () use ($controller) {
    // CI3 file cache → Laravel cache()
    $data = cache('status_langganan');
    // ...
    PelangganService::perbaruiLangganan($controller);
});
```

### 10. MY_Input: CSRF Skip untuk Laravel Route

`donjo-app/core/MY_Input.php` — tambah constructor + method `isLaravelRoute()`:

```php
public function __construct()
{
    // ... parent initialization ...
    if ($this->_enable_csrf === true && ! is_cli() && ! $this->isLaravelRoute()) {
        $this->security->csrf_verify();
    }
}

private function isLaravelRoute(): bool
{
    try {
        $uri   = load_class('URI', 'core');
        $route = \OpenSID\RouteBuilder::getByUrl($uri->uri_string());
        return $route->is404;
    } catch (\OpenSID\Exception\RouteNotFoundException $e) {
        return true;
    }
}
```

### 11. Config Standardization

| Old Config | New Config | File |
|-----------|-----------|------|
| `config_item('server_layanan')` | `config('services.opendesa.server_layanan')` | `config/services.php` |
| `config_item('token_layanan')` | `config('services.opendesa.token_layanan')` | `config/services.php` |
| `config_item('demo_mode')` | `config('opensid.demo_mode')` | `config/opensid.php` |
| `WEBSITE_DEMO` array (inline) | `is_demo_mode()` helper | `app/Helpers/opensid_helper.php` |

### 12. SyaratSurat Model — Repository Pattern

`app/Models/SyaratSurat.php`:
- `scopeFormatSuratExist()` — di-mark `@deprecated`, pindah ke `SyaratSuratRepository::queryWithFormatSuratExist()`
- `scopeDeleteFormatSuratExist()` — di-mark `@deprecated`, pindah ke `SyaratSuratRepository::deleteWithFormatSuratExist()`
- Method static `formatSuratExist()` dan `deleteFormatSuratExist()` ditambahkan sebagai wrapper backward-compatibility untuk CI3 fallback
- Ditambahkan `@property int $jumlah_format_surat` (PHPStan fix)

### 13. Authenticate & Authorize Middleware

**Authenticate** (`app/Http/Middleware/Authenticate.php`):
```php
// Guard admin → redirect ke 'siteman'
if ($this->auth->guard('admin')->guest()) {
    return 'siteman';
}
return route('login');
```

**Authorize** (`app/Http/Middleware/Authorize.php` — baru):
```php
// Untuk non-JSON request, redirect ke 'beranda' dengan flash error
// Untuk JSON request, throw 403 (default Laravel)
```

### 14. Konteks Modul — Single Source of Truth via `AdminModul::resolve()`

Sebelumnya konteks modul (modul, sub-modul, controller) di-ekstrak di 3+ tempat dengan cara berbeda:
- `Admin_Controller::$controller` via `$this->router->class`
- `Web_Controller` via `$this->router->class`
- Property hooks `$modulIni` / `$subModulIni` di base controller (butuh inheritance, tidak portable)

Sekarang:

**`AdminModul` attribute** adalah **satu-satunya sumber** deklarasi konteks, dan **`AdminModul::resolve()`** adalah satu-satunya titik resolusi:

```php
public static function resolve(?string $class, ?string $method = null): ?self
```

- Urutan resolusi: attribute level **method** → attribute level **class** (menelusuri parent & trait) → fallback `name($class)`
- `name($class)` = `strtolower(class_basename($class) tanpa suffix 'Controller')` — contoh `LaporanPerkembanganController` → `laporanperkembangan`
- Hasil **selalu non-null** untuk route ber-controller (fallback nama class); `null` hanya untuk closure route — middleware `ShareAdminModul` memakai prefix route name sebagai fallback terakhir (`closure-fallback.index` → `closure-fallback`)
- Memoized via static cache (`forClass()` / `forMethod()`); cache di-reset dengan `AdminModul::flush()` pada teardown test (`$this->beforeApplicationDestroyed(...)` di `BaseTestCase::setUp()`)

Variabel view yang di-share `ShareAdminModul`:

| Variabel | Sumber |
|----------|--------|
| `modul_ini` | `AdminModul::$modul` (atau fallback controller) |
| `sub_modul_ini` | `AdminModul::$subModul` |
| `controller` | `AdminModul::$controller` (fallback `name()` atau prefix route name untuk closure route) |
| `akses_modul` | `subModul ?: modul` |
| `kategori_pengaturan` | `AdminModul::$kategoriPengaturan` |

Variabel `modul` dan `alias_controller` yang dulu di-share **dihapus** — verifikasi grep di seluruh `resources/views`, `Modules`, dan `donjo-app` menunjukkan 0 pemakaian (tidak ada Blade view yang berubah).

### 15. Enforced by PHPStan — `AdminModulAttributeRule`

`app/PHPStan/Rules/AdminModulAttributeRule.php` (terdaftar di `phpstan.neon.dist`) memvalidasi semua class di `App\Http\Controllers\Admin\` yang extends `Controller`:

| Identifier | Kondisi | Pesan |
|-----------|---------|-------|
| `adminModul.missingAttribute` | Class tanpa `#[AdminModul]` | Controller admin wajib mendeklarasikan attribute |
| `adminModul.duplicateAttribute` | Lebih dari satu attribute di class atau method | Maksimal satu per class / method |
| `adminModul.invalidSlug` | `modul` bukan literal slug lowercase `[a-z0-9-]+` | Harus slug lowercase |

Validasi berjalan di AST level (Node visitor `Class_`), sehingga tidak butuh bootstrap aplikasi dan tidak kena masalah static cache.

## Request Lifecycle (SyaratSuratController — surat_mohon.index)

Berikut adalah lifecycle lengkap untuk request `GET /surat_mohon` (route name: `surat_mohon.index`) sebagai contoh konkret alur eksekusi.

### Step 1: Route Definition & Middleware Registration

**`bootstrap/app.php`** — group middleware `admin` (lihat section 2) didaftarkan via `$middleware->group('admin', [...])`.

**`routes/web.php`** (baris 10) — route admin Laravel memakai group `admin`:
```php
Route::middleware('admin')->group(base_path('routes/admin.php'));
```

**`routes/admin.php`** (baris 8-16) — route spesifik SyaratSuratController:
```php
Route::prefix('surat_mohon')->group(static function (): void {
    Route::get('/', [SyaratSuratController::class, 'index'])
        ->middleware(['can:daftar-persyaratan:baca'])
        ->name('surat_mohon.index');
    // ...
});
```

Route `surat_mohon.index` memiliki total 9 middleware:
1. `auth:admin` — dari grup `admin`
2. `EnsureDesaInstalled` — dari grup `admin`
3. `CheckDesaSetupCompletion` — dari grup `admin`
4. `ShareAnjungan` — dari grup `admin`
5. `ShareAdminModul` — dari grup `admin`
6. `ShareDesaData` — dari grup `admin`
7. `CheckDatabaseMigrations` — dari grup `admin`
8. `TrackDesaActivity` — dari grup `admin`
9. `can:daftar-persyaratan:baca` — dari route spesifik (via `Authorize` middleware)

### Step 2: Middleware Pipeline Execution

Middleware dijalankan secara FIFO sesuai urutan registrasi.

#### Layer 1: `auth:admin` (`Authenticate`)

```php
protected function redirectTo(Request $request): ?string
{
    if ($request->expectsJson()) {
        return null;
    }
    if ($this->auth->guard('admin')->guest()) {
        return 'siteman';           // Redirect ke halaman login admin
    }
    return route('login');          // Fallback (guard non-admin)
}
```
- Jika session admin tidak valid → redirect `302` ke `/siteman`
- Jika valid → `$next($request)` lanjut ke middleware berikutnya

#### Layer 2: `EnsureDesaInstalled`

```php
public function handle(Request $request, Closure $next): Response
{
    if ($redirect = $this->install->execute()) {
        return $redirect;          // Redirect ke instalasi jika desa belum diinstal
    }
    if ($redirect = $this->appKey->execute()) {
        return $redirect;          // Redirect jika app_key tidak valid
    }
    $dbError = $request->session()->get('db_error');
    $this->reconnect->execute($dbError, null); // Reconnect DB jika ada error

    return $next($request);
}
```
- `ValidateInstallAction`: cek apakah desa sudah diinstal (tabel setting, dll)
- `ValidateAppKeyAction`: cek apakah app_key cocok dengan konfigurasi
- `ReconnectAction`: jika session menyimpan `db_error`, coba reconnect database
- Jika semua lolos → `$next($request)`

#### Layer 3: `CheckDesaSetupCompletion`

```php
public function handle(Request $request, Closure $next): Response
{
    $currentController = explode('.', $request->route()?->getName() ?? '')[0];
    // Hasil: 'surat_mohon' — diambil dari route name 'surat_mohon.index'

    if ($redirect = $this->identity->execute($currentController)) {
        return $redirect;              // Redirect ke identitas_desa jika kode_desa kosong
    }

    if (! $this->premium->validasi($currentController, false)) {
        return redirect('peringatan'); // Redirect jika validasi premium gagal
    }
    $validasiPassed = true;

    if ($redirect = $this->forcePassword->execute($currentController, $validasiPassed)) {
        return $redirect;              // Redirect ke pengguna jika force change password
    }

    if ($request->session()->get('change_password') && $currentController !== 'pengguna') {
        return redirect('pengguna');
    }

    $isProduction = app()->environment('production') && ! config('opensid.demo_mode');
    $adminUser    = auth('admin')->user();
    $this->warnings->execute($currentController, $isProduction, $adminUser);

    return $next($request);
}
```
- `ValidateDesaIdentityAction`: cek apakah `kode_desa` sudah diisi, jika kosong redirect ke `identitas_desa`
- `CekService::validasi('surat_mohon', false)`: validasi premium — parameter kedua `false` berarti middleware yang handle redirect (bukan service)
  - `isExceptController('surat_mohon')` → false (tidak ada di daftar kecuali)
  - `isDemoMode()` → false
  - `validasiAkses()`: cek token, decode JWT, verifikasi desa_id, domain, masa berlaku
  - Jika gagal → `return redirect('peringatan')`
- `ForceChangePasswordAction`: jika `validasiPassed && force_change_password`, redirect ke halaman ganti password
- `HandleSetupWarningsAction`: set warning flags ke view (notifikasi setup yang belum selesai)
- Jika semua lolos → `$next($request)`

#### Layer 4: `ShareAnjungan`

```php
public function handle(Request $request, Closure $next): Response
{
    View::share('cek_anjungan', $this->resolveAnjungan->execute(
        $request->session()->get('mac_address'),
        $request->session()->get('anjungan_uuid'),
    ));

    return $next($request);
}
```
- `ResolveCurrentAction::execute()`: cek status anjungan dari session (`mac_address` + `anjungan_uuid`)
- View::share `cek_anjungan` — dulu di-set di constructor `BaseController`, sekarang di middleware terpisah
- Jika semua lolos → `$next($request)`

#### Layer 5: `ShareAdminModul`

```php
public function handle(Request $request, Closure $next): Response
{
    $context    = $this->resolveContext($request);
    $controller = $context->controller ?? $this->routeName($request);
    // Route 'surat_mohon.index' → SyaratSuratController@index
    // #[AdminModul(modul: 'layanan-surat', subModul: 'daftar-persyaratan')]
    // context->modul = 'layanan-surat', context->subModul = 'daftar-persyaratan'
    // controller = 'syaratsurat' (fallback nama class)

    View::share([
        'modul_ini'           => $context->modul ?? $controller,
        'sub_modul_ini'       => $context?->subModul,
        'controller'          => $controller,
        'akses_modul'         => $context?->subModul ?: $context->modul ?? $controller,
        'kategori_pengaturan' => $context?->kategoriPengaturan,
    ]);

    return $next($request);
}
```
- `AdminModul::resolve($route->getControllerClass(), $route->getActionMethod())`: resolusi konteks — attribute method → attribute class (parent & trait) → fallback `name()` (lowercase nama class tanpa suffix `Controller`)
- Fallback untuk closure route: prefix route name (`closure-fallback.index` → `closure-fallback`)
- View::share `modul_ini`, `sub_modul_ini`, `controller`, `akses_modul`, `kategori_pengaturan`
- Jika semua lolos → `$next($request)`

#### Layer 6: `ShareDesaData`

```php
public function handle(Request $request, Closure $next): Response
{
    $setting     = $this->settings->resolveSetting();
    $listSetting = $this->settings->resolvedListSetting();
    $desa        = identitas();
    $currentController = explode('.', $request->route()?->getName() ?? '')[0];
    // Hasil: 'surat_mohon'

    $perbaharuiFlag = self::perbaharuiLangganan();
    PelangganService::perbaruiLangganan($currentController, $perbaharuiFlag !== null);
    // Memanggil service untuk refresh data langganan jika cache expired

    $status_langganan = PelangganService::statusLangganan();
    $user = auth('admin')->user();

    View::share([
        'desa'        => $desa,
        'setting'     => $setting,
        'auth'        => $user,
        'session'     => new \Illuminate\Support\Optional(session()),
        'token_name'  => '_token',
        'token_value' => csrf_token(),
        'notif'       => [
            'langganan'  => $status_langganan,
            'pengumuman' => self::cekPengumuman($user),
        ],
        'notif_categories'     => $user ? NotificationService::getCategories() : [],
        'notif_counts'         => $user ? NotificationService::getNotificationCounts($user) : [],
        'notif_list'           => $user ? NotificationService::getRecentNotifications($user, 10) : [],
        'list_setting'         => $listSetting,
        'perbaharui_langganan' => self::perbaharuiLangganan(),
        'module_name'          => SebutanDesa(...),
        'is_mobile'            => (new UserAgent())->is_mobile(),
    ]);

    View::composer('*', function ($view) use ($setting): void {
        foreach (SettingService::sensitiveKeys() as $key) {
            unset($setting->{$key}); // Hapus key sensitif dari $setting yg di-share
        }
    });

    return $next($request);
}
```
- `resolveSetting()` + `resolvedListSetting()`: ambil semua setting aplikasi dari DB
- `identitas()`: ambil data desa (kode_desa, nama_desa, dll)
- `perbaharuiLangganan()`: jika cache `status_langganan` tidak ada, set flag `$perbaharuiLangganan = true`
- `PelangganService::perbaruiLangganan('surat_mohon', true)`: panggil API pemesanan jika diperlukan
- `PelangganService::statusLangganan()`: dapatkan status langganan dari cache
- `NotificationService`: dapatkan kategori, counts, dan list notifikasi untuk user admin
- `View::share()`: share 13+ variable ke semua Blade view
- `View::composer()`: hapus key sensitif (token, password) dari object setting
- Jika semua lolos → `$next($request)`

#### Layer 7: `CheckDatabaseMigrations`

```php
public function handle(Request $request, Closure $next): Response
{
    $this->migrationCheck->execute();

    return $next($request);
}
```
- `MigrationCheckAction::execute()`: cek apakah ada migrasi database yang belum dijalankan — skip di env testing terjadi di dalam action (`if (app()->environment('testing')) return;`)
- Jika semua lolos → `$next($request)`

#### Layer 8: `TrackDesaActivity`

```php
public function handle(Request $request, Closure $next): Response
{
    $this->trackDesa->execute();

    return $next($request);
}
```
- `TrackDesaAction::execute()`: delegasi ke `Tracker::trackDesa()` — kirim data tracking ke server pantau; skip di env testing terjadi di dalam action
- Jika semua lolos → `$next($request)`

#### Layer 9: `can:daftar-persyaratan:baca` (`Authorize`)

```php
public function handle($request, Closure $next, $ability, ...$models)
{
    if ($request->wantsJson()) {
        return parent::handle($request, $next, $ability, ...$models);
    }

    if ($this->gate->check($ability, $this->getGateArguments($request, $models))) {
        return $next($request);
    }

    $request->session()->flash('error', 'Anda tidak memiliki akses untuk halaman tersebut!');
    return redirect()->to('beranda');
}
```
- Cek Gate `daftar-persyaratan:baca` untuk user yang sedang login
- Jika tidak punya akses → redirect ke `beranda` dengan flash error
- Jika punya akses → `$next($request)`

### Step 3: Controller Construction

Setelah semua middleware lolos, Laravel meng-instantiate controller.

#### Controller::__construct() — tanpa setup tambahan

```php
abstract class Controller extends \Illuminate\Routing\Controller {}
```

Controller admin tidak memiliki constructor setup lagi — `BaseController` dan `BaseAdminController` sudah dihapus. Semua setup (setting, desa, cek_anjungan, migration check, tracker, view share) sudah dijalankan di middleware chain (Step 2).

Konteks modul tidak lagi dibaca dari property — dibaca dari attribute `#[AdminModul]` oleh `ShareAdminModul`:

```php
#[AdminModul(modul: 'layanan-surat', subModul: 'daftar-persyaratan')]
class SyaratSuratController extends Controller { ... }
```

Controller boleh menambahkan middleware spesifik via `$this->middleware(fn($r, $n) {...})` di constructor — contoh `AcakController` (cek super admin + modul aktif).

### Step 4: Controller Method Execution

Laravel memanggil method `index()` pada `SyaratSuratController`:

```php
public function index()
{
    return view('admin.syaratan_surat.index');
}
```

Method ini me-return Blade view. Semua variable yang sudah di-share oleh middleware chain tersedia di view:
- `$desa`, `$setting`, `$auth`, `$session`
- `$notif`, `$notif_categories`, `$notif_counts`, `$notif_list`
- `$list_setting`, `$perbaharui_langganan`, `$module_name`, `$is_mobile`
- `$cek_anjungan`, `$modul_ini`, `$sub_modul_ini`, `$controller`, `$akses_modul`, `$kategori_pengaturan`

### Step 5: Response Return

View di-render menjadi HTML response, kemudian dikirim kembali melalui middleware chain secara reverse order (LIFO):
1. `TrackDesaActivity` — no post-processing
2. `CheckDatabaseMigrations` — no post-processing
3. `ShareDesaData` — post-processing (View::composer sudah jalan)
4. `ShareAdminModul` — no post-processing
5. `ShareAnjungan` — no post-processing
6. `CheckDesaSetupCompletion` — no post-processing
7. `EnsureDesaInstalled` — no post-processing
8. `auth:admin` — no post-processing
9. `can:daftar-persyaratan:baca` — no post-processing

Response dikirim ke browser.

### Lifecycle Diagram (Request → Response)

```
REQUEST: GET /surat_mohon
  │
  ├─ [1] Laravel Bootstrap (index.php → bootstrap/app.php)
  │     ├─ Load middleware config (web groups, aliases, replace)
  │     └─ Load service providers
  │
  ├─ [2] Route Matching (RouteServiceProvider)
  │     └─ Match GET /surat_mohon → surat_mohon.index → SyaratSuratController@index
  │
  ├─ [3] Middleware Pipeline (FIFO)
  │     ├─ 3a. auth:admin ───────────────────────────── Authenticate
  │     │     └─ Cek session → redirect /siteman jika guest
  │     ├─ 3b. EnsureDesaInstalled ───────────────────── Middleware
  │     │     ├─ ValidateInstallAction
  │     │     ├─ ValidateAppKeyAction
  │     │     └─ ReconnectAction (jika db_error)
  │     ├─ 3c. CheckDesaSetupCompletion ──────────────── Middleware
  │     │     ├─ ValidateDesaIdentityAction
  │     │     ├─ CekService::validasi('surat_mohon', false)
  │     │     ├─ ForceChangePasswordAction
  │     │     └─ HandleSetupWarningsAction
  │     ├─ 3d. ShareAnjungan ─────────────────────────── Middleware
  │     │     └─ ResolveCurrentAction → View::share 'cek_anjungan'
  │     ├─ 3e. ShareAdminModul ───────────────────────── Middleware
  │     │     ├─ AdminModul::resolve() → #[AdminModul] attribute
  │     │     └─ View::share: modul_ini='layanan-surat',
  │     │                     sub_modul_ini='daftar-persyaratan',
  │     │                     controller='syaratsurat'
  │     ├─ 3f. ShareDesaData ─────────────────────────── Middleware
  │     │     ├─ resolveSetting() + resolvedListSetting()
  │     │     ├─ identitas()
  │     │     ├─ perbaharuiLangganan()
  │     │     ├─ PelangganService::perbaruiLangganan()
  │     │     ├─ statusLangganan()
  │     │     ├─ NotificationService (categories, counts, list)
  │     │     ├─ View::share (13 variables)
  │     │     └─ View::composer (sensitive keys)
  │     ├─ 3g. CheckDatabaseMigrations ────────────────── Middleware
  │     │     └─ MigrationCheckAction (skip di testing)
  │     ├─ 3h. TrackDesaActivity ──────────────────────── Middleware
  │     │     └─ TrackDesaAction → Tracker::trackDesa()
  │     └─ 3i. can:daftar-persyaratan:baca ────────────── Authorize
  │           └─ Gate check → redirect /beranda jika tidak punya akses
  │
  ├─ [4] Controller Construction
  │     ├─ 4a. Controller::__construct() — base Laravel controller
  │     └─ 4b. SyaratSuratController::__construct() — tanpa setup
  │           (konteks modul dari attribute #[AdminModul], dibaca
  │           oleh ShareAdminModul di langkah 3e)
  │
  ├─ [5] Controller Method
  │     └─ SyaratSuratController::index()
  │           └─ return view('admin.syaratan_surat.index')
  │
  ├─ [6] View Rendering
  │     ├─ Blade compile → HTML
  │     └─ Semua shared variable tersedia ($desa, $setting, $auth, dll)
  │
  └─ [7] Response (LIFO — middleware reverse order)
        └─ HTTP 200 → HTML response ke browser
```

## Consequences

### Positive

✅ **Separation of concerns** — setup desa, premium, password, anjungan, view sharing, migrasi, tracking dipisah per middleware (8 middleware, satu tanggung jawab per middleware)
✅ **Single source of truth** — konteks modul dideklarasikan sekali via `#[AdminModul]` dan di-resolve via `AdminModul::resolve()`; tidak ada property hooks / inheritance base class
✅ **Enforced by PHPStan** — rule `AdminModulAttributeRule` mewajibkan `#[AdminModul]` pada semua controller admin (`adminModul.missingAttribute`), menolak duplikat, dan memvalidasi slug
✅ **Testable** — middleware diuji independen: `ShareAdminModulTest` 11, `ShareAnjunganTest` 1, `AdminMiddlewareChainTest` 8 (replica chain penuh)
✅ **Portable** — controller Laravel-native tidak bergantung pada CI router; `isExceptController()` pakai parameter `$currentController`
✅ **Redirect controlled** — parameter `$redirectOnFail`: caller yang handle redirect
✅ **Config terpusat** — `config/services.opendesa.*`, `config/opensid.*`
✅ **Session/Cache Laravel-native** — `session()->put()`, `cache()->rememberForever()`
✅ **CI helper compatibility** — `CodeIgniterResponseMiddleware` memastikan CI Bootstrap tersedia
✅ **CSRF terlindungi** — MY_Input skip CSRF untuk Laravel-native route via `isLaravelRoute()`; CSRF masing-masing framework pakai token sendiri (ADR-011)
✅ **Constants tersedia global** — PREMIUM, VERSION_BUILD, WEBSITE_DEMO dll di `app/Helpers/core_helper.php`
✅ **Bug operator precedence fixed** — `($flagPerbaharui ?? false) && ...` di `PelangganService::perbaruiLangganan()`
✅ **Null controller di middleware fixed** — `CheckDesaSetupCompletion` / `ShareDesaData` mengekstrak langsung dari `$request->route()?->getName()`; `ShareAdminModul` membaca dari route (bukan `view()->shared()`)
✅ **Backward compatible** — CI controller yang belum dikonversi tetap jalan via `Admin_Controller` (bridge code minimal)
✅ **No duplicate View::share** — `ShareAnjungan` handle `cek_anjungan`; `ShareAdminModul` handle `modul_ini`, `sub_modul_ini`, `controller`, `akses_modul`, `kategori_pengaturan`; `ShareDesaData` handle `setting`, `desa`, `auth`

### Negative

⚠️ **Legacy CI3 tetap di-touch** — `Admin_Controller.php`, `Web_Controller.php`, `MY_Input.php` masih diubah meski prinsip Strangler Fig. Bersifat bridge code minimal yang tidak bisa dihindari selama dual framework.
⚠️ **Dual validation paths** — `CekService::validasi()` dipanggil dari middleware (Laravel) dan `Admin_Controller` (CI); perubahan behaviour harus disinkronisasi
⚠️ **Static cache di `AdminModul`** — `forClass()` / `forMethod()` / `resolve()` memakai static cache yang bocor antar test; wajib `AdminModul::flush()` di teardown (via `beforeApplicationDestroyed()` di `BaseTestCase::setUp()`)

## Implementation Details

### File Changes Summary (implementasi terbaru — `improve-konversi`)

| File | Change | Kategori |
|------|--------|----------|
| `app/Http/Controllers/BaseController.php` | **Dihapus** — setup dipindah ke middleware chain | Legacy cleanup |
| `app/Http/Controllers/Admin/BaseAdminController.php` | **Dihapus** — diganti attribute `#[AdminModul]` | Legacy cleanup |
| `app/Attributes/AdminModul.php` | **Baru** — attribute + resolver single source of truth (`resolve`, `forClass`, `forMethod`, `name`, `flush`) | Laravel baru |
| `app/Http/Controllers/Controller.php` | `extends \Illuminate\Routing\Controller` | Laravel mod |
| `app/Http/Controllers/Admin/SyaratSuratController.php` | Attribute `#[AdminModul]`, constructor setup dihapus | Laravel mod |
| `app/Http/Controllers/Admin/LaporanPerkembanganController.php` | Attribute `#[AdminModul]`, constructor setup dihapus | Laravel mod |
| `app/Http/Controllers/Admin/AcakController.php` | Attribute `#[AdminModul]` + middleware closure spesifik | Laravel mod |
| `app/Http/Middleware/Authenticate.php` | Redirect ke `siteman` untuk admin guard | Laravel mod |
| `app/Http/Middleware/Authorize.php` | **Baru** — redirect ke beranda dengan flash error | Laravel baru |
| `app/Http/Middleware/EnsureDesaInstalled.php` | **Baru** — ValidateInstall, ValidateAppKey, Reconnect | Laravel baru |
| `app/Http/Middleware/CheckDesaSetupCompletion.php` | **Baru** — Validasi premium, identity, password, warnings | Laravel baru |
| `app/Http/Middleware/ShareAnjungan.php` | **Baru** — View::share `cek_anjungan` via `ResolveCurrentAction` | Laravel baru |
| `app/Http/Middleware/ShareAdminModul.php` | **Baru** — View::share konteks modul dari `#[AdminModul]` | Laravel baru |
| `app/Http/Middleware/ShareDesaData.php` | **Baru** — View::share setting, desa, auth, notif | Laravel baru |
| `app/Http/Middleware/CheckDatabaseMigrations.php` | **Baru** — `MigrationCheckAction::execute()` | Laravel baru |
| `app/Http/Middleware/TrackDesaActivity.php` | **Baru** — `TrackDesaAction::execute()` (tracker) | Laravel baru |
| `app/Actions/Tracking/TrackDesaAction.php` | **Baru** — wrapper `Tracker::trackDesa()` (skip di testing) | Laravel baru |
| `app/PHPStan/Rules/AdminModulAttributeRule.php` | **Baru** — rule PHPStan (missing / duplicate / invalid slug) | Laravel baru |
| `phpstan.neon.dist` | Daftarkan `AdminModulAttributeRule` | Laravel mod |
| `routes/web.php` | `Route::middleware('admin')->group(...)` | Laravel mod |
| `bootstrap/app.php` | Middleware groups (`admin`, `codeigniter`), aliases, replace default | Laravel mod |
| `config/services.php` | Tambah `opendesa.server_layanan` | Laravel mod |
| `config/opensid.php` | Tambah `demo_mode` | Laravel mod |
| `app/Helpers/core_helper.php` | **Tambah** — define PREMIUM, VERSION_BUILD, WEBSITE_DEMO, etc; cek_anjungan() | Laravel mod |
| `app/Helpers/general_helper.php` | **Tambah** — can(), isCan(), folder(), folder_desa(), calculate_days(), calculate_date_intervals() | Laravel mod |
| `app/Helpers/opensid_helper.php` | **Tambah** — AmbilVersi(), currentVersion(), favico_desa(), gambar_desa(), isLocalIPAddress() | Laravel mod |
| `app/Helpers/pict_helper.php` | **Tambah** — FOTO_ constants, AmbilFoto(), Foto_Default() | Laravel mod |
| `Modules/Pelanggan/Services/CekService.php` | Refactor: hapus `$ci`, ganti session/cache/config Laravel | Service refactor |
| `Modules/Pelanggan/Services/PelangganService.php` | Refactor: hapus CI_Controller, fix operator precedence | Service refactor |
| `app/Models/SyaratSurat.php` | Repository pattern via SyaratSuratRepository, @deprecated scopes, @property PHPStan fix | Model mod |
| `donjo-app/core/Admin_Controller.php` | `validasi($this->controller)`, cache metadata → `cache()`, `is_demo_mode()` | Bridge code |
| `donjo-app/core/Web_Controller.php` | Closure `use ($controller)`, CI3 cache → Laravel `cache()` | Bridge code |
| `donjo-app/core/MY_Input.php` | Tambah `__construct()` + `isLaravelRoute()` skip CSRF | Bridge code |
| `donjo-app/helpers/core_helper.php` | Constants dihapus (pindah ke Laravel) | Legacy cleanup |
| `donjo-app/helpers/general_helper.php` | Fungsi dipindah/dihapus | Legacy cleanup |
| `donjo-app/helpers/opensid_helper.php` | Fungsi dipindah/dihapus | Legacy cleanup |
| `donjo-app/helpers/pict_helper.php` | Fungsi dipindah/dihapus | Legacy cleanup |
| `tests/BaseTestCase.php` | `beforeApplicationDestroyed()` → `AdminModul::flush()` | Test mod |
| `tests/Feature/Http/Middleware/ShareAdminModulTest.php` | **Baru** — 11 test | Test baru |
| `tests/Feature/Http/Middleware/ShareAnjunganTest.php` | **Baru** — 1 test | Test baru |
| `tests/Feature/Http/Middleware/TrackDesaActivityTest.php` | **Baru** — 3 test (mock `TrackDesaAction`) | Test baru |
| `tests/Feature/Http/Middleware/CheckDatabaseMigrationsTest.php` | **Baru** — 2 test | Test baru |
| `tests/Feature/Actions/Tracking/TrackDesaActionTest.php` | **Baru** — 2 test (skip testing / jalan production) | Test baru |
| `tests/Feature/Http/Controllers/Admin/AdminMiddlewareChainTest.php` | **Baru** — 8 test (replica chain penuh) | Test baru |
| `tests/Feature/Modules/Pelanggan/Services/PelangganServiceTest.php` | **Baru** — 12 test | Test baru |
| `tests/Feature/Modules/Pelanggan/Services/CekServiceTest.php` | **Baru** — 10 test | Test baru |

### Test Results

| Test Group | Status | Assertions |
|-----------|--------|-----------|
| `PelangganServiceTest` | ✅ 12 passed | ~48 |
| `CekServiceTest` | ✅ 10 passed | ~40 |
| `ShareAdminModulTest` | ✅ 11 passed | 45 |
| `ShareAnjunganTest` | ✅ 1 passed | 3 |
| `TrackDesaActivityTest` | ✅ 3 passed | 8 |
| `CheckDatabaseMigrationsTest` | ✅ 2 passed | 5 |
| `TrackDesaActionTest` | ✅ 2 passed | 2 |
| `AdminMiddlewareChainTest` | ✅ 8 passed | 18 |
| Existing test suite (23) | ✅ 23 passed | ~60 |
| **Total** | **✅ 72 passed** | **~229** |

PHPStan full run (termasuk `AdminModulAttributeRule`): `[OK] No errors` — rule terverifikasi menangkap class tanpa attribute, attribute duplikat, dan slug invalid.

## Related ADRs

- [ADR-001: Hybrid Architecture](./ADR-001-hybrid-laravel-codeigniter.md)
- [ADR-003: Custom Bootstrap Process](./ADR-003-custom-bootstrap-process.md)
- [ADR-004: Tiered Routing Strategy](./ADR-004-tiered-routing-strategy.md)
- [ADR-005: Middleware Response Transformation](./ADR-005-middleware-response-transformation.md)
- [ADR-008: Pending Response Pattern](./ADR-008-pending-response-pattern.md)
- [ADR-011: Dual CSRF Protection](./ADR-011-dual-csrf-protection.md)

## References

- `app/Attributes/AdminModul.php`
- `app/PHPStan/Rules/AdminModulAttributeRule.php`
- `app/Http/Controllers/Admin/SyaratSuratController.php`
- `app/Http/Controllers/Admin/LaporanPerkembanganController.php`
- `app/Http/Controllers/Admin/AcakController.php`
- `app/Http/Middleware/CheckDesaSetupCompletion.php`
- `app/Http/Middleware/ShareAnjungan.php`
- `app/Http/Middleware/ShareAdminModul.php`
- `app/Http/Middleware/ShareDesaData.php`
- `app/Http/Middleware/CheckDatabaseMigrations.php`
- `app/Http/Middleware/TrackDesaActivity.php`
- `app/Actions/Tracking/TrackDesaAction.php`
- `Modules/Pelanggan/Services/CekService.php`
- `Modules/Pelanggan/Services/PelangganService.php`
- `donjo-app/core/Admin_Controller.php`
- `donjo-app/core/Web_Controller.php`
- `donjo-app/core/MY_Input.php`
- `routes/web.php`
- `routes/admin.php`
- `config/services.php`
- `config/opensid.php`
- `tests/Feature/Http/Middleware/ShareAdminModulTest.php`
- `tests/Feature/Http/Middleware/ShareAnjunganTest.php`
- `tests/Feature/Http/Controllers/Admin/AdminMiddlewareChainTest.php`

---

**Decision Maker**: Tim Arsitektur OpenSID
**Date**: Juli 2026
**Last Updated**: Agustus 2026 (sinkronisasi dengan implementasi `improve-konversi`)
