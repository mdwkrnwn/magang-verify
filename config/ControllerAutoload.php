<?php

/**
 * ============================================================
 * CONTROLLER AUTOLOADER
 * ============================================================
 * Memuat controller hanya ketika class benar-benar digunakan.
 */

$controllerPath = dirname(__DIR__) . '/controllers';

$controllerMap = [

    /*
    |--------------------------------------------------------------------------
    | ROOT CONTROLLERS
    |--------------------------------------------------------------------------
    */

    'Controller' => $controllerPath . '/Controller.php',
    'LandingPageController' => $controllerPath . '/LandingPageController.php',
    'MahasiswaController' => $controllerPath . '/MahasiswaController.php',
    'MahasiswaProfilController' => $controllerPath . '/MahasiswaProfilController.php',
    'MitraController' => $controllerPath . '/MitraController.php',
    'MitraProfilController' => $controllerPath . '/MitraProfilController.php',
    'TentangController' => $controllerPath . '/TentangController.php',
    'LoginController' => $controllerPath . '/LoginController.php',
    'LogoutController' => $controllerPath . '/LogoutController.php',

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD MAHASISWA
    |--------------------------------------------------------------------------
    */

    'DashboardController' =>
        $controllerPath . '/dashboard/mahasiswa/DashboardController.php',

    'ProfilController' =>
        $controllerPath . '/dashboard/mahasiswa/ProfilController.php',

    'PortofolioController' =>
        $controllerPath . '/dashboard/mahasiswa/PortofolioController.php',

    'SertifikatController' =>
        $controllerPath . '/dashboard/mahasiswa/SertifikatController.php',

    'PengalamanController' =>
        $controllerPath . '/dashboard/mahasiswa/PengalamanController.php',

    'FormasiMagangController' =>
        $controllerPath . '/dashboard/mahasiswa/FormasiMagangController.php',

    'PengajuanController' =>
        $controllerPath . '/dashboard/mahasiswa/PengajuanController.php',

    'LogbookController' =>
        $controllerPath . '/dashboard/mahasiswa/LogbookController.php',

    'LamarController' =>
        $controllerPath . '/dashboard/mahasiswa/LamarController.php',

    'PengaturanController' =>
        $controllerPath . '/dashboard/mahasiswa/PengaturanController.php',

    'LaporanController' =>
        $controllerPath . '/dashboard/mahasiswa/LaporanController.php',

    'MahasiswaPermintaanPembimbingController' =>
        $controllerPath . '/dashboard/mahasiswa/PermintaanPembimbingController.php',

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD DOSEN
    |--------------------------------------------------------------------------
    */

    'DosenDashboardController' =>
        $controllerPath . '/dashboard/dosen/DosenDashboardController.php',

    'MahasiswaBimbinganController' =>
        $controllerPath . '/dashboard/dosen/MahasiswaBimbinganController.php',

    'PengajuanMagangController' =>
        $controllerPath . '/dashboard/dosen/PengajuanMagangController.php',

    'DosenLogbookController' =>
        $controllerPath . '/dashboard/dosen/LogbookController.php',

    'DosenMonitoringController' =>
        $controllerPath . '/dashboard/dosen/DosenMonitoringController.php',

    'PenilaianController' =>
        $controllerPath . '/dashboard/PenilaianController.php',

    'DosenPermintaanPembimbingController' =>
        $controllerPath . '/dashboard/dosen/PermintaanPembimbingController.php',

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD TENDIK
    |--------------------------------------------------------------------------
    */

    'TendikDashboardController' =>
        $controllerPath . '/dashboard/tendik/TendikDashboardController.php',

    'TendikLogbookController' =>
        $controllerPath . '/dashboard/tendik/LogbookController.php',

    'TendikUserController' =>
        $controllerPath . '/dashboard/tendik/UserController.php',

    'TendikVerifikasiDokumenController' =>
        $controllerPath . '/dashboard/tendik/VerifikasiDokumenController.php',

    'TendikPenetapanDosenController' =>
        $controllerPath . '/dashboard/tendik/PenetapanDosenController.php',

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD KOORDINATOR MAGANG
    |--------------------------------------------------------------------------
    */

    'KoordinatorMagangDashboardController' =>
        $controllerPath . '/dashboard/koordinatorMagang/KoordinatorMagangDashboardController.php',

    'KoordinatorMagangLogbookController' =>
        $controllerPath . '/dashboard/koordinatorMagang/LogbookController.php',

    'KoordinatorPenempatanController' =>
        $controllerPath . '/dashboard/koordinatorMagang/PenempatanController.php',

    'KoordinatorPenetapanDosenController' =>
        $controllerPath . '/dashboard/koordinatorMagang/PenetapanDosenController.php',

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD MITRA
    |--------------------------------------------------------------------------
    */

    'MitraDashboardController' =>
        $controllerPath . '/dashboard/mitra/MitraDashboardController.php',

    'MitraLogbookController' =>
        $controllerPath . '/dashboard/mitra/LogbookController.php',

    'MitraPengajuanController' =>
        $controllerPath . '/dashboard/mitra/PengajuanController.php',

    'MitraFormasiController' =>
        $controllerPath . '/dashboard/mitra/FormasiController.php',

    /*
    |--------------------------------------------------------------------------
    | SHARED CONTROLLERS
    |--------------------------------------------------------------------------
    */

    'LogbookFileController' =>
        $controllerPath . '/dashboard/LogbookFileController.php',

    'NotifikasiController' =>
        $controllerPath . '/dashboard/NotifikasiController.php',
];


/*
|--------------------------------------------------------------------------
| REGISTER AUTOLOADER
|--------------------------------------------------------------------------
*/

spl_autoload_register(function (string $class) use ($controllerMap): void {

    // Abaikan class yang tidak terdaftar
    if (!isset($controllerMap[$class])) {
        return;
    }

    $file = $controllerMap[$class];

    // Pastikan file controller tersedia
    if (is_file($file)) {

        // LOG: controller mulai dimuat
        error_log("[AUTOLOAD] Loading: {$class}");

        require_once $file;

        // LOG: controller berhasil dimuat
        error_log("[AUTOLOAD] Successfully loaded: {$class}");

        return;
    }

    // LOG: file controller tidak ditemukan
    error_log("[AUTOLOAD ERROR] Controller file not found: {$file}");
});