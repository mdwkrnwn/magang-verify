
<?php

/*
|--------------------------------------------------------------------------
| ROUTE DEFINITIONS
|--------------------------------------------------------------------------
|
| Semua controller menggunakan Lazy Loading melalui
| config/ControllerAutoload.php yang didaftarkan di index.php.
|
| Tidak perlu require_once controller di file ini.
|
*/
$routes = [

    '/dashboard/notifikasi' => [
        'controller' => NotifikasiController::class,
        'method' => 'index',
    ],

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

    '/forgot-password' => [
        'controller' => ForgotPasswordController::class,
        'method' => 'index',
    ],

    '/reset-password' => [
        'controller' => ForgotPasswordController::class,
        'method' => 'reset',
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

    '/dashboard/mahasiswa/pembimbing' => [
        'controller' => MahasiswaPermintaanPembimbingController::class,
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
    '/dashboard/dosen/bimbingan/detail/{id}' => [
        'controller' => MahasiswaBimbinganController::class,
        'method' => 'detail',
    ],
    // Menu Verifikasi Laporan Magang
    '/dashboard/dosen/verifikasi-laporan' => [
        'controller' => VerifikasiLaporanController::class,
        'method' => 'index',
    ],

    // Menu Mahasiswa Bimbingan

    '/dashboard/dosen/bimbingan' => [
        'controller' => MahasiswaBimbinganController::class,
        'method' => 'index',
    ],

    '/dashboard/dosen/permintaan-pembimbing' => [
        'controller' => DosenPermintaanPembimbingController::class,
        'method' => 'index',
    ],

    '/dashboard/dosen/pengajuan' => [
        'controller' => PengajuanMagangController::class,
        'method' => 'index',
    ],

    '/dashboard/dosen/monitoring' => [
        'controller' => DosenMonitoringController::class,
        'method' => 'index',
    ],

    '/dashboard/dosen/penilaian' => [
        'controller' => PenilaianController::class,
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


    '/dashboard/tendik/users' => [
        'controller' => TendikUserController::class,
        'method' => 'index',
    ],

    // Menu Verifikasi Laporan Magang
    '/dashboard/tendik/verifikasi/laporan' => [
        'controller' => TendikVerifikasiLaporanController::class,
        'method' => 'index',
    ],

    '/dashboard/tendik/users/create' => [
        'controller' => TendikUserController::class,
        'method' => 'create',
    ],

    '/dashboard/tendik/verifikasi' => [
        'controller' => TendikVerifikasiDokumenController::class,
        'method' => 'index',
    ],
    '/dashboard/tendik/verifikasi/portofolio' => [
        'controller' => TendikVerifikasiDokumenController::class,
        'method' => 'portfolioQueue',
    ],
    '/dashboard/tendik/verifikasi/sertifikat' => [
        'controller' => TendikVerifikasiDokumenController::class,
        'method' => 'certificateQueue',
    ],

    '/dashboard/tendik/penetapan-dosen' => [
        'controller' => TendikPenetapanDosenController::class,
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

    '/dashboard/koordinator-magang/penempatan' => [
        'controller' => KoordinatorPenempatanController::class,
        'method' => 'index',
    ],

    '/dashboard/koordinator-magang/penetapan-dosen' => [
        'controller' => KoordinatorPenetapanDosenController::class,
        'method' => 'index',
    ],

    '/dashboard/koordinator-magang/mitra' => [
        'controller' => KoordinatorMitraController::class,
        'method' => 'index',
    ],
    '/dashboard/koordinator-magang/mitra/tambah' => [
        'controller' => KoordinatorMitraController::class,
        'method' => 'create',
    ],
    '/dashboard/koordinator-magang/mitra/nonaktifkan' => [
        'controller' => KoordinatorMitraController::class,
        'method' => 'deactivate',
    ],
    '/dashboard/koordinator-magang/lowongan' => [
        'controller' => KoordinatorLowonganController::class,
        'method' => 'index',
    ],

    '/dashboard/koordinator-magang/lowongan/tambah' => [
        'controller' => KoordinatorLowonganController::class,
        'method' => 'create',
    ],

    '/dashboard/koordinator-magang/lowongan/status' => [
        'controller' => KoordinatorLowonganController::class,
        'method' => 'updateStatus',
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

    '/dashboard/mitra/formasi' => [
        'controller' => MitraFormasiController::class,
        'method' => 'index',
    ],

    '/dashboard/mitra/pengajuan' => [
        'controller' => MitraPengajuanController::class,
        'method' => 'index',
    ],

    '/dashboard/mitra/penilaian' => [
        'controller' => PenilaianController::class,
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

    // Normalisasi path
    if ($path === '' || $path === false) {
        $path = '/';
    }

    // Hilangkan garis miring ganda
    $path = preg_replace('#/+#', '/', $path);

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

    if (preg_match('#^/dashboard/tendik/verifikasi/(portofolio|sertifikat)/(\d+)$#', $path, $matches)) {
        return [
            'route' => [
                'controller' => TendikVerifikasiDokumenController::class,
                'method' => $matches[1] === 'portofolio' ? 'portfolioDetail' : 'certificateDetail',
            ],
            'params' => ['id' => (int) $matches[2]],
        ];
    }

    if (preg_match('#^/dashboard/tendik/verifikasi/portofolio/(\d+)/sampul$#', $path, $matches)) {
        return [
            'route' => ['controller' => TendikVerifikasiDokumenController::class, 'method' => 'portfolioCover'],
            'params' => ['id' => (int) $matches[1]],
        ];
    }

    if (preg_match('#^/dashboard/tendik/verifikasi/(portofolio|sertifikat)/(\d+)/(keputusan|berkas)(?:/(\d+))?$#', $path, $matches)) {
        $method = $matches[1] === 'portofolio'
            ? ($matches[3] === 'keputusan' ? 'decidePortfolio' : 'portfolioFile')
            : ($matches[3] === 'keputusan' ? 'decideCertificate' : 'certificateFile');
        $params = ['id' => (int) $matches[2]];
        if ($matches[3] === 'berkas') {
            $params['file_id'] = (int) ($matches[4] ?? 0);
        }
        return [
            'route' => ['controller' => TendikVerifikasiDokumenController::class, 'method' => $method],
            'params' => $params,
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

    if (preg_match('#^/mahasiswa/profil/foto/([^/]+)$#', $path, $matches)) {
        return [
            'route' => [
                'controller' => MahasiswaProfilController::class,
                'method' => 'foto',
            ],
            'params' => ['filename' => $matches[1]],
        ];
    }

    if (preg_match('#^/mahasiswa/profil/portofolio/([^/]+)$#', $path, $matches)) {
        return [
            'route' => [
                'controller' => MahasiswaProfilController::class,
                'method' => 'portofolio',
            ],
            'params' => ['filename' => $matches[1]],
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

    if (preg_match('#^/dashboard/mahasiswa/portofolio/cover/([^/]+)$#', $path, $matches)) {
        return [
            'route' => ['controller' => PortofolioController::class, 'method' => 'cover'],
            'params' => ['slug' => $matches[1]],
        ];
    }

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

    if (preg_match('#^/dashboard/mahasiswa/sertifikat/ajukan-ulang/([^/]+)$#', $path, $matches)) {
        return [
            'route' => [
                'controller' => SertifikatController::class,
                'method' => 'ajukanUlang',
            ],
            'params' => ['slug' => $matches[1]],
        ];
    }

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

    if (preg_match('#^/dashboard/mahasiswa/sertifikat/pratinjau/([^/]+)$#', $path, $matches)) {
        return [
            'route' => [
                'controller' => SertifikatController::class,
                'method' => 'preview',
            ],
            'params' => ['slug' => $matches[1]],
        ];
    }

    /*------------------------------------------------------------------
    | Laporan Magang Mahasiswa
    *------------------------------------------------------------------*/

    if (preg_match(
        '#^/dashboard/mahasiswa/laporan/revisi/(\d+)/download$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => LaporanController::class,
                'method' => 'downloadRevision',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }

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

    if (preg_match('#^/dashboard/dosen/monitoring/detail/(\d+)$#', $path, $matches)) {
        (new DosenMonitoringController())->detail(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/dosen/logbook/tanda-tangan/(\d+)$#', $path, $matches)) {
        (new DosenLogbookController())->sign(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/dosen/pengajuan/dokumen/(\d+)$#', $path, $matches)) {
        (new PengajuanMagangController())->document(['id' => (int) $matches[1]]);
        exit;
    }

    if ($path === '/dashboard/mitra/formasi/tambah') {
        (new MitraFormasiController())->create();
        exit;
    }

    if (preg_match('#^/dashboard/mitra/formasi/edit/(\d+)$#', $path, $matches)) {
        (new MitraFormasiController())->edit(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/mitra/formasi/status/(\d+)$#', $path, $matches)) {
        (new MitraFormasiController())->changeStatus(['id' => (int) $matches[1]]);
        exit;
    }

    if (preg_match('#^/dashboard/mitra/formasi/hapus/(\d+)$#', $path, $matches)) {
        (new MitraFormasiController())->delete(['id' => (int) $matches[1]]);
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

    if (preg_match('#^/dashboard/mitra/pengajuan/dokumen/(\d+)$#', $path, $matches)) {
        (new PengajuanMagangController())->document(['id' => (int) $matches[1]]);
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


    if (preg_match(
        '#^/dashboard/dosen/bimbingan/detail/(\d+)$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => MahasiswaBimbinganController::class,
                'method' => 'detail',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
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

    // verifikasi laporann dosen
    if (preg_match(
        '#^/dashboard/dosen/verifikasi-laporan/detail/(\d+)$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => VerifikasiLaporanController::class,
                'method' => 'detail',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }

    // Pratinjau PDF laporan dosen
    if (preg_match(
        '#^/dashboard/dosen/verifikasi-laporan/berkas/(\d+)$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => VerifikasiLaporanController::class,
                'method' => 'previewFile',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }


    // Proses verifikasi laporan dosen
    if (preg_match(
        '#^/dashboard/dosen/verifikasi-laporan/detail/(\d+)/proses$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => VerifikasiLaporanController::class,
                'method' => 'prosesVerifikasi',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }



    // Detail laporan Tendik
    if (preg_match(
        '#^/dashboard/tendik/verifikasi/laporan/detail/(\d+)$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => TendikVerifikasiLaporanController::class,
                'method' => 'detail',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }

    // Pratinjau PDF laporan Tendik
    if (preg_match(
        '#^/dashboard/tendik/verifikasi/laporan/berkas/(\d+)$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => TendikVerifikasiLaporanController::class,
                'method' => 'previewFile',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }

    // Proses keputusan verifikasi Tendik
    if (preg_match(
        '#^/dashboard/tendik/verifikasi/laporan/detail/(\d+)/proses$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => TendikVerifikasiLaporanController::class,
                'method' => 'prosesVerifikasi',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }


    if (preg_match('#^/dashboard/tendik/users/(\d+)$#', $path, $matches)) {
        return [
            'route' => [
                'controller' => TendikUserController::class,
                'method' => 'detail',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }

    if (preg_match(
        '#^/dashboard/koordinator-magang/mitra/edit/(\d+)$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => KoordinatorMitraController::class,
                'method' => 'edit',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }


    // DETAIL LOWONGAN
    if (preg_match(
        '#^/dashboard/koordinator-magang/lowongan/detail/(\d+)$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => KoordinatorLowonganController::class,
                'method' => 'detail',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }

    // EDIT LOWONGAN
    if (preg_match(
        '#^/dashboard/koordinator-magang/lowongan/edit/(\d+)$#',
        $path,
        $matches
    )) {
        return [
            'route' => [
                'controller' => KoordinatorLowonganController::class,
                'method' => 'edit',
            ],
            'params' => [
                'id' => (int) $matches[1],
            ],
        ];
    }


    return null;
}
