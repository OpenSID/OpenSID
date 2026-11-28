<?php
// rfm/init.php

error_reporting(0);

define('RFM_BASE_PATH', dirname(__FILE__, 2));

require RFM_BASE_PATH . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable(RFM_BASE_PATH)->load();

$encryptedCookie = $_COOKIE['rfm_access'] ?? null;

if (! $encryptedCookie) {
    http_response_code(401);
    exit('Access denied: No cookie');
}

try {
    $appKey = $_ENV['APP_KEY'] ?? file_get_contents(RFM_BASE_PATH . '/desa/app_key') ?? null;

    if (str_starts_with($appKey, 'base64:')) {
        $appKey = base64_decode(substr($appKey, 7));
    }

    $encrypter = new \Illuminate\Encryption\Encrypter($appKey, $_ENV['APP_CIPHER'] ?? 'AES-256-CBC');

    $data = $encrypter->decrypt($encryptedCookie);

    if (! is_array($data) || ! isset($data['user_id'], $data['expires'])) {
        throw new Exception('Invalid structure');
    }

    if (time() > $data['expires']) {
        throw new Exception('Expired');
    }

    $GLOBALS['RFM_AUTH'] = [
        'fm_key'                 => $data['fm_key'],
        'hapus_gambar_rfm'       => $data['hapus_gambar_rfm'] ?? false,
        'ubah_tambah_gambar_rfm' => $data['ubah_tambah_gambar_rfm'] ?? false,
    ];
} catch (Exception $e) {
    http_response_code(401);
    exit('Access denied: ' . $e->getMessage());
}
