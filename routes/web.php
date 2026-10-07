<?php

/* Authenticator */

require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../controllers/LogoutController.php';

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
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/PengalamanController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/FormasiMagangController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/PengajuanController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/LogbookController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/LamarController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/PengaturanController.php';
require_once __DIR__ . '/../controllers/dashboard/LogbookFileController.php';
require_once __DIR__ . '/../controllers/dashboard/mahasiswa/LaporanController.php';

/* Dashboard Dosen */

require_once __DIR__ . '/../controllers/dashboard/dosen/DosenDashboardController.php';

/* Dashboard Tendik */

require_once __DIR__ . '/../controllers/dashboard/tendik/TendikDashboardController.php';

/* Dashboard Koordinator Magang */

require_once __DIR__ . '/../controllers/dashboard/koordinatorMagang/KoordinatorMagangDashboardController.php';

/* Dashboard Mitra */

require_once __DIR__ . '/../controllers/dashboard/mitra/MitraDashboardController.php';

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

    /*
    |---------------------
    | Tentang
    |---------------------
    */

    '/tentang' => [
        'controller' => TentangController::class,
        'method' => 'index',
    ],

    /*
    |---------------------
    | Authenticator
    |---------------------
    */

    '/login' => [
        'controller' => LoginController::class,
        'method' => 'index',
    ],

    '/logout' => [
        'controller' => LogoutController::class,
        'method' => 'index',
    ],

    /*
    |---------------------
    | Dashboard Mahasiswa
    |---------------------
    */

    // Menu Dashboard

    '/dashboard/mahasiswa' => [
        'controller' => DashboardController::class,
        'method' => 'index',
    ],

    // Menu Profil Saya

    '/dashboard/mahasiswa/profil' => [
        'controller' => ProfilController::class,
        'method' => 'index',
    ],

    // Menu Portofolio

    '/dashboard/mahasiswa/portofolio' => [
        'controller' => PortofolioController::class,
        'method' => 'index',
    ],

    // Menu Sertifikat

    '/dashboard/mahasiswa/sertifikat' => [
        'controller' => SertifikatController::class,
        'method' => 'index',
    ],

    // Menu Pengalaman

    '/dashboard/mahasiswa/pengalaman' => [
        'controller' => PengalamanController::class,
        'method' => 'index',
    ],

    // Menu Formasi Magang

    '/dashboard/mahasiswa/formasi-magang' => [
        'controller' => FormasiMagangController::class,
        'method' => 'index',
    ],

    // Menu Pengajuan Saya

    '/dashboard/mahasiswa/pengajuan' => [
        'controller' => PengajuanController::class,
        'method' => 'index',
    ],

    // Menu Logbook

'/dashboard/mahasiswa/logbook' => [
    'controller' => LogbookController::class,
    'method' => 'index',
],

// Menu Laporan Magang

'/dashboard/mahasiswa/laporan' => [
    'controller' => LaporanController::class,
    'method' => 'index',
],

'/dashboard/mahasiswa/laporan/tambah' => [
    'controller' => LaporanController::class,
    'method' => 'create',
],

'/dashboard/mahasiswa/laporan/detail/{id}' => [
    'controller' => LaporanController::class,
    'method' => 'detail',
],

'/dashboard/mahasiswa/laporan/template/{id}' => [
    'controller' => LaporanController::class,
    'method' => 'downloadTemplate',
],

'/dashboard/mahasiswa/laporan/download/{id}' => [
    'controller' => LaporanController::class,
    'method' => 'download',
],

    // Menu Setting

    '/dashboard/mahasiswa/pengaturan' => [
        'controller' => PengaturanController::class,
        'method' => 'index',
    ],

    // Menu Logbook Dosen

    '/dashboard/dosen/logbook' => [
        'controller' => DosenLogbookController::class,
        'method' => 'index',
    ],

    // Menu Logbook Mitra

    '/dashboard/mitra/logbook' => [
        'controller' => MitraLogbookController::class,
        'method' => 'index',
    ],

    // Menu Logbook Tendik

    '/dashboard/tendik/logbook' => [
        'controller' => TendikLogbookController::class,
        'method' => 'index',
    ],

    // Menu Logbook Koordinator Magang

    '/dashboard/koordinator-magang/logbook' => [
        'controller' => KoordinatorMagangLogbookController::class,
        'method' => 'index',
    ],

    /*
    |---------------------
    | Dashboard Dosen

    |---------------------
    */

    // Menu Dashboard

    '/dashboard/dosen' => [
        'controller' => DosenDashboardController::class,
        'method' => 'index',
    ],

    /*
    |---------------------
    | Dashboard Tendik
    |---------------------
    */

    // Menu Dashboard

    '/dashboard/tendik' => [
        'controller' => TendikDashboardController::class,
        'method' => 'index',
    ],

    /*
    |---------------------
    | Dashboard Koordinator Magang
    |---------------------
    */

    // Menu Dashboard

    '/dashboard/koordinator-magang' => [
        'controller' => KoordinatorMagangDashboardController::class,
        'method' => 'index',
    ],

    /*
    |---------------------
    | Dashboard Mitra
    |---------------------
    */

    // Menu Dashboard

    '/dashboard/mitra' => [
        'controller' => MitraDashboardController::class,
        'method' => 'index',
    ],

];

/*
|-------------------------
| Router
|-------------------------
*/

function route(string $uri): ?array
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

    if (
        $basePath !== '' &&
        ($path === $basePath || str_starts_with($path, $basePath . '/'))
    ) {
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
    | CV Mahasiswa Publik
    |-----------------------
    |
    | Contoh:
    | /mahasiswa/profil/arif-hidayat/cv
    |
    */

    if (preg_match(
        '#^/mahasiswa/profil/([^/]+)/cv$#',
        $path,
        $matches
    )) {

        return [
            'route' => [
                'controller' => MahasiswaProfilController::class,
                'method' => 'cv',
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
    |-----------------------
    | Detail Formasi Magang
    |-----------------------
    |
    | Contoh:
    | Pengalaman
    |---------------------
    | /dashboard/mahasiswa/pengalaman
    | /dashboard/mahasiswa/pengalaman/tambah
    | /dashboard/mahasiswa/pengalaman/edit/1
    | /dashboard/mahasiswa/pengalaman/hapus/1
    */

    if ($path === '/dashboard/mahasiswa/pengalaman') {
        (new PengalamanController())->index();
        exit;
    }

    if ($path === '/dashboard/mahasiswa/pengalaman/tambah') {
        $controller = new PengalamanController();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $controller->store();
        }
        $controller->create();
        exit;
    }

    if (preg_match('#^/dashboard/mahasiswa/pengalaman/edit/(\d+)$#', $path, $matches)) {
        $controller = new PengalamanController();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $controller->update(['id' => (int) $matches[1]]);
        }
        $controller->edit(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/mahasiswa/pengalaman/hapus/(\d+)$#', $path, $matches)) {
        (new PengalamanController())->delete(['id' => (int) $matches[1]]);
        exit;
    }

    /*
    | /dashboard/mahasiswa/formasi-magang/detail/semarsoft
    |
    */

    if (preg_match(
        '#^/dashboard/mahasiswa/formasi-magang/detail/([^/]+)$#',
        $path,
        $matches
    )) {

        return [
            'route' => [
                'controller' => FormasiMagangController::class,
                'method' => 'detail',
            ],

            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }

    /*
    |-----------------------
    | Lamar / Pendaftaran Magang
    |-----------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/lamar/semarsoft
    |
    */

    if (preg_match(
        '#^/dashboard/mahasiswa/lamar/([^/]+)$#',
        $path,
        $matches
    )) {

        return [
            'route' => [
                'controller' => LamarController::class,
                'method' => 'index',
            ],

            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }

    /*
    |-----------------------
    | Detail Portofolio
    |-----------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/portofolio/detail/website-e-commerce-sederhana
    |
    */

    if (preg_match(
        '#^/dashboard/mahasiswa/portofolio/detail/([^/]+)$#',
        $path,
        $matches
    )) {

        return [
            'route' => [
                'controller' => PortofolioController::class,
                'method' => 'detail',
            ],

            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }

    /*
    |-----------------------
    | Tambah Portofolio
    |-----------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/portofolio/tambah
    |
    */

    if ($path === '/dashboard/mahasiswa/portofolio/tambah') {

        return [
            'route' => [
                'controller' => PortofolioController::class,
                'method' => 'tambah',
            ],

            'params' => [],
        ];
    }

    /*
    |-----------------------
    | Edit Portofolio
    |-----------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/portofolio/edit
    |
    */

    if (preg_match(
        '#^/dashboard/mahasiswa/portofolio/edit/([^/]+)$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => PortofolioController::class,
                'method' => 'edit',
            ],
            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }

    /*
    |-----------------------
    | Hapus Portofolio
    |-----------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/portofolio/hapus
    |
    */

    if (
        $path !== '' &&
        preg_match(
            '#^/dashboard/mahasiswa/portofolio/hapus/([^/]+)$#',
            $path,
            $matches
        )
    ) {
        return [
            'route' => [
                'controller' => PortofolioController::class,
                'method' => 'hapus',
            ],
            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }


    /*
    |-------------------------
    | Detail Sertifikat
    |-------------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/sertifikat/detail/web-development-basic
    |
    */

    if (preg_match(
        '#^/dashboard/mahasiswa/sertifikat/detail/([^/]+)$#',
        $path,
        $matches
    )) {

        return [
            'route' => [
                'controller' => SertifikatController::class,
                'method' => 'detail',
            ],

            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }


    /*
    |--------------------------\
    | Tambah Sertifikat
    |---------------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/sertifikat/tambah
    |
    */

    if ($path === '/dashboard/mahasiswa/sertifikat/tambah') {

        return [
            'route' => [
                'controller' => SertifikatController::class,
                'method' => 'tambah',
            ],

            'params' => [],
        ];
    }


    /*
    |--------------------------
    | Edit Sertifikat
    |--------------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/sertifikat/edit/web-development-basic
    |
    */

    if (preg_match(
        '#^/dashboard/mahasiswa/sertifikat/edit/([^/]+)$#',
        $path,
        $matches
    )) {

        return [
            'route' => [
                'controller' => SertifikatController::class,
                'method' => 'edit',
            ],

            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }


    /*
    |-------------------------
    | Hapus Sertifikat
    |-------------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/sertifikat/hapus/web-development-basic
    |
    */

    if (
        $path !== '' &&
        preg_match(
            '#^/dashboard/mahasiswa/sertifikat/hapus/([^/]+)$#',
            $path,
            $matches
        )
    ) {

        return [
            'route' => [
                'controller' => SertifikatController::class,
                'method' => 'hapus',
            ],

            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }


    /*
    |----------------------------
    | Download Sertifikat
    |----------------------------
    |
    | Contoh:
    | /dashboard/mahasiswa/sertifikat/download/web-development-basic
    |
    */

    if (preg_match(
        '#^/dashboard/mahasiswa/sertifikat/download/([^/]+)$#',
        $path,
        $matches
    )) {

        return [
            'route' => [
                'controller' => SertifikatController::class,
                'method' => 'download',
            ],

            'params' => [
                'slug' => $matches[1],
            ],
        ];
    }

    /*------------------------------------------------------------------
    | Laporan Magang Mahasiswa
    *------------------------------------------------------------------*/

if (preg_match(
    '#^/dashboard/mahasiswa/laporan/detail/(\d+)$#',
    $path,
    $matches
)) {
    return [
        'route' => [
            'controller' => LaporanController::class,
            'method' => 'detail',
        ],
        'params' => [
            'id' => (int) $matches[1],
        ],
    ];
}


if (preg_match(
    '#^/dashboard/mahasiswa/laporan/template/(\d+)$#',
    $path,
    $matches
)) {
    return [
        'route' => [
            'controller' => LaporanController::class,
            'method' => 'downloadTemplate',
        ],
        'params' => [
            'id' => (int) $matches[1],
        ],
    ];
}


if (preg_match(
    '#^/dashboard/mahasiswa/laporan/download/(\d+)$#',
    $path,
    $matches
)) {
    return [
        'route' => [
            'controller' => LaporanController::class,
            'method' => 'download',
        ],
        'params' => [
            'id' => (int) $matches[1],
        ],
    ];
}

    /*
    |-----------------------
    | Logbook Mahasiswa
    |-----------------------
    */

    

    if ($path === '/dashboard/mahasiswa/logbook/tambah') {
        (new LogbookController())->create();
        exit;
    }

    if (preg_match('#^/dashboard/mahasiswa/logbook/detail/(\d+)$#', $path, $matches)) {
        (new LogbookController())->detail(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/mahasiswa/logbook/hari/tambah/(\d+)$#', $path, $matches)) {
        (new LogbookController())->dailyCreate(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/mahasiswa/logbook/hari/edit/(\d+)$#', $path, $matches)) {
        (new LogbookController())->dailyEdit(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/mahasiswa/logbook/tanda-tangan/(\d+)$#', $path, $matches)) {
        (new LogbookController())->sign(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/mahasiswa/logbook/download/(\d+)$#', $path, $matches)) {
        (new LogbookController())->downloadWeek(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/mahasiswa/logbook/download-semua/(\d+)$#', $path, $matches)) {
        (new LogbookController())->downloadAll(['id' => (int) $matches[1]]);
        exit;
    }

    /*
    |-----------------------
    | Logbook Dosen
    |-----------------------
    */

    if (preg_match('#^/dashboard/dosen/logbook/detail/(\d+)$#', $path, $matches)) {
        (new DosenLogbookController())->detail(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/dosen/logbook/tanda-tangan/(\d+)$#', $path, $matches)) {
        (new DosenLogbookController())->sign(['id' => (int) $matches[1]]);
        exit;
    }

    /*
    |-----------------------
    | Logbook Mitra
    |-----------------------
    */

    if (preg_match('#^/dashboard/mitra/logbook/detail/(\d+)$#', $path, $matches)) {
        (new MitraLogbookController())->detail(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/mitra/logbook/tanda-tangan/(\d+)$#', $path, $matches)) {
        (new MitraLogbookController())->sign(['id' => (int) $matches[1]]);
        exit;
    }

    /*
    |-----------------------
    | Logbook Tendik
    |-----------------------
    */

    if (preg_match('#^/dashboard/tendik/logbook/detail/(\d+)$#', $path, $matches)) {
        (new TendikLogbookController())->detail(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/tendik/logbook/validasi/(\d+)$#', $path, $matches)) {
        (new TendikLogbookController())->validate(['id' => (int) $matches[1]]);
        exit;
    }

    /*
    |-----------------------
    | Logbook Koordinator Magang
    |-----------------------
    */

    if (preg_match('#^/dashboard/koordinator-magang/logbook/detail/(\d+)$#', $path, $matches)) {
        (new KoordinatorMagangLogbookController())->detail(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/koordinator-magang/logbook/validasi/(\d+)$#', $path, $matches)) {
        (new KoordinatorMagangLogbookController())->validate(['id' => (int) $matches[1]]);
        exit;
    }

    /*
    |-----------------------
    | File privat Logbook
    |-----------------------
    */

    if (preg_match('#^/dashboard/logbook/file/(signature|evidence)/(\d+)$#', $path, $matches)) {
        $controller = new LogbookFileController();
        if ($matches[1] === 'signature') {
            $controller->signature(['id' => (int) $matches[2]]);
        } else {
            $controller->evidence(['id' => (int) $matches[2]]);
        }
        exit;
    }

    /*
    |----------------------
    | Tidak ditemukan
    |----------------------
    */

    return null;
}
