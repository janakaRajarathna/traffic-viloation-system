<?php

declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

// Serve existing static files (uploads, etc.) when using PHP built-in server or similar
$staticPath = __DIR__ . $uri;
if ($uri !== '/' && is_file($staticPath)) {
    return false;
}

require dirname(__DIR__) . '/bootstrap.php';

$router = new App\Router();
(require dirname(__DIR__) . '/routes.php')($router);

$router->dispatch(request_method(), $uri);
