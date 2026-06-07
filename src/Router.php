<?php

declare(strict_types=1);

namespace App;

final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function match(array $methods, string $path, callable $handler): void
    {
        foreach ($methods as $method) {
            $this->routes[strtoupper($method)][$path] = $handler;
        }
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/') ?: '/';

        $methodRoutes = $this->routes[$method] ?? [];

        if (isset($methodRoutes[$path])) {
            ($methodRoutes[$path])();
            return;
        }

        foreach ($methodRoutes as $pattern => $handler) {
            $regex = '#^' . preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $pattern) . '$#';
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter(
                    $matches,
                    static fn ($key) => !is_int($key),
                    ARRAY_FILTER_USE_KEY
                );
                $handler($params);
                return;
            }
        }

        http_response_code(404);
        echo 'Page not found';
    }
}
