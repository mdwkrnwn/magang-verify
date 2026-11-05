
<?php

$GLOBALS['perf_start'] = microtime(true);
$GLOBALS['perf_last'] = $GLOBALS['perf_start'];

function perfMark(string $label): void
{
    $now = microtime(true);

    error_log(sprintf(
        '[PERF] %-32s | step: %.3f s | total: %.3f s',
        $label,
        $now - $GLOBALS['perf_last'],
        $now - $GLOBALS['perf_start']
    ));

    $GLOBALS['perf_last'] = $now;
}

error_log('[PERF] START ' . ($_SERVER['REQUEST_URI'] ?? ''));

register_shutdown_function(function (): void {
    error_log(sprintf(
        '[PERF] END | Total PHP: %.3f s | Peak memory: %.1f MB',
        microtime(true) - $GLOBALS['perf_start'],
        memory_get_peak_usage(true) / 1048576
    ));
});

require_once __DIR__ . '/config/app.php';
perfMark('index: config/app.php');

require_once __DIR__ . '/function/Helpers.php';
perfMark('index: Helpers.php');

// Controller Lazy Loading
require_once __DIR__ . '/config/ControllerAutoload.php';
perfMark('index: ControllerAutoload.php');

require_once __DIR__ . '/routes/web.php';
perfMark('index: routes/web.php loaded');

// require_once __DIR__ . '/middleware/AuthMiddleware.php';
// perfMark('index: AuthMiddleware.php');
$matchedRoute = route($_SERVER['REQUEST_URI']);
perfMark('index: route matching');

if (!$matchedRoute) {
    require __DIR__ . '/pages/errors/404.php';
    exit;
}
perfMark('index: before session');
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
perfMark('index: after session');
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
    if ($path === $prefix || str_starts_with($path, $prefix . '/')) {

        // Middleware hanya dimuat untuk halaman yang dilindungi.
        require_once __DIR__ . '/middleware/AuthMiddleware.php';
        perfMark('index: AuthMiddleware.php loaded');

        perfMark('index: before AuthMiddleware');

        AuthMiddleware::handle($requiredRole);

        perfMark('index: after AuthMiddleware');

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

    // TEST LAZY LOADING
    error_log(
        '[AUTOLOAD TEST] Declared controller classes: ' .
        json_encode(array_values(array_filter(
            get_declared_classes(),
            fn($class) => str_contains($class, 'Controller')
        )))
    );

    $controller = new $controllerClass();

    perfMark('index: before controller method');

    $controller->$method($params);

    perfMark('index: after controller method');

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