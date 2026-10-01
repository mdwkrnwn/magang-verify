<?php

/* Login */

require_once __DIR__ . '/../controllers/LoginController.php';

/* Landing Page */

require_once __DIR__ . '/../controllers/LandingPageController.php';
require_once __DIR__ . '/../controllers/MahasiswaController.php';
require_once __DIR__ . '/../controllers/MahasiswaProfilController.php';
require_once __DIR__ . '/../controllers/MitraController.php';
require_once __DIR__ . '/../controllers/MitraProfilController.php';
require_once __DIR__ . '/../controllers/TentangController.php';

/* Dashboard Mahasiswa */

require_once __DIR__ . '/../controllers/dashboard/mahasiswa/DashboardController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/ProfilController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/PortofolioController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/SertifikatController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/FormasiMagangController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/PengajuanController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/LogbookController.php';

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
    | Mitra
    |---------------------
    */

    '/mitra' => [
        'controller' => MitraController::class,
        'method' => 'index',
    ],

    '/mitra/detail' => [
        'controller' => MitraProfilController::class,
        'method' => 'index',
    ],

    '/tentang' => [
        'controller' => TentangController::class,
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

    /*
    |---------------------
    | Dashboard Mahasiswa
    |---------------------
    */

    '/dashboard/mahasiswa' => [
        'controller' => DashboardController::class,
        'method' => 'index',
    ],

    '/dashboard/mahasiswa/profil' => [
        'controller' => ProfilController::class,
        'method' => 'index',
    ],

    '/dashboard/mahasiswa/portofolio' => [
        'controller' => PortofolioController::class,
        'method' => 'index',
    ],

    '/dashboard/mahasiswa/sertifikat' => [
        'controller' => SertifikatController::class,
        'method' => 'index',
    ],

    '/dashboard/mahasiswa/formasi-magang' => [
        'controller' => FormasiMagangController::class,
        'method' => 'index',
    ],

    '/dashboard/mahasiswa/pengajuan' => [
        'controller' => PengajuanController::class,
        'method' => 'index',
    ],

    '/dashboard/mahasiswa/logbook' => [
        'controller' => LogbookController::class,
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
    |-----------------------
    | Profil Mitra
    |-----------------------
    |
    | Contoh:
    | /mitra/detail/pt-semarsoft-technology-indonesia
    |
    */

    if (preg_match(
        '#^/mitra/detail/([^/]+)$#',
        $path,
        $matches
    )) {

        return [
            'route' => [
                'controller' => MitraProfilController::class,
                'method' => 'index',
            ],

            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }

    /*
    |-----------------------
    | Detail Pengajuan
    |-----------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/pengajuan/detail/semarsoft
    |
    */

    if (preg_match(
        '#^/dashboard/mahasiswa/pengajuan/detail/([^/]+)$#',
        $path,
        $matches
    )) {

        return [
            'route' => [
                'controller' => PengajuanController::class,
                'method' => 'detail',
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
