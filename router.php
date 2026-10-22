<?php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/function/PrivateStorage.php';
require_once __DIR__ . '/function/PublicAsset.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = rtrim(APP_URL, '/');

if (
    $basePath !== '' &&
    ($path === $basePath || str_starts_with($path, $basePath . '/'))
) {
    $path = substr($path, strlen($basePath));
}

if ($path === '' || $path === false) {
    $path = '/';
}

/*
|--------------------------------------------------------------------------
| Static files
|--------------------------------------------------------------------------
*/

// Private storage is served only through authorized controllers.
if (isPrivateStorageRequestPath((string) $path)) {
    http_response_code(404);
    exit('File tidak ditemukan.');
}

$asset = resolvePublicAsset((string) $path, __DIR__ . '/assets');
if ($asset !== null) {
    header('Content-Type: ' . $asset['mime']);
    header('Content-Length: ' . (string) filesize($asset['path']));
    header('X-Content-Type-Options: nosniff');
    readfile($asset['path']);
    exit;
}

/*
|--------------------------------------------------------------------------
| Application
|--------------------------------------------------------------------------
*/

require __DIR__ . '/index.php';