<?php

use App\Models\Config;

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
