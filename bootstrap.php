<?php

declare(strict_types=1);

define('APP_ROOT', __DIR__ . DIRECTORY_SEPARATOR);

$envFile = APP_ROOT . '.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

session_start();

$config = require APP_ROOT . 'config/config.php';

foreach (['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS'] as $var) {
    if (($value = getenv($var)) !== false && $value !== '') {
        $key = match ($var) {
            'DB_HOST' => 'host',
            'DB_PORT' => 'port',
            'DB_NAME' => 'name',
            'DB_USER' => 'user',
            'DB_PASS' => 'pass',
        };
        $config['db'][$key] = $var === 'DB_PORT' ? (int) $value : $value;
    }
}

require APP_ROOT . 'src/helpers.php';
require APP_ROOT . 'src/Database.php';
require APP_ROOT . 'src/Router.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
    $file = APP_ROOT . 'src/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

App\Database::init($config['db']);
