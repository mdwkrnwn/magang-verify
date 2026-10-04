<?php

require_once __DIR__ . '/config/app.php';

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

$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {

    $mimeTypes = [
        'css'   => 'text/css',
        'js'    => 'application/javascript',
        'json'  => 'application/json',

        'html'  => 'text/html',
        'htm'   => 'text/html',

        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'webp'  => 'image/webp',
        'svg'   => 'image/svg+xml',
        'ico'   => 'image/x-icon',

        'pdf'   => 'application/pdf',

        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'otf'   => 'font/otf',
    ];

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    $mime = $mimeTypes[$extension] ?? 'application/octet-stream';

    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($file));

    readfile($file);

    exit;
}

/*
|--------------------------------------------------------------------------
| Application
|--------------------------------------------------------------------------
*/

require __DIR__ . '/index.php';