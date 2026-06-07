<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;

final class LoginController
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
    ) {
    }

    public function index(): void
    {
        if (request_method() === 'POST') {
            $this->authenticate();
            return;
        }

        render('login/index', ['pageTitle' => 'Enforcement Portal | Login']);
    }

    private function authenticate(): void
    {
        $nic = (int) preg_replace('/[^0-9]/', '', (string) request_post('NIC', ''));
        $password = (string) request_post('password', '');
        $selectedRole = strtolower((string) request_post('role', 'citizen'));

        $user = $this->users->findByNicAndPassword($nic, $password);

        if (!$user) {
            flash('error', 'Invalid NIC or Password.');
            redirect(url('app_login'));
        }

        $userRole = strtolower((string) $user->role);

        if ($selectedRole !== $userRole) {
            flash('error', sprintf('This account is registered as %s. Please select the correct role.', ucfirst($userRole)));
            redirect(url('app_login'));
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_role'] = $userRole;

        if ($userRole === 'admin') {
            redirect(url('app_admin_dashboard'));
        }

        if ($userRole === 'police') {
            redirect(url('app_police'));
        }

        redirect(url('app_citizen'));
    }
}
