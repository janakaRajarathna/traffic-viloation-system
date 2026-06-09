<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CitizenReportRepository;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use App\Repository\ViolationRepository;

final class CitizenController
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly ViolationRepository $violations = new ViolationRepository(),
        private readonly CitizenReportRepository $reports = new CitizenReportRepository(),
        private readonly VehicleRepository $vehicles = new VehicleRepository(),
    ) {
    }

    private function requireUser(): object
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            redirect(url('app_login'));
        }

        $user = $this->users->findById((int) $userId);
        if (!$user) {
            redirect(url('app_login'));
        }

        return $user;
    }

    public function index(): void
    {
        $user = $this->requireUser();
        $hasLicense = $user->licenceNo !== null;
        $violations = [];
        $outstandingTotal = 0;
        $pendingViolationCount = 0;

        if ($hasLicense) {
            $violations = $this->violations->findRecentByDriver((int) $user->id);
            $outstandingTotal = $this->violations->sumOutstandingByDriver((int) $user->id);
            $pendingViolationCount = $this->violations->countPendingByDriver((int) $user->id);
        }

        $citizenReports = $this->reports->findRecentByUser((int) $user->id, 10);
        $reportsCount = $this->reports->countByUser((int) $user->id);

        render('citizen/index', [
            'pageTitle' => 'Dash Cam | Citizen Dashboard',
            'user' => $user,
            'hasLicense' => $hasLicense,
            'violations' => $violations,
            'citizenReports' => $citizenReports,
            'outstandingTotal' => $outstandingTotal,
            'pendingViolationCount' => $pendingViolationCount,
            'reportsCount' => $reportsCount,
        ]);
    }

    public function profile(): void
    {
        $user = $this->requireUser();
        $user->vehicles = $this->vehicles->findByOwnerId((int) $user->id);

        render('citizen/profile', [
            'pageTitle' => 'Profile | Civic Flow',
            'user' => $user,
        ]);
    }

    public function settings(): void
    {
        $user = $this->requireUser();

        if (request_method() === 'POST') {
            $action = (string) request_post('action', '');

            if ($action === 'update_profile') {
                $fullName = trim((string) request_post('full_name', ''));
                $telNo = (string) request_post('tel_no', '');
                $licenceNo = request_post('licence_no');

                if ($fullName === '' || $telNo === '') {
                    flash('error', 'Full Name and Telephone Number are required.');
                } else {
                    $telNoInt = (int) preg_replace('/[^0-9]/', '', $telNo);
                    $licenceNoInt = ($licenceNo !== null && trim((string) $licenceNo) !== '') ? (int) preg_replace('/[^0-9]/', '', (string) $licenceNo) : null;
                    
                    $this->users->updateProfile((int) $user->id, $fullName, $licenceNoInt, $telNoInt);
                    flash('success', 'Personal details updated successfully.');
                }
                redirect(url('app_citizen_settings'));
            }

            if ($action === 'update_password') {
                $currentPassword = (string) request_post('current_password', '');
                $newPassword = (string) request_post('new_password', '');
                $confirmPassword = (string) request_post('confirm_password', '');

                if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
                    flash('error', 'All password fields are required.');
                } elseif ($currentPassword !== $user->password) {
                    flash('error', 'Current password is incorrect.');
                } elseif ($newPassword !== $confirmPassword) {
                    flash('error', 'New password and confirm password do not match.');
                } else {
                    $this->users->updatePassword((int) $user->id, $newPassword);
                    flash('success', 'Password updated successfully.');
                }
                redirect(url('app_citizen_settings'));
            }
        }

        render('citizen/settings', [
            'pageTitle' => 'Settings | Civic Flow',
            'user' => $user,
        ]);
    }
}
