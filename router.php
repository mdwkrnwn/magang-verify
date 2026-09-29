<?php

require_once __DIR__ . '/config/app.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = rtrim(APP_URL, '/');

/*
|--------------------------------------------------------------------------
| Hilangkan base URL dari path
|--------------------------------------------------------------------------
*/

if (
    $basePath !== '' &&
    str_starts_with($path, $basePath)
) {
    $path = substr($path, strlen($basePath));
}

if ($path === '' || $path === false) {
    $path = '/';
}

/*
|--------------------------------------------------------------------------
| Serve static files directly
|--------------------------------------------------------------------------
*/

$file = __DIR__ . $path;

if (
    $path !== '/' &&
    is_file($file)
) {
    return false;
}

/*
|--------------------------------------------------------------------------
| Semua request lainnya ke front controller
|--------------------------------------------------------------------------
*/

require __DIR__ . '/index.php';