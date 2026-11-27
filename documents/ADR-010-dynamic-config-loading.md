# ADR-010: Dynamic Config Loading dari CI Controllers

## Status
**Proposed** - Belum Diimplementasikan

### Catatan Implementasi
- **Status Saat Ini**: Tidak diimplementasikan
- **Yang Ada Sekarang**: Hanya controller `$setting` property yang di-sync (email, telegram, recaptcha)
- **Alasan Belum Implementasi**: Tidak ada kebutuhan urgent untuk sync CI config files ke Laravel config
- **Dokumentasi Ini**: Catatan desain untuk implementasi masa depan jika diperlukan

## Konteks

CodeIgniter controllers sering load config files secara dinamis:

```php
class PendudukController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load CI config dinamis
        $this->config->load('custom_config');
        
        // Access config
        $setting = $this->config->item('some_setting');
    }
}
```

**Laravel Pattern:**
```php
// Static config loaded saat bootstrap
$setting = config('custom_config.some_setting');
```

**Masalah Potensial:**

1. **Timing Mismatch** - CI loads config during controller execution, Laravel di bootstrap
2. **Config Namespace** - Laravel uses dot notation, CI uses file-based loading
3. **Cache Incompatibility** - `php artisan config:cache` caches static configs only
4. **Path Differences** - CI: `donjo-app/config/`, Laravel: `config/`
5. **Dynamic Values** - CI config files bisa contain logic, Laravel expect arrays

**Status Saat Ini:**

Sistem hybrid **tidak memerlukan** sync CI config ke Laravel karena:
- CI controllers menggunakan `$this->config->load()` yang tetap berfungsi
- Laravel code menggunakan `config()` helper untuk Laravel configs
- Kedua sistem berjalan independent tanpa konflik
- **Hanya controller settings** (email, telegram, recaptcha) yang perlu di-sync untuk keperluan Laravel mailers/services

**Yang Sudah Diimplementasikan:**

```php
// app/Providers/RouteServiceProvider.php

protected function configureConfig()
{
    Event::listen(CallingController::class, function (CallingController $event) {
        $setting = $event->controller->setting ?? null;
        
        if (! $setting) {
            return;
        }
        
        // HANYA sync setting properties yang diperlukan Laravel
        
        // Configure email SMTP
        if (isset($setting->email_smtp_url)) {
            config(['mail.default' => $setting->email_protocol]);
        }
        
        // Configure Telegram
        if (isset($setting->telegram_token)) {
            config(['services.telegram-bot-api.token' => $setting->telegram_token]);
        }
        
        // Configure reCAPTCHA
        if (isset($setting->google_recaptcha_secret_key)) {
            config(['captcha' => [...]]);
        }
    });
}
```

**Catatan**: Ini BUKAN sync CI config files, hanya property `$setting` dari controller instance.

## Keputusan (Untuk Masa Depan)

**Jika diperlukan di masa depan**, implementasi dynamic config synchronization dari CI ke Laravel dengan pattern berikut:

### Strategi yang Diusulkan

1. **Allow CI config loading** as-is (backward compatible)
2. **Sync loaded CI configs** to Laravel config on-demand via event listener
3. **Maintain separation** - Don't auto-load all CI configs
4. **Cache static configs** - Only dynamic configs bypass cache

### Implementasi yang Diusulkan (Belum Diterapkan)

```php
// app/Providers/RouteServiceProvider.php

protected function syncCIConfigsToLaravel(): void
{
    // Event listener untuk config sync
    $this->app['events']->listen(CallingController::class, function ($event) {
        // Sync loaded CI configs if available
        $CI = &get_instance();
        if (isset($CI->config)) {
            $ciConfigs = $CI->config->config ?? [];
            
            foreach ($ciConfigs as $key => $value) {
                // Skip Laravel native configs
                if (in_array($key, ['database', 'app', 'session'])) {
                    continue;
                }
                
                // Set to Laravel config dengan namespace ci.*
                config()->set("ci.{$key}", $value);
            }
        }
    });
}
```

**Catatan Penting:**
- Method ini **TIDAK ADA** di implementasi saat ini
- Hanya `configureConfig()` yang ada (untuk sync controller settings)
- Dokumentasi ini adalah proposal untuk future implementation

### Alasan Tidak Diimplementasikan

1. **Tidak Ada Kebutuhan** - CI code menggunakan CI config, Laravel code menggunakan Laravel config, tidak ada overlap
2. **Complexity vs Benefit** - Menambah kompleksitas tanpa benefit jelas
3. **Performance** - Extra overhead untuk sync configs yang mungkin tidak pernah diakses Laravel
4. **Maintenance** - Satu lagi moving part yang perlu di-maintain

## Konsekuensi (Jika Diimplementasikan)

### Positif

✅ **Backward Compatible**
- CI controllers work unchanged
- `$this->config->load()` still works
- `$this->config->item()` still works

✅ **Laravel Access**
```php
// After CI controller loads config
$value = config('ci.custom_config.setting');
```

✅ **Minimal Overhead**
- Only syncs when needed
- Lazy loading preserved
- Cache still works for static configs

✅ **Clear Namespace**
- CI configs under `ci.*` namespace
- No conflicts with Laravel configs
- Easy to identify source

### Negatif

⚠️ **Config Cache Limitation**
```bash
# This caches Laravel configs only
php artisan config:cache

# CI dynamic configs bypass cache
```

⚠️ **Synchronization Timing**
- CI must load config before Laravel can access
- Not available during bootstrap
- Request-scoped only

⚠️ **Memory Usage**
- Config duplication (CI + Laravel)
- ~10-50KB per loaded config

⚠️ **Debugging Complexity**
- Config could be in multiple places
- Source not always obvious
- Precedence rules matter

⚠️ **Maintenance Burden**
- One more integration point to maintain
- Potential for config sync bugs
- Testing complexity increases

## Alternatives Considered

### Alternative 1: Auto-Load All CI Configs

```php
// Load all CI configs at bootstrap
foreach (glob(APPPATH . 'config/*.php') as $file) {
    $config = include $file;
    config()->set('ci.' . basename($file, '.php'), $config);
}
```

**Rejected** karena:
- Loads unnecessary configs
- Performance hit (100+ config files)
- May have side effects
- Breaks lazy loading

### Alternative 2: CI Config Adapter

```php
class CIConfigAdapter implements ArrayAccess
{
    public function offsetGet($key) {
        $CI = get_instance();
        return $CI->config->item($key);
    }
}

config()->set('ci', new CIConfigAdapter());
```

**Rejected** karena:
- Complex implementation
- Cache incompatible
- Harder to debug

### Alternative 3: Require All CI Configs Use Laravel

```php
// Force migration
$this->config->load('file'); // ❌ Not allowed

config('file.key'); // ✅ Must use Laravel
```

**Rejected** karena:
- Breaking changes
- Massive refactoring needed
- Against hybrid approach

### Alternative 4: Config Service Facade

```php
// Unified config service
ConfigService::get('file.key'); // Works for both
```

**Rejected** karena:
- Over-engineering
- Adds layer of indirection
- Confusing for developers

## Implementasi Saat Ini (Yang Sudah Ada)

### Controller Settings Synchronization

Yang **benar-benar diimplementasikan** adalah sync controller `$setting` property untuk keperluan Laravel services:

```php
// app/Providers/RouteServiceProvider.php

protected function configureConfig()
{
    Event::listen(CallingController::class, function (CallingController $event) {
        $setting = $event->controller->setting ?? null;
        
        if (! $setting) {
            return;
        }
        
        // Configure email SMTP
        if (isset($setting->email_smtp_url)) {
            $protocol = $setting->email_protocol;
            config([
                'mail.default' => $protocol,
                "mail.mailers.{$protocol}" => [
                    'transport'    => $protocol,
                    'url'          => $setting->email_smtp_url,
                    'host'         => $setting->email_smtp_host,
                    'port'         => $setting->email_smtp_port,
                    'encryption'   => $setting->email_smtp_encryption ?? 'tls',
                    'username'     => $setting->email_smtp_user,
                    'password'     => $setting->email_smtp_pass,
                    'timeout'      => $setting->email_smtp_timeout,
                    'local_domain' => $setting->email_smtp_domain,
                ],
            ]);
        }
        
        // Configure Telegram
        if (isset($setting->telegram_token)) {
            config(['services.telegram-bot-api.token' => $setting->telegram_token]);
        }
        
        // Configure reCAPTCHA
        if (isset($setting->google_recaptcha_secret_key)) {
            config([
                'captcha' => [
                    'secret'          => $setting->google_recaptcha_secret_key,
                    'sitekey'         => $setting->google_recaptcha_site_key,
                    'options'         => [],
                    'version'         => $setting->google_recaptcha_version ?? 'v2',
                    'score_threshold' => $setting->google_recaptcha_score_threshold ?? 0.5,
                ],
            ]);
        }
    });
}
```

**Ini Bukan Config Sync!** Ini hanya mapping property dari controller instance ke Laravel config untuk services tertentu (mail, telegram, captcha).

## Kapan Implementasi Ini Diperlukan?

### Scenario 1: Laravel Code Perlu Akses CI Config

```php
// CI Controller loads config
$this->config->load('custom_module_config');

// Laravel middleware/service perlu akses config yang sama
$moduleSettings = config('ci.custom_module_config');
```

**Solusi Saat Ini**: Tidak perlu. CI code tidak perlu di-akses dari Laravel.

### Scenario 2: Shared Configuration

```php
// Both CI and Laravel need same config values
$apiKey = config('services.external_api.key'); // Laravel way
$apiKey = $this->config->item('external_api_key'); // CI way
```

**Solusi Saat Ini**: Duplicate config in both systems jika benar-benar diperlukan.

### Scenario 3: Migration Phase

Saat migrating CI controllers ke Laravel, mungkin butuh akses CI configs temporary.

**Solusi yang Lebih Baik**: Migrate configs ke Laravel format sekaligus dengan controller migration.

## Kesimpulan

**Status**: Proposal/Future Enhancement, bukan implementasi aktual

**Rekomendasi**:
- Jangan implement sampai ada kebutuhan konkret
- Pertimbangkan alternatif (duplicate configs, migrate configs)
- Jika implement, gunakan pattern yang didokumentasikan di ADR ini

**Related ADRs**:
- [ADR-007: Service Container Extensions](./ADR-007-service-container-extensions.md) - Implementasi actual controller settings sync
- [ADR-008: Pending Response Pattern](./ADR-008-pending-response-pattern.md) - Event system usage

---

**Decision Maker**: Tim Arsitektur OpenSID  
**Date**: Februari 2024  
**Status**: Proposal (Belum Diimplementasikan)  
**Last Updated**: Januari 2025
