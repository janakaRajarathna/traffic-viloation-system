<?php

declare(strict_types=1);

use App\Controller\AdminController;
use App\Controller\CitizenController;
use App\Controller\CitizenReportController;
use App\Controller\EvidenceController;
use App\Controller\LoginController;
use App\Controller\PoliceController;
use App\Controller\RegisterController;
use App\Controller\ViolationController;
use App\Router;

return static function (Router $router): void {
    $login = new LoginController();
    $register = new RegisterController();
    $citizen = new CitizenController();
    $police = new PoliceController();
    $violation = new ViolationController();
    $citizenReport = new CitizenReportController();
    $admin = new AdminController();
    $evidence = new EvidenceController();

    $router->match(['GET', 'POST'], '/login', fn () => $login->index());
    $router->get('/register', fn () => $register->index());
    $router->post('/register/process', fn () => $register->process());
    $router->get('/logout', fn () => $register->logout());

    $router->get('/citizen', fn () => $citizen->index());
    $router->get('/citizen/profile', fn () => $citizen->profile());
    $router->match(['GET', 'POST'], '/citizen/settings', fn () => $citizen->settings());
    $router->get('/citizen/notifications', fn () => $citizen->notifications());

    $router->get('/police', fn () => $police->index());

    $router->match(['GET', 'POST'], '/violations', fn () => $violation->index());

    $router->match(['GET', 'POST'], '/citizen-reports', fn () => $citizenReport->index());

    $router->get('/admin', fn () => $admin->index());

    $router->get('/evidence/report/{id}', fn (array $p) => $evidence->byReport($p));
    $router->get('/uploads/evidence/{filename}', fn (array $p) => $evidence->show($p));

    $router->get('/', fn () => redirect(url('app_login')));
};
