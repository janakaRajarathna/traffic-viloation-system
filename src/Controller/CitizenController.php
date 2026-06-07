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

        render('citizen/settings', [
            'pageTitle' => 'Settings | Civic Flow',
            'user' => $user,
        ]);
    }
}
