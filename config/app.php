<?php

require_once __DIR__ . '/debug.php';

date_default_timezone_set('Asia/Jakarta');

define('APP_URL', '/magang-verify');

/*
|--------------------------------------------------------------------------
| Session Security
|--------------------------------------------------------------------------
*/

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

// Aktifkan hanya jika aplikasi diakses melalui HTTPS.
$isHttps = !empty($_SERVER['HTTPS'])
    && $_SERVER['HTTPS'] !== 'off';

ini_set('session.cookie_secure', $isHttps ? '1' : '0');
