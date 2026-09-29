<?php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/function/Helpers.php';
require_once __DIR__ . '/routes/web.php';


$matchedRoute = route($_SERVER['REQUEST_URI']);

if (!$matchedRoute) {
    require __DIR__ . '/pages/errors/404.php';
    exit;
}

$route = $matchedRoute['route'];
$params = $matchedRoute['params'];


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