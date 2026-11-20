<?php
// router-testing.php — router script for `php -S` used by the Playwright test
// harness. Two responsibilities:
//  1. Serve real static files (css/js/images) directly — without this, every
//     asset request gets routed through index.php's CI3 dispatch instead of
//     being served as a plain file, breaking styling/JS (anti-csrf.js included).
//  2. Bridge CI_ENV from getenv() into $_SERVER/$_ENV before index.php runs —
//     Umum's own index.php resolves ENVIRONMENT from $_ENV['CI_ENV'] /
//     $_SERVER['CI_ENV'] directly (before Laravel/Dotenv loads), but PHP's
//     built-in server does NOT copy shell-exported env vars into $_SERVER/
//     $_ENV for incoming requests (only getenv() reflects them).
$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $path;
if ($path !== '/' && is_file($file)) {
    return false;
}

$_SERVER['CI_ENV'] = getenv('CI_ENV') ?: 'testing';
$_ENV['CI_ENV']    = $_SERVER['CI_ENV'];

require __DIR__ . '/index.php';
