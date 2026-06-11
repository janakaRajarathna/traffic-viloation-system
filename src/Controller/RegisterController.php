<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\VehicleRepository;

final class RegisterController
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly VehicleRepository $vehicles = new VehicleRepository(),
    ) {
    }

    public function index(): void
    {
        render('register/index', ['pageTitle' => 'Register | Civic Flow']);
    }

    public function process(): void
    {
        $fullName = trim((string) request_post('fullName', ''));
        $password = (string) request_post('password', '');
        $role = (string) (request_post('role') ?: 'citizen');
        $licenceNo = request_post('licenceNo');
        $nic = (string) request_post('NIC', '');
        $telNo = (string) request_post('telNo', '');
        $vehicleNo = trim((string) request_post('vehicleNo', ''));

        if ($fullName === '' || $password === '' || $nic === '' || $telNo === '') {
            text_response('Required fields are missing', 400);
        }

        $userId = $this->users->create([
            'full_name' => $fullName,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'licence_no' => $licenceNo !== null && $licenceNo !== '' ? (int) $licenceNo : null,
            'nic' => (int) preg_replace('/[^0-9]/', '', $nic),
            'tel_no' => (int) preg_replace('/[^0-9]/', '', $telNo),
            'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);

        if ($vehicleNo !== '') {
            $this->vehicles->create([
                'vehicle_id' => uniqid('VEH-'),
                'vehicle_no' => $vehicleNo,
                'owner_id' => $userId,
                'model' => 'Not Specified',
                'chassi_no' => 'N/A',
                'eng_no' => 'N/A',
            ]);
        }

        redirect(url('app_login'));
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        redirect(url('app_login'));
    }
}
