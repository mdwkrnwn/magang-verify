<?php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/function/Helpers.php';
require_once __DIR__ . '/routes/web.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';

$matchedRoute = route($_SERVER['REQUEST_URI']);

if (!$matchedRoute) {
    require __DIR__ . '/pages/errors/404.php';
    exit;
}

$route = $matchedRoute['route'];
$params = $matchedRoute['params'];

/*
|--------------------------------------------------------------------------
| Middleware Authorization
|--------------------------------------------------------------------------
*/

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = rtrim(APP_URL, '/');

if (
    $basePath !== '' &&
    ($path === $basePath || str_starts_with($path, $basePath . '/'))
) {
    $path = substr($path, strlen($basePath));
}

if (!$path) {
    $path = '/';
}

$path = $path === '/' ? '/' : rtrim($path, '/');

$protectedRoutes = [
    '/dashboard/mahasiswa' => 'mahasiswa',
    '/dashboard/dosen' => 'dosen',
    '/dashboard/koordinator-magang' => 'koordinator_magang',
    '/dashboard/tendik' => 'tendik',
    '/dashboard/mitra' => 'mitra',
];

foreach ($protectedRoutes as $prefix => $requiredRole) {
    if (
        $path === $prefix ||
        str_starts_with($path, $prefix . '/')
    ) {
        AuthMiddleware::handle($requiredRole);
        break;
    }
}

/*
|--------------------------------------------------------------------------
| View
|--------------------------------------------------------------------------
*/

if (!empty($route['view'])) {
    require __DIR__ . '/' . $route['view'];
    exit;
}

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
*/

if (
    !empty($route['controller']) &&
    !empty($route['method'])
) {
    $controllerClass = $route['controller'];
    $method = $route['method'];

    $controller = new $controllerClass();

    $controller->$method($params);
    exit;
}

/*
|--------------------------------------------------------------------------
| Invalid Route
|--------------------------------------------------------------------------
*/

http_response_code(500);

echo '<h1>500 - Konfigurasi Route Tidak Valid</h1>';

exit;