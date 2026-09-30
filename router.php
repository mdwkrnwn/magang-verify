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

    $mime = mime_content_type($file);

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