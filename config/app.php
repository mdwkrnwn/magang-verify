<?php

require_once __DIR__ . '/debug.php';

date_default_timezone_set('Asia/Jakarta');

if (!defined('APP_URL')) {
    define('APP_URL', '/magang-verify');
}

/*
|--------------------------------------------------------------------------
| Session Security
|--------------------------------------------------------------------------
*/

// Konfigurasi session hanya boleh dilakukan sebelum session aktif.
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');

    // Aktifkan hanya jika aplikasi menggunakan HTTPS.
    $isHttps = !empty($_SERVER['HTTPS'])
        && $_SERVER['HTTPS'] !== 'off';

    ini_set('session.cookie_secure', $isHttps ? '1' : '0');
}
