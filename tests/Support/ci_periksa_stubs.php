<?php

/*
 * Stub helper CI3 (namespace global) yang dibutuhkan App\Libraries\Periksa saat
 * dijalankan di harness test — di sana bootstrap CI3 tidak tersedia sehingga
 * ci_auth()/kades()/sekdes() belum terdefinisi. HANYA untuk pengujian.
 *
 * Semua diberi guard function_exists agar aman di-require berulang kali dan tidak
 * bentrok bila suatu saat harness benar-benar memuat helper CI3.
 */

if (! function_exists('ci_auth')) {
    function ci_auth($params = null)
    {
        // id null → App\Libraries\Periksa akan fallback ke User::first()->id.
        return (object) ['id' => null];
    }
}

if (! function_exists('kades')) {
    function kades()
    {
        // Truthy → Periksa menganggap jabatan kades ada (tidak membuat side-effect).
        return (object) ['id' => 1];
    }
}

if (! function_exists('sekdes')) {
    function sekdes()
    {
        return (object) ['id' => 1];
    }
}

if (! function_exists('setting')) {
    function setting($key = null)
    {
        return null;
    }
}
