<?php

use App\Models\Config;
use Modules\Pelanggan\Services\PelangganService;

/**
 * PREMIUM
 *
 * Versi OpenSID Premium
 */
define('PREMIUM', false);

/**
 * Version Build Script
 */
define('VERSION_BUILD', [
    // Version Build => Version (premium)
    '2026.08.20' => '2608.0.2',
    '2026.08.12' => '2608.0.1',
    '2026.08.01' => '2608.0.0',
    '2026.07.27' => '2607.1.0',
    '2026.07.15' => '2607.0.1',
    '2026.07.01' => '2607.0.0',
    '2026.06.26' => '2606.1.0',
    '2026.06.01' => '2606.0.0',
    '2026.05.15' => '2605.0.1',
    '2026.05.01' => '2605.0.0',
    '2026.04.15' => '2604.0.1',
    '2026.04.01' => '2604.0.0',
    '2026.03.15' => '2603.0.1',
    '2026.03.01' => '2603.0.0',
    '2026.02.01' => '2602.0.0',
    '2026.01.15' => '2601.0.1',
    '2026.01.01' => '2601.0.0',
    '2025.12.15' => '2512.0.1',
    '2025.12.01' => '2512.0.0',
    '-'          => '-',
    null         => '-',
]);

// Rentang waktu premium masuk umum (dalam bulan)
define('RANGE_PREMIUM_MASUK_UMUM', 12);

/**
 * Minimum versi script yang bisa melakukan migrasi, backup dan restore database ke versi ini
 */
define('MINIMUM_VERSION_BUILD', '2025.12.01');

// Website Demo OpenSID
define('WEBSITE_DEMO', [
    'beta.opendesa.id',
    'beta2.opensid.or.id',
    'berputar.opendesa.id',
    'devpremium.opendesa.id',
    'opensid-premium.test',
    'opensid.test',
    'pelatihan-opensid.opendesa.id',
    'localhost',
    '127.0.0.1',
    'devtestpremium.opendesa.id',
    'premium.test',
]);

// Modul bawaan OpenSID
define('MODUL_BAWAAN', [
    'Anjungan',
    'Analisis',
    'BukuTamu',
    'Kehadiran',
    'Pelanggan',
    'Lapak',
    'DTSEN',
]);

// Tema premium gratis
define('TEMA_PREMIUM_FREE', []);

if (! function_exists('is_demo_mode')) {
    /**
     * Cek apakah aplikasi berjalan dalam mode demo.
     */
    function is_demo_mode(): bool
    {
        return app()->environment(['development', 'testing']) || (config('opensid.demo_mode') && in_array(get_domain(APP_URL), WEBSITE_DEMO));
    }
}

if (! function_exists('cek_anjungan')) {
    /**
     * Cek status anjungan.
     */
    function cek_anjungan(): bool
    {
        return app(App\Services\Kapabilitas\GerbangFitur::class)->mengizinkan('anjungan');
    }
}

if (! function_exists('set_app_key')) {
    /**
     * Generate a new application key.
     */
    function set_app_key(): string
    {
        return 'base64:' . base64_encode(random_bytes(32));
    }
}

if (! function_exists('get_app_key')) {
    /**
     * Retrieve the application key, generating one if absent.
     */
    function get_app_key(): string
    {
        if ($app_key = env('APP_KEY')) {
            return $app_key;
        }

        $path = base_path('desa/app_key');

        if (is_file($path) && trim(file_get_contents($path))) {
            return trim(file_get_contents($path));
        }

        $app_key = set_app_key();

        file_put_contents($path, $app_key, LOCK_EX);

        return $app_key;
    }
}

if (! function_exists('identitas')) {
    /**
     * Get identitas desa, optionally returning a single attribute.
     *
     * @return ($params is null ? object|null : mixed)
     */
    function identitas(?string $params = null): mixed
    {
        $identitas = cache()->remember(
            'identitas_desa',
            604800,
            static fn () => Config::appKey()->first()
        );

        return $params ? $identitas?->{$params} : $identitas;
    }
}


if (! function_exists('token_bursa')) {
    /**
     * Token autentikasi ke bursa (marketplace) add-on.
     *
     * Accessor netral yang memusatkan pembacaan token pemasang add-on core
     * (ModuleManager/LayananHttpSource). Hilang dari sumber pra-refaktor;
     * ditambahkan pipeline rilis Umum (patch-wiring-addon-umum.php).
     */
    function token_bursa(): string
    {
        return (string) setting('layanan_opendesa_token');
    }
}
