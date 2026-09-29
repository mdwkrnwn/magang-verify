<?php

require_once __DIR__ . '/../controllers/LandingPageController.php';
require_once __DIR__ . '/../controllers/MahasiswaController.php';
require_once __DIR__ . '/../controllers/MahasiswaProfilController.php';
require_once __DIR__ . '/../controllers/LoginController.php';


$routes = [

    /*
    |------------------
    | Beranda
    |------------------
    */

    '/' => [
        'controller' => LandingPageController::class,
        'method' => 'index',
    ],


    /*
    |---------------------
    | Mahasiswa
    |---------------------
    */

    '/mahasiswa' => [
        'controller' => MahasiswaController::class,
        'method' => 'index',
    ],


    /*
    |---------------------
    | Login
    |---------------------
    */

    '/login' => [
        'controller' => LoginController::class,
        'method' => 'index',
    ],
];


/*
|-------------------------
| Router
|-------------------------
*/

function route($uri)
{
    global $routes;

    $path = parse_url($uri, PHP_URL_PATH);

    /*
    |---------------------
    | Hilangkan Base URL
    |---------------------
    |
    | REQUEST_URI:
    | /magang-verify/mahasiswa
    |
    | menjadi:
    | /mahasiswa
    |
    */

    $basePath = rtrim(APP_URL, '/');

    if ($basePath !== '' && str_starts_with($path, $basePath)) {
        $path = substr($path, strlen($basePath));
    }


    /*
    |----------------------
    | Normalisasi Path
    |----------------------
    */

    if ($path === '' || $path === false) {
        $path = '/';
    }

    if ($path !== '/') {
        $path = rtrim($path, '/');
    }


    /*
    |----------------------
    | Static Route
    |----------------------
    */

    if (isset($routes[$path])) {
        return [
            'route' => $routes[$path],
            'params' => [],
        ];
    }


    /*
    |-----------------------
    | Profil Mahasiswa
    |------------------------
    |
    | Contoh:
    | /mahasiswa/profil/ahmad-rizki
    |
    */

    if (preg_match(
        '#^/mahasiswa/profil/([^/]+)$#',
        $path,
        $matches
    )) {

        return [
            'route' => [
                'controller' => MahasiswaProfilController::class,
                'method' => 'index',
            ],

            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }


    /*
    |----------------------
    | Tidak ditemukan
    |----------------------
    */

    return null;
}
