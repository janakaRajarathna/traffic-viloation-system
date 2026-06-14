<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $route, array $params = []): string
{
    $routes = [
        'app_login' => '/login',
        'app_register' => '/register',
        'app_register_process' => '/register/process',
        'app_logout' => '/logout',
        'app_citizen' => '/citizen',
        'app_citizen_profile' => '/citizen/profile',
        'app_citizen_settings' => '/citizen/settings',
        'app_police' => '/police',
        'app_violations' => '/violations',
        'app_citizen_reports' => '/citizen-reports',
        'app_admin_dashboard' => '/admin',
        'app_evidence_report' => '/evidence/report/{id}',
        'app_evidence_file' => '/uploads/evidence/{filename}',
    ];

    $path = $routes[$route] ?? $route;

    foreach ($params as $key => $value) {
        $path = str_replace('{' . $key . '}', (string) $value, $path);
    }

    return $path;
}

function redirect(string $location): never
{
    header('Location: ' . $location);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['_flash'][$type][] = $message;
}

function get_flashes(): array
{
    $flashes = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);

    return $flashes;
}

function render(string $view, array $data = [], ?string $layout = 'layouts/base'): void
{
    extract($data, EXTR_SKIP);
    $viewFile = APP_ROOT . 'views/' . $view . '.php';

    if (!is_file($viewFile)) {
        http_response_code(500);
        echo 'View not found: ' . e($view);
        exit;
    }

    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    if ($layout === null) {
        echo $content;
        return;
    }

    $layoutFile = APP_ROOT . 'views/' . $layout . '.php';
    if (!is_file($layoutFile)) {
        echo $content;
        return;
    }

    require $layoutFile;
}

function request_method(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function request_post(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $default;
}

function request_file(string $key): ?array
{
    if (!isset($_FILES[$key]) || !is_array($_FILES[$key]) || ($_FILES[$key]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    return $_FILES[$key];
}

function json_response(mixed $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function text_response(string $body, int $status = 200): never
{
    http_response_code($status);
    echo $body;
    exit;
}

function map_user(array $row): object
{
    return (object) [
        'id' => (int) $row['id'],
        'fullName' => $row['full_name'],
        'password' => $row['password'],
        'role' => $row['role'],
        'licenceNo' => $row['licence_no'] !== null ? (string) $row['licence_no'] : null,
        'NIC' => (int) $row['nic'],
        'telNo' => (int) $row['tel_no'],
        'profilePic' => $row['profile_pic'] ?? null,
        'createdAt' => $row['created_at'] ? new DateTimeImmutable($row['created_at']) : null,
        'vehicles' => [],
    ];
}

function map_violation(array $row): object
{
    return (object) [
        'id' => (int) $row['id'],
        'vehicleId' => $row['vehicle_id'],
        'driverId' => (int) $row['driver_id'],
        'violationType' => $row['violation_type'],
        'fineAmount' => (int) $row['fine_amount'],
        'status' => $row['status'],
        'description' => $row['description'],
        'location' => $row['location'],
        'vehicleNumber' => $row['vehicle_number'],
        'incidentDate' => $row['incident_date'] ? new DateTime($row['incident_date']) : null,
        'createdAt' => $row['created_at'] ? new DateTime($row['created_at']) : null,
    ];
}

function map_report(array $row): object
{
    return (object) [
        'id' => (int) $row['id'],
        'incidentDate' => $row['incident_date'] ? new DateTimeImmutable($row['incident_date']) : null,
        'location' => $row['location'],
        'description' => $row['description'],
        'evidencePath' => $row['evidence_path'],
        'status' => $row['status'],
        'createdAt' => $row['created_at'] ? new DateTimeImmutable($row['created_at']) : null,
        'userId' => $row['user_id'] !== null ? (int) $row['user_id'] : null,
    ];
}

function map_vehicle(array $row): object
{
    return (object) [
        'id' => (int) $row['id'],
        'vehicleId' => $row['vehicle_id'],
        'vehicleNo' => $row['vehicle_no'],
        'ownerId' => (int) $row['owner_id'],
        'model' => $row['model'],
        'chassiNo' => $row['chassi_no'],
        'engNo' => $row['eng_no'],
    ];
}

function parse_ini_size(string $val): int
{
    $val = trim($val);
    if ($val === '') {
        return 0;
    }
    $last = strtolower($val[strlen($val) - 1]);
    $val = (int) $val;
    switch ($last) {
        case 'g':
            $val *= 1024;
            // fallthrough
        case 'm':
            $val *= 1024;
            // fallthrough
        case 'k':
            $val *= 1024;
            // fallthrough
    }
    return $val;
}

function get_max_upload_size(): int
{
    $phpMax = parse_ini_size(ini_get('upload_max_filesize'));
    $postMax = parse_ini_size(ini_get('post_max_size'));
    $appMax = 5 * 1024 * 1024; // 5MB limit inside our app

    $limits = array_filter([$phpMax, $postMax, $appMax]);
    return min($limits);
}

function format_bytes(int $bytes, int $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
